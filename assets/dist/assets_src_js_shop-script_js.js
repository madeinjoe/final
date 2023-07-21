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

var lamModule = function () {
  function showCountDown() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.product-sale-date').each(function (i, obj) {
      var theID = jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).data('id');
      var currentTime = new Date();
      var fromTime = new Date(jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).find('.from').data('from'));
      var toTime = new Date(jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).find('.to').data('to'));
      if ((fromTime || toTime) && fromTime < currentTime < toTime) {
        var d = null;
        var h = null;
        var m = null;
        var s = null;
        var note = null;
        var diff = 0;
        if (fromTime && currentTime < fromTime) {
          note = 'Sale start in :';
          diff = (fromTime - currentTime) / 1000;
        } else if (toTime && fromTime < currentTime < toTime) {
          note = 'Sale ends in :';
          diff = (toTime - currentTime) / 1000;
        }
        d = Math.abs(Math.floor(diff / (60 * 60 * 24)));
        h = Math.abs(Math.floor(diff % (60 * 60 * 24) / (60 * 60)));
        m = Math.abs(Math.floor(diff % (60 * 60 * 24) % (60 * 60) / 60));
        s = Math.abs(Math.floor(diff % 60));
        setInterval(function () {
          // Decrease second
          --s;
          m = s < 0 ? --m : m;
          h = m < 0 ? --h : h;
          d = h < 0 ? --d : d;
          if (d < 0) {
            clearInterval();
          }
          s = s < 0 ? 59 : s;
          m = m < 0 ? 59 : m;
          h = h < 0 ? 24 : h;
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#countdown-note").html(note);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#countdown").find("#days").html(d);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#countdown").find("#hours").html(h < 10 ? '0' + h : h);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#countdown").find("#minutes").html(m < 10 ? '0' + m : m);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#' + theID).find("#countdown").find("#seconds").html(s < 10 ? '0' + s : s);
        }, 1000);
      }
    });
  }
  function atcGamesProduct(e) {
    e.preventDefault();
    var theUrl = jquery__WEBPACK_IMPORTED_MODULE_0___default()('input[name="url"]').val();
    var postData = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).serializeArray();
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      url: theUrl,
      method: 'POST',
      data: jquery__WEBPACK_IMPORTED_MODULE_0___default().param(postData),
      beforeSend: function beforeSend() {
        console.log(postData);
      },
      statusCode: {
        200: function _(response) {
          console.log(response);
          alert(response.message);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('form.cart')[0].reset();
        },
        400: function _(response) {
          alert(response.responseJSON.message);
          console.log(response.responseJSON);
        },
        500: function _(response) {
          alert(response.responseJSON.message);
          console.log(response.responseJSON);
        }
      },
      complete: function complete(xhr, textStatus) {
        alert(xhr.status);
      }
    });
  }
  function atcButton() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".virtual-games").each(function (i, obj) {
      var parent = jquery__WEBPACK_IMPORTED_MODULE_0___default()(obj).parent();
      var parentTarget = jquery__WEBPACK_IMPORTED_MODULE_0___default()(parent).attr("href");
      var parentSiblings = jquery__WEBPACK_IMPORTED_MODULE_0___default()(parent).siblings('a.button');
      // console.log($(parentSiblings).attr("href"))

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
    atcButton();
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".cart").on("submit", atcGamesProduct);
  }
  return {
    init: initialize
  };
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (lamModule);

/***/ })

}]);
//# sourceMappingURL=assets_src_js_shop-script_js.js.map