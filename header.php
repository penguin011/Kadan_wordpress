<?php
$dp_options = get_design_plus_option();

if ( is_mobile() ) {
  $header_type = $dp_options['sp_header_fix'];
  $logo_type = $dp_options['sp_header_use_logo_image'];
  $logo_image = $dp_options['sp_header_logo_image'] ? wp_get_attachment_image_src( $dp_options['sp_header_logo_image'], 'full' ) : '';
  $use_retina_logo = $dp_options['sp_header_logo_image_retina'];
} else {
  $header_type = $dp_options['header_fix'];
  $logo_type = $dp_options['header_use_logo_image'];
  $logo_image = $dp_options['header_logo_image'] ? wp_get_attachment_image_src( $dp_options['header_logo_image'], 'full' ) : '';
  $use_retina_logo = $dp_options['header_logo_image_retina'];
}
$logo_width = $logo_image && $use_retina_logo ? ( $logo_image[1] / 2 ) : 'auto';
$logo_height = $logo_image && $use_retina_logo ? ( $logo_image[2] / 2 ) : 'auto';

$args = array(
  'container' => 'nav',
  'container_id' => 'js-global-nav',
  'container_class' => 'p-global-nav',
  'items_wrap' => '<ul class="%2$s">%3$s</ul>',
  'link_after' => '<span class="sub-menu-toggle"></span>',
  'theme_location' => 'global'
);

$contents_classes = 'l-contents';
if ( $dp_options['left_sidebar'] ) {
  $contents_classes .= ' l-contents--rev';
}
if ( ! is_kadan_page_template() ) {
  $contents_classes .= ' l-inner';
}

$header_logo_tag = is_front_page() ? 'h1' : 'div';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="description" content="<?php seo_description(); ?>">
<meta name="viewport" content="width=device-width">
<meta name="format-detection" content="telephone=no">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'tcd_before_header', $dp_options ); ?>

<header id="js-header" class="l-header<?php if ( 'type2' === $header_type ) { echo ' l-header--fixed'; } ?>">
  <div class="l-header__inner l-inner">
    <<?php echo $header_logo_tag; ?> class="l-header__logo c-logo">
      <?php if ( 'type1' === $logo_type ) : // Use text ?> 
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
      <?php else : ?>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <img src="<?php echo esc_attr( $logo_image[0] ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo esc_attr( $logo_width ); ?>" height="<?php echo esc_attr( $logo_height ); ?>">
      </a>
      <?php endif; ?>
    </<?php echo $header_logo_tag; ?>>
    <a href="#" id="js-menu-btn" class="p-menu-btn c-menu-btn"></a>
		<?php wp_nav_menu( $args ); ?>
  </div>
</header>
<main class="l-main" role="main">
  <?php 
  if ( ! is_front_page() ) :
    if ( is_single() ) { 
      get_template_part( 'template-parts/breadcrumb' );
    } else {
      get_template_part( 'template-parts/page-header' );
    }
  ?>
  <div class="<?php echo $contents_classes; ?>">
    <div class="l-primary">
  <?php endif; ?>
