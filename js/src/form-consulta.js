function validarFormularioConsulta() {
  const form = $('form[name="consultaReferencia"]');
  return validarFormulario(form);
}

function buscaReferencia( e ) {
  if (!validarFormularioConsulta()) {
    return false;
  }
  if (e.which === 13) {
    realizarBusca();
  }
  return true;
}
function realizarBusca() {
  console.log('realizarBusca');
  const referencia = $('input[name="referencia"]').val();
  if (referencia.trim() !== '') {
    $('form[name="consultaReferencia"]').submit();
  }
}
window.buscaReferencia = buscaReferencia;
window.validarFormularioConsulta = validarFormularioConsulta;
