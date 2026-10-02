document.addEventListener("click", async function (e) {
  const button = e.target.closest(".copy-link-btn");

  if (!button) return;

  const link = button.dataset.link;

  try {
    await navigator.clipboard.writeText(link);

    // Remove old tooltip
    const oldTooltip = button.querySelector(".copy-tooltip");

    if (oldTooltip) {
      oldTooltip.remove();
    }

    // Create tooltip
    const tooltip = document.createElement("span");

    tooltip.className = "copy-tooltip";
    tooltip.innerText =
      (typeof mona_params !== "undefined" && mona_params.i18n && mona_params.i18n.copied) ||
      "Đã sao chép";

    button.appendChild(tooltip);

    // Show animation
    requestAnimationFrame(() => {
      tooltip.classList.add("show");
    });

    // Remove tooltip
    setTimeout(() => {
      tooltip.classList.remove("show");

      setTimeout(() => {
        tooltip.remove();
      }, 300);
    }, 2000);
  } catch (error) {
    console.error("Copy failed:", error);
  }
});

// Language switcher dropdown toggle (WPML)
document.addEventListener("click", function (e) {
  const toggle = e.target.closest(".js-lang-toggle");
  const langBtn = document.querySelector(".lang-btn.js-lang");
  if (!langBtn) return;

  if (toggle && langBtn.contains(toggle)) {
    e.preventDefault();
    langBtn.classList.toggle("is-open");
    return;
  }

  // click outside closes the menu
  if (!e.target.closest(".lang-btn.js-lang")) {
    langBtn.classList.remove("is-open");
  }
});
