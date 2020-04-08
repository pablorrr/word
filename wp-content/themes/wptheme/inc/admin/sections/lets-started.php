<?php
/**
 * Welcome screen getting started template
 */


// get theme customizer url

$url 	= admin_url() . 'customize.php?';
$url 	.= '&return=' . urlencode( admin_url() . 'themes.php?page=larestaurante-welcome' );
$url 	.= '&larestaurante-customizer=true';
?>
<div id="lets_started" class="col two-col panel" style="margin-bottom: 1.618em; padding-top: 1.618em; overflow: hidden;">

	<h2><?php echo sprintf( esc_html__( 'Welcome to the %sLaRestaurante%s Theme , enjoy it!!!', 'larestaurante' ), '<strong>', '</strong>'); ?></h2>
	<p class="tagline"><?php _e( 'The theme contains many ways to configure.', 'larestaurante' ); ?></p>

	<div class="col-1">
		<!-- HOMEPAGE -->
		<div class="section homepage">
			<!-- CUSTOMIZER -->
		<h4><?php _e( 'Customize this theme with Wordpress Customizer &nbsp <span class="dashicons dashicons-admin-generic"></span>' ,'larestaurante' ); ?></h4>
		<p><?php _e( 'Use wordpress customizer to change the background color, background image, stylize headline and footer.', 'larestaurante' ); ?></p>
		<p><a href="<?php echo esc_url( $url ); ?>" target="_blank"class="button"><?php _e( 'Open the Customizer', 'larestaurante' ); ?></a></p>
		
		
		<!-- CODESTAR -->
		<h4><?php _e( 'Customize this theme with Codestar &nbsp <span class="dashicons dashicons-admin-generic"></span>' ,'larestaurante' ); ?></h4>
		<p><?php _e( 'Codestar is a framework for building Wordpress options. Easy and user-friendly option boards will make this theme fully adapted to your needs.', 'larestaurante' ); ?></p>
		
		<p><?php printf( wp_kses( __( ' <a href="%1$s" target="%2$s" class="%3$s">Open the Codestar</a>','larestaurante' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() )) ),
		esc_url(admin_url('themes.php?page=lares') ) , '_blank', 'button' );?></p>
		</div>		

		<!-- ADD RESERVATION -->
		<h4><?php _e( 'Manage reservations in your restaurant &nbsp <span class="dashicons dashicons-calendar-alt"></span>' ,'larestaurante' ); ?></h4>
		<p><?php _e( 'Add, edit, delete, save, reservations in your restaurant', 'larestaurante' ); ?></p>
		
		<p><?php printf( wp_kses( __( ' <a href="%1$s" target="%2$s" class="%3$s">Open the Reservations</a>','larestaurante' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() )) ),
		 esc_url(admin_url('themes.php?page=person_form') ) , '_blank', 'button' );?></p>
		 
		 <!-- AJAX lOAD POSTS BUTTON WITH JETPACK -->
		<h4><?php _e( 'Load your posts with the Ajax through jetpack &nbsp <span class="dashicons dashicons-format-aside"></span>' ,'larestaurante' ); ?></h4>
		<p><?php _e( 'To load posts using the Ajax button, download and activate the Jetpack plugin and then configure the plugin settings. Go to the Reading Settings and set the number of posts loaded on the page. Make sure that the Jetpack plugin option is selected in this section.Of course, you should first drag all segments to the container to the right in the Theme Options.', 'larestaurante' ); ?></p>
		<p><?php printf( wp_kses( __( ' <a href="%1$s" target="%2$s" class="%3$s">Open Reading Settings</a>','larestaurante' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() )) ),
		esc_url(self_admin_url('options-reading.php') ) , '_blank', 'button' );?></p>
		
		 <!-- AJAX lOAD ARCHIVE POSTS -->
		<h4><?php _e( 'Load your CPT in archive template with the Ajax  &nbsp <span class="dashicons dashicons-format-aside"></span>' ,'larestaurante' ); ?></h4>
		<p><?php echo sprintf( esc_html__ ('To load customized post types via Ajax when creating a new post type, %sset up slugs the same as the post name%s manually or with a plugin to create CPT', 'larestaurante' ), '<strong>', '</strong>'); ?></p>
		<p><a class ="button" href="https://codex.wordpress.org/Post_Types" target="_blank">Wordpress Custom Post Type</a></p>
		 
	</div><!--.col-1-->

	<div class="col-2 last-feature">
		<!-- MENUS -->
		<h4><?php _e( 'Configure menu location &nbsp<span class="dashicons dashicons-menu"></span>' ,'larestaurante' ); ?></h4>
		<p><?php _e( 'Configure your menu. The theme supports scrolling from the menu to selected segments on the front page. To do this, go to the Menu in the Administration Panel, choose your own links and paste - (http: // yourdomainname / #services) and add to the Menu. Other names of segments to be used are: restaurants, booking, slideshow, contact, menu, opinion, team, about_us, gallery','larestaurante' ); ?></p>
		<p><a href="<?php echo esc_url( self_admin_url( 'nav-menus.php' ) ); ?>" target="_blank" class="button"><?php _e( 'Configure menus', 'larestaurante' ); ?></a></p>
		
		<!-- SMTP CONFIGURE -->
		<h4><?php _e( 'Set up your SMTP configuration &nbsp <span class="dashicons dashicons-admin-network"></span></span>' ,'larestaurante' ); ?></h4>
		<p><?php _e( 'Simple Mail Transfer Protocol (SMTP) is an Internet standard for electronic mail (email) transmission. First defined by RFC 821 in 1982, it was updated in 2008 with Extended SMTP additions by RFC 5321, which is the protocol in widespread use today.</blockquote> Source:<a href="https://en.wikipedia.org/wiki/Simple_Mail_Transfer_Protocol" target="_blank"> Wikipedia</a>', 'larestaurante' ); ?></p>
		<p><?php printf( wp_kses( __( ' <a href="%1$s" target="%2$s" class="%3$s">Open the WP SMTP</a>','larestaurante' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() )) ),
		esc_url(self_admin_url('themes.php?page=contact-form') ) , '_blank', 'button' );?></p>
						 
						 
		<!-- SET UP CUSTOM PERMALINKS -->	
		<h4><?php _e( 'Set up your permalinks &nbsp <span class="dashicons dashicons-admin-site"></span>' ,'larestaurante' ); ?></h4>
		
		<p><?php echo sprintf( esc_html__( 'Create %sCustom Links%s to this theme. Customized links simplify the readability of links and are more friendly seo.', 'larestaurante'), '<strong>', '</strong>' ); ?>
		<a href="https://codex.wordpress.org/Using_Permalinks"> Visit Wordpress Codex</a> to see custom permalinks examples. Recommended custom permalink: /%category%/%postname%/</p>
		<p><?php printf( wp_kses( __( ' <a href="%1$s" target="%2$s" class="%3$s">Open Permalink Settings</a>','larestaurante' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() )) ),
		esc_url(self_admin_url('options-permalink.php') ) , '_blank', 'button' );?></p>
		
		<!-- WOOCOMMERCE  -->	
		<h4><?php _e( 'Sell products through Woocommerce plugin &nbsp <span class="dashicons dashicons-cart"></span>' ,'larestaurante' ); ?></h4>
		
		<p><?php _e( 'To sell products, download and install the Woocommerce plugin. It is necessary to pre-configure the plugin; create products, choose a template for a store, cart etc.','larestaurante' ); ?>
		</p>
		<p><a class ="button" href="https://docs.woocommerce.com/document/start-with-woocommerce-in-5-steps/" target="_blank">Woocomerce link</a></p>
		
	</div><!--.col-2 .last-feature-->
</div><!--#lets_started-->