<?
class SeiSolrUtil {

	public static $MSG_ERRO_PESQUISA = 'Erro realizando pesquisa.\n\nVerifique se não faltam operadores (e, ou, não) ou caracteres (aspas, parênteses) entre as palavras do campo de pesquisa.';
  public static $MSG_ERRO_SERVIDOR_NAO_CONFIGURADO = 'Servidor de pesquisa não foi configurado.';

	public static function criarBarraEstatisticas($total,$inicio,$fim)	{
    return "<div class=\"pesquisaBarra\">".self::obterTextoBarraEstatisticas($total,$inicio,$fim)."</div>";
	}

	public static function obterTextoBarraEstatisticas($total,$inicio,$fim)	{
	  $ret = '';
	  if ($total > 0 && $total != "") {
	    if ($total < $fim) {
	      $ret .= $total.' resultado'.($total>1?'s':'');
	    } else {
	      $ret .= "Exibindo " . ($inicio+1) . " - " . $fim . " de " . $total;
	    }
	  }
	  return $ret;
	}
	  
	public static function criarBarraNavegacao($totalRes, $inicio, $numResPorPag)
	{
		
		if ($totalRes == 0)
			return;
		
		$nav = "<div class=\"pesquisaPaginas\">";
		
		$paginaAtual = $inicio / $numResPorPag + 1;
		
		if ($inicio >= $numResPorPag ) {
			$nav .= "<a href=\"javascript:navegar('" . ($inicio - $numResPorPag) . "')\">Anterior</a>\n";
		}
		 
		if ($totalRes > $numResPorPag){

		  $numPagParaClicar = 12;
		  
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
		}
		 
		if (($inicio + $numResPorPag) < $totalRes){
			$nav .= "<a href=\"javascript:navegar('" . ($inicio + $numResPorPag) . "')\">Próxima</a>\n";
		}
		 
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