<?php

/**
 * ページビルダーウィジェット登録
 */
add_page_builder_widget(array(
	'id' => 'pb-widget-editor',
	'form' => 'form_page_builder_widget_editor',
	'form_rightbar' => 'form_rightbar_page_builder_widget', // 標準右サイドバー
	'display' => 'display_page_builder_widget_editor',
	'title' => __('Sentence', 'tcd-w'),
	'priority' => 16
));

/**
 * 管理画面用js
 */
function page_builder_widget_editor_admin_scripts() {
	wp_enqueue_script('page_builder-editor', get_template_directory_uri().'/pagebuilder/assets/admin/js/editor.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}
add_action('page_builder_admin_scripts', 'page_builder_widget_editor_admin_scripts', 12);

/**
 * フォーム
 */
function form_page_builder_widget_editor($values = array()) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_editor_default_values', array(
		'widget_index' => '',
		'content' => ''
	), 'form');

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);
?>

<div class="form-field form-field-editor">
	<?php
		wp_editor(
			$values['content'],
			str_replace('-', '_', 'pb_editor_'.$values['widget_index']),
			array(
				'textarea_name' => 'pagebuilder[widget]['.$values['widget_index'].'][content]',
				'textarea_rows' => 10
			)
		);
	?>
</div>

<?php
}

/**
 * クローン用のリッチエディター化処理をしないようにする
 * クローン後のリッチエディター化はjsで行う
 */
function page_builder_widget_editor_tiny_mce_before_init($mceInit, $editor_id) {
	if ($editor_id == 'pb_editor_widgetindex_pb_widget_editor') {
		$mceInit['wp_skip_init'] = true;
	}
	return $mceInit;
}
add_filter('tiny_mce_before_init', 'page_builder_widget_editor_tiny_mce_before_init', 10, 2);

/**
 * フロント出力
 */
function display_page_builder_widget_editor($values = array()) {
	if (empty($values['content'])) return;

	// ths_contentフィルター
	remove_filter('the_content', 'page_builder_filter_the_content', 8);

	echo apply_filters('the_content', $values['content']);

	// ths_contentフィルターを戻す
	add_filter('the_content', 'page_builder_filter_the_content', 8);
}
