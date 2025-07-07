const $ = jQuery;
function validarFormularioCadastre() {
  const form = $('#cadastro-form');
  return validarFormulario(form);
}
function ajaxTipoImovel() {
  const dadosFormulario = $('#cadastro-form').serialize();
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CADASTREIDENTIFICACAO', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      $('#loading-curtain').fadeIn();
    },
    success(response) {
      if (response.success) {
        $('#passoPosIdentificacao').replaceWith(response.data);
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
    complete( jqXHR, textStatus ) {
      $('#loading-curtain').fadeOut();
      $('html, body').animate({
        scrollTop: $("#btnProximo").offset().top
      }, 800);
    }
  });
}
function ajaxLocalizacao() {
  const dadosFormulario = $('#cadastro-form').serialize();
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CADASTRELOCALIZACAO', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      $('#loading-curtain').fadeIn();
    },
    success(response) {
      if (response.success) {
        $('#passoPosTipoImovel').replaceWith(response.data);
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
    complete( jqXHR, textStatus ) {
      $('#loading-curtain').fadeOut();
      $('html, body').animate({
        scrollTop: $("#btnProximo").offset().top
      }, 800);
    }
  });
}
function ajaxNegociacao() {
  const dadosFormulario = $('#cadastro-form').serialize();
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CADASTRENEGOCIACAO', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      $('#loading-curtain').fadeIn();
    },
    success(response) {
      if (response.success) {
        $('#passoPosLocalizacao').replaceWith(response.data);
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
    complete( jqXHR, textStatus ) {
      $('#loading-curtain').fadeOut();
      $('html, body').animate({
        scrollTop: $("#btnProximo").offset().top
      }, 800);
    }
  });
}
function ajaxCaracteristicas() {
  const dadosFormulario = $('#cadastro-form').serialize();
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CADASTRECARACTERISTICAS', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      $('#loading-curtain').fadeIn();
    },
    success(response) {
      if (response.success) {
        $('#passoPosNegociacao').replaceWith(response.data);
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
    complete( jqXHR, textStatus ) {
      $('#loading-curtain').fadeOut();
      $('html, body').animate({
        scrollTop: $("#btnProximo").offset().top
      }, 800);
    }
  });
}
function ajaxFotos() {
  const dadosFormulario = $('#cadastro-form').serialize();
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CADASTREFOTOS', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      $('#loading-curtain').fadeIn();
    },
    success(response) {
      if (response.success) {
        $('#passoPosCaracteristicas').replaceWith(response.data);
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
    complete( jqXHR, textStatus ) {
      $('#loading-curtain').fadeOut();
      $('html, body').animate({
        scrollTop: $("#btnProximo").offset().top
      }, 800);
    }
  });
}
// eslint-disable-next-line no-unused-vars
function passoPosIdentificacao(e) {
  e.preventDefault();
  const ok = validarFormularioCadastre();
  if (ok === true) {
    $('input[name="identificacao"]').val(1);
    ajaxTipoImovel();
  }
}
// eslint-disable-next-line no-unused-vars
function passoPosTipoImovel(e) {
  e.preventDefault();
  const ok = validarFormularioCadastre();
  if (ok === true) {
    ajaxLocalizacao();
  }
}
function passoPosLocalizacao(e) {
  e.preventDefault();
  const ok = validarFormularioCadastre();
  if (ok === true) {
    $('input[name="identificacao"]').val(1);
    ajaxNegociacao();
  }
}
function passoPosNegociacao(e) {
  e.preventDefault();
  const ok = validarFormularioCadastre();
  if (ok === true) {
    $('input[name="identificacao"]').val(1);
    ajaxCaracteristicas();
  }
}
function passoPosCaracteristicas(e) {
  e.preventDefault();
  const ok = validarFormularioCadastre();
  if (ok === true) {
    $('input[name="identificacao"]').val(1);
    ajaxFotos();
  }
}
function passoPosFotos(e) {
  e.preventDefault();
  const form = $('#cadastro-form')[0];
  if (!form.checkValidity()) {
    form.reportValidity();
    return false;
  }
  const ok = validarFormularioCadastre();
  const formData = new FormData(form);
  window.myDropzone.getAcceptedFiles().forEach(function (file) {
    formData.append('fotos[]', file, file.name);
  });
  $.ajax({
    url: form.action,
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    timeout: 2 * 60 * 1000,
    beforeSend( jqXHR, settings ) {
      $('#loading-curtain').fadeIn();
    },
    success: function(response) {
      if (response.success) {
        $('section#cadastre').replaceWith(response.data);
      } else {
        console.error('Erro na requisição AJAX: ', response.data);
      }
    },
    error: function (xhr, status, error) {
      console.error(arguments);
      if (status === 'timeout') {
        console.error('A requisição expirou (timeout)');
      } else {
        console.error('Erro AJAX:', error);
      }
    },
    complete( jqXHR, textStatus ) {
      $('#loading-curtain').fadeOut();
      $('html, body').animate({
        scrollTop: $("#btnProximo").offset().top
      }, 800);
    }
  });
}
function carregaCombo( select, array ) {
  $(select).empty();
  $(select).append('<option value="">Selecione ...</option>');
  $.each(array, function(index, item) {
    const $option = $('<option></option>').val(item.id).text(item.nome);
    if (item.selected) {
      $option.prop('selected', true);
    }
    $(select).append($option);
  });
}
function estadoChange(obj) {
  const dadosFormulario = $('#cadastro-form').serialize();
  const selectCidade = $('select[name="cidade"]');
  const selectBairro = $('select[name="bairro"]');
  const overlayCidade = selectCidade.siblings('.loading-overlay');
  const overlayBairro = selectBairro.siblings('.loading-overlay');
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'AUTOCOMPLETEGPBLOGRADOURO', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      overlayCidade.css('display', 'flex');
      overlayBairro.css('display', 'flex');
    },
    complete( jqXHR, textStatus ) {
      overlayCidade.hide();
      overlayBairro.hide();
    },
    success(response) {
      if (response.success) {
        const data = response.data;
        carregaCombo( selectCidade[0], data.cidades );
        carregaCombo( selectBairro[0], data.bairros );
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
  });
}
function cidadeChange(obj) {
  const dadosFormulario = $('#cadastro-form').serialize();
  const selectBairro = $('select[name="bairro"]');
  const overlayBairro = selectBairro.siblings('.loading-overlay');
  $.ajax({
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CIDADEGPBCHANGE', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      overlayBairro.css('display', 'flex');
    },
    complete( jqXHR, textStatus ) {
      overlayBairro.hide();
    },
    success(response) {
      if (response.success) {
        const data = response.data;
        const selectBairro = $('select[name="bairro"]');
        carregaCombo( selectBairro[0], data.bairros );
      } else {
        console.error('Erro na requisição AJAX: ', response);
      }
    },
    error() {
      console.error('Erro na requisição desconhecido AJAX');
    },
  });
}
function initAutocompleteEndereco(obj) {
  $(obj).autocomplete({
    source: function(request, callback) {
      $.ajax({
        url: ajax_object.ajaxurl,
        method: 'POST',
        data: { action: 'AUTOCOMPLETEGPBLOGRADOURO', form_data: $('#cadastro-form').serialize() },
        success: function(response) {
          var data = [];
          if ( response.success == true ) {
            data = response.data;
            if ( data.total > 0 ) {
              const logradouros = data.logradouros.map(item => ({
                label: item.logradouro
                , value: item.id
                , cep: item.cepFormatado
                , bairro: item.bairroIniNome
                , bairroId: item.bairroIniId
              }));
              callback( logradouros );
            }
          }
        },
        error: function(xhr, status, error) {
          console.error('Erro na requisição:', error);
        }
      });
    },
    minLength: 3,
    delay: 300,
    select: function(event, ui) {
      const logradouroSelecionado = ui.item.label
        , idSelecionado = ui.item.value
        , cepSelecionado = ui.item.cep
        , bairroIdSelecionado = ui.item.bairroId;
      $('#logradouro').val(logradouroSelecionado);
      $('#logradouroId').val(idSelecionado);
      $('#cep').val(cepSelecionado);
      $('#bairro').val(bairroIdSelecionado);
      $('input[name="numero"]').focus();
      return false;
    },
    focus: function(event, ui) {
      return false;
    }
  }).autocomplete("instance")._renderItem = function(ul, item) {
    return $("<li>")
      .append(`
            <div class="autocomplete-item">
                <div class="main-text" data-id="${item.value}">${item.label}</div>
                <div class="secondary-text">
                    <span class="bairro" data-id="${item.bairroId}">${item.bairro}</span>
                    <span class="cep">${item.cep}</span>
                </div>
            </div>
        `)
      .appendTo(ul);
  };
}
function vendaOnChange(obj) {
  const isChecked = obj.checked;
  const valor = $('input[type=number][name=valorVenda]');
  valor.prop('disabled', !isChecked);
  if ( isChecked ) {
    valor.focus();
  }
}
function locacaoOnChange(obj) {
  const isChecked = obj.checked;
  const valor = $('input[type=number][name=valorLocacao]');
  valor.prop('disabled', !isChecked);
  if ( isChecked ) {
    valor.focus();
  }
}
function instalaCaracteristicaToogle($) {
  var $btnCaracteristicas = $('#btnCaracteristicas');
  var $collapseIcon = $('#collapseIcon');
  var $detalhesCaracteristicas = $('#detalhesCaracteristicas');

  /* Manipulador de clique para o botão de características */
  $btnCaracteristicas.on('click', function(e) {
    e.preventDefault(); /* Previne o comportamento padrão do botão/link */

    /* Alterna a visibilidade do conteúdo com um efeito deslizante */
    $detalhesCaracteristicas.slideToggle(400, function() {
      /* Callback após a animação de slideToggle() completar */

      /* Verifica se o conteúdo está visível ou oculto para atualizar o botão */
      if ($detalhesCaracteristicas.is(':visible')) {
        /* Conteúdo está visível: muda o texto e ícone para "Ocultar" e seta para cima */
        $btnCaracteristicas.html('<i class="fa fa-chevron-up" id="collapseIcon"></i>Ocultar Características');
        $btnCaracteristicas.attr('aria-expanded', 'true'); /* Atualiza atributo ARIA */
      } else {
        /* Conteúdo está oculto: muda o texto e ícone para "Mostrar" e seta para baixo */
        $btnCaracteristicas.html('<i class="fa fa-chevron-down" id="collapseIcon"></i>Mostrar Características');
        $btnCaracteristicas.attr('aria-expanded', 'false'); /* Atualiza atributo ARIA */
      }
      /* Re-seleciona o ícone dentro do botão, pois o HTML interno foi reescrito */
      $collapseIcon = $btnCaracteristicas.find('#collapseIcon');
    });
  });
}
function instalaDropZone() {
  Dropzone.autoDiscover = false;
  if ($("#property-dropzone").length) {
    window.myDropzone = new Dropzone("#property-dropzone", {
      url: "#", // Não usaremos o upload automático
      paramName: "property_files",
      maxFiles: 10,
      maxFilesize: 5,
      acceptedFiles: 'image/*',
      addRemoveLinks: false,
      autoProcessQueue: false, // IMPORTANTE: desativa o auto upload
      parallelUploads: 5,
      uploadMultiple: true,
      dictMaxFilesExceeded: "Limite de 10 fotos atingido",
      dictDefaultMessage: "Arraste arquivos aqui ou clique para selecionar",
      dictFallbackMessage: "Seu navegador não suporta upload de arquivos por arrastar e soltar",
      dictFallbackText: "Por favor, use o formulário abaixo para enviar seus arquivos como antigamente.",
      dictFileTooBig: "Arquivo muito grande ({{filesize}}MB). Tamanho máximo permitido: {{maxFilesize}}MB.",
      dictInvalidFileType: "Você não pode enviar arquivos deste tipo.",
      dictResponseError: "Servidor respondeu com código {{statusCode}}.",
      dictCancelUpload: "Cancelar upload",
      dictUploadCanceled: "Upload cancelado.",
      dictRemoveFile: "Remover arquivo",
      dictRemoveFileConfirmation: "Tem certeza que deseja remover este arquivo?",
      dictFileSizeUnits: { tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "bytes" },
      init: function() {
        var submitButton = document.querySelector("#btnSubmitForm");
        var myDropzone = this;
        $("#cadastro-form").on("submit", function(e) {
          e.preventDefault();
          e.stopPropagation();
          if (myDropzone.getQueuedFiles().length > 0) {
            myDropzone.processQueue();
          } else {
            submitFormWithData();
          }
        });
        this.on("queuecomplete", function() {
          submitFormWithData();
        });
        this.on("error", function(file, message) {
          alert("Erro no upload: " + message);
          this.removeFile(file);
        });
      }
    });
    function submitFormWithData() {
      var formData = new FormData($("#cadastro-form")[0]);
      var files = myDropzone.getAcceptedFiles();
      files.forEach(function(file) {
        formData.append("property_files[]", file);
      });
      $.ajax({
        url: $("#cadastro-form").attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend( jqXHR, settings ) {
          $('#loading-curtain').fadeIn();
        },
        success: function(response) {
          if(response.success) {
            alert("Cadastro realizado com sucesso!");
          } else {
            alert(response.message || "Erro ao processar cadastro");
          }
        },
        error: function(xhr, status, error) {
        },
        complete( jqXHR, textStatus ) {
          $('#loading-curtain').fadeOut();
          $('html, body').animate({
            scrollTop: $("#btnProximo").offset().top
          }, 800);
        }
      });
    }
  }
}
window.passoPosIdentificacao = passoPosIdentificacao;
window.passoPosTipoImovel = passoPosTipoImovel;
window.passoPosLocalizacao = passoPosLocalizacao;
window.passoPosNegociacao = passoPosNegociacao;
window.passoPosCaracteristicas = passoPosCaracteristicas;
window.passoPosFotos = passoPosFotos;
window.estadoChange = estadoChange;
window.cidadeChange = cidadeChange;
window.vendaOnChange = vendaOnChange;
window.locacaoOnChange = locacaoOnChange;
window.initAutocompleteEndereco = initAutocompleteEndereco;
window.instalaCaracteristicaToogle = instalaCaracteristicaToogle;
window.instalaDropZone = instalaDropZone;
