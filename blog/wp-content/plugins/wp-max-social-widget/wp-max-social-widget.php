<?php
/*
====================================================
---------------WP-Max-Social-Widget-----------------
====================================================

Plugin Name: WP Max Social Wigdet

Plugin URI: http://www.designaeon.com/wp-max-social-widget

Description: WP Max Social Wigdet : Wordpress Sidebar widget To combine your social media profiles and bookmarks.

Version: 1.3.2

Author: Ramandeep Singh

Author URI: http://www.designaeon.com/

License: GPLv2
*/

class maxsocial extends WP_Widget{
	function maxsocial()
	{
		$widget_ops = array('classname' => 'maxsocial', 'description' => __(' Wordpress Sidebar widget To combine your social media profiles and bookmarks ,Created By Ramandeep Singh | Designaeon.com'));
		$control_ops = array('width' => 700, 'height' => 350);

		$this->WP_Widget('maxsocial', __('WP Max Social Wigdet'), $widget_ops, $control_ops);
	}
	//insert sytlesheet
	function addcss()
	{
		$siteurl = get_option('siteurl');
		$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/wp-max-social-style.css';
		echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
	}
	function widget( $args, $instance )
	{
		extract($args);		
		$smtitle=$instance['smtitle'];	
		$feedbr_id = $instance['feedbr_id'];
		$twitr_id = $instance['twitr_id'];
		$twitr1_id = $instance['twitr1_id'];
		/*------Twitter Settings-------*/
		$twitruname_id = $instance['twitruname_id'];
		$twitrcount_id = $instance['twitrcount_id'];
		/*------Twitter Settings end-------*/
		$fb_id = $instance['fb_id'];
		$fby_id=$instance['fby_id'];
		$pinterest_id = $instance['pinterest_id'];
		$digg_id=$instance['digg_id'];
		$su_id=$instance['su_id'];
		$dl_id=$instance['dl_id'];
		$mspace_id=$instance['mspace_id'];
		$ln_id=$instance['ln_id'];
		$dribbb_id=$instance['dribbb_id'];
		$lstfm_id=$instance['lstfm_id'];
		$rddit_id=$instance['rddit_id'];
		$vmeo_id=$instance['vmeo_id'];
		$gplus_id = $instance['gplus_id'];
		$utube_id=$instance['utube_id'];
		$widgwidth_id = $instance['widgwidth_id'];
		$fbwidth_id = $instance['fbwidth_id'];
		$fbheight_id = $instance['fbheight_id'];
		$fbywidth_id=$instance['fbywidth_id'];
		$fbyheight_id=$instance['fbyheight_id'];
		$recom_id = $instance['recom_id'];
		$ewidth_id = $instance['ewidth_id'];
		$etext_id = $instance['etext_id'];
		$footerurl_id = $instance['footerurl_id'];
		$footertext_id = $instance['footertext_id'];
		/*------Footer Settings-------*/
		$showfoot_id = $instance['showfoot_id'];
		/*------Footer Settings End-------*/
		$fbboxcolor_id = $instance['fbboxcolor_id'];
		$fbboxsubcolor_id = $instance['fbboxsubcolor_id'];
		$gpluscolor_id = $instance['gpluscolor_id'];
		$twtrcolor_id = $instance['twtrcolor_id'];
		$ecolor_id = $instance['ecolor_id'];
		$othercolor_id = $instance['othercolor_id'];
		
	    $google_page_id = $instance['google_page_id'];
		$badge_layout = $instance['badge_layout'];
		$badge_color = $instance['badge_color'];
		?>
		<!--Stat of The Wp-max Social Widget -->
		<!-- http://www.designaeon.com/wp-max-social-widget-->
		<div style="margin:10px 0 10px 0;">
		<div id="wp-max-social" style="width:<?php echo $widgwidth_id; ?>px;">
			<h3 class="wp-max-social-title"><span><?php echo$smtitle; ?></span></h3>
			
		<div id="max-social-all" style="width:<?php echo $widgwidth_id-2; ?>px;">
			<?php if(!empty($google_page_id)): ?>
				<div id="max-social-gplus" class="">
				<?php 	if ( $google_page_id || $badge_layout || $badge_color ): ?>
					<a style="display: block; height: 0;" href ="https://plus.google.com/<?php echo $google_page_id ?>"  rel="publisher"></a>
					<g:plus href="//plus.google.com/<?php echo $google_page_id; ?>" rel="publisher" width="<?php echo $widgwidth_id; ?>"  size="<?php echo $badge_layout; ?>" theme="<?php echo $badge_color; ?>"></g:plus>
					
				<?php endif; ?>
				</div>
			<?php endif; ?>
		
			<?php if(!empty($fb_id)): ?>
			<div id="max-social-fb-like" class="max-social-layout" style="background:<?php echo $fbboxcolor_id; ?>;">
				<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo $fb_id; ?>&amp;send=false&amp;layout=standard&amp;width=<?php echo $fbwidth_id; ?>&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=<?php echo $fbheight_id; ?>&amp;appId=199318853519754" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo $fbwidth_id; ?>px; height:<?php echo $fbheight_id; ?>px;"></iframe>
			</div>
			<?php endif; ?>
			<?php if(!empty($fby_id)): ?>
			<div id="max-social-fb-subscribe" class="max-social-layout" style="background:<?php echo $fbboxsubcolor_id; ?>">
				<iframe src="//www.facebook.com/plugins/subscribe.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $fby_id; ?>&amp;layout=standard&amp;show_faces=false&amp;colorscheme=light&amp;font&amp;width=<?php echo $fbywidth_id; ?>&amp;appId=199318853519754" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo $fbywidth_id; ?>px;height:<?php echo $fbyheight_id; ?>px;"></iframe>
			</div>
			<?php endif; ?>
			<div id="gplus-one" class="max-social-layout" style="background: <?php echo $gpluscolor_id; ?>;">
				 <span><?php echo $recom_id; ?></span><div class="g-plusone" data-size="medium"></div> 
				
			</div>
			<?php if(!empty($twitr_id)): ?>
			<div id="max-social-twitter-follow" class="max-social-layout" style="background: <?php echo $twtrcolor_id; ?>;">
				<iframe title="" style="width: 300px; height: 20px;" class="twitter-follow-button" src="http://platform.twitter.com/widgets/follow_button.html#_=1319978796351&amp;align=&amp;button=blue&amp;id=twitter_tweet_button_0&amp;lang=en&amp;link_color=&amp;screen_name=<?php echo $twitr_id; ?>&amp;show_count=<?php echo $twitrcount_id ? "true" : "false" ; ?>&amp;show_screen_name=<?php echo $twitruname_id ? "true" : "false" ; ?>&amp;text_color=" frameborder="0" scrolling="no"></iframe>
			</div>
			<?php endif; ?>
			<?php if(!empty($twitr1_id)) : ?>
			<div id="max-social-twitter-follow-addon" class="max-social-layout" style="background: <?php echo $twtrcolor_id; ?>;">
				<iframe title="" style="width: 300px; height: 20px;" class="twitter-follow-button" src="http://platform.twitter.com/widgets/follow_button.html#_=1319978796351&amp;align=&amp;button=blue&amp;id=twitter_tweet_button_0&amp;lang=en&amp;link_color=&amp;screen_name=<?php echo $twitr1_id; ?>&amp;show_count=<?php echo $twitrcount_id ? "true" : "false" ; ?>&amp;show_screen_name=<?php echo $twitruname_id ? "true" : "false" ; ?>&amp;text_color=" frameborder="0" scrolling="no"></iframe>
			</div>
			<?php endif; ?>
			<div id="max-social-email-subscribe" class="max-social-layout" style="background: <?php echo $ecolor_id; ?>;">
				<div class="max-email-box">
					<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feedbr_id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">	
						<input class="email" type="text" style="width: <?php echo $ewidth_id; ?>px; font-size: 12px;" id="email" name="email" value="Enter Your Email" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>		
						<input type="hidden" value="<?php echo $feedbr_id; ?>" name="uri"/>
						<input type="hidden" name="loc" value="en_US"/>
						<input class="subscribe" name="commit" type="submit" value="Subscribe"/>	
					</form>
				</div>			
			</div>
			<div id="max-social-icons" class="max-social-layout" style="background:<?php echo $othercolor_id; ?>">
				<ul class="max-social-media">
					<?php if(!empty($gplus_id)) : ?>
						<li><span><a class="g-p" title="follow on google plus" target="_blank" href="http://plus.google.com/<?php echo $gplus_id; ?>"> g+ </a></span></li>
					<?php endif ; ?>
					<?php if(!empty($digg_id)) : ?>
						<li><span><a class="dg" title="follow on digg" target="_blank" href="http://www.digg.com/<?php echo $digg_id; ?>"> digg </a></span></li>
					<?php endif; ?>
					<?php if(!empty($su_id)) : ?>
						<li><span><a class="su" title="follow on stumbleupon" target="_blank" href="http://www.stumbleupon.com/stumbler/<?php echo $su_id; ?>"> su </a></span></li>
					<?php endif; ?>
					<?php if(!empty($dl_id)) : ?>
						<li><span><a class="dl" title="follow on delicious" target="_blank" href="http://www.delicious.com/<?php echo $dl_id; ?>"> delicious </a></span></li>
					<?php endif; ?>
					<?php if(!empty($ln_id)) : ?>
						<li><span><a class="ln" title="follow on Linkdin" target="_blank" href="<?php echo $ln_id; ?>"> linkedin </a></span></li>
					<?php endif; ?>
					<?php if(!empty($pinterest_id)) : ?>
						<li><span><a class="pin" title="follow on Pinterest" target="_blank" href="http://pinterest.com/<?php echo $pinterest_id; ?>"> Pinterest </a></span></li>
					<?php endif; ?>
					<?php if(!empty($dribbb_id)) : ?>
						<li><span><a class="dribbb" title="follow on Dribbble" target="_blank" href="http://dribbble.com/<?php echo $dribbb_id; ?>"> Dribbble </a></span></li>
					<?php endif; ?>
					<?php if(!empty($lstfm_id)) : ?>
						<li><span><a class="lstfm" title="follow on LastFm" target="_blank" href="http://lastfm.com/user/<?php echo $lstfm_id; ?>"> Lastfm </a></span></li>
					<?php endif; ?>
					<?php if(!empty($mspace_id)) : ?>
						<li><span><a class="mspace" title="follow on Myspace" target="_blank" href="http://myspace.com/<?php echo $mspace_id; ?>"> My Space </a></span></li>
					<?php endif; ?>
					<?php if(!empty($rddit_id)) : ?>
						<li><span><a class="rddit" title="follow on Reddit" target="_blank" href="http://reddit.com/user/<?php echo $rddit_id; ?>"> Reddit </a></span></li>
					<?php endif; ?>
					<?php if(!empty($vmeo_id)) : ?>
						<li><span><a class="vmeo" title="follow on Vimeo" target="_blank" href="http://vimeo.com/<?php echo $vmeo_id; ?>"> Vimeo </a></span></li>
					<?php endif; ?>
					<?php if(!empty($utube_id)) : ?>
						<li><span><a class="utube" title="follow on Youtube" target="_blank" href="http://youtube.com/user/<?php echo $utube_id; ?>"> Youtube </a></span></li>
					<?php endif; ?>
					<?php if(!empty($feedbr_id)) : ?>
						<li><span><a class="rss" title="Rss" target="_blank" href="http://feeds.feedburner.com/<?php echo $feedbr_id; ?>"> feed </a></span></li>
					<?php endif; ?>
				</ul>
			</div>
			<?php if($showfoot_id): ?>
			<div id="max-social-foot" class="">
				<span class="ms-foot-text" style="font-family: Arial, Helvetica, sans-serif;">
					<a title="<?php echo $footertext_id; ?>" target="_blank" href="<?php echo $footerurl_id; ?>"><?php echo $footertext_id; ?> &raquo;&raquo;</a>
				</span>
			</div>
			<?php endif; ?>	
		</div>
	</div>
	</div>
	<!-- http://www.designaeon.com/wp-max-social-widget-->
<?php
	}
	function update($new_instance, $old_instance )
	{
		$instance = $old_instance;		
		$instance['smtitle'] = $new_instance['smtitle'];

		$instance['feedbr_id'] = $new_instance['feedbr_id'];

		$instance['twitr_id'] =  $new_instance['twitr_id'];
		
		$instance['twitruname_id'] =  (isset($new_instance['twitruname_id']) ?  1 : 0 );
		$instance['twitrcount_id'] =  isset($new_instance['twitrcount_id']) ? 1 : 0 ;

		$instance['twitr1_id'] =  $new_instance['twitr1_id'];

		$instance['fb_id'] =  $new_instance['fb_id'];
		$instance['fby_id'] =  $new_instance['fby_id'];

		$instance['pinterest_id'] =  $new_instance['pinterest_id'];
		
		$instance['digg_id'] =  $new_instance['digg_id'];
		$instance['su_id'] =  $new_instance['su_id'];
		$instance['dl_id'] =  $new_instance['dl_id'];
		$instance['ln_id'] =  $new_instance['ln_id'];
		$instance['dribbb_id'] =  $new_instance['dribbb_id'];
		$instance['lstfm_id'] =  $new_instance['lstfm_id'];
		$instance['mspace_id'] =  $new_instance['mspace_id'];
		$instance['rddit_id'] =  $new_instance['rddit_id'];
		$instance['vmeo_id'] =  $new_instance['vmeo_id'];

		$instance['gplus_id'] =  $new_instance['gplus_id'];
		
		$instance['utube_id'] =  $new_instance['utube_id'];

		$instance['widgwidth_id'] =  $new_instance['widgwidth_id'];

		$instance['fbwidth_id'] =  $new_instance['fbwidth_id'];

		$instance['fbheight_id'] =  $new_instance['fbheight_id'];
		
		$instance['fbywidth_id'] =  $new_instance['fbywidth_id'];

		$instance['fbyheight_id'] =  $new_instance['fbyheight_id'];

		$instance['recom_id'] =  $new_instance['recom_id'];

		$instance['ewidth_id'] =  $new_instance['ewidth_id'];

		$instance['etext_id'] =  $new_instance['etext_id'];

		$instance['footerurl_id'] =  $new_instance['footerurl_id'];

		$instance['footertext_id'] =  $new_instance['footertext_id'];
		
		$instance['showfoot_id'] =   isset($new_instance['showfoot_id']) ? 1 : 0 ;

		$instance['fbboxcolor_id'] =  $new_instance['fbboxcolor_id'];
		
		$instance['fbboxsubcolor_id'] =  $new_instance['fbboxsubcolor_id'];

		$instance['gpluscolor_id'] =  $new_instance['gpluscolor_id'];

		$instance['twtrcolor_id'] =  $new_instance['twtrcolor_id'];

		$instance['ecolor_id'] =  $new_instance['ecolor_id'];

		$instance['othercolor_id'] =  $new_instance['othercolor_id'];

				/* Strip tags for title and name to remove HTML (important for text inputs). */



		$instance['google_page_id'] = strip_tags( $new_instance['google_page_id'] );



		/* No need to strip tags for sex and show_sex. */

		$instance['badge_layout'] = $new_instance['badge_layout'];

		$instance['badge_color'] = $new_instance['badge_color'];

		return $instance;
	}
	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array('smtitle'=>'Subscribe to Us', 'feedbr_id' => 'designaeon', 'twitr_id' => 'designaeon', 'twitr1_id'=>'ramandeep000','twitruname_id'=>1,'twitrcount_id'=>1,'fb_id' => 'https://facebook.com/designaeon', 'fby_id'=>'ramandeep000' , 'fbwidth_id' => '270', 'fbheight_id' => '80','fbywidth_id' => '270', 'fbyheight_id' => '60', 'recom_id' => 'Recommend on Google', 'ewidth_id' => '140', 'etext_id' => 'Enter your email', 'footerurl_id' => 'http://www.designaeon.com/wp-max-social-widget/', 'footertext_id' => 'Get this widget','showfoot_id'=>1, 'fbboxcolor_id' => '#FFF','fbboxsubcolor_id' => '#FFF', 'gpluscolor_id' => '#F5FCFE', 'twtrcolor_id' => '#EEF9FD', 'ecolor_id' => '#E3EDF4', 'othercolor_id' => '#D8E6EB', 'pinterest_id' => 'ramandeep000','digg_id'=>'sramaninfinite','su_id'=>'ramandeep000','dl_id'=>'ramandeep000','ln_id'=>'http://in.linkedin.com/in/ramandeep0singh','dribbb_id'=>'ramandeepsingh','lstfm_id'=>'ramandeep000','mspace_id'=>'ramandeep000','rddit_id'=>'ramandeep000','vmeo_id'=> 'ramandeepsingh','gplus_id' => '103049352972527333852', 'widgwidth_id' => '280','google_page_id' => '107775935805285788668', 'badge_layout' => 'standard', 'badge_color' => 'light' ) );

		$smtitle=$instance['smtitle'];
		$feedbr_id = $instance['feedbr_id'];

		$twitr_id = format_to_edit($instance['twitr_id']);

		$twitr1_id=format_to_edit($instance['twitr1_id']);
		
		$twitruname_id = format_to_edit($instance['twitruname_id']);
		$twitrcount_id = format_to_edit($instance['twitrcount_id']);

		$fb_id = format_to_edit($instance['fb_id']);
		$fby_id = format_to_edit($instance['fby_id']);

		$pinterest_id = format_to_edit($instance['pinterest_id']);
		
		$digg_id = format_to_edit($instance['digg_id']);
		$su_id = format_to_edit($instance['su_id']);
		$dl_id = format_to_edit($instance['dl_id']);
		$ln_id = format_to_edit($instance['ln_id']);
		$dribbb_id = format_to_edit($instance['dribbb_id']);
		$lstfm_id = format_to_edit($instance['lstfm_id']);
		$mspace_id = format_to_edit($instance['mspace_id']);
		$rddit_id = format_to_edit($instance['rddit_id']);
		$vmeo_id = format_to_edit($instance['vmeo_id']);

		$gplus_id = format_to_edit($instance['gplus_id']);
		$utube_id = format_to_edit($instance['utube_id']);

		$widgwidth_id = format_to_edit($instance['widgwidth_id']);

		$fbwidth_id = format_to_edit($instance['fbwidth_id']);

		$fbheight_id = format_to_edit($instance['fbheight_id']);
		
		$fbywidth_id = format_to_edit($instance['fbywidth_id']);

		$fbyheight_id = format_to_edit($instance['fbyheight_id']);

		$recom_id = format_to_edit($instance['recom_id']);

		$ewidth_id = format_to_edit($instance['ewidth_id']);

		$etext_id = format_to_edit($instance['etext_id']);

		$footerurl_id = format_to_edit($instance['footerurl_id']);

		$footertext_id = format_to_edit($instance['footertext_id']);
		
		$showfoot_id = format_to_edit($instance['showfoot_id']);

		$fbboxcolor_id = format_to_edit($instance['fbboxcolor_id']);
		
		$fbboxsubcolor_id = format_to_edit($instance['fbboxsubcolor_id']);

		$gpluscolor_id = format_to_edit($instance['gpluscolor_id']);

		$twtrcolor_id = format_to_edit($instance['twtrcolor_id']);

		$ecolor_id = format_to_edit($instance['ecolor_id']);

		$othercolor_id = format_to_edit($instance['othercolor_id']);
		?>
		<div>
			<div style="float:left;padding:5px;margin:5px 20px 0 2px;">
			<center><strong><u>Widget Title</u></strong></center><br />

		<p><label for="<?php echo $this->get_field_id('smtitle'); ?>"><?php _e('Enter Widget Title:'); ?></label>

		<input class="widefat" id="<?php echo $this->get_field_id('smtitle'); ?>" name="<?php echo $this->get_field_name('smtitle'); ?>" type="text" value="<?php echo $smtitle; ?>" /></p>
		
		<center><strong><u>Enter Social Profiles</u></strong></center><br />

		<p><label for="<?php echo $this->get_field_id('feedbr_id'); ?>"><?php _e('Enter your Feedburner ID:'); ?></label>

		<input class="widefat" id="<?php echo $this->get_field_id('feedbr_id'); ?>" name="<?php echo $this->get_field_name('feedbr_id'); ?>" type="text" value="<?php echo $feedbr_id; ?>" /></p>

		

		<p><label for="<?php echo $this->get_field_id('twitr_id'); ?>"><?php _e('Enter your twitter ID:'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('twitr_id'); ?>" name="<?php echo $this->get_field_name('twitr_id'); ?>" value="<?php echo $twitr_id; ?>" /></p>

		

		<p><label for="<?php echo $this->get_field_id('twitr1_id'); ?>"><?php _e('Enter your twitter1 ID:(optional)'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('twitr1_id'); ?>" name="<?php echo $this->get_field_name('twitr1_id'); ?>" value="<?php echo $twitr1_id; ?>" /></p>

		
		<div style="border:1px solid #b3b3b3;padding:5px;margin-left:10px;">
		<center><strong><u>Advance Twitter Settings</u></strong></center><br />
		
			<span><input type="checkbox" <?php checked( $instance['twitruname_id'], 1 ); ?> id="<?php echo $this->get_field_id('twitruname_id'); ?>" name="<?php echo $this->get_field_name('twitruname_id'); ?>" value="<?php echo $twitruname_id; ?>" /> <label for="<?php echo $this->get_field_id('twitruname_id'); ?>"><?php _e('Show Username'); ?></label></span>&nbsp;&nbsp;
			<span><input type="checkbox" <?php checked( $instance['twitrcount_id'], 1 ); ?> id="<?php echo $this->get_field_id('twitrcount_id'); ?>" name="<?php echo $this->get_field_name('twitrcount_id'); ?>" value="<?php echo $twitrcount_id; ?>" /> <label for="<?php echo $this->get_field_id('twitrcount_id'); ?>"><?php _e('Show Count'); ?></label></span>
		</div>

		<p><label for="<?php echo $this->get_field_id('fb_id'); ?>"><?php _e('Enter your Facebook Page URL:'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fb_id'); ?>" value="<?php echo $fb_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('fby_id'); ?>"><?php _e('Enter your Facebook ID:'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fby_id'); ?>" value="<?php echo $fby_id; ?>" /></p>



		<p><label for="<?php echo $this->get_field_id('pinterest_id'); ?>"><?php _e('Enter your Pinterest ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('pinterest_id'); ?>" value="<?php echo $pinterest_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('digg_id'); ?>"><?php _e('Enter your Digg ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('digg_id'); ?>" value="<?php echo $digg_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('su_id'); ?>"><?php _e('Enter your Stumble Upon ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('su_id'); ?>" value="<?php echo $su_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('ln_id'); ?>"><?php _e('Enter your Linkdin Url:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('ln_id'); ?>" value="<?php echo $ln_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('digg_id'); ?>"><?php _e('Enter your Delicious ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('dl_id'); ?>" value="<?php echo $dl_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('dribbb_id'); ?>"><?php _e('Enter your Dribbble ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('dribbb_id'); ?>" value="<?php echo $dribbb_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('lstfm_id'); ?>"><?php _e('Enter your LastFm ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('lstfm_id'); ?>" value="<?php echo $lstfm_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('mspace_id'); ?>"><?php _e('Enter your Myspace ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('mspace_id'); ?>" value="<?php echo $mspace_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('rddit_id'); ?>"><?php _e('Enter your Reddit ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('rddit_id'); ?>" value="<?php echo $rddit_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('vmeo_id'); ?>"><?php _e('Enter your Vimeo ID:'); ?><a href="http://www.designaeon.com/wp-max-social-widget/">?</a></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('vmeo_id'); ?>" value="<?php echo $vmeo_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('gplus_id'); ?>"><?php _e('Enter your Google+ ID:'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('gplus_id'); ?>" value="<?php echo $gplus_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('utube_id'); ?>"><?php _e('Enter your Youtube ID:'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('utube_id'); ?>" value="<?php echo $utube_id; ?>" /></p>
 <!-- Badge G+ -->	

		<!-- Google Page ID: Text Input -->

		<p>

			<label for="<?php echo $this->get_field_id( 'google_page_id' ); ?>">Google+ Page ID:<a href="https://developers.google.com/+/plugins/badge/#faq-find-page-id" target="_blank">?</a></label>

			<input id="<?php echo $this->get_field_id( 'google_page_id' ); ?>" name="<?php echo $this->get_field_name( 'google_page_id' ); ?>" value="<?php echo $instance['google_page_id']; ?>" style="width:100%;" />

		</p>



		<!-- Badge Layout Style: Select Box -->

		<p>

			<label for="<?php echo $this->get_field_id( 'badge_layout' ); ?>">Layout Style:</label>

			<select id="<?php echo $this->get_field_id( 'badge_layout' ); ?>" name="<?php echo $this->get_field_name( 'badge_layout' ); ?>" class="widefat" style="width:100%;">

				<option <?php if ( 'smallbadge' == $instance['badge_layout'] ) echo 'selected="selected"'; ?> value="smallbadge">Small</option>

				<option <?php if ( 'badge' == $instance['badge_layout'] ) echo 'selected="selected"'; ?> value="badge">Standard</option>

			</select>

		</p>

		

                <!-- Badge Color Scheme: Select Box -->

		<p>

			<label for="<?php echo $this->get_field_id( 'badge_color' ); ?>">Color Scheme:</label>

			<select id="<?php echo $this->get_field_id( 'badge_color' ); ?>" name="<?php echo $this->get_field_name( 'badge_color' ); ?>" class="widefat" style="width:100%;">

				<option <?php if ( 'light' == $instance['badge_color'] ) echo 'selected="selected"'; ?> value="light">Light</option>

				<option <?php if ( 'dark' == $instance['badge_color'] ) echo 'selected="selected"'; ?> value="dark">Dark</option>

			</select>

		</p>

		

			
			
		
		</div>
		<div style="float:left;width:300px;margin-left:15px;padding:5px;">
				<center><strong><u>Widget Settings</u></strong></center><br />

		<p><label for="<?php echo $this->get_field_id('widgwidth_id'); ?>"><?php _e('Widget width(px):'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('widgwidth_id'); ?>" value="<?php echo $widgwidth_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('fbwidth_id'); ?>"><?php _e('Facebook width(px):'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fbwidth_id'); ?>" value="<?php echo $fbwidth_id; ?>" /></p>



		<p><label for="<?php echo $this->get_field_id('fbheight_id'); ?>"><?php _e('Facebook height(px):'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fbheight_id'); ?>" value="<?php echo $fbheight_id; ?>" /></p>
		
		<!-- Fb Subscribe Box Width/Height -->
		
		<p><label for="<?php echo $this->get_field_id('fbywidth_id'); ?>"><?php _e('Facebook Subscribe width(px):'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fbywidth_id'); ?>" value="<?php echo $fbywidth_id; ?>" /></p>



		<p><label for="<?php echo $this->get_field_id('fbyheight_id'); ?>"><?php _e('Facebook Subscribe height(px):'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fbyheight_id'); ?>" value="<?php echo $fbyheight_id; ?>" /></p>
		
		<!-- Fb Subscribe Box Width/Height -->
		

		<p><label for="<?php echo $this->get_field_id('recom_id'); ?>"><?php _e('Google recommend text:'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('recom_id'); ?>" value="<?php echo $recom_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('ewidth_id'); ?>"><?php _e('Subscription box width(px):'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('ewidth_id'); ?>" value="<?php echo $ewidth_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('etext_id'); ?>"><?php _e('Subscription box text:'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('etext_id'); ?>" value="<?php echo $etext_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('footertext_id'); ?>"><?php _e('Widget foot anchor text:(Optional)'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('footertext_id'); ?>" value="<?php echo $footertext_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('footerurl_id'); ?>"><?php _e('Widget foot URL:(Optional)'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('footerurl_id'); ?>" value="<?php echo $footerurl_id; ?>" /></p>
		
		<div style="border:1px solid #b3b3b3;padding:5px;margin-left:10px;">
		<center><strong><u>Footer Settings</u></strong></center><br />
		
			<span><input type="checkbox" <?php checked( $instance['showfoot_id'], 1 ); ?> id="<?php echo $this->get_field_id('showfoot_id'); ?>" name="<?php echo $this->get_field_name('showfoot_id'); ?>" value="<?php echo $showfoot_id; ?>" /> <label for="<?php echo $this->get_field_id('showfoot_id'); ?>"><?php _e('Show/Hide Footer'); ?></label></span>&nbsp;&nbsp;
			
		</div>

		<center><strong><u>Background Color Settings</u></strong><br />(Get color code list  <a href="http://html-color-codes.info/" rel="nofollow" title="Get color code list here" target="_blank"><strong>HERE</strong></a>)</center><br />

		<p><label for="<?php echo $this->get_field_id('fbboxcolor_id'); ?>"><?php _e('Facebook: Default: #FFFFFF'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fbboxcolor_id'); ?>" value="<?php echo $fbboxcolor_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('fbboxsubcolor_id'); ?>"><?php _e('Facebook Subscribe: Default: #FFFFFF'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('fbboxsubcolor_id'); ?>" value="<?php echo $fbboxsubcolor_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('gpluscolor_id'); ?>"><?php _e('Google: Default: #F5FCFE'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('gpluscolor_id'); ?>" value="<?php echo $gpluscolor_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('twtrcolor_id'); ?>"><?php _e('twitter: Default: #EEF9FD'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('twtrcolor_id'); ?>" value="<?php echo $twtrcolor_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('ecolor_id'); ?>"><?php _e('Subscription: Default: #E3EDF4'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('ecolor_id'); ?>" value="<?php echo $ecolor_id; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('othercolor_id'); ?>"><?php _e('RSS, LinkedIn, Google+: Default: #D8E6EB'); ?></label>

		<input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('othercolor_id'); ?>" value="<?php echo $othercolor_id; ?>" /></p>
		
			<p><hr />

				<label>If you liked this plugin, Please like on facebook ,G+:  </label><br />

	<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fdesignaeon.com&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=295337620523337" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>

	<iframe src="//www.facebook.com/plugins/subscribe.php?href=https%3A%2F%2Fwww.facebook.com%2Framandeep000&amp;layout=button_count&amp;show_faces=false&amp;colorscheme=light&amp;font&amp;width=120&amp;appId=102008056593077" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:120px;  height:21px;" allowTransparency="true"></iframe>

	<a href="https://plus.google.com/103049352972527333852?prsrc=3" rel="author" style="display:inline-block;text-decoration:none;color:#333;text-align:center;font:13px/16px arial,sans-serif;white-space:nowrap;"><span style="display:inline-block;font-weight:bold;vertical-align:top;margin-right:5px;margin-top:0px;">Follow</span><span style="display:inline-block;vertical-align:top;margin-right:13px;margin-top:0px;">on</span><img src="https://ssl.gstatic.com/images/icons/gplus-16.png" alt="" style="border:0;width:16px;height:16px;"/></a>



		</p>
		
		<p>Support this widget Share it! For more info, go to <a href="http://www.designaeon.com/wp-max-social-widget/" target="_blank">WP Max Social Wigdet</a>  page</p>
		</div>
		
		</div>
		
 <!-- Badge G+ -->		

		
<?php
	}
	
}

function add_scripts()
	{
		 echo '<script type="text/javascript">';

		echo '(function()';

		echo '{var po = document.createElement("script");';

		echo 'po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";';

		echo 'var s = document.getElementsByTagName("script")[0];';

		echo 's.parentNode.insertBefore(po, s);';



		echo '})();</script>';
		
		
	}
	add_action( 'wp_head', 'add_scripts');
	
	
//designaeon feeds

add_action('wp_dashboard_setup', 'da_dashboard_widgets');

function da_dashboard_widgets() {

     global $wp_meta_boxes;

     // remove unnecessary widgets

     // var_dump( $wp_meta_boxes['dashboard'] ); // use to get all the widget IDs

     unset(

          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],

          $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],

          $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']

     );

     // add a custom dashboard widget

     wp_add_dashboard_widget( 'dashboard_custom_feed', 'Important News you must read', 'dashboard_da_custom_feed_output' ); //add new RSS feed output

}

function dashboard_da_custom_feed_output() {

     echo '<div class="rss-widget">';

     wp_widget_rss_output(array(

          'url' => 'http://feeds.feedburner.com/designaeon',

          'title' => 'What\'s up at Design Aeon',

          'items' => 10,

          'show_summary' => 1,

          'show_author' => 0,

          'show_date' => 1 

     ));

     echo "</div>";

}

//end feeds


add_action('widgets_init', create_function('', 'return register_widget(\'maxsocial\');'));

add_action('wp_head', array('maxsocial','addcss'));