export default function ScrollTopModule() {
  $(document).ready(function () {
    const $backToTop = $(".backToTop");
    const $progressPath = $(".progress-path");
    const pathLength = 100;

    $progressPath.css({
      "stroke-dasharray": pathLength,
      "stroke-dashoffset": pathLength,
    });

    // Vẽ vòng tiến trình + bật/tắt nút theo tỉ lệ đã cuộn (0 -> 1)
    function renderProgress(ratio) {
      const safeRatio = Math.min(Math.max(ratio || 0, 0), 1);

      $progressPath.css("stroke-dashoffset", pathLength - safeRatio * pathLength);

      if (safeRatio > 0.01) {
        $backToTop.addClass("active");
      } else {
        $backToTop.removeClass("active");
      }
    }

    // Trang thường: tỉ lệ lấy từ scroll của window
    function updateProgressFromWindow() {
      const scroll = $(window).scrollTop();
      const height = $(document).height() - $(window).height();

      // Trang chủ dùng fullPage.js: nó dịch section bằng transform nên window
      // không hề cuộn, height = 0 -> (scroll / 0) ra NaN và vòng tiến trình
      // đứng im. Trang chủ được cập nhật riêng qua updateScrollProgress().
      if (height <= 0) return;

      renderProgress(scroll / height);
    }

    // HomeModule.js gọi vào đây từ callback afterLoad của fullPage.js
    window.MONA_FUNCTION = window.MONA_FUNCTION || {};
    window.MONA_FUNCTION.updateScrollProgress = renderProgress;

    $(window).on("scroll", updateProgressFromWindow);
    updateProgressFromWindow();

    // Cập nhật logic khi click vào nút
    $(".progress-wrap").on("click", function (event) {
      event.preventDefault();

      // fullPage.js chỉ được init khi >= 1200px (HomeModule.js). Dưới ngưỡng đó
      // thư viện vẫn được enqueue nên $.fn.fullpage tồn tại, nhưng moveTo thì
      // chưa được gán -> phải check chính moveTo, không check mỗi thư viện,
      // nếu không nút sẽ ném TypeError và chết hẳn ở mobile.
      const canUseFullpage =
        $("body").hasClass("p-home") &&
        typeof $.fn.fullpage !== "undefined" &&
        typeof $.fn.fullpage.moveTo === "function";

      if (canUseFullpage) {
        $.fn.fullpage.moveTo(1);
      } else {
        // "smooth" không phải duration hợp lệ của jQuery (nó âm thầm rơi về 400ms)
        $("html, body").animate({ scrollTop: 0 }, 600);
      }
    });
  });
}
