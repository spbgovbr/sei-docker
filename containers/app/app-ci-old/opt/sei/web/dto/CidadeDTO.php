<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class CidadeDTO extends InfraDTO {
  private $numTipoFkUf = null;

  public function getStrNomeTabela() {
  	 return 'cidade';
  }

 public function __construct(){
    $this->numTipoFkUf = InfraDTO::$TIPO_FK_OPCIONAL;
    parent::__construct();
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCidade',  'id_cidade');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUf',      'id_uf');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome',      'nome');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPais',    'id_pais');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'CodigoIbge','codigo_ibge');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinCapital','sin_capital');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'Latitude',  'latitude');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'Longitude', 'longitude');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUf', 'sigla', 'uf');
     $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUf', 'nome', 'uf');
     $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Pais', 'nome', 'pais');

    $this->configurarPK('IdCidade',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdUf', 'uf', 'id_uf',$this->getNumTipoFkUf());
    $this->configurarFK('IdPais', 'pais', 'id_pais',InfraDTO::$TIPO_FK_OPCIONAL);
  }

 public function getNumTipoFkUf(){
    return $this->numTipoFkUf;
  }

  public function setNumTipoFkUf($numTipoFkUf){
    $this->numTipoFkUf = $numTipoFkUf;
  }
}
?>