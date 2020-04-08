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
 */ 

function cptaajaxPagination(pnumber,plimit,e){
	
	var nth  = pnumber;
	var lmt  = plimit;
	var ajax_url = ajax_params.ajax_url;
	var cpta = jQuery("#post").attr('data-posttype');
	var cptacat = jQuery("#post").attr('data-cattype');
	var cptatax = jQuery("#post").attr('data-taxname');
	jQuery.ajax({
		url		:ajax_url,
		type	:'post',
		data	:{ 
					'action':'cptapagination',
					'number':nth,
					'limit':lmt,
					'cptapost':cpta,
					'cptacatname':cptacat,
					'cptataxname':cptatax 
		},
		beforeSend	: function(){
			 
			 var elmnt = document.querySelector(".pointscroll");
			 elmnt.scrollIntoView();
			
			 jQuery("#cptapagination-content").html("<div class='offset-md-6 col-md-6 loader'></div>");
		},
		success :function(pvalue){
			jQuery("#cptapagination-content").html(pvalue);
		}
	});
}