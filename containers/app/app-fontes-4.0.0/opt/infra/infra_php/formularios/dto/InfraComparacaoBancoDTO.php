<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/10/2012 - criado por MKR
*
*/

//require_once dirname(__FILE__).'/../Infra.php';

class InfraComparacaoBancoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ServidorOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PortaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'UsuarioOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SenhaOrigem');      
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoDadosOrigem');
      
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ServidorDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PortaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'UsuarioDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SenhaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoDadosDestino');
                   
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeTabelaOrigem');    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'QtdeRegistrosTabelaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ColunasTabelaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'QtdeRegistrosTabelaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinQtdeRegistrosTabelaDestinoOK');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinColunasTabelaDestinoOK');    
                
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeConstraintOrigem');           
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeColunasConstraintOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNomeColunasConstraintDestinoOK');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'MaxIdTabelaSequenciaOrigem');    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'MaxIdTabelaSequenciaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'MaxIdTabelaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'MaxIdTabelaDestino');       

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeIndiceOrigem');    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ColunasIndiceOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Unique');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinIndiceOK');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinComparaQtdeRegistrosTabela');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinComparaMaxIdTabela');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinComparaTipoColunasTabela');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinExibirSomenteDiferencas');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'TabelasIgnorar');
  }
}
?>