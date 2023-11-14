<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class LembreteDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'lembrete';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdLembrete','id_lembrete');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdUsuario','id_usuario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Conteudo','conteudo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'PosicaoX','posicao_x');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'PosicaoY','posicao_y');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'Largura','largura');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'Altura','altura');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Cor','cor');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'CorTexto','cor_texto');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,'Lembrete','dth_lembrete');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinAtivo','sin_ativo');

    $this->configurarPK('IdLembrete',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>