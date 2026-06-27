<?php
/**
 * Classe responsável por gerenciar as requisições AJAX (non_priv) relacionadas
 * ao fluxo de "Cadastre seu imóvel" e formulários de contato com o corretor.
 * * @package Pinedu
 */
class Pinedu_Form_Cadastre {

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
  const HOOK_IDENTIFICACAO = 'CADASTREIDENTIFICACAO';
  const HOOK_LOCALIZACAO = 'CADASTRELOCALIZACAO';
  const HOOK_NEGOCIACAO = 'CADASTRENEGOCIACAO';
  const HOOK_CARACTERISTICAS = 'CADASTRECARACTERISTICAS';
  const HOOK_FOTOS = 'CADASTREFOTOS';
  const HOOK_ESTADOCHANGE = 'ESTADOGPBCHANGE';
  const HOOK_CIDADECHANGE = 'CIDADEGPBCHANGE';
  const HOOK_AUTOCOMPLETE = 'AUTOCOMPLETEGPBLOGRADOURO';
  const HOOK_AUTOCOMPLETECEP = 'AUTOCOMPLETEGPBCEP';
  const HOOK_CONTATOIMOVEL = 'CONTATOIMOVEL';
  const HOOK_CONTATOIMOVELCORRETOR = 'CONTATOIMOVELCORRETOR';
  const HOOK_CONTATOCORRETOR = 'CONTATOCORRETOR';

  /**
   * Inicializa a classe, registrando todas as ações AJAX no WordPress.
   * Este método deve ser chamado durante o carregamento do tema.
   * * @return void
   */
  public static function init( ) {
    add_action( self::PREFIXO . self::HOOK_IDENTIFICACAO, [ __CLASS__, 'carregar_tipo_imovel' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_LOCALIZACAO, [ __CLASS__, 'carregar_localizacao' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_NEGOCIACAO, [ __CLASS__, 'carregar_negociacao' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CARACTERISTICAS, [ __CLASS__, 'carregar_caracteristicas' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_FOTOS, [ __CLASS__, 'carregar_fotos' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_ESTADOCHANGE, [ __CLASS__, 'carregar_cidades' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CIDADECHANGE, [ __CLASS__, 'carregar_bairros' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_AUTOCOMPLETE, [ __CLASS__, 'auto_complete_logradouro' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_AUTOCOMPLETECEP, [ __CLASS__, 'auto_complete_cep' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CONTATOIMOVEL, [ __CLASS__, 'contato_imovel' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CONTATOIMOVELCORRETOR, [ __CLASS__, 'contato_imovel_corretor' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CONTATOCORRETOR, [ __CLASS__, 'contato_corretor' ], 99, 1 );
  }

  /**
   * Carrega o template (HTML) referente ao passo "Tipo de Imóvel" do cadastro.
   * * @param array|null $form_data Dados opcionais enviados pela requisição.
   * @return void
   */
  public static function carregar_tipo_imovel( $form_data ) {
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    ob_start( );
    get_template_part( 'template-parts/cadastre/tipo-imovel', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }

  /**
   * Carrega o template (HTML) referente ao passo "Localização" do cadastro.
   * * @param array|null $form_data Dados contendo as seleções prévias do usuário.
   * @return void
   */
  public static function carregar_localizacao( $form_data ) {
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    $tipo_imovel = isset( $dados[ 'tipo-imovel' ] ) ?? $dados[ 'tipo-imovel' ];
    set_query_var( 'tipoImovel', $tipo_imovel );
    ob_start( );
    get_template_part( 'template-parts/cadastre/localizacao', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }

  /**
   * Remove quebras de linha e espaços extras do HTML gerado antes de enviá-lo via JSON.
   * Isso economiza banda e previne erros de parse no front-end.
   * * @param string $html O HTML cru.
   * @return string O HTML minificado.
   */
  public static function clean_result( $html ) {
    return trim( preg_replace( array( '/\s{2,}/', '/( \r\n|\n|\r )/', '/\s*<\s*/', '/\s*>\s*/' ), array( ' ', '', '<', '>' ), $html ) );
  }

  /**
   * Carrega o template (HTML) referente ao passo "Negociação" do cadastro.
   * * @param array|null $form_data Dados enviados.
   * @return void
   */
  public static function carregar_negociacao( $form_data ) {
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    ob_start( );
    get_template_part( 'template-parts/cadastre/negociacao', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }

  /**
   * Carrega o template (HTML) referente ao passo "Características" do cadastro.
   * * @param array|null $form_data Dados enviados.
   * @return void
   */
  public static function carregar_caracteristicas( $form_data ) {
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    ob_start( );
    get_template_part( 'template-parts/cadastre/caracteristicas', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }

  /**
   * Carrega o template (HTML) referente ao passo "Fotos" do cadastro.
   * * @param array|null $form_data Dados enviados.
   * @return void
   */
  public static function carregar_fotos( $form_data ) {
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    ob_start( );
    get_template_part( 'template-parts/cadastre/fotos', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }

  /**
   * Busca a lista de cidades no servidor remoto com base no estado (UF) selecionado.
   * Utiliza a classe DoGet para fazer a requisição cURL.
   * * @param array|null $form_data Contém a chave 'estado'.
   * @return false Envia a resposta via wp_send_json_success.
   */
  public static function carregar_cidades( $form_data ) {
    require_once get_template_directory( ) . '/inc/classes/DoGet.php';
    $options = get_option( 'pinedu_imovel_options', [ ] );
    $server = $options[ 'url_servidor' ] ?? '';
    $token = $options[ 'token' ];
    $do = new DoGet( );
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    $estado = $dados[ 'estado' ];
    $args = [ 'estado' => $estado ];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'cidades', $args );
    wp_send_json_success( $data );
    return false;
  }

  /**
   * Busca a lista de bairros no servidor remoto com base na cidade selecionada.
   * * @param array|null $form_data Contém a chave 'cidade'.
   * @return false Envia a resposta via wp_send_json_success.
   */
  public static function carregar_bairros( $form_data ) {
    require_once get_template_directory( ) . '/inc/classes/DoGet.php';
    $options = get_option( 'pinedu_imovel_options', [ ] );
    $server = $options[ 'url_servidor' ] ?? '';
    $token = $options[ 'token' ];
    $do = new DoGet( );
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    $cidade = $dados[ 'cidade' ];
    $args = [ 'cidade' => $cidade ];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'bairros', $args );
    wp_send_json_success( $data );
    return false;
  }

  /**
   * Autocompleta o logradouro consultando a API do servidor baseada na digitação do usuário.
   * * @param array|null $form_data Contém 'cidade' e 'logradouro' (texto parcial).
   * @return false
   */
  public static function auto_complete_logradouro( $form_data ) {
    require_once get_template_directory( ) . '/inc/classes/DoGet.php';
    $options = get_option( 'pinedu_imovel_options', [ ] );
    $server = $options[ 'url_servidor' ] ?? '';
    $token = $options[ 'token' ];
    $do = new DoGet( );
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    $estado = sanitize_text_field( $dados[ 'estado' ] );
    $cidade = sanitize_text_field( $dados[ 'cidade' ] );
    $nome = sanitize_text_field( $dados[ 'logradouro' ] );
    $max = isset($dados['max']) ? intval($dados['max']) : 10;
    $args = [ 'estado' => $estado, 'cidade' => $cidade, 'nome' => $nome, 'max' => $max];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'autocompleteEndereco', $args );
    if ( !empty($data->success) && $data->success == true ) {
      wp_send_json_success( $data );
    } else {
      wp_send_json_error( [ 'message' => 'Nenhum logradouro encontrado' ] );
    }
  }
  public static function auto_complete_cep( $form_data ) {
    require_once get_template_directory( ) . '/inc/classes/DoGet.php';
    $options = get_option( 'pinedu_imovel_options', [ ] );
    $server = $options[ 'url_servidor' ] ?? '';
    $token = $options[ 'token' ];
    $do = new DoGet( );
    $form_data = null;
    $dados = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $dados );
    }
    $cep = sanitize_text_field( $dados[ 'cep' ] );
    $max = isset($dados['max']) ? intval($dados['max']) : 10;
    $args = [ 'cep' => $cep, 'max' => $max ];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'autocompleteCep', $args );
    if ( !empty($data->success) && $data->success == true ) {
      wp_send_json_success( $data );
    } else {
      wp_send_json_error( [ 'message' => 'Nenhum logradouro encontrado' ] );
    }
  }

  /**
   * Processa o envio do formulário de contato genérico para o corretor (captura de lead).
   * Atualiza os cookies do usuário em caso de sucesso.
   * * @return false
   */
  public static function contato_corretor( ) {
    $form_data = null;
    $args = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $args );
    }
    $referencia = $args['referencia'] ?? null;
    $corretor = $args['corretor'] ?? null;
    $mensagem = $args['mensagem'] ?? null;
    $nome = $args['nome'] ?? null;
    $telefone = $args['telefone'] ?? null;
    $email = $args['email'] ?? null;

    // Função global do tema para registrar o lead no CRM/Banco
    $data = enviarCliente( $nome, $telefone, $email, $mensagem, getCookieId( ), $referencia, $corretor );

    if ( true === $data[ 'success' ] ) {
      updateCookie( $nome, $telefone, $email );
      ob_start( );
      get_template_part( 'template-parts/corretor/contato-success', 'imovel', [ 'nome' => $nome, 'telefone' => $telefone, 'email' => $email, 'corretor' => $corretor, 'mensagem' => $mensagem, 'referencia' => $referencia ] );
      wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
    } else {
      ob_start( );
      get_template_part( 'template-parts/corretor/contato-error', 'imovel' );
      wp_send_json_error( self::clean_result( ob_get_clean( ) ) );
    }
    return false;
  }

  /**
   * Carrega dinamicamente o modal de formulário de contato específico de um corretor.
   * * @return void Interrompe a execução com wp_die().
   */
  public static function contato_imovel_corretor( ) {
    if ( isset( $_POST['codigoCorretor'] ) ) {
      $codigoCorretor = sanitize_text_field( $_POST['codigoCorretor'] );
      $nomeCorretor = sanitize_text_field( $_POST['nomeCorretor'] );
      $referencia = sanitize_text_field( $_POST['referencia'] );

      if ( is_development_mode( ) ) {
        error_log('Código do Corretor Recebido: ' . $codigoCorretor);
      }

      set_query_var( 'codigo-corretor', $codigoCorretor );
      set_query_var( 'nome-corretor', $nomeCorretor );
      set_query_var( 'referencia', $referencia );

      ob_start();
      include locate_template( 'template-parts/corretor/contato-corretor.php' );
      wp_send_json_success( \Pinedu_Form_Cadastre::clean_result( ob_get_clean( ) ) );
    } else {
      if ( is_development_mode( ) ) {
        error_log('Erro: Nenhum codigoCorretor foi enviado.');
      }
      wp_send_json_error( array( 'message' => 'Parâmetro codigoCorretor faltando.' ) );
    }
    wp_die();
  }

  /**
   * Processa o envio de contato de interesse direto em um imóvel específico.
   * * @param array|null $form_data Contém os dados do lead e a referência do imóvel.
   * @return false
   */
  public static function contato_imovel( $form_data ) {
    $form_data = null;
    $args = [];
    if ( isset( $_POST[ 'form_data' ] ) ) {
      $form_data = wp_unslash( $_POST[ 'form_data' ] );
    }
    if ( ! empty( $form_data ) ) {
      parse_str( $form_data, $args );
    }
    $mensagem = isset( $args[ 'mensagem' ] ) ?? $args[ 'mensagem' ];
    $referencia = isset( $args[ 'referencia' ] ) ?? $args[ 'referencia' ];

    // Envia os dados para a rotina de CRM
    $data = enviarCliente( $args[ 'nome' ], $args[ 'telefone' ], $args[ 'email' ], $mensagem, getCookieId( ), $referencia );

    if ( true === $data[ 'success' ] ) {
      ob_start( );
      get_template_part( 'template-parts/imovel/contato-success', 'imovel' );
      wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
    } else {
      ob_start( );
      get_template_part( 'template-parts/imovel/contato-error', 'imovel' );
      wp_send_json_error( self::clean_result( ob_get_clean( ) ) );
    }
    return false;
  }
}
