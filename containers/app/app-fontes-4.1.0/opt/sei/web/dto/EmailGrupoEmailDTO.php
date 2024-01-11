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

class EmailGrupoEmailDTO extends InfraDTO {

	public function getStrNomeTabela() {
		return 'email_grupo_email';
	}

	public function montar() {

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
																		'IdEmailGrupoEmail',
																		'id_email_grupo_email');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
																		'IdGrupoEmail',
																		'id_grupo_email');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
																		'Email',
																		'email');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
																		'Descricao',
																		'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'IdxEmailGrupoEmail',
                                    'idx_email_grupo_email');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeGrupoEmail',
                                              'id_unidade',
                                              'grupo_email');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaTipoGrupoEmail',
                                              'sta_tipo',
                                              'grupo_email');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');

		$this->configurarPK('IdEmailGrupoEmail', InfraDTO::$TIPO_PK_NATIVA);

		$this->configurarFK('IdGrupoEmail', 'grupo_email', 'id_grupo_email');
	}
}
?>