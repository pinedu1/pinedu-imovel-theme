<?php
/**
 * The template Contato
 */

$empresaQuery = new \WP_Query( array( 'post_type' => 'empresa', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_query' => [ [ [ 'id'     => '1', 'value'   => '1', 'compare' => '=' ] ] ] ) );
$redes = array();
$dados = array();
if ($empresaQuery->have_posts()) {
  while ( $empresaQuery->have_posts() ) {
    $empresaQuery->the_post();
    global $post;
    $faceBook = get_post_meta( $post->ID, 'faceBook', true );
    if ( ( !empty( $faceBook ) ) ) {
      array_push( $redes, [ 'icon' => 'fa-brands fa-facebook', 'value' => $faceBook ] );
    }
    $instagram = get_post_meta( $post->ID, 'instagram', true );
    if ( ( !empty( $instagram ) ) ) {
      array_push( $redes, [ 'icon' => 'fa-brands fa-instagram', 'value' => $instagram ] );
    }
    $twitter = get_post_meta( $post->ID, 'twitter', true );
    if ( ( !empty( $twitter ) ) ) {
      array_push( $redes, [ 'icon' => 'fa-brands fa-twitter', 'value' => $twitter ] );
    }
    $googlePlus = get_post_meta( $post->ID, 'googlePlus', true );
    if ( ( !empty( $googlePlus ) ) ) {
      array_push( $redes, [ 'icon' => 'fa-brands fa-youtube', 'value' => $googlePlus ] );
    }
    $endereco = get_post_meta( $post->ID, 'endereco', true );
    set_query_var( 'endereco', $endereco );
    $endereco_codificado = urlencode($endereco);
    array_push( $dados, [ 'type' => '', 'label' => 'Endereço', 'icon' => 'fa-solid fa-location-dot', 'value' => $endereco ] );
    $telefones = get_post_meta( $post->ID, 'telefone', false );
    foreach ( $telefones as $telefone ) {
      array_push( $dados, [ 'type' => 'tel:', 'label' => 'Telefone', 'icon' => 'fa-solid fa-phone', 'value' => formata_telefone($telefone) ] );
    }
    $email_contato = get_post_meta( $post->ID, 'email', true );
    if ( ! empty( $email_contato ) ) {
      array_push( $dados, [ 'type' => 'mailto:', 'label' => 'Email', 'icon' => 'fa-solid fa-envelope', 'value' => $email_contato ] );
    }
  }
}

the_post();
get_header(); ?>
<main class="site-main">
  <section class="contact">
    <div class="container">
      <aside>
        <?php get_template_part( 'template-parts/contato/contato-form', 'imovel' ); ?>
      </aside>
      <aside>
        <div class="contact-info">
          <?php foreach ($dados as $d): ?>
            <div class="contact-item">
              <?php if ( !empty( $d['type'] ) ): ?>
              <a href="<?php echo ( empty( $d['type'] )? '': $d['type']) . $d['value']; ?>" target="_blank" >
                <?php endif; ?>
                <i class="<?php echo $d['icon'];  ?>"></i>
                <div class="contact-text">
                  <h5><?php echo $d['label'];  ?></h5>
                  <p><?php echo $d['value'];  ?></p>
                </div>
                <?php if ( !empty( $d['type'] ) ): ?>
              </a>
            <?php endif; ?>
            </div>
          <?php endforeach; ?>
          <?php if ( !empty( $redes ) ): ?>
            <div class="contact-item">
            <?php foreach ($redes as $r): ?>
              <a href="<?php echo $r['value']; ?>" target="_blank" ><i class="<?php echo $r['icon'];  ?>"></i></a>
            <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </aside>
    </div>
    <?php get_template_part( 'template-parts/empresa/google-maps', 'imovel' ); ?>
  </section>
</main>
<?php get_footer();
