<?php
/**
 * Visitados
 */
require_once get_template_directory( ) . '/inc/classes/PineduPostType.php';
require_once get_template_directory( ) . '/inc/classes/Pinedu_Base.php';

/**
 * Visitados
 */
class Visitados extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/visitados/container.php';
  private const CARD = 'template-parts/visitados/card.php';

  /**
   * @var string $class Classe usada para o container
   */
  private $class = 'visitados';

  /**
   * @var int|mixed $max Maximo de result na query
   */
  private $max = 6;

  /**
   * @var string $titulo Titulo do container
   */
  private $titulo = 'Imóveis mais Visitados';

  /**
   * @var WP_Query $query Armazena a query na instancia
   */
  private $query;

  public function __construct( $titulo = 'Imóveis mais visitados', $max = 6 ) {
    $this->titulo = $titulo;
    $this->max = $max;
    $this->query = $this->query( );
    baixar_fotos_destaque( $this->query );
  }

  public function query( ) {
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

    $meta_query = array(
        [ 'key'     => 'statusImovel', 'value'   => 'D', 'compare' => '=' ]
      , [ 'key'     => 'clicks',       'value'   => 0,   'compare' => '>' ]
    );

    $args = array(
      'post_type'      => 'imovel'
    , 'post_status'    => 'publish'
    , 'posts_per_page' => $this->max
    , 'paged'          => $paged
    , 'meta_query'     => $meta_query
    , 'orderby'        => 'meta_value_num'
    , 'meta_key'       => 'clicks'
    , 'order'          => 'DESC'
    , 'update_post_meta_cache' => true
    , 'update_post_term_cache' => true
    );

    // ==========================================
    // CACHE (TRANSIENT) SEGURO - APENAS IDs + TOTAL
    // ==========================================
    $transient_key = 'pnd_visitados_ids_v1_' . md5( serialize( $args ) );
    $cached_data = get_transient( $transient_key );

    if ( false !== $cached_data && is_array( $cached_data ) ) {
        $cached_ids = $cached_data['ids'];
        $total_posts = $cached_data['total'];

        // Se não houver cliques, evita quebrar a query retornando um falso id
        if ( empty( $cached_ids ) ) {
            return new \WP_Query( ['post__in' => [0]] );
        }

        // Refaz a query ultraleve. O segredo é o 'post__in' no orderby.
        // Ele garante que a ordem decrescente de cliques salva no cache seja respeitada.
        $query = new \WP_Query( [
            'post_type'              => 'imovel',
            'post__in'               => $cached_ids,
            'posts_per_page'         => $this->max,
            'paged'                  => 1, // Página 1 pois o array já está fatiado na página certa
            'orderby'                => 'post__in',
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ] );

        // Injeção manual para preservar a paginação matemática
        $query->found_posts = $total_posts;
        $query->max_num_pages = ceil( $total_posts / $this->max );

        return $query;
    }

    // Se não houver cache, o SGBD é acionado para calcular os CASTs e fazer o sort.
    $query = new \WP_Query( $args );

    $ids_para_salvar = $query->have_posts() ? wp_list_pluck( $query->posts, 'ID' ) : [];

    $data_to_cache = [
        'ids'   => $ids_para_salvar,
        'total' => $query->found_posts
    ];

    set_transient( $transient_key, $data_to_cache, 10 * MINUTE_IN_SECONDS );

    return $query;
  }

  public function render( ) {
    if ( $this->query->have_posts( ) ) {
      try {
        $posts = $this->query->posts;
        if ( is_array( $posts ) && ! empty( $posts ) ) {
            $post_ids = wp_list_pluck( $posts, 'ID' );
            if ( ! empty( $post_ids ) ) {
                update_post_caches( $posts, 'imovel', true, true );
            }
        }
          add_filter( 'the_title', [ $this, 'pinedu_visitados_titulo' ] );
          add_filter( 'the_content', [ $this, 'pinedu_visitados_conteudo' ] );

          include locate_template( self::TEMPLATE );

          wp_reset_postdata( );
          remove_filter( 'the_title', [ $this, 'pinedu_visitados_titulo' ] );
          remove_filter( 'the_content', [ $this, 'pinedu_visitados_conteudo' ] );
      } catch ( \Throwable $e ) {
          // Captura o erro e escreve no wp-content/debug.log
          error_log( 'DEBUG PINEDU RENDER ERROR: ' . $e->getMessage() . ' em ' . $e->getFile() . ':' . $e->getLine() );
          // Remove os filtros mesmo que ocorra um erro para não quebrar o resto da página
          remove_filter( 'the_title', [ $this, 'pinedu_visitados_titulo' ] );
          remove_filter( 'the_content', [ $this, 'pinedu_visitados_conteudo' ] );
      }
    }
  }

  public function pinedu_visitados_titulo( $title ) {
    return $title;
  }

  public function pinedu_visitados_conteudo( $content ) {
    include locate_template( self::CARD );
    return false;
  }

  /**
   * @return mixed
   */
  public function getClass( ) {
    return $this->class;
  }

  /**
   * @param mixed $class Nome da classe do container
   */
  public function setClass( $class ): void {
    $this->class = $class;
  }

  public function getMax( ): mixed {
    return $this->max;
  }

  public function setMax( mixed $max ): void {
    $this->max = $max;
  }

  public function getTitulo( ): string {
    return $this->titulo;
  }

  public function setTitulo( string $titulo ): void {
    $this->titulo = $titulo;
  }

  public function getQuery( ): WP_Query {
    return $this->query;
  }

  public function setQuery( WP_Query $query ): void {
    $this->query = $query;
  }
  /**
   * Método estático chamado via AJAX (admin-ajax.php)
   * pelo hook na classe Pinedu_Form_Pesquisa para paginar os Visitados
   */
  public static function paginar_visitados() {
    // Para debugar o que está vindo do AJAX, se necessário
    if ( is_development_mode() ) {
        error_log( 'paginar_visitados: ' . print_r( $_REQUEST, true ) );
    }

    // Resgata os parâmetros da requisição AJAX (com valores padrão seguros)
    $max = isset( $_REQUEST['max'] ) ? intval( $_REQUEST['max'] ) : 6;
    $titulo = isset( $_REQUEST['titulo'] ) ? sanitize_text_field( $_REQUEST['titulo'] ) : 'Imóveis mais Visitados';

    // Instancia a classe
    $v = new Visitados( $titulo, $max );

    // Inicia o buffer de saída (output buffering)
    ob_start();

    // Roda a query e imprime o template
    $v->render();

    // Captura o HTML gerado e limpa o buffer
    $html = ob_get_clean();

    // Ecoa o HTML para o frontend
    echo $html;

    // Encerra a execução do WordPress (obrigatório em retornos AJAX)
    wp_die();
  }
}
