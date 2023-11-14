<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AnexoINT extends InfraINT {

  public static function montarAnexos($arrIdAnexos, $bolAcaoDownload, $strAcaoDownload, &$arrAcoesDownload, $bolAcaoRemoverAnexo, &$arrAcoesRemover){

    $arrObjAnexoDTO = array();

    if (isset($_POST['hdnAnexos'])){
      $arrObjAnexoDTO = AnexoINT::processarRI0872($_POST['hdnAnexos']);
    }else if ($arrIdAnexos!=null && InfraArray::contar($arrIdAnexos)){
      $objAnexoRN = new AnexoRN();
      $objAnexoDTO = new AnexoDTO;
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retNumIdUnidade();
      $objAnexoDTO->retStrNome();
      $objAnexoDTO->retDthInclusao();
      $objAnexoDTO->retNumTamanho();
      $objAnexoDTO->retStrSiglaUsuario();
      $objAnexoDTO->retStrSiglaUnidade();
      $objAnexoDTO->setNumIdAnexo($arrIdAnexos,InfraDTO::$OPER_IN);
      $objAnexoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objAnexoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);
    }

    $arr = array();
    $arrAcoesDownload = array();
    $arrAcoesRemover = array();

    foreach($arrObjAnexoDTO as $objAnexoDTO){

      $arr[] = array($objAnexoDTO->getNumIdAnexo(),
          PaginaSEI::tratarHTML($objAnexoDTO->getStrNome()),
          $objAnexoDTO->getDthInclusao(),
          $objAnexoDTO->getNumTamanho(),
          InfraUtil::formatarTamanhoBytes($objAnexoDTO->getNumTamanho()),
          PaginaSEI::tratarHTML($objAnexoDTO->getStrSiglaUsuario()),
          PaginaSEI::tratarHTML($objAnexoDTO->getStrSiglaUnidade()));

      if ($bolAcaoDownload && is_numeric($objAnexoDTO->getNumIdAnexo())){
        $arrAcoesDownload[$objAnexoDTO->getNumIdAnexo()] = '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDownload.'&id_anexo='.$objAnexoDTO->getNumIdAnexo()).'" target="_blank"><img src="'.PaginaSEI::getInstance()->getIconeDownload().'" title="Baixar Anexo" alt="Baixar Anexo" class="infraImg" /></a> ';
      }

      if ($bolAcaoRemoverAnexo &&  (!is_numeric($objAnexoDTO->getNumIdAnexo()) || trim($objAnexoDTO->getStrSiglaUnidade())==trim(SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()))){
        $arrAcoesRemover[$objAnexoDTO->getNumIdAnexo()] = true;
      }
    }

    return PaginaSEI::getInstance()->gerarItensTabelaDinamica($arr);
  }

  public static function processarRI0872($strAnexos){
    $arrAnexos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($strAnexos);
    $arrObjAnexoDTO = array();
    foreach($arrAnexos as $anexo){
      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setNumIdAnexo($anexo[0]);
      $objAnexoDTO->setStrNome($anexo[1]);
      $objAnexoDTO->setDthInclusao($anexo[2]);
      $objAnexoDTO->setNumTamanho($anexo[3]);
      $objAnexoDTO->setStrSiglaUsuario($anexo[5]);
      $objAnexoDTO->setStrSiglaUnidade($anexo[6]);
      $objAnexoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $arrObjAnexoDTO[] = $objAnexoDTO;
    }
    return $arrObjAnexoDTO;
  }
}
?>