<article class="card-container <?php echo $this->getClass(); ?>" data-contrato="<?php echo $this->getContrato(); ?>">
  <header><h3>Promoções a <?php echo $this->getTitulo(); ?></h3></header>
  <div class="promocao-grid">
    <?php while ($this->query->have_posts()) : $this->query->the_post();
      the_content();
    endwhile; ?>
  </div>
  <footer>
    <?php if ($this->query->max_num_pages > 1): ?>
      <nav class="navigation pagination paginacao <?php echo $this->getClass(); ?>" data-contrato="<?php echo $this->getContrato(); ?>" aria-label="Navegação de páginas">
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
