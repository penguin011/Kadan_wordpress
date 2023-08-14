<?php
/**
 * Meta title and description
 */
add_action( 'add_meta_boxes', 'seo_meta_box' );
add_action( 'save_post', 'save_seo_meta_box' );

function seo_meta_box() {
	global $post;
	if ( $post->ID === get_option( 'page_on_front' ) ) return;
	add_meta_box( 'add_seo_option', __( 'Meta title and description', 'tcd-w' ), 'show_seo_meta_box', array( 'post', 'page', 'news', 'review' ), 'normal', 'low' );
}

function show_seo_meta_box( $post ) {

  $seo_title = array( 
		'name' => __( 'Meta title', 'tcd-w' ), 
		'desc' => __( 'Enter meta title here.', 'tcd-w' ), 
		'id' => 'tcd-w_meta_title', 
		'type' => 
		'input', 
		'std' => '' 
	);
  $seo_title_meta = get_post_meta( $post->ID, 'tcd-w_meta_title', true );

  $seo_desc = array( 
		'name' => __( 'Meta description', 'tcd-w' ), 
		'desc' => __( 'Enter meta description here.', 'tcd-w' ), 
		'id' => 'tcd-w_meta_description', 
		'type' => 'textarea', 
		'std' => '' 
	);
  $seo_desc_meta = get_post_meta( $post->ID, 'tcd-w_meta_description', true );

	wp_nonce_field( basename(__FILE__), 'seo_meta_box_nonce' );
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		countField('#tcd-w_meta_description');
	});
 
	function countField(target) {
		jQuery(target).after('<span class="word_counter" style="display:block; margin:0 15px 0 0; font-weight:bold;"></span>');
		jQuery(target).bind({
    	keyup: function() {
      	setCounter();
   	 	},
  	 	change: function() {
  	 		setCounter();
   	 	}
	  });
  	setCounter();
  	function setCounter(){
    	jQuery('span.word_counter').text("<?php _e( 'word count:', 'tcd-w' ); ?>"+jQuery(target).val().length);
  	};
	}
	</script>
	<dl class="ml_custom_fields">
		<dt class="label">
				<label for="<?php echo esc_attr( $seo_title['id'] ); ?>"><?php echo esc_html( $seo_title['name'] ); ?></label>
		</dt>
  	<dd class="content">
			<p class="desc"><?php echo esc_html( $seo_title['desc'] ); ?></p>
  		<input type="text" name="<?php echo esc_attr( $seo_title['id'] ); ?>" id="<?php echo esc_attr( $seo_title['id'] ); ?>" value="<?php echo $seo_title_meta ? esc_attr( $seo_title_meta ) : esc_attr( $seo_title['std'] ); ?>" size="30" style="width:100%">
		</dd>
  	<dt class="label">
			<label for="<?php echo esc_attr( $seo_desc['id'] ); ?>"><?php echo esc_html( $seo_desc['name'] ); ?></label>
		</dt>
  	<dd class="content">
			<p class="desc"><?php echo esc_html( $seo_desc['desc'] ); ?></p>
  		<textarea name="<?php echo esc_attr( $seo_desc['id'] ); ?>" id="<?php echo esc_attr( $seo_desc['id'] ); ?>" cols="60" rows="2" style="width:97%"><?php echo $seo_desc_meta ? esc_textarea( $seo_desc_meta ) : esc_textarea( $seo_desc['std'] ); ?></textarea>
		</dd>
  </dl>
	<?php
}

function save_seo_meta_box( $post_id ) {

	// nonce の値を比較し、両者が異なれば処理をしない
	if ( ! isset( $_POST['seo_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['seo_meta_box_nonce'], basename(__FILE__)  ) ) {
  	return $post_id;
  }

	// 自動保存の場合は処理をしない 
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
  	return $post_id;
  }

	// ユーザー権限を調べ、権限がなければ処理をしない
  if ( 'page' == $_POST['post_type'] ) {
  	if ( ! current_user_can( 'edit_page', $post_id ) ) {
    		return $post_id;
  	}
  } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
    return $post_id;
  }

  $cf_keys = array( 'tcd-w_meta_title', 'tcd-w_meta_description' );
  foreach ( $cf_keys as $cf_key ) {
  	$old = get_post_meta( $post_id, $cf_key, true );
		$new = ( isset( $_POST[$cf_key] ) ) ? $_POST[$cf_key] : '';

    if ( $new && $new != $old ) {
    	update_post_meta( $post_id, $cf_key, $new );
    } elseif ( '' == $new && $old ) {
    	delete_post_meta( $post_id, $cf_key, $old );
    }
  }
}

/**
 * Manage title tags.
 */
function seo_title( $title ) {

	global $post, $page, $paged;

	if ( is_single() && get_post_meta( $post->ID, 'tcd-w_meta_title', true ) or is_page() && get_post_meta( $post->ID, 'tcd-w_meta_title', true ) ) {
		$title['title'] = get_post_meta( $post->ID, 'tcd-w_meta_title', true );
 	} elseif ( is_category() ) {
  	$title['title'] = sprintf( __( 'Post list for %s', 'tcd-w' ), single_cat_title( '', false ) );
 	} elseif ( is_tag() ) {
  	$title['title'] = sprintf( __( 'Post list for %s', 'tcd-w' ), single_tag_title( '', false ) );
 	} elseif ( is_search() ) {
  	$title['title'] =  sprintf( __( 'Post list for %s', 'tcd-w' ), get_search_query() );
 	} elseif ( is_day() ) {
  	$title['title'] = sprintf( __( 'Archive for %s', 'tcd-w' ), get_the_time( __( 'F jS, Y', 'tcd-w' ) ) );
 	} elseif ( is_month() ) {
  	$title['title'] = sprintf( __( 'Archive for %s', 'tcd-w' ), get_the_time( __( 'F, Y', 'tcd-w') ) );
 	} elseif ( is_year() ) {
  	$title['title'] = sprintf( __( 'Archive for %s', 'tcd-w' ), get_the_time( __( 'Y', 'tcd-w') ) );
 	} elseif ( is_author() ) {
 		global $wp_query;
  	$curauth = $wp_query->get_queried_object();
  	$title['title'] = sprintf( __( 'Archive for %s', 'tcd-w'), $curauth->display_name );
  }
  return $title;
}
add_filter( 'document_title_parts', 'seo_title', 10 );

/**
 * Manage meta description
 */
function get_seo_description() {

	global $post;

 	// カスタムフィールドがある場合
 	if ( ( is_single() || is_page() ) && get_post_meta( $post->ID, 'tcd-w_meta_description', true ) ) {
  	$trim_content = post_custom( 'tcd-w_meta_description' );
  	$trim_content = str_replace( array( "\r\n", "\r", "\n" ), '', $trim_content );
  	$trim_content = htmlspecialchars( $trim_content );
  	return esc_html( $trim_content );

 	// 抜粋記事が登録されている場合は出力
 	} elseif ( ( is_single() || is_page() ) && has_excerpt() ) { 
  	$trim_content = get_the_excerpt();
  	$trim_content = str_replace( array( "\r\n", "\r", "\n" ), '', $trim_content );
  	return esc_html( $trim_content );

	// トップページの場合
	} elseif ( is_front_page() ) {
		return esc_html( get_bloginfo( 'description' ) );

 	// 上記が無い場合は本文から120文字を抜粋
 	} elseif ( is_single() || is_page() ) {
   	$base_content = $post->post_content;
   	$base_content = preg_replace( '!<style.*?>.*?</style.*?>!is', '', $base_content );
   	$base_content = preg_replace( '!<script.*?>.*?</script.*?>!is', '', $base_content );
   	$base_content = preg_replace( '/\[.+\]/','', $base_content );
   	$base_content = strip_tags( $base_content );
   	$trim_content = mb_substr( $base_content, 0, 120, 'utf-8' );
   	$trim_content = str_replace( ']]>', ']]&gt;', $trim_content );
   	$trim_content = str_replace( array( "\r\n", "\r", "\n" ), '', $trim_content );
   	$trim_content = htmlspecialchars( $trim_content );

   	if ( preg_match( '/。/', $trim_content ) ) { 
		// 指定した文字数内にある、最後の「。」以降をカットして表示
    	mb_regex_encoding( 'UTF-8' ); 
     	$trim_content = mb_ereg_replace( '。[^。]*$', '。', $trim_content );
  		return esc_html( $trim_content );
   	} else { 
			// 指定した文字数内に「。」が無い場合は、指定した文字数の文章を表示し、末尾に「…」を表示
			if ( $trim_content == '' ) {
				return esc_html( get_bloginfo( 'description' ) );
     	} else {
				return esc_html( $trim_content ) . '...';
			}
   	}
 	} elseif ( is_day() ) {
    return sprintf( __( 'Archive for %s', 'tcd-w' ), get_the_time( __( 'F jS, Y', 'tcd-w' ) ) );
 	} elseif ( is_month() ) {
    return sprintf( __( 'Archive for %s', 'tcd-w' ), get_the_time( __( 'F, Y', 'tcd-w' ) ) );
 	} elseif ( is_year() ) {
    return sprintf( __( 'Archive for %s', 'tcd-w' ), get_the_time( __( 'Y', 'tcd-w' ) ) );
 	} elseif ( is_author() ) {
    global $wp_query;
    $curauth = $wp_query->get_queried_object();
    return sprintf( __( 'Archive for %s', 'tcd-w' ), esc_html( $curauth->display_name ) );
 	} elseif ( is_search() ) {
    return sprintf( __( 'Post list for %s', 'tcd-w' ), get_search_query() );
 	} elseif ( is_category() ) {
  	$cat_id = get_query_var( 'cat' );
  	$cat_data = get_option( "cat_$cat_id" );
  	if ( category_description() ) {
    	$category_desc = strip_tags( category_description() );
    	$category_desc = str_replace( array( "\r\n", "\r", "\n" ), '', $category_desc );
    	return esc_html( $category_desc );
  	} else {
    	return null;
  	}
 	} else {
    return esc_html( get_bloginfo( 'description' ) );
 	}
}

function seo_description() {
  echo get_seo_description();
}
