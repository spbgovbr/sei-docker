<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/07/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../../SEI.php';

class RelSecaoModCjEstilosItemDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_secao_mod_cj_estilos_item';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdSecaoModelo','id_secao_modelo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdConjuntoEstilosItem','id_conjunto_estilos_item');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinPadrao','sin_padrao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'OrdemSecaoModelo',	'ordem', 'secao_modelo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoSecaoModelo','sin_ativo', 'secao_modelo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdModelo','id_modelo','secao_modelo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeEstilo','nome','conjunto_estilos_item');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'Formatacao','formatacao','conjunto_estilos_item');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdConjuntoEstilos','id_conjunto_estilos','conjunto_estilos_item');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinUltimoConjuntoEstilos','sin_ultimo','conjunto_estilos');

    $this->configurarPK('IdSecaoModelo',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdConjuntoEstilosItem',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSecaoModelo', 'secao_modelo', 'id_secao_modelo');
    $this->configurarFK('IdConjuntoEstilosItem', 'conjunto_estilos_item', 'id_conjunto_estilos_item');
    $this->configurarFK('IdConjuntoEstilos', 'conjunto_estilos', 'id_conjunto_estilos');

  }
}
?>