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
include 'php_upload.php';

$cuid = get_current_user_id();

if (!is_user_logged_in()) {
    echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
    exit;
}
if ($cuid != $_POST['user_id']) {
    echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
    exit;
}

if (empty($_POST['action']) || empty($_POST['user_id'])) {
    exit;
}

switch ($_POST['action']) {

    case 'oauth.email':

        session_start();
        if (empty($_POST['email'])) {
            echo (json_encode(array('error' => 1, 'msg' => '请输入邮箱帐号')));
            exit();
        }
        if (_pz('email_set_captch',true)) {
            if (empty($_POST['captch'])) {
                echo (json_encode(array('error' => 1, 'msg' => '请输入验证码')));
                exit();
            }
            if (!zib_is_captcha($_POST['email'], $_POST['captch'])) {
                echo (json_encode(array('error' => 1, 'msg' => '验证码错误')));
                exit();
            }
        }
        $status = wp_update_user(
            array(
                'ID' => $cuid,
                'user_email' => $_POST['email']
            )
        );

        if (is_wp_error($status)) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '修改失败，请稍后再试')));
            exit();
        }

        echo (json_encode(array('error' => 0, 'msg' => '邮箱绑定成功')));

        exit();
        break;

    case 'oauth.untying':

        delete_user_meta($_POST['user_id'], 'oauth_' . $_POST['type'] . '_openid');
        delete_user_meta($_POST['user_id'], 'oauth_' . $_POST['type'] . '_getUserInfo');

        echo (json_encode(array('error' => 0, 'ys' => '', 'msg' => '已解除绑定')));

        exit();
        break;

    case 'set.rewards':
        if (!wp_verify_nonce($_POST['upload_rewards_nonce'], 'upload_rewards')) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
            exit();
        }

        $weixin_lao_id = get_user_meta($cuid, 'rewards_wechat_image_id', true);
        $alipay_lao_id = get_user_meta($cuid, 'rewards_alipay_image_id', true);

        if (empty($_FILES['weixin']) && empty($_FILES['alipay']) && !$weixin_lao_id && !$alipay_lao_id) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请选择收款二维码')));
            exit();
        }

        if (!empty($_FILES['weixin'])) {
            $weixin_img_id = php_upload('weixin');

            if ($weixin_lao_id) {
                wp_delete_attachment($weixin_lao_id, true);
            }
            update_user_meta($cuid, 'rewards_wechat_image_id', $weixin_img_id);
        }

        if (!empty($_FILES['alipay'])) {
            $alipay_img_id = php_upload('alipay');
            if ($alipay_lao_id) {
                wp_delete_attachment($alipay_lao_id, true);
            }
            update_user_meta($cuid, 'rewards_alipay_image_id', $alipay_img_id);
        }

        if ($_POST['rewards_title']) update_user_meta($cuid, 'rewards_title', $_POST['rewards_title']);

        echo (json_encode(array('error' => 0, 'msg' => '设置成功', 'weixin_url' => $weixin_url[0], 'alipay_url' => $alipay_url[0], 'weixin_img_id' => $weixin_img_id, 'alipay_img_id' => $alipay_img_id, '_POST' => $_POST, '_FILES' => $_FILES)));

        exit();
        break;
    case 'password.edit':


        if (empty($_POST['password']) || empty($_POST['password2'])) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '密码不能为空')));
            exit();
        }

        if (strlen($_POST['password']) < 6) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '密码至少6位')));
            exit();
        }

        if ($_POST['password'] !== $_POST['password2']) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '两次密码输入不一致')));
            exit();
        }

        global $wp_hasher;
        require_once(ABSPATH . WPINC . '/class-phpass.php');

        if (!empty($_POST['oauth_new'])) {
            $oauth_new = get_user_meta($cuid, 'oauth_new', true);
            if ($_POST['oauth_new'] != $oauth_new) {
                echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败')));
                exit();
            }

            $status = wp_update_user(
                array(
                    'ID' => $cuid,
                    'user_pass' => $_POST['password']
                )
            );

            if (is_wp_error($status)) {
                echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '修改失败，请稍后再试')));
                exit();
            }
            delete_user_meta($cuid, 'oauth_new');
            echo (json_encode(array('error' => 0, 'msg' => '修改成功，下次请使用新密码登录')));
            exit();
        }

        if (empty($_POST['passwordold'])) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '密码不能为空')));
            exit();
        }

        if ($_POST['passwordold'] == $_POST['password']) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '新密码和原密码不能相同')));
            exit();
        }

        $wp_hasher = new PasswordHash(8, TRUE);

        if (!$wp_hasher->CheckPassword($_POST['passwordold'], $current_user->user_pass)) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '原密码错误')));
            exit();
        }

        $status = wp_update_user(
            array(
                'ID' => $cuid,
                'user_pass' => $_POST['password']
            )
        );

        if (is_wp_error($status)) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '修改失败，请稍后再试')));
            exit();
        }

        echo (json_encode(array('error' => 0, 'msg' => '修改成功，下次请使用新密码登录')));
        exit();
        break;

    case 'upload.cover':
        if (!wp_verify_nonce($_POST['upload_cover_nonce'], 'upload_cover')) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
            exit();
        }

        if (empty($_FILES['file'])) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请选择图像')));
            exit();
        }

        $img_id = php_upload();
        $image_url = wp_get_attachment_image_src($img_id, 'full');
        $lao_id = get_user_meta($cuid, 'cover_image_id', true);

        if ($lao_id) {
            wp_delete_attachment($lao_id, true);
        }

        update_user_meta($cuid, 'cover_image_id', $img_id);
        update_user_meta($cuid, 'cover_image', $image_url[0]);

        echo (json_encode(array('error' => 0, 'msg' => '封面图修改成功', 'img_id' => $img_id, 'img_url' => $image_url[0])));
        exit();
        break;

    case 'upload.avatar':
        if (!wp_verify_nonce($_POST['upload_avatar_nonce'], 'upload_avatar')) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
            exit();
        }

        if (empty($_FILES['file'])) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请选择图像')));
            exit();
        }

        $img_id = php_upload();
        $image_url = wp_get_attachment_image_src($img_id, 'thumbnail');
        $lao_id = get_user_meta($cuid, 'custom_avatar_id', true);
        if ($lao_id) {
            wp_delete_attachment($lao_id, true);
        }
        update_user_meta($cuid, 'custom_avatar_id', $img_id);
        update_user_meta($cuid, 'custom_avatar', $image_url[0]);
        echo (json_encode(array('error' => 0, 'msg' => '头像修改成功', 'img_id' => $img_id, 'img_url' => $image_url[0])));
        exit();
        break;

    case 'edit.datas':

        if (empty($_POST['name']) || (!empty($_POST['name']) && _new_strlen($_POST['name']) > 12) || (!empty($_POST['name']) && _new_strlen($_POST['name']) < 2)) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '昵称不能为空且限制在2-12字内')));
            exit();
        }

        if (empty($_POST['desc']) || _new_strlen($_POST['desc']) < 4) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请填写签名且不少于5个字符')));
            exit();
        }

        if (is_disable_username($_POST['name'])) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '昵称含保留或非法字符')));
            exit();
        }

        if (_pz('no_repetition_name', true)) {
            $db_name = '';
            global $wpdb;
            $db_name = $wpdb->get_var("SELECT id FROM $wpdb->users WHERE `user_nicename`='" . $_POST['name'] . "' OR `display_name`='" . $_POST['name'] . "' ");

            if ($db_name && $db_name != $cuid) {
                echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '昵称已经存在，请换一个试试')));
                exit();
            }
        }

        if ($_POST['url'] && (!zib_is_url($_POST['url']))) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '网址格式错误')));
            exit();
        }

        if ($_POST['url'] && !$_POST['url_name']) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请输入个人网站名称')));
            exit();
        }

        if ($_POST['url_name'] && !$_POST['url']) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请输入个人网站链接')));
            exit();
        }

        if ($_POST['address'] && _new_strlen($_POST['address']) > 50) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '居住地格式错误')));
            exit();
        }

        if ($_POST['weibo'] && (!zib_is_url($_POST['weibo']) || _new_strlen($_POST['weibo']) > 100)) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '微博格式错误')));
            exit();
        }

        if ($_POST['github'] && (!zib_is_url($_POST['github']) || _new_strlen($_POST['github']) > 100)) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => 'GitHub格式错误')));
            exit();
        }

        if ($_POST['qq'] && !preg_match("/^[1-9]\d{4,16}$/", $_POST['qq'])) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => 'QQ格式错误')));
            exit();
        }

        if ($_POST['weixin'] && _new_strlen($_POST['weixin']) > 30) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '微信字数过长，限制在30字内')));
            exit();
        }

        if ($_POST['desc']) update_user_meta($cuid, 'description', $_POST['desc']);
        if ($_POST['qq']) update_user_meta($cuid, 'qq', $_POST['qq']);
        if ($_POST['weixin']) update_user_meta($cuid, 'weixin', $_POST['weixin']);
        if ($_POST['weibo']) update_user_meta($cuid, 'weibo', $_POST['weibo']);
        if ($_POST['github']) update_user_meta($cuid, 'github', $_POST['github']);
        if ($_POST['url_name']) update_user_meta($cuid, 'url_name', $_POST['url_name']);
        if ($_POST['gender']) update_user_meta($cuid, 'gender', $_POST['gender']);
        if ($_POST['address']) update_user_meta($cuid, 'address', $_POST['address']);
        if ($_POST['privacy']) update_user_meta($cuid, 'privacy', $_POST['privacy']);

        $datas = array('ID' => $cuid);
        if ($_POST['url']) $datas['user_url'] = $_POST['url'];
        if ($_POST['name']) {
            $datas['display_name'] = $_POST['name'];
            $datas['nickname'] = $_POST['name'];
        };

        $status = wp_update_user($datas);

        if (!$status || is_wp_error($status)) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '修改失败，请稍后再试')));
            exit();
        }

        echo (json_encode(array('error' => 0)));
        exit();
        break;
}

echo (json_encode($_POST));
exit;
