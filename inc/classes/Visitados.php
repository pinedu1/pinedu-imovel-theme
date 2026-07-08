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

    // RESTAURADO: A sua condição original e exata!
    $meta_query = array(
        [ 'key'     => 'statusImovel', 'value'   => 'D', 'compare' => '=' ]
      , [ 'key'     => 'clicks',       'value'   => 0,   'compare' => '>' ]
    );

    // RESTAURADO: O ordenamento original pelos cliques!
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
    // CACHE (TRANSIENT) DA WP_QUERY - 10 MINUTOS
    // ==========================================
    $transient_key = 'pnd_visitados_q_' . md5( serialize( $args ) );
    $cached_query = get_transient( $transient_key );

    if ( false !== $cached_query ) {
        return $cached_query;
    }

    $query = new \WP_Query( $args );

    set_transient( $transient_key, $query, 10 * MINUTE_IN_SECONDS );

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
          remove_filter( 'the_title', [ $this, 'pinedu_promocao_titulo' ] );
          remove_filter( 'the_content', [ $this, 'pinedu_promocao_conteudo' ] );
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
}
