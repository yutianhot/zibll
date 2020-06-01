<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();
if (empty($_SESSION['YURUN_QQ_STATE'])) {
    wp_safe_redirect(home_url());
    exit;
}

//获取后台配置
$qqConfig = get_oauth_config('qq');

$qqOAuth  = new \Yurun\OAuthLogin\QQ\OAuth2($qqConfig['appid'], $qqConfig['appkey'], $qqConfig['backurl']);

if ($qqConfig['agent']) {
    $qqOAuth->loginAgentUrl = esc_url(home_url('/oauth/qqagent'));
}

// 获取accessToken，把之前存储的state传入，会自动判断。获取失败会抛出异常！
$accessToken = $qqOAuth->getAccessToken($_SESSION['YURUN_QQ_STATE']);

//验证AccessToken是否有效
$areYouOk = $qqOAuth->validateAccessToken($accessToken);

$openid   = $qqOAuth->openid; // 唯一ID
$userInfo = $qqOAuth->getUserInfo(); //第三方用户信息

// 处理本地业务逻辑
if ($openid && $userInfo) {

    $oauth_data = array(
        'type'   => 'qq',
        'openid' => $openid,
        'name' => $userInfo['nickname'],
        'avatar' => $userInfo['figureurl_qq_2'],
        'description' => '',
        'getUserInfo' => $userInfo,
    );

    $oauth_result = zib_oauth_update_user($oauth_data);

    if($oauth_result['error']){
        wp_die('<meta charset="UTF-8" />'.($oauth_result['msg']?$oauth_result['msg']:'处理失败'));
        exit;
    }else{
        $rurl = !empty($_SESSION['oauth_rurl']) ? $_SESSION['oauth_rurl'] : $oauth_result['redirect_url'];
        wp_safe_redirect($rurl);
        exit;
    }
}

wp_safe_redirect(home_url());
exit;