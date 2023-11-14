<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ServicoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'servico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdServico',
                                   'id_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Identificacao',
                                   'identificacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Servidor',
                                   'servidor');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinLinkExterno',
                                   'sin_link_externo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinChaveAcesso',
                                   'sin_chave_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinServidor',
                                   'sin_Servidor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Crc',
                                   'crc');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ChaveAcesso',
                                   'chave_acesso');



    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuario',
                                              'sigla',
                                              'usuario');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUsuario',
                                              'nome',
                                              'usuario');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdContatoUsuario',
                                              'id_contato',
                                              'usuario');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ChaveCompleta');

    $this->configurarPK('IdServico',InfraDTO::$TIPO_PK_NATIVA);
    
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>