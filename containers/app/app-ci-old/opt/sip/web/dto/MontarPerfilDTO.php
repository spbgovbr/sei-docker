<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class MontarPerfilDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return "recurso";
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSistema',
                                   'id_sistema');
    
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdRecurso',
                                   'id_recurso');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');
  	 
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdPerfilRelPerfilRecurso',
                                              'id_perfil',
                                              'rel_perfil_recurso');

		 $this->configurarFK('IdSistema', 'rel_perfil_recurso', 'id_sistema');
     $this->configurarFK('IdRecurso', 'rel_perfil_recurso', 'id_recurso');
     
     $this->configurarPK('IdRecurso',InfraDTO::$TIPO_PK_SEQUENCIAL);
     
  }
}
?>