<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/09/2017 - criado por mga
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

    case 'rel_usuario_marcador_configurar':
      $strTitulo = 'Marcadores Selecionados';

      if (isset($_POST['sbmSalvar'])) {
        try {
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjRelUsuarioMarcadorDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objRelUsuarioMarcadorDTO = new RelUsuarioMarcadorDTO();
            $objRelUsuarioMarcadorDTO->setNumIdMarcador($arrStrIds[$i]);
            $arrObjRelUsuarioMarcadorDTO[] = $objRelUsuarioMarcadorDTO;
          }
          $objRelUsuarioMarcadorRN = new RelUsuarioMarcadorRN();
          $objRelUsuarioMarcadorRN->configurar($arrObjRelUsuarioMarcadorDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

          $bolOk = true;

        } catch (Exception $e) {
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


  $objRelUsuarioMarcadorDTO = new RelUsuarioMarcadorDTO();
  $objRelUsuarioMarcadorDTO->retNumIdMarcador();
  $objRelUsuarioMarcadorDTO->setNumIdUnidadeMarcador(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objRelUsuarioMarcadorDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

  $objRelUsuarioMarcadorRN = new RelUsuarioMarcadorRN();
  $arrIdMarcadoresSelecionados = InfraArray::converterArrInfraDTO($objRelUsuarioMarcadorRN->listar($objRelUsuarioMarcadorDTO),'IdMarcador');

  $objMarcadorDTO = new MarcadorDTO();
  $objMarcadorDTO->setBolExclusaoLogica(false);
  $objMarcadorDTO->retNumIdMarcador();
  $objMarcadorDTO->retStrNome();
  //$objMarcadorDTO->retStrDescricao();
  $objMarcadorDTO->retStrStaIcone();
  $objMarcadorDTO->retStrSinAtivo();
  //$objMarcadorDTO->retNumProcessos();

  $objMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  PaginaSEI::getInstance()->prepararOrdenacao($objMarcadorDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objMarcadorRN = new MarcadorRN();
  $arrObjMarcadorDTO = $objMarcadorRN->listar($objMarcadorDTO);

  $numRegistros = count($arrObjMarcadorDTO);

  if ($numRegistros > 0){

    $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Marcadores.">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Marcadores',$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">Ícone</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO,'Nome','Nome',$arrObjMarcadorDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO,'Descrição','Descricao',$arrObjMarcadorDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO,'Ícone','StaIcone',$arrObjMarcadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ativo</th>'."\n";
    $strResultado .= '</tr>'."\n";

    $objMarcadorRN = new MarcadorRN();
    $arrIcones = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(),'StaIcone');

    for($i = 0;$i < $numRegistros; $i++){
      $strResultado .= '<tr class="infraTrClara">';
      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMarcadorDTO[$i]->getNumIdMarcador(),$arrObjMarcadorDTO[$i]->getStrNome(),(in_array($arrObjMarcadorDTO[$i]->getNumIdMarcador(),$arrIdMarcadoresSelecionados)?'S':'N')).'</td>';
      $strResultado .= '<td align="center"><a href="#" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$arrIcones[$arrObjMarcadorDTO[$i]->getStrStaIcone()]->getStrArquivo().'" title="'.PaginaSEI::tratarHTML($arrIcones[$arrObjMarcadorDTO[$i]->getStrStaIcone()]->getStrDescricao()).'" alt="'.PaginaSEI::tratarHTML($arrIcones[$arrObjMarcadorDTO[$i]->getStrStaIcone()]->getStrDescricao()).'" class="infraImg" /></a></td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrSinAtivo()).'</td>';
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
  <form id="frmRelUsuarioMarcadorLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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