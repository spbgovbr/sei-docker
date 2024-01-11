<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoContatoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'grupo_contato';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdGrupoContato',
                                   'id_grupo_Contato');

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
                                   
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelGrupoContatoDTO');
    
    $this->configurarPK('IdGrupoContato', InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
?>