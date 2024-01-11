<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: ModeloDTO.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ModeloDTO extends InfraDTO {
  
  private $numTipoPK = null;
  
  public function __construct(){
    parent::__construct();
    $this->numTipoPK = InfraDTO::$TIPO_PK_NATIVA;
  }

  public function setNumTipoPK($numTipoPK){
    $this->numTipoPK = $numTipoPK;
  }
  
  public function getStrNomeTabela() {
  	 return 'modelo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdModelo',
                                   'id_modelo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->configurarPK('IdModelo',$this->numTipoPK);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>