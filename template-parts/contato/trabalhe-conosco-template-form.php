<div class="curriculo-form-box">
  <?php if ( isset( $email_enviado ) && $email_enviado ): ?>
    <div class="sent-message" style="display:block; color:#4CAF50;">
      Sua mensagem foi enviada. Obrigado pelo interesse em fazer parte da nossa equipe!
    </div>
  <?php else: ?>
    <form id="curriculo_form" name="curriculo_form" action="<?php echo esc_url( get_permalink() ); ?>" method="post" enctype="multipart/form-data" class="curriculo-form">
      <?php wp_nonce_field( 'trabalhe_conosco', 'trabalhe_conosco_nonce' ); ?>
      <input type="hidden" id="recaptcha_token" name="recaptcha_token">
      <div class="form-group">
        <label>Nome completo *</label>
        <input type="text" name="nome" id="nome" placeholder="Digite seu Nome" value="<?php echo esc_attr($args['nome']); ?>" required/>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>E-mail *</label>
          <input type="email" name="email" id="email" placeholder="Digite seu Email" value="<?php echo esc_attr($args['email']); ?>" required/>
        </div>
        <div class="form-group">
          <label>Celular / WhatsApp *</label>
          <input type="text" name="celular" id="celular" placeholder="Digite seu Telefone" value="<?php echo esc_attr($args['telefone']); ?>" required/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Cidade *</label>
          <input type="text" name="cidade" id="cidade" placeholder="Digite sua Cidade" value="<?php echo esc_attr($args['cidade']); ?>" required>
        </div>
        <div class="form-group">
          <label>Cargo de interesse *</label>
          <select name="cargo" id="cargo" placeholder="Escolha sua área de interesse" required>
            <option value="">Selecione</option>
            <option>Corretor(a) de Imóveis</option>
            <option>Captador(a) de Imóveis</option>
            <option>Administrativo</option>
            <option>Marketing</option>
            <option>Estágio</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Possui CRECI ?</label>
        <select name="creci" id="creci" placeholder="Se corretor informe a situação" >
          <option value="">Selecione</option>
          <option>Sim</option>
          <option>Não</option>
          <option>Em andamento</option>
        </select>
      </div>
      <div class="form-group">
        <label>Fale um pouco sobre você </label>
        <textarea name="mensagem" id="mensagem" rows="5" placeholder="Conte brevemente sua experiência e objetivos profissionais."><?php echo esc_textarea($args['mensagem']); ?></textarea>
      </div>
      <div class="form-group upload">
        <label>Envie seu Currículo *</label>
        <input type="file" class="btn-curriculo" name="curriculo" id="curriculo" accept=".pdf,.doc,.docx" required>
        <small>Formatos aceitos: PDF, DOC ou DOCX(máx . 5 MB).</small>
      </div>
      <button type="submit" class="btn-enviar-curriculo">
        Enviar currículo
      </button>
      <?php if ( $args['chave_publica'] ): ?>
        <div style="font-size: 10px; color: #888; text-align: center; margin-top: 15px; line-height: 1.4;">
          Este site é protegido pelo reCAPTCHA e aplicam-se a
          <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" style="color: #666; text-decoration: underline;">Política de Privacidade</a> e os
          <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" style="color: #666; text-decoration: underline;">Termos de Serviço</a> do Google.
        </div>
      <?php endif; ?>
    </form>
  <?php endif; ?>
</div>
