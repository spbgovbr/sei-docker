<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 03/07/2019 - criado por cle@trf4.jus.br
  * @package infra_php
  */

  abstract class InfraSessaoRest {

    public function __construct(InfraIBanco $objInfraIBanco) {
      BancoInfra::setObjInfraIBanco($objInfraIBanco);
    }

    public function getNumDuracaoSessao() {
      return 4;
    }

    public function logar(InfraSessaoRestDTO $objInfraSessaoRestDTO) {
      try {
        $objInfraSessaoRestDTO->setStrIdInfraSessaoRest(hash('SHA512',mt_rand().$objInfraSessaoRestDTO->__toString().uniqid(mt_rand(),true)));
        $objInfraSessaoRestDTO->setDthLogin(date("d/m/Y H:i:s"));
        $objInfraSessaoRestDTO->setDthAcesso(date("d/m/Y H:i:s"));
        $objInfraSessaoRestDTO->setDthLogout(null);
        $strUserAgent = substr($_SERVER['HTTP_USER_AGENT'],0,500);
        $objInfraSessaoRestDTO->setStrUserAgent($strUserAgent);
        $strIp = substr($_SERVER['HTTP_CLIENT_IP'],0,39);
        $objInfraSessaoRestDTO->setStrHttpClientIp($strIp);
        $strIp = substr($_SERVER['HTTP_X_FORWARDED_FOR'],0,39);
        $objInfraSessaoRestDTO->setStrHttpXForwardedFor($strIp);
        $strIp = substr($_SERVER['REMOTE_ADDR'],0,39);
        $objInfraSessaoRestDTO->setStrRemoteAddr($strIp);

        $objInfraSessaoRestRN = new InfraSessaoRestRN();
        $objInfraSessaoRestDTO = $objInfraSessaoRestRN->cadastrar($objInfraSessaoRestDTO);

        return $objInfraSessaoRestDTO->getStrIdInfraSessaoRest();
      } catch(Exception $e) {
        throw new InfraException('Erro logando Sessão REST.', $e);
      }
    }

    public function validar($strIdSessaoRest) {
      try {
        $objInfraException = new InfraException();

        $objInfraSessaoRestDTO = new InfraSessaoRestDTO();
        $objInfraSessaoRestDTO->retDthAcesso();
        $objInfraSessaoRestDTO->setStrIdInfraSessaoRest($strIdSessaoRest);
        $objInfraSessaoRestDTO->setDthLogout(null, InfraDTO::$OPER_IGUAL);

        $objInfraSessaoRestRN = new InfraSessaoRestRN();
        $objInfraSessaoRestDTO = $objInfraSessaoRestRN->consultar($objInfraSessaoRestDTO);

        if (!is_object($objInfraSessaoRestDTO)) {
          throw new InfraException('Sessão REST inválida.', null, 'INFRA_LOGOUT');
        } else {
          if (InfraData::compararDataHora(InfraData::calcularData($this->getNumDuracaoSessao(), InfraData::$UNIDADE_HORAS, InfraData::$SENTIDO_ADIANTE, $objInfraSessaoRestDTO->getDthAcesso()), date("d/m/Y H:i:s")) < 0) {
            $objInfraSessaoRestDTO->unRetDthAcesso();
            $objInfraSessaoRestDTO->unSetDthLogout();
            $objInfraSessaoRestDTO->setStrIdInfraSessaoRest($strIdSessaoRest);
            $objInfraSessaoRestDTO->setDthAcesso(date("d/m/Y H:i:s"));

            $objInfraSessaoRestRN->alterar($objInfraSessaoRestDTO);
          } else {
            throw new InfraException('Sessão REST expirada. Faça login novamente.', null, 'INFRA_LOGOUT');
          }
        }
        $objInfraException->lancarValidacoes();
      } catch(Exception $e) {
        throw new InfraException('Erro validando Sessão REST.', $e);
      }
    }

    public function deslogar($strIdSessaoRest) {
      try {
        $objInfraSessaoRestDTO = new InfraSessaoRestDTO();
        $objInfraSessaoRestDTO->setStrIdInfraSessaoRest($strIdSessaoRest);
        $objInfraSessaoRestDTO->setDthLogout(date("d/m/Y H:i:s"));

        $objInfraSessaoRestRN = new InfraSessaoRestRN();
        $objInfraSessaoRestRN->alterar($objInfraSessaoRestDTO);
      } catch(Exception $e) {
        throw new InfraException('Erro deslogando Sessão REST.', $e);
      }
    }

  }
?>