import $ from "jquery"

const contactModule = (function() {
  function submitMessage (e) {
    e.preventDefault()

    let postData = $(this).serializeArray()
    postData.push({ name: 'nonce', value: parameters.ajax_contact_message.nonce })
    postData.push({ name: 'action', value: parameters.ajax_contact_message.action })

    $.ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: $.param(postData),
      beforeSend: function () {
        alert('Loading...')
      },
    }).done((response) => {
      alert(response.message)
      $(this)[0].reset()
      // window.location.replace(response.data.redirect)
    }).fail((response) => {
      const err = response.responseJSON.errors

      /** Show error in DOM */
      if (err['email']) {
        $("#contact-message-email").addClass("input-invalid")

        $("#error-msg-email").show()
        let errorMsg = ''
        err['email'].forEach(err => {
          errorMsg += err + '<br>'
        })
        $("#error-msg-email").html(errorMsg)
      } else {
        $("#contact-message-email").removeClass("input-invalid")
        $("#error-msg-email").html('')
        $("#error-msg-email").hide()
      }

      if (err['name']) {
        $("#contact-message-name").addClass("input-invalid")

        $("#error-msg-name").show()
        let errorMsg = ''
        err['name'].forEach(err => {
          errorMsg += err + '<br>'
        })
        $("#error-msg-name").html(errorMsg)
      } else {
        $("#contact-message-name").removeClass("input-invalid")
        $("#error-msg-name").html('')
        $("#error-msg-name").hide()
      }

      if (err['subject']) {
        $("#contact-message-subject").addClass("input-invalid")

        $("#error-msg-subject").show()
        let errorMsg = ''
        err['subject'].forEach(err => {
          errorMsg += err + '<br>'
        })
        $("#error-msg-subject").html(errorMsg)
      } else {
        $("#contact-message-subject").removeClass("input-invalid")
        $("#error-msg-subject").html('')
        $("#error-msg-subject").hide()
      }

      alert(response.responseJSON.message)
    })
  }

  function initialize () {
    $('#form-contact-message').on("submit", submitMessage)
  }

  return {
    init: initialize
  }
})()

export default contactModule
