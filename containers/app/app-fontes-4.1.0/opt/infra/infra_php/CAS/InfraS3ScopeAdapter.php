<?php

/**
 * Esta classe serve como um escopo, contendo todas os parâmetros necessários para configurar o adaptador AWS e
 * passado por parâmetro para o método que constrói InfraS3
 * @author j.rnascimento
 *
 */
class InfraS3ScopeAdapter
{
    private $strBucket;
    private $strTenant;
    private $strScheme;
    private $strKey;
    private $strSecret;
    private $bolPathStyleEndPoint;
    private static $objInfraS3ScopeAdapter = null;
    private $objInfraCasClusters;
    private $bolDebug;

    public static function getInstance()
    {
        if (is_null(self::$objInfraS3ScopeAdapter)) {
            return new InfraS3ScopeAdapter();
        } else {
            return self::$objInfraS3ScopeAdapter;
        }
    }

    /**
     * Seta as URLS dos servidores S3 que estão armazenados em um objeto InfraCasClusters
     * @param InfraCasClusters[] $objInfraCasClusters - Objeto InfraCasClusters
     */
    public function setObjInfraCasClusters(InfraCasClusters $objInfraCasClusters): InfraS3ScopeAdapter
    {
        $this->objInfraCasClusters = $objInfraCasClusters;
        return $this;
    }

    /**
     * Seta o Esquema da página em HTTP ou HTTPS
     * @param string $strScheme O protocolo de comunicação
     */
    public function setStrScheme($strScheme): InfraS3ScopeAdapter
    {
        $this->strScheme = $strScheme;
        return $this;
    }

    /**
     * Seta o Tenant onde os buckets estão armazenados
     * @param string $strTenant - O tenant onde estão armazenados os buckets do servidor
     */
    public function setStrTenant($strTenant): InfraS3ScopeAdapter
    {
        $this->strTenant = $strTenant;
        return $this;
    }

    /**
     * Informa se o endereço do bucket vai logo após o endereço do endpoint, deixando a URL no seguinte formato http[s]://tenant.endpoint/bucket
     * Deixando esta opção desativada a url será montada da seguinte maneira: http[s]://bucket.tenant.endpoint. Dependendo do certificado (*.dominio), pode haver problema no
     * handshake de servidores
     */
    public function setBolPathStyleEndPoint($bolPutAtFinal): InfraS3ScopeAdapter
    {
        $this->bolPathStyleEndPoint = $bolPutAtFinal;
        return $this;
    }

    /**
     * Ativa o debug do Adaptador AWS
     * @param boolean $bolActivate
     */
    public function setBolEnableDebugging($bolActivate): InfraS3ScopeAdapter
    {
        $this->bolDebug = $bolActivate;
        return $this;
    }

    /**
     * Seta qual bucket dentro do tenant será utilizado para o envio dos arquivos
     * @param string $strBucket
     */
    public function setStrBucket($strBucket): InfraS3ScopeAdapter
    {
        $this->strBucket = $strBucket;
        return $this;
    }

    /**
     * Seta o usário de acesso do CAS S3
     * @param string $strKey - O nome de usuário criptografado em hash md5
     */
    public function setStrKey($strKey): InfraS3ScopeAdapter
    {
        $this->strKey = $strKey;
        return $this;
    }

    /**
     * Seta a senha de acesso ao CAS S3
     * @param string $strSecret - A senha de acesso criptografado em base 64
     */
    public function setStrSecret($strSecret): InfraS3ScopeAdapter
    {
        $this->strSecret = $strSecret;
        return $this;
    }

    /**
     * Retorna o esquema de comunicação com os servidores CAS S3, via http ou https
     * @return string - http ou https
     */
    public function getStrScheme()
    {
        return $this->strScheme;
    }

    /**
     * Retorna a senha de acesso ao servidor S3
     * @return string
     */
    public function getStrSecret()
    {
        // Hash md5, conforme documentação
        return $this->strSecret;
    }

    /**
     * Retorna o usuário de acesso da aplicação
     * @return string
     */
    public function getStrKey()
    {
        // Hash base 64, conforme documentação
        return $this->strKey;
    }

    /**
     * Retorna o status de debug do adaptador AWS
     */
    public function getBolEnableDebugging()
    {
        return $this->bolDebug;
    }

    /**
     * Esta opção é usada para ativar o recurso use_path_style_endpoint do adaptador AWS deixando o
     * formato da URL http[s]://tenant.endpoint/bucket ao invés de  http[s]://bucket.tenant.endpoint/
     * Isso resolve o conflito de certificado que exige URLS no formato *.hcp-fct2.tjrs.gov.br e *.hcp-tjrs.tjrs.gov.br ( apenas um nível de sub domínio)
     */
    public function getBolPathStyleEndPoint()
    {
        return $this->bolPathStyleEndPoint;
    }

    /**
     * Retorna o objeto
     * @return InfraCasClusters
     */
    public function getObjInfraCasClusters()
    {
        return $this->objInfraCasClusters;
    }

    /**
     * Retorna o bucket setado anteriormente
     * @return string
     */
    public function getStrBucket()
    {
        return $this->strBucket;
    }
}
