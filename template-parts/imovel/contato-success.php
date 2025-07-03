<?php
  global $post;
  $referencia = get_post_meta($post->ID, 'referencia', true);
  $empresaQuery = new \WP_Query( array( 'post_type' => 'empresa', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_query' => [ [ [ 'id'     => '1', 'value'   => '1', 'compare' => '=' ] ] ] ) );
  $telefone = '';
  if ($empresaQuery->have_posts()) {
    $empresa = $empresaQuery->posts[0];
    $telefone = get_post_meta($empresa->ID, 'telefone', true);
    $email = get_post_meta($empresa->ID, 'email', true);
  }
?>
<section id="contato-success" class="contato-success">
  <header><h4>Sua solicitação foi enviada</h4></header>
  <div class="container">
    <div class="row">
      <p>Em breve nosso melhor Corretor, entrará em contato para intermediar sua visita;</p>
      <p>Por favor, aguarde o nosso contato, ou pode nos ligar a qualquer momento no número:<br><a href="telto:<?php echo esc_attr($telefone); ?>"><?php echo esc_html(formata_telefone($telefone)); ?></a>;</p>
      <p>Ou via email na caixa de email:<br><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>.</p>
    </div>
    <div class="row contato-button">
      <button id="fechar" class="button button-small" onClick="jQuery('section#contato-success').hide('slow');">Fechar</button>
    </div>
  </div>
</section>
