<?
  /*
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 17/12/2013 - criado por mkr@trf4.jus.br
  * 24/02/2014 - alterado por mga@trf4.jus.br
  */
 
  try {
    require_once dirname(__FILE__).'/SEI.php';

    //session_start();
    
    //SessaoSEI::getInstance(false);

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(false);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    if (ConfiguracaoSEI::getInstance()->getValor('PaginaSEI','Ouvidoria',false,true)!==true){
      die (SeiINT::$MSG_PAGINA_DESABILITADA);
    }

    SessaoSEIExterna::getInstance()->validarLink();

    PaginaSEIExterna::getInstance()->setTipoPagina(PaginaSEIExterna::$TIPO_PAGINA_SEM_MENU);

    
    //$arrComandos = array();

    switch ($_GET['acao']) {
      case 'ouvidoria':
        $strTitulo = 'Ouvidoria';

        CaptchaSEI::getInstance()->configurarCaptcha('Ouvidoria');

        $objSeiRN = new SeiRN();

        $strNumeroProcesso = null;

        if (isset($_POST['hdnInfraCaptcha']) && $_POST['hdnInfraCaptcha']=='1') {
          try{
            if (!CaptchaSEI::getInstance()->verificar()){
              PaginaSEIExterna::getInstance()->setStrMensagem('Desafio não foi resolvido.');
            }else{

              if (($_POST['txtNome'] == "") || ($_POST['txtEmail'] == "") || ($_POST['txaMensagem'] == "")) {
                $msg = 'Erro: Os campos obrigatórios não foram todos preenchidos.';
              } else {


                $cidade = $_POST['selCidade'];
                $estado = $_POST['selEstado'];
                if ($estado == 'OUTRO') {
                  $cidade = $_POST['txtCidade'];
                  $estado = '';
                }

                $objEntradaRegistrarOuvidoriaAPI = new EntradaRegistrarOuvidoriaAPI();
                $objEntradaRegistrarOuvidoriaAPI->setIdOrgao($_GET['id_orgao_acesso_externo']);
                $objEntradaRegistrarOuvidoriaAPI->setNome($_POST['txtNome']);
                $objEntradaRegistrarOuvidoriaAPI->setNomeSocial($_POST['txtNomeSocial']);
                $objEntradaRegistrarOuvidoriaAPI->setEmail($_POST['txtEmail']);
                $objEntradaRegistrarOuvidoriaAPI->setCpf($_POST['txtCpf']);
                $objEntradaRegistrarOuvidoriaAPI->setRg($_POST['txtRg']);
                $objEntradaRegistrarOuvidoriaAPI->setOrgaoExpedidor($_POST['txtOrgaoExpedidor']);
                $objEntradaRegistrarOuvidoriaAPI->setTelefone($_POST['txtTelefone']);
                $objEntradaRegistrarOuvidoriaAPI->setEstado($estado);
                $objEntradaRegistrarOuvidoriaAPI->setCidade($cidade);
                $objEntradaRegistrarOuvidoriaAPI->setIdTipoProcedimento($_POST['selTipo']);
                $objEntradaRegistrarOuvidoriaAPI->setProcessos($_POST['txtProcessos']);
                $objEntradaRegistrarOuvidoriaAPI->setSinRetorno($_POST['chkRetorno']!='N'?'S':$_POST['chkRetorno']);
                $objEntradaRegistrarOuvidoriaAPI->setMensagem($_POST['txaMensagem']);

                $objSeiRN = new SeiRN();
                $objProcedimentoResumidoAPI = $objSeiRN->registrarOuvidoria($objEntradaRegistrarOuvidoriaAPI);
                $strNumeroProcesso = $objProcedimentoResumidoAPI->getProcedimentoFormatado();
              }
            }
          }catch(Exception $e){
            PaginaSEIExterna::getInstance()->processarExcecao($e);
          }
        }

        if ($strNumeroProcesso!=null){

          $strDisplayMensagem = '';

          $msg = '';
          $msg .= '&nbsp;&nbsp;O seu contato foi recebido e registrado no Processo Administrativo ' . $strNumeroProcesso . '.' . "\n\n";
          $msg .= '&nbsp;&nbsp;Um comprovante foi enviado para o e-mail informado.';

        }else {


          //$strItensSelEstado = $objSeiRN->listarEstados($_POST['selEstado']);

          $objEntradaListarEstadosAPI = new EntradaListarEstadosAPI();
          $objEntradaListarEstadosAPI->setIdPais(PaisINT::buscarIdPaisBrasil());
          $arrObjEstadoAPI = $objSeiRN->listarEstados($objEntradaListarEstadosAPI);

          $strItensSelEstado .= '<option value="null" '.($_POST['selEstado']=='null'?'selected="selected"':'').'>&nbsp;</option>';
          $numIdEstado = null;
          if (is_array($arrObjEstadoAPI)) {
            foreach ($arrObjEstadoAPI as $objEstadoAPI) {
              if ($_POST['selEstado'] == $objEstadoAPI->getSigla()) {
                $numIdEstado = $objEstadoAPI->getIdEstado();
              }
              $strItensSelEstado .= '<option value="'.$objEstadoAPI->getSigla().'" '.($_POST['selEstado'] == $objEstadoAPI->getSigla() ? 'selected="selected"' : '').'>'.$objEstadoAPI->getSigla().'</option>';
            }
          }


          if ($_POST['selEstado'] != null && $_POST['selEstado'] != 'OUTRO') {

            $objEntradaListarCidadesAPI = new EntradaListarCidadesAPI();
            $objEntradaListarCidadesAPI->setIdPais(PaisINT::buscarIdPaisBrasil());
            $objEntradaListarCidadesAPI->setIdEstado($numIdEstado);
            $arrObjCidadeAPI = $objSeiRN->listarCidades($objEntradaListarCidadesAPI);

            $strItensSelCidade .= '<option value="null" '.($_POST['selCidade']=='null'?'selected="selected"':'').'>&nbsp;</option>';
            if (is_array($arrObjCidadeAPI)) {
              foreach ($arrObjCidadeAPI as $objCidadeAPI) {
                $strItensSelCidade .= '<option value="'.$objCidadeAPI->getNome().'" '.($_POST['selCidade'] == $objCidadeAPI->getNome() ? 'selected="selected"' : '').'>'.$objCidadeAPI->getNome().'</option>';
              }
            }
          }

          if ($_GET['tipo'] != '') {
            $_POST['selTipo'] = $_GET['tipo'];
          }

          $arrObjTipoProcedimentoAPI = $objSeiRN->listarTiposProcedimentoOuvidoria();
          $strSelTipo .= '<option value="null" '.($_POST['selTipo']=='null'?'selected="selected"':'').'>&nbsp;</option>';
          if (is_array($arrObjTipoProcedimentoAPI)){
            foreach($arrObjTipoProcedimentoAPI as $objTipoProcedimentoAPI){
              $strSelTipo .= '<option value="'.$objTipoProcedimentoAPI->getIdTipoProcedimento().'" '.($_POST['selTipo']==$objTipoProcedimentoAPI->getIdTipoProcedimento() ? 'selected="selected"' : '').'>'.$objTipoProcedimentoAPI->getNome().'</option>';
            }
          }

          $strDisplayMensagem = 'display:none';

          if (isset($_POST['hdnFlagFormulario'])) {
            if ($msg != '') {
              $strDisplayMensagem = '';
            } else {
              $strDisplayMensagem = 'display:none;';
            }
          }
        }

        break;

      default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numMaxMsg = $objInfraParametro->getValor('SEI_MAX_TAM_MENSAGEM_OUVIDORIA');

    $strSiglaOrgao = SessaoSEIExterna::getInstance()->getStrSiglaOrgaoUsuarioExterno();
    $strTextoFormulario = $objInfraParametro->getValor('SEI_MSG_FORMULARIO_OUVIDORIA_'.$strSiglaOrgao, false);
    if (trim($strTextoFormulario)=='') {
      $strTextoFormulario = $objInfraParametro->getValor('SEI_MSG_FORMULARIO_OUVIDORIA');
    }

  } catch(Exception $e) {
    PaginaSEIExterna::getInstance()->processarExcecao($e);
  }

  PaginaSEIExterna::getInstance()->montarDocType();
  PaginaSEIExterna::getInstance()->abrirHtml();
  PaginaSEIExterna::getInstance()->abrirHead();
  PaginaSEIExterna::getInstance()->montarMeta();
  PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema().' - '.$strTitulo);  
  PaginaSEIExterna::getInstance()->montarStyle();
  CaptchaSEI::getInstance()->montarStyle();
  PaginaSEIExterna::getInstance()->abrirStyle();
?>

#frmOuvidoria {padding-left:1em}

#lblNome{position:absolute;left:0;top:0%;width:20%;}
#txtNome{position:absolute;left:0;top:40%;width:60%;}

#lblNomeSocial{position:absolute;left:0;top:0%;}
#txtNomeSocial{position:absolute;left:0;top:40%;width:60%;}

#lblEmail{position:absolute;left:0;top:0%;}
#txtEmail{position:absolute;left:0;top:40%;width:35%;}

#lblTelefone{position:absolute;left:37%;top:0%;width:23%;}
#txtTelefone{position:absolute;left:37%;top:40%;width:23%;}

#divFormularioCPF{height:5em;}
#lblCpf{position:absolute;left:0;top:0%;}
#txtCpf{position:absolute;left:0;top:40%;width:21%;}

#divFormularioOpcao{height:2.5em;}
#ancOpcao{position:absolute;left:0;top:0%;font-size:1.2em;}
#ancOpcao:focus {outline:1px dotted black;}

#divFormularioRG{height:5em;;display:none;}
#lblRg{position:absolute;left:0;top:0%;}
#txtRg{position:absolute;left:0;top:40%;width:21%;}

#lblOrgaoExpedidor{position:absolute;top:0%;left:23%;width:20%;}
#txtOrgaoExpedidor{position:absolute;top:40%;left:23%;width:12%;}

#lblEstado{position:absolute;left:0;top:0%;width:7%;}
#selEstado{position:absolute;left:0;top:40%;width:7%;}

#lblCidade{position:absolute;left:8%;top:0%;}
#selCidade{position:absolute;left:8%;top:40%;width:27%;}
#txtCidade{position:absolute;left:8%;top:40%;width:27%;}

#lblTipo{position:absolute;left:0;top:0%;width:35%;}
#selTipo{position:absolute;left:0;top:40%;width:35%;}

#lblProcessos{position:absolute;left:37%;top:0%;width:23%;}
#txtProcessos{position:absolute;left:37%;top:40%;width:23%;}

#lblMensagem{position:absolute;left:0;top:0%;width:30%;}
#txaMensagem{position:absolute;left:0;top:15%;width:60%;}

#divRestantes {height:3em;width:60%;}
#spnCaracteresRestantes{font-size:1.1em;float:right;}

#divSinRetorno{position:absolute;left:0;top:0%;}

#divInfraCaptcha {margin:.5em 0;}

#sbmEnviar{position:absolute;left:0;top:0%;width:10em;}

#lblCamposObrigatorios{position:absolute;left:0;top:60%;}

#divMensagem {height:35em;padding:1em;font-size:1.2em;<?=$strDisplayMensagem?>}



<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
CaptchaSEI::getInstance()->montarJavascript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>
function inicializar(){

  <? if ($msg=='') {?>

    if(document.getElementById('selEstado').value=="OUTRO") {
      document.getElementById('selCidade').style.display="none";
      document.getElementById('txtCidade').style.display="";
      document.getElementById('lblCidade').innerHTML="Cidade:";
    } else {
      document.getElementById('selCidade').style.display="";
      document.getElementById('txtCidade').style.display="none";

    }

    if ('<?=$_POST['hdnFlagFormulario']?>'=='2'){
      document.getElementById('divFormularioCPF').style.display	= "none";
      document.getElementById('divFormularioRG').style.display	= "block";
      document.getElementById('ancOpcao').innerHTML = 'Se você prefere informar o CPF clique aqui.';
    }

    if (infraSelectSelecionado('selEstado') && document.getElementById('selEstado').value!='OUTRO' && !infraSelectSelecionado('selCidade')){
      document.getElementById('selCidade').focus();
    }else{
      document.getElementById('txtNome').focus();
    }

  <? } ?>

}

function mostrarRGCPF(){
	
	if (document.getElementById('ancOpcao').innerHTML=='Se você não tem CPF clique aqui.'){
    document.getElementById('hdnFlagFormulario').value = '2';
		document.getElementById('txtCpf').value = '';
		document.getElementById('divFormularioCPF').style.display	= "none";
		document.getElementById('divFormularioRG').style.display	= "block";
		document.getElementById('ancOpcao').innerHTML = 'Se você prefere informar o CPF clique aqui.';
		document.getElementById('txtRg').focus();
	}else{
    document.getElementById('hdnFlagFormulario').value = '1';
		document.getElementById('txtRg').value = '';
		document.getElementById('divFormularioCPF').style.display	= "block";
		document.getElementById('divFormularioRG').style.display	= "none";
		document.getElementById('ancOpcao').innerHTML = 'Se você não tem CPF clique aqui.';
		document.getElementById('txtCpf').focus();
	}
}

function validarFormulario() {
  
  if (document.getElementById('txtNome').value == '') {
    alert('Nome não informado.');
    document.getElementById('txtNome').focus();
    return false;
  } 
  
  if (document.getElementById('txtEmail').value=='') {
		alert('E-mail não informado.');
		document.getElementById('txtEmail').focus();
		return false;
  } 
  
  if (!infraValidarEmail(document.getElementById('txtEmail').value)) {
    alert('E-mail inválido.');
    document.getElementById('txtEmail').focus();
    return false;
  } 
  
  if (document.getElementById('txtCpf').value == ''){
		if (document.getElementById('txtRg').value == '' || document.getElementById('txtOrgaoExpedidor').value == ''){
	  	alert('Pelo menos um dos campos CPF ou RG/Órgão Expedidor deve ser informado.');
	  	return false;
  	}  	  	 
  }else if (!infraValidarCpf(document.getElementById('txtCpf').value)){  			
  	alert('CPF Inválido!');
  	document.getElementById('txtCpf').focus();                        
    return false;                            
  } 
  
  if (document.getElementById('selEstado').value=='null'){    
    alert('Selecione um estado.');
    document.getElementById('selEstado').focus();
    return false;
  } else if (document.getElementById('selEstado').value!='OUTRO'){
    if (document.getElementById('selCidade').value=='null'){    
      alert('Selecione uma cidade.');
      document.getElementById('selCidade').focus();
      return false;
    }
  }
  
  if (document.getElementById('selTipo').value=='null'){    
    alert('Selecione o tipo da mensagem.');
    document.getElementById('selTipo').focus();
    return false;
  } 
  
  if (infraTrim(document.getElementById('txaMensagem').value) == '') {
    alert('Mensagem não informada.');
    document.getElementById('txaMensagem').focus();
    return false;
  }
  
  <? CaptchaSEI::getInstance()->validarOnSubmit('frmOuvidoria');?>
}

function limitador() {
  var campoObservacoes = document.getElementById('txaMensagem');
  var caracteresRestantes = document.getElementById('spnCaracteresRestantes');
  var limite = <?=$numMaxMsg?>;
  if (campoObservacoes.value.length > limite) {
    campoObservacoes.value = campoObservacoes.value.substring(0, limite);
  } else {
  	var numero = limite - campoObservacoes.value.length;
    caracteresRestantes.innerHTML = 'Caracteres restantes: '+numero;
  }
}
<?
  PaginaSEIExterna::getInstance()->fecharJavaScript();  
  PaginaSEIExterna::getInstance()->fecharHead();
    
  PaginaSEIExterna::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');   
    
  ?>	
  <div id="divMensagem">
    <br /><br /><?=nl2br($msg)?><br /><br />
  </div>
  
  <? if ($msg=='') {?>


	<form name="frmOuvidoria" id="frmOuvidoria" class="formulario" method="post" onsubmit="return validarFormulario();" action="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'')?>">

    <div class="formularioTexto"><?=$strTextoFormulario?></div>
    <?
    PaginaSEIExterna::getInstance()->abrirAreaDados('5em');
    ?>
      <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome Completo:*</label>
      <input type="text" id="txtNome" name="txtNome" class="infraText" maxlength="100" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtNome']);?>"/>
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    PaginaSEIExterna::getInstance()->abrirAreaDados('5em');
    ?>
      <label id="lblNomeSocial" for="txtNomeSocial" class="infraLabelOpcional">Nome Social (opcional, identidade de gênero - <a target="_blank" href="http://www.planalto.gov.br/ccivil_03/_ato2015-2018/2016/decreto/D8727.htm" style="font-size:1em">Decreto nº 8.727/2016</a>):</label>
      <input type="text" id="txtNomeSocial" name="txtNomeSocial" class="infraText" maxlength="100" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtNomeSocial']);?>"/>
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    PaginaSEIExterna::getInstance()->abrirAreaDados('5em');
    ?>
      <label id="lblEmail" for="txtEmail" class="infraLabelObrigatorio">E-mail:*</label>
      <input type="text" id="txtEmail" name="txtEmail" maxlength="100" class="infraText" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtEmail']);?>"/>

      <label id="lblTelefone" for="txtTelefone" class="infraLabelOpcional">DDD e Telefone:</label>
      <input type="text" id="txtTelefone" name="txtTelefone" class="infraText" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtTelefone']);?>" onkeypress="return infraMascaraTelefone(this,event)" />
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    ?>
    <div id="divFormularioCPF" class="infraAreaDados">
      <label id="lblCpf" for="txtCpf" class="infraLabelObrigatorio">CPF:*</label>
      <input type="text" id="txtCpf" name="txtCpf" class="infraText" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtCpf']);?>" maxlength="14" onkeypress="return infraMascara(this,event,'###.###.###-##')" />
    </div>
      
    <div id="divFormularioOpcao" class="infraAreaDados">
      <a id="ancOpcao" href="#" onclick="mostrarRGCPF();" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">Se você não tem CPF clique aqui.</a>
    </div>
  
    <div id="divFormularioRG" class="infraAreaDados">
        <label id="lblRg" for="txtRg" class="infraLabelObrigatorio">RG:*</label>
        <input type="text" id="txtRg" name="txtRg" class="infraText" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtRg']);?>" maxlength="15" size="15" onkeypress="return infraMascaraNumero(this,event);" />

        <label id="lblOrgaoExpedidor" for="txtOrgaoExpedidor" class="infraLabelObrigatorio">Órgão Expedidor:*</label>
        <input type="text" id="txtOrgaoExpedidor" name="txtOrgaoExpedidor" class="infraText" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtOrgaoExpedidor']);?>" maxlength="6" size="6" />
    </div>
    <?
    PaginaSEIExterna::getInstance()->abrirAreaDados('5em');
    ?>
      <label id="lblEstado" for="selEstado" class="infraLabelObrigatorio">Estado:*</label>
      <select id="selEstado" name="selEstado" onchange="this.form.submit()" class="infraSelect" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
        <?=$strItensSelEstado?>
        <option id="optOutrosEst" name="optOutros" value="OUTRO" <? if ($_POST['selEstado'] == 'OUTRO') echo 'selected="true"'; ?>>Outro</option>
      </select>

      <label id="lblCidade" for="selCidade" class="infraLabelObrigatorio">Cidade:*</label>
      <select id="selCidade" name="selCidade" class="infraSelect" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
      <?=$strItensSelCidade?>
      </select>

      <input type="text" id="txtCidade" name="txtCidade" maxlength="100" class="infraText" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=$_POST['txtCidade'];?>"/>
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    PaginaSEIExterna::getInstance()->abrirAreaDados('5em');
    ?>
      <label id="lblTipo" for="selTipo" class="infraLabelObrigatorio">Tipo:*</label>
      <select id="selTipo" name="selTipo" class="infraSelect" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
      <?=$strSelTipo;?>
      </select>

      <label id="lblProcessos" for="txtProcessos" class="infraLabelOpcional">Processos Relacionados (se houver):</label>
      <input type="text" id="txtProcessos" name="txtProcessos" maxlength="100" class="infraText" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtProcessos']);?>"/>
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    PaginaSEIExterna::getInstance()->abrirAreaDados('14em');
    ?>
      <label id="lblMensagem" for="txaMensagem" class="infraLabelObrigatorio">Mensagem:*</label>
      <textarea id="txaMensagem" name="txaMensagem" class="infraTextarea" rows="6" onkeypress="return infraLimitarTexto(this,event,<?=$numMaxMsg?>);" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" onblur="limitador();" onkeyup="limitador();" onkeydown="limitador();"><?=PaginaSEIExterna::tratarHTML($_POST['txaMensagem']);?></textarea>
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    ?>
    <div id="divRestantes" class="infraAreaDados">
      <span id="spnCaracteresRestantes">Caracteres restantes: <?=$numMaxMsg?></span>
    </div>
    <?
    PaginaSEIExterna::getInstance()->abrirAreaDados('3em');
    ?>
      <div id="divSinRetorno" class="infraDivCheckbox">
        <input type="checkbox" id="chkRetorno" name="chkRetorno" class="infraCheckbox" value="N" <?=($_POST['chkRetorno']?'checked="checked"':'')?> tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
        <label id="lblRetorno" for="chkRetorno" class="infraLabelCheckbox">Não desejo receber retorno</label>
      </div>
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    CaptchaSEI::getInstance()->montarHtml(PaginaSEIExterna::getInstance()->getProxTabDados());
    PaginaSEIExterna::getInstance()->abrirAreaDados('5em');
    ?>
      <input id="sbmEnviar" name="sbmEnviar" type="submit" class="infraButton" title="Enviar" value="Enviar" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>"/>
    	<label id="lblCamposObrigatorios" for="selTipo" class="infraLabelObrigatorio">* Campos Obrigatórios</label>
    <?
    PaginaSEIExterna::getInstance()->fecharAreaDados();
    ?>

		 <input type="hidden" id="hdnFlagFormulario" name="hdnFlagFormulario" class="infraText" value="<?=$_POST['hdnFlagFormulario']?>" />
		</form>
<?  }   
  PaginaSEIExterna::getInstance()->montarAreaDebug();
  PaginaSEIExterna::getInstance()->fecharBody();
  PaginaSEIExterna::getInstance()->fecharHtml();
?>