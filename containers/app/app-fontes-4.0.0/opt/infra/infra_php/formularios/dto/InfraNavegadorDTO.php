<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/08/2012 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

//require_once dirname(__FILE__).'/../Infra.php';

class InfraNavegadorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'infra_navegador';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdInfraNavegador',
                                   'id_infra_navegador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Identificacao',
                                   'identificacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Versao',
                                   'versao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'UserAgent',
                                   'user_agent');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Ip',
                                   'ip');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Acesso',
                                   'dth_acesso');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Inicial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Final');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinIgnorarVersao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'TotalAcessos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'TotalFormatado');
    
    $this->configurarPK('IdInfraNavegador',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>