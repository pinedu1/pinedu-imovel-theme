<div class="row">
  <div class="col col-full">
    <label>Fotos do Imóvel</label>
    <div class="dropzone" id="property-dropzone">
      <div class="dz-message">
        Arraste fotos aqui ou clique para selecionar<br>
        <span class="note">(Máximo 10 fotos, até 5MB cada)</span>
      </div>
      <input type="hidden" name="uploaded_files" id="uploaded_files">
    </div>
  </div>
</div>
<footer class="row">
  <div id="passoPosCaracteristicas" class="row">
    <div class="row mensagem">
      <p>Acima, possibilitamos que você envie as fotos que podem impulsionar o seu imóvel.</p>
      <p>Uma fotografia vale mais que 100 palavras. Selecione as fotografias que são os pontos fortes da sua propriedade.</p>
    </div>
    <div class="row buttom">
      <button id="btnProximo" type="button" class="btn" onclick="passoPosFotos(event);">Próximo</button>
    </div>
  </div>
</footer>
<script>
  jQuery(document).ready(function($) {
    instalaDropZone();
  });
</script>
