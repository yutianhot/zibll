<?php
/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

if (!$_POST && !$_POST['action']) {
    exit;
}

require dirname(__FILE__) . '/../../../../wp-load.php';
if (is_user_logged_in()) {
    print_r(json_encode(array('error' => 1, 'msg' => '你已经登录，请刷新页面')));
    exit;
}
if (!$_POST['action']) {
    exit;
}
//print_r(json_encode(array('error' => 1, '_POST' => $_POST)));
//exit();

switch ($_POST['action']) {
    case 'signin':

        if (_pz('user_verification')) {
            if (_new_strlen($_POST['canvas_yz']) < 4) {
                print_r(json_encode(array('error' => 1, 'msg' => '请输入图形验证码')));
                exit();
            }
        }

        if (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {
            $user_data = get_user_by('login', $_POST['username']);
            if (empty($user_data)) {
                print_r(json_encode(array('error' => 1, 'msg' => '用户名或密码错误')));
                exit();
            }
        } else {
            $user_data = get_user_by('email', $_POST['username']);
            if (empty($user_data)) {
                print_r(json_encode(array('error' => 1, 'msg' => '邮箱或密码错误')));
                exit();
            }
        }

        $username = $user_data->user_login;

        if ($_POST['remember']) $_POST['remember'] = "true";
        else $_POST['remember'] = "false";

        $login_data = array(
            'user_login' => $username,
            'user_password' => $_POST['password'],
            'remember' => $_POST['remember']
        );

        $user_verify = wp_signon($login_data, false);

        if (is_wp_error($user_verify)) {
            print_r(json_encode(array('error' => 1, 'msg' => '账号或密码错误')));
            exit();
        }

        print_r(json_encode(array('error' => 0, 'msg' => '成功登录，页面跳转中')));
        exit();

        break;

    case 'signup':

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            print_r(json_encode(array('error' => 1, 'msg' => '邮箱格式错误')));
            exit();
        }
        $captch = _pz('user_signup_captch');

        if ($captch) {
            session_start();
            if (empty($_POST['captch'])) {
                print_r(json_encode(array('error' => 1, 'msg' => '请输入验证码')));
                exit();
            }
            if (!zib_is_captcha($_POST['email'], $_POST['captch']) ) {
                echo(json_encode(array('error' => 1, 'msg' => '验证码错误')));
                exit();
            }
        }

        if (_new_strlen($_POST['password2']) < 6) {
            print_r(json_encode(array('error' => 1, 'msg' => '密码太短,至少6位')));
            exit();
        }

        if ($_POST['password2'] !== $_POST['repassword']) {
            print_r(json_encode(array('error' => 1, 'msg' => '两次密码输入不一致')));
            exit();
        }

        if (_pz('user_verification')) {
            if (_new_strlen($_POST['canvas_yz']) < 4) {
                print_r(json_encode(array('error' => 1, 'msg' => '请输入图形验证码')));
                exit();
            }
        }

        if (is_disable_username($_POST['name'])) {
            print_r(json_encode(array('error' => 1, 'msg' => '昵称含保留或非法字符')));
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
        $status = wp_create_user($_POST['name'], $_POST['password2'], $_POST['email']);

        if (is_wp_error($status)) {
            $err = $status->errors;
            // print_r($err);
            if (!empty($err['existing_user_login'])) {
                print_r(json_encode(array('error' => 1, 'wp_error'=> json_encode($status), 'msg' => '用户名已存在，换一个试试')));
                exit();
            } else if (!empty($err['existing_user_email'])) {
                print_r(json_encode(array('error' => 1, 'wp_error'=> json_encode($status), 'msg' => '邮箱已存在，您可以尝试找回密码')));
                exit();
            }
            print_r(json_encode(array('error' => 1, 'wp_error'=> json_encode($status),'msg' => '注册失败，请稍后再试')));
            exit();
        }

        $login_data2 = array(
            'user_login' => $_POST['name'],
            'user_password' => $_POST['password2'],
        );

        $user_verify = wp_signon($login_data2, false);

        print_r(json_encode(array('error' => 0, 'msg' => '注册成功，欢迎您：' . $_POST['name'])));
        exit();

        break;

    default:
        # code...
        break;
}

exit();
