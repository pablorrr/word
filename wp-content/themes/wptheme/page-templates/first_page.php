<?php 
/**
 * Template Name:First Page
 *
 * 
 */ 

$cs_sorter_get_option = cs_get_option('segment_sort');
		
		if ( array_key_exists('enabled', $cs_sorter_get_option)  ):
		
		get_header();
		do_action('larestaurante_before_content');
 
					  foreach( $cs_sorter_get_option['enabled'] as $key => $value   ){
					    get_template_part('frontpage-segments/'.$value ); 
					  }
   
		 else:
		 get_header('nomenu');?>
		 
			<div id="load-posts" class="container larestaurante-posts-container">
				<div id="content" class="row">
					<div  class="col-md-12">
					<?php 
						if ( is_user_logged_in() && current_user_can( 'manage_options' ) ){
						printf( wp_kses( __( 'You can turn on segment mode in admin panel or change settings to display only blog post(no static page), 
						<a href="%1$s" target="%2$s">here</a>.', 'larestaurante' ), 
						array( 'a' => array( 'href' => array(), 'target' => array() )) ),
						esc_url( admin_url( 'themes.php?page=lares' ) ), '_blank' );	
						}
						if( have_posts() ):
							
							while( have_posts() ): the_post(); 
								
								get_template_part( 'template-parts/content', get_post_format() );
							
							endwhile;
						the_posts_navigation();

						else :

						get_template_part( 'template-parts/content','none' );	
						endif;
						?>
					</div><!-- .col -->	
					
				</div><!-- .row -->	
			</div><!--#load-post .container -->
		<?php endif;?>
		
	<?php do_action('larestaurante_after_content');?>	
<?php get_footer();?>