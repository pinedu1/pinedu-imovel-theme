<section class="content visinhanca">
  <article class="card-container <?php echo $this->getClass(); ?>">
    <header><h3><?php echo $this->getTitulo(); ?></h3></header>
    <div class="visinhanca-grid">
      <?php while ($this->query->have_posts()) : $this->query->the_post();
        the_content();
      endwhile; ?>
    </div>
  </article>
</section>
