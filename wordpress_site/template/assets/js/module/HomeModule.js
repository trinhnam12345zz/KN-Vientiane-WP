export default function HomeModule() {
  //--- Create Full page ---///
  jQuery(document).ready(function ($) {
    const width = $(window).width();

    // Helper: Safely play a video element
    function safePlayVideo(video) {
      if (video && video.paused) {
        const playPromise = video.play();
        if (playPromise !== undefined) {
          playPromise.catch(function () {
            // Browser autoplay restrictions handled gracefully
          });
        }
      }
    }

    // Helper: Resume all videos inside a container
    function resumeVideosInContainer($container) {
      if (!$container || !$container.length) return;
      $container.find("video").each(function () {
        safePlayVideo(this);
      });
    }

    if (width <= 991) {
      if ($("#onepage").length) {
        // add page number
        $("#onepage section").each(function (index) {
          $(this).addClass("page-" + (index + 1));
        });
      }
    }

    if (width >= 1200) {
      if ($("#onepage").length) {

        let _slugCounter = 0;
        function slugify(text) {
          var slug = text
            .toString()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, "-")
            .replace(/^-+|-+$/g, "")
            .replace(/-+/g, "-");
          // If slug is empty (e.g. pure CJK/Lao/Korean text), use a counter
          if (!slug) {
            _slugCounter++;
            slug = "section-" + _slugCounter;
          }
          return slug;
        }

        let targetTabToActivate = null;

        if ($("#onepage").length) {
          initFullPage();
        }

        function initFullPage() {
          if ($.fn.fullpage.destroy) {
            $.fn.fullpage.destroy("all");
          }

          // reset anchors mỗi lần init
          const anchors = [];
          $("#menu-fullpage li").hide();
          $(".is-full").each(function (index) {
            let rawAnchor = $(this).data("anchor");

            if (!rawAnchor) {
              rawAnchor = `section-${index + 1}`;
            }

            const finalAnchor = slugify(rawAnchor);
            $(this).attr("data-anchor", finalAnchor);

            // Visible label: use the translated data-anchor-label when present,
            // otherwise fall back to the raw anchor text. (Anchor/hash stays ASCII.)
            const anchorLabel = $(this).data("anchor-label") || rawAnchor;

            const $menuItem = $("#menu-fullpage li").eq(index);
            if ($menuItem.length) {
                $menuItem.show();
                $menuItem.attr("data-menuanchor", finalAnchor);
                $menuItem.find("a").attr("href", `#${finalAnchor}`);
                $menuItem.find(".txt-animate").text(anchorLabel);
            }

            anchors.push(finalAnchor);
          });

          $("#onepage").fullpage({
            licenseKey: "gplv3-license",
            sectionSelector: ".is-full",
            anchors: anchors,
            scrollingSpeed: 500,
            menu: "#menu-fullpage",
            // scrollOverflow: true,
            // responsiveHeight: 750,
            onLeave: function (origin, destination, direction) {
              // Bắt đầu chuyển section: Kích hoạt phát video sớm ở section đích
              resumeVideosInContainer($(destination.item));
            },
            afterLoad: function (origin, destination) {
              if (destination.index === 0) {
                $("body").addClass("is-white");
              } else {
                $("body").removeClass("is-white");
              }

              // Luôn đảm bảo video trong section hiện tại (đặc biệt khi cuộn ngược lên hero) tự động phát tiếp
              resumeVideosInContainer($(destination.item));

              // Trang chủ không cuộn window nên ScrollTopModule không tự tính
              // được tiến trình -> đẩy tỉ lệ section hiện tại sang cho nó.
              if (
                window.MONA_FUNCTION &&
                typeof window.MONA_FUNCTION.updateScrollProgress === "function"
              ) {
                const lastIndex = anchors.length - 1;

                window.MONA_FUNCTION.updateScrollProgress(
                  lastIndex > 0 ? destination.index / lastIndex : 0
                );
              }

              $(".is-full").find(".aos-init").removeClass("aos-animate");

              // animate section hiện tại
              setTimeout(() => {
                $(destination.item)
                  .find(".aos-init")
                  .addClass("aos-animate");
              }, 50);
            }
          });
        }
      }
    }

    // Global listener: Đảm bảo khi quay lại tab trình duyệt, video nền tự động phát tiếp
    document.addEventListener("visibilitychange", function () {
      if (!document.hidden) {
        $(".is-full.active, .slogan-bg").find("video").each(function () {
          safePlayVideo(this);
        });
      }
    });

    // IntersectionObserver cho video nền trên mọi trang / thiết bị
    if ("IntersectionObserver" in window) {
      const heroVideoObserver = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              safePlayVideo(entry.target);
            }
          });
        },
        { threshold: 0.1 }
      );

      $("video.mona-hero-video, .slogan-bg video").each(function () {
        heroVideoObserver.observe(this);
      });
    }
  });

  if ($('.ff-link').length) {
    const firstTab = $(".ff-link:first").data("tab");

    // Active tab content đầu tiên
    $("#" + firstTab).addClass("is-active");

    $(".ff-link").on("click", function () {
      const tabId = $(this).data("tab");
      // active button
      $(".ff-link").removeClass("is-current");
      $(this).addClass("is-current");
      // active tab content
      $(".news-req-slide").removeClass("is-active");
      $("#" + tabId).addClass("is-active");
    });
  }
}
