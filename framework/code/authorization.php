<?php

/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

require dirname(__FILE__) . '/../../../../../wp-load.php';
/**判断权限 */
if (!is_super_admin() || !current_user_can( 'edit_theme_options' )) {
    wp_safe_redirect(home_url());
    exit;
};
?>

<style>
#authorization_form {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-align: center;
    background: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.07);
    position: relative;
    padding-top: 70px;
    margin-top: 50px;
}

#authorization_submit {
    background-image: linear-gradient(135deg, #55a6ff 10%, #1276f1 100%);
    box-shadow: -1px 2px 10px 0 rgba(12, 113, 243, 0.5);
    text-shadow: none;
    font-size: 15px;
    padding: 6px 0;
    margin: 15px 0;
    cursor: pointer;
    border: none;
    border-radius: 30px;
    color: #fff;
    transition: all .2s;
    display: inline-block;
    max-width: 220px;
    width: 100%;
}

#authorization_form .ok-icon {
    font-size: 50px;
    line-height: 90px;
    position: absolute;
    top: -50px;
    width: 100px;
    margin: auto;
    left: 0;
    right: 0;
    height: 100px;
    background: #fff;
    border-radius: 100px;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.07);
}

#authorization_submit.c-red {
    background-image: linear-gradient(135deg, #ff8c55 10%, #f14d12 100%);
    box-shadow: -1px 2px 10px 0 rgba(243, 61, 12, 0.5);
}

#authorization_form input {
    border-color: #ddd;
    margin: 10px 0;
}

.zib-word .dashicons-heart {
    color: #ff2f86
}

.authorization_notice .notice {
    padding: 10px;
}

.authorization_notice .notice-error {
    color: #e61818;
}

.authorization_notice .notice-warning {
    color: #ff7742;
}

.authorization_notice .notice-info {
    color: #0581ea;
}
</style>

<main role="main" class="zib-word">

    <div class="notice notice-info">
        <h3><span class="dashicons-before dashicons-heart"></span> 感谢您使用Zibll子比主题</h3>
        <div><b>首次使用请在下方输入授权码进行授权验证</b></div>
        <p>子比主题是一款良心、厚道的好产品！创作不易，支持正版，从我做起！</p>
        <li>子比主题官网：<a target="_bank" href="https://zibll.com/">https://zibll.com</a></li>
        <li>作者联系方式：QQ 770349780 </li>
        <li>如在本地调试则无需授权</li>
    </div>

    <div class="box-theme">
        <p class="authorization_notice"></p>
        <?php zib_authorization_input() ?>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('body').on("click", '#authorization_submit', function() {

            var _data = {};
            form = $(this).parents('#authorization_form');
            form.find('input').each(function() {
                n = $(this).attr('au_name'), v = $(this).val();
                if (n) {
                    _data[n] = v;
                }
            });
            if (_data.action == 'zib_delete_authorization') {
                var r = confirm("确认要撤销授权？撤销后需重新输入授权码后才能正常使用主题！");
                if (r != true) {
                    return false;
                }
            }
            _notice = $('.authorization_notice');
            n_type = 'warning';
            n_msg = '正在处理，请稍候...';
            n_con = '<div class="notice notice-' + n_type + '"><b>' + n_msg + '</b></div>';
            _notice.html(n_con);
            ajax_url = '<?php echo zib_get_http_curl_url() ?>';
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: _data,
                dataType: "json",
                success: function(n) {
                    //console.log(n)
                    if (n.msg) {
                        n_msg = n.msg;
                    }
                    if (n.result) {
                        n_type = 'info';
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        n_type = 'error';
                    }
                    n_con = '<div class="notice notice-' + n_type + '"><b>' + n_msg + '</b></div>';
                    _notice.html(n_con);
                }
            });
        })

    });
    </script>
</main>