<?php
/**
 * ページビルダーウィジェット登録
 */
add_page_builder_widget(array(
	'id' => 'pb-widget-googlemap',
	'form' => 'form_page_builder_widget_googlemap',
	'form_rightbar' => 'form_rightbar_page_builder_widget', // 標準右サイドバー
	'display' => 'display_page_builder_widget_googlemap',
	'title' => __('Google Map', 'tcd-w'),
	'priority' => 21
));

/**
 * 管理画面用js
 */
function page_builder_widget_googlemap_admin_scripts() {
	wp_enqueue_script('page_builder-googlemap-admin', get_template_directory_uri().'/pagebuilder/assets/admin/js/googlemap.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}
add_action('page_builder_admin_scripts', 'page_builder_widget_googlemap_admin_scripts', 12);

/**
 * フォーム デフォルト値
 */
function get_page_builder_widget_googlemap_default_values() {
	$primary_color = page_builder_get_primary_color('#000000');

	$default_values = array(
		'widget_index' => '',
		'margin_bottom' => 30,
		'margin_bottom_mobile' => 30,
		'map_address' => '', // Google Maps で使用する住所
		'map_logo' => '', // マップの下に表示するロゴ画像
		'map_desc' => '', // マップの下に表示する説明文（住所情報等に使用）
		'map_link_label' => __( 'View in Google Maps', 'tcd-w' ), // 「大きな地図で見る」のラベル
		'map_link_label_sp' => __( 'View in Google Maps', 'tcd-w' ), // 「大きな地図で見る」のラベル（スマホ用）
		'map_link' => '', // 「大きな地図で見る」のリンクURL
		'map_link_bg' => '#ffffff', // 「大きな地図で見る」の背景色
		'map_link_color' => '#000000', // 「大きな地図で見る」の文字色
		'map_link_border_color' => '#dddddd', // 「大きな地図で見る」の枠線の色
		'map_link_bg_hover' => '#ffffff', // 「大きな地図で見る」の背景色（ホバー）
		'map_link_color_hover' => '#000000', // 「大きな地図で見る」の文字色（ホバー）
		'map_link_border_color_hover' => '#dddddd', // 「大きな地図で見る」の枠線の色（ホバー）
		'saturation' => -100, // Google Maps の彩度（デフォルトは -100 のモノクロ）
		'marker_type' => 'type1', // マーカーのタイプ（テーマオプション設定、デフォルト、カスタム）
		'custom_marker_type' => 'type1', // カスタムマーカーのタイプ（テキスト、画像）
		'marker_text' => '', // カスタムマーカーのテキスト
		'marker_color' => '#ffffff', // カスタムマーカーの文字色
		'marker_img' => '', // カスタムマーカーの画像
		'marker_bg' => '#000000', // カスタムマーカーの背景色
		'show_overlay' => 0,
		'overlay_layout' => 'type1',
		'overlay_map_layout' => 'type1',
		'overlay_bg_color' => $primary_color,
		'overlay_bg_opacity' => '0.5',
		'overlay_headline' => '',
		'overlay_headline_font_size' => '40',
		'overlay_headline_font_size_mobile' => '20',
		'overlay_headline_font_color' => '#ffffff',
		'overlay_headline_font_family' => 'type1',
		'overlay_headline_text_align' => 'left',
		'overlay_content' => '',
		'overlay_content_font_size' => '14',
		'overlay_content_font_size_mobile' => '14',
		'overlay_content_font_color' => '#ffffff',
		'overlay_content_font_family' => 'type1',
		'overlay_content_text_align' => 'left',
		'show_overlay_button' => 0,
		'overlay_button' => '',
		'overlay_button_url' => '',
		'overlay_button_target_blank' => 0,
		'overlay_button_font_color' => '#ffffff',
		'overlay_button_bg_color' => $primary_color,
		'overlay_button_bg_opacity' => 0,
		'overlay_button_border_color' => '#ffffff',
		'overlay_button_font_color_hover' => '#ffffff',
		'overlay_button_bg_color_hover' => $primary_color,
		'overlay_button_bg_opacity_hover' => 0,
		'overlay_button_border_color_hover' => '#ffffff'
	);

	return apply_filters('get_page_builder_widget_googlemap_default_values', $default_values);
}

/**
 * フォーム
 */
function form_page_builder_widget_googlemap($values = array()) {
	$dp_options = get_design_plus_option();

	// デフォルト値
	$default_values = apply_filters('page_builder_widget_googlemap_default_values', get_page_builder_widget_googlemap_default_values(), 'form');

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);

	// font family 選択肢
	$font_family_options = array(
		'type1' => __('Meiryo', 'tcd-w'),
		'type2' => __('YuGothic', 'tcd-w'),
		'type3' => __('YuMincho', 'tcd-w'),
	);

	// text align 選択肢
	$text_align_options = array(
		'left' => __('Align left', 'tcd-w'),
		'center' => __('Align center', 'tcd-w'),
		'right' => __('Align right', 'tcd-w')
	);

	// marker type 選択肢
	$marker_type_options = array(
		'type1' => __( 'Use settings in Theme Options', 'tcd-w' ),
		'type2' => __( 'Use default marker', 'tcd-w' ),
		'type3' => __( 'Use custom marker', 'tcd-w' )
	);

	// テーマオプションにgmap_marker_typeがなければtype1削除、必要なら値も変更
	if (!isset($dp_options['gmap_marker_type'])) {
		unset($marker_type_options['type1']);
		if ( 'type1' === $default_values['marker_type'] ) {
			$default_values['marker_type'] = 'type2';
		}
		if ( 'type1' === $values['marker_type'] ) {
			$values['marker_type'] = 'type2';
		}
	}
?>
<div class="form-field form-field-map_address">
	<h4><?php _e( 'Map address', 'tcd-w' ); ?></h4>
	<input class="large-text pb-input-overview" type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][map_address]" value="<?php echo esc_attr( $values['map_address'] ); ?>">
</div>

<div class="form-field form-field-map_logo">
	<h4><?php _e( 'Logo image', 'tcd-w' ); ?></h4>
	<p class="pb-description"><?php _e( 'The logo image is displayed after the map.', 'tcd-w' ); ?></p>
	<?php
		$input_name = 'pagebuilder[widget]['.$values['widget_index'].'][map_logo]';
		$media_id = $values['map_logo'];
		pb_media_form( $input_name, $media_id );
	?>
</div>

<div class="form-field form-field-map_desc">
	<h4><?php _e( 'Description', 'tcd-w' ); ?></h4>
	<p class="pb-description"><?php _e( 'The description is displayed after the map.', 'tcd-w' ); ?></p>
	<textarea class="large-text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][map_desc]"><?php echo esc_textarea( $values['map_desc'] ); ?></textarea>
</div>

<div class="form-field form-field-map_link">
	<h4><?php _e( '"View in Google Maps" settings', 'tcd-w' ); ?></h4>
	<table style="width: 100%;">
		<tr>
			<td><?php _e( 'Link label', 'tcd-w' ); ?></td>
			<td><input class="large-text" type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][map_link_label]" value="<?php echo esc_attr( $values['map_link_label'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Link label for mobile', 'tcd-w' ); ?></td>
			<td><input class="large-text" type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][map_link_label_sp]" value="<?php echo esc_attr( $values['map_link_label_sp'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Link URL', 'tcd-w' ); ?></td>
			<td><input class="large-text" type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][map_link]" value="<?php echo esc_attr( $values['map_link'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Background color', 'tcd-w' ); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr( $values['widget_index'] ); ?>][map_link_bg]" value="<?php echo esc_attr( $values['map_link_bg'] ); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr( $default_values['map_link_bg'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Font color', 'tcd-w' ); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr( $values['widget_index'] ); ?>][map_link_color]" value="<?php echo esc_attr( $values['map_link_color'] ); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr( $default_values['map_link_color'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Border color', 'tcd-w' ); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr( $values['widget_index'] ); ?>][map_link_border_color]" value="<?php echo esc_attr( $values['map_link_border_color'] ); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr( $default_values['map_link_border_color'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Background color on hover', 'tcd-w' ); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr( $values['widget_index'] ); ?>][map_link_bg_hover]" value="<?php echo esc_attr( $values['map_link_bg_hover'] ); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr( $default_values['map_link_bg_hover'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Font color on hover', 'tcd-w' ); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr( $values['widget_index'] ); ?>][map_link_color_hover]" value="<?php echo esc_attr( $values['map_link_color_hover'] ); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr( $default_values['map_link_color_hover'] ); ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Border color on hover', 'tcd-w' ); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr( $values['widget_index'] ); ?>][map_link_border_color_hover]" value="<?php echo esc_attr( $values['map_link_border_color_hover'] ); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr( $default_values['map_link_border_color_hover'] ); ?>"></td>
		</tr>
	</table>
</div>

<div class="form-field form-field-saturation">
	<h4><?php _e( 'Saturation', 'tcd-w' ); ?></h4>
	<p class="pb-description"><?php _e( 'Please set the saturation of the map. If you set it to -100 the output map is monochrome.', 'tcd-w' ); ?></p>
	<?php // range をスライドした時、現在の彩度がわかるように表示する ?>
	<p class="range-output"><?php _e( 'Current value: ', 'tcd-w' ); ?><span><?php echo esc_attr( $values['saturation'] ); ?></span></p>
	<input class="range" type="range" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][saturation]" value="<?php echo esc_attr( $values['saturation'] ); ?>" min="-100" max="100" step="10">
</div>

<div class="form-field form-field-radio form-field-marker_type">
	<h4><?php _e( 'Marker type', 'tcd-w' ); ?></h4>
	<?php
		$radio_html = array();
		foreach($marker_type_options as $key => $value) {
			$attr = '';
			if ($values['marker_type'] == $key) {
				$attr .= ' checked="checked"';
			}
			$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][marker_type]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
		}
		echo implode("<br>\n\t", $radio_html);
	?>
</div>

<div class="form-field form-field-marker_type-type3">
	<div class="form-field form-field-radio form-field-custom_marker_type">
		<h4><?php _e( 'Custom marker type', 'tcd-w' ); ?></h4>
		<?php
			$radio_options = array(
				'type1' => __( 'Text', 'tcd-w' ),
				'type2' => __( 'Image', 'tcd-w' )
			);
			$radio_html = array();
			foreach($radio_options as $key => $value) {
				$attr = '';
				if ($values['custom_marker_type'] == $key) {
					$attr .= ' checked="checked"';
				}
				$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][custom_marker_type]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
			}
			echo implode("<br>\n\t", $radio_html);
		?>
	</div>

	<div class="form-field form-field-marker_text">
		<h4><?php _e( 'Custom marker text', 'tcd-w' ); ?></h4>
		<input type="text" class="large-text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][marker_text]" value="<?php echo esc_attr( $values['marker_text'] ); ?>">
		<p><?php _e( 'Font color', 'tcd-w' ); ?> <input type="text" class="pb-input-narrow pb-wp-color-picker" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][marker_color]" data-default-color="<?php echo esc_attr( $default_values['marker_color'] ); ?>" value="<?php echo esc_attr( $values['marker_color'] ); ?>"></p>
	</div>

	<div class="form-field form-field-marker_img">
		<h4><?php _e( 'Custom marker image', 'tcd-w' ); ?></h4>
		<p class="pb-description"><?php _e('Recommended size: width:60px, height:20px', 'tcd-w'); ?></p>
		<?php
			$input_name = 'pagebuilder[widget]['.$values['widget_index'].'][marker_img]';
			$media_id = $values['marker_img'];
			pb_media_form( $input_name, $media_id );
		?>
	</div>

	<div class="form-field form-field-marker_bg">
		<h4><?php _e('Marker style', 'tcd-w'); ?></h4>
		<p><?php _e( 'Background color', 'tcd-w' ); ?> <input type="text" class="pb-input-narrow pb-wp-color-picker" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][marker_bg]" data-default-color="<?php echo esc_attr( $default_values['marker_bg'] ); ?>" value="<?php echo esc_attr( $values['marker_bg'] ); ?>"></p>
	</div>
</div>

<div class="form-field form-field-show_overlay">
	<h4><?php _e('Overlay text setting', 'tcd-w'); ?></h4>
	<p class="pb-description"><?php _e('You can display the background color and text overlaid on the map.', 'tcd-w'); ?></p>
	<p>
		<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_overlay]" value="0" />
		<label><input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_overlay]" value="1"<?php if ($values['show_overlay']) echo ' checked="checked"'; ?> /><?php _e('Display overlay text', 'tcd-w'); ?></label>
	</p>
</div>

<div class="form-field form-field-radio form-field-overlay hidden">
	<h4><?php _e('Overlay text layout', 'tcd-w'); ?></h4>
	<?php
		$radio_options = array(
			'type1' => __('Type1 (display text contents on the left side)', 'tcd-w'),
			'type2' => __('Type2 (display text contents on the right side)', 'tcd-w')
		);
		$radio_html = array();
		foreach($radio_options as $key => $value) {
			$attr = '';
			if ($values['overlay_layout'] == $key) {
				$attr .= ' checked="checked"';
			}
			$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][overlay_layout]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
		}
		echo implode("<br>\n\t", $radio_html);
	?>
</div>

<div class="form-field form-field-radio form-field-overlay hidden">
	<h4><?php _e('Google map layout', 'tcd-w'); ?></h4>
	<?php
		$radio_options = array(
			'type1' => __('Full width', 'tcd-w'),
			'type2' => __('Half width', 'tcd-w')
		);
		$radio_html = array();
		foreach($radio_options as $key => $value) {
			$attr = '';
			if ($values['overlay_map_layout'] == $key) {
				$attr .= ' checked="checked"';
			}
			$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][overlay_map_layout]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
		}
		echo implode("<br>\n\t", $radio_html);
	?>
</div>

<div class="form-field form-field-overlay hidden">
	<h4><?php _e('Overlay background color', 'tcd-w'); ?></h4>
	<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_bg_color]" value="<?php echo esc_attr($values['overlay_bg_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_bg_color']); ?>" />
	<table>
		<tr>
			<td><?php _e('Transparency', 'tcd-w'); ?></td>
			<td>
				<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_bg_opacity]" value="<?php echo esc_attr($values['overlay_bg_opacity']); ?>" class="small-text" min="0" max="1" step="0.1" />
				<span class="pb-description" style="margin-left: 5px;"><?php _e('Please enter the number 0 - 1.0. (e.g. 0.5)', 'tcd-w'); ?></span>
			</td>
		</tr>
	</table>
</div>

<div class="form-field form-field-overlay hidden">
	<h4><?php _e('Headline', 'tcd-w'); ?></h4>
	<textarea name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_headline]" rows="2"><?php echo esc_textarea($values['overlay_headline']); ?></textarea>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font size', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_headline_font_size]" value="<?php echo esc_attr($values['overlay_headline_font_size']); ?>" class="small-text" min="0" /> px</td>
		</tr>
		<tr>
			<td><?php _e('Font size for mobile', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_headline_font_size_mobile]" value="<?php echo esc_attr($values['overlay_headline_font_size_mobile']); ?>" class="small-text" min="0" /> px</td>
		</tr>
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_headline_font_color]" value="<?php echo esc_attr($values['overlay_headline_font_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_headline_font_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Font family', 'tcd-w'); ?></td>
			<td>
				<select name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_headline_font_family]">
					<?php
						foreach($font_family_options as $key => $value) {
							$attr = '';
							if ($values['overlay_headline_font_family'] == $key) {
								$attr .= ' selected="selected"';
							}
							echo '<option value="'.esc_attr($key).'"'.$attr.'>'.esc_html($value).'</option>';
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php _e('Text align', 'tcd-w'); ?></td>
			<td>
				<select name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_headline_text_align]">
					<?php
						foreach($text_align_options as $key => $value) {
							$attr = '';
							if ($values['overlay_headline_text_align'] == $key) {
								$attr .= ' selected="selected"';
							}
							echo '<option value="'.esc_attr($key).'"'.$attr.'>'.esc_html($value).'</option>';
						}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="form-field form-field-overlay hidden">
	<h4><?php _e('Description', 'tcd-w'); ?></h4>
	<textarea name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_content]" rows="4"><?php echo esc_textarea($values['overlay_content']); ?></textarea>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font size', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_content_font_size]" value="<?php echo esc_attr($values['overlay_content_font_size']); ?>" class="small-text" min="0" /> px</td>
		</tr>
		<tr>
			<td><?php _e('Font size for mobile', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_content_font_size_mobile]" value="<?php echo esc_attr($values['overlay_content_font_size_mobile']); ?>" class="small-text" min="0" /> px</td>
		</tr>
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_content_font_color]" value="<?php echo esc_attr($values['overlay_content_font_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_content_font_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Font family', 'tcd-w'); ?></td>
			<td>
				<select name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_content_font_family]">
					<?php
						foreach($font_family_options as $key => $value) {
							$attr = '';
							if ($values['overlay_content_font_family'] == $key) {
								$attr .= ' selected="selected"';
							}
							echo '<option value="'.esc_attr($key).'"'.$attr.'>'.esc_html($value).'</option>';
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php _e('Text align', 'tcd-w'); ?></td>
			<td>
				<select name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_content_text_align]">
					<?php
						foreach($text_align_options as $key => $value) {
							$attr = '';
							if ($values['overlay_content_text_align'] == $key) {
								$attr .= ' selected="selected"';
							}
							echo '<option value="'.esc_attr($key).'"'.$attr.'>'.esc_html($value).'</option>';
						}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="form-field form-field-overlay hidden">
	<h4><?php _e('Button Settings', 'tcd-w'); ?></h4>
	<p><label><input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_overlay_button]" value="1" <?php checked(1, $values['show_overlay_button']); ?>> <?php _e( 'Display button', 'tcd-w' ); ?></label></p>
	<table style="width:100%;">
		<tr>
			<td><?php _e('Button text', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button]" value="<?php echo esc_attr($values['overlay_button']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Link URL', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_url]" value="<?php echo esc_attr($values['overlay_button_url']); ?>" /></td>
		</tr>
		<tr>
			<td></td>
			<td><p style="margin:5px 0;"><label><input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_target_blank]" value="1"<?php if ($values['overlay_button_target_blank']) echo ' checked="checked"'; ?> /><?php _e('Open link in new window', 'tcd-w'); ?></label></p></td>
		</tr>
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_font_color]" value="<?php echo esc_attr($values['overlay_button_font_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_button_font_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_bg_color]" value="<?php echo esc_attr($values['overlay_button_bg_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_button_bg_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color transparency', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_bg_opacity]" value="<?php echo esc_attr($values['overlay_button_bg_opacity']); ?>" class="small-text" min="0" max="1" step="0.1" /></td>
		</tr>
		<tr>
			<td><?php _e('Border color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_border_color]" value="<?php echo esc_attr($values['overlay_button_border_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_button_border_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Font color (hover)', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_font_color_hover]" value="<?php echo esc_attr($values['overlay_button_font_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_button_font_color_hover']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color (hover)', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_bg_color_hover]" value="<?php echo esc_attr($values['overlay_button_bg_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_button_bg_color_hover']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color transparency (hover)', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_bg_opacity_hover]" value="<?php echo esc_attr($values['overlay_button_bg_opacity_hover']); ?>" class="small-text" min="0" max="1" step="0.1" /></td>
		</tr>
		<tr>
			<td><?php _e('Border color (hover)', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][overlay_button_border_color_hover]" value="<?php echo esc_attr($values['overlay_button_border_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['overlay_button_border_color_hover']); ?>" /></td>
		</tr>
	</table>
</div>
<?php
}

/**
 * フロント出力
 */
function display_page_builder_widget_googlemap($values = array(), $widget_index = null) {
	global $dp_options;
	if ( ! $dp_options ) $dp_options = get_design_plus_option();

	// デフォルト値
	$default_values = apply_filters('page_builder_widget_googlemap_default_values', get_page_builder_widget_googlemap_default_values(), 'form');
	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);

	$use_custom_overlay = 0;
	$saturation = $values['saturation'];
	$marker_text = '';
	$marker_img = '';

	if ( 'type1' === $values['marker_type'] && 'type2' === $dp_options['gmap_marker_type'] ) { // Use custom marker in Theme Options
		$use_custom_overlay = 1;
		if ( 'type1' === $dp_options['gmap_custom_marker_type'] ) { // Use text
			$marker_text = $dp_options['gmap_marker_text'];
		} else { // Use image
			$marker_img = $dp_options['gmap_marker_img'] ? wp_get_attachment_url( $dp_options['gmap_marker_img'] ) : '';
		}
	 } elseif ( 'type3' === $values['marker_type'] ) { // Use custom overlay in googlemap module
		$use_custom_overlay = 1;
		if ( 'type1' === $values['custom_marker_type'] ) {
			$marker_text = $values['marker_text'];
		} else {
			$marker_img = $values['marker_img'] ? wp_get_attachment_url( $values['marker_img'] ) : '';
		}
	}

	if ( ! empty( $values['show_overlay'] ) ) { // Use overlay
		$overlay_contents = array();
		$overlay_class = '';
		$map_class = '';

		if ($values['overlay_headline']) {
			$overlay_contents['headline'] = '<h3 class="pb_googlemap_headline pb_font_family_'.esc_attr($values['overlay_headline_font_family']).'">'.str_replace(array("\r\n", "\r", "\n"), '<br>', esc_html($values['overlay_headline'])).'</h3>';
		}

		if ($values['overlay_content']) {
			$overlay_contents['content'] = '<div class="pb_googlemap_content pb_font_family_'.esc_attr($values['overlay_content_font_family']).'">'.str_replace(array("\r\n", "\r", "\n"), '<br>', esc_html($values['overlay_content'])).'</div>';
		}

		if ($values['show_overlay_button'] && $values['overlay_button']) {
			if ($values['overlay_button_url']) {
				$overlay_contents['overlay_button'] = '<a class="pb_googlemap_button" href="'.esc_attr($values['overlay_button_url']).'"';
				if ($values['overlay_button_target_blank']) {
					$overlay_contents['overlay_button'] .= ' target="_blank"';
				}
				$overlay_contents['overlay_button'] .= '>'.esc_html($values['overlay_button']).'</a>';
			} else {
				$overlay_contents['overlay_button'] .= '<div class="pb_googlemap_button">'.esc_html($values['overlay_button']).'</div>';
			}
		}

		// overlay layout
		if ($values['overlay_layout'] == 'type2') {
			$overlay_class = ' pb_googlemap-overlay_layout-'.esc_attr($values['overlay_layout']);
		} else {
			$overlay_class = ' pb_googlemap-overlay_layout-type1';
		}

		// googlemap layout type2
		if ($values['overlay_map_layout'] == 'type2') {
			$overlay_class .= ' pb_googlemap-map_layout-type2';
			$map_class = $overlay_class;
		}
	}

	// Render HTML
?>
<div class="pb_googlemap clearfix">
<?php if ( ! empty( $values['show_overlay'] ) ) : ?>
	<div class="pb_googlemap_overlay<?php echo $overlay_class; ?>">
<?php
		if ( $overlay_contents ) :
			echo "\t\t" . implode( "\n\t\t", $overlay_contents );
		endif;
?>
	</div>
	<div class="pb_googlemap_map<?php echo $map_class; ?>">
		<div id="js-googlemap-<?php echo esc_attr( $widget_index ); ?>" class="pb_googlemap_embed"></div>
	</div>
<?php else : ?>
	<div id="js-googlemap-<?php echo esc_attr( $widget_index ); ?>" class="pb_googlemap_embed"></div>
<?php endif; ?>
	<div class="pb_googlemap_footer">
<?php if ( $values['map_logo'] ) : ?>
		<div class="pb_googlemap_logo">
			<img src="<?php echo esc_attr( wp_get_attachment_url( $values['map_logo'] ) ); ?>" alt="">
		</div>
<?php endif; ?>
		<div class="pb_googlemap_address"><?php echo wpautop( $values['map_desc'] ); ?></div>
<?php if ( $values['map_link'] ) : ?>
		<a href="<?php echo esc_url( $values['map_link'] ); ?>" target="_blank" class="pb_googlemap_footer_button"><?php echo is_mobile() ? esc_html( $values['map_link_label_sp'] ) : esc_html( $values['map_link_label'] ); ?></a>
<?php endif; ?>
	</div>
</div>
<script>jQuery(function($) { $(window).load(function() { initMap('js-googlemap-<?php echo esc_js( $widget_index ); ?>', '<?php echo esc_js( $values['map_address'] ); ?>', <?php echo esc_js( $saturation ); ?>, <?php echo esc_js( $use_custom_overlay ); ?>, '<?php echo esc_js( $marker_img ); ?>', '<?php echo esc_js( $marker_text ); ?>');});});</script>
<?php
}

/**
 * フロント用js・css
 */
function page_builder_widget_googlemap_scripts() {
	global $dp_options;
	if ( ! $dp_options ) $dp_options = get_design_plus_option();

	wp_enqueue_script('page_builder-googlemap-api', 'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $dp_options['gmap_api_key'] ), array(), null, true);
	wp_enqueue_script('page_builder-googlemap', get_template_directory_uri().'/pagebuilder/assets/js/googlemap.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}

function page_builder_widget_googlemap_styles() {
	wp_enqueue_style('page_builder-googlemap', get_template_directory_uri().'/pagebuilder/assets/css/googlemap.css', false, PAGE_BUILDER_VERSION);
}

function page_builder_widget_googlemap_sctipts_styles() {
	if (is_singular() && is_page_builder() && page_builder_has_widget('pb-widget-googlemap')) {
		add_action('wp_enqueue_scripts', 'page_builder_widget_googlemap_scripts', 11);
		add_action('wp_enqueue_scripts', 'page_builder_widget_googlemap_styles', 11);
		add_action('page_builder_css', 'page_builder_widget_googlemap_css');
	}
}
add_action('wp', 'page_builder_widget_googlemap_sctipts_styles');

function page_builder_widget_googlemap_css() {
	global $dp_options;
	if ( ! $dp_options ) $dp_options = get_design_plus_option();

	// 現記事で使用しているgoolemapコンテンツデータを取得
	$post_widgets = get_page_builder_post_widgets(get_the_ID(), 'pb-widget-googlemap');
	if ($post_widgets) {
		foreach($post_widgets as $post_widget) {
			$values = $post_widget['widget_value'];

			// 「大きな地図で見る」ボタン
			if ( $values['map_link'] ) {
				echo $post_widget['css_class'] . ' .pb_googlemap_footer_button { background: ' . esc_html( $values['map_link_bg'] ) . '; border: 1px solid ' . esc_html( $values['map_link_border_color'] ) . '; color: ' . esc_html( $values['map_link_color'] ) . '; }' . "\n";
				echo $post_widget['css_class'] . ' .pb_googlemap_footer_button:hover { background: ' . esc_html( $values['map_link_bg_hover'] ) . '; border: 1px solid ' . esc_html( $values['map_link_border_color_hover'] ) . '; color: ' . esc_html( $values['map_link_color_hover'] ) . '; }' . "\n";
			}

			// カスタムマーカー
			if ( ( 'type1' === $values['marker_type'] && 'type2' === $dp_options['gmap_marker_type'] ) || 'type3' === $values['marker_type'] ) {
				// Use custom marker in Theme Options
				if ( 'type1' === $values['marker_type'] && 'type2' === $dp_options['gmap_marker_type'] ) {
					$marker_color = $dp_options['gmap_marker_color'];
					$marker_bg = $dp_options['gmap_marker_bg'];
				// Use custom marker
				} elseif ( 'type3' === $values['marker_type'] ) {
					$marker_color = $values['marker_color'];
					$marker_bg = $values['marker_bg'];
				}

				echo $post_widget['css_class'] . ' .pb_googlemap_custom-overlay-inner { background: ' . esc_html( $marker_bg ) . '; color: ' . esc_html( $marker_color ) . '; }' . "\n";
				echo $post_widget['css_class'] . ' .pb_googlemap_custom-overlay-inner::after { border-color: ' . esc_html( $marker_bg ) . ' transparent transparent transparent; }' . "\n";
			}

			// オーバーレイあり
			if (!empty($values['show_overlay'])) {
				echo $post_widget['css_class'].' .pb_googlemap_overlay { background-color: rgba('.esc_attr(implode(',', page_builder_hex2rgb($values['overlay_bg_color'])).','.$values['overlay_bg_opacity']).'); }'."\n";

				if (!empty($values['overlay_headline'])) {
					echo $post_widget['css_class'].' .pb_googlemap_headline { color: '.esc_attr($values['overlay_headline_font_color']).'; font-size: '.esc_attr($values['overlay_headline_font_size']).'px; text-align: '.esc_attr($values['overlay_headline_text_align']).'; }'."\n";
				}
				if (!empty($values['overlay_content'])) {
					echo $post_widget['css_class'].' .pb_googlemap_content { color: '.esc_attr($values['overlay_content_font_color']).'; font-size: '.esc_attr($values['overlay_content_font_size']).'px; text-align: '.esc_attr($values['overlay_content_text_align']).'; }'."\n";
				}
				if (!empty($values['show_overlay_button']) && !empty($values['overlay_button'])) {
					echo $post_widget['css_class'].' .pb_googlemap_button { background-color: rgba('.esc_attr(implode(',', page_builder_hex2rgb($values['overlay_button_bg_color'])).','.$values['overlay_button_bg_opacity']).'); border-color: '.esc_attr($values['overlay_button_border_color']).'; color: '.esc_attr($values['overlay_button_font_color']).'; }'."\n";
					echo $post_widget['css_class'].' a.pb_googlemap_button:hover { background-color: rgba('.esc_attr(implode(',', page_builder_hex2rgb($values['overlay_button_bg_color_hover'])).','.$values['overlay_button_bg_opacity_hover']).'); border-color: '.esc_attr($values['overlay_button_border_color_hover']).'; color: '.esc_attr($values['overlay_button_font_color_hover']).'; }'."\n";
				}

				if (!empty($values['overlay_headline']) || !empty($values['overlay_content'])) {
					echo "@media only screen and (max-width: 767px) {\n";
					if (!empty($values['overlay_headline'])) {
						echo '  '.$post_widget['css_class'].' .pb_googlemap_headline { font-size: '.esc_attr($values['overlay_headline_font_size_mobile']).'px; }'."\n";
					}
					if (!empty($values['overlay_headline'])) {
						echo '  '.$post_widget['css_class'].' .pb_googlemap_content { font-size: '.esc_attr($values['overlay_content_font_size_mobile']).'px; }'."\n";
					}
					echo "}\n";
				}
			}
		}
	}
}
