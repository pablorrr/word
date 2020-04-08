<?php get_header();?>
 <section class="content">
 <?php while(  have_posts() ) :  the_post(); ?>
      <h2> wyniki szukania wp query-strona serach.php wp</h2>  
          <div class="">
            <a class="" href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( $size = 'thumbnail'); ?>
            </a>
            <div class="">
              <a href="<?php the_permalink(); ?>"><h4 class=""><?php the_title(); ?></h4></a>
              <?php the_excerpt(); ?>
              <a href="<?php the_permalink(); ?>" class="">Czytaj wiêcej</a>
            </div>
          </div>
          <hr>

<?php endwhile;?>
	  
	</section>
	
<?php get_footer();?>	