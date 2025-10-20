<?php
  $max = isset( $_REQUEST['max'] ) ? sanitize_text_field( $_REQUEST['max'] ) : 12;
  $sort = isset( $_REQUEST['sort'] ) ? sanitize_text_field( $_REQUEST['sort'] ) : 'dataPreco';
  $ordem = isset( $_REQUEST['ordem'] ) ? sanitize_text_field( $_REQUEST['ordem'] ) : 'DESC';
?>
<div class="exibir-resultado">
  <form name="sort" action="#" class="sort">
    <label for="sort">Ordenar</label>
    <select name="sort" id="sort">
      <option value="dataPreco" <?php echo ( 'dataPreco' == $sort ) ?? 'selected'; ?>>Data do Preço</option>
      <option value="valor" <?php echo ( 'valor' == $sort ) ?? 'selected'; ?>>Valor</option>
      <option value="referencia" <?php echo ( 'referencia' == $sort ) ?? 'selected'; ?>>Referência</option>
      <option value="cidade" <?php echo ( 'cidade' == $sort ) ?? 'selected'; ?>>Cidade</option>
      <option value="tipoimovel" <?php echo ( 'tipoimovel' == $sort ) ?? 'selected'; ?>>Tipo de Imóvel</option>
      <option value="regiao" <?php echo ( 'regiao' == $sort ) ?? 'selected'; ?>>Região</option>
      <option value="bairro" <?php echo ( 'bairro' == $sort ) ?? 'selected'; ?>>Bairro</option>
      <option value="finalidade" <?php echo ( 'finalidade' == $sort ) ?? 'selected'; ?>>Finalidade</option>
      <option value="dormitorio" <?php echo ( 'dormitorio' == $sort ) ?? 'selected'; ?>>Qtde. Dormitorios</option>
      <option value="suite" <?php echo ( 'suite' == $sort ) ?? 'selected'; ?>>Qtde. Suites</option>
      <option value="garagem" <?php echo ( 'garagem' == $sort ) ?? 'selected'; ?>>Qtde. Garagens</option>
      <option value="iptu" <?php echo ( 'iptu' == $sort ) ?? 'selected'; ?>>Valor do IPTU</option>
      <option value="condominio" <?php echo ( 'condominio' == $sort ) ?? 'selected'; ?>>Valor do Condomínio</option>
    </select>
  </form>
  <form name="order" action="#" class="direction">
    <label for="ordem">Direção</label>
    <select name="ordem" id="ordem">
      <option value="ASC" <?php echo ( 'ASC' == $ordem ) ?? 'selected'; ?>>Crescente</option>
      <option value="DESC" <?php echo ( 'DESC' == $ordem ) ?? 'selected'; ?>>Decrescente</option>
    </select>
  </form>
  <form name="show" action="#" class="show">
    <label for="max">Exibir</label>
    <select name="max" id="max">
      <option value="4" <?php echo ( 4 == $max ) ?? 'selected'; ?>>4</option>
      <option value="8" <?php echo ( 8 == $max ) ?? 'selected'; ?>>8</option>
      <option value="12" <?php echo ( 12 == $max ) ?? 'selected'; ?>>12</option>
      <option value="24" <?php echo ( 24 == $max ) ?? 'selected'; ?>>24</option>
      <option value="36" <?php echo ( 36 == $max ) ?? 'selected'; ?>>36</option>
      <option value="48" <?php echo ( 48 == $max ) ?? 'selected'; ?>>48</option>
    </select>
  </form>
</div>

