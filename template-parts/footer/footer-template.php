<section class="footer-grid">
  <!-- Logotipo -->
  <div class="footer-column footer-logo">
    <div class="logo">
      <?php the_custom_logo(); ?>
    </div>
  </div>
  <!-- Coluna de Lojas -->
  <div class="footer-column footer-stores">
    <h3>Nossas Lojas</h3>
    <?php echo get_template_part('template-parts/footer/lojas'); ?>
  </div>
  <!-- Demais colunas -->
  <div class="footer-column social-networks">
    <h3>Redes Sociais</h3>
    <?php echo get_template_part('template-parts/footer/social-network'); ?>
  </div>
  <div class="footer-column">
    <h3>Links Úteis</h3>
    <!-- ... -->
  </div>
</section>
