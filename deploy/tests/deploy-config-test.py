#!/usr/bin/env python3
"""Every component directory is attached to the deploy project, and every
attachment points at a real component whose homeboy.json agrees on id and
remote_path."""
import json
import pathlib
import sys

root = pathlib.Path(__file__).resolve().parents[2]
project = json.loads((root / "deploy/homeboy/projects/chubes-net/chubes-net.json").read_text())
attached = {c["local_path"]: c for c in project["components"]}
errors = []

dirs = sorted(
    str(p.parent.relative_to(root))
    for pattern in ("themes/*/homeboy.json", "plugins/*/homeboy.json")
    for p in root.glob(pattern)
)
for d in dirs:
    if d not in attached:
        errors.append(f"{d} has a homeboy.json but is not attached to the chubes-net project")

for local_path, c in attached.items():
    manifest = root / local_path / "homeboy.json"
    if not manifest.exists():
        errors.append(f"{local_path}: attached but has no homeboy.json")
        continue
    m = json.loads(manifest.read_text())
    if m.get("id") != c["id"]:
        errors.append(f"{local_path}: id {m.get('id')!r} != project {c['id']!r}")
    if m.get("remote_path") != c["remote_path"]:
        errors.append(f"{local_path}: remote_path {m.get('remote_path')!r} != project {c['remote_path']!r}")
    if pathlib.PurePosixPath(c["remote_path"]).name != pathlib.PurePosixPath(local_path).name:
        errors.append(f"{local_path}: directory name must match the installed slug in {c['remote_path']}")

# WordPress stores active plugins as "<dir>/<main file>". Renaming either one
# silently deactivates the plugin on deploy. These match production's
# active_plugins option; change one only together with a migration.
PLUGIN_MAIN_FILES = {
    "plugins/chubes-contact": "chubes-contact.php",
    "plugins/poem-block": "my-first-block.php",
    "plugins/visualizer-block": "visualizer-block.php",
}
for local_path in attached:
    if not local_path.startswith("plugins/"):
        continue
    main = PLUGIN_MAIN_FILES.get(local_path)
    if main is None:
        errors.append(f"{local_path}: add its main plugin file to PLUGIN_MAIN_FILES")
    elif "Plugin Name:" not in (root / local_path / main).read_text() if (root / local_path / main).exists() else True:
        errors.append(f"{local_path}/{main}: missing, or has no Plugin Name header")

server = json.loads((root / "deploy/homeboy/servers" / f"{project['server_id']}.json").read_text())
if server.get("user") == "root":
    errors.append("deploy server user must be the restricted deploy user, not root")

for e in errors:
    print(f"FAIL: {e}")
if errors:
    sys.exit(1)
print(f"OK: {len(attached)} components attached")
