<?php
class Pinedu_Form_Imovel {
  const PREFIXO = 'wp_ajax_nopriv_';
  const ENDPOINT = '/wordpress/';

  // --------------------------------------------------------------------------
  // Constantes de Nomes dos Hooks
  // --------------------------------------------------------------------------
  const HOOK_CONTATOIMOVEL = 'CONTATOIMOVEL';
  const HOOK_SOLICITARVISITA = 'SOLICITARVISITA';
  const HOOK_OPINARVISITA = 'OPINARVISITA';

  /**
   * Inicializa a classe, registrando as ações AJAX.
   */
  public static function init() {
    add_action( self::PREFIXO . self::HOOK_CONTATOIMOVEL, [__CLASS__, 'contato_imovel'] );
    add_action( self::PREFIXO . self::HOOK_SOLICITARVISITA, [__CLASS__, 'solicitar_visita'] );
    add_action( self::PREFIXO . self::HOOK_OPINARVISITA, [__CLASS__, 'opinar_visita'] );
    // --- NOVO HOOK DE LOG DE E-MAIL ---
    add_action( 'wp_mail_failed', [__CLASS__, 'log_wp_mail_error'] );
  }

  /**
   * Extratores de Dados Específicos
   */
  private static function extrair_validar_campos_contato() {
    $dados = self::extrator_base_formulario();
    if ( empty( $dados['nome'] ) )     wp_send_json_error( 'Por favor, informe seu Nome para contato. (COD: VAL-C01)' );
    if ( empty( $dados['email'] ) )    wp_send_json_error( 'Por favor, informe seu Email para contato. (COD: VAL-C02)' );
    if ( empty( $dados['mensagem'] ) ) wp_send_json_error( 'Por favor, informe a Mensagem para contato. (COD: VAL-C03)' );
    return $dados;
  }

  private static function extrair_validar_campos_visita() {
    $dados = self::extrator_base_formulario();
    if ( empty( $dados['nome'] ) )       wp_send_json_error( 'Por favor, informe seu Nome para contato. (COD: VAL-V01)' );
    if ( empty( $dados['email'] ) )      wp_send_json_error( 'Por favor, informe seu Email para contato. (COD: VAL-V02)' );
    if ( empty( $dados['referencia'] ) ) wp_send_json_error( 'Por favor, informe a referência para visita. (COD: VAL-V03)' );
    return $dados;
  }

  private static function extrair_validar_campos_opiniao() {
    $dados = self::extrator_base_formulario();
    if ( empty( $dados['mensagem'] ) ) wp_send_json_error( 'Por favor, escreva a sua avaliação sobre a visita. (COD: VAL-O01)' );
    return $dados;
  }

  // Helper DRY para evitar repetição do $_POST
  private static function extrator_base_formulario() {
    return array(
      'nome'       => isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '',
      'email'      => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
      'telefone'   => isset( $_POST['telefone'] ) ? sanitize_text_field( wp_unslash( $_POST['telefone'] ) ) : '',
      'mensagem'   => isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '',
      'referencia' => isset( $_POST['referencia'] ) ? sanitize_text_field( wp_unslash( $_POST['referencia'] ) ) : '',
      'corretor'   => isset( $_POST['corretor'] ) ? sanitize_text_field( wp_unslash( $_POST['corretor'] ) ) : '',
      'cookie_id'  => isset( $_POST['cookie_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_id'] ) ) : '',
      'post_id'    => isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0
    );
  }

  /**
   * Blindagem contra Bots
   */
  private static function validar_seguranca_requisicao( $nonce_action, $nonce_name ) {
    if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
      wp_send_json_error( 'Método inválido. (COD: SEC-01)' );
    }
    if ( ! isset( $_POST[$nonce_name] ) || ! wp_verify_nonce( $_POST[$nonce_name], $nonce_action ) ) {
      wp_send_json_error( 'Erro de segurança: Validação expirada ou requisição inválida. (COD: SEC-02)' );
    }

    $options       = get_option( 'pinedu_imovel_options', [] );
    $chave_publica = $options['chave_publica_recaptcha'] ?? '';
    $chave_secreta = $options['chave_secreta_recaptcha'] ?? '';

    if ( empty( $chave_publica ) || empty( $chave_secreta ) ) {
      wp_send_json_error( 'Preciso da chave recaptcha configurada no painel para prosseguir. (COD: SEC-03)' );
    }

    $recaptcha_token = isset( $_POST['recaptcha_token'] ) ? sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ) ) : '';
    if ( empty( $recaptcha_token ) ) {
      wp_send_json_error( 'Erro: Token de segurança ausente. Acesso negado. (COD: SEC-04)' );
    }

    $url_google = "https://www.google.com/recaptcha/api/siteverify?secret={$chave_secreta}&response={$recaptcha_token}";
    $resposta   = wp_remote_get( $url_google );

    if ( is_wp_error( $resposta ) ) {
      wp_send_json_error( 'Erro ao comunicar com os servidores de validação de segurança. (COD: SEC-05)' );
    }

    $corpo_resposta = wp_remote_retrieve_body( $resposta );
    $retorno        = json_decode( $corpo_resposta );

    if ( ! isset( $retorno->success ) || $retorno->success !== true || $retorno->score < 0.5 ) {
      wp_send_json_error( 'Erro de validação de segurança (reCAPTCHA reprovado). Acesso negado. (COD: SEC-06)' );
    }
  }

  // --------------------------------------------------------------------------
  // LÓGICA DE NEGÓCIO: 1. Contato Padrão
  // --------------------------------------------------------------------------
  public static function contato_imovel() {
    self::validar_seguranca_requisicao( 'contato_imovel', 'contato_nonce' );
    $dados = self::extrair_validar_campos_contato();

    if ( function_exists( 'updateCookie' ) ) {
      $novo_cookie_id = updateCookie( $dados['nome'], $dados['telefone'], $dados['email'] );
      if ( $novo_cookie_id ) $dados['cookie_id'] = $novo_cookie_id;
    }

    $nome_corretor = 'Não informado';
    if ( ! empty( $dados['corretor'] ) ) {
      $nome_corretor = self::obter_nome_corretor( $dados['corretor'] );
    }

    $destinatarios = self::obter_destinatarios( $dados['post_id'], $dados['corretor'] );

    $assunto = "Interesse no Imóvel Ref: {$dados['referencia']} - Contato de {$dados['nome']}";
    $corpo_email  = "Novo contato originado a partir do site.\n\n";
    $corpo_email .= "Detalhes do Interessado:\nNome: {$dados['nome']}\nEmail: {$dados['email']}\nTelefone: " . ( function_exists('formata_telefone') ? formata_telefone( $dados['telefone'] ) : $dados['telefone'] ) . "\n\n";
    $corpo_email .= "Detalhes do Imóvel:\nReferência: {$dados['referencia']}\nCorretor Associado: {$nome_corretor} (Cód: {$dados['corretor']})\n\n";
    $corpo_email .= "Mensagem:\n{$dados['mensagem']}\n";
    $headers = "From: {$dados['nome']} <{$dados['email']}>\r\nReply-To: {$dados['email']}\r\n";

    if ( wp_mail( $destinatarios, $assunto, $corpo_email, $headers ) ) {
      if ( function_exists('enviarCliente') ) {
        enviarCliente( $dados['nome'], $dados['telefone'], $dados['email'], $dados['mensagem'], $dados['cookie_id'], $dados['referencia'], $dados['corretor'] );
      }
      ob_start( );
      get_template_part( 'template-parts/imovel/contato', 'success', $dados );
      wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
    } else {
      wp_send_json_error( 'Houve uma falha no servidor de disparo de e-mails. Tente novamente mais tarde. (COD: MAIL-001)' );
    }
  }

  // --------------------------------------------------------------------------
  // LÓGICA DE NEGÓCIO: 2. Solicitar Visita
  // --------------------------------------------------------------------------
  public static function solicitar_visita() {
    $dados = self::extrair_validar_campos_visita();

    if ( function_exists( 'updateCookie' ) ) {
      $novo_cookie_id = updateCookie( $dados['nome'], $dados['telefone'], $dados['email'] );
      if ( $novo_cookie_id ) $dados['cookie_id'] = $novo_cookie_id;
    }

    $nome_corretor = 'Não informado';
    if ( ! empty( $dados['corretor'] ) ) {
      $nome_corretor = self::obter_nome_corretor( $dados['corretor'] );
    }

    $destinatarios = self::obter_destinatarios( $dados['post_id'], $dados['corretor'] );

    $assunto = "Solicitação de Visita no Imóvel Ref: {$dados['referencia']} - Contato de {$dados['nome']}";
    $corpo_email  = "Solicitação de Visita originada a partir do site.\n\n";
    $corpo_email .= "Detalhes do Interessado:\nNome: {$dados['nome']}\nEmail: {$dados['email']}\nTelefone: " . ( function_exists('formata_telefone') ? formata_telefone( $dados['telefone'] ) : $dados['telefone'] ) . "\n\n";
    $corpo_email .= "Detalhes do Imóvel:\nReferência: {$dados['referencia']}\nCorretor Associado: {$nome_corretor} (Cód: {$dados['corretor']})\n\n";
    $headers = "From: {$dados['nome']} <{$dados['email']}>\r\nReply-To: {$dados['email']}\r\n";

    if ( wp_mail( $destinatarios, $assunto, $corpo_email, $headers ) ) {
      if ( function_exists('solicitarVisita') ) {
        solicitarVisita( $dados['nome'], $dados['telefone'], $dados['email'], $dados['mensagem']??'', $dados['cookie_id'], $dados['referencia'], $dados['corretor']??'' );
      }
      $cookie_id_atual = !empty($dados['cookie_id']) ? $dados['cookie_id'] : ( class_exists('CookieUtil') ? CookieUtil::getCookieId() : '' );
      if ( ! empty( $dados['post_id'] ) && ! empty( $cookie_id_atual ) ) {
        $visitas_solicitadas = get_post_meta( $dados['post_id'], 'visita_solicitada', false );
        $ja_registrado = false;
        foreach ( $visitas_solicitadas as $registro ) {
          if ( is_array( $registro ) && isset( $registro['cookie'] ) && $registro['cookie'] === $cookie_id_atual ) {
            $ja_registrado = true; break;
          }
        }
        if ( ! $ja_registrado ) {
          add_post_meta( $dados['post_id'], 'visita_solicitada', [ 'cookie' => $cookie_id_atual, 'solicitada' => true ], false );
        }
      }
      ob_start( );
      get_template_part( 'template-parts/imovel/visita', 'success', $dados );
      wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
    } else {
      wp_send_json_error( 'Houve uma falha no servidor de disparo de e-mails. Tente novamente mais tarde. (COD: MAIL-002)' );
    }
  }

  // --------------------------------------------------------------------------
  // LÓGICA DE NEGÓCIO: 3. Feedback / Opinião da Visita
  // --------------------------------------------------------------------------
  public static function opinar_visita() {
    self::validar_seguranca_requisicao( 'opinar_visita', 'opinar_nonce' );
    $dados = self::extrair_validar_campos_opiniao();

    $nome_corretor = 'Não informado';
    if ( ! empty( $dados['corretor'] ) ) {
      $nome_corretor = self::obter_nome_corretor( $dados['corretor'] );
    }

    $destinatarios = self::obter_destinatarios( $dados['post_id'], $dados['corretor'] );

    $assunto = "Opinião da Visita - Ref: {$dados['referencia']} - De: {$dados['nome']}";
    $corpo_email  = "Um cliente deixou uma avaliação após visitar o imóvel.\n\n";
    $corpo_email .= "Detalhes do Cliente:\nNome: {$dados['nome']}\nEmail: {$dados['email']}\nTelefone: " . ( function_exists('formata_telefone') ? formata_telefone( $dados['telefone'] ) : $dados['telefone'] ) . "\n\n";
    $corpo_email .= "Detalhes do Imóvel:\nReferência: {$dados['referencia']}\nCorretor Associado: {$nome_corretor} (Cód: {$dados['corretor']})\n\n";
    $corpo_email .= "Avaliação do Cliente:\n{$dados['mensagem']}\n";
    $headers = "From: {$dados['nome']} <{$dados['email']}>\r\nReply-To: {$dados['email']}\r\n";

    if ( wp_mail( $destinatarios, $assunto, $corpo_email, $headers ) ) {
      if ( function_exists('opinarVisita') ) {
        opinarVisita( $dados['nome'], $dados['telefone'], $dados['email'], $dados['mensagem'], $dados['cookie_id'], $dados['referencia'], $dados['corretor'] );
      }
      wp_send_json_success( 'Sua avaliação foi enviada com sucesso e será repassada ao corretor responsável.' );
    } else {
      wp_send_json_error( 'Houve uma falha ao enviar sua avaliação. Tente novamente mais tarde. (COD: MAIL-003)' );
    }
  }

  /**
   * Recupera o nome do Corretor via SGBD a partir do seu código.
   * Retorna o nome formatado ou uma string de fallback caso não encontre.
   */
  private static function obter_nome_corretor( $codigo_corretor ) {
    global $wpdb;

    if ( empty( $codigo_corretor ) ) {
      return 'Não informado';
    }

    // Procura o meta 'nome' ou 'pessoa_nome' cruzando com o código do corretor
    $query = $wpdb->prepare("
      SELECT nome_meta.meta_value
      FROM {$wpdb->postmeta} AS codigo_meta
      INNER JOIN {$wpdb->posts} AS p
        ON p.ID = codigo_meta.post_id
      INNER JOIN {$wpdb->postmeta} AS nome_meta
        ON p.ID = nome_meta.post_id
      WHERE p.post_type = 'corretor'
        AND p.post_status = 'publish'
        AND codigo_meta.meta_key = 'codigo'
        AND codigo_meta.meta_value = %s
        AND nome_meta.meta_key IN ('nome', 'pessoa_nome')
      LIMIT 1
    ", $codigo_corretor );

    $nome = $wpdb->get_var( $query );

    return ! empty( $nome ) ? trim( $nome ) : null;
  }

  /**
   * Responsável EXCLUSIVO por consultar os emails do Corretor no SGBD.
   * Retorna sempre um array limpo apenas com emails válidos.
   */
  private static function obter_emails_corretor( $codigo_corretor ) {
    global $wpdb;
    $emails_validos = array();

    if ( empty( $codigo_corretor ) ) {
      return $emails_validos;
    }

    $query = $wpdb->prepare("
      SELECT email_meta.meta_value
      FROM {$wpdb->postmeta} AS codigo_meta
      INNER JOIN {$wpdb->posts} AS p
        ON p.ID = codigo_meta.post_id
      INNER JOIN {$wpdb->postmeta} AS email_meta
        ON p.ID = email_meta.post_id
      WHERE p.post_type = 'corretor'
        AND p.post_status = 'publish'
        AND codigo_meta.meta_key = 'codigo'
        AND codigo_meta.meta_value = %s
        AND email_meta.meta_key = 'email'
    ", $codigo_corretor );

    $emails = $wpdb->get_col( $query );

    if ( ! empty( $emails ) && is_array( $emails ) ) {
      foreach ( $emails as $email ) {
        $email = trim( $email );
        // A validação fica aqui, o orquestrador não precisa de saber disto
        if ( is_email( $email ) ) {
          $emails_validos[] = $email;
        }
      }
    }

    return $emails_validos;
  }

  /**
   * Helper DRY para adicionar um email ao array sem repetição e gerindo o ambiente de desenvolvimento.
   */
  private static function adicionar_destinatario( &$destinatarios, $email ) {
    if ( empty( $email ) || ! is_email( $email ) ) {
      return;
    }

    $email_final = $email;

    // Se estiver em modo de desenvolvimento, redireciona o email
    if ( function_exists( 'is_development_mode' ) && is_development_mode() ) {
      $email_final = 'eduardopinhe@gmail.com';
    }

    // Se ainda não estiver na lista, adicionamos (passado por referência com &$destinatarios)
    if ( ! in_array( $email_final, $destinatarios ) ) {
      $destinatarios[] = $email_final;
    }
  }

  /**
   * Helper para montar destinatários de forma limpa.
   */
  private static function obter_destinatarios( $post_id, $codigo_corretor = null ) {
    $destinatarios = array();
    $options = get_option( 'pinedu_imovel_options', [] );

    // 1. Email Padrão de Contato
    if ( ! empty( $options['email_contato'] ) ) {
      self::adicionar_destinatario( $destinatarios, $options['email_contato'] );
    }

    // 2. Emails do Corretor associado
    $emails_corretor = self::obter_emails_corretor( $codigo_corretor );
    foreach ( $emails_corretor as $email ) {
      self::adicionar_destinatario( $destinatarios, $email );
    }

    // 4. Trava de segurança final
    if ( empty( $destinatarios ) ) {
      wp_send_json_error( 'Sistema temporariamente indisponível (Erro interno de roteamento). (COD: ROUTE-001)' );
    }

    return $destinatarios;
  }

  public static function clean_result( $html ) {
    return trim( preg_replace( array( '/\s{2,}/', '/( \r\n|\n|\r )/', '/\s*<\s*/', '/\s*>\s*/' ), array( ' ', '', '<', '>' ), $html ) );
  }

  public static function ja_solicitou_visita( $post_id, $cookie_id ) {
    global $wpdb;
    if ( empty( $post_id ) || empty( $cookie_id ) ) return false;
    $busca_serializada = '%' . $wpdb->esc_like( $cookie_id ) . '%';
    $query = $wpdb->prepare(
      "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = 'visita_solicitada' AND meta_value LIKE %s",
      $post_id, $busca_serializada
    );
    return ( $wpdb->get_var( $query ) > 0 );
  }
  /**
   * Captura e registra falhas do wp_mail no error_log do servidor.
   * Utiliza o hook 'wp_mail_failed' do WordPress.
   */
  public static function log_wp_mail_error( $wp_error ) {
    // Verifica se realmente é um objeto WP_Error
    if ( is_wp_error( $wp_error ) ) {

      // Extrai a mensagem principal do PHPMailer
      $error_message = $wp_error->get_error_message();

      // Opcional: extrai dados detalhados (como headers que falharam, destinatários, etc)
      $error_data = $wp_error->get_error_data( 'wp_mail_failed' );

      $log_entry  = "\n=============================================\n";
      $log_entry .= "[PINEDU_MAIL_ERROR] Falha no disparo de e-mail\n";
      $log_entry .= "Mensagem: " . $error_message . "\n";

      if ( ! empty( $error_data ) ) {
        // Se houver dados como 'to', 'subject' e 'headers', printamos para ajudar na depuração
        $log_entry .= "Dados da Requisição: " . print_r( $error_data, true ) . "\n";
      }

      $log_entry .= "=============================================\n";

      // Escreve no log nativo do PHP/Servidor
      error_log( $log_entry );
    }
  }
}
