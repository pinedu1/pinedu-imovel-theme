<?php
  $empresa = new \WP_Query( array( 'post_type' => 'empresa', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_query' => [ [ [ 'id'     => '1', 'value'   => '1', 'compare' => '=' ] ] ] ) );
  if ($empresa->have_posts()): ?>
<?php while ($empresa->have_posts()): $empresa->the_post(); global $post; ?>
<div class="social-network">
  <?php if ( isset( $post->faceBook ) && ( !empty( $post->faceBook ) ) ) : ?>
  <div class="social-contact faceBook">
    <a href="<?php echo esc_url($post->faceBook); ?>" target="_blank" rel="noopener noreferrer">
      <span class="dashicons dashicons-facebook-alt"></span>
      <span style="margin-left: 5px;">Facebook</span>
    </a>
  </div>
  <?php endif; ?>
  <?php if ( isset( $post->instagram ) && ( !empty( $post->instagram ) ) ) : ?>
    <div class="social-contact instagram">
      <a href="<?php echo esc_url($post->instagram); ?>" target="_blank" rel="noopener noreferrer">
        <span class="dashicons dashicons-instagram"></span>
        <span style="margin-left: 5px;">Instagram</span>
      </a>
    </div>
  <?php endif; ?>
  <?php if ( isset( $post->twitter ) && ( !empty( $post->twitter ) ) ) : ?>
    <div class="social-contact instagram">
      <a href="<?php echo esc_url($post->twitter); ?>" target="_blank" rel="noopener noreferrer">
        <span class="dashicons dashicons-x"></span>
        <span style="margin-left: 5px;">X</span>
      </a>
    </div>
  <?php endif; ?>
  <?php if ( isset( $post->googlePlus ) && ( !empty( $post->googlePlus ) ) ) : ?>
    <div class="social-contact instagram">
      <a href="<?php echo esc_url($post->googlePlus); ?>" target="_blank" rel="noopener noreferrer">
        <span class="dashicons dashicons-youtube"></span>
        <span style="margin-left: 5px;">Youtube</span>
      </a>
    </div>
  <?php endif; ?>
  <?php if ( isset( $post->whatsapp ) && ( !empty( $post->whatsapp ) ) ) : ?>
    <div class="social-contact whatsapp">
      <a href="<?php echo esc_url('https://api.whatsapp.com/send?1=pt_BR&phone=55' . $post->whatsapp . '&text=Olá! Estou visitando seu WebSite, e gostaria de uma atenção.'); ?>" target="_blank" rel="noopener noreferrer">
        <span class="dashicons dashicons-whatsapp"></span>
        <span style="margin-left: 5px;">WhatsApp</span>
      </a>
    </div>
  <?php endif; ?>
</div>
<?php endwhile; ?>
<?php endif; wp_reset_postdata(); ?>
