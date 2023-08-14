<?php

/**
 * ページビルダーバージョン
 */
define('PAGE_BUILDER_VERSION', '1.3.4');

/**
 * グローバル変数
 */
global $pagebuilder_vars;
$pagebuilder_vars = array();

/**
 * ページビルダーを適用する投稿タイプ
 */
/*
フィルターサンプル
function custom_page_builder_post_types($post_types) {
	// 固定の場合
	return array('post', 'page', 'work', 'news');

	// 任意の投稿タイプを除外する場合
	if ($key = array_search('news', $post_types)) {
		unset($post_types[$key]);
	}
	return $post_types;
}
add_filter('get_page_builder_post_types', 'custom_page_builder_post_types');
*/
function get_page_builder_post_types() {
	static $page_builder_post_types;

	if (!is_array($page_builder_post_types)) {
		$page_builder_post_types = array();

		foreach(get_post_types(array('public' => true), 'names') as $post_type) {
			if (post_type_supports($post_type, 'editor')) {
				$page_builder_post_types[] = $post_type;
			}
		}
	}

	return apply_filters('get_page_builder_post_types', $page_builder_post_types);
}

/**
 * 管理画面でページビルダー出力対象か
 */
function is_admin_page_builder($recalc = false) {
	static $is_admin_page_builder;
	if (!is_null($is_admin_page_builder) && !$recalc) {
		return $is_admin_page_builder;
	}

	$is_admin_page_builder = false;

	global $pagenow, $typenow, $page_builder_widgets;
	if (in_array($pagenow, array('post.php', 'post-new.php')) && in_array($typenow, get_page_builder_post_types()) && $page_builder_widgets) {
		$is_admin_page_builder = true;
	}

	return $is_admin_page_builder;
}

/**
 * 管理画面用js・css
 */
function page_builder_admin_scripts() {
	if (is_admin_page_builder()) {
		wp_enqueue_script('page_builder-admin', get_template_directory_uri().'/pagebuilder/assets/admin/js/admin.js', array('jquery', 'jquery-ui-resizable', 'jquery-ui-sortable', 'jquery-ui-draggable', 'wp-color-picker'), PAGE_BUILDER_VERSION, true);
		wp_localize_script( 'page_builder-admin', 'pagebuilder_i18n', array(
			'page_builder' => __('Page Builder', 'tcd-w'),
			'visual_editor' => __( 'Visual Editor' ),
			'code_editor' => __( 'Code Editor' )
		) );

		do_action('page_builder_admin_scripts');
	}
}
add_action('admin_print_scripts', 'page_builder_admin_scripts', 11);
function page_builder_admin_styles() {
	if (is_admin_page_builder()) {
		wp_enqueue_style('page_builder-admin', get_template_directory_uri().'/pagebuilder/assets/admin/css/admin.css', array('wp-color-picker'), PAGE_BUILDER_VERSION);

		do_action('page_builder_admin_styles');
	}
}
add_action('admin_print_styles', 'page_builder_admin_styles', 11);

/**
 * メタボックス追加
 */
function add_page_builder_metaboxes() {
	foreach(get_page_builder_post_types() as $post_type){
		add_meta_box(
			'page_builder-metabox',
			__('Page Builder', 'tcd-w'),
			'show_page_builder_metabox',
			$post_type,
			function_exists('gutenberg_init') || version_compare($GLOBALS['wp_version'], '5.0', '>=' ) ? 'normal' : 'advanced',
			'high'
		);
	}
}
add_action('add_meta_boxes', 'add_page_builder_metaboxes');

/**
 * メタボックス表示
 */
function show_page_builder_metabox() {
	global $post;

	// ウィジェット取得
	$widgets = get_page_builder_widgets();

	// ページビルダーフラグ
	$use_page_builder = get_post_meta($post->ID, 'use_page_builder', true);

	// ページビルダーデータ
	$data = get_post_meta($post->ID, 'page_builder', true);
	if (empty($data)) $data = array();

	// 行インデックス最大値
	$row_index_max = 0;
	if (!empty($data['row']['indexes'])) {
		if (is_string($data['row']['indexes'])) {
			$data['row']['indexes'] = explode(',', $data['row']['indexes']);
		}

		$indexes = array_merge($data['row']['indexes'], array_keys($data['row']));
		$indexes = array_map('intval', $indexes);
		$row_index_max = max($indexes);
	}

	// ウィジェットインデックス最大値
	$widget_index_max = 0;
	if (!empty($data['widget']) && is_array($data['widget'])) {
		$indexes = array_keys($data['widget']);
		$indexes = array_map('intval', $indexes);
		$widget_index_max = max($indexes);
	}

	// ウィジェット取得
	$widgets = get_page_builder_widgets();

	// nonce
	echo '<input type="hidden" name="page_builder_metabox_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	echo "\n";

	// page builder flag
	echo '<input type="hidden" name="use_page_builder" value="'.(boolean) $use_page_builder.'" />';
	echo "\n";

	// プレビュー対策 変更フラグ @TODO 変更あった場合のみ1に
	echo '<input type="hidden" name="_page_builder_changed" value="1" />';
	echo "\n";

	// toolbar
	echo '<div class="pb-toolbar">';
	echo '<span class="pb-tool-button pb-switch-to-standard">'. __('Revert to Editor', 'tcd-w'). '</span>';
	echo '<span class="pb-tool-button pb-add-widget" data-require-row-message="'. __('Please click Add row button to start page builder.', 'tcd-w').'"><span class="dashicons dashicons-plus"></span> '. __('Add content', 'tcd-w') . '</span>';
	echo '<span class="pb-tool-button pb-add-row"><span class="dashicons dashicons-grid-view"></span> '. __('Add row', 'tcd-w'). '</span>';
	echo '</div>';
	echo "\n";

	// rows
	echo '<div class="pb-rows-container" data-rows="'.$row_index_max.'" data-widgets="'.$widget_index_max.'" data-require-row-message="'. __('Please click Add row button to start page builder.', 'tcd-w').'">';

	// 行ループ
	if (!empty($data['row']['indexes'])) {
		foreach($data['row']['indexes'] as $row_index) {
			if (!empty($data['row'][$row_index])) {
				render_page_builder_row(
					array(
						'id' => 'row-'.$row_index,
						'index' => $row_index
					),
					$data['row'][$row_index],
					$data
				);
			}
		}
	}

	echo '</div>';
	echo "\n";

	// 行の追加モーダル
	render_page_builder_edit_row_modal(array(
		'index' => 'rowindex',
		'id' => 'pb-add-row-modal',
		'title' => __('Add row', 'tcd-w'),
		'primary_button_label' => __('Add', 'tcd-w'),
		'attribute' => 'data-edit-title="'.esc_attr__('Row setting', 'tcd-w').'" data-edit-button="'.esc_attr__('Done', 'tcd-w').'"'
	));

	// ウィジェットの追加モーダル
	render_page_builder_add_widget_modal();

	echo '<div class="pb-clone hidden" style="display:none;">';

	// 行の追加時のクローン用
	render_page_builder_row(array('id' => 'clonerow', 'index' => 'rowindex'));

	// カラムクローン用
	render_page_builder_cell(array('id' => 'clonecell'));

	// ウィジェットクローン用
	if ($widgets) {
		foreach($widgets as $widget) {
			render_page_builder_widget(
				array(
					'id' => $widget['id'],
					'title' => $widget['title'],
					'widget_index' => 'widgetindex-'.$widget['id'],
					'attribute' => '',
				),
				$widget,
				array()
			);
		}
	}

	echo '</div>';
	echo "\n";
}

/**
 * 行出力
 */
function render_page_builder_row($args = array(), $row = array(), $data = array()) {
	$args = array_merge(
		array(
			'id' => '',
			'index' => 'rowindex'
		),
		(array) $args
	);
?>
<div id="<?php echo esc_attr($args['id']); ?>" class="pb-row-container">
	<input type="hidden" name="pagebuilder[row][indexes][]" value="<?php echo esc_attr($args['index']); ?>" class="pb-row-index" />
	<div class="pb-row-toolbar">
		<span class="pb-tool-button pb-row-edit"><?php _e('Edit row', 'tcd-w'); ?></span>
		<span class="pb-tool-button pb-row-delete" data-confirm="<?php _e("Do you want to delete this row?\nIf you delete this row, registered contents will also be deleted.", 'tcd-w'); ?>"><?php _e('Delete row', 'tcd-w'); ?></span>
		<span class="pb-tool-button pb-row-move"><span class="dashicons dashicons-leftright"></span></span>
	</div>
	<div class="pb-cells">
<?php
	// セルループ
	if (!empty($row['cells_width'])) {
		if (is_string($row['cells_width'])) {
			$row['cells_width'] = explode(',', $row['cells_width']);
		}

		$cell_index = 0;
		foreach($row['cells_width'] as $cells_width) {
			$cell_index++;

			$widget_indexes = array();
			if (!empty($data['cell'][$args['index'].'-'.$cell_index])) {
				$widget_indexes = $data['cell'][$args['index'].'-'.$cell_index];
			}

			render_page_builder_cell(array(
				'id' => 'cell-'.$args['index'].'-'.$cell_index,
				'index' => $cell_index,
				'width' => $cells_width * 100 . '%',
				'widget_indexes' => $widget_indexes,
			), $data);
		}
	}
?>
	</div>
<?php
	// 行編集モーダル
	if ($row) {
		render_page_builder_edit_row_modal(array_merge(
			array(
				'id' => 'row-modal-'.$args['index'],
				'index' => $args['index']
			),
			$row
		));
	}
?>
</div>
<?php
}

/**
 * セル出力
 */
function render_page_builder_cell($args = array(), $data = array()) {
	$args = array_merge(
		array(
			'id' => '',
			'index' => '',
			'width' => '100%',
			'widget_indexes' => array(),
		),
		(array) $args
	);

	if (!is_array($args['widget_indexes'])) {
		$args['widget_indexes'] = explode(',', $args['widget_indexes']);
	}
	$args['widget_indexes'] = array_filter($args['widget_indexes'], 'strlen');

	echo '<div id="'.esc_attr($args['id']).'" class="cell" style="width:'.esc_attr($args['width']).'">';
	echo '<div class="resize-handle"></div>';
	echo '<div class="cell-wrapper widgets-container">';

	// ウィジェットループ
	if ($args['widget_indexes']) {
		foreach($args['widget_indexes'] as $widget_index) {
			if (!empty($data['widget'][$widget_index]['widget_id'])) {
				$widget_id = $data['widget'][$widget_index]['widget_id'];
				$widget = get_page_builder_widget($widget_id);
				$widget_value = $data['widget'][$widget_index];

				if (!empty($widget['title'])) {
					render_page_builder_widget(
						array(
							'id' => 'widget-'.$widget_index,
							'widget_index' => $widget_index,
							'title' => $widget['title'],
							'attribute' => ''
						),
						$widget,
						$widget_value
					);
				}
			}
		}
	}

	echo '</div>';

	// セル内のウィジェット並び
	if ($args['widget_indexes']) {
		echo '<input type="hidden" name="pagebuilder[cell]['.esc_attr($args['index']).']" value="'.esc_attr(implode(',', $args['widget_indexes'])).'" class="widget_indexes" />';
	}

	echo '</div>';
	echo "\n";
}

/**
 * ウィジェット出力
 */
function render_page_builder_widget($args = array(), $widget = array(), $values = array()) {
	$args = array_merge(
		array(
			'id' => '',
			'widget_index' => '',
			'title' => '',
			'attribute' => ''
		),
		(array) $args
	);

	// wp_editor()で-が使えないため_に置換
	$args['widget_index'] = str_replace('-', '_', $args['widget_index']);

	$class = 'pb-widget';
	if (!empty($widget['id'])) {
		$class .= ' '.$widget['id'];
	}
	if (!empty($widget['additional_class'])) {
		if (is_array($widget['additional_class'])) {
			$widget['additional_class'] = implode(' ', $widget['additional_class']);
		}
		$class .= ' '.$widget['additional_class'];
	}
	echo '<div class="'.esc_attr($class).'"';
	if ($args['id']) {
		echo ' id="'.esc_attr($args['id']).'"';
	}
	echo ' data-widget-index="'.esc_attr($args['widget_index']).'"';
	if ($widget) {
		echo ' data-widget-id="'.esc_attr($widget['id']).'"';
	}
	if ($args['attribute']) {
		echo ' '.$args['attribute'];
	}
	echo '>';
	echo '<div class="pb-widget-wrapper">';
	echo '<h4 class="widget-title">'.esc_html($args['title']).'<span class="widget-overview"></span></h4>';
	echo '<div class="actions">';
	echo '<a class="pb-widget-clone">'. __('Clone', 'tcd-w').'</a>';
	echo '<a class="widget-delete" data-confirm="'. __('Do you want to delete this content?', 'tcd-w').'">'. __('Delete', 'tcd-w').'</a>';
	echo '</div>';
	echo '</div>';

	// ウィジェット編集モーダル
	if ($widget) {
		render_page_builder_edit_widget_modal(array(
				'id' => $widget['id'].'-'.$args['widget_index'],
				'widget_id' => $widget['id'],
				'widget_index' => $args['widget_index'],
				'title' => $widget['title'],
				'form' => $widget['form'],
				'form_rightbar' => $widget['form_rightbar']
			),
			$values
		);
	}

	echo '</div>';
	echo "\n";
}

/**
 * 行追加・編集モーダル用出力
 */
function render_page_builder_edit_row_modal($args = array()) {
	// デフォルト値
	$defaults = apply_filters('page_builder_row_default_values', array(
		'id' => 'pb-add-row-modal',
		'index' => 'rowindex',
		'title' => __('Row setting', 'tcd-w'),
		'primary_button_label' => __('Done', 'tcd-w'),
		'cells' => 1,
		'cells_width' => '1',
		'margin_bottom' => 30,
		'margin_bottom_mobile' => 30,
		'gutter' => 30,
		'gutter_mobile' => 30,
		'background_color' => '#ffffff',
		'row_width' => '',
		'mobile_cells' => 'clear',
		'nextpage' => 0,
		'show_border_top' => 0,
		'show_border_bottom' => 0,
		'show_border_left' => 0,
		'show_border_right' => 0,
		'show_border_top_mobile' => 0,
		'show_border_bottom_mobile' => 0,
		'show_border_left_mobile' => 0,
		'show_border_right_mobile' => 0,
		'border_color' => '#dddddd',
		'border_width' => 1,
		'border_width_mobile' => 1,
		'padding_top' => 0,
		'padding_bottom' => 0,
		'padding_left' => 0,
		'padding_right' => 0,
		'padding_top_mobile' => 0,
		'padding_bottom_mobile' => 0,
		'padding_left_mobile' => 0,
		'padding_right_mobile' => 0,
		'attribute' => ''
	));

	// デフォルト値に入力値をマージ
	$args = array_merge($defaults, (array) $args);

	if (is_array($args['cells_width'])) {
		$args['cells_width'] = implode(',', $args['cells_width']);
	}

	// 旧カラーピッカー対策
	if (preg_match('/^[0-9a-f]{6}$/i', $args['background_color'])) {
		$args['background_color'] = '#'.strtolower($args['background_color']);
	}
?>
<div id="<?php echo esc_attr($args['id']); ?>" class="pb-modal pb-has-right-sidebar pb-modal-row-edit" <?php if ($args['attribute']) echo $args['attribute']; ?>>
	<div class="pb-overlay"></div>
	<div class="pb-title-bar">
		<h3 class="pb-title"><?php echo esc_html($args['title']); ?></h3>
		<a class="pb-toggle-rightbar"><span class="pb-dialog-icon"></span></a>
		<a class="pb-close"><span class="pb-dialog-icon"></span></a>
	</div>

	<div class="pb-toolbar">
		<div class="pb-status"></div>
		<div class="pb-buttons">
			<div class="action-buttons">
				<a class="pb-delete" data-confirm="<?php _e("Do you want to delete this row?\nIf you delete this row, registered contents will also be deleted.", 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></a>
			</div>

			<input type="button" class="pb-apply button-primary" value="<?php echo esc_attr($args['primary_button_label']); ?>" />
		</div>
	</div>

	<div class="pb-content">
		<div class="row-set-form">
			<strong><?php _e('Number of column', 'tcd-w'); ?></strong>
			<select name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][cells]" class="cells">
				<?php
					$select_options = array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
						5 => 5,
						6 => 6
					);
					foreach($select_options as $key => $value) {
						$attr = '';
						if ($args['cells'] == $key) {
							$attr .= ' selected="selected"';
						}
						echo '<option value="'.esc_attr($key).'"'.$attr.'>'.esc_html($value).'</option>';
					}
				?>
			</select>
			<input type="hidden" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][cells_width]" class="cells_width" value="<?php echo esc_attr($args['cells_width']); ?>" />
		</div>

		<div class="row-preview">
		</div>
	</div>

	<div class="pb-sidebar pb-right-sidebar">
		<h3 data-pb-toggle-target=".pb-right-sidebar-layout" data-pb-toggle-status="open"><?php _e('Layout setting', 'tcd-w'); ?></h3>
		<div class="pb-toggle-content pb-right-sidebar-layout">
			<div class="form-field">
				<label><?php _e('Margin bottom of the row', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][margin_bottom]" value="<?php echo esc_attr($args['margin_bottom']); ?>" class="small-text" /> px
				<p class="pb-description"><?php _e('Space below the row.<br />Default is 30px.', 'tcd-w'); ?></p>
			</div>

			<div class="form-field">
				<label><?php _e('Margin bottom of the row for mobile', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][margin_bottom_mobile]" value="<?php echo esc_attr($args['margin_bottom_mobile']); ?>" class="small-text" /> px
				<p class="pb-description"><?php _e('Space below the row.<br />Default is 30px.', 'tcd-w'); ?></p>
			</div>

			<div class="form-field hide-if-one-column">
				<label><?php _e('Gutter', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][gutter]" value="<?php echo esc_attr($args['gutter']); ?>" class="small-text" min="0" /> px
				<p class="pb-description"><?php _e('Amount of space between columns.<br />Default is 30px.', 'tcd-w'); ?></p>
			</div>

			<div class="form-field hide-if-one-column">
				<label><?php _e('Gutter for mobile', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][gutter_mobile]" value="<?php echo esc_attr($args['gutter_mobile']); ?>" class="small-text" min="0" /> px
				<p class="pb-description"><?php _e('Amount of space between columns.<br />Default is 30px.', 'tcd-w'); ?></p>
			</div>

			<div class="form-field">
				<label><?php _e('Row width', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][row_width]" value="<?php echo esc_attr($args['row_width']); ?>" class="small-text" /> px
				<p class="pb-description"><?php _e('Please register the <strong>px</strong> of width if you want to change the width of this row.<br /><br />If you registered the width, the row will be center aligned.', 'tcd-w'); ?></p>
			</div>

			<div class="form-field hide-if-one-column">
				<label><?php _e('Layout of column at mobile device.', 'tcd-w'); ?></label>
				<select name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][mobile_cells]">
					<?php
						$select_options = array(
							'clear' => __('Display vertically', 'tcd-w'),
							'float' => __('Keep in horizontal', 'tcd-w')
						);
						foreach($select_options as $key => $value) {
							$attr = '';
							if ($args['mobile_cells'] == $key) {
								$attr .= ' selected="selected"';
							}
							echo '<option value="'.esc_attr($key).'"'.$attr.'>'.esc_html($value).'</option>';
						}
					?>
				</select>
			</div>

			<div class="form-field form-field-checkbox">
				<label><?php _e('Splitting contents', 'tcd-w'); ?></label>
				<input type="hidden" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][nextpage]" value="0" />
				<label class="checkbox"><input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][nextpage]" value="1" <?php if ($args['nextpage'] == 1) echo 'checked="checked"'; ?> /> <?php _e('Display this row at next page.', 'tcd-w'); ?></label>
			</div>
		</div>

		<h3 data-pb-toggle-target=".pb-right-sidebar-background" data-pb-toggle-status="close"><?php _e('Background setting', 'tcd-w'); ?></h3>
		<div class="pb-toggle-content pb-right-sidebar-background">
			<div class="form-field">
				<label><?php _e('Background color', 'tcd-w'); ?></label>
				<input type="text" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][background_color]" value="<?php echo esc_attr($args['background_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($defaults['background_color']); ?>" />
			</div>
		</div>

		<h3 data-pb-toggle-target=".pb-right-sidebar-border" data-pb-toggle-status="close"><?php _e('Border setting', 'tcd-w'); ?></h3>
		<div class="pb-toggle-content pb-right-sidebar-border">
			<div class="form-field">
				<ul>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_top]" value="1" <?php checked($args['show_border_top'] ,1); ?> />
							<?php _e('Display top border', 'tcd-w'); ?>
						</label>
					</li>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_bottom]" value="1" <?php checked($args['show_border_bottom'] ,1); ?> />
							<?php _e('Display bottom border', 'tcd-w'); ?>
						</label>
					</li>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_left]" value="1" <?php checked($args['show_border_left'] ,1); ?> />
							<?php _e('Display left border', 'tcd-w'); ?>
						</label>
					</li>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_right]" value="1" <?php checked($args['show_border_right'] ,1); ?> />
							<?php _e('Display right border', 'tcd-w'); ?>
						</label>
					</li>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_top_mobile]" value="1" <?php checked($args['show_border_top_mobile'] ,1); ?> />
							<?php _e('Display top border for mobile', 'tcd-w'); ?>
						</label>
					</li>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_bottom_mobile]" value="1" <?php checked($args['show_border_bottom_mobile'] ,1); ?> />
							<?php _e('Display bottom border for mobile', 'tcd-w'); ?>
						</label>
					</li>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_left_mobile]" value="1" <?php checked($args['show_border_left_mobile'] ,1); ?> />
							<?php _e('Display left border for mobile', 'tcd-w'); ?>
						</label>
					</li>
					<li>
						<label class="checkbox">
							<input type="checkbox" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][show_border_right_mobile]" value="1" <?php checked($args['show_border_right_mobile'] ,1); ?> />
							<?php _e('Display right border for mobile', 'tcd-w'); ?>
						</label>
					</li>
				</ul>
			</div>

			<div class="form-field">
				<label><?php _e('Border color', 'tcd-w'); ?></label>
				<input type="text" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][border_color]" value="<?php echo esc_attr($args['border_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($defaults['border_color']); ?>" />
			</div>

			<div class="form-field">
				<label><?php _e('Border width', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][border_width]" value="<?php echo esc_attr($args['border_width']); ?>" class="small-text" min="1" /> px
			</div>
			<div class="form-field">
				<label><?php _e('Border width for mobile', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][border_width_mobile]" value="<?php echo esc_attr($args['border_width_mobile']); ?>" class="small-text" min="1" /> px
			</div>
		</div>

		<h3 data-pb-toggle-target=".pb-right-sidebar-padding" data-pb-toggle-status="close"><?php _e('Padding setting', 'tcd-w'); ?></h3>
		<div class="pb-toggle-content pb-right-sidebar-padding">
			<div class="form-field">
				<label><?php _e('Top padding width', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_top]" value="<?php echo esc_attr($args['padding_top']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field">
				<label><?php _e('Bottom padding width', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_bottom]" value="<?php echo esc_attr($args['padding_bottom']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field">
				<label><?php _e('Left padding width', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_left]" value="<?php echo esc_attr($args['padding_left']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field">
				<label><?php _e('Right padding width', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_right]" value="<?php echo esc_attr($args['padding_right']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field">
				<label><?php _e('Top padding width for mobile', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_top_mobile]" value="<?php echo esc_attr($args['padding_top_mobile']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field">
				<label><?php _e('Bottom padding width for mobile', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_bottom_mobile]" value="<?php echo esc_attr($args['padding_bottom_mobile']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field">
				<label><?php _e('Left padding width for mobile', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_left_mobile]" value="<?php echo esc_attr($args['padding_left_mobile']); ?>" class="small-text" min="0" /> px
			</div>

			<div class="form-field">
				<label><?php _e('Right padding width for mobile', 'tcd-w'); ?></label>
				<input type="number" name="pagebuilder[row][<?php echo esc_attr($args['index']); ?>][padding_right_mobile]" value="<?php echo esc_attr($args['padding_right_mobile']); ?>" class="small-text" min="0" /> px
			</div>
		</div>
	</div>
</div>
<?php
}

/**
 * ウィジェット追加モーダル用出力
 */
function render_page_builder_add_widget_modal() {
	$widgets = get_page_builder_widgets();
?>
<div id="pb-add-widget-modal" class="pb-modal pb-modal-add-widget">
	<div class="pb-overlay"></div>
	<div class="pb-title-bar">
		<h3 class="pb-title"><?php _e('Select the content', 'tcd-w'); ?></h3>
		<a class="pb-close"><span class="pb-dialog-icon"></span></a>
	</div>

	<div class="pb-toolbar">
		<div class="pb-status"></div>
		<div class="pb-buttons">
		</div>
	</div>

	<div class="pb-content">
		<div class="pb-select-widget">
<?php
	if ($widgets) {
		foreach($widgets as $widget) {
			$class = $widget['id'];
			if (!empty($widget['additional_class'])) {
				if (is_array($widget['additional_class'])) {
					$widget['additional_class'] = implode(' ', $widget['additional_class']);
				}
				$class .= ' '.$widget['additional_class'];
			}

			echo '			';
			echo '<a href="#'.esc_attr($widget['id']).'" class="'.esc_attr($class).'">';
			echo '<span class="pb-icon dashicons dashicons-admin-generic"></span>';
			echo '<h3>'.esc_html($widget['title']).'</h3>';
			if (!empty($widget['description']))
			echo '<span class="pb-description">'.esc_html($widget['description']).'</span>';
			echo '</a>';
			echo "\n";
		}
	}
?>
		</div>
	</div>
</div>
<?php
}

/**
 * ウィジェット追加・編集モーダル用出力
 */
function render_page_builder_edit_widget_modal($args = array(), $values = array()) {
	$args = array_merge(
		array(
			'id' => '',
			'widget_id' => '',
			'widget_index' => 'widgetindex',
			'title' => '',
			'title_after' => __(' setting', 'tcd-w'),
			'form' => '',
			'form_rightbar' => '',
			'class' => 'pb-modal-edit-widget',
			'primary_button_label' => __('Done', 'tcd-w'),
			'attribute' => '',
			'values' => array()
		),
		(array) $args
	);

	if (is_array($args['class'])) {
		$args['class'] = implode(' ', $args['class']);
	}

	if ($args['widget_id']) {
		$args['class'] .= ' '.$args['widget_id'];
	}

	// 右サイドバーを表示するか
	$has_rightbar = false;
	if ($args['form_rightbar'] && function_exists($args['form_rightbar'])) {
		$args['class'] .= ' pb-has-right-sidebar';
		$has_rightbar = true;
	}

	// valuesにwidget_index代入
	$values['widget_index'] = $args['widget_index'];

?>

<div id="<?php echo esc_attr($args['id']); ?>" class="pb-modal <?php echo esc_attr($args['class']); ?>" <?php if ($args['attribute']) echo $args['attribute']; ?> data-title="<?php echo esc_attr($args['title']); ?>" data-widget-id="<?php echo esc_attr($args['widget_id']); ?>">
	<div class="pb-overlay"></div>
	<div class="pb-title-bar">
		<h3 class="pb-title"><?php echo esc_html($args['title'].$args['title_after']); ?></h3>
<?php if ($has_rightbar) { ?>
		<a class="pb-toggle-rightbar"><span class="pb-dialog-icon"></span></a>
<?php } ?>
		<a class="pb-close"><span class="pb-dialog-icon"></span></a>
	</div>

	<div class="pb-toolbar">
		<div class="pb-status"></div>
		<div class="pb-buttons">
			<div class="action-buttons">
				<a class="pb-delete" data-confirm="<?php _e('Do you want to delete this content?', 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></a>
				<a class="pb-widget-clone"><?php _e('Clone', 'tcd-w'); ?></a>
			</div>

			<input type="button" class="pb-apply button-primary" value="<?php echo esc_attr($args['primary_button_label']); ?>" />
		</div>
	</div>

	<div class="pb-content">
		<?php
			if ($args['form'] && function_exists($args['form'])) {
				call_user_func($args['form'], $values);
			}
		?>
	</div>

<?php if ($has_rightbar) { ?>
	<div class="pb-sidebar pb-right-sidebar">
		<?php call_user_func($args['form_rightbar'], $values, $args['widget_id'], $args); ?>
	</div>
<?php } ?>

	<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($args['widget_index']); ?>][widget_id]" value="<?php echo esc_attr($args['widget_id']); ?>" />
</div>

<?php
}


/**
 * メタボックス保存
 */
function save_page_builder_metabox($post_id) {
	// verify nonce
	if (!isset($_POST['page_builder_metabox_nonce']) || !wp_verify_nonce($_POST['page_builder_metabox_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check post_type
	if (empty($_POST['post_type']) || !in_array($_POST['post_type'], get_page_builder_post_types())) {
		return $post_id;
	}

	// check permissions
	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	// ページビルダーフラグ
	if (isset($_POST['use_page_builder'])) {
		// メタ保存 プレビュー対策でupdate_post_metaではなくupdate_metadataを使用
		update_metadata('post', $post_id, 'use_page_builder', $_POST['use_page_builder']);
	}

	// ページビルダーデータ
	if (isset($_POST['pagebuilder'])) {
		$data = $_POST['pagebuilder'];

		// 行クローン用を削除
		if (isset($data['row']['rowindex'])) {
			unset($data['row']['rowindex']);
		}
		if (isset($data['row']['indexes'])) {
			$key = array_search('rowindex', $data['row']['indexes']);
			if ($key !== false) {
				unset($data['row']['indexes'][$key]);
			}
		}

		// 行のセル幅のカンマ区切りを配列に変換
		if (!empty($data['row']) && is_array($data['row'])) {
			foreach($data['row'] as $key => $value) {
				if ($key == 'indexes' || !is_numeric($key)) continue;

				if (!empty($value['cells_width'])) {
					if (is_string($value['cells_width'])) {
						$value['cells_width'] = explode(',', $value['cells_width']);
					} else {
						$value['cells_width'] = (array) $value['cells_width'];
					}
					$value['cells_width'] = array_filter($value['cells_width'], 'strlen');
					$data['row'][$key] = $value;
				}
			}
		}

		// セルのウィジェットIDのカンマ区切りを配列に変換
		if (!empty($data['cell']) && is_array($data['cell'])) {
			foreach($data['cell'] as $key => $value) {
				if (is_string($value)) {
					$value = explode(',', $value);
				} else {
					$value = (array) $value;
				}
				$data['cell'][$key] = array_filter($value, 'strlen');
			}
		}

		// ウィジェット
		if (!empty($data['widget']) && is_array($data['widget'])) {
			foreach($data['widget'] as $key => $value) {
				// クローン用は削除
				if (strpos($key, 'widgetindex') !== false) {
					unset($data['widget'][$key]);
					continue;
				}

				// ウィジェットIDが無ければ次へ
				if (empty($value['widget_id'])) continue;
				$widget_id = $value['widget_id'];

				// ウィジェットIDでウィジェット取得
				$widget = get_page_builder_widget($widget_id);

				// ウィジェット保存関数
				if (!empty($widget['save']) && function_exists($widget['save'])) {
					$value = call_user_func($widget['save'], $value, $key, $widget);
				}

				// ウィジェットフィルター
				$value = apply_filters('save_pagebuilder-'.$widget_id, $value, $key);

				$data['widget'][$key] = $value;
			}
		}

		// 全体フィルター適用
		$data = apply_filters('save_pagebuilder', $data);

		// メタ保存 プレビュー対策でupdate_post_metaではなくupdate_metadataを使用
		update_metadata('post', $post_id, 'page_builder', $data);
	}
}
add_action('save_post', 'save_page_builder_metabox');

/**
 * プレビュー対策 プレビュー時のカスタムフィールド値差し替え
 */
function page_builder_preview_get_post_metadata($meta_value, $post_id, $meta_key, $single) {
	global $post;

	// プレビュー時
	if (is_preview()) {
		// ページビルダーのカスタムフィールドキー
		if (in_array($meta_key, array('use_page_builder', 'page_builder'), true)) {
			// 記事IDチェック
			if (!empty($post->ID) && $post->ID == $post_id && $post_id == url_to_postid($_SERVER['REQUEST_URI'])) {
				// 最終リビジョン取得
				$preview = wp_get_post_autosave($post_id);
				if (isset($preview->ID) && $preview->ID != $post_id) {
					// カスタムフィールド値差し替え 第3引数に$singleは渡さないで常にfalse
					$meta_value2 = get_post_meta($preview->ID, $meta_key, false);
					if ($meta_value2 && isset($meta_value2[0])) {
						$meta_value = $meta_value2;
					}
				}
			}
		}
	}

	return $meta_value;
}
add_filter('get_post_metadata', 'page_builder_preview_get_post_metadata', 12, 4);

/**
 * プレビュー対策 リビジョン比較フィールドにページビルダーフィールドに追加 要POSTデータにも同じキーを入れる必要あり
 */
function page_builder_preview_wp_post_revision_fields($fields, $post) {
	if (!empty($_POST['wp-preview']) && $_POST['wp-preview'] == 'dopreview' && !empty($_POST['_page_builder_changed'])) {
		$fields['_page_builder_changed'] = '_page_builder_changed';
	}
	return $fields;
}
add_filter('_wp_post_revision_fields', 'page_builder_preview_wp_post_revision_fields', 20, 2);

/**
 * フロント用 ページビルダー対象か
 */
function is_page_builder($post_id = null) {
	if (!$post_id) $post_id = get_the_ID();
	if (!$post_id) return false;

	$post = get_post($post_id);

	// パスワード保護
	if (post_password_required($post)) {
		return false;
	}

	// ページビルダー対象
	if (!empty($post->post_type) && in_array($post->post_type, get_page_builder_post_types())) {
		return true;
	}

	return false;
}

/**
 * フロント用 ページビルダーHTML
 */
function render_page_builder_content($post_id = null, $echo = false, $is_archive = false) {
	if (!$post_id) $post_id = get_the_ID();
	if (!$post_id) return false;

	// ページビルダーフラグ
	if (!get_post_meta($post_id, 'use_page_builder', true)) return false;

	// ページ分割用グローバル変数
	global $page, $numpages, $multipage, $more, $pages;

	// ページ分割用グローバル変数初期化
	$page = 1;
	$numpages = 1;
	$multipage = 0;
	$pages = array();

	// 出力変数
	$output = '';
	$sep = ' ';

	// ページビルダーデータ
	$data = get_post_meta($post_id, 'page_builder', true);
	if (empty($data)) $data = array();

	// 行ループ
	if (!empty($data['row']['indexes'])) {

		// 表示行番号 $row_indexとは異なるので注意
		$row_num = 0;

		foreach($data['row']['indexes'] as $row_index) {
			// セル幅データが無ければ次の行へ
			if (empty($data['row'][$row_index]['cells_width'])) continue;

			$cells_width = $data['row'][$row_index]['cells_width'];
			if (is_string($cells_width)) {
				$cells_width = explode(',', $cells_width);
				$cells_width = array_filter($cells_width, 'strlen');
			}
			if (empty($cells_width)) continue;

			$row_num++;

			// ページ分割 1行目の場合は無視
			if (!empty($data['row'][$row_index]['nextpage']) && $row_num > 1) {
				$output .= '<!--pbnextpage-->'."\n";

				// マルチページフラグ デフォルト:0
				$multipage = 1;

				// 全ページ数 デフォルト:1
				$numpages++;

				// 続きを読むフラグ デフォルト:0
				$more = 1;
			}

			// 行div
			$output .= str_repeat($sep, 1).'<div class="tcd-pb-row row'.$row_num.'">'."\n";
			$output .= str_repeat($sep, 2).'<div class="tcd-pb-row-inner clearfix">'."\n";

			// カラム番号
			$cell_index = 0;

			// カラムループ
			foreach($cells_width as $cell_width) {
				$cell_index++;

				// カラムdiv
				$output .= str_repeat($sep, 3).'<div class="tcd-pb-col col'.$cell_index.'">'."\n";

				// カラム内のウィジェットインデックスデータ
				if (!empty($data['cell'][$row_index.'-'.$cell_index])) {
					$widget_indexes = $data['cell'][$row_index.'-'.$cell_index];

					// ウィジェット番号 $widget_indexとは異なるので注意
					$widget_num = 0;

					// カラム内のウィジェットループ
					foreach($widget_indexes as $widget_index) {
						// ウィジェットIDが無ければ次へ
						if (empty($data['widget'][$widget_index]['widget_id'])) continue;

						$widget_num++;
						$widget_id = $data['widget'][$widget_index]['widget_id'];
						$widget = get_page_builder_widget($widget_id);
						$widget_value = $data['widget'][$widget_index];

						if (!empty($widget['title'])) {
							// ウィジェットの出力関数
							if (!empty($widget['display']) && function_exists($widget['display'])) {
								// ウィジェットdiv
								$output .= str_repeat($sep, 4).'<div class="tcd-pb-widget widget'.$widget_num.' '.$widget_id.'">'."\n";

								// バッファリング
								ob_start();
								call_user_func($widget['display'], $widget_value, $widget_index, $widget);
								$output .= ob_get_clean();

								$output .= str_repeat($sep, 4).'</div>'."\n"; // .tcd-pb-widget
							}
						}
					}
				} else {
					// ウィジェットなし
					$output .= str_repeat($sep, 4).'&nbsp;'."\n";
				}

				$output .= str_repeat($sep, 3).'</div>'."\n"; // .tcd-pb-col
			}

			$output .= str_repeat($sep, 2).'</div>'."\n"; // .tcd-pb-row-inner
			$output .= str_repeat($sep, 1).'</div>'."\n"; // .tcd-pb-row
		}
	}

	// ページ分割処理
	if ($multipage && $numpages > 1) {
		// リクエストページ番号
		$request_page = (int) get_query_var('page');
		if ($request_page < 1) {
			$request_page = 1;
		}

		$pages = explode('<!--pbnextpage-->', $output);

		// 正常なページ番号
		if (count($pages) >= $request_page) {
			$output = trim($pages[$request_page - 1]);

			// 表示するページ番号 デフォルト:1
			$page = $request_page;

		// 無効なページ番号の場合は1ページ目を表示
		} else {
			$output = trim($pages[0]);
			$page = 1;
		}
	} else {
		$pages = array($output);
	}

	if ($output) {
		// アーカイブ時は対象cssが存在しないためタグ・ショートコードを削除
		if ($is_archive) {
			$output = preg_replace('!<style.*?>.*?</style.*?>!is', '', $output);
			$output = preg_replace('!<script.*?>.*?</script.*?>!is', '', $output);
			$output = strip_shortcodes($output);
			$output = str_replace(']]>', ']]&gt;', $output);
			$output = strip_tags($output);
			$output = strip_tags($output);
			$output = preg_replace("!\s+[\r\n]+!", "\n", $output);
			$output = preg_replace('![\t ]+!', ' ', $output);
			$output = trim($output);

			foreach($pages as $key => $value) {
				if ($value) {
					$value = preg_replace('!<style.*?>.*?</style.*?>!is', '', $value);
					$value = preg_replace('!<script.*?>.*?</script.*?>!is', '', $value);
					$value = strip_shortcodes($value);
					$value = str_replace(']]>', ']]&gt;', $value);
					$value = strip_tags($value);
					$value = preg_replace("!\s+[\r\n]+!", "\n", $value);
					$value = preg_replace('![\t ]+!', ' ', $value);
					$value = trim($value);
					$pages[$key] = $value;
				}
			}

		// 全体div
		} else {
			$output = '<div id="tcd-pb-wrap">'."\n".$output.'</div>'."\n";
		}
	}

	if ($echo) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * フロント用 ページ分割 ページ番号上書き処理
 */
function page_builder_nextpage_override($post_id = null) {
	if (!$post_id) $post_id = get_the_ID();
	if (!$post_id) return false;

	// ページビルダーフラグ
	if (!get_post_meta($post_id, 'use_page_builder', true)) return false;

	// ページ分割用グローバル変数
	global $page, $numpages, $multipage, $more;

	// ページ分割用グローバル変数初期化
	$page = 1;
	$numpages = 1;
	$multipage = 0;

	// ページビルダーデータ
	$data = get_post_meta($post_id, 'page_builder', true);
	if (empty($data)) $data = array();

	// 行ループ
	if (!empty($data['row']['indexes'])) {

		// 表示行番号 $row_indexとは異なるので注意
		$row_num = 0;

		foreach($data['row']['indexes'] as $row_index) {
			// セル幅データが無ければ次の行へ
			if (empty($data['row'][$row_index]['cells_width'])) continue;

			$cells_width = $data['row'][$row_index]['cells_width'];
			if (is_string($cells_width)) {
				$cells_width = explode(',', $cells_width);
				$cells_width = array_filter($cells_width, 'strlen');
			}
			if (empty($cells_width)) continue;

			$row_num++;

			// ページ分割 1行目の場合は無視
			if (!empty($data['row'][$row_index]['nextpage']) && $row_num > 1) {
				// マルチページフラグ デフォルト:0
				$multipage = 1;

				// 全ページ数 デフォルト:1
				if (!$numpages) {
					$numpages = 2;
				} else {
					$numpages++;
				}

				// 続きを読むフラグ デフォルト:0
				$more = 1;
			}
		}
	}

	// ページ分割処理
	if ($multipage && $numpages > 1) {
		// リクエストページ番号
		$request_page = (int) get_query_var('page');
		if ($request_page < 1) {
			$request_page = 1;
		}

		// 正常なページ番号
		if ($numpages >= $request_page) {
			// 表示するページ番号 デフォルト:1
			$page = $request_page;

		// 無効なページ番号の場合は1ページ目を表示
		} else {
			$page = 1;
		}
	}
}

/**
 * フロント用 wpフィルター
 * タイトルタグにページ分割時のページ時番号を表示させる必要があるためwpアクションで先にHTML生成
 */
function page_builder_filter_wp($content) {
	if (is_singular() && is_page_builder()) {
		global $pagebuilder_vars;
		$pagebuilder_vars['content'] = render_page_builder_content();
	}
}
add_filter('wp', 'page_builder_filter_wp', 9);

/**
 * フロント用 the_postアクション
 */
function page_builder_action_the_post($post, $wp_query) {
	if (is_admin()) return;

	global $pagebuilder_vars;
	$pagebuilder_vars['is_main_query'] = $wp_query->is_main_query();
	$pagebuilder_vars['is_singular'] = $wp_query->is_singular();

	// 詳細ページ
	if ($wp_query->is_main_query() && $wp_query->is_singular()) {
		// ページ分割用 ページ番号上書き処理
		page_builder_nextpage_override($post->ID);

	// アーカイブ
	} elseif (is_page_builder($post->ID)) {
		// get_the_content()用にHTML生成し各種グローバル変数を上書き
		render_page_builder_content($post->ID, false, true);
	}
}
add_action('the_post', 'page_builder_action_the_post', 10, 2);

/**
 * フロント用 the_contentフィルター
 */
function page_builder_filter_the_content($content) {
	if (is_admin()) return $content;

	global $pagebuilder_vars;

	// 詳細ページ
	if (!empty($pagebuilder_vars['is_main_query']) && !empty($pagebuilder_vars['is_singular']) && !empty($pagebuilder_vars['content'])) {
		// ページ分割用 ページ番号上書き処理
		page_builder_nextpage_override();

		// wpautopを外してthe_contentの最後に戻す
		if (has_filter('the_content', 'wpautop')) {
			remove_filter('the_content', 'wpautop');
			add_filter('the_content', 'page_builder_filter_the_content_after', 999);
		}

		return $pagebuilder_vars['content'];
	}

	return $content;
}
function page_builder_filter_the_content_after($content) {
	if (!has_filter('the_content', 'wpautop')) {
		add_filter('the_content', 'wpautop');
	}
	remove_filter('the_content', 'page_builder_filter_the_content_after', 999);
	return $content;
}
add_filter('the_content', 'page_builder_filter_the_content', 8);

/**
 * フロント用 動的css
 */
function page_builder_css_wp_head() {
	if (!is_singular() || !is_page_builder()) return;

	$post_id = get_the_ID();

	// ページビルダーフラグ
	if (!get_post_meta($post_id, 'use_page_builder', true)) return;

	// ページビルダーデータ
	$data = get_post_meta($post_id, 'page_builder', true);
	if (empty($data)) return;

	// 行データなし
	if (empty($data['row']['indexes'])) return;

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/pagebuilder/assets/css/pagebuilder.css?ver=<?php echo PAGE_BUILDER_VERSION; ?>">
<style type="text/css">
<?php
	// 表示行番号 $row_indexとは異なるので注意
	$row_num = 0;

	// 行ループ
	foreach($data['row']['indexes'] as $row_index) {
		// セル幅データが無ければ次の行へ
		if (empty($data['row'][$row_index]['cells_width'])) continue;

		$cells_width = $data['row'][$row_index]['cells_width'];
		if (is_string($cells_width)) {
			$cells_width = explode(',', $cells_width);
			$cells_width = array_filter($cells_width, 'strlen');
		}
		if (empty($cells_width)) continue;

		$row = $data['row'][$row_index];
		$row_num++;

		$css = array();
		$css_responsive = array();
		$mobile_media_query = apply_filters('page_builder_mobile_media_query', 767);

		// 整数化マイナスあり
		foreach(array('margin_bottom', 'margin_bottom_mobile') as $key) {
			if (!empty($row[$key])) {
				$row[$key] = intval($row[$key]);
			} else {
				$row[$key] = 0;
			}
		}

		// 整数化マイナスなし
		foreach(array(
			'gutter', 'gutter_mobile',
			'row_width',
			'show_border_top', 'show_border_top_mobile',
			'show_border_bottom', 'show_border_bottom_mobile',
			'show_border_left', 'show_border_left_mobile',
			'show_border_right', 'show_border_right_mobile',
			'border_width', 'border_width_mobile',
			'padding_top', 'padding_top_mobile',
			'padding_bottom', 'padding_bottom_mobile',
			'padding_left', 'padding_left_mobile',
			'padding_right', 'padding_right_mobile'
		) as $key) {
			if (!empty($row[$key])) {
				$row[$key] = abs(intval($row[$key]));
			} else {
				$row[$key] = 0;
			}
		}
		if (!$row['border_width']) {
			$row['border_width'] = 1;
		}
		if (!$row['border_width_mobile']) {
			$row['border_width_mobile'] = 1;
		}

		// gutter半分計算
		if ($row['gutter']) {
			$row['gutter_harf'] = floor($row['gutter'] * 50) / 100; // 小数点2桁切り捨て
		} else {
			$row['gutter_harf'] = 0;
		}
		if ($row['gutter_mobile']) {
			$row['gutter_mobile_harf'] = floor($row['gutter_mobile'] * 50) / 100; // 小数点2桁切り捨て
		} else {
			$row['gutter_mobile_harf'] = 0;
		}

		// 行スタイル配列
		$row_styles = array();
		$row_styles_responsive = array();
		$row_inner_styles = array();
		$row_inner_styles_responsive = array();

		// 行幅指定あり
		if (!empty($row['row_width'])) {
			$row_styles[] = 'max-width:'.$row['row_width'].'px;';
			$row_styles[] = 'margin-left:auto;';
			$row_styles[] = 'margin-right:auto;';
		}

		// 行 2カラム～
		if (count($cells_width) > 1) {
			$row_inner_styles[] = 'margin-left:-'.$row['gutter_harf'].'px;';
			$row_inner_styles[] = 'margin-right:-'.$row['gutter_harf'].'px;';
			$row_inner_styles_responsive[] = 'margin-left:-'.$row['gutter_mobile_harf'].'px;';
			$row_inner_styles_responsive[] = 'margin-right:-'.$row['gutter_mobile_harf'].'px;';
		}

		// 行下マージン
		$row_styles[] = 'margin-bottom:'.$row['margin_bottom'].'px;';
		$row_styles_responsive[] = 'margin-bottom:'.$row['margin_bottom_mobile'].'px;';

		// 行背景色
		if (!empty($row['background_color'])) {
			// 旧カラーピッカー対策
			if (preg_match('/^[0-9a-f]{6}$/i', $row['background_color'])) {
				$row['background_color'] = '#'.$row['background_color'];
			}
			$row_styles[] = 'background-color:'.$row['background_color'].';';
		}

		// 行ボーダー
		if (!empty($row['border_color'])) {
			foreach(array('top', 'bottom', 'left', 'right') as $key) {
				if ($row['show_border_'.$key]) {
					$row_styles[] = 'border-'.$key.':'.$row['border_width'].'px solid '.$row['border_color'].';';
					if ($row['show_border_'.$key.'_mobile']) {
						$row_styles_responsive[] = 'border-'.$key.':'.$row['border_width_mobile'].'px solid '.$row['border_color'].';';
					} else {
						$row_styles_responsive[] = 'border-'.$key.':none;';
					}
				} elseif ($row['show_border_'.$key.'_mobile']) {
					$row_styles_responsive[] = 'border-'.$key.':'.$row['border_width_mobile'].'px solid '.$row['border_color'].';';
				}
			}
		}

		// 行パディング
		if ($row['padding_top'] || $row['padding_bottom'] || $row['padding_left'] || $row['padding_right']) {
			$row_styles[] = 'padding:'.$row['padding_top'].'px '.$row['padding_right'].'px '.$row['padding_bottom'].'px '.$row['padding_left'].'px;';
			$row_styles_responsive[] = 'padding:'.$row['padding_top_mobile'].'px '.$row['padding_right_mobile'].'px '.$row['padding_bottom_mobile'].'px '.$row['padding_left_mobile'].'px;';
		} elseif ($row['padding_top_mobile'] || $row['padding_bottom_mobile'] || $row['padding_left_mobile'] || $row['padding_right_mobile']) {
			$row_styles_responsive[] = 'padding:'.$row['padding_top_mobile'].'px '.$row['padding_right_mobile'].'px '.$row['padding_bottom_mobile'].'px '.$row['padding_left_mobile'].'px;';
		}

		// 行css
		if ($row_styles) {
			$css[0][] = '.tcd-pb-row.row'.$row_num.' { '.implode(' ', $row_styles).' }';
		}
		if ($row_inner_styles) {
			$css[0][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-row-inner { '.implode(' ', $row_inner_styles).' }';
		}
		if ($row_styles_responsive) {
			$css_responsive[$mobile_media_query][] = '.tcd-pb-row.row'.$row_num.' { '.implode(' ', $row_styles_responsive).' }';
		}
		if ($row_inner_styles_responsive) {
			$css_responsive[$mobile_media_query][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-row-inner { '.implode(' ', $row_inner_styles_responsive).' }';
		}

		// カラム番号
		$cell_index = 0;

		// カラムループ
		foreach($cells_width as $cell_width) {
			$cell_index++;

			// $cell_widthは1分率なのでパーセント化 小数点4桁切り捨て
			$cell_width = floor($cell_width * 100 * 10000) / 10000;

			// カラムcss
			// 1カラム
			if (count($cells_width) == 1) {
				$css[0][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' { width:100%; }';

			// 2カラム～
			} else {
				$css[0][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' { width:'.$cell_width.'%; padding-left:'.$row['gutter_harf'].'px; padding-right:'.$row['gutter_harf'].'px; }';

				$css_responsive[$mobile_media_query][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' { padding-left:'.$row['gutter_mobile_harf'].'px; padding-right:'.$row['gutter_mobile_harf'].'px; }';

				// スマートフォンで横並び解除
				if (!empty($row['mobile_cells']) && $row['mobile_cells'] == 'clear') {
					// 最終カラム
					if (count($cells_width) <= $cell_index) {
						$css_responsive[$mobile_media_query][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' { width:100%; float:none; }';
					} else {
						$css_responsive[$mobile_media_query][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' { width:100%; float:none; margin-bottom:'.$row['gutter_mobile'].'px; }';
					}
				}
			}

			// カラム内のウィジェットインデックスデータ
			if (!empty($data['cell'][$row_index.'-'.$cell_index])) {
				$widget_indexes = $data['cell'][$row_index.'-'.$cell_index];

				// ウィジェット番号 $widget_indexとは異なるので注意
				$widget_num = 0;

				// カラム内のウィジェットループ
				foreach($widget_indexes as $widget_index) {
					// ウィジェットIDが無ければ次へ
					if (empty($data['widget'][$widget_index]['widget_id'])) continue;

					$widget_num++;
					$widget_value = $data['widget'][$widget_index];

					// 整数化マイナスあり
					foreach(array('margin_bottom', 'margin_bottom_mobile') as $key) {
						if (!empty($widget_value[$key])) {
							$widget_value[$key] = intval($widget_value[$key]);
						} else {
							$widget_value[$key] = 0;
						}
					}

					// 整数化マイナスなし
					foreach(array(
						'show_border_top', 'show_border_top_mobile',
						'show_border_bottom', 'show_border_bottom_mobile',
						'show_border_left', 'show_border_left_mobile',
						'show_border_right', 'show_border_right_mobile',
						'border_width', 'border_width_mobile',
						'padding_top', 'padding_top_mobile',
						'padding_bottom', 'padding_bottom_mobile',
						'padding_left', 'padding_left_mobile',
						'padding_right', 'padding_right_mobile'
					) as $key) {
						if (!empty($widget_value[$key])) {
							$widget_value[$key] = abs(intval($widget_value[$key]));
						} else {
							$widget_value[$key] = 0;
						}
					}
					if (!$widget_value['border_width']) {
						$widget_value['border_width'] = 1;
					}
					if (!$widget_value['border_width_mobile']) {
						$widget_value['border_width_mobile'] = 1;
					}

					// ウィジェットスタイル配列
					$widget_styles = array();
					$widget_styles_responsive = array();

					// マージン
					if ($widget_value['margin_bottom']) {
						$widget_styles[] = 'margin-bottom:'.$widget_value['margin_bottom'].'px;';
					}
					if ($widget_value['margin_bottom_mobile']) {
						$widget_styles_responsive[] = 'margin-bottom:'.$widget_value['margin_bottom_mobile'].'px;';
					}

					// ウィジェット背景色
					if (!empty($widget_value['use_widget_background_color']) && !empty($widget_value['widget_background_color'])) {
						$widget_styles[] = 'background-color:'.$widget_value['widget_background_color'].';';
					}

					// ウィジェットボーダー
					if (!empty($widget_value['border_color'])) {

						foreach(array('top', 'bottom', 'left', 'right') as $key) {
							if ($widget_value['show_border_'.$key]) {
								$widget_styles[] = 'border-'.$key.':'.$widget_value['border_width'].'px solid '.$widget_value['border_color'].';';
								if ($widget_value['show_border_'.$key.'_mobile']) {
									$widget_styles_responsive[] = 'border-'.$key.':'.$widget_value['border_width_mobile'].'px solid '.$widget_value['border_color'].';';
								} else {
									$widget_styles_responsive[] = 'border-'.$key.':none;';
								}
							} elseif ($widget_value['show_border_'.$key.'_mobile']) {
								$widget_styles_responsive[] = 'border-'.$key.':'.$widget_value['border_width_mobile'].'px solid '.$widget_value['border_color'].';';
							}
						}
					}

					// ウィジェットパディング
					if ($widget_value['padding_top'] || $widget_value['padding_bottom'] || $widget_value['padding_left'] || $widget_value['padding_right']) {
						$widget_styles[] = 'padding:'.$widget_value['padding_top'].'px '.$widget_value['padding_right'].'px '.$widget_value['padding_bottom'].'px '.$widget_value['padding_left'].'px;';
						$widget_styles_responsive[] = 'padding:'.$widget_value['padding_top_mobile'].'px '.$widget_value['padding_right_mobile'].'px '.$widget_value['padding_bottom_mobile'].'px '.$widget_value['padding_left_mobile'].'px;';
					} elseif ($widget_value['padding_top_mobile'] || $widget_value['padding_bottom_mobile'] || $widget_value['padding_left_mobile'] || $widget_value['padding_right_mobile']) {
						$widget_styles_responsive[] = 'padding:'.$widget_value['padding_top_mobile'].'px '.$widget_value['padding_right_mobile'].'px '.$widget_value['padding_bottom_mobile'].'px '.$widget_value['padding_left_mobile'].'px;';
					}

					// ウィジェットcss
					if ($widget_styles) {
						$css[10][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' .tcd-pb-widget.widget'.$widget_num.' { '.implode(' ', $widget_styles).' }';
					}
					if ($widget_styles_responsive) {
						$css_responsive[$mobile_media_query][] = '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' .tcd-pb-widget.widget'.$widget_num.' { '.implode(' ', $widget_styles_responsive).' }';
					}
				}
			}
		}

		// css出力
		if ($css) {
			foreach($css as $key => $value) {
				if (is_array($value)) {
					echo implode("\n", array_filter($value, 'trim'))."\n";
				} elseif (is_string($value)) {
					echo trim($value)."\n";
				}
			}
		}

		// レスポンシブcss出力
		if ($css_responsive) {
			foreach($css_responsive as $key => $value) {
				if (is_int($key)) {
					echo "@media only screen and (max-width:{$key}px) {\n";
				} else {
					echo "@media only screen and ({$key}) {\n";
				}

				if (is_array($value)) {
					echo '  '.implode("\n  ", array_filter($value, 'trim'))."\n";
				} elseif (is_string($value)) {
					echo '  '.trim($value)."\n";
				}

				echo "}\n";
			}
		}
	}

	// 追加css出力アクション
	do_action('page_builder_css');
?>
</style>

<?php
	// 追加出力アクション
	do_action('page_builder_wp_head');
}
add_filter('wp_head', 'page_builder_css_wp_head', 11);

/**
 * 検索対象にページビルダーカスタムフィールド追加
 */
function page_builder_posts_search($search, $query) {
	global $wpdb, $is_page_builder_posts_search;
	$is_page_builder_posts_search = false;

	if ($query->is_search() && $query->get('search_terms')) {

		// 5.1.1版のWP_Query::parse_searchを元にカスタマイズ

		$search           = '';
		$n                = $query->get('exact') ? '' : '%';
		$searchand        = '';
		$exclusion_prefix = apply_filters( 'wp_query_search_exclusion_prefix', '-' );

		foreach ( $query->get('search_terms') as $term ) {
			$exclude = $exclusion_prefix && ( $exclusion_prefix === substr( $term, 0, 1 ) );
			if ( $exclude ) {
				$like_op  = 'NOT LIKE';
				$andor_op = 'AND';
				$term     = substr( $term, 1 );
			} else {
				$like_op  = 'LIKE';
				$andor_op = 'OR';
			}

			$like      = $n . $wpdb->esc_like( $term ) . $n;
			$search   .= $wpdb->prepare( "{$searchand}(({$wpdb->posts}.post_title $like_op %s) $andor_op ({$wpdb->posts}.post_excerpt $like_op %s) $andor_op ({$wpdb->posts}.post_content $like_op %s) $andor_op (pm_use_pb.meta_value = '1' AND pm_pb_data.meta_value LIKE %s))", $like, $like, $like, $like );
			$searchand = ' AND ';
		}

		if ( ! empty( $search ) ) {
			$search = " AND ({$search}) ";
			if ( ! is_user_logged_in() ) {
				$search .= " AND ({$wpdb->posts}.post_password = '') ";
			}
		}

		$is_page_builder_posts_search = true;
	}

	return $search;
}
function page_builder_posts_clauses($clauses, $query) {
	global $wpdb, $is_page_builder_posts_search;

	if ($is_page_builder_posts_search) {

		// join
		$clauses['join'] .= " LEFT JOIN $wpdb->postmeta AS pm_use_pb ON ($wpdb->posts.ID = pm_use_pb.post_id AND pm_use_pb.meta_key = 'use_page_builder') ";
		$clauses['join'] .= " LEFT JOIN $wpdb->postmeta AS pm_pb_data ON ($wpdb->posts.ID = pm_pb_data.post_id AND pm_pb_data.meta_key = 'page_builder') ";

		// groupby
		if (!$clauses['groupby']) {
			$clauses['groupby'] = "$wpdb->posts.ID";
		} elseif (false === strpos($clauses['groupby'], "$wpdb->posts.ID")) {
			$clauses['groupby'] = "$wpdb->posts.ID, " . $clauses['groupby'];
		}

		$is_page_builder_posts_search = false;
	}

	return $clauses;
}
add_filter('posts_search', 'page_builder_posts_search', 10, 2);
add_filter('posts_clauses', 'page_builder_posts_clauses', 10, 2);

/**
 * WordPress Importer インポートフィルター
 *
 * 改行が含まれているとインポート時に改行コードが\r\nから\nになる問題対策
 */
if (!function_exists('page_builder_filter_wp_import_post_data_raw')) {
	function page_builder_filter_wp_import_post_data_raw($post) {
		if (!empty($post['postmeta']) && is_array($post['postmeta'])) {
			foreach($post['postmeta'] as $k => $v) {
				if (!empty($v['key']) && $v['key'] === 'page_builder') {
					$meta_value = $v['value'];
					$meta_value_unserialized = maybe_unserialize($meta_value);

					// maybe_unserialize()が失敗した場合
					if ($meta_value && !$meta_value_unserialized) {
						// 改行を\r\nに変換
						$meta_value = str_replace(array("\r\n", "\r", "\n"), '%%__\n__%%', $meta_value);
						$meta_value = str_replace('%%__\n__%%', "\r\n", $meta_value);
						// maybe_unserialize()が成功すれば代入
						$meta_value_unserialized = maybe_unserialize($meta_value);
						if ($meta_value_unserialized) {
							$post['postmeta'][$k]['value'] = $meta_value_unserialized;
						}
					}
				}
			}
		}
		return $post;
	}
	add_filter('wp_import_post_data_raw', 'page_builder_filter_wp_import_post_data_raw');
}



/************************************************************
 * ここから下、ウィジェットモジュール関連
 ************************************************************/

global $page_builder_widgets;

/**
 * ウィジェットモジュール登録
 */
function add_page_builder_widget($args = array()) {
	global $page_builder_widgets;
	if (!is_array($page_builder_widgets)) {
		$page_builder_widgets = array();
	}

	if (empty($args['id']) || empty($args['title'])) {
		return false;
	}

	$page_builder_widgets[] = $args;
	return true;
}

/**
 * ウィジェットモジュール削除
 */
function remove_page_builder_widget($widget_id) {
	global $page_builder_widgets;
	if (!is_array($page_builder_widgets)) {
		$page_builder_widgets = array();
		return false;
	}

	if ($page_builder_widgets) {
		foreach($page_builder_widgets as $key => $page_builder_widget) {
			if (isset($page_builder_widget['id']) && $page_builder_widget['id'] === $widget_id) {
				unset($page_builder_widgets[$key]);
				return true;
			}
		}
	}

	return false;
}

/**
 * ウィジェットモジュール取得
 * priorityでソートされた配列が返る
 */
function get_page_builder_widgets() {
	global $page_builder_widgets;
	if (!is_array($page_builder_widgets)) {
		$page_builder_widgets = array();
	}

	$pb_widgets = array();
	$pb_widgets_priority = array();
	$pb_widgets_other = array();

	if ($page_builder_widgets) {
		foreach($page_builder_widgets as $page_builder_widget) {
			if (isset($page_builder_widget['priority'])) {
				$pb_widgets_priority[$page_builder_widget['priority']][] = $page_builder_widget;
			} else {
				$pb_widgets_other[] = $page_builder_widget;
			}
		}

		if ($pb_widgets_priority) {
			ksort($pb_widgets_priority);
			foreach($pb_widgets_priority as $key => $value) {
				$pb_widgets = array_merge($pb_widgets, $value);
			}
		}

		if ($pb_widgets_other) {
			$pb_widgets = array_merge($pb_widgets, $pb_widgets_other);
		}
	}

	return apply_filters('get_page_builder_widgets', $pb_widgets);
}

/**
 * ウィジェットIDからウィジェットモジュール取得
 */
function get_page_builder_widget($widget_id) {
	global $page_builder_widgets;
	if (!is_array($page_builder_widgets)) {
		$page_builder_widgets = array();
	}

	if ($widget_id && $page_builder_widgets) {
		foreach($page_builder_widgets as $page_builder_widget) {
			if (isset($page_builder_widget['id']) && $page_builder_widget['id'] == $widget_id) {
				return $page_builder_widget;
			}
		}
	}

	return false;
}

/**
 * フロント用 ウィジェットが使用されているかどうか
 */
function page_builder_has_widget($widget_id, $post_id = null) {
	if (!$post_id) $post_id = get_the_ID();
	if (!$post_id) return false;

	// ページビルダーフラグ
	if (!get_post_meta($post_id, 'use_page_builder', true)) {
		return false;
	}

	// ページビルダーデータ
	$data = get_post_meta($post_id, 'page_builder', true);
	if (empty($data)) return false;

	if (!empty($data['widget']) && is_array($data['widget'])) {
		foreach($data['widget'] as $index => $widget) {
			if (isset($widget['widget_id']) && $widget['widget_id'] === $widget_id) {
				return true;
			}
		}
	}

	return false;
}

/**
 * ウィジェットフォーム 標準右サイドバー
 */
function form_rightbar_page_builder_widget($values = array(), $widget_id = null, $args = array()) {
	// デフォルト値
	$default_values = array(
		'widget_index' => '',
		'margin_bottom' => 30,
		'margin_bottom_mobile' => 30,
		'use_widget_background_color' => 0,
		'widget_background_color' => '#ffffff',
		'show_border_top' => 0,
		'show_border_bottom' => 0,
		'show_border_left' => 0,
		'show_border_right' => 0,
		'show_border_top_mobile' => 0,
		'show_border_bottom_mobile' => 0,
		'show_border_left_mobile' => 0,
		'show_border_right_mobile' => 0,
		'border_color' => '#dddddd',
		'border_width' => 1,
		'border_width_mobile' => 1,
		'padding_top' => 0,
		'padding_bottom' => 0,
		'padding_left' => 0,
		'padding_right' => 0,
		'padding_top_mobile' => 0,
		'padding_bottom_mobile' => 0,
		'padding_left_mobile' => 0,
		'padding_right_mobile' => 0
	);

	// デフォルト値フィルター
	$default_values = apply_filters('page_builder_widget_form_rightbar_default_values', $default_values, 'form_rightbar', $widget_id, $args);
	$default_values = apply_filters('page_builder_widget_default_values', $default_values, 'form_rightbar', $widget_id, $args);
	if ($widget_id) {
		$default_values = apply_filters('page_builder_widget_'.$widget_id.'_default_values', $default_values, 'form_rightbar', $widget_id, $args);
	}

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);
?>

<h3 data-pb-toggle-target=".pb-right-sidebar-margin" data-pb-toggle-status="open"><?php _e('Margin setting', 'tcd-w'); ?></h3>
<div class="pb-toggle-content pb-right-sidebar-margin">
	<div class="form-field">
		<label><?php _e('Margin bottom', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][margin_bottom]" value="<?php echo esc_attr($values['margin_bottom']); ?>" class="small-text" /> px
		<p class="pb-description"><?php _e('Space below the content.<br />Default is 30px.', 'tcd-w'); ?></p>
	</div>

	<div class="form-field">
		<label><?php _e('Margin bottom for mobile', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][margin_bottom_mobile]" value="<?php echo esc_attr($values['margin_bottom_mobile']); ?>" class="small-text" /> px
		<p class="pb-description"><?php _e('Space below the content.<br />Default is 30px.', 'tcd-w'); ?></p>
	</div>
</div>

<h3 data-pb-toggle-target=".pb-right-sidebar-background" data-pb-toggle-status="close"><?php _e('Background setting', 'tcd-w'); ?></h3>
<div class="pb-toggle-content pb-right-sidebar-background">
		<div class="form-field">
			<label class="checkbox">
				<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][use_widget_background_color]" value="1" <?php checked($values['use_widget_background_color'] ,1); ?> class="use_widget_background_color" />
				<?php _e('Set the background color', 'tcd-w'); ?>
			</label>
		</div>
		<div class="form-field form-field-widget_background_color">
			<label><?php _e('Background color', 'tcd-w'); ?></label>
			<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][widget_background_color]" value="<?php echo esc_attr($values['widget_background_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['widget_background_color']); ?>" />
		</div>
</div>

<h3 data-pb-toggle-target=".pb-right-sidebar-border" data-pb-toggle-status="close"><?php _e('Border setting', 'tcd-w'); ?></h3>
<div class="pb-toggle-content pb-right-sidebar-border">
	<div class="form-field">
		<ul>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_top]" value="1" <?php checked($values['show_border_top'] ,1); ?> />
					<?php _e('Display top border', 'tcd-w'); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_bottom]" value="1" <?php checked($values['show_border_bottom'] ,1); ?> />
					<?php _e('Display bottom border', 'tcd-w'); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_left]" value="1" <?php checked($values['show_border_left'] ,1); ?> />
					<?php _e('Display left border', 'tcd-w'); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_right]" value="1" <?php checked($values['show_border_right'] ,1); ?> />
					<?php _e('Display right border', 'tcd-w'); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_top_mobile]" value="1" <?php checked($values['show_border_top_mobile'] ,1); ?> />
					<?php _e('Display top border for mobile', 'tcd-w'); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_bottom_mobile]" value="1" <?php checked($values['show_border_bottom_mobile'] ,1); ?> />
					<?php _e('Display bottom border for mobile', 'tcd-w'); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_left_mobile]" value="1" <?php checked($values['show_border_left_mobile'] ,1); ?> />
					<?php _e('Display left border for mobile', 'tcd-w'); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_border_right_mobile]" value="1" <?php checked($values['show_border_right_mobile'] ,1); ?> />
					<?php _e('Display right border for mobile', 'tcd-w'); ?>
				</label>
			</li>
		</ul>
	</div>

	<div class="form-field">
		<label><?php _e('Border color', 'tcd-w'); ?></label>
		<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][border_color]" value="<?php echo esc_attr($values['border_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['border_color']); ?>" />
	</div>

	<div class="form-field">
		<label><?php _e('Border width', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][border_width]" value="<?php echo esc_attr($values['border_width']); ?>" class="small-text" min="1" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Border width for mobile', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][border_width_mobile]" value="<?php echo esc_attr($values['border_width_mobile']); ?>" class="small-text" min="1" /> px
	</div>
</div>

<h3 data-pb-toggle-target=".pb-right-sidebar-padding" data-pb-toggle-status="close"><?php _e('Padding setting', 'tcd-w'); ?></h3>
<div class="pb-toggle-content pb-right-sidebar-padding">
	<div class="form-field">
		<label><?php _e('Top padding width', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_top]" value="<?php echo esc_attr($values['padding_top']); ?>" class="small-text" min="0" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Bottom padding width', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_bottom]" value="<?php echo esc_attr($values['padding_bottom']); ?>" class="small-text" min="0" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Left padding width', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_left]" value="<?php echo esc_attr($values['padding_left']); ?>" class="small-text" min="0" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Right padding width', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_right]" value="<?php echo esc_attr($values['padding_right']); ?>" class="small-text" min="0" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Top padding width for mobile', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_top_mobile]" value="<?php echo esc_attr($values['padding_top_mobile']); ?>" class="small-text" min="0" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Bottom padding width for mobile', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_bottom_mobile]" value="<?php echo esc_attr($values['padding_bottom_mobile']); ?>" class="small-text" min="0" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Left padding width for mobile', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_left_mobile]" value="<?php echo esc_attr($values['padding_left_mobile']); ?>" class="small-text" min="0" /> px
	</div>

	<div class="form-field">
		<label><?php _e('Right padding width for mobile', 'tcd-w'); ?></label>
		<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][padding_right_mobile]" value="<?php echo esc_attr($values['padding_right_mobile']); ?>" class="small-text" min="0" /> px
	</div>
</div>

<?php
}

/**
 * フロント用 記事ID指定で使用しているウィジェット配列を左上から右下の行カラムの順で返す
 *
 * @param int    $post_id          Optional. 記事ID 未指定の場合は現在の記事
 * @param string $widget_id_filter Optional. $widget_idで絞り込みを行う場合に指定
 */
function get_page_builder_post_widgets($post_id = null, $widget_id_filter = null) {
	$post_widgets = array();

	if (!$post_id) $post_id = get_the_ID();
	if (!$post_id) return false;

	if (!is_page_builder($post_id)) return false;

	// ページビルダーフラグ
	if (!get_post_meta($post_id, 'use_page_builder', true)) return false;

	// ページビルダーデータ
	$data = get_post_meta($post_id, 'page_builder', true);
	if (empty($data)) return false;

	// 行ループ
	if (!empty($data['row']['indexes'])) {
		// 表示行番号 $row_indexとは異なるので注意
		$row_num = 0;

		foreach($data['row']['indexes'] as $row_index) {
			// セル幅データが無ければ次の行へ
			if (empty($data['row'][$row_index]['cells_width'])) continue;

			$cells_width = $data['row'][$row_index]['cells_width'];
			if (is_string($cells_width)) {
				$cells_width = explode(',', $cells_width);
				$cells_width = array_filter($cells_width, 'strlen');
			}
			if (empty($cells_width)) continue;

			$row_num++;

			// カラム番号
			$cell_index = 0;

			// カラムループ
			foreach($cells_width as $cell_width) {
				$cell_index++;

				// カラム内のウィジェットインデックスデータ
				if (!empty($data['cell'][$row_index.'-'.$cell_index])) {
					$widget_indexes = $data['cell'][$row_index.'-'.$cell_index];

					// ウィジェット番号 $widget_indexとは異なるので注意
					$widget_num = 0;

					// カラム内のウィジェットループ
					foreach($widget_indexes as $widget_index) {
						// ウィジェットIDが無ければ次へ
						if (empty($data['widget'][$widget_index]['widget_id'])) continue;

						$widget_num++;

						// ウィジェットID絞り込みがある場合、一致しなければ次へ
						if ($widget_id_filter && $widget_id_filter !== $data['widget'][$widget_index]['widget_id']) continue;

						$widget_id = $data['widget'][$widget_index]['widget_id'];
						$widget = get_page_builder_widget($widget_id);
						$widget_value = $data['widget'][$widget_index];

						if ($widget) {
							$post_widgets[] = array(
								'widget_index' => $widget_index,
								'widget_id' => $widget_id,
								'widget' => $widget,
								'widget_value' => $widget_value,
								'row_num' => $row_num,
								'col_num' => $cell_index,
								'widget_num' => $widget_num,
								'css_class' => '.tcd-pb-row.row'.$row_num.' .tcd-pb-col.col'.$cell_index.' .tcd-pb-widget.widget'.$widget_num
							);
						}
					}
				}
			}
		}
	}

	return $post_widgets;
}

/**
 * 引数フォルダの全ファイル読み込み
 */
function page_builder_modules_load($dir) {
	if (!file_exists($dir) || !is_dir($dir)) return false;

	if ($handle = opendir($dir)) {
		$file_list = array();
		while (false !== ($file_name = readdir($handle))) {
			// 除外項目
			if (substr($file_name, '-4') != '.php' || $file_name == 'index.php') continue;

			if (is_file($dir.'/'.$file_name)) {
				$file_list[] = $file_name;
			}
		}
		if ($file_list) {
			sort($file_list);
			foreach($file_list as $file_name) {
				require $dir.'/'.$file_name;
			}
		}
	}
}

/**
 * modulesフォルダの全ファイル読み込み
 * /modules/includesを先に読み込む
 */
page_builder_modules_load(dirname(__FILE__).'/modules/includes');
page_builder_modules_load(dirname(__FILE__).'/modules');
do_action('page_builder_modules_loaded');
