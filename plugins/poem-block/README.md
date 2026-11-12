# Poem Block

Gutenberg block (`create-block/poem-block`) that shows a poem one page at a time with previous/next navigation. Used by the poetry posts on chubes.net.

```bash
npm ci
npm run build   # src/ -> build/
```

The main file is still the scaffold name `my-first-block.php`, because WordPress records active plugins by file path and renaming it would deactivate the plugin. The block name `create-block/poem-block` stays for the same reason: published posts reference it.
