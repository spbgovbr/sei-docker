<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2010 - criado por alexandre_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoEmailDTO extends InfraDTO {

	public function getStrNomeTabela() {
		return 'grupo_email';
	}

	public function montar() {

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
		'IdGrupoEmail',
		'id_grupo_email');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
		'IdUnidade',
		'id_unidade');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
		'Nome',
		'nome');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
		'Descricao',
		'descricao');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
		'StaTipo',
		'sta_tipo');
		
		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
		'SinAtivo',
		'sin_ativo');
		
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjEmailGrupoEmailDTO');

		$this->configurarPK('IdGrupoEmail', InfraDTO::$TIPO_PK_NATIVA );
		
		$this->configurarExclusaoLogica('SinAtivo', 'N');
	}
}
?>