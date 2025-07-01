<?php
require_once get_template_directory() . '/inc/classes/PineduPostType.php';
require_once get_template_directory() . '/inc/classes/Pinedu_Base.php';
class Visitados extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/visitados/container.php';
  private const CARD = 'template-parts/visitados/card.php';
  private $class = 'visitados';
  private $max = 6;
  private $titulo = 'Imóveis mais Visitados';
  private $query;
  public function __construct( $titulo = 'Imóveis mais visitados', $max = 6 ) {
    $this->titulo = $titulo;
    $this->max = $max;
    $this->query = $this->query( );
  }
  public function query( ) {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $metaQuery = array(
      [ 'key'     => 'statusImovel', 'value'   => 'D', 'compare' => '=']
      , [ 'key'     => 'clicks', 'value'   => 0, 'compare' => '>']
    );
    $args = array(
      'post_type' => 'imovel'
    , 'post_status' => 'publish'
    , 'posts_per_page' => $this->max
    , 'paged' => $paged
    , 'meta_query' => $metaQuery
    );
    return new \WP_Query( $args );
  }
  public function render() {
    if ($this->query->have_posts()):
      add_filter('the_title', [$this, 'pinedu_visitados_titulo']);
      add_filter('the_content', [$this, 'pinedu_visitados_conteudo']);
      include locate_template(self::TEMPLATE);
      wp_reset_postdata();
      remove_filter('the_title', [$this, 'pinedu_visitados_titulo']);
      remove_filter('the_content', [$this, 'pinedu_visitados_conteudo']);
    endif;
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
  public function getClass() {
    return $this->class;
  }
  /**
   * @param mixed $class
   */
  public function setClass( $class ): void {
    $this->class = $class;
  }

  public function getMax(): mixed {
    return $this->max;
  }

  public function setMax( mixed $max ): void {
    $this->max = $max;
  }

  public function getTitulo(): mixed {
    return $this->titulo;
  }

  public function setTitulo( mixed $titulo ): void {
    $this->titulo = $titulo;
  }
}
