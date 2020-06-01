<?php

/**
 * 下载页面
 */


require dirname(__FILE__) . '/../../../../wp-load.php';
date_default_timezone_set('Asia/Shanghai');

if (empty($_GET['down_id']) || empty($_GET['post_id'])) {
    wp_safe_redirect(home_url());
}

$down_id = $_GET['down_id'];
$post_id = $_GET['post_id'];

$down = zibpay_get_post_down_array($post_id);
$file_dir = $down[$down_id]['link'];

if (empty($file_dir)) {
    wp_die('下载信息错误！');
    exit;
}

$file_dir = $down[$down_id]['link'];
$home = home_url('/');

if(stripos($file_dir,$home) === 0){
    $file_dir = str_replace($home,"",$file_dir);
}

if (substr($file_dir, 0, 7) == 'http://' || substr($file_dir, 0, 8) == 'https://' || substr($file_dir, 0, 10) == 'thunder://' || substr($file_dir, 0, 7) == 'magnet:' || substr($file_dir, 0, 5) == 'ed2k:' || substr($file_dir, 0, 4) == 'ftp:') {
    $file_path = chop($file_dir);
    echo "<script type='text/javascript'>window.location='$file_path';</script>";
    exit;
}
$file_dir = chop($file_dir);
$file_dir = ABSPATH.$file_dir;
if (!file_exists($file_dir)) {
    return false;
}
$temp = explode("/", $file_dir);

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".end($temp)."\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($file_dir));
ob_end_flush();
@readfile($file_dir);

exit;
