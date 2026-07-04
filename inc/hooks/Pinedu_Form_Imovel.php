<?php

class Pinedu_Form_Imovel {
  /**
   * Prefixo padrão para requisições AJAX de usuários não logados no WordPress.
   * @var string
   */
  const PREFIXO = 'wp_ajax_nopriv_';

  /**
   * Endpoint base da API do servidor imobiliário remoto.
   * @var string
   */
  const ENDPOINT = '/wordpress/';

  // --------------------------------------------------------------------------
  // Constantes de Nomes dos Hooks (Ações AJAX)
  // --------------------------------------------------------------------------
  const HOOK_CONTATOIMOVEL = 'CONTATOIMOVEL';
  const HOOK_SOLICITARVISITA = 'SOLICITARVISITA';

  /**
   * Inicializa a classe, registrando todas as ações AJAX no WordPress.
   * Este método deve ser chamado durante o carregamento do tema.
   * @return void
   */
  public static function init() {
    add_action( self::PREFIXO . self::HOOK_CONTATOIMOVEL, [__CLASS__, 'contato_imovel'] );
    add_action( self::PREFIXO . self::HOOK_SOLICITARVISITA, [__CLASS__, 'solicitar_visita'] );
  }
  /**
   * Extrai, sanitiza e valida os campos enviados pelo formulário.
   * Interrompe a requisição caso falte algum dado obrigatório.
   *
   * @return array Array associativo com todos os dados limpos
   */
  private static function extrair_validar_campos_contato() {
    $dados = array(
      'nome'       => isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '',
      'email'      => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
      'telefone'   => isset( $_POST['telefone'] ) ? sanitize_text_field( wp_unslash( $_POST['telefone'] ) ) : '',
      'mensagem'   => isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '',
      'referencia' => isset( $_POST['referencia'] ) ? sanitize_text_field( wp_unslash( $_POST['referencia'] ) ) : '',
      'corretor'   => isset( $_POST['corretor'] ) ? sanitize_text_field( wp_unslash( $_POST['corretor'] ) ) : '',
      'cookie_id'  => isset( $_POST['cookie_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_id'] ) ) : '',
      'post_id' => isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0
    );

    // Validações Básicas (Early Return)
    if ( empty( $dados['nome'] ) )     wp_send_json_error( 'Por favor, informe seu Nome para contato.' );
    if ( empty( $dados['email'] ) )    wp_send_json_error( 'Por favor, informe seu Email para contato.' );
    if ( empty( $dados['mensagem'] ) ) wp_send_json_error( 'Por favor, informe a Mensagem para contato.' );

    return $dados;
  }
  /**
   * Extrai, sanitiza e valida os campos enviados pelo formulário.
   * Interrompe a requisição caso falte algum dado obrigatório.
   *
   * @return array Array associativo com todos os dados limpos
   */
  private static function extrair_validar_campos_visita() {
    $dados = array(
      'nome'       => isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '',
      'email'      => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
      'telefone'   => isset( $_POST['telefone'] ) ? sanitize_text_field( wp_unslash( $_POST['telefone'] ) ) : '',
      'referencia' => isset( $_POST['referencia'] ) ? sanitize_text_field( wp_unslash( $_POST['referencia'] ) ) : '',
      'corretor'   => isset( $_POST['corretor'] ) ? sanitize_text_field( wp_unslash( $_POST['corretor'] ) ) : '',
      'cookie_id'  => isset( $_POST['cookie_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_id'] ) ) : '',
      'post_id' => isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0
    );

    // Validações Básicas (Early Return)
    if ( empty( $dados['nome'] ) )     wp_send_json_error( 'Por favor, informe seu Nome para contato.' );
    if ( empty( $dados['email'] ) )    wp_send_json_error( 'Por favor, informe seu Email para contato.' );
    if ( empty( $dados['referencia'] ) )    wp_send_json_error( 'Por favor, a referencia pra visitar.' );

    return $dados;
  }
  /**
   * Valida o método da requisição, o Nonce de segurança e o score do reCAPTCHA v3.
   * Interrompe a execução com wp_send_json_error() em caso de falha.
   *
   * @param string $nonce_action Ação esperada no nonce (ex: 'contato_imovel')
   * @param string $nonce_name   Nome do campo nonce vindo no $_POST (ex: 'contato_nonce')
   * @return void
   */
  private static function validar_seguranca_requisicao( $nonce_action, $nonce_name ) {
    // 1. Verifica se a requisição é um POST
    if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
      wp_send_json_error( 'Método inválido.' );
    }

    // 2. Validação do Nonce de Segurança
    if ( ! isset( $_POST[$nonce_name] ) || ! wp_verify_nonce( $_POST[$nonce_name], $nonce_action ) ) {
      wp_send_json_error( 'Erro de segurança: Validação expirada ou requisição inválida.' );
    }

    // 3. Resgate das chaves do reCAPTCHA
    $options       = get_option( 'pinedu_imovel_options', [] );
    $chave_publica = $options['chave_publica_recaptcha'] ?? '';
    $chave_secreta = $options['chave_secreta_recaptcha'] ?? '';

    if ( empty( $chave_publica ) || empty( $chave_secreta ) ) {
      wp_send_json_error( 'Preciso da chave recaptcha configurada no painel para prosseguir.' );
    }

    // 4. VERIFICAÇÃO DO TOKEN
    $recaptcha_token = isset( $_POST['recaptcha_token'] ) ? sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ) ) : '';
    if ( empty( $recaptcha_token ) ) {
      wp_send_json_error( 'Erro: Token de segurança ausente. Acesso negado.' );
    }

    // 5. VALIDAÇÃO NA API DO GOOGLE
    $url_google = "https://www.google.com/recaptcha/api/siteverify?secret={$chave_secreta}&response={$recaptcha_token}";
    $resposta   = wp_remote_get( $url_google );

    if ( is_wp_error( $resposta ) ) {
      wp_send_json_error( 'Erro ao comunicar com os servidores de validação de segurança.' );
    }

    $corpo_resposta = wp_remote_retrieve_body( $resposta );
    $retorno        = json_decode( $corpo_resposta );

    // Se a nota for menor que 0.5, bloqueia o bot
    if ( ! isset( $retorno->success ) || $retorno->success !== true || $retorno->score < 0.5 ) {
      wp_send_json_error( 'Erro de validação de segurança (reCAPTCHA reprovado). Acesso negado.' );
    }
  }
  /**
   * Processa a submissão do formulário de contato via AJAX.
   */
  public static function contato_imovel() {

    // ==========================================
    // 1. BLINDAGEM DE SEGURANÇA (Boilerplate Isolado)
    // ==========================================
    self::validar_seguranca_requisicao( 'contato_imovel', 'contato_nonce' );

    // ==========================================
    // 2. EXTRAÇÃO DE DADOS E ATUALIZAÇÃO DO COOKIE
    // ==========================================
    $dados = self::extrair_validar_campos_contato();

    // Atualiza o cookie com os dados preenchidos (Nome, Telefone e Email) e atualiza o ID
    if ( function_exists( 'updateCookie' ) ) {
      $novo_cookie_id = updateCookie( $dados['nome'], $dados['telefone'], $dados['email'] );
      if ( $novo_cookie_id ) {
        $dados['cookie_id'] = $novo_cookie_id;
      }
    }

    $options = get_option( 'pinedu_imovel_options', [] );

    // ==========================================
    // 3. LÓGICA DE MÚLTIPLOS DESTINATÁRIOS
    // ==========================================
    $destinatarios = array();
    $email_padrao  = $options['email_contato'] ?? '';

    // Adiciona o e-mail central da imobiliária (se existir)
    if ( ! empty( $email_padrao ) ) {
      $destinatarios[] = $email_padrao;
    }

    // Busca o e-mail do captador usando diretamente o ID do Imóvel (Muito mais rápido)
    if ( ! empty( $dados['post_id'] ) ) {

      // Resgata o email diretamente do ID fornecido, sem necessidade de WP_Query
      $email_imovel = get_post_meta( $dados['post_id'], 'email', true );

      // Se houver email do captador e não for repetido, adiciona à lista
      if ( ! empty( $email_imovel ) && ! in_array( $email_imovel, $destinatarios ) ) {
        if ( function_exists( 'is_development_mode' ) && is_development_mode() ) {
          $destinatarios[] = 'eduardopinhe@gmail.com';
        } else {
          $destinatarios[] = $email_imovel;
        }
      }
    }
    // Trava de segurança: se após a busca não houver NENHUM destinatário
    if ( empty( $destinatarios ) ) {
      wp_send_json_error( 'Sistema temporariamente indisponível (Erro interno de roteamento de e-mail).' );
    }

    // ==========================================
    // 4. CONSTRUÇÃO E DISPARO DO E-MAIL
    // ==========================================
    $assunto = "Interesse no Imóvel Ref: {$dados['referencia']} - Contato de {$dados['nome']}";

    $corpo_email  = "Novo contato originado a partir do site.\n\n";
    $corpo_email .= "Detalhes do Interessado:\n";
    $corpo_email .= "Nome: {$dados['nome']}\n";
    $corpo_email .= "Email: {$dados['email']}\n";
    $corpo_email .= "Telefone: " . ( function_exists('formata_telefone') ? formata_telefone( $dados['telefone'] ) : $dados['telefone'] ) . "\n\n";

    $corpo_email .= "Detalhes do Imóvel:\n";
    $corpo_email .= "Referência: {$dados['referencia']}\n";
    $corpo_email .= "Corretor Associado: {$dados['corretor']}\n\n";

    $corpo_email .= "Mensagem:\n{$dados['mensagem']}\n";

    // Cabeçalhos (Padrão RFC)
    $headers  = "From: {$dados['nome']} <{$dados['email']}>\r\n";
    $headers .= "Reply-To: {$dados['email']}\r\n";

    // Disparo - O wp_mail lida perfeitamente com um array no primeiro parâmetro
    $resultado_email = wp_mail( $destinatarios, $assunto, $corpo_email, $headers );

    // ==========================================
    // 5. RESPOSTA AO FRONTEND
    // ==========================================
    if ( $resultado_email ) {

      // Integração Customizada com o cookie atualizado
      if ( function_exists('enviarCliente') ) {
        enviarCliente( $dados['nome'], $dados['telefone'], $dados['email'], $dados['mensagem'], $dados['cookie_id'], $dados['referencia'], $dados['corretor'] );
      }

      ob_start( );
      get_template_part( 'template-parts/imovel/contato', 'success', $dados );
      wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
    } else {
      wp_send_json_error( 'Houve uma falha no servidor de disparo de e-mails. Tente novamente mais tarde.' );
    }
  }
  public static function clean_result( $html ) {
    return trim( preg_replace( array( '/\s{2,}/', '/( \r\n|\n|\r )/', '/\s*<\s*/', '/\s*>\s*/' ), array( ' ', '', '<', '>' ), $html ) );
  }

  public static function solicitar_visita() {

    // ==========================================
    // 2. EXTRAÇÃO DE DADOS E ATUALIZAÇÃO DO COOKIE
    // ==========================================
    $dados = self::extrair_validar_campos_visita();

    // Atualiza o cookie com os dados preenchidos (Nome, Telefone e Email) e atualiza o ID
    if ( function_exists( 'updateCookie' ) ) {
      $novo_cookie_id = updateCookie( $dados['nome'], $dados['telefone'], $dados['email'] );
      if ( $novo_cookie_id ) {
        $dados['cookie_id'] = $novo_cookie_id;
      }
    }
    $options = get_option( 'pinedu_imovel_options', [] );
    // ==========================================
    // 3. LÓGICA DE MÚLTIPLOS DESTINATÁRIOS
    // ==========================================
    $destinatarios = array();
    $email_padrao  = $options['email_contato'] ?? '';

    // Adiciona o e-mail central da imobiliária (se existir)
    if ( ! empty( $email_padrao ) ) {
      $destinatarios[] = $email_padrao;
    }

    // Busca o e-mail do captador usando diretamente o ID do Imóvel (Muito mais rápido)
    if ( ! empty( $dados['post_id'] ) ) {
      // Resgata o email diretamente do ID fornecido, sem necessidade de WP_Query
      $email_imovel = get_post_meta( $dados['post_id'], 'email', true );
      // Se houver email do captador e não for repetido, adiciona à lista
      if ( ! empty( $email_imovel ) && ! in_array( $email_imovel, $destinatarios ) ) {
        if ( function_exists( 'is_development_mode' ) && is_development_mode() ) {
          $destinatarios[] = 'eduardopinhe@gmail.com';
        } else {
          $destinatarios[] = $email_imovel;
        }
      }
    }
    // Trava de segurança: se após a busca não houver NENHUM destinatário
    if ( empty( $destinatarios ) ) {
      wp_send_json_error( 'Sistema temporariamente indisponível (Erro interno de roteamento de e-mail).' );
    }

    // ==========================================
    // 4. CONSTRUÇÃO E DISPARO DO E-MAIL
    // ==========================================
    $assunto = "Solicitação de Visita no Imóvel Ref: {$dados['referencia']} - Contato de {$dados['nome']}";

    $corpo_email  = "Solicitação de Visita originado a partir do site.\n\n";
    $corpo_email .= "Detalhes do Interessado:\n";
    $corpo_email .= "Nome: {$dados['nome']}\n";
    $corpo_email .= "Email: {$dados['email']}\n";
    $corpo_email .= "Telefone: " . ( function_exists('formata_telefone') ? formata_telefone( $dados['telefone'] ) : $dados['telefone'] ) . "\n\n";

    $corpo_email .= "Detalhes do Imóvel:\n";
    $corpo_email .= "Referência: {$dados['referencia']}\n";
    $corpo_email .= "Corretor Associado: {$dados['corretor']}\n\n";

    // Cabeçalhos (Padrão RFC)
    $headers  = "From: {$dados['nome']} <{$dados['email']}>\r\n";
    $headers .= "Reply-To: {$dados['email']}\r\n";

    // Disparo - O wp_mail lida perfeitamente com um array no primeiro parâmetro
    $resultado_email = wp_mail( $destinatarios, $assunto, $corpo_email, $headers );

    // ==========================================
    // 5. RESPOSTA AO FRONTEND
    // ==========================================
    if ( $resultado_email ) {
      // Integração Customizada com o cookie atualizado
      if ( function_exists('solicitarVisita') ) {
        solicitarVisita( $dados['nome'], $dados['telefone'], $dados['email'], $dados['mensagem']??'', $dados['cookie_id'], $dados['referencia'], $dados['corretor']??'' );
      }
      ob_start( );
      get_template_part( 'template-parts/imovel/visita', 'success', $dados );
      wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
    } else {
      wp_send_json_error( 'Houve uma falha no servidor de disparo de e-mails. Tente novamente mais tarde.' );
    }
  }
}
