<?php
/**
 * Template name: Zibll-文章归档
 * Description:   A archives page
 */

get_header();

?>
<main role="main" class="container">
	<div class="main-bg theme-box radius8 main-shadow">
		<article class="box-body archives">
            <?php
            $previous_year = $year = 0;
            $previous_month = $month = 0;
            $ul_open = false;

            $myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');

            foreach($myposts as $post) :
                setup_postdata($post);

                $year = mysql2date('Y', $post->post_date);
                $month = mysql2date('n', $post->post_date);
                $day = mysql2date('j', $post->post_date);

                if($year != $previous_year || $month != $previous_month) :
                    if($ul_open == true) :
                        echo '</ul></div>';
                    endif;

                    echo '<div class="zib-widget"><h4 class="text-center title-h-center">';
                    echo the_time('Y年M'); 
                    echo '</h4>';
                    echo '<ul class="list-inline">';
                    $ul_open = true;

                endif;

                $previous_year = $year; $previous_month = $month;
            ?>
                <li class="author-set-left muted-color">
                    <time><?php the_time('j'); ?>日</time>
                     </li>
                    <li class="author-set-right">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                    <span class="muted-2-color ml6"><?php echo zib_svg('view').get_post_view_count($before = '', $after = '') ?></span>
                    <?php comments_number('', '<span class="muted-2-color ml6">'.zib_svg('comment').'1</span>','<span class="muted-2-color ml6">'.zib_svg('comment').'%</span>'); ?>
                    <?php $like = get_post_meta($post->ID, 'like', true); echo $like ? '<span class="muted-2-color ml6">'.zib_svg('like').$like.'</span>':''; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        </article>
	</div>

</main>

<?php

get_footer();