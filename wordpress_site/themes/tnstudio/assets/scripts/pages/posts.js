document.addEventListener("DOMContentLoaded", () => {
  class MonaNewsFilter {
    constructor() {
      this.filterButtons = document.querySelectorAll(".js-news-filter");
      this.newsList = document.querySelector(".js-news-list");
      this.pagination = document.querySelector(".js-news-pagination");

      this.taxonomies = {};

      this.bindEvents();
    }

    bindEvents() {
      this.filterButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
          e.preventDefault();

          this.handleFilter(button);
        });
      });

      document.addEventListener("click", (e) => {
        const paginationLink = e.target.closest(".js-news-pagination a");

        if (!paginationLink) return;

        e.preventDefault();

        const url = new URL(paginationLink.href);

        const paged = url.searchParams.get("paged") || 1;

        this.fetchPosts(paged);
      });
    }

    handleFilter(button) {
      this.filterButtons.forEach((btn) => {
        btn.classList.remove("is-active");
      });

      button.classList.add("is-active");

      const taxonomy = button.dataset.taxonomy;
      const termId = button.dataset.termId;

      this.taxonomies = {};

      if (termId) {
        this.taxonomies[taxonomy] = [termId];
      }

      this.fetchPosts(1);
    }

    async fetchPosts(paged = 1) {
      this.newsList.classList.add("processing");

      try {
        const formData = new FormData();

        formData.append("action", "mona_ajax_get_posts");

        formData.append("security", mona_params.ajaxNonce);

        formData.append("paged", paged);

        formData.append("posts_per_page", 6);

        if (mona_params.lang) {
          formData.append("lang", mona_params.lang);
        }

        Object.entries(this.taxonomies).forEach(([taxonomy, terms]) => {
          terms.forEach((termId, index) => {
            formData.append(`taxonomies[${taxonomy}][${index}]`, termId);
          });
        });

        const response = await fetch(mona_params.ajaxURL, {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (!result.success) {
          return;
        }

        this.newsList.innerHTML = result.data.posts_html;

        if (this.pagination) {
          this.pagination.innerHTML = result.data.pagination_html;
        }
      } catch (error) {
        console.error(error);
      }

      this.newsList.classList.remove("processing");
    }
  }

  new MonaNewsFilter();
});
