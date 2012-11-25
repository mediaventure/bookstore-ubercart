(function($) {
$(document).ready(function(){

  // SHOW/HIDE FOOTER ACTIONS
  $('#showHide').click(function(){
    if ($("#footerActions").is(":hidden")) {
      $(this).css('background-position','0 0');
      $("#footerActions").slideDown("slow");

    } else {
      $(this).css('background-position','0 -16px')
      $("#footerActions").hide();
      $("#footerActions").slideUp("slow");
    }
    return false;
  });

  // TOP SEARCH
  $('#topSearch input:text').autofill({
    value: "type your search..."
  });

  $('#topSearch input:text').focus(function() {
    $(this).animate({
      width: "215"
    }, 300 );
    $(this).val('')
  });

  $('#topSearch input:text').blur(function() {
    $(this).animate({
      width: "100"
    }, 300 );
  });

  // QUICK CONTACT

  $('#quickName').val('your name');
  $('#quickEmail').val('your email');
  $('#quickComment').val('your message');

  $('#quickName').focus(function() {
    $(this).val('');
  });

  $('#quickEmail').focus(function() {
    $(this).val('');
  });

  $('#quickComment').focus(function() {
    $(this).val('');
  });

  //SHARE LINKS
  $('#shareLinks a.share').click(function() {
    if ($("#shareLinks #icons").is(":hidden")) {
      $('#shareLinks').animate({
        width: "625"
      }, 500 );
      $('#shareLinks #icons').fadeIn();
      $(this).text('[-] Share & Bookmark');
      return false;
    }else {
      $('#shareLinks').animate({
        width: "130"
      }, 500 );
      $('#shareLinks #icons').fadeOut();
      $(this).text('[+] Share & Bookmark');
      return false;
    }
  });

});
<!-- end document ready -->
})(jQuery);