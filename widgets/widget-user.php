<?php

add_action('widgets_init', 'widget_register_user');
function widget_register_user()
{
	register_widget('widget_ui_avatar');
	register_widget('widget_ui_user');
	register_widget('widget_ui_user_lists');
}



/////用户列表-----

class widget_ui_user_lists extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_user_lists',
			'w_name'     =>  _name('用户列表'),
			'classname'     => '',
			'description'       => '显示网站注册用户列表，多种排序方式。',
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
			'include' => '',
			'exclude' => '',
			'number' => 8,
			'orderby' => 'user_registered',
			'order' => 'DESC'
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
		echo $title;

		echo '<div class="text-center user_lists zib-widget">';


        $users_args = array(
            'order' => $instance['order'],
            'orderby' => $instance['orderby'],
            'number' => $instance['number'],
            'orderby' => 'views'
		);

		if($instance['include']){
			$users_args['include'] = preg_split("/,|，|\s|\n/", $instance['include']);
		}
		if($instance['exclude']){
			$users_args['exclude'] = preg_split("/,|，|\s|\n/", $instance['exclude']);
		}
		if($instance['orderby'] == 'display_name'||$instance['orderby'] == 'post_count'||$instance['orderby'] == 'user_registered'){
			$users_args['orderby'] = $instance['orderby'];
		}else{
			$users_args['orderby'] = 'meta_value';
			$users_args['meta_query'] = array(
				array(
					'key' => $instance['orderby'],
					'order' => $instance['order']
				)
			);
		}


		zib_author_card_lists('',$users_args);

		echo '</div>';
		echo '</div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'include' => '',
			'exclude' => '',
			'number' => 8,
			'orderby' => 'user_registered',
			'order' => 'DESC'
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
				<input style="width:100%;" id="<?php echo $this->get_field_id('number');
												?>" name="<?php echo $this->get_field_name('number');
							?>" type="number" value="<?php echo $instance['number'];
											?>" size="24" />
			</label>
		</p>
		<p>
		<?php zib_user_help('包含的用户ID：')?>
			<label>
				<input style="width:100%;" id="<?php echo $this->get_field_id('include');
												?>" name="<?php echo $this->get_field_name('include');
							?>" type="text" value="<?php echo $instance['include'];
										?>" />
			</label>
		</p>
		<p>
		<?php zib_user_help('排除的用户ID：')?>
			<label>
				<input style="width:100%;" id="<?php echo $this->get_field_id('exclude');
												?>" name="<?php echo $this->get_field_name('exclude');
							?>" type="text" value="<?php echo $instance['exclude'];
										?>" />
			</label>
		</p>

		<p>
			<label>
				排序方式：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby');
												?>" name="<?php echo $this->get_field_name('orderby');
								?>">
					<option value="display_name" <?php selected('display_name', $instance['orderby']);
													?>>呢称</option>
					<option value="user_registered" <?php selected('user_registered', $instance['orderby']);
											?>>注册时间</option>
					<option value="post_count" <?php selected('post_count', $instance['orderby']);
											?>>文章数量</option>
					<option value="last_login" <?php selected('last_login', $instance['orderby']);
												?>>最后登录时间</option>
					<option value="followed-user-count" <?php selected('followed-user-count', $instance['orderby']);
												?>>粉丝数</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				排序顺序：
				<select style="width:100%;" id="<?php echo $this->get_field_id('order');
												?>" name="<?php echo $this->get_field_name('order');
								?>">
					<option value="ASC" <?php selected('ASC', $instance['order']);
													?>>升序</option>
					<option value="DESC" <?php selected('DESC', $instance['order']);
											?>>降序</option>
				</select>
			</label>
		</p>

	<?php
	}
}
/////用户信息---//用户信息---//用户信息---//用户信息---//用户信息------

class widget_ui_user extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_user',
			'w_name'     =>  _name('个人信息模块'),
			'classname'     => '',
			'description'       => '未登录时候显示登录注册按钮，登录后显示用户的个人信息',
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
			'loged_title' => 'Hi！请登录',
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
		echo $title;
		zib_posts_user_box($instance['loged_title']);
		echo '</div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'loged_title' => 'HI！请登录',
		);

		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p>
			<i style="width:100%;color:#f60;">此模块与作者信息模块较为相似，如果站点开启了个人中心功能建议优先添加此模块到所有侧边栏顶部</i>
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
				登录文案：
				<input style="width:100%;" id="<?php echo $this->get_field_id('loged_title');
												?>" name="<?php echo $this->get_field_name('loged_title');
							?>" type="text" value="<?php echo $instance['loged_title'];
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
/////文章作者信息----///文章作者信息----///文章作者信息----///文章作者信息----///文章作者信息------
/////文章作者信息----///文章作者信息----///文章作者信息----///文章作者信息----///文章作者信息------

class widget_ui_avatar extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_avatar',
			'w_name'     =>  _name('文章作者信息'),
			'classname'     => '',
			'description'       => '显示当前文章的作者信息',
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
			'show_info' => true,
			'show_posts' => '',
			'show_img_bg' => '',
			'show_img' => '',
			'limit' => 6,
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$args = array(
			'show_info' => $instance['show_info'],
			'show_posts' => $instance['show_posts'],
			'show_img_bg' => $instance['show_img_bg'],
			'show_img' => $instance['show_img'],
			'limit' => $instance['limit'],
			'orderby' => $instance['orderby'],
		);

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
		// echo '<pre>'.json_encode($instance).'</pre>';

		zib_posts_avatar_box($args);
		echo '</div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'show_info' => 'on',
			'show_posts' => 'on',
			'show_img_bg' => 'on',
			'show_img' => 'on',
			'limit' => 6,
			'orderby' => 'views'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
	?>
		<p>
			<i style="width:100%;color:#f60;font-size: 12px;">显示当前文章的作者信息，此模块与个人信息模块较为相似，且文章页文章内容下方也有作者信息模块可选择显示，建议不优先添加此模块，如添加在其他页面则显示最后一篇文章的作者信息(建议只添加在文章页面)</i>
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
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_img_bg'], 'on');
																										?> id="<?php echo $this->get_field_id('show_img_bg');
						?>" name="<?php echo $this->get_field_name('show_img_bg');
							?>"> 显示作者封面
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_posts'], 'on');
																										?> id="<?php echo $this->get_field_id('show_posts');
						?>" name="<?php echo $this->get_field_name('show_posts');
							?>"> 显示文章
			</label>
		</p>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_img'], 'on');
																										?> id="<?php echo $this->get_field_id('show_img');
						?>" name="<?php echo $this->get_field_name('show_img');
							?>">文章显示为图片模式
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
				排序方式：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby');
												?>" name="<?php echo $this->get_field_name('orderby');
								?>">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']);
													?>>评论数</option>
					<option value="views" <?php selected('views', $instance['orderby']);
											?>>浏览量</option>
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
