<?php
class CadastreTipoImovel {
  public static function campos_caracteristicas( $dep ) {
    if ( ! is_object( $dep ) || ! isset( $dep->tipoCampo, $dep->sigla, $dep->nome ) ) {
      error_log( 'Objeto $dep inválido fornecido para air_light_generate_form_field.' );
      return;
    }
    $sigla = esc_attr( $dep->sigla );
    $nome = esc_attr( $dep->nome );
    $tipo_campo = esc_attr( $dep->tipoCampo );
    switch ( $tipo_campo ) {
      case 'TEXTO': ?>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" id="<?php echo $sigla; ?>" name="<?php echo $sigla; ?>" placeholder="<?php echo $nome; ?>">
          </div>
        </div>
        <?php
        break;
      case 'INTEIRO': ?>
        <div class="row">
          <div class="col col-inteiro">
            <input type="number" step="1" class="form-control" id="<?php echo $sigla; ?>" name="<?php echo $sigla; ?>" placeholder="<?php echo $nome; ?>">
          </div>
        </div>
        <?php
        break;
      case 'INTEIRO_TEXTO': ?>
        <div class="row">
          <div class="col col-inteiro">
            <input type="number" step="1" class="form-control" id="<?php echo $sigla; ?>" name="<?php echo $sigla; ?>" placeholder="<?php echo $nome; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" id="<?php echo $sigla; ?>_desc" name="<?php echo $sigla; ?>_desc" placeholder="<?php echo $nome; ?> Descrição">
          </div>
        </div>
        <?php
        break;
      case 'FLOAT': ?>
        <div class="row">
          <div class="col col-float">
            <input type="number" step="0.5" class="form-control" id="<?php echo $sigla; ?>" name="<?php echo $sigla; ?>" placeholder="<?php echo $nome; ?>">
          </div>
        </div>
        <?php
        break;
      case 'FLOAT_TEXTO': ?>
        <div class="row">
          <div class="col col-float">
            <input type="number" step="0.5" class="form-control" id="<?php echo $sigla; ?>" name="<?php echo $sigla; ?>" placeholder="<?php echo $nome; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" id="<?php echo $sigla; ?>_desc" name="<?php echo $sigla; ?>_desc" placeholder="<?php echo $nome; ?> Descrição">
          </div>
        </div>
        <?php
        break;
      case 'BOOLEAN': ?>
        <div class="row">
          <div class="col col-checkbox">
            <input type="checkbox" class="form-check-input" id="<?php echo $sigla; ?>" name="<?php echo $sigla; ?>" placeholder="<?php echo $nome; ?>">
          </div>
          <div class="col col-label">
            <label class="form-check-label" for="<?php echo $sigla; ?>"><?php echo $nome; ?></label>
          </div>
        </div>
        <?php
        break;
      case 'BOOLEAN_TEXTO': ?>
        <div class="row">
          <div class="col col-checkbox">
            <input type="checkbox" class="form-check-input" id="<?php echo $sigla; ?>" name="<?php echo $sigla; ?>" placeholder="<?php echo $nome; ?>">
          </div>
          <div class="col col-label">
            <label class="form-check-label" for="<?php echo $sigla; ?>"><?php echo $nome; ?></label>
          </div>
          <div class="col col-texto">
            <input type="text" class="form-control" id="<?php echo $sigla; ?>_desc" name="<?php echo $sigla; ?>_desc" placeholder="<?php echo $nome; ?> Descrição">
          </div>
        </div>
        <?php
        break;
      default:
        break;
    }
  }
}
