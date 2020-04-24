<?php
/**
 * wptheme Theme Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @package wptheme
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 */


// ------------------------------
// register sections           -
// ------------------------------

function wptheme_customize_register($wp_customize)
{

    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'refresh';
    $wp_customize->get_control('header_textcolor')->section = 'others_setting';
    $wp_customize->get_control('background_image')->section = 'others_setting';
    $wp_customize->get_control('background_color')->section = 'others_setting';


    // Banner Section
    $wp_customize->add_section(
        'header_image',
        array(
            'title' => __('Header Banner', 'wptheme'),
            'priority' => 30,
        )
    );

    // Add custom header and sidebar background color setting and control.
    $wp_customize->add_setting(
        'header_background_color', array(
            'default' => '#fff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize, 'header_background_color', array(
                'label' => __('Header and Menu Navbar Background Color', ''),
                'description' => __('Applied to the header on small screens.', ''),
                'section' => 'header_image',
            )
        )
    );
    //Other Settings Section
    $wp_customize->add_section(
        'others_setting',
        array(
            'title' => __('Other Customizations', 'wptheme'),
            //'description' => __( 'This is a section for the header banner Image.', 'wptheme' ),
            'priority' => 40,
        )
    );
    $wp_customize->add_section(
        'colors',
        array(
            'title' => __('Background Color', 'wptheme'),
            'description' => __('This is a section for Background Color.', 'wptheme'),
            'priority' => 50,
            'panel' => 'styling_option_panel',
        )
    );
    $wp_customize->add_section(
        'background_image',
        array(
            'title' => __('Background Image', 'wptheme'),
            //'description' => __( 'This is a section for the header banner Image.', 'wptheme' ),
            'priority' => 60,
            'panel' => 'styling_option_panel',
        )
    );

    //link color
    $wp_customize->add_setting('link-color', array(
        'default' => '#555',
        'type' => 'theme_mod',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link-color', array(
        'label' => __('Link Color', 'wptheme'),
        'section' => 'others_setting',
        'settings' => 'link-color',
    )));


    //edition icon
    $wp_customize->selective_refresh->add_partial(
        'blogname', array(
            'selector' => '.site-title a',
            'render_callback' => 'wptheme_customize_partial_blogname',
        )
    );
    $wp_customize->selective_refresh->add_partial(
        'blogdescription', array(
            'selector' => '.site-description',
            'render_callback' => 'wptheme_customize_partial_blogdescription',
        )
    );

//https://wpbeaches.com/adding-text-block-via-customizer-wordpress/

    // Create custom panel.
    $wp_customize->add_panel('opening_hours', array(
        'priority' => 500,
        'theme_supports' => '',
        'title' => __('Opening Hours', 'wptheme'),
        'description' => __('Set editable text for certain content.', 'wptheme'),
    ));

    // Add section - Monday
    $wp_customize->add_section('monday', array(
        'title' => __('Monday', 'wptheme'),
        'panel' => 'opening_hours',
        'priority' => 10
    ));
    // Add setting
    $wp_customize->add_setting('monday_text_block', array(
        'default' => __('default text', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'monday',
            array(
                'label' => __('Monday', 'wptheme'),
                'section' => 'monday',
                'settings' => 'monday_text_block',
                'type' => 'text'
            )
        )
    );

    // Add section - Tuesday
    $wp_customize->add_section('tuesday', array(
        'title' => __('Tuesday', 'wptheme'),
        'panel' => 'opening_hours',
        'priority' => 11
    ));

    // Add setting
    $wp_customize->add_setting('tuesday_text_block', array(
        'default' => __('default text', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'tuesday',
            array(
                'label' => __('Tuesday', 'wptheme'),
                'section' => 'tuesday',
                'settings' => 'tuesday_text_block',
                'type' => 'text'
            )
        )
    );

    // Add section - Wednesday
    $wp_customize->add_section('wednesday', array(
        'title' => __('Wednesday', 'wptheme'),
        'panel' => 'opening_hours',
        'priority' => 11
    ));

    // Add setting
    $wp_customize->add_setting('wednesday_text_block', array(
        'default' => __('default text', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'wednesday',
            array(
                'label' => __('Wednesday', 'wptheme'),
                'section' => 'wednesday',
                'settings' => 'wednesday_text_block',
                'type' => 'text'
            )
        )
    );

    // Add section - Thursday
    $wp_customize->add_section('thursday', array(
        'title' => __('Thursday', 'wptheme'),
        'panel' => 'opening_hours',
        'priority' => 11
    ));

    // Add setting
    $wp_customize->add_setting('thursday_text_block', array(
        'default' => __('default text', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'thursday',
            array(
                'label' => __('Thursday', 'wptheme'),
                'section' => 'thursday',
                'settings' => 'thursday_text_block',
                'type' => 'text'
            )
        )
    );


    // Add section - Friday
    $wp_customize->add_section('friday', array(
        'title' => __('Friday', 'wptheme'),
        'panel' => 'opening_hours',
        'priority' => 12
    ));

    // Add setting
    $wp_customize->add_setting('friday_text_block', array(
        'default' => __('default text', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'friday',
            array(
                'label' => __('Friday', 'wptheme'),
                'section' => 'friday',
                'settings' => 'friday_text_block',
                'type' => 'text'
            )
        )
    );

    // Add section -Saturday
    $wp_customize->add_section('saturday', array(
        'title' => __('Saturday', 'wptheme'),
        'panel' => 'opening_hours',
        'priority' => 13
    ));

    // Add setting
    $wp_customize->add_setting('saturday_text_block', array(
        'default' => __('default text', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'saturday',
            array(
                'label' => __('Saturday', 'wptheme'),
                'section' => 'saturday',
                'settings' => 'saturday_text_block',
                'type' => 'text'
            )
        )
    );


    // Add section -Sunday
    $wp_customize->add_section('sunday', array(
        'title' => __('Sunday', 'wptheme'),
        'panel' => 'opening_hours',
        'priority' => 14
    ));

    // Add setting
    $wp_customize->add_setting('sunday_text_block', array(
        'default' => __('default text', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'sunday',
            array(
                'label' => __('Sunday', 'wptheme'),
                'section' => 'sunday',
                'settings' => 'sunday_text_block',
                'type' => 'text'
            )
        )
    );

    //telephone number
    // Create custom panel.
    $wp_customize->add_panel('phone_number', array(
        'priority' => 500,
        'theme_supports' => '',
        'title' => __('Telephone number', 'wptheme'),
        'description' => __('Set editable text for certain content.', 'wptheme'),
    ));

    // Add section - Monday
    $wp_customize->add_section('tel_number', array(
        'title' => __('Telephone number', 'wptheme'),
        'panel' => 'phone_number',
        'priority' => 10
    ));
    // Add setting
    $wp_customize->add_setting('tel_text_block', array(
        'default' => __('000 000 000', 'wptheme'),
        'sanitize_callback' => 'sanitize_text'
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'tel_number',
            array(
                'label' => __('Phone Number', 'wptheme'),
                'section' => 'tel_number',
                'settings' => 'tel_text_block',
                'type' => 'text'
            )
        )
    );




    // Sanitize text
    function sanitize_text($text)
    {
        return sanitize_text_field($text);
    }





}

add_action('customize_register', 'wptheme_customize_register');


// ------------------------------
// hook and callbacks sections           -
// ------------------------------

add_action('wp_head', 'wptheme_customizer_css');
function wptheme_customizer_css()
{
    ?>
    <style type="text/css">
        {
            background:
        <?php echo get_theme_mod('header_bg_color_setting', '#fff'); ?>
        ;
        }
    </style>
    <?php
}

/**
 * Render the site title for the selective refresh partial.
 */

function wptheme_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 */
function wptheme_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wptheme_customize_preview_js()
{
    wp_enqueue_script('wptheme-customizer',
        get_template_directory_uri() . '/inc/assets/js/customize-preview.js', array('jquery', 'customize-preview'), '', true);
}

add_action('customize_preview_init', 'wptheme_customize_preview_js');

add_action('wp_head', 'wptheme_print_link_color_style');
function wptheme_print_link_color_style()
{
    $link_color = get_theme_mod('link-color', '#000000');
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

        h1#site-title > a, h1#site-title > a:hover {
            color: white;
        }


        a.link-front {
            color: <?php echo $link_color; ?>;
        }

        a.link-post {
            color: <?php echo $link_color; ?>;
        }


        #services > div > div.row.text-center h4 > a.link-post {
            color: <?php echo $link_color; ?>;
        }

        #about_us div.timeline-heading > h4 > a.link-post {
            color: <?php echo $link_color; ?>;
        }

        #team > div > div.row.text-center div > h4 > a.link-post {
            color: <?php echo $link_color; ?>;
        }

        #restaurants > div > div.row.text-center h4 > a.link-post {
            color: <?php echo $link_color; ?>;
        }

        #menu > div div > h4 > a.link-post {
            color: <?php echo $link_color; ?>;
        }

        #footer h5 > a {
            color: <?php echo $link_color; ?>;
        }

        #footer h5 > a {
            color: <?php echo $link_color; ?>;
        }

        #footer div ul > li span > a a {
            color: <?php echo $link_color; ?>;
        }

    </style>

    <?php
}