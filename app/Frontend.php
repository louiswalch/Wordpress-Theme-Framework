<?php 

namespace HelloFramework;

class Frontend {

	public function __construct() {

        // Public render helper classes, must be called from global instance functions.
        require_once HELLO_DIR . '/public/render/Image.php';
        require_once HELLO_DIR . '/public/render/ImageInstance.php';
        require_once HELLO_DIR . '/public/render/SVG.php';
        require_once HELLO_DIR . '/public/render/SVGInstance.php';
        require_once HELLO_DIR . '/public/render/Include.php';
        require_once HELLO_DIR . '/public/render/IncludeInstance.php';
        require_once HELLO_DIR . '/public/render/Module.php';
        require_once HELLO_DIR . '/public/render/ModuleInstance.php';

        // Add theme specified CSS and JS assets.
        new FrontendAssets();

        // Add ability to force login from visitors to view the website.
        new FrontendAuthentication();

        // Add custom query params.
        $this->_addQueryParams();

        // Remove admin bar from website
        if (!CONFIG('frontend/admin_bar')) {
            add_filter('show_admin_bar', '__return_false');
        }

        // Add the current environment as class on BODY.
        $this->_addEnvironmentClass();

        // Add quick-link to edit page.
        $this->_addEditLink();

        // THIRD-PARTY - Clean up the Yoast SEO fields added to user profile
        // add_filter( 'user_contactmethods', 'clean_user_contactmethods', 10, 1 );

        // Clean up the meta title and make it more SEO friendly.
        add_filter( 'wp_title', [$this, 'betterPageTitles'], 10, 3 );

        // // clean up gallery output in wp
        add_filter( 'gallery_style', [$this, 'removeGalleryStyle']);

        // // cleaning up excerpt, remove 'Read More' text.
        add_filter( 'excerpt_more', '__return_false' );

        // // Archive titles are so ugly, let's change that.
        add_filter( 'get_the_archive_title', [$this, 'betterArchiveTitles']);

        // Deactivate the emoji stuff in Wordpress
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' ); 

        // category feeds
        remove_action( 'wp_head', 'feed_links_extra', 3 );

        // post and comment feeds
        remove_action( 'wp_head', 'feed_links', 2 );

        // EditURI link
        remove_action( 'wp_head', 'rsd_link' );

        // windows live writer
        remove_action( 'wp_head', 'wlwmanifest_link' );

        // previous link
        remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );

        // start link
        remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );

        // links for adjacent posts
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

        // WP version
        remove_action( 'wp_head', 'wp_generator' );

        // WP-JSON tag
        remove_action( 'wp_head', 'rest_output_link_wp_head' );

        // remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

        // Remove the REST API endpoint.
        remove_action('rest_api_init', 'wp_oembed_register_route');

        // Remove DNS-Prefetch
        remove_action( 'wp_head', 'wp_resource_hints', 2 );

        // Turn off oEmbed auto discovery.
        // Don't filter oEmbed results.
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

        // Remove oEmbed discovery links.
        remove_action('wp_head', 'wp_oembed_add_discovery_links');

        // Remove oEmbed-specific JavaScript from the front-end and back-end.
        remove_action('wp_head', 'wp_oembed_add_host_js');

        // Other dumb stuff in the HEAD
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
        remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

        // Remove pesky injected css for recent comments widget
        add_filter( 'show_recent_comments_widget_style', '__return_false', 1 );

    }


    // ------------------------------------------------------------
    // Clean up the meta title and make it more SEO friendly.
    // http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html

    public function betterPageTitles($title) {

        global $page, $paged;

        // Don't affect in feeds.
        if ( is_feed() ) return $title;

        $new_title = array();
        $new_title[] = trim($title);

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );

        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $new_title[] = $site_description;
        }

        // Add a page number if necessary:
        if ( $paged >= 2 || $page >= 2 ) {
           $new_title[] = sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
        }

        // Remove empty array values.
        $new_title = array_filter($new_title, function($value) { return $value !== ''; });
 
        $new_title[] = get_bloginfo( 'name' );

        return implode(' - ', $new_title);

    }


    // ------------------------------------------------------------
    // Clean up the archive title
    // This will remove "Category:", "Tag:", "Author:" etc from the archive title

    public function betterArchiveTitles($title) {

        if ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = '<span class="vcard">' . get_the_author() . '</span>' ;
        }

        return $title;

    }


    // ------------------------------------------------------------

    function removeGalleryStyle($css) {
        return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
    }


    // ------------------------------------------------------------

    private function _addQueryParams() {

        if (!CONFIG('frontend/query_params')) return;

        add_filter( 'query_vars', function( $vars ){
            return array_merge($vars, CONFIG('frontend/query_params'));    
        });

    }


    // ------------------------------------------------------------

    private function _addEnvironmentClass() {

        add_filter('body_class', function($classes) {
            $classes[] = 'is-' . detect_environment();
            return $classes; 
        });

    }


    private function _addEditLink() {

        if (!CONFIG('frontend/edit_link') || !is_user_logged_in()) return;

        add_action('wp_footer', function() {
            echo edit_post_link(CONFIG('frontend/edit_link/text'), '<div class="wordpress-edit-button">', '</div>', null, 'no-barba' );
        });

    }


}