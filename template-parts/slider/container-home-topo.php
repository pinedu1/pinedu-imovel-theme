<section class="slider-topo-main">
  <?php if ( $this->query->have_posts( ) ): ?>
    <!-- Swiper Web Component -->
    <swiper-container class="<?php echo $this->getClass(); ?>"
                      loop="<?php echo $this->getLoop()? 'true': 'false'; ?>"
                      navigation="<?php echo $this->getNavigation()? 'true': 'false'; ?>"
                      pagination="<?php echo $this->getPagination()? 'true': 'false'; ?>"
                      autoplay="<?php echo $this->getAutoplay()? 'true': 'false';?>"
                      autoplay-delay="<?php echo $this->getAutoplayDelay(); ?>"
                      speed="<?php echo $this->getSpeed(); ?>" >
      <?php while ($this->query->have_posts()) : $this->query->the_post();
        the_content();
      endwhile; ?>
    </swiper-container>
  <?php endif; ?>
</section>
