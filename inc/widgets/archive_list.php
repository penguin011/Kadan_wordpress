<?php
/**
 * Archive list (tcd ver)
 */
class Tcdw_Archive_List_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$widget_ops = array( 
			'classname' => 'tcdw_archive_list_widget',
			'discription' => __( 'Displays designed archive dropdown menu.', 'tcd-w' ) 
		);

		parent::__construct(
			'tcdw_archive_list_widget', // ID
			__( 'Archive list (tcd ver)', 'tcd-w' ), // Name
			$widget_ops
		);

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		
		if ( $title ) { 
			echo $args['before_title'] . $title . $args['after_title']; 
		}
		?>
		<div class="p-dropdown">
			<div class="p-dropdown__title"><?php _e( 'Select Month', 'tcd-w' ); ?></div>
			<ul class="p-dropdown__list">
				<?php wp_get_archives( 'type=monthly&format=html&show_post_count=0' ); ?>
			</ul>
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
	
		$title = isset( $instance['title'] ) ? $instance['title'] : '';	
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tcd-w' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>'" type="text" value="<?php echo esc_attr( $title ); ?>">
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
		return $instance;
	}

}

// register tcdw_archive_list_widget
function register_tcdw_archive_list_widget() {
	register_widget( 'Tcdw_Archive_List_Widget' );
}
add_action( 'widgets_init', 'register_tcdw_archive_list_widget' );
