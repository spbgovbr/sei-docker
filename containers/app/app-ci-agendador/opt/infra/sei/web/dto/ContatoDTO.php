<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 09/01/2008 - criado por marcio_db*
* 04/06/2018 -  cjy - criaзгo dos atributos numero_passaporte e id_pais_passaporte
* 12/06/2018 - cjy - insercao de estado e cidade textualmente, para paises estrangeiros
*
* Versгo do Gerador de Cуdigo: 1.12.0
*
* Versгo no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ContatoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'contato';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdContato',
                                   'id_contato');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdContatoAssociado',
                                   'id_contato_associado');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdCargo',
                                   'id_cargo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdCategoria',
                                   'id_categoria');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTitulo',
                                   'id_titulo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                'ExpressaoTituloContato',
                                'expressao',
                                'titulo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'AbreviaturaTituloContato',
                                    'abreviatura',
                                    'titulo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoContato',
                                   'id_tipo_contato');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuarioCadastro',
                                   'id_usuario_cadastro');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeCadastro',
                                   'id_unidade_cadastro');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Cadastro',
                                   'dth_cadastro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaNatureza',
                                   'sta_natureza');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'NomeRegistroCivil',
                                   'nome_registro_civil');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'NomeSocial',
                                   'nome_social');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Sigla',
                                   'sigla');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaGenero',
                                   'sta_genero');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'Cpf',
                                   'cpf');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'Cnpj',
                                   'cnpj');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'Rg',
                                   'rg');                                   
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'OrgaoExpedidor',
                                   'orgao_expedidor');                                   
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Matricula',
                                   'matricula');

     $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'MatriculaOab',
                                   'matricula_oab');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'TelefoneComercial',
                                   'telefone_comercial');

     $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'TelefoneCelular',
                                    'telefone_celular');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'TelefoneResidencial',
                                  'telefone_residencial');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Email',
                                   'email');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SitioInternet',
                                   'sitio_internet');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Endereco',
                                   'endereco');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Complemento',
                                   'complemento');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Bairro',
                                   'bairro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdCidade',
                                  'id_cidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdUf',
                                  'id_uf');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdPais',
                                   'id_pais');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Cep',
                                   'cep');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Observacao',
                                   'observacao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IdxContato',
                                   'idx_contato');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Nascimento',
                                   'dta_nascimento');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinEnderecoAssociado',
                                   'sin_endereco_associado');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'NumeroPassaporte',
                                  'numero_passaporte');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdPaisPassaporte',
                                  'id_pais_passaporte');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'Conjuge',
                                    'conjuge');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'Funcao',
                                    'funcao');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeCidade',
                                              'c1.nome',
                                              'cidade c1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'LatitudeCidade',
                                              'c1.latitude',
                                              'cidade c1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'LongitudeCidade',
                                              'c1.longitude',
                                              'cidade c1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUf',
                                              'u1.sigla',
                                              'uf u1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomePais',
                                              'p1.nome',
                                              'pais p1');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoContatoAssociado',
                                              'a.id_tipo_contato',
                                              'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaNaturezaContatoAssociado',
                                              'a.sta_natureza',
                                              'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaGeneroContatoAssociado',
                                              'a.sta_genero',
                                              'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinEnderecoAssociadoAssociado',
                                              'a.sin_endereco_associado',
                                              'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeContatoAssociado',
                                             'a.nome',
                                             'contato a');   

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaContatoAssociado',
                                             'a.sigla',
                                             'contato a');   
                                             
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'EnderecoContatoAssociado',
                                             'a.endereco',
                                             'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'ComplementoContatoAssociado',
                                             'a.complemento',
                                             'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'BairroContatoAssociado',
                                             'a.bairro',
                                             'contato a');   
                                             
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'CepContatoAssociado',
                                             'a.cep',
                                             'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdCidadeContatoAssociado',
                                              'a.id_cidade',
                                              'contato a');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeCidadeContatoAssociado',
                                             'c2.nome',
                                             'cidade c2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'LatitudeCidadeContatoAssociado',
                                              'c2.latitude',
                                              'cidade c2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'LongitudeCidadeContatoAssociado',
                                              'c2.longitude',
                                              'cidade c2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUfContatoAssociado',
                                              'a.id_uf',
                                              'contato a');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUfContatoAssociado',
                                             'u2.sigla',
                                             'uf u2');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeUfContatoAssociado',
                                             'u2.nome',
                                             'uf u2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdPaisContatoAssociado',
                                             'a.id_pais',
                                             'contato a');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomePaisContatoAssociado',
                                             'p2.nome',
                                             'pais p2');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'TelefoneComercialContatoAssociado',
                                             'a.telefone_comercial',
                                             'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'TelefoneCelularContatoAssociado',
                                              'a.telefone_celular',
                                              'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                        'TelefoneResidencialContatoAssociado',
                                        'a.telefone_residencial',
                                        'contato a');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SitioInternetContatoAssociado',
                                             'a.sitio_internet',
                                             'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'CnpjContatoAssociado',
                                              'a.cnpj',
                                              'contato a');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SinAtivoContatoAssociado',
                                             'a.sin_ativo',
                                             'contato a');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'ExpressaoTratamentoCargo',
                                             'expressao',
                                             'tratamento');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeCategoria',
                                             'nome',
                                             'categoria');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'ExpressaoVocativoCargo',
                                             'expressao',
                                             'vocativo');   
                                                                                    
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'ExpressaoCargo',
                                             'expressao',
                                             'cargo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdTratamentoCargo',
                                             'id_tratamento',
                                             'cargo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                             'IdVocativoCargo',
                                             'id_vocativo',
                                             'cargo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeTipoContato',
                                             'tcca.nome',
                                             'tipo_contato tcca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaAcessoTipoContato',
                                              'tcca.sta_acesso',
                                              'tipo_contato tcca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinSistemaTipoContato',
                                              'tcca.sin_sistema',
                                              'tipo_contato tcca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoTipoContato',
                                              'tcca.sin_ativo',
                                              'tipo_contato tcca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUnidadeCadastro',
                                             'unic.sigla',
                                             'unidade unic');   

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'DescricaoUnidadeCadastro',
                                             'unic.descricao',
                                             'unidade unic');   
                                             
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUsuarioCadastro',
                                             'usuc.sigla',
                                             'usuario usuc');   

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeUsuarioCadastro',
                                             'usuc.nome',
                                             'usuario usuc');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                            'IdPaisPassaporteContatoAssociado',
                                            'a.id_pais_passaporte',
                                            'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                            'NomePaisPassaporte',
                                            'pp.nome',
                                            'pais pp');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                            'NomeUf',
                                            'u1.nome',
                                            'uf u1');

		//Campos de pesquisa
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'MaisOpcoes');  	
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'NascimentoInicio');
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'NascimentoFim');
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdGrupoContato');
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'Afastamentos');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaOperacao');
  	  	
    $this->configurarPK('IdContato',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarFK('IdTipoContato', 'tipo_contato tcca', 'tcca.id_tipo_contato');
    $this->configurarFK('IdContatoAssociado', 'contato a', 'a.id_contato');
    $this->configurarFK('IdCargo', 'cargo', 'id_cargo', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdCategoria', 'categoria', 'id_categoria', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdTratamentoCargo', 'tratamento', 'id_tratamento', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdVocativoCargo', 'vocativo', 'id_vocativo', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdTitulo', 'titulo', 'id_titulo', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdCidade', 'cidade c1', 'c1.id_cidade', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUf', 'uf u1', 'u1.id_uf', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdPais', 'pais p1', 'p1.id_pais', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->configurarFK('IdCidadeContatoAssociado', 'cidade c2', 'c2.id_cidade', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUfContatoAssociado', 'uf u2', 'u2.id_uf', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdPaisContatoAssociado', 'pais p2', 'p2.id_pais', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->configurarFK('IdUnidadeCadastro', 'unidade unic', 'unic.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuarioCadastro', 'usuario usuc', 'usuc.id_usuario', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->configurarFK('IdPaisPassaporte', 'pais pp', 'pp.id_pais', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>