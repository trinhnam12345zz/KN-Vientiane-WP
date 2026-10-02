export default function AsideModule() {
  // Aside
  if ($(".aside-btn").length) {
    $(".aside-btn").on("click", function () {
      $(".aside-wrap").toggleClass("is-active");
      $(".aside-overlay").addClass("is-active");
      $("body").css("overflow", "hidden");
    });
  }

  if ($(".aside-close").length) {
    $(".aside-close").on("click", function () {
      $(".aside-wrap").toggleClass("is-active");
      $(".aside-overlay").removeClass("is-active");
      $("body").css("overflow", "hidden auto");
    });
  }

  if ($(".aside-overlay").length) {
    $(".aside-overlay").on("click", function () {
      $(".aside-wrap").toggleClass("is-active");
      $(".aside-overlay").removeClass("is-active");
      $("body").css("overflow", "hidden auto");
    });
  }
}
