<?php
global $wp_query;
$dp_options = get_design_plus_option();
$args = array(
  'current' => max(1, get_query_var('paged')),
  'prev_next' => false,
  'total' => $wp_query->max_num_pages,
  'type' => 'array'
);
get_header();
?>
<?php if (have_posts()): ?>
  <div class="p-news-list">
    <?php while (have_posts()):
      the_post(); ?>
      <article class="p-news-list__item p-article07">
        <a href="<?php the_permalink(); ?>" class="p-hover-effect--<?php echo esc_attr($dp_options['hover_type']); ?>"
          title="<?php the_title(); ?>">
          <?php if (has_post_thumbnail()): ?>
            <div class="p-article07__img">
              <?php the_post_thumbnail('size2'); ?>
            </div>
          <?php endif; ?>
          <div class="p-article07__content">
            <?php if ($dp_options['news_show_date']): ?>
              <time class="p-article07__date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
            <?php endif; ?>
            <h2 class="p-article07__title">
              <?php echo is_mobile() ? wp_trim_words(get_the_title(), 20, '...') : wp_trim_words(get_the_title(), 40, '...'); ?>
            </h2>
          </div>
        </a>
      </article>
    <?php endwhile; ?>
  </div><!-- /.p-news-list -->
<?php endif; ?>
<?php if (paginate_links($args)): ?>
  <ul class="p-pager">
    <?php foreach (paginate_links($args) as $link): ?>
      <li class="p-pager__item">
        <?php echo $link; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
</div><!-- /.l-primary -->
<?php get_footer(); ?>