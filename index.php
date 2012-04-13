<?php
/*
Plugin Name: Single Post Widget
Plugin URI: http://sjstrutt.com/sjs-single-post
Description: Include a single post or page in a widget area
Version: 0.8
Author: Steve Strutt
Author URI: http://sjstrutt.com
License: All Rights Reserved
*/


/**
 * Adds sjs_single_post widget.
 */
class sjs_single_post extends WP_Widget {

	// Register widget with WordPress.
	public function __construct() {
		parent::__construct(
	 		'sjs_single_post', // Base ID
			'Single Post/Page', // Name
			array( 'description' => __( 'Include a single post or page in a widget area', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		
		if(!is_null($instance['widgetpostid']) && $instance['widgetpostid'] != '') {
			$queried_post = 'page_id='.$instance['widgetpostid'];
	    	$latestPosts = new WP_Query();
	    	$latestPosts->query($queried_post);
			while ($latestPosts->have_posts()) : $latestPosts->the_post();
				if($instance['widgetdisplayfirst'] == 'titlefirst') {
					if($instance['widgetposttitle'] == 'show') { the_title(); }
					if($instance['widgetthumbnail'] == 'show') { the_post_thumbnail( array($instance['widgetthumbnailwidth'],$instance['widgetthumbnailheight']) ); }
				} else {
					if($instance['widgetposttitle'] == 'show') { the_title(); }
					if($instance['widgetthumbnail'] == 'show') { the_post_thumbnail( array($instance['widgetthumbnailwidth'],$instance['widgetthumbnailheight']) ); }
				}
				the_content();
			endwhile;
		}

	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['widgetpostid'] 			= preg_replace('/\D/', '', $new_instance['widgetpostid']);
		$instance['widgetposttitle'] 		= $new_instance['widgetposttitle'];
		$instance['widgetthumbnail'] 		= $new_instance['widgetthumbnail'];
		$instance['widgetthumbnailheight'] 	= $new_instance['widgetthumbnailheight'];
		$instance['widgetthumbnailwidth'] 	= $new_instance['widgetthumbnailwidth'];
		$instance['widgetdisplayfirst'] 	= $new_instance['widgetdisplayfirst'];
		return $instance;
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		if (isset($instance['widgetpostid']))
			{ $widgetpostid = $instance['widgetpostid']; }
		else { $widgetpostid = '';}

		if (isset($instance['widgetposttitle']))
			{ $widgetposttitle = $instance['widgetposttitle']; }
		else { $widgetposttitle = 'hide';}

		if (isset($instance['widgetthumbnail']))
			{ $widgetthumbnail = $instance['widgetthumbnail']; }
		else { $widgetthumbnail = 'show';}

		if (isset($instance['widgetthumbnailheight']))
			{ $widgetthumbnailheight = $instance['widgetthumbnailheight']; }
		else { $widgetthumbnailheight = '196';}

		if (isset($instance['widgetthumbnailwidth']))
			{ $widgetthumbnailwidth = $instance['widgetthumbnailwidth']; }
		else { $widgetthumbnailwidth = '365';}

		if (isset($instance['widgetdisplayfirst']))
			{ $widgetdisplayfirst = $instance['widgetdisplayfirst']; }
		else { $widgetdisplayfirst = 'thumbfirst'; } ?>
		<style>#sjs-single-post select { width:100%;}</style>
		<div id="sjs-single-post">
			<label for="<?php echo $this->get_field_id( 'widgetpostid' ); ?>"><?php _e( 'Post/Page:' ); ?></label> <?php
				$args = array(
			    	'depth'            => 0,
			    	'child_of'         => 0,
			    	'selected'         => $widgetpostid,
		    		'echo'             => 1,
		    		'name'             => $this->get_field_name( 'widgetpostid' ));
				wp_dropdown_pages( $args ); ?><br /><br />

			<label for="<?php echo $this->get_field_id( 'widgetposttitle' ); ?>"><?php _e( 'Post Title:' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'widgetposttitle' ); ?>" name="<?php echo $this->get_field_name( 'widgetposttitle' ); ?>">
				<option value="show"<?php if($widgetposttitle == 'show') { echo ' selected';} ?>>Show Post Title</option>
				<option value="hide"<?php if($widgetposttitle == 'hide') { echo ' selected';} ?>>Hide Post Title</option>
			</select><br/><br/>

			<label for="<?php echo $this->get_field_id( 'widgetthumbnail' ); ?>"><?php _e( 'Featured Image:' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'widgetthumbnail' ); ?>" name="<?php echo $this->get_field_name( 'widgetthumbnail' ); ?>">
				<option value="show"<?php if($widgetthumbnail == 'show') { echo ' selected';} ?>>Show Post Thumbnail</option>
				<option value="hide"<?php if($widgetthumbnail == 'hide') { echo ' selected';} ?>>Hide Post Thumbnail</option>
			</select>

			<label for="<?php echo $this->get_field_id( 'widgetthumbnailheight' ); ?>"><?php _e( 'Height:' ); ?></label> 
			<input class="" id="<?php echo $this->get_field_id( 'widgetthumbnailheight' ); ?>" name="<?php echo $this->get_field_name( 'widgetthumbnailheight' ); ?>" type="text" size="3" value="<?php echo esc_attr( $widgetthumbnailheight ); ?>" />
			<label for="<?php echo $this->get_field_id( 'widgetthumbnailwidth' ); ?>"><?php _e( 'Width:' ); ?></label> 
			<input class="" id="<?php echo $this->get_field_id( 'widgetthumbnailwidth' ); ?>" name="<?php echo $this->get_field_name( 'widgetthumbnailwidth' ); ?>" type="text" size="3" value="<?php echo esc_attr( $widgetthumbnailwidth ); ?>" /><br/><br/>

			<label for="<?php echo $this->get_field_id( 'widgetdisplayfirst' ); ?>"><?php _e( 'Order Post Title & Thumbnail:' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'widgetdisplayfirst' ); ?>" name="<?php echo $this->get_field_name( 'widgetdisplayfirst' ); ?>">
				<option value="thumbfirst"<?php if($widgetdisplayfirst == 'thumbfirst') { echo ' selected';} ?>>Post Thumbnail First</option>
				<option value="titlefirst"<?php if($widgetdisplayfirst == 'titlefirst') { echo ' selected';} ?>>Post Title First</option>
			</select>
		</div>

<?php }

} 

// register sjs_single_post widget
add_action( 'widgets_init', create_function( '', 'register_widget( "sjs_single_post" );' ) );

?>