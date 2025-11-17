/**
 * Atualiza um elemento select com novos options
 * @param {string} selectName - Nome do select (atributo 'name')
 * @param {Array} items - Lista de objetos no formato [{id: 'valor', nome: 'texto'}, ...]
 * @param {boolean} addEmptyFirst - Adiciona uma opção vazia no início (padrão: false)
 * @param {string} emptyText - Texto da opção vazia (padrão: 'Selecione...')
 */
document.telaInstalada = false;
function instalaSlider( valorMinimo, valorMaximo, passoValor, defaultIni, defaultFim, callbackPost ) {
  const slider = document.getElementById('price-slider');
  if (slider.noUiSlider) {
    slider.noUiSlider.destroy();
  }
  noUiSlider.create(slider, {
    start: [defaultIni, defaultFim], // valores iniciais (dois handles)
    connect: true,
    range: { min: valorMinimo, max: valorMaximo },
    step: passoValor,
    tooltips: [false, false], // mostra tooltip nos handles
    format: {
      to: value => Number(value).toLocaleString('pt-BR', {style:'currency', currency:'BRL'}),
      from: value => Number(value.replace(/[^0-9.-]+/g, ""))
    }
  });
  slider.noUiSlider.on('update', (values) => {
    const inputInicial = document.querySelector('[name="valor-inicial"]');
    const inputFinal = document.querySelector('[name="valor-final"]');
    const minLabel = document.getElementById('min-val');
    const maxLabel = document.getElementById('max-val');
    const toNumber = v => {
      const s = String(v)
        .replace(/[^\d,.-]/g, '')
        .replace(/\./g, '')
        .replace(',', '.');
      return parseFloat(s) || 0;
    };

    const valMin = parseFloat( toNumber( values[0] ) );
    const valMax = parseFloat( toNumber( values[1] ) );
    inputInicial.value = valMin;
    inputFinal.value = valMax;
    minLabel.textContent = Number( valMin ).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    maxLabel.textContent = Number( valMax ).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
  });
  $( 'li.faixa-valor-slider' ).css( 'display', 'list-item' );
  if ( typeof callbackPost === 'function' ) {
    callbackPost();
  }
}

jQuery(document).ready(($) => {
  /* funcoes */
  function reloadSelectOptions(selectName, items, addEmptyFirst = false, emptyText = 'Selecione...', callBackOpt = null) {
    const select = $(selectName);
    if (select.attr('data-carregando') === '1') {
      return;
    }
    select.attr('data-carregando', '1');
    select.empty();
    const itemSelecionado = items.find(item => item.selected === true);
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
      if (typeof callBackOpt === 'function') {
        callBackOpt(opt, item);
      }
      select.append(opt);
    });
    if (itemSelecionado) {
      select.val(itemSelecionado.id);
    }
    select.attr('data-carregando', '0');
  }

  /* Select Pesquisa
  *  Contrato
  * */
  const selectCtr = $('#form-pesquisa select[name=contrato]');
  selectCtr.on('change', (event) => {
    $('#loading-overlay').fadeIn(300);
    const ctr = selectCtr.val();
    const selectChildTipo = '#form-pesquisa select[name=tipo-imovel]';
    $( 'li.faixa-valor-slider' ).css( 'display', 'none' );
    $(selectChildTipo).parent().addClass('loading');
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
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
          if ( fx.length > 0 ) {
            const range = (fx[fx.length - 1] - fx[0]) / fx.length;
            const mid = parseInt( fx.length / 2 );
            const medianLow = fx[mid - 1];
            const medianHigh = fx[mid];
            const minimo = fx[0];
            const maximo = fx[fx.length - 1];
            instalaSlider( minimo, maximo, range, medianLow, medianHigh );
            $('#loading-overlay').fadeOut(300);
          }
        } else {
          console.error(dados.message);
        }
        $(selectChildTipo).parent().removeClass('loading');
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
    $('#loading-overlay').fadeIn(300);
    const tipo = selectTipo.val();
    const selectChild = '#form-pesquisa select[name=cidade]';
    $(selectChild).parent().addClass('loading');
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
    $.ajax({
      url: ajax_object.ajaxurl,
      method: 'GET',
      data: { action: 'TIPOIMOVELCHANGE', tipo: tipo },
      success(response) {
        const dados = response.data;
        if (response.success) {
          reloadSelectOptions(selectChild, dados.data, true, 'Selecione ...');
        } else {
          console.error(dados.message);
        }
        $(selectChild).parent().removeClass('loading');
        $('#loading-overlay').fadeOut(300);
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
    $('#loading-overlay').fadeIn(300);
    const cid = selectCid.val();
    const selectChild = '#form-pesquisa select[name=regiao]';
    $(selectChild).parent().addClass('loading');
    const frm = $('#form-pesquisa');
    const tipoFormulario = frm.data('tipo');
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
        $('#loading-overlay').fadeOut(300);
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
      },
      error() {
        console.error('Erro inesperado ao acessar o servidor!');
      },
    });
  });
  $('#referencia').on('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      $('#loading-overlay').fadeIn(300);
      buscaReferencia( e );
      $('#loading-overlay').fadeOut(300);
    }
  });
  $('form[name="consultaReferencia"]').on('submit', function(e) {
    e.preventDefault();
    if(validarFormularioConsulta()) {
      $(this).unbind('submit').submit();
    }
  });
  document.telaInstalada = true;
});
