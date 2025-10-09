<?php
class Pinedu_Base {
  public static function corta_texto( $texto, $tamanho ): string {
    if (!is_string($texto)) {
      return '';
    }
    if ( ( strlen($texto) > $tamanho ) ) {
      return substr($texto, 0, $tamanho);
    }
    return $texto;
  }
  public static function formata_valor($valor, $decimais = 0, $moeda = ''): string {
    // Converte para float se for string numérica
    $valor = is_numeric($valor) ? (float)$valor : 0;

    $valor_formatado = '';

    if (!empty($moeda)) {
      $valor_formatado = 'R$ ';
    }

    return $valor_formatado . number_format($valor, $decimais, ',', '.');
  }
}
