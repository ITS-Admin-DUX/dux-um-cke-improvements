## INTRODUCTION

`um_cke_improvements` is a custom Drupal module that standardizes and extends
CKEditor 5 behavior for U-M sites.

It provides two primary improvements:

- Alters CKEditor 5 plugin definitions at runtime to allow the Image URL option
  to be available when image upload is enabled.
- Overrides the default CKEditor 5 image upload controller route with a custom
  controller implementation for upload handling and validation.

It also ships editor configuration defaults for `full_html` and
`filtered_html` text formats.

## WHAT THIS MODULE CHANGES

### CKEditor 5 plugin definition changes

Using `hook_ckeditor5_plugin_info_alter()` in
`um_cke_improvements.module`, the module:

- Removes `drupal.conditions.imageUploadStatus` from
  `ckeditor5_imageUrl`, so the Image URL integration is not hidden by that
  condition.
- Adds `url` to `ckeditor5_imageUpload` image insert integrations:
  `ckeditor5.config.image.insert.integrations[]`.

This is the programmatic replacement for patching
`core/modules/ckeditor5/ckeditor5.ckeditor5.yml`.

### Upload route/controller changes

Using a route subscriber (`UmCkeImprovementsRouteSubscriber`), the module
replaces the core `ckeditor5.upload_image` controller with:

- `\Drupal\um_cke_improvements\Controller\CKEditor5ImageController::upload`

This is done so the upload flow runs through the module controller, where token
replacements can be applied to the upload destination path (for example,
`inline-images/[date:custom:Y]/[date:custom:m]`), while keeping access checks
and upload validation behavior aligned with CKEditor 5.

## REQUIREMENTS

Core:

- Drupal `^10 || ^11`

Contributed modules required by this module (declared in
`um_cke_improvements.info.yml` / `composer.json`):

- `ckeditor5_plugin_pack`
  - `ckeditor5_plugin_pack_auto_image`
  - `ckeditor5_plugin_pack_find_and_replace`
  - `ckeditor5_plugin_pack_paste_markdown`
  - `ckeditor5_plugin_pack_select_all`
  - `ckeditor5_plugin_pack_templates`
  - `ckeditor5_plugin_pack_word_count`
- `ckeditor_codemirror`
- `editor_advanced_image`
- `editor_advanced_link`
- `highlightjs_input_filter`
- `hotkeys_for_save`
- `anchor_link`

## MODULES INSTALLED/ENABLED

This module does not manually install other modules in code.

When you enable `um_cke_improvements`, Drupal will automatically require/enable
its declared dependencies (if available in the codebase), including the
contributed modules listed above.

## INSTALLATION

Update composer with new repo

```json
{
    "repositories": {
        "um_cke_improvements": {
            "type": "vcs",
            "url": "git@github.com:its-admin-dux/dux-um-cke-improvements.git"
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

Install the module using composer and enable it with drush:

```bash
composer require dux-umich/um_cke_improvements:dev-main --prefer-source
drush en um_cke_improvements -y  
```

Enable the HTML embed module
```bash
 drush config:set ckeditor5_plugin_pack.settings allow_html_embed true -y
 drush en ckeditor5_plugin_pack_html_embed
```

Import configuration if needed:  
on your local ddev:
```bash
ddev drush config:import --partial --source=web/modules/contrib/um_cke_improvements/config/optional/ -y
```
on remote Pantheon environments:
```bash
 terminus drush config:import --partial --source=/code/web/modules/contrib/um_cke_improvements/config/optional/ -y
```

Then rebuild caches:

```bash
drush cr
```

## CONFIGURATION

The module installs editor configuration for:

- `editor.editor.full_html`
- `editor.editor.filtered_html`

Review these text format editor settings at:

- `/admin/config/content/formats`

If your site already has customized editor configs, confirm config import order
and resolve conflicts as needed.

## VERIFYING THE CHANGES

1. Open a CKEditor 5-enabled text format and verify image insertion includes the
   URL integration behavior expected by your editorial workflow.
2. Confirm image uploads succeed and return expected JSON payload from the
   CKEditor upload endpoint.
3. If behavior looks unchanged, rebuild caches (`drush cr`) and verify the
   module is enabled.

## MAINTAINERS

- U-M ITS Web and Development team
