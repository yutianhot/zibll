<?php

/**
 * 订单系统
 */


class Zibpay_Order
{

    public function add_order_showdb()
    {
        global $wpdb;
        $wpdb->zibpay_order  = $wpdb->prefix . 'zibpay_order';
        /**订单 */

        $charset_collate = $wpdb->get_charset_collate();

        /**判断没有则创建 */
        if ($wpdb->get_var("show tables like '{$wpdb->zibpay_order}'") != $wpdb->zibpay_order) {

            $wpdb->query("CREATE TABLE $wpdb->zibpay_order (

                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) DEFAULT NULL COMMENT '用户id',
                    `ip_address` varchar(50) DEFAULT NULL COMMENT 'ip地址',
                    `product_id` int(11) DEFAULT NULL COMMENT '产品id',
                    `post_id` int(11) DEFAULT NULL COMMENT '文章id',
                    `order_num` varchar(50) DEFAULT NULL COMMENT '订单号',
                    `order_price` double(10,2) DEFAULT 0 COMMENT '订单价格',
                    `order_type` varchar(50) DEFAULT '0' COMMENT '订单类型',
                    `create_time` datetime DEFAULT NULL COMMENT '创建时间',
                    `pay_num` varchar(50) DEFAULT NULL COMMENT '支付订单号',
                    `pay_type` varchar(50) DEFAULT '0' COMMENT '支付类型',
                    `pay_price` double(10,2) DEFAULT NULL COMMENT '支付价格',
                    `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
                    `status` varchar(50) DEFAULT '0' COMMENT '订单状态',
                    `other` varchar(255) DEFAULT NULL COMMENT '其它',
                    PRIMARY KEY (`id`)

                  ) ENGINE=MyISAM DEFAULT CHARSET=" . DB_CHARSET . " COMMENT='授权明细';");
        }
    }
    public function delete_order($order_num='',$id = '')
    {
        if(!$order_num && !$id) return false;
        global $wpdb;
        if($order_num){
            $delete_db = $wpdb->query("DELETE FROM $wpdb->zibpay_order WHERE `order_num` = '$order_num'");
        }elseif($id){
            $delete_db = $wpdb->query("DELETE FROM $wpdb->zibpay_order WHERE `id` = $id");
        }
        return $delete_db ? true : false;
    }
    public function clear_order($days_ago = 15)
    {
        global $wpdb;
        date_default_timezone_set('Asia/Shanghai');
        $ago_time = date("Y-m-d H:i:s", strtotime("-$days_ago day"));
        $delete_count = $wpdb->query("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE  `status` =0 and `create_time` < '$ago_time'");
        $delete_db = $wpdb->query("DELETE FROM $wpdb->zibpay_order WHERE `status` =0 and `create_time` < '$ago_time'");

        return $delete_db ? $delete_count : false;
    }
    public function add_order($values)
    {

        global $wpdb;
        $defaults = array(
            'user_id' => '',
            'product_id' => '',
            'post_id' => '',
            'order_num' => '',
            'order_price' => '',
            'order_type' => '',
            'create_time' => '',
            'other' => '',
        );
        $values = wp_parse_args((array) $values, $defaults);
        date_default_timezone_set('Asia/Shanghai');

        $values['create_time'] = date("Y-m-d H:i:s");
        /** 创建时间 **/

        if (is_user_logged_in()) {
            $values['user_id'] = get_current_user_id();
            /** 操作用户ID **/
        }

        // 记录IP地址
        $values['ip_address'] = zibpay_get_user_ip();

        $values['order_num'] = date("mdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999); // 订单号
        /**创建订单号 */

        $sql = "insert into $wpdb->zibpay_order(
            user_id,
            product_id,
            post_id,
            order_num,
            ip_address,
            order_price,
            order_type,
            create_time,
            other
        )values(
            '" . $values['user_id'] . "',
            '" . $values['product_id'] . "',
            '" . $values['post_id'] . "',
            '" . $values['order_num'] . "',
            '" . $values['ip_address'] . "',
            '" . $values['order_price'] . "',
            '" . $values['order_type'] . "',
            '" . $values['create_time'] . "',
            '" . $values['other'] . "'
        )";

        if ($wpdb->query($sql)) {
            return $values;
        }

        return false;
    }

    public function payment_order($values)
    {
        global $wpdb;
        $defaults = array(
            'order_num' => '',
            'pay_type' => '',
            'pay_price' => '',
            'pay_num' => '',
            'other' => '',
        );
        $values = wp_parse_args((array) $values, $defaults);
        if(empty($values['order_num'])) return false;

        date_default_timezone_set('Asia/Shanghai');

        $values['pay_time'] = date("Y-m-d H:i:s");
        /** 支付时间 **/
        $values['status'] = 1;
        /** 已经支付 **/

        $sql = "update $wpdb->zibpay_order
        set
        pay_type = '" . $values['pay_type'] . "',
        pay_num = '" . $values['pay_num'] . "',
        pay_price = '" . $values['pay_price'] . "',
        pay_time = '" . $values['pay_time'] . "',
        status = '" . $values['status'] . "',
        other = '" . $values['other'] . "'
        where
        order_num=" . $values['order_num'];

        if ($wpdb->query($sql)) {
            do_action('payment_order_success',$values);
            return $values;
        }

        return false;
    }
}
