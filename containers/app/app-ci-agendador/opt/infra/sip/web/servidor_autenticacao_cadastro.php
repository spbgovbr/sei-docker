<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/06/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->verificarSelecao('servidor_autenticacao_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'servidor_autenticacao_cadastrar':
      $strTitulo = 'Novo Servidor de Autenticação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarServidorAutenticacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao(null);
      $objServidorAutenticacaoDTO->setStrNome($_POST['txtNome']);
      $objServidorAutenticacaoDTO->setStrStaTipo($_POST['selStaTipo']);
      $objServidorAutenticacaoDTO->setStrEndereco($_POST['txtEndereco']);
      $objServidorAutenticacaoDTO->setNumPorta($_POST['txtPorta']);
      $objServidorAutenticacaoDTO->setStrSufixo($_POST['txtSufixo']);
      $objServidorAutenticacaoDTO->setStrUsuarioPesquisa($_POST['txtUsuarioPesquisa']);
      $objServidorAutenticacaoDTO->setStrSenhaPesquisa($_POST['pwdSenhaPesquisa']);
      $objServidorAutenticacaoDTO->setStrContextoPesquisa($_POST['txtContextoPesquisa']);
      $objServidorAutenticacaoDTO->setStrAtributoFiltroPesquisa($_POST['txtAtributoFiltroPesquisa']);
      $objServidorAutenticacaoDTO->setStrAtributoRetornoPesquisa($_POST['txtAtributoRetornoPesquisa']);
      $objServidorAutenticacaoDTO->setNumVersao($_POST['selVersao']);

      if (isset($_POST['sbmCadastrarServidorAutenticacao'])) {
        try{
          $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
          $objServidorAutenticacaoDTO = $objServidorAutenticacaoRN->cadastrar($objServidorAutenticacaoDTO);
          PaginaSip::getInstance()->adicionarMensagem('Servidor de Autenticação "'.$objServidorAutenticacaoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_servidor_autenticacao='.$objServidorAutenticacaoDTO->getNumIdServidorAutenticacao().PaginaSip::getInstance()->montarAncora($objServidorAutenticacaoDTO->getNumIdServidorAutenticacao())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'servidor_autenticacao_alterar':
      $strTitulo = 'Alterar Servidor de Autenticação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarServidorAutenticacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_servidor_autenticacao'])){
        $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao($_GET['id_servidor_autenticacao']);
        $objServidorAutenticacaoDTO->retTodos();
        $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
        $objServidorAutenticacaoDTO = $objServidorAutenticacaoRN->consultar($objServidorAutenticacaoDTO);
        if ($objServidorAutenticacaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao($_POST['hdnIdServidorAutenticacao']);
        $objServidorAutenticacaoDTO->setStrNome($_POST['txtNome']);
        $objServidorAutenticacaoDTO->setStrStaTipo($_POST['selStaTipo']);
        $objServidorAutenticacaoDTO->setStrEndereco($_POST['txtEndereco']);
        $objServidorAutenticacaoDTO->setNumPorta($_POST['txtPorta']);
        $objServidorAutenticacaoDTO->setStrSufixo($_POST['txtSufixo']);
        $objServidorAutenticacaoDTO->setStrUsuarioPesquisa($_POST['txtUsuarioPesquisa']);
        $objServidorAutenticacaoDTO->setStrSenhaPesquisa($_POST['pwdSenhaPesquisa']);
        $objServidorAutenticacaoDTO->setStrContextoPesquisa($_POST['txtContextoPesquisa']);
        $objServidorAutenticacaoDTO->setStrAtributoFiltroPesquisa($_POST['txtAtributoFiltroPesquisa']);
        $objServidorAutenticacaoDTO->setStrAtributoRetornoPesquisa($_POST['txtAtributoRetornoPesquisa']);
        $objServidorAutenticacaoDTO->setNumVersao($_POST['selVersao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objServidorAutenticacaoDTO->getNumIdServidorAutenticacao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarServidorAutenticacao'])) {
        try{
          $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
          $objServidorAutenticacaoRN->alterar($objServidorAutenticacaoDTO);
          PaginaSip::getInstance()->adicionarMensagem('Servidor de Autenticação "'.$objServidorAutenticacaoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objServidorAutenticacaoDTO->getNumIdServidorAutenticacao())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'servidor_autenticacao_consultar':
      $strTitulo = 'Consultar Servidor de Autenticação';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($_GET['id_servidor_autenticacao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao($_GET['id_servidor_autenticacao']);
      $objServidorAutenticacaoDTO->setBolExclusaoLogica(false);
      $objServidorAutenticacaoDTO->retTodos();
      $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
      $objServidorAutenticacaoDTO = $objServidorAutenticacaoRN->consultar($objServidorAutenticacaoDTO);
      if ($objServidorAutenticacaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelStaTipo = ServidorAutenticacaoINT::montarSelectStaTipo('null','&nbsp;',$objServidorAutenticacaoDTO->getStrStaTipo());
  $strItensSelVersao = ServidorAutenticacaoINT::montarSelectVersao('null','&nbsp;',$objServidorAutenticacaoDTO->getNumVersao());

  if (isset($_POST['sbmTestar'])){
  
    $objInfraException = new InfraException();
  
    $objInfraLDAP = new InfraLDAP();
    $objInfraLDAP->setBolDebug(true);
  
    $strDebug = '';
    
    try{
      
      $objInfraLDAP->pesquisaAvancada($objServidorAutenticacaoDTO->getStrStaTipo(),
                                      $objServidorAutenticacaoDTO->getStrEndereco(),
                                      $objServidorAutenticacaoDTO->getNumPorta(),
                                      $objServidorAutenticacaoDTO->getStrUsuarioPesquisa(),
                                      $objServidorAutenticacaoDTO->getStrSenhaPesquisa(),
                                      $objServidorAutenticacaoDTO->getStrContextoPesquisa(),
                                      $objServidorAutenticacaoDTO->getStrAtributoFiltroPesquisa(),
                                      $objServidorAutenticacaoDTO->getStrAtributoRetornoPesquisa(),
                                      (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrSufixo())?$_POST['txtUsuarioTeste']:$_POST['txtUsuarioTeste'].$objServidorAutenticacaoDTO->getStrSufixo()),
                                      $_POST['pwdSenhaTeste'],
                                      $objServidorAutenticacaoDTO->getNumVersao());
      
    }catch(Exception $e){
      $strDebug = nl2br('Erro realizando autenticação.'."\n\n".InfraException::inspecionar($e));
    }

    if ($strDebug==''){
      $objInfraException->lancarValidacao('Autenticação realizada com sucesso.');
    }else{
      $objInfraException->lancarValidacao("Erro realizando autenticação.\\n\\n".'Verifique detalhes mais abaixo nesta tela.');
    }
  }
    
  $strMostrarTeste = '';
  if ($_GET['acao']=='servidor_autenticacao_consultar'){
    $strMostrarTeste = 'visibility:hidden;';
  }
  
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

#lblNome {position:absolute;left:0%;top:0%;width:30%;}
#txtNome {position:absolute;left:0%;top:7%;width:30%;}

#lblStaTipo {position:absolute;left:33%;top:0%;width:30%;}
#selStaTipo {position:absolute;left:33%;top:7%;width:30%;}

#lblVersao {position:absolute;left:65%;top:0%;width:7%;}
#selVersao {position:absolute;left:65%;top:7%;width:7%;}

#lblEndereco {position:absolute;left:0%;top:18%;width:30%;}
#txtEndereco {position:absolute;left:0%;top:25%;width:30%;}

#lblPorta {position:absolute;left:33%;top:18%;width:6%;}
#txtPorta {position:absolute;left:33%;top:25%;width:6%;}

#lblSufixo {position:absolute;left:42%;top:18%;width:20%;}
#txtSufixo {position:absolute;left:42%;top:25%;width:20%;}

#lblUsuarioPesquisa {position:absolute;left:0%;top:36%;width:39%;}
#txtUsuarioPesquisa {position:absolute;left:0%;top:43%;width:39%;}

#lblSenhaPesquisa {position:absolute;left:42%;top:36%;width:20%;}
#pwdSenhaPesquisa {position:absolute;left:42%;top:43%;width:20%;}

#lblContextoPesquisa {position:absolute;left:0%;top:54%;width:39%;}
#txtContextoPesquisa {position:absolute;left:0%;top:61%;width:39%;}

#lblAtributoFiltroPesquisa {position:absolute;left:42%;top:54%;width:20%;}
#txtAtributoFiltroPesquisa {position:absolute;left:42%;top:61%;width:20%;}

#lblAtributoRetornoPesquisa {position:absolute;left:65%;top:54%;width:20%;}
#txtAtributoRetornoPesquisa {position:absolute;left:65%;top:61%;width:20%;}

#lblAjudaPesquisa {position:absolute;left:0%;top:72%;}

#lblUsuarioTeste {position:absolute;left:0%;top:82%;width:30%;color:red;<?=$strMostrarTeste?>}
#txtUsuarioTeste {position:absolute;left:0%;top:89%;width:30%;border:1px solid red;<?=$strMostrarTeste?>}

#lblSenhaTeste {position:absolute;left:33%;top:82%;width:30%;color:red;<?=$strMostrarTeste?>}
#pwdSenhaTeste {position:absolute;left:33%;top:89%;width:30%;border:1px solid red;<?=$strMostrarTeste?>}

#sbmTestar {position:absolute;left:65%;top:88.5%;width:15%;<?=$strMostrarTeste?>}

#spnDebug {font-size:1.4em;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='servidor_autenticacao_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='servidor_autenticacao_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }
  
  if (!infraSelectSelecionado('selStaTipo')) {
    alert('Selecione um Tipo.');
    document.getElementById('selStaTipo').focus();
    return false;
  }
  
  if (!infraSelectSelecionado('selVersao')) {
    alert('Selecione a Versão.');
    document.getElementById('selVersao').focus();
    return false;
  }
  
  if (infraTrim(document.getElementById('txtEndereco').value)=='') {
    alert('Informe o Endereço.');
    document.getElementById('txtEndereco').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtPorta').value)=='') {
    alert('Informe a Porta.');
    document.getElementById('txtPorta').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtContextoPesquisa').value)!='' || infraTrim(document.getElementById('txtAtributoFiltroPesquisa').value)!='' || infraTrim(document.getElementById('txtAtributoRetornoPesquisa').value)!=''){
  
    if (infraTrim(document.getElementById('txtContextoPesquisa').value)==''){
      alert('Informe o Contexto de Pesquisa.');
      document.getElementById('txtContextoPesquisa').focus();
      return false;    
    }
    
    if (infraTrim(document.getElementById('txtAtributoFiltroPesquisa').value)==''){
      alert('Informe o Atributo Filtro de Pesquisa.');
      document.getElementById('txtAtributoFiltroPesquisa').focus();
      return false;    
    }
    
    if (infraTrim(document.getElementById('txtAtributoRetornoPesquisa').value)=='') {
      alert('Informe o Atributo Retorno de Pesquisa.');
      document.getElementById('txtAtributoRetornoPesquisa').focus();
      return false;    
    }
  }
  
  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmServidorAutenticacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('27em');
?>

  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblStaTipo" for="selStaTipo" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
  <select id="selStaTipo" name="selStaTipo" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelStaTipo?>
  </select>
  
  <label id="lblVersao" for="selVersao" accesskey="" class="infraLabelObrigatorio">Versão:</label>
  <select id="selVersao" name="selVersao" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelVersao?>
  </select>
  
  <label id="lblEndereco" for="txtEndereco" accesskey="" class="infraLabelObrigatorio">Endereço:</label>
  <input type="text" id="txtEndereco" name="txtEndereco" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrEndereco());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblPorta" for="txtPorta" accesskey="" class="infraLabelObrigatorio">Porta:</label>
  <input type="text" id="txtPorta" name="txtPorta" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getNumPorta());?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblSufixo" for="txtSufixo" accesskey="" class="infraLabelOpcional">Sufixo:</label>
  <input type="text" id="txtSufixo" name="txtSufixo" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrSufixo());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblUsuarioPesquisa" for="txtUsuarioPesquisa" accesskey="" class="infraLabelOpcional">Usuário de Pesquisa:</label>
  <input type="text" id="txtUsuarioPesquisa" name="txtUsuarioPesquisa" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrUsuarioPesquisa());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblSenhaPesquisa" for="pwdSenhaPesquisa" accesskey="" class="infraLabelOpcional">Senha de Pesquisa:</label>
  <input type="password" id="pwdSenhaPesquisa" name="pwdSenhaPesquisa" autocomplete="off" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrSenhaPesquisa());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblContextoPesquisa" for="txtContextoPesquisa" accesskey="" class="infraLabelOpcional">* Contexto de Pesquisa:</label>
  <input type="text" id="txtContextoPesquisa" name="txtContextoPesquisa" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrContextoPesquisa());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblAtributoFiltroPesquisa" for="txtAtributoFiltroPesquisa" accesskey="" class="infraLabelOpcional">* Atributo Filtro:</label>
  <input type="text" id="txtAtributoFiltroPesquisa" name="txtAtributoFiltroPesquisa" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrAtributoFiltroPesquisa());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblAtributoRetornoPesquisa" for="txtAtributoRetornoPesquisa" accesskey="" class="infraLabelOpcional">* Atributo Retorno:</label>
  <input type="text" id="txtAtributoRetornoPesquisa" name="txtAtributoRetornoPesquisa" class="infraText" value="<?=PaginaSip::tratarHTML($objServidorAutenticacaoDTO->getStrAtributoRetornoPesquisa());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblAjudaPesquisa" class="infraLabelOpcional">* Estes campos serão obrigatórios caso um deles seja informado</label>
  
  <label id="lblUsuarioTeste" for="txtUsuarioTeste" accesskey="" class="infraLabelOpcional">Usuário de Teste:</label>
  <input type="text" id="txtUsuarioTeste" name="txtUsuarioTeste" class="infraText" value="<?=PaginaSip::tratarHTML($_POST['txtUsuarioTeste'])?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
  <label id="lblSenhaTeste" for="pwdSenhaTeste" accesskey="" class="infraLabelOpcional">Senha de Teste:</label>
	<input type="password" id="pwdSenhaTeste" name="pwdSenhaTeste" autocomplete="off" class="infraText" value="<?=PaginaSip::tratarHTML($_POST['pwdSenhaTeste'])?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
  <button type="submit" id="sbmTestar" name="sbmTestar" value="Testar" class="infraButton">Testar</button>

  <input type="hidden" id="hdnIdServidorAutenticacao" name="hdnIdServidorAutenticacao" value="<?=$objServidorAutenticacaoDTO->getNumIdServidorAutenticacao();?>" />
  <?
  PaginaSip::getInstance()->fecharAreaDados();
  ?>
  <br />
  <span id="spnDebug"><?=$strDebug?></span>
  <?    
  //PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>