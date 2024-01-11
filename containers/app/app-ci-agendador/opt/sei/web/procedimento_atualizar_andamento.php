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
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcedimento'));

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  $objAtualizarAndamentoDTO = new AtualizarAndamentoDTO();

  $arrComandos = array();
  switch($_GET['acao']){
    
    case 'procedimento_atualizar_andamento':
      
      $strTitulo = 'Atualizar Andamento';

      //vindo da tela de controle de processos
      if ($_GET['acao_origem']=='procedimento_controlar' || $_GET['acao_origem']=='arvore_visualizar'){
        
      	if ($_GET['acao_origem']=='procedimento_controlar'){
          $arr = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
      	}else{
      		$arr = array($_GET['id_procedimento']);
      	}
      	
        $objAtividadeRN = new AtividadeRN();
        
        $arrStrIdProtocolo = implode(',',$arr);
        
        $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
        $objPesquisaPendenciaDTO->setDblIdProtocolo($arr);
        $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);
        
        $arrObjAtividadeDTO = array();
        foreach($arrObjProcedimentoDTO as $objProcedimentoDTO){
          $arrObjAtividadeDTO = array_merge($arrObjAtividadeDTO,$objProcedimentoDTO->getArrObjAtividadeDTO()); 
        }
        
        $arrStrIdAtividade = implode(',',InfraArray::converterArrInfraDTO($arrObjAtividadeDTO,'IdAtividade'));
	      
        $objAtualizarAndamentoDTO->setStrDescricao('');
        
      }else{
        $arrStrIdProtocolo = $_POST['hdnIdProtocolo'];
        $arrStrIdAtividade = $_POST['hdnIdAtividade'];
        $objAtualizarAndamentoDTO->setStrDescricao($_POST['txaDescricao']);
      }

      $objAtualizarAndamentoDTO->setArrObjProtocoloDTO(InfraArray::gerarArrInfraDTO('ProtocoloDTO','IdProtocolo',explode(',',$arrStrIdProtocolo)));
      $objAtualizarAndamentoDTO->setArrObjAtividadeDTO(InfraArray::gerarArrInfraDTO('AtividadeDTO','IdAtividade',explode(',',$arrStrIdAtividade)));
      
      //Escolheu uma ação nesta tela  
      if (isset($_POST['sbmSalvar'])){
        try{
          
  	      
          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->atualizarAndamento($objAtualizarAndamentoDTO);
  
          if (PaginaSEI::getInstance()->isBolArvore()){
            $strAcaoDestino = 'procedimento_consultar_historico';
          }else{
            $strAcaoDestino = PaginaSEI::getInstance()->getAcaoRetorno();
          }
          
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDestino.'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrStrIdProtocolo)));
          
          die;
          
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }      
      
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (PaginaSEI::getInstance()->getAcaoRetorno()=='procedimento_controlar') {
        $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrStrIdProtocolo)).'\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
      }
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


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
#lblDescricao {position:absolute;left:0%;top:0%;width:50%;}
#txaDescricao {position:absolute;left:0%;top:6%;width:90%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  
  document.getElementById('txaDescricao').focus();
   

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  
  if (infraTrim(document.getElementById('txaDescricao').value)==''){
    alert('Descrição não informada.');
    document.getElementById('txaDescricao').focus();
    return false;
  }

  return true;
}
 
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAtividadeListar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  
 	<label id="lblDescricao" for="txaDescricao" class="infraLabelObrigatorio">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'5':'6'?>" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objAtualizarAndamentoDTO->getStrDescricao());?></textarea>
  
  <input type="hidden" id="hdnIdAtividade" name="hdnIdAtividade" value="<?=$arrStrIdAtividade;?>" />
  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=$arrStrIdProtocolo;?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();  
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
//PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>