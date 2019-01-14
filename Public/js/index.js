$(function() {
  $('.i-tab-tittle').find('li').click(function() {
    $('.i-tab-tittle').find('li').removeClass('active');
    $(this).addClass('active');
    $('.i-txt').removeClass('i-cur');
    $('.i-txt').eq($(this).index()).addClass('i-cur');
  })
})
