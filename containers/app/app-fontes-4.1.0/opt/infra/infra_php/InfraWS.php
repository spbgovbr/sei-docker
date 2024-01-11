<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/06/2006 - criado por MGA
 *
 * @package infra_php
 */


abstract class InfraWS
{

    public function __construct()
    {
    }

    public function getObjInfraLog()
    {
        return null;
    }

    protected function processarExcecao($e, $bolLimparParametrosLog = false)
    {
        $strCodigoSoapFault = 'Client';
        $strCodigoInfra = 'INFRA_ERRO';
        $strErro = '';
        $strDetalhes = get_class($this) . "\n\n";
        $strTrace = '';
        $bolSoapFault = false;
        $bolGravarLog = true;
        $strStaTipoLog = InfraLog::$ERRO;

        if ($e instanceof InfraException) {
            if ($e->contemValidacoes()) {
                $strCodigoInfra = 'INFRA_VALIDACAO';
                $strErro = $e->__toString();
                $strTrace = $e->getTraceAsString();
            } else {
                if ($e->getMessage() != '') {
                    $strErro = $e->getMessage();
                } else {
                    $strErro = $e->__toString();
                }

                //Detalhes passados para o construtor de InfraException
                if ($e->getStrDetalhes() !== null) {
                    $strDetalhes .= $e->getStrDetalhes() . "\n\n";
                }

                //Texto da excecao original
                if ($e->getObjException() != null) {
                    $strTrace .= $e->getObjException()->__toString() . "\n\n";
                }

                //Trace da excecao original
                $strTrace .= $e->getStrTrace();
            }

            if ($e->isBolPermitirGravacaoLog() === false) {
                $bolGravarLog = false;
            }

            if ($e->getStrStaTipoLog() !== null) {
                $strStaTipoLog = $e->getStrStaTipoLog();
            }
        } elseif ($e instanceof SoapFault) {
            $bolSoapFault = true;

            $strCodigoSoapFault = $e->faultcode;
            $strErro = $e->faultstring;
            $strCodigoInfra = InfraException::getTipoInfraException($e);
            $strDetalhes .= $e->__toString();
            $strTrace = $e->getTraceAsString();
        } else {
            $strErro = $e->getMessage();
            $strDetalhes .= $e->__toString();
            $strTrace = $e->getTraceAsString();
        }

        if ($bolGravarLog && $this->getObjInfraLog() != null && $strCodigoInfra == 'INFRA_ERRO') {
            try {
                if ($bolLimparParametrosLog) {
                    $strErro = InfraString::limparParametrosPhp($strErro);
                    $strDetalhes = InfraString::limparParametrosPhp($strDetalhes);
                    $strTrace = InfraString::limparParametrosPhp($strTrace);
                }

                $strTextoLog = "Web Service:\n" . $strErro;
                $strTextoLog .= "\n\nDetalhes:\n" . $strDetalhes;
                $strTextoLog .= "\n\nTrace:\n" . $strTrace;

                if (InfraDebug::getInstance()->getStrDebug() != '') {
                    $strTextoLog .= "\n\nDebug:\n" . InfraDebug::getInstance()->getStrDebug();
                }

                $this->getObjInfraLog()->gravar($strTextoLog, $strStaTipoLog);
            } catch (Exception $e2) {
            }
        }

        if ($bolSoapFault) {
            throw $e;
        } else {
            throw new SoapFault($strCodigoSoapFault, $strErro, null, array('infra_tipo_excecao' => $strCodigoInfra));
        }
    }

    protected function validarAcessoAutorizado($arrPermitidos)
    {
        $strRemoteAddr = null;
        if (!InfraString::isBolVazia($_SERVER['REMOTE_ADDR'])) {
            $strRemoteAddr = $_SERVER['REMOTE_ADDR'];

            try {
                $strRemoteAddr = gethostbyaddr($strRemoteAddr);
            } catch (Exception $e) {
            }
        }

        if ($strRemoteAddr !== null && InfraUtil::verificarEnderecoPermitido($strRemoteAddr, $arrPermitidos)) {
            return;
        }

        $strHttpXForwardedFor = null;
        if (!InfraString::isBolVazia($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arrIpForwardedFor = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arrIpForwardedFor as $strHttpXForwardedFor) {
                if (!InfraString::isBolVazia($strHttpXForwardedFor)) {
                    if (InfraUtil::verificarEnderecoPermitido($strHttpXForwardedFor, $arrPermitidos)) {
                        return;
                    }

                    try {
                        $strHttpXForwardedFor = gethostbyaddr($strHttpXForwardedFor);
                    } catch (Exception $e) {
                    }

                    if (InfraUtil::verificarEnderecoPermitido($strHttpXForwardedFor, $arrPermitidos)) {
                        return;
                    }
                }
            }
        }

        if ($this->getObjInfraLog() != null) {
            $strLog = 'Acesso negado [' . get_class($this) . ']:';

            if ($strRemoteAddr !== null) {
                $strLog .= ' Remote=[' . $strRemoteAddr . ']';
            }

            if ($strHttpXForwardedFor !== null) {
                $strLog .= ' Forwarded for=[' . $strHttpXForwardedFor . ']';
            }

            $this->getObjInfraLog()->gravar($strLog);
        }

        throw new InfraException('Acesso negado.');
    }

    protected function validarChaveAcesso($arrChavesAcessos, $strChave)
    {
        $strChave = trim($strChave);

        if (strlen($strChave) == 64) {
            foreach ($arrChavesAcessos as $strChaveAcesso) {
                if (hash('SHA256', $strChaveAcesso) == $strChave) {
                    return;
                }
            }
        }

        throw new InfraException('Acesso negado.');
    }


    public static function isBolServicoExiste($objWS, $strServico)
    {
        $arr = $objWS->__getFunctions();
        foreach ($arr as $srv) {
            if (strpos($srv, ' ' . $strServico . '(') !== false) {
                return true;
            }
        }
        return false;
    }
}

