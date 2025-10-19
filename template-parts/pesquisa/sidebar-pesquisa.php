<?php
  if ( isset( $_REQUEST[ 'tipo_pesquisa_submit' ] ) ) {
    $tipo_pesquisa_submit = $_REQUEST[ 'tipo_pesquisa_submit' ];
  }
  $contrato_padrao = get_query_var( 'contrato' );
  if ( empty( $contrato_padrao ) && isset( $_REQUEST[ 'contrato' ] ) ) {
    $contrato_padrao = $_REQUEST[ 'contrato' ];
  }
  $tipo_imovel_padrao = get_query_var( 'tipo-imovel' );
  if ( empty( $tipo_imovel_padrao ) && isset( $_REQUEST[ 'tipo-imovel' ] ) ) {
    $tipo_imovel_padrao = $_REQUEST[ 'tipo-imovel' ];
  }
  $cidade_padrao = get_query_var( 'cidade' );
  if ( empty( $cidade_padrao ) && isset( $_REQUEST[ 'cidade' ] ) ) {
    $cidade_padrao = $_REQUEST[ 'cidade' ];
  }
  $regiao_padrao = get_query_var( 'regiao' );
  if ( empty( $regiao_padrao ) && isset( $_REQUEST[ 'regiao' ] ) ) {
    $regiao_padrao = $_REQUEST[ 'regiao' ];
  }
  $faixa_padrao = get_query_var( 'faixa-valor' );
  if ( empty( $faixa_padrao ) && isset( $_REQUEST[ 'faixa-valor' ] ) ) {
    $faixa_padrao = $_REQUEST[ 'faixa-valor' ];
  }
  $valor_inicial_padrao = get_query_var( 'valor-inicial' );
  if ( empty( $valor_inicial_padrao ) && isset( $_REQUEST[ 'valor-inicial' ] ) ) {
    $valor_inicial_padrao = $_REQUEST[ 'valor-inicial' ];
  }
  $valor_final_padrao = get_query_var( 'valor-final' );
  if ( empty( $valor_final_padrao ) && isset( $_REQUEST[ 'valor-final' ] ) ) {
    $valor_final_padrao = $_REQUEST[ 'valor-final' ];
  }

  $terms_contrato = lista_contratos();
  $terms_tipo_imovel = lista_tipo_imovel();
  $terms_cidade = lista_cidade();
  $terms_regiao = [];
  if ( $cidade_padrao != '' ) {
    $terms_regiao = lista_regiao( $cidade_padrao );
  }
  $terms_faixa_valor = [];
  if ( $contrato_padrao ) {
    $terms_faixa_valor = lista_faixa_valor_valores( $contrato_padrao );
  }
?>
<aside class="sidebar-pesquisa">
  <?php if (is_singular('imovel')):
    get_template_part( 'template-parts/imovel/solicita-visita', 'imovel' );
  endif; ?>
  <section class="sidebar pesquisa">
    <main class="pesquisa-content sidebar">
      <div id="loading-overlay" class="overlay-form" style="display: none;">
        <div class="spinner-container">
          <div class="spinner"></div> <p>Carregando dados, aguarde!</p>
        </div>
      </div>
      <header class="pesquisa-header">
        <h4>Pesquisa</h4>
      </header>
      <form role="search" data-tipo="barra" method="get" id="form-pesquisa" class="barra pesquisa-form" action="<?php echo esc_url( home_url( '/pesquisa' ) ); ?>">
        <input type="hidden" name="tipo_pesquisa_submit" value="imovel">
        <input type="hidden" name="valor-inicial">
        <input type="hidden" name="valor-final">
        <input type="hidden" name="post_type" value="imovel" />
        <input type="hidden" name="max" value="12">
        <input type="hidden" name="sort" value="dataPreco">
        <input type="hidden" name="ordem" value="DESC">
        <ul>
          <li class="contrato">
            <div><label for="contrato">Tipo de Contrato</label></div>
            <div class="select-container">
              <select id="contrato" name="contrato">
                <option value="" <?php echo ('' == $contrato_padrao)? 'selected': '' ?>>Selecione...</option>
                <?php if ( isset($terms_contrato) && !empty( $terms_contrato ) ): ?>
                  <?php foreach ((array) $terms_contrato as $contrato): ?>
                    <option value="<?php echo esc_attr($contrato->slug); ?>" <?php echo ($contrato->slug == $contrato_padrao)? 'selected': '' ?>><?php echo esc_html($contrato->name); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </li>
          <li class="tipo-imovel">
            <div><label for="tipo-imovel">Tipo de Imóvel</label></div>
            <div class="select-container">
              <select id="tipo-imovel" name="tipo-imovel">
                <option value="" <?php echo ('' == $tipo_imovel_padrao)? 'selected': '' ?>>Selecione...</option>
                <?php if ( isset($terms_tipo_imovel) && !empty( $terms_tipo_imovel ) ): ?>
                  <?php foreach ((array) $terms_tipo_imovel as $tipo_imovel): ?>
                    <option value="<?php echo esc_attr($tipo_imovel->slug); ?>" <?php echo ($tipo_imovel->slug == $tipo_imovel_padrao)? 'selected': '' ?>><?php echo esc_html($tipo_imovel->name); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </li>
          <li class="cidade">
            <div><label for="cidade">Cidade</label></div>
            <div class="select-container">
              <select id="cidade" name="cidade">
                <option value="" <?php echo ('' == $cidade_padrao)? 'selected': '' ?>>Selecione...</option>
                <?php if ( isset($terms_cidade) && !empty( $terms_cidade ) ): ?>
                  <?php foreach ((array) $terms_cidade as $cidade): ?>
                    <option value="<?php echo esc_attr($cidade->slug); ?>" <?php echo ($cidade->slug == $cidade_padrao)? 'selected': '' ?>><?php echo esc_html($cidade->name); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </li>
          <li class="regiao">
            <div><label for="regiao">Região</label></div>
            <div class="select-container">
              <select id="regiao" name="regiao">
                <option value="" <?php echo ('' == $regiao_padrao)? 'selected': '' ?>>Selecione...</option>
                <?php if ( isset($terms_regiao ) && !empty( $terms_regiao ) ): ?>
                  <?php foreach ((array) $terms_regiao as $regiao): ?>
                    <option value="<?php echo esc_attr($regiao->slug); ?>" <?php echo ($regiao->slug == $regiao_padrao)? 'selected': '' ?>><?php echo esc_html($regiao->name); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </li>
          <li class="faixa-valor-slider sidebar">
            <div><label for="faixa-valor">Faixa de Valor</label></div>
            <div class="faixa-container">
              <div id="price-slider" class="price-slider"></div>
              <div class="slider-rotulo">De: <span id="min-val" class="min-val"></span> — Até: <span id="max-val" class="max-val"></span></div>
            </div>
          </li>
          <li class="submit pesquisa">
            <div class="submit-container">
              <button type="submit" class="button button-small">Pesquisar</button>
            </div>
          </li>
        </ul>
      </form>
      <header class="pesquisa-header">
        <h4>Consulta</h4>
      </header>
      <form name="consultaReferencia" role="consulta" method="get" class="consulta-form" action="<?php echo esc_url(home_url('/pesquisa')); ?>">
        <input type="hidden" name="tipo_pesquisa_submit" value="consulta">
        <ul>
          <li class="referencia sidebar">
            <div><label for="referencia">Referência</label></div>
            <div><input type="text" name="referencia" id="referencia" placeholder="Referência" required aria-required="true"></div>
          </li>
          <li class="submit consulta">
            <div class="submit-container">
              <button id="submit-consulta" type="submit" class="button button-small">Consultar</button>
            </div>
          </li>
        </ul>
      </form>
    </main>
    <?php if ( is_singular('imovel') ): ?>
      <section class="corretor card sidebar">
        <?php get_template_part( 'template-parts/corretor/cartao', 'imovel' ); ?>
      </section>
    <?php endif; ?>
  </section>
</aside>
<script>
jQuery(document).ready(function($) {
  function instalaSlider( valorMinimo, valorMaximo, passoValor, defaultIni, defaultFim ) {
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
  }

  <?php echo 'var rangeSlider = ' . json_encode($terms_faixa_valor) . ';' ?>
  <?php
  $vIni = 0;
  $vFim = 0;
  if (isset( $_REQUEST )) {
    if (isset($_REQUEST['valor-inicial'])) {
      $vIni = floatval($_REQUEST['valor-inicial']);
    }
    if (isset($_REQUEST['valor-final'])) {
      $vFim = floatval($_REQUEST['valor-final']);
    }
  }
  ?>
  var mid = 0, medianLow = <?php echo $vIni; ?>, medianHigh = <?php echo $vFim; ?>, minimo = 0, maximo = 0, range = 0;
  if ( rangeSlider.length > 0 ) {
    range = (rangeSlider[rangeSlider.length - 1] - rangeSlider[0]) / rangeSlider.length;
    mid = parseInt( rangeSlider.length / 2 );
    medianLow  = ( medianLow > 0 ) ? medianLow:  rangeSlider[mid - 1];
    medianHigh =  ( medianHigh > 0 ) ? medianHigh: rangeSlider[mid];
    console.log( medianLow, medianHigh );
    minimo = rangeSlider[0];
    maximo = rangeSlider[rangeSlider.length - 1];
    instalaSlider( minimo, maximo, range, medianLow, medianHigh );
  } else {
    $( 'li.faixa-valor-slider' ).css( 'display', 'none' );
  }
});
</script>
