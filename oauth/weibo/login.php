<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();

//获取后台配置
$weiboConfig = get_oauth_config('weibo');
$weiboOAuth = new \Yurun\OAuthLogin\Weibo\OAuth2($weiboConfig['appid'], $weiboConfig['appkey'], $weiboConfig['backurl']);

if ($weiboConfig['agent']) {
    $weiboOAuth->loginAgentUrl = esc_url(home_url('/oauth/weiboagent'));
}

// 所有为null的可不传，这里为了演示和加注释就写了
$url = $weiboOAuth->getAuthUrl();
$_SESSION['YURUN_WEIBO_STATE'] = $weiboOAuth->state;
// 储存返回页面
$_SESSION['oauth_rurl']  = !empty($_REQUEST["rurl"]) ? $_REQUEST["rurl"] : '';

header('location:' . $url);
