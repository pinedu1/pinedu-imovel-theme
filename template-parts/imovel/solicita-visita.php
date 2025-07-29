<?php
global $post;
$referencia = get_post_meta($post->ID, 'referencia', true);
$empresaQuery = new \WP_Query( array( 'post_type' => 'empresa', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_query' => [ [ [ 'id'     => '1', 'value'   => '1', 'compare' => '=' ] ] ] ) );
$whatsapp = '';
if ($empresaQuery->have_posts()) {
  $empresa = $empresaQuery->posts[0];
  $whatsapp = get_post_meta($empresa->ID, 'whatsapp', true);
}
?>
<section class="solicita-visita">
  <header><h4>Quero fazer uma Visita</h4></header>
  <div class="container">
    <div id="botoes-contato" class="row">
      <div class="row contato-button">
        <button id="mais-informacoes" class="button button-small" onClick="exibeFormContato(this);">Agendar</button>
      </div>
      <?php if ( !empty( $whatsapp ) ) : ?>
        <!-- Botão do WhatsApp com o ícone -->
        <div class="row contato-button" title="Faça contato via WhatsApp">
          <a class="contato-whatsapp" href="https://api.whatsapp.com/send?1=pt_BR&amp;phone=55<?php echo $whatsapp; ?>&amp;text=Olá! Gostaria de mais informações sobre a referência:<?php echo $referencia; ?>." target="_blank">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <div id="div-contato" class="contato-form row">
      <form id="contato-form" class="contato-form" action="contato_imovel">
        <?php wp_nonce_field('contato_imovel', 'contato_nonce'); ?>
        <input type="hidden" name="referencia" value="<?php echo $referencia; ?>">
        <div class="flash-message"></div>
        <div class="row" title="Informe seus dados para que um dos nossos corretores possa entrar em contato, e marcar sua visita">
          <label for="nome">Nome <span class="required">*</span></label>
          <input type="text" name="nome" value="" size="20" required="required" placeholder="Nome para Contato">
        </div>
        <div class="row" title="Informe seus dados para que um dos nossos corretores possa entrar em contato, e marcar sua visita">
          <label for="email">Email <span class="required">*</span></label>
          <input type="email" name="email" value="" aria-required="true" required="required" placeholder="Email para Contato">
        </div>
        <div class="row" title="Informe seus dados para que um dos nossos corretores possa entrar em contato, e marcar sua visita">
          <label for="telefone">Telefone <span class="required">*</span></label>
          <input type="text" name="telefone" value="" required="" placeholder="Telefone para Contato">
        </div>
        <div class="row submit">
          <input type="submit" name="submit" class="button button-small" value="Enviar" onClick="return enviarContato(this);">
        </div>
      </form>
    </div>
  </div>
</section>
<div id="loading-curtain" style="display: none;">
  <div class="loading-content">
    <div class="loading-spinner"></div>
    <p>Processando seus dados, por favor aguarde...</p>
  </div>
</div>
