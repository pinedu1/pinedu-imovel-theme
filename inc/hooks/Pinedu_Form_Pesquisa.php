<?php
/**
 * Classe responsável pelo motor do formulário dinâmico de pesquisas do portal.
 * Controla os selects em cascata, cálculo de ranges de valores, paginação via AJAX
 * e montagem dos campos do filtro avançado.
 * * @package Pinedu
 */
class Pinedu_Form_Pesquisa {

  /**
   * Nome do cookie de rastreamento do visitante.
   * @var string
   */
  const NOME_COOKIE = 'PND_VISITANTE';

  /**
   * Prazo de validade do cookie em dias.
   * @var int
   */
  const DIAS_VALIDADE_COOKIE = 90;

  /**
   * Registra hooks tanto para usuários logados quanto deslogados.
   * @var array
   */
  const PREFIXOS = [ 'wp_ajax_nopriv_', 'wp_ajax_' ];

  // --------------------------------------------------------------------------
  // Constantes de Nomes dos Hooks (Ações AJAX)
  // --------------------------------------------------------------------------
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

  /**
   * Inicializa a classe, varrendo os prefixos para registrar as ações AJAX.
   * @return void
   */
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

  /**
   * Renderiza a paginação do bloco de promoções via AJAX.
   * @return void
   */
  public static function paginar_promocao( ) {
    require_once get_template_directory( ) . '/inc/classes/Promocoes.php';
    $contrato = isset( $_REQUEST[ 'contrato' ] ) ? sanitize_text_field( $_REQUEST[ 'contrato' ] ) : '';
    $paged = isset( $_REQUEST[ 'paged' ] ) ? sanitize_text_field( $_REQUEST[ 'paged' ] ) : '';
    $max = isset( $_REQUEST[ 'max' ] ) ? sanitize_text_field( $_REQUEST[ 'max' ] ) : 8;
    $card = isset( $_REQUEST[ 'card' ] ) ? sanitize_text_field( $_REQUEST[ 'card' ] ) : null;
    $template = isset( $_REQUEST[ 'template' ] ) ? sanitize_text_field( $_REQUEST[ 'template' ] ) : null;
    $tipo_imovel = isset( $_REQUEST[ 'tipo_imovel' ] ) ? sanitize_text_field( $_REQUEST[ 'tipo_imovel' ] ) : null;

    // Atribui o numero da página para simular o comportamento normal do WP_Query
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

  /**
   * Processa e exibe a próxima página dos resultados de pesquisa.
   * @return void
   */
  public static function paginar_pesquisa( ) {
    require_once get_template_directory( ) . '/inc/classes/Pesquisa.php';
    Pesquisa::paginar_pesquisa( );
  }

  /**
   * Paginação do carrossel de imóveis mais visitados.
   * @return void
   */
  public static function paginar_visitados( ) {
    require_once get_template_directory( ) . '/inc/classes/Visitados.php';
    $paged = isset( $_REQUEST[ 'paged' ] ) ? sanitize_text_field( $_REQUEST[ 'paged' ] ) : '';
    $max = isset( $_REQUEST[ 'max' ] ) ? sanitize_text_field( $_REQUEST[ 'max' ] ) : 8;
    $titulo = isset( $_REQUEST[ 'titulo' ] ) ? sanitize_text_field( $_REQUEST[ 'titulo' ] ) : 'Imóveis mais visitados';

    set_query_var( 'paged', $paged );
    Visitados::paginar_visitados( $titulo, $max );
  }

  /**
   * Paginação do carrossel de imóveis na vizinhança do imóvel atual.
   * @return void
   */
  public static function paginar_visinhanca( ) {
    require_once get_template_directory( ) . '/inc/classes/Visinhanca.php';
    $paged = isset( $_REQUEST[ 'paged' ] ) ? sanitize_text_field( $_REQUEST[ 'paged' ] ) : '';
    $max = isset( $_REQUEST[ 'max' ] ) ? sanitize_text_field( $_REQUEST[ 'max' ] ) : 8;
    $titulo = isset( $_REQUEST[ 'titulo' ] ) ? sanitize_text_field( $_REQUEST[ 'titulo' ] ) : 'Imóveis Próximos';
    $post_id = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : 0;

    // Verifica se um ID válido foi passado
    if ( $post_id > 0 ) {
      set_query_var( 'paged', $paged );
      Visinhanca::paginar_visinhanca( $post_id, $titulo, $max );
    }
  }

  /**
   * Resgata as opções da taxonomia "tipo-imovel".
   * @return array|WP_Error Array de objetos WP_Term.
   */
  private static function get_tipo_imoveis_terms( ) {
    $args = array(
      'taxonomy'   => 'tipo-imovel',
      'hide_empty' => true,
      'orderby'    => 'name',
      'order'      => 'ASC'
    );
    return get_terms( $args );
  }

  /**
   * Resgata as opções da taxonomia "cidade".
   * @return array|WP_Error Array de objetos WP_Term.
   */
  private static function get_cidades_terms( ) {
    $args = array(
      'taxonomy'   => 'cidade',
      'hide_empty' => true,
      'orderby'    => 'name',
      'order'      => 'ASC'
    );
    return get_terms( $args );
  }

  /**
   * Resgata as opções da taxonomia "regiao" baseadas em uma cidade pai.
   * * @param int|string|null $cidade Slug ou ID da cidade para relacionar as regiões.
   * @return array|WP_Error Array de objetos WP_Term.
   */
  private static function get_regioes_terms( $cidade = null ) {
    $args = array(
      'taxonomy' => 'regiao'
    , 'hide_empty' => false
    , 'orderby' => 'name'
    , 'order' => 'ASC'
    , 'meta_query' => [
        [
          'key' => 'parent_id',
          'value' => $cidade,
          'compare' => '='
        ]
      ]
    );
    return get_terms( $args );
  }

  /**
   * Trata o evento "onChange" do campo de Contrato no form de pesquisa.
   * Refaz todos os selects dependentes e recalcula a faixa de valores baseada no contrato.
   * @return void
   */
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

    $result['faixa-valores'] = $fx['faixas'];
    $result['valor-inicial'] = $fx['pivot_menor'];
    $result['valor-final'] = $fx['pivot_maior'];

    wp_send_json_success( [
      'message' => 'Processado com sucesso!',
      'data'    => $result
    ], 200 );
  }

  /**
   * Endpoint de apoio para recuperar apenas o array de faixas estatísticas do slide de preços.
   * @return void
   */
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

  /**
   * Calcula os steps dinâmicos para o Range Slider (Filtro de Preço), dividindo o
   * escopo de preços baseados em quartis estatísticos dos imóveis do banco.
   * Utiliza WordPress Transients para cachear os dados por 12 horas.
   * * @param string $contrato ID do contrato (1=Venda, 2=Locação, 3=Lançamento)
   * @return array Array contendo faixas e pivôs gerados estatisticamente.
   */
  public static function calcula_faixa_valor( $contrato ) {
    // Força limpeza (Remova em produção para usar o cache corretamente)
    delete_transient( 'pinedu_faixas_contrato_1' );
    delete_transient( 'pinedu_faixas_contrato_2' );
    delete_transient( 'pinedu_faixas_contrato_3' );
    delete_transient( 'pinedu_faixas_contrato_todos' );

    $chave_cache = 'pinedu_faixas_contrato_' . ( empty($contrato) ? 'todos' : $contrato );
    $dados_cacheados = get_transient( $chave_cache );

    if ( false !== $dados_cacheados ) {
      return $dados_cacheados;
    }

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
    $qtd_itens = 25;

    // Trava de Segurança
    if ( empty($valores_ordenados) ) {
      $resultado = [
        'faixas'      => array_fill(0, $qtd_itens, 0),
        'pivot_menor' => 0,
        'pivot_maior' => 0
      ];
      set_transient( $chave_cache, $resultado, 12 * HOUR_IN_SECONDS );
      return $resultado;
    }

    $total_imoveis = count($valores_ordenados);
    $min_real     = (float) $valores_ordenados[0];
    $q1_real      = (float) $valores_ordenados[(int) floor($total_imoveis * 0.25)];
    $mediana_real = (float) $valores_ordenados[(int) floor($total_imoveis * 0.50)];
    $q3_real      = (float) $valores_ordenados[(int) floor($total_imoveis * 0.75)];
    $max_real     = (float) $valores_ordenados[$total_imoveis - 1];

    $arredondar_centena = function($valor) {
      return round($valor / 100) * 100;
    };

    $novo_min = $arredondar_centena($min_real * 0.85);
    $novo_max = $arredondar_centena($max_real * 1.15);

    $faixas = [];

    if ($novo_max > $novo_min) {
      $faixas[0] = (int) $novo_min;

      $step1 = ($q1_real - $novo_min) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[$i] = (int) $arredondar_centena($novo_min + ($step1 * $i)); }
      $faixas[6] = (int) $arredondar_centena($q1_real);

      $step2 = ($mediana_real - $q1_real) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[6 + $i] = (int) $arredondar_centena($q1_real + ($step2 * $i)); }
      $faixas[12] = (int) $arredondar_centena($mediana_real);

      $step3 = ($q3_real - $mediana_real) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[12 + $i] = (int) $arredondar_centena($mediana_real + ($step3 * $i)); }
      $faixas[18] = (int) $arredondar_centena($q3_real);

      $step4 = ($novo_max - $q3_real) / 6;
      for ($i = 1; $i < 6; $i++) { $faixas[18 + $i] = (int) $arredondar_centena($q3_real + ($step4 * $i)); }

      $faixas[24] = (int) $novo_max;

      $idx_meio = 12;
      $idx_menor = 10;
      $idx_maior = 14;

      $pivot_menor = $faixas[$idx_menor];
      $pivot_maior = $faixas[$idx_maior];

    } else {
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

    set_transient( $chave_cache, $resultado, 12 * HOUR_IN_SECONDS );

    return $resultado;
  }

  /**
   * Processa o template HTML para o container de filtros avançados (Checkboxes e Numbers)
   * * @param array $terms_dependencias Array formatado de campos dependentes.
   * @return string Código HTML do filtro avançado pronto.
   */
  private static function renderiza_filtro_avancado( $terms_dependencias ) {
    if ( empty( $terms_dependencias ) || is_wp_error( $terms_dependencias ) ) {
      return '';
    }

    ob_start();
    get_template_part(
      'template-parts/pesquisa/filtro',
      'avancado',
      ['terms_dependencias' => $terms_dependencias]
    );
    return ob_get_clean();
  }

  /**
   * Trata o evento "onChange" do campo de Tipo de Imóvel.
   * Atualiza as Cidades, Regiões e aciona a construção dos filtros de Características (Filtro Avançado).
   * @return void
   */
  public static function tipo_imovel_change( ) {
    $tipo_imovel = isset( $_REQUEST['tipo'] ) ? sanitize_text_field( $_REQUEST['tipo'] ) : '';
    $cidade = isset( $_REQUEST['cidade'] ) ? sanitize_text_field( $_REQUEST['cidade'] ) : '';
    $regiao = isset( $_REQUEST['regiao'] ) ? sanitize_text_field( $_REQUEST['regiao'] ) : '';

    $tipo_imovel_padrao = $tipo_imovel;
    $cidade_padrao = $cidade;
    $regiao_padrao = $regiao;

    $result = array(
      'cidades'      => array(),
      'regioes'      => array(),
      'dependencias' => array(),
      'html_filtro'  => '',
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

    $terms_dependencias = self::retorna_dependencias( $tipo_imovel );

    if ( !empty( $terms_dependencias ) && !is_wp_error( $terms_dependencias ) ) {
      $result['dependencias'] = $terms_dependencias;
      // Delega a transformação do HTML para o método privado
      $result['html_filtro']  = self::renderiza_filtro_avancado( $terms_dependencias );
    }

    wp_send_json_success( [
      'message' => 'Processado com sucesso!',
      'data'    => $result
    ], 200 );
  }

  /**
   * Resgata do banco de dados quais características devem ser exibidas como
   * "Filtro Avançado" para um tipo específico de imóvel.
   * Ex: Terrenos não tem quartos. Apartamentos têm portaria.
   * * @param string $tipo_imovel O slug do tipo de imóvel ativo.
   * @return array Array particionado nos grupos 'BOOLEAN', 'INTEIRO', e 'FLOAT'.
   */
  public static function retorna_dependencias( $tipo_imovel ) {
    global $wpdb;

    // Prepara a string do LIKE com segurança (escapa caracteres especiais)
    $like_pattern = $wpdb->esc_like( $tipo_imovel ) . '-%';

    // Monta a query usando prefixos dinâmicos das tabelas ($wpdb->terms, etc)
    $query = $wpdb->prepare("
        SELECT DISTINCT
            t.term_id,
            t.name AS termo_nome,
            t.slug AS termo_slug,
            tm_tipo.meta_value AS meta_tipo
        FROM {$wpdb->terms} t
        INNER JOIN {$wpdb->term_taxonomy} tt
            ON t.term_id = tt.term_id
        INNER JOIN {$wpdb->termmeta} tm_relativo
            ON t.term_id = tm_relativo.term_id AND tm_relativo.meta_key = 'relativo'
        INNER JOIN {$wpdb->termmeta} tm_ordem
            ON t.term_id = tm_ordem.term_id AND tm_ordem.meta_key = 'ordem'
        INNER JOIN {$wpdb->termmeta} tm_tipo
            ON t.term_id = tm_tipo.term_id AND tm_tipo.meta_key = 'tipo'
        WHERE tt.taxonomy = 'tipo-dependencia'
          AND t.slug LIKE %s
          AND tm_relativo.meta_value = 'CARACTERISTICAS'
        ORDER BY
            CAST(tm_ordem.meta_value AS UNSIGNED) ASC, tm_tipo.meta_value ASC;
    ", $like_pattern );

    // ARRAY_A retorna os dados como arrays associativos para o foreach
    $dependencias = $wpdb->get_results( $query, ARRAY_A );

    $result = [
      'BOOLEAN' => [],
      'INTEIRO' => [],
      'FLOAT'   => []
    ];

    foreach ($dependencias as $dep) {
      $slug = mb_strtoupper( $dep['termo_slug'] , 'UTF-8');
      $p = explode('-', $slug);
      $key = isset($p[1]) ? $p[1] : '';

      $a = [
        'id'    => $slug,
        'nome'  => $dep['termo_nome'],
        'chave' => $key
      ];

      // Agrupa pelo tipo de campo a ser gerado
      if ( $dep['meta_tipo'] == 'BOOLEAN' ) {
        $result['BOOLEAN'][] = $a;
      } elseif ( $dep['meta_tipo'] == 'INTEIRO' || $dep['meta_tipo'] == 'INTEIRO_TEXTO' ) {
        $a['min'] = 0;
        $a['max'] = 99;
        $a['step'] = 1;
        $result['INTEIRO'][] = $a;
      } elseif ( $dep['meta_tipo'] == 'FLOAT' ) {
        $a['min'] = 0;
        $a['max'] = 99999;
        $a['step'] = 0.5;
        $result['FLOAT'][] = $a;
      }
    }
    return $result;
  }

  /**
   * Trata o evento "onChange" da Cidade.
   * Carrega as regiões pertencentes a esta cidade.
   * @return void
   */
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

  /**
   * Ação rápida disparada na troca de região.
   * * @param string $contrato
   * @return void
   */
  public static function regiao_change( $contrato ) {
    wp_send_json_success( [ 'message' => 'Cookie Regiao alterada com sucesso!' ] );
  }

  /**
   * Inicializa forçadamente o cookie de sessão do visitante,
   * usado para salvar histórico de imóveis visitados.
   * @return void
   */
  public static function criar_cookie( ) {
    criarCookie( );
    wp_send_json_success( [ 'message' => 'Cookie criado com sucesso!' ] );
  }
}
