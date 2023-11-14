<?php  
/*************************************************************  
 * TRF4
 * @package infra_php
 * 
 * criado por mkr@trf4.jus.br em 23/07/2012
 */
  
require_once dirname(__FILE__).'/phpqrcode/qrlib.php';

class InfraQRCode{               
  static public function gerar($strDados, $strNomeArquivoCompleto, $strErrorCorrection = 'L', $numSquarePixels = 4, $numSquaresBoundaryAround = 2){
    QRcode::png($strDados, $strNomeArquivoCompleto, $strErrorCorrection, $numSquarePixels, $numSquaresBoundaryAround);        
  }    
}  
?>