import $ from "jquery"

const lamModule = (function() {
  function submitMessage (e) {
    e.preventDefault()
    console.log('x')
    let postData = $(this).serializeArray()
    const theUrl = $(this).data('url')

    $.ajax({
      url: theUrl,
      method: 'POST',
      data: $.param(postData),
      statusCode: {
        200: function (response) {
          alert(response.message)
          $('#form-leave-a-message')[0].reset()
          // window.location.replace(response.data.redirect)
        },
        400: function (response) {
          const errors = response.responseJSON.errors

          if (errors['email']) {
            $("#lam-email").addClass("input-invalid")

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

          alert(response.responseJSON.message)
        },
        500: function (response) {
          alert(response.responseJSON.message)
        }
      },
      error: function(xhr, textStatus) {
        alert(textStatus)
      }
    })
  }

  function initialize () {
    $('#form-leave-a-message').on("submit", submitMessage)
  }

  return {
    init: initialize
  }
})()

export default lamModule
