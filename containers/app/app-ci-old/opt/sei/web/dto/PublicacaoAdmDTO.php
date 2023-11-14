<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/07/2009 - criado por mga
*
* Versão do Gerador de Código: 1.27.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoAdmDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'Publicacao_Adm';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Codigodocumento',
                                   'CodigoDocumento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Lotacaocodigo',
                                   'LotacaoCodigo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Lotacaonome',
                                   'LotacaoNome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Lotacaosigla',
                                   'LotacaoSigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nomerelator',
                                   'NomeRelator');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Processo',
                                   'Processo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Processoeditado',
                                   'ProcessoEditado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Conteudo',
                                   'Conteudo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Dataelaboracao',
                                   'DataElaboracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Dataintranet',
                                   'DataIntranet');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Conteudotxt',
                                   'ConteudoTxt');

    $this->configurarPK('Codigodocumento',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>