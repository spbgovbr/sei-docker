<?php

/**
 * @package infra_php
 *
 */
abstract class InfraOracle implements InfraIBanco
{
    private $conexao;
    private $id;
    private $transacao;

    public abstract function getServidor();

    public abstract function getPorta();

    public abstract function getBanco();

    public abstract function getUsuario();

    public abstract function getSenha();

    public function getUsuarioOwner()
    {
        return $this->getUsuario();
    }

    public function getCharset()
    {
        return 'WE8ISO8859P1';
    }

    public function isBolUsarPreparedStatement()
    {
        return false;
    }

    public function __construct()
    {
        $this->conexao = null;
        $this->id = null;
        $this->transacao = false;
    }

    public function __destruct()
    {
        if ($this->getIdConexao() != null) {
            try {
                $this->fecharConexao();
            } catch (Exception $e) {
            }
        }
    }

    public function getIdBanco()
    {
        return __CLASS__ . '-' . $this->getServidor() . '-' . $this->getPorta() . '-' . $this->getBanco(
            ) . '-' . $this->getUsuario();
    }

    public function getIdConexao()
    {
        return $this->id;
    }

    public function getValorSequencia($sequencia)
    {
        $rs = $this->consultarSql('SELECT ' . $sequencia . '.NEXTVAL FROM DUAL');
        return $rs[0]['nextval'];
    }

    public function isBolProcessandoTransacao()
    {
        return $this->transacao;
    }

    public function isBolForcarPesquisaCaseInsensitive()
    {
        return true;
    }

    public function isBolManterConexaoAberta()
    {
        return false;
    }

    public function isBolValidarISO88591()
    {
        return false;
    }

    public function isBolCarregarBin()
    {
        return false;
    }

    // SELECAO
    private function formatarSelecaoGenerico($tabela, $campo, $alias)
    {
        $ret = '';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }

        $ret .= $campo;

        if ($alias != null) {
            $ret .= ' AS ' . $alias;
        }
        return $ret;
    }

    private function formatarSelecaoAsVarchar($tabela, $campo, $alias)
    {
        $ret = "TO_CHAR($campo) as";
        $ret = 'TO_CHAR(';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }
        $ret .= $campo . ' ) AS ';

        if ($alias !== null) {
            $ret .= $alias;
        } else {
            $ret .= $campo;
        }
        return $ret;
    }

    public function formatarSelecaoDta($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    public function formatarSelecaoDth($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    public function formatarSelecaoStr($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    public function formatarSelecaoBol($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    public function formatarSelecaoNum($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    public function formatarSelecaoDin($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoAsVarchar($tabela, $campo, $alias);
    }

    public function formatarSelecaoDbl($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
        //return $this->formatarSelecaoAsVarchar($tabela, $campo, $alias);
    }

    public function formatarSelecaoBin($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    // GRAVACAO
    public function formatarGravacaoDta($dta)
    {
        return $this->gravarData(substr($dta, 0, 10));
    }

    public function formatarGravacaoDth($dth)
    {
        return $this->gravarData($dth);
    }

    public function formatarGravacaoStr($str)
    {
        if ($str === null || $str === '') {
            return 'NULL';
        }

        if ($this->isBolValidarISO88591() && InfraUtil::filtrarISO88591($str) != $str) {
            throw new InfraException('Detectado caracter inválido.');
        }

        $str = str_replace("'", '\'\'', $str);

        return '\'' . $str . '\'';
    }

    public function formatarGravacaoBol($bol)
    {
        if ($bol === true) {
            return 1;
        }
        return 0;
    }

    public function formatarGravacaoNum($num)
    {
        $num = trim($num);

        if ($num === '') {
            return 'NULL';
        }

        if (!is_numeric($num)) {
            throw new InfraException('Valor numérico inválido [' . $num . '].');
        }

        return $num;
    }

    public function formatarGravacaoDin($din)
    {
        $din = trim($din);

        if ($din === '') {
            return 'NULL';
        }

        $din = InfraUtil::prepararDin($din);

        if (!is_numeric($din)) {
            throw new InfraException('Valor numérico inválido [' . $din . '].');
        }

        return $din;
    }

    public function formatarGravacaoDbl($dbl)
    {
        $dbl = trim($dbl);

        if ($dbl === '') {
            return 'NULL';
        }

        $dbl = InfraUtil::prepararDbl($dbl);

        if (!is_numeric($dbl)) {
            throw new InfraException('Valor numérico inválido [' . $dbl . '].');
        }

        return $dbl;
    }

    public function formatarGravacaoBin($bin)
    {
        if ($bin === null || $bin === '') {
            return 'NULL';
        }
        return '\'' . ($bin) . '\'';
    }

    public function converterStr($tabela, $campo)
    {
        $ret = 'to_char(';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }
        $ret .= $campo . ')';
        return $ret;
    }

    public function formatarPesquisaStr($strTabela, $strCampo, $strValor, $strOperador, $bolCaseInsensitive, $strBind)
    {
        if ($bolCaseInsensitive) {
            if ($strBind == null) {
                return 'upper(' . $strCampo . ') ' . $strOperador . ' \'' . str_replace(
                        '\'',
                        '\'\'',
                        InfraString::transformarCaixaAlta(
                            $strValor
                        )
                    ) . '\' ';
            } else {
                return 'upper(' . $strCampo . ') ' . $strOperador . ' ' . $strBind . ' ';
            }
        } else {
            if ($strBind == null) {
                return $strCampo . ' ' . $strOperador . ' \'' . str_replace('\'', '\'\'', $strValor) . '\' ';
            } else {
                return $strCampo . ' ' . $strOperador . ' ' . $strBind . ' ';
            }
        }
    }

    public function formatarLeituraDta($dta)
    {
        $ret = $this->lerData($dta);
        if ($ret != null) {
            return substr($ret, 0, 10);
        }
        return null;
    }

    public function formatarLeituraDth($dth)
    {
        return $this->lerData($dth);
    }

    public function formatarLeituraStr($str)
    {
        return $str;
    }

    public function formatarLeituraBol($bol)
    {
        if ($bol === 't') {
            return true;
        } else {
            return false;
        }
    }

    public function formatarLeituraNum($num)
    {
        return $num;
    }

    public function formatarLeituraDin($din)
    {
        return InfraUtil::formatarDin($din);
    }

    public function formatarLeituraDbl($dbl)
    {
        return InfraUtil::formatarDbl($dbl);
    }

    public function formatarLeituraBin($bin)
    {
        if ($this->isBolCarregarBin()) {
            return ($bin->load());
        } else {
            return ($bin);
        }
    }

    /**
     *  Permite a utilização de parametros para setar o valor de NLS_NUMERIC_CHARACTERS
     * @return mixed|null|string.
     *
     */
    public function getNumberFormat()
    {
        return null;
    }

    public function abrirConexao()
    {
        try {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra('[InfraOracle->abrirConexao] ' . $this->getIdBanco());
            }

            if ($this->conexao != null) {
                throw new InfraException('Tentativa de abrir nova conexão sem fechar a anterior.');
            }

            $this->conexao = oci_connect(
                $this->getUsuario(),
                $this->getSenha(),
                $this->getServidor(),
                $this->getCharset()
            );
            $strAlterSession = 'ALTER SESSION SET CURRENT_SCHEMA=' . $this->getUsuarioOwner(
                ) . ' NLS_DATE_FORMAT=\'DD/MM/YYYY hh24:mi:ss\'';
            if ($this->getNumberFormat() != null) {
                $strAlterSession .= ' NLS_NUMERIC_CHARACTERS=\'' . $this->getNumberFormat() . '\'';
            }
            $this->executarSql($strAlterSession);

            $this->id = $this->getIdBanco();

            if ($this->conexao === false) {
                throw new InfraException(oci_error($this->conexao));
            }
        } catch (Exception $e) {
            if (strpos(strtolower($e->__toString()), 'oci_connect') !== false) {
                preg_match('/^oci_connect\(\):\s(.*)/m', $e->__toString(), $matches);
                throw new InfraException('Não foi possível abrir conexão com a base de dados.', null, $matches[1]);
            } else {
                throw $e;
            }
        }
    }

    public function fecharConexao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->fecharConexao] ' . $this->getIdConexao());
        }

        // InfraDebug::getInstance()->gravarInfra('[InfraOracle->fecharConexao] 10');
        if ($this->conexao == null) {
            throw new InfraException('Tentativa de fechar conexão que não foi aberta.');
        }
        // InfraDebug::getInstance()->gravarInfra('[InfraOracle->fecharConexao] 20');

        oci_close($this->conexao);

        $this->conexao = null;
        $this->id = null;
    }

    public function abrirTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->abrirTransacao] ' . $this->getIdConexao());
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentando abrir transação em uma conexão fechada.');
        }

        $this->transacao = true;
    }

    public function confirmarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->confirmarTransacao] ' . $this->getIdConexao());
        }

        // InfraDebug::getInstance()->gravarInfra('[InfraOracle->confirmarTransacao] 10');
        if ($this->conexao == null) {
            throw new InfraException('Tentando confirmar transação em uma conexão fechada.');
        }
        oci_commit($this->conexao);
        $this->transacao = false;
    }

    public function cancelarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->cancelarTransacao] ' . $this->getIdConexao());
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentando desfazer transação em uma conexão fechada.');
        }
        oci_rollback($this->conexao);
        $this->transacao = false;
    }

    public function consultarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraOracle->consultarSql] ' . InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentando executar uma consulta em uma conexão fechada.');
        }

        $resultado = oci_parse($this->conexao, $sql);

        if ($arrCamposBind != null && count($arrCamposBind) > 0) {
            $chaves = array_keys($arrCamposBind);
            for ($i = 0; $i < count($chaves); $i++) {
                oci_bind_by_name($resultado, $chaves[$i], $arrCamposBind[$chaves[$i]]);
            }
        }

        oci_execute($resultado, OCI_NO_AUTO_COMMIT);

        if ($resultado === false) {
            throw new InfraException(
                oci_error($this->conexao), null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
            );
        }

        $vetor_resultado = array();
        $cont = 0;

        $clobs = array();

        $ncols = oci_num_fields($resultado);

        for ($i = 1; $i <= $ncols; $i++) {
            $column_name = oci_field_name($resultado, $i);
            $column_type = oci_field_type($resultado, $i);

            if ($column_type == "CLOB") {
                $clobs[$column_name] = $column_type;
            }
        }

        while ($registro = oci_fetch_assoc($resultado)) {
            $chaves = array_keys($registro);

            $nChaves = count($chaves);

            for ($i = 0; $i < $nChaves; $i++) {
                $strChave = $chaves[$i];

                if ($registro[$strChave] != null) {
                    if (isset($clobs[$strChave]) && $clobs[$strChave] != null) {
                        if ($registro[$strChave]->size() > 0) {
                            $registro[$strChave] = $registro[$strChave]->read($registro[$strChave]->size());
                        } else {
                            $registro[$strChave] = "";
                        }
                    }
                }
            }
            $vetor_resultado[] = array_change_key_case($registro, CASE_LOWER);
        }

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraOracle->consultarSql] ' . InfraUtil::formatarMilhares(count($vetor_resultado)) . ' registro(s)'
            );
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->consultarSql] ' . $numSeg . ' s');
        }

        return $vetor_resultado;
    }

    public function paginarSql($sql, $ini, $qtd, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->paginarSql]');
        }

        if (!is_numeric($ini)) {
            throw new InfraException('Valor numérico inválido [' . $ini . '].');
        }

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        $arr = explode(' ', $sql);
        for ($i = 0; $i < count($arr); $i++) {
            if (strtoupper($arr[$i]) == 'FROM') {
                break;
            }
        }

        $sqlTotal = 'SELECT COUNT(*) as total';
        for (; $i < count($arr); $i++) {
            if (strtoupper($arr[$i]) == 'ORDER') {
                break;
            }
            $sqlTotal .= ' ' . $arr[$i];
        }
        $rsTotal = $this->consultarSql($sqlTotal, $arrCamposBind);

        $qtd = $qtd + $ini;
        $qtdOtimizacao = $qtd + 10;
        $sql = "SELECT a.* FROM ( SELECT /*+ FIRST_ROWS($qtdOtimizacao)*/ b.*,rownum b_rownum FROM ( $sql ) b WHERE rownum <= $qtd) a WHERE b_rownum >= $ini";

        $rs = $this->consultarSql($sql, $arrCamposBind);

        return array(
            'totalRegistros' => $rsTotal[0]['total'],
            'registrosPagina' => $rs
        );
    }

    public function limitarSql($sql, $qtd, $arrCamposBind = null)
    {
        // if (InfraDebug::isBolProcessar()) {
        // InfraDebug::getInstance()->gravarInfra('[InfraOracle->limitarSql] ' . $sql);
        // }

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        $sql = 'SELECT * FROM (' . $sql . ') WHERE rownum <= ' . $qtd;
        return $this->consultarSql($sql, $arrCamposBind);
    }

    /**
     * Permite a utilização de parametros IN na execução de scripts PL/SQL
     *
     * @param string $sql query que será executada.
     * @param string $arrCamposBind Lista de parâmetros (chave => valor) de entrada que serão passados por bind.
     * @return integer $numReg quantidade de registros afetados. Após a execução do método o valor de retorno pode
     *         ser acessado através da primeira posição do respectivo array. Caso o aparametro seja um binário a chave
     *         correspondente deve obrigatoriamente ser iniciada por 'bin'.
     */
    public function executarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraOracle->executarSql] ' . InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentando executar um comando em uma conexão fechada.');
        }

        $resultado = oci_parse($this->conexao, $sql);

        $arrBlob = array(); // Utilizando array pra não perder os endereços de memória
        if ($arrCamposBind != null && count($arrCamposBind) > 0) {
            $chaves = array_keys($arrCamposBind);
            for ($i = 0; $i < count($chaves); $i++) {
                if (substr($chaves[$i], 0, 3) == 'bin') {
                    $arrBlob[$i] = oci_new_descriptor($this->conexao, OCI_D_LOB);
                    oci_bind_by_name($resultado, $chaves[$i], $arrBlob[$i], -1, OCI_B_BLOB);
                    $arrBlob[$i]->writeTemporary($arrCamposBind[$chaves[$i]], OCI_TEMP_BLOB);
                } else {
                    oci_bind_by_name($resultado, $chaves[$i], $arrCamposBind[$chaves[$i]]);
                }
            }
        }

        if (!$this->transacao) {
            oci_execute($resultado, OCI_COMMIT_ON_SUCCESS);
        } else {
            oci_execute($resultado, OCI_NO_AUTO_COMMIT);
        }

        if ($resultado === false) {
            throw new InfraException(
                oci_error($this->conexao), null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
            );
        }
        $numReg = oci_num_rows($resultado);

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->executarSql] ' . $numReg . ' registro(s) afetado(s)');
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->executarSql] ' . $numSeg . ' s');
        }

        return $numReg;
    }

    /**
     * Permite a utilização de parametros IN/OUT na execução de scripts PL/SQL
     * @param string $sql query que será executada.
     * @param string $camposEntrada Lista de parâmetros (chave => valor) de entrada que serão passados por bind.
     * @param string &$camposRetorno Lista de parâmetros (chave => valor) de entrada/saída que podem ser passados por bind para uma procedure.
     *            <pre>
     *            O valor de cada item da lista de campos de retorno deve,
     *            obrigatoriamente, ser um array com 3 valores.
     *            - valor do campo.
     *            - tamanho do campo
     *            - tipo do campo. Verificar os tipos permitidos em:
     *            http://php.net/manual/en/function.oci-bind-by-name.php
     *            </pre>
     * @return integer $numReg quantidade de registros afetados. Após a execução do método o valor de retorno pode ser acessados através da primeira posição do respectivo array.
     * @example
     * <pre>
     *          $arrParametrosOUT['chave'] = [null, 20, SQLT_INT];
     *          executarSqlComRetorno($query, $arrParametrosIN, $arrParametrosOUT);
     *          $arrParametrosOUT['chave'][0];
     *          </pre>
     * @deprecated Substituído pelo método executarSqlTipado(), que trata tipos de campos de entrada e de saía no mesmo array de campos.
     */
    public function executarSqlComRetorno($sql, $camposEntrada = null, &$camposRetorno = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraOracle->executarSqlComRetorno] ' . substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar um comando em uma conexão fechada.');
        }
        $resultado = oci_parse($this->conexao, $sql);
        if ($camposEntrada != null && count($camposEntrada) > 0) {
            $chaves = array_keys($camposEntrada);
            for ($i = 0; $i < count($chaves); $i++) {
                oci_bind_by_name($resultado, $chaves[$i], $camposEntrada[$chaves[$i]]);
            }
        }
        if ($camposRetorno != null && count($camposRetorno) > 0) {
            $chaves = array_keys($camposRetorno);
            for ($i = 0; $i < count($chaves); $i++) {
                $tamanho = $camposRetorno[$chaves[$i]][1]; // o valor da chave indica o tamanho
                $tipo = $camposRetorno[$chaves[$i]][2]; // a chave do retorno indica o tipo
                oci_bind_by_name($resultado, $chaves[$i], $camposRetorno[$chaves[$i]][0], $tamanho, $tipo[0]);
            }
        }
        if (!$this->transacao) {
            oci_execute($resultado, OCI_COMMIT_ON_SUCCESS);
        } else {
            oci_execute($resultado, OCI_NO_AUTO_COMMIT);
        }
        if ($resultado === false) {
            throw new InfraException(oci_error($this->conexao), null, substr($sql, 0, INFRA_TAM_MAX_LOG_SQL));
        }
        $numReg = oci_num_rows($resultado);
        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->executarSql] ' . $numReg . ' registro(s) afetado(s)');
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->executarSql] ' . $numSeg . ' s');
        }
        return $numReg;
    }

    function lerData($Oracle_date)
    {
        return $Oracle_date;
    }

    public function gravarData($brasil_date)
    {
        if (trim($brasil_date) === '') {
            return 'NULL';
        }

        $numTamData = strlen($brasil_date);

        if (($numTamData != 10 && $numTamData != 19) || preg_match("/[^0-9 \/\-:]/", $brasil_date)) {
            throw new InfraException('Data inválida [' . $brasil_date . '].');
        }

        if ($numTamData == 10) {
            return 'TO_DATE(\'' . $brasil_date . '\',\'dd/mm/yyyy\')';
        } else {
            return 'TO_DATE(\'' . $brasil_date . '\',\'dd/mm/yyyy hh24:mi:ss\')';
        }
    }

    public function criarSequencialNativa($strSequencia, $numInicial)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->criarSequencialNativa]');
        }

        $this->executarSql(
            'CREATE SEQUENCE ' . $strSequencia . ' START WITH ' . $numInicial . ' INCREMENT BY 1 NOCACHE NOCYCLE'
        );
    }

    /**
     * Permite a utilização de parametros IN/OUT na execução de scripts PL/SQL
     *
     * @param string $sql
     *            query que ser executada.
     * @param string $arrCampos
     *            Lista de parâmetros (chave => valor) de entrada que serão passados por bind.
     *            <pre>
     *            O valor de cada item da lista deve, obrigatoriamente, ser um array com 3 valores.
     *            - valor do campo.
     *            - tamanho do campo
     *            - tipo do campo. Verificar os tipos permitidos em:
     *            http://php.net/manual/en/function.oci-bind-by-name.php
     *            </pre>
     * @return integer $numReg quantidade de registros afetados. Após a execução do méodo o valor de retorno pode ser acessados através da primeira posição do respectivo array.
     * @example <pre>
     *          $arrParametros['chave'] = [null, 20, SQLT_INT];
     *          $arrParametros['arquivo'] = [$blob, -1, SQLT_BLOB];
     *          executarSqlTipado($query, $arrParametros);
     *          $valorRetorno = $arrParametros['chave'][0];
     *          </pre>
     */
    public function executarSqlTipado($strSql, &$arrCampos = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraOracle->executarSql] ' . substr($strSql, 0, INFRA_TAM_MAX_LOG_SQL)
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar um comando em uma conexão fechada.');
        }
        $resResultado = oci_parse($this->conexao, $strSql);
        $arrBlob = array();
        if ($arrCampos != null && count($arrCampos) > 0) {
            $arrChaves = array_keys($arrCampos);

            for ($i = 0; $i < count($arrChaves); $i++) {
                $numTamanho = $arrCampos[$arrChaves[$i]][1]; // o valor da chave indica o tamanho
                $numTipo = $arrCampos[$arrChaves[$i]][2]; // a chave do retorno indica o tipo
                switch ($numTipo) {
                    case SQLT_BFILEE:
                    case OCI_B_BFILE:
                    case SQLT_CFILEE:
                    case OCI_B_CFILEE:
                    case SQLT_RDD:
                    case OCI_B_ROWID:
                    case SQLT_NTY:
                    case OCI_B_NTY:
                    case SQLT_INT:
                    case OCI_B_INT:
                    case SQLT_CHR:
                    case SQLT_BIN:
                    case OCI_B_BIN:
                    case SQLT_LNG:
                    case SQLT_LBI:
                    case SQLT_RSET:
                    case SQLT_BOL:
                    case OCI_B_BOL:
                        $bolTipoBlob = false;
                        break;
                    case SQLT_CLOB:
                    case OCI_B_CLOB:
                        $numTipoDescriptor = OCI_D_LOB;
                        $numTipoTemporario = OCI_TEMP_CLOB;
                        $bolTipoBlob = true;
                        break;
                    case SQLT_BLOB:
                    case OCI_B_BLOB:
                        $numTipoDescriptor = OCI_D_LOB;
                        $numTipoTemporario = OCI_TEMP_BLOB;
                        $bolTipoBlob = true;
                        break;
                    default:
                        throw new InfraException(
                            'Tipo inváido no parâmetro ' . $arrChaves[$i] . ' para operações com bind no banco de dados.'
                        );
                }
                if ($bolTipoBlob) {
                    $arrBlob[$i] = oci_new_descriptor($this->conexao, $numTipoDescriptor);
                    oci_bind_by_name($resResultado, $arrChaves[$i], $arrBlob[$i], -1, $numTipo);
                    $arrBlob[$i]->writeTemporary($arrCampos[$arrChaves[$i]][0], $numTipoTemporario);
                } else {
                    oci_bind_by_name(
                        $resResultado,
                        $arrChaves[$i],
                        $arrCampos[$arrChaves[$i]][0],
                        $numTamanho,
                        $numTipo
                    );
                }
            }
        }
        if (!$this->transacao) {
            oci_execute($resResultado, OCI_COMMIT_ON_SUCCESS);
        } else {
            oci_execute($resResultado, OCI_NO_AUTO_COMMIT);
        }
        if ($resResultado === false) {
            throw new InfraException(oci_error($this->conexao), null, substr($strSql, 0, INFRA_TAM_MAX_LOG_SQL));
        }
        $numReg = oci_num_rows($resResultado);
        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraOracle->executarSqlTipado] ' . $numReg . ' registro(s) afetado(s)'
            );
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->executarSqlTipado] ' . $numSeg . ' s');
        }
        return $numReg;
    }

    /**
     * Executar função Oracle se houver conexão aberta
     *
     * Exemplo de função existente no banco Oracle:
     *
     *     FUNCTION fTriplicar(p IN NUMBER) RETURN NUMBER IS
     *     BEGIN
     *         RETURN p * 3;
     *     END;
     *
     * @param string $sql
     *            função a ser executada.
     *            Ex.: $Stmt = 'Begin :r := fTriplicar(:p); End;';
     *
     * @param array $arrParametrosBind
     *            Lista de parâmetros (chave => valor) de entrada que serão passados por bind.
     *            Ex.: $arrParam = array(":p"=>8, ":r"=>"");
     *
     * @retorno string $retornoFuncao, com tamanho limitado a 512 caracteres
     *
     * @throws Exception
     * @throws InfraException
     *
     * Exemplo completo de uso:
     * ------------------------
     *     $arrParametros = array(":p"=>8, ":r"=>"");
     *     $strStmt = "BEGIN :r := fTriplicar(:p); END;";
     *     $strRetorno = $objBanco->executarFuncaoOracle($strStmt, $arrParametros);
     *
     *
     * 04/07/2019 - Teobaldo J.: criacao
     */
    public function executarFuncaoOracle($sql, $arrParametrosBind)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->executarFuncaoOracle] ' . $sql);
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }

        if (is_null($this->getIdConexao())) {
            throw new InfraException('Tentando executar função Oracle em uma conexão fechada.');
        }

        $resultado = oci_parse($this->conexao, $sql);

        if ($arrParametrosBind != null && count($arrParametrosBind) > 0) {
            // ober variavel de retorno
            $iPos1 = strpos($sql, ":");
            $iPos2 = strpos($sql, ":=", $iPos1 + 1);
            $paramRetorno = trim(substr($sql, $iPos1, ($iPos2 - $iPos1)));

            $arrParam = &$arrParametrosBind;

            $chaves = array_keys($arrParametrosBind);
            for ($i = 0; $i < count($chaves); $i++) {
                if ($chaves[$i] == $paramRetorno) {
                    $retornoFuncao = $arrParam[$chaves[$i]];
                    oci_bind_by_name($resultado, $chaves[$i], $retornoFuncao, 512);
                } else {
                    oci_bind_by_name($resultado, $chaves[$i], $arrParam[$chaves[$i]]);
                }
            }
        }

        oci_execute($resultado);

        if ($resultado === false) {
            throw new InfraException(oci_error($this->conexao), null, $sql);
        }

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra('[InfraOracle->executarFuncaoOracle] ' . $numSeg . ' s');
        }

        return $retornoFuncao;
    }

}

?>
