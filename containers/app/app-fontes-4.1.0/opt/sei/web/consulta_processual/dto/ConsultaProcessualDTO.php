<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/03/2023 - criado por mgb29
*
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ConsultaProcessualDTO  extends InfraDTO {

  private $numTipoFkParticipante = null;

  public function __construct(){
    $this->numTipoFkParticipante = InfraDTO::$TIPO_FK_OPCIONAL;
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
                                   'StaProtocolo',
                                   'sta_protocolo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaEstado',
                                   'sta_estado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinEliminado',
                                  'sin_eliminado');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaNivelAcessoGlobal',
                                   'sta_nivel_acesso_global');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeGeradora',
                                   'id_unidade_geradora');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Geracao',
                                   'dta_geracao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUnidadeGeradora',
      'uni_ger.sigla',
      'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoUnidadeGeradora',
      'uni_ger.descricao',
      'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdOrgaoUnidadeGeradora',
      'uni_ger.id_orgao',
      'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaOrgaoUnidadeGeradora',
      'sigla',
      'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoOrgaoUnidadeGeradora',
      'descricao',
      'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SinConsultaProcessualOrgaoUnidadeGeradora',
      'sin_consulta_processual',
      'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdTipoProcedimento',
      'p.id_tipo_procedimento',
      'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
      'IdProcedimento',
      'p.id_procedimento',
      'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeTipoProcedimento',
      'tp.nome',
      'tipo_procedimento tp');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SinOuvidoriaTipoProcedimento',
      'tp.sin_ouvidoria',
      'tipo_procedimento tp');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdContato',
      'id_contato',
      'participante');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaParticipacao',
      'sta_participacao',
      'participante');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
      'CpfContato',
      'cpf',
      'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
      'CnpjContato',
      'cnpj',
      'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaNaturezaContato',
      'sta_natureza',
      'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeContato',
      'nome',
      'contato');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaCriterioPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ValorPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'IdOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProtocoloConsulta');

    $this->configurarPK('IdProtocolo',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdProtocolo', 'procedimento p', 'p.id_procedimento');
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento tp', 'tp.id_tipo_procedimento');
    $this->configurarFK('IdUnidadeGeradora', 'unidade uni_ger', 'uni_ger.id_unidade');
    $this->configurarFK('IdOrgaoUnidadeGeradora', 'orgao', 'id_orgao');
    $this->configurarFK('IdProtocolo', 'participante', 'id_protocolo', $this->getNumTipoFkParticipante());
    $this->configurarFK('IdContato','contato','id_contato');
  }

  public function getNumTipoFkParticipante(){
    return $this->numTipoFkParticipante;
  }

  public function setNumTipoFkParticipante($numTipoFkParticipante){
    $this->numTipoFkParticipante = $numTipoFkParticipante;
  }
}
?>