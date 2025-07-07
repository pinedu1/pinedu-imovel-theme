import './utilitarios';
import './front-end';
import './form-pesquisa';
import './form-consulta';
import './promocao';
import './pesquisa';
import './privacy-compliance';
import './imovel';
import './cadastre-imovel';

if (typeof wp !== 'undefined' && document.body.classList.contains('block-editor-page')) {
  import('./gutenberg-editor')
    .catch((err) => console.error('Erro ao carregar gutenberg-editor:', err));
}
