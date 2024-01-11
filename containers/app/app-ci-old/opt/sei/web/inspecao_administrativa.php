<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/06/2012 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgao','txtUnidade','selTipoProcedimento','selSerie'));
  
  switch($_GET['acao']){

    case 'inspecao_administrativa_gerar':
      
      $strTitulo = 'Inspeção Administrativa';
      
      $bolInspecaoGeral = SessaoSEI::getInstance()->verificarPermissao('inspecao_administrativa_geral');
      $bolInspecaoOrgao = false;
      if (!$bolInspecaoGeral){
        $bolInspecaoOrgao = SessaoSEI::getInstance()->verificarPermissao('inspecao_administrativa_orgao');
        if (!$bolInspecaoOrgao){
          throw new InfraException('Usuário sem permissão para realizar Inspeção Administrativa.');
        }
      }
      
      /*
      $bolInspecaoGeral = true;
      $bolInspecaoOrgao = false;
      */
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $numGrafico=0;
  $objEstatisticasRN = new EstatisticasRN();
  
  $arrComandos = array();  
  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnLimpar" onclick="limpar();" value="Limpar" class="infraButton"><span class="infraTeclaAtalho">L</span>impar</button>';          	
  
  $objEstatisticasInspecaoDTO = new EstatisticasInspecaoDTO();
  
  if ($bolInspecaoGeral){
    $objEstatisticasInspecaoDTO->setNumIdOrgao($_POST['selOrgao']);
  }else if ($bolInspecaoOrgao){
    $objEstatisticasInspecaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
  }
  
  $objEstatisticasInspecaoDTO->setStrStaTipo($_POST['selTipo']);
  
  $strIdUnidade = trim($_POST['hdnIdUnidade']);
  $strNomeUnidade = $_POST['txtUnidade'];
  $objEstatisticasInspecaoDTO->setNumIdUnidade($strIdUnidade);

  $objEstatisticasInspecaoDTO->setNumIdTipoProcedimento($_POST['selTipoProcedimento']);
  $objEstatisticasInspecaoDTO->setNumIdSerie($_POST['selSerie']);
  
  $dtaPeriodoDe 	= $_POST['txtPeriodoDe'];
  $objEstatisticasInspecaoDTO->setDtaInicio($_POST['txtPeriodoDe']);
  
  $dtaPeriodoA		= $_POST['txtPeriodoA'];
  $objEstatisticasInspecaoDTO->setDtaFim($_POST['txtPeriodoA']);
  
  if ($objEstatisticasInspecaoDTO->getStrStaTipo()!=EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO){
    
    if (isset($_POST['sbmPesquisar']) || $_GET['acao']==$_GET['acao_origem']){
      try {
        
        PaginaSEI::getInstance()->prepararOrdenacao($objEstatisticasInspecaoDTO, 'SiglaOrgao', InfraDTO::$TIPO_ORDENACAO_ASC);
        
        $objEstatisticasRN = new EstatisticasRN();
        $objEstatisticasInspecaoDTORet = $objEstatisticasRN->gerarInspecaoAdministrativa($objEstatisticasInspecaoDTO);
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
    }
    
  }else{
    
    PaginaSEI::getInstance()->prepararOrdenacao($objEstatisticasInspecaoDTO, 'Abertura', InfraDTO::$TIPO_ORDENACAO_DESC);
    
    PaginaSEI::getInstance()->prepararPaginacao($objEstatisticasInspecaoDTO);
    
    $objEstatisticasRN = new EstatisticasRN();
    $objEstatisticasInspecaoDTORet = $objEstatisticasRN->gerarInspecaoAdministrativa($objEstatisticasInspecaoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objEstatisticasInspecaoDTO);
  }  
  
  if ($objEstatisticasInspecaoDTORet!=null){

    $arrTemp = array();

  	//Órgãos
  	$objOrgaoDTO = new OrgaoDTO();
  	$objOrgaoDTO->setBolExclusaoLogica(false);
  	$objOrgaoDTO->retNumIdOrgao();
  	$objOrgaoDTO->retStrSigla();
  	$objOrgaoDTO->retStrDescricao();
  	
  	$objOrgaoRN = new OrgaoRN();
  	$arrObjOrgaoDTO = InfraArray::indexarArrInfraDTO($objOrgaoRN->listarRN1353($objOrgaoDTO),'IdOrgao');
    
    $arrCores = $objEstatisticasRN->getArrCores();
    $numCores = count($arrCores);
    $numCorAtual = 0;

    $arrCoresOrgaos = array();
    
	  foreach($arrObjOrgaoDTO as $objOrgaoDTO){
	    
	    $arrCoresOrgaos[$objOrgaoDTO->getStrSigla()] = $arrCores[$numCorAtual];

	    if (++$numCorAtual >= $numCores){
	      $numCorAtual = 0;
	    }
	  }
        
  	//Unidades
  	$objUnidadeDTO = new UnidadeDTO();
  	$objUnidadeDTO->setBolExclusaoLogica(false);
  	$objUnidadeDTO->retNumIdUnidade();
  	$objUnidadeDTO->retStrSigla();
  	$objUnidadeDTO->retStrDescricao();
  	$objUnidadeDTO->retStrSiglaOrgao();
  	$objUnidadeDTO->retStrDescricaoOrgao();
  	//$objUnidadeDTO->retStrNomeCidade();
  	
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
  	
  	//GERADOS ORGAOS////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $strCssTr='';
    $totalOrgaosGerados = 0;
		$totalGeral = 0;	
    $strTabelaOrgaosGerados = '';
    $arrGraficoOrgaosGerados = array();
    
    if ($objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_GERADOS){
      
      foreach ($objEstatisticasInspecaoDTORet->getArrOrgaosProcessosGerados() as $keyOrgao => $numRegistros){
      	
        if ($arrObjOrgaoDTO[$keyOrgao]!=null){
        	$numIdOrgao = $arrObjOrgaoDTO[$keyOrgao]->getNumIdOrgao();
        	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
        	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
        }else{
        	$numIdOrgao = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }
        	      	
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabelaOrgaosGerados .= $strCssTr;
  	    $strTabelaOrgaosGerados .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
        //$strTabelaOrgaosGerados .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="linkFuncionalidade">'.$numRegistros.'</a></td>';
        $strTabelaOrgaosGerados .= '<td align="center">'.InfraUtil::formatarMilhares($numRegistros).'</td>';
        $strTabelaOrgaosGerados .= '</tr>'."\n";
  
        $arrGraficoOrgaosGerados[$strSiglaOrgao] = array($strSiglaOrgao.':'.InfraUtil::formatarMilhares($numRegistros),$numRegistros);
  
        $totalGeral += $numRegistros;
        
  			$totalOrgaosGerados++;
      }
    
      $strTabelaOrgaosGeradosCompleta .= '<table width="50%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_ORGAOS_GERADOS.'">'."\n";
      $strTabelaOrgaosGeradosCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_ORGAOS_GERADOS.':</caption>';
      $strTabelaOrgaosGeradosCompleta .= '<tr>';
      $strTabelaOrgaosGeradosCompleta .= '<th class="infraTh" width="50%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaOrgaosGeradosCompleta .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Quantidade','Quantidade',$arrTemp).'</th>'."\n";
      $strTabelaOrgaosGeradosCompleta .= '</tr>'."\n";
      $strTabelaOrgaosGeradosCompleta .= $strTabelaOrgaosGerados;
      
      if ($totalOrgaosGerados > 1){
        $strTabelaOrgaosGeradosCompleta .= '<tr class="totalEstatisticas"><td align="right"><b>TOTAL:</b></td><td align="center">'.InfraUtil::formatarMilhares($totalGeral).'</td></tr>';
      }
      $strTabelaOrgaosGeradosCompleta .= '</table>';
  

      $strResultadoGraficoOrgaosGerados = '';
      if ($totalOrgaosGerados > 1){
      	$strResultadoGraficoOrgaosGerados .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	$strResultadoGraficoOrgaosGerados .= $objEstatisticasRN->gerarGraficoPizza($numGrafico,EstatisticasRN::$TITULO_INSPECAO_ORGAOS_GERADOS,'OrgaosGerados',$arrGraficoOrgaosGerados,null,300,450,$arrCoresOrgaos);
      	$strResultadoGraficoOrgaosGerados .= '</div>';
      } 
    }
        
    //TRAMITACAO ORGAOS////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $strCssTr='';
    $totalOrgaosTramitacao = 0;
		$totalGeral = 0;	
    $strTabelaOrgaosTramitacao = '';
    $arrGraficoOrgaosTramitacao = array();
    if ($objEstatisticasInspecaoDTO->getNumIdUnidade()==null && $objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_TRAMITACAO){
      foreach ($objEstatisticasInspecaoDTORet->getArrOrgaosTramitacao() as $keyOrgao => $numRegistros){
      	
        if ($arrObjOrgaoDTO[$keyOrgao]!=null){
        	$numIdOrgao = $arrObjOrgaoDTO[$keyOrgao]->getNumIdOrgao();
        	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
        	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
        }else{
        	$numIdOrgao = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }
      	
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabelaOrgaosTramitacao .= $strCssTr;
  	    $strTabelaOrgaosTramitacao .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
        //$strTabelaOrgaosTramitacao .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="linkFuncionalidade">'.$numRegistros.'</a></td>';
        $strTabelaOrgaosTramitacao .= '<td align="center">'.InfraUtil::formatarMilhares($numRegistros).'</td>';
        $strTabelaOrgaosTramitacao .= '</tr>'."\n";
  
        $arrGraficoOrgaosTramitacao[$strSiglaOrgao] = array($strSiglaOrgao.':'.InfraUtil::formatarMilhares($numRegistros),$numRegistros);
  
        $totalGeral =$objEstatisticasInspecaoDTORet->getNumTotalTramitacao();
        
  			$totalOrgaosTramitacao++;
      }
    
  		$strTabelaOrgaosTramitacaoCompleta = '';
      $strTabelaOrgaosTramitacaoCompleta .= '<table width="50%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_ORGAOS_TRAMITACAO.'">'."\n";
      $strTabelaOrgaosTramitacaoCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_ORGAOS_TRAMITACAO.':</caption>';
      $strTabelaOrgaosTramitacaoCompleta .= '<tr>';
      $strTabelaOrgaosTramitacaoCompleta .= '<th class="infraTh" width="50%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaOrgaosTramitacaoCompleta .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Quantidade','Quantidade',$arrTemp).'</th>'."\n";
      $strTabelaOrgaosTramitacaoCompleta .= '</tr>'."\n";
      $strTabelaOrgaosTramitacaoCompleta .= $strTabelaOrgaosTramitacao;
      
      if ($totalOrgaosTramitacao > 1){
        $strTabelaOrgaosTramitacaoCompleta .= '<tr class="totalEstatisticas"><td align="right"><b>TOTAL:</b></td><td align="center">'.InfraUtil::formatarMilhares($totalGeral).'</td></tr>';
      }
      $strTabelaOrgaosTramitacaoCompleta .= '</table>';
  
      $strResultadoGraficoOrgaosTramitacao = '';
      if ($totalOrgaosTramitacao > 1){
      	$strResultadoGraficoOrgaosTramitacao .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	$strResultadoGraficoOrgaosTramitacao .= $objEstatisticasRN->gerarGraficoPizza($numGrafico,EstatisticasRN::$TITULO_INSPECAO_ORGAOS_TRAMITACAO ,'OrgaosTramitacao',$arrGraficoOrgaosTramitacao,null,300,450,$arrCoresOrgaos,$objEstatisticasInspecaoDTORet->getNumTotalTramitacao());
      	$strResultadoGraficoOrgaosTramitacao .= '</div>';
      } 
    }    

    
    //GERADOS UNIDADES //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $strCssTr='';
    $totalUnidadesGerados = 0;
		$totalGeral = 0;	
    $strTabelaUnidadesGerados = '';
    
    if ($objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_GERADOS){
      foreach ($objEstatisticasInspecaoDTORet->getArrUnidadesProcessosGerados() as $keyUnidade => $numRegistros){
      	
        if ($arrObjUnidadeDTO[$keyUnidade]!=null){
        	$numIdUnidade = $arrObjUnidadeDTO[$keyUnidade]->getNumIdUnidade();
        	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
        	$strDescricaoUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
        	$strSiglaOrgao = $arrObjUnidadeDTO[$keyUnidade]->getStrSiglaOrgao();
        	$strDescricaoOrgao = $arrObjUnidadeDTO[$keyUnidade]->getStrDescricaoOrgao();
        	//$strNomeCidade = $arrObjUnidadeDTO[$keyUnidade]->getStrNomeCidade();
        }else{
         	$numIdUnidade = '';
        	$strSiglaUnidade = '';
        	$strDescricaoUnidade = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }
        

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabelaUnidadesGerados .= $strCssTr;
  	    $strTabelaUnidadesGerados .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
  	    $strTabelaUnidadesGerados .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoUnidade).'" title="'.PaginaSEI::tratarHTML($strDescricaoUnidade).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaUnidade).'</a></td>';
        //$strTabelaUnidadesGerados .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="linkFuncionalidade">'.$numRegistros.'</a></td>';
        $strTabelaUnidadesGerados .= '<td align="center">'.InfraUtil::formatarMilhares($numRegistros).'</td>';
        $strTabelaUnidadesGerados .= '</tr>'."\n";
  
        $arrayGraficoGeradosUnidade[] = array($strSiglaUnidade.' / '.$strSiglaOrgao, InfraUtil::formatarMilhares($numRegistros),$numRegistros);
  
        $totalGeral += $numRegistros;
        
  			$totalUnidadesGerados++;
      }
    
  		$strTabelaUnidadesGeradosCompleta = '';
      $strTabelaUnidadesGeradosCompleta .= '<table width="70%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_UNIDADES_GERADOS.'">'."\n";
      $strTabelaUnidadesGeradosCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_UNIDADES_GERADOS.':</caption>';
      $strTabelaUnidadesGeradosCompleta .= '<tr>';
      $strTabelaUnidadesGeradosCompleta .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaUnidadesGeradosCompleta .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Unidade','SiglaUnidade',$arrTemp).'</th>'."\n";
      $strTabelaUnidadesGeradosCompleta .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Quantidade','Quantidade',$arrTemp).'</th>'."\n";
      $strTabelaUnidadesGeradosCompleta .= '</tr>'."\n";
      $strTabelaUnidadesGeradosCompleta .= $strTabelaUnidadesGerados;
      if ($totalUnidadesGerados > 1){
        $strTabelaUnidadesGeradosCompleta .= '<tr class="totalEstatisticas"><td align="right" colspan="2"><b>TOTAL:</b></td><td align="center">'.InfraUtil::formatarMilhares($totalGeral).'</td></tr>';
      }
      $strTabelaUnidadesGeradosCompleta .= '</table>';
      
      
      $strResultadoGraficoUnidadesGerados = '';
      if ($totalUnidadesGerados > 1){
        
      	$strResultadoGraficoUnidadesGerados .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	$strResultadoGraficoUnidadesGerados .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_INSPECAO_UNIDADES_GERADOS,null,$arrayGraficoGeradosUnidade, 300, $arrCores[0]);
      	$strResultadoGraficoUnidadesGerados .= '</div>';
      } 
      
    }    

    //GERADOS TIPOS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $strCssTr='';
    $totalTiposGerados = 0;
		$totalGeral = 0;	
    $strTabelaTiposGerados = '';
    
    if ($objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_TIPOS_GERADOS){
      foreach ($objEstatisticasInspecaoDTORet->getArrTiposProcessosGerados() as $keyOrgaoTipoProcedimento => $numRegistros){
      	
        $arr = explode('#',$keyOrgaoTipoProcedimento);
        $keyOrgao = $arr[0];
        $keyTipoProcedimento = $arr[1];
        
        if ($arrObjOrgaoDTO[$keyOrgao]!=null){
        	$numIdOrgao = $arrObjOrgaoDTO[$keyOrgao]->getNumIdOrgao();
        	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
        	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
        }else{
        	$numIdOrgao = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }
        
        //foreach ($arrTipos as $keyTipoProcedimento => $numRegistros){
          if ($arrObjTipoProcedimentoDTO[$keyTipoProcedimento]!=null){
          	$numIdTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipoProcedimento]->getNumIdTipoProcedimento();
          	$strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipoProcedimento]->getStrNome();
          }else{
           	$numIdTipoProcedimento = '';
          	$strNomeTipoProcedimento = '';
          }
        	
          $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
          $strTabelaTiposGerados .= $strCssTr;
    	    $strTabelaTiposGerados .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
    	    $strTabelaTiposGerados .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'" title="'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'</a></td>';
          //$strTabelaTiposGerados .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="linkFuncionalidade">'.$numRegistros.'</a></td>';
          $strTabelaTiposGerados .= '<td align="center">'.InfraUtil::formatarMilhares($numRegistros).'</td>';
          $strTabelaTiposGerados .= '</tr>'."\n";
    
          $arrayGraficoGeradosTipoProcedimento[] = array($strNomeTipoProcedimento.' / '.$strSiglaOrgao, InfraUtil::formatarMilhares($numRegistros),$numRegistros);
    
          $totalGeral += $numRegistros;
          
    			$totalTiposGerados++;
        //}
      }
    
  		$strTabelaTiposGeradosCompleta = '';
      $strTabelaTiposGeradosCompleta .= '<table width="70%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_TIPOS_GERADOS.'">'."\n";
      $strTabelaTiposGeradosCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_TIPOS_GERADOS.':</caption>';
      $strTabelaTiposGeradosCompleta .= '<tr>';
      $strTabelaTiposGeradosCompleta .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaTiposGeradosCompleta .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Tipo de Processo','NomeTipoProcedimento',$arrTemp).'</th>'."\n";
      $strTabelaTiposGeradosCompleta .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Quantidade','Quantidade',$arrTemp).'</th>'."\n";
      $strTabelaTiposGeradosCompleta .= '</tr>'."\n";
      $strTabelaTiposGeradosCompleta .= $strTabelaTiposGerados;
      if ($totalTiposGerados > 1){
        $strTabelaTiposGeradosCompleta .= '<tr class="totalEstatisticas"><td align="right" colspan="2"><b>TOTAL:</b></td><td align="center">'.InfraUtil::formatarMilhares($totalGeral).'</td></tr>';
      }
      $strTabelaTiposGeradosCompleta .= '</table>';
      
      
      $strResultadoGraficoTiposGerados = '';
      if ($objEstatisticasInspecaoDTO->getNumIdTipoProcedimento()==null && $totalTiposGerados > 1){
	    	$strResultadoGraficoTiposGerados .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
	    	$strResultadoGraficoTiposGerados .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS,null,$arrayGraficoGeradosTipoProcedimento, 300, $arrCores[0]);
	    	$strResultadoGraficoTiposGerados .= '</div><br />';
        
      }
    }    
    
    
    //TRAMITACAO UNIDADES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $strCssTr='';
    $totalUnidadesTramitacao = 0;
		$totalGeral = 0;	
    $strTabelaUnidadesTramitacao = '';
    
    if ($objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_TRAMITACAO){
      foreach ($objEstatisticasInspecaoDTORet->getArrUnidadesTramitacao() as $keyUnidade => $numRegistros){
      	
        if ($arrObjUnidadeDTO[$keyUnidade]!=null){
        	$numIdUnidade = $arrObjUnidadeDTO[$keyUnidade]->getNumIdUnidade();
        	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
        	$strDescricaoUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
        	$strSiglaOrgao = $arrObjUnidadeDTO[$keyUnidade]->getStrSiglaOrgao();
        	$strDescricaoOrgao = $arrObjUnidadeDTO[$keyUnidade]->getStrDescricaoOrgao();
        	//$strNomeCidade = $arrObjUnidadeDTO[$keyUnidade]->getStrNomeCidade();
        }else{
         	$numIdUnidade = '';
        	$strSiglaUnidade = '';
        	$strDescricaoUnidade = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabelaUnidadesTramitacao .= $strCssTr;
  	    $strTabelaUnidadesTramitacao .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
  	    $strTabelaUnidadesTramitacao .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoUnidade).'" title="'.PaginaSEI::tratarHTML($strDescricaoUnidade).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaUnidade).'</a></td>';
        //$strTabelaUnidadesTramitacao .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="linkFuncionalidade">'.$numRegistros.'</a></td>';
        $strTabelaUnidadesTramitacao .= '<td align="center">'.InfraUtil::formatarMilhares($numRegistros).'</td>';
        $strTabelaUnidadesTramitacao .= '</tr>'."\n";
        
        $arrayGraficoTramitacaoUnidade[] = array($strSiglaUnidade.' / '.$strSiglaOrgao, InfraUtil::formatarMilhares($numRegistros),$numRegistros);
        
  
        $totalGeral += $numRegistros;
        
  			$totalUnidadesTramitacao++;
      }
    
  		$strTabelaUnidadesTramitacaoCompleta = '';
      $strTabelaUnidadesTramitacaoCompleta .= '<table width="70%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_UNIDADES_TRAMITACAO.'">'."\n";
      $strTabelaUnidadesTramitacaoCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_UNIDADES_TRAMITACAO.':</caption>';
      $strTabelaUnidadesTramitacaoCompleta .= '<tr>';
      $strTabelaUnidadesTramitacaoCompleta .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaUnidadesTramitacaoCompleta .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Unidade','SiglaUnidade',$arrTemp).'</th>'."\n";
      $strTabelaUnidadesTramitacaoCompleta .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Quantidade','Quantidade',$arrTemp).'</th>'."\n";
      $strTabelaUnidadesTramitacaoCompleta .= '</tr>'."\n";
      $strTabelaUnidadesTramitacaoCompleta .= $strTabelaUnidadesTramitacao;
      if ($totalUnidadesTramitacao > 1){
        $strTabelaUnidadesTramitacaoCompleta .= '<tr class="totalEstatisticas"><td align="right" colspan="2"><b>TOTAL:</b></td><td align="center">'.InfraUtil::formatarMilhares($totalGeral).'</td></tr>';
      }
      $strTabelaUnidadesTramitacaoCompleta .= '</table>';
      
      $strResultadoGraficoUnidadesTramitacao = '';
      if ($totalUnidadesTramitacao > 1){
      	$strResultadoGraficoUnidadesTramitacao .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	$strResultadoGraficoUnidadesTramitacao .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_INSPECAO_UNIDADES_TRAMITACAO,null,$arrayGraficoTramitacaoUnidade, 300, $arrCores[0]);
      	$strResultadoGraficoUnidadesTramitacao .= '</div>';
        
      }
    } 

  	//DOCUMENTOS ORGAOS////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $strCssTr='';
    $totalDocumentosOrgaos = 0;
		$totalGeralGerados = 0;	
		$totalGeralRecebidos = 0;
    $strTabelaDocumentosOrgaos = '';
    $arrGraficoDocumentosOrgaosGerados = array();
    $arrGraficoDocumentosOrgaosRecebidos = array();
    
    if ($objEstatisticasInspecaoDTO->getNumIdUnidade()==null && $objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_ORGAOS_DOCUMENTOS){
      
      foreach ($objEstatisticasInspecaoDTORet->getArrOrgaosDocumentos() as $keyOrgao => $arrDocumentos){
      	
        if ($arrObjOrgaoDTO[$keyOrgao]!=null){
        	$numIdOrgao = $arrObjOrgaoDTO[$keyOrgao]->getNumIdOrgao();
        	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
        	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
        }else{
        	$numIdOrgao = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }
        	      	
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabelaDocumentosOrgaos .= $strCssTr;
  	    $strTabelaDocumentosOrgaos .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
        $strTabelaDocumentosOrgaos .= '<td align="center">'.InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]).'</td>';
        $strTabelaDocumentosOrgaos .= '<td align="center">'.InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]).'</td>';
        $strTabelaDocumentosOrgaos .= '</tr>'."\n";

        if (!is_numeric($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO])){
          $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO] = 0;
        }

        if (!is_numeric($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO])){
          $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO] = 0;
        }
        
        $arrGraficoDocumentosOrgaosGerados[$strSiglaOrgao] = array($strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]),$arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]);
        $arrGraficoDocumentosOrgaosRecebidos[$strSiglaOrgao] = array($strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]),$arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]);

        $totalGeralGerados += $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO];
        $totalGeralRecebidos += $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO];
      
			  $totalDocumentosOrgaos++;
      }

      $strTabelaDocumentosOrgaosCompleta .= '<table width="50%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_ORGAOS_DOCUMENTOS.'">'."\n";
      $strTabelaDocumentosOrgaosCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_ORGAOS_DOCUMENTOS.':</caption>';
      $strTabelaDocumentosOrgaosCompleta .= '<tr>';
      $strTabelaDocumentosOrgaosCompleta .= '<th class="infraTh" width="50%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaDocumentosOrgaosCompleta .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Gerados','QuantidadeGerados',$arrTemp).'</th>'."\n";
      $strTabelaDocumentosOrgaosCompleta .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Recebidos','QuantidadeRecebidos',$arrTemp).'</th>'."\n";
      $strTabelaDocumentosOrgaosCompleta .= '</tr>'."\n";
      $strTabelaDocumentosOrgaosCompleta .= $strTabelaDocumentosOrgaos;
      
      if ($totalDocumentosOrgaos > 1){
        $strTabelaDocumentosOrgaosCompleta .= '<tr class="totalEstatisticas"><td align="right"><b>TOTAL:</b></td>';
        $strTabelaDocumentosOrgaosCompleta .= '<td align="center">'.InfraUtil::formatarMilhares($totalGeralGerados).'</td>';
        $strTabelaDocumentosOrgaosCompleta .= '<td align="center">'.InfraUtil::formatarMilhares($totalGeralRecebidos).'</td>';
        $strTabelaDocumentosOrgaosCompleta .= '</tr>';
      }
      $strTabelaDocumentosOrgaosCompleta .= '</table>';

      $strResultadoGraficoDocumentosOrgaos = '';
      if ($totalDocumentosOrgaos > 1){
        
      	$strResultadoGraficoDocumentosOrgaos .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	$strResultadoGraficoDocumentosOrgaos .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Documentos gerados por órgão',null,$arrGraficoDocumentosOrgaosGerados,150,'#3399CC'); 
      	$strResultadoGraficoDocumentosOrgaos .= '</div>';

      	$strResultadoGraficoDocumentosOrgaos .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	$strResultadoGraficoDocumentosOrgaos .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Documentos externos por órgão',null,$arrGraficoDocumentosOrgaosRecebidos,150,'red');
      	$strResultadoGraficoDocumentosOrgaos .= '</div>';
      } 
    }
    

    
  	//DOCUMENTOS UNIDADES////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $strCssTr='';
    $totalDocumentosUnidades = 0;
		$totalGeralGerados = 0;	
		$totalGeralRecebidos = 0;
    $strTabelaDocumentosUnidades = '';
    $arrGraficoDocumentosUnidades = array();
    
    if ($objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_UNIDADES_DOCUMENTOS){
      
      foreach ($objEstatisticasInspecaoDTORet->getArrUnidadesDocumentos() as $keyUnidade => $arrDocumentos){
        
        if ($arrObjUnidadeDTO[$keyUnidade]!=null){
        	$numIdUnidade = $arrObjUnidadeDTO[$keyUnidade]->getNumIdUnidade();
        	$strSiglaUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrSigla();
        	$strDescricaoUnidade = $arrObjUnidadeDTO[$keyUnidade]->getStrDescricao();
        	$strSiglaOrgao = $arrObjUnidadeDTO[$keyUnidade]->getStrSiglaOrgao();
        	$strDescricaoOrgao = $arrObjUnidadeDTO[$keyUnidade]->getStrDescricaoOrgao();
        	//$strNomeCidade = $arrObjUnidadeDTO[$keyUnidade]->getStrNomeCidade();
        }else{
         	$numIdUnidade = '';
        	$strSiglaUnidade = '';
        	$strDescricaoUnidade = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }
                	      	
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabelaDocumentosUnidades .= $strCssTr;
  	    $strTabelaDocumentosUnidades .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
  	    $strTabelaDocumentosUnidades .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoUnidade).'" title="'.PaginaSEI::tratarHTML($strDescricaoUnidade).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaUnidade).'</a></td>';
        $strTabelaDocumentosUnidades .= '<td align="center">'.InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]).'</td>';
        $strTabelaDocumentosUnidades .= '<td align="center">'.InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]).'</td>';
        $strTabelaDocumentosUnidades .= '</tr>'."\n";

        //$arrGraficoDocumentosUnidades[$strSiglaOrgao][] = array($strSiglaUnidade,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]),null,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]),null);
        //$arrGraficoDocumentosUnidades[] = array($strSiglaUnidade.' / '.$strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]),null,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]),null);
        
        if (!is_numeric($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO])){
          $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO] = 0;
        }
  
        if (!is_numeric($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO])){
          $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO] = 0;
        }
        
        $arrGraficoDocumentosUnidadesGerados[] = array($strSiglaUnidade.' / '.$strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]),$arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]);
        $arrGraficoDocumentosUnidadesRecebidos[] = array($strSiglaUnidade.' / '.$strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]),$arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]);

        $totalGeralGerados += $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO];
        $totalGeralRecebidos += $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO];
      
			  $totalDocumentosUnidades++;
      }

      $strTabelaDocumentosUnidadesCompleta .= '<table width="70%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_UNIDADES_DOCUMENTOS.'">'."\n";
      $strTabelaDocumentosUnidadesCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_UNIDADES_DOCUMENTOS.':</caption>';
      $strTabelaDocumentosUnidadesCompleta .= '<tr>';
      $strTabelaDocumentosUnidadesCompleta .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaDocumentosUnidadesCompleta .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Unidade','SiglaUnidade',$arrTemp).'</th>'."\n";
      $strTabelaDocumentosUnidadesCompleta .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Gerados','QuantidadeGerados',$arrTemp).'</th>'."\n";
      $strTabelaDocumentosUnidadesCompleta .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Recebidos','QuantidadeRecebidos',$arrTemp).'</th>'."\n";
      $strTabelaDocumentosUnidadesCompleta .= '</tr>'."\n";
      $strTabelaDocumentosUnidadesCompleta .= $strTabelaDocumentosUnidades;
      
      if ($totalDocumentosUnidades > 1){
        $strTabelaDocumentosUnidadesCompleta .= '<tr class="totalEstatisticas"><td align="right" colspan="2"><b>TOTAL:</b></td>';
        $strTabelaDocumentosUnidadesCompleta .= '<td align="center">'.InfraUtil::formatarMilhares($totalGeralGerados).'</td>';
        $strTabelaDocumentosUnidadesCompleta .= '<td align="center">'.InfraUtil::formatarMilhares($totalGeralRecebidos).'</td>';
        $strTabelaDocumentosUnidadesCompleta .= '</tr>';
      }
      $strTabelaDocumentosUnidadesCompleta .= '</table>';

      $strResultadoGraficoDocumentosUnidades = '';
      if ($totalDocumentosUnidades > 1){
      	$strResultadoGraficoDocumentosUnidades .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	//$strResultadoGraficoDocumentosUnidades .= 'Documentos gerados por órgão e unidade:<br />';
      	$strResultadoGraficoDocumentosUnidades .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Documentos gerados por órgão e unidade',null,$arrGraficoDocumentosUnidadesGerados, 150, '#3399CC'); 
      	$strResultadoGraficoDocumentosUnidades .= '</div>';
      	
      	$strResultadoGraficoDocumentosUnidades .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
      	//$strResultadoGraficoDocumentosUnidades .= 'Documentos externos por órgão e unidade:<br />';
      	$strResultadoGraficoDocumentosUnidades .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Documentos externos por órgão e unidade',null,$arrGraficoDocumentosUnidadesRecebidos, 150, 'red');
      	$strResultadoGraficoDocumentosUnidades .= '</div>';
      	
      } 
    }

    
    
    //TIPOS DOCUMENTOS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $strCssTr='';
    $totalTiposDocumentos = 0;
		$totalGeralGerados = 0;	
		$totalGeralRecebidos = 0;
    $strTabelaTiposDocumentos = '';
    
    if ($objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_TIPOS_DOCUMENTOS){
      foreach ($objEstatisticasInspecaoDTORet->getArrTiposDocumentos() as $keyOrgaoSerie => $arrDocumentos){
      	
        $arr = explode('#',$keyOrgaoSerie);
        
        $keyOrgao = $arr[0];
        $keySerie = $arr[1];
        
        if ($arrObjOrgaoDTO[$keyOrgao]!=null){
        	$numIdOrgao = $arrObjOrgaoDTO[$keyOrgao]->getNumIdOrgao();
        	$strSiglaOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrSigla();
        	$strDescricaoOrgao = $arrObjOrgaoDTO[$keyOrgao]->getStrDescricao();
        }else{
        	$numIdOrgao = '';
        	$strSiglaOrgao = '';
        	$strDescricaoOrgao = '';
        }
        
        //foreach ($arrTipos as $keySerie => $arrDocumentos){
          if ($arrObjSerieDTO[$keySerie]!=null){
          	$numIdSerie = $arrObjSerieDTO[$keySerie]->getNumIdSerie();
          	$strNomeSerie = $arrObjSerieDTO[$keySerie]->getStrNome();
          }else{
           	$numIdSerie = '';
          	$strNomeSerie = '';
          }
        	
          $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
          $strTabelaTiposDocumentos .= $strCssTr;
    	    $strTabelaTiposDocumentos .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" title="'.PaginaSEI::tratarHTML($strDescricaoOrgao).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strSiglaOrgao).'</a></td>';
    	    $strTabelaTiposDocumentos .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($strNomeSerie).'" title="'.PaginaSEI::tratarHTML($strNomeSerie).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($strNomeSerie).'</a></td>';
    	    $strTabelaTiposDocumentos .= '<td align="center">'.InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]).'</td>';
          $strTabelaTiposDocumentos .= '<td align="center">'.InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]).'</td>';
          $strTabelaTiposDocumentos .= '</tr>'."\n";
    
          //$arrayGraficoGeradosTipoDocumento[$strSiglaOrgao][] = array($strNomeSerie, $numRegistros);
          
          //$arrayGraficoGeradosTipoDocumento[$strSiglaOrgao][] = array($strNomeSerie,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]),null,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]),null);
          //$arrayGraficoGeradosTipoDocumento[] = array($strNomeSerie.' / '.$strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]),null,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]),null);
          
          if (!is_numeric($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO])){
            $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO] = 0;
          }

          if (!is_numeric($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO])){
            $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO] = 0;
          }
          
          $arrayGraficoGeradosTipoDocumentoGerados[] = array($strNomeSerie.' / '.$strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]),$arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO]);
          $arrayGraficoGeradosTipoDocumentoRecebidos[] = array($strNomeSerie.' / '.$strSiglaOrgao,InfraUtil::formatarMilhares($arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]),$arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO]);
    
          $totalGeralGerados += $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_GERADO];
          $totalGeralRecebidos += $arrDocumentos[ProtocoloRN::$TP_DOCUMENTO_RECEBIDO];
                    
    			$totalTiposDocumentos++;
        //}
      }
    
  		$strTabelaTiposDocumentosCompleta = '';
      $strTabelaTiposDocumentosCompleta .= '<table width="70%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_INSPECAO_TIPOS_DOCUMENTOS.'">'."\n";
      $strTabelaTiposDocumentosCompleta .= '<caption class="infraCaption">'.EstatisticasRN::$TITULO_INSPECAO_TIPOS_DOCUMENTOS.':</caption>';
      $strTabelaTiposDocumentosCompleta .= '<tr>';
      $strTabelaTiposDocumentosCompleta .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Órgão','SiglaOrgao',$arrTemp).'</th>'."\n";
      $strTabelaTiposDocumentosCompleta .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Tipo de Documento','NomeSerie',$arrTemp).'</th>'."\n";
      $strTabelaTiposDocumentosCompleta .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Gerados','QuantidadeGerados',$arrTemp).'</th>'."\n";
      $strTabelaTiposDocumentosCompleta .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Recebidos','QuantidadeRecebidos',$arrTemp).'</th>'."\n";
      $strTabelaTiposDocumentosCompleta .= '</tr>'."\n";
      $strTabelaTiposDocumentosCompleta .= $strTabelaTiposDocumentos;
      if ($totalTiposDocumentos > 1){
        $strTabelaTiposDocumentosCompleta .= '<tr class="totalEstatisticas"><td align="right" colspan="2"><b>TOTAL:</b></td>';
        $strTabelaTiposDocumentosCompleta .= '<td align="center">'.InfraUtil::formatarMilhares($totalGeralGerados).'</td>';
        $strTabelaTiposDocumentosCompleta .= '<td align="center">'.InfraUtil::formatarMilhares($totalGeralRecebidos).'</td>';
        $strTabelaTiposDocumentosCompleta .= '</tr>';
      }
      $strTabelaTiposDocumentosCompleta .= '</table>';
      
      $strResultadoGraficoTiposDocumentos = '';
      if ($objEstatisticasInspecaoDTO->getNumIdSerie()==null && $totalTiposDocumentos > 1){
	    	$strResultadoGraficoTiposDocumentos .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
	    	//$strResultadoGraficoTiposDocumentos .= 'Tipos de documentos gerados por órgão:<br />'; 
	    	$strResultadoGraficoTiposDocumentos .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Tipos de documentos gerados por órgão',null,$arrayGraficoGeradosTipoDocumentoGerados, 150, '#3399CC');
	    	$strResultadoGraficoTiposDocumentos .= '</div><br />';
	    	
	    	$strResultadoGraficoTiposDocumentos .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
	    	//$strResultadoGraficoTiposDocumentos .= 'Tipos de documentos externos por órgão:<br />';
	    	$strResultadoGraficoTiposDocumentos .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Tipos de documentos externos por órgão',null,$arrayGraficoGeradosTipoDocumentoRecebidos, 150, 'red');
	    	$strResultadoGraficoTiposDocumentos .= '</div><br />';
	    	
      }
    }    
    
    
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $strResultadoMovimentacao = '';
    $numRegistrosMovimentacao = 0;
    
    if ($objEstatisticasInspecaoDTO->getStrStaTipo()==EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO){
    
      $arrObjEstatisticasAtividadeDTO = $objEstatisticasInspecaoDTORet->getArrMovimentacao();
      
      $numRegistrosMovimentacao = InfraArray::contar($arrObjEstatisticasAtividadeDTO);
      
      if ($numRegistrosMovimentacao){
        
        $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    
        $strSumarioTabela = 'Tabela de Processos.';
        $strCaptionTabela = 'Processos';
    
        $strResultadoMovimentacao .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
        $strResultadoMovimentacao .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistrosMovimentacao).'</caption>';
        $strResultadoMovimentacao .= '<tr>';
        $strResultadoMovimentacao .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
        $strResultadoMovimentacao .= '<th class="infraTh" width="17%">Processo</th>'."\n";
        $strResultadoMovimentacao .= '<th class="infraTh" >Tipo</th>'."\n";  
        $strResultadoMovimentacao .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Unidade','SiglaUnidade',$arrObjEstatisticasAtividadeDTO).'</th>'."\n";
        $strResultadoMovimentacao .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objEstatisticasInspecaoDTO,'Última Movimentação','Abertura',$arrObjEstatisticasAtividadeDTO).'</th>'."\n";
        $strResultadoMovimentacao .= '<th class="infraTh" width="10%">Dias</th>'."\n";
        $strResultadoMovimentacao .= '</tr>'."\n";
        $strCssTr='';
        for($i = 0;$i < $numRegistrosMovimentacao; $i++){
    
          $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
          $strResultadoMovimentacao .= $strCssTr;
          $strResultadoMovimentacao .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjEstatisticasAtividadeDTO[$i]->getDblIdProtocolo(),$arrObjEstatisticasAtividadeDTO[$i]->getDthAbertura()).'</td>';
          $strResultadoMovimentacao .= '<td align="center"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjEstatisticasAtividadeDTO[$i]->getDblIdProtocolo()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasAtividadeDTO[$i]->getStrNomeTipoProcedimento()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasAtividadeDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal">'.PaginaSEI::tratarHTML($arrObjEstatisticasAtividadeDTO[$i]->getStrProtocoloFormatadoProtocolo()).'</a></td>';
          $strResultadoMovimentacao .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjEstatisticasAtividadeDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
          $strResultadoMovimentacao .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasAtividadeDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasAtividadeDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEstatisticasAtividadeDTO[$i]->getStrSiglaUnidade()).'</a></td>';
          $strResultadoMovimentacao .= '<td align="center">'.$arrObjEstatisticasAtividadeDTO[$i]->getDthAbertura().'</td>';
          $strResultadoMovimentacao .= '<td align="center">'.InfraUtil::formatarMilhares($arrObjEstatisticasAtividadeDTO[$i]->getNumDias()).'</td>';
          
          $strResultadoMovimentacao .= '</tr>'."\n";
        }
        $strResultadoMovimentacao .= '</table>';
      }
    }
  }  
  
  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('', 'Todos', $objEstatisticasInspecaoDTO->getNumIdOrgao());
  $strItensSelTipo = EstatisticasINT::montarSelectTipoInspecao($objEstatisticasInspecaoDTO->getStrStaTipo());
  
  $strDesabilitarOrgao = '';
  if ($bolInspecaoOrgao){
    $strDesabilitarOrgao = 'disabled="disabled"';
  }
  
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');     	 
  $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('', 'Todos', $objEstatisticasInspecaoDTO->getNumIdTipoProcedimento());
  $strItensSelSerie = SerieINT::montarSelectNomeRI0802('', 'Todos', $objEstatisticasInspecaoDTO->getNumIdSerie());
  
  
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
#lblOrgao {position:absolute;left:0%;top:0%;}
#selOrgao {position:absolute;left:0%;top:20%;width:30%;}

#lblTipo {position:absolute;left:0%;top:50%;}
#selTipo {position:absolute;left:0%;top:70%;width:50%;}

#divUnidade {display:none;}
#lblUnidade {position:absolute;left:0%;top:0%;}
#txtUnidade {position:absolute;left:0%;top:40%;width:70%;}

#divTipoProcedimento {display:none;}
#lblTipoProcedimento {position:absolute;left:0%;top:0%;}
#selTipoProcedimento {position:absolute;left:0%;top:40%;width:70%;}

#divSerie {display:none;}
#lblSerie {position:absolute;left:0%;top:0%;}
#selSerie {position:absolute;left:0%;top:40%;width:50%;}


#divPeriodo {display:none;}
#lblPeriodoDe {position:absolute;left:0%;top:0%;}
#txtPeriodoDe {position:absolute;left:0%;top:40%;width:10%;}
#imgCalPeriodoD {position:absolute;left:10.7%;top:45%;}

#lblPeriodoA 	{position:relative;left:13.5%;top:40%;}
#txtPeriodoA 	{position:absolute;left:16%;top:40%;width:10%;}
#imgCalPeriodoA {position:absolute;left:26.7%;top:45%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->abrirStyleIE();
?>
#txtUnidade {width:63%;}
<?
PaginaSEI::getInstance()->fecharStyleIE();
PaginaSEI::getInstance()->montarJavaScript();
?>
<script type="text/javascript" src="/infra_js/raphaeljs/raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.bar-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.pie-min.js"></script>
<?  
PaginaSEI::getInstance()->abrirJavaScript();
?>

function alterarOrgao(){
  objAutoCompletarUnidade.limpar();
}

function alterarTipo(bolLimpar){

  var tipo = document.getElementById('selTipo').value;

  document.getElementById('divUnidade').style.display = 'none';
  document.getElementById('divTipoProcedimento').style.display = 'none';
  document.getElementById('divSerie').style.display = 'none';
  document.getElementById('divPeriodo').style.display = 'none';
   

  if (bolLimpar){
    if (document.getElementById('hdnInfraCampoOrd')!=null){
      if (tipo != '<?=EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO?>'){
        document.getElementById('hdnInfraCampoOrd').value = 'SiglaOrgao';
      }else{
        document.getElementById('hdnInfraCampoOrd').value = 'Abertura';
      }
    }
    
    if (document.getElementById('hdnInfraTipoOrd')!=null){
      if (tipo != '<?=EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO?>'){
        document.getElementById('hdnInfraTipoOrd').value = 'ASC';
      }else{
        document.getElementById('hdnInfraTipoOrd').value = 'DESC';
      }
    }
    
    document.getElementById('divResultado').style.display = 'none';
  }

  if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_ORGAOS_GERADOS?>'){
  
    document.getElementById('divPeriodo').style.display = 'block';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    document.getElementById('selSerie').selectedIndex = 1;
    objAutoCompletarUnidade.limpar();
  
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_ORGAOS_TRAMITACAO?>'){
  
    document.getElementById('txtPeriodoA').value = '';
    document.getElementById('txtPeriodoDe').value = '';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    document.getElementById('selSerie').selectedIndex = 1;
    objAutoCompletarUnidade.limpar();
  
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_ORGAOS_DOCUMENTOS?>'){
  
    document.getElementById('divPeriodo').style.display = 'block';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    document.getElementById('selSerie').selectedIndex = 1;
    objAutoCompletarUnidade.limpar();
       
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_UNIDADES_GERADOS?>'){
  
    document.getElementById('divPeriodo').style.display = 'block';
    document.getElementById('divUnidade').style.display = 'block';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    document.getElementById('selSerie').selectedIndex = 1;
    
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_UNIDADES_TRAMITACAO?>'){
  
    document.getElementById('txtPeriodoA').value = '';
    document.getElementById('txtPeriodoDe').value = '';
    document.getElementById('divUnidade').style.display = 'block';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    document.getElementById('selSerie').selectedIndex = 1;
    
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_UNIDADES_DOCUMENTOS?>'){
    
    document.getElementById('divPeriodo').style.display = 'block';
    document.getElementById('divUnidade').style.display = 'block';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    document.getElementById('selSerie').selectedIndex = 1;
    
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO?>'){

    document.getElementById('txtPeriodoA').value = '';
    document.getElementById('txtPeriodoDe').value = '';
    document.getElementById('divUnidade').style.display = 'block';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    document.getElementById('selSerie').selectedIndex = 1;
    
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_TIPOS_GERADOS?>'){
  
    document.getElementById('divPeriodo').style.display = 'block';
    document.getElementById('divTipoProcedimento').style.display = 'block';
    document.getElementById('selSerie').selectedIndex = 1;
    objAutoCompletarUnidade.limpar();
    
  }else if (tipo == '<?=EstatisticasRN::$TIPO_INSPECAO_TIPOS_DOCUMENTOS?>'){
  
    document.getElementById('divPeriodo').style.display = 'block';
    document.getElementById('divSerie').style.display = 'block';
    document.getElementById('selTipoProcedimento').selectedIndex = 1;
    objAutoCompletarUnidade.limpar();
    
  }
};

function limpar() {
  document.getElementById('selOrgao').selectedIndex = 0;
  document.getElementById('selTipo').selectedIndex = 0;
  
  document.getElementById('txtPeriodoA').value = '';
  document.getElementById('txtPeriodoDe').value = '';
  
  document.getElementById('divUnidade').style.display = 'none';
  objAutoCompletarUnidade.limpar();
    
  document.getElementById('divTipoProcedimento').style.display = 'none';
  document.getElementById('selTipoProcedimento').selectedIndex = 1;
    
  document.getElementById('divSerie').style.display = 'none';
  document.getElementById('selSerie').selectedIndex = 1;
  
  document.getElementById('divResultado').style.display = 'none';
  
}

var objAutoCompletarUnidade;

function inicializar(){

  if ('<?=$_GET['acao_origem']?>'!='inspecao_administrativa_gerar'){
    infraOcultarMenuSistemaEsquema();
  }

  infraAdicionarEvento(window,'resize',seiRedimensionarGraficos);

  //UNIDADE /////////////////////////////////////////////////////////////////////////////////////////////////  
  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
  //objAutoCompletarUnidade.maiusculas = true;
  //objAutoCompletarUnidade.mostrarAviso = true;
  //objAutoCompletarUnidade.tempoAviso = 1000;
  //objAutoCompletarUnidade.tamanhoMinimo = 3;
  objAutoCompletarUnidade.limparCampo = true;
  //objAutoCompletarUnidade.bolExecucaoAutomatica = false;

  objAutoCompletarUnidade.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao='+document.getElementById('selOrgao').value;
  };

  objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      document.getElementById('hdnIdUnidade').value = id;
      document.getElementById('txtUnidade').value = descricao;
    }
  }
  objAutoCompletarUnidade.selecionar('<?=$strIdUnidade?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUnidade,false)?>');
  
  alterarTipo(false);
  infraProcessarResize();
  infraAviso();
  infraEfeitoTabelas();
  seiRedimensionarGraficos();
}

function onSubmitForm(){

  if (!infraSelectSelecionado('selOrgao')){
    alert('Selecione Órgão para inspeção.');
    document.getElementById('selOrgao').focus();
    return false;
  }
  
  if (!infraSelectSelecionado('selTipo')){
    alert('Selecione Tipo da inspeção.');
    document.getElementById('selTipo').focus();
    return false;
  } 

  infraExibirAviso(true);  
  return true;
}

function abrirDetalhe(link){
 infraAbrirJanela(link,'janelaInspecaoDetalhe',750,550,'location=0,status=1,resizable=1,scrollbars=1');
}



<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInspecaoAdministrativa"  method="post" onsubmit="return onSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>
  <div id="divGeral" class="infraAreaDados" style="height:10em;">
    <label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelObrigatorio">Órgão:</label>
    <select id="selOrgao" name="selOrgao" onchange="alterarOrgao()" class="infraSelect" <?=$strDesabilitarOrgao?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelOrgao?>
    </select>
  
    <label id="lblTipo" for="selTipo" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
    <select id="selTipo" name="selTipo" onchange="alterarTipo(true)" class="infraSelect" <?=$strDesabilitarTipo?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelTipo?>
    </select>
  </div>
  
  <div id="divUnidade" class="infraAreaDados" style="height:4.6em;">
    <label id="lblUnidade" for="txtUnidade" accesskey="" class="infraLabelOpcional">Unidade:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" value="<?=$strNomeUnidade?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="<?=$strIdUnidade?>" />
  </div>
  
  <div id="divTipoProcedimento" class="infraAreaDados" style="height:4.6em;">
    <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Tipo de Processo:</label>
    <select id="selTipoProcedimento" name="selTipoProcedimento" class="infraSelect" <?=$strDesabilitarTipoProcedimento?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelTipoProcedimento?>
    </select>
  </div>  
  
  <div id="divSerie" class="infraAreaDados" style="height:4.6em;">
    <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Tipo de Documento:</label>
    <select id="selSerie" name="selSerie" class="infraSelect" <?=$strDesabilitarSerie?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelSerie?>
    </select>
  </div>  

  <div id="divPeriodo" class="infraAreaDados" style="height:4.6em;">
  
    <label id="lblPeriodoDe" for="txtperiodoDe" accesskey="P" class="infraLabelOpcional"><span class="infraTeclaAtalho">P</span>eríodo:</label>
    <input type="text" id="txtPeriodoDe" name="txtPeriodoDe" class="infraText" value="<?=$dtaPeriodoDe?>" onkeypress="return infraMascaraData(this, event)" />
		<img id="imgCalPeriodoD" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoDe',this);" />
    
    <label id="lblPeriodoA" for="txtperiodoA" accesskey="" class="infraLabelOpcional">a</label>
    <input type="text" id="txtPeriodoA" name="txtPeriodoA" class="infraText" value="<?=$dtaPeriodoA?>" onkeypress="return infraMascaraData(this, event)" />
    <img id="imgCalPeriodoA" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoA',this);" />
    
  </div>  
  <div id="divResultado">
  <?
    
  if ($objEstatisticasInspecaoDTO->getStrStaTipo()!=EstatisticasRN::$TIPO_INSPECAO_MOVIMENTACAO){
    
    if ($totalOrgaosGerados > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaOrgaosGeradosCompleta,$totalOrgaosGerados);
  	  if ($strResultadoGraficoOrgaosGerados != '') {
  	    EstatisticasINT::montarGrafico('OrgaosGerados',$strResultadoGraficoOrgaosGerados);
  	  }
    }
  
    if ($totalUnidadesGerados > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaUnidadesGeradosCompleta,$totalUnidadesGerados);
  	  if ($strResultadoGraficoUnidadesGerados != '') {
  	    EstatisticasINT::montarGrafico('UnidadesGerados',$strResultadoGraficoUnidadesGerados);
  	  }
  	}
  	
    if ($totalTiposGerados > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaTiposGeradosCompleta,$totalTiposGerados);
  	  if ($strResultadoGraficoTiposGerados != '') {
  	    EstatisticasINT::montarGrafico('TiposGerados',$strResultadoGraficoTiposGerados);
  	  }
  	}
  	
    if ($totalDocumentosOrgaos > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaDocumentosOrgaosCompleta,$totalDocumentosOrgaos);
  	  if ($strResultadoGraficoDocumentosOrgaos != '') {
  	    EstatisticasINT::montarGrafico('OrgaosDocumentos',$strResultadoGraficoDocumentosOrgaos);
  	  }
  	}
    
  	if ($totalDocumentosUnidades > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaDocumentosUnidadesCompleta,$totalDocumentosUnidades);
  	  if ($strResultadoGraficoDocumentosUnidades != '') {
  	    EstatisticasINT::montarGrafico('UnidadesDocumentos',$strResultadoGraficoDocumentosUnidades);
  	  }
  	}

    if ($totalTiposDocumentos > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaTiposDocumentosCompleta,$totalTiposDocumentos);
  	  if ($strResultadoGraficoTiposDocumentos != '') {
  	    EstatisticasINT::montarGrafico('TiposDocumentos',$strResultadoGraficoTiposDocumentos);
  	  }
  	}
  	
    if ($totalOrgaosTramitacao > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaOrgaosTramitacaoCompleta,$totalOrgaosTramitacao);
  	  if ($strResultadoGraficoOrgaosTramitacao != '') {
  	    EstatisticasINT::montarGrafico('OrgaosTramitacao',$strResultadoGraficoOrgaosTramitacao);
  	  }
  	}
  
    if ($totalUnidadesTramitacao > 0) {
  	  PaginaSEI::getInstance()->montarAreaTabela($strTabelaUnidadesTramitacaoCompleta,$totalUnidadesTramitacao);
  	  if ($strResultadoGraficoUnidadesTramitacao != '') {
  	    EstatisticasINT::montarGrafico('UnidadesTramitacao',$strResultadoGraficoUnidadesTramitacao);
  	  }
  	}
    
  }else{
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoMovimentacao,$numRegistrosMovimentacao);
  }
  
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
  </div>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>