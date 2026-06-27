<?php
/**
 * CadastreTipoImovel
 */
// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
/**
 * CadastreTipoImovel
 */
class CadastreTipoImovel {
  public static function campos_caracteristicas( $dep ) {
    if ( ! is_object( $dep ) || ! isset( $dep->tipoCampo, $dep->sigla, $dep->nome ) ) {
      error_log( 'Objeto $dep inválido fornecido para air_light_generate_form_field.' );
      return;
    }

    $nome = $dep->nome;
    $sigla = $dep->sigla;
    $tipo_campo = $dep->tipoCampo;

    switch ( $tipo_campo ) {
      // phpcs:disable PSR2.ControlStructures.SwitchDeclaration.BreakIndent
      case 'TEXTO':
        ?>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" id="<?= esc_attr( $sigla ) ?>" name="<?= esc_attr( $sigla ) ?>" placeholder="<?= esc_attr( $nome ) ?>">
          </div>
        </div>
        <?php
        break;
      case 'INTEIRO':
        ?>
        <div class="row">
          <div class="col col-inteiro">
            <input type="number" step="1" class="form-control" id="<?= esc_attr( $sigla ) ?>" name="<?= esc_attr( $sigla ) ?>" placeholder="<?= esc_attr( $nome ) ?>">
          </div>
        </div>
        <?php
        break;
      case 'INTEIRO_TEXTO':
        ?>
        <div class="row">
          <div class="col col-inteiro">
            <input type="number" step="1" class="form-control" id="<?= esc_attr( $sigla ) ?>" name="<?= esc_attr( $sigla ) ?>" placeholder="<?= esc_attr( $nome ) ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" id="<?= esc_attr( $sigla ) ?>_desc" name="<?= esc_attr( $sigla ) ?>_desc" placeholder="<?= esc_attr( $nome ) ?> Descrição">
          </div>
        </div>
        <?php
        break;
      case 'FLOAT':
        ?>
        <div class="row">
          <div class="col col-float">
            <input type="number" step="0.5" class="form-control" id="<?= esc_attr( $sigla ) ?>" name="<?= esc_attr( $sigla ) ?>" placeholder="<?= esc_attr( $nome ) ?>">
          </div>
        </div>
        <?php
        break;
      case 'FLOAT_TEXTO':
        ?>
        <div class="row">
          <div class="col col-float">
            <input type="number" step="0.5" class="form-control" id="<?= esc_attr( $sigla ) ?>" name="<?= esc_attr( $sigla ) ?>" placeholder="<?= esc_attr( $nome ) ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" id="<?= esc_attr( $sigla ) ?>_desc" name="<?= esc_attr( $sigla ) ?>_desc" placeholder="<?= esc_attr( $nome ) ?> Descrição">
          </div>
        </div>
        <?php
        break;
      case 'BOOLEAN':
        ?>
        <div class="row">
          <div class="col col-checkbox">
            <input type="checkbox" class="form-check-input" id="<?= esc_attr( $sigla ) ?>" name="<?= esc_attr( $sigla ) ?>" placeholder="<?= esc_attr( $nome ) ?>" value="1">
          </div>
          <div class="col col-label">
            <label class="form-check-label" for="<?= esc_attr( $sigla ) ?>"><?= esc_attr( $nome ) ?></label>
          </div>
        </div>
        <?php
        break;
      case 'BOOLEAN_TEXTO':
        ?>
        <div class="row">
          <div class="col col-checkbox">
            <input type="checkbox" class="form-check-input" id="<?= esc_attr( $sigla ) ?>" name="<?= esc_attr( $sigla ) ?>" placeholder="<?= esc_attr( $nome ) ?>">
          </div>
          <div class="col col-label">
            <label class="form-check-label" for="<?= esc_attr( $sigla ) ?>"><?= esc_attr( $nome ) ?></label>
          </div>
          <div class="col col-texto">
            <input type="text" class="form-control" id="<?= esc_attr( $sigla ) ?>_desc" name="<?= esc_attr( $sigla ) ?>_desc" placeholder="<?= esc_attr( $nome ) ?> Descrição">
          </div>
        </div>
        <?php
        break;
      default:
        break;
    }
  }
}
