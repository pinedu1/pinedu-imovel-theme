<?php
require_once __DIR__ . '/PineduShortCode.php';

class PineduShortInstituicaoFinanceira implements PineduShortCode {
 public static function do( $atts ) {
  error_log('PineduShortInstituicaoFinanceira::do');
    $atts = shortcode_atts(
        array(
            'titulo'   => 'Pesquisa',
            'conteudo' => ''
        ),
        $atts,
        'financeiras'
    );
    ob_start( );
    get_template_part( 'template-parts/shortcodes/instituicoes', 'financeiras', array(
        'titulo'   => $atts['titulo'],
        'conteudo' => $atts['conteudo']
    ) );
    return ob_get_clean( );
  }
}
