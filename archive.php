<?php
namespace Air_Light;
/* Começa aqui
global $wp_query;
echo '<pre>';
print_r( $wp_query->query_vars );
echo '</pre>';
Termina aqui */

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
            <?php while ( have_posts() ): the_post(); ?>
              <?php if ( $post->ID != null ): ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  <?php echo get_template_part('template-parts/pesquisa/card', 'imovel'); ?>
                </article>
              <?php endif; ?>
            <?php endwhile; ?>
          </div>
          <?php
          $my_args = [];
          $aux = [ 'cidade', 'contrato', 'faixa-valor', 'max', 'ordem', 'post_type', 'regiao', 'sort', 'tipo-imovel', 'valor-final', 'valor-inicial' ];
          foreach ( $aux as $k ) {
            if ( !empty( $wp_query->query_vars[ $k ] ) ) {
              $my_args[ $k ] = $wp_query->query_vars[ $k ];
            }
          }
          the_posts_pagination( );
          ?>
        <?php endif; ?>

      </div>
    </section>
  </div>
</main>
<?php get_footer();
