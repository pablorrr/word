<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package larestaurante
 */

get_header('nomenu'); ?>
<div id="content"  class="container">

		<div class="row mx-auto m-single">
			<section id="primary" class="content-area col-sm-12 col-md-8 col-lg-8">
				<main id="main" class="site-main" role="main">

				<?php   if ( have_posts() ) :

						if ( is_home() && ! is_front_page() ) : ?>
							<header>
								<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
							</header>

				<?php   endif;

					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

					the_posts_navigation();

				else :

					get_template_part( 'template-parts/content','none' );

				endif; ?>

				</main><!-- #main -->
			</section><!-- #primary -->
		<?php
		//get option from metabox field placed in edit cpt -"menu" to on/off sidebar
		$sidebar_show = get_post_meta( $post->ID, 'larestaurante_page_layout', true );
				if ($sidebar_show !== 'no-sidebar') { get_sidebar();}?>
		</div><!--.row .mx-auto .m-single -->
</div><!--#content-->
		
<?php get_footer();?>