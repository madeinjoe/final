import $ from "jquery"

const lamModule = (function() {
  function showCountDown () {
    $('.product-sale-date').each((i, obj) => {
      const theID = $(obj).data('id')

      const currentTime = new Date()
      const fromTime = new Date($(obj).find('.from').data('from'))
      const toTime = new Date($(obj).find('.to').data('to'))

      if ((fromTime || toTime) && (fromTime > currentTime || currentTime < toTime)) {
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
          $('#'+theID).find("#days").html(d)
          $('#'+theID).find("#hours").html(h < 10 ? '0' + h : h)
          $('#'+theID).find("#minutes").html(m < 10 ? '0' + m : m)
          $('#'+theID).find("#seconds").html(s < 10 ? '0' + s : s)
        }, 1000)
      }
    })
  }

  function atcGamesProduct (e) {
    e.preventDefault()

    const theUrl = $('input[name="url"]').val()
    const postData = $(this).serializeArray()

    $.ajax({
      url: theUrl,
      method: 'POST',
      data: $.param(postData),
      beforeSend: function () {
        alert('Loading...')
      },
      statusCode: {
        200: function (response) {
          // console.log(response)
          alert(response.message)
          $('form.cart')[0].reset()
        },
        400: function (response) {
          alert(response.responseJSON.message)
          // console.log(response.responseJSON)
        },
        500: function (response) {
          alert(response.responseJSON.message)
          // console.log(response.responseJSON)
        }
      }
    })
  }

  function atcButton () {
    $(".virtual-games").each((i, obj) => {
      const parent = $(obj).parent()
      const parentTarget = $(parent).attr("href")
      const parentSiblings = $(parent).siblings('a.button')
      // console.log($(parentSiblings).attr("href"))

      /** Give event listener to parents sibling */
      $(parentSiblings).removeClass("add_to_cart_button");
      $(parentSiblings).removeClass("ajax_add_to_cart_button");
      $(parentSiblings).html('Select Options')
      $(parentSiblings).attr("href", "#")

      $(parentSiblings).on("click", function (e) {
        e.preventDefault()
        window.location.replace(parentTarget)
      })
    })
  }

  // function test () {
  //   $(".woocommerce-loop-product__link").addClass('relative')
  // }

  function initialize () {
    showCountDown()
    atcButton()
    $(".cart").on("submit", atcGamesProduct)
    // test()
  }

  return {
    init: initialize
  }
})()

export default lamModule
