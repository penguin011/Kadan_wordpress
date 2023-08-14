jQuery(document).ready(function($){

	var $pb_metabox = $('#page_builder-metabox');
	if (!$pb_metabox.size()) return false;

	// Gutenberg editorフラグ
	var is_gutenberg_editor = false;

	// WordPress Color Picker
	// 旧jscolorの共存仕様で.pb-color*は使えないので注意
	$pb_metabox.find('.pb-row-container .pb-wp-color-picker, #pb-add-row-modal .pb-wp-color-picker').wpColorPicker();

	// 汎用トグル
	$pb_metabox.find('[data-pb-toggle-target]').each(function(){
		var status = $(this).attr('data-pb-toggle-status');
		if (status == 'close' || status == 'hide' || status == 'hidden') {
			$(this).attr('data-pb-toggle-status', 'close');
			var target = $(this).attr('data-pb-toggle-target');
			$pb_metabox.find(target).hide();
		} else {
			$(this).attr('data-pb-toggle-status', 'open');
		}
	});
	$pb_metabox.on('click', '.pb-modal [data-pb-toggle-target]', function(e){
		var $this = $(this);
		var $target = $(this).closest('.pb-modal').find($($(this).attr('data-pb-toggle-target')));

		if ($target.length) {
			if ($target.is(':visible')) {
				$this.attr('data-pb-toggle-status', 'closing');
				$target.slideUp(500, function(){
					$this.attr('data-pb-toggle-status', 'close');
				});
			} else {
				var accordion = $(this).attr('data-pb-toggle-accordion') || '';
				accordion = (accordion.toLowerCase() === 'true' || Number(accordion)) ;
				if (accordion) {
					var $siblings_handlar = $this.siblings('[data-pb-toggle-target][data-pb-toggle-status="open"]').attr('data-pb-toggle-status', 'closing');
					$target.siblings('.pb-toggle-content:visible').slideUp(500, function(){
						$siblings_handlar.attr('data-pb-toggle-status', 'close');
					});
				}

				$this.attr('data-pb-toggle-status', 'open');
				$target.slideDown(400);
			}
		}

		return false;
	});

	// ツールバー追従
	var toolbar_vars = { status: '' };
	var toolbar_init = function(){
		var $tb = $pb_metabox.find('.inside > .pb-toolbar');
		$tb.removeAttr('style');
		var offset = $tb.offset();
		toolbar_vars.height = $tb.innerHeight();
		toolbar_vars.width = $tb.innerWidth();
		toolbar_vars.top = Math.ceil(offset.top);
		toolbar_vars.left = Math.ceil(offset.left);
		toolbar_vars.window_height = window.innerHeight ? window.innerHeight : $(window).height();
		toolbar_vars.status = '';
		toolbar_vars.timer = 0;
		toolbar_vars.wp_toolbar_height = 0;
		if ($('#wpadminbar').css('position') == 'fixed') {
			toolbar_vars.wp_toolbar_height += $('#wpadminbar').height();
		}
		if (is_gutenberg_editor) {
			toolbar_vars.scroll_parent = $('.edit-post-layout__content').get(0);
			toolbar_vars.$scroll_parent = $('.edit-post-layout__content');
			toolbar_vars.wp_toolbar_height += $('.edit-post-header').innerHeight();
		}
		toolbar_scroll()
	};
	var toolbar_scroll = function(){
		var $tb = $pb_metabox.find('.inside > .pb-toolbar');
		var start = toolbar_vars.top;
		var end_abs = toolbar_vars.top + $pb_metabox.innerHeight() - toolbar_vars.height;
		var end_fixed = end_abs - toolbar_vars.window_height * 0.25;

		if (is_gutenberg_editor) {
			var scrollTop = toolbar_vars.scroll_parent.scrollTop || toolbar_vars.$scroll_parent.scrollTop();
		} else {
			var scrollTop = window.scrollY || document.documentElement.scrollTop;
		}

		if (scrollTop > start && scrollTop < end_fixed) {
			if (toolbar_vars.status != 'fixed') {
				toolbar_vars.status = 'fixed';
				$tb.parent().css({
					position: 'relative',
					paddingTop: toolbar_vars.height
				});
				$tb.css({
					width: toolbar_vars.width,
					position: 'fixed',
					top: toolbar_vars.wp_toolbar_height,
					left: toolbar_vars.left,
					zIndex: 2
				});
			}
		} else if (scrollTop > start && scrollTop >= end_fixed && scrollTop < end_abs) {
			if (toolbar_vars.status != 'absolute') {
				toolbar_vars.status = 'absolute';
				$tb.parent().css({
					position: 'relative',
					paddingTop: toolbar_vars.height
				});
				$tb.css({
					width: toolbar_vars.width,
					position: 'absolute',
					top: scrollTop - start + toolbar_vars.wp_toolbar_height,
					left: 0,
					zIndex: 2
				});
			}
		} else if (scrollTop <= start) {
			toolbar_vars.status = '';
			$tb.removeAttr('style');
			$tb.parent().removeAttr('style');
		}
	};
	var toolbar_scroll_on = function(){
		toolbar_init();
		$(window).on('resize', toolbar_init);
		if (is_gutenberg_editor) {
			clearTimeout(toolbar_vars.timer);
			toolbar_vars.timer = setTimeout(function(){
				$('.edit-post-layout__content').on('scroll', toolbar_scroll);
			}, 100);
		} else {
			clearTimeout(toolbar_vars.timer);
			toolbar_vars.timer = setTimeout(function(){
				$(window).on('scroll', toolbar_scroll);
			}, 100);
		}
	};
	var toolbar_scroll_off = function(){
		toolbar_vars.status = '';
		$(window).off('resize', toolbar_init);
		if (is_gutenberg_editor) {
			$('.edit-post-layout__content').off('scroll', toolbar_scroll);
		} else {
			$(window).off('scroll', toolbar_scroll);
		}
	};

	// モーダル表示中にモーダル外スクロール禁止
	var current_scroll_x, current_scroll_y
	var window_scroll_disable = function() {
		// モーダル表示中クラス追加
		if (is_gutenberg_editor) {
			$('body').addClass('pagebuilder-modal-active');
		}

		current_scroll_y = $(window).scrollTop();
		current_scroll_x = $(window).scrollLeft();
		$(window).off('scroll.window_scroll_disable');
		$(window).on('scroll.window_scroll_disable', function(){
			if ($(window).scrollTop() != current_scroll_y) {
				$(window).scrollTop(current_scroll_y);
			}
			if ($(window).scrollLeft() != current_scroll_x) {
				$(window).scrollLeft(current_scroll_x);
			}
		});
	};

	// モーダル外スクロール許可
	var window_scroll_enable = function() {
		// モーダル表示中クラス削除
		if (is_gutenberg_editor) {
			$('body').removeClass('pagebuilder-modal-active');
		}

		$(window).off('scroll.window_scroll_disable');
	};

	// 行内のカラムの高さを合わせる
	var fit_cell_height = function(){
		$('.pb-rows-container .pb-row-container').each(function(){
			$(this).find('.pb-cells .cell-wrapper').css('min-height', 0);
			var height = 0;
			$(this).find('.pb-cells .cell').each(function(){
				height = Math.max(height, $(this).height());
			});
			$(this).find('.pb-cells .cell-wrapper').css('min-height', Math.max(height, 70));
		});
	};
	$(window).resize(fit_cell_height);

	// Block editor(Gutenberg)対応
	if ($('.block-editor__container').length) {
		is_gutenberg_editor = true;
		$pb_metabox.addClass('attached-to-editor attached-to-block-editor');

		// 現在のビジュアルエディターorコードエディター
		var current_editor;

		// ヘッダー右の詳細ボタンクリック
		$('#editor').on('click', '.edit-post-more-menu button:first', function(){
			setTimeout(function(){
				// ポップメニューが開いてなければ終了
				if (!$('#editor .edit-post-more-menu__content').length) return;

				var $code_editor_select_button, $current_editor_button, $pb_select_button, $select_button_parent;
				var pb_select_button_label = pagebuilder_i18n.page_builder || 'Page Builder';

				// コードエディターボタン取得
				if (pagebuilder_i18n.code_editor && $('.edit-post-more-menu__content button:contains("'+pagebuilder_i18n.code_editor+'")').length) {
					$code_editor_select_button = $('.edit-post-more-menu__content button:contains("'+pagebuilder_i18n.code_editor+'")');
				} else if ($('.edit-post-more-menu__content button:contains("コードエディター")').length) {
					$code_editor_select_button = $('.edit-post-more-menu__content button:contains("コードエディター")');
				} else if ($('.edit-post-more-menu__content button:contains("Code Editor")').length) {
					$code_editor_select_button = $('.edit-post-more-menu__content button:contains("Code Editor")');
				}
				if (!$code_editor_select_button) return;

				// コードエディターボタンの親
				$select_button_parent = $code_editor_select_button.parent();

				// チェックありの既存エディター取得
				$current_editor_button = $select_button_parent.find('.has-icon, .components-icon-button');

				// チェックありの既存エディターの値を変数current_editorにセット
				switch ($current_editor_button.text()) {
					case pagebuilder_i18n.visual_editor :
					case 'Visual Editor' :
					case 'ビジュアルエディター' :
						current_editor = 'visual';
						break;

					case pagebuilder_i18n.code_editor :
					case 'Code Editor' :
					case 'コードエディター' :
						current_editor = 'text';
						break;
				}

				// ページビルダーボタン生成
				// ページビルダー有効時
				if ($pb_metabox.find('[name="use_page_builder"]').val() == 1) {
					// チェックあり
					$pb_select_button = $('<button type="button" aria-checked="true" role="menuitemradio" class="components-button components-icon-button components-menu-item__button has-icon"><svg aria-hidden="true" role="img" focusable="false" class="dashicon dashicons-yes" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M14.83 4.89l1.34.94-5.81 8.38H9.02L5.78 9.67l1.34-1.25 2.57 2.4z"></path></svg>' + pb_select_button_label + '</button>');

					// チェックありの既存エディターのチェックを外す
					$current_editor_button.removeClass('has-icon components-icon-button').find('svg, .dashicon').remove();

					// 既存エディターのショートカットを削除
					$select_button_parent.find('.components-menu-item__shortcut').remove();
				} else {
					$pb_select_button = $('<button type="button" aria-checked="false" role="menuitemradio" class="components-button components-menu-item__button">' + pb_select_button_label + '</button>');
				}

				// コードエディターの下にページビルダーボタン追加
				$select_button_parent.append($pb_select_button);
			}, 20);
		});

		// エディター選択ボタンクリック
		$('#editor').on('click', '.edit-post-more-menu__content button', function(){

			switch ($(this).text()) {
				case pagebuilder_i18n.page_builder :
				case 'Page Builder' :
				case 'ページビルダー' :
					if ($pb_metabox.find('[name="use_page_builder"]').val() == 1) {
						gutenbergHidePageBuilder();
					} else {
						gutenbergShowPageBuilder();
					}

					// 手動でポップアップ閉じる
					$('#editor .edit-post-more-menu button:first').trigger('click');

					// エディター変更処理は行わないのでreturn false;
					return false;
					break;

				case pagebuilder_i18n.visual_editor :
				case 'Visual Editor' :
				case 'ビジュアルエディター' :
					// ページビルダーからエディター切替
					if ($pb_metabox.find('[name="use_page_builder"]').val() == 1) {
						gutenbergHidePageBuilder();
					}

					// 保存されているエディターと一致する場合にポップアップが閉じないので手動でポップアップ閉じる
					if (current_editor == 'visual') {
						$('#editor .edit-post-more-menu button:first').trigger('click');
					}
					break;

				case pagebuilder_i18n.code_editor :
				case 'Code Editor' :
				case 'コードエディター' :
					// ページビルダーからエディター切替
					if ($pb_metabox.find('[name="use_page_builder"]').val() == 1) {
						gutenbergHidePageBuilder();
					}

					// 保存されているエディターと一致する場合にポップアップが閉じないので手動でポップアップ閉じる
					if (current_editor == 'text') {
						$('#editor .edit-post-more-menu button:first').trigger('click');
					}
					break;
			}
		});

		// ページビルダー表示処理
		var gutenbergShowPageBuilder = function() {
			$('#editor').addClass('pagebuilder-active');

			// ページビルダーフラグをon
			$pb_metabox.find('[name="use_page_builder"]').val(1);

			// 行内のカラムの高さを合わせる
			fit_cell_height();

			// ツールバー追従
			toolbar_scroll_on();
		}

		// ページビルダー非表示処理
		var gutenbergHidePageBuilder = function() {
			$('#editor').removeClass('pagebuilder-active');

			// ページビルダーフラグをoff
			$pb_metabox.find('[name="use_page_builder"]').val(0);

			// ツールバー追従無効
			toolbar_scroll_off();

			// WPエディター用にresize実行
			$(window).resize();
		};

		// 通常エディターに戻すクリック
		$pb_metabox.find('.pb-switch-to-standard').on('click', function() {
			gutenbergHidePageBuilder();
			return false;
		});

		// Gutenberg editorの初期化を待つ必要あり
		setTimeout(function(){
			// ページビルダーフラグがonならページビルダー表示
			if ($pb_metabox.find('[name="use_page_builder"]').val() == 1) {
				gutenbergShowPageBuilder();
			}
		}, 1000);

	// 旧エディター
	} else {
		// wp-editor-tabsにpage builder追加
		$('#wp-content-wrap .wp-editor-tabs').append('<span id="content-pagebuilder" class="hide-if-no-js wp-switch-editor switch-pagebuilder">' + $pb_metabox.find('.hndle span').html() + '</span>');

		// wp-editor-tabsのpage builderクリック
		$('#wp-content-wrap .wp-editor-tabs #content-pagebuilder').on('click', function(){
			$('#post').addClass('pagebuilder-active');
			$pb_metabox.show().find('> .inside').show();

			// ページビルダーフラグをon
			$pb_metabox.find('[name="use_page_builder"]').val(1);

			// 行内のカラムの高さを合わせる
			fit_cell_height();

			// ツールバー追従
			toolbar_scroll_on();

			return false;
		});

		// 通常エディターに戻す
		$pb_metabox.find('.pb-switch-to-standard').on('click', function() {
			$('#post').removeClass('pagebuilder-active');
			$pb_metabox.hide();

			// ページビルダーフラグをoff
			$pb_metabox.find('[name="use_page_builder"]').val(0);

			// ツールバー追従無効
			toolbar_scroll_off();

			// WPエディター用にresize実行
			$(window).resize();

			return false;
		});

		// メタボックスをエディターの後ろに移動
		$pb_metabox.insertAfter('#wp-content-wrap').hide().addClass('attached-to-editor');

		// ページビルダーフラグがonならページビルダー表示
		if ($pb_metabox.find('[name="use_page_builder"]').val() == 1) {
			$('#post').addClass('pagebuilder-active');
			$pb_metabox.show().find('> .inside').show();

			// 行内のカラムの高さを合わせる
			fit_cell_height();

			// ツールバー追従
			toolbar_scroll_on();
		}
	}

	// 行ソータブル
	$('.pb-rows-container').sortable({
		items: '.pb-row-container',
		handle: '.pb-row-move',
		axis: 'y',
		tolerance: 'pointer',
		scroll: false
	});

	// カラムクリック
	$pb_metabox.on('click', '.cell-wrapper', function(e){
		$(this).closest('.pb-rows-container').find('.pb-cells .cell.cell-selected').removeClass('cell-selected');
		$(this).closest('.cell').addClass('cell-selected');
	});

	// モーダルキャンセル用に現在値を属性にセット
	var setInputValueToAttr = function(el) {
		$(el).find(':input').not(':button, :submit').each(function(){
			if ($(this).is('select')) {
				$(this).attr('data-value', $(this).val());
				$(this).find('[value="' + $(this).val() + '"]').attr('selected', 'selected');
			} else if ($(this).is(':radio, :checkbox')) {
				if ($(this).is(':checked')) {
					$(this).attr('data-checked', 1);
				} else {
					$(this).removeAttr('data-checked');
				}
			} else {
				$(this).attr('data-value', $(this).val());
			}
		});
	};

	// モーダルキャンセル用に属性値から値をセット
	var resetInputValueFromAttr = function(el) {
		$(el).find(':input').not(':button, :submit').each(function(){
			if ($(this).is('.wp-color-picker')) {
				if ($(this).attr('data-value')) {
					$(this).wpColorPicker('color', $(this).attr('data-value'));
				} else if ($(this).attr('data-default-color')) {
					$(this).wpColorPicker('color', $(this).attr('data-default-color'));
				} else {
					$(this).wpColorPicker('color', '#ffffff');
				}
			} else if ($(this).is('select')) {
				$(this).find('option').removeAttr('selected');
				if ($(this).attr('data-value')) {
					$(this).find('[value="' + $(this).attr('data-value') + '"]').attr('selected', 'selected');
					$(this).removeAttr('data-value');
				}
			} else if ($(this).is(':radio, :checkbox')) {
				if ($(this).attr('data-checked')) {
					$(this).attr('checked', 'checked');
					$(this).removeAttr('data-checked');
				} else {
					$(this).removeAttr('checked');
					$(this).removeAttr('data-checked');
				}
			} else {
				$(this).val($(this).attr('data-value'));
				$(this).removeAttr('data-value');
			}
		});
	};

	// デフォルトモーダルクリック処理
	$pb_metabox.on('click', '.pb-modal .pb-apply', function(e){
		e.preventDefault();
		e.stopPropagation();
		$(this).closest('.pb-modal').hide();
		window_scroll_enable();
	});
	$pb_metabox.on('click', '.pb-modal .pb-close', function(e){
		e.preventDefault();
		e.stopPropagation();
		$(this).closest('.pb-modal').hide();
		window_scroll_enable();
	});
	$pb_metabox.on('click', '.pb-modal .pb-delete', function(e){
		e.preventDefault();
		e.stopPropagation();
		if ($(this).attr('data-confirm')) {
			if (!confirm($(this).attr('data-confirm'))) {
				$(this).attr('data-confirm-cancel', 1);
				return false;
			}
		}
		$(this).removeAttr('data-confirm-cancel');
		$(this).closest('.pb-modal').hide();
		window_scroll_enable();
	});

	// 行追加ボタン
	$('.pb-add-row').on('click', function(){
		$('.pb-modal:visible').hide();
		$('#pb-add-row-modal').show();
		$('#pb-add-row-modal .pb-delete').hide();
		setInputValueToAttr('#pb-add-row-modal');
		$('#pb-add-row-modal select.cells').trigger('change');
		window_scroll_disable();
	});

	// 行追加・編集モーダル カラムプレビュー
	var generateRowPreview = function($pb_modal, column_num, columns) {
		var rowPreview = $pb_modal.find('.row-preview');
		rowPreview.html('');

		column_num = parseInt(column_num, 10);
		if (column_num < 1) return false;

		if (column_num == 1) {
			$pb_modal.find('.hide-if-one-column').hide();
		} else {
			$pb_modal.find('.hide-if-one-column').show();
		}

		// 小数点6ケタの割合 パーセントではないので注意
		var column_width = Math.floor(100 / column_num * 10000) / 1000000;

		if (columns && typeof columns == 'object') {
			for(var i = 0; i < column_num; i++) {
				columns[i] = parseFloat(columns[i])
			}
		} else {
			columns = [];
			for(var i = 1; i <= column_num; i++) {
				columns.push(column_width)
			}
		}
		$pb_modal.find('.cells_width').val(columns.join(','));

		$.each(columns, function(i, value) {
			var newCell = $([
				'<div class="preview-cell" data-width="' + value + '" style="width:' + value * 100 + '%">',
				'<div class="preview-cell-in">',
				'<div class="preview-cell-weight">' + Math.floor(value * 1000) / 10
 + '</div>',
				'</div>',
				'</div>'
			].join(''));

			rowPreview.append(newCell);

			var prevCell = newCell.prev();
			var handle;

			// ドラッガブル
			if (prevCell.length) {
				var handle = $('<div class="resize-handle"></div>');
				handle.appendTo(newCell);
				handle.draggable({
					axis: 'x',
					containment: rowPreview,
					start: function (e, ui) {
						// Create the clone for the current cell
						var newCellClone = newCell.clone().appendTo(ui.helper).css({
							position: 'absolute',
							top: '0',
							width: newCell.outerWidth(),
							left: 6,
							height: newCell.outerHeight()
						});
						newCellClone.find('.resize-handle').remove();

						// Create the clone for the previous cell
						var prevCellClone = prevCell.clone().appendTo(ui.helper).css({
							position: 'absolute',
							top: '0',
							width: prevCell.outerWidth(),
							right: 6,
							height: prevCell.outerHeight()
						});
						prevCellClone.find('.resize-handle').remove();

						$(this).data({
							'newCellClone': newCellClone,
							'prevCellClone': prevCellClone
						});

						// Hide the
						newCell.find('> .preview-cell-in').css('visibility', 'hidden');
						prevCell.find('> .preview-cell-in').css('visibility', 'hidden');
					},
					drag: function (e, ui) {
						// Calculate the new cell and previous cell widths as a percent
						var ncw = columns[i] - ((ui.position.left + 6) / rowPreview.width());
						var pcw = columns[i - 1] + ((ui.position.left + 6) / rowPreview.width());

						var helperLeft = ui.helper.offset().left - rowPreview.offset().left - 6;

						$(this).data('newCellClone').css('width', rowPreview.width() * ncw)
							.find('.preview-cell-weight').html(Math.floor(ncw * 1000) / 10);

						$(this).data('prevCellClone').css('width', rowPreview.width() * pcw)
							.find('.preview-cell-weight').html(Math.floor(pcw * 1000) / 10);
					},
					stop: function (e, ui) {
						// Remove the clones
						$(this).data('newCellClone').remove();
						$(this).data('prevCellClone').remove();

						// Reshow the main cells
						newCell.find('.preview-cell-in').css('visibility', 'visible');
						prevCell.find('.preview-cell-in').css('visibility', 'visible');

						// Calculate the new cell weights
						var offset = ui.position.left + 6;
						var percent = offset / rowPreview.width()

						// Ignore this if any of the cells are below 2% in width.
						if (columns[i] - percent > 0.02 && columns[i - 1] + percent > 0.02) {
							columns[i] = Math.floor((columns[i] - percent) * 1000000) / 1000000;
							columns[i - 1] = Math.floor((columns[i - 1] + percent) * 1000000) / 1000000;

							rowPreview.find('.preview-cell').eq(i).css('width', columns[i] * 100 + '%').find('.preview-cell-weight').html(Math.floor(columns[i] * 1000) / 10);
							rowPreview.find('.preview-cell').eq(i - 1).css('width', columns[i - 1] * 100 + '%').find('.preview-cell-weight').html(Math.floor(columns[i - 1] * 1000) / 10);
							$pb_modal.find('.cells_width').val(columns.join(','));
						}

						ui.helper.css('left', - 6);
					}
				});
			}
		});
	};

	// モーダル内セレクト変更時のクローン対策
	$('.pb-modal select').on('change', function(e){
		var v = $(this).val();
		$(this).find('option').removeAttr('selected');
		$(this).find('[value="' + v + '"]').attr('selected', 'selected');
	});

	// 行追加・編集 モーダル カラム数変更
	$pb_metabox.on('change', '.pb-modal-row-edit select.cells', function(e){
		generateRowPreview($(this).closest('.pb-modal'), this.value);
	});

	// 行追加モーダル 追加ボタン
	$('#pb-add-row-modal .pb-apply').on('click', function(e){
		var $rows = $(this).closest('.postbox').find('.pb-rows-container');
		var row_index = $rows.attr('data-rows') || 0;
		row_index++;
		$rows.attr('data-rows', row_index);

		var $row = $('#clonerow').clone();
		$row.attr('id', 'row-' + row_index);

		var $row_modal_clone = $('#pb-add-row-modal').clone().hide();
		$row_modal_clone.attr('id', 'row-modal-' + row_index);
		if ($row_modal_clone.attr('data-edit-title')) {
			$row_modal_clone.find('.pb-title').text($row_modal_clone.attr('data-edit-title'));
		}
		if ($row_modal_clone.attr('data-edit-button')) {
			$row_modal_clone.find('.button-primary').val($row_modal_clone.attr('data-edit-button'));
		}
		$row.append($row_modal_clone);

		resetInputValueFromAttr('#pb-add-row-modal');

		$row_modal_clone.find(':input').not(':button, :submit').each(function(){
			// WordPress Color Picker 解除
			if ($(this).is('.wp-color-picker')) {
				var $pickercontainer = $(this).closest('.wp-picker-container');
				var $clone = $(this).clone().attr('value', $(this).val()).attr('data-value', $(this).val());
				$pickercontainer.after($clone).remove();
			} else if ($(this).is('select')) {
				var v = $(this).val();
				$(this).find('option').removeAttr('selected');
				$(this).find('[value="' + v + '"]').attr('selected', 'selected');
				$(this).attr('data-value', v);
			} else if ($(this).is(':radio, :checkbox')) {
				if ($(this).is(':checked')) {
					$(this).attr('checked', 'checked');
					$(this).attr('data-checked', 1);
				} else {
					$(this).removeAttr('checked');
					$(this).removeAttr('data-checked');
				}
			} else {
				var v = $(this).val();
				$(this).attr('value', v);
				$(this).attr('data-value', v);
			}
		});

		var cells_width = $row_modal_clone.find('.cells_width').val().split(',');

		$.each(cells_width, function(i, value){
			var $cell_clone = $('#clonecell').clone();
			$cell_clone.attr('id', 'cell-' + row_index + '-' + (i + 1)).css('width', value * 100 + '%');
			$row.find('.pb-cells').append($cell_clone);
		});

		$rows.append($row.prop('outerHTML').replace(/rowindex/g, row_index));

		// WordPress Color Picker 再セット
		$('#row-' + row_index + ' .pb-wp-color-picker').wpColorPicker();

		// ウィジェットソータブル
		widget_sortable_init();
	});

	// 行編集ボタン
	$pb_metabox.on('click', '.pb-rows-container .pb-row-container .pb-row-edit', function(e){
		e.preventDefault();
		$('.pb-modal:visible').hide();
		var $pb_modal = $(this).closest('.pb-row-container').find('.pb-modal-row-edit');
		$pb_modal.show();
		$pb_modal.find('.pb-delete').show();
		setInputValueToAttr($pb_modal);
		generateRowPreview($pb_modal, $pb_modal.find('.cells').val(), $pb_modal.find('.cells_width').val().split(','));
		window_scroll_disable();
	});

	// 行編集モーダル 閉じるボタン キャンセル扱い
	$pb_metabox.on('click', '.pb-modal-row-edit .pb-close', function(e){
		resetInputValueFromAttr($(this).closest('.pb-modal'));
	});

	// 行編集モーダル 編集ボタン
	$pb_metabox.on('click', '.pb-modal-row-edit .pb-apply', function(e){
		// 行追加モーダルの場合は終了
		if ($(this).closest('.pb-modal-row-edit').is('#pb-add-row-modal')) return;

		var $row = $(this).closest('.pb-row-container');
		var row_index = $row.attr('id').replace('row-', '');
		var cells_width = $row.find('.cells_width').val().split(',');
		var new_cells_count = cells_width.length;
		var old_cells_count = $row.find('.pb-cells .cell').size();

		// セル削除
		if (old_cells_count > new_cells_count) {
			$row.find('.pb-cells .cell').each(function(i){
				if (i >= new_cells_count) {
					if ($(this).find('.pb-widget').size()) {
						$(this).find('.pb-widget').appendTo($row.find('.pb-cells .cell .widgets-container').eq(new_cells_count - 1))
					}
					$(this).remove();
				}
			});
		}

		$.each(cells_width, function(i, value){
			var $cell;
			if (i >= old_cells_count) {
				$cell = $('#clonecell').clone().attr('id', 'cell-' + row_index + '-' + (i + 1));
				$row.find('.pb-cells').append($cell);
			} else {
				$cell = $row.find('.pb-cells .cell').eq(i);
			}
			$cell.css('width', value * 100 + '%');
		});

		$('.pb-modal:visible').hide();

		// ウィジェットソータブルリフレッシュ
		widget_sortable_init();

		// 行内のカラムの高さを合わせる
		fit_cell_height();

		// ウィジェットの並び順を更新
		widget_sort_collections();
	});

	// 行削除ボタン
	$pb_metabox.on('click', '.pb-rows-container .pb-row-container .pb-row-delete', function(e){
		e.preventDefault();
		if ($(this).attr('data-confirm')) {
			if (!confirm($(this).attr('data-confirm'))) {
				return false;
			}
		}
		$(this).closest('.pb-row-container').fadeOut('fast', function(){
			$(this).remove();
		});
	});

	// モーダル内の行削除
	$pb_metabox.on('click', '.pb-rows-container .pb-row-container .pb-modal-row-edit .pb-delete', function(e){
		if ($(this).attr('data-confirm-cancel')) return false;
		$(this).closest('.pb-row-container').fadeOut('fast', function(){
			$(this).remove();
		});
	});

	// ウィジェット編集
	$pb_metabox.on('click', '.pb-widget-wrapper', function(e){
		e.preventDefault();
		e.stopPropagation();
		$('.pb-modal:visible').hide();
		var $pb_modal = $(this).closest('.pb-widget').find('.pb-modal');
		$pb_modal.show();

		// モーダル外スクロール禁止
		window_scroll_disable();
	});

	// ウィジェット削除
	$pb_metabox.on('click', '.pb-widget .widget-delete', function(e){
		e.preventDefault();
		e.stopPropagation();
		if ($(this).attr('data-confirm')) {
			if (!confirm($(this).attr('data-confirm'))) {
				return false;
			}
		}
		$('.pb-modal:visible').hide();
		window_scroll_enable();
		$(this).closest('.pb-widget').fadeOut('fast', function(){
			$(this).remove();
			fit_cell_height();
			widget_sort_collections();
		});
		return false;
	});

	// テキスト概要を表示
	var updateWidgetOverview = function(el) {
		var overview = '';
		var $inputs = $(el).find('.pb-modal-edit-widget .pb-content .pb-input-overview:input');
		if (!$inputs.length) {
			$inputs = $(el).find('.pb-modal-edit-widget .pb-content :input').filter(':text, textarea');
		}
		$inputs.each(function(){
			if (this.name.match(/(url|link|color|size|opacity)/i)) return;
			if (this.value) {
				overview = this.value;
				return false;
			}
		});
		if (overview) {
			overview = overview.replace(/\s+/gm, ' ').replace(/<.*?>/gm, '');
		}
		if (overview.length > 100) {
			overview = overview.substring(0, 99) + '…';
		}
		$(el).find('.widget-overview').text(overview);
	};

	// 読み込み時に全ウィジェットのテキスト概要を表示
	$('.pb-rows-container .pb-widget').each(function(){
		updateWidgetOverview(this);
	});

	// TinyMCE入力値をtextareaに反映させる
	// ビジュアル入力値が複製されない対策
	var updateTinymceTextarea = function($widget) {
		if (window.tinymce) {
			var editor;
			$($widget).find('.pb-modal .wp-editor-area').each(function(e){
				editor = window.tinymce.get(this.id);
				if (editor && !editor.isHidden()) {
					editor.save();
				}
			});
		}
	};

	// ウィジェット完了ボタン
	$pb_metabox.on('click', '.pb-modal-edit-widget .pb-apply, .pb-modal-edit-widget .pb-close', function(e){
		e.preventDefault();
		e.stopPropagation();

		// TinyMCE入力値をtextareaに反映させる
		updateTinymceTextarea($(this).closest('.pb-widget'));

		// テキスト概要を更新
		updateWidgetOverview($(this).closest('.pb-widget'));
	});

	// モーダルでのウィジェット削除
	$pb_metabox.on('click', '.pb-widget .pb-modal-edit-widget .pb-delete', function(e){
		if ($(this).attr('data-confirm-cancel')) return false;
		$(this).closest('.pb-widget').fadeOut('fast', function(){
			$(this).remove();
			fit_cell_height();
		});
		return false;
	});

	// ウィジェット追加ボタン
	$('.pb-add-widget').on('click', function(){
		// 行なし
		if (!$(this).closest('.postbox').find('.pb-rows-container .pb-row-container').size()) {
			if ($(this).attr('data-require-row-message')) {
				alert($(this).attr('data-require-row-message'));
			}
			return false;
		}
		$('.pb-modal:visible').hide();
		$('#pb-add-widget-modal').show();

		// モーダル外スクロール禁止
		window_scroll_disable();
	});

	// ウィジェット追加モーダルのウィジェットクリック
	$('#pb-add-widget-modal .pb-select-widget a').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();
		var target_widget = $(this).attr('href');
		if (target_widget.indexOf('#') !== 0 || !$(target_widget).size()) return false;
		$('.pb-modal:visible').hide();

		// セルにウィジェットクローンを挿入

		var $rows = $(this).closest('.postbox').find('.pb-rows-container');
		var $target_cell = $rows.find('.cell.cell-selected');
		if (!$target_cell.size()) {
			$target_cell = $rows.find('.cell:last');
		}

		var widget_index = $rows.attr('data-widgets') || 0;
		widget_index++;
		$rows.attr('data-widgets', widget_index);

		// クローン
		var $widget = $(target_widget).clone();
		$widget.attr('id', 'widget-' + widget_index);

		// ウィジェットモーダルIDを書き換え
		// 追加された場合のみwidget-modal-NNNとなる
		var $widget_modal = $widget.find('.pb-modal')
		$widget_modal.attr('id', 'widget-modal-' + widget_index);

		// HTMLをテキスト取得しウィジェットインデックスをまとめて置換
		var html = $widget.prop('outerHTML');
		var replace_widget_index = $widget.attr('data-widget-index');
		if (replace_widget_index) {
			html = html.replace(new RegExp(replace_widget_index, 'g'), widget_index);
		} else {
			html = html.replace(/widgetindex/g, widget_index);
		}
		$target_cell.find('.widgets-container').append(html);

		// WordPress Color Picker セット
		$('#widget-' + widget_index + ' .pb-modal-edit-widget .pb-wp-color-picker').wpColorPicker();

		// ウィジェットソータブルリフレッシュ
		$('.widgets-container').sortable('refresh');

		// 行内のカラムの高さを合わせる
		fit_cell_height();

		// ウィジェットの並び順を更新
		widget_sort_collections();

		// モーダル表示
		var $widget_modal = $('#widget-modal-' + widget_index);
		$widget_modal.show();

		// モーダル外スクロール禁止
		window_scroll_disable();
	});

	// ウィジェット複製処理
	var widget_clone =  function(source_widget_index, open_modal){
		// 複製元ウィジェット
		var $source_widget = $('#widget-' + source_widget_index);
		if (!$source_widget.size()) return false;

		// モーダルを閉じる
		$('.pb-modal:visible').hide();
		window_scroll_enable();

		// TinyMCE入力値をtextareaに反映させる
		updateTinymceTextarea($source_widget);

		// テキスト概要を更新
		updateWidgetOverview($source_widget);

		// clone()すると複製元ラジオのチェックが外れる対策
		$source_widget.find(':radio:checked').each(function(){
			$(this).attr('data-checked', 'checked');
		});

		// ウィジェットインデックス
		var $rows = $source_widget.closest('.postbox').find('.pb-rows-container');
		var widget_index = $rows.attr('data-widgets') || 0;
		widget_index++;
		$rows.attr('data-widgets', widget_index);

		// 挿入先セル
		var $target_cell = $source_widget.closest('.cell');

		// 複製してウィジェットインデックスを書き換え
		var $widget = $source_widget.clone();
		$widget.attr('id', 'widget-' + widget_index);
		$widget.attr('data-widget-index', widget_index);

		// ウィジェットモーダルIDを書き換え
		var $widget_modal = $widget.find('.pb-modal')
		$widget_modal.attr('id', $widget.attr('data-widget-id') + '-' + widget_index);

		// 複製元ウィジェットの後ろに挿入
		$source_widget.after($widget);

		// input nameのウィジェットインデックス置換
		$widget.find(':input').each(function(){
			if (this.name) {
				this.name = this.name.replace('\[widget\]\[' + source_widget_index + '\]', '\[widget\]\[' + widget_index + '\]');
				$(this).trigger('change');
			}
		});

		// WordPress Color Picker 解除して再セット
		$widget.find('.pb-modal-edit-widget .wp-color-picker').each(function(){
			var $pickercontainer = $(this).closest('.wp-picker-container');
			var $clone = $(this).clone().attr('value', $(this).val()).attr('data-value', $(this).val());
			$pickercontainer.after($clone).remove();
		});
		$widget.find('.pb-modal-edit-widget .pb-wp-color-picker').wpColorPicker();

		// clone()すると複製元ラジオのチェックが外れる対策
		$source_widget.find(':radio[data-checked]').each(function(){
			$(this).attr('checked', 'checked');
			$(this).removeAttr('data-checked');
		});
		$widget.find(':radio[data-checked]').each(function(){
			$(this).attr('checked', 'checked');
			$(this).removeAttr('data-checked');
		});

		// ウィジェットソータブルリフレッシュ
		$('.widgets-container').sortable('refresh');

		// 行内のカラムの高さを合わせる
		fit_cell_height();

		// ウィジェットの並び順を更新
		widget_sort_collections();

		// 複製クラス追加削除
		$widget.addClass('pb-widget-cloned');
		setTimeout(function(){
			$widget.removeClass('pb-widget-cloned');
		}, 5000);

		// モーダル表示
		if (open_modal) {
			$('#widget-modal-' + widget_index).show();

			// モーダル外スクロール禁止
			window_scroll_disable();
		}
	};

	// ウィジェット複製クリック （モーダル内複製も共通）
	$pb_metabox.on('click', '.pb-rows-container .pb-widget .pb-widget-clone', function(e){
		e.preventDefault();
		e.stopPropagation();
		widget_clone($(this).closest('.pb-widget').attr('data-widget-index'));
	});

	// ウィジェットソータブル
	var widget_sortable_init =  function(){
		$('.widgets-container').sortable({
			handle: '.pb-widget-wrapper',
			placeholder: 'pb-widget-sortable-highlight',
			connectWith: '#' + $pb_metabox.attr('id') + ' .pb-cells .cell .widgets-container',
			tolerance: 'pointer',
			distance: 3,
			scroll: false,
			over: function (e, ui) {
				fit_cell_height();
			},
			start: function (e, ui) {
				$(document).trigger('page-builder-widget-sortable-start', ui.item[0]);
			},
			stop: function (e, ui) {
				fit_cell_height();
				widget_sort_collections();
				$(document).trigger('page-builder-widget-sortable-stop', ui.item[0]);
			}
		});
	};
	widget_sortable_init();

	// ウィジェットの並び順をフォームにセット
	var widget_sort_collections =  function(){
		$('.pb-rows-container .pb-row-container').each(function(){
			var row_index = parseInt($(this).find('.pb-row-index').val(), 10);
			if (!row_index) return;

			$(this).find('.pb-cells .cell').each(function(i){
				var cell_index = row_index + '-' + (i + 1);
				var widget_indexes = [];

				$(this).find('.pb-widget').each(function(){
					var widget_index = parseInt($(this).attr('id').replace('widget-', ''), 10);
					if (widget_index) {
						widget_indexes.push(widget_index);
					}
				});

				$(this).find('.widget_indexes').remove();
				$(this).append('<input type="hidden" name="pagebuilder[cell][' + cell_index + ']" value="' + widget_indexes.join(',')  + '" class="widget_indexes" />');
			});
		});

	};
	widget_sort_collections();

	// ウィジェット背景透明変更
	$pb_metabox.on('change', '.pb-modal-edit-widget .use_widget_background_color:checkbox', function(){
		if (this.checked) {
			$(this).closest('.pb-modal-edit-widget').find('.form-field-widget_background_color').slideDown('fast');
		} else {
			$(this).closest('.pb-modal-edit-widget').find('.form-field-widget_background_color').slideUp('fast');
		}
	});
	$pb_metabox.find('.pb-modal-edit-widget .use_widget_background_color:checkbox').trigger('change');

	// モバイル用モーダルサイドバートグル
	$pb_metabox.on('click', '.pb-modal.pb-has-right-sidebar .pb-toggle-rightbar', function(e){
		$('body').toggleClass('pb-show-right-sidebar');
	});
	$pb_metabox.on('click', '.pb-modal.pb-has-right-sidebar .pb-content', function(e){
		$('body.pb-show-right-sidebar').removeClass('pb-show-right-sidebar');
	});

});
