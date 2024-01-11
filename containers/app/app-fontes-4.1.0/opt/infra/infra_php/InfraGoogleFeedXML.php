<?php
/**
 *
 * @author Bruno Medeiros
 * @email bruno.medeiros@e-storageonline.com.br
 *
 *Classe respons·vel por gerar o XML do feed
 */

class InfraGoogleFeedXML
{

    private $_xmlFeed;
    private $_gsafeed;
    protected $_datasource;
    private $_feedtype;
    private $_group;
    private $_metadata;
    private $_record;

    /**
     * @method Contrutor
     */

    function __construct()
    {
        $imp = new DOMImplementation;
        $dtd = $imp->createDocumentType('gsafeed PUBLIC "-//Google//DTD GSA Feeds//EN" "gsafeed.dtd"');
        $this->_xmlFeed = $imp->createDocument("", "", $dtd);
        $this->_xmlFeed->encoding = 'UTF-8';
        //$this->_xmlFeed ->encoding = 'ISO-8859-1';
        $this->_xmlFeed->formatOutput = true;
        $this->_gsafeed = $this->_xmlFeed->appendChild($this->_xmlFeed->createElement('gsafeed'));
        $header = $this->_gsafeed->appendChild($this->_xmlFeed->createElement('header'));
        $this->_datasource = $header->appendChild($this->_xmlFeed->createElement('datasource'));
        $this->_feedtype = $header->appendChild($this->_xmlFeed->createElement('feedtype'));
    }

    /**
     * Nome e tipo de feed
     *
     * @param string $strNome
     * @param string $strFeedType ( full | incremental )
     * @example $objFeedXml->setFeed( 'NomeDoFeed' , 'full');
     */

    public function setFeed($strNome, $strFeedType = 'incremental')
    {
        $this->_datasource->appendChild($this->_xmlFeed->createTextNode($strNome));
        $this->_feedtype->appendChild($this->_xmlFeed->createTextNode($strFeedType));
    }

    /**
     * Novo Grupo ( add | delete )
     * @param unknown_type $strTipo
     *
     * @example $objFeedXml->addGroup( 'add' );
     */

    public function addGroup($strTipo)
    {
        $this->_group = $this->_gsafeed->appendChild($this->_xmlFeed->createElement('group'));
        $this->_group->setAttribute('action', $strTipo);
    }

    /**
     * Adiciona Record
     *
     * @param URl $urlUrl
     * @param string $strMime
     * @param data $dtModificacao
     * @param boolean $bolLock
     *
     * @example $objFeedXml->addRecord( 'http://minhaurl.com?id=1' , 'text/plain' , '2010-01-01' , FALSE );
     */

    /*
    public function addRecord( $urlUrl , $strMime , $dtModificacao = FALSE , $bolLock = FALSE ){

        $this->_record = $this->_group ->appendChild( $this->_xmlFeed->createElement( 'record' ) );
        $this->_record ->setAttribute( 'url' , $urlUrl );
        $this->_record ->setAttribute( 'mimetype' , $strMime );
        if( $dtModificacao ) $this->_record->setAttribute( 'last-modified' , $dtModificacao );
        if( $bolLock ) $this->_record->setAttribute( 'lock' , 'true' );
    }
    */


    public function addRecord($urlUrl, $strMime = null, $dtModificacao = false, $bolLock = false)
    {
        $this->_record = $this->_group->appendChild($this->_xmlFeed->createElement('record'));
        $this->_record->setAttribute('url', $urlUrl);
        if ($strMime) {
            $this->_record->setAttribute('mimetype', $strMime);
        }
        if ($dtModificacao) {
            $this->_record->setAttribute('last-modified', $dtModificacao);
        }
        if ($bolLock) {
            $this->_record->setAttribute('lock', 'true');
        }
    }

    /**
     * Inicia o bloco de metatags do Record
     */

    public function addMetadata()
    {
        $this->_metadata = $this->_record->appendChild($this->_xmlFeed->createElement('metadata'));
    }

    /**
     * Adiciona MetaTags
     * @param string $strNome
     * @param string $strConteudo
     * @example $objFeedXml->addMetatag( 'nome' , 'valor' );
     */

    public function addMetatag($strNome, $strConteudo)
    {
        $meta = $this->_metadata->appendChild($this->_xmlFeed->createElement('meta'));
        $meta->setAttribute('name', InfraString::toUTF8($strNome));
        $meta->setAttribute('content', InfraString::toUTF8($strConteudo));
    }

    /**
     * Adiciona Conteudo
     * @param string $strConteudo
     * @example $objFeedXml->addCdata('conte√∫do bla_blabla_bla...');
     */

    public function addCdata($strConteudo)
    {
        $content = $this->_record->appendChild($this->_xmlFeed->createElement('content'));
        $cdata = $this->_xmlFeed->createCDATASection($strConteudo);
        $content->appendChild($cdata);
    }

    /**
     * Adiciona conteudo codificado
     * @param string $strConteudo
     * @example $objFeedXml->addCdata64('conte√∫do bla_blabla_bla...');
     */

    public function addCdata64($strConteudo)
    {
        $content = $this->_record->appendChild($this->_xmlFeed->createElement('content'));
        $cdata = $this->_xmlFeed->createCDATASection($strConteudo);
        $content->appendChild($cdata);
        $content->setAttribute('encoding', 'base64binary');
    }

    /**
     * Salva Feed
     * @param path $pthPath
     * @example $objFeedXml->saveFeed('./feed.xml');
     */

    public function saveFeed($pthPath)
    {
        $this->_xmlFeed->save($pthPath);
    }

    /**
     * retorn documento XML
     * @return Documento XML
     */

    public function getXMLFeed()
    {
        return $this->_xmlFeed->saveXML();
    }

}

