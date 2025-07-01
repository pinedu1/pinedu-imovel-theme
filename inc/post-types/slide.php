<?php
/**
 * Slide post type
 */

namespace Air_Light;

function create_post_type_slide() {
  register_post_type( 'slide',
    [
      'labels' => [
        'name'               => __( 'Slides', 'air-light' ),
        'singular_name'      => __( 'Slide', 'air-light' ),
        'menu_name'          => __( 'Slides', 'air-light' ),
        'name_admin_bar'     => __( 'Slide', 'air-light' ),
        'add_new'            => __( 'Adicionar novo', 'air-light' ),
        'add_new_item'       => __( 'Adicionar novo slide', 'air-light' ),
        'new_item'           => __( 'Novo slide', 'air-light' ),
        'edit_item'          => __( 'Editar slide', 'air-light' ),
        'view_item'          => __( 'Ver slide', 'air-light' ),
        'all_items'          => __( 'Todos os slides', 'air-light' ),
        'search_items'       => __( 'Buscar slides', 'air-light' ),
        'not_found'          => __( 'Nenhum slide encontrado.', 'air-light' ),
      ]
      , 'public'             => true
      , 'has_archive'        => false
      , 'show_in_rest'       => true
      , 'menu_icon'          => 'dashicons-slides'
      , 'supports'          => [ 'title', 'editor', 'thumbnail', 'page-attributes' ]
      , 'rewrite'           => false
    ]
  );
}
create_post_type_slide();
