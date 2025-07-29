<?php
  require_once get_template_directory() . '/inc/classes/DoGet.php';
class Gpb extends DoGet {
  const ENDPOINT = '/pndPortal/wordpress/';
  private $url;
  private $token;
  public function __construct() {
    $options = get_option('pinedu_imovel_options', []);
    $this->url = $options['url_servidor'] ?? '';
    $this->token = $options['token'];
  }
  public function localizacao( $tipoImovel = 1 ) {
    return $this->do_get( $this->token, $this->url, self::ENDPOINT . 'localizacao', [ 'tipoImovel' => $tipoImovel ] );
  }
  public function estados( $estado = '' ) {
    return $this->do_get( $this->token, $this->url, self::ENDPOINT . 'estados', [ 'uf' => $estado ] );
  }
  public function cidades( $estado = '', $cidade = '' ) {
    return $this->do_get( $this->token, $this->url, self::ENDPOINT . 'cidades', [ 'uf' => $estado, 'cidade' => $cidade ] );
  }
  public function bairros( $estado = '', $cidade = '', $bairro = '' ) {
    return $this->do_get( $this->token, $this->url, self::ENDPOINT . 'bairros', [ 'uf' => $estado, 'cidade' => $cidade, 'bairro' => $bairro ] );
  }
  public function auto_complete( $estado = '', $cidade = '', $nome = '' ) {
    return $this->do_get( $this->token, $this->url, self::ENDPOINT . 'autocompleteEndereco', [ 'uf' => $estado, 'cidade' => $cidade, 'nome' => $nome ] );
  }
}
