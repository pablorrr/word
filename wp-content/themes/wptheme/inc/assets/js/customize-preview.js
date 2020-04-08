/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	
	 // Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} ); 
	
	// header background image
	wp.customize( 'header_img', function( value ) {
		value.bind( function( to ) {
			$( '#home.home' ).attr( "style", "background-image:url("+ to +")" );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( 'p.site-branding-text, p.site-description').css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( 'p.site-branding-text, p.site-description').css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( 'p.site-branding-text a, p.site-description').css( {
					'color': to
				} );
			}
		} );
	} );
	
	

// link color
	
	wp.customize( 'link-color', function( value ) {
	value.bind( function( newval ) {
		//Do stuff (newval variable contains your "new" setting data)
		if ( 'blank' === to ) {
				$( 'a' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( 'a' ).css( {
					'color': to
				} );
				
			}
	} );
	} );
	//footer
   
	wp.customize( 'monday', function( value ) {
		value.bind( function( to ) {
			$( '#footer > div > div:nth-child(3) > div:nth-child(2) > ul > li:nth-child(1)' ).text( to );
			
		} );
	} );	
	
	
	
	wp.customize( 'tuesday', function( value ) {
		value.bind( function( to ) {
			
			$( '#footer > div > div:nth-child(3) > div:nth-child(2) > ul > li:nth-child(2)' ).text( to );
			
		} );
	} );	
	wp.customize( 'wednesday', function( value ) {
		value.bind( function( to ) {
		
			$( '#footer > div > div:nth-child(3) > div:nth-child(2) > ul > li:nth-child(3)' ).text( to );
			
		} );
	} );	
	wp.customize( 'thursday', function( value ) {
		value.bind( function( to ) {
			
			$( '#footer > div > div:nth-child(3) > div:nth-child(2) > ul > li:nth-child(4)' ).text( to );
			
		} );
	} );	
	wp.customize( 'friday', function( value ) {
		value.bind( function( to ) {
			
			$( '#footer > div > div:nth-child(3) > div:nth-child(2) > ul > li:nth-child(5)' ).text( to );
			
		} );
	} );	
	wp.customize( 'saturday', function( value ) {
		value.bind( function( to ) {
			
			$( '#footer > div > div:nth-child(3) > div:nth-child(2) > ul > li:nth-child(6)' ).text( to );
			
		} );
	} );	
	wp.customize( 'sunday', function( value ) {
		value.bind( function( to ) {
			
			$( '#footer > div > div:nth-child(3) > div:nth-child(2) > ul > li:nth-child(7)' ).text( to );
		} );
	} );	
	
} )( jQuery );
