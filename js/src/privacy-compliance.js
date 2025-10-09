jQuery(document).ready(($) => {
  $('#privacy-compliance-container').removeClass('privacy-compliance-hidden');
  $('#accept-privacy-button').on('click', (e) => {
    e.preventDefault();
    $.ajax({
      url: ajax_object.ajaxurl,
      method: 'GET',
      data: { action: 'CRIARCOOKIE' },
      success(response) {
        const dados = response.data;
        if (response.success) {
          $('#privacy-compliance-container').addClass('privacy-compliance-hidden');
        } else {
          console.error(dados.message);
        }
      },
      error() {
        console.error( arguments );
        console.error('Erro inesperado ao acessar o servidor!');
      },
    });
  });
});
