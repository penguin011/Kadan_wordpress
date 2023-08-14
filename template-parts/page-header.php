<?php
$dp_options = get_design_plus_option();
$tag = 'h1';

if ( is_post_type_archive( 'news' ) ) {
  $ph_img = $dp_options['news_ph_img'];
  $ph_color = $dp_options['news_ph_color'];
  $ph_font_size = $dp_options['news_ph_font_size'];
  $ph_title = $dp_options['news_ph_title'];
  $ph_writing_type = $dp_options['news_ph_writing_type'];
} elseif ( is_post_type_archive( 'plan' ) ) {
  $ph_img = $dp_options['plan_ph_img'];
  $ph_color = $dp_options['plan_ph_color'];
  $ph_font_size = $dp_options['plan_ph_font_size'];
  $ph_title = $dp_options['plan_ph_title'];
  $ph_writing_type = $dp_options['plan_ph_writing_type'];
} elseif ( is_404() ) {
  $ph_img = $dp_options['404_ph_img'];
  $ph_color = $dp_options['404_ph_color'];
  $ph_font_size = $dp_options['404_ph_font_size'];
  $ph_title = $dp_options['404_ph_title'];
  $ph_writing_type = $dp_options['404_ph_writing_type'];
} elseif ( is_page() ) {
  $ph_img = $post->ph_img;
  $ph_color = $post->ph_color;
  $ph_font_size = $post->ph_font_size;
  $ph_title = $post->ph_title;
  $ph_writing_type = $post->ph_writing_type;
  $use_page_template = in_array( $post->page_tcd_template_type, array( 'type3', 'type4', 'type5' ) ) ? true : false;
  if ( $use_page_template ) {
    $tag = 'div';
  }

} else {
  $ph_img = $dp_options['ph_img'];
  $ph_color = $dp_options['ph_color'];
  $ph_font_size = $dp_options['ph_font_size'];
  $ph_title = $dp_options['ph_title'];
  $ph_writing_type = $dp_options['ph_writing_type'];
  
  if ( ! is_home() ) {
    $tag = 'div';
  }
}

$ph_title_classes = 'p-page-header__title';

// Add .p-page-header__title--with-padding class if the opacity of the background color of the header is 1.
if ( $dp_options['header_bg_opacity'] == 1 ) {
  $ph_title_classes .= ' p-page-header__title--with-padding';
}

// Add .p-page-header__title--vertical class if writing type is vertical writing.
if ( 'type1' === $ph_writing_type ) {
  $ph_title_classes .= ' p-page-header__title--vertical';
}
?>
  <header class="p-page-header<?php if ( is_page() && ! $use_page_template ) { echo ' mb0'; } ?>">
    <div class="p-page-header__upper">
      <<?php echo $tag; ?> class="<?php echo esc_attr( $ph_title_classes ); ?>"><span><?php echo nl2br( esc_html( $ph_title ) ); ?></span></<?php echo $tag; ?>>
    </div>
    <?php if ( is_page() && $use_page_template && ( $post->page_headline || $post->page_desc ) ) : ?>
    <div class="p-page-header__lower l-inner">
      <?php if ( $post->page_headline ) : ?>
      <h1 class="p-page-header__headline<?php if ( 'type1' === $post->page_headline_writing_mode ) { echo ' p-page-header__headline--vertical'; } ?>">
        <span class="p-page-header__headline-inner"><?php echo esc_html( $post->page_headline ); ?></span>
      </h1>
      <?php endif; ?>
      <?php if ( $post->page_desc ) : ?>
      <div class="p-page-header__desc p-page-header__desc--vertical">
        <p class="p-page-header__desc-inner"><?php echo nl2br( esc_html( $post->page_desc ) ); ?></p>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </header>
