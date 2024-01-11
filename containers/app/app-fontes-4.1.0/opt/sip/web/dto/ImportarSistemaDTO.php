<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class ImportarSistemaDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoSistemaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SiglaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistemaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdHierarquiaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SiglaDestino');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoServidor');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoPorta');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoNome');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoSenha');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaTipoBanco');

    //Dados em separado porque a clonagem de sistema utilizada a mesma estruturas
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'DadosSistemaDTO');
  }
}

?>