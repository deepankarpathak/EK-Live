<?php
/*-----------------------------------------------------------------------------------
 TPCRN Custom Styles
-----------------------------------------------------------------------------------*/
function trendify_themepacific_wp_head() {
global $data;
?>
<style type="text/css" media="screen"> 
 <?php if(  !empty($data['body_background_color']) || !empty($data['custom_bg']) ) {?>

body { 
<?php if(!empty($data['body_background_color'])) {?>background-color:<?php echo $data['body_background_color']; }?>;
<?php if(!empty($data['custom_bg']) && $data['custom_full_bg_img'] == 'no')  {?>background-repeat: repeat;background-image: url('<?php if(!empty($data['custom_bg_upload'])){echo $data['custom_bg_upload'];}   else echo $data['custom_bg'];?>');
<?php }?>
}
<?php if(!empty($data['custom_logo_margin_top'])) {?>
#logo{margin-top:<?php echo $data['custom_logo_margin_top'];?>}
<?php }?>
<?php } ?>
<?php
global $tpcrn_typo_options;	
foreach( $tpcrn_typo_options as $name => $value){
if($value['face'] == 'default'){
unset($value['face']);
}
 if( !empty($value['color']) || !empty($value['face']) || !empty($value['style']) || !empty($value['size']) ){
	echo $name.'{ '; 
  if( !empty($value['color']))echo "color: ". $value['color'].";";
  if( !empty($value['face']))echo "font-family: ". $value['face'].";";
  if( !empty($value['style']))echo "font-weight: ". $value['style'].";";
  if( !empty($value['size']))echo "font-size: ". $value['size'].";";
 echo '}';echo "\n";
  }
} /*for**/

?>

<?php if(!empty($data['custom_css'])) echo $data['custom_css'];?>
 
</style>
<?php 
if(!empty($data['custom_bg_upload']) && $data['custom_full_bg_img'] == 'yes'){?>
<script type="text/javascript">
				 jQuery(document).ready(function(){
					jQuery.backstretch("<?php echo $data['custom_bg_upload'];?>");
				   });
				</script>
<?php } 
}
 $tpcrn_typo_options = array(
	"body" 		    =>		$data['body_font'],
	"#catnav ul a"  =>		$data['headings_topmenu_font'],
	"#catnav.secondary ul a"  =>		$data['headings_catmenu_font'],
	"h2.blogpost-wrapper-title"   =>		$data['blog_mag_title_font'],
	".blog-lists-blog h3 a"  =>		$data['blog_post_title_font'],
	" .blog-lists h3 a"    =>		$data['mag_post_title_font'],
	".widget-head, ul.tabs2 li a"         =>		$data['sb_widget_title_font'],
	"h3.widget-title"      =>		$data['fb_widget_title_font'],
	".post-title h1"       =>		$data['post_title_font'],
	"#post-meta ,.post-meta-blog"  =>		$data['post_meta_font'],
	".post_content"  			   =>		$data['post_content_font'],
 	".single-navigation, .comment-nav-above, .comment-nav-below"  =>$data['post_navigation_font'],
	".comment-content"  =>		$data['post_comment_font'],
	".post_content h1"  =>		$data['post_h1_font'],
	".post_content h2"  =>		$data['post_h2_font'],
	".post_content h3"  =>		$data['post_h3_font'],
	".post_content h4"  =>		$data['post_h4_font'],
	".post_content h5"  =>		$data['post_h5_font'],
	".post_content h6"  =>		$data['post_h6_font'],
 
); 
 
 
 
/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function bresponZive_themepacific_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'tpcrn' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'bresponZive_themepacific_wp_title', 10, 2 ); 


 function bresponZive_wp_login_form( $login_only  = 0 ) {
	global $user_ID, $user_identity, $user_level;
	
	if ( $user_ID ) : ?>
		<?php if( empty( $login_only ) ): ?>
		<div id="tpcrn-user-in">
			<p class="tpcrn-user-greet"><?php _e( 'Howdy!' , 'bresponZive' ) ?> <strong><?php echo $user_identity ?></strong></p>
			<div class="tpcrn-author-avatar"><?php echo get_avatar( $user_ID, $size = '90'); ?></div>
			<ul>
				<li><a href="<?php echo home_url() ?>/wp-admin/"><?php _e( 'Dashboard' , 'bresponZive' ) ?> </a></li>
				<li><a href="<?php echo home_url() ?>/wp-admin/profile.php"><?php _e( 'Your Profile' , 'bresponZive' ) ?> </a></li>
				<li><a href="<?php echo wp_logout_url(); ?>"><?php _e( 'Logout' , 'bresponZive' ) ?> </a></li>
			</ul>
 			<div class="clear"></div>
		</div>
		<?php endif; ?>
	<?php else: ?>
		<div id="themepacific-login-form">
			<form action="<?php echo home_url() ?>/wp-login.php" method="post">
				<p id="tpcrn-username"><input size="40" type="text" name="my_username" value="<?php _e( 'Username' , 'bresponZive' ) ?>" onfocus="if (this.value == '<?php _e( 'Username' , 'bresponZive' ) ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Username' , 'bresponZive' ) ?>';}"   /></p>
				<p id="tpcrn-pw"><input size="40" type="password" name="my_password" value="<?php _e( 'Password' , 'bresponZive' ) ?>" onfocus="if (this.value == '<?php _e( 'Password' , 'bresponZive' ) ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Password' , 'bresponZive' ) ?>';}"  /></p>
				<input type="submit" name="submit" value="<?php _e( 'Log in' , 'bresponZive' ) ?>" class="nletterbutton" />
				<label><input name="remembercheck" type="checkbox" checked="checked" value="forever" /> <?php _e( 'Remember Me' , 'bresponZive' ) ?></label>
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
			</form>
			<ul class="tpcrn-forget-pw">
				<?php if ( get_option('users_can_register') ) : ?> <li><a href="<?php echo home_url() ?>/wp-register.php"><?php _e( 'Register' , 'bresponZive' ) ?></a></li><?php endif; ?>
				<li><a href="<?php echo home_url() ?>/wp-login.php?action=lostpassword"><?php _e( 'Lost your password?' , 'bresponZive' ) ?></a></li>
			</ul>
		</div>
	<?php endif;
}

function bresponZive_twitter_follow_count($twitter_id) {
	global $data;
	require_once(TEMPLATEPATH . '/includes/twitteroauth/twitteroauth.php');
 		$twitter_username 		= $twitter_id;
		$consumer_key 			=  $data['twit_c_key'];
		$consumer_secret		=  $data['twit_c_secret'];
		$access_token 			=  $data['twit_a_token'];
		$access_token_secret 	=  $data['twit_a_token_secret'];
		
		$twitterConnection = new TwitterOAuth( $consumer_key , $consumer_secret , $access_token , $access_token_secret	);
		$twitterData = $twitterConnection->get('users/show', array('screen_name' => $twitter_username));
 		$twitter['followers_count'] = $twitterData->followers_count;
		
 	if( get_option( 'followers_count') < $twitter['followers_count'] )
		update_option( 'followers_count' , $twitter['followers_count'] );
		
	if( $twitter['followers_count'] == 0 && get_option( 'followers_count') )
		$twitter['followers_count'] = get_option( 'followers_count');
			
	elseif( $twitter['followers_count'] == 0 && !get_option( 'followers_count') )
		$twitter['followers_count'] = 0;
	
	return $twitter;
}
 
function get_data_fb($json_url,$id){
   $sharestransient = 'followers_tpcrn_url_fb'.$id;
   $cachedresult =  get_transient($sharestransient);
 if ($cachedresult !== false ) {
	return $cachedresult;
	
	} else {
    
       $json_data = file_get_contents($json_url);
        $json = json_decode($json_data);
		$result = $json->likes;	
	
       set_transient($sharestransient, $result, 60*60*4);
		update_option($sharestransient, $result);		
         return $result;  
 	}
 }
 /*-- Breadcrumbs--*/
 function bresponZive_themepacific_breadcrumb() {
	if (!is_home()) {
	
		echo '<ul id="tpcrn-breadcrumbs"><li><a href="'.home_url().'">Home &raquo;</a> </li>';
		if (is_category() || is_single()) {
			 
$category = get_the_category(); 
$brecat_title = $category[0]-> cat_ID;
$category_link = get_category_link($brecat_title);
echo '<li><a class="vca" href="'. esc_url( $category_link ) . '">' . $category[0]->cat_name . ' &raquo;</a></li>';
 	 
			if (is_single()) {
				echo '<li class="current">';
				the_title();
				echo '</li>';
			}
		} elseif (is_page()) {
			echo '<li class="current">';
				the_title();
				echo '</li>';
		}
	echo '</ul>'; 
	}
}
 
/*-- Pagination --*/

function bresponZive_themepacific_tpcrn_pagination() {
	
		global $wp_query;
		$big = 999999999;
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'format' => '?paged=%#%',
			'prev_next'    => false,
			'prev_text'    => __('<i class="icon-double-angle-left"></i>'),
	        'next_text'    => __('<i class="icon-double-angle-right"></i>'),
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages )
		);
	}
 
  /*-- Custom Excerpts--*/
 
function bresponZive_themepacific_custom_excerpt_length( $length ) {
	return 15;
}
add_filter( 'excerpt_length', 'bresponZive_themepacific_custom_excerpt_length', 999 );
function bresponZive_themepacific_new_excerpt_more( $more ) {
	return '..';
}
add_filter('excerpt_more', 'bresponZive_themepacific_new_excerpt_more');
 
if (!isset( $content_width )) $content_width = 680;
 function bresponZive_themepacific_themepacific_breadcrumb() {
	if (!is_home()) {
	
		echo '<ul id="tpcrn-breadcrumbs"><li><a href="'.home_url().'">Home &raquo;</a> </li>';
		if (is_category() || is_single()) {
			 
$category = get_the_category(); 
$brecat_title = $category[0]-> cat_ID;
$category_link = get_category_link($brecat_title);
echo '<li><a class="vca" href="'. esc_url( $category_link ) . '">' . $category[0]->cat_name . ' &raquo;</a></li>';
 	 
			if (is_single()) {
				echo '<li class="current">';
				the_title();
				echo '</li>';
			}
		} elseif (is_page()) {
			echo '<li class="current">';
				the_title();
				echo '</li>';
		}
	echo '</ul>'; 
	}
}

/*-- Multiple Page Nav--*/		

function bresponZive_themepacific_single_split_page_links($defaults) {
	$args = array(
	'before' => '<div class="single-split-page"><p>' . __('<strong>Pages</strong>','bresponZive'),
	'after' => '</p></div>',
	'pagelink' => '%',
	);
	$r = wp_parse_args($args, $defaults);
	return $r;
	}
 
/*===================================================================================*/
/*  Comments
/*==================================================================================*/

function bresponZive_themepacific_themepacific_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'bresponZive' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'bresponZive' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li id="comment-<?php comment_ID(); ?>">
		<div <?php comment_class('comment-wrapper'); ?> >
 				<div class="comment-avatar">
					<?php
						$avatar_size = 65;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 65;

						echo get_avatar( $comment, $avatar_size );?>
				</div>
				<!--comment avatar-->
				<div class="comment-meta">
					<?php	
						printf( __( '%1$s  %2$s  ', 'bresponZive' ),
							sprintf( '<div class="author">%s</div>', get_comment_author_link() ),
							sprintf( '%4$s<a href="%1$s"><span class="time" style="border:none;">%3$s</span></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),get_comment_date(),								
								sprintf( __( '<span class="time">%1$s </span>', 'bresponZive' ),   get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'bresponZive' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- /comment-meta -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'bresponZive' ); ?></em>
					<br />
				<?php endif; ?>
 			<div class="comment-content">
				<?php comment_text(); ?>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( ' <span><i class="icon-reply"></i></span> Reply', 'bresponZive' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div> <!--/reply -->
			</div><!--/comment-content -->	
		</div>	<!--/Comment-wrapper -->
 			 
 	<?php
			break;
	endswitch;
}?>