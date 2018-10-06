# Wordpress Theme Framework


:warning: **This is a private beta release of this framework, please do not share.**

:email: **Feedback and suggestions are encouraged.**

Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.
    
1. [Installation](#installation)
1. [Frontend Libraries](#frontend-libraries)
    * [Image](#frontend-libraries---image-render)
    * [SVG](#frontend-libraries--svg-render)
    * [Includes](#frontend-libraries--includes-render)
    * [Modules](#frontend-libraries--modules-render)
    * ~~[Metatag](#frontend-libraries--meta-tag-render)~~
1. ~~[Frontend Helpers](#frontend-helpers)~~
1. [Configuration](#configuration)
1. [Configuration Options](#configuration-options)
    * [Frontend](#xxxx)
    * [Login](#xxxx)
    * [Dashboard — General](#xxxx) 
    * [Dashboard — Metaboxes](#xxxx)
    * [Dashboard — Content Editor](#xxxx)
    * ~~[Dashboard — ACF](#xxxx)~~
    * [Custom Image Sizes](#xxxx)
    * [Email](#xxxx)
1. [Environment Detection](#environment-detection)
1. ~~[Custom Post Types & Taxonomy](#custom-post-types--taxonomy)~~
1. [Demo Theme](#demo-theme)

<br/>

------
   
### TODO

- [ ] Name framework.
- [ ] License?
- [ ] Add ACF customizations.
- [ ] Add Metatag Library.
- [ ] Improve Configuration Options Layout 
- [ ] Write Product introduction.
- [ ] Configuration Options - Reformat section.
- [ ] Configuration Options - breakdown all chained methods and end points for each example.


<br/>

------
   

### Installation

Installation is very simple - install and activate this Plugin on your Wordpress site. Once running, most of the Frontend HTML and Dashboard UI cleanup functionality will happen automatically. If you would like to disable or customize functionality within the framework refer to the [Configuration](#configuration) section of this document.

If you are using any of the Template Libraries or Helper Functions in your theme code, we suggest placing the following in your code `functions.php` file to safegaurd against errors if the event the Plugin is deactivated.

```php
#functions.php
// Make sure the Theme Framework plugin is present. Required by this theme.
if (!is_admin() && !defined('HELLO_DIR')) exit('<strong>Whooops.</strong> This theme is meant to be used with the <a href="https://github.com/louiswalch/Wordpress-Theme-Framework">Theme Framework</a>. Please install and activate.');
```

##### Directory Structure
By default, the framework is configured for the following organization. Most are optional based on what aspects of the system you are using. Paths relative to your theme root.

- `_framework/`
	- `assets/` *Login, Dashboard & Editor CSS files.*
	- `config_development.php` *Development site overrides.*
	- `config_staging.php` *Staging site overrides.*
	- `config.php` *Your custom framework configuration.*
	- `metabox/` *Custom Metabox HTML.*
	- `types/` *Custom post type declarations.*
- `_includes/` *Files being used with Includes.*
- `_modules/` *Blocks being used with Modules.*
- `assets/` *Theme CSS/JS/IMG/etc.*

<br/>

---

### Frontend Libraries

Frontend Libraries are a collection of utilities meant to streamline your template authoring. This includes utilities to simplify image output *(with a variety of options)*, render an include file *(supporting proper data and caching)*, and automating a modular layout.


<br/>

---

### Frontend Libraries :  Image Render

This allows you to easily output a Wordpress image, either coming from an ACF Image field or the post thumbnail. They can either be rendered as a standard IMG element a DIV with backgorund image. In both cases it supports lazy loading as well as responsive src-set.

##### Example Usage - Output IMG element from custom field value:

```php
// <img src="../foo.jpg" alt="Image Title" />
echo IMAGE()->img(get_field('image'));

```

The library suports a variety of parameters to customize how the image should be rendered. These methods are desiged to be chained and would be passed in prior to your final output call.

> - TODO: Document
> - srcset($incoming=true)
> - wrap($incoming=false)
> - caption($incoming=null)
> - classes($incoming=false)
> - lazy($incoming=true)
> - size($incoming=false)
> - alpha($incoming=false)
> - attributes($incoming=false) 
> - attr($one=false, $two=false)
> - pinnable($incoming=false

Once you have defined any parameters, then you call your final output - either an IMG, DIV, or to retreive the SRC.

> - TODO: Document
> - div($image=false, $size=false)
> - img($image=false, $size=false, $showcaption=false)
> - src($image=false, $size=false)

##### More Examples:

```php
// Output IMG element from custom field value.
echo IMAGE()->img(get_field('image'));
	
// Output IMG element from Featured Image with class attribute.
echo IMAGE()->classes('thumbnail')->div(get_post_thumbnail_id()); 
	
// Output IMG for lazy loading from custom field. 
echo IMAGE()->classes('lazy image')->alpha(true)->img(get_field('image'));
	
// Output IMG from a custom field, adding custom attributes, example 1.
echo IMAGE()->attr(array('data-test1'=>'value1', 'data-test2'=>'value2'))->img(get_field('image'));
	
// Output IMG from a custom field, adding custom attributes, example 2.
echo IMAGE()->attr('data-test1', 'value1')->img(get_field('image'));
	
// Output a DIV element with from image in a custom field with class attribute.
echo IMAGE()->classes('thumbnail')->div(get_field('image'));
	
// Output a DIV element from image in a custom field, setting it's base size to something smaller.
echo IMAGE()->size(600)->div(get_field('image'));
	
// Output just the image src (e.g. for use in Meta Tag), passing in the size we want.
echo IMAGE()->src(get_post_thumbnail_id(), 800); 
```

<br/>

------

### Frontend Libraries : SVG Render

This allows you to easily output an SVG image from your theme's assets directory to the page. Images can either be rendered as a standard IMG element, as a DIV with background image or with the SVG source embedded within the HTML.

##### Example Usage:

```php
// Display an in-line SVG (stored in assets/img/arrow.svg):
// <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7"><path d="M3.235 6.747c-.172 0-.336-.055-.474-.158L.31 4.72A.79.79 0 0 1 .16 3.615c.26-.336.776-.4 1.107-.148L3.15 4.903 5.018 2.86 7.422.246C7.53.128 7.69.04 7.867.01L7.992 0c.297 0 .565.164.702.43.153.3.098.667-.14.908l-4.75 5.167a.79.79 0 0 1-.57.243" fill="#4B4A4B" fill-rule="evenodd"/></svg>
echo SVG()->show('arrow');

// Display an SVG (stored in assets/img/arrow.svg) as IMG with attributes:
// <img src=".../assets/img/test.png" class="testing" alt="Name of the image" />
echo SVG()->img('arrow.svg', array('class'=>'testing', 'alt'=>'Name of the image'))
````

> - TODO: Document
> - img($key, $attributes=array()) 
> - div($key, $attributes=array())
> - show($key)

<br/>

------

### Frontend Libraries : Includes Render

Includes allow you to separate some of your site's componants and render then within a page when needed. The main advantage over the native `get_template_part()` is that you can pass data directly into the include while rendering it in addition an include can be cached either globally or page-specific. 

##### Example Usage - Display a simple include file.:
```php
INCLUDES()->show('share_icons');
```
XXXXXXXXX

> - TODO: Document
> - cache($global=false, $set=true, $key=null) {
> - dir($dir=null) {
> - life($life=null) {
> - data($one=null, $two=null) {
> - debug($incoming=null) {
> - key($incoming=false) {
> - fetch($key, $data=false)     // Load and output the contents of a snippet, either live or from cache.
> - show($key, $data=false)

##### More Examples: 
```php
// Display an include file. Basic usage.
INCLUDES()->show('share_icons');

// Display an include file and cache the output.
// Useful for areas which perform a lot of database calls but hardly change (e.g. your site's header).
INCLUDES()->cache()->show('header');

// Display an include file and cache the output, globally.
// Normally cache is page-specific, but if your include doesn't change between pages you can set it to be site-wide.
INCLUDES()->cache('global')->show('header_global');

// Send data to include when generating it.
INCLUDES()->data(['section'=>'about', 'background'=>'blue'])->show('header');

// Get the contents of an include and store in variable.
$include_contents = INCLUDES()->fetch('share_icons');
```

Inside your include file you can call `get_include_var` to access any data passed in.

```php
get_include_var($key, $group = null)
```

<br/>

------

### Frontend Libraries : Modules Render

Module are similar to Includes except they are designed to be used with the ACF Flexible Content field, automatically rendering the correct module element based on what the page has been set up with.

##### Example Usage:
```php
Automatically load modules from current page. Default field name is 'modules'.
MODULES()->auto();

// You can have two sets of modules on one page like this:
MODULES()->auto('content_modules');
MODULES()->auto('sidebar_modules');

// Automatically load modules from a different page.
MODULES()->from(1234)->auto();

// Manually load a specified module while passing in the data to display.
MODULES()->show('text_content', [
    'title' => 'Test Module',
    'text' => 'Testing out manually including a module on this page. Did it work?',
    ]);
```

Inside your module file you can call `get_module_var` to access all data from the current Flexible Content block.

```php
get_module_var($key, $group = null)
```

<br/>

------

### ~~Frontend Libraries : Meta Tag Render~~

Not yet.

<br/>

------


### ~~Frontend Helpers~~

Not yet.


<br/>

------

### Configuration

Customization and control over the functionality contained in the framework is done through configuration files you place in your theme. To manage the configuration of your theme, create a file at `_framework/config.php` with your desired options.

##### Example Usage - Assigning theme CSS & JS files:

```php
# _framework/config.php
CONFIG()->set('frontend/assets/css', ['css/site.min.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.min.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.min.js', 'js/site.min.js']);
```

In addition base level configuration file, you can also create environment-specifc (development, staging) files which would override all default settings only on that environment. This could be useful for disableing cache on development/staging or delivering non-minified assets for easier debugging.

##### Example Usage - Assigning alternative CSS & JS on development server:

```php
# _framework/config_development.php
CONFIG()->set('frontend/assets/css', ['css/site.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.js', 'js/site.js']);
```

Should you ever want to inspect the current framework configuration, you can use the following command.

```php
<? pr(CONFIG()->dump(), 'Site Configuration'); ?>
```

<br/>

------

### Configuration Options


<!--`frontend/assets/version`<br/>
Default: `false`<br/>
Type: `bool`<br/>
Add cachebusting version to assets paths.
-->

<table>
    <thead>
        <tr.>
            <Th.>Key</th>
            <th>Default</th>
            <th>Input Type</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="4"><b>Frontend Settings</b></td></tr>
        <tr><td>frontend/assets/version</td><td>false</td><td>bool</td><td>Add cachebusting version to assets paths</td></tr>
        <tr><td>frontend/assets/css</td><td>[]</td><td>array</td><td>Website CSS file(s). Paths relative to `asset/` directory.</td></tr>
        <tr><td>frontend/assets/css_print</td><td>[]</td><td>array</td><td>Print CSS file(s). Paths relative to `asset/` directory.</td></tr>
        <tr><td>frontend/assets/js</td><td>[]</td><td>array</td><td>Website JS file(s). Paths relative to `asset/` directory.</td></tr>
        <tr><td>frontend/query_params</td><td>[]</td><td>array</td><td>Add custom query params.</td></tr>
        <tr><td>frontend/hide_admin_bar</td><td>true</td><td>bool</td><td>Hide the Wordpress Admin bar on Frontend</td></tr>
        <tr><td colspan="4"><b>Login Page Settings</b></td></tr>
        <tr><td>dashboard/login/css</td><td><i>empty</i></td><td>string</td><td>CSS file. Paths relative to `asset/` directory.</td></tr>
        <tr><td colspan="4"><b>Dashboard — General Settings</b></td></tr>
        <tr><td>dashboard/footer_credit</td><td>♥</td><td>string</td><td>Add custom CSS to dashboard.</td></tr>
        <tr><td>dashboard/css</td><td><i>empty</i></td><td>string</td><td>Add custom CSS to dashboard.</td></tr>
        <tr><td>dashboard/tags</td><td>false</td><td>bool</td><td>Disable some Wordpress functionality.</td></tr>
        <tr><td>dashboard/menus</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/widgets</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/customizer</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/delete_lock</td><td>[]</td><td>array</td><td>Block pages/posts from being deleted.</td></tr>
        <tr><td>dashboard/remove_roles</td><td>['subscriber', 'contributor']</td><td>array</td><td>Block pages/posts from being deleted.</td></tr>
        <tr><td>dashboard/bar/howdy</td><td>false</td><td>bool</td><td>Display 'Howdy' from the top bar?</td></tr>
        <tr><td>dashboard/bar/remove</td><td>['wp-logo','archive','updates','new-content']</td><td>array</td><td>Remove items from the top bar.</td></tr>
        <tr><td>dashboard/bar/relocate</td><td>['options-general.php', 'tools.php', 'themes.php', 'plugins.php', 'edit.php?post_type=acf-field-group']</td><td>array</td><td>Relocate items from the sidebar into top bar.</td></tr>
        <tr><td colspan="4"><b>Dashboard — Metabox Settings</b></td></tr>
        <tr><td colspan="4">DOCUMENTAION COMING SOON</td></tr> 
        <tr><td colspan="4"><b>Dashboard — Content Editor Settings</b></td></tr>
        <tr><td>dashboard/editor/customize</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/css</td><td><i>empty</i></td><td>string</td><td></td></tr>
        <tr><td>dashboard/editor/height</td><td>200</td><td>int</td><td></td></tr>
        <tr><td>dashboard/editor/resize</td><td>true</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/media_buttons</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/buttons_1</td><td>['formatselect', 'bold', 'italic', 'bullist', 'numlist', 'blockquote',' alignleft', 'aligncenter','alignright', 'link', 'unlink', 'pastetext', 'removeformat']</td><td>array</td><td></td></tr>
        <tr><td>dashboard/editor/buttons_2</td><td>[]</td><td>array</td><td></td></tr>
        <tr><td>dashboard/editor/formats</td><td>Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre</td><td>string</td><td></td></tr>
        <tr><td colspan="4"><b>Image settings</b></td></tr>
        <tr><td>image/enabled</td><td>true</td><td>bool</td><td>Enable thumbnail support for this theme.</td></tr>
        <tr><td>image/sizes</td><td>['400', '600', '800', '1200', '1600', '2400']</td><td>array</td><td></td></tr>
        <tr><td>image/jpg_quality</td><td>90</td><td>int</td><td>JPG quality level for generated images.</td></tr>
        <tr><td>image/thumbnail/w</td><td>170</td><td>int</td><td>Generated thumbnail width.</td></tr>
        <tr><td>image/thumbnail/h</td><td>140</td><td>int</td><td>Generated thumbnail height.</td></tr>
        <tr><td>image/thumbnail/crop</td><td>true</td><td>bool</td><td>Generated thumbnail crop.</td></tr>
        <tr><td>image/remove_default</td><td>['medium', 'medium_large', 'large']</td><td>array</td><td>Remove specified default Wordpress image sizes.</td></tr>
        <tr><td>image/srcset_max</td><td>2400</td><td>int</td><td>Max width for responsive srcset images.</td></tr>
        <tr><td colspan="4"><b>Email Settings</b></td></tr>
        <tr><td>email/name</td><td>get_option('blogname')</td><td>string</td><td></td></tr>
        <tr><td>email/address</td><td>'noreply@' . $_SERVER['HTTP_HOST']</td><td>string</td><td></td></tr>
        <tr><td>email/mime</td><td>'text/html'</td><td>string</td><td></td></tr>
        <tr><td>email/change_password</td><td>false</td><td>bool</td><td>Send 'New User' notifications?</td></tr>
        <tr><td>email/new_user</td><td>false</td><td>bool</td><td>Send 'Notice of Password Change' email?</td></tr>
        <tr><td colspan="4"><b>Misc Settings</b></td></tr>
        <tr><td>comments</td><td>false</td><td>bool</td><td>Enable comment support for this theme.</td></tr>
        <tr><td>posts</td><td>true</td><td>bool</td><td>Enable posts for this theme.</td></tr>
    </tbody>
</table>


##### Custom Image Sizes

[ TODO: Add details on how to define ]

##### Custom Metaboxes

[ TODO: Add details on how to define ]

##### Content Editor

[ TODO: Add details on how to define ]


<br/>

------

### Environment Detection

Based on the site url, the framework will detect the current environment (development, staging, production). This information is used for loading the appropriate configuration file and adding a CSS class to the BODY. The domain match string is defined in the base configuration file if you need to update it to match your infrastrucutre.

| Environment | Domain Match |
|--|--|
| Development | .local |
| Staging | staging. |
| Producton | * |


<br/>

---

### ~~Custom Post Types & Taxonomy~~

Not Yes.

<br/>

------

### Demo Theme

To familiarize yourself with developing Wordpress themes using this framework I reccommend checking out our demo theme. This is a very light implmentation showcasing most of the major features.

[https://github.com/louiswalch/Wordpress-Theme-Framework-Demo](https://github.com/louiswalch/Wordpress-Theme-Framework-Demo)

<br/>

------

### License

What should this be?
