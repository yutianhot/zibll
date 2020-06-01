<?php
/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

//上传图像
function php_upload($file='file')
{
    require dirname(__FILE__) . '/../../../../wp-load.php';
    if (!$_FILES) {
        return false;
    }

    if ($_FILES) {
        $files = $_FILES[$file];
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            $attach_id = media_handle_upload($file, 0);
        if (is_wp_error($attach_id)) {
            print_r(json_encode(array('error' => 1, '_FILES' => $_FILES ,'ys' => 'warning',  'is_wp_error' => $attach_id, 'msg' => '上传出错，请稍候再试')));
            exit();
        } else {
            return $attach_id;
        }
    }
}
