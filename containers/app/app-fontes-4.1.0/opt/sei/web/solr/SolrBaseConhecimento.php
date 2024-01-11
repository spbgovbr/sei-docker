<?
require_once dirname(__FILE__).'/../SEI.php';

class SolrBaseConhecimento {
	
	public static function executar(PesquisaBaseConhecimentoSolrDTO $objPesquisaBaseConhecimentoSolrDTO) {
	  
		$parametros = new stdClass();
		$parametros->q = InfraSolrUtil::formatarOperadores($objPesquisaBaseConhecimentoSolrDTO->getStrPalavrasChave());
		$parametros->q = utf8_encode($parametros->q);
		$parametros->start = $objPesquisaBaseConhecimentoSolrDTO->getNumInicioPaginacao();
		$parametros->rows = 10;
		$parametros->sort =  'dta_ger desc';

		$urlBusca = ConfiguracaoSEI::getInstance()->getValor('Solr','Servidor') . '/'.ConfiguracaoSEI::getInstance()->getValor('Solr','CoreBasesConhecimento') .'/select?' . http_build_query($parametros).'&hl=true&hl.snippets=2&hl.fl=content&hl.fragsize=100&hl.maxAnalyzedChars=1048576&hl.alternateField=content&hl.maxAlternateFieldLength=100&fl=id,id_bc,desc,id_uni,id_anexo,nome_anexo,dta_ger';

		//InfraDebug::getInstance()->gravar('URL:'.$urlBusca);
		//InfraDebug::getInstance()->gravar("PARÂMETROS: " . print_r($parametros, true));

		try{
		  $resultados = file_get_contents($urlBusca, false);
	  }catch(Exception $e){
			throw new InfraException('Erro realizando pesquisa.',$e, urldecode($urlBusca), false);
    }

		if ($resultados == ''){
		  throw new InfraException('Nenhum retorno encontrado no resultado da pesquisa.');
		}

		$xml = simplexml_load_string($resultados);
		
		$html = '';
		
		$arrRet = $xml->xpath('/response/result/@numFound');
		$itens = array_shift($arrRet);

		if ($itens == 0){
		  
			$html .= "<div class=\"pesquisaSemResultado\">";
      $html .= "Nenhum resultado encontrado.";
			$html .= "<br/>";
			$html .= "<br/>";
			$html .= "Sugestões:";
			$html .= "<ul>";
      $html .= "<li>Certifique-se de que todas as palavras estejam escritas corretamente.</li>";
      $html .= "<li>Tente palavras-chave ou critérios diferentes.</li>";
      $html .= "<li>Tente palavras-chave ou critérios mais genéricos.</li>";
			$html .= "</ul>";
			$html .= "</div>";
			
		}else{
		  		
  		$html = SeiSolrUtil::criarBarraEstatisticas($itens,$parametros->start,($parametros->start+$parametros->rows),false);
  		
  		$registros = $xml->xpath('/response/result/doc');

			$arrRegistros = array();
			$arrIdUnidade = array();

  		$numRegistros = count($registros);
			for ($i = 0; $i < $numRegistros; $i++) {

				$regResultado = $registros[$i];

        $dtaGeracao = InfraSolrUtil::obterTag($regResultado, 'dta_ger', 'date');
        $dtaGeracao = preg_replace("/(\\d{4})-(\\d{2})-(\\d{2})(.*)/", "$3/$2/$1", $dtaGeracao);

				$arrRegistros[$i] = array(
					'id' => InfraSolrUtil::obterTag($regResultado, 'id', 'str'),
					'id_bc' => InfraSolrUtil::obterTag($regResultado, 'id_bc', 'int'),
					'desc' => InfraSolrUtil::obterTag($regResultado, 'desc', 'str'),
					'id_uni' => InfraSolrUtil::obterTag($regResultado, 'id_uni', 'int'),
					'id_anexo' => InfraSolrUtil::obterTag($regResultado, 'id_anexo', 'int'),
					'nome_anexo' => InfraSolrUtil::obterTag($regResultado, 'nome_anexo', 'str'),
          'dta_ger' => $dtaGeracao
				);
				$arrIdUnidade[$arrRegistros[$i]["id_uni"]] = 0;
			}

      $arrObjUnidadeDTO = array();
      for ($i = 0; $i < $numRegistros; $i++) {
        $strChaveHistorico = $arrRegistros[$i]['id_uni'].'_'.$arrRegistros[$i]['dta_ger'];
        if (!isset($arrObjUnidadeDTO[$strChaveHistorico])) {
          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setNumIdUnidade($arrRegistros[$i]['id_uni']);
          $objUnidadeDTO->setDtaHistorico($arrRegistros[$i]['dta_ger']);
          $arrObjUnidadeDTO[$strChaveHistorico] = $objUnidadeDTO;
        }
      }

      $objHistoricoRN = new HistoricoRN();
      $objHistoricoRN->aplicar('Unidade', $arrObjUnidadeDTO, 'Historico', 'IdUnidade', 'Sigla', 'Descricao');


      $html .= "<table border=\"0\" class=\"pesquisaResultado\" width='100%'>";
      for ($i = 0; $i < $numRegistros; $i++) {

				$dados = $arrRegistros[$i];

  			$tituloCompleto = "<a onclick=\"infraLimparFormatarTrAcessada(this.parentNode.parentNode);\" href=\"" . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_visualizar&id_base_conhecimento='.$dados['id_bc']) . "\" target='_blank'>";
  			$tituloCompleto .= PaginaSEI::tratarHTML($dados['desc']);
  			$tituloCompleto .= "</a>";
  
  			if ($dados['id_anexo']!=null){
  				$tituloCompleto .= " ( <a onclick=\"infraLimparFormatarTrAcessada(this.parentNode.parentNode);\" href=\"" . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_download_anexo&id_anexo='.$dados['id_anexo']) . "\" target='_blank' class='linkAnexo'>";
  				$tituloCompleto .= PaginaSEI::tratarHTML($dados['nome_anexo']);
  				$tituloCompleto .= "</a> )";
  			}			

  			$temp = $xml->xpath("/response/lst[@name='highlighting']/lst[@name='".$dados['id']."']/arr[@name='content']/str");
  			
  			$snippet = '';
  			for($j=0;$j<count($temp);$j++){
  			  $snippetTemp = utf8_decode($temp[$j]);
  			  $snippetTemp = strtoupper(trim(strip_tags($snippetTemp))) == "NULL" ? null : $snippetTemp;
    		  $snippetTemp = preg_replace("/<br>/i", "<br />", $snippetTemp);
    		  $snippetTemp = preg_replace("/&lt;.*?&gt;/", "", $snippetTemp);
          $snippet .= trim($snippetTemp).'<b>&nbsp;&nbsp;...&nbsp;&nbsp;</b>';
  			}

				if (isset($arrObjUnidadeDTO[$dados['id_uni'].'_'.$dados['dta_ger']])){
  			  $objUnidadeDTO = $arrObjUnidadeDTO[$dados['id_uni'].'_'.$dados['dta_ger']];
					$strSiglaUnidade = PaginaSEI::tratarHTML($objUnidadeDTO->getStrSigla());
					$strDescricaoUnidade = PaginaSEI::tratarHTML($objUnidadeDTO->getStrDescricao());
				}else{
					$strSiglaUnidade = '[unidade não encontrada]';
					$strDescricaoUnidade = '[unidade não encontrada]';
				}

    		$titulo = preg_replace("/&lt;.*?&gt;/", "", $tituloCompleto);
    		
    		// CORRIGE A TAG BR NO SNIPSET
    		
    		$snippet = preg_replace("/<br>/i", "<br />", $snippet);
    		$snippet = preg_replace("/&lt;.*?&gt;/", "", $snippet);
    		

    		$html .= "<tr class=\"pesquisaTituloRegistro\">";
    		$html .= "<td class=\"pesquisaTituloEsquerda\">";
    		$html .= $titulo;
        $html .= "</td>";
        $html .= "<td class=\"pesquisaTituloDireita\">";
        $html .= "<a title=\"".$strDescricaoUnidade."\" class=\"linkUnidade\">".$strSiglaUnidade."</a>";
    		$html .= "</td>";
    		$html .= "</tr>";
  			$html .= "<tr><td colspan=\"2\" class=\"pesquisaSnippet\">" . $snippet . "</td></tr>";
  		}
      $html .= "</table>";

  		$html .= SeiSolrUtil::criarBarraNavegacao($itens, $parametros->start, $parametros->rows);
		}
		return $html;
	}
}
?>