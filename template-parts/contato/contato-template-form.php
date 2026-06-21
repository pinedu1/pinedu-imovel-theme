<div class="contact-form">
  <?php if ( isset( $email_enviado ) && $email_enviado ): ?>
    <header>
      <h4>Email enviado com sucesso!</h4><br>
    </header>
    <div class="sucess-container">
      <p class="resultado-email">Em breve o melhor profissional entrará em contato com você, procurando a melhor solução para sua mensagem.</p>
      <p class="resultado-email"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Clique aqui para ser direcionado para <strong>HOME PAGE</strong></a></p>
    </div>
  <?php else : ?>
    <header>
      <h4>Deixe seus dados de contato. Envie sua mensagem</h4>
    </header>
    <form id="contato" action="<?php echo esc_url( get_permalink() ); ?>" method="post">
      <?php wp_nonce_field( 'contato', 'contato_nonce' ); ?>
      <input type="hidden" id="recaptcha_token" name="recaptcha_token">
      <input type="text" class="form-contato" placeholder="Digite aqui seu Nome" name="nome" value="<?php echo esc_attr($nome); ?>" required/>
      <input type="email" class="form-contato" placeholder="Digite aqui seu Email" name="email" value="<?php echo esc_attr($email); ?>" required/>
      <input type="text" class="form-contato" placeholder="Digite aqui seu Telefone" name="telefone" value="<?php echo esc_attr($telefone); ?>" />
      <textarea id="mensagem" class="form-contato" placeholder="Digite aqui a mensagem" name="mensagem" rows="10" required><?php echo esc_textarea($mensagem); ?></textarea>
      <button class="btn" type="submit">Enviar</button>
    </form>
  <?php endif; ?>
</div>
