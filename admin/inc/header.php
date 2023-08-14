<?php 
/*
 * Manage header tab
 */

// Add default values
add_filter( 'before_getting_design_plus_option', 'add_header_dp_default_options' );

// Add label of header tab
add_action( 'tcd_tab_labels', 'add_header_tab_label' );

// Add HTML of header tab
add_action( 'tcd_tab_panel', 'add_header_tab_panel' );

// Register sanitize function
add_filter( 'theme_options_validate', 'add_header_theme_options_validate' );

// Header position
global $header_fix_options;
$header_fix_options = array(
	'type1' => array(
		'value' => 'type1',
		'label' => __( 'Normal header', 'tcd-w' ) 
	),
 	'type2' => array(
		'value' => 'type2',
		'label' => __( 'Fix at top after page scroll', 'tcd-w' )
	),
);

function add_header_dp_default_options( $dp_default_options ) {

  // Header
	$dp_default_options['header_fix'] = 'type1';
	$dp_default_options['sp_header_fix'] = 'type1';
	$dp_default_options['header_bg'] = '#000000';
	$dp_default_options['header_bg_opacity'] = 1;

  // Global navigation
	$dp_default_options['gnav_color'] = '#ffffff';
	$dp_default_options['gnav_color_hover'] = '#ffffff';
	$dp_default_options['gnav_bg_hover'] = '#660000';
	$dp_default_options['gnav_sub_color'] = '#ffffff';
	$dp_default_options['gnav_sub_bg'] = '#111111';
	$dp_default_options['gnav_sub_color_hover'] = '#ffffff';
	$dp_default_options['gnav_sub_bg_hover'] = '#660000';
	$dp_default_options['gnav_color_sp'] = '#ffffff';
	$dp_default_options['gnav_bg_sp'] = '#000000';
	$dp_default_options['gnav_bg_opacity_sp'] = 1;

	return $dp_default_options;
}

function add_header_tab_label( $tab_labels ) {
	$tab_labels['header'] = __( 'Header', 'tcd-w' );
	return $tab_labels;
}

function add_header_tab_panel( $options ) {

	global $dp_default_options, $header_fix_options; 
?>
<div id="tab-content-header">
  <?php // Header ?>
  <div class="theme_option_field cf">
    <h3 class="theme_option_headline"><?php _e( 'Header settings', 'tcd-w' ); ?></h3>
  	<h4 class="theme_option_headline2"><?php _e( 'Header position', 'tcd-w' ); ?></h4>
   	<fieldset class="cf select_type2">
			<?php foreach ( $header_fix_options as $option ) : ?>
     	<label class="description"><input type="radio" name="dp_options[header_fix]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php checked( $option['value'], $options['header_fix'] ); ?>><?php _e( $option['label'], 'tcd-w' ); ?></label>
			<?php endforeach; ?>
    </fieldset>
  	<h4 class="theme_option_headline2"><?php _e( 'Header position (mobile)', 'tcd-w' ); ?></h4>
  	<fieldset class="cf select_type2">
			<?php foreach ( $header_fix_options as $option ) : ?>
			<label class="description"><input type="radio" name="dp_options[sp_header_fix]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $options['sp_header_fix'] ); ?>><?php esc_html_e( $option['label'], 'tcd-w' ); ?></label>
			<?php endforeach; ?>
		</fieldset>
  	<h4 class="theme_option_headline2"><?php _e( 'Background color', 'tcd-w' ); ?></h4>
		<input type="text" name="dp_options[header_bg]" value="<?php echo esc_attr( $options['header_bg'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['header_bg'] ); ?>" class="c-color-picker">
    <p><?php _e( 'Opacity of background color', 'tcd-w' ); ?> <input type="number" min="0" max="1" step="0.1" name="dp_options[header_bg_opacity]" value="<?php echo esc_attr( $options['header_bg_opacity'] ); ?>"></p> 
  	<input type="submit" class="button-ml ajax_button" value="<?php _e( 'Save Changes', 'tcd-w' ); ?>">
  </div>
  <?php // Global navigation ?>
  <div class="theme_option_field cf">
    <h3 class="theme_option_headline"><?php _e( 'Global navigation settings', 'tcd-w' ); ?></h3>
    <p><label for="gnav_color"><?php _e( 'Font color', 'tcd-w' ); ?> </label><input type="text" id="gnav_color" class="c-color-picker" name="dp_options[gnav_color]" value="<?php echo esc_attr( $options['gnav_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_color'] ); ?>"></p>
    <p><label for="gnav_color_hover"><?php _e( 'Font color on hover', 'tcd-w' ); ?> </label><input type="text" id="gnav_color_hover" class="c-color-picker" name="dp_options[gnav_color_hover]" value="<?php echo esc_attr( $options['gnav_color_hover'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_color_hover'] ); ?>"></p>
    <p><label for="gnav_bg_hover"><?php _e( 'Background color on hover', 'tcd-w' ); ?> </label><input type="text" class="c-color-picker" name="dp_options[gnav_bg_hover]" value="<?php echo esc_attr( $options['gnav_bg_hover'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_bg_hover'] ); ?>"></p>
  	<h4 class="theme_option_headline2"><?php _e( 'Submenu settings', 'tcd-w' ); ?></h4>
    <p><label for="gnav_sub_color"><?php _e( 'Font color', 'tcd-w' ); ?> </label><input type="text" id="gnav_sub_color" class="c-color-picker" name="dp_options[gnav_sub_color]" value="<?php echo esc_attr( $options['gnav_sub_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_sub_color'] ); ?>"></p>
    <p><label for="gnav_sub_bg"><?php _e( 'Background color', 'tcd-w' ); ?> </label><input type="text" id="gnav_sub_bg" class="c-color-picker" name="dp_options[gnav_sub_bg]" value="<?php echo esc_attr( $options['gnav_sub_bg'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_sub_bg'] ); ?>"></p>
    <p><label for="gnav_sub_color_hover"><?php _e( 'Font color on hover', 'tcd-w' ); ?> </label><input type="text" id="gnav_sub_color_hover" class="c-color-picker" name="dp_options[gnav_sub_color_hover]" value="<?php echo esc_attr( $options['gnav_sub_color_hover'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_sub_color_hover'] ); ?>"></p>
    <p><label for="gnav_sub_bg_hover"><?php _e( 'Background color on hover', 'tcd-w' ); ?> </label><input type="text" class="c-color-picker" name="dp_options[gnav_sub_bg_hover]" value="<?php echo esc_attr( $options['gnav_sub_bg_hover'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_sub_bg_hover'] ); ?>"></p>
  	<h4 class="theme_option_headline2"><?php _e( 'Menu setting for mobile', 'tcd-w' ); ?></h4>
    <p><label for="gnav_color_sp"><?php _e( 'Font color', 'tcd-w' ); ?> </label><input type="text" class="c-color-picker" name="dp_options[gnav_color_sp]" value="<?php echo esc_attr( $options['gnav_color_sp'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_color_sp'] ); ?>"></p>
    <p><label for="gnav_bg_sp"><?php _e( 'Background color', 'tcd-w' ); ?> </label><input type="text" class="c-color-picker" name="dp_options[gnav_bg_sp]" value="<?php echo esc_attr( $options['gnav_bg_sp'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['gnav_bg_sp'] ); ?>"></p>
    <p><label><?php _e( 'Opacity of background color', 'tcd-w' ); ?> <input type="number" class="tiny-text" min="0" max="1" step="0.1" name="dp_options[gnav_bg_opacity_sp]" value="<?php echo esc_attr( $options['gnav_bg_opacity_sp'] ); ?>"></label></p>
  	<input type="submit" class="button-ml ajax_button" value="<?php _e( 'Save Changes', 'tcd-w' ); ?>">
  </div>
</div><!-- END #tab-content7 -->
<?php
}

function add_header_theme_options_validate( $input ) {

  global $header_fix_options;

  // Header
 	if ( ! isset( $input['header_fix'] ) ) $input['header_fix'] = null;
 	if ( ! array_key_exists( $input['header_fix'], $header_fix_options ) ) $input['header_fix'] = null;
 	if ( ! isset( $input['sp_header_fix'] ) ) $input['sp_header_fix'] = null;
 	if ( ! array_key_exists( $input['sp_header_fix'], $header_fix_options ) ) $input['sp_header_fix'] = null;
	$input['header_bg'] = sanitize_hex_color( $input['header_bg'] );
	$input['header_bg_opacity'] = sanitize_text_field( $input['header_bg_opacity'] );

  // Global navigation
	$input['gnav_color'] = sanitize_hex_color( $input['gnav_color'] );
	$input['gnav_color_hover'] = sanitize_hex_color( $input['gnav_color_hover'] );
	$input['gnav_bg_hover'] = sanitize_hex_color( $input['gnav_bg_hover'] );
	$input['gnav_sub_color'] = sanitize_hex_color( $input['gnav_sub_color'] );
	$input['gnav_sub_bg'] = sanitize_hex_color( $input['gnav_sub_bg'] );
	$input['gnav_sub_color_hover'] = sanitize_hex_color( $input['gnav_sub_color_hover'] );
	$input['gnav_sub_bg_hover'] = sanitize_hex_color( $input['gnav_sub_bg_hover'] );
	$input['gnav_color_sp'] = sanitize_hex_color( $input['gnav_color_sp'] );
	$input['gnav_bg_sp'] = sanitize_hex_color( $input['gnav_bg_sp'] );
	$input['gnav_bg_opacity_sp'] = sanitize_text_field( $input['gnav_bg_opacity_sp'] );

	return $input;
}
