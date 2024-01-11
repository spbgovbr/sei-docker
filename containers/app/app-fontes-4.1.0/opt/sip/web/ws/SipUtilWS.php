<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

abstract class SipUtilWS extends InfraWS {

  public function validarAcessoServico($strChaveAcesso, $numServico = null, $numIdSistema = null) {
    try {
      SessaoSip::getInstance(false)->simularLogin();

      if ($numIdSistema != null && in_array($numIdSistema, ConfiguracaoSip::getInstance()->getValor('Sip', 'SistemasSemChaveAcesso', false, array()))) {
        return;
      }

      if (strlen($strChaveAcesso) != 72 || preg_match("/[^0-9a-z]/", $strChaveAcesso)) {
        throw new InfraException('Erro validando acesso no Sistema de Permissões.');
      }

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->retStrChaveAcesso();
      $objSistemaDTO->retStrSigla();
      $objSistemaDTO->retNumIdOrgao();
      $objSistemaDTO->retStrSiglaOrgao();
      $objSistemaDTO->retStrServicosLiberados();
      $objSistemaDTO->setStrCrc(substr($strChaveAcesso, 0, 8));

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

      if ($objSistemaDTO == null) {
        throw new InfraException('Erro validando acesso no Sistema de Permissões.');
      }

      $objInfraBcrypt = new InfraBcrypt();
      if (!$objInfraBcrypt->verificar(md5(substr($strChaveAcesso, 8)), $objSistemaDTO->getStrChaveAcesso())) {
        throw new InfraException('Erro validando acesso no Sistema de Permissões.');
      }

      if ($numServico != null) {
        $arrObjTipoServicoDTO = InfraArray::indexarArrInfraDTO($objSistemaRN->listarValoresServico(), 'StaServico');

        if (!isset($arrObjTipoServicoDTO[$numServico])) {
          throw new InfraException('Serviço [' . $numServico . '] inválido.');
        }

        $arrServicos = explode(',', $objSistemaDTO->getStrServicosLiberados());

        if (!in_array($numServico, $arrServicos)) {
          throw new InfraException('Serviço "' . $arrObjTipoServicoDTO[$numServico]->getStrDescricao() . '" não foi liberado para o sistema ' . $objSistemaDTO->getStrSigla() . '/' . $objSistemaDTO->getStrSiglaOrgao() . '.');
        }
      }

      $ret = new SistemaDTO();
      $ret->setNumIdSistema($objSistemaDTO->getNumIdSistema());
      $ret->setStrSigla($objSistemaDTO->getStrSigla());
      $ret->setNumIdOrgao($objSistemaDTO->getNumIdOrgao());
      $ret->setStrSiglaOrgao($objSistemaDTO->getStrSiglaOrgao());
      return $ret;
    } catch (Throwable $e) {
      $strDetalhes = '';

      if (!InfraString::isBolVazia($_SERVER['REMOTE_ADDR'])) {
        $strDetalhes .= 'Remote_Addr=[' . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ']';
      }

      if (!InfraString::isBolVazia($_SERVER['HTTP_CLIENT_IP'])) {
        $strDetalhes .= ', Http_Client_IP=[' . gethostbyaddr($_SERVER['HTTP_CLIENT_IP']) . ']';
      }

      if (!InfraString::isBolVazia($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $strDetalhes .= ', Forwarded for=[' . $_SERVER['HTTP_X_FORWARDED_FOR'] . ']';
      }

      throw new InfraException('Erro validando acesso ao serviço.', $e, $strDetalhes);
    }
  }
}
