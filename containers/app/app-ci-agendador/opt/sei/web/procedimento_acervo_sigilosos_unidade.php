<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/06/2016 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
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

  $strParametros = '';
  if (isset($_GET['acesso'])){
    $strParametros .= '&acesso='.$_GET['acesso'];
  }

  $bolGeracaoOK = false;

  switch($_GET['acao']){

    case 'procedimento_credencial_cancelar':

      $arrIdProcedimento = PaginaSEI::getInstance()->getArrStrItensSelecionados();

      try{
        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->cancelarCredenciais(InfraArray::gerarArrInfraDTO('ProcedimentoDTO','IdProcedimento', $arrIdProcedimento));
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }

      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrIdProcedimento)));
      die;

    case 'procedimento_acervo_sigilosos_unidade':
      $strTitulo = 'Acervo de Processos Sigilosos da Unidade';
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  if ($_GET['acesso']=='1') {

    $arrComandos[] = '<button type="submit" accesskey="S" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
    $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnPesquisar" onclick="limpar();" value="Limpar" class="infraButton"><span class="infraTeclaAtalho">L</span>impar</button>';

    $objPesquisaSigilosoDTO = new PesquisaSigilosoDTO();
    $objPesquisaSigilosoDTO->retDtaGeracao();
    $objPesquisaSigilosoDTO->retStrProtocoloFormatado();
    $objPesquisaSigilosoDTO->retStrNomeTipoProcedimento();
    $objPesquisaSigilosoDTO->setStrStaAcessoUnidade(ProtocoloRN::$TASU_TODOS);
    //$objPesquisaSigilosoDTO->setStrStaAcessoUnidade(ProtocoloRN::$TASU_NAO);
    //$objPesquisaSigilosoDTO->setStrStaAcessoUnidade(ProtocoloRN::$TASU_SIM);
    $objPesquisaSigilosoDTO->setStrSinObservacoes('S');
    $objPesquisaSigilosoDTO->setStrSinAcompanhamentos('S');

    $objPesquisaSigilosoDTO->setStrSinFiltroProtocolo('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroTipoProcedimento('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroInteressado('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroObservacoes('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroUsuarioCredencial('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroPeriodoAutuacao('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroTramitacaoUnidade('S');

    ProcedimentoINT::montarCamposPesquisaSigiloso($objPesquisaSigilosoDTO, $strCssSigilosos, $strJsSigilosos, $strJsInicializarSigilosos, $strJsValidarSigilosos, $strHtmlSigilosos);

    PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaSigilosoDTO, 'Geracao', InfraDTO::$TIPO_ORDENACAO_DESC);

    PaginaSEI::getInstance()->prepararPaginacao($objPesquisaSigilosoDTO,1000);

    try{
      $objProcedimentoRN = new ProcedimentoRN();
      $arrObjProcedimentoDTO = $objProcedimentoRN->pesquisarAcervoSigilososUnidade($objPesquisaSigilosoDTO);
    }catch(Exception $e){
      PaginaSEI::getInstance()->processarExcecao($e);
    }

    PaginaSEI::getInstance()->processarPaginacao($objPesquisaSigilosoDTO);

    $numRegistros = count($arrObjProcedimentoDTO);

    if ($numRegistros) {

      $bolAcaoCredencialAtivar = SessaoSEI::getInstance()->verificarPermissao('procedimento_credencial_ativar');
      $bolAcaoCredencialCancelar = SessaoSEI::getInstance()->verificarPermissao('procedimento_credencial_cancelar');

      if ($bolAcaoCredencialAtivar) {
        $strLinkCredencialAtivar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_ativar&acao_origem=' . $_GET['acao'] . $strParametros);
      }

      if ($bolAcaoCredencialCancelar) {
        $strLinkCredencialCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_cancelar&acao_origem=' . $_GET['acao'] . $strParametros);
      }

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Processos.';
      $strCaptionTabela = 'Processos';
      $strResultado .= '<table id="tblProcessos" width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th rowspan="2" class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th rowspan="2" class="infraTh">Processo</th>' . "\n";
      $strResultado .= '<th rowspan="2" class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaSigilosoDTO,'Autuação','Geracao',$arrObjProcedimentoDTO).'</th>' . "\n";
      $strResultado .= '<th rowspan="2" class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaSigilosoDTO,'Tipo','NomeTipoProcedimento',$arrObjProcedimentoDTO).'</th>' . "\n";
      $strResultado .= '<th rowspan="2" class="infraTh">Observações da Unidade</th>' . "\n";
      $strResultado .= '<th colspan="4" class="infraTh">Acompanhamento Especial</th>' . "\n";
      $strResultado .= '<th rowspan="2" class="infraTh">Credenciais na Unidade</th>' . "\n";
      $strResultado .= '<th rowspan="2" class="infraTh" width="5%">Ações</th>' . "\n";
      $strResultado .= '</tr>' . "\n";

      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="8%">Data</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="8%">Usuário</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="8%">Grupo</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="8%">Observações</th>' . "\n";
      $strResultado .= '</tr>' . "\n";

      $strCssTr = '';

      $bolBotaoAtivarCredencial = false;
      $bolBotaoCancelarCredenciais = false;
      for ($i = 0; $i < $numRegistros; $i++) {

        $dblIdProcedimento = $arrObjProcedimentoDTO[$i]->getDblIdProcedimento();

        $arrObjAcessoDTO = $arrObjProcedimentoDTO[$i]->getArrObjAcessoDTO();

        $numAcompanhamentos = 0;
        $arrObjAcompanhamentoDTO = $arrObjProcedimentoDTO[$i]->getArrObjAcompanhamentoDTO();
        if ($arrObjAcompanhamentoDTO != null) {
          $numAcompanhamentos = count($arrObjAcompanhamentoDTO);
        }

        $strRowSpan = '';
        if ($numAcompanhamentos > 1){
          $strRowSpan = 'rowspan="'.$numAcompanhamentos.'"';
        }

        $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
        $strResultado .= '<tr name="trProcesso'.$dblIdProcedimento.'" '.$strCssTr.'>'."\n";

        $bolCredencialAtiva = false;
        $bolCredencialInativa = false;
        $bolAcessoPessoal = false;
        $strAcessos = '';
        foreach ($arrObjAcessoDTO as $objAcessoDTO) {

          if ($strAcessos != '') {
            $strAcessos .= '<br/>';
          }

          $strAcessos .= '<span class="iconeLegenda" style="color:';
          if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_FINALIZADA) {

            $strAcessos .= 'black;">&#9675;';

          } else if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_INATIVA) {

            $bolCredencialInativa = true;

            $strAcessos .= 'red;">&#9679;';

          } else if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_ATIVA) {

            $strAcessos .= 'green;">&#9679;';

            $bolCredencialAtiva = true;

            if ($objAcessoDTO->getNumIdUsuario() == SessaoSEI::getInstance()->getNumIdUsuario()) {
              $bolAcessoPessoal = true;
            }
          }
          $strAcessos .= '</span>';
          $strAcessos .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoDTO->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($objAcessoDTO->getStrNomeUsuario()).'" class="ancoraSigla textoLegenda">'.PaginaSEI::tratarHTML($objAcessoDTO->getStrSiglaUsuario()).'</a>';
        }

        $bolExibeAcaoCancelar = ($bolAcaoCredencialCancelar && $bolCredencialAtiva && $bolCredencialInativa);

        $strAtributosCheck = '';
        if (!$bolAcaoCredencialAtivar && !$bolExibeAcaoCancelar) {
          $strAtributosCheck = 'disabled="disabled" style="display:none"';
        }

        $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.'>'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjProcedimentoDTO[$i]->getDblIdProcedimento(), $arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado(), 'N', 'Infra', $strAtributosCheck).'</td>'."\n";

        if ($bolAcessoPessoal) {
          $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.' align="center"><a style="text-decoration:underline" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjProcedimentoDTO[$i]->getDblIdProcedimento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'" title="'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</a></td>'."\n";
        } else {
          $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.' align="center">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</td>'."\n";
        }

        $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.' align="center">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getDtaGeracaoProtocolo()).'</td>'."\n";
        $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.' align="center">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'</td>'."\n";

        $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.' align="left" valign="top">';
        if ($arrObjProcedimentoDTO[$i]->getObjObservacaoDTO() == null) {
          $strResultado .= '&nbsp;';
        } else {
          $strResultado .= nl2br(PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getObjObservacaoDTO()->getStrDescricao()));
        }
        $strResultado .= '</td>'."\n";

        $strResultado .= AcompanhamentoINT::montarTDsGrupoObservacao($arrObjAcompanhamentoDTO, $numAcompanhamentos, 0, 'name="tdProcesso'.$dblIdProcedimento.'"');

        $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.' align="left">' . ($strAcessos == '' ? '&nbsp;' : $strAcessos) . '</td>' . "\n";
        $strResultado .= '<td name="tdProcesso'.$dblIdProcedimento.'" '.$strRowSpan.' align="center">';

        if ($bolAcaoCredencialAtivar || $bolExibeAcaoCancelar) {
          $strId = $arrObjProcedimentoDTO[$i]->getDblIdProcedimento();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado());
        }

        if ($bolAcaoCredencialAtivar) {
          $bolBotaoAtivarCredencial = true;
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);acaoAtivarCredencial(\'' . $strId . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . Icone::CREDENCIAL_ATIVAR . '" title="Ativar Credencial na Unidade" alt="Ativar Credencial na Unidade" class="infraImg" /></a>&nbsp;';
        }

        if ($bolExibeAcaoCancelar) {
          $bolBotaoCancelarCredenciais = true;
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);acaoCancelarCredenciais(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . Icone::CREDENCIAL_CANCELAR . '" title="Cancelar Credenciais Inativas na Unidade" alt="Cancelar Credenciais Inativas na Unidade" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td>' . "\n";
        $strResultado .= '</tr>' . "\n";

        for ($j=1;$j<$numAcompanhamentos;$j++){
          $strResultado .= '<tr name="trProcesso'.$dblIdProcedimento.'" '.$strCssTr.'>';
          $strResultado .= AcompanhamentoINT::montarTDsGrupoObservacao($arrObjAcompanhamentoDTO, $numAcompanhamentos, $j, 'name="tdProcesso'.$dblIdProcedimento.'"');
          $strResultado .= '</tr>'."\n";
        }
      }
      $strResultado .= '</table>' . "\n";

      if ($bolBotaoAtivarCredencial) {
        $arrComandos[] = '<button type="button" id="btnAtivarCredencial" value="Ativar Credencial" onclick="acaoAtivarCredencialMultipla();" class="infraButton">Ativar Credencial</button>';
      }

      if ($bolBotaoCancelarCredenciais) {
        $arrComandos[] = '<button type="button" id="btnCancelarCredenciais" value="Cancelar Credenciais" onclick="acaoCancelarCredenciaisMultipla();" class="infraButton">Cancelar Credenciais Inativas</button>';
      }

      $arrComandos[] = '<button type="button" accesskey="G" name="btnGerar" value="Gerar" onclick="gerar();" class="infraButton"><span class="infraTeclaAtalho">G</span>erar Planilha</button>';

      $strLegenda = '<label id="lblLegenda" class="infraLabelOpcional">Legenda:</label>
                     <div id="divLegenda1"><span class="iconeLegenda" style="color:green;">&#9679;</span><span class="textoLegenda">Credencial ativa</span></div>
                     <div id="divLegenda2"><span class="iconeLegenda" style="color:red;">&#9679;</span><span class="textoLegenda">Credencial inativa (sem permissão na unidade)</span></div>
                     <div id="divLegenda3"><span class="iconeLegenda" style="color:black;">&#9675;</span><span class="textoLegenda">Credencial finalizada (renúncia / cassação / anulação / cancelamento)</span></div>';

      if ($_POST['hdnFlagGerar']=='1'){
        try{

          $objAnexoRN = new AnexoRN();
          $strArquivoTemp = $objAnexoRN->gerarNomeArquivoTemporario().'.csv';

          $strCsv = 'Processo;Autuação;Tipo;Observações da Unidade;Acompanhamentos Especiais;Credenciais na Unidade'."\n";

          for ($i = 0; $i < $numRegistros; $i++) {

            if (in_array($arrObjProcedimentoDTO[$i]->getDblIdProcedimento(), PaginaSEI::getInstance()->getArrStrItensSelecionados())) {

              $strCsv .= $arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado().';';
              $strCsv .= $arrObjProcedimentoDTO[$i]->getDtaGeracaoProtocolo().';';
              $strCsv .= '"'.str_replace('"', "\"\"", $arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'";';

              if ($arrObjProcedimentoDTO[$i]->getObjObservacaoDTO() != null) {
                $strCsv .= '"'.str_replace('"', "\"\"", $arrObjProcedimentoDTO[$i]->getObjObservacaoDTO()->getStrDescricao()).'"';
              }
              $strCsv .= ';';

              $arrObjAcompanhamentoDTO = $arrObjProcedimentoDTO[$i]->getArrObjAcompanhamentoDTO();
              $strAcompanhamentos = '';
              if ($arrObjAcompanhamentoDTO != null) {

                foreach ($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO) {

                  if ($strAcompanhamentos != '') {
                    $strAcompanhamentos .= "\n";
                  }

                  $strAcompanhamentos .= substr($objAcompanhamentoDTO->getDthAlteracao(),0,16);
                  $strAcompanhamentos .= ' ('.$objAcompanhamentoDTO->getStrSiglaUsuario().') ';

                  if ($objAcompanhamentoDTO->getStrNomeGrupo() != null) {
                    $strAcompanhamentos .= ' - '.$objAcompanhamentoDTO->getStrNomeGrupo();
                  }

                  if ($objAcompanhamentoDTO->getStrObservacao() != null) {
                    $strAcompanhamentos .= ': '.$objAcompanhamentoDTO->getStrObservacao();
                  }
                }
              }
              $strCsv .= '"'.str_replace('"', "\"\"", $strAcompanhamentos).'";';

              $arrObjAcessoDTO = $arrObjProcedimentoDTO[$i]->getArrObjAcessoDTO();
              $strAcessos = '';
              foreach ($arrObjAcessoDTO as $objAcessoDTO) {

                if ($strAcessos != '') {
                  $strAcessos .= "\n";
                }

                $strAcessos .= $objAcessoDTO->getStrSiglaUsuario();

                if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_FINALIZADA) {
                  $strAcessos .= ' (finalizada)';
                } else if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_INATIVA) {
                  $strAcessos .= ' (inativa)';
                } else if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_ATIVA) {
                  $strAcessos .= ' (ativa)';
                }
              }
              $strCsv .= '"'.str_replace('"', "\"\"", $strAcessos).'"'."\n";
            }
          }

          if (file_put_contents(DIR_SEI_TEMP.'/'.$strArquivoTemp, $strCsv) === false) {
            throw new InfraException('Erro criando arquivo CSV temporário.');
          }

          $strNomeDownload = 'SEI-Acervo-Unidade-'.str_replace(array('/',' ',':'),'-',InfraData::getStrDataHoraAtual()).'.csv';

          $bolGeracaoOK = true;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

    }
  }

  $strLinkAcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_validar_acesso&acao_origem='.$_GET['acao'].'&acao_destino=procedimento_acervo_sigilosos_unidade&acao_negado=procedimento_controlar');

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
<?=$strCssSigilosos;?>

#lblLegenda {position:absolute;left:0%;top:0%;width:18%;}
#divLegenda1 {position:absolute;left:18%;top:0%;width:60%;}
#divLegenda2 {position:absolute;left:18%;top:30%;width:60%;}
#divLegenda3 {position:absolute;left:18%;top:60%;width:60%;}

.iconeLegenda {
margin:0;
border:0;
padding:0 .1em 0 0;
display:inline-table;
font-size:20px;
}

.textoLegenda{
font-size:1.2em;
line-height:16px;
vertical-align:text-bottom;
padding-left:5px;
}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

<?=$strJsSigilosos;?>

function inicializar(){

  if ('<?=$_GET['acesso']?>'!='1'){
    infraAbrirJanelaModal('<?=$strLinkAcesso?>',500,300,true,'finalizar');
    return;
  }

  <?if ($bolGeracaoOK){ ?>
    window.open('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=exibir_arquivo&nome_arquivo='.$strArquivoTemp.'&nome_download='.InfraUtil::formatarNomeArquivo($strNomeDownload).'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);?>');
  <?}?>

  <?=$strJsInicializarSigilosos;?>

  infraOcultarMenuSistemaEsquema();

  prepararTrs();

  //infraEfeitoTabelas();
}

function prepararTrs(){

  var i;
  var tab = document.getElementById('tblProcessos');

  if (tab != null){

    var trs = tab.getElementsByTagName("tr");

    for(i=0;i < trs.length;i++){

        if (i) {
          trs[i].onmarcada = function () {
            var arrTr = document.getElementsByName(this.getAttribute('name'));
            for (var j = 0; j<arrTr.length; j++) {
              $(arrTr[j]).removeClass("infraTrSelecionada");
              $(arrTr[j]).addClass("infraTrMarcada");
            }
          };

          trs[i].ondesmarcada = function () {
            var arrTr = document.getElementsByName(this.getAttribute('name'));
            for (var j = 0; j<arrTr.length; j++) {
              $(arrTr[j]).removeClass("infraTrMarcada");
              $(arrTr[j]).removeClass("infraTrAcessada");
              $(arrTr[j]).removeClass("infraTrSelecionada");
            }
          };

          trs[i].onacessada = function () {
            var arrTr = document.getElementsByName(this.getAttribute('name'));
            for (var j = 0; j<arrTr.length; j++) {
              $(arrTr[j]).removeClass("infraTrSelecionada");
              $(arrTr[j]).removeClass("infraTrMarcada");
              $(arrTr[j]).addClass("infraTrAcessada");
            }
          };

          trs[i].onmouseover = function () {
            var arrTr = document.getElementsByName(this.getAttribute('name'));
            for (var j = 0; j<arrTr.length; j++) {
              $(arrTr[j]).addClass("infraTrSelecionada");
            }
          };

          trs[i].onmouseout = function () {
            var arrTr = document.getElementsByName(this.getAttribute('name'));
            for (var j = 0; j<arrTr.length; j++) {
              $(arrTr[j]).removeClass("infraTrSelecionada");
            }
          };
        }
    }
  }
}


function onSubmitForm(){
  <?=$strJsValidarSigilosos;?>
  return true;
}

<?if ($bolAcaoCredencialAtivar) {?>

function acaoAtivarCredencial(id){

  infraAbrirJanela('<?=$strLinkCredencialAtivar?>','janelaAtivarCredencial',700,250,'location=0,status=1,resizable=1,scrollbars=1');
  
  document.getElementById('hdnInfraItemId').value=id;

  var actionAnterior = document.getElementById('frmProcedimentoAcervoSigilososUnidade').action;
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').target='janelaAtivarCredencial';
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').action='<?=$strLinkCredencialAtivar?>';
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').submit();
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').action=actionAnterior;
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').target='_self';
}

function acaoAtivarCredencialMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo selecionado.');
    return;
  }
  
  infraAbrirJanela('<?=$strLinkCredencialAtivar?>','janelaAtivarCredencial',700,250,'location=0,status=1,resizable=1,scrollbars=1');
  
  document.getElementById('hdnInfraItemId').value='';

  var actionAnterior = document.getElementById('frmProcedimentoAcervoSigilososUnidade').action;
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').target='janelaAtivarCredencial';
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').action='<?=$strLinkCredencialAtivar?>';
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').submit();
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').action=actionAnterior;
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').target='_self';
}
<?}?>

<? if ($bolAcaoCredencialCancelar){ ?>
function acaoCancelarCredenciais(id, desc){
  if (confirm("Confirma cancelamento das credenciais inativas do processo \""+desc+"\" nesta unidade?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProcedimentoAcervoSigilososUnidade').action='<?=$strLinkCredencialCancelar?>';
    document.getElementById('frmProcedimentoAcervoSigilososUnidade').submit();
  }
}

function acaoCancelarCredenciaisMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo selecionado.');
    return;
  }
  if (confirm("Confirma cancelamento das credenciais inativas dos processos selecionados nesta unidade?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProcedimentoAcervoSigilososUnidade').action='<?=$strLinkCredencialCancelar?>';
    document.getElementById('frmProcedimentoAcervoSigilososUnidade').submit();
  }
}
<? } ?>

function gerar() {

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo selecionado.');
    return;
  }

  infraExibirAviso(false);

  document.getElementById('hdnFlagGerar').value = '1';
  document.getElementById('frmProcedimentoAcervoSigilososUnidade').submit();
}


//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoAcervoSigilososUnidade" onsubmit="return onSubmitForm()" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados();
  echo $strHtmlSigilosos;
  PaginaSEI::getInstance()->fecharAreaDados();
  if ($strLegenda!='') {
    PaginaSEI::getInstance()->abrirAreaDados('8em');
    echo $strLegenda;
    PaginaSEI::getInstance()->fecharAreaDados();
  }
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  <input type="hidden" id="hdnFlagGerar" name="hdnFlagGerar" value="0" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>