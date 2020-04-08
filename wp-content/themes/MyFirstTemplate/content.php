<?php if ( has_post_thumbnail() )  the_post_thumbnail() ;  ?>
	
<?php	$mycategory = get_the_category_list();//pobiera aktualna kategorie i tqworzuyy link do archiwum kategorii //( pliku category)
	if ( $mycategory ) {
		echo '<span class="categories-links">' . $mycategory . '</span>';
	}
	$mytag_list = get_the_tag_list();
	if ( $mytag_list ) {
		echo '<span class="tags-links">' . $mytag_list . '</span>';
	}
	
	?>

				<div class="author-info">
				<p> <?php edit_post_link();?></p>
					<div class="author-avatar">
						<?php
						/** This filter is documented in author.php */
						
						echo get_avatar( get_the_author_meta( 'user_email' ), 32 );
						?>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2><?php printf( 'About %s', get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
						<div class="author-link">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								<?php printf( 'View all posts by %s <span class="meta-nav">&rarr;</span>',  get_the_author() ); ?>
							</a>
						</div><!-- .author-link	-->
					</div><!-- .author-description -->
				</div><!-- .author-info -->



	
	
	
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <p><?php the_author(); ?></p>
            <a href="<?php the_permalink(); ?>"></a>
			
            <?php the_content('czytaj dalej'); ?>
           


