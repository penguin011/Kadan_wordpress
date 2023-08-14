<?php
$dp_options = get_design_plus_option();
get_header();
?>
<div id="js-index-slider" class="p-index-slider"
  data-speed="<?php echo absint($dp_options['hero_header_speed']) * 1000; ?>">
  <?php
  for ($i = 1; $i <= 3; $i++):

    $attachment = '';

    if (wp_is_mobile()) {
      $attachment = $dp_options['hero_header_img_sp' . $i];
    } elseif ('type1' === $dp_options['hero_header_type' . $i]) {
      $attachment = $dp_options['hero_header_img' . $i];
    } elseif ('type2' === $dp_options['hero_header_type' . $i]) {
      $attachment = $dp_options['hero_header_video' . $i];
    } elseif ('type3' === $dp_options['hero_header_type' . $i]) {
      $attachment = $dp_options['hero_header_youtube' . $i];
    }

    if (!$attachment)
      break;
    ?>
    <div class="p-index-slider__item">
      <div class="p-index-slider__item-content">
        <div<?php if ('type1' === $dp_options['hero_header_writing_type' . $i]) {
          echo ' class="p-index-slider__item-content-inner"';
        } ?>>
          <?php if ($dp_options['hero_header_catch' . $i]): ?>
            <h2 class="p-index-slider__item-title">
              <?php echo nl2br(esc_html($dp_options['hero_header_catch' . $i])); ?>
            </h2>
          <?php endif; ?>
          <?php if ($dp_options['hero_header_desc' . $i]): ?>
            <p class="p-index-slider__item-desc">
              <?php echo nl2br(esc_html($dp_options['hero_header_desc' . $i])); ?>
            </p>
          <?php endif; ?>
      </div>
    </div>
    <?php if (wp_is_mobile() || 'type1' === $dp_options['hero_header_type' . $i]): // Image ?>
      <div class="p-index-slider__item-img<?php if (!wp_is_mobile()) {
        echo ' p-index-slider__item-img--fixed';
      } ?>"
        style="background-image: url(<?php echo esc_attr(wp_get_attachment_url($attachment)); ?>);"></div>
    <?php elseif ('type2' === $dp_options['hero_header_type' . $i]): // Video ?>
      <video class="p-index-slider__item-video">
        <source src="<?php echo esc_attr(wp_get_attachment_url($attachment)); ?>">
      </video>
      <?php
    else: // YouTube 
      $origin = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
      ?>
      <iframe id="js-index-slider__item-youtube<?php echo $i; ?>"
        class="p-index-slider__item-youtube js-index-slider__item-youtube" type="text/html"
        src="https://www.youtube.com/embed/<?php echo esc_attr($attachment); ?>?enablejsapi=1&origin=<?php echo esc_url($origin); ?>&autoplay=0&controls=0&loop=1&showinfo=0&rel=0"
        frameborder="0"></iframe>
    <?php endif; ?>
  </div>
<?php endfor; ?>
<ul id="js-index-slider__nav" class="p-index-slider__nav">
  <?php
  for ($i = 1; $i <= 3; $i++):
    if ($dp_options['hero_header_btn_label' . $i]):
      ?>
      <li class="p-index-slider__nav-item">
        <a href="<?php echo esc_url($dp_options['hero_header_btn_url' . $i]); ?>" <?php if ($dp_options['hero_header_btn_target' . $i]) {
                 echo ' target="_blank"';
               } ?>><?php echo esc_html($dp_options['hero_header_btn_label' . $i]); ?></a>
      </li>
      <?php
    endif;
  endfor;
  ?>
</ul>
<a href="#js-cb" id="js-index-slider__arrow" class="p-index-slider__arrow"></a>
</div><!-- /.p-index-slider -->
<div id="js-cb" class="p-cb">
  <?php
  foreach ($dp_options['contents_builder'] as $key => $value):
    if ('news' === $value['cb_content_select'] && $value['cb_news_display']):
      $topic_args = array(
        'post_type' => esc_html($value['cb_news_topic_type']),
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'orderby' => 'rand',
        'meta_key' => 'topic',
        'meta_value' => 'on'
      );
      $news_args = array(
        'post_type' => 'news',
        'post_status' => 'publish',
        'posts_per_page' => 4,
        'orderby' => 'date',
        'order' => 'DESC'
      );
      $topic_query = new WP_Query($topic_args);
      $topic_dates = array_column($topic_query->posts, 'ID');
      $news_query = new WP_Query($news_args);
      ?>
      <div id="cb_<?php echo esc_attr($key); ?>" class="p-index-news p-cb__item l-inner">
        <div class="p-index-news__inner">
          <section class="p-index-news__col p-index-news__col--topic">
            <h2 class="p-index-news__col-title">
              <?php echo esc_html($value['cb_news_topic_headline']); ?>
            </h2>
            <div class="p-index-news__topic js-index-news__topic">
              <?php if ($value['cb_news_display_date']): ?>
                <p class="p-index-news__topic-date">
                  <?php for ($i = 0, $len = $topic_query->post_count; $i < $len; $i++): ?>
                    <span class="p-index-news__topic-date-inner<?php if (0 === $i) {
                      echo ' is-active';
                    } ?>"><?php echo get_the_date('Y.m.d', $topic_dates[$i]); ?></span>
                  <?php endfor; ?>
                </p>
              <?php endif; ?>
              <?php if ($topic_query->post_count > 1): ?>
                <ul class="p-index-news__topic-pager js-index-news__topic-pager">
                  <?php for ($i = 1, $len = $topic_query->post_count; $i <= $len; $i++): ?>
                    <li class="p-index-news__topic-pager-item<?php if (1 === $i) {
                      echo ' is-active';
                    } ?>">
                      <a href="#">
                        <?php echo $i; ?>
                      </a>
                    </li>
                  <?php endfor; ?>
                </ul>
              <?php endif; ?>
              <?php if ($topic_query->have_posts()): ?>
                <div class="p-index-news__topic-inner js-index-news__topic-inner"
                  data-speed="<?php echo esc_attr($value['cb_news_topic_speed'] * 1000); ?>">
                  <?php
                  while ($topic_query->have_posts()):
                    $topic_query->the_post();
                    ?>
                    <article class="p-index-news__topic-item p-article12">
                      <div class="p-article12__img">
                        <a href="<?php the_permalink(); ?>"
                          class="p-hover-effect--<?php echo esc_attr($dp_options['hover_type']); ?>">
                          <?php
                          if (has_post_thumbnail()) {
                            the_post_thumbnail('size7');
                          } else {
                            echo '<img src="' . get_template_directory_uri() . '/assets/images/848x582.gif" alt="">' . "\n";
                          }
                          ?>
                        </a>
                      </div>
                      <h3 class="p-article12__title">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo wp_trim_words(get_the_title(), 30, '...'); ?></a>
                      </h3>
                      <p class="p-article12__desc">
                        <?php echo is_mobile() ? wp_trim_words(get_the_content(), 95, '...') : wp_trim_words(get_the_content(), 109, '...'); ?>
                      </p>
                    </article>
                    <?php
                  endwhile;
                  wp_reset_postdata();
                  ?>
                </div>
              <?php endif; ?>
            </div>
          </section>
          <section class="p-index-news__col">
            <h2 class="p-index-news__col-title">
              <?php echo esc_html($value['cb_news_news_headline']); ?>
            </h2>
            <?php if ($news_query->have_posts()): ?>
              <div class="p-index-news__list">
                <?php
                while ($news_query->have_posts()):
                  $news_query->the_post();
                  ?>
                  <article class="p-index-news__list-item p-article10">
                    <a href="<?php the_permalink(); ?>"
                      class="p-article10__img p-hover-effect--<?php echo esc_attr($dp_options['hover_type']); ?>">
                      <?php
                      if (has_post_thumbnail()) {
                        the_post_thumbnail('size6');
                      } else {
                        echo '<img src="' . get_template_directory_uri() . '/assets/images/516x356.gif" alt="">' . "\n";
                      }
                      ?>
                    </a>
                    <?php if ($dp_options['news_show_date']): ?>
                      <time class="p-article10__date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
                    <?php endif; ?>
                    <h3 class="p-article10__title">
                      <a href="<?php the_permalink(); ?>"><?php echo is_mobile() ? wp_trim_words(get_the_title(), 25, '...') : wp_trim_words(get_the_title(), 30, '...'); ?></a>
                    </h3>
                  </article>
                  <?php
                endwhile;
                wp_reset_postdata();
                ?>
              </div>
            <?php endif; ?>
          </section>
        </div><!-- /.p-index-news__inner -->
        <?php if ($value['cb_news_btn_label']): ?>
          <p class="p-index-news__btn p-cb__item-btn">
            <a href="<?php echo get_post_type_archive_link('news'); ?>" class="p-btn"><?php echo esc_html($value['cb_news_btn_label']); ?></a>
          </p>
        <?php endif; ?>
      </div><!-- /.p-cb__item -->
      <?php
      // Section
    elseif ('section' === $value['cb_content_select'] && $value['cb_section_display']):

      $headline_classes = 'p-section-header__headline';

      if ('type1' === $value['cb_section_headline_writing_type']) {
        $headline_classes .= ' p-section-header__headline--vertical';
      }

      if ('type1' === $value['cb_section_type']) {
        if ('type2' === $value['cb_section_headline_layout']) {
          $headline_classes .= ' p-section-header__headline--rev';
        }
      } else { // type2, type3
        if ('type2' === $value['cb_section_' . $value['cb_section_type'] . '_layout']) {
          $headline_classes .= ' p-section-header__headline--rev';
        }
      }
      ?>
      <div id="cb_<?php echo esc_attr($key); ?>" class="p-cb__item">
        <div class="p-section-header">
          <div class="p-section-header__upper">
            <h2
              class="p-section-header__title<?php if ('type1' === $value['cb_section_header_writing_type']) {
                echo ' p-section-header__title--vertical';
              } ?>">
              <span>
                <?php echo nl2br(esc_html($value['cb_section_header_title'])); ?>
              </span>
            </h2>
          </div>
          <?php if ('type1' === $value['cb_section_type']): ?>
            <div class="p-section-header__lower l-inner">
              <h3 class="<?php echo $headline_classes; ?>">
                <span class="p-section-header__headline-inner">
                  <?php echo esc_html($value['cb_section_headline']); ?>
                </span>
              </h3>
              <div class="p-section-header__desc p-section-header__desc--vertical">
                <p class="p-section-header__desc-inner">
                  <?php echo nl2br(esc_html($value['cb_section_desc'])); ?>
                </p>
              </div>
            </div>
          <?php else: // type2, type3 ?>
            <div class="p-section-header__lower l-inner pt0">
              <h3 class="<?php echo esc_attr($headline_classes); ?>">
                <span class="p-section-header__headline-inner">
                  <?php echo esc_html($value['cb_section_headline']); ?>
                </span>
              </h3>
            </div>
          <?php endif; ?>
        </div><!-- /.p-section-header -->
        <?php if ('type1' === $value['cb_section_type']): ?>
          <div
            class="l-inner p-block04<?php if ('type2' === $value['cb_section_type1_block_layout']) {
              echo ' p-block04--rev';
            } ?>">
            <?php for ($i = 1; $i <= 3; $i++): ?>
              <div class="p-block04__item">
                <div class="p-block04__item-img">
                  <img
                    src="<?php echo esc_attr(wp_get_attachment_url($value['cb_section_type1_block_img' . ($i * 2 - 1)])); ?>"
                    alt="">
                </div>
                <div class="p-block04__item-content">
                  <div class="p-block04__item-content-inner">
                    <p class="p-block04__item-desc">
                      <?php echo nl2br(esc_html($value['cb_section_type1_block_text' . ($i * 2)])); ?>
                    </p>
                    <?php if ($value['cb_section_type1_block_btn_label' . ($i * 2)]): ?>
                      <a href="<?php echo esc_url($value['cb_section_type1_block_btn_url' . ($i * 2)]); ?>"
                        class="p-block04__item-btn p-btn"><?php echo esc_html($value['cb_section_type1_block_btn_label' . ($i * 2)]); ?></a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endfor; ?>
          </div><!-- /.p-block04 -->
        <?php else: // type2, type3 ?>
          <div
            class="p-index-section__content l-inner<?php if ('type2' === $value['cb_section_' . $value['cb_section_type'] . '_layout']) {
              echo ' p-index-section__content--rev';
            } ?>">
            <div class="p-index-section__content-desc">
              <p class="p-index-section__content-desc-inner">
                <?php echo nl2br(esc_html($value['cb_section_desc'])); ?>
              </p>
            </div>
            <?php if ('type2' === $value['cb_section_type']): ?>
              <div
                class="p-index-section__content-block p-block05<?php if ('type2' === $value['cb_section_type2_block_layout']) {
                  echo ' p-block05--rev';
                } ?>">
                <div class="p-block05__upper">
                  <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="p-block05__img">
                      <img src="<?php echo esc_attr(wp_get_attachment_url($value['cb_section_type2_block_img' . $i])); ?>"
                        alt="">
                    </div>
                  <?php endfor; ?>
                </div>
                <div class="p-block05__lower">
                  <div class="p-block05__img">
                    <img src="<?php echo esc_attr(wp_get_attachment_url($value['cb_section_type2_block_img4'])); ?>" alt="">
                  </div>
                  <div class="p-block05__content">
                    <h4 class="p-block05__content-title">
                      <?php echo nl2br(esc_html($value['cb_section_type2_block_title'])); ?>
                    </h4>
                    <p class="p-block05__content-desc">
                      <?php echo nl2br(esc_html($value['cb_section_type2_block_desc']));
                      ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php elseif ('type3' === $value['cb_section_type']): ?>
              <div
                class="p-index-section__content-block p-block05<?php if ('type2' === $value['cb_section_type3_block_layout']) {
                  echo ' p-block05--rev';
                } ?>">
                <div class="p-block05__upper">
                  <?php for ($i = 1; $i <= 2; $i++): ?>
                    <div class="p-block05__img">
                      <img src="<?php echo esc_attr(wp_get_attachment_url($value['cb_section_type3_block_img' . $i])); ?>"
                        alt="">
                    </div>
                  <?php endfor; ?>
                </div>
                <div class="p-block05__lower p-block05__lower--rev">
                  <div class="p-block05__img">
                    <img src="<?php echo esc_attr(wp_get_attachment_url($value['cb_section_type3_block_img3'])); ?>" alt="">
                  </div>
                  <div class="p-block05__content">
                    <h4 class="p-block05__content-title">
                      <?php echo nl2br(esc_html($value['cb_section_type3_block_title'])); ?>
                    </h4>
                    <p class="p-block05__content-desc">
                      <?php echo nl2br(esc_html($value['cb_section_type3_block_desc'])); ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div><!-- /.p-index-section__content -->
        <?php endif; // End type2, type3 ?>
        <?php if ($value['cb_section_btn_label']): ?>
          <p class="p-cb__item-btn">
            <a href="<?php echo esc_url($value['cb_section_btn_url']); ?>" class="p-btn"><?php echo esc_html($value['cb_section_btn_label']); ?></a>
          </p>
        <?php endif; ?>
      </div><!-- /.p-cb__item -->
      <?php
      // Recommended plan
    elseif ('recommended_plan' === $value['cb_content_select'] && $value['cb_recommended_plan_display']):
      $args = array(
        'post_type' => 'plan',
        'post_status' => 'publish',
        'posts_per_page' => 4,
        'meta_key' => 'recommended_plan',
        'meta_value' => 'on'
      );
      $the_query = new WP_Query($args);
      ?>
      <div id="cb_<?php echo esc_attr($key); ?>" class="p-index-plan p-cb__item p-cb__item--sm">
        <div class="p-index-plan__visual p-visual">
          <div class="p-visual__content">
            <div
              class="p-visual__content-inner<?php if ('type1' === $value['cb_recommended_plan_header_writing_type']) {
                echo ' p-visual__content-inner--vertical';
              } ?>">
              <h2 class="p-visual__title">
                <?php echo nl2br(esc_html($value['cb_recommended_plan_header_title'])); ?>
              </h2>
            </div>
          </div>
        </div>
        <section class="p-index-plan p-recommended-plan l-inner">
          <h3 class="p-recommended-plan__title">
            <?php echo esc_html($dp_options['recommended_plan_headline']); ?>
          </h3>
          <div class="p-index-plan__list p-recommended-plan__list">
            <?php
            if ($the_query->have_posts()):
              while ($the_query->have_posts()):
                $the_query->the_post();
                if (has_excerpt()) {
                  $excerpt = $post->post_excerpt;
                } elseif ($post->use_page_builder) {
                  $excerpt = render_page_builder_content($post->ID);
                } else {
                  $excerpt = $post->post_content;
                }
                ?>
                <article class="p-index-plan__list-item p-recommended-plan__list-item p-article09">
                  <a href="<?php the_permalink(); ?>"
                    class="p-hover-effect--<?php echo esc_attr($dp_options['hover_type']); ?>" title="<?php the_title(); ?>">
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
          <?php if ($value['cb_recommended_plan_btn_label']): ?>
            <p class="p-cb__item-btn">
              <a href="<?php echo get_post_type_archive_link('plan'); ?>" class="p-btn"><?php echo esc_html($value['cb_recommended_plan_btn_label']); ?></a>
            </p>
          <?php endif; ?>
        </section>
      </div><!-- /.p-cb__item -->
      <?php
      // Blog
    elseif ('blog' === $value['cb_content_select'] && $value['cb_blog_display']):

      if ('recent_post' === $value['cb_blog_type']) {
        $args = array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'posts_per_page' => -1
        );
      } else {
        $args = array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'posts_per_page' => -1,
          'meta_key' => esc_html($value['cb_blog_type']),
          'meta_value' => 'on'
        );
      }
      $the_query = new WP_Query($args);
      ?>
      <div id="cb_<?php echo esc_attr($key); ?>" class="p-index-blog p-cb__item l-inner">
        <h2 class="p-index-blog__title">
          <?php echo esc_html($value['cb_blog_headline']); ?>
        </h2>
        <?php if ($the_query->have_posts()): ?>
          <div class="p-index-blog__slider js-index-blog__slider">
            <?php
            while ($the_query->have_posts()):
              $the_query->the_post();
              $categories = get_the_category();
              ?>
              <article class="p-index-blog__slider-item p-article11">
                <a href="<?php echo get_category_link($categories[0]->term_id); ?>" class="p-article11__cat"><?php echo esc_html($categories[0]->name); ?></a>
                <a href="<?php the_permalink(); ?>" class="p-article11__inner">
                  <div class="p-article11__img">
                    <?php
                    if (has_post_thumbnail()) {
                      the_post_thumbnail('size1');
                    } else {
                      echo '<img src="' . get_template_directory_uri() . '/assets/images/592x410.gif" alt="">' . "\n";
                    }
                    ?>
                  </div>
                  <h3 class="p-article11__title">
                    <?php echo is_mobile() ? wp_trim_words(get_the_title(), 25, '...') : wp_trim_words(get_the_title(), 39, '...'); ?>
                  </h3>
                </a>
              </article>
              <?php
            endwhile;
            wp_reset_postdata();
            ?>
          </div>
        <?php endif; ?>
        <?php if ($value['cb_blog_btn_label']): ?>
          <p class="p-cb__item-btn">
            <a href="<?php echo get_post_type_archive_link('post'); ?>" class="p-btn"><?php echo esc_html($value['cb_blog_btn_label']); ?></a>
          </p>
        <?php endif; ?>
      </div><!-- /.p-cb__item -->
      <?php
    elseif ('wysiwyg' === $value['cb_content_select'] && $value['cb_wysiwyg_display']):
      $cb_wysiwyg_editor = apply_filters('the_content', $value['cb_wysiwyg_editor']);
      if ($cb_wysiwyg_editor) {
        echo '<div id="cb_' . esc_attr($key) . '">' . $cb_wysiwyg_editor . '</div>' . "\n";
      }
    endif;
  endforeach;
  ?>
</div><!-- /.p-cb -->
</div><!-- /.l-primary -->
<?php get_footer(); ?>