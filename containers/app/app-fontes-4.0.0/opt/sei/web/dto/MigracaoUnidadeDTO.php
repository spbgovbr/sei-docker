<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/08/2012 - criado por mga
 *
 * Versão do Gerador de Código: 1.14.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class MigracaoUnidadeDTO extends InfraDTO {

	public function getStrNomeTabela() {
		return null;
	}

	public function montar() {
		$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidadeOrigem');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidadeDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PrefixoMigracao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcompanhamentoEspecial');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAssinatura');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinBlocoInterno');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinGrupoBloco');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinGrupoContato');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinGrupoEmail');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinGrupoUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinMarcadores');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinModelo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinTextoPadrao');
	}
}
?>