<?php

/**
 * Template name: Zibll-写文章、投稿页面
 * Description:   用户前台发布文章的页面模板
 */

get_header();
$btn_txet = _pz('post_article_review_s') ? '发布' : '审核';
if (!is_user_logged_in()) {
    $btn_txet = '审核';
}
?>
<?php if (!_pz('post_article_s')) {
    get_template_part('template/content-404');
    get_footer();
    exit();
}
?>
<main role="main" class="container">
    <form>
        <?php
        $cuid = get_current_user_id();
        $draft_id = get_user_meta($cuid, 'posts_draft', true);
        $post_title = '';
        $post_content = '';
        $post_cat = '';
        $post_tags = '';
        $post_tag = '';
        $post_uptime = '';
        $view_btn = '';
        if ($draft_id) {
            $args = array(
                'include'          => array($draft_id),
                'post_status'     => 'draft'
            );
            $draft = get_posts($args);
            if (!empty($draft[0])) {
                $post_title = $draft[0]->post_title;
                $post_content = $draft[0]->post_content;
                $post_uptime = '最后保存：' . $draft[0]->post_modified;
                $post_cat = get_the_category($draft_id)[0]->term_id;
                $post_tags = get_the_tags($draft_id);
                $view_btn = '<a target="_blank" href="' . get_permalink($draft_id) . '" class="but c-blue">预览文章</a>';
                if ($post_tags) {
                    foreach ($post_tags as $_tag) {
                        $post_tag .= $_tag->name . ',';
                    }
                }
            }
        }
        ?>
        <div class="content-wrap newposts-wrap">
            <div class="content-layout">

                <div class="main-bg theme-box radius8 box-body main-shadow">
                    <div class="relative theme-box newposts-title">
                        <input type="text" class="line-form-input input-lg new-title" name="post_title" tabindex="1" value="<?php echo $post_title; ?>" placeholder="请输入文章标题">
                        <i class="line-form-line"></i>
                        <div class="abs-right view-btn"><?php 
                        if (is_user_logged_in()&&current_user_can( 'edit_post', $draft_id )){
                        echo $view_btn;} ?></div>
                    </div>

                    <p class="em09 muted-3-color pull-right modified-time">
                        <time><?php echo $post_uptime; ?></time>
                    </p>

                    <?php
                    //  echo '<pre>'.json_encode($draft).'</pre>';
                    $content = $post_content;
                    $editor_id = 'post_content';
                    $settings = array(
                        'textarea_rows' => 20,
                        'editor_height' => 510,
                        'media_buttons' => false,
                        'default_editor' => 'tinymce',
                        'quicktags' => false,
                        'editor_css'    => '',
                        'tinymce'       => array(
                            'content_css' => get_stylesheet_directory_uri() . '/css/tinymce.css',
                        ),
                        'teeny' => false,
                    );
                    if(_pz('post_article_img_s')){
                        $settings['media_buttons'] = true;
                    }
                    if (!is_user_logged_in() && (_pz('post_article_limit', 'logged_in') == 'logged_in')) {

                        echo '<div class="text-center" style="padding:100px 0">';
                        echo '<p class="separator muted-3-color box-body">请先登录</p>';
                        echo '<p>';
                        echo '<a href="javascript:;" class="signin-loader but radius jb-blue padding-lg"><i class="fa fa-fw fa-sign-in mr10"></i>登录</a>';
                        echo '<a href="javascript:;" class="signup-loader ml10 but radius jb-yellow padding-lg"><i class="fa fa-fw fa-pencil-square-o mr10"></i>注册</a>';
                        echo '</p>';
                        zib_social_login();
                        echo '</div>';
                    } else {
                        wp_editor($content, $editor_id, $settings);
                    }

                    ?>

                </div>
            </div>
        </div>

        <div class="sidebar show-sidebar">
            <?php if (!is_user_logged_in()) {
            ?>
                <div class="theme-box">
                    <div class="main-bg theme-box radius8 main-shadow relative">
                        <?php if (_pz('post_article_limit', 'logged_in') != 'logged_in') {
                        ?>
                            <div class="box-header">
                                <div class="title-theme">用户信息</div>
                            </div>
                            <div class="box-body">
                                <p class="muted-3-color">请填写用户信息：</p>
                                <div class="box-body notop">
                                    <input class="form-control" type="text" name="user_name" value="" tabindex="3" placeholder="昵称">
                                </div>
                                <div class="box-body notop">
                                    <input class="form-control" type="text" name="contact_details" value="" tabindex="4" placeholder="联系方式">
                                </div>
                            </div>
                        <?php } ?>
                        <div class="text-center box-body">
                            <p class="separator muted-3-color box-body">快速登录</p>
                            <p>
                                <a href="javascript:;" class="signin-loader but c-blue"><i class="fa fa-fw fa-sign-in mr10"></i>登录</a>
                                <a href="javascript:;" class="signup-loader ml10 but c-yellow"><i class="fa fa-fw fa-pencil-square-o mr10"></i>注册</a>
                            </p>
                            <?php zib_social_login(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (is_user_logged_in()) {
            ?>
                <div class="main-bg theme-box radius8 main-shadow relative">

                    <?php $args = array(
                        'user_id' => $cuid,
                        'show_posts' => false,
                        'show_img_bg' => true,
                    );
                    zib_posts_avatar_box($args); ?>

                </div>
            <?php } ?>
            <div class="theme-box">
                <div class="main-bg theme-box radius8 main-shadow relative">
                    <div class="box-header">
                        <div class="title-theme">文章分类</div>
                    </div>

                    <div class="box-body">

                        <select class="form-control" name="category" tabindex="5">
                            <?php
                            $cat_ids = array();
                            if (_pz('post_article_cat')) {
                                foreach (_pz('post_article_cat') as $key => $value) {
                                    if ($value) $cat_ids[] = $key;
                                }
                            }
                            if ($cat_ids) {
                                foreach ($cat_ids as $c_id) {
                                    $the_cat = get_category($c_id);
                                    $c_name = $the_cat->name;

                                    echo '<option value="' . $c_id . '" ' . selected($c_id, $post_cat) . '>' . $c_name . '</option>';
                                }
                            }
                            ?>
                        </select>

                    </div>
                    <div class="box-header">
                        <div class="title-theme">文章标签</div>
                    </div>
                    <div class="box-body">
                        <p class="muted-3-color em09">填写文章的标签，每个标签用逗号隔开</p>
                        <textarea class="form-control" rows="3" name="tags" placeholder="输入文章标签" tabindex="6"><?php echo $post_tag; ?></textarea>
                    </div>
                    <div class="box-body">
                        <div class="text-center">
                            <p class="separator muted-3-color">Are you ready</p>
                            <?php if (is_user_logged_in()) {
                                echo '<p class="em09 muted-3-color">如果您的文章还未完全写作完成，请先保存草稿，文章提交' . $btn_txet . '之后不可再修改！</p>';
                                echo '<botton type="button" action="posts.draft" name="submit" class="but jb-green mr6"><i class="fa fa-fw fa-sign-in mr10"></i>保存草稿</botton>';
                            }
                            if ($draft_id) {
                                echo '<input type="hidden" name="posts_id" value="' . $draft_id . '">';
                            }
                            ?>
                            <botton type="button" action="posts.save" name="submit" class="ml10 but jb-blue ml6"><i class="fa fa-fw fa-pencil-square-o mr10"></i>提交<?php echo $btn_txet ?></botton>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php

        ?>

        </div>
    </form>
</main>
<?php get_footer();
