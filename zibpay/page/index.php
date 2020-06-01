<?php
/*
商城数据统计
*/

$user_Info   = wp_get_current_user();
if (!is_user_logged_in()) {
    exit;
}
date_default_timezone_set('Asia/Shanghai');

global $wpdb;
$y = date('Y');
$m = date('m');
$lm = date('m') - 1;
$todaytime = date('Y-m-d');
$Yestertime = date("Y-m-d", strtotime("-1 day"));

$firstday = date('Y-m');
$firsttomonth = date('Y-m', strtotime("$y-$lm-1"));

// 今日总订单
$today_order = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$todaytime%' ");
// 今日总金额
$today_price = $wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$todaytime%' ");
$today_price = $today_price ? $today_price : 0;

// 昨天总订单
$Yesterday_order = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$Yestertime%'  ");
// 昨天总金额
$Yesterday_price = $wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$Yestertime%' ");
$Yesterday_price = $Yesterday_price ? $Yesterday_price : 0;
// 本月总订单
$tomonth_order = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$firstday%' ");
// 本月总金额
$tomonth_price = $wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$firstday%' ");
$tomonth_price = $tomonth_price ? $tomonth_price : 0;

// 上月总订单
$ssmonth_order = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$firsttomonth%' ");
// 上月总金额
$ssmonth_price = $wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$firsttomonth%' ");
$ssmonth_price = $ssmonth_price ? $ssmonth_price : 0;

$obj = array();
$time_vat = '';
$count_val_1 = '';
$count_val_2 = '';
$price_val_1 = array();
$price_val_2 = array();
for ($x = 16; $x >= 0; $x--) {
    $time = date("Y-m-d", strtotime("-$x day"));

    if ($x == 16) {
        $fenge = '';
    } else {
        $fenge = ',';
    }
    $time_vat .= $fenge . '"' . date("m-d", strtotime("-$x day")) . '"';
    $count_val_0 .= $fenge . $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$time%'");
    $count_val_1 .= $fenge . $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$time%' and order_type=1");
    $count_val_2 .= $fenge . $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$time%' and order_type=2");
    $price_val_0[] = @round($wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$time%'"), 2);
    $price_val_2[] = @round($wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$time%' and order_type=1"), 2);
    $price_val_2[] = @round($wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE pay_time LIKE '%$time%' and order_type=2"), 2);
}

$time_vat = '[' . $time_vat . ']';
$count_val_0 = '[' . $count_val_0 . ']';
$count_val_1 = '[' . $count_val_1 . ']';
$count_val_2 = '[' . $count_val_2 . ']';

$price_val_0 = json_encode($price_val_0);
$price_val_1 = json_encode($price_val_1);
$price_val_2 = json_encode($price_val_2);

?>
<div class="wrap pay-container">
    <h2>全部订单</h2>
    <div class="row-3">
        <div class="box-panel">

            <span class="count_top">今日订单</span>
            <div class="count"><?php echo $today_order ?></div>
            <span class="count_bottom">同比昨日：<?php echo @round((($today_order - $Yesterday_order) / $Yesterday_order * 100), 1) . '%' ?></span>

        </div>
    </div>
    <div class="row-3">
        <div class="box-panel">

            <span class="count_top">今日收款</span>
            <div class="count"><?php echo $today_price ?></div>
            <span class="count_bottom">同比昨日：<?php echo @round((($today_price - $Yesterday_price) / $Yesterday_price * 100), 1) . '%' ?></span>


        </div>
    </div>
    <div class="row-3">
        <div class="box-panel">
            <span class="count_top">本月订单</span>
            <div class="count"><?php echo $tomonth_order ?></div>
            <span class="count_bottom">同比昨日：<?php echo @round((($tomonth_order - $ssmonth_order) / $ssmonth_order * 100), 1) . '%' ?></span>



        </div>
    </div>
    <div class="row-3">
        <div class="box-panel">

            <span class="count_top">本月收款</span>
            <div class="count"><?php echo $tomonth_price ?></div>
            <span class="count_bottom">同比昨日：<?php echo @round((($tomonth_price - $ssmonth_price) / $ssmonth_price * 100), 1) . '%' ?></span>


        </div>
    </div>
    <div class="row-6">
        <div class="box-panel">
            <div id="highcharts_count" style="height:400px;"></div>

        </div>
    </div>
    <div class="row-6">
        <div class="box-panel">
            <div id="highcharts_price" style="height:400px;"></div>

        </div>
        <div id="main"></div>

    </div>
    <script type="text/javascript">
        option = {
            title: {
                text: '订单数量'
            },
            legend: {
                data: ['付费阅读', '付费下载']
            },
            tooltip: {
                trigger: 'axis'
            },
            xAxis: {
                type: 'category',
                data: <?php echo $time_vat ?>
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                name: '全部订单',
                data: <?php echo $count_val_0 ?>,
                type: 'line',
                markPoint: {
                    data: [{
                            type: 'max',
                            name: '最大值'
                        },
                        {
                            type: 'min',
                            name: '最小值'
                        }
                    ]
                },
                smooth: true
            },{
                name: '付费阅读',
                data: <?php echo $count_val_1 ?>,
                type: 'line',
                markPoint: {
                    data: [{
                            type: 'max',
                            name: '最大值'
                        },
                        {
                            type: 'min',
                            name: '最小值'
                        }
                    ]
                },
                smooth: true
            }, {
                name: '付费下载',
                data: <?php echo $count_val_2 ?>,
                type: 'line',
                markPoint: {
                    data: [{
                            type: 'max',
                            name: '最大值'
                        },
                        {
                            type: 'min',
                            name: '最小值'
                        }
                    ]
                },
                smooth: true
            }]
        };

        var myChart = echarts.init(document.getElementById('highcharts_count'), 'westeros');
        myChart.setOption(option);

        option = {
            title: {
                text: '收款金额'
            },
            legend: {
                data: ['付费阅读', '付费下载']
            },
            tooltip: {
                trigger: 'axis'
            },
            xAxis: {
                type: 'category',
                data: <?php echo $time_vat ?>
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                name: '全部订单',
                data: <?php echo $price_val_0 ?>,
                type: 'line',
                markPoint: {
                    data: [{
                            type: 'max',
                            name: '最大值'
                        },
                        {
                            type: 'min',
                            name: '最小值'
                        }
                    ]
                },
                smooth: true
            },{
                name: '付费阅读',
                data: <?php echo $price_val_1 ?>,
                type: 'line',
                markPoint: {
                    data: [{
                            type: 'max',
                            name: '最大值'
                        },
                        {
                            type: 'min',
                            name: '最小值'
                        }
                    ]
                },
                smooth: true
            }, {
                name: '付费下载',
                data: <?php echo $price_val_2 ?>,
                type: 'line',
                markPoint: {
                    data: [{
                            type: 'max',
                            name: '最大值'
                        },
                        {
                            type: 'min',
                            name: '最小值'
                        }
                    ]
                },
                smooth: true
            }]
        };

        var myChart = echarts.init(document.getElementById('highcharts_price'), 'westeros');
        myChart.setOption(option);
    </script>
</div>

<?php
