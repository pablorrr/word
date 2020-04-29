<?php
/**
 * Welcome screen add-ons template
 */
$formatted_string = wpautop('wptheme is a:
													- responsive ( Bootsrap 4 framework ),
													- customizable ( Wp Customizer),
													- translatable,
													- supported e-shoping ( Woocommerce )
													
							You can also extend possibilities of this theme by applying recommended plugins.			
 
							The theme includes the following template files:
										archive.php- for archive pages
										index.php
										page.php - for static pages
										header.php
										sidebar.php
										footer.php
										woocommerce.php -for supporting woocommerce (dispalying woo pages)
										functions.php
										 
										The theme supports featured images, menus and widgets.
										 
										Menus:
										The default menu is in header.php, and uses the Menus admin
										 
										Widget Areas
										There are three widget areas, all added via the widgets.php file in the footer.
										
							This theme takes advantage of these generous tools:

									

									WP Bootstrap Starter
									https://afterimagedesigns.com/wp-bootstrap-starter/
									https://afterimagedesigns.com/

									
									
							Theme Screenshot image from Pixabay:
									https://www.pexels.com/photo-license/
									
							License:
									License: GNU General Public License v2.0
									License URI:http://www.gnu.org/licenses/gpl-2.0.html'); ?>

<div id="add_on" class="add-ons panel" style="padding-top: 1.618em; clear: both;">
    <h2><?php echo esc_html__('Read documentation', 'wptheme'); ?></h2>

    <p class="tagline">
        <?php echo $formatted_string; ?>
    </p>
    <hr style="clear: both;"/>
</div>