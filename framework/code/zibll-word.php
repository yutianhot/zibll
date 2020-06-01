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
<main role="main" class="zib-word">
    <div class="box-theme">
        <div class="updated">
            <h3>感谢您使用Zibll子比主题</h3>
            <p>
                Zibll 子比主题专为博客、自媒体、资讯类的网站设计开发，简约优雅的设计风格，全面的前端用户功能，简单的模块化配置！
            </p>
            <p><b>如果您是第一次使用此主题，那么请先看看基础的主题文档吧</b>
            </p>
        </div>
    </div>
    <div class="box-theme">
        <div class="title-theme">
            <b>主题更新</b>
        </div>
        <?php zib_update_input() ?>
    </div>

    <div class="box-theme">
        <div class="title-theme">
            <b>系统环境</b>
        </div>
        <b>环境要求：</b>
        <li><strong>WordPress</strong>：4.0+，推荐使用最新版</li>
        <li><strong>PHP</strong>：PHP5.6及以上，推荐使用7.0以上</li>
        <li><strong>服务器配置</strong>：无要求，最低配都行</li>
        <li><strong>操作系统</strong>：无要求，不推荐使用Windows系统</li>

        <b>当前环境：</b>
        <li><strong>操作系统</strong>： <?php echo PHP_OS ?></li>
        <li><strong>运行环境</strong>： <?php echo $_SERVER["SERVER_SOFTWARE"] ?></li>
        <li><strong>PHP版本</strong>： <?php echo PHP_VERSION ?></li>
        <li><strong>WordPress版本</strong>： <?php echo bloginfo('version') ?></li>
        <li><strong>系统信息</strong>： <?php echo php_uname() ?></li>
        <li><strong>服务器时间</strong>： <?php echo current_time('mysql') ?></li>
    </div>
    <div class="box-theme">
        <div class="title-theme">
            <b>基础教程</b>
        </div>
        <p>首次安装必做</p>
        <li>设置导航菜单，主题电脑端菜单和手机端菜单是分开的，新网站一定要先设置菜单，一级菜单建议不超过6个·<a href="<?php echo admin_url('nav-menus.php')?>">点击设置</a>
        </li>
        <li>设置小工具，模块化配置属于主题核心功能，建议使用「实时预览管理」设置小工具·<a href="<?php echo 	zib_get_customize_widgets_url()?>">点击设置</a></li>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('body').on("click", '#update-submit', function() {
            var _data = {};
            form = $(this).parents('#update-form');
            form.find('input').each(function() {
                n = $(this).attr('au_name'), v = $(this).val();
                if (n) {
                    _data[n] = v;
                }
            });
            _notice = form.find('.update-notice');
            n_msg = '正在处理，请稍候...';
            n_con = '<b>' + n_msg + '</b>';
            _notice.html(n_con);
            ajax_url = '<?php echo zib_get_http_curl_url() ?>';
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: _data,
                dataType: "json",
                success: function(n) {
                    if (!n.result) {
                        n_con = '<b>暂无更新</b>';
                        _notice.html(n_con);
                    } else {
                        location.reload();
                    }
                }
            });
        })

    });
    </script>
</main>