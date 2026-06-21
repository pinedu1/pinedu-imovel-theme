<?php
// Inicializa variáveis
$email_enviado = false;
$options	   = get_option( 'pinedu_imovel_options', [] );
$chave_publica = $options['chave_publica_recaptcha'] ?? '';
$chave_secreta = $options['chave_secreta_recaptcha'] ?? '';
// 1. Captura Segura: Verifica se existe, remove barras de escape e sanitiza
$nome	 = isset( $_POST['nome'] )	 ? sanitize_text_field( wp_unslash( $_POST['nome'] ) )	 : '';
$email	= isset( $_POST['email'] )	? sanitize_email( wp_unslash( $_POST['email'] ) )		 : '';
$celular  = isset( $_POST['celular'] )  ? sanitize_text_field( wp_unslash( $_POST['celular'] ) )  : '';
$cidade   = isset( $_POST['cidade'] )   ? sanitize_text_field( wp_unslash( $_POST['cidade'] ) )   : '';
$cargo	= isset( $_POST['cargo'] )	? sanitize_text_field( wp_unslash( $_POST['cargo'] ) )	: '';
$creci	= isset( $_POST['creci'] )	? sanitize_text_field( wp_unslash( $_POST['creci'] ) )	: '';
$mensagem = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';
$nome_arquivo_curriculo = ''; // Inicializa a variável para a template
if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {

  // 1. Verificação de Nonce (Segurança)
  if ( ! isset( $_POST['trabalhe_conosco_nonce'] ) || ! wp_verify_nonce( $_POST['trabalhe_conosco_nonce'], 'trabalhe_conosco' ) ) {
    wp_die( 'Erro de segurança: Validação expirada ou requisição inválida.' );
  }
  // 2. Verificação de Chaves reCAPTCHA
  if ( empty( $chave_publica ) || empty( $chave_secreta ) ) {
    wp_die( 'Preciso da chave recaptcha configurada no painel para prosseguir.' );
  }
  // 3. Validação do Token reCAPTCHA no Google (Importado do contato-form.php)
  $recaptcha_token = isset( $_POST['recaptcha_token'] ) ? sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ) ) : '';
  if ( empty( $recaptcha_token ) ) {
    wp_die( 'Erro: Token de segurança ausente. Acesso negado.' );
  }
  $url_google = "https://www.google.com/recaptcha/api/siteverify?secret={$chave_secreta}&response={$recaptcha_token}";
  $resposta = wp_remote_get( $url_google );
  $corpo_resposta = wp_remote_retrieve_body( $resposta );
  $retorno = json_decode( $corpo_resposta );
  if ( ! isset( $retorno->success ) || $retorno->success !== true || $retorno->score < 0.5 ) {
    wp_die( 'Erro de validação de segurança (reCAPTCHA). Acesso negado.' );
  }
  // 4. Verificação de Preenchimento Obrigatório (empty)
  if ( empty( $nome ) )	wp_die( 'Por favor, informe seu Nome completo.' );
  if ( empty( $email ) )   wp_die( 'Por favor, informe seu E-mail válido.' );
  if ( empty( $celular ) ) wp_die( 'Por favor, informe seu Celular ou WhatsApp.' );
  if ( empty( $cidade ) )  wp_die( 'Por favor, informe sua Cidade.' );
  if ( empty( $cargo ) )   wp_die( 'Por favor, informe o Cargo desejado.' );
  // 5. Tratamento de Upload do Currículo
  $attachment = [];
  if ( ! empty( $_FILES['curriculo']['name'] ) ) {

    // Exigência do WordPress para usar wp_handle_upload() no frontend
    if ( ! function_exists( 'wp_handle_upload' ) ) {
      require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    $uploaded_file = $_FILES['curriculo'];
    $nome_arquivo_curriculo = sanitize_file_name( $uploaded_file['name'] );
    // Validação de tipo de arquivo por segurança
    $tipo_arquivo = wp_check_filetype( $nome_arquivo_curriculo );
    $tipos_permitidos = [ 'pdf', 'doc', 'docx' ];
    if ( ! in_array( strtolower( $tipo_arquivo['ext'] ), $tipos_permitidos ) ) {
      wp_die( 'Erro de segurança: Apenas arquivos PDF, DOC ou DOCX são permitidos.' );
    }
    // Move o arquivo para a pasta temporária de Uploads do WP
    $upload_overrides = array( 'test_form' => false );
    $movefile = wp_handle_upload( $uploaded_file, $upload_overrides );
    if ( $movefile && ! isset( $movefile['error'] ) ) {
      // O wp_mail precisa do caminho absoluto do servidor para anexar o arquivo
      $attachment = array( $movefile['file'] );
    } else {
      wp_die( 'Erro ao processar o currículo: ' . esc_html( $movefile['error'] ) );
    }
  } else {
    wp_die( 'Por favor, anexe o seu currículo antes de enviar.' );
  }
  // 6. Configurações de envio de E-mail
  $destinatario = $options['email_rh'] ?? ( $options['email_contato'] ?? '' );
  if ( empty( $destinatario ) ) {
    wp_die( 'Erro do Servidor: E-mail de destino do RH não configurado.' );
  }
  $assunto = "Trabalhe Conosco: $nome";

  // Deixei no formato padrão para caso você decida interceptar esse e-mail
  // com a classe do plugin e injetar a template HTML do recrutamento depois!
  $corpo  = "Nome: $nome\n";
  $corpo .= "Email: $email\n";
  $corpo .= "Telefone: $celular\n";
  $corpo .= "Cidade: $cidade\n";
  $corpo .= "Cargo: $cargo\n";
  $corpo .= "Situação CRECI: $creci\n";
  $corpo .= "Mensagem: $mensagem\n";
  $headers  = "From: $nome <$email>\r\n";
  $headers .= "Reply-To: $email\r\n";
  // 7. Dispara o E-mail com anexo
  $email_enviado = wp_mail( $destinatario, $assunto, $corpo, $headers, $attachment );
  if ( ! $email_enviado ) {
    wp_die( 'Falha no servidor ao enviar a mensagem. Tente novamente mais tarde.' );
  }
  // Opcional (Recomendado): Apagar o arquivo do servidor após enviar o e-mail para não lotar a hospedagem
  // if ( ! empty( $attachment[0] ) && file_exists( $attachment[0] ) ) {
  //	 unlink( $attachment[0] );
  // }
}
// 8. Envia as variáveis para a visualização (Template Part)
$dados_form = [
  'nome'		  => $nome,
  'email'		 => $email,
  'telefone'	  => $celular,
  'mensagem'	  => $mensagem,
  'creci'		 => $creci,
  'cidade'		=> $cidade,
  'cargo'		 => $cargo,
  'curriculo'	 => $nome_arquivo_curriculo,
  'email_enviado' => isset( $email_enviado ) ? $email_enviado : false,
  'chave_publica' => $chave_publica
];
get_template_part( 'template-parts/contato/trabalhe-conosco-template-form', 'imovel', $dados_form );
// 9. Gatilho do reCAPTCHA
if ( ! empty( $chave_publica ) && empty( $email_enviado ) ) : ?>
  <script>
    // Correção: O ID alvo agora é 'curriculo_form' (igual ao HTML da sua template)
    document.getElementById('curriculo_form').addEventListener('submit', function(e) {
      e.preventDefault();
      grecaptcha.ready(function() {
        grecaptcha.execute(PineduVars.chave_site, {action: 'submit'}).then(function(token) {
          document.getElementById('recaptcha_token').value = token;
          document.getElementById('curriculo_form').submit();
        });
      });
    });
  </script>
<?php endif; ?>
