<?php

/**
 * 微信企业支付异步回调
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require dirname(__FILE__) . '/../../../../../../wp-load.php';
ob_end_clean();

//获取参数
$config = zibpay_get_payconfig('official_wechat');

$params = new \Yurun\PaySDK\Weixin\Params\PublicParams;

$params->appID = $config['appid'];
$params->mch_id = $config['merchantid'];
$params->key = $config['key'];

// SDK实例化，传入公共配置
$sdk = new \Yurun\PaySDK\Weixin\SDK($params);

class PayNotify extends \Yurun\PaySDK\Weixin\Notify\Pay
{
    /**
     * 后续执行操作
     * @return void
     */
    protected function __exec()
    {
        // 支付成功处理，一般做订单处理，$this->data 是从微信发送来的数据
        // file_put_contents(__DIR__ . '/notify_result.txt', date('Y-m-d H:i:s') . ':' . var_export($this->data, true));

        //准备订单数据

        //准备订单数据
        $pay = array(
            'order_num' => $this->data['out_trade_no'],
            'pay_type' => 'weixin',
            'pay_price' => $this->data['total_fee'] / 100,
            'pay_num' => $this->data['transaction_id'],
            'other' => '',
        );

        //实例化订单函数
        $zibpay = new Zibpay_Order;
        // 更新订单状态
        $order = $zibpay->payment_order($pay);

        // 告诉微信我处理过了，不要再通过了
        $this->reply('SUCCESS', 'OK');
    }
}

$payNotify = new PayNotify;

try {
    $sdk->notify($payNotify);
} catch (Exception $e) {
    //失败的操作
    file_put_contents(__DIR__ . '/notify_result.txt', $e->getMessage() . ':' . var_export($payNotify->data, true));
}
