<?php
/**
 * Template Name:First Page
 *
 *
 */


get_header();
do_action('larestaurante_before_content');
?>

    <div id="load-posts" class="container larestaurante-posts-container">
        <div id="content" class="row">
            <div class="col-md-12">
                <?php

                if (have_posts()):

                    while (have_posts()): the_post();

                        get_template_part('template-parts/content', get_post_format());

                    endwhile;
                    the_posts_navigation();

                else :

                    get_template_part('template-parts/content', 'none');
                endif;
                ?>
            </div><!-- .col -->

        </div><!-- .row -->
    </div><!--#load-post .container -->


<?php do_action('larestaurante_after_content'); ?>
<?php get_footer(); ?>