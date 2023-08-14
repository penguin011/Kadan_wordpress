<?php
/**
 * Category list (tcd ver)
 */
class tcdw_category_list_widget extends WP_Widget {

	public function __construct() {

		$widget_ops = array( 
			'classname' => 'tcdw_category_list_widget',
			'description' => __( 'Displays designed category list.', 'tcd-w' )
		);

		parent::__construct(
			'tcdw_category_list_widget', // ID
			__( 'Category list (tcd ver)', 'tcd-w' ), // Name
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
		$cat_args = array(
  		'class' => '',
			'exclude'   => $instance['exclude_cat_num'], // category id to exclude
  		'show_count' => 0,
  		'hierarchical' => 0,
  		'echo' => 0
		);
		$categories = get_categories( $cat_args );

   	echo $args['before_widget'];

    ?>
    <div class="p-list">
		  <?php if ( $title ) { echo $args['before_title'] . $title . $args['after_title']; } ?>
		  <ul>
 			  <?php foreach ( $categories as $cat ) :?>
			  <li><a class="clearfix" href="<?php echo get_term_link( $cat->slug, 'category' ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
			  <?php endforeach; ?>
		  </ul>
    </div>
		<?php
   	echo $args['after_widget'];
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
		$instance['exclude_cat_num'] = strip_tags( $new_instance['exclude_cat_num'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */	
	function form( $instance ) {

		$defaults = array(
			'title' => __( 'Category list', 'tcd-w' ),
			'exclude_cat_num' => ''
		);
  	$instance = wp_parse_args( $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tcd-w' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>'" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_cat_num' ); ?>"><?php _e( 'Categories To Exclude:', 'tcd-w' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'exclude_cat_num' ); ?>" name="<?php echo $this->get_field_name( 'exclude_cat_num' ); ?>'" type="text" value="<?php echo esc_attr( $instance['exclude_cat_num'] ); ?>">
			<span><?php _e( 'Enter a comma-seperated list of category ID numbers, example 2,4,10<br />(Don\'t enter comma for last number).', 'tcd-w' ); ?></span>
		</p>
		<?php
	}
}

/**
 * Register Tcdw_Category_List_Widget widget
 */
function register_tcdw_category_list_widget() {
	register_widget( 'Tcdw_Category_List_Widget' );
}
add_action( 'widgets_init', 'register_tcdw_category_list_widget' );
