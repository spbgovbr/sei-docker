<?php

require_once("InfraCasHttpClass.php");
require_once("InfraCasLifepoint.php");
require_once("InfraCasObject.php");
require_once("InfraCasNode.php");
require_once("InfraCasClusters.php");
require_once ("IInfraCAS.php");

define('CASTOR_OPER_READ',                     1001);
define('CASTOR_OPER_WRITE',                    1002);
define('CASTOR_OPER_DELETE',                   1003);
define('CASTOR_OPER_INFO',                     1004);

define('CASTOR_LOG_LEVEL_ERROR',                 10);
define('CASTOR_LOG_LEVEL_WARNING',               20);
define('CASTOR_LOG_LEVEL_INFO',                  30);
define('CASTOR_LOG_LEVEL_DEBUG',                 40);

define('CASTOR_ERROR_UNAVAILABLE_NODES_ERROR',  800);
define('CASTOR_ERROR_STREAM_ERROR',             801);
define('CASTOR_ERROR_TOO_MANY_RETRIES',         802);
define('CASTOR_ERROR_EXCEPTION',                803);
define('CASTOR_ERROR_NOT_FOUND',                804);
define('CASTOR_ERROR_UNSPECIFIED_ERROR',        899);


define('HTTP_CLIENT_ERROR_NO_ERROR',                 0);
define('HTTP_CLIENT_ERROR_INVALID_SERVER_ADDRESS',   700);
define('HTTP_CLIENT_ERROR_CANNOT_CONNECT',           701);
define('HTTP_CLIENT_ERROR_COMMUNICATION_FAILURE',    702);
define('HTTP_CLIENT_ERROR_CANNOT_ACCESS_LOCAL_FILE', 703);
define('HTTP_CLIENT_ERROR_PROTOCOL_FAILURE',         704);
define('HTTP_CLIENT_ERROR_INVALID_PARAMETERS',       705);
define('HTTP_CLIENT_ERROR_UNSPECIFIED_ERROR',        799);

/**
 * Classe com a funcionalidade de executar as operações de: leitura, escrita, informação e apagar objetos no Swarm.
 *
 * Exemplo de gravação:
 *
 * $obj = new InfraCasObject(null, array(new InfraCasLifepoint(7,2,0), new InfraCasLifepoint(0,-1,-1, 1)));
 *
 * $obj->body="teste";
 *
 * $resultado=$swarm.salvarDocumento($obj);
 *
 *
 */
class InfraSWARM implements IInfraCAS
{
    public $maxretries = 3;
    public $error_code;
    public $error_message;

    public $cluster = null;
    public $maincluster = null;
    public $readcluster = null;
    public $selectedNode = null;
    public $leituraInicialNoMain = false;
    public $username = null;
    public $password = null;
    public $domain = null;

    /**
     * Construtor da classe contendo o cluster a ser utilizado para execução das transações.
     *
     * @param InfraCasCluster $clusters - Classe contendo os clusters para operação desta API.
     *
     * @see cas_clusters
     *
     **/
    function __construct($username, $password, $clusters) {
        $this->maincluster=$clusters->maincluster;
        $this->readcluster=$clusters->readcluster;
        $this->leituraInicialNoMain=$clusters->leituraInicialNoMain;
        $this->domain=$clusters->domain;
        $this->username=$username;
        $this->password=$password;
    }

    /**
     * Função a ser implementada para gerar log de tempo de execução de cada requisição.
     *
     *
     * @param  int $operation       - Tipo de operação que está sendo realizada, opções.
     *                                CASTOR_OPER_READ, CASTOR_OPER_WRITE, CASTOR_OPER_INFO, CASTOR_OPER_DELETE
     *
     *                                Para obter uma representação textual da mensagem utilize InfraCasErrors::getCasOperationText($error_code)
     *
     * @param  float $timeinms      - Tempo de execução da operação em ms
     *
     * @param  InfraCasObject $obj       - Classe 'InfraCasObject' contendo informações sobre o objeto motivo deste 'log'
     *
     */
    function logTimer($operation, $timeinms, $obj) {

    }

    /**
     * Função a ser implementada para gerar log do ocorrido nesta API.
     *
     * @param  int $loglevel        -  Indica o nível de informação associada a esta mensagem, opções são.
     *                                CASTOR_LOG_LEVEL_ERROR, CASTOR_LOG_LEVEL_WARNING, CASTOR_LOG_LEVEL_INFO, CASTOR_LOG_LEVEL_DEBUG
     *
     *                                Para obter uma representação textual da mensagem utilize InfraCasErrors::getCasLevelText($error_code)
     *
     * @param  int $operation       - Tipo de operação que está sendo realizada, opções.
     *                                CASTOR_OPER_READ, CASTOR_OPER_WRITE, CASTOR_OPER_INFO, CASTOR_OPER_DELETE
     *
     *                                Para obter uma representação textual da mensagem utilize InfraCasErrors::getCasOperationText($error_code)
     *
     * @param  int $error_code      - Código do erro ou informação, caso o número seja entre 700 e 800 trata-se de mensagens geradas por esta API nos demais casos são erros de HTTP.
     *
     *                                Para obter uma representação textual da mensagem utilize InfraCasErrors::getCasErrorText($error_code)
     *
     * @param  string $error_message - Mensagem textual do ocorrido
     * @param  InfraCasObject $obj       - Classe 'InfraCasObject' contendo informações sobre o objeto motivo deste 'log'
     *
     * @see cas_object
     *
     */
    public function logError($loglevel, $operation, $error_code, $error_message, $obj) {
    }

    /**
     * Função para armazenar os dados em ambiente de cache, caso não deseje utilizar esta caracteristica basta não implementar os métodos.
     *
     * @return bool true, se foi bem sucedida

     **/
    public function saveDataToCache($key, $data) {
    }

    /**
     * Função para recuperar os dados em ambiente de cache, caso não deseje utilizar esta caracteristica basta não implementar os métodos.
     *
     * @return bool true, se foi bem sucedida

     **/
    public function readDataFromCache($key) {
        return null;
    }

    // Métodos para uso interno
    private function setError($loglevel, $operation, $error_code, $error_message, $obj) {
        $this->error_code=$error_code;
        $this->error_message=$error_message;

        $this->logError($loglevel, $operation, $error_code, $error_message, $obj);

        return false;
    }
    private function clearError() {
        $this->error_code=0;
        $this->error_message="";
    }
    private function __writeInit(&$obj) {
        $this->cluster=$this->maincluster;

        $this->selectedNode=$this->cluster->getNode();
        if ($this->selectedNode==null)
            return $this->setError(CASTOR_LOG_LEVEL_ERROR, CASTOR_OPER_WRITE, CASTOR_ERROR_UNAVAILABLE_NODES_ERROR, "Não existem nós disponíveis para gravar o objeto.", $obj );
    }
    private function __readInit(&$obj) {
        $this->cluster = $this->readcluster==null ? $this->maincluster :( $this->leituraInicialNoMain? $this->maincluster: $this->readcluster);

        $this->selectedNode=$this->cluster->getNode();
        if ($this->selectedNode==null) {
            if ( $this->readcluster==null)
                return $this->setError(CASTOR_LOG_LEVEL_ERROR, CASTOR_OPER_READ, CASTOR_ERROR_UNAVAILABLE_NODES_ERROR, "Não foi possível obter um nó para gravar objeto. (write)", $obj );

            if ($this->$leituraInicialNoMain)
                $this->cluster=$this->readcluster;
            else
                $this->cluster=$this->maincluster;
            $this->selectedNode=$this->cluster->getNode();
            if ($this->selectedNode==null)
                return $this->setError(CASTOR_LOG_LEVEL_ERROR, CASTOR_OPER_READ, CASTOR_ERROR_UNAVAILABLE_NODES_ERROR, "Não foi possível obter um nó para gravar objeto (read/write).", $obj );
        }

        return true;
    }

    private function core_process($operation, InfraCasObject &$obj)  {

        try {
            $this->clearError();
            $writeMode = false;
            switch($operation) {
                case CASTOR_OPER_DELETE:
                case CASTOR_OPER_WRITE:
                    $writeMode = true;
                    $result=$this->__writeInit($obj);
                    break;
                case CASTOR_OPER_INFO:
                case CASTOR_OPER_READ:
                    $result=$this->__readInit($obj);
                    break;
                default:
                    $result=true;
                    break;
            }

            $retries = $this->maxretries;
            do {
                //inicializar argumentos
                $sw = microtime(true);
                if ($obj->core_process_request($this->username, $this->password, $operation, $this->selectedNode->url, $this->domain)) {
                    $this->logTimer($operation, round((microtime(true) - $sw)*1000, 3), $obj);
                    return true;
                }

                $this->error_code=$obj->error_code;
                $this->error_message=$obj->error_message;

                if ($this->error_code==404 || $this->error_code == 701 || $this->error_code == 702) {
                    $this->logError(CASTOR_LOG_LEVEL_INFO, $operation, $this->error_code, "Objeto não localizado no endereço ".$this->selectedNode->url, $obj);

                    if ($this->leituraInicialNoMain && !$writeMode)
                        $this->cluster=$this->readcluster;
                    else
                        $this->cluster=$this->maincluster;

                    $this->selectedNode=$this->cluster->getNode();
                    if ($this->selectedNode==null)
                        return $this->setError(CASTOR_LOG_LEVEL_ERROR, $operation, CASTOR_ERROR_UNAVAILABLE_NODES_ERROR, "Não foi possível obter um nó (read/write).", $obj );

                    $this->logError(CASTOR_LOG_LEVEL_INFO, $operation, $this->error_code, "Tentando localizador objeto no endereço ".$this->selectedNode->url, $obj);
                    continue;
                } else {
                    if ( $this->error_code!=HTTP_CLIENT_ERROR_CANNOT_CONNECT) {
                        $this->logError(CASTOR_LOG_LEVEL_ERROR, $operation, $this->error_code, $this->error_message, $obj);
                        return false;
                    }
                    $this->logError(CASTOR_LOG_LEVEL_INFO, $operation, $this->error_code, "Nó ".$this->selectedNode->url." não responde", $obj);
                }

                $this->selectedNode->fail();
                if ($this->cluster==$this->readcluster)
                    $this->saveDataToCache("cluster_read", $this->readcluster);
                else
                    if ($this->cluster==$this->maincluster)
                        $this->saveDataToCache("cluster_main", $this->maincluster);
                $this->selectedNode = $this->cluster->getNode();
                if ( $this->selectedNode==null) {
                    if (( $this->cluster==$this->readcluster) && ( !$this->leituraInicialNoMain)) {
                        $this->cluster=$this->maincluster;
                        $this->selectedNode=$this->cluster->getNode();
                        $this->logError(CASTOR_LOG_LEVEL_INFO, $operation, $this->error_code, "Não foi possível se conectar no cluster de leitura, mudando para cluster principal", $obj);
                    } else
                        if (( $this->cluster==$this->maincluster) && ( $this->leituraInicialNoMain) && ($operation!=CASTOR_OPER_WRITE) && ($operation!=CASTOR_OPER_DELETE)) {
                            $this->cluster=$this->readcluster;
                            $this->selectedNode=$this->cluster->getNode();
                            $this->logError(CASTOR_LOG_LEVEL_INFO, $operation, $this->error_code, "Não foi possível se conectar no cluster principal, mudando para cluster de leitura", $obj);
                        }
                    if ( $this->selectedNode==null)
                        return $this->setError(CASTOR_LOG_LEVEL_ERROR, $operation, CASTOR_ERROR_UNAVAILABLE_NODES_ERROR, "Não existem nós disponíveis para gravar o objeto.", $obj );
                }
            } while (--$retries>0);
            return $this->setError(CASTOR_LOG_LEVEL_ERROR, $operation, CASTOR_ERROR_TOO_MANY_RETRIES, "Erro de gravação, foram feitas um total de ".$this->maxretries." tentativas sem sucesso", $obj );
        }
        catch (Exception $e) {
            return $this->setError(CASTOR_LOG_LEVEL_ERROR, $operation, CASTOR_ERROR_EXCEPTION, "EXCEPTION: ".$e->getMessage(), $obj);
        }
    }

    // ====================================================================================================
    // Funções públicas
    // ====================================================================================================
    
    /**
     * Função para armazenar objeto no sistema de armazenamento Swarm
     * @param InfraCasObject $obj - Classe contendo informações sobre o objeto a ser gravado
     * @return bool true, se foi bem sucedida, caso contrario retorna false e a mensagem e códigos de erro estão dentro do objeto
     *
     * @see cas_object
     **/
    public function salvarDocumento(InfraCasObject &$obj)  {
        return $this->core_process(CASTOR_OPER_WRITE, $obj);
    }
    
    /**
     * Função para ler objeto do sistema de armazenamento Swarm
     * @param InfraCasObject $obj - Classe contendo o UUID do objeto a ser recuperado
     * @return bool true, se foi bem sucedida, caso contrario retorna false e a mensagem e códigos de erro estão dentro do objeto
     *
     * @see cas_object
     **/
    public function recuperarDocumento(InfraCasObject &$obj)  {
        return $this->core_process(CASTOR_OPER_READ, $obj);
    }
    
    /**
     * Função para ler apenas o cabeçalho do objeto armazenado no Swarm
     * @param InfraCasObject $obj - Classe contendo o UUID do objeto a ser recuperado
     * @return bool true, se foi bem sucedida, caso contrario retorna false e a mensagem e códigos de erro estão dentro do objeto
     *
     * @see cas_object
     **/
    public function infoDocumento(InfraCasObject &$obj)  {
        return $this->core_process(CASTOR_OPER_INFO, $obj);
    }
    
    /**
     * Função para apagar objeto do sistema de armazenamento Swarm
     * @param InfraCasObject $obj - Classe contendo o UUID do objeto a ser apagado
     * @return bool true, se foi bem sucedida, caso contrario retorna false e a mensagem e códigos de erro estão dentro do objeto
     *
     * @see cas_object
     **/
    public function apagarDocumento(InfraCasObject &$obj)  {
        return $this->core_process(CASTOR_OPER_DELETE, $obj);
    }
}