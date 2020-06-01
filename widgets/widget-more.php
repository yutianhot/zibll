<?php

add_action('widgets_init', 'widget_register_more');
function widget_register_more()
{
	register_widget('widget_ui_yiyan');
	register_widget('widget_ui_posts_navs');
	register_widget('widget_ui_new_comment');
	register_widget('widget_ui_links_lists');
	register_widget('widget_ui_notice');
	register_widget('widget_ui_search');
}


/**
 *搜索小工具
 */
class widget_ui_search extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_search',
			'w_name'     =>  _name('搜索框'),
			'classname'     => '',
			'description'       => '显示一个搜索框，多种显示效果',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}

	function widget($args, $instance)
	{
		extract($args);
		$defaults = array(
			'title' => '搜索',
			'mini_title' => '',
			'class' => '',
			'show_keywords' => '',
			'keywords_title' => '热门搜索',
			'placeholder' => '开启精彩搜索',
			'show_input_cat' =>  '',
			'show_more_cat' => '',
			'in_cat' => '',
			'in_affix' => '',
			'more_cats' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = $instance['title'];
		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';
		echo '<div'.$in_affix.' class="theme-box">';

		if ($title) {
			$title = '<div class="box-body notop"><div class="title-theme">' . $title . $mini_title . '</div></div>';
		}

		echo $title;
		echo '<div class="zib-widget widget-search">';

        $args = array(
            'class' => '',
            'show_keywords' => $instance['show_keywords'],
			'keywords_title' => $instance['keywords_title'],
			'placeholder' => $instance['placeholder'],
			'show_input_cat' =>  $instance['show_input_cat'],
			'show_more_cat' => $instance['show_more_cat'],
			'in_cat' => $instance['in_cat'],
		);
		if( $instance['more_cats']){
			$args['more_cats'] = preg_split("/,|，|\s|\n/", $instance['more_cats']);
		}
        zib_get_search($args);

		echo '</div>';
		echo '</div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '搜索',
			'mini_title' => '',
			'class' => '',
			'show_keywords' => '',
			'keywords_title' => '热门搜索',
			'placeholder' => '开启精彩搜索',
			'show_input_cat' =>  '',
			'show_more_cat' => '',
			'in_cat' => '',
			'in_affix' => '',
			'more_cats' => '',
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
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_keywords'], 'on'); ?> id="<?php echo $this->get_field_id('show_keywords'); ?>" name="<?php echo $this->get_field_name('show_keywords'); ?>"> 显示热门搜索关键词
			</label>
		</p>
		<p>
			<label>
			热门搜索-标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('keywords_title');
												?>" name="<?php echo $this->get_field_name('keywords_title');
															?>" type="text" value="<?php echo $instance['keywords_title'];
																					?>" />
			</label>
		</p>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_input_cat'], 'on'); ?> id="<?php echo $this->get_field_id('show_input_cat'); ?>" name="<?php echo $this->get_field_name('show_input_cat'); ?>"> 显示分类
			</label>
		</p>

		<p>
			<label>
				默认已选择的分类：
				<select style="width:100%;" name="<?php echo $this->get_field_name('in_cat'); ?>">
				<?php echo zib_widget_option('cat',$instance['in_cat']); ?>
				</select>
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_more_cat'], 'on'); ?> id="<?php echo $this->get_field_id('show_more_cat'); ?>" name="<?php echo $this->get_field_name('show_more_cat'); ?>"> 显示更多分类选择框
			</label>
		</p>
		<p>
			<label>
			更多分类的ID（默认为全部分类，如需自定义则将分类的ID填入下方，多个ID用逗号隔开）：
				<input style="width:100%;" id="<?php echo $this->get_field_id('more_cats');
												?>" name="<?php echo $this->get_field_name('more_cats');
															?>" type="text" value="<?php echo $instance['more_cats'];
																					?>" />
			</label>
		</p>




	<?php
	}
}


////---------公告栏--------、、、、、、、
class widget_ui_notice extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_notice',
			'w_name'     =>  _name('滚动公告'),
			'in_affix' => '',
			'classname'     => '',
			'description'       => '可做公告栏或者其他滚动显示内容',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function form($instance)
	{
		$defaults = array(
			'blank'  => '',
			'alignment'   => '',
			'radius'   => '',
			'null'   => '',
			'in_affix' => '',
			'color'   => 'c-blue',
			'img_ids' => array(),
		);

		$defaults['img_ids'][] = array(
			'title' => '子比主题开始公测啦！正版授权，限时免费！',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$defaults['img_ids'][] = array(
			'title' => '更优雅的WordPress网站主题：子比主题！全面开启',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$img_html = '';
		$img_i = 0;

		foreach ($instance['img_ids'] as $category) {
			$_tt = '<div class="panel"><h4 class="panel-title">消息'.($img_i + 1).'：'.$instance['img_ids'][$img_i]['title'].'</h4><div class="panel-hide panel-conter">';
			$_html_a = '<label>消息' . ($img_i + 1) . '-内容（必填）：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].title" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][title]" value="' . $instance['img_ids'][$img_i]['title'] . '" /></label>';
			$_html_b = '<label>消息' . ($img_i + 1) . '-图标（填写FA图标class）：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].icon" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][icon]" value="' . $instance['img_ids'][$img_i]['icon'] . '" /></label>';
			$_html_b .= '<label>消息' . ($img_i + 1) . '-链接：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].href" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][href]" value="' . $instance['img_ids'][$img_i]['href'] . '" /></label>';

			$_tt2 = '</div></div>';
			$img_html .= '<div class="widget_ui_slider_g">' . $_tt.$_html_a . $_html_b .$_tt2 . '</div>';
			$img_i++;
		}

		$add_b = '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button add_button add_notice_button">添加栏目</button>';
		$add_b .= '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button rem_lists_button">删除栏目</button>';
		$img_html .= $add_b;
		//echo '<pre>' . json_encode($instance) . '</pre>';
?>
		<p>
			显示一个公告栏，多个消息滚动显示,请注意控制长度，否则在移动端显示不全
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['blank'], 'on'); ?> id="<?php echo $this->get_field_id('blank'); ?>" name="<?php echo $this->get_field_name('blank'); ?>"> 链接新窗口打开
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['radius'], 'on'); ?> id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>"> 两边显示为圆形
			</label>
		</p>
		<p>
			<label>
				主题色彩：
				<select style="width:100%;" name="<?php echo $this->get_field_name('color'); ?>">
					<option value="c-red" <?php selected('c-red', $instance['color']); ?>>透明红</option>
					<option value="c-yellow" <?php selected('c-yellow', $instance['color']); ?>>透明黄</option>
					<option value="c-blue" <?php selected('c-blue', $instance['color']); ?>>透明蓝</option>
					<option value="c-green" <?php selected('c-green', $instance['color']); ?>>透明绿</option>
					<option value="c-purple" <?php selected('c-purple', $instance['color']); ?>>透明紫</option>
					<option value="b-theme sbg" <?php selected('b-theme', $instance['color']); ?>>主题色</option>
					<option value="b-red sbg" <?php selected('b-red', $instance['color']); ?>>红色</option>
					<option value="b-yellow sbg" <?php selected('b-yellow', $instance['color']); ?>>黄色</option>
					<option value="b-blue sbg" <?php selected('b-blue', $instance['color']); ?>>蓝色</option>
					<option value="b-green sbg" <?php selected('b-green', $instance['color']); ?>>绿色</option>
					<option value="b-purple sbg" <?php selected('b-purple', $instance['color']); ?>>紫色</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				对齐方式：
				<select style="width:100%;" name="<?php echo $this->get_field_name('alignment'); ?>">
					<option value="" <?php selected('', $instance['alignment']); ?>>靠左</option>
					<option value="text-center" <?php selected('text-center', $instance['alignment']); ?>>居中</option>
					<option value="text-right" <?php selected('text-right', $instance['alignment']); ?>>靠右</option>
				</select>
			</label>
		</p>
		<div class="widget_ui_slider_lists">
			<?php echo $img_html; ?>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox hide" type="checkbox" <?php checked($instance['null'], 'on'); ?> id="<?php echo $this->get_field_id('null'); ?>" name="<?php echo $this->get_field_name('null'); ?>"><a class="button ok_button">应用</a>
			</label>
		</div>
		<?php wp_enqueue_media(); ?>
	<?php
	}

	function widget($args, $instance)
	{

		extract($args);

		$defaults = array(
			'blank'  => '',
			'alignment'   => '',
			'radius'   => '',
			'null'   => '',
			'in_affix' => '',
			'color'   => 'c-blue',
			'img_ids' => array(),
		);

		$defaults['img_ids'][] = array(
			'title' => '子比主题开始公测啦！正版授权，限时免费！',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$defaults['img_ids'][] = array(
			'title' => '更优雅的WordPress网站主题：子比主题！全面开启',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$links = array(
			'class'   => $instance['alignment'].' '.$instance['color'].($instance['radius']?' radius':' radius8'),
		);
		foreach ($instance['img_ids'] as $slide_img) {
			if ($slide_img['title']) {
				$slide = array(
					'title'   => $slide_img['title'],
					'href'   => $slide_img['href'],
					'blank'  => $instance['blank'],
					'icon'     => $slide_img['icon']
				);
				$links['notice'][] = $slide;
			}
		}
		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';
		echo '<div'.$in_affix.' class="theme-box">';
		zib_notice($links);
		echo '</div>';

		//echo '<pre>'.json_encode($instance).'</pre>';
	?>

	<?php
	}
}

/////链接列表-------------------------------
class widget_ui_links_lists extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_links_lists',
			'w_name'     =>  _name('链接列表'),
			'classname'     => '',
			'description'       => '快速插入链接列表，很适合做友情链接',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'show_box'   => '',
			'type'   => 'all',
			'blank'  => '',
			'go_link'  => '',
			'alignment'   => '',
			'null'   => '',
			'img_ids' => array(),
		);

		$defaults['img_ids'][] = array(
			'title' => '子比主题',
			'dec' => '更优雅的WordPress主题',
			'href'   => 'https://zibll.com',
			'link' => 'https://zibll.com/wp-content/themes/zibll/img/favicon.png'
		);

		$defaults['img_ids'][] = array(
			'title' => '',
			'dec' => '',
			'href'   => '',
			'link' => ''
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$img_html = '';
		$img_i = 0;

		foreach ($instance['img_ids'] as $category) {
			$_tt = '<div class="panel"><h4 class="panel-title">链接'.($img_i + 1).'：'.$instance['img_ids'][$img_i]['title'].'</h4><div class="panel-hide panel-conter">';
			$_html_a = '<label>链接' . ($img_i + 1) . '-名称（必填）：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].title" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][title]" value="' . $instance['img_ids'][$img_i]['title'] . '" /></label>';
			$_html_b = '<label>链接' . ($img_i + 1) . '-简介：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].dec" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][dec]" value="' . $instance['img_ids'][$img_i]['dec'] . '" /></label>';
			$_html_b .= '<label>链接' . ($img_i + 1) . '-链接（必填）：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].href" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][href]" value="' . $instance['img_ids'][$img_i]['href'] . '" /></label>';

			$_html_c = '<label>链接' . ($img_i + 1) . '-图片：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].link" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][link]" value="' . $instance['img_ids'][$img_i]['link'] . '" /></label>';

			$_html_d =  '<div class="">' . $_html_c . '<button type="button" class="button ashu_upload_button">选择图片</button><button type="button" class="button delimg_upload_button">移除图片</button><div class="widget_ui_slider_box"><img src="' . $instance['img_ids'][$img_i]['link'] . '"></div></div></div>';
			$_tt2 = '</div></div>';
			$img_html .= '<div class="widget_ui_slider_g">' . $_tt.$_html_a . $_html_b . $_html_d .$_tt2 . '';
			$img_i++;
		}

		$add_b = '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button add_button add_links_button">添加栏目</button>';
		$add_b .= '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button rem_lists_button">删除栏目</button>';
		$img_html .= $add_b;
		//echo '<pre>' . json_encode($instance) . '</pre>';
?>
		<p>
			快速插入链接列表，你可搭配是否显示链接图片、简介等，但请注意统一性
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
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_box'], 'on'); ?> id="<?php echo $this->get_field_id('show_box'); ?>" name="<?php echo $this->get_field_name('show_box'); ?>"> 显示框架盒子
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['blank'], 'on'); ?> id="<?php echo $this->get_field_id('blank'); ?>" name="<?php echo $this->get_field_name('blank'); ?>"> 链接新窗口打开
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['go_link'], 'on'); ?> id="<?php echo $this->get_field_id('go_link'); ?>" name="<?php echo $this->get_field_name('go_link'); ?>"> 链接重定向<a class="" title="将非本站链接转为本站链接，有利于SEO"> ？</a>
			</label>
		</p>

		<p>
			<label>
				对齐方式：
				<select style="width:100%;" name="<?php echo $this->get_field_name('alignment'); ?>">
					<option value="" <?php selected('', $instance['alignment']); ?>>靠左</option>
					<option value="center" <?php selected('center', $instance['alignment']); ?>>居中</option>
					<option value="right" <?php selected('right', $instance['alignment']); ?>>靠右</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				显示模式：
				<select style="width:100%;" name="<?php echo $this->get_field_name('type'); ?>">
					<option value="card" <?php selected('card', $instance['type']); ?>>图文模式</option>
					<option value="image" <?php selected('image', $instance['type']); ?>>图片模式</option>
					<option value="simple" <?php selected('simple', $instance['type']); ?>>极简模式</option>
				</select>
			</label>
		</p>
		<div class="widget_ui_slider_lists">
			<?php echo $img_html; ?>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox hide" type="checkbox" <?php checked($instance['null'], 'on'); ?> id="<?php echo $this->get_field_id('null'); ?>" name="<?php echo $this->get_field_name('null'); ?>"><a class="button ok_button">应用</a>
			</label>
		</div>
		<?php wp_enqueue_media(); ?>
	<?php
	}

	function widget($args, $instance)
	{

		extract($args);

		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'alignment'   => '',
			'show_box'   => '',
			'in_affix' => '',
			'type'   => 'card',
			'blank'  => '',
			'img_ids' => array(),

		);

		$defaults['img_ids'][] = array(
			'title' => '子比主题',
			'dec' => '更优雅的WordPress主题',
			'href'   => 'https://zibll.com/',
			'link' => 'https://zibll.com/wp-content/themes/zibll/img/favicon.png'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = $instance['title'];

		if ($title) {
			$title = '<div class="box-body notop"><div class="title-theme">' . $title . $mini_title . '</div></div>';
		}
		$links = array();
		foreach ($instance['img_ids'] as $slide_img) {
			if ($slide_img['href'] && $slide_img['title']) {
				$slide = array(
					'title'   => $slide_img['title'],
					'href'   => $slide_img['href'],
					'src'  => $slide_img['link'],
					'blank'  => $instance['blank'],
					'go_link'  => !empty($instance['go_link'])?true:'',
					'desc'     => $slide_img['dec']
				);
				$links[] = $slide;
			}
		}
		$class = $instance['alignment'] ? ' text-'.$instance['alignment']:'';

		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';
		echo '<div'.$in_affix.' class="theme-box'.$class.'">';

		echo $title;

		if ($instance['show_box']) {
			echo '<div class="links-lists zib-widget">';
		}
		zib_links_box($links, $instance['type']);
		if ($instance['show_box']) {
			echo '</div>';
		}
		echo '</div>';

		//echo '<pre>'.json_encode($instance).'</pre>';
	?>

	<?php
	}
}

class widget_ui_new_comment extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_new_comment',
			'w_name'     =>  _name('最近评论'),
			'classname'     => '',
			'description'       => '显示网友最新的评论，建议显示在侧边栏',
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
			'limit' => 8,
			'outer' => '1',
			'outpost' => ''
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
		echo '<div class="box-body comment-mini-lists zib-widget">';
		zib_widget_comments($instance['limit'], $instance['outpost'], $instance['outer']);
		echo '</div>';
		echo '</div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'in_affix' => '',
			'mini_title' => '',
			'limit' => 8,
			'outer' => '1'
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
			<label>
				显示数目：
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
			</label>
		</p>
		<p>
			<?php zib_user_help('排除某用户ID') ?>
			<label>
				<input class="widefat" id="<?php echo $this->get_field_id('outer'); ?>" name="<?php echo $this->get_field_name('outer'); ?>" type="text" value="<?php echo $instance['outer']; ?>" />
			</label>
		</p>
		<p>
			<label>
				排除某文章ID：
				<input class="widefat" id="<?php echo $this->get_field_id('outpost'); ?>" name="<?php echo $this->get_field_name('outpost'); ?>" type="number" value="<?php echo $instance['outpost']; ?>" />
			</label>
		</p>
	<?php
	}
}

class widget_ui_posts_navs extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_posts_navs',
			'w_name'     =>  _name('文章目录树'),
			'classname'     => '',
			'description'       => '显示文章的目录树，非文章页则不显示内容',
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
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = esc_html($instance['title']) . esc_html($mini_title);
		if ($title) {
			$title = ' data-title="' . $title . '"';
		}
		$in_affix = $instance['in_affix'] ? ' data-affix="true"':'';

		echo '<div'.$in_affix.' class="posts-nav-box"' . $title . '></div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '文章目录',
			'in_affix' => '',
			'mini_title' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);

	?>
		<p>
			<label>
				<i style="width:100%;color:#f80;">显示文章的目录，添加在非文章页则不会显示任何内容。在实时预览添加此模块时，请注意查看是否在文章页</i>
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
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
	<?php
	}
}


/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
class widget_ui_yiyan extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_yiyan',
			'w_name'     =>  _name('一言'),
			'classname'     => 'yiyan-box main-bg theme-box text-center box-body radius8 main-shadow',
			'description'       => '这是一个显示一言的小工具，每次页面刷新或者每隔30秒会自动更新内容',
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
		);

		$instance = wp_parse_args((array) $instance, $defaults);
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
		echo '<div class="yiyan-box main-bg text-center box-body radius8 main-shadow">';
		echo '<div class="yiyan"></div>';
		echo '</div>';
		echo '</div>';
	}
	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
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
<?php
	}
}
