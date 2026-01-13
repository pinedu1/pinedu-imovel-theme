<?php

global $empresa;
if ( isset( $empresa ) && isset( $empresa->telefonePadrao) ) {
  $telefonePadrao = $empresa->telefonePadrao;
}

?>
<section class="central-atendimento">
  <a href="tel:+55<?php echo $telefonePadrao; ?>" title="Acesse nossa Central de atendimento <?php echo formata_telefone( $telefonePadrao ); ?>">
    <img
      src="<?php echo get_template_directory_uri(); ?>/assets/images/central_atendimento_vermelho.png"
      alt="Acesse nossa Central de atendimento <?php echo formata_telefone( $telefonePadrao ); ?>"
      class="central-atendimento"
      style="max-width: 100%; height: auto;"
    />
  </a>
</section>
