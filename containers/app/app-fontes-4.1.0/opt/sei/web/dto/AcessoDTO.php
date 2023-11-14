<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/05/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'acesso';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAcesso','id_acesso');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario','id_usuario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade','id_unidade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdControleInterno','id_controle_interno');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProtocolo','id_protocolo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'StaTipo','sta_tipo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaProtocoloProtocolo',
                                              'sta_protocolo',
                                              'protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaNivelAcessoGlobalProtocolo',
                                              'sta_nivel_acesso_global',
                                              'protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoProtocolo',
                                              'protocolo_formatado',
                                              'protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUnidade',
                                              'sigla',
                                              'unidade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdOrgao',
                                              'id_orgao',
                                              'unidade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'DescricaoUnidade',
                                              'descricao',
                                              'unidade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUsuario',
                                              'sigla',
                                              'usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeUsuario',
                                              'nome',
                                              'usuario');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaCredencialUnidade');

    $this->configurarPK('IdAcesso',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarFK('IdProtocolo','protocolo','id_protocolo');
    $this->configurarFK('IdUnidade','unidade','id_unidade');
    $this->configurarFK('IdUsuario','usuario','id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
  }
}
?>