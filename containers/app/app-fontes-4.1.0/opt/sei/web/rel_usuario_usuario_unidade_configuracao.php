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

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $bolOk = false;

  switch($_GET['acao']){

    case 'rel_usuario_usuario_unidade_configurar':
      $strTitulo = 'Usuários Selecionados';

      if (isset($_POST['sbmSalvar'])){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjRelUsuarioUsuarioUnidadeDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objRelUsuarioUsuarioUnidadeDTO = new RelUsuarioUsuarioUnidadeDTO();
            $objRelUsuarioUsuarioUnidadeDTO->setNumIdUsuarioAtribuicao($arrStrIds[$i]);
            $arrObjRelUsuarioUsuarioUnidadeDTO[] = $objRelUsuarioUsuarioUnidadeDTO;
          }
          $objRelUsuarioUsuarioUnidadeRN = new RelUsuarioUsuarioUnidadeRN();
          $objRelUsuarioUsuarioUnidadeRN->configurar($arrObjRelUsuarioUsuarioUnidadeDTO);
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

  $objRelUsuarioUsuarioUnidadeDTO = new RelUsuarioUsuarioUnidadeDTO();
  $objRelUsuarioUsuarioUnidadeDTO->retNumIdUsuarioAtribuicao();
  $objRelUsuarioUsuarioUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objRelUsuarioUsuarioUnidadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

  $objRelUsuarioUsuarioUnidadeRN = new RelUsuarioUsuarioUnidadeRN();
  $arrIdUsuariosAtribuicaoSelecionados = InfraArray::converterArrInfraDTO($objRelUsuarioUsuarioUnidadeRN->listar($objRelUsuarioUsuarioUnidadeDTO),'IdUsuarioAtribuicao');

  $objUnidadeDTO = new UnidadeDTO();
  $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  $objUsuarioRN = new UsuarioRN();
  $arrIdUsuarioPermissao = InfraArray::converterArrInfraDTO($objUsuarioRN->listarPorUnidadeRN0812($objUnidadeDTO),'IdUsuario');

  $arrIdUsuario = array_merge($arrIdUsuariosAtribuicaoSelecionados,$arrIdUsuarioPermissao);

  if (InfraArray::contar($arrIdUsuario)) {

    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->setBolExclusaoLogica(false);
    $objUsuarioDTO->retNumIdUsuario();
    $objUsuarioDTO->retStrSigla();
    $objUsuarioDTO->retStrNome();
    $objUsuarioDTO->retStrSiglaOrgao();
    $objUsuarioDTO->retStrDescricaoOrgao();
    $objUsuarioDTO->setNumIdUsuario($arrIdUsuario, InfraDTO::$OPER_IN);

    PaginaSEI::getInstance()->prepararOrdenacao($objUsuarioDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);

    $objUsuarioRN = new UsuarioRN();
    $arrObjUsuarioDTO = $objUsuarioRN->listarRN0490($objUsuarioDTO);

    $numRegistros = count($arrObjUsuarioDTO);

    if ($numRegistros > 0) {

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $strResultado = '';

      $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Usuários.">'."\n";
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Usuários', $numRegistros).'</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Sigla', 'Sigla', $arrObjUsuarioDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Nome', 'Nome', $arrObjUsuarioDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Órgao', 'SiglaOrgao', $arrObjUsuarioDTO).'</th>'."\n";
      $strResultado .= '</tr>'."\n";

      for ($i = 0; $i < $numRegistros; $i++) {
        $strResultado .= '<tr class="infraTrClara">';
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjUsuarioDTO[$i]->getNumIdUsuario(), UsuarioINT::formatarSiglaNome($arrObjUsuarioDTO[$i]->getStrSigla(), $arrObjUsuarioDTO[$i]->getStrNome()), (in_array($arrObjUsuarioDTO[$i]->getNumIdUsuario(), $arrIdUsuariosAtribuicaoSelecionados) ? 'S' : 'N')).'</td>';
        $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSigla()).'</td>';
        $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()).'</td>';
        $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSiglaOrgao()).'</a></td>';
        $strResultado .= '</tr>'."\n";
      }
      $strResultado .= '</table>';
    }
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
  <form id="frmRelUsuarioUsuarioUnidadeLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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