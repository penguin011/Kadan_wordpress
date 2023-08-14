<?php
$dp_options = get_design_plus_option();
$plan_label = $dp_options['plan_breadcrumb'] ? $dp_options['plan_breadcrumb'] : __( 'Plan', 'tcd-w' );
$display_thumbnail = has_post_thumbnail() && $post->plan_display_thumbnail ? true : false;
get_header(); 
?>
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) :
          the_post();
					$previous_post = get_previous_post();
          $next_post = get_next_post();
      ?>
      <article class="p-plan p-entry">
        <header>
          <h1 class="p-plan__title"><?php the_title(); ?></h1>
        </header>
        <div class="p-plan__gallery">
          <div id="js-plan__slider" class="p-plan__slider">
            <?php
            for ( $i = 1; $i <= 6; $i++ ) : 
              if ( $plan_slider_img = get_post_meta( $post->ID, 'plan_slider_img' . $i, true ) ) :
            ?>
            <div class="p-plan__slider-item">
              <img class="p-plan__slider-item-img" src="<?php echo esc_attr( wp_get_attachment_url( $plan_slider_img ) ); ?>" alt="">
              <?php if ( $plan_slider_cap = get_post_meta( $post->ID, 'plan_slider_cap' . $i, true ) ) : ?>
              <p class="p-plan__slider-item-cap"><?php echo esc_html( $plan_slider_cap ); ?></p>
              <?php endif; ?>
            </div>
            <?php
              endif;
            endfor; 
            ?>
          </div>
          <div id="js-plan__slider-nav" class="p-plan__slider-nav">
            <?php
            for ( $i = 1; $i <= 6; $i++ ) : 
              if ( $plan_slider_img = get_post_meta( $post->ID, 'plan_slider_img' . $i, true ) ) :
                $plan_slider_nav_img = wp_get_attachment_image_src( $plan_slider_img, 'size4' );
            ?>
            <div class="p-plan__slider-nav-img">
              <img src="<?php echo esc_attr( $plan_slider_nav_img[0] ); ?>" alt="">
            </div>
            <?php
              endif;
            endfor; 
            ?>
          </div>
        </div>
        <div class="p-plan__content">
          <div class="p-plan__content-inner p-entry__body">
            <?php the_content(); ?>
          </div>
			    <div class="p-plan__meta u-clearfix<?php if ( ! $display_thumbnail ) { echo ' p-plan__meta--no-img'; } ?>">
            <?php if ( $display_thumbnail ) : ?>
            <img class="p-plan__meta-img" src="<?php echo get_the_post_thumbnail_url( $post->ID, 'size2' ); ?>" alt="">
            <?php endif; ?>
            <dl class="p-plan__meta-info">
              <dt><?php printf( __( '%s name', 'tcd-w' ), $plan_label ); ?></dt>
              <dd><?php the_title(); ?></dd>
              <?php
              if ( isset( $post->plan_data['header'] ) ) :
                foreach ( $post->plan_data['header'] as $key => $header ) : 
              ?>
              <dt><?php echo esc_html( $header ); ?></dt>
              <dd><?php echo esc_html( $post->plan_data['data'][$key] ); ?></dd>
              <?php
                endforeach; 
              endif;
              ?>
            </dl>
            <?php if ( $post->plan_btn_label ) : ?>
            <a class="p-plan__meta-btn p-btn" href="<?php echo esc_url( $post->plan_btn_url ); ?>"<?php if ( $post->plan_btn_target ) { echo ' target="_blank"'; } ?>><?php echo esc_html( $post->plan_btn_label ); ?></a>
            <?php endif; ?>
          </div>
        </div>
      </article>
      <?php
        endwhile;
      endif;
      ?>
      <?php if ( $dp_options['plan_show_next_post'] ) : ?>
      <ul class="p-nav02">
        <?php if ( $previous_post ) : ?>
        <li class="p-nav02__item">
          <a href="<?php echo get_permalink( $previous_post->ID ); ?>"><?php printf( __( 'Previous %s', 'tcd-w' ), esc_html( $plan_label ) ); ?></a>
        </li>
        <?php endif; ?>
        <?php if ( $next_post ) : ?>
        <li class="p-nav02__item">
          <a href="<?php echo get_permalink( $next_post->ID ); ?>"><?php printf( __( 'Next %s', 'tcd-w' ), esc_html( $plan_label ) ); ?></a>
        </li>
        <?php endif; ?>
      </ul>
      <?php endif; ?>
      <?php get_template_part( 'template-parts/recommended-plan' ); ?>
		</div><!-- /.l-primary -->
<?php get_footer(); ?>
