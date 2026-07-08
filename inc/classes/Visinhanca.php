<?php
/**
 * Visinhanca
 */
require_once get_template_directory( ) . '/inc/classes/PineduPostType.php';
require_once get_template_directory( ) . '/inc/classes/Pinedu_Base.php';

/**
 * Visinhanca
 */
class Visinhanca extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/visinhanca/container.php';
  private const CARD = 'template-parts/visinhanca/card.php';

  /**
   * @var string $class Classe usada para o container
   */
  private $class = 'visinhanca';

  /**
   * @var int|mixed $max Maximo de result na query
   */
  private $max = 6;

  /**
   * @var string $titulo Titulo do container
   */
  private $titulo = 'Imóveis Próximos';

  /**
   * @var WP_Query $query Armazena a query na instancia
   */
  private $query;

  /**
   * @var WP_Post Instância Post varrido
   */
  private $post;

  public function __construct( $post, $titulo = 'Imóveis próximos', $max = 6 ) {
    $this->titulo = $titulo;
    $this->max = $max;
    $this->post = $post;
    $this->query = $this->query( );
    //baixar_fotos_destaque( $this->query );
  }

  public function query( ) {
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $latitude = get_post_meta( $this->post->ID, 'latitude', true );
    $longitude = get_post_meta( $this->post->ID, 'longitude', true );
    $raio = 5; // km

    $args = array(
      'post_type'      => 'imovel'
    , 'post_status'    => 'publish'
    , 'posts_per_page' => $this->max
    , 'paged'          => $paged
    , 'post__not_in'   => [ $this->post->ID ]
    , 'update_post_meta_cache' => true
    , 'update_post_term_cache' => true
    );

    if ( ! empty( $latitude ) && ! empty( $longitude ) ) {
        $lat_min = $latitude - ( $raio / 111.12 );
        $lat_max = $latitude + ( $raio / 111.12 );
        $lon_min = $longitude - ( $raio / abs( cos( deg2rad( $latitude ) ) * 111.12 ) );
        $lon_max = $longitude + ( $raio / abs( cos( deg2rad( $latitude ) ) * 111.12 ) );

        $args['meta_query'] = array(
            'relation' => 'AND'
          , array( 'key' => 'statusImovel', 'value' => 'D', 'compare' => '=' )
          , array( 'key' => 'latitude', 'value' => [ $lat_min, $lat_max ], 'compare' => 'BETWEEN', 'type' => 'DECIMAL' )
          , array( 'key' => 'longitude', 'value' => [ $lon_min, $lon_max ], 'compare' => 'BETWEEN', 'type' => 'DECIMAL' )
        );
    } else {
        $args['meta_query'] = array(
            array( 'key' => 'statusImovel', 'value' => 'D', 'compare' => '=' )
        );
    }

    // ==========================================
    // CACHE (TRANSIENT) DA WP_QUERY - 10 MINUTOS
    // ==========================================
    $transient_key = 'pnd_visinhanca_q_' . md5( serialize( $args ) );
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
        add_filter( 'the_title', [ $this, 'pinedu_visinhanca_titulo' ] );
        add_filter( 'the_content', [ $this, 'pinedu_visinhanca_conteudo' ] );
        include locate_template( self::TEMPLATE );
        wp_reset_postdata( );
        remove_filter( 'the_title', [ $this, 'pinedu_visinhanca_titulo' ] );
        remove_filter( 'the_content', [ $this, 'pinedu_visinhanca_conteudo' ] );
      } catch ( \Throwable $e ) {
          // Captura o erro e escreve no wp-content/debug.log
          error_log( 'DEBUG PINEDU RENDER ERROR: ' . $e->getMessage() . ' em ' . $e->getFile() . ':' . $e->getLine() );
          // Remove os filtros mesmo que ocorra um erro para não quebrar o resto da página
          remove_filter( 'the_title', [ $this, 'pinedu_promocao_titulo' ] );
          remove_filter( 'the_content', [ $this, 'pinedu_promocao_conteudo' ] );
      }
    }
  }

  public function pinedu_visinhanca_titulo( $title ) {
    return $title;
  }

  public function pinedu_visinhanca_conteudo( $content ) {
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
   * @param mixed $class No da classe do container a definir
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

  public function getTitulo( ): mixed {
    return $this->titulo;
  }

  public function setTitulo( mixed $titulo ): void {
    $this->titulo = $titulo;
  }

  public function getQuery( ): mixed {
    return $this->query;
  }

  public function setQuery( mixed $query ): void {
    $this->query = $query;
  }

  public function getPost( ): WP_Post {
    return $this->post;
  }

  public function setPost( WP_Post $post ): void {
    $this->post = $post;
  }
}
