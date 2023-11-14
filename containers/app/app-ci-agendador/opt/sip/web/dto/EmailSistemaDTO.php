<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/07/2018 - criado por mga
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class EmailSistemaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'email_sistema';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdEmailSistema',
                                   'id_email_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'IdEmailSistemaModulo',
                                    'id_email_sistema_modulo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'De',
                                   'de');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Para',
                                   'para');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Assunto',
                                   'assunto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Conteudo',
                                   'conteudo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');
        
    $this->configurarPK('IdEmailSistema',InfraDTO::$TIPO_PK_INFORMADO);
    
    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>