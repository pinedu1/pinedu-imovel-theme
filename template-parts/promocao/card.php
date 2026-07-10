<?php
// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
  require_once get_template_directory( ) . '/inc/classes/Promocoes.php';
  global $post;
  $destaques = Air_Light\busca_campos_destaque_card( $post );
  $icons = [ 'DOR' => 'fa fa-bed', 'SUI' => 'fa fa-shower', 'BAN' => 'fa fa-bath', 'GAR' => 'fa fa-car', 'COZ' => 'fa-solid fa-kitchen-set', 'PIS' => 'fa-solid fa-person-swimming', 'PISPRV' => 'fa-solid fa-person-swimming', 'SAL' => 'fa-solid fa-couch', 'ARS' => 'fa fa-brush', 'INTERFON' => 'fa fa-bell', 'ARCOND' => 'fa fa-snowflake', 'ARUTIL' => 'fa-solid fa-ruler-combined', 'ARCONS' => 'fa-solid fa-draw-polygon', 'ARTOT' => 'fa-solid fa-circle-nodes' ];
?>
<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="cardPromocao">
  <section class="property-card promocao" itemscope itemtype="http://schema.org/RealEstateListing">
    <!-- Seção da Foto -->
    <figure class="property-image" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
      <?php
        $thumbnail_attrs = array( 'class' => 'property-thumbnail', 'alt' => get_the_title( ), 'itemprop' => 'url' );
        the_post_thumbnail( 'medium', $thumbnail_attrs );
      ?>
    </figure>
    <table class="caracteristicas" role="table" aria-label="Informações básicas do imóvel">
      <tbody>
        <tr>
        <?php if ( true ): ?>
          <?php if ( isset( $destaques['ARU'] ) ) : $d = $destaques['ARU']; ?><td itemprop="floorSize" title="<?php echo $d['nome']; ?>"><?php echo formata_valor( $d['valor'], 0 ); ?> <i class="<?php echo $icons['ARUTIL']; ?>"></i></td><?php endif; ?>
          <?php if ( isset( $destaques['ART'] ) ) : $d = $destaques['ART']; ?><td itemprop="floorSize" title="<?php echo $d['nome']; ?>"><?php echo formata_valor( $d['valor'], 0 ); ?> <i class="<?php echo $icons['ARTOT']; ?>"></i></td><?php endif; ?>
          <?php if ( isset( $destaques['DOR'] ) ) : $d = $destaques['DOR']; ?><td itemprop="numberOfRooms" title="<?php echo $d['nome']; ?>"><?php echo $d['valor']; ?> <i class="<?php echo $icons['DOR']; ?>"></i></td><?php endif; ?>
          <?php if ( isset( $destaques['SUI'] ) ) : $d = $destaques['SUI']; ?><td itemprop="numberOfRooms" title="<?php echo $d['nome']; ?>"><?php echo $d['valor']; ?> <i class="<?php echo $icons['SUI']; ?>"></i></td><?php endif; ?>
          <?php if ( isset( $destaques['BAN'] ) ) : $d = $destaques['BAN']; ?><td itemprop="numberOfBathroomsTotal" title="<?php echo $d['nome']; ?>"><?php echo $d['valor']; ?> <i class="<?php echo $icons['BAN']; ?>"></i></td><?php endif; ?>
          <?php if ( isset( $destaques['GAR'] ) ) : $d = $destaques['GAR']; ?><td itemprop="parkingSpace" title="<?php echo $d['nome']; ?>"><?php echo $d['valor']; ?> <i class="<?php echo $icons['GAR']; ?>"></i></td><?php endif; ?>
        <?php else : ?>
          <?php if ( isset( $destaques['ARU'] ) ) : $d = $destaques['ARU']; $txt = $d['valor'] . ' ' . corta_texto( $d['nome'], 5 ); ?><td itemprop="floorSize"><?php echo $txt; ?></td><?php endif; ?>
          <?php if ( isset( $destaques['DOR'] ) ) : $d = $destaques['DOR']; $txt = $d['valor'] . ' ' . corta_texto( $d['nome'], 5 ); ?><td itemprop="numberOfRooms"><?php echo $txt ?></td><?php endif; ?>
          <?php if ( isset( $destaques['BAN'] ) ) : $d = $destaques['BAN']; $txt = $d['valor'] . ' ' . corta_texto( $d['nome'], 5 ); ?><td itemprop="numberOfBathroomsTotal"><?php echo $txt; ?></td><?php endif; ?>
          <?php if ( isset( $destaques['GAR'] ) ) : $d = $destaques['GAR']; $txt = $d['valor'] . ' ' . corta_texto( $d['nome'], 5 ); ?><td itemprop="parkingSpace"><?php echo $txt; ?></td><?php endif; ?>
        <?php endif; ?>
        </tr>
      </tbody>
    </table>
    <!-- Detalhes do imóvel -->
    <div class="property-details">
      <h4 itemprop="additionalType">
        <meta itemprop="name" content="Tipo do Imóvel" />
        <?php echo $post->tipoImovelNome; ?>
      </h4>
      <!-- Segunda tabela ( 4 colunas, 3 linhas ) -->
      <table role="table" aria-label="Detalhes do imóvel">
        <tbody>
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
              <span itemprop="value"><?php echo Promocoes::formata_valor( $post->valorCondominio, 0, 'R$ ' ); ?></span>
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
              <span itemprop="value"><?php echo Promocoes::formata_valor( $post->valorIptu, 0, 'R$ ' ); ?></span>
              <meta itemprop="unitText" content="BRL" />
            </td>
          </tr>
        <?php endif; ?>
        <?php if ( 1 == intval( $post->ativarVenda ) ): ?>
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
                <span itemprop="price"><?php echo Promocoes::formata_valor( $post->vendaValor, 0 ); ?></span>
              </div>
            </td>
          </tr>
        <?php endif; ?>
        <?php if ( 1 == intval( $post->ativarLancamento ) ): ?>
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
                <span itemprop="price"><?php echo Promocoes::formata_valor( $post->lancamentoValor, 0 ); ?></span>
              </div>
            </td>
          </tr>
        <?php endif; ?>
        <?php if ( 1 == intval( $post->ativarLocacao ) ): ?>
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
                <span itemprop="price"><?php echo Promocoes::formata_valor( $post->locacaoValor, 0 ); ?></span>
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
      <div aria-label="Identificar Unidade">
        <span colspan="2" itemscope itemtype="https://schema.org/Product">
          <link itemprop="url" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" />
          <meta itemprop="availability" content="https://schema.org/InStock" />
          Referência: <span itemprop="sku"><?php echo $post->referencia; ?>
        </span>
      </div>
    </footer>
  </section>
</a>
