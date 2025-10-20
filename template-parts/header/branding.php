<?php
// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
/**
 * Site branding & logo
 *
 * @package air-light
 */

namespace Air_Light;

$description = get_bloginfo( 'description', 'display' );
?>

<section class="site-branding">
  <header class="branding-header">
    <div class="branding-row">
      <div class="branding-logo">
        <?php the_custom_logo(); ?>
      </div>
      <div class="branding-logo-mobile">
        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>" class="branding-logo-mobile">
          <img
            src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_mobile_azul_invertido.png"
            alt="<?php bloginfo('name'); ?>"
            class="branding-logo-mobile"
          />
        </a>
      </div>
      <h1 class="branding-title">
        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
          <?php bloginfo('name'); ?>
        </a>
      </h1>
    </div>
    <!-- Linha da Descrição -->
    <?php if ( $description || is_customize_preview() ) : ?>
      <p class="branding-description site-description">
        <?php echo esc_html( $description ); ?>
      </p>
    <?php else : ?>
      <p class="branding-description site-description">
        <?php echo esc_html( 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."' ); ?>
      </p>
    <?php endif; ?>
    <?php if ( ! empty( $telefonePadrao ) ) : ?>
    <?php endif; ?>
  </header>
</section>
