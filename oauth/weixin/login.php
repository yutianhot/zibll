<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();

//获取后台配置
$wxConfig = get_oauth_config('weixin');
$wxOAuth = new \Yurun\OAuthLogin\Weixin\OAuth2($wxConfig['appid'], $wxConfig['appkey']);

if ($wxConfig['agent']) {
    $wxOAuth->loginAgentUrl = esc_url(home_url('/oauth/weixinagent'));
}

$url = $wxOAuth->getAuthUrl(
	$wxConfig['backurl'],	// 回调地址，登录成功后返回该地址，为null则取来源页面
	null,										// state 为空自动生成
	null										// scope 只要登录默认为空即可
);

$_SESSION['YURUN_WEIXIN_STATE'] = $wxOAuth->state;
// 储存返回页面
$_SESSION['oauth_rurl']  = !empty($_REQUEST["rurl"]) ? $_REQUEST["rurl"] : '';

header('location:' . $url);