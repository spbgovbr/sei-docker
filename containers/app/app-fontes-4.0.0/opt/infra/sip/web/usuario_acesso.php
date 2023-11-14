<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/07/2018 - criado por mga
 *
 */
try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSip::getInstance()->salvarCamposPost(array('selTipoProcedimento'));

  $strParametros = '';
  if (isset($_GET['id_usuario'])){
    $strParametros .= '&id_usuario='.$_GET['id_usuario'];
  }

  $objUsuarioDTO = new UsuarioDTO();

  $arrComandos = array();

  $objUsuarioRN = new UsuarioRN();

  switch($_GET['acao']){

    case 'usuario_bloquear':

      $strTitulo = 'Bloquear Usuário';

      $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);
      $objUsuarioDTO->setNumIdUsuarioOperacao(SessaoSip::getInstance()->getNumIdUsuario());
      $objUsuarioDTO->setStrMotivo($_POST['txaMotivo']);
      $objUsuarioDTO->setStrIdCodigoAcesso(null);

      //Escolheu uma ação nesta tela
      if (isset($_POST['sbmSalvar'])){
        try{

          $objUsuarioRN->bloquear($objUsuarioDTO);

          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_listar&acao_origem='.$_GET['acao'].$strParametros.PaginaSip::montarAncora($_GET['id_usuario'])));
          die;

        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSip::montarAncora($_GET['id_usuario'])).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      break;

    case 'usuario_desbloquear':

      $strTitulo = 'Desbloquear Usuário';

      $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);
      $objUsuarioDTO->setNumIdUsuarioOperacao(SessaoSip::getInstance()->getNumIdUsuario());
      $objUsuarioDTO->setStrMotivo($_POST['txaMotivo']);
      $objUsuarioDTO->setStrIdCodigoAcesso(null);

      //Escolheu uma ação nesta tela
      if (isset($_POST['sbmSalvar'])){
        try{

          $objUsuarioRN->desbloquear($objUsuarioDTO);

          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_listar&acao_origem='.$_GET['acao'].$strParametros.PaginaSip::montarAncora($_GET['id_usuario'])));
          die;

        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSip::montarAncora($_GET['id_usuario'])).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objUsuarioDTOBanco = new UsuarioDTO();
  $objUsuarioDTOBanco->setBolExclusaoLogica(false);
  $objUsuarioDTOBanco->retStrSigla();
  $objUsuarioDTOBanco->retStrNome();
  $objUsuarioDTOBanco->retStrSiglaOrgao();
  $objUsuarioDTOBanco->setNumIdUsuario($_GET['id_usuario']);

  $objUsuarioDTOBanco = $objUsuarioRN->consultar($objUsuarioDTOBanco);

  $strTitulo .= ' '.$objUsuarioDTOBanco->getStrSigla().' / '.$objUsuarioDTOBanco->getStrSiglaOrgao().' - '.$objUsuarioDTOBanco->getStrNome();


  $objUsuarioHistoricoDTO = new UsuarioHistoricoDTO();
  $objUsuarioHistoricoDTO->retStrIdCodigoAcesso();
  $objUsuarioHistoricoDTO->retNumIdUsuarioHistorico();
  $objUsuarioHistoricoDTO->retDthOperacao();
  $objUsuarioHistoricoDTO->retStrStaOperacao();
  $objUsuarioHistoricoDTO->retNumIdUsuarioOperacao();
  $objUsuarioHistoricoDTO->retStrSiglaUsuarioOperacao();
  $objUsuarioHistoricoDTO->retStrNomeUsuarioOperacao();
  $objUsuarioHistoricoDTO->retStrSiglaOrgaoUsuarioOperacao();
  $objUsuarioHistoricoDTO->retStrDescricaoOrgaoUsuarioOperacao();
  $objUsuarioHistoricoDTO->retStrMotivo();
  $objUsuarioHistoricoDTO->setNumIdUsuario($_GET['id_usuario']);
  $objUsuarioHistoricoDTO->setOrdDthOperacao(InfraDTO::$TIPO_ORDENACAO_DESC);

  $objUsuarioHistoricoRN = new UsuarioHistoricoRN();
  $arrObjUsuarioHistoricoDTO = $objUsuarioHistoricoRN->listar($objUsuarioHistoricoDTO);

  $numRegistros = count($arrObjUsuarioHistoricoDTO);

  if ($numRegistros > 0) {

    $bolAcaoCodigoAcessoConsultar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_consultar');

    $arrObjOperacaoUsuarioHistoricoDTO = InfraArray::indexarArrInfraDTO($objUsuarioHistoricoRN->listarValoresOperacao(),'StaOperacao');

    $strResultado = '';

    $strSumarioTabela = 'Tabela de bloqueios/desbloqueios.';
    $strCaptionTabela = 'bloqueios/desbloqueios';

    $strResultado .= '<table width="95%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="15%">Data/Hora</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Operação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh">Motivo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr = '';

    for ($i = 0; $i < $numRegistros; $i++) {

      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getDthOperacao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjOperacaoUsuarioHistoricoDTO[$arrObjUsuarioHistoricoDTO[$i]->getStrStaOperacao()]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getStrNomeUsuarioOperacao()).'" title="'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getStrNomeUsuarioOperacao()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getStrSiglaUsuarioOperacao()).'</a> / <a alt="'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getStrDescricaoOrgaoUsuarioOperacao()).'" title="'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getStrDescricaoOrgaoUsuarioOperacao()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getStrSiglaOrgaoUsuarioOperacao()).'</a></td>';
      $strResultado .= '<td>'.PaginaSip::tratarHTML($arrObjUsuarioHistoricoDTO[$i]->getStrMotivo()).'</td>';
      $strResultado .= '<td align="center">';

      if ($bolAcaoCodigoAcessoConsultar && $arrObjUsuarioHistoricoDTO[$i]->getStrIdCodigoAcesso()!=null){
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);exibirCodigoAcesso(\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_codigo_acesso='.$arrObjUsuarioHistoricoDTO[$i]->getStrIdCodigoAcesso().'&pagina_simples=1').'\');"  tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getDiretorioSvgLocal().'/2fa.svg"  title="Consultar Código de Acesso Associado" alt="Consultar Código de Acesso Associado" class="infraImg"/></a>';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblMotivo {position:absolute;left:0%;top:0%;width:50%;}
  #txaMotivo {position:absolute;left:0%;top:15%;width:95%;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
    document.getElementById('txaMotivo').focus();
    infraEfeitoTabelas();
  }

  function OnSubmitForm() {

  if (infraTrim(document.getElementById('txaMotivo').value)==''){
    alert('Motivo não informado.');
    document.getElementById('txaMotivo').focus();
    return false;
  }

  return true;
  }

  function exibirCodigoAcesso(link){
     infraAbrirJanela(link,'janelaCodigoAcesso',750,550,'location=0,status=1,resizable=1,scrollbars=1',false);
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmUsuarioBloqueio" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('12em');
    ?>

    <label id="lblMotivo" for="txaMotivo" class="infraLabelObrigatorio">Motivo:</label>
    <textarea id="txaMotivo" name="txaMotivo" rows="5" class="infraTextarea" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><?=PaginaSip::tratarHTML($objUsuarioDTO->getStrMotivo());?></textarea>

    <?
    PaginaSip::getInstance()->fecharAreaDados();

    if ($numRegistros){
      PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    }

    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
//PaginaSip::getInstance()->montarAreaDebug();
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>