<?php
/*
Plugin Name: Weptile Image Slider Widget
Plugin URI: http://weptile.com
Description: Easy, lightweight, responsive sidebar image slider widget. Utilizes the Nivo slider script. Includes lots and lots of customization options and all done within the widget. Allows multiple widgets on one screen and can be used in any sidebar. (Please visit <a href="http://weptile.com" target="_blank" title="wordpress development">Weptile.com</a> for more. You can also <a href="http://weptile.com" target="_blank" title="wordpress development">HIRE WEPTILE</a> for all your Wordpress projects and/or for WP support.)
Version: 1.1.3
Author: Weptile
Author URI: http://weptile.com
License: GPL v3

Weptile Image Slider Widget
Copyright (C) 2012, Ufuk Erdogmus - pm@weptile.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Weptile_Image_Slider_Widget extends WP_Widget {
	private $textdomain = 'weptile_image_slider_textdomain';
	private $default_themes = array('default','light','dark','bar');

	public function __construct() {
		// widget actual processes
		parent::__construct(
			'weptile-image-slider-widget', // Base ID
			'Weptile Image Slider Widget', // Name
			array( // Args
				'description' => __('A widgetized image slider by Weptile. Please visit Weptile.com for more.', $this->textdomain)
			)
		);
	}

	public function form($instance) {
		// outputs the options form on admin

		if ($instance) {
			$slider_options = array(
				'title' => esc_attr($instance['title']),
				'width' => esc_attr($instance['slider-width']),
				'height' => esc_attr($instance['slider-height']),
				'theme' => esc_attr($instance['slider-theme']),
				'effect' => esc_attr($instance['slider-effect']),
				'speed' => esc_attr($instance['slider-speed']),
				'duration' => esc_attr($instance['slider-duration']),
				'directional-nav' => esc_attr($instance['slider-directional-nav']),
				'button-nav' => esc_attr($instance['slider-button-nav']),
				'pause-hover' => esc_attr($instance['slider-pause-hover']),
				'start-random' => esc_attr($instance['slider-start-random']),
				'slices' => esc_attr($instance['slider-slices']),
				'box-columns' => esc_attr($instance['slider-box-columns']),
				'box-rows' => esc_attr($instance['slider-box-rows']),
				'prev-text' => esc_attr($instance['slider-prev-text']),
				'next-text' => esc_attr($instance['slider-next-text']),
				'responsive' => esc_attr($instance['slider-responsive'])
			);
		}


		echo '<h4><label for="'.$this->get_field_id('title').'">'.__('Title', $this->textdomain).'</label></h4> <input type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" ' . (!empty($slider_options['title']) ? ' value="' . $slider_options['title'] . '" ' : '') . ' class="widefat" />';
		echo '<h4>' . __('Options', $this->textdomain) . '</h4>';
		echo
		'<table style="width:100%; border-collapse:collapse; border:1px solid #CCC;" cellpadding="4">
			<tr>
				<th colspan="2">' . __('Visual', $this->textdomain) . '</th>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-width') . '">' . __('Width', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-width') . '" type="text" name="' . $this->get_field_name('slider-width') . '" ' . (!empty($slider_options['width']) ? ' value="' . $slider_options['width'] . '" ' : '') . ' size="2" class="weptile-image-slider-widget-number-only-input" /> px</td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-height') . '">' . __('Height', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-height') . '" type="text" name="' . $this->get_field_name('slider-height') . '" ' . (!empty($slider_options['height']) ? ' value="' . $slider_options['height'] . '" ' : '') . ' size="2" class="weptile-image-slider-widget-number-only-input" /> px</td>
			</tr>
			<tr class="weptile-tr-with-description">
				<td><label for="' . $this->get_field_id('slider-responsive') . '">' . __('Responsive', $this->textdomain) . '</label> :</td>
				<td><input  id="' . $this->get_field_id('slider-responsive') . '" name="' . $this->get_field_name('slider-responsive') . '" ' . (($slider_options['responsive'] == true) ? ' checked="checked" ' : '') . ' type="checkbox" value="1" /></td>
			</tr>
			<tr style=""><td colspan="2">'.__('This feature makes the slider fits its container in both conditions; if the container smaller/bigger than images or the container resizes. Otherwise slider will render in the dimensions you set above.').'</td></tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-theme') . '">' . __('Slider Theme', $this->textdomain) . '</label> :</td>
				<td>';
					$theme_options = array(
						'default' => __('Default Theme',$this->textdomain),
						'light' => __('Light Theme',$this->textdomain),
						'dark' => __('Dark Theme',$this->textdomain),
						'bar' => __('Bar Theme',$this->textdomain),
						'weptile_custom_theme' => __('Custom Theme...', $this->textdomain)
					);
					if (array_key_exists($slider_options['theme'], $theme_options) || empty($slider_options['theme'])){
						echo '<select  id="' . $this->get_field_id('slider-theme') . '" class="weptile-image-slider-theme-select widefat" name="' .$this->get_field_name('slider-theme') . '" >';

						foreach($theme_options as $key => $value){
							echo'<option value="'.$key.'" '.( esc_attr($slider_options['theme']) == $key ? ' selected="selected" ' : '' ).'>'.$value.'</option>';
						}
						echo '</select>';
					}else{
						echo '<input  id="' . $this->get_field_id('slider-theme') . '" class="weptile-image-slider-custom-theme-input widefat" type="text"  name="' . $this->get_field_name('slider-theme') . '" ' .
							((($slider_options['theme']) != '') ? ' value="' . $slider_options['theme'].'"' : '') . ' /><button type="button" title="'.__('Cancel custom theme name input', $this->textdomain).'" class="button weptile-image-slider-custom-theme-cancel-button" >X</button>';
					}
				echo
					'
				</td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-directional-nav') . '">' . __('Directional Navigation', $this->textdomain) . '</label> :</td>
				<td><input  id="' . $this->get_field_id('slider-directional-nav') . '" name="' . $this->get_field_name('slider-directional-nav') . '" ' . (($slider_options['directional-nav'] == true) ? ' checked="checked" ' : '') . ' type="checkbox" value="1" /></td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-button-nav') . '">' . __('Button Navigation', $this->textdomain) . '</label> :</td>
				<td><input  id="' . $this->get_field_id('slider-button-nav') . '" name="' . $this->get_field_name('slider-button-nav') . '" ' . (($slider_options['button-nav'] == true) ? ' checked="checked" ' : '') . ' type="checkbox" value="1" /></td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-pause-hover') . '">' . __('Pause on mouse hover', $this->textdomain) . '</label> :</td>
				<td><input  id="' . $this->get_field_id('slider-pause-hover') . '" name="' . $this->get_field_name('slider-pause-hover') . '" ' . (($slider_options['pause-hover'] == true) ? ' checked="checked" ' : '') . ' type="checkbox" value="1" /></td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-start-random') . '">' . __('Start random', $this->textdomain) . '</label> :</td>
				<td><input  id="' . $this->get_field_id('slider-start-random') . '" name="' . $this->get_field_name('slider-start-random') . '" ' . (($slider_options['start-random'] == true) ? ' checked="checked" ' : '') . ' type="checkbox" value="1" /></td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-prev-text') . '">' . __('Previous Text', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-prev-text') . '" type="text" name="' . $this->get_field_name('slider-prev-text') . '" ' . (!empty($slider_options['prev-text']) ? ' value="' . $slider_options['prev-text'] . '" ' : ' value="'. __('Previous', $this->textdomain) .'" ') . ' size="3" /></td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-next-text') . '">' . __('Next Text', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-next-text') . '" type="text" name="' . $this->get_field_name('slider-next-text') . '" ' . (!empty($slider_options['next-text']) ? ' value="' . $slider_options['next-text'] . '" ' : ' value="'. __('Next', $this->textdomain) .'" ') . ' size="3" /></td>
			</tr>
		</table>
		<br />
		<table style="width:100%; border-collapse:collapse; border:1px solid #CCC;" cellpadding="4">
			<tr>
				<th colspan="2">' . __('Transitions', $this->textdomain) . '</th>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-effect') . '">' . __('Effects', $this->textdomain) . '</label> :</td>
				<td>
					<select id="' . $this->get_field_id('slider-effect') . '" name="' . $this->get_field_name('slider-effect') . '" style="width:100px;">';
					$available_effects = array(
						'fade' => __('Fade', $this->textdomain),
						'fold' => __('Fold', $this->textdomain),
						'slideInLeft' => __('Slide In Left', $this->textdomain),
						'slideInRight' => __('Slide In Right', $this->textdomain),
						'random' => __('Random', $this->textdomain),
						'sliceDown' => __('Slice Down', $this->textdomain),
						'sliceDownLeft' => __('Slice Down Left', $this->textdomain),
						'sliceUp' => __('Slice Up', $this->textdomain),
						'sliceUpLeft' => __('Slice Up Left', $this->textdomain),
						'sliceUpDown' => __('Slice Up and Down', $this->textdomain),
						'sliceUpDownLeft' => __('Slice Up, Down and Left', $this->textdomain),
						'boxRandom' => __('Random Boxes', $this->textdomain),
						'boxRain' => __('Raining Boxes', $this->textdomain),
						'boxRainReverse' => __('Raining Boxes Reverse', $this->textdomain),
						'boxRainGrow' => __('Growing Boxes Raining', $this->textdomain),
						'boxRainGrowReverse' => __('Growing Boxes Raining Reverse', $this->textdomain));
				foreach ($available_effects as $k => $v) {
					echo '<option value="' . $k . '" ' . (($slider_options['effect'] == $k) ? ' selected="selected" ' : '') . ' >' . $v . '</option>';
				}
				echo
				'</select>
				</td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-speed') . '">' . __('Speed', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-speed') . '" type="text" name="' . $this->get_field_name('slider-speed') . '" ' . (!empty($slider_options['speed']) ? ' value="' . $slider_options['speed'] . '" ' : '') . ' size="2" class="weptile-image-slider-widget-number-only-input" /> ms</td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-duration') . '">' . __('Duration', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-duration') . '" type="text" name="' . $this->get_field_name('slider-duration') . '" ' . (!empty($slider_options['duration']) ? ' value="' . $slider_options['duration'] . '" ' : '') . ' size="2" class="weptile-image-slider-widget-number-only-input" /> ms</td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-slices') . '">' . __('Slices', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-slices') . '" type="text" name="' . $this->get_field_name('slider-slices') . '" ' . (!empty($slider_options['slices']) ? ' value="' . $slider_options['slices'] . '" ' : '') . ' size="2" class="weptile-image-slider-widget-number-only-input" /></td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-box-columns') . '">' . __('Box Columns', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-box-columns') . '" type="text" name="' . $this->get_field_name('slider-box-columns') . '" ' . (!empty($slider_options['box-columns']) ? ' value="' . $slider_options['box-columns'] . '" ' : '') . ' size="2" class="weptile-image-slider-widget-number-only-input" /></td>
			</tr>
			<tr>
				<td><label for="' . $this->get_field_id('slider-box-rows') . '">' . __('Box Rows', $this->textdomain) . '</label> :</td>
				<td><input id="' . $this->get_field_id('slider-box-rows') . '" type="text" name="' . $this->get_field_name('slider-box-rows') . '" ' . (!empty($slider_options['box-rows']) ? ' value="' . $slider_options['box-rows'] . '" ' : '') . ' size="2" class="weptile-image-slider-widget-number-only-input" /></td>
			</tr>
		</table>
		<br />
		<h4>' . __('Images', $this->textdomain) . '</h4>';

		if (count($instance['slider-images']) > 0) {
			echo '<ol id="' . $this->get_field_id('slider-images-order') . '" class="weptile-image-slider-images-order">';
				$i = 0;
				foreach ($instance['slider-images'] as $image) {

					$image_path = str_ireplace(get_site_url(), '', $image);
					$image_path = weptile_get_wp_config_path() . $image_path;
					$image_file_name = substr($image_path, strripos($image_path, '/') + 1);
					$suffix = 'resized-30x30';

					$image_url = get_site_url() . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/cache/' . str_ireplace(substr($image_file_name, -4), '-' . $suffix.'-thumbnail' . substr($image_file_name, -4), $image_file_name);

					echo
					'<li>
						<img src="' . esc_attr($image_url) . '" /><button class="weptile-image-slider-images-delete-button button" type="button">' . __('Remove Image', $this->textdomain) . '</button>
						<table style="width:100%;" border="0" cellpadding="4" class="weptile-image-slider-images-details-table">
							<tr>
								<td colspan="2">
									<input  id="' . $this->get_field_id('slider-image-link-'.$i) . '" class="weptile-image-slider-image-link-input widefat" type="text" placeholder="' . __('Link : http://', $this->textdomain) . '" name="' . $this->get_field_name('slider-image-links') . '[]" ' .
										((esc_attr($instance['slider-image-links'][$i]) != '') ? ' value="' . esc_attr($instance['slider-image-links'][$i]) . '" ' : '') . ' />
								</td>
							</tr>
							<tr>
								<td>
									Link\'s Target
								</td>
								<td>';
									$target_options = array(
										'_blank' => __('In a new window / tab', $this->textdomain),
										'_self' => __('In the same page', $this->textdomain),
										'_parent' => __('In the container of the frame', $this->textdomain),
										'_top' => __('In the same window of the frame\'s container', $this->textdomain),
										'weptile_custom_frame_name' => __('Custom frame name...', $this->textdomain)
									);
									if (array_key_exists($instance['slider-image-link-targets'][$i], $target_options) || esc_attr($instance['slider-image-link-targets'][$i]) == ''){
										echo '<select  id="' . $this->get_field_id('slider-image-link-targets'.$i) . '" class="weptile-image-slider-image-link-target-select widefat" name="' . $this->get_field_name('slider-image-link-targets') . '[]" >';

										foreach($target_options as $key => $value){
											echo'<option value="'.$key.'" '.( esc_attr($instance['slider-image-link-targets'][$i]) == $key ? ' selected="selected" ' : '' ).'>'.$value.'</option>';
										}
										echo '</select>';
									}else{
										echo '<input  id="' . $this->get_field_id('slider-image-link-targets'.$i) . '" class="weptile-image-slider-image-link-target-input widefat" type="text" name="' . $this->get_field_name('slider-image-link-targets') . '[]" ' .
											((esc_attr($instance['slider-image-link-targets'][$i]) != '') ? ' value="' . esc_attr($instance['slider-image-link-targets'][$i]) . '" ' : '') . ' /><button type="button" title="'.__('Cancel custom frame name input', $this->textdomain).'" class="button weptile-image-slider-image-link-target-cancel-button" >X</button>';
									}
							echo'
								</td>
							</tr>
							<tr>
								<td>
									Link\'s Rel
								</td>
								<td>';
									$rel_options = array(
										'' => '',
										'alternate' => 'alternate',
										'author' => 'author',
										'bookmark' => 'bookmark',
										'help' => 'help',
										'license' => 'license',
										'next' => 'next',
										'nofollow' => 'nofollow',
										'noreferrer' => 'noreferrer',
										'prefetch' => 'prefetch',
										'prev' => 'prev',
										'search' => 'search',
										'tag' => 'tag',
										'weptile_custom_rel' => __('Custom rel value...', $this->textdomain)
									);
									if (array_key_exists($instance['slider-image-link-rels'][$i], $rel_options) || esc_attr($instance['slider-image-link-rels'][$i]) == ''){
										echo '<select  id="' . $this->get_field_id('slider-image-link-rels'.$i) . '" class="weptile-image-slider-image-link-rel-select widefat" name="' . $this->get_field_name('slider-image-link-rels') . '[]" >';

										foreach($rel_options as $key => $value){
											echo'<option value="'.$key.'" '.( esc_attr($instance['slider-image-link-rels'][$i]) == $key ? ' selected="selected" ' : '' ).'>'.$value.'</option>';
										}
										echo '</select>';
									}else{
										echo '<input  id="' . $this->get_field_id('slider-image-link-rels'.$i) . '" class="weptile-image-slider-image-link-rel-input widefat" type="text" name="' . $this->get_field_name('slider-image-link-rels') . '[]" ' .
											((esc_attr($instance['slider-image-link-targets'][$i]) != '') ? ' value="' . esc_attr($instance['slider-image-link-rels'][$i]) . '" ' : '') . ' /><button type="button" title="'.__('Cancel custom rel value input', $this->textdomain).'" class="button weptile-image-slider-image-link-rel-cancel-button" >X</button>';
									}
							echo'
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input  id="' . $this->get_field_id('slider-image-caption-'.$i) . '" class="weptile-image-slider-image-caption-input widefat" type="text" placeholder="' . __('Caption :', $this->textdomain) . '" name="' . $this->get_field_name('slider-image-captions') . '[]" ' .
										((esc_attr($instance['slider-image-captions'][$i]) != '') ? ' value="' . esc_attr($instance['slider-image-captions'][$i]) . '" ' : '') . ' />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input  id="' . $this->get_field_id('slider-image-alt-'.$i) . '" class="weptile-image-slider-image-alt-input widefat" type="text" placeholder="' . __('Alt :', $this->textdomain) . '" name="' . $this->get_field_name('slider-image-alts') . '[]" ' .
										((esc_attr($instance['slider-image-alts'][$i]) != '') ? ' value="' . esc_attr($instance['slider-image-alts'][$i]) . '" ' : '') . ' />
								</td>
							</tr>
						</table>
						<input type="hidden" name="' . $this->get_field_name('slider-images') . '[]" value="' . esc_attr($image) . '" />
						<button class="weptile-image-slider-images-details-button button" type="button">' . __('Details', $this->textdomain) . '</button>'.
					'</li>';
					$i++;
				}
			echo'</ol>';
		}


		echo
		'<p>' . __('You can upload new images or pick one of your images from your gallery and drag them to reorder.', $this->textdomain) . '</p>
		<br />
		<input type="hidden" id="' . $this->get_field_id('slider-images') . '" name="' . $this->get_field_name('slider-images') . '[]" disabled="disabled"  />
		<button id="' . $this->get_field_id('slider-images-upload-button') . '" class="button" type="button" style="margin: 5px auto; display: block;">' . __('Upload or Pick Image', $this->textdomain) . '</button>
		<script>
		window.weptile_link_target_select_options = \'<option value="_blank">'.__('New page/tab', $this->textdomain).'</option><option value="_self">'. __('Same page', $this->textdomain).'</option><option value="_parent">'.__('In the container of the frame', $this->textdomain).'</option><option value="_top">'.__('Same window of the frame\\\'s container', $this->textdomain).'</option><option value="weptile_custom_frame_name">'.__('Custom frame name...', $this->textdomain).'</option>\';
		window.weptile_link_target_input_title = "'.__('Cancel custom frame name input', $this->textdomain).'";

		window.weptile_link_rel_select_options = \'<option></option><option>alternate</option><option>author</option><option>bookmark</option><option>help</option><option>license</option><option>next</option><option>nofollow</option><option>noreferrer</option><option>prefetch</option><option>prev</option><option>search</option><option>tag</option><option value="weptile_custom_rel">'.__('Custom rel value...', $this->textdomain).'</option>\';
		window.weptile_link_rel_input_title = "'.__('Cancel custom rel value input', $this->textdomain).'";
		window.weptile_custom_theme_button_title = "'.__('Custom Theme...', $this->textdomain).'";
		window.weptile_slider_theme_select_options = \'<option value="default">'.__('Default Theme', $this->textdomain).'</option><option value="light">'.__('Light Theme', $this->textdomain).'</option><option value="dark">'.__('Dark Theme', $this->textdomain).'</option><option value="bar">'.__('Bar Theme', $this->textdomain).'</option><option value="weptile_custom_theme">'.__('Custom Theme...', $this->textdomain).'</option>\';
		jQuery(function () {
			jQuery("#' . $this->get_field_id('slider-images-order') . '").sortable({
					placeholder: "weptile-image-slider-images-order-item-placeholder"
				});
		});
		function apply_insert_button_filter(iframejq) {
			timeout = setTimeout(function(){
				if(iframejq != undefined){
					if(iframejq("#src").length > 0) {
						iframejq("body").addClass("fromUrl");
					}

					iframejq("#go_button").each(function(i, e){
						iframejq(e).attr("value",  "'.__('Send Image to Weptile Slider Widget', $this->textdomain).'");
					});
					iframejq(".savesend > input[type=submit]").each(function(){
						jQuery(this).attr("value", "'.__('Send Image to Weptile Slider Widget', $this->textdomain).'");
					});
					apply_insert_button_filter(iframejq);
				}
			}, 1);
		}
		</script>
		This widget is brought to you by <a href="http://weptile.com" target="_blank" title="wordpress development">Weptile</a>.
		<div>Does this plugin help you out?
				​<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9RUSUW5XBBESE" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></a>
			</div>';
	}

	public function widget($args, $instance) {
		// outputs the content of the widget

		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title']);


		echo $before_widget;

		if ($title) {
			echo $before_title . $title . $after_title;
		}

		$is_there_a_problem = false;

		if (count($instance['slider-images']) === 0) {
			$is_there_a_problem = true;
			$error_message = '<p>'. __('No image found for slider', $this->textdomain) .'</p>';
		}

		if ($instance['slider-width'] < 50 || $instance['slider-height'] < 50){
			$is_there_a_problem = true;
			$error_message = '<p>'. __('Slider height and/or width are not valid or smaller than 50px', $this->textdomain) .'</p>';
		}


		if ($is_there_a_problem === false) {
			$slider_options = array(
				'width' => esc_attr($instance['slider-width']),
				'height' => esc_attr($instance['slider-height']),
				'theme' => esc_attr($instance['slider-theme']),
				'effect' => esc_attr($instance['slider-effect']),
				'speed' => esc_attr($instance['slider-speed']),
				'duration' => esc_attr($instance['slider-duration']),
				'directional-nav' => esc_attr($instance['slider-directional-nav']),
				'button-nav' => esc_attr($instance['slider-button-nav']),
				'pause-hover' => esc_attr($instance['slider-pause-hover']),
				'start-random' => esc_attr($instance['slider-start-random']),
				'slices' => esc_attr($instance['slider-slices']),
				'box-columns' => esc_attr($instance['slider-box-columns']),
				'box-rows' => esc_attr($instance['slider-box-rows']),
				'prev-text' => esc_attr($instance['slider-prev-text']),
				'next-text' => esc_attr($instance['slider-next-text']),
				'responsive' => esc_attr($instance['slider-responsive'])
			);

			if (empty($slider_options['theme'])) {
				$slider_options['theme'] = 'default';
			}

			if (array_search($slider_options['theme'],$this->default_themes) === false){
				//if this is a custom theme
				//first check if the specified folder exists
				if (is_dir(WP_PLUGIN_DIR.'/'.$slider_options['theme']))
					wp_register_style('weptile-image-slider-widget-nivo-slider-theme-'.$slider_options['theme'], WP_PLUGIN_URL . '/'.$slider_options['theme'].'/'.$slider_options['theme'].'.css');
				else
					$slider_options['theme'] = 'default';
			}

			wp_enqueue_style('weptile-image-slider-widget-nivo-slider-theme-' . $slider_options['theme']);

			if ($slider_options['responsive'] != true)
				echo '<style type="text/css" >.slider-wrapper.' . $this->get_field_id('weptile-image-slider-widget-nivo-slider') . '{ width:' . $slider_options['width'] . 'px; /*height:' . $slider_options['height'] . 'px;*/ }</style>';

			echo
			'<div class="slider-wrapper weptile-image-slider-widget-slider-wrapper theme-' . $slider_options['theme'] . ' ' . $this->get_field_id('weptile-image-slider-widget-nivo-slider') . '">'.
				'<div class="nivoSliderWeptile" id="' . $this->get_field_id('weptile-image-slider-widget-nivo-slider') . '">';
			foreach ($instance['slider-images']  as $i => $image) {

				$image_path = str_ireplace(get_site_url(), '', $image);
				$image_path = weptile_get_wp_config_path() . $image_path;

				$image_file_name = substr($image_path, strripos($image_path, '/') + 1);
				$suffix = 'resized-' . $slider_options['width'] . 'x' . $slider_options['height'];


				$image_url = get_site_url() . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/cache/' . str_ireplace(substr($image_file_name, -4), '-' . $suffix . substr($image_file_name, -4), $image_file_name);
				echo ( !empty($instance['slider-image-links'][$i]) ? '<a href="'.$instance['slider-image-links'][$i].'" target="'.$instance['slider-image-link-targets'][$i].'" rel="'.$instance['slider-image-link-rels'][$i].'" >' : '' );
				echo '<img src="' . $image_url . '" alt="'. (!empty($instance['slider-image-alts'][$i]) ? $instance['slider-image-alts'][$i] : '') .'" title="'. ( !empty($instance['slider-image-captions'][$i]) ? $instance['slider-image-captions'][$i] : '' ) .'" />';
				echo ( !empty($instance['slider-image-links'][$i]) ? '</a>' : '' );
			}
			echo
				'</div>'.
			'</div>';
			echo '<script>
			jQuery(window).load(function() {
				jQuery("#' . $this->get_field_id('weptile-image-slider-widget-nivo-slider') . '").nivoSliderWeptile({
					animSpeed:' . (empty($slider_options['speed']) ? 650 : $slider_options['speed'] ) . ',
					pauseTime:' . (empty($slider_options['duration']) ? 5000 : $slider_options['duration'] ) . ',
					effect:"' . $slider_options['effect'] . '",
					prevText: "' . __('Previous', $this->textdomain) . '",
					nextText: "' . __('Next', $this->textdomain) . '",
					directionNav: ' . ($slider_options['directional-nav'] ? 'true' : 'false') . ',
					controlNav: ' . ($slider_options['button-nav'] ? 'true' : 'false') . ',
					pauseOnHover: ' . ($slider_options['pause-hover'] ? 'true' : 'false') . ',
					randomStart: ' . ($slider_options['start-random'] ? 'true' : 'false') . ',
					slices: ' . (empty($slider_options['slices']) ? '15' : $slider_options['slices']) . ',
					boxCols:' . (empty($slider_options['box-columns']) ? '8' : $slider_options['box-columns']) . ',
					boxRows:' . (empty($slider_options['box-rows']) ? '4' : $slider_options['box-rows']) . ',
					prevText: "' . (empty($slider_options['prev-text']) ? 'Previous' : $slider_options['prev-text']) . '",
					nextText: "' . (empty($slider_options['next-text']) ? 'Next' : $slider_options['next-text']) . '"
				});
			});
			</script>';

			// Else
		} else {
			echo $error_message;
		}

		echo $after_widget;
	}

	public function update($new_instance, $old_instance) {
		// processes widget options to be saved

		$instance = $old_instance;

		foreach ($old_instance['slider-images']  as $i => $image) { //clear old images and thumbs
			$image_path = str_ireplace(get_site_url(), '', $image);
			$image_path = weptile_get_wp_config_path() . $image_path;
			$image_file_name = substr($image_path, strripos($image_path, '/') + 1);
			$dest_path = weptile_get_wp_config_path() . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/cache/';
			$suffix_thumb ='resized-30x30-thumbnail';
			$old_suffix = $suffix = 'resized-' . $old_instance['slider-width'] . 'x' . $old_instance['slider-height'];
			$check_file_path = $dest_path . str_ireplace(substr($image_file_name, -4), '-' . $old_suffix . substr($image_file_name, -4), $image_file_name);
			if (is_file($check_file_path))
				unlink($check_file_path);
			$check_file_path = $dest_path . str_ireplace(substr($image_file_name, -4), '-' . $suffix_thumb . substr($image_file_name, -4), $image_file_name);
			if (is_file($check_file_path))
				unlink($check_file_path);
		}


		array_walk_recursive($new_instance,'strip_tags_for_array');
		$instance = $new_instance;

		foreach ($instance['slider-images']  as $i => $image) {
			$image_path = str_ireplace(get_site_url(), '', $image);
			$image_path = weptile_get_wp_config_path() . $image_path;

			$dest_path = weptile_get_wp_config_path() . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/cache/';
			$suffix = 'resized-' . $instance['slider-width'] . 'x' . $instance['slider-height'];
			$suffix_thumb ='resized-30x30-thumbnail';

			$image_size = getimagesize($image_path);
			if( $image_size[0] <= $instance['slider-width'] && $image_size[1] <= $instance['slider-height']){ // source image dimensions' are equal or smaller to target copy the image to cache
				$extension = substr($image_path,strrpos($image_path,'.'));
				$new_image_file_name = substr($image_path,strrpos($image_path,'/') + 1);
				$new_image_file_name_with_suffix = str_ireplace($extension, '-'.$suffix.$extension ,$new_image_file_name);
				copy($image_path , $dest_path . $new_image_file_name_with_suffix);
			}else{
				if (function_exists('wp_get_image_editor')){
					$image = wp_get_image_editor($image_path);
					if ( ! is_wp_error( $image ) ) {
						$image->set_quality(100);
						$image->resize( $instance['slider-width'], $instance['slider-height'], true );
						$image->save($image->generate_filename($suffix, $dest_path));
					}
				}else
					image_resize($image_path, $instance['slider-width'], $instance['slider-height'], true, $suffix, $dest_path, 100); //older versions of wp < 3.5
			}
			if (function_exists('wp_get_image_editor')){
				$image = wp_get_image_editor($image_path);
				if ( ! is_wp_error($image) ) {
					$image->set_quality(100);
					$image->resize(30, 30, true);
					$image->save($image->generate_filename($suffix_thumb, $dest_path)); //thumbnails for wp admin side
				}
			}else{
				image_resize($image_path, 30, 30, true, $suffix_thumb, $dest_path, 100); //thumbnails for wp admin side //older versions of wp < 3.5
			}
		}

		return $instance;
	}
}

function strip_tags_for_array($value,$key){
	$value = strip_tags($value);
}

function esc_attr_for_array($value,$key){
	$value = esc_attr($value);
}

function weptile_get_wp_config_path() {
	$base = dirname(__FILE__);
	$path = false;

	if (file_exists(dirname(dirname($base)) . "/wp-config.php")) {
		$path = dirname(dirname($base)) . "/wp-config.php";
	} else
		if (file_exists(dirname(dirname(dirname($base))) . "/wp-config.php")) {
			$path = dirname(dirname(dirname($base))) . "/wp-config.php";
		} else
			$path = false;

	if ($path != false) {
		$path = str_replace("\\", "/", $path);
	}
	return str_replace('/wp-config.php', '', $path);
}

function weptile_slider_widget_clear_cache() {
	$cache_path = weptile_get_wp_config_path() . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/cache/';
	$files = glob($cache_path . '*'); // get all file names
	foreach ($files as $file) { // iterate files
		if (is_file($file))
			unlink($file); // delete file
	}
}


/**
 * Script & stype loader for widget.php
 */
function weptile_image_slider_widget_admin_actions($hook) {
	if (('widgets.php' != $hook) && ('customize.php' != $hook)) {
		return;
	}
	// Scripts
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery-ui-sortable');
	wp_register_script('weptile-image-slider-widget-admin', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/js/weptile-image-slider-widget-admin.js'));
	wp_enqueue_script('weptile-image-slider-widget-admin');

	// Styles
	wp_enqueue_style('thickbox');
	wp_enqueue_style('jquery-ui');
	wp_register_style('weptile-image-slider-widget', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/css/weptile-image-slider-widget-admin.css'));
	wp_enqueue_style('weptile-image-slider-widget');
}

/**
 * Script & style loader for the actual widget
 */
function weptile_image_slider_widget_actions() {

	// Scripts
	wp_enqueue_script('jquery');
	wp_register_script('weptile-image-slider-widget', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/js/weptile-image-slider-widget.js'));
	wp_enqueue_script('weptile-image-slider-widget');
	//for nivo slider
	wp_register_script('weptile-image-slider-widget-nivo-slider', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/nivo-slider/jquery.nivo.slider.pack.js'));
	wp_enqueue_script('weptile-image-slider-widget-nivo-slider');


	// Styles
	wp_register_style('weptile-image-slider-widget', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/css/weptile-image-slider-widget.css'));
	wp_enqueue_style('weptile-image-slider-widget');
	//for nivo slider
	wp_register_style('weptile-image-slider-widget-nivo-slider', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/nivo-slider/nivo-slider.css'));
	wp_enqueue_style('weptile-image-slider-widget-nivo-slider');
	//register nivo slider themes for widget calls
	wp_register_style('weptile-image-slider-widget-nivo-slider-theme-default', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/nivo-slider/themes/default/default.css'));
	wp_register_style('weptile-image-slider-widget-nivo-slider-theme-bar', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/nivo-slider/themes/bar/bar.css'));
	wp_register_style('weptile-image-slider-widget-nivo-slider-theme-dark', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/nivo-slider/themes/dark/dark.css'));
	wp_register_style('weptile-image-slider-widget-nivo-slider-theme-light', path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)) . '/nivo-slider/themes/light/light.css'));

}

add_action('admin_enqueue_scripts', 'weptile_image_slider_widget_admin_actions');
add_action('wp_enqueue_scripts', 'weptile_image_slider_widget_actions');
add_action('widgets_init', create_function('', 'register_widget( "weptile_image_slider_Widget" );'));

function weptile_image_slider_shortcode( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'imgs' => '',
        'width' => '0',
        'height' => '0',
        'theme' => 'default',
        'effect' => '',
        'speed' => '',
        'duration' => '',
        'slice' => '',
        'boxcolumns' => '',
        'boxrows' => '',
        'prevtext' => '',
        'nexttext' => '',
        'responsive' => '0',
		'directionalnav' => '',
		'buttonnav' => '',
		'pausehover' => '',
		'startrandom' => ''

    ), $atts ) );
    $textdomain_shortcode = 'weptile_image_slider_textdomain';
    $default_themes = array('default','light','dark','bar');
    $shortcode_id = 'widget-weptile-image-slider-widget-shortcode-weptile-image-slider-widget-nivo-slider';
    $is_there_a_problem = false;
    $imgs = str_replace(' ', '', $imgs);
    $shortcode_images = explode(',',$imgs);
    if (count($shortcode_images) === 0) {
        $is_there_a_problem = true;
        $error_message = '<p>'. __('No image found for slider', $textdomain_shortcode) .'</p>';
    }

    if ($width < 50 || $height < 50){
        $is_there_a_problem = true;
        $error_message = '<p>'. __('Slider height and/or width are not valid or smaller than 50px', $textdomain_shortcode) .'</p>';
    }


    if ($is_there_a_problem === false) {
        $slider_options = array(
            'width' => $width,
            'height' => $height,
            'theme' => $theme,
            'effect' => $effect,
            'speed' => $speed,
            'duration' => $duration,
            'directional-nav' => $directionalnav,
            'button-nav' => $buttonnav,
            'pause-hover' => $pausehover,
            'start-random' => $startrandom,
            'slices' => $slices,
            'box-columns' => $boxcolumns,
            'box-rows' => $boxrows,
            'prev-text' => $prevtext,
            'next-text' => $nexttext,
            'responsive' => $responsive
        );

        if (empty($slider_options['theme'])) {
            $slider_options['theme'] = 'default';
        }

        if (array_search($slider_options['theme'],$default_themes) === false){
            //if this is a custom theme
            //first check if the specified folder exists
            if (is_dir(WP_PLUGIN_DIR.'/'.$slider_options['theme']))
                wp_register_style('weptile-image-slider-widget-nivo-slider-theme-'.$slider_options['theme'], WP_PLUGIN_URL . '/'.$slider_options['theme'].'/'.$slider_options['theme'].'.css');
            else
                $slider_options['theme'] = 'default';
        }

        wp_enqueue_style('weptile-image-slider-widget-nivo-slider-theme-' . $slider_options['theme']);

        if ($slider_options['responsive'] != '1')
            echo '<style type="text/css" >.slider-wrapper.' . $shortcode_id . '{ width:' . $slider_options['width'] . 'px; /*height:' . $slider_options['height'] . 'px;*/ }</style>';

        echo
            '<div class="slider-wrapper weptile-image-slider-widget-slider-wrapper theme-' . $slider_options['theme'] . ' ' . $shortcode_id . '">'.
            '<div class="nivoSliderWeptile" id="' . $shortcode_id . '">';
        foreach ($shortcode_images  as $shortcode_image) {

            $image_path = str_ireplace(get_site_url(), '', $image);
            $image_path = weptile_get_wp_config_path() . $image_path;

            $image_file_name = substr($image_path, strripos($image_path, '/') + 1);
            $suffix = 'resized-' . $slider_options['width'] . 'x' . $slider_options['height'];


            $image_url = $shortcode_image;
            echo ( !empty($instance['slider-image-links'][$i]) ? '<a href="'.$instance['slider-image-links'][$i].'" target="'.$instance['slider-image-link-targets'][$i].'" rel="'.$instance['slider-image-link-rels'][$i].'" >' : '' );
            echo '<img src="' . $image_url . '" alt="'. (!empty($instance['slider-image-alts'][$i]) ? $instance['slider-image-alts'][$i] : '') .'" title="'. ( !empty($instance['slider-image-captions'][$i]) ? $instance['slider-image-captions'][$i] : '' ) .'" />';
            echo ( !empty($instance['slider-image-links'][$i]) ? '</a>' : '' );
        }
        echo
            '</div>'.
            '</div>';
        echo '<script>
			jQuery(window).load(function() {
				jQuery("#' . $shortcode_id . '").nivoSliderWeptile({
					animSpeed:' . (empty($slider_options['speed']) ? 650 : $slider_options['speed'] ) . ',
					pauseTime:' . (empty($slider_options['duration']) ? 5000 : $slider_options['duration'] ) . ',
					effect:"' . (empty($slider_options['effect']) ? 'fade' : $slider_options['effect'] ) . '",
					prevText: "' . __('Previous', $textdomain_shortcode) . '",
					nextText: "' . __('Next', $textdomain_shortcode) . '",
					directionNav: ' . ($slider_options['directional-nav'] ? 'true' : 'false') . ',
					controlNav: ' . ($slider_options['button-nav'] ? 'true' : 'false') . ',
					pauseOnHover: ' . ($slider_options['pause-hover'] ? 'true' : 'false') . ',
					randomStart: ' . ($slider_options['start-random'] ? 'true' : 'false') . ',
					slices: ' . (empty($slider_options['slices']) ? '15' : $slider_options['slices']) . ',
					boxCols:' . (empty($slider_options['box-columns']) ? '8' : $slider_options['box-columns']) . ',
					boxRows:' . (empty($slider_options['box-rows']) ? '4' : $slider_options['box-rows']) . ',
					prevText: "' . (empty($slider_options['prev-text']) ? 'Previous' : $slider_options['prev-text']) . '",
					nextText: "' . (empty($slider_options['next-text']) ? 'Next' : $slider_options['next-text']) . '"
				});
			});
			</script>';

        // Else
    } else {
        echo $error_message;
    }
}
add_shortcode( 'weptile-slider', 'weptile_image_slider_shortcode' );