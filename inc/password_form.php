<?php
$options = get_design_plus_option();

// 固定ページでも「抜粋」を使用可能にする
add_post_type_support( 'page', 'excerpt' );

// 初期状態で「抜粋」ボックスを編集画面に表示する
function tcd_default_hidden_meta_boxes( $hidden ) {

	if ( $key = array_search( 'postexcerpt', $hidden ) ) {
		unset( $hidden[$key] ); 
	}
	return $hidden;

}
add_filter( 'default_hidden_meta_boxes', 'tcd_default_hidden_meta_boxes' );

// 投稿編集画面に「会員登録への誘導文」を選択するためのメタボックスを追加
$pw_content_options = array();
for ( $i = 0; $i <= 5; $i++ ) {
	if ( 0 === $i ) {
		$pw_content_options[] = array( 'value' => '', 'label' => __( 'Select a content to encourage member registration.', 'tcd-w' ) );
	} else {
		$pw_content_options[] = array( 'value' => $i, 'label' => __( 'Content', 'tcd-w' ) . $i . ' : ' . $options['pw_name' . $i] );
	}
}

$pw_fields = array(
	array( 
		'id' => 'pw_content',
		'title' => '',
		'type' => 'select',
		'options' => $pw_content_options,
	)
);

$pw_args = array(
	'id' => 'select_pw_meta_box',
	'title' => __( 'Contents to encourage member registration', 'tcd-w' ),
	'screen' => array( 'post', 'page' ),
	'fields' => $pw_fields,
	'before_render' => '<div>',
	'after_render' => '</div>'
); 
$pw_meta_box = new TCD_Meta_Box( $pw_args );

// 「会員登録への誘導文」選択時、「保護中: 」を削除する
// また、正しいパスワードクッキーが存在する場合も削除する
function tcd_protected_title_format( $title, $post ) {
	if ( ! post_password_required( $post ) || $post->pw_content ) {
		return '%s';
	} else {
		return $title; // 「保護中: 」のついたデフォルトタイトルを返す
	}
}
add_filter( 'protected_title_format', 'tcd_protected_title_format', 10, 2 );

// 「会員登録への誘導文」選択時、記事一覧に抜粋文を表示する
function tcd_the_excerpt() {
	global $post;
	if ( post_password_required( $post ) && $post->pw_content ) {
		if ( has_excerpt() ) {
			$excerpt = $post->post_excerpt;
		} else {
			$excerpt = $post->post_content;
		}

		// 不要なタグ、ショートコード、改行等を削除
		$excerpt = preg_replace( '!<style.*?>.*?</style.*?>!is', '', $excerpt );
		$excerpt = preg_replace( '!<script.*?>.*?</script.*?>!is', '', $excerpt );
		$excerpt = strip_shortcodes( $excerpt );
		$excerpt = strip_tags( $excerpt );
		$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
		$excerpt = str_replace( array( "\r\n", "\r", "\n" , "&nbsp;" ), "", $excerpt );
		$excerpt = htmlspecialchars( $excerpt, ENT_QUOTES );			

		$excerpt = wp_trim_words( $excerpt, 80, '...' );

		return $excerpt;

	} else {
		// 誘導文が選択されていないときは、デフォルトのパスワード保護の文言を返す
		return get_the_excerpt(); 
	} 
}
add_filter( 'the_excerpt', 'tcd_the_excerpt' );

// パスワード保護
function tcd_password_form() {
	global $post;
	$options = get_design_plus_option();
	
	$pw_content = $post->pw_content;
	$pw_align = $options['pw_align'];

	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form c-pw" method="post">';
	
	// 抜粋文を追加
	$output .= wpautop( $post->post_excerpt );

	// 導入文を追加 
	if ( $pw_content ) {

		$output .= '<div class="c-pw__desc">' . apply_filters( 'the_content', $options['pw_editor' . $pw_content] ) . '</div>';

		// ボタンを追加
		if ( $options['pw_btn_label' . $pw_content] ) {	

			$target = $options['pw_btn_target' . $pw_content] ? ' target="_blank"' : '';

			if ( 'type2' === $pw_align ) {
				$output .= '<div class="u-center">';
			} else {
				$output .= '<div>';
			}
			$output .= '<a class="c-pw__btn c-pw__btn--register" href="' . esc_url( $options['pw_btn_url' . $pw_content] ) . '"' . $target . '>' .  esc_html( $options['pw_btn_label' . $pw_content] ) .'</a></div>';

		}	

	}

	// パスワード入力ボックスを追加
	if ( 'type2' === $pw_align ) {
		$output .= '<div class="u-center">';
	} else {
		$output .= '<div>';
	}
  $output .= '<div class="c-pw__box">';

	// 誘導文を選択していない時、ボックスにデフォルトテキストを表示
	if ( ! $pw_content ) {

		$output .= '<p class="c-pw__box-desc">' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>';
		$output .= '<div><label class="c-pw__box-label" for="' . $label . '">' . __( 'Password:', 'tcd-w' ) . '</label><input class="c-pw__box-input" name="post_password" id="' . $label . '" type="password" size="20"><input class="c-pw__btn c-pw__btn--submit" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form' ) . '"></div>';

	} else {

		$output .= '<div>';

		if ( $options['pw_label'] ) { 
			$output .= '<label class="c-pw__box-label" for="' . $label . '">' . esc_html( $options['pw_label'] ) . '</label>';
		}
			
		$output .= '<input class="c-pw__box-input" name="post_password" id="' . $label . '" type="password" size="20"><input class="c-pw__btn c-pw__btn--submit" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form' ) . '"></div>';

	}

	$output .= '</div></div></form>';

	return $output;
}
add_filter( 'the_password_form', 'tcd_password_form' );
