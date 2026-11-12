# chubes.net

Source for [chubes.net](https://chubes.net): the theme and the site's own plugins in one repository. Same model as the [Extra Chill monorepo](https://github.com/Extra-Chill/extrachill), sized for a personal site.

## Layout

Directory names are WordPress slugs: `plugins/<slug>` deploys to `wp-content/plugins/<slug>`. Renaming a directory deactivates the plugin, so names change only together with a migration.

| Component | Path | What it is |
|---|---|---|
| `chubes` | `themes/chubes` | The site theme (Journal, homepage, templates) |
| `chubes-contact` | `plugins/chubes-contact` | Contact form block + REST endpoint |
| `poem-block` | `plugins/poem-block` | Paginated poem block |
| `visualizer-block` | `plugins/visualizer-block` | Interactive audio-reactive particle canvas ([/visualizer](https://chubes.net/visualizer)) |

Each component has its own `homeboy.json`, version, `docs/CHANGELOG.md` and `<component-id>-vX.Y.Z` tags.

Not here: plugins with their own release cycle (Data Machine, Intelligence, DocSync, Lean SEO, wp-coding-agents) and third-party plugins.

| Path | What it is |
|---|---|
| `deploy/homeboy/` | Homeboy config root: the `chubes-net` deploy project and server |
| `deploy/tests/` | Deploy config contract test |

## CI

`homeboy.yml` runs each changed component's own lint and tests (homeboy-action `components: changed`) with differential gating, so only new findings fail. The `Site infrastructure` job checks the deploy config.

## Releases

On every merge, `release.yml` releases each component that has commits since its own tag: version bump, changelog from conventional commit subjects (`feat:`, `fix:`, ...) and a GitHub Release. Don't edit changelogs by hand.

## Deploys

Merging to `main` deploys it. `deploy.yml` runs after Release, builds `main`, and deploys every component whose build differs from what is installed (`homeboy deploy chubes-net --ref <sha> --outdated`). Rolling back means deploying an older commit (Actions → Deploy → `sha`).

- Unattended runs touch the server only when the repository variable `DEPLOY_AUTOMATION` is `enabled`. `gh variable set DEPLOY_AUTOMATION --body paused` turns them back into plan-only runs.
- Manual runs default to a dry run.
- The server is reached as a dedicated `deploy` user with write access limited to these components' directories. Secrets: `CHUBES_DEPLOY_SSH_KEY`, `CHUBES_DEPLOY_KNOWN_HOSTS`.

## Building locally

```bash
cd plugins/<slug>
npm ci
npm run build
```
