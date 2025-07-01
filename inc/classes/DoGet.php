<?php

class DoGet	{
  public function do_get( $token, $server, $end_point, $args) {
    if ( !filter_var( $server, FILTER_VALIDATE_URL ) ) {
      wp_send_json_error( ['message' => 'URL inválida.'] );
    }
    $fullUrl = trailingslashit( $server ) . ltrim( $end_point, '/' );
    $fullUrl = add_query_arg( $args, $fullUrl );
    if ( !$args || ( $args == null ) || !is_array( $args ) ) {
      $args = array();
    }
    $headers = [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . sanitize_text_field( $token ) ];
    $response = wp_remote_get( $fullUrl, $headers );

    if ( is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      if ( is_wp_error( $response ) ) {
        wp_send_json_error( ['message' => $error_message ] );
      }
      return false;
    }
    $status_code = wp_remote_retrieve_response_code( $response );
    if ( $status_code === 200 ) {
      $body = wp_remote_retrieve_body( $response );
      $data = json_decode( $body );
      return $data;
    } else {
      return false;
    }
  }
}
