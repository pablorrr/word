<!--Tabs handle-->
<h2 class="nav-tab-wrapper">
	<a href="#lets_started" class="nav-tab nav-tab-active"><?php _e( 'Lets Begin!', 'larestaurante' ); ?><span class="dashicons dashicons-admin-home"></span></a>
	<a href="#add_ons" class="nav-tab"> <?php _e( 'Supported Plugins', 'larestaurante' ); ?> 
	<span class="dashicons dashicons-admin-plugins"></span></a>
	<a href="#add_on" class="nav-tab"><?php _e( 'Documentation', 'larestaurante' ); ?> <span class="dashicons dashicons-welcome-learn-more"></span></a>
	
</h2>

<script>
jQuery( document ).ready( function() {
	jQuery( 'div.panel' ).hide();
	jQuery( 'div#lets_started' ).show();

	jQuery( '.nav-tab-wrapper a' ).click( function() {

		var tab = jQuery( this );
		var	tabs_wrapper = tab.closest( '.about-wrap' );

		jQuery( '.nav-tab-wrapper a', tabs_wrapper ).removeClass( 'nav-tab-active' );
		jQuery( 'div.panel', tabs_wrapper ).hide();
		jQuery( 'div' + tab.attr( 'href' ), tabs_wrapper ).show();
		tab.addClass( 'nav-tab-active' );

		return false;
	});
});
</script>