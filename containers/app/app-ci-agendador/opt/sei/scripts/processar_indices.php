<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  InfraDebug::getInstance()->setBolLigado(true);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  if (substr(SEI_VERSAO,0,3) != '3.0'){
    throw new InfraException('Versao do SEI '.SEI_VERSAO.' incompativel (versao requerida 3.0.x)');
  }

  InfraDebug::getInstance()->gravar('INICIANDO PROCESSAMENTO DE INDICES...');

  BancoSEI::getInstance()->abrirConexao();

  $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

  $arrTabelas30 = array('acesso','acesso_externo','acompanhamento','andamento_marcador','andamento_situacao','anexo','anotacao',
      'arquivamento','arquivo_extensao','assinante','assinatura','assunto','assunto_proxy','atividade','atributo',
      'atributo_andamento','auditoria_protocolo','base_conhecimento','bloco','cargo','cargo_funcao','cidade',
      'conjunto_estilos','conjunto_estilos_item','contato','contexto','controle_interno','controle_unidade',
      'documento','documento_conteudo','dominio','email_grupo_email','email_sistema','email_unidade','email_utilizado',
      'estatisticas','estilo','feed','feriado','grupo_acompanhamento','grupo_contato','grupo_email','grupo_protocolo_modelo',
      'grupo_serie','grupo_unidade','hipotese_legal','imagem_formato','infra_agendamento_tarefa','infra_auditoria',
      'infra_dado_usuario','infra_log','infra_navegador','infra_parametro','infra_regra_auditoria','infra_regra_auditoria_recurso',
      'infra_sequencia','localizador','lugar_localizador','mapeamento_assunto','marcador','modelo','monitoramento_servico',
      'nivel_acesso_permitido','notificacao','novidade','numeracao','observacao','operacao_servico','ordenador_despesa',
      'orgao','pais','participante','procedimento','protocolo','protocolo_modelo','publicacao','publicacao_legado',
      'rel_acesso_ext_protocolo','rel_assinante_unidade','rel_base_conhec_tipo_proced','rel_bloco_protocolo',
      'rel_bloco_unidade','rel_controle_interno_orgao','rel_controle_interno_serie','rel_controle_interno_tipo_proc',
      'rel_controle_interno_unidade','rel_grupo_contato','rel_grupo_unidade_unidade','rel_notificacao_documento',
      'rel_protocolo_assunto','rel_protocolo_atributo','rel_protocolo_protocolo','rel_secao_mod_cj_estilos_item',
      'rel_secao_modelo_estilo','rel_serie_assunto','rel_serie_veiculo_publicacao','rel_situacao_unidade',
      'rel_tipo_procedimento_assunto','rel_unidade_tipo_contato','retorno_programado','secao_documento','secao_imprensa_nacional',
      'secao_modelo','serie','serie_escolha','serie_publicacao','serie_restricao',
      'servico','situacao','tabela_assuntos','tarefa','tarja_assinatura','texto_padrao_interno',
      'tipo_conferencia','tipo_contato','tipo_formulario','tipo_localizador','tipo_proced_restricao','tipo_procedimento',
      'tipo_procedimento_escolha','tipo_suporte','tratamento','uf','unidade','unidade_publicacao','usuario',
      'veiculo_imprensa_nacional','veiculo_publicacao','velocidade_transferencia','versao_secao_documento',
      'vocativo');

  sort($arrTabelas30);

  $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas30);

  BancoSEI::getInstance()->fecharConexao();

  InfraDebug::getInstance()->gravar('FIM');

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>