<?php
$nome       = $args['nome']       ?? '';
$telefone   = $args['telefone']   ?? '';
$email      = $args['email']      ?? '';
$codigo_corretor   = $args['corretor']   ?? '';
$mensagem   = $args['mensagem']   ?? '';
$referencia = $args['referencia'] ?? '';


$corretor = getCorretor( $codigo_corretor );
$telefone_corretor = '';
if ( $corretor ) {
  $tel = validarWhatsappBR( get_post_meta( $corretor->ID, 'telefone', true ) );
  if ( $tel !== false ) {
    $telefone_corretor = $tel;
  }
}
$empresa = getEmpresa( );
$nome_corretor = get_query_var( 'nome-corretor' );

$telefone = '';
if ( $empresa ) {
  $telefone = get_post_meta( $empresa->ID, 'telefone', true );
  $email = get_post_meta( $empresa->ID, 'email', true );
}
if ( !empty( $telefone_corretor ) ):
  $corretorNome = get_post_meta( $corretor->ID, 'pessoa_nome', true );
  $saudacao = montaSaudacaoCorretorWhatsApp($nome, $telefone, $email, $referencia, $corretorNome);
?>
<section id="contato-success" class="contato-success">
  <header><h4>Encontramos o corretor para seu atendimento</h4></header>
  <div class="container">
    <div class="row">
        <!-- Botão do WhatsApp com o ícone -->
        <div class="row contato-button" title="Faça contato via WhatsApp">
          <a class="contato-whatsapp" href="https://api.whatsapp.com/send?1=pt_BR&amp;phone=<?php echo $telefone_corretor; ?>&amp;text=<?php echo $saudacao; ?>" target="_blank">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
    </div>
    <div class="row contato-button">
      <button id="fechar" class="button button-small" onClick="jQuery( 'section#contato-success' ).hide( 'slow' );">Fechar</button>
    </div>
  </div>
</section>
<?php else: ?>
<section id="contato-success" class="contato-success">
  <header><h4>Sua solicitação foi enviada</h4></header>
  <div class="container">
    <div class="row">
      <p>Em breve nosso melhor Corretor, entrará em contato para intermediar sua visita;</p>
      <p>Por favor, aguarde o nosso contato, ou pode nos ligar a qualquer momento no número:<br><a href="telto:<?php echo esc_attr( $telefone ); ?>"><?php echo esc_html( formata_telefone( $telefone ) ); ?></a>;</p>
      <p>Ou via email na caixa de email:<br><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>.</p>
    </div>
    <div class="row contato-button">
      <button id="fechar" class="button button-small" onClick="jQuery( 'section#contato-success' ).hide( 'slow' );">Fechar</button>
    </div>
  </div>
</section>
<?php endif; ?>
