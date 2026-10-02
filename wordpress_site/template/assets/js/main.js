/**
 * QUAN TRỌNG KHI DEPLOY — phải đổi version ở HAI chỗ, không phải một:
 *
 *   1. TN_THEME_VERSION trong wp-content/themes/tnstudio/functions.php
 *      -> làm mới main.js, CSS và các file JS được enqueue qua WordPress.
 *
 *   2. Chuỗi ?v= trong các dòng import ngay bên dưới
 *      -> làm mới các file module. WordPress KHÔNG chạm được vào đây vì đây là
 *         import tĩnh của ES module, trình duyệt tự tải thẳng.
 *
 * Chỉ bump chỗ 1 thì khách vẫn dùng module cũ đã cache, dù main.js đã mới.
 * Hai con số phải luôn khớp nhau.
 */

import AosModule from "./module/AosModule.js?v=4.4.2";
import MenuModule from "./module/MenuModule.js?v=4.5.3";
import SwiperModule from "./module/SwiperModule.js?v=4.5.3";
import AsideModule from "./module/AsideModule.js?v=4.4.2";
import ToggleFilter from "./module/ToggleFilter.js?v=4.4.2";
import UploadModule from "./module/UploadModule.js?v=4.4.2";
import CounterModule from "./module/CounterModule.js?v=4.4.2";
import HomeModule from "./module/HomeModule.js?v=4.4.7";
import FaqModule from "./module/FaqModule.js?v=4.4.2";
import ModalModule from "./module/ModalModule.js?v=4.4.2";
import PartnerModule from "./module/PartnerModule.js?v=4.4.2";
import ScrollTopModule from "./module/ScrollTopModule.js?v=4.4.2";
import Select2Module from "./module/Select2Module.js?v=4.4.2";
import './library/select2/select2.min.js?v=4.4.2';

window.addEventListener("DOMContentLoaded", () => {
  window.MONA_FUNCTION = {};

  AosModule();
  MenuModule();
  AsideModule();
  ToggleFilter();
  UploadModule();
  CounterModule();
  HomeModule();
  FaqModule();
  ModalModule();
  PartnerModule();
  ScrollTopModule();
  Select2Module();

  window.MONA_FUNCTION["swiper"] = SwiperModule().init;
});
