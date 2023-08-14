<?php
/**
 * AdSense (tcd ver)
 */
class ad_widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$widget_ops = array( 
			'classname' => 'ad_widget',
			'description' => __( 'Show AdSense at random in front page.', 'tcd-w' )
		);
		parent::__construct( 
			'ad_widget', // ID
			__( 'AdSense (tcd ver)', 'tcd-w' ), // Name
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

		/**
		 * コード3 or 画像3 の登録あり => 広告1〜3の中から表示
		 * コード2 or 画像2 の登録あり => 広告1〜2の中から表示
		 * コード1 or 画像1 の登録あり => 広告1を表示
		 */ 

		// どの広告を表示するか、1〜3の数字が入る
		$random = '';

		for ( $i = 3; $i >= 1; $i-- ) {
			if ( $instance['banner_code' . $i] || $instance['banner_image' . $i] ) {
				$random = rand( 1, $i );
				break; 
			}			
		}

		if ( ! $random ) return;

		$banner_code = $instance['banner_code' . $random];
		$banner_image = wp_get_attachment_url( $instance['banner_image' . $random] );
		$banner_url = $instance['banner_url' . $random];

   	echo $args['before_widget'];

		if ( $banner_code ) {
			echo $banner_code;
		} else {
			echo '<a href="' . esc_url( $banner_url ). '" target="_blank"><img src="' . esc_attr( $banner_image ) . '" alt=""></a>';
		}

		echo $args['after_widget'];

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */	
	public function form( $instance ) {

		for ( $i = 1; $i <= 3; $i++ ) {
			${ 'banner_code' . $i } = isset( $instance['banner_code' . $i] ) ? $instance['banner_code' . $i] : ''; 
			${ 'banner_image' . $i } = isset( $instance['banner_image' . $i] ) ? $instance['banner_image' . $i] : ''; 
			${ 'banner_url' . $i } = isset( $instance['banner_url' . $i] ) ? $instance['banner_url' . $i] : ''; 
		}	
		?>
		<p><?php _e( 'One out of three AdSense will be displayed at random in front page.', 'tcd-w' ); ?></p>
		<div class="a-widget-box">
		  <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<h4 class="a-widget-box__headline"><?php _e( 'AdSense','tcd-w' ); ?><?php echo $i; ?></h4>
			<div class="a-widget-box__content">
    		<h5 class="a-widget-box__sub-headline"><?php _e( 'Register AdSense code','tcd-w' ); ?></h5>
    		<p><?php _e( 'If you are using Google AdSense or similar kind of AdSense, enter all code below.', 'tcd-w' ); ?></p>
    		<p><textarea id="<?php echo $this->get_field_id( 'banner_code' . $i ); ?>" class="widefat" rows="10" name="<?php echo $this->get_field_name( 'banner_code' . $i ); ?>"><?php echo ${ 'banner_code' . $i }; ?></textarea></p>
  			<p class="a-widget-box__notice"><?php _e( 'If you want to register banner image and affiliate code individually, leave the field above blank and use the field below.', 'tcd-w' ); ?></p>
    		<h4 class="a-widget-box__sub-headline"><?php _e( 'Register AdSense image', 'tcd-w' ); ?></h4>
      	<div class="a-widget-box__upload cf cf_media_field hide-if-no-js <?php echo $this->get_field_id( 'banner_image' . $i ); ?>">
       		<input type="hidden" id="<?php echo $this->get_field_id( 'banner_image' . $i ); ?>" class="cf_media_id" name="<?php echo $this->get_field_name( 'banner_image' . $i ); ?>" value="<?php echo esc_attr( ${ 'banner_image' . $i } ); ?>">
       		<div class="preview_field"><?php if ( ${ 'banner_image' . $i } ) { echo wp_get_attachment_image( ${ 'banner_image' . $i }, 'medium' ); } ?></div>
					<div class="button_area">
        		<input type="button" class="cfmf-select-img button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>">
        		<input type="button" class="cfmf-delete-img button <?php if ( ! ${ 'banner_image' . $i } ) { echo 'hidden'; } ?>" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>">
       		</div>
     		</div>
   			<h4 class="a-widget-box__sub-headline"><?php _e( 'Register affiliate code or link url for registered image', 'tcd-w' ); ?></h4>
   			<input type="text" id="<?php echo $this->get_field_id( 'banner_url' . $i ); ?>" class="img widefat" name="<?php echo $this->get_field_name( 'banner_url' . $i ); ?>" value="<?php echo esc_attr( ${ 'banner_url' . $i } ); ?>">
			</div>
		  <?php endfor; ?>
		</div>
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

			for ( $i = 1; $i <= 3; $i++ ) {
  			$instance['banner_code' . $i] = $new_instance['banner_code' . $i];
  			$instance['banner_image' . $i] = strip_tags( $new_instance['banner_image' . $i] );
  			$instance['banner_url' . $i] = strip_tags( $new_instance['banner_url' . $i] );
			}

  		return $instance;
	}
}

// register ad_widget widget
function register_ad_widget() {
	register_widget( 'ad_widget' );
}
add_action( 'widgets_init', 'register_ad_widget' );
