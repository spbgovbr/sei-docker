<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MonitoramentoServicoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'monitoramento_servico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdMonitoramentoServico',
                                   'id_monitoramento_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdServico',
                                   'id_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Operacao',
                                   'operacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'TempoExecucao',
                                   'tempo_execucao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IpAcesso',
                                   'ip_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Acesso',
                                   'dth_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Servidor',
                                   'servidor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'UserAgent',
                                   'user_agent');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'IdentificacaoServico',
                                              'identificacao',
                                              'servico');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUsuarioServico',
                                              'id_usuario',
                                              'servico');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuarioServico',
                                              'sigla',
                                              'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUsuarioServico',
                                              'nome',
                                              'usuario');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaTipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Inicial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Final');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Total');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'TempoMedio');

    $this->configurarFK('IdServico', 'servico', 'id_servico');
    $this->configurarFK('IdUsuarioServico', 'usuario', 'id_usuario');

    $this->configurarPK('IdMonitoramentoServico',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>