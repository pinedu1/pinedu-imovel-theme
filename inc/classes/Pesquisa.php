<?php
/**
 * Pesquisa
 */
require_once get_template_directory( ) . '/inc/classes/PineduPostType.php';
require_once get_template_directory( ) . '/inc/classes/Pinedu_Base.php';
require_once WP_PLUGIN_DIR . '/pinedu-imovel-plugin/public/ImovelSearchService.php';

/**
 * Pesquisa
 */
class Pesquisa extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/pesquisa/container.php';
  private const CARD = 'template-parts/pesquisa/card';

  /**
   * @var WP_Post
   * Armazena a instância do POST
   */
  private $post;

  /**
   * DECLARAÇÃO NECESSÁRIA PARA PHP 8.2+
   * Armazena a query atual
   */
  public WP_Query $query;

  private $contrato = null;

  public function __construct( $post = null, $paged = 1 ) {
    $this->post = $post;
    // 1. Cria uma WP_Query VAZIA para configurarmos antes de consultar a base de dados
    $this->query = new WP_Query();
    $this->query->parse_query([
      'post_type'   => 'imovel',
      'post_status' => 'publish',
      'paged'       => $paged
    ]);
  }

  /**
   * MÉTODO AJAX: Chamado via JavaScript para paginação e ordenação
   */
  public static function paginar_pesquisa( ) {
    $paged = $_REQUEST['paged'] ?? 1;
    $p = new Pesquisa( $_REQUEST, $paged );

    if (isset($_REQUEST['contrato'])) {
      $p->setContrato($_REQUEST['contrato']);
    }

    $p->aplicar_filtros_pesquisa( $p->query );
    // 3. Executa a consulta
    global $wp_query; // 1. Aceda ao global
    $wp_query = $p->getQuery();
    $p->getQuery()->get_posts();

    ob_start( );
    $p->render( );
    $html = ob_get_clean( );

    echo $html;
    wp_die( );
  }

  /**
   * FIX PARA: Call to undefined method Pesquisa::getClass()
   */
  public function getClass() {
    return get_class($this);
  }

  public function pinedu_pesquisa_titulo( $title ) {
    return false;
  }

  public function pinedu_pesquisa_conteudo( $content ) {
    get_template_part( self::CARD );
    return false;
  }

  // Função auxiliar segura para obter parâmetros via GET/POST
  public function get_request_param($key, $default = '') {
    return isset($post[$key]) ? sanitize_text_field($post[$key]) : $default;
  }
  public function aplicar_filtros($query) {
    $max = (int) $this->get_request_param('max', 12);
    $query->set('posts_per_page', $max > 0 ? $max : 12);

    $tax_query = ['relation' => 'AND'];
    $meta_query = ['key' => 'statusImovel', 'value' => 'D', 'compare' => '='];

    // Filtros de Taxonomia
    foreach (['contrato', 'tipo-imovel', 'cidade', 'regiao'] as $tax) {
      $termo = $this->get_request_param($tax);
      if (!empty($termo)) {
        $tax_query[] = ['taxonomy' => $tax, 'field' => 'slug', 'terms' => [(string)$termo]];
      }
    }

    // Faixa de Preço
    $v_min = (float) $this->get_request_param('valor-inicial');
    $v_max = (float) $this->get_request_param('valor-final');
    if ($v_min && $v_max) {
      $contrato = self::get_request_param('contrato');
      $chaves = ['1' => 'vendaValor', '2' => 'locacaoValor', '3' => 'lancamentoValor'];

      if (array_key_exists($contrato, $chaves)) {
        $meta_query[] = ['key' => $chaves[$contrato], 'value' => [$v_min, $v_max], 'type' => 'NUMERIC', 'compare' => 'BETWEEN'];
      } else {
        $or_meta = ['relation' => 'OR'];
        foreach ($chaves as $k) $or_meta[] = ['key' => $k, 'value' => [$v_min, $v_max], 'type' => 'NUMERIC', 'compare' => 'BETWEEN'];
        $meta_query[] = $or_meta;
      }
    }

    $query->set('tax_query', $tax_query);
    $query->set('meta_query', $meta_query);
    $this->ordenar_pesquisa(
      $query
      , $this->get_request_param('contrato')
      , $this->get_request_param('sort')
      , $this->get_request_param('ordem')
    );
    return $query;
  }

  public function render( ) {
    $query = $this->getQuery();
    if ( isset($query) && $query->have_posts( ) ):
      add_filter( 'the_title', [ $this, 'pinedu_pesquisa_titulo' ] );
      add_filter( 'the_content', [ $this, 'pinedu_pesquisa_conteudo' ] );

      include locate_template( self::TEMPLATE );

      wp_reset_postdata( );
      remove_filter( 'the_title', [ $this, 'pinedu_pesquisa_titulo' ] );
      remove_filter( 'the_content', [ $this, 'pinedu_pesquisa_conteudo' ] );
    else:
      echo '<div class="alerta-sem-resultados"><p>Nenhum imóvel foi encontrado com estes filtros.</p></div>';
    endif;
  }

  /**
   * Aplica as regras de ordenação usando meta_keys de forma segura
   */
  private function ordenar_pesquisa( $query, $contrato, $sort, $direction ) {
    ImovelSearchService::ordenar( $query, $contrato, $sort, $direction );
  }

  /**
   * CORE: Motor principal que injeta os filtros de Busca.
   * Agora pode ser usado tanto pelo AJAX quanto pelo WP nativo.
   */
  public function aplicar_filtros_pesquisa( $query ) {
    ImovelSearchService::apply($query);
  }

  public function query( ) {
    // TODO: Implement query( ) method.
  }
  public function getQuery() {
    return $this->query;
  }
  public function getContrato() {
    return $this->contrato;
  }
  public function setContrato( $contrato ) {
    $this->contrato = $contrato;
  }
}
