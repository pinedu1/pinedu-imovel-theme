<?php
/**
 * The template for displaying front page
 *
 * Contains the closing of the #content div and all content after.
 * Initial styles for front page template.
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2022-05-25 20:18:40
 *
 * @package air-light
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

namespace Air_Light;

// Featured image for Theme Checker (it's a requirement for theme to pass in official Theme directory)
// NB! Our dev version uses newtheme.sh build script which cleans ups things including this next line
$thumbnail = wp_get_attachment_url( get_post_thumbnail_id() ) ?: THEME_SETTINGS['default_featured_image'];

/**
 * Template Name: Página Inicial
 */

get_header();
?>
  <main id="main" class="site-main" role="main">
    <?php echo get_template_part('template-parts/slider-home-topo'); ?>
    <div class="container">
      <?php echo get_template_part('template-parts/pesquisa/barra-pesquisa'); ?>
      <section class="content promocao">
        <div class="container">
          <!-- Promoções de Venda -->
          <?php set_query_var('promocao_contrato', '1'); ?>
          <?php echo get_template_part('template-parts/promocao'); ?>
          <!-- Promoções de Locação -->
          <?php set_query_var('promocao_contrato', '2'); ?>
          <?php echo get_template_part('template-parts/promocao'); ?>
          <!-- Promoções de Lançamento -->
          <?php set_query_var('promocao_contrato', '3'); ?>
          <?php echo get_template_part('template-parts/promocao'); ?>
        </div>
      </section>
      <section class="content nuvem-tag">
        <div class="container">
          <header><h3>Links úteis</h3></header>
          <?php echo get_template_part('template-parts/nuvem-tag/cidade'); ?>
          <?php echo get_template_part('template-parts/nuvem-tag/tipo-imovel'); ?>
          <?php echo get_template_part('template-parts/nuvem-tag/regiao'); ?>
        </div>
      </section>
      <section class="content visitados">
        <div class="container">
          <?php echo get_template_part('template-parts/visitados'); ?>
        </div>
      </section>
    </div>
  <!--<?php air_edit_link(); ?>-->
</main>
<?php get_footer();
