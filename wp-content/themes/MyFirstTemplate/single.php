  <?php get_header(); ?>
  
    
        <section class="content">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'content', get_post_format());?> 

        <?php endwhile;?>
		
		</section>
		<?php else: ?>
        <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
		<?php get_sidebar(); ?>
		<div style ="clear:both;padding:25px;">
		<?php comments_template(); ?>
		</div>
        
      

     
      <?php get_footer(); ?>