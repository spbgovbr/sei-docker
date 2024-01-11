<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/06/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TarjaAssinaturaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'tarja_assinatura';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdTarjaAssinatura','id_tarja_assinatura');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'StaTarjaAssinatura','sta_tarja_assinatura');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Texto','texto');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Logo','logo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinAtivo','sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeArquivo');

    $this->configurarPK('IdTarjaAssinatura',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
?>