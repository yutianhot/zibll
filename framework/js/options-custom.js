/**
 * Custom scripts needed for the colorpicker, image button selectors,
 * and navigation tabs.
 */

jQuery(document).ready(function ($) {


	/*
		一个简单的手势封装插件
		BY:Qinver
	*/
	"use strict";function cssTransition(n,t,o,i){var e,r,a;i&&(t+="px",o+="px",e="translate3D("+t+","+o+" , 0)",r={},a=cssT_Support(),r[a+"transform"]=e,r[a+"transition"]=a+"transform 0s linear","null"==i&&(r[a+"transform"]="",r[a+"transition"]=""),n.css(r))}function cssT_Support(){var n=document.body||document.documentElement,n=n.style;return""==n.WebkitTransition?"-webkit-":""==n.MozTransition?"-moz-":""==n.OTransition?"-o-":""==n.transition?"":void 0}$.fn.minitouch=function(n){var n=$.extend({direction:"bottom",depreciation:50,onStart:!1,onEnd:!1},n),t=$(this),o=$("body"),i=n.depreciation,e=0,r=0,a=0,c=0,s=0,u=0,l=0,p=!1;o.on("touchstart pointerdown MSPointerDown",t.selector,function(n){e=0,r=0,a=0,c=0,s=0,u=0,l=0,e=n.originalEvent.pageX||n.originalEvent.touches[0].pageX,r=n.originalEvent.pageY||n.originalEvent.touches[0].pageY,p=!0}).on("touchmove pointermove MSPointerMove",t.selector,function(o){a=o.originalEvent.pageX||o.originalEvent.touches[0].pageX,c=o.originalEvent.pageY||o.originalEvent.touches[0].pageY,u=a-e,l=c-r,s=180*Math.atan2(l,u)/Math.PI,"right"==n.direction&&(l=0,u=s>-40&&s<40&&u>0?u:0),"left"==n.direction&&(l=0,u=(s>150||s<-150)&&0>u?u:0),"top"==n.direction&&(u=0,l=s>-130&&s<-50&&0>l?l:0),"bottom"==n.direction&&(u=0,l=s>50&&s<130&&l>0?l:0),0===u&&0===l||(o.preventDefault(),cssTransition(t,u,l,p))}).on("touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel",t.selector,function(o){(Math.abs(u)>i||Math.abs(l)>i)&&0!=n.onEnd&&n.onEnd(),cssTransition(t,0,0,"null"),p=!1,e=0,r=0,a=0,c=0,s=0,u=0,l=0})};
	// Loads the color pickers
	$('.of-color').wpColorPicker();

	// Image Options
	$('.of-radio-img-img').click(function () {
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	// Loads tabbed sections if they exist
	if ($('.nav-tab-wrapper').length > 0) {
		options_framework_tabs();
	}

	function options_framework_tabs() {

		var $group = $('.group'),
			$navtabs = $('.nav-tab-wrapper a'),
			active_tab = '';

		// Hides all the .group sections to start
		$group.hide();

		// Find if a selected tab is saved in localStorage
		if (typeof (localStorage) != 'undefined') {
			active_tab = localStorage.getItem('active_tab');
		}

		// If active tab is saved and exists, load it's .group
		if (active_tab != '' && $(active_tab).length) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').addClass('nav-tab-active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		// Bind tabs clicks
		$navtabs.click(function (e) {

			e.preventDefault();
			// Remove active class from all tabs
			$navtabs.removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active').blur();
			if (typeof (localStorage) != 'undefined') {
				localStorage.setItem('active_tab', $(this).attr('href'));
			}
			var selected = $(this).attr('href');
			$group.hide();
			$(selected).fadeIn();
		});
	}

	$("[data-html-src]").each(function () {
		$(this).load($(this).attr('data-html-src'));
	});

	setTimeout(function () {
		$('#optionsframework-wrap .updated.fade').css('right', '-300px');
	}, 5000);
	$(".number-slider").ionRangeSlider({
		type: "single",
		keyboard: true
	});

	$('a.button-nav').click(function () {
		$('#optionsframework-wrap nav,.button-nav-bj').toggleClass('xs');
	})
	$('a.button-nav-bj').click(function () {
		$('#optionsframework-wrap nav,#optionsframework-wrap .button-nav-bj').removeClass('xs');
	})

	var getUrl = window.location.hash;
	getUrl = getUrl.replace("#", "");
	if (getUrl && $('a#' + getUrl).length) {
		$('a#' + getUrl).click();
	}

	var _wid = $(window).width();
	if(_wid < 610){
	$('.nav-tab-wrapper').minitouch({
		direction: 'right',
		onEnd: function () {
			$('.nav-tab-wrapper').removeClass('xs')
		}
	});

}

});