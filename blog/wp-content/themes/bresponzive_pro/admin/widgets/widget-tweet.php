<?php
/*****************************************************************
Plugin Name: Latest Tweets Widget	
Description: Tweets Widget will display your Latest Tweets in your sidebar.
Author: ThemePacific Team
Author URI: http://themepacific.com
*********************************************************/
/**
 * Add function to widgets_init that'll load our widget.
  */
add_action( 'widgets_init', 'latest_tweet_widget' );
function latest_tweet_widget() {
	register_widget( 'themepacific_latest_tweets' );
}
class themepacific_latest_tweets extends WP_Widget {

	function themepacific_latest_tweets() {
		$widget_ops = array( 'classname' => 'tpcrn_tweets'  );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'latest-tweets-widget' );
		$this->WP_Widget( 'latest-tweets-widget','Themepaicifc: Latest Tweets', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
	
	global $data;
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$twit_id = $instance['twit_id'];
		$my_tweets_show = $instance['my_tweets_show'];
		  
		$twitter_username 		= $twit_id;
		$tw_transientname 		= 'tpcrn_tweets'.$twitter_username;	
		$consumer_key 			=  $data['twit_c_key'];
		$consumer_secret		=  $data['twit_c_secret'];
		$access_token 			=  $data['twit_a_token'];
		$access_token_secret 	=  $data['twit_a_token_secret'];
		$cacheTime = 20;
		
	if( !empty($twitter_username) && !empty($consumer_key) && !empty($consumer_secret) && !empty($access_token) && !empty($access_token_secret)  ){
		$twitterbits = get_transient($tw_transientname);
	    if($twitterbits == false){
		
			require_once(TEMPLATEPATH . '/includes/twitteroauth/twitteroauth.php');

			$twitterConnection = new TwitterOAuth( $consumer_key , $consumer_secret , $access_token , $access_token_secret	);
			$twitterbits = $twitterConnection->get(
					  'statuses/user_timeline',
					  array(
					    'screen_name'     => $twitter_username ,
					    'count'           => $my_tweets_show
						)
					);
			if($twitterConnection->http_code != 200)
			{
				$twitterbits = get_transient($tw_transientname);
			}
	        // Cache the Twitter content.
	        set_transient($tw_transientname, $twitterbits, 60 * $cacheTime);
	    }
		/* Before widget (defined by themes). */
		echo $before_widget;
	
	
			echo $before_title; ?>
			<a href="http://twitter.com/<?php echo $twitter_username  ?>"><?php echo $title ; ?></a>
		<?php echo $after_title; 

            	if( ( !empty($twitterbits) && is_array($twitterbits) ) || !isset($twitterbits['error'])){
             		$count=0;
					$tpcrn_convert_hyperlinks = true;
					$encode_utf8 = true;
					$twitter_users = true;
					$update = true;
					echo '
<div id="twitter-widget" >
<ul class="twitter_update_list">';
		            foreach($twitterbits as $item){
		                    $msg = $item->text;
		                    $permalink = 'http://twitter.com/#!/'. $twitter_username .'/status/'. $item->id_str;
		                    if($encode_utf8) $msg = utf8_encode($msg);
		                    $link = $permalink;
		                     echo '<li class="twitter-item">';
		                      if ($tpcrn_convert_hyperlinks) {    $msg = $this->tpcrn_convert_hyperlinks($msg); }
		                      if ($twitter_users)  { $msg = $this->twitter_users($msg); }
		                      echo $msg;
		                    if($update) {
		                      $time = strtotime($item->created_at);
		                      if ( ( abs( time() - $time) ) < 86400 )
		                        $h_time = sprintf( __('%s ago'), human_time_diff( $time ) );
		                      else
		                        $h_time = date(__('Y/m/d'), $time);
		                      echo sprintf( __('%s', 'twitter-for-wordpress'),' <span class="twitter-timestamp"><abbr title="' . date(__('Y/m/d H:i:s'), $time) . '">' . $h_time . '</abbr></span>' );
		                     }
		                    echo '</li>';
		                    $count++;
		                    if ( $count >= $my_tweets_show ) break;
		            }
					echo '</ul> </div>';
            	}
            ?>
		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	else{
		echo $before_widget;
		echo $before_title; ?>
			<a href="http://twitter.com/<?php echo $twitter_username  ?>"><?php echo $title ; ?></a>
		<?php echo $after_title; 
		echo ' You need to Setup Twitter API OAuth settings under Theme Options';
		echo $after_widget;
	}
}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twit_id'] = $new_instance['twit_id'];
		$instance['my_tweets_show'] = strip_tags( $new_instance['my_tweets_show'] );
 		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' =>__('@Follow Me' , 'themepacific') ,'twit_id' => '',  'my_tweets_show' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p><em style="color:red;">Make sure you Setup Twitter API OAuth settings in the ThemePacfic Panel (Social Settings)</em></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
 			<label for="<?php echo $this->get_field_id('twit_id'); ?>">Entet Twitter Username:</label>

			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('twit_id'); ?>" name="<?php echo $this->get_field_name('twit_id'); ?>" value="<?php echo $instance['twit_id']; ?>" />
 		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'my_tweets_show' ); ?>">Tweets to show : </label>
			<input id="<?php echo $this->get_field_id( 'my_tweets_show' ); ?>" name="<?php echo $this->get_field_name( 'my_tweets_show' ); ?>" value="<?php echo $instance['my_tweets_show']; ?>" type="text" size="3" />
		</p>
	<?php
	}
	
		/**
	 * Find links and create the tpcrn_convert_hyperlinks
	 */
	private function tpcrn_convert_hyperlinks($text) {
	    $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);
	    // match name@address
	    $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
	        //mach #trendingtopics. Props to Michael Voigt
	    $text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);
	    return $text;
	}
	/**
	 * Find twitter usernames and link to them
	 */
	private function twitter_users($text) {
	       $text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
	       return $text;
	}
	
}
?>