<?php
global $wp_query;
$dp_options = get_design_plus_option();
$args = array( 
  'current' => max( 1, get_query_var( 'paged' ) ),  
  'prev_next' => false,
  'total' => $wp_query->max_num_pages,
  'type' => 'array'
);
get_header(); 
?>
      <div class="p-archive-header">
        <?php if ( is_home() ) : ?>
        <p class="p-archive-header__desc"><?php echo nl2br( esc_html( $dp_options['archive_desc'] ) ); ?></p>
        <?php else : ?>
        <h1 class="p-archive-header__title"><?php the_archive_title(); ?></h1>
        <p class="p-archive-header__desc"><?php the_archive_description(); ?></p>
        <?php endif; ?>
      </div>
      <?php if ( have_posts() ) : ?>
      <div class="p-blog-list">
        <?php
        while ( have_posts() ) :
          the_post(); 
          $categories = get_the_category();
        ?>
        <article class="p-blog-list__item p-article01">
          <?php if ( $dp_options['show_category'] && $categories ) : ?>
          <a class="p-article01__cat" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a>
          <?php endif; ?>
          <a class="p-article01__img p-hover-effect--<?php echo esc_attr( $dp_options['hover_type'] ); ?>" href="<?php the_permalink(); ?>">
            <?php
            if ( has_post_thumbnail() ) {
              the_post_thumbnail( 'size1' );
            } else {
              echo '<img src="' . get_template_directory_uri() . '/assets/images/592x410.gif" alt="">' . "\n";
            }
          ?>
          </a>
          <div class="p-article01__content">
            <h2 class="p-article01__title">
              <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo is_mobile() ? wp_trim_words( get_the_title(), 25, '...' ) : wp_trim_words( get_the_title(), 40, '...' ); ?></a>
            </h2>
            <?php if ( $dp_options['show_date'] ) : ?>
            <time class="p-article01__date" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( 'Y.m.d' ); ?></time>
            <?php endif; ?>
          </div>
        </article>
        <?php endwhile; ?>
      </div>
      <?php endif; ?>
			<?php if ( paginate_links( $args ) ) : ?>
      <ul class="p-pager">
        <?php foreach ( paginate_links( $args ) as $link ) : ?>
        <li class="p-pager__item"><?php echo $link; ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div><!-- /.l-primary -->
<?php get_footer(); ?>
