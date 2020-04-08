<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package LaRestaurante
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-thumbnail">
	
	<?php  
  ///retriving group opt value to get codestar comment option switcher on/off		
$cs_switcher_get_option_group = cs_get_option('group_basic_single'); 

			if ( ! empty ($cs_switcher_get_option_group)){
			foreach( $cs_switcher_get_option_group as $single ){
			 if ( !empty($single['single_thumbnails_switcher']))
				 $thumbnails_opt_cs = $single['single_thumbnails_switcher'];
		     else
				 $thumbnails_opt_cs = NULL;} 
		}
		 else 
			 $thumbnails_opt_cs = NULL; 
		
		 
			if( has_post_thumbnail() && $thumbnails_opt_cs) {
				the_post_thumbnail(array(600,600), array('class'=>'img-responsive'));
				}
?>
	</div>
	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php larestaurante_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
        if ( is_single() ) :
			the_content();
        else :
            the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'larestaurante' ) );
        endif;

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'larestaurante' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php larestaurante_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
