<?php
/**获取分类全部文章数量 */
function zib_get_cat_postcount($id,$type = 'category')
{
    // 获取当前分类信息
    $cat = get_term($id, $type);
    // 当前分类文章数
    $count = (int) $cat->count;
    // 获取当前分类所有子孙分类
    $tax_terms = get_terms($type, array('child_of' => $id));
    foreach ($tax_terms as $tax_term) {
        // 子孙分类文章数累加
        $count += $tax_term->count;
    }
    return $count;
}

function zib_topics_cover($cat_id = '')
{
    $desc = trim(strip_tags(category_description()));
    if (is_super_admin() && !$desc) {
        $desc = '请在Wordress后台-文章-文章专题中添加专题描述！';
    }

    $desc .= zib_get_admin_edit('编辑此专题');

    global $wp_query;
    if (!$cat_id) {
        $cat_id = get_queried_object_id();
    }
    $cat = get_term($cat_id, 'topics');
    $count = $cat->count;
    $title = '<b class="em12"><i class="fa fa-cube mr6" aria-hidden="true"></i>' . $cat->name . '</b>';
    // $title .= '<span class="icon-spot">共' . $count . '篇</span>';
    //$title .='<pre>'. json_encode($cat) .'</pre>';
    $img = zib_get_taxonomy_img_url();
    zib_page_cover($title, $img, $desc, '', true);
}

function zib_cat_cover($cat_id = '')
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
    //$title .='<pre>'. json_encode($cat) .'</pre>';
    $img = zib_get_taxonomy_img_url();
    if (_pz('page_cover_cat_s', true)) {
        zib_page_cover($title, $img, $desc);
    } else {
        echo '<div class="main-bg text-center box-body radius8 main-shadow theme-box">';
        echo '<h4 class="title-h-center">' . $title . '</h4>';
        echo '<div class="muted-2-color">' . $desc . '</div>';
        echo '</div>';
    }
}

function zib_tag_cover()
{
    $desc = trim(strip_tags(tag_description()));
    if (is_super_admin() && !$desc) {
        $desc = '请在Wordress后台-文章-文章分类中添加标签描述！';
    }

    $desc .= zib_get_admin_edit('编辑此标签');
    global $wp_query;
    $tag_id = get_queried_object_id();
    $tag = get_tag($tag_id);
    $count = $tag->count;
    $title = '<i class="fa fa-tag em12 mr10 ml6" aria-hidden="true"></i>' . $tag->name;
    $title .= '<span class="icon-spot">共' . $count . '篇</span>';
    $img = zib_get_taxonomy_img_url();
    if (_pz('page_cover_tag_s', true)) {
        zib_page_cover($title, $img, $desc);
    } else {
        echo '<div class="main-bg text-center box-body radius8 main-shadow theme-box">';
        echo '<h4 class="title-h-center">' . $title . '</h4>';
        echo '<div class="muted-2-color">' . $desc . '</div>';
        echo '</div>';
    }
}

function zib_page_cover($title, $img, $desc, $more = '', $center = false)
{
    $paged = (get_query_var('paged', 1));
    if ($paged && $paged > 1) {
        $title .= ' <small class="icon-spot">第' . $paged . '页</small>';
    }
    $src = get_stylesheet_directory_uri() . '/img/thumbnail-lg.svg';
    $img = $img ? $img : _pz('page_cover_img', get_stylesheet_directory_uri() . '/img/user_t.jpg');
?>
    <div class="page-cover theme-box radius8 main-shadow">
        <img class="lazyload fit-cover" <?php echo _pz('lazy_cover', true) ? 'src="' . $src . '" data-src="' . $img . '"' : 'src="' . $img . '"'; ?>>
        <div class="absolute page-mask"></div>
        <div class="list-inline box-body <?php echo $center ? 'abs-center text-center' : 'page-cover-con'; ?>">
            <div class="<?php echo $center ? 'title-h-center' : 'title-h-left'; ?>">
                <b><?php echo $title; ?></b>
            </div>
            <div class="em09 page-desc"><?php echo $desc; ?></div>
        </div>
        <?php echo $more; ?>
    </div>
<?php }

function zib_option_cat($show_cat = true, $show_top = true, $show_tag = true)
{
    if (!$show_cat && !$show_top && !$show_tag) return;
    $all_cat = zib_get_all_cat_link('ajax-next text-ellipsis');
    $all_tag = zib_get_all_tags_link('ajax-next text-ellipsis');
    $all_top = zib_get_all_topics_link('ajax-next text-ellipsis');
    $op_top = zib_get_option_topics_link('ajax-next text-ellipsis');
    $op_cat = zib_get_option_cat_link('ajax-next text-ellipsis');
    $op_tag = zib_get_option_tags_link('ajax-next text-ellipsis');
    $_set = '<a style="color:#f73636;" target="_blank" href="' . of_get_menuurl('options-group-9-tab') . '">[设置]</a>';
    if (is_super_admin()) {
        $op_top = $op_top ? $op_top : '<span>（！列表为空）</sapn>' . $_set;
        $op_cat = $op_cat ? $op_cat : '<span>（！列表为空）</sapn>' . $_set;
        $op_tag = $op_tag ? $op_tag : '<span>（！列表为空）</sapn>' . $_set;
    }
?>
    <div class="ajax-option">
        <?php if ($show_cat) { ?>
            <div class="option-dropdown splitters-this-r">分类
                <?php if (_pz('option_list_alllist_s')) {
                ?>
                    <i class="fa fa-fw fa-sort ml6" aria-hidden="true"></i>
                    <div class="option-dropdown-items main-shadow radius8 main-bg scroll-y mini-scrollbar">
                        <?php echo $all_cat; ?>
                    </div>
                <?php } ?>
            </div>
            <ul class="list-inline scroll-x mini-scrollbar option-items">
                <?php echo $op_cat; ?>
            </ul>
        <?php } ?>
        <?php if ($show_top) { ?>
            <div class="option-dropdown splitters-this-r">专题
                <?php if (_pz('option_list_alllist_s')) {
                ?>
                    <i class="fa fa-fw fa-sort ml6" aria-hidden="true"></i>
                    <div class="option-dropdown-items main-shadow radius8 main-bg scroll-y mini-scrollbar">
                        <?php echo $all_top; ?>
                    </div>
                <?php } ?>
            </div>
            <ul class="list-inline scroll-x mini-scrollbar option-items">
                <?php echo $op_top; ?>
            </ul>
        <?php } ?>
        <?php if ($show_tag) { ?>
            <div class="option-dropdown splitters-this-r">标签
                <?php if (_pz('option_list_alllist_s')) { ?>
                    <i class="fa fa-fw fa-sort ml6" aria-hidden="true"></i>
                    <div class="option-dropdown-items main-shadow radius8 main-bg scroll-y mini-scrollbar">
                        <?php echo $all_tag; ?>
                    </div>
                <?php } ?>
            </div>
            <ul class="list-inline scroll-x mini-scrollbar option-items">
                <?php echo $op_tag; ?>
            </ul>
        <?php } ?>
    </div>
    <div></div>
<?php }


function zib_get_option_topics_link($link_class = '', $before = '', $after = '', $shou_count = false)
{

    $cats = _pz('option_list_topics');
    $links = '';

    if ($cats) {
        foreach ($cats as $key => $value) {
            if ($value) {
                $cat = get_term($key, 'topics');
                $links .= $before . '<a ajax-replace="true" class="' . $link_class . '" title="查看此专题更多文章" href="' . get_category_link($cat->term_id) . '">' . $cat->name . ($shou_count ? ' ' . zib_get_cat_postcount($cat->term_id,'topics') : '') . '</a>' . $after;
            }
        }
    }
    return $links;
}

function zib_get_all_topics_link($link_class = '', $before = '', $after = '', $shou_count = false)
{
    $cats = get_terms(array(
        'taxonomy' => 'topics',
        'hide_empty' => false,
    ));
    $links = '';
    if (!empty($cats[0])) {
        foreach ($cats as $cat) {
            $links .= $before . '<a ajax-replace="true" class="' . $link_class . '" title="查看此专题更多文章" href="' . get_category_link($cat->term_id) . '">' . $cat->name . ($shou_count ? ' ' . zib_get_cat_postcount($cat->term_id,'topics') : '') . '</a>' . $after;
        }
    }
    return $links;
}

function zib_get_option_cat_link($link_class = '', $before = '', $after = '', $shou_count = false)
{

    $cats = _pz('option_list_cats');
    $links = '';

    if ($cats) {
        foreach ($cats as $key => $value) {
            if ($value) {
                $cat = get_category($key);
                $links .= $before . '<a ajax-replace="true" class="' . $link_class . '" title="查看更多分类文章" href="' . get_category_link($cat->cat_ID) . '">' . $cat->cat_name . ($shou_count ? ' ' . zib_get_cat_postcount($cat->cat_ID,'category') : '') . '</a>' . $after;
            }
        }
    }
    return $links;
}

function zib_get_all_cat_link($link_class = '', $before = '', $after = '', $shou_count = false)
{
    $cats = get_categories();
    $links = '';
    if (!empty($cats[0])) {
        foreach ($cats as $cat) {
            $links .= $before . '<a ajax-replace="true" class="' . $link_class . '" title="查看此分类更多文章" href="' . get_category_link($cat->cat_ID) . '">' . $cat->cat_name . ($shou_count ? ' ' . zib_get_cat_postcount($cat->cat_ID,'category') : '') . '</a>' . $after;
        }
    }
    return $links;
}
function zib_get_option_tags_link($link_class = '', $before = '', $after = '', $shou_count = false)
{

    $tags_id = _pz('option_list_tags');
    $links = '';
    $tags = array();
    if ($tags_id) {
        foreach ($tags_id as $key => $value) {
            if ($value) {
                $tags[] = get_tag($key);
            }
        }
    }
    return zib_get_tags($tags, $link_class, $before, $after, 0, true);
}
function zib_get_all_tags_link($link_class = '', $before = '', $after = '', $shou_count = false)
{
    $tags = get_tags();
    $links = '';

    // return $links ;
    return zib_get_tags($tags, $link_class, $before, $after, 0, true);
}

function zib_term_box()
{
    $tags = get_tags();
}
