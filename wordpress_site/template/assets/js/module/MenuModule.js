export default function MenuModule() {
  $(document).ready(function () {
    let width = $(window).width();

    if (width < 1201) {
      $(".js-child0, .js-child1, .js-child2").hide(``);

      $(".js-dropdown").click(function (e) {
        e.stopPropagation();

        let childDropdowns = $(this).children(
          ".js-child0, .js-child1, .js-child2"
        );

        if ($(this).hasClass("is-active")) {
          childDropdowns.stop().slideUp();
          $(this).removeClass("is-active");
        } else {
          childDropdowns.stop().slideDown();
          $(this).addClass("is-active");
        }
      });

      $(document).click(function (e) {
        if (!$(e.target).closest(".js-dropdown").length) {
          $(".js-child0, .js-child1, .js-child2").slideUp();
          $(".js-dropdown").removeClass("is-active");
        }
      });
    }
  });

  $(document).on("click", ".js-bar", function () {
    $(this).toggleClass("is-active");
    $(".js-menu").toggleClass("is-active");

    if ($(this).hasClass("is-active")) {
      $(".main").off("touchmove");
      $("body").css("overflow", "hidden");
    } else {
      $("body").css("overflow", "unset");
    }
  });

  $(document).on("click", ".overlay, .js-btn-close", closeMenu);

  // Click vào mục menu thì thu menu lại.
  $(document).on("click", ".js-menu a", function () {
    const $link = $(this);
    const opensSubmenu =
      $link.closest(".js-dropdown").length &&
      $link.siblings(".js-child0, .js-child1, .js-child2").length;

    if (opensSubmenu) return;

    closeMenu();
  });

  function closeMenu() {
    $(".js-bar, .js-menu").removeClass("is-active");
    $("body").css("overflow", "unset");

    $(".js-child0, .js-child1, .js-child2").slideUp();
    $(".js-dropdown").removeClass("is-active");
  }

  // Onscroll
  $(document).ready(function () {
    if ($("body").hasClass("p-home")) return;
    if ($(document).scrollTop() > 20) {
      if ($(".js-header").length) {
        $(".js-header").addClass("is-fixed");
      }
    } else {
      if ($(".js-header").length) {
        $(".js-header").removeClass("is-fixed");
      }
    }
  });

  $(window).scroll(function () {
    if ($("body").hasClass("p-home")) return;
    if ($(document).scrollTop() > 20) {
      if ($(".js-header").length) {
        $(".js-header").addClass("is-fixed");
      }
    } else {
      if ($(".js-header").length) {
        $(".js-header").removeClass("is-fixed");
      }
    }
  });

  // Hide Header
  $(document).ready(function () {
    let lastScrollTop = 0;
    $(window).scroll(function () {
      let currentScroll = $(this).scrollTop();
      if (
        currentScroll > lastScrollTop &&
        currentScroll > 100 &&
        !$(".js-search-box").hasClass("is-active")
      ) {
        if ($(".js-header").length) {
          $(".js-header").addClass("is-hidden");
        }
      } else {
        if ($(".js-header").length) {
          $(".js-header").removeClass("is-hidden");
        }
      }
      lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
  });

  // ===== Get Height Of Footer =====
  function vh(percent) {
    var h = Math.max(
      document.documentElement.clientHeight,
      window.innerHeight || 0
    );
    return (percent * h) / 100;
  }
  let heightFooter;
  if ($(".js-footer").length) {
    heightFooter = $(".js-footer").outerHeight(true);
  } else {
    heightFooter = 0;
  }
  let heightHeight;
  if ($(".js-header").length) {
    heightHeight = $(".js-header").outerHeight(true);
  } else {
    heightHeight = 0;
  }

  let mainHeight = vh(100) - heightFooter;
  if ($(".main").length) {
    $(".main").css("min-height", mainHeight);
    if (!$("body").hasClass("homes")) {
      $(".main").css("padding-top", heightHeight);
    }
  }
}
