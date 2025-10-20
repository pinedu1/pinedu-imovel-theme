<?php
// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
global $post;
$empresa = getEmpresa( );
$captador_id = get_post_meta( $post->ID, 'captadorPrincipalId', true );
$corretor = getCorretor( $captador_id );
if ( $corretor && ! empty( $corretor->creci ) ): ?>
<div class="card-corretor">
  <div class="card-header">
    <div class="logo-lopes">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="branding-logo-mobile">
        <img
          src="<?php echo get_template_directory_uri( ); ?>/assets/images/logo_mobile_vermelho.png"
          alt="<?php bloginfo( 'name' ); ?>"
          class="branding-logo-mobile"
        />
      </a>
    </div>
  </div>
  <div class="card-content">
    <div class="avatar">
      <?php if ( 'M' === $corretor->sexo ): ?>
      <img
        src="<?php echo get_template_directory_uri( ); ?>/assets/images/avatar-corretor.png"
        alt="<?php $corretor->codNome; ?>"
        class="avatar-corretor"
      />
      <?php else : ?>
      <img
        src="<?php echo get_template_directory_uri( ); ?>/assets/images/avatar-corretora.png"
        alt="<?php $corretor->codNome; ?>"
        class="avatar-corretor"
      >
      <?php endif; ?>
    </div>
    <div class="info-container">
      <div class="info-textos">
        <h2 class="nome-usuario"><?php echo esc_attr( $corretor->codNome ); ?></h2>
        <p class="nome-completo"><?php echo esc_attr( $corretor->pessoa_nome ); ?></p>
        <p class="cargo">Corretor de Imóveis</p>
        <?php if ( ! empty( $corretor->creci ) ): ?>
          <?php echo '<p class="creci">Creci: ' . esc_attr( $corretor->creci ) . '</p>' ?>
        <?php elseif ( ! empty( $empresa->creci ) ): ?>
          <?php echo '<p class="creci">Creci: ' . esc_attr( $empresa->creci ) . '</p>' ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="card-footer">
    <a href="#" class="btn-contato">Falar com o corretor</a>
  </div>
</div>
<?php endif; ?>
