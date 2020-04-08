<?php 
/* 
 * Version:1.2
 * Author:Naveenkumar C
 * License:GPL2
 * Copyright 2014-2017 Naveenkumar C (email: cnaveen777 at gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General
 * Public.License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any * later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the*implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR * PURPOSE.  See the GNU General Public License for *more details. 

 * You should have received a copy of the GNU General Public License along with this program; if not, write to the * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA.
 *
 * Support for pagination by jquery ajax, compatibility with the ajax-pagi.php file
 */ 

// Exit you access directly 
//if ( ! defined( 'WPINC' ) )  die; 
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_ajax_cptapagination', 'cptapagination_callback' );
add_action( 'wp_ajax_nopriv_cptapagination', 'cptapagination_callback' );					

function cptapagination_callback() {
	global $wpdb;
	$cptaNumber = absint($_POST['number']);
	$cptaLimit  = absint($_POST['limit']);
	$cptaType = sanitize_text_field($_POST['cptapost']);
	$cptaCatName = sanitize_text_field($_POST['cptacatname']);
	$cptataxname = sanitize_text_field($_POST['cptataxname']);
	if( $cptaNumber == "1" ){
		$cptaOffsetValue = "0";
		if( $cptataxname ) {
			$args = array(  'posts_per_page' => $cptaLimit,
							'post_type' => $cptaType,
							 $cptataxname=>$cptaCatName,
							'post_status' => 'publish');		
		}else{
			$args = array('posts_per_page' => $cptaLimit,
						  'post_type' => $cptaType,
						  'post_status' => 'publish');	
		}
	}else{
		$cptaOffsetValue = ($cptaNumber-1)*$cptaLimit;
		if( $cptataxname ) {
		$args = array(  'posts_per_page' => $cptaLimit,
						'post_type' => $cptaType,
						$cptataxname=>$cptaCatName,
						'offset' => $cptaOffsetValue,
						'post_status' => 'publish');
		}else{
		$args = array(  'posts_per_page' => $cptaLimit,
						'post_type' => $cptaType,
						'offset' => $cptaOffsetValue,
						'post_status' => 'publish');	
		}
		
	}
	$cptaQuery = new WP_Query( $args );
		if( $cptaQuery->have_posts() ){
			while( $cptaQuery->have_posts() ){ $cptaQuery->the_post();
				 echo "<div class ='cpta-Section bg-light-gray'>
				 <h1 class='arch-padding'>".get_the_title()."</h1>".get_the_post_thumbnail($post_id, 'large')."<p class='shadow p-4 mb-4 bg-white lead'>".get_the_excerpt()."</p><a href=".get_the_permalink()." class='btn-default fr-end-butt'>Read More</a>
				</div>";
			} wp_reset_postdata();
		}
		if($cptataxname!=""){
			$cpta_args = array( 'posts_per_page' => -1,
								'post_type' => $cptaType,
								$cptataxname=>$cptaCatName,
								'post_status' => 'publish');
		}else{
			$cpta_args = array( 'posts_per_page' => -1,
								'post_type' => $cptaType,
								'post_status' => 'publish');
		}
		$cpta_Query = new WP_Query( $cpta_args );
		$cpta_Count = count($cpta_Query->posts);
		$cpta_Paginationlist = ceil($cpta_Count/$cptaLimit);
		$last = ceil( $cpta_Paginationlist );
		if( $cptaNumber>1 ){ $cptaprev = $cptaNumber-1;	}
		if( $cptaNumber < $last ){ $cptanext = $cptaNumber+1; }
		
		$adjacents = "2"; 
		$setPagination = "";
		if( $cpta_Paginationlist > 0 ){
    
			$setPagination .="<ul class='pagination'>";
			$setPagination .="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='javascript:cptaajaxPagination($cptaprev,$cptaLimit)'>Prev</a></li>";

			if ( $cpta_Paginationlist < 7 + ($adjacents * 2) ){

				for( $cpta=1; $cpta<=$cpta_Paginationlist; $cpta++){

					if( $cptaNumber ==  $cpta ){ $active="active"; }else{ $active=""; }
					$setPagination .="<li class='page-item'><a href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";

				}

			} else if ( $cpta_Paginationlist > 5 + ($adjacents * 2) ){

				if( $cptaNumber < 1 + ($adjacents * 2) ){
					
					for( $cpta=1; $cpta <=4 + ($adjacents * 2); $cpta++){

						if( $cptaNumber ==  $cpta ){ $active="active"; }else{ $active=""; }
						$setPagination .="<li class='page-item'><a href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";
					}
					$setPagination .="<li class='page-item'>...</li>";
					$setPagination .="<li class='page-item'><a class='page-link'
					href='javascript:void(0);' onclick='javascript:cptaajaxPagination($last,$cptaLimit)'>".$last."</a></li>";

				} elseif( $cpta_Paginationlist - ($adjacents * 2) > $cptaNumber && $cptaNumber > ($adjacents * 2)) { 

					$setPagination .="<li class='page-item'><a href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination(1,$cptaLimit);'>1</a></li>";
					$setPagination .="<li class='page-item'>...</li>";

					for( $cpta=$cptaNumber - $adjacents; $cpta<=$cptaNumber + $adjacents; $cpta++){

						if( $cptaNumber ==  $cpta ){ $active="active"; }else{ $active=""; }
						$setPagination .="<li class='page-item'><a href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";
	
					}

					$setPagination .="<li class='page-item'>...</li>";
					$setPagination .="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='javascript:cptaajaxPagination($last,$cptaLimit)'>".$last."</a></li>";
					
				} else {

				$setPagination .="<li class='page-item'><a href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination(1,$cptaLimit);'>1</a></li>";
					$setPagination .="<li class='page-item'>...</li>";
					
					for ($cpta = $last - (2 + ($adjacents * 2)); $cpta <= $last; $cpta++){

						if( $cptaNumber ==  $cpta ){ $active="active"; }else{ $active=""; }
						$setPagination .="<li class='page-item'><a href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";
	
					}

				}

			} else {

				for( $cpta=1; $cpta<=$cpta_Paginationlist; $cpta++){
					if( $cptaNumber ==  $cpta ){ $active="active"; }else{ $active=""; }
					$setPagination .="<li class='page-item'><a  href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";
				}
				
			}
			$setPagination .="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='javascript:cptaajaxPagination($cptanext,$cptaLimit)'>Next</a></li>";
			$setPagination .="</ul>";
		}
		echo $setPagination;
	wp_die();
}

function cptapagination_default($atts) {
	global $wpdb;
	
	$atts = shortcode_atts(	array('custom_post_type' => '','cptataxname'=>'','cptacatname'=>'',
	'post_limit' => ''),$atts,'cptapagination');
	
	if($atts['custom_post_type'] !="" ){
		$cptaType = sanitize_text_field($atts['custom_post_type']);	
	}else{
		$cptaType ="post";	
	}
	
	if($atts['cptacatname'] !="" ){
		$cptaCatName = sanitize_text_field($atts['cptacatname']);	
	}else{
		$cptaCatName ="uncategorized";	
	}
	
	if( $atts['post_limit'] !="" ){
		$cptaLimit= absint($atts['post_limit']);	
	}else{
		$cptaLimit=5;
	}

	
	if($atts['cptataxname']!=""){
		global $cptataxname;
		$cptataxname =  sanitize_text_field($atts['cptataxname']);
		$args = array('posts_per_page' => $cptaLimit,
					  'post_type' => $cptaType,
					   $cptataxname=>$cptaCatName,
					  'post_status' => 'publish');
	}else{
		$args = array('posts_per_page' => $cptaLimit,
					  'post_type' => $cptaType,
					  'post_status' => 'publish');	
	}	
	
	
	$cptaQuery = new WP_Query( $args );
	echo "<div id='cptapagination-content'>";
		if( $cptaQuery->have_posts() ){
			while( $cptaQuery->have_posts() ){ $cptaQuery->the_post();
				 echo "<div class ='cpta-Section bg-light-gray'>
				 <h1 class='arch-padding'>".get_the_title()."</h1>".get_the_post_thumbnail($post = null, 'large')."<p class='shadow p-4 mb-4 bg-white lead'>".get_the_excerpt()."</p><a href=".get_the_permalink()." class='btn-default fr-end-butt'>Read More</a>
				</div>";
			} wp_reset_postdata();
		}
		global $cptataxname;
		if($cptataxname!=""){
			$cpta_args = array( 'posts_per_page' => -1,
								'post_type' => $cptaType,
								$cptataxname=>$cptaCatName,
								'post_status' => 'publish');
		}else{
			$cpta_args = array( 'posts_per_page' => -1,
								'post_type' => $cptaType,
								'post_status' => 'publish');
		}
		$cpta_Query = new WP_Query( $cpta_args );
		$cpta_Count = count($cpta_Query->posts);
		$cpta_Paginationlist = ceil($cpta_Count/$cptaLimit);
		$last = ceil( $cpta_Paginationlist );

		$adjacents = "2"; 
		$setPagination = "";
		if( $cpta_Paginationlist > 0 ){

			$setPagination .="<ul class='pagination'>";
			$setPagination .="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='javascript:cptaajaxPagination(1,$cptaLimit)'>Prev</a></li>";

			if ( $cpta_Paginationlist < 7 + ($adjacents * 2) ){

				for( $cpta=1; $cpta<=$cpta_Paginationlist; $cpta++){

					if( $cpta ==  0 || $cpta ==  1 ){ $active="active"; }else{ $active=""; }
					$setPagination .="<li class='page-item'><a  href='javascript:void(0);' id='post' class='$active page-link'data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";

				}

			} else if ( $cpta_Paginationlist > 5 + ($adjacents * 2) ){
					
				for( $cpta=1; $cpta <= 4 + ($adjacents * 2); $cpta++){
					if( $cpta ==  0 || $cpta ==  1 ){ $active="active"; }else{ $active=""; }
					$setPagination .="<li class='page-item'><a  href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";
				}
				$setPagination .="<li class='page-item'>...</li>";
				$setPagination .="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='javascript:cptaajaxPagination($last,$cptaLimit)'>".$last."</a></li>";

			} else {

				for( $cpta=1; $cpta<=$cpta_Paginationlist; $cpta++){
					if( $cpta ==  0 || $cpta ==  1 ){ $active="active"; }else{ $active=""; }
					$setPagination .="<li><a href='javascript:void(0);' id='post' class='$active page-link' data-posttype='$cptaType' data-taxname='$cptataxname' data-cattype='$cptaCatName' onclick='cptaajaxPagination($cpta,$cptaLimit);'>$cpta</a></li>";
				}

			}
			$setPagination .="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='javascript:cptaajaxPagination(2,$cptaLimit)'>Next</a></li>";
			$setPagination .="</ul>";
		}
		echo $setPagination;

	echo "</div>";
}
add_shortcode( 'cptapagination', 'cptapagination_default' );