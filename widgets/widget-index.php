<?php
add_action('widgets_init', 'unregister_d_widget');
function unregister_d_widget()
{
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Recent_Comments');
}
add_action('load-widgets.php', 'register_widget_jsloader');
function register_widget_jsloader()
{
	_jsloader(array('widget-set'));
	_cssloader(array('widget-set' => 'widget-set'));
}

$widgets = array(
	'more',
	'posts',
	'user',
	'slider',
);

foreach ($widgets as $widget) {
	include 'widget-' . $widget . '.php';
}

// 注册小工具
if (function_exists('register_sidebar')) {
	$sidebars = array();
	$pags = array(
		'home' => '首页',
		'single' => '文章页',
		'cat' => '分类页',
		'tag' => '标签页',
		'search' => '搜索页',
		'author' => '用户页',
	);

	$poss = array(
		'top_fluid' => '顶部全宽度',
		'top_content' => '主内容上面',
		'bottom_content' => '主内容下面',
		'bottom_fluid' => '底部全宽度',
		'sidebar' => '侧边栏',
	);
	$sidebars[] = array(
		'name'          => '所有页面-顶部全宽度',
		'id'            => 'all_top_fluid',
		'description'   => '显示在所有页面的顶部全宽度位置，由于位置较多，建议使用实时预览管理！',
	);

	$sidebars[] = array(
		'name'          => '所有页面-底部全宽度',
		'id'            => 'all_bottom_fluid',
		'description'   => '显示在所有页面的底部全宽度位置，由于位置较多，建议使用实时预览管理！',
	);

	$sidebars[] = array(
		'name'          => '所有页面-侧边栏-顶部位置',
		'id'            => 'all_sidebar_top',
		'description'   => '显示在所有侧边栏的最上面位置，由于位置较多，建议使用实时预览管理！',
	);

	$sidebars[] = array(
		'name'          => '所有页面-侧边栏-底部位置',
		'id'            => 'all_sidebar_bottom',
		'description'   => '显示在所有侧边栏的最下面，由于位置较多，建议使用实时预览管理！',
	);

	$sidebars[] = array(
		'name'          => '所有页面-页脚区内部',
		'id'            => 'all_footer',
		'description'   => '显示最底部页脚区域内部，由于位置较多，建议使用实时预览管理！',
	);
	foreach ($pags as $key => $value) {
		foreach ($poss as $poss_key => $poss_value) {

			$sidebars[] = array(
				'name'          => $value . '-' . $poss_value,
				'id'            => $key . '_' . $poss_key,
				'description'   => '显示在 ' . $value . ' 的 ' . $poss_value . ' 位置，由于位置较多，建议使用实时预览管理！',
			);
		}
	}

	$sidebars[] = array(
		'name'          => '移动端—弹出菜单底部',
		'id'            => 'mobile_nav_fluid',
		'description'   => '显示在移动端弹出的菜单内部的下方，由于宽度较小，请勿添加大尺寸模块',
	);
	foreach ($sidebars as $value) {
		register_sidebar(array(
			'name' => $value['name'],
			'id' => $value['id'],
			'description' => $value['description'],
			'before_widget' => '<div class="zib-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>'
		));
	};
}

function zib_cat_help()
{
	$table = '';
	$args = array(
		'orderby' => 'count',
		'order' => 'DESC',
		'hide_empty' => false
	);
	$cats = get_categories($args);
	foreach ($cats as $cat) {
		$table .= '<tr><td>' . $cat->cat_ID . '</td><td>' . $cat->cat_name . '</td><td>' . zib_get_cat_postcount($cat->cat_ID) . '</td></tr>';
	}
?>
	<div>
		分类限制：<a class="cat-help-button" style="font-weight:bold;color: #ff0039;text-decoration:none;background: #ffe8e8;width: 1.5em;line-height: 1.5em;text-align: center;display: inline-block;border-radius: 50%;" href="javascript:;">?</a>
		<div class="cat-help-con" style="display:none;padding: 5px;border: 1px solid rgb(221, 221, 221);margin: 5px 0;background: #f7f8f9;border-radius: 8px;font-size: 12px;">
			<p>分类限制通过分类的id进行分类筛选，可以选择某些分类或者排除某些分类。示例及id列表如下</p>
			<b>分类限制示例：</b>
			<p>
				<div>仅仅显示分类ID为"10"的文章</div>
				<div style="padding: 6px;background: #ececec">10</div>
			</p>
			<p>
				<div>显示包含分类ID为"10，11，12，13"的文章</div>
				<div style="padding: 6px;background: #ececec">10,11,12,13</div>
			</p>
			<p>
				<div>排除分类ID为"10，11，12，13"的文章</div>
				<div style="padding: 6px;background: #ececec">-10,-11,-12,-13</div>
			</p>
			<p>
				<div>排除分类ID为"10"的文章</div>
				<div style="padding: 6px;background: #ececec">-10</div>
			</p>
			<table class="table" style=" text-align: center; width: 100%;background: #f2f3f4; ">
				<caption>分类id列表</caption>
				<thead>
					<tr>
						<th>ID</th>
						<th>分类名</th>
						<th>文章数</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $table; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
}
/**
 * 专题帮助
 *
 * @param string $name
 * @return void
 */

function zib_topics_help()
{
	$table = '';
	$cats = get_terms(array(
		'taxonomy' => 'topics',
		'hide_empty' => false,
	));;
	foreach ($cats as $cat) {
		$table .= '<tr><td>' . $cat->term_id . '</td><td>' . $cat->name . '</td><td>' . zib_get_cat_postcount($cat->term_id ,'topics') . '</td></tr>';
	}
?>
	<div>
		专题选择：<a class="cat-help-button" style="font-weight:bold;color: #ff0039;text-decoration:none;background: #ffe8e8;width: 1.5em;line-height: 1.5em;text-align: center;display: inline-block;border-radius: 50%;" href="javascript:;">?</a>
		<div class="cat-help-con" style="display:none;padding: 5px;border: 1px solid rgb(221, 221, 221);margin: 5px 0;background: #f7f8f9;border-radius: 8px;font-size: 12px;">
			<p>输入专题ID，输入多个ID请用英文逗号分割</p>
			<table class="table" style=" text-align: center; width: 100%;background: #f2f3f4; ">
				<caption>专题id列表</caption>
				<thead>
					<tr>
						<th>ID</th>
						<th>专题名</th>
						<th>文章数</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $table; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
}
function zib_user_help($name = '')
{
	$table = '';
	$cats = get_users();
	foreach ($cats as $cat) {
		$table .= '<tr><td>' . $cat->ID . '</td><td>' . $cat->display_name . '</td><td>' . count_user_posts($cat->ID, 'post', true) . '</td><td>' . (int) get_user_comment_count($cat->ID) . '</td></tr>';
	}
?>
	<div>
		<?php echo $name; ?><a class="cat-help-button" style="font-weight:bold;color: #ff0039;text-decoration:none;background: #ffe8e8;width: 1.5em;line-height: 1.5em;text-align: center;display: inline-block;border-radius: 50%;" href="javascript:;">?</a>
		<div class="cat-help-con" style="display:none;padding: 5px;border: 1px solid rgb(221, 221, 221);margin: 5px 0;background: #f7f8f9;border-radius: 8px;font-size: 12px;">
			<p>输入用户ID，输入多个ID请用英文逗号分割</p>
			<table class="table" style=" text-align: center; width: 100%;background: #f2f3f4; ">
				<caption>用户id列表</caption>
				<thead>
					<tr>
						<th>ID</th>
						<th>昵称</th>
						<th>文章数</th>
						<th>评论数</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $table; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
}

function zib_widget_option($type = 'cat', $selected = '')
{
	$html = '<option value="" ' . selected('',$selected,false ) . '>未选择</option>';
	if ($type == 'cat') {
		$args = array(
			'orderby' => 'count',
			'order' => 'DESC',
			'hide_empty' => false
		);
		$options_cat = get_categories($args);
		foreach ($options_cat as $category) {
			$title = rtrim(get_category_parents($category->cat_ID, false, '>'), '>') . '[ID:' . $category->cat_ID . '][共' . $category->count . '篇]';
			$_id = $category->cat_ID;
			$html .= '<option value="'.$_id.'" ' . selected($_id,$selected,false ) . '>'.$title.'</option>';
		}
	}
	return $html;
}
