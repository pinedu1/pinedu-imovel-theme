<?php
require_once get_template_directory() . '/inc/classes/DoGet.php';
require_once get_template_directory() . '/inc/classes/CadastreTipoImovel.php';
parse_str( $_POST['form_data'], $dados );
$tipoImovel = $dados['tipo-imovel'];
$options = get_option('pinedu_imovel_options', []);
$server = $options['url_servidor'] ?? '';
$token = $options['token'];
$do = new DoGet();
$data = $do->do_get( $token, $server, '/pndPortal/wordpress/tipoImovel', [ 'tipoImovel' => $tipoImovel ] );
if ( $data->success === true ): $tipoImovel = $data->tipoImovel; $relativo = $tipoImovel->relativo; ?>
<section id="caracteristicas">
  <button id="btnCaracteristicas" class="btn btn-link" type="button" data-toggle="collapse" data-target="#detalhesCaracteristicas" aria-expanded="false" aria-controls="detalhesCaracteristicas">
    <i class="fa fa-chevron-down" id="collapseIcon"></i>Mostrar Características
  </button>
  <div class="row collapse" id="detalhesCaracteristicas" style="display: none;">
    <div class="col-grid">
      <header><h4>Características</h4></header>
      <?php foreach ($relativo->CARACTERISTICAS as $dep ): ?>
        <?php CadastreTipoImovel::campos_caracteristicas( $dep ); ?>
      <?php endforeach; ?>
    </div>
    <div class="col-grid">
      <header><h4>Infraestrutura</h4></header>
      <?php foreach ($relativo->INFRAEXTRUTURA as $dep ): ?>
        <?php CadastreTipoImovel::campos_caracteristicas( $dep ); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<footer class="row">
  <div id="passoPosCaracteristicas" class="row">
    <div class="row mensagem">
      <p>Acima listamos possíveis características que podem tornar a promoção do seu imóvel ainda mais empolgante.</p>
      <p>Quanto maiores as informações que disponibilizamos para os possíveis compradores deste imóvel, mais rica se torna a experiência do comprador.</p>
      <p>Porém, o preenchimento dos dados desta seção é opcional.</p>
    </div>
    <div class="row buttom">
      <button id="btnProximo" type="button" class="btn" onclick="passoPosCaracteristicas(event);">Próximo</button>
    </div>
  </div>
</footer>
<?php endif;?>
<script>
  jQuery(document).ready(function($) {
    instalaCaracteristicaToogle($)
  });
</script>
