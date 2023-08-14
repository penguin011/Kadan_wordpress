<?php
global $wp_query;
$dp_options = get_design_plus_option();
$pager_args = array(
  'current' => max(1, get_query_var('paged')),
  'prev_next' => false,
  'total' => $wp_query->max_num_pages,
  'type' => 'array'
);
get_header();
?>
<div class="p-archive-header">
  <p class="p-archive-header__desc">
    <?php echo nl2br(esc_html($dp_options['plan_archive_desc'])); ?>
  </p>
</div>
<div class="p-plan-list">
  <div class="p-plan-list__inner">
    <?php
    if (have_posts()):
      while (have_posts()):
        the_post();
        if (has_excerpt()) {
          $excerpt = $post->post_excerpt;
        } elseif ($post->use_page_builder) {
          $excerpt = render_page_builder_content($post->ID);
        } else {
          $excerpt = $post->post_content;
        }
        ?>
        <article class="p-plan-list__item p-article09">
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
    endif;
    ?>
  </div><!-- /.p-plan-list__inner -->
  <?php if (paginate_links($pager_args)): ?>
    <ul class="p-pager">
      <?php foreach (paginate_links($pager_args) as $link): ?>
        <li class="p-pager__item">
          <?php echo $link; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div><!-- /.p-plan-list -->
<?php get_template_part('template-parts/recommended-plan'); ?>
</div><!-- /.l-primary -->
<?php get_footer(); ?>