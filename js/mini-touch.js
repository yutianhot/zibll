/*
	一个简单的手势封装插件
	BY:Qinver
*/
function cssTransition(a, b, c, d) {
    var e, f, g;
    d && (b += "px", c += "px", e = "translate3D(" + b + "," + c + " , 0)", f = {},
        g = cssT_Support(),
        f[g + "transform"] = e,
        f[g + "transition"] = g + "transform 0s linear",
        "null" == d && (f[g + "transform"] = "", f[g + "transition"] = ""), a.css(f));
}

function cssT_Support() {
    var a = document.body || document.documentElement,
        a = a.style;
    return "" == a.WebkitTransition ? "-webkit-" : "" == a.MozTransition ? "-moz-" : "" == a.OTransition ? "-o-" : "" == a.transition ? "" : void 0;
}
$.fn.minitouch = function (options) {
    var options = $.extend({
                direction: 'bottom',
                depreciation: 50,
                onStart: false,
                onEnd: false,
            },
            options),
        _e = $(this),
        _body =  $('body'),
        dep = options.depreciation,
        startX = 0,
        startY = 0,
        endX = 0,
        endY = 0,
        angle = 0,
        distanceX = 0,
        distanceY = 0,
        dragging = false;
        _body.on('touchstart pointerdown MSPointerDown',_e.selector, function (e) {
                startX = 0,
                startY = 0,
                endX = 0,
                endY = 0,
                angle = 0,
                distanceX = 0,
                distanceY = 0;
            startX = e.originalEvent.pageX || e.originalEvent.touches[0].pageX, startY = e.originalEvent.pageY || e.originalEvent.touches[0].pageY;
            dragging = !0;

        })
        .on("touchmove pointermove MSPointerMove",_e.selector, function (a) {
                endX = a.originalEvent.pageX || a.originalEvent.touches[0].pageX,
                endY = a.originalEvent.pageY || a.originalEvent.touches[0].pageY, distanceX = endX - startX,
                distanceY = endY - startY, angle = 180 * Math.atan2(distanceY, distanceX) / Math.PI,
                "right" == options.direction && (distanceY = 0, distanceX = ((angle>-40&&angle<40)&&distanceX > 0) ? distanceX : 0),
                "left" == options.direction && (distanceY = 0, distanceX = ((angle>150||angle<-150)&&0 > distanceX) ? distanceX : 0),
                "top" == options.direction && (distanceX = 0, distanceY = ((angle>-130&&angle<-50)&&0 > distanceY) ? distanceY : 0),
                "bottom" == options.direction && (distanceX = 0, distanceY = ((angle>50&&angle<130)&&distanceY > 0) ? distanceY : 0);

                if(distanceX !== 0 || distanceY !== 0) {
                    a.preventDefault();
                    cssTransition(_e, distanceX, distanceY, dragging);
                }
        })
        .on('touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel',_e.selector, function (e) {
            ((Math.abs(distanceX) > dep || Math.abs(distanceY) > dep) && 0 != options.onEnd) && options.onEnd() , cssTransition(_e, 0, 0, "null"),
                dragging = !1,
                startX = 0,
                startY = 0,
                endX = 0,
                endY = 0,
                angle = 0,
                distanceX = 0,
                distanceY = 0;
        });
}