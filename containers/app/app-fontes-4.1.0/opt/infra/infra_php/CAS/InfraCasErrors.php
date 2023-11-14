<?php

class InfraCasErrors
{
    /**
     * Funзгo estбtica para obter uma representaзгo textual do cуdigo de nнvel de 'log' informado.
     *
     * @param int $value - Cуdigo do nнvel de log a ser pesquisado
     *
     * @return string Representaзгo textual do cуdigo informado
     *
     **/
    public static function getCasLevelText($value)
    {
        switch ($value) {
            case CASTOR_LOG_LEVEL_DEBUG:
                return "CASTOR_LOG_LEVEL_DEBUG";
            case CASTOR_LOG_LEVEL_WARNING:
                return "CASTOR_LOG_LEVEL_WARNING";
            case CASTOR_LOG_LEVEL_INFO:
                return "CASTOR_LOG_LEVEL_INFO";
            case CASTOR_LOG_LEVEL_ERROR:
                return "CASTOR_LOG_LEVEL_ERROR";
        }
        return "N/D";
    }

    /**
     * Funзгo estбtica para obter uma representaзгo textual do cуdigo de operaзгo informado
     *
     * @param int $value - Cуdigo do nнvel de operaзгo
     *
     * @return string Representaзгo textual do cуdigo informado
     *
     **/
    public static function getCasOperationText($value)
    {
        switch ($value) {
            case CASTOR_OPER_READ:
                return "CASTOR_OPER_READ";
            case CASTOR_OPER_WRITE:
                return "CASTOR_OPER_WRITE";
            case CASTOR_OPER_INFO:
                return "CASTOR_OPER_INFO";
            case CASTOR_OPER_DELETE:
                return "CASTOR_OPER_DELETE";
        }
        return "N/D";
    }

    /**
     * Funзгo estбtica para obter uma representaзгo textual do cуdigo de erro informado
     *
     * @param int $value - Cуdigo do erro informado
     *
     * @return string Representaзгo textual do cуdigo informado
     *
     **/
    public static function getCasErrorText($value)
    {
        switch ($value) {
            case 100:
                $text = 'HTTP Continue';
                break;
            case 101:
                $text = 'HTTP Switching Protocols';
                break;
            case 200:
                $text = 'HTTP OK';
                break;
            case 201:
                $text = 'HTTP Created';
                break;
            case 202:
                $text = 'HTTP Accepted';
                break;
            case 203:
                $text = 'HTTP Non-Authoritative Information';
                break;
            case 204:
                $text = 'HTTP No Content';
                break;
            case 205:
                $text = 'HTTP Reset Content';
                break;
            case 206:
                $text = 'HTTP Partial Content';
                break;
            case 300:
                $text = 'HTTP Multiple Choices';
                break;
            case 301:
                $text = 'HTTP Moved Permanently';
                break;
            case 302:
                $text = 'HTTP Moved Temporarily';
                break;
            case 303:
                $text = 'HTTP See Other';
                break;
            case 304:
                $text = 'HTTP Not Modified';
                break;
            case 305:
                $text = 'HTTP Use Proxy';
                break;
            case 400:
                $text = 'HTTP Bad Request';
                break;
            case 401:
                $text = 'HTTP Unauthorized';
                break;
            case 402:
                $text = 'HTTP Payment Required';
                break;
            case 403:
                $text = 'HTTP Forbidden';
                break;
            case 404:
                $text = 'HTTP Not Found';
                break;
            case 405:
                $text = 'HTTP Method Not Allowed';
                break;
            case 406:
                $text = 'HTTP Not Acceptable';
                break;
            case 407:
                $text = 'HTTP Proxy Authentication Required';
                break;
            case 408:
                $text = 'HTTP Request Time-out';
                break;
            case 409:
                $text = 'HTTP Conflict';
                break;
            case 410:
                $text = 'HTTP Gone';
                break;
            case 411:
                $text = 'HTTP Length Required';
                break;
            case 412:
                $text = 'HTTP Precondition Failed';
                break;
            case 413:
                $text = 'HTTP Request Entity Too Large';
                break;
            case 414:
                $text = 'HTTP Request-URI Too Large';
                break;
            case 415:
                $text = 'HTTP Unsupported Media Type';
                break;
            case 500:
                $text = 'HTTP Internal Server Error';
                break;
            case 501:
                $text = 'HTTP Not Implemented';
                break;
            case 502:
                $text = 'HTTP Bad Gateway';
                break;
            case 503:
                $text = 'HTTP Service Unavailable';
                break;
            case 504:
                $text = 'HTTP Gateway Time-out';
                break;
            case 505:
                $text = 'HTTP Version not supported';
                break;
            case 700:
                $text = 'HTTP_CLIENT_ERROR_INVALID_SERVER_ADDRESS';
                break;
            case 701:
                $text = 'HTTP_CLIENT_ERROR_CANNOT_CONNECT';
                break;
            case 702:
                $text = 'HTTP_CLIENT_ERROR_COMMUNICATION_FAILURE';
                break;
            case 703:
                $text = 'HTTP_CLIENT_ERROR_CANNOT_ACCESS_LOCAL_FILE';
                break;
            case 704:
                $text = 'HTTP_CLIENT_ERROR_PROTOCOL_FAILURE';
                break;
            case 705:
                $text = 'HTTP_CLIENT_ERROR_INVALID_PARAMETERS';
                break;
            case 799:
                $text = 'HTTP_CLIENT_ERROR_UNSPECIFIED_ERROR';
                break;
            case 800:
                $text = 'CASTOR_ERROR_UNAVAILABLE_NODES_ERROR';
                break;
            case 801:
                $text = 'CASTOR_ERROR_STREAM_ERROR';
                break;
            case 802:
                $text = 'CASTOR_ERROR_TOO_MANY_RETRIES';
                break;
            case 803:
                $text = 'CASTOR_ERROR_EXCEPTION';
                break;
            case 804:
                $text = 'CASTOR_ERROR_NOT_FOUND';
                break;
            case 899:
                $text = 'CASTOR_ERROR_UNSPECIFIED_ERROR';
                break;

            default:
                $text = 'Unknown HTTP status code "' . $value . '"';
                break;
        }
        return $text;
    }
}
