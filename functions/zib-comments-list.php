<?php
function zib_comments_list($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	global $commentcount, $wpdb, $post;
	zib_get_comments_list($comment, $depth);
}

function zib_get_comments_list($comment, $depth = 0)
{

	if (!$comment) return false;
	$user_id = $comment->user_id;
	$c_like = zib_get_comment_like('action action-comment-like pull-right muted-2-color', $comment->comment_ID);

	echo '<li ';
	comment_class();
	echo ' id="comment-' . get_comment_ID() . '">';
	echo '<ul class="list-inline">';
	if (!$comment->comment_parent > 0) {
		echo '<li>';
		echo '<div class="comt-avatar">' . zib_get_data_avatar($user_id) . '</div>';
		echo '</li>';
	}
	echo '<li class="comt-main" id="div-comment-' . get_comment_ID() . '">';
	$con =  preg_replace('/\[img=(.*?)\]/', '<img class="box-img lazyload" src="$1">', nl2br(convert_smilies(get_comment_text())));

	if (_pz('lazy_comment')) {
		$con =  str_replace(' src=', ' src="' . zib_default_thumb() . '" data-src=', $con);
	}
	$con =  preg_replace('/\[g=(.*?)\]/', '<img class="smilie-icon" src="' . get_stylesheet_directory_uri() . '/img/smilies/$1.gif">', $con);

	$author_link  = '<strong>' . $comment->comment_author . '</strong>';

	if ($user_id) {
		$author_link = '<a href="' . get_author_posts_url($user_id) . '">' . $author_link . '</a>';
	}
	if ($comment->comment_parent > 0) {
		$author_link = '<i class="comt-avatar-mini mr10">' . zib_get_data_avatar($user_id) . '</i>' . $author_link;
	}
	if ($comment->comment_approved == '0') {
		$author_link .= '<span class="comt-approved but c-red ml10">待审核</span>';
	}

	echo '<p class="comt-avatar-name">' . $author_link . $c_like . '</p>';
	echo '<div class="comment-content"><p>' . $con . '</p></div>';
	echo '<div class="comt-meta  muted-2-color">';

	echo '<span class="comt-author">';
	echo zib_get_time_ago($comment->comment_date);
	echo '</span>';

	if ($comment->comment_parent > 0) {
		echo '<span>@<a rel="nofollow" class="url" href="javascript:(scrollTo(\'#comment-' . $comment->comment_parent . '\',-70));">' . get_comment_author($comment->comment_parent) . '</a></span>';
	}

	$max_depth = get_option('thread_comments_depth');
	if ($comment->comment_approved !== '0' && $depth) {
		$replyText = get_comment_reply_link(array('add_below' => 'div-comment', 'reply_text' => '回复','reply_to_text'=>'回复', 'depth' => $depth, 'max_depth' => $max_depth));
		if (strstr($replyText, 'reply-login')) {
			$replyText =   preg_replace('# class="[\s\S]*?" href="[\s\S]*?"#', ' class="signin-loader" href="javascript:;"', $replyText);
		} else {
			$replyText =  preg_replace('# aria-label=#', ' data-toggle="tooltip" data-original-title=', $replyText);
		}
		echo '<span>' . $replyText . '</span>';
	}

	echo zib_get_admin_edit('编辑此评论','comment');
	if (get_edit_comment_link()) {
		$del_nonce = esc_html('_wpnonce=' . wp_create_nonce("delete-comment_$comment->comment_ID"));
		$trash_url = esc_url("comment.php?action=trashcomment&p=$comment->comment_post_ID&c=$comment->comment_ID&$del_nonce");
		echo '<span class="admin-edit" data-toggle="tooltip" data-original-title="删除此评论"><a class="comment-edit-link" href="' . admin_url($trash_url) . '">[删除]</a></span>';
	}

	echo '</div>';
	echo '</li>';
	echo '</ul>';
	echo '</li> ';
}

function zib_comments_author_list($comment, $args = '')
{
	if (!$comment) return false;
	$c_like = zib_get_comment_like('action action-comment-like pull-right muted-2-color', $comment->comment_ID);

	$cont =  preg_replace('/\[img=(.*?)\]/', '<img class="box-img" src="$1">', convert_smilies($comment->comment_content));
	$cont =  preg_replace('/\<img(.*?)\>/', '[图片]', $cont);
	$cont =  preg_replace('/\[g=(.*?)\]/', '<img class="smilie-icon" src="' . get_stylesheet_directory_uri() . '/img/smilies/$1.gif">', $cont);
	$_link = get_comment_link($comment->comment_ID);
	$post_title = get_the_title( $comment->comment_post_ID );
	$post_tlink = get_the_permalink( $comment->comment_post_ID );

	$time = $comment->comment_date;
	$approved = '';
	$parent = '';
	$post = '<a class="muted-color" href="'.$post_tlink .'">'.$post_title.'</a>';

	$cont = '<a href="'.$_link .'">'.$cont.'</a>';
	if ($comment->comment_parent > 0) {
		$parent = '<span class="mr10" >@' . get_comment_author($comment->comment_parent) . '</span>';
	}

	if ($comment->comment_approved == '0') {
		$approved = '<span class="muted-2-color mr6">[待审核]</span>';
	}

	$time = zib_get_time_ago($comment->comment_date);

	echo '<div class="list-inline">';
	echo '<div class="author-set-left muted-2-color">';
	echo $time;
	echo '</div>';

	echo '<div class="author-set-right">';
	echo '<p>';
	echo $approved.$cont;

	echo '</p>';
	echo '<span class="muted-2-color em09">';
	echo $parent.'评论于：'.$post;

	echo '</s>';
	echo '</div>';

	echo '</div>';
}


function zib_widget_comments($limit, $outpost, $outer)
{
	global $wpdb;
	$args = array(
		'number' => $limit,
		'orderby' => 'comment_date',
		'number' => $limit,
		'status' => 'approve',
		'author__not_in' =>  preg_split("/,|，|\s|\n/", $outer),
		'post__not_in' =>  preg_split("/,|，|\s|\n/", $outpost),
	);

	$comments = get_comments( $args );;

	$output = '';
	foreach ($comments as $comment) {
		$cont =  preg_replace('/\[img=(.*?)\]/', '<img class="box-img" src="$1">', convert_smilies(strip_tags($comment->comment_content)));
		$cont =  preg_replace('/\<img(.*?)\>/', '[图片]', $cont);
		$cont =  preg_replace('/\[g=(.*?)\]/', '<img class="smilie-icon" src="' . get_stylesheet_directory_uri() . '/img/smilies/$1.gif">', $cont);
		$_link = get_comment_link($comment->comment_ID);
		$post_title = $comment->post_title;
		$post_link = get_the_permalink($comment->ID);

		$time = zib_get_time_ago($comment->comment_date);

		$user_name = $comment->comment_author;
		$user_id = $comment->user_id;
		$c_like = zib_get_comment_like('action action-comment-like pull-right muted-2-color', $comment->comment_ID);

		if ($user_id) {
			$user_name = '<a href="' . get_author_posts_url($user_id) . '">' . $user_name . '</a>';
		}
		$avatar = '<i>' . zib_get_data_avatar($user_id, '22') . '</i>';


		echo '<div class="posts-mini">';
		echo $avatar;
		echo '<div class="comment-con em09">';
		echo '<p>';
		echo $user_name;
		echo '<span class="icon-spot muted-3-color">' . $time . '</span>';
		echo '<span class="pull-right">' . $c_like . '</span>';
		echo '</p>';

		echo '<a class="muted-color" href="' . $_link . '">' . $cont . '</a>';
		echo '</div>';
		echo '</div>';
	}
};

