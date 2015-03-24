<?php
/*---------------------------------------------------------------------------------*/
/* My YouTube Videos Widget */
/*---------------------------------------------------------------------------------*/
class OceanThemes_My_YouTube_Videos_Widget extends WP_Widget{
	function OceanThemes_My_YouTube_Videos_Widget(){

		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_my_youtube', 'description' => 'Display your latest videos from your YouTube account in the sidebar.');
		/* Widget control settings. */
		$control_ops = array('id_base' => 'oceanthemes-my_youtube-widget');
		/* Create the widget. */
		$this->WP_Widget('oceanthemes-my_youtube-widget', 'My YouTube Videos', $widget_ops, $control_ops);
	}

	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$username = $instance['username'];
		$display_many_yt = $instance['myyt_display_many'];
		$thumbnail = $instance['myyt_display_thumb'];
		$dateuploaded = $instance['myyt_display_dateadded'];
		$enable_hd = $instance['myyt_enable_hd'];
	
		$children = '<div id="my_youtube_videos" class="widget">';
		$children .= "<ul>";
		/* Require RSS Feed */
		require_once(ABSPATH.WPINC.'/rss.php');
		$rss = fetch_rss("http://gdata.youtube.com/feeds/base/users/".$username."/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile");
		if(!empty($rss)){
			$items = array_slice($rss->items, 0, $display_many_yt);
			foreach($items as $item){
				$video_link = clean_url($item['link'], $protocolls = null, 'display');
				$video_id = str_replace("http://www.youtube.com/watch?v=", "", "$video_link");					 /* Removes the beginning part of the video url. */
				$video_id = str_replace("feature=youtube_gdata", "", "$video_id");								 /* Removes the end of the video url. */
				if($enable_hd == 'yes'){
					$enableHD = str_replace("feature=youtube_gdata", "feature=youtube_gdata&hd=1", $video_link); /* Removes the end of the video url. */
					$video_link = $enableHD;
				}
				$children .= "<li>";
				$video_id = preg_replace('/&/', '&amp;', $video_id);/* Rewrites the '&' symbol. */
				$video_id = str_replace('&amp;', '', $video_id);	/* Removes the '&' symbol. */
				$video_id = str_replace('#038;', '', $video_id);	/* Removes the garbage put in place of '&' . */
				if($thumbnail == 'yes'){							/* Displays latest video thumbnail linked to the video. */
					$children .= '<a target="_blank" href="'.$video_link.'" title="'.htmlentities($item['title']).'"><img class="latest_yt" src="http://i.ytimg.com/vi/'.$video_id.'/default.jpg" width="120" height="90" /></a><br />';
				}
				/* Title of Video */
				$children .= '<a target="_blank" href="'.$video_link.'" title="'.htmlentities($item['title']).'">'.htmlentities($item['title']).'</a><br />';
				/* Date of video uploaded. */
				if($dateuploaded == 'yes'){ $children .= 'Added: <em>('.date('M j, Y', strtotime($item['pubdate'])).')</em><br />'; }
				/* Description of the Video */
				$description = strip_tags($item['description']);
				$description = str_replace(htmlentities($item['title']), "", $description);
				/* Removes End Description */
				$description = str_replace('From:', "", $description);
				$description = str_replace($username, "", $description);
				$description = str_replace('Views:', "", $description);
				$description = str_replace('Time:', "", $description);
				$description = str_replace('ratings', "", $description);
				$description = str_replace('More in', "", $description);
				//$children .= '<span style="font-size:12px;">'.$description.'</span><br />';
				/* Video Length */
				//$findtext = 'Time:';
				//$pos = strpos($findtext, '');
				$videolength = substr("Time:", 5, -5);
				//$uploadedby = str_replace('From: '.$username, "", $uploadedby);
				//$children .= '<span style="font-size:12px;">Time: <em>('.$videolength.')</em></span><br />';
				$children .= "</li>";
			}
		}
		else{
			$children .= "<li>YouTube Feed not found! Please try again later</li>\n";
		}
		$children .= "</ul>";
		$children .= '</div>';

		echo $before_widget;
		if(empty($title)){ $title = _e('My Latest Videos on <span>YouTube<span>', 'oceanthemes_my_yt_vids'); }
		echo $before_title . $title . $after_title;
		echo $children;
		echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['myyt_display_many'] = esc_attr($new_instance['myyt_display_many']);
		$instance['myyt_display_thumb'] = esc_attr($new_instance['myyt_display_thumb']);
		$instance['myyt_display_dateadded'] = esc_attr($new_instance['myyt_display_dateadded']);
		$instance['myyt_enable_hd'] = esc_attr($new_instance['myyt_enable_hd']);

		return $instance;
	}

	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array(
						'title' => '',
						'username' => 'sebbyd86',
						'myyt_display_many' => '4',
						'myyt_display_thumb' => 'yes',
						'myyt_display_dateadded' => 'yes',
						'myyt_enable_hd' => 'no'
					);
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'oceanthemes'); ?></label>
	<input id="<?php echo $this->get_field_id('title'); ?>" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:', 'oceanthemes'); ?> <input id="<?php echo $this->get_field_id('username'); ?>" class="widefat" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $instance['username']; ?>" /></label></p>
	<p><label for="<?php echo $this->get_field_id('myyt_display_many'); ?>"><?php _e('Number of Videos:', 'oceanthemes'); ?>
	<select name="<?php echo $this->get_field_name('myyt_display_many'); ?>" class="widefat" id="<?php echo $this->get_field_id('myyt_display_many'); ?>">
	<?php for($i = 1; $i <= 10; $i += 1){ ?>
	<option value="<?php echo $i; ?>"<?php if($instance['myyt_display_many'] == $i){ echo " selected='selected'"; } ?>><?php echo $i; ?></option>
	<?php } ?>
	</select>
	</p>
	<p><label for="<?php echo $this->get_field_id('myyt_display_thumb'); ?>"><?php _e('Display Thumbnail:', 'oceanthemes'); ?></label>
	<select name="<?php echo $this->get_field_name('myyt_display_thumb'); ?>" class="widefat" id="<?php echo $this->get_field_id('myyt_display_thumb'); ?>">
	<option value="yes"<?php if($instance['myyt_display_thumb'] == "yes"){ echo " selected='selected'"; } ?>><?php _e('Yes', 'oceanthemes'); ?></option>
	<option value="no"<?php if($instance['myyt_display_thumb'] == "no"){ echo " selected='selected'"; } ?>><?php _e('No', 'oceanthemes'); ?></option>
	</select></p>
	<p><label for="<?php echo $this->get_field_id('myyt_display_dateadded'); ?>"><?php _e('Display Date Added:', 'oceanthemes'); ?></label>
	<select name="<?php echo $this->get_field_name('myyt_display_dateadded'); ?>" class="widefat" id="<?php echo $this->get_field_id('myyt_display_dateadded'); ?>">
	<option value="yes"<?php if($instance['myyt_display_dateadded'] == "yes"){ echo " selected='selected'"; } ?>><?php _e('Yes', 'oceanthemes'); ?></option>
	<option value="no"<?php if($instance['myyt_display_dateadded'] == "no"){ echo " selected='selected'"; } ?>><?php _e('No', 'oceanthemes'); ?></option>
	</select>
	</p>
	<p><label for="<?php echo $this->get_field_id('myyt_enable_hd'); ?>"><?php _e('HD Videos:','oceanthemes'); ?></label>
	<select name="<?php echo $this->get_field_name('myyt_enable_hd'); ?>" class="widefat" id="<?php echo $this->get_field_id('myyt_enable_hd'); ?>">
	<option value="yes"<?php if($instance['myyt_enable_hd'] == "yes"){ echo " selected='selected'"; } ?>><?php _e('Yes', 'oceanthemes'); ?></option>
	<option value="no"<?php if($instance['myyt_enable_hd'] == "no"){ echo " selected='selected'"; } ?>><?php _e('No', 'oceanthemes'); ?></option>
	</select>
	</p>
<?php
	}
}
/* Add action to initiate the widget. */
add_action('widgets_init', 'ot_load_myytvid_widget');
/* Register Widget. */
function ot_load_myytvid_widget(){
	register_widget('OceanThemes_My_YouTube_Videos_Widget');
}

?>