<?php
$dp_options = get_design_plus_option();
$news_label = $dp_options['news_breadcrumb'] ? $dp_options['news_breadcrumb'] : __( 'News', 'tcd-w' );
get_header();
?>
      <?php
      if ( have_posts() ) : 
        while ( have_posts() ) :
          the_post();
					$previous_post = get_previous_post();
          $next_post = get_next_post();
          $args = array(
            'post_type' => 'news',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
          );
          $the_query = new WP_Query( $args );
      ?>
      <article class="p-entry">
			  <header class="p-entry__header p-entry__header--news">
					<h1 class="p-entry__title"><?php the_title(); ?></h1>
          <?php if ( $dp_options['news_show_date'] ) : ?>
          <time class="p-entry__date" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( 'Y.m.d' ); ?></time>
          <?php endif; ?>
				</header>
        <?php if ( $dp_options['news_show_sns_top'] ) { get_template_part( 'template-parts/sns-btn-top' ); } ?>
        <?php if ( has_post_thumbnail() && $dp_options['news_show_thumbnail'] ) : ?>
				<div class="p-entry__img">
          <?php the_post_thumbnail( 'full' ); ?>
        </div>
        <?php endif; ?>
        <?php if ( ! is_mobile() ) { get_template_part( 'template-parts/ad-top' ); } ?>
				<div class="p-entry__body">
          <?php
          the_content();
					if ( ! post_password_required() ) {
            wp_link_pages( array( 
              'before' => '<div class="p-page-links">', 
              'after' => '</div>', 
              'link_before' => '<span>', 
              'link_after' => '</span>' 
            ) ); 
          }
          ?>
        </div>
        <?php if ( $dp_options['news_show_sns_btm'] ) { get_template_part( 'template-parts/sns-btn-btm' ); } ?>
      </article>
      <?php
        endwhile;
      endif; 

      // Display <li> even if the previous or the next post does not exist.
      if ( $dp_options['news_show_next_post'] ) : 
      ?>
      <ul class="p-nav01 c-nav01">
    	  <li class="p-nav01__item p-nav01__item--prev c-nav01__item"><?php // Not include white spaces
          if ( $previous_post ) : ?> 
    	    <a href="<?php echo get_permalink( $previous_post->ID ); ?>" class="p-hover-effect--<?php echo esc_attr( $dp_options['hover_type'] ); ?>" data-prev="<?php _e( 'Previous post', 'tcd-w' ); ?>">
            <?php if ( has_post_thumbnail( $previous_post->ID ) ) : ?>
            <div class="p-nav01__item-img">
              <?php echo get_the_post_thumbnail( $previous_post->ID, 'size3' ); ?>
            </div>
            <?php endif; ?>
            <span class="p-nav01__item-title"><?php echo wp_trim_words( get_the_title( $previous_post->ID ), 18, '...' ); ?></span>
          </a>
          <?php endif; // Not include white spaces
        ?></li>
    	  <li class="p-nav01__item p-nav01__item--next c-nav01__item"><?php // Not include white spaces
          if ( $next_post ) : ?> 
    	    <a href="<?php echo get_permalink( $next_post->ID ); ?>" class="p-hover-effect--<?php echo esc_attr( $dp_options['hover_type'] ); ?>" data-next="<?php _e( 'Next post', 'tcd-w' ); ?>">
            <span class="p-nav01__item-title"><?php echo wp_trim_words( get_the_title( $next_post->ID ), 18, '...' ); ?></span>
            <?php if ( has_post_thumbnail( $next_post->ID ) ) : ?>
            <div class="p-nav01__item-img">
              <?php echo get_the_post_thumbnail( $next_post->ID, 'size3' ); ?>
            </div>
            <?php endif; ?>
          </a>
          <?php endif; // Not include white spaces
    	  ?></li>
			</ul>
      <?php
      endif; 
      get_template_part( 'template-parts/ad-btm' );
      if ( $dp_options['news_show_latest_post'] ) :
      ?>
			<section>
        <div class="p-headline mb0">
          <h2><?php echo esc_html( $news_label ); ?></h2>
          <a href="<?php echo get_post_type_archive_link( 'news' ); ?>" class="p-headline__link"><?php printf( __( '%s list', 'tcd-w' ), esc_html( $news_label ) ); ?></a>
        </div>
				<ul class="p-latest-news">
          <?php
          if ( $the_query->have_posts() ) :
            while ( $the_query->have_posts() ) :
              $the_query->the_post();
          ?>
          <li class="p-latest-news__item p-article08">
            <a href="<?php the_permalink(); ?>">
              <?php if ( $dp_options['news_show_date'] ) : ?>
              <time datetime="<?php the_time( 'Y-m-d' ); ?>" class="p-article08__date"><?php the_time( 'Y.m.d' ); ?></time>
              <?php endif; ?>
              <h3 class="p-article08__title"><?php echo is_mobile() ? wp_trim_words( get_the_title(), 30, '...' ) : wp_trim_words( get_the_title(), 35, '...' ); ?></h3>
            </a>
          </li>
          <?php
            endwhile;
            wp_reset_postdata();
          else :
            echo '<li class="p-latest-news__item--no-post">' . __( 'There is no registered post.', 'tcd-w' ) . '</li>' . "\n";
          endif;
          ?>
        </ul>
			</section>
      <?php endif; ?>
		</div><!-- /.l-primary -->
    <?php get_sidebar(); ?>
<?php get_footer(); ?>
