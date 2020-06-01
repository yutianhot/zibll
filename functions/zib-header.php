<?php
function zib_header()
{
	$layout = _pz('header_layout', '2');
	$m_nav_align = _pz('mobile_navbar_align', 'right');
	$m_layout = _pz('mobile_header_layout', 'center');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$show_slide = false;
	if (is_home() && $paged == 1 && _pz('index_slide_s') && _pz('index_slide_position', 'top') == 'header' && _pz('index_slide_src_1')) {
		$show_slide = true;
	}
?>
	<header class="header header-layout-<?php echo $layout;
										echo $show_slide ? ' show-slide' : ''; ?>">
		<nav class="navbar navbar-top <?php echo $m_layout; ?>">
			<div class="container-fluid container-header">
				<?php zib_navbar_header(); ?>
				<div class="collapse navbar-collapse">
					<?php
					if (!wp_is_mobile()) {
						if ($layout != 3) {
							zib_menu_items();
						}
						if ($layout == 2) {
							echo zib_get_menu_search();
						}
						zib_menu_button($layout);
						if ($layout == 3) {
							echo '<div class="navbar-right">';
							zib_menu_items();
							echo '</div>';
						}
					}
					?>
				</div>

			</div>
		</nav>


	</header>

	<?php
	if (wp_is_mobile() || $layout != 2) {
		zib_header_search();
	}
	?>
	<div class="mobile-header">
		<nav <?php echo $m_nav_align != 'top' ? 'nav-touch="' . $m_nav_align . '"' : ''; ?> class="mobile-navbar visible-xs-block scroll-y mini-scrollbar <?php echo $m_nav_align; ?>">
			<?php zib_nav_mobile();
			if (function_exists('dynamic_sidebar')) {
				echo '<div class="mobile-nav-widget">';
				dynamic_sidebar('mobile_nav_fluid');
				echo '</div>';
			}
			?>
		</nav>
		<div class="fixed-body" data-close=".mobile-navbar"></div>
	</div>
	<?php if ($show_slide) {
		zib_index_slide();
	} ?>
<?php }
function zib_menu_button($layout = 1)
{
	$sub = '';
	$li = '';
	$new_p = '';
	$is_logged = false;
	$new_b = zib_get_write_posts_button('but jb-blue');
	if (_pz('nav_newposts') && !is_page_template('pages/newposts.php')) {
		$new_p .= '<div class="navbar-form navbar-right newposts">' . $new_b . '</div>';
	}

	$new_p .= _pz('theme_mode_button', true) ? '<div class="navbar-form navbar-right">
	<a href="javascript:;" class="toggle-theme toggle-radius">' . zib_svg('theme') . '</a>
	</div>' : '';

	if ($layout != 2) {
		$sub = '
		<li><a href="javascript:;" class="signin-loader">' . zib_svg('user', '0 0 1024 1024', 'icon mr10') . '登录</a></li>
		<li><a href="javascript:;" class="signup-loader">' . zib_svg('signup', '0 0 1024 1024', 'icon mr10') . '注册</a></li>
		<li><a target="_blank" href="' . zib_get_permalink(_pz('user_rp')) . '">' . zib_svg('user_rp', '0 0 1024 1024', 'icon mr10') . '找回密码</a></li>
		';
	}

	if (is_user_logged_in()) {
		$is_logged = true;
		global $current_user;
		$img = zib_get_data_avatar($current_user->ID);
		$sub = '
		<li><a href="' . get_author_posts_url($current_user->ID) . '">' . zib_svg('user', '50 0 924 924', 'icon mr10') . '个人中心</a></li>
		<li><a href="" data-toggle="modal" data-target="#modal_signout">' . zib_svg('signout', '0 0 1024 1024', 'icon mr10') . '退出登录</a></li>
		';
		if (is_super_admin()) {
			$sub .= '
			<li><a target="_blank" href="' . of_get_menuurl() . '">' . zib_svg('theme', '0 0 1024 1024', 'icon mr10') . '主题设置</a></li>
			<li><a target="_blank" href="' . zib_get_customize_widgets_url() . '"><i class="fa fa-pie-chart mr10"></i>模块配置</a></li>
			<li><a target="_blank" href="' . admin_url() . '">' . zib_svg('set', '0 0 1024 1024', 'icon mr10') . '后台管理</a></li>
			';
		}
	}

	$b_b = '
	<div class="navbar-form navbar-right">
		<ul class="list-inline splitters relative">
			<li><a href="javascript:;" class="btn' . ($is_logged ? '' : ' signin-loader') . '">' . zib_svg('user', '50 0 924 924') . '</a>
				<ul class="sub-menu">
				' . $sub . '
				</ul>
			</li><li class="relative">
				<a href="javascript:;" data-toggle-class data-target=".navbar-search" class="btn nav-search-btn">' . zib_svg('search') . '</a>
			</li>
		</ul>
	</div>';

	if ($layout == 2) {
		$_a = '<li><a href="javascript:;" class="signin-loader">登录</a></li><li><a href="javascript:;" class="signup-loader">注册</a></li>';
		if ($is_logged) {
			$_a = '<li><a href="javascript:;" class="navbar-avatar">' . $img . '</a>
					<ul class="sub-menu">' . $sub . '</ul></li>';
		}
		$b_b = '
		<div class="navbar-right' . ($is_logged ? '' : ' navbar-text') . '">
			<ul class="list-inline splitters relative">
			' . $_a . '
			</ul>
		</div>
		';
	}

	if ($layout == 3) {
		$html = $b_b . $new_p;
	} else {
		$html = $new_p . $b_b;
	}


	echo $html;
}


function zib_header_search()
{
	$more_cats = array();
	$more_cats_obj = _pz('header_search_more_cat_obj');
	if(empty($more_cats_obj['all']) && $more_cats_obj){
        foreach ($more_cats_obj as $key => $value) {
            if ($value) $more_cats[] = $key;
        }
	}

	$args = array(
        'class' => '',
        'show_keywords' => _pz('header_search_popular_key',true),
        'show_input_cat' => _pz('header_search_cat',true),
        'show_more_cat' => _pz('header_search_more_cat',true),
        'in_cat' => _pz('header_search_cat_in'),
        'more_cats' => $more_cats,
    );
	echo '<div class="fixed-body main-bg box-body navbar-search">';
	echo '<div class="theme-box"><button class="close" data-toggle-class data-target=".navbar-search" ><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></button></div>';
	echo '<div class="box-body">';
	zib_get_search($args);
	echo '</div>';
	echo '</div>';
}


function zib_get_menu_search()
{
	$html = '
      <form method="get" class="navbar-form navbar-left" action="' . esc_url(home_url('/')) . '">
        <div class="form-group relative">
          	<input type="text" class="form-control search-input" name="s" placeholder="搜索内容">
			   <div class="abs-right muted-3-color"><button type="submit" tabindex="3" class="null">' . zib_svg('search') . '</button></div>
		</div>
      </form>';
	return $html;
}

function zib_menu_items($location = 'topmenu')
{
	$args = array(
		'container'       => false,
		'container_class' => 'nav navbar-nav',
		'echo'            => false,
		'fallback_cb'     => false,
		'items_wrap'      => '<ul class="nav navbar-nav">%3$s</ul>',
		'theme_location'  => $location,
	);
	if (!wp_is_mobile()) {
		$args['depth'] = 0;
	}
	$menu = wp_nav_menu($args);
	if (!$menu && is_super_admin()) {
		$menu = '<ul class="nav navbar-nav"><li><a href="' . admin_url('nav-menus.php') . '" class="signin-loader loaderbt">添加导航菜单</a></li></ul>';
	}
	echo $menu;
}


function zib_navbar_header()
{
	$m_layout = _pz('mobile_header_layout', 'center');

	$t = _pz('hometitle') ? _pz('hometitle') : get_bloginfo('name') . (get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '');
	$logo = '<a class="navbar-logo" href="' . get_bloginfo('url') . '" title="' . $t . '">'
	. zib_get_adaptive_theme_img(_pz('logo_src'),_pz('logo_src_dark'),$t,'height="50"').'
			</a>';
	$button = '<button type="button" data-toggle-class data-target=".mobile-navbar" class="navbar-toggle">' . zib_svg('menu', '0 0 1024 1024', 'icon em12') . '</button>';
	if ($m_layout == 'center') {
		$button .= '<button type="button" data-toggle-class data-target=".navbar-search" class="navbar-toggle">' . zib_svg('search') . '</button>';
	}

	echo '<div class="navbar-header">
			<div class="navbar-brand">' . $logo . '</div>
			' . $button . '
		</div>';
}

function zib_nav_mobile($location = 'mobilemenu')
{
	$menu = '';
	$args = array(
		'container'       => false,
		'echo'            => false,
		'fallback_cb'     => false,
		'depth'           => 3,
		'items_wrap'      => '<ul class="mobile-menus theme-box">%3$s</ul>',
		'theme_location'  => $location,
	);

	$m_layout = _pz('mobile_header_layout', 'center');

	$menu .= _pz('theme_mode_button', true) ? '<a href="javascript:;" class="toggle-theme toggle-radius">' . zib_svg('theme') . '</a>' : '';

	if ($m_layout != 'center') {
		$menu .= '<a href="javascript:;" data-toggle-class data-target=".navbar-search" class="toggle-radius">' . zib_svg('search') . '</a>';
	}

	$menu .= wp_nav_menu($args);
	$menu .= '<div class="posts-nav-box" data-title="文章目录"></div>';
	if (!wp_nav_menu($args)) {
		$args['theme_location'] = 'topmenu';
		if (wp_nav_menu($args)) {
			$menu .= wp_nav_menu($args);
		} else {
			$menu .= '<ul class="mobile-menus theme-box"><li><a href="' . admin_url('nav-menus.php') . '" class="signin-loader loaderbt">添加导航菜单</a></li></ul>';
		}
	}
	$new_b = zib_get_write_posts_button('but c-green btn-block hollow', '发布文章', '<i class="fa fa-file-text-o mr10" aria-hidden="true"></i>');
	if (_pz('nav_newposts') && !is_page_template('pages/newposts.php')) {
		$menu .= '<div class="newposts">' . $new_b . '</div>';
	}

	$sub = '
		<a href="javascript:;" class="signin-loader but c-blue"><i class="fa fa-fw fa-sign-in mr10"></i>登录</a>
		<a href="javascript:;" class="signup-loader but c-green"><i class="fa fa-fw fa-pencil-square-o mr10"></i>注册</a>
		';

	if (is_user_logged_in()) {
		global $current_user;
		$sub = '
		<a class="but c-blue" href="' . get_author_posts_url($current_user->ID) . '"><i class="fa fa-fw fa-sign-in mr10"></i>个人中心</a>
		<a class="but c-red" href="" data-toggle="modal" data-target="#modal_signout"><i class="fa fa-fw fa-pencil-square-o mr10"></i>退出登录</a>
		';
		if (is_super_admin()) {
			$sub .= '
			<a target="_blank" class="but" href="' . of_get_menuurl() . '"><i class="fa fa-fw fa-sign-in mr10"></i>主题设置</a>
			<a target="_blank" class="but" href="' . admin_url() . '"><i class="fa fa-fw fa-sign-in mr10"></i>后台管理</a>
			';
		}
	}
	$sub = '<ul class="mobile-user-menus">' . $sub . '</ul>';

	echo $menu . $sub;
}
