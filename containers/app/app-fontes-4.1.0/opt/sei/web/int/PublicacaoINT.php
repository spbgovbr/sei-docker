<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/11/2008 - criado por mga
 *
 * Versão do Gerador de Código: 1.25.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoINT extends InfraINT {
   
  public static function montarSelectStaMotivoRI1061($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $dblIdProtocolo){

    $objPublicacaoRN = new PublicacaoRN();
    $arrObjVeiculoPublicacaoDTO = $objPublicacaoRN->listarValoresMotivoRN1056();

    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->retDblIdProtocoloAgrupador();
    $objProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);

    $objProtocoloRN = new ProtocoloRN();
    $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

    if ($objProtocoloDTO->getDblIdProtocoloAgrupador()!=$dblIdProtocolo){
      $arrTemp = array();
      foreach($arrObjVeiculoPublicacaoDTO as $objVeiculoPublicacaoDTO){
        if ($objVeiculoPublicacaoDTO->getStrStaMotivo()!=PublicacaoRN::$TM_PUBLICACAO || $objVeiculoPublicacaoDTO->getStrStaMotivo()==$strValorItemSelecionado){
          $arrTemp[] = $objVeiculoPublicacaoDTO;
        }
      }
      $arrObjVeiculoPublicacaoDTO = $arrTemp;
    }else{
      $arrTemp = array();
      foreach($arrObjVeiculoPublicacaoDTO as $objVeiculoPublicacaoDTO){
        if ($objVeiculoPublicacaoDTO->getStrStaMotivo()==PublicacaoRN::$TM_PUBLICACAO){
          $arrTemp[] = $objVeiculoPublicacaoDTO;
          break;
        }
      }
      $arrObjVeiculoPublicacaoDTO = $arrTemp;
      $strValorItemSelecionado = PublicacaoRN::$TM_PUBLICACAO;
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjVeiculoPublicacaoDTO, 'StaMotivo', 'Descricao');
  }
   
  public static function sugerirDataDisponibilizacaoRI1054($idOrgao,$idVeiculoPublicacao){
    $objPublicacaoDTO = new PublicacaoDTO();
    $objPublicacaoDTO->setNumIdOrgaoUnidadeResponsavelDocumento($idOrgao);
    $objPublicacaoDTO->setNumIdVeiculoPublicacao($idVeiculoPublicacao);    

    $objPublicacaoRN = new PublicacaoRN();

    return $objPublicacaoRN->obterProximaDataRN1055($objPublicacaoDTO);
  }

  public static function obterTextoInformativoPublicacao(DocumentoDTO $parObjDocumentoDTO) {
    global $SEI_MODULOS;
    $strResultado = '';
    if ($parObjDocumentoDTO->isSetObjPublicacaoDTO()){
      $objPublicacaoDTO = $parObjDocumentoDTO->getObjPublicacaoDTO();
      if ($objPublicacaoDTO != null) {
        if ($objPublicacaoDTO->getStrStaEstado() == PublicacaoRN::$TE_PUBLICADO) {

          $objPublicacaoAPI = new PublicacaoAPI();
          $objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
          $objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
          $objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());
          $objPublicacaoAPI->setIdDocumento($parObjDocumentoDTO->getDblIdDocumento());

          foreach ($SEI_MODULOS as $seiModulo) {
            if (($strTextoInformativo = $seiModulo->executar('montarTextoInformativoPublicacao', $objPublicacaoAPI)) != null) {
              $strResultado .= $strTextoInformativo."\n";
            }
          }

          if ($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao() == VeiculoPublicacaoRN::$TV_INTERNO) {
            $strResultado .= $objPublicacaoDTO->getStrNomeVeiculoPublicacao().' em '.$objPublicacaoDTO->getDtaDisponibilizacao()."\n";
            $strResultado .= self::montarDadosImprensaNacional($objPublicacaoDTO);
          } else {

            if (InfraString::isBolVazia($strResultado)) {
              $strResultado .= $objPublicacaoDTO->getStrNomeVeiculoPublicacao().' nº '.$objPublicacaoDTO->getNumNumero()."\n".
                  'Disponibilização: '.$objPublicacaoDTO->getDtaDisponibilizacao()."\n".
                  'Publicação: '.$objPublicacaoDTO->getDtaPublicacao()."\n";
              $strResultado .= self::montarDadosImprensaNacional($objPublicacaoDTO);
            }
          }

          //if ($objPublicacaoDTO->getNumIdVeiculoIO() != null) {
          //  $strResultado .= $objPublicacaoDTO->getStrSiglaVeiculoImprensaNacional().' de '.$objPublicacaoDTO->getDtaPublicacaoIO()
          //      .', Seção '.$objPublicacaoDTO->getStrNomeSecaoImprensaNacional()
          //      .', Página '.$objPublicacaoDTO->getStrPaginaIO();
          //}

        }
      }
    }
    return $strResultado;
  }

  public static function montarDadosImprensaNacional(PublicacaoDTO $objPublicacaoDTO){

    $strResultado = '';
    
    global $SEI_MODULOS;

    $objPublicacaoAPI = new PublicacaoAPI();
    $objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
    $objPublicacaoAPI->setIdVeiculoPublicacao($objPublicacaoDTO->getNumIdVeiculoPublicacao());
    $objPublicacaoAPI->setStaTipoVeiculo($objPublicacaoDTO->getStrStaTipoVeiculoPublicacao());

    foreach ($SEI_MODULOS as $seiModulo) {
      if (($strDadosImprensaNacional = $seiModulo->executar('montarDadosImprensaNacional', $objPublicacaoAPI)) != null){
        $strResultado .= $strDadosImprensaNacional;
      }
    }

    if(InfraString::isBolVazia($strResultado)){
      if (!InfraString::isBolVazia($objPublicacaoDTO->getStrSiglaVeiculoImprensaNacional())) {
        $strResultado .= PaginaSEI::tratarHTML($objPublicacaoDTO->getStrSiglaVeiculoImprensaNacional());

        if (!InfraString::isBolVazia($objPublicacaoDTO->getDtaPublicacaoIO())) {
          $strResultado .= " de ".$objPublicacaoDTO->getDtaPublicacaoIO();
        }

        if (!InfraString::isBolVazia($objPublicacaoDTO->getStrNomeSecaoImprensaNacional())) {
          $strResultado .= ", seção ".PaginaSEI::tratarHTML($objPublicacaoDTO->getStrNomeSecaoImprensaNacional());
        }

        if (!InfraString::isBolVazia($objPublicacaoDTO->getStrPaginaIO())) {
          $strResultado .= ", página ".$objPublicacaoDTO->getStrPaginaIO();
        }
      }
    }

    return $strResultado;
  }
}
?>