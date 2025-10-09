<?php
  function custom_tag_cloud_format_tipo_imovel($return, $tags, $args) {
    return preg_replace_callback(
      '/(<a[^>]*>)(.*?)(<\/a>)/',
      function($matches) {
        return $matches[1] . $matches[2] . '<span class="tag-bullet"><i class="fas fa-circle"></i></span>' . $matches[3];
      },
      $return
    );
  }
  remove_filter('wp_generate_tag_cloud', 'custom_tag_cloud_format_cidade', 10);
  remove_filter('wp_generate_tag_cloud', 'custom_tag_cloud_format_regiao', 10);
  add_filter('wp_generate_tag_cloud', 'custom_tag_cloud_format_tipo_imovel', 10, 3);
?>
<article class="tag-cloud tipo-imovel">
  <header><h4>Tipo de imóveis</h4></header>
  <?php
    wp_tag_cloud(array(
      'smallest' => 18
      , 'largest' => 18
      , 'unit' => 'px'
      , 'format' => 'list'
      , 'separator' => ' • '
      , 'orderby' => 'count'
      , 'order' => 'DESC'
      , 'show_count' => 0
      , 'echo' => true
      , 'number' => 12
      , 'taxonomy' => ['tipo-imovel']
      , 'post_type' => 'imovel'
      , 'aria_label' => __('Nuvem de tags')
    ));
  ?>
</article>
