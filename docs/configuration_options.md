⬅ [Return to main README](../README.md)

# Wordpress Theme Framework

###  Configuration Options

Customization and control over the functionality contained in the framework is done through configuration files you place in your theme. To manage the configuration of your theme, create a file at `_framework/config.php` with your desired options.

See the Configuration section in [README](README.md) for more information about how these are used.

1. [Frontend Options](frontend-settings)
1. [Login Page Options](#login-page-settings)
1. Dashboard
    * [General Options](#dashboard--general-settings)
    * [Content Editor Options](#dashboard--content-editor-settings)
1. [Image Options](#image-settings)
1. [Email General Options](#email-general-settings)
1. [Email Skinning Options](#email-skinning-settings)
1. [Wordpress Supports Options](#wordpress-functionality)

<br/>

------

Each configuation rule has it's own require input type, refer to the default value to know what you should provide. Non-matching input types will be ignored.

------


### Frontend Settings

##### `frontend/assets/version`
> Add cachebusting version to assets paths
>
> **Default:** false

##### `frontend/assets/css`
> CSS file(s). Paths relative to `asset/` directory. 
> 
> **Default:** []

##### `frontend/assets/css_print`
> Print CSS file(s). Paths relative to `asset/` directory.
>
> **Default:** []

##### `frontend/assets/js_head`
> Head JS file(s). Paths relative to `asset/` directory.
>
> **Default:** []

##### `frontend/assets/js_jquery`
> Replace version of jQuery. Path is relative to `asset/` directory.
>
> **Default:** <i>empty</i>



##### `frontend/assets/js`
> Footer JS file(s). Paths relative to `asset/` directory.
>
> **Default:** []

##### `frontend/query_params`
> Add custom query params.
>
> **Default:** []

##### `frontend/hide_admin_bar`
> Hide the Wordpress Admin bar on Frontend
>
> **Default:** true

<br/>

------

### Login Page Settings

##### `login/css`
> CSS file. Paths relative to `asset/` directory.
>
> **Default:** <i>empty</i>


<br/>

------

### Dashboard — General Settings

##### `dashboard/footer_credit`
> Add custom CSS to dashboard.
>
> **Default:** ♥


##### `dashboard/css`
> Add custom CSS to dashboard.
>
> **Default:** <i>empty</i>


##### `dashboard/tags`
> Disable some Wordpress functionality.
>
> **Default:** false

##### `dashboard/menus`
> **Default:** false

##### `dashboard/widgets`
> **Default:** false

##### `dashboard/customizer`
> **Default:** false

##### `dashboard/delete_lock`
> Block pages/posts from being deleted.
> 
> **Default:** []

##### `dashboard/remove_roles`
> Block pages/posts from being deleted.
> 
> **Default:** ['subscriber', 'contributor']

##### `dashboard/admin_bar/howdy`
> Display 'Howdy' from the top bar?
> 
> **Default:** false

##### `dashboard/admin_bar/remove`
> Remove items from the top bar.
> 
> **Default:** ['wp-logo','archive','updates','new-content']

##### `dashboard/admin_bar/relocate`
> Relocate items from the sidebar into top bar.
> 
> **Default:** ['options-general.php', 'tools.php', 'themes.php', 'plugins.php', 'edit.php?post_type=acf-field-group']

<br/>

------

### Dashboard — Metabox Settings
> DOCUMENTAION COMING SOON

<br/>

------

### Dashboard — Content Editor Settings

##### `dashboard/editor/customize`
> **Default:** false

##### `dashboard/editor/css`
> **Default:** <i>empty</i>


##### `dashboard/editor/height`
> **Default:** 200
> **Input Type:** int

##### `dashboard/editor/resize`
> **Default:** true

##### `dashboard/editor/media_buttons`
> **Default:** false

##### `dashboard/editor/buttons_1`
> **Default:** ['formatselect', 'bold', 'italic', 'bullist', 'numlist', 'blockquote',' alignleft', 'aligncenter','alignright', 'link', 'unlink', 'pastetext', 'removeformat']

##### `dashboard/editor/buttons_2`
> **Default:** []

##### `dashboard/editor/formats`
> **Default:** Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre


<br/>

------

### Image settings

##### `image/enabled`
> Enable thumbnail support for this theme.
> 
> **Default:** true

##### `image/sizes`
> Additional image widths generated for responsive `srcset` values. These are the available candidates; they do not define the rendered CSS width.
>
> **Default:** ['400', '600', '800', '1200', '1600', '2400']

##### `image/jpg_quality`
> JPG quality level for generated images.
> 
> **Default:** 90

##### `image/thumbnail/w`
> Generated thumbnail width.
> 
> **Default:** 170


##### `image/thumbnail/h`
> Generated thumbnail height.
> 
> **Default:** 140
> **Input Type:** int

##### `image/thumbnail/crop`
> Generated thumbnail crop.
> **Default:** true

##### `image/remove_default`
> Remove specified default Wordpress image sizes.
>
> **Default:** ['medium', 'medium_large', 'large']

##### `image/max/w`
> Maximum width allowed in generated responsive `srcset` values. Wordpress defaults this to 2048px; this framework setting overrides that limit.
>
> **Default:** 2400

##### `render/image/loading_mode`
> Image loading implementation. `lazysizes` preserves the existing JavaScript behavior; `native` outputs native lazy-loaded `<img>` elements. Native mode supports `img()` only; `div()` returns the configured fallback.
>
> **Default:** `lazysizes`

##### `render/image/default_lazy`
> Enable lazy loading by default for image renders. Per-image `loading('lazy')` / `loading('eager')` or the existing `lazy()` method override this default. `loading('eager')` explicitly emits `loading="eager"`; `lazy(false)` preserves omission of the loading attribute. Fetch priority is independently controlled with `fetchpriority('high' | 'low' | 'auto' | false)` and is omitted by default. See [per-image examples and precedence](libraries.md#per-image-loading-and-fetch-priority).
>
> **Default:** `true`

##### `render/image/default_responsive`
> Enable responsive `srcset` and `sizes` output by default.
>
> **Default:** `true`

##### `render/image/default_decoding`
> Default value for the image `decoding` attribute. Supported values are `async`, `sync`, `auto`, or `false` to omit the attribute.
>
> **Default:** `async`

##### `render/image/native_sizes`
> The `sizes` attribute used by native responsive images, both lazy and eager. This must describe the image's rendered CSS width. It is used with `srcset` by the browser to select an image candidate. Eager images remove a leading `auto` and retain its fallback list (`auto, 100vw` becomes `100vw`); bare `auto` falls back to `100vw`.
>
> **Default:** `100vw`

##### `render/image/native_class`
> Class added to images using native lazy loading. Set to `false` to disable it.
>
> **Default:** `lazy`

##### `render/image/default_size`
> Default WordPress image size requested by the renderer.
>
> **Default:** `1600`

##### `render/image/default_draggable`
> Allow images to be dragged by the browser.
>
> **Default:** `false`

##### `render/image/default_pinnable`
> Allow images to be pinned by supported services such as Pinterest.
>
> **Default:** `false`

##### `render/image/alt_fallback`
> Generate fallback alt text when the attachment has no alt text.
>
> **Default:** `true`

##### `render/image/alt_fallback/function`
> Callback used to generate custom fallback alt text. Receives the attachment ID and should return a string or `false`.

##### `render/image/alt_limit`
> Maximum length for attachment alt text. Set to `false` to disable limiting.
>
> **Default:** `160`

##### `render/image/default_caption`
> Display attachment captions by default.
>
> **Default:** `true`

##### `render/image/default_caption_location`
> Caption location when an image is wrapped: `inside` or `outside`.
>
> **Default:** `inside`

##### `render/image/caption_element`
> HTML element used for captions.
>
> **Default:** `div`

##### `render/image/caption_class`
> Class added to the caption element.
>
> **Default:** `caption`

##### `render/image/caption_strip_html`
> Strip HTML from attachment captions before output.
>
> **Default:** `true`

##### `render/image/caption_placeholder`
> Output an empty caption element when no caption exists.
>
> **Default:** `false`

##### `render/image/default_description`
> Display the attachment description by default.
>
> **Default:** `false`

##### `render/image/default_description_location`
> Description location when an image is wrapped: `inside` or `outside`.
>
> **Default:** `inside`

##### `render/image/description_element`
> HTML element used for descriptions.
>
> **Default:** `div`

##### `render/image/description_class`
> Class added to the description element.
>
> **Default:** `description`

##### `render/image/default_wrap`
> Wrap rendered images in a container element.
>
> **Default:** `false`

##### `render/image/default_wrap_size`
> Default size class added to the image wrapper.
>
> **Default:** empty

##### `render/image/default_wrap_autosize`
> Add an aspect-ratio or padding-bottom style to wrappers.
>
> **Default:** `false`

##### `render/image/default_wrap_class`
> Class added to image wrappers.
>
> **Default:** `img-wrapper`

##### `render/image/default_wrap_fallback`
> Output the configured wrapper fallback when no image is available.
>
> **Default:** `true`

##### `render/image/autosize_method`
> Wrapper autosize calculation: `padding-bottom` or `aspect-ratio`.
>
> **Default:** `padding-bottom`

##### `render/image/lazy_class`
> Classes added in lazysizes mode.
>
> **Default:** `lazyload lazyload-persist`

##### `render/image/lazy_img_srcset`
> Attribute used for lazy IMG `srcset` values.
>
> **Default:** `data-srcset`

##### `render/image/lazy_div_srcset`
> Attribute used for lazy background-image candidate values.
>
> **Default:** `data-bgset`

##### `render/image/lazy_sizes`
> Attribute used for lazy `sizes` values.
>
> **Default:** `data-sizes`

<br/>

------

### Email General Settings

##### `email/name`
> Sender name in emails sent from Wordpress.
>
> **Default:** get_option('blogname')


##### `email/address`
> Sender address in emails sent from Wordpress.
>
> **Default:** `'noreply@' . $_SERVER['HTTP_HOST']`


##### `email/mime`
> **Default:** 'text/html'


##### `email/change_password`
>  Should 'New User' notifications to admin be sent?
> 
>  - **Default:** false

##### `email/new_user`
>  Should 'Notice of Password Change' email to admin be sent?
> 
>  - **Default:** false

<br/>

------

### Email Skinning Settings

##### `email/skin`
> Should outgoing Wordpress emails be skinned?
>
> **Default:** false

##### `email/skin/header`
> Header to be included before all outgoing Wordpress emails.
> Value should be defined as path inside the `_includes` directory.
>
> **Default:** <i>empty</i>


##### `email/skin/footer`
> Footer to be included after all outgoing Wordpress emails.
> Value should be defined as path inside the `_includes` directory.
>
> **Default:** <i>empty</i>


##### `email/skin/link_color`
> Force all links in the email body to have the following color. 
> Value supplied should be a hex color value.
> 
> **Default:** <i>empty</i>


<br/>

------
### Wordpress Functionality

##### `support/categories`
> Enable post categories support for this theme.
> 
> **Default:** true

##### `support/comments`
> Enable comment support for this theme.
> 
> **Default:** false

##### `support/customizer`
> Enable customizer support for this theme.
> 
> **Default:** false

##### `support/menus`
> Enable comment menus for this theme.
> 
> **Default:** false

##### `support/posts`
> Enable posts support for this theme.
> 
> **Default:** true

##### `support/tags`
> Enable tags support for this theme.
> 
> **Default:** false

##### `support/thumbnails`
> Enable thumbnails support for this theme.
> 
> **Default:** true

##### `support/widgets`
> Enable widgets support for this theme.
> 
> **Default:** false


<br/>

------

##### Custom Image Sizes

[ TODO: Add details on how to define ]

##### Custom Metaboxes

[ TODO: Add details on how to define ]

##### Content Editor

[ TODO: Add details on how to define ]

<br/>
