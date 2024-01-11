<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 03/07/2019 - criado por cle@trf4.jus.br
  * Versão do Gerador de Código: 1.42.0
  */

  try {
    require_once dirname(__FILE__).'/../Infra.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoInfra::getInstance()->validarLink();

    PaginaInfra::getInstance()->verificarSelecao('infra_sessao_rest_selecionar');

    SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    $objInfraSessaoRestDTO = new InfraSessaoRestDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch($_GET['acao']) {
      case 'infra_sessao_rest_consultar':
        $strTitulo = 'Consultar Infra Sessão REST';
        $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($_GET['id_infra_sessao_rest'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

        $objInfraSessaoRestDTO->setStrIdInfraSessaoRest($_GET['id_infra_sessao_rest']);
        $objInfraSessaoRestDTO->setBolExclusaoLogica(false);
        $objInfraSessaoRestDTO->retTodos();

        $objInfraSessaoRestRN = new InfraSessaoRestRN();
        $objInfraSessaoRestDTO = $objInfraSessaoRestRN->consultar($objInfraSessaoRestDTO);

        if ($objInfraSessaoRestDTO === null) {
          throw new InfraException("Registro não encontrado.");
        }
        break;

      default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

  } catch(Exception $e) {
    PaginaInfra::getInstance()->processarExcecao($e);
  }

  PaginaInfra::getInstance()->montarDocType();
  PaginaInfra::getInstance()->abrirHtml();
  PaginaInfra::getInstance()->abrirHead();
  PaginaInfra::getInstance()->montarMeta();
  PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
  PaginaInfra::getInstance()->montarStyle();
  PaginaInfra::getInstance()->abrirStyle();
?>
<?if(0){?><style><?}?>
<?if(0){?></style><?}?>
<?
  PaginaInfra::getInstance()->fecharStyle();
  PaginaInfra::getInstance()->montarJavaScript();
  PaginaInfra::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_sessao_rest_cadastrar'){
    document.getElementById('txtIdUsuario').focus();
  } else if ('<?=$_GET['acao']?>'=='infra_sessao_rest_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}
<?if(0){?></script><?}?>
<?
  PaginaInfra::getInstance()->fecharJavaScript();
  PaginaInfra::getInstance()->fecharHead();
  PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraSessaoRestCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
  PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaInfra::getInstance()->montarAreaValidacao();
  PaginaInfra::getInstance()->abrirAreaDados();
?>
  <label id="lblIdInfraSessaoRest" for="txtIdInfraSessaoRest" accesskey="u" class="infraLabelObrigatorio">Id da Ses<span class="infraTeclaAtalho">s</span>ão REST:</label>
  <br />
  <textarea id="txtIdInfraSessaoRest" cols="100" rows="5" name="txtIdInfraSessaoRest" class="infraText" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>"><?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getStrIdInfraSessaoRest());?></textarea>
  <br /><br />

  <label id="lblIdUsuario" for="txtIdUsuario" accesskey="u" class="infraLabelObrigatorio">Id do <span class="infraTeclaAtalho">U</span>suário no SIP:</label>
  <br />
  <input type="text" id="txtIdUsuario" size="10" name="txtIdUsuario" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getNumIdUsuario());?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblSiglaUsuario" for="txtSiglaUsuario" accesskey="o" class="infraLabelObrigatorio">Sigla d<span class="infraTeclaAtalho">o</span> Usuário:</label>
  <br />
  <input type="text" id="txtSiglaUsuario" size="10" name="txtSiglaUsuario" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getStrSiglaUsuario());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblIdOrgao" for="txtIdOrgao" accesskey="r" class="infraLabelObrigatorio">Id do Ó<span class="infraTeclaAtalho">r</span>gão:</label>
  <br />
  <input type="text" id="txtIdOrgao" size="10" name="txtIdOrgao" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getNumIdOrgao());?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblSiglaOrgao" for="txtSiglaOrgao" accesskey="i" class="infraLabelObrigatorio">S<span class="infraTeclaAtalho">i</span>gla do Órgão:</label>
  <br />
  <input type="text" id="txtSiglaOrgao" size="10" name="txtSiglaOrgao" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getStrSiglaOrgao());?>" onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblLogin" for="txtLogin" accesskey="l" class="infraLabelObrigatorio">Data do <span class="infraTeclaAtalho">L</span>ogin:</label>
  <br />
  <input type="text" id="txtLogin" size="20" name="txtLogin" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getDthLogin());?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblAcesso" for="txtAcesso" accesskey="m" class="infraLabelObrigatorio">Data do Últi<span class="infraTeclaAtalho">m</span>o Acesso:</label>
  <br />
  <input type="text" id="txtAcesso" size="20" name="txtAcesso" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getDthAcesso());?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblLogout" for="txtLogout" accesskey="t" class="infraLabelOpcional">Da<span class="infraTeclaAtalho">t</span>a do Logout:</label>
  <br />
  <input type="text" id="txtLogout" size="20" name="txtLogout" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getDthLogout());?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblUserAgent" for="txtUserAgent" accesskey="g" class="infraLabelObrigatorio">User A<span class="infraTeclaAtalho">g</span>ent:</label>
  <br />
  <textarea id="txtUserAgent" name="txtUserAgent" cols="100" rows="5" class="infraText" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>"><?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getStrUserAgent());?>"</textarea>
  <br /><br />

  <label id="lblHttpClientIp" for="txtHttpClientIp" accesskey="p" class="infraLabelOpcional">I<span class="infraTeclaAtalho">P</span> do Cliente:</label>
  <br />
  <input type="text" id="txtHttpClientIp" size="20" name="txtHttpClientIp" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getStrHttpClientIp());?>" onkeypress="return infraMascaraTexto(this,event,39);" maxlength="39" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblHttpXForwardedFor" for="txtHttpXForwardedFor" accesskey="x" class="infraLabelOpcional"><span class="infraTeclaAtalho">X</span>-Forwarded-For:</label>
  <br />
  <input type="text" id="txtHttpXForwardedFor" size="20" name="txtHttpXForwardedFor" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getStrHttpXForwardedFor());?>" onkeypress="return infraMascaraTexto(this,event,39);" maxlength="39" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <br /><br />

  <label id="lblRemoteAddr" for="txtRemoteAddr" accesskey="d" class="infraLabelOpcional">Remote A<span class="infraTeclaAtalho">d</span>dress:</label>
  <br />
  <input type="text" id="txtRemoteAddr" size="20" name="txtRemoteAddr" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSessaoRestDTO->getStrRemoteAddr());?>" onkeypress="return infraMascaraTexto(this,event,39);" maxlength="39" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
<?
  PaginaInfra::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdInfraSessaoRest" name="hdnIdInfraSessaoRest" value="<?=$objInfraSessaoRestDTO->getStrIdInfraSessaoRest();?>" />
<?
  //PaginaInfra::getInstance()->montarAreaDebug();
  PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
  PaginaInfra::getInstance()->fecharBody();
  PaginaInfra::getInstance()->fecharHtml();
