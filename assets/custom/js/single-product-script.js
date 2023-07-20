import $ from "jquery";

export const showCountDown = function () {
  const currentTime = new Date()
  const fromTime = new Date($(obj).find('.from').data('from'))
  const toTime = new Date($(obj).find('.to').data('to'))

  if (fromTime || toTime) {
    if (fromTime && currentTime < fromTime) {
      var note = 'Sale start in :'

      var diff = (fromTime - currentTime) / 1000
      var d = Math.floor(diff / (60 * 60 * 24))
      var h = Math.floor(diff % (60 * 60 * 24) / (60 * 60) )
      var m = Math.floor(diff % (60 * 60 * 24) % (60 * 60) / (60))
      var s = Math.floor(diff % 60)
    } else if (toTime && currentTime < toTime) {
      var note = 'Sale ends in :'

      var diff = (toTime - currentTime) / 1000
      var d = Math.floor(diff / (60 * 60 * 24))
      var h = Math.floor(diff % (60 * 60 * 24) / (60 * 60) )
      var m = Math.floor(diff % (60 * 60 * 24) % (60 * 60) / (60))
      var s = Math.floor(diff % 60)
    }

    setInterval(function () {
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

      $('#'+theID).find("#countdown-note").html(note)
      $('#'+theID).find("#countdown").find("#days").html(d)
      $('#'+theID).find("#countdown").find("#hours").html(h < 10 ? '0' + h : h)
      $('#'+theID).find("#countdown").find("#minutes").html(m < 10 ? '0' + m : m)
      $('#'+theID).find("#countdown").find("#seconds").html(s < 10 ? '0' + s : s)
    }, 1000)
  }
}