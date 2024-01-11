<?php

class InfraBoleto
{
    public static $hndImagem;
    public static $numLargura = 480;
    public static $numAltura = 50;
    public static $hndBarraBranca;
    public static $hndBarraPreta;
    public static $strInicioCodigo = '0000';
    public static $strFimCodigo = '100';
    public static $arrMascara = array(
        '00110',
        '10001',
        '01001',
        '11000',
        '00101',
        '10100',
        '01100',
        '00011',
        '10010',
        '01010'
    );
    public static $numXAtual = 0;
    public static $numCor = 0;
    public static $numMoeda = 9;

    public static $arrDadosBanco = array('001' => array('codigo' => '001-9', 'logo' => 'logo-banco-brasil.png'));

    public function __construct()
    {
    }

    public static function gerarBoleto(
        $strBanco,
        $strCedente,
        $strAgenciaCodigoCedente,
        $strConvenio,
        $strNossoNumero,
        $strNumeroDocumento,
        $dtaVencimento,
        $strValorDocumento,
        $strValorCobrado,
        $strSacado,
        $strInformacoes,
        $strCarteira
    ) {
        self::$numXAtual = 0;
        self::$numCor = 0;
        self::$hndImagem = null;

        $strLogoBanco = self::$arrDadosBanco[$strBanco]['logo'];
        $strCodigoBanco = self::$arrDadosBanco[$strBanco]['codigo'];
        $numCodigoBarras = self::montarCodigoBarras(
            $strBanco,
            $dtaVencimento,
            $strValorDocumento,
            $strConvenio,
            $strNossoNumero,
            $strCarteira
        );
        $strLinhaDigitavel = self::montarLinhaDigitavel($strBanco, $numCodigoBarras);
        $strCodigoBarras = self::gerarImagemCodigoBarras($numCodigoBarras);

        $strResultado = '<div id="container">
<div id="recibo">
  <div class="cabecalho">
    <div class="banco_logo"><img src="/infra_css/imagens/' . $strLogoBanco . '" /></div>
    <div class="banco_codigo">' . $strCodigoBanco . '</div>
    <div class="linha_digitavel">' . $strLinhaDigitavel . '</div>
  </div>
  <div class="linha">
    <div class="cedente item"><label>Cedente</label>' . $strCedente . '</div>
    <div class="agencia item"><label>Ag./Código do Cedente</label>' . $strAgenciaCodigoCedente . '</div>
    <div class="moeda item"><label>Moeda</label>R$</div>
    <div class="qtd item"><label>Qtd.</label>1</div>
    <div class="nosso_numero item"><label>Nosso Número</label>' . $strConvenio . $strNossoNumero . '</div>
  </div>
  <div class="linha">
    <div class="num_doc item"><label>Número do Documento</label>' . $strNumeroDocumento . '</div>
    <div class="cpf_cnpj item"><label>CPF/CNPJ</label>92.518.737/0001-19</div>
    <div class="vencimento item"><label>Vencimento</label>' . $dtaVencimento . '</div>
    <div class="valor item"><label>Valor do Documento</label>' . $strValorDocumento . '</div>
  </div>
  <div class="linha">
    <div class="descontos item"><label>(-) Desconto/Abatimento</label></div>
    <div class="outras_deducoes item"><label>(-) Outras Deduções</label></div>
    <div class="multa item"><label>(+) Mora/Multa</label></div>
    <div class="outros_acrescimos item"><label>(+) Outros Acréscimos</label></div>
    <div class="valor item"><label>(=) Valor Cobrado</label>' . $strValorCobrado . '</div>
  </div>
  <div class="linha">
    <div class="sacado item"><label>Sacado</label>' . $strSacado . '</div>
  </div>
  <div class="linha">
    <div class="demonstrativo item"><label>Demonstrativo</label>' . $strInformacoes . '</div>
    <div class="autenticacao_mecanica"><label>Autenticação Mecânica</label></div>
  </div>
  <div class="linha_corte"><label>Corte na linha pontilhada</label></div>
</div>            
<div id="ficha_compensacao">
  <div class="cabecalho">
    <div class="banco_logo"><img src="/infra_css/imagens/' . $strLogoBanco . '" /></div>
    <div class="banco_codigo">' . $strCodigoBanco . '</div>
    <div class="linha_digitavel last">' . $strLinhaDigitavel . '</div>
  </div>
  <div id="colunaprincipal">
    <div class="local_pagamento item"><label>Local de Pagamento</label>Pagável em qualquer banco até o vencimento</div>
    <div class="cedente item"><label>Cedente </label>' . $strCedente . '</div>
    <div class="linha">
      <div class="data_doc item"><label>Data do documento</label>' . date("d/m/Y") . '</div>
      <div class="num_doc item"><label>Número do documento</label>' . $strNumeroDocumento . '</div>
      <div class="espec_doc item"><label>Espécie Doc.</label>DS</div>
      <div class="aceite item"><label>Aceite</label>N</div>
      <div class="dt_proc item"><label>Data proc</label>' . date("d/m/Y") . '</div>
    </div>
    <div class="linha">
      <div class="uso_banco item"><label>Uso do Banco</label></div>
      <div class="carteira item"><label>Carteira</label>' . $strCarteira . '</div>
      <div class="moeda item"><label>Moeda</label>R$</div>
      <div class="qtd item"><label>Quantidade</label>1</div>
      <div class="valor item"><label>(x) Valor</label>' . $strValorDocumento . '</div>
    </div>
    <div class="mensagens "><label>Instruções (Texto de responsabilidade do cedente)</label>' . $strInformacoes . '</div>
  </div>
  <div id="colunadireita">
    <div class=""><label>Vencimento</label>' . $dtaVencimento . '</div>
    <div class=""><label>Agência / Código cedente</label>' . $strAgenciaCodigoCedente . '</div>
    <div class=""><label>Nosso número</label>' . $strConvenio . $strNossoNumero . '</div>
    <div class=""><label>(=) Valor do documento</label>' . $strValorDocumento . '</div>
    <div class=""><label>(-) Desconto/Abatimento</label></div>
    <div class=""><label>(-) Outras deduções</label></div>
    <div class=""><label>(+) Mora/Multa</label></div>
    <div class=""><label>(+) Outros Acréscimos</label></div>
    <div class=""><label>(=) Valor cobrado</label>' . $strValorCobrado . '</div>
  </div>
  <div id="sacado" class="">
    <div class=""><label>Sacado</label>' . $strSacado . '</div>
  </div>
  <div id="codigo_barras" class="">
    <div><label>Sacador/Avalista</label><img src="' . $strCodigoBarras . '" /></div>
    <div class=""><span>Ficha de Compensação</span><label>Autenticação Mecânica</label></div>
  </div>
  <div class="linha_corte"><label>Corte na linha pontilhada</label></div>
</div>
</div>';

        return $strResultado;
    }

    public static function montarLinhaDigitavel($strBanco, $numCodigoBarras)
    {
        $numCodigoBarrasParte1 = substr($numCodigoBarras, 0, 4) . substr($numCodigoBarras, 19, 5);
        $numCampo1 = $numCodigoBarrasParte1 . self::gerarDVModulo10($numCodigoBarrasParte1);
        $numCampo1 = substr($numCampo1, 0, 5) . '.' . substr($numCampo1, 5, 5);

        $numCodigoBarrasParte2 = substr($numCodigoBarras, 24, 10);
        $numCampo2 = $numCodigoBarrasParte2 . self::gerarDVModulo10($numCodigoBarrasParte2);
        $numCampo2 = substr($numCampo2, 0, 5) . '.' . substr($numCampo2, 5, 6);

        $numCodigoBarrasParte3 = substr($numCodigoBarras, 34, 10);
        $numCampo3 = $numCodigoBarrasParte3 . self::gerarDVModulo10($numCodigoBarrasParte3);
        $numCampo3 = substr($numCampo3, 0, 5) . '.' . substr($numCampo3, 5, 6);

        $numCampo4 = substr($numCodigoBarras, 4, 1);

        $numCampo5 = substr($numCodigoBarras, 5, 4) . substr($numCodigoBarras, 9, 10);

        return $numCampo1 . ' ' . $numCampo2 . ' ' . $numCampo3 . ' ' . $numCampo4 . ' ' . $numCampo5;
    }

    public static function montarCodigoBarras(
        $strBanco,
        $dtaVencimento,
        $strValorDocumento,
        $strConvenio,
        $strNossoNumero,
        $strCarteira
    ) {
        $numValorFormatadoDocumento = substr(
            '0000000000' . str_replace('.', '', str_replace(',', '', $strValorDocumento)),
            -10
        );
        if (strlen($strConvenio) == 7) {
            $numValorFormatadoNossoNumero = '000000' . $strConvenio . substr('0000000000' . $strNossoNumero, -10);
        }
        $numCodigoBarras = $strBanco . self::$numMoeda . $numDVGeral . self::calcularFatorVencimento(
                $dtaVencimento
            ) . $numValorFormatadoDocumento . $numValorFormatadoNossoNumero . $strCarteira;
        $numDVCodigoBarras = self::gerarDVModulo11($numCodigoBarras);
        return substr($numCodigoBarras, 0, 4) . $numDVCodigoBarras . substr($numCodigoBarras, 4, 39);
    }

    public static function gerarImagemCodigoBarras($strCodigo)
    {
        self::$hndImagem = imagecreate(self::$numLargura, self::$numAltura);
        self::$hndBarraBranca = imagecolorallocate(self::$hndImagem, 255, 255, 255);
        self::$hndBarraPreta = imagecolorallocate(self::$hndImagem, 0, 0, 0);
        self::processarCodigoNumerico($strCodigo);
        imagepng(self::$hndImagem, './barcode/' . $strCodigo . '.png');
        imagedestroy(self::$hndImagem);
        return 'barcode/' . $strCodigo . '.png';
    }

    public function processarCodigoNumerico($strCodigo)
    {
        $n = strlen($strCodigo);
        $strCodigo = (strlen($strCodigo) % 2) != 0 ? '0' . $strCodigo : $strCodigo;
        for ($i = 0; $i < $n; $i += 2) {
            $strValorAtual = self::intercalar(substr($strCodigo, $i, 1) . substr($strCodigo, $i + 1, 1));
            if ($i == 0) {
                $strValorAtual = self::$strInicioCodigo . $strValorAtual;
            }
            if ($i == ($n - 2)) {
                $strValorAtual .= self::$strFimCodigo;
            }
            for ($j = 0; $j < strlen($strValorAtual); $j++) {
                $numLargura = substr($strValorAtual, $j, 1) == 1 ? 3 : 1;
                self::adicionarBarra(self::$numXAtual, $numLargura, self::$numCor);
                self::$numCor = self::$numCor == 0 ? 1 : 0;
                self::$numXAtual += $numLargura;
            }
        }
        self::adicionarBarra(self::$numXAtual, 1, self::$numCor);
    }

    public function adicionarBarra($numX, $numLargura, $numCor)
    {
        $numCor = $numCor == 0 ? self::$hndBarraPreta : self::$hndBarraBranca;
        imagefilledrectangle(self::$hndImagem, $numX, 0, $numX + $numLargura, self::$numAltura, $numCor);
    }

    public function intercalar($strCodigo)
    {
        $numN1 = substr($strCodigo, 0, 1);
        $numN2 = substr($strCodigo, 1, 1);
        $strRetorno = '';
        for ($i = 0; $i < 5; $i++) {
            $strRetorno .= substr(self::$arrMascara[$numN1], $i, 1) . substr(self::$arrMascara[$numN2], $i, 1);
        }
        return $strRetorno;
    }

    public static function gerarDVModulo10($numNumero)
    {
        $numTamanho = strlen($numNumero) - 1;
        $numSoma = 0;
        $numMultiplicador = 2;

        for ($i = $numTamanho; $i >= 0; $i--) {
            $numResultado = substr($numNumero, $i, 1) * $numMultiplicador;
            if ($numResultado >= 10) {
                $numResultado = substr($numResultado, 0, 1) + substr($numResultado, 1, 1);
            }
            $numSoma += $numResultado;
            $numMultiplicador = $numMultiplicador == 2 ? 1 : 2;
        }

        $numResto = $numSoma % 10;
        $numDV = 10 - $numResto;

        if ($numResto == 0) {
            return $numResto;
        } else {
            return $numDV;
        }
    }

    public static function gerarDVModulo11($numNumero)
    {
        $numTamanho = strlen($numNumero) - 1;
        $numSoma = 0;
        $numMultiplicador = 2;
        $numMultiplicadorMaximo = 9;

        for ($i = $numTamanho; $i >= 0; $i--) {
            $numSoma += substr($numNumero, $i, 1) * $numMultiplicador;
            $numMultiplicador = $numMultiplicador >= $numMultiplicadorMaximo ? 2 : $numMultiplicador + 1;
        }

        $numResto = $numSoma % 11;
        $numDV = 11 - $numResto;

        if (in_array($numDV, array(0, 10, 11))) {
            return 1;
        } else {
            return $numDV;
        }
    }

    public static function calcularFatorVencimento($dtaVencimento)
    {
        $arrVencimento = explode('/', $dtaVencimento);
        $numDia = $arrVencimento[0];
        $numMes = $arrVencimento[1];
        $numAno = $arrVencimento[2];
        $numVencimento = (int)(mktime(0, 0, 0, $numMes, $numDia, $numAno) / (24 * 60 * 60));
        $numDatabase = (int)(mktime(0, 0, 0, 10, 7, 1997) / (24 * 60 * 60));
        return abs($numVencimento - $numDatabase);
    }

    public function __destruct()
    {
    }
}