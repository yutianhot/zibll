$("link#swiper").length || $("head").append('<link type="text/css" id="swiper" rel="stylesheet" href="' + _win.uri + '/css/swiper.css">');
close = '';
/*	close +='<i class="icon-close"><i class="fa fa-play-circle-o" aria-hidden="true"></i></i>';
	close +='<i class="icon-close"><i class="fa fa-search-minus" aria-hidden="true"></i></i>';
	close +='<i class="icon-close"><i class="fa fa-search" aria-hidden="true"></i></i>';  */
close += '<i class="icon-close"><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></i>';

beijin = '<div class="modal-backdrop imgbox-bg"></div>';
anniu = '<div class="imgbox-an">' + close + '</div>';
imgbox = '<div class="imgbox">' + beijin + anniu + '</div>';

_win.bd.append(imgbox);

var _i = $(".imgbox"),
	_img = _i.find(".swiper-slide img");

_win.bd.on().on('click', '.img-close,.imgbox-bg,.imgbox-an .icon-close' ,function () {
	imgbox_close();
});



function imgbox_close() {
	$("html,body").css("overflow", ""), _i.removeClass("show"), _img.css({
		transform: ""
	});
	$('.swiper-imgbox.comt-imgbox').remove();
}

function imgbox_touch() {
	tbquire(["mini-touch"], function () {
	_i.find(".swiper-close.no-scale").minitouch({
		direction: 'bottom',
		depreciation: 100,
		onStart: false,
		onEnd: function () {imgbox_close()}
		});
	});
}

function imgbox_open(e, b, c) {
	tbquire(['swiper'], function () {
		var _a = $(e),
			length = _a.length,
			index = 0,
			b = b || 'swiper-imgbox',
			tupian = '';

		_a.each(function () {
			link = $(this).attr("href") || $(this).attr("src");
			src = $(this).find('img').attr("src");
			link2 = link;
			src == link && (link2 = link.replace(/(.*\/)(.*)(-\d+x\d+)(.*)/g, "$1$2$4"));
			index += 1;
			$(this).attr("imgbox-index", index);
			tupian += '<div class="swiper-slide"><div class="swiper-close no-scale"><div class="swiper-zoom-container"><div class="absolute img-close"></div><img data-src="' + link2 + '" class="swiper-lazy lazyload"><div class="swiper-lazy-preloader"></div></div></div></div>';
		});

		wrapper = '<div class="swiper-wrapper">' + tupian + '</div>';
		swiper = '<div class="' + b + '">' + wrapper + '<div class="swiper-pagination"></div><div class="swiper-button-prev"></div><div class="swiper-button-next"></div></div>';
		_i.append(swiper);

		var imgbox_S = new Swiper("." + b, {
			init: !1,
			lazy: {
				loadPrevNext: !0
			},
			navigation: {
				nextEl: "." + b +" .swiper-button-next",
				prevEl: "." + b +" .swiper-button-prev"
			},
			pagination: {
				el: "." + b +" .swiper-pagination",
				clickable: !0
			},
			zoom: {
				maxRatio: 2,
			},
			keyboard: {
				enabled: !0,
				onlyInViewport: !1
			},
			spaceBetween: 20,
			on: {
				zoomChange: function (i, e, n) {
					i > 1 ? _i.find(".swiper-close").removeClass("no-scale") : _i.find(".swiper-close").addClass("no-scale");
				}
			}
		});

		$('body').on('click', e,  function () {
			$('.swiper-imgbox').css('display', '');
			return inx = $(this).attr("imgbox-index"), $("html,body").css("overflow", "hidden"),
				$(".modal").modal("hide"), imgbox_S.init(), imgbox_S.slideToLoop(inx - 1, 10), _i.addClass("show"),
				imgbox_touch(),!1;
		});

		$('.imgbox-an .icon-play').click(function () {
			imgbox_S.autoplay.start()
		});
		_i.on('touchmove pointermove MSPointerMove', function (e) {
			e.preventDefault()
		});
	})
}

function click_imgbox(e) {
	$('body').on('click', e, function () {
		if($(this).parent('a').attr('href')&&$(this).parent('a').attr('href').match(/\.(jpeg|jpg|gif|png)$/) != null) return;
		$('.swiper-imgbox').css('display', 'none');
		link = $(this).attr("href") || $(this).attr("src");
		tupian = '<div class="swiper-slide"><div class="swiper-close no-scale"><div class="swiper-zoom-container"><div class="absolute img-close"></div><img src="' + link + '"></div></div></div>';
		wrapper = '<div class="swiper-wrapper">' + tupian + '</div>';
		swiper = '<div class="swiper-imgbox comt-imgbox">' + wrapper + '</div>';
		_i.append(swiper).addClass("show");
		imgbox_touch();
		$("html,body").css("overflow", "hidden");
		return !1;
	});
}
imgbox_open('a[data-imgbox="imgbox"]', 'swiper-imgbox');
click_imgbox('.comt-main .box-img');
click_imgbox('.wp-posts-content img');
auto_fun();