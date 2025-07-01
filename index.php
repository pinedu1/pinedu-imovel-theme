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

get_header(); ?>
<main class="site-main">
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
          the_posts_pagination(
            array(
              'add_args'  => array(
                'cidade' => $_REQUEST['cidade'] ?? ''
                , 'contrato' => $_REQUEST['contrato'] ?? ''
                , 'faixa-valor' => $_REQUEST['faixa-valor'] ?? ''
                , 'max' => $_REQUEST['max'] ?? 12
                , 'ordem' => $_REQUEST['ordem'] ?? 'DESC'
                , 'post_type' => $_REQUEST['post_type'] ?? 'imovel'
                , 'regiao' => $_REQUEST['regiao'] ?? ''
                , 'sort' => $_REQUEST['sort'] ?? 'dataPreco'
                , 'tipo-imovel' => $_REQUEST['tipo-imovel'] ?? ''
                , 'tipo_pesquisa_submit' => 'imovel'
                , 'valor-final' => $_REQUEST['valor-final'] ?? ''
                , 'valor-inicial' => $_REQUEST['valor-inicial'] ?? ''
              )
            )
          );
          ?>
        <?php endif; ?>

      </div>
    </section>
  </div>
</main>
<?php get_footer();
