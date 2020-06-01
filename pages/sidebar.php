<?php

/**
 * Template name: Zibll-带侧边栏模块
 * Description:   A no sidebar page
 */

get_header();
?>
<main class="container">
	<div class="content-wrap">
		<div class="content-layout">
			<?php while (have_posts()) : the_post(); ?>
				<div class="box-body theme-box radius8 main-bg main-shadow">
					<article class="article wp-posts-content">
						<?php the_content(); ?>
					</article>
					<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>

				<?php endwhile;  ?>
				<?php comments_template('', true); ?>
				</div>
		</div>

	</div>
	<?php get_sidebar(); ?>
</main>
<?php
get_footer();
