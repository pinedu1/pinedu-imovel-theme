<?php
$valorVenda = isset($_REQUEST['valorVenda']) ? intval($_REQUEST['valorVenda']) : '';
$valorLocacao = isset($_REQUEST['valorLocacao']) ? intval($_REQUEST['valorLocacao']) : '';
?>
<div class="row row-half">
  <div class="col col-checkbox">
    <input type="checkbox" class="form-check-input" id="ativarVenda" name="ativarVenda" onChange="vendaOnChange(this);" placeholder="Ativar Venda">
  </div>
  <div class="col col-label">
    <label class="form-check-label" for="valorVenda" >Ativar para Venda</label>
  </div>
  <div class="col col-valor">
    <input type="number" class="form-control" id="valorVenda" name="valorVenda" placeholder="Valor de Venda" value="<?php echo $valorVenda; ?>" disabled required title="Valor de Venda. (Ative este campo para definir um valor)">
  </div>
  <div class="flex-spacer"></div>
</div>
<div class="row row-half">
  <div class="col col-checkbox">
    <input type="checkbox" class="form-check-input" id="ativarLocacao" name="ativarLocacao" onChange="locacaoOnChange(this);" placeholder="Ativar Locação">
  </div>
  <div class="col col-label">
    <label class="form-check-label" for="valorLocacao" >Ativar para Locação</label>
  </div>
  <div class="col col-valor">
    <input type="number" class="form-control" id="valorLocacao" name="valorLocacao" placeholder="Valor de Locação" value="<?php echo $valorLocacao; ?>" disabled required title="Valor de Venda. (Ative este campo para definir um valor)">
  </div>
  <div class="flex-spacer"></div>
</div>
<footer id="passoPosNegociacao" class="row">
  <div class="row mensagem">
    <p>Escolha o tipo de Negociação que você deseja realizar e defina o valor de Negociação que pretende para sua propriedade; pelo menos um dos valores será necessário.</p>
  </div>
  <div class="row buttom">
    <button id="btnProximo" type="button" class="btn btn-primary mt-2" onclick="passoPosNegociacao(event);">Próximo</button>
  </div>
</footer>
