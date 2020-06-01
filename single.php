<?php
if(zib_is_docs_mode()){
    get_template_part('template/single-dosc');
    return;
}
get_header(); ?>
<?php if (function_exists('dynamic_sidebar')) {
    echo '<div class="container fluid-widget">';
	dynamic_sidebar('all_top_fluid');
    dynamic_sidebar('single_top_fluid');
    echo '</div>';
}
?>
<main role="main" class="container">
    <div class="content-wrap">
        <div class="content-layout">
            <?php if (function_exists('dynamic_sidebar')) {
                dynamic_sidebar('single_top_content');
            }
            ?>
            <?php while (have_posts()) : the_post();
                $user_id = get_the_author_meta('ID');
            ?>
                <?php zib_single_cover() ?>
                <article class="article main-bg theme-box box-body radius8 main-shadow">
                    <?php zib_single_header() ?>
                    <?php zib_single_content() ?>
                    <?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
                </article>
            <?php endwhile; ?>

            <?php if (_pz('yiyan_single_box')) {
                zib_yiyan($class = 'yiyan-box main-bg theme-box text-center box-body radius8 main-shadow');
            } ?>

            <?php if (_pz('post_authordesc_s')) {
                $args = array(
                    'user_id' => $user_id,
                    'show_button' => false
                );
                zib_posts_avatar_box($args);
            } ?>

            <?php if (_pz('post_prevnext_s')) {
                zib_posts_prevnext();
            } ?>

            <?php if (_pz('post_related_s')) {
                zib_posts_related(_pz('related_title'), _pz('post_related_n'));
            } ?>

            <?php if (comments_open()) {
                comments_template('', true);
            } ?>
            <?php if (function_exists('dynamic_sidebar')) {
                dynamic_sidebar('single_bottom_content');
            }
            ?>
        </div>
    </div>
    <?php get_sidebar();?>
</main>
<?php if (function_exists('dynamic_sidebar')) {
    echo '<div class="container fluid-widget">';
    dynamic_sidebar('single_bottom_fluid');
	dynamic_sidebar('all_bottom_fluid');
    echo '</div>';
}
?>
<?php zib_rewards_modal($user_id); ?>
<?php get_footer();
