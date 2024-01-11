<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AndamentoSituacaoDTO extends InfraDTO {

  private $numTipoFkSituacao;

  public function __construct(){
    $this->numTipoFkSituacao = InfraDTO::$TIPO_FK_OPCIONAL;
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'andamento_situacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdAndamentoSituacao','id_andamento_situacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,'Execucao','dth_execucao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,'IdProcedimento','id_procedimento');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdUnidade','id_unidade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdUsuario','id_usuario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdSituacao','id_situacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinUltimo','sin_ultimo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUsuario','sigla','usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeUsuario','nome','usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeSituacao','nome','situacao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinAtivoSituacao','sin_ativo','situacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdProcedimentoProcedimento','id_procedimento','procedimento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdTipoProcedimentoProcedimento','id_tipo_procedimento','procedimento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeTipoProcedimento','nome','tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaNivelAcessoGlobalProtocolo','sta_nivel_acesso_global','protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoProtocolo','protocolo_formatado','protocolo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdControleUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinSituacoesDesativadas');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'GraficoPorSituacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'GraficoGeral');

    $this->configurarPK('IdAndamentoSituacao',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdUsuario','usuario','id_usuario');
    $this->configurarFK('IdSituacao','situacao','id_situacao',$this->getNumTipoFkSituacao());
    $this->configurarFK('IdProcedimento','procedimento','id_procedimento');
    $this->configurarFK('IdTipoProcedimentoProcedimento','tipo_procedimento','id_tipo_procedimento');
    $this->configurarFK('IdProcedimentoProcedimento','protocolo','id_protocolo');
  }

  public function getNumTipoFkSituacao(){
    return $this->numTipoFkSituacao;
  }

  public function setNumTipoFkSituacao($numTipoFkSituacao){
    $this->numTipoFkSituacao = $numTipoFkSituacao;
  }
}
?>