<?php get_header(); ?>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('all_top_fluid');
	dynamic_sidebar('cat_top_fluid');
	echo '</div>';
}
?>
<main role="main" class="container">
	<div class="content-wrap">
		<div class="content-layout">
			<?php
            zib_dosc_cat_cover();
            echo '<div class="ajaxpager">';
            echo '<div class="ajax-item">';
            zib_dosc_cat_content();
            echo '</div>';
            echo '</div>';
			?>
		</div>
    </div>
    <?php get_sidebar(); ?>
</main>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('cat_top_fluid');
	dynamic_sidebar('all_bottom_fluid');
	echo '</div>';
}
?>
<?php get_footer();

function zib_dosc_cat_cover($cat_id = '')
{
    $desc = trim(strip_tags(category_description()));
    if (is_super_admin() && !$desc) {
        $desc = '请在Wordress后台-文章-文章分类中添加分类描述！';
    }

    $desc .= zib_get_admin_edit('编辑此分类');

    global $wp_query;
    if (!$cat_id) {
        $cat_id = get_queried_object_id();
    }
    $cat = get_category($cat_id);
    $count = zib_get_cat_postcount($cat_id,'category');
    $title = '<i class="fa fa-folder-open em12 mr10 ml6" aria-hidden="true"></i>' . $cat->cat_name;
    $title .= '<span class="icon-spot">共' . $count . '篇</span>';
    $img = zib_get_taxonomy_img_url();

    $src = get_stylesheet_directory_uri() . '/img/thumbnail-lg.svg';
    $img = $img ? $img : _pz('page_cover_img', get_stylesheet_directory_uri() . '/img/user_t.jpg');
?>
    <div class="page-cover theme-box radius8 main-shadow">
        <img class="fit-cover no-scale lazyload" <?php echo _pz('lazy_cover', true) ? 'src="' . $src . '" data-src="' . $img . '"' : 'src="' . $img . '"'; ?>>
        <div class="absolute page-mask"></div>
        <div class="list-inline box-body abs-center text-center">
            <div class="title-h-center">
                <b class="em12"><?php echo $cat->cat_name ?></b>
            </div>
            <div class="em09 page-desc theme-box"><?php echo $desc; ?></div>
            <?php zib_cat_dosc_search() ?>
        </div>
    </div>
<?php }

function zib_cat_dosc_search()
{
    $cat_id = zib_is_docs_mode();
    $cat_obj = get_category($cat_id);
?>
    <div class="dosc-search">
        <div class="search-input">
            <form method="get" class="relative line-form" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="search-input-cat option-dropdown splitters-this-r">
                    <span class="text-ellipsis" name="cat"><?php echo $cat_obj->cat_name ?></span>
                    <input type="hidden" name="cat" tabindex="1" value="<?php echo $cat_id ?>">
                </div>
                <input type="text" name="s" class="line-form-input" tabindex="2" placeholder="搜索<?php echo $cat_obj->cat_name ?>">
                <div class="abs-right muted-color">
                    <button type="submit" tabindex="3" class="null"><?php echo zib_svg('search'); ?></button>
                </div>
                <i class="line-form-line"></i>
            </form>
        </div>
    </div>
<?php
}

function zib_dosc_cat_content()
{

    echo '<div class="theme-box zib-widget dosc-cat-content">';
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
        $args = array(
            'show_thumb' => 1,
            'show_meta' => 1,
            'show_number' => 0,
        );
        $new_query = array(
            'cat' => $cat_id,
            'showposts' => -1,
            'ignore_sticky_posts' => 1
        );
        $new_query = new WP_Query($new_query);
        while ($new_query->have_posts()) : $new_query->the_post();
        zib_posts_mini_while($args,0);
        endwhile;
    } else {
        echo '<div class="panel-group" id="' . $data_parent . '">';
        foreach ((array) $terms[$cat_id] as $child) {
            // echo 'child_id:' . json_encode($child) . '<br>';
            if ($cat_id === $child) {
                continue;
            }
            $cat_obj = get_category($child);
            echo '<div class="theme-box">';
            echo '<div class="title-h-left"><b>' . $cat_obj->cat_name . '</b></div>';
            echo '<div class="panel-collapse">';
            echo '<ul class="box-body">';
            if (!empty($terms[$child])) {
                echo '<div class="box-child">';
                zib_single_dosc_nav($child, 'dosc-cat-nav-panel-' . $child);
                echo '</div>';
            } else {
                $args = array(
                    'show_thumb' => 0,
                    'show_meta' => 0,
                    'show_number' => 1,
                );
                $new_query = array(
                    'cat' => $child,
                    'showposts' => -1,
					'ignore_sticky_posts' => 1
                );
                $number = 0;
                $new_query = new WP_Query($new_query);
                while ($new_query->have_posts()) : $new_query->the_post();
                $number ++;
                zib_posts_mini_while($args,$number);
                endwhile;
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
    // echo json_encode($cat_obj);
}
