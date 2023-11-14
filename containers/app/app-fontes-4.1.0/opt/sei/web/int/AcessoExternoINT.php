<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/05/2012 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcessoExternoINT extends InfraINT {

  public static function obterDadosDestinatario($dblIdProcedimento, $numIdContato){
    $numIdUsuarioExterno = null;
    $numIdParticipante = null;
    $strEmail = null;
    
    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->retNumIdUsuario();
    $objUsuarioDTO->retStrSigla();
    $objUsuarioDTO->setNumIdContato($numIdContato);
    $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_EXTERNO);

    $objUsuarioRN = new UsuarioRN();
    $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

    
    if ($objUsuarioDTO!=null){
      
      $numIdUsuarioExterno = $objUsuarioDTO->getNumIdUsuario();
      $strEmail = $objUsuarioDTO->getStrSigla();
      
    }else{
      
      $objParticipanteDTO = new ParticipanteDTO();
      $objParticipanteDTO->retNumIdParticipante();
      $objParticipanteDTO->retStrEmailContato();
      $objParticipanteDTO->setNumIdContato($numIdContato);
      $objParticipanteDTO->setDblIdProtocolo($dblIdProcedimento);
      $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
      
      $objParticipanteRN = new ParticipanteRN();
      $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);
      
      if ($objParticipanteDTO != null){
        
        $numIdParticipante = $objParticipanteDTO->getNumIdParticipante();
        $strEmail = $objParticipanteDTO->getStrEmailContato();
        
      }else{
        
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->retStrEmail();
        $objContatoDTO->setNumIdContato($numIdContato);
        
        $objContatoRN = new ContatoRN();
        $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
    
        if ($objContatoDTO!=null && $objContatoDTO->getStrEmail()!=null){
          $strEmail = $objContatoDTO->getStrEmail();
        }
      }
    }
    
    return array('IdUsuarioExterno' => $numIdUsuarioExterno, 'IdParticipante' => $numIdParticipante, 'Email' => $strEmail);
  }
}
?>