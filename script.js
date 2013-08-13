
$(document).ready(function() {
    $('.autofocus').focus().select();
    setUpVoteLinks();
    setUpDeclarationForm();
    setUpExpansions();
});

function setUpVoteLinks() {
    // make sure that stuff gets bound only once, even if run multiple times
    $('.vote').not('.bound').addClass('bound').click(function(e) {
        e.preventDefault();
        $('.vote').css('visibility', 'hidden');
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {uri: $(this).parents('.expansion').children('.uri').text(), vote: ($(this).is('.up') ? 'up' : 'down')},
            complete: function(xhr) {
                $('.note').text(xhr.responseText);
            }
        });
        registerTrackerAction('mappings', 'vote-' + $(this).is('.up') ? 'up' : 'down', $(this).attr('href'));
    });
}

function registerTrackerAction(category, action, label) {
    if (!pageTracker) return;
    pageTracker._trackEvent(category, action, label);
}

function setUpDeclarationForm() {
    $('#declaration-form').hide();
    $('#button-cancel, #button-show-form').click(function(e) {
        $('#button-show-form, #declaration-form, h2.notfound').toggle();
        $('#declaration-form:visible input.uri').focus().select();
        e.preventDefault();
    });
    $('#button-declare').click(function(e) {
        $('#declaration-form input').attr('disabled', 'disabled');
        $.ajax({
            url: $('base').attr('href') + $('h1').text(),
            type: 'POST',
            data: {create: $('input.uri').val()},
            success: function(html) {
                $('.note').text('Thanks for your contribution. You can add more namespaces tomorrow.');
                $('#declaration-form, .footer .message').hide();
                $('h2.notfound').remove();
                $('.footer .links').removeClass('hidden');
                $('.expansions').append(html);
                setUpVoteLinks();
                setUpExpansions();
                registerTrackerAction('mappings', 'declare-success', $('h1').text());
            },
            error: function(xhr) {
                $('.note').text(xhr.responseText).wrapInner('<strong></strong>');
                $('#declaration-form input').removeAttr('disabled');
                $('input.uri').focus();
                registerTrackerAction('mappings', 'declare-fail', $('h1').text());
            }
        });
    });
}

function setUpExpansions() {
    $('h2:gt(0)').addClass('alternate');
    // make sure that stuff gets bound only once, even if run multiple times
    $('.namespace-link img').not('.bound').addClass('bound').hover(
        function() { $(this).attr('src', 'images/link-hover.png'); },
        function() { $(this).attr('src', 'images/link.png'); }
    );
    $('.vote.up img').not('.bound').addClass('bound').hover(
        function() { $(this).attr('src', 'images/vote-up-hover.png'); },
        function() { $(this).attr('src', 'images/vote-up.png'); }
    );
    $('.vote.down img').not('.bound').addClass('bound').hover(
        function() { $(this).attr('src', 'images/vote-down-hover.png'); },
        function() { $(this).attr('src', 'images/vote-down.png'); }
    );
    setUpCopyButtons();
}

function setUpCopyButtons() {
  if (ZeroClipboard.detectFlashSupport()) {
    ZeroClipboard.setDefaults({ moviePath: "/zeroclipboard.swf", forceHandCursor: true, hoverClass: 'hover' });
    $('.uri.copy').each(function () {
      var $uri = $(this);
      $uri.before(createCopyButton($uri.text()), ' ');
    }).removeClass('copy');
    $('pre.source.copy').each(function () {
      $('.footer').prepend($('<p>').append(createCopyButton($(this).text().trim(),
                                                            ' Copy this snippet to the clipboard')));
    }).removeClass('copy');
  }
}

function createCopyButton(copyText, caption) {
  var over = function () { $('.hover img').attr('src', 'images/clipboard-hover.png'); },
      out =  function () { $('.clipboard img').attr('src', 'images/clipboard.png'); },
      $copy = $('<a>').attr({ 'class': 'clipboard' + (caption ? '' : ' no-caption'),
                              'title': 'Copy to the clipboard',
                              'data-clipboard-text': copyText, 'href': 'javascript:;' })
                      .append($('<img>').attr('src', 'images/clipboard.png'), caption)
                      .hover(function () { $(this).addClass('hover');   over(); },
                             function () { $(this).removeClass('hover'); out(); }),
      clip = new ZeroClipboard($copy);
  clip.on('mouseover', over);
  clip.on('mouseout', out);
  return $copy;
}
