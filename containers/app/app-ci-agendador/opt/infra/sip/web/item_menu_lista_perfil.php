<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 05/08/2013 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  $arrComandos = array();

  $objPerfilRN = new PerfilRN();

  switch($_GET['acao']){
    case 'item_menu_listar_perfil':
      $strTitulo = 'Itens de Menu do Perfil';
      
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setBolExclusaoLogica(false);
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->retStrSigla();
      $objSistemaDTO->retStrSiglaOrgao();
      $objSistemaDTO->setNumIdSistema($_GET['id_sistema']);
      
      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
      
      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->setBolExclusaoLogica(false);
      $objPerfilDTO->retNumIdPerfil();
      $objPerfilDTO->retNumIdSistema();
      $objPerfilDTO->retStrNome();
      $objPerfilDTO->setNumIdPerfil($_GET['id_perfil']);
      
      $objPerfilRN = new PerfilRN();
      $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);
      
      
      $objItemMenuRN = new ItemMenuRN();
      $arrObjItemMenuDTO = $objItemMenuRN->listarPerfil($objPerfilDTO);
      
      $numRegistros = count($arrObjItemMenuDTO);
         
      if ($numRegistros > 0){
      
        $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';
    
        $strResultado = '';
        $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Itens de Menu cadastrados">'."\n";
        $strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Itens de Menu',$numRegistros).'</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
        $strResultado .= '<th class="infraTh">Ramifica��o</th>';
        $strResultado .= '<th class="infraTh">Menu</th>';
        
        $strResultado .= '</tr>'."\n";
        for($i = 0;$i < $numRegistros; $i++){
          if ( ($i+2) % 2 ) {
            $strResultado .= '<tr class="infraTrEscura">';
          } else {
            $strResultado .= '<tr class="infraTrClara">';
          }
          $strResultado .= '<td valign="top">'.PaginaSip::getInstance()->getTrCheck($i,$arrObjItemMenuDTO[$i]->getNumIdMenu().'-'.$arrObjItemMenuDTO[$i]->getNumIdItemMenu(),$arrObjItemMenuDTO[$i]->getStrRotulo()).'</td>';
          
          $strResultado .= '<td align="left">'.PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getStrRamificacao()).'</td>';
          $strResultado .= '<td align="center" width="15%">'.PaginaSip::tratarHTML($arrObjItemMenuDTO[$i]->getStrNomeMenu()).'</td>';
          $strResultado .= '</tr>'."\n";
        }
        $strResultado .= '</table>';
      }

      $arrComandos[] = '<input type="button" name="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($_GET['id_perfil'].'-'.$_GET['id_sistema'])).'\';" class="infraButton" />';

      break;

    default:
      throw new InfraException("A��o '".$_GET['acao']."' n�o reconhecida.");
  }

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Montar Perfil');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
#txtOrgaoSistema {position:absolute;left:0%;top:12%;width:25%;}

#lblSistema {position:absolute;left:0%;top:30%;width:25%;}
#txtSistema {position:absolute;left:0%;top:42%;width:25%;}

#lblPerfil {position:absolute;left:0%;top:60%;width:60%;}
#txtPerfil {position:absolute;left:0%;top:72%;width:60%;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  
  if (!validarForm()){
    return false;
  }
  
  return true;
}

function validarForm(){
  return true;
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmItemMenuListaPerfil" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('15em');
?>
  <label id="lblOrgaoSistema" for="txtOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">�<span class="infraTeclaAtalho">r</span>g�o do Sistema:</label>
  <input type="text" id="txtOrgaoSistema" name="txtOrgaoSistema" class="infraText" value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrSiglaOrgao());?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" readonly="readonly" />

  <label id="lblSistema" for="txtSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <input type="text" id="txtSistema" name="txtSistema" class="infraText" value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrSigla());?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" readonly="readonly" />

  <label id="lblPerfil" for="txtPerfil" accesskey="P" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">P</span>erfil:</label>
  <input type="text" id="txtPerfil" name="txtPerfil" class="infraText" value="<?=PaginaSip::tratarHTML($objPerfilDTO->getStrNome());?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" readonly="readonly" />
<?
  PaginaSip::getInstance()->fecharAreaDados();
	//echo $strResultado;
	PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>