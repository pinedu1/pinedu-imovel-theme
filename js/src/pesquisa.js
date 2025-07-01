jQuery(document).ready(($) => {
  function reordenaPesquisa() {
    $('form#form-pesquisa input[name=max]').val($('form[name=show] select[name=max]').val());
    $('form#form-pesquisa input[name=sort]').val($('form[name=sort] select[name=sort]').val());
    $('form#form-pesquisa input[name=ordem]').val($('form[name=order] select[name=ordem]').val());
    $('form#form-pesquisa').submit();
  }
  $('form[name=sort] select[name=sort]').on('change', function () {
    reordenaPesquisa();
  });
  $('form[name=show] select[name=max]').on('change', function () {
    reordenaPesquisa();
  });
  $('form[name=order] select[name=ordem]').on('change', function () {
    reordenaPesquisa();
  });
});
