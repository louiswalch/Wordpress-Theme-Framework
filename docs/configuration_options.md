⬅ [Return to main README](../README.md)

# Wordpress Theme Framework

###  Configuration Options

Customization and control over the functionality contained in the framework is done through configuration files you place in your theme. To manage the configuration of your theme, create a file at `_framework/config.php` with your desired options.

See the Configuration section in [README](README.md) for more information about how these are used.

1. [Frontend Options](frontend-settings)
1. [Login Page Options](#login-page-settings)
1. Dashboard
    * [General Options](#dashboard--general-settings)
    * [Metabox Options](#dashboard--metabox-settings)
    * [Content Editor Options](#dashboard--content-editor-settings)
1. [Image Options](#image-settings)
1. [Email Options](#email-settings)
1. [Wordpress Supports Options](#wordpress-functionality)

<br/>

------


### Frontend Settings

##### `frontend/assets/version`
> Add cachebusting version to assets paths
>
> - **Default:** false
> - **Input Type:** bool

##### `frontend/assets/css`
> CSS file(s). Paths relative to `asset/` directory. 
> 
> - **Default:** []
> - **Input Type:** array

##### `frontend/assets/css_print`
> Print CSS file(s). Paths relative to `asset/` directory.
>
> - **Default:** []
> - **Input Type:** array

##### `frontend/assets/js_head`
> Head JS file(s). Paths relative to `asset/` directory.
>
> - **Default:** []
> - **Input Type:** array

##### `frontend/assets/js`
> Footer JS file(s). Paths relative to `asset/` directory.
>
> - **Default:** []
> - **Input Type:** array

##### `frontend/query_params`
> Add custom query params.
>
> - **Default:** []
> - **Input Type:** array

##### `frontend/hide_admin_bar`
> Hide the Wordpress Admin bar on Frontend
>
> - **Default:** true
> - **Input Type:** bool

<br/>

------

### Login Page Settings

##### `dashboard/login/css`
> CSS file. Paths relative to `asset/` directory.
>
> - **Default:** <i>empty</i>
> - **Input Type:** string

<br/>

------

### Dashboard — General Settings

##### `dashboard/footer_credit`
> Add custom CSS to dashboard.
>
> - **Default:** ♥
> - **Input Type:** string

##### `dashboard/css`
> Add custom CSS to dashboard.
>
> - **Default:** <i>empty</i>
> - **Input Type:** string

##### `dashboard/tags`
> Disable some Wordpress functionality.
>
> - **Default:** false
> - **Input Type:** bool

##### `dashboard/menus`
> - **Default:** false
> - **Input Type:** bool

##### `dashboard/widgets`
> - **Default:** false
> - **Input Type:** bool

##### `dashboard/customizer`
> - **Default:** false
> - **Input Type:** bool

##### `dashboard/delete_lock`
> Block pages/posts from being deleted.
> 
> - **Default:** []
> - **Input Type:** array

##### `dashboard/remove_roles`
> Block pages/posts from being deleted.
> 
> - **Default:** ['subscriber', 'contributor']
> - **Input Type:** array

##### `dashboard/admin_bar/howdy`
> Display 'Howdy' from the top bar?
> 
> - **Default:** false
> - **Input Type:** bool

##### `dashboard/admin_bar/remove`
> Remove items from the top bar.
> 
> - **Default:** ['wp-logo','archive','updates','new-content']
> - **Input Type:** array

##### `dashboard/admin_bar/relocate`
> Relocate items from the sidebar into top bar.
> 
> - **Default:** ['options-general.php', 'tools.php', 'themes.php', 'plugins.php', 'edit.php?post_type=acf-field-group']
> - **Input Type:** array

<br/>

------

### Dashboard — Metabox Settings
> DOCUMENTAION COMING SOON

<br/>

------

### Dashboard — Content Editor Settings

##### `dashboard/editor/customize`
> - **Default:** false
> - **Input Type:** bool

##### `dashboard/editor/css`
> - **Default:** <i>empty</i>
> - **Input Type:** string

##### `dashboard/editor/height`
> - **Default:** 200
> - **Input Type:** int

##### `dashboard/editor/resize`
> - **Default:** true
> - **Input Type:** bool

##### `dashboard/editor/media_buttons`
> - **Default:** false
> - **Input Type:** bool

##### `dashboard/editor/buttons_1`
> - **Default:** ['formatselect', 'bold', 'italic', 'bullist', 'numlist', 'blockquote',' alignleft', 'aligncenter','alignright', 'link', 'unlink', 'pastetext', 'removeformat']
> - **Input Type:** array

##### `dashboard/editor/buttons_2`
> - **Default:** []
> - **Input Type:** array

##### `dashboard/editor/formats`
> - **Default:** Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre
> - **Input Type:** string

<br/>

------

### Image settings

##### `image/enabled`
> Enable thumbnail support for this theme.
> 
> - **Default:** true
> - **Input Type:** bool

##### `image/sizes`
> - **Default:** ['400', '600', '800', '1200', '1600', '2400']
> - **Input Type:** array

##### `image/jpg_quality`
> JPG quality level for generated images.
> 
> - **Default:** 90
> - **Input Type:** int

##### `image/thumbnail/w`
> Generated thumbnail width.
> 
> - **Default:** 170
> - **Input Type:** int

##### `image/thumbnail/h`
> Generated thumbnail height.
> 
> - **Default:** 140
> - **Input Type:** int

##### `image/thumbnail/crop`
> Generated thumbnail crop.
> - **Default:** true
> - **Input Type:** bool

##### `image/remove_default`
> Remove specified default Wordpress image sizes.
> 
> - **Default:** ['medium', 'medium_large', 'large']
> - **Input Type:** array

##### `image/srcset_max`
> Max width for responsive srcset images.
> 
> - **Default:** 2400
> - **Input Type:** int

<br/>

------

### Email Settings

##### `email/name`
> - **Default:** get_option('blogname')
> - **Input Type:** string

##### `email/address`
> - **Default:** `'noreply@' . $_SERVER['HTTP_HOST']`
> - **Input Type:** string

##### `email/mime`
> - **Default:** 'text/html'
> - **Input Type:** string

##### `email/change_password`
>  Send 'New User' notifications to admin?
> 
>  - **Default:** false
>  - **Input Type:** bool

##### `email/new_user`
>  Send 'Notice of Password Change' email to admin?
> 
>  - **Default:** false
>  - **Input Type:** bool

<br/>

------
### Wordpress Functionality

##### `support/categories`
> Enable post categories support for this theme.
> 
> - **Default:** true
> - **Input Type:** bool

##### `support/comments`
> Enable comment support for this theme.
> 
> - **Default:** false
> - **Input Type:** bool

##### `support/customizer`
> Enable customizer support for this theme.
> 
> - **Default:** false
> - **Input Type:** bool

##### `support/menus`
> Enable comment menus for this theme.
> 
> - **Default:** false
> - **Input Type:** bool

##### `support/posts`
> Enable posts support for this theme.
> 
> - **Default:** true
> - **Input Type:** bool

##### `support/tags`
> Enable tags support for this theme.
> 
> - **Default:** false
> - **Input Type:** bool

##### `support/thumbnails`
> Enable thumbnails support for this theme.
> 
> - **Default:** true
> - **Input Type:** bool

##### `support/widgets`
> Enable widgets support for this theme.
> 
> - **Default:** false
> - **Input Type:** bool


<br/>

------

##### Custom Image Sizes

[ TODO: Add details on how to define ]

##### Custom Metaboxes

[ TODO: Add details on how to define ]

##### Content Editor

[ TODO: Add details on how to define ]

<br/>
