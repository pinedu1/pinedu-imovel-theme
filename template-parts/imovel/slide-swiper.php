<?php
$fotografias = get_post_meta( get_the_ID(), 'fotografias', false );
$swiper_breakpoints = [
  '640' => [
    'slidesPerView' => 1,
    'spaceBetween'  => 10,
  ],
  '768' => [
    'slidesPerView' => 1,
    'spaceBetween'  => 20,
  ],
  '1024' => [
    'slidesPerView' => 1,
    'spaceBetween'  => 30,
  ],
  '1200' => [
    'slidesPerView' => 1,
    'spaceBetween'  => 40,
  ]
];

$breakpoints_json = json_encode($swiper_breakpoints, JSON_HEX_QUOT);

if ( !empty( $fotografias ) ): ?>
  <swiper-container
    class="caroussel"
    slides-per-view="auto"
    space-between="10"
    navigation="true"
    pagination="true"
    pagination-clickable="true"
    scrollbar="false"
    keyboard="true"
    loop="true"
    breakpoints='<?php echo $breakpoints_json; ?>' >

    <div class="container-start" slot="container-start">Rendered before wrapper</div>
    <div class="container-end" slot="container-end"></div>
    <?php foreach ( $fotografias as $fotografia ): $media_id = $fotografia['id']; ?>
      <?php if ( ! empty( $media_id ) ): ?>
      <swiper-slide ><?php echo wp_get_attachment_image($media_id, 'full', false, ['class' => 'imovel-foto']); ?></swiper-slide>
      <?php endif; ?>
    <?php endforeach; ?>
  </swiper-container>
<?php endif; ?>
<style>

</style>
