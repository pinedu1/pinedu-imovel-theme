<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2022-02-08 17:03:18
 *
 * @package air-light
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */
namespace Air_Light;
/* Começa aqui
global $wp_query;
echo '<pre>';
print_r( $wp_query->query_vars );
print_r( $_REQUEST );
echo '</pre>';
Termina aqui  */

get_header(); ?>
<main class="site-main">
  <?php echo get_template_part('template-parts/slider-home-topo'); ?>
  <div class="content">
    <?php
      get_template_part( 'template-parts/pesquisa/sidebar-pesquisa' , 'imovel' );
    ?>
    <section class="block block-blog">
      <header>
        <h3>Resultado da Pesquisa</h3>
        <?php echo get_template_part('template-parts/pesquisa/meta-pesquisa', 'imovel'); ?>
      </header>
      <div class="container">
        <?php if ( have_posts() ) : ?>
          <?php if ( false && is_home() && ! is_front_page() ) : ?>
            <h1 id="content" class="screen-reader-text"><?php single_post_title(); ?></h1>
          <?php endif; ?>
          <div class="pesquisa-grid">
            <div class="cortina-aguarde inactive">
              <div class="loading-spinner"></div>
              <span>Aguarde...</span>
            </div>
            <?php while ( have_posts() ): the_post(); ?>
            <?php if ( ($post->ID != null) && get_post_type( $post->ID ) === 'imovel' ): ?>
                <?php echo get_template_part('template-parts/pesquisa/card', 'imovel'); ?>
            <?php endif; ?>
            <?php endwhile; ?>
          </div>
          <?php
          $my_args = array( );
          $my_par = [ 'contrato', 'cidade', 'regiao', 'tipo-imovel', 'faixa-valor', 'tipo_pesquisa_submit', 'valor-final', 'valor-inicial', 'max', 'ordem', 'post_type', 'sort' ];
          foreach ( $my_par as $par ) {
            if ( !empty( $_REQUEST[ $par ] ) ) {
              $my_args[ $par ] = $_REQUEST[ $par ];
            }
          }
          the_posts_pagination( array( 'add_args'  => $my_args ) );
          ?>
        <?php endif; ?>
      </div>
    </section>
    <?php
      // Novo formato com acessibilidade
      wp_tag_cloud(array(
        'smallest' => 12,
        'largest' => 24,
        'unit' => 'px',
        'format' => 'flat',
        'separator' => "\n",
        'orderby' => 'count',
        'order' => 'DESC',
        'show_count' => 1,
        'echo' => true,
        'taxonomy' => 'post_tag',
        'aria_label' => __('Nuvem de tags') // Acessibilidade
      ));

    ?>
  </div>
</main>
<?php get_footer();
