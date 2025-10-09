<?php
/**
 * Template for displaying the footer
 *
 * Site footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package air-light
 */

namespace Air_Light;

?>

      </div><!-- #content -->
    <footer id="colophon" class="site-footer">
      <?php echo get_template_part('template-parts/footer/footer-template', 'copyright'); ?>
    </footer><!-- #colophon -->
    <?php echo get_template_part('template-parts/footer/colophon', 'copyright'); ?>
  </div><!-- #page -->
  <?php wp_footer(); ?>
  <?php echo get_template_part('template-parts/footer/privacy-policy'); ?>
  <a href="#page" id="top" class="top no-external-link-indicator" data-version="<?php echo esc_attr( AIR_LIGHT_VERSION ); ?>" >
    <span class="screen-reader-text"><?php echo esc_html( get_default_localization( 'Back to top' ) ); ?></span>
    <span aria-hidden="true">&uarr;</span>
  </a>
  </body>
</html>
