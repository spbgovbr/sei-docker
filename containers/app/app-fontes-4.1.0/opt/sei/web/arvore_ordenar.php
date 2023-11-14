<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('arvore_ordenar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
  

  $arrComandos = array();

  switch($_GET['acao']){
    case 'arvore_ordenar':
    	
      $strTitulo = 'Ordenar Árvore do Processo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
      
	    $arrRelProtocoloProtocolo = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnRelProtocoloProtocolo']);
	    
	    $arrObjRelProtocoloProtocoloDTO = array();
	    for($i=0;$i<count($arrRelProtocoloProtocolo);$i++){
	      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
	      $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo($arrRelProtocoloProtocolo[$i]);
	      $objRelProtocoloProtocoloDTO->setNumSequencia($i);
	      $arrObjRelProtocoloProtocoloDTO[] = $objRelProtocoloProtocoloDTO;
	    }
      $objProcedimentoDTO->setArrObjRelProtocoloProtocoloDTO($arrObjRelProtocoloProtocoloDTO);
	    
      if (isset($_POST['sbmSalvar'])) {
        try{
          
          $objProtocoloRN = new ProtocoloRN();
          $objProtocoloRN->alterarOrdem($objProcedimentoDTO);
          
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&atualizar_arvore=1'.$strParametros));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelRelProtocoloProtocolo = ProcedimentoINT::montarSelectArvoreOrdenacao($objProcedimentoDTO->getDblIdProcedimento());
  
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

#lblRelProtocoloProtocolo {position:absolute;left:0%;top:0%;width:90%;}
#selRelProtocoloProtocolo {position:absolute;left:0%;top:3.5%;width:90%;}

#imgRelProtocoloProtocoloAcima {position:absolute;left:91%;top:3.5%;}
#imgRelProtocoloProtocoloAbaixo {position:absolute;left:91%;top:9%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objLupaRelProtocoloProtocolo = null;

function inicializar(){

  objLupaRelProtocoloProtocolo = new infraLupaSelect('selRelProtocoloProtocolo','hdnRelProtocoloProtocolo', null, true);

  infraEfeitoTabelas();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmArvoreOrdenar" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('50em');
?>

  <label id="lblRelProtocoloProtocolo" for="selRelProtocoloProtocolo" accesskey="" class="infraLabelObrigatorio">Protocolos:</label>
  <select id="selRelProtocoloProtocolo" name="selRelProtocoloProtocolo" size="30" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  	<?=$strItensSelRelProtocoloProtocolo?>
  </select>
  <img id="imgRelProtocoloProtocoloAcima" onclick="objLupaRelProtocoloProtocolo.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Protocolo Selecionado" title="Mover Acima Protocolo Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgRelProtocoloProtocoloAbaixo" onclick="objLupaRelProtocoloProtocolo.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Protocolo Selecionado" title="Mover Abaixo Protocolo Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnRelProtocoloProtocolo" name="hdnRelProtocoloProtocolo" value="<?=$_POST['hdnRelProtocoloProtocolo']?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>