<?php
/**
 * 記事ページの広告用ショートコード
 */
function theme_option_single_banner() {

	$options = get_design_plus_option();
	$html = '';	

	if ( $options['single_ad_code5'] || $options['single_ad_image5'] || $options['single_ad_code6'] || $options['single_ad_image6'] ) {
    	
		$html .= '<div class="p-entry__ad p-entry__ad--inner">' . "\n";

    if ( $options['single_ad_code5'] ) {
    	$html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= $options['single_ad_code5'] . "\n";
      $html .= '</div>' . "\n";
    } elseif ( $options['single_ad_image5'] ) {
      $single_image5 = wp_get_attachment_image_src( $options['single_ad_image5'], 'full' );
      $html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= '<a href="' . esc_url( $options['single_ad_url5'] ) . '" target="_blank"><img src="' . esc_attr( $single_image5[0] ) . '" alt=""></a>' . "\n";
      $html .= '</div>' . "\n";
    }

    if ( $options['single_ad_code6'] ) {
    	$html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= $options['single_ad_code6'] . "\n";
      $html .= '</div>' . "\n";
    } elseif ( $options['single_ad_image6'] ) {
      $single_image6 = wp_get_attachment_image_src( $options['single_ad_image6'], 'full' );
      $html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= '<a href="' . esc_url( $options['single_ad_url6'] ) . '" target="_blank"><img src="' . esc_attr( $single_image6[0] ) . '" alt=""></a>' . "\n";
      $html .= '</div>' . "\n";
    }

    $html .= '</div>' . "\n";
    }
    return $html;
}
add_shortcode( 's_ad', 'theme_option_single_banner' );

/**
 * ニュースページの広告用ショートコード
 */
function theme_option_news_banner() {

	$options = get_design_plus_option();
	$html = '';	

	if ( $options['news_ad_code5'] || $options['news_ad_image5'] || $options['news_ad_code6'] || $options['news_ad_image6'] ) {
    	
		$html .= '<div class="p-entry__ad p-entry__ad--inner">' . "\n";

    if ( $options['news_ad_code5'] ) {
    	$html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= $options['news_ad_code5'] . "\n";
      $html .= '</div>' . "\n";
    } elseif ( $options['news_ad_image5'] ) {
      $news_image5 = wp_get_attachment_image_src( $options['news_ad_image5'], 'full' );
      $html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= '<a href="' . esc_url( $options['news_ad_url5'] ) . '" target="_blank"><img src="' . esc_attr( $news_image5[0] ) . '" alt=""></a>' . "\n";
      $html .= '</div>' . "\n";
    }

    if ( $options['news_ad_code6'] ) {
    	$html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= $options['news_ad_code6'] . "\n";
      $html .= '</div>' . "\n";
    } elseif ( $options['news_ad_image6'] ) {
      $news_image6 = wp_get_attachment_image_src( $options['news_ad_image6'], 'full' );
      $html .= '<div class="p-entry__ad-item">' . "\n";
      $html .= '<a href="' . esc_url( $options['news_ad_url6'] ) . '" target="_blank"><img src="' . esc_attr( $news_image6[0] ) . '" alt=""></a>' . "\n";
      $html .= '</div>' . "\n";
    }

    $html .= '</div>' . "\n";
    }
    return $html;
}
add_shortcode( 'n_ad', 'theme_option_news_banner' );
