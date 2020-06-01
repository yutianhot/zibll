<?php

/**
 * Template name: Zibll-文章导航
 * Description:   根据分类显示文章列表的导航模板
 */

get_header();
global $post;
$show_cat_id = array();
$post_ID = $post->ID;
$show_cat_id = get_post_meta($post_ID, 'navs_show_cat_id', true);
$page_desc = get_post_meta($post_ID, 'navs_page_desc', true);
$page_name = get_post_meta($post_ID, 'navs_page_name', true);
$title = get_the_title();
$page_name = $page_name ? $page_name : $title;
?>
<div class="container">
    <div class="main-bg theme-box radius8 main-shadow box-body">
        <div class="box-body">
            <h2 class="theme-box">
                <?php echo $page_name;
                if (is_user_logged_in() && is_super_admin()) {
                    echo '<small> <a style="cursor:pointer" data-toggle="modal" data-target="#post-navs-sz"> [设置]</a></small>';
                }
                ?>
            </h2>
            <div class="pull-right em12 page-share">
                <?php zib_share() ?>
            </div>

            <div class="muted-color">
                <?php echo $page_desc ? $page_desc : '暂无简介内容，请设置' ?>
            </div>
        </div>
    </div>
</div>

<main class="container">
    <div class="content-wrap">
        <div class="content-layout">
            <div class="posts-navs" data-nav="posts">
                <?php
                $args = array(
                    'orderby' => 'term_group',
                    'order' => 'ASC',
                    'hide_empty' => false
                );
                $cats = get_categories($args);
                //	echo 	json_encode($cats);
                if ($show_cat_id) {
                    foreach ($show_cat_id as $c_id) {
                        $the_cat = get_category($c_id);
                        $posts = get_posts(array(
                            'category' => $c_id,
                            'numberposts' => -1,
                        ));

                        $time = get_the_time('Y-m-d', $post->cat_ID);
                        $time_g = zib_get_time_ago(get_the_time('U', $post->cat_ID));
                        $tip = $time . '发布 ' . get_post_view_count($before = '', $after = '') . '次阅读';
                        if (!empty($posts)) {
                            echo '
                                <div class="theme-box">
                                <div class="box-body notop"><div class="title-theme"><h2>' . $the_cat->name . '</h2></div></div>
                                <div class="main-bg radius8 main-shadow box-body">
                            <ul>';
                            foreach ($posts as $post) {
                                $subtitle = get_post_meta($post->ID, 'subtitle', true);
                                echo '<p>
                                        <a title="' . $tip . '" href="' . get_permalink($post->ID) . '"><div title="' . $tip . '" data-toggle="tooltip" class="text-ellipsis"><span class="muted-2-color">' . $time_g . '</span><span class="icon-circle"></span>' . $post->post_title . $subtitle . '</div></a>
                                    </p>';
                            }
                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                }
                ?>

            </div>
        </div>
    </div>
    <div class="sidebar">
        <div class="posts-nav-box" data-title="导航目录"></div>
    </div>
</main>

<?php if (is_user_logged_in() && is_super_admin()) { ?>
    <div class="modal fade" id="post-navs-sz" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <p class="modal-title">当前页面设置
                            <button class="close" data-dismiss="modal">
                                <i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i>
                            </button>
                        </p>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="page_name">标题</label>
                            <input type="text" class="form-control" id="page_name" name="navs_page_name" placeholder="自定义标题" value="<?php echo $page_name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="page_desc">简介</label>
                            <input type="text" class="form-control" id="page_desc" name="navs_page_desc" placeholder="自定义简介" value="<?php echo $page_desc; ?>">
                        </div>
                        <div class="form-group">
                            <label>请将需要显示的分类拖动到选区并排序</label>
                            <div class="row text-center">
                                <div class="col-xs-6 col-sm-6">
                                    <p class="text-muted">未选择的分类</p>
                                    <div class="cat-group mini-scrollbar" id="cat-sortable">
                                        <?php
                                        foreach ($cats as $the_cat) {
                                            $c_id = $the_cat->cat_ID;
                                            $c_name = rtrim(get_category_parents($c_id, false, '>'), '>');
                                            if ($show_cat_id && in_array($c_id, $show_cat_id)) {
                                                continue;
                                            }
                                            echo '<span class="sortable-cat-item" data-id="' . $c_id . '">' . $c_name . '<span class="badge">' . $the_cat->count . '</span></span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <p class="text-muted">已选择的分类</p>
                                    <div class="cat-group mini-scrollbar" id="cat-sortable-ok">
                                        <?php
                                        if ($show_cat_id) {
                                            foreach ($show_cat_id as $c_id) {
                                                $the_cat = get_category($c_id);
                                                $c_id = $the_cat->cat_ID;
                                                $c_name = rtrim(get_category_parents($c_id, false, '>'), '>');
                                                echo '<span class="sortable-cat-item" data-id="' . $c_id . '">' . $c_name . '<span class="badge">' . $the_cat->count . '</span></span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="post-navs.settings">
                        <input type="hidden" name="page_id" value="<?php echo $post_ID; ?>">
                        <button class="btn2 btn btn-primary" evt="action">确认修改</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    <div class="navs-bj"></div>
<?php
} ?>

<?php
get_footer();
