<?php

class SalvarImovel {
  const ENDPOINT = '/pndWordpress/api/';
  public static function salvar() {
    $options = get_option('pinedu_imovel_options', []);
    $server = $options['url_servidor'] ?? '';
    $token = $options['token'];
    if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' && is_page( 'deixe-seu-imovel' ) ) {
      // 1. (Opcional) Valide campos aqui
      // 2. Constrói o POST multipart para o servidor Java
      $boundary = uniqid();
      $delimiter = '-------------' . $boundary;
      $postData = self::build_multipart_data( $_POST, $_FILES, $delimiter );

      $response = wp_remote_post(
        $server . self::ENDPOINT . 'cadastrarImovel'
        , [ 'headers' =>
          [
            'Content-Type' => "multipart/form-data; boundary=" . $delimiter
            , 'Authorization' => 'Bearer ' . sanitize_text_field( $token )
          ]
          , 'body' => $postData, 'timeout' => 60
        ]
      );
      // 3. Trate a resposta do seu servidor Java
      if ( is_wp_error( $response ) ) {
        wp_die( 'Erro ao enviar: ' . $response->get_error_message() );
      } else {
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        if ( json_last_error( ) !== JSON_ERROR_NONE ) {
          wp_send_json_error( ['message' => 'Erro ao decodificar JSON: ' . json_last_error_msg( )] );
          return false;
        }
        if ( isset( $data['success'] ) && $data['success'] === true ) {
          return $data;
        } else {
          return false;
        }
        exit;
      }
    }
  }
  // Função auxiliar para construir multipart manualmente
  private static function build_multipart_data($fields, $files, $delimiter) {
    $data = '';
    $fields['cookie'] = getCookieId();
    foreach ($fields as $name => $value) {
      if (is_array($value)) {
        foreach ($value as $item) {
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
    foreach ($files as $fieldName => $fileData) {
      $fileCount = is_array($fileData['name']) ? count($fileData['name']) : 1;
      for ($i = 0; $i < $fileCount; $i++) {
        $name = is_array($fileData['name']) ? $fileData['name'][$i] : $fileData['name'];
        $type = is_array($fileData['type']) ? $fileData['type'][$i] : $fileData['type'];
        $tmpName = is_array($fileData['tmp_name']) ? $fileData['tmp_name'][$i] : $fileData['tmp_name'];
        if (!is_uploaded_file($tmpName)) continue;
        $contents = file_get_contents($tmpName);
        $data .= "--$delimiter\r\n";
        $data .= "Content-Disposition: form-data; name=\"{$fieldName}[]\"; filename=\"$name\"\r\n";
        $data .= "Content-Type: $type\r\n\r\n";
        $data .= $contents . "\r\n";
      }
    }
    $data .= "--$delimiter--\r\n";
    return $data;
  }
  public static function validate_nonce( $request ) {
    $nonce = $_REQUEST["property_nonce"];
    $action = 'property_submission_nonce';
    $result_nonce = wp_verify_nonce($nonce, $action);
    return ( $result_nonce !== false);
  }
}
