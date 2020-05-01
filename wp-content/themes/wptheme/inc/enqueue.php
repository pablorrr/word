<?php

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// ENQUEUE SCRIPTS AND STYLES. 
// -----------------------------------------------------------------------------------------------
// ===============================================================================================

function wptheme_scripts()
{

    //load WP styles
   // wp_enqueue_style('wptheme-style', get_stylesheet_uri());


// ---------------------------------------------
// load theme and Bootstrap CSS  conditionally     -
// ---------------------------------------------

    if (!is_admin()) {
        //load Bootstrap Css
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css');
        // load theme styles
        wp_enqueue_style('wptheme', get_template_directory_uri() . '/inc/assets/css/wptheme-css/wptheme.css', array(), '1.0', false);

        //affix effect of Bootstrap
        wp_enqueue_style('affix', get_template_directory_uri() . '/inc/assets/css/wptheme-css/affix.css', array(), '1.0', false);
        //paralax header effect
        wp_enqueue_style('styleparx', get_template_directory_uri() . '/inc/assets/css/wptheme-css/stylepar.css', array(), '1.0.0', 'all');


    }

// -----------------------------------------------
// load dashicon WP style, Jquery, HTML5 support     -
// -----------------------------------------------	

    wp_enqueue_style('dashicons');
    wp_enqueue_script('jquery');
    // Internet Explorer HTML5 support
    wp_enqueue_script('html5hiv', get_template_directory_uri() . '/inc/assets/js/html5.js', array(), '3.7.0', false);
    wp_script_add_data('html5hiv', 'conditional', 'lt IE 9');

// ---------------------------------------------------
// load Bootstrap Jquery, Theme Jquery conditionally    -
// --------------------------------------------------		

    if (!is_admin()) {
        //fontawesome
        wp_enqueue_script('wptheme-fontawesome', get_template_directory_uri() . '/inc/assets/js/fontawesome/fontawesome-all.min.js', array());
        //fontawesome-V4
        wp_enqueue_script('wptheme-fontawesome-v4', get_template_directory_uri() . '/inc/assets/js/fontawesome/fa-v4-shims.min.js', array());
        //popper
        wp_enqueue_script('wptheme-popper', get_template_directory_uri() . '/inc/assets/js/popper.min.js', array());
        //theme's JS
        wp_enqueue_script('wptheme-themejs', get_template_directory_uri() . '/inc/assets/js/themes-scripts.js', array());
        //skip focus link
        wp_enqueue_script('wptheme-skip-link-focus-fix', get_template_directory_uri() . '/inc/assets/js/skip-link-focus-fix.js', array(), '20151215', true);
        //easing scroll menu effect
        wp_enqueue_script('jquery-effects-core', get_template_directory_uri() . '/inc/assets/js/easing-effect/jquery.easing.min.js', array('jquery'), '1.8.8', true);
        // scroll nav effect
        wp_enqueue_script('nav-scroll', get_template_directory_uri() . '/inc/assets/js/scrolling-nav.js', array('jquery'), '1.8.8', true);
        //bt carousel jqery
        wp_enqueue_script('ajax-googleapis', get_template_directory_uri() . '/inc/assets/js/jquery.min.js');

        //Bootstrap Jquery
        wp_enqueue_script('wptheme-bootstrapjs', get_template_directory_uri() . '/inc/assets/js/bootstrap.min.js');


        //affix plugin
        wp_enqueue_script('affix-plugin', get_template_directory_uri() . '/inc/assets/js/affix-plugin.js', false, '', true);
    }


// -

// ---------------------------------------------------
//  thread comments support  -
// --------------------------------------------------		 

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'wptheme_scripts');

// ---------------------------------------------
// remove WooCommerce css style when is unnecessary     -
// source: https://crunchify.com/how-to-stop-loading-woocommerce-js-javascript-and-css-files-on-all-wordpress-postspages/
// ---------------------------------------------

/* */
add_action('wp_enqueue_scripts', 'crunchify_disable_woocommerce_loading_css_js');

function crunchify_disable_woocommerce_loading_css_js()
{

    // Check if WooCommerce plugin is active
    if (function_exists('is_woocommerce')) {

        // Check if it's any of WooCommerce page
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {

            ## Dequeue WooCommerce styles
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-smallscreen');

            ## Dequeue WooCommerce scripts
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('wc-add-to-cart');

        }
    }
}