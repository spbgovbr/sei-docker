<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
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

  PaginaSEI::getInstance()->prepararSelecao('pesquisa_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'pesquisa_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjPesquisaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objPesquisaDTO = new PesquisaDTO();
          $objPesquisaDTO->setNumIdPesquisa($arrStrIds[$i]);
          $arrObjPesquisaDTO[] = $objPesquisaDTO;
        }
        $objPesquisaRN = new PesquisaRN();
        $objPesquisaRN->excluir($arrObjPesquisaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/*
    case 'pesquisa_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjPesquisaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objPesquisaDTO = new PesquisaDTO();
          $objPesquisaDTO->setNumIdPesquisa($arrStrIds[$i]);
          $arrObjPesquisaDTO[] = $objPesquisaDTO;
        }
        $objPesquisaRN = new PesquisaRN();
        $objPesquisaRN->desativar($arrObjPesquisaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'pesquisa_reativar':
      $strTitulo = 'Reativar Pesquisas';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjPesquisaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objPesquisaDTO = new PesquisaDTO();
            $objPesquisaDTO->setNumIdPesquisa($arrStrIds[$i]);
            $arrObjPesquisaDTO[] = $objPesquisaDTO;
          }
          $objPesquisaRN = new PesquisaRN();
          $objPesquisaRN->reativar($arrObjPesquisaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

 */
    case 'pesquisa_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Pesquisa','Selecionar Pesquisas');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='pesquisa_cadastrar'){
        if (isset($_GET['id_pesquisa'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_pesquisa']);
        }
      }
      break;

    case 'pesquisa_listar':
      $strTitulo = 'Pesquisas';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  //if ($_GET['acao'] == 'pesquisa_selecionar'){
  //  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="selecionarPesquisa();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  //}

  /* if ($_GET['acao'] == 'pesquisa_listar' || $_GET['acao'] == 'pesquisa_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_cadastrar');
    if ($bolAcaoCadastrar){
     // $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */

  $objPesquisaDTO = new PesquisaDTO();
  $objPesquisaDTO->retNumIdPesquisa();
  $objPesquisaDTO->retStrNome();
  $objPesquisaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

/*
  if ($_GET['acao'] == 'pesquisa_reativar'){
    //Lista somente inativos
    $objPesquisaDTO->setBolExclusaoLogica(false);
    $objPesquisaDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objPesquisaDTO);

  $objPesquisaRN = new PesquisaRN();
  $arrObjPesquisaDTO = $objPesquisaRN->listar($objPesquisaDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objPesquisaDTO);
  $numRegistros = count($arrObjPesquisaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='pesquisa_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('pesquisa_excluir');
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='pesquisa_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('pesquisa_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('pesquisa_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('pesquisa_desativar');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='pesquisa_reativar'){ */
      $strSumarioTabela = 'Tabela de Pesquisas.';
      $strCaptionTabela = 'Pesquisas';
    /* }else{
      $strSumarioTabela = 'Tabela de Pesquisas Inativas.';
      $strCaptionTabela = 'Pesquisas Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaDTO,'Nome','Nome',$arrObjPesquisaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $strFormularios = "";

    $objCampoPesquisaRN = new CampoPesquisaRN();
    for($i = 0;$i < $numRegistros; $i++){
      $objCampoPesquisaDTO = new CampoPesquisaDTO();
      $objCampoPesquisaDTO->retStrValor();
      $objCampoPesquisaDTO->retNumChave();
      $objCampoPesquisaDTO->setNumIdPesquisa($arrObjPesquisaDTO[$i]->getNumIdPesquisa());

      $arrObjCampoPesquisaDTO = $objCampoPesquisaRN->listar($objCampoPesquisaDTO);
      $arrCampoPesquisa = array();
      if(InfraArray::contar($arrObjCampoPesquisaDTO) > 0){
        $arrCampoPesquisa = InfraArray::indexarArrInfraDTO($arrObjCampoPesquisaDTO,"Chave", true);
      }

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjPesquisaDTO[$i]->getNumIdPesquisa(),$arrObjPesquisaDTO[$i]->getStrNome(),'N','Infra','onclick="selecionarPesquisa('.$arrObjPesquisaDTO[$i]->getNumIdPesquisa().')"').'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjPesquisaDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td align="center">';
      $strLink = SessaoSEI::getInstance()->assinarLink("controlador.php?acao=protocolo_pesquisar&acao_origem=protocolo_pesquisar");
      $strOrgao = "";

      if (isset($arrCampoPesquisa[CampoPesquisaRN::$CP_SIN_RESTRINGIR_ORGAO]) && $arrCampoPesquisa[CampoPesquisaRN::$CP_SIN_RESTRINGIR_ORGAO][0]->getStrValor()=='S'){
        $strOrgao .= '<input type="hidden" name="selOrgaoPesquisa[]" value="'.SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual().'"/>';
      }else if(InfraArray::contar($arrCampoPesquisa[CampoPesquisaRN::$CP_ID_ORGAO]) > 0){
        foreach ($arrCampoPesquisa[CampoPesquisaRN::$CP_ID_ORGAO] as $iOrgao => $objOrgao) {
          $strOrgao .= '<input type="hidden" name="selOrgaoPesquisa[]" value="'.$objOrgao->getStrValor().'"/>';
        }
      }else{
        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->retNumIdOrgao();
        $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objOrgaoRN = new OrgaoRN();
        $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

        foreach ($arrObjOrgaoDTO as $objOrgao) {
          $strOrgao .= '<input type="hidden" name="selOrgaoPesquisa[]" value="'.$objOrgao->getNumIdOrgao().'"/>';
        }
      }
      $strFormularios .= '
        <form name="formularioPesquisa'.$arrObjPesquisaDTO[$i]->getNumIdPesquisa().'" id="formularioPesquisa'.$arrObjPesquisaDTO[$i]->getNumIdPesquisa().'" target="telaPesquisa" method="post" action="'.$strLink.'" >
          <input type="hidden" name="hdnInicio" value="0"/>
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_PESQUISAR_EM, "rdoPesquisarEm",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_DATA_FIM, "txtDataFim",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_DATA_INICIO, "txtDataInicio",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_SIN_DATA, "selData",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_NOME_ARVORE_DOCUMENTO_PESQUISA, "txtNomeArvoreDocumentoPesquisa",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_NUMERO_DOCUMENTO_PESQUISA, "txtNumeroDocumentoPesquisa",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_SERIE_PESQUISA, "selSeriePesquisa",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_TIPO_PROCEDIMENTO_PESQUISA, "selTipoProcedimentoPesquisa",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_PROTOCOLO_PESQUISA, "txtProtocoloPesquisa",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_UNIDADE, "hdnIdUnidade",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_ASSUNTO, "hdnIdAssunto",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_OBSERVACAO_PESQUISA, "txtObservacaoPesquisa",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_DESCRICAO_PESQUISA, "txtDescricaoPesquisa",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_ASSINANTE, "hdnIdAssinante",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_SIN_DESTINATARIO, "chkSinDestinatario",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_SIN_REMETENTE, "chkSinRemetente",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_SIN_INTERESSADO, "chkSinInteressado",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_CONTATO, "hdnIdContato",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TEXTO_PESQUISA, "q",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_SIN_TRAMITACAO, "chkSinTramitacao",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_DOCUMENTOS_RECEBIDOS, "chkSinDocumentosRecebidos",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_DOCUMENTOS_GERADOS, "chkSinDocumentosGerados",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_USUARIO_GERADOR1, "hdnIdUsuarioGerador1",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_USUARIO_GERADOR2, "hdnIdUsuarioGerador2",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_ID_USUARIO_GERADOR3, "hdnIdUsuarioGerador3",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TXT_USUARIO_GERADOR1, "txtUsuarioGerador1",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TXT_USUARIO_GERADOR2, "txtUsuarioGerador2",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TXT_USUARIO_GERADOR3, "txtUsuarioGerador3",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TXT_UNIDADE, "txtUnidade",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TXT_ASSUNTO, "txtAssunto",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TXT_ASSINANTE, "txtAssinante",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_TXT_CONTATO, "txtContato",$arrCampoPesquisa).'
          '.CampoPesquisaINT::montarInput(CampoPesquisaRN::$CP_SIN_RESTRINGIR_ORGAO, "chkSinRestringirOrgao",$arrCampoPesquisa).'
          '.$strOrgao.'
        </form>
      ';
      //$strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjPesquisaDTO[$i]->getNumIdPesquisa());

      //$strResultado .= '<a href="#" onclick="selecionarPesquisa('.$arrObjPesquisaDTO[$i]->getNumIdPesquisa().')"  ><img src="'.PaginaSEI::getInstance()->getIconeTransportar().'" title="Selecionar esta Pesquisa" alt="Selecionar esta Pesquisa" class="infraImg"></a>';

      if ($bolAcaoConsultar){
        //$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_pesquisa='.$arrObjPesquisaDTO[$i]->getNumIdPesquisa()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Pesquisa" alt="Consultar Pesquisa" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_pesquisa='.$arrObjPesquisaDTO[$i]->getNumIdPesquisa()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Pesquisa" alt="Alterar Pesquisa" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjPesquisaDTO[$i]->getNumIdPesquisa();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjPesquisaDTO[$i]->getStrNome());
      }
/*
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Pesquisa" alt="Desativar Pesquisa" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Pesquisa" alt="Reativar Pesquisa" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Pesquisa" alt="Excluir Pesquisa" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'pesquisa_selecionar'){
    //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="infraFecharJanelaModal();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

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

  tr.infraTrClara, tr.infraTrEscura{
    cursor:pointer;
  }

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>


  function selecionarPesquisa(numIdPesquisa){
    if(!numIdPesquisa){
      numIdPesquisa = $('input[name=chkInfraItem]:checked', '#frmPesquisaLista').val();
      if(!numIdPesquisa){
        alert("Nenhuma pesquisa selecionada.");
        return;
      }
    }

    window.parent.name = "telaPesquisa";
    $("#formularioPesquisa"+numIdPesquisa).submit();
    infraFecharJanelaModal();
  }

function inicializar(){
  if ('<?=$_GET['acao']?>'=='pesquisa_selecionar'){
    infraReceberSelecao();
    //document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Pesquisa \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmPesquisaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmPesquisaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Pesquisa selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Pesquisas selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmPesquisaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmPesquisaLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPesquisaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
echo $strFormularios;
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
