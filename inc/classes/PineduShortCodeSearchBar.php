<?php

class PineduShortCodeSearchBar implements PineduShortCode {
  private const TEMPLATE = './template-parts/pesquisa/barra-pesquisa.php';
  public static function do( $atts ) {

    $atts = shortcode_atts( array( 'titulo' => 'Pesquisa' ), $atts, 'barra-pesquisa' );
    ob_start( );
    $titulo = $atts['titulo'];
    $conteudo = $atts['conteudo'];
    $template_path = get_template_directory( ) . self::TEMPLATE;
    if ( file_exists( $template_path ) ) {
      include( $template_path );
    } else {
      error_log( 'Template não encontrado: ' . $template_path );
    }
    return ob_get_clean( );
  }
}
