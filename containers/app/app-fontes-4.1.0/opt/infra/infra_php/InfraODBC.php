<?php

/**
 * @package infra_php
 *
 */
abstract class InfraODBC implements InfraIBanco
{
    private $conexao;
    private $id;
    private $transacao;
    private $driver; // tipo de banco

    public abstract function getServidor();

    public abstract function getPorta();

    public abstract function getBanco();

    public abstract function getUsuario();

    public abstract function getSenha();

    private static $ODBC_INFO_RECORDS = 0;
    private static $ODBC_INFO_DUPLICATES = 1;
    private static $ODBC_INFO_WARNINGS = 2;
    private static $ODBC_INFO_DELETED = 3;
    private static $ODBC_INFO_SKIPPED = 4;
    private static $ODBC_INFO_ROWS_MATCHED = 5;
    private static $ODBC_INFO_CHANGED = 6;
    private static $ODBC_INFO_DRIVER = 7;

    private static $ODBC_TRANS_BEGIN = '';
    private static $ODBC_TRANS_COMMIT = '';
    private static $ODBC_TRANS_ROLLBACK = '';


    public function __construct()
    {
        $this->conexao = null;
        $this->id = null;
        $this->driver = null;
        $this->transacao = false;
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

    public function isBolProcessandoTransacao()
    {
        return $this->transacao;
    }

    public function isBolManterConexaoAberta()
    {
        return false;
    }

//  seleciona base de dados em MySQL
    private function selecionaBanco($base)
    {
        //echo '<br/>' . __FILE__ . ":" . __LINE__ . ":::::::::" . $dsn; flush();

        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->selecionaBanco] ' . $this->getIdBanco());
        }


        if ($this->conexao === null) {
            throw new InfraException('Tentativa de selecionar banco sem abrir conexão.');
        }
        $result = null;
        switch ($this->driver) {
            case 'mysql':
            case 'freetds':
                $result = odbc_exec($this->conexao, 'USE ' . $base);
                break;
            default:
                $result = true;
                break;
        }
        return $result;
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

    private function formatarSelecaoAsVarchar($tabela, $campo, $alias)
    {
        $ret = 'CAST(';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }
        $ret .= $campo . ' as varchar)';

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
        switch ($this->driver) {
            case 'mysql':
                return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
            case 'freetds':
                return $this->formatarSelecaoAsVarchar($tabela, $campo, $alias);
            default:
                return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
        }
    }

    public function formatarSelecaoDbl($tabela, $campo, $alias)
    {
        switch ($this->$driver) {
            case 'mysql':
                return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
            case 'freetds':
                return $this->formatarSelecaoAsVarchar($tabela, $campo, $alias);
            default:
                return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
        }
    }

    public function formatarSelecaoBin($tabela, $campo, $alias)
    {
        return '';
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
        switch ($this->$driver) {
            case 'mysql':
                return '\'' . str_replace('\'', '\'\'', $str) . '\'';
            case 'freetds':
                $str = str_replace("\'", '\'', $str);
                $str = str_replace("'", '\'\'', $str);
                return '\'' . $str . '\'';
            default:
                return '\'' . str_replace('\'', '\'\'', $str) . '\'';
        }
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
        if (trim($num) === '') {
            return 'NULL';
        }
        return '\'' . $num . '\'';
    }

    public function formatarGravacaoDin($din)
    {
        if (trim($din) === '') {
            return 'NULL';
        }
        return InfraUtil::prepararDin($din);
    }

    public function formatarGravacaoDbl($dbl)
    {
        if (trim($dbl) === '') {
            return 'NULL';
        }

        return InfraUtil::prepararDbl($dbl);
    }

    public function formatarGravacaoBin($dbl)
    {
        return 0;
    }

//LEITURA		

    public function formatarPesquisaStr($strTabela, $strCampo, $strValor, $strOperador, $bolCaseInsensitive, $strBind)
    {
        switch ($this->driver) {
            case 'mysql':
                return 'upper(' . $strCampo . ') LIKE \'' . str_replace(
                        '\'',
                        '\'\'',
                        InfraString::transformarCaixaAlta($strValor)
                    ) . '\' ';
            case 'freetds':
                $strValor = str_replace("\'", '\'', $strValor);
                $strValor = str_replace("'", '\'\'', $strValor);
                return 'upper(' . $strCampo . ') LIKE \'' . InfraString::transformarCaixaAlta($strValor) . '\' ';
            default:
                return 'upper(' . $strCampo . ') LIKE \'' . str_replace(
                        '\'',
                        '\'\'',
                        InfraString::transformarCaixaAlta($strValor)
                    ) . '\' ';
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

    public function formatarLeituraBin($dbl)
    {
        return 0;
    }


    public function abrirConexao()
    {
        try {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra('[InfraODBC->abrirConexao] ' . $this->getIdBanco());
            }


            //InfraDebug::getInstance()->gravarInfra('[InfraODBC->abrirConexao] 10');

            if ($this->conexao != null) {
                throw new InfraException('Tentativa de abrir nova conexão sem fechar a anterior.');
            }

            //InfraDebug::getInstance()->gravarInfra('[InfraODBC->abrirConexao] 20');
            // SERVIDOR = NOME_DO_DSN, Os detalhes do acesso estão no DSN em /etc/unixODBC/odbc.ini
            $dsn_name = $this->getServidor();
            $dsn = null;
            if ($this->getPorta()) {
                $dsn .= '; Port=' . $this->getPorta();
            }
            if ($this->getBanco()) {
                $dsn .= '; Database=' . $this->getBanco();
                //$dsn .= '; Initial Catalog=' . $this->getBanco();

            }

            if ($this->getUsuario()) {
                $dsn .= '; UID=' . $this->getUsuario();
            }
            if ($this->getSenha()) {
                $dsn .= '; PWD=' . $this->getSenha();
            }

            if ($dsn) {
                $dsn = "DSN=" . $dsn_name . $dsn . ";";
            } else {
                $dsn = $dsn_name;
            }
//  	    $dsn = "Data Source=" . $dsn_name;

            $this->conexao = odbc_connect($dsn, $this->getUsuario(), $this->getSenha());
            //$this->conexao = odbc_connect($dsn);

            if (!$this->conexao) {
                throw new InfraException('Não foi possível abrir conexão com o banco de dados.');
            }
            $dsn_name = strtolower($dsn_name);

            $this->id = $this->getIdBanco();

            // InfraDebug::getInstance()->gravarInfra('[InfraODBC->abrirConexao] 30');
            $this->driver = $this->getODBCDriver($this->conexao, $dsn_name);
            //echo __FILE__ . ":" . __LINE__ . " = " . $dsn; flush();
            switch ($this->driver) {
                case 'mysql':
                    self::$ODBC_TRANS_BEGIN = 'BEGIN';
                    self::$ODBC_TRANS_COMMIT = 'COMMIT';
                    self::$ODBC_TRANS_ROLLBACK = 'ROLLBACK';
                    break;
                case 'freetds':
                    self::$ODBC_TRANS_BEGIN = 'BEGIN TRANSACTION';
                    self::$ODBC_TRANS_COMMIT = 'COMMIT TRANSACTION';
                    self::$ODBC_TRANS_ROLLBACK = 'ROLLBACK TRANSACTION';
                    break;
                default:
                    self::$ODBC_TRANS_BEGIN = 'BEGIN';
                    self::$ODBC_TRANS_COMMIT = 'COMMIT';
                    self::$ODBC_TRANS_ROLLBACK = 'ROLLBACK';
            }
            //echo 'Antes;;;;;<br/>';
            if (!$this->selecionaBanco($this->getBanco())) {
                throw new InfraException('Não foi possível selecionar o banco de dados.');
            }
            //echo '<br/>' . __FILE__ . ":" . __LINE__ . " == " . $dsn; flush();

        } catch (Exception $e) {
            switch ($this->driver) {
                case 'mysql':
                    throw $e;
                case 'freetds':
                    if (strpos(strtolower($e->__toString()), 'unable to connect to server')) {
                        throw new InfraException('Não foi possível abrir conexão com a base de dados.');
                    } else {
                        throw $e;
                    }
                default:
                    throw $e;
            }
        }
    }

    public function fecharConexao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->fecharConexao] ' . $this->getIdConexao());
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->fecharConexao] 10');

        if ($this->conexao == null) {
            throw new InfraException('Tentativa de fechar conexão que não foi aberta.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->fecharConexao] 20');

        odbc_close($this->conexao);

        $this->conexao = null;
        $this->id = null;
        $this->driver = null;
    }

    public function abrirTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->abrirTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando abrir transação em uma conexão fechada.');
        }
        //echo 'Abrindo: ' . self::$ODBC_TRANS_BEGIN;
        $this->executarSql(self::$ODBC_TRANS_BEGIN);

        $this->transacao = true;
    }

    public function confirmarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->confirmarTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando confirmar transação em uma conexão fechada.');
        }

        $this->executarSql(self::$ODBC_TRANS_COMMIT);

        $this->transacao = false;
    }

    public function cancelarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->cancelarTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando desfazer transação em uma conexão fechada.');
        }

        $this->executarSql(self::$ODBC_TRANS_ROLLBACK);

        $this->transacao = false;
    }

    public function consultarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultarSql] ' . $sql);
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultarSql] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar uma consulta em uma conexão fechada.');
        }

        if ($this->getIdBanco() !== $this->getIdConexao()) {
            throw new InfraException(
                'Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.'
            );
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultarSql] 15');
        if (!$this->selecionaBanco($this->getBanco())) {
            throw new InfraException('Não foi possível selecionar a base de dados para execução da consulta.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultarSql] 20');
        $resultado = odbc_exec($this->conexao, $sql);

        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultarSql] 30');
        if ($resultado === false) {
            throw new InfraException(odbc_errormsg($this->conexao), null, $sql);
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultarSql] 40');
        $vetor_resultado = array();
        while ($registro = odbc_fetch_array($resultado)) {
            $vetor_resultado[] = $registro;
            //foreach($registro as $reg){
            //  InfraDebug::getInstance()->gravar('#'.$reg);
            //}
        }

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultarSql] ' . $numSeg . ' s');
        }


        return $vetor_resultado;
    }

    public function paginarSql($sql, $ini, $qtd, $arrCamposBind = null)
    {
        switch ($this->driver) {
            case 'mysql':
                return $this->paginarMySql($sql, $ini, $qtd);
            case 'freetds':
                return $this->paginarSqlServer($sql, $ini, $qtd);
            default:
                return $this->paginarMySql($sql, $ini, $qtd);
        }
    }

    private function paginarSqlServer($sql, $ini, $qtd)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->paginarSqlServer]');
        }


        $arr = explode(' ', $sql);
        $select = '';


        for ($i = 0; $i < count($arr); $i++) {
            if (strtoupper($arr[$i]) == 'FROM') {
                break;
            }

            $select .= ' ' . $arr[$i];
        }

        $from = '';
        for (; $i < count($arr); $i++) {
            if (strtoupper($arr[$i]) == 'ORDER') {
                break;
            }
            $from .= ' ' . $arr[$i];
        }

        if (trim($from) == '') {
            throw new InfraException('Cláusula FROM não encontrada.');
        }

        $order = '';
        for (; $i < count($arr); $i++) {
            $order .= ' ' . $arr[$i];
        }

        if (trim($order) == '') {
            throw new InfraException(
                'Para utilizar a paginação com este banco de dados é necessário que a consulta utilize pelo menos um campo para ordenação.'
            );
        }

        $sql = '';
        $sql .= ' SELECT TOP ' . $qtd . ' * FROM (';

        if (strpos(strtoupper($select), 'DISTINCT') === false) {
            $sql .= $select;
            $sql .= ',InfraRowCount = COUNT(*) OVER(),ROW_NUMBER() OVER (' . $order . ') as InfraRowNumber ';
            $sql .= $from;
        } else {
            /*
             Se tiver DISTINCT tem que montar de outra maneira, adicionando outro nível de consulta:
             SELECT TOP 100 * FROM (
             SELECT *
             ,ROW_NUMBER() OVER (order by id_pessoa) as InfraRowNumber [order by sem o nome da tabela nos campos]
             ,InfraRowCount = COUNT(*) OVER()
             FROM  (
             [sql original sem o order by]
             ) as InfraTabelaDistinct
             ) AS InfraTabela
             WHERE InfraRowNumber > 10
             */

            $arrOrder = explode(' ', $order);
            $order = '';
            for ($i = 0; $i < count($arrOrder); $i++) {
                $order .= ' ';
                if (strtoupper($arrOrder[$i]) == 'ORDER' || strtoupper($arrOrder[$i]) == 'BY' || strtoupper(
                        $arrOrder[$i]
                    ) == ',') {
                    $order .= $arrOrder[$i];
                } else {
                    $pos = strpos($arrOrder[$i], '.');
                    if ($pos === false) {
                        $order .= $arrOrder[$i];
                    } else {
                        $order .= substr($arrOrder[$i], $pos + 1);
                    }
                }
            }

            $sql .= ' SELECT *';
            $sql .= ',InfraRowCount = COUNT(*) OVER(),ROW_NUMBER() OVER (' . $order . ') as InfraRowNumber ';
            $sql .= ' FROM (';
            $sql .= $select;
            $sql .= $from;
            $sql .= ') AS InfraTabelaDistinct';
        }
        $sql .= ') AS InfraTabela WHERE InfraRowNumber > ' . $ini;

        $rs = $this->consultarSql($sql);

        return array('totalRegistros' => $rs[0]['InfraRowCount'], 'registrosPagina' => $rs);
    }

    private function paginarMySql($sql, $ini, $qtd)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->paginarMySql] ' . $sql);
        }


        $arr = explode(' ', $sql);
        $select = '';
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
        $rsTotal = $this->consultarSql($sqlTotal);

        $sql .= ' LIMIT ' . $ini . ',' . $qtd;

        $rs = $this->consultarSql($sql);

        return array('totalRegistros' => $rsTotal[0]['total'], 'registrosPagina' => $rs);
    }

    public function limitarSql($sql, $qtd, $arrCamposBind = null)
    {
        switch ($this->driver) {
            case 'mysql':
                return $this->limitarMySql($sql, $ini, $qtd);
            case 'freetds':
                return $this->limitarSqlServer($sql, $ini, $qtd);
            default:
                return $this->limitarMySql($sql, $ini, $qtd);
        }
    }

    private function limitarMySql($sql, $qtd)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->limitarMySql] ' . $sql);
        }


        $sql .= ' LIMIT 0,' . $qtd;
        return $this->consultarSql($sql);
    }

    private function limitarSqlServer($sql, $qtd)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->limitarSqlServer]');
        }


        $sql = trim($sql);
        if (strtoupper(substr($sql, 0, 7)) != 'SELECT ') {
            throw new InfraException('Início da consulta não localizado.');
        }
        $sql = substr($sql, 0, 7) . 'TOP ' . $qtd . substr($sql, 6);
        return $this->consultarSql($sql);
    }

    public function executarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->executar] ' . substr($sql, 0, INFRA_TAM_MAX_LOG_SQL));
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->executar] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar um comando em uma conexão fechada.');
        }

        if ($this->getIdBanco() !== $this->getIdConexao()) {
            throw new InfraException(
                'Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.'
            );
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->consultar] 15');
        if (!$this->selecionaBanco($this->getBanco())) {
            throw new InfraException('Não foi possível selecionar a base de dados para execução do comando.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->executar] 20');
        $resultado = odbc_exec($this->conexao, $sql);
        //InfraDebug::getInstance()->gravarInfra('[InfraODBC->executar] 30');

        if ($resultado === false) {
            //InfraDebug::getInstance()->gravarInfra('[InfraODBC->executar] 35');
            throw new InfraException(odbc_errormsg($this->conexao), null, substr($sql, 0, INFRA_TAM_MAX_LOG_SQL));
        }

        $arrInfo = $this->getODBCInfo($this->conexao, $resultado);


        if ($arrInfo[self::$ODBC_INFO_WARNINGS] > 0) {
            throw new InfraException('Erro executando comando.');
        }


        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraODBC->executar] ' . $arrInfo[self::$ODBC_INFO_ROWS_MATCHED] . ' registro(s) afetado(s)'
            );
            InfraDebug::getInstance()->gravarInfra('[InfraODBC->executar] ' . $numSeg . ' s');
        }


        return $arrInfo[self::$ODBC_INFO_ROWS_MATCHED];
    }


    public function lerData($Date)
    {
        switch ($this->driver) {
            case 'mysql':
                return $this->lerDataMySql($date);
            case 'freetds':
                return $this->lerDataSqlServer($date);
            default:
                return $this->lerDataMySql($date);
        }
    }

    private function lerDataMySql($odbcDate)
    {
        if ($mySqlDate === null) {
            return null;
        }

        //2007-01-01 12:12:12
        $tam = strlen($mySqlDate);

        if ($tam != 10 && $tam != 19) {
            throw new InfraException('Tamanho de data inválido.', null, $mySqlDate);
        }

        $ret = substr($mySqlDate, 8, 2) . '/' . substr($mySqlDate, 5, 2) . '/' . substr($mySqlDate, 0, 4);

        if ($tam == 19) {
            $ret .= substr($mySqlDate, 10);
        }

        return $ret;
    }

    private function lerDataSqlServer($sqlServerDate)
    {
        //InfraDebug::getInstance()->gravarInfra($sqlServerDate);
        /* php.ini
   ; Specify how datetime and datetim4 columns are returned
   ; On => Returns data converted to SQL server settings
   ; Off => Returns values as YYYY-MM-DD hh:mm:ss
   ;mssql.datetimeconvert = On
        */

        if ($sqlServerDate === null) {
            return null;
        }

        if (strlen($sqlServerDate) != 19) {
            throw new InfraException('Tamanho de data inválido.', null, $sqlServerDate);
        }

        return substr($sqlServerDate, 8, 2) . '/' . substr($sqlServerDate, 5, 2) . '/' . substr(
                $sqlServerDate,
                0,
                4
            ) . substr($sqlServerDate, 10);
    }

    public function gravarData($brasilDate)
    {
        switch ($this->driver) {
            case 'mysql':
                return $this->gravarDataMySql($brasilDate);
            case 'freetds':
                return $this->gravarDataSqlServer($brasilDate);
            default:
                return $this->gravarDataMySql($brasilDate);
        }
    }

    private function gravarDataMySql($brasilDate)
    {
        if (trim($brasilDate) === '') {
            return 'NULL';
        }

        $ret = '\'' . substr($brasilDate, 6, 4) . '-' . substr($brasilDate, 3, 2) . '-' . substr($brasilDate, 0, 2);

        if (strlen($brasilDate) == 19) {
            $ret .= substr($brasilDate, 10);
        }

        $ret .= '\'';


        return $ret;
    }

    private function gravarDataSqlServer($brasilDate)
    {
        if (trim($brasilDate) === '') {
            return 'NULL';
        }

        if (strlen($brasilDate) == 10) {
            $brasilDate .= ' 00:00:00';
        }

        //31/12/2005 15:23:50 -> 2005-12-31 15:23:50
        return '\'' . substr($brasilDate, 6, 4) . '-' . substr($brasilDate, 3, 2) . '-' . substr(
                $brasilDate,
                0,
                2
            ) . substr($brasilDate, 10) . '\'';
    }

    function getODBCInfo($connid, $linkid = null)
    {
        $return = array();
        $linkid ? $num = odbc_num_rows($linkid) : $num = odbc_num_rows();
        $strInfo = '';
        $strInfo .= ' Records: ' . $num;
        preg_match("/Records: ([0-9]*)/", $strInfo, $records);
        preg_match("/Duplicates: ([0-9]*)/", $strInfo, $dupes);
        $linkid ? $err = odbc_error($connid) : $err = odbc_error();
        $strInfo .= ' Warnings: ' . $err;
        preg_match("/Warnings: ([0-9]*)/", $strInfo, $warnings);
        $strInfo .= ' Deleted: ' . $num;
        preg_match("/Deleted: ([0-9]*)/", $strInfo, $deleted);
        preg_match("/Skipped: ([0-9]*)/", $strInfo, $skipped);
        $strInfo .= ' Rows matched: ' . $num;
        preg_match("/Rows matched: ([0-9]*)/", $strInfo, $rows_matched);
        $strInfo .= ' Changed: ' . $num;
        preg_match("/Changed: ([0-9]*)/", $strInfo, $changed);

        $return[self::$ODBC_INFO_RECORDS] = $records[1];
        $return[self::$ODBC_INFO_DUPLICATES] = $dupes[1];
        $return[self::$ODBC_INFO_WARNINGS] = $warnings[1];
        $return[self::$ODBC_INFO_DELETED] = $deleted[1];
        $return[self::$ODBC_INFO_SKIPPED] = $skipped[1];
        $return[self::$ODBC_INFO_ROWS_MATCHED] = $rows_matched[1];
        $return[self::$ODBC_INFO_CHANGED] = $changed[1];
        $return[self::$ODBC_INFO_DRIVER] = $this->driver;

        return $return;
    }

    function getODBCDriver($linkid, $dsn)
    {
        // InfraDebug::getInstance()->gravarInfra('[InfraODBC->getODBCDriver]');
        $retorno = null;

        $result = odbc_data_source($linkid, SQL_FETCH_FIRST);
        //echo __FILE__ . ":" . __LINE__ . ":" . $dsn; flush();
        $dsn_name = strtolower($dsn);
        while ($result) {
            if ($dsn_name == strtolower($result['server'])) {
                $driver = strtolower($result['description']);
                // quando for indicada a LIB em vez de um nome simbolico
                $driver = basename($driver, '.so');
                $driver = basename($driver, '.dll'); // ARGHHH!!!
                $retorno = $driver;
                if (substr_compare($retorno, 'lib', 0, 3)) {
                    $retorno = substr($retorno, 3, (strlen($retorno) - 3));
                }
                if (preg_match('/mysql|myodbc/', $driver)) {
                    $retorno = 'mysql';
                } elseif (preg_match('/freetds|tds/', $driver)) {
                    $retorno = 'freetds';
                }
                break;
            } else {
                $result = odbc_data_source($linkid, SQL_FETCH_NEXT);
            }
        }
        //echo __FILE__ . ":" . __LINE__ . ":" . $dsn; flush();
        return $retorno;
    }
}

?>
