<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/11/2019 - criado por mga
 *
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

  PaginaSEI::getInstance()->salvarCamposPost(array('selUsuario','selTipoProcedimento'));

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $bolOk = false;

  switch($_GET['acao']){

    case 'rel_usuario_tipo_proced_configurar':
      $strTitulo = 'Tipos de Processos Selecionados';

      if (isset($_POST['sbmSalvar'])){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjRelUsuarioTipoProcedDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objRelUsuarioTipoProcedDTO = new RelUsuarioTipoProcedDTO();
            $objRelUsuarioTipoProcedDTO->setNumIdTipoProcedimento($arrStrIds[$i]);
            $arrObjRelUsuarioTipoProcedDTO[] = $objRelUsuarioTipoProcedDTO;
          }
          $objRelUsuarioTipoProcedRN = new RelUsuarioTipoProcedRN();
          $objRelUsuarioTipoProcedRN->configurar($arrObjRelUsuarioTipoProcedDTO);
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

  $objRelUsuarioTipoProcedDTO = new RelUsuarioTipoProcedDTO();
  $objRelUsuarioTipoProcedDTO->retNumIdTipoProcedimento();
  $objRelUsuarioTipoProcedDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objRelUsuarioTipoProcedDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

  $objRelUsuarioTipoProcedRN = new RelUsuarioTipoProcedRN();
  $arrIdTiposProcedimentoSelecionados = InfraArray::converterArrInfraDTO($objRelUsuarioTipoProcedRN->listar($objRelUsuarioTipoProcedDTO),'IdTipoProcedimento');

  $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
  $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
  $objTipoProcedimentoDTO->retStrNome();
  PaginaSEI::getInstance()->prepararOrdenacao($objTipoProcedimentoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objTipoProcedimentoRN = new TipoProcedimentoRN();
  $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

  $numRegistros = count($arrObjTipoProcedimentoDTO);

  if ($numRegistros > 0){

    $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Tipos de Processos.">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Tipos de Processos',$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO,'Nome','Nome',$arrObjTipoProcedimentoDTO).'</th>'."\n";
    $strResultado .= '</tr>'."\n";

    for($i = 0;$i < $numRegistros; $i++){
      $strResultado .= '<tr class="infraTrClara">';
      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(),$arrObjTipoProcedimentoDTO[$i]->getStrNome(),(in_array($arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(),$arrIdTiposProcedimentoSelecionados)?'S':'N')).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>';
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
      infraEfeitoTabelas();
    <?}?>
  }

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmRelUsuarioTipoProcedLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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