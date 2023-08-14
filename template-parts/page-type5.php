<?php
$page_type5_b = $post->page_type5_b;
$page_type5_d = $post->page_type5_d;
$page_type5_f_2 = $post->page_type5_f_2;
$page_type5_f_4 = $post->page_type5_f_4;
?>
<div class="l-inner">
  <?php
  if (isset($page_type5_b['headline'][0])):
    foreach (array_keys($page_type5_b['headline']) as $index):
      ?>
      <div id="page_type5_b_<?php echo esc_attr($index); ?>"
        class="p-block02<?php if ('type2' === $page_type5_b['layout'][$index]) {
          echo ' p-block02--rev';
        } ?>">
        <div class="p-block02__title p-vertical-block">
          <h2 class="p-vertical-block__inner">
            <?php echo esc_html($page_type5_b['headline'][$index]); ?>
          </h2>
        </div>
        <div class="p-block02__item p-block02__item--slider js-block02__item--slider">
          <?php
          for ($i = 1; $i <= 3; $i++):
            if (!$page_type5_b['slider_img' . $i][$index])
              continue;
            ?>
            <div class="p-block01__item-slider-img">
              <img src="<?php echo esc_attr(wp_get_attachment_url($page_type5_b['slider_img' . $i][$index])); ?>" alt="">
            </div>
          <?php endfor; ?>
        </div>
        <div class="p-block02__item p-block02__item--content">
          <div class="p-block02__item-content">
            <?php if ($page_type5_b['desc'][$index]): ?>
              <p class="p-block02__item-desc">
                <?php echo nl2br(esc_html($page_type5_b['desc'][$index])); ?>
              </p>
            <?php endif; ?>
            <?php if ($page_type5_b['img1'][$index] || $page_type5_b['img2'][$index]): ?>
              <div class="p-block02__item-grid">
                <?php if ($page_type5_b['img1'][$index]): ?>
                  <figure class="p-block02__item-img">
                    <img src="<?php echo esc_attr(wp_get_attachment_url($page_type5_b['img1'][$index])); ?>" alt="">
                  </figure>
                <?php endif; ?>
                <?php if ($page_type5_b['img2'][$index]): ?>
                  <figure class="p-block02__item-img">
                    <img src="<?php echo esc_attr(wp_get_attachment_url($page_type5_b['img2'][$index])); ?>" alt="">
                  </figure>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php
    endforeach;
  endif;
  ?>
</div><!-- /.l-inner -->
<?php if ($post->page_type5_c_catch): ?>
  <div id="page_type5_c" class="p-visual">
    <div class="p-visual__content">
      <div
        class="p-visual__content-inner<?php if ('type1' === $post->page_type5_c_writing_mode) {
          echo ' p-visual__content-inner--vertical';
        } ?>">
        <h2 class="p-visual__title">
          <?php echo nl2br(esc_html($post->page_type5_c_catch)); ?>
        </h2>
      </div>
    </div>
  </div>
<?php endif; ?>
<div class="l-inner">
  <?php
  if (isset($page_type5_d['headline'][0])):
    foreach (array_keys($page_type5_d['headline']) as $index):
      ?>
      <div id="page_type5_d_<?php echo esc_attr($index); ?>"
        class="p-block02<?php if ('type2' === $page_type5_d['layout'][$index]) {
          echo ' p-block02--rev';
        } ?>">
        <div class="p-block02__title p-vertical-block">
          <h2 class="p-vertical-block__inner">
            <?php echo esc_html($page_type5_d['headline'][$index]); ?>
          </h2>
        </div>
        <div class="p-block02__item p-block02__item--slider js-block02__item--slider">
          <?php
          for ($i = 1; $i <= 3; $i++):
            if (!$page_type5_d['slider_img' . $i][$index])
              continue;
            ?>
            <div class="p-block01__item-slider-img">
              <img src="<?php echo esc_attr(wp_get_attachment_url($page_type5_d['slider_img' . $i][$index])); ?>" alt="">
            </div>
          <?php endfor; ?>
        </div>
        <div class="p-block02__item p-block02__item--content">
          <div class="p-block02__item-content">
            <?php if ($page_type5_d['desc'][$index]): ?>
              <p class="p-block02__item-desc">
                <?php echo nl2br(esc_html($page_type5_d['desc'][$index])); ?>
              </p>
            <?php endif; ?>
            <?php if ($page_type5_d['img1'][$index] || $page_type5_d['img2'][$index]): ?>
              <div class="p-block02__item-grid">
                <?php if ($page_type5_d['img1'][$index]): ?>
                  <figure class="p-block02__item-img">
                    <img src="<?php echo esc_attr(wp_get_attachment_url($page_type5_d['img1'][$index])); ?>" alt="">
                  </figure>
                <?php endif; ?>
                <?php if ($page_type5_d['img2'][$index]): ?>
                  <figure class="p-block02__item-img">
                    <img src="<?php echo esc_attr(wp_get_attachment_url($page_type5_d['img2'][$index])); ?>" alt="">
                  </figure>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php
    endforeach;
  endif;
  ?>
</div><!-- /.l-inner -->
<?php if ($post->page_type5_e_catch): ?>
  <div id="page_type5_e" class="p-visual">
    <div class="p-visual__content">
      <div
        class="p-visual__content-inner<?php if ('type1' === $post->page_type5_e_writing_mode) {
          echo ' p-visual__content-inner--vertical';
        } ?>">
        <h2 class="p-visual__title">
          <?php echo nl2br(esc_html($post->page_type5_e_catch)); ?>
        </h2>
      </div>
    </div>
  </div>
<?php endif; ?>
<div class="l-inner">
  <?php if ($post->page_type5_f_1 || isset($page_type5_f_2['desc'][0]) || $post->page_type5_f_3 || isset($page_type5_f_4['desc'][0])): ?>
    <div class="p-meal-info">
      <div class="p-meal-info__col">
        <h3 class="p-meal-info__col-title">
          <?php echo nl2br(esc_html($post->page_type5_f_1)); ?>
        </h3>
        <?php
        if (isset($page_type5_f_2['desc'][0])):
          foreach (array_keys($page_type5_f_2['desc']) as $index):
            ?>
            <div class="p-meal-info__row">
              <?php if ($page_type5_f_2['img'][$index]): ?>
                <img class="p-meal-info__row-img"
                  src="<?php echo esc_attr(wp_get_attachment_url($page_type5_f_2['img'][$index])); ?>" alt="">
              <?php endif; ?>
              <p class="p-meal-info__row-desc">
                <?php echo nl2br(esc_html($page_type5_f_2['desc'][$index])); ?>
              </p>
            </div>
            <?php
          endforeach;
        endif;
        ?>
      </div><!-- / .p-meal-info__col -->
      <div class="p-meal-info__col">
        <h3 class="p-meal-info__col-title">
          <?php echo nl2br(esc_html($post->page_type5_f_3)); ?>
        </h3>
        <?php
        if (isset($page_type5_f_4['desc'][0])):
          foreach (array_keys($page_type5_f_4['desc']) as $index):
            ?>
            <div class="p-meal-info__row">
              <?php if ($page_type5_f_4['img'][$index]): ?>
                <img class="p-meal-info__row-img"
                  src="<?php echo esc_attr(wp_get_attachment_url($page_type5_f_4['img'][$index])); ?>" alt="">
              <?php endif; ?>
              <p class="p-meal-info__row-desc">
                <?php echo nl2br(esc_html($page_type5_f_4['desc'][$index])); ?>
              </p>
            </div>
            <?php
          endforeach;
        endif;
        ?>
      </div><!-- / .p-meal-info__col -->
    </div><!-- /.p-meal-info -->
  <?php endif; ?>
</div><!-- /.l-inner -->