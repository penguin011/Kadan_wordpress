<?php
/**
 * Google custom search (tcd ver)
 */
class google_search extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$widget_ops = array( 
			'classname' => 'google_search_widget',
			'description' => __( 'Displays Google Custom Search form.', 'tcd-w' )
		);

		parent::__construct(
			'google_search_widget', // ID
			__( 'Google Custom Search (tcd ver)', 'tcd-w' ), // Name
			$widget_ops
		);

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
 	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );
		$google_search_id = $instance['google_search_id'];

		echo $args['before_widget'];
		
		if ( $title ) { 
			echo $args['before_title'] . $title . $args['after_title']; 
		}
		?>
   	<div class="p-widget-search">
			<form action="https://cse.google.com/cse" method="get">
  	 		<div>
  	  		<input class="p-widget-search__input" type="text" value="" name="q">
  	  		<input class="p-widget-search__submit" type="submit" name="sa" value="&#xe915;">
  	  		<input type="hidden" name="cx" value="<?php echo esc_attr( $google_search_id ); ?>">
  	  		<input type="hidden" name="ie" value="UTF-8">
  	  	</div>
  	 	</form>
    </div>
		<?php
   	echo $args['after_widget'];
	} 

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */	
	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Search', 'tcd-w' );
		$google_search_id = isset( $instance['google_search_id'] ) ? $instance['google_search_id'] : '';
		?>
		<p><?php _e( 'If you want to use google custom search for your WordPress, enter your google custom search ID.<br><a href="http://www.google.com/cse/" target="_blank">Read more about Google custom search page.</a>', 'tcd-w' ); ?></p>
		<p>
 			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tcd-w' ); ?></label>
 			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>'" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'google_search' ); ?>"><?php _e( 'Google custom search ID', 'tcd-w' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'google_search_id' ); ?>" name="<?php echo $this->get_field_name( 'google_search_id' ); ?>" type="text" value="<?php echo esc_attr( $google_search_id ); ?>">
		</p>
		<?php
 	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['google_search_id'] = strip_tags( $new_instance['google_search_id'] );
		return $instance;
	}
}

// register google_search widget
function register_google_search_widget() {
	register_widget( 'google_search' );
}
add_action( 'widgets_init', 'register_google_search_widget' );
