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
                                        $_GET['flag_eliminado'],
                                        $_GET['codigo_acesso'],
                                        $_GET['no_pai'],
                                        explode(',',$_POST['hdnProtocolos']),
                                        $numNo, $strNos,
                                        $numNoAcao, $strNosAcao);

        $strNos = str_replace('-->', '-- >', $strNos);
        $strNosAcao = str_replace('-->', '-- >', $strNosAcao);

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
      $bolFlagEliminado = false;
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
                                                               $bolFlagEliminado,
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
                  if ($arrObjRelProtocoloProtocoloDTO[$j]->getDblIdProtocolo2()==$dblIdProtocoloPosicionar || $arrObjRelProtocoloProtocoloDTO[$j]->getDblIdProtocolo2().'-'.$arrObjRelProtocoloProtocoloDTO[$j]->getDblIdRelProtocoloProtocolo()==$dblIdProtocoloPosicionar){
                    $strAberto = 'true';
                    $arrPastasAbertas[] = $numPastaAtual;
                    break;
                  }
                }
              }

              $strPastaRomano = InfraUtil::converterNumeroDecimalParaRomano($numPastaAtual);
              $strNos .= 'Nos['.$numNo.'] = new infraArvoreNo("PASTA",'.
                '"PASTA'.$numPastaAtual.'",'.
                '"'.$dblIdProcedimento.'",'.
                '"javascript:abrirFecharPasta(\'PASTA'.$numPastaAtual.'\');",'.
                'null,'.
                '"'.$strPastaRomano.'",'.
                '"Pasta '.$strPastaRomano.' ('.$numPastaAtual.')",'.
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
                                            $bolFlagEliminado,
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
                                          $bolFlagEliminado,
                                          $numCodigoAcesso,
      	                                  $dblIdProcedimento,
      	                                  InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO,'IdRelProtocoloProtocolo'),
      	                                  $numNo, $strNos,
      	                                  $numNoAcao, $strNosAcao);
      	}

        //Ação de consulta de andamento
        $bolAcaoHistoricoProcedimento = SessaoSEI::getInstance()->verificarPermissao('procedimento_consultar_historico');

        if ($bolAcaoHistoricoProcedimento){
          $strConsultarAndamento = '<a href="#" style="cursor:pointer;" onclick="consultarAndamento(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_consultar_historico&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1').'\');"><img src="'.Icone::PROCESSO_ANDAMENTOS.'" alt="Consultar Andamento" title="Consultar Andamento" />&nbsp;Consultar Andamento</a>'."\n";
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

            $strCardColor = '';
            if ($objProcedimentoDTORelacionado->getStrSinAberto()=='S'){
              $strCardColor = 'success';
            }else{
              $strCardColor = 'danger';
            }

            $strProcessoRelacionado = '<div class="card cardRelacionado">
    <div class="card-body cardBodyRelacionado">
      <p class="card-title cardTitleRelacionado"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=procedimento_visualizar&id_procedimento='.$objProcedimentoDTORelacionado->getDblIdProcedimento()).'" class="card-block text-'.$strCardColor.'">'.$objProcedimentoDTORelacionado->getStrProtocoloProcedimentoFormatado().'</a></p>';

            if ($objProcedimentoDTORelacionado->getStrDescricaoProtocolo()!=''){
              $strProcessoRelacionado .= '<p class="card-text">'.PaginaSEI::tratarHTML($objProcedimentoDTORelacionado->getStrDescricaoProtocolo()).'</p>';
            }

            $strProcessoRelacionado .= '
                </div>
            </div>            
            ';

            $arrRelacionamentos[$objProcedimentoDTORelacionado->getStrNomeTipoProcedimento()][] = $strProcessoRelacionado;
          }

          $numRelacionado = 0;

          $strRelacionamentos .= '<div id="divRelacionadosParciais">'."\n";
          foreach($arrRelacionamentos as $strIdentificacaoRelacionado => $arrLinksRelacionados){
            $strRelacionamentos .= '<a href="javascript:void(0);" onclick="visualizacaoRelacionados('.$numRelacionado.')" class="ancoraRelacionadosParcial">'.PaginaSEI::tratarHTML($strIdentificacaoRelacionado).' ('.InfraArray::contar($arrLinksRelacionados).')</a><br />';
            $strRelacionamentos .= '<div id="divRelacionadosParcial'.$numRelacionado.'" class="divRelacionadosParcial">'."\n";

            $strRelacionamentos .= '<div class="card-deck cardDeckRelacionado">';

            foreach($arrLinksRelacionados as $strLinkRelacionado){
              $strRelacionamentos .= $strLinkRelacionado;
            }

            $strRelacionamentos .= '</div>';

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
	    	  $strJsArrPastas = '';
	    	  foreach($arrPastas as $numPasta => $arrIdRelProtocoloProtocolo){
	    	    $strIdRelProtocoloProtocolo = implode(',',$arrIdRelProtocoloProtocolo);
	    	    $strJsArrPastas .= '  Pastas['.$numPasta.'] = [];'."\n";
	    	    $strJsArrPastas .= '  Pastas['.$numPasta.'][\'link\'] = \''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_paginar&id_procedimento='.$dblIdProcedimento.'&id_unidade='.$numIdUnidadeAtual.'&flag_aberto='.$bolFlagAberto.'&flag_anexado='.$bolFlagAnexado.'&flag_aberto_anexado='.$bolFlagAbertoAnexado.'&flag_protocolo='.$bolFlagProtocolo.'&flag_arquivo='.$bolFlagArquivo.'&flag_tramitacao='.$bolFlagTramitacao.'&flag_sobrestado='.$bolFlagSobrestado.'&flag_bloqueado='.$bolFlagBloqueado.'&flag_eliminado='.$bolFlagEliminado.'&codigo_acesso='.$numCodigoAcesso.'&no_pai=PASTA'.$numPasta.'&pagina_hash='.md5($strIdRelProtocoloProtocolo)).'\';'."\n";
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

.cardDeckRelacionado{
  margin:5px 0;
}
.cardRelacionado{
  border-color: #b0b0b0;
  margin: 0 5px 10px 5px !important;
  min-width: 220px;
  max-width: 220px;
}
.cardTitleRelacionado{
  margin-bottom: 0 !important;
}
.cardBodyRelacionado{
  padding: .2rem .4rem;2.2rem
}

#aVisualizarDocumento{
  display:none;
  position: fixed;
  bottom: 5px;
  right: 10px;
  z-index: 99;
  border: 0;
}

#aVisualizarDocumento img{
  height: 32px;
}

.popover-header{
  display:none;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->adicionarJavaScript('js/popover/popper.min.js');

PaginaSEI::getInstance()->montarJavaScript();

if (!in_array(PaginaSEI::getInstance()->getNumTipoBrowser(), array(InfraPagina::$TIPO_BROWSER_IE56, InfraPagina::$TIPO_BROWSER_IE7, InfraPagina::$TIPO_BROWSER_IE8))) {
  PaginaSEI::getInstance()->adicionarJavaScript('js/clipboard/clipboard.min.js');
}

PaginaSEI::getInstance()->adicionarJavaScript('js/arvore_montar.js');


PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
  var objArvore = null;
  var processandoPasta = false;
  var processarIframe = false;
  var ultimoSentido = 'P';
  var linkArvoreNavegar = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_navegar')?>';
  var imgIconeRemover = '<?=PaginaSEI::getInstance()->getIconeRemover()?>';
  var imgIconeAguardar = '<?=PaginaSEI::getInstance()->getIconeAguardar()?>';


 function inicializar(){

    if ('<?=$bolErro?>'=='1'){
      parent.parent.document.location.href = '<?=$strLinkControleProcessos?>';
    }

    var Nos = Array();
    var NosAcoes = Array();
    var Pastas = Array();

    <?=$strJsArrPastas?>
    <?=$strNos?>
    <?=$strNosAcao?>

    for (i = 0; i < Nos.length; i++) {
      Nos[i].bolAgrupador = infraInArray(Nos[i].tipo,['FEDERACAO','INSTALACAO_FEDERACAO','PASTA']);
    }

    objArvore = new infraArvore('divArvore', Nos, NosAcoes, 'hdnArvore', 'topmenu', 24);

    configurarArvore(objArvore, Pastas, '<?=$_GET['id_procedimento']?>', <?=InfraArray::contar($arrPastas)?>);

    processarAcoes(NosAcoes);

    <?=$strOcultarAbrirFechar?>
    <?=$strNumPastasAbertas?>

    if (Nos.length){

      Nos[0].processar = function (){
        if(!parent.infraIsBreakpointBootstrap("lg")) {
          parent.document.getElementById('ifrConteudoVisualizacao').src = "about:blank";
          setTimeout(function () {
            visualizarDocumento();
          },100)
        }
        document.location = '<?=$strLinkAtualizarArvore?>';
        return false;
      }

      if  (typeof(Clipboard) != 'undefined'){
        associarNosClipboard(Nos,NosAcoes,'<?=ConfiguracaoSEI::getInstance()->getValor("SEI","URL")?>','<?=$_GET['id_procedimento']?>');
      }
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
      consultarCiencias('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_ciencia_listar&acao_origem=procedimento_visualizar&id_procedimento='.$_GET['id_procedimento'].'&arvore=1')?>');
      <? }else if (isset($_GET['documento_visualizar_ciencias']) && $_GET['documento_visualizar_ciencias'] == '1'){ ?>
      consultarCiencias('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_ciencia_listar&acao_origem=procedimento_visualizar&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'].'&arvore=1')?>');
      <?}else if (!isset($_GET['montar_visualizacao']) || $_GET['montar_visualizacao']=='1'){ ?>
      self.setTimeout('atualizarVisualizacao()',300);
      <? } ?>
    }
  }



//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
//PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<body onload="inicializar();" class="infraArvore">
<div id="header" class="px-2">
  <div id="topmenu"></div>
</div>
<div id="container" class="px-2">
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
<a href="#" id="aVisualizarDocumento" onclick="visualizarDocumento()" ><img   src="<?=PaginaSEI::getInstance()->getIconeAvancar()?>" title="Voltar para o Protocolo" alt="Voltar para o Protocolo" ></a>

<?
//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
<iframe id="ifrPasta" name="ifrPasta" onload="processarPasta('<?=ConfiguracaoSEI::getInstance()->getValor("SEI","URL")?>','<?=$_GET['id_procedimento']?>');" width="100%" height="100%" frameborder="0" style="display:none;"></iframe>
<?
PaginaSEI::getInstance()->montarAreaDebug();
?>
</body>
<?
//PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>