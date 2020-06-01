<?php
/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

if (!$_POST) {
    exit;
}

require dirname(__FILE__) . '/../../../../wp-load.php';

$cuid = get_current_user_id();

if (!_pz('post_article_s')) {
    print_r(json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '不允许发布文章')));
    exit();
} elseif (_pz('post_article_limit', 'logged_in') == 'logged_in') {
    if (!is_user_logged_in()) {
        print_r(json_encode(array('error' => 1, 'ys' => 'warning', 'singin' => true, 'msg' => '请先登录！')));
        exit;
    }
}

$title   =  !empty($_POST['post_title'])?$_POST['post_title']:false;
$content =  !empty($_POST['post_content'])?$_POST['post_content']:false;
$cat = !empty($_POST['category'])?$_POST['category']:false;
$action = !empty($_POST['action'])?$_POST['action']:false;
$draft_id = '';
if (is_user_logged_in()) {
    $draft_id = get_user_meta($cuid, 'posts_draft', true);
}

$posts_id = !empty($_POST['posts_id']) ? $_POST['posts_id'] : ($draft_id ? $draft_id : 0);

if (empty($title)) {
    print_r(json_encode(array('error' => 1, 'ys' => 'warning', 'msg' => '请填写文章标题')));
    exit();
}
if (empty($content)) {
    print_r(json_encode(array('error' => 1, 'ys' => 'warning', 'msg' => '还未填写任何内容')));
    exit();
}

if ($action == 'posts.save') {
    if (_new_strlen($title) > 30) {
        print_r(json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '标题太长了，不能超过30个字')));
        exit();
    }
    if (_new_strlen($title) < 5) {
        print_r(json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '标题太短！')));
        exit();
    }
    if (_new_strlen($content) < 10) {
        print_r(json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '文章内容过少')));
        exit();
    }
    if (empty($cat)) {
        print_r(json_encode(array('error' => 1, 'ys' => 'warning', 'msg' => '请选择文章分类')));
        exit();
    }
}

if (!is_user_logged_in() && _pz('post_article_limit') == 'all') {
    if (_new_strlen($content) < 10) {
        print_r(json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '文章内容过少')));
        exit();
    }
    if (empty($_POST['user_name'])) {
        print_r(json_encode(array('error' => 1, 'msg' => '请输入昵称')));
        exit();
    }
    $cuid = _pz('post_article_limit', 1);
    $lx = !empty($_POST['contact_details']) ? ',联系：' . $_POST['contact_details'] : '';
    $title = $title . '[投稿-姓名：' . $_POST['user_name'] . $lx . ']';
}

$cat = array();
$cat[] = !empty($_POST['category'])?$_POST['category']:false;
$tags = preg_split("/,|，|\s|\n/", $_POST['tags']);

$postarr = array(
    'post_title'   => $title,
    'post_author'  => $cuid,
    'post_status'   => 'draft',
    'ID'            => $posts_id,
    'post_content' => $content,
    'post_category' => $cat,
    'tags_input'    => $tags,
    'comment_status'=> 'open',
);

if (_pz('post_article_review_s') && is_user_logged_in()) {
    $postarr['post_status'] = 'publish';
}
if ($action == 'posts.draft') {
    $postarr['post_status'] = 'draft';
}
$in_id = wp_insert_post($postarr);

if (!$in_id) {
    print_r(json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '投稿失败，请稍后再试')));
    exit();
}
$url = '';
if (is_user_logged_in()&&current_user_can( 'edit_post', $in_id )) {
    $url = get_permalink($in_id);
}

if ($action == 'posts.draft') {
    update_user_meta($cuid, 'posts_draft', $in_id);
    print_r(json_encode(array('error' => 0, 'posts_id' => $in_id, 'url' => $url, 'tags' => $tags, 'time' => current_time('mysql'), 'posts_url' => get_permalink($in_id),   'msg' => '草稿保存成功')));
    exit();
}
if(get_current_user_id()){
    $open_url = get_author_posts_url(get_current_user_id());
}else{
    $open_url = home_url();
}
if ($action == 'posts.save') {
    update_user_meta($cuid, 'posts_draft', false);
    if (_pz('post_article_review_s')) {
        print_r(json_encode(array('error' => 0, 'posts_id' => $in_id, 'url' => $url,  'ok' => 1,'open_url' => $open_url, 'msg' => '文章已发布')));
    } else {
        print_r(json_encode(array('error' => 0, 'posts_id' => $in_id, 'url' => $url,  'ok' => 1,'open_url' => $open_url, 'msg' => '投稿成功，等待审核中...')));
    }
    exit();
}

print_r(json_encode($_POST));
exit;
