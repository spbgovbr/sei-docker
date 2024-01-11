<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/12/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: SecaoDocumentoDTO.php 9499 2014-11-14 14:17:50Z mga $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class SecaoDocumentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'secao_documento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                   'IdSecaoDocumento', 
                                   'id_secao_documento');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                   'Ordem', 
                                   'ordem');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinSomenteLeitura', 
                                   'sin_somente_leitura');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinAssinatura', 
                                   'sin_assinatura');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinPrincipal', 
                                   'sin_principal');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinDinamica', 
                                   'sin_dinamica');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinCabecalho', 
                                   'sin_cabecalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinRodape', 
                                   'sin_rodape');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinHtml', 
                                   'sin_html');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                   'IdSecaoModelo', 
                                   'id_secao_modelo');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 
                                   'IdDocumento', 
                                   'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                   'IdBaseConhecimento', 
                                   'id_base_conhecimento');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'Conteudo', 
                                   'conteudo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdSecaoModeloSecaoModelo',
                                              'id_secao_modelo',
                                              'secao_modelo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSecaoModelo',
                                              'nome',
                                              'secao_modelo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdDocumentoDocumento',
                                              'id_documento',
                                              'documento');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ConteudoOriginal');
    
    $this->configurarPK('IdSecaoDocumento',InfraDTO::$TIPO_PK_NATIVA);
    
    $this->configurarFK('IdSecaoModelo', 'secao_modelo', 'id_secao_modelo');
    $this->configurarFK('IdDocumento', 'documento', 'id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdBaseConhecimento', 'base_conhecimento', 'id_base_conhecimento', InfraDTO::$TIPO_FK_OPCIONAL);
    
  }
}
?>