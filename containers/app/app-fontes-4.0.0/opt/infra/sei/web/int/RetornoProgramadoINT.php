<?php
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RetornoProgramadoINT extends InfraINT {

	public static $TP_NENHUM = 'N';
	public static $TP_AGENDADO = 'A';
	public static $TP_ATRASADO = 'T';
	
  public static function montarSelectIdRetornoProgramado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade='', $numIdAtividade='', $numIdUsuario=''){
    $objRetornoProgramadoDTO = new RetornoProgramadoDTO();
    $objRetornoProgramadoDTO->retNumIdRetornoProgramado();
    $objRetornoProgramadoDTO->retNumIdRetornoProgramado();

    if ($numIdUnidade!==''){
      $objRetornoProgramadoDTO->setNumIdUnidadeEnvio($numIdUnidade);
    }

    if ($numIdAtividade!==''){
      $objRetornoProgramadoDTO->setNumIdAtividade($numIdAtividade);
    }

    if ($numIdUsuario!==''){
      $objRetornoProgramadoDTO->setNumIdUsuario($numIdUsuario);
    }

    $objRetornoProgramadoDTO->setOrdNumIdRetornoProgramado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRetornoProgramadoRN = new RetornoProgramadoRN();
    $arrObjRetornoProgramadoDTO = $objRetornoProgramadoRN->listar($objRetornoProgramadoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRetornoProgramadoDTO, 'IdRetornoProgramado', 'IdRetornoProgramado');
  }
    
	public static function gerarCalendario($dtaAtual,$bolFlagTodoMes,$numDiaInicioMes, $numDiaFinalMes,$arrObjRetornoProgramadoDTOCalendario) {
		
		$strLink = 'controlador.php?acao=retorno_programado_listar';
		
		$numDia 			= substr($dtaAtual,0,2);
		$numMes 			= substr($dtaAtual,3,2);
		$numAno				= substr($dtaAtual,6,4);
		$numDiaSemana = date("w", mktime(0, 0, 0, $numMes, 1, $numAno));
		$numDiasMes 	= date("t", mktime(0, 0, 0, $numMes, 1, $numAno));
		
		if ($numMes < 10){
			$numIndiceMes = substr($numMes,1,1);
		}else {
			$numIndiceMes = $numMes;
		}

		$numMesAnterior = $numMes - 1;
		$numAnoAnterior = $numAno;
		if ($numMesAnterior == 0) {
			$numMesAnterior = 12;
			$numAnoAnterior = $numAno - 1;
		}
		$numMesSeguinte = $numMes + 1;
		$numAnoSeguinte = $numAno;
		if ($numMesSeguinte == 13) {
			$numMesSeguinte = 1;
			$numAnoSeguinte = $numAno + 1;
		}		
		
		$numAnoPosterior	= $numAno+1;
		$numAnoAntes			= $numAno-1;
		$numDiaAtual 			= 1;
		$strClassDiaConteudo = '';
		$strClassDiaUtil 	= '';
		
		$strCalendario = 	"<div id=\"divCalendario\">
												<table border=\"0\">
													<tr>
														<td colspan=\"7\" align=\"center\">
															<a href=\"".SessaoSEI::getInstance()->assinarLink($strLink."&data_atual="."".str_pad($numDiaAtual,2,0,STR_PAD_LEFT)."/".str_pad($numIndiceMes,2,0,STR_PAD_LEFT)."/".$numAnoAntes)."\">
																&lsaquo;&lsaquo;
															</a>
														&nbsp;".$numAno."&nbsp;
															<a href=\"".SessaoSEI::getInstance()->assinarLink($strLink."&data_atual="."".str_pad($numDiaAtual,2,0,STR_PAD_LEFT)."/".str_pad($numIndiceMes,2,0,STR_PAD_LEFT)."/".$numAnoPosterior)."\">
																&rsaquo;&rsaquo;
															</a>													
														</td>
													</tr>
													<tr>
														<td colspan=\"7\" align=\"center\">
															<a href=\"".SessaoSEI::getInstance()->assinarLink($strLink."&data_atual="."".str_pad($numDiaAtual,2,0,STR_PAD_LEFT)."/".str_pad($numMesAnterior,2,0,STR_PAD_LEFT)."/".$numAnoAnterior)."\">
																&lsaquo;&lsaquo;
															</a>
															<a href=\"".SessaoSEI::getInstance()->assinarLink($strLink."&data_inicial="."".str_pad($numDiaAtual,2,0,STR_PAD_LEFT)."/".str_pad($numMes,2,0,STR_PAD_LEFT)."/".$numAno."&data_final="."".$numDiasMes."/".str_pad($numMes,2,0,STR_PAD_LEFT)."/".$numAno)."\">
																&nbsp;".InfraData::descreverMes($numMes)."&nbsp;
															</a>	
															<a href=\"".SessaoSEI::getInstance()->assinarLink($strLink."&data_atual="."".str_pad($numDiaAtual,2,0,STR_PAD_LEFT)."/".str_pad($numMesSeguinte,2,0,STR_PAD_LEFT)."/".$numAnoSeguinte)."\">
																&rsaquo;&rsaquo;
															</a>
														</td>
													</tr>
													<tr>
														<td align=\"center\">D</td>
														<td align=\"center\">S</td>
														<td align=\"center\">T</td>
														<td align=\"center\">Q</td>
														<td align=\"center\">Q</td>
														<td align=\"center\">S</td>
														<td align=\"center\">S</td>
													</tr>";
											
		
		
		if ($numDiaSemana != 0) {
			$strCalendario.= '<tr>';
			$strCalendario.= "<td colspan=\"".($numDiaSemana)."\">&nbsp;</td>";
		}

		
		$numDiaHoje = substr(InfraData::getStrDataAtual(),0,2);
		$numMesHoje = substr(InfraData::getStrDataAtual(),3,2);
		$numAnoHoje = substr(InfraData::getStrDataAtual(),6,4);
		
		while ($numDiaAtual <= $numDiasMes) {

			if ($numDiaSemana == 0) {
				$strCalendario.= "<tr>";
			}

			$strClass = "";
			
			if (($numDiaSemana == 0) || ($numDiaSemana == 6)) {
				$strClass = "diaFimDeSemana";
			}else{
				$strClass = "diaUtil";
			}
			
			if ($numDiaAtual==$numDiaHoje && $numMes==$numMesHoje && $numAno==$numAnoHoje){
				$strClass = " diaHoje";
			}
			
			
			$numTipoPrazo = RetornoProgramadoINT::verificaExistencia($arrObjRetornoProgramadoDTOCalendario,$numDiaAtual,$numMes,$numAno);
			
			if ($numTipoPrazo==self::$TP_AGENDADO){
				$strClass .= " diaConteudo";
			}else if ($numTipoPrazo==self::$TP_ATRASADO){
				$strClass .= " diaAtrasado";
			}
			
			if($numDiaAtual == $numDia || $bolFlagTodoMes){
				$strClass .= " diaAtual";		
				$numIndiceSemana = $numDiaSemana;
			}
			
			if (($numDiaSemana != 0) && ($numDiaSemana != 6)) {
				$strCalendario.= "<td class=\"".$strClass."\" align=\"center\">
														<a href=\"".SessaoSEI::getInstance()->assinarLink($strLink."&data_atual=".str_pad($numDiaAtual,2,0,STR_PAD_LEFT)."/".$numMes."/".$numAno)."\">
															".$numDiaAtual."
														</a>
													</td>";			
			}else{
				$strCalendario.= "<td class=\"".$strClass."\" align=\"center\">
														<a href=\"".SessaoSEI::getInstance()->assinarLink($strLink."&data_atual=".str_pad($numDiaAtual,2,0,STR_PAD_LEFT)."/".$numMes."/".$numAno)."\">
															".$numDiaAtual."
														</a>	
													</td>";
			}
			
			if ($numDiaSemana == 6) {
				$numDiaSemana = -1;
				$strCalendario.= "</tr>";
			}
			
		  $numDiaSemana++;
		  $numDiaAtual++;
		}
		
		if (($numDiaSemana != 0) && ($numDiaSemana != 1) && ($numDiaSemana != 6)) {
		$strCalendario.= "<td colspan=\"".($numDiaSemana)."\" align=\"center\">
												&nbsp;
											</td>";
		}
		
		$strDiaSemana = InfraData::obterDescricaoDiaSemana($dtaAtual);
		$strDiaSemana = strtoupper(substr($strDiaSemana,0,1)).substr($strDiaSemana,1);
		
		if ($numDiaSemana){
		  $strCalendario.= '</tr>';
		}
		
		if (!$bolFlagTodoMes){
			$strCalendario.= "<tr style=\"background-color:#BBBBBB\">
													<td colspan=\"7\"></td>
												</tr>
												<tr>
	                        <td colspan=\"2\" align=\"center\">
														<font size=\"5\">".$numDia."</font>
													</td>
												
													<td colspan=\"5\" align=\"center\">
														<font size=\"1\">
														  <br />
															".$strDiaSemana."<br />".InfraData::descreverMes($numMes)." de ".$numAno."
															<br />
															<br />
														</font>
													</td>
												</tr>";

      if (!isset($_GET['data_inicial']) && !isset($_GET['data_final'])){
      	$strCalendario.= '<tr style="background-color:#BBBBBB"><td colspan="7"></td></tr>';
												
			  $strCalendario.= '<tr>';
        $strCalendario.= '<td colspan="7" align="center">';
				$strCalendario.= '<a href="'.SessaoSEI::getInstance()->assinarLink($strLink.'&data_inicial='.$numDiaInicioMes.'&data_final='.$numDiaFinalMes).'" class="ancoraPadraoPreta">Ver todo o mês</a>';
        $strCalendario.= '</td>';
        $strCalendario.= '</tr>';
      }
		}
		
		$strCalendario.='</table></div>';
		
		return $strCalendario;       
   }  
   
  public static function verificaExistencia($arrObjRetornoProgramadoDTOCalendario,$numDiaAtual,$numMes,$numAno){
  	$retorno = self::$TP_NENHUM;
  	foreach ($arrObjRetornoProgramadoDTOCalendario as $objRetornoProgramadoDTO) {
  		 if (substr($objRetornoProgramadoDTO->getDtaProgramada(),0,10) == str_pad($numDiaAtual,2,0,STR_PAD_LEFT).'/'.str_pad($numMes,2,0,STR_PAD_LEFT).'/'.$numAno) {
  		 	 
  		 	 $retorno = self::$TP_AGENDADO;
  		 	 
  		 	 if ($objRetornoProgramadoDTO->getNumDiasPrazo() < 0){
  		 	   	$retorno = self::$TP_ATRASADO;
  		 	   	break;
  		 	 }
  		 }
  	}
    return $retorno; 	
  }

  public static function montarIconeRetornoProgramadoDevolver($arrObjRetornoProgramadoDTO, &$strIconeRetornoProgramado, &$strTituloRetornoProgramado, &$strRetornoProgramado){

    $strRetornoProgramado = '';
    $strDataAtual = InfraData::getStrDataAtual();
    $flagNormal = 0;
    $flagAtrasado = 0;
    $flagConcluido = 0;

    foreach($arrObjRetornoProgramadoDTO as $objRetornoProgramadoDTO){
      if ($objRetornoProgramadoDTO->getNumIdUnidadeRetorno()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $strRetornoProgramado .= self::montarTextoRetornoProgramado($strDataAtual, $objRetornoProgramadoDTO->getStrSiglaUnidadeEnvio(), $objRetornoProgramadoDTO->getDtaProgramada(),$objRetornoProgramadoDTO->getDthAberturaAtividadeRetorno(), 'devolvido', $flagNormal, $flagAtrasado, $flagConcluido);
      }
    }

    if ($strRetornoProgramado != '') {

      if ($flagAtrasado){
        $strIconeRetornoProgramado = Icone::RETORNO_PROGRAMADO3;
        $strTituloRetornoProgramado = 'Para Devolver';
      }else if ($flagNormal){
        $strIconeRetornoProgramado = Icone::RETORNO_PROGRAMADO1;
        $strTituloRetornoProgramado = 'Para Devolver';
      }else{
        $strIconeRetornoProgramado = Icone::RETORNO_PROGRAMADO2;
        $strTituloRetornoProgramado = 'Devolução Cumprida';
      }

      return true;
    }

    return false;
  }

  public static function montarIconeRetornoProgramadoAguardando($arrObjRetornoProgramadoDTO, &$strIconeRetornoProgramado, &$strTituloRetornoProgramado, &$strRetornoProgramado){

    $strRetornoProgramado = '';
    $strDataAtual = InfraData::getStrDataAtual();
    $flagNormal = 0;
    $flagAtrasado = 0;
    $flagConcluido = 0;

    foreach($arrObjRetornoProgramadoDTO as $objRetornoProgramadoDTO){
      if ($objRetornoProgramadoDTO->getNumIdUnidadeEnvio()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $strRetornoProgramado .= self::montarTextoRetornoProgramado($strDataAtual, $objRetornoProgramadoDTO->getStrSiglaUnidadeRetorno(), $objRetornoProgramadoDTO->getDtaProgramada(), $objRetornoProgramadoDTO->getDthAberturaAtividadeRetorno(), 'retornado', $flagNormal, $flagAtrasado, $flagConcluido);
      }
    }

    if ($strRetornoProgramado != '') {

      if ($flagAtrasado){
        $strIconeRetornoProgramado = Icone::RETORNO_AGUARDANDO3;
        $strTituloRetornoProgramado = 'Aguardando Retorno';
      }else if ($flagNormal){
        $strIconeRetornoProgramado = Icone::RETORNO_AGUARDANDO1;
        $strTituloRetornoProgramado = 'Aguardando Retorno';
      }else{
        $strIconeRetornoProgramado = Icone::RETORNO_AGUARDANDO2;
        $strTituloRetornoProgramado = 'Retorno Cumprido';
      }

      return true;
    }

    return false;
  }

  private static function montarTextoRetornoProgramado($strDataAtual, $strSiglaUnidade, $srDataProgramada, $strDataRetorno, $strTipoFinalizacao, &$flagNormal, &$flagAtrasado, &$flagConcluido){

    $strTexto = $strSiglaUnidade.' '.$srDataProgramada.' (';

    if ($strDataRetorno != null){

      $flagConcluido = 1;

      $strTexto .= $strTipoFinalizacao.' em '.substr($strDataRetorno,0,10);

    }else {

      $numPrazo = InfraData::compararDatas($strDataAtual, $srDataProgramada);

      if ($numPrazo < 0) {
        $flagAtrasado = 1;
      } else {
        $flagNormal = 1;
      }

      if ($numPrazo == 0) {
        $strTexto .= 'até hoje';
      } else if ($numPrazo == 1) {
        $strTexto .= '1 dia';
      } else if ($numPrazo > 1) {
        $strTexto .= $numPrazo.' dias';
      } else if ($numPrazo == -1) {
        $strTexto .= 'atrasado 1 dia';
      } else if ($numPrazo < -1) {
        $strTexto .= 'atrasado '.abs($numPrazo).' dias';
      }
    }

    $strTexto .= ')'."\n";

    return $strTexto;
  }
}
?>