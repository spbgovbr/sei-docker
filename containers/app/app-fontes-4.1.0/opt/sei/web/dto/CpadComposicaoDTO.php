<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadComposicaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'cpad_composicao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCpadComposicao', 'id_cpad_composicao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCpadVersao', 'id_cpad_versao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCargo', 'id_cargo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinPresidente', 'sin_presidente');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Ordem', 'ordem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario', 'sigla', 'usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario', 'nome', 'usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ExpressaoCargo', 'expressao', 'cargo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUsuarioVersao', 'id_usuario', 'cpad_versao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdCpad', 'id_cpad', 'cpad_versao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoCpad', 'id_orgao', 'cpad');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoCpad', 'sin_ativo', 'cpad');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoCpadVersao', 'sin_ativo', 'cpad_versao');


    $this->configurarPK('IdCpadComposicao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdCpadVersao', 'cpad_versao', 'id_cpad_versao');
    $this->configurarFK('IdCpad', 'cpad', 'id_cpad');
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdCargo', 'cargo', 'id_cargo');


  }
}
