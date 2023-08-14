<?php

/**
 * ページビルダーウィジェット登録
 */
add_page_builder_widget(array(
	'id' => 'pb-widget-tab',
	'form' => 'form_page_builder_widget_tab',
	'form_rightbar' => 'form_rightbar_page_builder_widget_tab',
	'save' => 'save_page_builder_repeater',
	'display' => 'display_page_builder_widget_tab',
	'title' => __('Tab', 'tcd-w'),
	'description' => __('You can display content using tab.', 'tcd-w'),
	'additional_class' => 'pb-repeater-widget',
	'priority' => 31
));

/**
 * 管理画面用js
 */
function page_builder_widget_tab_admin_scripts() {
	wp_enqueue_script('page_builder-tab-admin', get_template_directory_uri().'/pagebuilder/assets/admin/js/tab.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}
add_action('page_builder_admin_scripts', 'page_builder_widget_tab_admin_scripts', 12);

/**
 * フォーム デフォルト値
 */
function get_page_builder_widget_tab_default_values() {
	$default_values = array(
		'widget_index' => '',
		'tab_type' => 'type1',
		'type2_tab_color' => '#000000',
		'type2_tab_bg_color' => '#eeeeee',
		'type2_tab_color_active' => '#ffffff',
		'type2_tab_bg_color_active' => '#222222',
		'type2_tab_color_hover' => '#ffffff',
		'type2_tab_bg_color_hover' => '#222222',
		'type3_tab_color' => '#000000',
		'type3_tab_bg_color' => '#f6f6f6',
		'type3_tab_color_active' => '#000000',
		'type3_tab_bg_color_active' => '#ffffff',
		'type3_tab_color_hover' => '#666666',
		'type3_tab_bg_color_hover' => '#f6f6f6',
		'margin_bottom' => 30,
		'margin_bottom_mobile' => 30,
		'first_tab_open_mobile' => 0,
	);

	return apply_filters('get_page_builder_widget_tab_default_values', $default_values);
}

/**
 * リピーター 行デフォルト値
 */
function get_page_builder_widget_tab_default_row_values() {
	$default_row_values = array(
		'repeater_label' => '',
		'tab_name' => '',
		'content_type' => 'type1',

		// content type1用
		'headline' => '',
		'content' => '',
		'type1_padding' => 30,
		'type1_padding_mobile' => 15,

		// content type2用
		'headline' => '',
		'content' => '',

		// content type2・type3・type4兼用
		'caption' => '',

		// content type2・type3兼用
		'nav_color' => '#ffffff',
		'nav_bg_color' => page_builder_get_secondary_color('#000000'),
		'nav_color_active' => '#ffffff',
		'nav_bg_color_active' => page_builder_get_primary_color('#222222'),

		// content type2リピーター用
		'row2' => array(),
		'row2_index' => array(),

		// content type3リピーター用
		'row3' => array(),
		'row3_index' => array(),

		// content type4用
		'video_type' => 'type1',
		'video_id' => 0,
		'oembed_url' => '',
		'embed_code' => ''
	);

	return apply_filters('get_page_builder_widget_tab_default_row_values', $default_row_values);
}

/**
 * リピーター内 type2/3リピーター用 デフォルト値
 */
function get_page_builder_widget_tab_default_type_row_values($type = null) {
	if (!$type || !is_string($type)) return false;

	// type2
	$default_type2_row_values = array(
		'repeater_level2_label' => '',
		'image' => '',
		'headline' => '',
		'headline_font_size' => 24,
		'headline_font_size_mobile' => 18,
		'headline_font_color' => '#ffffff',
		'headline_font_family' => 'type1',
		'headline_text_align' => 'type1',
		'content' => '',
		'content_font_size' => 14,
		'content_font_size_mobile' => 14,
		'content_font_color' => '#ffffff',
		'content_font_family' => 'type1',
		'content_text_align' => 'type1',
		'content_bg_color' => page_builder_get_primary_color('#000000')
	);

	// type3
	$default_type3_row_values = array(
		'repeater_level2_label' => '',
		'image' => '',
		'caption' => ''
	);

	if ($type === 'type2') {
		$default_type_row_values = apply_filters('get_page_builder_widget_tab_default_type2_row_values', $default_type2_row_values);
	} elseif ($type === 'type3') {
		$default_type_row_values = apply_filters('get_page_builder_widget_tab_default_type3_row_values', $default_type3_row_values);
	} else {
		$default_type_row_values = apply_filters('get_page_builder_widget_tab_default_'.$type.'_row_values', array());
	}

	return apply_filters('get_page_builder_widget_tab_default_type_row_values', $default_type_row_values, $type);
}

/**
 * フォーム
 */
function form_page_builder_widget_tab($values = array()) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_tab_default_values', get_page_builder_widget_tab_default_values(), 'form');

	// 旧バージョン値変換
	if (isset($values['tab_color'])) {
		$values['type2_tab_color'] = $values['tab_color'];
	}
	if (isset($values['tab_bg_color'])) {
		$values['type2_tab_bg_color'] = $values['tab_bg_color'];
	}
	if (isset($values['tab_color_active'])) {
		$values['type2_tab_color_active'] = $values['tab_color_active'];
		$values['type2_tab_color_hover'] = $values['tab_color_active'];
	}
	if (isset($values['tab_bg_color_active'])) {
		$values['type2_tab_bg_color_active'] = $values['tab_bg_color_active'];
		$values['type2_tab_bg_color_hover'] = $values['tab_bg_color_active'];
	}

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);
?>

<div class="form-field form-field-radio form-field-tab_type">
	<h4><?php _e('Tab design', 'tcd-w'); ?></h4>
	<?php
		$radio_options = array(
            'type1' => __('Type 1 (Background color: White fixed, Border: Yes, Arrangement of tab: Left)', 'tcd-w'),
            'type2' => __('Type 2 (background color: any color, border line: none, arrangement of tabs: even)', 'tcd-w'),
            'type3' => __('Type 3 (background color: any color, border line: Yes, arrangement of tabs: even)', 'tcd-w'),
		);
		$radio_html = array();
		foreach($radio_options as $key => $value) {
			$attr = '';
			if ($values['tab_type'] == $key) {
				$attr .= ' checked="checked"';
			}
			$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][tab_type]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
		}
		echo implode("<br>\n\t", $radio_html);
	?>
</div>

<div class="form-field form-field-tab_type-type2 hidden">
	<h4><?php _e('Tab color', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type2_tab_color]" value="<?php echo esc_attr($values['type2_tab_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type2_tab_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type2_tab_bg_color]" value="<?php echo esc_attr($values['type2_tab_bg_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type2_tab_bg_color']); ?>" /></td>
		</tr>
	</table>
</div>

<div class="form-field form-field-tab_type-type2 hidden">
	<h4><?php _e('Active tab color', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type2_tab_color_active]" value="<?php echo esc_attr($values['type2_tab_color_active']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type2_tab_color_active']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type2_tab_bg_color_active]" value="<?php echo esc_attr($values['type2_tab_bg_color_active']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type2_tab_bg_color_active']); ?>" /></td>
		</tr>
	</table>
</div>

<div class="form-field form-field-tab_type-type2 hidden">
	<h4><?php _e('Tab hover color', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type2_tab_color_hover]" value="<?php echo esc_attr($values['type2_tab_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type2_tab_color_hover']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type2_tab_bg_color_hover]" value="<?php echo esc_attr($values['type2_tab_bg_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type2_tab_bg_color_hover']); ?>" /></td>
		</tr>
	</table>
</div>

<div class="form-field form-field-tab_type-type3 hidden">
	<h4><?php _e('Tab color', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type3_tab_color]" value="<?php echo esc_attr($values['type3_tab_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type3_tab_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type3_tab_bg_color]" value="<?php echo esc_attr($values['type3_tab_bg_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type3_tab_bg_color']); ?>" /></td>
		</tr>
	</table>
</div>

<div class="form-field form-field-tab_type-type3 hidden">
	<h4><?php _e('Active tab color', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type3_tab_color_active]" value="<?php echo esc_attr($values['type3_tab_color_active']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type3_tab_color_active']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type3_tab_bg_color_active]" value="<?php echo esc_attr($values['type3_tab_bg_color_active']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type3_tab_bg_color_active']); ?>" /></td>
		</tr>
	</table>
</div>

<div class="form-field form-field-tab_type-type3 hidden">
	<h4><?php _e('Tab hover color', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Font color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type3_tab_color_hover]" value="<?php echo esc_attr($values['type3_tab_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type3_tab_color_hover']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][type3_tab_bg_color_hover]" value="<?php echo esc_attr($values['type3_tab_bg_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['type3_tab_bg_color_hover']); ?>" /></td>
		</tr>
	</table>
</div>

<?php
	// リピーター行の並び
	$repeater_indexes = array();
	if (!empty($values['repeater_index']) && is_array($values['repeater_index'])) {
		$repeater_indexes = $values['repeater_index'];

		// リピーター行データが無ければ削除
		foreach($repeater_indexes as $key => $repeater_index) {
			if (empty($values['repeater'][$repeater_index])) {
				unset($repeater_indexes[$key]);
			}
		}
	} elseif (!empty($values['repeater']) && is_array($values['repeater'])) {
		$repeater_indexes = array_keys($values['repeater']);
	}

	// リピーター行 最大インデックス
	$repeater_index_max = 0;
	if ($repeater_indexes) {
		$repeater_indexes = array_map('intval', $repeater_indexes);
		$repeater_index_max = max($repeater_indexes);
	}

	echo '<div class="pb_repeater_wrap" data-rows="'.$repeater_index_max.'">'."\n";
	echo '	<div class="pb_repeater_sortable">'."\n";

	// リピーター行あり
	if ($repeater_indexes) {
		// リピーター行ループ
		foreach($repeater_indexes as $repeater_index) {
			// リピーター行データあり
			if (!empty($values['repeater'][$repeater_index])) {
				// リピーター行出力
				form_page_builder_widget_tab_repeater_row(
					array(
						'widget_index' => $values['widget_index'],
						'repeater_index' => $repeater_index
					),
					$values['repeater'][$repeater_index]
				);
			}
		}
	}

	echo '	</div>'."\n"; // .pb_repeater_sortable

	// 項目の追加ボタン
	echo '<div class="form-field">';
	echo '<a href="#" class="pb_add_repeater button-primary">'.__('Add item', 'tcd-w').'</a>';
	echo '</div>'."\n";

	// 追加ボタン時に差し込むHTML
	echo '<div class="add_pb_repeater_clone hidden" style="display:none">'."\n";

	// 行出力
	form_page_builder_widget_tab_repeater_row(
		array(
			'widget_index' => $values['widget_index'],
			'repeater_index' => 'pb_repeater_add_index'
		),
		array(
			'repeater_label' => __('New item', 'tcd-w')
		)
	);

	echo '</div>'."\n"; // .add_pb_repeater_clone

	echo '</div>'."\n"; // .pb_repeater_wrap
}

/**
 * リピーター行出力
 */
function form_page_builder_widget_tab_repeater_row($values = array(), $row_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => ''
		),
		(array) $values
	);

	// 行デフォルト値
	$default_row_values = apply_filters('page_builder_widget_tab_default_row_values', get_page_builder_widget_tab_default_row_values());

	// 行デフォルト値に行の値をマージ
	$row_values = array_merge(
		$default_row_values,
		(array) $row_values
	);

	// リピーター表示名
	if (!$row_values['repeater_label'] && $row_values['tab_name']) {
		$row_values['repeater_label'] = $row_values['tab_name'];
	} elseif (!$row_values['repeater_label']) {
		$row_values['repeater_label'] = __('New item', 'tcd-w');
	}
?>

<div id="pb_tab-<?php echo esc_attr($values['widget_index'].'-'.$values['repeater_index']); ?>" class="pb_repeater pb_repeater-<?php echo esc_attr($values['repeater_index']); ?>">
	<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater_index][]" value="<?php echo esc_attr($values['repeater_index']); ?>" />
	<ul class="pb_repeater_button pb_repeater_cf">
		<li><span class="pb_repeater_move"><?php _e('Move', 'tcd-w'); ?></span></li>
		<li><span class="pb_repeater_delete" data-confirm="<?php _e('Are you sure you want to delete this item?', 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></span></li>
	</ul>
	<div class="pb_repeater_content">
		<h3 class="pb_repeater_headline"><span class="index_label" data-empty="<?php _e('New item', 'tcd-w'); ?>"><?php echo esc_html($row_values['repeater_label']); ?></span><a href="#"><?php _e('Open', 'tcd-w'); ?></a></h3>
		<div class="pb_repeater_field">
			<div class="form-field">
				<h4><?php _e('Name', 'tcd-w'); ?></h4>
				<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][tab_name]" value="<?php echo esc_attr($row_values['tab_name']); ?>" class="index_label pb-input-overview" />
			</div>

			<div class="form-field form-field-radio form-field-content_type">
				<h4><?php _e('Content Type', 'tcd-w'); ?></h4>
				<?php
					$radio_options = array(
                        'type1' => __('Text only', 'tcd-w'),
                        'type2' => __('Image + text (you can add items)', 'tcd-w'),
                        'type3' => __('Image gallery (you can add items)', 'tcd-w'),
                        'type4' => __('Video / Youtube', 'tcd-w')
					);
					$radio_html = array();
					foreach($radio_options as $key => $value) {
						$attr = '';
						if ($row_values['content_type'] == $key) {
							$attr .= ' checked="checked"';
						}
						$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][repeater]['.esc_attr($values['repeater_index']).'][content_type]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
					}
					echo implode("<br>\n\t", $radio_html);
				?>
			</div>

			<div class="form-field form-field-content_type-type1">
				<h4><?php _e('Headline', 'tcd-w'); ?></h4>
				<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][headline]" value="<?php echo esc_attr($row_values['headline']); ?>" />
			</div>

			<div class="form-field form-field-editor form-field-content_type-type1">
				<h4><?php _e('Sentence', 'tcd-w'); ?></h4>
				<?php
					wp_editor(
						$row_values['content'],
						str_replace('-', '_', 'pb_tab_'.$values['widget_index'].'_'.$values['repeater_index'].'_content'),
						array(
							'textarea_name' => 'pagebuilder[widget]['.$values['widget_index'].'][repeater]['.$values['repeater_index'].'][content]',
							'textarea_rows' => 10
						)
					);
				?>
			</div>

			<div class="form-field form-field-content_type-type1">
				<h4><?php _e('Padding', 'tcd-w'); ?></h4>
				<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][type1_padding]" value="<?php echo esc_attr($row_values['type1_padding']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field form-field-content_type-type1">
				<h4><?php _e('Padding for mobile', 'tcd-w'); ?></h4>
				<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][type1_padding_mobile]" value="<?php echo esc_attr($row_values['type1_padding_mobile']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field form-field-content_type-type2 hidden">
				<?php
					// リピーター内リピーター type2
					form_page_builder_widget_tab_repeater_level2_rows_type2($values, $row_values);
				?>
			</div>

			<div class="form-field form-field-content_type-type3 hidden">
				<?php
					// リピーター内リピーター type3
					form_page_builder_widget_tab_repeater_level2_rows_type3($values, $row_values);
				?>
			</div>

			<div class="form-field form-field-radio form-field-content_type-type4 hidden">
				<div class="form-field form-field-video_type">
					<h4><?php _e('Video type', 'tcd-w'); ?></h4>
					<?php
						$radio_options = array(
							'type1' => __('Video upload', 'tcd-w'),
							'type2' => __('Youtube / oEmbed', 'tcd-w'),
							'type3' => __('Embed code', 'tcd-w')
						);
						$radio_html = array();
						foreach($radio_options as $key => $value) {
							$attr = '';
							if ($row_values['video_type'] == $key) {
								$attr .= ' checked="checked"';
							}
							$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][repeater]['.esc_attr($values['repeater_index']).'][video_type]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
						}
						echo implode("<br>\n\t", $radio_html);
					?>
				</div>

				<div class="form-field form-field-video_type-type1">
					<h4><?php _e('Video upload', 'tcd-w'); ?></h4>
					<?php
						$input_name = 'pagebuilder[widget]['.esc_attr($values['widget_index']).'][repeater]['.esc_attr($values['repeater_index']).'][video_id]';
						$media_id = $row_values['video_id'];
						pb_media_form($input_name, $media_id, true);
					?>
				</div>

				<div class="form-field form-field-video_type-type2 hidden">
					<h4><?php _e('oEmbed url', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][oembed_url]" value="<?php echo esc_attr($row_values['oembed_url']); ?>" />
					<p class="pb-description"><?php
						if (strtolower(get_locale()) == 'ja') {
							$wp_url = 'http://wpdocs.osdn.jp/oEmbed#.E3.81.93.E3.81.AE.E3.83.A1.E3.83.87.E3.82.A3.E3.82.A2.E5.9F.8B.E3.82.81.E8.BE.BC.E3.81.BF.E6.A9.9F.E8.83.BD.E3.82.92.E4.BD.BF.E3.81.88.E3.82.8B.E3.82.B5.E3.82.A4.E3.83.88.E3.81.AF.EF.BC.9F';
						} else {
							$wp_url = 'https://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F';
						}
                    printf(__('Please enter the media URL of<a href="%s" target="_blank">oEmbed-compatible site</a>supported by WordPress such as Youtube', 'tcd-w'), $wp_url);
					?></p>
				</div>

				<div class="form-field form-field-video_type-type3 hidden">
					<h4><?php _e('Embed code', 'tcd-w'); ?></h4>
					<textarea name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][embed_code]" rows="4"><?php echo esc_textarea($row_values['embed_code']); ?></textarea>
				</div>
			</div>

			<div class="form-field form-field-content_type-type2 form-field-content_type-type3 hidden">
				<h4><?php _e('Navigation color', 'tcd-w'); ?></h4>
				<table style="margin-top:5px;">
					<tr>
						<td><?php _e('Font color', 'tcd-w'); ?></td>
						<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][nav_color]" value="<?php echo esc_attr($row_values['nav_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['nav_color']); ?>" /></td>
					</tr>
					<tr>
						<td><?php _e('Background color', 'tcd-w'); ?></td>
						<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][nav_bg_color]" value="<?php echo esc_attr($row_values['nav_bg_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['nav_bg_color']); ?>" /></td>
					</tr>
				</table>
			</div>

			<div class="form-field form-field-content_type-type2 form-field-content_type-type3 hidden">
				<h4><?php _e('Active navigation color', 'tcd-w'); ?></h4>
				<table style="margin-top:5px;">
					<tr>
						<td><?php _e('Font color', 'tcd-w'); ?></td>
						<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][nav_color_active]" value="<?php echo esc_attr($row_values['nav_color_active']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['nav_color_active']); ?>" /></td>
					</tr>
					<tr>
						<td><?php _e('Background color', 'tcd-w'); ?></td>
						<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][nav_bg_color_active]" value="<?php echo esc_attr($row_values['nav_bg_color_active']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['nav_bg_color_active']); ?>" /></td>
					</tr>
				</table>
			</div>

			<div class="form-field form-field-content_type-type2 form-field-content_type-type3 form-field-content_type-type3 form-field-content_type-type4 hidden">
				<h4><?php _e('Tab caption', 'tcd-w'); ?></h4>
				<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][caption]" value="<?php echo esc_attr($row_values['caption']); ?>" />
			</div>
		</div>
	</div>
</div>

<?php
}

/**
 * リピーター内 リピーター出力 type2
 */
function form_page_builder_widget_tab_repeater_level2_rows_type2($values = array(), $repeater_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => '',
			'repeater_level2_index' => '',
		),
		(array) $values
	);

	// リピーター行の並び
	$repeater_level2_indexes = array();
	if (!empty($repeater_values['row2_index']) && is_array($repeater_values['row2_index'])) {
		$repeater_level2_indexes = $repeater_values['row2_index'];

		// リピーター行データが無ければ削除
		foreach($repeater_level2_indexes as $key => $repeater_level2_index) {
			if (empty($repeater_values['row2'][$repeater_level2_index])) {
				unset($repeater_level2_indexes[$key]);
			}
		}
	} elseif (!empty($repeater_values['row2']) && is_array($repeater_values['row2'])) {
		$repeater_level2_indexes = array_keys($repeater_values['row2']);
	}

	// リピーター行 最大インデックス
	$repeater_level2_index_max = 0;
	if ($repeater_level2_indexes) {
		$repeater_level2_indexes = array_map('intval', $repeater_level2_indexes);
		$repeater_level2_index_max = max($repeater_level2_indexes);
	}

	echo '<div class="pb_repeater_wrap pb_repeater_level2_wrap" data-rows="'.$repeater_level2_index_max.'">'."\n";
	echo '	<div class="pb_repeater_level2 pb_repeater_level2_sortable">'."\n";

	// リピーター行あり
	if ($repeater_level2_indexes) {
		// リピーター行ループ
		foreach($repeater_level2_indexes as $repeater_level2_index) {
			// リピーター行データあり
			if (!empty($repeater_values['row2'][$repeater_level2_index])) {
				// リピーター行出力
				form_page_builder_widget_tab_repeater_level2_row_type2(
					array(
						'widget_index' => $values['widget_index'],
						'repeater_index' => $values['repeater_index'],
						'repeater_level2_index' => $repeater_level2_index
					),
					$repeater_values['row2'][$repeater_level2_index]
				);
			}
		}
	}

	echo '	</div>'."\n"; // .pb_repeater_sortable

	// 追加ボタン時に差し込むHTML
	ob_start();
	form_page_builder_widget_tab_repeater_level2_row_type2(
		array(
			'widget_index' => $values['widget_index'],
			'repeater_index' => $values['repeater_index'],
			'repeater_level2_index' => 'pb_repeater_level2_add_index'
		)
	);
	$add_repeater_level2_html = ob_get_clean();

	// 項目の追加ボタン
	echo '<div class="form-field">';
	echo '<a href="#" class="pb_add_repeater_level2 button-primary" data-clone="'.esc_attr($add_repeater_level2_html).'">'.__('Add item', 'tcd-w').'</a>';
	echo '</div>'."\n";

	echo '</div>'."\n"; // .pb_repeater_wrap
}

/**
 * リピーター内 リピーター行出力 type2
 */
function form_page_builder_widget_tab_repeater_level2_row_type2($values = array(), $repeater_level2_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => '',
			'repeater_level2_index' => '',
		),
		(array) $values
	);

	// 行デフォルト値
	$default_row_values = apply_filters('page_builder_widget_tab_repeater_level2_type2_default_row_values', get_page_builder_widget_tab_default_type_row_values('type2'));

	// 行デフォルト値に行の値をマージ
	$repeater_level2_values = array_merge(
		$default_row_values,
		(array) $repeater_level2_values
	);

	// リピーター表示名
	if (!$repeater_level2_values['repeater_level2_label'] && $repeater_level2_values['headline']) {
		$repeater_level2_values['repeater_level2_label'] = $repeater_level2_values['headline'];
	} elseif (!$repeater_level2_values['repeater_level2_label']) {
		$repeater_level2_values['repeater_level2_label'] = __('New item', 'tcd-w');
	}

	// name属性ベース
	$repeater_level2_name_base = 'pagebuilder[widget]['.$values['widget_index'].'][repeater]['.$values['repeater_index'].'][row2]['.$values['repeater_level2_index'].']';

	// font family 選択肢
	$font_family_options = array(
		'type1' => __('Meiryo', 'tcd-w'),
		'type2' => __('YuGothic', 'tcd-w'),
		'type3' => __('YuMincho', 'tcd-w'),
	);

	// font family 選択肢
	$text_align_options = array(
		'left' => __('Align left', 'tcd-w'),
		'center' => __('Align center', 'tcd-w'),
		'right' => __('Align right', 'tcd-w')
	);
?>

<div id="pb_tab-<?php echo esc_attr($values['widget_index'].'-'.$values['repeater_index'].'-'.$values['repeater_level2_index']); ?>" class="pb_repeater pb_repeater-<?php echo esc_attr($values['repeater_level2_index']); ?>">
	<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][row2_index][]" value="<?php echo esc_attr($values['repeater_level2_index']); ?>" />
	<ul class="pb_repeater_button pb_repeater_cf">
		<li><span class="pb_repeater_level2_move"><?php _e('Move', 'tcd-w'); ?></span></li>
		<li><span class="pb_repeater_delete" data-confirm="<?php _e('Are you sure you want to delete this item?', 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></span></li>
	</ul>
	<div class="pb_repeater_content">
		<h3 class="pb_repeater_headline"><span class="index_label" data-empty="<?php _e('New item', 'tcd-w'); ?>"><?php echo esc_html($repeater_level2_values['repeater_level2_label']); ?></span><a href="#"><?php _e('Open', 'tcd-w'); ?></a></h3>
		<div class="pb_repeater_field">
			<div class="form-field">
				<h4><?php _e('Image', 'tcd-w'); ?></h4>
				<?php
					$input_name = $repeater_level2_name_base.'[image]';
					$media_id = $repeater_level2_values['image'];
					pb_media_form($input_name, $media_id);
				?>
			</div>

			<div class="form-field">
				<h4><?php _e('Headline', 'tcd-w'); ?></h4>
				<textarea name="<?php echo esc_attr($repeater_level2_name_base); ?>[headline]" rows="2" class="index_label"><?php echo esc_textarea($repeater_level2_values['headline']); ?></textarea>
				<table style="margin-top:5px;">
					<tr>
						<td><?php _e('Font size', 'tcd-w'); ?></td>
						<td><input type="number" name="<?php echo esc_attr($repeater_level2_name_base); ?>[headline_font_size]" value="<?php echo esc_attr($repeater_level2_values['headline_font_size']); ?>" class="small-text" min="0" /> px</td>
					</tr>
					<tr>
						<td><?php _e('Font size for mobile', 'tcd-w'); ?></td>
						<td><input type="numer" name="<?php echo esc_attr($repeater_level2_name_base); ?>[headline_font_size_mobile]" value="<?php echo esc_attr($repeater_level2_values['headline_font_size_mobile']); ?>" class="small-text" min="0" /> px</td>
					</tr>
					<tr>
						<td><?php _e('Font color', 'tcd-w'); ?></td>
						<td><input type="text" name="<?php echo esc_attr($repeater_level2_name_base); ?>[headline_font_color]" value="<?php echo esc_attr($repeater_level2_values['headline_font_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['headline_font_color']); ?>" /></td>
					</tr>
					<tr>
						<td><?php _e('Font family', 'tcd-w'); ?></td>
						<td>
							<select name="<?php echo esc_attr($repeater_level2_name_base); ?>[headline_font_family]">
								<?php
									foreach($font_family_options as $key => $value) {
										$attr = '';
										if ($repeater_level2_values['headline_font_family'] == $key) {
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
							<select name="<?php echo esc_attr($repeater_level2_name_base); ?>[headline_text_align]">
								<?php
									foreach($text_align_options as $key => $value) {
										$attr = '';
										if ($repeater_level2_values['headline_text_align'] == $key) {
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

			<div class="form-field">
				<h4><?php _e('Description', 'tcd-w'); ?></h4>
				<textarea name="<?php echo esc_attr($repeater_level2_name_base); ?>[content]" rows="4"><?php echo esc_textarea($repeater_level2_values['content']); ?></textarea>
				<table style="margin-top:5px;">
					<tr>
						<td><?php _e('Font size', 'tcd-w'); ?></td>
						<td><input type="number" name="<?php echo esc_attr($repeater_level2_name_base); ?>[content_font_size]" value="<?php echo esc_attr($repeater_level2_values['content_font_size']); ?>" class="small-text" min="0" /> px</td>
					</tr>
					<tr>
						<td><?php _e('Font size for mobile', 'tcd-w'); ?></td>
						<td><input type="number" name="<?php echo esc_attr($repeater_level2_name_base); ?>[content_font_size_mobile]" value="<?php echo esc_attr($repeater_level2_values['content_font_size_mobile']); ?>" class="small-text" min="0" /> px</td>
					</tr>
					<tr>
						<td><?php _e('Font color', 'tcd-w'); ?></td>
						<td><input type="text" name="<?php echo esc_attr($repeater_level2_name_base); ?>[content_font_color]" value="<?php echo esc_attr($repeater_level2_values['content_font_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['content_font_color']); ?>" /></td>
					</tr>
					<tr>
						<td><?php _e('Font family', 'tcd-w'); ?></td>
						<td>
							<select name="<?php echo esc_attr($repeater_level2_name_base); ?>[content_font_family]">
								<?php
									foreach($font_family_options as $key => $value) {
										$attr = '';
										if ($repeater_level2_values['content_font_family'] == $key) {
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
							<select name="<?php echo esc_attr($repeater_level2_name_base); ?>[content_text_align]">
								<?php
									foreach($text_align_options as $key => $value) {
										$attr = '';
										if ($repeater_level2_values['content_text_align'] == $key) {
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

			<div class="form-field">
				<h4><?php _e('Background Color', 'tcd-w'); ?></h4>
				<input type="text" name="<?php echo esc_attr($repeater_level2_name_base); ?>[content_bg_color]" value="<?php echo esc_attr($repeater_level2_values['content_bg_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['content_bg_color']); ?>" />
			</div>
		</div>
	</div>
</div>

<?php
}

/**
 * リピーター内 リピーター出力 type3
 */
function form_page_builder_widget_tab_repeater_level2_rows_type3($values = array(), $repeater_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => '',
			'repeater_level2_index' => '',
		),
		(array) $values
	);

	// リピーター行の並び
	$repeater_level2_indexes = array();
	if (!empty($repeater_values['row3_index']) && is_array($repeater_values['row3_index'])) {
		$repeater_level2_indexes = $repeater_values['row3_index'];

		// リピーター行データが無ければ削除
		foreach($repeater_level2_indexes as $key => $repeater_level2_index) {
			if (empty($repeater_values['row3'][$repeater_level2_index])) {
				unset($repeater_level2_indexes[$key]);
			}
		}
	} elseif (!empty($repeater_values['row3']) && is_array($repeater_values['row3'])) {
		$repeater_level2_indexes = array_keys($repeater_values['row3']);
	}

	// リピーター行 最大インデックス
	$repeater_level2_index_max = 0;
	if ($repeater_level2_indexes) {
		$repeater_level2_indexes = array_map('intval', $repeater_level2_indexes);
		$repeater_level2_index_max = max($repeater_level2_indexes);
	}

	echo '<div class="pb_repeater_wrap pb_repeater_level2_wrap" data-rows="'.$repeater_level2_index_max.'">'."\n";
	echo '	<div class="pb_repeater_level2 pb_repeater_level2_sortable">'."\n";

	// リピーター行あり
	if ($repeater_level2_indexes) {
		// リピーター行ループ
		foreach($repeater_level2_indexes as $repeater_level2_index) {
			// リピーター行データあり
			if (!empty($repeater_values['row3'][$repeater_level2_index])) {
				// リピーター行出力
				form_page_builder_widget_tab_repeater_level2_row_type3(
					array(
						'widget_index' => $values['widget_index'],
						'repeater_index' => $values['repeater_index'],
						'repeater_level2_index' => $repeater_level2_index
					),
					$repeater_values['row3'][$repeater_level2_index]
				);
			}
		}
	}

	echo '	</div>'."\n"; // .pb_repeater_sortable

	// 追加ボタン時に差し込むHTML
	ob_start();
	form_page_builder_widget_tab_repeater_level2_row_type3(
		array(
			'widget_index' => $values['widget_index'],
			'repeater_index' => $values['repeater_index'],
			'repeater_level2_index' => 'pb_repeater_level2_add_index'
		)
	);
	$add_repeater_level2_html = ob_get_clean();

	// 項目の追加ボタン
	echo '<div class="form-field">';
	echo '<a href="#" class="pb_add_repeater_level2 button-primary" data-clone="'.esc_attr($add_repeater_level2_html).'">'.__('Add item', 'tcd-w').'</a>';
	echo '</div>'."\n";

	echo '</div>'."\n"; // .pb_repeater_wrap
}

/**
 * リピーター内 リピーター行出力 type3
 */
function form_page_builder_widget_tab_repeater_level2_row_type3($values = array(), $repeater_level2_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => '',
			'repeater_level2_index' => '',
		),
		(array) $values
	);

	// 行デフォルト値
	$default_row_values = apply_filters('page_builder_widget_tab_repeater_level2_type3_default_row_values', get_page_builder_widget_tab_default_type_row_values('type3'));

	// 行デフォルト値に行の値をマージ
	$repeater_level2_values = array_merge(
		$default_row_values,
		(array) $repeater_level2_values
	);

	// リピーター表示名
	if (!$repeater_level2_values['repeater_level2_label']) {
		$repeater_level2_values['repeater_level2_label'] = __('Image', 'tcd-w').' '.$values['repeater_level2_index'];
	}

	// name属性ベース
	$repeater_level2_name_base = 'pagebuilder[widget]['.$values['widget_index'].'][repeater]['.$values['repeater_index'].'][row3]['.$values['repeater_level2_index'].']';
?>

<div id="pb_tab-<?php echo esc_attr($values['widget_index'].'-'.$values['repeater_index'].'-'.$values['repeater_level2_index']); ?>" class="pb_repeater pb_repeater-<?php echo esc_attr($values['repeater_level2_index']); ?>">
	<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][row3_index][]" value="<?php echo esc_attr($values['repeater_level2_index']); ?>" />
	<ul class="pb_repeater_button pb_repeater_cf">
		<li><span class="pb_repeater_level2_move"><?php _e('Move', 'tcd-w'); ?></span></li>
		<li><span class="pb_repeater_delete" data-confirm="<?php _e('Are you sure you want to delete this item?', 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></span></li>
	</ul>
	<div class="pb_repeater_content">
		<h3 class="pb_repeater_headline"><span class="index_label" data-empty="<?php _e('New item', 'tcd-w'); ?>"><?php echo esc_html($repeater_level2_values['repeater_level2_label']); ?></span><a href="#"><?php _e('Open', 'tcd-w'); ?></a></h3>
		<div class="pb_repeater_field">
			<div class="form-field">
				<h4><?php _e('Image', 'tcd-w'); ?></h4>
				<?php
					$input_name = $repeater_level2_name_base.'[image]';
					$media_id = $repeater_level2_values['image'];
					pb_media_form($input_name, $media_id);
				?>
			</div>

			<div class="form-field">
				<h4><?php _e('Image caption', 'tcd-w'); ?></h4>
				<input type="text" name="<?php echo esc_attr($repeater_level2_name_base); ?>[caption]" value="<?php echo esc_attr($repeater_level2_values['caption']); ?>" class="index_label" />
			</div>
		</div>
	</div>
</div>

<?php
}

/**
 * クローン用のリッチエディター化処理をしないようにする
 * クローン後のリッチエディター化はjsで行う
 */
function page_builder_widget_tab_tiny_mce_before_init($mceInit, $editor_id) {
	if (strpos($editor_id, 'pb_tab_') == 0 && strpos($editor_id, '_pb_repeater_add_index_content') !== false) {
		$mceInit['wp_skip_init'] = true;
	}
	return $mceInit;
}
add_filter('tiny_mce_before_init', 'page_builder_widget_tab_tiny_mce_before_init', 10, 2);

/**
 * フォーム 右サイドバー
 */
function form_rightbar_page_builder_widget_tab($values = array(), $widget_id = null, $args = array()) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_tab_default_values', get_page_builder_widget_tab_default_values(), 'form_rightbar');

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);
?>

<h3 data-pb-toggle-target=".pb-right-sidebar-display" data-pb-toggle-status="open"><?php _e('Display setting', 'tcd-w'); ?></h3>
<div class="pb-toggle-content pb-right-sidebar-display">
	<div class="form-field">
		<label><input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][first_tab_open_mobile]" value="1"<?php if ($values['first_tab_open_mobile']) echo ' checked="checked"'; ?> /> <?php _e('Open first tab for mobile', 'tcd-w'); ?></label>
	</div>
</div>

<?php
	// 以降は標準右サイドバー
	form_rightbar_page_builder_widget($values, $widget_id, $args);
}


/**
 * フロント出力
 */
function display_page_builder_widget_tab($values = array(), $widget_index = null) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_tab_default_values', get_page_builder_widget_tab_default_values(), 'form');

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);

	// リピーター行の並び
	if (!empty($values['repeater_index']) && is_array($values['repeater_index'])) {
		$repeater_indexes = $values['repeater_index'];

		// リピーター行ループし、行データが無ければ削除
		foreach($repeater_indexes as $key => $repeater_index) {
			if (empty($values['repeater'][$repeater_index])) {
				unset($repeater_indexes[$key]);
			}
		}
	} elseif (!empty($values['repeater']) && is_array($values['repeater'])) {
		$repeater_indexes = array_keys($values['repeater']);
	}

	// リピーター行がなければ終了
	if (empty($repeater_indexes)) return;

	// 行デフォルト値
	$default_row_values = apply_filters('page_builder_widget_tab_default_row_values', get_page_builder_widget_tab_default_row_values());

	// ths_contentフィルターフラグ
	$is_remove_filter_the_content = false;
?>
<div class="pb_tab pb_tab-<?php
	echo esc_attr($values['tab_type']);
	if (!empty($values['first_tab_open_mobile'])) {
		echo ' pb_tab-first_tab_open_mobile';
	}
?>">
  <ul class="resp-tabs-list">
<?php
	foreach($repeater_indexes as $repeater_index) {
		$repeater_values = $values['repeater'][$repeater_index];
		$repeater_values = array_merge($default_row_values, $repeater_values);

		echo '<li>'.esc_html($repeater_values['tab_name']).'</li>';
	}
?>
  </ul>
  <div class="resp-tabs-container">
<?php
	$i = 0;
	foreach($repeater_indexes as $repeater_index) {
		$i++;
		$repeater_values = $values['repeater'][$repeater_index];
		$repeater_values = array_merge($default_row_values, $repeater_values);

		// content type2
		if ($repeater_values['content_type'] == 'type2') {
?>
    <div class="pb_tab_content pb_tab_content-<?php echo $i; ?> pb_tab_content-type2">
      <div class="pb_tab_slider pb_tab_slider-type2">
<?php
			// リピーター内リピーター行の並び
			if (!empty($repeater_values['row2_index']) && is_array($repeater_values['row2_index'])) {
				$j = 0;
				// リピーター内リピーター行ループ
				foreach($repeater_values['row2_index'] as $key => $repeater_level2_index) {
					if (empty($repeater_values['row2'][$repeater_level2_index])) {
						continue;
					}

					$repeater_level2_values = $repeater_values['row2'][$repeater_level2_index];
					$content_html = '';
					$image_html = '';

					if (!empty($repeater_level2_values['headline'])) {
						$content_html .= '<h3 class="pb_tab_headline pb_font_family_'.esc_attr($repeater_level2_values['headline_font_family']).'">'.str_replace(array("\r\n", "\r", "\n"), '<br>', esc_html($repeater_level2_values['headline'])).'</h3>';
					}

					if (!empty($repeater_level2_values['content'])) {
						$content_html .= '<div class="pb_tab_description pb_font_family_'.esc_attr($repeater_level2_values['content_font_family']).'">'.str_replace(array("\r\n", "\r", "\n"), '<br>', esc_html($repeater_level2_values['content'])).'</div>';
					}

					if (!empty($repeater_level2_values['image'])) {
						$image_html = wp_get_attachment_image($repeater_level2_values['image'], 'full');
					}

					$j++;
					echo '<div class="pb_tab_slider_item pb_tab_slider_item-'.$j.'"><div class="pb_tab_slider_item_inner">'."\n";
					echo '<div class="pb_tab_text">'.$content_html.'</div>'."\n";
					echo '<div class="pb_tab_image">'.$image_html.'</div>'."\n";
					echo '</div></div>'."\n";
				}
			}
?>
      </div>
<?php
			if ($repeater_values['caption']) {
				echo '<div class="pb_tab_caption">'.esc_html($repeater_values['caption']).'</div>';
			}
?>
    </div>
<?php
		// content type3
		} elseif ($repeater_values['content_type'] == 'type3') {
?>
    <div class="pb_tab_content pb_tab_content-<?php echo $i; ?> pb_tab_content-type3">
      <div class="pb_tab_slider pb_tab_slider-type3">
<?php
			// リピーター内リピーター行の並び
			$j = 0;
			if (!empty($repeater_values['row3_index']) && is_array($repeater_values['row3_index'])) {
				// リピーター内リピーター行ループ
				foreach($repeater_values['row3_index'] as $key => $repeater_level2_index) {
					if (empty($repeater_values['row3'][$repeater_level2_index])) {
						continue;
					}

					$repeater_level2_values = $repeater_values['row3'][$repeater_level2_index];
					$image_html = '';

					if (!empty($repeater_level2_values['image'])) {
						$image_html = wp_get_attachment_image($repeater_level2_values['image'], 'full');
					}

					if (!$image_html) {
						continue;
					}

					if (!empty($repeater_level2_values['caption'])) {
						$image_html .= "\n".'<div class="pb_tab_image_caption">'.esc_html($repeater_level2_values['caption']).'</div>';
					}

					$j++;
					echo '<div class="pb_tab_slider_item pb_tab_slider_item-'.$j.'">'."\n".$image_html."\n</div>\n";
				}
			}
?>
      </div>
<?php
			if ($repeater_values['caption']) {
				echo '<div class="pb_tab_caption">'.esc_html($repeater_values['caption']).'</div>';
			}
?>
    </div>
<?php
		// content type4
		} elseif ($repeater_values['content_type'] == 'type4') {
?>
    <div class="pb_tab_content pb_tab_content-<?php echo $i; ?> pb_tab_content-type4">
<?php
			// video type2
			if ($repeater_values['video_type'] == 'type2' && $repeater_values['oembed_url']) {
				echo wp_oembed_get($repeater_values['oembed_url']);

			// video type3
			} elseif ($repeater_values['video_type'] == 'type3' && $repeater_values['embed_code']) {
				echo $repeater_values['embed_code'];

			// video type1
			} elseif ($repeater_values['video_id'] && ($video_url = wp_get_attachment_url($repeater_values['video_id']))) {
				echo '<video src="'.esc_attr($video_url).'" controls></video>';
			}

			if ($repeater_values['caption']) {
				echo '<div class="pb_tab_caption">'.esc_html($repeater_values['caption']).'</div>';
			}
?>
    </div>
<?php
		// content type1
		} else {
?>
    <div class="pb_tab_content pb_tab_content-<?php echo $i; ?> pb_tab_content-type1">
<?php
			if (!empty($repeater_values['headline'])) {
?>
      <h3 class="pb_headline"><?php echo esc_html($repeater_values['headline']); ?></h3>
<?php
			}
			if (!empty($repeater_values['content'])) {
				if (!$is_remove_filter_the_content) {
					remove_filter('the_content', 'page_builder_filter_the_content', 8);
					$is_remove_filter_the_content = true;
				}
				echo apply_filters('the_content', $repeater_values['content']);
			}
?>
    </div>
<?php
		}
	}
?>

  </div>
</div>
<?php

	// ths_contentフィルターを戻す
	if ($is_remove_filter_the_content) {
		add_filter('the_content', 'page_builder_filter_the_content', 8);
	}
}

/**
 * フロント用js・css
 */
function page_builder_widget_tab_sctipts() {
	if (page_builder_widget_tab_has_slick()) {
		page_builder_slick_enqueue_script();
	}
	wp_enqueue_script('page_builder-tab', get_template_directory_uri().'/pagebuilder/assets/js/tab.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}

function page_builder_widget_tab_styles() {
	if (page_builder_widget_tab_has_slick()) {
		page_builder_slick_enqueue_style();
	}
	wp_enqueue_style('page_builder-tab', get_template_directory_uri().'/pagebuilder/assets/css/tab.css', false, PAGE_BUILDER_VERSION);
}

function page_builder_widget_tab_sctipts_styles() {
	if (is_singular() && is_page_builder() && page_builder_has_widget('pb-widget-tab')) {
		add_action('wp_enqueue_scripts', 'page_builder_widget_tab_sctipts', 11);
		add_action('wp_enqueue_scripts', 'page_builder_widget_tab_styles', 11);
		add_action('wp_head', 'pb_tab_script_header');
		add_action('page_builder_css', 'page_builder_widget_tab_css');
	}
}
add_action('wp', 'page_builder_widget_tab_sctipts_styles');

function pb_tab_script_header() {
?>
<script type="text/javascript">
jQuery(document).ready(function($){
  if (typeof $.fn.easyResponsiveTabs == 'undefined') return;
  $('.pb_tab').easyResponsiveTabs();

<?php // スマホの場合はタブを閉じた状態にする first_tab_open_mobileなしのみ ?>
  if ($(window).width() < 768) {
    $('.pb_tab:not(.pb_tab-first_tab_open_mobile)').each(function(){
      $('.resp-tab-active', this).removeClass('resp-tab-active');
      $('.resp-tab-content-active', this).removeClass('resp-tab-content-active').hide();
    });
  }

<?php
	if (page_builder_widget_tab_has_slick()) {
?>
  if (typeof $.fn.slick == 'undefined') return;
<?php
		if (page_builder_widget_tab_has_slick('type2')) {
?>
  $('.pb_tab_slider-type2').slick({
    infinite: false,
    dots: true,
    arrows: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    autoplay: false,
    fade: true,
    speed: 1000
  });
<?php
		}
		if (page_builder_widget_tab_has_slick('type3')) {
?>
  $('.pb_tab_slider-type3').slick({
    infinite: true,
    dots: false,
    arrows: true,
    prevArrow: '<button type="button" class="slick-prev">&#xe90f;</button>',
    nextArrow: '<button type="button" class="slick-next">&#xe910;</button>',
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    autoplay: false,
    fade: false,
    speed: 1000
  });
<?php
		}
		if (page_builder_widget_tab_has_slick('type4')) {
			// youtubeのiframeを16:9に高さ調整
?>
  if ($('.pb_tab .pb_tab_content-type4 iframe[src*=youtube\\.com\\/embed]')) {
    $(window).on('resize orientationchange', function(){
      $('.pb_tab .pb_tab_content-type4 iframe[src*=youtube\\.com\\/embed]').each(function(){
        $(this).height(Math.floor($(this).closest('.pb_tab').width() / 16 * 9));
      });
    }).trigger('resize');
  }
<?php
		}
?>
  $('.pb_tab').on('click', '.resp-tab-item, .resp-accordion', function(){
    $(this).closest('.pb_tab').find('.resp-tab-content-active .pb_tab_slider').slick('setPosition');
  });

  setTimeout(function(){
    $('.pb_tab_slider').slick('setPosition');
  }, 300);
<?php
	}
?>
});
</script>
<?php
}

function page_builder_widget_tab_css() {
	// 現記事で使用しているtabコンテンツデータを取得
	$post_widgets = get_page_builder_post_widgets(get_the_ID(), 'pb-widget-tab');
	if ($post_widgets) {
		$css = array();
		$css_mobile = array();

		// 行デフォルト値
		$default_row_values = apply_filters('page_builder_widget_tab_default_row_values', get_page_builder_widget_tab_default_row_values());

		foreach($post_widgets as $post_widget) {
			$widget_index = $post_widget['widget_index'];
			$values = $post_widget['widget_value'];

			// リピーター行の並び
			if (!empty($values['repeater_index']) && is_array($values['repeater_index'])) {
				$repeater_indexes = $values['repeater_index'];

				// リピーター行ループし、行データが無ければ削除
				foreach($repeater_indexes as $key => $repeater_index) {
					if (empty($values['repeater'][$repeater_index])) {
						unset($repeater_indexes[$key]);
					}
				}
			} elseif (!empty($values['repeater']) && is_array($values['repeater'])) {
				$repeater_indexes = array_keys($values['repeater']);
			}

			// リピーター行がなければ終了
			if (empty($repeater_indexes)) continue;

			// tab type2
			if ($values['tab_type'] == 'type2') {
				// 旧バージョン値変換
				if (isset($values['tab_color'])) {
					$values['type2_tab_color'] = $values['tab_color'];
				}
				if (isset($values['tab_bg_color'])) {
					$values['type2_tab_bg_color'] = $values['tab_bg_color'];
				}
				if (isset($values['tab_color_active'])) {
					$values['type2_tab_color_active'] = $values['tab_color_active'];
					$values['type2_tab_color_hover'] = $values['tab_color_active'];
				}
				if (isset($values['tab_bg_color_active'])) {
					$values['type2_tab_bg_color_active'] = $values['tab_bg_color_active'];
					$values['type2_tab_bg_color_hover'] = $values['tab_bg_color_active'];
				}

				$css[] = $post_widget['css_class'].' .pb_tab-type2 ul.resp-tabs-list li { color: '.esc_attr($values['type2_tab_color']).'; background-color: '.esc_attr($values['type2_tab_bg_color']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab-type2 ul.resp-tabs-list li:hover { color: '.esc_attr($values['type2_tab_color_hover']).'; background-color: '.esc_attr($values['type2_tab_bg_color_hover']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab-type2 ul.resp-tabs-list li.resp-tab-active { color: '.esc_attr($values['type2_tab_color_active']).'; background-color: '.esc_attr($values['type2_tab_bg_color_active']).'; }';

			// tab type3
			} elseif ($values['tab_type'] == 'type3') {
				$css[] = $post_widget['css_class'].' .pb_tab-type3 ul.resp-tabs-list li { color: '.esc_attr($values['type3_tab_color']).'; background-color: '.esc_attr($values['type3_tab_bg_color']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab-type3 ul.resp-tabs-list li:hover { color: '.esc_attr($values['type3_tab_color_hover']).'; background-color: '.esc_attr($values['type3_tab_bg_color_hover']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab-type3 ul.resp-tabs-list li.resp-tab-active { color: '.esc_attr($values['type3_tab_color_active']).'; background-color: '.esc_attr($values['type3_tab_bg_color_active']).'; }';
			}

			$i = 0;
			foreach($repeater_indexes as $repeater_index) {
				$i++;
				$repeater_values = $values['repeater'][$repeater_index];
				$repeater_values = array_merge($default_row_values, $repeater_values);

				// content type1
				if ($repeater_values['content_type'] == 'type1') {
					$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' { padding: '.esc_attr($repeater_values['type1_padding']).'px; }';
					$css_mobile[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' { padding: '.esc_attr($repeater_values['type1_padding_mobile']).'px; }';

				// content type2
				} elseif ($repeater_values['content_type'] == 'type2') {
					// リピーター内リピーター行の並び
					if (!empty($repeater_values['row2_index']) && is_array($repeater_values['row2_index'])) {

						$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider .slick-dots li button { color: '.esc_attr($repeater_values['nav_color']).'; background-color: '.esc_attr($repeater_values['nav_bg_color']).'; }';
						$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider .slick-dots li.slick-active button, '.$post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider .slick-dots li button:hover { color: '.esc_attr($repeater_values['nav_color_active']).'; background-color: '.esc_attr($repeater_values['nav_bg_color_active']).'; }';

						$j = 0;
						// リピーター内リピーター行ループ
						foreach($repeater_values['row2_index'] as $key => $repeater_level2_index) {
							if (empty($repeater_values['row2'][$repeater_level2_index])) {
								continue;
							}

							$repeater_level2_values = $repeater_values['row2'][$repeater_level2_index];

							$j++;
							$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider_item-'.$j.' .pb_tab_text { background-color: '.esc_attr($repeater_level2_values['content_bg_color']).'; }';
							$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider_item-'.$j.' .pb_tab_headline { color: '.esc_attr($repeater_level2_values['headline_font_color']).'; font-size: '.esc_attr($repeater_level2_values['headline_font_size']).'px; text-align: '.esc_attr($repeater_level2_values['headline_text_align']).'; }';
							$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider_item-'.$j.' .pb_tab_description { color: '.esc_attr($repeater_level2_values['content_font_color']).'; font-size: '.esc_attr($repeater_level2_values['content_font_size']).'px; text-align: '.esc_attr($repeater_level2_values['content_text_align']).'; }';

							$css_mobile[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider_item-'.$j.' .pb_tab_headline { font-size: '.esc_attr($repeater_level2_values['headline_font_size_mobile']).'px; }';
							$css_mobile[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider_item-'.$j.' .pb_tab_description { font-size: '.esc_attr($repeater_level2_values['content_font_size_mobile']).'px; }';
						}
					}

				// content type3
				} elseif ($repeater_values['content_type'] == 'type3') {
					$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider .slick-arrow { color: '.esc_attr($repeater_values['nav_color']).'; background-color: '.esc_attr($repeater_values['nav_bg_color']).'; }';
					$css[] = $post_widget['css_class'].' .pb_tab_content-'.$i.' .pb_tab_slider .slick-arrow:hover { color: '.esc_attr($repeater_values['nav_color_active']).'; background-color: '.esc_attr($repeater_values['nav_bg_color_active']).'; }';
				}
			}
		}

		if ($css && is_array($css)) {
			foreach($css as $value) {
				$value = trim($value);
				if ($value) {
					echo $value."\n";
				}
			}
		} elseif ($css && is_string($css)) {
			echo $css;
		}

		if ($css_mobile && is_array($css_mobile)) {
			echo "@media only screen and (max-width: 767px) {\n";
			foreach($css_mobile as $value) {
				$value = trim($value);
				if ($value) {
					echo '  '.$value."\n";
				}
			}
			echo "}\n";
		} elseif ($css && is_string($css_mobile)) {
			echo "@media only screen and (max-width: 767px) {\n";
			echo $css_mobile;
			echo "}\n";
		}
	}
}

/**
 * slickを読み込むコンテンツがあるか
 */
function page_builder_widget_tab_has_slick($content_type = null) {
	// 現記事で使用しているtabコンテンツデータを取得
	$post_widgets = get_page_builder_post_widgets(get_the_ID(), 'pb-widget-tab');
	if ($post_widgets) {
		foreach($post_widgets as $post_widget) {
			$widget_index = $post_widget['widget_index'];
			$values = $post_widget['widget_value'];
			$repeater_values = $post_widget['widget_value'];

			// リピーター行の並び
			if (!empty($values['repeater_index']) && is_array($values['repeater_index'])) {
				$repeater_indexes = $values['repeater_index'];

				// リピーター行ループし、行データが無ければ削除
				foreach($repeater_indexes as $key => $repeater_index) {
					if (empty($values['repeater'][$repeater_index])) {
						unset($repeater_indexes[$key]);
					}
				}
			} elseif (!empty($values['repeater']) && is_array($values['repeater'])) {
				$repeater_indexes = array_keys($values['repeater']);
			}

			// リピーター行がなければ終了
			if (empty($repeater_indexes)) continue;

			foreach($repeater_indexes as $repeater_index) {
				$repeater_values = $values['repeater'][$repeater_index];

				if (!empty($repeater_values['content_type'])) {
					if ($content_type && $repeater_values['content_type'] == $content_type) {
						return true;
					} elseif (!$content_type && in_array($repeater_values['content_type'], array('type2', 'type3'))) {
						return true;
					}
				}
			}
		}
	}

	return false;
}
