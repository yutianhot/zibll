<?php

add_action('widgets_init', 'widget_register_posts');
function widget_register_posts()
{
	register_widget('widget_ui_mian_posts');
	register_widget('widget_ui_oneline_posts');
	register_widget('widget_ui_mini_posts');
	register_widget('widget_ui_mini_tab_posts');
	register_widget('widget_ui_main_tab_posts');
}
class widget_ui_main_tab_posts extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_main_tab_posts',
			'w_name'     =>  _name('多栏目文章'),
			'classname'     => '',
			'description'       => '多栏目文章显示，可同时显示多个栏目的文章',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function widget($args, $instance)
	{
		extract($args);

		$defaults = array(
			'show_thumb' => '',
			'show_meta' => '',
			'show_number' => '',
			'type' => 'auto',
			'limit_day' => '',
			'limit' => 6,
			'tabs' => array()
		);
		$defaults['tabs'][] = array(
			'title' => '热门文章',
			'cat' => '',
			'topics' => '',
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		echo '<div class="theme-box">';
		echo '<div class="index-tab">';
		echo '<ul class="list-inline scroll-x mini-scrollbar">';
		$_i = 0;
		$nav = '';
		$con = '';
		foreach ($instance['tabs'] as $tabs) {
			if ($tabs['title']) {
				$nav_class = $_i == 0 ? 'active' : '';
				$id = $this->get_field_id('tab_') . $_i;
				echo '<li class="' . $nav_class . '" ><a data-toggle="tab" href="#' . $id . '">' . $tabs['title'] . '</a></li>';
				$_i++;
			}
		}
		echo '</ul>';
		echo '</div>';
		$list_args = array(
			'type' => $instance['type'],
		);
		$_i2 = 0;

		echo '<div class="tab-content">';
		foreach ($instance['tabs'] as $tabs) {
			if ($tabs['title']) {
				$args = array(
					'cat' => $tabs['cat'],
					'order' => 'DESC',
					'showposts' => $instance['limit'],
					'ignore_sticky_posts' => 1
				);
				$orderby = $tabs['orderby'];
				if ($orderby !== 'views' && $orderby !== 'favorite'&& $orderby !== 'like') {
					$args['orderby'] = $orderby;
				} else {
					$args['orderby'] = 'meta_value_num';
					$args['meta_query'] = array(
						array(
							'key' => $orderby,
							'order' => 'DESC'
						)
					);
				}
				if ($tabs['topics'] ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'topics',
							'terms' => preg_split("/,|，|\s|\n/", $tabs['topics'])
						)
					);
				}
				if($instance['limit_day'] > 0){
					$args['date_query'] = array(
						array(
							'after'     => date('Y-m-d H:i:s', strtotime("-".$instance['limit_day']." day")),
							'before'    => date('Y-m-d H:i:s'),
							'inclusive' => true,
						)
					);
				}
				$con_class = $_i2 == 0 ? ' active in' : '';
				$id = $this->get_field_id('tab_') . $_i2;
				echo '<div class="tab-pane fade' . $con_class . '" id="' . $id . '">';

				$the_query = new WP_Query($args);
				if ($instance['type'] == 'oneline_card') {
					$list_args['type'] = 'card';
					echo '<div data-scroll="x" class="relative"><div class="scroll-x mini-scrollbar">';
					zib_posts_list($list_args, $the_query);
					echo '</div></div>';
				} else {
					zib_posts_list($list_args, $the_query);
				}
				echo '</div>';
				$_i2++;
			}
		}
		echo '</div>';
		echo '</div>';
	}
	function form($instance)
	{
		$defaults = array(
			'type' => 'auto',
			'limit' => 6,
			'limit_day' => '',
			'tabs' => array()
		);
		$defaults['tabs'][] = array(
			'title' => '热门文章',
			'cat' => '',
			'topics' => '',
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$img_html = '';
		$img_i = 0;
		foreach ($instance['tabs'] as $category) {
			$_html_a = '<label>栏目' . ($img_i + 1) . '-标题（必填）：<input style="width:100%;" type="text" id="' . $this->get_field_id('tabs') . '[' . $img_i . '].title" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][title]" value="' . $instance['tabs'][$img_i]['title'] . '" /></label>';

			$_html_b = '<label>栏目' . ($img_i + 1) . '-分类限制：<input style="width:100%;" type="text" id="' . $this->get_field_id('tabs') . '[' . $img_i . '].cat" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][cat]" value="' . $instance['tabs'][$img_i]['cat'] . '" /></label>';
			$_html_b .= '<label>栏目' . ($img_i + 1) . '-专题：<input style="width:100%;" type="text" id="' . $this->get_field_id('tabs') . '[' . $img_i . '].topics" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][topics]" value="' . $instance['tabs'][$img_i]['topics'] . '" /></label>';

			$_html_c = '<label>栏目' . ($img_i + 1) . '-排序方式：
			<select style="width:100%;" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][orderby]">
			<option value="comment_count" ' . selected('comment_count', $instance['tabs'][$img_i]['orderby'], false) . '>评论数</option>
			<option value="views" ' . selected('views', $instance['tabs'][$img_i]['orderby'], false) . '>浏览量</option>
			<option value="like" ' . selected('like', $instance['tabs'][$img_i]['orderby'], false) . '>点赞数</option>
			<option value="favorite" ' . selected('favorite', $instance['tabs'][$img_i]['orderby'], false) . '>收藏数</option>
			<option value="date" ' . selected('date', $instance['tabs'][$img_i]['orderby'], false) . '>发布时间</option>
			<option value="modified" ' . selected('modified', $instance['tabs'][$img_i]['orderby'], false) . '>更新时间</option>
			<option value="rand" ' . selected('rand', $instance['tabs'][$img_i]['orderby'], false) . '>随机排序</option>
			</select></label>';

			$_tt = '<div class="panel"><h4 class="panel-title">栏目'.($img_i + 1).'：'.$instance['tabs'][$img_i]['title'].'</h4><div class="panel-hide panel-conter">';
			$_tt2 = '</div></div>';

			$img_html .= '<div class="widget_ui_slider_g">' .$_tt. $_html_a . $_html_b . $_html_c .$_tt2 .'</div>';
			$img_i++;
		}

		$add_b = '<button type="button" data-name="' . $this->get_field_name('tabs') . '" data-count="' . $img_i . '" class="button add_button add_lists_button">添加栏目</button>';
		$add_b .= '<button type="button" data-name="' . $this->get_field_name('tabs') . '" data-count="' . $img_i . '" class="button rem_lists_button">删除栏目</button>';
		$img_html .= $add_b;
?> <p>
			<i style="width:100%;font-size: 12px;">在一个模块中实现多栏目的文章显示。通过对栏目分类的显示和排序方式可组合成多种需求的文章显示，主题！如果要显示在全宽度页面，请确保显示模式统一，不要选择自动模式</i></br>
			<?php zib_cat_help() ?>
			<?php zib_topics_help() ?>
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit');
												?>" name="<?php echo $this->get_field_name('limit');
															?>" type="number" value="<?php echo $instance['limit'];
																						?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				限制时间（最近X天）：
				<input style="width:100%;" name="<?php echo $this->get_field_name('limit_day')?>" type="number" value="<?php echo $instance['limit_day']?>" size="24" />
			</label>
		</p>

		<p>
			<label>
				列表显示模式：
				<select style="width:100%;" id="<?php echo $this->get_field_id('type');
												?>" name="<?php echo $this->get_field_name('type');
															?>">
					<option value="auto" <?php selected('auto', $instance['type']);
											?>>默认（自动跟随主题设置)</option>
					<option value="card" <?php selected('card', $instance['type']);
											?>>卡片模式</option>
					<option value="oneline_card" <?php selected('oneline_card', $instance['type']);
													?>>单行滚动卡片模式</option>
					<option value="no_thumb" <?php selected('no_thumb', $instance['type']);
												?>>无缩略图列表</option>
					<option value="mult_thumb" <?php selected('mult_thumb', $instance['type']);
												?>>多图模式</option>
				</select>
			</label>
		</p>
		<?php echo $img_html; ?>
	<?php
	}
}

//////---多栏目文章mini---////////
class widget_ui_mini_tab_posts extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_mini_tab_posts',
			'w_name'     =>  _name('多栏目文章mini'),
			'classname'     => '',
			'description'       => '多栏目文章显示mini版，可同时显示多个栏目的文章',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function widget($args, $instance)
	{
		extract($args);

		$defaults = array(
			'title' => '',
			'in_affix' => '',
			'mini_title' => '',
			'show_thumb' => '',
			'show_meta' => '',
			'show_number' => '',
			'limit' => 6, 'limit_day' => '',
			'tabs' => array()
		);
		$defaults['tabs'][] = array(
			'title' => '热门文章',
			'cat' => '',
			'topics' => '',
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);


		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = $instance['title'];
		$class = '';

		if ($title) {
			$title = '<div class="box-body notop' . $class . '"><div class="title-theme">' . $title . $mini_title . '</div></div>';
		}

		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';
		echo '<div'.$in_affix.' class="theme-box">';
		echo $title;
		echo '<div class="box-body posts-mini-lists zib-widget">';
		echo '<ul class="list-inline scroll-x mini-scrollbar tab-nav-theme">';
		$_i = 0;
		$nav = '';
		$con = '';
		foreach ($instance['tabs'] as $tabs) {
			if ($tabs['title']) {
				$nav_class = $_i == 0 ? 'active' : '';
				$id = $this->get_field_id('tab_') . $_i;
				echo '<li class="' . $nav_class . '" ><a data-toggle="tab" href="#' . $id . '">' . $tabs['title'] . '</a></li>';
				$_i++;
			}
		}
		echo '</ul>';
		$list_args = array(
			'show_thumb' => $instance['show_thumb'] ? true : false,
			'show_meta' => $instance['show_meta'] ? true : false,
			'show_number' =>  $instance['show_number'] ? true : false,
		);
		$_i2 = 0;

		echo '<div class="tab-content">';
		foreach ($instance['tabs'] as $tabs) {
			if ($tabs['title']) {
				$args = array(
					'cat' => $tabs['cat'],
					'order' => 'DESC',
					'showposts' => $instance['limit'],
					'ignore_sticky_posts' => 1
				);
				$orderby = $tabs['orderby'];
				if ($orderby !== 'views'&&$orderby !== 'favorite'&&$orderby !== 'like') {
					$args['orderby'] = $orderby;
				} else {
					$args['orderby'] = 'meta_value_num';
					$args['meta_query'] = array(
						array(
							'key' => $orderby,
							'order' => 'DESC'
						)
					);
				}
				if ($tabs['topics'] ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'topics',
							'terms' => preg_split("/,|，|\s|\n/", $tabs['topics'])
						)
					);
				}
				if($instance['limit_day'] > 0){
					$args['date_query'] = array(
						array(
							'after'     => date('Y-m-d H:i:s', strtotime("-".$instance['limit_day']." day")),
							'before'    => date('Y-m-d H:i:s'),
							'inclusive' => true,
						)
					);
				}
				$con_class = $_i2 == 0 ? ' active in' : '';
				$id = $this->get_field_id('tab_') . $_i2;
				echo '<div class="tab-pane fade' . $con_class . '" id="' . $id . '">';
				$the_query = new WP_Query($args);
				zib_posts_mini_list($list_args, $the_query);
				echo '</div>';
				$_i2++;
			}
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'show_thumb' => '',
			'show_meta' => '',
			'show_number' => '',
			'limit' => 6, 'limit_day' => '',
			'tabs' => array()
		);
		$defaults['tabs'][] = array(
			'title' => '热门文章',
			'cat' => '',
			'topics' => '',
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$img_html = '';
		$img_i = 0;
		foreach ($instance['tabs'] as $category) {
			$_html_a = '<label>栏目' . ($img_i + 1) . '-标题（必填）：<input style="width:100%;" type="text" id="' . $this->get_field_id('tabs') . '[' . $img_i . '].title" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][title]" value="' . $instance['tabs'][$img_i]['title'] . '" /></label>';

			$_html_b = '<label>栏目' . ($img_i + 1) . '-分类限制：<input style="width:100%;" type="text" id="' . $this->get_field_id('tabs') . '[' . $img_i . '].cat" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][cat]" value="' . $instance['tabs'][$img_i]['cat'] . '" /></label>';
			$_html_b .= '<label>栏目' . ($img_i + 1) . '-专题：<input style="width:100%;" type="text" id="' . $this->get_field_id('tabs') . '[' . $img_i . '].topics" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][topics]" value="' . $instance['tabs'][$img_i]['topics'] . '" /></label>';

			$_html_c = '<label>栏目' . ($img_i + 1) . '-排序方式：
			<select style="width:100%;" name="' . $this->get_field_name('tabs') . '[' . $img_i . '][orderby]">
			<option value="comment_count" ' . selected('comment_count', $instance['tabs'][$img_i]['orderby'], false) . '>评论数</option>
			<option value="views" ' . selected('views', $instance['tabs'][$img_i]['orderby'], false) . '>浏览量</option>
			<option value="like" ' . selected('like', $instance['tabs'][$img_i]['orderby'], false) . '>点赞数</option>
			<option value="favorite" ' . selected('favorite', $instance['tabs'][$img_i]['orderby'], false) . '>收藏数</option>
			<option value="date" ' . selected('date', $instance['tabs'][$img_i]['orderby'], false) . '>发布时间</option>
			<option value="modified" ' . selected('modified', $instance['tabs'][$img_i]['orderby'], false) . '>更新时间</option>
			<option value="rand" ' . selected('rand', $instance['tabs'][$img_i]['orderby'], false) . '>随机排序</option>
		</select></label>';
		$_tt = '<div class="panel"><h4 class="panel-title">栏目'.($img_i + 1).'：'.$instance['tabs'][$img_i]['title'].'</h4><div class="panel-hide panel-conter">';
		$_tt2 = '</div></div>';

		$img_html .= '<div class="widget_ui_slider_g">' .$_tt. $_html_a . $_html_b . $_html_c .$_tt2 .'</div>';

		$img_i++;
		}

		$add_b = '<button type="button" data-name="' . $this->get_field_name('tabs') . '" data-count="' . $img_i . '" class="button add_button add_lists_button">添加栏目</button>';
		$add_b .= '<button type="button" data-name="' . $this->get_field_name('tabs') . '" data-count="' . $img_i . '" class="button rem_lists_button">删除栏目</button>';
		$img_html .= $add_b;
	?> <p>
			<i style="width:100%;">在一个模块中实现多栏目的文章显示。通过对栏目分类的显示和排序方式可组合成多种需求的文章显示</i></br>
			<?php zib_cat_help() ?>
			<?php zib_topics_help() ?>
		</p>

		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title');
												?>" name="<?php echo $this->get_field_name('title');
															?>" type="text" value="<?php echo $instance['title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				副标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('mini_title');
												?>" name="<?php echo $this->get_field_name('mini_title');
															?>" type="text" value="<?php echo $instance['mini_title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
	<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_thumb'], 'on'); ?> id="<?php echo $this->get_field_id('show_thumb'); ?>" name="<?php echo $this->get_field_name('show_thumb'); ?>">显示缩略图
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_number'], 'on'); ?> id="<?php echo $this->get_field_id('show_number'); ?>" name="<?php echo $this->get_field_name('show_number'); ?>">显示编号
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_meta'], 'on'); ?> id="<?php echo $this->get_field_id('show_meta'); ?>" name="<?php echo $this->get_field_name('show_meta'); ?>">显示作者和发布日期
			</label>
		</p>

		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit');
												?>" name="<?php echo $this->get_field_name('limit');
															?>" type="number" value="<?php echo $instance['limit'];
																						?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				限制时间（最近X天）：
				<input style="width:100%;" name="<?php echo $this->get_field_name('limit_day')?>" type="number" value="<?php echo $instance['limit_day']?>" size="24" />
			</label>
		</p>

		<?php echo $img_html; ?>
	<?php
	}
}

class widget_ui_mini_posts extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_mini_posts',
			'w_name'     =>  _name('文章mini'),
			'classname'     => '',
			'description'       => '尺寸更小的文章列表，更适合放置在侧边栏',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function widget($args, $instance)
	{
		extract($args);

		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'limit' => 6, 'limit_day' => '',
			'cat' => '',
			'topics' => '',
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$orderby = $instance['orderby'];

		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = $instance['title'];
		$class = '';

		if ($title) {
			$title = '<div class="box-body notop' . $class . '"><div class="title-theme">' . $title . $mini_title . '</div></div>';
		}

		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';
		echo '<div'.$in_affix.' class="theme-box">';
		echo $title;
		//	echo '<pre>'.json_encode($instance).'</pre>';

		$args = array(
			'cat' => $instance['cat'],
			'order' => 'DESC',
			'showposts' => $instance['limit'],
			'ignore_sticky_posts' => 1
		);

		if ($orderby !== 'views'&&$orderby !== 'favorite'&&$orderby !== 'like') {
			$args['orderby'] = $orderby;
		} else {
			$args['orderby'] = 'meta_value_num';
			$args['meta_query'] = array(
				array(
					'key' => $orderby,
					'order' => 'DESC'
				)
			);
		}
		if ($instance['topics'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'topics',
					'terms' => preg_split("/,|，|\s|\n/", $instance['topics'])
				)
			);
		}
		if($instance['limit_day'] > 0){
			$args['date_query'] = array(
				array(
					'after'     => date('Y-m-d H:i:s', strtotime("-".$instance['limit_day']." day")),
					'before'    => date('Y-m-d H:i:s'),
					'inclusive' => true,
				)
			);
		}
		$list_args = array(
			'show_thumb' => isset($instance['show_thumb']) ? true : false,
			'show_meta' => isset($instance['show_meta']) ? true : false,
			'show_number' =>  isset($instance['show_number']) ? true : false,
		);
		echo '<div class="box-body posts-mini-lists zib-widget">';
		$the_query = new WP_Query($args);
		zib_posts_mini_list($list_args, $the_query);
		echo '</div>';
		echo '</div>';
	}
	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'show_thumb' => '',
			'show_meta' => '',
			'show_number' => '',
			'limit' => 6, 'limit_day' => '',
			'topics' => '',
			'cat' => '',
			'orderby' => 'views'
		);
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p>
			尺寸更小的文章列表，推荐设置在侧边栏，如果要设置在非侧边栏位置，请打开显示缩略图
		</p>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title');
												?>" name="<?php echo $this->get_field_name('title');
															?>" type="text" value="<?php echo $instance['title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				副标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('mini_title');
												?>" name="<?php echo $this->get_field_name('mini_title');
															?>" type="text" value="<?php echo $instance['mini_title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
	<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_thumb'], 'on'); ?> id="<?php echo $this->get_field_id('show_thumb'); ?>" name="<?php echo $this->get_field_name('show_thumb'); ?>">显示缩略图
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_number'], 'on'); ?> id="<?php echo $this->get_field_id('show_number'); ?>" name="<?php echo $this->get_field_name('show_number'); ?>">显示编号
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_meta'], 'on'); ?> id="<?php echo $this->get_field_id('show_meta'); ?>" name="<?php echo $this->get_field_name('show_meta'); ?>">显示作者和发布日期
			</label>
		</p>
		<p>
			<?php zib_cat_help() ?>
			<input style="width:100%;" id="<?php echo $this->get_field_id('cat');
											?>" name="<?php echo $this->get_field_name('cat');
														?>" type="text" value="<?php echo $instance['cat'];
																				?>" size="24" />
		</p>
		<p>
			<?php zib_topics_help() ?>
			<input style="width:100%;" id="<?php echo $this->get_field_id('topics');
											?>" name="<?php echo $this->get_field_name('topics');
														?>" type="text" value="<?php echo $instance['topics'];
																				?>" size="24" />
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit');
												?>" name="<?php echo $this->get_field_name('limit');
															?>" type="number" value="<?php echo $instance['limit'];
																						?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				限制时间（最近X天）：
				<input style="width:100%;" name="<?php echo $this->get_field_name('limit_day')?>" type="number" value="<?php echo $instance['limit_day']?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				排序：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby');
												?>" name="<?php echo $this->get_field_name('orderby');
															?>">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']);
													?>>评论数</option>
					<option value="views" <?php selected('views', $instance['orderby']);
											?>>浏览量</option>
					<option value="like" <?php selected('like', $instance['orderby']);
											?>>点赞数</option>
					<option value="favorite" <?php selected('favorite', $instance['orderby']);
											?>>收藏数</option>
					<option value="date" <?php selected('date', $instance['orderby']);
											?>>发布时间</option>
					<option value="modified" <?php selected('modified', $instance['orderby']);
												?>>更新时间</option>
					<option value="rand" <?php selected('rand', $instance['orderby']);
											?>>随机排序</option>
				</select>
			</label>
		</p>
	<?php
	}
}


class widget_ui_mian_posts extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_mian_posts',
			'w_name'     =>  _name('文章列表'),
			'classname'     => '',
			'description'       => '核心的文章列表功能',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function widget($args, $instance)
	{
		extract($args);

		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'type' => 'auto',
			'limit' => 6, 'limit_day' => '',
			'cat' => '',
			'topics' => '',
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$orderby = $instance['orderby'];

		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = $instance['title'];
		$class = ' nobottom';
		if ($instance['type'] == 'card') {
			$class = '';
		}
		if ($title) {
			$title = '<div class="box-body notop' . $class . '"><div class="title-theme">' . $title . $mini_title . '</div></div>';
		}

		echo '<div class="theme-box">';
		echo $title;
		//	echo '<pre>'.json_encode($instance).'</pre>';

		$args = array(
			'cat' => $instance['cat'],
			'order' => 'DESC',
			'showposts' => $instance['limit'],
			'ignore_sticky_posts' => 1
		);

		if ($orderby !== 'views'&&$orderby !== 'favorite'&&$orderby !== 'like') {
			$args['orderby'] = $orderby;
		} else {
			$args['orderby'] = 'meta_value_num';
			$args['meta_query'] = array(
				array(
					'key' => $orderby,
					'order' => 'DESC'
				)
			);
		}
		if ($instance['topics'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'topics',
					'terms' => preg_split("/,|，|\s|\n/", $instance['topics'])
				)
			);
		}
		if($instance['limit_day'] > 0){
			$args['date_query'] = array(
				array(
					'after'     => date('Y-m-d H:i:s', strtotime("-".$instance['limit_day']." day")),
					'before'    => date('Y-m-d H:i:s'),
					'inclusive' => true,
				)
			);
		}

		$list_args = array(
			'type' => $instance['type'],
		);

		$the_query = new WP_Query($args);
		zib_posts_list($list_args, $the_query);
		echo '</div>';
	}
	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'limit' => 6, 'limit_day' => '',
			'type' => 'auto',
			'topics' => '',
			'cat' => '',
			'orderby' => 'views'
		);
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p>
			<label>
				<i style="width:100%;font-size: 12px;">核心的文章列表功能，不建议设置在侧边栏。如果要设置在全宽度位置，请确保显示模式一致，不要选择自动模式</i>
			</label>
		</p>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title');
												?>" name="<?php echo $this->get_field_name('title');
															?>" type="text" value="<?php echo $instance['title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				副标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('mini_title');
												?>" name="<?php echo $this->get_field_name('mini_title');
															?>" type="text" value="<?php echo $instance['mini_title'];
																					?>" />
			</label>
		</p>
		<p>

			<?php zib_cat_help() ?>
			<input style="width:100%;" id="<?php echo $this->get_field_id('cat');
											?>" name="<?php echo $this->get_field_name('cat');
														?>" type="text" value="<?php echo $instance['cat'];
																				?>" size="24" />
		</p>
		<p>
			<?php zib_topics_help() ?>
			<input style="width:100%;" id="<?php echo $this->get_field_id('topics');
											?>" name="<?php echo $this->get_field_name('topics');
														?>" type="text" value="<?php echo $instance['topics'];
																				?>" size="24" />
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" name="<?php echo $this->get_field_name('limit')?>" type="number" value="<?php echo $instance['limit']?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				限制时间（最近X天）：
				<input style="width:100%;" name="<?php echo $this->get_field_name('limit_day')?>" type="number" value="<?php echo $instance['limit_day']?>" size="24" />
			</label>
		</p>

		<p>
			<label>
				列表显示模式：
				<select style="width:100%;" id="<?php echo $this->get_field_id('type');
												?>" name="<?php echo $this->get_field_name('type');
															?>">
					<option value="auto" <?php selected('auto', $instance['type']);
											?>>默认（自动跟随主题设置)</option>
					<option value="card" <?php selected('card', $instance['type']);
											?>>卡片模式</option>
					<option value="no_thumb" <?php selected('no_thumb', $instance['type']);
												?>>无缩略图列表</option>
					<option value="mult_thumb" <?php selected('mult_thumb', $instance['type']);
												?>>多图模式</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				排序：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby');
												?>" name="<?php echo $this->get_field_name('orderby');
															?>">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']);
													?>>评论数</option>
					<option value="views" <?php selected('views', $instance['orderby']);
											?>>浏览量</option>
					<option value="like" <?php selected('like', $instance['orderby']);
											?>>点赞数</option>
					<option value="favorite" <?php selected('favorite', $instance['orderby']);
											?>>收藏数</option>
					<option value="date" <?php selected('date', $instance['orderby']);
											?>>发布时间</option>
					<option value="modified" <?php selected('modified', $instance['orderby']);
												?>>更新时间</option>
					<option value="rand" <?php selected('rand', $instance['orderby']);
											?>>随机排序</option>
				</select>
			</label>
		</p>
	<?php
	}
}


///////单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------
///////单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------
///////单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------
///////单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------
///////单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------//单行滚动文章板块------

class widget_ui_oneline_posts extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_oneline_posts',
			'w_name'     =>  _name('单行文章列表'),
			'classname'     => '',
			'description'       => '显示文章列表，只显示一行，自动横向滚动',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function widget($args, $instance)
	{
		extract($args);

		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'type' => 'auto',
			'limit' => 6, 'limit_day' => '',
			'topics' => '',
			'cat' => '',
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$orderby = $instance['orderby'];

		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = $instance['title'];

		if ($title) {
			$title = '<div class="box-body notop"><div class="title-theme">' . $title . $mini_title . '</div></div>';
		}

		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';
		echo '<div'.$in_affix.' class="theme-box">';
		echo $title;
		//	echo '<pre>'.json_encode($instance).'</pre>';

		$args = array(
			'cat' => $instance['cat'],
			'order' => 'DESC',
			'showposts' => $instance['limit'],
			'ignore_sticky_posts' => 1
		);

		if ($orderby !== 'views'&&$orderby !== 'favorite'&&$orderby !== 'like') {
			$args['orderby'] = $orderby;
		} else {
			$args['orderby'] = 'meta_value_num';
			$args['meta_query'] = array(
				array(
					'key' => $orderby,
					'order' => 'DESC'
				)
			);
		}
		if ($instance['topics'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'topics',
					'terms' => preg_split("/,|，|\s|\n/", $instance['topics'])
				)
			);
		}
		if($instance['limit_day'] > 0){
			$args['date_query'] = array(
				array(
					'after'     => date('Y-m-d H:i:s', strtotime("-".$instance['limit_day']." day")),
					'before'    => date('Y-m-d H:i:s'),
					'inclusive' => true,
				)
			);
		}

		$list_args = array(
			'type' => 'card',
		);

		echo '<div data-scroll="x" class="relative"><div class="scroll-x mini-scrollbar">';
		$the_query = new WP_Query($args);
		zib_posts_list($list_args, $the_query);
		echo '</div>';
		echo '</div></div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '热门文章',
			'mini_title' => '',
			'in_affix' => '',
			'limit' => 6, 'limit_day' => '',
			'type' => 'auto',
			'topics' => '',
			'cat' => '',
			'orderby' => 'views'
		);
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title');
												?>" name="<?php echo $this->get_field_name('title');
															?>" type="text" value="<?php echo $instance['title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				副标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('mini_title');
												?>" name="<?php echo $this->get_field_name('mini_title');
															?>" type="text" value="<?php echo $instance['mini_title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
	<p>
			<?php zib_cat_help() ?>
			<input style="width:100%;" id="<?php echo $this->get_field_id('cat');
											?>" name="<?php echo $this->get_field_name('cat');
														?>" type="text" value="<?php echo $instance['cat'];
																				?>" size="24" />
		</p>
		<p>
			<?php zib_topics_help() ?>
			<input style="width:100%;" id="<?php echo $this->get_field_id('topics');
											?>" name="<?php echo $this->get_field_name('topics');
														?>" type="text" value="<?php echo $instance['topics'];
																				?>" size="24" />
		</p>
				<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit');
												?>" name="<?php echo $this->get_field_name('limit');
															?>" type="number" value="<?php echo $instance['limit'];
																						?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				限制时间（最近X天）：
				<input style="width:100%;" name="<?php echo $this->get_field_name('limit_day')?>" type="number" value="<?php echo $instance['limit_day']?>" size="24" />
			</label>
		</p>

		<p>
			<label>
				排序方式：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby');
												?>" name="<?php echo $this->get_field_name('orderby');
															?>">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']);
													?>>评论数</option>
					<option value="views" <?php selected('views', $instance['orderby']);
											?>>浏览量</option>
					<option value="like" <?php selected('like', $instance['orderby']);
											?>>点赞数</option>
					<option value="favorite" <?php selected('favorite', $instance['orderby']);
											?>>收藏数</option>
					<option value="date" <?php selected('date', $instance['orderby']);
											?>>发布时间</option>
					<option value="modified" <?php selected('modified', $instance['orderby']);
												?>>更新时间</option>
					<option value="rand" <?php selected('rand', $instance['orderby']);
											?>>随机排序</option>
				</select>
			</label>
		</p>
<?php
	}
}
