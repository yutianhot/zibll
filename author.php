<?php
get_header();
global $wp_query;
$curauth = $wp_query->get_queried_object();
?>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('all_top_fluid');
	dynamic_sidebar('author_top_fluid');
	echo '</div>';
}
?>
<main class="container">
	<div class="content-wrap">
		<div class="content-layout">
			<?php if (function_exists('dynamic_sidebar')) {
				dynamic_sidebar('author_top_content');
			}
			?>
			<?php
			zib_author_header();
			zib_author_content();

			$args = array(
				'no_margin' => true,
				'no_author' => true,
			);
		//	zib_posts_list($args);
		//	zib_paging();

			?>
			<?php if (function_exists('dynamic_sidebar')) {
				dynamic_sidebar('author_bottom_content');
			}
			?>
		</div>
	</div>
	<?php get_sidebar() ?>
</main>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('author_bottom_fluid');
	dynamic_sidebar('all_bottom_fluid');
	echo '</div>';
}
?>
<?php 
    global $wp_query;
    $curauth = $wp_query->get_queried_object();
	$user_id = $curauth->ID;
zib_rewards_modal($user_id); ?>
<?php get_footer(); ?>