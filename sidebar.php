<?php if (!zib_is_show_sidebar() || wp_is_mobile()) return; ?>
<div class="sidebar">
	<?php
	if (function_exists('dynamic_sidebar')) {
		dynamic_sidebar('all_sidebar_top');
		if (is_home()) {
			dynamic_sidebar('home_sidebar');
		} elseif (is_category()||is_tax( 'topics' )) {
			dynamic_sidebar('cat_sidebar');
		} elseif (is_tag()) {
			dynamic_sidebar('tag_sidebar');
		} elseif (is_search()) {
			dynamic_sidebar('search_sidebar');
		} elseif (is_single()) {
			dynamic_sidebar('single_sidebar');
		}
		dynamic_sidebar('all_sidebar_bottom');
	}
	?>
</div>
