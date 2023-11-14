<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
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

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
  	$strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  $objAtribuirDTO = new AtribuirDTO();

  $arrComandos = array();
  switch($_GET['acao']){
    
    case 'procedimento_atribuicao_cadastrar':    	
      $strTitulo = 'Atribuir Processo';

      $objAtividadeRN = new AtividadeRN();
      
      //vindo da tela de controle de processos
      if ($_GET['acao_origem']=='procedimento_controlar'){
        
        $objAtribuirDTO->setNumIdUsuarioAtribuicao(null);          
        
        $arrStrIdProtocolo = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDistinct(true);
        $objAtividadeDTO->retNumIdUsuarioAtribuicao();
        $objAtividadeDTO->setDblIdProtocolo($arrStrIdProtocolo,InfraDTO::$OPER_IN);
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setDthConclusao(null);
        
        $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);
        
        if (count($arrObjAtividadeDTO)==1){
          $objAtribuirDTO->setNumIdUsuarioAtribuicao($arrObjAtividadeDTO[0]->getNumIdUsuarioAtribuicao());        
        }
        
        
      }else if ($_GET['acao_origem']=='arvore_visualizar'){
        $objAtribuirDTO->setNumIdUsuarioAtribuicao(null);
        $arrStrIdProtocolo = array($_GET['id_procedimento']);        
      }else{
        $objAtribuirDTO->setNumIdUsuarioAtribuicao($_POST['selAtribuicao']);
        $arrStrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      //Escolheu uma ação nesta tela  
      if (isset($_POST['sbmSalvar'])){
        try{
        	
  	      $arrObjProtocoloDTO = array();     
  	      foreach($arrStrIdProtocolo as $dlbIdProtocolo){
  	      	$dto = new ProtocoloDTO();
  	        $dto->setDblIdProtocolo($dlbIdProtocolo);	        
  	        $arrObjProtocoloDTO[] = $dto;
  	      }
          
  	      $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);	      
          $objAtividadeRN->atribuirRN0985($objAtribuirDTO);            
          if (InfraArray::contar($arrStrIdProtocolo) == 1) {
          	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'&id_procedimento='.$arrStrIdProtocolo[0].'&atualizar_arvore=1'.PaginaSEI::montarAncora($arrStrIdProtocolo)));
          }
          else {          	
          	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrStrIdProtocolo)));
          }
          die;      		         
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }            
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';     
     	$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrStrIdProtocolo)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      break;

      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'procedimento_atribuicao_alterar':    	
      $strTitulo = 'Alterar Atribuição';

      $objAtividadeRN = new AtividadeRN();
      
      //vindo da tela de controle de processos
      if ($_GET['acao_origem']=='procedimento_atribuicao_listar'){
      	 $objAtribuirDTO->setNumIdUsuarioAtribuicao(null);
        $arrStrIdProtocolo = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else{
      	$objAtribuirDTO->setNumIdUsuarioAtribuicao($_POST['selAtribuicao']);
      	$arrStrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      //Escolheu uma ação nesta tela  
      if (isset($_POST['sbmSalvar'])){
        try{
        	
  	      $arrObjProtocoloDTO = array();     
  	      foreach($arrStrIdProtocolo as $dlbIdProtocolo){
  	      	$dto = new ProtocoloDTO();
  	        $dto->setDblIdProtocolo($dlbIdProtocolo);	        
  	        $arrObjProtocoloDTO[] = $dto;
  	      }
          
  	      $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);	      
          $objAtividadeRN->atribuirRN0985($objAtribuirDTO);            
         	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_atribuicao_listar&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($arrStrIdProtocolo)));
          die;
                		         
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }            
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';     
     	$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrStrIdProtocolo)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelAtribuicao = UsuarioINT::montarSelectPorUnidadeRI0811('null','&nbsp;',$objAtribuirDTO->getNumIdUsuarioAtribuicao(),SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $arrStrIdProtocolo = implode(',',$arrStrIdProtocolo);
  
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
#lblAtribuicao {position:absolute;left:0%;top:0%;width:50%;}
#selAtribuicao {position:absolute;left:0%;top:6%;width:50%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>


function inicializar(){
  
  document.getElementById('selAtribuicao').focus();
  

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm(){
  return true;
}
 
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAtividadeAtribuir" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>

 	<label id="lblAtribuicao" for="selAtribuicao" class="infraLabelOpcional">Atribuir para:</label>
  <select id="selAtribuicao" name="selAtribuicao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelAtribuicao?>
  </select>
  
  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=$arrStrIdProtocolo;?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();  
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>