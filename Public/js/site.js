$(function() {
  $('.s-tap-tittle').find('li').click(function() {
    $('.s-tap-tittle').find('li').removeClass('active')
    $(this).addClass('active')
    $('.s-cont-tab').css('display','none')
    $('.s-cont-tab').eq($(this).index()).css('display','block')
    $('.s-con-img').css('display','none')
    $('.s-con-img').eq($(this).index()).css('display','block')
  })
})
