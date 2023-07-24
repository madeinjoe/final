"use strict";
(this["webpackChunkhello_elementor_mi"] = this["webpackChunkhello_elementor_mi"] || []).push([["assets_src_js_contact-script_js"],{

/***/ "./assets/src/js/contact-script.js":
/*!*****************************************!*\
  !*** ./assets/src/js/contact-script.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

var contactModule = function () {
  function submitMessage(e) {
    var _this = this;
    e.preventDefault();
    var postData = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).serializeArray();
    postData.push({
      name: 'nonce',
      value: parameters.ajax_contact_message.nonce
    });
    postData.push({
      name: 'action',
      value: parameters.ajax_contact_message.action
    });
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: jquery__WEBPACK_IMPORTED_MODULE_0___default().param(postData),
      beforeSend: function beforeSend() {
        alert('Loading...');
      }
    }).done(function (response) {
      alert(response.message);
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(_this)[0].reset();
      // window.location.replace(response.data.redirect)
    }).fail(function (response) {
      var err = response.responseJSON.errors;

      /** Show error in DOM */
      if (err['email']) {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#contact-message-email").addClass("input-invalid");
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").show();
        var errorMsg = '';
        err['email'].forEach(function (err) {
          errorMsg += err + '<br>';
        });
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").html(errorMsg);
      } else {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#contact-message-email").removeClass("input-invalid");
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").html('');
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").hide();
      }
      if (err['name']) {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#contact-message-name").addClass("input-invalid");
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-name").show();
        var _errorMsg = '';
        err['name'].forEach(function (err) {
          _errorMsg += err + '<br>';
        });
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-name").html(_errorMsg);
      } else {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#contact-message-name").removeClass("input-invalid");
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-name").html('');
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-name").hide();
      }
      if (err['subject']) {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#contact-message-subject").addClass("input-invalid");
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-subject").show();
        var _errorMsg2 = '';
        err['subject'].forEach(function (err) {
          _errorMsg2 += err + '<br>';
        });
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-subject").html(_errorMsg2);
      } else {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#contact-message-subject").removeClass("input-invalid");
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-subject").html('');
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-subject").hide();
      }
      alert(response.responseJSON.message);
    });
  }
  function initialize() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('#form-contact-message').on("submit", submitMessage);
  }
  return {
    init: initialize
  };
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (contactModule);

/***/ })

}]);
//# sourceMappingURL=assets_src_js_contact-script_js.js.map