<?php 
/**
 * Manage footer tab
 */

// Add default values
add_filter( 'before_getting_design_plus_option', 'add_footer_dp_default_options' );

// Add label of footer tab
add_action( 'tcd_tab_labels', 'add_footer_tab_label' );

// Add HTML of footer tab
add_action( 'tcd_tab_panel', 'add_footer_tab_panel' );

// Register sanitize function
add_filter( 'theme_options_validate', 'add_footer_theme_options_validate' );

// Blog slider type
global $footer_slider_type_options;
$footer_slider_type_options = array(
  'post' => array( 'value' => 'post', 'label' => __( 'Post', 'tcd-w' ) ),
  'plan' => array( 'value' => 'plan', 'label' => __( 'Plan', 'tcd-w' ) )
);

// Logo type
global $footer_logo_type_options;
$footer_logo_type_options = array(
	'type1' => array( 'value' => 'type1', 'label' => __( 'Use text for logo', 'tcd-w' ) ),
	'type2' => array( 'value' => 'type2', 'label' => __( 'Use image for logo', 'tcd-w' ) )
);

// Footer bar display type
global $footer_bar_display_options;
$footer_bar_display_options = array(
	'type1' => array( 'value' => 'type1', 'label' => __( 'Fade In', 'tcd-w' ) ),
	'type2' => array( 'value' => 'type2', 'label' => __( 'Slide In', 'tcd-w' ) ),
	'type3' => array( 'value' => 'type3', 'label' => __( 'Hide', 'tcd-w' ) )
);

// Footer bar button type
global $footer_bar_button_options;
$footer_bar_button_options = array(
	'type1' => array( 'value' => 'type1', 'label' => __( 'Default', 'tcd-w' ) ),
 	'type2' => array( 'value' => 'type2', 'label' => __( 'Share', 'tcd-w' ) ),
 	'type3' => array( 'value' => 'type3', 'label' => __( 'Telephone', 'tcd-w' ) )
);

// Footer bar button icon
global $footer_bar_icon_options;
$footer_bar_icon_options = array(
	'file-text' => array( 'value' => 'file-text', 'label' => __( 'Document', 'tcd-w' ) ),
	'share-alt' => array( 'value' => 'share-alt', 'label' => __( 'Share', 'tcd-w' ) ),
	'phone' => array( 'value' => 'phone', 'label' => __( 'Telephone', 'tcd-w' ) ),
	'envelope' => array( 'value' => 'envelope', 'label' => __( 'Envelope', 'tcd-w' ) ),
	'tag' => array( 'value' => 'tag', 'label' => __( 'Tag', 'tcd-w' ) ),
	'pencil' => array( 'value' => 'pencil', 'label' => __( 'Pencil', 'tcd-w' ) )
);

function add_footer_dp_default_options( $dp_default_options ) {

  // Footer slider
	$dp_default_options['footer_slider_type'] = 'post';
	$dp_default_options['footer_slider_bg'] = '#f4f1ed';

  // Company information
	$dp_default_options['footer_use_logo_image'] = 'type1';
	$dp_default_options['footer_logo_font_size'] = 25;
	$dp_default_options['footer_logo_image'] = '';
	$dp_default_options['footer_logo_image_retina'] = '';
	$dp_default_options['footer_info_color'] = '#000000';
	$dp_default_options['display_footer_info_on_front'] = 1;
	$dp_default_options['display_footer_info_on_sub'] = 1;
	$dp_default_options['display_footer_info_col1'] = 1;
	$dp_default_options['display_footer_info_col2'] = 1;
	$dp_default_options['display_footer_info_col3'] = 1;
	$dp_default_options['footer_address'] = __( 'Here is the company information such as the address.', 'tcd-w' );
	$dp_default_options['facebook_url'] = '';
	$dp_default_options['twitter_url'] = '';
	$dp_default_options['insta_url'] = '';
	$dp_default_options['pinterest_url'] = '';
	$dp_default_options['mail_url'] = '';
	$dp_default_options['show_rss'] = 1;
	$dp_default_options['footer_desc'] = __( 'Here is a description', 'tcd-w' );
	$dp_default_options['footer_reservation_desc'] = __( 'Here is a text and a button', 'tcd-w' );
	$dp_default_options['footer_reservation_btn_label'] = __( 'Sample button', 'tcd-w' );
	$dp_default_options['footer_reservation_btn_url'] = '';
	$dp_default_options['footer_reservation_btn_target'] = 0;
	$dp_default_options['footer_reservation_btn_color'] = '#ffffff';
	$dp_default_options['footer_reservation_btn_bg'] = '#000000';
	$dp_default_options['footer_reservation_btn_color_hover'] = '#ffffff';
	$dp_default_options['footer_reservation_btn_bg_hover'] = '#660000';

  // Footer menu
	$dp_default_options['footer_menu_color'] = '#ffffff';
	$dp_default_options['footer_menu_bg'] = '#660000';
	$dp_default_options['footer_menu_color_hover'] = '#ffbfbf';

  // Footer bar
	$dp_default_options['footer_bar_display'] = 'type3';
	$dp_default_options['footer_bar_tp'] = 0.8;
	$dp_default_options['footer_bar_bg'] = '#ffffff';
	$dp_default_options['footer_bar_border'] = '#dddddd';
	$dp_default_options['footer_bar_color'] = '#000000';
	$dp_default_options['footer_bar_btns'] = array();

	return $dp_default_options;
}

function add_footer_tab_label( $tab_labels ) {
	$tab_labels['footer'] = __( 'Footer', 'tcd-w' );
	return $tab_labels;
}

function add_footer_tab_panel( $dp_options ) {
	global $dp_default_options, $footer_slider_type_options, $footer_logo_type_options, $footer_bar_display_options, $footer_bar_button_options, $footer_bar_icon_options;
?>
<div id="tab-content-footer">
  <?php // Footer slider ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Slider settings', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Post type', 'tcd-w' ); ?></h4>
    <?php foreach ( $footer_slider_type_options as $option ) : ?>
    <p><label><input type="radio" name="dp_options[footer_slider_type]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $dp_options['footer_slider_type'] ); ?>><?php echo esc_html_e( $option['label'] ); ?></label></p>
    <?php endforeach; ?>
    <h4 class="theme_option_headline2"><?php _e( 'Background color', 'tcd-w' ); ?></h4>
    <input type="text" class="c-color-picker" name="dp_options[footer_slider_bg]" value="<?php echo esc_attr( $dp_options['footer_slider_bg'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_slider_bg'] ); ?>">
  	<input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
  </div>
	<?php // Company information ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Company information settings', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Font color', 'tcd-w' ); ?></h4>
    <input type="text" class="c-color-picker" name="dp_options[footer_info_color]" data-default-color="<?php echo esc_attr( $dp_default_options['footer_info_color'] ); ?>" value="<?php echo esc_attr( $dp_options['footer_info_color'] ); ?>">
    <h4 class="theme_option_headline2"><?php _e( 'Display settings', 'tcd-w' ); ?></h4>
    <p><label><input type="checkbox" value="1" name="dp_options[display_footer_info_on_front]" <?php checked( 1, $dp_options['display_footer_info_on_front'] ); ?>> <?php _e( 'Display on the front page', 'tcd-w' ); ?></label></p>
    <p><label><input type="checkbox" value="1" name="dp_options[display_footer_info_on_sub]" <?php checked( 1, $dp_options['display_footer_info_on_sub'] ); ?>> <?php _e( 'Display on the subpage', 'tcd-w' ); ?></label></p>
    <h4 class="theme_option_headline2"><?php _e( 'Column settings', 'tcd-w' ); ?></h4>
		<div class="sub_box cf"> 
    	<h3 class="theme_option_subbox_headline"><?php _e( 'Column1: logo, address and social links', 'tcd-w' ); ?></h3>
    	<div class="sub_box_content">
        <p><label><input type="checkbox" name="dp_options[display_footer_info_col1]" value="1" <?php checked( 1, $dp_options['display_footer_info_col1'] ); ?>> <?php _e( 'Display column1', 'tcd-w' ); ?></label></p>
        <h4 class="theme_option_headline2"><?php _e( 'Logo type', 'tcd-w' ); ?></h4>
		    <ul>
		    	<?php foreach ( $footer_logo_type_options as $option ) : ?>
		    	<li><label><input type="radio" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $dp_options['footer_use_logo_image'], $option['value'] ); ?> name="dp_options[footer_use_logo_image]"> <?php echo esc_html_e( $option['label'], 'tcd-w' ); ?></label></li>
		    	<?php endforeach; ?>
		    </ul>
		    <div id="footer_use_logo_image_type1"<?php if ( 'type2' === $dp_options['footer_use_logo_image'] ) { echo ' style="display: none;"'; } ?>>
        	<h4 class="theme_option_headline2"><?php _e( 'Font size for text logo', 'tcd-w' ); ?></h4>
        	<input class="tiny-text hankaku" type="number" min="1" name="dp_options[footer_logo_font_size]" value="<?php esc_attr_e( $dp_options['footer_logo_font_size'] ); ?>"> <span>px</span>
        </div>
		    <div id="footer_use_logo_image_type2"<?php if ( 'type1' === $dp_options['footer_use_logo_image'] ) { echo ' style="display: none;"'; } ?>>
   	    	<h4 class="theme_option_headline2"><?php _e( 'Image for logo', 'tcd-w' ); ?></h4>
        	<div class="image_box cf">
        		<div class="cf cf_media_field hide-if-no-js footer_logo_image">
        			<input type="hidden" value="<?php echo esc_attr( $dp_options['footer_logo_image'] ); ?>" id="footer_logo_image" name="dp_options[footer_logo_image]" class="cf_media_id">
          		<div class="preview_field"><?php if ( $dp_options['footer_logo_image'] ) { echo wp_get_attachment_image( $dp_options['footer_logo_image'], 'full' ); } ?></div>
          		<div class="button_area">
          	 		<input type="button" value="<?php _e( 'Select Image', 'tcd-w' ); ?>" class="cfmf-select-img button">
          	 		<input type="button" value="<?php _e( 'Remove Image', 'tcd-w' ); ?>" class="cfmf-delete-img button <?php if ( ! $dp_options['footer_logo_image'] ) { echo 'hidden'; } ?>">
          		</div>
		    		</div>
        	</div>
        	<p><label><input name="dp_options[footer_logo_image_retina]" type="checkbox" value="1" <?php checked( 1, $dp_options['footer_logo_image_retina'] ); ?>><?php _e( 'Use retina display logo image', 'tcd-w' ); ?></label></p>
		    </div>
        <h4 class="theme_option_headline2"><?php _e( 'Address', 'tcd-w' ); ?></h4>
		    <textarea rows="4" class="large-text" name="dp_options[footer_address]"><?php echo esc_textarea( $dp_options['footer_address'] ); ?></textarea>
        <h4 class="theme_option_headline2"><?php _e( 'SNS button', 'tcd-w' ); ?></h4>
  	    <p><?php _e( 'Enter URL of your SNS pages. If it is blank SNS button will not be shown.', 'tcd-w' ); ?></p>
  	    <ul>
  	    	<li><label><?php _e( 'your Facebook URL', 'tcd-w' ); ?> <input class="regular-text" type="text" name="dp_options[facebook_url]" value="<?php esc_attr_e( $dp_options['facebook_url'] ); ?>"></label></li>
  	    	<li><label><?php _e( 'your Twitter URL', 'tcd-w' ); ?> <input class="regular-text" type="text" name="dp_options[twitter_url]" value="<?php esc_attr_e( $dp_options['twitter_url'] ); ?>"></label></li>
  	    	<li><label><?php _e( 'your instagram URL', 'tcd-w' ); ?> <input class="regular-text" type="text" name="dp_options[insta_url]" value="<?php esc_attr_e( $dp_options['insta_url'] ); ?>"></label></li>
  	    	<li><label><?php _e( 'your pinterest URL', 'tcd-w' ); ?> <input class="regular-text" type="text" name="dp_options[pinterest_url]" value="<?php esc_attr_e( $dp_options['pinterest_url'] ); ?>"></label></li>
  	    	<li><label><?php _e( 'your email address', 'tcd-w' ); ?> <input class="regular-text" type="text" name="dp_options[mail_url]" value="<?php esc_attr_e( $dp_options['mail_url'] ); ?>"></label></li>
		    </ul>
 		    <hr>
  	    <p><label><input name="dp_options[show_rss]" type="checkbox" value="1" <?php checked( '1', $dp_options['show_rss'] ); ?>><?php _e( 'Display RSS button', 'tcd-w' ); ?></label></p>
  	    <input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
      </div><!-- /.sub_box_content -->
    </div><!-- /.sub_box -->
		<div class="sub_box cf"> 
    	<h3 class="theme_option_subbox_headline"><?php _e( 'Column2: description', 'tcd-w' ); ?></h3>
    	<div class="sub_box_content">
        <p><label><input type="checkbox" name="dp_options[display_footer_info_col2]" value="1" <?php checked( 1, $dp_options['display_footer_info_col2'] ); ?>> <?php _e( 'Display column2', 'tcd-w' ); ?></label></p>
        <h4 class="theme_option_headline2"><?php _e( 'Description', 'tcd-w' ); ?></h4>
        <textarea rows="4" class="large-text" name="dp_options[footer_desc]"><?php echo $dp_options['footer_desc']; // No escape ?></textarea>
  	    <input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
      </div><!-- /.sub_box_content -->
    </div><!-- /.sub_box -->
		<div class="sub_box cf"> 
    	<h3 class="theme_option_subbox_headline"><?php _e( 'Column3: reservation information', 'tcd-w' ); ?></h3>
    	<div class="sub_box_content">
        <p><label><input type="checkbox" name="dp_options[display_footer_info_col3]" value="1" <?php checked( 1, $dp_options['display_footer_info_col3'] ); ?>> <?php _e( 'Display column3', 'tcd-w' ); ?></label></p>
        <h4 class="theme_option_headline2"><?php _e( 'Text', 'tcd-w' ); ?></h4>
        <textarea rows="4" class="large-text" name="dp_options[footer_reservation_desc]"><?php echo esc_textarea( $dp_options['footer_reservation_desc'] ); ?></textarea>
        <h4 class="theme_option_headline2"><?php _e( 'Button settings', 'tcd-w' ); ?></h4>
        <p><label><?php _e( 'Label', 'tcd-w' ); ?> <input type="text" name="dp_options[footer_reservation_btn_label]" value="<?php echo esc_attr( $dp_options['footer_reservation_btn_label'] ); ?>"></label></p>
        <p><label><?php _e( 'Link URL', 'tcd-w' ); ?> <input class="regular-text" type="text" name="dp_options[footer_reservation_btn_url]" value="<?php echo esc_attr( $dp_options['footer_reservation_btn_url'] ); ?>"></label></p>
        <p><label><input type="checkbox" name="dp_options[footer_reservation_btn_target]" value="1" <?php checked( 1, $dp_options['footer_reservation_btn_target'] ); ?>> <?php _e( 'Open with new window', 'tcd-w' ); ?></label></p>
        <p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[footer_reservation_btn_color]" value="<?php echo esc_attr( $dp_options['footer_reservation_btn_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_reservation_btn_color'] ); ?>"></p>
        <p><?php _e( 'Background color', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[footer_reservation_btn_bg]" value="<?php echo esc_attr( $dp_options['footer_reservation_btn_bg'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_reservation_btn_bg'] ); ?>"></p>
        <p><?php _e( 'Font color on hover', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[footer_reservation_btn_color_hover]" value="<?php echo esc_attr( $dp_options['footer_reservation_btn_color_hover'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_reservation_btn_color_hover'] ); ?>"></p>
        <p><?php _e( 'Background color on hover', 'tcd-w' ); ?> <input type="text" class="c-color-picker" name="dp_options[footer_reservation_btn_bg_hover]" value="<?php echo esc_attr( $dp_options['footer_reservation_btn_bg_hover'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_reservation_btn_bg_hover'] ); ?>"></p>
  	    <input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
      </div><!-- /.sub_box_content -->
    </div><!-- /.sub_box -->
  	<input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
  </div>
  <?php // Footer menu ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Menu settings', 'tcd-w' ); ?></h3>
    <h4 class="theme_option_headline2"><?php _e( 'Font color', 'tcd-w' ); ?></h4>
    <input type="text" class="c-color-picker" name="dp_options[footer_menu_color]" value="<?php echo esc_attr( $dp_options['footer_menu_color'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_menu_color'] ); ?>">
    <h4 class="theme_option_headline2"><?php _e( 'Background color', 'tcd-w' ); ?></h4>
    <input type="text" class="c-color-picker" name="dp_options[footer_menu_bg]" value="<?php echo esc_attr( $dp_options['footer_menu_bg'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_menu_bg'] ); ?>">
    <h4 class="theme_option_headline2"><?php _e( 'Font color on hover', 'tcd-w' ); ?></h4>
    <input type="text" class="c-color-picker" name="dp_options[footer_menu_color_hover]" value="<?php echo esc_attr( $dp_options['footer_menu_color_hover'] ); ?>" data-default-color="<?php echo esc_attr( $dp_default_options['footer_menu_color_hover'] ); ?>">
  	<input type="submit" class="button-ml ajax_button" value="<?php echo _e( 'Save Changes', 'tcd-w' ); ?>">
  </div>
	<?php // Footer bar ?>
	<div class="theme_option_field cf">
  	<h3 class="theme_option_headline"><?php _e( 'Footer bar settings', 'tcd-w' ); ?></h3>
		<p><?php _e( 'Please set the footer bar which is displayed with smart phone.', 'tcd-w' ); ?>
    <h4 class="theme_option_headline2"><?php _e( 'Display type', 'tcd-w' ); ?></h4>
    <fieldset class="cf select_type2">
     <?php foreach ( $footer_bar_display_options as $option ) : ?>
     <label class="description"><input type="radio" name="dp_options[footer_bar_display]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $dp_options['footer_bar_display'], $option['value'] ); ?>><?php echo esc_html_e( $option['label'], 'tcd-w' ); ?></label>
     <?php endforeach; ?>
    </fieldset>
    <h4 class="theme_option_headline2"><?php _e( 'Appearance settings', 'tcd-w' ); ?></h4>
    <p>
     	<?php _e( 'Background color', 'tcd-w' ); ?>
			<input type="text" name="dp_options[footer_bar_bg]" value="<?php echo esc_attr( $dp_options['footer_bar_bg'] ); ?>" data-default-color="#ffffff" class="c-color-picker">
		</p>
    <p>
    	<?php _e( 'Border color', 'tcd-w' ); ?>
			<input type="text" name="dp_options[footer_bar_border]" value="<?php echo esc_attr( $dp_options['footer_bar_border'] ); ?>" data-default-color="#dddddd" class="c-color-picker">
		</p>
    <p>
     	<?php _e( 'Font color', 'tcd-w' ); ?>
			<input type="text" name="dp_options[footer_bar_color]" value="<?php echo esc_attr( $dp_options['footer_bar_color'] ); ?>" data-default-color="#000000" class="c-color-picker">
		</p>
		<p>
     	<?php _e( 'Opacity of background', 'tcd-w' ); ?>
     	<input class="tiny-text hankaku" type="number" min="0" max="1" step="0.1" name="dp_options[footer_bar_tp]" value="<?php echo esc_attr( $dp_options['footer_bar_tp'] ); ?>">
      <p><?php _e( 'Please enter the number 0 - 1.0. (e.g. 0.8)', 'tcd-w' ); ?></p>
		</p>
    <h4 class="theme_option_headline2"><?php _e( 'Contents settings', 'tcd-w'); ?></h4>
   	<p><?php _e( 'You can display buttons with icon in the footer bar. (We recommend you to set max 4 buttons.)', 'tcd-w' ); ?><br><?php _e( 'You can select button types below.', 'tcd-w' ); ?></p>
		<table class="table-border">
			<tr>
				<th><?php _e( 'Default', 'tcd-w' ); ?></th>
				<td><?php _e( 'You can set link URL.', 'tcd-w' ); ?></td>
			</tr>
			<tr>
				<th><?php _e( 'Share', 'tcd-w' ); ?></th>
				<td><?php _e( 'Share buttons are displayed if you tap this button.', 'tcd-w' ); ?></td>
			</tr>
			<tr>
				<th><?php _e( 'Telephone', 'tcd-w' ); ?></th>
				<td><?php _e( 'You can call this number.', 'tcd-w' ); ?></td>
			</tr>
		</table>
		<p><?php _e( 'Click "Add item", and set the button for footer bar. You can drag the item to change their order.', 'tcd-w' ); ?></p>
		<div class="repeater-wrapper" data-delete-confirm="<?php _e( 'Delete?', 'tcd-w' ); ?>">
			<div class="repeater sortable">
				<?php 
				if ( $dp_options['footer_bar_btns'] ) :
					foreach ( $dp_options['footer_bar_btns'] as $key => $value ) :  
				?>
				<div class="sub_box repeater-item repeater-item-<?php echo esc_attr( $key ); ?>">
     			<h4 class="theme_option_subbox_headline"><?php echo esc_attr( $value['label'] ); ?></h4>
					<div class="sub_box_content">
            <p class="footer-bar-target" style="<?php if ( $value['type'] !== 'type1' ) { echo 'display: none;'; } ?>"><label><input name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][target]" type="checkbox" value="1" <?php checked( $value['target'], 1 ); ?>><?php _e( 'Open with new window', 'tcd-w' ); ?></label></p>
    				<table class="table-repeater">
     					<tr class="footer-bar-type">
								<th><label><?php _e( 'Button type', 'tcd-w' ); ?></label></th>
								<td>
									<select name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][type]">
										<?php foreach( $footer_bar_button_options as $option ) : ?>
										<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $value['type'], $option['value'] ); ?>><?php esc_html_e( $option['label'], 'tcd-w' ); ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
     					<tr>
								<th><label for="dp_options[repeater_footer_bar_btn<?php echo esc_attr( $key ); ?>_label]"><?php _e( 'Button label', 'tcd-w' ); ?></label></th>
								<td><input id="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_label]" class="regular-text change_subbox_headline repeater-label" type="text" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][label]" value="<?php echo esc_attr( $value['label'] ); ?>"></td>
							</tr>
							<tr class="footer-bar-url" style="<?php if ( $value['type'] !== 'type1' ) { echo 'display: none;'; } ?>">
								<th><label for="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_url]"><?php _e( 'Link URL', 'tcd-w' ); ?></label></th>
								<td><input id="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_url]" class="regular-text" type="text" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][url]" value="<?php echo esc_attr( $value['url'] ); ?>"></td>
							</tr>
     					<tr class="footer-bar-number" style="<?php if ( $value['type'] !== 'type3' ) { echo 'display: none;'; } ?>">
								<th><label for="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_number]"><?php _e( 'Phone number', 'tcd-w' ); ?></label></th>
								<td><input id="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_number]" class="regular-text" type="text" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][number]" value="<?php echo esc_attr( $value['number'] ); ?>"></td>
							</tr>
     					<tr>
								<th><?php _e( 'Button icon', 'tcd-w' ); ?></th>
								<td>
									<?php foreach( $footer_bar_icon_options as $option ) : ?>
									<p><label><input type="radio" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][icon]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $option['value'], $value['icon'] ); ?>><span class="icon icon-<?php echo esc_attr( $option['value'] ); ?>"></span><?php esc_html_e( $option['label'], 'tcd-w' ); ?></label></p>
									<?php endforeach; ?>
								</td>
							</tr>
						</table>
       			<p class="delete-row right-align"><a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a></p>
					</div>
				</div>
				<?php
					endforeach;
				endif;
				?>
				<?php
    		$key = 'addindex';
    		ob_start();
				?>
				<div class="sub_box repeater-item repeater-item-<?php echo $key; ?>">
     			<h4 class="theme_option_subbox_headline"><?php _e( 'New item', 'tcd-w' ); ?></h4>
					<div class="sub_box_content">
            <p class="footer-bar-target"><label><input name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][target]" type="checkbox" value="1"><?php _e( 'Open with new window', 'tcd-w' ); ?></label></p>
    				<table class="table-repeater">
     					<tr class="footer-bar-type">
								<th><label><?php _e( 'Button type', 'tcd-w' ); ?></label></th>
								<td>
									<select name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][type]">
										<?php foreach( $footer_bar_button_options as $option ) : ?>
										<option value="<?php echo esc_attr( $option['value'] ); ?>"><?php esc_html_e( $option['label'], 'tcd-w' ); ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
     					<tr>
								<th><label for="dp_options[repeater_footer_bar_btn<?php echo esc_attr( $key ); ?>_label]"><?php _e( 'Button label', 'tcd-w' ); ?></label></th>
								<td><input id="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_label]" class="regular-text change_subbox_headline repeater-label" type="text" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][label]" value=""></td>
							</tr>
							<tr class="footer-bar-url">
								<th><label for="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_url]"><?php _e( 'Link URL', 'tcd-w' ); ?></label></th>
								<td><input id="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_url]" class="regular-text" type="text" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][url]" value=""></td>
							</tr>
     					<tr class="footer-bar-number" style="display: none;">
								<th><label for="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_number]"><?php _e( 'Phone number', 'tcd-w' ); ?></label></th>
								<td><input id="dp_options[footer_bar_btn<?php echo esc_attr( $key ); ?>_number]" class="regular-text" type="text" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][number]" value=""></td>
							</tr>
     					<tr>
								<th><?php _e( 'Button icon', 'tcd-w' ); ?></th>
								<td>
									<?php foreach( $footer_bar_icon_options as $option ) : ?>
									<p><label><input type="radio" name="dp_options[footer_bar_btns][<?php echo esc_attr( $key ); ?>][icon]" value="<?php echo esc_attr( $option['value'] ); ?>"<?php if ( 'file-text' == $option['value'] ) { echo ' checked="checked"'; } ?>><span class="icon icon-<?php echo esc_attr( $option['value'] ); ?>"></span><?php esc_html_e( $option['label'], 'tcd-w' ); ?></label></p>
									<?php endforeach; ?>
								</td>
							</tr>
						</table>
       			<p class="delete-row right-align"><a href="#" class="button button-secondary button-delete-row"><?php _e( 'Delete item', 'tcd-w' ); ?></a></p>
					</div>
				</div>
				<?php $clone = ob_get_clean(); ?>
			</div>
			<a href="#" class="button button-secondary button-add-row" data-clone="<?php echo esc_attr( $clone ); ?>"><?php _e( 'Add item', 'tcd-w' ); ?></a>
		</div>
		<input type="submit" class="button-ml ajax_button" value="<?php _e( 'Save Changes', 'tcd-w' ); ?>"> 
	</div>
</div><!-- END #tab-content8 -->
<?php
}

function add_footer_theme_options_validate( $input ) {

	global $footer_slider_type_options, $footer_logo_type_options, $footer_bar_display_options, $footer_bar_button_options, $footer_bar_icon_options;
  
  // Footer slider
  if ( ! isset( $input['footer_slider_type'] ) ) $input['footer_slider_type'] = null;
  if ( ! array_key_exists( $input['footer_slider_type'], $footer_slider_type_options ) ) $input['footer_slider_type'] = null; 
	$input['footer_slider_bg'] = sanitize_hex_color( $input['footer_slider_bg'] );
		
  // Company information
	$input['footer_info_color'] = sanitize_hex_color( $input['footer_info_color'] );
 	if ( ! isset( $input['display_footer_info_on_front'] ) ) $input['display_footer_info_on_front'] = null;
  $input['display_footer_info_on_front'] = ( $input['display_footer_info_on_front'] == 1 ? 1 : 0 );
 	if ( ! isset( $input['display_footer_info_on_sub'] ) ) $input['display_footer_info_on_sub'] = null;
  $input['display_footer_info_on_sub'] = ( $input['display_footer_info_on_sub'] == 1 ? 1 : 0 );
  for ( $i = 1; $i <= 3; $i++ ) {
 	  if ( ! isset( $input['display_footer_info_col' . $i] ) ) $input['display_footer_info_col' . $i] = null;
    $input['display_footer_info_col' . $i] = ( $input['display_footer_info_col' . $i] == 1 ? 1 : 0 );
  }
 	if ( ! isset( $input['footer_use_logo_image'] ) ) $input['footer_use_logo_image'] = null;
 	if ( ! array_key_exists( $input['footer_use_logo_image'], $footer_logo_type_options ) ) $input['footer_use_logo_image'] = null;
 	$input['footer_logo_font_size'] = absint( $input['footer_logo_font_size'] );
 	$input['footer_logo_image'] = absint( $input['footer_logo_image'] );
 	if ( ! isset( $input['footer_logo_image_retina'] ) ) $input['footer_logo_image_retina'] = null;
  $input['footer_logo_image_retina'] = ( $input['footer_logo_image_retina'] == 1 ? 1 : 0 );
	$input['footer_address'] = sanitize_textarea_field( $input['footer_address'] );
 	$input['facebook_url'] = esc_url_raw( $input['facebook_url'] );
	$input['twitter_url'] = esc_url_raw( $input['twitter_url'] );
 	$input['insta_url'] = esc_url_raw( $input['insta_url'] );
 	$input['pinterest_url'] = esc_url_raw( $input['pinterest_url'] );
 	$input['mail_url'] = sanitize_email( $input['mail_url'] );
 	if ( ! isset( $input['show_rss'] ) ) $input['show_rss'] = null;
  $input['show_rss'] = ( $input['show_rss'] == 1 ? 1 : 0 );
	$input['footer_desc'] = $input['footer_desc']; // No validation
	$input['footer_reservation_desc'] = sanitize_textarea_field( $input['footer_reservation_desc'] );
	$input['footer_reservation_btn_label'] = sanitize_textarea_field( $input['footer_reservation_btn_label'] );
	$input['footer_reservation_btn_url'] = esc_url_raw( $input['footer_reservation_btn_url'] );
 	if ( ! isset( $input['footer_reservation_btn_target'] ) ) $input['footer_reservation_btn_target'] = null;
  $input['footer_reservation_btn_target'] = ( $input['footer_reservation_btn_target'] == 1 ? 1 : 0 );
	$input['footer_reservation_btn_color'] = sanitize_hex_color( $input['footer_reservation_btn_color'] );
	$input['footer_reservation_btn_bg'] = sanitize_hex_color( $input['footer_reservation_btn_bg'] );
	$input['footer_reservation_btn_color_hover'] = sanitize_hex_color( $input['footer_reservation_btn_color_hover'] );
	$input['footer_reservation_btn_bg_hover'] = sanitize_hex_color( $input['footer_reservation_btn_bg_hover'] );

  // Footer menu color
	$input['footer_menu_color'] = sanitize_hex_color( $input['footer_menu_color'] );
	$input['footer_menu_bg'] = sanitize_hex_color( $input['footer_menu_bg'] );
	$input['footer_menu_color_hover'] = sanitize_hex_color( $input['footer_menu_color_hover'] );

  // Footer bar
 	if ( ! array_key_exists( $input['footer_bar_display'], $footer_bar_display_options ) ) $input['footer_bar_display'] = 'type3';
 	$input['footer_bar_bg'] = sanitize_hex_color( $input['footer_bar_bg'] );
 	$input['footer_bar_border'] = sanitize_hex_color( $input['footer_bar_border'] );
 	$input['footer_bar_color'] = sanitize_hex_color( $input['footer_bar_color'] );
 	$input['footer_bar_tp'] = sanitize_text_field( $input['footer_bar_tp'] );

	if ( isset( $input['footer_bar_btns'] ) ) {
    foreach ( $input['footer_bar_btns'] as $key => $value ) {
 	    if ( ! isset( $input['footer_bar_btns'][$key]['type'] ) ) $input['footer_bar_btns'][$key]['type'] = 'type1';
 	    if ( ! array_key_exists( $input['footer_bar_btns'][$key]['type'], $footer_bar_button_options ) ) $input['footer_bar_btns'][$key]['type'] = 'type1';
      $input['footer_bar_btns'][$key]['label'] = sanitize_text_field( $input['footer_bar_btns'][$key]['label'] );
      $input['footer_bar_btns'][$key]['url'] = esc_url_raw( $input['footer_bar_btns'][$key]['url'] );
      $input['footer_bar_btns'][$key]['number'] = sanitize_text_field( $input['footer_bar_btns'][$key]['number'] );
 	    if ( ! isset( $input['footer_bar_btns'][$key]['target'] ) ) $input['footer_bar_btns'][$key]['target'] = null;
      $input['footer_bar_btns'][$key]['target'] = ( $input['footer_bar_btns'][$key]['target'] == 1 ? 1 : 0 );
 	    if ( ! isset( $input['footer_bar_btns'][$key]['icon'] ) ) $input['footer_bar_btns'][$key]['icon'] = 'file-text';
 	    if ( ! array_key_exists( $input['footer_bar_btns'][$key]['icon'], $footer_bar_icon_options ) ) $input['footer_bar_btns'][$key]['icon'] = 'file-text';
    }
  }
	
	return $input;
}
