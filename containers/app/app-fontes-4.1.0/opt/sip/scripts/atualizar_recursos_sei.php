<?

require_once dirname(__FILE__) . '/../web/Sip.php';

class VersaoSipRN extends InfraScriptVersao {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  public function versao_4_0_0($strVersaoAtual) {

  }

  public function versao_4_1_0($strVersaoAtual) {
    try {
      $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());
      $objInfraMetaBD->setBolValidarIdentificador(true);

      $numIdSistemaSei = ScriptSip::obterIdSistema('SEI');

      $numIdPerfilSeiBasico = ScriptSip::obterIdPerfil($numIdSistemaSei, 'Básico');
      $numIdPerfilSeiAdministrador = ScriptSip::obterIdPerfil($numIdSistemaSei, 'Administrador');
      $numIdPerfilSeiArquivamento = ScriptSip::obterIdPerfil($numIdSistemaSei, 'Arquivamento');
      $numIdPerfilSeiInformatica = ScriptSip::obterIdPerfil($numIdSistemaSei, 'Informática');

      $numIdMenuSei = ScriptSip::obterIdMenu($numIdSistemaSei, 'Principal');
      $numIdItemMenuSeiAdministracao = ScriptSip::obterIdItemMenu($numIdSistemaSei, $numIdMenuSei, 'Administração');
      $numIdItemMenuSeiInfra = ScriptSip::obterIdItemMenu($numIdSistemaSei, $numIdMenuSei, 'Infra');
      $numIdItemMenuSeiContatos = ScriptSip::obterIdItemMenu($numIdSistemaSei, $numIdMenuSei, 'Contatos', $numIdItemMenuSeiAdministracao);
      $numIdItemMenuSeiGrupos = ScriptSip::obterIdItemMenu($numIdSistemaSei, $numIdMenuSei, 'Grupos', null);
      $numIdItemMenuSeiGruposInstitucionais = ScriptSip::obterIdItemMenu($numIdSistemaSei, $numIdMenuSei, 'Grupos Institucionais', $numIdItemMenuSeiAdministracao);
      $numIdItemMenuSeiRelatorios = ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Relatórios');
      $numIdItemRecursoProcedimentoControlar = ScriptSip::obterIdRecurso($numIdSistemaSei, 'procedimento_controlar');

      BancoSip::getInstance()->executarSql('update item_menu set icone=\'estatisticas.svg\' where rotulo=\'Estatísticas\' and id_item_menu_pai is null and id_sistema=' . $numIdSistemaSei);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'procedimento_linha_direta');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'arvore_navegar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'arvore_processar_html');

      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiInformatica, 'infra_captcha_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiInformatica, $numIdMenuSei, $numIdItemMenuSeiInfra, $objRecursoDTO->getNumIdRecurso(), 'Captcha', 0);

      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiInformatica, 'sistema_configurar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiInformatica, $numIdMenuSei, $numIdItemMenuSeiInfra, $objRecursoDTO->getNumIdRecurso(), 'Configuração do Sistema', 0);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'aviso_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'aviso_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'aviso_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'aviso_upload');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'aviso_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'aviso_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'aviso_mostrar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, $objRecursoDTO->getNumIdRecurso(), 'Avisos', 0);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_aviso_orgao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_aviso_orgao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_aviso_orgao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_aviso_orgao_listar');


      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'plano_trabalho_configurar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'plano_trabalho_alterar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'plano_trabalho_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'plano_trabalho_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'plano_trabalho_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'plano_trabalho_listar');
      $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, $objRecursoDTO->getNumIdRecurso(), 'Planos de Trabalho', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'plano_trabalho_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'plano_trabalho_desativar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'plano_trabalho_reativar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'plano_trabalho_clonar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'plano_trabalho_consultar_historico');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_serie_plano_trabalho_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_serie_plano_trabalho_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_serie_plano_trabalho_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_serie_plano_trabalho_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_serie_plano_trabalho_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'etapa_trabalho_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'etapa_trabalho_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'etapa_trabalho_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'etapa_trabalho_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'etapa_trabalho_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'etapa_trabalho_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'etapa_trabalho_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'etapa_trabalho_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'item_etapa_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'item_etapa_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'item_etapa_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'item_etapa_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'item_etapa_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'item_etapa_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'item_etapa_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'item_etapa_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_item_etapa_unidade_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_unidade_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_item_etapa_unidade_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_unidade_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_item_etapa_serie_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_serie_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_item_etapa_serie_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_serie_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'procedimento_plano_associar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'plano_trabalho_detalhar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'item_etapa_incluir_documento');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'item_etapa_atualizar_andamento');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'item_etapa_consultar_andamento');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_documento_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_documento_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_documento_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_documento_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_item_etapa_documento_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_plano_trabalho_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_plano_trabalho_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_plano_trabalho_lancar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_plano_trabalho_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atributo_andam_plano_trab_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atributo_andam_plano_trab_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atributo_andam_plano_trab_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atributo_andam_plano_trab_listar');


      ScriptSip::removerItemMenu($numIdSistemaSei, $numIdMenuSei, ScriptSip::obterIdItemMenu($numIdSistemaSei, $numIdMenuSei, 'Instalações Federação', $numIdItemMenuSeiAdministracao));
      $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Federação', 0);
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $objItemMenuDTO->getNumIdItemMenu(), ScriptSip::obterIdRecurso($numIdSistemaSei, 'instalacao_federacao_listar'), 'Instalações', 0);
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'relatorio_federacao_gerar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $objItemMenuDTO->getNumIdItemMenu(), $objRecursoDTO->getNumIdRecurso(), 'Processos', 0);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'tarefa_alterar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'reabertura_programada_gerenciar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'reabertura_programada_registrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'reabertura_programada_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'reabertura_programada_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'reabertura_programada_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'reabertura_programada_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'reabertura_programada_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, null, $objRecursoDTO->getNumIdRecurso(), 'Reabertura Programada', 0, 'reabertura_programada.svg');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'documento_geracao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'documento_geracao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'documento_geracao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'documento_geracao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'documento_geracao_listar');

      $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'CPAD', 0);
      $numIdItemMenuPai = $objItemMenuDTO->getNumIdItemMenu();
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_cadastrar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuPai, $objRecursoDTO->getNumIdRecurso(), 'Nova', 10);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_consultar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuPai, $objRecursoDTO->getNumIdRecurso(), 'Listar', 20);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_desativar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_reativar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuPai, $objRecursoDTO->getNumIdRecurso(), 'Reativar', 30);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_versao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_versao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_versao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_versao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_versao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_versao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_versao_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_composicao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_composicao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_composicao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_composicao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_composicao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_composicao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'cpad_composicao_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'avaliacao_documental_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'avaliacao_documental_listar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'avaliacao_documental_pesquisar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'avaliacao_documental_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'avaliacao_documental_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'avaliacao_documental_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'avaliacao_documental_selecionar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiArquivamento, $numIdMenuSei, null, $objRecursoDTO->getNumIdRecurso(), 'Avaliação Documental', 0);

      $numIdPerfilSeiCpad = ScriptSip::cadastrarPerfil($numIdSistemaSei, 'CPAD', 'Comissão Permanente de Avaliação de Documentos', 'S', 'N')->getNumIdPerfil();
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_avaliacao_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiCpad, $numIdMenuSei, null, $objRecursoDTO->getNumIdRecurso(), 'Avaliação CPAD', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiCpad, 'cpad_avaliacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiCpad, 'cpad_avaliacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'cpad_avaliacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'cpad_avaliacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiCpad, 'cpad_avaliacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'cpad_avaliacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiCpad, 'cpad_avaliacao_ativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiCpad, 'cpad_avaliacao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'cpad_avaliacao_desativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiCpad, 'avaliacao_documental_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiCpad, 'cpad_versao_alterar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'edital_eliminacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_gerar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_eliminar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_eliminados_gerar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'edital_eliminacao_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiArquivamento, $numIdMenuSei, null, $objRecursoDTO->getNumIdRecurso(), 'Editais de Eliminação', 0);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_conteudo_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'edital_eliminacao_conteudo_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_conteudo_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_conteudo_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'edital_eliminacao_arquivados_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'arquivamento_eliminacao_sinalizar');

      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'arquivamento_eliminacao_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiArquivamento, $numIdMenuSei, null, $objRecursoDTO->getNumIdRecurso(), 'Documentos para Eliminação', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'arquivamento_eliminar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_erro_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'edital_eliminacao_erro_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'edital_eliminacao_erro_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiArquivamento, 'edital_eliminacao_erro_excluir');

      $numIdItemMenuAtributosSessao = ScriptSip::obterIdItemMenu($numIdSistemaSei, $numIdMenuSei, 'Atributos de Sessão', $numIdItemMenuSeiInfra);
      ScriptSip::removerItemMenu($numIdSistemaSei, $numIdMenuSei, $numIdItemMenuAtributosSessao);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_orgao_pesquisa_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_orgao_pesquisa_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'rel_orgao_pesquisa_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_orgao_pesquisa_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_orgao_pesquisa_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atividade_unidade_detalhe');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atividade_unidade_pesquisar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiBasico,$numIdMenuSei,$numIdItemMenuSeiRelatorios,$objRecursoDTO->getNumIdRecurso(),'Atividade na Unidade', 0 );
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_selecionar_contato');

      $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Tipos de Prioridade', 0);
      $numIdItemMenuPai = $objItemMenuDTO->getNumIdItemMenu();
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'tipo_prioridade_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuPai, $objRecursoDTO->getNumIdRecurso(), 'Listar', 20);
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'tipo_prioridade_consultar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'tipo_prioridade_cadastrar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuPai, $objRecursoDTO->getNumIdRecurso(), 'Novo', 10);
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'tipo_prioridade_alterar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'tipo_prioridade_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'tipo_prioridade_desativar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'tipo_prioridade_reativar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuPai, $objRecursoDTO->getNumIdRecurso(), 'Reativar', 30);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'base_conhecimento_cancelar_liberacao');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_prioridade_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_prioridade_configurar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_prioridade_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_prioridade_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_prioridade_selecionar');

      ScriptSip::removerRecurso($numIdSistemaSei, 'velocidade_transferencia_alterar');
      ScriptSip::removerRecurso($numIdSistemaSei, 'velocidade_transferencia_cadastrar');
      ScriptSip::removerRecurso($numIdSistemaSei, 'velocidade_transferencia_consultar');
      ScriptSip::removerRecurso($numIdSistemaSei, 'velocidade_transferencia_excluir');
      ScriptSip::removerRecurso($numIdSistemaSei, 'velocidade_transferencia_listar');

      ScriptSip::adicionarAuditoria($numIdSistemaSei, 'Geral', array(
        'aviso_cadastrar',
        'aviso_alterar',
        'aviso_excluir',
        'grupo_bloco_cadastrar',
        'grupo_bloco_alterar',
        'grupo_bloco_excluir',
        'grupo_bloco_desativar',
        'grupo_bloco_reativar',
        'reabertura_programada_registrar',
        'reabertura_programada_excluir',
        'tipo_prioridade_cadastrar',
        'tipo_prioridade_alterar',
        'tipo_prioridade_excluir',
        'tipo_prioridade_desativar',
        'tipo_prioridade_reativar',
        'plano_trabalho_configurar',
        'plano_trabalho_alterar',
        'plano_trabalho_cadastrar',
        'plano_trabalho_desativar',
        'plano_trabalho_excluir',
        'plano_trabalho_reativar',
        'plano_trabalho_clonar',
        'etapa_trabalho_alterar',
        'etapa_trabalho_cadastrar',
        'etapa_trabalho_desativar',
        'etapa_trabalho_excluir',
        'etapa_trabalho_reativar',
        'item_etapa_alterar',
        'item_etapa_cadastrar',
        'item_etapa_desativar',
        'item_etapa_excluir',
        'item_etapa_reativar',
        'procedimento_plano_associar',
        'sistema_configurar'
      ));

      ScriptSip::adicionarAuditoria($numIdSistemaSei, 'Gestão Documental', array(
        'cpad_cadastrar',
        'cpad_alterar',
        'cpad_excluir',
        'cpad_desativar',
        'cpad_reativar',
        'cpad_composicao_cadastrar',
        'cpad_composicao_alterar',
        'cpad_composicao_excluir',
        'cpad_composicao_desativar',
        'cpad_composicao_reativar',
        'cpad_avaliacao_cadastrar',
        'cpad_avaliacao_alterar',
        'cpad_avaliacao_excluir',
        'cpad_avaliacao_ativar',
        'cpad_avaliacao_desativar',
        'edital_eliminacao_cadastrar',
        'edital_eliminacao_alterar',
        'edital_eliminacao_excluir',
        'edital_eliminacao_gerar',
        'edital_eliminacao_eliminar',
        'edital_eliminacao_eliminados_gerar',
        'edital_eliminacao_conteudo_cadastrar',
        'edital_eliminacao_conteudo_excluir'
      ));

    } catch (Exception $e) {
      throw new InfraException('Erro atualizando versão.', $e);
    }
  }
}

try {
  session_start();

  SessaoSip::getInstance(false);

  $objInfraParametro = new InfraParametro(BancoSip::getInstance());

  if (!$objInfraParametro->isSetValor('SIP_VERSAO')) {
    die("\n\nVERSAO DO SIP NAO IDENTIFICADA (REQUER 3.1.*)\n");
  }

  $strVersaoBancoSip = $objInfraParametro->getValor('SIP_VERSAO');
  if (substr($strVersaoBancoSip,0,3)!='3.1'){
    die("\n\nVERSAO DO SIP INSTALADA " . $strVersaoBancoSip . " INCOMPATIVEL (REQUER 3.1.*)\n");
  }

  if (!$objInfraParametro->isSetValor('SEI_VERSAO')) {
    die("\n\nVERSAO DOS RECURSOS SEI NAO IDENTIFICADA (REQUER 4.0.*)\n");
  }

  $strVersaoBancoSei = $objInfraParametro->getValor('SEI_VERSAO');
  if (substr($strVersaoBancoSei,0,3)!='4.0'){
    die("\n\nVERSAO DOS RECURSOS SEI INSTALADA " . $strVersaoBancoSei . " INCOMPATIVEL (REQUER 4.0.*)\n");
  }

  $objVersaoSipRN = new VersaoSipRN();
  $objVersaoSipRN->setStrNome('SIP - RECURSOS SEI');
  $objVersaoSipRN->setStrVersaoAtual('4.1.0');
  $objVersaoSipRN->setStrParametroVersao('SEI_VERSAO');
  $objVersaoSipRN->setArrVersoes(array(
    '4.0.*' => 'versao_4_0_0',
    '4.1.*' => 'versao_4_1_0'
  ));
  $objVersaoSipRN->setStrVersaoInfra('2.0.11');
  $objVersaoSipRN->setBolMySql(true);
  $objVersaoSipRN->setBolOracle(true);
  $objVersaoSipRN->setBolSqlServer(true);
  $objVersaoSipRN->setBolPostgreSql(true);
  $objVersaoSipRN->setBolErroVersaoInexistente(false);

  $objVersaoSipRN->atualizarVersao();
} catch (Exception $e) {
  echo(InfraException::inspecionar($e));
  try {
    LogSip::getInstance()->gravar(InfraException::inspecionar($e));
  } catch (Exception $e) {
  }
  exit(1);
}
