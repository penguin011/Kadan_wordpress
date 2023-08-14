<?php
$dp_options = get_design_plus_option();
$trackbacks = $comments_by_type['pings'];

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
	die('Please do not load this page directly. Thanks!');
}

if (post_password_required()) {
	/*
	   echo '<div class="c-comment">' . "\n";
	   echo '<div class="c-comment__password-protected">' . "\n";
	 echo '<p>' . __( 'This post is password protected. Enter the password to view comments.', 'tcd-w' ) . '</p>' . "\n";
	 echo '</div>' . "\n";
	   echo '</div>' . "\n";
	   */
	return;
}
?>
<div class="c-comment">
	<ul id="js-comment__tab" class="c-comment__tab u-clearfix">
		<?php
		// if trackback is open 
		if (pings_open() && $dp_options['show_trackback']):
			?>
			<li class="c-comment__tab-item is-active"><a href="#js-comment-area">
					<?php _e('Comment', 'tcd-w'); ?> (
					<?php echo esc_html(count($comments) - count($trackbacks)); ?> )
				</a></li>
			<li class="c-comment__tab-item"><a href="#js-trackback-area">
					<?php _e('Trackback', 'tcd-w'); ?> (
					<?php echo esc_html(count($trackbacks)); ?> )
				</a></li>
		<?php else: ?>
			<li class="c-comment__tab-item is-active">
				<p>
					<?php _e('Comment', 'tcd-w'); ?> (
					<?php echo esc_html(count($comments) - count($trackbacks)); ?> )
				</p>
			</li>
			<li class="c-comment__tab-item">
				<p>
					<?php _e('Trackbacks are closed.', 'tcd-w'); ?>
				</p>
			</li>
		<?php endif; ?>
	</ul>
	<div id="js-comment-area">
		<ol id="comments" class="c-comment__list">
			<?php
			if ($comments && count($comments) - count($trackbacks) > 0):
				wp_list_comments('type=comment&callback=custom_comments');
			else:
				?>
				<li class="c-comment__list-item">
					<div class="c-comment__item-body">
						<p>
							<?php _e('No comments yet.', 'tcd-w'); ?>
						</p>
					</div>
				</li>
			<?php endif; ?>
		</ol>
	</div>
	<?php
	// トラックバック一覧
	if (pings_open() && $dp_options['show_trackback']):
		?>
		<div id="js-trackback-area">
			<ol class="c-comment__list">
				<?php
				if ($trackbacks):
					foreach ($trackbacks as $comment):
						?>
						<li class="c-comment__list-item">
							<div class="c-comment__item-header">
								<div class="c-comment__item-meta u-clearfix">
									<div class="c-comment__item-date">
										<?php echo get_comment_time(__('F jS, Y', 'tcd-w')) ?>
										<?php edit_comment_link(__('[ EDIT ]', 'tcd-w'), '', ''); ?>
									</div>
								</div>
							</div>
							<div class="c-comment__item-body">
								<p>
									<?php _e('Trackback from: ', 'tcd-w'); ?><a href="<?php comment_author_url() ?>"
										rel="nofollow"><?php comment_author(); ?></a>
								</p>
							</div>
						</li>
						<?php
					endforeach;
				else:
					?>
				<li class="c-comment__list-item">
					<div class="c-comment__item-body">
						<p>
							<?php _e('No trackbacks yet.', 'tcd-w'); ?>
						</p>
					</div>
				</li>
			<?php endif; ?>
			</ol>
			<div class="c-comment__input">
				<label class="c-comment__label">
					<span class="c-comment__label-text">
						<?php _e('TRACKBACK URL', 'tcd-w'); ?>
					</span><input type="text" class="c-comment__trackback-url" name="trackback_url"
						value="<?php trackback_url() ?>" readonly="readonly" onfocus="this.select()">
				</label>
			</div>
		</div>
		<?php
	endif;

	// if comment are closed and don't have any comments 
	if (!comments_open()):

		// if registration required and not logged in. 
	elseif (get_option('comment_registration') && !$user_ID):
		$login_link = wp_login_url();
		?>
		<div class="c-comment__form-wrapper" id="respond">
			<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'tcd-w'), $login_link); ?>
		</div>
	<?php else: // if comment is open ?>
		<fieldset id="respond" class="c-comment__form-wrapper">
			<div class="c-comment__cancel">
				<?php cancel_comment_reply_link() ?>
			</div>
			<form action="<?php echo esc_url(site_url('/')); ?>wp-comments-post.php" class="c-comment__form"
				method="post">
				<?php
				// ログインユーザーの場合、ログイン中であることを示すテキストを表示
				if ($user_ID):
					?>
					<div class="c-comment__form-login">
						<p>
							<?php printf(__('Logged in as <a href="%1$s">%2$s</a>.', 'tcd-w'), get_site_url() . '/wp-admin/profile.php', $user_identity); ?><a
								href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e('[ Log out ]', 'tcd-w'); ?></a>
						</p>
					</div>
					<?php
					// ゲストユーザーの場合、名前、メールアドレス、URLのフィールドを表示する
				else:
					?>
					<div class="c-comment__input">
						<label><span class="c-comment__label-text">
								<?php _e('NAME', 'tcd-w'); ?>
								<?php if ($req)
									_e('( required )', 'tcd-w'); ?>
							</span><input type="text" name="author" value="<?php echo $comment_author; ?>" tabindex="1" <?php if ($req)
								   echo 'aria-required="true"'; ?>></label>
					</div>
					<div class="c-comment__input">
						<label><span class="c-comment__label-text">
								<?php _e('E-MAIL', 'tcd-w'); ?>
								<?php if ($req)
									_e('( required )', 'tcd-w'); ?>
								<?php _e('- will not be published -', 'tcd-w'); ?>
							</span><input type="text" name="email" value="<?php echo $comment_author_email; ?>" tabindex="2"
								<?php if ($req)
									echo 'aria-required="true"'; ?>></label>
					</div>
					<div class="c-comment__input">
						<label><span class="c-comment__label-text">
								<?php _e('URL', 'tcd-w'); ?>
							</span><input type="text" name="url" value="<?php echo esc_attr($comment_author_url); ?>"
								tabindex="3"></label>
					</div>
				<?php endif; ?>
				<div class="c-comment__input">
					<textarea id="js-comment__textarea" name="comment" tabindex="4"></textarea>
				</div>
				<?php do_action('comment_form', $post->ID); ?>
				<input type="submit" class="c-comment__form-submit" tabindex="5"
					value="<?php _e('Submit Comment', 'tcd-w'); ?>">
				<div class="c-comment__form-hidden">
					<?php comment_id_fields(); ?>
				</div>
			</form>
		</fieldset>
	<?php endif; ?>
</div>