<?php
require_once get_template_directory() . '/inc/classes/PineduPostType.php';
require_once get_template_directory() . '/inc/classes/Pinedu_Base.php';
class Pesquisa extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/pesquisa/container';
  private const CARD = 'template-parts/pesquisa/card';
  private $post;
  public function __construct( $post ) {
    $this->post = $post;
  }
  public function pinedu_pesquisa_titulo( $title ) {
    return false;
  }
  public function pinedu_pesquisa_conteudo( $content ) {
    get_template_part(self::CARD );
    return false;
  }
  public static function paginar_pesquisa( $contrato ) {
    if ( empty( $contrato )  ) {
      wp_send_json_error([
        'message' => 'Tipo de Contrato obrigatório'
        , 'error_code' => 'contrato_required'
      ], 500);
    }
    $contrato = isset($_REQUEST['contrato']) ? sanitize_text_field($_REQUEST['contrato']) : '';
    $p = new Pesquisa( $contrato, 8 );
    $p->render();
    $html = ob_get_clean();
    echo $html;
    wp_die();
  }
  public function render() {
    if ($this->query->have_posts()):
      add_filter('the_title', [$this, 'pinedu_pesquisa_titulo']);
      add_filter('the_content', [$this, 'pinedu_pesquisa_conteudo']);
      include locate_template(self::TEMPLATE);
      wp_reset_postdata();
      remove_filter('the_title', [$this, 'pinedu_pesquisa_titulo']);
      remove_filter('the_content', [$this, 'pinedu_pesquisa_conteudo']);
    endif;
  }

  public function query() {
    // TODO: Implement query() method.
  }
}
