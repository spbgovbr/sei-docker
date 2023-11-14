<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/06/2018 - criado por mga
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__) . '/../Sip.php';

class CodigoAcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'codigo_acesso';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdCodigoAcesso', 'id_codigo_acesso');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioDesativacao', 'id_usuario_desativacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveGeracao', 'chave_geracao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveAtivacao', 'chave_ativacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveDesativacao', 'chave_desativacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Geracao', 'dth_geracao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'EnvioAtivacao', 'dth_envio_ativacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Ativacao', 'dth_ativacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Acesso', 'dth_acesso');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'EnvioDesativacao', 'dth_envio_desativacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Desativacao', 'dth_desativacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Email', 'email');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUsuario', 'usu_ger.id_orgao', 'usuario usu_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario', 'usu_ger.sigla', 'usuario usu_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario', 'usu_ger.nome', 'usuario usu_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH, 'Pausa2faUsuario', 'usu_ger.dth_pausa_2fa', 'usuario usu_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUsuario', 'ou_ger.sigla', 'orgao ou_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoUsuario', 'ou_ger.descricao', 'orgao ou_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUsuarioDesativacao', 'usu_des.id_orgao', 'usuario usu_des');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioDesativacao', 'usu_des.sigla', 'usuario usu_des');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioDesativacao', 'usu_des.nome', 'usuario usu_des');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUsuarioDesativacao', 'ou_des.sigla', 'orgao ou_des');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoUsuarioDesativacao', 'ou_des.descricao', 'orgao ou_des');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema', 'id_orgao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaSistema', 'sigla', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoSistema', 'descricao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoSistema', 'os.sigla', 'orgao os');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoSistema', 'os.descricao', 'orgao os');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'QrCode');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ChaveDigitavel');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'CodigoExterno');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ChaveAtivacaoExterna');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ChaveDesativacaoExterna');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ChaveBloqueioExterna');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdDispositivoAcesso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ChaveAcessoExterna');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ChaveDispositivoExterna');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinLiberarDispositivo');

    $this->configurarPK('IdCodigoAcesso', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdUsuario', 'usuario usu_ger', 'usu_ger.id_usuario');
    $this->configurarFK('IdOrgaoUsuario', 'orgao ou_ger', 'ou_ger.id_orgao');
    $this->configurarFK('IdUsuarioDesativacao', 'usuario usu_des', 'usu_des.id_usuario', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdOrgaoUsuarioDesativacao', 'orgao ou_des', 'ou_des.id_orgao');
    $this->configurarFK('IdSistema', 'sistema', 'id_sistema');
    $this->configurarFK('IdOrgaoSistema', 'orgao os', 'os.id_orgao');

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
