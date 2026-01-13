<?php
$referencia = get_query_var( 'referencia' );
$codigo_corretor = get_query_var( 'codigo-corretor' );
$nome_corretor = get_query_var( 'nome-corretor' );
?>
<section class="contato-corretor">
  <header class="contato-corretor">
    <h3>Contato com Corretor</h3>
  </header>
  <main class="contato-corretor">
    <?php get_template_part( 'template-parts/corretor/contato-corretor-form' ); ?>
  </main>
  <footer class="contato-corretor">
    <p>Em breve o corretor <?php echo $nome_corretor; ?> entrará em contato</p>
  </footer>
</section>
