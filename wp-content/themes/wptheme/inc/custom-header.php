<?php
/**
 * Implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package larestaurante
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * 
 */
function larestaurante_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'larestaurante_custom_header_args', array(
	
		'default-image'          => get_template_directory_uri() . '/img/about-us-1.jpg', 
		'default-text-color'     => 'fff',
		'width'                  => 2500,
		'height'                 => 2000,
		'flex-height'            => true,
		'wp-head-callback'       => 'larestaurante_header_style',
	) ) );
	
   add_theme_support( 'custom-logo', array(
	   'height'      => 175,
	   'width'       => 400,
	   'flex-width' => true,
	   'header-text' => array( 'site-title'),
	   ) );
}
add_action( 'after_setup_theme', 'larestaurante_custom_header_setup' );

if ( ! function_exists( 'larestaurante_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog.
 *
 * 
 */
 
function larestaurante_header_style() {
	
	$header_text_color = get_header_textcolor();
	$header_background_color = get_theme_mod( 'header_background_color', 'default' );

	/*
	 * If no custom options for text are set, let's bail.
	 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
	 */
	if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		h1.site-title,
		a.site-title ,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that.
		else :
	?>
        a.site-title,
		.site-description,
		a.check-out,
		<!--a.page-scroller	{color: #<?php// echo esc_attr( $header_text_color ); ?>;}-->
		nav.navbar a {color: #<?php echo esc_attr( $header_text_color ); ?>;}
		<?php endif; ?>
		
		
		 
	</style>
<?php }

endif;//if ( ! function_exists( 'larestaurante_header_style' ) ) :

if ( ! function_exists( 'larestaurante_header_background_color_css' ) ) :

function larestaurante_header_background_color_css() {

	$header_background_color = get_theme_mod( 'header_background_color', '#fff' );
	?>
	
	<style>
	
	#home,.navbar,.navbar-expand-md,.fixed-top,.affix-top
	{background-color:<?php echo esc_attr($header_background_color);?>; }
	
	<?php if (!has_header_image()):?>
	header#home{width:100%; height:150px;} 
	 <?php endif;?>
	
	
	</style>
	
	
<?php   }

add_action( 'wp_head', 'larestaurante_header_background_color_css');
endif;
?>