<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();

if (empty($_SESSION['YURUN_WEIXIN_STATE'])) {
    wp_safe_redirect(home_url());
    exit;
}

//获取后台配置
$wxConfig = get_oauth_config('weixin');
$wxOAuth = new \Yurun\OAuthLogin\Weixin\OAuth2($wxConfig['appid'], $wxConfig['appkey']);

if ($wxConfig['agent']) {
    $wxOAuth->loginAgentUrl = esc_url(home_url('/oauth/weixinagent'));
}

// 获取accessToken，把之前存储的state传入，会自动判断。获取失败会抛出异常！
$accessToken = $wxOAuth->getAccessToken($_SESSION['YURUN_WEIXIN_STATE']);

$openid   = $wxOAuth->openid; // 唯一ID
$userInfo = $wxOAuth->getUserInfo(); //第三方用户信息
// 处理本地业务逻辑
if ($openid && $userInfo) {

    $oauth_data = array(
        'type'   => 'weixin',
        'openid' => $openid,
        'name' => $userInfo['nickname'],
        'avatar' => $userInfo['headimgurl'],
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
