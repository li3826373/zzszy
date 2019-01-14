window.onload = function() {
  var sub = document.getElementById('but')
  var modal = document.getElementsByClassName('modal')[0]
  var num = 1
  sub.onclick = function() {
    if (num == 1) {
      num = 0
      modal.style.width = '320px'
    } else {
      num = 1
      modal.style.width = '0px'
    }
  }
}
