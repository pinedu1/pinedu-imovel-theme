<?php
/**
 * Promocoes
 */
require_once get_template_directory( ) . '/inc/classes/PineduPostType.php';
require_once get_template_directory( ) . '/inc/classes/Pinedu_Base.php';

/**
 * Promocoes
 */
class Promocoes extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/promocao/container.php';

  // Transformado de constante para variável
  private $card = 'template-parts/promocao/card';

  /**
   * @var WP_Post
   */
  private $promocao;

  /**
   * @var int armazena o contrato [1,2,3]
   */
  private $contrato;

  /**
   * @var string
   */
  private $titulo;

  /**
   * @var WP_Query
   */
  private $query;

  /**
   * @var int
   */
  private $max;

  /**
   * @var string Nome da classe que vai ser usada para o container
   */
  private $class;

  /**
   * @var string|null Filtro para a taxonomia tipo-imovel
   */
  private $tipo_imovel = null;

  public function __construct( $contrato, $max = 6 ) {
    $this->contrato = $contrato;
    $this->max = $max;
    // O ideal seria chamar a query DEPOIS de setar o tipo_imovel se for passar pelo construtor,
    // mas se for setar depois via setter, você precisará chamar $this->query = $this->query(); novamente.
    $this->query = $this->query( );
    baixar_fotos_destaque( $this->query );
  }

  public function query( ) {
    $contrato = isset( $this->contrato ) ? $this->contrato : 1;
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

    $meta_query = [
      [ 'key' => 'statusImovel', 'value' => 'D', 'compare' => '=' ]
    ];

    switch ( $contrato ) {
      // phpcs:disable PSR2.ControlStructures.SwitchDeclaration.BreakIndent
      case 1:
        $meta_query[] = [ 'key' => 'vendaPromocao', 'value' => '1', 'compare' => '=' ];
        $this->titulo = 'Venda';
        $this->class = 'venda';
        break;
      case 2:
        $meta_query[] = [ 'key' => 'locacaoPromocao', 'value' => '1', 'compare' => '=' ];
        $this->titulo = 'Locação';
        $this->class = 'locacao';
        break;
      case 3:
        $meta_query[] = [ 'key' => 'lancamentoPromocao', 'value' => '1', 'compare' => '=' ];
        $this->titulo = 'Lançamento';
        $this->class = 'lançamento';
        break;
      default:
        $meta_query[] = [ 'key' => 'vendaPromocao', 'value' => '1', 'compare' => '=' ];
        $this->titulo = 'Venda';
        $this->class = 'venda';
        break;
    }

    $args = array(
      'post_type' => 'imovel'
    , 'post_status' => 'publish'
    , 'posts_per_page' => $this->max
    , 'paged' => $paged
    , 'meta_query' => $meta_query
    );

    // ==========================================
    // NOVA VERIFICAÇÃO DO TIPO DE IMÓVEL
    // ==========================================
    if ( ! is_null( $this->tipo_imovel ) ) {
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'tipo-imovel',
          'field'    => 'name',
          'terms'    => $this->tipo_imovel,
        ),
      );
    }

    return new \WP_Query( $args );
  }

  public function pinedu_promocao_titulo( $title ) {
    return $title;
  }

  public function pinedu_promocao_conteudo( $content ) {
    // Atualizado para usar a variável $this->card em vez da constante self::CARD
    get_template_part( $this->card );
    return false;
  }

  public static function paginar_promocao( $contrato, $tipo_imovel = null, $max = 8 ) {
    if ( empty( $contrato ) ) {
      wp_send_json_error( [
        'message' => 'Tipo de Contrato obrigatório'
        , 'error_code' => 'contrato_required'
      ], 500 );
    }
    $contrato = isset( $_REQUEST['contrato'] ) ? sanitize_text_field( $_REQUEST['contrato'] ) : '';
    $card = isset( $_REQUEST[ 'card' ] ) ? sanitize_text_field( $_REQUEST[ 'card' ] ) : null;

    $p = new Promocoes( $contrato, $max );
    if ( $card && !empty( $card ) ) {
      $p->setCard( $card );
    }
    if ( ! empty($tipo_imovel) ) {
      $p->setTipoImovel( $tipo_imovel );
    }

    // Se o AJAX enviar também o tipo de imóvel, você pode setá-lo e rodar a query novamente aqui:
    if ( isset( $_REQUEST['tipo_imovel'] ) ) {
      $p->setTipoImovel( sanitize_text_field( $_REQUEST['tipo_imovel'] ) );
      $p->setQuery( $p->query() ); // Atualiza a query com o novo filtro
    }

    $p->render( );
    $html = ob_get_clean( );
    echo $html;
    wp_die( );
  }

  public function render( ) {
    if ( $this->query->have_posts( ) ):
      add_filter( 'the_title', [ $this, 'pinedu_promocao_titulo' ] );
      add_filter( 'the_content', [ $this, 'pinedu_promocao_conteudo' ] );
      include locate_template( self::TEMPLATE );
      wp_reset_postdata( );
      remove_filter( 'the_title', [ $this, 'pinedu_promocao_titulo' ] );
      remove_filter( 'the_content', [ $this, 'pinedu_promocao_conteudo' ] );
    endif;
  }

  // ==========================================
  // GETTERS E SETTERS
  // ==========================================

  /**
   * @return string
   */
  public function getCard() {
    return $this->card;
  }

  /**
   * @param string $card
   */
  public function setCard($card): void {
    $this->card = $card;
  }

  /**
   * @return string|null
   */
  public function getTipoImovel() {
    return $this->tipo_imovel;
  }

  /**
   * @param string|null $tipo_imovel
   */
  public function setTipoImovel($tipo_imovel): void {
    $this->tipo_imovel = $tipo_imovel;
  }

  /**
   * @param WP_Query $query
   */
  public function setQuery($query): void {
    $this->query = $query;
  }

  public function getTitulo( ) {
    return $this->titulo;
  }

  public function setTitulo( $titulo ): void {
    $this->titulo = $titulo;
  }

  public function getClass( ) {
    return $this->class;
  }

  public function setClass( $class ): void {
    $this->class = $class;
  }

  public function getPromocao( ) {
    return $this->promocao;
  }

  public function setPromocao( $promocao ): void {
    $this->promocao = $promocao;
  }

  public function getContrato( ) {
    return $this->contrato;
  }

  public function setContrato( $contrato ): void {
    $this->contrato = $contrato;
  }

  public function get_top_tipo_imovel( $limit = 3 ) {
    global $wpdb;

    $contrato = isset( $this->contrato ) ? $this->contrato : 1;
    $limit    = intval( $limit );

    $meta_key_promocao = 'vendaPromocao'; // Padrão (1)
    if ( $contrato == 2 ) {
      $meta_key_promocao = 'locacaoPromocao';
    } elseif ( $contrato == 3 ) {
      $meta_key_promocao = 'lancamentoPromocao';
    }

    $query = $wpdb->prepare( "
		SELECT
			t.term_id,
			t.name,
			t.slug,
			COUNT(tr.object_id) as promocao_count
		FROM {$wpdb->terms} t
		INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
		INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
		INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
		INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id
		INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id
		WHERE
			tt.taxonomy = 'tipo-imovel'
			AND p.post_type = 'imovel'
			AND p.post_status = 'publish'
			AND pm1.meta_key = 'statusImovel' AND pm1.meta_value = 'D'
			AND pm2.meta_key = %s AND pm2.meta_value = '1'
		GROUP BY
			t.term_id, t.name, t.slug
		ORDER BY
			promocao_count DESC
		LIMIT %d
	", $meta_key_promocao, $limit );

    return $wpdb->get_results( $query );
  }
}
