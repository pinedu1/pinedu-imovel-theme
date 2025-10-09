<?php
require_once get_template_directory() . '/inc/classes/PineduPostType.php';
require_once get_template_directory() . '/inc/classes/Pinedu_Base.php';
class Slides extends Pinedu_Base implements PineduPostType {
  private const TEMPLATE = 'template-parts/slider/container-home-topo.php';
  private const CARD = 'template-parts/slider/card-home-topo.php';
  private $class = 'slides';

  private $loop;
  private $navigation;
  private $pagination;
  private $autoplay;
  private $autoplay_delay;
  private $speed;
  private $max;
  private $query;
  public function __construct( $class = 'slides', $max = -1, $loop = true, $navigation = true, $pagination = true, $autoplay = true, $autoplay_delay = 10000, $speed = 600 ) {
    $this->max = $max;
    $this->class = $class;
    $this->loop = $loop;
    $this->navigation = $navigation;
    $this->pagination = $pagination;
    $this->autoplay = $autoplay;
    $this->autoplay_delay = $autoplay_delay;
    $this->speed = $speed;

    $this->query = $this->query();
  }
  public function query() {
    $args = array(
      'post_type' => 'slide'
      , 'post_status' => 'publish'
      , 'posts_per_page' => $this->max
    );
    return new \WP_Query( $args );
  }
  public function render() {
    if ($this->query->have_posts()):
      add_filter('the_title', [$this, 'pinedu_slider_titulo']);
      add_filter('the_content', [$this, 'pinedu_slider_conteudo']);
      include locate_template(self::TEMPLATE);
      wp_reset_postdata();
      remove_filter('the_title', [$this, 'pinedu_slider_titulo']);
      remove_filter('the_content', [$this, 'pinedu_slider_conteudo']);
    endif;
  }
  public function pinedu_slider_titulo() {
    return false;
  }
  public function pinedu_slider_conteudo() {
    include locate_template( self::CARD );
    return false;
  }
  public function getClass(): mixed {
    return $this->class;
  }

  public function setClass( mixed $class ): void {
    $this->class = $class;
  }

  public function getMax(): mixed {
    return $this->max;
  }

  public function setMax( mixed $max ): void {
    $this->max = $max;
  }
  public function getLoop(): mixed {
    return $this->loop;
  }

  public function setLoop( mixed $loop ): void {
    $this->loop = $loop;
  }

  public function getNavigation(): mixed {
    return $this->navigation;
  }

  public function setNavigation( mixed $navigation ): void {
    $this->navigation = $navigation;
  }

  public function getPagination(): mixed {
    return $this->pagination;
  }

  public function setPagination( mixed $pagination ): void {
    $this->pagination = $pagination;
  }

  public function getAutoplay(): mixed {
    return $this->autoplay;
  }

  public function setAutoplay( mixed $autoplay ): void {
    $this->autoplay = $autoplay;
  }

  public function getAutoplayDelay(): mixed {
    return $this->autoplay_delay;
  }

  public function setAutoplayDelay( mixed $autoplay_delay ): void {
    $this->autoplay_delay = $autoplay_delay;
  }

  public function getSpeed(): mixed {
    return $this->speed;
  }

  public function setSpeed( mixed $speed ): void {
    $this->speed = $speed;
  }
}
