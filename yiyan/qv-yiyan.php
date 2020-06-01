<?php
//获取句子文件的绝对路径
$path = dirname(__FILE__);
$file = file($path."/qv-yiyan.txt");
//随机读取一行
$arr = mt_rand( 0, count( $file ) - 2 );
$arr = ($arr % 2 === 0) ? $arr + 1 : $arr;
$content  = trim($file[$arr]).'/&/'.trim($file[$arr+1]);
//编码判断，用于输出相应的响应头部编码
if (isset($_GET['charset']) && !empty($_GET['charset'])) {
    $charset = $_GET['charset'];
    if (strcasecmp($charset,"gbk") == 0 ) {
        $content = mb_convert_encoding($content,'gbk', 'utf-8');
    }
} else {
    $charset = 'utf-8';
}
header("Content-Type: text/html; charset=$charset");
echo $content;