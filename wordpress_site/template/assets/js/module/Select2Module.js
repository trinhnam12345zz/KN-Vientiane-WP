export default function Select2Module() {
  jQuery(document).on("select2:opening select2:open", function (e) {
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
      setTimeout(function (e) {
        document.activeElement.blur();
      }, 1);
    }
  });
  if ($(".re-select-main").length) {
    $(document).ready(function () {
      $(".re-select-main").each(function () {
        // Get the first option's text as placeholder
        var firstOption = $(this).find("option:first");
        var placeholder = firstOption.length ? firstOption.text() : "";

        $(this).select2({
          minimumResultsForSearch: -1,
          placeholder: placeholder,
          dropdownCssClass: "custom-select2",
        });
      });
    });
  }
}
