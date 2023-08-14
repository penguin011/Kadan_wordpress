<?php

/**
 * 画像サイズ登録
 */
add_image_size('page_builder_slider_small', 300, 300, true);
//add_image_size('page_builder_slider_large', 1200, 0, false);

/**
 * ページビルダーウィジェット登録
 */
add_page_builder_widget(array(
	'id' => 'pb-widget-slider',
	'form' => 'form_page_builder_widget_slider',
	'form_rightbar' => 'form_rightbar_page_builder_widget', // 標準右サイドバー
	'save' => 'save_page_builder_repeater',
	'display' => 'display_page_builder_widget_slider',
	'title' => __('Slider', 'tcd-w'),
	'description' => __('You can display gallery slider.', 'tcd-w'),
	'additional_class' => 'pb-repeater-widget',
	'priority' => 21
));

/**
 * 管理画面用js
 */
function page_builder_widget_slider_admin_scripts() {
	wp_enqueue_script('page_builder-slider-admin', get_template_directory_uri().'/pagebuilder/assets/admin/js/slider.js', array('jquery'), PAGE_BUILDER_VERSION, true);
}
add_action('page_builder_admin_scripts', 'page_builder_widget_slider_admin_scripts', 12);

/**
 * フォーム デフォルト値
 */
function get_page_builder_widget_slider_default_values() {
	$default_values = array(
		'widget_index' => '',
		'slide_time' => 10,
		'arrow_color' => '#ffffff',
		'arrow_background_color' => '#000000',
		'arrow_opacity' => 0.6,
		'arrow_color_hover' => '#ffffff',
		'arrow_background_color_hover' => '#000000',
		'arrow_opacity_hover' => 1,
		'footer_type' => 'type1',
		'caption_padding' => 40,
		'caption_padding_mobile' => 20,
		'show_caption_border' => 1,
		'repeater' => array(),
		'margin_bottom' => 30,
		'margin_bottom_mobile' => 30
	);

	return apply_filters('get_page_builder_widget_slider_default_values', $default_values);
}


/**
 * フォーム
 */
function form_page_builder_widget_slider($values = array()) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_slider_default_values', get_page_builder_widget_slider_default_values(), 'form');

	// デフォルト値に入力値をマージ
	$values = array_merge($default_values, (array) $values);
?>

<div class="form-field">
	<h4><?php _e('Slide speed', 'tcd-w'); ?></h4>
	<input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][slide_time]" value="<?php echo esc_attr($values['slide_time']); ?>" class="small-text" min="5" /> <?php _e('seconds', 'tcd-w'); ?>
</div>

<div class="form-field">
	<h4><?php _e('Arrow setting', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Arrow color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][arrow_color]" value="<?php echo esc_attr($values['arrow_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['arrow_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][arrow_background_color]" value="<?php echo esc_attr($values['arrow_background_color']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['arrow_background_color']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Opacity', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][arrow_opacity]" value="<?php echo esc_attr($values['arrow_opacity']); ?>" class="small-text" min="0" max="1" step="0.1" /></td>
		</tr>
		<tr>
			<td><?php _e('Arrow color on hover', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][arrow_color_hover]" value="<?php echo esc_attr($values['arrow_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['arrow_color_hover']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Background color on hover', 'tcd-w'); ?></td>
			<td><input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][arrow_background_color_hover]" value="<?php echo esc_attr($values['arrow_background_color_hover']); ?>" class="pb-wp-color-picker" data-default-color="<?php echo esc_attr($default_values['arrow_background_color_hover']); ?>" /></td>
		</tr>
		<tr>
			<td><?php _e('Opacity on hover', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][arrow_opacity_hover]" value="<?php echo esc_attr($values['arrow_opacity_hover']); ?>" class="small-text" min="0" max="1" step="0.1" /></td>
		</tr>
	</table>
</div>

<div class="form-field form-field-radio form-field-footer_type">
	<h4><?php _e('Slider footer', 'tcd-w'); ?></h4>
	<?php
		$radio_options = array(
			'type1' => __('Display thumbnail navigation', 'tcd-w'),
			'type2' => __('Display caption', 'tcd-w'),
			'none' => __('None', 'tcd-w')
		);
		$radio_html = array();
		foreach($radio_options as $key => $value) {
			$attr = '';
			if ($values['footer_type'] == $key) {
				$attr .= ' checked="checked"';
			}
			$radio_html[] = '<label><input type="radio" name="pagebuilder[widget]['.esc_attr($values['widget_index']).'][footer_type]" value="'.esc_attr($key).'"'.$attr.' />'.esc_html($value).'</label>';
		}
		echo implode("<br>\n\t", $radio_html);
	?>
</div>

<div class="form-field form-field-footer_type-type2 hidden">
	<h4><?php _e('Caption border setting', 'tcd-w'); ?></h4>
	<label class="checkbox">
		<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_caption_border]" value="0" />
		<input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][show_caption_border]" value="1" <?php checked($values['show_caption_border'] ,1); ?> />
		<?php _e('Display caption border', 'tcd-w'); ?>
	</label>
</div>

<div class="form-field form-field-footer_type-type2 hidden">
	<h4><?php _e('Caption padding setting', 'tcd-w'); ?></h4>
	<table style="margin-top:5px;">
		<tr>
			<td><?php _e('Padding width', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][caption_padding]" value="<?php echo esc_attr($values['caption_padding']); ?>" class="small-text" min="0" /> px</td>
		</tr>
		<tr>
			<td><?php _e('Padding width for mobile', 'tcd-w'); ?></td>
			<td><input type="number" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][caption_padding_mobile]" value="<?php echo esc_attr($values['caption_padding_mobile']); ?>" class="small-text" min="0" /> px</td>
		</tr>
	</table>
</div>

<div class="form-field">
	<h4><?php _e('Slide setting', 'tcd-w'); ?></h4>
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
				form_page_builder_widget_slider_repeater_row(
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
	form_page_builder_widget_slider_repeater_row(
		array(
			'widget_index' => $values['widget_index'],
			'repeater_index' => 'pb_repeater_add_index'
		)
	);

	echo '</div>'."\n"; // .add_pb_repeater_clone

	echo '</div>'."\n"; // .pb_repeater_wrap
}

/**
 * リピーター行出力
 */
function form_page_builder_widget_slider_repeater_row($values = array(), $row_values = array()) {
	// デフォルト値に入力値をマージ
	$values = array_merge(
		array(
			'widget_index' => '',
			'repeater_index' => ''
		),
		(array) $values
	);

	// 行デフォルト値
	$default_row_values = apply_filters('page_builder_widget_slider_default_row_values', array(
		'repeater_label' => '',
		'image' => '',
		'link' => '',
		'target_blank' => '',
		'caption' => ''
	));

	// 行デフォルト値に行の値をマージ
	$row_values = array_merge(
		$default_row_values,
		(array) $row_values
	);

	// リピーター表示名
	if (!$row_values['repeater_label']) {
		$row_values['repeater_label'] = __('Image', 'tcd-w').' '.$values['repeater_index'];
	}
?>

<div id="pb_slider-<?php echo esc_attr($values['widget_index'].'-'.$values['repeater_index']); ?>" class="pb_repeater pb_repeater-<?php echo esc_attr($values['repeater_index']); ?>">
	<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater_index][]" value="<?php echo esc_attr($values['repeater_index']); ?>" />
	<ul class="pb_repeater_button pb_repeater_cf">
		<li><span class="pb_repeater_move"><?php _e('Move', 'tcd-w'); ?></span></li>
		<li><span class="pb_repeater_delete" data-confirm="<?php _e('Are you sure you want to delete this item?', 'tcd-w'); ?>"><?php _e('Delete', 'tcd-w'); ?></span></li>
	</ul>
	<div class="pb_repeater_content">
		<h3 class="pb_repeater_headline"><span class="index_label"><?php echo esc_html($row_values['repeater_label']); ?></span><a href="#"><?php _e('Open', 'tcd-w'); ?></a></h3>
		<div class="pb_repeater_field">
			<div class="form-field">
				<h4><?php _e('Image', 'tcd-w'); ?></h4>
				<?php
					$input_name = 'pagebuilder[widget]['.$values['widget_index'].'][repeater]['.$values['repeater_index'].'][image]';
					$media_id = $row_values['image'];
					pb_media_form($input_name, $media_id);
				?>
			</div>

			<div class="form-field">
				<h4><?php _e('Link URL for image', 'tcd-w'); ?></h4>
				<input type="text" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][link]" value="<?php echo esc_attr($row_values['link']); ?>" />
			</div>

			<div class="form-field">
				<h4><?php _e('Link target', 'tcd-w'); ?></h4>
				<input type="hidden" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][target_blank]" value="0" />
				<label><input type="checkbox" name="pagebuilder[widget][<?php echo esc_attr($values['widget_index']); ?>][repeater][<?php echo esc_attr($values['repeater_index']); ?>][target_blank]" value="1"<?php if ($row_values['target_blank']) echo ' checked="checked"'; ?> /> <?php _e('Use target blank for this link', 'tcd-w'); ?></label>
			</div>

			<div class="form-field form-field-editor form-field-footer_type-type2 hidden">
				<h4><?php _e('Caption', 'tcd-w'); ?></h4>
				<?php
					wp_editor(
						$row_values['caption'],
						str_replace('-', '_', 'pb_slider_'.$values['widget_index'].'_'.$values['repeater_index'].'_caption'),
						array(
							'textarea_name' => 'pagebuilder[widget]['.$values['widget_index'].'][repeater]['.$values['repeater_index'].'][caption]',
							'textarea_rows' => 5
						)
					);
				?>
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
function page_builder_widget_slider_tiny_mce_before_init($mceInit, $editor_id) {
	if (strpos($editor_id, 'pb_slider_') == 0 && strpos($editor_id, '_pb_repeater_add_index_caption') !== false) {
		$mceInit['wp_skip_init'] = true;
	}
	return $mceInit;
}
add_filter('tiny_mce_before_init', 'page_builder_widget_slider_tiny_mce_before_init', 10, 2);

/**
 * フロント出力
 */
function display_page_builder_widget_slider($values = array(), $widget_index = null) {
	// デフォルト値
	$default_values = apply_filters('page_builder_widget_slider_default_values', get_page_builder_widget_slider_default_values(), 'form');

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
?>
<div class="pb_slider_wrap">
  <div id="pb_slider-<?php echo esc_attr($widget_index); ?>" class="pb_slider">
<?php
	$is_first = true;
	foreach($repeater_indexes as $repeater_index) {
		$repeater_values = $values['repeater'][$repeater_index];

		$image = null;
		if (!empty($repeater_values['image'])) {
			$image = wp_get_attachment_image_src($repeater_values['image'], apply_filters('page_builder_slider_large_image_size', 'full'));
		}
		if (empty($image[0])) continue;

		echo '   <div class="pb_slider_item">';
		if (!empty($repeater_values['link'])) {
			echo '<a href="'.esc_attr($repeater_values['link']).'"';
			if (!empty($repeater_values['target_blank'])) {
				echo ' target="_blank"';
			}
			echo '>';
		}

		if ($is_first) {
			$is_first = false;
			echo '<img src="'.esc_attr($image[0]).'" alt="" />';
		} else {
			echo '<img data-lazy="'.esc_attr($image[0]).'" alt="" />';
		}

		if (!empty($repeater_values['link'])) {
			echo '</a>';
		}
		echo '</div>'."\n";
	}
?>
  </div>
<?php
	if ($values['footer_type'] == 'type1') {
?>
  <div id="pb_slider_nav-<?php echo esc_attr($widget_index); ?>" class="pb_slider_nav">
<?php
		foreach($repeater_indexes as $repeater_index) {
			$repeater_values = $values['repeater'][$repeater_index];

			$image = null;
			if (!empty($repeater_values['image'])) {
				$image = wp_get_attachment_image_src($repeater_values['image'], apply_filters('page_builder_slider_small_image_size', 'page_builder_slider_small'));
			}
			if (empty($image[0])) continue;

			echo '   <div class="pb_slider_nav_item">';
			echo '<img src="'.esc_attr($image[0]).'" alt="" />';
			echo '</div>'."\n";
		}
?>
  </div>
<?php
	} elseif ($values['footer_type'] == 'type2') {
?>
  <div id="pb_slider_caption-<?php echo esc_attr($widget_index); ?>" class="pb_slider_caption <?php echo !empty($values['show_caption_border']) ? 'pb_slider_caption-show_border' : 'pb_slider_caption-hide_border'; ?>">
<?php
		// ページビルダーのths_contentフィルターを削除
		remove_filter('the_content', 'page_builder_filter_the_content', 8);

		foreach($repeater_indexes as $repeater_index) {
			$repeater_values = $values['repeater'][$repeater_index];

			$image = null;
			if (!empty($repeater_values['image'])) {
				$image = wp_get_attachment_image_src($repeater_values['image'], apply_filters('page_builder_slider_large_image_size', 'full'));
			}
			if (empty($image[0])) continue;

			if (!empty($repeater_values['caption'])) {
				$repeater_values['caption'] = trim($repeater_values['caption']);
			}
			if (!empty($repeater_values['caption'])) {
				echo '   <div class="pb_slider_caption_item">'."\n";
				echo apply_filters('the_content', $repeater_values['caption']);
				echo '   </div>'."\n";
			} else {
				echo '   <div class="pb_slider_caption_item"></div>'."\n";
			}
		}

		// ページビルダーのths_contentフィルターを戻す
		add_filter('the_content', 'page_builder_filter_the_content', 8);
?>
  </div>
<?php
	}
?>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
  if (typeof $.fn.slick == 'undefined') return;

  $('#pb_slider-<?php echo esc_attr($widget_index); ?>').slick({
    infinite: false,
    dots: false,
    arrows: true,
    prevArrow: '<button type="button" class="slick-prev">&#xe90f;</button>',
    nextArrow: '<button type="button" class="slick-next">&#xe910;</button>',
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    autoplay: true,
    fade: true,
    lazyLoad: 'progressive',
    speed: 1000,
    autoplaySpeed: <?php echo floatval($values['slide_time']) * 1000; ?>,
    asNavFor: <?php
		if ($values['footer_type'] == 'type1') {
			echo "'#pb_slider_nav-".esc_attr($widget_index) . "'";
		} elseif ($values['footer_type'] == 'type2') {
			echo "'#pb_slider_caption-".esc_attr($widget_index) . "'";
		} else {
			echo 'null';
		}
?>

  });

<?php
	if ($values['footer_type'] == 'type1') {
?>
  $('#pb_slider_nav-<?php echo esc_attr($widget_index); ?>').slick({
    focusOnSelect: true,
    infinite: false,
    dots: false,
    arrows: false,
    slidesToShow: 7,
    slidesToScroll: 1,
    autoplay: false,
    speed: 1000,
    asNavFor: '#pb_slider-<?php echo esc_attr($widget_index); ?>'
  });
<?php
	} elseif ($values['footer_type'] == 'type2') {
?>
  $('#pb_slider_caption-<?php echo esc_attr($widget_index); ?>').slick({
    infinite: false,
    dots: false,
    arrows: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    autoplay: false,
    draggable: false,
    fade: true,
    speed: 1000,
    asNavFor: '#pb_slider-<?php echo esc_attr($widget_index); ?>'
  }).on('beforeChange', function(event, slick, currentSlide, nextSlide){
    if (slick.$slider.hasClass('pb_slider_caption-show_border')) {
      if (!slick.$slides.eq(nextSlide).text()) {
        slick.$slider.addClass('pb_slider_caption-transparent').stop().animate({opacity: 0}, 1000);
      } else if (slick.$slider.hasClass('pb_slider_caption-transparent')) {
        slick.$slider.removeClass('pb_slider_caption-transparent').stop().animate({opacity: 1}, 1000);
      }
    }
  });
<?php
	}
?>
});
</script>
<?php
}

/**
 * フロント用js・css
 */
function page_builder_widget_slider_sctipts() {
	page_builder_slick_enqueue_script();
}

function page_builder_widget_slider_styles() {
	page_builder_slick_enqueue_style();
	wp_enqueue_style('page_builder-slider', get_template_directory_uri().'/pagebuilder/assets/css/slider.css', false, PAGE_BUILDER_VERSION);
}

function page_builder_widget_slider_sctipts_styles() {
	if (is_singular() && is_page_builder() && page_builder_has_widget('pb-widget-slider')) {
		add_action('wp_enqueue_scripts', 'page_builder_widget_slider_sctipts', 11);
		add_action('wp_enqueue_scripts', 'page_builder_widget_slider_styles', 11);
		add_action('wp_head', 'pb_slider_script_header');
		add_action('page_builder_css', 'page_builder_widget_slider_css');
	}
}
add_action('wp', 'page_builder_widget_slider_sctipts_styles');

function pb_slider_script_header() {
?>
<script type="text/javascript">
jQuery(document).ready(function($){
  if (typeof $.fn.slick == 'undefined') return;

  setTimeout(function(){
    $('.pb_slider, .pb_slider_nav, .pb_slider_caption').slick('setPosition');
  }, 300);
  $(window).on('load', function(){
    $('.pb_slider, .pb_slider_nav, .pb_slider_caption').slick('setPosition');
  });
  $('.pb_slider img').on('load', function(){
    setTimeout(function(){
      $('.pb_slider, .pb_slider_nav, .pb_slider_caption').slick('setPosition');
    }, 50);
  });
});
</script>
<?php
}

function page_builder_widget_slider_css() {
	// 現記事で使用しているsliderコンテンツデータを取得
	$post_widgets = get_page_builder_post_widgets(get_the_ID(), 'pb-widget-slider');
	if ($post_widgets) {
		$css = array();
		$css_mobile = array();

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

			// arrow
			if (!empty($values['arrow_color'])) {
				$css[] = $post_widget['css_class'].' .pb_slider .slick-arrow { color: '.esc_attr($values['arrow_color']).'; background-color: '.esc_attr($values['arrow_background_color']).'; opacity: '.esc_attr($values['arrow_opacity']).'; }';
				$css[] = $post_widget['css_class'].' .pb_slider .slick-arrow:hover { color: '.esc_attr($values['arrow_color_hover']).'; background-color: '.esc_attr($values['arrow_background_color_hover']).'; opacity: '.esc_attr($values['arrow_opacity_hover']).'; }';
			}

			// caption
			if (!empty($values['footer_type']) && $values['footer_type'] == 'type2') {
				$css[] = $post_widget['css_class'].' .pb_slider_caption { padding: '.floatval($values['caption_padding']).'px; }';
				$css_mobile[] = $post_widget['css_class'].' .pb_slider_caption { padding: '.floatval($values['caption_padding_mobile']).'px; }';
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
