<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/10/2013 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: arquivo_extensao_lista.php 7863 2013-08-19 21:34:46Z mga $
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

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  $arrComandos = array();
  
  switch($_GET['acao']){
    case 'assinatura_verificar':
      
      $strTitulo = 'Consulta de Assinaturas';
      
      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retDblIdDocumento();
      $objAssinaturaDTO->retDblIdProcedimentoDocumento();
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->retStrNomeUsuario();
      $objAssinaturaDTO->retStrTratamento();
      $objAssinaturaDTO->retDblCpf();
      $objAssinaturaDTO->retStrNumeroSerieCertificado();
      $objAssinaturaDTO->retDthAberturaAtividade();
      $objAssinaturaDTO->retStrStaFormaAutenticacao();
      $objAssinaturaDTO->retStrSiglaUnidade();
      $objAssinaturaDTO->retStrDescricaoUnidade();
      $objAssinaturaDTO->setDblIdDocumento($_GET['id_documento']);
      
      PaginaSEI::getInstance()->prepararOrdenacao($objAssinaturaDTO, 'AberturaAtividade', InfraDTO::$TIPO_ORDENACAO_ASC);
      
      $objAssinaturaRN = new AssinaturaRN();
      $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);
      
      $numRegistros = count($arrObjAssinaturaDTO);
      
      if ($numRegistros > 0){
      
        $bolAcaoAssinaturaDownloadP7s = SessaoSEI::getInstance()->verificarPermissao('assinatura_download_p7s');

        $strLink = '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar_conteudo_assinatura&acao_origem='.$_GET['acao'].'&id_procedimento='.$arrObjAssinaturaDTO[0]->getDblIdProcedimentoDocumento().'&id_documento='.$arrObjAssinaturaDTO[0]->getDblIdDocumento()).'" target="_blank" class="ancoraPadraoPreta">Clique aqui para obter o conteúdo assinado</a>';

        $strResultado = '';
      
        $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Assinaturas.">'."\n";
        $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Assinaturas',$numRegistros).'</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objAssinaturaDTO,'Nome','NomeUsuario',$arrObjAssinaturaDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objAssinaturaDTO,'Cargo/Função','Tratamento',$arrObjAssinaturaDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objAssinaturaDTO,'Unidade','SiglaUnidade',$arrObjAssinaturaDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objAssinaturaDTO,'Data/Hora','AberturaAtividade',$arrObjAssinaturaDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="">'.PaginaSEI::getInstance()->getThOrdenacao($objAssinaturaDTO,'Certificado Digital','NumeroSerieCertificado',$arrObjAssinaturaDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
        $strResultado .= '</tr>'."\n";
        $strCssTr='';
        for($i = 0;$i < $numRegistros; $i++){
      
          $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
          $strResultado .= $strCssTr;
      
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAssinaturaDTO[$i]->getStrNomeUsuario()).'</td>';
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAssinaturaDTO[$i]->getStrTratamento()).'</td>';
          $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjAssinaturaDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjAssinaturaDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAssinaturaDTO[$i]->getStrSiglaUnidade()).'</a></td>';
          $strResultado .= '<td align="center">'.$arrObjAssinaturaDTO[$i]->getDthAberturaAtividade().'</td>';
          $strResultado .= '<td align="center">'.$arrObjAssinaturaDTO[$i]->getStrNumeroSerieCertificado().'</td>';
          $strResultado .= '<td align="center">';
      
          if ($bolAcaoAssinaturaDownloadP7s && $arrObjAssinaturaDTO[$i]->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_CERTIFICADO_DIGITAL){
            $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinatura_download_p7s&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_assinatura='.$arrObjAssinaturaDTO[$i]->getNumIdAssinatura()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" target="_blank"><img src="'.PaginaSEI::getInstance()->getIconeDownload().'" title="Baixar arquivo PKCS #7 para validação" alt="Baixar arquivo PKCS #7 para validação" class="infraImg" /></a>&nbsp;';
          }
      
          $strResultado .= '</td></tr>'."\n";
        }
        $strResultado .= '</table>';
      }
      
      //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      
      break;

    case 'assinatura_download_p7s':
      
      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retStrProtocoloDocumentoFormatado();
      $objAssinaturaDTO->retStrNome();
      $objAssinaturaDTO->retStrP7sBase64();
      $objAssinaturaDTO->setNumIdAssinatura($_GET['id_assinatura']);

      $objAssinaturaRN = new AssinaturaRN();
      $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);
      
      if ($objAssinaturaDTO==null){
        die('Assinatura não encontrada.');
      }

      InfraPagina::montarHeaderDownload($objAssinaturaDTO->getStrProtocoloDocumentoFormatado().'_'.InfraString::transformarCaixaAlta($objAssinaturaDTO->getStrNome()).'.p7s','attachment');
      echo base64_decode($objAssinaturaDTO->getStrP7sBase64());
      die;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  //document.getElementById('btnFechar').focus();
  infraEfeitoTabelas();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAssinaturaVerificacao" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  echo $strLink;
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>