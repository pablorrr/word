<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package larestaurante
 */

get_header(); ?>

	<section id="primary" class="content-area col-sm-12 col-lg-8">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
			
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'larestaurante' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'larestaurante' ); ?></p>

					<?php
					 //retriving group opt value to get codestar 404 search display option switcher on/off		
					$cs_switcher_get_option_group = cs_get_option('group_basic_404'); 

					if ( ! empty ($cs_switcher_get_option_group)){
						foreach( $cs_switcher_get_option_group as $single ){
						 if ( !empty($single['404_page_switcher']))
							 $search_opt_cs = $single['404_page_switcher'];
						 else
							 $search_opt_cs = NULL;} 
						 }
					 else 
						 $search_opt_cs = NULL;
					 if ($search_opt_cs) {get_search_form();}
					?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();