<?php
$page_type3_b = $post->page_type3_b;
$page_type3_d = $post->page_type3_d;
?>
<div class="l-inner">
  <?php
  if (isset($page_type3_b['headline1'])):
    foreach (array_keys($page_type3_b['headline1']) as $index):
      ?>
      <div id="page_type3_b_<?php echo esc_attr($index); ?>"
        class="p-block01<?php if ('type2' === $page_type3_b['layout'][$index]) {
          echo ' p-block01--rev';
        } ?>">
        <?php for ($i = 1; $i <= 2; $i++): ?>
          <div class="p-block01__item">
            <div class="p-block01__item-img">
              <img src="<?php echo esc_attr(wp_get_attachment_url($page_type3_b['img' . $i][$index])); ?>" alt="">
            </div>
            <div class="p-block01__item-title p-vertical-block">
              <h2 class="p-vertical-block__inner">
                <?php echo esc_html($page_type3_b['headline' . $i][$index]); ?>
              </h2>
            </div>
            <p class="p-block01__item-desc">
              <?php echo nl2br(esc_html($page_type3_b['desc' . $i][$index])); ?>
            </p>
          </div>
        <?php endfor; ?>
      </div><!-- /.p-block01 -->
      <?php
    endforeach;
  endif;
  ?>
</div><!-- /.l-inner -->
<div class="p-visual">
  <div class="p-visual__content">
    <div
      class="p-visual__content-inner<?php if ('type1' === $post->page_type3_c_writing_mode) {
        echo ' p-visual__content-inner--vertical';
      } ?>">
      <h2 class="p-visual__title">
        <?php echo nl2br(esc_html($post->page_type3_c_catch)); ?>
      </h2>
    </div>
  </div>
</div>
<div class="l-inner">
  <?php
  if (isset($page_type3_d['headline1'])):
    foreach (array_keys($page_type3_d['headline1']) as $index):
      ?>
      <div id="page_type3_d_<?php echo esc_attr($index); ?>"
        class="p-block01<?php if ('type2' === $page_type3_d['layout'][$index]) {
          echo ' p-block01--rev';
        } ?>">
        <?php for ($i = 1; $i <= 2; $i++): ?>
          <div class="p-block01__item">
            <div class="p-block01__item-img">
              <img src="<?php echo esc_attr(wp_get_attachment_url($page_type3_d['img' . $i][$index])); ?>" alt="">
            </div>
            <div class="p-block01__item-title p-vertical-block">
              <h2 class="p-vertical-block__inner">
                <?php echo esc_html($page_type3_d['headline' . $i][$index]); ?>
              </h2>
            </div>
            <p class="p-block01__item-desc">
              <?php echo nl2br(esc_html($page_type3_d['desc' . $i][$index])); ?>
            </p>
          </div>
        <?php endfor; ?>
      </div><!-- /.p-block01 -->
      <?php
    endforeach;
  endif;
  ?>
  <?php if ($post->page_type3_e_catch): ?>
    <div class="p-vertical p-vertical--lg">
      <p>
        <?php echo nl2br(esc_html($post->page_type3_e_catch)); ?>
      </p>
    </div>
  <?php endif; ?>
  <?php if ($post->page_type3_f_1): ?>
    <img src="<?php echo esc_attr(wp_get_attachment_url($post->page_type3_f_1)); ?>" alt="" class="p-cover">
  <?php endif; ?>
  <?php if ($post->page_type3_f_2 || $post->page_type3_f_3): ?>
    <div class="p-spring-info">
      <?php if ($post->page_type3_f_2): ?>
        <p class="p-spring-info__col">
          <?php echo nl2br(esc_html($post->page_type3_f_2)); ?>
        </p>
      <?php endif; ?>
      <?php if ($post->page_type3_f_3): ?>
        <dl class="p-spring-info__data p-spring-info__col u-clearfix">
          <?php foreach (array_keys($post->page_type3_f_3['header']) as $index): ?>
            <dt>
              <?php echo esc_html($post->page_type3_f_3['header'][$index]); ?>
            </dt>
            <dd>
              <?php echo esc_html($post->page_type3_f_3['data'][$index]); ?>
            </dd>
          <?php endforeach; ?>
        </dl>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div><!-- /.l-inner -->