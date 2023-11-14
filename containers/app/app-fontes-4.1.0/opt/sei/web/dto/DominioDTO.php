<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class DominioDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'dominio';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdDominio',
                                   'id_dominio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtributo',
                                   'id_atributo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Valor',
                                   'valor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Rotulo',
                                   'rotulo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'Ordem',
                                  'ordem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinPadrao',
                                   'sin_padrao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeAtributo',
                                              'nome',
                                              'atributo');

    $this->configurarPK('IdDominio', InfraDTO::$TIPO_PK_NATIVA );
    

    $this->configurarFK('IdAtributo', 'atributo', 'id_atributo');
    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>