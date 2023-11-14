<?php
/**
 * @package infra_php
 *
 */

class InfraString
{

    private static $arrMinusculas = array(
        'a',
        'a',
        'a',
        'a',
        'a',
        'e',
        'e',
        'e',
        'e',
        'i',
        'i',
        'o',
        'o',
        'o',
        'o',
        'u',
        'u',
        'c',
        'n'
    );
    private static $arrMaiusculas = array(
        'A',
        'A',
        'A',
        'A',
        'A',
        'E',
        'E',
        'E',
        'E',
        'I',
        'I',
        'O',
        'O',
        'O',
        'O',
        'U',
        'U',
        'C',
        'N'
    );
    private static $arrMinusculasAcentuadas = array(
        'á',
        'ã',
        'à',
        'ä',
        'â',
        'é',
        'è',
        'ë',
        'ê',
        'í',
        'ï',
        'ó',
        'õ',
        'ô',
        'ö',
        'ú',
        'ü',
        'ç',
        'ñ'
    );
    private static $arrMaiusculasAcentuadas = array(
        'Á',
        'Ã',
        'À',
        'Ä',
        'Â',
        'É',
        'È',
        'Ë',
        'Ê',
        'Í',
        'Ï',
        'Ó',
        'Õ',
        'Ô',
        'Ö',
        'Ú',
        'Ü',
        'Ç',
        'Ñ'
    );
    private static $arrMinusculasAcentuadasHTML = array(
        '&aacute;',
        '&atilde;',
        '&agrave;',
        '&auml;',
        '&acirc;',
        '&eacute;',
        '&egrave;',
        '&euml;',
        '&ecirc;',
        '&iacute;',
        '&iuml;',
        '&oacute;',
        '&otilde;',
        '&ocirc;',
        '&ouml;',
        '&uacute;',
        '&uuml;',
        '&ccedil;',
        '&ntilde;'
    );
    private static $arrMaiusculasAcentuadasHTML = array(
        '&Aacute;',
        '&Atilde;',
        '&Agrave;',
        '&Auml;',
        '&Acirc;',
        '&Eacute;',
        '&Egrave;',
        '&Euml;',
        '&Ecirc;',
        '&Iacute;',
        '&Iuml;',
        '&Oacute;',
        '&Otilde;',
        '&Ocirc;',
        '&Ouml;',
        '&Uacute;',
        '&Uuml;',
        '&Ccedil;',
        '&Ntilde;'
    );
    private static $numTotalCaracteres = 18;

    private function __construct()
    {
    }

    /**
     * Converte o texto para letras maiúsculas
     *
     * @access public
     * @param string $dados - texto para conversão
     * @return string texto convertido
     */
    public static function transformarCaixaAlta($dados)
    {
        $dados = strtoupper($dados);
        for ($i = 0; $i < self::$numTotalCaracteres; $i++) {
            $dados = str_replace(self::$arrMinusculasAcentuadas[$i], self::$arrMaiusculasAcentuadas[$i], $dados);
        }
        return $dados;
    }

    /**
     * Converte o texto para letras minúsculas
     *
     * @access public
     * @param string $dados - texto para conversão
     * @return string texto convertido
     */
    public static function transformarCaixaBaixa($dados)
    {
        $dados = strtolower($dados);
        for ($i = 0; $i < self::$numTotalCaracteres; $i++) {
            $dados = str_replace(self::$arrMaiusculasAcentuadas[$i], self::$arrMinusculasAcentuadas[$i], $dados);
        }
        return $dados;
    }

    /**
     * Substitui os caracteres acentuados por não-acentuados
     *
     * @access public
     * @param string $dados - texto para substituição
     * @return string texto substituído
     */
    public static function excluirAcentos($dados)
    {
        for ($i = 0; $i < self::$numTotalCaracteres; $i++) {
            $dados = str_replace(self::$arrMinusculasAcentuadas[$i], self::$arrMinusculas[$i], $dados);
            $dados = str_replace(self::$arrMaiusculasAcentuadas[$i], self::$arrMaiusculas[$i], $dados);
        }
        return $dados;
        /*
      $arr = array('/[ÂÀÁÄÃ]/'=>'A',
                                '/[âãàáä]/'=>'a',
                                '/[ÊÈÉË]/'=>'E',
                                '/[êèéë]/'=>'e',
                                '/[ÎÍÌÏ]/'=>'I',
                                '/[îíìï]/'=>'i',
                                '/[ÔÕÒÓÖ]/'=>'O',
                                '/[ôõòóö]/'=>'o',
                                '/[ÛÙÚÜ]/'=>'U',
                                '/[ûúùü]/'=>'u',
                                '/ç/'=>'c',
                                '/Ç/'=> 'C');
      return preg_replace(array_keys($arr), array_values($arr), $str);
        */
    }

    /**
     * Substitui os caracteres acentuados pelos respectivos códigos HTML
     *
     * @access public
     * @param string $dados - texto para substituição
     * @return string texto substituído
     */
    public static function acentuarHTML($dados)
    {
        for ($i = 0; $i < self::$numTotalCaracteres; $i++) {
            $dados = str_replace(self::$arrMinusculasAcentuadas[$i], self::$arrMinusculasAcentuadasHTML[$i], $dados);
            $dados = str_replace(self::$arrMaiusculasAcentuadas[$i], self::$arrMaiusculasAcentuadasHTML[$i], $dados);
        }
        return $dados;
    }

    /**
     * Substitui os códigos HTML pelos respectivos caracteres acentuados
     *
     * @access public
     * @param string $dados - texto para substituição
     * @return string texto substituído
     */
    public static function removerAcentosHTML($dados)
    {
        for ($i = 0; $i < self::$numTotalCaracteres; $i++) {
            $dados = str_replace(self::$arrMinusculasAcentuadasHTML[$i], self::$arrMinusculasAcentuadas[$i], $dados);
            $dados = str_replace(self::$arrMaiusculasAcentuadasHTML[$i], self::$arrMaiusculasAcentuadas[$i], $dados);
        }
        return $dados;
    }

    /**
     * Testa se uma string é vazia ou nula
     *
     * @access public
     * @param string $str - string para teste
     * @return bool true (se for vazia) ou false
     */
    public static function isBolVazia($str)
    {
        if ($str === null || trim($str) === '') {
            return true;
        }
        return false;
    }

    /**
     * Substitui os caracteres &, <, > e " pelos seus códigos XML
     * As quebras-de-linha (<br />) serão mantidas.
     *
     * @access public
     * @param string $str - string para substituição
     * @return string texto substituído
     */

    public static function formatarXML($str)
    {
        $str = str_replace('&', '&amp;', $str);
        $str = str_replace('<', '&lt;', $str);
        $str = str_replace('>', '&gt;', $str);
        $str = str_replace('\"', '&quot;', $str);
        $str = str_replace('"', '&quot;', $str);

        $str = str_replace('&amp;lt;', '&lt;', $str);
        $str = str_replace('&amp;gt;', '&gt;', $str);
        $str = str_replace('&amp;quot;', '&quot;', $str);

        return $str;
    }

    public static function removerFormatacaoXML($str)
    {
        $str = str_replace('&quot;', '"', $str);
        $str = str_replace('&apos;', "'", $str);
        $str = str_replace('&#39;', "'", $str);
        $str = str_replace('&#039;', "'", $str);
        $str = str_replace('&lt;', '<', $str);
        $str = str_replace('&gt;', '>', $str);
        $str = str_replace('&amp;', '&', $str);
        return $str;
    }

    /**
     * Substitui a pontuação (ponto, vírgula, ponto e vírgula, dois pontos, exclamação
     * e interrogação) por espaço em branco
     *
     * @access public
     * @param string $txt - string para substituição
     * @return string texto substituído
     */
    public static function excluirPontuacao($txt)
    {
        $arrPontuacao = array('.', ',', ';', ':', '!', '?');
        return str_replace($arrPontuacao, ' ', $txt);
    }

    public static function prepararIndexacao($txt, $bolSomenteLetrasNumeros = false)
    {
        $ret = '';
        $txt = trim($txt);
        if ($txt != '') {
            $ret = self::prepararPesquisa($txt);

            if ($bolSomenteLetrasNumeros) {
                $ret = preg_replace('/[^a-z0-9 ]/', '', $ret);
            } else {
                $ret = preg_replace('/[^\x20-\x7Eªº°§]/', '', $ret);
            }

            $ret = self::substituirIterativo('  ', ' ', $ret);
        }
        return $ret;
    }

    public static function prepararPesquisa($txt)
    {
        $ret = str_replace(array("\n", "\r", "\t", "\0"), ' ', $txt);
        $ret = self::excluirAcentos($ret);
        $ret = self::transformarCaixaBaixa($ret);
        $ret = self::substituirIterativo('  ', ' ', $ret);
        return trim($ret);
    }

    public static function prepararPesquisaDTO(
        InfraDTO $objDTO,
        $strAtributo,
        $strAtributoPesquisa = null,
        $bolUnsetAtributo = true,
        $bolSomenteLetrasNumeros = false
    ) {
        if ($objDTO->isSetAtributo($strAtributo)) {
            $strPalavrasPesquisa = InfraString::prepararIndexacao($objDTO->get($strAtributo), $bolSomenteLetrasNumeros);

            if (!InfraString::isBolVazia($strPalavrasPesquisa)) {
                if ($strAtributoPesquisa == null) {
                    $strAtributoPesquisa = $strAtributo;
                }

                $objDTO->set($strAtributoPesquisa, $strPalavrasPesquisa);

                self::tratarPalavrasPesquisaDTO($objDTO, $strAtributoPesquisa);

                if ($bolUnsetAtributo && $strAtributo != $strAtributoPesquisa) {
                    $objDTO->unSetAtributo($strAtributo);
                }
            }
        }
        return $objDTO;
    }

    public static function tratarPalavrasPesquisaDTO(InfraDTO $objDTO, $strAtributo)
    {
        if ($objDTO->isSetAtributo($strAtributo)) {
            $strPalavrasPesquisa = trim($objDTO->get($strAtributo));

            if ($strPalavrasPesquisa != '') {
                $arrPalavrasPesquisa = explode(' ', $strPalavrasPesquisa);

                $numPalavrasPesquisa = count($arrPalavrasPesquisa);

                if ($numPalavrasPesquisa) {
                    for ($i = 0; $i < $numPalavrasPesquisa; $i++) {
                        $arrPalavrasPesquisa[$i] = '%' . $arrPalavrasPesquisa[$i] . '%';
                    }

                    if ($numPalavrasPesquisa == 1) {
                        $objDTO->set($strAtributo, $arrPalavrasPesquisa[0], InfraDTO::$OPER_LIKE);
                    } else {
                        $a = array_fill(0, $numPalavrasPesquisa, $strAtributo);
                        $b = array_fill(0, $numPalavrasPesquisa, InfraDTO::$OPER_LIKE);
                        $d = array_fill(0, $numPalavrasPesquisa - 1, InfraDTO::$OPER_LOGICO_AND);
                        $objDTO->adicionarCriterio($a, $b, $arrPalavrasPesquisa, $d);

                        $objDTO->unSetAtributo($strAtributo);
                    }
                }
            }
        }
        return $objDTO;
    }


    //MARCA AS PALAVRAS PESQUISADAS
    public static function marcarPalavrasPesquisadas($texto, $palavras)
    {
        //AS PALAVRAS PODEM TER CARACTERES ESPECIAIS À ESQUERDA (NÃO PODEM SER TAGS)
        $vetor_caracteres_especiais = array(">", "/", ",", "-", "(", ".", " ", "\n");
        $resultado = self::removerAcentosHTML($texto);
        $vetor_palavras_chave = self::agruparItens($palavras);
        $numPalavrasChave = count($vetor_palavras_chave);
        $numCaractesEspeciais = count($vetor_caracteres_especiais);
        for ($j = 0; $j < $numPalavrasChave; $j++) {
            for ($i = 0; $i < $numCaractesEspeciais; $i++) {
                //MONTA POSSIBILIDADES
                $palavra = $vetor_caracteres_especiais[$i] . $vetor_palavras_chave[$j];
                $palavra_marcada = $vetor_caracteres_especiais[$i] . "<span class=\"marcas\">" . substr(
                        $palavra,
                        1,
                        strlen($palavra)
                    ) . "</span>";
                $palavra_maiuscula = self::transformarCaixaAlta($palavra);
                $palavra_maiuscula_marcada = $vetor_caracteres_especiais[$i] . "<span class=\"marcas\">" . substr(
                        $palavra_maiuscula,
                        1,
                        strlen($palavra_maiuscula)
                    ) . "</span>";
                $palavra_minuscula = self::transformarCaixaBaixa($palavra);
                $palavra_minuscula_marcada = $vetor_caracteres_especiais[$i] . "<span class=\"marcas\">" . substr(
                        $palavra_minuscula,
                        1,
                        strlen($palavra_minuscula)
                    ) . "</span>";
                $palavra_primeira_maiuscula = $vetor_caracteres_especiais[$i] . self::transformarCaixaAlta(
                        substr($palavra_minuscula, 1, 1)
                    ) . substr($palavra_minuscula, 2, strlen($palavra_minuscula));
                $palavra_primeira_maiuscula_marcada = $vetor_caracteres_especiais[$i] . "<span class=\"marcas\">" . substr(
                        $palavra_primeira_maiuscula,
                        1,
                        strlen($palavra_primeira_maiuscula)
                    ) . "</span>";
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
    public static function marcarPalavrasPesquisadasIdx($textoOrig, $textoIdx, $palavras)
    {
        $palavras_chave = self::agruparItens($palavras);
        $original = $textoOrig;
        $indexacao = $textoIdx;
        $numPalavrasChave = count($palavras_chave);
        for ($i = 0; $i < $numPalavrasChave; $i++) {
            $posIni = 0;
            $posFim = 0;
            while (true) {
                $posFim = strpos($indexacao, $palavras_chave[$i], $posFim);
                if ($posFim === false) {
                    break;
                }
                $original = substr($original, $posIni, $posFim) .
                    "<span class=\"marcas\">" .
                    substr($original, $posFim, strlen($palavras_chave[$i])) .
                    "</span>" .
                    substr($original, $posFim + strlen($palavras_chave[$i]));

                $indexacao = substr($indexacao, $posIni, $posFim) .
                    "<span class=\"marcas\">" .
                    substr($indexacao, $posFim, strlen($palavras_chave[$i])) .
                    "</span>" .
                    substr($indexacao, $posFim + strlen($palavras_chave[$i]));

                $posFim = $posFim + strlen("<span class=\"marcas\">") +
                    strlen($palavras_chave[$i]) +
                    strlen("</span>");
            }
        }
        return $original;
    }

    //MONTA UM VETOR COM AS PALAVRAS ENTRE ASPAS AGRUPADAS
    public static function agruparItens($strPalavras)
    {
        $arrVetor = array();
        $numIndice = 0;

        $strPalavras = str_replace("\\", "", $strPalavras);

        $numTamPalavras = strlen($strPalavras);

        for ($i = 0; $i < $numTamPalavras; $i++) {
            $strNovaPalavra = "";
            if ($strPalavras[$i] != " " && $strPalavras[$i] != "(" && $strPalavras[$i] != ")") {
                if ($strPalavras[$i] != '"') {
                    while (($i < $numTamPalavras) &&
                        ($strPalavras[$i] != " ") &&
                        ($strPalavras[$i] != "(") &&
                        ($strPalavras[$i] != ")")) {
                        $strNovaPalavra .= $strPalavras[$i];
                        $i++;
                    }
                    $arrVetor[$numIndice++] = $strNovaPalavra;
                } else {
                    $i++;
                    while (($i < $numTamPalavras) && ($strPalavras[$i] != '"')) {
                        $strNovaPalavra .= $strPalavras[$i];
                        $i++;
                    }
                    $arrVetor[$numIndice++] = $strNovaPalavra;
                }
            }
            if ($i < $numTamPalavras && ($strPalavras[$i] == "(" || $strPalavras[$i] == ")")) {
                $arrVetor[$numIndice++] = $strPalavras[$i];
            }
        }
        return $arrVetor;
    }

    /*
    public static function formatarPesquisa($palavras) {
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
        elseif($dados[$i]=="ou") {
          $dados[$i] = "or";
        }
        elseif($dados[$i]=="nao") {
          $dados[$i] = "and not";
        }
        elseif($dados[$i]=="prox") {
          $dados[$i] = "near";
        }
      }

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

      return $dados_formatados;
    }
    */

    public static function substituirIterativo($strAntigo, $strNovo, $strTexto)
    {
        while (strpos($strTexto, $strAntigo) !== false) {
            $strTexto = str_replace($strAntigo, $strNovo, $strTexto);
        }
        return $strTexto;
    }

    public static function formatarJavaScript($str)
    {
        $str = str_replace('"', '&quot;', $str);
        $str = str_replace("\'", "'", $str);
        $str = str_replace('\'', '\\\'', $str);
        $str = str_replace('\\\\n', '\\n', $str);
        return $str;
    }

    public static function formatarNome($strNome)
    {
        $arrNome = explode(' ', InfraString::transformarCaixaBaixa(trim($strNome)));
        $strTemp = '';
        $arrParticulas = array('da' => 'da', 'das' => 'das', 'de' => 'de', 'do' => 'do', 'dos' => 'dos', 'e' => 'e');
        foreach ($arrNome as $parte) {
            if (!isset($arrParticulas[$parte])) {
                $parte = InfraString::transformarCaixaAlta(substr($parte, 0, 1)) . substr($parte, 1);
            }
            if ($strTemp != '') {
                $strTemp .= ' ';
            }
            $strTemp .= $parte;
        }
        return $strTemp;
    }

    /**
     * Formata array passado para exibição
     * @param array $arrayFormatar
     * @param string $separadorIntermediario
     * @param string $separadorFinal
     * @return string
     */
    public static function formatarArray($arrayFormatar, $separadorIntermediario = ', ', $separadorFinal = ' e ')
    {
        if (count($arrayFormatar) > 1) {
            $ultimaPos = array_pop($arrayFormatar);
            $strExt = implode($separadorIntermediario, $arrayFormatar);

            $str = $strExt . $separadorFinal . $ultimaPos;
            return $str;
        } elseif (count($arrayFormatar) == 1) {
            return $arrayFormatar[0];
        }
        return '';
    }


    /**
     * Verifica se a string $strConteudo inicia com a string $strConteudoBuscado
     * @param $strConteudo
     * @param $strConteudoBuscado
     * @param bool $bolSensivelCaixa Se true, pesquisa sensível a caixa
     * @return bool
     */
    public static function isBolIniciaCom($strConteudo, $strConteudoBuscado, $bolSensivelCaixa = true)
    {
        if ($strConteudoBuscado === '') {
            return true;
        }
        if ($bolSensivelCaixa) {
            return strpos($strConteudo, $strConteudoBuscado) === 0;
        }
        return stripos($strConteudo, $strConteudoBuscado) === 0;
    }

    /**
     * Verifica se a string $strConteudo termina com a string $strConteudoBuscado
     * @param $strConteudo
     * @param $strConteudoBuscado
     * @param bool $bolSensivelCaixa Se true, pesquisa sensível a caixa
     * @return bool
     */
    public static function isBolTerminaCom($strConteudo, $strConteudoBuscado, $bolSensivelCaixa = true)
    {
        if ($strConteudoBuscado === '') {
            return true;
        }
        if ($bolSensivelCaixa) {
            return (strpos($strConteudo, $strConteudoBuscado) + strlen($strConteudoBuscado) === strlen($strConteudo));
        }
        return (stripos($strConteudo, $strConteudoBuscado) + strlen($strConteudoBuscado) === strlen($strConteudo));
    }

    public static function limparParametrosPhp($strTexto)
    {
        $strRet = '';
        $bolLendoString = false;
        for ($i = 0; $i < strlen($strTexto); $i++) {
            if ($strTexto[$i] == '\'') {
                if ($bolLendoString) {
                    $bolLendoString = false;
                } else {
                    $bolLendoString = true;
                }
                $strRet .= '\'';
            } else {
                if (!$bolLendoString) {
                    $strRet .= $strTexto[$i];
                }
            }
        }
        return $strRet;
    }

    private static function contarCaracteresTrechosDestacados(
        $arrPosDestaque,
        $lengthReticenciasIni,
        $textoDestaque,
        $lengthReticencias
    ) {
        $lengthJaComputado = 0;

        //se comeca no inicio do texto nao tem reticencias
        if ($arrPosDestaque[0]['ini'] > 0) {
            $lengthJaComputado += $lengthReticenciasIni;
        }

        //descobre quantos caracteres fazem parte do trecho destacado, descontando os caracteres das tags html
        foreach ($arrPosDestaque as $trecho) {
            $strTemp = substr($textoDestaque, $trecho['ini'], $trecho['fim'] - $trecho['ini']);
            $strTemp = strip_tags($strTemp);
            $lengthJaComputado += strlen($strTemp);
            if ($trecho['fim'] < strlen($textoDestaque)) {
                $lengthJaComputado += $lengthReticencias;
            }
        }

        return $lengthJaComputado;
    }

    public static function abreviarDestacarTexto(
        $texto,
        $substring,
        $tamanhoMax,
        $htmlDestaqueIni,
        $htmlDestaqueFim,
        $maxTrechos = 3
    ) {
        $reticencias = '... ';
        $lengthReticencias = strlen($reticencias);
        $reticenciasIni = '...';
        $lengthReticenciasIni = strlen($reticenciasIni);

        //retira as tags html do texto
        $texto = strip_tags($texto);

        //troca os caracteres de fim de linha por espaços
        $texto = preg_replace('/\s/', ' ', $texto);
        $texto = str_replace('  ', ' ', $texto);

        //coloca as palavras-chave em destaque
        $palavras = explode(' ', $substring);
        $p = implode('|', array_map('preg_quote', $palavras));

        $textoDestaque = preg_replace(
            "/\b(" . preg_quote($p, '/') . ")\b/i",
            $htmlDestaqueIni . '$1' . $htmlDestaqueFim,
            $texto
        );

        //se já for menor que o tamanho máximo, retorna o texto completo
        if (strlen($texto) <= $tamanhoMax) {
            return $textoDestaque;
        } else { //se for maior que o tamanho máximo
            $arrPosDestaque = array();
            $cursor = 0;

            //monta array com as posicoes de inicio e fim das palavras-chave destacadas
            while ($cursor < strlen($textoDestaque)) {
                $posIni = strpos($textoDestaque, $htmlDestaqueIni, $cursor);
                if ($posIni === false) {
                    break;
                }
                $posFim = strpos($textoDestaque, $htmlDestaqueFim, $posIni) + strlen($htmlDestaqueFim);
                $arrPosDestaque[] = array('ini' => $posIni, 'fim' => $posFim);
                $cursor = $posFim;
            }

            //numero de palavras-chave encontradas no texto
            $numSubstr = InfraArray::contar($arrPosDestaque);

            do {
                $mudou = false;
                //se tiver mais ocorrencias do que o maximo de trechos, devem ser escolhidas as ocorrencias que irao aparecer
                if ($numSubstr > $maxTrechos) {
                    $arrPosDestaqueTemp = $arrPosDestaque;
                    $arrPosDestaque = array();
                    if ($maxTrechos > 1) {
                        $intervalo = floor(($numSubstr - 1) / ($maxTrechos - 1));
                    } else {
                        $intervalo = 0;
                    }

                    $cont = 0;
                    for ($i = 0; $i < $numSubstr; $i = $i + $intervalo) {
                        if ($cont >= $maxTrechos) {
                            break;
                        }
                        $arrPosDestaque[] = $arrPosDestaqueTemp[$i];
                        $cont++;
                    }
                    $numSubstr = InfraArray::contar($arrPosDestaque);
                }

                $lengthJaComputado = self::contarCaracteresTrechosDestacados(
                    $arrPosDestaque,
                    $lengthReticenciasIni,
                    $textoDestaque,
                    $lengthReticencias
                );

                //se somente as palavras-chave já for maior do que o tamanho máximo permitido, deve ser diminuído o número de trechos
                if ($lengthJaComputado > $tamanhoMax) {
                    if ($maxTrechos > 1) {
                        $maxTrechos--;
                        $mudou = true;
                    } else { //se o tamanho máximo não permitir nem uma única palavra, retorna um erro
                        return false;
                    }
                }
            } while ($mudou);

            //retira as tags html de tudo que nao forem os trechos selecionados
            $textoDestaqueTemp = $textoDestaque;
            $textoDestaque = '';
            $cursor = 0;
            for ($i = 0; $i < $numSubstr; $i++) {
                $textoDestaque .= strip_tags(
                        substr($textoDestaqueTemp, $cursor, $arrPosDestaque[$i]['ini'] - $cursor)
                    ) . substr(
                        $textoDestaqueTemp,
                        $arrPosDestaque[$i]['ini'],
                        $arrPosDestaque[$i]['fim'] - $arrPosDestaque[$i]['ini']
                    );
                $cursor = $arrPosDestaque[$i]['fim'];
            }
            $textoDestaque .= strip_tags(
                substr(
                    $textoDestaqueTemp,
                    $arrPosDestaque[$i - 1]['fim'],
                    strlen($textoDestaqueTemp) - $arrPosDestaque[$i - 1]['fim']
                )
            );

            //remarca as pocicoes dos trechos destacados
            if ($textoDestaque <> $textoDestaqueTemp) {
                $arrPosDestaque = array();
                $cursor = 0;
                //monta array com as posicoes de inicio e fim das palavras-chave destacadas
                while ($cursor < strlen($textoDestaque)) {
                    $posIni = strpos($textoDestaque, $htmlDestaqueIni, $cursor);
                    if ($posIni === false) {
                        break;
                    }
                    $posFim = strpos($textoDestaque, $htmlDestaqueFim, $posIni) + strlen($htmlDestaqueFim);
                    $arrPosDestaque[] = array('ini' => $posIni, 'fim' => $posFim);
                    $cursor = $posFim;
                }
            }

            //encontra o tamanho de fragmento ideal
            do {
                $mudou = false;


                $lengthJaComputado = self::contarCaracteresTrechosDestacados(
                    $arrPosDestaque,
                    $lengthReticenciasIni,
                    $textoDestaque,
                    $lengthReticencias
                );

                //numero de palavras-chave
                $numSubstr = InfraArray::contar($arrPosDestaque);

                //cada palavra-chave sera envolvida por 2 fragmentos, um antes, outro depois
                $numFrag = $numSubstr * 2;

                //se a palavra-chave esta no inicio do texto nao tem fragmento antes
                if ($arrPosDestaque[0]['ini'] == 0) {
                    $numFrag--;
                }

                //se a palavra-chave esta no fim do texto nao tem fragmento depois
                if ($arrPosDestaque[$numSubstr - 1]['fim'] == strlen($textoDestaque)) {
                    $numFrag--;
                }

                //encontra o tamanho de cada fragmento
                $tamanhoFrag = floor(($tamanhoMax - $lengthJaComputado) / $numFrag);


                //verifica se, ao aplicar os fragmentos, vai ter colisao com o inicio do texto, com o fim do texto ou com o termino do fragmento anterior, se houver colisao, o trecho nao deve ser considerado um fragmento e o calculo do fragmento deve ser refeito
                $arrPosDestaqueTemp = $arrPosDestaque;
                $arrPosDestaque = array();
                $cont = 0;
                for ($i = 0; $i < $numSubstr; $i++) {
                    $arrPosDestaque[$cont]['ini'] = $arrPosDestaqueTemp[$i]['ini'];
                    $arrPosDestaque[$cont]['fim'] = $arrPosDestaqueTemp[$i]['fim'];

                    //colisao do fragmento com o inicio do texto
                    if ($i == 0 && $arrPosDestaqueTemp[$i]['ini'] > 0 && ($arrPosDestaqueTemp[$i]['ini'] - $tamanhoFrag) < 0) {
                        $arrPosDestaque[$cont]['ini'] = 0;
                        $mudou = true;
                    }

                    //colisao do fragmento com o fim do fragmento anterior
                    if ($i > 0 && ($arrPosDestaqueTemp[$i]['ini'] - $tamanhoFrag) <= $arrPosDestaqueTemp[$i - 1]['fim'] + $tamanhoFrag) {
                        $arrPosDestaque[$cont - 1]['fim'] = $arrPosDestaqueTemp[$i]['fim'];
                        unset($arrPosDestaque[$cont]);
                        $cont--;
                        $mudou = true;
                    }

                    //colisao do fragmento com o termino do texto
                    if ($arrPosDestaqueTemp[$i]['fim'] < strlen(
                            $textoDestaque
                        ) && ($arrPosDestaqueTemp[$i]['fim'] + $tamanhoFrag) > strlen($textoDestaque)) {
                        $arrPosDestaque[$cont]['fim'] = strlen($textoDestaque);
                        $mudou = true;
                        break;
                    }
                    $cont++;
                }
            } while ($mudou);

            $i = 0;
            $cursor = 0;
            //se for começar no inicio do texto nao precisa colocar as reticencias
            if ($arrPosDestaque[$i]['ini'] - $tamanhoFrag < $cursor) {
                $result = '';
            } else {
                $result = $reticenciasIni;
            }

            //loop para extrair os fragmentos de texto que contem as palavras-chave
            while ($i < $numSubstr) {
                //calcula a posicao inicial do fragmento
                if ($arrPosDestaque[$i]['ini'] - $tamanhoFrag < $cursor) {
                    $ini = $cursor;
                } else {
                    $ini = $arrPosDestaque[$i]['ini'] - $tamanhoFrag;
                }

                //calcula a posicao final do fragmento
                if ($arrPosDestaque[$i]['fim'] + $tamanhoFrag > strlen($textoDestaque)) {
                    $fim = strlen($textoDestaque);
                } else {
                    $fim = $arrPosDestaque[$i]['fim'] + $tamanhoFrag;
                }

                //adiciona o fragmento ao resultado
                $result .= substr($textoDestaque, $ini, ($fim - $ini));

                //se o fragmento termina antes do termino do texto, coloca reticencias
                if ($arrPosDestaque[$i]['fim'] < strlen($textoDestaque)) {
                    $result .= $reticencias;
                }

                $cursor = $fim;
                $i++;
            }


            //destaca novamente todas as palavras-chave que estao no resumo
            $result = strip_tags($result);
            $result = preg_replace(
                "/\b(" . preg_quote($p, '/') . ")\b/i",
                $htmlDestaqueIni . '$1' . $htmlDestaqueFim,
                $result
            );
            return $result;
        }
    }

    public static function obterValor($dados, $tag, $finalizador)
    {
        $ret = null;
        $ini = strpos($dados, $tag);
        if ($ini !== false) {
            $ini += strlen($tag);
            $fim = strpos($dados, $finalizador, $ini);
            if ($fim !== false) {
                $ret = trim(substr($dados, $ini, $fim - $ini));
            }
        }
        return $ret;
    }

    /**
     * @param string|null $string
     * @return string
     */
    public static function toUTF8($string)
    {
        if (is_null($string)) {
            return '';
        }

        if (extension_loaded('mbstring')) {
            return mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
        }

        return utf8_encode($string);
    }

    /**
     * @param string|null $value
     * @return string
     */
    public static function fromUTF8($value)
    {
        if (is_null($value)) {
            return '';
        }

        if (extension_loaded('mbstring')) {
            return mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
        }

        return utf8_decode($value);
    }
}

