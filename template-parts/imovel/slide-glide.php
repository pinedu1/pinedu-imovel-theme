<?php
$fotografias = get_post_meta( get_the_ID(), 'fotografias', false );
if ( !empty( $fotografias ) ): ?>
<section class="fotos">
  <div class="container">
    <div id="imovel-carousel" class="glide">
      <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
          <?php foreach ( $fotografias as $fotografia ): $media_id = $fotografia['id']; ?>
            <?php if ( ! empty( $media_id ) ): ?>
              <li class="glide__slide"><?php echo wp_get_attachment_image($media_id, 'full', false, ['class' => 'imovel-foto']); ?></li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </div>
      <!-- Setas de navegação -->
      <div class="glide__arrows" data-glide-el="controls">
        <button class="glide__arrow glide__arrow--left" data-glide-dir="<">«</button>
        <button class="glide__arrow glide__arrow--right" data-glide-dir=">">»</button>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
