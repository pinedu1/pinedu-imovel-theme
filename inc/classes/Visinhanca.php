<?php
require_once get_template_directory() . '/inc/classes/PineduPostType.php';
require_once get_template_directory() . '/inc/classes/Pinedu_Base.php';
class Visinhanca extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/visinhanca/container.php';
  private const CARD = 'template-parts/visinhanca/card.php';
  private $class = 'visinhanca';
  private $max = 6;
  private $titulo = 'Imóveis mais Visitados';
  private $query;
  private $post;
  public function __construct( $post, $titulo = 'Imóveis próximos', $max = 6 ) {
    $this->titulo = $titulo;
    $this->max = $max;
    $this->post = $post;
    $this->query = $this->query( );
  }
  public function query( ) {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $metaQuery = array(
      'relation' => 'AND'
      , [ 'key' => 'statusImovel', 'value' => 'D', 'compare' => '=']
      , [ 'key' => 'referencia', 'value' => get_post_meta( $this->post->ID, 'referencia', true ), 'compare' => '!=']
    );
    if ( get_post_meta( $this->post->ID, 'ativarVenda', true ) == '1' ) {
      $metaQuery[] = [ 'key' => 'ativarVenda', 'value' => '1', 'compare' => '='];
    }
    if ( get_post_meta( $this->post->ID, 'ativarLocacao', true ) == '1' ) {
      $metaQuery[] = [ 'key' => 'ativarLocacao', 'value' => '1', 'compare' => '='];
    }
    if ( get_post_meta( $this->post->ID, 'ativarLancamento', true ) == '1' ) {
      $metaQuery[] = [ 'key' => 'ativarLancamento', 'value' => '1', 'compare' => '='];
    }
    $metaQuery[] = [ 'key' => 'regiaoCorretagem', 'value' => get_post_meta( $this->post->ID, 'regiaoCorretagem', true ), 'compare' => '='];

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
      add_filter('the_title', [$this, 'pinedu_visinhanca_titulo']);
      add_filter('the_content', [$this, 'pinedu_visinhanca_conteudo']);
      include locate_template(self::TEMPLATE);
      wp_reset_postdata();
      remove_filter('the_title', [$this, 'pinedu_visinhanca_titulo']);
      remove_filter('the_content', [$this, 'pinedu_visinhanca_conteudo']);
    endif;
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
