 
  $(function () {
  
    var ul = $('ul').hide(),
      contact = $('#contact').hide(),
      quote = $('#quote').hide(),
      h1 = $('h1').hide(),
      container = $('#container').show();

    contact.html('<a href="#" id="email" title="Send me a message">' + contact.html() + '</a>');

    $('#email').click(function (e) {

      var email_box, form_html;

      e.preventDefault();

      if ($('#email_box').length > 0) {
        return;
      }

      form_html = '<div>Feel free to send me a message</div>';

      form_html += '<div><textarea></textarea></div>';

      form_html += '<div><label for="from">From</label><input id="from"></div>';

      form_html += '<div class="footer"><input type="submit" value="Send"> <a href="#">Close</a></div>';

      email_box = $('<form>').

        attr('id', 'email_box').

        hide().

        html(form_html).

        submit(function (e) {

          var message = email_box.find('textarea').val(),
            from = email_box.find('#from').val();

          e.preventDefault();

          $.ajax({

            type: 'POST',

            url: '/',

            dataType: 'text',

            data: {message: message, from: from},

            success: function (data) {
              var notify, notify_ok, message = 'Not really sure what happened... Maybe you should try again.';

              if (data === 'limit') {
                message = 'Sorry but you\'ve sent too many messages. Either try again tomorrow, or send me a direct email.';
              } else if (data === 'success') {
                message = 'Thanks for the message! If you supplied a reply-to address, I\'ll get back to you as soon as possible.';
              } else if (data === 'fail') {
                message = 'Sorry but the message failed to send. You should probably try again.';
              } else if (data === 'invalid') {
                message = 'You didn\'t supply a message to send.';
              }

              notify = $('<div>').attr('id', 'notify').text(message).hide();

              notify_ok = $('<div>').append($('<a>').attr('href', '#').text('OK'));

              notify_ok.click(function (e) {

                e.preventDefault();

                notify.fadeOut(500, function () {

                  $(this).remove();

                });

              });

              container.append(notify.append(notify_ok));

              notify.show(500);

            }

          });

          email_box.find('a').click();

        });

      email_box.find('a').click(function (e) {

        e.preventDefault();

        email_box.hide(700, function () {

          $(this).remove();

        });

      });

      container.append(email_box);

      email_box.show(700, function () {

        email_box.find('textarea').focus();

      });

    });

    $(document).keydown(function (e) {

      // Escape key closes the email and notify boxes
      if (e.which === 27) {
        $('#email_box a').click();
        $('#notify a').click();

      // Enter key closes the notify box
      } else if (e.which === 13) {
        $('#notify a').click();
      }

    });
  
    $('li p').hide();
  
    $('li').mouseenter(function () {
  
      $(this).find('p').stop(true, true).slideDown(300, 'swing');
  
    }).mouseleave(function () {
  
      $(this).find('p').stop(true, true).slideUp(300, 'swing');
  
    }).click(function () {

      document.location.href = $(this).find('h2 a').attr('href');

    });
  
    h1.fadeIn(1000, function () {

      quote.fadeIn(1000);
  
      contact.fadeIn(1000, function () {
  
        ul.slideDown(1000, 'swing');

      });
  
    });
  
  });

