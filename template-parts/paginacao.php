<?php
global $wp_query;
$custom_query = isset( $custom_query ) ? $custom_query : $wp_query;
if ( $custom_query->max_num_pages > 1 ):?>
<nav class="paginacao" aria-label="<?php echo 'Navegação de páginas'; ?>">
  <?php
    echo paginate_links( array(
      'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) )
      , 'format' => '?paged=%#%'
      , 'current' => max( 1, get_query_var( 'paged' ) )
      , 'total' => $custom_query->max_num_pages
      , 'prev_text' => '« Anterior'
      , 'next_text' => 'Próxima »'
      , 'type' => 'list'
      , 'end_size' => 1
      , 'mid_size' => 2
    ) );
  ?>
</nav>
<?php endif; ?>
