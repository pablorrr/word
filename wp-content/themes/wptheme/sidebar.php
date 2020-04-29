<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wptheme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area col-sm-12 col-md-4 col-lg-4" role="complementary">
 <img  src ="<?php echo get_template_directory_uri();?>/img/divider.svg"/>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->