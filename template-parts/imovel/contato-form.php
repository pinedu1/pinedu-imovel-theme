<?php
$referencia = get_query_var( 'referencia' );
$codigo_corretor = get_query_var( 'codigo-corretor' );
?>
<div id="div-contato" class="contato-form row">
  <form id="contato-form" class="contato-form" action="contato_imovel">
    <?php wp_nonce_field( 'contato_imovel', 'contato_nonce' ); ?>
    <input type="hidden" name="referencia" value="<?php echo $referencia; ?>">
    <input type="hidden" name="corretor" value="<?php echo $codigo_corretor; ?>">
    <div class="flash-message"></div>
    <div class="row" title="Informe seus dados para que um dos nossos corretores possa entrar em contato, e marcar sua visita">
      <label for="nome">Nome <span class="required">*</span></label>
      <input type="text" name="nome" value="" size="20" required="required" placeholder="Nome para Contato">
    </div>
    <div class="row" title="Informe seus dados para que um dos nossos corretores possa entrar em contato, e marcar sua visita">
      <label for="email">Email <span class="required">*</span></label>
      <input type="email" name="email" value="" aria-required="true" required="required" placeholder="Email para Contato">
    </div>
    <div class="row" title="Informe seus dados para que um dos nossos corretores possa entrar em contato, e marcar sua visita">
      <label for="telefone">Telefone <span class="required">*</span></label>
      <input type="text" name="telefone" value="" required="" placeholder="Telefone para Contato">
    </div>
    <div class="row submit">
      <input type="submit" name="submit" class="button button-small" value="Enviar" onClick="return enviarContato( this );">
    </div>
  </form>
</div>
