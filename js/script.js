function googleTranslateElementInit() {
  new google.translate.TranslateElement({ pageLanguage: ep_i10n.pageLanguage }, 'google_translate_element');
}

(function ($) {
  $('#content-tmce').trigger('click');
  $('#ed_toolbar').addClass('notranslate');
  $('#titlewrap input, #excerpt, #new-tag-post_tag, #tax-input-post_tag, .tagchecklist').addClass('translate');

  $(document).on('change', '.goog-te-combo', function () {
    $('#content-tmce').trigger('click');
    if ($('#content_ifr').length) {
      $('#content_ifr').addClass('translate');
    } else {
      $('#wp-content-editor-container').addClass('translate');
    }
  });
})(jQuery);

