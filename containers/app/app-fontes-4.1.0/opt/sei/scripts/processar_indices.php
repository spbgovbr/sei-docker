<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  InfraDebug::getInstance()->setBolLigado(true);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  if (substr(SEI_VERSAO,0,3) != '4.1'){
    throw new InfraException('Versao do SEI '.SEI_VERSAO.' incompativel (versao requerida 4.1.x)');
  }

  InfraDebug::getInstance()->gravar('INICIANDO PROCESSAMENTO DE INDICES...');

  BancoSEI::getInstance()->abrirConexao();

  $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

  $arrTabelas = array('acesso', 'acesso_externo', 'acompanhamento', 'andamento_marcador', 'andamento_situacao', 'anexo', 'anotacao',
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
    'acao_federacao', 'parametro_acao_federacao','plano_trabalho','etapa_trabalho','item_etapa','rel_item_etapa_unidade',
    'rel_item_etapa_serie','rel_item_etapa_documento','tarefa_plano_trabalho',
    'andamento_plano_trabalho','atributo_andam_plano_trab','rel_serie_plano_trabalho','aviso', 'rel_aviso_orgao',
    'reabertura_programada', 'documento_geracao', 'avaliacao_documental', 'cpad', 'cpad_versao', 'cpad_composicao',
    'cpad_avaliacao', 'edital_eliminacao', 'edital_eliminacao_conteudo', 'edital_eliminacao_erro', 'rel_orgao_pesquisa',
    'tipo_prioridade', 'rel_usuario_tipo_prioridade');

  sort($arrTabelas);

  $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas);

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