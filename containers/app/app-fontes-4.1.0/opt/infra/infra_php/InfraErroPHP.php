<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 16/03/2023 - criado por MGA
 *
 * CREATE TABLE infra_erro_php (
 *   id_infra_erro_php     varchar(32)  NOT NULL ,
 *   sta_tipo              integer  NOT NULL ,
 *   arquivo               varchar(255)  NOT NULL ,
 *   linha                 int  NOT NULL ,
 *   erro                  varchar(4000)  NOT NULL ,
 *   dth_cadastro          datetime  NOT NULL
 * );
 *
 * ALTER TABLE infra_erro_php ADD CONSTRAINT pk_infra_erro_php PRIMARY KEY (id_infra_erro_php  ASC);
 *
 */


class InfraErroPHP
{

    private static $instance = null;
    private $arrConfiguracoes = null;
    private $arrObjInfraErroPhpTipoDTO = null;
    private $bolRegistrar = false;

    public static $W_UNDEFINED_ARRAY_KEY = 1;
    public static $W_UNDEFINED_VARIABLE = 2;
    public static $W_UNDEFINED_PROPERTY = 3;
    public static $W_ATTEMPT_TO_READ_PROPERTY = 4;
    public static $W_TRYING_TO_ACCESS_ARRAY_OFFSET = 5;
    public static $W_ARRAY_TO_STRING_CONVERSION = 6;
    public static $W_RESOURCE_USED_AS_OFFSET_CASTING_TO_INTEGER = 7;
    public static $W_STRING_OFFSET_CAST_OCCURRED = 8;
    public static $W_UNINITIALIZED_STRING_OFFSET = 9;
    public static $W_CANNOT_ACCESS_OFFSET_OF_TYPE_STRING_ON_STRING = 10;
    public static $W_ARGUMENT_MUST_BE_PASSED_BY_REFERENCE = 11;
    public static $W_A_NON_NUMERIC_VALUE_ENCOUNTERED = 12;

    public static $T_LANCAR_EXCECAO = 'E';
    public static $T_IGNORAR = 'I';
    public static $T_REGISTRAR = 'R';


    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new InfraErroPHP();
        }
        return self::$instance;
    }

    public function __construct(){

    }

    public function setObjInfraIBanco(InfraIBanco $objInfraIBanco){
        BancoInfra::setObjInfraIBanco($objInfraIBanco);
        $this->bolRegistrar = true;
    }

    public function setBolRegistrar($bolRegistrar){
        $this->bolRegistrar = $bolRegistrar;
    }

    public function isBolRegistrar(){
        return $this->bolRegistrar;
    }

    public function configurar($numStaTipo, $strStaTratamento){

        if ($this->arrObjInfraErroPhpTipoDTO == null) {
            $objInfraErroPhpRN = new InfraErroPhpRN();
            $this->arrObjInfraErroPhpTipoDTO = InfraArray::indexarArrInfraDTO($objInfraErroPhpRN->listarValoresTipo(),'StaTipo');
        }

        if ($this->arrConfiguracoes == null){
            $this->arrConfiguracoes = array();
        }

        if (!isset($this->arrObjInfraErroPhpTipoDTO[$numStaTipo])){
          die('Tipo do erro PHP inválido ['.$numStaTipo.'].');
        }

        if (!in_array($strStaTratamento,array(self::$T_LANCAR_EXCECAO, self::$T_IGNORAR, self::$T_REGISTRAR))){
          die('Tipo do tratamento de erro PHP inválido ['.$strStaTratamento.'].');
        }

        $this->arrConfiguracoes[$numStaTipo] = $this->arrObjInfraErroPhpTipoDTO[$numStaTipo];
        $this->arrConfiguracoes[$numStaTipo]->setStrStaTratamento($strStaTratamento);

    }

    public function obterConfiguracoes(){
        return $this->arrConfiguracoes;
    }

    public function registrar($numStaTipo, $strArquivo, $numLinha, $strErro)
    {
        try {

            if ($this->isBolRegistrar()) {
                $objInfraErroPhpDTO = new InfraErroPhpDTO();
                $objInfraErroPhpDTO->setStrIdInfraErroPhp(null);
                $objInfraErroPhpDTO->setNumStaTipo($numStaTipo);
                $objInfraErroPhpDTO->setStrArquivo($strArquivo);
                $objInfraErroPhpDTO->setNumLinha($numLinha);
                $objInfraErroPhpDTO->setStrErro($strErro);

                $objInfraErroPhpRN = new InfraErroPhpRN();
                $objInfraErroPhpRN->registrar($objInfraErroPhpDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro gravando erro PHP.', $e);
        }
    }

    public function gerarTelaListagem($objInfraPagina, $objInfraSessao, $objInfraIBanco)
    {
        PaginaInfra::setObjInfraPagina($objInfraPagina);
        SessaoInfra::setObjInfraSessao($objInfraSessao);
        BancoInfra::setObjInfraIBanco($objInfraIBanco);
        require_once dirname(__FILE__) . '/formularios/infra_erro_php_lista.php';
    }
}

