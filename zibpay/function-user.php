<?php

/**
 * 子比主题
 * 支付系统
 */

/**挂钩到用户中心 */

if (_pz('pay_show_user')) {
    add_action('author_info_tab', 'zibpay_user_info_tab');
    add_action('author_info_tab_con', 'zibpay_user_info_tab_con');
}
function zibpay_user_info_tab($user_id)
{
    echo '<li class="active"><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-pay"><i class="fa fa-shopping-cart hide-sm fa-fw" aria-hidden="true"></i>支付订单</a></li>';
}

function zibpay_user_info_tab_con($user_id)
{


?>
    <div class="tab-pane fade in active" id="author-tab-pay">
    <div class="theme-box user-pay">
            <div class="box-body">
                <div class="title-h-left"><b>统计</b></div>
            </div>
            <div class="box-body notop nobottom user-pay-order">
                <?php echo zibpay_get_user_pay_statistical($user_id); ?>
            </div>
        </div>
        <div class="theme-box user-pay">
            <div class="box-body notop">
                <div class="title-h-left"><b>订单</b></div>
            </div>
            <div class="box-body notop nobottom user-pay-statistical">
                <?php echo zibpay_get_user_order($user_id); ?>
            </div>
        </div>
    </div>
<?php }


/**挂钩_GET参数打开tab */
function zib_pay_user_url_show_tab()
{
if(!empty( $_GET['page'])&& $_GET['page'] == 'pay'){
    $_GET['show_tab'] = 'author-tab-user-data-set';
}
}
add_action('wp_footer', 'zib_pay_user_url_show_tab');


/**
 * 用户订单金额统计
 */
function zibpay_get_user_pay_price($user_id, $type = '', $order_type = '')
{
    global $wpdb;
    $sum = 0;
    $order_type = $order_type ? 'AND `order_type` = ' . $order_type : '';
    if ($type == 'order_price') {
        $sum = $wpdb->get_var("SELECT SUM(order_price) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id $order_type");
    } elseif ($type == 'pay_price') {
        $sum = $wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id $order_type");
    }
    return $sum ? $sum : 0;
}

/**
 * 用户订单数量统计
 */
function zibpay_get_user_order_count($user_id, $type = '')
{
    global $wpdb;
    if ($type) {
        $count = $wpdb->get_var("SELECT COUNT(user_id) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id AND `order_type` = $type ");
    } else {
        $count = $wpdb->get_var("SELECT COUNT(user_id) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id ");
    }
    return $count ? $count : 0;
}
/**
 * 用户中心统计信息
 */
function zibpay_get_user_pay_statistical($user_id)
{

    $count_all = zibpay_get_user_order_count($user_id);
    $count_t1 = zibpay_get_user_order_count($user_id, 1);
    $count_t2 = zibpay_get_user_order_count($user_id, 2);

    $sumprice_all = zibpay_get_user_pay_price($user_id, 'pay_price');
    $sumprice_t1 = zibpay_get_user_pay_price($user_id, 'pay_price', 1);
    $sumprice_t2 = zibpay_get_user_pay_price($user_id, 'pay_price', 2);

    $obj = array();
    $obj[] = array(
        '全部订单' => $count_all,
        '付费阅读' => $count_t1,
        '付费资源' => $count_t2,
        'unit' => '',
    );
    $obj[] = array(
        '支付金额' => $sumprice_all,
        '付费阅读' => $sumprice_t1,
        '付费资源' => $sumprice_t2,
        'unit' => '￥',
    );
    $con = '<div class="row">';
    foreach ($obj as  $val) {

        $con .= '<div class="col-sm-6">
            <div class="zib-widget pay-box">
                <div class="statistical-header">
                '.array_keys($val)[0].'
                </div>
                <div class="statistical-con">
                <span class="pay-mark">'.$val['unit'].'</span>'.array_values($val)[0].'
                </div>
                <div class="statistical-bottom muted-2-color">
                <span class="pay-mark">'.array_keys($val)[1].'：'.$val['unit'].'</span>'.array_values($val)[1].'
                <span class="pay-mark ml10">'.array_keys($val)[2].'：'.$val['unit'].'</span>'.array_values($val)[2].'
                </div>
            </div>
        </div>';
    };
    $con .= '</div>';

    return  $con;
}
/**
 * 用户订单明细
 */
function zibpay_get_user_order($user_id, $page = 1, $ice_perpage = 10)
{

    $offset = $ice_perpage * ($page - 1);
    global $wpdb;
    $db_order = $wpdb->get_results("SELECT * FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id  order by pay_time DESC limit $offset,$ice_perpage");
    $con = '';
    if ($db_order) {
        $count_all = zibpay_get_user_order_count($user_id);
        $mark = _pz('pay_mark', '￥');

        $con .= '<div class="order-ajaxpager">';

        foreach ($db_order as $order) {

            $order_num = $order->order_num;
            $order_price = $order->order_price;

            $pay_price = $order->pay_price;
            $pay_time = $order->pay_time;
            $post_id = $order->post_id;
            $order_type_name = zibpay_get_pay_type_name($order->order_type);
            $pay_mate = get_post_meta($post_id, 'posts_zibpay', true);
            $order_price = !empty($pay_mate['pay_original_price']) ?$pay_mate['pay_original_price'] : $order_price;


            $class = 'order-type-' . $order->order_type;

            $posts_title = get_the_title($post_id);
            $pay_title = !empty($pay_mate['pay_title']) ? $pay_mate['pay_title'] : $posts_title;
            $pay_title = '<a target="_blank" href="' . get_permalink($post_id) . '">' . $pay_title . '</a>';
            $pay_doc = '付款时间：' . $pay_time;
            $pay_doc .= '<sapn class="pull-right em12"><span class="pay-mark">价格：' . $mark . '</span>' . $order_price . '<span class="pay-mark ml10">实付金额：' . $mark . '</span>' . $pay_price . '</sapn>';

            $pay_num = '订单号：' . $order_num;

            $con .= '<div class="zib-widget pay-box order-ajax-item ' . $class . '">
            <div class="pay-tag abs-center">
            ' . $order_type_name . '
            </div>
                        <div>
                            <dl>
                            <div>' . $pay_title . '</div>
                                <div class="meta-time em09 muted-2-color">' . $pay_num . '</div>
                                <dd class="meta-time em09 muted-2-color">' . $pay_doc . '</dd>
                            </dl>
                        </div>
        </div>';
        }
        $con .= zibpay_get_user_order_paging($count_all, $page, $ice_perpage);
        $con .= '</div>';

    } else {
        $con = '<div class="text-center radius8 theme-box muted-2-color" style="background: var(--muted-border-color); padding: 30px 10px;">暂无订单</div>';
    }

    return $con;
}

/**
 * 订单分页函数
 */
function zibpay_get_user_order_paging($count_all , $page = 1, $ice_perpage = 10)
{
    $total_pages    = ceil($count_all / $ice_perpage);
    $con = '';
    if($total_pages > $page){
        $nex = _pz("ajax_trigger", '加载更多');
        $ajax_url = get_template_directory_uri() . '/action/author-content.php';
        $href = $ajax_url.'?type=author-pay-order&amp;paged='.($page+1);
        $con = '<div class="text-center theme-pagination order-ajax-pag"><div class="next-page order-ajax-next">
        <a href="'.$href.'">'.$nex.'</a>
        </div></div>';
    }else{
        $con = '';
    }
    return $con;
}