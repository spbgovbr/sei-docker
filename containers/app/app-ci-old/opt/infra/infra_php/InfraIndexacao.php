<?
/**
 * @package infra_php
 *
 */

class InfraIndexacao {
	
	private function __construct(){
		
	}
	
  //public static function prepararIndexacao($txt) {
  public static function prepararTexto($txt) {
  	$ret = '';
  	if ( $txt != '' ) {
  		$ret = InfraString::excluirAcentos($txt);
  		$ret = strtolower($ret);
  		$ret = self::removerPontoNumeros($ret);
  	}
  	return $ret;
  }

  //MONTA UM VETOR COM AS PALAVRAS ENTRE ASPAS AGRUPADAS
  //public static function agruparItens($palavras) {
  public static function prepararPalavrasChave($palavras) {
   //echo $palavras;die;
   $vetor = array();
   $indice_vetor = 0;
   //TIRA AS BARRAS DO POST
   $palavras = str_replace("\\", "", $palavras);
   for ($i=0; $i<strlen($palavras); $i++) {
     $nova_palavra = "";
     if ($palavras{$i} != " " && $palavras{$i} != "(" && $palavras{$i}!=")"){
       if ($palavras{$i} != "\"") {
         while (($i<strlen($palavras)) && 
         ($palavras{$i} != " ") && 
         ($palavras{$i} != "(") && 
         ($palavras{$i} != ")")) {
           $nova_palavra .= $palavras{$i};
           $i++;
         }
         $vetor[$indice_vetor] = $nova_palavra;
         $indice_vetor++;
       } else {
         $i++;
         while (($i<strlen($palavras)) && 
         ($palavras{$i} != "\"") && 
         ($palavras{$i} != "(") && 
         ($palavras{$i} != ")")) {
           $nova_palavra .= $palavras{$i};
           $i++;
         }
         $vetor[$indice_vetor] = $nova_palavra;
         $indice_vetor++;
         
       }
     }
     if ($palavras{$i} == "(" || $palavras{$i}== ")"){
       $vetor[$indice_vetor] = $palavras{$i};
       $indice_vetor++;
     }                 
   }
   return $vetor;
  }
  
  //O mecanismo de indexacao do SQL Server não encontra corretamente
  //números com ponto (8.112 por exemplo), esta função retira os pontos
  //existentes entre números. Ao submeter a consulta no banco os 
  //dados informados devem receber o mesmo tratamento
   private static function removerPontoNumeros($str){
    $ret = '';
    for($i=0;$i<strlen($str);$i++){
      if (($str{$i}=='.') && ($i>0) && (($i+1) < strlen($str))){
        if (!is_numeric($str{$i-1}) || !is_numeric($str{$i+1})){
          $ret .= $str{$i};
        }
      }else{
        $ret .= $str{$i};
      } 
    }
    return $ret;
   }
  
  //MARCA AS PALAVRAS PESQUISADAS
  public static function marcarPalavrasPesquisadas($texto, $palavras) {
   //AS PALAVRAS PODEM TER CARACTERES ESPECIAIS À ESQUERDA (NÃO PODEM SER TAGS)
   $vetor_caracteres_especiais = array(">", "/", ",", "-", "(", ".", " ","\n");
   $resultado = self::removerAcentosHTML($texto);
   $vetor_palavras_chave = self::agruparItens($palavras);
   for ($j=0; $j<count($vetor_palavras_chave); $j++) {
     for ($i=0; $i<count($vetor_caracteres_especiais); $i++) {
       //MONTA POSSIBILIDADES
       $palavra = $vetor_caracteres_especiais[$i].$vetor_palavras_chave[$j];
       $palavra_marcada = $vetor_caracteres_especiais[$i]."<span class=\"marcas\">".substr($palavra, 1, strlen($palavra))."</span>";
       $palavra_maiuscula = self::transformarCaixaAlta($palavra);
       $palavra_maiuscula_marcada = $vetor_caracteres_especiais[$i]."<span class=\"marcas\">".substr($palavra_maiuscula, 1, strlen($palavra_maiuscula))."</span>";
       $palavra_minuscula = self::transformarCaixaBaixa($palavra);
       $palavra_minuscula_marcada = $vetor_caracteres_especiais[$i]."<span class=\"marcas\">".substr($palavra_minuscula, 1, strlen($palavra_minuscula))."</span>";
       $palavra_primeira_maiuscula = $vetor_caracteres_especiais[$i].self::transformarCaixaAlta(substr($palavra_minuscula, 1, 1)).substr($palavra_minuscula, 2, strlen($palavra_minuscula));
       $palavra_primeira_maiuscula_marcada = $vetor_caracteres_especiais[$i]."<span class=\"marcas\">".substr($palavra_primeira_maiuscula, 1, strlen($palavra_primeira_maiuscula))."</span>";
       //FAZ AS SUBSTITUIÇÕES
       $resultado = str_replace($palavra, $palavra_marcada, $resultado);
       $resultado = str_replace($palavra_maiuscula, $palavra_maiuscula_marcada, $resultado);
       $resultado = str_replace($palavra_minuscula, $palavra_minuscula_marcada, $resultado);
       $resultado = str_replace($palavra_primeira_maiuscula, $palavra_primeira_maiuscula_marcada, $resultado);
     }
   }
   return $resultado;
  }
  
  //MARCA AS PALAVRAS PESQUISADAS
  private static function marcarPalavrasPesquisadasIdx($textoOrig,$textoIdx, $palavras) {
   $resultado = "";
   $palavras_chave = self::agruparItens($palavras);
   $original = $textoOrig;
   $indexacao = $textoIdx;
   for ($i=0; $i<count($palavras_chave); $i++) {
     $posIni = 0;
     $posFim = 0;
     while (true) {
       $posFim = strpos($indexacao, $palavras_chave[$i],$posFim);
       if ( $posFim === false ) {
         break;
       }
       $original = substr($original, $posIni, $posFim).
       "<span class=\"marcas\">".
       substr($original, $posFim, strlen($palavras_chave[$i])).
       "</span>".
       substr($original, $posFim + strlen($palavras_chave[$i]));
       
       $indexacao = substr($indexacao, $posIni, $posFim).
       "<span class=\"marcas\">".
       substr($indexacao, $posFim, strlen($palavras_chave[$i])).
       "</span>".
       substr($indexacao, $posFim + strlen($palavras_chave[$i]));
       
       $posFim = $posFim + strlen("<span class=\"marcas\">") + 
       strlen($palavras_chave[$i]) + 
       strlen("</span>");                      
     }
   }
   return $original;
  }
  
  private static function formatarPesquisa($palavras) {
   $dados = self::agruparItens($palavras);
   for($i=0;$i<count($dados);$i++){
     
     if ( strpos($dados[$i]," ") !== false ||
     strpos($dados[$i],",") !== false ||
     (strpos($dados[$i],"*") !== false && strpos($dados[$i],"\"") === false)) {
       $dados[$i] = "\"".$dados[$i]."\"";
     }
     
     if($dados[$i] == "e") {
       $dados[$i] = "and";
     }
     else if($dados[$i]=="ou") {
       $dados[$i] = "or";
     }
     else if($dados[$i]=="nao") {
       $dados[$i] = "and not";
     }
     else if($dados[$i]=="prox") {
       $dados[$i] = "near";
     }
   }
  
   /* 
   $dados_formatados = "";         
   for($i=0;$i<count($dados);$i++){
     $dados_formatados .= " ".$dados[$i];
   }
   */
   
  	$dados_formatados = "";			
  	for($i=0;$i<count($dados);$i++){
  	  
  	  //Adiciona operador and como padrão se não informado
  	  if ($i>0){
  	    if (!in_array($dados[$i-1],array('and','or','and not','near','(')) &&
  	        !in_array($dados[$i],array('and','or','and not','near',')'))){
  	      $dados_formatados .= " and";
  	    } 
  	  }
  		$dados_formatados .= " ".$dados[$i];
  	}
   
   
   //echo $dados_formatados;
   return $dados_formatados; 
  }
}
?>