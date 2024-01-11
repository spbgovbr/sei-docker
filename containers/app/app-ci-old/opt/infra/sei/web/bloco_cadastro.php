<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
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

  PaginaSEI::getInstance()->verificarSelecao('bloco_selecionar_processo');
  PaginaSEI::getInstance()->verificarSelecao('bloco_selecionar_documento');
  PaginaSEI::getInstance()->verificarSelecao('documento_gerar_multiplo');
  PaginaSEI::getInstance()->verificarSelecao('documento_gerar_circular');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento'])){
    $strParametros .= "&id_documento=".$_GET['id_documento'];
  }

  $objBlocoDTO = new BlocoDTO();

  $strDesabilitar = '';

  $arrComandos = array();
  $bolCadastroOk = false;

  switch($_GET['acao']){
    case 'bloco_assinatura_cadastrar':
    case 'bloco_interno_cadastrar':
    case 'bloco_reuniao_cadastrar':    	
      
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarBloco" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

    if (PaginaSEI::getInstance()->getAcaoRetorno()=='documento_gerar_multiplo' || PaginaSEI::getInstance()->getAcaoRetorno()=='documento_gerar_circular') {
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
    }else{
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . $strParametros) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
    }

      $objBlocoDTO->setNumIdBloco(null);
      
      if($_GET['acao']=='bloco_assinatura_cadastrar'){
      	$strTitulo = 'Novo Bloco de Assinatura';
      	$objBlocoDTO->setStrStaTipo(BlocoRN::$TB_ASSINATURA);
      }else if($_GET['acao']=='bloco_interno_cadastrar'){
      	$strTitulo = 'Novo Bloco Interno';
      	$objBlocoDTO->setStrStaTipo(BlocoRN::$TB_INTERNO);
      }else if($_GET['acao']=='bloco_reuniao_cadastrar'){
      	$strTitulo = 'Novo Bloco de Reunião';
      	$objBlocoDTO->setStrStaTipo(BlocoRN::$TB_REUNIAO);
      }

      $objBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objBlocoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objBlocoDTO->setStrDescricao($_POST['txtDescricao']);
      $objBlocoDTO->setStrIdxBloco(null);
      $objBlocoDTO->setStrStaEstado(BlocoRN::$TE_ABERTO);
      $objBlocoDTO->setNumIdGrupoBlocoRelBlocoUnidade($_POST['selGrupoBloco']);

      
      $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
      $arrObjRelBlocoUnidadeDTO = array();
      foreach($arrUnidades as $numIdUnidade){
       	$objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
       	$objRelBlocoUnidadeDTO->setNumIdBloco($numIdBloco);
       	$objRelBlocoUnidadeDTO->setNumIdUnidade($numIdUnidade);
      	$arrObjRelBlocoUnidadeDTO[] = $objRelBlocoUnidadeDTO;
      }
      $objBlocoDTO->setArrObjRelBlocoUnidadeDTO($arrObjRelBlocoUnidadeDTO);
      
            
      if (isset($_POST['sbmCadastrarBloco'])) {
        
        try{
          $objBlocoRN = new BlocoRN();
          $objBlocoDTO = $objBlocoRN->cadastrarRN1273($objBlocoDTO);

          if (PaginaSEI::getInstance()->getAcaoRetorno()=='documento_gerar_multiplo' || PaginaSEI::getInstance()->getAcaoRetorno()=='documento_gerar_circular'){
            $bolCadastroOk = true;
          }else {
            PaginaSEI::getInstance()->setStrMensagem('Bloco "' . $objBlocoDTO->getNumIdBloco() . '" cadastrado com sucesso.');
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_bloco=' . $objBlocoDTO->getNumIdBloco() . $strParametros . PaginaSEI::getInstance()->montarAncora($objBlocoDTO->getNumIdBloco())));
            die;
          }
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;
      
    case 'bloco_assinatura_alterar':
    case 'bloco_reuniao_alterar':  
    case 'bloco_interno_alterar':
      
      if($_GET['acao']=='bloco_assinatura_alterar'){
      	$strTitulo = 'Alterar Bloco de Assinatura';
      }else if($_GET['acao']=='bloco_interno_alterar'){
      	$strTitulo = 'Alterar Bloco Interno';
      }else if($_GET['acao']=='bloco_reuniao_alterar'){
      	$strTitulo = 'Alterar Bloco de Reunião';
      }
      
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarBloco" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_bloco'])){
        $objBlocoDTO->setNumIdBloco($_GET['id_bloco']);
        $objBlocoDTO->retTodos();
        $objBlocoDTO->retNumIdGrupoBlocoRelBlocoUnidade();
        $objBlocoDTO->setNumIdUnidadeRelBlocoUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objBlocoRN = new BlocoRN();
        $objBlocoDTO = $objBlocoRN->consultarRN1276($objBlocoDTO);
        if ($objBlocoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objBlocoDTO->setNumIdBloco($_POST['hdnIdBloco']);
        //$objBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        //$objBlocoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objBlocoDTO->setStrDescricao($_POST['txtDescricao']);
        $objBlocoDTO->setNumIdGrupoBlocoRelBlocoUnidade($_POST['selGrupoBloco']);

        $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
        $arrObjRelBlocoUnidadeDTO = array();
        foreach($arrUnidades as $numIdUnidade){
         	$objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
         	$objRelBlocoUnidadeDTO->setNumIdBloco($numIdBloco);
         	$objRelBlocoUnidadeDTO->setNumIdUnidade($numIdUnidade);
        	$arrObjRelBlocoUnidadeDTO[] = $objRelBlocoUnidadeDTO;
        }
        $objBlocoDTO->setArrObjRelBlocoUnidadeDTO($arrObjRelBlocoUnidadeDTO);
        
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objBlocoDTO->getNumIdBloco())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarBloco'])) {
        try{
          
          //die($objBlocoDTO->__toString());
          
          $objBlocoRN = new BlocoRN();
          $objBlocoRN->alterarRN1274($objBlocoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Bloco "'.$objBlocoDTO->getNumIdBloco().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objBlocoDTO->getNumIdBloco())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'bloco_consultar':
      $strTitulo = 'Consultar Bloco';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_bloco'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objBlocoDTO->setNumIdBloco($_GET['id_bloco']);
      $objBlocoDTO->setBolExclusaoLogica(false);
      $objBlocoDTO->retTodos();
      $objBlocoDTO->retNumIdGrupoBlocoRelBlocoUnidade();
      $objBlocoDTO->setNumIdUnidadeRelBlocoUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objBlocoRN = new BlocoRN();
      $objBlocoDTO = $objBlocoRN->consultarRN1276($objBlocoDTO);
      if ($objBlocoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelGrupoBloco = str_replace('&nbsp;','Nenhum', GrupoBlocoINT::montarSelectUnidade('null','&nbsp;', $objBlocoDTO->getNumIdGrupoBlocoRelBlocoUnidade()));
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_outras');     	 
  $strLinkUnidadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_outras&tipo_selecao=2&id_object=objLupaUnidades');
  $strItensSelUnidades = RelBlocoUnidadeINT::montarSelectIdUnidadesDisponibilizacao(null,null,null,$objBlocoDTO->getNumIdBloco());

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
#divIdentificacao {display:none;}
#lblIdBloco {position:absolute;left:0%;top:0%;width:25%;}
#txtIdBloco {position:absolute;left:0%;top:40%;width:25%;}

#lblDescricao {position:absolute;left:0%;top:0%;width:95%;}
#txtDescricao {position:absolute;left:0%;top:18%;width:95%;}

#lblGrupoBloco {position:absolute;left:0%;top:0%;width:50%;}
#selGrupoBloco {position:absolute;left:0%;top:40%;width:50%;}

#lblUnidades {position:absolute;left:0%;top:1%;width:70%;}
#txtUnidade {position:absolute;left:0%;top:12%;width:50%;}
#selUnidades {position:absolute;left:0%;top:26%;width:70%;}
#imgLupaUnidades {position:absolute;left:71%;top:26%;}
#imgExcluirUnidades {position:absolute;left:71%;top:39%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

function inicializar(){

  <?if ($bolCadastroOk){?>
    <? if ($_GET['arvore']=='1'){ ?>
      parent.document.getElementById('ifrVisualizacao').contentWindow.atualizarBlocos(<?=$objBlocoDTO->getNumIdBloco()?>);
    <? }else{ ?>
      window.parent.atualizarBlocos(<?=$objBlocoDTO->getNumIdBloco()?>);
    <? } ?>
      self.setTimeout('infraFecharJanelaModal()',500);
      return;
  <?}?>

  if ('<?=$_GET['acao']?>'=='bloco_consultar'){
    document.getElementById('divIdentificacao').style.display = 'block';
    infraDesabilitarCamposAreaDados();
    document.getElementById('btnFechar').focus();
    return;
  }else if ('<?=$_GET['acao']?>'=='bloco_assinatura_cadastrar' || '<?=$_GET['acao']?>'=='bloco_interno_cadastrar' ||  '<?=$_GET['acao']?>'=='bloco_reuniao_cadastrar'){
    document.getElementById('divIdentificacao').style.display = 'none';
    document.getElementById('txtDescricao').focus();
  }else{
    document.getElementById('divIdentificacao').style.display = 'block';
    document.getElementById('btnCancelar').focus();
  }

  if ('<?=$_GET['acao']?>'=='bloco_interno_cadastrar' ||  '<?=$_GET['acao']?>'=='bloco_interno_alterar'){
     document.getElementById('divUnidades').style.display = 'none';
  }
   
  objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadeSelecao?>');
  
  
  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
  //objAutoCompletarUnidade.maiusculas = true;
  //objAutoCompletarUnidade.mostrarAviso = true;
  //objAutoCompletarUnidade.tempoAviso = 1000;
  //objAutoCompletarUnidade.tamanhoMinimo = 3;
  objAutoCompletarUnidade.limparCampo = true;
  //objAutoCompletarUnidade.bolExecucaoAutomatica = false;

  objAutoCompletarUnidade.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidade').value;
  };
  
  objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaUnidades.adicionar(id,descricao,document.getElementById('txtUnidade'));
    }
  };

  infraEfeitoTabelas();
}

function validarCadastroRI1284() {
  return true;
}

function OnSubmitForm() {
  return validarCadastroRI1284();
}


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmBlocoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divIdentificacao" class="infraAreaDados" style="height:5em;">
    <label id="lblIdBloco" for="txtIdBloco" accesskey="" class="infraLabelObrigatorio">Número:</label>
    <input type="text" id="txtIdBloco" name="txtIdBloco" class="infraText" disabled="true" value="<?=$objBlocoDTO->getNumIdBloco();?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>
  
  <div id="divDescricao" class="infraAreaDados" style="height:10em;">
    <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <textarea id="txtDescricao" name="txtDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objBlocoDTO->getStrDescricao())?></textarea>
  </div>

  <div id="divGrupoBloco" class="infraAreaDados" style="height:5em;">
    <label id="lblGrupoBloco" for="selGrupoBloco" class="infraLabelOpcional">Grupo:</label>
    <select id="selGrupoBloco" name="selGrupoBloco" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelGrupoBloco?>
    </select>
  </div>

  <div id="divUnidades" class="infraAreaDados" style="height:18em;">
   	<label id="lblUnidades" for="selUnidades" class="infraLabelOpcional">Unidades para Disponibilização:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="" />
    <select id="selUnidades" name="selUnidades" size="5" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelUnidades?>
    </select>
    <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg" />
    <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" />
  </div>

  <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="<?=$objBlocoDTO->getNumIdBloco();?>" />
  <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades'];?>" />  
<?

  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>