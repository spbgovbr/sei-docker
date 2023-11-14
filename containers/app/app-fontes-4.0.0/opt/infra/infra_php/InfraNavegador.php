<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/08/2012 - criado por MGA
 *
 * @package infra_php
 */

/*

CREATE TABLE infra_navegador
(
	id_infra_navegador    bigint  NOT NULL ,
	identificacao         varchar(50)  NOT NULL ,
	versao                varchar(20)  NULL ,
	user_agent            varchar(4000)  NOT NULL ,
	ip                    varchar(15)  NOT NULL ,
	dth_acesso            datetime  NOT NULL
)
go



ALTER TABLE infra_navegador
	ADD CONSTRAINT  pk_infra_navegador PRIMARY KEY (id_infra_navegador  ASC)
go

create table seq_infra_navegador (id bigint identity(1,1), campo char(1) null);

create index i01_infra_navegador ON infra_navegador (dth_acesso) INCLUDE (identificacao,versao);

*/

abstract class InfraNavegador extends InfraRN {

  public static $TN_NAO_IDENTIFICADO = 'Não Identificado';
  public static $TN_INTERNET_EXPLORER = 'Internet Explorer';
  public static $TN_FIREFOX = 'Firefox';
  public static $TN_CHROME = 'Chrome';
  public static $TN_SAFARI_IPAD = 'Safari/iPad';
  public static $TN_SAFARI = 'Safari';
	public static $TN_EDGE = 'Edge';

	public function __construct(InfraIBanco $objInfraIBanco) {
	  BancoInfra::setObjInfraIBanco($objInfraIBanco);
	}

	public function getNumTipoPK(){
		return InfraDTO::$TIPO_PK_SEQUENCIAL;
	}

	protected function inicializarObjInfraIBanco(){
    return BancoInfra::getInstance();
  }

  public static function obterDados($strUserAgent, &$strIdentificacao, &$strVersao){

    $strIdentificacao = self::$TN_NAO_IDENTIFICADO;
    $strVersao = InfraPagina::getNumVersaoInternetExplorer($strUserAgent);

    if ($strVersao != null){
      $strIdentificacao = self::$TN_INTERNET_EXPLORER;
    }else{

      $strVersao = InfraPagina::getNumVersaoFirefox($strUserAgent);

      if ($strVersao != null){

        $strIdentificacao = self::$TN_FIREFOX;

      }else{

        $strVersao = InfraPagina::getNumVersaoEdge($strUserAgent);

        if ($strVersao != null){

          $strIdentificacao = self::$TN_EDGE;

        }else{
          $strVersao = InfraPagina::getNumVersaoChrome($strUserAgent);

          if ($strVersao != null){

            $strIdentificacao = self::$TN_CHROME;

          }else{

            $strVersao = InfraPagina::getNumVersaoSafariIpad($strUserAgent);

            if ($strVersao != null){

              $strIdentificacao = self::$TN_SAFARI_IPAD;

            }else{

              $strVersao = InfraPagina::getNumVersaoSafari($strUserAgent);

              if ($strVersao != null){
                $strIdentificacao = self::$TN_SAFARI;
              }
            }
          }
        }
      }
    }
  }

  protected function registrarControlado() {
		try {

		  if ($this->getNumTipoPK()==InfraDTO::$TIPO_PK_SEQUENCIAL){
	      $objInfraSequencia = new InfraSequencia(BancoInfra::getInstance());
	  		$numProxSeq = $objInfraSequencia->obterProximaSequencia('infra_navegador');
		  }else if ($this->getNumTipoPK()==InfraDTO::$TIPO_PK_NATIVA){
  		  $numProxSeq = BancoInfra::getInstance()->getValorSequencia('seq_infra_navegador');
		  }else{
		  	throw new InfraException('Tipo PK inválida para infra_navegador.');
		  }


  		//Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)
  		//Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727)
  		//Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko
      //Mozilla/5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3
      //Mozilla/5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.9.2) Gecko/20100115 Firefox/3.6 ( .NET CLR 3.5.30729)
      //Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0) Gecko/20100101 Firefox/10.0
      //Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/530.8 (KHTML, like Gecko) Chrome/2.0.177.1 Safari/530.8
      //Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.186 Safari/535.1
      //Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10
			//Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X; pt-br) AppleWebKit/534.46.0 (KHTML, like Gecko) CriOS/21.0.1180.82 Mobile/10A403 Safari/7534.48.3
      //Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_2 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B146 Safari/8536.25
      //Mozilla/5.0 (Windows NT 6.2; ARM; Trident/7.0; Touch; rv:11.0; WPDesktop; Lumia 1320) like Gecko
			//Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240

      self::obterDados($_SERVER['HTTP_USER_AGENT'], $strIdentificacao, $strVersao);

      $objInfraNavegadorDTO = new InfraNavegadorDTO();
      $objInfraNavegadorDTO->setDblIdInfraNavegador($numProxSeq);
      $objInfraNavegadorDTO->setDthAcesso(InfraData::getStrDataHoraAtual());
      $objInfraNavegadorDTO->setStrIp(InfraUtil::getStrIpUsuario());
      $objInfraNavegadorDTO->setStrIdentificacao($strIdentificacao);
      $objInfraNavegadorDTO->setStrVersao($strVersao);
      $objInfraNavegadorDTO->setStrUserAgent($_SERVER['HTTP_USER_AGENT']);

      $objInfraNavegadorRN = new InfraNavegadorRN();
      $objInfraNavegadorRN->cadastrar($objInfraNavegadorDTO);

  	  return $numProxSeq;

		} catch(Exception $e){
      InfraDebug::getInstance()->gravarInfra($e->__toString());
			throw new InfraException('Erro gravando dados do navegador.',$e);
		}
  }

}
?>