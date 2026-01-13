<?php
// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
global $post;
$empresa = getEmpresa( );
$captador_id = $post->captadorPrincipalId;
$referencia = $post->referencia;
$corretor = getCorretor( $captador_id );
$creci = '';
if ( !empty( $empresa->creci ) ) {
  $creci = $empresa->creci;
}
if ( !empty( $corretor->creci ) ) {
  $creci = $corretor->creci;
}
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
        <?php if ( !empty( $corretor->codNome ) ): ?>
          <h2 class="nome-usuario"><?php echo esc_attr( $corretor->codNome ); ?></h2>
          <p class="nome-completo"><?php echo esc_attr( $corretor->pessoa_nome ); ?></p>
        <?php else: ?>
          <h2 class="nome-usuario"><?php echo esc_attr( $corretor->pessoa_nome ); ?></h2>
        <?php endif; ?>
        <p class="cargo">Corretor de Imóveis</p>
        <?php echo '<p class="creci">' . esc_attr( $creci ) . '</p>' ?>
      </div>
    </div>
  </div>
  <div class="card-footer">
    <button class="cartao btn-contato" type="button" data-codigo-corretor="<?php echo $corretor->codigo; ?>" data-nome-corretor="<?php echo $corretor->pessoa_nome; ?>" data-referencia="<?php echo $referencia; ?>">
      Falar com o corretor
    </button>
  </div>
</div>
<?php endif; ?>
