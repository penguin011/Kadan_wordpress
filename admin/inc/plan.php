<?php 
/*
 * Manage plan tab
 */

// Add default values
add_filter( 'before_getting_design_plus_option', 'add_plan_dp_default_options' );

//  Add label of plan tab
add_action( 'tcd_tab_labels', 'add_plan_tab_label' );

// Add HTML of plan tab
add_action( 'tcd_tab_panel', 'add_plan_tab_panel' );

// Register sanitize function
add_filter( 'theme_options_validate', 'add_plan_theme_options_validate' );

$ph_writing_type_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
);

function add_plan_dp_default_options( $dp_default_options ) {

  // Archive page
	$dp_default_options['plan_ph_img'] = '';
	$dp_default_options['plan_ph_title'] = __( 'Plan', 'tcd-w' );
	$dp_default_options['plan_ph_font_size'] = 40;
	$dp_default_options['plan_ph_color'] = '#ffffff';
	$dp_default_options['plan_ph_writing_type'] = 'type1';
	$dp_default_options['plan_archive_desc'] = __( 'Here is a plan description. Here is a plan description. Here is a plan description.', 'tcd-w' ) . "\n" . __( 'Here is a plan description. Here is a plan description. Here is a plan description.', 'tcd-w' );
	$dp_default_options['plan_post_num'] = 8;

  // Single page
	$dp_default_options['plan_title_font_size'] = 36;
	$dp_default_options['plan_content_font_size'] = 14;
	$dp_default_options['plan_bg'] = '#f4f1ed';
	$dp_default_options['plan_btn_color'] = '#ffffff';
	$dp_default_options['plan_btn_bg'] = '#000000';
	$dp_default_options['plan_btn_color_hover'] = '#ffffff';
	$dp_default_options['plan_btn_bg_hover'] = '#660000';
	$dp_default_options['plan_archive_link_label'] = '';

	// Display
	$dp_default_options['plan_show_next_post'] = 1;
  $dp_default_options['recommended_plan_headline'] = __( 'Recommened staying plan', 'tcd-w' );
  $dp_default_options['plan_breadcrumb'] = __( 'Plan', 'tcd-w' );
  $dp_default_options['plan_slug'] = 'plan';

	return $dp_default_options;
}

function add_plan_tab_label( $tab_labels ) {
	$tab_labels['plan'] = __( 'Plan', 'tcd-w' );
	return $tab_labels;
}

function add_plan_tab_panel( $dp_options ) {

	global $dp_default_options, $ph_writing_type_options;
?>
<div id="tab-content-plan">
	<?php // Archive page ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Archive page settings', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Background image of the header', 'tcd-w' ); ?></h4>
    <p><?php _e( 'Recommended image size. Width: 1450px, Height: 1100px', 'tcd-w' ); ?></p>
  	<div class="image_box cf">
  		<div class="cf cf_media_field hide-if-no-js plan_ph_img">
  			<input type="hidden" value="<?php echo esc_attr( $dp_options['plan_ph_img'] ); ?>" id="plan_ph_img" name="dp_options[plan_ph_img]" class="cf_media_id">
  			<div class="preview_field"><?php if ( $dp_options['plan_ph_img'] ) { echo wp_get_attachment_image( $dp_options['plan_ph_img'], 'medium' ); } ?></div>
  			<div class="button_area">
  				<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
  				<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $dp_options['plan_ph_img'] ) { echo 'hidden'; } ?>">
  			</div>
  		</div>
  	</div>
    <h4 class="theme_option_headline2"><?php _e( 'Title of the header', 'tcd-w' ); ?></h4>
    <textarea class="regular-text" name="dp_options[plan_ph_title]"><?php echo esc_textarea( $dp_options['plan_ph_title'] ); ?></textarea>
    <p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" class="tiny-text" name="dp_options[plan_ph_font_size]" value="<?php echo esc_attr( $dp_options['plan_ph_font_size'] ); ?>" min="1" step="1"> px</label></p>
    <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[plan_ph_color]" value="<?php echo esc_attr( $dp_options['plan_ph_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['plan_ph_color'] ); ?>"></p>
    <ul>
      <?php foreach ( $ph_writing_type_options as $option ) : ?>
      <li><label><input type="radio" name="dp_options[plan_ph_writing_type]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $dp_options['plan_ph_writing_type'] ); ?>><?php echo esc_html_e( $option['label'] ); ?></label></li>
      <?php endforeach; ?>
    </ul>
    <h4 class="theme_option_headline2"><?php _e( 'Description', 'tcd-w' ); ?></h4>
    <textarea class="large-text" rows="4" name="dp_options[plan_archive_desc]"><?php echo esc_textarea( $dp_options['plan_archive_desc'] ); ?></textarea>
    <h4 class="theme_option_headline2"><?php _e( 'Number of post to show per page', 'tcd-w' ); ?></h4>
    <p><input type="number" min="0" step="1" name="dp_options[plan_post_num]" value="<?php echo esc_attr( $dp_options['plan_post_num'] ); ?>" class="tiny-text"><?php _e( ' posts', 'tcd-w' ); ?></p>
    <input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
	</div>
	<?php // Single page ?>
  <div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Single Page Settings', 'tcd-w' ); ?></h3>
  	<h4 class="theme_option_headline2"><?php _e( 'Font size of post title', 'tcd-w' ); ?></h4>
  	<input class="hankaku tiny-text" type="number" min="1" step="1" name="dp_options[plan_title_font_size]" value="<?php echo esc_attr( $dp_options['plan_title_font_size'] ); ?>"> <span>px</span>
  	<h4 class="theme_option_headline2"><?php _e( 'Font size of post contents', 'tcd-w' ); ?></h4>
  	<input class="hankaku tiny-text" type="number" min="1" step="1" name="dp_options[plan_content_font_size]" value="<?php echo esc_attr( $dp_options['plan_content_font_size'] ); ?>"> <span>px</span>
    <h4 class="theme_option_headline2"><?php _e( 'Background color of the content', 'tcd-w' ); ?></h4>
    <input type="text" class="c-color-picker" name="dp_options[plan_bg]" data-default-color="<?php echo esc_attr( $dp_default_options['plan_bg'] ); ?>" value="<?php echo esc_attr( $dp_options['plan_bg'] ); ?>">
  	<h4 class="theme_option_headline2"><?php _e( 'Button settings of plan information', 'tcd-w' ); ?></h4>
    <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[plan_btn_color]" data-default-color="<?php echo esc_attr( $dp_default_options['plan_btn_color'] ); ?>" value="<?php echo esc_attr( $dp_options['plan_btn_color'] ); ?>"></p>
    <p><?php _e( 'Background color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[plan_btn_bg]" data-default-color="<?php echo esc_attr( $dp_default_options['plan_btn_bg'] ); ?>" value="<?php echo esc_attr( $dp_options['plan_btn_bg'] ); ?>"></p>
    <p><?php _e( 'Font color on hover', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[plan_btn_color_hover]" data-default-color="<?php echo esc_attr( $dp_default_options['plan_btn_color_hover'] ); ?>" value="<?php echo esc_attr( $dp_options['plan_btn_color_hover'] ); ?>"></p>
    <p><?php _e( 'Background color on hover', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[plan_btn_bg_hover]" data-default-color="<?php echo esc_attr( $dp_default_options['plan_btn_bg_hover'] ); ?>" value="<?php echo esc_attr( $dp_options['plan_btn_bg_hover'] ); ?>"></p>
    <h4 class="theme_option_headline2"><?php _e( 'Archive link label', 'tcd-w' ); ?></h4>
	  <p><input type="text" class="regular-text" name="dp_options[plan_archive_link_label]" value="<?php echo esc_attr( $dp_options['plan_archive_link_label'] ); ?>"></p>
    
  	<input type="submit" class="button-ml ajax_button" value="<?php _e( 'Save Changes', 'tcd-w' ); ?>">
	</div>
	<?php // Display ?>
  <div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Display settings', 'tcd-w' ); ?></h3>
    <ul>
    	<li><label><input name="dp_options[plan_show_next_post]" type="checkbox" value="1" <?php checked( '1', $dp_options['plan_show_next_post'] ); ?>><?php _e( 'Display next previous post link', 'tcd-w' ); ?></label></li>
    </ul>
    <h4 class="theme_option_headline2"><?php _e( 'Headline of recommended plan', 'tcd-w' ); ?></h4>
    <p><?php _e( 'The recommended plan is displayed on front, archive, and single page.', 'tcd-w' ); ?>
		<p><input type="text" class="regular-text" name="dp_options[recommended_plan_headline]" value="<?php echo esc_attr( $dp_options['recommended_plan_headline'] ); ?>"></p>
		<h4 class="theme_option_headline2"><?php _e( 'Breadcrumb settings', 'tcd-w' ); ?></h4>
		<p><?php _e( 'It is used in the breadcrumb navigation. If it is not registerd, "Plan" is displayed instead.', 'tcd-w' ); ?></p>
		<p><input type="text" name="dp_options[plan_breadcrumb]" value="<?php echo esc_attr( $dp_options['plan_breadcrumb'] ); ?>"></p>
    <h4 class="theme_option_headline2"><?php _e( 'Slug settings', 'tcd-w' ); ?></h4>
		<p><?php _e( 'It is used in URL. You can use only alphanumeric. If it is not registerd, "plan" is used instead.', 'tcd-w' ); ?></p>
		<p><?php _e( 'Note: if you want to change the slug, change permalinks from "Plain".', 'tcd-w' ); ?></p>
		<p><?php _e( 'Note: after changing the slug, you need to go to "Permalink Settings" and click "Save Changes".', 'tcd-w' ); ?></p>
		<p><input type="text" name="dp_options[plan_slug]" value="<?php echo esc_attr( $dp_options['plan_slug'] ); ?>"></p>
    <input type="submit" class="button-ml ajax_button" value="<?php _e( 'Save Changes', 'tcd-w' ); ?>">
	</div>
</div><!-- END #tab-content4 -->
<?php
}

function add_plan_theme_options_validate( $input ) {

  global $ph_writing_type_options;

  // Archive page
 	$input['plan_ph_img'] = absint( $input['plan_ph_img'] );
 	$input['plan_ph_title'] = sanitize_textarea_field( $input['plan_ph_title'] );
 	$input['plan_ph_font_size'] = absint( $input['plan_ph_font_size'] );
 	$input['plan_ph_color'] = sanitize_hex_color( $input['plan_ph_color'] );
  if ( ! isset( $input['plan_ph_writing_type'] ) ) $input['plan_ph_writing_type'] = null;
  if ( ! array_key_exists( $input['plan_ph_writing_type'], $ph_writing_type_options ) ) $input['plan_ph_writing_type'] = null;
 	$input['plan_archive_desc'] = sanitize_textarea_field( $input['plan_archive_desc'] );
 	$input['plan_post_num'] = absint( $input['plan_post_num'] );

  // Single page
 	$input['plan_title_font_size'] = absint( $input['plan_title_font_size'] );
 	$input['plan_content_font_size'] = absint( $input['plan_content_font_size'] );
 	$input['plan_bg'] = sanitize_hex_color( $input['plan_bg'] );
 	$input['plan_archive_link_label'] = sanitize_text_field( $input['plan_archive_link_label'] );
 	$input['plan_btn_color'] = sanitize_hex_color( $input['plan_btn_color'] );
 	$input['plan_btn_bg'] = sanitize_hex_color( $input['plan_btn_bg'] );
 	$input['plan_btn_color_hover'] = sanitize_hex_color( $input['plan_btn_color_hover'] );
 	$input['plan_btn_bg_hover'] = sanitize_hex_color( $input['plan_btn_bg_hover'] );

 	// Display
 	if ( ! isset( $input['plan_show_next_post'] ) ) $input['plan_show_next_post'] = null;
  $input['plan_show_next_post'] = ( $input['plan_show_next_post'] == 1 ? 1 : 0 );
 	$input['recommended_plan_headline'] = sanitize_text_field( $input['recommended_plan_headline'] );
 	$input['plan_breadcrumb'] = sanitize_text_field( $input['plan_breadcrumb'] );
 	$input['plan_slug'] = sanitize_text_field( $input['plan_slug'] );

	return $input;
}
