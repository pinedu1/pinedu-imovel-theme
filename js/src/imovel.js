import Glide from '@glidejs/glide';
window.Glide = Glide;

$=jQuery;
function validarFormularioContato() {
  const form = $('#contato-form');
  const inputs = form.find('input[required]');
  const selects = form.find('select[required]');
  let valido = true;
  // Limpar mensagens de erro anteriores
  $('.error-message').remove();
  // Validar cada campo requerido
  inputs.each(function () {
    const input = $(this);
    if (!input.val() && !input.prop('disabled')) {
      valido = false;
      // Adicionar uma mensagem de erro se o campo não estiver preenchido
      const errorMessage = $('<div class="error-message" style="color: red;">');
      errorMessage.text(`O campo ${input.attr('placeholder')} é obrigatório.`);
      input.parent().append(errorMessage);
      setTimeout(() => {
        errorMessage.remove();
      }, 10000);
    }
  });
  // Validar cada campo requerido
  selects.each(() => {
    const select = $(this);
    if (!select.val()) {
      valido = false;
      // Adicionar uma mensagem de erro se o campo não estiver preenchido
      const errorMessage = $('<div class="error-message" style="color: red;">');
      errorMessage.text(`O campo ${select.attr('placeholder')} é obrigatório.`);
      select.parent().append(errorMessage);
      setTimeout(() => {
        errorMessage.remove();
      }, 10000);
    }
  });
  return valido;
}
function exibeFormContato(button) {
  $('#botoes-contato').hide('slow');
  $('#div-contato').show('slow');
}
function ajaxContato() {
  const form = $('#contato-form');
  const dadosFormulario = form.serialize();
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CONTATOIMOVEL', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      $('#loading-curtain').fadeIn();
    },
    success(response) {
      if (response.success) {
        $('section.solicita-visita').replaceWith(response.data);
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
    complete( jqXHR, textStatus ) {
      $('#loading-curtain').fadeOut();
/*
      $('html, body').animate({
        scrollTop: $("#btnProximo").offset().top
      }, 800);
*/
    },
  });
}
function enviarContato(button) {
  const ok = validarFormularioContato();
  if (ok === true) {
    ajaxContato();
  }
  return false;
}
window.exibeFormContato = exibeFormContato;
window.enviarContato = enviarContato;
