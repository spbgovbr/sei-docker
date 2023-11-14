<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/10/2011 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

//require_once dirname(__FILE__).'/../Infra.php';

class InfraAuditoriaINT extends InfraINT {

  public static function formatarUsuario(InfraAuditoriaDTO $objInfraAuditoriaDTO){
    $ret = $objInfraAuditoriaDTO->getStrSiglaUsuario().' / '.$objInfraAuditoriaDTO->getStrSiglaOrgaoUsuario().' - '.$objInfraAuditoriaDTO->getStrNomeUsuario();
    if ($objInfraAuditoriaDTO->getNumIdUsuarioEmulador()!=null){
      $ret .= ' (emulado por '.$objInfraAuditoriaDTO->getStrSiglaUsuarioEmulador().' / '.$objInfraAuditoriaDTO->getStrSiglaOrgaoUsuarioEmulador().' - '.$objInfraAuditoriaDTO->getStrNomeUsuarioEmulador().')';
    }
    return $ret;
  }

  public static function formatarUnidade(InfraAuditoriaDTO $objInfraAuditoriaDTO){
    return $objInfraAuditoriaDTO->getStrSiglaUnidade().' / '.$objInfraAuditoriaDTO->getStrSiglaOrgaoUnidade().' - '.$objInfraAuditoriaDTO->getStrDescricaoUnidade();
  }

  public static function montarSelectCamposRetorno($arrCamposExibicao){

    $arrObjArrInfraValorStaDTO = InfraAuditoriaRN::listarCamposRetorno();

    $ret = '';
    $numCampos = count($arrObjArrInfraValorStaDTO);
    for($i=0;$i<$numCampos;$i++){
      $ret .= '<option value="'.$arrObjArrInfraValorStaDTO[$i]->getStrStaValor().'"';
      if (in_array($arrObjArrInfraValorStaDTO[$i]->getStrStaValor(), $arrCamposExibicao)) {
        $ret .= ' selected="selected"';
      }
      $ret .= '>'.InfraPagina::tratarHTML($arrObjArrInfraValorStaDTO[$i]->getStrDescricao()).'</option>';
    }
    return $ret;
  }

  public static function montarSelectRegistrosPagina($numRegistrosPagina){
    $arr = array('100' => 100, '200' => 200, '300' => 300, '400' => 400, '500' => 500);
    return InfraINT::montarSelectArray(null,null,$numRegistrosPagina,$arr);
  }
}
?>