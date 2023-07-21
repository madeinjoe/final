"use strict";
(this["webpackChunkhello_elementor_mi"] = this["webpackChunkhello_elementor_mi"] || []).push([["assets_src_js_lam-script_js"],{

/***/ "./assets/src/js/lam-script.js":
/*!*************************************!*\
  !*** ./assets/src/js/lam-script.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

var lamModule = function () {
  function submitMessage(e) {
    e.preventDefault();
    console.log('x');
    var postData = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).serializeArray();
    var theUrl = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).data('url');
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      url: theUrl,
      method: 'POST',
      data: jquery__WEBPACK_IMPORTED_MODULE_0___default().param(postData),
      statusCode: {
        200: function _(response) {
          alert(response.message);
          jquery__WEBPACK_IMPORTED_MODULE_0___default()('#form-leave-a-message')[0].reset();
          // window.location.replace(response.data.redirect)
        },

        400: function _(response) {
          var errors = response.responseJSON.errors;
          if (errors['email']) {
            jquery__WEBPACK_IMPORTED_MODULE_0___default()("#lam-email").addClass("input-invalid");
            jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").show();
            var errorMsg = '';
            errors['email'].forEach(function (err) {
              errorMsg += err + '<br>';
            });
            jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").html(errorMsg);
          } else {
            jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-egistraiton-email").removeClass("input-invalid");
            jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").html('');
            jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").hide();
          }
          alert(response.responseJSON.message);
        },
        500: function _(response) {
          alert(response.responseJSON.message);
        }
      },
      error: function error(xhr, textStatus) {
        alert(textStatus);
      }
    });
  }
  function initialize() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('#form-leave-a-message').on("submit", submitMessage);
  }
  return {
    init: initialize
  };
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (lamModule);

/***/ })

}]);
//# sourceMappingURL=assets_src_js_lam-script_js.js.map