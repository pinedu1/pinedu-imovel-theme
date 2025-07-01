<?php
  $max = $_REQUEST['max']?? 12;
  $sort = $_REQUEST['sort']?? 'dataPreco';
  $ordem = $_REQUEST['ordem']?? 'DESC';
?>
<div class="exibir-resultado">
  <form name="sort" action="#" class="sort">
    <label for="sort">Ordenar</label>
    <select name="sort" id="sort">
      <option value="dataPreco" <?php echo ($sort == 'dataPreco')? 'selected': ''; ?>>Data do Preço</option>
      <option value="valor" <?php echo ($sort == 'valor')? 'selected': ''; ?>>Valor</option>
      <option value="referencia" <?php echo ($sort == 'referencia')? 'selected': ''; ?>>Referência</option>
      <option value="cidade" <?php echo ($sort == 'cidade')? 'selected': ''; ?>>Cidade</option>
      <option value="tipoimovel" <?php echo ($sort == 'tipoimovel')? 'selected': ''; ?>>Tipo de Imóvel</option>
      <option value="regiao" <?php echo ($sort == 'regiao')? 'selected': ''; ?>>Região</option>
      <option value="bairro" <?php echo ($sort == 'bairro')? 'selected': ''; ?>>Bairro</option>
      <option value="finalidade" <?php echo ($sort == 'finalidade')? 'selected': ''; ?>>Finalidade</option>
      <option value="dormitorio" <?php echo ($sort == 'dormitorio')? 'selected': ''; ?>>Qtde. Dormitorios</option>
      <option value="suite" <?php echo ($sort == 'suite')? 'selected': ''; ?>>Qtde. Suites</option>
      <option value="garagem" <?php echo ($sort == 'garagem')? 'selected': ''; ?>>Qtde. Garagens</option>
      <option value="iptu" <?php echo ($sort == 'iptu')? 'selected': ''; ?>>Valor do IPTU</option>
      <option value="condominio" <?php echo ($sort == 'condominio')? 'selected': ''; ?>>Valor do Condomínio</option>
    </select>
  </form>
  <form name="order" action="#" class="direction">
    <label for="ordem">Direção</label>
    <select name="ordem" id="ordem">
      <option value="ASC" <?php echo ($ordem == 'ASC')? 'selected': ''; ?>>Crescente</option>
      <option value="DESC" <?php echo ($ordem == 'DESC')? 'selected': ''; ?>>Decrescente</option>
    </select>
  </form>
  <form name="show" action="#" class="show">
    <label for="max">Exibir</label>
    <select name="max" id="max">
      <option value="4" <?php echo ($max == 4)? 'selected': ''; ?>>4</option>
      <option value="8" <?php echo ($max == 8)? 'selected': ''; ?>>8</option>
      <option value="12" <?php echo ($max == 12)? 'selected': ''; ?>>12</option>
      <option value="24" <?php echo ($max == 24)? 'selected': ''; ?>>24</option>
      <option value="36" <?php echo ($max == 36)? 'selected': ''; ?>>36</option>
      <option value="48" <?php echo ($max == 48)? 'selected': ''; ?>>48</option>
    </select>
  </form>
</div>

