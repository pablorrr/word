<?php 
/**
 * The template for displaying header without menu for blog and woocommmerce pages
 *
 * @package larestaurante
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
  <body <?php body_class();?>>
  
    <header>
		<div class="container-fluid">
			 <div class="row align-items-start">
				<div class="col-md-12" >
					<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark arch-nav">
						<h1 class="site-title text-center" >
							<a class="site-title"  href="<?php echo esc_url( home_url( '/' ) ); ?>" 
								rel="home"><?php bloginfo( 'name' ); ?></a>
						</h1>
					</nav>
				</div>  
			</div>  
		</div>  				 
   </header>    