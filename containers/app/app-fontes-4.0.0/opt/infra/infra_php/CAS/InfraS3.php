<?php
require_once "IInfraCAS.php";
require_once "InfraS3ScopeAdapter.php";
require_once "InfraCasObject.php";

use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;
use Aws\Exception\AwsException;

/**
 * Classe infra que conecta e troca informa��s com servidor de arquivos utilizando o protocolo S3
 *
 * @author j.rnascimento
 *        
 */
class InfraS3 implements IInfraCAS
{
    private $objConfigurationScope;
    private $objArrS3Client = array();  
    private $intArrayPosition = 0;

    public function __construct(InfraS3ScopeAdapter $objScope)
    {
        $this->objConfigurationScope = $objScope;
        $this->setEnvironmnent();
    }

    /**
     * Monta o caminho completo para o local do arquivo no reposit�rio CAS
     * @param InfraCasObject $obj
     * @return string
     */
    private function montarCaminhoCompleto(InfraCasObject $obj) {
        // Se diret�rio n�o informado, o arquivo est�/estar� na ra�z do reposit�rio, nomeado com o seu id
        $path = $obj->castor_iddocumento;
        if (!empty($obj->folderFileServer)) {
            // O arquivo est�/estar� no diret�rio informado, nomeado com seu id
            $ultimoChar = substr($obj->folderFileServer, strlen($obj->folderFileServer) - 1);
            // Se necess�rio, concatena a barra no final do diret�rio informado
            if (strcmp($ultimoChar, '/') != 0) {
                $path = '/'. $path;
            }
            // Monta o caminho completo do arquivo no reposit�rio
            $path = $obj->folderFileServer . $path;
        }
        return $path;
    }

    /**
     * Salva um arquivo em determinado bucket
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
     */
    public function salvarDocumento(InfraCasObject &$obj)
    {
        $bolRetorno = false;
        $objInfraException = new InfraException();
        
        if (empty($obj->castor_iddocumento)) {
            $objInfraException->adicionarValidacao("Informe o 'castor_iddocumento' do arquivo desejado!");
        }
        $objInfraException->lancarValidacoes();

        $caminhoCompleto = $this->montarCaminhoCompleto($obj);

        $param = [
            'Body' => $obj->body,
            'Bucket' => $this->objConfigurationScope->getStrBucket(),
            'ContentType' => $obj->content_type,
            'Key' => $caminhoCompleto
        ];
        if (isset($obj->contentSHA256)) {
            $param['ContentSHA256'] = $obj->contentSHA256;
        }
        
        try {
            $objAwsResult = $this->objArrS3Client[$this->intArrayPosition]->putObject($param);
            $obj->uuid = $objAwsResult["ETag"];
            $bolRetorno = true;
        } catch (AwsException $e) {
            throw new InfraException("N�o foi poss�vel salvar o arquivo {$obj->filename} (id: {$obj->castor_iddocumento}) no CAS S3! Motivo: {$e->__toString()}");
        }
        
        return $bolRetorno;
    }

    /**
     * 
     * Retorna arquivo de um bucket
     *
     * {@inheritdoc}
     * @see IInfraCAS::recuperarDocumento()
     */
    public function recuperarDocumento(InfraCasObject &$obj)
    {
        $objInfraException = new InfraException();
        
        if (empty($obj->castor_iddocumento)) {
            $objInfraException->adicionarValidacao("Informe o 'castor_iddocumento' do arquivo desejado!");
        }
        $objInfraException->lancarValidacoes();

        $caminhoCompleto = $this->montarCaminhoCompleto($obj);

        foreach ($this->objArrS3Client as $objS3Client) {
            try {
                $objFile = $objS3Client->getObject([
                    'Bucket' => $this->objConfigurationScope->getStrBucket(),
                    'Key' => $caminhoCompleto
                ]);
                $obj->uuid = $objFile["ETag"];
                $obj->body = $objFile["Body"];
                
                return true;
                
            } catch (AwsException $e) {
                if ($e->getCode() == "NoSuchKey") {
                    continue; //Esta exce��o � que o arquivo n�o foi encontrado no bucket. Infelizmente n�o tem uma fun��o que retorne um Count
                } else {
                    throw new InfraException("Erro ao recuperar o arquivo {$obj->filename} (id: {$obj->castor_iddocumento}) do CAS S3! Motivo: {$e->__toString()}");
                }
            }
            return false;
        }                        
        $objInfraException->adicionarValidacao("Arquivo de id {$obj->castor_iddocumento} n�o encontrado no CAS S3!");
        $objInfraException->lancarValidacoes();
    }

    public function saveDataToCache($key, $data)
    {}

    public function logError($loglevel, $operation, $error_code, $error_message, $obj)
    {}

    public function logTimer($operation, $timeinms, $obj)
    {}

    public function apagarDocumento(InfraCasObject &$obj)
    {
        $objInfraException = new InfraException();

        if (empty($obj->castor_iddocumento)) {
            $objInfraException->adicionarValidacao("Informe o 'castor_iddocumento' do arquivo desejado!");
        }

        $objInfraException->lancarValidacoes();
        $caminhoCompleto = $this->montarCaminhoCompleto($obj);

        foreach ($this->objArrS3Client as $objS3Client) {
            try {
                $objS3Client->deleteObject([
                    'Bucket' => $this->objConfigurationScope->getStrBucket(),
                    'Key' => $caminhoCompleto
                ]);

                return true;
            } catch (AwsException $e) {
                if ($e->getCode() == "NoSuchKey") {
                    continue; //Esta exceção é que o arquivo não foi encontrado no bucket. Infelizmente não tem uma função que retorne um Count
                } else {
                    throw new InfraException("Erro ao deletar o arquivo {$obj->filename} (id: {$obj->castor_iddocumento}) do CAS S3! Motivo: {$e->__toString()}");
                }
            }
            return false;
        }
    }

    public function readDataFromCache($key)
    {}

    public function infoDocumento(InfraCasObject &$obj)
    {}

    /**
     * 
     * Configura o ambiente ao qual este wrapper ir� interagir
     */
    private function setEnvironmnent()
    {
        try {
            $arrObjInfraCasNode = $this->objConfigurationScope->getObjInfraCasClusters()->maincluster->getActiveNodes();
            if (count($arrObjInfraCasNode) > 0) {
                foreach ($arrObjInfraCasNode as $objInfraCasNode) {
                    $strEndPoint = "{$this->objConfigurationScope->getStrScheme()}://{$this->objConfigurationScope->getObjInfraCasClusters()->domain}.{$objInfraCasNode->url}";
                    $this->objArrS3Client[] = new Aws\S3\S3Client([
                        'version' => 'latest',
                        'region' => 'sa-east-1', // regi�o de S�o Paulo
                        'credentials' => [
                            'key' => $this->objConfigurationScope->getStrKey(),
                            'secret' => $this->objConfigurationScope->getStrSecret()
                        ],
                        'scheme' => $this->objConfigurationScope->getStrScheme(),
                        'endpoint' => $strEndPoint,
                        'Bucket' => $this->objConfigurationScope->getStrBucket(),
                        'debug' => $this->objConfigurationScope->getBolEnableDebugging(),
                        'use_path_style_endpoint' => $this->objConfigurationScope->getBolPathStyleEndPoint() // devido ao formato do certificado, deve-se deixar esta op��o ativada, pois coloca o bucket na frente do endpoint. http[s]://tenant.endpoint/bucket ao inv�s de http[s]://bucket.tenant.endpoint/
                    ]);
                    
                    $this->intArrayPosition = $this->selectRandomIndexNode();
                }
            } else {
                throw new InfraException("Todos os servidores CAS est�o offline, ou lentos, ou n�o est�o devidamente configurados em sua aplica��o!");
            }
        } catch (AwsException $e) {
            throw new InfraException("Erro ao conectar no servidor CAS S3! Motivo: {$e->__toString()}");
        }
    }

    /**
     * 
     * Retorna o nome de um arquivo por ETag
     *
     * @param string $strETag
     *            - o uuid armazenado em banco de dados
     * @return string
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjectsv2
     */
    private function getFileNameInBucketByETag($strETag)
    {   
        foreach ($this->objArrS3Client as $objS3Client) {
            $objArrResultPaginator = $objS3Client->getPaginator('ListObjects', ['Bucket' => $this->objConfigurationScope->getStrBucket()]);            
            foreach ($objArrResultPaginator as $objResultPaginator) {
                $strNomeArquivo = $objResultPaginator->search("Contents[?contains(ETag,'{$strETag}')]|[0].Key");
                if(strlen($strNomeArquivo) > 0){
                    return $strNomeArquivo ;
                }
            }            
        }
    }

    /**
     * 
     * Seleciona randomicamente uma posi��o para o array objArrS3Client 
     * @return number
     */
    private function selectRandomIndexNode(){
        
        $intEndPointSize = count($this->objArrS3Client) - 1;
        
        return rand(0, $intEndPointSize);
        
    }
}