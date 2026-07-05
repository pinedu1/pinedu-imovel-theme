<?php
global $post;
$google_maps_key = get_google_maps_key( );

 if ( ( ! empty( $google_maps_key ) ) && ( ! empty( $post->latitude ) ) && ( 0 != $post->latitude ) && ( ! empty( $post->longitude ) ) && ( 0 != $post->longitude ) ) : ?>
  <?php
  $ponto = [ 'latitude' => $post->latitude, 'longitude' => $post->longitude ];
  $circulo = formatCoordinatesToCircle( drawCircle( $ponto, 250 ) );
  $args = [
    'center' => $post->latitude . ',' . $post->longitude,
    'zoom' => 16,
    'size' => '800x500',
    'key' => $google_maps_key,
    'maptype' => 'roadmap',
    'language' => 'pt-BR',
    'path' => 'color:0x0000ff|weight:1|fillcolor:0xaaaaaa|' . $circulo,
  ];
  $base_url = 'https://maps.googleapis.com/maps/api/staticmap';
  $static_map_url = $base_url . '?' . http_build_query( $args );
  ?>
<section class="imovel-mapa">
  <h4>Proximidades</h4>
  <div class="container">
    <figure id="mapa" class="mapa">
      <img src="<?php echo $static_map_url; ?>" alt="Proximidades do Imóvel">
      <figcaption>250m ao redor da localidade</figcaption>
    </figure>
  </div>
</section>
<?php endif; ?>
