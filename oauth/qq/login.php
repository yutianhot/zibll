<?php
require dirname(__FILE__) . '/../../../../../wp-load.php';
//启用 session
session_start();
// 要求noindex
wp_no_robots();

//获取后台配置
$qqConfig = get_oauth_config('qq');
$qqOAuth  = new \Yurun\OAuthLogin\QQ\OAuth2($qqConfig['appid'], $qqConfig['appkey'], $qqConfig['backurl']);
if ($qqConfig['agent']) {
    $qqOAuth->loginAgentUrl = esc_url(home_url('/oauth/qqagent'));
}

$url = $qqOAuth->getAuthUrl();
// 存储sdk自动生成的state，回调处理时候要验证
$_SESSION['YURUN_QQ_STATE'] = $qqOAuth->state;
// 储存返回页面
$_SESSION['oauth_rurl']  = !empty($_GET["rurl"]) ? $_GET["rurl"] : '';

// 跳转到登录页
header('location:' . $url);
