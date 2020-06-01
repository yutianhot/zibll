<?php get_header(); ?>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('all_top_fluid');
	dynamic_sidebar('home_top_fluid');
	echo '</div>';
}
?>
<main role="main" class="container">
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if ($paged == 1 && _pz('index_slide_s') && _pz('index_slide_position', 'top') == 'top' && _pz('index_slide_src_1')) {
		zib_index_slide();
	}
	?>

	<div class="content-wrap">
		<div class="content-layout">
			<?php
			if ($paged == 1 && _pz('index_slide_s') && _pz('index_slide_position') == 'left' && _pz('index_slide_src_1')) {
				zib_index_slide();
			}
			if ($paged == 1 && _pz('topic_kg') && !wp_is_mobile() || $paged == 1 && _pz('topic_kg') && !_pz('topic_sjd') && wp_is_mobile()) {
				zib_get_topic();
			}
			if (function_exists('dynamic_sidebar')) {
				dynamic_sidebar('home_top_content');
			}
			?>

			<div class="index-tab">
				<ul class="scroll-x mini-scrollbar">
					<li class="active"><a data-toggle="tab" href="#index-tab-1"><?php echo _pz('index_list_title') ? _pz('index_list_title') : '最新发布' ?></a></li>
					<?php
					$pagedtext = '';
					if ($paged > 1) {
						$pagedtext = ' <li>第' . $paged . '页</li>';
					}
					echo $pagedtext;
					?>
					<?php if ($paged == 1) {
						zib_index_tab($nav = 'nav');
					}
					?>
				</ul>
			</div>

			<div class="tab-content">
				<div class="ajaxpager tab-pane fade in active" id="index-tab-1">
					<?php

					get_template_part('template/excerpt');
					zib_paging();

					?>
				</div>
				<?php if ($paged == 1) {
					zib_index_tab('content');
				}
				?>
			</div>
			<?php if (function_exists('dynamic_sidebar')) {
				dynamic_sidebar('home_bottom_content');
			}
			?>
		</div>
	</div>
	<?php get_sidebar(); ?>
</main>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('home_bottom_fluid');
	dynamic_sidebar('all_bottom_fluid');
	echo '</div>';
}
?>

<?php get_footer();
