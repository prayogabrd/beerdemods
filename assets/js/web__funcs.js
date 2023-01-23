let _lang = atob($('[__data_lang]').attr('__data_lang')),
    _change_lang_url = atob($('[__data_change_lang_id]').attr('__data_change_lang_id'));
function changeLang(lang) {
  fetch(_change_lang_url, {
    method: 'POST',
    body: JSON.stringify({
      'language': lang
    })
  }).then(result => result.json()).then(data => {
    if (data.success) {
     document.location = '';
    }
  });
}