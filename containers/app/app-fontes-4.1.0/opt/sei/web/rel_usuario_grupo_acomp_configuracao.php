<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/09/2017 - criado por mga
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

  PaginaSEI::getInstance()->salvarCamposPost(array('selUsuario','selGrupoAcompanhamento'));

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $bolOk = false;

  switch($_GET['acao']){

    case 'rel_usuario_grupo_acomp_configurar':
      $strTitulo = 'Grupos de Acompanhamentos Especiais Selecionados';

      if (isset($_POST['sbmSalvar'])){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjRelUsuarioGrupoAcompDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objRelUsuarioGrupoAcompDTO = new RelUsuarioGrupoAcompDTO();
            $objRelUsuarioGrupoAcompDTO->setNumIdGrupoAcompanhamento($arrStrIds[$i]);
            $arrObjRelUsuarioGrupoAcompDTO[] = $objRelUsuarioGrupoAcompDTO;
          }
          $objRelUsuarioGrupoAcompRN = new RelUsuarioGrupoAcompRN();
          $objRelUsuarioGrupoAcompRN->configurar($arrObjRelUsuarioGrupoAcompDTO);
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

  $objRelUsuarioGrupoAcompDTO = new RelUsuarioGrupoAcompDTO();
  $objRelUsuarioGrupoAcompDTO->retNumIdGrupoAcompanhamento();
  $objRelUsuarioGrupoAcompDTO->setNumIdUnidadeGrupoAcompanhamento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objRelUsuarioGrupoAcompDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

  $objRelUsuarioGrupoAcompRN = new RelUsuarioGrupoAcompRN();
  $arrIdGruposAcompanhamentoSelecionados = InfraArray::converterArrInfraDTO($objRelUsuarioGrupoAcompRN->listar($objRelUsuarioGrupoAcompDTO),'IdGrupoAcompanhamento');


  $objGrupoAcompanhamentoDTO = new GrupoAcompanhamentoDTO();
  $objGrupoAcompanhamentoDTO->retNumIdGrupoAcompanhamento();
  $objGrupoAcompanhamentoDTO->retStrNome();
  $objGrupoAcompanhamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoAcompanhamentoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
  $arrObjGrupoAcompanhamentoDTO = $objGrupoAcompanhamentoRN->listar($objGrupoAcompanhamentoDTO);

  $numRegistros = count($arrObjGrupoAcompanhamentoDTO);

  if ($numRegistros > 0){

    $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Grupos de Acompanhamento.">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Grupos de Acompanhamento',$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO,'Nome','Nome',$arrObjGrupoAcompanhamentoDTO).'</th>'."\n";
    $strResultado .= '</tr>'."\n";

    for($i = 0;$i < $numRegistros; $i++){
      $strResultado .= '<tr class="infraTrClara">';
      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(),$arrObjGrupoAcompanhamentoDTO[$i]->getStrNome(),(in_array($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(),$arrIdGruposAcompanhamentoSelecionados)?'S':'N')).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</td>';
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
  <form id="frmRelUsuarioGrupoAcompLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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