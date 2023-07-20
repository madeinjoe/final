import $ from "jquery";

export const showCountDown = function () {
  $('.product-sale-date').each((i, obj) => {
    const theID = $(obj).data('id')

    const currentTime = new Date()
    const fromTime = new Date($(obj).find('.from').data('from'))
    const toTime = new Date($(obj).find('.to').data('to'))

    if ((fromTime || toTime) && fromTime < currentTime < toTime) {
      let d = null
      let h = null
      let m = null
      let s = null
      let note = null
      let diff = 0

      if (fromTime && currentTime < fromTime) {
        note = 'Sale start in :'

        diff = (fromTime - currentTime) / 1000
      } else if (toTime && fromTime < currentTime < toTime) {
        note = 'Sale ends in :'

        diff = (toTime - currentTime) / 1000
      }

      d = Math.abs(Math.floor(diff / (60 * 60 * 24)))
      h = Math.abs(Math.floor(diff % (60 * 60 * 24) / (60 * 60) ))
      m = Math.abs(Math.floor(diff % (60 * 60 * 24) % (60 * 60) / (60)))
      s = Math.abs(Math.floor(diff % 60))

      setInterval(function () {
        // Decrease second
        --s

        m = (s < 0 ? --m : m)
        h = (m < 0 ? --h : h)
        d = (h < 0 ? --d : d)

        if (d < 0) {
          clearInterval()
        }
        s = (s < 0 ? 59 : s)
        m = (m < 0 ? 59 : m)
        h = (h < 0 ? 24 : h)

        $('#'+theID).find("#countdown-note").html(note)
        $('#'+theID).find("#countdown").find("#days").html(d)
        $('#'+theID).find("#countdown").find("#hours").html(h < 10 ? '0' + h : h)
        $('#'+theID).find("#countdown").find("#minutes").html(m < 10 ? '0' + m : m)
        $('#'+theID).find("#countdown").find("#seconds").html(s < 10 ? '0' + s : s)
      }, 1000)
    }
  })
}

export const addToCartGames = function () {
  $(".cart").on("submit", function (e) {
    e.preventDefault()

    const theUrl = $('input[name="url"]').val()
    const postData = $(this).serializeArray()
    // console.log(postData)

    $.ajax({
      url: theUrl,
      method: 'POST',
      data: $.param(postData),
      beforeSend: function () {
        console.log(postData)
      },
      statusCode: {
        200: function (response) {
          console.log(response)
        },
        400: function (response) {
          console.log(response.responseJSON)
        },
        500: function (response) {
          console.log(response.responseJSON)
        }
      },
      complete: function(xhr, textStatus) {
          console.log(xhr.status);
      }
    })
  })
}
const init = function () {
  showCountDown()
  addToCartGames()
}

try {
  init()
} catch (error) {

}