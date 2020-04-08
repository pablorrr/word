<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package LaRestaurante
 */
function larestaurante_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'larestaurante' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'larestaurante' ),
		'before_widget' => '<div style="style="padding:15px;" id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'larestaurante' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here.', 'larestaurante' ),
        'before_widget' => '<div style="padding:15px;" id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'larestaurante' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here.', 'larestaurante' ),
        'before_widget' => '<div style="padding:15px;" id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'larestaurante' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here.', 'larestaurante' ),
        'before_widget' => '<div style="padding:15px;" id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'larestaurante_widgets_init' );
