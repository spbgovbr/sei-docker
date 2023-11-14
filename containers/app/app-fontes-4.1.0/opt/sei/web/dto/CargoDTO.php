<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class CargoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'cargo';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdCargo',
                                   'id_cargo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                    'IdTratamento',
                                    'id_tratamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdVocativo',
                                  'id_vocativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdTitulo',
                                  'id_titulo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Expressao',
                                   'expressao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'StaGenero',
                                  'sta_genero');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ExpressaoTratamento',
                                              'expressao',
                                              'tratamento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ExpressaoVocativo',
                                              'expressao',
                                              'vocativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ExpressaoTitulo',
                                              'expressao',
                                              'titulo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'AbreviaturaTitulo',
                                              'abreviatura',
                                              'titulo');

    $this->configurarPK('IdCargo', InfraDTO::$TIPO_PK_NATIVA );
    

    $this->configurarExclusaoLogica('SinAtivo', 'N');

    $this->configurarFK('IdTratamento', 'tratamento', 'id_tratamento', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdVocativo', 'vocativo', 'id_vocativo', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdTitulo', 'titulo', 'id_titulo', InfraDTO::$TIPO_FK_OPCIONAL);


  }
}
?>