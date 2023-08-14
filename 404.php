<?php
$dp_options = get_design_plus_option();
get_header();
?>
<p class="p-archive-desc">
  <?php echo nl2br(esc_html($dp_options['404_archive_desc'])); ?>
</p>
</div><!-- /.l-primary -->
<?php get_footer(); ?>