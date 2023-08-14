<?php

/**
 * リピーター管理画面用js・css
 */
function page_builder_repeater_admin_scripts() {
	wp_enqueue_script('page_builder-repeater', get_template_directory_uri().'/pagebuilder/assets/admin/js/repeater.js', array('jquery', 'jquery-ui-sortable'), PAGE_BUILDER_VERSION, true);
}
add_action('page_builder_admin_scripts', 'page_builder_repeater_admin_scripts', 11);

function page_builder_repeater_admin_styles() {
	wp_enqueue_style('page_builder-repeater', get_template_directory_uri().'/pagebuilder/assets/admin/css/repeater.css', false, PAGE_BUILDER_VERSION);
}
add_action('page_builder_admin_styles', 'page_builder_repeater_admin_styles', 11);

/**
 * リピーター保存値修正
 */
function save_page_builder_repeater($values = array(), $widget_index = null, $widget = array()) {
	$return_values = array();

	// リピーター行あり
	if (!empty($values['repeater']) && is_array($values['repeater'])) {
		// repeater_indexあり
		if (!empty($values['repeater_index'])) {
			foreach($values['repeater_index'] as $repeater_index) {
				// クローン用は除外
				if ($repeater_index == 'pb_repeater_add_index') continue;

				if (isset($values['repeater'][$repeater_index])) {
					$return_values['repeater_index'][] = $repeater_index;
					$return_values['repeater'][$repeater_index] = $values['repeater'][$repeater_index];
				}
			}
		} else {
			// repeater_indexが無い場合、ポストされた順に配列キーを振り直す
			$i = 0;
			foreach($values['repeater'] as $repeater_key => $repeater_value) {
				if (is_numeric($repeater_key)) {
					$return_values['repeater'][++$i] = $repeater_value;
				}
			}
		}

	}

	// リピーター行以外
	if (!empty($values) && is_array($values)) {
		foreach($values as $key => $value) {
			// リピーター行以外
			if (!in_array($key, array('repeater', 'repeater_index'))) {
				$return_values[$key] = $value;
			}
		}
	}

	return $return_values;
}
