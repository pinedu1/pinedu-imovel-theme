<?php global $post; ?>
<swiper-slide>
  <?php the_post_thumbnail('full', array( 'class' => 'property-thumbnail', 'alt' => $post->post_title, 'itemprop' => 'url' ) ); ?>
</swiper-slide>
