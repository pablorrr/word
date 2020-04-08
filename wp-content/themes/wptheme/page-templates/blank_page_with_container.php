<?php
/**
 * Template Name: Blank with Container
 */

get_header();
?>
<div class="container">

	<div class="row mx-auto m-single">
		<section id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<div class="container">
				<h1>jkljkljlj</h1>
					<?php
					while ( have_posts() ) : the_post();
					$breadcrumbs_show = get_post_meta( $post->ID, 'breadcrumbs_show', true );
							if ($breadcrumbs_show !== 'no-breadcrumbs') {larestaurante_the_post_breadcrumb();}
						get_template_part( 'template-parts/content', 'notitle' );
					endwhile; // End of the loop.
					?>
				</div>
			</main><!-- #main -->
		</section><!-- #primary -->
	</div>
	
</div>
<?php get_footer();?>
