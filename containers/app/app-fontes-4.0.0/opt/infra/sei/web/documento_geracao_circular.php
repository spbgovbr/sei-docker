<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2015 - criado por mga
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
  InfraDebug::getInstance()->setBolDebugInfra(false);
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

  if (isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }

  $arrComandos = array();
  $strBotaoNovo = '';
  $bolGeracaoBlocoOK = false;

  switch($_GET['acao']){
    case 'documento_gerar_circular':
      $strTitulo = 'Gerar Circular';
      $arrComandos[] = '<button type="submit" accesskey="G" id="sbmGerar" name="sbmGerar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">G</span>erar</button>';

      if (SessaoSEI::getInstance()->verificarPermissao('bloco_assinatura_cadastrar')){
        $strBotaoNovo = '<button type="button" accesskey="N" id="btnNovoAssinatura" value="Novo" onclick="novoBloco()" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
      }

      /*
      $arrIdDestinatarios = null;
      if ($_GET['acao_origem']=='arvore_visualizar') {

        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdContato();
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
        $objParticipanteDTO->setDblIdProtocolo($_GET['id_documento']);

        $objParticipanteRN = new ParticipanteRN();
        $arrIdDestinatarios = InfraArray::converterArrInfraDTO($objParticipanteRN->listarRN0189($objParticipanteDTO),'IdContato');
      }else{
        $arrIdDestinatarios = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDestinatarios']);
      }
      */

      $arrIdDestinatarios = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDestinatarios']);

      $objDocumentoRN = new DocumentoRN();

      $objDocumentoCircularDTO = new DocumentoCircularDTO();
      $objDocumentoCircularDTO->setDblIdProcedimento($_GET['id_procedimento']);
      $objDocumentoCircularDTO->setDblIdDocumento($_GET['id_documento']);
      $objDocumentoCircularDTO->setArrNumIdDestinatario($arrIdDestinatarios);
      $objDocumentoCircularDTO->setNumIdBloco($_POST['selBloco']);

      if (isset($_POST['sbmGerar'])) {
        try{

          $arrRet = $objDocumentoRN->gerarDocumentoCircular($objDocumentoCircularDTO);

          //PaginaSEI::getInstance()->setStrMensagem('Documentos gerados com sucesso.');

          if (!InfraString::isBolVazia($objDocumentoCircularDTO->getNumIdBloco())){

            $arrAncora = array();
            foreach($arrRet as $item){
              $arrAncora[] = $item->getDblIdDocumento().'-'.$objDocumentoCircularDTO->getNumIdBloco();
            }

            $strLinkBlocosAssinatura = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_listar&acao_origem='.$_GET['acao'].'&id_bloco='.$objDocumentoCircularDTO->getNumIdBloco().PaginaSEI::montarAncora($arrAncora));
            $bolGeracaoBlocoOK = true;
          }else{
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&atualizar_arvore=1'.$strParametros));
            die;
          }

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objDocumentoDTO = new DocumentoDTO();
  $objDocumentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);

  $arrObjDocumentoCircularDTO = $objDocumentoRN->listarDocumentoCircular($objDocumentoDTO);

  $numRegistros = count($arrObjDocumentoCircularDTO);

  if ($numRegistros > 0){

    $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
    $bolAcaoDocumentoEmailCircular = SessaoSEI::getInstance()->verificarPermissao('documento_email_circular');

    if ($bolAcaoDocumentoEmailCircular){
      $arrComandos[] = '<button type="button" name="btnEmailCircular" id="btnEmailCircular" onclick="enviarEmailCircular();" value="Enviar E-mail" class="infraButton">Enviar E-mail</button>';
    }

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Documentos Circulares.';
    $strCaptionTabela = 'Documentos Circulares';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" >Destinatário</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Email</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $n = 0;
    foreach($arrObjDocumentoCircularDTO as $objDocumentoCircularDTO){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $arrObjParticipanteDTO = $objDocumentoCircularDTO->getArrObjParticipanteDTO();
      $numDestinatarios = InfraArray::contar($arrObjParticipanteDTO);

      $strResultado .= '<td valign="top" align="center" valign="top">';

      $bolDestinatarioSemEmail = false;
      foreach($arrObjParticipanteDTO as $objParticipanteDTO){
        if (InfraString::isBolVazia($objParticipanteDTO->getStrEmailContato())){
          $bolDestinatarioSemEmail = true;
        }
      }

      if ($numDestinatarios==0 || $bolDestinatarioSemEmail || $objDocumentoCircularDTO->getStrSinAssinado()=='N'){
        $strResultado .= '&nbsp;';
      }else{
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++,$objDocumentoCircularDTO->getDblIdDocumento(),$objDocumentoCircularDTO->getStrProtocoloDocumentoFormatado());
      }

      $strResultado .= '</td>';

      $strResultado .= '<td align="center" valign="top">';

      if ($bolAcaoDocumentoVisualizar){
        $strResultado .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objDocumentoCircularDTO->getDblIdDocumento()) .'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="protocoloNormal" title="'.PaginaSEI::tratarHTML($objDocumentoCircularDTO->getStrNomeSerie().' '.$objDocumentoCircularDTO->getStrNumero()).'">'.PaginaSEI::tratarHTML($objDocumentoCircularDTO->getStrProtocoloDocumentoFormatado()).'</a>';
      }else{
        $strResultado .= '<span class="protocoloNormal">'.PaginaSEI::tratarHTML($objDocumentoCircularDTO->getStrProtocoloDocumentoFormatado()).'</span>';
      }

      $strResultado .= '</td>';

      $strDestinatarios = '';
      foreach($arrObjParticipanteDTO as $objParticipanteDTO){

        if ($strDestinatarios!=''){
          $strDestinatarios .= ',<br />';
        }

        $strDestinatarios .= '<b>'.$objParticipanteDTO->getStrNomeContato().'</b>';

        if ($objParticipanteDTO->getStrEmailContato()!=''){
          $strDestinatarios .= ' ('.$objParticipanteDTO->getStrEmailContato().')';
        }
      }

      $strResultado .= '<td align="left" valign="top">'.$strDestinatarios.'</td>'."\n";


      $arrObjDocumentoDTOEmail = $objDocumentoCircularDTO->getArrObjDocumentoDTOEmail();
      $numEmail = InfraArray::contar($arrObjDocumentoDTOEmail);

      $strResultado .= '<td align="center" valign="top">';


      if ($numEmail) {
        for($i=0;$i<$numEmail;$i++){

          if ($i > 0){
            $strResultado .= '<br />';
          }

          if ($bolAcaoDocumentoVisualizar) {
            $strResultado .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento=' . $arrObjDocumentoDTOEmail[$i]->getDblIdDocumento()) . '" target="_blank" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="protocoloNormal" title="' . PaginaSEI::tratarHTML($arrObjDocumentoDTOEmail[$i]->getStrNomeSerie()) . '">' . PaginaSEI::tratarHTML($arrObjDocumentoDTOEmail[$i]->getStrProtocoloDocumentoFormatado()) . '</a>';
          } else {
            $strResultado .= '<span class="protocoloNormal">' . PaginaSEI::tratarHTML($arrObjDocumentoDTOEmail[$i]->getStrProtocoloDocumentoFormatado()) . '</span>';
          }
        }
      }else{
        $strResultado .= '&nbsp;';
      }


      $strResultado .= '</td>';

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }


  $strItensSelDestinatario = ContatoINT::montarSelectDestinatarios($arrIdDestinatarios);
  $strLinkDestinatariosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaDestinatarios');

  $strLinkAjaxContatos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225');


  $strLinkAjaxCadastroAutomatico = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_cadastro_contexto_temporario');
  $strLinkNovoBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&arvore='.$_GET['arvore']);
  $strLinkAjaxBloco = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=bloco_assinatura_montar_select');
  $strItensSelBloco = BlocoINT::montarSelectAssinatura('null','&nbsp;',$_POST['selBloco']);
  $strLinkAlterarContato = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_alterar&acao_origem='.$_GET['acao']);
  $strLinkEmailCircular = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_email_circular&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&arvore=1');

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
#divDestinatarios {height:20em}
#lblDestinatarios {position:absolute;left:0%;top:0%;}
#txtDestinatario {position:absolute;left:0%;top:9%;width:60%;}
#selDestinatarios {position:absolute;left:0%;top:20%;width:90%;}
#divOpcoesDestinatarios {position:absolute;left:91%;top:20%;}

#divBloco {height:5em}
#lblBloco {position:absolute;left:0%;top:0%;}
#selBloco {position:absolute;left:0%;top:40%;width:60%;}
#btnNovoAssinatura {position:absolute;left:61%;top:40%;width:10%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

var objLupaDestinatarios = null;
var objContatoCadastroAutomatico = null;
var objAutoCompletarDestinatarioRI1226 = null;
var objAjaxBloco = null;

function inicializar(){

  <?if ($bolGeracaoBlocoOK){?>
  parent.parent.document.location.href = '<?=$strLinkBlocosAssinatura?>';
  return;
  <?}?>

  parent.parent.infraOcultarAviso();

  objAutoCompletarDestinatarioRI1226 = new infraAjaxAutoCompletar('hdnIdDestinatario','txtDestinatario','<?=$strLinkAjaxContatos?>');
  objAutoCompletarDestinatarioRI1226.limparCampo = false;
  objAutoCompletarDestinatarioRI1226.prepararExecucao = function(){
    return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtDestinatario').value);
  };
  objAutoCompletarDestinatarioRI1226.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaDestinatarios.adicionar(id,descricao,document.getElementById('txtDestinatario'));
    }
  };

  infraAdicionarEvento(document.getElementById('txtDestinatario'),'keyup',tratarEnterDestinatario);

  objLupaDestinatarios = new infraLupaSelect('selDestinatarios','hdnDestinatarios','<?=$strLinkDestinatariosSelecao?>');


  document.getElementById('selDestinatarios').ondblclick = function(e){
    objLupaDestinatarios.alterar();
  };

  objLupaDestinatarios.processarAlteracao = function (pos, texto, valor){
    seiAlterarContato(valor, 'selDestinatarios', 'frmDocumentoGeracaoCircular','<?=$strLinkAlterarContato?>');
  }

  objContatoCadastroAutomatico = new infraAjaxComplementar(null,'<?=$strLinkAjaxCadastroAutomatico?>');
  objContatoCadastroAutomatico.prepararExecucao = function(){
    return 'nome='+encodeURIComponent(document.getElementById('txtDestinatario').value);
  };

  objContatoCadastroAutomatico.processarResultado = function(arr){
    if (arr!=null){
      objAutoCompletarDestinatarioRI1226.processarResultado(arr['IdContato'], document.getElementById('txtDestinatario').value, null);
    }
  };

  objAjaxBloco = new infraAjaxMontarSelect('selBloco','<?=$strLinkAjaxBloco?>');
  objAjaxBloco.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','Todos',document.getElementById('hdnIdBloco').value);
  }

  document.getElementById('txtDestinatario').focus();

  infraEfeitoTabelas();
}

function OnSubmitForm() {

  if (infraTrim(document.getElementById('hdnDestinatarios').value)==''){
    alert('Nenhum destinatário informado.');
    document.getElementById('selDestinatarios').focus();
    return false;
  }

  parent.parent.infraExibirAviso(false);

  return true;
}

function novoBloco(){
  parent.infraAbrirJanelaModal('<?=$strLinkNovoBloco?>',700,450);
}

function atualizarBlocos(idBloco){
  document.getElementById('hdnIdBloco').value = idBloco;
  objAjaxBloco.executar();
}

function tratarEnterDestinatario(ev){
  if (infraGetCodigoTecla(ev) == 13 && document.getElementById('hdnIdDestinatario').value=='' && infraTrim(document.getElementById('txtDestinatario').value)!=''){
    if (confirm('Nome inexistente. Deseja incluir?')){
      objContatoCadastroAutomatico.executar();
    }
  }
}

function enviarEmailCircular(){

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }

  infraAbrirJanela('<?=$strLinkEmailCircular?>','janelaEmailCircular',800,500,'location=0,status=1,resizable=1,scrollbars=1');

  var frm = document.getElementById('frmDocumentoGeracaoCircular');
  var actionAnterior = frm.action;
  frm.target = 'janelaEmailCircular';
  frm.action='<?=$strLinkEmailCircular?>';
  frm.submit();
  frm.action = actionAnterior;
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmDocumentoGeracaoCircular" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>

  <div id="divDestinatarios" class="infraAreaDados">
    <label id="lblDestinatarios" for="txtDestinatario" class="infraLabelOpcional">Destinatários:</label>
    <input type="text" id="txtDestinatario" name="txtDestinatario" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnIdDestinatario" name="hdnIdDestinatario" class="infraText" value="" />
    <select id="selDestinatarios" name="selDestinatarios" class="infraSelect" size="10" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
      <?=$strItensSelDestinatario?>
    </select>
    <div id="divOpcoesDestinatarios">
      <img id="imgPesquisarDestinatarios" onclick="objLupaDestinatarios.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Contatos para Destinatários" title="Selecionar Contatos para Destinatários" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <img id="imgAlterarDestinatario" onclick="objLupaDestinatarios.alterar();" src="<?=PaginaSEI::getInstance()->getIconeAlterar()?>" alt="Consultar/Alterar Dados do Destinatário Selecionado" title="Consultar/Alterar Dados do Destinatário Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <img id="imgRemoverDestinatarios" onclick="objLupaDestinatarios.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Destinatários Selecionados" title="Remover Destinatários Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    </div>
  </div>

  <div id="divBloco" class="infraAreaDados">
    <label id="lblBloco" for="selBloco" accesskey="" class="infraLabelOpcional">Bloco de Assinatura:</label>
    <select id="selBloco" name="selBloco" class="infraSelect" onchange="this.form.submit();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelBloco?>
    </select>
    <?=$strBotaoNovo?>
  </div>

  <input type="hidden" id="hdnDestinatarios" name="hdnDestinatarios" value="<?=PaginaSEI::tratarHTML($_POST['hdnDestinatarios'])?>" />
  <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="" />

  <input type="hidden" id="hdnContatoObject" name="hdnContatoObject" value="" />
  <input type="hidden" id="hdnContatoIdentificador" name="hdnContatoIdentificador" value="" />
  <br />
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>