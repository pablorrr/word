<?php
require( get_template_directory() . '/inc/theme-options.php' );
/*  if ( 'dark' == $theme_options['color_scheme'] )
		$default_background_color = '1d1d1d';
	else
		$default_background_color = 'e2e2e2';   */

	
	
	

add_action( 'init', 'register_my_menus' );
function register_my_menus() {
  register_nav_menus(
	array( 'header-menu' => 'Menu naglowka')
  );
}

add_action('after_setup_theme','_support');

function _support(){
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 120, 100 );
add_image_size( 'single-post', 120, 100 );
}

if ( function_exists('register_sidebar') ){
  register_sidebar(array(
         'name' => 'moj sidebar',
		'id' => 'widget-zone',
		'description' => 'to moja strefa widgetow',
		'before_widget' => '<div id="%1$s" class="box widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
));}
        else
            return;

       // add_action('after_setup_theme','_author_name');
        function _author_name (){
            $user_info = get_userdata(1);
            echo 'Autor: ' . $user_info->user_login;
        }
        
       // add_action('after_setup_theme','_content_tag');
        function _content_tag(){
			
        bloginfo('description');
        echo ' ';echo 'URL:';
        bloginfo('url');
         echo ' ';
        }
/* // filter dla galerii wp		
add_filter( 'use_default_gallery_style', '__return_false' );	 */

	
//wykorzytsanie mechanizmu rttypow posta iumagae jako galerii		
//add_filter
function my_the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since Twenty thirteen 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	 
	 /*  apply_filters- wywoluje funkcje modyfikujac a zmienna , param:1- nazwa funkcji callback ,2- argumenty do przesylania do modyfikacji, tutaj: filter modyfikuje rozmiar obrazka, dalej tablica z wymiarami obrazka */
	 
	$attachment_size     = apply_filters( 'twentythirteen_attachment_size', array( 324, 324 ) );
	
	/* Retrieve the URL for an attachment.- wp_get_attachment_url()
		param:$post_id (int) (Optional) Attachment ID. Default 0.
		*/
	$next_attachment_url = wp_get_attachment_url();
	
	/* get_post- uzyskuje pst ale nie printuje go $post
		(int|WP_Post|null) (Optional) Post ID or post object. Defaults to global $post.
        Default value: null
		*/
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	 /* get_posts- uzyskuje dostep do wszytskich postow okreslonych poprzez array w parametrze */
	 
	 /*  post rodzicielski, njprwd dlatego ze obrazki sa jako typ posta //image sa osadzone w winnym typie posta  tutaj :standardowym
	
	//ststaus posta - odziedziczony-logiczne posty sa odzieciczony wzgledem //posta nadrzednego - standardowefgo*/
	 
	$attachment_ids = get_posts( array(
	
	
	
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',//pola z jakich bedzie korzytsac zapytanie
		'numberposts'    => -1,// wybierz wszytskie posty
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',//typ posta zalacznik
		'post_mime_type' => 'image',//typp mime posta obrazek , mime to standard rozpoznawaniatresci w sieci
		'order'          => 'ASC',//uporzadkowanie rosnaco
		'orderby'        => 'menu_order ID',//uporzadkowanie wg id
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $idx => $attachment_id ) {
			
			if ( $attachment_id == $post->ID ) {//jesli id zalacznika bedzie rowne id posta do ktorego przynalezy //wtedy bedzie mialao takasama wartosc
			
			/* wykreowanie nastepnej wartosci id - zwiekszenie id o jeden a nstepnie operacja tej wartosci przez reszte z dzielenia modula liczby wszytskich id atatachments */
				$next_id = $attachment_ids[ ( $idx + 1 ) % count( $attachment_ids ) ];
				break;//zapogienie wykonanaia kolejnych instrukcji
			}
		}

		// get the URL of the next image attachment..
		/*GET_ATTACHMENT_LINK- Returns the URI of the page for an attachment. 
           param:$id (integer) (optional) The numeric ID of the attachment.
         Default: The current post ID, when used in The Loop. 
          */
		  
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			/* reset()php- spowowduje powrot do pierwszego eleemnetu w tablicy */
			$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
	}
/* printf- formatowanie zmiennych-param: lancuch ze zmiennymi
parametry 1-
- znak porcenta oznaczjacy zmienna
- cyfry oznaczjace kolejnosc
-  zanak oznaczjacu spsosb formatowania na typ zmiennych tutak s- czyli string

2- zglaszane zmienne i sposb ich [prezentacji tutja sa obslugiwane i kreowane przez funckje*/

/*the_title_attribute-- Displays or returns the title of the current post. It somewhat duplicates the functionality of the_title(), but provides a “clean” version of the title for use in HTML attributes by stripping HTML tags with strip_tags() and by converting certain characters (including quotes) to their character entity equivalent with esc_attr(); it also uses query-string style parameters. This tag must be within The Loop. */

/*wp_get_attachment_image-- Get an HTML img element representing an image attachment
Parameters #Parameters

$attachment_id
(int) (Required) Image attachment ID.
$size
(string|array) (Optional) Image size. Accepts any valid image size, or an array of width and height values in pixels (in that order).
Default value: 'thumbnail'
$icon
(bool) (Optional) Whether the image should be treated as an icon.
Default value: false
$attr
(string|array) (Optional) Attributes for the image markup.
Default value: ''
*/



/* KREOWANIE LINKU KTORY PROWADZI PO KLIKNIECIU DO POJEDYNCZEGO OBRAZKA nie jest to link nawigacji */

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),//url oczysczany
		the_title_attribute( array( 'echo' => false ) ),//pritowanie tytuly posta ustawione na false
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}

//koniec obslugi attached image

// advanced search functionality
 function advanced_search_query( $query ) {

	if($query->is_search()) {
		
		// tag search
		if (isset($_GET['taglist']) && is_array( $_GET['taglist']) ) {
			$query->set('tag_slug__and', $_GET['taglist']);
		}
	
		return $query;
	}

}

add_action( 'pre_get_posts', 'advanced_search_query', 1000 ); 

//dodawanie obslugi formatow postow
function my_templ_add_post_format(){
/* add_theme_support( 'html5',
	array( 
	        'comment-list',
			'comment-form',
			'search-form', 
			'caption'
			) ); */

add_theme_support( 'post-formats', array( 'quote' ) );
}
add_action('after_setup_theme','my_templ_add_post_format');

/**
 * This outputs the javascript needed to automate the live settings preview.
 * Also keep in mind that this function isn't necessary unless your settings 
 * are using 'transport'=>'postMessage' instead of the default 'transport'
 * => 'refresh'
 * 
 * Used by hook: 'customize_preview_init'
 */
 
 function my_customize_register( $wp_customize ) {
	 
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	//$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';zwroc uwage na linie 221
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
	
	$wp_customize->add_section( 'mytheme_new_section_name' , array(
    'title'      => 'Ustawienia kolorów nagłówka i opisu',
    'priority'   => 5,
) );
	//tytul nagłowka
	$wp_customize->add_setting( 'header_textcolor' , array(
    'default'     => '#eeee22',
    'transport'   => 'postMessage',
) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_textcolor', array(
	'label'      => 'Main Site Title Color' ,
	'section'    => 'mytheme_new_section_name',
	'settings'   => 'header_textcolor',
) ) );

//tytuł opisu
$wp_customize->add_setting( 'header_color' , array(
    'default'     => '#000000',
    'transport'   => 'postMessage',
) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_color', array(
	'label'      => 'Description Site Title Color' ,
	'section'    => 'mytheme_new_section_name',
	'settings'   => 'header_color',
) ) );


///nowa sekcja bgcolor

$wp_customize->add_section( 'mytheme_second_section_name' , array(
    'title'      => 'bgcolor',
    'priority'   => 30,
) );

$wp_customize->add_setting(
    // $id
    'background_color',
    // $args
    array(
        'default'           => '#000000',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage'
    )
);


$wp_customize->add_control(
    new WP_Customize_Color_Control(
        // $wp_customize object
        $wp_customize,
        // $id
        'background_color',
        // $args
        array(
            'settings'      => 'background_color',
            'section'       => 'mytheme_second_section_name',
            'label'         => 'Background Color',
            'description'   => 'Select the background color for header.',
            'type'          => 'color',

        )
    )
);	 

}
	
add_action( 'customize_register', 'my_customize_register' );	

  function my_customize_preview_js() {
	wp_enqueue_script( 'my-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '', true );
}
/*customize_preview_init-dodaje do akcji live rpeviev fukcjonlanosc  */
 add_action( 'customize_preview_init', 'my_customize_preview_js' );  
 
function mytheme_customize_css()
{
    ?>
         <style type="text/css">
             #site-title a { color: <?php echo '#'.get_theme_mod('header_textcolor'); ?>; }
			 #site-description { color: <?php echo get_theme_mod('header_color'); ?>; }
			body{ background-color:<?php echo '#'.get_theme_mod('background_color'); ?>;}
			
         </style>
    <?php
}
add_action( 'wp_head', 'mytheme_customize_css'); 

//////////////////////////HEADER

// Add support for custom headers.
	$custom_header_support = array(
		// The default header text color.
		'default-text-color' => '000',
		// The height and width of our custom header.
		'width' => apply_filters( '_image_width', 1000 ),
		'height' => apply_filters( '_header_image_height', 288 ),
		// Support flexible heights.
		'flex-height' => true,
		// Random image rotation by default.
		'random-default' => true,
		// Callback for styling the header.
		'wp-head-callback' => '_header_style',
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => '_admin_header_style',
		// Callback used to display the header preview in the admin.
		'admin-preview-callback' => '_admin_header_image',
	);
/* dodanie wsparcia dla customowego heraqdera dla motywu wraz z pzesylaYNYMI OPCJAMI-EWARTOSCIAMI */
	add_theme_support( 'custom-header', $custom_header_support );
/* wsparcie dla instlalacji wp wstecz przed wp 3.4 */
	if ( ! function_exists( 'get_custom_header' ) ) {
		// This is all for compatibility with versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR', $custom_header_support['default-text-color'] );
		define( 'HEADER_IMAGE', '' );
		define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
		add_custom_image_header( $custom_header_support['wp-head-callback'], $custom_header_support['admin-head-callback'], $custom_header_support['admin-preview-callback'] );
		add_custom_background();//dodaje customowy background
	}

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	//ostatni param:$crop:-
/* (boolean or array) (optional) Crop the image or not. False - Soft proportional crop mode ; True - Hard crop mode. Alternately an array representing the crop position. e.g. array( 'left', 'top')
Default: false znaczenie:njprwfd: proportional cropping: jako przycinanaie zbyt duzych obrazkow na zasadzie prorporcji i jest to soft cropping, hard cropping jako podawanie na sztywno regul okr. miejsc na croppin g*/

	set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

	// Add Twenty Eleven's custom image sizes.
	// Used for large feature (header) images.
	add_image_size( 'large-feature', $custom_header_support['width'], $custom_header_support['height'], true );
	// Used for featured posts if a large-feature doesn't exist.
	add_image_size( 'small-feature', 500, 300 );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	//regisrter default headeers jest f., wbudowana w wp
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/wheel.jpg',
			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
			/* translators: header image description */
			'description' =>  'Wheel'
		),
		'shore' => array(
			'url' => '%s/images/headers/shore.jpg',
			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
			/* translators: header image description */
			'description' =>  'Shore'
		),
		'trolley' => array(
			'url' => '%s/images/headers/trolley.jpg',
			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
			/* translators: header image description */
			'description' => 'Trolley'
		),
		'pine-cone' => array(
			'url' => '%s/images/headers/pine-cone.jpg',
			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
			/* translators: header image description */
			'description' => 'Pine Cone'
		),
		'chessboard' => array(
			'url' => '%s/images/headers/chessboard.jpg',
			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
			/* translators: header image description */
			'description' => 'Chessboard'
		),
		'lanterns' => array(
			'url' => '%s/images/headers/lanterns.jpg',
			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
			/* translators: header image description */
			'description' => 'Lanterns'
		),
		'willow' => array(
			'url' => '%s/images/headers/willow.jpg',
			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
			/* translators: header image description */
			'description' => 'Willow'
		),
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			/* translators: header image description */
			'description' => 'Hanoi Plant'
		)
	) );

//endif; // twentyeleven_setup

if ( ! function_exists( '_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Twenty Eleven 1.0
 */
 /* kolory textu nahglowkow sa okrlsane poprze wp customizer */
function _header_style() {
	$text_color = get_header_textcolor();//pobiera color textu naglowka , dodkonanaego przez wybur usera w wp //customizer

	// If no custom options for text are set, let's bail.
	if ( $text_color == HEADER_TEXTCOLOR )//headr textciolor przyjmuje wartosc domyslna  a zatem nie wybranoi  //zadnej opcjonalnej wartosci (user nie dokonal wybioru koloru czcionki textu nagłowka
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo $text_color; ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // twentyeleven_header_style

if ( ! function_exists( '_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
 
 /* stylizacja naglowkow domyslsnych njprwd po stronie front -endu */
function _admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // twentyeleven_admin_header_style

if ( ! function_exists( '_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
 //stylizacja obrazkow njprwd pos rtronie back endu w naglowku
function _admin_header_image() { ?>
	<div id="headimg">
		<?php
		$color = get_header_textcolor();//pobranie kloru textu w naglowku
		$image = get_header_image();//pobranie obrazkow naglowgka obydwie f., wbudowane w wp
		if ( $color && $color != 'blank' )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // twentyeleven_admin_header_image