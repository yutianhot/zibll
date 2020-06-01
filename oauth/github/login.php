<?php
require dirname(__FILE__) . '/../../../../../wp-load.php';
//启用 session
session_start();
// 要求noindex
wp_no_robots();

//获取后台配置
$githubConfig = get_oauth_config('github');
$githubOAuth  = new \Yurun\OAuthLogin\Github\OAuth2($githubConfig['appid'], $githubConfig['appkey'], $githubConfig['backurl']);
$url = $githubOAuth->getAuthUrl();
if ($githubConfig['agent']) {
    $githubOAuth->loginAgentUrl = esc_url(home_url('/oauth/githubagent'));
}
// 存储sdk自动生成的state，回调处理时候要验证
$_SESSION['YURUN_GITHUB_STATE'] = $githubOAuth->state;
// 储存返回页面
$_SESSION['oauth_rurl']  = !empty($_REQUEST["rurl"]) ? $_REQUEST["rurl"] : '';

// 跳转到登录页
header('location:' . $url);