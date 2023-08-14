<?php
$dp_options = get_design_plus_option();

// Get footer slider items
$slider_args = array(
  'post_type' => $dp_options['footer_slider_type'],
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'rand'
);
$slider_query = new WP_Query($slider_args);

// Whether footer information is displayed or not
$display_footer_info = is_front_page() ? $dp_options['display_footer_info_on_front'] : $dp_options['display_footer_info_on_sub'];

// Footer menu arguments
$menu_args = array(
  'fallback_cb' => '',
  // Hide the menu area if the footer menu is not registered
  'items_wrap' => '<ul class="%2$s">%3$s</ul>',
  'menu_class' => 'p-footer-nav',
  'theme_location' => 'footer'
);
?>
</div><!-- /.l-contents -->
</main>
<footer class="l-footer">

  <div class="p-footer-slider">
    <?php if ($slider_query->have_posts()): ?>
      <div id="js-footer-slider__inner" class="p-footer-slider__inner l-inner">
        <?php
        while ($slider_query->have_posts()):
          $slider_query->the_post();
          ?>
          <article class="p-article02 p-footer-slider__item">
            <a class="p-hover-effect--<?php echo esc_attr($dp_options['hover_type']); ?>" href="<?php the_permalink(); ?>"
              title="<?php the_title(); ?>">
              <div class="p-article02__img">
                <?php
                if (has_post_thumbnail()) {
                  the_post_thumbnail('size1');
                } else {
                  echo '<img src="' . get_template_directory_uri() . '/assets/images/592x410.gif" alt="">' . "\n";
                }
                ?>
              </div>
              <h2 class="p-article02__title">
                <?php echo is_mobile() ? wp_trim_words(get_the_title(), 23, '...') : wp_trim_words(get_the_title(), 40, '...'); ?>
              </h2>
            </a>
          </article>
          <?php
        endwhile;
        wp_reset_postdata();
        ?>
      </div><!-- /.p-footer-slider__inner -->
    <?php endif; ?>
  </div><!-- /.p-footer-slider -->
  <?php if ($display_footer_info): ?>
    <div class="p-info">
      <div class="p-info__inner l-inner">
        <?php if ($dp_options['display_footer_info_col1']): ?>
          <div class="p-info__col">
            <div class="p-info__logo c-logo<?php if ($dp_options['footer_logo_image_retina']) {
              echo ' c-logo--retina';
            } ?>">
              <?php if ('type1' === $dp_options['footer_use_logo_image']): // Use text ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
              <?php else: // Use image ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                  <img src="<?php echo esc_attr(wp_get_attachment_url($dp_options['footer_logo_image'])); ?>"
                    alt="<?php bloginfo('name'); ?>">
                </a>
              <?php endif; ?>
            </div>
            <p class="p-info__address">
              <?php echo nl2br(esc_html($dp_options['footer_address'])); ?>
            </p>
            <ul class="p-info__social p-social-nav">
              <?php if ($dp_options['facebook_url']): ?>
                <li class="p-social-nav__item p-social-nav__item--facebook">
                  <a href="<?php echo esc_url($dp_options['facebook_url']); ?>" target="_blank"></a>
                </li>
              <?php endif; ?>
              <?php if ($dp_options['twitter_url']): ?>
                <li class="p-social-nav__item p-social-nav__item--twitter">
                  <a href="<?php echo esc_url($dp_options['twitter_url']); ?>" target="_blank"></a>
                </li>
              <?php endif; ?>
              <?php if ($dp_options['insta_url']): ?>
                <li class="p-social-nav__item p-social-nav__item--instagram">
                  <a href="<?php echo esc_url($dp_options['insta_url']); ?>" target="_blank"></a>
                </li>
              <?php endif; ?>
              <?php if ($dp_options['pinterest_url']): ?>
                <li class="p-social-nav__item p-social-nav__item--pinterest">
                  <a href="<?php echo esc_url($dp_options['pinterest_url']); ?>" target="_blank"></a>
                </li>
              <?php endif; ?>
              <?php if ($dp_options['mail_url']): ?>
                <li class="p-social-nav__item p-social-nav__item--mail">
                  <a href="mailto:<?php echo sanitize_email($dp_options['mail_url']); ?>"></a>
                </li>
              <?php endif; ?>
              <?php if ($dp_options['show_rss']): ?>
                <li class="p-social-nav__item p-social-nav__item--rss">
                  <a href="<?php bloginfo('rss2_url'); ?>" target="_blank"></a>
                </li>
              <?php endif; ?>
            </ul>
          </div><!-- /.p-info__col -->
        <?php endif; ?>
        <?php if ($dp_options['display_footer_info_col2']): ?>
          <div class="p-info__col">
            <div class="p-info__text">
              <p>
                <?php echo nl2br($dp_options['footer_desc']); // No escape ?>
              </p>
            </div>
          </div><!-- /.p-info__col -->
        <?php endif; ?>
        <?php if ($dp_options['display_footer_info_col3']): ?>
          <div class="p-info__col">
            <a href="https://staynavi.direct/campaign/gototravel/?facility_id=280177" target="_blank"><img
                src="http://yamamotokan.com/wp/wp-content/uploads/2020/08/300-200_D@2x.png" width="200" alt="goto"></a>
            <p class="p-info__text">
              <?php echo nl2br(esc_html($dp_options['footer_reservation_desc'])); ?>
            </p>
            <?php if ($dp_options['footer_reservation_btn_label']): ?>
              <a class="p-info__btn p-btn" href="<?php echo esc_url($dp_options['footer_reservation_btn_url']); ?>" <?php if ($dp_options['footer_reservation_btn_target']) {
                   echo ' target="_blank"';
                 } ?>><?php echo esc_html($dp_options['footer_reservation_btn_label']); ?></a>
            <?php endif; ?>
          </div><!-- /.p-info__col -->

        <?php endif; ?>
      </div><!-- /.p-info__inner -->
    </div><!-- /.p-info -->
  <?php endif; ?>
  <?php wp_nav_menu($menu_args); ?>
  <p class="p-copyright">
    <small>Copyright &copy;
      <?php bloginfo('name'); ?> All Rights Reserved.
    </small>
  </p>
  <div id="js-pagetop" class="p-pagetop"><a href="#"></a></div>
</footer>
<?php wp_footer(); ?>
</body>

</html>