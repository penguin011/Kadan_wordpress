<?php

// Set theme languages directory
load_theme_textdomain( 'tcd-w', get_template_directory() . '/languages' );

function kadan_setup() {

	// Enable post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Enable a title tag
	add_theme_support( 'title-tag' );

	// Enable automatic feed links
	add_theme_support( 'automatic-feed-links' );

	// Add image sizes
  add_image_size( 'size1', 592, 410, true ); // For index.php
  add_image_size( 'size2', 500, 500, true ); // For archive-news.php
  add_image_size( 'size3', 240, 240, true ); // For single.php, sidebar.php
  add_image_size( 'size4', 416, 416, true ); // For single.php
  add_image_size( 'size5', 594, 594, true ); // For archive-plan.php
  add_image_size( 'size6', 516, 356, true ); // For front-page.php
  add_image_size( 'size7', 848, 582, true ); // For front-page.php
  add_image_size( 'size8', 600, 412, true ); // For widget "Styled post list3"
	add_image_size( 'size-card', 120, 120, true ); // For card link
	
	// Register menus
	register_nav_menus( array(
		'global' => __( 'Global navigation', 'tcd-w' ),
		'footer' => __( 'Footer navigation', 'tcd-w' )
	) );

}
add_action( 'after_setup_theme', 'kadan_setup' );

function kadan_init() {

  $dp_options = get_design_plus_option();

	// Disable emoji
  if ( 0 == $dp_options['use_emoji'] ) {
  	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );    
  	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

  // Register custom post type "News"
  $news_label = $dp_options['news_breadcrumb'] ? esc_html( $dp_options['news_breadcrumb'] ) : __( 'News', 'tcd-w' );
  $news_slug = $dp_options['news_slug'] ? esc_html( $dp_options['news_slug'] ) : 'news';
	$news_labels = array(
		'name' => $news_label,
		'add_new' => __( 'Add New', 'tcd-w' ),
		'add_new_item' => __( 'Add New Item', 'tcd-w' ),
		'edit_item' => __( 'Edit', 'tcd-w' ),
		'new_item' => __( 'New item', 'tcd-w' ),
		'view_item' => __( 'View Item', 'tcd-w' ),
		'search_items' => __( 'Search Items', 'tcd-w' ),
		'not_found' => __( 'Not Found', 'tcd-w' ),
		'not_found_in_trash' => __( 'Not found in trash', 'tcd-w' ), 
		'parent_item_colon' => ''
	);
	register_post_type( 'news', array(
  	'label' => $news_label,
  	'labels' => $news_labels,
  	'public' => true,
  	'publicly_queryable' => true,
  	'menu_position' => 5,
  	'show_ui' => true,
  	'query_var' => true,
  	'rewrite' => array( 'slug' => $news_slug ),
  	'capability_type' => 'post',
  	'has_archive' => true,
  	'hierarchical' => false,
  	'supports' => array( 'title', 'editor', 'thumbnail' )
 	) );

  // Register custom post type "Plan"
  $plan_label = $dp_options['plan_breadcrumb'] ? esc_html( $dp_options['plan_breadcrumb'] ) : __( 'Plan', 'tcd-w' );
  $plan_slug = $dp_options['plan_slug'] ? esc_html( $dp_options['plan_slug'] ) : 'plan';
	$plan_labels = array(
		'name' => $plan_label,
		'add_new' => __( 'Add New', 'tcd-w' ),
		'add_new_item' => __( 'Add New Item', 'tcd-w' ),
		'edit_item' => __( 'Edit', 'tcd-w' ),
		'new_item' => __( 'New item', 'tcd-w' ),
		'view_item' => __( 'View Item', 'tcd-w' ),
		'search_items' => __( 'Search Items', 'tcd-w' ),
		'not_found' => __( 'Not Found', 'tcd-w' ),
		'not_found_in_trash' => __( 'Not found in trash', 'tcd-w' ), 
		'parent_item_colon' => ''
	);
	register_post_type( 'plan', array(
  	'label' => $plan_label,
  	'labels' => $plan_labels,
  	'public' => true,
  	'publicly_queryable' => true,
  	'menu_position' => 5,
  	'show_ui' => true,
  	'query_var' => true,
  	'rewrite' => array( 'slug' => $plan_slug ),
  	'capability_type' => 'post',
  	'has_archive' => true,
  	'hierarchical' => false,
  	'supports' => array( 'title', 'editor', 'thumbnail' )
 	) );

}
add_action( 'init', 'kadan_init' );

/**
 * Change the post number to display
 */
function kadan_pre_get_posts( $query ) { 

  $dp_options = get_design_plus_option();

  if ( is_admin() || ! $query->is_main_query() ) return;

  if ( $query->is_post_type_archive( 'plan' ) ) { 

    $query->set( 'posts_per_page', $dp_options['plan_post_num'] );

  }
}
add_action( 'pre_get_posts', 'kadan_pre_get_posts' );

/**
 * Add widget areas
 */
function kadan_widgets_init() {

  // Base
	register_sidebar( array(
		'before_widget' => '<div class="p-widget %2$s" id="%1$s">' . "\n",
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="p-widget__title">',
		'after_title' => '</h2>',
		'name' => __( 'Common widget', 'tcd-w' ),
		'id' => 'common_widget'
	) );

  // Posts
	register_sidebar( array(
		'before_widget' => '<div class="p-widget %2$s" id="%1$s">' . "\n",
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="p-widget__title">',
		'after_title' => '</h2>',
		'name' => __( 'Blog', 'tcd-w' ),
		'id' => 'blog_widget'
	) );
	register_sidebar( array(
		'before_widget' => '<div class="p-widget %2$s" id="%1$s">' . "\n",
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="p-widget__title">',
		'after_title' => '</h2>',
		'name' => __( 'Blog (mobile)', 'tcd-w' ),
		'id' => 'blog_widget_sp'
	) );

  // News 
	register_sidebar( array(
		'before_widget' => '<div class="p-widget %2$s" id="%1$s">' . "\n",
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="p-widget__title">',
		'after_title' => '</h2>',
		'name' => __( 'News', 'tcd-w' ),
		'id' => 'news_widget'
	) );
	register_sidebar( array(
		'before_widget' => '<div class="p-widget %2$s" id="%1$s">' . "\n",
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="p-widget__title">',
		'after_title' => '</h2>',
		'name' => __( 'News (mobile)', 'tcd-w' ),
		'id' => 'news_widget_sp'
	) );

  // Pages
	register_sidebar( array(
		'before_widget' => '<div class="p-widget %2$s" id="%1$s">' . "\n",
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="p-widget__title">',
		'after_title' => '</h2>',
		'name' => __( 'Page', 'tcd-w' ),
		'id' => 'page_widget'
	) );
	register_sidebar( array(
		'before_widget' => '<div class="p-widget %2$s" id="%1$s">' . "\n",
		'after_widget' => "</div>\n",
		'before_title' => '<h2 class="p-widget__title">',
		'after_title' => '</h2>',
		'name' => __( 'Page (mobile)', 'tcd-w' ),
		'id' => 'page_widget_sp'
	 ) );

}
add_action( 'widgets_init', 'kadan_widgets_init' );

/**
 * Enqueue scripts
 */
function kadan_scripts() {

  $dp_options = get_design_plus_option();

  wp_enqueue_script( 'kadan-inview', get_template_directory_uri() . '/assets/js/jquery.inview.min.js', array( 'jquery' ), version_num(), true );
  wp_enqueue_style( 'kadan-slick', get_template_directory_uri() . '/assets/css/slick.min.css' );
  wp_enqueue_style( 'kadan-slick-theme', get_template_directory_uri() . '/assets/css/slick-theme.min.css' );
  wp_enqueue_script( 'kadan-slick', get_template_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), version_num(), false );

  wp_enqueue_style( 'kadan-style', get_stylesheet_uri(), false, version_num() );
  wp_enqueue_script( 'kadan-script', get_template_directory_uri() . '/assets/js/functions.min.js', array( 'jquery', 'kadan-slick' ), version_num(), true );

	if ( is_front_page() ) {
    wp_enqueue_script( 'kadan-front-script', get_template_directory_uri() . '/assets/js/front-page.min.js', array( 'jquery', 'kadan-slick', 'kadan-inview', 'kadan-script' ), version_num(), true );
  } elseif ( is_singular( 'post' ) ) {
    wp_enqueue_script( 'kadan-comment', get_template_directory_uri() . '/assets/js/comment.js', array( 'jquery' ), version_num(), true );
  }

}

// Priority 12 is bigger than that of pagebuilder (11)
add_action( 'wp_enqueue_scripts', 'kadan_scripts', 12 ); 

/**
 * Enqueue admin scripts
 */
function kadan_admin_scripts( $hook_suffix ) {

  // Media uploader API
  wp_enqueue_media();
  wp_enqueue_script( 'cf-media-field', get_template_directory_uri() . '/admin/assets/js/cf-media-field.min.js', '', version_num() );
  wp_localize_script( 'cf-media-field', 'cfmf_text', array( 'title' => __( 'Please Select Image', 'tcd-w' ), 'button' => __( 'Use this Image', 'tcd-w' ) ) );

	// WordPress Color Picker API
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker');

  // For widgets.php
  if ( 'widgets.php' === $hook_suffix ) {
    wp_enqueue_style( 'kadan-widgets', get_template_directory_uri() . '/admin/assets/css/widgets.min.css', '', version_num() );
	  wp_enqueue_script( 'kadan-widgets', get_template_directory_uri() . '/admin/assets/js/widget.min.js', array( 'jquery' ), '', version_num() );
  }

  // For theme options
  if ( 'appearance_page_theme_options' === $hook_suffix ) {
    wp_enqueue_style( 'a-footer-bar', get_template_directory_uri() . '/admin/assets/css/footer-bar.min.css', '', version_num() );
    wp_enqueue_style( 'cb', get_template_directory_uri() . '/admin/assets/css/cb.min.css', '', version_num() );
	  wp_enqueue_script( 'cb', get_template_directory_uri() . '/admin/assets/js/cb.min.js', '', version_num() );
		wp_enqueue_style( 'editor-buttons' ); // editor-buttons.css を常時出力
	  wp_enqueue_script( 'jquery.cookieTab', get_template_directory_uri() . '/admin/assets/js/jquery.cookieTab.js', '', version_num() );
	  wp_enqueue_script( 'jquery-form' ); // For submitting with AJAX
  }

  wp_enqueue_style( 'my-admin', get_template_directory_uri() . '/admin/assets/css/my_admin.min.css', '', version_num() );
	wp_enqueue_script( 'my-script', get_template_directory_uri() . '/admin/assets/js/my_script.min.js', array( 'jquery' ), version_num(), true );
  wp_localize_script( 'my-script', 'error_messages', array( 'success' => __( 'Settings Saved Successfully', 'tcd-w' ), 'error' => __( 'Can not save data. Please try again.', 'tcd-w' ) ) );
}
add_action( 'admin_enqueue_scripts', 'kadan_admin_scripts' );

/**
 * Disable self pingback
 */
function no_self_ping( &$links ) {
  $home = home_url();
  foreach ( $links as $l => $link ) {
  	if ( 0 === strpos( $link, $home ) ) {
			unset( $links[$l] );
		}
	}
}
add_action( 'pre_ping', 'no_self_ping' );

/**
 * Add favicon
 */
function kadan_add_favicon() {
  $dp_options = get_design_plus_option();
  if ( $dp_options['favicon'] ) {
    echo '<link rel="shortcut icon" href="' . esc_html( wp_get_attachment_url( $dp_options['favicon'] ) ) . '">' . "\n";
  }
}
add_action( 'wp_head', 'kadan_add_favicon' );

/**
 * Registers an editor stylesheet for the theme
 */
function kadan_add_editor_styles() {
	add_editor_style( 'admin/assets/css/editor-style.min.css' );
}
add_action( 'admin_init', 'kadan_add_editor_styles' );

/**
 * Manage get_the_archive_title() function
 */
function kadan_archive_title( $title ) {
  global $author, $post;
  if ( is_author() ) { 
    $title = get_the_author_meta( 'display_name', $author );
  } elseif ( is_category() || is_tag() ) { 
    $title = single_term_title( '', false );
  } elseif ( is_day() ) { 
    $title = get_the_time( __( 'F jS, Y', 'tcd-w' ), $post );
  } elseif ( is_month() ) { 
    $title = get_the_time( __( 'F, Y', 'tcd-w' ), $post );
  } elseif ( is_year() ) { 
    $title = get_the_time( __( 'Y', 'tcd-w' ), $post );
  } elseif ( is_search() ) { 
    $title = __( 'Search result', 'tcd-w' );
  }
  return $title;
}
add_filter( 'get_the_archive_title', 'kadan_archive_title' );

/**
 * Remove unnecessary codes from wp_head
 */
remove_action( 'wp_head', 'wp_generator' ); 
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

/**
 * Remove inline styles
 */
add_action( 'widgets_init', 'remove_recent_comments_style' );
add_action( 'get_header', 'remove_adminbar_inline_style' );

function remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

function remove_adminbar_inline_style() {
  remove_action( 'wp_head', '_admin_bar_bump_cb' );
}

/**
 * Remove wpautop() function from the_excerpt
 */
remove_filter( 'the_excerpt', 'wpautop' );

/**
 * Theme options
 */
require get_template_directory() . '/admin/theme-options.php';

/**
 * Manage custom columns
 */
require get_template_directory() . '/inc/admin_column.php';

/**
 * Add quicktags to the visual editor
 */
require get_template_directory() . '/inc/custom_editor.php';

/**
 * Add footer bar
 */
require get_template_directory() . '/inc/footer-bar.php';

/*
 * Add load icon
 */
require get_template_directory() . '/inc/load-icon.php';

/**
 * Add inline styles and scripts
 */
require get_template_directory() . '/inc/head.php';
require get_template_directory() . '/inc/footer.php';
require get_template_directory() . '/inc/page-header.php';

/**
 * Add page builder
 */
require get_template_directory() . '/pagebuilder/pagebuilder.php';

/**
 * Manage quick edit
 */
require get_template_directory() . '/inc/quick_edit.php';

/**
 * Add shortcodes
 */
require get_template_directory() . '/inc/short_code.php';

/**
 * Update notifier
 */
require get_template_directory() . '/inc/update_notifier.php';

/**
 * Add custom fields
 */
require get_template_directory() . '/inc/class-tcd-meta-box.php';
require get_template_directory() . '/inc/blog_cf.php';
require get_template_directory() . '/inc/recommend.php';
require get_template_directory() . '/inc/topic.php';
require get_template_directory() . '/inc/plan_cf.php';
require get_template_directory() . '/inc/ph_cf.php';
require get_template_directory() . '/inc/template_cf.php';
require get_template_directory() . '/inc/custom_css.php';
require get_template_directory() . '/inc/seo.php';

/**
 * Manage OGP and Twitter Cards
 */
require get_template_directory() . '/inc/ogp.php';

/**
 * Add password protected pages
 */
require get_template_directory() . '/inc/password_form.php';

/**
 * Register widgets
 */
require get_template_directory() . '/inc/widgets/ad.php';
require get_template_directory() . '/inc/widgets/archive_list.php';
require get_template_directory() . '/inc/widgets/category_list.php'; 
require get_template_directory() . '/inc/widgets/google_search.php';
require get_template_directory() . '/inc/widgets/styled_post_list1.php';
require get_template_directory() . '/inc/widgets/styled_post_list2.php';
require get_template_directory() . '/inc/widgets/styled_post_list3.php';

/**
 * Test if the current browser runs on a mobile device
 */
function is_mobile() {
  
	// If you want to include tablets, use wp_is_mobile() function.
 	$match = 0;
	$ua = array(
  	'iPhone', // iPhone
   	'iPod', // iPod touch
		'Android.*Mobile', // 1.5+ Android *** Only mobile
		'Windows.*Phone', // *** Windows Phone
		'dream', // Pre 1.5 Android
		'CUPCAKE', // 1.5+ Android
		'BlackBerry', // BlackBerry
		'BB10', // BlackBerry10
		'webOS', // Palm Pre Experimental
		'incognito', // Other iPhone browser
		'webmate' // Other iPhone browser
	);

 	$pattern = '/' . implode( '|', $ua ) . '/i';
 	$match   = preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );

	return 1 === $match ? TRUE : FALSE;
}

/**
 * Translate rgb to hex
 */
function hex2rgb( $hex ) {

   $hex = str_replace( '#', '', $hex );

   if( strlen( $hex ) == 3 ) {
      $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
      $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
      $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
   } else {
      $r = hexdec( substr( $hex, 0, 2 ) );
      $g = hexdec( substr( $hex, 2, 2 ) );
      $b = hexdec( substr( $hex, 4, 2 ) );
   }
   $rgb = array( $r, $g, $b );

   return $rgb;
}

/**
 * Get the version number of this theme
 */
function version_num() {
	if ( function_exists( 'wp_get_theme' ) ) {
		$theme_data = wp_get_theme();
 	} else {
   		$theme_data = get_theme_data( TEMPLATEPATH . '/style.css' );
 	}
	$current_version = $theme_data['Version'];
 	return $current_version;
}

/**
 * カードリンクパーツ
 */
function get_the_custom_excerpt( $content, $length ) {
	$length = ( $length ? $length : 70 ); // デフォルトの長さを指定する
  $content =  preg_replace( '/<!--more-->.+/is', '', $content ); // moreタグ以降削除
 	$content =  strip_shortcodes( $content ); // ショートコード削除
  $content =  strip_tags( $content ); // タグの除去
  $content =  str_replace( '&nbsp;', '', $content ); // 特殊文字の削除（今回はスペースのみ）
  $content =  mb_substr( $content, 0, $length ); // 文字列を指定した長さで切り取る
  return $content.'...';
}
 
/**
 * カードリンクショートコード
 */
function clink_scode( $atts ) {
	extract( shortcode_atts( array( 'url' => '', 'title' => '', 'excerpt' => '' ), $atts ) );
  $id = url_to_postid( $url ); // URLから投稿IDを取得
  $post = get_post( $id ); // IDから投稿情報の取得
  $date = mysql2date( 'Y.m.d', $post->post_date ); // 投稿日の取得
  $img_width = 120; // 画像サイズの幅指定
  $img_height = 120; // 画像サイズの高さ指定
  $no_image = get_template_directory_uri() . '/assets/images/no-image-400x400.gif';

  // 抜粋を取得
  if ( empty( $excerpt ) ) {
  	if ( $post->post_excerpt ) {
    	$excerpt = get_the_custom_excerpt( $post->post_excerpt, 145 );
  	} else {
      $excerpt = get_the_custom_excerpt( $post->post_content , 145 );
  	}
  }
  // タイトルを取得
  if ( empty( $title ) ) {
  	$title = esc_html( get_the_title( $id ) );
  }
 	// アイキャッチ画像を取得 
  if ( has_post_thumbnail( $id ) ) {
  	$img = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'size-card' );
    $img_tag = '<img src="' . $img[0] . '" alt="' . $title . '" width="' . $img[1] . '" height="' . $img[2] . '">';
  } else { 
		$img_tag ='<img src="' . $no_image . '" alt="" width="' . $img_width . '" height="' . $img_height . '">';
  }
  $clink = '<div class="cardlink"><a href="' . esc_url( $url ) . '"><div class="cardlink_thumbnail">' . $img_tag . '</div></a><div class="cardlink_content"><span class="cardlink_timestamp">' . esc_html( $date ) . '</span><div class="cardlink_title"><a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a></div><div class="cardlink_excerpt">' . esc_html( $excerpt ) . '</div></div><div class="cardlink_footer"></div></div>';
  
	return $clink;
}  
add_shortcode( 'clink', 'clink_scode' );

/**
 * Comment
 */
function custom_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	global $commentcount;
	if ( ! $commentcount ) {
		$commentcount = 0;
	}
?>
<li id="comment-<?php comment_ID(); ?>" class="c-comment__list-item comment">
	<div class="c-comment__item-header u-clearfix">
		<div class="c-comment__item-meta u-clearfix">
<?php 
	if ( function_exists( 'get_avatar' ) && get_option( 'show_avatars' ) ) { 
		echo get_avatar( $comment, 35, '', false, array( 'class' => 'c-comment__item-avatar' ) ); 
	} 
	if ( get_comment_author_url() ) {
		echo '<a id="commentauthor-' . get_comment_ID() . '" class="c-comment__item-author" rel="nofollow">' . get_comment_author() . '</a>' . "\n";
	} else {
		echo '<span id="commentauthor-' . get_comment_ID() . '" class="c-comment__item-author">' . get_comment_author() . '</span>' . "\n";
	}
?>
			<time class="c-comment__item-date" datetime="<?php comment_time( 'Y-m-d' ); ?>"><?php comment_time( __( 'F jS, Y', 'tcd-w' ) ); ?></time>
		</div>
		<ul class="c-comment__item-act">
<?php 
	if ( 1 == get_option( 'thread_comments' ) ) :
?>
			<li><?php comment_reply_link( array_merge( $args, array( 'add_below' => 'comment-content', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __( 'REPLY', 'tcd-w' ) . '' ) ) ); ?></li>
<?php
	else :
?>
    	<li><a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'js-comment__textarea');"><?php _e( 'REPLY', 'tcd-w' ); ?></a></li>
<?php
	endif;
?>
    	<li><a href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment-content-<?php comment_ID() ?>', 'js-comment__textarea');"><?php _e( 'QUOTE', 'tcd-w' ); ?></a></li>
    	<?php edit_comment_link( __( 'EDIT', 'tcd-w' ), '<li>', '</li>'); ?>
		</ul>
	</div>
	<div id="comment-content-<?php comment_ID() ?>" class="c-comment__item-body">
<?php
	if ( 0 == $comment->comment_approved ) {
		echo '<span class="c-comment__item-note">' . __( 'Your comment is awaiting moderation.', 'tcd-w' ) . '</span>' . "\n"; 
	} else {
		comment_text();
	}
?>
	</div>
<?php
}

function is_kadan_page_template() {

  global $post;

  if ( is_page() && in_array( $post->page_tcd_template_type, array( 'type3', 'type4', 'type5' ) ) ) {
    return true;
  } else {
    return false;
  }

}
