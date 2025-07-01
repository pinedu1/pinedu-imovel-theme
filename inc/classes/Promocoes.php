<?php
require_once get_template_directory() . '/inc/classes/PineduPostType.php';
require_once get_template_directory() . '/inc/classes/Pinedu_Base.php';
class Promocoes extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/promocao/container.php';
  private const CARD = 'template-parts/promocao/card';
  private $promocao;
  private $contrato;
  private $titulo;
  private $query;
  private $max;
  public function __construct( $contrato, $max = 6 ) {
    $this->contrato = $contrato;
    $this->max = $max;
    $this->query = $this->query();
  }
  public function query( ) {
    $contrato = isset($this->contrato) ? $this->contrato: 1;
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $metaQuery = array(
      [ 'key'     => 'statusImovel', 'value'   => 'D', 'compare' => '=']
    );
    switch ( $contrato ) {
      case 1:
        $metaQuery[] = [ 'key'     => 'vendaPromocao', 'value'   => '1', 'compare' => '='];
        $this->titulo = 'Venda';
        $this->class = 'venda';
        break;
      case 2:
        $metaQuery[] = [ 'key'     => 'locacaoPromocao', 'value'   => '1', 'compare' => '='];
        $this->titulo = 'Locação';
        $this->class = 'locacao';
        break;
      case 3:
        $metaQuery[] = [ 'key'     => 'lancamentoPromocao', 'value'   => '1', 'compare' => '='];
        $this->titulo = 'Lançamento';
        $this->class = 'lançamento';
        break;
      default:
        $metaQuery[] = [ 'key'     => 'vendaPromocao', 'value'   => '1', 'compare' => '='];
        $this->titulo = 'Venda';
        $this->class = 'venda';
        break;
    }
    $args = array(
      'post_type' => 'imovel'
      , 'post_status' => 'publish'
      , 'posts_per_page' => $this->max
      , 'paged' => $paged
      , 'meta_query' => $metaQuery
    );
    return new \WP_Query( $args );
  }
  public function pinedu_promocao_titulo( $title ) {
    return $title;
  }
  public function pinedu_promocao_conteudo( $content ) {
    get_template_part(self::CARD );
    return false;
  }
  public static function paginar_promocao( $contrato ) {
    if ( empty( $contrato )  ) {
      wp_send_json_error([
        'message' => 'Tipo de Contrato obrigatório'
        , 'error_code' => 'contrato_required'
      ], 500);
    }
    $contrato = isset($_REQUEST['contrato']) ? sanitize_text_field($_REQUEST['contrato']) : '';
    $p = new Promocoes( $contrato, 8 );
    $p->render();
    $html = ob_get_clean();
    echo $html;
    wp_die();
  }
  public function render() {
    if ($this->query->have_posts()):
      add_filter('the_title', [$this, 'pinedu_promocao_titulo']);
      add_filter('the_content', [$this, 'pinedu_promocao_conteudo']);
      include locate_template(self::TEMPLATE);
      wp_reset_postdata();
      remove_filter('the_title', [$this, 'pinedu_promocao_titulo']);
      remove_filter('the_content', [$this, 'pinedu_promocao_conteudo']);
    endif;
  }
  /**
   * @return mixed
   */
  public function getTitulo() {
    return $this->titulo;
  }
  /**
   * @param mixed $titulo
   */
  public function setTitulo( $titulo ): void {
    $this->titulo = $titulo;
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
  private $class;

  public function getPromocao() {
    return $this->promocao;
  }
  public function setPromocao( $promocao ): void {
    $this->promocao = $promocao;
  }

  /**
   * @return mixed
   */
  public function getContrato() {
    return $this->contrato;
  }

  /**
   * @param mixed $contrato
   */
  public function setContrato( $contrato ): void {
    $this->contrato = $contrato;
  }
}
