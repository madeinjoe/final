$j = jQuery.noConflict()
$j(function ($) {
  $('#form-leave-a-message').on('submit', function (e) {
    e.preventDefault()

    let postData = $(this).serializeArray()
    postData.push({ name: 'action', value: lamData.action });
    postData.push({ name: 'nonce', value: lamData.token });

    console.log('a')
    console.log(postData)
    $.ajax({
      url: lamData.url,
      method: 'POST',
      data: $.param(postData),
      statusCode: {
        200: function (response) {
          console.log(response)
        },
        400: function (response) {
          console.log(response.responseJSON)
        }
      }
    })
  })
})