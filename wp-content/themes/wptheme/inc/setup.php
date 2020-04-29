<?php
if (!function_exists('wptheme_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */

    /**
     * Add Extra Code to Primary Menu
     * @param string $menu
     * @param object $args
     * @return string modified menu
     * @link http://www.billerickson.net/customizing-wordpress-menus/
     *
     * @author Bill Erickson
     */
    function be_menu_extras($menu, $args)
    {
        if (!function_exists('is_plugin_active'))
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');

        if (class_exists('woocommerce') && is_plugin_active('woocommerce/woocommerce.php')) {


            $extras = '<div class="row mx-md-n5">
                        <div class="col px-md-5">
                            <div class="p-3 border bg-light">
                            <a  href="' . esc_url(wc_get_cart_url()).'/cart/' . '" >
                                <i class="fa fa-shopping-cart"></i>
                                </a>
                            </div>
						</div>
                        <div class="col px-md-5">
                            <div class="p-3 border bg-light">
                            <a  href="' . esc_url(wc_get_page_permalink('shop')) . '/shop/' . '" >
                                <i class="fa fa-shopping-bag"></i>
                                </a>
                                </div>
						 </div>
                        </div>';
            return $menu . $extras;
        }
    }

    add_filter('wp_nav_menu', 'be_menu_extras', 10, 2);
    function wptheme_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on WP Bootstrap Starter, use a find and replace
         * to change 'wptheme' to the name of your theme in all the template files.
         *
         */

        load_theme_textdomain('wptheme', get_template_directory() . '/lang');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        add_image_size('about-us-size', 250, 250);
        add_image_size('our-team-size', 250, 250);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('primary', 'wptheme'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'comment-form',
            'comment-list',
            'caption',
            'search-form'
        ));


        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('wptheme_custom_background_args', array(
            'default-color' => '',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        function wptheme_add_editor_styles()
        {
            add_editor_style('custom-editor-style.css');
        }

        add_action('admin_init', 'wptheme_add_editor_styles');

        // Redirect to welcome page after activation theme
        global $pagenow;

        if (is_admin() && 'themes.php' == $pagenow && isset($_GET['activated'])) {
            wp_redirect(admin_url('themes.php?page=wptheme-welcome'));
        }
    }


endif;
add_action('after_setup_theme', 'wptheme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wptheme_content_width()
{
    $GLOBALS['content_width'] = apply_filters('wptheme_content_width', 1170);
}

add_action('after_setup_theme', 'wptheme_content_width', 0);
?>