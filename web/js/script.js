$('#dialog').ready(function(){
  $(this).find('.msg_history').scrollTop(9999999);
});
$('#dialog').on('beforeSubmit', function(event){
  $.post('/dialog/newmessage', $(this).serialize());
  $('#dialog textarea').redactor('code.set', '');
  $(this).find('.msg_history').scrollTop(9999999);
  return false;
});
$('.inbox_msg .load-more a').on('click', function(event){
  event.preventDefault();
  if( $(this).prop('disabled') == true )
    return;

  let firstID = 0;
  let id_dialog = $('input[name="id_dialog"]').val();
  if( $('.ms-block').length )
    firstID = $('.ms-block').eq(0).attr('data-id');

  $('.inbox_msg .load-more a').prop('disabled', true);
  $.post('/dialog/loadmessage', {
    firstID: firstID,
    id_dialog: id_dialog
  }, function( response ){
    $('.inbox_msg .load-more a').prop('disabled', false);
    if( response == 'err' )
      return;
    if( response.length > 0 )
      $('.inbox_msg .load-more').after(response);
    else
      $('.inbox_msg .load-more').remove();
  });
});
$(function(){
  checkViewMessage();
  checkUnreadMessage();
  updateMessage();

  function checkViewMessage() {
    if( $('.dialog-box').length ) {
      let msIds = [];
      $('.ms-block').each(function(){
        msIds.push($(this).attr('data-id'));
      });
      $.post('/dialog/viewmessage', {
        msIds: JSON.stringify(msIds)
      }, function( response ){
        if( response == 'err' )
          return;
        setTimeout(function(){
          checkViewMessage();
        }, 2500);
        // if( response == 0 )
        //   $('#app > div > div.left-menu > div > a.btn.btn-default.message > div > span:nth-child(2)').html('');
        // else
        //   $('#app > div > div.left-menu > div > a.btn.btn-default.message > div > span:nth-child(2)').html('+'+response);
      });
    }
  }
  function checkUnreadMessage() {
    $.post('/dialog/unreadmessage', function( response ){
      if( response == 'err' )
        return;
      setTimeout(function(){
        checkUnreadMessage();
      }, 2500);
      if( response == 0 )
        $('#app > div > div.left-menu > div > a.btn.btn-default.message > div > span:nth-child(2)').html('');
      else
        $('#app > div > div.left-menu > div > a.btn.btn-default.message > div > span:nth-child(2)').html('+'+response);
    });
  }
  function updateMessage() {
    if( $('.dialog-box').length ) {
      let lastID = 0;
      let id_dialog = $('input[name="id_dialog"]').val();
      if( $('.ms-block').length )
        lastID = $('.ms-block').eq(-1).attr('data-id');

      $.post('/dialog/updatemessage', {
        lastID: lastID,
        id_dialog: id_dialog
      }, function( response ){
        if( response == 'err' )
          return;
        setTimeout(function(){
          updateMessage();
        }, 500);
        if( response.length > 0 ) {
          if( $('.ms-block').length == 0 )
            $('.msg_history').prepend(response);
          else
            $('.ms-block').eq(-1).after(response);
          $('.dialog-box').find('.msg_history').scrollTop(9999999);
        }
      });
    }
  }
});
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});
$(function() {
  $("img.lazy").each(function(){
    $(this).attr('src', $(this).attr('data-original'));
  });
});
