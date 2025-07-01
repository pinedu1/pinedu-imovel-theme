<?php
$fotografias = get_post_meta( get_the_ID(), 'fotografias', false );
if ( !empty( $fotografias ) ): ?>
  <swiper-container init="false" class="imovel" style="height: 600px;">
  <?php foreach ( $fotografias as $fotografia ): $media_id = $fotografia['id']; ?>
    <?php if ( ! empty( $media_id ) ): ?>
      <swiper-slide>
        <div class="swiper-zoom-container">
            <?php echo wp_get_attachment_image($media_id, 'full', false, ['class' => 'imovel-foto']); ?>
        </div>
      </swiper-slide>
    <?php endif; ?>
  <?php endforeach; ?>
  </swiper-container>
<?php endif; ?>
<script>
  jQuery(document).ready(function($) {
    const swiperEl = $('swiper-container.imovel')[0];
    if (swiperEl) {
      swiperEl.initialize();
    }
  });
</script>
