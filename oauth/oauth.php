<?php
/**
 * 获取配置信息
 */
function get_oauth_config($type='qq')
{
    $args = array(
        'appid' => _pz('oauth_'.$type.'_appid'),
        'appkey' => _pz('oauth_'.$type.'_appkey'),
        'backurl' => esc_url(home_url('/oauth/'.$type.'/callback')),
        'agent' => _pz('oauth_'.$type.'_agent',false),
    );
    return $args;
}

/**
 * 处理返回数据，更新用户资料
 */
function zib_oauth_update_user($args)
{
    /** 需求数据明细 */
    $defaults = array(
        'type'   => '',
        'openid' => '',
        'name' => '',
        'avatar' => '',
        'description' => '',
        'getUserInfo' => array(),
    );

    $args = wp_parse_args((array) $args, $defaults);

    // 初始化信息
    $openid_meta_key = 'oauth_'.$args['type'].'_openid';
    $openid = $args['openid'];
    $return_data = array(
        'redirect_url' => '',
        'msg' => '',
        'error' => true,
    );;

    global $wpdb, $current_user;

    // 查询该openid是否已存在
    $user_exist = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key=%s AND meta_value=%s", $openid_meta_key, $openid));

    // 查询已登录用户
    $current_user_id = get_current_user_id();

    //如果已经登录，且该openid已经存在
    if ($current_user_id && isset($user_exist) && $current_user_id != $user_exist) {
        $return_data['msg'] = '绑定失败，可能之前已有其他账号绑定，请先登录并解绑。';
        return $return_data;
    }

    if (isset($user_exist) && (int) $user_exist > 0) {
        // 该开放平台账号已连接过WP系统，再次使用它直接登录
        $user_exist = (int) $user_exist;

        //登录
        $user = get_user_by('id', $user_exist);
        wp_set_current_user($user_exist);
        wp_set_auth_cookie($user_exist ,true, false);
        do_action('wp_login', $user->user_login, $user);

        $return_data['redirect_url'] = get_author_posts_url($user_exist);  //重定向链接到用户中心
        $return_data['error'] = false;
    } elseif ($current_user_id) {
        // 已经登录，但openid未占用，则绑定，更新用户字段
        // 更新用户mate
        $args['user_id'] = $current_user_id;
        //绑定用户不更新以下数据
        $args['name'] = '';
        $args['description'] = '';

        zib_oauth_update_user_mate($args);
        // 准备返回数据
        $return_data['redirect_url'] = get_author_posts_url($current_user_id);  //重定向链接到用户中心
        $return_data['error'] = false;
    } else {
        // 既未登录且openid未占用，则新建用户并绑定
        $login_name = "user" . mt_rand(1000, 9999) . mt_rand(1000, 9999);
        $user_pass  = wp_create_nonce(rand(10, 1000));

        $user_id = wp_create_user($login_name,$user_pass);
        if (is_wp_error($user_id)) {
            //新建用户出错
            $return_data['msg'] = $user_id->get_error_message();
        } else {
            //新建用户成功
            update_user_meta($user_id, 'oauth_new' , $args['type']);  /**标记为系统新建用户 */
            //更新用户mate
            $args['user_id'] = $user_id;
            zib_oauth_update_user_mate($args);
            $return_data['redirect_url'] = get_author_posts_url($user_id);  //重定向链接到用户中心

            //登录
            $user = get_user_by('id', $user_id);
            wp_set_current_user( $user_id, $user->user_login );
            wp_set_auth_cookie($user_id, true, false);
            do_action('wp_login', $user->user_login, $user);
            // 准备返回数据
            $return_data['redirect_url'] = get_author_posts_url($user_id);  //重定向链接到用户中心
            $return_data['error'] = false;
        }
    }
    return $return_data;
}

function zib_oauth_update_user_mate($args)
{
    /** 需求数据明细 */
    $defaults = array(
        'user_id' => '',  /**用户id */
        'type'   => '',
        'openid' => '',
        'name' => '',
        'avatar' => '',
        'description' => '',
        'getUserInfo' => array(),
    );
    $args = wp_parse_args((array) $args, $defaults);

    update_user_meta($args['user_id'], 'oauth_'.$args['type'].'_openid',$args['openid']);
    update_user_meta($args['user_id'], 'oauth_'.$args['type'].'_getUserInfo',$args['getUserInfo']);
    $custom_avatar = get_user_meta($args['user_id'], 'custom_avatar', true);
    if(!empty($args['avatar']) && !$custom_avatar){
        update_user_meta($args['user_id'], 'custom_avatar',$args['avatar']);
    }
    if(!empty($args['name'])){
        $user_datas =array(
            'ID' => $args['user_id'],
            'display_name' => $args['name'],
            'nickname'     => $args['name'],
        );
        wp_update_user($user_datas);
    }
    if(!empty($args['description'])){
        update_user_meta($args['user_id'], 'description',$args['avatar']);
    }
}