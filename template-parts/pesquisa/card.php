<?php
  global $post;

  $icons = [ 'DOR' => 'fa fa-bed', 'SUI' => 'fa fa-shower', 'BAN' => 'fa fa-bath', 'GAR' => 'fa fa-car', 'COZ' => 'fa-solid fa-kitchen-set', 'PIS' => 'fa-solid fa-person-swimming', 'PISPRV' => 'fa-solid fa-person-swimming', 'SAL' => 'fa-solid fa-couch', 'ARS' => 'fa fa-brush', 'INTERFON' => 'fa fa-bell', 'ARCOND' => 'fa fa-snowflake', 'ARUTIL' => 'fa-solid fa-ruler-combined', 'ARCONS' => 'fa-solid fa-draw-polygon', 'ARTOT' => 'fa-solid fa-circle-nodes' ];
?>
<section class="property-card pesquisa" itemscope itemtype="http://schema.org/RealEstateListing">
  <!-- Seção da Foto -->
  <figure class="property-image" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
    <?php
      $thumbnail_attrs = array( 'class' => 'property-thumbnail', 'alt' => get_the_title(), 'itemprop' => 'url' );
      the_post_thumbnail('medium', $thumbnail_attrs);
    ?>
  </figure>
  <table class="caracteristicas" role="table" aria-label="Informações básicas do imóvel">
    <tbody>
      <tr>
      <?php if ( true ): ?>
        <?php if( $post->ARUTIL ): ?><td itemprop="floorSize" title="<?php echo  $post->ARUTILNome; ?>"><?php echo formata_valor( $post->ARUTIL, 0); ?> <i class="<?php echo  $icons[ 'ARUTIL' ]; ?>"></i></td><?php endif;?>
        <?php if( $post->DOR ): ?><td itemprop="numberOfRooms" title="<?php echo  $post->DORNome; ?>"><?php echo $post->DOR; ?> <i class="<?php echo $icons[ 'DOR' ]; ?>"></i></td><?php endif;?>
        <?php if( $post->SUI ): ?><td itemprop="numberOfRooms" title="<?php echo  $post->SUINome; ?>"><?php echo $post->SUI; ?> <i class="<?php echo $icons[ 'SUI' ]; ?>"></i></td><?php endif;?>
        <?php if( $post->BAN ): ?><td itemprop="numberOfBathroomsTotal" title="<?php echo  $post->BANNome; ?>"><?php echo $post->BAN; ?> <i class="<?php echo $icons[ 'BAN' ]; ?>"></i></td><?php endif;?>
        <?php if( $post->GAR ): ?><td itemprop="parkingSpace" title="<?php echo  $post->GARNome; ?>"><?php echo $post->GAR; ?> <i class="<?php echo $icons[ 'GAR' ]; ?>"></i></td><?php endif;?>
      <?php else: ?>
        <?php if( $post->ARUTIL ): $txt = $post->ARUTIL. " " . corta_texto( $post->ARUTILNome, 5); ?><td itemprop="floorSize"><?php echo $txt; ?></td><?php endif;?>
        <?php if( $post->DOR ): $txt = $post->DORcorta_texto( $post->DORNome, 5 ); ?><td itemprop="numberOfRooms"><?php echo $txt ?></td><?php endif;?>
        <?php if( $post->BAN ): $txt = $post->BANcorta_texto( $post->BANNome, 5); ?><td itemprop="numberOfBathroomsTotal"><?php echo $txt; ?></td><?php endif;?>
        <?php if( $post->GAR ): $txt = $post->GARcorta_texto( $post->GARNome, 5 ); ?><td itemprop="parkingSpace"><?php echo $txt; ?></td><?php endif;?>
      <?php endif; ?>
      </tr>
    </tbody>
  </table>
  <!-- Detalhes do imóvel -->
  <div class="property-details">
    <h4 itemprop="additionalType"><?php echo $post->tipoImovelNome; ?></h4>
    <!-- Segunda tabela (4 colunas, 3 linhas) -->
    <table role="table" aria-label="Detalhes do imóvel">
      <tbody>
      <tr>
        <td colspan="2" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
          <meta itemprop="name" content="Tipo do Imóvel" />
          <span itemprop="value"><?php echo esc_html($post->tipoImovelNome); ?></span>
        </td>
      </tr>
      <tr colspan="2">
        <td itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><span itemprop="addressLocality"><?php echo $post->cidade; ?></span></td>
      </tr>
      <tr>
        <td itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><span itemprop="addressRegion"><?php echo $post->regiao; ?></span></td>
      </tr>
      </tbody>
    </table>
    <table role="table" aria-label="Informações financeiras">
      <tbody>
      <?php if ( isset( $post->valorCondominio ) && ( $post->valorCondominio > 0 ) ): ?>
        <!-- Condomínio -->
        <tr itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
          <td>
            <meta itemprop="name" content="Condomínio" />
            <span>Condomínio</span>
          </td>
          <td>
            <span itemprop="value"><?php echo formata_valor( $post->valorCondominio, 0, 'R$ '); ?></span>
            <meta itemprop="unitText" content="BRL" />
          </td>
        </tr>
      <?php endif; ?>
      <?php if ( isset( $post->valorIptu ) && ( $post->valorIptu > 0 ) ): ?>
        <tr class="iptu">
          <!-- IPTU -->
          <td>
            <meta itemprop="name" content="IPTU" />
            <span>IPTU</span>
          </td>
          <td>
            <span itemprop="value"><?php echo formata_valor( $post->valorIptu, 0, 'R$ '); ?></span>
            <meta itemprop="unitText" content="BRL" />
          </td>
        </tr>
      <?php endif; ?>
      <?php if ( $post->ativarVenda == '1' ): ?>
        <tr class="venda">
          <td>
            <div>Venda</div>
          </td>
          <td itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
            <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
              <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
              <link itemprop="availability" href="https://schema.org/InStock" />
              <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
              <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
              <meta itemprop="price" content="<?php echo $post->vendaValor; ?>" />
              <span itemprop="priceCurrency" content="BRL">R$</span>
              <span itemprop="price"><?php echo formata_valor( $post->vendaValor, 0); ?></span>
            </div>
          </td>
        </tr>
      <?php endif; ?>
      <?php if ( $post->ativarLancamento == '1' ): ?>
        <tr class="lancamento">
          <td>
            <div>Venda</div>
          </td>
          <td itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
            <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
              <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
              <link itemprop="availability" href="https://schema.org/InStock" />
              <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
              <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
              <meta itemprop="price" content="<?php echo $post->lancamentoValor; ?>" />
              <span itemprop="priceCurrency" content="BRL">R$</span>
              <span itemprop="price"><?php echo formata_valor( $post->lancamentoValor, 0); ?></span>
            </div>
          </td>
        </tr>
      <?php endif; ?>
      <?php if ( $post->ativarLocacao == '1' ): ?>
        <tr class="locacao">
          <td>
            <div>Locação</div>
          </td>
          <td itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
            <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
              <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#LeaseOut"/>
              <link itemprop="availability" href="https://schema.org/InStock" />
              <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
              <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
              <meta itemprop="price" content="<?php echo $post->locacaoValor; ?>" />
              <span itemprop="priceCurrency" content="BRL">R$</span>
              <span itemprop="price"><?php echo formata_valor( $post->locacaoValor, 0); ?></span>
            </div>
          </td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
    <?php if ( false ): ?>
      <table role="table" aria-label="Descrição">
        <tbody>
        <tr>
          <td colspan="2" itemprop="description"><p><?php echo $post->anuncioRenderizado; ?></p></td>
        </tr>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  <footer class="property-footer">
    <table role="table" aria-label="Identificar Unidade">
      <tbody>
      <tr>
        <td colspan="2" itemscope itemtype="https://schema.org/Product">
          <link itemprop="url" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" />
          <meta itemprop="availability" content="https://schema.org/InStock" />
          <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">Referência: <span itemprop="sku"><?php echo $post->referencia; ?></a>
        </td>
      </tr>
      </tbody>
    </table>
  </footer>
</section>
