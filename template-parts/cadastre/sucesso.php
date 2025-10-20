<?php
  use function Air_Light\air_edit_link;
  $erro = get_query_var( 'erro', false );
  $data = get_query_var( 'data', '' );
  $mensagem = 'Houve um erro desconhecido ao cadastrar seu imóvel. Tente novamente mais tarde!';
  if ( isset( $data ) ) {
    if ( is_array( $data ) && isset( $data['message'] ) ) {
      $mensagem = $data['message'];
    } elseif ( is_object( $data ) && property_exists( $data, 'message' ) ) {
      $mensagem = $data->message;
    }
  }
?>
<section id="cadastre" class="cadastre">
  <div class="container">
    <h3>Cadastre seu Imóvel</h3>
    <div class="row <?php echo $erro ? 'error' : 'success'; ?>">
      <div class="col col-full <?php echo $erro ? 'error' : 'success'; ?>">
        <?php if ( true === $erro ) : ?>
          <label>Houve um erro ao cadastrar o seu imóvel!</label>
        <?php else : ?>
          <label>Sucesso!</label>
        <?php endif; ?>
        <div class="row mensagem <?php echo $erro ? 'error' : 'success'; ?>">
          <p><?php echo $mensagem; ?></p>
        </div>
      </div>
    </div>
    <footer class="row <?php echo $erro ? 'error' : 'success'; ?>">
      <div id="passoPosCadastro" class="row <?php echo $erro ? 'error' : 'success'; ?>">
        <div class="row buttom <?php echo $erro ? 'error' : 'success'; ?>">
          <button id="btnProximo" type="button" class="btn" onclick="window.location.href='<?php echo esc_url( home_url( '/' ) ); ?>'">Ir para a página inicial</button>
        </div>
      </div>
    </footer>
  </div>
</section>
