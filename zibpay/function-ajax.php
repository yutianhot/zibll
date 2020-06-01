<?php
// 挂钩AJAX-发起订单
function zibpay_initiate_order($order_data = array())
{
	date_default_timezone_set('Asia/Shanghai');

	if (empty($_POST['post_id']) || empty($_POST['order_price'])) {
		echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '商品数据设置错误')));
		exit();
	}
	if (!is_user_logged_in() && !_pz('pay_no_logged_in', true)) {
		echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请先登录')));
		exit();
	}

	$pay_mate = get_post_meta($_POST['post_id'], 'posts_zibpay', true);

	$price = round($pay_mate['pay_price'], 2);

	$pay_type = $pay_mate['pay_type'];
	$pay_type = !empty($pay_mate['pay_type']) ? $pay_mate['pay_type'] : '';
	$product_id = !empty($pay_mate['product_id']) ? $pay_mate['product_id'] : '';

	//准备订单数据
	$pay = array(
		'post_id' => $_POST['post_id'],
		'order_price' => $price,
		'order_type' => $pay_type,
		'product_id' => $product_id,
		'other' => '',
	);

	$zibpay = new Zibpay_Order;
	//创建新订单
	$order = $zibpay->add_order($pay);

	if (!$order) {
		echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '订单创建失败')));
		exit();
	}
	/**添加创建订单成功挂钩 */
	do_action('zibpay_add_order_success', $order, $pay_mate);

	//设置浏览器缓存

	$expire = time() + 3600 * 24 * _pz('pay_cookie_day', '15');
	setcookie('zibpay_' . $order['post_id'], $order['order_num'], $expire, '/', '', false);

	//准备支付数据
	$order_type_name = zibpay_get_pay_type_name($pay_type);
	$order_name = get_bloginfo('name') . '-' . $order_type_name;
	$order_data = array(
		'payment_method' => !empty($_POST['pay_type']) ? $_POST['pay_type'] : 'wechat',
		'order_num' => $order['order_num'],
		'order_price' => $order['order_price'],
		'order_name' => !empty($_POST['order_name']) ? $_POST['order_name'] : $order_name,
		'return_url' => !empty($_POST['return_url']) ? $_POST['return_url'] : get_permalink(),
		/**回调链接判断 */
	);

	$initiate_pay = zibpay_initiate_pay($order_data);
	/**添加创建订单成功挂钩 */
	do_action('zibpay_initiate_pay', $initiate_pay);

	/**返回数据 */
	echo (json_encode($initiate_pay));
	exit();
}
add_action('wp_ajax_initiate_pay', 'zibpay_initiate_order');
add_action('wp_ajax_nopriv_initiate_pay', 'zibpay_initiate_order');

// 挂钩AJAX-确认支付订单
function zibpay_check_pay($order_data = array())
{
	header("Content-type:application/json;character=utf-8");

	date_default_timezone_set('Asia/Shanghai');

	if (empty($_POST['order_num'])) {
		echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '还未生成订单')));
		exit();
	}
	$check_order_num = $_POST['order_num'];
	/**根据订单号查询订单 */
	global $wpdb;
	$order_check = $wpdb->get_row("SELECT * FROM `$wpdb->zibpay_order` WHERE `order_num` = '$check_order_num'");
	echo (json_encode($order_check));
	exit();
}

add_action('wp_ajax_check_pay', 'zibpay_check_pay');
add_action('wp_ajax_nopriv_check_pay', 'zibpay_check_pay');

/**发起支付函数 */
function zibpay_initiate_pay($order_data)
{
	//初始化默认数据
	$defaults = array(
		'order_num' => date("mdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999), // 订单号
		'order_price' => '',
		'order_name' => get_bloginfo('name') . '支付',
		'return_url' => home_url(),
		/**回调链接判断 */
		'payment_method' => 'wechat',
	);
	$order_data = wp_parse_args((array) $order_data, $defaults);
	if (!$order_data['order_price']) {
		return array('error' => 1, 'ys' => 'danger', 'msg' => '订单价格错误');
	}
	if (empty($order_data['order_num']) || empty($order_data['order_price']) || empty($order_data['order_name'])) {
		return array('error' => 1, 'ys' => 'danger', 'msg' => '未获取到商品数据');
	}
	/**准备付款接口 */
	$pay_sdk = '';
	if ($order_data['payment_method'] == 'wechat') {
		$pay_sdk = _pz('pay_wechat_sdk_options');
	} else {
		$pay_sdk = _pz('pay_alipay_sdk_options');
	}
	if (!$pay_sdk || $pay_sdk == 'null') {
		return array('error' => 1, 'ys' => 'danger', 'msg' => '网站未接入此收款方式，请联系站长');
	}
	switch ($pay_sdk) {
			// 根据支付接口循环进行支付流程
		case 'official_alipay':
			$payresult = zibpay_initiate_official_alipay($order_data);

			$payresult = array_merge($payresult, $order_data);

			return $payresult;
			break;
		case 'official_wechat':
			$payresult = zibpay_initiate_official_wechat($order_data);

			$payresult = array_merge($payresult, $order_data);

			return $payresult;
			break;
		case 'xunhupay_wechat':
			$payresult = zibpay_initiate_xunhupay($order_data, 'wechat');

			$payresult = array_merge($payresult, $order_data);

			return $payresult;
			break;

		case 'xunhupay_alipay':
			$payresult = zibpay_initiate_xunhupay($order_data, 'alipay');

			$payresult = array_merge($payresult, $order_data);

			return $payresult;
			break;
	}
}


/**支付宝官方发起支付 */
function zibpay_initiate_official_alipay($order_data = array(), $payment = 'alipay')
{

	//获取参数
	$config = zibpay_get_payconfig('official_alipay');

	// 判断是否开启H5
	if (wp_is_mobile() && $config['h5'] && $config['webappid'] && $config['webprivatekey']) {
		/**手机网站支付产品 */
		// 公共配置
		$params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
		$params->appID = $config['webappid'];
		$params->appPrivateKey = $config['webprivatekey'];

		// SDK实例化，传入公共配置
		$pay = new \Yurun\PaySDK\AlipayApp\SDK($params);

		// 支付接口
		$request = new \Yurun\PaySDK\AlipayApp\Wap\Params\Pay\Request;
		$request->notify_url = get_template_directory_uri() . '/zibpay/shop/alipay/notify.php'; // 支付后通知地址（作为支付成功回调，这个可靠）
		$request->return_url = !empty($order_data['return_url']) ? $order_data['return_url'] : home_url(); // 支付后跳转返回地址
		$request->businessParams->out_trade_no = $order_data['order_num']; // 商户订单号
		$request->businessParams->total_amount = $order_data['order_price']; // 价格
		$request->businessParams->subject = $order_data['order_name']; // 商品标题

		$payurl = $pay->redirectExecuteUrl($request);

		return array('open_url' => 1, 'url' => $payurl);
	} elseif ($config['webappid'] && $config['webprivatekey'] && (empty($config['privatekey']) || empty($config['appid']) || empty($config['publickey']))) {
		/**电脑网站支付 */
		// 公共配置
		$params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
		$params->appID = $config['webappid'];
		$params->appPrivateKey = $config['webprivatekey'];
		// SDK实例化，传入公共配置
		$pay = new \Yurun\PaySDK\AlipayApp\SDK($params);

		// 支付接口
		$request = new \Yurun\PaySDK\AlipayApp\Page\Params\Pay\Request;
		$request->notify_url = get_template_directory_uri() . '/zibpay/shop/alipay/notify.php'; // 支付后通知地址（作为支付成功回调，这个可靠）
		$request->return_url = !empty($order_data['return_url']) ? $order_data['return_url'] : home_url(); // 支付后跳转返回地址
		$request->businessParams->out_trade_no = $order_data['order_num']; // 商户订单号
		$request->businessParams->total_amount = $order_data['order_price']; // 价格
		$request->businessParams->subject = $order_data['order_name']; // 商品标题

		$payurl = $pay->redirectExecuteUrl($request);

		return array('open_url' => 1, 'url' => $payurl);
	} else {
		/**当面付 */

		if (empty($config['privatekey']) || empty($config['appid']) || empty($config['publickey'])) {
			return array('error' => 1, 'ys' => 'danger', 'msg' => '支付宝后台配置无效');
		}

		// 配置文件
		$params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
		$params->appID = $config['appid'];
		$params->appPrivateKey = $config['privatekey'];
		$params->appPublicKey = $config['publickey'];
		// SDK实例化，传入公共配置
		$pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
		// 支付接口
		$request = new \Yurun\PaySDK\AlipayApp\FTF\Params\QR\Request;
		$request->notify_url    = get_template_directory_uri() . '/zibpay/shop/alipay/notify2.php'; // 支付后通知地址
		$request->businessParams->out_trade_no = $order_data['order_num']; // 商户订单号
		$request->businessParams->total_amount = $order_data['order_price']; // 价格
		$request->businessParams->subject      = $order_data['order_name']; // 商品标题

		// 调用接口
		try {
			$data = $pay->execute($request);
		} catch (Exception $e) {
			var_dump($pay->response->body());
		}

		if (!empty($data['alipay_trade_precreate_response']['qr_code'])) {
			$data['alipay_trade_precreate_response']['url_qrcode'] = zibpay_get_Qrcode($data['alipay_trade_precreate_response']['qr_code']);
			$data['alipay_trade_precreate_response']['msg'] = '处理完成，请扫码支付';
			return $data['alipay_trade_precreate_response'];
		} else {
			return array('error' => 1, 'ys' => 'danger', 'errcode' => $pay->getError(), 'msg' => $pay->getErrorCode());
		}
	}
}


/**微信企业支付发起支付 */
function zibpay_initiate_official_wechat($order_data = array())
{

	//获取参数
	$config = zibpay_get_payconfig('official_wechat');
	if (empty($config['merchantid']) || empty($config['appid']) || empty($config['key'])) {
		return array('error' => 1, 'ys' => 'danger', 'msg' => '微信支付后台配置无效');
	}

	$params = new \Yurun\PaySDK\Weixin\Params\PublicParams;

	$params->appID = $config['appid'];
	$params->mch_id = $config['merchantid'];
	$params->key = $config['key'];
	// SDK实例化，传入公共配置
	$pay = new \Yurun\PaySDK\Weixin\SDK($params);
	// 判断是否开启手机版跳转
	if (wp_is_mobile() && $config['h5'] && !zibpay_is_wechat_app()) {
		// H5支付接口
		$request = new \Yurun\PaySDK\Weixin\H5\Params\Pay\Request;
		$request->body = $order_data['order_name']; // 商品描述
		$request->out_trade_no = $order_data['order_num']; // 订单号
		$request->total_fee = $order_data['order_price'] * 100; // 订单总金额，单位为：分
		$request->spbill_create_ip = !empty($order_data['ip_address']) ? $order_data['ip_address'] : '127.0.0.1'; // 客户端ip，必须传正确的用户ip，否则会报错
		$request->notify_url = get_template_directory_uri() . '/zibpay/shop/weixin/notify.php'; // 异步通知地址
		$request->scene_info = new \Yurun\PaySDK\Weixin\H5\Params\SceneInfo;
		//场景信息
		$request->scene_info->type = 'Wap'; // 可选值：IOS、Android、Wap
		$request->scene_info->wap_url = !empty($order_data['return_url']) ? $order_data['return_url'] : home_url(); //h5支付返回地址
		$request->scene_info->wap_name = get_bloginfo('name');  //WAP 网站名
		// 调用接口
		$result = $pay->execute($request);
		if ($pay->checkResult()) {
			/**支付订单成功 */
			$result['open_url'] = true;
			$result['url'] = $result['mweb_url'];
			return $result;
		} else {
			return array('error' => 1, 'ys' => 'danger', 'errcode' => $pay->getErrorCode(), 'msg' => $pay->getError());
		}
	} else {
		// PC扫码支付接口
		$request = new \Yurun\PaySDK\Weixin\Native\Params\Pay\Request;
		$request->body = $order_data['order_name']; // 商品描述
		$request->out_trade_no = $order_data['order_num']; // 订单号
		$request->total_fee = $order_data['order_price'] * 100; // 订单总金额，单位为：分
		$request->spbill_create_ip = empty($order_data['ip_address']) ? $order_data['ip_address'] : '127.0.0.1'; // 客户端ip，必须传正确的用户ip，否则会报错
		$request->notify_url = get_template_directory_uri() . '/zibpay/shop/weixin/notify.php'; // 异步通知地址
		// 调用接口
		$result = $pay->execute($request);
		$shortUrl = $result['code_url'];
		if (is_array($result) && $shortUrl) {
			$result['url_qrcode'] = zibpay_get_Qrcode($shortUrl);
			return $result;
		} else {
			return array('error' => 1, 'ys' => 'danger', 'errcode' => $pay->getError(), 'msg' => $pay->getErrorCode());
			exit;
		}
	}
}


/**讯虎虎皮椒发起支付 */
function zibpay_initiate_xunhupay($order_data = array(), $payment = 'alipay')
{

	//获取参数
	$config = zibpay_get_payconfig('xunhupay');
	if ($payment == 'wechat' && empty($config['wechat_appid']) && empty($config['wechat_appsecret'])) {
		return array('error' => 1, 'ys' => 'danger', 'msg' => '未设置appid或者appsecret');
	}
	if ($payment == 'alipay' && empty($config['alipay_appid']) && empty($config['alipay_appsecret'])) {
		return array('error' => 1, 'ys' => 'danger', 'msg' => '未设置appid或者appsecret');
	}

	require_once(get_theme_file_path('/zibpay/sdk/xunhupay/api.php'));

	//  return array('error' => 1, 'ys' => 'danger', 'msg' => '未获取到商品数据');

	$trade_order_id = $order_data['order_num'];

	if ($payment == 'wechat') {
		$appid = $config['wechat_appid'];
		$appsecret = $config['wechat_appsecret'];
		$payment = 'wechat';
	} else {
		$appid = $config['alipay_appid'];
		$appsecret = $config['alipay_appsecret'];
		$payment = 'alipay';
	}
	//支付方式：wechat(微信接口)|alipay(支付宝接口)
	$my_plugin_id = 'zibpay_xunhupay';

	$data = array(
		'version' => '1.1', //固定值，api 版本，目前暂时是1.1
		'lang' => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
		'plugins' => $my_plugin_id, //必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
		'appid' => $appid, //必须的，APPID
		'trade_order_id' => $trade_order_id, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
		'payment' => $payment, //必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
		'total_fee' => $order_data['order_price'], //人民币，单位精确到分(测试账户只支持0.1元内付款)
		'title' => $order_data['order_name'], //必须的，订单标题，长度32或以内
		'time' => time(), //必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
		'notify_url' => get_template_directory_uri() . '/zibpay/shop/xunhupay/notify.php', //必须的，支付成功异步回调接口
		'return_url' => !empty($order_data['return_url']) ? $order_data['return_url'] : home_url(), //必须的，支付成功后的跳转地址
		'callback_url' => !empty($order_data['return_url']) ? $order_data['return_url'] : home_url(), //必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
		'modal' => null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
		'nonce_str' => str_shuffle(time()) //必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
	);

	$hashkey = $appsecret;
	$data['hash'] = XH_Payment_Api::generate_xh_hash($data, $hashkey);
	/**
	 * 个人支付宝/微信官方支付，支付网关：https://api.xunhupay.com
	 * 微信支付宝代收款，需提现，支付网关：https://pay.wordpressopen.com
	 */

	$url = 'https://api.xunhupay.com/payment/do.html';

	try {
		$response = XH_Payment_Api::http_post($url, json_encode($data));
		/**
		 * 支付回调数据
		 * @var array(
		 *      order_id,//支付系统订单ID
		 *      url//支付跳转地址
		 *  )
		 */
		$result = $response ? json_decode($response, true) : null;
		if (!$result) {
			throw new Exception('Internal server error', 500);
		}

		$hash = XH_Payment_Api::generate_xh_hash($result, $hashkey);
		if (!isset($result['hash']) || $hash != $result['hash']) {
			throw new Exception(__('Invalid sign!', XH_Wechat_Payment), 40029);
		}

		if ($result['errcode'] != 0) {
			throw new Exception($result['errmsg'], $result['errcode']);
		}

		$pay_url = $result['url'];

		$_is_wechat_app = XH_Payment_Api::is_wechat_app();
		$result['open_url'] = wp_is_mobile();
		return $result;
		// header("Location: $pay_url");
		// json_encode(array('status' => '1', 'type' => '2', 'rurl' => $pay_url, 'qrcode' => '', 'msg' => $order_trade_no));
		// exit;
	} catch (Exception $e) {
		//echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
		return array('error' => 1, 'ys' => 'danger', 'errcode' => $e->getCode(), 'msg' => $e->getMessage());
		//TODO:处理支付调用异常的情况
	}
}
