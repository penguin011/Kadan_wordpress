<?php
function add_page_header_styles( $inline_styles, $dp_options ) {

  global $post;

  if ( is_front_page() || is_single() ) return $inline_styles;
  
  if ( is_post_type_archive( 'news' ) ) {
    $ph_img = $dp_options['news_ph_img'];
    $ph_color = $dp_options['news_ph_color'];
    $ph_font_size = $dp_options['news_ph_font_size'];
  } elseif ( is_post_type_archive( 'plan' ) ) {
    $ph_img = $dp_options['plan_ph_img'];
    $ph_color = $dp_options['plan_ph_color'];
    $ph_font_size = $dp_options['plan_ph_font_size'];
  } elseif ( is_404() ) {
    $ph_img = $dp_options['404_ph_img'];
    $ph_color = $dp_options['404_ph_color'];
    $ph_font_size = $dp_options['404_ph_font_size'];
  } elseif ( is_page() ) {
    $ph_img = get_post_meta( $post->ID, 'ph_img', true );
    $ph_color = get_post_meta( $post->ID, 'ph_color', true );
    $ph_font_size = get_post_meta( $post->ID, 'ph_font_size', true );
  } else {
    $ph_img = $dp_options['ph_img'];
    $ph_color = $dp_options['ph_color'];
    $ph_font_size = $dp_options['ph_font_size'];
  }

  $inline_styles[] = array(
    'selectors' => '.p-page-header__upper',
    'properties' => array(
      sprintf( 'background-image: url(%s)', esc_html( wp_get_attachment_url( $ph_img ) ) ),
      sprintf( 'color: %s', esc_html( $ph_color ) )
    )
  );
  $inline_styles[] = array(
    'selectors' => '.p-page-header__title',
    'properties' => array(
      sprintf( 'font-size: %spx', esc_html( $ph_font_size ) ),
      sprintf( 'text-shadow: 0 0 20px %s', esc_html( $ph_color ) )
    )
  );
  $inline_styles[] = array(
    'selectors' => '.p-page-header__title.is-inview',
    'properties' => sprintf( 'text-shadow: 0 0 0 %s', esc_html( $ph_color ) )
  );

  return $inline_styles;
}

add_filter( 'tcd_inline_styles', 'add_page_header_styles', 10, 2 );
