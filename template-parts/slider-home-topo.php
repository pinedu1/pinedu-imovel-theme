<?php
  require_once get_template_directory() . '/inc/classes/Slides.php';
  $slider = new Slides( 'home-topo' );
  $slider->render();
