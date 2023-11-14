<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: EditorDTO.php 9456 2014-10-30 19:30:41Z mga $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class EditorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'editor';
  }

  public function montar() {

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeCampo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeTag');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinSomenteLeitura');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCodigoFonte');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinEstilos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinImagens');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAutoTexto');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinLinkSei');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'TamanhoEditor');
    
    //consulta de versão
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdBaseConhecimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Versao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'VersaoComparacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinIgnorarNovaVersao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjSecaoDocumentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinForcarNovaVersao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAlterouVersao');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoTextoBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoEdocBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdBaseConhecimentoBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdModelo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdConjuntoEstilos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ConteudoSecaoPrincipal');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdTextoPadraoInterno');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCabecalho');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinRodape');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinIdentificacaoVersao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCarimboPublicacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAssinaturas');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinProcessarLinks');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinMontandoEditor');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinProcessandoEditor');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ConteudoInicialSecoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ConteudoCss');

    //atributos de retorno para o CK
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Inicializacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Toolbar');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Textareas');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Editores');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Validacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Mensagens');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Css');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinValidarXss');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ArquivoComparacaoXss');
  }
}
?>