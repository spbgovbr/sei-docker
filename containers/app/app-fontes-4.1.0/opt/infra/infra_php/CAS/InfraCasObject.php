<?php

require_once("InfraCasLifepoint.php");
require_once("InfraCasHttpClass.php");

class InfraCasObject
{
    public $error_code = 0;
    public $error_message = "";
    public $error_loglevel = 0;
    private $use_continue = 0;


    // Variáveis internas
    public $lifepoints = array();

    /**
     * Indica que se em caso de existir o $filename se o $content_type deve ser determinado automaticamente
     *
     * @var int $autocontenttype
     */
    public $autocontenttype = true;

    /**
     * Identificação do UUID do objeto a ser consultado, lido ou apagado do sistema Swarm.
     * @var string $uuid
     */
    public $uuid;

    /**
     * Em caso de gravação: Indica um array a ser gravado no Swarm
     * Em caso de leitura: Retorna um array com o objeto lido
     *
     * A Prioridade é:
     *
     *   $bodyfilename
     *   $bodystream
     *   $body
     *
     * @var array $body
     */
    public $body;

    /**
     * Tanto para leitura ou gravação indica um stream de onde os dados devem lidos ou gravados, a prioridade é:
     *
     *   $bodyfilename
     *   $bodystream
     *   $body
     *
     * @var resource $bodystream
     */
    public $bodystream;

    /**
     * Caminho completo para um arquivo que será lido ou gravado, a prioridade é:
     *
     *   $bodyfilename
     *   $bodystream
     *   $body
     *
     * @var string $bodyfilename
     */
    public $bodyfilename;

    /**
     * Nome do servidor Swarm, informado pelo servidor
     *
     * @var string $server
     */
    public $server = "";

    /**
     * Tamanho do objeto, somente válido para leitura ou informação.
     *
     *   $bodyfilename
     *   $bodystream
     *   $body
     *
     * @var int $content_length
     */
    public $content_length = 0;

    /**
     * Nome do arquivo original
     *
     * @var string $filename
     */
    public $filename;

    /**
     * Hash SHA256 do arquivo para controle de integridade
     * @var unknown
     */
    public $contentSHA256;

    /**
     * Array de InfraCasHeader para ser utilizado como metadados
     *
     * @var array $headers
     */
    public $headers = null;

    /**
     * MIME do objeto, se o atributo $autocontenttype for 1 e o nome do arquivo for informado ele será determinado automaticamente.
     *
     * @var string $content_type
     */
    public $content_type = "application/octet-stream";

    /**
     * Usado pelo e-Proc, nome do aplicativo
     *
     * @var string $castor_aplicativo
     */
    public $castor_aplicativo;

    /**
     * Usado pelo e-Proc, origem do documento
     *
     * @var string $castor_origem
     */
    public $castor_origem;

    /**
     * Diretório do arquivo no servidor CAS
     *
     * @var string $folderFileServer
     */
    public $folderFileServer;

    /**
     * Usado pelo e-Proc, ID do documentp
     *
     * @var string $castor_iddocumento
     */
    public $castor_iddocumento;

    /**
     * Construtor da classe que representa um objeto
     *
     * @param string $uuid Indica o número único universal do objeto a ser pesquisado, lido ou exlcuído.
     *
     * @param InfraCasLifepoint $lifepoints Array de regras de Lifepoints
     *
     * @param InfraCasHeader $headers Array de cas_headers em caso de necessidade de envio de metadados para o Swarm.
     *
     **/
    function __construct($uuid = null, $lifepoints = null, $headers = null)
    {
        $this->uuid = $uuid;
        $this->lifepoints = $lifepoints;
        $this->headers = $headers;
    }

    /**
     * Adiciona um lifepoint a este objeto
     *
     * @param InfraCasLifepoint $lifepoint Adiciona uma regra de Lifepoint a este objeto
     *
     **/
    function addLifepoint($lifepoint)
    {
        array_push($this->lifepoints, $lifepoint);
    }

    public function parseReadHeaders($headers)
    {
        try {
            $this->headers = $headers;

            if (isset($headers["content-uuid"])) {
                $this->uuid = $headers["content-uuid"];
            } else {
                if (isset($headers["etag"])) {
                    $this->uuid = ltrim(rtrim($headers["etag"], "\""), "\"");
                }
            }

            if (isset($headers["server"])) {
                $this->server = $headers["server"];
            }
            if (isset($headers["content-length"])) {
                $this->content_length = (int)$headers["content-length"];
            }
            if (isset($headers["content-type"])) {
                $this->content_type = $headers["content-type"];
            }
            if (isset($headers["castor-origem"])) {
                $this->castor_origem = $headers["castor-origem"];
            }
            if (isset($headers["castor-aplicativo"])) {
                $this->castor_aplicativo = $headers["castor-aplicativo"];
            }
            if (isset($headers["castor-iddocumento"])) {
                $this->castor_iddocumento = $headers["castor-iddocumento"];
            }
            if (isset($headers["content-disposition"])) {
                $r = explode('"', $headers["content-disposition"]);
                if (count($r) >= 3) {
                    $this->filename = $r[1];
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setWriteHeaders(&$http_arguments)
    {
        try {
            if (isset($this->bodystream)) {
                $http_arguments["BodyStream"] = $this->bodystream;
            } else {
                if (isset($this->bodyfilename)) {
                    $http_arguments["BodyStream"] = array(0 => array("File" => $this->bodyfilename));
                } else {
                    if (isset($this->body)) {
                        $http_arguments["Body"] = $this->body;
                    }
                }
            }

            $line = "";
            if (!empty($this->lifepoints)) {
                $i = 0;
                foreach ($this->lifepoints as $life) {
                    $line .= ($i == 0) ? $life->getLine() : "," . $life->getLine();
                    $i++;
                }
                $http_arguments["Headers"]["Lifepoint"] = $line;
            }
            if ($this->autocontenttype) {
                if (isset($this->filename)) {
                    $this->content_type = $this->getFilenameType($this->filename);
                }
            }
            $http_arguments["Headers"]["Content-Type"] = $this->content_type;
            if ($this->headers != null) {
                foreach ($this->headers as $header) {
                    $http_arguments["Headers"][$header->key] = $header->value;
                }
            }
            if (isset($this->castor_aplicativo)) {
                $http_arguments["Headers"]["castor-aplicativo"] = $this->castor_aplicativo;
            }
            if (isset($this->castor_iddocumento)) {
                $http_arguments["Headers"]["castor-iddocumento"] = $this->castor_iddocumento;
            }
            if (isset($this->castor_origem)) {
                $http_arguments["Headers"]["castor-origem"] = $this->castor_origem;
            }
            if (isset($this->filename)) {
                $http_arguments["Headers"]["Content-Disposition"] = "filename=\"" . $this->filename . "\"";
            }

            return true;
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
            return false;
        }
    }

    function getFilenameType($name)
    {
        switch (GetType($dot = strrpos($name, ".")) == "integer" ? strtolower(substr($name, $dot)) : "") {
            case ".xls":
                $content_type = "application/excel";
                break;
            case ".hqx":
                $content_type = "application/macbinhex40";
                break;
            case ".doc":
            case ".dot":
            case ".wrd":
                $content_type = "application/msword";
                break;
            case ".pdf":
                $content_type = "application/pdf";
                break;
            case ".pgp":
                $content_type = "application/pgp";
                break;
            case ".ps":
            case ".eps":
            case ".ai":
                $content_type = "application/postscript";
                break;
            case ".ppt":
                $content_type = "application/powerpoint";
                break;
            case ".rtf":
                $content_type = "application/rtf";
                break;
            case ".tgz":
            case ".gtar":
                $content_type = "application/x-gtar";
                break;
            case ".gz":
                $content_type = "application/x-gzip";
                break;
            case ".php":
            case ".php3":
                $content_type = "application/x-httpd-php";
                break;
            case ".js":
                $content_type = "application/x-javascript";
                break;
            case ".ppd":
            case ".psd":
                $content_type = "application/x-photoshop";
                break;
            case ".swf":
            case ".swc":
            case ".rf":
                $content_type = "application/x-shockwave-flash";
                break;
            case ".tar":
                $content_type = "application/x-tar";
                break;
            case ".zip":
                $content_type = "application/zip";
                break;
            case ".mid":
            case ".midi":
            case ".kar":
                $content_type = "audio/midi";
                break;
            case ".mp2":
            case ".mp3":
            case ".mpga":
                $content_type = "audio/mpeg";
                break;
            case ".ra":
                $content_type = "audio/x-realaudio";
                break;
            case ".wav":
                $content_type = "audio/wav";
                break;
            case ".bmp":
                $content_type = "image/bitmap";
                break;
            case ".gif":
                $content_type = "image/gif";
                break;
            case ".iff":
                $content_type = "image/iff";
                break;
            case ".jb2":
                $content_type = "image/jb2";
                break;
            case ".jpg":
            case ".jpe":
            case ".jpeg":
                $content_type = "image/jpeg";
                break;
            case ".jpx":
                $content_type = "image/jpx";
                break;
            case ".png":
                $content_type = "image/png";
                break;
            case ".tif":
            case ".tiff":
                $content_type = "image/tiff";
                break;
            case ".wbmp":
                $content_type = "image/vnd.wap.wbmp";
                break;
            case ".xbm":
                $content_type = "image/xbm";
                break;
            case ".css":
                $content_type = "text/css";
                break;
            case ".txt":
                $content_type = "text/plain";
                break;
            case ".htm":
            case ".html":
                $content_type = "text/html";
                break;
            case ".xml":
                $content_type = "text/xml";
                break;
            case ".mpg":
            case ".mpe":
            case ".mpeg":
                $content_type = "video/mpeg";
                break;
            case ".qt":
            case ".mov":
                $content_type = "video/quicktime";
                break;
            case ".avi":
                $content_type = "video/x-ms-video";
                break;
            case ".eml":
                $content_type = "message/rfc822";
                break;
            default:
                $content_type = "application/octet-stream";
                break;
        }
        return $content_type;
    }

    // ===============================================================================================
    // Private
    // ===============================================================================================
    private function setError($loglevel, $error_code, $error_message)
    {
        $this->error_code = $error_code;
        $this->error_message = $error_message;
        $this->error_loglevel = $loglevel;

        return false;
    }

    private function clearError()
    {
        $this->error_code = 0;
        $this->error_message = "";
        $this->error_loglevel = 0;
    }

    private function connect($http, $http_arguments, &$headers)
    {
        try {
            if (!isset($http_arguments)) {
                return $this->setError(
                    CASTOR_LOG_LEVEL_ERROR,
                    0,
                    CASTOR_ERROR_UNSPECIFIED_ERROR,
                    "Erro para inicializar argumentos"
                );
            }

            $error = $http->Open($http_arguments);
            if ($error == "") {
                while (true) {
                    $error = $http->SendRequest($http_arguments);
                    if ($error != "") {
                        break;
                    }
                    $error = $http->ReadReplyHeaders($headers);
                    if ($error != "100") {
                        break;
                    }
                }

                if ($error == "") {
                    $this->error_code = (int)$http->response_status;
                    if ($this->error_code == 404) {
                        return false;
                    }

                    if (($this->error_code < 200) || ($this->error_code >= 300)) {
                        return $this->setError(
                            CASTOR_LOG_LEVEL_INFO,
                            $this->error_code,
                            "Retorno de HTTP com erro: " . $http->response_message
                        );
                    }

                    return true;
                }
            }
            return $this->setError(CASTOR_LOG_LEVEL_ERROR, $http->error_code, $http->error);
        } catch (Exception $e) {
            return false;
        }
    }

    private function initArguments($http, $url, $args = null, $method = null)
    {
        $url = ($args == null) ? $url : rtrim($url, '/') . '/' . $args;
        $http->GetRequestArguments($url, $http_arguments);

        if (isset($method)) {
            $http_arguments["RequestMethod"] = $method;
        }

        return $http_arguments;
    }

    private function writeObjectSuccess(&$headers)
    {
        if (!$this->parseReadHeaders($headers)) {
            return $this->setError(
                CASTOR_LOG_LEVEL_ERROR,
                CASTOR_ERROR_UNAVAILABLE_NODES_ERROR,
                "Erro para interpretar cabeçalhos de retorno",
                $this
            );
        }

        return true;
    }

    private function readObjectSuccess($http, &$headers)
    {
        $this->parseReadHeaders($headers);

        if (isset($this->bodyfilename)) {
            $stream = fopen($this->bodyfilename, "w+");
            if ($stream == false) {
                return $this->setError(
                    CASTOR_LOG_LEVEL_ERROR,
                    CASTOR_ERROR_STREAM_ERROR,
                    "Erro para abrir " . $this->bodyfilename . " dados usando stream."
                );
            }
            for (; ;) {
                $error = $http->ReadReplyBody($body, 8000);
                if ($error != "" || strlen($body) == 0) {
                    break;
                }

                if (!@fwrite($stream, $body)) {
                    return $this->setError(
                        CASTOR_LOG_LEVEL_ERROR,
                        CASTOR_ERROR_STREAM_ERROR,
                        "Erro para gravar dados usando stream."
                    );
                }
            }
            fclose($stream);
        } else {
            if (isset($this->bodystream)) {
                for (; ;) {
                    $error = $http->ReadReplyBody($body, 8000);
                    if ($error != "" || strlen($body) == 0) {
                        break;
                    }

                    if (!@fwrite($this->bodystream, $body)) {
                        return $this->setError(
                            CASTOR_LOG_LEVEL_ERROR,
                            CASTOR_ERROR_STREAM_ERROR,
                            "Erro para gravar dados usando stream."
                        );
                    }
                }
            } else {
                $this->body = "";
                for (; ;) {
                    $error = $http->ReadReplyBody($body, 8000);
                    if ($error != "" || strlen($body) == 0) {
                        break;
                    }

                    $this->body .= $body;
                }
            }
        }

        return true;
    }

    public function core_process_request($username, $password, $operation, $url, $domain)
    {
        try {
            $this->clearError();

            $http = new InfraCasHttpClass;
            $http->timeout = 2;
            $http->data_timeout = 0;
            $http->debug = 0;
            $http->follow_redirect = 1;
            $http->redirection_limit = 5;
            $http->keep_alive = 1;
            $http->use_continue = $this->use_continue;

            //inicializar argumentos
            $http_arguments = null;
            switch ($operation) {
                case CASTOR_OPER_READ:
                    $path = $this->uuid;
                    if (isset($domain)) {
                        $path .= "?domain=" . $domain;
                    }

                    $http_arguments = $this->initArguments($http, $url, $path, "GET");
                    break;
                case CASTOR_OPER_WRITE:
                    $path = null;
                    if (isset($domain)) {
                        $path = "?domain=" . $domain;
                    }

                    $http_arguments = $this->initArguments($http, $url, $path, "POST");
                    $result = $this->setWriteHeaders($http_arguments);
                    if (!$result) {
                        return $this->setError(
                            CASTOR_LOG_LEVEL_ERROR,
                            CASTOR_ERROR_UNSPECIFIED_ERROR,
                            "Erro para inicializar argumentos de HTTP."
                        );
                    }
                    break;
                case CASTOR_OPER_INFO:
                    $path = $this->uuid;
                    if (isset($domain)) {
                        $path .= "?domain=" . $domain;
                    }

                    $http_arguments = $this->initArguments($http, $url, $path, "HEAD");
                    break;
                case CASTOR_OPER_DELETE:
                    $path = $this->uuid;
                    if (isset($domain)) {
                        $path .= "?domain=" . $domain;
                    }

                    $http_arguments = $this->initArguments($http, $url, $path, "DELETE");
                    break;
            }

            if (isset($username) && isset($password)) {
                $http_arguments["Authorization"] = "Basic " . base64_encode($username . ":" . $password);
            }

            $result = $this->connect($http, $http_arguments, $headers);
            if ($result) {
                switch ($operation) {
                    case CASTOR_OPER_WRITE:
                    case CASTOR_OPER_DELETE:
                    case CASTOR_OPER_INFO:
                        $result = $this->writeObjectSuccess($headers);
                        break;
                    case CASTOR_OPER_READ:
                        $result = $this->readObjectSuccess($http, $headers);
                        break;
                }
                $http->Close(1);
                return $result;
            }

            $http->Close(1);

            return false;
        } catch (Exception $e) {
            return $this->setError(CASTOR_LOG_LEVEL_ERROR, CASTOR_ERROR_EXCEPTION, "EXCEPTION: " . $e->getMessage());
        }
    }

}
