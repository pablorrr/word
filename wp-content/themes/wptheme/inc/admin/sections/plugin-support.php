<?php
/**
 * Welcome screen add-ons template
 */
?>
<div id="add_ons" class="wptheme-add-ons panel" style="padding-top: 1.618em; clear: both;">
	<h2><?php echo  esc_html__( 'Install recommended plugins','wptheme'); ?></h2>

	<p class="tagline">
		<?php echo sprintf( esc_html__( 'Add to the subject plugins supporting take care of seo, security and 	inform users that you are using cookie in %swptheme%s', 'wptheme' ), '<strong>', '</strong>'); ?>
	</p>

	<div class="add-on">
	
		<div class="content">
			<!-- Plugins -->
		<div class="section plugins">
			<h4><?php _e( 'Install recommended plugins <span class="dashicons dashicons-admin-plugins"></span>' ,'wptheme' ); ?></h4>
			<p style="margin-bottom:10px;"><?php echo sprintf( esc_html__( '%sIncrease the functionality%s of your theme by applying extensions in the form of plugins. take care of seo, security on the network, cookie privacy policy and commercial selling- %sWoocommerce%s', 'wptheme' ), '<strong>', '</strong>', '<a target="_blank" href="' . esc_url('https://wordpress.org/plugins/woocommerce/') . '"><strong>', '</strong></a>'); ?></p>
			

			<p><a href="<?php echo esc_url( self_admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button button-primary"><?php _e( 'Install &amp; Activate Recommended Plugins', 'wptheme' ); ?></a></p>
		</div>
		</div>
	</div>

	<hr style="clear: both;" />
</div>