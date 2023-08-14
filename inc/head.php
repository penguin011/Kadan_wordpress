<?php
function kadan_inline_styless() {

	global $post;
	$dp_options = get_design_plus_option();

	$primary_color = $dp_options['primary_color'];
	$secondary_color = $dp_options['secondary_color'];

  switch ( $dp_options['font_type'] ) {
    case 'type1' :
      $font_type_style = 'Verdana, "ヒラギノ角ゴ ProN W3", "Hiragino Kaku Gothic ProN", "メイリオ", Meiryo, sans-serif';
      break;
    case 'type2' :
      $font_type_style = '"Segoe UI", Verdana, "游ゴシック", YuGothic, "Hiragino Kaku Gothic ProN", Meiryo, sans-serif';
      break;
    case 'type3' :
      $font_type_style = '"Times New Roman", "游明朝", "Yu Mincho", "游明朝体", "YuMincho", "ヒラギノ明朝 Pro W3", "Hiragino Mincho Pro", "HiraMinProN-W3", "HGS明朝E", "ＭＳ Ｐ明朝", "MS PMincho", serif; font-weight: 500';
      break;
  }

  switch ( $dp_options['headline_font_type'] ) {
    case 'type1' :
      $headline_font_type_style = 'Verdana, "ヒラギノ角ゴ ProN W3", "Hiragino Kaku Gothic ProN", "メイリオ", Meiryo, sans-serif';
      break;
    case 'type2' :
      $headline_font_type_style = '"Segoe UI", Verdana, "游ゴシック", YuGothic, "Hiragino Kaku Gothic ProN", Meiryo, sans-serif';
      break;
    case 'type3' :
      $headline_font_type_style = '"Times New Roman", "游明朝", "Yu Mincho", "游明朝体", "YuMincho", "ヒラギノ明朝 Pro W3", "Hiragino Mincho Pro", "HiraMinProN-W3", "HGS明朝E", "ＭＳ Ｐ明朝", "MS PMincho", serif; font-weight: 500';
      break;
  }

  if ( is_mobile() ) {
    $header_logo_type = $dp_options['sp_header_use_logo_image'];
    $header_logo_color = $dp_options['sp_header_logo_color']; 
    $header_logo_font_size = $dp_options['sp_header_logo_font_size']; 
  } else {
    $header_logo_type = $dp_options['header_use_logo_image'];
    $header_logo_color = $dp_options['header_logo_color']; 
    $header_logo_font_size = $dp_options['header_logo_font_size']; 
  }
 
  $keyframes_styles = array();
  $inline_styles = array();

  // Primary color
  $inline_styles[] = array(
    'selectors' => array(
      '.c-comment__form-submit:hover',
      '.c-pw__btn:hover', 
      '.p-article01__cat:hover',
      '.p-article11__cat:hover',
      '.p-block02 .slick-arrow:hover',
      '.p-block03 .slick-arrow:hover',
      '.p-cb__item-btn a:hover',
      '.p-entry__cat:hover',
      '.p-entry__date',
      '.p-index-news__topic-pager-item.is-active a',
      '.p-index-news__topic-pager-item a:hover',
      '.p-nav02__item a:hover',
      '.p-readmore__btn:hover',
      '.p-page-links > span', 
      '.p-pagetop a:hover', 
      '.p-page-links a:hover',
      '.p-pager__item a:hover',
      '.p-pager__item span',
      '.p-post-list03 .slick-arrow:hover',
      '.p-recommended-plan__btn a:hover',
    ),
    'properties' => sprintf( 'background: %s', esc_html( $primary_color ) )
  );
  $inline_styles[] = array(
    'selectors' => array(
      '.p-article01__title a:hover',
      '.p-article02:hover .p-article02__title',
      '.p-article03:hover .p-article03__title',
      '.p-article06__title a:hover',
      '.p-article08:hover .p-article08__title',
      '.p-article10__title a:hover',
      '.p-breadcrumb a:hover', 
      '.p-room-meta dt',
      '.p-section-nav__item:hover .p-section-nav__item-title',
      '.p-social-nav__item a:hover',
      '.p-spring-info dt',
      '.p-vertical'
    ),
    'properties' => sprintf( 'color: %s', esc_html( $primary_color ) )
  );

  // Secondary color
  $inline_styles[] = array(
    'selectors' => array(
      '.c-pw__btn',
      '.p-entry__cat',
      '.p-article01__cat',
      '.p-article11__cat',
      '.p-block02 .slick-arrow',
      '.p-block03 .slick-arrow',
      '.p-cb__item-btn a',
      '.p-copyright',
      '.p-headline',
      '.p-nav02__item a',
      '.p-readmore__btn',
      '.p-page-links a',
      '.p-pager__item a',
      '.p-post-list03 .slick-arrow',
      '.p-recommended-plan__btn a',
      '.p-widget__title', 
    ),
    'properties' => sprintf( 'background: %s', esc_html( $secondary_color ) )
  );

  // Content link color
  $inline_styles[] = array(
    'selectors' => '.p-entry__body a',
    'properties' => sprintf( 'color: %s', esc_html( $dp_options['content_link_color'] ) )
  );

  // Font type
  $inline_styles[] = array(
    'selectors' => array( 'body' ),
    'properties' => sprintf( 'font-family: %s', $font_type_style )
  );

  // Headline font type
  $inline_styles[] = array(
    'selectors' => array(
      '.c-logo',
      '.p-entry__title',
      '.p-page-header__headline',
      '.p-index-blog__title',
      '.p-index-news__col-title',
      '.p-index-slider__item-title',
      '.p-page-header__title',
      '.p-archive-header__title',
      '.p-plan__title',
      '.p-recommended-plan__title',
      '.p-section-header__title',
      '.p-section-header__headline',
      '.p-vertical',
      '.p-vertical-block',
      '.p-visual__title'
    ),
    'properties' => sprintf( 'font-family: %s', $headline_font_type_style )
  );

  // Load icon
  if ( $dp_options['use_load_icon'] ) {

    $inline_styles[] = array(
      'selectors' => '.p-page-header__title',
      'properties' => sprintf( 'transition-delay: %ds', intval( $dp_options['load_time'] ) )
    );

  }

  // Hover effect
  if ( 'type1' === $dp_options['hover_type'] ) {

    $inline_styles[] = array(
      'selectors' => '.p-hover-effect--type1:hover img',
      'properties' => array(
        sprintf( '-webkit-transform: scale(%s)', esc_html( $dp_options['hover1_zoom'] ) ),
        sprintf( 'transform: scale(%s)', esc_html( $dp_options['hover1_zoom'] ) )
      )
    );

  } elseif ( 'type2' === $dp_options['hover_type'] ) {

    $inline_styles[] = array(
      'selectors' => '.p-hover-effect--type2:hover img',
      'properties' => sprintf( 'opacity:%s', esc_html( $dp_options['hover2_opacity'] ) )
    );

    if ( 'type1' === $dp_options['hover2_direct'] ) {

      $inline_styles[] = array(
        'selectors' => '.p-hover-effect--type2 img',
        'properties' => array(
          'margin-left: 15px',
          '-webkit-transform: scale(1.3) translate3d(-15px, 0, 0)',
          'transform: scale(1.3) translate3d(-15px, 0, 0)'
        )
      );
      $inline_styles[] = array(
        'selectors' => '.p-author__img.p-hover-effect--type2 img',
        'properties' => array(
          'margin-left: 5px',
          '-webkit-transform: scale(1.3) translate3d(-5px, 0, 0)',
          'transform: scale(1.3) translate3d(-5px, 0, 0)'
        )
      );

    } else {

      $inline_styles[] = array(
        'selectors' => '.p-hover-effect--type2 img',
        'properties' => array(
          'margin-left: -15px',
          '-webkit-transform: scale(1.3) translate3d(15px, 0, 0)',
          'transform: scale(1.3) translate3d(15px, 0, 0)'
        )
      );
      $inline_styles[] = array(
        'selectors' => '.p-author__img.p-hover-effect--type2 img',
        'properties' => array(
          'margin-left: -5px',
          '-webkit-transform: scale(1.3) translate3d(5px, 0, 0)',
          'transform: scale(1.3) translate3d(5px, 0, 0)'
        )
      );

    }

  } else { // Hover type3

    $inline_styles[] = array(
      'selectors' => array(
        '.p-hover-effect--type3',
        '.p-hover-effect--type3 .p-article02__img',
        '.p-hover-effect--type3 .p-article04__img',
        '.p-hover-effect--type3 .p-article07__img',
        '.p-hover-effect--type3 .p-article09__img',
        '.p-hover-effect--type3 .p-nav01__item-img',
        '.p-hover-effect--type3 .p-section-nav__item-img'
      ),
      'properties' => sprintf( 'background: %s', esc_html( $dp_options['hover3_bgcolor'] ) )
    );
    $inline_styles[] = array(
      'selectors' => '.p-hover-effect--type3:hover img',
      'properties' => sprintf( 'opacity: %s', esc_html( $dp_options['hover3_opacity'] ) )
    );

  }

  // Logo
  if ( 'type1' === $header_logo_type ) {
    $inline_styles[] = array(
      'selectors' => '.l-header__logo a',
      'properties' => array(
        sprintf( 'color: %s', esc_html( $header_logo_color ) ),
        sprintf( 'font-size: %dpx', esc_html( $header_logo_font_size ) )
      )
    );
  }

  if ( 'type1' === $dp_options['footer_use_logo_image'] ) {
    $inline_styles[] = array(
      'selectors' => '.l-footer__logo',
      'properties' => array(
        sprintf( 'font-size: %dpx', esc_html( $dp_options['footer_logo_font_size'] ) )
      )
    );
  }

  // Header
  $inline_styles[] = array(
    'selectors' => '.l-header',
    'properties' => sprintf( 'background: rgba(%s, %s)', esc_html( implode( ', ', hex2rgb( $dp_options['header_bg'] ) ) ), esc_html( $dp_options['header_bg_opacity'] ) )
  );
  $inline_styles[] = array(
    'selectors' => '.p-global-nav a',
    'properties' => sprintf( 'color: %s', esc_html( $dp_options['gnav_color'] ) )
  );
  $inline_styles[] = array(
    'selectors' => '.p-global-nav a:hover',
    'properties' => array(
      sprintf( 'background: %s', esc_html( $dp_options['gnav_bg_hover'] ) ),
      sprintf( 'color: %s', esc_html( $dp_options['gnav_color_hover'] ) )
    )
  );
  $inline_styles[] = array(
    'selectors' => '.p-global-nav .sub-menu a',
    'properties' => array(
      sprintf( 'background: %s', esc_html( $dp_options['gnav_sub_bg'] ) ),
      sprintf( 'color: %s', esc_html( $dp_options['gnav_sub_color'] ) )
    )
  );
  $inline_styles[] = array(
    'selectors' => '.p-global-nav .sub-menu a:hover',
    'properties' => array(
      sprintf( 'background: %s', esc_html( $dp_options['gnav_sub_bg_hover'] ) ),
      sprintf( 'color: %s', esc_html( $dp_options['gnav_sub_color_hover'] ) )
    )
  );

  // Footer
  $inline_styles[] = array(
    'selectors' => '.p-footer-slider',
    'properties' => sprintf( 'background: %s', esc_html( $dp_options['footer_slider_bg'] ) )
  );
  $inline_styles[] = array(
    'selectors' => '.p-info',
    'properties' => sprintf( 'color: %s', esc_html( $dp_options['footer_info_color'] ) )
  );
  $inline_styles[] = array(
    'selectors' => '.p-info__logo',
    'properties' => sprintf( 'font-size: %spx', esc_html( $dp_options['footer_logo_font_size'] ) )
  );
  $inline_styles[] = array(
    'selectors' => '.p-info__btn',
    'properties' => array(
      sprintf( 'background: %s', esc_html( $dp_options['footer_reservation_btn_bg'] ) ),
      sprintf( 'color: %s', esc_html( $dp_options['footer_reservation_btn_color'] ) )
    )
  );
  $inline_styles[] = array(
    'selectors' => '.p-info__btn:hover',
    'properties' => array(
      sprintf( 'background: %s', esc_html( $dp_options['footer_reservation_btn_bg_hover'] ) ),
      sprintf( 'color: %s', esc_html( $dp_options['footer_reservation_btn_color_hover'] ) )
    )
  );
  $inline_styles[] = array(
    'selectors' => '.p-footer-nav',
    'properties' => array(
      sprintf( 'background: %s', esc_html( $dp_options['footer_menu_bg'] ) ),
      sprintf( 'color: %s', esc_html( $dp_options['footer_menu_color'] ) )
    )
  );
  $inline_styles[] = array(
    'selectors' => '.p-footer-nav a',
    'properties' => sprintf( 'color: %s', esc_html( $dp_options['footer_menu_color'] ) )
  );
  $inline_styles[] = array(
    'selectors' => '.p-footer-nav a:hover',
    'properties' => sprintf( 'color: %s', esc_html( $dp_options['footer_menu_color_hover'] ) )
  );

  if ( is_front_page() ) {

    $inline_styles[] = array(
      'selectors' => '.p-index-slider__arrow',
      'properties' => sprintf( 'color: %s', esc_html( $dp_options['hero_header_arrow_color'] ) )
    );

    for ( $i = 1; $i <= 3; $i++ ) {

      $inline_styles[] = array(
        'selectors' => '.p-index-slider__nav-item a',
        'properties' => sprintf( 'font-size: %spx', esc_html( $dp_options['hero_header_btn_font_size'] ) )
      );
      $inline_styles[] = array(
        'selectors' => ".p-index-slider__nav-item:nth-child({$i}) a",
        'properties' => array(
          sprintf( 'background: %s', esc_html( $dp_options['hero_header_btn_bg' . $i] ) ),
          sprintf( 'color: %s', esc_html( $dp_options['hero_header_btn_color' . $i] ) )
        )
      );
      $inline_styles[] = array(
        'selectors' => ".p-index-slider__nav-item:nth-child({$i}) a:hover",
        'properties' => array(
          sprintf( 'background: %s', esc_html( $dp_options['hero_header_btn_bg_hover' . $i] ) ),
          sprintf( 'color: %s', esc_html( $dp_options['hero_header_btn_color_hover' . $i] ) )
        )
      );

      $keyframes_styles[] = array(
        'name' => "sliderContentAnimation{$i}",
        'from' => array( 
          'opacity: 0',
          sprintf( 'text-shadow: 0 0 20px %s', esc_html( $dp_options['hero_header_color' . $i] ) )
        ),
        'to' => array(
          'opacity: 1',
          sprintf( 'text-shadow: 0 0 0 %s', esc_html( $dp_options['hero_header_color' . $i] ) )
        )
      );

      $inline_styles[] = array(
        'selectors' => ".p-index-slider__item:nth-child({$i})",
        'properties' => sprintf( 'color: %s', esc_html( $dp_options['hero_header_color' . $i] ) )
      );
      $inline_styles[] = array(
        'selectors' => ".p-index-slider__item:nth-child({$i}) .p-index-slider__item-title",
        'properties' => sprintf( 'font-size: %spx', esc_html( $dp_options['hero_header_catch_font_size' . $i] ) )
      );
      $inline_styles[] = array(
        'selectors' => ".p-index-slider__item:nth-child({$i}).slick-active .p-index-slider__item-title",
        'properties' => array(
          "animation: sliderContentAnimation{$i} 1.2s ease forwards 2s",
          'transform: translateZ(0)'
        )
      );
      $inline_styles[] = array(
        'selectors' => ".p-index-slider__item:nth-child({$i}) .p-index-slider__item-desc",
        'properties' => sprintf( 'font-size: %spx', esc_html( $dp_options['hero_header_desc_font_size' . $i] ) )
      );
      $inline_styles[] = array(
        'selectors' => ".p-index-slider__item:nth-child({$i}).slick-active .p-index-slider__item-desc",
        'properties' => array(
          "animation: sliderContentAnimation{$i} 1.2s ease forwards 3s",
          'transform: translateZ(0)'
        )
      );

      if ( 'type1' === $dp_options['hero_header_type' . $i] ) {

        if ( 'type1' === $dp_options['hero_header_effect_type' . $i] ) {

          $inline_styles[] = array(
            'selectors' => ".p-index-slider__item:nth-child({$i}).is-active .p-index-slider__item-img",
            'properties' => array(
              sprintf( 'animation: slideAnimation1 %ds linear 1 0s', absint( $dp_options['hero_header_speed'] ) + 2 ),
              'transform: translateZ(0)'
            )
          );

        } elseif ( 'type2' === $dp_options['hero_header_effect_type' . $i] ) {

          $inline_styles[] = array(
            'selectors' => ".p-index-slider__item:nth-child({$i}).is-active .p-index-slider__item-img",
            'properties' => array(
              sprintf( 'animation: slideAnimation2 %ds linear 1 0s', absint( $dp_options['hero_header_speed'] ) + 2 ),
              'transform: translateZ(0)'
            )
          );

        }
      }
    }

    // Contents builder
    foreach ( $dp_options['contents_builder'] as $key => $value ) {
      if ( 'section' === $value['cb_content_select'] ) {

          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-section-header__upper",
            'properties' => array(
              sprintf( 'background-image: url(%s)', wp_get_attachment_url( $value['cb_section_header_img'] ) ),
              sprintf( 'color: %s', esc_html( $value['cb_section_header_color'] ) )
            )
          );
          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-section-header__title",
            'properties' => array(
              sprintf( 'font-size: %spx', esc_html( $value['cb_section_header_font_size'] ) ),
              sprintf( 'text-shadow: 0 0 20px %s', esc_html( $value['cb_section_header_color'] ) )
            )
          );
          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-section-header__title.is-inview",
            'properties' => sprintf( 'text-shadow: 0 0 0 %s', esc_html( $value['cb_section_header_color'] ) )
          );
          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-section-header__headline",
            'properties' => array(
              sprintf( 'background: %s', esc_html( $value['cb_section_headline_bg'] ) ),
              sprintf( 'color: %s', esc_html( $value['cb_section_headline_color'] ) ),
              sprintf( 'font-size: %spx', esc_html( $value['cb_section_headline_font_size'] ) )
            )
          );

          if ( 'type1' === $value['cb_section_type'] ) {

            $inline_styles[] = array(
              'selectors' => "#cb_{$key} .p-block04__item-content",
              'properties' => array( 
                sprintf( 'background: %s', esc_html( $value['cb_section_type1_block_bg'] ) ),
                sprintf( 'color: %s', esc_html( $value['cb_section_type1_block_color'] ) )
              )
            );
            $inline_styles[] = array(
              'selectors' => "#cb_{$key} .p-block04__item-btn",
              'properties' => array( 
                sprintf( 'background: %s', esc_html( $value['cb_section_type1_block_btn_bg'] ) ),
                sprintf( 'color: %s', esc_html( $value['cb_section_type1_block_btn_color'] ) )
              )
            );
            $inline_styles[] = array(
              'selectors' => "#cb_{$key} .p-block04__item-btn:hover",
              'properties' => array( 
                sprintf( 'background: %s', esc_html( $value['cb_section_type1_block_btn_bg_hover'] ) ),
                sprintf( 'color: %s', esc_html( $value['cb_section_type1_block_btn_color_hover'] ) )
              )
            );

          } elseif ( 'type2' === $value['cb_section_type'] || 'type3' === $value['cb_section_type'] ) {

            $inline_styles[] = array(
              'selectors' => "#cb_{$key} .p-block05__content",
              'properties' => array( 
                sprintf( 'background: %s', esc_html( $value['cb_section_' . $value['cb_section_type'] . '_block_bg'] ) ),
                sprintf( 'color: %s', esc_html( $value['cb_section_' . $value['cb_section_type'] . '_block_color'] ) )
              )
            );

          }

      } elseif ( 'recommended_plan' === $value['cb_content_select'] ) {

          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-visual",
            'properties' => array(
              sprintf( 'background-image: url(%s)', wp_get_attachment_url( $value['cb_recommended_plan_img'] ) ),
              sprintf( 'color: %s', esc_html( $value['cb_recommended_plan_header_color'] ) )
            )
          );
          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-visual__content-inner",
            'properties' => sprintf( 'text-shadow: 0 0 20px %s', esc_html( $value['cb_recommended_plan_header_color'] ) )
          );
          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-visual.is-inview .p-visual__content-inner",
            'properties' => sprintf( 'text-shadow: 0 0 0 %s', esc_html( $value['cb_recommended_plan_header_color'] ) )
          );
          $inline_styles[] = array(
            'selectors' => "#cb_{$key} .p-visual__title",
            'properties' => sprintf( 'font-size: %spx', esc_html( $value['cb_recommended_plan_header_font_size'] ) ),
          );

      }
    }

  } elseif ( is_singular( 'post' ) || is_page() ) {

    $inline_styles[] = array(
      'selectors' => '.p-entry__title',
      'properties' => sprintf( 'font-size: %dpx', esc_html( $dp_options['title_font_size'] ) )
    );
    $inline_styles[] = array(
      'selectors' => '.p-entry__body', 
      'properties' => sprintf( 'font-size: %dpx', esc_html( $dp_options['content_font_size'] ) )
    );

    // Add styles in page template
    if ( is_page() ) {

      if ( in_array( $post->page_tcd_template_type, array( 'type3', 'type4', 'type5' ) ) ) {

        $inline_styles[] = array(
          'selectors' => '.p-page-header__headline', 
          'properties' => array(
            sprintf( 'background: %s', esc_html( $post->page_headline_bg ) ),
            sprintf( 'color: %s', esc_html( $post->page_headline_color ) ),
            sprintf( 'font-size: %dpx', esc_html( $post->page_headline_font_size ) )
          )
        );
      }

      if ( 'type3' === $post->page_tcd_template_type ) {

        $type3_b = $post->page_type3_b;
        $type3_d = $post->page_type3_d;

        if ( isset( $type3_b['headline1'] ) ) {

          foreach ( array_keys( $type3_b['headline1'] ) as $index ) {
            $inline_styles[] = array(
              'selectors' => "#page_type3_b_{$index}", 
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type3_b['bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type3_b['color'][$index] ) )
              )
            );
            $inline_styles[] = array(
              'selectors' => "#page_type3_b_{$index} .p-block01__item-title", 
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type3_b['headline_bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type3_b['headline_color'][$index] ) )
              )
            );
          }

        }
        $inline_styles[] = array(
          'selectors' => '.p-visual', 
          'properties' => array(
            sprintf( 'background-image: url(%s)', esc_html( wp_get_attachment_url( $post->page_type3_c_img ) ) ),
            sprintf( 'color: %s', esc_html( $post->page_type3_c_color ) ) // For IE11, Edge
          )
        );
        $inline_styles[] = array(
          'selectors' => '.p-visual__content-inner', 
          'properties' => sprintf( 'text-shadow: 0 0 20px %s', esc_html( $post->page_type3_c_color ) )
        );
        $inline_styles[] = array(
          'selectors' => '.p-visual.is-inview .p-visual__content-inner', 
          'properties' => sprintf( 'text-shadow: 0 0 0 %s', esc_html( $post->page_type3_c_color ) )
        );
        if ( isset( $type3_d['headline1'][0] ) ) {
          foreach ( array_keys( $type3_d['headline1'] ) as $index ) {
            $inline_styles[] = array(
              'selectors' => "#page_type3_d_{$index}",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type3_d['bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type3_d['color'][$index] ) )
              )
            );
            $inline_styles[] = array(
              'selectors' => "#page_type3_d_{$index} .p-block01__item-title",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type3_d['headline_bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type3_d['headline_color'][$index] ) )
              )
            );
          }
        }
        $inline_styles[] = array(
          'selectors' => '.p-vertical', 
          'properties' => sprintf( 'font-size: %dpx', absint( $post->page_type3_e_font_size ) )
        );
        $inline_styles[] = array(
          'selectors' => '.p-spring-info', 
          'properties' => sprintf( 'background: %s', esc_html( $post->page_type3_f_bg ) )
        );

      } elseif ( 'type4' === $post->page_tcd_template_type ) {

        $type4_c = $post->page_type4_c;

        if ( isset( $type4_c['header_headline'][0] ) ) {
          foreach ( array_keys( $type4_c['header_headline'] ) as $index ) {
            $inline_styles[] = array(
              'selectors' => "#js-block03-{$index} .p-vertical-block",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type4_c['header_headline_bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type4_c['header_headline_color'][$index] ) )
              )
            );
            $inline_styles[] = array(
              'selectors' => "#js-block03-{$index} .p-block03__slider-title",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type4_c['slider_headline_bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type4_c['slider_headline_color'][$index] ) )
              )
            );
          }
        }

        $inline_styles[] = array(
          'selectors' => '.p-room-meta',
          'properties' => sprintf( 'background: %s', esc_html( $post->page_type4_d_bg ) )
        );
        $inline_styles[] = array(
          'selectors' => '.p-room-meta__btn',
          'properties' => array(
            sprintf( 'background: %s', esc_html( $post->page_type4_d_4_bg ) ),
            sprintf( 'color: %s', esc_html( $post->page_type4_d_4_color ) ),
          )
        );
        $inline_styles[] = array(
          'selectors' => '.p-room-meta__btn:hover',
          'properties' => array(
            sprintf( 'background: %s', esc_html( $post->page_type4_d_4_bg_hover ) ),
            sprintf( 'color: %s', esc_html( $post->page_type4_d_4_color_hover ) ),
          )
        );

      } elseif ( 'type5' === $post->page_tcd_template_type ) {

        $type5_b = $post->page_type5_b;
        $type5_d = $post->page_type5_d;

        if ( isset( $type5_b['headline'][0] ) ) {
          foreach ( array_keys( $type5_b['headline'] ) as $index ) {
            $inline_styles[] = array(
              'selectors' => "#page_type5_b_{$index}",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type5_b['bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type5_b['color'][$index] ) )
              )
            );
            $inline_styles[] = array(
              'selectors' => "#page_type5_b_{$index} .p-vertical-block",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type5_b['headline_bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type5_b['headline_color'][$index] ) )
              )
            );
          }
        }
        $inline_styles[] = array(
          'selectors' => '#page_type5_c',
          'properties' => array(
            sprintf( 'background-image: url(%s)', esc_html( wp_get_attachment_url( $post->page_type5_c_img ) ) ),
            sprintf( 'color: %s', esc_html( $post->page_type5_c_color ) )
          )
        );
        $inline_styles[] = array(
          'selectors' => '#page_type5_c .p-visual__content-inner',
          'properties' => sprintf( 'text-shadow: 0 0 20px %s', esc_html( $post->page_type5_c_color ) )
        );
        $inline_styles[] = array(
          'selectors' => '#page_type5_c.is-inview .p-visual__content-inner',
          'properties' => sprintf( 'text-shadow: 0 0 0 %s', esc_html( $post->page_type5_c_color ) )
        );
        if ( isset( $type5_d['headline'][0] ) ) {
          foreach ( array_keys( $type5_d['headline'] ) as $index ) {
            $inline_styles[] = array(
              'selectors' => "#page_type5_d_{$index}",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type5_d['bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type5_d['color'][$index] ) )
              )
            );
            $inline_styles[] = array(
              'selectors' => "#page_type5_d_{$index} .p-vertical-block",
              'properties' => array(
                sprintf( 'background: %s', esc_html( $type5_d['headline_bg'][$index] ) ),
                sprintf( 'color: %s', esc_html( $type5_d['headline_color'][$index] ) )
              )
            );
          }
        }
        $inline_styles[] = array(
          'selectors' => '#page_type5_e',
          'properties' => array(
            sprintf( 'background-image: url(%s)', esc_html( wp_get_attachment_url( $post->page_type5_e_img ) ) ),
            sprintf( 'color: %s', esc_html( $post->page_type5_e_color ) )
          )
        );
        $inline_styles[] = array(
          'selectors' => '#page_type5_e .p-visual__content-inner',
          'properties' => sprintf( 'text-shadow: 0 0 20px %s', esc_html( $post->page_type5_e_color ) )
        );
        $inline_styles[] = array(
          'selectors' => '#page_type5_e.is-inview .p-visual__content-inner',
          'properties' => sprintf( 'text-shadow: 0 0 0 %s', esc_html( $post->page_type5_e_color ) )
        );

      }

    }

  } elseif ( is_singular( 'news' ) ) {

    $inline_styles[] = array(
      'selectors' => '.p-entry__title',
      'properties' => sprintf( 'font-size: %dpx', esc_html( $dp_options['news_title_font_size'] ) )
    );
    $inline_styles[] = array(
      'selectors' => '.p-entry__body', 
      'properties' => sprintf( 'font-size: %dpx', esc_html( $dp_options['news_content_font_size'] ) )
    );
    $inline_styles[] = array(
      'selectors' => '.p-headline__link:hover', 
      'properties' => sprintf( 'color: %s', esc_html( $dp_options['news_archive_link_color_hover'] ) )
    );

  } elseif ( is_singular( 'plan' ) ) {

    $inline_styles[] = array(
      'selectors' => '.p-plan__title',
      'properties' => sprintf( 'font-size: %spx', esc_html( $dp_options['plan_title_font_size'] ) )
    );
    $inline_styles[] = array(
      'selectors' => array( '.p-plan__slider-nav', '.p-plan__content' ),
      'properties' => array( 
        sprintf( 'background: %s', esc_html( $dp_options['plan_bg'] ) ),
        sprintf( 'font-size: %spx', esc_html( $dp_options['plan_content_font_size'] ) )
      )
    );
    $inline_styles[] = array(
      'selectors' => '.p-plan__meta-btn',
      'properties' => array( 
        sprintf( 'background: %s', esc_html( $dp_options['plan_btn_bg'] ) ),
        sprintf( 'color: %s', esc_html( $dp_options['plan_btn_color'] ) )
      )
    );
    $inline_styles[] = array(
      'selectors' => '.p-plan__meta-btn:hover',
      'properties' => array( 
        sprintf( 'background: %s', esc_html( $dp_options['plan_btn_bg_hover'] ) ),
        sprintf( 'color: %s', esc_html( $dp_options['plan_btn_color_hover'] ) )
      )
    );

  }

  // You can add styles with 'tcd_inline_styles' filter
  $inline_styles = apply_filters( 'tcd_inline_styles', $inline_styles, $dp_options );

  $responsive_styles = array();

  $responsive_styles['max-width: 991px'][] = array(
    'selectors' => '.p-global-nav',
    'properties' => sprintf( 'background: rgba(%s, %s)', esc_html( implode( ',', hex2rgb( $dp_options['gnav_bg_sp'] ) ) ), esc_html( $dp_options['gnav_bg_opacity_sp'] ) )
  );
  $responsive_styles['max-width: 991px'][] = array(
    'selectors' => array(
      '.p-global-nav a',
      '.p-global-nav a:hover',
      '.p-global-nav .sub-menu a',
      '.p-global-nav .sub-menu a:hover',
    ),
    'properties' => sprintf( 'color: %s', esc_html( $dp_options['gnav_color_sp'] ) )
  );
  $responsive_styles['max-width: 991px'][] = array(
    'selectors' => '.p-global-nav .menu-item-has-children > a > .sub-menu-toggle::before',
    'properties' => sprintf( 'border-color: %s', esc_html( $dp_options['gnav_color_sp'] ) )
  );

  // You can add responsive styles with 'tcd_responsive_styles' filter
  $responsive_styles = apply_filters( 'tcd_responsive_styles', $responsive_styles, $dp_options );

  echo '<style>' . "\n";

  $output = '';

  // Add $keyframes_styles to $output
  foreach ( $keyframes_styles as $style ) {
    $from_properties = is_array( $style['from'] ) ? implode( ';', $style['from'] ) : $style['from']; 
    $to_properties = is_array( $style['to'] ) ? implode( ';', $style['to'] ) : $style['to']; 
    $output .= sprintf( '@keyframes %s{from{%s}to{%s}}', $style['name'], $from_properties, $to_properties );
  }

  // Add $inline_styles to $output
  foreach ( $inline_styles as $style ) {
    $selectors = is_array( $style['selectors'] ) ? implode( ',', $style['selectors'] ) : $style['selectors']; 
    $properties = is_array( $style['properties'] ) ? implode( ';', $style['properties'] ) : $style['properties']; 
    $output .= sprintf( '%s{%s}', $selectors, $properties ); 
  }

  // Add $responsive_styles to $output
  foreach ( $responsive_styles as $media_query => $styles ) {

    $output .= sprintf( '@media screen and (%s) {', $media_query );

    foreach ( $styles as $style ) {
      $selectors = is_array( $style['selectors'] ) ? implode( ',', $style['selectors'] ) : $style['selectors']; 
      $properties = is_array( $style['properties'] ) ? implode( ';', $style['properties'] ) : $style['properties']; 
      $output .= sprintf( '%s{%s}', $selectors, $properties ); 
    }

    $output .= '}';
  }

  if ( $output ) { echo $output; }
  
  do_action( 'tcd_head', $dp_options );

  // Custom CSS
  if ( $dp_options['css_code'] ) { echo $dp_options['css_code']; }

  echo '</style>' . "\n";

}
add_action( 'wp_head', 'kadan_inline_styless' );
