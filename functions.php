<?php
/**
 * Gather all bits and pieces together.
 * If you end up having multiple post types, taxonomies,
 * hooks and functions - please split those to their
 * own files under /inc and just require here.
 *
 * @Date: 2019-10-15 12:30:02
 * @Last Modified by:   Roni Laukkarinen
 * @Last Modified time: 2024-01-10 18:54:48
 *
 * @package air-light
 */

namespace Air_Light;

/**
 * The current version of the theme.
 */
define( 'AIR_LIGHT_VERSION', '9.5.1' );
define( 'PINEDU_PESQUISA_THEME', '1.0.0' );

// We need to have some defaults as comments or empties so let's allow this:
// phpcs:disable Squiz.Commenting.InlineComment.SpacingBefore, WordPress.Arrays.ArrayDeclarationSpacing.SpaceInEmptyArray

/**
 * Theme settings
 */
add_action( 'after_setup_theme', function() {
  $theme_settings = [
    /**
     * Theme textdomain
     */
    'textdomain' => 'air-light',

    /**
     * Content width
     */
    'content_width' => 800,

    /**
     * Logo and featured image
     */
    'default_featured_image'  => null,
    'logo'                    => '/svg/logo.svg',

    /**
     * Custom setting group settings when using Air setting groups plugin.
     * On multilingual sites using Polylang, translations are handled automatically.
     */
    'custom_settings' => [
      // 'your-custom-setting' => [
      //   'id' => Your custom setting post id,
      //   'title' => 'Your custom setting',
      //   'block-editor' => true,
      //  ],
    ],

    'social_media_accounts'  => [
      // 'twitter' => [
      //   'title' => 'Twitter',
      //   'url'   => 'https://twitter.com/digitoimistodude',
      // ],
    ],

    /**
     * All links are checked with JS, if those direct to external site and if,
     * indicator of that is included. Exclude domains from that check in this array.
     */
    'external_link_domains_exclude' => [
      'localhost:3000',
      'airdev.test',
      'airwptheme.com',
      'localhost',
    ],

    /**
     * Menu locations
     */
    'menu_locations' => [
      'primary' => __( 'Primary Menu', 'air-light' ),
    ],

    /**
     * Taxonomies
     *
     * See the instructions:
     * https://github.com/digitoimistodude/air-light#custom-taxonomies
     */
    'taxonomies' => [
      // 'Your_Taxonomy' => [ 'post', 'page' ],
    ],

    /**
     * Post types
     *
     * See the instructions:
     * https://github.com/digitoimistodude/air-light#custom-post-types
     */
    'post_types' => [
      'Slide'
      // 'Your_Post_Type',
    ],

    /**
     * Gutenberg -related settings
     */
    // Register custom ACF Blocks
    'acf_blocks' => [
      // [
      //   'name'           => 'block-file-slug',
      //   'title'          => 'Block Visible Name',
      //   // You can safely remove lines below if you find no use for them
      //   'prevent_cache'  => false, // Defaults to false,
      //   // Icon defaults to svg file inside svg/block-icons named after the block name,
      //   // eg. svg/block-icons/block-file-slug.svg
      //   //
      //   // Icon setting defines the dashicon equivalent: https://developer.wordpress.org/resource/dashicons/#block-default
      //   // 'icon'  => 'block-default',
      // ],
    ],

    // Custom ACF block default settings
    'acf_block_defaults' => [
      'category'          => 'air-light',
      'mode'              => 'auto',
      'align'             => 'full',
      'post_types'        => [
        'page',
      ],
      'supports'  => [
        'align'           => false,
        'anchor'          => true,
        'customClassName' => false,
      ],
      'render_callback'   => __NAMESPACE__ . '\render_acf_block',
    ],

    // Restrict to only selected blocks
    //
    // Options: 'none', 'all', 'all-core-blocks', 'all-acf-blocks',
    // or any specific block or a combination of these
    // Accepts both string (all*/none-options only) and array (options + specific blocks)
    'allowed_blocks' => [
      'post' => [
        'core/column',
        'core/columns',
        'core/coverImage',
        'core/embed',
        'core/freeform',
        'core/gallery',
        'core/heading',
        'core/html',
        'core/image',
        'core/list',
        'core/list-item',
        'core/paragraph',
        'core/quote',
        'core/block',
        'core/table',
        'core/textColumns',
      ],
      'page' => [],
      // 'page' => [
      //   'all-acf-blocks',
      //   'core/paragraph',
      // ],
      // 'post-type' => [
      //   'acf/content-image',
      //   'core/paragraph',
      // ],
      // 'example' => [
      //   'all-core-blocks',
      //   'acf/content-image',
      // ],
    ],

    // If you want to use classic editor somewhere, define it here
    'use_classic_editor' => [],

    // Add your own settings and use them wherever you need, for example THEME_SETTINGS['my_custom_setting']
    'my_custom_setting' => true,
  ];

  $theme_settings = apply_filters( 'air_light_theme_settings', $theme_settings );

  define( 'THEME_SETTINGS', $theme_settings );
} ); // end action after_setup_theme

/**
 * Debug function to print all available blocks
 */
function debug_print_all_blocks() {
  $blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
  $block_names = array_map(function( $block ) {
    return "'" . $block->name . "',";
  }, $blocks);
  echo '<pre>' . implode( "\n", $block_names ) . '</pre>'; // phpcs:ignore
  die();
}

// Uncomment the following line to see all available blocks:
//add_action('init', __NAMESPACE__ . '\\debug_print_all_blocks');


/**
 * Required files
 */
require get_theme_file_path( '/inc/hooks.php' );
require get_theme_file_path( '/inc/filters.php' );
require get_theme_file_path( '/inc/includes.php' );
require get_theme_file_path( '/inc/template-tags.php' );

// Run theme setup
add_action( 'after_setup_theme', __NAMESPACE__ . '\theme_setup' );
add_action( 'after_setup_theme', __NAMESPACE__ . '\build_theme_support' );

/*
 * First: we register the taxonomies and post types after setup theme
 * If air-helper loads (for translations), we unregister the original taxonomies and post types
 * and reregister them with the translated ones.
 *
 * This allows the slugs translations to work before the translations are available,
 * and for the label translations to work if they are available.
 */
add_action( 'after_setup_theme', __NAMESPACE__ . '\build_taxonomies' );
add_action( 'after_setup_theme', __NAMESPACE__ . '\build_post_types' );

add_action( 'after_air_helper_init', __NAMESPACE__ . '\rebuild_taxonomies' );
add_action( 'after_air_helper_init', __NAMESPACE__ . '\rebuild_post_types' );
// Ativar suporte a logotipo personalizado
add_theme_support('custom-logo', array(
  'height'      => 350, // Altura máxima em pixels
  'width'       => 350, // Largura máxima em pixels
  'flex-height' => true, // Permite altura flexível
  'flex-width'  => true, // Permite largura flexível
  'header-text' => array('site-title', 'site-description'), // Texto alternativo
));
function formata_endereco_loja( $tipo, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $cep ) {
  $end = $tipo . ' ' . $logradouro;
  if ( ! empty( $numero ) ) {
    $end .= ', ' . $numero;
  }
  if ( ! empty( $complemento ) ) {
    $end .= ' - ' . $complemento;
  }
  if ( ! empty( $bairro ) ) {
    $end .= '<br>' . $bairro;
  }
  if ( ! empty( $cidade ) ) {
    $end .= ' - ' . $cidade;
  }
  if ( ! empty( $estado ) ) {
    $end .= '<br>' . $estado . ', Brasil' ;
  }
  if ( ! empty( $cep ) ) {
    $end .= '<br>Cep: ' . formata_cep( $cep ) ;
  }
  return formatar_title_case( $end );
}
add_filter( 'template_include', function( $template ) {
  if ( false && wp_get_environment_type() === 'development' ) {
    echo '<pre>Template usado: ' . $template . '</pre>';
  }
  return $template;
});
function busca_campos_destaque_card( $post ) {
  $campos = get_post_meta( $post->ID, '', false );
  $destaques = [];
  $getValor = function( $campos, $campo ) {
    $p = explode('Nome', $campo);
    $sigla = $p[0];
    return $campos[ $sigla ][0];
  };
  foreach ( $campos as $campo => $cpp ) {
    if ( !preg_match('/^[A-Z]/', $campo) ) continue;
    $cp = $cpp[0];
    if ( str_starts_with(normalizar($cp), 'DORMIT') ) {
      $destaques[ 'DOR' ] = [ 'nome' => $cp, 'valor' => $getValor( $campos, $campo ) ];
    } else if ( str_starts_with(normalizar($cp), 'QUARTO') ) {
      $destaques[ 'QUA' ] = [ 'nome' => $cp, 'valor' => $getValor( $campos, $campo ) ];
    } else if ( str_starts_with(normalizar($cp), 'SUITE') ) {
      $destaques[ 'SUI' ] = [ 'nome' => $cp, 'valor' => $getValor( $campos, $campo ) ];
    } else if ( str_starts_with(normalizar($cp), 'BAN') ) {
      $destaques[ 'BAN' ] = [ 'nome' => $cp, 'valor' => $getValor( $campos, $campo ) ];
    } else if ( str_starts_with(normalizar($cp), 'GARAGE') ) {
      $destaques[ 'GAR' ] = [ 'nome' => $cp, 'valor' => $getValor( $campos, $campo ) ];
    } else if ( str_starts_with(normalizar($cp), 'AREA U') ) {
      $destaques[ 'ARU' ] = [ 'nome' => $cp, 'valor' => $getValor( $campos, $campo ) ];
    } else if ( str_starts_with(normalizar($cp), 'AREA T') ) {
      $destaques[ 'ART' ] = [ 'nome' => $cp, 'valor' => $getValor( $campos, $campo ) ];
    }
  }
  return $destaques;
}
// Adicionar campo de telefone no Customizer
/*
function pinedu_customize_register($wp_customize) {

  // Adicionar seção (se necessário)
  $wp_customize->add_section('pinedu_contact_info', array(
    'title' => __('Informações de Contato', 'air-light'),
    'priority' => 30,
  ));

  // Campo de Telefone
  $wp_customize->add_setting('pinedu_phone', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  $wp_customize->add_control('pinedu_phone', array(
    'label' => __('Telefone', 'air-light'),
    'section' => 'pinedu_contact_info',
    'type' => 'text',
  ));

  // Campo de Email
  $wp_customize->add_setting('pinedu_email', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_email',
  ));

  $wp_customize->add_control('pinedu_email', array(
    'label' => __('Email', 'air-light'),
    'section' => 'pinedu_contact_info',
    'type' => 'email',
  ));

  // Campo de Endereço
  $wp_customize->add_setting('pinedu_address', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_textarea_field',
  ));

  $wp_customize->add_control('pinedu_address', array(
    'label' => __('Endereço', 'air-light'),
    'section' => 'pinedu_contact_info',
    'type' => 'textarea',
  ));
}
add_action('customize_register', 'Air_Light\pinedu_customize_register');
*/
/**
 * Add Open Graph meta tags for social sharing
 * Specially optimized for single property posts (imovel)
 */
function pinedu_add_open_graph_meta_tags() {
  if ( is_singular('imovel') || is_singular('post') ) {
    global $post;
    // Basic Open Graph tags
    $og_title = get_the_title();
    $og_description = get_the_excerpt();
    if ( empty($og_description) ) {
      $og_description = wp_trim_words( strip_tags( get_the_content() ), 30, '...' );
    }
    $og_url = get_permalink();
    $og_type = 'article';
    if ( is_singular('imovel') ) {
      $og_type = 'product.item';
    }
    $og_site_name = get_bloginfo('name');
    // Get featured image or first image from gallery
    $og_image = '';
    if ( has_post_thumbnail() ) {
      $og_image = get_the_post_thumbnail_url( $post->ID, 'large' );
    } else {
      $gallery = get_post_gallery( $post->ID, false );
      if ( !empty($gallery['src']) && is_array($gallery['src']) ) {
        $og_image = $gallery['src'][0];
      }
    }
    // Output Open Graph meta tags
    echo "\n<!-- Open Graph Meta Tags -->\n";
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($og_site_name) . '" />' . "\n";
    $options = get_option( 'pinedu_imovel_options', [] );
    $usar_descricao_do_imovel = true;
    if (isset($options['usar_descricao_do_imovel'])) {
      echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    } else {
      if ( isset($post->cidade) || isset($post->regiao) ) {
        $enhanced_description = '';
        if ( isset($post->tipoImovelNome) ) {
          $enhanced_description .= $post->tipoImovelNome;
        }
        if ( isset($post->regiao) && !empty($post->regiao) ) {
          $enhanced_description .= ' em ' . $post->regiao;
        }
        if ( isset($post->cidade) && !empty($post->cidade) ) {
          $enhanced_description .= ', ' . $post->cidade;
        }
        if ( !empty($enhanced_description) ) {
          $enhanced_description .= ' - ' . $og_description;
          echo '<meta property="og:description" content="' . esc_attr($enhanced_description) . '" />' . "\n";
        }
      }
    }

    if ( !empty($og_image) ) {
      echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";

      // Recuperar dimensões dinâmicas da imagem
      $image_id = null;
      if ( has_post_thumbnail() ) {
        $image_id = get_post_thumbnail_id( $post->ID );
      } else {
        $gallery = get_post_gallery( $post->ID, false );
        if ( !empty($gallery['ids']) ) {
          $gallery_ids = explode(',', $gallery['ids']);
          $image_id = $gallery_ids[0];
        }
      }
      if ( $image_id ) {
        $image_meta = wp_get_attachment_image_src( $image_id, 'large' );
        if ( $image_meta ) {
          $image_width = $image_meta[1];
          $image_height = $image_meta[2];
          echo '<meta property="og:image:width" content="' . esc_attr($image_width) . '" />' . "\n";
          echo '<meta property="og:image:height" content="' . esc_attr($image_height) . '" />' . "\n";
        }
      } else {
        // Fallback para dimensões padrão caso não consiga obter o ID
        echo '<meta property="og:image:width" content="1200" />' . "\n";
        echo '<meta property="og:image:height" content="630" />' . "\n";
      }
    }    // Facebook App ID (opcional)
    $options = get_option( 'pinedu_imovel_options', [] );
    if (isset($options['facebook_app_id']) && !empty($options['facebook_app_id'])) {
      echo '<meta property="fb:app_id" content="' . esc_attr($options['facebook_app_id']) . '" />' . "\n";
    }
    // Twitter Card tags
    echo "\n<!-- Twitter Card Meta Tags -->\n";
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
    if ( !empty($og_image) ) {
      echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    // Additional meta tags for imovel post type - USANDO AS PROPRIEDADES CORRETAS
    if ( is_singular('imovel') ) {
      // Preço (venda, lançamento ou locação)
      echo '<meta property="product:retailer_item_id" content="' . esc_attr($post->referencia) . '" />' . "\n";
      if (isset($post->novo) && !empty($post->novo) && $post->novo == '1' ) {
        echo '<meta property="product:condition" content="new" />' . "\n";
      } ;
      $empresa = getEmpresa( 1 );
      if ( !empty($empresa) ) {
        echo '<meta property="product:brand" content="' . esc_attr($empresa->codNome) . '" />' . "\n";
      }

      $preco = '';
      if ( isset($post->ativarVenda) && $post->ativarVenda == '1' && !empty($post->vendaValor) ) {
        $preco = $post->vendaValor;
        echo '<meta property="og:availability" content="for_sale" />' . "\n";
      } elseif ( isset($post->ativarLancamento) && $post->ativarLancamento == '1' && !empty($post->lancamentoValor) ) {
        echo '<meta property="og:availability" content="for_sale" />' . "\n";
        $preco = $post->lancamentoValor;
      } elseif ( isset($post->ativarLocacao) && $post->ativarLocacao == '1' && !empty($post->locacaoValor) ) {
        echo '<meta property="og:availability" content="for_rent" />' . "\n";
        $preco = $post->locacaoValor;
      }
      echo '<meta property="og:property:status" content="Disponível" />' . "\n";
      echo '<meta property="product:availability" content="in stock" />' . "\n";
      echo '<!-- DEBUG -->';
      $matriz = [
        [ 'tag'=> 'og:property:bedrooms', 'label'=> 'dormitorio' ] // Dormitorios
        , [ 'tag'=> 'og:property:bedrooms', 'label'=> 'quartos' ] // Quartos
        , [ 'tag'=> 'og:property:bathrooms', 'label'=> 'banheiros' ] // Banheiros
        , [ 'tag'=> 'og:property:suites', 'label'=> 'suites' ] // Suítes
        , [ 'tag'=> 'og:property:parking_spaces', 'label'=> 'garage' ] // Vagas
        , [ 'tag'=> 'og:property:furnished', 'label'=> 'mobiliado' ] // Mobiliado
        , [ 'tag'=> 'og:property:pets_allowed', 'label'=> 'aceita pets' ] // Aceita pets
        , [ 'tag'=> 'og:property:floor', 'label'=> 'numero do andar' ] // Numero do andar
        , [ 'tag'=> 'og:property:total_floors', 'label'=> 'andares' ] // Numero de andares
      ];
      foreach ( $matriz as $m) {
        $area_struct = get_meta_value( $post, $m['label'] );
        if ( $area_struct && isset($area_struct['valor']) && !empty($area_struct['valor']) ) {
          echo '<meta property="' . $m['tag'] .  '" content="' . esc_attr($area_struct['valor']) . '" />' . "\n";
        }
      }
      // Ano de Construção
      $ano = $post->anoConstrucao;
      if ( !empty($ano) ) {
        echo '<meta property="og:property:year_built" content="' . esc_attr($ano) . '" />' . "\n";
      }
      //Area Útil ou Área Total
      $area_struct = get_meta_value( $post, 'area util' );
      if ( $area_struct && isset($area_struct['valor']) && !empty($area_struct['valor']) && is_long( $area_struct['valor'] ) ) {
        // Area total
        echo '<meta property="product:size" content="' . esc_attr($area_struct['valor']) . ' m²" />' . "\n";
        echo '<meta property="og:product:size" content="' . esc_attr($area_struct['valor']) . '" />' . "\n";
        if ( floatval( $area_struct['valor'] ) > 0 ) {
          // Preço por m²
          echo '<meta property="og:property:price_per_sqm" content="' . intval( floatval( $preco ) / floatval( $area_struct['valor'] ) ) . '" />';
        }
      } else {
        $area_struct = get_meta_value( $post, 'area total' );
        if ( $area_struct && isset($area_struct['valor']) && !empty($area_struct['valor']) && is_long( $area_struct['valor'] ) ) {
          // Area total
          echo '<meta property="product:size" content="' . esc_attr($area_struct['valor']) . ' m²" />' . "\n";
          echo '<meta property="og:product:size" content="' . esc_attr($area_struct['valor']) . '" />' . "\n";
          if ( floatval( $area_struct['valor'] ) > 0 ) {
            // Preço por m²
            echo '<meta property="og:property:price_per_sqm" content="' . intval( floatval( $preco ) / floatval( $area_struct['valor'] ) ) . '" />';
          }
        }
      }
      echo '<!-- DEBUG -->';
      if ( !empty($preco) ) {
        echo '<meta property="og:price:amount" content="' . esc_attr($preco) . '" />' . "\n";
        echo '<meta property="og:price:currency" content="BRL" />' . "\n";
      }
      // Condomínio
      $condominio = $post->valorCondominio;
      if ( !empty($condominio) ) {
        echo '<meta property="og:property:condo_fee" content="' . $condominio . '" />' . "\n";
      }
      // IPTU
      $iptu = $post->valorIptu;
      if ( !empty($iptu) ) {
        echo '<meta property="og:property:property_tax" content="' . $iptu . '" />' . "\n";
      }
      // Localização
      if (!empty($post->cidade)) {
        echo '<meta property="og:locality" content="' . esc_attr($post->cidade) . '" />' . "\n";
      }
/*      if ( false ) {
        // Endereço
        echo '<meta property="og:street-address" content="' . esc_attr($post->enderecoRenderizado) . '" />' . "\n";
      }*/
      //Estado
      if (!empty($post->estado)) {
        echo '<meta property="og:region" content="' . esc_attr($post->estado) . '" />' . "\n";
      }
      //Cep
      if (!empty($post->cep)) {
        echo '<meta property="og:postal-code" content="' . esc_attr(formata_cep($post->cep)) . '" />' . "\n";
      }
      echo '<meta property="og:country-name" content="Brasil" />' . "\n";
      //Geoposicao
      if ( !empty($post->latitude) && !empty($post->longitude) && (floatval($post->latitude) != 0) && (floatval($post->longitude) != 0) ) {
        echo '<meta property="og:latitude" content="' . $post->latitude . '" />' . "\n";
        echo '<meta property="og:longitude" content="' . $post->longitude . '" />' . "\n";
      }

      // Tipo de imóvel
      echo '<meta property="product:category" content="' . esc_attr($post->tipoImovelNome) . '" />' . "\n";
      // Referência
      echo '<meta property="product:item_group_id" content="' . esc_attr($post->referencia) . '" />' . "\n";
    }
    echo "<!-- End Open Graph Meta Tags -->\n\n";
  }
}
add_action('wp_head', 'Air_Light\pinedu_add_open_graph_meta_tags');
