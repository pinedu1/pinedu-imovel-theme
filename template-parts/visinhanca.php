<?php
  global $post;
  require_once get_template_directory() . '/inc/classes/Visinhanca.php';
  $visinhanca = new Visinhanca( $post, 'Imóveis próximos', 8 );
  $visinhanca->render();
