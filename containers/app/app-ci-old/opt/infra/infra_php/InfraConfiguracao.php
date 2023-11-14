<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/10/2009 - criado por MGA
*
* @package infra_php
*/

abstract class InfraConfiguracao {
  
	public abstract function getArrConfiguracoes();
	
	public function getValor($strGrupo, $strChave = null, $bolErroNaoEncontrado=true, $strValorPadrao = null)
  {

    $arr = $this->getArrConfiguracoes();

    if (!isset($arr[$strGrupo])) {
      if ($bolErroNaoEncontrado) {
        throw new InfraException('Grupo de Configurações ' . $strGrupo . ' não encontrado.');
      }else {
        return $strValorPadrao;
      }
    }

    if ($strChave != null){
      if (!isset($arr[$strGrupo][$strChave])) {
        if ($bolErroNaoEncontrado) {
          throw new InfraException('Configuração ' . $strChave . ' não encontrada no grupo ' . $strGrupo . '.');
        }else {
          return $strValorPadrao;
        }
      }
      return $arr[$strGrupo][$strChave];
    }

    return $arr[$strGrupo];
	}
	
	public function isSetValor($strGrupo, $strChave = null){
	  
	  $arr = $this->getArrConfiguracoes();

	  if (!isset($arr[$strGrupo])){
	    return false;
	  }
	  
	  if ($strChave!=null && !isset($arr[$strGrupo][$strChave])){
	    return false;
	  }
	  
	  return true;;
	}
	
}
?>