<?php
/**
 * @package infra_php
 *
 */

class InfraUtil
{

    private function __construct()
    {
    }

    /**
     * Avalia se a url È v·lida. O comando get_headers retorna um array com os cabeÁalhos
     * enviados pelo servidor em resposta ‡ requisiÁ„o HTTP. A funÁ„o avalia se h· um array
     * retornado (se chegou em algum servidor), e se a p·gina solicitada existe nesse servidor.
     * @param string $strUrl
     * @return boolean
     */
    public static function isBolUrlValida($strUrl)
    {
        $url_info = parse_url($strUrl);

        if (isset($url_info['scheme']) && $url_info['scheme'] == 'https') {
            $port = 443;
            @$fp = fsockopen('ssl://' . $url_info['host'], $port, $errno, $errstr);
        } else {
            $port = isset($url_info['port']) ? $url_info['port'] : 80;
            @$fp = fsockopen($url_info['host'], $port, $errno, $errstr);
        }

        if ($fp) {
            stream_set_timeout($fp, 60);
            $head = "HEAD " . @$url_info['path'] . "?" . @$url_info['query'];
            $head .= " HTTP/1.0\r\nHost: " . @$url_info['host'] . "\r\n\r\n";
            fputs($fp, $head);
            while (!feof($fp)) {
                if ($header = trim(fgets($fp, 1024))) {
                    $sc_pos = strpos($header, ':');
                    if ($sc_pos === false) {
                        $headers['status'] = $header;
                    } else {
                        $label = substr($header, 0, $sc_pos);
                        $value = substr($header, $sc_pos + 1);
                        $headers[strtolower($label)] = trim($value);
                    }
                }
            }

            if (strpos(strtoupper($headers['status']), '404 NOT FOUND') !== false) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica se o sinalizador È ou "S" ou "N", se n„o for retorna false.
     * Como regra da infra, somente esses dois valores s„o aceitos.
     * @param string $dados
     * @return boolean
     */
    public static function isBolSinalizadorValido($dados)
    {
        if ($dados !== 'S' && $dados !== 'N') {
            return false;
        }
        return true;
    }

    /**
     * Recebe o cÛdigo do processo informado, retira todos os elementos n„o numÈricos da string,
     * e dependendo do tamanho (10, 15 ou 20 dÌgitos), coloca os caracteres "." e "-" nas posiÁıes
     * padr„o respectivas, devolvendo a string do cÛdigo do processo no formato correto.
     * @param string $strProcesso
     * @return string
     */
    public static function formatarProcessoTrf4($strProcesso)
    {
        $strProcesso = self::retirarFormatacao($strProcesso);

        if (strlen($strProcesso) == 0) {
            return '';
        }

        if (strlen($strProcesso) == 10) {
            $strProcesso = substr($strProcesso, 0, 2) . "." .
                substr($strProcesso, 2, 2) . "." .
                substr($strProcesso, 4, 5) . "-" .
                substr($strProcesso, 9, 1);
        } elseif (strlen($strProcesso) == 20) {
            $strProcesso = substr($strProcesso, 0, 7) . "-" .
                substr($strProcesso, 7, 2) . "." .
                substr($strProcesso, 9, 4) . "." .
                substr($strProcesso, 13, 1) . "." .
                substr($strProcesso, 14, 2) . "." .
                substr($strProcesso, 16, 4);
        } else {
            $strProcesso = str_pad($strProcesso, 15, '0', STR_PAD_LEFT);
            $strProcesso = substr($strProcesso, 0, 4) . "." .
                substr($strProcesso, 4, 2) . "." .
                substr($strProcesso, 6, 2) . "." .
                substr($strProcesso, 8, 6) . "-" .
                substr($strProcesso, 14, 1);
        }
        return $strProcesso;
    }

    /**
     * Remove toda formataÁ„o da entrada deixando apenas n˙meros
     *
     * @param string $str
     * @return string
     */
    public static function retirarFormatacao($str, $bolRemoverLetras = true)
    {
        if ($bolRemoverLetras) {
            return preg_replace("/[^0-9]+/", '', $str);
        } else {
            return preg_replace("/[^0-9a-zA-Z]+/", '', $str);
        }
    }

    /**
     * Retorna validaÁ„o do dÌgito de controle do n˙mero do processo com 10, 15 ou 20 dÌgitos
     * @param string $strProcesso N˙mero do processo
     * @return  Boolean Resultado da validaÁ„o
     */
    public static function verificarProcessoTrf4($strProcesso)
    {
        $strProcesso = self::retirarFormatacao($strProcesso);

        if (!is_numeric($strProcesso)) {
            return false;
        }

        switch (strlen($strProcesso)) {
            //processos com 10 dÌgitos
            case 10:
                $mult = 1;
                $qtd = 9;
                break;

            //processos com 15 dÌgitos
            case 15:
                $mult = 7;
                $qtd = 14;
                break;

            case 20:
                $n = substr($strProcesso, 0, 7);
                $dv = substr($strProcesso, 7, 2);
                $a = substr($strProcesso, 9, 4);
                $jtr = substr($strProcesso, 13, 3);
                $o = substr($strProcesso, 16, 4);
                //$calc = calcula_verificador($n,$a,$jtr,$o);
                $calc = (98 - (((($n % 97) . $a . $jtr) % 97) . $o . "00") % 97);
                if ($dv == $calc) {
                    return true;
                }
                return false;
                break;

            default:
                return false;
        }

        $total = 0;
        for ($i = 0; $i < $qtd; $i++) {
            $total += $strProcesso[$i] * $mult;
            if (strlen($strProcesso) == 15) {
                $mult = ($mult == 2 ? 9 : $mult - 1);
            } else {
                $mult++;
            }
        }
        $mod11 = $total % 11;

        $dv = ($mod11 < 10 ? $mod11 : 0);

        if ($strProcesso[$qtd] == $dv) {
            return true;
        }

        return false;
    }

    /**
     * Confere se uma sequencia confere com o dÌgito verificador MÛdulo 11.
     * @param $strNumero
     * @return boolean
     * */
    public static function verificarModulo11($strNumero)
    {
        $strNumero = self::retirarFormatacao($strNumero);

        if (!is_numeric($strNumero)) {
            return false;
        }

        if (strlen($strNumero) < 2) {
            return false;
        }

        $dv = self::calcularModulo11(substr($strNumero, 0, strlen($strNumero) - 1));

        if (intval(substr($strNumero, strlen($strNumero) - 1, 1)) === intval($dv)) {
            return true;
        }

        return false;
    }

    /**
     * Remove um diretÛrio apagando tambÈm todos os arquivos e pastas que ele contÈm
     *
     * @param string $dirname
     * @return  Boolean Resultado da operaÁ„o
     */
    public static function removerDir($dirname)
    {
        if ($dirHandle = opendir($dirname)) {
            $old_cwd = getcwd();
            chdir($dirname);

            while ($file = readdir($dirHandle)) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                if (is_dir($file)) {
                    if (!InfraUtil::removerDir($file)) {
                        return false;
                    }
                } else {
                    if (!unlink($file)) {
                        return false;
                    }
                }
            }

            closedir($dirHandle);
            chdir($old_cwd);
            if (!rmdir($dirname)) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }


    /**
     * Formata o n˙mero sem casas decimais e com "." como separador dos milhares
     *
     * @param Int $num
     * @return  Boolean Resultado da operaÁ„o
     */
    public static function formatarMilhares($num)
    {
        if (is_numeric($num)) {
            return number_format($num, 0, '', '.');
        } else {
            return $num;
        }
    }

    /**
     * Formata o n˙mero na forma de dinheiro. Assume que o valor recebido possui somente separador de decimais.
     * (Ex. Entrada: 83838343.34  -  Saida: 83.838.343,34).  Se os decimais possuÌrem menos de 2 casas completa com zeros.
     * O segundo par‚metro define o limite de casas decimais, completando com zero atÈ esse limite. Se o n˙mero de casas decimais de
     * $din for maior do que $dec, considera somente este limite. Os pontos de milhares somente ser„o apresentados se $dec
     * for informado.
     *
     * @param string $din - o valor informado
     * @param int $dec - o numero de casas decimais
     * @return string
     */
    public static function formatarDin($din, $dec = null)
    {
        if (!InfraString::isBolVazia($din)) {
            $din = trim($din);

            $sinal = '';
            if (substr(
                    $din,
                    0,
                    1
                ) == '-') {//RETIRA O SINAL PARA AS OPERA«’ES E GUARDA PARA RECOLOCAR NO FINAL DA FUN«√O
                $sinal = '-';
                $din = substr($din, 1);
            }
            $pos = strpos($din, '.');
            if ($pos !== false) {//VERIFICA SE H¡ DECIMAIS
                $decimais = substr($din, $pos + 1);
                $inteiros = substr($din, 0, $pos);
            } else {
                $inteiros = $din;
                $decimais = '';
            }
            $din = '';
            $j = 0;
            for ($i = strlen($inteiros) - 1; $i >= 0; $i--) {//CRIA A PONTUA«√O DOS MILHARES
                if ($j >= 3 && ($j % 3) === 0) {
                    $din = '.' . $din;
                }
                $din = $inteiros[$i] . $din;
                $j++;
            }

            $numTamDec = strlen($decimais);

            if ($dec == null) {//$dec N√O INFORMADO


                if ($numTamDec == 0) {
                    $decimais = '00';
                } elseif ($numTamDec == 1) {
                    $decimais .= '0';
                }
            } else {//$dec INFORMADO

                if ($numTamDec == 0) {//PREENCHE COM ZEROS DO ⁄LTIMO N⁄MERO V¡LIDO AT… O LIMITE INFORMADO EM $dec
                    for (; $dec > 0; $dec--) {
                        $decimais = $decimais . '0';
                    }
                } elseif ($numTamDec < $dec) {
                    $decimais = str_pad($decimais, $dec, '0', STR_PAD_RIGHT);
                } else {
                    $decimais = substr($decimais, 0, $dec);
                }
            }

            $din = $sinal . $din . ',' . $decimais;
        }
        return $din;
    }

    /**
     * Assume que o valor recebido PODE possuir separador de decimais. (Entrada: 83838343.34 - Saida: 83838343,34)
     * N„o possui tratamento para milhares
     * @param string $dbl - o valor informado
     * @param int $dec - o numero de casas decimais (se o numero de casas decimais for menor que o existente no valor informado em $dbl este sera truncado)
     * @return float
     */
    public static function formatarDbl($dbl, $dec = null)
    {
        if (!InfraString::isBolVazia($dbl)) {
            $dbl = str_replace('.', ',', $dbl);

            if ($dec != null) {
                $pos = strpos($dbl, ',');

                if ($pos === false) {
                    $dbl = $dbl . ',';
                    for (; $dec > 0; $dec--) {
                        $dbl = $dbl . '0';
                    }
                } elseif ((strlen($dbl) - $pos - 1) < $dec) {
                    $dbl = substr($dbl, 0, $pos) . ',' . str_pad(substr($dbl, ($pos + 1)), $dec, '0', STR_PAD_RIGHT);
                } else {
                    $dbl = substr($dbl, 0, $pos + 1 + $dec);
                }
            }
        }
        return $dbl;
    }

    /**
     * Substitui todas as ocorrÍncias de "," por "." no n˙mero informado
     * @param string $dbl - o valor informado
     * @return string
     */
    public static function prepararDbl($dbl)
    {
        return str_replace(',', '.', $dbl);
    }

    /**
     * Substitui todas as ocorrÍncias de "." por "", e de "," por "." no n˙mero informado
     * @param string $din - o valor informado
     * @return string
     */
    public static function prepararDin($din)
    {
        $din = str_replace('.', '', $din);
        $din = str_replace(',', '.', $din);
        return $din;
    }

    /**
     * Formata um n˙mero de CPF informado na formataÁ„o padr„o (ex. Entrada: 00239476055  SaÌda: 002.394.760-55)
     * @param string $str - o cpf informado
     * @return string
     */
    public static function formatarCpf($str)
    {
        $numero = self::retirarFormatacao($str);

        if (strlen($numero) == 0) {
            return '';
        }

        $numero = str_pad($numero, 11, '0', STR_PAD_LEFT);
        $ret = '';
        $ret .= substr($numero, 0, 3);
        $ret .= '.';
        $ret .= substr($numero, 3, 3);
        $ret .= '.';
        $ret .= substr($numero, 6, 3);
        $ret .= '-';
        $ret .= substr($numero, 9, 2);
        return $ret;
    }

    /**
     * Formata um n˙mero de CNPJ informado na formataÁ„o padr„o (ex. Entrada: 34241231000109  SaÌda: 34.241.231/0001-09)
     * @param string $str - o CNPJ informado
     * @return string
     */
    public static function formatarCnpj($str)
    {
        $numero = self::retirarFormatacao($str);

        if (strlen($numero) == 0) {
            return '';
        }

        $numero = str_pad($numero, 14, '0', STR_PAD_LEFT);
        $ret = '';
        $ret .= substr($numero, 0, 2);
        $ret .= '.';
        $ret .= substr($numero, 2, 3);
        $ret .= '.';
        $ret .= substr($numero, 5, 3);
        $ret .= '/';
        $ret .= substr($numero, 8, 4);
        $ret .= '-';
        $ret .= substr($numero, 12, 2);
        return $ret;
    }

    /**
     * Verifica se o CPF informado È v·lido
     * @param string $strCpf - o CPF informado
     * @return Boolean
     */
    public static function validarCpf($strCpf)
    {
        $numero = self::retirarFormatacao($strCpf);

        if (strlen($numero) > 11) {
            return false;
        }

        //if (!is_numeric($numero)) return false;

        $base = substr($numero, 0, strlen($numero) - 2);
        $calculado = str_pad($base, 11, '0', STR_PAD_LEFT);
        $calculado = substr($calculado, 2, 11);

        $digitos = '';
        for ($j = 1; $j <= 2; $j++) {
            $k = 2;
            $soma = 0;
            for ($i = strlen($calculado) - 1; $i >= 0; $i--) {
                $soma += intval($calculado[$i]) * $k;
                $k = ($k - 1) % 11 + 2;
            }
            $dv = 11 - $soma % 11;
            if ($dv > 9) {
                $dv = 0;
            }
            $calculado .= $dv;
            $digitos .= $dv;
        }

        // Valida dÌgitos verificadores
        if ($numero != $base . $digitos) {
            return false;
        }

        // N„o ser„o considerados v·lidos os seguintes CPF:
        // 000.000.000-00, 111.111.111-11, 222.222.222-22, 333.333.333-33, 444.444.444-44,
        // 555.555.555-55, 666.666.666-66, 777.777.777-77, 888.888.888-88, 999.999.999-99.

        $algUnico = true;
        for ($i = 1; $i < 11; $i++) {
            $algUnico = $algUnico && $numero[$i - 1] == $numero[$i];
        }
        return (!$algUnico);
    }

    /**
     * Verifica se o CNPJ informado È v·lido
     * @param string $strCnpj - o CNPJ informado
     * @return Boolean
     */
    public static function validarCnpj($strCnpj)
    {
        $numero = self::retirarFormatacao($strCnpj);

        if (strlen($numero) > 14) {
            return false;
        } elseif (strlen($numero) < 14) {
            $numero = str_pad($numero, 14, '0', STR_PAD_LEFT);
        }

        //if (!is_numeric($numero)) return false;

        $base = substr($numero, 0, 8);
        $ordem = substr($numero, 8, 4);
        $calculado = str_pad($base . $ordem, 14, '0', STR_PAD_LEFT);
        $calculado = substr($calculado, 2, 12);
        $digitos = '';

        for ($j = 1; $j <= 2; $j++) {
            $k = 2;
            $soma = 0;
            for ($i = strlen($calculado) - 1; $i >= 0; $i--) {
                $soma += intval($calculado[$i]) * $k;
                $k = ($k - 1) % 8 + 2;
            }
            $dv = 11 - $soma % 11;
            if ($dv > 9) {
                $dv = 0;
            }
            $calculado .= $dv;
            $digitos .= $dv;
        }

        // Valida dÌgitos verificadores
        if ($numero != $base . $ordem . $digitos) {
            return false;
        }

        // N„o ser„o considerados v·lidos os CNPJ com os seguintes n˙meros B¡SICOS:
        // 11.111.111, 22.222.222, 33.333.333, 44.444.444, 55.555.555,
        // 66.666.666, 77.777.777, 88.888.888, 99.999.999.

        $algUnico = $numero[0] != '0';
        for ($i = 1; $i < 8; $i++) {
            $algUnico = $algUnico && ($numero[$i - 1] == $numero[$i]);
        }

        if ($algUnico) {
            return false;
        }

        // N„o ser· considerado v·lido CNPJ com n˙mero de ORDEM igual a 0000.
        // N„o ser· considerado v·lido CNPJ com n˙mero de ORDEM maior do que 0300
        // e com as trÍs primeiras posiÁıes do n˙mero B¡SICO com 000 (zeros).
        // Esta crÌtica n„o ser· feita quando o no B¡SICO do CNPJ for igual a 00.000.000.
        if ($ordem == '0000') {
            return false;
        }

        return ($base == '00000000' || intval($ordem) <= 300 || substr($base, 0, 3) != '000');
    }

    /**
     * Faz consulta e recebe um array com dados do usu·rio.
     * @param string $cpf
     * @param string $orgao
     * @param string $sistema
     * @param string $usuario
     * @return array
     */
    public static function consultarDadosCPF($cpf, $orgao, $sistema, $usuario)
    {
        return InfraCJF::consultarDadosCPF($cpf, $orgao, $sistema, $usuario);
    }

    /**
     * Faz consulta e recebe um array com dados de uma empresa ou entidade.
     * @param string $cnpj
     * @param string $orgao
     * @param string $sistema
     * @param string $usuario
     * @return array
     */
    public static function consultarDadosCNPJ($cnpj, $orgao, $sistema, $usuario)
    {
        return InfraCJF::consultarDadosCNPJ($cnpj, $orgao, $sistema, $usuario);
    }

    /**
     * Valida um email
     * @param $email
     * @return boolean
     */
    public static function validarEmail($email)
    {
        $email = trim($email);

        if (InfraString::isBolVazia($email)) {
            return false;
        }

        /*
        if (is_numeric($email{0})){
          return false;
        }
        */

        $regexp = '/^\#([\w-]+(\.[\w-]+)*@(([A-Za-z\d-]{0,62}[A-Za-z\d]\.)+[A-Za-z]{1,62}|\[\d{1,3}(\.\d{1,3}){3}\])|([^<^>^"]*)\<[\w-]+(\.[\w-]+)*@(([A-Za-z\d-]{0,62}[A-Za-z\d]\.)+[A-Za-z]{1,62}|\[\d{1,3}(\.\d{1,3}){3}\])\>|((\s)*"[^"]*"(\s)*)\<[\w-]+(\.[\w-]+)*@(([A-Za-z\d-]{0,62}[A-Za-z\d]\.)+[A-Za-z]{1,62}|\[\d{1,3}(\.\d{1,3}){3}\])\>)\#$/i';

        if (!preg_match($regexp, '#' . $email . '#')) {
            return false;
        }

        return true;
    }

    public static function validarDin($dinValor)
    {
        $dinValor = trim($dinValor);

        if (InfraString::isBolVazia($dinValor)) {
            return false;
        }

        if (!preg_match('/^(?:[1-9](?:[\d]{0,2}(?:\.[\d]{3})*|[\d]+)|0)(?:,[\d]{0,2})?$/', $dinValor)) {
            return false;
        }

        return true;
    }

    /**
     * Calcula o tempo do processamento em microsegundos desde o momento passado como par‚metro.
     * A data atual pode ser obtida com os mÈtodos time() e microtime() do PHP (em segundos e microsegundos desde 0:00:00 January 1, 1970 GMT)
     * e deve ser armazenada em uma vari·vel antes da chamada do(s) mÈtodo(s) e passada para a funÁ„o depois.
     * @param float $inicio
     * @return float
     */
    public static function verificarTempoProcessamento($inicio = false)
    {
        if (!$inicio) {
            return microtime(true);
        }
        $fim = microtime(true);
        return round($fim - $inicio, 3);
    }

    /**
     * Calcula o dÌgito verificador mÛdulo 11 de um n˙mero. O valor informado deve ter apenas caracteres numÈricos.
     * @param $strValor
     * @return integer
     */
    public static function calcularModulo11($strValor)
    {
        //InfraDebug::getInstance()->gravarInfra('NUM:'.$strValor);

        if (!is_numeric($strValor)) {
            return null;
        }

        $soma = 0; // acumulador
        $peso = 2; // peso inicial
        $numdig = strlen($strValor); // n˙mero de dÌgitos
        for ($i = $numdig - 1; $i >= 0; $i--) {
            //InfraDebug::getInstance()->gravarInfra(substr($strValor, $i, 1).' * '.$peso);
            $soma += intval(substr($strValor, $i, 1)) * $peso++;
            if ($peso == 10) {
                $peso = 2;
            }
        }

        //InfraDebug::getInstance()->gravarInfra('SOMA='.$soma);

        $resto = $soma % 11;

        if ($resto == 0 || $resto == 1) {
            $dv = 0;
        } else {
            $dv = 11 - $resto;
        }

        //InfraDebug::getInstance()->gravarInfra('RESTO:'.$resto);
        //InfraDebug::getInstance()->gravarInfra('DV:'.$dv);

        return $dv;
    }

    /**
     * Retira a extens„o do nome do arquivo, e identifica o Content-type a ser informado no cabeÁalho do email do qual esse arquivo È anexo.
     * @param $strNomeArquivo
     * @return string
     */
    public static function getStrMimeType($strNomeArquivo)
    {
        $ext = explode('.', $strNomeArquivo);

        if (count($ext) == 1) {
            return 'application/octet-stream';
        }

        $ext = strtolower($ext[count($ext) - 1]);

        switch ($ext) {
            case 'ai':
                return 'application/postscript';
            case 'bcpio':
                return 'application/x-bcpio';
            case 'bin':
                return 'application/octet-stream';
            case 'ccad':
                return 'application/clariscad';
            case 'cdf':
                return 'application/x-netcdf';
            case 'class':
                return 'application/octet-stream';
            case 'cpio':
                return 'application/x-cpio';
            case 'cpt':
                return 'application/mac-compactpro';
            case 'csh':
                return 'application/x-csh';
            case 'dcr':
                return 'application/x-director';
            case 'dir':
                return 'application/x-director';
            case 'dms':
                return 'application/octet-stream';
            case 'doc':
                return 'application/msword';
            case 'drw':
                return 'application/drafting';
            case 'dvi':
                return 'application/x-dvi';
            case 'dwg':
                return 'application/acad';
            case 'dxf':
                return 'application/dxf';
            case 'dxr':
                return 'application/x-director';
            case 'eps':
                return 'application/postscript';
            case 'exe':
                return 'application/octet-stream';
            case 'ez':
                return 'application/andrew-inset';
            case 'gtar':
                return 'application/x-gtar';
            case 'gz':
                return 'application/x-gzip';
            case 'hdf':
                return 'application/x-hdf';
            case 'hqx':
                return 'application/mac-binhex40';
            case 'ips':
                return 'application/x-ipscript';
            case 'ipx':
                return 'application/x-ipix';
            case 'js':
                return 'application/x-javascript';
            case 'latex':
                return 'application/x-latex';
            case 'lha':
                return 'application/octet-stream';
            case 'lsp':
                return 'application/x-lisp';
            case 'lzh':
                return 'application/octet-stream';
            case 'man':
                return 'application/x-troff-man';
            case 'me':
                return 'application/x-troff-me';
            case 'mif':
                return 'application/vnd.mif';
            case 'ms':
                return 'application/x-troff-ms';
            case 'nc':
                return 'application/x-netcdf';
            case 'oda':
                return 'application/oda';
            case 'p7s':
                return 'application/octet-stream';
            case 'pdf':
                return 'application/pdf';
            case 'pgn':
                return 'application/x-chess-pgn';
            case 'pot':
                return 'application/vnd.ms-powerpoint';
            case 'pps':
                return 'application/vnd.ms-powerpoint';
            case 'ppt':
                return 'application/vnd.ms-powerpoint';
            case 'ppz':
                return 'application/vnd.ms-powerpoint';
            case 'pre':
                return 'application/x-freelance';
            case 'prt':
                return 'application/pro_eng';
            case 'ps':
                return 'application/postscript';
            case 'roff':
                return 'application/x-troff';
            case 'scm':
                return 'application/x-lotusscreencam';
            case 'set':
                return 'application/set';
            case 'sh':
                return 'application/x-sh';
            case 'shar':
                return 'application/x-shar';
            case 'sit':
                return 'application/x-stuffit';
            case 'skd':
                return 'application/x-koan';
            case 'skm':
                return 'application/x-koan';
            case 'skp':
                return 'application/x-koan';
            case 'skt':
                return 'application/x-koan';
            case 'smi':
                return 'application/smil';
            case 'smil':
                return 'application/smil';
            case 'sol':
                return 'application/solids';
            case 'spl':
                return 'application/x-futuresplash';
            case 'src':
                return 'application/x-wais-source';
            case 'step':
                return 'application/STEP';
            case 'stl':
                return 'application/SLA';
            case 'stp':
                return 'application/STEP';
            case 'sv4cpio':
                return 'application/x-sv4cpio';
            case 'sv4crc':
                return 'application/x-sv4crc';
            case 'swf':
                return 'application/x-shockwave-flash';
            case 't':
                return 'application/x-troff';
            case 'tar':
                return 'application/x-tar';
            case 'tcl':
                return 'application/x-tcl';
            case 'tex':
                return 'application/x-tex';
            case 'texi':
                return 'application/x-texinfo';
            case 'texinfo':
                return 'application/x-texinfo';
            case 'tr':
                return 'application/x-troff';
            case 'tsp':
                return 'application/dsptype';
            case 'unv':
                return 'application/i-deas';
            case 'ustar':
                return 'application/x-ustar';
            case 'vcd':
                return 'application/x-cdlink';
            case 'vda':
                return 'application/vda';
            case 'xlc':
                return 'application/vnd.ms-excel';
            case 'xll':
                return 'application/vnd.ms-excel';
            case 'xlm':
                return 'application/vnd.ms-excel';
            case 'xls':
                return 'application/vnd.ms-excel';
            case 'xlw':
                return 'application/vnd.ms-excel';
            case 'zip':
                return 'application/zip';
            case 'rar':
                return 'application/rar';

            case 'aif':
                return 'audio/x-aiff';
            case 'aifc':
                return 'audio/x-aiff';
            case 'aiff':
                return 'audio/x-aiff';
            case 'au':
                return 'audio/basic';
            case 'kar':
                return 'audio/midi';
            case 'mid':
                return 'audio/midi';
            case 'midi':
                return 'audio/midi';
            case 'mp2':
                return 'audio/mpeg';
            case 'mp3':
                return 'audio/mpeg';
            case 'mpga':
                return 'audio/mpeg';
            case 'ra':
                return 'audio/x-realaudio';
            case 'ram':
                return 'audio/x-pn-realaudio';
            case 'rm':
                return 'audio/x-pn-realaudio';
            case 'rpm':
                return 'audio/x-pn-realaudio-plugin';
            case 'snd':
                return 'audio/basic';
            case 'tsi':
                return 'audio/TSP-audio';
            case 'wav':
                return 'audio/x-wav';

            case 'asc':
                return 'text/plain';
            case 'c':
                return 'text/plain';
            case 'cc':
                return 'text/plain';
            case 'css':
                return 'text/css';
            case 'csv':
                return 'text/csv';
            case 'etx':
                return 'text/x-setext';
            case 'f':
                return 'text/plain';
            case 'f90':
                return 'text/plain';
            case 'h':
                return 'text/plain';
            case 'hh':
                return 'text/plain';
            case 'htm':
                return 'text/html';
            case 'html':
                return 'text/html';
            case 'm':
                return 'text/plain';
            case 'rtf':
                return 'text/rtf';
            case 'rtx':
                return 'text/richtext';
            case 'sgm':
                return 'text/sgml';
            case 'sgml':
                return 'text/sgml';
            case 'tsv':
                return 'text/tab-separated-values';
            case 'tpl':
                return 'text/template';
            case 'txt':
                return 'text/plain';
            case 'xml':
                return 'text/xml';

            case 'avi':
                return 'video/x-msvideo';
            case 'fli':
                return 'video/x-fli';
            case 'mov':
                return 'video/quicktime';
            case 'movie':
                return 'video/x-sgi-movie';
            case 'mpe':
                return 'video/mpeg';
            case 'mpeg':
                return 'video/mpeg';
            case 'mpg':
                return 'video/mpeg';
            case 'qt':
                return 'video/quicktime';
            case 'viv':
                return 'video/vnd.vivo';
            case 'vivo':
                return 'video/vnd.vivo';
            case 'mp4':
                return 'video/mp4';

            case 'bmp':
                return 'image/bmp';
            case 'gif':
                return 'image/gif';
            case 'ief':
                return 'image/ief';
            case 'jpe':
                return 'image/jpeg';
            case 'jpeg':
                return 'image/jpeg';
            case 'jpg':
                return 'image/jpeg';
            case 'pbm':
                return 'image/x-portable-bitmap';
            case 'pgm':
                return 'image/x-portable-graymap';
            case 'png':
                return 'image/png';
            case 'pnm':
                return 'image/x-portable-anymap';
            case 'ppm':
                return 'image/x-portable-pixmap';
            case 'ras':
                return 'image/cmu-raster';
            case 'rgb':
                return 'image/x-rgb';
            case 'tif':
                return 'image/tiff';
            case 'tiff':
                return 'image/tiff';
            case 'xbm':
                return 'image/x-xbitmap';
            case 'xpm':
                return 'image/x-xpixmap';
            case 'xwd':
                return 'image/x-xwindowdump';

            case 'ice':
                return 'x-conference/x-cooltalk';

            case 'iges':
                return 'model/iges';
            case 'igs':
                return 'model/iges';
            case 'mesh':
                return 'model/mesh';
            case 'msh':
                return 'model/mesh';
            case 'silo':
                return 'model/mesh';
            case 'vrml':
                return 'model/vrml';
            case 'wrl':
                return 'model/vrml';

            case 'mime':
                return 'www/mime';

            case 'pdb':
                return 'chemical/x-pdb';
            case 'xyz':
                return 'chemical/x-pdb';

            case 'xlsx':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            case 'xltx':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
            case 'potx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.template';
            case 'ppsx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
            case 'pptx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            case 'sldx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.slide';
            case 'docx':
                return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            case 'dotx':
                return 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
            case 'xlam':
                return 'application/vnd.ms-excel.addin.macroEnabled.12';
            case 'xlsb':
                return 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';


            case 'odt':
                return 'application/vnd.oasis.opendocument.text';
            case 'ott':
                return 'application/vnd.oasis.opendocument.text-template';
            case 'odp':
                return 'application/vnd.oasis.opendocument.presentation';
            case 'ods':
                return 'application/vnd.oasis.opendocument.spreadsheet';
        }

        return 'application/octet-stream';
    }

    /*
     public static function calcularDvGedoc($strValor){

    if (!is_numeric($strValor)){
    return null;
    }

    $soma = 0; // acumulador
    $peso = 9; // peso inicial
    $numdig = strlen($strValor); // n˙mero de dÌgitos
    for ($i = $numdig - 1; $i >= 0; $i--) {
    $soma += intval(substr($strValor, $i, 1)) * $peso;
    if ($peso == 2) {
    $peso = 9;
    }else{
    $peso--;
    }
    }

    $resto = ($soma % 11);

    if ($resto==10){
    $dv = 0;
    }else{
    $dv = $resto;
    }

    return $dv;
    }
    */

    /**
     * MÈtodo genÈrico que formata CPF ou CNPJ, considerando o n˙mero de caracteres informado
     * @param string $strNumero
     * @return string
     * */
    public static function formatarCpfCnpj($strNumero)
    {
        $strNumero = InfraUtil::retirarFormatacao($strNumero);
        if (trim($strNumero) == '') {
            return '';
        }
        if (strlen($strNumero) > 11) {
            return InfraUtil::formatarCnpj($strNumero);
        } else {
            return InfraUtil::formatarCpf($strNumero);
        }
    }

    /*public static function baixarCSV($objDTO, $strNomeDocumento) {

    $strCabecalho = '';
    $strDados = '';

    $arrAtributos = $objDTO[0]->getArrAtributos();
    foreach ($arrAtributos as $atributo) {
    $strCabecalho .= $atributo[InfraDTO::$POS_ATRIBUTO_NOME].';';
    }
    $strCabecalho .= "\r\n";

    for ($i=0; $i<count($objDTO); $i++) {
    $arrAtributos = $objDTO[$i]->getArrAtributos();
    foreach ($arrAtributos as $atributo) {
    $strDados .= $atributo[InfraDTO::$POS_ATRIBUTO_VALOR].';';
    }
    $strDados .= "\r\n";
    }

    return array('header("Content-Type: text/csv;");header("Content-Disposition: attachment;filename='.$strNomeDocumento.'.csv;");',$strCabecalho.$strDados);

    }*/

    /**********************************************************************************************************************/

    /**
     * Monta o nome do arquivo para upload em um formato padr„o
     * @param string $strUsuario
     * @param string $numTimeStamp - a classe InfraPagina informa "time()"
     * @param string $strNomeArquivo - (Ex. $_FILES[$strCampoArquivo]["name"])
     * @return string
     */
    public static function montarNomeArquivoUpload($strUsuario, $numTimeStamp, $strNomeArquivo)
    {
        return '[' . InfraUtil::formatarNomeArquivo($strUsuario) . '][' . date(
                'dmY-His',
                $numTimeStamp
            ) . ']-' . InfraUtil::formatarNomeArquivo($strNomeArquivo);
    }

    /**
     * No formaÁ„o do nome do arquivo, somente permite caracteres alfanumÈricos, "." e "_". Os demais s„o substituidos por "_".
     * @param string $strNomeArquivo
     * @return string
     * */
    public static function formatarNomeArquivo($strNomeArquivo)
    {
        $strNomeArquivo = InfraString::excluirAcentos($strNomeArquivo);

        $ret = '';
        for ($i = 0; $i < strlen($strNomeArquivo); $i++) {
            $car = substr($strNomeArquivo, $i, 1);
            if (ctype_alnum($car) == true || $car == '_' || $car == '.') {
                $ret .= $car;
            } else {
                $ret .= '_';
            }
        }
        return $ret;
    }

    /**
     * Mostra o IP do usu·rio
     * @return string
     * */
    public static function getStrIpUsuario($strHttpClientIp = null, $strHttpXForwardedFor = null, $strRemoteAddr = null)
    {
        $strIp = '';

        //INTERNO
        //HTTP_CLIENT_IP=
        //HTTP_X_FORWARDED_FOR=
        //REMOTE_ADDR=10.100.59.196

        //EXTERNO
        //HTTP_CLIENT_IP=
        //HTTP_X_FORWARDED_FOR=10.151.77.140, 10.4.1.8, 200.186.60.37 //10.151.77.140 È o ip do usu·rio
        //REMOTE_ADDR=10.100.100.241

        //CADASTRANDO:100000858:10.151.77.140, 10.4.1.8, 200.186.60.37:Mozilla / 5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.9.2.6) Gecko / 20100625 Firefox / 3.6.6
        //VALIDANDO:  100000858:10.151.77.140, 10.4.1.8, 189.59.75.37 :Mozilla / 5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.9.2.6) Gecko / 20100625 Firefox / 3.6.6

        if ($strHttpClientIp === null && $strHttpXForwardedFor === null && $strRemoteAddr === null) {
            $strHttpClientIp = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : null;
            $strHttpXForwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
            $strRemoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        }

        if (!empty($strHttpClientIp)) {
            $strIp = $strHttpClientIp;
        } elseif (!empty($strHttpXForwardedFor)) {
            $arr = explode(',', $strHttpXForwardedFor);

            $strIp = $arr[0]; //pega o primeiro ip da lista

        } elseif (!empty($strRemoteAddr)) {
            $strIp = $strRemoteAddr;
        }
        return $strIp;
    }

    /**
     * Varre um array procurando pela ocorrÍncia do valor buscado.
     * @param $mixValorASerProcurado
     * @param array $arrLista
     * @return boolean: FALSE caso n„o encontre OU n„o tenha sido passado array como 2o par‚metro;
     *                  TRUE caso encontre
     */
    public static function inArray($mixValorASerProcurado, $arrLista)
    {
        //Tratamento especial para o caso de n„o ser passado um array no par‚metro $arrLista. Isso pode ocorrer
        //em casos onde uma consulta retorna null, e aÌ se manda procurar um ID nesse "array" que em verdade È um null.
        //Para estes casos, ao invÈs de retornar uma exceÁ„o, a funÁ„o sempre retornar· false.
        if (!is_array($arrLista)) {
            return false;
        }

        $numTotalItems = null;
        $strTipoDoValorProcurado = gettype($mixValorASerProcurado);

        foreach ($arrLista as $itemDaLista) {
            $strTipoDoItemAtualDaLista = gettype($itemDaLista);
            //Tipo diferente
            if ($strTipoDoValorProcurado != $strTipoDoItemAtualDaLista) {
                //Se a comparacao for entre string e numero (dinossauro e 0; ou "PHP_INT_MAX+5" e PHP_INT_MAX)
                if ((is_string($mixValorASerProcurado) && is_numeric($itemDaLista)) || (is_string(
                            $itemDaLista
                        ) && is_numeric($mixValorASerProcurado))) {
                    //Converte ambos pra string e compara. Funcionar· com "dinossauro" x 0 e tambÈm com "PHP_INT_MAX+5" x PHP_INT_MAX
                    if ((string)$mixValorASerProcurado == (string)$itemDaLista) {
                        return true;
                    }
                } //Caso contrario (sao objetos diferentes, nao necessariamente string e nr. Ent„o usa a comparaÁ„o do in_array strict
                else {
                    if ($mixValorASerProcurado === $itemDaLista) {
                        return true;
                    }
                }
            } //Tipo igual
            else {
                if ($mixValorASerProcurado == $itemDaLista) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Converte um n˙mero decimal para romano
     * @param integer $number N˙mero a ser convertido para a notaÁ„o romana
     * @return  string        $linje            N˙mero na notaÁ„o romana
     */
    public static function converterNumeroDecimalParaRomano($number)
    {
        # Making input compatible with script.
        $number = floor($number);
        $linje = "";
        $oldChunk = "";
        if ($number < 0) {
            $linje = "-";
            $number = abs($number);
        }
        # Defining arrays
        $romanNumbers = array(1000, 500, 100, 50, 10, 5, 1);
        $romanLettersToNumbers = array("M" => 1000, "D" => 500, "C" => 100, "L" => 50, "X" => 10, "V" => 5, "I" => 1);
        $romanLetters = array_keys($romanLettersToNumbers);

        # Looping through and adding letters.
        while ($number) {
            for ($pos = 0; $pos <= 6; $pos++) {
                # Dividing the remaining number with one of the roman numbers.
                $dividend = $number / $romanNumbers[$pos];

                # If that division is >= 1, round down, and add that number of letters to the string.
                if ($dividend >= 1) {
                    $linje .= str_repeat($romanLetters[$pos], floor($dividend));

                    # Reduce the number to reflect what is left to make roman of.
                    $number -= floor($dividend) * $romanNumbers[$pos];
                }
            }
        }
        # If I find 4 instances of the same letter, this should be done in a different way.
        # Then, subtract instead of adding (smaller number in front of larger).
        $numberOfChanges = 1;
        $appearance = 0;
        while ($numberOfChanges) {
            $numberOfChanges = 0;
            for ($start = 0; $start < strlen($linje); $start++) {
                $chunk = substr($linje, $start, 1);
                if ($chunk == $oldChunk && $chunk != "M") {
                    $appearance++;
                } else {
                    $oldChunk = $chunk;
                    $appearance = 1;
                }
                # Was there found 4 instances.
                if ($appearance == 4) {
                    $firstLetter = substr($linje, $start - 4, 1);
                    $letter = $chunk;

                    $pos = array_search($letter, $romanLetters);

                    # Are the four digits to be calculated together with the one before? (Example yes: VIIII = IX Example no: MIIII = MIV
                    # This is found by checking if the digit before the first of the four instances is the one which is before the digits in the order
                    # of the roman number. I.e. MDCLXVI.
                    if ($romanLetters[$pos - 1] == $firstLetter) {
                        $oldString = $firstLetter . str_repeat($letter, 4);
                        $newString = $letter . $romanLetters[$pos - 2];
                    } else {
                        $oldString = str_repeat($letter, 4);
                        $newString = $letter . $romanLetters[$pos - 1];
                    }
                    $numberOfChanges++;
                    $linje = str_replace($oldString, $newString, $linje);
                }
            }
        }
        return $linje;
    }

    /**
     * A partir dos bytes informados, apresenta o tamanho em m˙ltiplos de 2^10 (Entrada: 122223333 SaÌda: 116.56 Mb)
     * @param $numBytes - o tamanho em bytes
     * @return string
     */
    public static function formatarTamanhoBytes($numBytes)
    {
        $strBytes = null;
        if ($numBytes >= 1099511627776) {
            $strBytes = round($numBytes / 1099511627776, 2) . ' Tb';
        } elseif ($numBytes >= 1073741824) {
            $strBytes = round($numBytes / 1073741824, 2) . ' Gb';
        } elseif ($numBytes >= 1048576) {
            $strBytes = round($numBytes / 1048576, 2) . ' Mb';
        } else /* if ($numBytes > 1024) */ {
            $strBytes = round($numBytes / 1024, 2) . ' Kb';
            /* }else{
              $strBytes = $numBytes.' bytes'; */
        }
        return $strBytes;
    }

    /**
     * Retorna apenas caracteres v·lidos para a codificaÁ„o ISO-8859-1
     * @param ?string $strTexto - texto para aplicaÁ„o do filtro
     * @return string
     */
    public static function filtrarISO88591($strTexto)
    {
        if ($strTexto!==null && $strTexto!=='') {

            if (is_numeric($strTexto)){
                return (string) $strTexto;
            }

            return preg_replace(
                '/([\x00-\x08]|[\x0B-\x0C]|[\x0E-\x1F]|[\x7F-\x9F])/',
                '',
                str_replace(chr(0), '', $strTexto)
            );
        }

        return '';
    }

    public static function verificarEnderecoPermitido($strEndereco, $arrPermitidos)
    {
        foreach ($arrPermitidos as $strPermitido) {
            $strPermitido = trim($strPermitido);
            if (strlen($strPermitido)) {
                if ($strPermitido == '*') {
                    return true;
                } elseif (($pos = strpos($strPermitido, '*')) !== false) {
                    $strPrefixoPermitido = ($pos == 0) ? '' : substr($strPermitido, 0, $pos);
                    $strSufixoPermitido = ($pos == strlen($strPermitido)) ? '' : substr($strPermitido, $pos + 1);

                    if (strlen($strEndereco) >= (strlen($strPrefixoPermitido) + strlen($strSufixoPermitido))) {
                        $strPrefixoEndereco = ($strPrefixoPermitido == '') ? '' : substr(
                            $strEndereco,
                            0,
                            strlen($strPrefixoPermitido)
                        );
                        $strSufixoEndereco = ($strSufixoPermitido == '') ? '' : substr(
                            $strEndereco,
                            strlen(
                                $strSufixoPermitido
                            ) * (-1)
                        );

                        if ($strPrefixoPermitido == $strPrefixoEndereco && $strSufixoPermitido == $strSufixoEndereco) {
                            return true;
                        }
                    }
                } elseif ($strPermitido == $strEndereco) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Valida um texto conforme a m·scara informada, se permitir validaÁ„o parcial, o texto È validado enquanto houver
     * caracteres na string
     * @param string $strTexto
     * @param string $strMascara
     * @param bool $bolParcial
     * @return bool
     */
    public static function validarMascara($strTexto, $strMascara, $bolParcial = false)
    {
        $tamMascara = strlen($strMascara);
        $tamTexto = strlen($strTexto);

        if ($tamTexto > $tamMascara) {
            return false;
        }
        if (!$bolParcial && $tamMascara != $tamTexto) {
            return false;
        }
        for ($i = 0; $i < $tamMascara && $i < $tamTexto; $i++) {
            switch ($strMascara[$i]) {
                case '#':
                    if (preg_match('/[0-9]/', $strTexto[$i]) !== 1) {
                        return false;
                    }
                    break;
                case 'A':
                    if (preg_match('/[A-Z]/', $strTexto[$i]) !== 1) {
                        return false;
                    }
                    break;
                case 'a':
                    if (preg_match('/[a-z]/', $strTexto[$i]) !== 1) {
                        return false;
                    }
                    break;
                case 'L':
                    if (preg_match('/[A-Za-z]/', $strTexto[$i]) !== 1) {
                        return false;
                    }
                    break;
                case 'H':
                    if (preg_match('/[A-Za-z0-9]/', $strTexto[$i]) !== 1) {
                        return false;
                    }
                    break;
                default:
                    if ($strMascara[$i] != $strTexto[$i]) {
                        return false;
                    }
            }
        }
        return true;
    }

    public static function listarArquivos($dir, &$arr)
    {
        $ffs = scandir($dir);
        foreach ($ffs as $ff) {
            if ($ff != '.' && $ff != '..') {
                if (is_dir($dir . '/' . $ff)) {
                    self::listarArquivos($dir . '/' . $ff, $arr);
                } else {
                    $arr[] = $dir . '/' . $ff;
                }
            }
        }
    }

    public static function compararDecimal($dec1, $dec2)
    {
        return ('#' . $dec1 . '#' == '#' . $dec2 . '#');
    }

    public static function isBolLinhaDeComando()
    {
        if (defined('STDIN')) {
            return true;
        }

        if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and InfraArray::contar(
                $_SERVER['argv']
            ) > 0) {
            return true;
        }

        return false;
    }

    public static function verificarConteudoPermitidoArquivo($strArquivo)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $strMime = finfo_file($finfo, $strArquivo);
        finfo_close($finfo);

        if (strpos($strMime, 'text/x-php') !== false || strpos($strMime, 'text/x-shellscript') !== false) {
            return false;
        }

        return true;
    }

    /**
     * Monta uma URL assim como sua query string, com base no array de par‚metros
     * @param $url - a url base
     * @param array $arrParams - o array de par‚metros (chave => valor) a serem formatados como query string
     * @return string
     */
    public static function montarUrl($url, $arrParams)
    {
        $ret = $url;

        $firstParam = true;
        if (InfraArray::contar($arrParams)) {
            foreach ($arrParams as $key => $value) {
                if ($firstParam) {
                    $ret .= '?';
                    $firstParam = false;
                } else {
                    $ret .= '&';
                }
                $ret .= "$key=$value";
            }
        }
        return $ret;
    }

    public static function validarNome($strNome, $numMinimoParticulas = 2, $numMinimoLetrasPorParticula = 2)
    {
        $strRegexSeparadores = '/ |-|\./';
        $strRegexLetras = '/[A-Za-za·‡„‚‰ÈËÍÎÌÏÓÔÛÚıÙˆ˙˘˚¸ÁÒA¡¿√¬ƒ…» ÀÕÃŒœ”“’‘÷⁄Ÿ€‹«—]/';

        $arrParticulas = preg_split($strRegexSeparadores, trim($strNome));
        $numParticulas = count($arrParticulas);
        $arrFiltrado = array();
        for ($i = 0; $i < $numParticulas; $i++) {
            if (strlen($arrParticulas[$i]) >= $numMinimoLetrasPorParticula) {
                $arrFiltrado[] = $arrParticulas[$i];
            }
        }
        $numFiltrado = count($arrFiltrado);
        if ($numFiltrado < $numMinimoParticulas) {
            return false;
        }
        $numParticulas = 0;
        for ($i = 0; $i < $numFiltrado; $i++) {
            $arrLetrasValidas = array();
            preg_match_all($strRegexLetras, $arrFiltrado[$i], $arrLetrasValidas);
            if (count($arrLetrasValidas[0]) >= $numMinimoLetrasPorParticula) {
                $numParticulas++;
            }
        }
        return ($numParticulas >= $numMinimoParticulas);
    }

    public static function isBolNumeroInteiro($strPadrao)
    {
        return preg_match('/^\d+$/', $strPadrao);
    }

    public static function obterCharsetArquivo($strArquivo)
    {
        $output = array();
        exec('file -i ' . $strArquivo, $output);
        if (isset($output[0])) {
            $ex = explode('charset=', $output[0]);
            return isset($ex[1]) ? $ex[1] : null;
        }
        return null;
    }

    /**
     * @param $strVersao1
     * @param $strOperador
     * @param $strVersao2
     * @param int $numFator n˙mero m·ximo de caracteres por nÌvel (strict)
     * @param bool $strict especifica se deve forÁar comparaÁ„o do mesmo numero de niveis e tamanho m·ximo do fator em cada nÌvel
     * @return bool
     * @throws InfraException
     *
     * Compara versıes de acordo com o operador
     *  se for strict=true
     *    1.400.0 = 1.400 => exception
     *    1.4000.0 > 1.401  e numFator=3 => exception
     *  se n„o for strict
     *    1.400 = 1.400.0.0 => true
     *    1.400 > 1.400.0.1 => false
     *
     */
    public static function compararVersoes($strVersao1, $strOperador, $strVersao2, $numFator = 3, $strict = false)
    {
        $ret = false;

        //valida string versao: limitada a numeros e pontos, sem espaÁos, iniciando e terminando com numeros
        $regex = '/^\d+(?>\.\d+)*$/';

        if (!in_array($strOperador, array('>', '>=', '<', '<=', '=='))) {
            throw new InfraException('Operador de comparaÁ„o [' . $strOperador . '] inv·lido.');
        }
        if (preg_match($regex, $strVersao1) !== 1) {
            throw new InfraException('Vers„o [' . $strVersao1 . '] n„o possui formato v·lido.');
        }
        if (preg_match($regex, $strVersao2) !== 1) {
            throw new InfraException('Vers„o [' . $strVersao2 . '] n„o possui formato v·lido.');
        }

        $arrV1 = explode('.', $strVersao1);
        $arrV2 = explode('.', $strVersao2);
        $tamVersao = max(count($arrV1), count($arrV2));

        $numVersao1 = '';
        $numVersao2 = '';

        if ($strict) {
            if (count($arrV1) != count($arrV2)) {
                throw new InfraException('Versıes ' . $strVersao1 . ' e ' . $strVersao2 . ' n„o s„o compatÌveis.');
            }
            for ($i = 0; $i < $tamVersao; $i++) {
                if (isset($arrV1[$i]) && strlen($arrV1[$i]) > $numFator) {
                    throw new InfraException(
                        'Vers„o ' . $strVersao1 . ' possui subconjunto maior que o fator de comparaÁ„o.'
                    );
                }
                if (isset($arrV2[$i]) && strlen($arrV2[$i]) > $numFator) {
                    throw new InfraException(
                        'Vers„o ' . $strVersao2 . ' possui subconjunto maior que o fator de comparaÁ„o.'
                    );
                }

                $numVersao1 .= str_pad(isset($arrV1[$i]) ? $arrV1[$i] : '', $numFator, '0', STR_PAD_LEFT);
                $numVersao2 .= str_pad(isset($arrV1[$i]) ? $arrV1[$i] : '', $numFator, '0', STR_PAD_LEFT);
            }

            $numVersao1 = (int)$numVersao1;
            $numVersao2 = (int)$numVersao2;
        } else {
            for ($i = 0; $i < $tamVersao; $i++) {
                $strV1 = isset($arrV1[$i]) ? $arrV1[$i] : '';
                $strV2 = isset($arrV2[$i]) ? $arrV2[$i] : '';

                $maxLen = max(strlen($strV1), strlen($strV2));

                $numVersao1 .= str_pad($strV1, $maxLen, '0', STR_PAD_LEFT);
                $numVersao2 .= str_pad($strV2, $maxLen, '0', STR_PAD_LEFT);
            }
        }

        switch ($strOperador) {
            case '>';
                $ret = ($numVersao1 > $numVersao2);
                break;

            case '>=';
                $ret = ($numVersao1 >= $numVersao2);
                break;

            case '<';
                $ret = ($numVersao1 < $numVersao2);
                break;

            case '<=';
                $ret = ($numVersao1 <= $numVersao2);
                break;

            case '==';
                $ret = ($numVersao1 == $numVersao2);
                break;
        }

        return $ret;
    }

    public static function gerarUUID()
    {
        return InfraUUID::gerar();
    }

    public static function validarUUID($strUUID)
    {
        return InfraUUID::validar($strUUID);
    }

    public static function isBolBase64($str)
    {
        $str = (string)$str;

        if (!isset($str[0])) {
            return false;
        }

        $base64String = (string)base64_decode($str, true);
        if ($base64String && base64_encode($base64String) === $str) {
            return true;
        }

        return false;
    }

    /**
     * Cria e faz um HTTP post para o endereÁo especificado - copiado de download_completo_agendamento_gerenciar.php
     * @param $url string com endereÁo a ser acessado
     * @param $params array. Um array cujas chaves ser„o os nomes das vari·veis e os valores, well, o valor que se deseja passar :). Deve estar codificado como string codificada com urlencode()
     * @return mixed         Retorna o output que a URL acessada gerar/retornar
     */
    public static function httpPost($url, $params)
    {
        //Obs: FunÁ„o est· aqui temporariamente, ser· migrada depois pra InfraUtil
        $postData = '';

        //cria pares de nome/valor separados por &
        foreach ($params as $key => $value) {
            $postData .= $key . '=' . $value . '&';
        }
        $postData = rtrim($postData, '&');

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $output = curl_exec($ch);

        curl_close($ch);
        return $output;
    }

    public static function formatarCelulaPlanilha($strConteudo)
    {
        if (strlen($strConteudo) > 32700) {
            $strConteudo = substr($strConteudo, 0, 32700) . '...';
        }

        return '"' . str_replace('"', "\"\"", trim($strConteudo)) . '"';
    }

    private static function converterUTF8($obj)
    {
        if (is_string($obj)) {
            $obj = InfraString::toUTF8($obj);
        } else {
            if (is_object($obj)) {
                $obj = (array)$obj;
            }
            if (is_array($obj)) {
                foreach ($obj as $k => $v) {
                    $obj[$k] = self::converterUTF8($obj[$k]);
                }
            }
        }
        return $obj;
    }

    public static function converterJSON($obj, $bolConverterUTF8 = true)
    {
        if ($bolConverterUTF8) {
            $obj = self::converterUTF8($obj);
        }
        //JSON_THROW_ON_ERROR = 4194304
        return json_encode($obj, 4194304);
    }

    public static function formatarValorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
    {
        $singular = null;
        $plural = null;

        if ($bolExibirMoeda) {
            $singular = array("centavo", "real", "mil", "milh„o", "bilh„o", "trilh„o", "quatrilh„o");
            $plural = array("centavos", "reais", "mil", "milhıes", "bilhıes", "trilhıes", "quatrilhıes");
        } else {
            $singular = array("", "", "mil", "milh„o", "bilh„o", "trilh„o", "quatrilh„o");
            $plural = array("", "", "mil", "milhıes", "bilhıes", "trilhıes", "quatrilhıes");
        }

        $c = array(
            "",
            "cem",
            "duzentos",
            "trezentos",
            "quatrocentos",
            "quinhentos",
            "seiscentos",
            "setecentos",
            "oitocentos",
            "novecentos"
        );
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array(
            "dez",
            "onze",
            "doze",
            "treze",
            "quatorze",
            "quinze",
            "dezesseis",
            "dezessete",
            "dezoito",
            "dezenove"
        );
        $u = array("", "um", "dois", "trÍs", "quatro", "cinco", "seis", "sete", "oito", "nove");


        if ($bolPalavraFeminina) {
            if ($valor == 1) {
                $u = array("", "uma", "duas", "trÍs", "quatro", "cinco", "seis", "sete", "oito", "nove");
            } else {
                $u = array("", "um", "duas", "trÍs", "quatro", "cinco", "seis", "sete", "oito", "nove");
            }

            $c = array(
                "",
                "cem",
                "duzentas",
                "trezentas",
                "quatrocentas",
                "quinhentas",
                "seiscentas",
                "setecentas",
                "oitocentas",
                "novecentas"
            );
        }

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junÁ„o de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000") {
                $z++;
            } elseif ($z > 0) {
                $z--;
            }

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            }

            if ($r) {
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
            }
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "zero");
    }

}

