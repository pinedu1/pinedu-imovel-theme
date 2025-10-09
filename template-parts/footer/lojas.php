<?php
  wp_reset_postdata();
  $lojas = new \WP_Query( array( 'post_type' => 'loja', 'post_status' => 'publish', 'posts_per_page' => 6 ) );
if ($lojas->have_posts()): ?>
  <?php while ($lojas->have_posts()): $lojas->the_post(); global $post; ?>
  <div class="store">
    <h4 class="store-name"><?php echo $post->nome; ?></h4>
    <address class="store-address">
      <?php
        $endereco = Air_Light\formata_endereco_loja( $post->tipo, $post->logradouro, $post->numero, $post->complemento, $post->bairro, $post->cidade, $post->estado, $post->cep );
        $endereco_codificado = urlencode($endereco);
        ?>
      <?php echo '<a href="https://www.google.com/maps/search/?api=1&query=' . esc_attr($endereco_codificado) . '" target="_blank" title="Ver no Google Maps">' . $endereco . '</a>'; ?>
    </address>
    <div class="contact">
      <?php
        $emails = get_post_meta( $post->ID, 'telefone', false );
        if ( ! empty( $emails ) ): foreach ( $emails as $telefone ): ?>
          <div class="store-contact"><a href="telto:<?php echo esc_attr($telefone); ?>"><?php echo esc_html(formata_telefone($telefone)); ?></a></div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="contact">
      <?php
        $emails = get_post_meta( $post->ID, 'email', false );
        if ( ! empty( $emails ) ): foreach ( $emails as $email ): ?>
          <div class="store-contact"><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
  </div>
  <?php endwhile; ?>
  <?php wp_reset_postdata(); ?>
<?php endif; ?>
