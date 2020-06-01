<?php
get_header();
?>
<?php if (function_exists('dynamic_sidebar')) {
    echo '<div class="container fluid-widget">';
    dynamic_sidebar('all_top_fluid');
    dynamic_sidebar('single_top_fluid');
    echo '</div>';
}
?>
<main role="main" class="container site-layout-3">
    <?php echo zib_breadcrumbs(); ?>
    <div class="content-wrap">
        <div class="content-layout">

            <?php while (have_posts()) : the_post();
                $user_id = get_the_author_meta('ID');
            ?>

                <article class="article main-bg theme-box box-body radius8 main-shadow">
                    <?php zib_single_dosc_header() ?>
                    <?php zib_single_dosc_content() ?>
                </article>
            <?php endwhile; ?>
            <?php zib_posts_prevnext(); ?>
            <?php if (comments_open()) {
                comments_template('', true);
            } ?>

        </div>
    </div>
    <div class="sidebar show-sidebar">
        <?php zib_single_cat_search(zib_is_docs_mode()); ?>
        <?php zib_single_dosc_cat_nav(); ?>
        <div data-affix="1" data-title="文章目录" class="posts-nav-box"></div>

    </div>

    <?php if (_pz('share_img') && _pz('share_s')) {
        zib_Screenshot_share();
    } ?>

</main>
<?php if (function_exists('dynamic_sidebar')) {
    echo '<div class="container fluid-widget">';
    dynamic_sidebar('single_bottom_fluid');
    dynamic_sidebar('all_bottom_fluid');
    echo '</div>';
}
?>
<?php get_footer();

function zib_single_dosc_header()
{
    $title = get_the_title() . get_the_subtitle();
    $time_up = zib_get_time_ago(get_the_modified_time('U'));
    $time = zib_get_time_ago(get_the_time('U'));

    if ((get_the_modified_time('Y') * 365 + get_the_modified_time('z')) > (get_the_time('Y') * 365 + get_the_time('z'))) {
        $time_html = '<span data-toggle="tooltip" title="' . get_the_time('Y年m月d日 H:i') . '发布" class="article-avatar">' . $time_up . '更新</span>';
    } else {
        $time_html = '<span data-toggle="tooltip" title="' . get_the_time('Y年m月d日 H:i') . '发布" class="article-avatar">' . $time . '发布</span>';
    }
    $meta = '';
    if (comments_open()) {
        $meta .= '<item class="meta-comm"><a data-toggle="tooltip" title="去评论" href="' . get_comments_link() . '">' . zib_svg('comment') . get_comments_number('0', '1', '%') . '</a></item>';
    }
    $meta .= '<item class="meta-view" data-toggle="tooltip" title="阅读">' . zib_svg('view') . get_post_view_count($before = '', $after = '') . '</item>';
    $meta .= '<item class="meta-like" data-toggle="tooltip" title="点赞">' . zib_get_post_like('action action-like', '', '') . '</item>';
    $meta .= '<item class="meta-favorite" data-toggle="tooltip" title="收藏">' . zib_get_post_favorite('action action-favorite', '', '') . '</item>';
?>
    <div class="article-header theme-box clearfix">
        <div class="dosc-article-title">
            <div class="title-h-left">
                <a href="<?php the_permalink() ?>"><b><?php echo $title; ?></b></a>
                <span class="smail"><?php echo zib_get_admin_edit('编辑此文章', 'posts'); ?></span>
            </div>
            <div class="dosc-article-meta muted-2-color">
                <?php echo '<item class="meta-time">' . $time_html . '</item>' . $meta; ?>
            </div>
        </div>
    </div>
<?php }
function zib_single_dosc_content()
{
    global $post;
    $show_nav = zib_is_show_posts_nav();
    $show_nav_data = '';
    $favorite_button = zib_get_post_favorite($class = 'action action-favorite');
    $share_button = zib_get_share();

    if ($show_nav) {
        $show_nav_data .= 'data-nav="posts"';
    }
?>
    <div class="article-content">
        <div <?php echo $show_nav_data; ?>class="theme-box wp-posts-content">
            <?php
            echo _pz('post_front_content');
            the_content();
            echo _pz('post_after_content');
            ?>
        </div>
        <div class="article-content">
            <?php
            if (_pz('post_copyright_s')) {
                echo '<div class="em09 muted-3-color"><div><span>©</span> 版权声明</div><div class="posts-copyright">' . _pz('post_copyright') . '</div></div>';
            }
            echo '<div class="article-docs-footer">';
            if (_pz('share_s')) {
                echo $share_button;
            }
            echo '</div>';
            ?>
        </div>
    </div>

<?php }

function zib_single_dosc_cat_nav()
{

    echo '<div class="theme-box zib-widget dosc-nav">';

    echo '<div class="title-h-left"><b>主题文档</b></div>';
    $docs_cat = zib_is_docs_mode();
    zib_single_dosc_nav($docs_cat);

    echo '</div>';
}

function zib_single_dosc_nav($cat_id = '', $data_parent = 'dosc-nav-panel')
{
    if (!$cat_id) return;
    $pid = get_queried_object_id();

    $terms = _get_term_hierarchy('category');
    if (!isset($terms[$cat_id])) {
        $cat_obj = get_category($cat_id);
        $posts = get_posts(array(
            'category' => $cat_id,
            'numberposts' => -1,
        ));
        $lists = '';
        $active = '';
        foreach ($posts as $post) {
            $subtitle = get_post_meta($post->ID, 'subtitle', true);
            $active = $post->ID == $pid ? ' active' : '';
            $lists .= '<li class="' . $active . '">
            <a class="text-ellipsis icon-spot" href="' . get_permalink($post->ID) . '">' . $post->post_title . $subtitle . '</a>
        </li>';
        }
        echo '<ul class="relative nav">';
        echo $lists;
        echo '</ul>';
    } else {
        echo '<div class="panel-group" id="' . $data_parent . '">';
        foreach ((array) $terms[$cat_id] as $child) {
            // echo 'child_id:' . json_encode($child) . '<br>';
            if ($cat_id === $child) {
                continue;
            }

            $cat_obj = get_category($child);
            $posts = get_posts(array(
                'category' => $child,
                'numberposts' => -1,
            ));
            $lists = '';
            $open = '';
            foreach ($posts as $post) {
                $subtitle = get_post_meta($post->ID, 'subtitle', true);
                $active = $post->ID == $pid ? ' active' : '';
                if ($post->ID == $pid) {
                    $open = true;
                }
                $lists .= '<li class="' . $active . '">
                        <a class="text-ellipsis icon-spot" href="' . get_permalink($post->ID) . '">' . $post->post_title . $subtitle . '</a>
                    </li>';
            }
            echo '<div class="panel">';
            echo '<a class="panel-toggle' . (empty($terms[$child]) && $open ? '' : ' collapsed') . '" data-toggle="collapse" data-parent="#' . $data_parent . '" href="#dosc-nav-catid_' . $child . '"><i class="fa fa-angle-right mr10"></i><b>' . $cat_obj->cat_name . '</b></a>';
            echo '<div id="dosc-nav-catid_' . $child . '" class="panel-collapse' . (empty($terms[$child]) && $open ? ' collapse in' : ' collapse') . '">';
            echo '<ul class="relative nav">';
            if (!empty($terms[$child])) {
                echo '<div class="panel-child">';
                zib_single_dosc_nav($child, 'dosc-cat-nav-panel-' . $child);
                echo '</div>';
            } else {
                echo $lists;
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
    // echo json_encode($cat_obj);
}
