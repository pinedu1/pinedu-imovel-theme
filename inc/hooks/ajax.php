<?php
/**
 * Gerencia AJAX non_priv
 */
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
/**
 * Gerencia AJAX non_priv Cadastre seu imóvel
 */
class Pinedu_Form_Cadastre {
// phpcs:disable WordPress.Security.NonceVerification.Missing
  const PREFIXO = 'wp_ajax_nopriv_';
  const ENDPOINT = '/wordpress/';
  const HOOK_IDENTIFICACAO = 'CADASTREIDENTIFICACAO';
  const HOOK_LOCALIZACAO = 'CADASTRELOCALIZACAO';
  const HOOK_NEGOCIACAO = 'CADASTRENEGOCIACAO';
  const HOOK_CARACTERISTICAS = 'CADASTRECARACTERISTICAS';
  const HOOK_FOTOS = 'CADASTREFOTOS';
  const HOOK_ESTADOCHANGE = 'ESTADOGPBCHANGE';
  const HOOK_CIDADECHANGE = 'CIDADEGPBCHANGE';
  const HOOK_AUTOCOMPLETE = 'AUTOCOMPLETEGPBLOGRADOURO';
  const HOOK_CONTATOIMOVEL = 'CONTATOIMOVEL';
  const HOOK_CONTATOIMOVELCORRETOR = 'CONTATOIMOVELCORRETOR';
  const HOOK_CONTATOCORRETOR = 'CONTATOCORRETOR';
  public static function init( ) {
    add_action( self::PREFIXO . self::HOOK_IDENTIFICACAO, [ __CLASS__, 'carregar_tipo_imovel' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_LOCALIZACAO, [ __CLASS__, 'carregar_localizacao' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_NEGOCIACAO, [ __CLASS__, 'carregar_negociacao' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CARACTERISTICAS, [ __CLASS__, 'carregar_caracteristicas' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_FOTOS, [ __CLASS__, 'carregar_fotos' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_ESTADOCHANGE, [ __CLASS__, 'carregar_cidades' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CIDADECHANGE, [ __CLASS__, 'carregar_bairros' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_AUTOCOMPLETE, [ __CLASS__, 'auto_complete_logradouro' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CONTATOIMOVEL, [ __CLASS__, 'contato_imovel' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CONTATOIMOVELCORRETOR, [ __CLASS__, 'contato_imovel_corretor' ], 99, 1 );
    add_action( self::PREFIXO . self::HOOK_CONTATOCORRETOR, [ __CLASS__, 'contato_corretor' ], 99, 1 );
  }
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
  public static function clean_result( $html ) {
    return trim( preg_replace( array( '/\s{2,}/', '/( \r\n|\n|\r )/', '/\s*<\s*/', '/\s*>\s*/' ), array( ' ', '', '<', '>' ), $html ) );
  }
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
    $cidade = $dados[ 'cidade' ];
    $nome = $dados[ 'logradouro' ];
    $args = [ 'cidade' => $cidade, 'nome' => $nome ];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'autocompleteEndereco', $args );
    wp_send_json_success( $data );
    return false;
  }
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
      include locate_template(  'template-parts/corretor/contato-corretor.php' );;
      wp_send_json_success( \Pinedu_Form_Cadastre::clean_result( ob_get_clean( ) ) );
    } else {
      if ( is_development_mode( ) ) {
        error_log('Erro: Nenhum codigoCorretor foi enviado.');
      }
      wp_send_json_error( array( 'message' => 'Parâmetro codigoCorretor faltando.' ) );
    }
    wp_die();
  }
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
/**
 * Gerencia AJAX non_priv Form de combos para pesquisa
 */
class Pinedu_Form_Pesquisa {
  const NOME_COOKIE = 'PND_VISITANTE';
  const DIAS_VALIDADE_COOKIE = 90;
  const PREFIXOS = [ 'wp_ajax_nopriv_', 'wp_ajax_' ];
  const HOOK_CONTRATO = 'CONTRATOCHANGE';
  const HOOK_TIPOIMOVEL = 'TIPOIMOVELCHANGE';
  const HOOK_CIDADE = 'CIDADECHANGE';
  const HOOK_REGIAO = 'REGIAOCHANGE';
  const HOOK_CRIAR_COOKIE = 'CRIARCOOKIE';
  const HOOK_PAGINAR_PROMOCAO = 'PAGINARPROMOCAO';
  const HOOK_PAGINAR_VISITADOS = 'PAGINARVISITADOS';
  const HOOK_PAGINAR_VISINHANCA = 'PAGINARVISINHANCA';
  const HOOK_PAGINAR_PESQUISA = 'PAGINARPESQUISA';
  const HOOK_FAIXAVALOR = 'RECUPERAFAIXAVALOR';
  // Método estático para inicialização
  public static function init( ) {
    foreach ( self::PREFIXOS as $prefixo ) {
      add_action( $prefixo . self::HOOK_CONTRATO, [ __CLASS__, 'contrato_change' ], 99, 1 );
      add_action( $prefixo . self::HOOK_TIPOIMOVEL, [ __CLASS__, 'tipo_imovel_change' ], 99, 1 );
      add_action( $prefixo . self::HOOK_CIDADE, [ __CLASS__, 'cidade_change' ], 99, 1 );
      add_action( $prefixo . self::HOOK_REGIAO, [ __CLASS__, 'regiao_change' ], 99, 1 );
      add_action( $prefixo . self::HOOK_CRIAR_COOKIE, [ __CLASS__, 'criar_cookie' ] );
      add_action( $prefixo . self::HOOK_PAGINAR_PROMOCAO, [ __CLASS__, 'paginar_promocao' ] );
      add_action( $prefixo . self::HOOK_PAGINAR_VISITADOS, [ __CLASS__, 'paginar_visitados' ] );
      add_action( $prefixo . self::HOOK_PAGINAR_VISINHANCA, [ __CLASS__, 'paginar_visinhanca' ] );
      add_action( $prefixo . self::HOOK_PAGINAR_PESQUISA, [ __CLASS__, 'paginar_pesquisa' ] );
      add_action( $prefixo . self::HOOK_FAIXAVALOR, [ __CLASS__, 'recupera_faixavalor' ] );
    }
  }
  // Métodos estáticos
  public static function paginar_promocao( ) {
    require_once get_template_directory( ) . '/inc/classes/Promocoes.php';
    $contrato = isset( $_REQUEST[ 'contrato' ] ) ? sanitize_text_field( $_REQUEST[ 'contrato' ] ) : '';
    $paged = isset( $_REQUEST[ 'paged' ] ) ? sanitize_text_field( $_REQUEST[ 'paged' ] ) : '';
    $max = isset( $_REQUEST[ 'max' ] ) ? sanitize_text_field( $_REQUEST[ 'max' ] ) : 8;
    $card = isset( $_REQUEST[ 'card' ] ) ? sanitize_text_field( $_REQUEST[ 'card' ] ) : null;
    $template = isset( $_REQUEST[ 'template' ] ) ? sanitize_text_field( $_REQUEST[ 'template' ] ) : null;
    $tipo_imovel = isset( $_REQUEST[ 'tipo_imovel' ] ) ? sanitize_text_field( $_REQUEST[ 'tipo_imovel' ] ) : null;
    /* Atribui o numero da página para simular o comportamento normal */
    set_query_var( 'paged', $paged );
    set_query_var( 'tipo', $tipo_imovel );

    if ( $template && !empty( $template ) ) {
      set_query_var( 'template', $template );
    }
    if ( $card && !empty( $card ) ) {
      set_query_var( 'card', $card );
    }
    Promocoes::paginar_promocao( $contrato, $tipo_imovel, $max );
  }
  public static function paginar_pesquisa( ) {
    require_once get_template_directory( ) . '/inc/classes/Pesquisa.php';
    Pesquisa::paginar_pesquisa( );
  }
  public static function paginar_visitados( ) {
    require_once get_template_directory( ) . '/inc/classes/Visitados.php';
    $paged = isset( $_REQUEST[ 'paged' ] ) ? sanitize_text_field( $_REQUEST[ 'paged' ] ) : '';
    $max = isset( $_REQUEST[ 'max' ] ) ? sanitize_text_field( $_REQUEST[ 'max' ] ) : 8;
    $titulo = isset( $_REQUEST[ 'titulo' ] ) ? sanitize_text_field( $_REQUEST[ 'titulo' ] ) : 'Imóveis mais visitados';
    set_query_var( 'paged', $paged );
    Visitados::paginar_visitados( $titulo, $max );
  }
  public static function paginar_visinhanca( ) {
    require_once get_template_directory( ) . '/inc/classes/Visinhanca.php';
    $paged = isset( $_REQUEST[ 'paged' ] ) ? sanitize_text_field( $_REQUEST[ 'paged' ] ) : '';
    $max = isset( $_REQUEST[ 'max' ] ) ? sanitize_text_field( $_REQUEST[ 'max' ] ) : 8;
    $titulo = isset( $_REQUEST[ 'titulo' ] ) ? sanitize_text_field( $_REQUEST[ 'titulo' ] ) : 'Imóveis Próximos';
    $post_id = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : 0;
    // 2. Verifica se um ID válido (maior que zero) foi passado
    if ( $post_id > 0 ) {
      set_query_var( 'paged', $paged );
      Visinhanca::paginar_visinhanca( $post_id, $titulo, $max );
    }
  }
  private static function get_tipo_imoveis_terms( ) {
    $args = array(
      'taxonomy'   => 'tipo-imovel',
      'hide_empty' => true,
      'orderby'    => 'name',
      'order'      => 'ASC'
    );

    return get_terms( $args );
  }
  private static function get_cidades_terms( ) {
    $args = array(
      'taxonomy'   => 'cidade',
      'hide_empty' => true,
      'orderby'    => 'name',
      'order'      => 'ASC'
    );

    return get_terms( $args );
  }
  private static function get_regioes_terms( $cidade = null ) {
    $args = array(
      'taxonomy' => 'regiao'
    , 'hide_empty' => false
    , 'orderby' => 'name'
    , 'order' => 'ASC'
    , 'meta_query' => [
        [
          'key' => 'parent_id'
          , 'value' => $cidade
          , 'compare' => '='
        ]
      ]
    );

    return get_terms( $args );
  }
  public static function contrato_change() {
    $contrato = isset( $_REQUEST['contrato'] ) ? sanitize_text_field( $_REQUEST['contrato'] ) : '';
    $tipo_imovel = isset( $_REQUEST['tipo-imovel'] ) ? sanitize_text_field( $_REQUEST['tipo-imovel'] ) : '';
    $cidade = isset( $_REQUEST['cidade'] ) ? sanitize_text_field( $_REQUEST['cidade'] ) : '';
    $regiao = isset( $_REQUEST['regiao'] ) ? sanitize_text_field( $_REQUEST['regiao'] ) : '';

    $tipo_imovel_padrao = $tipo_imovel;
    $cidade_padrao = $cidade;
    $regiao_padrao = $regiao;
    $result = array(
      'tipo-imoveis'  => array(),
      'cidades'  => array(),
      'regioes'  => array(),
      'faixa-valores' => array(),
    );

    $terms_tipo_imovel = self::get_tipo_imoveis_terms();
    $terms_cidade = self::get_cidades_terms();

    $cidade_padrao = str_pad($cidade_padrao, 4, '0', STR_PAD_LEFT);
    if ( ! empty( $terms_cidade ) && ! is_wp_error( $terms_cidade ) ) {
      foreach ($terms_cidade as $cid) {
        $opt = ['id' => $cid->slug, 'nome' => $cid->name];
        if ($cidade_padrao && ($cid->slug == $cidade_padrao)) {
          $opt['selected'] = true;
        }
        $result['cidades'][] = $opt;
      }
    }
    if ( !empty( $cidade ) ) {
      $terms_regiao = self::get_regioes_terms($cidade_padrao);
      $regiao_padrao = str_pad($regiao_padrao, 4, '0', STR_PAD_LEFT);
      if (!empty($terms_regiao) && !is_wp_error($terms_regiao)) {
        foreach ($terms_regiao as $reg) {
          if ($regiao_padrao && ($reg->slug == $regiao_padrao)) {
            $opt['selected'] = true;
          }
          $result['regioes'][] = $opt;
        }
      }
    }

    if ( ! empty( $terms_tipo_imovel ) && ! is_wp_error( $terms_tipo_imovel ) ) {
      foreach ( $terms_tipo_imovel as $tipo_imovel ) {
        $opt = [ 'id' => $tipo_imovel->slug, 'nome' => $tipo_imovel->name ];
        if ( $tipo_imovel_padrao && ( strtolower( $tipo_imovel->slug ) == strtolower( $tipo_imovel_padrao ) ) ) {
          $opt['selected'] = true;
        }
        $result['tipo-imoveis'][] = $opt;
      }
    }

    // Calcula as faixas baseadas no banco de dados
    $fx = self::calcula_faixa_valor( $contrato );

    // CORREÇÃO APLICADA: Atribuição direta, sem push []
    $result['faixa-valores'] = $fx['faixas'];
    $result['valor-inicial'] = $fx['pivot_menor'];
    $result['valor-final'] = $fx['pivot_maior'];

    if ( ( isset( $result['tipo-imoveis'] ) && ! empty( $result['tipo-imoveis'] ) ) || ( isset( $result['faixa-valores'] ) && ! empty( $result['faixa-valores'] ) ) ) {
      wp_send_json_success( [
        'message' => 'Processado com sucesso!',
        'data'    => $result
      ], 200 );
    } else {
      wp_send_json_success( [
        'message' => 'Processado com sucesso!',
        'data'    => $result
      ], 200 );
    }
  }
  public static function recupera_faixavalor( ) {
    $contrato = isset( $_REQUEST['contrato'] ) ? sanitize_text_field( $_REQUEST['contrato'] ) : '';
    $fx = self::calcula_faixa_valor( $contrato );
    $result = [
      'faixa-valores' => $fx['faixas'],
      'valor-inicial' => $fx['pivot_menor'],
      'valor-final'   => $fx['pivot_maior']
    ];
    wp_send_json_success( $result );
  }
// ==============================================================================
// 2. O CÁLCULO ESTATÍSTICO (COM CACHE TRANSIENT)
// ==============================================================================
  public static function calcula_faixa_valor( $contrato ) {
    delete_transient( 'pinedu_faixas_contrato_1' );
    delete_transient( 'pinedu_faixas_contrato_2' );
    delete_transient( 'pinedu_faixas_contrato_3' );
    delete_transient( 'pinedu_faixas_contrato_todos' );
    // 1. Tenta carregar do Cache primeiro (Protege o Banco de Dados)
    $chave_cache = 'pinedu_faixas_contrato_' . ( empty($contrato) ? 'todos' : $contrato );
    $dados_cacheados = get_transient( $chave_cache );

    if ( false !== $dados_cacheados ) {
      return $dados_cacheados;
    }

    // 2. Se não tem cache, processa o SQL
    global $wpdb;

    if ( $contrato == '1' ) {
      $meta_key_sql = "pm_valor.meta_key = 'vendaValor'";
      $extra_join = "INNER JOIN {$wpdb->postmeta} pm_ativarVenda ON p.ID = pm_ativarVenda.post_id";
      $extra_where = "AND pm_ativarVenda.meta_key = 'ativarVenda' AND pm_ativarVenda.meta_value = '1'";
    } elseif ( $contrato == '2' ) {
      $meta_key_sql = "pm_valor.meta_key = 'locacaoValor'";
      $extra_join = "INNER JOIN {$wpdb->postmeta} pm_ativarLocacao ON p.ID = pm_ativarLocacao.post_id";
      $extra_where = "AND pm_ativarLocacao.meta_key = 'ativarLocacao' AND pm_ativarLocacao.meta_value = '1'";
    } elseif ( $contrato == '3' ) {
      $meta_key_sql = "pm_valor.meta_key = 'lancamentoValor'";
      $extra_join = "INNER JOIN {$wpdb->postmeta} pm_ativarLancamento ON p.ID = pm_ativarLancamento.post_id";
      $extra_where = "AND pm_ativarLancamento.meta_key = 'ativarLancamento' AND pm_ativarLancamento.meta_value = '1'";
    } else {
      $meta_key_sql = "pm_valor.meta_key IN ('vendaValor', 'locacaoValor', 'lancamentoValor')";
    }

    // Query: Extrai valores limpos e ordenados.
    // O Mínimo será o índice 0 e o Máximo será o último índice.
    $query = "
    SELECT CAST(REPLACE(REPLACE(pm_valor.meta_value, '.', ''), ',', '.') AS DECIMAL(15,2)) AS valor
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm_valor ON p.ID = pm_valor.post_id
    INNER JOIN {$wpdb->postmeta} pm_status ON p.ID = pm_status.post_id
    {$extra_join}
    WHERE p.post_type = 'imovel'
      AND p.post_status = 'publish'
      AND {$meta_key_sql}
      AND pm_valor.meta_value != ''
      AND pm_status.meta_key = 'statusImovel'
      AND pm_status.meta_value = 'D'
      {$extra_where}
    ORDER BY valor ASC
";

    $valores_ordenados = $wpdb->get_col($query);
    //error_log('Valores ordenados: ' . print_r($valores_ordenados, true));
    $qtd_itens = 25;

    // Trava de Segurança: Banco vazio ou sem imóveis no status D
    if ( empty($valores_ordenados) ) {
      $resultado = [
        'faixas'      => array_fill(0, $qtd_itens, 0),
        'pivot_menor' => 0,
        'pivot_maior' => 0
      ];
      set_transient( $chave_cache, $resultado, 12 * HOUR_IN_SECONDS );
      return $resultado;
    }
    //error_log('Valores ordenados: ' . print_r($valores_ordenados, true));
    $total_imoveis = count($valores_ordenados);

    // 3. Extração de Extremos e Quartis Reais
    $min_real     = (float) $valores_ordenados[0]; // EQUIVALE AO MIN() DO SQL
    $q1_real      = (float) $valores_ordenados[(int) floor($total_imoveis * 0.25)];
    $mediana_real = (float) $valores_ordenados[(int) floor($total_imoveis * 0.50)];
    $q3_real      = (float) $valores_ordenados[(int) floor($total_imoveis * 0.75)];
    $max_real     = (float) $valores_ordenados[$total_imoveis - 1]; // EQUIVALE AO MAX() DO SQL

    // Função auxiliar para arredondar para a centena mais próxima
    $arredondar_centena = function($valor) {
      return round($valor / 100) * 100;
    };

    // Regra Exigida: 15% a menos no Mínimo e 15% a mais no Máximo, arredondados para a centena
    $novo_min = $arredondar_centena($min_real * 0.85);
    $novo_max = $arredondar_centena($max_real * 1.15);

    $faixas = [];

    if ($novo_max > $novo_min) {

      // CRAVADO O PRIMEIRO ITEM (Índice 0): min * 15% de margem
      $faixas[0] = (int) $novo_min;

      // Segmento 1: do Mínimo Ajustado até o Quartil 1 (Índices 1 a 6)
      $step1 = ($q1_real - $novo_min) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[$i] = (int) $arredondar_centena($novo_min + ($step1 * $i)); }
      $faixas[6] = (int) $arredondar_centena($q1_real); // Crava o Q1

      // Segmento 2: do Quartil 1 até a Mediana (Índices 7 a 12)
      $step2 = ($mediana_real - $q1_real) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[6 + $i] = (int) $arredondar_centena($q1_real + ($step2 * $i)); }
      $faixas[12] = (int) $arredondar_centena($mediana_real); // Crava a Mediana no Pivot Central

      // Segmento 3: da Mediana até o Quartil 3 (Índices 13 a 18)
      $step3 = ($q3_real - $mediana_real) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[12 + $i] = (int) $arredondar_centena($mediana_real + ($step3 * $i)); }
      $faixas[18] = (int) $arredondar_centena($q3_real); // Crava o Q3

      // Segmento 4: do Quartil 3 até o Máximo Ajustado (Índices 19 a 24)
      $step4 = ($novo_max - $q3_real) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[18 + $i] = (int) $arredondar_centena($q3_real + ($step4 * $i)); }

      // CRAVADO O ÚLTIMO ITEM (Índice 24): max + 15% de margem
      $faixas[24] = (int) $novo_max;

      // 4. FIXA OS PIVOTS DA INTERFACE:
      // A Mediana está no índice 12. Pivots ficam em 11 e 13 para contorná-la.
      $idx_meio = 12;
      $idx_menor = 10;
      $idx_maior = 14;

      $pivot_menor = $faixas[$idx_menor];
      $pivot_maior = $faixas[$idx_maior];

    } else {
      // Fallback se min e max forem iguais (apenas 1 imóvel)
      $valor_inteiro = (int) $arredondar_centena($novo_min);
      $faixas = array_fill(0, $qtd_itens, $valor_inteiro);
      $pivot_menor = $valor_inteiro;
      $pivot_maior = $valor_inteiro;
    }

    $resultado = [
      'faixas'      => $faixas,
      'pivot_menor' => $pivot_menor,
      'pivot_maior' => $pivot_maior
    ];

    // 5. Salva no Cache por 12 horas
    set_transient( $chave_cache, $resultado, 12 * HOUR_IN_SECONDS );

    return $resultado;
  }
  public static function tipo_imovel_change( ) {
    $tipo_imovel = isset( $_REQUEST['tipo-imovel'] ) ? sanitize_text_field( $_REQUEST['tipo-imovel'] ) : '';
    $cidade = isset( $_REQUEST['cidade'] ) ? sanitize_text_field( $_REQUEST['cidade'] ) : '';
    $regiao = isset( $_REQUEST['regiao'] ) ? sanitize_text_field( $_REQUEST['regiao'] ) : '';

    $tipo_imovel_padrao = $tipo_imovel;
    $cidade_padrao = $cidade;
    $regiao_padrao = $regiao;
    $result = array(
      'cidades'  => array(),
      'regioes'  => array(),
    );

    $terms_cidade = self::get_cidades_terms();
    $cidade_padrao = str_pad($cidade_padrao, 4, '0', STR_PAD_LEFT);
    if ( ! empty( $terms_cidade ) && ! is_wp_error( $terms_cidade ) ) {
      foreach ($terms_cidade as $cid) {
        $opt = ['id' => $cid->slug, 'nome' => $cid->name];
        if ($cidade_padrao && ($cid->slug == $cidade_padrao)) {
          $opt['selected'] = true;
        }
        $result['cidades'][] = $opt;
      }
    }
    if ( !empty( $cidade ) ) {
      $terms_regiao = self::get_regioes_terms($cidade_padrao);
      $regiao_padrao = str_pad($regiao_padrao, 4, '0', STR_PAD_LEFT);
      if (!empty($terms_regiao) && !is_wp_error($terms_regiao)) {
        foreach ($terms_regiao as $reg) {
          $opt = ['id' => $reg->slug, 'nome' => $reg->name];
          if ($regiao_padrao && ($reg->slug == $regiao_padrao)) {
            $opt['selected'] = true;
          }
          $result['regioes'][] = $opt;
        }
      }
    }
    wp_send_json_success( [
      'message' => 'Processado com sucesso!',
      'data'    => $result
    ], 200 );
  }
  public static function cidade_change(  ) {
    $cidade = isset( $_REQUEST['cidade'] ) ? sanitize_text_field( $_REQUEST['cidade'] ) : '';
    $regiao = isset( $_REQUEST['regiao'] ) ? sanitize_text_field( $_REQUEST['regiao'] ) : '';

    $cidade_padrao = $cidade;
    $regiao_padrao = $regiao;
    $result = array(
      'regioes'  => array(),
    );

    if ( !empty( $cidade ) ) {
      $terms_regiao = self::get_regioes_terms($cidade_padrao);
      $regiao_padrao = str_pad($regiao_padrao, 4, '0', STR_PAD_LEFT);
      if (!empty($terms_regiao) && !is_wp_error($terms_regiao)) {
        foreach ($terms_regiao as $reg) {
          $opt = ['id' => $reg->slug, 'nome' => $reg->name];
          if ($regiao_padrao && ($reg->slug == $regiao_padrao)) {
            $opt['selected'] = true;
          }
          $result['regioes'][] = $opt;
        }
      }
    }
    wp_send_json_success( [
      'message' => 'Processado com sucesso!',
      'data'    => $result
    ], 200 );
  }
  public static function regiao_change( $contrato ) {
    wp_send_json_success( [ 'message' => 'Cookie Regiao alterada com sucesso!' ] );
  }
  public static function criar_cookie( ) {
    criarCookie( );
    wp_send_json_success( [ 'message' => 'Cookie criado com sucesso!' ] );
  }
}
Pinedu_Form_Pesquisa::init( );
Pinedu_Form_Cadastre::init( );
