<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 27/03/2017 - criado por MGA
 *
 * @package infra_php
 */

class InfraMapa {

  public static function converterLatitudeMapa($latitude){
    $valorBase=5.608892288;
    $valorUnidade=-0.00020469;
    $ret=($latitude-$valorBase)/$valorUnidade;
    return floor($ret);
  }

  public static function converterLongitudeMapa($longitude) {
    $valorBase=-75.29307218;
    $valorUnidade=0.00021190;
    $ret=($longitude-$valorBase)/$valorUnidade;
    return floor($ret);
  }

  public static function converterArrayPontosJson($arrLocais){
    $ret='{"children":[';
    $v0='';
    foreach($arrLocais as $strLocal => $arrPonto){
      $ret.=$v0.'{';
      $ret.=' "local":"'.$strLocal.'", "cy": '.InfraMapa::converterLatitudeMapa($arrPonto[0]).', "cx": '.InfraMapa::converterLongitudeMapa($arrPonto[1]).', ';
      $v='';
      if (count($arrPonto[2]) == 1){
        $ret.='"id_ponto":"'.$arrPonto[2][0][0].'"';
        $ret.=', "descricao":"'.$arrPonto[2][0][1].'"';
        $ret.=', "cor":"'.$arrPonto[2][0][2].'"';
        $ret.=', "href":"'.$arrPonto[2][0][3].'" ';
      } else {
        $ret.='"_children": [';
        foreach($arrPonto[2] as $p){
          $ret.= $v.' { "id_ponto":"'.$p[0].'"';
          $ret.=', "descricao":"'.$p[1].'"';
          $ret.=', "cor":"'.$p[2].'"';
          $ret.=', "href":"'.$p[3].'"}';
          $v=',';
        }
        $ret.="]";
      }
      $ret.="}\n";
      $v0=',';
    }
    $ret.= "]}";
    return $ret;
  }

}

?>