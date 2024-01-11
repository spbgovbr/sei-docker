<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoContatoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'tipo_contato';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoContato',
                                   'id_tipo_contato');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaAcesso',
                                   'sta_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinSistema',
                                   'sin_sistema');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

     $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelUnidadeTipoContatoDTO');
  	 
    $this->configurarPK('IdTipoContato', InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>