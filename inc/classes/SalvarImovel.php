<?php
/**
 * SalvarImovel
 */

/**
 * SalvarImovel
 */
class SalvarImovel {
  const ENDPOINT = '/wordpress/';
  public static function salvar( ) {
    $options = get_option( 'pinedu_imovel_options', [] );
    $server = $options['url_servidor'] ?? '';
    $token = $options['token'];
    // phpcs:disable WordPress.Security.NonceVerification.Missing
    if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && ( 'POST' === $_SERVER[ 'REQUEST_METHOD' ] ) && is_page( 'deixe-seu-imovel' ) ) {
      $boundary = uniqid( );
      $delimiter = '-------------' . $boundary;
      $credenciais = [ 'username' => urlencode( $options['token_username'] ), 'password' => urlencode( $options['token_password'] ) ];
      $post_data = self::build_multipart_data( array_merge( $_POST, $credenciais ), $_FILES, $delimiter );
      $response = wp_remote_post(
        $server . self::ENDPOINT . 'cadastrarImovel'
        , [
          'headers' => [
              'Content-Type' => 'multipart/form-data; boundary=' . $delimiter
              , 'Authorization' => 'Bearer ' . sanitize_text_field( $token )
            ]
          , 'body' => $post_data
          , 'timeout' => 600
        ]
      );
      if ( is_wp_error( $response ) ) {
        error_log( 'Response: ' . print_r( $response, true ) );
        wp_die( 'Erro ao enviar: ' . $response->get_error_message( ) );
      } else {
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        if ( json_last_error( ) !== JSON_ERROR_NONE ) {
          wp_send_json_error( [ 'message' => 'Erro ao decodificar JSON: ' . json_last_error_msg( ) ] );
          return false;
        }
        if ( isset( $data['success'] ) && ( true === $data['success'] ) ) {
          return $data;
        } else {
          return false;
        }
        exit;
      }
    }
  }
  // Função auxiliar para construir multipart manualmente
  private static function build_multipart_data( $fields, $files, $delimiter ) {
    $data = '';
    $fields['cookie'] = getCookieId( );
    foreach ( $fields as $name => $value ) {
      if ( is_array( $value ) ) {
        foreach ( $value as $item ) {
          $data .= "--$delimiter\r\n";
          $data .= "Content-Disposition: form-data; name=\"{$name}[]\"\r\n\r\n";
          $data .= "$item\r\n";
        }
      } else {
        $data .= "--$delimiter\r\n";
        $data .= "Content-Disposition: form-data; name=\"$name\"\r\n\r\n";
        $data .= "$value\r\n";
      }
    }
    foreach ( $files as $field_name => $file_data ) {
      $file_count = is_array( $file_data['name'] ) ? count( $file_data['name'] ) : 1;
      for ( $i = 0; $i < $file_count; $i++ ) {
        $name = is_array( $file_data['name'] ) ? $file_data['name'][ $i ] : $file_data['name'];
        $type = is_array( $file_data['type'] ) ? $file_data['type'][ $i ] : $file_data['type'];
        $tmp_name = is_array( $file_data['tmp_name'] ) ? $file_data['tmp_name'][ $i ] : $file_data['tmp_name'];
        if ( ! is_uploaded_file( $tmp_name ) ) continue;
        $contents = file_get_contents( $tmp_name );
        $data .= "--$delimiter\r\n";
        $data .= "Content-Disposition: form-data; name=\"{$field_name}[]\"; filename=\"$name\"\r\n";
        $data .= "Content-Type: $type\r\n\r\n";
        $data .= $contents . "\r\n";
      }
    }
    $data .= "--$delimiter--\r\n";
    return $data;
  }
  public static function validate_nonce( $request ) {
    $nonce = ( isset( $_REQUEST['property_nonce'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['property_nonce'] ) ) : '';
    $action = 'cadastro_imovel';
    $result_nonce = wp_verify_nonce( $nonce, $action );
    return ( false !== $result_nonce );
  }
}
