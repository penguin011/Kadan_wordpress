<?php

/**
 * ページビルダーウィジェット登録
 */
add_page_builder_widget(array(
	'id' => 'pb-widget-tab-voice',
	'form' => 'form_page_builder_widget_tab_voice',
	'form_rightbar' => 'form_rightbar_page_builder_widget_tab_voice',
	'save' => 'save_page_builder_repeater',
	'display' => 'display_page_builder_widget_tab_voice',
	'title' => __('Voice', 'tcd-w'),
	'description' => __('You can display voice content using tab.', 'tcd-w'),
	'additional_class' => 'pb-widget-tab pb-repeater-widget',
	'priority' => 54
));

/**
 * 管理画面用js
 */
function page_builder_widget_tab_voice_admin_scripts() {
	wp_enqueue_script('page_builder-tab-admin', get_template_directory_uri().'/pagebuilder/assets/admin/js/tab.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}
add_action('page_builder_admin_scripts', 'page_builder_widget_tab_voice_admin_scripts', 12);

/**
 * フォーム デフォルト値
 */
function get_page_builder_widget_tab_voice_default_values() {
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
		'first_tab_open_mobile' => 0
	);

	return apply_filters('get_page_builder_widget_tab_voice_default_values', $default_values);
}

/**
 * リピーター 行デフォルト値
 */
function get_page_builder_widget_tab_voice_default_row_values() {
	$default_row_values = array(
		'repeater_label' => '',
		'tab_name' => '',

		// リピーター用
		'voices' => array(),
		'voices_index' => array(),
	);

	return apply_filters('get_page_builder_widget_tab_voice_default_row_values', $default_row_values);
}

/**
 * リピーター内 リピーター用 デフォルト値
 */
function get_page_builder_widget_tab_voice_default_type_row_values() {
	return apply_filters('get_page_builder_widget_tab_voice_default_type_row_values', array(
		'repeater_level2_label' => '',
		'content' => '',
		'customer' => '',
		'customer_font_color' => '#660000',
		'background_color' => '#f4f1ed'
	));
}

/**
 * フォーム
 */
function form_page_builder_widget_tab_voice($values = array()) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_tab_voice_default_values', get_page_builder_widget_tab_voice_default_values(), 'form');
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

<div class="form-field form-field-radio form-field-tab_type-type2 hidden">
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

<div class="form-field form-field-radio form-field-tab_type-type2 hidden">
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

<div class="form-field form-field-radio form-field-tab_type-type2 hidden">
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

<div class="form-field form-field-radio form-field-tab_type-type3 hidden">
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

<div class="form-field form-field-radio form-field-tab_type-type3 hidden">
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

<div class="form-field form-field-radio form-field-tab_type-type3 hidden">
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
				form_page_builder_widget_tab_voice_repeater_row(
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
	form_page_builder_widget_tab_voice_repeater_row(
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
function form_page_builder_widget_tab_voice_repeater_row($values = array(), $row_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => ''
		),
		(array) $values
	);

	// 行デフォルト値
	$default_row_values = apply_filters('page_builder_widget_tab_voice_default_row_values', get_page_builder_widget_tab_voice_default_row_values());

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

			<div class="form-field">
				<?php
					// リピーター内リピーター
					form_page_builder_widget_tab_voice_repeater_level2_rows($values, $row_values);
				?>
			</div>
		</div>
	</div>
</div>

<?php
}

/**
 * リピーター内 リピーター出力
 */
function form_page_builder_widget_tab_voice_repeater_level2_rows($values = array(), $repeater_values = array()) {
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
	if (!empty($repeater_values['voices_index']) && is_array($repeater_values['voices_index'])) {
		$repeater_level2_indexes = $repeater_values['voices_index'];

		// リピーター行データが無ければ削除
		foreach($repeater_level2_indexes as $key => $repeater_level2_index) {
			if (empty($repeater_values['voices'][$repeater_level2_index])) {
				unset($repeater_level2_indexes[$key]);
			}
		}
	} elseif (!empty($repeater_values['voices']) && is_array($repeater_values['voices'])) {
		$repeater_level2_indexes = array_keys($repeater_values['voices']);
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
			if (!empty($repeater_values['voices'][$repeater_level2_index])) {
				// リピーター行出力
				form_page_builder_widget_tab_voice_repeater_level2_row(
					array(
						'widget_index' => $values['widget_index'],
						'repeater_index' => $values['repeater_index'],
						'repeater_level2_index' => $repeater_level2_index
					),
					$repeater_values['voices'][$repeater_level2_index]
				);
			}
		}
	}

	echo '	</div>'."\n"; // .pb_repeater_sortable

	// 追加ボタン時に差し込むHTML
	ob_start();
	form_page_builder_widget_tab_voice_repeater_level2_row(
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
 * リピーター内 リピーター行出力
 */
function form_page_builder_widget_tab_voice_repeater_level2_row($values = array(), $repeater_level2_values = array()) {
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
	$default_row_values = apply_filters('page_builder_widget_tab_voice_default_type_row_values', get_page_builder_widget_tab_voice_default_type_row_values());

	// 行デフォルト値に行の値をマージ
	$repeater_level2_values = array_merge(
		$default_row_values,
		(array) $repeater_level2_values
	);

	// リピーター表示名
	if (!$repeater_level2_values['repeater_level2_label'] && $repeater_level2_values['content']) {
		$repeater_level2_values['repeater_level2_label'] = mb_strimwidth($repeater_level2_values['content'], 0, 100, '…');
	} elseif (!$repeater_level2_values['repeater_level2_label']) {
		$repeater_level2_values['repeater_level2_label'] = __('New item', 'tcd-w');
	}

	// name属性ベース
	$repeater_level2_name_base = 'pagebuilder[widget]['.$values['widget_index'].'][repeater]['.$values['repeater_index'].'][voices]['.$values['repeater_level2_index'].']';
?>

<div id="pb_tab-<?php echo esc_attr($values['widget_index'].'-'.$values['repeater_index'].'-'.$values['repeater_level2_index']); ?>" class="pb_repeater pb_repeater-<?php echo esc_attr($values['repeater_level2_index']); ?>">
	<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][voices_index][]" value="<?php echo esc_attr($values['repeater_level2_index']); ?>" />
	<ul class="pb_repeater_button pb_repeater_cf">
		<li><span class="pb_repeater_level2_move"><?php _e('Move', 'tcd-w'); ?></span></li>
		<li><span class="pb_repeater_delete" data-confirm="<?php _e('Are you sure you want to delete this item?', 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></span></li>
	</ul>
	<div class="pb_repeater_content">
		<h3 class="pb_repeater_headline"><span class="index_label" data-empty="<?php _e('New item', 'tcd-w'); ?>"><?php echo esc_html($repeater_level2_values['repeater_level2_label']); ?></span><a href="#"><?php _e('Open', 'tcd-w'); ?></a></h3>
		<div class="pb_repeater_field">
			<div class="form-field">
				<h4><?php _e('Voice', 'tcd-w'); ?></h4>
				<textarea name="<?php echo esc_attr($repeater_level2_name_base); ?>[content]" rows="4" class="index_label"><?php echo esc_textarea($repeater_level2_values['content']); ?></textarea>
				</table>
			</div>

			<div class="form-field">
				<h4><?php _e('Customer', 'tcd-w'); ?></h4>
				<textarea name="<?php echo esc_attr($repeater_level2_name_base); ?>[customer]" rows="2"><?php echo esc_textarea($repeater_level2_values['customer']); ?></textarea>
				<table style="margin-top:5px;">
					<tr>
						<td><?php _e('Font color', 'tcd-w'); ?></td>
						<td><input type="text" name="<?php echo esc_attr($repeater_level2_name_base); ?>[customer_font_color]" value="<?php echo esc_attr($repeater_level2_values['customer_font_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['customer_font_color']); ?>" /></td>
					</tr>
				</table>
			</div>

			<div class="form-field">
				<h4><?php _e('Background Color', 'tcd-w'); ?></h4>
				<input type="text" name="<?php echo esc_attr($repeater_level2_name_base); ?>[background_color]" value="<?php echo esc_attr($repeater_level2_values['background_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_row_values['background_color']); ?>" />
			</div>
		</div>
	</div>
</div>

<?php
}

/**
 * フォーム 右サイドバー
 */
function form_rightbar_page_builder_widget_tab_voice($values = array(), $widget_id = null, $args = array()) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_tab_voice_default_values', get_page_builder_widget_tab_voice_default_values(), 'form_rightbar');

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
function display_page_builder_widget_tab_voice($values = array(), $widget_index = null) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_tab_voice_default_values', get_page_builder_widget_tab_voice_default_values(), 'form');

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
	$default_row_values = apply_filters('page_builder_widget_tab_voice_default_row_values', get_page_builder_widget_tab_voice_default_row_values());

	// ths_contentフィルターフラグ
	$is_remove_filter_the_content = false;
?>
<div class="pb_tab_voice pb_tab_voice-<?php
	echo esc_attr($values['tab_type']);
	if (!empty($values['first_tab_open_mobile'])) {
		echo ' pb_tab_voice-first_tab_open_mobile';
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
?>
    <div class="pb_tab_voice_content pb_tab_voice_content-<?php echo $i; ?>">
<?php
		// リピーター内リピーター行の並び
		if (!empty($repeater_values['voices_index']) && is_array($repeater_values['voices_index'])) {
			$j = 0;
			// リピーター内リピーター行ループ
			foreach($repeater_values['voices_index'] as $key => $repeater_level2_index) {
				if (empty($repeater_values['voices'][$repeater_level2_index])) {
					continue;
				}

				$repeater_level2_values = $repeater_values['voices'][$repeater_level2_index];

				if (!empty($repeater_level2_values['content'])) {
					echo '      <div class="pb_tab_voice_box" style="background-color: '.esc_attr($repeater_level2_values['background_color']).'">';

					echo str_replace(array("\r\n", "\r", "\n"), '<br>', esc_html(trim($repeater_level2_values['content'])));


					if (!empty($repeater_level2_values['customer'])) {
						echo '<div class="pb_tab_voice_customer" style="color: '.esc_attr($repeater_level2_values['customer_font_color']).'">'.str_replace(array("\r\n", "\r", "\n"), '<br>', esc_html(trim($repeater_level2_values['customer']))).'</div>';
					}

					echo '</div>'."\n";
				}
			}
		}
?>
    </div>
<?php
	}
?>
  </div>
</div>
<?php
}

/**
 * フロント用js・css
 */
function page_builder_widget_tab_voice_sctipts() {
	wp_enqueue_script('page_builder-tab', get_template_directory_uri().'/pagebuilder/assets/js/tab.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}

function page_builder_widget_tab_voice_styles() {
	wp_enqueue_style('page_builder-tab-voice', get_template_directory_uri().'/pagebuilder/assets/css/tab_voice.css', false, PAGE_BUILDER_VERSION);
}

function page_builder_widget_tab_voice_sctipts_styles() {
	if (is_singular() && is_page_builder() && page_builder_has_widget('pb-widget-tab-voice')) {
		add_action('wp_enqueue_scripts', 'page_builder_widget_tab_voice_sctipts', 11);
		add_action('wp_enqueue_scripts', 'page_builder_widget_tab_voice_styles', 11);
		add_action('wp_head', 'pb_tab_voice_script_header');
		add_action('page_builder_css', 'page_builder_widget_tab_voice_css');
	}
}
add_action('wp', 'page_builder_widget_tab_voice_sctipts_styles');

function pb_tab_voice_script_header() {
?>
<script type="text/javascript">
jQuery(document).ready(function($){
  if (typeof $.fn.easyResponsiveTabs == 'undefined') return;
  $('.pb_tab_voice').easyResponsiveTabs();

<?php // スマホの場合はタブを閉じた状態にする first_tab_open_mobileなしのみ ?>
  if ($(window).width() < 768) {
    $('.pb_tab_voice:not(.pb_tab_voice-first_tab_open_mobile)').each(function(){
      $('.resp-tab-active', this).removeClass('resp-tab-active');
      $('.resp-tab-content-active', this).removeClass('resp-tab-content-active').hide();
    });
  }
});
</script>
<?php
}

function page_builder_widget_tab_voice_css() {
	// 現記事で使用しているtab-voiceコンテンツデータを取得
	$post_widgets = get_page_builder_post_widgets(get_the_ID(), 'pb-widget-tab-voice');
	if ($post_widgets) {
		$css = array();
		$css_mobile = array();

		// 行デフォルト値
		$default_row_values = apply_filters('page_builder_widget_tab_voice_default_row_values', get_page_builder_widget_tab_voice_default_row_values());

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
				$css[] = $post_widget['css_class'].' .pb_tab_voice-type2 ul.resp-tabs-list li { color: '.esc_attr($values['type2_tab_color']).'; background-color: '.esc_attr($values['type2_tab_bg_color']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab_voice-type2 ul.resp-tabs-list li:hover { color: '.esc_attr($values['type2_tab_color_hover']).'; background-color: '.esc_attr($values['type2_tab_bg_color_hover']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab_voice-type2 ul.resp-tabs-list li.resp-tab-active { color: '.esc_attr($values['type2_tab_color_active']).'; background-color: '.esc_attr($values['type2_tab_bg_color_active']).'; }';

			// tab type3
			} elseif ($values['tab_type'] == 'type3') {
				$css[] = $post_widget['css_class'].' .pb_tab_voice-type3 ul.resp-tabs-list li { color: '.esc_attr($values['type3_tab_color']).'; background-color: '.esc_attr($values['type3_tab_bg_color']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab_voice-type3 ul.resp-tabs-list li:hover { color: '.esc_attr($values['type3_tab_color_hover']).'; background-color: '.esc_attr($values['type3_tab_bg_color_hover']).'; }';
				$css[] = $post_widget['css_class'].' .pb_tab_voice-type3 ul.resp-tabs-list li.resp-tab-active { color: '.esc_attr($values['type3_tab_color_active']).'; background-color: '.esc_attr($values['type3_tab_bg_color_active']).'; }';
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
