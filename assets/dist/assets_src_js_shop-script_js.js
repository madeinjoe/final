"use strict";
(this["webpackChunkhello_elementor_mi"] = this["webpackChunkhello_elementor_mi"] || []).push([["assets_src_js_shop-script_js"],{

/***/ "./assets/src/js/shop-script.js":
/*!**************************************!*\
  !*** ./assets/src/js/shop-script.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

var shopModule = function () {
  function showCountDown() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.product-sale-date').each(function (i, obj) {
      var theID = jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).data('id');
      var currentTime = new Date();
      var fromTime = new Date(jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).find('.from').data('from'));
      var toTime = new Date(jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).find('.to').data('to'));
      if ((fromTime || toTime) && (fromTime > currentTime || currentTime < toTime)) {
        var day = null;
        var hour = null;
        var minute = null;
        var second = null;
        var note = null;
        var difference = 0;
        if (fromTime && currentTime < fromTime) {
          note = 'Sale start in :';
          difference = (fromTime - currentTime) / 1000;
        } else if (toTime && fromTime < currentTime < toTime) {
          note = 'Sale ends in :';
          difference = (toTime - currentTime) / 1000;
        }
        day = Math.abs(Math.floor(difference / (60 * 60 * 24)));
        hour = Math.abs(Math.floor(difference % (60 * 60 * 24) / (60 * 60)));
        minute = Math.abs(Math.floor(difference % (60 * 60 * 24) % (60 * 60) / 60));
        second = Math.abs(Math.floor(difference % 60));
        setInterval(function () {
          // Decrease second
          --second;
          minute = second < 0 ? --minute : minute;
          hour = minute < 0 ? --hour : hour;
          day = hour < 0 ? --day : day;
          if (day <= 0 && hour <= 0 && minute <= 0 && second <= 0) {
            clearInterval();
          }
          second = second < 0 ? 59 : second;
          minute = minute < 0 ? 59 : minute;
          hour = hour < 0 ? 24 : hour;
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#countdown-note").html(note);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#days").html(day);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#hours").html(hour < 10 ? '0' + hour : hour);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#minutes").html(minute < 10 ? '0' + minute : minute);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#seconds").html(second < 10 ? '0' + second : second);
        }, 1000);
      }
    });
  }
  function addToCartGamesProduct(e) {
    e.preventDefault();
    var postData = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).serializeArray();
    postData.push({
      name: 'nonce',
      value: parameters.ajax_add_to_cart.nonce
    });
    postData.push({
      name: 'action',
      value: parameters.ajax_add_to_cart.action
    });
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: jquery__WEBPACK_IMPORTED_MODULE_0___default().param(postData),
      beforeSend: function beforeSend() {
        alert('loading');
      }
    }).done(function (response) {
      var message = response.message || 'Added to cart.';
      alert(message);
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('form.cart')[0].reset();
    }).fail(function (response) {
      alert(response.responseJSON.message);
    });
  }

  /**
   * Alter add to cart button for "virtual" product with category = "games".
   * "virtual" product with category "games" has 2 custom meta that required to fill.
   *
   * Selector : element(s) with class .virtual-games (element created from php).
   * redirect to : product single page.
   * */
  function addToCartButton() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".virtual-games").each(function (obj) {
      var parent = jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).parent();
      var parentTarget = jquery__WEBPACK_IMPORTED_MODULE_0___default()(parent).attr("href");
      var parentSiblings = jquery__WEBPACK_IMPORTED_MODULE_0___default()(parent).siblings('a.button');

      /** Give event listener to parents sibling */
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(parentSiblings).removeClass("add_to_cart_button");
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(parentSiblings).removeClass("ajax_add_to_cart_button");
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(parentSiblings).html('Select Options');
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(parentSiblings).attr("href", "#");
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(parentSiblings).on("click", function (e) {
        e.preventDefault();
        window.location.replace(parentTarget);
      });
    });
  }
  function initialize() {
    showCountDown();
    addToCartButton();

    /**
     * Add Event listener in the single.
     * ONLY IF DOM with id virtual-games is exists. */
    if (jquery__WEBPACK_IMPORTED_MODULE_0___default()("#virtual-games").data("type") === 'virtual' && jquery__WEBPACK_IMPORTED_MODULE_0___default()("#virtual-games").data("category") === 'games') {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(".cart").on("submit", addToCartGamesProduct);
    }
  }
  return {
    init: initialize
  };
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (shopModule);

/***/ })

}]);
//# sourceMappingURL=assets_src_js_shop-script_js.js.map