<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: VersaoSecaoDocumentoDTO.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class VersaoSecaoDocumentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'versao_secao_documento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 
                                   'IdVersaoSecaoDocumento', 
                                   'id_versao_secao_documento');
        
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                    'IdSecaoDocumento', 
                                    'id_secao_documento');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                    'IdUsuario', 
                                    'id_usuario');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                    'IdUnidade', 
                                    'id_unidade');
        
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'Conteudo', 
                                   'conteudo');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 
                                   'Atualizacao', 
                                   'dth_atualizacao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                   'Versao', 
                                   'versao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 
                                   'SinUltima', 
                                   'sin_ultima');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdSecaoModeloSecaoDocumento',
                                              'id_secao_modelo',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'OrdemSecaoDocumento',
                                              'ordem',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdDocumentoSecaoDocumento',
                                              'id_documento',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdBaseConhecimentoSecaoDocumento',
                                              'id_base_conhecimento',
                                              'secao_documento');
        
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAssinaturaSecaoDocumento',
                                              'sin_assinatura',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinSomenteLeituraSecaoDocumento',
                                              'sin_somente_leitura',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinPrincipalSecaoDocumento',
                                              'sin_principal',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinDinamicaSecaoDocumento',
                                              'sin_dinamica',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinCabecalhoSecaoDocumento',
                                              'sin_cabecalho',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinRodapeSecaoDocumento',
                                              'sin_rodape',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinHtmlSecaoDocumento',
                                              'sin_html',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ConteudoSecaoDocumento',
                                              'conteudo',
                                              'secao_documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuario',
                                              'sigla',
                                              'usuario');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUsuario',
                                              'nome',
                                              'usuario');
        
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUsuario',
                                              'id_orgao',
                                              'usuario');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidade',
                                              'descricao',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUsuario',
                                              'sigla',
                                              'orgao');    

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSecaoModelo',
                                              'nome',
                                              'secao_modelo');    
    
    $this->configurarPK('IdVersaoSecaoDocumento',InfraDTO::$TIPO_PK_NATIVA);
    
    $this->configurarFK('IdSecaoDocumento', 'secao_documento', 'id_secao_documento');
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdOrgaoUsuario', 'orgao', 'id_orgao');
    $this->configurarFK('IdSecaoModeloSecaoDocumento','secao_modelo','id_secao_modelo');
  }
}
?>