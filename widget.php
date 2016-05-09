<?php

namespace PodloveSubscribeButton;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Podlove_Subscribe_Button_Widget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
					'podlove_subscribe_button_wp_plugin_widget',
					( self::is_podlove_publisher_active() ? 'Podlove Subscribe Button' : 'Podlove Subscribe Button (WordPress plugin)' ),
					array( 'description' => __( 'Adds a Podlove Subscribe Button to your Sidebar', 'podlove' ), )
				);
	}

	public static $widget_settings = array('infotext', 'title', 'size', 'style', 'format', 'autowidth', 'button');

	public static function is_podlove_publisher_active() {
		if ( is_plugin_active("podlove-publisher/plugin.php") )
			return true;

		return false;
	}

	public function widget( $args, $instance ) {
		if ( ! $button = ( $button = ( \PodloveSubscribeButton\Model\Button::find_one_by_property('name', $instance['button']) ? \PodloveSubscribeButton\Model\Button::find_one_by_property('name', $instance['button']) : \PodloveSubscribeButton\Model\NetworkButton::find_one_by_property('name', $instance['button']) ) ) )
			return sprintf( __('Oops. There is no button with the ID "%s".', 'podlove'), $args['button'] );

		echo $args['before_widget'];
		echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];

		echo $button->render($instance['size'], $instance['autowidth'], $instance['style'], $instance['format'], $instance['color']);
		
		if ( strlen($instance['infotext']) )
			echo wpautop($instance['infotext']);

		echo $args['after_widget'];
	}	

	public function form( $instance ) {
		foreach (self::$widget_settings as $setting) {
			$$setting = isset( $instance[$setting] ) ? $instance[$setting] : '';
		}

		$buttons = \PodloveSubscribeButton\Model\Button::all();
		$network_buttons = \PodloveSubscribeButton\Model\NetworkButton::all();

		$buttons_as_options = function ($buttons) {
			foreach ($buttons as $subscribebutton) {
				echo "<option value='".$subscribebutton->name."' ".( $subscribebutton->name == $button ? 'selected=\"selected\"' : '' )." >".$subscribebutton->title." (".$subscribebutton->name.")</option>";
			}
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'podlove' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />

			<label for="<?php echo $this->get_field_id( 'button' ); ?>"><?php _e( 'Button', 'podlove' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'button' ); ?>"
				      name="<?php echo $this->get_field_name( 'button' ); ?>">
				<?php if ( isset($network_buttons) && count($network_buttons) > 0 ) : ?>
					<optgroup label="<?php _e('Local', 'podlove'); ?>">
						<?php $buttons_as_options($buttons); ?>
					</optgroup>
					<optgroup label="<?php _e('Network', 'podlove'); ?>">
						<?php $buttons_as_options($network_buttons); ?>
					</optgroup>
				<?php else : 
					$buttons_as_options($buttons);
				 endif; ?>
			</select>

			<?php
			$customize_options = array(
					'size' => array(
							'name' => 'Size',
							'options' => \PodloveSubscribeButton\Model\Button::$sizes
						),
					'style' => array(
							'name' => 'Style',
							'options' => \PodloveSubscribeButton\Model\Button::$styles
						),
					'format' => array(
							'name' => 'Format',
							'options' => \PodloveSubscribeButton\Model\Button::$formats
						),
					'autowidth' => array(
							'name' => 'Autowidth',
							'options' => \PodloveSubscribeButton\Model\Button::$autowidth
						)
				);

			foreach ($customize_options as $slug => $properties) : ?>
				<label for="<?php echo $this->get_field_id( $slug ); ?>"><?php _e( $properties['name'], 'podlove' ); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( $slug ); ?>" name="<?php echo $this->get_field_name( $slug ); ?>">
					<option value="default" <?php echo ( $$slug == 'default' ? 'selected="selected"' : '' ); ?>><?php _e( 'Default ' . $properties['name'], 'podlove' ) ?></option>
					<optgroup>
						<?php foreach ( $properties['options'] as $property => $name ) : ?>
						<option value="<?php echo $property; ?>" <?php echo ( $$slug == $property ? 'selected="selected"' : '' ); ?>><?php _e( $name, 'podlove' ) ?></option>
						<?php endforeach; ?>
					</optgroup>
				</select>
			<?php endforeach; ?>
		
			<label for="<?php echo $this->get_field_id( 'infotext' ); ?>"><?php _e( 'Description', 'podlove' ); ?></label> 
			<textarea class="widefat" rows="10" id="<?php echo $this->get_field_id( 'infotext' ); ?>" name="<?php echo $this->get_field_name( 'infotext' ); ?>"><?php echo $infotext; ?></textarea>
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		foreach (self::$widget_settings as $setting) {
			$instance[$setting]  = ( ! empty( $new_instance[$setting] ) ) ? strip_tags( $new_instance[$setting] ) : '';
		}

		return $instance;
	}
}
add_action( 'widgets_init', function(){
     register_widget( '\PodloveSubscribeButton\Podlove_Subscribe_Button_Widget' );
});