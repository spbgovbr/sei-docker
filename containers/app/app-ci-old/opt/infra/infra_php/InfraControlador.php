<?
  /*
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 31/07/2009 - criado por MGA
  */

  class InfraControlador {

    public static function processar($strAcao, $objInfraPagina, $objInfraSessao, $objInfraIBanco, $objInfraLog = null, $objInfraCache = null, $objInfraAuditoria = null){

      PaginaInfra::setObjInfraPagina($objInfraPagina);
      SessaoInfra::setObjInfraSessao($objInfraSessao);
      BancoInfra::setObjInfraIBanco($objInfraIBanco);
      LogInfra::setObjInfraLog($objInfraLog);
      CacheInfra::setObjInfraCache($objInfraCache);
      AuditoriaInfra::setObjInfraAuditoria($objInfraAuditoria);

      if ($objInfraAuditoria!=null) {
        BancoAuditoria::setObjInfraIBanco(AuditoriaInfra::getInstance()->getObjInfraIBancoAuditoria());
      }else{
        BancoAuditoria::setObjInfraIBanco(null);
      }

      switch($strAcao) {

        case 'infra_trocar_unidade':
          require_once dirname(__FILE__).'/formularios/infra_trocar_unidade.php';
          return true;

        case 'infra_configurar':
          require_once dirname(__FILE__).'/formularios/infra_configurar.php';
          return true;

        case 'infra_log_listar':
        case 'infra_log_excluir':
          require_once dirname(__FILE__).'/formularios/infra_log_lista.php';
          return true;

        case 'infra_parametro_cadastrar':
        case 'infra_parametro_alterar':
        case 'infra_parametro_consultar':
          require_once dirname(__FILE__).'/formularios/infra_parametro_cadastro.php';
          return true;

        case 'infra_parametro_excluir':
        case 'infra_parametro_listar':
          require_once dirname(__FILE__).'/formularios/infra_parametro_lista.php';
          return true;


        case 'infra_sequencia_cadastrar':
        case 'infra_sequencia_alterar':
        case 'infra_sequencia_consultar':
          require_once dirname(__FILE__).'/formularios/infra_sequencia_cadastro.php';
          return true;

        case 'infra_sequencia_excluir':
        case 'infra_sequencia_listar':
          require_once dirname(__FILE__).'/formularios/infra_sequencia_lista.php';
          return true;

        case 'infra_atributo_sessao_cadastrar':
        case 'infra_atributo_sessao_alterar':
          require_once dirname(__FILE__).'/formularios/infra_atributo_sessao_cadastro.php';
          return true;

        case 'infra_atributo_sessao_excluir':
        case 'infra_atributo_sessao_listar':
          require_once dirname(__FILE__).'/formularios/infra_atributo_sessao_lista.php';
          return true;

        case 'infra_erro_fatal_logar':
          require_once dirname(__FILE__).'/formularios/infra_erro_fatal.php';
          return true;

        case 'infra_gerar_planilha_tabela':
          require_once dirname(__FILE__).'/formularios/infra_gerar_planilha_tabela.php';
          return true;

        case 'infra_navegador_listar':
        case 'infra_navegador_excluir':
          require_once dirname(__FILE__).'/formularios/infra_navegador_lista.php';
          return true;

        case 'infra_auditoria_listar':
          require_once dirname(__FILE__).'/formularios/infra_auditoria_lista.php';
          return true;

        case 'infra_auditoria_recurso_selecionar':
          require_once dirname(__FILE__).'/formularios/infra_auditoria_recurso_selecao.php';
          return true;

        case 'infra_agendamento_tarefa_cadastrar':
        case 'infra_agendamento_tarefa_alterar':
        case 'infra_agendamento_tarefa_consultar':
          require_once dirname(__FILE__).'/formularios/infra_agendamento_tarefa_cadastro.php';
          return true;

        case 'infra_agendamento_tarefa_excluir':
        case 'infra_agendamento_tarefa_listar':
        case 'infra_agendamento_tarefa_desativar':
        case 'infra_agendamento_tarefa_reativar':
        case 'infra_agendamento_tarefa_executar':
          require_once dirname(__FILE__).'/formularios/infra_agendamento_tarefa_lista.php';
          return true;

        case 'infra_atributo_cache_excluir':
        case 'infra_atributo_cache_listar':
          require_once dirname(__FILE__).'/formularios/infra_atributo_cache_lista.php';
          return true;

        case 'infra_atributo_cache_consultar':
          require_once dirname(__FILE__).'/formularios/infra_atributo_cache_cadastro.php';
          return true;

        case 'infra_acesso_usuario_listar':
          require_once dirname(__FILE__).'/formularios/infra_acesso_usuario_lista.php';
          return true;

        case 'infra_banco_comparar':
          require_once dirname(__FILE__).'/formularios/infra_banco_comparacao.php';
          return true;

        case 'infra_sessao_rest_consultar':
          require_once dirname(__FILE__).'/formularios/infra_sessao_rest_cadastro.php';
          return true;

        case 'infra_sessao_rest_listar':
          require_once dirname(__FILE__).'/formularios/infra_sessao_rest_lista.php';
          return true;
      }

      return false;
    }

  }
?>