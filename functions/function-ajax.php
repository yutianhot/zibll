<?php

/**设置验证码 */
function zib_get_captcha($counts = 6)
{
    $originalcode = '0,1,2,3,4,5,6,7,8,9';
    $originalcode = explode(',', $originalcode);
    $countdistrub = 10;
    $_dscode      = "";
    for ($j = 0; $j < $counts; $j++) {
        $dscode = $originalcode[rand(0, $countdistrub - 1)];
        $_dscode .= $dscode;
    }
    return strtolower($_dscode);
}

/**发送验证码 */
function zib_send_captcha($to, $type = 'email', $title = '', $message = '')
{
    session_start();
    $code = zib_get_captcha(6);
    /**保存 */
    $_SESSION['zib_captcha'] = $code;
    $_SESSION['zib_verification_to'] = $to;

    $send = '';
    $result = false;
    $blog_name = get_bloginfo('name');

    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {

        $title = '[' . $blog_name . ']' . $title;
        $send .= $message . "\r\n\r\n";
        $send .= '您的邮箱为：' . $to . "\r\n\r\n";
        $send .= '您的验证码为：';
        $send .= '<p style="font-size:34px;color:#3095f1;"><span style="border-bottom: 1px dashed rgb(204, 204, 204); z-index: 1; position: static;">' . $code . '</span></p>';

        $result = wp_mail($to, $title, $send);
    }

    return $result;
}
/**验证码效验 */
function zib_is_captcha($to, $code)
{

    if (empty($_SESSION['zib_captcha']) || $_SESSION['zib_captcha'] != $code || empty($_SESSION['zib_verification_to']) || $_SESSION['zib_verification_to'] != $to) {
        return false;
    } else {
        return true;
    }
}

/**前端AJAX发送验证码 */
function zib_ajax_signup_captcha()
{
    header('Content-type:application/json; Charset=utf-8');
    $captcha = !empty($_POST['captcha_type']) ? $_POST['captcha_type'] : 'email';

    switch ($captcha) {
        case 'email':
            if (empty($_POST['email'])) {
                echo json_encode(array('error' => 1, 'msg' => '请输入邮箱帐号'));
                exit;
            }
            global $wpdb;
            $user_email = !empty($_POST['email']) ? esc_sql($_POST['email']) : null;
            $user_email = apply_filters('user_registration_email', $user_email);
            $user_email = $wpdb->_escape(trim($user_email));

            if (email_exists($user_email) && empty($_POST['repeat'])) {
                echo json_encode(array('error' => 1, 'msg' => '邮箱已存在'));
                exit;
            }
            $send = zib_send_captcha($user_email,'email','收到验证码','您正在本站进行验证操作，如非您本人操作，请忽略此邮件。验证码30分钟内有效，如果超时请重新获取');
            if($send){
                echo json_encode(array('error' => 0, 'msg' => '验证码已发送，请在邮箱查看'));
                exit;
            }else{
                echo json_encode(array('error' => 1, 'msg' => '验证码发送失败'));
                exit;
            }
            break;
    }
}
add_action('wp_ajax_signup_captcha', 'zib_ajax_signup_captcha');
add_action('wp_ajax_nopriv_signup_captcha', 'zib_ajax_signup_captcha');
