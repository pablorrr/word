	<div style="margin:10px;">
	<?php global $query_string;
		query_posts( $query_string . '&orderby=date&order=ASC' );//kolejnosc wpisow od najstarszego do najnowszego?>
	<?php
			// Start the loop.
		 
		while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				 get_template_part( 'content-sidebar');

			// End the loop.
			endwhile;?>


	</div><!-- .secondary -->

