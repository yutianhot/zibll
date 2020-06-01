<?php

/**
 * 子比主题
 * Zibll Theme
 * 官方网站：https://www.zibll.com/
 * 作者QQ：770349780
 * 感谢您使用子比主题，主题源码有详细的注释，支持二次开发
 * 如您需要定制功能、或者其它任何交流欢迎加QQ
 */

define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/framework/');
require_once(get_theme_file_path('/framework/options-framework.php'));
require_once(get_theme_file_path('/framework/code/options-html.php'));

/**
 * 引入核心文件
 * 此文件属于加密文件，且有文件修改时间、MD5等效验，请勿做任何修改！！
 */
require get_theme_file_path('/framework/code/theme-code.php');

function framework_option_args()
{
	$args = array(
		'name' => 'Zibll',
		'page_title' => __('子比主题设置', 'theme-textdomain'),
		'menu_title' => __('Zibll主题设置', 'theme-textdomain'),
		'debug' => false,
		'show_toolbar' => true,
	);
	return $args;
}

function optionsframework_options()
{

	$args = array(
		'orderby' => 'count',
		'order' => 'DESC',
		'hide_empty' => false
	);

	// 将所有文章分组类别放入数组
	$options_categories = array();
	$options_categories_obj = get_categories($args);
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = rtrim(get_category_parents($category->cat_ID, false, '>'), '>') . ' [共' . zib_get_cat_postcount($category->cat_ID) . '篇]';
	}

	// 将所有标签放入数组
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ($options_tags_obj as $tag) {
		$options_tags[$tag->term_id] = $tag->name . ' [共' . $tag->count . '篇]';
	}

	// 将所有用户
	$options_users = array();
	$options_users_obj = get_users();
	foreach ($options_users_obj as $user) {
		$options_users[$user->ID] = $user->display_name;
	}

	// 将所有页面拉入数组
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	// $options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// 将所有专题拉入数组
	$options_topics_obj = get_terms(array(
		'taxonomy' => 'topics',
		'hide_empty' => false,
	));
	$options_topics = array();
	foreach ($options_topics_obj as $topics) {
		$options_topics[$topics->term_id] = $topics->name;
	}

	// 将所有链接拉入数组
	$options_linkcats = array();
	$options_linkcats_obj = get_terms('link_category');
	foreach ($options_linkcats_obj as $tag) {
		$options_linkcats[$tag->term_id] = $tag->name;
	}

	// 定义一些常量
	$imagepath =  get_template_directory_uri() . '/img/';
	$f_imgpath =  get_template_directory_uri() . '/framework/img/';
	$adsdesc =  __('可添加任意广告联盟代码或自定义代码', 'zib_language');
	$adsstd =  __('<div style="padding:20px;border:2px dashed #ccc;opacity:.8">广告位，电脑和手机可分别设置，可放任何广告代码</div>');


	$options = array();

	$options[] = array(
		'name' => __('全局核心', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('网站图标', 'zib_language'),
		'id' => 'favicon',
		'desc' => __('自定义网站图标，也就是favicon.ico(建议48x48)'),
		'std' => $imagepath . 'favicon.png',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('桌面图标', 'zib_language'),
		'id' => 'iconpng',
		'desc' => __('桌面图标，建议148x148(苹果手机添加到桌面的图标)'),
		'std' => $imagepath . 'icon.png',
		'type' => 'upload'
	);
	$options[] = array(
		'name' => __('网站Logo（日间主题）', 'zib_language'),
		'id' => 'logo_src',
		'desc' => __('显示在顶部的Logo 建议高度60px，请使用png格式的透明图片', 'zib_language'),
		'question' => '如果单张LOGO图能同时适应日间和夜间主题，则仅设置日间主题的logo即可（推荐这样设置）',
		'std' => $imagepath . 'logo.png',
		'type' => 'upload'
	);
	$options[] = array(
		'name' => __('网站Logo（夜间主题）', 'zib_language'),
		'id' => 'logo_src_dark',
		'class' => 'op-multicheck',
		'std' => $imagepath . 'logo_dark.png',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('登录框logo(日间主题)', 'zib_language'),
		'id' => 'user_img',
		'desc' => __('登录框顶部图像，建议尺寸450px*280px'),
		'question' => '如果单张图能同时适应日间和夜间主题，则仅设置日间主题的图片即可（推荐这样设置）',
		'std' => $imagepath . 'logo.png',
		'type' => 'upload'
	);
	$options[] = array(
		'name' => __('登录框logo(夜间主题)', 'zib_language'),
		'id' => 'user_img_dark',
		'class' => 'op-multicheck',
		'std' => $imagepath . 'logo_dark.png',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('核心SEO优化', 'zib_language'),
		'id' => 'post_keywords_description_s',
		'question' => '开启后每一篇文章、分类和页面都可以独立设置SEO内容',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('文章、页面独立SEO设置', 'zib_language')
	);

	$options[] = array(
		'id' => 'connector',
		'desc' => __('全站连接符（一经选择，切勿更改，对SEO不友好，一般为“-”或“_”或者“|”）', 'zib_language'),
		'std' => _pz('connector') ? _pz('connector') : '-',
		'type' => 'text',
		'class' => 'op-multicheck mini'
	);

	$options[] = array(
		'id' => 'hometitle',
		'name' => __('网站SEO', 'zib_language'),
		'std' => '',
		'question' => '站点一句话有吸引力的标题，建议25—35字，如果未设置，则采用“站点标题+副标题”',
		'desc' => 'SEO标题(title)',
		'settings' => array(
			'rows' => 2
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'keywords',
		'std' => '关键词,关键词,关键词,关键词,关键词,关键词,关键词',
		'question' => '关键字有利于SEO优化，建议个数在5-8个之间，用英文逗号隔开',
		'class' => 'op-multicheck',
		'desc' => __('SEO关键字(keywords)', 'zib_language'),
		'settings' => array(
			'rows' => 2
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'description',
		'std' => __('本站是一个高端大气上档次的网站', 'zib_language'),
		'class' => 'op-multicheck',
		'question' => '介绍、描述您的网站，建议字数在40-70之间',
		'desc' => __('SEO描述(description)', 'zib_language'),
		'settings' => array(
			'rows' => 3
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'description_text',
		'class' => 'op-multicheck',
		'desc' => __('做好网站每一个页面的SEO内容，可以有效的提高搜索引擎收录。SEO内容设置之后不建议轻易修改！', 'zib_language'),
		'type' => ''
	);

	// ======================================================================================================================

	$options[] = array(
		'name' => __('主题显示', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('侧边栏'),
		'id' => 'sidebar_home_s',
		'type' => "checkbox",
		'std' => false,
		'desc' => '首页开启 (开启后请在小工具设置中添加侧边栏内容)'
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'sidebar_single_s',
		'type' => "checkbox",
		'std' => true,
		'desc' => '文章页开启'
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'sidebar_cat_s',
		'type' => "checkbox",
		'std' => false,
		'desc' => '分类页面开启'
	);
	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'sidebar_tag_s',
		'type' => "checkbox",
		'std' => false,
		'desc' => '标签页开启'
	);
	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'sidebar_search_s',
		'type' => "checkbox",
		'std' => false,
		'desc' => '搜索页开启'
	);
	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'description_text',
		'type' => '',
		'desc' => '侧边栏显示位置：'
	);

	$options[] = array(
		'id' => 'sidebar_layout',
		'class' => 'op-multicheck',
		'std' => "right",
		'type' => "images",
		'options' => array(
			'left' => $f_imgpath . '2cl.png',
			'right' => $f_imgpath . '2cr.png',
		)
	);

	$options[] = array(
		'name' => __('默认主题', 'zib_language'),
		'id' => 'theme_mode',
		'question' => '主题最高优先级来自用户选择，也就是浏览器缓存，只有当用户未设置主题的时候此选项才有效',
		'std' => "time-auto",
		'type' => "radio",
		'options' => array(
			'white-theme' => __('日间亮色主题', 'zib_language'),
			'dark-theme' => __('夜间深色主题', 'zib_language'),
			'time-auto' => __('早晚8点自动切换', 'zib_language'),
		)
	);
	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'theme_mode_button',
		'question' => '如果关闭此功能，则前端不会显示切换按钮',
		'type' => "checkbox",
		'std' => true,
		'desc' => '允许用户切换'
	);

	$options[] = array(
		'name' => __("全局主题色", 'zib_language'),
		'desc' => __("选择主题颜色或在下方自定义颜色。", 'zib_language'),
		'id' => "theme_skin",
		'std' => "45B6F7",
		'type' => "colorradio",
		'options' => array(
			'f33c6e' => 'f33c6e',
			'f747c9' => 'f747c9',
			'ae53f3' => 'ae53f3',
			'627bf5' => '627bf5',
			'00a2e3' => '00a2e3',
			'16b597' => '16b597',
			'36af18' => '36af18',
			'8fb107' => '8fb107',
			'b18c07' => 'b18c07',
			'e06711' => 'e06711',
			'f74735' => 'f74735',
		)
	);

	$options[] = array(
		'id' => 'theme_skin_custom',
		'class' => 'op-multicheck',
		'std' => "",
		'desc' => __('自定义高亮主题色（如果不用自定义颜色清空即可）', 'zib_language'),
		'type' => "color"
	);

	$options[] = array(
		'name' => __('全局圆角尺寸', 'zib_language'),
		'id' => 'theme_main_radius',
		'desc' => __('页面卡片的圆角尺寸，建议为8', 'zib_language'),
		'std' => 8,
		'settings' => array(
			'max' => 15,
			'min' => 0,
			'step' => 1,
			'prefix' => '',
			'postfix' => 'px'
		),
		'type' => 'number'
	);

	$options[] = array(
		'name' => __('全局加载loading动画'),
		'id' => 'qj_loading',
		'type' => "checkbox",
		'std' => true,
		'desc' => '开启(网络不好，或显示不正常请关闭！)'
	);

	$options[] = array(
		'id' => 'qj_dh_xs',
		'std' => 'no1',
		'class' => 'op-multicheck mini',
		'type' => 'select',
		'desc' => '选择动画效果',
		'options' => array(
			'no1' => __('淡出淡入', 'zib_language'),
			'no2' => __('动画2', 'zib_language'),
			'no3' => __('动画3', 'zib_language'),
			'no4' => __('动画4', 'zib_language'),
			'no5' => __('动画5', 'zib_language'),
			'no6' => __('动画6', 'zib_language'),
			'no7' => __('动画7', 'zib_language'),
			'no8' => __('动画8', 'zib_language'),
			'no9' => __('动画9', 'zib_language'),
			'no10' => __('动画10', 'zib_language')
		)
	);

	$options[] = array(
		'name' => __("顶部导航主题色", 'zib_language'),
		'id' => 'description_text',
		'desc' => __('默认为同步全局主题，在此可以单独设置顶部导航主题的背景色和前景色，注意背景色和文字颜色的搭配', 'zib_language'),
		'type' => ''
	);

	$options[] = array(
		'id' => 'header_theme_custom',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => false,
		'desc' => '开启自定义导航主题色'
	);
	$options[] = array(
		'id' => 'description_text',
		'class' => 'op-multicheck',
		'desc' => __('自定义背景颜色：', 'zib_language'),
		'type' => ''
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => "header_theme_bg",
		'std' => "45B6F7",
		'type' => "colorradio",
		'options' => array(
			'ff648f'  => 'ff648f',
			'c246f5' => 'c246f5',
			'469cf5' => '469cf5',
			'27bf41' => '27bf41',
			'fd6b4e' => 'fd6b4e',
			'2d2422' => '2d2422',
		)
	);

	$options[] = array(
		'id' => 'header_theme_bg_custom',
		'class' => 'op-multicheck',
		'std' => "",
		'question' => '如果选择了上方的预选颜色，请将此处清空！',
		'type' => "color"
	);

	$options[] = array(
		'id' => 'description_text',
		'class' => 'op-multicheck',
		'desc' => __('自定义文字颜色：', 'zib_language'),
		'type' => ''
	);

	$options[] = array(
		'id' => "header_theme_color",
		'class' => 'op-multicheck',
		'std' => "fff",
		'type' => "colorradio",
		'options' => array(
			'fff' => 'fff',
			'555' => '555',
		)
	);
	$options[] = array(
		'id' => 'header_theme_color_custom',
		'class' => 'op-multicheck',
		'desc' => __("前景色", 'zib_language'),
		'std' => "",
		'question' => '如果选择了上方的黑色或者白色，请将此处清空！',
		'type' => "color"
	);



	$options[] = array(
		'name' => __("底部页脚主题色", 'zib_language'),
		'id' => 'description_text',
		'desc' => __('默认为同步全局主题，在此可以单独设置顶部导航主题的背景色和前景色，注意背景色和文字颜色的搭配', 'zib_language'),
		'type' => ''
	);

	$options[] = array(
		'id' => 'footer_theme_custom',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => false,
		'desc' => '开启自定义底部页脚主题色'
	);
	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'description_text',
		'desc' => __('自定义背景颜色：', 'zib_language'),
		'type' => ''
	);
	$options[] = array(
		'class' => 'op-multicheck',
		'id' => "footer_theme_bg",
		'std' => "45B6F7",
		'type' => "colorradio",
		'options' => array(
			'ff648f'  => 'ff648f',
			'c246f5' => 'c246f5',
			'469cf5' => '469cf5',
			'27bf41' => '27bf41',
			'fd6b4e' => 'fd6b4e',
			'2d2422' => '2d2422',
		)
	);

	$options[] = array(
		'id' => 'footer_theme_bg_custom',
		'class' => 'op-multicheck',
		'std' => "",
		'question' => '如果选择了上方的预选颜色，请将此处清空！',
		'type' => "color"
	);

	$options[] = array(
		'id' => 'description_text',
		'class' => 'op-multicheck',
		'desc' => __('自定义文字颜色：', 'zib_language'),
		'type' => ''
	);

	$options[] = array(
		'id' => 'footer_theme_color_custom',
		'class' => 'op-multicheck',
		'desc' => __("前景色", 'zib_language'),
		'std' => "",
		'question' => '在此选择自定义前景色，注意与背景色的搭配',
		'type' => "color"
	);


	//-------------------------------------------------------------
	//---------------------------------------------------------------------------------

	$options[] = array(
		'name' => __('常用功能', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '登录注册',
		'id' => 'user_verification',
		'std' => true,
		'desc' => __('登陆、注册需输入图形验证码', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'user_signup_captch',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('开启注册验证码', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'captch_type',
		'class' => 'op-multicheck',
		'std' => "email",
		'type' => "radio",
		'options' => array(
			'email' => __('邮箱验证码（请确保邮件能正常发送）'),
			'dxyz' => __('短信验证码（测试中，暂未开放）')
		)
	);
	$options[] = array(
		'name' => '图像异步加载',
		'id' => 'lazy_posts_thumb',
		'std' => true,
		'desc' => __('文章缩略图', 'zib_language'),
		'question' => '开启图片懒加载，当页面滚动到图像位置时候才加载图片，可极大的提高页面访问速度。',
		'type' => 'checkbox'
	);
	$options[] = array(
		'id' => 'lazy_avatar',
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('头像', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'lazy_posts_content',
		'class' => 'op-multicheck',
		'std' => false,
		'question' => '对SEO有一点影响，请酌情开启！',
		'desc' => __('文章内容图片（谨慎开启）', 'zib_language'),
		'type' => 'checkbox'
	);
	$options[] = array(
		'id' => 'lazy_comment',
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('评论内容图片', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'lazy_sider',
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('幻灯片图片', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'lazy_cover',
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('封面图片', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '文章功能',
		'id' => 'article_nav',
		'desc' => '文章目录树',
		'std' => true,
		'type' => "checkbox"
	);

	$options[] = array(
		'id' => 'imagelightbox',
		'class' => 'op-multicheck',
		'desc' => '图片灯箱',
		'std' => true,
		'type' => "checkbox"
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'post_like_s',
		'desc' => '文章点赞',
		'std' => true,
		'type' => "checkbox"
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'comment_like_s',
		'desc' => '评论点赞',
		'std' => true,
		'type' => "checkbox"
	);

	$options[] = array(
		'name' => '搜索功能',
		'id' => 'search_popular_key',
		'desc' => '热门搜索关键词',
		'std' => true,
		'type' => "checkbox"
	);
	$options[] = array(
		'id' => 'search_popular_title',
		'class' => 'mini op-multicheck',
		'desc' => '热门搜索-默认标题',
		'type' => 'text',
		'std' => '热门搜索',
	);
	$options[] = array(
		'id' => 'search_popular_key_num',
		'desc' => __('热门关键词最大数量', 'zib_language'),
		'std' => 20,
		'class' => 'op-multicheck',
		'settings' => array(
			'max' => 100,
			'min' => 10,
			'step' => 2,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);

	$options[] = array(
		'id' => 'search_placeholder',
		'class' => 'op-multicheck',
		'desc' => '搜索框-默认占位符',
		'type' => 'text',
		'std' => '开启精彩搜索',
	);


	$options[] = array(
		'name' => __('返回顶部按钮', 'zib_language'),
		'id' => 'float_right_ontop',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('显示', 'zib_language')
	);

	$options[] = array(
		'id' => 'float_right_mobile_show',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('在手机端显示', 'zib_language')
	);

	$options[] = array(
		'name' => __('弹窗通知', 'zib_language'),
		'id' => 'system_notice_s',
		'question' => '打开页面自动弹出一个模态框，当天不会重复显示',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('开启', 'zib_language')
	);

	$options[] = array(
		'id' => 'system_notice_size',
		'std' => 'modal-sm',
		'class' => 'op-multicheck',
		'type' => 'radio',
		'options' => array(
			'modal-sm' => __('小(弹窗大小)', 'zib_language'),
			'' => __('中', 'zib_language'),
			'modal-lg' => __('大', 'zib_language')
		)
	);

	$options[] = array(
		'id' => 'system_notice_radius',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => false,
		'desc' => __('按钮圆角显示', 'zib_language')
	);

	$options[] = array(
		'id' => 'system_notice_title',
		'class' => 'op-multicheck',
		'std' => '<i class="fa fa-heart c-red"></i>',
		'desc' => '标题',
		'settings' => array(
			'rows' => 1
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'system_notice_content',
		'class' => 'op-multicheck',
		'desc' => '内容',
		'settings' => array(
			'rows' => 3
		),
		'type' => 'textarea',
		'std' => '<p class="c-yellow">感谢您选择子比主题</p>
这是一条系统弹窗通知，您可以在后台-主题设置里修改内容',
	);

	$options[] = array(
		'id' => 'system_notice_b1_t',
		'class' => 'mini',
		'desc' => '按钮1：内容',
		'type' => 'text',
		'std' => '子比官网',
	);

	$options[] = array(
		'id' => 'system_notice_b1_h',
		'class' => 'op-multicheck',
		'desc' => '按钮1：链接',
		'type' => 'text',
		'std' => 'https://zibll.com',
	);

	$options[] = array(
		'desc' => __("按钮1：颜色", 'zib_language'),
		'id' => "system_notice_b1_c",
		'class' => 'op-multicheck',
		'std' => "c-green",
		'type' => "colorradio",
		'options' => array(
			'c-red' => 'ffd4d4',
			'c-yellow' => 'ffe7c5',
			'c-blue' => 'c6e6ff',
			'c-green' => 'c6f3d5',
			'c-purple' => 'e9c9f5',
			'b-red' => 'f74b3d',
			'b-yellow' => 'f3920a',
			'b-blue' => '0a8cf3',
			'b-green' => '1fd05a',
			'b-purple' => 'c133f5',
		)
	);

	$options[] = array(
		'id' => 'system_notice_b2_t',
		'class' => 'mini',
		'desc' => '按钮2：内容',
		'type' => 'text',
		'std' => '立即设置',
	);

	$options[] = array(
		'id' => 'system_notice_b2_h',
		'class' => 'op-multicheck',
		'desc' => '按钮2：链接',
		'type' => 'text',
		'std' => of_get_menuurl('options-group-3-tab'),
	);

	$options[] = array(
		'desc' => __("按钮2：颜色", 'zib_language'),
		'id' => "system_notice_b2_c",
		'class' => 'op-multicheck',
		'std' => "c-blue",
		'type' => "colorradio",
		'options' => array(
			'c-red' => 'ffd4d4',
			'c-yellow' => 'ffe7c5',
			'c-blue' => 'c6e6ff',
			'c-green' => 'c6f3d5',
			'c-purple' => 'e9c9f5',
			'b-red' => 'f74b3d',
			'b-yellow' => 'f3920a',
			'b-blue' => '0a8cf3',
			'b-green' => '1fd05a',
			'b-purple' => 'c133f5',
		)
	);
	// ======================================================================================================================





	/*
	$options[] = array(
		'name' => __('分类url去除category字样', 'zib_language'),
		'id' => 'no_categoty',
		'type' => "checkbox",
		'std' => false,
		'question' => '（主题已内置no-category插件功能，请不要安装插件；开启后请去设置-固定连接中点击保存即可',
		'desc' => __('开启', 'zib_language', 'zib_language')
	);

		$options[] = array(
		'name' => '设置引导',
		'id' => 'edit_yd',
		'type' => "checkbox",
		'question' => '关闭设置引导将不会在页面显示快速设置链接，建议主题适用熟练之后在关闭',
		'std' => false,
		'desc' => '关闭'); */

	//--------------------------------------------------------------
	$options[] = array(
		'name' => __('顶部导航', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('电脑端导航布局', 'zib_language'),
		'id' => 'header_layout',
		'std' => "1",
		'type' => "images",
		'options' => array(
			'1' => $f_imgpath . 'header_layout_1.png',
			'2' => $f_imgpath . 'header_layout_2.png',
			'3' => $f_imgpath . 'header_layout_3.png',
		)
	);

	$options[] = array(
		'id' => 'nav_fixed',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('电脑端固定在顶部)')
	);

	$options[] = array(
		'name' => __('移动端导航布局', 'zib_language'),
		'id' => 'mobile_header_layout',
		'std' => "center",
		'type' => "images",
		'options' => array(
			'center' => $f_imgpath . 'mobile_header_layout_center.png',
			'left' => $f_imgpath . 'mobile_header_layout_left.png',
		)
	);

	$options[] = array(
		'id' => 'mobile_navbar_align',
		'std' => 'left',
		'type' => 'radio',
		'name' => '移动端菜单弹出方向',
		'options' => array(
			'top' => __('顶部', 'zib_language'),
			'left' => __('左边', 'zib_language'),
			'right' => __('右边', 'zib_language')
		)
	);

	$options[] = array(
		'name' => '投稿按钮',
		'id' => 'nav_newposts',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'question' => '请先在个人中心中开启投稿功能，并设置好投稿页面',
		'std' => true,
		'desc' => __('显示', 'zib_language')
	);

	$options[] = array(
		'name' => '顶部搜索功能',
		'id' => 'header_search_popular_key',
		'desc' => '显示热门搜索关键词',
		'std' => true,
		'type' => "checkbox"
	);
	$options[] = array(
		'id' => 'header_search_cat',
		'desc' => '显示分类选择',
		'class' => 'op-multicheck',
		'std' => true,
		'type' => "checkbox"
	);
	$options_categories_no = array(0 => "未选择") + $options_categories;
	$options[] = array(
		'id' => 'header_search_cat_in',
		'class' => 'op-multicheck mini',
		'desc' => __('默认分类', 'zib_language'),
		'options' => $options_categories_no,
		'type' => 'select'
	);
	$options[] = array(
		'id' => 'header_search_more_cat',
		'desc' => '显示更多分类选择',
		'class' => 'op-multicheck',
		'std' => true,
		'type' => "checkbox"
	);
	$options_categories_all = array('all' => '全部允许') + $options_categories;

	$options[] = array(
		'id' => 'header_search_more_cat_obj',
		'std' => '',
		'class' => 'op-multicheck',
		'desc' => __('允许选择的更多分类', 'zib_language'),
		'options' => $options_categories_all,
		'type' => 'multicheck'
	);

	//--------------------------------------------------------------
	$options[] = array(
		'name' => __('底部页脚', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('页脚布局模板选择', 'zib_language'),
		'id' => 'fcode_template',
		'std' => "template_1",
		'question' => '由于页脚布局及样式种类繁多，更多模板正在开发中。后续也会发布可视化编辑功能',
		'type' => "images",
		'options' => array(
			'template_1' => $f_imgpath . 'fcode_template_1.png',
		)
	);

	$options[] = array(
		'name' => __('板块一', 'zib_language'),
		'id' => 'footer_t1_img',
		'desc' => __('日间模式图片', 'zib_language'),
		'std' => $imagepath . 'logo.png',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'footer_t1_img_dark',
		'class' => 'op-multicheck',
		'desc' => __('夜间模式图片', 'zib_language'),
		'std' => $imagepath . 'logo_dark.png',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'footer_t1_t',
		'class' => 'mini',
		'desc' => __('标题', 'zib_language'),
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => __('文案', 'zib_language'),
		'id' => 'fcode_t1_code',
		'class' => 'op-multicheck',
		'std' => 'Zibll 子比主题专为博客、自媒体、资讯类的网站设计开发，简约优雅的设计风格，全面的前端用户功能，简单的模块化配置，欢迎您的体验',
		'type' => 'textarea',
		'settings' => array(
			'rows' => 3
		),
	);

	$options[] = array(
		'name' => __('板块二', 'zib_language'),
		'desc' => __('第一行(建议为友情链接，或者站内链接)', 'zib_language'),
		'id' => 'fcode_t2_code_1',
		'std' => '<a href="https://zibll.com">友链申请</a>
<a href="https://zibll.com">免责声明</a>
<a href="https://zibll.com">广告合作</a>
<a href="https://zibll.com">关于我们</a>',
		'type' => 'textarea',
		'settings' => array(
			'rows' => 4
		),
	);

	$options[] = array(
		'desc' => __('第二行(建议为版权提醒，备案号等)', 'zib_language'),
		'id' => 'fcode_t2_code_2',
		'class' => 'op-multicheck',
		'std' => 'Copyright &copy;&nbsp;' . date('Y') . '&nbsp;·&nbsp;<a href="' . home_url() . '">' . get_bloginfo('name') . '</a>&nbsp;·&nbsp;由<a target="_blank" href="https://zibll.com">Zibll主题</a>强力驱动.',
		'type' => 'textarea',
		'settings' => array(
			'rows' => 3
		),
	);

	$options[] = array(
		'name' => __('联系方式', 'zib_language'),
		'id' => 'footer_contact_m_s',
		'question' => '如果不勾选则仅仅在电脑端显示此联系方式图标',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('在移动端显示')
	);

	$options[] = array(
		'id' => 'footer_contact_wechat_img',
		'class' => 'op-multicheck',
		'desc' => __('微信二维码', 'zib_language'),
		'std' => $imagepath . 'qrcode.png',
		'type' => 'upload'
	);

	$options[] = array(
		'desc' => __('QQ号', 'zib_language'),
		'id' => 'footer_contact_qq',
		'class' => 'op-multicheck',
		'std' => '1234567788',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => __('微博链接', 'zib_language'),
		'id' => 'footer_contact_weibo',
		'class' => 'op-multicheck',
		'std' => 'https://weibo.com/',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => __('邮箱', 'zib_language'),
		'id' => 'footer_contact_email',
		'class' => 'op-multicheck',
		'std' => '1234567788@QQ.COM',
		'type' => 'text'
	);

	$is = 3;
	for ($i = 1; $i <= $is; $i++) {
		$options[] = array(
			'id' => 'footer_mini_img_' . $i,
			'name' => $i == 1 ? '板块三' : '',
			'desc' => __('图片', 'zib_language') . $i,
			'std' => $i != 3 ?  $imagepath . 'qrcode.png' : '',
			'type' => 'upload'
		);
		if ($i == 1) {
			$options[] = array(
				'id' => 'footer_mini_img_m_s',
				'type' => "checkbox",
				'class' => 'op-multicheck',
				'question' => '在移动端显示',
				'std' => true,
				'desc' => __('显示')
			);
		}
		$options[] = array(
			'id' => 'footer_mini_img_t_' . $i,
			'class' => 'op-multicheck mini',
			'desc' => __('图片配文', 'zib_language'),
			'std' => '扫码加QQ群',
			'type' => 'text'
		);
	}


	//--------------------------------------------------------------
	$options[] = array(
		'name' => __('首页文章', 'zib_language'),
		'type' => 'heading'
	);
	$options[] = array(
		'id' => 'home_exclude_posts',
		'name' => __('排除文章', 'zib_language'),
		'desc' => __('填写需要排除文章的ID，多个ID用逗号分割', 'zib_language'),
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'home_exclude_cats',
		'name' => __('排除分类', 'zib_language'),
		'class' => 'op-multicheck',
		'desc' => __('填写需要排除文章分类的ID，多个ID用逗号分割', 'zib_language'),
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'home_list_num',
		'name' => __('文章栏目数', 'zib_language'),
		'desc' => __('需要开启的列表栏目数量', 'zib_language'),
		'std' => 4,
		'settings' => array(
			'max' => 10,
			'min' => 1,
			'step' => 1,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);

	$options[] = array(
		'name' => __('文章栏目：栏目1', 'zib_language'),
		'desc' => '文章栏目1：标题',
		'question' => '栏目一为最新文章列表，不可修改排序方式',
		'class' => 'mini',
		'id' => 'index_list_title',
		'std' => __('最新发布', 'zib_language'),
		'settings' => array(
			'rows' => 1
		),
		'type' => 'textarea'
	);

	$home_list_num = _pz('home_list_num') ? _pz('home_list_num') : '4';

	for ($i = 2; $i <= $home_list_num; $i++) {
		$options[] = array(
			'name' => __('文章栏目：栏目' . $i . '', 'zib_language'),
			'id' => 'home_list' . $i . '_s',
			'std' => false,
			'desc' => __('开启', 'zib_language'),
			'type' => 'checkbox'
		);

		$options[] = array(
			'id' => 'home_list' . $i . '_cat',
			'class' => 'op-multicheck mini',
			'desc' => __('显示的分类内容', 'zib_language'),
			'options' => $options_categories,
			'type' => 'select'
		);

		$options[] = array(
			'id' => 'home_list' . $i . '_t',
			'class' => 'op-multicheck mini',
			'desc' => '文章列表' . $i . '：标题',
			'question' => '如果不填写，则显示所选择分类的名称',
			'std' => '',
			'settings' => array(
				'rows' => 1
			),
			'type' => 'textarea'
		);
	}


	//------------------------------------------------
	$options[] = array(
		'name' => __('首页专题', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('首页专题模块', 'zib_language'),
		'id' => 'topic_kg',
		'std' => true,
		'desc' => __('开启', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'topic_sjd',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('手机端不显示', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'topic_blank',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('新窗口打开', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'topic_title',
		'class' => 'op-multicheck mini',
		'desc' => __('专题名称', 'zib_language'),
		'std' => '精彩专题',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'topic_ms',
		'desc' => '副标题',
		'class' => 'op-multicheck',
		'std' => '这里是专题的描述内容',
		'settings' => array(
			'rows' => 1
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'topic_number',
		'class' => 'op-multicheck',
		'question' => '修改此项目后，请先保存后在修改下面内容',
		'desc' => '显示数量',
		'std' => 4,
		'settings' => array(
			'max' => 10,
			'min' => 1,
			'step' => 1,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);

	$topic_i = _pz('topic_number', 4);

	for ($i = 1; $i <= $topic_i; $i++) {
		$options[] = array(
			'id' => 'topic_name_' . $i,
			'class' => 'mini',
			'desc' => __('显示标题', 'zib_language'),
			'question' => '默认显示为分类的名称，如果单独设置名称，请填写此项',
			'std' => '',
			'type' => 'text'
		);

		$options[] = array(
			'id' => 'topic_category_' . $i,
			'desc' => '选择专题',
			'class' => 'op-multicheck mini',
			'options' => $options_topics,
			'type' => 'select'
		);
	}

	// ==================================
	$options[] = array(
		'name' => __('首页幻灯片', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('首页幻灯片', 'zib_language'),
		'id' => 'index_slide_s',
		'std' => true,
		'desc' => __('开启', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'index_slide_loop_s',
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('循环播放', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'index_slide_show_button',
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('显示翻页按钮', 'zib_language'),
		'type' => 'checkbox'
	);
	$options[] = array(
		'id' => 'index_slide_show_pagination',
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('显示指示器', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'index_slide_moshi',
		'std' => 'moren',
		'class' => 'op-multicheck mini',
		'desc' => __('显示模式'),
		'type' => "select",
		'options' => array(
			'moren' => __('默认', 'zib_language'),
			'sltms' => __('组合（开发中）', 'zib_language'),
			'mohu' => __('模糊（开发中）', 'zib_language'),
		)
	);

	$options[] = array(
		'id' => 'index_slide_position',
		'std' => 'top',
		'class' => 'op-multicheck mini',
		'desc' => __('显示位置'),
		'type' => "select",
		'options' => array(
			'header' => __('导航栏内部', 'zib_language'),
			'top' => __('顶部全宽度', 'zib_language'),
			'left' => __('内容区域', 'zib_language'),
		)
	);
	$options[] = array(
		'id' => 'index_slide_effect',
		'std' => 'slide',
		'class' => 'op-multicheck mini',
		'desc' => __('切换动画'),
		'type' => "select",
		'options' => array(
			'slide' => __('滑动', 'haoui'),
			'fade' => __('淡出淡入', 'haoui'),
			'cube' => __('3D方块', 'haoui'),
			'coverflow' => __('3D滑入', 'haoui'),
			'flip' => __('3D翻转', 'haoui'),
		)
	);

	$options[] = array(
		'name' => '幻灯片设置',
		'id' => 'index_slide_auto_height',
		'question' => '开启自动高度之后，下方设定的高度则不生效。',
		'std' => false,
		'desc' => __('自动高度', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'index_slide_height',
		'class' => 'op-multicheck',
		'desc' => '幻灯片电脑端高度',
		'std' => 350,
		'settings' => array(
			'max' => 700,
			'min' => 120,
			'step' => 20,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);

	$options[] = array(
		'id' => 'index_slide_height_m',
		'desc' => '幻灯片移动端高度',
		'class' => 'op-multicheck',
		'std' => 220,
		'settings' => array(
			'max' => 500,
			'min' => 100,
			'step' => 20,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);

	$options[] = array(
		'id' => 'index_slide_interval',
		'desc' => '播放速度（每张停留时间）',
		'class' => 'op-multicheck',
		'std' => 5,
		'settings' => array(
			'max' => 20,
			'min' => 2,
			'step' => 1,
			'prefix' => '',
			'postfix' => '秒'
		),
		'type' => 'number'
	);
	$options[] = array(
		'id' => 'index_slide_sort',
		'class' => 'op-multicheck',
		'desc' => '幻灯片数量(修改此项后必须先保存后再做其他设置！)',
		'std' => 4,
		'settings' => array(
			'max' => 20,
			'min' => 1,
			'step' => 1,
			'prefix' => '',
			'postfix' => '张'
		),
		'type' => 'number'
	);


	$is = _pz('index_slide_sort') ? _pz('index_slide_sort') : '4';

	for ($i = 1; $i <= $is; $i++) {

		$options[] = array(
			'name' => __('幻灯片-图', 'zib_language') . $i,
			'id' => 'index_slide_src_' . $i,
			'desc' => __('图片，建议尺寸：', 'zib_language') . '900*400',
			'std' => $imagepath . 'slide.jpg',
			'type' => 'upload'
		);

		$options[] = array(
			'id' => 'index_slide_title_' . $i,
			'class' => 'op-multicheck',
			'desc' => __('幻灯片标题', 'zib_language'),
			'std' => '更优雅的Wordpress主题 - 子比主题',
			'type' => 'text'
		);

		$options[] = array(
			'id' => 'index_slide_desc_' . $i,
			'class' => 'op-multicheck',
			'desc' => __('幻灯片简介', 'zib_language'),
			'std' => '化繁为简，为阅读而生',
			'type' => 'text'
		);

		$options[] = array(
			'id' => 'index_slide_href_' . $i,
			'class' => 'op-multicheck',
			'desc' => __('幻灯片链接', 'zib_language'),
			'std' => 'https://zibll.com',
			'type' => 'text'
		);

		$options[] = array(
			'id' => 'index_slide_blank_' . $i,
			'class' => 'op-multicheck',
			'std' => true,
			'desc' => __('新窗口打开', 'zib_language'),
			'type' => 'checkbox'
		);
	}



	//--------------------------------------------------------------
	$options[] = array(
		'name' => __('分类/标签页', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('显示分类、标签封面', 'zib_language'),
		'id' => 'page_cover_cat_s',
		'question' => '在分类页和标签页顶部显示图像封面，请在文章分类和标签中设置封面图和介绍',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('分类页显示', 'zib_language')
	);

	$options[] = array(
		'id' => 'page_cover_tag_s',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('标签页显示', 'zib_language')
	);

	$options[] = array(
		'id' => 'page_cover_img',
		'desc' => __('默认封面图，建议尺寸1000x400,如果分类页未开启侧边栏，请选择更大的尺寸'),
		'std' => $imagepath . 'user_t.jpg',
		'question' => '显示在分类和标签页顶部的封面图像，你可以在分类设置中单独设置每一个分类的封面图，如未设置则显示此图像',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('AJAX菜单列表', 'zib_language'),
		'id' => 'option_list',
		'desc' => '在分类页和标签页显示分类、标签、专题的菜单，通过ajax获取内容',
		'type' => "",
	);

	$options[] = array(
		'id' => 'option_list',
		'type' => "",
		'desc' => __('分类页显示：', 'zib_language')
	);

	$options[] = array(
		'id' => 'option_list_cat_cat',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('分类菜单列表', 'zib_language')
	);
	$options[] = array(
		'id' => 'option_list_cat_top',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('专题菜单列表', 'zib_language')
	);
	$options[] = array(
		'id' => 'option_list_cat_tag',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('标签菜单列表', 'zib_language')
	);
	//////////////////////////
	$options[] = array(
		'id' => 'option_list',
		'type' => "",
		'desc' => __('专题页显示：', 'zib_language')
	);

	$options[] = array(
		'id' => 'option_list_topics_cat',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('分类菜单列表', 'zib_language')
	);
	$options[] = array(
		'id' => 'option_list_topics_top',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('专题菜单列表', 'zib_language')
	);
	$options[] = array(
		'id' => 'option_list_topics_tag',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('标签菜单列表', 'zib_language')
	);
	//////////////////////////
	$options[] = array(
		'id' => 'option_list',
		'type' => "",
		'desc' => __('标签页显示：', 'zib_language')
	);

	$options[] = array(
		'id' => 'option_list_tag_cat',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('分类菜单列表', 'zib_language')
	);
	$options[] = array(
		'id' => 'option_list_tag_top',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('专题菜单列表', 'zib_language')
	);
	$options[] = array(
		'id' => 'option_list_tag_tag',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('标签菜单列表', 'zib_language')
	);



	$options[] = array(
		'id' => 'option_list_alllist_s',
		'type' => "checkbox",
		'question' => '开启后在分类菜单前会单独显示一个全部分类、全部标签的下拉栏，鼠标滑动后将展开显示全部分类、标签明细。如果不开启此功能则只会显示下方选择的分类、标签、专题',
		'std' => true,
		'desc' => __('单独显示全部列表', 'zib_language')
	);

	$options[] = array(
		'name' => __('分类菜单列表', 'zib_language'),
		'id' => 'option_list_cats',
		'options' => $options_categories,
		'desc' => __('需要显示为菜单的分类（建议为常用分类）'),
		'type' => 'multicheck'
	);

	$options[] = array(
		'name' => __('专题菜单列表', 'zib_language'),
		'id' => 'option_list_topics',
		'options' => $options_topics,
		'desc' => __('需要显示为菜单的专题（建议为常用专题）'),
		'type' => 'multicheck'
	);

	$options[] = array(
		'name' => __('标签菜单列表', 'zib_language'),
		'id' => 'option_list_tags',
		'options' => $options_tags,
		'desc' => __('需要显示为菜单的标签（建议为常用标签）'),
		'type' => 'multicheck'
	);

	//--------------------------------------------------------------
	$options[] = array(
		'name' => __('文章列表', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('列表布局', 'zib_language'),
		'id' => 'list_show_type',
		'std' => "separate",
		'type' => "images",
		'options' => array(
			'separate' => $f_imgpath . 'list_separate.png',
			'no_margin' => $f_imgpath . 'list_no_margin.png',
		)
	);

	$options[] = array(
		'name' => __('默认排序方式', 'zib_language'),
		'id' => 'list_orderby',
		'std' => "modified",
		'type' => "radio",
		'options' => array(
			'date' => __('按发布时间'),
			'modified' => __('按更新时间'),
		)
	);

	$options[] = array(
		'name' => __('功能', 'zib_language'),
		'id' => 'target_blank',
		'type' => "checkbox",
		'std' => false,
		'desc' => __('新窗口打开文章', 'zib_language')
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'paging_ajax_s',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('AJAX加载', 'zib_language')
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'item_heading_bold',
		'type' => "checkbox",
		'std' => false,
		'desc' => __('列表标题粗体显示', 'zib_language')
	);

	$options[] = array(
		'name' => __('默认列表模式', 'zib_language'),
		'id' => 'list_type',
		'std' => "thumb",
		'type' => "radio",
		'desc' => __('注意：文字模式、自动图文模式、多图模式仅在开启侧边栏的页面有效'),
		'options' => array(
			'text' => __('列表文字模式', 'zib_language'),
			'thumb' => __('列表图文模式（无缩略图时使用备用缩略图）', 'zib_language'),
			'thumb_if_has' => __('列表自动图文模式（无缩略图时自动转换为文字模式） ', 'zib_language'),
			'card' => __('卡片模式 ', 'zib_language')
		)
	);

	if (_pz('list_type') !== 'card') {
		$options[] = array(
			'name' => __('列表卡片模式', 'zib_language'),
			'id' => 'list_card_home',
			'question' => '当默认模式非卡片模式时，可独立选择显示为卡片模式的页面',
			'type' => "checkbox",
			'std' => false,
			'desc' => __('首页开启', 'zib_language')
		);

		$options[] = array(
			'id' => 'list_card_tag',
			'class' => 'op-multicheck',
			'type' => "checkbox",
			'std' => false,
			'desc' => __('标签页开启', 'zib_language')
		);

		$options[] = array(
			'id' => 'list_card_author',
			'class' => 'op-multicheck',
			'type' => "checkbox",
			'std' => false,
			'desc' => __('作者页开启', 'zib_language')
		);

		$options[] = array(
			'id' => 'list_card_topics',
			'class' => 'op-multicheck',
			'type' => "checkbox",
			'std' => false,
			'desc' => __('专题页开启', 'zib_language')
		);
		$options[] = array(
			'name' => __('分类页开启', 'zib_language'),
			'class' => 'op-multicheck',
			'id' => 'list_card',
			'options' => $options_categories,
			'desc' => __('勾选的分类将会在分类页面显示为卡片模式'),
			'type' => 'multicheck'
		);

		$options[] = array(
			'name' => __('列表多图显示', 'zib_language'),
			'id' => 'mult_thumb',
			'question' => '文章格式为“图片、画廊”的文章默认显示为此模式，如需开启整个分类，请打开此开关，并在下方选择一个分类！注意：当列表模式为卡片模式或未开启侧边栏时，此显示方式无效',
			'type' => "checkbox",
			'std' => false,
			'desc' => __('开启', 'zib_language')
		);

		$options[] = array(
			'desc' => __('选择一个文章分类在列表以多张缩略图方式显示', 'zib_language'),
			'id' => 'mult_thumb_cat',
			'class' => 'op-multicheck mini',
			'options' => $options_categories,
			'type' => 'select'
		);
	}
	$options[] = array(
		'name' => __('缩略图设置', 'zib_language'),
		'id' => 'list_thumb_slides_s',
		'question' => '开启后文章格式为“画廊”的文章将显示幻灯片缩略图',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('缩略图开启幻灯片', 'zib_language')
	);
	$options[] = array(
		'id' => 'thumb_postfirstimg_s',
		'class' => 'op-multicheck',
		'question' => '缩略图获取优先级：文章特色图像>文章首图>分类封面图>备用缩略图',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('自动使用文章第一张图作为缩略图（文章无特色图象时）', 'zib_language')
	);

	$options[] = array(
		'id' => 'thumb_catimg_s',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('自动使用分类封面为缩略图', 'zib_language')
	);

	$options[] = array(
		'id' => 'thumb_postfirstimg_size',
		'std' => 'medium',
		'class' => 'op-multicheck',
		'desc' => __('此处的三个尺寸均可在后台-设置-媒体-缩略图中修改，建议此处选择中尺寸，并将中尺寸的尺寸设置为430x300效果最佳'),
		'type' => "radio",
		'options' => array(
			'thumbnail' => __('小尺寸 （缩略图尺寸）', 'zib_language'),
			'medium' => __('中尺寸', 'zib_language'),
			'large' => __('大尺寸', 'zib_language'),
		)
	);

	$options[] = array(
		'id' => 'thumbnail',
		'class' => 'op-multicheck',
		'desc' => __('缩略图预载、备用缩略图'),
		'question' => '当文章没有任何图像时、以及缩略图加载前显示的图像，建议尺寸450x300',
		'std' => $imagepath . 'thumbnail.svg',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('列表小部件', 'zib_language'),
		'id' => 'post_list_author',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('显示文章作者', 'zib_language')
	);

	$options[] = array(
		'id' => 'list_meta_show',
		'std' => "like",
		'class' => 'op-multicheck',
		'type' => "radio",
		'question' => '在移动设备由于显示空间不足，则会隐藏部分部件，此处选择的部件将会一直显示',
		'desc' => __('移动端显示的小部件'),
		'options' => array(
			'view' => __('阅读量 （小部件优先显示）', 'zib_language'),
			'like' => __('点赞数', 'zib_language'),
			'comm' => __('评论', 'zib_language'),
		)
	);

	$options[] = array(
		'name' => __('列表单次加载文章数', 'zib_language'),
		'id' => 'posts_per_page',
		'desc' => __('单次加载过多可能会延长加载时间', 'zib_language'),
		'std' => 12,
		'settings' => array(
			'max' => 24,
			'min' => 4,
			'step' => 1,
			'prefix' => '',
			'postfix' => '篇'
		),
		'type' => 'number'
	);

	$options[] = array(
		'id' => 'ajax_trigger',
		'class' => 'op-multicheck',
		'desc' => '列表加载按钮 文案',
		'std' => '<i class="fa fa-arrow-right"></i>加载更多',
		'settings' => array(
			'rows' => 1
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'ajax_nomore',
		'class' => 'op-multicheck',
		'desc' => '列表全部加载完毕 文案',
		'std' => '没有更多内容了',
		'settings' => array(
			'rows' => 1
		),
		'type' => 'textarea'
	);

	// -----------------------------------------
	$options[] = array(
		'name' => __('文章页功能', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('文章功能', 'zib_language'),
		'id' => 'post_p_indent_s',
		'type' => "checkbox",
		'question' => '开启后文章内容每一个段落首行将向右偏移2个文字距离',
		'std' => false,
		'desc' => __('内容段落缩进', 'zib_language')
	);

	$options[] = array(
		'id' => 'article_maxheight_kg',
		'class' => 'op-multicheck',
		'question' => '开启后如果文章高度超过设定值则会显示展开阅读全文的按钮。也可以在每篇文章中单独开启此功能',
		'desc' => '内容高度限制',
		'std' => false,
		'type' => "checkbox"
	);

	$options[] = array(
		'id' => 'article_maxheight',
		'class' => 'op-multicheck',
		'desc' => __('限制文章内容的最大高度', 'zib_language'),
		'std' => 1000,
		'settings' => array(
			'max' => 3000,
			'min' => 600,
			'step' => 200,
			'prefix' => '',
			'postfix' => 'px'
		),
		'type' => 'number'
	);


	//////////////////////////
	$options[] = array(
		'name' => __('文章封面', 'zib_language'),
		'id' => 'description_text',
		'type' => "",
		'desc' => '在发布文章时候，将文章格式设置为"图片、画廊"将会在文章页头部显示图片封面，如需显示幻灯片封面，请将文章格式设置为"画廊"，并开启下方功能（注意！使用这两种格式请确保文章内的图片足够清晰）'
	);

	$options[] = array(
		'id' => 'article_slide_cover',
		'question' => '开启此功能后文章格式为"画廊"的文章将会显示幻灯片封面',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('开启幻灯片封面', 'zib_language')
	);

	$options[] = array(
		'id' => 'article_cover_slide_show_button',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('灯片封面：显示翻页按钮', 'zib_language'),
		'type' => 'checkbox'
	);
	$options[] = array(
		'id' => 'article_cover_slide_show_pagination',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('灯片封面：显示指示器', 'zib_language'),
		'type' => 'checkbox'
	);
	if (_pz('index_slide_moshi') != 'mohu') {
		$options[] = array(
			'id' => 'article_cover_slide_effect',
			'std' => 'slide',
			'class' => 'op-multicheck mini',
			'desc' => __('灯片封面：切换动画'),
			'type' => "select",
			'options' => array(
				'slide' => __('滑动', 'zib_language'),
				'fade' => __('淡出淡入', 'zib_language'),
				'cube' => __('3D方块', 'zib_language'),
				'coverflow' => __('3D滑入', 'zib_language'),
				'flip' => __('3D翻转', 'zib_language'),
			)
		);
	}

	$options[] = array(
		'id' => 'article_cover_slide_height',
		'class' => 'op-multicheck',
		'desc' => '灯片封面：电脑端高度',
		'std' => 300,
		'settings' => array(
			'max' => 700,
			'min' => 120,
			'step' => 20,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);

	$options[] = array(
		'id' => 'article_cover_slide_height_m',
		'desc' => '灯片封面：移动端高度',
		'class' => 'op-multicheck',
		'std' => 180,
		'settings' => array(
			'max' => 500,
			'min' => 100,
			'step' => 20,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);



	$options[] = array(
		'name' => __('文章页显示', 'zib_language'),
		'id' => 'breadcrumbs_single_s',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('面包屑导航', 'zib_language')
	);

	$options[] = array(
		'id' => 'breadcrumbs_single_text',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('面包屑导航用“正文”替代文章标题', 'zib_language')
	);

	$options[] = array(
		'id' => 'post_prevnext_s',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('上一页、下一页板块', 'zib_language')
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'post_authordesc_s',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('作者信息板块', 'zib_language')
	);

	$options[] = array(
		'name' => __('插入一言显示'),
		'id' => 'yiyan_single_content_header',
		'question' => '将一言内容插入到文章页位置，如需修改内容，文件地址在：' . get_template_directory_uri() . '/yiyan/qv-yiyan.txt',
		'type' => "checkbox",
		'std' => false,
		'desc' => __('文章内容头部', 'zib_language')
	);

	$options[] = array(
		'id' => 'yiyan_single_content_footer',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => false,
		'desc' => __('文章内容尾部', 'zib_language')
	);

	$options[] = array(
		'id' => 'yiyan_single_box',
		'type' => "checkbox",
		'std' => true,
		'class' => 'op-multicheck',
		'desc' => __('文章页面下方独立板块', 'zib_language')
	);

	$options[] = array(
		'name' => __('相关文章板块', 'zib_language'),
		'id' => 'post_related_s',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('开启', 'zib_language')
	);

	$options[] = array(
		'id' => 'post_related_thumb_s',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => __('显示图文模式', 'zib_language')
	);

	$options[] = array(
		'id' => 'post_related_type',
		'std' => "img",
		'desc' => __('显示样式', 'zib_language'),
		'type' => "images",
		'options' => array(
			'img' => $f_imgpath . 'related_img.png',
			'list' => $f_imgpath . 'related_list.png',
			'text' => $f_imgpath . 'related_text.png',
		)
	);

	$options[] = array(
		'id' => 'related_title',
		'class' => 'op-multicheck mini',
		'std' => '相关推荐',
		'desc' => __('标题', 'zib_language'),
		'type' => 'text'
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'desc' => __('显示数量', 'zib_language'),
		'id' => 'post_related_n',
		'std' => 6,
		'settings' => array(
			'max' => 12,
			'min' => 4,
			'step' => 2,
			'prefix' => '',
			'postfix' => ''
		),
		'type' => 'number'
	);

	$options[] = array(
		'name' => __('内容分享', 'zib_language'),
		'id' => 'share_s',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('开启', 'zib_language')
	);

	$options[] = array(
		'name' => __('自定义分享代码', 'zib_language'),
		'class' => 'op-multicheck',
		'id' => 'share_code',
		'std' => '',
		'question' => '如果需要更多分享功能，可以改成其他分享代码，例如百度分享，自定义代码生成地址：http://share.baidu.com/code',
		'desc' => __('！留空即使用主题默认分享代码', 'zib_language'),
		'settings' => array(
			'rows' => 2
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __('生成海报分享', 'zib_language'),
		'id' => 'share_img',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('开启', 'zib_language')
	);

	$options[] = array(
		'id' => 'share_img_byimg',
		'class' => 'op-multicheck',
		'desc' => __('图片分享默认图片'),
		'question' => '当文章没有任何图片时显示此图片，建议尺寸800*500',
		'std' => $imagepath . 'slide.jpg',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'share_logo',
		'desc' => __('海报分享LOGO，建议尺寸300x100'),
		'class' => 'op-multicheck',
		'std' => $imagepath . 'logo.png',
		'type' => 'upload'
	);
	$options[] = array(
		'desc' => __('海报分享底部文案', 'zib_language'),
		'class' => 'op-multicheck',
		'id' => 'share_desc',
		'std' => __('扫描二维码阅读全文', 'zib_language'),
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('版权声明', 'zib_language'),
		'id' => 'post_copyright_s',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('开启', 'zib_language')
	);

	$options[] = array(
		'desc' => __('版权提示内容', 'zib_language'),
		'class' => 'op-multicheck',
		'id' => 'post_copyright',
		'std' => __('文章版权归作者所有，未经允许请勿转载。', 'zib_language'),
		'settings' => array(
			'rows' => 2
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __('文章页脚文案', 'zib_language'),
		'id' => 'post_button_toptext',
		'type' => "text",
		'std' => '喜欢就支持以下吧',
		'desc' => __('文章底部打赏、分享按钮上面的文字', 'zib_language')
	);


	$options[] = array(
		'name' => __('文章插入内容', 'zib_language'),
		'question' => '在每篇文章顶部和尾部插入内容，可以插入广告或者文章说明等内容',
		'id' => 'post_front_content',
		'std' => '',
		'desc' => __('在文章内容前-插入内容', 'zib_language'),
		'settings' => array(
			'rows' => 3
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'post_after_content',
		'class' => 'op-multicheck',
		'std' => '',
		'desc' => __('在文章内容后-插入内容', 'zib_language'),
		'settings' => array(
			'rows' => 3
		),
		'type' => 'textarea'
	);

	//-------------------------------------------------

	$options[] = array(
		'name' => __('评论功能', 'zib_language'),
		'type' => 'heading'
	);
	$options[] = array(
		'name' => __('评论功能', 'zib_language'),
		'id' => 'comment_smilie',
		'question' => '为了防止恶意评论，建议在后台-设置-讨论：开启"用户必须登录后才能发表评论"',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('允许插入表情', 'zib_language')
	);

	$options[] = array(
		'id' => 'comment_code',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('允许插入代码', 'zib_language')
	);

	$options[] = array(
		'id' => 'comment_img',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => __('允许插入图片', 'zib_language')
	);

	$options[] = array(
		'id' => 'comment_title',
		'class' => 'mini',
		'desc' => __('自定义评论标题', 'zib_language'),
		'std' => __('评论', 'zib_language'),
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'comment_submit_text',
		'class' => 'op-multicheck mini',
		'desc' => __('自定义评论提交按钮文案', 'zib_language'),
		'std' => __('提交评论', 'zib_language'),
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'comment_text',
		'class' => 'op-multicheck',
		'desc' => __('自定义评论框默认文案', 'zib_language'),
		'std' => __('欢迎您留下宝贵的见解！', 'zib_language'),
		'type' => 'text'
	);








	// ======================================================================================================================
	$options[] = array(
		'name' => __('个人中心', 'zib_language'),
		'type' => 'heading'
	);

	// -----------------------------------------

	$options[] = array(
		'name' => __('用户默认头像', 'zib_language'),
		'id' => 'avatar_default_img',
		'desc' => __('用户默认头像，建议尺寸100px*100px'),
		'std' => $imagepath . 'avatar-default.png',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('用户默认封面', 'zib_language'),
		'id' => 'user_cover_img',
		'desc' => __('默认封面图，建议尺寸1000x400,如果分类页未开启侧边栏，请选择更大的尺寸'),
		'question' => '用户可在个人中心设置自己的封面图，如用户未单独设置则显示此图像',
		'std' => $imagepath . 'user_t.jpg',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('用户默认签名', 'zib_language'),
		'question' => __('用户未设置签名时候，显示的签名', 'zib_language'),
		'std' => '这家伙很懒，什么都没有写...',
		'id' => 'user_desc_std',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'yiyan_avatar_desc',
		'type' => "checkbox",
		'std' => false,
		'class' => 'op-multicheck',
		'desc' => __('用一言代替用户签名', 'zib_language')
	);

	$options[] = array(
		'name' => '功能设置',
		'id' => 'email_set_captch',
		'question' => __('开启后修改邮箱需验证后才能修改,请确保邮件功能正常', 'zib_language'),
		'desc' => '开启邮箱修改验证功能',
		'std' => true,
		'type' => "checkbox"
	);

	$options[] = array(
		'id' => 'post_rewards_s',
		'desc' => '开启用户打赏功能',
		'std' => true,
		'type' => "checkbox"
	);

	$options[] = array(
		'class' => 'op-multicheck mini',
		'id' => 'post_rewards_text',
		'desc' => '自定义打赏按钮文字',
		'std' => '赞赏',
		'type' => 'text'
	);

	// -----------------------------------------

	$options[] = array(
		'name' => __('前端发布文章', 'zib_language'),
		'id' => 'post_article_s',
		'std' => true,
		'desc' => __('允许用户发布文章', 'zib_language'),
		'type' => 'checkbox'
	);
	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'post_article_img_s',
		'std' => false,
		'desc' => __('允许上传图片', 'zib_language'),
		'type' => 'checkbox'
	);
	$options[] = array(
		'id' => 'post_article_limit',
		'std' => "logged_in",
		'type' => "radio",
		'options' => array(
			'logged_in' => __('仅登录后可发布文章'),
			'all' => __('无需登录直接可发布文章'),
		)
	);

	$options[] = array(
		'id' => 'post_article_review_s',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('发布文章无需站长审核直接发布', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => 'mini',
		'id' => 'post_article_user',
		'options' => $options_users,
		'question' => '当您选择无需登录就能投稿时，投稿文章的用户ID',
		'desc' => __('投稿发布用户'),
		'type' => 'select'
	);

	$options[] = array(
		'id' => 'post_article_cat',
		'options' => $options_categories,
		'desc' => __('发布允许选择的分类'),
		'type' => 'multicheck'
	);


	// ======================================================================================================================
	$options[] = array(
		'name' => __('社交登录', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('接入插件', 'zib_language'),
		'id' => 'social',
		'type' => "checkbox",
		'std' => false,
		'desc' => 'Wechat Social登录（需安装迅虎网络的Wechat Social社会化登录插件）'
	);

	$options[] = array(
		'id' => 'oauth_text',
		'desc' => '开启插件的社会化登录以及下方的社会化登录二选一',
		'type' => ""
	);

	$options[] = array(
		'name' => __('QQ登录', 'zib_language'),
		'id' => 'oauth_qq_s',
		'std' => false,
		'desc' => __('开启', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'op-multicheck',
		'desc' => '接入QQ登录，申请地址：https://connect.qq.com/',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'csf-notice op-multicheck',
		'desc' => '回调地址：',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'options-notice op-multicheck',
		'desc' => esc_url(home_url('/oauth/qq/callback')),
		'type' => ""
	);

	$options[] = array(
		'desc' => 'QQ AppID',
		'class' => 'op-multicheck',
		'id' => 'oauth_qq_appid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => 'QQ AppKey',
		'class' => 'op-multicheck',
		'id' => 'oauth_qq_appkey',
		'std' => '',
		'type' => 'text'
	);

	/////////////////
	/////////////////

	$options[] = array(
		'name' => __('微信登录', 'zib_language'),
		'id' => 'oauth_weixin_s',
		'std' => false,
		'desc' => __('开启', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'op-multicheck',
		'desc' => '接入微信登录，申请地址：https://open.weixin.qq.com/',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'csf-notice op-multicheck',
		'desc' => '回调地址：',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'options-notice op-multicheck',
		'desc' => esc_url(home_url('/oauth/weixin/callback')),
		'type' => ""
	);

	$options[] = array(
		'desc' => '微信 AppID',
		'class' => 'op-multicheck',
		'id' => 'oauth_weixin_appid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '微信 AppKey',
		'class' => 'op-multicheck',
		'id' => 'oauth_weixin_appkey',
		'std' => '',
		'type' => 'text'
	);

	/////////////////
	$options[] = array(
		'name' => __('微博登录', 'zib_language'),
		'id' => 'oauth_weibo_s',
		'std' => false,
		'desc' => __('开启', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'op-multicheck',
		'desc' => '接入微博登录，申请地址：https://open.weibo.com/authentication/',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'csf-notice op-multicheck',
		'desc' => '回调地址：',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'options-notice op-multicheck',
		'desc' => esc_url(home_url('/oauth/weibo/callback')),
		'type' => ""
	);

	$options[] = array(
		'desc' => '微博 AppID',
		'class' => 'op-multicheck',
		'id' => 'oauth_weibo_appid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '微博 AppKey',
		'class' => 'op-multicheck',
		'id' => 'oauth_weibo_appkey',
		'std' => '',
		'type' => 'text'
	);

	/////////////////
	$options[] = array(
		'name' => __('GitHub登录', 'zib_language'),
		'id' => 'oauth_github_s',
		'std' => false,
		'desc' => __('开启', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'op-multicheck',
		'desc' => '接入GitHub登录，申请地址：https://github.com/settings/developers',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'csf-notice op-multicheck',
		'desc' => '回调地址：',
		'type' => ""
	);
	$options[] = array(
		'id' => 'oauth_text',
		'class' => 'options-notice op-multicheck',
		'desc' => esc_url(home_url('/oauth/github/callback')),
		'type' => ""
	);

	$options[] = array(
		'desc' => 'GitHub AppID',
		'class' => 'op-multicheck',
		'id' => 'oauth_github_appid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => 'GitHub AppKey',
		'class' => 'op-multicheck',
		'id' => 'oauth_github_appkey',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('按钮样式', 'zib_language'),
		'id' => 'oauth_button_lg',
		'std' => false,
		'desc' => __('显示为大按钮', 'zib_language'),
		'type' => 'checkbox'
	);
	/////////////////

	// ======================================================================================================================
	$options[] = array(
		'name' => __('商城设置', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('商城设置', 'zib_language'),
		'desc' => '货币符号（例如 R币）',
		'class' => 'mini',
		'id' => 'pay_mark',
		'std' => '￥',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'pay_no_logged_in',
		'class' => 'mini-heading',
		'name' => '免登陆购买',
		'std' => true,
		'question' => '开启后如果用户未登录则使用浏览器缓存验证是否购买',
		'desc' => __('开启', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'desc' => __('Cookie时间', 'zib_language'),
		'id' => 'pay_cookie_day',
		'question' => '免登陆购买的浏览器缓存有效时间',
		'std' => 15,
		'settings' => array(
			'max' => 31,
			'min' => 1,
			'step' => 1,
			'prefix' => '',
			'postfix' => '天'
		),
		'type' => 'number'
	);

	$options[] = array(
		'desc' => '未登录提醒',
		'class' => 'op-multicheck',
		'id' => 'pay_no_logged_remind',
		'std' => '您当前未登录！建议登陆后购买，可保存购买订单',
		'settings' => array(
			'rows' => 2
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'default_payment',
		'std' => 'wechat',
		'class' => 'mini-heading',
		'name' => '快捷支付方式',
		'type' => "radio",
		'options' => array(
			'wechat' => __('微信', 'zib_language'),
			'alipay' => __('支付宝', 'zib_language'),
		)
	);

	$options[] = array(
		'id' => 'pay_show_allbut',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('直接显示支付宝、微信购买按钮', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'pay_show_user',
		'class' => 'mini-heading',
		'name' => '用户中心',
		'std' => true,
		'desc' => __('显示订单数据', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __('收款接口选择', 'zib_language'),
		'id' => 'pay_wechat_sdk_options',
		'std' => 'xunhupay_wechat',
		'desc' => '微信接口选择',
		'class' => 'mini',
		'type' => "select",
		'options' => array(
			'official_wechat' => __('微信官方', 'zib_language'),
			'xunhupay_wechat' => __('虎皮椒-微信', 'zib_language'),
			'null' => __('关闭', 'zib_language'),
		)
	);

	$options[] = array(
		'id' => 'pay_alipay_sdk_options',
		'std' => 'xunhupay_alipay',
		'desc' => '支付宝接口选择',
		'class' => 'op-multicheck mini',
		'type' => "select",
		'options' => array(
			'official_alipay' => __('支付宝官方', 'zib_language'),
			'xunhupay_alipay' => __('虎皮椒-支付宝', 'zib_language'),
			'null' => __('关闭', 'zib_language'),
		)
	);
	$options[] = array(
		'id' => 'oauth_text',
		'name' => __('接口配置', 'zib_language'),
		'desc' => '请在下方配置收款接口',
		'type' => ""
	);

	$options[] = array(
		'name' => __('微信-官方企业支付', 'zib_language'),
		'id' => 'oauth_text',
		'class' => 'mini-heading',
		'html' => '<div class="options-notice"><div class="explain"><p>微信官方接口，需要企业执照。申请有一定难度</p>
		<li>支持PC端扫码支付</li>
		<li>支持移动端H5支付</li>
		回调地址：' . get_stylesheet_directory_uri() . '/zibpay/shop/weixin/return.php</div></div>',
		'type' => "html"
	);

	$options[] = array(
		'desc' => '微信支付商户号 PartnerID',
		'class' => 'op-multicheck',
		'id' => 'official_wechat_merchantid',
		'std' => '',
		'type' => 'text'
	);
	$options[] = array(
		'desc' => '公众号或小程序APPID',
		'class' => 'op-multicheck',
		'id' => 'official_wechat_appid',
		'std' => '',
		'type' => 'text'
	);
	$options[] = array(
		'desc' => '微信支付API密钥',
		'class' => 'op-multicheck',
		'id' => 'official_wechat_appkey',
		'std' => '',
		'type' => 'text'
	);
	/**
	$options[] = array(
		'id' => 'official_wechat_jsapi',
		'class' => 'mini-heading',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('开启JSAPI支付（微信内打开直接发起支付）', 'zib_language'),
		'type' => 'checkbox'
	);
	 */
	$options[] = array(
		'id' => 'official_wechat_h5',
		'class' => 'mini-heading',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('开启H5支付（移动端自动跳转到微信APP支付，需开通H5支付）', 'zib_language'),
		'type' => 'checkbox'
	);
	$options[] = array(
		'name' => __('支付宝-官方企业支付', 'zib_language'),
		'id' => 'oauth_text',
		'class' => 'mini-heading',
		'html' => '<div class="options-notice"><div class="explain"><p>支付宝官方接口，商家可申请，需签约 电脑网站支付</p><p>如需接入此方式请填写下方参数，反之请留空</p>
		申请地址：<a target="_blank" href="https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001000" class="loginbtn">点击跳转</a></div></div>',
		'type' => "html"
	);
/*
	$options[] = array(
		'desc' => 'mapi网关-合作伙伴身份PID',
		'class' => 'op-multicheck mini-heading',
		'id' => 'enterprise_alipay_pid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => 'mapi网关-MD5密钥',
		'class' => 'op-multicheck mini-heading',
		'id' => 'enterprise_alipay_md5key',
		'std' => '',
		'type' => 'text'
	);
*/
	$options[] = array(
		'desc' => '网站应用-APPID',
		'class' => 'mini-heading',
		'id' => 'enterprise_alipay_appid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '网站应用-应用私钥',
		'class' => 'op-multicheck',
		'id' => 'enterprise_alipay_privatekey',
		'std' => '',
		'settings' => array(
			'rows' => 3
		),
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'enterprise_alipay_h5',
		'class' => 'mini-heading',
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => __('开启H5支付（移动端自动跳转到支付宝APP支付，需签约 手机网站支付）', 'zib_language'),
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __('支付宝-官方当面付', 'zib_language'),
		'id' => 'oauth_text',
		'class' => 'mini-heading',
		'html' => '<div class="options-notice"><div class="explain"><p>支付宝官方接口，个人可申请，申请难度低</p>
		<li>支持PC端扫码支付</li><p>如需接入此方式请填写下方参数，反之请留空。可以同时开启企业支付以及当面付，PC端优先使用当面付</p>
		申请地址：<a target="_blank" href="https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001003" class="loginbtn">点击跳转</a></div></div>',
		'type' => "html"
	);

	$options[] = array(
		'desc' => '当面付:开放平台-应用APP-ID',
		'class' => 'op-multicheck mini-heading',
		'id' => 'official_alipay_appid',
		'std' => '',
		'type' => 'text'
	);
	$options[] = array(
		'desc' => '当面付:开放平台-应用私钥',
		'class' => 'op-multicheck',
		'id' => 'official_alipay_privatekey',
		'std' => '',
		'settings' => array(
			'rows' => 3
		),
		'type' => 'textarea'
	);
	$options[] = array(
		'desc' => '当面付:开放平台-支付宝公钥',
		'class' => 'op-multicheck',
		'id' => 'official_alipay_publickey',
		'std' => '',
		'settings' => array(
			'rows' => 3
		),
		'type' => 'textarea'
	);

	/**虎皮椒 */
	$options[] = array(
		'id' => 'oauth_text',
		'class' => '',
		'html' => '<div class="options-notice"><div class="explain"><p>虎皮椒是迅虎网络旗下的支付产品，无需营业执照、无需企业，申请简单。适合个人站长申请，有一定的费用。</p>
		<li>支持扫码支付</li>
		<li>支付宝支持移动端跳转APP支付</li>
		<li>微信支持微信内支付</li>
		开通地址：<a target="_blank" href="https://admin.xunhupay.com/sign-up/12207.html" class="loginbtn">点击跳转</a></div></div>',
		'type' => "html"
	);

	$options[] = array(
		'name' => __('微信-虎皮椒', 'zib_language'),
		'desc' => '虎皮椒-微信:APP-ID',
		'class' => 'op-multicheck mini-heading',
		'id' => 'xunhupay_wechat_appid',
		'std' => '',
		'type' => 'text'
	);
	$options[] = array(
		'desc' => '虎皮椒-微信:app-secret',
		'class' => 'op-multicheck',
		'id' => 'xunhupay_wechat_appsecret',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('支付宝-虎皮椒', 'zib_language'),
		'desc' => '虎皮椒-支付宝:APP-ID',
		'class' => 'mini-heading',
		'id' => 'xunhupay_alipay_appid',
		'std' => '',
		'type' => 'text'
	);
	$options[] = array(
		'desc' => '虎皮椒-支付宝:APP-key',
		'class' => 'op-multicheck',
		'id' => 'xunhupay_alipay_appsecret',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'oauth_text',
		'class' => '',
		'html' => '<div class="options-notice"><div class="explain"><p>由于时间和测试条件等原因，更多的收款方式正在努力接入中...</p>
		<p>如果您有其他的收款API的帐号或权限，欢迎与我联系。只要方便测试，接入就很快</p>

		<a target="_blank" href="https://www.zibll.com/580.html" class="loginbtn">官方教程</a></div></div>',
		'type' => "html"
	);

	// ======================================================================================================================
	$options[] = array(
		'name' => __('自定义代码', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('自定义头部代码', 'zib_language'),
		'desc' => __('位于</head>之前，这部分代码是在主要内容显示之前加载，通常是CSS样式、自定义的<meta>标签、全站头部JS等需要提前加载的代码', 'zib_language'),
		'id' => 'headcode',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __('自定义CSS样式', 'zib_language'),
		'desc' => __('位于</head>之前，直接写样式代码，不用添加&lt;style&gt;标签', 'zib_language'),
		'id' => 'csscode',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __('自定义底部代码', 'zib_language'),
		'desc' => __('位于&lt;/body&gt;之前，这部分代码是在主要内容加载完毕加载，通常是JS代码', 'zib_language'),
		'id' => 'footcode',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __('网站统计代码', 'zib_language'),
		'desc' => __('位于底部，用于添加第三方流量数据统计代码，如：Google analytics、百度统计、CNZZ、51la，国内站点推荐使用百度统计，国外站点推荐使用Google analytics', 'zib_language'),
		'id' => 'trackcode',
		'std' => '',
		'type' => 'textarea'
	);




	$options[] = array(
		'name' => __('百度搜索资源', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('百度熊掌号', 'zib_language'),
		'id' => 'xzh_on',
		'std' => false,
		'desc' => ' 开启',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'xzh_appid',
		'desc' => ' 开启',
		'std' => '熊掌号 AppID',
		'type' => 'text'
	);
	/*
	$options[] = array(
		'name' => __('显示位置', 'zib_language'),
		'id' => 'xzh_render_head',
		'std' => false,
		'desc' => '吸顶bar',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'xzh_render_body',
		'std' => true,
		'desc' => '文章段落顶部',
		'type' => 'checkbox'
	);
*/

	$options[] = array(
		'name' => __('显示熊掌号', 'zib_language'),
		'id' => 'xzh_render_tail',
		'class' => 'mini-heading',
		'std' => true,
		'desc' => '文章内容底部',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __('添加JSON_LD数据', 'zib_language'),
		'id' => 'xzh_jsonld_single',
		'class' => 'mini-heading',
		'std' => true,
		'desc' => '文章页添加',
		'type' => 'checkbox'
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'xzh_jsonld_page',
		'std' => false,
		'desc' => '页面添加',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'xzh_jsonld_img',
		'desc' => '不添加图片',
		'class' => 'op-multicheck',
		'std' => false,
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __('自动推送', 'zib_language'),
		'id' => 'zib_baidu_push_js',
		'std' => false,
		'desc' => '全站链接自动推送',
		'question' => '采用百度最新自动推送接口，无需开启熊掌号，开启后自动将网站所有连接推送到百度，极大的提高收录速度。官方文档：https://ziyuan.baidu.com/college/courseinfo?id=267&page=2#h2_article_title12',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __('主动推送', 'zib_language'),
		'id' => 'xzh_post_on',
		'std' => false,
		'desc' => '文章发布主动推送',
		'question' => '使用此功能，请在百度资源获取推送秘钥，同时确保秘钥和网站对应',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '推送密钥 token',
		'id' => 'xzh_post_token',
		'desc' => '密钥获取：https://zn.baidu.com/linksubmit',
		'std' => '',
		'type' => 'text'
	);

	// =================
	$options[] = array(
		'name' => __('文档模式', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('开启文档模式', 'zib_language'),
		'id' => 'docs_mode',
		'type' => "",
		'question' => "文档模式适合帮助文档、使用文档等类型的文章使用。此模式会自动搜索二级分类及文章生成列表，请选择一级分类。为了良好的效果，文章分类请选择最后的子分类",
		'desc' => '请选择一级分类显示为文档模式'
	);

	$options[] = array(
		'id' => 'docs_mode_cats',
		'class' => 'op-multicheck',
		'desc' => __('', 'zib_language'),
		'options' => $options_categories,
		'type' => 'multicheck'
	);

	$options[] = array(
		'id' => 'docs_mode_exclude',
		'class' => 'op-multicheck',
		'question' => "开启之后，在网站首页不显示文档模式的相关内容，不影响小工具、其他位置以及首页置顶文章的显示",
		'type' => "checkbox",
		'std' => true,
		'desc' => '在首页排除此类内容'
	);

	// =================
	$options[] = array(
		'name' => __('扩展功能', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '外链重定向',
		'id' => 'go_link_s',
		'type' => "checkbox",
		'question' => "开启此功能后，非本站的链接将会重定向至内部链接，点击后延迟跳转，有利于SEO。如果对正常链接造成了影响，请关闭此功能",
		'std' => true,
		'desc' => '开启'
	);

	$options[] = array(
		'id' => 'go_link_post',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => '文章内容开启'
	);

	$options[] = array(
		'name' => '系统优化',
		'id' => 'no_categoty',
		'type' => "checkbox",
		'question' => "该功能和no-category插件作用相同，可停用no-category插件",
		'std' => false,
		'desc' => '分类url去除category'
	);

	$options[] = array(
		'id' => 'hide_admin_bar',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'question' => "开启后则不显示WordPress顶部黑条",
		'std' => true,
		'desc' => '关闭顶部admin_bar'
	);

	$options[] = array(
		'id' => 'disabled_pingback',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => '防pingback攻击'
	);
	$options[] = array(
		'id' => 'remove_emoji',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => '删除WordPress自带Emoji表情'
	);

	$options[] = array(
		'id' => 'remove_open_sans',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => '禁用Google字体'
	);
	$options[] = array(
		'id' => 'remove_more_wp_head',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => '清理多于的头部Meta标签'
	);

	$options[] = array(
		'class' => 'op-multicheck',
		'id' => 'newfilename',
		'type' => "checkbox",
		'question' => '上传文件自动重命名为随机英文名',
		'std' => false,
		'desc' => __('上传文件重命名', 'zib_language')
	);
	$options[] = array(
		'id' => 'display_wp_update',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => false,
		'desc' => '禁止WordPress检测更新'
	);
	$options[] = array(
		'id' => 'search_no_page',
		'class' => 'op-multicheck',
		'question' => '在搜索页只能搜索文章内容，不能搜索页面内容',
		'type' => "checkbox",
		'std' => false,
		'desc' => '搜索内容排除页面'
	);
	$options[] = array(
		'id' => 'no_repetition_name',
		'class' => 'op-multicheck',
		'question' => '前端修改呢称时候，不允许修改为已存在的呢称，不会影响后台修改',
		'type' => "checkbox",
		'std' => true,
		'desc' => '禁止重复昵称'
	);

	$options[] = array(
		'name' => '禁用古腾堡编辑器',
		'id' => 'close_gutenberg',
		'type' => "checkbox",
		'std' => false,
		'desc' => '开启后后台编辑器仍然使用4.9的编辑器'
	);

	$options[] = array(
		'name' => '代码高亮',
		'id' => 'highlight_kg',
		'type' => "checkbox",
		'std' => true,
		'desc' => '开启'
	);

	$options[] = array(
		'id' => 'highlight_hh',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => false,
		'desc' => '显示行号'
	);

	$options[] = array(
		'id' => 'highlight_btn',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'question' => '显示切换高亮、复制、新窗口打开三个扩展按钮',
		'std' => false,
		'desc' => '显示扩展按钮'
	);

	$options[] = array(
		'id' => 'highlight_maxheight',
		'class' => 'op-multicheck',
		'desc' => __('最大高度（为0则不限制）', 'zib_language'),
		'std' => 400,
		'settings' => array(
			'max' => 2000,
			'min' => 0,
			'step' => 20,
			'prefix' => '',
			'postfix' => 'px'
		),
		'type' => 'number'
	);

	$options[] = array(
		'id' => 'highlight_zt',
		'desc' => '全局默认主题',
		'question' => '主题预览地址： https://enlighterjs.org/Theme.Enlighter.html',
		'type' => 'select',
		'class' => 'op-multicheck mini',
		'std' => 'enlighter',
		'options' => array(
			'enlighter' => __('默认浅色主题'),
			'bootstrap4' => __('浅色：Bootstrap'),
			'classic' => __('浅色：Classic'),
			'beyond' => __('浅色：Beyond'),
			'mowtwo' => __('浅色：Mowtwo'),
			'eclipse' => __('浅色：Eclipse'),
			'droide' => __('浅色：Droide'),
			'minimal' => __('浅色：Minimal'),
			'rowhammer' => __('浅色：Rowhammer'),
			'godzilla' => __('浅色：Godzilla'),
			'dracula' => __('深色：Dracula'),
			'atomic' => __('深色：Atomic'),
			'monokai' => __('深色：Monokai')
		)
	);

	$options[] = array(
		'name' => __('时间格式', 'zib_language'),
		'id' => 'time_ago_s',
		'type' => "checkbox",
		'question' => '开启后时间格式化为：X分钟前，X小时前，X天前....',
		'std' => true,
		'desc' => '开启倒计时显示'
	);

	$options[] = array(
		'id' => 'time_format',
		'type' => "text",
		'question' => '需关闭上方倒计时功能后，自定义格式才能生效。时间格式接受标准时间格式，请注意控制长度！',
		'class' => 'op-multicheck mini',
		'std' => 'n月j日 H:i',
		'desc' => '自定义时间格式'
	);

	$options[] = array(
		'name' => __('页面修改', 'zib_language'),
		'class' => 'mini',
		'id' => 'post_article_url',
		'std' => get_page_by_title('发布文章')->ID,
		'desc' => '发布文章页面',
		'question' => __('如未创建，请新建空白页面选择：Zibll-写文章、投稿页面模板', 'zib_language'),
		'options' => $options_pages,
		'type' => 'select'
	);

	$options[] = array(
		'class' => 'mini op-multicheck',
		'id' => 'user_rp',
		'std' => get_page_by_title('找回密码')->ID,
		'desc' => '找回密码页面',
		'question' => __('如未创建，请新建空白页面选择：Zibll-密码找回模板', 'zib_language'),
		'options' => $options_pages,
		'type' => 'select'
	);


	$options[] = array(
		'name' => __('框架文件CDN托管', 'zib_language'),
		'id' => 'js_outlink',
		'std' => "no",
		'question' => '将核心框架JS文件和CSS文件托管到CDN，可提高加载速度。如果页面显示不正常，请关闭！',
		'type' => "radio",
		'options' => array(
			'no' => __('不托管', 'zib_language'),
			'baidu' => __('百度', 'zib_language'),
			'staticfile' => __('七牛云', 'zib_language'),
			'bootcdn' => __('BootCDN', 'zib_language'),
			'he' => __('框架来源站点', 'zib_language')
		)
	);

	$options[] = array(
		'name' => '布局宽度',
		'id' => 'layout_max_width',
		'std' => 1200,
		'question' => __('页面宽度已经经过精心的调整，非特殊需求请勿调整，宽度过大会造成显示不协调', 'zib_language'),
		'desc' => __('页面全局宽度', 'zib_language'),
		'settings' => array(
			'max' => 1800,
			'min' => 1200,
			'step' => 50,
			'prefix' => '',
			'postfix' => 'px'
		),
		'type' => 'number'
	);

	$options[] = array(
		'name' => '前端上传限制',
		'id' => 'up_max_size',
		'std' => 4,
		'desc' => __('允许上传的最大图像大小（单位M,为0则不限制）', 'zib_language'),
		'settings' => array(
			'max' => 10,
			'min' => 0,
			'step' => 0.5,
			'prefix' => '',
			'postfix' => 'M'
		),
		'type' => 'number'
	);

	$options[] = array(
		'name' => __('注册昵称限制', 'zib_language'),
		'desc' => __('用户不能使用包含这些关键字的昵称注册(请用逗号或换行分割)', 'zib_language'),
		'id' => 'user_nickname_out',
		'std' => "赌博,博彩,彩票,性爱,色情,做爱,爱爱,淫秽,傻b,妈的,妈b,admin,test",
		'type' => 'textarea'
	);

	// =================
	$options[] = array(
		'name' => __('邮件功能', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __('邮件设置', 'zib_language'),
		'id' => 'email_payment_order',
		'type' => "checkbox",
		'std' => true,
		'desc' => '用户支付订单后 向用户发送邮件'
	);

	$options[] = array(
		'id' => 'email_comment_approved',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => '评论通过审核后 向用户发送邮件'
	);
	$options[] = array(
		'id' => 'email_newpost_to_publish',
		'class' => 'op-multicheck',
		'type' => "checkbox",
		'std' => true,
		'desc' => '投稿通过审核后 向用户发送邮件'
	);

	$options[] = array(
		'name' => __('其它设置', 'zib_language'),
		'id' => 'mail_showname',
		'class' => 'mini mini-heading',
		'desc' => '自定义发件人昵称（仅部分邮箱服务器有效）',
		'std' => get_bloginfo('name'),
		'type' => 'text'
	);

	$options[] = array(
		'desc' => __('邮件底部添加额外内容（支持html代码）', 'zib_language'),
		'question' => __('由于不同邮件服务商的代码支持不同，请使用较为基础的html代码', 'zib_language'),
		'class' => 'op-multicheck',
		'id' => 'mail_more_content',
		'std' => '<a href="' . get_bloginfo('url') . '">访问网站</a> | 
<a href="#">与我联系</a>',
		'type' => 'textarea',
		'settings' => array(
			'rows' => 3
		),
	);

	$options[] = array(
		'name' => '邮件SMTP',
		'id' => 'mail_smtps',
		'type' => "checkbox",
		'std' => false,
		'desc' => '开启'
	);

	$options[] = array(
		'id' => 'description_text',
		'class' => 'op-multicheck',
		'html' => '<div class="options-notice"><div class="explain"><p>WordPress配置SMTP邮箱，解决邮件发送问题。功能和SMTP插件一致，所以！不能和其他SMTP插件一起开启！</p>
		<a target="_blank" href="https://www.zibll.com/720.html" class="loginbtn">官网教程</a></div></div>',
		'type' => "html"
	);

	$options[] = array(
		'name' => 'SMTP配置',
		'id' => 'mail_name',
		'desc' => '发信人邮箱账号',
		'class' => 'mini op-multicheck mini-heading',
		'std' => '88888888@qq.com',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'mail_passwd',
		'class' => 'mini op-multicheck',
		'desc' => 'SMTP服务邮箱密码（此密码非邮箱密码，一般需要单独开启）',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'mail_host',
		'class' => 'mini',
		'desc' => '邮件服务器地址',
		'std' => 'smtp.qq.com',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'mail_port',
		'class' => 'mini op-multicheck',
		'desc' => 'SMTP服务器端口号',
		'std' => '465',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'mail_smtpauth',
		'type' => "checkbox",
		'class' => 'op-multicheck',
		'std' => true,
		'desc' => '启用SMTPAuth服务'
	);

	$options[] = array(
		'id' => 'mail_smtpsecure',
		'class' => 'mini op-multicheck',
		'desc' => '加密方式（SMTPSecure）',
		'std' => 'ssl',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __('发件测试', 'zib_language'),
		'id' => 'description_text',
		'html' => zib_ohtml_email(),
		'type' => "html"
	);

	$options[] = array(
		'name' => __((zib_is_update() ? '主题更新' : '主题文档'), 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'id' => 'zib_word',
		'src' => get_template_directory_uri() . '/framework/code/zibll-word.php',
		'type' => 'html-src'
	);

	$options[] = array(
		'name' => __('主题授权', 'zib_language'),
		'type' => 'heading'
	);

	$options[] = array(
		'id' => 'zib_word',
		'src' => get_template_directory_uri() . '/framework/code/authorization.php',
		'type' => 'html-src'
	);

	return $options;
}
