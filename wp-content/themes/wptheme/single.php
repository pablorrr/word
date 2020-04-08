<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @link https://developer.wordpress.org/themes/template-files-section/post-template-files/
 *
 * @package larestaurante
 * 
 */

get_header('nomenu');?>

<div class="container">

		<div class="row mx-auto m-single">
		
			<section id="primary" class="content-area col-sm-12 col-md-8 col-lg-8">
				<main id="main" class="site-main" role="main">

					<?php while ( have_posts() ) : the_post();
					  
					//get option from metabox field placed in edit cpt -"menu" to on/off breadcrumbs
					$breadcrumbs_show = get_post_meta( $post->ID, 'breadcrumbs_show', true );
							if ($breadcrumbs_show !== 'no-breadcrumbs') {larestaurante_the_post_breadcrumb();}

					get_template_part('template-parts/content-cpt');

					  //retriving group opt value to get codestar comment option switcher on/off		
					$cs_switcher_get_option_group = cs_get_option('group_basic_single'); 
							
							if ( ! empty ($cs_switcher_get_option_group)){
								foreach( $cs_switcher_get_option_group as $single ){
								 if ( !empty($single['single_comments_switcher']))
									 $comment_opt_cs = $single['single_comments_switcher'];
								 else
									 $comment_opt_cs = NULL;} 
							}
							 else 
								 $comment_opt_cs = NULL;
								
					   if ( is_single() && $comment_opt_cs && comments_open() ) {comments_template('',true);}
						
					   elseif (is_user_logged_in()){
						   
						 printf( wp_kses( __( 'Comments are disabled go to admin panel to change it,you can also check settings->discuss or if your custom post type supports comments <a href="%1$s" target="%2$s">enable comments please here</a>.', 'larestaurante' ), 
						 array( 'a' => array( 'href' => array(), 'target' => array() )) ),
						esc_url(admin_url('themes.php?page=lares') ) , '_blank' );
						}?>
						
					<?php endwhile ;?>
				</main><!--#main-->
			</section><!--#primary-->	
		
			<?php
			//get option from metabox field placed in edit cpt -"menu" to on/off sidebar
			$sidebar_show = get_post_meta( $post->ID, 'larestaurante_page_layout', true );
					if ($sidebar_show !== 'no-sidebar') { get_sidebar();}?>
		</div><!--.row .mx-auto .m-single -->
</div><!--.container-->
<?php get_footer();?>