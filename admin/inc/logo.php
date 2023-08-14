<?php 
/*
 * Manage logo tab
 */

// Add default values
add_filter( 'before_getting_design_plus_option', 'add_logo_dp_default_options' );

// Add label of logo tab
add_action( 'tcd_tab_labels', 'add_logo_tab_label' );

// Add HTML of logo tab
add_action( 'tcd_tab_panel', 'add_logo_tab_panel' );

// Register sanitize function
add_filter( 'theme_options_validate', 'add_logo_theme_options_validate' );

global $use_logo_image_options;
$use_logo_image_options = array(
	'type1' => array( 'value' => 'type1', 'label' => __( 'Use text for logo', 'tcd-w' ) ),
	'type2' => array( 'value' => 'type2', 'label' => __( 'Use image for logo', 'tcd-w' ) )
);

function add_logo_dp_default_options( $dp_default_options ) {

  // Header logo
	$dp_default_options['header_use_logo_image'] = 'type1';
	$dp_default_options['header_logo_color'] = '#ffffff';
	$dp_default_options['header_logo_font_size'] = 25;
	$dp_default_options['header_logo_image'] = '';
	$dp_default_options['header_logo_image_retina'] = '';

  // Header logo for mobile
	$dp_default_options['sp_header_use_logo_image'] = 'type1';
	$dp_default_options['sp_header_logo_color'] = '#ffffff';
	$dp_default_options['sp_header_logo_font_size'] = 25;
	$dp_default_options['sp_header_logo_image'] = '';
	$dp_default_options['sp_header_logo_image_retina'] = '';

	return $dp_default_options;
}

function add_logo_tab_label( $tab_labels ) {
	$tab_labels['logo'] = __( 'Logo', 'tcd-w' );
	return $tab_labels;
}

function add_logo_tab_panel( $options ) {
	global $dp_default_options, $use_logo_image_options;
?>
<div id="tab-content-logo">
	<?php // Header logo ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Header logo settings', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Logo type', 'tcd-w' ); ?></h4>
		<ul>
			<?php foreach ( $use_logo_image_options as $option ) : ?>
			<li><label><input type="radio" value="<?php echo esc_attr( $option['value'] ); ?>" name="dp_options[header_use_logo_image]" <?php checked( $options['header_use_logo_image'], $option['value'] ); ?>> <?php echo esc_html_e( $option['label'], 'tcd-w' ); ?></label></li>
			<?php endforeach; ?>
		</ul>
		<div id="header_use_logo_image_type1"<?php if ( 'type2' === $options['header_use_logo_image'] ) { echo ' style="display: none;"'; } ?>>
    	<h4 class="theme_option_headline2"><?php _e( 'Font color for text logo', 'tcd-w' ); ?></h4>
    	<input class="c-color-picker" type="text" name="dp_options[header_logo_color]" value="<?php echo esc_attr( $options['header_logo_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['header_logo_color'] ); ?>">
    	<h4 class="theme_option_headline2"><?php _e( 'Font size for text logo', 'tcd-w' ); ?></h4>
    	<input class="hankaku tiny-text" type="number" min="1" name="dp_options[header_logo_font_size]" value="<?php esc_attr_e( $options['header_logo_font_size'] ); ?>"> <span>px</span>
    </div>
		<div id="header_use_logo_image_type2"<?php if ( 'type1' === $options['header_use_logo_image'] ) { echo ' style="display: none;"'; } ?>>
   		<h4 class="theme_option_headline2"><?php _e( 'Image for logo', 'tcd-w' ); ?></h4>
    	<div class="image_box cf">
    		<div class="cf cf_media_field hide-if-no-js header_logo_image">
    			<input type="hidden" value="<?php echo esc_attr( $options['header_logo_image'] ); ?>" id="header_logo_image" name="dp_options[header_logo_image]" class="cf_media_id">
      		<div class="preview_field"><?php if ( $options['header_logo_image'] ) { echo wp_get_attachment_image( $options['header_logo_image'], 'full' ); } ?></div>
      		<div class="button_area">
      	 		<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
      	 		<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $options['header_logo_image'] ) { echo 'hidden'; } ?>">
      		</div>
				</div>
    	</div>
    	<p><label><input name="dp_options[header_logo_image_retina]" type="checkbox" value="1" <?php checked( 1, $options['header_logo_image_retina'] ); ?>><?php _e( 'Use retina display logo image', 'tcd-w' ); ?></label></p>
		</div>
    <input type="submit" class="button-ml ajax_button" value="<?php echo __( 'Save Changes', 'tcd-w' ); ?>">
	</div>
	<?php // Header logo for mobile ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Header logo settings for mobile', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Logo type', 'tcd-w' ); ?></h4>
		<ul>
			<?php foreach ( $use_logo_image_options as $option ) : ?>
			<li><label><input type="radio" value="<?php echo esc_attr( $option['value'] ); ?>" name="dp_options[sp_header_use_logo_image]" <?php checked( $options['sp_header_use_logo_image'], $option['value'] ); ?>> <?php echo esc_html_e( $option['label'], 'tcd-w' ); ?></label></li>
			<?php endforeach; ?>
		</ul>
		<div id="sp_header_use_logo_image_type1"<?php if ( 'type2' === $options['sp_header_use_logo_image'] ) { echo ' style="display: none;"'; } ?>>
    	<h4 class="theme_option_headline2"><?php _e( 'Font color for text logo', 'tcd-w' ); ?></h4>
    	<input class="c-color-picker" type="text" name="dp_options[sp_header_logo_color]" value="<?php echo esc_attr( $options['sp_header_logo_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['sp_header_logo_color'] ); ?>">
    	<h4 class="theme_option_headline2"><?php _e( 'Font size for text logo', 'tcd-w' ); ?></h4>
    	<input class="hankaku tiny-text" type="number" min="1" name="dp_options[sp_header_logo_font_size]" value="<?php esc_attr_e( $options['sp_header_logo_font_size'] ); ?>"> <span>px</span>
    </div>
		<div id="sp_header_use_logo_image_type2"<?php if ( 'type1' === $options['sp_header_use_logo_image'] ) { echo ' style="display: none;"'; } ?>>
   		<h4 class="theme_option_headline2"><?php _e( 'Image for logo', 'tcd-w' ); ?></h4>
    	<div class="image_box cf">
    		<div class="cf cf_media_field hide-if-no-js sp_header_logo_image">
    			<input type="hidden" value="<?php echo esc_attr( $options['sp_header_logo_image'] ); ?>" id="sp_header_logo_image" name="dp_options[sp_header_logo_image]" class="cf_media_id">
      		<div class="preview_field"><?php if ( $options['sp_header_logo_image'] ) { echo wp_get_attachment_image( $options['sp_header_logo_image'], 'full' ); } ?></div>
      		<div class="button_area">
      	 		<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
      	 		<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $options['sp_header_logo_image'] ) { echo 'hidden'; } ?>">
      		</div>
				</div>
    	</div>
    	<p><label><input name="dp_options[sp_header_logo_image_retina]" type="checkbox" value="1" <?php checked( 1, $options['sp_header_logo_image_retina'] ); ?>><?php _e( 'Use retina display logo image', 'tcd-w' ); ?></label></p>
		</div>
    <input type="submit" class="button-ml ajax_button" value="<?php echo __( 'Save Changes', 'tcd-w' ); ?>">
	</div>
</div><!-- END #tab-content2 -->
<?php
}

function add_logo_theme_options_validate( $input ) {

	global $use_logo_image_options;

  // Header logo
 	if ( ! isset( $input['header_use_logo_image'] ) ) $input['header_use_logo_image'] = null;
 	if ( ! array_key_exists( $input['header_use_logo_image'], $use_logo_image_options ) ) $input['header_use_logo_image'] = null;
 	$input['header_logo_color'] = sanitize_hex_color( $input['header_logo_color'] );
 	$input['header_logo_font_size'] = absint( $input['header_logo_font_size'] );
 	$input['header_logo_image'] = absint( $input['header_logo_image'] );
 	if ( ! isset( $input['header_logo_image_retina'] ) ) $input['header_logo_image_retina'] = null;
  $input['header_logo_image_retina'] = ( $input['header_logo_image_retina'] == 1 ? 1 : 0 );

  // Header logo for mobile
 	if ( ! isset( $input['sp_header_use_logo_image'] ) ) $input['sp_header_use_logo_image'] = null;
 	if ( ! array_key_exists( $input['sp_header_use_logo_image'], $use_logo_image_options ) ) $input['sp_header_use_logo_image'] = null;
 	$input['sp_header_logo_color'] = sanitize_hex_color( $input['sp_header_logo_color'] );
 	$input['sp_header_logo_font_size'] = absint( $input['sp_header_logo_font_size'] );
 	$input['sp_header_logo_image'] = absint( $input['sp_header_logo_image'] );
 	if ( ! isset( $input['sp_header_logo_image_retina'] ) ) $input['sp_header_logo_image_retina'] = null;
  $input['sp_header_logo_image_retina'] = ( $input['sp_header_logo_image_retina'] == 1 ? 1 : 0 );

	return $input;
}
