<?
class SeiSolrUtil {

	public static $MSG_ERRO_PESQUISA = 'Erro realizando pesquisa.\n\nVerifique se não faltam operadores (e, ou, não) ou caracteres (aspas, parênteses) entre as palavras do campo de pesquisa.';
  public static $MSG_ERRO_SERVIDOR_NAO_CONFIGURADO = 'Servidor de pesquisa não foi configurado.';

	public static function criarBarraEstatisticas($total,$inicio,$fim,$bolAncora=true)	{
    $ret = '';

    if ($total > 0 && $bolAncora) {
      $ret .= "\n".'<a name="ancoraBarraPesquisa"></a><br>'."\n";
    }

    $ret .= '<div class="pesquisaBarra">'."\n";

    if ($total > 0 && $bolAncora) {
      $ret .= '<div class="pesquisaBarraE"><button type="button" id="btnVerCriteriosPesquisa" onclick="infraMoverParaTopo();" class="infraButton">Ver Critérios de Pesquisa</button></div>'."\n";
    }

    $ret .=  '<div class="pesquisaBarraD">'.self::obterTextoBarraEstatisticas($total,$inicio,$fim).'</div>'."\n";
    $ret .= '</div>'."\n";
	  return $ret;
	}

	public static function obterTextoBarraEstatisticas($total,$inicio,$fim)	{
	  $ret = '';
	  if ($total > 0 && $total != "") {
	    if ($total < $fim) {
	      $ret .= $total.' resultado'.($total>1?'s':'');
	    } else {
	      $ret .= "Exibindo " . InfraUtil::formatarMilhares($inicio+1) . " - " . InfraUtil::formatarMilhares($fim) . " de " . InfraUtil::formatarMilhares(intval($total));
	    }
	  }
	  return $ret;
	}
	  
	public static function criarBarraNavegacao($totalRes, $inicio, $numResPorPag)
	{
		
		if ($totalRes == 0)
			return;
		
		$nav = '<div class="pesquisaPaginas d-flex flex-column flex-md-row text-align-center">';
		
		$paginaAtual = $inicio / $numResPorPag + 1;

    $nav .= '<div class="col-12 col-md-4 mx-0 px-0 text-center text-md-right">';
		if ($inicio >= $numResPorPag ) {
			$nav .= "<a href=\"javascript:navegar('" . ($inicio - $numResPorPag) . "')\">Anterior</a>";
		}
    $nav .= '</div>'."\n";
		 
		if ($totalRes > $numResPorPag){

      $nav .= '<div class="col-12 col-md-4 mx-0 px-0 text-center">'."\n";

		  $numPagParaClicar = 10;
		  
			if (ceil($totalRes / $numResPorPag) > $numPagParaClicar)
			{
				$iniNav = ($paginaAtual - floor(($numPagParaClicar - 1) / 2)) - 1;
				$fimNav = ($paginaAtual + ceil(($numPagParaClicar - 1) / 2));
				
				if ($iniNav < 0)
				{
					$iniNav = 0;
					$fimNav = $numPagParaClicar;
				}
				
				if ($fimNav > ceil($totalRes / $numResPorPag))
				{
					$fimNav = ceil($totalRes / $numResPorPag);
					$iniNav = $fimNav - $numPagParaClicar;
				}
			}
			else
			{
				$iniNav = 0;
				$fimNav = ceil($totalRes / $numResPorPag);
			}
			
			for ($i = $iniNav; $i < $fimNav; $i++)
			{
				if ($inicio == 0 AND $i == 0){
					$nav .= " <div class=\"pesquisaPaginaSelecionada\">" . ($i + 1) . "</div> ";
				}elseif (($i + 1) == ($inicio / $numResPorPag + 1)){
					$nav .= " <div class=\"pesquisaPaginaSelecionada\">" . ($i + 1) . "</div> ";
				}else{
					$nav .= " <a href=\"javascript:navegar('" . ($i * $numResPorPag) . "')\">" . ($i + 1) . "</a>\n";
				}
			}
      $nav .= '</div>'."\n";
		}

    $nav .= '<div class="col-12 col-md-4 mx-0 px-0 text-center text-md-left">';
		if (($inicio + $numResPorPag) < $totalRes){
			$nav .= "<a href=\"javascript:navegar('" . ($inicio + $numResPorPag) . "')\">Próxima</a>";
		}
    $nav .= '</div>'."\n";
		 
		$nav .= "</div>";
		 
		return $nav;
	}

	public static function tratarErroPesquisa($objInfraPagina, $e){
    if (ConfiguracaoSEI::getInstance()->getValor('Solr','Servidor')==null){
      $objInfraPagina->setStrMensagem(SeiSolrUtil::$MSG_ERRO_SERVIDOR_NAO_CONFIGURADO, InfraPagina::$TIPO_MSG_AVISO);
    }else {
      $objInfraPagina->setStrMensagem(SeiSolrUtil::$MSG_ERRO_PESQUISA, InfraPagina::$TIPO_MSG_AVISO);
      LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
    }
  }
}
?>