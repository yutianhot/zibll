<?php

// foot code
add_action('wp_footer', 'zib_footer');
function zib_footer()
{
    $code = '';
    if (_pz('footcode')) {
        $code .= "<!--FOOTER_CODE_START-->\n" . _pz('footcode') . "\n<!--FOOTER_CODE_END-->\n";
    }
    if (_pz('trackcode')) {
        $code .= "<!--FOOTER_CODE_START-->\n" . _pz('trackcode') . "\n<!--FOOTER_CODE_END-->\n";
    }

    echo $code;
}

if (_pz('zib_baidu_push_js')) {
    add_action('wp_footer', 'zib_baidu_push_js');
}

add_action('wp_footer', 'zib_win_var');
function zib_win_var()
{
    $highlight_theme = zib_get_theme_mode() == 'dark-theme' ? 'dracula' : _pz("highlight_zt");
?>
    <!--window_variable_start-->
    <script type="text/javascript">
        window._win = {
            www: '<?php echo home_url() ?>',
            uri: '<?php echo get_stylesheet_directory_uri() ?>',
            ver: '<?php echo THEME_VERSION ?>',
            ajaxpager: '<?php echo _pz("ajaxpager") ?>',
            ajax_trigger: '<?php echo _pz("ajax_trigger") ?>',
            ajax_nomore: '<?php echo _pz("ajax_nomore") ?>',
            qj_loading: '<?php echo _pz("qj_loading") ?>',
            url_rp: '<?php echo zib_get_permalink(_pz('user_rp')) ?>',
            highlight_kg: '<?php echo _pz("highlight_kg") ?>',
            highlight_hh: '<?php echo _pz("highlight_hh") ?>',
            highlight_btn: '<?php echo _pz("highlight_btn") ?>',
            highlight_zt: '<?php echo  $highlight_theme; ?>',
            up_max_size: '<?php echo _pz("up_max_size") ?>'
        }
    </script>
    <!--window_variable_end-->
<?php
}

add_action('wp_footer', 'zib_win_console');
function zib_win_console()
{
?>
<script type="text/javascript">
    console.log("get_num_queries：<?php echo get_num_queries();?>，timer_stop <?php echo timer_stop(0,5);?>");
</script>
<?php
}

function zib_baidu_push_js()
{
?>
    <!--baidu_push_js-->
    <script type="text/javascript">
        (function(){
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            }
            else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
    </script>
    <!--baidu_push_js-->
<?php
}

function zib_float_right()
{
    if (!_pz('float_right_ontop') || (!_pz('float_right_mobile_show') && wp_is_mobile())) {
        return false;
    }

    $kefuhtml = '<a class="main-shadow muted-2-color ontop radius8 fade" title="返回顶部" href="javascript:(scrollTo());"><i class="fa fa-arrow-up"></i></a>';
    echo '<div class="float-right text-center">' . $kefuhtml . '</div>';
}

//-----底部页脚内容------
if(_pz('fcode_template')=='template_1'){
    add_action('zib_footer_conter', 'zib_footer_con');
}
function zib_footer_con()
{ ?>
    <ul class="list-inline">
        <li class="hidden-xs" style="max-width: 300px;">
            <?php zib_footer_con_1(); ?>
        </li>
        <li  style="max-width: 550px;">
            <?php if (_pz('fcode')) {
                echo '<p class="">' . _pz('fcode') . '</p>';
            } ?>
            <?php zib_footer_con_2(); ?>
        </li>
        <li class="">
            <?php zib_footer_con_3(); ?>
        </li>
    </ul>
    <div class="">
        <?php if (!_pz('fcode')) {
            echo '<p class="footer-conter">' . _pz('fcode') . '</p>';
        } ?>

    </div>
<?php
}

function zib_footer_con_1()
{
    $html = '';
    $s_src = get_stylesheet_directory_uri() . '/img/thumbnail-sm.svg';
    if (_pz('footer_t1_img')) {
        $html .= '<p><a class="footer-logo" href="' . esc_url(home_url()) . '" title="' . _pz('hometitle') . '">
                    '.zib_get_adaptive_theme_img(_pz('footer_t1_img'),_pz('footer_t1_img_dark'),_pz('hometitle'),'class="lazyload" height="40"',true).'
                </a></p>';
    }

    if (_pz('footer_t1_t')) {
        $html .= '<p class="title-h-left">' . _pz('footer_t1_t') . '</p>';
    }

    if (_pz('fcode_t1_code')) {
        $html .= '<div class="footer-muted em09">' . _pz('fcode_t1_code') . '</div>';
    }
    echo $html;
}

function zib_footer_con_2()
{
    $html = '';

    if (_pz('fcode_t2_code_1')) {
        $html .= '<p class="fcode-links">' . _pz('fcode_t2_code_1') . '</p>';
    }

    if (_pz('fcode_t2_code_2')) {
        $html .= '<p class="footer-muted em09">' . _pz('fcode_t2_code_2') . '</p>';
    }
    $s_src = get_stylesheet_directory_uri() . '/img/thumbnail-sm.svg';;
    $m_show = _pz('footer_mini_img_m_s', true) ? '' : ' hidden-xs';
    if (!wp_is_mobile() || _pz('footer_mini_img_m_s', true)) {
        $html .= '<div class="footer-contact' . $m_show . '">';
        if (_pz('footer_contact_wechat_img')) {

            $s_img = '';
            $s_img .= '<div class="hover-show-con footer-wechat-img">';
            $s_img .= '<img class="lazyload" height="100" src="' . $s_src . '" data-src="' . _pz('footer_contact_wechat_img') . '">';
            $s_img .= '</div>';

            $html .= '<a class="hover-show" href="javascript:;">' . zib_svg('d-wechat') .$s_img. '</a>';

        }
        if (_pz('footer_contact_qq')) {
            $html .= '<a data-toggle="tooltip" title="QQ联系" href="http://wpa.qq.com/msgrd?v=3&uin=' . _pz('footer_contact_qq') . '&site=qq&menu=yes">' . zib_svg('d-qq','-50 0 1100 1100') . '</a>';
        }
        if (_pz('footer_contact_weibo')) {
            $html .= '<a data-toggle="tooltip" title="微博" href="' . _pz('footer_contact_weibo') . '">' . zib_svg('d-weibo') . '</a>';
        }
        if (_pz('footer_contact_email')) {
            $html .= '<a data-toggle="tooltip" title="发邮件" href="mailto:' . _pz('footer_contact_email') . '">' . zib_svg('d-email','-20 80 1024 1024') . '</a>';
        }
        $html .= '</div>';
    }
    echo $html;
}

function zib_footer_con_3()
{
    $html = '';
    $is = 3;
    $s_src = get_stylesheet_directory_uri() . '/img/thumbnail-sm.svg';;

    if (wp_is_mobile() &&  !_pz('footer_mini_img_m_s', true)) return;
    for ($i = 1; $i <= $is; $i++) {
        if (_pz('footer_mini_img_' . $i)) {
            $html .= '<div class="footer-miniimg" data-toggle="tooltip" title="' . _pz('footer_mini_img_t_' . $i) . '">
                <p>
                <img class="lazyload" src="' . $s_src . '" data-src="' . _pz('footer_mini_img_' . $i) . '">
                </p>
                <span class="footer-muted em09">' . _pz('footer_mini_img_t_' . $i) . '</span>
            </div>';
        }
    }
    echo $html;
}


/**挂钩_GET参数打开tab */
function zib_url_show_tab()
{

if(!empty( $_GET['show_tab'])){
    echo '<script type="text/javascript">';
    echo 'window._win.show_tab = "'.$_GET['show_tab'].'";';
    if(!empty( $_GET['show_tab2'])){
        echo 'window._win.show_tab2 = "'.$_GET['show_tab2'].'";';
    }
    if(!empty( $_GET['show_tab3'])){
        echo 'window._win.show_tab3 = "'.$_GET['show_tab3'].'";';
    }
    echo '</script>';
}

}
add_action('wp_footer', 'zib_url_show_tab',99);