<?php
/*
订单中心
*/
if (!defined('ABSPATH')) {
     exit;
}
$user_Info   = wp_get_current_user();
if (!is_user_logged_in()) {
     exit;
}

$order_url = admin_url('admin.php?page=zibpay_product_page');
$desc_url = $order_url;

$WHERE = '';
$WHERE_order_type = !empty($_GET['order_type']) ? $_GET['order_type'] : false;
if ($WHERE_order_type) {
     $WHERE = "WHERE
     `order_type` = $WHERE_order_type";
     $desc_url = $order_url . '&amp;order_type=' . $WHERE_order_type;
}

global $wpdb;
//统计数据
$total_trade = COUNT($wpdb->get_col("SELECT distinct post_id FROM $wpdb->zibpay_order $WHERE"));
//分页计算
$ice_perpage = 20;
$pages = ceil($total_trade / $ice_perpage);
$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$offset = $ice_perpage * ($page - 1);

$db_post_id = $wpdb->get_col("SELECT distinct post_id FROM $wpdb->zibpay_order $WHERE limit $offset,$ice_perpage");

$list = array();
if($db_post_id){
    foreach ($db_post_id as $pid) {
        $list[] = $wpdb->get_row("SELECT SUM(status),order_price,SUM(pay_price),post_id,order_type FROM $wpdb->zibpay_order WHERE post_id = $pid");
    }
}

$all_c = COUNT($wpdb->get_col("SELECT distinct post_id FROM $wpdb->zibpay_order"));
$o1_c = COUNT($wpdb->get_col("SELECT distinct post_id FROM $wpdb->zibpay_order WHERE `order_type` =1"));
$o2_c = COUNT($wpdb->get_col("SELECT distinct post_id FROM $wpdb->zibpay_order WHERE `order_type` =2"));

?>
<div class="wrap">
     <h2>商品明细</h2>

     <div class="order-header">
          <ul class="subsubsub">
               <li class=""><a class="" href="<?php echo $order_url; ?>">全部订单</a>(<?php echo $all_c ?>)</li> |
               <li class=""><a class="" href="<?php echo $order_url . '&amp;order_type=1'; ?>">付费阅读</a>(<?php echo $o1_c ?>)</li> |
               <li class=""><a class="" href="<?php echo $order_url . '&amp;order_type=2'; ?>">付费资源</a>(<?php echo $o2_c ?>)</li>
          </ul>
     </div>

     <div class="table-box">
          <table class="widefat fixed striped posts">
               <thead>
                    <tr>
                         <?php

                         $theads = array();
                         $theads[] = array('width' => '30%', 'type' => 'post_id', 'name' => '文章');
                         $theads[] = array('width' => '30%', 'type' => 'user_id', 'name' => '付费类型');
                         $theads[] = array('width' => '20%', 'type' => 'user_id', 'name' => '单价');
                         $theads[] = array('width' => '20%', 'type' => 'user_id', 'name' => '销售量');
                         $theads[] = array('width' => '20%', 'type' => 'user_id', 'name' => '销售总金额');

                         foreach ($theads as $thead) {
                              $href = $desc_url . '&amp;order=' . $thead['type'];
                              $href_acs = $desc_url . '&amp;order=' . $thead['type'] . '&amp;desc=asc';
                              echo '<th width="' . $thead['width'] . '"><b>' . $thead['name'] . '</b></th>';
                         } ?>
                    </tr>
               </thead>
               <tbody>
                    <?php
                    if ($list) {
                         $ii = 1;
                         foreach ($list as $value) {

                              $order_type = zibpay_get_pay_type_name($value->order_type);

                              $post_a = '<a style=" overflow: hidden; text-overflow:ellipsis; white-space: nowrap; display: block; " target="_blank" href="' . get_edit_post_link($value->post_id) . '">' . get_the_title($value->post_id). '</a>';
                              echo "<tr>\n";
                              $value_a = (array)$value;
                              echo "<td>$post_a</td>\n";
                              echo "<td>$order_type</td>\n";
                              echo "<td>$value->order_price</td>\n";
                              echo "<td>".$value_a['SUM(status)']."</td>\n";
                              echo "<td>".($value_a['SUM(pay_price)'] ? $value_a['SUM(pay_price)'] :0)."</td>\n";

                              echo "<td></td>\n";

                              echo "</tr>";
                              $ii++;
                         }
                    } else {
                         echo '<tr><td colspan="12" align="center"><strong>暂无订单</strong></td></tr>';
                    }
                    ?>
               </tbody>
          </table>
     </>
     <?php echo zibpay_admin_pagenavi($total_trade, $ice_perpage); ?>
     　　
</div>