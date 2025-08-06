/**
 * Atualiza um elemento select com novos options
 * @param {string} selectName - Nome do select (atributo 'name')
 * @param {Array} items - Lista de objetos no formato [{id: 'valor', nome: 'texto'}, ...]
 * @param {boolean} addEmptyFirst - Adiciona uma opção vazia no início (padrão: false)
 * @param {string} emptyText - Texto da opção vazia (padrão: 'Selecione...')
 */
document.telaInstalada = false;
jQuery(document).ready(($) => {
  /* funcoes */
  function reloadSelectOptions(selectName, items, addEmptyFirst = false, emptyText = 'Selecione...', callBackOpt = null) {
    const select = $(selectName);
    select.empty();
    if (addEmptyFirst) {
      const opt = $('<option>', {
        value: '',
        text: emptyText,
      });
      if (typeof callBackOpt === 'function') {
        callBackOpt(opt, {valorInicial:0, valorFinal:9999999999});
      }
      select.append(opt);
    }
    $.each(items, (index, item) => {
      const opt = $('<option>', {
        value: item.id,
        text: item.nome,
      });
      if (item.selected === true) {
        opt.prop('selected', true);
      }
      if (typeof callBackOpt === 'function') {
        callBackOpt(opt, item);
      }
      select.append(opt);
    });
  }

  /* Select Pesquisa
  *  Contrato
  * */
  const selectCtr = $('#form-pesquisa select[name=contrato]');
  selectCtr.on('change', (event) => {
    const ctr = selectCtr.val();
    const selectChildTipo = '#form-pesquisa select[name=tipo-imovel]';
    const selectChildFx = '#form-pesquisa select[name=faixa-valor]';
    $(selectChildTipo).parent().addClass('loading');
    $(selectChildFx).parent().addClass('loading');
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
    if ( tipoFormulario == 'barra' ) {
      $('.cortina-aguarde').removeClass('inactive').addClass('is-active');
    }
    $.ajax({
      url: ajax_object.ajaxurl,
      method: 'GET',
      data: { action: 'CONTRATOCHANGE', contrato: ctr },
      success(response) {
        const dados = response.data;
        if (response.success) {
          const tipos = dados.data['tipo-imoveis'];
          reloadSelectOptions(selectChildTipo, tipos, true, 'Selecione ...');
          const fx = dados.data['faixa-valores'];
          reloadSelectOptions(selectChildFx, fx, true, 'Selecione ...');
        } else {
          console.error(dados.message);
        }
        $(selectChildTipo).parent().removeClass('loading');
        $(selectChildFx).parent().removeClass('loading');
        if ( tipoFormulario == 'barra' && document.telaInstalada === true ) {
          frm.submit();
        }
      },
      error() {
        console.error('Erro inesperado ao acessar o servidor!');
      },
    });
  });
  /* Select Pesquisa
  *  Tipo de Imovel
  * */
  const selectTipo = $('#form-pesquisa select[name=tipo-imovel]');
  selectTipo.on('change', (event) => {
    const tipo = selectTipo.val();
    const selectChild = '#form-pesquisa select[name=cidade]';
    $(selectChild).parent().addClass('loading');
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
    if ( tipoFormulario == 'barra' ) {
      $('.cortina-aguarde').removeClass('inactive').addClass('is-active');
    }

    $.ajax({
      url: ajax_object.ajaxurl,
      method: 'GET',
      data: { action: 'TIPOIMOVELCHANGE', tipo: tipo },
      success(response) {
        const dados = response.data;
        if (response.success) {
          // console.log(dados);
          reloadSelectOptions(selectChild, dados.data, true, 'Selecione ...');
        } else {
          console.error(dados.message);
        }
        $(selectChild).parent().removeClass('loading');
        if ( tipoFormulario == 'barra' && document.telaInstalada === true ) {
          frm.submit();
        }
      },
      error() {
        console.error('Erro inesperado ao acessar o servidor!');
      },
    });
  });
  /* Select Pesquisa
  *  Cidade
  * */
  const selectCid = $('#form-pesquisa select[name=cidade]');
  selectCid.on('change', (event) => {
    const cid = selectCid.val();
    const selectChild = '#form-pesquisa select[name=regiao]';
    $(selectChild).parent().addClass('loading');
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
    if ( tipoFormulario == 'barra' ) {
      $('.cortina-aguarde').removeClass('inactive').addClass('is-active');
    }
    $.ajax({
      url: ajax_object.ajaxurl,
      method: 'GET',
      data: { action: 'CIDADECHANGE', cidade: cid },
      success(response) {
        const dados = response.data;
        if (response.success) {
          // console.log(dados);
          reloadSelectOptions(selectChild, dados.data, true, 'Selecione ...');
        } else {
          console.error(dados.message);
        }
        $(selectChild).parent().removeClass('loading');
        if ( tipoFormulario == 'barra' && document.telaInstalada === true ) {
          frm.submit();
        }
      },
      error() {
        console.error('Erro inesperado ao acessar o servidor!');
      },
    });
  });
  /* Select Pesquisa
  *  Cidade
  * */
  const selectReg = $('#form-pesquisa select[name=regiao]');
  selectReg.on('change', (event) => {
    const cid = selectReg.val();
    const selectChild = '#form-pesquisa select[name=regiao]';
    $(selectChild).parent().addClass('loading');
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
    if ( tipoFormulario == 'barra' ) {
      $('.cortina-aguarde').removeClass('inactive').addClass('is-active');
    }
    $.ajax({
      url: ajax_object.ajaxurl,
      method: 'GET',
      data: { action: 'REGIAOCHANGE', cidade: cid },
      success(response) {
        const dados = response.data;
        if (response.success) {
          // console.log(dados);
          // console.log(dados.message);
        } else {
          console.error(dados.message);
        }
        $(selectChild).parent().removeClass('loading');
        if ( tipoFormulario == 'barra' && document.telaInstalada === true ) {
          frm.submit();
        }
      },
      error() {
        console.error('Erro inesperado ao acessar o servidor!');
      },
    });
  });
  const selectFx = $('#form-pesquisa select[name=faixa-valor]');
  selectFx.on('change', (event) => {
    const selectedOption = selectFx.find('option:selected');
    const valorInicial = selectedOption.attr('valor-inicial');
    const valorFinal = selectedOption.attr('valor-final');
    $('#form-pesquisa input[name="valor-inicial"]').val(valorInicial);
    $('#form-pesquisa input[name="valor-final"]').val(valorFinal);
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
    if ( tipoFormulario == 'barra' && document.telaInstalada === true ) {
        frm.submit();
    }
  });
  document.telaInstalada = true;
});
