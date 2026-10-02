export default function PartnerModule() {
  $(document).ready(function () {
    if ($('.year-point').length) {
      // const $items = $('.year-point');
      // const $first = $items.first();
      // const $last = $items.last();

      // $first.addClass("is-active");
      // $first.find(".year-exp-list").slideDown(0);

      // $(".year-point").on("click", function () {
      //   const $this = $(this);
      //   const $expList = $this.find(".year-exp-list");

      //   $this.toggleClass("is-active");
      //   $expList.stop().slideToggle();
      // });
      const $items = $(".year-point");

      function checkScroll() {

        // vùng trigger khoảng 35% màn hình
        const triggerPoint = window.innerHeight * 0.35;
        const triggerMiddle = window.innerHeight / 2;

        // YEAR POINT
        $items.each(function () {
          const $item = $(this);
          const $txt = $item.find(".year-txt");
          const $list = $item.find(".year-exp-list");

          if ($item.hasClass("is-active")) return;

          const rect = $txt[0].getBoundingClientRect();

          if (rect.top <= triggerPoint) {
            $item.addClass("is-active");
            $list.stop(true, true).slideDown(400);

          }

        });

        // YEL ITEM

        $(".yel-item").each(function () {
          const $item = $(this);
          if ($item.hasClass("is-active")) return;
          const rect = this.getBoundingClientRect();

          /*
    
            item sẽ active khi:
    
            - top chưa vượt quá trigger
    
            - bottom vẫn còn nằm dưới trigger
    
          */

          if (
            rect.top <= triggerMiddle &&
            rect.bottom >= triggerMiddle
          ) {
            $item.addClass("is-active");
          }

        });

      }

      $(".year-exp-list").hide();

      checkScroll();

      $(window).on("scroll", function () {

        checkScroll();

      });
    }

    if ($('.business-action_item').length) {
      const viewportCenter = $(window).width() / 2;

      function classifyItems() {
        $(".business-action_item").each(function () {
          const $item = $(this);
          const itemLeft = $item.offset().left + ($item.outerWidth() / 2);
          $item.removeClass("is-left is-right");

          if (itemLeft >= viewportCenter) {
            $item.addClass("is-right");
          } else {
            $item.addClass("is-left");
          }
        });
      }

      classifyItems();

      $(window).on("resize", classifyItems);

      $(document).on("mouseenter", ".business-action_item", function () {
        $(this).addClass("is-active");
      });

      $(document).on("mouseleave", ".business-action_item", function () {
        $(this).removeClass("is-active");
      });
    }
  });
}