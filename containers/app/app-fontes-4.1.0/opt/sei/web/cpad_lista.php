<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);


  switch($_GET['acao']){
    //excluir padrão
    case 'cpad_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjCpadDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCpadDTO = new CpadDTO();
          $objCpadDTO->setNumIdCpad($arrStrIds[$i]);
          $arrObjCpadDTO[] = $objCpadDTO;
        }
        $objCpadRN = new CpadRN();
        $objCpadRN->excluir($arrObjCpadDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
    //desativar padrão
    case 'cpad_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjCpadDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCpadDTO = new CpadDTO();
          $objCpadDTO->setNumIdCpad($arrStrIds[$i]);
          $arrObjCpadDTO[] = $objCpadDTO;
        }
        $objCpadRN = new CpadRN();
        $objCpadRN->desativar($arrObjCpadDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
    //reativar padrão
    case 'cpad_reativar':
      $strTitulo = 'Reativar Comissões Permanentes de Avaliação de Documentos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjCpadDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objCpadDTO = new CpadDTO();
            $objCpadDTO->setNumIdCpad($arrStrIds[$i]);
            $arrObjCpadDTO[] = $objCpadDTO;
          }
          $objCpadRN = new CpadRN();
          $objCpadRN->reativar($arrObjCpadDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;
    //listar padrão
    case 'cpad_listar':
      $strTitulo = 'Comissões Permanentes de Avaliação de Documentos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  if ($_GET['acao'] == 'cpad_listar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('cpad_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  }

  $objCpadDTO = new CpadDTO();
  $objCpadDTO->retNumIdCpad();
  $objCpadDTO->retStrSigla();
  $objCpadDTO->retStrSiglaOrgao();
  $objCpadDTO->retStrDescricaoOrgao();
  $objCpadDTO->retStrDescricao();

  if ($_GET['acao'] == 'cpad_reativar'){
    //Lista somente inativos
    $objCpadDTO->setBolExclusaoLogica(false);
    $objCpadDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objCpadDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objCpadDTO);

  $objCpadRN = new CpadRN();
  $arrObjCpadDTO = $objCpadRN->listar($objCpadDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objCpadDTO);

  $numRegistros =  InfraArray::contar($arrObjCpadDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='cpad_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('cpad_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('cpad_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('cpad_excluir');
      $bolAcaoDesativar = false;
      $bolAcaoVersaoListar = SessaoSEI::getInstance()->verificarPermissao('cpad_versao_listar');
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('cpad_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('cpad_alterar');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('cpad_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('cpad_desativar');
      $bolAcaoVersaoListar = SessaoSEI::getInstance()->verificarPermissao('cpad_versao_listar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_excluir&acao_origem='.$_GET['acao']);
    }


    $strResultado = '';

    if ($_GET['acao']!='cpad_reativar'){
      $strSumarioTabela = 'Tabela de Comissões Permanentes de Avaliação de Documentos.';
      $strCaptionTabela = 'Comissões Permanentes de Avaliação de Documentos';
    }else{
      $strSumarioTabela = 'Tabela de Comissões Permanentes de Avaliação de Documentos Inativas.';
      $strCaptionTabela = 'Comissões Permanentes de Avaliação de Documentos Inativas';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh"  width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadDTO,'Sigla','Sigla',$arrObjCpadDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadDTO,'Órgão','SiglaOrgao',$arrObjCpadDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td align="center">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjCpadDTO[$i]->getNumIdCpad(),$arrObjCpadDTO[$i]->getStrSigla()).'</td>';
      }
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjCpadDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjCpadDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjCpadDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjCpadDTO[$i]->getStrSiglaOrgao()).'</a></td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCpadDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjCpadDTO[$i]->getNumIdCpad());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_cpad='.$arrObjCpadDTO[$i]->getNumIdCpad()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar CPAD" alt="Consultar CPAD" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_cpad='.$arrObjCpadDTO[$i]->getNumIdCpad()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar CPAD" alt="Alterar CPAD" class="infraImg" /></a>&nbsp;';
      }
      //ícone para lista de versões da cpad
      if($bolAcaoVersaoListar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_versao_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_cpad=' . $arrObjCpadDTO[$i]->getNumIdCpad()) . '"  tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_VERSOES_COMISSOES.'" title="Consultar Versões da CPAD" alt="Consultar Versões da CPAD" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjCpadDTO[$i]->getNumIdCpad();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjCpadDTO[$i]->getStrSigla());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar CPAD" alt="Desativar CPAD" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar CPAD" alt="Reativar CPAD" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir CPAD" alt="Excluir CPAD" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
<?if(0){?><style><?}?>

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='cpad_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da CPAD \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCpadLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCpadLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma CPAD selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Comissões Permanentes de Avaliação de Documentos selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCpadLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCpadLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da CPAD \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCpadLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCpadLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma CPAD selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Comissões Permanentes de Avaliação de Documentos selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCpadLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCpadLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da CPAD \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCpadLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCpadLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma CPAD selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Comissões Permanentes de Avaliação de Documentos selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCpadLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCpadLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCpadLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
