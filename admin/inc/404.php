<?php 
/*
 * Manage 404 tab
 */

// Add default values
add_filter( 'before_getting_design_plus_option', 'add_404_dp_default_options' );

// Add label of 404 tab
add_action( 'tcd_tab_labels', 'add_404_tab_label' );

// Add HTML of 404 tab
add_action( 'tcd_tab_panel', 'add_404_tab_panel' );

// Register sanitize function
add_filter( 'theme_options_validate', 'add_404_theme_options_validate' );

$ph_writing_type_options = array(
  'type1' => array( 'value' => 'type1', 'label' => __( 'Vertical writing', 'tcd-w' ) ),
  'type2' => array( 'value' => 'type2', 'label' => __( 'Horizontal writing', 'tcd-w' ) )
);

function add_404_dp_default_options( $dp_default_options ) {

	$dp_default_options['404_ph_img'] = '';
	$dp_default_options['404_ph_title'] = '404 Not Found';
	$dp_default_options['404_ph_font_size'] = 40;
	$dp_default_options['404_ph_color'] = '#ffffff';
	$dp_default_options['404_ph_writing_type'] = 'type2';
	$dp_default_options['404_archive_desc'] = __( 'Sorry, the page you are looking for is not found.', 'tcd-w' );

	return $dp_default_options;
}

function add_404_tab_label( $tab_labels ) {
	$tab_labels['404'] = __( '404 page', 'tcd-w' );
	return $tab_labels;
}

function add_404_tab_panel( $dp_options ) {
	global $dp_default_options, $ph_writing_type_options;
?>
<div id="tab-content-404">
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Archive page settings', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Background image of the header', 'tcd-w' ); ?></h4>
    <p><?php _e( 'Recommended image size. Width: 1450px, Height: 1100px', 'tcd-w' ); ?></p>
  	<div class="image_box cf">
  		<div class="cf cf_media_field hide-if-no-js 404_ph_img">
  			<input type="hidden" value="<?php echo esc_attr( $dp_options['404_ph_img'] ); ?>" id="404_ph_img" name="dp_options[404_ph_img]" class="cf_media_id">
  			<div class="preview_field"><?php if ( $dp_options['404_ph_img'] ) { echo wp_get_attachment_image( $dp_options['404_ph_img'], 'medium' ); } ?></div>
  			<div class="button_area">
  				<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
  				<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $dp_options['404_ph_img'] ) { echo 'hidden'; } ?>">
  			</div>
  		</div>
  	</div>
    <h4 class="theme_option_headline2"><?php _e( 'Title of the header', 'tcd-w' ); ?></h4>
    <textarea class="regular-text" name="dp_options[404_ph_title]"><?php echo esc_textarea( $dp_options['404_ph_title'] ); ?></textarea>
    <p><label><?php _e( 'Font size', 'tcd-w' ); ?> <input type="number" class="tiny-text" name="dp_options[404_ph_font_size]" value="<?php echo esc_attr( $dp_options['404_ph_font_size'] ); ?>" min="1" step="1"> px</label></p>
    <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[404_ph_color]" value="<?php echo esc_attr( $dp_options['404_ph_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['404_ph_color'] ); ?>"></p>
    <ul>
      <?php foreach ( $ph_writing_type_options as $option ) : ?></p>
      <li><label><input type="radio" name="dp_options[404_ph_writing_type]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $dp_options['404_ph_writing_type'] ); ?>><?php echo esc_html_e( $option['label'] ); ?></label></li>
      <?php endforeach; ?>
    </ul>
    <h4 class="theme_option_headline2"><?php _e( 'Description', 'tcd-w' ); ?></h4>
    <textarea class="large-text" rows="4" name="dp_options[404_archive_desc]"><?php echo esc_textarea( $dp_options['404_archive_desc'] ); ?></textarea>
    <input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
	</div>
</div><!-- END #tab-content4 -->
<?php
}

function add_404_theme_options_validate( $input ) {

  global $ph_writing_type_options;

 	$input['404_ph_img'] = absint( $input['404_ph_img'] );
 	$input['404_ph_title'] = sanitize_textarea_field( $input['404_ph_title'] );
 	$input['404_ph_font_size'] = absint( $input['404_ph_font_size'] );
 	$input['404_ph_color'] = sanitize_hex_color( $input['404_ph_color'] );
  if ( ! isset( $input['404_ph_writing_type'] ) ) $input['404_ph_writing_type'] = null;
  if ( ! array_key_exists( $input['404_ph_writing_type'], $ph_writing_type_options ) ) $input['404_ph_writing_type'] = null;
 	$input['404_archive_desc'] = sanitize_textarea_field( $input['404_archive_desc'] );

	return $input;
}
