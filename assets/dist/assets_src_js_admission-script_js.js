"use strict";
(this["webpackChunkhello_elementor_mi"] = this["webpackChunkhello_elementor_mi"] || []).push([["assets_src_js_admission-script_js"],{

/***/ "./assets/src/js/admission-script.js":
/*!*******************************************!*\
  !*** ./assets/src/js/admission-script.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

var admissionModule = function () {
  function passwordToggle(e) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).addClass("hidden");
    if (jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).hasClass("pw-s")) {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).siblings('.pw-h').removeClass('hidden');
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).siblings('input').attr('type', 'text');
    } else {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).siblings('input').attr('type', 'password');
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).siblings('.pw-s').removeClass('hidden');
    }
  }
  function submitRegistration(e) {
    e.preventDefault();
    var postData = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).serializeArray();
    postData.push({
      name: 'nonce',
      value: parameters.ajax_custom_registration.nonce
    });
    postData.push({
      name: 'action',
      value: parameters.ajax_custom_registration.action
    });
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: jquery__WEBPACK_IMPORTED_MODULE_0___default().param(postData),
      beforeSend: function beforeSend() {
        console.log(postData);
        alert('loading');
      }
    }).done(function (response) {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').html(response.message + "or <a href=\"" + response.data.redirect + "\" class=\"underline cursor-pointer\">Click here to login</a>");
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').removeClass('hidden');
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').hasClass('hidden') ? jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').hide() : jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').addClass('hidden');
      alert(response.message);
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('#registration-form')[0].reset();
      // window.location.replace(response.data.redirect)
    }).fail(function (response) {
      if (response.status >= 500) {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').html(response.responseJSON.message);
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').removeClass('hidden');
        alert(response.responseJSON.message);
      } else {
        document.getElementById('error-msg').innerHTML = response.responseJSON.message;
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').hasClass('hidden') ? jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').hide() : jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').addClass('hidden');
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').removeClass('hidden');
        var errors = response.responseJSON.errors;
        if (errors['username']) {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-registration-username").addClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").show();
          var errorMsg = '';
          errors['username'].forEach(function (err) {
            errorMsg += err + '<br>';
          });
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").html(errorMsg);
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-registration-username").removeClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").html('');
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").hide();
        }
        if (errors['email']) {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-registration-email").addClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").show();
          var _errorMsg = '';
          errors['email'].forEach(function (err) {
            _errorMsg += err + '<br>';
          });
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").html(_errorMsg);
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-egistraiton-email").removeClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").html('');
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-email").hide();
        }
        if (errors['password']) {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-registration-password").addClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-password").show();
          var _errorMsg2 = '';
          errors['password'].forEach(function (err) {
            _errorMsg2 += err + '<br>';
          });
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-password").html(_errorMsg2);
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-registration-email").removeClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-password").html('');
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-password").hide();
        }
        if (errors['re-password']) {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-registration-re-password").addClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-re-password").show();
          var _errorMsg3 = '';
          errors['re-password'].forEach(function (err) {
            _errorMsg3 += err + '<br>';
          });
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-re-password").html(_errorMsg3);
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-registration-email").removeClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-re-password").html('');
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-re-password").hide();
        }
        alert(response.responseJSON.message);
      }
    });
  }
  function submitLogin(e) {
    e.preventDefault();
    var postData = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).serializeArray();
    postData.push({
      name: 'nonce',
      value: parameters.ajax_custom_login.nonce
    });
    postData.push({
      name: 'action',
      value: parameters.ajax_custom_login.action
    });
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: jquery__WEBPACK_IMPORTED_MODULE_0___default().param(postData),
      beforeSend: function beforeSend() {
        // console.log(postData)
        alert('Logging you in...');
      }
    }).done(function (response) {
      alert(response.message);
      jquery__WEBPACK_IMPORTED_MODULE_0___default()('#login-form')[0].reset();
      window.location.replace(response.data.redirect);
    }).fail(function (response) {
      if (response.status >= 500) {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').html(response.responseJSON.message);
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').removeClass('hidden');
        alert(response.responseJSON.message);
      } else {
        document.getElementById('error-msg').innerHTML = response.responseJSON.message;
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').hasClass('hidden') ? jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').hide() : jquery__WEBPACK_IMPORTED_MODULE_0___default()('#success-msg').addClass('hidden');
        jquery__WEBPACK_IMPORTED_MODULE_0___default()('#error-msg').removeClass('hidden');
        var errors = response.responseJSON.errors;
        if (errors['username']) {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-login-username").addClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").show();
          var errorMsg = '';
          errors['username'].forEach(function (err) {
            errorMsg += err + '<br>';
          });
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").html(errorMsg);
        } else {
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#ft-login-username").removeClass("input-invalid");
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").html('');
          jquery__WEBPACK_IMPORTED_MODULE_0___default()("#error-msg-username").hide();
        }
        alert(response.responseJSON.message);
      }
    });
  }
  function initialize() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(document).on("submit", "#login-form", submitLogin);
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('#registration-form').on("submit", submitRegistration);
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".password-sh-toggle").on("click", passwordToggle);
  }
  return {
    init: initialize
  };
}();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (admissionModule);

/***/ })

}]);
//# sourceMappingURL=assets_src_js_admission-script_js.js.map