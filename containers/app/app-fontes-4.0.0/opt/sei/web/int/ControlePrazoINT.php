<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/08/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ControlePrazoINT extends InfraINT {

  public static function montarSelectIdControlePrazo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $dblIdProtocolo='', $numIdUnidade='', $numIdUsuario=''){
    $objControlePrazoDTO = new ControlePrazoDTO();
    $objControlePrazoDTO->retNumIdControlePrazo();

    if ($dblIdProtocolo!==''){
      $objControlePrazoDTO->setDblIdProtocolo($dblIdProtocolo);
    }

    if ($numIdUnidade!==''){
      $objControlePrazoDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($numIdUsuario!==''){
      $objControlePrazoDTO->setNumIdUsuario($numIdUsuario);
    }

    $objControlePrazoDTO->setOrdNumIdControlePrazo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objControlePrazoRN = new ControlePrazoRN();
    $arrObjControlePrazoDTO = $objControlePrazoRN->listar($objControlePrazoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjControlePrazoDTO, 'IdControlePrazo', 'IdControlePrazo');
  }

  public static function montarSelectAnos($strAno){

    $objControlePrazoRN = new ControlePrazoRN();

    $objControlePrazoDTO = new ControlePrazoDTO();
    $objControlePrazoDTO->setNumMaxRegistrosRetorno(1);
    $objControlePrazoDTO->retDtaPrazo();
    $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objControlePrazoDTO->setOrdDtaPrazo(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objControlePrazoDTOInicio = $objControlePrazoRN->consultar($objControlePrazoDTO);

    $objControlePrazoDTO->setOrdDtaPrazo(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objControlePrazoDTOFim = $objControlePrazoRN->consultar($objControlePrazoDTO);

    $arrAnos = array();
    if ($objControlePrazoDTOInicio!=null && $objControlePrazoDTOFim!=null){
      $numAnoInicio = substr($objControlePrazoDTOInicio->getDtaPrazo(),6,4);
      $numAnoFim = substr($objControlePrazoDTOFim->getDtaPrazo(),6,4);
      while($numAnoInicio <= $numAnoFim){
        $arrAnos[$numAnoInicio] = $numAnoInicio;
        $numAnoInicio++;
      }
    }

    if ($strAno==''){
      $strAno = substr(InfraData::getStrDataAtual(),6,4);
    }

    return parent::montarSelectArray('null', 'Todos', $strAno, $arrAnos);
  }

  public static function montarIconeControlePrazo($bolAcaoControlePrazo, $objProcedimentoDTO, $bolTitulo, $strParametros = '',&$strIcone= "", &$strTexto = "")
  {

    $ret = '';

    if ($objProcedimentoDTO->isSetObjControlePrazoDTO() && $objProcedimentoDTO->getObjControlePrazoDTO() != null) {

      $objControlePrazoDTO = $objProcedimentoDTO->getObjControlePrazoDTO();

      if ($objControlePrazoDTO != null) {

        if(InfraString::isBolVazia($objControlePrazoDTO->getDtaConclusao())) {
          $strDataAtual = InfraData::getStrDataAtual();

          $numPrazo = InfraData::compararDatas($strDataAtual, $objControlePrazoDTO->getDtaPrazo());
          if ($numPrazo < 0) {
            $strIcone = Icone::CONTROLE_PRAZO3;
          } else {
            $strIcone = Icone::CONTROLE_PRAZO1;
          }

          $strTexto = $objControlePrazoDTO->getDtaPrazo().' (';
          if ($numPrazo == 0) {
            $strTexto .= 'até hoje';
          } else if ($numPrazo == 1) {
            $strTexto .= '1 dia';
          } else if ($numPrazo > 1) {
            $strTexto .= $numPrazo.' dias';
          } else if ($numPrazo == -1) {
            $strTexto .= 'atrasado 1 dia';
          } else if ($numPrazo < -1) {
            $strTexto .= 'atrasado '.abs($numPrazo).' dias';
          }
          $strTexto .= ')';
        }else{
          $strTexto = $objControlePrazoDTO->getDtaPrazo().' (concluído em '.$objControlePrazoDTO->getDtaConclusao().')';
          $strIcone = Icone::CONTROLE_PRAZO2;
        }

        if ($bolAcaoControlePrazo) {
          $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_prazo_definir&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_prazo=' . $objControlePrazoDTO->getNumIdControlePrazo() . '&id_procedimento=' . $objControlePrazoDTO->getDblIdProtocolo() . $strParametros);
        } else {
          $strLink = 'javascript:void(0);';
        }

        if ($bolTitulo) {
          $strTexto = $objControlePrazoDTO->getStrSiglaUsuario() . ' ' . $strTexto;
        } else {
          $strTexto = 'Controle de Prazo:\n' . $objControlePrazoDTO->getStrSiglaUsuario() . ' ' . $strTexto;
        }

        $ret = '<a href="' . $strLink . '" ' . PaginaSEI::montarTitleTooltip($strTexto, "Controle de Prazo") . '><img src="' . $strIcone . '" class="imagemStatus" /></a>';
      }
    }
    return $ret;
  }

}
