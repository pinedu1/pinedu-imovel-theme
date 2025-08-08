<?php
$nome = '';
$email = '';
$telefone = '';
$cpf = '';
$identificacao = '';
if ( isset( $_REQUEST['nome'] ) ) {
	$nome = $_REQUEST['nome'];
}
if ( isset( $_REQUEST['email'] ) ) {
  $email = $_REQUEST['email'];
}
if ( isset( $_REQUEST['telefone'] ) ) {
  $telefone = $_REQUEST['telefone'];
}
if (  isset( $_REQUEST['cpf'] ) ) {
  $cpf = $_REQUEST['cpf'];
}
if ( isset( $_REQUEST['identificacao_ok'] ) ) {
  $identificacao = $_REQUEST['identificacao_ok'];
}
?>
<section class="contato" name="contato">
  <header class="row">
    <h4>Identificação</h4>
  </header>
  <form name="cadastro-form" id="cadastro-form" action="<?php echo esc_url(get_permalink()); ?>" method="POST" enctype="multipart/form-data">
    <?php wp_nonce_field('cadastro_imovel', 'property_nonce'); ?>
    <input type="hidden" name="identificacao" id="identificacao" value="<?php echo $identificacao; ?>">
    <div class="row">
      <div class="col col-nome">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" class="cadastre identificacao" placeholder="Nome" value="<?php echo $nome; ?>" required>
      </div>
      <div class="col col-email">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" required>
      </div>
      <div class="col col-telefone">
        <label for="telefone">Telefone</label>
        <input type="text" name="telefone" id="telefone" placeholder="Telefone" value="<?php echo $telefone; ?>" maxlength="15" required>
      </div>
      <div class="col col-cpf">
        <label for="cpf">Cpf</label>
        <input type="text" name="cpf" id="cpf" placeholder="CPF" value="<?php echo $cpf; ?>" maxlength="18" required>
      </div>
    </div>
    <footer class="row" id="passoPosIdentificacao">
      <div class="row mensagem">
        <p>Sua identificação é essencial para oferecermos o melhor serviço; com as informações fornecidas, um corretor qualificado entrará em contato para ajudar na promoção do seu imóvel; seus dados estarão protegidos e usados exclusivamente conforme nossa &nbsp;<a href="./politicaPrivacidade" class="cadastre-privacy">Política de Privacidade</a></strong>.</p>
      </div>
      <div class="row buttom">
        <button type="button" id="btnProximo" onClick="passoPosIdentificacao(event);">Próximo</button>
      </div>
    </footer>
  </form>
</section>
