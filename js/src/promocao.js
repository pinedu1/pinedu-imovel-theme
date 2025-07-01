jQuery(document).ready(($) => {

  $('section.content.promocao').on('click', 'nav.paginacao a.page-numbers', function(e) {
    e.preventDefault();
    const $promocaoContainer = $(this).closest('.card-container'); // Ou use um seletor mais específico se houver vários
    const contrato = $promocaoContainer.data('contrato'); // Pega o valor do atributo data-contrato
    let paged = 1;
    const link = $(e.currentTarget).attr('href');
    const matchPageSlash = link.match(/\/page\/(\d+)\/?/);
    const matchPagedParam = link.match(/\?paged=(\d+)/);
    if (matchPageSlash && matchPageSlash[1]) {
      paged = matchPageSlash[1];
    } else if (matchPagedParam && matchPagedParam[1]) {
      paged = matchPagedParam[1];
    }

    $.ajax({
      url: ajax_object.ajaxurl,
      type: 'POST',
      data: {
        action: 'PAGINARPROMOCAO',
        paged: paged,
        contrato: contrato // Envia o valor do contrato para o PHP
      },
      beforeSend: function() {
        $promocaoContainer.addClass('is-loading');
      },
      success: function(response) {
        $('html, body').animate({ scrollTop: $promocaoContainer.offset().top }, 300);
        $promocaoContainer.fadeOut('slow', function() {
          $(this).replaceWith(response);
          $('article.promocao').fadeIn('slow').removeClass('is-loading');
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('Erro na requisição AJAX: ', textStatus, errorThrown);
        $promocaoContainer.removeClass('is-loading').fadeIn('slow');
      }
    });
  });
});
