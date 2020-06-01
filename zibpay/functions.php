<?php

/**
 * 子比主题支付系统
 */

require_once(get_theme_file_path('/zibpay/class/order-class.php'));
require_once(get_theme_file_path('/zibpay/function-ajax.php'));
require_once(get_theme_file_path('/zibpay/function-download.php'));
require_once(get_theme_file_path('/zibpay/function-user.php'));

require_once(get_theme_file_path('/framework/includes/class-posts-meta.php'));

/**挂钩到主题启动 */

function zibpay_creat_table_order()
{
    $zibpay = new Zibpay_Order;
    $zibpay->add_order_showdb();
}
add_action('after_setup_theme', 'zibpay_creat_table_order');

/**
 * 创建后台管理菜单
 */
function add_settings_menu()
{
    add_menu_page('Zibll商城', 'Zibll商城', 'administrator',  'zibpay_page', 'zibpay_page', 'dashicons-cart');
    add_submenu_page('zibpay_page', '商品明细', '商品明细', 'administrator', 'zibpay_product_page', 'zibpay_product_page');
    add_submenu_page('zibpay_page', '订单明细', '订单明细', 'administrator', 'zibpay_order_page', 'zibpay_order_page');
}
add_action('admin_menu', 'add_settings_menu');

function zibpay_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/index.php';
}
function zibpay_order_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/order.php';
}
function zibpay_product_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/product.php';
}
/**
 * 排队插入JS文件
 */
add_action('admin_enqueue_scripts', 'zibpay_setting_scripts');
function zibpay_setting_scripts()
{
    if (isset($_GET['page']) &&  stristr($_GET['page'], "zibpay")) {
        wp_enqueue_style('zibpay_page', get_template_directory_uri() . '/zibpay/assets/css/pay-page.css');
        wp_enqueue_script('jquery_form', get_template_directory_uri() . '/zibpay/assets/js/jquery.form.js', array('jquery'));
        wp_enqueue_script('highcharts', get_template_directory_uri() . '/zibpay/assets/js/highcharts.js', array('jquery'));
        wp_enqueue_script('westeros', get_template_directory_uri() . '/zibpay/assets/js/westeros.js', array('jquery', 'highcharts'));
        wp_enqueue_script('zibpay_page', get_template_directory_uri() . '/zibpay/assets/js/pay-page.js', array('jquery', 'jquery_form'));
    }
}

/**
 * 文章编辑时，增加付费功能
 */
function zibpay_add_meta_box()
{

    $meta = array(
        'id' => 'posts_zibpay',
        'name' => '付费功能',
        'type' => array('post'),
    );
    $args = array();

    $args[] = array(
        'name' => __('付费模式', 'zib_language'),
        'id' => 'pay_type',
        'question' => '',
        'std' => 'no',
        'question' => zibpay_get_edit_posts_meta_doc(),
        'type' => "radio",
        'desc' => __(' ', 'zib_language'),
        'options' => array(
            'no' => __('关闭', 'zib_language'),
            1 => __('付费阅读', 'zib_language'),
            2 => __('付费下载', 'zib_language'),
        )
    );

    $args[] = array(
        'name' => __('执行价', 'zib_language'),
        'id' => 'pay_price',
        'desc' => __('（必填）填0则为免费,填非数字会报错', 'zib_language'),
        'std' => '0.01',
        'type' => 'text',
        'class' => 'mini pay-hide'
    );

    $args[] = array(
        'name' => __('原价', 'zib_language'),
        'id' => 'pay_original_price',
        'desc' => __('（选填）此价格会显示在执行价格前面，并划掉', 'zib_language'),
        'std' => '',
        'type' => 'text',
        'class' => 'mini pay-hide'
    );

    $args[] = array(
        'name' => __('下载链接', 'zib_language'),
        'id' => 'pay_download',
        'desc' => __('', 'zib_language'),
        'class' => 'pay-download',
        'std' => '',
        'type' => 'textarea',
    );
    $args[] = array(
        'id' => 'pay_html',
        'desc' => __('（必填）一行一个按钮，格式：链接 | 按钮显示的文字 | 更多内容 | 颜色代码', 'zib_language'),
        'class' => 'op-multicheck pay-download',
        'std' => '',
        'question' => zibpay_get_edit_posts_meta_download_help(),
        'type' => 'html',
        'html' => zibpay_get_edit_posts_meta_download(),
    );
    $args[] = array(
        'name' => __('标题', 'zib_language'),
        'id' => 'pay_title',
        'class' => 'pay-hide',
        'desc' => __('（选填）默认显示文章标题', 'zib_language'),
        'std' => '',
        'type' => 'textarea',
        'settings' => array(
            'rows' => 1
        ),
    );
    $args[] = array(
        'name' => __('简介', 'zib_language'),
        'id' => 'pay_doc',
        'class' => 'pay-hide',
        'desc' => __('（选填）默认显示付费提示', 'zib_language'),
        'std' => '',
        'settings' => array(
            'rows' => 1
        ),
        'type' => 'textarea',
    );
    $args[] = array(
        'name' => __('更多详情', 'zib_language'),
        'class' => 'pay-hide',
        'id' => 'pay_details',
        'desc' => __('（选填）可插入任意的HTML代码', 'zib_language'),
        'std' => '',
        'type' => 'textarea',
    );
    $args[] = array(
        'name' => __('额外隐藏内容', 'zib_language'),
        'class' => 'pay-hide',
        'id' => 'pay_extra_hide',
        'desc' => __('（选填）可插入任意的HTML代码', 'zib_language'),
        'std' => '',
        'type' => 'textarea',
    );

    $meta = apply_filters('zib_add_pay_meta_box_meta', $meta);
    $args = apply_filters('zib_add_pay_meta_box_args', $args);
    new Zib_add_meta_box($meta, $args);
}
add_action('init', 'zibpay_add_meta_box');

/**文章内容底部插入产品 */
function zibpay_posts_pay_content($post)
{
    $pay_mate = get_post_meta($post->ID, 'posts_zibpay', true);
    $payment = _pz('default_payment', 'wechat');

    if (empty($pay_mate['pay_type']) || $pay_mate['pay_type'] == 'no') return;

    // 查询是否已经购买
    $paid = zibpay_is_paid($post->ID);

    if ($paid) {
        echo zibpay_posts_paid_box($pay_mate, $paid);
    } else {
        echo zibpay_posts_pay_box($pay_mate, $post->ID);
    }
}
add_action('zib_posts_content_after', 'zibpay_posts_pay_content');

/**文章已经付费盒子 */
function zibpay_posts_paid_box($pay_mate, $paid)
{

    $pay_doc = '';
    $pay_num = '';
    $paid_name = zibpay_get_paid_type_name($paid['paid_type']);
    $paid_name = '<b class="but jb-red mr6" style=" font-size: 12px; padding: 2px 10px; line-height: 1.4; "><i class="fa fa-check mr6" aria-hidden="true"></i>' . $paid_name . '</b>';
    $_thumb = zib_post_thumbnail('', 'fit-cover radius8');

    $order_type_name = zibpay_get_pay_type_name($pay_mate['pay_type']);
    $order_type_class = 'order-type-' . $pay_mate['pay_type'];

    $posts_title = get_the_title() . get_the_subtitle(false);
    $pay_title = !empty($pay_mate['pay_title']) ? $pay_mate['pay_title'] : $posts_title;
    $pay_title = '<b>' . $pay_title . '</b>';

    if ($paid['paid_type'] == 'paid' && !empty($paid['post_id'])) {
        $mark = _pz('pay_mark', '￥');
        $mark = '<span class="pay-mark">' . $mark . '</span>';
        $price = round($pay_mate['pay_price'], 2);
        $vip_price = !empty($pay_mate['vip_price']) ? round($pay_mate['vip_price'], 2) : '';

        $pay_doc = '付款时间：' . $paid['pay_time'];
        $pay_doc .= '<sapn class="pull-right em12">' . $mark . $price . '</sapn>';

        $pay_num = '订单号：' . zibpay_get_order_num_link($paid['order_num']);
        $pay_title = $paid_name . $pay_title;
    } else {
        $pay_title = '<div style="margin-bottom:10px">' . $pay_title . '</div><div>' . $paid_name . '</div>';
    }

    $pay_details = !empty($pay_mate['pay_details']) ? '<div class="pay-details">' . $pay_mate['pay_details'] . '</div>' : '';
    $pay_extra_hide = !empty($pay_mate['pay_extra_hide']) ? '<div class="pay-extra-hide">' . $pay_mate['pay_extra_hide'] . '</div>' : '';

    $dowmbox = '';
    if ($pay_mate['pay_type'] == '2') {
        $dowmbox = '<div class="hidden-box show"><div class="hidden-text"><i class="fa fa-download mr6" aria-hidden="true"></i>资源下载</div>' . zibpay_get_post_down_buts($pay_mate) . '</div>';
    }

    $con = '<div class="zib-widget pay-box ' . $order_type_class . '" id="posts-pay">
                <div class="pay-tag abs-center">
                ' . $order_type_name . '
                </div>
						<ul class="list-inline">
						    <li>
						        <div class="pay-thumbnail">
						            ' . $_thumb . '
						        </div>
						    </li>
						    <li>
						        <dl>
                                <div>' . $pay_title . '</div>
						            <div class="meta-time em09 muted-2-color">' . $pay_num . '</div>
						            <dd class="meta-time em09 muted-2-color">' . $pay_doc . '</dd>
						        </dl>
						    </li>
                        </ul>
                        ' . $dowmbox . $pay_extra_hide . $pay_details . '
            </div>';

    return $con;
}
function zibpay_posts_free_box($pay_mate, $paid)
{
}
/**文章付费盒子 */
function zibpay_get_pay_type_name($pay_type)
{
    $order_type_name = '';
    if ($pay_type == '1') {
        $order_type_name = '付费阅读';
    } elseif ($pay_type == '2') {
        $order_type_name = '付费资源';
    } elseif ($pay_type == '3') {
        $order_type_name = '产品购买';
    }
    return $order_type_name;
}

/**文章付费盒子 */
function zibpay_posts_pay_box($pay_mate, $post_id)
{
    $pay_mate = $pay_mate ? $pay_mate : get_post_meta($post_id, 'posts_zibpay', true);
    if (empty($pay_mate['pay_type']) || $pay_mate['pay_type'] == 'no') return;

    $_thumb = zib_post_thumbnail('', 'fit-cover radius8');

    $order_type_name = zibpay_get_pay_type_name($pay_mate['pay_type']);
    $order_type_class = 'order-type-' . $pay_mate['pay_type'];

    global $wpdb;

    $cuont = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order where post_id=$post_id and status=1");
    $cuont = '<span class="pay-cuont but c-green hollow">已售 ' . $cuont . '</span>';
    $mark = _pz('pay_mark', '￥');
    $mark = '<span class="pay-mark">' . $mark . '</span>';

    $order_name = get_bloginfo('name') . '-' . $order_type_name;
    $price = round($pay_mate['pay_price'], 2);
    $vip_price = !empty($pay_mate['vip_price']) ? round($pay_mate['vip_price'], 2) : '';

    $price_con = $mark . $price;

    $original_price = empty($pay_mate['pay_original_price']) ? '' : $pay_mate['pay_original_price'];
    if ($original_price) {
        $original_price = '<span class="original-price mr10">' . $mark . $original_price . '</span>';
    }

    $payment = _pz('default_payment', 'wechat');
    $pay_wechat_sdk = _pz('pay_wechat_sdk_options');
    $pay_alipay_sdk = _pz('pay_alipay_sdk_options');
    if ($payment == 'wechat' && (!$pay_wechat_sdk || $pay_wechat_sdk == 'null')) $payment = 'alipay';
    if ($payment == 'alipay' && (!$pay_alipay_sdk || $pay_alipay_sdk == 'null')) $payment = 'wechat';

    $posts_title = get_the_title() . get_the_subtitle(false);
    $pay_title = !empty($pay_mate['pay_title']) ? $pay_mate['pay_title'] : $posts_title;

    $pay_doc = !empty($pay_mate['pay_doc']) ? $pay_mate['pay_doc'] : '此内容为' . $order_type_name . '，请付费后查看';
    $pay_details = !empty($pay_mate['pay_details']) ? '<div class="pay-details">' . $pay_mate['pay_details'] . '</div>' : '';

    $open_url = wp_is_mobile();
    $args = array(
        'class' => '',
        'payment' => $payment,
        'order_price' => $price,
        'order_name' => $order_name
    );
    $vip_but = '<a class="but jb-blue radius" href=""><i class="fa fa-angle-right mr6" aria-hidden="true"></i>开通VIP</a>';
    $modal = zibpay_qrcon_pay_modal($args);

    $pay_button = '<button class="but jb-red radius initiate-pay ml10 mr6" pay_type="' . $payment . '"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>立即购买</button>';

    if (wp_is_mobile() || _pz('pay_show_allbut')) {
        $pay_button = '';
        if ($pay_alipay_sdk && $pay_alipay_sdk != 'null') {
            $pay_button .= '<button class="but jb-blue radius initiate-pay ml10" pay_type="alipay"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>支付宝购买</button>';
        }
        if ($pay_wechat_sdk && $pay_wechat_sdk != 'null') {
            $pay_button .= '<button class="but jb-green radius initiate-pay ml10 mr6" pay_type="wechat"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>微信购买</button>';
        }
    }

    $remind = '';
    if (!is_user_logged_in()) {
        if (!_pz('pay_no_logged_in', true)) {
            $pay_button = '<a href="javascript:;" class="but jb-blue signin-loader ml10 mr6"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>登录购买</a>';
        } else {
            $remind =  '<div class="pay-extra-hide">' . _pz('pay_no_logged_remind') . '</div>';
        }
    }


    if ((!$pay_wechat_sdk || $pay_wechat_sdk == 'null') && (!$pay_alipay_sdk || $pay_alipay_sdk == 'null')) {
        $pay_button = '<span class="pay-mark">暂时无法购买，请于站长联系</span>';
        if (is_super_admin()) {
            $pay_button = '<a href="' . of_get_menuurl('options-group-15-tab') . '" class="but c-red mr6">请先配置收款方式！</a>';
        }
    }

    $con = '<div class="zib-widget pay-box ' . $order_type_class . '" id="posts-pay">
        <div class="pay-tag abs-center">
        ' . $order_type_name . '
        </div>

        <form class="pay-form">

						<ul class="list-inline">
						    <li>
						        <div class="pay-thumbnail">
						            ' . $_thumb . '
						        </div>
						    </li>
						    <li>
						        <dl>
						        <dt class="">' . $pay_title . '</dt>
						        <dt class="pay-box-price">' . $original_price . $price_con . $cuont . '</dt>
						            <dd class="meta-time em09 muted-2-color">' . $pay_doc . '</dd>
						        </dl>
						    </li>
                        </ul>' . $remind . '
						    <div class="pay-button">
						   		 ' . $pay_button . '
                            </div>
                            ' . $pay_details . '

            <input type="hidden" name="post_id" value="' . $post_id . '">
            <input type="hidden" name="order_price" value="' . $price . '">
            <input type="hidden" name="order_name" value="' . $order_name . '">
            <input type="hidden" name="open_url" value="' . $open_url . '">
            <input type="hidden" name="return_url" value="' . get_permalink() . '">
            <input type="hidden" name="order_type" value="' . $pay_mate['pay_type'] . '">
            <input type="hidden" name="action" value="initiate_pay">
        </form>
    </div>';

    return $con . $modal;
}
/**扫码付款模态框 */
function zibpay_qrcon_pay_modal($args = array())
{
    $defaults = array(
        'class' => '',
        'payment' => 'wechat',
        'order_price' => 0.01,
        'order_name' => '',
    );
    $args = wp_parse_args((array) $args, $defaults);

    $class = 'pay-payment ' . $args['payment'];
    $class .= ' ' . $args['class'];
    $pay_wechat_sdk = _pz('pay_wechat_sdk_options');
    $pay_alipay_sdk = _pz('pay_alipay_sdk_options');
    $pay_switch_button = '';
    if ($pay_alipay_sdk && $pay_alipay_sdk != 'null') {
        $pay_switch_button .= '<button class="but c-blue btn-block hollow t-alipay initiate-pay-switch" pay_type="alipay">切换支付宝付款</button>';
    }
    if ($pay_wechat_sdk && $pay_wechat_sdk != 'null') {
        $pay_switch_button .= '<button class="but c-green btn-block hollow t-wechat initiate-pay-switch" pay_type="wechat">切换微信付款</button>';
    }

    $alipay_sys = get_template_directory_uri() . '/zibpay/assets/img/alipay-sys.png';
    $wechat_sys = get_template_directory_uri() . '/zibpay/assets/img/wechat-sys.png';

    $qrcode_defaults = get_template_directory_uri() . '/zibpay/assets/img/pay-qrcode.png';

    $con = '<div class="modal fade" id="modal_pay" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="' . $class . '">
                <div class="modal-body modal-pay-body">
                    <div class="row-5 hide-sm">
                        <img class="pay-sys lazyload t-wechat" data-src="' . $alipay_sys . '">
                        <img class="pay-sys lazyload t-alipay" data-src="' . $wechat_sys . '">
                    </div>
                    <div class="row-5">
                    <div class="pay-qrcon">
                        <div class="qrcon">
                            <div class="pay-logo-header theme-box"><span class="pay-logo"></span><span class="pay-logo-name t-wechat">支付宝</span><span class="pay-logo-name t-alipay">微信支付</span></div>
                            <div class="title em09 muted-2-color">' . $args['order_name'] . '</div>
                            <div class="price em12">￥' . $args['order_price'] . '</div>
                            <div class="pay-qrcode">
                                <img class="lazyload" data-src="' . $qrcode_defaults . '">
                            </div>
                        </div>
                    <div class="pay-switch">' . $pay_switch_button . '</div>
                    <div class="pay-notice"><div class="notice load">正在生成订单，请稍候</div></div>
                    </div>
				</div>
                </div>
            </div>
        </div>
    </div>';
    return $con;
}


/**前台载入js文件 */
function zibpay_load_scripts()
{
    wp_enqueue_script('zibpay', get_template_directory_uri() . '/zibpay/assets/js/pay.js', array('jquery'), THEME_VERSION, true);
    wp_localize_script('zibpay', 'zibpay_ajax_url', admin_url("admin-ajax.php"));
}

add_action('wp_enqueue_scripts', 'zibpay_load_scripts');

/**后台生成二维码图片 */
function zibpay_get_Qrcode($url)
{
    //引入phpqrcode类库
    require_once(get_theme_file_path('/zibpay/class/qrcode.class.php'));
    $errorCorrectionLevel = 'L'; //容错级别
    $matrixPointSize      = 6; //生成图片大小
    ob_start();
    QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
    $data = ob_get_contents();
    ob_end_clean();

    $imageString = base64_encode($data);
    header("content-type:application/json; charset=utf-8");
    return 'data:image/jpeg;base64,' . $imageString;
}

/**获取支付参数函数 */
function zibpay_get_payconfig($type)
{

    if ($type == 'xunhupay') {
        return array(
            'wechat_appid'     => _pz('xunhupay_wechat_appid'),
            'wechat_appsecret' => _pz('xunhupay_wechat_appsecret'),
            'alipay_appid'     => _pz('xunhupay_alipay_appid'),
            'alipay_appsecret' => _pz('xunhupay_alipay_appsecret'),
        );
    } elseif ($type == 'official_wechat') {
        return array(
            'merchantid'     => _pz('official_wechat_merchantid'),
            'appid'     => _pz('official_wechat_appid'),
            'key' => _pz('official_wechat_appkey'),
            'jsapi' => _pz('official_wechat_jsapi', true),
            'h5' => _pz('official_wechat_h5', false),
        );
    } elseif ($type == 'official_alipay') {
        return array(
            'appid'     => _pz('official_alipay_appid'),
            'privatekey' => _pz('official_alipay_privatekey'),
            'publickey' => _pz('official_alipay_publickey'),
            'pid'     => _pz('enterprise_alipay_pid'),
            'md5key'     => _pz('enterprise_alipay_md5key'),
            'webappid'     => _pz('enterprise_alipay_appid'),
            'webprivatekey'     => _pz('enterprise_alipay_privatekey'),
            'h5' => _pz('enterprise_alipay_h5', false),
        );
    }
}

/**根据订单号获取链接 */
function zibpay_get_order_num_link($order_num, $class = '')
{
    $href = '';
    $user_id = get_current_user_id();
    if ($user_id) {
        $href = get_author_posts_url($user_id) . '?page=pay';
    }
    $a = '<a target="_blank" href="' . $href . '" class="' . $class . '">' . $order_num . '</a>';
    if ($href) {
        return $a;
    } else {
        return '<span class="' . $class . '">' . $order_num . '</span>';
    }
}

/**判断是否在微信APP内 */
function zibpay_is_wechat_app()
{
    return strripos($_SERVER['HTTP_USER_AGENT'], 'micromessenger');
}

/**查看权限转文字 */
function zibpay_get_paid_type_name($pay_type)
{
    $order_type_name = '';
    if ($pay_type == 'paid') {
        $order_type_name = '已支付';
    } elseif ($pay_type == 'free') {
        $order_type_name = '免费资源';
    }
    return $order_type_name;
}
/**判断查查看权限 */
function zibpay_is_paid($post_id, $user_id = '', $product_id = '')
{
    // 准备判断参数
    if (!$post_id) $post_id = get_queried_object_id();
    if (!$user_id) $user_id = get_current_user_id();
    $posts_pay = get_post_meta($post_id, 'posts_zibpay', true);
    if (empty($posts_pay['pay_price'])) {
        $pay_order = array('paid_type' => 'free');
        return $pay_order;
    }

    global $wpdb;

    if ($user_id) {
        // 如果已经登录，根据用户id查找数据库订单
        $pay_order = $wpdb->get_row("SELECT * FROM $wpdb->zibpay_order where user_id=$user_id and post_id=$post_id and status=1");
        if ($pay_order) {
            $pay_order = (array) $pay_order;
            $pay_order['paid_type'] = 'paid';
            return $pay_order;
        }
    }
    // 如果未登录，
    //根据浏览器Cookie查找
    if (isset($_COOKIE['zibpay_' . $post_id])) {
        $pay_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->zibpay_order} WHERE order_num = %s and post_id=%d and status=1", $_COOKIE['zibpay_' . $post_id], $post_id));

        if ($pay_order) {
            $pay_order = (array) $pay_order;
            $pay_order['paid_type'] = 'paid';
            return $pay_order;
        }
    } else {
        //根据IP地址查找


    }
    return false;
}

/**判断是否已经存在订单 */
function zibpay_is_order_exists($post_id, $user_id = '', $product_id = '')
{
    // 准备判断参数
    if (!$user_id) $user_id = get_current_user_id();
    global $wpdb;

    if ($user_id) {
        // 如果已经登录，根据用户id查找数据库订单
        $pay_order = $wpdb->get_row("SELECT * FROM $wpdb->zibpay_order where user_id=$user_id and post_id=$post_id");
        if ($pay_order) return $pay_order;
    }
    // 如果未登录，
    //根据浏览器Cookie查找
    if (isset($_COOKIE['zibpay_' . $post_id])) {
        $pay_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->zibpay_order} WHERE order_num = %s and post_id=%d", $_COOKIE['zibpay_' . $post_id], $post_id));

        if ($pay_order) return $pay_order;
    } else {
        //根据IP地址查找
    }
    return false;
}

/** 获取用户IP地址*/
function zibpay_get_user_ip()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';
}

/**创建编辑器短代码 */
//添加隐藏内容，付费可见
function zibpay_to_show($atts, $content = null)
{

    $a = '#posts-pay';
    $_hide = '<div class="hidden-box"><a class="hidden-text" href="javascript:(scrollTo(\'' . $a . '\',-120));"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;此处内容已隐藏，请付费后查看</a></div>';
    $_show = '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容</div>' . do_shortcode($content) . '</div>';
    global $post;

    $pay_mate = get_post_meta($post->ID, 'posts_zibpay', true);

    $paid = zibpay_is_paid($post->ID);
    /**如果未设置付费阅读功能，则直接显示 */
    if (empty($pay_mate['pay_type']) || $pay_mate['pay_type'] != '1') return  $content;
    /**
     * 判断逻辑
     * 1. 管理登录
     * 2. 已经付费
     * 3. 必须设置了付费阅读
     */
    if (is_super_admin()) {
        return '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容 - 管理员可见</div>' . do_shortcode($content) . '</div>';
    } elseif ($paid) {
        $paid_name = zibpay_get_paid_type_name($paid['paid_type']);
        return '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容 - ' . $paid_name . '</div>' . do_shortcode($content) . '</div>';
    } else {
        return  $_hide;
    }
}
add_shortcode('payshow', 'zibpay_to_show');


/**
 *页码加载
 */
function zibpay_admin_pagenavi($total_count, $number_per_page = 15)
{

    $current_page = isset($_GET['paged']) ? $_GET['paged'] : 1;

    if (isset($_GET['paged'])) {
        unset($_GET['paged']);
    }

    $base_url = add_query_arg($_GET, admin_url('admin.php'));

    $total_pages    = ceil($total_count / $number_per_page);

    $first_page_url    = $base_url . '&amp;paged=1';
    $last_page_url    = $base_url . '&amp;paged=' . $total_pages;

    if ($current_page > 1 && $current_page < $total_pages) {
        $prev_page        = $current_page - 1;
        $prev_page_url    = $base_url . '&amp;paged=' . $prev_page;

        $next_page        = $current_page + 1;
        $next_page_url    = $base_url . '&amp;paged=' . $next_page;
    } elseif ($current_page == 1) {
        $prev_page_url    = '#';
        $first_page_url    = '#';
        if ($total_pages > 1) {
            $next_page        = $current_page + 1;
            $next_page_url    = $base_url . '&amp;paged=' . $next_page;
        } else {
            $next_page_url    = '#';
        }
    } elseif ($current_page == $total_pages) {
        $prev_page        = $current_page - 1;
        $prev_page_url    = $base_url . '&amp;paged=' . $prev_page;
        $next_page_url    = '#';
        $last_page_url    = '#';
    }
?>
    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <span class="displaying-num">每页 <?php echo $number_per_page; ?> 共 <?php echo $total_count; ?></span>
            <span class="pagination-links">
                <a class="first-page button <?php if ($current_page == 1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url; ?>">«</a>
                <a class="prev-page button <?php if ($current_page == 1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url; ?>">‹</a>
                <span class="paging-input">第 <?php echo $current_page; ?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
                <a class="next-page button <?php if ($current_page == $total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url; ?>">›</a>
                <a class="last-page button <?php if ($current_page == $total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url; ?>">»</a>
            </span>
        </div>
        <br class="clear">
    </div>
<?php
}


/**购买发送邮件 */
if(_pz('email_payment_order',true)){
    add_action('payment_order_success', 'zibpay_mail_payment_order', 10, 2);
}
function zibpay_mail_payment_order($values)
{
    if (empty($values['order_num'])) return false;

    /**根据订单号查询订单 */
    global $wpdb;
    $pay_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->zibpay_order} WHERE order_num = %s and status=1", $values['order_num']));
    $pay_order = (array) $pay_order;
    $user_id = $pay_order['user_id'];

    $udata = get_userdata($user_id);
    /**获取用户邮箱 */
    $user_email = $udata->user_email;
    /**如果没有 email或者email无效则终止*/
    if (!$user_email || stristr($user_email, '@no')) return false;

    $user_name = $udata->display_name;

    $pay_price = $pay_order['pay_price'];
    $pay_time = $pay_order['pay_time'];
    $blog_name = get_bloginfo('name');
    $blog_url = home_url();

    $title = '[' . $blog_name . '] 商品支付成功';

    $message = '您好！ '.$user_name . "<br />";;
    $message .= '您在【' . $blog_name . '】购买的产品已支付成功' . "<br />";
    $message .= '订单号：' . $pay_order['order_num'] . "<br />";;
    $message .= '支付金额：￥' . $pay_price . "<br />";;
    $message .= '付款时间：' . $pay_time . "<br />";;
    $message .= "<br />";;
    $message .= '您可以打开下方链接查看订单详情' . "<br />";;
    $message .= get_author_posts_url($user_id) . '?page=pay<br />';
    $message = apply_filters('mail_content', $message);

    /**发送邮件 */
    @wp_mail($user_email, $title, $message);
}
