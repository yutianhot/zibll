<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="robots" content="noindex,nofollow">
</head>

<body>
    <main class="container" style="display: none;">
        <?php
        require dirname(__FILE__) . '/../../../../wp-load.php';
        $type = $_GET['type'];
        (int) $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
        (int) $user_id = isset($_GET['id']) ? $_GET['id'] : 0;

        function zib_auther_orderby_comment($orderby = 'data')
        {
            $type = $_GET['type'];
            $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
            $user_id = isset($_GET['id']) ? $_GET['id'] : 0;
            $is_next = false;
            if (!$user_id) return;
            echo '<div class="ajaxpager">';

            $args = array(
                'user_id' => $user_id,
                'number' => 10,
                'offset' => ($paged - 1) * 10
            );
            $comments = get_comments($args);
            $count = get_user_comment_count($user_id);
            if (!$count && $paged == 1) {
                echo '<div class="ajaxpager">';
                echo '<div class="ajax-item text-center">
                        <p class="em09 muted-3-color separator" style="line-height:160px">暂无评论</p>
                    </div>';
                echo '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
                echo '</div>';
            }
            foreach ($comments as $comment) {
                echo '<div class="ajax-item posts-item no_margin">';
                zib_comments_author_list($comment);
                echo '</div>';
            }
            if ($count > ($paged * 10)) {
                $nex = _pz("ajax_trigger", '加载更多');
                $ajax_url = get_stylesheet_directory_uri() . '/action/author-content.php';
                $nex_a = '<a href="' . $ajax_url . '?type=' . $type . '&id=' . $user_id . '&paged=' . ($paged + 1) . '">' . $nex . '</a>';
                echo '<div class="text-center theme-pagination ajax-pag"><div class="next-page ajax-next">' . $nex_a . '</div></div>';
            }
            echo '</div>';
        }

        function zib_post_count( $poststatus ) {
            global $wpdb;
            $cuid = isset($_GET['id']) ? $_GET['id'] : 0;
            $cuid = esc_sql($cuid);
            if( $poststatus == 'all' ){
                $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM $wpdb->posts WHERE post_author=%d AND post_type='post'", $cuid));
            }else{
                $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM $wpdb->posts WHERE post_author=%d AND post_type=%s", $cuid,$poststatus));
            }
            return (int)$count;
        }

        ///////////------文章获取函数-------------//////////////////
        function zib_auther_orderby_posts($orderby = 'data')
        {
            $type = $_GET['type'];
            $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
            $user_id = isset($_GET['id']) ? $_GET['id'] : 0;
            if (!$user_id) return;
            $max_page = (int) count_user_posts($user_id);
            $pagenums = (int) get_option('posts_per_page');
            $is_next = ($max_page / ($pagenums * $paged));

            echo '$is_next:' . json_encode($is_next);
            echo '<div class="ajaxpager">';

            $args = array(
                'no_margin' => true,
                'no_author' => false,
            );

            $post_args = array(
                'ignore_sticky_posts' => 1,
                'order' => 'DESC',
                'author' => $user_id,
                'paged' => $paged,
            );
            if ($orderby !== 'views') {
                $post_args['orderby'] = $orderby;
            } else {
                $post_args['orderby'] = 'meta_value_num';
                $post_args['meta_query'] = array(
                    array(
                        'key' => 'views',
                        'order' => 'DESC'
                    )
                );
            }
            if ($type == 'author-tab-favorite-posts') {
                $favorite_ids = get_user_meta($user_id, 'favorite-posts', true);
                if ($favorite_ids) {
                    $favorite_ids = unserialize($favorite_ids);
                } else {
                    echo '<div class="ajax-item text-center">
                                <p class="em09 muted-3-color separator" style="line-height:160px">暂无收藏内容</p>
                            </div>';
                    echo '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
                }

                $post_args['orderby'] = 'data';
                $post_args['author'] = 0;
                $post_args['paged'] = 0;
                $post_args['post__in'] = $favorite_ids;

                $f_count = count($favorite_ids);
                if ($f_count > $pagenums) {
                    $favorite_ids = array_chunk($favorite_ids, $pagenums);
                    $post__in = isset($favorite_ids[$paged - 1]) ? $favorite_ids[$paged - 1] : 0;
                    $post_args['post__in'] = $post__in;
                }
                if (!isset($favorite_ids[$paged - 1])) {
                    return;
                }
                $is_next = isset($favorite_ids[$paged]) ? 2 : 0;
            }

            if ($type == 'author-tab-posts-orderby-pending') {
                $post_args['post_status'] = 'draft';
                $is_next = (zib_post_count( 'draft')/ ($pagenums * $paged));
            }
            if ($type == 'author-tab-posts-orderby-trash') {
                $post_args['post_status'] = 'trash';
                $is_next = (zib_post_count( 'trash')/ ($pagenums * $paged));
            }
            $the_query = new WP_Query($post_args);
            zib_posts_list($args, $the_query);
            if ($is_next > 1) {
                $nex = _pz("ajax_trigger", '加载更多');
                $ajax_url = get_stylesheet_directory_uri() . '/action/author-content.php';
                $nex_a = '<a href="' . $ajax_url . '?type=' . $type . '&id=' . $user_id . '&paged=' . ($paged + 1) . '">' . $nex . '</a>';
                echo '<div class="text-center theme-pagination ajax-pag"><div class="next-page ajax-next">' . $nex_a . '</div></div>';
            }
            echo '</div>';
        }

        switch ($type) {
            case 'author-pay-order':
                $user_id = get_current_user_id();
                if (!$user_id) return;
                echo zibpay_get_user_order($user_id,$paged);
            break;
            case 'author-tab-posts-orderby-modified':
                zib_auther_orderby_posts('modified');
                break;
            case 'author-tab-posts-orderby-pending':
                zib_auther_orderby_posts();
                break;
            case 'author-tab-posts-orderby-trash':
                zib_auther_orderby_posts();
                break;
            case 'author-tab-posts-orderby-comment_count':
                zib_auther_orderby_posts('comment_count');
                break;
            case 'author-tab-posts-orderby-views':
                zib_auther_orderby_posts('views');
                break;
            case 'author-tab-favorite-posts':
                zib_auther_orderby_posts();
                break;
            case 'author-tab-comment':
                zib_auther_orderby_comment();
                break;
            case 'author-tab-follow':
                if (!$user_id) return;
                echo '<div class="ajaxpager">';
                $follow = get_user_meta($user_id, 'follow-user', true);
                $followed = get_user_meta($user_id, 'followed-user', true);
                $follow_count = '0';
                $followed_count = '0';
                if ($follow) {
                    $follow = unserialize($follow);
                    $follow_count = count($follow);
                }

                if ($followed) {
                    $followed = unserialize($followed);
                    $followed_count = count($followed);
                }

                echo '<div class="ajax-item text-center">';
                echo '<ul class="list-inline splitters relative">';
                echo '<li class="active">
                                <a data-toggle="tab" class="muted-color" href="#ajaxauthor-tab-follow">关注 ' . $follow_count . '</a></li>';
                echo '<li><a data-toggle="tab" class="muted-color" href="#ajaxauthor-tab-followed">粉丝 ' . $followed_count . '</a></li>';
                echo '</ul>';

                echo '<div class="tab-content box-body">';
                echo '<div class="tab-pane fade in active" id="ajaxauthor-tab-follow">';
                if ($follow) {
                    foreach ($follow as $user_id) {
                        zib_author_card($user_id);
                    }
                } else {
                    echo '<p class="em09 muted-3-color separator" style="line-height:90px">暂无关注用户</p>';
                }
                echo '</div>';
                echo '<div class="tab-pane fade" id="ajaxauthor-tab-followed">';
                if ($followed) {
                    foreach ($followed as $user_id) {
                        zib_author_card($user_id);
                    }
                } else {
                    echo '<p class="em09 muted-3-color separator" style="line-height:90px">暂无粉丝</p>';
                }
                echo '</div>';
                echo '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
                echo '</div>';
                echo '</div>';
                break;
            case 'author-tab-user-data':
                if (!$user_id) return;

                echo '<div class="ajaxpager">';
                echo '<div class="ajax-item box-body notop">';

                zib_author_datas($user_id);

                echo '</div>';
                echo '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
                echo '</div>';
                echo '</div>';
                break;
            case 'author-tab-user-data-set':
                if (!$user_id) return;

                echo '<div class="ajaxpager">';
                echo '<div class="ajax-item">';

                zib_author_set($user_id);

                echo '</div>';
                echo '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
                echo '</div>';
                echo '</div>';
                break;
        }
        ?>
    </main>
</body>

</html>