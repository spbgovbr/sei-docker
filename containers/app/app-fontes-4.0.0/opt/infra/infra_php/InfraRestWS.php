<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 02/07/2019 - criado por cle@trf4.jus.br
  * @package infra_php
  */

  abstract class InfraRestWS {

    public function __construct() {}

    public abstract function getObjInfraLog();

    public abstract function getObjInfraConfiguracao();

    public abstract function getObjInfraSessao();

    protected function processarExcecao($e, $bolLimparParametrosLog=false) {
      $strCodigoInfra = 'INFRA_ERRO';
      $strDetalhes = get_class($this)."\n\n";
      $strTrace = '';
      $bolGravarLog = true;
      $strStaTipoLog = InfraLog::$ERRO;

      if ($e instanceof InfraException) {
        if ($e->getStrDetalhes() == 'INFRA_LOGOUT') {
          $strCodigoInfra = 'INFRA_LOGOUT';
          $strMensagem = $e->getStrDescricao();
        } elseif ($e->contemValidacoes()) {
          $strCodigoInfra = 'INFRA_VALIDACAO';
          $strErro = $e->__toString();
          $strTrace = $e->getTraceAsString();
        } else {
          if ($e->getMessage() != '') {
            $strErro = $e->getMessage();
          } else {
            $strErro = $e->__toString();
          }

          //DETALHES PASSADOS PARA O CONSTRUTOR DE INFRAEXCEPTION
          if ($e->getStrDetalhes() !== null) {
            $strDetalhes .= $e->getStrDetalhes()."\n\n";
          }

          //TEXTO DA EXCECAO ORIGINAL
          if ($e->getObjException() != null) {
            $strTrace .= $e->getObjException()->__toString()."\n\n";
          }

          //TRACE DA EXCEÇÃO ORIGINAL
          $strTrace .= $e->getStrTrace();
        }

        if ($e->isBolPermitirGravacaoLog() === false) {
          $bolGravarLog = false;
        }

        if ($e->getStrStaTipoLog() !== null) {
          $strStaTipoLog = $e->getStrStaTipoLog();
        }
      } else {
        $strErro = $e->getMessage();
        $strDetalhes .= $e->__toString();
        $strTrace = $e->getTraceAsString();
      }

      if (($bolGravarLog) && ($this->getObjInfraLog() != null) && ($strCodigoInfra == 'INFRA_ERRO')) {
        try {
          if ($bolLimparParametrosLog) {
            $strErro = InfraString::limparParametrosPhp($strErro);
            $strDetalhes = InfraString::limparParametrosPhp($strDetalhes);
            $strTrace = InfraString::limparParametrosPhp($strTrace);
          }

          $strTextoLog = "Rest Web Service:\n".$strErro;
          $strTextoLog .= "\n\nDetalhes:\n".$strDetalhes;
          $strTextoLog .= "\n\nTrace:\n".$strTrace;

          if (InfraDebug::getInstance()->getStrDebug() != '') {
            $strTextoLog .= "\n\nDebug:\n".InfraDebug::getInstance()->getStrDebug();
          }

          $this->getObjInfraLog()->gravar($strTextoLog,$strStaTipoLog);
        } catch (Exception $e2) {}
      }

      if ($strCodigoInfra == 'INFRA_VALIDACAO') {
        echo json_encode(array($strCodigoInfra => utf8_encode($strErro)));
      } elseif ($strCodigoInfra == 'INFRA_ERRO') {
        echo json_encode(array($strCodigoInfra => utf8_encode(str_replace("\n", '<br >', str_replace('\n', '', $strTextoLog)))));
      } elseif ($strCodigoInfra == 'INFRA_LOGOUT') {
        echo json_encode(array($strCodigoInfra => utf8_encode($strMensagem)));
      }
      die();
    }

    protected function validarChaveAcesso($strSiglaOrgao, $strSiglaSistema, $strChave, $strChaveRaiz='ChaveRest') {
      try {
        $objInfraException = new InfraException();
        if ($this->getObjInfraConfiguracao()->isSetValor($strChaveRaiz, InfraString::transformarCaixaAlta($strSiglaOrgao.'_'.$strSiglaSistema))) {
          if (utf8_decode($strChave) != hash('sha256', $this->getObjInfraConfiguracao()->getValor('ChaveRest', InfraString::transformarCaixaAlta($strSiglaOrgao.'_'.$strSiglaSistema)))) {
            $objInfraException->adicionarValidacao('Chave REST inválida.');
          }
        } else {
          $objInfraException->adicionarValidacao('Chave REST inexistente.');
        }
        $objInfraException->lancarValidacoes();
      } catch(Exception $e) {
        throw new InfraException('Erro validando Chave REST.', $e);
      }
    }

  }
?>