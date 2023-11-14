<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/08/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ControlePrazoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'controle_prazo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdControlePrazo', 'id_controle_prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProtocolo', 'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Prazo', 'dta_prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Conclusao', 'dta_conclusao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAberto');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCincluir');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'ProtocoloFormatado',
      'protocolo_formatado',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUsuario',
      'sigla',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeUsuario',
      'nome',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdTipoProcedimento',
      'id_tipo_procedimento',
      'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeTipoProcedimento',
      'nome',
      'tipo_procedimento');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDiasUteis');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Dias');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Ano');

    $this->configurarPK('IdControlePrazo',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdProtocolo','protocolo','id_protocolo');
    $this->configurarFK('IdUsuario','usuario','id_usuario');

    $this->configurarFK('IdProtocolo','procedimento','id_procedimento');
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');



  }
}
