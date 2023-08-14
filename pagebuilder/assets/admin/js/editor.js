jQuery(document).ready(function($) {

	var $pb_metabox = $('#page_builder-metabox');
	if (!$pb_metabox.size()) return false;

	// ウィジェット追加モーダルのエディターありウィジェットクリック
	$('#pb-add-widget-modal .pb-select-widget a.pb-widget-editor, #pb-add-widget-modal .pb-select-widget a.pb-widget-has-editor').on('click', function(e){
		var $meta_wrap = $(this).closest('.postbox');
		var widget_index = $meta_wrap.find('.pb-rows-container').attr('data-widgets');
		var $widget = $meta_wrap.find('#widget-' + widget_index);
		var widget_id = $widget.attr('data-widget-id');

		// クローン元のリッチエディターをループ
		$meta_wrap.find('.pb-clone .'+widget_id+' .wp-editor-area').each(function(){
			var regexp = new RegExp('widgetindex_' + widget_id.replace(/-/g, '_'), 'g');

			// id
			var id_clone = $(this).attr('id');
			var id_new = id_clone.replace(regexp, widget_index);

			// クローン元のmceInitをコピー置換
			if (typeof tinyMCEPreInit.mceInit[id_clone] != 'undefined') {
				// オブジェクトを=で代入すると参照渡しになるため$.extendを利用
				var mce_init_new = $.extend(true, {}, tinyMCEPreInit.mceInit[id_clone]);
				mce_init_new.body_class = mce_init_new.body_class.replace(regexp, widget_index);
				mce_init_new.selector = mce_init_new.selector.replace(regexp, widget_index);
				tinyMCEPreInit.mceInit[id_new] = mce_init_new;

				// リッチエディター化
				tinymce.init(mce_init_new);
			}

			// クローン元のqtInitをコピー置換
			if (typeof tinyMCEPreInit.qtInit[id_clone] != 'undefined') {
				// オブジェクトを=で代入すると参照渡しになるため$.extendを利用
				var qt_init_new = $.extend(true, {}, tinyMCEPreInit.qtInit[id_clone]);
				qt_init_new.id = qt_init_new.id.replace(regexp, widget_index);
				tinyMCEPreInit.qtInit[id_new] = qt_init_new;

				// テキスト入力のタグボタン有効化
				quicktags(tinyMCEPreInit.qtInit[id_new]);
				try {
					if (QTags.instances['0'].theButtons) {
						QTags.instances[id_new].theButtons = QTags.instances['0'].theButtons;
					}
				} catch(err) {
				}
			}

			// ビジュアルボタンがあればビジュアル状態に
			if ($widget.find('.wp-editor-tabs .switch-tmce').length) {
				$widget.find('.wp-editor-wrap').removeClass('html-active').addClass('tmce-active');
			}
		});
	});

	// ウィジェット複製クリック （モーダル内複製も共通）
	$pb_metabox.on('click', '.pb-rows-container .pb-widget.pb-widget-editor .pb-widget-clone, .pb-rows-container .pb-widget.pb-widget-has-editor .pb-widget-clone', function(e){
		// 複製元
		var $source_widget = $(this).closest('.pb-widget');
		var source_widget_index = $source_widget.attr('data-widget-index');
		var widget_id = $source_widget.attr('data-widget-id');

		// 複製先
		var $rows = $(this).closest('.postbox').find('.pb-rows-container');
		var widget_index = $rows.attr('data-widgets') || 0;
		if (!widget_index) return;
		var $widget = $('#widget-' + widget_index);
		if (!$widget.length) return;

		var regexp = new RegExp('widgetindex_' + widget_id.replace(/-/g, '_'), 'g');

		// クローン元のリッチエディターをループ（複製元ではなくページビルダーのクローン元）
		// 複製先のエディターをクローンしなおす
		var $meta_wrap = $(this).closest('.postbox');
		$meta_wrap.find('.pb-clone .' + widget_id + ' .wp-editor-area').each(function(i){

			// id
			var id_clone = $(this).attr('id');
			var id_new = id_clone.replace(regexp, widget_index);

			// 複製先の現エディター
			var $current_editor_wrap = $widget.find('.wp-editor-wrap').eq(i);
			if (!$current_editor_wrap.length) return;

			// 現エディターの入力値
			var current_editor_val = $current_editor_wrap.find('.wp-editor-area').val();

			// クローンするエディターHTML
			var clone_editor_html = $(this).closest('.wp-editor-wrap').prop('outerHTML');

			// ウィジェットID置換してクローンエディターHTMLを挿入
			$current_editor_wrap.after(clone_editor_html.replace(regexp, widget_index));

			// 現エディター削除
			$current_editor_wrap.remove();

			// 挿入したクローンエディター
			var $new_editor_wrap = $widget.find('.wp-editor-wrap').eq(i);

			// テキストエリアに値代入
			$new_editor_wrap.find('.wp-editor-area').val(current_editor_val);

			// クローン元のmceInitをコピー置換
			if (typeof tinyMCEPreInit.mceInit[id_clone] != 'undefined') {
				// オブジェクトを=で代入すると参照渡しになるため$.extendを利用
				var mce_init_new = $.extend(true, {}, tinyMCEPreInit.mceInit[id_clone]);
				mce_init_new.body_class = mce_init_new.body_class.replace(regexp, widget_index);
				mce_init_new.selector = mce_init_new.selector.replace(regexp, widget_index);
				tinyMCEPreInit.mceInit[id_new] = mce_init_new;

				// リッチエディター化
				tinymce.init(mce_init_new);
			}

			// クローン元のqtInitをコピー置換
			if (typeof tinyMCEPreInit.qtInit[id_clone] != 'undefined') {
				// オブジェクトを=で代入すると参照渡しになるため$.extendを利用
				var qt_init_new = $.extend(true, {}, tinyMCEPreInit.qtInit[id_clone]);
				qt_init_new.id = qt_init_new.id.replace(regexp, widget_index);
				tinyMCEPreInit.qtInit[id_new] = qt_init_new;

				// テキスト入力のタグボタン有効化
				quicktags(tinyMCEPreInit.qtInit[id_new]);
				try {
					if (QTags.instances['0'].theButtons) {
						QTags.instances[id_new].theButtons = QTags.instances['0'].theButtons;
					}
				} catch(err) {
				}
			}

			// ビジュアル/テキストを複製元に合わせる
			setTimeout(function(){
				if ($source_widget.find('.wp-editor-wrap').eq(i).hasClass('tmce-active')) {
					switchEditors.go(id_new, 'toggle');
					switchEditors.go(id_new, 'tmce');
				} else {
					switchEditors.go(id_new, 'html');
				}
			}, 500);
		});
	});

	// リッチエディターの異なるセルへのドラッグ対策
	var cell_id_before, cell_id_after;
	$(document).on('page-builder-widget-sortable-start', function(e, item) {
		if (!$(item).hasClass('pb-widget-editor') && !$(item).hasClass('pb-widget-has-editor')) return;

		cell_id_before = $(item).closest('.cell').attr('id') || null;
	});
	$(document).on('page-builder-widget-sortable-stop', function(e, item) {
		if (!$(item).hasClass('pb-widget-editor') && !$(item).hasClass('pb-widget-has-editor')) return;

		cell_id_after = $(item).closest('.cell').attr('id') || null;
		if (!cell_id_before || cell_id_before == cell_id_after) return;

		var $this = $(this);

		$(item).find('.wp-editor-area').each(function(){
			var id = $(this).attr('id');
			var $editor_wrap = $(this).closest('.wp-editor-wrap');

			if (!id) return;

			if (window.tinymce) {
				var mceInstance = window.tinymce.get(id);
				if (mceInstance) {
					mceInstance.remove();
					tinymce.init(id);
				}
			}

			if (window.quicktags) {
				var qtInstance = window.QTags.getInstance(id);
				if (qtInstance) {
					qtInstance.remove();
					quicktags(tinyMCEPreInit.qtInit[id]);
				}
			}

			setTimeout(function(){
				if ($editor_wrap.hasClass('tmce-active')) {
					switchEditors.go(id, 'toggle');
					switchEditors.go(id, 'tmce');
				} else {
					switchEditors.go(id, 'html');
				}
			}, 500);
		});
	});

});
