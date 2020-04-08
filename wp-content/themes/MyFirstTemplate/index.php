<?php get_header();?>
<div   role="main" >
  <section class="content">
    
  <?php if( have_posts() ): while( have_posts() ): the_post(); ?>
  <?php get_template_part( 'content');?>
  <?php endwhile;?>

  </section>
  <?php else : ?>
	 <h3>Przykro mi nie ma postów</h3>
  <?php endif; ?>
  
  <?php get_sidebar(); ?>
  <!--pobrany przykład z.. https://www.sitepoint.com/add-advanced-search-wordpress-site/   -->
 
 
<section class="content">
 <h2>formularz przeszukiwania wg standardowych standardowych postów mojego autorstwa</h2>
	<form  method="get" action="<?php bloginfo('url'); ?>">
		<fieldset>
		 <select name="s"><!--paqrametrr s od search jest wbudowany w wpp i nie mozna zmieniac jejgop nazwy!!-->
			<?php
			$args = array( 
			'post_type' => 'post', 
				); 
			$myposts = get_posts($args);

				foreach ( $myposts as $post ) : setup_postdata( $post ); ?> 

     <option  value="<?php the_title();?>"><?php the_title();?></option>
      
	<?php endforeach;
		wp_reset_postdata(); ?>
	  
	  </select>
		<button type="submit">Search</button>
		</fieldset>
	</form>
	

 </section>
 
 
 
 <?php get_template_part('my-query-search-Form');?>
 
 
 
<?php get_footer();?>



