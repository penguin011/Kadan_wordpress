<?php
$dp_options = get_design_plus_option();
$recommended_plan_args = array(
  'post_type' => 'plan',
  'post_status' => 'publish',
  'posts_per_page' => 4,
  'meta_key' => 'recommended_plan',
  'meta_value' => 'on'
);
$recommended_plan = new WP_Query($recommended_plan_args);
?>
<section class="p-recommended-plan">
  <h2 class="p-recommended-plan__title">
    <?php echo esc_html($dp_options['recommended_plan_headline']); ?>
  </h2>
  <div class="p-recommended-plan__list">
    <?php
    if ($recommended_plan->have_posts()):
      while ($recommended_plan->have_posts()):
        $recommended_plan->the_post();
        if (has_excerpt()) {
          $excerpt = $post->post_excerpt;
        } elseif ($post->use_page_builder) {
          $excerpt = render_page_builder_content($post->ID);
        } else {
          $excerpt = $post->post_content;
        }
        ?>
        <article class="p-recommended-plan__list-item p-article09">
          <a href="<?php the_permalink(); ?>" class="p-hover-effect--<?php echo esc_attr($dp_options['hover_type']); ?>"
            title="<?php the_title(); ?>">
            <div class="p-article09__img">
              <?php
              if (has_post_thumbnail()) {
                the_post_thumbnail('size5');
              } else {
                echo '<img src="' . get_template_directory_uri() . '/assets/images/594x594.gif" alt="">' . "\n";
              }
              ?>
            </div>
            <div class="p-article09__content">
              <h2 class="p-article09__title">
                <?php echo is_mobile() ? wp_trim_words(get_the_title(), 20, '...') : wp_trim_words(get_the_title(), 22, '...'); ?>
              </h2>
              <p class="p-article09__excerpt">
                <?php echo wp_trim_words($excerpt, 47, '...'); ?>
              </p>
            </div>
          </a>
        </article>
        <?php
      endwhile;
      wp_reset_postdata();
    endif;
    ?>
  </div><!-- /.p-recommended-plan__list -->
  <?php if (is_singular('plan') && $dp_options['plan_archive_link_label']): ?>
    <p class="p-recommended-plan__btn">
      <a href="<?php echo get_post_type_archive_link('plan'); ?>" class="p-btn"><?php echo esc_html($dp_options['plan_archive_link_label']); ?></a>
    </p>
  <?php endif; ?>
</section>