<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'edital_eliminacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdEditalEliminacao', 'id_edital_eliminacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdDocumento', 'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Especificacao', 'especificacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Publicacao', 'dta_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaEditalEliminacao', 'sta_edital_eliminacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'ProcedimentoFormatado',
      'ppc.protocolo_formatado',
      'protocolo ppc');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
      'IdDocumento',
      'pdc.id_protocolo',
      'protocolo pdc');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DocumentoFormatado',
      'pdc.protocolo_formatado',
      'protocolo pdc');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
      'PublicacaoPublicacao',
      'pub.dta_publicacao',
      'publicacao pub');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdOrgaoUnidade',
      'u.id_orgao',
      'unidade u');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidade',
        'u.sigla',
        'unidade u');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAvaliacaoDocumentalDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjEditalEliminacaoConteudoDTO');

    $this->configurarPK('IdEditalEliminacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdProcedimento', 'procedimento pc', 'pc.id_procedimento');
    $this->configurarFK('IdProcedimento', 'protocolo ppc', 'ppc.id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_ON);
    $this->configurarFK('IdDocumento', 'protocolo pdc', 'pdc.id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_ON);
    $this->configurarFK('IdDocumento', 'publicacao pub', 'pub.id_documento', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_ON);
    $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade');


  }
}
