<?php

/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

require dirname(__FILE__) . '/../../../../../wp-load.php';

if (!empty($_POST['action']) && $_POST['action'] == 'zib_get_update') {
    echo json_encode(zib_curl_get_update_data());
    exit;
}

if (!empty($_POST['action']) && $_POST['action'] == 'zib_skip_update') {
    zib_skip_this_update();
    $result_data = array(
        'result' => true,
        'msg' => '不在提醒此次更新',
        'obj' => ''
    );
    echo json_encode($result_data);
    exit;
}

if (!empty($_POST['authorization']) && $_POST['action'] == 'zib_authorization') {

    $result_data = array(
        'result' => false,
        'msg' => '请输入授权码',
        'obj' => ''
    );
    $result_data['result'] = $result_obj['result'];
    $result_data['msg'] = '恭喜您，授权成功';
    echo json_encode($result_data);
    exit;
}

if (!empty($_POST['authorization']) && $_POST['action'] == 'zib_delete_authorization') {

    $result_data = array(
        'result' => true,
        'msg' => '已撤销授权，如需继续使用请重新授权',
        'obj' => ''
    );

    $result_obj = zib_curl_delete_authorization();

    echo json_encode($result_data);
    exit;
}
