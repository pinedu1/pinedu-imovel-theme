<?php
/**
 * Template for header
 *
 * <head> section and everything up until <div id="content">
 *
 * @package air-light
 */
// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase

namespace Air_Light;

$empresa = getEmpresa(1);
if ( isset( $empresa ) && isset( $empresa->telefonePadrao) ) {
  $telefonePadrao = $empresa->telefonePadrao;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'no-js' ); ?>>
  <a class="skip-link screen-reader-text" href="#content"><?php echo esc_html( get_default_localization( 'Skip to content' ) ); ?></a>
  <?php wp_body_open(); ?>
  <div id="page" class="site">
    <header class="site-header">
      <div class="site-header">
        <?php get_template_part( 'template-parts/header/branding' ); ?>
        <?php get_template_part( 'template-parts/header/navigation' ); ?>
      </div>
      <?php if ( ! empty( $telefonePadrao ) ): ?>
      <div class="central-atendimento">
        <a href="tel:<?php echo $telefonePadrao; ?> title="Acesse nossa Central de atendimento <?php echo formata_telefone( $telefonePadrao ); ?>">
        <img
          src="<?php echo get_template_directory_uri(); ?>/assets/images/central_atendimento_vermelho.png"
          alt="Acesse nossa Central de atendimento <?php echo formata_telefone( $telefonePadrao ); ?>"
          class="central-atendimento"
          style="max-width: 100%; height: auto;"
        />
        </a>
      </div>
      <?php endif; ?>
    </header>
    <div class="site-content">
