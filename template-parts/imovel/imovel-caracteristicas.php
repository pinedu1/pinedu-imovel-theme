<?php
global $post;
$dependencias = get_tipo_dependencias_imovel( $post->ID );
unset( $dependencias['LOCALIZACAO'] );
$titulo = [
  'CARACTERISTICAS' => 'Características'
  , 'CONDOMINIO' => 'Condomínio'
  , 'EDIFICIO' => 'Edifício'
  , 'INFRAEXTRUTURA' =>'Infraextrutura'
]
?>
<section class="referencia">
  <div class="container">
    <span itemscope itemtype="https://schema.org/Product">
      <link itemprop="url" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" />
      <meta itemprop="availability" content="https://schema.org/InStock" />
      <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">Referência: <span itemprop="sku"><?php echo $post->referencia; ?></a>
    </span>
  </div>
</section>
<section class="misc">
  <div class="container">
    <section class="detalhes informacoes">
      <h4>Informações</h4>
      <div class="info-top">
        <div class="info-row" title="Tipo do Imóvel / Bem" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
          <i class="fa-solid fa-house-chimney"></i> <span class="label">Tipo</span>
          <span class="value"><?php echo esc_html($post->tipoImovelNome); ?></span>
        </div>
        <div class="info-row" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" title="Cidade">
          <i class="fa-solid fa-city"></i> <span itemprop="addressLocality" class="label">Cidade</span>
          <span class="value"><?php echo $post->cidade; ?></span>
        </div>
        <div class="info-row" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" title="Bairro">
          <i class="fa-solid fa-map-marker-alt"></i> <span itemprop="addressRegion" class="label">Bairro</span>
          <span class="value"><?php echo $post->regiao; ?></span>
        </div>
      </div>
      <div class="separador-vertical"></div>
      <div class="info-bottom">
        <?php if ( isset( $post->valorCondominio ) && ( $post->valorCondominio > 0 ) ): ?>
          <div class="info-row valor" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue" title="Valor do Condomínio">
            <i class="fa-solid fa-money-bills"></i> <meta itemprop="name" content="Condomínio" /><span class="label">Condomínio</span>&nbsp;
            <span class="value" itemprop="value"><?php echo formata_valor( $post->valorCondominio, 0, 'R$ '); ?></span>
          </div>
        <?php endif; ?>
        <?php if ( isset( $post->valorCondominio ) && ( $post->valorCondominio > 0 ) ): ?>
          <div class="info-row valor" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue" title="Valor do IPTU">
            <i class="fa-solid fa-money-bills"></i> <meta itemprop="name" content="IPTU" /><span class="label">IPTU</span>&nbsp;
            <span class="value" itemprop="value"><?php echo formata_valor( $post->valorIptu, 0, 'R$ '); ?></span>
          </div>
        <?php endif; ?>
        <?php if ( $post->ativarVenda == '1' ): ?>
          <div class="info-row valor" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue" title="Valor para Venda">
            <i class="fa-solid fa-handshake"></i> <span class="label" itemprop="name" content="Venda">Venda</span>
            <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
            <link itemprop="availability" href="https://schema.org/InStock" />
            <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
            <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
            <meta itemprop="price" content="<?php echo $post->vendaValor; ?>" />
            <span class="value" itemprop="priceCurrency" content="BRL">R$ <?php echo formata_valor( $post->vendaValor, 0); ?></span>
          </div>
        <?php endif; ?>
        <?php if ( $post->ativarLancamento == '1' ): ?>
          <div class="info-row valor" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue" title="Valor para Venda">
            <i class="fa-solid fa-handshake"></i> <meta itemprop="name" content="Venda" /><span class="label">Venda</span>&nbsp;
            <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
            <link itemprop="availability" href="https://schema.org/InStock" />
            <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
            <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
            <meta itemprop="price" content="<?php echo $post->lancamentoValor; ?>" />
            <span class="value" itemprop="priceCurrency" content="BRL">R$ <?php echo formata_valor( $post->lancamentoValor, 0); ?></span>
          </div>
        <?php endif; ?>
        <?php if ( $post->ativarLocacao == '1' ): ?>
          <div class="info-row valor" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue" title="Valor para Aluguel">
            <i class="fa-solid fa-handshake"></i> <meta itemprop="name" content="Venda" /><span class="label">Locação</span>&nbsp;
            <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#LeaseOut"/>
            <link itemprop="availability" href="https://schema.org/InStock" />
            <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
            <meta itemprop="businessFunction" content="http://purl.org/goodrelations/v1#Sell"/>
            <meta itemprop="price" content="<?php echo $post->locacaoValor; ?>" />
            <span class="value" itemprop="priceCurrency" content="BRL">R$ <?php echo formata_valor( $post->locacaoValor, 0); ?></span>
          </div>
        <?php endif; ?>
      </div>
    </section>
    <?php foreach ( $dependencias as $relativo => $deps ) : ?>
      <section class="detalhes <?php echo strtolower( $relativo ); ?>">
        <h4><?php echo $titulo[ $relativo ]; ?></h4>
        <?php foreach ( $deps as $caracteristica ): ?>
          <div class="info-row" title="<?php echo $caracteristica['descricao']; ?>">
            <i class="<?php echo $caracteristica[ 'icone' ]; ?>"></i> <span class="label"><?php echo $caracteristica['nome']; ?></span>
            <span class="value">
              <?php
                switch ($caracteristica['tipo']) {
                  case 'TEXTO':
                    echo $caracteristica['valor'];
                    break;
                  case 'INTEIRO':
                  case 'INTEIRO_TEXTO':
                    echo $caracteristica['valor'];
                    break;
                  case 'FLOAT':
                  case 'FLOAT_TEXTO':
                    echo formata_valor( $caracteristica['valor'], 2 );
                    break;
                  case 'BOOLEAN':
                  case 'BOOLEAN_TEXTO':
                    echo (((boolean) $caracteristica['valor'])? '': '');
                    break;
                }
              ?></span>
          </div>
        <?php endforeach; ?>
      </section>
    <?php endforeach; ?>
  </div>
</section>
