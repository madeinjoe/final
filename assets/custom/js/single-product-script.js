$j = jQuery.noConflict()
$j(function ($) {
  var d = parseInt(productData.item_sale_diff.days)
  var h = parseInt(productData.item_sale_diff.hours)
  var m = parseInt(productData.item_sale_diff.minutes)
  var s = parseInt(productData.item_sale_diff.seconds)

  const countdown = setInterval(function () {
    // Decrease second
    --s

    m = (s < 0 ? --m : m)
    h = (m < 0 ? --h : h)
    d = (h < 0 ? --d : d)

    if (d < 0) {
      clearInterval(count)
    }
    s = (s < 0 ? 59 : s)
    m = (m < 0 ? 59 : m)
    h = (h < 0 ? 24 : h)

    $("#countdown").find("#days").html(d)
    $("#countdown").find("#hours").html(h < 10 ? '0' + h : h)
    $("#countdown").find("#minutes").html(m < 10 ? '0' + m : m)
    $("#countdown").find("#seconds").html(s < 10 ? '0' + s : s)
  }, 1000)
})