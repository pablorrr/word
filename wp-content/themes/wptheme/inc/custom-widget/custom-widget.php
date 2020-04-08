<?php
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// Custom Widget for this Theme
// -----------------------------------------------------------------------------------------------
// ===============================================================================================

/* 
 * Allows displaying posts and comments with authors.
 */


class larestaurante_widget extends WP_Widget{
    
    function __construct(){

        $widget_options = array(
            'classname' => 'larestaurante_widget',
            'description' => 'posts tags'
        );
        
        parent::__construct('larestaurante-widget', 'Additional posts', $widget_options);
		
		   if(!is_admin()){
			  add_action('wp_print_styles', array($this, 'registerStyles'));
			  /* add_action('wp_enqueue_scripts', array($this, 'registerScripts')); */
		  }
		}
           // widget style registration
			function registerStyles(){
             wp_enqueue_style('styles-widget',get_template_directory_uri(). '/inc/custom-widget/styles/styles.css' );
               
         }
		 
// ------------------------------
// Front End part     -
// ------------------------------	 

    function widget($args, $instance){
        
        extract($args);
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        echo $before_widget;
		 
        if ( $title ) { echo $before_title . $title . $after_title;}
       
        $entries_count = (!empty($instance['entries_count'])) ? (int)$instance['entries_count'] : 5;
        $entry_type = (!empty($instance['entry_type'])) ? $instance['entry_type'] : 'post';
        
        //coments
		 $comments_count = (!empty($instance['comments_count'])) ? (int)$instance['comments_count'] : 3;
		//wp tag clouds
		 $taxonomy = (!empty($instance['taxonomy'])) ? $instance['taxonomy'] : 'category';
		 //checkbox
		 $avatar = $instance[ 'avatar' ] ? 'true' : 'false'; 
		 $author = $instance[ 'author' ] ? 'true' : 'false'; 
		 $data   = $instance[ 'data' ] ? 'true' : 'false'; 
		 $comment_content = $instance[ 'comment_content' ] ? 'true' : 'false'; 
		//comments
		 function fetchRecentComs($limit = 1) {

	    global $wpdb;
        
		$limit = (int)$limit;
		$res = $wpdb->get_results("
            SELECT C.*, P.post_title
                FROM {$wpdb->comments} C
                    LEFT JOIN {$wpdb->posts} P ON C.comment_post_ID = P.ID
                WHERE comment_approved = 1
                ORDER BY comment_date_gmt DESC
                LIMIT {$limit}
        ");
                
        return $res;
		}
		 
		$recent_comments = fetchRecentComs($comments_count); 
		$postTitle = $instance[ 'entry_type' ];
        echo '<p class="post">Post type: '.$postTitle.'</p>';
	    $loop = new WP_Query(array(
                    'post_type' => $entry_type,
                    'posts_per_page' => $entries_count
					));
        
        if(!$loop->have_posts()){
            echo '<p>'.__('no posts','larestaurante').'</p>';
        }else{
            echo '<ul>';
            while($loop->have_posts()){
                $loop->the_post();?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></li>
       <?php }
           echo '</ul>';}?>
		<p class="comment"><?php echo esc_html__('Recent comment:','larestaurante');?></p>
		<?php
		foreach($recent_comments as $comment){
            $date = new DateTime($comment->comment_date_gmt);?>
                <div id="fadeincom">
                    <header>
                     <?php if( 'on' == $instance[ 'author' ] ) 
						echo   esc_html__('Author: ','larestaurante').$comment->comment_author;
					   if( 'on' == $instance[ 'data' ] ) 
						echo   esc_html__(' on day: ','larestaurante').$date->format('d.m.Y'); ?>
                        <?php echo $comment->post_title; ?>
                    </header>
                    <?php
					 if( 'on' == $instance[ 'avatar' ] ) echo get_avatar($comment->user_id, 29); ?>
                    <blockquote>
					    <p>
                        <?php if( 'on' == $instance[ 'comment_content' ] ){
						         $commentContent =	substr($comment->comment_content, 0, 250);  // abcdef
						        echo '" '.$commentContent.'[...]'.' " ';
								 $link = get_permalink( $comment->comment_post_ID );
								 echo '<br><a href='.$link.'>Go to the post with this comment</a>';} ?>
                        </p>
					</blockquote>
                </div>
			
			<?php } ?>
	
		<p class="taxonomy"><?php echo esc_html__('Taxonomies :','larestaurante');?></p>
		<div id="fadein">
				<div class="tag-cloud">
        
				  <?php wp_tag_cloud(array(
						'taxonomy' => $taxonomy,
						'smallest' => 11,
						'largest' => 16.5,
						'unit' => 'px'
					)); ?>
				
				</div>
				
	   </div>
        
        
<?php echo $after_widget;  
}
    
	//update
	function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    
    
	$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
    $instance['entries_count'] = $new_instance[ 'entries_count' ];
	$instance['entry_type'] =  $new_instance[ 'entry_type' ];
    $instance[ 'avatar' ] = $new_instance[ 'avatar' ];
	$instance[ 'author' ] = $new_instance[ 'author' ];
	$instance[ 'data' ] = $new_instance[ 'data' ];
	$instance['comments_count'] = $new_instance['comments_count'];
	$instance[ 'comment_content' ] = $new_instance[ 'comment_content' ];
	$instance[ 'taxonomy' ] = $new_instance[ 'taxonomy' ];
    return $instance;
}


// ------------------------------
// Back End part     -
// ------------------------------	

    function form($instance){
		$defaults = array( 'title' => __('Additional posts','larestaurante'),
						   'avatar' => 'off',
						   'author' => 'off',
						   'data' => 'off',
						   'comments_count'=> 3,
						   'comment_content' => 'off',
						   'entries_count' => 3,
						   'entry_type' => 'post',
						   'taxonomy'=> 'category'
						   );
		$instance = wp_parse_args( ( array ) $instance, $defaults );  ?>
         
       
        <label id="<?php echo $this->get_field_id( 'title' ); ?>"  for="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php echo esc_html__('Title','larestaurante');?></label>
        <input id="<?php echo $this->get_field_id( 'title' ); ?>" class = "widefat" 
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" 
		value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" />
    
        <br/>
       
       <label for="<?php echo $this->get_field_id('entry_type') ?>">
            <?php echo esc_html__('Post type: ','larestaurante');?>
            <select name="<?php echo $this->get_field_name('entry_type') ?>"
                id="<?php echo $this->get_field_id('entry_type') ?>" class = "widefat">
                <?php
                
                    $entries_types_list = get_post_types(NULL, 'object');
                    $exclude = array('attachment', 'revision', 'nav_menu_item');
                    
                    $curr_type = $instance['entry_type'];
                    
                    foreach($entries_types_list as $type){
                        $name = $type->name;
                        if(!in_array($name, $exclude)){
                            $label = $type->labels->name;
                            if($curr_type == $name){
                                echo '<option selected="selected" value="'.$name.'">'.$label.'</selected>';
                            }else{
                                echo '<option value="'.$name.'">'.$label.'</selected>';
                            }
                        }
					}
                ?>
            </select>
        </label>
        <br/>
        <label for="<?php echo $this->get_field_id('entries_count') ?>">
            <?php echo esc_html__('Post type count','larestaurante');?>
            <select 
                name="<?php echo $this->get_field_name('entries_count') ?>"
                id="<?php echo $this->get_field_id('entries_count') ?>" class = "widefat">
                <?php
                    $opts = array(1, 2, 3);
                    $curr = (int)esc_attr($instance['entries_count']);
                    foreach($opts as $val){
                        if($curr == $val){
                            echo '<option selected="selected" value="'.$val.'">'.$val.'</selected>';
                        }else{
                            echo '<option value="'.$val.'">'.$val.'</selected>';
                        }
                    } ?>
            </select>
        </label>
		
		</br>
        <label for="<?php echo $this->get_field_id('taxonomy') ?>">
           <?php echo esc_html__('Taxonomy :','larestaurante');?> 
            <select 
                name="<?php echo $this->get_field_name('taxonomy') ?>"
                id="<?php echo $this->get_field_id('taxonomy') ?>" class = "widefat">
                <?php
                
                    $taxonomies_list = get_taxonomies(NULL, 'object');
                    $exclude = array('', 'type');
                    
                    $curr_taxonomy = $instance['taxonomy'];
                    
                    foreach($taxonomies_list as $taxonomy){
                        $name = $taxonomy->name;
                        if(!in_array($name, $exclude)){
                            $label = $taxonomy->labels->name;
                            if($curr_taxonomy == $name){
                                echo '<option selected="selected" value="'.$name.'">'.$label.'</selected>';
                            }else{
                                echo '<option value="'.$name.'">'.$label.'</selected>';
                            }
                        }
                    }
                ?>
            </select>
        </label>
		
		 <!-- The checkbox echo esc_html__ -->
		 <h4><?php echo esc_html__('Comments meta','larestaurante');?></h4>
    <p>
        <input  type="checkbox" <?php checked( $instance[ 'avatar' ], 'on' ); ?>  
		id="<?php echo $this->get_field_id( 'avatar' ); ?>" class = "widefat" 
		name="<?php echo $this->get_field_name( 'avatar' ); ?>" /> 
        <label for="<?php echo $this->get_field_id( 'avatar' ); ?>">
		<?php echo esc_html__('Show avatar', 'larestaurante');?></label>
    </p>
	 <p>
        <input  type="checkbox" <?php checked( $instance[ 'author' ], 'on' ); ?> 
		id="<?php echo $this->get_field_id( 'author' ); ?>" class = "widefat" 
		name="<?php echo $this->get_field_name( 'author' ); ?>" /> 
        <label for="<?php echo $this->get_field_id( 'author' ); ?>">
		<?php echo esc_html__('Show author', 'larestaurante');?></label>
    </p>	
	 <p>
        <input  type="checkbox" <?php checked( $instance[ 'data' ], 'on' ); ?>
		id="<?php echo $this->get_field_id( 'data' ); ?>" class = "widefat" 
		name="<?php echo $this->get_field_name( 'data' ); ?>" /> 
        <label for="<?php echo $this->get_field_id( 'data' ); ?>">
		<?php echo esc_html__('Show data', 'larestaurante');?></label>
    </p>	
	 <p>
        <input  type="checkbox" <?php checked( $instance[ 'comment_content' ], 'on' ); ?> 
		id="<?php echo $this->get_field_id( 'comment_content' ); ?>" class = "widefat" 
		name="<?php echo $this->get_field_name( 'comment_content' ); ?>" /> 
        <label for="<?php echo $this->get_field_id( 'comment_content' ); ?>">
		<?php echo esc_html__('Show comment content', 'larestaurante');?></label>
    </p>	
<?php 	
    }
}
//register custom widget
function larestaurante_widget_init(){register_widget('larestaurante_widget');}

add_action('widgets_init', 'larestaurante_widget_init');
?>