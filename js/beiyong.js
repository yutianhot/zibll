
//文章Ajax加载
(Number(_win.ajaxpager) > 0 && $(".ajaxpager .posts-item").length && $(".ajaxpager .next-page").length ) && post_ias();

function post_ias() {
    spinner = '.ajax-loading';
    item = ".ajax-item";
    next = ".next-page a";
    pagination = '.pagination';
    over = '<div class="muted-3-color">-----------  已加载全部内容  -----------</div>';

tbquire([ "ias" ], function() {
    var index_ias = new InfiniteAjaxScroll('.ajaxpager', {
        item: item,
        next: next,
        spinner: spinner,
        loadOnScroll: false,
        pagination:pagination,
      });
      index_ias.on('appended', () => {
        show_svg();
      });
      index_ias.on('last', () => {
        $(spinner).html(over).css('opacity',1);
      });
      $(next).on('click', () => {
        return index_ias.next(),!1;
      });
});

}

if ($('.swiper-vv.sltms').length) {
  var Swiper2 = new Swiper('.swiper-vv.sltms', {
      lazy: {
          loadPrevNext: !0
      },
      direction: 'vertical',
      spaceBetween: 10,
      slidesPerView: 3,
      watchSlidesProgress: true,
      watchSlidesVisibility: true,
      on: {
          tap: function (event) {
              Swiper1.slideToLoop(this.clickedIndex)
          },
          init: function () {
              setTimeout(function () {
                  Swiper2.update()
              }, "1500");
          }
      }
  })
}
if ($('.swiper-vv.mohu').length) {
  var Swiper3 = new Swiper('.swiper-vv.mohu', {
      effect: 'coverflow',
      lazy: {
          loadPrevNext: !0
      },
      loop,
      autoplay: {
          delay: delay,
          disableOnInteraction: false
      },
      on: {
          init: function () {
              $('.swiper-c.mohu>.swiper-wrapper img').css('filter', 'blur(40px)').next('.swiper-lazy-preloader').remove();
              $('.swiper-vv.mohu').css('opacity', '1');
              setTimeout(function () {
                  Swiper3.update()
              }, "1500");
          },
          slideChange: function () {
              _Sp.find('.swiper-slide').removeClass('active');
              _Sp.find('.swiper-slide:eq(' + this.realIndex + ')').addClass('active');
          }
      },
      pagination: {
          el: '.swiper-pagination',
          clickable: true,
      },
      navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
      },
      slidesPerView: 3,
      grabCursor: true,
      centeredSlides: true,
      coverflowEffect: {
          rotate: 42,
          stretch: 50,
          depth: 320,
          modifier: 1.2,

      },
  });
}
function updateNavPosition(e) {
    if ($('.swiper-vv.mohu').length) {
        Swiper2.slideToLoop(e.realIndex)
    }
    if ($('.swiper-vv.sltms').length) {
        $('.swiper-vv.sltms .active-nav').removeClass('active-nav')
        var activeNav = $('.swiper-vv.sltms .swiper-slide').eq(e.realIndex).addClass('active-nav');
        if (!activeNav.hasClass('swiper-slide-visible')) {
            if (activeNav.index() > Swiper2.realIndex) {
                var thumbsPerNav = Math.floor(Swiper2.width / activeNav.width()) - 1
                Swiper2.slideTo(activeNav.index() - thumbsPerNav)
            } else {
                Swiper2.slideTo(activeNav.index())
            }
        }
    }
}