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
  if ( $contrato_padrao ) {
    $terms_faixa_valor = lista_faixa_valor( $contrato_padrao );
  }
?>
<aside class="sidebar-pesquisa">
  <?php if (is_singular('imovel')):
    get_template_part( 'template-parts/imovel/solicita-visita', 'imovel' );
  endif; ?>
  <section class="sidebar pesquisa">
    <header class="pesquisa-header">
      <h4>Pesquisa</h4>
    </header>
    <main class="pesquisa-content">
      <form role="search" method="get" id="form-pesquisa" class="barra pesquisa-form" action="<?php echo esc_url( home_url( '/pesquisa' ) ); ?>">
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
                <?php if ( $terms_contrato && !empty( $terms_contrato ) ): ?>
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
                <?php if ( $terms_tipo_imovel && !empty( $terms_tipo_imovel ) ): ?>
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
                <?php if ( $terms_cidade && !empty( $terms_cidade ) ): ?>
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
                <?php if ( $terms_regiao && !empty( $terms_regiao ) ): ?>
                  <?php foreach ((array) $terms_regiao as $regiao): ?>
                    <option value="<?php echo esc_attr($regiao->slug); ?>" <?php echo ($regiao->slug == $regiao_padrao)? 'selected': '' ?>><?php echo esc_html($regiao->name); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </li>
          <li class="faixa-valor">
            <div><label for="faixa-valor">Faixa de Valor</label></div>
            <div class="select-container">
              <select id="faixa-valor" name="faixa-valor">
                <option value="">Selecione...</option>
                <?php if ( $terms_faixa_valor && !empty( $terms_faixa_valor ) ): ?>
                  <?php foreach ((array) $terms_faixa_valor as $faixa): ?>
                    <option valor-inicial="<?php echo esc_attr(get_term_meta($faixa->term_id, 'valor-inicial', true)); ?>"
                            valor-final="<?php echo esc_attr(get_term_meta($faixa->term_id, 'valor-final', true)); ?>"
                            value="<?php echo esc_attr($faixa->slug); ?>">
                      <?php echo esc_html($faixa->name); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </li>
          <li class="submit pesquisa">
            <div class="submit-container">
              <button type="submit" class="button button-small">Pesquisa Imóveis</button>
            </div>
          </li>
        </ul>
      </form>
    </main>
  </section>
  <section class="sidebar consulta">
    <header class="pesquisa-header">
      <h4>Consulta</h4>
    </header>
    <main class="pesquisa-content">
      <form name="consultaReferencia" role="consulta" method="get" class="consulta-form" action="<?php echo esc_url(home_url('/pesquisa')); ?>">
        <input type="hidden" name="tipo_pesquisa_submit" value="consulta">
        <ul>
          <li class="referencia">
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
  </section>
</aside>
<script>
jQuery(document).ready(function($) {
  $('#referencia').on('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      buscaReferencia( e );
    }
  });
  $('form[name="consultaReferencia"]').on('submit', function(event) {
    event.preventDefault();
    console.log('oi');
    if(validarFormularioConsulta()) {
      $(this).unbind('submit').submit();
    }
  });
});
</script>
