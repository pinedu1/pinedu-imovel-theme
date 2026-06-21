<?php
// Captura das chaves diretamente das opções do tema
$options = get_option( 'pinedu_imovel_options', [] );
$chave_publica = $options['chave_publica_recaptcha'] ?? '';
$chave_secreta = $options['chave_secreta_recaptcha'] ?? '';
// Correção: Uso do operador ternário correto para não transformar o texto em booleano
$nome	 = isset( $_POST['nome'] )	 ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '';
$email	= isset( $_POST['email'] )	? sanitize_email( wp_unslash( $_POST['email'] ) )	: '';
$telefone = isset( $_POST['telefone'] ) ? sanitize_text_field( wp_unslash( $_POST['telefone'] ) ) : '';
$mensagem = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';
if ( isset( $_SERVER['REQUEST_METHOD'] ) && ( 'POST' === $_SERVER['REQUEST_METHOD'] ) ) {
  if ( ! isset( $_POST['contato_nonce'] ) || ! wp_verify_nonce( $_POST['contato_nonce'], 'contato' ) ) {
    wp_die( 'Erro de segurança: Validação expirada ou requisição inválida.' );
  }
  // 1. VERIFICAÇÃO DE CHAVES CONFIGURADAS
  if ( empty( $chave_publica ) || empty( $chave_secreta ) ) {
    wp_die( 'Preciso da chave recaptcha configurada no painel para prosseguir.' );
  }
  // 2. VERIFICAÇÃO DO TOKEN (Prevenção contra requisições forjadas via CURL/Postman)
  $recaptcha_token = isset( $_POST['recaptcha_token'] ) ? sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ) ) : '';
  if ( empty( $recaptcha_token ) ) {
    wp_die( 'Erro: Token de segurança ausente. Acesso negado.' );
  }
  // 3. VALIDAÇÃO NA API DO GOOGLE
  $url_google = "https://www.google.com/recaptcha/api/siteverify?secret={$chave_secreta}&response={$recaptcha_token}";
  $resposta = wp_remote_get( $url_google );
  $corpo_resposta = wp_remote_retrieve_body( $resposta );
  $retorno = json_decode( $corpo_resposta );
  // Nota menor que 0.5 geralmente é bot
  if ( ! isset( $retorno->success ) || $retorno->success !== true || $retorno->score < 0.5 ) {
    wp_die( 'Erro de validação de segurança (reCAPTCHA). Acesso negado.' );
  }
  // 4. PROCESSAMENTO DO E-MAIL (Apenas se passar pelo reCAPTCHA)
  $destinatario = $options['email_contato'] ?? '';
  if ( empty( $destinatario ) ) {
    wp_die( 'Por favor, não está configurado Destinatário para este email!.' );
  }
  if ( isset( $post_type ) && ( 'imovel' === $post_type ) ) {
    global $post; // Garantir o escopo da variável $post
    $my_email = get_post_meta( $post->ID, 'email', true );
    if ( ! empty( $my_email ) ) {
      $destinatario = $my_email;
    }
  }
  $assunto = "Contato via Site de: $nome";
  $corpo_email = "Nome: $nome\n";
  $corpo_email .= "Email: $email\n";
  // Verificação se a função formata_telefone existe antes de chamar
  $corpo_email .= 'Telefone: ' . ( function_exists('formata_telefone') ? formata_telefone( $telefone ) : $telefone ) . "\n";
  $corpo_email .= "Mensagem: $mensagem";
  // Corrigido para "From" (padrão RFC) em vez de "De" para evitar bloqueios de servidor
  $headers  = "From: $nome <$email>\r\n";
  $headers .= "Reply-To: $email\r\n";
  if ( empty( $nome ) )	 wp_die( 'Por favor, informe seu Nome para contato!.' );
  if ( empty( $email ) )	wp_die( 'Por favor, informe seu Email para contato!.' );
  if ( empty( $mensagem ) ) wp_die( 'Por favor, informe a Mensagem para contato!.' );
  $cookie_id = function_exists('getCookieId') ? getCookieId() : '';
  $resultado_email = wp_mail( $destinatario, $assunto, $corpo_email, $headers );
  if ( $resultado_email ) {
    if ( function_exists('enviarCliente') ) {
      enviarCliente( $nome, $telefone, $email, $mensagem, $cookie_id );
    }
    $email_enviado = true;
  }
}
$dados_form = [
  'nome'          => $nome,
  'email'         => $email,
  'telefone'      => $telefone,
  'mensagem'      => $mensagem,
  'email_enviado' => isset( $email_enviado ) ? $email_enviado : false,
  'chave_publica'=>$chave_publica
];
get_template_part( 'template-parts/contato/contato-template-form', 'imovel', $dados_form );
if ( ! empty( $chave_publica ) && ! isset( $email_enviado ) ) : ?>
  <script>
    document.getElementById('contact-form').addEventListener('submit', function(e) {
      e.preventDefault();
      grecaptcha.ready(function() {
        grecaptcha.execute(PineduVars.chave_site, {action: 'submit'}).then(function(token) {
          document.getElementById('recaptcha_token').value = token;
          document.getElementById('contact-form').submit();
        });
      });
    });
  </script>
<?php endif; ?>
