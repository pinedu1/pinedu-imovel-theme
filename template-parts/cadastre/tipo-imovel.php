<?php
$tipo_imovel_padrao = isset($_REQUEST['tipo-imovel']) ? sanitize_text_field($_REQUEST['tipo-imovel']) : '';
$terms_tipo_imovel = lista_tipo_imovel();
?>
<div class="row">
  <div class="col col-half col-tipo">
    <label for="tipo-imovel">Tipo de Imóvel</label>
    <select id="tipo-imovel" name="tipo-imovel">
      <option value="">Selecione...</option>
      <?php foreach ( (array) $terms_tipo_imovel as $tipo): ?>
        <option value="<?php echo esc_attr($tipo->slug); ?>" <?php echo ($tipo->slug == $tipo_imovel_padrao)? 'selected': '' ?>><?php echo esc_html($tipo->name); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>
<footer class="row" id="passoPosTipoImovel">
  <div class="row mensagem">
    <p>Por favor, defina o tipo de imóvel que você deseja deixar sob a nossa responsabilidade; com essa informação, poderemos selecionar os dados necessários para a promoção da sua unidade.</p>
  </div>
  <div class="row buttom">
    <button id="btnProximo" type="button" class="btn" onClick="return passoPosTipoImovel(event);">Próximo</button>
  </div>
</footer>
