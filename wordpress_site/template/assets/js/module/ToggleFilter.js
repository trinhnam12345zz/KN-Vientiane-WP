export default function ToggleFilter() {
  if ($('.news-cate-group').length) {
    $(document).ready(function () {
      const $group = $('.news-cate-group');
      const $overlay = $('.overlay');

      // Mở menu
      $('.btn-open-mb').on('click', function (e) {
        e.stopPropagation();
        $group.addClass('is-active');
        $overlay.addClass('is-active');

        $("body").css("overflow", "hidden");
      });

      // Đóng bằng nút close
      $('.btn-clost-mb').on('click', function (e) {
        e.stopPropagation();
        $group.removeClass('is-active');
        $overlay.removeClass('is-active');
        $("body").css("overflow", "unset");
      });

      // Click ra ngoài thì đóng
      $(document).on('click', function (e) {
        if (!$(e.target).closest('.news-cate-group').length) {
          $group.removeClass('is-active');
          $overlay.removeClass('is-active');
          $("body").css("overflow", "unset");
        }
      });
    });
  }

  if ($('.js-toggle-item').length) {
    $(".sa-i-item").on("click", function () {
      const $this = $(this);
      if ($this.hasClass("active")) {
        $this.removeClass("active");
        $this.find(".i-desc").stop(true, true).slideUp();
        $this.find(".i-item-img").stop(true, true).slideUp();
        return;
      }

      // Đóng tất cả item khác
      $(".sa-i-item.active")
        .removeClass("active")
        .find(".i-desc, .i-item-img")
        .stop(true, true)
        .slideUp();

      // Mở item hiện tại
      $this.addClass("active");
      $this.find(".i-desc").stop(true, true).slideDown();
      $this.find(".i-item-img").stop(true, true).slideDown();
    });

    $(".sa-i-item:first")
      .addClass("active")
      .find(".i-desc, .i-item-img")
      .show();
  }

}