<?php
$dp_options = get_design_plus_option();
$title_encode = urlencode(get_the_title($post->ID));
$url_encode = urlencode(get_permalink($post->ID));
$pinterestimage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

// type5  
if ('type5' == $dp_options['sns_type_btm']):
	?>
	<ul class="c-share c-share--official u-clearfix">
		<?php
		if ($dp_options['show_twitter_btm']):
			?>
			<li class="c-share__btn c-share__btn--twitter">
				<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_fblike_btm']):
			?>
			<li class="c-share__btn c-share__btn--facebook">
				<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count"
					data-width="450" data-show-faces="false"></div>
			</li>
			<?php
		endif;
		if ($dp_options['show_fbshare_btm']):
			?>
			<li class="c-share__btn c-share__btn--facebook">
				<div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-layout="button_count"></div>
			</li>
			<?php
		endif;
		if ($dp_options['show_google_btm']):
			?>
			<li class="c-share__btn c-share__btn--google-plus">
				<div class="g-plusone" data-size="medium"></div>
			</li>
			<?php
		endif;
		if ($dp_options['show_hatena_btm']):
			?>
			<li class="c-share__btn c-share__btn--google-hatebu">
				<a href="http://b.hatena.ne.jp/entry/<?php the_permalink(); ?>" class="hatena-bookmark-button"
					data-hatena-bookmark-title="<?php the_title(); ?>"
					data-hatena-bookmark-layout="<?php echo (is_mobile()) ? 'simple' : 'standard'; ?>-balloon"
					data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img
						src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加"
						width="20" height="20" style="border: none;" /></a>
			</li>
			<?php
		endif;
		if ($dp_options['show_pocket_btm']):
			?>
			<li class="c-share__btn c-share__btn--pocket">
				<a data-pocket-label="pocket" data-pocket-count="horizontal" class="pocket-btn" data-lang="en"></a>
			</li>
			<?php
		endif;
		if ($dp_options['show_feedly_btm']):
			?>
			<li class="c-share__btn c-share__btn--feedly">
				<a href='http://feedly.com/index.html#subscription%2Ffeed%2F<?php bloginfo('rss2_url'); ?>' <?php echo (is_mobile()) ? '' : ' target="_blank"'; ?>><img id='feedlyFollow'
						src='http://s3.feedly.com/img/follows/feedly-follow-rectangle-flat-small_2x.png'
						alt='follow us in feedly' width='66' height='20'></a>
			</li>
			<?php
		endif;
		if ($dp_options['show_pinterest_btm']):
			?>
			<li class="c-share__btn c-share__btn--pinterest">
				<a data-pin-do="buttonPin" data-pin-color="red" data-pin-count="beside"
					href="https://www.pinterest.com/pin/create/button/?url=<?php echo $url_encode ?>&media=<?php echo $pinterestimage[0]; ?>&description=<?php echo $title_encode ?>"><img
						src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_red_20.png" /></a>
			</li>
			<?php
		endif;
		?>
	</ul>
	<?php
	// type1, type2, type3, type4
else:
	if ('type1' == $dp_options['sns_type_btm']) {
		$btm_class = 'c-share--sm c-share--color';
	} elseif ('type2' == $dp_options['sns_type_btm']) {
		$btm_class = 'c-share--sm c-share--mono';
	} elseif ('type3' == $dp_options['sns_type_btm']) {
		$btm_class = 'c-share--lg c-share--color';
	} elseif ('type4' == $dp_options['sns_type_btm']) {
		$btm_class = 'c-share--lg c-share--mono';
	}
	?>
	<ul class="p-entry__share c-share u-clearfix <?php echo esc_attr($btm_class); ?>">
		<?php
		if ($dp_options['show_twitter_btm']):
			?>
			<li class="c-share__btn c-share__btn--twitter">
				<?php
				if (is_mobile()):
					?>
					<a
						href="http://twitter.com/share?text=<?php echo $title_encode ?>&url=<?php echo $url_encode ?>&via=<?php echo $dp_options['twitter_info']; ?>&tw_p=tweetbutton&related=<?php echo $dp_options['twitter_info']; ?>">
						<?php
				else:
					?>
						<a href="http://twitter.com/share?text=<?php echo $title_encode ?>&url=<?php echo $url_encode ?>&via=<?php echo $dp_options['twitter_info']; ?>&tw_p=tweetbutton&related=<?php echo $dp_options['twitter_info']; ?>"
							onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=600');return false;">
							<?php
				endif;
				?>
						<i class="c-share__icn c-share__icn--twitter"></i>
						<span class="c-share__title">Tweet</span>
					</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_fbshare_btm']):
			?>
			<li class="c-share__btn c-share__btn--facebook">
				<a href="//www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>&amp;t=<?php echo $title_encode ?>"
					rel="nofollow" target="_blank">
					<i class="c-share__icn c-share__icn--facebook"></i>
					<span class="c-share__title">Share</span>
				</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_google_btm']):
			?>
			<li class="c-share__btn c-share__btn--google-plus">
				<?php
				if (is_mobile()):
					?>
					<a href="https://plus.google.com/share?url=<?php echo $url_encode; ?>">
						<?php
				else:
					?>
						<a href="https://plus.google.com/share?url=<?php echo $url_encode; ?>"
							onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500');return false;">
							<?php
				endif;
				?>
						<i class="c-share__icn c-share__icn--google-plus"></i>
						<span class="c-share__title">+1</span>
					</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_hatena_btm']):
			?>
			<li class="c-share__btn c-share__btn--hatebu">
				<?php
				if (is_mobile()):
					?>
					<a href="http://b.hatena.ne.jp/add?mode=confirm&url=<?php echo $url_encode ?>">
						<?php
				else:
					?>
						<a href="http://b.hatena.ne.jp/add?mode=confirm&url=<?php echo $url_encode ?>"
							onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=510');return false;">
						<?php endif; ?>
						<i class="c-share__icn c-share__icn--hatebu"></i>
						<span class="c-share__title">Hatena</span>
					</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_pocket_btm']):
			?>
			<li class="c-share__btn c-share__btn--pocket">
				<a href="http://getpocket.com/edit?url=<?php echo $url_encode; ?>&title=<?php echo $title_encode; ?>"
					target="_blank">
					<i class="c-share__icn c-share__icn--pocket"></i>
					<span class="c-share__title">Pocket</span>
				</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_rss_btm']):
			?>
			<li class="c-share__btn c-share__btn--rss">
				<a href="<?php bloginfo('rss2_url'); ?>" target="_blank">
					<i class="c-share__icn c-share__icn--rss"></i>
					<span class="c-share__title">RSS</span>
				</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_feedly_btm']):
			?>
			<li class="c-share__btn c-share__btn--feedly">
				<a href="http://feedly.com/index.html#subscription%2Ffeed%2F<?php bloginfo('rss2_url'); ?>" target="_blank">
					<i class="c-share__icn c-share__icn--feedly"></i>
					<span class="c-share__title">feedly</span>
				</a>
			</li>
			<?php
		endif;
		if ($dp_options['show_pinterest_btm']):
			?>
			<li class="c-share__btn c-share__btn--pinterest">
				<a href="https://www.pinterest.com/pin/create/button/?url=<?php echo $url_encode; ?>&media=<?php echo $pinterestimage[0]; ?>&description=<?php echo $title_encode ?>"
					rel="nofollow" target="_blank">
					<i class="c-share__icn c-share__icn--pinterest"></i>
					<span class="c-share__title">Pin it</span>
				</a>
			</li>
			<?php
		endif;
		?>
	</ul>
	<?php
endif;
?>