<?php
$referencia = get_query_var( 'referencia' );
$codigo_corretor = get_query_var( 'codigo-corretor' );
$nome_corretor = get_query_var( 'nome-corretor' );
?>
<div id="div-contato-corretor" class="contato-corretor-form row">
  <form name="contato-corretor-form" id="contato-corretor-form" class="contato-corretor-form" action="contato_corretor">
    <?php wp_nonce_field( 'contato_corretor', 'contato_corretor_nonce' ); ?>
    <input type="hidden" name="referencia" value="<?php echo $referencia; ?>">
    <input type="hidden" name="corretor" value="<?php echo $codigo_corretor; ?>">
    <div class="flash-message"></div>
    <div class="row" title="Informe seus dados o corretor <?php echo $nome_corretor; ?> possa entrar em contato, e lhe fornecer o melhor atendimento">
      <label for="nome">Nome <span class="required">*</span></label>
      <input type="text" name="nome" value="" size="20" required="required" placeholder="Nome para Contato">
    </div>
    <div class="row" title="Informe seus dados o corretor <?php echo $nome_corretor; ?> possa entrar em contato, e lhe fornecer o melhor atendimento">
      <label for="email">Email <span class="required">*</span></label>
      <input type="email" name="email" value="" aria-required="true" required="required" placeholder="Email para Contato">
    </div>
    <div class="row" title="Informe seus dados o corretor <?php echo $nome_corretor; ?> possa entrar em contato, e lhe fornecer o melhor atendimento">
      <label for="telefone">Telefone <span class="required">*</span></label>
      <input type="text" name="telefone" value="" required="" placeholder="Telefone para Contato">
    </div>
    <div class="row submit">
      <input type="submit" name="submit" class="button button-small" value="Enviar" onClick="return contatoCorretor( this );">
    </div>
  </form>
</div>
