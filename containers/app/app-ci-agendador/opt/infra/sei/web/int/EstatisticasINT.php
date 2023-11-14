<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EstatisticasINT extends InfraINT {

  public static function montarGrafico($strGrafico, $strConteudo, $bolMontarBotaoVisualizacao = true ){
  	
  	echo '<br />';
  	
  	if ($bolMontarBotaoVisualizacao){
      echo '<button type="button" id="btnVer'.$strGrafico.'" onclick="seiExibirOcultarGrafico(\''.$strGrafico.'\')" class="infraButton" style="display:none;width:13em;">';
      echo   '<img src="imagens/sei_seta_abaixo.gif" style="float:left;vertical-align:middle;padding:.4em .2em .2em .5em;" />';
      echo   '<span style="float:left;padding-top:.1em;padding-left:1em;cursor:pointer;">Ver Gráfico</span>';
      echo '</button>';
      
      echo '<button type="button" id="btnOcultar'.$strGrafico.'" onclick="seiExibirOcultarGrafico(\''.$strGrafico.'\')" class="infraButton" style="width:13em;">';
      echo   '<img src="imagens/sei_seta_acima.gif" style="float:left;vertical-align:middle;padding:.4em .2em .2em .5em;" />';
      echo '  <span style="float:left;padding-top:.1em;padding-left:1em;cursor:pointer;">Ocultar Gráfico</span>';
      echo '</button>';
  	}
  	
    echo '<div id="div'.$strGrafico.'" style="display:block ;clear:both;width:700px;margin:0;">'."\n";
    echo $strConteudo;
    echo '</div>';
  }
  
  
  public static function montarSelectTipoInspecao($strValorItemSelecionado){
    
    $arr = array();
    $arr[EstatisticasRN::$TIPO_INSPECAO_ORGAOS_GERADOS] = EstatisticasRN::$TITULO_INSPECAO_ORGAOS_GERADOS;
    $arr[EstatisticasRN::$TIPO_INSPECAO_UNIDADES_GERADOS] = EstatisticasRN::$TITULO_INSPECAO_UNIDADES_GERADOS;
    $arr[EstatisticasRN::$TIPO_INSPECAO_TIPOS_GERADOS] = EstatisticasRN::$TITULO_INSPECAO_TIPOS_GERADOS;
    $arr[EstatisticasRN::$TIPO_INSPECAO_ORGAOS_TRAMITACAO] = EstatisticasRN::$TITULO_INSPECAO_ORGAOS_TRAMITACAO;
    $arr[EstatisticasRN::$TIPO_INSPECAO_UNIDADES_TRAMITACAO] = EstatisticasRN::$TITULO_INSPECAO_UNIDADES_TRAMITACAO;
    $arr[EstatisticasRN::$TIPO_INSPECAO_ORGAOS_DOCUMENTOS] = EstatisticasRN::$TITULO_INSPECAO_ORGAOS_DOCUMENTOS;
    $arr[EstatisticasRN::$TIPO_INSPECAO_UNIDADES_DOCUMENTOS] = EstatisticasRN::$TITULO_INSPECAO_UNIDADES_DOCUMENTOS;
    $arr[EstatisticasRN::$TIPO_INSPECAO_TIPOS_DOCUMENTOS] = EstatisticasRN::$TITULO_INSPECAO_TIPOS_DOCUMENTOS;
    $arr[EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO] = EstatisticasRN::$TITULO_INSPECAO_MOVIMENTACAO;
    
    
    return parent::montarSelectArray('null', '&nbsp;', $strValorItemSelecionado, $arr);
  }
}
?>