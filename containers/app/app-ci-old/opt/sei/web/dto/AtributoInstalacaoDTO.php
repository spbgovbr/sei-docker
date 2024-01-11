<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2019 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoInstalacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'atributo_instalacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAtributoInstalacao', 'id_atributo_instalacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAndamentoInstalacao', 'id_andamento_instalacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Valor', 'valor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrigem', 'id_origem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao','andamento_instalacao' );

    $this->configurarPK('IdAtributoInstalacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdAndamentoInstalacao', 'andamento_instalacao', 'id_andamento_instalacao');


  }
}
