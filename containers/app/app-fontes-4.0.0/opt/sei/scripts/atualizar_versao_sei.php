<?
	try{
	
    require_once dirname(__FILE__).'/../web/SEI.php';

    class VersaoSeiRN extends InfraScriptVersao {

      public function __construct(){
        parent::__construct();
      }

      protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
      }

      public function versao_3_0_0($strVersaoAtual){
      }

      public function versao_3_1_0($strVersaoAtual){
        try{

          $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
          $objInfraMetaBD->setBolValidarIdentificador(true);

          if (BancoSEI::getInstance() instanceof InfraMySql){
            $objScriptRN = new ScriptRN();
            $objScriptRN->atualizarSequencias();
          }

          if (BancoSEI::getInstance() instanceof InfraOracle){
            $objInfraMetaBD->alterarColuna('acompanhamento','tipo_visualizacao',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('andamento_situacao','sin_ultimo',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('assinatura','id_tarja_assinatura',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('assunto','id_tabela_assuntos',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('base_conhecimento','sta_documento',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('contato','sta_natureza',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('contato','sin_endereco_associado',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('contato','id_contato_associado',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('contato','id_tipo_contato',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('controle_unidade','id_situacao',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('documento','sta_documento',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('grupo_contato','sin_ativo',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('grupo_contato','sta_tipo',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('infra_log','sta_tipo',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('infra_navegador','user_agent',$objInfraMetaBD->tipoTextoVariavel(4000),'not null');
            $objInfraMetaBD->alterarColuna('orgao','id_contato',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('serie','sin_interno',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('tarja_assinatura','sin_ativo',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('tarja_assinatura','sta_tarja_assinatura',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('tipo_contato','sin_sistema',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('tipo_contato','sta_acesso',$objInfraMetaBD->tipoTextoFixo(1),'not null');
            $objInfraMetaBD->alterarColuna('usuario','id_contato',$objInfraMetaBD->tipoNumero(),'not null');
            $objInfraMetaBD->alterarColuna('usuario','sin_acessibilidade',$objInfraMetaBD->tipoTextoFixo(1),'not null');
          }

          $this->fixIndices31($objInfraMetaBD);

          InfraDebug::getInstance()->setBolDebugInfra(true);

          $this->logar('ATUALIZANDO PARAMETROS...');

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_HABILITAR_VERIFICACAO_REPOSITORIO\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_HABILITAR_VERIFICACAO_REPOSITORIO\',\'0\')');
          }

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_EXIBIR_ARVORE_RESTRITO_SEM_ACESSO\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_EXIBIR_ARVORE_RESTRITO_SEM_ACESSO\',\'0\')');
          }

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_EMAIL_CONVERTER_ANEXO_HTML_PARA_PDF\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_EMAIL_CONVERTER_ANEXO_HTML_PARA_PDF\',\'0\')');
          }

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO\',\'0\')');
          }

          $objInfraMetaBD->adicionarColuna('assinatura','agrupador',$objInfraMetaBD->tipoTextoVariavel(36),'null');
          $objInfraMetaBD->criarIndice('assinatura','i01_assinatura', array('agrupador'));

          if (count($objInfraMetaBD->obterColunasTabela('protocolo','protocolo_formatado_pesq_inv'))==0){
            $objInfraMetaBD->adicionarColuna('protocolo', 'protocolo_formatado_pesq_inv', $objInfraMetaBD->tipoTextoVariavel(50), 'null');
            BancoSEI::getInstance()->executarSql('update protocolo set protocolo_formatado_pesq_inv = reverse(protocolo_formatado_pesquisa)');
            $objInfraMetaBD->alterarColuna('protocolo', 'protocolo_formatado_pesq_inv', $objInfraMetaBD->tipoTextoFixo(50), 'not null');
            $objInfraMetaBD->criarIndice('protocolo', 'ak4_protocolo', array('protocolo_formatado_pesq_inv'), true);
          }

          BancoSEI::getInstance()->executarSql('update infra_agendamento_tarefa set comando = replace(comando,\' \',\'\')');

          BancoSEI::getInstance()->executarSql('update infra_agendamento_tarefa set sta_periodicidade_execucao=\'N\', periodicidade_complemento=\'0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55\' where comando=\'AgendamentoRN::testarAgendamento\'');

          BancoSEI::getInstance()->executarSql('UPDATE tarefa SET sin_permite_processo_fechado=\'S\' WHERE id_tarefa='.TarefaRN::$TI_CANCELAMENTO_LIBERACAO_ACESSO_EXTERNO);

          BancoSEI::getInstance()->executarSql('update contato set sin_endereco_associado=\'N\' where id_contato=id_contato_associado and sin_endereco_associado=\'S\'');

          //altera opcao do corretor ortografico de avaliacao gratuita para nativo do navegador
          BancoSEI::getInstance()->executarSql('update orgao set sta_corretor_ortografico=\'B\' where sta_corretor_ortografico=\'G\'');

          if (BancoSEI::getInstance() instanceof InfraSqlServer){
            BancoSEI::getInstance()->executarSql('update tarefa set nome = replace(nome,\'\r\n\',char(10))');
          }

          if (BancoSEI::getInstance() instanceof InfraOracle){
            BancoSEI::getInstance()->executarSql('update tarefa set nome = replace(nome,\'\r\n\',CHR(10))');
          }


          $objInfraMetaBD->adicionarColuna('protocolo','dta_inclusao',$objInfraMetaBD->tipoDataHora(),'null');
          $this->fixDataCadastroProtocolo();
          $objInfraMetaBD->alterarColuna('protocolo','dta_inclusao',$objInfraMetaBD->tipoDataHora(),'not null');
          $objInfraMetaBD->criarIndice('protocolo','i07_protocolo',array('dta_inclusao','sta_protocolo','id_unidade_geradora'));

          $objInfraMetaBD->adicionarColuna('contato','numero_passaporte',$objInfraMetaBD->tipoTextoVariavel(15),'null');
          $objInfraMetaBD->adicionarColuna('contato','id_pais_passaporte',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_contato_pais_passaporte','contato',array('id_pais_passaporte'),'pais',array('id_pais'));

          $this->fixAcessoProcessosAnexadosRestritos();

          $this->fixQuantidadeControleProcessos();

          $this->fixNumeracao();

          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\'126\',\'Alterado tipo do processo de "@TIPO_PROCESSO_ANTERIOR@" para "@TIPO_PROCESSO_ATUAL@"\',\'N\',\'S\',\'S\',\'N\',\'N\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\'128\',\'Alterado número do processo de "@PROTOCOLO_ANTERIOR@" para "@PROTOCOLO_ATUAL@"\',\'N\',\'S\',\'S\',\'N\',\'N\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\'129\',\'Alterada data de autuação do processo de "@DATA_ANTERIOR@" para "@DATA_ATUAL@"\',\'N\',\'S\',\'S\',\'N\',\'N\')');

        }catch(Exception $e){
          InfraDebug::getInstance()->setBolLigado(false);
          InfraDebug::getInstance()->setBolDebugInfra(false);
          InfraDebug::getInstance()->setBolEcho(false);
          throw new InfraException('Erro atualizando versão.', $e);
        }
      }

      public function versao_4_0_0($strVersaoAtual){
        try{

          $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
          $objInfraMetaBD->setBolValidarIdentificador(true);

          if (BancoSEI::getInstance() instanceof InfraMySql){
            $objScriptRN = new ScriptRN();
            $objScriptRN->atualizarSequencias();
          }

          InfraDebug::getInstance()->setBolDebugInfra(true);

          $objInfraMetaBD->alterarColuna('infra_agendamento_tarefa', 'periodicidade_complemento', $objInfraMetaBD->tipoTextoVariavel(200), 'null');

          if (BancoSEI::getInstance() instanceof InfraSqlServer){
            $objInfraMetaBD->criarIndice('email_sistema', 'i01_email_sistema', array('id_email_sistema_modulo'),true);
            $objInfraMetaBD->criarIndice('tarefa', 'i01_tarefa', array('id_tarefa_modulo'),true);
            BancoSEI::getInstance()->executarSql('drop table seq_notificacao');
          }

          if (BancoSEI::getInstance() instanceof InfraMySql){
            $objInfraMetaBD->criarIndice('infra_regra_auditoria_recurso','fk_inf_reg_aud_rec_inf_reg_aud',array('id_infra_regra_auditoria'));
            BancoSEI::getInstance()->executarSql('drop table seq_notificacao');
          }

          if ( BancoSEI::getInstance() instanceof InfraOracle){
            $objInfraMetaBD->criarIndice('infra_regra_auditoria_recurso','fk_inf_reg_aud_rec_inf_reg_aud',array('id_infra_regra_auditoria'));
            BancoSEI::getInstance()->executarSql('drop sequence seq_notificacao');
          }



          $this->logar('ATUALIZANDO PARAMETROS...');

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_FEDERACAO_NUMERO_PROCESSO\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_FEDERACAO_NUMERO_PROCESSO\',\'1\')');
          }

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO\',\'1\')');
          }

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_TAM_MB_CORRETOR_DESABILITADO\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_TAM_MB_CORRETOR_DESABILITADO\',\'2\')');
          }

          $rs = BancoSEI::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SEI_SINALIZACAO_PROCESSO\'');
          if ($rs[0]['total']==0) {
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SEI_SINALIZACAO_PROCESSO\',\'0\')');
          }

          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\'124\',\'Correção de encaminhamento para @ORGAO@ (@PROCESSO@)\',\'S\',\'S\',\'S\',\'N\',\'N\')');

          BancoSEI::getInstance()->executarSql('insert into email_sistema (id_email_sistema,descricao,de,para,assunto,conteudo,sin_ativo,id_email_sistema_modulo) values (10,\'Correção de encaminhamento de Ouvidoria\',\'@sigla_orgao_origem@ <no-reply@@sigla_orgao_origem_minusculas@@sufixo_email@>\',\'@nome_contato@ <@email_contato@>\',\'Contato com OUVIDORIA/@sigla_orgao_origem@ - Correção de Encaminhamento\',\'\r\nEste é um e-mail automático.\r\n\r\nA demanda abaixo, registrada na ouvidoria do órgão @sigla_orgao_origem@, deveria ter sido protocolada no órgão @sigla_orgao_destino@, motivo pelo qual foi realizada a correção de encaminhamento e o novo número do seu processo é @processo_destino@.\r\n\r\n@conteudo_formulario_ouvidoria@\r\n\r\n\',\'S\',null)');

          BancoSEI::getInstance()->executarSql('CREATE TABLE rel_usuario_marcador (
                                            id_marcador '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
                                            id_usuario '.$objInfraMetaBD->tipoNumero().' NOT NULL
                                          )');

          $objInfraMetaBD->adicionarChavePrimaria('rel_usuario_marcador','pk_rel_usuario_marcad_usuario',array('id_marcador','id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usuario_marcad_marcad','rel_usuario_marcador',array('id_marcador'),'marcador',array('id_marcador'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usuario_marcad_usuario','rel_usuario_marcador',array('id_usuario'),'usuario',array('id_usuario'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE rel_usuario_grupo_acomp (
                                            id_usuario '.$objInfraMetaBD->tipoNumero().' NOT NULL,
                                            id_grupo_acompanhamento '.$objInfraMetaBD->tipoNumero().'  NOT NULL
                                          )');

          $objInfraMetaBD->adicionarChavePrimaria('rel_usuario_grupo_acomp','pk_rel_usuario_grupo_acomp',array('id_usuario','id_grupo_acompanhamento'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_grp_acomp_usuario','rel_usuario_grupo_acomp',array('id_usuario'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_grp_acomp_grp_acomp','rel_usuario_grupo_acomp',array('id_grupo_acompanhamento'),'grupo_acompanhamento',array('id_grupo_acompanhamento'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE rel_usuario_usuario_unidade (
                                            id_usuario '.$objInfraMetaBD->tipoNumero().' NOT NULL,
                                            id_usuario_atribuicao '.$objInfraMetaBD->tipoNumero().' NOT NULL,
                                            id_unidade '.$objInfraMetaBD->tipoNumero().'  NOT NULL
                                          )');

          $objInfraMetaBD->adicionarChavePrimaria('rel_usuario_usuario_unidade','pk_rel_usuario_usuario_unidade',array('id_usuario','id_usuario_atribuicao','id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_usu_uni_usuario','rel_usuario_usuario_unidade',array('id_usuario'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_usu_uni_unidade','rel_usuario_usuario_unidade',array('id_unidade'),'unidade',array('id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_usu_uni_usu_atrib','rel_usuario_usuario_unidade',array('id_usuario_atribuicao'),'usuario',array('id_usuario'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE rel_usuario_tipo_proced (
                                            id_usuario '.$objInfraMetaBD->tipoNumero().' NOT NULL,
                                            id_tipo_procedimento '.$objInfraMetaBD->tipoNumero().' NOT NULL,
                                            id_unidade '.$objInfraMetaBD->tipoNumero().'  NOT NULL
                                          )');

          $objInfraMetaBD->adicionarChavePrimaria('rel_usuario_tipo_proced','pk_rel_usuario_tipo_proced',array('id_usuario','id_tipo_procedimento','id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_tipo_proced_usu','rel_usuario_tipo_proced',array('id_usuario'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_tipo_proced_unidade','rel_usuario_tipo_proced',array('id_unidade'),'unidade',array('id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_tipo_proced_tipo_pr','rel_usuario_tipo_proced',array('id_tipo_procedimento'),'tipo_procedimento',array('id_tipo_procedimento'));

          $this->logar('ATUALIZANDO MARCADORES...');
          $objInfraMetaBD->adicionarColuna('andamento_marcador','sta_operacao',$objInfraMetaBD->tipoTextoFixo(1),'null');
          $objInfraMetaBD->adicionarColuna('andamento_marcador','sin_ativo',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update andamento_marcador set sin_ativo=\'S\'');
          $this->fixMarcadores();
          $objInfraMetaBD->alterarColuna('andamento_marcador','sta_operacao',$objInfraMetaBD->tipoTextoFixo(1),'not null');
          $objInfraMetaBD->alterarColuna('andamento_marcador','sin_ativo',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $this->logar('ATUALIZANDO ACOMPANHAMENTOS ESPECIAIS...');
          $objInfraMetaBD->adicionarColuna('acompanhamento','id_usuario',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarColuna('acompanhamento','dth_alteracao',$objInfraMetaBD->tipoDataHora(),'null');
          BancoSEI::getInstance()->executarSql('update acompanhamento set id_usuario=id_usuario_gerador');
          BancoSEI::getInstance()->executarSql('update acompanhamento set dth_alteracao=dth_geracao');
          $objInfraMetaBD->alterarColuna('acompanhamento','id_usuario',$objInfraMetaBD->tipoNumero(),'not null');
          $objInfraMetaBD->alterarColuna('acompanhamento','dth_alteracao',$objInfraMetaBD->tipoDataHora(),'not null');

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acompanhamento_usuario','acompanhamento',array('id_usuario'),'usuario',array('id_usuario'));
          $objInfraMetaBD->excluirChaveEstrangeira('acompanhamento','fk_acompanhamento_usuario_ger');

          if (BancoSEI::getInstance() instanceof InfraSqlServer){
            $objInfraMetaBD->excluirIndice('acompanhamento','fk_acompanhamento_usuario_ger');
          }

          $objInfraMetaBD->excluirColuna('acompanhamento','id_usuario_gerador');
          $objInfraMetaBD->excluirColuna('acompanhamento','dth_geracao');

          $objInfraMetaBD->adicionarColuna('acompanhamento','idx_acompanhamento',$objInfraMetaBD->tipoTextoVariavel(4000),'null');

          $objInfraMetaBD->alterarColuna('marcador','sta_icone',$objInfraMetaBD->tipoTextoVariavel(2),'not null');

          $objInfraMetaBD->adicionarChavePrimaria('controle_unidade','pk_controle_unidade', array('id_controle_unidade'));

          BancoSEI::getInstance()->executarSql('delete from infra_dado_usuario where id_usuario in (select id_usuario from usuario where sin_ativo=\'N\')');


          $this->logar('ATUALIZANDO EXTENSOES DE ARQUIVO...');
          $objInfraMetaBD->adicionarColuna('arquivo_extensao','sin_interface',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update arquivo_extensao set sin_interface=\'S\'');
          $objInfraMetaBD->alterarColuna('arquivo_extensao','sin_interface',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $objInfraMetaBD->adicionarColuna('arquivo_extensao','sin_servico',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update arquivo_extensao set sin_servico=\'S\'');
          $objInfraMetaBD->alterarColuna('arquivo_extensao','sin_servico',$objInfraMetaBD->tipoTextoFixo(1),'not null');


          $objInfraMetaBD->alterarColuna("uf","sigla",$objInfraMetaBD->tipoTextoFixo(2),'null');

          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\'125\',\'Cancelado arquivamento do documento @DOCUMENTO@ no localizador @LOCALIZADOR@\',\'N\',\'S\',\'N\',\'S\',\'S\')');
          $objInfraMetaBD->adicionarColuna('arquivamento','id_atividade_cancelamento',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_arquiv_ativ_canc','arquivamento',array('id_atividade_cancelamento'),'atividade',array('id_atividade'));


          $objInfraMetaBD->adicionarColuna('acesso_externo','sin_inclusao',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update acesso_externo set sin_inclusao = \'N\'');
          $objInfraMetaBD->alterarColuna('acesso_externo','sin_inclusao',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $objInfraMetaBD->criarIndice('acesso_externo', 'i05_acesso_externo', array('sta_tipo', 'sin_inclusao'));

          $objInfraMetaBD->adicionarColuna('serie','sin_usuario_externo',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql("update serie set sin_usuario_externo = 'N'");
          $objInfraMetaBD->alterarColuna('serie','sin_usuario_externo',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          BancoSEI::getInstance()->executarSql('CREATE TABLE orgao_historico
            (
              id_orgao_historico   '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              id_orgao             '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              sigla                '.$objInfraMetaBD->tipoTextoVariavel(30).'  NOT NULL ,
              descricao            '.$objInfraMetaBD->tipoTextoVariavel(100).'  NOT NULL ,
              dta_inicio           '.$objInfraMetaBD->tipoDataHora().'  NOT NULL ,
              dta_fim              '.$objInfraMetaBD->tipoDataHora().'  NULL
            )');
          $objInfraMetaBD->adicionarChavePrimaria('orgao_historico','pk_orgao_historico',array('id_orgao_historico'));
          $objInfraMetaBD->criarIndice('orgao_historico', 'i02_orgao_historico', array('dta_inicio', 'dta_fim', 'id_orgao'), true);

          BancoSEI::getInstance()->executarSql('CREATE TABLE unidade_historico
          (
            id_unidade_historico '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_unidade           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_orgao             '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            sigla                '.$objInfraMetaBD->tipoTextoVariavel(30).'  NOT NULL ,
            descricao            '.$objInfraMetaBD->tipoTextoVariavel(250).'  NOT NULL ,
            dta_inicio           '.$objInfraMetaBD->tipoDataHora().'  NOT NULL ,
            dta_fim              '.$objInfraMetaBD->tipoDataHora().'  NULL
          )');
          $objInfraMetaBD->adicionarChavePrimaria('unidade_historico','pk_unidade_historico',array('id_unidade_historico'));
          $objInfraMetaBD->criarIndice('unidade_historico', 'i02_unidade_historico', array('dta_inicio', 'dta_fim', 'id_unidade'), true);

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_orgao_historico_orgao','orgao_historico',array('id_orgao'),'orgao',array('id_orgao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_unidade_historico_unidade','unidade_historico',array('id_unidade'),'unidade',array('id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_unidade_historico_orgao','unidade_historico',array('id_orgao'),'orgao',array('id_orgao'));

          BancoSEI::getInstance()->criarSequencialNativa('seq_orgao_historico',1);
          BancoSEI::getInstance()->criarSequencialNativa('seq_unidade_historico',1);

          $this->fixHistoricoUnidadeOrgao();

          $objInfraMetaBD->alterarColuna('contato','email',$objInfraMetaBD->tipoTextoVariavel(100),'null');

          BancoSEI::getInstance()->executarSql('
          CREATE TABLE titulo
          (
            id_titulo            '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            expressao            '.$objInfraMetaBD->tipoTextoVariavel(100).'  NOT NULL ,
            abreviatura          '.$objInfraMetaBD->tipoTextoVariavel(20).'  NULL ,
            sin_ativo            '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL
          )');
          $objInfraMetaBD->adicionarChavePrimaria('titulo','pk_titulo',array('id_titulo'));
          BancoSEI::getInstance()->criarSequencialNativa('seq_titulo',1);

          $objInfraMetaBD->adicionarColuna('contato','id_titulo',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_contato_titulo','contato',array('id_titulo'),'titulo',array('id_titulo'));

          $objInfraMetaBD->adicionarColuna('cargo','id_titulo',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_cargo_titulo','cargo',array('id_titulo'),'titulo',array('id_titulo'));

          $objInfraMetaBD->adicionarColuna('contato','telefone_comercial',$objInfraMetaBD->tipoTextoVariavel(50),'null');
          BancoSEI::getInstance()->executarSql('update contato set telefone_comercial=telefone_fixo');
          $objInfraMetaBD->excluirColuna('contato','telefone_fixo');
          $objInfraMetaBD->adicionarColuna('contato','telefone_residencial',$objInfraMetaBD->tipoTextoVariavel(50),'null');

          $objInfraMetaBD->adicionarColuna('contato','conjuge',$objInfraMetaBD->tipoTextoVariavel(100),'null');
          $objInfraMetaBD->adicionarColuna('contato','funcao',$objInfraMetaBD->tipoTextoVariavel(100),'null');

          $this->fixTelefonesContatosOuvidoria();

          $objInfraMetaBD->adicionarColuna('contato','nome_registro_civil',$objInfraMetaBD->tipoTextoVariavel(250),'null');
          $objInfraMetaBD->adicionarColuna('contato','nome_social',$objInfraMetaBD->tipoTextoVariavel(250),'null');
          BancoSEI::getInstance()->executarSql('update contato set nome_registro_civil=nome where sta_natureza=\'F\'');

          $objInfraMetaBD->adicionarColuna('usuario','nome_registro_civil',$objInfraMetaBD->tipoTextoVariavel(100),'null');
          BancoSEI::getInstance()->executarSql('update usuario set nome_registro_civil=nome');
          $objInfraMetaBD->alterarColuna('usuario','nome_registro_civil',$objInfraMetaBD->tipoTextoVariavel(100),'not null');
          $objInfraMetaBD->adicionarColuna('usuario','nome_social',$objInfraMetaBD->tipoTextoVariavel(100),'null');

          $objInfraMetaBD->criarIndice('usuario','i02_usuario',array('id_contato','sta_tipo'));

          $objInfraMetaBD->criarIndice('atividade','i10_atividade',array('dth_abertura','id_tarefa'));

          $objInfraMetaBD->alterarColuna('acompanhamento','observacao',$objInfraMetaBD->tipoTextoVariavel(500),'null');

          $objInfraMetaBD->adicionarColuna('acesso_externo','dth_visualizacao',$objInfraMetaBD->tipoDataHora(),'null');

           BancoSEI::getInstance()->executarSql('
          CREATE TABLE controle_prazo
          (
            id_controle_prazo    '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_protocolo         '.$objInfraMetaBD->tipoNumeroGrande().'  NOT NULL ,
            id_unidade           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_usuario           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            dta_prazo            '.$objInfraMetaBD->tipoDataHora().'  NOT NULL
          )');
          $objInfraMetaBD->adicionarChavePrimaria('controle_prazo','pkcontrole_prazo',array('id_controle_prazo'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_controle_prazo_protocolo','controle_prazo',array('id_protocolo'),'protocolo',array('id_protocolo'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_controle_prazo_unidade','controle_prazo',array('id_unidade'),'unidade',array('id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_controle_prazo_usuario','controle_prazo',array('id_usuario'),'usuario',array('id_usuario'));
          BancoSEI::getInstance()->criarSequencialNativa('seq_controle_prazo',1);

          $objInfraMetaBD->adicionarColuna('controle_prazo','dta_conclusao',$objInfraMetaBD->tipoDataHora(),'null');
          $objInfraMetaBD->criarIndice('controle_prazo','i01_controle_prazo',array('id_unidade','dta_prazo','dta_conclusao'));
          $objInfraMetaBD->criarIndice('controle_prazo','i02_controle_prazo',array('id_unidade','dta_prazo'));
          $objInfraMetaBD->criarIndice('controle_prazo','i03_controle_prazo',array('id_unidade','dta_conclusao'));

          $objInfraMetaBD->adicionarColuna('rel_bloco_protocolo','idx_rel_bloco_protocolo',$objInfraMetaBD->tipoTextoVariavel(4000),'null');

          BancoSEI::getInstance()->executarSql('
          CREATE TABLE comentario
          (
            id_comentario    '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_procedimento      '.$objInfraMetaBD->tipoNumeroGrande().'  NOT NULL ,
            id_rel_protocolo_protocolo    '.$objInfraMetaBD->tipoNumeroGrande().'  NULL ,
            id_unidade           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_usuario           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            descricao            '.$objInfraMetaBD->tipoTextoVariavel(4000).'  NOT NULL,
            dth_comentario            '.$objInfraMetaBD->tipoDataHora().'  NOT NULL
          )');
          $objInfraMetaBD->adicionarChavePrimaria('comentario','pk_comentario',array('id_comentario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_comentario_procedimento','comentario',array('id_procedimento'),'procedimento',array('id_procedimento'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_comentario_rel_prot_prot','comentario',array('id_rel_protocolo_protocolo'),'rel_protocolo_protocolo',array('id_rel_protocolo_protocolo'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_comentario_unidade','comentario',array('id_unidade'),'unidade',array('id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_comentario_usuario','comentario',array('id_usuario'),'usuario',array('id_usuario'));

          $objInfraMetaBD->criarIndice('comentario', 'i01_comentario', array('id_procedimento', 'id_rel_protocolo_protocolo'));

          BancoSEI::getInstance()->criarSequencialNativa('seq_comentario',1);

          $objInfraMetaBD->excluirChaveEstrangeira('retorno_programado','fk_retorno_programado_unidade');

          $objInfraMetaBD->adicionarColuna('retorno_programado', 'id_unidade_envio' , $objInfraMetaBD->tipoNumero(), 'null');
          BancoSEI::getInstance()->executarSql('update retorno_programado set id_unidade_envio=id_unidade');
          $objInfraMetaBD->alterarColuna('retorno_programado', 'id_unidade_envio' , $objInfraMetaBD->tipoNumero(), 'not null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_ret_programado_uni_envio', 'retorno_programado', array('id_unidade_envio'), 'unidade', array('id_unidade'));

          if (BancoSEI::getInstance() instanceof InfraSqlServer){
            $objInfraMetaBD->excluirIndice('retorno_programado','fk_retorno_programado_unidade');
            $objInfraMetaBD->excluirIndice('retorno_programado','i04_retorno_programado');
          }

          $objInfraMetaBD->excluirColuna('retorno_programado', 'id_unidade');

          $objInfraMetaBD->adicionarColuna('retorno_programado', 'id_unidade_retorno' , $objInfraMetaBD->tipoNumero(), 'null');
          BancoSEI::getInstance()->executarSql('update retorno_programado set id_unidade_retorno = (select atividade.id_unidade from atividade where atividade.id_atividade=retorno_programado.id_atividade_envio)');
          $objInfraMetaBD->alterarColuna('retorno_programado', 'id_unidade_retorno' , $objInfraMetaBD->tipoNumero(), 'not null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_ret_programado_uni_retorno', 'retorno_programado', array('id_unidade_retorno'), 'unidade', array('id_unidade'));

          $objInfraMetaBD->adicionarColuna('retorno_programado', 'id_protocolo' , $objInfraMetaBD->tipoNumeroGrande(), 'null');
          BancoSEI::getInstance()->executarSql('update retorno_programado set id_protocolo = (select atividade.id_protocolo from atividade where atividade.id_atividade=retorno_programado.id_atividade_envio)');
          $objInfraMetaBD->alterarColuna('retorno_programado', 'id_protocolo' , $objInfraMetaBD->tipoNumeroGrande(), 'not null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_ret_programado_protocolo', 'retorno_programado', array('id_protocolo'), 'protocolo', array('id_protocolo'));

          $objInfraMetaBD->criarIndice('retorno_programado','i07_retorno_programado',array('id_unidade_envio','id_unidade_retorno','id_protocolo','id_atividade_retorno'));
          $objInfraMetaBD->criarIndice('retorno_programado','i08_retorno_programado',array('id_unidade_envio','id_unidade_retorno','dta_programada'));
          $objInfraMetaBD->criarIndice('retorno_programado','i09_retorno_programado',array('id_unidade_envio','id_unidade_retorno','id_protocolo'));

          BancoSEI::getInstance()->executarSql('
          CREATE TABLE categoria
          (
            id_categoria         '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            nome                 '.$objInfraMetaBD->tipoTextoVariavel(100).' NOT  NULL ,
            sin_ativo            '.$objInfraMetaBD->tipoTextoFixo(1).' NOT  NULL
          )'
          );
          $objInfraMetaBD->adicionarChavePrimaria('categoria','pk_categoria',array('id_categoria'));
          $objInfraMetaBD->adicionarColuna('contato','id_categoria',$objInfraMetaBD->tipoNumero(),'null');


          $objInfraMetaBD->adicionarChaveEstrangeira('fk_contato_categoria','contato',array('id_categoria'),'categoria',array('id_categoria'));
          BancoSEI::getInstance()->criarSequencialNativa('seq_categoria',1);

          $objInfraMetaBD->criarIndice('acesso','i03_acesso',array('id_protocolo','id_unidade','id_usuario'));

          BancoSEI::getInstance()->executarSql('drop table contexto');

          BancoSEI::getInstance()->executarSql('CREATE TABLE lembrete (
             id_lembrete '.$objInfraMetaBD->tipoNumero().' NOT NULL,
             id_usuario '.$objInfraMetaBD->tipoNumero().' NOT NULL,
             posicao_x '.$objInfraMetaBD->tipoNumero().' NOT NULL,
             posicao_y '.$objInfraMetaBD->tipoNumero().' NOT NULL,
             largura '.$objInfraMetaBD->tipoNumero().' NOT NULL,
             altura '.$objInfraMetaBD->tipoNumero().' NOT NULL,
             cor '.$objInfraMetaBD->tipoTextoFixo(7).' NOT NULL,
             cor_texto '.$objInfraMetaBD->tipoTextoFixo(7).' NOT NULL,
             dth_lembrete '.$objInfraMetaBD->tipoDataHora().' NOT NULL,
             conteudo '.$objInfraMetaBD->tipoTextoGrande().' NOT NULL,
             sin_ativo '.$objInfraMetaBD->tipoTextoFixo(1).' NOT NULL
          )');
          $objInfraMetaBD->adicionarChavePrimaria('lembrete','pk_lembrete',array('id_lembrete'));
          BancoSEI::getInstance()->criarSequencialNativa('seq_lembrete',1);
          $objInfraMetaBD->criarIndice('lembrete','i01_lembrete',array('id_usuario'));

          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\'127\',\'Renovada credencial do usuário @USUARIO@"\',\'S\',\'S\',\'N\',\'N\',\'N\')');

          $objInfraMetaBD->alterarColuna('protocolo_modelo','descricao',$objInfraMetaBD->tipoTextoVariavel(1000),'null');
          $objInfraMetaBD->adicionarColuna('protocolo_modelo','dth_alteracao',$objInfraMetaBD->tipoDataHora(),'null');
          BancoSEI::getInstance()->executarSql('update protocolo_modelo set dth_alteracao=dth_geracao');
          $objInfraMetaBD->alterarColuna('protocolo_modelo','dth_alteracao',$objInfraMetaBD->tipoDataHora(),'not null');
          $objInfraMetaBD->excluirColuna('protocolo_modelo','dth_geracao');
          $objInfraMetaBD->adicionarColuna('protocolo_modelo','idx_protocolo_modelo',$objInfraMetaBD->tipoTextoVariavel(4000),'null');


          $objInfraMetaBD->adicionarColuna('documento','nome_arvore',$objInfraMetaBD->tipoTextoVariavel(50),'null');
          BancoSEI::getInstance()->executarSql('update documento set nome_arvore=numero where sta_documento=\''.DocumentoRN::$TD_EXTERNO.'\' and numero is not null');
          BancoSEI::getInstance()->executarSql('update documento set numero=null where sta_documento=\''.DocumentoRN::$TD_EXTERNO.'\' and nome_arvore is not null');

          BancoSEI::getInstance()->executarSql('update tarefa set nome = \'Disponibilizado acesso externo para @DESTINATARIO_NOME@ (@DESTINATARIO_EMAIL@)@VALIDADE@.@VISUALIZACAO@'.$objInfraMetaBD->novaLinha().'@MOTIVO@\' where id_tarefa = 50');
          BancoSEI::getInstance()->executarSql('update tarefa set nome = \'Disponibilizado acesso externo para @DESTINATARIO_NOME@ (@DESTINATARIO_EMAIL@)@VALIDADE@.@VISUALIZACAO@'.$objInfraMetaBD->novaLinha().'@MOTIVO@'.$objInfraMetaBD->novaLinha().'(cancelada por @USUARIO@ em @DATA_HORA@)\' where id_tarefa = 89');
          $this->fixTarefasPrazoAcessoExterno();

          BancoSEI::getInstance()->executarSql('update tarefa set nome = \'Liberada assinatura externa para o usuário @USUARIO_EXTERNO_NOME@ (@USUARIO_EXTERNO_SIGLA@) no documento @DOCUMENTO@@VALIDADE@.@VISUALIZACAO@\' where id_tarefa = '.TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA);
          BancoSEI::getInstance()->executarSql('update tarefa set nome = \'Liberada assinatura externa para o usuário @USUARIO_EXTERNO_NOME@ (@USUARIO_EXTERNO_SIGLA@) no documento @DOCUMENTO@@VALIDADE@.@VISUALIZACAO@'.$objInfraMetaBD->novaLinha().'(cancelada por @USUARIO@ em @DATA_HORA@)\' where id_tarefa = '.TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA);

          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','id_usuario_atribuicao',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','id_usuario_revisao',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','id_usuario_prioridade',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','id_usuario_comentario',$objInfraMetaBD->tipoNumero(),'null');

          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','sin_revisao',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update rel_bloco_unidade set sin_revisao=\'N\'');
          $objInfraMetaBD->alterarColuna('rel_bloco_unidade','sin_revisao',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','sin_prioridade',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update rel_bloco_unidade set sin_prioridade=\'N\'');
          $objInfraMetaBD->alterarColuna('rel_bloco_unidade','sin_prioridade',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','sin_comentario',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update rel_bloco_unidade set sin_comentario=\'N\'');
          $objInfraMetaBD->alterarColuna('rel_bloco_unidade','sin_comentario',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','texto_comentario',$objInfraMetaBD->tipoTextoVariavel(4000),'null');

          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','dth_revisao',$objInfraMetaBD->tipoDataHora(),'null');
          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','dth_prioridade',$objInfraMetaBD->tipoDataHora(),'null');
          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade','dth_comentario',$objInfraMetaBD->tipoDataHora(),'null');

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_blo_uni_usu_atribuicao','rel_bloco_unidade',array('id_usuario_atribuicao'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_blo_uni_usu_revisao','rel_bloco_unidade',array('id_usuario_revisao'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_blo_uni_usu_prioridade','rel_bloco_unidade',array('id_usuario_prioridade'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_blo_uni_usu_comentario','rel_bloco_unidade',array('id_usuario_comentario'),'usuario',array('id_usuario'));

          $objInfraMetaBD->criarIndice('rel_bloco_unidade','i05_rel_bloco_unidade', array('id_bloco', 'id_unidade', 'id_usuario_atribuicao'));
          $objInfraMetaBD->criarIndice('rel_bloco_unidade','i06_rel_bloco_unidade', array('id_bloco', 'id_unidade', 'sin_prioridade', 'sin_revisao', 'sin_comentario'));
          $objInfraMetaBD->criarIndice('rel_bloco_unidade','i07_rel_bloco_unidade', array('id_bloco', 'id_unidade', 'id_usuario_atribuicao', 'sin_prioridade', 'sin_revisao', 'sin_comentario'));

             BancoSEI::getInstance()->executarSql('CREATE TABLE rel_acesso_ext_serie
                  (
                    id_acesso_externo    '.$objInfraMetaBD->tipoNumero().'   NOT NULL ,
                    id_serie             '.$objInfraMetaBD->tipoNumero().'   NOT NULL
                  )
                ');
          $objInfraMetaBD->adicionarChavePrimaria('rel_acesso_ext_serie','pk_rel_acesso_ext_serie',array('id_acesso_externo','id_serie'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_aces_ext_serie_aces_ext','rel_acesso_ext_serie',array('id_acesso_externo'),'acesso_externo',array('id_acesso_externo'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_aces_ext_serie_serie','rel_acesso_ext_serie',array('id_serie'),'serie',array('id_serie'));

        $objInfraMetaBD->alterarColuna('grupo_acompanhamento','nome',$objInfraMetaBD->tipoTextoVariavel(100),'not null');

          BancoSEI::getInstance()->executarSql('CREATE TABLE grupo_bloco (
              id_grupo_bloco       '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              id_unidade           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              nome                 '.$objInfraMetaBD->tipoTextoVariavel(100).'  NOT NULL,
              sin_ativo  '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL
          )
          ');
          $objInfraMetaBD->adicionarChavePrimaria('grupo_bloco','pk_grupo_bloco',array('id_grupo_bloco'));
          BancoSEI::getInstance()->criarSequencialNativa('seq_grupo_bloco',1);
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_grupo_bloco_unidade','grupo_bloco',array('id_unidade'),'unidade',array('id_unidade'));

          $objInfraMetaBD->adicionarColuna('rel_bloco_unidade', 'id_grupo_bloco', $objInfraMetaBD->tipoNumero(), 'null');

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_blo_uni_grupo_bloco','rel_bloco_unidade',array('id_grupo_bloco'),'grupo_bloco',array('id_grupo_bloco'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE rel_usuario_grupo_bloco (
            id_grupo_bloco       '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_usuario           '.$objInfraMetaBD->tipoNumero().'  NOT NULL)
          ');
          $objInfraMetaBD->adicionarChavePrimaria('rel_usuario_grupo_bloco','pk_rel_usuario_grupo_bloco',array('id_grupo_bloco','id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_grupo_bloco_usuario','rel_usuario_grupo_bloco',array('id_usuario'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_usu_grupo_bloco_grp_blo','rel_usuario_grupo_bloco',array('id_grupo_bloco'),'grupo_bloco',array('id_grupo_bloco'));

          $this->fixBlocosUnidadeGeradora();

          if (BancoSEI::getInstance() instanceof InfraOracle){
            BancoSEI::getInstance()->executarSql('ALTER TABLE protocolo DROP UNIQUE (protocolo_formatado_pesquisa)');
          }

          $objInfraMetaBD->excluirIndice('protocolo','ak3_protocolo');
          $objInfraMetaBD->excluirIndice('protocolo','ak4_protocolo');

          $objInfraMetaBD->alterarColuna('protocolo','protocolo_formatado_pesq_inv',$objInfraMetaBD->tipoTextoVariavel(50),'not null');

          $objInfraMetaBD->criarIndice('protocolo','i15_protocolo',array('protocolo_formatado_pesquisa'));
          $objInfraMetaBD->criarIndice('protocolo','i16_protocolo',array('protocolo_formatado_pesq_inv'));

          if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql('alter table servico rename column servidor to servidor_old');
            $objInfraMetaBD->adicionarColuna('servico', 'servidor', $objInfraMetaBD->tipoTextoGrande(), 'null');
            BancoSEI::getInstance()->executarSql('UPDATE servico SET servidor = servidor_old');
            $objInfraMetaBD->excluirColuna('servico','servidor_old');
          }else {
            $objInfraMetaBD->alterarColuna('servico', 'servidor', $objInfraMetaBD->tipoTextoGrande(), 'null');
          }

          $objInfraMetaBD->adicionarColuna('servico', 'sin_chave_acesso', $objInfraMetaBD->tipoTextoFixo(1), 'null');
          BancoSEI::getInstance()->executarSql('update servico set sin_chave_acesso=\'N\'');
          $objInfraMetaBD->alterarColuna('servico', 'sin_chave_acesso', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

          $objInfraMetaBD->adicionarColuna('servico', 'sin_servidor', $objInfraMetaBD->tipoTextoFixo(1), 'null');
          BancoSEI::getInstance()->executarSql('update servico set sin_servidor=\'S\'');
          $objInfraMetaBD->alterarColuna('servico', 'sin_servidor', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

          $objInfraMetaBD->adicionarColuna('servico', 'crc', $objInfraMetaBD->tipoTextoFixo(8), 'null');
          $objInfraMetaBD->adicionarColuna('servico', 'chave_acesso', $objInfraMetaBD->tipoTextoFixo(60), 'null');
          $objInfraMetaBD->criarIndice('servico', 'i02_servico', array('crc'));

          $objInfraMetaBD->criarIndice('assinatura', 'i02_assinatura', array('id_documento','id_atividade'));
          $objInfraMetaBD->alterarColuna('assinatura', 'nome', $objInfraMetaBD->tipoTextoVariavel(500), 'not null');


          $objInfraMetaBD->adicionarColuna('documento', 'sin_arquivamento', $objInfraMetaBD->tipoTextoFixo(1), 'null');
          BancoSEI::getInstance()->executarSql('update documento set sin_arquivamento=\'N\'');
          BancoSEI::getInstance()->executarSql('update documento set sin_arquivamento=\'S\' where sta_documento=\'X\' and exists (select arquivamento.id_protocolo from arquivamento where arquivamento.id_protocolo=documento.id_documento)');
          $objInfraMetaBD->alterarColuna('documento', 'sin_arquivamento', $objInfraMetaBD->tipoTextoFixo(1), 'not null');
          $objInfraMetaBD->criarIndice('documento','i06_documento',array('id_documento','sin_arquivamento'));

          //FEDERACAO - INICIO
          $this->configurarUsuarioInternet();
          $this->cadastrarTipoProcessoFederacao();

          BancoSEI::getInstance()->executarSql('CREATE TABLE instalacao_federacao (
            id_instalacao_federacao         '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            cnpj                '.$objInfraMetaBD->tipoNumeroGrande().' NOT NULL ,
            sigla                '.$objInfraMetaBD->tipoTextoVariavel(30).'  NOT NULL ,
            descricao            '.$objInfraMetaBD->tipoTextoVariavel(100).'  NOT NULL ,
            endereco             '.$objInfraMetaBD->tipoTextoVariavel(250).' NOT NULL ,
            chave_publica_local        '.$objInfraMetaBD->tipoTextoVariavel(1024).'  NULL ,
            chave_publica_remota        '.$objInfraMetaBD->tipoTextoVariavel(1024).'  NULL ,
            chave_privada        '.$objInfraMetaBD->tipoTextoVariavel(1024).'  NULL ,
            sta_tipo           '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL ,
            sta_estado           '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL,
            sta_agendamento           '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL,
            sin_ativo            '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL
          )');

          $objInfraMetaBD->adicionarChavePrimaria('instalacao_federacao','pk_instalacao_federacao',array('id_instalacao_federacao'));
          $objInfraMetaBD->criarIndice('instalacao_federacao','ak_cnjp', array('cnpj'), true);

          BancoSEI::getInstance()->executarSql('CREATE TABLE tarefa_instalacao  (
                id_tarefa_instalacao '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
                nome                 '.$objInfraMetaBD->tipoTextoVariavel(250).'  NOT NULL
          )');
          $objInfraMetaBD->adicionarChavePrimaria('tarefa_instalacao','pk_tarefa_instalacao',array('id_tarefa_instalacao'));


          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'1\',\'Recebida solicitação de registro de @INSTITUICAO@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'2\',\'Enviada solicitação de registro para @INSTITUICAO@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'3\',\'Recebida de @INSTITUICAO@ solicitação de replicação de registro de @INSTITUICAO_REPLICADA@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'4\',\'Enviada para @INSTITUICAO@ solicitação de replicação de registro de @INSTITUICAO_REPLICADA@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'5\',\'Liberação enviada para @INSTITUICAO@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'6\',\'Bloqueio enviado para @INSTITUICAO@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'7\',\'Desativada\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'8\',\'Reativada\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'9\',\'Alterado endereço para @ENDERECO@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'10\',\'Liberação recebida de @INSTITUICAO@\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa_instalacao (id_tarefa_instalacao,nome) values (\'11\',\'Bloqueio recebido de @INSTITUICAO@\')');

          BancoSEI::getInstance()->executarSql('CREATE TABLE andamento_instalacao (
              id_andamento_instalacao '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              id_instalacao_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              id_tarefa_instalacao   '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              id_unidade  '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              id_usuario   '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              sta_estado  '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL ,              
              dth_estado   '.$objInfraMetaBD->tipoDataHora().'  NOT NULL
            )');

          $objInfraMetaBD->adicionarChavePrimaria('andamento_instalacao','pk_andamento_instalacao',array('id_andamento_instalacao'));
          BancoSEI::getInstance()->criarSequencialNativa('seq_andamento_instalacao',1);

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_andamento_inst_inst_fed','andamento_instalacao',array('id_instalacao_federacao'),'instalacao_federacao',array('id_instalacao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_andamento_inst_unidade','andamento_instalacao',array('id_unidade'),'unidade',array('id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_andamento_inst_usuario','andamento_instalacao',array('id_usuario'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_and_inst_tarefa_inst','andamento_instalacao',array('id_tarefa_instalacao'),'tarefa_instalacao',array('id_tarefa_instalacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE atributo_instalacao  (
                id_atributo_instalacao '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
                id_andamento_instalacao '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
                nome                 '.$objInfraMetaBD->tipoTextoVariavel(50).'  NOT NULL ,
                valor                '.$objInfraMetaBD->tipoTextoVariavel(4000).'  NOT NULL ,
                id_origem            '.$objInfraMetaBD->tipoTextoVariavel(50).'  NULL
          )');
          BancoSEI::getInstance()->criarSequencialNativa('seq_atributo_instalacao',1);
          $objInfraMetaBD->adicionarChavePrimaria('atributo_instalacao','pk_atributo_instalacao',array('id_atributo_instalacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_atributo_inst_andam_inst','atributo_instalacao',array('id_andamento_instalacao'),'andamento_instalacao',array('id_andamento_instalacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE orgao_federacao (
              id_orgao_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              id_instalacao_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              sigla                '.$objInfraMetaBD->tipoTextoVariavel(30).'  NOT NULL ,
              descricao            '.$objInfraMetaBD->tipoTextoVariavel(250).'  NOT NULL
            )');

          $objInfraMetaBD->adicionarChavePrimaria('orgao_federacao','pk_orgao_federacao',array('id_orgao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_orgao_fed_instalacao_fed','orgao_federacao',array('id_instalacao_federacao'),'instalacao_federacao',array('id_instalacao_federacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE unidade_federacao (
              id_unidade_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              id_instalacao_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              sigla                '.$objInfraMetaBD->tipoTextoVariavel(30).'  NOT NULL ,
              descricao            '.$objInfraMetaBD->tipoTextoVariavel(250).'  NOT NULL
            )');

          $objInfraMetaBD->adicionarChavePrimaria('unidade_federacao','pk_unidade_federacao',array('id_unidade_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_unidade_fed_instalacao_fed','unidade_federacao',array('id_instalacao_federacao'),'instalacao_federacao',array('id_instalacao_federacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE usuario_federacao (
              id_usuario_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              id_instalacao_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              sigla                '.$objInfraMetaBD->tipoTextoVariavel(100).'  NOT NULL ,
              nome            '.$objInfraMetaBD->tipoTextoVariavel(100).'  NOT NULL
            )');

          $objInfraMetaBD->adicionarChavePrimaria('usuario_federacao','pk_usuario_federacao',array('id_usuario_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_usuario_fed_instalacao_fed','usuario_federacao',array('id_instalacao_federacao'),'instalacao_federacao',array('id_instalacao_federacao'));


          BancoSEI::getInstance()->executarSql('CREATE TABLE protocolo_federacao
          (
              id_protocolo_federacao         '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
              id_instalacao_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).' NOT NULL ,
              protocolo_formatado  '.$objInfraMetaBD->tipoTextoVariavel(50).'  NOT NULL,
              protocolo_formatado_pesquisa  '.$objInfraMetaBD->tipoTextoVariavel(50).'  NOT NULL,
              protocolo_formatado_pesq_inv  '.$objInfraMetaBD->tipoTextoVariavel(50).'  NOT NULL
          )');

          $objInfraMetaBD->adicionarChavePrimaria('protocolo_federacao','pk_protocolo_federacao',array('id_protocolo_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_protocolo_fed_inst_fed','protocolo_federacao',array('id_instalacao_federacao'),'instalacao_federacao',array('id_instalacao_federacao'));

          $objInfraMetaBD->criarIndice('protocolo_federacao','i01_protocolo_federacao', array('protocolo_formatado'));
          $objInfraMetaBD->criarIndice('protocolo_federacao','i02_protocolo_federacao', array('protocolo_formatado_pesquisa'));
          $objInfraMetaBD->criarIndice('protocolo_federacao','i03_protocolo_federacao', array('protocolo_formatado_pesq_inv'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE acesso_federacao (
            id_acesso_federacao  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_instalacao_federacao_rem  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_orgao_federacao_rem  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_unidade_federacao_rem  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_usuario_federacao_rem  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NULL ,
            id_instalacao_federacao_dest  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_orgao_federacao_dest  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_unidade_federacao_dest  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_usuario_federacao_dest  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NULL ,
            id_procedimento_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).' NOT NULL ,
            id_documento_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).' NULL ,
            dth_liberacao        '.$objInfraMetaBD->tipoDataHora().'  NOT NULL,
            motivo_liberacao     '.$objInfraMetaBD->tipoTextoVariavel(4000).' NULL,
            dth_cancelamento     '.$objInfraMetaBD->tipoDataHora().'  NULL,
            motivo_cancelamento  '.$objInfraMetaBD->tipoTextoVariavel(4000).'  NULL,
            sta_tipo             '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            sin_ativo            '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL
          )');

          $objInfraMetaBD->adicionarChavePrimaria('acesso_federacao','pk_acesso_federacao',array('id_acesso_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_procedimento_fed','acesso_federacao',array('id_procedimento_federacao'),'protocolo_federacao',array('id_protocolo_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_documento_fed','acesso_federacao',array('id_documento_federacao'),'protocolo_federacao',array('id_protocolo_federacao'));

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_instal_fed_rem','acesso_federacao',array('id_instalacao_federacao_rem'),'instalacao_federacao',array('id_instalacao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_orgao_fed_rem','acesso_federacao',array('id_orgao_federacao_rem'),'orgao_federacao',array('id_orgao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_unidade_fed_rem','acesso_federacao',array('id_unidade_federacao_rem'),'unidade_federacao',array('id_unidade_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_usuario_fed_rem','acesso_federacao',array('id_usuario_federacao_rem'),'usuario_federacao',array('id_usuario_federacao'));

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_instal_fed_dest','acesso_federacao',array('id_instalacao_federacao_dest'),'instalacao_federacao',array('id_instalacao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_orgao_fed_dest','acesso_federacao',array('id_orgao_federacao_dest'),'orgao_federacao',array('id_orgao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_unidade_fed_dest','acesso_federacao',array('id_unidade_federacao_dest'),'unidade_federacao',array('id_unidade_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_acesso_fed_usuario_fed_dest','acesso_federacao',array('id_usuario_federacao_dest'),'usuario_federacao',array('id_usuario_federacao'));

          $objInfraMetaBD->criarIndice('acesso_federacao','i01_acesso_federacao',array('id_procedimento_federacao'));

          $objInfraMetaBD->criarIndice('acesso_federacao','i02_acesso_federacao',array('id_procedimento_federacao','id_instalacao_federacao_rem','id_instalacao_federacao_dest'));

          $objInfraMetaBD->criarIndice('acesso_federacao','i03_acesso_federacao',array('id_procedimento_federacao','id_instalacao_federacao_dest'));

          $objInfraMetaBD->criarIndice('acesso_federacao','i04_acesso_federacao',array('id_documento_federacao','id_instalacao_federacao_rem','id_instalacao_federacao_dest'));


          BancoSEI::getInstance()->executarSql('CREATE TABLE acao_federacao (
            id_acao_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_instalacao_federacao  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_orgao_federacao  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_unidade_federacao  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_usuario_federacao  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_procedimento_federacao  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NULL ,
            id_documento_federacao  '.$objInfraMetaBD->tipoTextoVariavel(26).'  NULL ,
            dth_geracao           '.$objInfraMetaBD->tipoDataHora().'  NOT NULL ,
            dth_acesso            '.$objInfraMetaBD->tipoDataHora().'  NULL,
            sta_tipo            '.$objInfraMetaBD->tipoNumero().'  NOT NULL, 
            sin_ativo            '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL
          )');

          $objInfraMetaBD->adicionarChavePrimaria('acao_federacao','pk_acao_federacao',array('id_acao_federacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE parametro_acao_federacao (
            id_acao_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            nome  '.$objInfraMetaBD->tipoTextoVariavel(50).'  NOT NULL,
            valor '.$objInfraMetaBD->tipoTextoGrande().'  NOT NULL)');

          $objInfraMetaBD->adicionarChavePrimaria('parametro_acao_federacao','pk_parametro_acao_federacao',array('id_acao_federacao','nome'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_param_acao_fed_acao_fed','parametro_acao_federacao',array('id_acao_federacao'),'acao_federacao',array('id_acao_federacao'));

          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\''.TarefaRN::$TI_PROCESSO_ENVIADO_FEDERACAO.'\',\'Processo enviado para @ORGAO_DESTINATARIO@ por @ORGAO_REMETENTE@'.$objInfraMetaBD->novaLinha().'@MOTIVO@\',\'S\',\'S\',\'S\',\'N\',\'N\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\''.TarefaRN::$TI_PROCESSO_ENVIADO_FEDERACAO_CANCELADO.'\',\'Processo enviado para @ORGAO_DESTINATARIO@ por @ORGAO_REMETENTE@'.$objInfraMetaBD->novaLinha().'@MOTIVO@'.$objInfraMetaBD->novaLinha().'(cancelado por @USUARIO@ em @DATA_HORA@)\',\'S\',\'S\',\'S\',\'N\',\'N\')');
          BancoSEI::getInstance()->executarSql('insert into tarefa (id_tarefa,nome,sin_historico_resumido,sin_historico_completo,sin_fechar_andamentos_abertos,sin_lancar_andamento_fechado,sin_permite_processo_fechado) values (\''.TarefaRN::$TI_CANCELAMENTO_ENVIO_PROCESSO_FEDERACAO.'\',\'Cancelado envio para @ORGAO_DESTINATARIO@ por @ORGAO_REMETENTE@'.$objInfraMetaBD->novaLinha().'@MOTIVO@\',\'S\',\'S\',\'S\',\'N\',\'N\')');

          $objInfraMetaBD->adicionarColuna('orgao','sin_federacao_envio',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update orgao set sin_federacao_envio=\'N\'');
          $objInfraMetaBD->alterarColuna('orgao','sin_federacao_envio',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $objInfraMetaBD->adicionarColuna('orgao','sin_federacao_recebimento',$objInfraMetaBD->tipoTextoFixo(1),'null');
          BancoSEI::getInstance()->executarSql('update orgao set sin_federacao_recebimento=\'N\'');
          $objInfraMetaBD->alterarColuna('orgao','sin_federacao_recebimento',$objInfraMetaBD->tipoTextoFixo(1),'not null');

          $objInfraMetaBD->adicionarColuna('orgao','id_unidade',$objInfraMetaBD->tipoNumero(),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_orgao_unidade','orgao',array('id_unidade'),'unidade',array('id_unidade'));

          $objInfraMetaBD->adicionarColuna('orgao','id_orgao_federacao',$objInfraMetaBD->tipoTextoVariavel(26),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_orgao_orgao_federacao','orgao',array('id_orgao_federacao'),'orgao_federacao',array('id_orgao_federacao'));
          $objInfraMetaBD->adicionarColuna('unidade','id_unidade_federacao',$objInfraMetaBD->tipoTextoVariavel(26),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_unidade_unidade_federacao','unidade',array('id_unidade_federacao'),'unidade_federacao',array('id_unidade_federacao'));
          $objInfraMetaBD->adicionarColuna('usuario','id_usuario_federacao',$objInfraMetaBD->tipoTextoVariavel(26),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_usuario_usuario_federacao','usuario',array('id_usuario_federacao'),'usuario_federacao',array('id_usuario_federacao'));
          $objInfraMetaBD->adicionarColuna('protocolo','id_protocolo_federacao',$objInfraMetaBD->tipoTextoVariavel(26),'null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_protocolo_protocolo_fed','protocolo',array('id_protocolo_federacao'),'protocolo_federacao',array('id_protocolo_federacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE grupo_federacao (
            id_grupo_federacao   '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_unidade           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            nome                 '.$objInfraMetaBD->tipoTextoVariavel(50).'  NOT NULL ,
            descricao            '.$objInfraMetaBD->tipoTextoVariavel(250).'  NULL ,
            sta_tipo             '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL ,
            sin_ativo            '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL)');

          $objInfraMetaBD->adicionarChavePrimaria('grupo_federacao','pk_grupo_federacao',array('id_grupo_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_grupo_federacao_unidade','grupo_federacao',array('id_unidade'),'unidade',array('id_unidade'));
          BancoSEI::getInstance()->criarSequencialNativa('seq_grupo_federacao',1);

          BancoSEI::getInstance()->executarSql('CREATE TABLE rel_grupo_fed_orgao_fed (
            id_grupo_federacao   '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            id_orgao_federacao   '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL)');

          $objInfraMetaBD->adicionarChavePrimaria('rel_grupo_fed_orgao_fed','pk_rel_grupo_fed_orgao_fed',array('id_grupo_federacao','id_orgao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_grp_fed_org_fed_grp_fed','rel_grupo_fed_orgao_fed',array('id_grupo_federacao'),'grupo_federacao',array('id_grupo_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_grp_fed_org_fed_org_fed','rel_grupo_fed_orgao_fed',array('id_orgao_federacao'),'orgao_federacao',array('id_orgao_federacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE sinalizacao_federacao (
            id_instalacao_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_protocolo_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_unidade '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            dth_sinalizacao      '.$objInfraMetaBD->tipoDataHora().'  NOT NULL ,
            sta_sinalizacao      '.$objInfraMetaBD->tipoNumero().'  NULL
          )');

          $objInfraMetaBD->adicionarChavePrimaria('sinalizacao_federacao','pk_sinalizacao_federacao',array('id_instalacao_federacao','id_protocolo_federacao','id_unidade'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_sinalizacao_fed_inst_fed','sinalizacao_federacao',array('id_instalacao_federacao'),'instalacao_federacao',array('id_instalacao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_sinalizacao_fed_prot_fed','sinalizacao_federacao',array('id_protocolo_federacao'),'protocolo_federacao',array('id_protocolo_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_sinalizacao_fed_unidade','sinalizacao_federacao',array('id_unidade'),'unidade',array('id_unidade'));

          $objInfraMetaBD->criarIndice('sinalizacao_federacao', 'i01_sinalizacao_federacao', array('id_protocolo_federacao','id_unidade','sta_sinalizacao'));

          BancoSEI::getInstance()->executarSql('CREATE TABLE replicacao_federacao (
            id_replicacao_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_instalacao_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            id_protocolo_federacao '.$objInfraMetaBD->tipoTextoVariavel(26).'  NOT NULL ,
            sta_tipo             '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            dth_cadastro         '.$objInfraMetaBD->tipoDataHora().'  NOT NULL ,
            dth_replicacao       '.$objInfraMetaBD->tipoDataHora().'  NULL ,
            tentativa            '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            erro                 '.$objInfraMetaBD->tipoTextoVariavel(4000).'  NULL,
            sin_ativo            '.$objInfraMetaBD->tipoTextoFixo(1).'  NOT NULL
            )');

          $objInfraMetaBD->adicionarChavePrimaria('replicacao_federacao','pk_replicacao_federacao',array('id_replicacao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_replicacao_fed_inst_fed','replicacao_federacao',array('id_instalacao_federacao'),'instalacao_federacao',array('id_instalacao_federacao'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_replicacao_fed_prot_fed','replicacao_federacao',array('id_protocolo_federacao'),'protocolo_federacao',array('id_protocolo_federacao'));

          $objInfraSequencia = new InfraSequencia(BancoSEI::getInstance());
          BancoSEI::getInstance()->executarSql('insert into infra_agendamento_tarefa (
                            id_infra_agendamento_tarefa, descricao, comando, sta_periodicidade_execucao,
                            periodicidade_complemento, dth_ultima_execucao, dth_ultima_conclusao,
                            sin_sucesso, parametro, email_erro, sin_ativo)
                            values ('.$objInfraSequencia->obterProximaSequencia('infra_agendamento_tarefa').',\'Processa replicações de sinalizações em processos e envia e-mails de aviso sobre solicitações do SEI Federação.\',\'AgendamentoRN::processarFederacao\',\'N\',\'0, 10, 20, 30, 40, 50\',null,null,\'N\',null,null,\'S\')');

          //FEDERACAO - FIM

          BancoSEI::getInstance()->executarSql('CREATE TABLE campo_pesquisa
          (
            id_campo_pesquisa    '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            chave                '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
            valor               '.$objInfraMetaBD->tipoTextoVariavel(4000).'  NOT NULL ,
            id_pesquisa          '.$objInfraMetaBD->tipoNumero().'  NOT NULL 
          )');
          $objInfraMetaBD->adicionarChavePrimaria('campo_pesquisa','pk_campo_pesquisa',array('id_campo_pesquisa'));

          BancoSEI::getInstance()->executarSql('
            CREATE TABLE pesquisa
            (
              id_pesquisa          '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              nome                '.$objInfraMetaBD->tipoTextoVariavel(50).'  NOT NULL ,
              id_usuario           '.$objInfraMetaBD->tipoNumero().'  NOT NULL ,
              id_unidade           '.$objInfraMetaBD->tipoNumero().'  NULL 
            )');
          $objInfraMetaBD->adicionarChavePrimaria('pesquisa','pk_pesquisa',array('id_pesquisa'));

          $objInfraMetaBD->adicionarChaveEstrangeira('fk_campo_pesquisa_pesquisa','campo_pesquisa',array('id_pesquisa'),'pesquisa',array('id_pesquisa'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_pesquisa_usuario','pesquisa',array('id_usuario'),'usuario',array('id_usuario'));
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_pesquisa_unidade','pesquisa',array('id_unidade'),'unidade',array('id_unidade'));

          BancoSEI::getInstance()->criarSequencialNativa('seq_pesquisa',1);
          BancoSEI::getInstance()->criarSequencialNativa('seq_campo_pesquisa',1);

          $objInfraMetaBD->criarIndice('atividade','i17_atividade',array('id_protocolo','id_tarefa','id_unidade','id_unidade_origem'));

          $objInfraMetaBD->alterarColuna('assinatura', 'tratamento', $objInfraMetaBD->tipoTextoVariavel(200), 'not null');
          $objInfraMetaBD->alterarColuna('assinante', 'cargo_funcao', $objInfraMetaBD->tipoTextoVariavel(200), 'not null');

          $objInfraMetaBD->alterarColuna('orgao', 'descricao', $objInfraMetaBD->tipoTextoVariavel(250), 'not null');
          $objInfraMetaBD->alterarColuna('orgao_historico', 'descricao', $objInfraMetaBD->tipoTextoVariavel(250), 'not null');

          $objInfraMetaBD->adicionarColuna('assinante', 'id_orgao', $objInfraMetaBD->tipoNumero(), 'null');
          $this->fixAssinantes();
          $objInfraMetaBD->alterarColuna('assinante', 'id_orgao', $objInfraMetaBD->tipoNumero(), 'not null');
          $objInfraMetaBD->adicionarChaveEstrangeira('fk_assinante_orgao','assinante',array('id_orgao'),'orgao',array('id_orgao'));

          InfraDebug::getInstance()->setBolDebugInfra(false);

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

          InfraDebug::getInstance()->setBolDebugInfra(false);
          $this->fixIndices40($objInfraMetaBD);

        }catch(Exception $e){
          InfraDebug::getInstance()->setBolLigado(false);
          InfraDebug::getInstance()->setBolDebugInfra(false);
          InfraDebug::getInstance()->setBolEcho(false);
          throw new InfraException('Erro atualizando versão.', $e);
        }
      }

      protected function fixTarefasPrazoAcessoExterno(){
        $arrIdTarefas = array(TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO, TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO_CANCELADA, TarefaRN::$TI_CANCELAMENTO_LIBERACAO_ACESSO_EXTERNO);
        foreach($arrIdTarefas as $numIdTarefa) {
          $rsTarefas = BancoSEI::getInstance()->consultarSql('select aadias.id_atividade, aadias.id_atributo_andamento as id_atributo_andamento_dias,aadias.valor as dias, aadata.id_atributo_andamento as id_atributo_andamento_data, aadata.valor as data FROM atributo_andamento aadias inner join atividade atdias on aadias.id_atividade = atdias.id_atividade AND atdias.id_tarefa = '.$numIdTarefa.' AND aadias.nome  = \'DIAS_VALIDADE\'   inner join atributo_andamento aadata on aadata.id_atividade = aadias.id_atividade inner join atividade atdata on aadata.id_atividade = atdata.id_atividade AND atdata.id_tarefa = '.$numIdTarefa.' AND aadata.nome  = \'DATA_VALIDADE\'');

          InfraDebug::getInstance()->setBolDebugInfra(false);

          $n = 0;
          $numRegistros = count($rsTarefas);

          foreach ($rsTarefas as $tarefa) {

            if ((++$n >= 500 && $n % 500 == 0) || $n == $numRegistros) {
              InfraDebug::getInstance()->gravar('ATUALIZANDO ANDAMENTOS DE ACESSO EXTERNO: '.$n.' DE '.$numRegistros);
            }

            $strNovoTexto = "até ".$tarefa['data']." (".$tarefa['dias'].")";
            BancoSEI::getInstance()->executarSql("update atributo_andamento set nome = 'VALIDADE', valor = ".BancoSEI::getInstance()->formatarGravacaoStr($strNovoTexto)."  where id_atributo_andamento = ".$tarefa['id_atributo_andamento_dias']);
            BancoSEI::getInstance()->executarSql("delete from atributo_andamento where id_atributo_andamento = ".$tarefa['id_atributo_andamento_data']);
          }
          InfraDebug::getInstance()->setBolDebugInfra(true);
        }
      }

      protected function fixTelefonesContatosOuvidoria()
      {
        $objTipoContatoRN = new TipoContatoRN();

        $objTipoContatoDTO = new TipoContatoDTO();
        $objTipoContatoDTO->retNumIdTipoContato();
        $objTipoContatoDTO->setStrNome("Ouvidoria");
        $objTipoContatoDTO = $objTipoContatoRN->consultarRN0336($objTipoContatoDTO);

        if ($objTipoContatoDTO!=null) {
          InfraDebug::getInstance()->gravar('ALTERANDO TELEFONES CONTATOS OUVIDORIA');
          BancoSEI::getInstance()->executarSql("update contato set telefone_residencial = telefone_comercial where id_tipo_contato = ".$objTipoContatoDTO->getNumIdTipoContato());
          BancoSEI::getInstance()->executarSql("update contato set telefone_comercial = null where id_tipo_contato = ".$objTipoContatoDTO->getNumIdTipoContato());
        }
      }

      protected function fixHistoricoUnidadeOrgao (){
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

        if($objAtividadeDTO!=null){

          $dtaAtividade = substr($objAtividadeDTO->getDthAbertura(),0,10);

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

        if ($objPublicacaoLegadoDTO!=null && InfraData::compararDatas($objPublicacaoLegadoDTO->getDtaPublicacao(), $dtaInicial) > 0){
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

      protected function fixMarcadoresConectado(){
        try{

          //busca processos com marcador
          $rsProcedimentos = BancoSEI::getInstance()->consultarSql('select distinct '.BancoSEI::getInstance()->formatarSelecaoDbl('andamento_marcador', 'id_procedimento', 'idprocedimento').' from andamento_marcador order by idprocedimento desc');

          $numRegistros = count($rsProcedimentos);

          InfraDebug::getInstance()->setBolDebugInfra(false);

          $objAndamentoMarcadorBD = new AndamentoMarcadorBD(BancoSEI::getInstance());

          $n = 0;

          //para cada processo
          foreach($rsProcedimentos as $item) {

            $dblIdProcedimento = BancoSEI::getInstance()->formatarLeituraDbl($item['idprocedimento']);

            if ((++$n >= 500 && $n % 500 == 0) || $n == $numRegistros) {
              InfraDebug::getInstance()->gravar('ATUALIZANDO ANDAMENTOS DE MARCADORES: '.$n.' DE '.$numRegistros);
            }

            $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
            $objAndamentoMarcadorDTO->setDistinct(true);
            $objAndamentoMarcadorDTO->retNumIdUnidade();
            $objAndamentoMarcadorDTO->setDblIdProcedimento($dblIdProcedimento);

            $arrIdUnidadeMarcador = InfraArray::converterArrInfraDTO($objAndamentoMarcadorBD->listar($objAndamentoMarcadorDTO),'IdUnidade');

            foreach($arrIdUnidadeMarcador as $numIdUnidade) {

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

          $rs = BancoSEI::getInstance()->consultarSql('select count(*),'.
              BancoSEI::getInstance()->formatarSelecaoNum('andamento_marcador','id_marcador', 'idmarcador').','.
              BancoSEI::getInstance()->formatarSelecaoNum('andamento_marcador','id_unidade','idunidade').','.
              BancoSEI::getInstance()->formatarSelecaoDbl('andamento_marcador','id_procedimento','idprocedimento').
              ' from andamento_marcador where sin_ultimo=\'S\''.
              ' group by id_marcador, id_unidade, id_procedimento'.
              ' having count(*) > 1');

          InfraDebug::getInstance()->setBolDebugInfra(false);

          foreach($rs as $item){

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

          BancoSEI::getInstance()->executarSql('update andamento_marcador set sin_ultimo=\'N\', sta_operacao=\''.AndamentoMarcadorRN::$TO_REMOCAO.'\' where id_marcador is null');

          BancoSEI::getInstance()->executarSql('update andamento_marcador set sin_ultimo=\'N\' where sin_ultimo=\'S\' and sin_ativo=\'N\'');

        }catch(Exception $e){
          throw new InfraException('Erro atualizando marcadores.', $e);
        }
      }

      protected function fixIndices31(InfraMetaBD $objInfraMetaBD)
      {
        InfraDebug::getInstance()->setBolDebugInfra(true);

        $this->logar('ATUALIZANDO INDICES...');

        $arrTabelas31 = array('acesso','acesso_externo','acompanhamento','andamento_marcador','andamento_situacao','anexo','anotacao',
            'arquivamento','arquivo_extensao','assinante','assinatura','assunto','assunto_proxy','atividade','atributo',
            'atributo_andamento','auditoria_protocolo','base_conhecimento','bloco','cargo','cargo_funcao','cidade',
            'conjunto_estilos','conjunto_estilos_item','contato','contexto','controle_interno','controle_unidade',
            'documento','documento_conteudo','dominio','email_grupo_email','email_sistema','email_unidade','email_utilizado',
            'estatisticas','estilo','feed','feriado','grupo_acompanhamento','grupo_contato','grupo_email','grupo_protocolo_modelo',
            'grupo_serie','grupo_unidade','hipotese_legal','imagem_formato','localizador','lugar_localizador','mapeamento_assunto',
            'marcador','modelo','monitoramento_servico','nivel_acesso_permitido','notificacao','novidade','numeracao',
            'observacao','operacao_servico','ordenador_despesa','orgao','pais','participante','procedimento','protocolo',
            'protocolo_modelo','publicacao','publicacao_legado','rel_acesso_ext_protocolo','rel_assinante_unidade',
            'rel_base_conhec_tipo_proced','rel_bloco_protocolo','rel_bloco_unidade','rel_controle_interno_orgao',
            'rel_controle_interno_serie','rel_controle_interno_tipo_proc','rel_controle_interno_unidade','rel_grupo_contato',
            'rel_grupo_unidade_unidade','rel_notificacao_documento','rel_protocolo_assunto','rel_protocolo_atributo',
            'rel_protocolo_protocolo','rel_secao_modelo_estilo','rel_secao_mod_cj_estilos_item','rel_serie_assunto',
            'rel_serie_veiculo_publicacao','rel_situacao_unidade','rel_tipo_procedimento_assunto','rel_unidade_tipo_contato',
            'retorno_programado','secao_documento','secao_imprensa_nacional','secao_modelo','serie','serie_escolha',
            'serie_publicacao','serie_restricao','servico','situacao','tabela_assuntos','tarefa','tarja_assinatura',
            'texto_padrao_interno','tipo_conferencia','tipo_contato','tipo_formulario','tipo_localizador','tipo_procedimento',
            'tipo_procedimento_escolha','tipo_proced_restricao','tipo_suporte','tratamento','uf','unidade','unidade_publicacao',
            'usuario','veiculo_imprensa_nacional','veiculo_publicacao','velocidade_transferencia','versao_secao_documento',
            'vocativo')
        ;

        $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas31);

        $objInfraMetaBD->criarIndice('numeracao', 'ak_numeracao', array('ano', 'id_serie', 'id_orgao', 'id_unidade'), true);
        $objInfraMetaBD->criarIndice('documento', 'i04_documento', array('numero', 'id_serie'));
        $objInfraMetaBD->criarIndice('atributo_andamento', 'i02_atributo_andamento', array('nome', 'id_origem'));
        $objInfraMetaBD->criarIndice('atividade', 'i03_atividade', array('id_unidade', 'dth_conclusao', 'sin_inicial'));
        $objInfraMetaBD->criarIndice('atividade','i10_atividade',array('dth_abertura','id_tarefa'));
        $objInfraMetaBD->criarIndice('acesso', 'i02_acesso', array('id_protocolo', 'sta_tipo'));
        $objInfraMetaBD->criarIndice('acesso','i03_acesso',array('id_protocolo','id_unidade','id_usuario'));
        $objInfraMetaBD->criarIndice('andamento_marcador', 'i02_andamento_marcador', array('id_unidade', 'id_procedimento', 'sin_ultimo'));
        $objInfraMetaBD->criarIndice('retorno_programado', 'i06_retorno_programado', array('dta_programada'));
        $objInfraMetaBD->criarIndice('protocolo', 'i10_protocolo', array('protocolo_formatado_pesquisa', 'sta_nivel_acesso_global', 'id_protocolo'));
        $objInfraMetaBD->criarIndice('protocolo', 'i11_protocolo', array('sta_protocolo', 'sta_nivel_acesso_global', 'id_protocolo'));
        $objInfraMetaBD->criarIndice('protocolo', 'i12_protocolo', array('sta_estado', 'sta_protocolo', 'sta_nivel_acesso_global', 'id_protocolo'));
        $objInfraMetaBD->criarIndice('protocolo', 'i13_protocolo', array('id_protocolo', 'sta_protocolo', 'id_usuario_gerador', 'id_unidade_geradora', 'dta_geracao'));
        $objInfraMetaBD->criarIndice('protocolo', 'i14_protocolo', array('id_protocolo', 'id_hipotese_legal', 'id_unidade_geradora'));
        $objInfraMetaBD->criarIndice('atributo_andamento', 'i04_atributo_andamento', array('id_atividade', 'id_atributo_andamento'));
        $objInfraMetaBD->criarIndice('atividade', 'i16_atividade', array('id_unidade', 'id_protocolo', 'dth_conclusao', 'id_usuario', 'id_atividade', 'id_usuario_atribuicao'));

        InfraDebug::getInstance()->setBolDebugInfra(false);
      }

      protected function fixIndices40(InfraMetaBD $objInfraMetaBD)
      {
        InfraDebug::getInstance()->setBolDebugInfra(true);

        $this->logar('ATUALIZANDO INDICES...');

        $arrTabelas40 = array('acesso','acesso_externo','acompanhamento','andamento_marcador','andamento_situacao','anexo','anotacao',
            'arquivamento','arquivo_extensao','assinante','assinatura','assunto','assunto_proxy','atividade','atributo',
            'atributo_andamento','auditoria_protocolo','base_conhecimento','bloco','cargo','cargo_funcao','cidade',
            'conjunto_estilos','conjunto_estilos_item','contato','controle_interno','controle_unidade',
            'documento','documento_conteudo','dominio','email_grupo_email','email_sistema','email_unidade','email_utilizado',
            'estatisticas','estilo','feed','feriado','grupo_acompanhamento','grupo_contato','grupo_email','grupo_protocolo_modelo',
            'grupo_serie','grupo_unidade','hipotese_legal','imagem_formato','localizador','lugar_localizador','mapeamento_assunto',
            'marcador','modelo','monitoramento_servico','nivel_acesso_permitido','notificacao','novidade','numeracao',
            'observacao','operacao_servico','ordenador_despesa','orgao','pais','participante','procedimento','protocolo',
            'protocolo_modelo','publicacao','publicacao_legado','rel_acesso_ext_protocolo','rel_assinante_unidade',
            'rel_base_conhec_tipo_proced','rel_bloco_protocolo','rel_bloco_unidade','rel_controle_interno_orgao',
            'rel_controle_interno_serie','rel_controle_interno_tipo_proc','rel_controle_interno_unidade','rel_grupo_contato',
            'rel_grupo_unidade_unidade','rel_notificacao_documento','rel_protocolo_assunto','rel_protocolo_atributo',
            'rel_protocolo_protocolo','rel_secao_modelo_estilo','rel_secao_mod_cj_estilos_item','rel_serie_assunto',
            'rel_serie_veiculo_publicacao','rel_situacao_unidade','rel_tipo_procedimento_assunto','rel_unidade_tipo_contato',
            'retorno_programado','secao_documento','secao_imprensa_nacional','secao_modelo','serie','serie_escolha',
            'serie_publicacao','serie_restricao','servico','situacao','tabela_assuntos','tarefa','tarja_assinatura',
            'texto_padrao_interno','tipo_conferencia','tipo_contato','tipo_formulario','tipo_localizador','tipo_procedimento',
            'tipo_procedimento_escolha','tipo_proced_restricao','tipo_suporte','tratamento','uf','unidade','unidade_publicacao',
            'usuario','veiculo_imprensa_nacional','veiculo_publicacao','velocidade_transferencia','versao_secao_documento','vocativo',
            'rel_usuario_marcador','rel_usuario_grupo_acomp','rel_usuario_usuario_unidade',
            'orgao_historico', 'unidade_historico', 'titulo', 'controle_prazo', 'comentario', 'categoria',
            'lembrete','rel_acesso_ext_serie', 'grupo_bloco', 'rel_usuario_grupo_bloco',
            'instalacao_federacao', 'tarefa_instalacao', 'andamento_instalacao', 'atributo_instalacao',
            'orgao_federacao', 'unidade_federacao', 'usuario_federacao', 'protocolo_federacao',
            'acesso_federacao', 'acao_federacao', 'parametro_acao_federacao'
            )
        ;

        $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas40);

        InfraDebug::getInstance()->setBolDebugInfra(false);
      }

      protected function fixAcessoProcessosAnexadosRestritosConectado(){
        try{

          InfraDebug::getInstance()->setBolDebugInfra(false);

          InfraDebug::getInstance()->gravar('RESTABELECENDO ACESSO EM PROCESSOS ANEXADOS RESTRITOS');

          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->setDistinct(true);
          $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

          $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
          $arrIdProcessosPai = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO),'IdProtocolo1');

          $objAtividadeRN = new AtividadeRN();
          $objAcessoRN = new AcessoRN();
          $objProtocoloRN = new ProtocoloRN();

          $n = 0;
          $numRegistros = count($arrIdProcessosPai);
          foreach($arrIdProcessosPai as $dblIdProcessoPai) {

            $arrAtualizacao = array();

            if ((++$n >= 100 && $n % 100 == 0) || $n == 1 || $n == $numRegistros) {
              InfraDebug::getInstance()->gravar('VERIFICANDO '.$n.' DE '.$numRegistros);
            }

            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($dblIdProcessoPai);

            $arrIdProtocolos = $arrIdProcessosFilho = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO),'IdProtocolo2');

            $arrIdProtocolos[] = $dblIdProcessoPai;

            $objProtocoloDTO = new ProtocoloDTO();
            $objProtocoloDTO->retDblIdProtocolo();
            $objProtocoloDTO->retStrProtocoloFormatado();
            $objProtocoloDTO->retStrStaNivelAcessoGlobal();
            $objProtocoloDTO->setDblIdProtocolo($arrIdProtocolos, InfraDTO::$OPER_IN);
            $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO),'IdProtocolo');


            foreach($arrIdProcessosFilho as $dblIdProcessoFilho) {
              if ($arrObjProtocoloDTO[$dblIdProcessoPai]->getStrStaNivelAcessoGlobal() != $arrObjProtocoloDTO[$dblIdProcessoFilho]->getStrStaNivelAcessoGlobal()) {
                InfraDebug::getInstance()->gravar($arrObjProtocoloDTO[$dblIdProcessoPai]->getStrProtocoloFormatado().' -> '.$arrObjProtocoloDTO[$dblIdProcessoFilho]->getStrProtocoloFormatado());
                $arrAtualizacao[$dblIdProcessoPai] = 0;
                break;
              }
            }

            if (!isset($arrAtualizacao[$dblIdProcessoPai]) && $arrObjProtocoloDTO[$dblIdProcessoPai]->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_RESTRITO){

              $objAtividadeDTO = new AtividadeDTO();
              $objAtividadeDTO->setDistinct(true);
              $objAtividadeDTO->retNumIdUnidade();
              $objAtividadeDTO->setNumIdTarefa(TarefaRN::getArrTarefasTramitacao(), InfraDTO::$OPER_IN);
              $objAtividadeDTO->setDblIdProtocolo($dblIdProcessoPai);
              $objAtividadeDTO->setOrdNumIdUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

              $arrIdUnidadesTramitacao = InfraArray::converterArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO), 'IdUnidade');

              $objAcessoDTO = new AcessoDTO();
              $objAcessoDTO->retDblIdProtocolo();
              $objAcessoDTO->retNumIdUnidade();
              $objAcessoDTO->setDblIdProtocolo($arrIdProtocolos, InfraDTO::$OPER_IN);
              $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_RESTRITO_UNIDADE);
              $objAcessoDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
              $objAcessoDTO->setOrdNumIdUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

              $arrObjAcessoDTO = InfraArray::indexarArrInfraDTO($objAcessoRN->listar($objAcessoDTO),'IdProtocolo', true);

              foreach($arrObjAcessoDTO as $dblIdProcessoAcesso => $arr) {

                $arrIdUnidadesAcesso = InfraArray::converterArrInfraDTO($arr,'IdUnidade');

                if ($arrIdUnidadesTramitacao != $arrIdUnidadesAcesso) {
                  InfraDebug::getInstance()->gravar($arrObjProtocoloDTO[$dblIdProcessoAcesso]->getStrProtocoloFormatado());
                  $arrAtualizacao[$dblIdProcessoPai] = 0;
                  break;
                }
              }
            }


            if (count($arrAtualizacao)) {

              foreach(array_keys($arrAtualizacao) as $dblIdProcesso) {
                $objMudarNivelAcessoDTO = new MudarNivelAcessoDTO();
                $objMudarNivelAcessoDTO->setStrSinLancarAndamento('N');
                $objMudarNivelAcessoDTO->setStrStaOperacao(ProtocoloRN::$TMN_ANEXACAO);
                $objMudarNivelAcessoDTO->setDblIdProtocolo($dblIdProcesso);
                $objMudarNivelAcessoDTO->setStrStaNivel(null);
                $objProtocoloRN->mudarNivelAcesso($objMudarNivelAcessoDTO);
              }
            }
          }

          InfraDebug::getInstance()->setBolDebugInfra(true);

        }catch(Exception $e){
          throw new InfraException('Erro restabelecendo acesso em processos anexados restritos.', $e);
        }
      }

      protected function fixQuantidadeControleProcessosConectado(){
        try{

          InfraDebug::getInstance()->gravar('CORRIGINDO QUANTIDADE DE PROCESSOS DO CONTROLE DE PROCESSOS');

          InfraDebug::getInstance()->setBolDebugInfra(false);

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setBolExclusaoLogica(false);
          $objUnidadeDTO->retNumIdUnidade();

          $objUnidadeRN = new UnidadeRN();
          $arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);

          $objAtividadeBD = new AtividadeBD(BancoSEI::getInstance());

          foreach($arrObjUnidadeDTO as $objUnidadeDTO) {


            $sql = 'select count(*) as total, '.
                BancoSEI::getInstance()->formatarSelecaoDbl('atividade', 'id_protocolo', 'idprotocolo').', '.
                BancoSEI::getInstance()->formatarSelecaoNum('atividade', 'id_unidade', 'idunidade').', '.
                BancoSEI::getInstance()->formatarSelecaoNum('atividade', 'id_usuario', 'idusuario').
                ' from atividade '.
                ' where id_tarefa in (32, 61, 66, 118) and dth_conclusao is null '.
                ' and id_unidade='.$objUnidadeDTO->getNumIdUnidade().
                ' group by id_protocolo, id_unidade, id_usuario '.
                ' having count(*) > 1 '.
                ' order by id_protocolo';

            $rs = BancoSEI::getInstance()->consultarSql($sql);

            $numRegistros = count($rs);

            $n = 0;

            foreach ($rs as $item) {

              if ((++$n >= 100 && $n % 100 == 0) || $n == $numRegistros) {
                InfraDebug::getInstance()->gravar($objUnidadeDTO->getNumIdUnidade().': '.$n.' DE '.$numRegistros);
              }

              $objAtividadeDTO = new AtividadeDTO();
              $objAtividadeDTO->retNumIdAtividade();
              $objAtividadeDTO->retDthAbertura();
              $objAtividadeDTO->setDblIdProtocolo(BancoSEI::getInstance()->formatarLeituraDbl($item['idprotocolo']));
              $objAtividadeDTO->setNumIdUnidade(BancoSEI::getInstance()->formatarLeituraNum($item['idunidade']));
              $objAtividadeDTO->setNumIdUsuario(BancoSEI::getInstance()->formatarLeituraNum($item['idusuario']));
              $objAtividadeDTO->setDthConclusao(null);
              $objAtividadeDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);
              $arrObjAtividadeDTO = $objAtividadeBD->listar($objAtividadeDTO);

              $numAndamentos = count($arrObjAtividadeDTO);

              if ($numAndamentos > 1) {
                for ($i = 1; $i < $numAndamentos; $i++) {
                  $arrObjAtividadeDTO[$i]->unSetDthAbertura();
                  $arrObjAtividadeDTO[$i]->setDthConclusao($arrObjAtividadeDTO[0]->getDthAbertura());
                  $objAtividadeBD->alterar($arrObjAtividadeDTO[$i]);
                }
              }
            }
          }

          InfraDebug::getInstance()->setBolDebugInfra(true);

        }catch(Exception $e){
          throw new InfraException('Erro corrigindo quantidade de processos do Controle de Processos.', $e);
        }
      }

      protected function fixNumeracaoConectado(){
        try{

          $rs = BancoSEI::getInstance()->consultarSql('select count(*), ano, id_serie, id_orgao, id_unidade from numeracao group by ano, id_serie, id_orgao, id_unidade having count(*) > 1');

          $objNumeracaoDTO = new NumeracaoDTO();

          $objNumeracaoRN = new NumeracaoRN();

          foreach($rs as $item) {

            $objNumeracaoDTO->retNumIdNumeracao();
            $objNumeracaoDTO->setNumAno($item['ano']);
            $objNumeracaoDTO->setNumIdSerie($item['id_serie']);
            $objNumeracaoDTO->setNumIdOrgao($item['id_orgao']);
            $objNumeracaoDTO->setNumIdUnidade($item['id_unidade']);
            $objNumeracaoDTO->setOrdNumIdNumeracao(InfraDTO::$TIPO_ORDENACAO_ASC);

            $arrObjNumeracaoDTO = $objNumeracaoRN->listar($objNumeracaoDTO);

            for($i=1;$i<count($arrObjNumeracaoDTO);$i++){
              $objNumeracaoRN->excluir(array($arrObjNumeracaoDTO[$i]));
            }
          }

        }catch(Exception $e){
          throw new InfraException('Erro corrigindo numeração.', $e);
        }
      }

      protected function fixDataCadastroProtocoloConectado(){
        try{

          InfraDebug::getInstance()->setBolDebugInfra(false);

          InfraDebug::getInstance()->gravar('POPULANDO DATA DE CADASTRO EM PROTOCOLO');

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->retDthAbertura();
          $objAtividadeDTO->setOrdDthAbertura(InfraDTO::$TIPO_ORDENACAO_ASC);
          $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

          $objAtividadeRN = new AtividadeRN();
          $objAtributoAdamentoRN = new AtributoAndamentoRN();
          $objAtividadeDTO = $objAtividadeRN->consultarRN0033($objAtividadeDTO);
          if($objAtividadeDTO){
            $dtaInicial = substr($objAtividadeDTO->getDthAbertura(),0,10);
          } else {
            $dtaInicial = InfraData::getStrDataAtual();
          }
          $dtaFinal = InfraData::getStrDataAtual();

          $mesAno = substr($dtaInicial,3,2).'/'.substr($dtaInicial,6,4);

          while(InfraData::compararDatasSimples($dtaInicial,$dtaFinal)>=0) {

            $mesAnoAtual = substr($dtaInicial,3,2).'/'.substr($dtaInicial,6,4);

            if ($mesAnoAtual!=$mesAno) {
              InfraDebug::getInstance()->gravar($mesAnoAtual.'...');
              $mesAno = $mesAnoAtual;
            }

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->retDblIdProtocolo();
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);
            $objAtividadeDTO->adicionarCriterio(array('Abertura', 'Abertura'),
                array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
                array($dtaInicial.' 00:00:00', $dtaInicial.' 23:59:59'),
                InfraDTO::$OPER_LOGICO_AND);

            $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

            if (count($arrObjAtividadeDTO)) {

              $arrIdProcessos = InfraArray::converterArrInfraDTO($arrObjAtividadeDTO, 'IdProtocolo');

              $sql = 'update protocolo set dta_inclusao='.BancoSEI::getInstance()->formatarGravacaoDta($dtaInicial).' where ';

              //oracle
              $arrPartes = array_chunk($arrIdProcessos, 1000);

              $strOr = '';
              foreach ($arrPartes as $arrParte) {
                if ($strOr != '') {
                  $sql .= $strOr;
                }
                $sql .= ' id_protocolo in ('.implode(',', $arrParte).')';
                $strOr = ' OR ';
              }

              BancoSEI::getInstance()->executarSql($sql);

              $arrTarefasDocumentos = array(TarefaRN::$TI_GERACAO_DOCUMENTO, TarefaRN::$TI_RECEBIMENTO_DOCUMENTO);

              foreach ($arrTarefasDocumentos as $numIdTarefaDocumento) {

                $objAtividadeDTO = new AtividadeDTO();
                $objAtividadeDTO->retNumIdAtividade();
                $objAtividadeDTO->setNumIdTarefa($numIdTarefaDocumento);
                $objAtividadeDTO->adicionarCriterio(array('Abertura', 'Abertura'),
                    array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
                    array($dtaInicial.' 00:00:00', $dtaInicial.' 23:59:59'),
                    InfraDTO::$OPER_LOGICO_AND);

                $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

                if (count($arrObjAtividadeDTO)) {

                  $objAtributoAdamentoDTO = new AtributoAndamentoDTO();
                  $objAtributoAdamentoDTO->retStrIdOrigem();
                  $objAtributoAdamentoDTO->setNumIdAtividade(InfraArray::converterArrInfraDTO($arrObjAtividadeDTO, 'IdAtividade'), InfraDTO::$OPER_IN);
                  $objAtributoAdamentoDTO->setStrNome('DOCUMENTO');

                  $arrObjAtributoAndamentoDTO = $objAtributoAdamentoRN->listarRN1367($objAtributoAdamentoDTO);

                  if (count($arrObjAtributoAndamentoDTO)) {

                    $arrIdDocumentos = InfraArray::converterArrInfraDTO($arrObjAtributoAndamentoDTO, 'IdOrigem');

                    $sql = 'update protocolo set dta_inclusao='.BancoSEI::getInstance()->formatarGravacaoDta($dtaInicial).' where ';

                    //oracle
                    $arrPartes = array_chunk($arrIdDocumentos, 1000);

                    $strOr = '';
                    foreach ($arrPartes as $arrParte) {
                      if ($strOr != '') {
                        $sql .= $strOr;
                      }
                      $sql .= ' id_protocolo in ('.implode(',', $arrParte).')';
                      $strOr = ' OR ';
                    }

                    BancoSEI::getInstance()->executarSql($sql);
                  }
                }
              }
            }
            $dtaInicial = InfraData::calcularData(1,InfraData::$UNIDADE_DIAS,InfraData::$SENTIDO_ADIANTE, $dtaInicial);
          }

          BancoSEI::getInstance()->executarSql('update protocolo set dta_inclusao=dta_geracao where dta_inclusao is null');

          InfraDebug::getInstance()->setBolDebugInfra(true);

        }catch(Exception $e){
          throw new InfraException('Erro populando data de cadastro em protocolo.', $e);
        }
      }

      protected function fixBlocosUnidadeGeradoraConectado()
      {
        try {

          InfraDebug::getInstance()->gravar('AJUSTANDO BLOCOS DA UNIDADE');

          InfraDebug::getInstance()->setBolDebugInfra(false);

          $sql = 'select '.
              BancoSEI::getInstance()->formatarSelecaoNum('bloco','id_bloco', 'idbloco').','.
              BancoSEI::getInstance()->formatarSelecaoNum('bloco','id_unidade', 'idunidade').' '.
              'from bloco '.
              'where not exists (select rel_bloco_unidade.id_bloco '.
              'from rel_bloco_unidade '.
              'where rel_bloco_unidade.id_bloco=bloco.id_bloco and rel_bloco_unidade.id_unidade=bloco.id_unidade)';


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
              InfraDebug::getInstance()->gravar($n.' DE '.$numRegistros);
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

      protected function configurarUsuarioInternet(){
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
            $objUsuarioDTO->setStrSinAcessibilidade('N');
            $objUsuarioDTO->setStrSinAtivo('S');
            $objUsuarioDTO = $objUsuarioRN->cadastrarRN0487($objUsuarioDTO);
          }

          $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
          $objInfraParametro->setValor('ID_USUARIO_INTERNET', $objUsuarioDTO->getNumIdUsuario());

        }catch(Exception $e){
          throw new InfraException('Erro configurando usuário INTERNET.', $e);
        }
      }

      protected function cadastrarTipoProcessoFederacao(){
        try{

          $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
          $objTipoProcedimentoDTO->setNumIdTipoProcedimento(null);
          $objTipoProcedimentoDTO->setStrNome('SEI Federação');
          $objTipoProcedimentoDTO->setStrDescricao('Aplicado automaticamente em processos recebidos pelo SEI Federação.');
          $objTipoProcedimentoDTO->setStrStaGrauSigiloSugestao(ProtocoloRN::$NA_PUBLICO);
          $objTipoProcedimentoDTO->setNumIdHipoteseLegalSugestao(null);
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

        }catch(Exception $e){
          throw new InfraException('Erro configurando tipo de processo do SEI Federação.', $e);
        }
      }

      protected function fixAssinantes(){
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
        foreach($arrObjOrgaoDTO as $objOrgaoDTO) {

          InfraDebug::getInstance()->gravar($objOrgaoDTO->getStrSigla().'...');

          $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
          $objRelAssinanteUnidadeDTO->setDistinct(true);
          $objRelAssinanteUnidadeDTO->retNumIdUnidade();
          $objRelAssinanteUnidadeDTO->retStrCargoFuncaoAssinante();
          $objRelAssinanteUnidadeDTO->setNumIdOrgaoUnidade($objOrgaoDTO->getNumIdOrgao());
          $arrObjRelAssinanteUnidadeDTO = InfraArray::indexarArrInfraDTO($objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO),'CargoFuncaoAssinante',true);

          foreach($arrObjRelAssinanteUnidadeDTO as $strCargoFuncao => $arrObjRelAssinanteUnidadeDTOCargoFuncao) {
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
    }

    session_start();

    SessaoSEI::getInstance(false);

    BancoSEI::getInstance()->setBolScript(true);

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $strVersaoBanco = $objInfraParametro->getValor('SEI_VERSAO');
    if (count(explode('.',$strVersaoBanco))==2){
      $strVersaoBanco .= '.0';
      $objInfraParametro->setValor('SEI_VERSAO',$strVersaoBanco);
    }

    $objVersaoSeiRN = new VersaoSeiRN();
    $objVersaoSeiRN->setStrNome('SEI');
    $objVersaoSeiRN->setStrVersaoAtual(SEI_VERSAO);
    $objVersaoSeiRN->setStrParametroVersao('SEI_VERSAO');
    $objVersaoSeiRN->setArrVersoes(array('3.0.*' => 'versao_3_0_0',
                                         '3.1.*' => 'versao_3_1_0',
                                         '4.0.*' => 'versao_4_0_0'
    ));
    $objVersaoSeiRN->setStrVersaoInfra('1.583.4');
    $objVersaoSeiRN->setBolMySql(true);
    $objVersaoSeiRN->setBolOracle(true);
    $objVersaoSeiRN->setBolSqlServer(true);
    $objVersaoSeiRN->setBolPostgreSql(true);
    $objVersaoSeiRN->setBolErroVersaoInexistente(true);

    $objVersaoSeiRN->atualizarVersao();

	}catch(Exception $e){
		echo(InfraException::inspecionar($e));
		try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
		exit(1);
	}
?>