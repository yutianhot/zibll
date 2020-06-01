<?php
/**登录 */
add_action('wp_footer','zib_sign_modal');
function zib_sign_modal()
{
    if (is_user_logged_in()) {
        return false;
    }
    $yz =  _pz('user_verification', true);

	$t = _pz('hometitle') ? _pz('hometitle') : get_bloginfo('name') . (get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '');

?>
    <div class="modal fade" id="u_sign" tabindex="-1" role="dialog">
        <div class="modal-dialog" style="max-width:320px;margin: auto;" role="document">
            <div class="modal-content">
                <div class="sign">
                    <div class="sign-content modal-body">
                        <button class="close" data-dismiss="modal">
                            <i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i>
                        </button>
                        <div class="text-center">
                            <div class="sign-img box-body"><?php echo zib_get_adaptive_theme_img(_pz('user_img'),_pz('user_img_dark'),$t,'class="lazyload"',true);?></div>
                            <ul class="list-inline splitters">
                                <li class="active"><a href="#tab-sign-in" data-toggle="tab">登录</a></li><li><a href="#tab-sign-up" data-toggle="tab">注册</a></li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="tab-sign-in">
                                <form id="sign-in" class="wyz">
                                    <ul>
                                        <li class="relative line-form">
                                            <input type="text" name="username" class="line-form-input" tabindex="1" placeholder="用户名或邮箱">
                                            <i class="line-form-line"></i>
                                        </li>
                                        <li class="relative line-form">
                                            <input type="password" name="password" class="line-form-input" tabindex="2" placeholder="登录密码">
                                            <div class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></div>
                                            <i class="line-form-line"></i>
                                        </li>
                                        <?php if ($yz ) {
                                        ?>
                                            <li class="relative line-form">
                                                <input type="text" name="canvas_yz" class="line-form-input" autocomplete="off" tabindex="3" placeholder="验证码">
                                                <span class="yztx abs-right"><canvas id="user_yz_canvas1" width="92" height="38"></canvas></span>
                                                <div class="abs-right match-ok muted-color"><i class="fa-fw fa fa-check-circle"></i></div>
                                                <i class="line-form-line"></i>
                                            </li>
                                        <?php } ?>
                                        <li class="relative line-form">
                                            <label class="muted-color em09">
                                                <input type="checkbox" checked="checked" tabindex="4" name="remember" value="forever">
                                                记住登录</label>
                                            <?php if (zib_get_permalink(_pz('user_rp'))) {
                                                echo '<a class="abs-right muted-2-color" href="' . zib_get_permalink(_pz('user_rp')) . '">找回密码</a>';
                                            } ?>
                                        </li>
                                    </ul>
                                    <div class="box-body">
                                        <input type="hidden" name="action" value="signin">
                                        <button type="button" class="but radius jb-blue padding-lg signsubmit-loader btn-block"><i class="fa fa-sign-in mr10"></i>登录</button>
                                    </div>
                                    <?php zib_social_login();?>

                                </form>
                            </div>
                            <div class="tab-pane fade" id="tab-sign-up">
                                <form id="sign-up">
                                    <ul>
                                        <li class="relative line-form">
                                            <input type="text" name="name" class="line-form-input" tabindex="1" placeholder="设置用户名">
                                            <i class="line-form-line"></i>
                                        </li>
                                        <li class="relative line-form">
                                            <input type="text" name="email" class="line-form-input" tabindex="2" placeholder="邮箱">
                                            <i class="line-form-line"></i>
                                        </li>
                                        <?php

                                        $captch = _pz('user_signup_captch');
                                        $captch_type = _pz('captch_type', 'email');

                                        if ($captch ) {
                                        ?>
                                            <li class="relative line-form signup-captch">
                                                <input type="text" name="captch" class="line-form-input" autocomplete="off" tabindex="3" placeholder="验证码">
                                                <span class="yztx abs-right"><button type="button" class="but jb-blue captchsubmit">发送验证码</button></span>
                                                <div class="abs-right match-ok muted-color"><i class="fa-fw fa fa-check-circle"></i></div>
                                                <i class="line-form-line"></i>
                                                <input type="hidden" name="captch_type" value="<?php echo $captch_type ?>">
                                            </li>
                                        <?php } ?>
                                        <li class="relative line-form">
                                            <input type="password" name="password2" class="line-form-input" tabindex="3" placeholder="设置密码">
                                            <div class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></div>
                                            <i class="line-form-line"></i>
                                        </li>
                                        <li class="relative line-form">
                                            <input type="password" name="repassword" class="line-form-input" tabindex="4" placeholder="重复密码">
                                            <div class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></div>
                                            <i class="line-form-line"></i>
                                        </li>
                                        <?php if ($yz ) {
                                        ?>
                                            <li class="relative line-form">
                                                <input type="text" name="canvas_yz" class="line-form-input" autocomplete="off" tabindex="5" placeholder="验证码">
                                                <span class="yztx abs-right"><canvas id="user_yz_canvas2" width="92" height="38"></canvas></span>
                                                <div class="abs-right match-ok muted-color"><i class="fa-fw fa fa-check-circle"></i></div>
                                                <i class="line-form-line"></i>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="box-body">
                                        <input type="hidden" name="action" value="signup">
                                        <button type="button" class="but radius jb-green padding-lg signsubmit-loader btn-block"><?php echo zib_svg('signup','0 0 1024 1024','icon mr10');?>注册</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="sign-tips"></div>
                </div>
            </div>
        </div>
    </div>

<?php
}

/**退出登录 */
add_action('wp_footer','zib_signout_modal');
function zib_signout_modal()
{
    if (is_user_logged_in()) {
        global $current_user; ?>
        <div class="modal fade" id="modal_signout" tabindex="-1" role="dialog">
            <div class="modal-dialog" style="max-width:350px;margin: auto;" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h4>您好！ <?php echo $current_user->display_name ?></h4>
                        <p style="color: #ff473a">确认要退出当前登录吗？</p>
                    </div>
                    <div class="box-body text-right">
                        <button type="button" class="but mr10" data-dismiss="modal">取消</button>
                        <a type="button" class="but c-red" href="<?php echo wp_logout_url(home_url()) ?>">确认退出</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } 
}




function zib_posts_user_box($loged_title='Hi！请登录')
{
    $cuid = get_current_user_id();
    $cover = '<img class="lazyload fit-cover" data-src="' . _pz('user_cover_img', get_stylesheet_directory_uri() . '/img/user_t.jpg') . '">';
    $avatar = '<img class="fit-cover avatar lazyload" data-src="' . zib_default_avatar() . '">';
    if (is_user_logged_in()) {
        $args = array(
            'user_id' => $cuid,
            'show_posts' => false,
            'show_img_bg' => true,
        );
        return zib_posts_avatar_box($args);
    } else {
        echo '<div class="article-author main-bg theme-box box-body radius8 main-shadow relative">';
        echo '<div class="avatar-img-bg">';
        echo $cover;
        echo '</div>';
        echo '<ul class="list-inline avatar-info radius8">
            <li><div class="avatar-img">' . $avatar . '</div></li>';

        echo '<div class="text-center">
            <p class="separator muted-3-color box-body notop">'.$loged_title.'</p>
        <p>
            <a href="javascript:;" class="signin-loader but jb-blue padding-lg"><i class="fa fa-fw fa-sign-in mr10"></i>登录</a>
            <a href="javascript:;" class="signup-loader ml10 but jb-yellow padding-lg">' . zib_svg('signup','0 0 1024 1024','icon mr10') . '</i>注册</a>
        </p>';
        zib_social_login();
        echo '</ul>';
        echo '</div>';
    }
}
