⬅ [Return to main README](../README.md)

# Wordpress Theme Framework

### Frontend Library

Frontend Libraries are a collection of utilities meant to streamline your template authoring. This includes utilities to simplify image output (with a variety of options), render an include file (supporting proper data and caching), and automating a modular layout.

1. [Cache](#frontend-library--cache)
1. [Includes](#frontend-library--includes-render)
1. [Modules](#frontend-library--modules-render)
1. [Image](#frontend-library---image-render)
1. [SVG](#frontend-library--svg-render)
1. [DataStore](#frontend-library--data-store)

<br/>

------

### Frontend Library : Cache

```php
// Simplest usage:
$data = CACHE()->load('test123', function() {
    // Big query or expensive process to load $data
    return $data;    
});


// Store for longer than default:
$data = CACHE()->time(DAY_IN_SECONDS)->load('test123', function() {
    // Big query or expensive process to load $data
    return $data;    
});

// By default, cache are stored at the page/object level, you cam override that to store/fetch globally:
$data = CACHE()->global(true)->load('footer-evnets', function() {
    // Big query or expensive process to load $data
    return $data;    
});
```

------

### Frontend Library : Includes Render

Includes allow you to separate some of your site's components and render then within a page when needed. The main advantage over the native `get_template_part()` is that you can pass data directly into the include while rendering it in addition an include can be cached either globally or page-specific. 


##### Example Usage - Display a simple include file:
```php
INCLUDES()->show('share_icons');
```

##### Params:

The library supports a variety of parameters to customize how the image should be rendered. These methods are designed to be chained and would be passed in prior to your final output call.

> - cache($global=false, $set=true, $key=null)
> - dir($dir=null)
> - life($life=null)
> - data($one=null, $two=null)
> - debug($incoming=null)
> - key($incoming=false)
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
get_include_var($key, $default = false, $group = null)
```

For debugging purposes or if you don't know what data is accessible to you, you can output the `$data` variable.

```php
pr($data, 'Include Data');
```
<br/>

------

### Frontend Library : Modules Render

Module are similar to Includes except they are designed to be used with the ACF Flexible Content field, automatically rendering the correct module element based on what the page has been set up with.

##### Example Usage:
```php
// Automatically load modules from current page. Default field name is 'modules'.
MODULES()->auto();

// Automatically load all modules passing the module (Flexible Content) value.
MODULES()->auto( $my_modules );

// You can have two sets of modules on one page like this:
MODULES()->auto('content_modules');
MODULES()->auto('sidebar_modules');

// When automatically loading modules, you can specify certain ones to ignore.
MODULES()->auto('content_modules', ['unwanted_module_name', 'ugly_thing']);
// Manually load certain modules passing the module (Flexible Content) value, and an array of layout to skip.
MODULES()->auto( $my_modules, ['unwanted_module_name', 'ugly_thing'] );

// Automatically load modules from a different page, or from the ACF Options.
MODULES()->from(1234)->auto('content_modules');
MODULES()->from('options')->auto('footer_modules');

// Alter the name of module file to be included: Adding a prefix.
// For a module named 'gallery' in the CMS, this would include 'exhibition_gallery'.
MODULES()->filename('exhibition_')->auto('content_modules');

// You can also use the `filename()` prefix option to map to a subdirectory of _modules.
MODULES()->filename('exhibition/')->auto('content_modules');

// Alter the name of module file to be included: Using `sprintf` command.
// For a module named 'gallery' in the CMS, this would include 'exhibition_gallery_preview'.
MODULES()->filename('exhibition_%s_preview')->auto('content_modules');

// Manually load a specified module while passing in the data to display.
MODULES()->show('text_content', [
    'title' => 'Test Module',
    'text' => 'Testing out manually including a module on this page. Did it work?',
    ]);
```

Inside your module file you can call `get_module_var` to access all data from the current Flexible Content block.

```php
get_module_var($key, $default = false, $group = null);
```

For debugging purposes or if you don't know what data is accessible to you, you can output the `$data` variable.

```php
pr($data, 'Module Data');
```

##### Special Variables: 

The following built-in variables are made available within your module file. These can be referenced directly (e.g. `$_name`) or using `get_module_var()`.

- `_name` *(String)* Name specified for this module within the CMS.
- `_index` *(Int)* Index of the current module within iteration.
- `_first` *(Bool)* Whether or not the current module is first.
- `_last` *(Bool)* Whether or not the current module is last.

<br/>

---

### Frontend Library :  Image Render

This allows you to easily output a Wordpress image, either coming from an ACF Image field or the post thumbnail. They can either be rendered as a standard IMG element a DIV with background image. In both cases it supports lazy loading as well as responsive src-set.

##### Example Usage - Output IMG element from custom field value:

```php
// <img src="../foo.jpg" alt="Image Title" />
echo IMAGE()->img(get_field('image'));

```

##### Params:

The library supports a variety of parameters to customize how the image should be rendered. These methods are designed to be chained and would be passed in prior to your final output call.

> - srcset($incoming=true)
> - wrap($incoming=false)
> - autosize($incoming=false)
> - caption($incoming=null)
> - classes($incoming=false)
> - lazy($incoming=true)
> - size($incoming=false)
> - alpha($incoming=false)
> - attributes($incoming=false) 
> - attr($one=false, $two=false)
> - pinnable($incoming=false

Once you have defined any parameters, then you call your final output - either an IMG, DIV, or to retrieve the SRC.

> - div($image=false, $size=false)
> - img($image=false, $size=false, $showcaption=false)
> - src($image=false, $size=false)

##### Basic Rendering Examples:

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

##### Image Render Library - Wrapper Functionality

Wrapping your image embed with another element is handy when working with responsive images as well as allow for a placeholder while the image is being loaded. Default behavior can be defined in your site's framework `config.php` file, or pass custom parameters in when calling making a call to the library.

- [Image Render Library Config Options ↗](configuration_options)

The framework configuration settings appropriate to Image Render wrapping are (along with their default values):

```php
// Enable or disable the wrapping functionality across all renders.
CONFIG()->set('render/image/default_wrap' , false);

// This class will be added to the wrapping DIV element and would correspond to any CSS you have to managing responsive images.
CONFIG()->set('render/image/default_wrap_class' , 'image_wrapper');

// This is an extra class that gets added to show the correct size for this particular image. 
CONFIG()->set('render/image/default_wrap_size' , '');

// Enable or disable the autosize functionality across all wraps. This will calculate an image's aspect ratio and add CSS to the element matching.
CONFIG()->set('render/image/default_wrap_autosize' , false);
```

In most cases you will be interfacing with this by adding parameters to your call to the Image Render library. The examples below go through some common use cases, they all use DIV render method but wrapping can also be useful for IMG render.

```php
// Enable wrapping and output an image. Pretty simple.
// <div class="image_wrapper">
//      <div style="background-image: url(foo.png);"></div>
// </div>
echo IMAGE()->wrap(true)->div(get_field('image'));

// Enable wrapping, setting a size class to be added and output an image. 
// <div class="image_wrapper square">
//      <div style="background-image: url(foo.png);"></div>
// </div>
echo IMAGE()->wrap('square')->div(get_field('image'));

// Enable wrapping with the 'autosize' functionality turned on and output an image. In this example the image is a square.
// <div class="image_wrapper" style="padding-bottom: 100%;">
//      <div style="background-image: url(foo.png);"></div>
// </div>
echo IMAGE()->wrap('auto')->div(get_field('image'));

// If desired, you also can pass in autosize as it's own parameter in the render request:
echo IMAGE()->wrap(true)->autosize(true)->div(get_field('image'));
```


##### Image Render Library - Working with captions.

All images in Wordpress have the capability to store a caption, this information can then be displayed throught the Image Render Library. Default behavior can be defined in your site's framework `config.php` file, or pass custom parameters in when calling making a call to the library.

###### Caption Configuration:

```php
// Automatically display image caption by default whenever rendering an image.
CONFIG('render/image/default_caption', true);

// Control where the caption is displayed when an image is being wrapped.
// 'inside' : inside the wrapper, following the image
// 'outside' : outside the wrapper
CONFIG('render/image/default_caption_location', 'inside');

// Enable or disable default behavior for image captions.
CONFIG()->set('render/image/default_caption' , true);
```
- [View All Image Library Config Options ↗](configuration_options.md#image-settings)


###### Caption Rendering Examples:

```php
// Output simple IMG with caption.
// <img src="...." /><div class="caption">This is my caption.</div>
echo IMAGE()->caption(true)->img(get_field('image'));

// Output a wrapper IMG with caption and assign location.
// <div class="image-wrapper"><img src="...." /><div class="caption">This is my caption.</div></div>
echo IMAGE()->wrap(true)->caption('inside')->img(get_field('image'));
```

If you'd like more control over where the caption for your image appears, you can fetch it yourself. This helper function returns an image caption (either formatted or not) for you do to as you like.

```php
$caption = IMAGE()->get_caption(get_field('image'));
```

<br/>

------

### Frontend Library : SVG Render

This allows you to easily output an SVG image from your theme's assets directory to the page. Images can either be rendered as a standard IMG element, as a DIV with background image or with the SVG source embedded within the HTML.

##### Example Usage:

```php
// Display an in-line SVG (stored in assets/img/arrow.svg):
// <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7"><path d="M3.235 6.747c-.172 0-.336-.055-.474-.158L.31 4.72A.79.79 0 0 1 .16 3.615c.26-.336.776-.4 1.107-.148L3.15 4.903 5.018 2.86 7.422.246C7.53.128 7.69.04 7.867.01L7.992 0c.297 0 .565.164.702.43.153.3.098.667-.14.908l-4.75 5.167a.79.79 0 0 1-.57.243" fill="#4B4A4B" fill-rule="evenodd"/></svg>
echo SVG()->show('arrow');

// Display an SVG (stored in assets/img/arrow.svg) as IMG with attributes:
// <img src=".../assets/img/test.png" class="testing" alt="Name of the image" />
echo SVG()->img('arrow.svg', array('class'=>'testing', 'alt'=>'Name of the image'))
```

##### Params:

> - img($key, $attributes=array()) 
> - div($key, $attributes=array())
> - show($key)


------

### Frontend Library : Data Store

This allows...

##### Example Usage, Saving:

```php
DATASTORE()->save('example-feed', 'https://example.com/feed.json');
```

```php
DATASTORE()->save('example-array', ['id' => 123, 'title' => 'Hello']);
```

```php
DATASTORE()->save('example-callback', function() {
    return ['foo' => 'bar'];
});
```

```php
DATASTORE()->save('example-html', '<div>Hello</div>', 'text');
```

##### Example Usage, Loading:

```php
$feed = DATASTORE()->load('example-feed');

// $feed['cached_at']
// $feed['data']
```

```php
$data    = DATASTORE()->load_data('example-feed');
$time    = DATASTORE()->load_time('example-feed');
$url     = DATASTORE()->load_url('example-feed');
```
