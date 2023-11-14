<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/09/2017 - criado por mga
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioMarcadorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_usuario_marcador';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMarcador', 'id_marcador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    //$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMarcador', 'nome', 'marcador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeMarcador', 'id_unidade', 'marcador');

    $this->configurarPK('IdMarcador',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMarcador', 'marcador', 'id_marcador');

  }
}
?>