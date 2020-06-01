<?php
get_header();
?>
<main class="container">
    <?php while (have_posts()) : the_post(); ?>
    <div class="box-body theme-box radius8 main-bg main-shadow">
        <article class="article wp-posts-content">
            <?php the_content(); ?>
        </article>
        <?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
    </div>
    <?php endwhile;  ?>
    <?php comments_template('', true); ?>
</main>
<?php
get_footer();