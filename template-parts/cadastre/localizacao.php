<?php
require_once get_template_directory() . '/inc/classes/Gpb.php';
$tipoImovel = get_query_var( 'tipoImovel', '' );
$gpb = new Gpb();
$data = $gpb->localizacao( $tipoImovel );
$tipoImovel = false;
if ( isset( $data->tipoImovel ) ) {
  $tipoImovel = $data->tipoImovel;
}
$estado = isset($_REQUEST['estado']) ? sanitize_text_field($_REQUEST['estado']) : '';
$cidade = isset($_REQUEST['cidade']) ? sanitize_text_field($_REQUEST['cidade']) : '';
$bairro = isset($_REQUEST['bairro']) ? sanitize_text_field($_REQUEST['bairro']) : '';
$logradouro = isset($_REQUEST['logradouro']) ? sanitize_text_field($_REQUEST['logradouro']) : '';
$numero = isset($_REQUEST['numero']) ? sanitize_text_field($_REQUEST['numero']) : '';
$cep = isset($_REQUEST['cep']) ? sanitize_text_field($_REQUEST['cep']) : '';
$apt = isset($_REQUEST['apt']) ? sanitize_text_field($_REQUEST['apt']) : '';
$blc = isset($_REQUEST['blc']) ? sanitize_text_field($_REQUEST['blc']) : '';
?>
<div class="row">
  <div class="col col-estado">
    <label for="estado">Estado</label>
    <select id="estado" name="estado" onchange="estadoChange(this);">
      <option value="">Selecione...</option>
      <?php if ( $data->estados && ! empty( $data->estados ) ): ?>
        <?php foreach ( (array) $data->estados as $uf): ?>
          <option value="<?php echo esc_attr($uf->id); ?>" <?php echo ($uf->selected == true)? 'selected': '' ?>><?php echo esc_html($uf->uf); ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>
  <div class="col col-cidade">
    <label for="cidade">Cidade</label>
    <select id="cidade" name="cidade" onchange="cidadeChange(this);">
      <option value="">Selecione...</option>
      <?php if ( $data->cidades && ! empty( $data->cidades ) ): ?>
        <?php foreach ( (array) $data->cidades as $cidade): ?>
          <option value="<?php echo esc_attr($cidade->id); ?>" <?php echo ($cidade->selected == true)? 'selected': '' ?>><?php echo esc_html($cidade->nome); ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
    <div class="loading-overlay"></div>
  </div>
  <div class="col col-bairro">
    <label for="bairro">Bairro</label>
    <select id="bairro" name="bairro">
      <option value="">Selecione...</option>
      <?php if ( $data->bairros && ! empty( $data->bairros ) ): ?>
        <?php foreach ( (array) $data->bairros as $bairro): ?>
          <option value="<?php echo esc_attr($bairro->id); ?>" <?php echo ($bairro->selected == true)? 'selected': '' ?>><?php echo esc_html($bairro->nome); ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
    <div class="loading-overlay"></div>
  </div>
</div>
<div class="row">
  <div class="col col-logradouro">
    <input type="hidden" id="logradouroId" name="logradouroId">
    <label for="logradouro">Endereço</label>
    <input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Endereço" onfocus="initAutocompleteEndereco(this);" value="<?php echo $logradouro; ?>" required>
  </div>
  <div class="col col-numero">
    <label for="numero">Número</label>
    <input type="text" class="form-control" id="numero" name="numero" placeholder="Número" value="<?php echo $numero; ?>" required>
  </div>
  <div class="col col-apt">
    <label for="apt"><?php echo $tipoImovel? $tipoImovel->campoUnidade: ''; ?></label>
    <input type="text" class="form-control" id="apt" name="apt" placeholder="<?php echo $tipoImovel? $tipoImovel->campoUnidade: ''; ?>" value="<?php echo $apt; ?>">
  </div>
  <div class="col col-blc">
    <label for="blc"><?php echo $tipoImovel? $tipoImovel->campoBloco: ''; ?></label>
    <input type="text" class="form-control" id="blc" name="blc" placeholder="<?php echo $tipoImovel? $tipoImovel->campoBloco: ''; ?>" value="<?php echo $blc; ?>">
  </div>
  <div class="col col-cep">
    <label for="cep">Cep</label>
    <input type="text" class="form-control" id="cep" name="cep" placeholder="Cep" value="<?php echo $cep; ?>">
  </div>
</div>
<footer class="row" id="passoPosLocalizacao">
  <div class="row mensagem">
    <p>A localização do imóvel é um dos fatores principais para promover o seu imóvel; selecione o &nbsp;<strong>Estado</strong>&nbsp; e a &nbsp;<strong>Cidade</strong>,&nbsp; e comece a digitar o nome da Localidade; o sistema preencherá automaticamente os campos &nbsp;<strong>CEP</strong>&nbsp; e &nbsp;<strong>Bairro</strong>&nbsp;.</p>
  </div>
  <div class="row buttom">
    <button id="btnProximo" type="button" class="btn" onClick="return passoPosLocalizacao(event);">Próximo</button>
  </div>
</footer>
