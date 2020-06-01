<?php

/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

function zib_ohtml_email()
{

$con = '<div class="options-notice">
<div class="explain">
<p><b>您可以在下方测试邮件发送功能是否正常，请输入您的邮箱帐号：</b></p>
<ajaxform class="ajax-form" ajax-url="'.admin_url("admin-ajax.php").'">
<p><input type="text" style=" max-width: 200px;" ajax-name="email" value=""></p>
<div class="ajax-notice"></div>
<p><a href="javascript:;" class="but jb-yellow ajax-submit">发送测试邮件</a></p>
<input type="hidden" ajax-name="action" value="test_send_mail">
</ajaxform>
</div></div>';
$con .='<script>
jQuery(document).ready(function($) {
    $("body").on("click", ".ajax-submit", function() {
        var _data = {},_this = $(this),_tt = _this.html();
        form = _this.parents(".ajax-form");
        _notice = form.find(".ajax-notice");
        ajax_url = form.attr("ajax-url");
        form.find("input").each(function() {
            n = $(this).attr("ajax-name"), v = $(this).val();
            if (n) {
                _data[n] = v;
            }
        });
        _this.attr("disabled", true).html("请稍候...");
        n_type = "warning";
        n_msg = "正在处理，请稍候...";
        n_con = "<div style=\'padding: 10px;\' class=\'notice notice-" + n_type +"\'><b>" + n_msg + "</b></div>";
        _notice.html(n_con);
        console.log(_data);
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: _data,
            dataType: "json",
            success: function(n) {
                console.log(n);
                _this.attr("disabled", false).html(_tt);
                n_type = n.error ? "error" : "info";
                n_con = "<div style=\'padding: 10px;\' class=\'notice notice-" + n_type +"\'><b>" + n.msg + "</b></div>";
                _notice.html(n_con);
            }
        });
    })

});
</script>';
return $con;
}

add_action('wp_ajax_test_send_mail', 'zib_test_send_mail');
function zib_test_send_mail()
{
    header('Content-type:application/json; Charset=utf-8');
    if(empty($_POST['email'])){
		echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请输入邮箱帐号')));
		exit();
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo (json_encode(array('error' => 1, 'msg' => '邮箱格式错误')));
        exit();
    }
    $blog_name = get_bloginfo('name');
    $blog_url = get_bloginfo('url');
    $title = '[' . $blog_name . '] 测试邮件';

    $message = '您好！ <br />';
    $message .= '这是一封来自'.$blog_name.'['.$blog_url.']的测试邮件<br />';
    $message .= '该邮件由网站后台发出，如果非您本人操作，请忽略此邮件 <br />';
    $message .= date("Y-m-d H:i:s");

    $test = wp_mail($_POST['email'], $title, $message);
    if($test){
        echo (json_encode(array('error' => 0, 'msg' => '后台已操作')));
    }else{
        echo (json_encode(array('error' => 1, 'msg' => '发送失败')));
    }
    exit();
}
