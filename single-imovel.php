<?php
/**
 * The template for displaying all single posts
 *
 * @Date:   2019-10-15 12:30:02
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2022-09-07 11:57:39
 *
 * @package air-light
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */
namespace Air_Light;
the_post();
get_header(); ?>
<main class="site-main imovel">
  <section class="block block-single">
    <?php
      get_template_part( 'template-parts/pesquisa/sidebar-pesquisa' , 'imovel' );
    ?>
    <article class="article-content">
      <!-- Marca imóvel visitado -->
      <?php registra_visita_imovel( ); ?>
      <!-- Slides -->
      <?php get_template_part('template-parts/imovel/slide-glide', 'imovel'); ?>
      <h1><?php the_title(); ?></h1>
      <section class="imovel-content">
        <?php the_content(); ?>
      </section>
      <?php
      get_template_part( 'template-parts/imovel/imovel-caracteristicas', 'imovel' );
      get_template_part( 'template-parts/imovel/imovel-mapa', 'imovel' );
      get_template_part( 'template-parts/visinhanca', 'imovel' );
      entry_footer();

  		// If comments are open or we have at least one comment, load up the comment template.
      if ( comments_open() || get_comments_number() ) {
        comments_template();
      } ?>

    </article>
    <!-- Sidebar -->
  </section>
</main>
<?php get_footer();
