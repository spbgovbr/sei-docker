<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ItemMenuDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return "item_menu";
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMenu',
                                   'id_menu');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdItemMenu',
                                   'id_item_menu');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSistema',
                                   'id_sistema');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMenuPai',
                                   'id_menu_pai');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdItemMenuPai',
                                   'id_item_menu_pai');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdRecurso',
                                   'id_recurso');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Rotulo',
                                   'rotulo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'Icone',
                                    'icone');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Sequencia',
                                   'sequencia');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinNovaJanela',
                                   'sin_nova_janela');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeMenu',
                                              'nome',
                                              'menu');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdSistemaMenu',
                                              'id_sistema',
                                              'menu');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoSistema',
                                              'id_orgao',
                                              'sistema');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoSistema',
                                              'sigla',
                                              'orgao');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeRecurso',
                                              'nome',
                                              'recurso');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'CaminhoRecurso',
                                              'caminho',
                                              'recurso');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoRecurso',
                                              'sin_ativo',
                                              'recurso');
                                              
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Ramificacao');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Nivel');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Pais');
		
		
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPerfil');
		

    $this->configurarPK('IdMenu',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdItemMenu',InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarFK('IdMenu', 'menu', 'id_menu');
		
    //$this->configurarFK('IdSistema', 'recurso', 'id_sistema');
    $this->configurarFK('IdRecurso', 'recurso', 'id_recurso', InfraDTO::$TIPO_FK_OPCIONAL);
		
    //$this->configurarFK('IdMenuPai', 'item_menu ar', 'ar.id_menu_pai');
    //$this->configurarFK('IdItemMenuPai', 'item_menu ar', 'ar.id_item_menu_pai');
		
		$this->configurarFK('IdSistemaMenu', 'sistema', 'id_sistema');
		
		$this->configurarFK('IdOrgaoSistema', 'orgao', 'id_orgao');
		
    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>