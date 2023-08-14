<?php
$dp_options = get_design_plus_option();
get_header(); 
?>
      <?php
      if ( have_posts() ) : 
        while ( have_posts() ) :
          the_post();
          $categories = get_the_category();
					$pagenation_type = 'type3' === $post->pagenation_type ? $options['pagenation_type'] : $post->pagenation_type;
					$previous_post = get_previous_post();
          $next_post = get_next_post();
          $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 8,
            'post__not_in' => array( $post->ID ),
            'category_name' => $categories[0]->slug,
            'orderby' => 'rand',
          );
          $the_query = new WP_Query( $args );
      ?>
      <article class="p-entry">
			  <header class="p-entry__header">
          <?php if ( $dp_options['show_category'] || $dp_options['show_date'] ) : ?>
					<div class="p-entry__meta">
            <?php if ( $dp_options['show_category'] ) : ?> 
            <a class="p-entry__cat" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a>
            <?php endif; ?>
            <?php if ( $dp_options['show_date'] ) : ?> 
						<time class="p-entry__date" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( 'Y.m.d' ); ?></time>
            <?php endif; ?>
					</div>
          <?php endif; ?>
					<h1 class="p-entry__title"><?php the_title(); ?></h1>
				</header>
        <?php if ( $dp_options['show_sns_top'] ) { get_template_part( 'template-parts/sns-btn-top' ); } ?>
        <?php if ( has_post_thumbnail() && $dp_options['show_thumbnail'] ) : ?>
				<div class="p-entry__img">
          <?php the_post_thumbnail( 'full' ); ?>
        </div>
        <?php endif; ?>
        <?php if ( ! is_mobile() ) { get_template_part( 'template-parts/ad-top' ); } ?>
				<div class="p-entry__body">
          <?php
          the_content();
					if ( ! post_password_required() ) {
            if ( 'type2' === $pagenation_type ) {
              if ( $page < $numpages && preg_match( '/href="(.*?)"/', _wp_link_page( $page + 1 ), $matches ) ) :
            ?>
            <div class="p-readmore">
              <a class="p-readmore__btn p-btn" href="<?php echo esc_url( $matches[1] ); ?>"><?php _e( 'Read more', 'tcd-w' ); ?></a>
              <p class="p-readmore__num"><?php echo $page . ' / ' . $numpages; ?></p>
            </div>
            <?php
              endif;
            } else {
              wp_link_pages( array( 
                'before' => '<div class="p-page-links">', 
                'after' => '</div>', 
                'link_before' => '<span>', 
                'link_after' => '</span>' 
              ) ); 
            }
          }
          ?>
        </div>
        <?php if ( $dp_options['show_sns_btm'] ) { get_template_part( 'template-parts/sns-btn-btm' ); } ?>
        <?php if ( $dp_options['show_author'] || $dp_options['show_category'] || $dp_options['show_tag'] || $dp_options['show_comment'] ) : ?>
        <?php endif; ?>
				<?php if ( $options['show_author'] || $options['show_category'] || $options['show_tag'] || $options['show_comment'] ) : ?>
				<ul class="p-entry__meta-box c-meta-box u-clearfix">
					<?php if ( $dp_options['show_author'] ) : ?><li class="c-meta-box__item c-meta-box__item--author"><?php _e( 'Author', 'tcd-w' ); ?>: <?php the_author_posts_link(); ?></li><?php endif; if ( $dp_options['show_category'] ) : ?><li class="c-meta-box__item c-meta-box__item--category"><?php the_category( ', ' ); ?></li><?php endif; if ( $dp_options['show_tag'] && get_the_tags() ) : ?><li class="c-meta-box__item c-meta-box__item--tag"><?php echo get_the_tag_list( '', ', ', '' ); ?></li><?php endif; if ( $dp_options['show_comment'] ) : ?><li class="c-meta-box__item c-meta-box__item--comment"><?php _e( 'Comments', 'tcd-w' ); ?>: <a href="#comment_headline"><?php echo get_comments_number( '0', '1', '%' ); ?></a></li><?php endif; ?>
				</ul>
				<?php endif; ?>
      </article>
      <?php
        endwhile;
      endif; 
      ?>
      <?php
      // Display <li> even if the previous or the next post does not exist.
      if ( $dp_options['show_next_post'] ) : 
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
      if ( $dp_options['show_comment'] ) { comments_template( '', true ); }
      ?>
      <?php if ( $dp_options['show_related_post'] ) : ?>
			<section>
			 	<h2 class="p-headline"><?php _e( 'Related posts', 'tcd-w' ); ?></h2>
			 	<ul class="p-entry__related">
          <?php
          if ( $the_query->have_posts() ) :
            while ( $the_query->have_posts() ) : 
              $the_query->the_post();
          ?>
          <li class="p-entry__related-item p-article03">
            <a href="<?php the_permalink(); ?>" class="p-hover-effect--<?php echo esc_attr( $dp_options['hover_type'] ); ?>">
              <div class="p-article03__img">
                <?php
                if ( has_post_thumbnail() ) {
                  the_post_thumbnail( 'size4' );
                } else {
                  echo '<img src="' . get_template_directory_uri() . '/assets/images/416x416.gif" alt="">' . "\n";
                }
                ?>
              </div>
              <h3 class="p-article03__title"><?php echo is_mobile() ? wp_trim_words( get_the_title(), 20, '...' ) : wp_trim_words( get_the_title(), 32, '...' ); ?></h3>
            </a>
          </li>
          <?php
            endwhile;
            wp_reset_postdata();
          else :
            echo '<li class="p-entry__related-item--no-post">' . __( 'No related posts.', 'tcd-w' ) . '</li>' . "\n";
          endif;
          ?>
         </ul>
			 </section>
       <?php endif; ?>
		</div><!-- /.l-primary -->
    <?php get_sidebar(); ?>
<?php get_footer(); ?>
