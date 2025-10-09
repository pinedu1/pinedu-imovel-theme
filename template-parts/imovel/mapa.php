<?php
global $post;
$google_maps_key = get_google_maps_key();
?>
<?php if ( (!empty($google_maps_key)) && ( !empty( $post->latitude ) ) && ($post->latitude != 0 ) && ( !empty( $post->longitude ) ) && ( $post->longitude != 0 ) ): ?>
<section id="detalhes" class="imovel-mapa">
  <h4>Proximidades</h4>
  <div class="container">
    <img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $post->latitude; ?>,<?php echo $post->longitude; ?>&amp;zoom=15&amp;size=750x450&amp;key=<?php echo $google_maps_key; ?>" alt="Proximidades do Imóvel" style="width: 750px; height: 450px;">
  </div>
</section>
<?php endif; ?>
