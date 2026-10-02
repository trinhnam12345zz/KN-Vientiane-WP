export default function ModalModule() {
  jQuery(document).ready(function ($) {
    setTimeout(() => {
      if (
        $("a[rel='modal:open']").length ||
        $("a[rel='modal:open nofollow']").length
      ) {
        $("a[rel='modal:open'], a[rel='modal:open nofollow']").on(
          "click",
          function () {
            $(this).modal({
              fadeDuration: 250,
            });

            // --- TikTok ---
            if ($("#tiktokModal").length && $(this).data("tiktok")) {
              const tiktokUrl = $(this).data("tiktok");
              const match = tiktokUrl.match(/video\/(\d+)/);
              const id = match ? match[1] : "";

              if (id) {
                $("#tiktokModal iframe").attr(
                  "src",
                  "https://www.tiktok.com/player/v1/" +
                  id +
                  "?&music_info=0&description=0&autoplay=1"
                );
              }
            }

            // --- HTML5 Video autoplay ---
            const target = $(this).attr("href");

            if (target && $(target).find("video").length) {
              const video = $(target).find("video").get(0);

              if (video) {
                video.play();
              }
            }

            return false;
          }
        );

        // --- Close modal ---
        $(document).on($.modal.CLOSE, function () {
          // reset all html5 video
          $("video").each(function () {
            this.pause();
            this.currentTime = 0;
          });

          $("#tiktokModal iframe").attr("src", "");
        });
      }
    }, 1500);
  });
}