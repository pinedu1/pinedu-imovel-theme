<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}
class Pinedu_Estatistica_Robusta {

  /**
   * Calcula o Ponto Central e o Desvio Absoluto de uma amostra isolando anomalias.
   *
   * @param array $dados Array unidimensional de valores (ex: preços)
   * @return array Contendo o 'pivo_central' e o 'mad' (Margem de variação)
   */
  public static function calcular_centro_robusto( array $dados ) {
    if ( empty( $dados ) ) {
      return [ 'pivo_central' => 0, 'mad' => 0 ];
    }

    // Ordena a amostra do menor para o maior
    sort( $dados );
    $n = count( $dados );

    // 1. Truncamento: Remove os 3% dos extremos (Corte de Cauda)
    $corte = (int) floor( $n * 0.03 );
    $dados_truncados = array_slice( $dados, $corte, $n - ( 2 * $corte ) );

    // Se a amostra for muito pequena e o corte zerar a array, usamos a original
    if ( empty( $dados_truncados ) ) {
      $dados_truncados = $dados;
    }

    // 2. Encontra o eixo de equilíbrio inabalável (Mediana)
    $mediana = self::calcular_mediana( $dados_truncados );

    // 3. Calcula o MAD (Median Absolute Deviation) - O quanto os dados variam do centro
    $desvios = [];
    foreach ( $dados_truncados as $v ) {
      $desvios[] = abs( $v - $mediana );
    }
    $mad = self::calcular_mediana( $desvios );

    // Se todos os imóveis tiverem o mesmo valor, o MAD é 0
    if ( $mad == 0 ) {
      return [ 'pivo_central' => $mediana, 'mad' => 0 ];
    }

    // 4. Média Ponderada por Densidade (Pesos Gaussianos)
    // O fator 1.4826 converte o MAD em uma aproximação de Desvio Padrão
    $sigma = $mad * 1.4826;
    $soma_valores_ponderados = 0;
    $soma_pesos = 0;

    foreach ( $dados_truncados as $v ) {
      $distancia = abs( $v - $mediana );

      // Fórmula do Peso Gaussiano: decai exponencialmente conforme se afasta do centro
      $peso = exp( -pow( $distancia, 2 ) / ( 2 * pow( $sigma, 2 ) ) );

      $soma_valores_ponderados += ( $v * $peso );
      $soma_pesos += $peso;
    }

    $media_robusta = $soma_pesos > 0 ? ( $soma_valores_ponderados / $soma_pesos ) : $mediana;

    return [
      'pivo_central' => $media_robusta,
      'mad'          => $mad
    ];
  }

  /**
   * Função auxiliar para calcular a Mediana de um array
   */
  private static function calcular_mediana( array $arr ) {
    $n = count( $arr );
    sort( $arr );
    $meio = (int) floor( $n / 2 );

    if ( $n % 2 == 0 ) {
      return ( $arr[ $meio - 1 ] + $arr[ $meio ] ) / 2.0;
    }
    return $arr[ $meio ];
  }
}
