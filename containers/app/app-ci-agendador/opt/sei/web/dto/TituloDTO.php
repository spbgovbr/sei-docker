<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/08/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class TituloDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'titulo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTitulo', 'id_titulo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Expressao', 'expressao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Abreviatura', 'abreviatura');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdTitulo',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
