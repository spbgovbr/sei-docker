<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: SecaoModeloDTO.php 9075 2014-07-24 16:51:34Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class SecaoModeloDTO extends InfraDTO {

  private $numTipoPK = null;

  public function __construct(){
    parent::__construct();
    $this->numTipoPK = InfraDTO::$TIPO_PK_NATIVA;
  }

  public function setNumTipoPK($numTipoPK){
    $this->numTipoPK = $numTipoPK;
  }

  public function getStrNomeTabela() {
  	 return 'secao_modelo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSecaoModelo', 'id_secao_modelo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdModelo', 'id_modelo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Conteudo', 'conteudo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Ordem', 'ordem');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinSomenteLeitura', 'sin_somente_leitura');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAssinatura', 'sin_assinatura');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinPrincipal', 'sin_principal');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDinamica', 'sin_dinamica');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinHtml', 'sin_html');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinCabecalho',
                                   'sin_cabecalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinRodape',
                                   'sin_rodape');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeModelo', 'nome', 'modelo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelSecaoModeloEstiloDTO');
    $this->configurarPK('IdSecaoModelo',$this->numTipoPK);
    $this->configurarFK('IdModelo', 'modelo', 'id_modelo');
    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
?>