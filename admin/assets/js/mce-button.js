(function() {

	if (!tcdQuicktagsL10n) return;

	// ビジュアルエディタにプルダウンメニューの追加
	tinymce.PluginManager.add('tcd_mce_button', function( editor, url ) {
		editor.addButton( 'tcd_mce_button', {
			text: tcdQuicktagsL10n.pulldown_title.display,
			icon: false,
			type: 'menubutton',
			menu: [
				{
					// Youtube動画
					text: tcdQuicktagsL10n.ytube.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.ytube.tag);
					}
				},
				{
					// 関連記事カードリンク
					text: tcdQuicktagsL10n.relatedcardlink.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.relatedcardlink.tag);
					}
				},
				{
					// レイアウト2c
					text: tcdQuicktagsL10n['post_col-2'].display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n['post_col-2'].tag);
					}
				},
				{
					// レイアウト3c
					text: tcdQuicktagsL10n['post_col-3'].display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n['post_col-3'].tag);
					}
				},
				{
					// H3見出しa
					text: tcdQuicktagsL10n.style3a.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.style3a.tag);
					}
				},
				{
					// H3見出しb
					text: tcdQuicktagsL10n.style3b.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.style3b.tag);
					}
				},
				{
					// H4見出しa
					text: tcdQuicktagsL10n.style4a.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.style4a.tag);
					}
				},
				{
					// H4見出しb
					text: tcdQuicktagsL10n.style4b.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.style4b.tag);
					}
				},
				{
					// H5見出しa
					text: tcdQuicktagsL10n.style5a.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.style5a.tag);
					}
				},
				{
					// H5見出しb
					text: tcdQuicktagsL10n.style5b.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.style5b.tag);
					}
				},
				{
					// 囲み枠a
					text: tcdQuicktagsL10n.well.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.well.tag);
					}
				},
				{
					// 囲み枠b
					text: tcdQuicktagsL10n.well2.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.well2.tag);
					}
				},
				{
					// 囲み枠c
					text: tcdQuicktagsL10n.well3.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.well3.tag);
					}
				},
				{
					// フラットボタン
					text: tcdQuicktagsL10n.q_button.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button.tag);
					}
				},
				{
					// フラットボタン-L
					text: tcdQuicktagsL10n.q_button_l.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_l.tag);
					}
				},
				{
					// フラットボタン-S
					text: tcdQuicktagsL10n.q_button_s.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_s.tag);
					}
				},
				{
					// フラットボタン-blue
					text: tcdQuicktagsL10n.q_button_blue.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_blue.tag);
					}
				},
				{
					// フラットボタン-green
					text: tcdQuicktagsL10n.q_button_green.display,
					onclick: function() {
                             editor.insertContent(tcdQuicktagsL10n.q_button_green.tag);
					}
				},
				{
					// フラットボタン-red
					text: tcdQuicktagsL10n.q_button_red.display,
					onclick: function() {
                             editor.insertContent(tcdQuicktagsL10n.q_button_red.tag);
					}
				},
				{
					// フラットボタン-yellow
					text: tcdQuicktagsL10n.q_button_yellow.display,
					onclick: function() {
                             editor.insertContent(tcdQuicktagsL10n.q_button_yellow.tag);
					}
				},
				{
					// 角丸ボタン
					text: tcdQuicktagsL10n.q_button_rounded.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_rounded.tag);
					}
				},
				{
					// 角丸ボタン-L
					text: tcdQuicktagsL10n.q_button_rounded_l.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_rounded_l.tag);
					}
				},
				{
					// 角丸ボタン-S
					text: tcdQuicktagsL10n.q_button_rounded_s.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_rounded_s.tag);
					}
				},
				{
					// ラウンドボタン
					text: tcdQuicktagsL10n.q_button_pill.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_pill.tag);
					}
				},
				{
					// ラウンドボタン-L
					text: tcdQuicktagsL10n.q_button_pill_l.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_pill_l.tag);
					}
				},
				{
					// ラウンドボタン-S
					text: tcdQuicktagsL10n.q_button_pill_s.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.q_button_pill_s.tag);
					}
				},
				{
					// 広告
					text: tcdQuicktagsL10n.single_banner.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.single_banner.tag);
					}
				},
				{
					// 改ページ
					text: tcdQuicktagsL10n.page_break.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.page_break.tag);
					}
				},
				{
					// 縦書き
					text: tcdQuicktagsL10n.vertical_writing.display,
					onclick: function() {
						editor.insertContent(tcdQuicktagsL10n.vertical_writing.tag);
					}
				}
			]
		});
	});
})();
