export default function AosModule() {
  AOS.init({
    startEvent: "DOMContentLoaded",
    offset: 0,
    duration: 700,
    delay: "100",
    easing: "ease",
    once: true,
    mirror: true,
    disable: function () {
      return $(window).width() <= 768;
    },
  });
}
