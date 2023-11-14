<?php

/**
 * @package infra_php
 *
 */
abstract class InfraMySqli extends InfraMySql
{
    private $conexao;
    private $id;
    private $transacao;
    private $arrOpcoes;

    //public abstract function getServidor();

    //public abstract function getPorta();

    //public abstract function getBanco();

    //public abstract function getUsuario();

    //public abstract function getSenha();

    private static $MYSQL_INFO_RECORDS = 0;
    private static $MYSQL_INFO_DUPLICATES = 1;
    private static $MYSQL_INFO_WARNINGS = 2;
    private static $MYSQL_INFO_DELETED = 3;
    private static $MYSQL_INFO_SKIPPED = 4;
    private static $MYSQL_INFO_ROWS_MATCHED = 5;
    private static $MYSQL_INFO_CHANGED = 6;

    public function __construct()
    {
        $this->conexao = null;
        $this->id = null;
        $this->transacao = false;
        $this->arrOpcoes = array();
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

    public function setArrOpcoes($arrOpcoes)
    {
        $this->arrOpcoes = $arrOpcoes;
    }

    public function getArrOpcoes()
    {
        return $this->arrOpcoes;
    }

    public function getValorSequencia($sequencia)
    {
        $this->executarSql('INSERT INTO ' . $sequencia . ' (campo) VALUES (\'0\')');
        return $this->conexao->insert_id;
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

    public function isBolConsultaRetornoAssociativo()
    {
        return false;
    }

    public function isBolUsarPreparedStatement()
    {
        return false;
    }

    public function getCharset()
    {
        return null;
    }

    //SELECAO

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

    private function formatarSelecaoAsChar($tabela, $campo, $alias)
    {
        $ret = 'CAST(';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }
        $ret .= $campo . ' AS CHAR)';

        if ($alias !== null) {
            $ret .= ' AS ' . $alias;
        } else {
            $ret .= ' AS ' . $campo;
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
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    public function formatarSelecaoDbl($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
        //return $this->formatarSelecaoAsChar($tabela,$campo,$alias);
    }

    public function formatarSelecaoBin($tabela, $campo, $alias)
    {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
    }

    //GRAVACAO
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

        return '\'' . str_replace("\\", "\\\\", str_replace('\'', '\'\'', $str)) . '\'';
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
        if ($this->isBolUsarPreparedStatement()) {
            return $bin;
        } else {
            return '0x' . bin2hex($bin);
        }
    }

    //LEITURA
    public function converterStr($tabela, $campo)
    {
        $ret = 'CAST(';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }
        $ret .= $campo . ' AS CHAR)';

        return $ret;
    }

    public function formatarPesquisaStr($strTabela, $strCampo, $strValor, $strOperador, $bolCaseInsensitive, $strBind)
    {
        if ($bolCaseInsensitive) {
            if ($strBind == null) {
                return 'upper(' . $strCampo . ') ' . $strOperador . ' \'' . str_replace('\'','\'\'',InfraString::transformarCaixaAlta(str_replace('\\', '\\\\\\\\', $strValor))) . '\' ';
            } else {
                return 'upper(' . $strCampo . ') ' . $strOperador . ' ' . $strBind . ' ';
            }
        } else {
            if ($strBind == null) {
                return $strCampo . ' ' . $strOperador . ' \'' . str_replace('\'', '\'\'', str_replace('\\', '\\\\\\\\', $strValor)) . '\' ';
            } else {
                return $strCampo . ' ' . $strOperador . ' ' . $strBind . ' ';
            }
        }
    }

    public function formatarLeituraDta($dta)
    {
        $dta = $this->lerData($dta);
        if ($dta !== null) {
            $dta = substr($dta, 0, 10);
        }
        return $dta;
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
        if ($bol == 1) {
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
        return $bin;
    }

    public function abrirConexao()
    {
        try {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra('[InfraMySqli->abrirConexao] ' . $this->getIdBanco());
            }

            //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->abrirConexao] 10');

            if ($this->conexao != null) {
                throw new InfraException('Tentativa de abrir nova conexão sem fechar a anterior.');
            }

            //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->abrirConexao] 20');
            $this->conexao = new ConectorMySqli($this);

            if (!$this->conexao) {
                throw new InfraException('Não foi possível abrir conexão com o banco de dados.');
            }

            if ($this->getCharset() != null) {
                try {
                    $retornoComando = $this->conexao->set_charset($this->getCharset());
                } catch (Exception $e) {
                    throw new InfraException('Erro configurando charset do banco de dados.');
                }

                if ($retornoComando === false) {
                    throw new InfraException('Erro configurando charset do banco de dados.');
                }
            }

            $this->id = $this->getIdBanco();
            //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->abrirConexao] 30');

        } catch (Exception $e) {
            if (strpos(strtolower($e->__toString()), 'mysql_connect') !== false) {
                throw new InfraException('Não foi possível abrir conexão com a base de dados.');
            } else {
                throw $e;
            }
        }
    }

    public function fecharConexao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->fecharConexao] ' . $this->getIdConexao());
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->fecharConexao] 10');

        if ($this->conexao == null) {
            throw new InfraException('Tentativa de fechar conexão que não foi aberta.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->fecharConexao] 20');

        $this->conexao->close();

        $this->conexao = null;
        $this->id = null;
    }

    public function abrirTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->abrirTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando abrir transação em uma conexão fechada.');
        }

        $this->conexao->autocommit(false);

        $this->transacao = true;
    }

    public function confirmarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->confirmarTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando confirmar transação em uma conexão fechada.');
        }

        $this->conexao->commit();

        $this->conexao->autocommit(true);

        $this->transacao = false;
    }

    public function cancelarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->cancelarTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando desfazer transação em uma conexão fechada.');
        }

        $this->conexao->rollback();

        $this->conexao->autocommit(true);

        $this->transacao = false;
    }

    public function consultarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraMySqli->consultarSql] ' . InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->consultarSql] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar uma consulta em uma conexão fechada.');
        }

        if ($this->getIdBanco() !== $this->getIdConexao()) {
            throw new InfraException(
                'Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.'
            );
        }

        $arrResultado = array();

        if ($arrCamposBind != null && count($arrCamposBind) > 0) {
            try {
                $stmt = $this->conexao->prepare($sql);
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            if ($stmt === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            $arrParams = array();

            $strTiposBind = '';
            foreach ($arrCamposBind as $arrBind) {
                $strTiposBind .= $arrBind[0];
            }

            $arrParams[] = &$strTiposBind;

            $numBind = count($arrCamposBind);
            $arrBlobs = array();
            for ($i = 0; $i < $numBind; $i++) {
                if ($arrCamposBind[$i][0] == 'b') {
                    $arrBlobs[] = array($i, $arrCamposBind[$i][1]);
                    $arrCamposBind[$i][1] = null;
                }
                $arrParams[] = &$arrCamposBind[$i][1];
            }

            try {
                $retornoComando = call_user_func_array(array($stmt, 'bind_param'), $arrParams);
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            if ($retornoComando === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            if (!empty($arrBlobs)) {
                foreach ($arrBlobs as $arrBlob) {
                    try {
                        $retornoComando = $stmt->send_long_data($arrBlob[0], $arrBlob[1]);
                    } catch (Exception $e) {
                        throw new InfraException(
                            $e->getMessage(),
                            $e,
                            InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                        );
                    }

                    if ($retornoComando === false) {
                        throw new InfraException(
                            $this->conexao->error,
                            null,
                            InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                        );
                    }
                }
            }

            try {
                $retornoComando = $stmt->execute();

                $resultado = $stmt->get_result();
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            if ($retornoComando === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            if ($resultado === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            $tipo_vetor = MYSQLI_BOTH;
            if ($this->isBolConsultaRetornoAssociativo()) {
                $tipo_vetor = MYSQLI_ASSOC;
            }

            while ($registro = $resultado->fetch_array($tipo_vetor)) {
                $arrResultado[] = $registro;
            }

            $stmt->close();
        } else {
            //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->consultarSql] 20');
            try {
                $resultado = $this->conexao->query($sql);
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->consultarSql] 30');
            if ($resultado === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }
            //InfraDebug::getInstance()->gravarInfra('[InfraMySqli->consultarSql] 40');

            $tipo_vetor = MYSQLI_BOTH;
            if ($this->isBolConsultaRetornoAssociativo()) {
                $tipo_vetor = MYSQLI_ASSOC;
            }

            while ($registro = mysqli_fetch_array($resultado, $tipo_vetor)) {
                $arrResultado[] = $registro;
            }
        }

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraMySqli->consultarSql] ' . InfraUtil::formatarMilhares(count($arrResultado)) . ' registro(s)'
            );
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->consultarSql] ' . $numSeg . ' s');
        }

        return $arrResultado;
    }

    public function paginarSql($sql, $ini, $qtd, $arrCamposBind = null)
    {
        if (!is_numeric($ini)) {
            throw new InfraException('Valor numérico inválido [' . $ini . '].');
        }

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        $posSelect = strpos($sql, 'SELECT') + 6;
        $sql = substr($sql, 0, $posSelect) . ' SQL_CALC_FOUND_ROWS' . substr($sql, $posSelect);
        $sql .= ' LIMIT ' . $ini . ',' . $qtd;

        $sqlTotal = 'SELECT FOUND_ROWS() as total';

        $rs = $this->consultarSql($sql, $arrCamposBind);
        $rsTotal = $this->consultarSql($sqlTotal);

        return array('totalRegistros' => $rsTotal [0]['total'], 'registrosPagina' => $rs);
    }

    public function limitarSql($sql, $qtd, $arrCamposBind = null)
    {
        //if (InfraDebug::isBolProcessar()) {
        //  InfraDebug::getInstance()->gravarInfra('[InfraMySqli->limitarSql] ' . $sql);
        //}

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        $sql .= ' LIMIT 0,' . $qtd;
        return $this->consultarSql($sql, $arrCamposBind);
    }

    public function executarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraMySqli->executar] ' . InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentando executar um comando em uma conexão fechada.');
        }

        if ($this->getIdBanco() !== $this->getIdConexao()) {
            throw new InfraException(
                'Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.'
            );
        }

        if ($arrCamposBind != null && count($arrCamposBind) > 0) {
            try {
                $stmt = $this->conexao->prepare($sql);
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            if ($stmt === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            $arrParams = array();

            $strTiposBind = '';
            foreach ($arrCamposBind as $arrBind) {
                $strTiposBind .= $arrBind[0];
            }

            $arrParams[] = &$strTiposBind;

            $numBind = count($arrCamposBind);
            $arrBlobs = array();
            for ($i = 0; $i < $numBind; $i++) {
                if ($arrCamposBind[$i][0] == 'b') {
                    $arrBlobs[] = array($i, $arrCamposBind[$i][1]);
                    $arrCamposBind[$i][1] = null;
                }
                $arrParams[] = &$arrCamposBind[$i][1];
            }

            try {
                $retornoComando = call_user_func_array(array($stmt, 'bind_param'), $arrParams);
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            if ($retornoComando === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            if (!empty($arrBlobs)) {
                foreach ($arrBlobs as $arrBlob) {
                    try {
                        $retornoComando = $stmt->send_long_data($arrBlob[0], $arrBlob[1]);
                    } catch (Exception $e) {
                        throw new InfraException(
                            $e->getMessage(),
                            $e,
                            InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                        );
                    }

                    if ($retornoComando === false) {
                        throw new InfraException(
                            $this->conexao->error,
                            null,
                            InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                        );
                    }
                }
            }

            try {
                $retornoComando = $stmt->execute();
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            if ($retornoComando === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            $affectedRows = $this->conexao->affected_rows;
            if ($affectedRows == 0) {
                $arrInfo = $this->getMysqlInfo($this->conexao);
                $affectedRows = $arrInfo[self::$MYSQL_INFO_ROWS_MATCHED];
            }

            $stmt->close();
        } else {
            try {
                $retornoComando = $this->conexao->query($sql);
            } catch (Exception $e) {
                throw new InfraException($e->getMessage(), $e, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
            }

            if ($retornoComando === false) {
                throw new InfraException(
                    $this->conexao->error, null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind)
                );
            }

            $affectedRows = $this->conexao->affected_rows;
            if ($affectedRows == 0) {
                $arrInfo = $this->getMysqlInfo($this->conexao);
                $affectedRows = $arrInfo[self::$MYSQL_INFO_ROWS_MATCHED];
            }
        }


        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraMySqli->executar] ' . $affectedRows . ' registro(s) afetado(s)'
            );
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->executar] ' . $numSeg . ' s');
        }

        //return $arrInfo[self::$MYSQL_INFO_ROWS_MATCHED];

        return $affectedRows;
    }

    public function lerData($mySqlDate)
    {
        if ($mySqlDate === null) {
            return null;
        }

        //2007-01-01 12:12:12
        //2015-09-22 08:50:00.000000
        $tam = strlen($mySqlDate);

        if ($tam != 10 && $tam != 19 && $tam != 26) {
            throw new InfraException('Tamanho de data inválido.', null, $mySqlDate);
        }

        $ret = substr($mySqlDate, 8, 2) . '/' . substr($mySqlDate, 5, 2) . '/' . substr($mySqlDate, 0, 4);

        if ($tam == 19) {
            $ret .= substr($mySqlDate, 10);
        } else {
            if ($tam == 26) {
                $ret .= substr($mySqlDate, 10, 9);
            }
        }

        return $ret;
    }

    public function gravarData($brasilDate)
    {
        if (trim($brasilDate) === '') {
            return 'NULL';
        }

        $numTamData = strlen($brasilDate);

        if (($numTamData != 10 && $numTamData != 19) || preg_match("/[^0-9 \/\-:]/", $brasilDate)) {
            throw new InfraException('Data inválida [' . $brasilDate . '].');
        }

        $ret = '\'' . substr($brasilDate, 6, 4) . '-' . substr($brasilDate, 3, 2) . '-' . substr($brasilDate, 0, 2);

        if ($numTamData == 19) {
            $ret .= substr($brasilDate, 10);
        }

        $ret .= '\'';


        return $ret;
    }

    function getMysqlInfo($linkid = null)
    {
        $linkid ? $strInfo = $linkid->info : $strInfo = $this->conexao->info;

        //InfraDebug::getInstance()->gravar($strInfo);

        $return = array();
        preg_match("/Records: ([0-9]*)/", $strInfo, $records);
        preg_match("/Duplicates: ([0-9]*)/", $strInfo, $dupes);
        preg_match("/Warnings: ([0-9]*)/", $strInfo, $warnings);
        preg_match("/Deleted: ([0-9]*)/", $strInfo, $deleted);
        preg_match("/Skipped: ([0-9]*)/", $strInfo, $skipped);
        preg_match("/Rows matched: ([0-9]*)/", $strInfo, $rows_matched);
        preg_match("/Changed: ([0-9]*)/", $strInfo, $changed);

        if (isset($records[1])) {
            $return[self::$MYSQL_INFO_RECORDS] = $records[1];
        } else {
            $return[self::$MYSQL_INFO_RECORDS] = 0;
        }

        if (isset($dupes[1])) {
            $return[self::$MYSQL_INFO_DUPLICATES] = $dupes[1];
        } else {
            $return[self::$MYSQL_INFO_DUPLICATES] = 0;
        }

        if (isset($warnings[1])) {
            $return[self::$MYSQL_INFO_WARNINGS] = $warnings[1];
        } else {
            $return[self::$MYSQL_INFO_WARNINGS] = 0;
        }

        if (isset($deleted[1])) {
            $return[self::$MYSQL_INFO_DELETED] = $deleted[1];
        } else {
            $return[self::$MYSQL_INFO_DELETED] = 0;
        }

        if (isset($skipped[1])) {
            $return[self::$MYSQL_INFO_SKIPPED] = $skipped[1];
        } else {
            $return[self::$MYSQL_INFO_SKIPPED] = 0;
        }

        if (isset($rows_matched[1])) {
            $return[self::$MYSQL_INFO_ROWS_MATCHED] = $rows_matched[1];
        } else {
            $return[self::$MYSQL_INFO_ROWS_MATCHED] = 0;
        }

        if (isset($changed[1])) {
            $return[self::$MYSQL_INFO_CHANGED] = $changed[1];
        } else {
            $return[self::$MYSQL_INFO_CHANGED] = 0;
        }

        return $return;
    }

    public function criarSequencialNativa($strSequencia, $numInicial)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->criarSequencialNativa]');
        }

        $this->executarSql(
            'create table ' . $strSequencia . ' (id int not null primary key AUTO_INCREMENT, campo char(1) null)'
        );
        $this->executarSql('alter table ' . $strSequencia . ' AUTO_INCREMENT = ' . $numInicial);
    }

    public function ping()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySqli->ping] ' . $this->getIdBanco());
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentativa de ping em uma conexão fechada.');
        }
        return $this->conexao->ping();
    }

    public function realEscapeString($str)
    {
        return $this->conexao->real_escape_string($str);
    }
}

class ConectorMySqli extends mysqli
{

    public function __construct(InfraMySqli $objInfraMySqli)
    {
        parent::__construct();

        /*
		Exemplos de passagem de parâmetros de conexão
		if (!parent::options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
            die('Setting MYSQLI_INIT_COMMAND failed');
        }

        if (!parent::options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
            die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
        }*/

        foreach ($objInfraMySqli->getArrOpcoes() as $varOpcao => $varValor) {
            try {
                $resultadoComando = parent::options($varOpcao, $varValor);
            } catch (Exception $e) {
                throw new InfraException('Erro configurando opção do banco de dados [' . $varOpcao . '].');
            }

            if ($resultadoComando === false) {
                throw new InfraException('Erro configurando opção do banco de dados [' . $varOpcao . '].');
            }
        }

        try {
            $resultadoComando = parent::real_connect(
                $objInfraMySqli->getServidor(),
                $objInfraMySqli->getUsuario(),
                $objInfraMySqli->getSenha(),
                $objInfraMySqli->getBanco(),
                $objInfraMySqli->getPorta()
            );
        } catch (Exception $e) {
            throw new InfraException('Falha ao abrir conexão com o banco de dados.');
        }

        if ($resultadoComando === false) {
            throw new InfraException('Não foi possível abrir conexão com o banco de dados.');
        }
    }
}

?>