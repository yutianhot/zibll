var nt = '',
    angle = '',
    i = 0;
$('[data-nav] h1,[data-nav] h2,[data-nav] h3,[data-nav] h4').each(function () {
    var tag = $(this).prop("tagName"),
        text = $(this).text();
    if (i === 0) {
        nt += '<li class="n-' + tag + ' active"><a  class="text-ellipsis" href="#wznav_' + i + '">' + text + '</a></li>'
    } else {
        nt += '<li class="n-' + tag + '"><a  class="text-ellipsis" href="#wznav_' + i + '">' + text + '</a></li>'
    }
    $(this).attr('id', 'wznav_' + i)
    i++
})

$('.posts-nav-box').each(function () {
    _t = $(this).attr('data-title') || '';
    _t = _t && '<div class="box-body notop"><div class="title-theme">' + _t + '</div></div>';

    _box = '<div class="theme-box">' + _t + '\
                <div class="main-bg theme-box box-body radius8 main-shadow"><div class="posts-nav-lists scroll-y mini-scrollbar list-unstyled"></div></div>\
            </div>';
    $(this).append(_box);
});

$('.posts-nav-lists').html('<ul class="bl relative nav">' + nt + '</ul>');

_win.bd.on("click", '.posts-nav-lists a', function () {
    maxh_k();
    scrollTo($(this).attr("href"));
    return false;
})

$('body').scrollspy({
    target: '.posts-nav-lists',
    offset: 105
});

var a_n = $('.posts-nav-lists'),
    _hbl = $('.posts-nav-lists .bl').innerHeight();

if (_hbl > 380) {
    var n_hx = $('.posts-nav-lists .n-H1');
    n_hx.each(function () {
        if ($(this).nextUntil(".n-H1").length) {
            $(this).nextUntil(".n-H1").addClass('yc');
            $(this).append('<i class="fa fa-angle-right"></i>')
                .find('.fa').on('click', function () {
                    $(this).toggleClass('fa-rotate-90').parent().nextUntil(".n-H1").toggleClass('yc');
                })
        }
    })
}