<article class="card-container <?php echo $this->getClass(); ?>" data-contrato="<?php echo $this->getContrato(); ?>">
  <header>
    <h3>Resultado da Pesquisa</h3>
    <?php echo get_template_part('template-parts/pesquisa/meta-pesquisa', 'imovel'); ?>
  </header>
  <div class="pesquisa-grid">
    <div class="cortina-aguarde inactive">
      <div class="loading-spinner"></div>
      <span>Aguarde...</span>
    </div>
    <?php if ( $this->query->have_posts() ): ?>
      <?php while ($this->query->have_posts()) : $this->query->the_post();?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
  <footer>
    <?php if ($this->query->max_num_pages > 1): ?>
      <nav class="paginacao <?php echo $this->getClass(); ?>" aria-label="Navegação de páginas">
        <?php
          $base_link = get_pagenum_link(999999999);
          $base = $base_link ? str_replace(999999999, '%#%', esc_url($base_link)) : '';
          echo paginate_links( array(
            'base' => $base
            , 'format' => '?paged=%#%'
            , 'current' => max(1, get_query_var('paged'))
            , 'total' => $this->query->max_num_pages
            , 'prev_text' => __('« Anterior')
            , 'next_text' => __('Próxima »')
            , 'type' => 'list'
            , 'end_size' => 1
            , 'mid_size' => 2
          ) );
        ?>
      </nav>
    <?php endif; ?>
  </footer>
</article>
