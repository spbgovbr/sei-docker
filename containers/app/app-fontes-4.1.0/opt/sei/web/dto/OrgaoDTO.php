<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'orgao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdOrgao',
                                   'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'IdOrgaoFederacao',
                                  'id_orgao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdContato',
                                  'id_contato');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdUnidade',
                                  'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Sigla',
                                   'sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinEnvioProcesso',
                                   'sin_envio_processo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinFederacaoEnvio',
                                   'sin_federacao_envio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinFederacaoRecebimento',
                                  'sin_federacao_recebimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'Timbre', 
                                   'timbre');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'Numeracao', 
                                   'numeracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'ServidorCorretorOrtografico',
                                  'servidor_corretor_ortografico');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'StaCorretorOrtografico',
                                  'sta_corretor_ortografico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'CodigoSei',
                                  'codigo_sei');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinPublicacao',
                                  'sin_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinConsultaProcessual',
                                  'sin_consulta_processual');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'IdxOrgao',
                                  'idx_orgao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeArquivo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                            'NomeContato',
                                            'nome',
                                            'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'CnpjContato',
                                              'cnpj',
                                              'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'EnderecoContato',
        'endereco',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'ComplementoContato',
        'complemento',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdCidadeContato',
        'id_cidade',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeCidadeContato',
        'nome',
        'cidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUfContato',
        'id_uf',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUfContato',
        'sigla',
        'uf');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'TelefoneComercialContato',
        'telefone_comercial',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'TelefoneResidencialContato',
      'a.telefone_residencial',
      'contato a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'TelefoneCelularContato',
        'telefone_celular',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'EmailContato',
        'email',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SitioInternetContato',
        'sitio_internet',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'BairroContato',
        'bairro',
        'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'CepContato',
        'cep',
        'contato');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'Historico');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinRestricaoPesquisaOrgaos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelOrgaoPesquisaDTO');


    $this->configurarPK('IdOrgao', InfraDTO::$TIPO_PK_INFORMADO );
    $this->configurarFK('IdContato', 'contato', 'id_contato');
    $this->configurarFK('IdCidadeContato', 'cidade', 'id_cidade',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUfContato', 'uf', 'id_uf',InfraDTO::$TIPO_FK_OPCIONAL);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>