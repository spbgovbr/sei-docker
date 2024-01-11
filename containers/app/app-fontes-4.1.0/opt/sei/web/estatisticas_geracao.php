<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/09/2010 - criado por jonatas_db
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  
	//PaginaSEI::getInstance()->setBolAutoRedimensionar(false);
  //////////////////////////////////////////////////////////////////////////////
  
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();

  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrComandos = array();  
  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';          	
  
  $strTitulo = 'Estatisticas Ouvidoria';
  
  
  switch($_GET['acao']){

  	case 'gerar_estatisticas_unidade':
    case 'gerar_estatisticas_ouvidoria':

    	if ($_GET['acao']=='gerar_estatisticas_unidade'){
    		$strTitulo = 'Estatísticas da Unidade';
    		$strAcaoDetalhar = 'estatisticas_detalhar_unidade';
    		$strEscolhaOrgao = 'display:none !important;';
    		$bolOuvidoria = false;
    	}else{
        $strTitulo = 'Estatísticas da Ouvidoria';
        $strAcaoDetalhar = 'estatisticas_detalhar_ouvidoria';
        $strEscolhaOrgao = '';
        $bolOuvidoria = true;
        
	      if (!isset($_POST['selOrgao'])){
	      	$numIdOrgaoEscolha = SessaoSEI::getInstance()->getNumIdOrgaoUsuario();
	      }
    	}

  		if (isset($_POST['txtPeriodoDe']) && isset($_POST['txtPeriodoA'])){
  			
	      $objEstatisticasDTO = new EstatisticasDTO();
	      
	    	$numIdOrgaoEscolha			= $_POST['selOrgao'];
	      if ($numIdOrgaoEscolha!=''){
			    $objEstatisticasDTO->setNumIdOrgaoUnidade($numIdOrgaoEscolha);
	      }

	      $dtaPeriodoDe 	= $_POST['txtPeriodoDe'];
	      $objEstatisticasDTO->setDtaInicio($dtaPeriodoDe);
	      
	      $dtaPeriodoA		= $_POST['txtPeriodoA'];
			  $objEstatisticasDTO->setDtaFim($dtaPeriodoA);
			  
			  if ($bolOuvidoria){
			    
            $objOuvidoriaRN = new OuvidoriaRN();

            $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
            $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
            $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
            $objTipoProcedimentoDTO->setStrSinOuvidoria('S');
            
            $objTipoProcedimentoRN = new TipoProcedimentoRN();
            $objEstatisticasDTO->setArrObjTipoProcedimentoDTO($objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO));
              
            
            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->retNumIdUnidade();
            $objUnidadeDTO->setStrSinOuvidoria('S');
            
            $objUnidadeRN = new UnidadeRN();
            $objEstatisticasDTO->setArrObjUnidadeDTO($objUnidadeRN->listarRN0127($objUnidadeDTO));
            
			  }else{
			  	 
			  	$arr = array();
			  	
			  	$objUnidadeDTO = new UnidadeDTO();
			  	$objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
			  	$arr[] = $objUnidadeDTO;
			  	
			  	$objEstatisticasDTO->setArrObjUnidadeDTO($arr);
			  }

			  $objEstatisticasDTORet = null;
			  try{
			    $objEstatisticasRN = new EstatisticasRN();
			    
			    if ($_GET['acao']=='gerar_estatisticas_unidade'){
			      $objEstatisticasDTORet	= $objEstatisticasRN->gerarUnidade($objEstatisticasDTO);
			    }else{
			      $objEstatisticasDTORet	= $objEstatisticasRN->gerarOuvidoria($objEstatisticasDTO);
			    }
			    
			  }catch(Exception $e){
			    PaginaSEI::getInstance()->processarExcecao($e);
			  }

			  if ($objEstatisticasDTORet != null){
			  
				  $arrObjEstatisticasTabelaGERADOS		= $objEstatisticasDTORet->getArrEstatisticasGERADOS();
					$arrObjEstatisticasTabelaTRAMITACAO	= $objEstatisticasDTORet->getArrEstatisticasTRAMITACAO();
				  $arrObjEstatisticasTabelaFECHADOS		= $objEstatisticasDTORet->getArrEstatisticasFECHADOS();
				  $arrObjEstatisticasTabelaABERTOS		= $objEstatisticasDTORet->getArrEstatisticasABERTOS();
				  $arrObjEstatisticasTabelaTEMPO			= $objEstatisticasDTORet->getArrEstatisticasTEMPO();
				  $arrObjEstatisticasTabelaDOCUMENTOSGERADOS	= $objEstatisticasDTORet->getArrEstatisticasDOCUMENTOSGERADOS();
				  $arrObjEstatisticasTabelaDOCUMENTOSRECEBIDOS	= $objEstatisticasDTORet->getArrEstatisticasDOCUMENTOSRECEBIDOS();
				  
				  
				  $objOrgaoDTO = new OrgaoDTO();
				  $objOrgaoDTO->retStrSigla();
				  
				  
          $arrCores = $objEstatisticasRN->getArrCores();
          $numCores = count($arrCores);
          $numCorAtual = 0;

				  $objOrgaoRN = new OrgaoRN();
				  $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);
				  foreach($arrObjOrgaoDTO as $objOrgaoDTO){
				    
				    $arrCoresOrgaos[$objOrgaoDTO->getStrSigla()] = $arrCores[$numCorAtual];

				    if (++$numCorAtual >= $numCores){
				      $numCorAtual = 0;
				    }
				  }
				  
	    		if ($bolOuvidoria){
	    		  
	    		  $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
            $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
	    		  $objTipoProcedimentoDTO->retStrNome();
	    		  $objTipoProcedimentoDTO->setStrSinOuvidoria('S');
	    		  
	    		  $objTipoProcedimentoRN = new TipoProcedimentoRN();
	    		  $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);
	    		  
	    		  $numCorAtual = 0;
	    		  foreach($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO){
	    		    
	    		    $arrCoresTipoProcedimento[$objTipoProcedimentoDTO->getStrNome()] 	= $arrCores[$numCorAtual];
	    		    
  	    		  if (++$numCorAtual >= $numCores){
  				      $numCorAtual = 0;
  				    }
	    		  }
	    		}else{
	    			$arrCoresTipoProcedimento = $arrCores[0];
	    		}
			  }  
  		}  
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


  if ($objEstatisticasDTORet != null){
  	
  	//Órgãos
  	$objOrgaoDTO = new OrgaoDTO();
  	$objOrgaoDTO->setBolExclusaoLogica(false);
  	$objOrgaoDTO->retNumIdOrgao();
  	$objOrgaoDTO->retStrSigla();
  	$objOrgaoDTO->retStrDescricao();
  	
  	$objOrgaoRN = new OrgaoRN();
  	$arrObjOrgaoDTO = InfraArray::indexarArrInfraDTO($objOrgaoRN->listarRN1353($objOrgaoDTO),'IdOrgao');
  	
  	//Unidades
  	$objUnidadeDTO = new UnidadeDTO();
  	$objUnidadeDTO->setBolExclusaoLogica(false);
  	$objUnidadeDTO->retNumIdUnidade();
  	$objUnidadeDTO->retStrSigla();
  	$objUnidadeDTO->retStrDescricao();
  	
  	$objUnidadeRN = new UnidadeRN();
  	$arrObjUnidadeDTO = InfraArray::indexarArrInfraDTO($objUnidadeRN->listarRN0127($objUnidadeDTO),'IdUnidade');
  	
  	
  	//Tipos de Procedimento
  	$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
  	$objTipoProcedimentoDTO->setBolExclusaoLogica(false);
  	$objTipoProcedimentoDTO->retNumIdTipoProcedimento();
  	$objTipoProcedimentoDTO->retStrNome();
  	
  	$objTipoProcedimentoRN = new TipoProcedimentoRN();
  	$arrObjTipoProcedimentoDTO = InfraArray::indexarArrInfraDTO($objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO),'IdTipoProcedimento');

  	//Tipos de Documento
  	$objSerieDTO = new SerieDTO();
  	$objSerieDTO->setBolExclusaoLogica(false);
  	$objSerieDTO->retNumIdSerie();
  	$objSerieDTO->retStrNome();
  	
  	$objSerieRN = new SerieRN();
  	$arrObjSerieDTO = InfraArray::indexarArrInfraDTO($objSerieRN->listarRN0646($objSerieDTO),'IdSerie');
  	
    $bolAcaoImprimir = true;
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirDiv(\'divTabelas\');" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Quantidade X Ano

    $strCssTr='';
    $contadorTabelaTotal = 0;
		$totalGeral = 0;	
    if ($bolOuvidoria){
    
	    foreach ($arrObjEstatisticasTabelaGERADOS as $keyOrgao => $arrUnidades){
	    	
	      if ($arrObjOrgaoDTO[$keyOrgao]!=null){
	      	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
	      	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
	      }else{
	      	$strSiglaOrgao = '';
	      	$strDescricaoOrgao = '';
	      }
	    	
	    	foreach ($arrUnidades as $keyUnidade => $arrTipos){

		      if ($arrObjUnidadeDTO[$keyUnidade]!=null){
		      	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
		      	$strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
		      }else{
		      	$strSiglaUnidade = '';
		      	$strDescricaoUnidade =  '';
		      }

	    		$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_GERADOS.'&id_unidade='.$keyUnidade);
		      
	    		$total = 0;
	    		foreach ($arrTipos as $keyTipo => $arrMeses){
						foreach ($arrMeses as $keyAno => $arrMeses2) {
							foreach ($arrMeses2 as $keyMes => $quantidade){
								$total += $quantidade;
							}
						}
	    		}	
	    			
		      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
		      $strResultadoTabelaGeradosTotal .= $strCssTr;
			    $strResultadoTabelaGeradosTotal .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
		      $strResultadoTabelaGeradosTotal .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($total).'</a></td>';
		      $strResultadoTabelaGeradosTotal .= '</tr>'."\n";
	
	        $arrayGraficoTOTAL[] = array($strSiglaOrgao, InfraUtil::formatarMilhares($total),$total, $strLink);

	        $totalGeral += $total;
	        
					$contadorTabelaGeradosTotal++;
	    	}
	    }
    
			$strResultadoGeradosTotal = '';
	    $strResultadoGeradosTotal .= '<table width="70%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS.'">'."\n";
	    $strResultadoGeradosTotal .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS.':</caption>';
	    $strResultadoGeradosTotal .= '<tr>';
	    $strResultadoGeradosTotal .= '<th class="infraTh" width="30%">Órgão</th>'."\n";
	    $strResultadoGeradosTotal .= '<th class="infraTh" width="">Quantidade</th>'."\n";
	    $strResultadoGeradosTotal .= '</tr>'."\n";
	    $strResultadoGeradosTotal .= $strResultadoTabelaGeradosTotal;
      $strResultadoGeradosTotal .= '<tr class="totalEstatisticas"><td align="right"><b>TOTAL:</b></td><td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_GERADOS).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($totalGeral).'</a></td></tr>';
	    $strResultadoGeradosTotal .= '</table>';
    }

    // Tipo X Mes
    $strCssTr='';
    $contadorTabelaGerados = 0; 
    $total = 0;
    $contadorMes = array();

		$mesInicial = substr($objEstatisticasDTO->getDtaInicio(),3,2);
		$anoInicial = substr($objEstatisticasDTO->getDtaInicio(),6,4);
		$mesFinal = substr($objEstatisticasDTO->getDtaFim(),3,2);
		$anoFinal = substr($objEstatisticasDTO->getDtaFim(),6,4);

		$numMeses = ($anoFinal*12 + $mesFinal - 1)-($anoInicial*12 + $mesInicial - 1) + 1;
		$numTamanhoColuna = floor(75/($numMeses+1));
		$numTamanhoTabela = floor(($numMeses+1)*100/13);
		if ($numTamanhoTabela>100) $numTamanhoTabela=100;

		if ($bolOuvidoria){
			if ($numTamanhoTabela < 70){
				$numTamanhoTabela = 70;
			}
		}else{
			if ($numTamanhoTabela < 55){
				$numTamanhoTabela = 55;
			}
		}
		
    foreach ($arrObjEstatisticasTabelaGERADOS as $keyOrgao => $arrUnidades){
    	
      if ($arrObjOrgaoDTO[$keyOrgao]!=null){
      	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
      	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
      }else{
      	$strSiglaOrgao = '';
      	$strDescricaoOrgao = '';
      }
      	
    	
    	foreach ($arrUnidades as $keyUnidade => $arrTipos){
    		
	      if ($arrObjUnidadeDTO[$keyUnidade]!=null){
	      	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
	      	$strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
	      }else{
	      	$strSiglaUnidade = '';
	      	$strDescricaoUnidade =  '';
	      }
	      	
	    	foreach ($arrTipos as $keyTipo => $arrMeses){

  	      if ($arrObjTipoProcedimentoDTO[$keyTipo]!=null){
  	      	$strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipo]->getStrNome();
  	      }else{
  	      	$strNomeTipoProcedimento = '';
  	      }
	    	  
		      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
		      $strResultadoTabelaGerados .= $strCssTr;
		
		      if ($bolOuvidoria){
			      $strResultadoTabelaGerados .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
			    }
			    
				  $strResultadoTabelaGerados .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'</td>';
	    		
				  $mes = $mesInicial;
				  $ano = $anoInicial;
				  $n = 0;
				    
				  $totalTipo = 0;
				  
				  while ($n < $numMeses){

				  	$strResultadoTabelaGerados .= '<td align="center" width="'.$numTamanhoColuna.'%">';
				  	
				  	if (isset($arrMeses[$ano][$mes])){
				  		
				  		$strResultadoTabelaGerados .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_GERADOS.'&id_unidade='.$keyUnidade.'&id_tipo_procedimento='.$keyTipo.'&ano='.$ano.'&mes='.substr($mes,0,2)).'\');" class="ancoraPadraoAzul">'.$arrMeses[$ano][$mes].'</a>';
				  		
				  		$total += $arrMeses[$ano][$mes];
				  		$totalTipo += $arrMeses[$ano][$mes];
				      $contadorMes[$ano][$mes] += $arrMeses[$ano][$mes];
				      
				  	}else{
				  		$strResultadoTabelaGerados .= '&nbsp;';
				  	}

				  	$strResultadoTabelaGerados .= '</td>';

						$mes++;
				  	if ($mes==13) {
							$mes=1;
							$ano++;
						}
	  	      $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
				  	
				  	$n++;
				  }

				  $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_GERADOS.'&id_unidade='.$keyUnidade.'&id_tipo_procedimento='.$keyTipo);
				  
				  $strResultadoTabelaGerados .= '<td align="center" width="'.$numTamanhoColuna.'%" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($totalTipo).'</a></td>';
				  
				  $strResultadoTabelaGerados .= '</tr>';
				  
				  $strTituloGraficoGerados = EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS;
				  $arrayGraficoGERADOSORGAO[$strSiglaUnidade.' / '.$strSiglaOrgao][] = array($strNomeTipoProcedimento, InfraUtil::formatarMilhares($totalTipo), $totalTipo, $strLink);

	        $contadorTabelaGerados++;
	    	}
    	}
    }	
		$strResultadoGerados = '';
    $strResultadoGerados .= '<table width="'.$numTamanhoTabela.'%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS.'">'."\n";
    $strResultadoGerados .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS.':</caption>';
    
    
	  $mes = $mesInicial;

	  $strResultadoGerados .= '<tr>';
	  
	  if ($bolOuvidoria){
	  	$strResultadoGerados .= '<th class="infraTh" rowspan="2" width="">Órgão</th>'."\n";
	  	$strResultadoGerados .= '<th class="infraTh" rowspan="2" width="">Tipo</th>'."\n";
	  }else{
	    $strResultadoGerados .= '<th class="infraTh" rowspan="2" width="20%">Tipo</th>'."\n";	
	  }
	  
	  $colSpanAnoInicial = 0;
    $colSpanAnoFinal = 0;

		if ($anoFinal-$anoInicial>0){
			$colSpanAnoInicial=13-$mesInicial;
			$colSpanAnoFinal=$mesFinal;
		} else {
			$colSpanAnoInicial=$mesFinal-$mesInicial+1;
		}
	  
	  if ($colSpanAnoInicial){
	  	$strResultadoGerados .= '<th class="infraTh" colspan="'.$colSpanAnoInicial.'">'.$anoInicial.'</th>';
	  }
	  for ($i=$anoInicial+1;$i<$anoFinal;$i++){
			$strResultadoGerados .= '<th class="infraTh" colspan="12">'.$i.'</th>';
		}
	  if ($colSpanAnoFinal){
	  	$strResultadoGerados .= '<th class="infraTh" colspan="'.($colSpanAnoFinal).'">'.$anoFinal.'</th>';
	  }
	  
	  $strResultadoGerados .= '<th class="infraTh" rowspan="2">&nbsp;</th>'."\n";
	  
	  $strResultadoGerados .= '</tr>';
	  
    $strResultadoGerados .= '<tr>';
	  
	  $n = 0;
	  while ($n < $numMeses){
	  	$strResultadoGerados .= '<th class="infraTh">'.InfraData::obterMesSiglaBR($mes).'</th>';
      $mes++;
			if ($mes==13) {
				$mes=1;
				$ano++;
			}
      $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);	  	
	  	$n++;
	  }
    
    
    $strResultadoGerados .= '</tr>'."\n";    
    $strResultadoGerados .= $strResultadoTabelaGerados;
    $strResultadoGerados .= '<tr>';
    $strResultadoGerados .= '<td align="right" '.($bolOuvidoria?'colspan="2"':'').' class="totalEstatisticas"><b>TOTAL:</b></td>';
    
    $mes = $mesInicial;
		$ano = $anoInicial;
	  $n = 0;
	  while ($n < $numMeses){
	  	$strResultadoGerados .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_GERADOS.'&ano='.$ano.'&mes='.$mes).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($contadorMes[$ano][$mes]).'</a></td>';
	  	$mes++;
			if ($mes==13) {
				$mes=1;
				$ano++;
			}
	  	$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
	  	$n++;
	  }
    
	  
	  $strResultadoGerados .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_GERADOS).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($total).'</a></td>';
    $strResultadoGerados .= '</tr>';
    $strResultadoGerados .= '</table>';

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // com tramitação no período

    $strResultadoTabelaTramitacao = '';
    $strCssTr='';
    $contadorTabelaTramitacao = 0;
    $total = 0;
    foreach ($arrObjEstatisticasTabelaTRAMITACAO as $keyOrgao => $arrUnidades){
    	
      if ($arrObjOrgaoDTO[$keyOrgao]!=null){
      	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
      	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
      }else{
      	$strSiglaOrgao = '';
      	$strDescricaoOrgao = '';
      }
      	
    	
    	foreach ($arrUnidades as $keyUnidade => $arrTipos){
    		
	      if ($arrObjUnidadeDTO[$keyUnidade]!=null){
	      	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
	      	$strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
	      }else{
	      	$strSiglaUnidade = '';
	      	$strDescricaoUnidade =  '';
	      }
	      	    		
    		foreach ($arrTipos as $keyTipo => $quantidade){
  	      
    		  if ($arrObjTipoProcedimentoDTO[$keyTipo]!=null){
  	      	$strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipo]->getStrNome();
  	      }else{
  	      	$strNomeTipoProcedimento = '';
  	      }
    		  

    		  $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasTramitacao().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_TRAMITACAO.'&id_unidade='.$keyUnidade.'&id_unidade='.$keyUnidade.'&id_tipo_procedimento='.$keyTipo);
		      
		      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
		      $strResultadoTabelaTramitacao .= $strCssTr;
		

		      if ($bolOuvidoria){
			      $strResultadoTabelaTramitacao .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
			    }
			    $strResultadoTabelaTramitacao .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'</td>';
		      $strResultadoTabelaTramitacao .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($quantidade).'</a></td>';
		      
		      
		      $strResultadoTabelaTramitacao .= '</tr>'."\n";
		      
		      $total += $quantidade;
		      
		      $strTituloGraficoTramitacao = EstatisticasRN::$TITULO_ESTATISTICAS_TRAMITACAO;
		      $arrayGraficoTRAMITACAO[$strSiglaUnidade.' / '.$strSiglaOrgao][] = array($strNomeTipoProcedimento, InfraUtil::formatarMilhares($quantidade), $quantidade, $strLink);
		      
		      $contadorTabelaTramitacao++;
    		}
    	}
    }	
    
		$strResultadoTramitacao = '';
    $strResultadoTramitacao .= '<table width="'.($bolOuvidoria?'70':'55').'%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_TRAMITACAO.'">'."\n";
    $strResultadoTramitacao .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_TRAMITACAO.':</caption>';
    $strResultadoTramitacao .= '<tr>';
    
    if ($bolOuvidoria){
      $strResultadoTramitacao .= '<th class="infraTh" width="20%">Órgão</th>'."\n";
    }
    
    $strResultadoTramitacao .= '<th class="infraTh" width="">Tipo</th>'."\n";
    $strResultadoTramitacao .= '<th class="infraTh" width="'.($bolOuvidoria?'20':'30').'%">Quantidade</th>'."\n";
    $strResultadoTramitacao .= '</tr>'."\n";    
    $strResultadoTramitacao .= $strResultadoTabelaTramitacao;
    $strResultadoTramitacao .= '<tr class="totalEstatisticas"><td '.($bolOuvidoria?'colspan="2"':'').' align="right"><b>TOTAL:</b></td><td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasTramitacao().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_TRAMITACAO).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($total).'</a></td></tr>';
    $strResultadoTramitacao .= '</table>';  
    

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Concluídas X Mês
    $strResultadoTabelaFechados = '';
    $strCssTr='';
    $contadorTabelaFechados = 0; 
    $total = 0;
    foreach ($arrObjEstatisticasTabelaFECHADOS as $keyOrgao => $arrUnidades){
    	
      if ($arrObjOrgaoDTO[$keyOrgao]!=null){
      	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
      	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
      }else{
      	$strSiglaOrgao = '';
      	$strDescricaoOrgao = '';
      }
      	
    	
    	foreach ($arrUnidades as $keyUnidade => $arrTipos){
    		
	      if ($arrObjUnidadeDTO[$keyUnidade]!=null){
	      	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
	      	$strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
	      }else{
	      	$strSiglaUnidade = '';
	      	$strDescricaoUnidade =  '';
	      }
	      	    		
	    	foreach ($arrTipos as $keyTipo => $quantidade){

	    	  if ($arrObjTipoProcedimentoDTO[$keyTipo]!=null){
  	      	$strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipo]->getStrNome();
  	      }else{
  	      	$strNomeTipoProcedimento = '';
  	      }

	    		$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasFechados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_FECHADOS.'&id_unidade='.$keyUnidade.'&id_tipo_procedimento='.$keyTipo);
		      
	    		$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
		      $strResultadoTabelaFechados .= $strCssTr;
	    		
		      if ($bolOuvidoria){
			      $strResultadoTabelaFechados .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
		      }
		      
		      $strResultadoTabelaFechados .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'</td>';
		      $strResultadoTabelaFechados .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($quantidade).'</a></td>';
		      
		      $strResultadoTabelaFechados .= '</tr>'."\n";
		      
		      $total += $quantidade;
		      
		      $strTituloGraficoFechados = EstatisticasRN::$TITULO_ESTATISTICAS_FECHADOS;
		      $arrayGraficoFECHADOS[$strSiglaUnidade.' / '.$strSiglaOrgao][] = array($strNomeTipoProcedimento, InfraUtil::formatarMilhares($quantidade),$quantidade, $strLink);
		      $contadorTabelaFechados++;
    		}
    	}
    }	
    
		$strResultadoFechados = '';
    $strResultadoFechados .= '<table width="'.($bolOuvidoria?'70':'55').'%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_FECHADOS.'">'."\n";
    $strResultadoFechados .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_FECHADOS.':</caption>';
    $strResultadoFechados .= '<tr>';
    
    if ($bolOuvidoria){
      $strResultadoFechados .= '<th class="infraTh" width="20%">Órgão</th>'."\n";
    }
    
    $strResultadoFechados .= '<th class="infraTh" width="">Tipo</th>'."\n";
    $strResultadoFechados .= '<th class="infraTh" width="'.($bolOuvidoria?'20':'30').'%">Quantidade</th>'."\n";
    $strResultadoFechados .= '</tr>'."\n";    
    $strResultadoFechados .= $strResultadoTabelaFechados;
    $strResultadoFechados .= '<tr class="totalEstatisticas"><td '.($bolOuvidoria?'colspan="2"':'').' align="right"><b>TOTAL:</b></td><td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasFechados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_FECHADOS).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($total).'</a></td></tr>';
    $strResultadoFechados .= '</table>';  
     

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Pendentes X Mês
    $strResultadoTabelaAbertos = '';
    $strCssTr='';
    $contadorTabelaAbertos = 0; 
    $total = 0;
    foreach ($arrObjEstatisticasTabelaABERTOS as $keyOrgao => $arrUnidades){

      if ($arrObjOrgaoDTO[$keyOrgao]!=null){
      	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
      	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
      }else{
      	$strSiglaOrgao = '';
      	$strDescricaoOrgao = '';
      }
      	
    	
    	foreach ($arrUnidades as $keyUnidade => $arrTipos){

	      if ($arrObjUnidadeDTO[$keyUnidade]!=null){
	      	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
	      	$strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
	      }else{
	      	$strSiglaUnidade = '';
	      	$strDescricaoUnidade =  '';
	      }
	      	    		
    		foreach ($arrTipos as $keyTipo => $quantidade) {
	    	  
    		  if ($arrObjTipoProcedimentoDTO[$keyTipo]!=null){
  	      	$strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipo]->getStrNome();
  	      }else{
  	      	$strNomeTipoProcedimento = '';
  	      }

    			$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasAbertos().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_ABERTOS.'&id_unidade='.$keyUnidade.'&id_tipo_procedimento='.$keyTipo);
		      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
		      $strResultadoTabelaAbertos .= $strCssTr;
    			
		      if ($bolOuvidoria){
		        $strResultadoTabelaAbertos .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
		      }
		      
	        $strResultadoTabelaAbertos .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'</td>';
		      $strResultadoTabelaAbertos .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($quantidade).'</a></td>';
		      
		      
		      $strResultadoTabelaAbertos .= '</tr>'."\n";
		      
		      $total += $quantidade;
		      
		      $strTituloGraficoAbertos = EstatisticasRN::$TITULO_ESTATISTICAS_ABERTOS;
		      $arrayGraficoABERTOS[$strSiglaUnidade.' / '.$strSiglaOrgao][] = array($strNomeTipoProcedimento, InfraUtil::formatarMilhares($quantidade),$quantidade, $strLink);
		      $contadorTabelaAbertos++;
    		}
    	}
    }	
		$strResultadoAbertos = '';
    $strResultadoAbertos .= '<table width="'.($bolOuvidoria?'70':'55').'%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_ABERTOS.'">'."\n";
    $strResultadoAbertos .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_ABERTOS.':</caption>';
    $strResultadoAbertos .= '<tr>';
    
    if ($bolOuvidoria){
      $strResultadoAbertos .= '<th class="infraTh" width="20%">Órgão</th>'."\n";
    }
    
    $strResultadoAbertos .= '<th class="infraTh" width="">Tipo</th>'."\n";
    $strResultadoAbertos .= '<th class="infraTh" width="'.($bolOuvidoria?'20':'30').'%">Quantidade</th>'."\n";
    $strResultadoAbertos .= '</tr>'."\n";    
    $strResultadoAbertos .= $strResultadoTabelaAbertos;
    $strResultadoAbertos .= '<tr class="totalEstatisticas"><td '.($bolOuvidoria?'colspan="2"':'').' align="right"><b>TOTAL:</b></td><td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasAbertos().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_ABERTOS).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($total).'</a></td></tr>';
    $strResultadoAbertos .= '</table>';  

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // TEMPO
    $strResultadoTabelaTempo = '';
    $strCssTr='';
    $contadorTabelaTempo = 0; 
    $total = 0;
    foreach ($arrObjEstatisticasTabelaTEMPO as $keyOrgao => $arrUnidades){

      if ($arrObjOrgaoDTO[$keyOrgao]!=null){
      	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
      	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
      }else{
      	$strSiglaOrgao = '';
      	$strDescricaoOrgao = '';
      }
      	

    	foreach ($arrUnidades as $keyUnidade => $arrTipos){

	      if ($arrObjUnidadeDTO[$keyUnidade]!=null){
	      	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
	      	$strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
	      }else{
	      	$strSiglaUnidade = '';
	      	$strDescricaoUnidade =  '';
	      }
	      	
    		
    		foreach ($arrTipos as $keyTipo => $quantidade) {
    		  
    		  if ($arrObjTipoProcedimentoDTO[$keyTipo]!=null){
  	      	$strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipo]->getStrNome();
  	      }else{
  	      	$strNomeTipoProcedimento = '';
  	      }

    			$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasTempo().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_TEMPO.'&id_unidade='.$keyUnidade.'&id_tipo_procedimento='.$keyTipo);
		      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
		      $strResultadoTabelaTempo .= $strCssTr;
    			
		      if ($bolOuvidoria){
			      $strResultadoTabelaTempo .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
		      }
		      
			    $strResultadoTabelaTempo .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'</td>';
		      $strResultadoTabelaTempo .= '<td align="right"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraData::formatarTimestamp($quantidade).'</a></td>';
		      
		      
		      $strResultadoTabelaTempo .= '</tr>'."\n";
		      
		      $total += $quantidade;
		      
		      $strTituloGraficoTempo = EstatisticasRN::$TITULO_ESTATISTICAS_TEMPO;
		      $arrayGraficoTEMPO[$strSiglaUnidade.' / '.$strSiglaOrgao][] = array($strNomeTipoProcedimento, InfraData::formatarTimestamp($quantidade), $quantidade, $strLink);
		      $contadorTabelaTempo++;
    		}
    	}
    }	
		$strResultadoTempo = '';
    $strResultadoTempo .= '<table width="'.($bolOuvidoria?'70':'55').'%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_TEMPO.'">'."\n";
    $strResultadoTempo .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_TEMPO.':</caption>';
    $strResultadoTempo .= '<tr>';
    
    if ($bolOuvidoria){
      $strResultadoTempo .= '<th class="infraTh" width="20%">Órgão</th>'."\n";
    }
    
    $strResultadoTempo .= '<th class="infraTh" width="">Tipo</th>'."\n";
    $strResultadoTempo .= '<th class="infraTh" width="'.($bolOuvidoria?'20':'30').'%">Tempo Médio</th>'."\n";
    $strResultadoTempo .= '</tr>'."\n";    
    $strResultadoTempo .= $strResultadoTabelaTempo;
    $strResultadoTempo .= '<tr class="totalEstatisticas"><td '.($bolOuvidoria?'colspan="2"':'').' align="right"><b>GERAL:</b></td><td align="right"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasTempo().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_TEMPO).'\');" class="ancoraPadraoAzul">'.InfraData::formatarTimestamp(bcdiv($total,($contadorTabelaTempo>0?$contadorTabelaTempo:1),0)).'</a></td></tr>';
    $strResultadoTempo .= '</table>';  
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // DOCUMENTOS GERADOS

    $strCssTr='';
    $contadorTabelaDocumentoGerado = 0; 
    $total = 0;
    
    if (!$bolOuvidoria){
    	
	    $contadorMes = array();

			foreach ($arrObjEstatisticasTabelaDOCUMENTOSGERADOS as $keyOrgao => $arrUnidades){
	    	
	      if ($arrObjOrgaoDTO[$keyOrgao]!=null){
	      	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
	      	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
	      }else{
	      	$strSiglaOrgao = '';
	      	$strDescricaoOrgao = '';
	      }
	      	
	    	
	    	foreach ($arrUnidades as $keyUnidade => $arrTipos){
	    		
		      if ($arrObjUnidadeDTO[$keyUnidade]!=null){
		      	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
		      	$strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
		      }else{
		      	$strSiglaUnidade = '';
		      	$strDescricaoUnidade =  '';
		      }
		      	
	    	  foreach ($arrTipos as $keyTipo => $arrMeses) {
	    	    
      		  if ($arrObjSerieDTO[$keyTipo]!=null){
    	      	$strNomeSerie = $arrObjSerieDTO[$keyTipo]->getStrNome();
    	      }else{
    	      	$strNomeSerie = '';
    	      }
	    	    
	
			      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
			      $strResultadoTabelaDocumentosGerados .= $strCssTr;
			
					  $strResultadoTabelaDocumentosGerados .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeSerie).'</td>';
		    		
					  $mes = $mesInicial;
						$ano=$anoInicial;
					    
					  $n = 0;
					    
					  $totalTipo = 0;
					  
					  while ($n < $numMeses){
	
					  	$strResultadoTabelaDocumentosGerados .= '<td align="center" width="'.$numTamanhoColuna.'%">';
					  	
					  	if (isset($arrMeses[$ano][$mes])){
					  		
					  		$strResultadoTabelaDocumentosGerados .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_GERADOS.'&id_unidade='.$keyUnidade.'&id_serie='.$keyTipo.'&ano='.$ano.'&mes='.substr($mes,0,2)).'\');" class="ancoraPadraoAzul">'.$arrMeses[$ano][$mes].'</a>';
					  		$total += $arrMeses[$ano][$mes];
					  		$totalTipo += $arrMeses[$ano][$mes];
					      $contadorMes[$ano][$mes] += $arrMeses[$ano][$mes];
					      
					  	}else{
					  		$strResultadoTabelaDocumentosGerados .= '&nbsp;';
					  	}
	
					  	$strResultadoTabelaDocumentosGerados .= '</td>';

							$mes++;
							if ($mes==13) {
								$mes=1;
								$ano++;
							}
		  	      $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
					  	
					  	$n++;
					  }
	
					  $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_GERADOS.'&id_unidade='.$keyUnidade.'&id_serie='.$keyTipo);
					  
					  $strResultadoTabelaDocumentosGerados .= '<td align="center" width="'.$numTamanhoColuna.'%" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($totalTipo).'</a></td>';
					  
					  $strResultadoTabelaDocumentosGerados .= '</tr>';
		        
			      $strTituloGraficoDocumento = EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_GERADOS;
			      $arrayGraficoDOCUMENTOGERADO[$strSiglaUnidade.' / '.$strSiglaOrgao][] = array($strNomeSerie, InfraUtil::formatarMilhares($totalTipo),$totalTipo, $strLink);
			      
			      $contadorTabelaDocumentoGerado++;
		    	}
		    	
	    	}
	    }	
			$strResultadoDocumentoGerado = '';
	    $strResultadoDocumentoGerado .= '<table width="'.$numTamanhoTabela.'%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_GERADOS.'">'."\n";
	    $strResultadoDocumentoGerado .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_GERADOS.':</caption>';
		  
		  $strResultadoDocumentoGerado .= '<tr>';
		  
	    $strResultadoDocumentoGerado .= '<th class="infraTh" rowspan="2" width="20%">Tipo</th>'."\n";	
		  

			$strResultadoDocumentoGerado .= '<th class="infraTh" colspan="'.$colSpanAnoInicial.'">'.$anoInicial.'</th>';

			for ($i=$anoInicial+1;$i<$anoFinal;$i++){
				$strResultadoDocumentoGerado .= '<th class="infraTh" colspan="12">'.$i.'</th>';
			}
			if ($colSpanAnoFinal){
				$strResultadoDocumentoGerado .= '<th class="infraTh" colspan="'.($colSpanAnoFinal).'">'.$anoFinal.'</th>';
			}
		  
		  $strResultadoDocumentoGerado .= '<th class="infraTh" rowspan="2">&nbsp;</th>'."\n";
		  
		  $strResultadoDocumentoGerado .= '</tr>';
		  
	    $strResultadoDocumentoGerado .= '<tr>';
			$mes = $mesInicial;
			$ano=$anoInicial;
		  $n = 0;
		  while ($n < $numMeses){
		  	$strResultadoDocumentoGerado .= '<th class="infraTh">'.InfraData::obterMesSiglaBR($mes).'</th>';
	      $mes++;
				if ($mes==13) {
					$mes=1;
					$ano++;
				}
	      $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);	  	
		  	$n++;
		  }
	    
	    
	    $strResultadoDocumentoGerado .= '</tr>'."\n";    
	    $strResultadoDocumentoGerado .= $strResultadoTabelaDocumentosGerados;
	    $strResultadoDocumentoGerado .= '<tr>';
	    $strResultadoDocumentoGerado .= '<td align="right" '.($bolOuvidoria?'colspan="2"':'').' class="totalEstatisticas"><b>TOTAL:</b></td>';
	    
	    $mes = $mesInicial;
			$ano=$anoInicial;
		  $n = 0;
		  while ($n < $numMeses){
		  	$strResultadoDocumentoGerado .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_GERADOS.'&ano='.$ano.'&mes='.$mes).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($contadorMes[$ano][$mes]).'</a></td>';
		  	$mes++;
				if ($mes==13) {
					$mes=1;
					$ano++;
				}
		  	$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
		  	$n++;
		  }
	    
		  
		  $strResultadoDocumentoGerado .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosGerados().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_GERADOS).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($total).'</a></td>';
	    $strResultadoDocumentoGerado .= '</tr>';
	    $strResultadoDocumentoGerado .= '</table>';
    }    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // DOCUMENTOS EXTERNOS

    $strCssTr='';
    $contadorTabelaDocumentoRecebido = 0;
    $total = 0;
    
    if (!$bolOuvidoria){
       
      $contadorMes = array();

      	
      foreach ($arrObjEstatisticasTabelaDOCUMENTOSRECEBIDOS as $keyOrgao => $arrUnidades){
    
        if ($arrObjOrgaoDTO[$keyOrgao]!=null){
          $strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
          $strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
        }else{
          $strSiglaOrgao = '';
          $strDescricaoOrgao = '';
        }
    
    
        foreach ($arrUnidades as $keyUnidade => $arrTipos){
           
          if ($arrObjUnidadeDTO[$keyUnidade]!=null){
            $strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
            $strDescricaoUnidade =  $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
          }else{
            $strSiglaUnidade = '';
            $strDescricaoUnidade =  '';
          }
           
          foreach ($arrTipos as $keyTipo => $arrMeses) {
    
            if ($arrObjSerieDTO[$keyTipo]!=null){
              $strNomeSerie = $arrObjSerieDTO[$keyTipo]->getStrNome();
            }else{
              $strNomeSerie = '';
            }
    
    
            $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
            $strResultadoTabelaDocumentosRecebidos .= $strCssTr;
            	
            $strResultadoTabelaDocumentosRecebidos .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeSerie).'</td>';
    
            $mes = $mesInicial;
						$ano= $anoInicial;
            	
            $n = 0;
            	
            $totalTipo = 0;
            	
            while ($n < $numMeses){
    
              $strResultadoTabelaDocumentosRecebidos .= '<td align="center" width="'.$numTamanhoColuna.'%">';
    
              if (isset($arrMeses[$ano][$mes])){
                	
                $strResultadoTabelaDocumentosRecebidos .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosRecebidos().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS.'&id_unidade='.$keyUnidade.'&id_serie='.$keyTipo.'&ano='.$ano.'&mes='.substr($mes,0,2)).'\');" class="ancoraPadraoAzul">'.$arrMeses[$ano][$mes].'</a>';
                $total += $arrMeses[$ano][$mes];
                $totalTipo += $arrMeses[$ano][$mes];
                $contadorMes[$ano][$mes] += $arrMeses[$ano][$mes];
                 
              }else{
                $strResultadoTabelaDocumentosRecebidos .= '&nbsp;';
              }
    
              $strResultadoTabelaDocumentosRecebidos .= '</td>';
    
              $mes++;
							if ($mes==13) {
								$mes=1;
								$ano++;
							}
              $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
    
              $n++;
            }
    
            $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosRecebidos().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS.'&id_unidade='.$keyUnidade.'&id_serie='.$keyTipo);
            	
            $strResultadoTabelaDocumentosRecebidos .= '<td align="center" width="'.$numTamanhoColuna.'%" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($totalTipo).'</a></td>';
            	
            $strResultadoTabelaDocumentosRecebidos .= '</tr>';
    
            $strTituloGraficoDocumento = EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS;
            $arrayGraficoDOCUMENTORECEBIDO[$strSiglaUnidade.' / '.$strSiglaOrgao][] = array($strNomeSerie, InfraUtil::formatarMilhares($totalTipo),$totalTipo, $strLink);
             
            $contadorTabelaDocumentoRecebido++;
          }
           
        }
      }
      $strResultadoDocumentoRecebido = '';
      $strResultadoDocumentoRecebido .= '<table width="'.$numTamanhoTabela.'%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS.'">'."\n";
      $strResultadoDocumentoRecebido .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS.':</caption>';
    
      $strResultadoDocumentoRecebido .= '<tr>';
    
      $strResultadoDocumentoRecebido .= '<th class="infraTh" rowspan="2" width="20%">Tipo</th>'."\n";
    


			$strResultadoDocumentoRecebido .= '<th class="infraTh" colspan="'.$colSpanAnoInicial.'">'.$anoInicial.'</th>';
			for ($i=$anoInicial+1;$i<$anoFinal;$i++){
				$strResultadoDocumentoRecebido .= '<th class="infraTh" colspan="12">'.$i.'</th>';
			}
			if ($colSpanAnoFinal){
				$strResultadoDocumentoRecebido .= '<th class="infraTh" colspan="'.($colSpanAnoFinal).'">'.$anoFinal.'</th>';
			}
    
      $strResultadoDocumentoRecebido .= '<th class="infraTh" rowspan="2">&nbsp;</th>'."\n";
    
      $strResultadoDocumentoRecebido .= '</tr>';
    
      $strResultadoDocumentoRecebido .= '<tr>';
			$mes = $mesInicial;
			$ano=$anoInicial;
      $n = 0;
      while ($n < $numMeses){
        $strResultadoDocumentoRecebido .= '<th class="infraTh">'.InfraData::obterMesSiglaBR($mes).'</th>';
        $mes++;
				if ($mes==13) {
					$mes=1;
					$ano++;
				}
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $n++;
      }
       
       
      $strResultadoDocumentoRecebido .= '</tr>'."\n";
      $strResultadoDocumentoRecebido .= $strResultadoTabelaDocumentosRecebidos;
      $strResultadoDocumentoRecebido .= '<tr>';
      $strResultadoDocumentoRecebido .= '<td align="right" '.($bolOuvidoria?'colspan="2"':'').' class="totalEstatisticas"><b>TOTAL:</b></td>';
       
      $mes = $mesInicial;
			$ano = $anoInicial;
      $n = 0;
      while ($n < $numMeses){
        $strResultadoDocumentoRecebido .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosRecebidos().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS.'&ano='.$ano.'&mes='.$mes).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($contadorMes[$ano][$mes]).'</a></td>';
        $mes++;
				if ($mes==13) {
					$mes=1;
					$ano++;
				}
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $n++;
      }
       
    
      $strResultadoDocumentoRecebido .= '<td align="center" class="totalEstatisticas"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDetalhar.'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDocumentosRecebidos().'&tabela_estatisticas='.EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS).'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($total).'</a></td>';
      $strResultadoDocumentoRecebido .= '</tr>';
      $strResultadoDocumentoRecebido .= '</table>';
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $numGrafico=0;
    $objEstatisticasRN = new EstatisticasRN();

    $strResultadoGraficoTOTAL = '';
    if ($contadorTabelaGeradosTotal > 0){
    	$strResultadoGraficoTOTAL .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      $strResultadoGraficoTOTAL .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS,null,$arrayGraficoTOTAL, 150, $arrCoresOrgaos);
    	$strResultadoGraficoTOTAL .= '</div>';
    } 

    
    if ($contadorTabelaGerados > 0){
    	$strResultadoGraficoGERADOS = '';
	    foreach ($arrayGraficoGERADOSORGAO as $chave => $arr) {
	    	$strResultadoGraficoGERADOS .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
	    	$strResultadoGraficoGERADOS .=$objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS.' ('.$chave.')',null,$arr, 150, $arrCoresTipoProcedimento);
	    	$strResultadoGraficoGERADOS .='</div><br />';
	    }
    }
    
		if ($contadorTabelaTramitacao > 0){
			$strResultadoGraficoTRAMITACAO = '';
			foreach ($arrayGraficoTRAMITACAO as $chave => $arr) {
			  $strResultadoGraficoTRAMITACAO .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    	  
    	  $strResultadoGraficoTRAMITACAO .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_TRAMITACAO.' ('.$chave.')',null,$arr, 150, $arrCoresTipoProcedimento);
    	  $strResultadoGraficoTRAMITACAO .= '</div><br />';
			}
		}
		
		if ($contadorTabelaFechados > 0){
			$strResultadoGraficoFECHADOS = '';	
			foreach ($arrayGraficoFECHADOS as $chave => $arr) {
			  $strResultadoGraficoFECHADOS .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
			  $strResultadoGraficoFECHADOS .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_FECHADOS.' ('.$chave.')',null,$arr, 150, $arrCoresTipoProcedimento);
			  $strResultadoGraficoFECHADOS .= '</div><br />';
			}
		}

		
		if ($contadorTabelaAbertos > 0){
			$strResultadoGraficoABERTOS = ''; 
			foreach ($arrayGraficoABERTOS as $chave => $arr) {
    	  $strResultadoGraficoABERTOS .='<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    	  $strResultadoGraficoABERTOS .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_ABERTOS.' ('.$chave.')',null,$arr, 150, $arrCoresTipoProcedimento);
    	  $strResultadoGraficoABERTOS .= '</div><br />';
			}
		}
		
		if ($contadorTabelaTempo > 0){
			$strResultadoGraficoTEMPO = ''; 
			foreach ($arrayGraficoTEMPO as $chave => $arr) {
    	  $strResultadoGraficoTEMPO .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    	  $strResultadoGraficoTEMPO .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_TEMPO.' ('.$chave.')',null,$arr, 150, $arrCoresTipoProcedimento);
    	  $strResultadoGraficoTEMPO .= '</div><br />';
			}
		}

  	if ($contadorTabelaDocumentoGerado > 0){
  		$strResultadoGraficoDOCUMENTOGERADO = '';
			foreach ($arrayGraficoDOCUMENTOGERADO as $chave => $arr) {
    	  $strResultadoGraficoDOCUMENTOGERADO .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
    	  $strResultadoGraficoDOCUMENTOGERADO .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_GERADOS.' ('.$chave.')',null,$arr, 150, $arrCoresTipoProcedimento);
    	  $strResultadoGraficoDOCUMENTOGERADO .= '</div><br />';
			}
		}
		
		if ($contadorTabelaDocumentoRecebido > 0){
		  $strResultadoGraficoDOCUMENTORECEBIDO = '';
		  foreach ($arrayGraficoDOCUMENTORECEBIDO as $chave => $arr) {
		    $strResultadoGraficoDOCUMENTORECEBIDO .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
		    $strResultadoGraficoDOCUMENTORECEBIDO .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS.' ('.$chave.')',null,$arr, 150, $arrCoresTipoProcedimento);
		    $strResultadoGraficoDOCUMENTORECEBIDO .= '</div><br />';
		  }
		}
  }
  
  $strItensSelOrgaos = OrgaoINT::montarSelectSiglaOuvidoria('','Todos',$numIdOrgaoEscolha);

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
.divAreaGrafico DIV { margin:0px; }

#frmEstatisticas{max-width: 1200px;}

#divOrgao	{<?=$strEscolhaOrgao?>}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
?>

<script type="text/javascript" src="/infra_js/raphaeljs/raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.bar-min.js"></script>
<? 
PaginaSEI::getInstance()->abrirJavaScript();
?>
function labelBarChart(r, bc, labels, attrs) {
    // Label a bar chart bc that is part of a Raphael object r
    // Labels is an array of strings. Attrs is a dictionary
    // that provides attributes such as fill (text color)
    // and font (text font, font-size, font-weight, etc) for the
    // label text.

    for (var i = 0; i< bc.bars[0].length; i++) {
        var bar = bc.bars[0][i];
        var gutter_y = bar.w * 0.4;
        var label_x = bar.x
        var label_y = bar.y  - gutter_y;
        var label_text = bar.value;
        var label_attr = { fill:  "#2f69bf", font: "11px sans-serif" };

        r.text(label_x, label_y, label_text).attr(label_attr);
    }

}

function inicializar(){

  if ('<?=$_GET['acao_origem']?>'!='gerar_estatisticas_unidade' && '<?=$_GET['acao_origem']?>'!='gerar_estatisticas_ouvidoria'){
    infraOcultarMenuSistemaEsquema();
  }

  infraAdicionarEvento(window,'resize',seiRedimensionarGraficos);
  infraProcessarResize();
  infraEfeitoTabelas();
  seiRedimensionarGraficos();

  document.getElementById('sbmPesquisar').focus();
}

function abrirDetalhe(link){
 infraAbrirJanelaModal(link,750,550);
}

function validarFormulario(){
	if(infraTrim(document.getElementById('txtPeriodoDe').value) == "" || infraTrim(document.getElementById('txtPeriodoA').value)=="") {
		
		alert("Informe o período de datas.");
	  
	  if(infraTrim(document.getElementById('txtPeriodoDe').value) == ""){
	    document.getElementById('txtPeriodoDe').focus();
	  }else{
	    document.getElementById('txtPeriodoA').focus();
	  }
		
		return false;
	}
	
	infraExibirAviso();
	return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEstatisticas" onsubmit="return validarFormulario();" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>

  <div id="divOrgao" class="infraAreaDados d-flex flex-column flex-md-row mb-1">
    <div class="col-12 col-md-1 mx-0 px-0 pt-1">
      <label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelOpcional">Órgão:</label>
    </div>
    <div class="col-7 col-md-3 pl-0 pl-md-1 pt-1 media">

      <select id="selOrgao" name="selOrgao" class="w-100 infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelOrgaos?>
      </select>
    </div>
  </div>


  <div id="divData" class="infraAreaDados d-flex flex-column flex-md-row mb-1">
    <div class="col-12 col-md-1 mx-0 px-0 pt-1">
      <label id="lblPeriodoDe" for="txtPeriodoDe" class="infraLabelObrigatorio">Período:</label>
    </div>
    <div class="d-flex flex-column flex-md-row col-10 col-md-8 col-xl-6 pl-0 pl-md-1 media">
      <div class="col-12 col-md-8 media pl-0 pt-1">
        <div class="col-6 pl-0 media">
          <input type="text" id="txtPeriodoDe" name="txtPeriodoDe" onkeypress="return infraMascaraData(this, event)" class="infraText w-75" value="<?=PaginaSEI::tratarHTML($dtaPeriodoDe);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <img id="imgCalPeriodoD" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtPeriodoDe',this);" alt="Selecionar Data Inicial" title="Selecionar Data Inicial" class="infraImg mx-1" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblPeriodoA" for="txtPeriodoA" accesskey="" class="infraLabelOpcional mx-0 pt-1 pl-md-2">e</label>
        </div>
        <div class="col-6 pl-0 pl-md-2 media">
          <input type="text" id="txtPeriodoA" name="txtPeriodoA" onkeypress="return infraMascaraData(this, event)" class="infraText w-75" value="<?=PaginaSEI::tratarHTML($dtaPeriodoA);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <img id="imgCalPeriodoA" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtPeriodoA',this);" alt="Selecionar Data Final" title="Selecionar Data Final" class="infraImg mx-1" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>
      </div>
    </div>
  </div>



  <div id="divTabelas">
  <?

  echo '<div id="divSeparador" style="float:left;padding:1em"></div>';
  
  if ($contadorTabelaGeradosTotal > 0) {
  	echo '<br /><br />';
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoGeradosTotal,$contadorTabelaGeradosTotal);
	  EstatisticasINT::montarGrafico('GeradosTotal',$strResultadoGraficoTOTAL);
  }
    
  if ($contadorTabelaGerados > 0) {
  	echo '<br /><br />';
		echo '<div style="display:block !important;clear:both;overflow-x: scroll;overflow-y: hidden">'."\n";
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoGerados,$contadorTabelaGerados);
		echo '</div>';
		EstatisticasINT::montarGrafico('Gerados',$strResultadoGraficoGERADOS);
  }

  
  if ($contadorTabelaTramitacao > 0) {
  	echo '<br /><br />';
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoTramitacao,$contadorTabelaTramitacao);
		EstatisticasINT::montarGrafico('Tramitacao',$strResultadoGraficoTRAMITACAO);
  }
  
  if ($contadorTabelaFechados > 0) {
  	echo '<br /><br />';

		PaginaSEI::getInstance()->montarAreaTabela($strResultadoFechados,$contadorTabelaFechados);
		EstatisticasINT::montarGrafico('Fechados',$strResultadoGraficoFECHADOS);
  }

  if ($contadorTabelaAbertos > 0) {
  	echo '<br /><br />';
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoAbertos,$contadorTabelaAbertos);
		EstatisticasINT::montarGrafico('Abertos',$strResultadoGraficoABERTOS);
  }
  
  if ($contadorTabelaTempo > 0) {
  	echo '<br /><br />';
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoTempo,$contadorTabelaTempo);
		EstatisticasINT::montarGrafico('Tempo',$strResultadoGraficoTEMPO);
  }

  if ($contadorTabelaDocumentoGerado > 0) {
  	echo '<br /><br />';
		echo '<div style="display:block !important;clear:both;overflow-x: scroll;overflow-y: hidden">'."\n";
		PaginaSEI::getInstance()->montarAreaTabela($strResultadoDocumentoGerado,$contadorTabelaDocumentoGerado);
		echo '</div>';
		EstatisticasINT::montarGrafico('Documento',$strResultadoGraficoDOCUMENTOGERADO);
  }
  
  if ($contadorTabelaDocumentoRecebido > 0) {
    echo '<br /><br />';
		echo '<div style="display:block !important;clear:both;overflow-x: scroll;overflow-y: hidden">'."\n";
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoDocumentoRecebido,$contadorTabelaDocumentoRecebido);
		echo '</div>';
    EstatisticasINT::montarGrafico('Documento',$strResultadoGraficoDOCUMENTORECEBIDO);
  }
  
  
  ?>
  </div>
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>