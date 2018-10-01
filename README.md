# Wordpress Theme Framework

[ TODO: Add project decription ]

### Configuration

Functionality contained in the framework, including some of the built-in enhancements, can controlled through a configuration file placed in your theme.

```php
# _framework/config.php
CONFIG()->set('frontend/assets/css', ['css/site.min.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.min.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.min.js', 'js/site.min.js']);
```

In addition, you can define a environment-specifc (development, staging, production) which is automatically loaded based on the site url to override a setting. In the example below we are telling the theme to show non-minified assets on the development/local environment.

```php
# _framework/config_development.php
CONFIG()->set('frontend/assets/css', ['css/site.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.js', 'js/site.js']);
```
### Configuration Options

<table>
    <thead>
        <tr>
            <th>Key</th>
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
        <tr><td>dashboard/bar/remove</td><td>[]</td><td>array</td><td>Remove items from the top bar.</td></tr>
        <tr><td>dashboard/bar/relocate</td><td>[]</td><td>array</td><td>Relocate items from the sidebar into top bar.</td></tr>
        <tr><td colspan="4"><b>Dashboard — Metabox Settings</b></td></tr>
        <tr><td colspan="4">DOCUMENTAION COMING SOON</td></tr> 
        <tr><td colspan="4"><b>Dashboard — Content Editor Settings</b></td></tr>
        <tr><td>dashboard/editor/customize</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/css</td><td><i>empty</i></td><td>string</td><td></td></tr>
        <tr><td>dashboard/editor/height</td><td>200</td><td>int</td><td></td></tr>
        <tr><td>dashboard/editor/resize</td><td>true</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/media_buttons</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/buttons_1</td><td>[]</td><td>array</td><td></td></tr>
        <tr><td>dashboard/editor/buttons_2</td><td>[]</td><td>array</td><td></td></tr>
        <tr><td>dashboard/editor/formats</td><td><i>empty</i></td><td>string</td><td></td></tr>
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

------------

### Template Utilities

#### Image Rendermage Render

[ TODO: Add description ]

```php
// Output <IMG> element from custom field value.
echo IMAGE()->img(get_field('image'));

// Output <IMG> element from Featured Image with class attribute.
echo IMAGE()->class('thumbnail')->div(get_post_thumbnail_id()); 

// Output <IMG> for lazy loading from custom field. 
echo IMAGE()->classes('lazy image')->alpha(true)->img(get_field('image'));

// Output <IMG> from a custom field, adding custom attributes, example 1.
echo IMAGE()->attr(array('data-test1'=>'value1', 'data-test2'=>'value2'))->img(get_field('image'));

// Output <IMG> from a custom field, adding custom attributes, example 2.
echo IMAGE()->attr('data-test1', 'value1')->img(get_field('image'));

// Output a <DIV> element with from image in a custom field with class attribute.
echo IMAGE()->classes('thumbnail')->div(get_field('image'));

// Output a <DIV> element from image in a custom field, setting it's base size to something smaller.
echo IMAGE()->size(600)->div(get_field('image'));

// Output just the image src (e.g. for use in Meta Tag), passing in the size we want.
echo IMAGE()->src(get_post_thumbnail_id(), 800); 
```
<br/>

#### SVG Render

[ TODO: Add description ]


```php
// Display an in-line SVG (stored in assets/img/arrow.svg):
echo SVG()->show('arrow');

// Display an SVG (stored in assets/img/arrow.svg) as <img> with attributes:
echo SVG()->img('test', array('class'=>'testing', 'alt'=>'Name of the image'))
```
<br/>

#### Include Render

[ TODO: Add description ]

```php
// Display an include file. Basic usage.
INCLUDES()->show('share_icons');

// Display an include file and cache the output.
// Useful for areas which perform a lot of database calls but hardly change (e.g. your site's header).
INCLUDES()->cache()->show('share_icons');

// Display an include file and cache the output, globally.
// Normally cache is page-specific, but if your include doesn't change between pages you can set it to be site-wide.
INCLUDES()->cache('global')->show('header');

// Send data to include when generating it.
INCLUDES()->data([ 'section'=>'about', 'background'=>'blue'])->show('header');

// Get the contents of an include and store in variable.
$include_contents = INCLUDES()->fetch('header');
```
<br/>

#### Module Render

[ TODO: Add description ]
[ TODO: Add examples ]
<br/>

------------

### Template Functions

[ TODO: Add description ]
[ TODO: Add examples ]

------------

### Demo Theme

[ TODO: Add information ]