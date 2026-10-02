// 'use strict';

// export async function getProjectCategories() {
//     let body = await fetch(mona_params.ajaxURL, {
//         method: 'POST',
//         body: new URLSearchParams({
//             action: 'mona_ajax_get_project_categories',
//         })
//     });

//     return body.json();
// }

// export async function getProjects(categoryId, pageIndex = 1, pageSize = 12) {
//     const params = {
//         action: 'mona_ajax_get_projects',
//         pageIndex,
//         pageSize,
//     };

//     // Only add categoryId if provided (to support "All" tab)
//     if (categoryId) {
//         params.categoryId = categoryId;
//     }

//     let body = await fetch(mona_params.ajaxURL, {
//         method: 'POST',
//         body: new URLSearchParams(params)
//     });

//     return body.json();
// }

"use strict";

/**
 * Hàm lấy danh sách dự án (Hỗ trợ cả phân trang và lọc theo danh mục)
 * @param {number|null} categoryId - ID của danh mục (null hoặc undefined nếu chọn "Tất cả")
 * @param {number} pageIndex - Trang hiện tại
 * @param {number} pageSize - Số lượng item trên 1 trang
 */
export async function getProjects(
  categoryId = null,
  pageIndex = 1,
  pageSize = 3,
) {
  const params = {
    // Tùy biến: Nếu có categoryId thì gọi sang endpoint categories, ngược lại gọi endpoint gốc
    action: categoryId
      ? "mona_ajax_get_project_categories"
      : "mona_ajax_get_projects",
    paged: pageIndex,
    posts_per_page: pageSize,
    // Bổ sung nonce security để qua vòng kiểm tra check_ajax_referer của Mona backend
    // security:
    //   typeof mona_params !== "undefined" && mona_params.ajaxNonce
    //     ? mona_params.ajaxNonce
    //     : "",
  };

  if (categoryId) {
    params.categoryId = categoryId;
  }

  if (typeof mona_params !== "undefined" && mona_params.lang) {
    params.lang = mona_params.lang;
  }

  //   const ajaxURL =
  //     typeof mona_params !== "undefined" && mona_params.ajaxURL
  //       ? mona_params.ajaxURL
  //       : window.ajaxurl || "/wp-admin/admin-ajax.php";

  const ajaxURL = mona_params.ajaxURL;

  let body = await fetch(ajaxURL, {
    method: "POST",
    body: new URLSearchParams(params),
  });

  return body.json();
}

/**
 * Lấy danh sách danh mục dự án
 * Trả về cấu trúc giống: { success: true, data: { items: [ {id, name, slug, count}, ... ] } }
 */

export async function getProjectCategories() {
  const params = {
    action: "mona_ajax_get_project_category_list",
  };

  if (typeof mona_params !== "undefined" && mona_params.lang) {
    params.lang = mona_params.lang;
  }

  let body = await fetch(mona_params.ajaxURL, {
    method: "POST",
    body: new URLSearchParams(params),
  });

  return body.json();
}
