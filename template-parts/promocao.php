<?php
  require_once get_template_directory() . '/inc/classes/Promocoes.php';
  $promocao_contrato = isset($promocao_contrato) ? $promocao_contrato: 1;
  $promo = new Promocoes( $promocao_contrato, 8 );
  $promo->render();
