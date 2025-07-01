<?php

if (function_exists('acf_add_local_field_group')) {

  acf_add_local_field_group(array(
    'key' => 'group_hero_homepage',
    'title' => 'Hero da Homepage',
    'fields' => array(
      array(
        'key' => 'field_hero_title',
        'label' => 'Título do Hero',
        'name' => 'hero_title',
        'type' => 'text',
      ),
      array(
        'key' => 'field_hero_subtitle',
        'label' => 'Subtítulo do Hero',
        'name' => 'hero_subtitle',
        'type' => 'textarea',
        'rows' => 3,
      ),
      array(
        'key' => 'field_hero_image',
        'label' => 'Imagem de Fundo do Hero',
        'name' => 'hero_background_image',
        'type' => 'image',
        'return_format' => 'url',
        'preview_size' => 'medium',
        'library' => 'all',
      ),
      array(
        'key' => 'field_hero_button_text',
        'label' => 'Texto do Botão',
        'name' => 'hero_button_text',
        'type' => 'text',
      ),
      array(
        'key' => 'field_hero_button_url',
        'label' => 'URL do Botão',
        'name' => 'hero_button_url',
        'type' => 'url',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'front-page.php',
        ),
      ),
    ),
  ));
}
