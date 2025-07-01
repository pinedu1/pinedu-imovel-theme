<?php
  $options = get_option('pinedu_imovel_options', []);
  if ( isset( $options['chave_google_api'] ) ) {
    $chave_google_api = $options['chave_google_api'];
  }
  $endereco = get_query_var( 'endereco' );
  $largura = '100%';
  $altura = 400;
  $zoom = 15;
  $base_url = 'https://www.google.com';
  $path = '/maps/embed/v1/place';
  $query_params = array(
    'key' => $options['chave_google_api']
  , 'q' => $endereco
  , 'zoom' => $zoom
  , 'maptype' => 'roadmap'
  );
  $query_string = http_build_query($query_params);
  $url = $base_url . $path . '?' . $query_string;

if ( !empty( $chave_google_api ) && !empty( $endereco ) ): ?>
<section class="localizacao">
  <header>
    <h3>Onde nos encontrar?</h3>
  </header>
  <article class="map">
    <iframe width='<?php echo $largura; ?>' height="<?php echo $altura; ?>" style="border:0" src="<?php echo $url;?>" allowfullscreen referrerpolicy="no-referrer-when-downgrade" loading="lazy"></iframe>
  </article>
</section>
<?php endif; ?>
