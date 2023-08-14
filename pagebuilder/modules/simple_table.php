<?php

/**
 * ページビルダーウィジェット登録
 */
add_page_builder_widget(array(
	'id' => 'pb-widget-simple_table',
	'form' => 'form_page_builder_widget_simple_table',
	'form_rightbar' => 'form_rightbar_page_builder_widget', // 標準右サイドバー
	'save' => 'save_page_builder_repeater',
	'display' => 'display_page_builder_widget_simple_table',
	'title' => __('Sipmle table', 'tcd-w'),
	'description' => __('You can display two columns table.', 'tcd-w'),
	'additional_class' => 'pb-repeater-widget',
	'priority' => 41
));

/**
 * フォーム
 */
function form_page_builder_widget_simple_table($values = array()) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_simple_table_default_values', array(
		'widget_index' => '',
		'th_color' => page_builder_get_primary_color('#000000'),
		'th_background_color' => '#f9f9f9',
		'repeater' => array()
	), 'form');

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);

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
				form_page_builder_widget_simple_table_repeater_row(
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
	form_page_builder_widget_simple_table_repeater_row(
		array(
			'widget_index' => $values['widget_index'],
			'repeater_index' => 'pb_repeater_add_index'
		),
		array(
			'repeater_label' => __('New item', 'tcd-w')
		)
	);

	echo '</div>'."\n"; // .add_pb_repeater_clone
?>

<div class="form-field">
	<h4><?php _e('Color for headline', 'tcd-w'); ?></h4>
	<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][th_color]" value="<?php echo esc_attr($values['th_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['th_color']); ?>" />
</div>

<div class="form-field">
	<h4><?php _e('Background color for headline', 'tcd-w'); ?></h4>
	<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][th_background_color]" value="<?php echo esc_attr($values['th_background_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['th_background_color']); ?>" />
</div>

<?php
	echo '</div>'."\n"; // .pb_repeater_wrap
}

/**
 * リピーター行出力
 */
function form_page_builder_widget_simple_table_repeater_row($values = array(), $row_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => ''
		),
		(array) $values
	);

	// 行デフォルト値
	$default_row_values = apply_filters('page_builder_widget_simple_table_default_row_values', array(
		'repeater_label' => '',
		'headline' => '',
		'content_type' => 'type1',
		'content_text' => '',
		'website_url' => '',
		'facebook_url' => '',
		'twitter_url' => '',
		'instagram_url' => '',
		'pinterest_url' => '',
		'flickr_url' => '',
		'tumblr_url' => '',
		'youtube_url' => '',
		'contact_url' => '',
		'rss_url' => ''
	));

	// 行デフォルト値に行の値をマージ
	$row_values = array_merge(
		$default_row_values,
		(array) $row_values
	);

	// リピーター表示名
	if (!$row_values['repeater_label'] && $row_values['headline']) {
		$row_values['repeater_label'] = $row_values['headline'];
	} elseif (!$row_values['repeater_label']) {
		$row_values['repeater_label'] = __('New item', 'tcd-w');
	}
?>

<div id="pb_simple_table-<?php echo esc_attr($values['widget_index'].'-'.$values['repeater_index']); ?>" class="pb_repeater pb_repeater-<?php echo esc_attr($values['repeater_index']); ?>">
	<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater_index][]" value="<?php echo esc_attr($values['repeater_index']); ?>" />
	<ul class="pb_repeater_button pb_repeater_cf">
		<li><span class="pb_repeater_move"><?php _e('Move', 'tcd-w'); ?></span></li>
		<li><span class="pb_repeater_delete" data-confirm="<?php _e('Are you sure you want to delete this item?', 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></span></li>
	</ul>
	<div class="pb_repeater_content">
		<h3 class="pb_repeater_headline"><span class="index_label"><?php echo esc_attr($row_values['repeater_label']); ?></span><a href="#"><?php _e('Open', 'tcd-w'); ?></a></h3>
		<div class="pb_repeater_field">
			<div class="form-field">
				<h4><?php _e('Headline', 'tcd-w'); ?></h4>
				<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][headline]" value="<?php echo esc_attr($row_values['headline']); ?>" class="index_label" />
			</div>
			<div class="form-field form-field-radio">
				<h4><?php _e('Content type', 'tcd-w'); ?></h4>
				<?php
					$radio_options = array(
						'type1' => __('Text', 'tcd-w'),
						'type2' => __('SNS icon', 'tcd-w'),
					);
					$radio_html = array();
					foreach($radio_options as $key => $value) {
						$attr = '';
						if ($row_values['content_type'] == $key) {
							$attr .= ' checked="checked"';
						}
						$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][repeater]['.esc_attr($values['repeater_index']).'][content_type]" value="'.esc_attr($key).'" class="content_type"'.$attr.' />'.esc_html($value).'</label>';
					}
					echo implode("<br>\n\t\t\t\t", $radio_html);
				?>
			</div>
			<div class="form-field content_type-type1"<?php if ($row_values['content_type'] != 'type1') echo ' style="display:none;"'; ?>>
				<h4><?php _e('Text', 'tcd-w'); ?></h4>
				<textarea name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][content_text]" rows="2"><?php echo esc_textarea($row_values['content_text']); ?></textarea>
			</div>
			<div class="content_type-type2"<?php if ($row_values['content_type'] != 'type2') echo ' style="display:none;"'; ?>>
				<div class="form-field">
					<h4><?php _e('Website URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][website_url]" value="<?php echo esc_attr($row_values['website_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Facebook URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][facebook_url]" value="<?php echo esc_attr($row_values['facebook_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Twitter URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][twitter_url]" value="<?php echo esc_attr($row_values['twitter_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Instagram URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][instagram_url]" value="<?php echo esc_attr($row_values['instagram_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Pinterest URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][pinterest_url]" value="<?php echo esc_attr($row_values['pinterest_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Flickr URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][flickr_url]" value="<?php echo esc_attr($row_values['flickr_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Tumblr URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][tumblr_url]" value="<?php echo esc_attr($row_values['tumblr_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Youtube URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][youtube_url]" value="<?php echo esc_attr($row_values['youtube_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('Contact page URL (You can use mailto:)', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][contact_url]" value="<?php echo esc_attr($row_values['contact_url']); ?>" />
				</div>
				<div class="form-field">
					<h4><?php _e('RSS URL', 'tcd-w'); ?></h4>
					<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][rss_url]" value="<?php echo esc_attr($row_values['rss_url']); ?>" />
				</div>
			</div>
		</div>
	</div>
</div>

<?php
}

/**
 * フロント出力
 */
function display_page_builder_widget_simple_table($values = array(), $widget_index = null, $widget = array(), $post_id = null) {
	// リピーター行の並び
	if (!empty($values['repeater_index']) && is_array($values['repeater_index'])) {
		$repeater_indexes = $values['repeater_index'];
	} elseif (!empty($values['repeater']) && is_array($values['repeater'])) {
		$repeater_indexes = array_keys($values['repeater']);
	}

	if (!empty($repeater_indexes)) {
		// リピーター行ループし、行データが無ければ削除
		foreach($repeater_indexes as $key => $repeater_index) {
			if (empty($values['repeater'][$repeater_index])) {
				unset($repeater_indexes[$key]);
			}
		}
	}

	// リピーター行がなければ終了
	if (empty($repeater_indexes)) return;

	echo '<table class="pb_simple_table">'."\n";

	foreach($repeater_indexes as $repeater_index) {
		$repeater_values = $values['repeater'][$repeater_index];

		echo '<tr>';
		echo '<th>'.esc_html($repeater_values['headline']).'</th>';
		echo '<td>';

		// SNSアイコン表示
		if ($repeater_values['content_type'] == 'type2') {
			$sns_html = '';

			if ($repeater_values['website_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-website"><a href="' . esc_attr( $repeater_values['website_url'] ) . '" target="_blank"><span>website</span></a></li>';
			}
			if ($repeater_values['facebook_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-facebook"><a href="' . esc_attr( $repeater_values['facebook_url'] ) . '" target="_blank"><span>facebook</span></a></li>';
			}
			if ($repeater_values['twitter_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-twitter"><a href="' . esc_attr( $repeater_values['twitter_url'] ) . '" target="_blank"><span>twitter</span></a></li>';
			}
			if ($repeater_values['instagram_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-instagram"><a href="' . esc_attr( $repeater_values['instagram_url'] ) . '" target="_blank"><span>instagram</span></a></li>';
			}
			if ($repeater_values['pinterest_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-pinterest"><a href="' . esc_attr( $repeater_values['pinterest_url'] ) . '" target="_blank"><span>pinterest</span></a></li>';
			}
			if ($repeater_values['flickr_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-flickr"><a href="' . esc_attr( $repeater_values['flickr_url'] ) . '" target="_blank"><span>flickr</span></a></li>';
			}
			if ($repeater_values['tumblr_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-tumblr"><a href="' . esc_attr( $repeater_values['tumblr_url'] ) . '" target="_blank"><span>tumblr</span></a></li>';
			}
			if ($repeater_values['youtube_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-youtube"><a href="' . esc_attr( $repeater_values['youtube_url'] ) . '" target="_blank"><span>youtube</span></a></li>';
			}
			if ($repeater_values['contact_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-contact"><a href="' . esc_attr( $repeater_values['contact_url'] ) . '" target="_blank"><span>contact</span></a></li>';
			}
			if ($repeater_values['rss_url']) {
				$sns_html .= '<li class="pb_simple_table_icon-rss"><a href="' . esc_attr( $repeater_values['rss_url'] ) . '" target="_blank"><span>rss</span></a></li>';
			}

			if ($sns_html) {
				echo '<ul class="pb_simple_table_icons">'.$sns_html.'</ul>';
			}

		// テキスト表示
		} else {
			// URL自動リンク
			$pattern = '/(=[\"\'])?https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
			$content_text = preg_replace_callback( $pattern, function( $matches ) {
				// 既にリンク等の場合はそのまま
				if ( isset( $matches[1] ) ) return $matches[0];
				return "<a href=\"{$matches[0]}\" target=\"_blank\">{$matches[0]}</a>";
			}, $repeater_values['content_text'] );
			echo wpautop( trim( $content_text ) );
		}

		echo '</td>';
		echo '</tr>'."\n";
	}

	echo '</table>'."\n";
}

/**
 * 管理画面用js
 */
function page_builder_simple_table_admin_scripts() {
	wp_enqueue_script('page_builder-simple_table', get_template_directory_uri().'/pagebuilder/assets/admin/js/simple_table.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}
add_action('page_builder_admin_styles', 'page_builder_simple_table_admin_scripts', 12);

/**
 * フロント用css
 */
function page_builder_widget_simple_table_styles() {
	wp_enqueue_style('page_builder-simple_table', get_template_directory_uri().'/pagebuilder/assets/css/simple_table.css', false, PAGE_BUILDER_VERSION);
}

function page_builder_widget_simple_table_sctipts_styles($arg = null) {
	// wpフック時には第1引数にWPクラスが渡るので注意
	if ($arg === true) {
		add_action('wp_enqueue_scripts', 'page_builder_widget_simple_table_styles', 11);
	}
	if (is_singular() && is_page_builder() && page_builder_has_widget('pb-widget-simple_table')) {
		add_action('wp_enqueue_scripts', 'page_builder_widget_simple_table_styles', 11);
		add_action('page_builder_css', 'page_builder_widget_simple_table_css');
	}
}
add_action('wp', 'page_builder_widget_simple_table_sctipts_styles');

function page_builder_widget_simple_table_css() {
	// 現記事で使用しているsimple_tableコンテンツデータを取得
	$post_widgets = get_page_builder_post_widgets(get_the_ID(), 'pb-widget-simple_table');
	if ($post_widgets) {
		foreach($post_widgets as $post_widget) {
			$values = $post_widget['widget_value'];

			echo $post_widget['css_class'].' .pb_simple_table th { background-color: '.esc_attr($values['th_background_color']).'; color: '.esc_attr($values['th_color']).'; }'."\n";
		}
	}
}
