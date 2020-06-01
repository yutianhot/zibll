window.console || (window.console = {
    log: function () {}
});

/*
 * _win
 */
_win.bd = $("body"), _win.is_signin = !!_win.bd.hasClass("logged-in");
_win.bd.on("click", '[data-close]', function () {
    return e = $(this).attr('data-close'),
        $(e).removeClass('show in'), !1;
})

_win.bd.on("click", '[data-toggle-class]', function () {
    return c = $(this).attr('data-toggle-class') || 'show',
        e = $(this).attr('data-target') || $(this),
        $(e).toggleClass(c), !1;
})

//文章限制高度
if ($('.article-content.limit-height').length) {
    nn = '<div class="read-more"><a href="javascript:;" onclick="maxh_k()">展开阅读全文<i class="fa ml10 fa-angle-down"></i></a></div>'
    r = $('.article-content.limit-height');
    r_h = r.height();
    r_m = r.attr('data-maxheight');
    if (Number(r_h) >= Number(r_m) + 79) {
        r.height(r_m).append(nn);
    }
}

function maxh_k() {
    $('.article-content.limit-height').css({
        height: '',
        'max-height': ''
    }).find('.read-more').remove();
}

//首页幻灯片
function new_swiper() {
    if ($('.new-swiper:not(.swiper-container-initialized)').length) {
        $("link#swiper").length || $("head").append('<link type="text/css" id="swiper" rel="stylesheet" href="' + _win.uri + '/css/swiper.css">');
        tbquire(['swiper'], function () {

            var slider_arr = [];

            $('.new-swiper').each(function (e, c) {
                if ($(this).hasClass('swiper-container-initialized')) return;
                sjs = Math.floor(Math.random() * 10000);
                cla = 'swiper-eq' + sjs + e;
                var _Sp = $(this),
                    slider_arr = [],
                    delay = parseInt(_Sp.attr("data-interval")) || 6000,
                    auto_h = _Sp.attr("auto-height") ? true : false,
                    loop = _Sp.attr("data-loop") ? true : false,
                    effect = _Sp.attr("data-effect") || 'slide';
                direction = _Sp.attr("data-direction") || 'horizontal';
                spaceBetween = parseInt(_Sp.attr("data-spaceBetween")) || 0;
                _Sp.addClass(cla);
                slider_arr.push(new Swiper('.new-swiper.' + cla, {
                    loop: loop,
                    autoHeight: auto_h,
                    direction: direction,
                    spaceBetween: spaceBetween,
                    lazy: {
                        loadPrevNext: !0
                    },
                    effect: effect,
                    autoplay: {
                        delay: delay,
                        disableOnInteraction: false
                    },
                    speed: 400,
                    pagination: {
                        el: '.new-swiper.' + cla + ' .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.new-swiper.' + cla + ' .swiper-button-next',
                        prevEl: '.new-swiper.' + cla + ' .swiper-button-prev',
                    },
                    on: {
                        lazyImageReady: function (slideEl, imageEl) {
                            $(imageEl).addClass('lazyloaded');
                        }
                    }
                }))

            });
        })
    }
}
if ($('.widget-nav').length) {
    $(".widget-nav li:first-child").addClass('active');
    $('.widget-navcontent .item:eq(0)').addClass('active');
    $('.widget-nav li').each(function (e) {
        $(this).hover(function () {
            $(this).addClass('active').siblings().removeClass('active');
            $('.widget-navcontent .item:eq(' + e + ')').addClass('active').siblings().removeClass('active');
        })
    })
}

function post_ajax(_this, con, jcon, item, loader, pag, next, trigger, replace, nomore, data) {
    /*  AJAX获取包装函数   (必须)
        _this 传入点击按钮的自己   需要有href，下一页的链接(必须)
        con ：需要插入的父元素选择器   (必须)
        jcon ：获取内容的父元素选择器   (必须)
        item ：获取的列表选择器   (必须)
        loader ：加载动画的内容 （非必须，有默认值）
        pag ：获取的分页内容选择器 （必须）
        // 如果需要将下一页链接从新插入到新的按钮，则填写下面2个
        next ：获取分页内容中的下一页 选择器
        trigger ：将获取的下一页链接从新插入到的新的 按钮中-的class值
        replace ：替换列表内容而不是追加
        nomore ：全部加载完成之后的文案
        data : 需要传入的数据，默认为空白
     */

    replace = _this.attr('ajax-replace') || replace;
    replace && _this.parents(con).find(item).remove();
    return $con = _this.parents(con), data = data || '', nomore = nomore || _win.ajax_nomore, href = _this.attr("ajax-href") || _this.attr("href") || _this.find('a').attr("ajax-href") || _this.find('a').attr("href"), $item = $con.find(item),
        loader = loader || '<div class="theme-box box-body ajax-loading text-center"><h2 class="loading zts"></h2></div>',
        loader = '<span class="post_ajax_loader">' + loader + "</span>", href && $.ajax({
            type: "GET",
            url: href,
            data: data,
            dataType: "html",
            beforeSend: function () {
                $item.length ? $item.last().after(loader) : $con.append(loader);
                $con.find(pag).remove(), $con.find(".post_ajax_trigger,.no-more").remove();
            },
            success: function (a) {
                c_c = $(a).find(jcon).find(item);
                c_p = $(a).find(jcon).find(pag);
                n_h = c_p.find(next).attr("href") || c_p.find(next).find('a').attr("href");
                //console.log(a);
                //console.log(c_c, c_p, n_h);
                c_p = c_p.length ? c_p : '<div class="text-center theme-box muted-color muted-2-color box-body no-more separator">' + nomore + '</div>';
                trigger && (c_p = "undefined" != typeof n_h ? '<span class="post_ajax_trigger"><a class="' + trigger + '" href="' + n_h + '">' + (_win.ajax_trigger ? _win.ajax_trigger : '<i class="fa fa-arrow-right"></i>加载更多') + "</a></span>" : c_p),
                    $con.find(".post_ajax_loader,.post_ajax_trigger").remove(),
                    $item.length && $item.last().after(c_c),
                    $con.find(item).last().after(c_p),
                    $item.length || $con.append(c_c).append(c_p),
                    auto_fun();
            }
        }), !1;
}

$('a[data-ajax]').on('show.bs.tab', function (e) {
    a = e.target.hash;
    b = $(a).find('.ajax-next.ajax-open');
    b.length && b.click();
})

//文章手动ajax
$(".container").on("click", ".ajax-next", function (t) {
    _loader = '<div class="posts-item main-shadow radius8"><i class="radius8 item-thumbnail placeholder"></i> <div class="item-body"> <p class="placeholder t1"></p> <h4 class="item-excerpt placeholder k1"></h4><p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></div></div>';
    return post_ajax($(this), '.ajaxpager', '.ajaxpager', '.ajax-item', _loader + _loader, '.ajax-pag');
})

//评论手动ajax
_win.bd.on("click", ".pagenav a", function (t) {
    dh = '<div class="posts-item radius8"><i class="radius8 item-thumbnail placeholder"></i> <div class="item-body"> <p class="placeholder t1"></p> <h4 class="item-excerpt placeholder k1"></h4><p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></div></div>';
    $('#cancel-comment-reply-link').click();
    return post_ajax($(this), '#postcomments', '#postcomments', '.commentlist', dh + dh, '.pagenav', '', '', true);
});

var _wid = $(window).width();
var _hei = $(window).height();
var header_h = $(".header").innerHeight();

//文章幻灯片
if ($('.wp-block-carousel').length) {
    if (!$("link#swiper").length) {
        $('head').append('<link type="text/css" id="swiper" rel="stylesheet" href="' + _win.uri + '/css/swiper.css">');
    }
    var _sc = $('.wp-block-carousel');
    var si = 0;
    Sw = [];
    if (_sc.find('.wp-block-gallery>.blocks-gallery-grid').length) {
        _sc.find('.wp-block-gallery').html(_sc.find('.wp-block-gallery>.blocks-gallery-grid').html())
    }

    _sc.find('.wp-block-gallery').addClass('swiper-wrapper').removeClass('wp-block-gallery is-cropped columns-1 columns-2 columns-3 columns-4 columns-5 columns-6').find('.blocks-gallery-item').addClass('swiper-slide');
    _sc.find('.carousel-control.left').replaceWith('<div class="swiper-button-next"></div>');
    _sc.find('.carousel-control.right').replaceWith('<div class="swiper-button-prev"></div><div class="swiper-pagination"></div>');

    tbquire(['swiper'], function () {
        _sc.each(function () {
            si++
            _ss = $(this).find('.carousel');
            _ss.addClass('swiper_wz_' + si);
            var delay = _ss.attr("data-interval") || 6000,
                loop = _ss.attr("data-jyloop") ? "" : "loop:true",
                effect = _ss.attr("data-effect") || 'slide';

            Sw['swiper_wz_' + si] = new Swiper('.wp-block-carousel .carousel.swiper_wz_' + si, {
                spaceBetween: 10,
                loop: loop,
                effect: effect,
                autoplay: {
                    delay: delay,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                on: {

                }
            })
        })
    })
}


function auto_fun() {

    /* 提示工具*/
    $("[data-toggle='tooltip']").tooltip({
        container: 'body'
    });
    // 弹出框
    $("[data-toggle='html-popover']").each(function () {
        html_e = $(this).attr('data-target');
        html = $(html_e).html();
        $(this).popover({
            html: true,
            content: html,
        });
    });
    $("[data-toggle='popover']").popover();
    $("[data-toggle='html-popover']").on('shown.bs.popover', function () {
        show_svg(),
            $("[data-toggle='tooltip']").tooltip({
                container: 'body'
            });
    });
    //幻灯片检测
    new_swiper();
    // SVG-图标
    tbquire(["svg-icon"], () => {
        show_svg()
    });
    //高亮代码
    var _h_e = _win.highlight_kg ? 'pre code' : 'pre code.gl,pre code.special';
    $(_h_e).length && tbquire(["enlighterjs"], function () {
        lin = _win.highlight_hh ? !0 : !1;
        !$("link#enlighterjs").length && $("head").append('<link type="text/css" rel="stylesheet" href="' + _win.uri + '/js/enlighter/enlighterjs.min.css" id="enlighterjs">');
        $(_h_e).enlight({
            linenumbers: lin,
            indent: 2,
            textOverflow: 'scroll',
            rawcodeDbclick: !0,
            rawButton: !1,
            infoButton: !1,
            windowButton: !1,
            theme: _win.highlight_zt,

        });
    });

    //图片灯箱
    $('a[data-imgbox="imgbox"],.comt-main .box-img,.wp-posts-content img').length && tbquire(["imgbox"], function () {});

    //文章幻灯片保持长宽比例
    $(".wp-block-carousel .carousel").length && $(".wp-block-carousel .carousel").each(function () {
        fir = $(this).find(".swiper-wrapper"),
            firw = fir.width(),
            fbl = $(this).attr("proportion"),
            fbl && fir.height(firw * fbl);
    });
    if ($(".feature").length) {
        var _feh = 0,
            _fehm = 0;
        $(".feature").each(function () {
                (_feh = $(this).find(".feature-icon").innerHeight() + $(this).find(".feature-title").innerHeight() + $(this).find(".feature-note").innerHeight()) > _fehm && (_fehm = _feh);
            }),
            $(".feature").css("height", _fehm);
    }
}

//页面滚动监听函数
$(window).scroll(function () {
    var h = document.documentElement.scrollTop + document.body.scrollTop,
        ontop = $('.ontop');
    h > 100 ? _win.bd.addClass('body-scroll') : _win.bd.removeClass('body-scroll');
    h > 400 ? ontop.addClass('show') : ontop.removeClass('show');
})

//滚动执行函数
function scrollTo(o, t, l) {
    l || (l = 300), h = _win.bd.hasClass("nav-fixed") ? $(".header").innerHeight() : 0, o ? $(o).length > 0 && $("html,body").animate({
        scrollTop: ($(o).offset().top || o.offset().top) + (t || 0) - h
    }, l, 'swing') : $("html,body").animate({
        scrollTop: 0
    }, l, 'swing');
}

//横向滚动执行函数
$("[data-scroll]").each(function () {
    _this = $(this);
    var _n_w = 0,
        _w = $(this).innerWidth();
    left = '<div data-scroll-direction="left" data-scroll-value="' + _w + '" class="scroll-button-left"><i class="fa fa-angle-left"></i></div>';
    right = '<div data-scroll-direction="right" data-scroll-value="' + _w + '"  class="scroll-button-right"><i class="fa fa-angle-right"></i></div>';
    buttons = '<div class="scroll-button">' + left + right + '</div>';
    _this.children().children().each(function () {
        _n_w = _n_w + $(this).innerWidth();
    });
    _n_w > _w && _this.append(buttons);
});

_win.bd.on("click", '[data-scroll-direction]', function () {
    value = parseInt($(this).attr('data-scroll-value'));
    direction = $(this).attr('data-scroll-direction');
    target = $(this).attr('data-target') || $(this).parent().prev();
    _l = 0;
    _l = parseInt(target.scrollLeft());
    scroll = direction == 'left' ? _l - value : _l + value;
    target.animate({
        scrollLeft: scroll
    }, value / 2.2, 'swing')
})
_win.bd.on("click", '.toggle-radius,.float-right a,.but-ripple,.but,.item-thumbnail,.menu-item >a,.yiyan-box,.article-author .more-posts a, .relates-thumb li a', function (e) {
    _th = $(this);
    _th.css({
        "overflow": "hidden",
        "position": "relative"
    })
    _th.find("#wave").remove();
    _th.append("<div id='wave'></div>");
    var wave = $(this).find("#wave");
    wave.css({
        "display": "block",
        //涟漪的颜色
        "background": " rgba(200, 200, 200, 0.4)",
        "border-radius": "50%",
        "position": " absolute",
        "-webkit-transform": "scale(0)",
        "transform": "scale(0)",
        "opacity": "1",
        //涟漪的速度
        "transition": "all 1s",
        "-webkit-transition": "all 1s",
        "-moz-transition": "all 1s",
        "-o-transition": "all 1s",
        "z-index": " 1",
        "overflow": " hidden",
        "pointer-events": " none"
    });
    waveWidth = parseInt($(this).outerWidth());
    waveHeight = parseInt($(this).outerHeight());
    if (waveWidth < waveHeight) {
        var R = waveHeight;
    } else {
        var R = waveWidth;
    }
    wave.css({
        "width": (R * 2) + "px",
        "height": (R * 2) + "px",
        "top": (e.pageY - $(this).offset().top - R) + 'px',
        "left": (e.pageX - $(this).offset().left - R) + 'px',
        "transform": "scale(1)",
        "-webkit-transform": "scale(1)",
        "opacity": "0"
    });
});


/*侧边栏*/
var _sidebar = $('.sidebar')
if (_wid > 900 && _sidebar.length && _sidebar.find('[data-affix]').length) {
    var _top = _sidebar.offset().top,
        _bottom = $(".footer").outerHeight(!0) + $("main.container").nextAll('.fluid-widget').outerHeight(!0);
    _sh = _sidebar.innerHeight(),
        _hh = _win.bd.hasClass("nav-fixed") ? $(".header").outerHeight(!0) : 20,
        _boh = $(".content-layout").outerHeight(!0),
        rollFirst = _sidebar.find('[data-affix]'),
        _roll_ww = rollFirst.innerWidth();
    rollFirst.on("affix-top.bs.affix", function () {
        rollFirst.css({
            top: '',
            width: '',
            'transition': '',
            '-webkit-transition': '',
            position: '',
            opacity: ''
        })
    }), rollFirst.on("affix.bs.affix", function () {
        rollFirst.css({
            top: _hh,
            position: 'fixed',
            opacity: '',
            width: _roll_ww
        });
        var _hh2 = _hh;
        rollFirst.each(function () {
            $(this).css({
                top: _hh2
            })
            _hh2 += $(this).innerHeight();
        })
    }), rollFirst.on("affix-bottom.bs.affix", function () {
        rollFirst.each(function (i, x) {
            // console.log(i)
            i == 0 && $(this).css({
                'transition': '0s',
                '-webkit-transition': '0s',
            })
            i != 0 && $(this).css({
                opacity: '0',
            })
        })
    });
    rollFirst.eq(0).affix({
        offset: {
            top: _sh + _top - _hh + 20,
            bottom: _bottom
        }
    });
    $(window).scroll(function () {
        var _top = _sidebar.offset().top,
            _bottom = $(".footer").outerHeight(!0) + $("main.container").nextAll('.fluid-widget').outerHeight(!0);
        rollFirst.each(function () {
            _bottom += $(this).innerHeight();
        })
        _bottom -= rollFirst.eq(0).innerHeight();
        _sh = _sidebar.outerHeight(!0),
            _hh = _win.bd.hasClass("nav-fixed") ? $(".header").outerHeight(!0) : 20,
            rollFirst.eq(0).data('bs.affix').options.offset = {
                top: _sh + _top - _hh + 20,
                bottom: _bottom
            }
    })
}

//主题切换
$('.toggle-theme').click(function () {
    tbquire(["jquery.cookie"], function () {
        a = $('body').hasClass('dark-theme') ? !0 : !1;
        $('img[switch-src]').each(function () {
            _this = $(this),
                _src = _this.attr('data-src') || _this.attr('src'),
                _s_src = _this.attr('switch-src'),
                _this.attr('src', _s_src).attr('switch-src', _src).attr('data-src', '');
        })
        if (!a) {
            $('body').addClass('dark-theme'), $.cookie("theme_mode", 'dark-theme', {
                path: '/'
            });
        } else {
            $('body').removeClass('dark-theme'), $.cookie("theme_mode", 'white-theme', {
                path: '/'
            });
        }
    })
})

/*==============点赞===收藏===关注===========*/
tbquire(["jquery.cookie"], function () {
    _win.bd.on("click", '[data-action]', function () {
        var _this = $(this),
            pid = s = _this.attr("data-pid");
        key = _this.attr("data-action");
        type = key;
        _type = 'zibll' + type;
        data = {
            type: type,
            key: key,
            pid: pid
        };
        if (!_win.is_signin) {
            var t = lcs.get(_type) || "";
            if (-1 !== t.indexOf("," + s + ",")) return notyf("已赞过此" + (type == 'like' ? '文章' : '评论') + "了！", "warning");;
            t ? t.length >= 160 ? (t = t.substring(0, t.length - 1), t = t.substr(1).split(","),
                t.splice(0, 1), t.push(s), t = t.join(","), lcs.set(_type, "," + t + ",")) : lcs.set(_type, t + s + ",") : lcs.set(_type, "," + s + ",");

        }
        action_ajax(_this, data, '已赞！感谢您的支持')
    })
})

function action_ajax(_this, type, pid, key, text) {
    c = text || "处理完成";
    $.ajax({
        type: "POST",
        url: _win.uri + "/action/action.php",
        dataType: "json",
        data: data,
        beforeSend: function () {
            _this.find("count").html('<i class="loading zts" style="font-size:1.1em"></i>')
        },
        success: function (n) {
            // console.log(n);
            ys = (n.error ? 'danger' : "");
            if (n.action && n.action == "remove") {
                _this.removeClass('actived action-animation');
                ys = 'warning';
            }
            if (n.action && n.action == "add") {
                _this.addClass('actived action-animation')
            }
            notyf(n.msg || c, ys);
            _this.find("count").html(n.cuont || '0');
            if (type == "follow_user") {
                $('[data-action="follow_user"][data-pid="' + pid + '"]').each(function () {
                    $(this).find("count").html(n.cuont);
                })
            }
        }
    })
}

//登录注册
_win.bd.hasClass("logged-in") || tbquire(["sign-register"], function (i) {
    i.init()
});
_win.bd.on("click", '.signin-loader', function () {
    $('#u_sign').modal('show');
    $('a[href="#tab-sign-in"]').tab('show')
})
_win.bd.on("click", '.signup-loader', function () {
    $('#u_sign').modal('show');
    $('a[href="#tab-sign-up"]').tab('show')
})

//个人中心
_win.bd.hasClass("author") && tbquire(["author"], function (i) {
    i.init()
});
//发布文章
_win.bd.hasClass("page-template-newposts") && tbquire(["newposts"], function (a) {
    a.init()
});
//文章导航
_win.bd.hasClass("page-template-postsnavs") && _win.bd.hasClass("logged-admin") && tbquire(["navs"], function (a) {
    a.init()
});
//评论
$("#commentform").length && tbquire(["comment"], function (t) {
    t.init()
});

//搜索关键词高亮
if (_win.bd.hasClass('search-results')) {
    var val = $('.search-keyword').text(),
        reg = eval('/' + val + '/i');
    $('.item-heading a,.item-excerpt').each(function () {
        $(this).html($(this).text().replace(reg, function (w) {
            return '<b class="focus-color">' + w + '</b>'
        }))
    })
}
//搜索多选择
_win.bd.on("click", '[data-for]', function () {
    _zz = $(this);
    _tt = _zz.html();
    _for = _zz.attr('data-for');
    _f = _zz.parents('form');
    _v = _zz.attr('data-value');
    _f.find('span[name=' + _for + ']').html(_tt);
    _f.find('input[name=' + _for + ']').val(_v);
    _f.find('input[name=s]').focus();
})

/*菜单*/
$(".navbar-top li.menu-item-has-children>a").each(function () {
    $(this).append('<i class="fa fa-angle-down ml6"></i>');
});

//系统通知
function notyf(str, ys, time, id) {
    if (!ys) {
        ys = "success"
    };
    if (!time) {
        time = 5000
    };
    if (id) {
        if ($('#' + id).length) {
            $('#' + id).find('.notyf').removeClass().addClass('notyf ' + ys).html('<span>' + str + '</span>').delay(time).fadeOut().delay(1000, function () {
                $(this).parent().remove()
            });
        } else {
            $('.notyn').append('<div class="noty1" id="' + id + '" onclick="javascript:$(this).fadeOut().delay(1000,function(){$(this).remove()});"><div class="notyf ' + ys + '">' + str + '</span></div></div>');
        }
    } else {
        $('.notyn').append('<div class="noty1" onclick="javascript:$(this).fadeOut().delay(1000,function(){$(this).remove()});"><div class="notyf ' + ys + '"><span>' + str + '</span></div></div>').find('.noty1').delay(time).fadeOut().delay(1000, function () {
            $(this).remove()
        });
    }
}

//切换密码显示

var pai = 1;
_win.bd.on("click", '.passw', function () {
    if (pai == 1) {
        $(this).find('.fa').addClass("fa-eye-slash");
        $(this).siblings('input').attr('type', 'text');
        pai = 2
    } else {
        $(this).find('.fa').removeClass("fa-eye-slash");
        $(this).siblings('input').attr('type', 'password');
        pai = 1
    }
})

function is_name(str) {
    return /.{2,12}$/.test(str)
}

function zib_is_url(str) {
    return /^((http|https)\:\/\/)([a-z0-9-]{1,}.)?[a-z0-9-]{2,}.([a-z0-9-]{1,}.)?[a-z0-9]{2,}$/.test(str)
}

function is_qq(str) {
    return /^[1-9]\d{4,13}$/.test(str)
}

function is_mail(str) {
    return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(str)
}


$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


function strToDate(e, t) {
    t || (t = "yyyy-MM-dd hh:mm:ss"), e = new Date(1e3 * e);
    var s = {
        "M+": e.getMonth() + 1,
        "d+": e.getDate(),
        "h+": e.getHours(),
        "m+": e.getMinutes(),
        "s+": e.getSeconds(),
        "q+": Math.floor((e.getMonth() + 3) / 3),
        S: e.getMilliseconds()
    };
    /(y+)/.test(t) && (t = t.replace(RegExp.$1, (e.getFullYear() + "").substr(4 - RegExp.$1.length)));
    for (var g in s) new RegExp("(" + g + ")").test(t) && (t = t.replace(RegExp.$1, 1 == RegExp.$1.length ? s[g] : ("00" + s[g]).substr(("" + s[g]).length)));
    return t;
}


$('.popover-focus').on('show.bs.popover', function () {
    _win.bd.append('<div class="popover-mask" onclick="popover_hide(\'.popover-focus\')"></div>');
})

function popover_hide(e) {
    $(e).popover('hide');
    $('.popover-mask').remove()
}

/**发送验证码 */
_win.bd.on("click", ".captchsubmit", function () {
    var _this = $(this),
        captch_html = _this.html(),
        wait = 60,
        n = _this.parents('form'),
        tt = _this.html();
    o = n.serializeObject();
    o.action = 'signup_captcha';
    _this.attr('disabled', true).html('<i class="loading mr10"></i><span>请稍候</span>'), notyf("正在处理请稍等...", "load", "", "signup_captcha"), $.ajax({
        type: "POST",
        url: zibpay_ajax_url,
        data: o,
        dataType: "json",
        success: function (n) {

            n.msg && notyf(n.msg, (n.error ? 'danger' : ""), "", "signup_captcha");

            if (n.error) {
                _this.attr('disabled', false).html(tt);
            } else {
                captchdown();
            }
        }
    });
    var captchdown = function () {
        if (wait > 0) {
            _this.html(wait + '秒后可重新发送');
            wait--;
            setTimeout(captchdown, 1000);
        } else {
            _this.html(captch_html).attr('disabled', false);
            wait = 60
        }
    }
})



/* erphpdown 登录使用弹出登录框
 * =========================================
 */
$('.erphp-login-must').each(function () {
    $(this).addClass('signin-loader')
});

//浏览器窗口调整自动化
$(window).resize(function (event) {
    _wid = $(window).width();
    auto_fun();
});
$('.collapse').on('shown.bs.collapse', function () {
    auto_fun();
})

auto_fun();

$(document).ready(function () {

    $(".qrcode").length && tbquire(["qrcode"], function () {
        $(".qrcode").qrcode({
            width: 160,
            height: 160,
            correctLevel: 0,
            text: document.URL,
            background: "#fff",
            foreground: "#555"
        });
    });

    /**延迟加载函数 */
    setTimeout(function () {
        //海报分享
        $(".poster-share").length && tbquire(["poster-share"]);
    }, 1000);

    $(".modal").on("show.bs.modal", function () {
        var o = $(this),
            i = o.find(".modal-dialog");
        o.css("display", "block"), i.css({
            "margin-top": Math.max(0, (_hei - i.height()) / 2)
        });
    });
    /**tab跳转 */
    var tab_show = '';
    if ("undefined" != typeof _win.show_tab) {
        tab_show += 'a[href="#' + _win.show_tab + '"]';
    }
    if ("undefined" != typeof _win.show_tab2) {
        tab_show += ',a[href="#' + _win.show_tab2 + '"]';
    }
    if ("undefined" != typeof _win.show_tab3) {
        tab_show += ',a[href="#' + _win.show_tab2 + '"]';
    }
    tab_show && $(tab_show).tab('show');

    //图片延迟懒加载
    tbquire(["lazysizes"], function () {
        document.addEventListener('lazybeforeunveil', function (e) {
            var bg = e.target.getAttribute('data-bg');
            if (bg) {
                e.target.style.backgroundImage = 'url(' + bg + ')';
            }
        });
    });

    _win.qj_loading && $(".qj_loading").fadeOut(500).delay(1e3, function () {
        $(this).remove(), $("#qj_dh_css").remove();
    });
    /*一言功能*/
    function yiyan_nr(n) {
        $.ajax({
            url: yylink
        }).done(function (i) {
            lines = i.replace(/\r\n|\r/g, "/&/").trim().split("/&/"), lines && lines.length && (y_nr = '<div class="cn">' + lines[0] + '</div><div class="en">' + lines[1] + "</div>",
                n.html(y_nr));
        });
    }

    yylink = _win.uri + "/yiyan/qv-yiyan.php", $(".yiyan").each(function () {
        yiyan_nr($(this));
    }), setInterval(function () {
        $(".yiyan").each(function () {
            yiyan_nr($(this));
        });
    }, 3e4), $(".yiyan").on("click", function () {
        yiyan_nr($(this));
    });

    /*文章目录*/
    $("[data-nav] h1,[data-nav] h2,[data-nav] h3,[data-nav] h4").length > 2 && tbquire(["section_navs"]);

    //手势控制
    tbquire(["mini-touch"], function () {
        direction = $('[nav-touch]').attr('nav-touch'),
            $('[nav-touch]').minitouch({
                direction: direction,
                onEnd: function () {
                    $('[nav-touch]').removeClass('show')
                }
            });
    });
    //系统通知弹窗
    if ($('#modal-system-notice').length) {
        tbquire(["jquery.cookie"], function () {
            $('#modal-system-notice').modal('show');
            $.cookie("showed_system_notice", 'showed', {
                path: '/',
                expires: 1
            });
        });
    }
});

console.log('\n' + ' %c ZibllTheme %c https://zibll.com ' + '\n', 'color: #fadfa3; background: #030307; padding:5px 0; font-size:12px;', 'background: #fadfa3; padding:5px 0; font-size:12px;');