<?
try {
  require_once dirname(__FILE__).'/../SEI.php';
  
  session_start();
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
  SessaoPublicacoes::getInstance()->validarLink();

  SessaoPublicacoes::getInstance()->validarPermissao($_GET['acao']);

  PaginaPublicacoes::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  
  switch($_GET['acao']){
    case 'publicacao_relacionada_visualizar':
      
      $strTitulo = 'Publicações Relacionadas';

      $arrObjPublicacaoDTO = array();

      if ($_GET['id_publicacao_legado'] != null){
        $objPublicacaoLegadoRN = new PublicacaoLegadoRN();
        $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();     
                               
        $objPublicacaoLegadoDTO->retNumIdPublicacaoLegado();
        $objPublicacaoLegadoDTO->retStrIdDocumento();
        $objPublicacaoLegadoDTO->retDtaGeracao();
        $objPublicacaoLegadoDTO->retStrNomeSerie();
        $objPublicacaoLegadoDTO->retStrNumero();
        $objPublicacaoLegadoDTO->retNumIdVeiculoIO();
        $objPublicacaoLegadoDTO->retNumIdVeiculoPublicacao();
        $objPublicacaoLegadoDTO->retStrStaTipoVeiculoPublicacao();
        $objPublicacaoLegadoDTO->retStrNomeVeiculoPublicacao();
        $objPublicacaoLegadoDTO->retStrDescricaoVeiculoPublicacao();
        $objPublicacaoLegadoDTO->retStrSiglaVeiculoImprensaNacional();
        $objPublicacaoLegadoDTO->retStrDescricaoVeiculoImprensaNacional();
        $objPublicacaoLegadoDTO->retDtaPublicacaoIO();
        $objPublicacaoLegadoDTO->retNumIdSecaoIO();
        $objPublicacaoLegadoDTO->retStrNomeSecaoImprensaNacional();
        $objPublicacaoLegadoDTO->retStrPaginaIO();
        $objPublicacaoLegadoDTO->retDtaPublicacao();
        $objPublicacaoLegadoDTO->retStrProtocoloFormatado();
        $objPublicacaoLegadoDTO->retStrSiglaUnidade();
        $objPublicacaoLegadoDTO->retStrDescricaoUnidade();
        $objPublicacaoLegadoDTO->retStrSiglaOrgaoUnidade();
        $objPublicacaoLegadoDTO->retStrDescricaoOrgaoUnidade();
        $objPublicacaoLegadoDTO->retStrResumo();
        
        $objPublicacaoLegadoDTO->setOrdDtaPublicacao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objPublicacaoLegadoDTO->setStrIdDocumento($_GET['id_documento']);        
        $arrObjPublicacaoLegadoDTO = $objPublicacaoLegadoRN->listar($objPublicacaoLegadoDTO);
        

        foreach($arrObjPublicacaoLegadoDTO as $objPublicacaoLegadoDTO){
          $objPublicacaoDTO = new PublicacaoDTO();
          $objPublicacaoDTO->setNumIdPublicacao(null);
          $objPublicacaoDTO->setNumIdPublicacaoLegado($objPublicacaoLegadoDTO->getNumIdPublicacaoLegado());
          $objPublicacaoDTO->setDblIdDocumento($objPublicacaoLegadoDTO->getStrIdDocumento());
          $objPublicacaoDTO->setDtaGeracaoProtocolo($objPublicacaoLegadoDTO->getDtaGeracao());
          $objPublicacaoDTO->setStrNomeSerieDocumento($objPublicacaoLegadoDTO->getStrNomeSerie());
          $objPublicacaoDTO->setStrNumeroDocumento($objPublicacaoLegadoDTO->getStrNumero());          
          $objPublicacaoDTO->setNumIdVeiculoIO($objPublicacaoLegadoDTO->getNumIdVeiculoIO());
          $objPublicacaoDTO->setNumIdVeiculoPublicacao($objPublicacaoLegadoDTO->getNumIdVeiculoPublicacao());
          $objPublicacaoDTO->setStrStaTipoVeiculoPublicacao($objPublicacaoLegadoDTO->getStrStaTipoVeiculoPublicacao());
          $objPublicacaoDTO->setStrNomeVeiculoPublicacao($objPublicacaoLegadoDTO->getStrNomeVeiculoPublicacao());
          $objPublicacaoDTO->setStrDescricaoVeiculoPublicacao($objPublicacaoLegadoDTO->getStrDescricaoVeiculoPublicacao());
          $objPublicacaoDTO->setStrSiglaVeiculoImprensaNacional($objPublicacaoLegadoDTO->getStrSiglaVeiculoImprensaNacional());
          $objPublicacaoDTO->setStrDescricaoVeiculoImprensaNacional($objPublicacaoLegadoDTO->getStrDescricaoVeiculoImprensaNacional());
          $objPublicacaoDTO->setDtaPublicacaoIO($objPublicacaoLegadoDTO->getDtaPublicacaoIO());
          $objPublicacaoDTO->setNumIdSecaoIO($objPublicacaoLegadoDTO->getNumIdSecaoIO());
          $objPublicacaoDTO->setStrNomeSecaoImprensaNacional($objPublicacaoLegadoDTO->getStrNomeSecaoImprensaNacional());
          $objPublicacaoDTO->setStrPaginaIO($objPublicacaoLegadoDTO->getStrPaginaIO());
          $objPublicacaoDTO->setDtaPublicacao($objPublicacaoLegadoDTO->getDtaPublicacao());
          $objPublicacaoDTO->setNumNumero(null);
          $objPublicacaoDTO->setStrProtocoloFormatadoProtocolo($objPublicacaoLegadoDTO->getStrProtocoloFormatado());          
          $objPublicacaoDTO->setStrSiglaUnidadeResponsavelDocumento($objPublicacaoLegadoDTO->getStrSiglaUnidade());
          $objPublicacaoDTO->setStrDescricaoUnidadeResponsavelDocumento($objPublicacaoLegadoDTO->getStrDescricaoUnidade());
          $objPublicacaoDTO->setStrSiglaOrgaoUnidadeResponsavelDocumento($objPublicacaoLegadoDTO->getStrSiglaOrgaoUnidade());
          $objPublicacaoDTO->setStrDescricaoOrgaoUnidadeResponsavelDocumento($objPublicacaoLegadoDTO->getStrDescricaoOrgaoUnidade());
          $objPublicacaoDTO->setStrResumo($objPublicacaoLegadoDTO->getStrResumo());

          $arrObjPublicacaoDTO[] = $objPublicacaoDTO;
        }
      }else{
        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoDTO = new PublicacaoDTO();               
        $objPublicacaoDTO->setDblIdDocumento($_GET['id_documento']);
        $arrObjPublicacaoDTO = $objPublicacaoRN->listarPublicacoesRelacionadas($objPublicacaoDTO);
      }
      
     
      $numRegistros = count($arrObjPublicacaoDTO);

      if ($numRegistros){
        $strSumarioTabela = 'Tabela de Publicações Eletrônicas.';
        $strResultado .= '<table id="tblPublicacoes" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
        $strResultado .= '<caption class="infraCaption">'.PaginaPublicacoes::getInstance()->gerarCaptionTabela('Publicações Eletrônicas',$numRegistros).'</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="5%">Documento</th>'."\n";
        $strResultado .= '<th class="infraTh" width="15%">Descrição</th>'."\n";
        $strResultado .= '<th class="infraTh" width="8%">Veículo</th>'."\n";
        $strResultado .= '<th class="infraTh" width="8%">Data de Publicação</th>'."\n";
        $strResultado .= '<th class="infraTh" width="5%">Unidade</th>'."\n";
        $strResultado .= '<th class="infraTh" width="5%">Órgão</th>'."\n";
        $strResultado .= '<th class="infraTh">Resumo</th>'."\n";
        $strResultado .= '<th class="infraTh" width="8%">Imprensa Nacional</th>'."\n";
        $strResultado .= '</tr>'."\n";
         
        foreach($arrObjPublicacaoDTO as $objPublicacaoDTO){
        
          $strTrClass = ($strTrClass=='infraTrClara')?'infraTrEscura':'infraTrClara';
          $strResultado .= '<tr class="'.$strTrClass.'">';
          $strResultado .= '<td align="center" class="tdDados"><a href="'.SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao=publicacao_visualizar&id_publicacao_legado='.$objPublicacaoDTO->getNumIdPublicacaoLegado().'&id_documento='.$objPublicacaoDTO->getDblIdDocumento()).'" target="_blank" alt="'.$objPublicacaoDTO->getStrNomeSerieDocumento().'" title="'.$objPublicacaoDTO->getStrNomeSerieDocumento().'" class="ancoraPadraoAzul">'.$objPublicacaoDTO->getStrProtocoloFormatadoProtocolo().'</a></td>';
          $strResultado .= '<td align="center" class="tdDados">'.$objPublicacaoDTO->getStrNomeSerieDocumento().' '.$objPublicacaoDTO->getStrNumeroDocumento().'</td>';
          $strResultado .= '<td align="center" class="tdDados">'.$objPublicacaoDTO->getStrNomeVeiculoPublicacao().' '.$objPublicacaoDTO->getNumNumero().'</td>';
          $strResultado .= '<td align="center" class="tdDados">'.$objPublicacaoDTO->getDtaPublicacao().'</td>';
          $strResultado .= '<td align="center" class="tdDados"><a alt="'.$objPublicacaoDTO->getStrDescricaoUnidadeResponsavelDocumento().'" title="'.$objPublicacaoDTO->getStrDescricaoUnidadeResponsavelDocumento().'" class="ancoraSigla">'.$objPublicacaoDTO->getStrSiglaUnidadeResponsavelDocumento().'</a></td>';
          $strResultado .= '<td align="center" class="tdDados"><a alt="'.$objPublicacaoDTO->getStrDescricaoOrgaoUnidadeResponsavelDocumento().'" title="'.$objPublicacaoDTO->getStrDescricaoOrgaoUnidadeResponsavelDocumento().'" class="ancoraSigla">'.$objPublicacaoDTO->getStrSiglaOrgaoUnidadeResponsavelDocumento().'</a></td>';
          $strResultado .= '<td align="left" class="tdDados">'.$objPublicacaoDTO->getStrResumo() .'</td>';
          $strResultado .= '<td align="center" class="tdDados">&nbsp;';
          $strResultado .= PublicacaoINT::montarDadosImprensaNacional($objPublicacaoDTO);
          $strResultado .= '</td>';
        }
        $strResultado .= '</table>';
      }
            
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
} catch(Exception $e) {
  PaginaPublicacoes::getInstance()->processarExcecao($e);
}
//MONTAGEM DA PÁGINA
PaginaPublicacoes::getInstance()->montarDocType();
PaginaPublicacoes::getInstance()->abrirHtml();
PaginaPublicacoes::getInstance()->abrirHead();
PaginaPublicacoes::getInstance()->montarMeta();
PaginaPublicacoes::getInstance()->montarTitle(PaginaPublicacoes::getInstance()->getStrNomeSistema().' - Publicações Eletrônicas');
PaginaPublicacoes::getInstance()->montarStyle();
PaginaPublicacoes::getInstance()->montarJavaScript();
PaginaPublicacoes::getInstance()->fecharHead(); 
PaginaPublicacoes::getInstance()->abrirBody($strTitulo);
?>
<form id="frmPublicacoesRelacionadas">
  <?
  PaginaPublicacoes::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaPublicacoes::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaPublicacoes::getInstance()->fecharBody();
PaginaPublicacoes::getInstance()->fecharHtml();
?>