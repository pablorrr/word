( function( $ ){
	
	//przyspiesz printowanie nazwy strony
	//to- parametr ten jest trrewsscia przekazwywane go dynamicznie htmla i jego cobntentu w postaci tesci //textowej htmla
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '#site-title a' ).html( to );
		} );
	} );
	
	//przypsiesz podglad zmin oppisu  bloga
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '#site-description' ).html( to );
		} );
	} );
	
	//Update site title color in real time...
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( newval ) {
			$('#site-title a').css('color', newval );
		} );
	} );
	//opis  color in real time
	wp.customize( 'header_color', function( value ) {
		value.bind( function( newval ) {
			$('#site-description').css('color', newval );
		} );
	} );

	//Update site background color...
	wp.customize( 'background_color', function( value ) {
		value.bind( function( newval ) {
			$('body').css('background-color', newval );
		} );
	} );
	
	
	
} )( jQuery );