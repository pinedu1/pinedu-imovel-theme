<?php

class PineduWidgetSearchBar extends WP_Widget {
  public function __construct() {
    parent::__construct(
      'pesquisa_sidebar_widget'
      , 'Widget Barra de Pesquisa'
      , array( 'description' => 'Barra de Pesquisa de Imoveis Widget' )
    );
  }
  public function widget( $widget_args, $instance ) {
    echo $widget_args['before_sidebar'];
    echo $widget_args['before_widget'];
    echo $widget_args['before_title'];
    echo $widget_args['after_title'];
    echo do_shortcode( '[pinedu-search-bar]' );
    echo $widget_args['after_widget'];
    echo $widget_args['after_sidebar'];
  }
  public function form( $instance ) {
    $instance = wp_parse_args( (array) $instance, array(  ));
  }
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    return $instance;
  }
}
