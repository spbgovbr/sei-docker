<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/05/2012 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore','id_procedimento','id_procedimento_federacao'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $arrComandos = array();

  $numRegistros = 0;

  switch($_GET['acao']){

    case 'acesso_federacao_gerenciar':
      $strTitulo = 'SEI Federação';

      $numRegistros = 0;

      if ($_GET['id_procedimento_federacao']!='') {

        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->setBolExclusaoLogica(false);
        $objAcessoFederacaoDTO->retNumStaTipo();
        $objAcessoFederacaoDTO->retStrIdAcessoFederacao();

        $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrSiglaInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrDescricaoInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrSiglaOrgaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrDescricaoOrgaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrIdUnidadeFederacaoRem();
        $objAcessoFederacaoDTO->retStrSiglaUnidadeFederacaoRem();
        $objAcessoFederacaoDTO->retStrDescricaoUnidadeFederacaoRem();

        $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrSiglaInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrDescricaoInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrSiglaOrgaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrDescricaoOrgaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrSiglaUnidadeFederacaoDest();
        $objAcessoFederacaoDTO->retStrDescricaoUnidadeFederacaoDest();

        $objAcessoFederacaoDTO->retStrIdProcedimentoFederacao();
        $objAcessoFederacaoDTO->retDthLiberacao();
        $objAcessoFederacaoDTO->retStrMotivoLiberacao();
        $objAcessoFederacaoDTO->retDthCancelamento();
        $objAcessoFederacaoDTO->retStrMotivoCancelamento();

        $objAcessoFederacaoDTO->retStrSinAtivo();

        $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($_GET['id_procedimento_federacao']);

        $objAcessoFederacaoDTO->setOrdStrIdAcessoFederacao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        $arrObjAcessoFederacaoDTO = $objAcessoFederacaoRN->listar($objAcessoFederacaoDTO);

        $numRegistros = count($arrObjAcessoFederacaoDTO);
      }

      if ($numRegistros==0 && $_GET['acao_origem']!='acesso_federacao_cancelar'){
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_federacao_enviar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']));
        die;
      }

      break;

	    default:
	      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }



  $bolAcaoEnviar = SessaoSEI::getInstance()->verificarPermissao('acesso_federacao_enviar');
  $bolAcaoCancelarEnvio = SessaoSEI::getInstance()->verificarPermissao('acesso_federacao_cancelar');

  if ($bolAcaoEnviar){

    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->retStrIdProtocoloFederacao();
    $objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);

    $objProtocoloRN = new ProtocoloRN();
    $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

    //se o protocolo já tramitou pelo federacao
    if ($objProtocoloDTO->getStrIdProtocoloFederacao()!=null) {

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retStrIdOrgaoFederacao();
      $objOrgaoDTO->retStrSinEnvioProcesso();
      $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

      //se o orgao nunca esteve no federacao entao nao tem acesso
      if ($objOrgaoDTO->getStrIdOrgaoFederacao()==null){
        $bolAcaoEnviar = false;
      }else{

        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($objProtocoloDTO->getStrIdProtocoloFederacao());
        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        $arrIdOrgaoFederacaoAcesso = InfraArray::converterArrInfraDTO($objAcessoFederacaoRN->obterOrgaosAcessoFederacao($objAcessoFederacaoDTO), 'IdOrgaoFederacao');

        //se o orgao nao tem acesso no federacao
        if (count($arrIdOrgaoFederacaoAcesso)==0 || !in_array($objOrgaoDTO->getStrIdOrgaoFederacao(),$arrIdOrgaoFederacaoAcesso)){

          $objProtocoloFederacaoDTO = new ProtocoloFederacaoDTO();
          $objProtocoloFederacaoDTO->retStrIdInstalacaoFederacao();
          $objProtocoloFederacaoDTO->setStrIdProtocoloFederacao($objProtocoloDTO->getStrIdProtocoloFederacao());

          $objProtocoloFederacaoRN = new ProtocoloFederacaoRN();
          $objProtocoloFederacaoDTO = $objProtocoloFederacaoRN->consultar($objProtocoloFederacaoDTO);

          $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
          if ($objProtocoloFederacaoDTO==null || $objProtocoloFederacaoDTO->getStrIdInstalacaoFederacao()!=$objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal()){
            $bolAcaoEnviar = false;
          }
        }
      }
    }

    if ($bolAcaoEnviar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo Envio" onclick="parent.parent.parent.infraExibirAviso(false);location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_federacao_enviar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo Envio</button>';
    }else{
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo Envio" onclick="alert(\'Órgão '.SessaoSEI::getInstance()->getStrSiglaOrgaoUnidadeAtual().' não possui envio do processo pelo SEI Federação.\');" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo Envio</button>';
    }
  }

  if ($numRegistros > 0){

    $objUnidadeDTO = new UnidadeDTO();
    $objUnidadeDTO->setBolExclusaoLogica(false);
    $objUnidadeDTO->retStrIdUnidadeFederacao();
    $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objUnidadeRN = new UnidadeRN();
    $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

    //$arrObjTipoAcessoFederacaoDTO = InfraArray::indexarArrInfraDTO($objAcessoFederacaoRN->listarValoresTipo(),'StaTipo');

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Envios.';
    $strCaptionTabela = 'Envios';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%" style="display:none;" rowspan="2">'.PaginaSEI::getInstance()->getThCheck('','Infra','style="display:none;"').'</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="15%">Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" colspan="3">De</th>'."\n";
    $strResultado .= '<th class="infraTh" colspan="3">Para</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2" width="15%">Envio</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2" width="15%">Cancelamento</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="10%">Órgão</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Instalação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Órgão</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Instalação</th>'."\n";
    $strResultado .= '</tr>'."\n";

    $strCssTr='';

    $n = 0;

    foreach($arrObjAcessoFederacaoDTO as $objAcessoFederacaoDTO){

      if ($objAcessoFederacaoDTO->getStrSinAtivo()=='S'){
        $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      $strResultado .= "\n".'<td valign="top" style="display:none;">';
      $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++,$objAcessoFederacaoDTO->getStrIdAcessoFederacao(),$objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoRem(),'N','Infra','style="visibility:hidden;"');
      $strResultado .= '</td>';

      //$strResultado .= "\n".'<td align="center"  valign="top">'.PaginaSEI::tratarHTML($arrObjTipoAcessoFederacaoDTO[$objAcessoFederacaoDTO->getNumStaTipo()]->getStrDescricao()).'</td>';

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoRem()).'</a>';
      $strResultado .= '</td>'."\n";

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoRem()).'</a>';
      $strResultado .= '</td>'."\n";

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoRem()).'</a>';
      $strResultado .= '</td>'."\n";

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoDest()).'</a>';
      $strResultado .= '</td>'."\n";

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoDest()).'</a>';
      $strResultado .= '</td>'."\n";

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoDest()).'</a>';
      $strResultado .= '</td>'."\n";

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= PaginaSEI::tratarHTML(substr($objAcessoFederacaoDTO->getDthLiberacao(),0,16));
      $strResultado .= '</td>'."\n";

      $strResultado .= "\n".'<td align="center"  valign="top">';
      if ($objAcessoFederacaoDTO->getDthCancelamento()==null){
        $strResultado .= '&nbsp';
      }else{
        $strResultado .= PaginaSEI::tratarHTML(substr($objAcessoFederacaoDTO->getDthCancelamento(),0,16));
      }
      $strResultado .= '</td>'."\n";

			$strResultado .= '<td align="center" valign="top">';

      if ($objAcessoFederacaoDTO->getStrMotivoLiberacao()!=null) {
        $strResultado .= '<a href="javascript:void(0)" '.PaginaSEI::montarTitleTooltip($objAcessoFederacaoDTO->getStrMotivoLiberacao(),'Motivo do Envio').'><img src="'.Icone::FEDERACAO_ACESSO_LIBERACAO.'" class="infraImg" /></a>';
      }

      if ($objAcessoFederacaoDTO->getStrMotivoCancelamento()!=null) {
        $strResultado .= '<a href="javascript:void(0)" '.PaginaSEI::montarTitleTooltip($objAcessoFederacaoDTO->getStrMotivoCancelamento(),'Motivo do Cancelamento').'><img src="'.Icone::FEDERACAO_ACESSO_CANCELAMENTO.'" class="infraImg" /></a>';
      }

      if ($bolAcaoCancelarEnvio && $objAcessoFederacaoDTO->getStrIdUnidadeFederacaoRem() == $objUnidadeDTO->getStrIdUnidadeFederacao() && $objAcessoFederacaoDTO->getStrSinAtivo()=='S'){
		    $strResultado .= '<a href="#ID-'.$objAcessoFederacaoDTO->getStrIdAcessoFederacao().'"  onclick="acaoCancelarEnvio(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_federacao_cancelar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_acesso_federacao='.$objAcessoFederacaoDTO->getStrIdAcessoFederacao()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeRemover().'" title="Cancelar Envio" alt="Cancelar Envio" class="infraImg" /></a>';
      }else{
      	$strResultado .= '&nbsp;';
      }
			$strResultado .= '</td>';


      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&montar_visualizacao=0');

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
  parent.parent.parent.infraOcultarAviso(false);

  <?if (($_GET['acao_origem']=='acesso_federacao_enviar' || $_GET['acao_origem']=='acesso_federacao_cancelar') && $_GET['resultado']=='1') { ?>
    parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>

  infraEfeitoTabelas();
}

<? if ($bolAcaoCancelarEnvio){ ?>
function acaoCancelarEnvio(link){
  parent.infraAbrirJanelaModal(link,600,250);
}
<? } ?>

function OnSubmitForm(){
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcessoFederacaoGerenciar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>

  <input type="hidden" id="hdnFlag" name="hdnFlag" value="0" />

  <br />
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
	PaginaSEI::getInstance()->montarAreaDebug();
	//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>