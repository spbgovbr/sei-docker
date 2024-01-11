<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class UsuarioDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return "usuario";
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdOrgao',
                                   'id_orgao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IdOrigem',
                                   'id_origem');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Sigla',
                                   'sigla');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'NomeRegistroCivil',
                                  'nome_registro_civil');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'NomeSocial',
                                  'nome_social');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                  'Cpf',
                                  'cpf');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'Email',
                                  'email');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinBloqueado',
                                  'sin_bloqueado');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgao',
                                              'sigla',
                                              'orgao');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                        	     'DescricaoOrgao',
                                        	     'descricao',
                                        	     'orgao');

     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdCodigoAcesso');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUsuarioOperacao');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Motivo');

		 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjPermissaoDTO');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'TipoServidorAutenticacao');

     $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_SEQUENCIAL);

     $this->configurarFK('IdOrgao', 'orgao', 'id_orgao');
     $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>