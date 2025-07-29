<?php
class Pinedu_Form_Cadastre {
  const PREFIXO = 'wp_ajax_nopriv_';
  const ENDPOINT = '/pndPortal/wordpress/';
  const HOOK_IDENTIFICACAO = 'CADASTREIDENTIFICACAO';
  const HOOK_LOCALIZACAO = 'CADASTRELOCALIZACAO';
  const HOOK_NEGOCIACAO = 'CADASTRENEGOCIACAO';
  const HOOK_CARACTERISTICAS = 'CADASTRECARACTERISTICAS';
  const HOOK_FOTOS = 'CADASTREFOTOS';
  const HOOK_ESTADOCHANGE = 'ESTADOGPBCHANGE';
  const HOOK_CIDADECHANGE = 'CIDADEGPBCHANGE';
  const HOOK_AUTOCOMPLETE = 'AUTOCOMPLETEGPBLOGRADOURO';
  const HOOK_CONTATOIMOVEL = 'CONTATOIMOVEL';
  public static function init() {
    add_action(self::PREFIXO . self::HOOK_IDENTIFICACAO, [__CLASS__, 'carregar_tipo_imovel' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_LOCALIZACAO, [__CLASS__, 'carregar_localizacao' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_NEGOCIACAO, [__CLASS__, 'carregar_negociacao' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_CARACTERISTICAS, [__CLASS__, 'carregar_caracteristicas' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_FOTOS, [__CLASS__, 'carregar_fotos' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_ESTADOCHANGE, [__CLASS__, 'carregar_cidades' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_CIDADECHANGE, [__CLASS__, 'carregar_bairros' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_AUTOCOMPLETE, [__CLASS__, 'auto_complete_logradouro' ], 99, 1);
    add_action(self::PREFIXO . self::HOOK_CONTATOIMOVEL, [__CLASS__, 'contato_imovel' ], 99, 1);
  }
  public static function carregar_tipo_imovel( $form_data ) {
    parse_str( $_POST['form_data'], $dados );
    ob_start();
    get_template_part( 'template-parts/cadastre/tipo-imovel', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }
  public static function carregar_localizacao( $form_data ) {
    parse_str( $_POST['form_data'], $dados );
    $tipoImovel = $dados['tipo-imovel'];
    set_query_var( 'tipoImovel', $tipoImovel );
    ob_start();
    get_template_part( 'template-parts/cadastre/localizacao', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }
  public static function clean_result( $html ) {
    return trim( preg_replace( array( '/\s{2,}/', '/(\r\n|\n|\r)/', '/\s*<\s*/', '/\s*>\s*/' ), array( ' ', '', '<', '>' ), $html ) );
  }
  public static function carregar_negociacao( $form_data ) {
    parse_str( $_POST['form_data'], $dados );
    ob_start();
    get_template_part( 'template-parts/cadastre/negociacao', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }
  public static function carregar_caracteristicas( $form_data ) {
    parse_str( $_POST['form_data'], $dados );
    ob_start();
    get_template_part( 'template-parts/cadastre/caracteristicas', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }
  public static function carregar_fotos( $form_data ) {
    parse_str( $_POST['form_data'], $dados );
    ob_start();
    get_template_part( 'template-parts/cadastre/fotos', 'imovel' );
    wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
  }
  public static function carregar_cidades( $form_data ) {
    require_once get_template_directory() . '/inc/classes/DoGet.php';
    $options = get_option('pinedu_imovel_options', []);
    $server = $options['url_servidor'] ?? '';
    $token = $options['token'];
    $do = new DoGet();
    parse_str( $_POST['form_data'], $dados );
    $estado = $dados['estado'];
    $args = [ 'estado' => $estado ];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'cidades', $args);
    wp_send_json_success( $data );
    return false;
  }
  public static function carregar_bairros( $form_data ) {
    require_once get_template_directory() . '/inc/classes/DoGet.php';
    $options = get_option('pinedu_imovel_options', []);
    $server = $options['url_servidor'] ?? '';
    $token = $options['token'];
    $do = new DoGet();
    parse_str( $_POST['form_data'], $dados );
    $cidade = $dados['cidade'];
    $args = [ 'cidade' => $cidade ];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'bairros', $args);
    wp_send_json_success( $data );
    return false;
  }
  public static function auto_complete_logradouro( $form_data ) {
    require_once get_template_directory() . '/inc/classes/DoGet.php';
    $options = get_option('pinedu_imovel_options', []);
    $server = $options['url_servidor'] ?? '';
    $token = $options['token'];
    $do = new DoGet();
    parse_str( $_POST['form_data'], $dados );
    $cidade = $dados['cidade'];
    $nome = $dados['logradouro'];
    $args = [ 'cidade' => $cidade, 'nome' => $nome ];
    $data = $do->do_get( $token, $server, self::ENDPOINT . 'autocompleteEndereco', $args);
    wp_send_json_success( $data );
    return false;
  }
  public static function contato_imovel( $form_data ) {
    parse_str( $_POST['form_data'], $args );
    $mensagem = isset( $args['mensagem'] )? $args['mensagem']: '';
    $referencia = isset( $args['referencia'] )? $args['referencia']: '';
    $data = enviarCliente( $args['nome'], $args['telefone'], $args['email'], $mensagem, getCookieId(), $referencia );
    if ( $data['success'] === true ) {
      ob_start();
      get_template_part( 'template-parts/imovel/contato-success', 'imovel' );
      wp_send_json_success( self::clean_result( ob_get_clean( ) ) );
    } else {
      ob_start();
      get_template_part( 'template-parts/imovel/contato-error', 'imovel' );
      wp_send_json_error( self::clean_result( ob_get_clean( ) ) );
    }
    return false;
  }
}
class Pinedu_Form_Pesquisa {
  const NOME_COOKIE = 'PND_VISITANTE';
  const DIAS_VALIDADE_COOKIE = 90;
  const PREFIXO = 'wp_ajax_nopriv_';
  const HOOK_CONTRATO = 'CONTRATOCHANGE';
  const HOOK_TIPOIMOVEL = 'TIPOIMOVELCHANGE';
  const HOOK_CIDADE = 'CIDADECHANGE';
  const HOOK_REGIAO = 'REGIAOCHANGE';
  const HOOK_CRIAR_COOKIE = 'CRIARCOOKIE';
  const HOOK_PAGINAR_PROMOCAO = 'PAGINARPROMOCAO';
  const HOOK_PAGINAR_PESQUISA = 'PAGINARPESQUISA';
  // Método estático para inicialização
  public static function init() {
    //add_action('wp_ajax_nopriv_' . self::HOOK_CONTRATO, [__CLASS__, 'contrato_change']);
    add_action(self::PREFIXO . self::HOOK_CONTRATO, [__CLASS__, 'contrato_change'], 99, 1);
    add_action(self::PREFIXO . self::HOOK_TIPOIMOVEL, [__CLASS__, 'tipo_imovel_change'], 99, 1);
    add_action(self::PREFIXO . self::HOOK_CIDADE, [__CLASS__, 'cidade_change'], 99, 1);
    add_action(self::PREFIXO . self::HOOK_REGIAO, [__CLASS__, 'regiao_change'], 99, 1);
    add_action(self::PREFIXO . self::HOOK_CRIAR_COOKIE, [__CLASS__, 'criar_cookie']);
    add_action(self::PREFIXO . self::HOOK_PAGINAR_PROMOCAO, [__CLASS__, 'paginar_promocao']);
  }
  // Métodos estáticos
  public static function paginar_promocao() {
    require_once get_template_directory() . '/inc/classes/Promocoes.php';
    $contrato = isset($_REQUEST['contrato']) ? sanitize_text_field($_REQUEST['contrato']) : '';
    $paged = isset($_REQUEST['paged']) ? sanitize_text_field($_REQUEST['paged']) : '';
    /* Atribui o numero da página para simular o comportamento normal */
    set_query_var( 'paged', $paged );
    Promocoes::paginar_promocao( $contrato );
  }
  public static function contrato_change( ) {
    $contrato = isset($_REQUEST['contrato']) ? sanitize_text_field($_REQUEST['contrato']) : '';
    $options = get_option('pinedu_imovel_options');
    $tipo_imovel_padrao = null;
    if ( isset( $options['tipo_imovel'] ) ) { $tipo_imovel_padrao = $options['tipo_imovel']; }

    $args = array(
      'taxonomy' => 'tipo-imovel',
      'hide_empty' => true,
      'orderby' => 'slug',
      'order' => 'ASC'
    );

    $terms_tipo_imovel = get_terms($args);

    $args = [
      'taxonomy'   => 'faixa-valor'
      , 'hide_empty' => false
      , 'meta_query' => [
        [
          'key'     => 'tipo-contrato'
          , 'value'   => (string)$contrato
          , 'compare' => '='
        ]
      ]
      , 'orderby' => 'meta_value_num'
      , 'meta_key' => 'valor-inicial'
      , 'order'   => 'ASC'
    ];
    $terms_faixa_valor = get_terms($args);


    $result = array(
      'tipo-imoveis' => array()
      ,'faixa-valores' => array()
    );

    if (!empty($terms_tipo_imovel) && !is_wp_error($terms_tipo_imovel)) {
      foreach ( $terms_tipo_imovel as $tipo_imovel ) {
        $opt = [ 'id' => $tipo_imovel->slug, 'nome' => $tipo_imovel->name ];
        if ( $tipo_imovel_padrao && ( ( ( int )$tipo_imovel->slug ) == ( (int)$tipo_imovel_padrao ) ) ) $opt[ 'selected' ] = true;
        $result[ 'tipo-imoveis' ][] = $opt;
      }
    }
    if (!empty($terms_faixa_valor) && !is_wp_error($terms_faixa_valor)) {
      foreach ( $terms_faixa_valor as $fx ) {
        $opt = [ 'id' => $fx->slug, 'nome' => $fx->name, 'valorInicial' => get_term_meta( $fx->term_id, 'valor-inicial', true ), 'valorFinal' => get_term_meta( $fx->term_id, 'valor-final', true ) ];
        $result[ 'faixa-valores' ][] = $opt;
      }
    }
    if ( ( isset( $result[ 'tipo-imoveis' ] ) && !empty( $result[ 'tipo-imoveis' ] ) ) || ( isset( $result[ 'faixa_valores' ] ) && !empty( $result[ 'faixa_valores' ] ) ) ) {
      wp_send_json_success([
        'message' => 'Processado com sucesso!',
        'data' => $result
      ], 200);
    } else {
      wp_send_json_success([
        'message' => 'Processado com sucesso!',
        'data' => $result
      ], 200);
    }
  }

  public static function tipo_imovel_change( ) {
    $tipo_imovel = isset($_REQUEST['tipo_imovel']) ? sanitize_text_field($_REQUEST['tipo_imovel']) : '';
    $options = get_option('pinedu_imovel_options');
    $cidade = null;
    if ( isset( $options['cidade'] ) ) { $cidade = $options['cidade']; }

    $args = array(
      'taxonomy' => 'cidade'
      , 'hide_empty' => true
      , 'orderby' => 'slug'
      , 'order' => 'ASC'
    );

    $terms_cidade = get_terms($args);
    $result = array();

    if (!empty($terms_cidade) && !is_wp_error($terms_cidade)) {
      foreach ( $terms_cidade as $cid) {
        $opt = [ 'id' => $cid->slug, 'nome' => $cid->name ];
        //if ( $cidade && ( ( ( int )$cid->slug) == ( (int)$cidade) ) ) $opt['selected'] = true;
        $result[] = $opt;
      }
      wp_send_json_success([
        'message' => 'Processado com sucesso!',
        'data' => $result
      ], 200);
    } else {
      wp_send_json_success([
        'message' => 'Processado com sucesso!',
        'data' => $result
      ], 200);
    }
  }
  public static function cidade_change(  ) {
    $cidade = isset($_REQUEST['cidade']) ? sanitize_text_field($_REQUEST['cidade']) : '';
    $options = get_option('pinedu_imovel_options');
    $regiao = null;
    if ( isset( $options['regiao'] ) ) { $regiao = $options['regiao']; }

    $args = array(
      'taxonomy' => 'regiao'
    , 'hide_empty' => false
    , 'orderby' => 'slug'
    , 'order' => 'ASC'
    , 'meta_query' => [
        [
          'key' => 'parent_id'
          , 'value' => $cidade
          , 'compare' => '='
        ]
      ]
    );
    $terms_regiao = get_terms($args);
    $result = array();

    if (!empty($terms_regiao) && !is_wp_error($terms_regiao)) {
      foreach ($terms_regiao as $reg) {
        $opt = [ 'id' => $reg->slug, 'nome' => $reg->name ];
        //if ( $regiao && ( ( ( int )$reg->slug) == ( (int)$regiao) ) ) $opt['selected'] = true;
        $result[] = $opt;
      }
      wp_send_json_success([
        'message' => 'Processado com sucesso!',
        'data' => $result
      ], 200);
    } else {
      wp_send_json_success([
        'message' => 'Processado com sucesso!',
        'data' => $result
      ], 200);
    }
  }
  public static function regiao_change( $contrato ) {
    wp_send_json_success( [ 'message' => 'Cookie Regiao alterada com sucesso!' ] );
  }
  public static function criar_cookie() {
    criarCookie();
    wp_send_json_success( [ 'message' => 'Cookie criado com sucesso!' ] );
  }

}
Pinedu_Form_Pesquisa::init();
Pinedu_Form_Cadastre::init();
