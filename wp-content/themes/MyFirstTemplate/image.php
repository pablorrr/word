<?php
/**
 * The template for displaying image attachments
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 
 sluzy do wyswietlaniaq pojedynczego obrazka
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		<h1> to jest image file</h1>
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-meta">
						<?php
							$published_text = __( '<span class="attachment-meta">Published on <time class="entry-date" datetime="%1$s">%2$s</time> in <a href="%3$s" title="Return to %4$s" rel="gallery">%5$s</a></span>', 'twentythirteen' );
							
							$post_title = get_the_title( $post->post_parent );//wyciaganie tytulu z posat //nadrzednego
							if ( empty( $post_title ) || 0 == $post->post_parent )//jesli tytul posta nadrzednego //nie istnieej
								$published_text = '<span class="attachment-meta"><time class="entry-date" datetime="%1$s">%2$s</time></span>';

							printf( $published_text,
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() ),//wyciaganie daty
								esc_url( get_permalink( $post->post_parent ) ),//tworzenie linka do posta
								esc_attr( strip_tags( $post_title ) ),//strip_tags — Strip HTML and PHP tags from //a string
								$post_title
							);
								/* metadane obrazka np szerokosc wyskosc */
							$metadata = wp_get_attachment_metadata();//wyciagenie metedanych z zalacznika (obrazek
							printf( '<span class="attachment-meta full-size-link"><a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a></span>',
								esc_url( wp_get_attachment_url() ),
								esc_attr__( 'Link to full-size image', 'twentythirteen' ),
								__( 'Full resolution', 'twentythirteen' ),
								$metadata['width'],
								$metadata['height']
							);
								/* edycja zalacznika */
							edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' );
						?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->
<!--previous_image_link---This creates a link to the previous image attached to the current post. Whenever a series of images are linked to the attachment page, it will put a 'previous image link' with the images when viewed in the attachment page.-->
				<div class="entry-content">
					<nav style="position:absolute;top:50%;" id="image-navigation" class="navigation image-navigation" role="navigation">
						<span style="font-size:15pt;" class="nav-previous"><?php previous_image_link( false, __( '<span class="meta-nav">&larr;</span> Previous', 'twentythirteen' ) ); ?></span>
						
						<span style="position:relative;left:100%;font-size:15pt;" class="nav-next"><?php next_image_link( false, __( 'Next <span  class="meta-nav">&rarr;</span>', 'twentythirteen' ) ); ?></span>
						
					</nav><!-- #image-navigation -->

					<div class="entry-attachment">
						<div  class="attachment">
							<?php my_the_attached_image();//funkcj a ta pochodzi z functions dla obslugi //zalznikow image ?>

							<?php if ( has_excerpt() ) : //jesli ma czytaj dalej?>
							<div class="entry-caption">
								<?php the_excerpt(); //wyswiuetlanie skroconego posta?>
							</div>
							<?php endif; ?>
						</div><!-- .attachment -->
					</div><!-- .entry-attachment -->
					
<!--wp_link_pages--Displays page links for paginated posts (i.e. includes the <!–nextpage–>. Quicktag one or more times). This tag must be within The Loop.-->


					<?php if ( ! empty( $post->post_content ) ) : ?>
					<div class="entry-description">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentythirteen' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-description -->
					<?php endif; ?>

				</div><!-- .entry-content -->
			</article><!-- #post -->

			<?php comments_template(); ?>

			<?php endwhile; // End the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
