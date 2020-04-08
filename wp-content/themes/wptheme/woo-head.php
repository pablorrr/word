<?php
/**
 * The template for displaying header for woocommerce pages, there's no menu
 *
 * @link https://wordpress.org/plugins/woocommerce/
 *
 * @package larestaurante
 * 
 */
get_header(); ?>
<header>
		<div class="container-fluid">
			 <div class="row align-items-start">
				<div  class="col-md-12" >
					<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark arch-nav">
						<h1 class="site-title text-center respo" >
						<a class="site-title"  href="<?php echo esc_url( home_url( '/' ) ); ?>" 
							rel="home"><?php bloginfo( 'name' ); ?></a>
						</h1>
					</nav>
					<span id="content"></span>	
				</div>  
			</div>  
		</div>		 
</header>