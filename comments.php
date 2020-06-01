<?php
defined('ABSPATH') or die('无法直接加载此文件.');

if (!comments_open()) return;

/*global $comment_ids; $comment_ids = array();
foreach ( $comments as $comment ) {
	if (get_comment_type() == "comment") {
		$comment_ids[get_comment_id()] = ++$comment_i;
	}
} */


$my_email = get_bloginfo('admin_email');
$str = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_approved = '1' AND comment_type = '' AND comment_author_email";
$count_t = $post->comment_count;

date_default_timezone_set('PRC');
$closeTimer = (strtotime(date('Y-m-d G:i:s')) - strtotime(get_the_time('Y-m-d G:i:s'))) / 86400;
?>
<div class="theme-box" id="comments">
	<div class="box-body notop">
		<div class="title-theme"><?php echo _pz('comment_title') ?> <?php echo $count_t ? '<small>共' . $count_t . '条</small>' : '<small>抢沙发</small>'; ?></div>
	</div>

	<div class="no_webshot main-bg theme-box box-body radius8 main-shadow">
		<?php if (get_option('comment_registration') && !is_user_logged_in()) { ?>

			<div class="comment-signarea text-center box-body radius8">
				<h3 class="text-muted em12 separator theme-box muted-3-color">请登录后发表评论</h3>
				<p>
				<a href="javascript:;" class="signin-loader but c-blue"><i class="fa fa-fw fa-sign-in mr10"></i>登录</a>
				<a href="javascript:;" class="signup-loader ml10 but c-yellow"><i class="fa fa-fw fa-pencil-square-o mr10"></i>注册</a>
				</p>
				<?php zib_social_login(); ?>
			</div>

		<?php } elseif (get_option('close_comments_for_old_posts') && $closeTimer > get_option('close_comments_days_old')) { ?>
			<div class="comment-signarea text-center box-body">
			<div class="text-muted em12 separator">文章评论已关闭</div>
			</div>
		<?php } else { ?>
			<div id="respond">
			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

				<ul class="list-inline">
					<li class="comt-title text-center">
						<?php
						if (is_user_logged_in()) {
							global $current_user;
							echo '<div class="comt-avatar">' .zib_get_data_avatar(get_current_user_id()). '</div>';
							echo '<p class="text-ellipsis muted-2-color">' . $user_identity . '</p>';
						} else {
							echo '<div class="comt-avatar">' .zib_get_data_avatar(). '</div>';
							echo '<p class="comment-user-change" data-toggle="tooltip" title="填写用户信息">' . ($comment_author ? $comment_author : '昵称') . '</p>';
						}
						?>
					</li>
					<li class="comt-box">
						<textarea placeholder="<?php echo _pz('comment_text') ?>" class="form-control" name="comment" id="comment" cols="100%" rows="4" tabindex="1" onkeydown="if(event.ctrlKey&amp;&amp;event.keyCode==13){document.getElementById('submit').click();return false};"></textarea>
						<div class="comt-ctrl relative">
							<div class="comt-tips">
								<?php comment_id_fields();
									do_action('comment_form', $post->ID); ?>
							</div>
							<div class="comt-tips-right pull-right">
								<a class="but c-red" id="cancel-comment-reply-link" href="javascript:;">取消回复</a>
								<button type="submit" class="but c-blue" name="submit" id="submit" tabindex="5"><?php echo _pz('comment_submit_text') ? _pz('comment_submit_text') : '提交评论' ?></button>
							</div>
							<div class="comt-tips-left">
								<?php if (!is_user_logged_in()) {
									echo '<a class="but comment-user-change" href="javascript:;"><i class="fa fa-fw fa-user"></i><span>昵称</span></a>';
								} ?>
								<?php if (_pz('comment_smilie')) { ?>
									<a class="but popover-focus comt-smilie" href="javascript:;"><i class="fa fa-fw fa-smile-o"></i><span>表情</span></a>
								<?php } ?>
								<?php if (_pz('comment_code')) { ?>
									<a class="but popover-focus comt-code" href="javascript:;"><i class="fa fa-fw fa-code"></i><span>代码</span></a>
								<?php } ?>
								<?php if (_pz('comment_img')) { ?>
									<a class="but popover-focus comt-image" href="javascript:;"><i class="fa fa-fw fa-image"></i><span>图片</span></a>
								<?php } ?>
							</div>
							<?php if (!is_user_logged_in()) { ?>
								<div class="popover top" id="comment-user-info" <?php if (get_option('require_name_email')) echo 'require_name_email="true"'; ?>>
									<div class="sign-mask"></div>
									<div class="box-body">
									<div class="theme-box">
										<p>请填写用户信息：</p>
										<ul>
										<li class="relative">
                                            <input type="text" name="author" class="line-form-input" tabindex="1" value="<?php echo esc_attr($comment_author); ?>" placeholder="昵称 <?php if (get_option('require_name_email')) echo '（必填）'; ?>">
											<div class="abs-right muted-color"><i class="fa fa-fw fa-user"></i></div>
											<i class="line-form-line"></i>
                                        </li>
                                        <li class="relative">
                                            <input type="text" name="email" class="line-form-input" tabindex="2" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="邮箱 <?php if (get_option('require_name_email')) echo '（必填）'; ?>">
                                            <div class="abs-right muted-color"><i class="fa-fw fa fa-globe"></i></div>
                                            <i class="line-form-line"></i>
                                        </li>
										</ul>
										</div>
										<?php zib_social_login();?>
									</div>
								</div>
							<?php } ?>
						</div>
					</li>
				</ul>
			</form>
			</div>
		<?php } ?>
		<?php
		if (have_comments()) {
		?>
			<div id="postcomments">
				<ol class="commentlist list-unstyled">
					<?php
					wp_list_comments('type=comment&callback=zib_comments_list');
					?>
				</ol>
				<?php if (paginate_comments_links('type=array')) { ?>
					<div class="pagenav">
						<?php paginate_comments_links('prev_text=上一页&next_text=下一页'); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>