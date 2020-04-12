<?php 
/**
 * The template for displaying header
 *
 * Displays all of the <head>  and <header> section
 *
 * @package LaRestaurante
 */
?>

<!DOCTYPE html>
	
	<!--[if gt IE 9]>
	<html <?php language_attributes(); ?>> <![endif]-->
	<!--[if !IE]><!-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
	
</head>
  <body data-spy="scroll" data-target=".navbar" data-offset="50" <?php body_class();?>>
	<?php  if( has_header_image() && is_front_page()):?>
    <header  id="home" class="home" style="background-image: url('<?php header_image();?>');"  role="banner">
	 
        <div class="container-fluid text-vcenter" >
          <div class="row align-items-start">
            <div class="col-md-12" >
				<nav class="navbar navbar-expand-md fixed-top" data-spy="affix" data-offset-top="400" 
					 role="navigation">
				
					<div class="container-fluid">
						<div class="navbar-header">
							<button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
							data-target="#mainNav" aria-controls="navbarCollapse" aria-expanded="false" 
							aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
							</button>
						 </div>
						<?php if ( has_nav_menu( 'primary' ) && is_front_page () ):?>
						<div id="mainNav" class="navbar-collapse collapse" >
							<?php
								wp_nav_menu(array(
										'theme_location'  => 'primary',
										'container'       => 'div',
										'container_id'    => '',
										'container_class' => '',
										'menu_id'         => false,
										'menu_class'      => 'navbar-nav mr-auto',
										'depth'           => 3,
										'walker'          => new larestaurante_navwalker()
										));
							?>
						</div>
				    </div>
					<?php elseif (is_user_logged_in()&& !is_single() && !is_archive()):?>
					
					<div class="container-fluid">
						<h3>
							<?php printf( wp_kses( __( 'Theres no menu, <a href="%1$s" target="%2$s">go to 		admin 	panel and create and activate menu</a>.', 'larestaurante' ), 
							array( 'a' => array( 'href' => array(), 'target' => array() )) ),
									 admin_url('nav-menus.php') , '_blank' );  ?>
						</h3>
					</div>	
					<!-- end has menu primary-->
					<?php endif;?>		
				</nav> 
				
 <!-- main home content -->
   <div class="site-branding">
	<div class="wrap">
		<div class="site-branding-text">
			<?php if ( is_front_page() && !has_custom_logo (get_current_blog_id())) : ?>
			<h1  class="site-title respo"><a style="font-size:2.3em;" class="site-title"  href="<?php echo esc_url( home_url( '/' ) ); ?>" 
				rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : the_custom_logo (); 
				  endif;

			$description = get_bloginfo( 'description', 'display' );

			if ( $description || is_customize_preview() ) :?>
			

					<div class="col-md-12 mt-1">
						<a style="font-size:1.7em;" href="<?php the_permalink();?>"class="btn btn-secondary btn-lg">
						<?php _e( $post_type.' of the day','larestaurante');?>
						<i class="fa fa-commenting"></i></a>
	 

					<?php else:?>
						<a style="font-size:1.7em;" onclick="alert('Sorry no matched criteria or you have no posts')" 
							href="" 
							class="btn btn-secondary btn-lg">
						<?php _e('Post of the day','larestaurante');?><i class="fa fa-commenting"></i></a>
					<!-- end if menu posts-->
					<?php endif; ?>
					<?php endif; ?>
					<?php if ( class_exists( 'woocommerce' ) ) {
						//https://www.skyverge.com/blog/get-woocommerce-page-urls/
						echo '<a style="font-size:1.7em;" class="btn btn-secondary btn-lg" 
							href="'.esc_url(wc_get_cart_url()).'" >
							Go to your Cart<i class="fa fa-shopping-cart"></i></a>
							<a style="font-size:1.7em;" class="btn btn-secondary btn-lg" 
							href="'.esc_url(get_permalink(wc_get_page_id( 'shop' ) )).'" >Go to the Shop
							<i class="fa fa-shopping-bag"></i></a>';
							}?>
							
							<?php if(is_plugin_active( 'restaurant-reservations/restaurant-reservations.php')) :?>
							<a id="bookit" style="font-size:1.7em;" class="btn btn-secondary btn-lg">
								<?php _e('Book a table','larestaurante');?><i class="fa fa-book"></i></a>
								<div id="book"><?php echo do_shortcode( '[booking-form]' ); ?></div>
		
							
							<?php endif; ?>
					
						<p class="site-description respo"><?php echo $description; ?></p>
						<a href="#content" class="page-scroller"><i class="fa fa-fw fa-angle-down"></i></a>	
					
					</div><!-- .col-md-12 .mt-1 -->
		</div><!-- .site-branding-text-->
		</div><!--wrap .-->
		</div><!--.site-branding-->
	   </div><!--.col-md-12 -->
	  </div><!--.row align-items-start--> 
	</div><!--container-fluid text-vcenter--> 
   </header>
   <?php if( !has_header_image() && is_front_page()):?><!--if( has_header_image() && is_front_page())-->
   <?php get_template_part('header-nomenu');?> 
   <?php endif;?>
   
   <?php if(is_plugin_active( 'woocommerce/woocommerce.php') && !is_front_page() ):?>
   <?php get_template_part('woo-head');?>
  
   <?php endif;?>  