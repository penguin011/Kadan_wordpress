<?php
$dp_options = get_design_plus_option();
$page_type4_c = $post->page_type4_c;
?>
<?php if (isset($page_type4_c['header_headline'][0])): ?>
  <div class="l-inner">
    <div class="p-section-nav">
      <?php foreach (array_keys($page_type4_c['header_headline']) as $index): ?>
        <div class="p-section-nav__item">
          <a href="#js-block03-<?php echo esc_attr($index); ?>"
            class="p-hover-effect--<?php echo esc_attr($dp_options['hover_type']); ?>">
            <?php if ($page_type4_c['b_img'][$index]): ?>
              <div class="p-section-nav__item-img">
                <img src="<?php echo esc_attr(wp_get_attachment_url($page_type4_c['b_img'][$index])); ?>" alt="">
              </div>
            <?php endif; ?>
            <p class="p-section-nav__item-title">
              <?php echo nl2br(esc_html($page_type4_c['b_title'][$index])); ?>
            </p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div><!-- /.l-inner -->
<?php endif; ?>
<?php
if (isset($page_type4_c['header_headline'][0])):
  foreach (array_keys($page_type4_c['header_headline']) as $index):
    ?>
    <section id="js-block03-<?php echo esc_attr($index); ?>" class="p-block03">
      <div class="l-inner">
        <header class="p-block03__header">
          <div class="p-block03__header-title p-vertical-block">
            <h2 class="p-vertical-block__inner">
              <?php echo esc_html($page_type4_c['header_headline'][$index]); ?>
            </h2>
          </div>
          <?php if (is_mobile() && $page_type4_c['header_img_sp'][$index]): ?>
            <img class="p-block03__header-img"
              src="<?php echo esc_attr(wp_get_attachment_url($page_type4_c['header_img_sp'][$index])); ?>" alt="">
          <?php else: ?>
            <img class="p-block03__header-img"
              src="<?php echo esc_attr(wp_get_attachment_url($page_type4_c['header_img'][$index])); ?>" alt="">
          <?php endif; ?>
        </header>
        <div class="p-block03__slider">
          <div class="p-block03__slider-title">
            <?php echo esc_html($page_type4_c['slider_headline'][$index]); ?>
          </div>
          <div class="p-block03__slider-inner js-block03__slider-inner">
            <?php
            for ($i = 1; $i <= 3; $i++):
              if ($page_type4_c['slider_img' . $i][$index] == '')
                continue;
              ?>
              <div class="p-block03__slider-img">
                <img src="<?php echo esc_attr(wp_get_attachment_url($page_type4_c['slider_img' . $i][$index])); ?>"
                  alt="">
              </div>
            <?php endfor; ?>
          </div>
        </div>
        <?php if ($page_type4_c['desc'][$index] || $page_type4_c['img'][$index]): ?>
          <div class="p-block03__grid">
            <div class="p-block03__grid-col">
              <div>
                <?php echo nl2br($page_type4_c['desc'][$index]); ?>
              </div>
            </div>
            <div class="p-block03__grid-col">
              <img src="<?php echo esc_attr(wp_get_attachment_url($page_type4_c['img'][$index])); ?>" alt="">
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
    <?php
  endforeach;
endif;
?>
<?php if ($post->page_type4_d_1 || $post->page_type4_d_2 || isset($post->page_type4_d_3['header'][0]) || $post->page_type4_d_4_label): ?>
  <div class="l-inner">
    <div class="p-room-meta">
      <?php if ($post->page_type4_d_1): ?>
        <img class="p-room-meta__img" src="<?php echo esc_attr(wp_get_attachment_url($post->page_type4_d_1)); ?>"
          alt="">
      <?php endif; ?>
      <div class="p-room-meta__content">
        <h3 class="p-room-meta__title">
          <?php echo esc_html($post->page_type4_d_2); ?>
        </h3>
        <?php if (isset($post->page_type4_d_3['header'][0])): ?>
          <dl class="p-room-meta__info u-clearfix">
            <?php foreach (array_keys($post->page_type4_d_3['header']) as $index): ?>
              <dt>
                <?php echo esc_html($post->page_type4_d_3['header'][$index]); ?>
              </dt>
              <dd>
                <?php echo esc_html($post->page_type4_d_3['data'][$index]); ?>
              </dd>
            <?php endforeach; ?>
          </dl>
        <?php endif; ?>
        <?php if ($post->page_type4_d_4_label): ?>
          <a href="<?php echo esc_url($post->page_type4_d_4_url); ?>" class="p-room-meta__btn p-btn" <?php if ($post->page_type4_d_4_target) {
                 echo ' target="_blank"';
               } ?>><?php echo esc_html($post->page_type4_d_4_label); ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endif; ?>