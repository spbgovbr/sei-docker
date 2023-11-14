<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
* 24/05/2019 - criado por cle@trf4.jus.br
*/

require_once dirname(__FILE__) . '/../Sip.php';

class SipRestWS extends InfraRestWS {

  public function getObjInfraLog() {
    return LogSip::getInstance();
  }

  public function getObjInfraConfiguracao() {
    return ConfiguracaoSip::getInstance();
  }

  public function getObjInfraSessao() {
    return SessaoSip::getInstance();
  }

  public function verificarEstado($strSiglaOrgao, $strSiglaSistema, $strChave) {
    try {
      $this->validarChaveAcesso($strSiglaOrgao, $strSiglaSistema, $strChave);
      return json_encode(array('aviso' => utf8_encode('Sistema SIP está disponível.')));
    } catch (Exception $e) {
      $this->processarExcecao($e);
    }
  }

  public function autenticar($numIdOrgao, $strSigla, $strSenha, $strSiglaOrgao, $strSiglaSistema, $strChave) {
    try {
      $this->validarChaveAcesso($strSiglaOrgao, $strSiglaSistema, $strChave);

      $numIdUsuarioSIP = '';

      $strSenha = base64_decode($strSenha);
      $arrSenha = explode('*', $strSenha);
      for ($i = 0; $i < InfraArray::contar($arrSenha); $i++) {
        $arrSenha[$i] = chr(~(int)$arrSenha[$i]);
      }
      $strSenha = implode($arrSenha, '');

      SessaoSip::getInstance(false)->simularLogin();

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setNumIdOrgaoUsuario($numIdOrgao);
      $objLoginDTO->setStrSiglaUsuario($strSigla);
      $objLoginDTO->setStrSenhaUsuario($strSenha);

      $objLoginRN = new LoginRN();

      try {
        $objLoginRN->autenticar($objLoginDTO);

        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
        $objUsuarioDTO->setStrSigla($strSigla);

        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

        $numIdUsuarioSIP = $objUsuarioDTO->getNumIdUsuario();
      } catch (Exception $e) {
        $this->processarExcecao($e);
      }

      return json_encode(array('id_usuario_sip' => $numIdUsuarioSIP, 'aviso' => utf8_encode('Combinação Usuário/Senha válida.')));
    } catch (Exception $e) {
      $this->processarExcecao($e);
    }
  }

  public function carregarRecursos(
    $numIdSistema, $strPerfil, $strRecurso, $numIdOrgao, $strSigla, $strSenha, $strSiglaOrgao, $strSiglaSistema, $strChave) {
    try {
      $this->validarChaveAcesso($strSiglaOrgao, $strSiglaSistema, $strChave);

      $strSenha = base64_decode($strSenha);
      $arrSenha = explode('*', $strSenha);
      for ($i = 0; $i < InfraArray::contar($arrSenha); $i++) {
        $arrSenha[$i] = chr(~(int)$arrSenha[$i]);
      }
      $strSenha = implode($arrSenha, '');

      SessaoSip::getInstance(false)->simularLogin();

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setNumIdOrgaoUsuario($numIdOrgao);
      $objLoginDTO->setStrSiglaUsuario($strSigla);
      $objLoginDTO->setStrSenhaUsuario($strSenha);

      $objLoginRN = new LoginRN();

      try {
        $objLoginRN->autenticar($objLoginDTO);
      } catch (Exception $e) {
        $this->processarExcecao($e);
      }

      $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
      $objRelPerfilRecursoDTO->setDistinct(true);
      $objRelPerfilRecursoDTO->retStrNomeRecurso();
      $objRelPerfilRecursoDTO->setStrSinAtivoPerfil('S');
      $objRelPerfilRecursoDTO->setStrSinAtivoRecurso('S');

      $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);

      if ($strPerfil != null) {
        $objRelPerfilRecursoDTO->setNumIdPerfil(array($strPerfil), InfraDTO::$OPER_IN);
      }

      if ($strRecurso != null) {
        $objRelPerfilRecursoDTO->setStrNomeRecurso(array($strRecurso), InfraDTO::$OPER_IN);
      }

      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
      $arrObjRelPerfilRecursoDTO = $objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO);

      for ($i = 0; $i < InfraArray::contar($arrObjRelPerfilRecursoDTO); $i++) {
        $arrResultado[] = $arrObjRelPerfilRecursoDTO[$i]->getStrNomeRecurso();
      }

      return json_encode($arrResultado);
    } catch (Exception $e) {
      $this->processarExcecao($e);
    }
  }

}

?>