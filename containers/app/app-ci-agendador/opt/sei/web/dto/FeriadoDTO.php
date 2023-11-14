<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class FeriadoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'feriado';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdFeriado',
                                   'id_feriado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdOrgao',
                                   'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Feriado',
                                   'dta_feriado');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgao',
                                              'sigla',
                                              'orgao');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'Inicial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'Final');

    $this->configurarPK('IdFeriado',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdOrgao', 'orgao', 'id_orgao',InfraDTO::$TIPO_FK_OPCIONAL);
  }
}
?>