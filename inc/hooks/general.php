<?php
/**
 * General hooks.
 *
 * @package air-light
 */

namespace Air_Light;

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function definir_widgets_area( ) {
  $mapa_widget_area = array(
  );
  foreach ( $mapa_widget_area as $widget_instance ) {
    $widget_area = $widget_instance['area'];
    $widget_id = $widget_instance['widget'];
    unset( $widget_instance['area'] );
    unset( $widget_instance['widget'] );
    $sidebars_widgets = get_option( 'sidebars_widgets', array( ) );
    if ( !isset( $sidebars_widgets[$widget_area] ) ) {
      $sidebars_widgets[$widget_area] = array( );
    }
    $widget_instances = get_option( 'widget_' . $widget_id, array( ) );
    $widget_number = count( $widget_instances ) + 1;
    $widget_instances[$widget_number] = $widget_instance;
    update_option( 'widget_' . $widget_id, $widget_instances );
    $sidebars_widgets[$widget_area][] = $widget_id . '-' . $widget_number;
    update_option( 'sidebars_widgets', $sidebars_widgets );
  }

}
function registrar_widgets( ) {
  register_sidebar( array(
    'name' => esc_html__( 'Sidebar de Pesquisa', 'pinedu' )
    , 'id' => 'pesquisa-sidebar'
    , 'description' => esc_html__( 'Adicione widgets aqui para aparecer na barra lateral de pesquisa.', 'pinedu' )
    , 'before_widget' => '<section id="%1$s" class="widget %2$s">'
    , 'after_widget' => '</section>'
    , 'before_title' => '<h3 class="widget-title">'
    , 'after_title' => '</h3>'
  ) );
}
function widgets_init( ) {
  registrar_widgets( );
  definir_widgets_area( );
}
