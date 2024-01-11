<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2009 - criado por mga
*
* Versão do Gerador de Código: 1.27.1
*
* Versão no CVS: $Id$
*/

try {
  //require_once 'Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'infra_atributo_sessao_excluir':
      try{
        $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
        foreach($arrStrIds as $id){
          SessaoInfra::getInstance()->removerAtributo($id);
        }
        PaginaInfra::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaInfra::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'infra_atributo_sessao_listar':
      $strTitulo = 'Atributos de Sessão';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $bolAcaoCadastrar = SessaoInfra::getInstance()->verificarPermissao('infra_atributo_sessao_cadastrar');
  if ($bolAcaoCadastrar){
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_atributo_sessao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }


  $strSiglaOrgaoSistema = SessaoInfra::getInstance()->getStrSiglaOrgaoSistema();
  $strSiglaSistema = SessaoInfra::getInstance()->getStrSiglaSistema();
    
  $arrAtributos = array();
	if (isset($_SESSION['INFRA_ATRIBUTOS'][$strSiglaOrgaoSistema][$strSiglaSistema])){
	  $arrAtributos = $_SESSION['INFRA_ATRIBUTOS'][$strSiglaOrgaoSistema][$strSiglaSistema];
	}else{
	  $arrAtributos = array();
	}
			  
  $numRegistros = count($arrAtributos);

  if ($numRegistros > 0){

    $bolCheck = false;

    $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_atributo_sessao_alterar');
    $bolAcaoImprimir = true;
    $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_atributo_sessao_excluir');

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_atributo_sessao_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Atributos de Sessão.';
    $strCaptionTabela = 'Atributos de Sessão';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaInfra::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">Nome</th>'."\n";
    $strResultado .= '<th class="infraTh">Valor</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    $i = 0;
    
    
    
    foreach(array_keys($arrAtributos) as $nome){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaInfra::getInstance()->getTrCheck($i++,$nome,$nome).'</td>';
      }
      $strResultado .= '<td valign="top">'.PaginaInfra::getInstance()->tratarHTML($nome).'</td>';
      //$strResultado .= '<td>'.$arrAtributos[$nome].'</td>';
      
      $strResultado .= '<td valign="top">';
      if (!is_array($arrAtributos[$nome])){
        $strResultado .= PaginaInfra::getInstance()->tratarHTML($arrAtributos[$nome]);
      }else{
      	$strResultado .= nl2br(PaginaInfra::getInstance()->tratarHTML(print_r($arrAtributos[$nome],true)));
      }
      $strResultado .= '</td>';
      
      
      $strResultado .= '<td align="center" valign="top">';

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_atributo_sessao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&nome='.$nome).'" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeAlterar().'" title="Alterar Atributo de Sessão" alt="Alterar Atributo de Sessão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strId = $nome;
        $strDescricao = PaginaInfra::getInstance()->formatarParametrosJavaScript($nome);
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeExcluir().'" title="Excluir Atributo de Sessão" alt="Excluir Atributo de Sessão" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

}catch(Exception $e){
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
<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('btnFechar').focus();
  infraEfeitoImagens();
  infraEfeitoTabelas();
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Atributo de Sessão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraAtributoSessaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInfraAtributoSessaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Atributo de Sessão selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Atributos de Sessão selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraAtributoSessaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInfraAtributoSessaoLista').submit();
  }
}
<? } ?>

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraAtributoSessaoLista" method="post" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaInfra::getInstance()->abrirAreaDados('5em');
  //PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaInfra::getInstance()->montarAreaDebug();
  PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>