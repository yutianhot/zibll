<?php

/**
 * 支付宝同步回调
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require dirname(__FILE__) . '/../../../../../../wp-load.php';
ob_end_clean();

$user_id = get_current_user_id();
if ($user_id) {
    wp_safe_redirect(get_author_posts_url($user_id) . '?page=pay');
    return;
}

wp_safe_redirect(home_url());
