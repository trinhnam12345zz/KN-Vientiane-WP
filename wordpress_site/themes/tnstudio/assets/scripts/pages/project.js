"use strict";

import { getProjects, getProjectCategories } from "../modules/ProjectModule.js";
import Notification from "../modules/notification.js";

const notification = new Notification();

// Helpers: safe access to localized params/messages with defaults
function getMsg(key, def = "") {
  return typeof mona_params !== "undefined" &&
    mona_params.messages &&
    mona_params.messages[key]
    ? mona_params.messages[key]
    : def;
}

function getParam(key, def = "") {
  return typeof mona_params !== "undefined" && mona_params[key]
    ? mona_params[key]
    : def;
}

// Category
function renderListCategories(container, categories, includeAllTab = false) {
  let html = "";
  container.innerHTML = "";

  // Add "All" tab if requested
  if (includeAllTab) {
    const allLabel = (typeof mona_params !== "undefined" && mona_params.i18n && mona_params.i18n.all) || "Tất cả";
    html += `<div class="swiper-slide"><a class="tab-item is-active" name="category" data-category-id="" href="#"><span class="txt">${allLabel}</span></a></div>`;
  }

  if (categories.length) {
    for (let category of categories) {
      html += renderCategoryItem(category);
    }
  }

  container.innerHTML = html;
}

function renderCategoryItem(category) {
  let html = `<div class="swiper-slide"><a class="tab-item" name="category" data-category-id="${category.id}" href="#"><span class="txt">${category.name}</span></a></div>`;

  return html;
}

// project
function renderListProject(container, projects) {
  let html = "";
  container.innerHTML = "";

  if (projects.length) {
    for (let project of projects) {
      html += renderProjectItem(project);
    }
  } else {
    html = `<div class="col mona-empty">${getMsg("list_empty", "Không có dự án nào")}</div>`;
  }

  container.innerHTML = html;
}

function renderProjectItem(project) {
  let html = `<div class="news-box">
                <div class="project-item" data-id="${project.id}" data-cat_id="${project.dishCategoryId || ""}">
                  <div class="img-pj">
                    <img src="${project.thumbnail || getParam("image_default_url", "/template/assets/images/field/pj2.jpg")}" alt="${project.name}" title="${project.name}" loading="lazy">
                    <div class="pj-hub">
                      <div class="pj-hub-ic">
                        <img src="/template/assets/images/home/icon-bot.png" alt="icon" title="icon" loading="lazy">
                      </div>
                      <p class="text-16">${project.description || ""}</p>
                      <a class="btn" href="${project.permalink || "#"}">
                        <span>${(typeof mona_params !== "undefined" && mona_params.i18n && mona_params.i18n.projectDetail) || "Dự án chi tiết"}</span>
                        <img src="/template/assets/images/icons/arrow-right.svg" alt="" title="" loading="lazy">
                      </a>
                    </div>
                  </div>
                  <a class="pj-name" href="${project.permalink || "#"}">${project.name}</a>
                </div>
              </div>`;

  return html;
}

function renderPagination(container, currentPage, totalPage) {
  if (!container) return;

  if (totalPage <= 1) {
    container.innerHTML = "";
    return;
  }

  let html = `<ul class="page-numbers">`;

  // Prev
  html += `
    <li>
      <a 
        class="prev page-numbers ${currentPage <= 1 ? "disable" : ""}" 
        href="#"
        data-page="${currentPage - 1}"
      >
        <div class="page-number">
          <img src="/template/assets/images/icons/icon-arrow-prv.svg" loading="lazy">
        </div>
      </a>
    </li>
  `;

  // Pages
  for (let i = 1; i <= totalPage; i++) {
    if (i === currentPage) {
      html += `
        <li>
          <span class="page-numbers current">${i}</span>
        </li>
      `;
    } else {
      html += `
        <li>
          <a 
            class="page-numbers"
            href="#"
            data-page="${i}"
          >
            ${i}
          </a>
        </li>
      `;
    }
  }

  // Next
  html += `
    <li>
      <a 
        class="next page-numbers ${currentPage >= totalPage ? "disable" : ""}" 
        href="#"
        data-page="${currentPage + 1}"
      >
        <div class="page-number">
          <img src="/template/assets/images/icons/icon-arrow.svg" loading="lazy">
        </div>
      </a>
    </li>
  `;

  html += `</ul>`;

  container.innerHTML = html;
}

(function ($) {
  $(document).ready(function () {
    if ($(".sec-project-js").length) {
      // Data
      let categories = [];
      let projects = [];
      let activeProject = null;
      let currentPage = 1;
      let currentCategory = null;

      // Element
      const categoryContainer = document.getElementById("product-categories");
      const projectContainer = document.getElementById("project-list-js");
      const paginationContainer = document.querySelector(".pagination");

      // Load categories
      categoryContainer.classList.add("processing");
      getProjectCategories()
        .then((response) => {
          if (response.success) {
            categories = response.data.items || [];

            // Render categories with "All" tab at the beginning
            renderListCategories(categoryContainer, categories, true);

            const first_category = document.querySelector('a[name="category"]');
            if (first_category) {
              first_category.click();
            }
          } else {
            notification.error({
              title: getMsg("error_title", "Lỗi"),
              message: response.errors
                ? response.errors.join(", ")
                : "Unknown error",
            });
          }
        })
        .catch((err) => {
          console.error("Fetch categories failed:", err);
        })
        .finally(() => categoryContainer.classList.remove("processing"));

      // Click category
      $(document).on("click", 'a[name="category"]', async function (e) {
        e.preventDefault();

        projectContainer.classList.add("processing");
        $('a[name="category"]').removeClass("is-active");

        const _this = this;
        _this.classList.add("is-active");
        let categoryID = _this.dataset.categoryId || null; // Empty string becomes null for "All"

        currentCategory = categoryID;
        currentPage = 1;
        projects = [];

        loadProjects(categoryID, currentPage, false);
      });

      $(document).on(
        "click",
        ".pagination .page-numbers[data-page]",
        function (e) {
          e.preventDefault();

          if ($(this).hasClass("disable")) return;

          const page = parseInt($(this).data("page"));

          if (!page || page < 1) return;

          currentPage = page;

          loadProjects(currentCategory, currentPage);
        },
      );

      // Initial project load when entering the page
      function initializeProjectList() {
        const firstCategoryBtn =
          document.querySelector('a[name="category"].active') ||
          document.querySelector('a[name="category"]');
        if (firstCategoryBtn) {
          firstCategoryBtn.click();
          return;
        }

        loadProjects(null, currentPage, false);
      }

      initializeProjectList();

      // Function to load projects
      function loadProjects(categoryID, pageIndex, append = false) {
        if (!append) {
          projectContainer.classList.add("processing");
        }

        getProjects(categoryID, pageIndex, 12)
          .then((response) => {
            if (response.success) {
              const responseData = response.data || {};
              const newProjects = responseData.items || [];
              const totalItem = responseData.totalItem || 0;
              const totalPage = responseData.totalPage || 0;
              const currentPageIndex = responseData.pageIndex || 1;

              if (append) {
                // Append new projects to existing list
                projects = projects.concat(newProjects);
                // Append to DOM instead of replacing
                const projectHTML = newProjects
                  .map((project) => renderProjectItem(project))
                  .join("");
                projectContainer.insertAdjacentHTML("beforeend", projectHTML);
              } else {
                // Replace projects list
                projects = newProjects;
                renderListProject(projectContainer, projects);
              }

              renderPagination(
                paginationContainer,
                currentPageIndex,
                totalPage,
              );

              //   TabModule();
            } else {
              notification.error({
                title: getMsg("error_title", "Lỗi"),
                message: response.data.message,
              });
            }
          })
          .catch((err) => {
            console.error("Fetch projects failed:", err);
          })
          .finally(() => {
            projectContainer.classList.remove("processing");
          });
      }
    }
  });
})(jQuery);
