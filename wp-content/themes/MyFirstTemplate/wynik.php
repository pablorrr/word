<?php
/* 
Template Name: wynik 

*/


 get_header(); ?>
 
    <section class = "content">
     <h1>tresci wyszukane</h1>

<?php 
//if(( isset( $_GET['categoryname']) && $_GET['categoryname']) || ( isset( $_GET['tagname']) && $_GET['tagname']) )
$cat = $_GET['categoryname'];
$tag = $_GET['tagname'];
$order = strtoupper ( $_GET['order'] ) ;

echo $order;

	$args = array(
	'post_type' => 'post',
	'tax_query' => array(
	'relation' => 'OR',	
					/*array(
                      
					  'taxonomy' => 'tag',
                     'field'    => 'slug',
                     'terms'    => array( $tag ),
                        ), */

                    array(
                      
					 'taxonomy' => 'category',
                     'field'    => 'slug',
                     'terms'    => array( $cat ),
                        ) ,

	),
	 'tag' => $tag ,
	'author_name' => 'johny2',
	'date_query' => array(
		array(
			'after'     => 'January 1st, 2013',//po wartosci podanej 
			'before'    => array(//przed data podana ponizej
				'year'  => 2017,
				'month' => 2,
				'day'   => 28,
			),
			'inclusive' => true,//For after/before, whether exact value should be matched or not'. 
		),
	),
	'posts_per_page' => -1,
);

 if ($order === 'RAND'){
	
	$args['orderby']= $rand.',';
	}
	
	else{
		$args['orderby']['title'] = $order;
		$args['orderby']['menu_order'] = $order;
		$args['orederby']['menu_order'].',';
		}  
	
$querycat = new WP_Query( $args );?>

<?php while ($querycat->have_posts() ) : $querycat->the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; 
 wp_reset_postdata(); 
 //$query = new WP_Query( array( 'author_name' => 'rami' ) );
?>	  
	</section>

      
      <?php get_footer(); ?>
 

