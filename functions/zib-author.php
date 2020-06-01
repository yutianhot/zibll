<?php
function zib_author_header()
{
    global $wp_query;
    $curauth = $wp_query->get_queried_object();

    $like_n = get_user_posts_meta_count($curauth->ID, 'like');
    $view_n = get_user_posts_meta_count($curauth->ID, 'views');
    $followed_n = get_user_meta($curauth->ID, 'followed-user-count', true);
    $com_n = get_user_comment_count($curauth->ID);
    $post_n = (int) get_the_author_posts($curauth->ID);
    $b1 = zib_get_rewards_button($curauth->ID, 'em09 ml10');
    $b2 = zib_get_user_follow('em09 ml10', $curauth->ID);
    $img = get_user_cover_img($curauth->ID);
    $src = get_stylesheet_directory_uri() . '/img/thumbnail-lg.svg';
    $name = $curauth->display_name . $b1 . $b2;

    $desc = '<li><div class="avatar-img">' . zib_get_data_avatar($curauth->ID) . '</div></li><li><div><b>' . $name . '</b></div><div class="em09 page-desc">' . get_user_desc($curauth->ID) . '</div></li>';

    $items = $view_n ? '<item><a data-toggle="tooltip" data-original-title="人气值 ' . $view_n . '">' . zib_svg('huo') . $view_n . '</a></item>' : '';
    $items .= $like_n ? '<item><a data-toggle="tooltip" data-original-title="获得' . $like_n . '个点赞">' . zib_svg('like') . $like_n . '</a></item>' : '';
    $items .= $followed_n ? '<item><a data-toggle="tooltip" data-original-title="共' . $followed_n . '个粉丝"><i class="fa fa-heart em09"></i>' . $followed_n . '</a></item>' : '';

    $metas = ($view_n || $like_n || $followed_n) ? '<div class="article-meta abs-right radius em12">' . $items . '</div>' : '';

    echo '<div class="author-cover page-cover theme-box radius8 main-shadow" >';
    echo '<img class="lazyload fit-cover" ' . (_pz('lazy_cover', true) ? 'src="' . $src . '" data-src="' . $img . '"' : 'src="' . $img . '"') . '>';
    echo '<div class="absolute page-mask"></div>';
    echo '<ul class="list-inline box-body page-cover-con">';
    echo '<li>';
    echo '<div class="avatar-img">';
    echo zib_get_data_avatar($curauth->ID);
    echo '</div>';
    echo '</li>';
    echo '<li>';
    echo '<div>';
    echo '<b>' . $name . '</b>';
    echo '</div>';
    echo '<div class="em09 page-desc">' . get_user_desc($curauth->ID) . '</div>';
    echo '</li>';
    echo '</ul>';
    echo $metas;
    echo '</div>';
}

function zib_author_content()
{
    global $wp_query;
    $curauth = $wp_query->get_queried_object();
    $author_id = $curauth->ID;
    $posts_count = (int) count_user_posts($author_id);;
?>

    <div class="index-tab box-header text-center zib-widget">
        <ul class="scroll-x mini-scrollbar">
            <li class="active"><a data-toggle="tab" href="#author-tab-publish-posts"><i class="fa fa fa-file-text-o mr6"></i>发布</a></li>
            <?php
            echo zib_author_main_tab('nav');
            do_action('zib_author_main_tab', $author_id);
            ?>
        </ul>
    </div>

    <div class="main-bg zib-widget author-content nobottom">

        <div class="tab-content author-tab-content">
            <div class="tab-pane fade in active" id="author-tab-publish-posts">
                <ul class="list-inline scroll-x mini-scrollbar box-body notop">
                    <li class="active"><a class="muted-color" data-toggle="tab" href="#author-tab-publish-posts-data">最新发布</a></li>
                    <?php echo zib_author_posts_tab('nav'); ?>
                </ul>
                <div class="tab-content">
                    <div class="ajaxpager tab-pane fade in active" id="author-tab-publish-posts-data">
                        <?php
                        $args = array(
                            'no_margin' => true,
                            'no_author' => true,
                        );
                        if ($posts_count) {
                            zib_posts_list($args);
                            zib_paging();
                        } else {
                            echo '<div class="text-center">';
                            echo '<p class="em09 muted-3-color separator" style="line-height:160px">暂无文章</p>';
                            if (is_user_logged_in()) {
                                $user_id = get_current_user_id();
                                if ($user_id && $user_id == $author_id && !is_page_template('pages/newposts.php')) {
                                    echo '<p style="padding-bottom: 80px;">' . zib_get_write_posts_button('but jb-blue padding-lg', '发布文章') . '</p>';
                                }
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <?php echo zib_author_posts_tab('content'); ?>
                </div>

            </div>
            <?php
            echo zib_author_main_tab('content');
            do_action('zib_author_main_tab_con', $author_id);
            ?>
        </div>
    </div>
<?php }
function zib_author_posts_tab($type = 'nav')
{
    $tabs = array(
        array(
            'name' => '最近更新',
            'id' => 'posts-orderby-modified',
            'only_me' => false,
        ), array(
            'name' => '热门文章',
            'id' => 'posts-orderby-views',
            'only_me' => false,
        ), array(
            'name' => '最多评论',
            'id' => 'posts-orderby-comment_count',
            'only_me' => false,
        ), array(
            'name' => '待审核',
            'id' => 'posts-orderby-pending',
            'only_me' => true,
        ), array(
            'name' => '回收站',
            'id' => 'posts-orderby-trash',
            'only_me' => true,
        )
    );
    return zib_get_tab($type, $tabs, 'muted-color');
}

function zib_author_main_tab($type = 'nav')
{
    $tabs = array(
        array(
            'name' => '<i class="fa fa-star-o mr6"></i>收藏',
            'id' => 'favorite-posts',
            'only_me' => false,
        ), array(
            'name' => '<i class="fa fa-comments-o mr6"></i>评论',
            'id' => 'comment',
            'only_me' => false,
        ), array(
            'name' => '<i class="fa fa-heart-o mr6"></i>关注',
            'id' => 'follow',
            'only_me' => false,
        ), array(
            'name' => '<i class="fa fa-user-o mr6"></i>资料',
            'id' => 'user-data',
            'only_me' => false,
        ), array(
            'name' => '<i class="fa fa-address-card-o mr6"></i>用户',
            'id' => 'user-data-set',
            'only_me' => true,
        )
    );
    return zib_get_tab($type, $tabs);
}

function zib_get_tab($type = 'nav', $tabs = array(), $nav_class = '', $con_class = 'ajaxpager')
{

    global $wp_query;
    $curauth = $wp_query->get_queried_object();
    $author_id = $curauth->ID;
    $user_id = false;
    $ajax_url = get_stylesheet_directory_uri() . '/action/author-content.php';
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
    }
    $nav = '';
    $con = '';
    foreach ($tabs as $tab) {
        $id = 'author-tab-' . $tab['id'];
        $name = $tab['name'];

        if ($tab['only_me'] && (!$user_id || $user_id != $author_id)) {
            continue;
        }

        $nav .= '<li><a class="' . $nav_class . '" data-toggle="tab" data-ajax="" href="#' . $id . '">' . $name . '</a></li>';
        $con .= '<div class="tab-pane fade ' . $con_class . '" id="' . $id . '">
                <span class="post_ajax_trigger">
                <a ajax-href="' . esc_url($ajax_url . '?type=' . $id . '&id=' . $author_id) . '" class="ajax_load ajax-next ajax-open"></a>
                </span>
                <div class="post_ajax_trigger">' . zib_placeholder('posts-item') . zib_placeholder('posts-item') . '</div>
            </div>';
    }
    if ($type == 'nav') {
        return $nav;
    }
    if ($type == 'content') {
        return $con;
    }
}



function zib_author_card_lists($args, $users_args = array())
{
    $defaults = array(
        'user_id' => '',
        'show_info' => true,
        'show_posts' => true,
        'show_img_bg' => false,
        'show_img' => true,
        'show_name' => true,
        'show_tag' => true,
        'show_button' => true,
        'limit' => 6,
        'orderby' => 'views'
    );
    $args = wp_parse_args((array) $args, $defaults);
    if (!$users_args) {
        $users_args = array(
            'include' => array(),
            'exclude' => array('1'),
            'order' => 'DESC',
            'orderby' => 'user_registered',
            'number' => 8,
        );
    }
    $users = get_users($users_args);

    if ($users) {
        foreach ($users as $user) {
            zib_author_card($user->ID);
        }
    } else {
        echo '未找到用户!';
    }
}

function zib_author_card($user_id = '')
{
    if (!$user_id) return;
    $user_data = get_userdata($user_id);
    $link = get_author_posts_url($user_id);
    $name = $user_data->display_name;
    $img = zib_get_data_avatar($user_id);
    $follow = zib_get_user_follow('focus-color em09 ml10 follow', $user_id);
    $desc = get_user_desc($user_id);
    $args = array(
        'user_id' => $user_id,
        'show_posts' => false,
        'show_info' => 1,
        'show_button' => false,
        'show_img_bg' => 1,
    );

    echo '
    <div class="author-minicard radius8">
        <ul class="list-inline">
            <li><a id="container-' . $user_id . '" data-container="#container-' . $user_id . '" data-target="#author-more-popover' . $user_id . '" data-trigger="hover" data-placement="top" data-toggle="html-popover" class="avatar-img" href="' . $link . '">' . $img . '</a>
            </li>
            <li>
                <dl>
                    <dt><a href="' . $link . '">' . $name . '</a>' . $follow . '</dt>
                    <dd class="avatar-dest em09 muted-3-color text-ellipsis">' . $desc . '</dd>
                </dl>
            </li>
        </ul>
        <div id="author-more-popover' . $user_id . '" class="hide">
        <div class="author-more">';
    zib_posts_avatar_box($args);
    echo '</div></div></div>';
}
function zib_author_datas($user_id = '', $class = 'box-body', $t_class = 'muted-2-color', $v_class = '')
{
    if (!$user_id) return;
    $current_id = get_current_user_id();
    $udata = get_userdata($user_id);
    $privacy = get_user_meta($user_id, 'privacy', true);

    $datas = array(
        array(
            'title' => '用户名',
            'value' => esc_attr($udata->user_login),
            'no_show' => false,
        ), array(
            'title' => '昵称',
            'value' => esc_attr($udata->display_name),
            'no_show' => false,
        ), array(
            'title' => '性别',
            'value' => esc_attr(get_user_meta($user_id, 'gender', true)),
            'spare' => '保密',
            'no_show' => true,
        ), array(
            'title' => '地址',
            'value' => esc_textarea(get_user_meta($user_id, 'address', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '注册时间',
            'value' => $udata->user_registered,
            'spare' => '未知',
            'no_show' => false,
        ), array(
            'title' => '最后登录',
            'value' => get_user_meta($user_id, 'last_login', true),
            'spare' => '未知',
            'no_show' => false,
        ), array(
            'title' => '邮箱',
            'value' => esc_attr($udata->user_email),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '个人网站',
            'value' => zib_get_url_link($user_id),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => 'QQ',
            'value' => esc_attr(get_user_meta($user_id, 'qq', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '微信',
            'value' => esc_attr(get_user_meta($user_id, 'weixin', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '微博',
            'value' => esc_url(get_user_meta($user_id, 'weibo', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => 'Github',
            'value' => esc_url(get_user_meta($user_id, 'github', true)),
            'spare' => '未知',
            'no_show' => true,
        )
    );
    foreach ($datas as $data) {
        if (!is_super_admin() && $data['no_show'] && $privacy != 'public' && $current_id != $user_id) {
            if (($privacy == 'just_logged' && !$current_id) || $privacy == 'not_show') {
                $data['value'] = '用户未公开';
            }
        }
        echo '<div class="' . $class . '">';
        echo '<ul class="list-inline list-author-data">';
        echo '<li class="author-set-left ' . $t_class . '">' . $data['title'] . '</li>';
        echo '<li class="author-set-right ' . $v_class . '">' . ($data['value'] ? $data['value'] : $data['spare']) . '</li>';
        echo '</ul>';
        echo '</div>';
    }
}
function zib_get_url_link($user_id, $class = 'focus-color')
{
    $user_url =  get_userdata($user_id)->user_url;
    $url_name = get_user_meta($user_id, 'url_name', true) ? get_user_meta($user_id, 'url_name', true) : $user_url;
    $user_url =  go_link($user_url, true);
    return '<a class="' . $class . '" href="' . esc_url($user_url) . '" target="_blank">' . esc_attr($url_name) . '</a>';
}

function zib_author_set($user_id = '', $class = 'box-body em12', $t_class = 'muted-2-color', $v_class = '')
{
    $current_id = get_current_user_id();
    if (!$current_id || !$user_id || $current_id != $user_id) {
        return;
    }
    $udata = get_userdata($user_id);
    $_d = array(
        'regtime' => $udata->user_registered,
        'last_login' => get_user_meta($user_id, 'last_login', true),
        'logname' => $udata->user_login,
        'nickname' => $udata->display_name,
        'email' => $udata->user_email,
        'url' => $udata->user_url,
        'roles' => $udata->roles,
        'url_name' => get_user_meta($user_id, 'url_name', true),
        'gender' => get_user_meta($user_id, 'gender', true),
        'address' => get_user_meta($user_id, 'address', true),
        'privacy' => get_user_meta($user_id, 'privacy', true),
        'avatar' => get_user_meta($user_id, 'custom_avatar', true),
        'desc' => get_user_meta($user_id, 'description', true),
        'show_desc' => get_user_desc($user_id),
        'qq' => get_user_meta($user_id, 'qq', true),
        'weixin' => get_user_meta($user_id, 'weixin', true),
        'weibo' => get_user_meta($user_id, 'weibo', true),
        'github' => get_user_meta($user_id, 'github', true)
    );
    $img = zib_get_data_avatar($user_id);
    $oauth_new = get_user_meta($user_id, 'oauth_new', true);
?>


    <ul class="list-inline scroll-x mini-scrollbar box-body author-set-tab">
        <?php do_action('author_info_tab', $user_id); ?>
        <li class="<?php echo _pz('pay_show_user') ? '' : 'active'; ?>"><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-dataset"><i class="fa fa-address-card hide-sm fa-fw" aria-hidden="true"></i>资料修改</a></li>
        <?php
        if (_pz('post_rewards_s')) {
            echo '<li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-rewards"><i class="fa fa-usd hide-sm fa-fw" aria-hidden="true"></i>打赏设置</a></li>';
        }
        ?>
        <li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-avatarset"><i class="fa fa-user-circle-o hide-sm fa-fw" aria-hidden="true"></i>修改头像</a></li>
        <li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-coverimgset"><i class="fa fa-picture-o hide-sm fa-fw" aria-hidden="true"></i>修改封面</a></li>
        <li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-securityset"><i class="fa fa-cog hide-sm fa-fw" aria-hidden="true"></i>账户设置</a></li>
    </ul>
    <div class="author-set-con">
        <div class="tab-content author-tab-set">
            <?php do_action('author_info_tab_con', $user_id); ?>
            <div class="tab-pane list-unstyled<?php echo _pz('pay_show_user') ? '' : ' fade in active'; ?>" id="author-tab-dataset">
                <form>
                    <li>
                        <div class="author-set-left">昵称</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="name" value="<?php echo esc_attr($_d['nickname']) ?>" placeholder="请输入用户名">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">个人签名</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="desc" value="<?php echo esc_attr($_d['desc']) ?>" placeholder="请简短的介绍自己">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">隐私设置</div>
                        <div class="author-set-right">
                            <select class="form-control" name="privacy">
                                <option value="public" <?php selected('public', $_d['privacy']); ?>>社交资料 所有人可见</option>
                                <option value="just_logged" <?php selected('just_logged', $_d['privacy']); ?>>社交资料 仅注册用户可见
                                </option>
                                <option value="not_show" <?php selected('not_show', $_d['privacy']); ?>>社交资料 所有人都不可见
                                </option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">性别</div>
                        <div class="author-set-right">
                            <select class="form-control" name="gender">
                                <option value="保密" <?php selected('保密', $_d['gender']); ?>>保密</option>
                                <option value="男" <?php selected('男', $_d['gender']); ?>>男</option>
                                <option value="女" <?php selected('女', $_d['gender']); ?>>女</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">居住地</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="address" value="<?php echo esc_attr($_d['address']) ?>" placeholder="请输入居住地址">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">个人网站</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="url_name" value="<?php echo esc_attr($_d['url_name']) ?>" placeholder="请输入网站名称">
                            <input type="input" class="form-control" name="url" style="margin-top:10px" value="<?php echo esc_attr($_d['url']) ?>" placeholder="请输入网址">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">QQ</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="qq" value="<?php echo esc_attr($_d['qq']) ?>" placeholder="请输入QQ">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">微信</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="weixin" value="<?php echo esc_attr($_d['weixin']) ?>" placeholder="请输入微信">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">微博</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="weibo" value="<?php echo esc_attr($_d['weibo']) ?>" placeholder="请输入微博地址">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left">Github</div>
                        <div class="author-set-right">
                            <input type="input" class="form-control" name="github" value="<?php echo esc_attr($_d['github']) ?>" placeholder="请输入Github地址">
                        </div>
                    </li>
                    <li>
                        <div class="author-set-left"></div>
                        <div class="author-set-right">
                            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id) ?>">
                            <input type="hidden" name="action" value="edit.datas">
                            <button type="button" action="data.set" class="but b-theme padding-lg author-submit" name="submit">提交</button>
                        </div>
                    </li>

                </form>
            </div>
            <?php if (_pz('post_rewards_s')) {
                $weixin = get_user_meta($user_id, 'rewards_wechat_image_id', true);
                $alipay = get_user_meta($user_id, 'rewards_alipay_image_id', true);
                $rewards_title = get_user_meta($user_id, 'rewards_title', true);
                $weixin_img = '';
                $alipay_img = '';
                if ($weixin) {
                    $weixin = wp_get_attachment_image_src($weixin, 'medium');
                    $weixin_img = '<img class="lazyload fit-cover" data-src="' . esc_attr($weixin[0]) . '">';
                }
                if ($alipay) {
                    $alipay = wp_get_attachment_image_src($alipay, 'medium');
                    $alipay_img = '<img class="lazyload fit-cover" data-src="' . esc_attr($alipay[0]) . '">';
                }
            ?>
                <div class="tab-pane fade" id="author-tab-rewards">
                    <form class="set-rewards-form text-center">
                        <div class="box-body">
                            <p class="muted-color">请在下方设置打赏的标题文案，并上传微信和支付宝收款二维码</p>
                        </div>
                        <div class="box-body radius8 main-shadow">
                            <div class="box-body rewards-title notop">
                                <div class="muted-color text-left">打赏文案：</div>
                                <div class="relative line-form">
                                    <input type="input" class="line-form-input" name="rewards_title" value="<?php echo esc_attr($rewards_title); ?>" placeholder="文章很赞！支持以下吧">
                                    <i class="line-form-line"></i>
                                </div>
                            </div>
                            <ul class="list-inline avatar-upload">
                                <li>
                                    <p class="muted-2-color">微信收款码</p>
                                    <div class="upload-preview large radius8 preview weixin"><?php echo $weixin_img; ?></div>
                                    <label>
                                        <a class="but hollow padding-lg c-green"><i class="fa fa-cloud-upload mr10"></i>上传微信收款码</a>
                                        <input class="hide" type="file" data-preview=".preview.weixin" accept="image/gif,image/jpeg,image/jpg,image/png" data-tag="weixin" name="image_upload" action="image_upload" multiple="false">
                                    </label>
                                </li>
                                <li>
                                    <p class="muted-2-color">支付宝收款码</p>
                                    <div class="upload-preview large radius8 preview alipay"><?php echo $alipay_img; ?></div>
                                    <label>
                                        <a class="but hollow padding-lg c-blue"><i class="fa fa-cloud-upload mr10"></i>上传支付宝收款码</a>
                                        <input class="hide" type="file" data-preview=".preview.alipay" accept="image/gif,image/jpeg,image/jpg,image/png" data-tag="alipay" name="image_upload" action="image_upload" multiple="false">
                                    </label>
                                </li>
                            </ul>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0;">
                                    <span class="">10%</span>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <button type="button" action="info.upload" class="but b-theme author-submit padding-lg" name="submit">提交</button>
                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                            <?php wp_nonce_field('upload_rewards', 'upload_rewards_nonce') ?>
                            <input type="hidden" name="action" value="set.rewards">
                        </div>
                    </form>
                </div>
            <?php } ?>
            <div class="tab-pane fade" id="author-tab-avatarset">
                <div class="box-body">
                    <form class="set-avatar-form text-center">
                        <div class="">
                            <h4>选择头像</h4>
                            <p class="muted-2-color">
                                请在下方上传头像，支持jpg、png、gif格式，大小不能超过<?php echo _pz("up_max_size") ?>M，建议尺寸150x150</p>
                            <ul class="list-inline avatar-upload">
                                <li class="hide-sm">
                                    <div class="upload-preview large radius8 preview">
                                    </div>
                                </li>
                                <li>
                                    <p class="">效果预览</p>
                                    <div class="upload-preview small radius preview"></div>
                                    <p class=""></p>
                                    <label>
                                        <a class="but hollow padding-lg c-yellow"><i class="fa fa-cloud-upload mr10"></i>上传头像</a>
                                        <input class="hide" type="file" accept="image/gif,image/jpeg,image/jpg,image/png" name="image_upload" action="image_upload" multiple="false">
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="box-body">
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0;">
                                    <span class="">10%</span>
                                </div>
                            </div>
                            <button type="button" action="info.upload" class="but b-theme author-submit padding-lg" name="submit">提交</button>
                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                            <?php wp_nonce_field('upload_avatar', 'upload_avatar_nonce') ?>
                            <input type="hidden" name="action" value="upload.avatar">
                        </div>
                    </form>


                </div>
            </div>
            <div class="tab-pane fade" id="author-tab-coverimgset">
                <div class="box-body">

                    <form class="set-cover-form text-center">
                        <div class="">
                            <h4>选择封面图</h4>
                            <p class="muted-2-color">
                                请在下方上传图片，请选择深色图片，支持jpg、png，大小不能超过<?php echo _pz("up_max_size") ?>M，建议尺寸800x400</p>
                            <div class="cover-upload box-body">
                                <div class="cover-preview radius8 relative">
                                    <div class="preview-container preview abs-center"></div>
                                </div>
                            </div>

                            <label>
                                <a class="but hollow padding-lg c-yellow"><i class="fa fa-cloud-upload mr10"></i>上传图片</a>
                                <input class="hide" type="file" accept="image/gif,image/jpeg,image/jpg,image/png" name="image_upload" action="image_upload" multiple="false">
                            </label>

                            </label>
                            <div class="box-body">
                                <div class="progress progress-striped active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0;">
                                    </div>
                                </div>
                                <button type="button" action="info.upload" class="but b-theme author-submit padding-lg" name="submit">提交</button>
                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                <?php wp_nonce_field('upload_cover', 'upload_cover_nonce') ?>
                                <input type="hidden" name="action" value="upload.cover">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="author-tab-securityset">
                <div class="theme-box">
                    <?php do_action('zib_oauth_set', $user_id,$_d) ?>
                    <form>
                        <div class="zib-widget">
                            <div class="box-body">
                                <div class="title-h-left">
                                    <b>
                                        <?php echo $oauth_new ? '设置新密码' : '修改密码' ?>
                                    </b>
                                </div>
                                <div class="muted-2-color"><?php echo $oauth_new ? '您还未设置过密码，请在此设置新密码' : '' ?></div>
                            </div>
                            <?php if ($oauth_new) {
                            ?>
                                <input type="hidden" name="oauth_new" value="<?php echo $oauth_new ?>">
                            <?php } else { ?>
                                <div class="box-body">
                                    <div class="author-set-left">原密码</div>
                                    <div class="author-set-right">
                                        <div class="relative">
                                            <input type="password" class="form-control" name="passwordold" placeholder="请输入原密码">
                                            <label class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></label>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="box-body">
                                <div class="author-set-left">新密码</div>
                                <div class="author-set-right">
                                    <div class="relative">
                                        <input type="password" class="form-control" name="password" placeholder="请输入新密码">
                                        <label class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="author-set-left">确认新密码</div>
                                <div class="author-set-right">
                                    <div class="relative">
                                        <input type="password" class="form-control" name="password2" placeholder="请再输入新密码">
                                        <label class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="author-set-left"></div>
                                <div class="author-set-right">
                                    <button type="button" action="data.set" class="but b-theme padding-lg author-submit" name="submit">提交</button>
                                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                    <input type="hidden" name="action" value="password.edit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php }

add_action('zib_oauth_set', 'zib_oauth_email_set',9,2);
function zib_oauth_email_set($user_id,$user_data)
{
    $captch = _pz('email_set_captch','true');

    $t =$user_data['email'] ? '修改邮箱帐号' : '绑定邮箱帐号';
?>
    <div class="zib-widget">
        <div class="box-body">
            <div class="title-h-left"><b>
                    账户绑定
                </b></div>
            <div class="muted-2-color">绑定邮箱帐号后，方便接收订单信息</div>
        </div>
        <div class="box-body">
            <form>
                <div class="muted-2-color"><?php echo $t ?></div>
                <div class="relative line-form mb10">
                    <input type="text" name="email" class="line-form-input" tabindex="2" value="<?php echo esc_attr($user_data['email']) ?>" placeholder="请输入邮箱">
                    <i class="line-form-line"></i>
                </div>
                <?php if ($captch) {
                            ?>
                <div class="relative line-form signup-captch mb10">
                    <input type="text" name="captch" class="line-form-input" autocomplete="off" tabindex="3" placeholder="请输入验证码">
                    <span class="yztx abs-right"><button type="button" class="but c-blue captchsubmit">发送验证码</button></span>
                    <i class="line-form-line"></i>
                    <input type="hidden" name="captch_type" value="email">
                    <input type="hidden" name="repeat" value="1">
                </div>
                <?php } ?>
                <button type="button" action="data.set" class="but b-theme padding-lg mt10 author-submit" name="submit">提交</button>
                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                <input type="hidden" name="action" value="oauth.email">
            </form>
        </div>
    </div>
<?php }

add_action('zib_oauth_set', 'zib_oauth_set');
function zib_oauth_set($user_id)
{
    if (!$user_id || _pz('social') || (!_pz('oauth_qq_s') && !_pz('oauth_weixin_s') && !_pz('oauth_weibo_s') && !_pz('oauth_github_s'))) return;
    $con = '';
    $rurl = get_author_posts_url($user_id) . '?show_tab=author-tab-user-data-set';
    $args = array();
    $args[] = array(
        'name' => 'QQ',
        'type' => 'qq',
        'class' => 'c-blue',
        'name_key' => 'nickname',
        'icon' => 'fa-qq',
    );
    $args[] = array(
        'name' => '微信',
        'type' => 'weixin',
        'class' => 'c-green',
        'name_key' => 'nickname',
        'icon' => 'fa-weixin',
    );
    $args[] = array(
        'name' => '微博',
        'type' => 'weibo',
        'class' => 'c-red',
        'name_key' => 'screen_name',
        'icon' => 'fa-weibo',
    );
    $args[] = array(
        'name' => 'GitHub',
        'type' => 'github',
        'class' => '',
        'name_key' => 'name',
        'icon' => 'fa-github',
    );

    foreach ($args as $arg) {
        $name = $arg['name'];
        $type = $arg['type'];
        $class = $arg['class'];
        $name_key = $arg['name_key'];
        $icon = $arg['icon'];
        if (_pz('oauth_' . $type . '_s') && !_pz('social')) {
            $oauth_info = get_user_meta($user_id, 'oauth_' . $type . '_getUserInfo', true);
            if ($oauth_info) {
                $con .= '<a data-toggle="tooltip" href="javascript:;" title="解绑' . $name . '帐号" user-id="' . $user_id . '" untying-type="' . $type . '" class="oauth-untying but ' . $class . ' hollow"><i class="fa ' . $icon . '"></i>已绑定' . $name . ' ' . $oauth_info[$name_key] . '</a>';;
            } else {
                $con .= '<a title="绑定' . $name . '帐号" href="' . esc_url(home_url('/oauth/' . $type . '?rurl=' . $rurl)) . '" class="but ' . $class . ' hollow"><i class="fa ' . $icon . '"></i>绑定' . $name . '帐号</a>';
            }
        }
    }

?>
    <div class="zib-widget oauth-set">
        <div class="box-body">
            <div class="title-h-left"><b>
                    绑定社交帐号
                </b></div>
            <div class="muted-2-color">绑定社交帐号之后，您可以更快速的一键登录本站</div>
        </div>
        <div class="box-body">
            <?php echo $con ?>
        </div>
    </div>
<?php }



function zib_posts_avatar_box($args = array())
{
    $defaults = array(
        'user_id' => '',
        'show_info' => true,
        'show_posts' => true,
        'show_img_bg' => false,
        'show_img' => true,
        'show_name' => true,
        'show_tag' => true,
        'show_button' => true,
        'limit' => 6,
        'orderby' => 'views'
    );

    $args = wp_parse_args((array) $args, $defaults);
    if (!$args['user_id']) {
        $user_id = get_the_author_meta('ID');
    } else {
        $user_id = $args['user_id'];
    }
    $cuid = get_current_user_id();

    if (!is_user_logged_in() || $cuid != $user_id) {
        $args['show_button'] = false;
    }
    $avatar = zib_get_data_avatar($user_id);
    $cover = '<img class="lazyload fit-cover" data-src="' . get_user_cover_img($user_id) . '">';
?>
    <div class="article-author main-bg theme-box box-body radius8 main-shadow relative">
        <?php if ($args['show_img_bg']) {
            echo '<div class="avatar-img-bg">';
            echo $cover;
            echo '</div>';
        } ?>
        <ul class="list-inline avatar-info radius8">
            <li>
                <div class="avatar-img"><?php echo $avatar; ?></div>
            </li>
            <li>
                <dl>
                    <?php if ($args['show_name']) { ?>
                        <dt class="avatar-name clearfix">
                            <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">
                                <?php echo esc_textarea((get_the_author_meta('display_name', $user_id))); ?>
                            </a>
                            <?php echo zib_get_user_follow('focus-color em09 ml10 follow', $user_id); ?>
                        </dt>
                    <?php } ?>
                    <?php if ($args['show_tag']) { ?>
                        <dt class="author-tag">
                            <?php zib_avatar_metas($user_id); ?>
                        </dt>
                    <?php } ?>
                    <?php if ($args['show_info']) { ?>
                        <dt class="author-desc muted-3-color em09">
                            <?php if (_pz('yiyan_avatar_desc')) {
                                echo '<div class="yiyan"></div>';
                            } else {
                                echo get_user_desc($user_id);
                            }
                            ?>
                        </dt>
                    <?php } ?>
                    <?php if ($args['show_button']) { ?>
                        <div class="more-button box-body nobottom">
                            <?php
                            if (!is_page_template('pages/newposts.php')) {
                                echo zib_get_write_posts_button('but jb-purple mr10', '发布文章', '');
                            }
                            ?>
                            <?php echo '<a class="but jb-blue" href="' . esc_url( get_author_posts_url($user_id) ). '">个人中心</a>'; ?>
                        </div>
                    <?php } ?>
                </dl>
            </li>
        </ul>
        <?php if ($args['show_posts']) {
            if ($args['show_img']) {
                echo '<ul data-scroll="x">';
                echo '<div class="list-inline more-posts scroll-x mini-scrollbar">';
                zib_avatar_posts($user_id, $args['limit'], $args['orderby'], $args['show_img']);
                echo '</div>';
                echo '</ul>';
            } else {
                echo '<ul class="more-posts-noimg">';
                zib_avatar_posts($user_id, $args['limit'], $args['orderby'], $args['show_img']);
                echo '</ul>';
            }
        };
        ?>
    </div>
<?php }

function zib_avatar_posts($user_id, $count = 6, $orderby = 'views', $show_img = true)
{
    global $post;
    if (!$user_id) {
        $user_id = get_the_author_meta('ID');
    }
    $args = array(
        'post__not_in'        => array($post->ID),
        'author'                => $user_id,
        'showposts' => $count,
        'ignore_sticky_posts' => 1
    );

    if ($orderby !== 'views') {
        $args['orderby'] = $orderby;
    } else {
        $args['orderby'] = 'meta_value_num';
        $args['meta_query'] = array(
            array(
                'key' => 'views',
                'order' => 'DESC'
            )
        );
    }

    $new_query = new WP_Query($args);
    while ($new_query->have_posts()) {
        $new_query->the_post();
        $title = get_the_title() . get_the_subtitle(false);
        if ($show_img) {
            echo '<li class="box-body">';
            echo '<a class="relative radius8" href="' . get_permalink() . '">' . zib_post_thumbnail() . '
            <span>' . get_the_title() . get_the_subtitle() . '</span>
            </a>';
            echo '</li>';
        } else {
            echo '<li><a class="icon-circle text-ellipsis" href="' . get_permalink() . '">' . get_the_title() . get_the_subtitle() . '</a></li>';
        }
    };
    wp_reset_query();
    wp_reset_postdata();
}
