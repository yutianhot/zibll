<?php
// 删除多于头部代码


if (_pz('remove_more_wp_head',true)) {
    function remove_more_wp_head()
    {
		remove_action('wp_head', 'feed_links_extra', 3); // 移除feed
		remove_action('wp_head', 'feed_links', 2); // 移除feed
		remove_action('wp_head', 'rsd_link'); // 移除离线编辑器开放接口
		remove_action('wp_head', 'wlwmanifest_link'); // 移除离线编辑器开放接口
		remove_action('wp_head', 'index_rel_link'); // 移除Index link
		remove_action('wp_head', 'parent_post_rel_link', 10, 0); // 移除Prev link
		remove_action('wp_head', 'start_post_rel_link', 10, 0); // 移除Start link
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // 移除与当前帖子相邻的帖子的关系链接。
		remove_action('wp_head', 'wp_generator'); // 移除WordPress版本信息
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);// 移除与当前帖子相邻的帖子的关系链接
		remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
		remove_action('wp_head', 'wp_oembed_add_discovery_links', 10, 1);
		remove_action('wp_head', 'rel_canonical', 10, 0);
    }
    add_action('after_setup_theme', 'remove_more_wp_head'); //清除wp_head带入的meta标签
}

// 注册头部代码
add_action('wp_head', 'zib_head');
function zib_head() {
	zib_post_views_record();
	zib_head_favicon();
	zib_head_css();
	zib_head_code();
	zib_head_other();
}
function zib_seo() {
	zib_title();
	zib_keywords();
	zib_description();
}
//注册阅读量
function zib_post_views_record() {
	if (is_singular()) {
		global $post;
		$post_ID = $post->ID;
		if ($post_ID) {
			$post_views = (int) get_post_meta($post_ID, 'views', true);
			if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
				add_post_meta($post_ID, 'views', 1, true);
			}
		}
	}
}
function zib_head_other() {
		echo "<meta name='apple-mobile-web-app-title' content='".get_bloginfo('name')."'>";
		echo '<!--[if IE]><script src="'.get_stylesheet_directory_uri().'/js/libs/html5.min.js"></script><![endif]-->';
		echo qj_dh_css();
}
function zib_head_favicon() {
	if (_pz('favicon')) {
		echo "<link rel='shortcut icon' href='". _pz('favicon') ."'>";
		echo "<link rel='icon' href='". _pz('favicon') ."'>";
	}else{
		echo "<link rel='shortcut icon' href='".home_url( '/' )."favicon.ico'>";
		echo "<link rel='icon' href='".home_url( '/' )."favicon.ico'>";
	}
	if (_pz('iconpng')) {
		echo "<link rel='apple-touch-icon-precomposed' href='". _pz('iconpng') ."'>";
		echo "<meta name='msapplication-TileImage' content='". _pz('iconpng') ."'>";
	}
}

function zib_head_css() {
	$styles = '';

	$color = '';
	if (_pz('theme_skin')) {
		$color = _pz('theme_skin');
	}

	if (_pz('theme_skin_custom')) {
		$color = substr(_pz('theme_skin_custom'), 1);
	}
	$shadow='';
	if ($color) {
		$shadow = hex_to_rgba('#'.$color,'.4');
	}

	$var ='';
	$var .=$color?'--theme-color:#'.$color.';':'';
	$var .=$shadow?'--focus-shadow-color:'.$shadow.';':'';
	$mian_r = _pz('theme_main_radius',8);
	if($mian_r != 8){
		$var .= '--main-radius:'.$mian_r.'px;';
	}
	
	$var .= '--mian-max-width:'._pz('layout_max_width',1200).'px;';
	if (_pz('header_theme_custom')) {
		$h_bg = 'var(--main-bg-color)';$h_c = 'var(--main-color)';
		if (_pz('header_theme_bg')=='ff648f') $h_bg = 'linear-gradient(135deg, #f546e8 10%, #ff648f 100%)';
		if (_pz('header_theme_bg')=='c246f5') $h_bg = 'linear-gradient(135deg, #c246f5 10%, #a064ff 100%)';
		if (_pz('header_theme_bg')=='469cf5') $h_bg = 'linear-gradient(135deg, #469cf5 10%, #6485ff 100%)';
		if (_pz('header_theme_bg')=='27bf41') $h_bg = 'linear-gradient(135deg, #44c38f 10%, #27bf41 100%)';
		if (_pz('header_theme_bg')=='fd6b4e') $h_bg = 'linear-gradient(135deg, #ec8a51 10%, #fd6b4e 100%)';
		if (_pz('header_theme_bg')=='2d2422') $h_bg = 'linear-gradient(135deg, #4a4b50 10%, #2d2422 100%)';

		if (_pz('header_theme_bg_custom')) $h_bg = _pz('header_theme_bg_custom');

		if (_pz('header_theme_color')=='fff') $h_c = '#fff';
		if (_pz('header_theme_color')=='555') $h_c = '#555';

		if (_pz('header_theme_color_custom')) $h_c = _pz('header_theme_color_custom');
		$styles .= '.header{--header-bg:'.$h_bg.';--header-color:'.$h_c.';}';
	}

	if (_pz('footer_theme_custom')) {
		$h_bg = 'var(--main-bg-color)';$h_c = 'var(--muted-2-color)';
		if (_pz('footer_theme_bg')=='ff648f') $h_bg = 'linear-gradient(135deg, #f546e8 10%, #ff648f 100%)';
		if (_pz('footer_theme_bg')=='c246f5') $h_bg = 'linear-gradient(135deg, #c246f5 10%, #a064ff 100%)';
		if (_pz('footer_theme_bg')=='469cf5') $h_bg = 'linear-gradient(135deg, #469cf5 10%, #6485ff 100%)';
		if (_pz('footer_theme_bg')=='27bf41') $h_bg = 'linear-gradient(135deg, #44c38f 10%, #27bf41 100%)';
		if (_pz('footer_theme_bg')=='fd6b4e') $h_bg = 'linear-gradient(135deg, #ec8a51 10%, #fd6b4e 100%)';
		if (_pz('footer_theme_bg')=='2d2422') $h_bg = 'linear-gradient(135deg, #4a4b50 10%, #2d2422 100%)';

		if (_pz('footer_theme_bg_custom')) $h_bg = _pz('footer_theme_bg_custom');

		if (_pz('footer_theme_color_custom')) $h_c = _pz('footer_theme_color_custom');

		$styles .= '.footer{--footer-bg:'.$h_bg.';--footer-color:'.$h_c.';}';
	}






	$styles .= ':root{'.$var.'}';

	if ((is_single() || is_page()) && _pz('post_p_indent_s')) {
		$styles .= '.article-content p{text-indent:30px}';
	}

	if (_pz('csscode')) {
		$styles .= _pz('csscode');
	}

	if( _pz('highlight_kg') && _pz('highlight_maxheight')){
		$styles .= '.enlighter-default{max-height:'._pz('highlight_maxheight').'px;overflow-y:auto !important;}';
	}

	if( _pz('highlight_kg') && !_pz('highlight_btn')){
		$styles .= '.enlighter-toolbar{display:none !important;}';
	}

	if( _pz('item_heading_bold')){
		$styles .= '.posts-item .item-heading>a {font-weight: bold;color: unset;}';
	}

	$styles .= '@media (max-width:640px) {
		.meta-right .meta-'._pz('list_meta_show').'{
			display: unset !important;
		}
	}';

	if ($styles) {
		echo '<style>' . $styles . '</style>';
	}
}

function zib_head_code() {
	if (_pz('headcode')) {
		echo "\n<!--HEADER_CODE_START-->\n" . _pz('headcode') . "\n<!--HEADER_CODE_END-->\n";
	}
}

function zib_title($meta=true) {
	global $new_title;
	if ($new_title) return $new_title;

	global $paged;

	$html = '';
	$t = trim(wp_title('', false));

	if ((is_single() || is_page()) && get_the_subtitle(false)) {
		$t .= get_the_subtitle(false);
	}

	if ($t) {
		$html .= $t . _get_delimiter();
	}

	$html .= get_bloginfo('name');

	if (is_home()) {
		if (_pz('hometitle')) {
			$html = _pz('hometitle');
			if ($paged > 1) {
				$html .= _get_delimiter() . '最新发布';
			}
		} else {
			if ($paged > 1) {
				$html .= _get_delimiter() . '最新发布';
			} else if (get_option('blogdescription')) {
				$html .= _get_delimiter() . get_option('blogdescription');
			}
		}
	}

	if (is_category() || is_tax( 'topics' )) {
		global $wp_query;
		$cat_ID = get_queried_object_id();
		$cat_tit = _get_tax_meta($cat_ID, 'title');
		if ($cat_tit) {
			$html = $cat_tit;
		}
	}

	if ((is_single() || is_page()) && _pz('post_keywords_description_s')) {
		global $post;
		$post_ID = $post->ID;
		$seo_title = trim(get_post_meta($post_ID, 'title', true));
		if ($seo_title) $html = $seo_title;
	}

	if ($paged > 1) {
		$html .= _get_delimiter() . '第' . $paged . '页';
	}
	if ($meta){
	echo '<title>'.$html.'</title>';
	} else {
		return $html;
	}
}
//关键字
function zib_keywords($meta = true) {
	global $new_keywords;
	if ($new_keywords) {
		echo "<meta name=\"keywords\" content=\"{$new_keywords}\">\n";
		return;
	}

	global $s, $post;
	$keywords = '';
	if (is_singular()) {
		if (get_the_tags($post->ID)) {
			foreach (get_the_tags($post->ID) as $tag) {
				$keywords .= $tag->name . ', ';
			}
		}
		foreach (get_the_category($post->ID) as $category) {
			$keywords .= $category->cat_name . ', ';
		}
		$keywords = substr_replace($keywords, '', -2);
		$the = trim(get_post_meta($post->ID, 'keywords', true));
		if ($the) {
			$keywords = $the;
		}
	} elseif (is_home()) {
		$keywords = _pz('keywords');
	} elseif (is_tag()) {
		$keywords = single_tag_title('', false);
	} elseif (is_category()|| is_tax( 'topics' )) {

		global $wp_query;
		$cat_ID = get_queried_object_id();
		$keywords = _get_tax_meta($cat_ID, 'keywords');
		if (!$keywords) {
			$keywords = single_cat_title('', false);
		}

	} elseif (is_search()) {
		$keywords = esc_html($s, 1);
	} else {
		$keywords = trim(wp_title('', false));
	}
	if ($keywords) {

		if ($meta) {
			echo "<meta name=\"keywords\" content=\"{$keywords}\">\n";
		} else {
			return  $keywords;
		}
		return;

	}
}

//网站描述
function zib_description($meta = true) {
	global $new_description;
	if ($new_description) {
		if ($meta) {
		echo  "<meta name=\"description\" content=\"$new_description\">\n";
		return;
		} else {
		return  $new_description;
		}
	}

	global $s, $post;
	$description = '';
	$blog_name = get_bloginfo('name');
	if (is_singular()) {

		$description = zib_get_excerpt($limit = 210, $after = '...');

		$description = mb_substr($description, 0, 200, 'utf-8');

		if (!$description) {
			$description = $blog_name . "-" . trim(wp_title('', false));
		}

		$the = trim(get_post_meta($post->ID, 'description', true));
		if ($the) {
			$description = $the;
		}

	} elseif (is_home()) {
		$description = _pz('description');
	} elseif (is_tag()) {
		$description = trim(strip_tags(tag_description()));
	} elseif (is_category() || is_tax( 'topics' )) {

		global $wp_query;
		$cat_ID = get_queried_object_id();
		$description = _get_tax_meta($cat_ID, 'description');
		if (!$description) {
			$description = trim(strip_tags(category_description()));
		}

	} elseif (is_archive()) {
		$description = $blog_name . "'" . trim(wp_title('', false)) . "'";
	} elseif (is_search()) {
		$description = $blog_name . ": '" . esc_html($s, 1) . "' 的搜索結果";
	} else {
		$description = $blog_name . "'" . trim(wp_title('', false)) . "'";
	}

		if ($meta) {
			echo  "<meta name=\"description\" content=\"$description\">\n";
		} else {
			return  $description;
		}
		return;
}
//全局loading动画
function qj_dh_nr()
{
	$dh_nr = '';

	if (_pz('qj_dh_xs') == 'no2') {
		$dh_nr = '<div class="qjdh_no2"></div>';
	} elseif (_pz('qj_dh_xs') == 'no3') {
		$dh_nr = '<div class="qjdh_no3"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
	} elseif (_pz('qj_dh_xs') == 'no4') {
		$dh_nr = '<div class="qjdh_no4"></div>';
	} elseif (_pz('qj_dh_xs') == 'no5') {
		$dh_nr = '<div class="qjdh_no5"><div></div><div></div><div></div></div>';
	} elseif (_pz('qj_dh_xs') == 'no6') {
		$dh_nr = '<div class="qjdh_no6"><div></div><div></div><div></div></div>';
	} elseif (_pz('qj_dh_xs') == 'no7') {
		$dh_nr = '<div class="qjdh_no7"></div>';
	} elseif (_pz('qj_dh_xs') == 'no8') {
		$dh_nr = '<div class="qjdh_no8"><div></div><div></div><div></div><div></div></div>';
	} elseif (_pz('qj_dh_xs') == 'no9') {
		$dh_nr = '<div class="qjdh_no9"><div></div><div></div><div></div><div></div><div></div></div>';
	} elseif (_pz('qj_dh_xs') == 'no10') {
		$dh_nr = '<div class="qjdh_no10"><div></div><div></div><div></div></div>';
	}

	if (_pz('qj_loading')) {
		return '<div class="qjl qj_loading" style="position: fixed;background:var(--main-bg-color);width: 100%;margin-top:-150px;height:300%;z-index: 99999999"><div style="position:fixed;top:0;left:0;bottom:0;right:0;display:flex;align-items:center;justify-content:center">' . $dh_nr . '</div></div>';
	}
};
function qj_dh_css()
{
	if (_pz('qj_dh_xs') == 'no2') {
		$dh_css = '.qjdh_no2{width:50px;height:50px;border:5px solid transparent;border-radius:50%;border-top-color:#2aab69;border-bottom-color:#2aab69;animation:huan-rotate 1s cubic-bezier(0.7, 0.1, 0.31, 0.9) infinite}@keyframes huan-rotate{0%{transform:rotate(0)}to{transform:rotate(360deg)}}';
	} elseif (_pz('qj_dh_xs') == 'no3') {
		$dh_css = '.qjdh_no3{position:relative;top:-10px;left:-4px;transform:scale(1)}.qjdh_no3>div:nth-child(1){top:20px;left:0;-webkit-animation:line-spin-fade-loader 1.2s -.84s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.84s infinite ease-in-out}.qjdh_no3>div:nth-child(2){top:13.64px;left:13.64px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:line-spin-fade-loader 1.2s -.72s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.72s infinite ease-in-out}.qjdh_no3>div:nth-child(3){top:0;left:20px;-webkit-transform:rotate(90deg);transform:rotate(90deg);-webkit-animation:line-spin-fade-loader 1.2s -.6s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.6s infinite ease-in-out}.qjdh_no3>div:nth-child(4){top:-13.64px;left:13.64px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:line-spin-fade-loader 1.2s -.48s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.48s infinite ease-in-out}.qjdh_no3>div:nth-child(5){top:-20px;left:0;-webkit-animation:line-spin-fade-loader 1.2s -.36s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.36s infinite ease-in-out}.qjdh_no3>div:nth-child(6){top:-13.64px;left:-13.64px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:line-spin-fade-loader 1.2s -.24s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.24s infinite ease-in-out}.qjdh_no3>div:nth-child(7){top:0;left:-20px;-webkit-transform:rotate(90deg);transform:rotate(90deg);-webkit-animation:line-spin-fade-loader 1.2s -.12s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.12s infinite ease-in-out}.qjdh_no3>div:nth-child(8){top:13.64px;left:-13.64px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:line-spin-fade-loader 1.2s 0s infinite ease-in-out;animation:line-spin-fade-loader 1.2s 0s infinite ease-in-out}.qjdh_no3>div{position:absolute;margin:2px;width:4px;width:5px;height:35px;height:15px;border-radius:2px;background-color:#1487ff;-webkit-animation-fill-mode:both;animation-fill-mode:both}@-webkit-keyframes line-spin-fade-loader{50%{opacity:.3}to{opacity:1}}@keyframes line-spin-fade-loader{50%{opacity:.3}to{opacity:1}}';
	} elseif (_pz('qj_dh_xs') == 'no4') {
		$dh_css = '.qjdh_no4{width:50px;height:50px;background-color:#1b96b9;-webkit-animation:rotateplane 1s infinite ease-in-out;animation:rotateplane 1s infinite ease-in-out}@-webkit-keyframes rotateplane{0%{-webkit-transform:perspective(120px)}50%{-webkit-transform:perspective(120px) rotateY(180deg)}to{-webkit-transform:perspective(120px) rotateY(180deg) rotateX(180deg)}}@keyframes rotateplane{0%{transform:perspective(120px) rotateX(0deg) rotateY(0deg);-webkit-transform:perspective(120px) rotateX(0deg) rotateY(0deg)}50%{transform:perspective(120px) rotateX(-180.1deg) rotateY(0deg);-webkit-transform:perspective(120px) rotateX(-180.1deg) rotateY(0deg)}to{transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg);-webkit-transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg)}}';
	} elseif (_pz('qj_dh_xs') == 'no5') {
		$dh_css = '.qjdh_no5{transform:scale(1)}.qjdh_no5>div:nth-child(1){-webkit-animation:ball-pulse-sync .6s -.14s infinite ease-in-out;animation:ball-pulse-sync .6s -.14s infinite ease-in-out}.qjdh_no5>div:nth-child(2){-webkit-animation:ball-pulse-sync .6s -70ms infinite ease-in-out;animation:ball-pulse-sync .6s -70ms infinite ease-in-out}.qjdh_no5>div:nth-child(3){-webkit-animation:ball-pulse-sync .6s 0s infinite ease-in-out;animation:ball-pulse-sync .6s 0s infinite ease-in-out}.qjdh_no5>div{background-color:#ec6a21;width:15px;height:15px;border-radius:100%;margin:4px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block}@keyframes ball-pulse-sync{33%{-webkit-transform:translateY(10px);transform:translateY(10px)}66%{-webkit-transform:translateY(-10px);transform:translateY(-10px)}to{-webkit-transform:translateY(0);transform:translateY(0)}}';
	} elseif (_pz('qj_dh_xs') == 'no6') {
		$dh_css = '.qjdh_no6{transform:scale(1) translateY(-30px)}.qjdh_no6>div:nth-child(2){-webkit-animation-delay:-.4s;animation-delay:-.4s}.qjdh_no6>div:nth-child(3){-webkit-animation-delay:-.2s;animation-delay:-.2s}.qjdh_no6>div{position:absolute;top:0;left:-30px;margin:2px;margin:0;width:15px;width:60px;height:15px;height:60px;border-radius:100%;background-color:#ff3cb2;opacity:0;-webkit-animation-fill-mode:both;animation-fill-mode:both;-webkit-animation:ball-scale-multiple 1s 0s linear infinite;animation:ball-scale-multiple 1s 0s linear infinite}@-webkit-keyframes ball-scale-multiple{0%{opacity:0;-webkit-transform:scale(0);transform:scale(0)}5%{opacity:1}to{-webkit-transform:scale(1);transform:scale(1)}}@keyframes ball-scale-multiple{0%,to{opacity:0}0%{-webkit-transform:scale(0);transform:scale(0)}5%{opacity:1}to{opacity:0;-webkit-transform:scale(1);transform:scale(1)}}';
	} elseif (_pz('qj_dh_xs') == 'no7') {
		$dh_css = '.qjdh_no7{position:absolute;top:0;right:0;bottom:0;left:0;margin:auto;width:50px;height:50px}.qjdh_no7:before{top:59px;height:5px;border-radius:50%;background:#000;opacity:.1;animation:box-loading-shadow .5s linear infinite}.qjdh_no7:after,.qjdh_no7:before{position:absolute;left:0;width:50px;content:""}.qjdh_no7:after{top:0;height:50px;border-radius:3px;background:#15c574;animation:box-loading-animate .5s linear infinite}@keyframes box-loading-animate{17%{border-bottom-right-radius:3px}25%{transform:translateY(9px) rotate(22.5deg)}50%{border-bottom-right-radius:40px;transform:translateY(18px) scale(1,.9) rotate(45deg)}75%{transform:translateY(9px) rotate(67.5deg)}to{transform:translateY(0) rotate(90deg)}}@keyframes box-loading-shadow{0%,to{transform:scale(1,1)}50%{transform:scale(1.2,1)}}';
	} elseif (_pz('qj_dh_xs') == 'no8') {
		$dh_css = '.qjdh_no8{height:50px;width:50px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:l_xx 1.5s infinite;animation:l_xx 1.5s infinite}.qjdh_no8>div{width:25px;height:25px;background-color:#f54a71;float:left}.qjdh_no8>div:nth-child(1){-webkit-animation:o_one 1.5s infinite;animation:o_one 1.5s infinite}.qjdh_no8>div:nth-child(2){-webkit-animation:o_two 1.5s infinite;animation:o_two 1.5s infinite}.qjdh_no8>div:nth-child(3){-webkit-animation:o_three 1.5s infinite;animation:o_three 1.5s infinite}.qjdh_no8>div:nth-child(4){-webkit-animation:o_four 1.5s infinite;animation:o_four 1.5s infinite}@-webkit-keyframes l_xx{to{-webkit-transform:rotate(-45deg)}}@-webkit-keyframes o_one{30%{-webkit-transform:translate(0,-50px) rotate(-180deg)}to{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes o_one{30%{transform:translate(0,-50px) rotate(-180deg);-webkit-transform:translate(0,-50px) rotate(-180deg)}to{transform:translate(0,0) rotate(-180deg);-webkit-transform:translate(0,0) rotate(-180deg)}}@-webkit-keyframes o_two{30%{-webkit-transform:translate(50px,0) rotate(-180deg)}to{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes o_two{30%{transform:translate(50px,0) rotate(-180deg);-webkit-transform:translate(50px,0) rotate(-180deg)}to{transform:translate(0,0) rotate(-180deg);-webkit-transform:translate(0,0) rotate(-180deg)}}@-webkit-keyframes o_three{30%{-webkit-transform:translate(-50px,0) rotate(-180deg)}to{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes o_three{30%{transform:translate(-50px,0) rotate(-180deg);-webkit-transform:translate(-50px,0) rotate(-180deg)}to{transform:translate(0,0) rotate(-180deg);-webkit-transform:rtranslate(0,0) rotate(-180deg)}}@-webkit-keyframes o_four{30%{-webkit-transform:translate(0,50px) rotate(-180deg)}to{-webkit-transform:translate(0,0) rotate(-180deg)}}@keyframes o_four{30%{transform:translate(0,50px) rotate(-180deg);-webkit-transform:translate(0,50px) rotate(-180deg)}to{transform:translate(0,0) rotate(-180deg);-webkit-transform:translate(0,0) rotate(-180deg)}}';
	} elseif (_pz('qj_dh_xs') == 'no9') {
		$dh_css = '.qjdh_no9{transform:scale(1)}.qjdh_no9>div{display:inline-block;margin:5px;width:4px;height:35px;border-radius:2px;background-color:#11d4c5;-webkit-animation-fill-mode:both;animation-fill-mode:both;-webkit-animation:line-scale-pulse-out .9s -.6s infinite cubic-bezier(.85,.25,.37,.85);animation:line-scale-pulse-out .9s -.6s infinite cubic-bezier(.85,.25,.37,.85)}.qjdh_no9>div:nth-child(2),.qjdh_no9>div:nth-child(4){-webkit-animation-delay:-.4s!important;animation-delay:-.4s!important}.qjdh_no9>div:nth-child(1),.qjdh_no9>div:nth-child(5){-webkit-animation-delay:-.2s!important;animation-delay:-.2s!important}@-webkit-keyframes line-scale-pulse-out{0%{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}}@keyframes line-scale-pulse-out{0%,to{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}to{-webkit-transform:scaley(1);transform:scaley(1)}}';
	} elseif (_pz('qj_dh_xs') == 'no10') {
		$dh_css = '.qjdh_no10{position:relative;transform:translate(-29.99px,-37.51px)}.qjdh_no10>div:nth-child(1){animation-name:ql-1}.qjdh_no10>div:nth-child(1),.qjdh_no10>div:nth-child(2){animation-delay:0;animation-duration:2s;animation-timing-function:ease-in-out;animation-iteration-count:infinite}.qjdh_no10>div:nth-child(2){animation-name:ql-2}.qjdh_no10>div:nth-child(3){animation-name:ql-3;animation-delay:0;animation-duration:2s;animation-timing-function:ease-in-out;animation-iteration-count:infinite}.qjdh_no10>div{position: absolute;width:18px;height:18px;border-radius:100%;background:#ff00a3}.qjdh_no10>div:nth-of-type(1){top:50px}.qjdh_no10>div:nth-of-type(2){left:25px}.qjdh_no10>div:nth-of-type(3){top:50px;left:50px}@keyframes ql-1{33%{transform:translate(25px,-50px)}66%{transform:translate(50px,0)}to{transform:translate(0,0)}}@keyframes ql-2{33%{transform:translate(25px,50px)}66%{transform:translate(-25px,50px)}to{transform:translate(0,0)}}@keyframes ql-3{33%{transform:translate(-50px,0)}66%{transform:translate(-25px,-50px)}to{transform:translate(0,0)}}';
	}

	if (_pz('qj_loading') && _pz('qj_dh_xs') && _pz('qj_dh_xs') != 'no1') {
		return '<style type="text/css" id="qj_dh_css">' . $dh_css . '</style>';
	}

	return;
};
