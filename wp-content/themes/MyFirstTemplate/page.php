
  <?php get_header(); ?>
		<section class="content">
          <h1>to jest strona page wp</h1> 
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'content', get_post_format());?> 

        <?php endwhile;?>
		
		</section>
        <?php else: ?>
        <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
       <?php endif; ?>
	   <?php get_sidebar(); ?>
     
      <?php get_footer(); ?>


