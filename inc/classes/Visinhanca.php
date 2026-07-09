<?php
require_once get_template_directory() . '/inc/classes/PineduPostType.php';
require_once get_template_directory() . '/inc/classes/Pinedu_Base.php';

class Visinhanca extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/visinhanca/container.php';
  private const CARD = 'template-parts/visinhanca/card.php';

  private $class = 'visinhanca';
  private $max = 6;
  private $titulo = 'Imóveis mais Visitados'; // Nota: O construtor substitui para "Imóveis próximos"
  private $query;
  private $post;
  private $latitude;
  private $longitude;
  private $raio_km;
  public function __construct( $post, $titulo = 'Imóveis próximos', $max = 6, $raio_km = 0.5 ) {
    $this->titulo = $titulo;
    $this->max = $max;
    $this->post = $post;
    $this->raio_km = $raio_km;

    $this->latitude = (float) get_post_meta( $this->post->ID, 'latitude', true );
    $this->longitude = (float) get_post_meta( $this->post->ID, 'longitude', true );

    $this->query = $this->query();
  }
  private function get_ids_proximos( $lat, $lng, $raio_km = 0.5 ) {
      global $wpdb;
      $table_geo = $wpdb->prefix . 'pnd_geodata';

      // Fórmula de Haversine no SQL para performance máxima
      // 6371 é o raio da terra em KM
      $sql = $wpdb->prepare( "
          SELECT post_id
          FROM {$table_geo}
          WHERE (6371 * acos(
              cos(radians(%f)) * cos(radians(lat)) * cos(radians(lng) - radians(%f))
              + sin(radians(%f)) * sin(radians(lat))
          )) <= %f
      ", $lat, $lng, $lat, $raio_km );

      return $wpdb->get_col( $sql );
  }
  private function query_gis($raio_km = 0.5) {
    // Definir paged corretamente
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    // 1. Busca IDs geográficos
    $ids_proximos = $this->get_ids_proximos($this->latitude, $this->longitude, $raio_km);
    $ids_proximos = array_diff($ids_proximos, [$this->post->ID]);

    if (empty($ids_proximos)) {
        return new \WP_Query(['post__in' => [0]]);
    }

    // 2. Define filtros padrão
    $metaQuery = [
      'relation' => 'AND',
      ['key' => 'statusImovel', 'value' => 'D', 'compare' => '='],
      ['key' => 'referencia', 'value' => get_post_meta($this->post->ID, 'referencia', true), 'compare' => '!=']
    ];

    if (get_post_meta($this->post->ID, 'ativarVenda', true) == '1') $metaQuery[] = ['key' => 'ativarVenda', 'value' => '1', 'compare' => '='];
    if (get_post_meta($this->post->ID, 'ativarLocacao', true) == '1') $metaQuery[] = ['key' => 'ativarLocacao', 'value' => '1', 'compare' => '='];
    if (get_post_meta($this->post->ID, 'ativarLancamento', true) == '1') $metaQuery[] = ['key' => 'ativarLancamento', 'value' => '1', 'compare' => '='];

    $args = [
      'post_type'      => 'imovel',
      'post_status'    => 'publish',
      'posts_per_page' => $this->max,
      'paged'          => $paged,
      'post__in'       => $ids_proximos,
      'meta_query'     => $metaQuery,
      'orderby'        => 'post__in',
      'update_post_meta_cache' => true,
      'update_post_term_cache' => true
    ];

    $transient_key = 'pnd_visinhanca_gis_ids_' . md5(serialize($args));
    $cached_data = get_transient($transient_key);

    if (false !== $cached_data && is_array($cached_data)) {
        $query = new \WP_Query([
            'post_type'      => 'imovel',
            'post__in'       => $cached_data['ids'],
            'posts_per_page' => $this->max,
            'paged'          => $paged, // CORRIGIDO: Aqui respeita a página solicitada
            'orderby'        => 'post__in'
        ]);
        $query->found_posts = $cached_data['total'];
        $query->max_num_pages = ceil($cached_data['total'] / $this->max);
        return $query;
    }

    $query = new \WP_Query($args);
    $data_to_cache = [
        'ids'   => $query->have_posts() ? wp_list_pluck($query->posts, 'ID') : [],
        'total' => $query->found_posts
    ];
    set_transient($transient_key, $data_to_cache, 10 * MINUTE_IN_SECONDS);

    return $query;
  }

  private function query_default() {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1; // CORRIGIDO: Inicializado

    $metaQuery = array(
      'relation' => 'AND'
      , [ 'key' => 'statusImovel', 'value' => 'D', 'compare' => '=' ]
      , [ 'key' => 'referencia', 'value' => get_post_meta( $this->post->ID, 'referencia', true ), 'compare' => '!=' ]
    );

    $venda      = get_post_meta( $this->post->ID, 'ativarVenda', true );
    $locacao    = get_post_meta( $this->post->ID, 'ativarLocacao', true );
    $lancamento = get_post_meta( $this->post->ID, 'ativarLancamento', true );
    $regiao     = get_post_meta( $this->post->ID, 'regiaoCorretagem', true );

    if ( $venda === '1' ) $metaQuery[] = [ 'key' => 'ativarVenda', 'value' => '1', 'compare' => '=' ];
    if ( $locacao === '1' ) $metaQuery[] = [ 'key' => 'ativarLocacao', 'value' => '1', 'compare' => '=' ];
    if ( $lancamento === '1' ) $metaQuery[] = [ 'key' => 'ativarLancamento', 'value' => '1', 'compare' => '=' ];
    $metaQuery[] = [ 'key' => 'regiaoCorretagem', 'value' => $regiao, 'compare' => '=' ];

    $args = array(
      'post_type'      => 'imovel',
      'post_status'    => 'publish',
      'posts_per_page' => $this->max,
      'paged'          => $paged,
      'meta_query'     => $metaQuery,
      'update_post_meta_cache' => true,
      'update_post_term_cache' => true
    );

    $transient_key = 'pnd_visinhanca_ids_v1_' . md5( serialize( $args ) );
    $cached_data = get_transient( $transient_key );

    if ( false !== $cached_data && is_array( $cached_data ) ) {
        $cached_ids  = $cached_data['ids'];
        $total_posts = $cached_data['total'];

        if ( empty( $cached_ids ) ) return new \WP_Query( ['post__in' => [0]] );

        $query = new \WP_Query( [
            'post_type'              => 'imovel',
            'post__in'               => $cached_ids,
            'posts_per_page'         => $this->max,
            'paged'                  => $paged,
            'orderby'                => 'post__in',
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ] );

        $query->found_posts = $total_posts;
        $query->max_num_pages = ceil( $total_posts / $this->max );

        return $query;
    }

    $query = new \WP_Query( $args );
    $ids_para_salvar = $query->have_posts() ? wp_list_pluck( $query->posts, 'ID' ) : [];
    $data_to_cache = [ 'ids' => $ids_para_salvar, 'total' => $query->found_posts ];
    set_transient( $transient_key, $data_to_cache, 10 * MINUTE_IN_SECONDS );

    return $query;
  }
  public function query() {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $options = get_option( 'pinedu_imovel_options', [] );
    // Switch de decisão baseado na flag de geoposicao
    if (($this->latitude != 0) && ($this->longitude != 0) && !empty($options['geoposicao_nativa']) && $options['geoposicao_nativa'] === true) {
        return $this->query_gis( $this->raio_km );
    }
    return $this->query_default();
  }

  // ==========================================
  // MÉTODO DE PAGINAÇÃO AJAX
  // ==========================================
  public static function paginar_visinhanca() {
    if ( function_exists('is_development_mode') && is_development_mode() ) {
        error_log( 'paginar_visinhanca recebido: ' . print_r( $_REQUEST, true ) );
    }

    // 1. O JS precisa obrigatoriamente enviar o post_id
    $post_id = isset( $_REQUEST['post_id'] ) ? intval( $_REQUEST['post_id'] ) : 0;
    // Captura a página solicitada (o JS deve enviar 'paged')
    $requested_paged = isset( $_REQUEST['paged'] ) ? intval( $_REQUEST['paged'] ) : 1;
    // Definir o paged globalmente para que a WP_Query dentro da classe o reconheça
    set_query_var('paged', $requested_paged);
    if ( empty( $post_id ) ) {
        wp_send_json_error( [
            'message' => 'O ID do imóvel é obrigatório para carregar a vizinhança.',
            'error_code' => 'post_id_required'
        ], 400 );
    }

    // 2. Tenta recuperar o post real do banco
    $post = get_post( $post_id );

    if ( ! $post || $post->post_type !== 'imovel' ) {
        wp_send_json_error( [
            'message' => 'Imóvel referência não encontrado.',
            'error_code' => 'post_not_found'
        ], 404 );
    }
    // 3. Resgata os demais parâmetros visuais
    $max = isset( $_REQUEST['max'] ) ? intval( $_REQUEST['max'] ) : 6;
    $titulo = isset( $_REQUEST['titulo'] ) ? sanitize_text_field( $_REQUEST['titulo'] ) : 'Imóveis próximos';

    // 4. Instancia a classe fornecendo o $post recém resgatado
    $v = new Visinhanca( $post, $titulo, $max );


    // 5. Inicia o buffer, renderiza o template, coleta o HTML e encerra o script
    ob_start();
    $v->render();
    $html = ob_get_clean();

    echo $html;
    wp_die();
  }

  public function render() {
    if ( $this->query->have_posts( ) ) {
      try {
        $posts = $this->query->posts;
        if ( is_array( $posts ) && ! empty( $posts ) ) {
            $post_ids = wp_list_pluck( $posts, 'ID' );
            if ( ! empty( $post_ids ) ) {
                update_post_caches( $posts, 'imovel', true, true );
            }
        }
        add_filter('the_title', [$this, 'pinedu_visinhanca_titulo']);
        add_filter('the_content', [$this, 'pinedu_visinhanca_conteudo']);
        include locate_template(self::TEMPLATE);
        wp_reset_postdata();
        remove_filter('the_title', [$this, 'pinedu_visinhanca_titulo']);
        remove_filter('the_content', [$this, 'pinedu_visinhanca_conteudo']);
      } catch ( \Throwable $e ) {
          // Captura o erro e escreve no wp-content/debug.log
          error_log( 'DEBUG PINEDU RENDER ERROR: ' . $e->getMessage() . ' em ' . $e->getFile() . ':' . $e->getLine() );
          // Remove os filtros mesmo que ocorra um erro para não quebrar o resto da página
          remove_filter( 'the_title', [ $this, 'pinedu_visinhanca_titulo' ] );
          remove_filter( 'the_content', [ $this, 'pinedu_visinhanca_conteudo' ] );
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
