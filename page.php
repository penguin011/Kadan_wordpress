<?php
$dp_options = get_design_plus_option();
get_header(); 
?>
      <?php
      if ( 'type1' === $post->page_tcd_template_type || 'type2' === $post->page_tcd_template_type ) :
        if ( have_posts() ) : 
          while ( have_posts() ) :
            the_post();
        ?>
        <div class="p-entry">
          <?php if ( has_post_thumbnail() ) : ?>
			  	<div class="p-entry__img">
            <?php the_post_thumbnail( 'full' ); ?>
          </div>
          <?php endif; ?>
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
        </div>
        <?php
          endwhile;
        endif; 
      else : // Use page template
        get_template_part( "template-parts/page-{$post->page_tcd_template_type}" );
      endif;
      ?>
		</div><!-- /.l-primary -->
    <?php if ( 'type1' === $post->page_tcd_template_type ) { get_sidebar(); } ?>
<?php get_footer(); ?>
