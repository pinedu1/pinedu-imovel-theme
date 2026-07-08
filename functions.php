<?php
/**
Theme Name: Pinedu Imóveis
Theme URI: https://pinedu.com.br/
Author: Eduardo Pinheiro da Silva
Author URI: https://pinedu.com.br/
Description: Tema baseado em Air-light, customizado para o portal Haddad Imóveis.
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: pinedu-imovel-theme
@Date: 2025-10-20 12:30:02
@Last Modified by: Eduardo Pinheiro da Silva
@Last Modified time: 2025-10-20 12:30:02
@Author Email: eduardo@pinedu.com.br
@since 1.0.0
@package pinedu-imovel-theme
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
add_action( 'after_setup_theme', function( ) {
  $theme_settings = [
    /**
     * Theme textdomain
     */
    'textdomain'   => 'air-light',

    /**
     * Content width
     */
    'content_width'   => 800,

    /**
     * Logo and featured image
     */
    'default_featured_image'    => null,
    'logo'                      => '/svg/logo.svg',

    /**
     * Custom setting group settings when using Air setting groups plugin.
     * On multilingual sites using Polylang, translations are handled automatically.
     */
    'custom_settings'   => [
      // 'your-custom-setting'   => [
      //   'id'   => Your custom setting post id,
      //   'title'   => 'Your custom setting',
      //   'block-editor'   => true,
      //  ],
    ],

    'social_media_accounts'    => [
      // 'twitter'   => [
      //   'title'   => 'Twitter',
      //   'url'     => 'https://twitter.com/digitoimistodude',
      // ],
    ],

    /**
     * All links are checked with JS, if those direct to external site and if,
     * indicator of that is included. Exclude domains from that check in this array.
     */
    'external_link_domains_exclude'   => [
      'localhost:3000',
      'airdev.test',
      'airwptheme.com',
      'localhost',
    ],

    /**
     * Menu locations
     */
    'menu_locations'   => [
      'primary'   => __( 'Primary Menu', 'air-light' ),
    ],

    /**
     * Taxonomies
     *
     * See the instructions:
     * https://github.com/digitoimistodude/air-light#custom-taxonomies
     */
    'taxonomies'   => [
      // 'Your_Taxonomy'   => [ 'post', 'page' ],
    ],

    /**
     * Post types
     *
     * See the instructions:
     * https://github.com/digitoimistodude/air-light#custom-post-types
     */
    'post_types'   => [
      'Slide',
      // 'Your_Post_Type',
    ],

    /**
     * Gutenberg -related settings
     */
    // Register custom ACF Blocks
    'acf_blocks'   => [
      // [
      //   'name'             => 'block-file-slug',
      //   'title'            => 'Block Visible Name',
      //   // You can safely remove lines below if you find no use for them
      //   'prevent_cache'    => false, // Defaults to false,
      //   // Icon defaults to svg file inside svg/block-icons named after the block name,
      //   // eg. svg/block-icons/block-file-slug.svg
      //   //
      //   // Icon setting defines the dashicon equivalent: https://developer.wordpress.org/resource/dashicons/#block-default
      //   // 'icon'    => 'block-default',
      // ],
    ],

    // Custom ACF block default settings
    'acf_block_defaults'   => [
      'category'            => 'air-light',
      'mode'                => 'auto',
      'align'               => 'full',
      'post_types'          => [
        'page',
      ],
      'supports'    => [
        'align'             => false,
        'anchor'            => true,
        'customClassName'   => false,
      ],
      'render_callback'     => __NAMESPACE__ . '\render_acf_block',
    ],

    // Restrict to only selected blocks
    //
    // Options: 'none', 'all', 'all-core-blocks', 'all-acf-blocks',
    // or any specific block or a combination of these
    // Accepts both string ( all*/none-options only ) and array ( options + specific blocks )
    'allowed_blocks'   => [
      'post'   => [
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
      'page'   => [],
      // 'page'   => [
      //   'all-acf-blocks',
      //   'core/paragraph',
      // ],
      // 'post-type'   => [
      //   'acf/content-image',
      //   'core/paragraph',
      // ],
      // 'example'   => [
      //   'all-core-blocks',
      //   'acf/content-image',
      // ],
    ],

    // If you want to use classic editor somewhere, define it here
    'use_classic_editor'   => [],

    // Add your own settings and use them wherever you need, for example THEME_SETTINGS['my_custom_setting']
    'my_custom_setting'   => true,
  ];

  $theme_settings = apply_filters( 'air_light_theme_settings', $theme_settings );

  define( 'THEME_SETTINGS', $theme_settings );
} ); // end action after_setup_theme

/**
 * Debug function to print all available blocks
 */
function debug_print_all_blocks( ) {
  $blocks = \WP_Block_Type_Registry::get_instance( )->get_all_registered( );
  $block_names = array_map( function( $block ) {
    return "'" . $block->name . "',";
  }, $blocks );
  echo '<pre>' . implode( "\n", $block_names ) . '</pre>'; // phpcs:ignore
  die( );
}

// Uncomment the following line to see all available blocks:
// add_action( 'init', __NAMESPACE__ . '\\debug_print_all_blocks' );


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
 * If air-helper loads ( for translations ), we unregister the original taxonomies and post types
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
add_theme_support( 'custom-logo', array(
  'height'        => 350, // Altura máxima em pixels
  'width'         => 350, // Largura máxima em pixels
  'flex-height'   => true, // Permite altura flexível
  'flex-width'    => true, // Permite largura flexível
  'header-text'   => array( 'site-title', 'site-description', ), // Texto alternativo
 ) );
function formata_endereco_loja( $tipo, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $cep, $quebrarLinha = true ) {
  $end = $tipo . ' ' . $logradouro;
  if ( ! empty( $numero ) ) {
    $end .= ', ' . $numero;
  }
  if ( ! empty( $complemento ) ) {
    $end .= ' - ' . $complemento;
  }
  if ( ! empty( $bairro ) ) {
    $end .=  ( $quebrarLinha === true ? '<br>' : ' - ') . $bairro;
  }
  if ( ! empty( $cidade ) ) {
    $end .= ' - ' . $cidade;
  }
  if ( ! empty( $estado ) ) {
    $end .= ( $quebrarLinha === true ? '<br>' : '') . $estado . ', Brasil';
  }
  if ( ! empty( $cep ) ) {
    $end .= ( $quebrarLinha === true ? '<br>' : '') . ' Cep: ' . formata_cep( $cep );
  }
  return formatar_title_case( $end );
}
add_filter( 'template_include', function( $template ) {
  if ( false && wp_get_environment_type( ) === 'development' ) {
    echo '<pre>Template usado: ' . $template . '</pre>';
  }
  return $template;
} );
function busca_caracteristicas_imovel( $post ) {
    global $wpdb;

    // Variável estática para manter o cache apenas durante esta requisição
    static $static_cache = [];

    $tipo_imovel = get_post_meta( $post->ID, 'tipoImovel_id', true );

    if ( empty( $tipo_imovel ) ) {
        return [];
    }

    $tipo_imovel_clean = mb_strtolower( $tipo_imovel, 'UTF-8' );
    $cache_key = 'carac_imovel_' . md5( $tipo_imovel_clean );

    // 1. Verifica se já buscamos isso nesta mesma página (Memória RAM)
    if ( isset( $static_cache[$cache_key] ) ) {
        return $static_cache[$cache_key];
    }

    // 2. Tenta buscar do Banco de Dados (Transiente)
    $dependencias = get_transient( $cache_key );

    if ( false === $dependencias ) {
        $like_pattern = $wpdb->esc_like( $tipo_imovel_clean ) . '-%';

        // Otimização: A query parece correta, mas certifique-se que as colunas
        // term_id, taxonomy e slug nas tabelas wp_terms/wp_term_taxonomy estão indexadas.
        $query = $wpdb->prepare("
            SELECT DISTINCT
                t.term_id,
                t.name AS termo_nome,
                t.slug AS termo_slug,
                tm_tipo.meta_value AS meta_tipo,
                tm_sigla.meta_value AS meta_sigla
            FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt
                ON t.term_id = tt.term_id
            INNER JOIN {$wpdb->termmeta} tm_relativo
                ON t.term_id = tm_relativo.term_id AND tm_relativo.meta_key = 'relativo'
            INNER JOIN {$wpdb->termmeta} tm_tipo
                ON t.term_id = tm_tipo.term_id AND tm_tipo.meta_key = 'tipo'
            INNER JOIN {$wpdb->termmeta} tm_sigla
                ON t.term_id = tm_sigla.term_id AND tm_sigla.meta_key = 'sigla'
            WHERE tt.taxonomy = 'tipo-dependencia'
              AND t.slug LIKE %s
              AND tm_relativo.meta_value = 'CARACTERISTICAS';
        ", $like_pattern );

        $dependencias = $wpdb->get_results( $query, ARRAY_A );

        // 3. Salva no Transiente
        set_transient( $cache_key, $dependencias, 10 * MINUTE_IN_SECONDS );
    }

    // 4. Guarda na variável estática para as próximas chamadas no loop
    $static_cache[$cache_key] = $dependencias;

    return $dependencias;
}
function busca_campos_destaque_card( $post ) {
  $campos = get_post_meta( $post->ID, '', false );
  $destaques = [];
  $dependencias = busca_caracteristicas_imovel( $post );
  foreach ( $dependencias as $dependencia ) {
    $sigla = $dependencia['meta_sigla'];
    if ( isset( $campos[ $sigla ]) ) {
      $cp_val = $campos[$sigla][0];
      if ( intval($cp_val) <= 0 ) continue;
      $cp_nome = $campos[ $sigla . 'Nome' ][0];
      // 1. Normalizamos o nome APENAS UMA VEZ
      $nome_norm = normalizar( $cp_nome );
      // 2. Criamos o mapa de Prefixos => Chave do Destaque
      $mapa_destaques = [
        'QUARTO SERV' => 'DEPEMP'
        , 'DORMIT' => 'DOR'
        , 'SUITE' => 'SUI'
        , 'BAN' => 'BAN'
        , 'GARAGE' => 'GAR'
        , 'N DE VAGA' => 'GAR'
        , 'AREA UTIL' => 'ARU'
        , 'AREA P' => 'ARU'
        , 'AREA TOTAL' => 'ART'
        , 'AREA CONS' => 'ARC'
        , 'AREA DO T' => 'ART'
        , 'REPARTI' => 'REP'
        , 'NUMERO DE PAVIMETO' => 'PAV'
        , 'NUMERO DE WC' => 'BAN'
        , 'NUMERO DE BANHE' => 'BAN'
        , 'ESQUINA' => 'ZESQ'
        , 'PATIO' => 'PAT'
        , 'PORTARIA' => 'POR'
        , 'INTERFON' => 'INT'
        , 'PISCINA' => 'PIS'
        , 'INDUSTR' => 'IND'
        , 'COMERCIAL' => 'COM'
        , 'MOBILIADO' => 'MOB'
        , 'QUINTAL' => 'QUI'
        , 'N DE SALA' => 'SAL'
        , 'ESCADA' => 'ESC'
      ];

      // 3. Percorremos o mapa testando o prefixo
      foreach ( $mapa_destaques as $prefixo => $chave ) {
        if ( str_starts_with( $nome_norm, $prefixo ) ) {
          $destaques[ $chave ] = [
            'nome'  => $cp_nome,
            'valor' => $cp_val
          ];
          break;
        }
      }
    }
  }

  return $destaques;
}
