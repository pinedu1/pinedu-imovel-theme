<?php
/**
 * Enqueue and localize theme scripts and styles.
 *
 * @package air-light
 */

namespace Air_Light;

/**
 * Move jQuery to footer
 */
function move_jquery_into_footer( $wp_scripts ) {
  if ( ! is_admin() ) {
    $wp_scripts->add_data( 'jquery',         'group', 1 );
    $wp_scripts->add_data( 'jquery-core',    'group', 1 );
    $wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
  }
} // end air_light_move_jquery_into_footer
function ajax_link_nom_priv() {
  wp_localize_script( 'scripts', 'ajax_object', [
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
  ] );
}
/**
 * Enqueue scripts and styles.
 */
function enqueue_theme_scripts() {
  // Enqueue global.css
  wp_enqueue_style( 'styles',
    get_theme_file_uri( get_asset_file( 'global.css' ) )
    , array( 'dashicons' )
    , filemtime( get_theme_file_path( get_asset_file( 'global.css' ) ) )
  );

  // Enqueue jquery and front-end.js
  wp_enqueue_script( 'jquery-core' );
  enqueue_dropzone_assets();
  enqueue_jquery_ui();
  enqueue_swiper_js();
  //enqueue_glide_js();
  enqueue_font_awesome();

  wp_enqueue_script( 'scripts', get_theme_file_uri( get_asset_file( 'main.js' ) ), [], filemtime( get_theme_file_path( get_asset_file( 'main.js' ) ) ), true );
  wp_localize_script( 'scripts', 'ajax_object', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );
  // Required comment-reply script
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }

wp_localize_script( 'scripts', 'air_light_screenReaderText', [
    'expand_for'      => get_default_localization( 'Open child menu for' ),
    'collapse_for'    => get_default_localization( 'Close child menu for' ),
    'expand_toggle'   => get_default_localization( 'Open main menu' ),
    'collapse_toggle' => get_default_localization( 'Close main menu' ),
    'external_link'   => get_default_localization( 'External site' ),
    'target_blank'    => get_default_localization( 'opens in a new window' ),
    'previous_slide'  => get_default_localization( 'Previous slide' ),
    'next_slide'      => get_default_localization( 'Next slide' ),
    'last_slide'      => get_default_localization( 'Last slide' ),
    'skip_slider'     => get_default_localization( 'Skip over the carousel element' ),
  ] );

  // Add domains/hosts to disable external link indicators
  wp_localize_script( 'scripts', 'air_light_externalLinkDomains', THEME_SETTINGS['external_link_domains_exclude'] );
  /*
   * Instala meus ShortCodes Pinedu
   * */
  instala_shortcodes();

} // end air_light_scripts

/**
 * Returns the built asset filename and path depending on
 * current environment.
 *
 * @param string $filename File name with the extension
 * @return string file and path of the asset file
 */
function get_asset_file( $filename ) {
  $env = 'development' === wp_get_environment_type() && ! isset( $_GET['load_production_builds'] ) ? 'dev' : 'prod'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

  $filetype = pathinfo( $filename )['extension'];

  return "{$filetype}/{$env}/{$filename}";
} // end get_asset_file
function instala_shortcodes() {
  add_shortcode('barra_pesquisa', 'instala_shortcode_barra_pesquisa');
}
function instala_shortcode_barra_pesquisa( $atts ) {
  require_once get_template_directory() . '/inc/classes/PineduShortCodeSearchBar.php';
  \PineduShortCodeSearchBar::do( $atts );
}
function enqueue_dropzone_assets() {
  wp_enqueue_script( 'dropzone-js', get_theme_file_uri('/assets/js/vendor/dropzone/dropzone-min.js'), array(), filemtime(get_theme_file_path('/assets/js/vendor/dropzone/dropzone-min.js')), true );
  wp_enqueue_style( 'dropzone-css', get_theme_file_uri('/assets/css/vendor/dropzone/dropzone.css'), array(), filemtime(get_theme_file_path('/assets/css/vendor/dropzone/dropzone.css')) );
}
function enqueue_jquery_ui() {
  wp_enqueue_script( 'jquery-ui-autocomplete', get_theme_file_uri('/assets/js/vendor/jquery-ui/jquery-ui.js' ), array( 'jquery' ), '1.13.2', true );
  wp_enqueue_style( 'custom-autocomplete-css', get_theme_file_uri('/assets/css/vendor/jquery-ui/jquery-ui.css'), array(), '1.13.2' );
  //wp_enqueue_script( 'jquery-ui-autocomplete', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js', array( 'jquery' ), '1.13.2', true );
  //wp_enqueue_style( 'custom-autocomplete-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css', array(), '1.13.2' );
}
function enqueue_swiper_js() {
  wp_enqueue_script( 'swiper-js', get_theme_file_uri('/assets/js/vendor/swiper/swiper-element-bundle.js'), array(), '11.2.8', true );
  wp_enqueue_style( 'swiper-css', get_theme_file_uri('/assets/css/vendor/swiper/swiper-bundle.css'), array(), '11.2.8' );
}
function enqueue_glide_js() {
  wp_enqueue_script( 'swiper-js', get_theme_file_uri('/assets/js/vendor/glide/glide.esm.js'), array(), '11.2.8', true );
  wp_enqueue_style( 'swiper-css', get_theme_file_uri('/assets/css/vendor/glide/glide.core.css'), array(), '11.2.8' );
}
function enqueue_font_awesome() {
  //wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
  wp_enqueue_style( 'font-awesome', get_theme_file_uri('/node_modules/@fortawesome/fontawesome-free/css/all.css'), array(), filemtime(get_theme_file_path('/node_modules/@fortawesome/fontawesome-free/css/all.min.css') ) );
  wp_enqueue_script( 'font-awesome', get_theme_file_uri('/node_modules/@fortawesome/fontawesome-free/js/all.js'), array(), filemtime(get_theme_file_path('/node_modules/@fortawesome/fontawesome-free/js/all.min.js')), true );
}
