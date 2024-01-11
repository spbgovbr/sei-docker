<?
try{

  require_once dirname(__FILE__).'/../web/Sip.php';

  session_start();

  SessaoSip::getInstance(false);

  InfraDebug::getInstance()->setBolLigado(true);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  if (substr(SIP_VERSAO,0,3) != '2.0'){
    throw new InfraException('Versao do SIP '.SIP_VERSAO.' incompativel (versao requerida 2.0.x)');
  }

  InfraDebug::getInstance()->gravar('INICIANDO PROCESSAMENTO DE INDICES...');

  BancoSip::getInstance()->abrirConexao();

  $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());

  $arrTabelas20 = array('administrador_sistema','contexto','coordenador_perfil','coordenador_unidade','grupo_rede','hierarquia',
                        'infra_agendamento_tarefa','infra_auditoria','infra_log','infra_parametro','infra_regra_auditoria','infra_regra_auditoria_recurso',
                        'infra_sequencia','item_menu','login','menu','orgao','perfil','permissao','recurso','recurso_vinculado',
                        'regra_auditoria','rel_grupo_rede_unidade','rel_hierarquia_unidade','rel_orgao_autenticacao','rel_perfil_item_menu',
                        'rel_perfil_recurso','rel_regra_auditoria_recurso','seq_infra_auditoria','seq_infra_log','servidor_autenticacao',
                        'sistema','tipo_permissao','unidade','usuario');

  sort($arrTabelas20);

  $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas20);

  BancoSip::getInstance()->fecharConexao();

  InfraDebug::getInstance()->gravar('FIM');

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSip::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>