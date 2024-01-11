<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 27/08/2019 - criado por mga
 *
 * Versão do Gerador de Código: 1.40.1
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

  PaginaSEI::getInstance()->salvarCamposPost(array('selUsuario','selGrupoBloco'));

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $bolOk = false;

  switch($_GET['acao']){

    case 'rel_usuario_grupo_bloco_configurar':
      $strTitulo = 'Grupos de Blocos Selecionados';

      if (isset($_POST['sbmSalvar'])){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjRelUsuarioGrupoBlocoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objRelUsuarioGrupoBlocoDTO = new RelUsuarioGrupoBlocoDTO();
            $objRelUsuarioGrupoBlocoDTO->setNumIdGrupoBloco($arrStrIds[$i]);
            $arrObjRelUsuarioGrupoBlocoDTO[] = $objRelUsuarioGrupoBlocoDTO;
          }
          $objRelUsuarioGrupoBlocoRN = new RelUsuarioGrupoBlocoRN();
          $objRelUsuarioGrupoBlocoRN->configurar($arrObjRelUsuarioGrupoBlocoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

          $bolOk = true;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        //header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']));
        //die;
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objRelUsuarioGrupoBlocoDTO = new RelUsuarioGrupoBlocoDTO();
  $objRelUsuarioGrupoBlocoDTO->retNumIdGrupoBloco();
  $objRelUsuarioGrupoBlocoDTO->setNumIdUnidadeGrupoBloco(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objRelUsuarioGrupoBlocoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

  $objRelUsuarioGrupoBlocoRN = new RelUsuarioGrupoBlocoRN();
  $arrIdGruposBlocoSelecionados = InfraArray::converterArrInfraDTO($objRelUsuarioGrupoBlocoRN->listar($objRelUsuarioGrupoBlocoDTO),'IdGrupoBloco');


  $objGrupoBlocoDTO = new GrupoBlocoDTO();
  $objGrupoBlocoDTO->retNumIdGrupoBloco();
  $objGrupoBlocoDTO->retStrNome();
  $objGrupoBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoBlocoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objGrupoBlocoRN = new GrupoBlocoRN();
  $arrObjGrupoBlocoDTO = $objGrupoBlocoRN->listar($objGrupoBlocoDTO);

  $numRegistros = count($arrObjGrupoBlocoDTO);

  if ($numRegistros > 0){

    $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Grupos de Blocos.">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Grupos de Blocos',$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoBlocoDTO,'Nome','Nome',$arrObjGrupoBlocoDTO).'</th>'."\n";
    $strResultado .= '</tr>'."\n";

    for($i = 0;$i < $numRegistros; $i++){
      $strResultado .= '<tr class="infraTrClara">';
      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoBlocoDTO[$i]->getNumIdGrupoBloco(),$arrObjGrupoBlocoDTO[$i]->getStrNome(),(in_array($arrObjGrupoBlocoDTO[$i]->getNumIdGrupoBloco(),$arrIdGruposBlocoSelecionados)?'S':'N')).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoBlocoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';


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
    <?if ($bolOk){?>
      self.setTimeout('infraFecharJanelaModal()',200);
    <?}else{?>
      document.getElementById('sbmSalvar').focus();
      infraEfeitoTabelas();
    <?}?>
  }

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmRelUsuarioGrupoBlocoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>