<?php
/**
 * Template part for displaying Custom Post Type Content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package LaRestaurante
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header>

        <h2 class="entry-title">
            <?php the_title(); ?>
        </h2><br>
        <p><?php _e('Published on', 'larestaurante'); ?>
            <time datetime="<?php echo get_the_date('Y/m/d \a\t g:i A'); ?>"><!--data-->
                <?php the_time('Y/m/d \a\t g:i A'); ?>,
            </time>
            <?php _e('author ', 'larestaurante');
            the_author_link(); ?>

        </p>
        <br>

        <?php


        if (has_post_thumbnail() && $thumbnails_opt_cs) {
            the_post_thumbnail(array(600, 600), array('class' => 'img-responsive'));
        } ?>
        <br><br>
        <?php the_content(); ?>

    </header>
</article>