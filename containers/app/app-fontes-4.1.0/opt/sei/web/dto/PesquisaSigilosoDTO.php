<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/06/2016 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PesquisaSigilosoDTO extends InfraDTO {

  private $numTipoFkParticipante = null;
  private $numTipoFkObservacao = null;

  public function __construct(){
    $this->numTipoFkParticipante = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkObservacao = InfraDTO::$TIPO_FK_OPCIONAL;
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'protocolo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
        'IdProtocolo',
        'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
        'ProtocoloFormatado',
        'protocolo_formatado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
        'ProtocoloFormatadoPesquisa',
        'protocolo_formatado_pesquisa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'ProtocoloFormatadoPesqInv',
      'protocolo_formatado_pesq_inv');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
        'StaProtocolo',
        'sta_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
        'Descricao',
        'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
        'Geracao',
        'dta_geracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
        'StaNivelAcessoGlobal',
        'sta_nivel_acesso_global');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdTipoProcedimento',
        'id_tipo_procedimento',
        'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeTipoProcedimento',
        'nome',
        'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdContatoParticipante',
        'id_contato',
        'participante');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'StaParticipacaoParticipante',
        'sta_participacao',
        'participante');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeObservacao',
        'id_unidade',
        'observacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'IdxObservacao',
        'idx_observacao',
        'observacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUsuarioAcesso',
        'id_usuario',
        'acesso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeAcesso',
        'id_unidade',
        'acesso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'StaTipoAcesso',
        'sta_tipo',
        'acesso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUsuarioAtividade',
        'id_usuario',
        'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeAtividade',
        'id_unidade',
        'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
        'ConclusaoAtividade',
        'dth_conclusao',
        'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdOrgaoUnidadeAtividade',
        'id_orgao',
        'unidade');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaAcessoUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdContatoUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAnotacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinObservacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAcompanhamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAbertosFechados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCredencialInativa');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'Fim');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroTipoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroInteressado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroObservacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroUsuarioCredencial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroPeriodoAutuacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroTramitacaoUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinFiltroCredencialInativa');


    $this->configurarFK('IdProtocolo', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdProtocolo', 'acesso', 'id_protocolo');
    $this->configurarFK('IdProtocolo', 'atividade', 'id_protocolo');
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');
    $this->configurarFK('IdProtocolo', 'participante', 'id_protocolo', $this->getNumTipoFkParticipante());
    $this->configurarFK('IdProtocolo', 'observacao', 'id_protocolo', $this->getNumTipoFkObservacao());
    $this->configurarFK('IdUnidadeAtividade', 'unidade', 'id_unidade');

  }

  public function getNumTipoFkParticipante(){
    return $this->numTipoFkParticipante;
  }

  public function setNumTipoFkParticipante($numTipoFkParticipante){
    $this->numTipoFkParticipante = $numTipoFkParticipante;
  }

  public function getNumTipoFkObservacao(){
    return $this->numTipoFkObservacao;
  }

  public function setNumTipoFkObservacao($numTipoFkObservacao){
    $this->numTipoFkObservacao = $numTipoFkObservacao;
  }

}
?>