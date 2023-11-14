<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //if (SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario()=='XXXX' && SessaoSEI::getInstance()->getStrSiglaUsuario()=='xxxx'){
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
  //}

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarLink();

  //SessaoSEI::getInstance()->validarAuditarPermissao($_GET['acao']);

  //$numSegOrig = $numSeg = InfraUtil::verificarTempoProcessamento();

  switch($_GET['acao']){

    case 'procedimento_paginar':
      try{

        if (!isset($_POST['hdnProtocolos'])){
          die;
        }

        $strNos = '';
        $strNosAcao = '';

        $numNo = 0;
        $numNoAcao = 0;

        if (md5($_POST['hdnProtocolos']) != $_GET['pagina_hash']){
          throw new InfraException('Conjunto de protocolos inválido ['.substr($_POST['hdnProtocolos'],0,10).'...].');
        }

        ProtocoloINT::montarAcoesArvore($_GET['id_procedimento'],
          $_GET['id_unidade'],
          $_GET['flag_aberto'],
          $_GET['flag_anexado'],
          $_GET['flag_aberto_anexado'],
          $_GET['flag_protocolo'],
          $_GET['flag_arquivo'],
          $_GET['flag_tramitacao'],
          $_GET['flag_sobrestado'],
          $_GET['flag_bloqueado'],
          $_GET['codigo_acesso'],
          $_GET['no_pai'],
          explode(',',$_POST['hdnProtocolos']),
          $numNo, $strNos,
          $numNoAcao, $strNosAcao);

        die('OK <!--//--><![CDATA[//><!--'."\n".$strNos."\n".$strNosAcao."\n".'//--><!]]>');

      }catch(Exception $e){

        if ($e instanceof InfraException && $e->contemValidacoes()){
          die("INFRA_VALIDACAO\n".$e->__toString()); //retorna para o iframe exibir o alert
        }

        PaginaSEI::getInstance()->processarExcecao($e); //vai para a página de erro padrão
      }

      break;

    case 'procedimento_visualizar':

      $strTitulo = 'Árvore Montar';

      $objAuditoriaProtocoloDTO = new AuditoriaProtocoloDTO();
      $objAuditoriaProtocoloDTO->setStrRecurso($_GET['acao']);
      $objAuditoriaProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAuditoriaProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);
      $objAuditoriaProtocoloDTO->setNumIdAnexo(null);
      $objAuditoriaProtocoloDTO->setDtaAuditoria(InfraData::getStrDataAtual());
      $objAuditoriaProtocoloDTO->setNumVersao(null);

      $objAuditoriaProtocoloRN = new AuditoriaProtocoloRN();
      $objAuditoriaProtocoloRN->auditarVisualizacao($objAuditoriaProtocoloDTO);

      /*
      if ($_GET['acao_origem']!='procedimento_trabalhar' &&
          $_GET['acao_origem']!='procedimento_visualizar' &&
          $_GET['acao_origem']!='arvore_visualizar' &&
          $_GET['acao_origem']!='documento_assinar' &&
          $_GET['acao_origem']!='editor_montar' &&
      		$_GET['acao_origem']!='distribuicao_gerar' &&
          $_GET['acao_origem']!='item_sessao_julgamento_cadastrar' &&
        	$_GET['acao_origem']!='procedimento_relacionar' &&
        	$_GET['acao_origem']!='procedimento_anexar' &&
        	$_GET['acao_origem']!='documento_mover' &&
        	$_GET['acao_origem']!='procedimento_excluir_relacionamento' &&
        	$_GET['acao_origem']!='publicacao_cancelar_agendamento' &&
        	$_GET['acao_origem']!='procedimento_credencial_gerenciar' &&
        	$_GET['acao_origem']!='procedimento_credencial_conceder'){
      	throw new InfraException('Erro no acesso ao processo ['.$_GET['acao_origem'].'].');
      }
      */

      $numIdUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();

      $bolAcaoProcedimentoReceber = SessaoSEI::getInstance()->verificarPermissao('procedimento_receber');
      $bolAcaoProcedimentoLinhaDireta = SessaoSEI::getInstance()->verificarPermissao('procedimento_linha_direta');

      $dblIdProcedimento = $_GET['id_procedimento'];

      $dblIdProtocoloPosicionar = '';
      if (isset($_GET['id_documento']) && $_GET['id_documento']!=''){
        $dblIdProtocoloPosicionar = $_GET['id_documento'];
      }else if(isset($_GET['id_procedimento_anexado']) && $_GET['id_procedimento_anexado']!=''){
        $dblIdProtocoloPosicionar = $_GET['id_procedimento_anexado'];
      }

      $strNos = '';
      $strNosAcao = '';
      $strJsArrPastas = '';
      $numNo = 0;
      $numNoAcao = 0;

      $strOcultarAbrirFechar = '';
      $strNumPastasAbertas = '';

      $bolFlagAberto = false;
      $bolFlagAnexado = false;
      $bolFlagAbertoAnexado = false;
      $bolFlagProtocolo = false;
      $bolFlagArquivo = false;
      $bolFlagTramitacao = false;
      $bolFlagSobrestado = false;
      $bolFlagBloqueado = false;
      $bolFlagLinhaDireta = false;
      $bolErro = false;
      $numCodigoAcesso = 0;


      if ($bolAcaoProcedimentoLinhaDireta){

        if (isset($_GET['linha_direta'])){
          if ($_GET['linha_direta']=='1'){
            ProcedimentoINT::adicionarLinhaDireta($dblIdProcedimento);
          }else{
            ProcedimentoINT::removerLinhaDireta($dblIdProcedimento);
          }
        }

        $arrLinhaDireta = SessaoSEI::getInstance()->getAtributo('LINHA_DIRETA_'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());

        $bolFlagLinhaDireta = isset($arrLinhaDireta[$dblIdProcedimento]);
      }

      $objProcedimentoDTO = ProcedimentoINT::montarAcoesArvore($dblIdProcedimento,
        $numIdUnidadeAtual,
        $bolFlagAberto,
        $bolFlagAnexado,
        $bolFlagAbertoAnexado,
        $bolFlagProtocolo,
        $bolFlagArquivo,
        $bolFlagTramitacao,
        $bolFlagSobrestado,
        $bolFlagBloqueado,
        $bolFlagLinhaDireta,
        $numCodigoAcesso,
        $numNo, $strNos,
        $numNoAcao, $strNosAcao,
        $bolErro);

      $arrPastas = array();
      $arrPastasAbertas = array();

      if (!$bolErro && $objProcedimentoDTO!=null){

        $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

        $numTotalProtocolos = InfraArray::contar($arrObjRelProtocoloProtocoloDTO);

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $numMaxDocPasta = $objInfraParametro->getValor('SEI_NUM_MAX_DOCS_PASTA');

        if ($numMaxDocPasta == ''){
          $numMaxDocPasta = $numTotalProtocolos;
        }

        $bolAbrirPastas = (isset($_GET['abrir_pastas']) && $_GET['abrir_pastas']=='1');
        $bolFecharPastas = (isset($_GET['fechar_pastas']) && $_GET['fechar_pastas']=='1');


        if ($numTotalProtocolos > $numMaxDocPasta){

          $numPastaAtual = 0;

          for($i=0;$i<$numTotalProtocolos;$i++){

            if ($i==0 || ($i>=$numMaxDocPasta && $i%$numMaxDocPasta==0)){

              $strAberto = 'false';

              $numPastaAtual++;

              if (!$bolFecharPastas){
                if ($bolAbrirPastas || ($numPastaAtual*$numMaxDocPasta)>=$numTotalProtocolos){
                  $strAberto = 'true';
                  $arrPastasAbertas[] = $numPastaAtual;
                }
              }

              if ($dblIdProtocoloPosicionar!='' && $strAberto=='false'){

                $k = $i + $numMaxDocPasta;

                if ($k > $numTotalProtocolos){
                  $k = $numTotalProtocolos;
                }

                //se posicionando em um documento/processo de uma pasta intermediária
                for($j=$i;$j<$k;$j++){
                  if ($arrObjRelProtocoloProtocoloDTO[$j]->getDblIdProtocolo2()==$dblIdProtocoloPosicionar){
                    $strAberto = 'true';
                    $arrPastasAbertas[] = $numPastaAtual;
                    break;
                  }
                }
              }

              $strNos .= 'Nos['.$numNo.'] = new infraArvoreNo("PASTA",'.
                '"PASTA'.$numPastaAtual.'",'.
                '"'.$dblIdProcedimento.'",'.
                '"javascript:abrirFecharPasta(\'PASTA'.$numPastaAtual.'\');",'.
                'null,'.
                '"'.InfraUtil::converterNumeroDecimalParaRomano($numPastaAtual).'",'.
                '"'.$numPastaAtual.'",'.
                'null,'.
                '"'.Icone::PROCESSO_ABERTO.'",'.
                '"'.Icone::PROCESSO_FECHADO.'",'.
                $strAberto.','.
                'true,'.
                'null,'.
                'null,'.
                'null);'."\n";

              $strNos .= 'Nos['.$numNo.'].carregado = '.$strAberto.';'."\n";
              $numNo++;

              if ($strAberto=='false'){
                $strNos .= 'Nos['.$numNo++.'] = new infraArvoreNo("AGUARDE",'.
                  '"AGUARDE'.$numPastaAtual.'",'.
                  '"PASTA'.$numPastaAtual.'",'.
                  'null,'.
                  'null,'.
                  '"Aguarde...",'.
                  '"Aguarde...",'.
                  '"'.PaginaSEI::getInstance()->getIconeAguardar().'",'.
                  'null,'.
                  'null,'.
                  'false,'.
                  'true,'.
                  'null,'.
                  'null,'.
                  'null);'."\n";
              }
            }

            $arrPastas[$numPastaAtual][] = $arrObjRelProtocoloProtocoloDTO[$i]->getDblIdRelProtocoloProtocolo();
          }

          $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("ABRIR_PASTAS",'.
            '"AP'.$dblIdProcedimento.'",'.
            '"'.$dblIdProcedimento.'",'.
            '"'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&abrir_pastas=1').'",'.
            'null,'.
            '"Abrir todas as Pastas",'.
            '"'.PaginaSEI::getInstance()->getIconeMais().'",'.
            'true);'."\n";

          $strNosAcao .= 'NosAcoes['.$numNoAcao++.'] = new infraArvoreAcao("FECHAR_PASTAS",'.
            '"FP'.$dblIdProcedimento.'",'.
            '"'.$dblIdProcedimento.'",'.
            '"'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento.'&fechar_pastas=1').'",'.
            'null,'.
            '"Fechar todas as Pastas",'.
            '"'.PaginaSEI::getInstance()->getIconeMenos().'",'.
            'true);'."\n";


          if ($bolAbrirPastas){
            $strOcultarAbrirFechar = 'document.getElementById(\'anchorAP'.$dblIdProcedimento.'\').style.display=\'none\';';
          }else if ($bolFecharPastas){
            $strOcultarAbrirFechar = 'document.getElementById(\'anchorFP'.$dblIdProcedimento.'\').style.display=\'none\';';
          }

          $strNumPastasAbertas = 'objArvore.numPastasAbertas='.InfraArray::contar($arrPastasAbertas).';';


          foreach($arrPastasAbertas as $numPastaAberta){
            ProtocoloINT::montarAcoesArvore($dblIdProcedimento,
              $numIdUnidadeAtual,
              $bolFlagAberto,
              $bolFlagAnexado,
              $bolFlagAbertoAnexado,
              $bolFlagProtocolo,
              $bolFlagArquivo,
              $bolFlagTramitacao,
              $bolFlagSobrestado,
              $bolFlagBloqueado,
              $numCodigoAcesso,
              'PASTA'.$numPastaAberta,
              $arrPastas[$numPastaAberta],
              $numNo, $strNos,
              $numNoAcao, $strNosAcao);
          }

        }else{
          ProtocoloINT::montarAcoesArvore($dblIdProcedimento,
            $numIdUnidadeAtual,
            $bolFlagAberto,
            $bolFlagAnexado,
            $bolFlagAbertoAnexado,
            $bolFlagProtocolo,
            $bolFlagArquivo,
            $bolFlagTramitacao,
            $bolFlagSobrestado,
            $bolFlagBloqueado,
            $numCodigoAcesso,
            $dblIdProcedimento,
            InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO,'IdRelProtocoloProtocolo'),
            $numNo, $strNos,
            $numNoAcao, $strNosAcao);
        }

        //Ação de consulta de andamento
        $bolAcaoHistoricoProcedimento = SessaoSEI::getInstance()->verificarPermissao('procedimento_consultar_historico');

        if ($bolAcaoHistoricoProcedimento){
          $strConsultarAndamento = '<a style="cursor:pointer;" onclick="consultarAndamento();"><img src="'.Icone::PROCESSO_ANDAMENTOS.'" alt="Consultar Andamento" title="Consultar Andamento" />Consultar Andamento</a>'."\n";
          $strLinkHistorio = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_consultar_historico&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1');
        }

        //Relacionamentos
        $objProcedimentoDTORelacionado = new ProcedimentoDTO();
        $objProcedimentoDTORelacionado->setDblIdProcedimento($_GET['id_procedimento']);

        $objProcedimentoRN = new ProcedimentoRN();
        $arrObjRelProtocoloProtocoloDTO = $objProcedimentoRN->listarRelacionados($objProcedimentoDTORelacionado);

        $strRelacionamentosTitulo = '';
        $strRelacionamentos = '';

        if (count($arrObjRelProtocoloProtocoloDTO)){
          $arrRelacionamentos = array();
          foreach($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO){

            if ($objRelProtocoloProtocoloDTO->getObjProtocoloDTO1()!=null){
              $objProcedimentoDTORelacionado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO1();
            }else{
              $objProcedimentoDTORelacionado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();
            }

            $strClassRelacionamento = '';
            if ($objProcedimentoDTORelacionado->getStrSinAberto()=='S'){
              $strClassRelacionamento = 'protocoloAberto';
            }else{
              $strClassRelacionamento = 'protocoloFechado';
            }

            $arrRelacionamentos[$objProcedimentoDTORelacionado->getStrNomeTipoProcedimento()][] = '<a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=procedimento_visualizar&id_procedimento='.$objProcedimentoDTORelacionado->getDblIdProcedimento()).'" '.PaginaSEI::montarTitleTooltip($objProcedimentoDTORelacionado->getStrDescricaoProtocolo()).' class="'.$strClassRelacionamento.'">'.$objProcedimentoDTORelacionado->getStrProtocoloProcedimentoFormatado().'</a><br />'."\n";
          }

          $numRelacionado = 0;

          $strRelacionamentos .= '<div id="divRelacionadosParciais">'."\n";
          foreach($arrRelacionamentos as $strIdentificacaoRelacionado => $arrLinksRelacionados){
            $strRelacionamentos .= '<a href="javascript:void(0);" onclick="visualizacaoRelacionados('.$numRelacionado.')" class="ancoraRelacionadosParcial">'.PaginaSEI::tratarHTML($strIdentificacaoRelacionado).' ('.InfraArray::contar($arrLinksRelacionados).')</a><br />';
            $strRelacionamentos .= '<div id="divRelacionadosParcial'.$numRelacionado.'" class="divRelacionadosParcial">'."\n";
            foreach($arrLinksRelacionados as $strLinkRelacionado){
              $strRelacionamentos .= $strLinkRelacionado;
            }
            $strRelacionamentos .= '</div>'."\n";
            $numRelacionado++;
          }
          $strRelacionamentos .= '</div>'."\n";

          if ($strRelacionamentos != ''){
            $strRelacionamentosTitulo = '<label>Processos Relacionados:</label> <br />';
          }
        }

        if ($bolFlagAberto && $bolAcaoProcedimentoReceber){
          $objProcedimentoRN->receber($objProcedimentoDTO);
        }

        if (InfraArray::contar($arrPastas)){
          $strJsArrPastas = '  var Pastas = [];'."\n\n";
          foreach($arrPastas as $numPasta => $arrIdRelProtocoloProtocolo){
            $strIdRelProtocoloProtocolo = implode(',',$arrIdRelProtocoloProtocolo);
            $strJsArrPastas .= '  Pastas['.$numPasta.'] = [];'."\n";
            $strJsArrPastas .= '  Pastas['.$numPasta.'][\'link\'] = \''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_paginar&id_procedimento='.$dblIdProcedimento.'&id_unidade='.$numIdUnidadeAtual.'&flag_aberto='.$bolFlagAberto.'&flag_anexado='.$bolFlagAnexado.'&flag_aberto_anexado='.$bolFlagAbertoAnexado.'&flag_protocolo='.$bolFlagProtocolo.'&flag_arquivo='.$bolFlagArquivo.'&flag_tramitacao='.$bolFlagTramitacao.'&flag_sobrestado='.$bolFlagSobrestado.'&flag_bloqueado='.$bolFlagBloqueado.'&codigo_acesso='.$numCodigoAcesso.'&no_pai=PASTA'.$numPasta.'&pagina_hash='.md5($strIdRelProtocoloProtocolo)).'\';'."\n";
            $strJsArrPastas .= '  Pastas['.$numPasta.'][\'protocolos\'] = \''.$strIdRelProtocoloProtocolo.'\';'."\n\n";
          }
        }
      }

      $strLinkAtualizarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=procedimento_visualizar&id_procedimento='.$dblIdProcedimento);
      $strLinkControleProcessos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao']);

      break;

    default:

      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


  //$numSeg = InfraUtil::verificarTempoProcessamento($numSegOrig);
  //InfraDebug::getInstance()->gravar($numSeg.' s');

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

body{

overflow:visible;
}

#header {
margin:0 auto;
height: 30px;
width:100%;
}

#topmenu {
height:20px;
padding-top:.5em;
}

#container {
width:100%;
}

#content {
margin: 0 auto;
}

#divArvore {
width:99%;
padding-top:2px;
}

div.infraArvore img {
vertical-align:baseline;
}

div.infraArvore span {
font-size: .875rem;
vertical-align:super;
}

#divArvore a {
font-size:.875rem;
}

#divConsultarAndamento {
width:99%;
padding:1em 0.2em 1em 0.2em;
margin-top:1em;
border-top:.1em solid #b0b0b0;
}

#divConsultarAndamento a {
text-decoration:none;
font-size: .9rem;
}

#divConsultarAndamento img {
vertical-align:middle;
padding-right:5px;
}

#divRelacionados {
width:99%;
padding-top:1em;
border-top:.1em solid #b0b0b0;
}

#divRelacionados label,
#divRelacionadosParciais label,
#divRelacionados a,
#divRelacionadosParciais a  {
font-size: .875rem;
}

#divRelacionados label{
width:99%;
}

#divRelacionadosParciais{
white-space: nowrap;
}

.divRelacionadosParcial{
padding-left:2em;
display:none;
}

a.ancoraRelacionadosParcial{
padding-left:1.2em;
text-decoration:none;
color: black;
}

a.ancoraRelacionadosParcial:hover{
text-decoration:underline;
}

.noVisitado {
background-color:white;
color:#0000cc;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->adicionarJavaScript('js/popover/popper.min.js');

PaginaSEI::getInstance()->montarJavaScript();

if (!in_array(PaginaSEI::getInstance()->getNumTipoBrowser(), array(InfraPagina::$TIPO_BROWSER_IE56, InfraPagina::$TIPO_BROWSER_IE7, InfraPagina::$TIPO_BROWSER_IE8))) {
  PaginaSEI::getInstance()->adicionarJavaScript('js/clipboard/clipboard.min.js');
}

PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
  var objArvore = null;
  var processandoPasta = false;
  var processarIframe = false;

  function inicializar(){

    if ('<?=$bolErro?>'=='1'){
      parent.parent.document.location.href = '<?=$strLinkControleProcessos?>';
    }

    document.onkeydown = navegarTeclado;

    var Nos = Array();
    var NosAcoes = Array();

    <?=$strJsArrPastas?>
    <?=$strNos?>
    <?=$strNosAcao?>

    objArvore = new infraArvore('divArvore', Nos, NosAcoes, 'hdnArvore', 'topmenu', 24);

    objArvore.processar = function(no){
      if(!parent.infraIsBreakpointBootstrap('lg') && (no instanceof infraArvoreNo || (no instanceof infraArvoreAcao && no.target == "ifrVisualizacao" && no.href != "javascript:void"))) {
        parent.document.getElementById('ifrVisualizacao').onload = function() {
          if(!parent.infraIsBreakpointBootstrap('lg')) {
            parent.document.getElementById('divIframeVisualizacao').style.cssText = "display:block !important;";
            parent.document.getElementById('divIframeArvore').style.cssText = "display:none !important;";
          }
        }
      }
      return no;
    }

    objArvore.getNoAnterior = function(nodeId){
      var i,j,n;
      n = this.nodes.length;

      if (n > 1) {

        for (i = 0; i < n; i++) {
          if (this.nodes[i].id==nodeId) {
            break;
          }
        }

        if (i > 0){

          if (this.nodes[i-1].tipo == 'FEDERACAO' || this.nodes[i-1].tipo=='INSTALACAO_FEDERACAO' || this.nodes[i-1].tipo=='ORGAO_FEDERACAO'){
            return this.nodes[0];
          }

          if (this.nodes[i].tipo == 'PASTA'){

            for(j=n-1;j>i;j--){
              if (this.nodes[j].idPai==this.nodes[i].id && this.nodes[j].tipo != 'AGUARDE'){
                return this.nodes[j];
              }
            }

            for(j=i-1;j>0;j--){
              if (this.nodes[j].tipo == 'PASTA'){
                return this.nodes[j];
              }
            }

          }else{
            if (this.nodes[i-1].idPai == this.nodes[i].idPai || this.nodes[i-1].idPai == null){
              return this.nodes[i-1];
            }else{
              for (j=1;j<n;j++){
                if (this.nodes[j].tipo == 'PASTA' && this.nodes[j].id==this.nodes[i].idPai) {
                  if (this.nodes[j-1].tipo == 'PROCESSO'){
                    return this.nodes[j-1];
                  }else if (this.nodes[j-1].tipo == 'AGUARDE'){
                    return this.nodes[j-2];
                  }else if (this.nodes[j-1].tipo == 'PASTA'){
                    return this.nodes[j-1];
                  }
                  break;
                }
              }
            }
          }
        }
      }
      return null;
    }

    objArvore.getNoProximo = function(nodeId){
      var i,j,k,n;
      n = this.nodes.length;

      if (n > 1) {

        for (i = 0; i < n; i++) {
          if (this.nodes[i].id==nodeId) {
            break;
          }
        }

        if (i < n){

          if (i==0){

            if (this.nodes[1].tipo == 'FEDERACAO'){
              for (j=1;j<n;j++){
                if (this.nodes[j].tipo != 'FEDERACAO' && this.nodes[j].tipo!='INSTALACAO_FEDERACAO' && this.nodes[j].tipo!='ORGAO_FEDERACAO'){
                  return this.nodes[j];
                }
              }
            }else{
              return this.nodes[1];
            }
          }

          if (this.nodes[i].tipo == 'PASTA'){

            if (this.nodes[i].carregado){
              for(j=i+1;j<n;j++){
                if (this.nodes[j].idPai==this.nodes[i].id && this.nodes[j].tipo != 'AGUARDE'){
                  return this.nodes[j];
                }
              }

              for(j=i+1;j<n;j++){
                if (this.nodes[j].tipo == 'PASTA'){
                  return this.nodes[j];
                }
              }
            }else {
              return this.nodes[i];
            }

          }else{
            if ((i < n - 1) && this.nodes[i+1].idPai == this.nodes[i].idPai){
              return this.nodes[i+1];
            }else{
              for (j=1;j<n;j++){
                if (this.nodes[j].tipo == 'PASTA' && this.nodes[j].id==this.nodes[i].idPai){
                  for(k=j+1;k<n;k++){
                    if (this.nodes[k].tipo == 'PASTA'){
                      return this.nodes[k];
                    }
                  }
                  break;
                }
              }
            }
          }
        }
      }
      return null;
    }

  <?=$strOcultarAbrirFechar?>
    <?=$strNumPastasAbertas?>

    if (Nos.length){

      Nos[0].processar = function (){
        if(!parent.infraIsBreakpointBootstrap("lg")) {
          parent.document.getElementById('ifrVisualizacao').src = "about:blank";
          parent.document.getElementById('divIframeVisualizacao').style.cssText  = "display:block !important;";
          parent.document.getElementById('divIframeArvore').style.cssText  = "display:none !important;";
        }
        document.location = '<?=$strLinkAtualizarArvore?>';

        return false;
      }

      if  (typeof(Clipboard) != 'undefined'){

        associarNosClipboard(Nos);
      }
    }

    objArvore.processarAbertura = function(no){

      if (no.tipo.indexOf('FEDERACAO') != -1) {
        return true;
      }

      processarIframe = true;

      if (!processandoPasta){
        if (!no.carregado){
          document.getElementById('hdnPastaAtual').value = no.id;
          document.getElementById('hdnProtocolos').value = Pastas[no.id.substr(5)]['protocolos'];
          document.getElementById('frmArvore').action = Pastas[no.id.substr(5)]['link'];
          document.getElementById('frmArvore').submit();
        }

        document.getElementById('anchorFP<?=$_GET['id_procedimento']?>').style.display='';

        objArvore.numPastasAbertas = objArvore.numPastasAbertas + 1;
        if (objArvore.numPastasAbertas == <?=InfraArray::contar($arrPastas)?>){
          document.getElementById('anchorAP<?=$_GET['id_procedimento']?>').style.display='none';
        }

        return true;
      }

      return false;
    }

    objArvore.processarFechamento = function(no){

      if (no.tipo.indexOf('FEDERACAO') != -1) {
        return true;
      }

      document.getElementById('hdnPastaAtual').value = no.id;
      atualizarMensagemPasta('AGUARDE');
      document.getElementById('anchorAP<?=$_GET['id_procedimento']?>').style.display='';
      objArvore.numPastasAbertas = objArvore.numPastasAbertas - 1;
      if (objArvore.numPastasAbertas == 0){
        document.getElementById('anchorFP<?=$_GET['id_procedimento']?>').style.display='none';
      }

      return true;
    }

    var objNoSelecionado = null;
    if ('<?=$dblIdProtocoloPosicionar?>' != ''){
      objNoSelecionado = objArvore.getNo('<?=$dblIdProtocoloPosicionar?>');
    }else if ('<?=(isset($_GET['id_orgao_federacao']) && $_GET['id_orgao_federacao']!='')?>' != ''){
      objNoSelecionado = objArvore.getNo('<?=$_GET['id_orgao_federacao']?>');
    }else if(parent.infraIsBreakpointBootstrap("lg")){
      objNoSelecionado = objArvore.getNo('<?=$_GET['id_procedimento']?>');
    }else{
      objNoSelecionado = objArvore.getNo('<?=$_GET['id_procedimento']?>');
    }

    if (objNoSelecionado != null){
      objArvore.setNoSelecionado(objNoSelecionado);
      <? if (isset($_GET['procedimento_visualizar_ciencias']) && $_GET['procedimento_visualizar_ciencias'] == '1'){ ?>
      consultarProcedimentoCiencias();
      <? }else if (isset($_GET['documento_visualizar_ciencias']) && $_GET['documento_visualizar_ciencias'] == '1'){ ?>
      consultarDocumentoCiencias();
      <?}else if (!isset($_GET['montar_visualizacao']) || $_GET['montar_visualizacao']=='1'){ ?>
      self.setTimeout('atualizarVisualizacao()',100);
      <? } ?>
    }
  }

  function atualizarVisualizacao(){
    if (objArvore != null) {
      var no = objArvore.getNoSelecionado();
      if (no != null) {
        parent.document.getElementById('ifrVisualizacao').src = no.href;
      }
    }
  }

  function consultarAndamento(){
    parent.document.getElementById('ifrVisualizacao').src = '<?=$strLinkHistorio?>';
    if(!parent.infraIsBreakpointBootstrap('lg') && (no instanceof infraArvoreNo || (no instanceof infraArvoreAcao && no.target == "ifrVisualizacao" && no.href != "javascript:void"))) {
      parent.document.getElementById('ifrVisualizacao').onload = function() {
        if(!parent.infraIsBreakpointBootstrap('lg')) {
          parent.document.getElementById('divIframeVisualizacao').style.cssText = "display:block !important;";
          parent.document.getElementById('divIframeArvore').style.cssText = "display:none !important;";
        }
      }
    }
  }

  function consultarProcedimentoCiencias(){
    <? if (isset($_GET['procedimento_visualizar_ciencias']) && $_GET['procedimento_visualizar_ciencias'] == '1'){ ?>
    parent.document.getElementById('ifrVisualizacao').src = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_ciencia_listar&acao_origem=procedimento_visualizar&id_procedimento='.$_GET['id_procedimento'].'&arvore=1')?>#' + infraGetAnchor();
    <? } ?>
  }

  function consultarDocumentoCiencias(){
    <? if (isset($_GET['documento_visualizar_ciencias']) && $_GET['documento_visualizar_ciencias'] == '1'){ ?>
    parent.document.getElementById('ifrVisualizacao').src = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_ciencia_listar&acao_origem=procedimento_visualizar&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'].'&arvore=1')?>#' + infraGetAnchor();
    <? } ?>
  }


  function processarPasta(){

    if (processarIframe){

      var ie = infraVersaoIE();

      try{
        if (!ie){
          docIframe = document.getElementById('ifrPasta').contentWindow.document;
        }else{
          docIframe = window.frames['ifrPasta'].document;
        }
      }catch(e){
        alert('Não foi possível recuperar os protocolos.');
        return;
      }

      ret = docIframe.body.innerHTML;

      if (ret != ''){

        if (ret.substring(0,2) != 'OK'){

          var prefixoValidacao = 'INFRA_VALIDACAO';

          if (ret.substr(0,15) == prefixoValidacao){

            atualizarMensagemPasta('AVISO');

            var msg = ret.substr(prefixoValidacao.length+1);
            msg = msg.infraReplaceAll("\\n", "\n");
            msg = decodeURIComponent(msg);
            alert(msg);

          }else{

            try{

              atualizarMensagemPasta('ERRO');

              if (docIframe.getElementById('divInfraExcecao')==null){
                alert('Erro recuperando protocolos.');
              }else{

                document.getElementById("ifrPasta").style.display = 'block';
                document.getElementById('frmArvore').style.display = 'none';

                resizeIframe();

                docIframe.getElementById('btnInfraFecharExcecao').value = 'Voltar';
                if (!ie){
                  docIframe.getElementById('btnInfraFecharExcecao').innerHTML = 'Voltar';
                }
                docIframe.getElementById('btnInfraFecharExcecao').onclick = function() {
                  document.getElementById("ifrPasta").style.display = 'none';
                  document.getElementById('frmArvore').style.display = 'block';
                }

              }

            }catch(e){alert(e);}
          }
        }else{

          if (objArvore != null){

            var Nos = [];
            var NosAcoes = [];

            var arrComandos = ret.substr(3).split("\n");
            for(var i=0; i < arrComandos.length; i++){
              if (arrComandos[i].substr(0,3)=='Nos'){
                eval(arrComandos[i]);
              }
            }

            if (Nos.length==0){
              atualizarMensagemPasta('NAO ENCONTRADO');
            }else{
              processandoPasta = true;
              try{
                var noPasta = objArvore.getNo(document.getElementById('hdnPastaAtual').value);

                var div = document.getElementById('div' + noPasta.id);
                div.innerHTML = '';

                objArvore.adicionarFilhos(noPasta, Nos, NosAcoes);

                associarNosClipboard(Nos);

                noPasta.carregado = true;

                if (noPasta.navegar != null){
                  var funcaoNavegar = 'navegarArvore(\'' + noPasta.navegar + '\')';
                  noPasta.navegar = null;
                  setTimeout(funcaoNavegar, 200);
                }

              }catch(e){
                alert(e);
              }
              processandoPasta = false;
            }
          }

          if (INFRA_IE){
            window.status='Finalizado.';
          }
        }
      }
    }
  }

  function navegarArvore(sentido) {

    if (objArvore!=null) {

      var noSelecionado = objArvore.getNoSelecionado();

      if (noSelecionado!=null) {
        var no = null;
        while(true){
          if (sentido == 'P'){
            no = objArvore.getNoProximo(noSelecionado.id);
          }else if (sentido == 'A'){
            no = objArvore.getNoAnterior(noSelecionado.id);
          }
          if (no!=null) {

            if(no.tipo == 'PASTA' ){
              if(no.carregado){
                noSelecionado = no;
              }else{
                no.navegar = sentido;
                abrirFecharPasta(no.id);
                break;
              }
            }else if(no.tipo == 'AGUARDE' || !no.bolHabilitado){
              noSelecionado = no;
            }else{
              objArvore.setNoSelecionado(no);
              self.setTimeout('atualizarVisualizacao()',100);
              break;
            }
          }else{
            alert("Não existem mais protocolos disponíveis para exibição.");
            break;
          }
        }
      }
      else{
        alert("Não existem mais protocolos disponíveis para exibição.");
      }
    }
  }

  function navegarTeclado(ev){
    var key = infraGetCodigoTecla(ev);
    if(key == 40) {
      navegarArvore('P');
      return false;
    } else if(key == 38) {
      navegarArvore('A');
      return false;
    }
    return true;
  }

  function associarNosClipboard(nos){
      var icone = null;
      for(var i=0;i<nos.length;i++){
        var no = nos[i];
        if (no.tipo != 'PASTA' && no.tipo != 'AGUARDE' && no.tipo.indexOf('FEDERACAO') == -1) {
          icone = document.getElementById('anchorImg' + no.id);

          var id = 'popover-content' + icone.id;
          var divConteudoPopover = null;
          if (no.tipo.indexOf('PROCESSO') != -1) {
            divConteudoPopover = $('<div id="' + id + '" style="display: none;position:relative;">\n' +
              '  <ul class="list-group custom-popover" tipo="' + no.tipo + '">\n' +
              '     <li popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux + '" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">' + no.aux + '</span></li>\n' +
              '    <li popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux +' (' + no.title +')" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">'  + no.aux +' (' + no.title + ')</span></li>\n' +
              '    <li popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#{'+ no.id +'|'  + no.aux +'}#" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_editor.svg"/>&nbsp;<span class="align-self-center">' + no.aux + '</span></li>\n' +
              '    <li popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#{'+ no.id +'|'  + no.aux +'} (' + no.title +  ')#" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_editor.svg"/>&nbsp;<span class="align-self-center">' + no.aux +' (' + no.title +  ')</span></li>\n' +
              '    <li popoverId="' + icone.id + '" tipo="url" onclick="copiarParaClipboard(this)" data-clipboard-text="<?=ConfiguracaoSEI::getInstance()->getValor("SEI","URL")?>/controlador.php?acao=procedimento_trabalhar&id_procedimento='+no.id+'" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_link_direto.svg" /><span class="align-self-center">&nbsp;Link para Acesso Direto</span></li>\n' +
              '    <li popoverId="' + icone.id + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar" ><span class="align-self-center">Fechar</span></li>\n' +
              '  </ul>\n' +
              '</div>');
          }else{

            if (!no.bolHabilitado || no.tipo == 'DOCUMENTO_MOVIDO'){
              divConteudoPopover = $('<div id="' + id + '" style="display: none;position:relative;">\n' +
                  '  <ul class="list-group custom-popover" tipo="' + no.tipo + '">\n' +
                  '    <li popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux + '" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">' + no.aux + '</span></li>\n' +
                  '    <li popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)"  data-clipboard-text="' + no.label +'" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">'   + no.label + '</span></li>\n' +
                  '    <li popoverId="' + icone.id + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar" ><span class="align-self-center">Fechar</span></li>\n' +
                  '  </ul>\n' +
                  '</div>');
            }else{
              divConteudoPopover = $('<div id="' + id + '" style="display: none;position:relative;">\n' +
                  '  <ul class="list-group custom-popover" tipo="' + no.tipo + '">\n' +
                  '    <li popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux + '" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">' + no.aux + '</span></li>\n' +
                  '    <li popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)"  data-clipboard-text="' + no.label +'" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">'   + no.label + '</span></li>\n' +
                  '    <li popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#{'+ no.id +'|'  + no.aux +'}#" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_editor.svg"/>&nbsp;<span class="align-self-center">' + no.aux + '</span></li>\n' +
                  '    <li popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#'+no.label.replace(no.aux,"{"+ no.id +"|"  + no.aux +"}")+'#" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_editor.svg"/>&nbsp;<span class="align-self-center">' + no.label +'</span></li>\n' +
                  '    <li popoverId="' + icone.id + '" tipo="url" onclick="copiarParaClipboard(this)" data-clipboard-text="<?=ConfiguracaoSEI::getInstance()->getValor("SEI","URL")?>/controlador.php?acao=procedimento_trabalhar&id_procedimento=<?=$_GET['id_procedimento']?>&id_documento='+no.id+'" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_link_direto.svg" /><span class="align-self-center">&nbsp;Link para Acesso Direto</span></li>\n' +
                  '    <li popoverId="' + icone.id + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar" ><span class="align-self-center">Fechar</span></li>\n' +
                  '  </ul>\n' +
                  '</div>');
            }
          }
          $("body").append(divConteudoPopover);
          $(icone).attr("data-toggle","popover");
          $(icone).attr("data-placement","bottom");

          $(icone).popover({
            html: true,
            sanitize: false,
            content: function() {
              return $("#"+'popover-content'+ this.id) .html();
            }
          });
          $(icone).on('show.bs.popover', function () {
            $("a[data-toggle=popover]").not($(this)).popover("hide");
          })
        }
      }
  }

  function atualizarMensagemPasta(tipo){

    var pastaAtual = document.getElementById('hdnPastaAtual');

    if (pastaAtual != null){

      var idAguarde = pastaAtual.value.replace('PASTA','AGUARDE');

      var spanAguarde = document.getElementById('span' + idAguarde);
      var imgAguarde = document.getElementById('icon' + idAguarde);

      if (spanAguarde != null && imgAguarde != null){
        if (tipo == 'AVISO'){
          spanAguarde.innerHTML = spanAguarde.title = 'Não foi possível carregar os protocolos.';
          imgAguarde.src = '<?=PaginaSEI::getInstance()->getIconeRemover()?>';
        }else if (tipo == 'ERRO'){
          spanAguarde.innerHTML = spanAguarde.title = 'Erro carregando protocolos.';
          imgAguarde.src = '<?=PaginaSEI::getInstance()->getIconeRemover()?>';
        }else if (tipo == 'NAO ENCONTRADO'){
          spanAguarde.innerHTML = spanAguarde.title = 'Nenhum protocolo encontrado.';
          imgAguarde.src = '<?=PaginaSEI::getInstance()->getIconeRemover()?>';
        }else if (tipo == 'AGUARDE'){
          spanAguarde.innerHTML = spanAguarde.title = 'Aguarde...';
          imgAguarde.src = '<?=PaginaSEI::getInstance()->getIconeAguardar()?>';
        }
      }
    }
  }

  function resizeIframe(){
    document.getElementById("ifrPasta").style.height = (infraClientHeight()-30) + 'px';
  }

  function abrirFecharPasta(id){
    objArvore.processarNoJuncao(id);

  }

  function visualizacaoRelacionados(n){
    var div = document.getElementById('divRelacionadosParcial'+n);
    if (div != null){
      if (div.style.display=='block'){
        div.style.display = 'none';
      }else{
        div.style.display = 'block';
      }
    }
  }

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
//PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<body onload="inicializar();" class="px-2 infraArvore">

<div id="header">
  <div id="topmenu"></div>
</div>
<div id="container" >
  <div id="content">
    <form id="frmArvore" method="post" target="ifrPasta">

      <div id="divArvore">
      </div>

      <div id="divConsultarAndamento">
        <?=$strConsultarAndamento?>
      </div>

      <div id="divRelacionados">
        <?=$strRelacionamentosTitulo?>
      </div>
      <?=$strRelacionamentos?>

      <input type="hidden" id="hdnArvore" name="hdnArvore" value="<?=$_POST['hdnArvore']?>" />
      <input type="hidden" id="hdnPastaAtual" name="hdnPastaAtual" value="<?=$_POST['hdnPastaAtual']?>" />
      <input type="hidden" id="hdnProtocolos" name="hdnProtocolos" value="<?=$_POST['hdnProtocolos']?>" />
    </form>
  </div>
</div>

<?
//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
<iframe id="ifrPasta" name="ifrPasta" onload="processarPasta();" width="100%" height="100%" frameborder="0" style="display:none;"></iframe>
<?
PaginaSEI::getInstance()->montarAreaDebug();
?>
</body>
<?
//PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>