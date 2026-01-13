$=jQuery;
function validarFormulario( componentSelector ) {
  const form = $( componentSelector );
  const inputs = form.find( 'input[required]' );
  const selects = form.find( 'select[required]' );
  let valido = true;
  // Limpar mensagens de erro anteriores
  $( '.error-message' ).remove( );
  // Validar cada campo requerido
  inputs.each( function ( ) {
    const input = $( this );
    if ( !input.val( ) && !input.prop( 'disabled' ) ) {
      valido = false;
      // Adicionar uma mensagem de erro se o campo não estiver preenchido
      const errorMessage = $( '<div class="error-message" style="color: red;">' );
      errorMessage.text( `O campo ${input.attr( 'placeholder' )} é obrigatório.` );
      input.parent( ).append( errorMessage );
      setTimeout( ( ) => {
        errorMessage.remove( );
      }, 10000 );
    }
  } );
  // Validar cada campo requerido
  selects.each( ( ) => {
    const select = $( this );
    if ( !select.val( ) ) {
      valido = false;
      // Adicionar uma mensagem de erro se o campo não estiver preenchido
      const errorMessage = $( '<div class="error-message" style="color: red;">' );
      errorMessage.text( `O campo ${select.attr( 'placeholder' )} é obrigatório.` );
      select.parent( ).append( errorMessage );
      setTimeout( ( ) => {
        errorMessage.remove( );
      }, 10000 );
    }
  } );
  return valido;
}
function ajaxContatoCorretor( ) {
  const form = $( 'form[name=contato-corretor-form]' );
  const dadosFormulario = form.serialize( );
  console.log( dadosFormulario );
  $.ajax( {
    url: ajax_object.ajaxurl,
    method: 'POST',
    data: { action: 'CONTATOCORRETOR', form_data: dadosFormulario },
    beforeSend( jqXHR, settings ) {
      /*$( '#loading-curtain' ).fadeIn( );*/
    },
    success( response ) {
      if ( response.success ) {
        $( 'main.contato-corretor' ).html( response.data );
      } else {
        console.error( 'Erro na requisição AJAX: ', response );
      }
    },
    error( ) {
      console.error( 'Erro na requisição desconhecido AJAX' );
    },
    complete( jqXHR, textStatus ) {
      /*$( '#loading-curtain' ).fadeOut( );*/
    },
  } );
}
function validarFormularioContatoCorretor( ) {
  return validarFormulario( 'form[name=contato-corretor-form]' );
}
function contatoCorretor(button ) {
  const ok = validarFormularioContatoCorretor( );
  if ( ok === true ) {
    ajaxContatoCorretor( );
  }
  /*
    console.log( button );
    const form = $( button ).closest( 'form[name=contato-corretor-form]' );
    console.log( form.serialize() );
    alert( button );
  */
  return false;
}
window.contatoCorretor = contatoCorretor;
jQuery( document ).ready( ( $ ) => {
  $( 'button.cartao.btn-contato' ).on( 'click', function( ){
    const codigoCorretor = $( this ).data( 'codigo-corretor' );
    const nomeCorretor = $( this ).data( 'nome-corretor' );
    const referencia = $( this ).data( 'referencia' );
    $.ajax( {
      url: ajax_object.ajaxurl,
      method: 'POST',
      data: {
        action: 'CONTATOIMOVELCORRETOR'
        , codigoCorretor: codigoCorretor
        , nomeCorretor: nomeCorretor
        , referencia: referencia
      },
      beforeSend( jqXHR, settings ) {
        $( '#cortina-imovel-full' ).css( 'display', 'none' );
      },
      success( response ) {
        if ( response.success ) {
          /*console.log(response.data);*/
          $( '#cortina-imovel-full' ).html( response.data ).css( 'display', 'block' );
          $( 'section.contato-corretor' ).css( 'display', 'flex' ).hide().fadeIn( 400 );
        } else {
          console.error( 'Erro na requisição AJAX: ', response );
        }
      },
      error( ) {
        console.error( 'Erro na requisição desconhecido AJAX' );
      },
      complete( jqXHR, textStatus ) {
        //$( '#cortina-imovel-full' ).fadeOut( );
      },
    } );
  } );
} );
