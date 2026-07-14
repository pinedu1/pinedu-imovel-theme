<?php
$instituicoes = new \WP_Query( array(
    'post_type'      => 'financeira',
    'post_status'    => 'publish',
    'posts_per_page' => -1
) );
if ( $instituicoes->have_posts() ) : ?>
  <h4><strong><?= $args['titulo']; ?></strong></h4>
  <div class="instituicoes">
    <?php while ( $instituicoes->have_posts() ) : $instituicoes->the_post();
        $link_banco = get_post_meta( get_the_ID(), 'link', true );
        $link_simular = get_post_meta( get_the_ID(), 'link_simular', true );
    ?>
    <div class="card">
      <?php if ( has_post_thumbnail() ) : ?>
          <div class="imagem-destacada">
              <?php the_post_thumbnail( 'full' ); ?>
          </div>
      <?php endif; ?>
      <div class="titulo"><strong><?php the_title(); ?></strong></div>
      <?php if ( has_excerpt() ) : ?>
          <p class="subtitulo"><?php echo get_the_excerpt(); ?></p>
      <?php endif; ?>
      <div class="botoes-card">
          <?php if ( ! empty( $link_banco ) ) : ?>
              <a href="<?php echo esc_url( $link_banco ); ?>" class="btn-banco" target="_blank">Ir para <?php the_title(); ?></a>
          <?php endif; ?>
          <?php if ( ! empty( $link_simular ) ) : ?>
              <a href="<?php echo esc_url( $link_simular ); ?>" class="btn-simular" target="_blank">Simular financimento</a>
          <?php endif; ?>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php wp_reset_postdata(); ?>
<?php endif; ?>
