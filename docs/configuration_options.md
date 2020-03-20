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

##### `dashboard/login/css`
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
