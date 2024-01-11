<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EmailUnidadeINT extends InfraINT {

  public static function montarSelectEmail($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objEmailUnidadeDTO = new EmailUnidadeDTO();
    $objEmailUnidadeDTO->retNumIdEmailUnidade();
    $objEmailUnidadeDTO->retStrEmail();
    $objEmailUnidadeDTO->retStrDescricao();
    $objEmailUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objEmailUnidadeDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objEmailUnidadeDTO->setOrdStrEmail(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objEmailUnidadeRN = new EmailUnidadeRN();
    $arrObjEmailUnidadeDTO = $objEmailUnidadeRN->listar($objEmailUnidadeDTO);
    
    $arrEmailUnidade = array();
    
    foreach($arrObjEmailUnidadeDTO as $objEmailUnidadeDTO){
    	$arrEmailUnidade[EmailINT::formatarNomeEmailRI0960(SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual(),$objEmailUnidadeDTO->getStrDescricao(),$objEmailUnidadeDTO->getStrEmail())] = InfraString::formatarXML(EmailINT::formatarNomeEmailRI0960(SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual(),$objEmailUnidadeDTO->getStrDescricao(),$objEmailUnidadeDTO->getStrEmail()));
    }
  
    return parent::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, PaginaSEI::tratarHTML($strValorItemSelecionado), $arrEmailUnidade);
  }

  public static function validarEmailUnidadeRemetente($strEmailRemetente){
    $objEmailUnidadeDTO = new EmailUnidadeDTO();
    $objEmailUnidadeDTO->retStrEmail();
    $objEmailUnidadeDTO->retStrDescricao();
    $objEmailUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objEmailUnidadeRN = new EmailUnidadeRN();
    $arrObjEmailUnidadeDTO = $objEmailUnidadeRN->listar($objEmailUnidadeDTO);

    $bolEncontrou = false;
    foreach ($arrObjEmailUnidadeDTO as $objEmailUnidadeDTO) {

      $strEmailUnidade = EmailINT::formatarNomeEmailRI0960(SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual(), $objEmailUnidadeDTO->getStrDescricao(), $objEmailUnidadeDTO->getStrEmail());
      $strEmailUnidade = str_replace('&lt;','<',$strEmailUnidade);
      $strEmailUnidade = str_replace('&gt;','>',$strEmailUnidade);

      if ($strEmailRemetente == $strEmailUnidade) {
        $bolEncontrou = true;
        break;
      }
    }

    if (!$bolEncontrou) {
      throw new InfraException('Email do remetente '.InfraString::formatarXML($_POST['selDe']).' não é válido para a unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
    }
  }
}
?>