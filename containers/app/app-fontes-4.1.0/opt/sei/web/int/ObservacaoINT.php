<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ObservacaoINT extends InfraINT {

  public static function tabelaObservacoesOutrasUnidades($dblIdProtocolo){
    
    $objObservacaoDTO = new ObservacaoDTO();
    $objObservacaoDTO->retStrSiglaUnidade();
    $objObservacaoDTO->retStrDescricaoUnidade();
    $objObservacaoDTO->retStrDescricao();
    
    $objObservacaoDTO->setDblIdProtocolo($dblIdProtocolo);
    $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),InfraDTO::$OPER_DIFERENTE);
    
    $objObservacaoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);


    $objObservacaoRN = new ObservacaoRN();
    $arrObjObservacaoDTO = $objObservacaoRN->listarRN0219($objObservacaoDTO);

    $numRegistros = count($arrObjObservacaoDTO);
    $strResultado = '';
    
    if ($numRegistros > 0){
    
      $strSumarioTabela = 'Tabela de observações de outras unidades.';
      $strCaptionTabela = 'observações de outras unidades';
        
      $strResultado = '';
      $strResultado .= '<table width="85%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="25%">Unidade</th>'."\n";
      $strResultado .= '<th class="infraTh">Observação</th>'."\n";
      $strResultado .= '</tr>';
      
      $strCssTr = '';
      foreach ($arrObjObservacaoDTO as $objObservacaoDTO){
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;
        $strResultado .= '<td valign="top" align="center"><a alt="'.PaginaSEI::tratarHTML($objObservacaoDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objObservacaoDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objObservacaoDTO->getStrSiglaUnidade()).'</a></td>';
        $strResultado .= '<td>'.nl2br(PaginaSEI::tratarHTML($objObservacaoDTO->getStrDescricao())).'</td>';
        $strResultado .= '</tr>';
      }
      
      $strResultado .= '</table>';
    }
    
    return $strResultado;
    
  }
}
?>