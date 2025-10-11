<div class="footer-line row"></div>
<section class="footer-grid">
  <!-- Logotipo -->
  <div class="footer-column footer-logo">
    <!--<h3>&emsp;</h3>-->
    <div class="logo">
      <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>" class="branding-logo-footer">
        <img
          src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_footer.png"
          alt="<?php bloginfo('name'); ?>"
          class="branding-logo-footer"
        />
      </a>
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
  <div class="footer-column links-uteis">
    <h3>Links Úteis</h3>
    <!-- ... -->
  </div>
</section>
