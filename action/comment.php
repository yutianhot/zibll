<?php
/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    echo '错误请求';
	exit;
}

require( dirname(__FILE__).'/../../../../wp-load.php' );

function err($ErrMsg) {
    header('HTTP/1.1 405 Method Not Allowed');
    echo $ErrMsg;
    exit;
}

$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {
            $data = $comment->get_error_data();
            if ( ! empty( $data ) ) {
            	err($comment->get_error_message());
            } else {
                exit;
            }
        }
$user = wp_get_current_user();
do_action('set_comment_cookies', $comment, $user);

zib_get_comments_list($comment);

exit;
?>
