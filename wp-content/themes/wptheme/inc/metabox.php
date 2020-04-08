<?php
/**
 * Metabox
 *
 * source url :https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
 *
 * @package LaRestaurante
 *
 */
 
// ---------------------------------------------
// Add Meta Boxes.   -
//-----------------------------------------------

add_action( 'add_meta_boxes', 'larestaurante_add_custom_box' );
 
function larestaurante_add_custom_box() {
$services_cpt_select = cs_get_option ('services_cpt_select')   ? cs_get_option ('services_cpt_select') : 'post';
$aboutus_cpt_select = cs_get_option ('aboutus_cpt_select')     ? cs_get_option ('aboutus_cpt_select'): 'post';
$ourplaces_cpt_select = cs_get_option ('ourplaces_cpt_select') ? cs_get_option ('ourplaces_cpt_select'): 'post';
$menu_cpt_select = cs_get_option ('menu_cpt_select')           ? cs_get_option ('menu_cpt_select') : 'post';
$team_cpt_select = cs_get_option ('team_cpt_select')           ? cs_get_option ('team_cpt_select'): 'post';

	// Adding layout meta box for Page
	add_meta_box(
	'page-layout', //id metabox
	esc_html__( 'Select Layout', 'larestaurante' ),
	'larestaurante_meta_form_callback',//callback 
	'page',
	'side'
	);
	// Adding layout meta box for Standard Post
	
	add_meta_box( 
	'post-layout', //id metabox
	esc_html__( 'Select Layout','larestaurante' ),
	'larestaurante_meta_form_callback',//callback 
	'post',
	'normal',
	'high'
	);
	// Adding layout meta box for menu Post
	if($menu_cpt_select && $menu_cpt_select != 'post'){
		add_meta_box( 
		'post-layout', //id metabox
		esc_html__( 'Select Layout','larestaurante' ),
		'larestaurante_meta_form_callback',//callback 
		$menu_cpt_select,
		'normal',
		'high'
		);
	}
	// Adding layout meta box 
	if($ourplaces_cpt_select && $ourplaces_cpt_select != 'post'){
	add_meta_box( 
	'post-layout', //id metabox
	esc_html__( 'Select Layout','larestaurante' ),
	'larestaurante_meta_form_callback',//callback 
	$ourplaces_cpt_select,
	'normal',
	'high'
	);
	}
	
	// Adding layout meta box for service post
	// Adding layout meta box 
	if($services_cpt_select && $services_cpt_select != 'post'){
	add_meta_box( 
	'post-layout', //id metabox
	esc_html__( 'Select Layout','larestaurante' ),
	'larestaurante_meta_form_callback',//callback 
	$services_cpt_select,
	'normal',
	'high'
	);
	}
	// Adding layout meta box for aboutus post
	if($aboutus_cpt_select && $aboutus_cpt_select != 'post'){
	add_meta_box( 
	'post-layout', //id metabox
	esc_html__( 'Select Layout','larestaurante' ),
	'larestaurante_meta_form_callback',//callback 
	$aboutus_cpt_select,
	'normal',
	'high'
	);
	}
	
	// Adding layout meta box for team post
	if($team_cpt_select && $team_cpt_select != 'post'){
	add_meta_box( 
	'post-layout', //id metabox
	esc_html__( 'Select Layout','larestaurante' ),
	'larestaurante_meta_form_callback',//callback 
	$team_cpt_select,
	'normal',
	'high'
	);
	}
}

// ---------------------------------------------
// metabox options     -
// ---------------------------------------------

global $larestaurante_page_layout,$larestaurante_post_breadcrumb;
$larestaurante_page_layout = array(
							'default-layout' 	=> array(
														'id'			=> 'larestaurante_page_layout',
														'value' 		=> 'yes-sidebar',
														'label' 		=> esc_html__( 'Sidebar On', 'larestaurante' )
														),
							'no-sidebar' 	=> array(
														'id'			=> 'larestaurante_page_layout',
														'value' 		=> 'no-sidebar',
														'label' 		=> esc_html__( 'Sidebar Off', 'larestaurante' )
														)
				);

$larestaurante_post_breadcrumb = array(

							'default-breadcrumbs' 	=> array(
														'id'			=> 'breadcrumbs_show',
														'value' 		=> 'yes-breadcrumbs',
														'label' 		=> esc_html__( 'Breadcrumbs On', 'larestaurante' )
														),
														
							'no-breadcrumbs' 	=> array(
														'id'			=> 'breadcrumbs_show',
														'value' 		=> 'no-breadcrumbs',
														'label' 		=> esc_html__( 'Breadcrumbs Off', 'larestaurante' )
														)	
														
														);


// ---------------------------------------------
// metabox callback     -
// ---------------------------------------------


function larestaurante_meta_form_callback( $larestaurante_page_layout, $larestaurante_post_breadcrumb ) {
	
	global $larestaurante_page_layout,$larestaurante_post_breadcrumb;
	global $post;

	// Use nonce for verification
	
	
	wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );?>
	<div style="overflow:hidden; width:100%">
	           
	         <div style="margin-right:260px">
	               <?php foreach ( $larestaurante_page_layout as $field ) {
					   
						$layout_meta = get_post_meta( $post->ID, $field['id'], true );
				
					
						if(  !$layout_meta  ) { $layout_meta = 'yes-sidebar'; } ?>
						<input class="post-format" type="radio" name="<?php echo esc_attr($field['id']); ?>"
						value="<?php echo esc_attr( $field['value'] ); ?>"<?php checked( $field['value'], $layout_meta ); ?>/>
						<label class="post-format-icon"><?php echo esc_html( $field['label'] ); ?></label><br/>
				
			<?php 	}
			
		            foreach ( $larestaurante_post_breadcrumb as $field ) {
				
				
				       $layout_meta = get_post_meta( $post->ID, $field['id'], true );
				
					
						if(  !$layout_meta  ) { $layout_meta = 'yes-breadcrumbs'; }  ?>
						<input class="post-format" type="radio" name="<?php echo esc_attr($field['id']); ?>"
						value="<?php echo esc_attr( $field['value'] ); ?>"<?php checked( $field['value'], $layout_meta ); ?>/>
						<label class="post-format-icon"><?php echo esc_html( $field['label'] ); ?></label><br/>
			
			
		<?php	 }//foreach  ?>				
	           </div>
	 </div>
 <?php
}

// ---------------------------------------------
// metabox saving     -
// ---------------------------------------------

add_action('save_post', 'larestaurante_save_custom_meta');
/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function larestaurante_save_custom_meta( $post_id ) {
	global $larestaurante_page_layout, $post, $larestaurante_post_breadcrumb , $post;

	// Verify the nonce before proceeding. 
   if( !isset( $_POST[ 'custom_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ 'custom_meta_box_nonce' ], basename( __FILE__ ) ) )
      return;

	// Stop WP from clearing custom fields on autosave-  
   if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
      return;

	if( 'page' == $_POST['post_type'] ) {
      if( !current_user_can( 'edit_page', $post_id ) )
         return $post_id;
   }
   elseif( !current_user_can( 'edit_post', $post_id ) ) {
      return $post_id;
   }

   foreach( $larestaurante_page_layout as $field ) {
		//Execute this saving function
		$old = get_post_meta( $post_id, $field['id'], true );
		
		
		$new = sanitize_key( $_POST[$field['id']] );
		if( $new && $new != $old ) {
			
			update_post_meta( $post_id, $field['id'], $new );
			
		} elseif ( '' == $new && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	} // end foreach

	
	foreach( $larestaurante_post_breadcrumb as $field ) {
		//Execute this saving function
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = sanitize_key( $_POST[$field['id']] );
		if( $new && $new != $old ) {
			update_post_meta( $post_id, $field['id'], $new );
		} elseif ( '' == $new && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	} // end foreach
}
?>