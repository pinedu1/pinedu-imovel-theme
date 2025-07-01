<?php if (!getCookie()): ?>
<div id="privacy-compliance-container" class="privacy-compliance-hidden">
  <div class="privacy-compliance-content">
    <h3>Nossa Política de Privacidade e Cookies</h3>
    <p>Utilizamos cookies para melhorar sua experiência em nosso site. Ao continuar navegando, você concorda com o uso de cookies conforme nossa <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" target="_blank" rel="noopener noreferrer">Política de Privacidade</a>.</p>
    <div class="privacy-compliance-actions">
      <button id="accept-privacy-button" class="privacy-button privacy-accept">Aceitar e Fechar</button>
      <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" class="privacy-button privacy-more-info" target="_blank" rel="noopener noreferrer">Saiba Mais</a>
    </div>
  </div>
</div>
<?php endif; ?>
