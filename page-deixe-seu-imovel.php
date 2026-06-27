<?php
namespace Air_Light;
require_once get_template_directory() . '/inc/classes/SalvarImovel.php';
require_once get_template_directory() . '/inc/hooks/ajax.php';
// ==========================================================================
// 1. INTERCEPTAÇÃO DA SUBMISSÃO DO FORMULÁRIO (EARLY RETURN)
// ==========================================================================
if ( isset( $_SERVER['REQUEST_METHOD'] ) && ( 'POST' === $_SERVER['REQUEST_METHOD'] ) ) {
  if ( \SalvarImovel::validate_nonce( $_REQUEST ) ) {
    $resultado_cadastro = \SalvarImovel::salvar();
    // Simplificação lógica: se não tiver resultado, é erro (true)
    set_query_var( 'data', $resultado_cadastro );
    set_query_var( 'erro', empty( $resultado_cadastro ) );
  } else {
    $data = new \stdClass();
    $data->mensagem = 'Erro nos dados enviados! Recarregue a página para reiniciar o cadastro.';
    set_query_var( 'data', $data );
    set_query_var( 'erro', true );
  }
  // Saída unificada (Evita repetição de código)
  ob_start();
  get_template_part( 'template-parts/cadastre/sucesso', 'imovel' );
  // O wp_send_json_success já embute o wp_die() nativamente, encerrando a execução aqui.
  wp_send_json_success( \Pinedu_Form_Cadastre::clean_result( ob_get_clean() ) );
  wp_die(); // Colocado apenas como garantia visual e boas práticas.
}
// ==========================================================================
// 2. FLUXO PADRÃO: RENDERIZAÇÃO DA PÁGINA
// ==========================================================================
the_post();
get_header();
?>
<main class="site-main">
  <section id="cadastre" class="cadastre">
    <div class="container">
      <h3>Cadastre seu Imóvel</h3>
      <?php get_template_part( 'template-parts/cadastre/identificacao', 'imovel' ); ?>
    </div>
  </section>

  <?php
  the_content();
  air_edit_link();
  ?>
</main>
<div id="loading-curtain" style="display: none;">
  <div class="loading-content">
    <div class="loading-spinner"></div>
    <p>Processando seus dados, por favor aguarde...</p>
  </div>
</div>
<?php get_footer(); ?>
