<?

try {
  require_once dirname(__FILE__) . '/../web/SEI.php';

  class VersaoSeiRN extends InfraScriptVersao {

    public function __construct() {
      parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
      return BancoSEI::getInstance();
    }

    public function versao_4_0_0($strVersaoAtual) {

    }

    public function versao_4_1_0($strVersaoAtual) {
      try {
        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraMetaBD->setBolValidarIdentificador(true);

        if (BancoSEI::getInstance() instanceof InfraMySql) {
          $objScriptRN = new ScriptRN();
          $objScriptRN->atualizarSequencias();
        }

        InfraDebug::getInstance()->setBolDebugInfra(true);

        if (BancoSEI::getInstance() instanceof InfraMySql) {
          $objInfraMetaBD->excluirIndice('retorno_programado', 'i04_retorno_programado');
        }

        if (BancoSEI::getInstance() instanceof InfraSqlServer){
          $objInfraMetaBD->criarIndice('infra_regra_auditoria_recurso', 'fk_inf_reg_aud_rec_inf_reg_aud', array('id_infra_regra_auditoria'));
        }

        if (BancoSEI::getInstance() instanceof InfraOracle) {
          BancoSEI::getInstance()->executarSql('update orgao_historico set dta_inicio=trunc(dta_inicio), dta_fim=trunc(dta_fim)');
          BancoSEI::getInstance()->executarSql('update unidade_historico set dta_inicio=trunc(dta_inicio), dta_fim=trunc(dta_fim)');
        }

        $this->logar('ATUALIZANDO PARAMETROS...');

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_TIPO_CAPTCHA\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_TIPO_CAPTCHA\',\'5\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_NUM_MAX_PROTOCOLOS_BLOCO\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_NUM_MAX_PROTOCOLOS_BLOCO\',\'1000\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_FEDERACAO_NOME_TIPO_PROCESSO\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_FEDERACAO_NOME_TIPO_PROCESSO\',\'0\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_WS_PLANO_TRABALHO_INCLUSAO_DOCUMENTO\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_WS_PLANO_TRABALHO_INCLUSAO_DOCUMENTO\',\'0\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_DATA_CORTE_SINALIZADOR_PARA_ARQUIVAMENTO\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_DATA_CORTE_SINALIZADOR_PARA_ARQUIVAMENTO\',\'\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_NUM_FATOR_DOWNLOAD_AUTOMATICO\'');
        if ($rs[0]['total'] == 1) {
          BancoSEI::getInstance()->executarSql('delete from infra_parametro where nome = \'SEI_NUM_FATOR_DOWNLOAD_AUTOMATICO\'');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from procedimento where id_tipo_procedimento is null');
        if ($rs[0]['total'] > 0) {
          $numIdTipoProcedimentoSemIdentificacao = $this->cadastrarTipoProcessoNaoIdentificado();
          BancoSEI::getInstance()->executarSql('update procedimento set id_tipo_procedimento='.$numIdTipoProcedimentoSemIdentificacao.' where id_tipo_procedimento is null');
        }

        if (BancoSEI::getInstance() instanceof InfraSqlServer) {
          $objInfraMetaBD->excluirIndice('procedimento','fk_procedimento_tipo_procedime');
        }

        BancoSEI::getInstance()->executarSql('drop table velocidade_transferencia');

        $objInfraMetaBD->alterarColuna('procedimento', 'id_tipo_procedimento', $objInfraMetaBD->tipoNumero(), 'not null');

        BancoSEI::getInstance()->executarSql('CREATE TABLE plano_trabalho
        (
          id_plano_trabalho    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          nome                 ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
          descricao            ' . $objInfraMetaBD->tipoTextoVariavel(4000) . '  NULL ,
          sin_ativo            ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL 
        )');

        $objInfraMetaBD->adicionarChavePrimaria('plano_trabalho', 'pk_plano_trabalho', array('id_plano_trabalho'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_plano_trabalho', 1);

        BancoSEI::getInstance()->executarSql('CREATE TABLE etapa_trabalho
        (
          id_etapa_trabalho     ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_plano_trabalho     ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          nome                 ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
          descricao            ' . $objInfraMetaBD->tipoTextoVariavel(4000) . '  NULL ,
          ordem                 ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          sin_ativo             ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL 
        )');

        $objInfraMetaBD->adicionarChavePrimaria('etapa_trabalho', 'pk_etapa_trabalho', array('id_etapa_trabalho'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_etapa_trabalho', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_etapa_trab_plano_trab', 'etapa_trabalho', array('id_plano_trabalho'), 'plano_trabalho', array('id_plano_trabalho'));

        BancoSEI::getInstance()->executarSql('CREATE TABLE item_etapa 
        (
          id_item_etapa        ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_etapa_trabalho    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          nome                 ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
          descricao            ' . $objInfraMetaBD->tipoTextoVariavel(4000) . '  NULL ,
          ordem                ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          sin_ativo            ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL 
          )');

        $objInfraMetaBD->adicionarChavePrimaria('item_etapa', 'pk_item_etapa', array('id_item_etapa'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_item_etapa', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_item_etapa_etapa_trabalho', 'item_etapa', array('id_etapa_trabalho'), 'etapa_trabalho', array('id_etapa_trabalho'));

        BancoSEI::getInstance()->executarSql('CREATE TABLE rel_item_etapa_unidade (
          id_unidade           ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_item_etapa        ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL
        )');

        $objInfraMetaBD->adicionarChavePrimaria('rel_item_etapa_unidade', 'pk_rel_item_etapa_unidade', array('id_unidade', 'id_item_etapa'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_item_etap_uni_unidade', 'rel_item_etapa_unidade', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_item_etap_uni_item_etap', 'rel_item_etapa_unidade', array('id_item_etapa'), 'item_etapa', array('id_item_etapa'));

        BancoSEI::getInstance()->executarSql('CREATE TABLE rel_item_etapa_serie (
          id_item_etapa        ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_serie             ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL
        )');

        $objInfraMetaBD->adicionarChavePrimaria('rel_item_etapa_serie', 'pk_rel_item_etapa_serie', array('id_serie', 'id_item_etapa'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_item_etap_ser_serie', 'rel_item_etapa_serie', array('id_serie'), 'serie', array('id_serie'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_item_etap_ser_item_etap', 'rel_item_etapa_serie', array('id_item_etapa'), 'item_etapa', array('id_item_etapa'));


        BancoSEI::getInstance()->executarSql('CREATE TABLE rel_item_etapa_documento (
        id_documento         ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL ,
        id_item_etapa        ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL
      )');

        $objInfraMetaBD->adicionarChavePrimaria('rel_item_etapa_documento', 'pk_rel_item_etapa_documento', array('id_documento', 'id_item_etapa'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_item_etap_doc_documento', 'rel_item_etapa_documento', array('id_documento'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_item_etap_doc_item_etap', 'rel_item_etapa_documento', array('id_item_etapa'), 'item_etapa', array('id_item_etapa'));

        BancoSEI::getInstance()->executarSql('CREATE TABLE tarefa_plano_trabalho (
          id_tarefa_plano_trabalho ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
          nome                 ' . $objInfraMetaBD->tipoTextoVariavel(250) . '  NOT NULL
        )');

        $objInfraMetaBD->adicionarChavePrimaria('tarefa_plano_trabalho', 'pk_tarefa_plano_trabalho', array('id_tarefa_plano_trabalho'));

        BancoSEI::getInstance()->executarSql('insert into tarefa_plano_trabalho (id_tarefa_plano_trabalho, nome) values (1, \'Associado plano de trabalho @PLANO_TRABALHO@\')');
        BancoSEI::getInstance()->executarSql('insert into tarefa_plano_trabalho (id_tarefa_plano_trabalho, nome) values (2, \'Atualizado item @ITEM_ETAPA@@DESCRICAO@\')');
        BancoSEI::getInstance()->executarSql('insert into tarefa_plano_trabalho (id_tarefa_plano_trabalho, nome) values (3, \'Associado documento @DOCUMENTO@ com o item @ITEM_ETAPA@\')');
        BancoSEI::getInstance()->executarSql('insert into tarefa_plano_trabalho (id_tarefa_plano_trabalho, nome) values (4, \'Removida associação do documento @DOCUMENTO@ com o item @ITEM_ETAPA@\')');
        BancoSEI::getInstance()->executarSql('insert into tarefa_plano_trabalho (id_tarefa_plano_trabalho, nome) values (5, \'Removida associação com o plano de trabalho @PLANO_TRABALHO@\')');

        BancoSEI::getInstance()->executarSql('CREATE TABLE andamento_plano_trabalho (
          id_andamento_plano_trabalho ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_plano_trabalho    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_procedimento    ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL ,
          id_tarefa_plano_trabalho ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_usuario_origem    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_unidade_origem    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          dth_execucao         ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL,
          sta_situacao         ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NULL
        )');

        $objInfraMetaBD->adicionarChavePrimaria('andamento_plano_trabalho', 'pk_andamento_plano_trabalho', array('id_andamento_plano_trabalho'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_andamento_plano_trabalho', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_andam_plano_trab_plano_trab', 'andamento_plano_trabalho', array('id_plano_trabalho'), 'plano_trabalho', array('id_plano_trabalho'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_andam_plano_trab_proced', 'andamento_plano_trabalho', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_and_plan_trab_tar_plan_trab', 'andamento_plano_trabalho', array('id_tarefa_plano_trabalho'), 'tarefa_plano_trabalho', array('id_tarefa_plano_trabalho'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_andam_plano_trab_usu_origem', 'andamento_plano_trabalho', array('id_usuario_origem'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_andam_plano_trab_uni_origem', 'andamento_plano_trabalho', array('id_unidade_origem'), 'unidade', array('id_unidade'));

        BancoSEI::getInstance()->executarSql('CREATE TABLE atributo_andam_plano_trab (
          id_atributo_andam_plano_trab ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_andamento_plano_trabalho ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          chave                ' . $objInfraMetaBD->tipoTextoVariavel(50) . '  NOT NULL ,
          valor                ' . $objInfraMetaBD->tipoTextoVariavel(250) . '  NULL ,
          id_origem            ' . $objInfraMetaBD->tipoTextoVariavel(50) . '  NULL
        )');

        $objInfraMetaBD->adicionarChavePrimaria('atributo_andam_plano_trab', 'pk_atributo_andam_plano_trab', array('id_atributo_andam_plano_trab'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_atributo_andam_plano_trab', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_atr_and_pla_tra_and_pla_tra', 'atributo_andam_plano_trab', array('id_andamento_plano_trabalho'), 'andamento_plano_trabalho', array('id_andamento_plano_trabalho'));


        BancoSEI::getInstance()->executarSql('CREATE TABLE rel_serie_plano_trabalho
        (
          id_serie             ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_plano_trabalho    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL
        )');

        $objInfraMetaBD->adicionarChavePrimaria('rel_serie_plano_trabalho', 'pk_rel_serie_plano_trabalho', array('id_serie', 'id_plano_trabalho'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_serie_plano_trab_serie', 'rel_serie_plano_trabalho', array('id_serie'), 'serie', array('id_serie'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_serie_plano_trab_plano', 'rel_serie_plano_trabalho', array('id_plano_trabalho'), 'plano_trabalho', array('id_plano_trabalho'));

        $objInfraMetaBD->adicionarColuna('tipo_procedimento', 'id_plano_trabalho', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_tipo_proced_plano_trabalho', 'tipo_procedimento', array('id_plano_trabalho'), 'plano_trabalho', array('id_plano_trabalho'));

        $objInfraMetaBD->adicionarColuna('procedimento', 'id_plano_trabalho', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_procedimento_plano_trabalho', 'procedimento', array('id_plano_trabalho'), 'plano_trabalho', array('id_plano_trabalho'));

        $objInfraMetaBD->adicionarColuna('serie', 'sin_valor_monetario', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update serie set sin_valor_monetario=\'N\'');
        $objInfraMetaBD->alterarColuna('serie', 'sin_valor_monetario', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

        BancoSEI::getInstance()->executarSql('CREATE TABLE infra_erro_php (
          id_infra_erro_php     ' . $objInfraMetaBD->tipoTextoVariavel(32) . '  NOT NULL ,
          sta_tipo              ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          arquivo               ' . $objInfraMetaBD->tipoTextoVariavel(255) . '  NOT NULL ,
          linha                 ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          erro                  ' . $objInfraMetaBD->tipoTextoVariavel(4000) . '  NOT NULL ,
          dth_cadastro          ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL)
        ');

        $objInfraMetaBD->adicionarChavePrimaria('infra_erro_php', 'pk_infra_erro_php', array('id_infra_erro_php'));

        BancoSEI::getInstance()->executarSql('CREATE TABLE infra_captcha (
            identificacao         ' . $objInfraMetaBD->tipoTextoVariavel(50) . '  NOT NULL ,
            dia                   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            mes                   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            ano                   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            acertos               ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL ,
            erros                 ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL
          )');

        $objInfraMetaBD->adicionarChavePrimaria('infra_captcha', 'pk_infra_captcha', array('identificacao', 'dia', 'mes', 'ano'));

        BancoSEI::getInstance()->executarSql('CREATE TABLE infra_captcha_tentativa (
          identificacao         ' . $objInfraMetaBD->tipoTextoVariavel(50) . '  NOT NULL ,
          id_usuario_origem     ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
          tentativas            ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
          dth_tentativa         ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
          user_agent            ' . $objInfraMetaBD->tipoTextoVariavel(500) . '  NOT NULL ,
          ip                    ' . $objInfraMetaBD->tipoTextoVariavel(15) . '  NOT NULL 
        )');

        $objInfraMetaBD->adicionarChavePrimaria('infra_captcha_tentativa', 'pk_infra_captcha_tentativa', array('identificacao', 'id_usuario_origem'));

        BancoSEI::getInstance()->executarSql('
            CREATE TABLE aviso
            (
              id_aviso            ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
              sta_aviso           ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL ,
              sin_liberado         ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL ,
              dth_inicio          ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
              dth_fim             ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
              descricao           ' . $objInfraMetaBD->tipoTextoVariavel(500) . '   NULL ,
              link                ' . $objInfraMetaBD->tipoTextoVariavel(250) . '   NULL ,
              imagem                ' . $objInfraMetaBD->tipoTextoGrande() . '  NOT NULL 
            )');
        $objInfraMetaBD->adicionarChavePrimaria('aviso', 'pk_aviso', array('id_aviso'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_aviso', 1);
        $objInfraMetaBD->criarIndice('aviso', 'i01_aviso', array('dth_inicio', 'dth_fim', 'sin_liberado'));

        BancoSEI::getInstance()->executarSql('
            CREATE TABLE rel_aviso_orgao
            (
              id_aviso            ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
              id_orgao            ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL 
            )');
        $objInfraMetaBD->adicionarChavePrimaria('rel_aviso_orgao', 'pk_rel_aviso_orgao', array('id_aviso', 'id_orgao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_aviso_orgao_aviso', 'rel_aviso_orgao', array('id_aviso'), 'aviso', array('id_aviso'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_aviso_orgao_orgao', 'rel_aviso_orgao', array('id_orgao'), 'orgao', array('id_orgao'));

        BancoSEI::getInstance()->criarSequencialNativa('seq_notificacao', 1);

        $objInfraMetaBD->excluirIndice('lembrete', 'i01_lembrete');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_lembrete_usuario', 'lembrete', array('id_usuario'), 'usuario', array('id_usuario'));

        $objInfraMetaBD->adicionarColuna('documento', 'sin_versoes', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update documento set sin_versoes=\'S\' where sta_documento=\'' . DocumentoRN::$TD_EDITOR_INTERNO . '\'');
        BancoSEI::getInstance()->executarSql('update documento set sin_versoes=\'N\' where sta_documento<>\'' . DocumentoRN::$TD_EDITOR_INTERNO . '\'');
        $objInfraMetaBD->alterarColuna('documento', 'sin_versoes', $objInfraMetaBD->tipoTextoFixo(1), 'not null');
        $objInfraMetaBD->criarIndice('documento', 'i07_documento', array('id_documento', 'sin_versoes'));

        $objInfraMetaBD->adicionarColuna('documento', 'din_valor', $objInfraMetaBD->tipoNumeroDecimal(15,2), 'null');

        try {
          $objInfraMetaBD->excluirChavePrimaria('controle_unidade', 'pk_controle_unidade');
        } catch (Exception $e) {
        }

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE reabertura_programada (
          id_reabertura_programada ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_protocolo          ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL ,
          id_unidade            ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_usuario            ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_atividade          ' . $objInfraMetaBD->tipoNumero() . '  NULL ,
          dta_programada        ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
          dth_alteracao         ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL,
          dth_processamento     ' . $objInfraMetaBD->tipoDataHora() . '  NULL,
          dth_visualizacao      ' . $objInfraMetaBD->tipoDataHora() . '  NULL,
          erro         ' . $objInfraMetaBD->tipoTextoVariavel(250) . '  NULL 
        )');
        $objInfraMetaBD->adicionarChavePrimaria('reabertura_programada', 'pk_reabertura_programada', array('id_reabertura_programada'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_reabertura_programada', 1);
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_reabertura_prog_unidade', 'reabertura_programada', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_reabertura_prog_usuario', 'reabertura_programada', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_reabertura_prog_atividade', 'reabertura_programada', array('id_atividade'), 'atividade', array('id_atividade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_reabertura_prog_protocolo', 'reabertura_programada', array('id_protocolo'), 'protocolo', array('id_protocolo'));
        $objInfraMetaBD->criarIndice('reabertura_programada', 'i01_reabertura_programada', array('id_protocolo', 'id_unidade', 'dta_programada'));
        $objInfraMetaBD->criarIndice('reabertura_programada', 'i02_reabertura_programada', array('dta_programada', 'dth_processamento'));
        $objInfraMetaBD->criarIndice('reabertura_programada', 'i03_reabertura_programada', array('id_protocolo', 'id_unidade'));
        $objInfraMetaBD->criarIndice('reabertura_programada', 'i04_reabertura_programada', array('id_protocolo', 'id_unidade', 'dth_processamento', 'dth_visualizacao'));

        $objInfraSequencia = new InfraSequencia(BancoSEI::getInstance());
        BancoSEI::getInstance()->executarSql('insert into infra_agendamento_tarefa (
                            id_infra_agendamento_tarefa, descricao, comando, sta_periodicidade_execucao,
                            periodicidade_complemento, dth_ultima_execucao, dth_ultima_conclusao,
                            sin_sucesso, parametro, email_erro, sin_ativo)
                            values (' . $objInfraSequencia->obterProximaSequencia('infra_agendamento_tarefa') . ',\'Processa reaberturas programadas de processos.\',\'AgendamentoRN::reabrirProcessos\',\'D\',\'00:01,01:01\',null,null,\'N\',null,null,\'S\')');

        BancoSEI::getInstance()->executarSql('update infra_agendamento_tarefa set periodicidade_complemento=\'00:00,01:00\' where comando=\'AgendamentoRN::confirmarPublicacaoInterna\'');

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE documento_geracao
        (
          id_documento         ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL ,
          id_documento_modelo    ' . $objInfraMetaBD->tipoNumeroGrande() . '  NULL,
          id_texto_padrao_interno ' . $objInfraMetaBD->tipoNumero() . '  NULL 	 
        )');
        $objInfraMetaBD->adicionarChavePrimaria('documento_geracao', 'pk_documento_geracao', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_documento_geracao_documento', 'documento_geracao', array('id_documento'), 'documento', array('id_documento'), false);

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE avaliacao_documental
        (
          id_avaliacao_documental      ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_procedimento      ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL ,
          id_assunto_proxy      ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_assunto      ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_usuario           ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_unidade           ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          sta_avaliacao        ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL ,
          dta_avaliacao         ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL
        )
        ');
        $objInfraMetaBD->adicionarChavePrimaria('avaliacao_documental', 'pk_avaliacao_documental ', array('id_avaliacao_documental'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_avaliacao_documental_proced', 'avaliacao_documental', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_avaliacao_documental_usu', 'avaliacao_documental', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_avaliacao_doc_unidade', 'avaliacao_documental', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_avaliacao_doc_assunto_proxy', 'avaliacao_documental', array('id_assunto_proxy'), 'assunto_proxy', array('id_assunto_proxy'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_avaliacao_doc_assunto', 'avaliacao_documental', array('id_assunto'), 'assunto', array('id_assunto'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_avaliacao_documental', 1);

        BancoSEI::getInstance()->executarSql('
       CREATE TABLE cpad
            (
              id_cpad              ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
              id_orgao             ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
              sigla                ' . $objInfraMetaBD->tipoTextoVariavel(30) . '  NOT NULL ,
              descricao            ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
              sin_ativo            ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL
            )
        ');
        $objInfraMetaBD->adicionarChavePrimaria('cpad', 'pk_cpad', array('id_cpad'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_orgao', 'cpad', array('id_orgao'), 'orgao', array('id_orgao'));

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE cpad_versao
        (
          id_cpad_versao       ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_cpad              ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          sigla                ' . $objInfraMetaBD->tipoTextoVariavel(30) . '  NOT NULL ,
          descricao            ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
          dth_versao           ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
          sin_editavel          ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL ,
          sin_ativo          ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL ,
          id_usuario           ' . $objInfraMetaBD->tipoNumero() . '  NULL ,
          id_unidade           ' . $objInfraMetaBD->tipoNumero() . '  NULL
        )
        ');
        $objInfraMetaBD->adicionarChavePrimaria('cpad_versao', 'pk_cpad_versao', array('id_cpad_versao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_versao_usuario', 'cpad_versao', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_versao_unidade', 'cpad_versao', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_versao_cpad', 'cpad_versao', array('id_cpad'), 'cpad', array('id_cpad'));

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE cpad_composicao
          (
            id_cpad_composicao   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            id_cpad_versao       ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            id_usuario           ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            id_cargo             ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            sin_presidente        ' . $objInfraMetaBD->tipoTextoFixo(1) . '   NOT NULL,
            ordem                 ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL
          )
        ');
        $objInfraMetaBD->adicionarChavePrimaria('cpad_composicao', 'pk_cpad_composicao', array('id_cpad_composicao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_composicao_cpad_versao', 'cpad_composicao', array('id_cpad_versao'), 'cpad_versao', array('id_cpad_versao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_composicao_usuario', 'cpad_composicao', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_composicao_cargo', 'cpad_composicao', array('id_cargo'), 'cargo', array('id_cargo'));

        BancoSEI::getInstance()->criarSequencialNativa('seq_cpad', 1);
        BancoSEI::getInstance()->criarSequencialNativa('seq_cpad_versao', 1);
        BancoSEI::getInstance()->criarSequencialNativa('seq_cpad_composicao', 1);

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE cpad_avaliacao
        (
          id_cpad_avaliacao    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_avaliacao_documental ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_cpad_composicao   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          dth_avaliacao        ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
          sta_cpad_avaliacao   ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL ,
          motivo              ' . $objInfraMetaBD->tipoTextoGrande() . '  NULL,
          justificativa              ' . $objInfraMetaBD->tipoTextoGrande() . '  NULL,
          sin_ativo   ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL
        )
        ');
        $objInfraMetaBD->adicionarChavePrimaria('cpad_avaliacao', 'pk_cpad_avaliacao', array('id_cpad_avaliacao'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_cpad_avaliacao', 1);

        $objInfraMetaBD->criarIndice('cpad_avaliacao', 'i01_cpad_avaliacao', array('id_cpad_composicao'), false);
        $objInfraMetaBD->criarIndice('cpad_avaliacao', 'i02_cpad_avaliacao', array('id_avaliacao_documental'), false);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_avaliacao_cpad_comp', 'cpad_avaliacao', array('id_cpad_composicao'), 'cpad_composicao', array('id_cpad_composicao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_cpad_avaliacao_aval_doc', 'cpad_avaliacao', array('id_avaliacao_documental'), 'avaliacao_documental', array('id_avaliacao_documental'));

        $objInfraMetaBD->criarIndice('avaliacao_documental', 'i04_avaliacao_documental', array('id_usuario', 'dta_avaliacao', 'sta_avaliacao'), false);

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_NUM_DIAS_PRAZO_ELIMINACAO\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_NUM_DIAS_PRAZO_ELIMINACAO\',\'\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'ID_TIPO_PROCEDIMENTO_ELIMINACAO\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'ID_TIPO_PROCEDIMENTO_ELIMINACAO\',\'\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'ID_SERIE_EDITAL_ELIMINACAO\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'ID_SERIE_EDITAL_ELIMINACAO\',\'\')');
        }

        $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'ID_SERIE_EDITAL_ELIMINACAO_LISTAGEM_ELIMINADOS\'');
        if ($rs[0]['total'] == 0) {
          BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'ID_SERIE_EDITAL_ELIMINACAO_LISTAGEM_ELIMINADOS\',\'\')');
        }


        BancoSEI::getInstance()->executarSql('
        CREATE TABLE edital_eliminacao
        (
          id_edital_eliminacao ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_procedimento      ' . $objInfraMetaBD->tipoNumeroGrande() . '  NULL ,
          id_documento         ' . $objInfraMetaBD->tipoNumeroGrande() . '  NULL ,
          id_unidade           ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
          especificacao        ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
          dta_publicacao       ' . $objInfraMetaBD->tipoDataHora() . '  NULL ,
          sta_edital_eliminacao ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL
        )
        ');
        $objInfraMetaBD->adicionarChavePrimaria('edital_eliminacao', 'pk_edital_eliminacao', array('id_edital_eliminacao'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_edital_eliminacao', 1);


        $objInfraMetaBD->adicionarChaveEstrangeira('fk_edital_eliminacao_documento', 'edital_eliminacao', array('id_documento'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_edital_eliminacao_proced', 'edital_eliminacao', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_edital_eliminacao_unidade', 'edital_eliminacao', array('id_unidade'), 'unidade', array('id_unidade'));

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE edital_eliminacao_conteudo
        (
          id_edital_eliminacao_conteudo ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_avaliacao_documental ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_edital_eliminacao ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          id_usuario_inclusao  ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          dth_inclusao         ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL
        )
        ');
        $objInfraMetaBD->adicionarChavePrimaria('edital_eliminacao_conteudo', 'pk_edital_eliminacao_conteudo', array('id_edital_eliminacao_conteudo'));
        BancoSEI::getInstance()->criarSequencialNativa('seq_edital_eliminacao_conteudo', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_aval_doc_ed_eli_av_doc', 'edital_eliminacao_conteudo', array('id_avaliacao_documental'), 'avaliacao_documental', array('id_avaliacao_documental'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_aval_doc_ed_eli_ed_eli', 'edital_eliminacao_conteudo', array('id_edital_eliminacao'), 'edital_eliminacao', array('id_edital_eliminacao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_edital_elim_cont_usu_inclus', 'edital_eliminacao_conteudo', array('id_usuario_inclusao'), 'usuario', array('id_usuario'));

        BancoSEI::getInstance()->executarSql('
          CREATE TABLE edital_eliminacao_erro
          (
            id_edital_eliminacao_erro ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            id_edital_eliminacao_conteudo ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            dth_erro             ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
            texto_erro           ' . $objInfraMetaBD->tipoTextoVariavel(4000) . '  NOT NULL 
          )');

        $objInfraMetaBD->adicionarChavePrimaria('edital_eliminacao_erro', 'pk_edital_eliminacao_erro', array('id_edital_eliminacao_erro'));

        BancoSEI::getInstance()->criarSequencialNativa('seq_edital_eliminacao_erro', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_edit_elim_erro_edit_eli_con', 'edital_eliminacao_erro', array('id_edital_eliminacao_conteudo'), 'edital_eliminacao_conteudo', array('id_edital_eliminacao_conteudo'));

        BancoSEI::getInstance()->executarSql("insert into tarefa (id_tarefa,nome,sin_historico_resumido, sin_historico_completo, sin_lancar_andamento_fechado, sin_permite_processo_fechado, sin_fechar_andamentos_abertos, id_tarefa_modulo) values (130,'Eliminação de documentos eletrônicos: @DOCUMENTO@','S','S','S','S','N',null)");

        BancoSEI::getInstance()->executarSql("insert into tarefa (id_tarefa,nome,sin_historico_resumido, sin_historico_completo, sin_lancar_andamento_fechado, sin_permite_processo_fechado, sin_fechar_andamentos_abertos, id_tarefa_modulo) values (131,'Desarquivamento para eliminação: @DOCUMENTO@','S','S','S','S','N',null)");

        $objInfraMetaBD->adicionarColuna('procedimento', 'dta_conclusao', $objInfraMetaBD->tipoDataHora(), 'null');
        $this->fixDataConclusaoProcesso();
        $objInfraMetaBD->criarIndice('procedimento', 'i01_procedimento', array('dta_conclusao'), false);
        $objInfraMetaBD->adicionarColuna('procedimento', 'dta_eliminacao', $objInfraMetaBD->tipoDataHora(), 'null');
        $objInfraMetaBD->criarIndice('procedimento', 'i02_procedimento', array('dta_eliminacao'), false);

        BancoSEI::getInstance()->executarSql("insert into tarefa (id_tarefa,nome,sin_historico_resumido, sin_historico_completo, sin_lancar_andamento_fechado, sin_permite_processo_fechado, sin_fechar_andamentos_abertos, id_tarefa_modulo) values (132,'Processo incluído no edital de eliminação @DOCUMENTO@','S','S','S','S','N',null)");
        BancoSEI::getInstance()->executarSql("insert into tarefa (id_tarefa,nome,sin_historico_resumido, sin_historico_completo, sin_lancar_andamento_fechado, sin_permite_processo_fechado, sin_fechar_andamentos_abertos, id_tarefa_modulo) values (133,'Processo retirado do edital de eliminação @DOCUMENTO@','S','S','S','S','N',null)");

        $objInfraMetaBD->adicionarColuna('arquivamento', 'sta_eliminacao', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update arquivamento set sta_eliminacao=\'' . ArquivamentoRN::$TE_NAO_ELIMINADO . '\'');
        $objInfraMetaBD->alterarColuna('arquivamento', 'sta_eliminacao', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

        $objInfraMetaBD->adicionarColuna('arquivamento', 'id_atividade_eliminacao', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_arquiv_ativ_eliminacao', 'arquivamento', array('id_atividade_eliminacao'), 'atividade', array('id_atividade'));

        $objInfraMetaBD->adicionarColuna('protocolo', 'sin_eliminado', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update protocolo set sin_eliminado=\'N\'');
        $objInfraMetaBD->alterarColuna('protocolo', 'sin_eliminado', $objInfraMetaBD->tipoTextoFixo(1), 'not null');
        $objInfraMetaBD->criarIndice('protocolo', 'i17_protocolo', array('sin_eliminado'));

        $objInfraMetaBD->adicionarColuna('localizador', 'sin_ativo', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update localizador set sin_ativo=\'S\'');
        $objInfraMetaBD->alterarColuna('localizador', 'sin_ativo', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

        $objInfraMetaBD->adicionarColuna('rel_protocolo_assunto', 'id_protocolo_procedimento', $objInfraMetaBD->tipoNumeroGrande(), 'null');

        $objInfraMetaBD->excluirColuna('usuario', 'sin_acessibilidade');

        $objInfraMetaBD->criarIndice('bloco', 'i03_bloco', array('id_bloco', 'id_unidade', 'sta_tipo', 'sta_estado'));
        $objInfraMetaBD->criarIndice('bloco', 'i04_bloco', array('id_bloco', 'sta_estado'));
        $objInfraMetaBD->criarIndice('rel_bloco_unidade', 'i08_rel_bloco_unidade', array('id_bloco', 'id_unidade', 'sin_retornado'));

        $objInfraMetaBD->criarIndice('acesso_externo', 'i06_acesso_externo', array('sta_tipo', 'dta_validade'));
        $objInfraMetaBD->criarIndice('acesso_externo', 'i07_acesso_externo', array('id_documento', 'sta_tipo', 'dta_validade'));

        $objInfraMetaBD->alterarColuna('andamento_marcador', 'texto', $objInfraMetaBD->tipoTextoVariavel(500), 'null');

        $objInfraMetaBD->adicionarColuna('documento', 'sta_editor', $objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update documento set sta_editor=\'' . EditorRN::$VE_NENHUM . '\' where sta_documento<>\'' . DocumentoRN::$TD_EDITOR_INTERNO . '\'');
        BancoSEI::getInstance()->executarSql('update documento set sta_editor=\'' . EditorRN::$VE_CK4 . '\' where sta_documento=\'' . DocumentoRN::$TD_EDITOR_INTERNO . '\'');
        $objInfraMetaBD->alterarColuna('documento', 'sta_editor', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

        BancoSEI::getInstance()->executarSql('update tarefa set nome=\'Bloco @BLOCO@ disponibilizado para @UNIDADE@\' where id_tarefa=38');
        BancoSEI::getInstance()->executarSql('update tarefa set nome=\'Cancelada disponibilização do bloco @BLOCO@ para @UNIDADE@\' where id_tarefa=39');
        BancoSEI::getInstance()->executarSql('update tarefa set nome=\'Bloco @BLOCO@ retornado para @UNIDADE@\' where id_tarefa=40');

        BancoSEI::getInstance()->executarSql('update tarefa set sin_permite_processo_fechado=\'S\' where id_tarefa=' . TarefaRN::$TI_CANCELAMENTO_ENVIO_PROCESSO_FEDERACAO);

        BancoSEI::getInstance()->executarSql('update infra_agendamento_tarefa set sta_periodicidade_execucao=\'N\', periodicidade_complemento=\'0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55\', sin_ativo=\'S\' where comando = \'AgendamentoRN::processarFederacao\'');

        $objInfraMetaBD->adicionarColuna('email_unidade', 'sequencia', $objInfraMetaBD->tipoNumero(), 'null');
        BancoSEI::getInstance()->executarSql('update email_unidade set sequencia=0');
        $objInfraMetaBD->alterarColuna('email_unidade', 'sequencia', $objInfraMetaBD->tipoNumero(), 'not null');

        $objInfraMetaBD->criarIndice('contato', 'i02_contato', array('cpf'));
        $objInfraMetaBD->criarIndice('contato', 'i03_contato', array('cnpj'));
        $objInfraMetaBD->alterarColuna('contato','telefone_celular',$objInfraMetaBD->tipoTextoVariavel(50),'null');
        $objInfraMetaBD->alterarColuna('contato','telefone_comercial',$objInfraMetaBD->tipoTextoVariavel(100),'null');

        BancoSEI::getInstance()->executarSql('
            CREATE TABLE rel_orgao_pesquisa (
              id_orgao_1           ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
              id_orgao_2           ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL 
            )');

        $objInfraMetaBD->adicionarChavePrimaria('rel_orgao_pesquisa', 'pk_rel_orgao_pesquisa', array('id_orgao_1', 'id_orgao_2'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_orgao_pesq_org_1', 'rel_orgao_pesquisa', array('id_orgao_1'), 'orgao', array('id_orgao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_orgao_pesq_org_2', 'rel_orgao_pesquisa', array('id_orgao_2'), 'orgao', array('id_orgao'));

        $objInfraMetaBD->alterarColuna('serie', 'nome', $objInfraMetaBD->tipoTextoVariavel(100), 'not null');

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $objInfraParametro->setValor('ID_PAIS_BRASIL', ID_BRASIL);

        BancoSEI::getInstance()->executarSql('
            CREATE TABLE tipo_prioridade (
                id_tipo_prioridade   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                nome                 ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
                descricao            ' . $objInfraMetaBD->tipoTextoVariavel(500) . '  NULL ,
                sin_ativo            ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL 
            )');
        $objInfraMetaBD->adicionarChavePrimaria('tipo_prioridade', 'pk_tipo_prioridade', array('id_tipo_prioridade'));

        $objInfraMetaBD->adicionarColuna('procedimento', 'id_tipo_prioridade', $objInfraMetaBD->tipoNumero(), 'null');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_proced_tipo_prioridade', 'procedimento', array('id_tipo_prioridade'), 'tipo_prioridade', array('id_tipo_prioridade'));

        BancoSEI::getInstance()->criarSequencialNativa('seq_tipo_prioridade', 1);

        BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\'137\',\'Alterada prioridade do processo de "@TIPO_PRIORIDADE_ANTERIOR@" para "@TIPO_PRIORIDADE_ATUAL@"\',\'N\',\'S\',\'S\',\'N\',\'N\')');

        BancoSEI::getInstance()->executarSql('
        CREATE TABLE rel_usuario_tipo_prioridade (
                 id_unidade           ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
                 id_usuario           ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                 id_tipo_prioridade    ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL 
            )');
        $objInfraMetaBD->adicionarChavePrimaria('rel_usuario_tipo_prioridade', 'pk_rel_usuario_tipo_prioridade', array('id_unidade,id_usuario,id_tipo_prioridade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_uso_tipo_prio_unid', 'rel_usuario_tipo_prioridade', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_uso_tipo_prio_usuario', 'rel_usuario_tipo_prioridade', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_uso_tipo_prio_tipo_prio', 'rel_usuario_tipo_prioridade', array('id_tipo_prioridade'), 'tipo_prioridade', array('id_tipo_prioridade'));

        BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_HABILITAR_CONSULTA_PROCESSUAL\',\'0\')');

        $objInfraMetaBD->adicionarColuna('orgao','sin_consulta_processual',$objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update orgao set sin_consulta_processual=\'N\'');
        $objInfraMetaBD->alterarColuna('orgao','sin_consulta_processual',$objInfraMetaBD->tipoTextoFixo(1), 'not null');

        $objInfraMetaBD->adicionarColuna('tarefa','sin_consulta_processual ',$objInfraMetaBD->tipoTextoFixo(1), 'null');
        BancoSEI::getInstance()->executarSql('update tarefa  set sin_consulta_processual=\'N\'');
        BancoSEI::getInstance()->executarSql('update tarefa  set sin_consulta_processual=\'S\'  where id_tarefa in ('.
          TarefaRN::$TI_GERACAO_PROCEDIMENTO.','.
          TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE.','.
          TarefaRN::$TI_CONCLUSAO_PROCESSO_UNIDADE.','.
          TarefaRN::$TI_REABERTURA_PROCESSO_UNIDADE.','.
          TarefaRN::$TI_PUBLICACAO.','.
          TarefaRN::$TI_RELACIONAR_PROCEDIMENTO.','.
          TarefaRN::$TI_REMOCAO_RELACIONAMENTO_PROCEDIMENTO.','.
          TarefaRN::$TI_ANEXADO_PROCESSO.','.
          TarefaRN::$TI_ANEXADO_AO_PROCESSO.','.
          TarefaRN::$TI_DESANEXADO_PROCESSO.','.
          TarefaRN::$TI_DESANEXADO_DO_PROCESSO.','.
          TarefaRN::$TI_ALTERACAO_TIPO_PROCESSO.','.
          TarefaRN::$TI_PROCESSO_INCLUSAO_EDITAL_ELIMINACAO.','.
          TarefaRN::$TI_PROCESSO_RETIRADA_EDITAL_ELIMINACAO.','.
          TarefaRN::$TI_ALTERACAO_PRIORIDADE_PROCESSO.')');
        $objInfraMetaBD->alterarColuna('tarefa','sin_consulta_processual',$objInfraMetaBD->tipoTextoFixo(1), 'not null');

        InfraDebug::getInstance()->setBolDebugInfra(false);

        /*
        $objIndexacaoDTO = new IndexacaoDTO();
        $objIndexacaoDTO->setStrSinScript('S');
        $objIndexacaoDTO->setStrSinOrgaos('S');
        $objIndexacaoDTO->setStrSinUnidades('S');
        $objIndexacaoDTO->setStrSinUsuarios('S');
        $objIndexacaoDTO->setStrSinContatos('S');
        $objIndexacaoDTO->setStrSinAssuntos('S');
        $objIndexacaoDTO->setStrSinAcompanhamentos('S');
        $objIndexacaoDTO->setStrSinBlocos('S');
        $objIndexacaoDTO->setStrSinGruposEmail('S');
        $objIndexacaoDTO->setStrSinObservacoes('S');
        $objIndexacaoDTO->setStrSinFavoritos('S');

        $objIndexacaoRN = new IndexacaoRN();
        $objIndexacaoRN->gerarIndexacaoInterna($objIndexacaoDTO);
        */

        InfraDebug::getInstance()->setBolDebugInfra(false);
        $this->fixIndices41($objInfraMetaBD);

      } catch (Throwable $e) {
        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        throw new InfraException('Erro atualizando versão.', $e);
      }
    }

    protected function fixTarefasPrazoAcessoExterno() {
      $arrIdTarefas = array(TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO, TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO_CANCELADA, TarefaRN::$TI_CANCELAMENTO_LIBERACAO_ACESSO_EXTERNO);
      foreach ($arrIdTarefas as $numIdTarefa) {
        $rsTarefas = BancoSEI::getInstance()->consultarSql('select aadias.id_atividade, aadias.id_atributo_andamento as id_atributo_andamento_dias,aadias.valor as dias, aadata.id_atributo_andamento as id_atributo_andamento_data, aadata.valor as data FROM atributo_andamento aadias inner join atividade atdias on aadias.id_atividade = atdias.id_atividade AND atdias.id_tarefa = ' . $numIdTarefa . ' AND aadias.nome  = \'DIAS_VALIDADE\'   inner join atributo_andamento aadata on aadata.id_atividade = aadias.id_atividade inner join atividade atdata on aadata.id_atividade = atdata.id_atividade AND atdata.id_tarefa = ' . $numIdTarefa . ' AND aadata.nome  = \'DATA_VALIDADE\'');

        InfraDebug::getInstance()->setBolDebugInfra(false);

        $n = 0;
        $numRegistros = count($rsTarefas);

        foreach ($rsTarefas as $tarefa) {
          if ((++$n >= 500 && $n % 500 == 0) || $n == $numRegistros) {
            InfraDebug::getInstance()->gravar('ATUALIZANDO ANDAMENTOS DE ACESSO EXTERNO: ' . $n . ' DE ' . $numRegistros);
          }

          $strNovoTexto = "até " . $tarefa['data'] . " (" . $tarefa['dias'] . ")";
          BancoSEI::getInstance()->executarSql("update atributo_andamento set nome = 'VALIDADE', valor = " . BancoSEI::getInstance()->formatarGravacaoStr($strNovoTexto) . "  where id_atributo_andamento = " . $tarefa['id_atributo_andamento_dias']);
          BancoSEI::getInstance()->executarSql("delete from atributo_andamento where id_atributo_andamento = " . $tarefa['id_atributo_andamento_data']);
        }
        InfraDebug::getInstance()->setBolDebugInfra(true);
      }
    }

    protected function fixTelefonesContatosOuvidoria() {
      $objTipoContatoRN = new TipoContatoRN();

      $objTipoContatoDTO = new TipoContatoDTO();
      $objTipoContatoDTO->retNumIdTipoContato();
      $objTipoContatoDTO->setStrNome("Ouvidoria");
      $objTipoContatoDTO = $objTipoContatoRN->consultarRN0336($objTipoContatoDTO);

      if ($objTipoContatoDTO != null) {
        InfraDebug::getInstance()->gravar('ALTERANDO TELEFONES CONTATOS OUVIDORIA');
        BancoSEI::getInstance()->executarSql("update contato set telefone_residencial = telefone_comercial where id_tipo_contato = " . $objTipoContatoDTO->getNumIdTipoContato());
        BancoSEI::getInstance()->executarSql("update contato set telefone_comercial = null where id_tipo_contato = " . $objTipoContatoDTO->getNumIdTipoContato());
      }
    }

    protected function fixHistoricoUnidadeOrgao() {
      InfraDebug::getInstance()->setBolDebugInfra(false);

      InfraDebug::getInstance()->gravar('INSERINDO HISTORICO DOS ORGAOS E UNIDADES');

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->retStrSinAtivo();

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
      $objUnidadeRN = new UnidadeRN();
      $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
      $objAtividadeRN = new AtividadeRN();

      $dtaInicial = InfraData::getStrDataAtual();

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retDthAbertura();
      $objAtividadeDTO->setOrdDthAbertura(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
      $objAtividadeDTO = $objAtividadeRN->consultarRN0033($objAtividadeDTO);

      if ($objAtividadeDTO != null) {
        $dtaAtividade = substr($objAtividadeDTO->getDthAbertura(), 0, 10);

        if (InfraData::compararDatas($dtaAtividade, $dtaInicial) > 0) {
          $dtaInicial = $dtaAtividade;
        }
      }

      $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();
      $objPublicacaoLegadoDTO->retDtaPublicacao();
      $objPublicacaoLegadoDTO->setOrdDtaPublicacao(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objPublicacaoLegadoDTO->setNumMaxRegistrosRetorno(1);
      $objPublicacaoLegadoRN = new PublicacaoLegadoRN();
      $objPublicacaoLegadoDTO = $objPublicacaoLegadoRN->consultar($objPublicacaoLegadoDTO);

      if ($objPublicacaoLegadoDTO != null && InfraData::compararDatas($objPublicacaoLegadoDTO->getDtaPublicacao(), $dtaInicial) > 0) {
        $dtaInicial = $objPublicacaoLegadoDTO->getDtaPublicacao();
      }

      $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);
      if (count($arrObjOrgaoDTO)) {
        foreach ($arrObjOrgaoDTO as $objOrgaoDTO) {
          $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();
          $objOrgaoHistoricoDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
          $objOrgaoHistoricoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
          $objOrgaoHistoricoDTO->setStrDescricao($objOrgaoDTO->getStrDescricao());
          $objOrgaoHistoricoDTO->setDtaInicio($dtaInicial);
          $objOrgaoHistoricoDTO->setDtaFim(null);
          $objOrgaoHistoricoDTO->setBolOrigemSIP(true);

          $objOrgaoHistoricoDTO = $objOrgaoHistoricoRN->cadastrar($objOrgaoHistoricoDTO);

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setBolExclusaoLogica(false);
          $objUnidadeDTO->retNumIdUnidade();
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrDescricao();
          $objUnidadeDTO->retStrSinAtivo();
          $objUnidadeDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());

          $arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);
          if (count($arrObjUnidadeDTO)) {
            foreach ($arrObjUnidadeDTO as $objUnidadeDTO) {
              $objUnidadeHistorico = new UnidadeHistoricoDTO();
              $objUnidadeHistorico->setBolExclusaoLogica(false);
              $objUnidadeHistorico->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
              $objUnidadeHistorico->setStrSigla($objUnidadeDTO->getStrSigla());
              $objUnidadeHistorico->setStrDescricao($objUnidadeDTO->getStrDescricao());
              $objUnidadeHistorico->setDtaInicio($dtaInicial);
              $objUnidadeHistorico->setDtaFim(null);
              $objUnidadeHistorico->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
              $objUnidadeHistorico->setBolOrigemSIP(true);

              $objUnidadeHistoricoRN->cadastrar($objUnidadeHistorico);
            }
          }
        }
      }
      InfraDebug::getInstance()->setBolDebugInfra(true);
    }

    protected function fixMarcadores() {
      try {
        //busca processos com marcador
        $rsProcedimentos = BancoSEI::getInstance()->consultarSql('select distinct ' . BancoSEI::getInstance()->formatarSelecaoDbl('andamento_marcador', 'id_procedimento',
            'idprocedimento') . ' from andamento_marcador order by idprocedimento desc');

        $numRegistros = count($rsProcedimentos);

        InfraDebug::getInstance()->setBolDebugInfra(false);

        $objAndamentoMarcadorBD = new AndamentoMarcadorBD(BancoSEI::getInstance());

        $n = 0;

        //para cada processo
        foreach ($rsProcedimentos as $item) {
          $dblIdProcedimento = BancoSEI::getInstance()->formatarLeituraDbl($item['idprocedimento']);

          if ((++$n >= 500 && $n % 500 == 0) || $n == $numRegistros) {
            InfraDebug::getInstance()->gravar('ATUALIZANDO ANDAMENTOS DE MARCADORES: ' . $n . ' DE ' . $numRegistros);
          }

          $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
          $objAndamentoMarcadorDTO->setDistinct(true);
          $objAndamentoMarcadorDTO->retNumIdUnidade();
          $objAndamentoMarcadorDTO->setDblIdProcedimento($dblIdProcedimento);

          $arrIdUnidadeMarcador = InfraArray::converterArrInfraDTO($objAndamentoMarcadorBD->listar($objAndamentoMarcadorDTO), 'IdUnidade');

          foreach ($arrIdUnidadeMarcador as $numIdUnidade) {
            //recupera andamentos de marcador do processo em ordem ascendente
            $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
            $objAndamentoMarcadorDTO->retNumIdAndamentoMarcador();
            $objAndamentoMarcadorDTO->retDblIdProcedimento();
            $objAndamentoMarcadorDTO->retNumIdMarcador();
            $objAndamentoMarcadorDTO->retNumIdUnidade();
            $objAndamentoMarcadorDTO->retNumIdUsuario();
            $objAndamentoMarcadorDTO->retStrTexto();
            $objAndamentoMarcadorDTO->retDthExecucao();
            $objAndamentoMarcadorDTO->retStrSinUltimo();
            $objAndamentoMarcadorDTO->setDblIdProcedimento($dblIdProcedimento);
            $objAndamentoMarcadorDTO->setNumIdUnidade($numIdUnidade);
            $objAndamentoMarcadorDTO->setOrdNumIdAndamentoMarcador(InfraDTO::$TIPO_ORDENACAO_ASC);

            $arrObjAndamentoMarcadorDTO = $objAndamentoMarcadorBD->listar($objAndamentoMarcadorDTO);

            $numAndamentosMarcadores = count($arrObjAndamentoMarcadorDTO);

            $arrMarcadores = array();

            //para cada andamento
            for ($i = 0; $i < $numAndamentosMarcadores; $i++) {
              //se for o primeiro
              if ($i == 0) {
                //configura operação de inclusão
                $dto = new AndamentoMarcadorDTO();
                $dto->setStrStaOperacao(AndamentoMarcadorRN::$TO_INCLUSAO);

                //se andamento final
                if (($i + 1) == $numAndamentosMarcadores && $arrObjAndamentoMarcadorDTO[$i]->getStrSinUltimo() == 'N') {
                  $dto->setStrSinUltimo('S');
                }

                $dto->setNumIdAndamentoMarcador($arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador());
                $objAndamentoMarcadorBD->alterar($dto);

                //adiciona andamento na lista ativa deste marcador
                $arrMarcadores[$arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador()][] = $arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador();
                //se é o mesmo marcador anterior
              } elseif ($arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador() == $arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador()) {
                //configura operação de alteração
                $dto = new AndamentoMarcadorDTO();
                $dto->setStrStaOperacao(AndamentoMarcadorRN::$TO_ALTERACAO);

                //se andamento final
                if (($i + 1) == $numAndamentosMarcadores && $arrObjAndamentoMarcadorDTO[$i]->getStrSinUltimo() == 'N') {
                  $dto->setStrSinUltimo('S');
                }

                $dto->setNumIdAndamentoMarcador($arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador());
                $objAndamentoMarcadorBD->alterar($dto);

                //adiciona andamento na lista ativa deste marcador
                $arrMarcadores[$arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador()][] = $arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador();
                //se removeu marcador
              } elseif ($arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador() == null && $arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador() != null) {
                //configura operação de remoção
                $dto = new AndamentoMarcadorDTO();
                $dto->setNumIdMarcador($arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador());
                $dto->setStrStaOperacao(AndamentoMarcadorRN::$TO_REMOCAO);
                $dto->setStrSinAtivo('N');
                $dto->setStrSinUltimo('N');
                $dto->setNumIdAndamentoMarcador($arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador());
                $objAndamentoMarcadorBD->alterar($dto);

                //desativa andamentos do marcador
                if (isset($arrMarcadores[$arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador()])) {
                  foreach ($arrMarcadores[$arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador()] as $numIdAndamentoMarcador) {
                    $dto = new AndamentoMarcadorDTO();
                    $dto->setStrSinAtivo('N');
                    $dto->setStrSinUltimo('N');
                    $dto->setNumIdAndamentoMarcador($numIdAndamentoMarcador);
                    $objAndamentoMarcadorBD->alterar($dto);
                  }
                  unset($arrMarcadores[$arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador()]);
                }
                //se adicionou marcador
              } elseif ($arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador() != null && $arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador() == null) {
                //configura operação de inclusão
                $dto = new AndamentoMarcadorDTO();
                $dto->setStrStaOperacao(AndamentoMarcadorRN::$TO_INCLUSAO);

                //se andamento final
                if (($i + 1) == $numAndamentosMarcadores && $arrObjAndamentoMarcadorDTO[$i]->getStrSinUltimo() == 'N') {
                  $dto->setStrSinUltimo('S');
                }

                $dto->setNumIdAndamentoMarcador($arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador());
                $objAndamentoMarcadorBD->alterar($dto);

                $arrMarcadores[$arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador()][] = $arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador();
                //se trocou marcador
              } elseif ($arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador() != null && $arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador() != null) {
                //configura operação de inclusão
                $dto = new AndamentoMarcadorDTO();
                $dto->setStrStaOperacao(AndamentoMarcadorRN::$TO_INCLUSAO);

                if (($i + 1) == $numAndamentosMarcadores && $arrObjAndamentoMarcadorDTO[$i]->getStrSinUltimo() == 'N') {
                  $dto->setStrSinUltimo('S');
                }

                $dto->setNumIdAndamentoMarcador($arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador());
                $objAndamentoMarcadorBD->alterar($dto);

                $arrMarcadores[$arrObjAndamentoMarcadorDTO[$i]->getNumIdMarcador()][] = $arrObjAndamentoMarcadorDTO[$i]->getNumIdAndamentoMarcador();

                //cadastra operação de remoção para o marcador anterior
                $dto = new AndamentoMarcadorDTO();
                $dto->setNumIdAndamentoMarcador(null);
                $dto->setDblIdProcedimento($arrObjAndamentoMarcadorDTO[$i - 1]->getDblIdProcedimento());
                $dto->setNumIdMarcador($arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador());
                $dto->setStrTexto($arrObjAndamentoMarcadorDTO[$i - 1]->getStrTexto());
                $dto->setNumIdUnidade($arrObjAndamentoMarcadorDTO[$i]->getNumIdUnidade());
                $dto->setNumIdUsuario($arrObjAndamentoMarcadorDTO[$i]->getNumIdUsuario());
                $dto->setDthExecucao(InfraData::calcularData(1, InfraData::$UNIDADE_SEGUNDOS, InfraData::$SENTIDO_ATRAS, $arrObjAndamentoMarcadorDTO[$i]->getDthExecucao()));
                $dto->setStrStaOperacao(AndamentoMarcadorRN::$TO_REMOCAO);
                $dto->setStrSinAtivo('N');
                $dto->setStrSinUltimo('N');
                $objAndamentoMarcadorBD->cadastrar($dto);

                //desativa andamentos do marcador anterior
                if (isset($arrMarcadores[$arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador()])) {
                  foreach ($arrMarcadores[$arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador()] as $numIdAndamentoMarcador) {
                    $dto = new AndamentoMarcadorDTO();
                    $dto->setStrSinAtivo('N');
                    $dto->setStrSinUltimo('N');
                    $dto->setNumIdAndamentoMarcador($numIdAndamentoMarcador);
                    $objAndamentoMarcadorBD->alterar($dto);
                  }
                  unset($arrMarcadores[$arrObjAndamentoMarcadorDTO[$i - 1]->getNumIdMarcador()]);
                }
              }
            }
          }
        }

        InfraDebug::getInstance()->setBolDebugInfra(true);

        $rs = BancoSEI::getInstance()->consultarSql('select count(*),' . BancoSEI::getInstance()->formatarSelecaoNum('andamento_marcador', 'id_marcador',
            'idmarcador') . ',' . BancoSEI::getInstance()->formatarSelecaoNum('andamento_marcador', 'id_unidade', 'idunidade') . ',' . BancoSEI::getInstance()->formatarSelecaoDbl('andamento_marcador', 'id_procedimento',
            'idprocedimento') . ' from andamento_marcador where sin_ultimo=\'S\'' . ' group by id_marcador, id_unidade, id_procedimento' . ' having count(*) > 1');

        InfraDebug::getInstance()->setBolDebugInfra(false);

        foreach ($rs as $item) {
          $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
          $objAndamentoMarcadorDTO->setBolExclusaoLogica(false);
          $objAndamentoMarcadorDTO->retNumIdAndamentoMarcador();
          $objAndamentoMarcadorDTO->setStrSinUltimo('S');
          $objAndamentoMarcadorDTO->setDblIdProcedimento(BancoSEI::getInstance()->formatarLeituraDbl($item['idprocedimento']));
          $objAndamentoMarcadorDTO->setNumIdUnidade(BancoSEI::getInstance()->formatarLeituraNum($item['idunidade']));
          $objAndamentoMarcadorDTO->setNumIdMarcador(BancoSEI::getInstance()->formatarLeituraNum($item['idmarcador']));
          $objAndamentoMarcadorDTO->setOrdNumIdAndamentoMarcador(InfraDTO::$TIPO_ORDENACAO_DESC);

          $arrObjAndamentoMarcadorDTOMarcadorUnidade = $objAndamentoMarcadorBD->listar($objAndamentoMarcadorDTO);

          $numAndamentosMarcadorUnidade = count($arrObjAndamentoMarcadorDTOMarcadorUnidade);

          for ($i = 1; $i < $numAndamentosMarcadorUnidade; $i++) {
            $dto = new AndamentoMarcadorDTO();
            $dto->setStrSinUltimo('N');
            $dto->setNumIdAndamentoMarcador($arrObjAndamentoMarcadorDTOMarcadorUnidade[$i]->getNumIdAndamentoMarcador());
            $objAndamentoMarcadorBD->alterar($dto);
          }
        }

        //Criar índice em assinatura por agrupador e sin_ativo

        InfraDebug::getInstance()->setBolDebugInfra(true);

        BancoSEI::getInstance()->executarSql('update andamento_marcador set sin_ultimo=\'N\', sta_operacao=\'' . AndamentoMarcadorRN::$TO_REMOCAO . '\' where id_marcador is null');

        BancoSEI::getInstance()->executarSql('update andamento_marcador set sin_ultimo=\'N\' where sin_ultimo=\'S\' and sin_ativo=\'N\'');
      } catch (Exception $e) {
        throw new InfraException('Erro atualizando marcadores.', $e);
      }
    }

    protected function fixIndices40(InfraMetaBD $objInfraMetaBD) {
      InfraDebug::getInstance()->setBolDebugInfra(true);

      $this->logar('ATUALIZANDO INDICES...');

      $objInfraMetaBD->processarIndicesChavesEstrangeiras(array(
        'acesso', 'acesso_externo', 'acompanhamento', 'andamento_marcador', 'andamento_situacao', 'anexo', 'anotacao',
        'arquivamento', 'arquivo_extensao', 'assinante', 'assinatura', 'assunto', 'assunto_proxy', 'atividade',
        'atributo', 'atributo_andamento', 'auditoria_protocolo', 'base_conhecimento', 'bloco', 'cargo', 'cargo_funcao',
        'cidade', 'conjunto_estilos', 'conjunto_estilos_item', 'contato', 'controle_interno', 'controle_unidade',
        'documento', 'documento_conteudo', 'dominio', 'email_grupo_email', 'email_sistema', 'email_unidade',
        'email_utilizado', 'estatisticas', 'estilo', 'feed', 'feriado', 'grupo_acompanhamento', 'grupo_contato',
        'grupo_email', 'grupo_protocolo_modelo', 'grupo_serie', 'grupo_unidade', 'hipotese_legal', 'imagem_formato',
        'localizador', 'lugar_localizador', 'mapeamento_assunto', 'marcador', 'modelo', 'monitoramento_servico',
        'nivel_acesso_permitido', 'notificacao', 'novidade', 'numeracao', 'observacao', 'operacao_servico',
        'ordenador_despesa', 'orgao', 'pais', 'participante', 'procedimento', 'protocolo', 'protocolo_modelo',
        'publicacao', 'publicacao_legado', 'rel_acesso_ext_protocolo', 'rel_assinante_unidade',
        'rel_base_conhec_tipo_proced', 'rel_bloco_protocolo', 'rel_bloco_unidade', 'rel_controle_interno_orgao',
        'rel_controle_interno_serie', 'rel_controle_interno_tipo_proc', 'rel_controle_interno_unidade',
        'rel_grupo_contato', 'rel_grupo_unidade_unidade', 'rel_notificacao_documento', 'rel_protocolo_assunto',
        'rel_protocolo_atributo', 'rel_protocolo_protocolo', 'rel_secao_modelo_estilo', 'rel_secao_mod_cj_estilos_item',
        'rel_serie_assunto', 'rel_serie_veiculo_publicacao', 'rel_situacao_unidade', 'rel_tipo_procedimento_assunto',
        'rel_unidade_tipo_contato', 'retorno_programado', 'secao_documento', 'secao_imprensa_nacional', 'secao_modelo',
        'serie', 'serie_escolha', 'serie_publicacao', 'serie_restricao', 'servico', 'situacao', 'tabela_assuntos',
        'tarefa', 'tarja_assinatura', 'texto_padrao_interno', 'tipo_conferencia', 'tipo_contato', 'tipo_formulario',
        'tipo_localizador', 'tipo_procedimento', 'tipo_procedimento_escolha', 'tipo_proced_restricao', 'tipo_suporte',
        'tratamento', 'uf', 'unidade', 'unidade_publicacao', 'usuario', 'veiculo_imprensa_nacional', 'veiculo_publicacao',
        'velocidade_transferencia', 'versao_secao_documento', 'vocativo', 'rel_usuario_marcador', 'rel_usuario_grupo_acomp',
        'rel_usuario_usuario_unidade', 'orgao_historico', 'unidade_historico', 'titulo', 'controle_prazo', 'comentario',
        'categoria', 'lembrete', 'rel_acesso_ext_serie', 'grupo_bloco', 'rel_usuario_grupo_bloco', 'instalacao_federacao',
        'tarefa_instalacao', 'andamento_instalacao', 'atributo_instalacao', 'orgao_federacao', 'unidade_federacao',
        'usuario_federacao', 'protocolo_federacao', 'acesso_federacao', 'acao_federacao', 'parametro_acao_federacao'
      ));

      InfraDebug::getInstance()->setBolDebugInfra(false);
    }

    protected function fixIndices41(InfraMetaBD $objInfraMetaBD) {
      InfraDebug::getInstance()->setBolDebugInfra(true);

      $this->logar('ATUALIZANDO INDICES...');

      $objInfraMetaBD->processarIndicesChavesEstrangeiras(array(
        'acesso', 'acesso_externo', 'acompanhamento', 'andamento_marcador', 'andamento_situacao', 'anexo', 'anotacao',
        'arquivamento', 'arquivo_extensao', 'assinante', 'assinatura', 'assunto', 'assunto_proxy', 'atividade', 'atributo',
        'atributo_andamento', 'auditoria_protocolo', 'base_conhecimento', 'bloco', 'cargo', 'cargo_funcao', 'cidade',
        'conjunto_estilos', 'conjunto_estilos_item', 'contato', 'controle_interno', 'controle_unidade', 'documento',
        'documento_conteudo', 'dominio', 'email_grupo_email', 'email_sistema', 'email_unidade', 'email_utilizado',
        'estatisticas', 'estilo', 'feed', 'feriado', 'grupo_acompanhamento', 'grupo_contato', 'grupo_email',
        'grupo_protocolo_modelo', 'grupo_serie', 'grupo_unidade', 'hipotese_legal', 'imagem_formato', 'localizador',
        'lugar_localizador', 'mapeamento_assunto', 'marcador', 'modelo', 'monitoramento_servico', 'nivel_acesso_permitido',
        'notificacao', 'novidade', 'numeracao', 'observacao', 'operacao_servico', 'ordenador_despesa', 'orgao', 'pais',
        'participante', 'procedimento', 'protocolo', 'protocolo_modelo', 'publicacao', 'publicacao_legado',
        'rel_acesso_ext_protocolo', 'rel_assinante_unidade', 'rel_base_conhec_tipo_proced', 'rel_bloco_protocolo',
        'rel_bloco_unidade', 'rel_controle_interno_orgao', 'rel_controle_interno_serie', 'rel_controle_interno_tipo_proc',
        'rel_controle_interno_unidade', 'rel_grupo_contato', 'rel_grupo_unidade_unidade', 'rel_notificacao_documento',
        'rel_protocolo_assunto', 'rel_protocolo_atributo', 'rel_protocolo_protocolo', 'rel_secao_modelo_estilo',
        'rel_secao_mod_cj_estilos_item', 'rel_serie_assunto', 'rel_serie_veiculo_publicacao', 'rel_situacao_unidade',
        'rel_tipo_procedimento_assunto', 'rel_unidade_tipo_contato', 'retorno_programado', 'secao_documento',
        'secao_imprensa_nacional', 'secao_modelo', 'serie', 'serie_escolha', 'serie_publicacao', 'serie_restricao',
        'servico', 'situacao', 'tabela_assuntos', 'tarefa', 'tarja_assinatura', 'texto_padrao_interno', 'tipo_conferencia',
        'tipo_contato', 'tipo_formulario', 'tipo_localizador', 'tipo_procedimento', 'tipo_procedimento_escolha',
        'tipo_proced_restricao', 'tipo_suporte', 'tratamento', 'uf', 'unidade', 'unidade_publicacao', 'usuario',
        'veiculo_imprensa_nacional', 'veiculo_publicacao', 'versao_secao_documento', 'vocativo',
        'rel_usuario_marcador', 'rel_usuario_grupo_acomp', 'rel_usuario_usuario_unidade', 'orgao_historico', 'unidade_historico',
        'titulo', 'controle_prazo', 'comentario', 'categoria', 'lembrete', 'rel_acesso_ext_serie', 'grupo_bloco',
        'rel_usuario_grupo_bloco', 'instalacao_federacao', 'tarefa_instalacao', 'andamento_instalacao', 'atributo_instalacao',
        'orgao_federacao', 'unidade_federacao', 'usuario_federacao', 'protocolo_federacao', 'acesso_federacao',
        'acao_federacao', 'parametro_acao_federacao', 'aviso', 'rel_aviso_orgao', 'reabertura_programada',
        'documento_geracao', 'avaliacao_documental', 'cpad', 'cpad_versao', 'cpad_composicao', 'cpad_avaliacao',
        'edital_eliminacao', 'edital_eliminacao_conteudo', 'edital_eliminacao_erro', 'rel_orgao_pesquisa', 'tipo_prioridade',
        'rel_usuario_tipo_prioridade'
      ));

      InfraDebug::getInstance()->setBolDebugInfra(false);
    }

    protected function fixBlocosUnidadeGeradora() {
      try {
        InfraDebug::getInstance()->gravar('AJUSTANDO BLOCOS DA UNIDADE');

        InfraDebug::getInstance()->setBolDebugInfra(false);

        $sql = 'select ' . BancoSEI::getInstance()->formatarSelecaoNum('bloco', 'id_bloco', 'idbloco') . ',' . BancoSEI::getInstance()->formatarSelecaoNum('bloco', 'id_unidade',
            'idunidade') . ' ' . 'from bloco ' . 'where not exists (select rel_bloco_unidade.id_bloco ' . 'from rel_bloco_unidade ' . 'where rel_bloco_unidade.id_bloco=bloco.id_bloco and rel_bloco_unidade.id_unidade=bloco.id_unidade)';


        $rs = BancoSEI::getInstance()->consultarSql($sql);

        $numRegistros = count($rs);

        $n = 0;

        $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
        $objRelBlocoUnidadeDTO->setNumIdBloco(null);
        $objRelBlocoUnidadeDTO->setNumIdUnidade(null);
        $objRelBlocoUnidadeDTO->setNumIdGrupoBloco(null);
        $objRelBlocoUnidadeDTO->setNumIdUsuarioAtribuicao(null);
        $objRelBlocoUnidadeDTO->setNumIdUsuarioRevisao(null);
        $objRelBlocoUnidadeDTO->setStrSinRevisao('N');
        $objRelBlocoUnidadeDTO->setDthRevisao(null);
        $objRelBlocoUnidadeDTO->setNumIdUsuarioPrioridade(null);
        $objRelBlocoUnidadeDTO->setStrSinPrioridade('N');
        $objRelBlocoUnidadeDTO->setDthPrioridade(null);
        $objRelBlocoUnidadeDTO->setNumIdUsuarioComentario(null);
        $objRelBlocoUnidadeDTO->setStrTextoComentario(null);
        $objRelBlocoUnidadeDTO->setStrSinComentario('N');
        $objRelBlocoUnidadeDTO->setDthComentario(null);
        $objRelBlocoUnidadeDTO->setStrSinRetornado('N');

        $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD(BancoSEI::getInstance());
        $arrObjRelBlocoUnidadeDTO = array();
        foreach ($rs as $item) {
          $objRelBlocoUnidadeDTOClone = clone($objRelBlocoUnidadeDTO);
          $objRelBlocoUnidadeDTOClone->setNumIdBloco(BancoSEI::getInstance()->formatarLeituraNum($item['idbloco']));
          $objRelBlocoUnidadeDTOClone->setNumIdUnidade(BancoSEI::getInstance()->formatarLeituraNum($item['idunidade']));
          $arrObjRelBlocoUnidadeDTO[] = $objRelBlocoUnidadeDTOClone;

          if ((++$n >= 1000 && $n % 1000 == 0) || $n == $numRegistros) {
            InfraDebug::getInstance()->gravar($n . ' DE ' . $numRegistros);
            $objRelBlocoUnidadeBD->cadastrar($arrObjRelBlocoUnidadeDTO);

            unset($arrObjRelBlocoUnidadeDTO);

            $arrObjRelBlocoUnidadeDTO = array();
          }
        }

        InfraDebug::getInstance()->setBolDebugInfra(true);
      } catch (Exception $e) {
        throw new InfraException('Erro ajustando blocos da unidade.', $e);
      }
    }

    protected function configurarUsuarioInternet() {
      try {
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setNumMaxRegistrosRetorno(1);
        $objUsuarioDTO->setBolExclusaoLogica(false);
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->setStrSigla('INTERNET');
        $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SISTEMA);

        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

        if ($objUsuarioDTO == null) {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setNumIdUsuario(null);
          $objUsuarioDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoSistema());
          $objUsuarioDTO->setStrIdOrigem(null);
          $objUsuarioDTO->setStrSigla('INTERNET');
          $objUsuarioDTO->setStrNome('INTERNET');
          $objUsuarioDTO->setNumIdContato(null);
          $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SISTEMA);
          $objUsuarioDTO->setStrSenha(null);
          $objUsuarioDTO->setStrSinAtivo('S');
          $objUsuarioDTO = $objUsuarioRN->cadastrarRN0487($objUsuarioDTO);
        }

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $objInfraParametro->setValor('ID_USUARIO_INTERNET', $objUsuarioDTO->getNumIdUsuario());
      } catch (Exception $e) {
        throw new InfraException('Erro configurando usuário INTERNET.', $e);
      }
    }

    protected function cadastrarTipoProcessoFederacao() {
      try {
        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento(null);
        $objTipoProcedimentoDTO->setStrNome('SEI Federação');
        $objTipoProcedimentoDTO->setStrDescricao('Aplicado automaticamente em processos recebidos pelo SEI Federação.');
        $objTipoProcedimentoDTO->setStrStaGrauSigiloSugestao(ProtocoloRN::$NA_PUBLICO);
        $objTipoProcedimentoDTO->setNumIdHipoteseLegalSugestao(null);
        $objTipoProcedimentoDTO->setNumIdPlanoTrabalho(null);
        $objTipoProcedimentoDTO->setStrSinInterno('S');
        $objTipoProcedimentoDTO->setStrSinOuvidoria('N');
        $objTipoProcedimentoDTO->setStrSinIndividual('N');

        $objTipoProcedimentoDTO->setArrObjRelTipoProcedimentoAssuntoDTO(array());

        $objTipoProcedimentoDTO->setArrObjTipoProcedRestricaoDTO(array());

        $arrObjNivelAcessoPermitidoDTO = array();

        $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
        $objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_RESTRITO);
        $arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO;

        $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
        $objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_PUBLICO);
        $arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO;

        $objTipoProcedimentoDTO->setArrObjNivelAcessoPermitidoDTO($arrObjNivelAcessoPermitidoDTO);

        $objTipoProcedimentoDTO->setStrStaNivelAcessoSugestao(ProtocoloRN::$NA_RESTRITO);
        $objTipoProcedimentoDTO->setStrSinAtivo('S');

        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $objTipoProcedimentoDTO = $objTipoProcedimentoRN->cadastrarRN0265($objTipoProcedimentoDTO);

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $objInfraParametro->setValor('SEI_ID_TIPO_PROCEDIMENTO_FEDERACAO', $objTipoProcedimentoDTO->getNumIdTipoProcedimento());
      } catch (Exception $e) {
        throw new InfraException('Erro configurando tipo de processo do SEI Federação.', $e);
      }
    }

    protected function fixAssinantes() {
      InfraDebug::getInstance()->setBolDebugInfra(false);

      InfraDebug::getInstance()->gravar('REMONTANDO ASSINATURAS DAS UNIDADES');

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

      $objAssinanteRN = new AssinanteRN();
      $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
      foreach ($arrObjOrgaoDTO as $objOrgaoDTO) {
        InfraDebug::getInstance()->gravar($objOrgaoDTO->getStrSigla() . '...');

        $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
        $objRelAssinanteUnidadeDTO->setDistinct(true);
        $objRelAssinanteUnidadeDTO->retNumIdUnidade();
        $objRelAssinanteUnidadeDTO->retStrCargoFuncaoAssinante();
        $objRelAssinanteUnidadeDTO->setNumIdOrgaoUnidade($objOrgaoDTO->getNumIdOrgao());
        $arrObjRelAssinanteUnidadeDTO = InfraArray::indexarArrInfraDTO($objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO), 'CargoFuncaoAssinante', true);

        foreach ($arrObjRelAssinanteUnidadeDTO as $strCargoFuncao => $arrObjRelAssinanteUnidadeDTOCargoFuncao) {
          $objAssinanteDTO = new AssinanteDTO();
          $objAssinanteDTO->setNumIdAssinante(null);
          $objAssinanteDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
          $objAssinanteDTO->setStrCargoFuncao($strCargoFuncao);
          $objAssinanteDTO->setArrObjRelAssinanteUnidadeDTO($arrObjRelAssinanteUnidadeDTOCargoFuncao);
          $objAssinanteRN->cadastrarRN1335($objAssinanteDTO);
        }
      }

      InfraDebug::getInstance()->setBolDebugInfra(true);

      BancoSEI::getInstance()->executarSql('delete from rel_assinante_unidade where id_assinante in (select id_assinante from assinante where id_orgao is null)');
      BancoSEI::getInstance()->executarSql('delete from assinante where id_orgao is null');
    }

    protected function fixDataConclusaoProcesso() {
      try {
        InfraDebug::getInstance()->setBolDebugInfra(false);

        InfraDebug::getInstance()->gravar('POPULANDO DATA DE CONCLUSAO DO PROCESSO');

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->retDthAbertura();
        $objAtividadeDTO->setOrdDthAbertura(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->consultarRN0033($objAtividadeDTO);
        if ($objAtividadeDTO != null) {
          $dtaInicial = substr($objAtividadeDTO->getDthAbertura(), 0, 10);
        } else {
          $dtaInicial = InfraData::getStrDataAtual();
        }
        $dtaFinal = InfraData::getStrDataAtual();

        $mesAno = substr($dtaInicial, 3, 2) . '/' . substr($dtaInicial, 6, 4);

        while (InfraData::compararDatasSimples($dtaInicial, $dtaFinal) >= 0) {
          $mesAnoAtual = substr($dtaInicial, 3, 2) . '/' . substr($dtaInicial, 6, 4);

          if ($mesAnoAtual != $mesAno) {
            InfraDebug::getInstance()->gravar($mesAnoAtual . '...');
            $mesAno = $mesAnoAtual;
          }

          $sql = ' select ' . BancoSEI::getInstance()->formatarSelecaoDbl('a1', 'id_protocolo',
              'idProtocolo') . ' from atividade a1' . ' where a1.id_tarefa=' . TarefaRN::$TI_GERACAO_PROCEDIMENTO . ' and (a1.dth_abertura>=' . BancoSEI::getInstance()->formatarGravacaoDth($dtaInicial . ' 00:00:00') . ' AND a1.dth_abertura<=' . BancoSEI::getInstance()->formatarGravacaoDth($dtaInicial . ' 23:59:59') . ')' . ' and not exists (select a2.id_protocolo from atividade a2 where a2.id_protocolo=a1.id_protocolo and a2.dth_conclusao is null)' . ' order by id_protocolo';

          $rsProcessos = BancoSEI::getInstance()->consultarSql($sql);

          $objAtividadeBD = new AtividadeBD(BancoSEI::getInstance());
          if (count($rsProcessos)) {
            $arrIdProcessosPartes = array_chunk(InfraArray::simplificarArr($rsProcessos, 'idProtocolo'), 100);

            foreach ($arrIdProcessosPartes as $arrParte) {
              $sql = ' select max(a1.dth_conclusao) as data_conclusao, ' . BancoSEI::getInstance()->formatarSelecaoDbl('a1', 'id_protocolo', 'idProtocolo') . ' from atividade a1' . ' where ' . $objAtividadeBD->formatarIn('a1.id_protocolo',
                  $arrParte, InfraDTO::$PREFIXO_DBL) . ' and id_tarefa in (' . implode(',',
                  array(TarefaRN::$TI_CONCLUSAO_PROCESSO_UNIDADE, TarefaRN::$TI_CONCLUSAO_AUTOMATICA_UNIDADE, TarefaRN::$TI_CONCLUSAO_PROCESSO_USUARIO, TarefaRN::$TI_CONCLUSAO_AUTOMATICA_USUARIO)) . ')' . ' group by id_protocolo';

              $rs = BancoSEI::getInstance()->consultarSql($sql);

              foreach ($rs as $item) {
                $dtaConclusao = substr(BancoSEI::getInstance()->formatarLeituraDth($item['data_conclusao']), 0, 10);
                $sql = 'update procedimento set dta_conclusao=' . BancoSEI::getInstance()->formatarGravacaoDta($dtaConclusao) . ' where id_procedimento=' . BancoSEI::getInstance()->formatarGravacaoDbl($item['idProtocolo']);
                BancoSEI::getInstance()->executarSql($sql);
              }
            }
          }
          $dtaInicial = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtaInicial);
        }

        InfraDebug::getInstance()->setBolDebugInfra(true);
      } catch (Exception $e) {
        throw new InfraException('Erro populando data de conclusão no processo.', $e);
      }
    }

    protected function cadastrarTipoProcessoNaoIdentificado() {
      try {
        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento(null);
        $objTipoProcedimentoDTO->setStrNome('Não Identificado');
        $objTipoProcedimentoDTO->setStrDescricao(null);
        $objTipoProcedimentoDTO->setStrStaGrauSigiloSugestao(ProtocoloRN::$NA_PUBLICO);
        $objTipoProcedimentoDTO->setNumIdHipoteseLegalSugestao(null);
        $objTipoProcedimentoDTO->setNumIdPlanoTrabalho(null);
        $objTipoProcedimentoDTO->setStrSinInterno('S');
        $objTipoProcedimentoDTO->setStrSinOuvidoria('N');
        $objTipoProcedimentoDTO->setStrSinIndividual('N');

        $objTipoProcedimentoDTO->setArrObjRelTipoProcedimentoAssuntoDTO(array());

        $objTipoProcedimentoDTO->setArrObjTipoProcedRestricaoDTO(array());

        $arrObjNivelAcessoPermitidoDTO = array();

        $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
        $objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_RESTRITO);
        $arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO;

        $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
        $objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_PUBLICO);
        $arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO;

        $objTipoProcedimentoDTO->setArrObjNivelAcessoPermitidoDTO($arrObjNivelAcessoPermitidoDTO);

        $objTipoProcedimentoDTO->setStrStaNivelAcessoSugestao(ProtocoloRN::$NA_RESTRITO);
        $objTipoProcedimentoDTO->setStrSinAtivo('S');

        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $objTipoProcedimentoDTO = $objTipoProcedimentoRN->cadastrarRN0265($objTipoProcedimentoDTO);

        return $objTipoProcedimentoDTO->getNumIdTipoProcedimento();
      } catch (Exception $e) {
        throw new InfraException('Erro configurando tipo de processo não identificado.', $e);
      }
    }

  }

  session_start();

  SessaoSEI::getInstance(false);

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

  if (!$objInfraParametro->isSetValor('SEI_VERSAO')) {
    die("\n\nVERSAO DO SEI NAO IDENTIFICADA (REQUER 4.0.*)\n");
  }

  $strVersaoBancoSei = $objInfraParametro->getValor('SEI_VERSAO');

  if (substr($strVersaoBancoSei,0,3)!='4.0'){
    die("\n\nVERSAO DO SEI INSTALADA " . $strVersaoBancoSei . " INCOMPATIVEL (REQUER 4.0.*)\n");
  }

  $objVersaoSeiRN = new VersaoSeiRN();
  $objVersaoSeiRN->setStrNome('SEI');
  $objVersaoSeiRN->setStrVersaoAtual(SEI_VERSAO);
  $objVersaoSeiRN->setStrParametroVersao('SEI_VERSAO');
  $objVersaoSeiRN->setArrVersoes(array(
    '4.0.*' => 'versao_4_0_0',
    '4.1.*' => 'versao_4_1_0'
  ));
  $objVersaoSeiRN->setStrVersaoInfra('2.0.11');
  $objVersaoSeiRN->setBolMySql(true);
  $objVersaoSeiRN->setBolOracle(true);
  $objVersaoSeiRN->setBolSqlServer(true);
  $objVersaoSeiRN->setBolPostgreSql(true);
  $objVersaoSeiRN->setBolErroVersaoInexistente(true);

  $objVersaoSeiRN->atualizarVersao();
} catch (Throwable $e) {
  echo(InfraException::inspecionar($e));
  try {
    LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
  } catch (Exception $e) {
  }
  exit(1);
}
?>