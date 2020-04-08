<?php
/**
 * Larestaurante Theme Customizer
 *
 * @package larestaurante
 
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 
 */


// ------------------------------
// register sections           -
// ------------------------------

function larestaurante_customize_register( $wp_customize ) {
	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'refresh';
    $wp_customize->get_control( 'header_textcolor'  )->section = 'others_setting';
    $wp_customize->get_control( 'background_image'  )->section = 'others_setting';
    $wp_customize->get_control( 'background_color'  )->section = 'others_setting';
	

	// Banner Section
     $wp_customize->add_section(
        'header_image',
        array(
            'title' => __( 'Header Banner', 'larestaurante' ),
            'priority' => 30,
        )
    );

	// Add custom header and sidebar background color setting and control.
	$wp_customize->add_setting(
		'header_background_color', array(
			'default'           => '#fff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'header_background_color', array(
				'label'       => __( 'Header and Menu Navbar Background Color', 'larestaurante' ),
				'description' => __( 'Applied to the header on small screens.', 'larestaurante' ),
				'section'     => 'header_image',
			)
		)
	);
    //Other Settings Section
   $wp_customize->add_section(
        'others_setting',
        array(
            'title' => __( 'Other Customizations', 'larestaurante' ),
            //'description' => __( 'This is a section for the header banner Image.', 'larestaurante' ),
            'priority' => 40,
        )
    );
    $wp_customize->add_section(
        'colors',
        array(
            'title' => __( 'Background Color', 'larestaurante' ),
            'description' => __( 'This is a section for Background Color.', 'larestaurante' ),
            'priority' => 50,
            'panel' => 'styling_option_panel',
        )
    );
    $wp_customize->add_section(
        'background_image',
        array(
            'title' => __( 'Background Image', 'larestaurante' ),
            //'description' => __( 'This is a section for the header banner Image.', 'larestaurante' ),
            'priority' => 60,
            'panel' => 'styling_option_panel',
        )
    );
	
	//link color
	 $wp_customize->add_setting( 'link-color', array(
        'default'        => '#555',
        'type'           => 'theme_mod',
        'transport'      => 'postMessage',
        'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link-color', array(
        'label'   => __( 'Link Color' , 'larestaurante'),
        'section' => 'others_setting',
        'settings'   => 'link-color',
    ) ) ); 
	
	
	//edition icon 
	 $wp_customize->selective_refresh->add_partial(
		'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'larestaurante_customize_partial_blogname',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'larestaurante_customize_partial_blogdescription',
		)
	); 

 }
add_action( 'customize_register', 'larestaurante_customize_register' );


// ------------------------------
// hook and callbacks sections           -
// ------------------------------

add_action( 'wp_head', 'larestaurante_customizer_css');
function larestaurante_customizer_css()
{
    ?>
    <style type="text/css">
         { background: <?php echo get_theme_mod('header_bg_color_setting', '#fff'); ?>; }
    </style>
    <?php
}

/**
 * Render the site title for the selective refresh partial.
 */
 
function larestaurante_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 */
function larestaurante_customize_partial_blogdescription() {
	bloginfo( 'description' );
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function larestaurante_customize_preview_js() {
    wp_enqueue_script( 'larestaurante-customizer',
	get_template_directory_uri() .'/inc/assets/js/customize-preview.js', array( 'jquery','customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'larestaurante_customize_preview_js' );

add_action( 'wp_head', 'larestaurante_print_link_color_style' );
function larestaurante_print_link_color_style() {
 $link_color = get_theme_mod('link-color','#000000');
?>
	<style>
		/* Link color */
		a,
		#site-title a:focus,
		#site-title a:hover,
		#site-title a:active,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-title a:active,
		
		section.recent-posts .other-recent-posts a[rel="bookmark"]:hover,
		section.recent-posts .other-recent-posts .comments-link a:hover,
		.format-image footer.entry-meta a:hover,
		#site-generator a:hover {
			color: <?php echo $link_color; ?>;
		}
		section.recent-posts .other-recent-posts .comments-link a:hover {
			border-color: <?php echo $link_color; ?>;
		}
		article.feature-image.small .entry-summary p a:hover,
		.entry-header .comments-link a:hover,
		.entry-header .comments-link a:focus,
		.entry-header .comments-link a:active,
		.feature-slider a.active {
			background-color: <?php echo $link_color; ?>;
		}
		
		h1#site-title > a,h1#site-title > a:hover{color:white;}
		
		
		a.link-front{color:<?php echo $link_color; ?>;}
		a.link-post{color:<?php echo $link_color; ?>;}
		
		
		#services > div > div.row.text-center  h4 > a.link-post {color:<?php echo $link_color; ?>;}
		#about_us div.timeline-heading > h4 > a.link-post {color:<?php echo $link_color; ?>;}
		
		#team > div > div.row.text-center div > h4 > a.link-post {color:<?php echo $link_color; ?>;}
		#restaurants > div > div.row.text-center  h4 > a.link-post {color:<?php echo $link_color; ?>;}
		#menu > div  div > h4 > a.link-post {color:<?php echo $link_color; ?>;}
		
		#footer h5 > a{color:<?php echo $link_color; ?>;}
		#footer h5 > a{color:<?php echo $link_color; ?>;}
		#footer div ul > li span > a a{color:<?php echo $link_color; ?>;}
		
	</style>
	
<?php 
}