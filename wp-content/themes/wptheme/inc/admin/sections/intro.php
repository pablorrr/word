<?php
/**
 * Welcome screen intro template
 */
?>

<div class="col two-col" style="margin-bottom: 1.618em; overflow: hidden;">
	<div class="col">
		<h1 style="margin-right: 0;"><?php echo '<strong>LaRestaurante</strong>';?></h1>
		<?php $formatted_string = wpautop('				In this theme you can:

														- turn on and off the segments you selected, the front end of the theme
														- choose pictures, for example for a gallery
														- set the font
														- send emails and configure smtp mail
														- save table reservations for clients
														- set up links for social media
														- edit css

														and set some additional options.
														
													The theme has ajax support at archive pagination,
													customized custom posts and post formats.
													
													<strong>Before the first use of the Theme please enter the Theme Options and save all the default options.</strong>');?>


		<p style="font-size: 1.2em;"><?php _e( $formatted_string, 'larestaurante' ); ?></p>
		
	</div>

	<div class="col last-feature">
		<img src="<?php echo esc_url( get_template_directory_uri() ) . '/screenshot.png'; ?>" alt="LaRestaurante" class="image-50" width="440" />
	</div>
</div>