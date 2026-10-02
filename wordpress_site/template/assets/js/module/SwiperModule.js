export default function SwiperModule() {
  let width = $(window).width();

  function getSwiperOptions($container, customOptions) {
    // Default options for Swiper
    var defaultOptions = {
      slidesPerView: "auto",
      speed: 800,

      autoplay: {
        delay: 8000,
        disableOnInteraction: false,
      },

      pagination: {
        // Find the pagination element within the container
        el: $container.find(">.swiper-pagination")[0],
        clickable: true,

        // dynamicBullets: true,
        // dynamicMainBullets: 3,
      },

      navigation: {
        // Find the navigation next/prev elements using the container's sibling element
        nextEl: $container.find(".swiper-navigation").find(">.next")[0],
        prevEl: $container.find(".swiper-navigation").find(">.prev")[0],
      },
    };

    return $.extend(true, {}, defaultOptions, customOptions);
  }

  function initNamedSwiper(selector, variablePrefix, customOptions) {
    var $containers = $(selector);
    if (!$containers.length) return;

    $containers.each(function (index) {
      var $container = $(this);

      var $swiperContainer = $container.find(".swiper");
      if (!$swiperContainer.length) return;

      // Swiper Options
      var options = getSwiperOptions($container, customOptions);

      // Swiper Instance
      var swiperInstance = new Swiper($swiperContainer[0], options);

      // Unique Variable Name
      var variableName = variablePrefix + (index + 1);

      // Assign the Swiper Name
      window.MONA_SWIPER = window.MONA_SWIPER || {};
      window.MONA_SWIPER[variableName] = swiperInstance;
    });
  }

  function initSwiper(selector, variablePrefix, customOptions) {
    $(document).ready(function () {
      initNamedSwiper(selector, variablePrefix, customOptions);
    });
  }

  //- //////////////////////////////
  //- COMMON SWIPER

  $(document).ready(function () {
    const $heroContainer = $(".js-news-hero-swiper");
    if ($heroContainer.length) {
      const heroSwiperEl = $heroContainer.find(".swiper")[0] || $heroContainer[0];
      const newsHeroSwiper = new Swiper(heroSwiperEl, {
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 800,
        grabCursor: true,
        loop: true,
        effect: "fade",
        fadeEffect: {
          crossFade: true,
        },
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        },
      });

      $(document).on("click", ".js-news-hero-next", function (e) {
        e.preventDefault();
        newsHeroSwiper.slideNext();
      });

      $(document).on("click", ".js-news-hero-prev", function (e) {
        e.preventDefault();
        newsHeroSwiper.slidePrev();
      });
    }
  });

  initSwiper(".js-news-slide", "newSlide", {
    autoplay: {
      delay: 6000,
      disableOnInteraction: false,
    },
    speed: 1000,
    grabCursor: true,
    loop: true,
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
  });

  initSwiper(".js-tab-slide", "tabSlide", {
    autoplay: false,
    slidesPerView: "auto",
    freeMode: true,
  });

  initSwiper(".js-team-slide", "teamSlide", {
    autoplay: true,
    spaceBetween: 32,
    slidesPerView: 3,

    navigation: {
      nextEl: ".team-img .swiper-navigation .next",
      prevEl: ".team-img .swiper-navigation .prev",
    },

    breakpoints: {
      0: {
        slidesPerView: 1.6,
        spaceBetween: 10,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 10,
      },
    },
  });

  $(".js-news-req").each(function () {

    const $wrap = $(this);
    const $swiper = $wrap.find(".swiper");

    new Swiper($swiper[0], {
      slidesPerView: 3,
      spaceBetween: 32,
      autoplay: false,

      pagination: {
        el: $wrap.find(".swiper-pagination")[0],
        type: "progressbar",
      },

      navigation: {
        nextEl: ".news-req .swiper-navigation .next",
        prevEl: ".news-req .swiper-navigation .prev",
      },

      breakpoints: {
        0: {
          slidesPerView: 2,
          spaceBetween: 10,
        },

        768: {
          slidesPerView: 3,
          spaceBetween: 10,
        },

        1024: {
          slidesPerView: 3,
          spaceBetween: 32,
        },
      },
    });

  });

  initSwiper(".js-field-slide", "fieldSlide", {
    slidesPerView: "auto",
    autoplay: false,

    breakpoints: {
      0: {
        centeredSlides: true,
        initialSlide: 1,
        spaceBetween: 12,
      },
      480: {
        centeredSlides: true,
        initialSlide: 1,
        spaceBetween: 12,
      },
      768: {
        centeredSlides: false
      }

    }
  });

  initSwiper(".js-cate-slide", "cateSlide", {
    slidesPerView: "auto",
    autoplay: false,
    spaceBetween: 12,
    pagination: {
      type: "progressbar"
    },
  });

  initSwiper(".js-manager-slide", "managerSlide", {
    slidesPerView: "auto",
    autoplay: false,
    effect: "fade",
    pagination: {
      type: "progressbar"
    },
    allowTouchMove: false,
  });

  initSwiper(".js-manager-list", "managerList", {
    slidesPerView: "auto",
    autoplay: false,
    spaceBetween: 16,
    slideActiveClass: "is-active",
    pagination: {
      type: "progressbar"
    },

    breakpoints: {
      0: {
        spaceBetween: 8,
      },
      980: {
        spaceBetween: 16,
      },

    }
  });

  if (($(".js-manager-list") && $(".js-manager-slide")).length) {

    $(window).on("load", function () {

      const mList = window.MONA_SWIPER.managerList1;
      const mSlide = window.MONA_SWIPER.managerSlide1;

      if (!mList || !mSlide) return;

      $(mList.slides[0]).addClass("is-active");

      mList.on("click", function () {

        const index = mList.clickedIndex;
        if (typeof index === "undefined") return;

        mSlide.slideTo(index);
        $(mList.slides).removeClass("is-active");
        $(mList.slides[index]).addClass("is-active");
      });

    });

  }

  initSwiper(".js-system-slide", "systemSlide", {
    slidesPerView: 3,
    autoplay: false,
    spaceBetween: 32,
    pagination: {
      type: "progressbar"
    },

    breakpoints: {
      0: {
        slidesPerView: 1.2,
        spaceBetween: 16,
        centeredSlides: true,
        initialSlide: 1,
      },
      480: {
        slidesPerView: 2,
        spaceBetween: 16,
        centeredSlides: true,
        initialSlide: 1,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 16,
      },
      980: {
        slidesPerView: 3,
        spaceBetween: 32,

      },

    }
  });

  initSwiper(".js-ban-info", "banInfo", {
    slidesPerView: 1,
    autoplay: false,
    loop: true,
    loopedSlides: 6,
    speed: 800,
    allowTouchMove: false,
    effect: 'fade',
    fadeEffect: {
      crossFade: true,
    },
  });

  initSwiper(".js-ban-img", "banImg", {
    slidesPerView: "auto",
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    loop: true,
    loopedSlides: 6,
    slideToClickedSlide: true,
    watchSlidesProgress: true,
    speed: 800,
    pagination: {
      type: "progressbar"
    },

    breakpoints: {
      0: {
        centeredSlides: true,
      },
      580: {
        centeredSlides: false,
      },
    }
  });

  initSwiper(".js-pj-background", "banBg", {
    slidesPerView: 1,
    autoplay: false,
    loop: true,
    loopedSlides: 6,
    speed: 800,
    allowTouchMove: false,
    effect: 'fade',
    fadeEffect: {
      crossFade: true,
    },
  });

  if ($(".js-ban-img").length) {
    window.addEventListener("load", () => {
      const infoSwiper = window.MONA_SWIPER?.banInfo1;
      const imgSwiper = window.MONA_SWIPER?.banImg1;
      const bgSwiper = window.MONA_SWIPER?.banBg1;

      if (!imgSwiper) return;

      imgSwiper.on("slideChange", () => {
        const realIdx = imgSwiper.realIndex;
        if (infoSwiper) {
          infoSwiper.slideToLoop(realIdx, 800);
        }
        if (bgSwiper) {
          bgSwiper.slideToLoop(realIdx, 800);
        }
      });
    });
  }

  initSwiper(".js-recruit-slide", "recruitSlide", {
    slidesPerView: 1.3,
    spaceBetween: 32,
    autoplay: false,
    initialSlide: 1,
    centeredSlides: true,

    navigation: {
      nextEl: ".recruit-req-inner .swiper-navigation .next",
      prevEl: ".recruit-req-inner .swiper-navigation .prev",
    },

    pagination: {
      type: "progressbar",
    },

    breakpoints: {
      0: {
        slidesPerView: 1.1,
        spaceBetween: 10,
        initialSlide: 0,
      },
      768: {
        slidesPerView: 1.3,
        spaceBetween: 10,
      },
    },
  });
  //========================
  /**
   * Slide Tổng quan ESG — 1 thẻ / 1 màn hình.
   *
   * Bản cũ dùng coverflow với slidesPerView "auto", mỗi thẻ một bề rộng khác
   * nhau (active 67.2%, còn lại 15%), cộng thêm một cơ chế dịch .swiper-wrapper
   * thủ công qua biến CSS --offset-left tính lúc init. Bố cục đó lệch ngay khi
   * đổi slide và để hở mảng trắng bên phải ở thẻ cuối; tính lại --offset-left
   * giữa chừng còn làm wrapper văng hẳn ra ngoài khung.
   *
   * Đổi sang 1 thẻ chính + ló một phần thẻ kế tiếp: mọi thẻ cùng bề rộng, do
   * Swiper tự tính từ slidesPerView, nên không còn con số nào phải khớp với con
   * số nào và về mặt hình học không thể hở mép ở bất kỳ vị trí nào.
   *
   * Bù lại phải có nút điều hướng + pagination (đã thêm vào template-stable.php)
   * vì bản cũ chuyển slide bằng cách bấm vào thẻ bên cạnh — giờ chỉ còn phần ló
   * rất nhỏ nên không thể dựa vào cách đó nữa.
   *
   * LƯU Ý: không được đặt width cho .overview .swiper-slide bằng !important.
   * Swiper gán width inline theo slidesPerView, rule !important sẽ đè lên và
   * làm vỡ bố cục.
   */
  const swiperOverview = new Swiper(".js-coverflow-slider", {
    slidesPerView: 1.08,
    speed: 800,
    spaceBetween: 16,
    loop: true,
    autoplay: false,
    grabCursor: true,

    pagination: {
      el: ".overview-pagi",
      clickable: true,
    },

    navigation: {
      nextEl: ".overview-nav .next",
      prevEl: ".overview-nav .prev",
    },

    breakpoints: {
      768: {
        slidesPerView: 1.12,
      },
    },

    on: {
      init(swiper) {
        swiper.update();
        setActiveState(swiper);
      },

      slideChangeTransitionEnd(swiper) {
        setActiveState(swiper);
      },
    },
  });
  function updateDiscoverOffset(swiper) {
    if (!swiper || !swiper.slides.length) return;
    const wrapper = swiper.wrapperEl;
    const activeSlide = swiper.slides[swiper.activeIndex];
    if (!activeSlide) return;
    const { width } = activeSlide.getBoundingClientRect();
    wrapper.style.setProperty("--offset-left", `${-Math.round(width)}px`);
  }
  function setActiveState(swiper) {
    const slides = swiper.slides;
    if (!slides.length) return;

    slides.forEach((slide) => {
      slide.classList.remove("is-active");
    });

    const activeIndex = swiper.activeIndex;

    if (slides[activeIndex]) {
      slides[activeIndex].classList.add("is-active");
    }

    if (slides[activeIndex - 1]) {
      slides[activeIndex - 1].classList.add("is-active");
    }
  }

  //=---------------------------
  if ($(".js-slider-thumbs").length && $(".js-slider-images").length) {
    const sliderThumbs = new Swiper(".js-slider-thumbs", {
      // direction: "vertical",
      slidesPerView: 4,
      spaceBetween: 0,
      speed: 800,
      navigation: {
        nextEl: ".slider-next",
        prevEl: ".slider-prev",
      },
      freeMode: true,
      breakpoints: {
        // 0: {
        //   direction: "horizontal",
        // },
        576: {
          direction: "vertical",
        },
      },
    });
    const sliderImages = new Swiper(".js-slider-images", {
      slidesPerView: 1,
      spaceBetween: 8,
      speed: 800,
      grabCursor: true,
      thumbs: {
        swiper: sliderThumbs,
      },
    });
  }

  return {
    init: initSwiper,
  };
}
