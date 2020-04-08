<?php 
if ( ! function_exists( 'larestaurante_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function larestaurante_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WP Bootstrap Starter, use a find and replace
	 * to change 'larestaurante' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'larestaurante', get_template_directory() . '/lang' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
    add_image_size( 'about-us-size', 250, 250 );
	add_image_size( 'our-team-size', 250, 250 );
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'primary', 'larestaurante' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'caption',
		'search-form'
	) );
	
	
    
	// Set up the WordPress core custom background feature.
	  add_theme_support( 'custom-background', apply_filters( 'larestaurante_custom_background_args', array(
		'default-color' => '',
		'default-image' => '',
	) ) );  

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

    function larestaurante_add_editor_styles() {
        add_editor_style( 'custom-editor-style.css' );
    }
    add_action( 'admin_init', 'larestaurante_add_editor_styles' );
	
	// Redirect to welcome page after activation theme 
	global $pagenow;
	
    if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
  	wp_redirect( admin_url( 'themes.php?page=larestaurante-welcome' ) );}
}



endif;
add_action( 'after_setup_theme', 'larestaurante_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function larestaurante_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'larestaurante_content_width', 1170 );
}
add_action( 'after_setup_theme', 'larestaurante_content_width', 0 );
?>