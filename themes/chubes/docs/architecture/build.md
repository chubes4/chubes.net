# # Build script (./build.sh)

- Purpose: packages the theme into a production zip file suitable for WordPress installation.
- Behavior: copies repository files into a temporary build directory and creates a zip in /build/ while excluding development files.
- Exclusions: the script excludes .git*, .DS_Store, build/, dist/, build.sh, CLAUDE.md, README.md, CLAUDE.md, .claude/, and other developer files (see build.sh for exact excludes).
- Output: /build/chubes.zip (the build script prints the path on success).

Notes

- This repository does not include a Node.js build pipeline or package.json; assets are edited directly.
- Uses standardized build process following monorepo conventions.