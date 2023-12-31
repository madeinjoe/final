import $ from "jquery"
const admissionModule = (function() {
  function passwordToggle (e) {
    $(e.target).addClass("hidden")

    if ($(e.target).hasClass("pw-s")) {
      $(e.target).siblings('.pw-h').removeClass('hidden')
      $(e.target).siblings('input').attr('type', 'text')
    } else {
      $(e.target).siblings('input').attr('type', 'password')
      $(e.target).siblings('.pw-s').removeClass('hidden')
    }
  }

  function submitRegistration (e) {
    e.preventDefault()

    const postData = $(this).serializeArray()
    postData.push({ name: 'nonce', value: parameters.ajax_custom_registration.nonce })
    postData.push({ name: 'action', value: parameters.ajax_custom_registration.action })

    $.ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: $.param(postData),
      beforeSend: function () {
        console.log(postData)
        alert('loading')
      }
    }).done((response) => {
      $('#success-msg').html(response.message + `or <a href="`+ response.data.redirect + `" class="underline cursor-pointer">Click here to login</a>`)
      $('#success-msg').removeClass('hidden')
      $('#error-msg').hasClass('hidden') ? $('#error-msg').hide() : $('#error-msg').addClass('hidden')
      alert(response.message)
      $('#registration-form')[0].reset()
      // window.location.replace(response.data.redirect)
    }).fail((response) => {
      if (response.status >= 500) {
        $('#error-msg').html(response.responseJSON.message)
        $('#error-msg').removeClass('hidden')
        alert(response.responseJSON.message)
      } else {
        document.getElementById('error-msg').innerHTML = response.responseJSON.message
        $('#success-msg').hasClass('hidden') ? $('#success-msg').hide() : $('#success-msg').addClass('hidden')
        $('#error-msg').removeClass('hidden')
        const errors = response.responseJSON.errors

        if (errors['username']) {
          $("#ft-registration-username").addClass("input-invalid")

          $("#error-msg-username").show()
          let errorMsg = ''
          errors['username'].forEach(err => {
            errorMsg += err + '<br>'
          })
          $("#error-msg-username").html(errorMsg)
        } else {
          $("#ft-registration-username").removeClass("input-invalid")
          $("#error-msg-username").html('')
          $("#error-msg-username").hide()
        }

        if (errors['email']) {
          $("#ft-registration-email").addClass("input-invalid")

          $("#error-msg-email").show()
          let errorMsg = ''
          errors['email'].forEach(err => {
            errorMsg += err + '<br>'
          })
          $("#error-msg-email").html(errorMsg)
        } else {
          $("#ft-egistraiton-email").removeClass("input-invalid")
          $("#error-msg-email").html('')
          $("#error-msg-email").hide()
        }

        if (errors['password']) {
          $("#ft-registration-password").addClass("input-invalid")

          $("#error-msg-password").show()
          let errorMsg = ''
          errors['password'].forEach(err => {
            errorMsg += err + '<br>'
          })
          $("#error-msg-password").html(errorMsg)
        } else {
          $("#ft-registration-email").removeClass("input-invalid")
          $("#error-msg-password").html('')
          $("#error-msg-password").hide()
        }

        if (errors['re-password']) {
          $("#ft-registration-re-password").addClass("input-invalid")

          $("#error-msg-re-password").show()
          let errorMsg = ''
          errors['re-password'].forEach(err => {
            errorMsg += err + '<br>'
          })
          $("#error-msg-re-password").html(errorMsg)
        } else {
          $("#ft-registration-email").removeClass("input-invalid")
          $("#error-msg-re-password").html('')
          $("#error-msg-re-password").hide()
        }

        alert(response.responseJSON.message)
      }
    })
  }

  function submitLogin (e) {
    e.preventDefault()

    const postData = $(this).serializeArray()
    postData.push({ name: 'nonce', value: parameters.ajax_custom_login.nonce })
    postData.push({ name: 'action', value: parameters.ajax_custom_login.action })

    $.ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: $.param(postData),
      beforeSend: function () {
        // console.log(postData)
        alert('Logging you in...')
      }
    }).done((response) => {
      alert(response.message)
      $('#login-form')[0].reset()
      window.location.replace(response.data.redirect)
    }).fail((response) => {
      if (response.status >= 500) {
        $('#error-msg').html(response.responseJSON.message)
        $('#error-msg').removeClass('hidden')
        alert(response.responseJSON.message)
      } else {
        document.getElementById('error-msg').innerHTML = response.responseJSON.message
        $('#success-msg').hasClass('hidden') ? $('#success-msg').hide() : $('#success-msg').addClass('hidden')
        $('#error-msg').removeClass('hidden')
        const errors = response.responseJSON.errors

        if (errors['username']) {
          $("#ft-login-username").addClass("input-invalid")

          $("#error-msg-username").show()
          let errorMsg = ''
          errors['username'].forEach(err => {
            errorMsg += err + '<br>'
          })
          $("#error-msg-username").html(errorMsg)
        } else {
          $("#ft-login-username").removeClass("input-invalid")
          $("#error-msg-username").html('')
          $("#error-msg-username").hide()
        }

        alert(response.responseJSON.message)
      }
    })
  }

  function initialize () {
    $(document).on("submit", "#login-form", submitLogin)
    $('#registration-form').on("submit", submitRegistration)
    $(".password-sh-toggle").on("click", passwordToggle)
  }

  return {
    init: initialize,
  }
})()

export default admissionModule
