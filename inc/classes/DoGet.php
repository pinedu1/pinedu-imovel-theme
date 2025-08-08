<?php

class DoGet	{
  public function do_get( $token, $server, $end_point, $args = array( ) ) {
    if ( !filter_var( $server, FILTER_VALIDATE_URL ) ) {
      return new WP_Error( 'invalid_url', 'URL inválida.' );
    }
    $options = get_option( 'pinedu_imovel_options', [ ] );
    $credenciais = [ 'username' => urlencode( $options['token_username'] ), 'password' => urlencode( $options['token_password'] ) ];
    $fullUrl = trailingslashit( $server ) . ltrim( $end_point, '/' );

    if ( empty( $args ) || !is_array( $args ) ) {
      $args = [ ];
    }
    $args[ ] = $credenciais;
    $fullUrl = add_query_arg( $args, $fullUrl );
    $request_args = [
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . sanitize_text_field( $token )
      ],
      'timeout' => 30
    ];
    $response = wp_remote_get( $fullUrl, $request_args );
    if ( is_wp_error( $response ) ) {
      return $response;
    }
    $status_code = wp_remote_retrieve_response_code( $response );
    $body = wp_remote_retrieve_body( $response );
    if ( $status_code !== 200 ) {
      return new WP_Error( 'api_error', 'Erro na API', [
        'status' => $status_code,
        'body' => $body
      ] );
    }
    return json_decode( $body );
  }
}
