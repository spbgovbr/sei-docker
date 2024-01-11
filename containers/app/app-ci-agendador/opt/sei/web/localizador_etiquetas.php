<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/03/2008 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();


  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
	
  $arrComandos = array();
  
  
  switch($_GET['acao']){
    case 'localizador_imprimir_etiqueta':
      $strTitulo = 'Etiquetas de Localizadores';                               
      $arrComandos[] = '<input type="button" name="btnImprimir" value="Imprimir" onclick="imprimirEtiquetasRI1129();" class="infraButton" />';
      
      PaginaSEI::getInstance()->salvarSelecao($_GET['acao'],$_GET['acao_origem']);

      
      if (isset($_POST['hdnLocalizadores'])){
        $arrNumIdLocalizadores = array();
        $arr = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnLocalizadores']);
        foreach($arr as $item){
          $arrNumIdLocalizadores[] = $item[0];
        }
      }else{
        $arrNumIdLocalizadores = PaginaSEI::getInstance()->getArrStrItensSelecionados();  
      }
            
      $strAncora = '';
      if (InfraArray::contar($arrNumIdLocalizadores)>0){
         
      	$strLocalizadores = LocalizadorINT::buscarEtiquetasRI1127($arrNumIdLocalizadores);
    	}
      
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).$strAncora.'\';" class="infraButton" />';
      break;    

      
    case 'localizador_imprimir_etiqueta_pdf':
    
      $arr = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnLocalizadores']);
      
			$pdf = new InfraEtiquetasPDF('localizador', 'mm', 1, $_POST['txtLinha'],'H');	
			$pdf->Open();		
      
			for($i = 0;$i < InfraArray::contar($arr); $i++){
				 $pdf->Add_PDF_Label(str_replace('<br />',"\n", $arr[$i][1]), 0, 'C', 'H');
				 $pdf->Add_PDF_Label(str_replace('<br />',"\n", $arr[$i][1]), 0, 'C', 'H');
			}
			$pdf->Output();
			die;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	$strLinkPdfEtiquetas = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_imprimir_etiqueta_pdf');
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
#fldPosicionamento {position:absolute;left:0%;top:10%;width:40%;padding:1em;}

#lblLinha {width:20%;}
#txtLinha {width:10%;}

#lblColuna {width:20%;}
#txtColuna {width:10%;}

#lblAviso {width:100%;}


#fldOpcoes {position:absolute;left:50%;top:10%;width:25%;padding:1em;}

<?
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->abrirStyle('media="all"');
?>
	.pagina {
      page-break-after:always;
      margin-top: 12pt;             
  }

	.paginas {
      page-break-after:always;
      margin-top: 17pt;
  }

  .etiqueta {
      vertical-align:top;
  }
    
<?
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->abrirStyleIE('if IE','media="all"');
?>
	.pagina {
	    margin-top: 34.5pt;
	    margin-left: 18pt;
	}
	
	.paginas {
	    margin-top: 34.5pt;
	    margin-left: 18pt;
	}
	
  .etiqueta {
  	vertical-align:top;              
  } 	
<?
PaginaSEI::getInstance()->fecharStyleIE();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objTabelaLocalizadores;
function inicializar(){
		
	document.getElementById('txtLinha').focus();

  objTabelaLocalizadores = new infraTabelaDinamica('tblLocalizadores','hdnLocalizadores',false,true);
}

function OnSubmitForm() {
	return true;
}

function imprimirEtiquetasRI1129(){

	if (infraTrim(document.getElementById('txtLinha').value)=='') {
		alert('Informe o número da linha.');
		document.getElementById('txtLinha').focus();
		return false;
	}else{
		if(document.getElementById('txtLinha').value>3){
			alert ('Linha não pode ser maior que 3.');
			document.getElementById('txtLinha').focus();
			return false;
		}
	}
	
	
	var frm = document.getElementById('frmLocalizadorEtiquetas');
	
	var aWindow = window.open('', 'TableAddRowNewWindow',	'scrollbars=yes,menubar=no,resizable=yes,toolbar=no,width=800,height=600');

	var targetAnterior = frm.target;
  var actionAnterior = frm.action; 

	frm.target = 'TableAddRowNewWindow';
	frm.action='<?=$strLinkPdfEtiquetas?>';
	
	//alert(document.getElementById('hdnLocalizadores').value);
	
	frm.submit();
	
	frm.target = targetAnterior;
	frm.action = actionAnterior;
	
  return true;
}



function imprimirTabela(etiquetas){
 document.title = "";

  var div = document.getElementById('infraDivImpressao');
  document.getElementById('divInfraAreaGlobal').style.display='none';
  div.className = '';
  div.innerHTML = etiquetas;
  
  window.print();

  //chama restauração via setTimeout para sincronizar a caixa de impressao
  //no Firefox (mostrava a caixa depois que tinha restaurado)
 self.setTimeout('infraRestaurarImpressao()', 1000);
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmLocalizadorEtiquetas" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('localizador_etiquetas.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('15em');
?>
  <fieldset id="fldPosicionamento" class="infraFieldset">
    <legend class="infraLegend">Posicionamento</legend>
    <br />
    &nbsp;&nbsp;
	  <label id="lblLinha" for="txtLinha" accesskey="L" class="infraLabelObrigatorio" ><span class="infraTeclaAtalho">L</span>inha:</label>
	  &nbsp;
	  <input type="text" id="txtLinha" name="txtLinha" class="InfraText" value="1" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <br /> 	  
    <br />
	  <label id="lblAviso" for="" accesskey="" class="infraLabelOpcional" style="color:red;" >
	  AVISO: configurar no navegador a impressão de página sem cabeçalhos ou rodapés e com margens tamanho zero.
	  </label>
	  
	</fieldset>    
	
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaTabela();
?>
  
  <table width="60%" id="tblLocalizadores" name="tblLocalizadores" class="infraTable">
    <caption class="infraCaption"><?=PaginaSEI::getInstance()->gerarCaptionTabela("Localizadores para Impressão",0)?></caption>		
    <tr>
			<th style="display:none;">ID</th>
			<th class="infraTh" align="left">Etiqueta</th>
			<th class="infraTh" width="15%">Ações</th>
		</tr>
  </table>
	
  <input type="hidden" id="hdnLocalizadores" name="hdnLocalizadores" value="<?=$strLocalizadores;?>" />
<?
  PaginaSEI::getInstance()->fecharAreaTabela();  
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>