<?php

/**
 * @package infra_php
 *
 */
abstract class InfraMySql implements InfraIBanco
{
    private $conexao;
    private $id;
    private $transacao;

    public abstract function getServidor();

    public abstract function getPorta();

    public abstract function getBanco();

    public abstract function getUsuario();

    public abstract function getSenha();

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
        $this->executarSql('INSERT INTO ' . $sequencia . ' (campo) VALUES (null)');
        return mysql_insert_id($this->conexao);
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
        return '0x' . bin2hex($bin);
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
            return 'upper(' . $strCampo . ') ' . $strOperador . ' \'' . str_replace('\'','\'\'',InfraString::transformarCaixaAlta(str_replace('\\', '\\\\\\\\', $strValor))) . '\' ';
        } else {
            return $strCampo . ' ' . $strOperador . ' \'' . str_replace('\'', '\'\'', str_replace('\\', '\\\\\\\\', $strValor)) . '\' ';
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
                InfraDebug::getInstance()->gravarInfra('[InfraMySql->abrirConexao] ' . $this->getIdBanco());
            }


            //InfraDebug::getInstance()->gravarInfra('[InfraMySql->abrirConexao] 10');

            if ($this->conexao != null) {
                throw new InfraException('Tentativa de abrir nova conexão sem fechar a anterior.');
            }

            //InfraDebug::getInstance()->gravarInfra('[InfraMySql->abrirConexao] 20');
            // - 2 CLIENT_FOUND_ROWS: retorna o número de linhas encontradas
            $this->conexao = mysql_connect(
                $this->getServidor() . ':' . $this->getPorta(),
                $this->getUsuario(),
                $this->getSenha(),
                true,
                2
            );
            if (!$this->conexao) {
                throw new InfraException('Não foi possível abrir conexão com o banco de dados.');
            }


            $this->id = $this->getIdBanco();

            //InfraDebug::getInstance()->gravarInfra('[InfraMySql->abrirConexao] 30');

            if (!mysql_select_db($this->getBanco(), $this->conexao)) {
                throw new InfraException('Não foi possível selecionar o banco de dados.');
            }
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
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->fecharConexao] ' . $this->getIdConexao());
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->fecharConexao] 10');

        if ($this->conexao == null) {
            throw new InfraException('Tentativa de fechar conexão que não foi aberta.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->fecharConexao] 20');

        mysql_close($this->conexao);

        $this->conexao = null;
        $this->id = null;
    }

    public function abrirTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->abrirTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando abrir transação em uma conexão fechada.');
        }

        $this->executarSql('BEGIN');

        $this->transacao = true;
    }

    public function confirmarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->confirmarTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando confirmar transação em uma conexão fechada.');
        }

        $this->executarSql('COMMIT');

        $this->transacao = false;
    }

    public function cancelarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->cancelarTransacao] ' . $this->getIdConexao());
        }


        if ($this->conexao == null) {
            throw new InfraException('Tentando desfazer transação em uma conexão fechada.');
        }

        $this->executarSql('ROLLBACK');

        $this->transacao = false;
    }

    public function consultarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultarSql] ' . $sql);
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultarSql] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar uma consulta em uma conexão fechada.');
        }

        if ($this->getIdBanco() !== $this->getIdConexao()) {
            throw new InfraException(
                'Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.'
            );
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultarSql] 15');
        if (!mysql_select_db($this->getBanco(), $this->conexao)) {
            throw new InfraException('Não foi possível selecionar a base de dados para execução da consulta.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultarSql] 20');
        $resultado = mysql_query($sql, $this->conexao);

        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultarSql] 30');
        if ($resultado === false) {
            throw new InfraException(mysql_error(), null, $sql);
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultarSql] 40');
        $vetor_resultado = array();

        $tipo_vetor = MYSQL_BOTH;
        if ($this->isBolConsultaRetornoAssociativo()) {
            $tipo_vetor = MYSQL_ASSOC;
        }

        while ($registro = mysql_fetch_array($resultado, $tipo_vetor)) {
            $vetor_resultado[] = $registro;
        }

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraMySql->consultarSql] ' . InfraUtil::formatarMilhares(count($vetor_resultado)) . ' registro(s)'
            );
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultarSql] ' . $numSeg . ' s');
        }


        return $vetor_resultado;
    }

    public function paginarSql($sql, $ini, $qtd, $arrCamposBind = null)
    {
        if (!is_numeric($ini)) {
            throw new InfraException('Valor numérico inválido [' . $ini . '].');
        }

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        $arr = explode(' ', $sql);

        $sql = '';
        for ($i = 0; $i < count($arr); $i++) {
            if (strtoupper($arr [$i]) == 'SELECT') {
                $arr [$i] = $arr [$i] . ' SQL_CALC_FOUND_ROWS';
            }
            $sql .= ' ' . $arr [$i];
        }

        $sql .= ' LIMIT ' . $ini . ',' . $qtd;

        $sqlTotal = 'SELECT FOUND_ROWS() as total';

        $rs = $this->consultarSql($sql);
        $rsTotal = $this->consultarSql($sqlTotal);

        return array('totalRegistros' => $rsTotal [0]['total'], 'registrosPagina' => $rs);
    }

    /*
        public function paginarSql($sql,$ini,$qtd){
               InfraDebug::getInstance()->gravarInfra('[InfraMySql->paginarSql]');

        $arr = explode(' ',$sql);
        $select = '';

        $bolDistinct = false;
              foreach( $arr as  $pl){
                  if ( strtoupper($pl) == 'DISTINCT' )  {
                      $bolDistinct = true;
                      break;
                  }
              }

        // Se houver DISTINCT, armazena os nomes de campos para construir comando diferenciado.
        if ($bolDistinct) {
          // Pega nomes de todos os campos, desde o "SELECT DISTINCT" até o "FROM".
          $numTamanhoComando = strlen($sql);
          $numPosInicial = strpos($sql, 'DISTINCT') + 9;
          $numExtensao = $numTamanhoComando - $numPosInicial - ($numTamanhoComando - (strpos($sql, 'FROM') - 1));
          $strAliasCampos = substr($sql, $numPosInicial, $numExtensao);
          $arrAliasCampos = explode(',',$strAliasCampos);
          $strCampos = '';
          for ($c = 0; $c < count($arrAliasCampos); $c++) {
                      //nem todos os campos possuem alias (somente maiores que 30 caracteres)
                      $posAlias = strpos($arrAliasCampos[$c], 'AS');
                      if ($posAlias!==false) {
                          $strCampos .= 'IFNULL(' . substr($arrAliasCampos[$c], 0, (strlen($arrAliasCampos[$c]) - (strlen($arrAliasCampos[$c]) - ($posAlias - 1)))) . ', 0), ';
                      }else{
                          $strCampos .= 'IFNULL(' . $arrAliasCampos[$c] . ', 0), ';
                      }
                  }
          $strCampos = substr($strCampos, 0, -2); // Retira vírgula e espaço após o último.
        }

        for($i=0;$i<count($arr);$i++){
          if (strtoupper($arr[$i])=='FROM'){
            break;
          }
        }

        if ($bolDistinct == true) {
          $sqlTotal = 'SELECT COUNT(DISTINCT ' . $strCampos . ') as total';
        } else {
          $sqlTotal = 'SELECT COUNT(*) as total';
        }

        for(;$i<count($arr);$i++){
          if (strtoupper($arr[$i])=='ORDER'){
            break;
          }
          $sqlTotal .= ' '.$arr[$i];
        }

        $rsTotal = $this->consultarSql($sqlTotal);

          $sql .= ' LIMIT '.$ini.','.$qtd;

          $rs = $this->consultarSql($sql);

          return array('totalRegistros'=>$rsTotal[0]['total'],'registrosPagina'=>$rs);

        }
      */

    public function limitarSql($sql, $qtd, $arrCamposBind = null)
    {
        //if (InfraDebug::isBolProcessar()) {
        //	InfraDebug::getInstance()->gravarInfra('[InfraMySql->limitarSql] ' . $sql);
        //}

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        $sql .= ' LIMIT 0,' . $qtd;
        return $this->consultarSql($sql);
    }

    public function executarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->executar] ' . substr($sql, 0, INFRA_TAM_MAX_LOG_SQL));
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->executar] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar um comando em uma conexão fechada.');
        }

        if ($this->getIdBanco() !== $this->getIdConexao()) {
            throw new InfraException(
                'Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.'
            );
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->consultar] 15');
        if (!mysql_select_db($this->getBanco(), $this->conexao)) {
            throw new InfraException('Não foi possível selecionar a base de dados para execução do comando.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->executar] 20');
        $resultado = mysql_query($sql, $this->conexao);
        //InfraDebug::getInstance()->gravarInfra('[InfraMySql->executar] 30');

        if ($resultado === false) {
            //InfraDebug::getInstance()->gravarInfra('[InfraMySql->executar] 35');
            throw new InfraException(mysql_error(), null, substr($sql, 0, INFRA_TAM_MAX_LOG_SQL));
        }

        //  $arrInfo = $this->getMysqlInfo($this->conexao);
        $numRows = 0;

        if ($resultado === true) {
            if ($this->conexao) {
                $affectedRows = mysql_affected_rows($this->conexao);
            } else {
                $affectedRows = mysql_affected_rows();
            }
        } else {
            if ($this->conexao) {
                $numRows = mysql_num_rows($resultado);
            }
        }

        $arrInfo = $affectedRows + $numRows;

        //if ($arrInfo[self::$MYSQL_INFO_WARNINGS]>0){
        /*if ($arrInfo==0){
          throw new InfraException('Erro executando comando.');
        }*/

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->executar] ' . $arrInfo . ' registro(s) afetado(s)');
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->executar] ' . $numSeg . ' s');
        }


        //return $arrInfo[self::$MYSQL_INFO_ROWS_MATCHED];

        return $arrInfo;
    }

    public function lerData($mySqlDate)
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

    public function gravarData($brasilDate)
    {
        if (trim($brasilDate) === '') {
            return 'NULL';
        }

        $numTamData = strlen($brasilDate);

        if (($numTamData != 10 && $numTamData != 19) || preg_match("/[^0-9 \/:]/", $brasilDate)) {
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
        $linkid ? $strInfo = mysql_info($linkid) : $strInfo = mysql_info();

        //InfraDebug::getInstance()->gravar($strInfo);

        $return = array();
        preg_match("/Records: ([0-9]*)/", $strInfo, $records);
        preg_match("/Duplicates: ([0-9]*)/", $strInfo, $dupes);
        preg_match("/Warnings: ([0-9]*)/", $strInfo, $warnings);
        preg_match("/Deleted: ([0-9]*)/", $strInfo, $deleted);
        preg_match("/Skipped: ([0-9]*)/", $strInfo, $skipped);
        preg_match("/Rows matched: ([0-9]*)/", $strInfo, $rows_matched);
        preg_match("/Changed: ([0-9]*)/", $strInfo, $changed);

        $return[self::$MYSQL_INFO_RECORDS] = $records[1];
        $return[self::$MYSQL_INFO_DUPLICATES] = $dupes[1];
        $return[self::$MYSQL_INFO_WARNINGS] = $warnings[1];
        $return[self::$MYSQL_INFO_DELETED] = $deleted[1];
        $return[self::$MYSQL_INFO_SKIPPED] = $skipped[1];
        $return[self::$MYSQL_INFO_ROWS_MATCHED] = $rows_matched[1];
        $return[self::$MYSQL_INFO_CHANGED] = $changed[1];

        return $return;
    }

    public function criarSequencialNativa($strSequencia, $numInicial)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->criarSequencialNativa]');
        }

        $this->executarSql(
            'create table ' . $strSequencia . ' (id int not null primary key AUTO_INCREMENT, campo char(1) null)'
        );
        $this->executarSql('alter table ' . $strSequencia . ' AUTO_INCREMENT = ' . $numInicial);
    }

    public function ping()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraMySql->ping] ' . $this->getIdBanco());
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentativa de ping em uma conexão fechada.');
        }
        return mysql_ping($this->conexao);
    }
}

