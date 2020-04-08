<?php
/**
 * The template for displaying archive page.
 *
 *
 * Archive Template Page is a collection of previously added posts grouped by time of addition.
 *
 * @link https://codex.wordpress.org/Creating_an_Archive_Index
 *
 * @package LaRestaurante
 */

get_header('nomenu'); ?>
<div  class="container m-arch">
<?php 
	/* https://wordpress.stackexchange.com/questions/274569/how-to-get-url-of-current-page-displayed */
	global $wp;  
	$current_url = home_url(add_query_arg(array(),$wp->request));
	/* https://stackoverflow.com/questions/7395049/get-last-part-of-url-php */
	$end = end(explode('/', $current_url));?>
	
    <div class="row bg-light-gray m-arch">
		<div class="col-lg-12">			
			<h6><?php _e('Find post:','larestaurante')?></h6>
				
					    <?php $search = larestaurante_getQuerySingleParam('search');?>
					    <form class="form-inline" method="get" action="<?php larestaurante_getCurrentPageUrl();?>">
						  <div class="form-group">
                            <input class="form-control" type="text" name="search" id="search" 
									value="<?php echo $search ?>" />
						  </div>
						   <button type="submit" class="btn btn-default fr-end-butt">Submit</button>
						</form>
		</div>
	</div>		   
	<div class="row bg-light-gray m-arch">
		<div class="col-lg-12 text-center">			
			<?php   global $search_ingr;
					if(isset($search_ingr) || isset($search)) :?>
				
					<h6 class="search-results"><?php _e('Search Results','larestaurante');?>:</h6>
				
					<?php endif;

                    global $search_ingr;
						
						if(isset($search_ingr)) {
							global $loop;
						} else {
						
						$query_params = larestaurante_getQueryParams();
							if(isset($query_params['search'])) {
								$query_params['post_title_like'] = $query_params['search'];
								unset($query_params['search']);
							}

						$loop = new WP_Query($query_params);
					
						}?>
						<h6><?php echo esc_html__ ('Post Links','larestaurante')?></h6>
						<ul class="list-inline">
						<?php if($loop->have_posts()) :
						
						while($loop->have_posts()) : $loop->the_post();?>

						<li class="list-inline-item">	
							<a class="btn-lg fr-end-butt"href="<?php the_permalink(); ?>">
							<?php the_title(); ?></a>
						</li>
			
						<?php endwhile; ?>
							<?php else: ?>
					
						<h6><?php _e('Theres no posts','larestaurante')?></h6>
						
					    <?php endif; ?>
						</ul>
		
		</div><!--.col-lg-12 .text-center -->
			  
	</div><!--.row .bg-light-gray .m-arch -->
	<div class="row bg-light-gray m-arch">
		<div class="col-lg-12">	

		<?php $cats  = get_terms( 'category') ;
			  $tags  = get_terms( 'tag') ;
			  if (!empty ($terms) || !empty ($tags ) ):?>                     
			 <h6><?php echo esc_html__('Choose by category or tagname', 'larestaurante')?></h6> 
			 
			<form class="form-inline"  method="get" action="<?php get_permalink();?>">
				<div class="form-group">
					<select class="form-control" name="categoryname">
						<?php
								// generate list of  standard categories
							if	(get_terms( 'category')){
								$cats  = get_terms( 'category') ;
								$cname = isset( $_GET['categoryname'] ) ? $_GET['categoryname'] : " " ;
						
						foreach ( $cats as $cat ) 
						  echo '<option value="', $cat->slug,'"',( $cname == $cat->slug )?  'selected' :' ' ,'>', $cat->name, "</option>\n"; 
							}
							else
						       echo '<option value=""> no categories</option>\n'; 
						?>
					</select>
					<select class="form-control" style="margin-left:25px;" name="tagname">
						<?php 
						// generate list of  standard tags	 
						if	(get_terms( 'post_tag')){
							$tags  = get_terms( 'post_tag') ;
							$tname = isset($_GET['tagname']) ? $_GET['tagname'] : " " ;
						foreach ( $tags as $tag ) 
						   echo '<option value="', $tag->slug,'"',( $tname == $tag->slug )?  'selected' :' ' ,'>', $tag->name, "</option>\n";
							}
							else
						       echo '<option value="">no tags</option>\n'; ?>
					</select>
				</div><!--.form-group-->	
				 
				<div class="form-group" style="margin-left:25px;">	
					<?php $order = isset ($_GET['order']) ? $_GET['order'] :  " " ; ?>
					​<label class="radio-inline">
						<input type="radio" class="form-check-input" name="order" 
						value="asc" <?php echo ( $order == 'asc' )? 'checked' :' ' ?>> asc
					</label>
					​<label class="radio-inline">
						<input type="radio" class="form-check-input" name="order" 
						value="desc"<?php echo ($order == 'desc' )? 'checked' :' ' ?>> desc
					</label>
				​	<label class="radio-inline">
						<input type="radio" class="form-check-input" name="order" 
						value="rand"<?php echo ($order == 'rand' )? 'checked' :' ' ?>> random 
					</label>
				</div><!--.form-group-->
				
			<button type="submit" class="btn btn-default fr-end-butt">
			<?php echo esc_html__ ('Submit','larestaurante');?></button>
         
			</form>
		<!-- display content has found -->	
		<h6 style="padding:25px;" class="pointscroll" ><?php _e('content found','larestaurante')?></h6>

			<?php $cat = isset ( $_GET['categoryname'] ) ? $_GET['categoryname'] : "no-category "; 
				  $tag = isset ( $_GET['tagname'] ) ? $_GET['tagname'] : "no-tag "; 
				  $order = strtoupper ( $order ) ;

					$args = array(
					'tax_query' => array(
					'relation'  => 'OR',
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => $cat,
					),
					array(
						'taxonomy' => 'tag',
						'field' => 'slug',
					   'terms' => $tag,
						)
					)
				);
				if ($order === 'rand'){$args['orderby']= $rand.',';}
				else{
					$args['orderby']['title'] = $order;
					$args['orderby']['menu_order'] = $order;
					$args['orderby']['menu_order'].',';
					}    
				
				$querycat = new WP_Query( $args );
				while ($querycat->have_posts() ) : $querycat->the_post(); ?>
							<?php get_template_part( 'template-parts/content-cpt', get_post_format() );?>
							<?php endwhile; 
				wp_reset_postdata();  
				else:?>
			<h3><?php _e('Sorry theres no tags or category assigned to any posts','larestaurante');?></h3>
						<?php endif;?>
		</div><!--col-lg-12 -->		
	</div><!--row bg-light-gray m-arch-->	

	
</div><!--container m-arch -->
<div  class="container">
	<div class="row m-arch">
		<div class="col-lg-12 text-center">	
			<div class="offset-lg-3 col-lg-6 bg-light-gray m-arch">	
			<?php $archive_post_type = $end;
				 // $correct_pattern = preg_match('/[a-zA-Z]/', $archive_post_type);
							   
						 if (!preg_match('/[a-zA-Z]/', $archive_post_type) ){$archive_post_type = 'post';}  ?>
						 <?php if ($archive_post_type=='post' ):?>
						 <?php the_archive_title( '<h1 class="text-center arch-padding">', '</h1>' );?>
						 <?php else:?>
						 <a href="<?php echo get_post_type_archive_link( $archive_post_type ); ?>">
						 <h1 class="text-center arch-padding">
						 <?php echo ucfirst(esc_html($archive_post_type ));?>&nbsp;Archive
						 </h1></a>
						 <?php endif;?>
				<hr class="styled">
			</div>
			<?php echo do_shortcode( '[cptapagination custom_post_type='.$archive_post_type.' post_limit="3"]' );?>
		</div> 
	
	</div> 
</div> 
<?php get_footer(); ?>