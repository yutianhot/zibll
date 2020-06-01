<?php
function zib_share()
{
	echo zib_get_share();
}

function zib_get_share($tooltip = true)
{
	$title = zib_title(false);
	$content = zib_description(false);
	$pic = zib_share_img();

	$url = get_permalink();

	$tooltip = $tooltip ? ' data-toggle="tooltip"' : '';
	$qzone = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $url . '&#38;title=' . $title . '&#38;pics=' . $pic . '&#38;summary=' . $content;
	$weibo = 'http://service.weibo.com/share/share.php?url=' . $url . '&#38;title=' . $title . '&#38;pic=' . $pic . '&#38;searchPic=false';
	$renren = 'http://widget.renren.com/dialog/share?resourceUrl=' . $url . '&#38;srcUrl=' . $url . '&#38;title=' . $title . '&#38;description=' . $content;
	$douban = 'http://www.douban.com/share/service?href=' . $url . '&#38;name=' . $title . '&#38;text=' . $content . '&#38;image=' . $pic;
	$facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $url . '&#38;t=' . $title . '&#38;pic=' . $pic;
	$twitter = 'https://twitter.com/intent/tweet?text=' . $title . '&#38;url=' . $url;
	$linkedin = 'https://www.linkedin.com/shareArticle?title=' . $title . '&#38;summary=' . $content . '&#38;mini=true&#38;url=' . $url . '&#38;ro=true';
	$share = '';
	$share_img = array(
		'icon'   => '<svg class="icon" viewBox="0 0 1024 1024"><path d="M319.658667 466.432l211.797333 223.5392 177.7664-102.161067L887.466667 708.181333v98.082134H153.6v-127.249067z" fill="#CFDEFF"></path><path d="M860.16 197.973333H180.7872a27.306667 27.306667 0 1 1 0-54.613333h679.492267a54.408533 54.408533 0 0 1 54.493866 54.3232v594.500267a54.408533 54.408533 0 0 1-54.493866 54.3232H180.7872A54.408533 54.408533 0 0 1 126.293333 792.183467V387.976533a27.306667 27.306667 0 1 1 54.613334 0V791.893333h679.253333V197.973333z" fill="#2C6EFF" p-id="10347"></path><path d="M466.176 380.381867a27.306667 27.306667 0 1 1 0-54.613334h298.973867a27.306667 27.306667 0 1 1 0 54.613334H466.176zM588.475733 528.9984a27.306667 27.306667 0 1 1 0-54.613333h171.963734a27.306667 27.306667 0 1 1 0 54.613333H588.475733z" fill="#FFA200" p-id="10348"></path></svg>',
	);
	$qq = array(
		'icon'   => '<svg class="icon" viewBox="0 0 1024 1024"><path d="M909.937778 664.803556c-18.432-110.876444-95.829333-183.523556-95.829333-183.523556 11.064889-100.664889-29.496889-118.528-29.496889-118.528C776.106667 51.313778 517.432889 56.775111 512 56.917333c-5.432889-0.142222-264.135111-5.603556-272.611556 305.863111 0 0-40.561778 17.863111-29.496889 118.528 0 0-77.397333 72.647111-95.829333 183.523556 0 0-9.841778 187.335111 88.462222 22.954667 0 0 22.129778 62.435556 62.663111 118.528 0 0-72.504889 25.486222-66.332444 91.761778 0 0-2.474667 73.898667 154.823111 68.835556 0 0 110.563556-8.903111 143.758222-57.344l29.240889 0c33.166222 48.440889 143.758222 57.344 143.758222 57.344 157.240889 5.091556 154.794667-68.835556 154.794667-68.835556 6.115556-66.247111-66.332444-91.761778-66.332444-91.761778 40.533333-56.092444 62.663111-118.528 62.663111-118.528C919.751111 852.167111 909.937778 664.803556 909.937778 664.803556L909.937778 664.803556zM909.937778 664.803556" fill="#1296db"></path></svg>',
		'href'   => 'http://connect.qq.com/widget/shareqq/index.html?url=' . $url . '&#38;desc=' . $content . '&#38;title=' . $title . '&#38;pics=' . $pic,
	);
	if (_pz('share_s') && _pz('share_code')) {
		$share =  _pz('share_code');
	} else {
		$share .= '<a class="bds qzone" target="_blank"' . $tooltip . ' title="分享到QQ空间" href="' . $qzone . '"><svg t="1555860145812" class="icon" viewBox="0 0 1159 1024"><path d="M1159.511619 372.253643c0-12.905868 0-9.276093-12.905869-9.276093h-346.845214l-51.220166-91.551004L607.382434 0h-25.811737l-128.252068 271.426546L403.308389 362.97755H0v5.646318l72.59551 51.220165 201.654195 179.875542-45.977157 411.374557c0 12.905868 2.823159 12.905868 28.231587 12.905868l335.149272-205.687278 334.745963 205.687278c12.905868 0 13.309177 0 26.215045-12.905868l-64.126034-411.374557 192.78141-165.35644zM312.564002 766.285939l372.253643-303.691217L337.97243 403.308389h526.720756l-322.646711 290.785349L875.985821 766.285939z" fill="#eab32c"></path></svg></a>';
		$share .=  '<a class="bds tsina" target="_blank"' . $tooltip . ' title="分享到新浪微博" href="' . $weibo . '"><svg t="1555857500786" class="icon" viewBox="0 0 1194 1024"><path d="M850.801 524.863c-41.947-8.678-21.697-30.375-21.697-30.375s40.501-66.536-8.678-115.716c-60.751-60.751-206.842 7.233-206.842 7.233-56.411 17.358-40.501-7.233-33.268-50.626 0-50.626-17.358-135.966-166.342-85.34-146.091 52.072-273.379 229.986-273.379 229.986-89.68 118.609-78.109 209.736-78.109 209.736 21.697 202.503 237.218 257.468 403.559 270.486 176.467 14.464 412.238-60.751 484.56-214.074 73.769-151.877-57.858-212.628-99.805-221.307M480.51 903.833c-175.020 7.233-315.326-79.554-315.326-196.717s140.305-209.736 315.326-218.413c173.573-7.233 315.326 65.090 315.326 180.806 0 117.162-141.752 225.646-315.326 234.324z" fill="#d81e06" p-id="3535"></path><path d="M445.796 566.81c-175.020 20.25-154.77 185.145-154.77 185.145s-1.447 52.072 47.733 78.109c102.697 54.965 208.288 21.697 261.807-47.733 52.072-67.984 20.25-235.771-154.77-215.521M400.956 796.796c-33.268 4.339-59.304-14.464-59.304-41.947s23.143-56.411 56.411-59.304c37.608-2.892 62.198 18.804 62.198 44.84 0 27.483-26.036 53.518-59.304 56.411M503.654 708.562c-11.572 8.678-24.59 7.233-30.375-2.892s-4.339-24.59 7.233-33.268c13.018-10.125 26.036-7.233 31.822 2.892 7.233 10.125 2.892 24.59-8.678 33.268z" fill="#2c2c2c" p-id="3536"></path><path d="M1105.376 433.737c1.447-2.892 1.447-7.233 1.447-10.125 2.892-15.911 4.339-31.822 4.339-47.733 0-173.573-141.752-313.88-315.326-313.88-24.59 0-43.393 18.804-43.393 43.393 0 24.59 18.804 43.393 43.393 43.393 125.841 0 227.093 101.252 227.093 227.093 0 14.464-1.447 27.483-4.339 41.947v0c0 1.447 0 2.892 0 4.339 0 24.59 18.804 43.393 43.393 43.393 21.697 0 39.054-13.018 43.393-31.822v0c0 0 0 0 0 0z" fill="#d81e06" p-id="3537"></path><path d="M969.41 391.79c0-5.786 1.447-10.125 1.447-15.911 0-95.466-78.109-173.573-173.573-173.573-20.25 0-36.161 15.911-36.161 36.161 0 20.25 15.911 36.161 36.161 36.161 56.411 0 101.252 44.84 101.252 101.252 0 4.339 0 8.678-1.447 13.018h1.447c0 1.447-1.447 2.892-1.447 4.339 0 20.25 15.911 36.161 36.161 36.161 18.804 0 33.268-14.464 34.715-31.822v0c0-1.447 0-1.447 0-2.892 1.447 0 1.447 0 1.447-2.892 0 1.447 0 1.447 0 0z" fill="#d81e06"></path></svg></a>';
		$share .=  '<a class="bds sqq" target="_blank"' . $tooltip . ' title="分享给QQ好友" href="' . $qq['href'] . '">' . $qq['icon'] . '</a>';
	};
	if (_pz('share_img') && is_single()) {
		$share .= '<a class="bds simg" data-toggle="modal" data-target="#modal_poster"' . $tooltip . ' title="生成分享海报" href>' . $share_img['icon'] . '</a>';
	}
	return $share;
}


//获取文章分享图片
function zib_share_img()
{
	$r_src = zib_post_thumbnail('full', '', true);
	if ($r_src) {
		return $r_src;
	} else {
		return  _pz('share_img_byimg') ? _pz('share_img_byimg') : get_stylesheet_directory_uri() . '/img/share_img.jpg';
	}
}


function zib_screenshot_share()
{
	$weekarray = array("日", "一", "二", "三", "四", "五", "六");
	$Day = $weekarray[date("w")];
	if (_pz('share_img')) { ?>
		<div class="modal fade" id="modal_poster" tabindex="-1" role="dialog">
			<div class="modal-dialog poster-share" style="max-width:350px;margin: auto;" role="document">
				<div class="modal-body">
					<div class="poster-imgbox hover-show">
						<div class="modal-content poster-loading text-center">
							<div class="loading zts"></div>
							<div class="muted-2-color">正在生成图片，请稍候...</div>
						</div>
						<div class="abs-center hover-show-con text-center">
							<a type="button" class="but toggle-radius jb-blue mr10 poster-download" download="poster_<?php echo esc_attr(get_queried_object_id()) ?>.png"><i class="fa fa-download" aria-hidden="true"></i></a>
							<button type="button" class="but toggle-radius jb-yellow ml10" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
						</div>

					</div>
					<div class="qrcode hide"></div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			window._poster = {
				banner: '<?php echo esc_attr(zib_share_img()) ?>',
				title: '<?php echo esc_attr(the_title() . get_the_subtitle()) ?>',
				content: '<?php echo esc_attr(zib_get_excerpt(70)) ?>',
				tags: '<?php echo esc_attr('作者: ' . get_the_author() . '分类: ' . get_the_category()[0]->cat_name) ?>',
				logo: '<?php echo esc_attr(_pz('share_logo')) ?>',
				description: '<?php echo esc_attr(_pz(' share_desc ', '扫描二维码阅读全文 ')) ?>',
			}
		</script>
<?php }
}

if (_pz('share_img') && _pz('share_s')) {
	add_action('wp_footer', 'zib_screenshot_share');
}
