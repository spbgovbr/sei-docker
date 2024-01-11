<?

require_once dirname(__FILE__) . '/../web/Sip.php';

class VersaoSipRN extends InfraScriptVersao {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  public function versao_3_0_0($strVersaoAtual) {

  }

  public function versao_3_1_0($strVersaoAtual) {
    try {
      if (BancoSip::getInstance() instanceof InfraMySql) {
        $objScriptRN = new ScriptRN();
        $objScriptRN->atualizarSequencias();
      }

      InfraDebug::getInstance()->setBolDebugInfra(true);

      $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());
      $objInfraMetaBD->setBolValidarIdentificador(true);

      $numIdSistemaSip = ScriptSip::obterIdSistema('SIP');

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->setStrSiglaOrgao(ConfiguracaoSip::getInstance()->getValor('SessaoSip', 'SiglaOrgaoSistema'));
      $objUnidadeDTO->setStrSigla('TESTE');

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

      if ($objUnidadeDTO == null) {
        throw new InfraException('Unidade de TESTE nгo encontrada no уrgгo ' . ConfiguracaoSip::getInstance()->getValor('SessaoSip', 'SiglaOrgaoSistema') . '.');
      }

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $objInfraParametro->setValor('ID_UNIDADE_TESTE', $objUnidadeDTO->getNumIdUnidade());

      BancoSip::getInstance()->executarSql('CREATE TABLE infra_erro_php (
          id_infra_erro_php     ' . $objInfraMetaBD->tipoTextoVariavel(32) . '  NOT NULL ,
          sta_tipo              ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          arquivo               ' . $objInfraMetaBD->tipoTextoVariavel(255) . '  NOT NULL ,
          linha                 ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
          erro                  ' . $objInfraMetaBD->tipoTextoVariavel(4000) . '  NOT NULL ,
          dth_cadastro          ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL)
        ');

      $objInfraMetaBD->adicionarChavePrimaria('infra_erro_php', 'pk_infra_erro_php', array('id_infra_erro_php'));

      $rs = BancoSip::getInstance()->consultarSql('select max(id_usuario_historico) as ultimo from usuario_historico');

      $objInfraSequenciaDTO = new InfraSequenciaDTO();
      $objInfraSequenciaDTO->setStrNome('usuario_historico');

      if (count($rs) == 0) {
        $objInfraSequenciaDTO->setDblNumAtual(0);
      } else {
        $objInfraSequenciaDTO->setDblNumAtual($rs[0]['ultimo'] + 1);
      }

      $objInfraSequenciaDTO->setDblNumMaximo(999999999);
      $objInfraSequenciaDTO->setDblQtdIncremento(1);

      $objInfraSequenciaRN = new InfraSequenciaRN();
      $objInfraSequenciaRN->cadastrar($objInfraSequenciaDTO);

      if (BancoSip::getInstance() instanceof InfraMySql || BancoSip::getInstance() instanceof InfraSqlServer){
        BancoSip::getInstance()->executarSql('drop table seq_usuario_historico');
      }else if (BancoSip::getInstance() instanceof InfraOracle || BancoSip::getInstance() instanceof InfraPostgreSql){
        BancoSip::getInstance()->executarSql('drop sequence seq_usuario_historico');
      }

      $objInfraMetaBD->alterarColuna('infra_agendamento_tarefa', 'periodicidade_complemento', $objInfraMetaBD->tipoTextoVariavel(200), 'null');

      $numIdPerfilSipAdministradorSip = ScriptSip::obterIdPerfil($numIdSistemaSip, 'Administrador SIP');
      $numIdPerfilSipAdministradorSistema = ScriptSip::obterIdPerfil($numIdSistemaSip, 'Administrador de Sistema');
      $numIdPerfilSipBasico = ScriptSip::obterIdPerfil($numIdSistemaSip, 'Bбsico');
      $numIdPerfilSipCoordenadorPerfil = ScriptSip::obterIdPerfil($numIdSistemaSip, 'Coordenador de Perfil');
      $numIdPerfilSipCoordenadorUnidade = ScriptSip::obterIdPerfil($numIdSistemaSip, 'Coordenador de Unidade');
      $numIdPerfilSipAdministradorSip = ScriptSip::obterIdPerfil($numIdSistemaSip, 'Administrador SIP');
      $numIdPerfilSipCadastroUsuariosUnidades = ScriptSip::obterIdPerfil($numIdSistemaSip, 'Cadastro de Usuбrios e Unidades');

      $numIdMenuSip = ScriptSip::obterIdMenu($numIdSistemaSip, 'Principal');
      $numIdItemMenuSipAutenticacao2fa = ScriptSip::obterIdItemMenu($numIdSistemaSip, $numIdMenuSip, 'Autenticaзгo em 2 Fatores');
      $numIdItemMenuSipInfra = ScriptSip::obterIdItemMenu($numIdSistemaSip, $numIdMenuSip, 'Infra');


      InfraDebug::getInstance()->setBolDebugInfra(true);

      $this->logar('ATUALIZANDO PARAMETROS...');

      $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SIP_EMAIL_SISTEMA\'');
      if ($rs[0]['total'] == 0) {
        $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'EMAIL_SISTEMA\'');
        if ($rs[0]['total'] == 1) {
          BancoSip::getInstance()->executarSql('update infra_parametro set nome=\'SIP_EMAIL_SISTEMA\' where nome=\'EMAIL_SISTEMA\'');
        } else {
          BancoSip::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SIP_EMAIL_SISTEMA\',\'\')');
        }
      } else {
        $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'EMAIL_SISTEMA\'');
        if ($rs[0]['total'] == 1) {
          BancoSip::getInstance()->executarSql('delete from infra_parametro where nome=\'EMAIL_SISTEMA\'');
        }
      }

      $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SIP_EMAIL_ADMINISTRADOR\'');
      if ($rs[0]['total'] == 0) {
        $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'EMAIL_ADMINISTRADOR\'');
        if ($rs[0]['total'] == 1) {
          BancoSip::getInstance()->executarSql('update infra_parametro set nome=\'SIP_EMAIL_ADMINISTRADOR\' where nome=\'EMAIL_ADMINISTRADOR\'');
        } else {
          BancoSip::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SIP_EMAIL_ADMINISTRADOR\',\'\')');
        }
      } else {
        $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'EMAIL_ADMINISTRADOR\'');
        if ($rs[0]['total'] == 1) {
          BancoSip::getInstance()->executarSql('delete from infra_parametro where nome=\'EMAIL_ADMINISTRADOR\'');
        }
      }

      $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SIP_FORMATAR_SIGLA_USUARIO\'');
      if ($rs[0]['total'] == 0) {
        BancoSip::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SIP_FORMATAR_SIGLA_USUARIO\',\'1\')');
      }

      $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SIP_FORMATAR_NOME_USUARIO\'');
      if ($rs[0]['total'] == 0) {
        BancoSip::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SIP_FORMATAR_NOME_USUARIO\',\'1\')');
      }

      $objInfraMetaBD->alterarColuna('recurso', 'nome', $objInfraMetaBD->tipoTextoVariavel(100), 'not null');

      BancoSip::getInstance()->executarSql('CREATE TABLE grupo_perfil(
        id_grupo_perfil       ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
        id_sistema            ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
        nome                  ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
        sin_ativo             ' . $objInfraMetaBD->tipoTextoFixo(1) . '  NOT NULL
      )');

      $objInfraMetaBD->adicionarChavePrimaria('grupo_perfil', 'pk_grupo_perfil', array('id_grupo_perfil', 'id_sistema'));

      $objInfraMetaBD->adicionarChaveEstrangeira('fk_grupo_perfil_sistema', 'grupo_perfil', array('id_sistema'), 'sistema', array('id_sistema'));

      $objInfraSequenciaDTO = new InfraSequenciaDTO();
      $objInfraSequenciaDTO->setStrNome('grupo_perfil');
      $objInfraSequenciaDTO->setDblNumAtual(0);
      $objInfraSequenciaDTO->setDblNumMaximo(999999999);
      $objInfraSequenciaDTO->setDblQtdIncremento(1);

      $objInfraSequenciaRN = new InfraSequenciaRN();
      $objInfraSequenciaRN->cadastrar($objInfraSequenciaDTO);

      BancoSip::getInstance()->executarSql('CREATE TABLE rel_grupo_perfil_perfil(
        id_grupo_perfil       ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
        id_sistema            ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
        id_perfil             ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL
      )');

      $objInfraMetaBD->adicionarChavePrimaria('rel_grupo_perfil_perfil', 'pk_rel_grupo_perfil_perfil', array('id_grupo_perfil', 'id_sistema', 'id_perfil'));

      $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_grupo_perf_perf_grupo', 'rel_grupo_perfil_perfil', array('id_grupo_perfil', 'id_sistema'), 'grupo_perfil', array('id_grupo_perfil', 'id_sistema'));
      $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_grupo_perf_perf_perfil', 'rel_grupo_perfil_perfil', array('id_perfil', 'id_sistema'), 'perfil', array('id_perfil', 'id_sistema'));

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'grupo_perfil_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSistema, 'grupo_perfil_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'grupo_perfil_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSistema, 'grupo_perfil_alterar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'grupo_perfil_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSistema, 'grupo_perfil_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'grupo_perfil_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSistema, 'grupo_perfil_desativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'grupo_perfil_reativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSistema, 'grupo_perfil_reativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipBasico, 'grupo_perfil_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCoordenadorPerfil, 'grupo_perfil_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipBasico, 'grupo_perfil_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCoordenadorPerfil, 'grupo_perfil_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipBasico, 'grupo_perfil_selecionar');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'rel_grupo_perfil_perfil_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSistema, 'rel_grupo_perfil_perfil_cadastrar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'rel_grupo_perfil_perfil_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSistema, 'rel_grupo_perfil_perfil_excluir');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipBasico, 'rel_grupo_perfil_perfil_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCoordenadorPerfil, 'rel_grupo_perfil_perfil_consultar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipBasico, 'rel_grupo_perfil_perfil_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCoordenadorPerfil, 'rel_grupo_perfil_perfil_listar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipBasico, 'rel_grupo_perfil_perfil_selecionar');

      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipBasico, 'infra_captcha_listar');


      $rs = BancoSip::getInstance()->consultarSql('select count(*) as total from infra_parametro where nome = \'SIP_TIPO_CAPTCHA\'');
      if ($rs[0]['total'] == 0) {
        BancoSip::getInstance()->executarSql('insert into infra_parametro (nome, valor) values (\'SIP_TIPO_CAPTCHA\',\'5\')');
      }

      BancoSip::getInstance()->executarSql('CREATE TABLE infra_captcha (
            identificacao         ' . $objInfraMetaBD->tipoTextoVariavel(50) . '  NOT NULL ,
            dia                   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            mes                   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            ano                   ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
            acertos               ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL ,
            erros                 ' . $objInfraMetaBD->tipoNumeroGrande() . '  NOT NULL
          )');

      $objInfraMetaBD->adicionarChavePrimaria('infra_captcha', 'pk_infra_captcha', array('identificacao', 'dia', 'mes', 'ano'));

      BancoSip::getInstance()->executarSql('CREATE TABLE infra_captcha_tentativa (
          identificacao         ' . $objInfraMetaBD->tipoTextoVariavel(50) . '  NOT NULL ,
          id_usuario_origem     ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  NOT NULL ,
          tentativas            ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
          dth_tentativa         ' . $objInfraMetaBD->tipoDataHora() . '  NOT NULL ,
          user_agent            ' . $objInfraMetaBD->tipoTextoVariavel(500) . '  NOT NULL ,
          ip                    ' . $objInfraMetaBD->tipoTextoVariavel(15) . '  NOT NULL 
        )');

      $objInfraMetaBD->adicionarChavePrimaria('infra_captcha_tentativa', 'pk_infra_captcha_tentativa', array('identificacao', 'id_usuario_origem'));

      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'infra_captcha_listar');
      ScriptSip::adicionarItemMenu($numIdSistemaSip, $numIdPerfilSipAdministradorSip, $numIdMenuSip, $numIdItemMenuSipInfra, $objRecursoDTO->getNumIdRecurso(), 'Captcha', 0);

      $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'sistema_configurar');
      ScriptSip::adicionarItemMenu($numIdSistemaSip, $numIdPerfilSipAdministradorSip, $numIdMenuSip, $numIdItemMenuSipInfra, $objRecursoDTO->getNumIdRecurso(), 'Configuraзгo do Sistema', 0);

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'usuario_historico_excluir');

      $objInfraMetaBD->adicionarColuna('perfil', 'sin_2_fatores', $objInfraMetaBD->tipoTextoFixo(1), 'null');
      BancoSip::getInstance()->executarSql('update perfil set sin_2_fatores=\'N\'');
      $objInfraMetaBD->alterarColuna('perfil', 'sin_2_fatores', $objInfraMetaBD->tipoTextoFixo(1), 'not null');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'usuario_pausar_2fa');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipAdministradorSip, 'usuario_remover_pausa_2fa');

      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCadastroUsuariosUnidades, 'codigo_acesso_desativar');
      ScriptSip::adicionarItemMenu($numIdSistemaSip, $numIdPerfilSipCadastroUsuariosUnidades, $numIdMenuSip, null, ScriptSip::obterIdRecurso($numIdSistemaSip, 'codigo_acesso_listar'), 'Autenticaзгo em 2 Fatores', 0);
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCadastroUsuariosUnidades, 'codigo_acesso_reativar');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCadastroUsuariosUnidades, 'usuario_bloquear');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCadastroUsuariosUnidades, 'usuario_desbloquear');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCadastroUsuariosUnidades, 'usuario_pausar_2fa');
      ScriptSip::adicionarRecursoPerfil($numIdSistemaSip, $numIdPerfilSipCadastroUsuariosUnidades, 'usuario_remover_pausa_2fa');

      BancoSip::getInstance()->executarSql('insert into infra_parametro (nome,valor) values (\'SIP_2_FATORES_TEMPO_DIAS_PAUSA_USUARIO\',\'3\')');

      $objInfraMetaBD->adicionarColuna('usuario', 'dth_pausa_2fa', $objInfraMetaBD->tipoDataHora(), 'null');
      $objInfraMetaBD->adicionarColuna('usuario_historico', 'dth_pausa_2fa', $objInfraMetaBD->tipoDataHora(), 'null');

      $objInfraMetaBD->criarIndice('usuario', 'i07_usuario', array('sin_bloqueado'));
      $objInfraMetaBD->criarIndice('usuario', 'i08_usuario', array('dth_pausa_2fa'));

      ScriptSip::adicionarAuditoria($numIdSistemaSip, 'Geral', array(
        'grupo_perfil_cadastrar',
        'grupo_perfil_alterar',
        'grupo_perfil_excluir',
        'grupo_perfil_desativar',
        'grupo_perfil_reativar',
        'sistema_configurar'
      ));

      InfraDebug::getInstance()->setBolDebugInfra(false);

      $this->fixIndices31($objInfraMetaBD);
    } catch (Throwable $e) {
      throw new InfraException('Erro atualizando versгo.', $e);
    }
  }

  protected function fixIndices30(InfraMetaBD $objInfraMetaBD) {
    InfraDebug::getInstance()->setBolDebugInfra(true);

    $this->logar('ATUALIZANDO INDICES...');

    $objInfraMetaBD->processarIndicesChavesEstrangeiras(array(
      'administrador_sistema', 'coordenador_perfil', 'coordenador_unidade', 'dtproperties', 'hierarquia', 'item_menu',
      'login', 'menu', 'orgao', 'perfil', 'permissao', 'recurso', 'recurso_vinculado', 'regra_auditoria',
      'rel_hierarquia_unidade', 'rel_orgao_autenticacao', 'rel_perfil_item_menu', 'rel_perfil_recurso',
      'rel_regra_auditoria_recurso', 'servidor_autenticacao', 'sistema', 'tipo_permissao', 'unidade', 'usuario',
      'codigo_acesso', 'usuario_historico', 'codigo_bloqueio', 'dispositivo_acesso', 'email_sistema'
    ));

    InfraDebug::getInstance()->setBolDebugInfra(false);
  }

  protected function fixIndices31(InfraMetaBD $objInfraMetaBD) {
    InfraDebug::getInstance()->setBolDebugInfra(true);

    $this->logar('ATUALIZANDO INDICES...');

    $objInfraMetaBD->processarIndicesChavesEstrangeiras(array(
      'administrador_sistema', 'coordenador_perfil', 'coordenador_unidade', 'dtproperties', 'hierarquia', 'item_menu',
      'login', 'menu', 'orgao', 'perfil', 'permissao', 'recurso', 'recurso_vinculado', 'regra_auditoria',
      'rel_hierarquia_unidade', 'rel_orgao_autenticacao', 'rel_perfil_item_menu', 'rel_perfil_recurso',
      'rel_regra_auditoria_recurso', 'servidor_autenticacao', 'sistema', 'tipo_permissao', 'unidade', 'usuario',
      'codigo_acesso', 'usuario_historico', 'codigo_bloqueio', 'dispositivo_acesso', 'email_sistema', 'grupo_perfil',
      'rel_grupo_perfil_perfil'
    ));

    InfraDebug::getInstance()->setBolDebugInfra(false);
  }
}

try {
  session_start();

  SessaoSip::getInstance(false);

  $objInfraParametro = new InfraParametro(BancoSip::getInstance());

  if (!$objInfraParametro->isSetValor('SIP_VERSAO')) {
    die("\n\nVERSAO DO SIP NAO IDENTIFICADA (REQUER 3.0.*)\n");
  }

  $strVersaoBancoSip = $objInfraParametro->getValor('SIP_VERSAO');

  if (substr($strVersaoBancoSip,0,3)!='3.0'){
    die("\n\nVERSAO DO SIP INSTALADA " . $strVersaoBancoSip . " INCOMPATIVEL (REQUER 3.0.*)\n");
  }

  $objVersaoSipRN = new VersaoSipRN();
  $objVersaoSipRN->setStrNome('SIP');
  $objVersaoSipRN->setStrVersaoAtual(SIP_VERSAO);
  $objVersaoSipRN->setStrParametroVersao('SIP_VERSAO');
  $objVersaoSipRN->setArrVersoes(array(
    '3.0.*' => 'versao_3_0_0',
    '3.1.*' => 'versao_3_1_0'
  ));
  $objVersaoSipRN->setStrVersaoInfra('2.0.11');
  $objVersaoSipRN->setBolMySql(true);
  $objVersaoSipRN->setBolOracle(true);
  $objVersaoSipRN->setBolSqlServer(true);
  $objVersaoSipRN->setBolPostgreSql(true);
  $objVersaoSipRN->setBolErroVersaoInexistente(true);

  $objVersaoSipRN->atualizarVersao();

} catch (Throwable $e) {
  echo(InfraException::inspecionar($e));
  try {
    LogSip::getInstance()->gravar(InfraException::inspecionar($e));
  } catch (Exception $e) {
  }
  exit(1);
}
?>