<?php
  require_once get_template_directory() . '/inc/classes/Visitados.php';
  $promo = new Visitados( 'Imóveis mais visitados', 8 );
  $promo->render();
