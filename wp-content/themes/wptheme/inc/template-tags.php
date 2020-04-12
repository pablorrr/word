<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package LaRestaurante
 */

if ( ! function_exists( 'larestaurante_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Function is invoking in content files located in Template Parts folder
 */
function larestaurante_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'larestaurante' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'larestaurante' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span> | <span class="byline"> ' . $byline . '</span>'; 
	// WPCS: XSS OK.

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo ' | <span class="comments-link"><i class="fa fa-comments" aria-hidden="true"></i> ';
        /* translators: %s: post title */
        comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'larestaurante' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
        echo '</span>';
    }

}
endif;

if ( ! function_exists( 'larestaurante_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function larestaurante_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'larestaurante' ) );
		if ( $categories_list && larestaurante_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'larestaurante' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'larestaurante' ) );
		if ( $tags_list ) {
			printf( ' | <span class="tags-links">' . esc_html__( 'Tagged %1$s', 'larestaurante' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}


	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'larestaurante' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		' | <span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function larestaurante_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'larestaurante_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'larestaurante_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so wp_bootstrap_starter_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so wp_bootstrap_starter_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in larestaurante_categorized_blog.
 */
function larestaurante_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'larestaurante_categories' );
}
add_action( 'edit_category', 'larestaurante_category_transient_flusher' );
add_action( 'save_post',     'larestaurante_category_transient_flusher' );



if ( ! function_exists( 'larestaurante_comments_feed_template_callback' ) ) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     */
    /* comments form callback function */
	
function larestaurante_comments_feed_template_callback($comment, $args, $depth) {

  $GLOBAL['comment'] = $comment;

  ?> <br>
    <div class="row media">
      <a href="<?php get_comment_author_url(); ?>" class="pull-left"></a>
      <div class="col-md-12 media-body">
        <h5 class="media-heading">
          <a href="<?php get_comment_author_url(); ?>"><?php echo get_comment_author(); ?></a>
          <small><?php comment_date(); ?> at <?php comment_time(); ?></small>
        </h5>
		<br>
        <?php comment_text(); ?>
        <?php comment_reply_link(array_merge($args, array(
		  'reply_text' => __('<strong class="btn-lg fr-end-butt">Answer </strong><i class="icon-share-alt"></i>','larestaurante'),
          'depth' => $depth,
          'max_depth' => $args['max_depth']
        ))); ?>
      </div>
    </div>
    <hr>
  <?php
}

endif; 

/* for archive searching */


function larestaurante_getQueryParams(){
	
        global $query_string;
		$parts = explode('&', $query_string);
		$return = array();
		
        foreach($parts as $part){ 
		
            $tmp = explode('=', $part);
			
            $return[$tmp[0]] = trim(urldecode($tmp[1]));
			
        }
        
        return $return;
    }  


function larestaurante_getQuerySingleParam($name){
        $qparams = larestaurante_getQueryParams();
        
        if(isset($qparams[$name])){
            return $qparams[$name];
        }
        
        return NULL;
    }

function larestaurante_getCurrentPageUrl(){
	
     $pageURL = 'http';
     if(isset($_SERVER["HTTPS"])){
            if($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
        
	$pageURL .= "://";
        
	if($_SERVER["SERVER_PORT"] != "80"){
	
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			
			}else{
				
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
    }

/*add_filter('posts_where', 'larestaurante_title_like_posts_where', 10, 2);

    function larestaurante_title_like_posts_where( $where, &$wp_query ) {
	
        global $wpdb;
        
        if ($post_title_like = $wp_query->get('post_title_like')){
			
			$where .= ' AND '.$wpdb->posts.'.post_title LIKE \'%'.esc_sql(wpdb::esc_like($post_title_like)).'%\''; 
        }
        
        return $where;
    }
*/
	/* breadcrumbs */
	
	function larestaurante_the_post_breadcrumb() {
		global $post;
		echo '<ol class="breadcrumb">';
		echo '<li>';
		echo '<a href="'.home_url().'">'.__('main','larestaurante').'</a>';
		echo '</li>';
		
		$post_type_name = get_post_type();
		
		$taxonomy_names = get_object_taxonomies( $post );
		$current_post_tax_name = $taxonomy_names;
		
		$post_type_url = get_post_type_archive_link(get_post_type());
		echo '<li>';
		echo '<a href="'.$post_type_url.'">'.ucfirst($post_type_name).'</a>';
		echo '</li>';
		$taxname = get_the_terms($post->ID, $current_post_tax_name);


		
		if(!empty($taxname)) {
			$displaytaxname = array_shift(array_values($taxname));
			
			$taxonomy_link = get_term_link($displaytaxname->slug,$current_post_tax_name[0]);
			
			echo '<li>';
			echo '<a href="'.$taxonomy_link.'">'.ucfirst($displaytaxname->name).'</a>';
			echo '</li>';
			
		}	
		echo '<li>';
		the_title();
		echo '</li>';
		echo '</ol>';
	} 

 /*  end breadcrumbs */




function larestaurante_the_excerpt_max_charlength($charlength) {
		echo larestaurante_cutText(get_the_excerpt(), $charlength);
	}


    function larestaurante_cutText($text, $maxLength){
        
        $maxLength++;

        $return = '';
        if (mb_strlen($text) > $maxLength) {
		
			$subex = mb_substr($text, 0, $maxLength - 5);
		
            $exwords = explode(' ', $subex);
            $excut = - ( mb_strlen($exwords[count($exwords) - 1]) );
			
            if ($excut < 0) {
                $return = mb_substr($subex, 0, $excut);
            } 
			else {
                $return = $subex;
            }
            $return .= '[...]';
        } 
		
		else {
            $return = $text;
        }
        
        return $return;
    }

/* Function is used in Opinion Segment */
function larestaurante_fetchRecentComments($limit = 3) {

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

/* Modify link class into Bootstrap classs */
add_filter('comment_reply_link', 'larestaurante_add_reply_link_class');

function larestaurante_add_reply_link_class($class) {
  $class = str_replace("class='comment-reply-link", "class='btn btn-default pull-right", $class);
  return $class;
}

add_filter( 'preprocess_comment', 'larestaurante_comment_limitation' );

/* limit comments char */
function larestaurante_comment_limitation($comment) {
    if ( strlen( $comment['comment_content'] ) > 800 ) {
        wp_die('Comment is too long. Please keep your comment under 250 characters.');
    }
if ( strlen( $comment['comment_content'] ) < 60 ) {
        wp_die('Comment is too short. Please use at least 60 characters.');
    }
    return $comment;
}

/* password protection for protected posts */
function larestaurante_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    <div class="d-block mb-3">' . __( "To view this protected post, enter the password below:", "larestaurante" ) . '</div>
    <div class="form-group form-inline"><label for="' . $label . '" class="mr-2">' . __( "Password:", "larestaurante" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" class="form-control mr-2" /> <input type="submit" name="Submit" value="' . esc_attr__( "Submit", "larestaurante" ) . '" class="btn btn-primary"/></div>
    </form>';
    return $o;
}
add_filter( 'the_password_form', 'larestaurante_password_form' );

/* https://codex.wordpress.org/Dashboard_Widgets_API */

function larestaurante_dashboard_widget() {
 	wp_add_dashboard_widget(
							'larestaurante_dashboard_widget',// Widget slug.
							'Larestaurante Dashboard Widget',// Title.
							'larestaurante_dashboard_widget_function'// Display function.
							);
 	
 	
 	global $wp_meta_boxes;
 	
 	// Get the regular dashboard widgets array 
 	// (which has our new widget already but at the end)
 
 	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
 	
 	// Backup and delete our new dashboard widget from the end of the array
	
 
 	$culinary_widget_backup = array( 'larestaurante_dashboard_widget' => $normal_dashboard['larestaurante_dashboard_widget'] );
 	unset( $normal_dashboard['culinary_dashboard_widget'] );
 
 	// Merge the two arrays together so our widget is at the beginning
	
 
 	$sorted_dashboard = array_merge( $culinary_widget_backup, $normal_dashboard );
 
 	// Save the sorted array back into the original metaboxes 
 
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	
	
	
} 
add_action( 'wp_dashboard_setup', 'larestaurante_dashboard_widget' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function larestaurante_dashboard_widget_function() {

	
	echo "Welcome Restaurant Culinary Theme , enjoy it!!! <br>
			<br>
			In this theme you can:<br>
			<br>
			- turn on and off the segments you selected, the front end of the theme<br>
			- choose pictures, for example for a gallery<br>
			- set the font<br>
			- send emails and configure smtp mail<br>
			- save table reservations for clients<br>
			- set up links for social media<br>
			- edit css<br>
			
			<br>
			and set some additional options.<br>
			<br>
		The theme has ajax support at archive pagination, customized custom posts and post formats.<br>"; 
}

/* add font styles , Google Fonts Supporting*/
if (! function_exists('larestaurante_add_font_styles')){
	
 function larestaurante_add_font_styles(){

		
	}
	add_action ('wp_head','larestaurante_add_font_styles'); 
}

/* Add to body tag layout class */



/* Redirect to Home Page after logout */
add_action( 'wp_logout', 'larestaurante_auto_redirect_external_after_logout');

function larestaurante_auto_redirect_external_after_logout(){
		wp_redirect( home_url() );
		exit();
		}
		
		
/* Add to menu class to scroll page with menu */
function larestaurante_add_specific_menu_location_atts( $atts, $item, $args ) {
    // check if the item is in the primary menu
    if( $args->theme_location == 'primary' ) {
      // add the desired attributes:
      $atts['class'] = 'nav-link js-scroll-trigger';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'larestaurante_add_specific_menu_location_atts', 10, 3 );	

/* svg support */
/* source :https://codex.wordpress.org/Plugin_API/Filter_Reference/upload_mimes */
function my_custom_mime_types( $mimes ) {
	
        // New allowed mime types.
        $mimes['svg'] = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
        $mimes['doc'] = 'application/msword'; 

        // Optional. Remove a mime type.
       // unset( $mimes['exe'] );

	return $mimes;
}
add_filter( 'upload_mimes', 'my_custom_mime_types' );