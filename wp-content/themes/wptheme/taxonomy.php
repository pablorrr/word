<?php
/**
 * The template for displaying taxonomy
 *
 * @link https://codex.wordpress.org/Taxonomies
 *
 * @package larestaurante
 * 
 */
get_header(); ?>
<section id="primary" >
	<div class="container">

		<div class="row mar-top-20"> 
            <div class="col-md-12">
				<?php $category_desc = category_description();
					  if(!empty($category_desc)) : ?>
						<div><p> <?php echo 'description :'.$category_desc; ?></p></div>
				<?php endif;?>
				<h5><?php echo ucfirst(single_cat_title('tag/category title:', false)); ?></h5>
							<?php if ( have_posts  () ): while ( have_posts() ) : the_post();?>
							<?php get_template_part( 'content', get_post_format() );?>
							<?php endwhile; ?>
					<?php else:?>
			   <div class="col-md-12">
				<h2 style="padding:100px;">
				<?php _e('Sorry, there are no posts in this category','larestaurante');?></h2>
			   </div>
				<?php endif;?>
			</div>
		</div>
	
	<!--pagination-->	
	<div class="container">
		<div class="row row-centered">
			<div class="col-xs-6 col-centered">
				<ul class="pagination">
					 <li>
					<?php $big = 999999999; // need an unlikely integer,
							echo paginate_links( array(//paginate links 
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
												'format' => '?paged=%#%',
												'current' => max( 1, $paged ),
												'total' => $wp_query->max_num_pages,
												'prev_text' => '&lt;&lt;',
												'next_text' => '&gt;&gt;',
												'type' => 'list'
											) ); ?>
					 </li>		
				</ul>			
			</div>
		</div>
	</div><!--end pagination .container-->
	</div><!--.container-->
</section><!--#primary -->
	<div class="row mar-bot-30"></div>
<?php get_footer(); ?>