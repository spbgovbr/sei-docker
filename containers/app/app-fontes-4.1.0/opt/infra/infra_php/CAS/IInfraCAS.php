<?php

/**
 * Interface criada para utilizar o pattern Proxy
 * @author j.rnascimento
 * @link https://www.geeksforgeeks.org/proxy-design-pattern/
 *
 */
interface IInfraCAS
{
    function apagarDocumento(InfraCasObject &$obj);

    function apagarDocumentoEx(InfraCasObject &$obj);

    function infoDocumento(InfraCasObject &$obj);

    function logTimer($operation, $timeinms, $obj);

    function logError($loglevel, $operation, $error_code, $error_message, $obj);

    function readDataFromCache($key);

    function salvarDocumento(InfraCasObject &$obj);

    function saveDataToCache($key, $data);

    function recuperarDocumento(InfraCasObject &$obj);
}
