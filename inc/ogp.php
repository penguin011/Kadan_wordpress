<?php
/**
 * Manage OGP and Twitter Cards
 */
$dp_options = get_design_plus_option();

function og_image( $n ) {
	global $post;
	$dp_options = get_design_plus_option();
	$myArray = array();
	if ( is_single() && has_post_thumbnail()) {
		$post_thumbnail_id = get_post_thumbnail_id($post->ID);
		$image = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
		list($myArray[0], $myArray[1], $myArray[2]) = $image;
		echo esc_attr($myArray[$n]);
	} else {
		if($dp_options['ogp_image']){
			$image = wp_get_attachment_image_src( $dp_options['ogp_image'], 'full');
			list($myArray[0], $myArray[1], $myArray[2]) = $image;
			echo esc_attr($myArray[$n]);
		}else{
		$myArray[0] = get_template_directory_uri() . '/assets/images/no-image-510x320.gif';
		$myArray[1] = 510;
		$myArray[2] = 320;
		echo esc_attr($myArray[$n]);
		}
	}
}

function twitter_image() {
	global $post;
	$dp_options = get_design_plus_option();
	if ( is_single() && has_post_thumbnail()) {
		$post_thumbnail_id = get_post_thumbnail_id($post->ID);
		$image = wp_get_attachment_image_src( $post_thumbnail_id, 'size1');
		list($src, $width, $height) = $image;
		echo esc_attr($src);
	} else {
		if($dp_options['ogp_image']){
			$image = wp_get_attachment_image_src( $dp_options['ogp_image'], 'size1');
			list($src, $width, $height) = $image;
			echo esc_attr($src);
		}else{
		echo get_template_directory_uri() . '/assets/images/no-image-510x320.gif';
		}
	}
}

function ogp() {
  global $post;
  $dp_options = get_design_plus_option();

  $og_type = is_singular() ? 'article' : 'website';
  $og_url = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  //$og_title = is_front_page() || is_home() ? get_bloginfo( 'name' ) : strip_tags( get_the_title() );
  $og_title = is_front_page() ? get_bloginfo( 'name' ) : strip_tags( wp_get_document_title() );
  $og_description = is_singular() ? get_seo_description() : get_bloginfo( 'description' );
  $twitter_title = is_singular() ? get_the_title() : get_bloginfo( 'name' );
?>
<meta property="og:type" content="<?php echo $og_type; ?>">
<meta property="og:url" content="<?php echo esc_url( $og_url ); ?>">
<meta property="og:title" content="<?php echo $og_title; ?>">
<meta property="og:description" content="<?php echo $og_description; ?>">
<meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ); ?>">
<meta property="og:image" content="<?php og_image(0); ?>">
<meta property="og:image:secure_url" content="<?php og_image(0); ?>"> 
<meta property="og:image:width" content="<?php og_image(1); ?>"> 
<meta property="og:image:height" content="<?php og_image(2); ?>">
<?php /*if ( $dp_options['fb:app_id'] ) { ?>
<meta property="fb:admins" content="<?php echo esc_attr( $dp_options['fb_admin_id'] ); ?>">
<?php } */ ?>
<?php if ( $dp_options['fb_app_id'] ) { ?>
<meta property="fb:app_id" content="<?php echo esc_attr( $dp_options['fb_app_id'] ); ?>">
<?php } ?>
<?php if ( $dp_options['use_twitter_card'] ) { ?>
<meta name="twitter:card" content="summary">
<?php if ( $dp_options['twitter_account_name'] ) { ?>
<meta name="twitter:site" content="@<?php echo esc_attr( $dp_options['twitter_account_name'] ); ?>">
<meta name="twitter:creator" content="<?php echo esc_attr( $dp_options['twitter_account_name'] ); ?>">
<?php } ?>
<meta name="twitter:title" content="<?php echo $og_title; ?>">
<meta property="twitter:description" content="<?php echo $og_description; ?>">
<?php if ( is_singular() ) { ?>
<meta name="twitter:image:src" content="<?php twitter_image(); ?>">
<?php } }
}
if ( $dp_options['use_ogp'] ) {
  add_action( 'wp_head', 'ogp', 1 );
}
