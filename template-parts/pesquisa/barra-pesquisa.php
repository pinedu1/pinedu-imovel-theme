<?php
  $options = get_option( 'pinedu_imovel_options' );
  if ( isset( $_REQUEST[ 'tipo_pesquisa_submit' ] ) ) {
    $tipo_pesquisa_submit = sanitize_text_field( $_REQUEST[ 'tipo_pesquisa_submit' ] );
  }
  $contrato_padrao = get_query_var( 'contrato' );
  if ( empty( $contrato_padrao ) && isset( $_REQUEST[ 'contrato' ] ) ) {
    $contrato_padrao = sanitize_text_field( $_REQUEST[ 'contrato' ] );
  }
  $tipo_imovel_padrao = get_query_var( 'tipo-imovel' );
/*  if ( empty( $tipo_imovel_padrao ) && ! empty( $options['tipo_imovel'] ) ) {
    $tipo_imovel_padrao = strtolower( $options['tipo_imovel'] );
  }*/
  if ( isset( $_REQUEST[ 'tipo-imovel' ] ) ) {
    $tipo_imovel_padrao = sanitize_text_field( $_REQUEST[ 'tipo-imovel' ] );
  }
  $cidade_padrao = get_query_var( 'cidade' );
  if ( empty( $cidade_padrao ) && isset( $_REQUEST[ 'cidade' ] ) ) {
    $cidade_padrao = sanitize_text_field( $_REQUEST[ 'cidade' ] );
  }
  $regiao_padrao = get_query_var( 'regiao' );
  if ( empty( $regiao_padrao ) && isset( $_REQUEST[ 'regiao' ] ) ) {
    $regiao_padrao = sanitize_text_field( $_REQUEST[ 'regiao' ] );
  }
  $valor_inicial_padrao = get_query_var( 'valor-inicial' );
  if ( empty( $valor_inicial_padrao ) && isset( $_REQUEST[ 'valor-inicial' ] ) ) {
    $valor_inicial_padrao = sanitize_text_field( $_REQUEST[ 'valor-inicial' ] );
  }
  $valor_final_padrao = get_query_var( 'valor-final' );
  if ( empty( $valor_final_padrao ) && isset( $_REQUEST[ 'valor-final' ] ) ) {
    $valor_final_padrao = sanitize_text_field( $_REQUEST[ 'valor-final' ] );
  }

  $terms_contrato = lista_contratos( );
  $terms_tipo_imovel = lista_tipo_imovel( );
  $terms_cidade = lista_cidade( );
  $terms_regiao = [];
  if ( '' == $cidade_padrao ) {
    $terms_regiao = lista_regiao( $cidade_padrao );
  }
  $terms_faixa_valor = lista_faixa_valor_valores( $contrato_padrao );
  add_action('wp_footer', function() use ( $terms_faixa_valor ) {
    ?>
    <script type="text/javascript">
      var PineduJsVars = <?php echo json_encode($terms_faixa_valor); ?>;
    </script>
    <?php
  }, 1);
?>
<section class="barra-pesquisa pesquisa">
  <main class="pesquisa-content">
    <div id="loading-overlay" class="overlay-form" style="display: none;">
      <div class="spinner-container">
        <div class="spinner"></div> <p>Carregando dados, aguarde!</p>
      </div>
    </div>
    <header class="pesquisa-header">
      <h4>Pesquisa</h4>
    </header>
    <form role="search" method="get" id="form-pesquisa" class="barra pesquisa-form" action="<?php echo esc_url( home_url( '/pesquisa' ) ); ?>">
      <input type="hidden" name="tipo_pesquisa_submit" value="imovel">
      <input type="hidden" name="valor-inicial" value="<?php echo isset( $_REQUEST['valor-inicial'] ) ? floatval( $_REQUEST['valor-inicial'] ) : 0; ?>" class="valor-inicial">
      <input type="hidden" name="valor-final" value="<?php echo isset( $_REQUEST['valor-final'] ) ? floatval( $_REQUEST['valor-final'] ) : 0; ?>" class="valor-final">
      <input type="hidden" name="post_type" value="imovel" />
      <input type="hidden" name="max" value="12">
      <input type="hidden" name="sort" value="dataPreco">
      <input type="hidden" name="ordem" value="DESC">
      <ul>
        <li class="contrato">
          <div><label for="contrato">Tipo de Contrato</label></div>
          <div class="select-container">
            <select id="contrato" name="contrato">
              <option value="" <?php echo ( '' == $contrato_padrao ) ? 'selected="true"' : '' ?>>Selecione...</option>
              <?php if ( isset( $terms_contrato ) && ! empty( $terms_contrato ) ) : ?>
                <?php foreach ( (array) $terms_contrato as $contrato ) : ?>
                  <option value="<?php echo esc_attr( $contrato->slug ); ?>" <?php echo ( $contrato->slug == $contrato_padrao ) ? 'selected="true"' : '' ?>><?php echo esc_html( $contrato->name ); ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </li>
        <li class="tipo-imovel">
          <div><label for="tipo-imovel">Tipo de Imóvel</label></div>
          <div class="select-container">
            <select id="tipo-imovel" name="tipo-imovel">
              <?php $placeholder_selected = empty( $tipo_imovel_padrao ) ?? 'selected'; ?>
              <option value="" <?php echo ( empty( $tipo_imovel_padrao ) ? 'selected="true"' : '' ) ?>>Selecione...</option>
              <?php if ( isset( $terms_tipo_imovel ) && ! empty( $terms_tipo_imovel ) ): ?>
                <?php foreach ( (array) $terms_tipo_imovel as $tipo_imovel ): ?>
                  <option value="<?php echo esc_attr( $tipo_imovel->slug ); ?>" <?php echo ( $tipo_imovel->slug === $tipo_imovel_padrao ) ? 'selected="true"' : '' ?>><?php echo esc_html( $tipo_imovel->name ); ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </li>
        <li class="cidade">
          <div><label for="cidade">Cidade</label></div>
          <div class="select-container">
            <select id="cidade" name="cidade">
              <option value="" <?php echo ( '' == $cidade_padrao ) ? 'selected="true"' : '' ?>>Selecione...</option>
              <?php if ( isset( $terms_cidade ) && ! empty( $terms_cidade ) ) : ?>
                <?php foreach ( (array) $terms_cidade as $cidade ) : ?>
                  <option value="<?php echo esc_attr( $cidade->slug ); ?>" <?php echo ( $cidade->slug == $cidade_padrao ) ? 'selected="true"' : '' ?>><?php echo esc_html( $cidade->name ); ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </li>
        <li class="regiao">
          <div><label for="regiao">Região</label></div>
          <div class="select-container">
            <select id="regiao" name="regiao">
              <option value="" <?php echo ( '' == $regiao_padrao ) ? 'selected="true"' : '' ?>>Selecione...</option>
              <?php if ( isset( $terms_regiao ) && ! empty( $terms_regiao ) ) : ?>
                <?php foreach ( (array) $terms_regiao as $regiao ) : ?>
                  <option value="<?php echo esc_attr( $regiao->slug ); ?>" <?php echo ( $regiao->slug == $regiao_padrao ) ? 'selected="true"' : '' ?>><?php echo esc_html( $regiao->name ); ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </li>
        <li class="faixa-valor-slider">
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
    <footer class="pesquisa-footer">
    </footer>
    <header class="pesquisa-header">
      <h4>Consulta</h4>
    </header>
    <form name="consultaReferencia" role="consulta" method="get" class="consulta-form" action="<?php echo esc_url( home_url( '/pesquisa' ) ); ?>">
    <input type="hidden" name="tipo_pesquisa_submit" value="consulta">
    <ul>
      <li class="referencia">
        <div><label for="referencia">Referência</label></div>
        <div class="input-container"><input type="text" name="referencia" id="referencia" placeholder="Referência" required aria-required="true"></div>
      </li>
      <li class="submit consulta">
        <div class="submit-container">
          <button id="submit-consulta" type="submit" class="button button-small">Consultar</button>
        </div>
      </li>
    </ul>
  </form>
</section>
<script>
  function encontrarJanelaCentral(obj) {
    var keys = Object.keys(obj);
    var len = keys.length;
    var pivo = len / 2;
    var ini, fim;
    if (len % 2 !== 0) {
      ini = obj[Math.floor(pivo)];
      fim = obj[Math.ceil(pivo)];
    } else {
      ini = obj[pivo - 1];
      fim = obj[pivo + 1];
    }
    return {
      length: len,
      pivo: pivo,
      ini: ini,
      fim: fim
    };
  }

jQuery( document ).ready( function( $ ) {
  var rangeSlider = <?php echo json_encode( $terms_faixa_valor ) ?>
    , medianLow = parseFloat( document.querySelector('input[name=valor-inicial]')?.value )
    , medianHigh = parseFloat( document.querySelector('input[name=valor-final]')?.value )
    , minimo = 0
    , maximo = 0
    , range = 0;

  if ( rangeSlider.length > 0 ) {
    range = ( rangeSlider[rangeSlider.length - 1] - rangeSlider[0] ) / rangeSlider.length;
    if ( medianLow <= 0 && medianHigh <= 0 ) {
      const janelaCentral = encontrarJanelaCentral( rangeSlider );
      medianLow = janelaCentral.ini;
      medianHigh = janelaCentral.fim;
    }
    minimo = rangeSlider[0];
    maximo = rangeSlider[rangeSlider.length - 1];
    instalaSlider( minimo, maximo, range, medianLow, medianHigh );
  } else {
    $( 'li.faixa-valor-slider' ).css( 'display', 'none' );
  }
} );
</script>
