<?php
/**
 * Ponto de entrada (Entrypoint) para as requisições AJAX do tema.
 * * Este arquivo atua como um Orquestrador. Para garantir fácil manutenção
 * e seguir os princípios de Responsabilidade Única (SRP), as funcionalidades
 * foram divididas em classes específicas que são importadas aqui.
 * * phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
 */

// 1. Carrega a classe responsável pelo formulário de Cadastro e Contato de Imóveis
require_once __DIR__ . '/Pinedu_Form_Cadastre.php';

// 2. Carrega a classe responsável pelo formulário Dinâmico de Pesquisas e Filtros
require_once __DIR__ . '/Pinedu_Form_Pesquisa.php';

// 3. Inicializa os Listeners (Gatilhos AJAX do WordPress)
Pinedu_Form_Pesquisa::init();
Pinedu_Form_Cadastre::init();
