<?php
/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

require dirname(__FILE__) . '/../../../../wp-load.php';
$key = checkpost('key');
$post_id = checkpost('pid');
$type = checkpost('type');
$is_like = checkpost('is_like');


switch ($type) {
    case 'comment_like':
        posts_action('like-comment', $post_id, $key, '已赞！感谢您的支持', '点赞已取消', true);
        exit();
        break;

    case 'like':
        posts_action('like-posts', $post_id, $key, '已赞！感谢您的支持', '点赞已取消');
        exit();
        break;

    case 'favorite':
        if (!is_user_logged_in()) {
            print_r(json_encode(array('error' => 1, 'pid' => $post_id, 'type' => $type, 'msg' => '请先登录')));
            exit();
            break;
        }
        posts_action('favorite-posts', $post_id, $key, '已收藏此文章', '已取消收藏');
        exit();
        break;

    case 'follow_user':
        if (!is_user_logged_in()) {
            print_r(json_encode(array('error' => 1, 'pid' => $post_id, 'type' => $type, 'msg' => '请先登录')));
            exit();
            break;
        }
        follow_action('follow-user', 'followed-user', $post_id);
        exit();
        break;
}
exit();
function follow_action($_name, $ed_name, $_ed_id, $add_msg = '已关注此用户', $rem_msg = '已取消关注此用户')
{
    $user_meta = false;
    $is_in_meta = false;
    $user_id = get_current_user_id();
    $user_meta = get_user_meta($user_id, $_name, true);
    $ed_user_meta = get_user_meta($_ed_id, $ed_name, true);
    if ($user_meta) {
        $user_meta = unserialize($user_meta);
        $is_in_meta = in_array($_ed_id, $user_meta);
    }
    if ($ed_user_meta) {
        $ed_user_meta = unserialize($ed_user_meta);
    }

    if (!$user_meta || !$is_in_meta) {
        if (!$user_meta) {
            $user_meta = array($_ed_id);
        } else {
            array_unshift($user_meta, $_ed_id);
        }
        if (!$ed_user_meta) {
            $ed_user_meta = array($user_id);
        } else {
            array_unshift($ed_user_meta, $user_id);
        }

        update_user_meta($user_id, $_name, serialize($user_meta));
        update_user_meta($_ed_id, $ed_name, serialize($ed_user_meta));
        update_user_meta($user_id, $_name.'-count', count($user_meta));
        update_user_meta($_ed_id, $ed_name.'-count', count($ed_user_meta));
        print_r(json_encode(array('error' => 0, 'action' => 'add', 'follow-user' => $user_meta, 'followed-user' => $ed_user_meta, 'msg' => $add_msg, 'cuont' => '<i class="fa fa-heart mr6" aria-hidden="true"></i>已关注')));
        exit;
    }
    if ($is_in_meta) {
        $h = array_search($_ed_id, $user_meta);
        unset($user_meta[$h]);
        $h2 = array_search($user_id, $ed_user_meta);
        unset($ed_user_meta[$h2]);
        update_user_meta($user_id, $_name, serialize($user_meta));
        update_user_meta($_ed_id, $ed_name, serialize($ed_user_meta));
        update_user_meta($user_id, $_name.'-count', count($user_meta));
        update_user_meta($_ed_id, $ed_name.'-count', count($ed_user_meta));
        print_r(json_encode(array('error' => 0, 'action' => 'remove', 'follow-user' => $user_meta, 'followed-user' => $ed_user_meta, 'msg' => $rem_msg, 'cuont' => '<i class="fa fa-heart-o mr6" aria-hidden="true"></i>关注')));
        exit;
    }
    exit;
}

function posts_action($user_meta_name, $post_id, $key, $add_msg = '已完成', $rem_msg = '已取消', $is_comment = false)
{
    $user_meta = false;
    $is_in_meta = false;
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $user_meta = get_user_meta($user_id, $user_meta_name, true);
        if ($user_meta) {
            $user_meta = unserialize($user_meta);
            $is_in_meta = in_array($post_id, $user_meta);
        }
    }
    if (!$user_meta || !$is_in_meta ) {
        if (!$user_meta) {
            $user_meta = array($post_id);
        } else {
            array_unshift($user_meta, $post_id);
        }
        action_update_meta($user_meta_name, $user_meta);
        if ($is_comment) {
            $g = (int) get_comment_meta($post_id, $key, true);
        } else {
            $g = (int) get_post_meta($post_id, $key, true);
        }
        if (!$g) {
            $g = 0;
        }
        if ($is_comment) {
            update_comment_meta($post_id, $key, $g + 1);
        } else {
            update_post_meta($post_id, $key, $g + 1);
        }
        print_r(json_encode(array('error' => 0, 'action' => 'add', 'post_id' => $post_id, '_post' => json_encode( $_POST),'key' => $key, 'is_in_meta' => $is_in_meta, 'user_meta' => $user_meta, 'msg' => $add_msg, 'cuont' => $g + 1)));
        exit;
    }
    if ($is_in_meta) {
        $h = array_search($post_id, $user_meta);
        unset($user_meta[$h]);
        action_update_meta($user_meta_name, $user_meta);
        if ($is_comment) {
            $g = (int) get_comment_meta($post_id, $key, true);
        } else {
            $g = (int) get_post_meta($post_id, $key, true);
        }
        if (!$g) {
            $g = 0;
        }
        if ($is_comment) {
            update_comment_meta($post_id, $key, $g - 1);
        } else {
            update_post_meta($post_id, $key, $g - 1);
        }
        print_r(json_encode(array('error' => 0, 'action' => 'remove', 'key' => $key, '_post' => json_encode( $_POST),'user_meta' => $user_meta, 'msg' => $rem_msg, 'cuont' => $g - 1)));
        exit;
    }
    exit;
}
function action_update_meta($user_meta_name, $value)
{
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, $user_meta_name, serialize($value));
    }
}
function checkpost($j)
{
    return isset($_POST[$j]) ? trim(htmlspecialchars($_POST[$j], ENT_QUOTES)) : '';
}
function isInStr($k, $l)
{
    $k = '-_-!' . $k;
    return (bool) strpos($k, $l);
}
