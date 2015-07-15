<?php

$includes_path = TEMPLATEPATH . '/includes/';
require_once(TEMPLATEPATH . '/dashboard.php'); 


if ( function_exists('register_sidebars') )
    register_sidebars(6);
?>
<?php
function widget_mytheme_search() {
?>
<h2>Search</h2>
<form id="searchform" method="get" action="<?php bloginfo('home'); ?>/"> <input type="text" value="Search: type and hit enter!" onfocus="if (this.value == 'Search: type and hit enter!') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search: type and hit enter!';}" size="18" maxlength="50" name="s" id="s" /> </form> 
<?php
}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Search'), 'widget_mytheme_search');
?>
<?php
add_filter('comments_template', 'legacy_comments');
function legacy_comments($file) {
	if(!function_exists('wp_list_comments')) : // WP 2.7-only check
		$file = TEMPLATEPATH . '/legacy.comments.php';
	endif;
	return $file;
}

# Displays a list of popular posts
function gtt_popular_posts($num, $pre='<li>', $suf='</li>', $excerpt=true) {
	global $wpdb;
	$querystr = "SELECT $wpdb->posts.post_title, $wpdb->posts.ID, $wpdb->posts.post_content FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'post' ORDER BY $wpdb->posts.comment_count DESC LIMIT $num";
	$myposts = $wpdb->get_results($querystr, OBJECT);
	foreach($myposts as $post) {
		echo $pre;
		?>
        <a href="<?php the_permalink(); ?>"><?php dp_attachment_image($post->ID, 'small', 'alt="' . $post->post_title . '"'); ?></a>
        
        <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title ?></a><?php
		if ($excerpt) {
			?><?php
		}
		echo $suf;
	}
}

# Displays post image attachment (sizes: thumbnail, medium, full)
function dp_attachment_image($postid=0, $size='thumbnail', $attributes='') {
	if ($postid<1) $postid = get_the_ID();
	if ($images = get_children(array(
		'post_parent' => $postid,
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
		foreach($images as $image) {
			$attachment=wp_get_attachment_image_src($image->ID, $size);
			?><img src="<?php echo $attachment[0]; ?>" <?php echo $attributes; ?> /><?php
		}
}

# Removes tags and trailing dots from excerpt
function dp_clean($excerpt, $substr=0) {
	$string = strip_tags(str_replace('[...]', '...', $excerpt));
	if ($substr>0) {
		$string = substr($string, 0, $substr);
	}
	return $string;
}

/* 
Plugin Name: Recent Comments 
Plugin URI: http://mtdewvirus.com/code/wordpress-plugins/ 
*/ 

function dp_recent_comments($no_comments = 10, $comment_len = 60) { 
    global $wpdb; 
	
	$request = "SELECT * FROM $wpdb->comments";
	$request .= " JOIN $wpdb->posts ON ID = comment_post_ID";
	$request .= " WHERE comment_approved = '1' AND post_status = 'publish' AND post_password ='' AND comment_type = ''"; 
	$request .= " ORDER BY comment_date DESC LIMIT $no_comments"; 
		
	$comments = $wpdb->get_results($request);
		
	if ($comments) { 
		foreach ($comments as $comment) { 
			ob_start();
			?>
				<li>
					<div class="tab-comments-avatar"><?php echo get_avatar($comment,$size='40' ); ?></div>
					<div class="tab-comments-text">
						<a href="<?php echo get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID; ?>"><?php echo dp_get_author($comment); ?>:</a>
						<?php echo strip_tags(substr(apply_filters('get_comment_text', $comment->comment_content), 0, $comment_len)); ?>...
					</div>
				</li>
			<?php
			ob_end_flush();
		} 
	} else { 
		echo "<li>No comments</li>";
	}
}

function dp_get_author($comment) {
	$author = "";

	if ( empty($comment->comment_author) )
		$author = __('Anonymous');
	else
		$author = $comment->comment_author;
		
	return $author;
}



// Load Javascript in wp_head
require_once ($includes_path . 'theme-js.php');
?>