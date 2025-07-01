<?php
  require_once get_template_directory() . '/inc/classes/Visitados.php';
  global $post;
?>
<section class="property-card <?php echo $this->getClass(); ?>" itemscope itemtype="http://schema.org/RealEstateListing">
  <!-- Topo: Container -->
  <div class="property-container">
    <!-- Esquerda: Imagem -->
    <figure class="property-image" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
      <?php $thumbnail_attrs = array( 'class' => 'property-thumbnail', 'alt' => get_the_title(), 'itemprop' => 'url' ); the_post_thumbnail('medium', $thumbnail_attrs); ?>
    </figure>
    <!-- Direita: Conteúdo -->
    <div class="property-details">
      <div class="property-info">
        <div class="info-row" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
          <meta itemprop="name" content="Tipo do Imóvel">
          <span class="label">Tipo</span>
          <span class="value" itemprop="value"><?php echo $post->tipoImovelNome; ?></span>
        </div>
        <div class="info-row" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
          <span class="label">Cidade</span>
          <span class="value" itemprop="addressLocality"><?php echo $post->cidade; ?></span>
        </div>
        <?php if( $post->regiao ): ?>
        <div class="info-row" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
          <span class="label">Bairro</span>
          <span class="value" itemprop="addressRegion"><?php echo $post->regiao; ?></span>
        </div>
        <?php endif;?>
        <?php if( $post->DOR ): ?>
        <div class="info-row" itemprop="numberOfRooms">
          <span class="label"><?php echo $post->DORNome; ?></span>
          <span class="value"><?php echo $post->DOR; ?></span>
        </div>
        <?php endif;?>
        <?php if( $post->BAN ): ?>
        <div class="info-row" itemprop="numberOfBathroomsTotal">
          <span class="label"><?php echo $post->BANNome; ?></span>
          <span class="value"><?php echo $post->BAN; ?></span>
        </div>
        <?php endif;?>
        <?php if( $post->GAR ): ?>
        <div class="info-row" itemprop="parkingSpace">
          <span class="label"><?php echo $post->GARNome; ?></span>
          <span class="value"><?php echo $post->GAR; ?></span>
        </div>
        <?php endif;?>
        <?php if( $post->ARUTIL ): ?>
        <div class="info-row" itemprop="floorSize">
          <span class="label"><?php echo $post->ARUTILNome; ?></span>
          <span class="value"><?php echo $post->ARUTIL; ?></span>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
  <!-- Bottom: footer -->
  <div class="property-footer">
    <div class="price-box" itemprop="offers" itemscope itemtype="http://schema.org/Offer" title="Venda">
      <?php if ( $post->ativarVenda == '1' ): ?>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
          <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
          <link itemprop="availability" href="https://schema.org/InStock" />
          <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
          <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
          <meta itemprop="price" content="<?php echo $post->vendaValor; ?>" />
          <span itemprop="priceCurrency" content="BRL">R$</span>
          <span itemprop="price"><?php echo formata_valor( $post->vendaValor, 0); ?></span>
        </div>
      <?php endif; ?>
      <?php if ( $post->ativarLancamento == '1' ): ?>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" title="Venda">
          <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
          <link itemprop="availability" href="https://schema.org/InStock" />
          <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
          <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
          <meta itemprop="price" content="<?php echo $post->lancamentoValor; ?>" />
          <span itemprop="priceCurrency" content="BRL">R$</span>
          <span itemprop="price"><?php echo formata_valor( $post->lancamentoValor, 0); ?></span>
        </div>
      <?php endif; ?>
      <?php if ( $post->ativarLocacao == '1' ): ?>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" title="Locação">
          <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#LeaseOut"/>
          <link itemprop="availability" href="https://schema.org/InStock" />
          <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
          <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
          <meta itemprop="price" content="<?php echo $post->locacaoValor; ?>" />
          <span itemprop="priceCurrency" content="BRL">R$</span>
          <span itemprop="price"><?php echo formata_valor( $post->locacaoValor, 0); ?></span>
        </div>
      <?php endif; ?>
    </div>
    <div class="reference" itemscope itemtype="https://schema.org/Product">
      <link itemprop="url" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" />
      <meta itemprop="availability" content="https://schema.org/InStock" />
      <span><a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">Ref: <span itemprop="sku"><?php echo $post->referencia; ?></span></a></span>
    </div>
  </div>
</section>
