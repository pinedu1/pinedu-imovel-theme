<?php
/**
 * Template para o corpo do e-mail do Trabalhe Conosco.
 * Recebe os dados através da variável nativa $args via get_template_part().
 */
// Evita acesso direto
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Novo Currículo Recebido</title>
  </head>
  <body style="background-color: #f4f6f9; font-family: Helvetica, Arial, sans-serif; margin: 0; padding: 0; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f6f9; padding: 40px 20px;">
      <tr>
        <td align="center">
          <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <tr>
              <td style="background-color: #1a365d; padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold;">Novo Candidato</h1>
                <p style="color: #a0aec0; margin: 8px 0 0 0; font-size: 14px;">Originado da página Trabalhe Conosco</p>
              </td>
            </tr>
            <tr>
              <td style="padding: 40px 30px;">
                <h2 style="color: #2d3748; font-size: 18px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; margin-top: 0; margin-bottom: 20px;">Dados Pessoais</h2>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td width="30%" style="padding: 8px 0; color: #718096; font-size: 14px; font-weight: bold;">Nome:</td>
                    <td width="70%" style="padding: 8px 0; color: #2d3748; font-size: 15px;"><?php echo esc_html( $args['nome'] ?? '' ); ?></td>
                  </tr>
                  <tr>
                    <td style="padding: 8px 0; color: #718096; font-size: 14px; font-weight: bold;">E-mail:</td>
                    <td style="padding: 8px 0; font-size: 15px;">
                      <a href="mailto:<?php echo esc_attr( $args['email'] ?? '' ); ?>" style="color: #3182ce; text-decoration: none;"><?php echo esc_html( $args['email'] ?? '' ); ?></a>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding: 8px 0; color: #718096; font-size: 14px; font-weight: bold;">Celular/WhatsApp:</td>
                    <td style="padding: 8px 0; color: #2d3748; font-size: 15px;"><?php echo esc_html( $args['celular'] ?? '' ); ?></td>
                  </tr>
                  <tr>
                    <td style="padding: 8px 0; color: #718096; font-size: 14px; font-weight: bold;">Cidade:</td>
                    <td style="padding: 8px 0; color: #2d3748; font-size: 15px;"><?php echo esc_html( $args['cidade'] ?? '' ); ?></td>
                  </tr>
                </table>
                <h2 style="color: #2d3748; font-size: 18px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;">Interesse Profissional</h2>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td width="30%" style="padding: 8px 0; color: #718096; font-size: 14px; font-weight: bold;">Cargo Desejado:</td>
                    <td width="70%" style="padding: 8px 0; color: #2d3748; font-size: 15px;"><strong><?php echo esc_html( $args['cargo'] ?? '' ); ?></strong></td>
                  </tr>
                  <?php if ( ! empty( $args['creci'] ) ) : ?>
                    <tr>
                      <td style="padding: 8px 0; color: #718096; font-size: 14px; font-weight: bold;">Situação CRECI:</td>
                      <td style="padding: 8px 0; color: #2d3748; font-size: 15px;"><?php echo esc_html( $args['creci'] ); ?></td>
                    </tr>
                  <?php endif; ?>
                </table>
                <?php if ( ! empty( $args['mensagem'] ) ) : ?>
                  <h2 style="color: #2d3748; font-size: 18px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;">Apresentação do Candidato</h2>
                  <div style="background-color: #f7fafc; padding: 20px; border-left: 4px solid #3182ce; color: #4a5568; font-size: 15px; line-height: 1.6; border-radius: 0 4px 4px 0;">
                    <?php echo nl2br( esc_html( $args['mensagem'] ) ); ?>
                  </div>
                <?php endif; ?>
                <div style="margin-top: 30px; padding: 15px; background-color: #ebf8ff; border: 1px solid #bee3f8; border-radius: 4px; text-align: center; color: #2b6cb0;">
                  <strong style="font-size: 15px;">📎 O currículo deste candidato foi anexado a este e-mail.</strong>
                </div>
              </td>
            </tr>
            <tr>
              <td style="background-color: #edf2f7; padding: 20px; text-align: center;">
                <p style="color: #a0aec0; font-size: 12px; margin: 0;">
                  Este é um e-mail automático gerado pelo sistema.<br>
                  Data do registro: <?php echo date( 'd/m/Y \à\s H:i:s' ); ?>
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
