<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class PermissaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return "permissao";
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdPerfil',
                                   'id_perfil');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSistema',
                                   'id_sistema');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoPermissao',
                                   'id_tipo_permissao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinSubunidades',
                                   'sin_subunidades');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'DataInicio',
                                   'dta_inicio');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'DataFim',
                                   'dta_fim');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomePerfil',
                                              'nome',
                                              'perfil');
																							
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                      	      'SinAtivoPerfil',
                                      	      'sin_ativo',
                                      	      'perfil');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoTipoPermissao',
                                              'descricao',
                                              'tipo_permissao');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdSistemaPerfil',
                                              'id_sistema',
                                              'perfil');
		 
     //Dados de Sistema
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaSistema',
                                              'sigla',
                                              'sistema');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoSistema',
                                              'descricao',
                                              'sistema');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoSistema',
                                              'id_orgao',
                                              'sistema');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                        	     'SinAtivoSistema',
                                        	     'sin_ativo',
                                        	     'sistema');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoSistema',
                                              'a.sigla',
                                              'orgao a');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgaoSistema',
                                              'a.descricao',
                                              'orgao a');
  	 
     //Dados de Usuario

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																							'IdOrigemUsuario',
																							'id_origem',
																							'usuario');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUsuario',
                                              'id_orgao',
                                              'usuario');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuario',
                                              'sigla',
                                              'usuario');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUsuario',
                                              'nome',
                                              'usuario');
                                              
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoUsuario',
                                              'sin_ativo',
                                              'usuario');
                                              
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUsuario',
                                              'b.sigla',
                                              'orgao b');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgaoUsuario',
                                              'b.descricao',
                                              'orgao b');
  	 
     //Dados de Unidade

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																							'IdOrigemUnidade',
																							'id_origem',
																							'unidade');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidade',
                                              'descricao',
                                              'unidade');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidade',
                                              'id_orgao',
                                              'unidade');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                        	     'SinAtivoUnidade',
                                        	     'sin_ativo',
                                        	     'unidade');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUnidade',
                                              'c.sigla',
                                              'orgao c');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgaoUnidade',
                                              'c.descricao',
                                              'orgao c');
  	 
  	//Usado na manutenção de tabelas do sistema
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelPerfilRecursoDTO');

		//Usado na busca de usuários de um recurso
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeRecurso');
			
		//Usada no carregamento das permissões do usuario
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRecursoDTO');

  	//Usado na atribuição de permissão múltipla
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjUsuarioDTO');
		
		//Usado na carga de usuário via web-service
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'TipoServidorAutenticacao');
		
    $this->configurarPK('IdPerfil',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdSistema',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);

    //PERMISSAO x PERFIL
    $this->configurarFK('IdPerfil', 'perfil', 'id_perfil');
    $this->configurarFK('IdSistema', 'perfil', 'id_sistema');

		//PERMISSAO x TIPO_PERMISSAO
    $this->configurarFK('IdTipoPermissao', 'tipo_permissao', 'id_tipo_permissao');
		
		//PERMISSAO x UNIDADE
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');

		//PERMISSAO x USUARIO
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');

		//PERFIL x SISTEMA
		$this->configurarFK('IdSistemaPerfil', 'sistema', 'id_sistema');
		
		$this->configurarFK('IdOrgaoSistema', 'orgao a', 'a.id_orgao');
		$this->configurarFK('IdOrgaoUsuario', 'orgao b', 'b.id_orgao');
		$this->configurarFK('IdOrgaoUnidade', 'orgao c', 'c.id_orgao');
		
		
  }
}
?>