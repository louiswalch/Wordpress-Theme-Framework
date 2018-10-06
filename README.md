# Wordpress Theme Framework


==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 
  
> 1. Template Libraries
> 		* [Image](#envirius)
> 		* SVG
> 		* Include
> 		* Module
> 		* ~~Metatag~~
> 2. Template Helper Functions
> 3. Environment Detection
> 4. Configuration
> 5. Configuration Options
> 		* Frontend 
> 		* Login Page 
> 		* Dashboard — General 
> 		* Dashboard — Meatball 
> 		* Dashboard - Content Editor 
> 		* Custom Image Size 
> 		* Email 
> 6. Installation 
> 7. Custom Post Types & Taxonomy
> 8. Demo Theme



```
TODO Items

Various aspects across the entire framework that are pending finalization. Feel free to pitch in.

- [ ] Improve Configuration Options Layout 
- [ ] Write Product introduction.
- [ ] Configuration Options - Reformat section.
- [ ] Configuration Options - breakdown all chained methods and end points for each example.
- [ ] Collapse code examples for Renders.
```

<br/>
-------------------------------------------------

### Template Library : Image Render

==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 

End:

 - img($image=false, $size=false, $showcaption=false)
 - src($image=false, $size=false)

Methods:

- srcset($incoming=true) {
- wrap($incoming=false) {
- caption($incoming=null) {
- classes($incoming=false) {
- lazy($incoming=true){
- size($incoming=false) {    }
- alpha($incoming=false)
- attributes($incoming=false) 
- attr($one=false, $two=
- pinnable($incoming=false) {

<details>
  <summary>
    **View Usage Examples**
  </summary>

```php
// Output IMG element from custom field value.
echo IMAGE()->img(get_field('image'));
	
// Output IMG element from Featured Image with class attribute.
echo IMAGE()->class('thumbnail')->div(get_post_thumbnail_id()); 
	
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

</details>



<br/>
-------------------------------------------------

### Template Library : SVG Render

==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 

Ends:

- img($key, $attributes=array()) 
- div($key, $attributes=array())
- show($key)

Metthods:

 - srcset($incoming=true) {
 - wrap($incoming=false) {
 - caption($incoming=null) {
 - classes($incoming=false) alse) {
 - lazy($incoming=true){vfalse) {
 - size($incoming=false) {flse) {
 - alpha($incoming=false) { false) {
 - attributes($incoming=false) { false) {
 - 	pinnable($incoming=false) {

<details>
  <summary>
    **View Usage Examples**
  </summary>

```php
// Display an in-line SVG (stored in assets/img/arrow.svg):
// <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7"><path d="M3.235 6.747c-.172 0-.336-.055-.474-.158L.31 4.72A.79.79 0 0 1 .16 3.615c.26-.336.776-.4 1.107-.148L3.15 4.903 5.018 2.86 7.422.246C7.53.128 7.69.04 7.867.01L7.992 0c.297 0 .565.164.702.43.153.3.098.667-.14.908l-4.75 5.167a.79.79 0 0 1-.57.243" fill="#4B4A4B" fill-rule="evenodd"/></svg>
echo SVG()->show('arrow');

// Display an SVG (stored in assets/img/arrow.svg) as IMG with attributes:
// <img src=".../assets/img/test.png" class="testing" alt="Name of the image" />
echo SVG()->img('arrow.svg', array('class'=>'testing', 'alt'=>'Name of the image'))
```

</details>




<br/>
-------------------------------------------------


### Template Library : Includes Render


==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 

**Caching**
**Cearing cache.**


**End:**

 - fetch($key, $data=false)     // Load and output the contents of a snippet, either live or from cache.
 - show($key, $data=false)

**Methods;**

 - cache($global=false, $set=true, $key=null) {
 - dir($dir=null) {
 - life($life=null) {
 - data($one=null, $two=null) {
 - debug($incoming=null) {
 - key($incoming=false) {

<details>
  <summary>
    **View Usage Examples**
  </summary>
  
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
INCLUDES()->data([ 'section'=>'about', 'background'=>'blue'])->show('header');

// Get the contents of an include and store in variable.
$include_contents = INCLUDES()->fetch('share_icons');
```

</details>



```php
get_include_var($key, $group = null)
```


<br/>
-------------------------------------------------


### Template Library : Modules Render

==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 

```php
get_module_var($key, $group = null)
```

<br/>
-------------------------------------------------


### ~~Template Library : Meta Tag Render~~

Not yet.

<br/>
-------------------------------------------------

### Environment Detection

==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 


| Environment | Domain Match |
|--|--|
| Development | ['.local', '.dev'] |
| Staging | ['staging', 'beta'] |
| Producton | * |

<br/>
-------------------------------------------------


### Configuration

Control over the functionality contained in the framework is all done through configuration files you place in your theme. These files are loaded as inheriting from each-ohter, so the more defined the environment is potentially the moremspecificed it's confg settings.

The system will always first load the built-in configurationl file, theeck to see if there are user-specific or enarmy lea


If you would like to assign a new configuration value, create a file inside your theme at `_framework/config.php`. This theme-specific configuration file can then be updated with your preferred settings. 

Here is an example of a very common practice - setting your CSS and JS files to render on the new Wordpress site.

```php
# _framework/config.php
CONFIG()->set('frontend/assets/css', ['css/site.min.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.min.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.min.js', 'js/site.min.js']);
```

In addition base level configuration file, you can also create environment-specifc (development, staging, production) files which would override all devault settings only on that server.

This could be useful for disableing cache on development/staging or delivering non-minified assets for easier debugging.

```php
# _framework/config_development.php
CONFIG()->set('cache', [false]);
CONFIG()->set('frontend/assets/css', ['css/site.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.js', 'js/site.js']);
```

```php
<? pr(CONFIG()->dump(), 'Site Configuration'); ?>
```


<br/>
-------------------------------------------------





### Configuration Options


`frontend/assets/version`<br/>
Default: `false`<br/>
Type: `bool`<br/>
Add cachebusting version to assets paths.



##### Custom Image Sizes

[ TODO: Add details on how to define ]

##### Custom Metaboxes

[ TODO: Add details on how to define ]

##### Content Editor

[ TODO: Add details on how to define ]


<br/>
-------------------------------------------------


### Template Functions

Functionality
- asset($file='', $echo=false, $server=false)
- require_all_files($directory = false)
- require_all_files($directory = false)
- detect_environment($match = false)
- is_ajax_request()

- string
	- X
	- X
	- X
- detect_area($match = false)
- detect_environment($match = false)
- function asset($file='', $echo=false, $server=false) {
- require_all_files($directory = false)
- is_ajax_request()
- top_level_slug()


Output:
- pr($val, $title=false, $echo=true) {
-  safemail($email, $text = '', $title = '', $class = '') {
-  widont($str = '') {
-  format_plain_text($string) {
-  get_excerpt($field = 'snippet', $count=90, $settings=array()){
-  cycle($first_value, $values = '*') {

[ TODO: Add description ]
[ TODO: Add examples ]



<br/>
-------------------------------------------------

### Installation


```php
#functions.php
// Make sure the Theme Framework plugin is present. Required by this theme.
if (!is_admin() && !defined('HELLO_DIR')) exit('Error. This theme is meant to be used with the <a href="https://github.com/louiswalch/Wordpress-Theme-Framework">Theme Framework</a>.');
```

<br/>
-------------------------------------------------

### Custom Post Types & Taxonomy


<br/>
-------------------------------------------------


### Demo Theme

==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 

[https://github.com/louiswalch/Wordpress-Theme-Framework-Demo](https://github.com/louiswalch/Wordpress-Theme-Framework-Demo)

<br/>
-------------------------------------------------


### License

==Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.== 


