<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'grupo_federacao';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdGrupoFederacao',
                                   'id_grupo_federacao');

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
                                   
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelGrupoFedOrgaoFedDTO');
    
    $this->configurarPK('IdGrupoFederacao', InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
?>