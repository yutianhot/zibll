<?php

/**
 * 支付宝当面付异步通知-当面付
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require dirname(__FILE__) . '/../../../../../../wp-load.php';
ob_end_clean();

if (empty($_POST)) {
    echo '非法请求';
    exit();
}

$config = zibpay_get_payconfig('official_alipay');

// 配置文件
$params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
$params->appID = $config['appid'];
$params->appPrivateKey = $config['privatekey'];
$params->appPublicKey = $config['publickey'];

$pay = new \Yurun\PaySDK\AlipayApp\SDK($params);

if(!empty($_POST['out_trade_no']) && !empty($_POST['total_amount']) && !empty($_POST['trade_no']) && !empty($_POST['trade_status']) && $_POST['trade_status'] == 'TRADE_SUCCESS' ){
        // 通知验证成功，可以通过POST参数来获取支付宝回传的参数
        $pay = array(
            'order_num' => $_POST['out_trade_no'],
            'pay_type' => 'alipay',
            'pay_price' => $_POST['total_amount'],
            'pay_num' => $_POST['trade_no'],
            'other' => '',
        );
        //实例化订单函数
        $zibpay = new Zibpay_Order;
        // 更新订单状态
        $order = $zibpay->payment_order($pay);
        /**返回不在发送异步通知 */
        echo 'success';exit();
}

if ($pay->verifyCallback($_POST)) {

} else {
    // 通知验证失败
    //$content = var_export($_POST, true) . PHP_EOL . 'verify:' . var_export($pay->verifyCallback($_POST), true);
    //file_put_contents(__DIR__ . '/notify_result.txt', $content);
}
/**返回不在发送异步通知 */
echo "error";exit();