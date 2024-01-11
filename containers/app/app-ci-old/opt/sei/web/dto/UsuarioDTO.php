<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/01/2008 - criado por marcio_db
* 06/06/2018 - cjy - adicao dos campos numero_passaporte e id_pais_passaporte
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UsuarioDTO extends InfraDTO {

  public function __construct(){
    parent::__construct();
  }	
	
  public function getStrNomeTabela() {
  	 return 'usuario';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdUsuario','id_usuario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'IdUsuarioFederacao','id_usuario_federacao');
 	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdContato','id_contato');
 	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'IdOrigem','id_origem');
 	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdOrgao','id_orgao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Sigla','sigla');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Nome','nome');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'NomeRegistroCivil','nome_registro_civil');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'NomeSocial','nome_social');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'IdxUsuario','idx_usuario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'StaTipo','sta_tipo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Senha','senha');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinAcessibilidade','sin_acessibilidade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinAtivo','sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeContato','a.nome','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'EnderecoContato','a.endereco','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'ComplementoContato','a.complemento','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'BairroContato','a.bairro','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdCidadeContato','a.id_cidade','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeCidadeContato','nome','cidade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdCargoContato','a.id_cargo','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'ExpressaoCargoContato','expressao','cargo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUfContato','a.id_uf','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUfContato','sigla','uf');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdPaisContato','a.id_pais','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'CepContato','a.cep','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,'CpfContato','a.cpf','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,'RgContato','a.rg','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'OrgaoExpedidorContato','a.orgao_expedidor','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'TelefoneComercialContato','a.telefone_comercial','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'TelefoneResidencialContato','a.telefone_residencial','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'TelefoneCelularContato','a.telefone_celular','contato a');
 	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaOrgao','sigla','orgao');
 	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'DescricaoOrgao','descricao','orgao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdContatoOrgao','id_contato','orgao');
 	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SitioInternetOrgaoContato','b.sitio_internet','contato b');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NumeroPassaporte','a.numero_passaporte','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdPaisPassaporte','a.id_pais_passaporte','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,'CadastroContato','a.dth_cadastro','contato a');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'EmailContato','a.email','contato a');
 	  
 	  $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SenhaNova');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PalavrasPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinEstrangeiro');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Processos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Alterados');

 	  $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarFK('IdContato','contato a','a.id_contato');
    $this->configurarFK('IdCidadeContato','cidade','id_cidade',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdCargoContato','cargo','id_cargo',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUfContato','uf','id_uf',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdPaisContato','pais','id_pais',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdPaisPassaporte','pais','id_pais',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdOrgao','orgao','id_orgao');
    $this->configurarFK('IdContatoOrgao','contato b','b.id_contato');
    
    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>