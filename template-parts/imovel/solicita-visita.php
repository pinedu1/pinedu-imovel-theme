<?php
global $post;
$referencia = $post->referencia;
$empresa_query = new \WP_Query( array( 'post_type' => 'empresa', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_query' => [ [ [ 'id' => '1', 'value' => '1', 'compare' => '=' ] ] ] ) );
$whatsapp = '';
if ( $empresa_query->have_posts( ) ) {
  $empresa = $empresa_query->posts[0];
  $whatsapp = get_post_meta( $empresa->ID, 'whatsapp', true );
}
?>
<section class="solicita-visita">
  <header><h4>Quero fazer uma Visita</h4></header>
  <div class="container">
    <div id="botoes-contato" class="row">
      <div class="row contato-button">
        <button id="mais-informacoes" class="button button-small" onClick="exibeFormContato( this );">Agendar</button>
      </div>
      <?php if ( ! empty( $whatsapp ) ) : ?>
        <!-- Botão do WhatsApp com o ícone -->
        <div class="row contato-button" title="Faça contato via WhatsApp">
          <a class="contato-whatsapp" href="https://api.whatsapp.com/send?1=pt_BR&amp;phone=55<?php echo $whatsapp; ?>&amp;text=Olá! Gostaria de mais informações sobre a referência:<?php echo $referencia; ?>." target="_blank">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <?php get_template_part( 'template-parts/imovel/contato-form' , 'contato-form' ); ?>
  </div>
</section>
<div id="loading-curtain" style="display: none;">
  <div class="loading-content">
    <div class="loading-spinner"></div>
    <p>Processando seus dados, por favor aguarde...</p>
  </div>
</div>
