<?php
$nome = isset($_REQUEST['nome']) ? htmlspecialchars($_REQUEST['nome'], ENT_QUOTES, 'UTF-8'): '';
$email = isset($_REQUEST['email']) ? htmlspecialchars($_REQUEST['email'], ENT_QUOTES, 'UTF-8'): '';
$telefone = isset($_REQUEST['telefone']) ? htmlspecialchars($_REQUEST['telefone'], ENT_QUOTES, 'UTF-8'): '';
$mensagem = isset($_REQUEST['mensagem']) ? htmlspecialchars($_REQUEST['mensagem'], ENT_QUOTES, 'UTF-8'): '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $options = get_option( 'pinedu_imovel_options', [] );
	$destinatario = $options['email_contato'];
  if ( empty($destinatario) ) {
    wp_die('Por favor, Não está configurado DEstinatário para este email!.');
  }
	if ( isset( $post_type ) && ( $post_type == 'imovel' ) ) {
        $my_email = get_post_meta( $post->ID, 'email', true );
        if ( !empty( $my_email ) ) {
	        $destinatario = $my_email;
        }
	}

	$assunto = "Contato via Site de: $nome";
	$corpo_email = "Nome: $nome\n";
	$corpo_email .= "Email: $email\n";
	$corpo_email .= "Telefone: " . formata_telefone( $telefone ) . "\n";
	$corpo_email .= "Mensagem: $mensagem";

	$headers = "De: $nome <$email>";
	if ( empty($nome) ) {
		wp_die('Por favor, informe seu Nome para contato!.');
	}
	if ( empty($email) ) {
		wp_die('Por favor, informe seu Email para contato!.');
	}
	if ( empty($mensagem) ) {
		wp_die('Por favor, informe a Mensagem para contato!.');
	}
  /* Atualiza as propriedades do Cookie
  * Para salvar o Pré-Interessado
  */
  //$cookieId = updateCookie( $nome, $telefone, $email );
  $cookieId = getCookieId();
	$resultado_email = wp_mail($destinatario, $assunto, $corpo_email, $headers);
	if ( $resultado_email ) {
    /* Envia dados do Pre-interessado para SERVIDOR */
    enviarCliente( $nome, $telefone, $email, $mensagem, $cookieId );
    $email_enviado = true;
	}
}
?>
<div class="contact-form">
	<?php if (isset($email_enviado) && $email_enviado): ?>
    <header>
      <h4>Email enviado com sucesso!</h4><br>
    </header>
    <div class="sucess-container">
		  <p class="resultado-email">Em breve o melhor profissional entrará em contato com você, procurando a melhor solução para sua mensagem.</p>
  		<p class="resultado-email"><a href="<?php echo esc_url(home_url('/')); ?>">Clique aqui para ser direcionado para <strong>HOME PAGE</strong></a></p>
    </div>
	<?php else: ?>
    <header>
      <h4>Deixe seus dados de contato. Envie sua mensagem</h4>
    </header>
		<form action="<?php echo esc_url(get_permalink()); ?>" method="post">
			<input type="text" class="form-contato" placeholder="Digite aqui seu Nome" name="nome" value="<?php echo $nome; ?>" required/>
			<input type="email" class="form-contato" placeholder="Digite aqui seu Email" name="email" value="<?php echo $email; ?>" required/>
			<input type="text" class="form-contato" placeholder="Digite aqui seu Telefone" name="telefone" value="<?php echo $telefone; ?>" />
			<textarea id="mensagem"  class="form-contato" placeholder="Digite aqui a mensagem" name="mensagem" rows="10" required><?php echo $mensagem; ?></textarea>
			<button class="btn" type="submit">Enviar</button>
		</form>
	<?php endif; ?>
</div>
