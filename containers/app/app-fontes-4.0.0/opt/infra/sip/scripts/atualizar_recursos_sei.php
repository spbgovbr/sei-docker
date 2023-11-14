<?
require_once dirname(__FILE__).'/../web/Sip.php';

class VersaoSipRN extends InfraScriptVersao {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  public function versao_3_0_0($strVersaoAtual){

  }

  public function versao_3_1_0($strVersaoAtual){
    try{

      $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());
      $objInfraMetaBD->setBolValidarIdentificador(true);

      $numIdSistemaSei = ScriptSip::obterIdSistema('SEI');
      $numIdPerfilSeiBasico = ScriptSip::obterIdPerfil($numIdSistemaSei,'Básico');
      $numIdPerfilSeiAdministrador = ScriptSip::obterIdPerfil($numIdSistemaSei,'Administrador');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'usuario_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'usuario_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'serie_publicacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'unidade_publicacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'anexo_download');

      ScriptSip::adicionarAuditoria($numIdSistemaSei,'Geral',array(
          'procedimento_gerar_pdf',
          'procedimento_gerar_zip'));

    }catch(Exception $e){
      throw new InfraException('Erro atualizando versão.', $e);
    }
  }

  public function versao_4_0_0($strVersaoAtual){
    try{

      $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());
      $objInfraMetaBD->setBolValidarIdentificador(true);

      try {
        $numIdSistemaSei = ScriptSip::obterIdSistema('SEI');
      }catch(Exception $e){
        $numIdSistemaSei = ScriptSip::obterIdSistema('SEI-TST');
      }

      $numIdPerfilSeiBasico = ScriptSip::obterIdPerfil($numIdSistemaSei,'Básico');
      $numIdPerfilSeiAdministrador = ScriptSip::obterIdPerfil($numIdSistemaSei,'Administrador');
      $numIdPerfilSeiArquivamento = ScriptSip::obterIdPerfil($numIdSistemaSei,'Arquivamento');
      $numIdPerfilSeiInformatica = ScriptSip::obterIdPerfil($numIdSistemaSei,'Informática');

      $numIdMenuSei = ScriptSip::obterIdMenu($numIdSistemaSei,'Principal');
      $numIdItemMenuSeiAdministracao = ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Administração');
      $numIdItemMenuSeiInfra = ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Infra');
      $numIdItemMenuSeiContatos=ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Contatos',$numIdItemMenuSeiAdministracao);
      $numIdItemMenuSeiGrupos = ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Grupos',null);
      $numIdItemMenuSeiGruposInstitucionais=ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Grupos Institucionais',$numIdItemMenuSeiAdministracao);
      $numIdItemRecursoProcedimentoControlar = ScriptSip::obterIdRecurso($numIdSistemaSei,'procedimento_controlar');

      BancoSip::getInstance()->executarSql('update sistema set esquema_login=\''.InfraPaginaEsquema3::$ESQUEMA_AZUL_CELESTE.'\' where id_sistema='.$numIdSistemaSei);

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setStrLogo('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');
      $objSistemaDTO->setNumIdSistema($numIdSistemaSei);

      $objSistemaBD = new SistemaBD(BancoSip::getInstance());
      $objSistemaBD->alterar($objSistemaDTO);

      BancoSip::getInstance()->executarSql('update sistema set servicos_liberados=\'1,2,3,4,5,6\' where id_sistema='.$numIdSistemaSei);

      BancoSip::getInstance()->executarSql('update item_menu set sequencia=0 where id_item_menu_pai is null and id_sistema = '.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set sequencia=999 where id_item_menu_pai is null and rotulo=\'Infra\' and id_sistema = '.$numIdSistemaSei);

      BancoSip::getInstance()->executarSql('update recurso set caminho=\'controlador.php?acao=procedimento_controlar&reset=1\' where id_recurso='.$numIdItemRecursoProcedimentoControlar);

      $objItemMenuDTOBlocos = ScriptSip::adicionarItemMenu($numIdSistemaSei, null, $numIdMenuSei, null, null, 'Blocos', 0);
      $numIdItemMenuSeiBlocosAssinatura = ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Blocos de Assinatura');
      BancoSip::getInstance()->executarSql('update item_menu set rotulo=\'Assinatura\', id_item_menu_pai='.$objItemMenuDTOBlocos->getNumIdItemMenu().' where id_item_menu = '.$numIdItemMenuSeiBlocosAssinatura);
      $numIdItemMenuSeiBlocosInternos = ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Blocos Internos');
      BancoSip::getInstance()->executarSql('update item_menu set rotulo=\'Internos\', id_item_menu_pai='.$objItemMenuDTOBlocos->getNumIdItemMenu().' where id_item_menu = '.$numIdItemMenuSeiBlocosInternos);
      $numIdItemMenuSeiBlocosReuniao = ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Blocos de Reunião');
      BancoSip::getInstance()->executarSql('update item_menu set rotulo=\'Reunião\', id_item_menu_pai='.$objItemMenuDTOBlocos->getNumIdItemMenu().' where id_item_menu = '.$numIdItemMenuSeiBlocosReuniao);


      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acompanhamento_gerenciar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acompanhamento_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acompanhamento_alterar_grupo');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_marcador_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_marcador_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_marcador_remover');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'marcador_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'painel_controle_configurar');
      $objRecursoDTO =ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'painel_controle_visualizar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiBasico,$numIdMenuSei,null,$objRecursoDTO->getNumIdRecurso(),'Painel de Controle', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'procedimento_configurar_detalhe');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_marcador_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_marcador_configurar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_marcador_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_marcador_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_marcador_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_acomp_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_acomp_configurar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_acomp_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_acomp_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_acomp_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_usuario_unidade_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_usuario_unidade_configurar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_usuario_unidade_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_usuario_unidade_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_usuario_unidade_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_proced_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_proced_configurar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_proced_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_proced_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_tipo_proced_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'arquivamento_cancelar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'orgao_historico_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'orgao_historico_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'orgao_historico_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'unidade_historico_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'unidade_historico_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'unidade_historico_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_historico_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_historico_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_historico_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_historico_listar');

      $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuSeiContatos,null,'Títulos', 90);
      $numIdItemMenuPai=$objItemMenuDTO->getNumIdItemMenu();
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'titulo_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuPai,$objRecursoDTO->getNumIdRecurso(),'Listar', 2);
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'titulo_consultar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'titulo_cadastrar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuPai,$objRecursoDTO->getNumIdRecurso(),'Novo', 1);
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'titulo_alterar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'titulo_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'titulo_desativar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'titulo_reativar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuPai,$objRecursoDTO->getNumIdRecurso(),'Reativar', 3);
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'titulo_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'contato_gerar_relatorios');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'editor_simular');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'controle_prazo_definir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'controle_prazo_concluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'controle_prazo_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'controle_prazo_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'controle_prazo_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'controle_prazo_consultar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'controle_prazo_listar');

      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiBasico,$numIdMenuSei,null,$objRecursoDTO->getNumIdRecurso(),'Controle de Prazos', 0 );

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'comentario_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'comentario_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'comentario_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'comentario_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'comentario_excluir');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'infra_acesso_usuario_listar');

      $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuSeiContatos,null,'Categorias', 100);
      $numIdItemMenuPai=$objItemMenuDTO->getNumIdItemMenu();
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'categoria_consultar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'categoria_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuPai,$objRecursoDTO->getNumIdRecurso(),'Listar', 20);
      $objRecursoDTO =  ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'categoria_cadastrar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuPai,$objRecursoDTO->getNumIdRecurso(),'Nova', 10);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'categoria_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'categoria_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'categoria_desativar');
      $objRecursoDTO  = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'categoria_reativar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuPai,$objRecursoDTO->getNumIdRecurso(),'Reativar', 30);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'procedimento_credencial_renovar');


      $numIdItemMenuSeiModelosFavoritos=ScriptSip::obterIdItemMenu($numIdSistemaSei,$numIdMenuSei,'Modelos Favoritos');
      $objItemMenuDTO = new ItemMenuDTO();
      $objItemMenuDTO->setStrRotulo('Favoritos');
      $objItemMenuDTO->setNumIdMenu($numIdMenuSei);
      $objItemMenuDTO->setNumIdItemMenu($numIdItemMenuSeiModelosFavoritos);

      $objItemMenuRN = new ItemMenuRN();
      $objItemMenuRN->alterar($objItemMenuDTO);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'protocolo_modelo_gerenciar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'bloco_navegar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'bloco_priorizar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'bloco_revisar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'bloco_atribuir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'bloco_comentar');

      $objInfraMetaBD->processarIndicesChavesEstrangeiras();

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_acesso_ext_serie_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_acesso_ext_serie_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_acesso_ext_serie_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_acesso_ext_serie_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_acesso_ext_serie_detalhar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_bloco_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_bloco_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_bloco_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_bloco_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_bloco_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_bloco_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_bloco_reativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'bloco_alterar_grupo');


      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_bloco_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_bloco_configurar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_bloco_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_bloco_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_usuario_grupo_bloco_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'serie_selecionar_acesso_externo');


      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'servico_gerar_chave_acesso');


      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'instalacao_federacao_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuSeiAdministracao,$objRecursoDTO->getNumIdRecurso(),'Instalações Federação', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'instalacao_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'instalacao_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'instalacao_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'instalacao_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'instalacao_federacao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'instalacao_federacao_reativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'instalacao_federacao_liberar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'instalacao_federacao_bloquear');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'instalacao_federacao_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'instalacao_federacao_verificar_conexao');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'andamento_instalacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'andamento_instalacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_instalacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamento_instalacao_consultar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_gerenciar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_enviar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'acesso_federacao_cancelar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acesso_federacao_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'tarefa_instalacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'tarefa_instalacao_consultar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'atributo_instalacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atributo_instalacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'atributo_instalacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'atributo_instalacao_excluir');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'orgao_federacao_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'unidade_federacao_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_selecionar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'usuario_federacao_reativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'protocolo_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'protocolo_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'protocolo_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'protocolo_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'protocolo_federacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'protocolo_federacao_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acao_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acao_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acao_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acao_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acao_federacao_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'acao_federacao_desativar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'parametro_acao_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'parametro_acao_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'parametro_acao_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'parametro_acao_federacao_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'processo_consulta_federacao');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'documento_consulta_federacao');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'andamentos_consulta_federacao');

      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiBasico,$numIdMenuSei,$numIdItemMenuSeiGrupos,$objRecursoDTO->getNumIdRecurso(),'Federação', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_selecionar');

      $objItemMenuDTOGrupoInstitucionalFederacao = ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuSeiGruposInstitucionais,null,'Federação', 0);
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'grupo_federacao_institucional_cadastrar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$objItemMenuDTOGrupoInstitucionalFederacao->getNumIdItemMenu(),$objRecursoDTO->getNumIdRecurso(),'Novo', 10);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'grupo_federacao_institucional_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_institucional_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'grupo_federacao_institucional_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_institucional_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$objItemMenuDTOGrupoInstitucionalFederacao->getNumIdItemMenu(),$objRecursoDTO->getNumIdRecurso(),'Listar', 20);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'grupo_federacao_institucional_desativar');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'grupo_federacao_institucional_reativar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$objItemMenuDTOGrupoInstitucionalFederacao->getNumIdItemMenu(),$objRecursoDTO->getNumIdRecurso(),'Reativar', 30);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'grupo_federacao_institucional_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_grupo_fed_orgao_fed_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_grupo_fed_orgao_fed_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_grupo_fed_orgao_fed_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_grupo_fed_orgao_fed_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'sinalizacao_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'sinalizacao_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'sinalizacao_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'sinalizacao_federacao_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'sinalizacao_federacao_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_sinalizacao_fed_unidade_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_sinalizacao_fed_unidade_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_sinalizacao_fed_unidade_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_sinalizacao_fed_unidade_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'rel_sinalizacao_fed_unidade_listar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_agendar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_replicar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_excluir');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiInformatica,$numIdMenuSei,$numIdItemMenuSeiInfra,$objRecursoDTO->getNumIdRecurso(),'Replicações Federação', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'replicacao_federacao_reativar');

      ScriptSip::removerRecurso($numIdSistemaSei, 'rel_unidade_serie_unidade_listar');
      ScriptSip::removerRecurso($numIdSistemaSei, 'tarefa_alterar');

      ScriptSip::renomearRecurso($numIdSistemaSei, 'procedimento_acervo_sigilosos', 'procedimento_acervo_sigilosos_unidade');
      ScriptSip::removerRecurso($numIdSistemaSei, 'procedimento_relatorio_sigilosos');
      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'procedimento_acervo_sigilosos_global');
      ScriptSip::adicionarItemMenu($numIdSistemaSei,$numIdPerfilSeiAdministrador,$numIdMenuSei,$numIdItemMenuSeiAdministracao,$objRecursoDTO->getNumIdRecurso(),'Acervo Global de Sigilosos', 0);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'pesquisa_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'pesquisa_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'pesquisa_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'pesquisa_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'pesquisa_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'pesquisa_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'campo_pesquisa_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'campo_pesquisa_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'campo_pesquisa_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'campo_pesquisa_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'campo_pesquisa_listar');

      ScriptSip::removerRecurso($numIdSistemaSei, 'para_saber_mais');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'infra_trocar_unidade');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'aviso_mostrar');

      BancoSip::getInstance()->executarSql('update item_menu set icone=\'grupos.svg\' where rotulo=\'Grupos\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'relatorios.svg\' where rotulo=\'Relatórios\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'estatisticas.svg\' where rotulo=\'Estatisticas\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'administracao.svg\' where rotulo=\'Administração\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'blocos.svg\' where rotulo=\'Blocos\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'contatos.svg\' where rotulo=\'Contatos\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'iniciar_processo.svg\' where rotulo=\'Iniciar Processo\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'controle_processos.svg\' where rotulo=\'Controle de Processos\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'pesquisa.svg\' where rotulo=\'Pesquisa\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'processos_sobrestados.svg\' where rotulo=\'Processos Sobrestados\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'retorno_programado.svg\' where rotulo=\'Retorno Programado\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'base_conhecimento.svg\' where rotulo=\'Base de Conhecimento\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'acompanhamento_especial.svg\' where rotulo=\'Acompanhamento Especial\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'texto_padrao.svg\' where rotulo=\'Textos Padrão\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'inspecao_administrativa.svg\' where rotulo=\'Inspeção Administrativa\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'favoritos.svg\' where rotulo=\'Favoritos\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'pontos_controle.svg\' where rotulo=\'Pontos de Controle\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'marcadores.svg\' where rotulo=\'Marcadores\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'painel_controle.svg\' where rotulo=\'Painel de Controle\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'controle_prazo.svg\' where rotulo=\'Controle de Prazos\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'arquivamento.svg\' where rotulo=\'Arquivamento\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'desarquivamento.svg\' where rotulo=\'Desarquivamento\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);
      BancoSip::getInstance()->executarSql('update item_menu set icone=\'infra.svg\' where rotulo=\'Infra\' and id_item_menu_pai is null and id_sistema='.$numIdSistemaSei);

      ScriptSip::adicionarAuditoria($numIdSistemaSei,'Geral',array(
        'orgao_historico_alterar',
        'orgao_historico_cadastrar',
        'orgao_historico_excluir',
        'unidade_historico_alterar',
        'unidade_historico_cadastrar',
        'unidade_historico_excluir',
        'infra_auditoria_listar',
        'arquivamento_cancelar',
        'comentario_cadastrar',
        'comentario_alterar',
        'comentario_excluir',
        'controle_prazo_cadastrar',
        'controle_prazo_alterar',
        'controle_prazo_excluir',
        'usuario_externo_alterar',
        'usuario_externo_excluir',
        'processo_consulta_federacao',
        'documento_consulta_federacao',
        'instalacao_federacao_cadastrar',
        'instalacao_federacao_alterar',
        'instalacao_federacao_excluir',
        'instalacao_federacao_desativar',
        'instalacao_federacao_reativar',
        'instalacao_federacao_liberar',
        'instalacao_federacao_bloquear',
        'acesso_federacao_enviar',
        'acesso_federacao_cancelar',
        'grupo_federacao_cadastrar',
        'grupo_federacao_alterar',
        'grupo_federacao_excluir',
        'grupo_federacao_institucional_cadastrar',
        'grupo_federacao_institucional_alterar',
        'grupo_federacao_institucional_excluir',
        'grupo_federacao_institucional_desativar',
        'grupo_federacao_institucional_reativar',
        'procedimento_acervo_sigilosos_unidade',
        'procedimento_acervo_sigilosos_global'));

    }catch(Exception $e){
      throw new InfraException('Erro atualizando versão.', $e);
    }
  }
}
try{

  session_start();

  SessaoSip::getInstance(false);

  BancoSip::getInstance()->setBolScript(true);

  $objInfraParametro = new InfraParametro(BancoSip::getInstance());
  if (!$objInfraParametro->isSetValor('SEI_VERSAO')) {
    $strVersaoBancoSip = $objInfraParametro->getValor('SIP_VERSAO');
    if (InfraUtil::compararVersoes($strVersaoBancoSip, '<', '2.0.0')) {
      die("\n\nSCRIPT INCOMPATIVEL COM A VERSAO DO BANCO DE DADOS DO SIP (".$strVersaoBancoSip.")\n");
    }else if (InfraUtil::compararVersoes($strVersaoBancoSip, '<', '2.1.0')) {
      $objInfraParametro->setValor('SEI_VERSAO', '3.0.0');
    } else if (InfraUtil::compararVersoes($strVersaoBancoSip, '<', '3.0.0')) {
      $objInfraParametro->setValor('SEI_VERSAO', '3.1.0');
    }
  }

  $objVersaoSipRN = new VersaoSipRN();
  $objVersaoSipRN->setStrNome('RECURSOS SEI');
  $objVersaoSipRN->setStrVersaoAtual('4.0.0');
  $objVersaoSipRN->setStrParametroVersao('SEI_VERSAO');
  $objVersaoSipRN->setArrVersoes(array('3.0.*' => 'versao_3_0_0',
                                       '3.1.*' => 'versao_3_1_0',
                                       '4.0.*' => 'versao_4_0_0'
  ));
  $objVersaoSipRN->setStrVersaoInfra('1.583.4');
  $objVersaoSipRN->setBolMySql(true);
  $objVersaoSipRN->setBolOracle(true);
  $objVersaoSipRN->setBolSqlServer(true);
  $objVersaoSipRN->setBolPostgreSql(true);
  $objVersaoSipRN->setBolErroVersaoInexistente(false);

  $objVersaoSipRN->atualizarVersao();

}catch(Exception $e){
  echo(InfraException::inspecionar($e));
  try{LogSip::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
  exit(1);
}
