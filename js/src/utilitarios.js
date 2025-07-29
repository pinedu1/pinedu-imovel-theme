$=jQuery;
function validarFormulario(form) {
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
      }, 5000);
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
      }, 5000);
    }
  });
  return valido;
}
window.validarFormulario = validarFormulario;
