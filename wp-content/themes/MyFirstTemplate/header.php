<!DOCTYPE html>
<html <?php language_attributes(); ?>>
 <head>
     <meta charset="<?php bloginfo('charset'); //utf 8?>">
     <meta name="Description" content="<?php _content_tag();?><?php _author_name(); ?>" >
     <title><?php wp_title();?><?php bloginfo( 'name' ); ?></title>
  
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/mystyle.css"
	 <!-- favicon  -->
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
	
	
  <?php wp_head(); ?>
 </head>
 
 <body <?php body_class();?>>
  <div id="page" class="hfeed">
  <header id="header" >
  
			<hgroup>
				<h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
 
			<?php  //////////header image/////////////
  // Check to see if the header image has been removedexit
                     $header_image = get_header_image();
				if ( $header_image ) :
					// Compatibility with versions of WordPress prior to 3.4.
					if ( function_exists( 'get_custom_header' ) ) {
						// We need to figure out what the minimum width should be for our featured image.
						// This result would be the suggested width if the theme were to implement flexible widths.
						/* get_theme_support- daje dostep do tzw features wp tutasj jest to szerokosc customowego headra */
						$header_image_width = get_theme_support( 'custom-header', 'width' );
					} else {//njprwd gdy wp jest w wersji ponizej 3.4
						$header_image_width = HEADER_IMAGE_WIDTH;
					}
					?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
					// The header image
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					
					/* wp_get_attachment_image_src-The returned array contains four values: the URL of the attachment image src, the width of the image file, the height of the image file, and a boolean representing whether the returned array describes an intermediate (generated) image size or the original, full-sized upload.
					get_post_thumbnail_id- zwraca id miniaturki posta
					*/
					
					
					if ( is_singular() && has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( $header_image_width, $header_image_width ) ) ) &&
							$image[1] >= $header_image_width ) :
						// Houston, we have a new header image!, zostal dodany nowy obrazek headera spoza //wbudowanego zestawu obrazków w  folderze, a domyslny zostal ususniety
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );//printuje nowy obrazek spoza //folderu obrazków
					else :
						// Compatibility with versions of WordPress prior to 3.4.
						if ( function_exists( 'get_custom_header' ) ) {
							$header_image_width  = get_custom_header()->width;
							$header_image_height = get_custom_header()->height;
						} else {//ponizej wersji 3.4
							$header_image_width  = HEADER_IMAGE_WIDTH;
							$header_image_height = HEADER_IMAGE_HEIGHT;
						}
						?>
					<img src="<?php header_image();//wyprintuje url image header ale wbudowane z folderu ?>" width="<?php echo $header_image_width; ?>" height="<?php echo $header_image_height; ?>" alt="" />
				<?php endif; // end check for featured image or standard header, koniec podrzednego if ?>
			</a>
			<?php endif; // end check for removed header image, koniec nadrzednego if ?>

			<?php
				// Has the text been hidden?,,jesli nie zistalokreslony kolor textu to znacz ze nie ma textu
				if ( 'blank' == get_header_textcolor() ) :
				//ponizej klasa with image wyswietli sie wtedy gdy istnieje obrazek nagłowka
			?>
				<div class="only-search<?php if ( $header_image ) : ?> with-image<?php endif; ?>">
				<?php get_search_form();//zalaczenie formularza search wbud w wp ?>
				</div>
			<?php
				else :
			?>
				<?php get_search_form(); ?>
			<?php endif; ?>
  
  
  
  
  
 <div id="navbar">
        
   <div id="mainnav">
  
  <?php wp_nav_menu( array( 'theme_location' => 'header-menu')); ?>
  
   </div>
  </div>
</header>