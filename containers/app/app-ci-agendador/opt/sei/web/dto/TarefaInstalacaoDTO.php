<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2019 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class TarefaInstalacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'tarefa_instalacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTarefaInstalacao', 'id_tarefa_instalacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->configurarPK('IdTarefaInstalacao',InfraDTO::$TIPO_PK_NATIVA);

  }
}
