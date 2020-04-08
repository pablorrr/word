<div style="margin:30px;">
<h1 style="font-size:45px">Tworzenie motywu Wordpress z własnym typem postu.</h1>
<br>
<h2>Jeśli nie chcesz robić wszytsko ręcznie to tutaj sciągniesz cały szablon o motorach i samochodach <?php echo do_shortcode('[easy_media_download url="http://wptutorial.websitecreator.pl/wp-content/uploads/tutorial.zip" text=" TU sciągnij szablon" color="green_dark"]');?> </h2>
<p>Po ściągnięciu szablonu i skopiowania go do folderu themes, musisz zaimportować treść;<a style="color:blue"; href="#tips">przejdź do rozdziału o importowaniu treści WP</a></p>
<h2 style="font-size:35px">Spis treści</h2>


<?php global $query_string;
		query_posts( $query_string . '&orderby=date&order=ASC' );//kolejnosc wpisow od najstarszego do najnowszego?>
		<?php if ( have_posts() ) : ?>

			<?php
			// Start the loop.
		 
		while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				
				get_template_part( 'content-sidebar');?>
				<?php endwhile;?>
				<?php endif;?>
</div>