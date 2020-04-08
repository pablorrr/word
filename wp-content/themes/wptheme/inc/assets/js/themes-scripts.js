/*
 * Jquery and JS support set for widgets,
 * with url swapping and scrolling and displaying the front page in this theme.
 */

jQuery( function ( $ ) {
    'use strict';
    // here for each comment reply link of wordpress
    $( '.comment-reply-link' ).addClass( 'btn btn-primary' );

    // here for the submit button of the comment reply form
    $( '#commentsubmit' ).addClass( 'btn btn-primary' );

    // The WordPress Default Widgets
    // Now we'll add some classes for the wordpress default widgets - let's go

    // the search widget
    $( '.widget_search input.search-field' ).addClass( 'form-control' );
    $( '.widget_search input.search-submit' ).addClass( 'btn btn-default' );
    $( '.variations_form .variations .value > select' ).addClass( 'form-control' );
    $( '.widget_rss ul' ).addClass( 'media-list' );

    $( '.widget_meta ul, .widget_recent_entries ul, .widget_archive ul, .widget_categories ul, .widget_nav_menu ul, .widget_pages ul, .widget_product_categories ul' ).addClass( 'nav flex-column' );
    $( '.widget_meta ul li, .widget_recent_entries ul li, .widget_archive ul li, .widget_categories ul li, .widget_nav_menu ul li, .widget_pages ul li, .widget_product_categories ul li' ).addClass( 'nav-item' );
    $( '.widget_meta ul li a, .widget_recent_entries ul li a, .widget_archive ul li a, .widget_categories ul li a, .widget_nav_menu ul li a, .widget_pages ul li a, .widget_product_categories ul li a' ).addClass( 'nav-link' );

    $( '.widget_recent_comments ul#recentcomments' ).css( 'list-style', 'none').css( 'padding-left', '0' );
    $( '.widget_recent_comments ul#recentcomments li' ).css( 'padding', '5px 15px');

    $( 'table#wp-calendar' ).addClass( 'table table-striped');

    // Adding Class to contact form 7 form
    $('.wpcf7-form-control').not(".wpcf7-submit, .wpcf7-acceptance, .wpcf7-file, .wpcf7-radio").addClass('form-control');
    $('.wpcf7-submit').addClass('btn btn-primary');

    // Adding Class to Woocommerce form
    $('.woocommerce-Input--text, .woocommerce-Input--email, .woocommerce-Input--password').addClass('form-control');
    $('.woocommerce-Button.button').addClass('btn btn-primary mt-2').removeClass('button');

    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).parent().siblings().removeClass('open');
        $(this).parent().toggleClass('open');
    });

    // Add Option to add Fullwidth Section
    function fullWidthSection(){
        var screenWidth = $(window).width();
        if ($('.entry-content').length) {
            var leftoffset = $('.entry-content').offset().left;
        }else{
            var leftoffset = 0;
        }
        $('.full-bleed-section').css({
            'position': 'relative',
            'left': '-'+leftoffset+'px',
            'box-sizing': 'border-box',
            'width': screenWidth,
        });
    }
    fullWidthSection();
    $( window ).resize(function() {
        fullWidthSection();
    });

    // Allow smooth scroll
    $('.page-scroller').on('click', function (e) {
        e.preventDefault();
        var target = this.hash;
        var $target = $(target);
        $('html, body').animate({
            'scrollTop': $target.offset().top
        }, 1000, 'swing');
    });
	
	//slide toggle booking 
	    $("#book").hide();   
		$("#bookit").click(function(e){
			e.preventDefault();
        $("#book").slideToggle("slow","swing");
	}); 
	
	
	
/* begin scroll url script */

var servicesPosition = $('.services').position();
var servicesHeight =  $('.services').height();

var teamPosition = $('.our-team').position();
var teamHeight =  $('.our-team').height();

var bookingPosition = $('.booking').position();
var bookingHeight = $('.booking').height();

var carouselPosition = $('.carousel').position();
var carouselHeight = $('.carousel').height();

var contactPosition = $('.contact').position();
var contactHeight = $('.contact').height();

var galleryPosition = $('.gallery').position();
var galleryHeight = $('.gallery').height();

var opinionPosition = $('.opinion').position();
var opinionHeight = $('.opinion').height();

var aboutusPosition = $('.about-us').position();
var aboutusHeight = $('.about-us').height(); 

var restaurantsPosition = $('.restaurants').position();
var restaurantsHeight = $('.restaurants').height();

var menuPosition = $('.menu').position();
var menuHeight = $('.menu').height();

var winHeight = $(window).height();

$(window).scroll( function(){
		
		var scroll = $(window).scrollTop();
		console.log('actual scrolling value ' + scroll);
		
		function scroll_url( position, height , hash_url ){
	
			if( scroll >= (position.top.toFixed(0) -(0.25 * winHeight)) 
			&& scroll < (parseInt( position.top.toFixed(0)) + parseInt(height)) ){
			history.replaceState( null, null, hash_url);
			return false;
			} 
		}
		scroll_url( contactPosition, contactHeight , '#contact' );
		scroll_url( teamPosition, teamHeight , '#team' );
		scroll_url( opinionPosition,  opinionHeight , '#opinion' );
		scroll_url( galleryPosition,  galleryHeight , '#gallery' );
		scroll_url( menuPosition,   menuHeight , '#menu' );
		scroll_url( aboutusPosition, aboutusHeight , '#about_us' ); 
		scroll_url( carouselPosition, carouselHeight , '#carousel' );
		scroll_url( restaurantsPosition, restaurantsHeight , '#restaurants' );
		scroll_url( bookingPosition,  bookingHeight ,'#booking' );
		scroll_url( servicesPosition, servicesHeight , '#services' );
		
		});
	
/* end scroll script */
	
	//preventing send form content when page is refreshed
	 if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
	//ajax archive pagination
	 jQuery(document).on('click','#pagi a', function(e){
			e.preventDefault();
			var linkhref = jQuery(this).attr('href');
			var link = jQuery(this).attr('href') + ' #contentInner';
		  //alert(link);
		   jQuery('#contentInner').html('loading...').load(link);
			//window.scrollTo(0,0);
			
			window.history.pushState({ page: linkhref },linkhref,'/'+linkhref);
			//return false;
			
		  
  });
  
	/* hover menu */
	$('div#menu ul.navbar-nav').hover(function() {
	  $(this).find('ul.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
	}, function() {
	  $(this).find('ul.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
	});
});