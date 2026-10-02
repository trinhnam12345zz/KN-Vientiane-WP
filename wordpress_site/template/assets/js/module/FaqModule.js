export default function FaqModule() {
  $(document).ready(function () {
    if ($(".faq-drop").length) {
      $(".faq-drop .faq-item:first").addClass("is-active").find(".fi-content").show();

      $(".faq-drop .faq-item").on("click", function () {
        const $this = $(this);

        if ($this.hasClass("is-active")) {
          $this.removeClass("is-active");
          $this.find(".fi-content").stop(true, true).slideUp();
        } else {
          $(".faq-drop .faq-item")
            .removeClass("is-active")
            .find(".fi-content")
            .stop(true, true)
            .slideUp();

          // Open current item
          $this.addClass("is-active");
          $this.find(".fi-content").stop(true, true).slideDown();
        }
      });

    }
  });
}