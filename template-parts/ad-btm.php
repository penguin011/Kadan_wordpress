<?php 
$dp_options = get_design_plus_option(); 

/**
 * postで使用するオプション
 *   pc: single_ad_code, single_ad_image, single_ad_url
 * 	 sp: single_mobile_ad_code, single_mobile_ad_image, single_mobile_ad_url
 *
 * newsで使用するオプション
 *   pc: news_ad_code, news_ad_image, news_ad_url
 * 	 sp: news_mobile_ad_code, news_mobile_ad_image, news_mobile_ad_url
 */
$slug = is_singular( 'news' ) ? 'news' : 'single';
$slug .= is_mobile() ? '_mobile' : '';

if ( is_mobile() ) {

	if ( $dp_options[$slug . '_ad_code1'] || $dp_options[$slug . '_ad_image1'] ) {
		echo '<div class="p-entry__ad p-entry__ad--lower">' . "\n";
		if ( $dp_options[$slug . '_ad_code1'] ) {
			echo '<div class="p-entry__ad-item">' . $dp_options[$slug . '_ad_code1'] . '</div>';
		} elseif ( $dp_options[$slug . '_ad_image1'] ) {
			$mobile_image1 = wp_get_attachment_image_src( $dp_options[$slug . '_ad_image1'], 'full' ); 
			echo '<div class="p-entry__ad-item"><a href="' . esc_url( $dp_options[$slug . '_ad_url1'] ) . '"><img src="' . esc_attr( $mobile_image1[0] ) . '" alt=""></a></div>';
		}
		echo '</div>' . "\n";
	}

} else {

	if ( $dp_options[$slug . '_ad_code3'] || $dp_options[$slug . '_ad_image3'] || $dp_options[$slug . '_ad_code4'] || $dp_options[$slug . '_ad_image4'] ) {
		echo '<div class="p-entry__ad p-entry__ad--lower">' . "\n";
		if ( $dp_options[$slug . '_ad_code3'] ) {
			echo '<div class="p-entry__ad-item">' . $dp_options[$slug . '_ad_code3'] . '</div>';
		} elseif ( $dp_options[$slug . '_ad_image3'] ) {
			$image3 = wp_get_attachment_image_src( $dp_options[$slug. '_ad_image3'], 'full' ); 
			echo '<div class="p-entry__ad-item"><a href="' . esc_url( $dp_options[$slug. '_ad_url3'] ) . '"><img src="' . esc_attr( $image3[0] ) . '" alt=""></a></div>';
		}
		if ( $dp_options[$slug . '_ad_code4'] ) {
			echo '<div class="p-entry__ad-item">' . $dp_options[$slug . '_ad_code4'] . '</div>';
		} elseif ( $dp_options[$slug . '_ad_image4'] ) {
			$image4 = wp_get_attachment_image_src( $dp_options[$slug . '_ad_image4'], 'full' ); 
			echo '<div class="p-entry__ad-item"><a href="' . esc_url( $dp_options[$slug . '_ad_url4'] ) . '"><img src="' . esc_attr( $image4[0] ) . '" alt=""></a></div>';
		}
		echo '</div>' . "\n";
	}
}
?>
