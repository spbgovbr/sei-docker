<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/08/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SerieEscolhaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'serie_escolha';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'NomeSerie',
					                                   'nome',
					                                   'serie');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																							'StaAplicabilidadeSerie',
																							'sta_aplicabilidade',
																							'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                            'SinInternoSerie',
                                            'sin_interno',
                                            'serie');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'SinAtivoSerie',
					                                   'sin_ativo',
					                                   'serie');
		
    $this->configurarPK('IdSerie',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSerie', 'serie', 'id_serie');
  }
}
?>