<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MarcadorINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade=''){
    $objMarcadorDTO = new MarcadorDTO();
    $objMarcadorDTO->retNumIdMarcador();
    $objMarcadorDTO->retStrNome();

    if ($numIdUnidade!==''){
      $objMarcadorDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($strValorItemSelecionado!=null){
      $objMarcadorDTO->setBolExclusaoLogica(false);
      $objMarcadorDTO->adicionarCriterio(array('SinAtivo','IdMarcador'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMarcadorDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMarcadorRN = new MarcadorRN();
    $arrObjMarcadorDTO = $objMarcadorRN->listar($objMarcadorDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMarcadorDTO, 'IdMarcador', 'Nome');
  }

  public static function montarSelectMarcador($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $bolApenasSelecionado = false){
    $ret = '';

    $objMarcadorDTO = new MarcadorDTO();
    $objMarcadorDTO->retNumIdMarcador();
    $objMarcadorDTO->retStrNome();
    $objMarcadorDTO->retStrStaIcone();
    $objMarcadorDTO->retStrSinAtivo();
    $objMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    if ($strValorItemSelecionado!=null){

      if ($bolApenasSelecionado){
        $objMarcadorDTO->setBolExclusaoLogica(false);
        $objMarcadorDTO->setNumIdMarcador($strValorItemSelecionado);
      }else{
        $objMarcadorDTO->setBolExclusaoLogica(false);
        $objMarcadorDTO->adicionarCriterio(array('SinAtivo','IdMarcador'),
          array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
          array('S',$strValorItemSelecionado),
          InfraDTO::$OPER_LOGICO_OR);

      }
    }

    $objMarcadorDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMarcadorRN = new MarcadorRN();
    $arrObjMarcadorDTO = $objMarcadorRN->listar($objMarcadorDTO);

    foreach($arrObjMarcadorDTO as $dto){
      $dto->setStrNome(self::formatarMarcadorDesativado($dto->getStrNome(),$dto->getStrSinAtivo()));
    }

    $arrObjIconeMarcadorDTO = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(),'StaIcone');

    if (!$bolApenasSelecionado) {
      $ret .= '<option value="null" '.($strValorItemSelecionado === null ? 'selected="selected"' : '').'>'.$strPrimeiroItemDescricao.'</option>'."\n";
    }

    foreach ($arrObjMarcadorDTO as $objMarcadorDTO) {
      $ret .= '<option '.(($objMarcadorDTO->getNumIdMarcador()==$strValorItemSelecionado)?'selected="selected"':'').' value="' .$objMarcadorDTO->getNumIdMarcador() . '" data-imagesrc="'.$arrObjIconeMarcadorDTO[$objMarcadorDTO->getStrStaIcone()]->getStrArquivo().'">'.$objMarcadorDTO->getStrNome().'</option>'."\n";
    }

    return $ret;
  }

  public static function montarSelectStaIcone($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $ret = '';

    $objMarcadorRN = new MarcadorRN();
    $arrObjIconeMarcadorDTO = $objMarcadorRN->listarValoresIcone();

    InfraArray::ordenarArrInfraDTO($arrObjIconeMarcadorDTO,'Descricao',InfraArray::$TIPO_ORDENACAO_ASC);

    $ret .= '<option value="null" '.($strValorItemSelecionado===null?'selected="selected"':'').'>&nbsp;</option>'."\n";

    foreach ($arrObjIconeMarcadorDTO as $objIconeMarcadorDTO) {
      $ret .= '<option '.(($objIconeMarcadorDTO->getStrStaIcone()==$strValorItemSelecionado)?'selected="selected"':'').' value="' .$objIconeMarcadorDTO->getStrStaIcone() . '" data-imagesrc="'.$objIconeMarcadorDTO->getStrArquivo().'">'.$objIconeMarcadorDTO->getStrDescricao().'</option>'."\n";
    }

    return $ret;
  }

  public static function formatarMarcadorDesativado($strNomeMarcador, $strSinAtivoMarcador){
    return $strNomeMarcador.(($strSinAtivoMarcador == 'N')?' - DESATIVADO':'');
  }

  public static function montarSelectProcedimento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $dblIdProcedimento){
    $ret = '';

    $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
    $objAndamentoMarcadorDTO->setBolExclusaoLogica(false);
    $objAndamentoMarcadorDTO->setDistinct(true);
    $objAndamentoMarcadorDTO->retNumIdMarcador();
    $objAndamentoMarcadorDTO->retStrNomeMarcador();
    $objAndamentoMarcadorDTO->retStrStaIconeMarcador();
    $objAndamentoMarcadorDTO->retStrSinAtivoMarcador();
    $objAndamentoMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objAndamentoMarcadorDTO->setDblIdProcedimento($dblIdProcedimento);
    $objAndamentoMarcadorDTO->setNumIdMarcador(null,InfraDTO::$OPER_DIFERENTE);
    $objAndamentoMarcadorDTO->setOrdStrNomeMarcador(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
    $arrObjAndamentoMarcadorDTO = $objAndamentoMarcadorRN->listar($objAndamentoMarcadorDTO);

    foreach($arrObjAndamentoMarcadorDTO as $dto){
      $dto->setStrNomeMarcador(self::formatarMarcadorDesativado($dto->getStrNomeMarcador(),$dto->getStrSinAtivoMarcador()));
    }

    $objMarcadorRN = new MarcadorRN();
    $arrObjIconeMarcadorDTO = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(),'StaIcone');

    $ret .= '<option value="'.$strPrimeiroItemValor.'" '.($strValorItemSelecionado==null?'selected="selected"':'').'>'.$strPrimeiroItemDescricao.'</option>'."\n";

    foreach ($arrObjAndamentoMarcadorDTO as $objAndamentoMarcadorDTO) {
      $ret .= '<option '.(($objAndamentoMarcadorDTO->getNumIdMarcador()==$strValorItemSelecionado)?'selected="selected"':'').' value="' .$objAndamentoMarcadorDTO->getNumIdMarcador() . '" data-imagesrc="'.$arrObjIconeMarcadorDTO[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()]->getStrArquivo().'">'.$objAndamentoMarcadorDTO->getStrNomeMarcador().'</option>'."\n";
    }

    return $ret;
  }

  public static function montarSelectMarcadorRemocao($strValorItemSelecionado, $arrIdProtocolo){

    $ret = '';

    if (InfraArray::contar($arrIdProtocolo)) {

      $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
      $objAndamentoMarcadorDTO->setDistinct(true);
      $objAndamentoMarcadorDTO->retNumIdMarcador();
      $objAndamentoMarcadorDTO->setDblIdProcedimento($arrIdProtocolo, InfraDTO::$OPER_IN);
      $objAndamentoMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAndamentoMarcadorDTO->setStrSinUltimo('S');

      $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
      $arrIdMarcador = InfraArray::converterArrInfraDTO($objAndamentoMarcadorRN->listar($objAndamentoMarcadorDTO),'IdMarcador');

      if (count($arrIdMarcador)) {

        $objMarcadorDTO = new MarcadorDTO();
        $objMarcadorDTO->setBolExclusaoLogica(false);
        $objMarcadorDTO->retNumIdMarcador();
        $objMarcadorDTO->retStrNome();
        $objMarcadorDTO->retStrStaIcone();
        $objMarcadorDTO->retStrSinAtivo();
        $objMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMarcadorDTO->setNumIdMarcador($arrIdMarcador, InfraDTO::$OPER_IN);
        $objMarcadorDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMarcadorRN = new MarcadorRN();
        $arrObjMarcadorDTO = $objMarcadorRN->listar($objMarcadorDTO);

        foreach ($arrObjMarcadorDTO as $dto) {
          $dto->setStrNome(self::formatarMarcadorDesativado($dto->getStrNome(), $dto->getStrSinAtivo()));
        }

        $arrObjIconeMarcadorDTO = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(), 'StaIcone');

        if (count($arrObjMarcadorDTO) == 1){
          $strValorItemSelecionado = $arrObjMarcadorDTO[0]->getNumIdMarcador();
        }

        $ret .= '<option value="null" '.($strValorItemSelecionado === null ? 'selected="selected"' : '').'>&nbsp;</option>'."\n";

        foreach ($arrObjMarcadorDTO as $objMarcadorDTO) {
          $ret .= '<option '.(($objMarcadorDTO->getNumIdMarcador() == $strValorItemSelecionado) ? 'selected="selected"' : '').' value="'.$objMarcadorDTO->getNumIdMarcador().'" data-imagesrc="'.$arrObjIconeMarcadorDTO[$objMarcadorDTO->getStrStaIcone()]->getStrArquivo().'">'.$objMarcadorDTO->getStrNome().'</option>'."\n";
        }
      }
    }

    return $ret;
  }
}
?>