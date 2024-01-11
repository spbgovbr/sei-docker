<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/11/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('email_sistema_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'email_sistema_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjEmailSistemaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objEmailSistemaDTO = new EmailSistemaDTO();
          $objEmailSistemaDTO->setNumIdEmailSistema($arrStrIds[$i]);
          $arrObjEmailSistemaDTO[] = $objEmailSistemaDTO;
        }
        $objEmailSistemaRN = new EmailSistemaRN();
        $objEmailSistemaRN->excluir($arrObjEmailSistemaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

 
    case 'email_sistema_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjEmailSistemaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objEmailSistemaDTO = new EmailSistemaDTO();
          $objEmailSistemaDTO->setNumIdEmailSistema($arrStrIds[$i]);
          $arrObjEmailSistemaDTO[] = $objEmailSistemaDTO;
        }
        $objEmailSistemaRN = new EmailSistemaRN();
        $objEmailSistemaRN->desativar($arrObjEmailSistemaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'email_sistema_reativar':
      $strTitulo = 'Reativar E-mails do Sistema';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjEmailSistemaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objEmailSistemaDTO = new EmailSistemaDTO();
            $objEmailSistemaDTO->setNumIdEmailSistema($arrStrIds[$i]);
            $arrObjEmailSistemaDTO[] = $objEmailSistemaDTO;
          }
          $objEmailSistemaRN = new EmailSistemaRN();
          $objEmailSistemaRN->reativar($arrObjEmailSistemaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'email_sistema_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar E-mail do Sistema','Selecionar E-mails do Sistema');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='email_sistema_cadastrar'){
        if (isset($_GET['id_email_sistema'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_email_sistema']);
        }
      }
      break;

    case 'email_sistema_listar':
      $strTitulo = 'E-mails do Sistema';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'email_sistema_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'email_sistema_listar' || $_GET['acao'] == 'email_sistema_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_sistema_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objEmailSistemaDTO = new EmailSistemaDTO();
  $objEmailSistemaDTO->retNumIdEmailSistema();
  $objEmailSistemaDTO->retStrDescricao();
  //$objEmailSistemaDTO->retStrDe();
  //$objEmailSistemaDTO->retStrPara();
  //$objEmailSistemaDTO->retStrAssunto();
  //$objEmailSistemaDTO->retStrConteudo();
  $objEmailSistemaDTO->retStrSinAtivo();
  $objEmailSistemaDTO->setBolExclusaoLogica(false);
  
  PaginaSEI::getInstance()->prepararOrdenacao($objEmailSistemaDTO, 'IdEmailSistema', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objEmailSistemaDTO);

  $objEmailSistemaRN = new EmailSistemaRN();
  $arrObjEmailSistemaDTO = $objEmailSistemaRN->listar($objEmailSistemaDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objEmailSistemaDTO);
  $numRegistros = count($arrObjEmailSistemaDTO);

  if ($numRegistros > 0){

    $bolCheck = true;

    if ($_GET['acao']=='email_sistema_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='email_sistema_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('email_sistema_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('email_sistema_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('email_sistema_desativar');
    }

    if ($bolAcaoDesativar){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_sistema_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_sistema_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_sistema_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }
    

    $strResultado = '';

    /* if ($_GET['acao']!='email_sistema_reativar'){ */
      $strSumarioTabela = 'Tabela de E-mails do Sistema.';
      $strCaptionTabela = 'E-mails do Sistema';
    /* }else{
      $strSumarioTabela = 'Tabela de E-mails do Sistema Inativos.';
      $strCaptionTabela = 'E-mails do Sistema Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objEmailSistemaDTO,'Descrição','Descricao',$arrObjEmailSistemaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objEmailSistemaDTO,'Remetente','De',$arrObjEmailSistemaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objEmailSistemaDTO,'Destinatário','Para',$arrObjEmailSistemaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objEmailSistemaDTO,'Assunto','Assunto',$arrObjEmailSistemaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objEmailSistemaDTO,'Conteúdo','Conteudo',$arrObjEmailSistemaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      if ($arrObjEmailSistemaDTO[$i]->getStrSinAtivo()=='S'){
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema(),$arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjEmailSistemaDTO[$i]->getStrDescricao()).'</td>';
      //$strResultado .= '<td>'.$arrObjEmailSistemaDTO[$i]->getStrDe().'</td>';
      //$strResultado .= '<td>'.$arrObjEmailSistemaDTO[$i]->getStrPara().'</td>';
      //$strResultado .= '<td>'.$arrObjEmailSistemaDTO[$i]->getStrAssunto().'</td>';
      //$strResultado .= '<td>'.$arrObjEmailSistemaDTO[$i]->getStrConteudo().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_sistema_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_email_sistema='.$arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar E-mail do Sistema" alt="Consultar E-mail do Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_sistema_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_email_sistema='.$arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar E-mail do Sistema" alt="Alterar E-mail do Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema());
      }
 
      if ($arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema()==EmailSistemaRN::$ES_CONCESSAO_CREDENCIAL ||
          $arrObjEmailSistemaDTO[$i]->getNumIdEmailSistema()==EmailSistemaRN::$ES_CONCESSAO_CREDENCIAL_ASSINATURA){
        
        if ($bolAcaoDesativar && $arrObjEmailSistemaDTO[$i]->getStrSinAtivo()=='S'){
          $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar E-mail do Sistema" alt="Desativar E-mail do Sistema" class="infraImg" /></a>&nbsp;';
        }
  
        if ($bolAcaoReativar && $arrObjEmailSistemaDTO[$i]->getStrSinAtivo()=='N'){
          $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar E-mail do Sistema" alt="Reativar E-mail do Sistema" class="infraImg" /></a>&nbsp;';
        }
        
      }
      
      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir E-mail do Sistema" alt="Excluir E-mail do Sistema" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'email_sistema_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='email_sistema_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do E-mail do Sistema \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmEmailSistemaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmEmailSistemaLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum E-mail do Sistema selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos E-mails do Sistema selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmEmailSistemaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmEmailSistemaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do E-mail do Sistema \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmEmailSistemaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmEmailSistemaLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum E-mail do Sistema selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos E-mails do Sistema selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmEmailSistemaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmEmailSistemaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do E-mail do Sistema \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmEmailSistemaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmEmailSistemaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum E-mail do Sistema selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos E-mails do Sistema selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmEmailSistemaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmEmailSistemaLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEmailSistemaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>