import $ from "jquery"

const shopModule = (function() {
  function showCountDown () {
    $('.product-sale-date').each((i, obj) => {
      const theID = $(obj).data('id')

      const currentTime = new Date()
      const fromTime = new Date($(obj).find('.from').data('from'))
      const toTime = new Date($(obj).find('.to').data('to'))

      if ((fromTime || toTime) && (fromTime > currentTime || currentTime < toTime)) {
        let day = null
        let hour = null
        let minute = null
        let second = null
        let note = null
        let difference = 0

        if (fromTime && currentTime < fromTime) {
          note = 'Sale start in :'

          difference = (fromTime - currentTime) / 1000
        } else if (toTime && fromTime < currentTime < toTime) {
          note = 'Sale ends in :'

          difference = (toTime - currentTime) / 1000
        }

        day = Math.abs(Math.floor(difference / (60 * 60 * 24)))
        hour = Math.abs(Math.floor(difference % (60 * 60 * 24) / (60 * 60) ))
        minute = Math.abs(Math.floor(difference % (60 * 60 * 24) % (60 * 60) / (60)))
        second = Math.abs(Math.floor(difference % 60))

        setInterval(function () {
          // Decrease second
          --second

          minute = (second < 0 ? --minute : minute)
          hour = (minute < 0 ? --hour : hour)
          day = (hour < 0 ? --day : day)

          if (day <= 0 && hour <= 0 && minute <= 0 && second <= 0) {
            clearInterval()
          }

          second = (second < 0 ? 59 : second)
          minute = (minute < 0 ? 59 : minute)
          hour = (hour < 0 ? 24 : hour)

          $('#'+theID).find("#countdown-note").html(note)
          $('#'+theID).find("#days").html(day)
          $('#'+theID).find("#hours").html(hour < 10 ? '0' + hour : hour)
          $('#'+theID).find("#minutes").html(minute < 10 ? '0' + minute : minute)
          $('#'+theID).find("#seconds").html(second < 10 ? '0' + second : second)
        }, 1000)
      }
    })
  }

  function addToCartGamesProduct (e) {
    e.preventDefault()

    const postData = $(this).serializeArray()
    postData.push({ name: 'nonce', value: parameters.ajax_add_to_cart.nonce })
    postData.push({ name: 'action', value: parameters.ajax_add_to_cart.action })

    $.ajax({
      url: parameters.url_admin_ajax,
      method: 'POST',
      data: $.param(postData),
      beforeSend: function () {
        alert('loading')
      }
    }).done((response) => {
      const message = response.message || 'Added to cart.'
      alert(message)
      $('form.cart')[0].reset()
    }).fail((response) => {
      alert(response.responseJSON.message)
    })
  }

  /**
   * Alter add to cart button for "virtual" product with category = "games".
   * "virtual" product with category "games" has 2 custom meta that required to fill.
   *
   * Selector : element(s) with class .virtual-games (element created from php).
   * redirect to : product single page.
   * */
  function addToCartButton () {
    $(".virtual-games").each((obj) => {
      const parent = $(obj).parent()
      const parentTarget = $(parent).attr("href")
      const parentSiblings = $(parent).siblings('a.button')

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

  function initialize () {
    showCountDown()
    addToCartButton()

    /**
     * Add Event listener in the single.
     * ONLY IF DOM with id virtual-games is exists. */
    if ($("#virtual-games").data("type") === 'virtual' && $("#virtual-games").data("category") === 'games') {
      $(".cart").on("submit", addToCartGamesProduct)
    }
  }

  return {
    init: initialize
  }
})()

export default shopModule
