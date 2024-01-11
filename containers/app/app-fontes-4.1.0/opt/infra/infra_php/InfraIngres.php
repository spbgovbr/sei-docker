<?php

/**
 * @package infra_php
 *
 */
abstract class InfraIngres implements InfraIBanco
{
    private $conexao;
    private $id;
    private $numTipoInstalacao;
    private $transacao;

    public abstract function getVnode();

    public abstract function getBanco();

    public abstract function getUsuario();

    public abstract function getSenha();

    public function getOpcoes()
    {
        return array();
    }

    public function __construct()
    {
        $this->conexao = null;
        $this->id = null;
        $this->transacao = false;

        if (function_exists('ingres_free_result')) {
            $this->numTipoInstalacao = 3;
        } elseif (function_exists('ingres2_free_result')) {
            $this->numTipoInstalacao = 2;
        } else {
            $this->numTipoInstalacao = 1;
        }
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
        return __CLASS__ . '-' . $this->getVnode() . '-' . $this->getBanco() . '-' . $this->getUsuario();
    }

    public function getIdConexao()
    {
        return $this->id;
    }

    public function isBolProcessandoTransacao()
    {
        return $this->transacao;
    }

    public function getValorSequencia($sequencia)
    {
        $rs = $this->consultarSql('SELECT ' . $sequencia . '.NextVal AS sequencia');
        return $rs[0]['sequencia'];
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

    //CONTROLE DE VERSÕES DO INGRESS


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

    private function formatarSelecaoAsVarchar($tabela, $campo, $alias)
    {
        $ret = 'VARCHAR(';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }
        $ret .= $campo . ') AS ';

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
        return $this->formatarSelecaoAsVarchar($tabela, $campo, $alias);
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
        //if (trim($str)==''){
        if ($str === null || $str === '') {
            return 'NULL';
        }

        if ($this->isBolValidarISO88591() && InfraUtil::filtrarISO88591($str) != $str) {
            throw new InfraException('Detectado caracter inválido.');
        }

        return '\'' . str_replace('\'', '\'\'', $str) . '\'';
    }

    public function formatarGravacaoBol($bol)
    {
        if ($bol) {
            return 1;
        } else {
            return 0;
        }
    }

    public function formatarGravacaoNum($num)
    {
        if (trim($num) === '') {
            return 'NULL';
        }
        return $num;
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

    public function formatarGravacaoBin($bin)
    {
        if ($bin === null || $bin === '') {
            return 'NULL';
        }
        return '\'' . bin2hex($bin) . '\'';
    }

//LEITURA		
    public function converterStr($tabela, $campo)
    {
        $ret = 'VARCHAR(';
        if ($tabela !== null) {
            $ret .= $tabela . '.';
        }
        $ret .= $campo . ')';

        return $ret;
    }

    public function formatarPesquisaStr($strTabela, $strCampo, $strValor, $strOperador, $bolCaseInsensitive, $strBind)
    {
        if ($bolCaseInsensitive) {
            return 'uppercase(varchar(' . $strCampo . ')) ' . $strOperador . ' \'' . str_replace(
                    '\'',
                    '\'\'',
                    InfraString::transformarCaixaAlta(
                        $strValor
                    )
                ) . '\' ';
        } else {
            return $strCampo . ' ' . $strOperador . ' \'' . str_replace('\'', '\'\'', $strValor) . '\' ';
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
        $din = str_replace('$ ', '', $din);
        return InfraUtil::formatarDin($din);
    }

    public function formatarLeituraDbl($dbl)
    {
        return InfraUtil::formatarDbl($dbl);
    }

    public function formatarLeituraBin($bin)
    {
        return pack('H*', $bin);
    }


    //ABRE A CONEXÃO
    public function abrirConexao()
    {
        try {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra(
                    '[InfraIngres(' . $this->numTipoInstalacao . ')->abrirConexao] ' . $this->getIdBanco()
                );
            }

            if ($this->conexao != null) {
                throw new InfraException('Tentativa de abrir nova conexão sem fechar a anterior.');
            }

            //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->abrirConexao] 20');
            $string_conexao = $this->getVnode() . '::' . $this->getBanco();

            //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->abrirConexao] 30');

            if ($this->numTipoInstalacao == 1) {
                $this->conexao = ingres_connect(
                    $string_conexao,
                    $this->getUsuario(),
                    $this->getSenha(),
                    $this->getOpcoes()
                );
            } elseif ($this->numTipoInstalacao == 2) {
                $this->conexao = ingres2_connect(
                    $string_conexao,
                    $this->getUsuario(),
                    $this->getSenha(),
                    $this->getOpcoes()
                );
            } elseif ($this->numTipoInstalacao == 3) {
                $this->conexao = ingres_connect(
                    $string_conexao,
                    $this->getUsuario(),
                    $this->getSenha(),
                    $this->getOpcoes()
                );
            }

            $this->id = $this->getIdBanco();
            //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->abrirConexao] 40');

            if ($this->conexao) {
                //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->abrirConexao] 50');
                //$sql = 'set lockmode session where readlock=nolock, maxlocks=300, level=page';
                $sql = 'set lockmode session where readlock=nolock, timeout=30';

                if ($this->numTipoInstalacao == 1) {
                    $ret = ingres_query($sql, $this->conexao);
                } elseif ($this->numTipoInstalacao == 2) {
                    $ret = ingres2_query($this->conexao, $sql);
                } elseif ($this->numTipoInstalacao == 3) {
                    $ret = ingres_query($this->conexao, $sql);
                }

                if (!$ret) {
                    throw new InfraException('Erro configurando lockmode da conexão.');
                }

                //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->abrirConexao] 60');
                //$sql = 'set session with on_error=rollback transaction';
                //$ret = ingres_query($sql, $this->conexao);
                //if (! $ret) {
                //   throw new InfraException('Erro configurando on_error da conexão.');
                //}

                //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->abrirConexao] 70');
                //$sql = 'set session read write';
                //$ret = ingres_query($sql, $this->conexao);
                //if (! $ret) {
                //   throw new InfraException('Erro configurando read/write da conexão.');
                //}
            }
        } catch (Exception $e) {
            if (strpos(strtolower($e->__toString()), 'invalid internal data prevents database access') !== false) {
                throw new InfraException('Não foi possível abrir conexão com a base de dados.');
            } else {
                throw $e;
            }
        }
    }

    //FECHA A CONEXÃO
    public function fecharConexao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->fecharConexao] ' . $this->getIdConexao()
            );
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->fecharConexao] 10');
        if ($this->conexao == null) {
            throw new InfraException('Tentativa de fechar conexão que não foi aberta.');
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->fecharConexao] 20');

        if ($this->numTipoInstalacao == 1) {
            ingres_close($this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            ingres2_close($this->conexao);
        } elseif ($this->numTipoInstalacao == 3) {
            ingres_close($this->conexao);
        }

        $this->conexao = null;
        $this->id = null;
    }

    public function abrirTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->abrirTransacao] ' . $this->getIdConexao()
            );
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->abrirTransacao] 0 s');

        $this->transacao = true;

        return true;
    }

    //CONFIRMA A TRANSAÇÃO
    public function confirmarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->confirmarTransacao] ' . $this->getIdConexao()
            );
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->confirmarTransacao] 10');
        if ($this->conexao == null) {
            throw new InfraException('Tentando confirmar transação em uma conexão fechada.');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->confirmarTransacao] 20');

        if ($this->numTipoInstalacao == 1) {
            ingres_commit($this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            ingres2_commit($this->conexao);
        } elseif ($this->numTipoInstalacao == 3) {
            ingres_commit($this->conexao);
        }

        $this->transacao = false;
    }

    //CANCELA A TRANSAÇÃO
    public function cancelarTransacao()
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->cancelarTransacao] ' . $this->getIdConexao()
            );
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->cancelarTransacao] 10');
        if ($this->conexao == null) {
            throw new InfraException('Tentando desfazer transação em uma conexão fechada.');
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->cancelarTransacao] 20');

        if ($this->numTipoInstalacao == 1) {
            ingres_rollback($this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            ingres2_rollback($this->conexao);
        } elseif ($this->numTipoInstalacao == 3) {
            ingres_rollback($this->conexao);
        }

        $this->transacao = false;
    }

    //EXECUTA UMA CLÁUSULA SQL
    public function consultarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->consultarSql] ' . $sql
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->consultarSql] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar uma consulta em uma conexão fechada.');
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->consultarSql] 20');

        if ($this->numTipoInstalacao == 1) {
            $resultado = ingres_query($sql, $this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            $resultado = ingres2_query($this->conexao, $sql);

            if (ingres2_errno()) {
                throw new InfraException(
                    ingres2_errno() . ': ' . ingres2_error(),
                    null,
                    substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
                );
            }
        } elseif ($this->numTipoInstalacao == 3) {
            $resultado = ingres_query($this->conexao, $sql);

            if (ingres_errno()) {
                throw new InfraException(
                    ingres_errno() . ': ' . ingres_error(),
                    null,
                    substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
                );
            }
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->consultarSql] 30');
        if ($resultado === false) {
            throw new InfraException('Erro executando consulta.', null, $sql);
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->consultarSql] 40');
        $vetor_resultado = array();
        $i = 0;

        if ($this->numTipoInstalacao == 1) {
            while ($linha = ingres_fetch_array(INGRES_ASSOC, $this->conexao)) {
                $vetor_resultado[$i++] = $linha;
                //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->consultarSql] '.$i);
            }
        } elseif ($this->numTipoInstalacao == 2) {
            while ($linha = ingres2_fetch_array($resultado, INGRES2_ASSOC)) {
                $vetor_resultado[$i++] = $linha;
                //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->consultarSql] '.$i);
            }
        } elseif ($this->numTipoInstalacao == 3) {
            while ($linha = ingres_fetch_array($resultado, INGRES_ASSOC)) {
                $vetor_resultado[$i++] = $linha;
                //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->consultarSql] '.$i);
            }
        }

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->consultarSql] ' . InfraUtil::formatarMilhares(
                    count($vetor_resultado)
                ) . ' registro(s)'
            );
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->consultarSql] ' . $numSeg . ' s'
            );
        }

        return $vetor_resultado;
    }

    //EXECUTA UMA CLÁUSULA SQL
    public function paginarSql($sql, $ini, $qtd, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->paginarSql] ' . $sql
            );
        }

        if (!is_numeric($ini)) {
            throw new InfraException('Valor numérico inválido [' . $ini . '].');
        }

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->paginarSql] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar uma paginação em uma conexão fechada.');
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->paginarSql] 20');

        if ($this->numTipoInstalacao == 1) {
            $resultado = ingres_query($sql, $this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            $resultado = ingres2_query($this->conexao, $sql);

            if (ingres2_errno()) {
                throw new InfraException(
                    ingres2_errno() . ': ' . ingres2_error(),
                    null,
                    substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
                );
            }
        } elseif ($this->numTipoInstalacao == 3) {
            $resultado = ingres_query($this->conexao, $sql);

            if (ingres_errno()) {
                throw new InfraException(
                    ingres_errno() . ': ' . ingres_error(),
                    null,
                    substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
                );
            }
        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->paginarSql] 30');
        if ($resultado === false) {
            throw new InfraException('Erro executando paginação.');
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->paginarSql] 40');

        $vetor_resultado = array();
        $n = 0;

        if ($this->numTipoInstalacao == 1) {
            while (($registro = ingres_fetch_array(INGRES_ASSOC, $this->conexao))) {
                if (($n - $ini) == $qtd) {
                    break;
                }
                if ($n >= $ini) {
                    $vetor_resultado[] = $registro;
                }
                $n++;
            }
            $n = ingres_num_rows($this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            while (($registro = ingres2_fetch_array($resultado, INGRES2_ASSOC))) {
                if (($n - $ini) == $qtd) {
                    break;
                }
                if ($n >= $ini) {
                    $vetor_resultado[] = $registro;
                }
                $n++;
            }
            $n = ingres2_num_rows($resultado);
        } elseif ($this->numTipoInstalacao == 3) {
            while (($registro = ingres_fetch_array($resultado, INGRES_ASSOC))) {
                if (($n - $ini) == $qtd) {
                    break;
                }
                if ($n >= $ini) {
                    $vetor_resultado[] = $registro;
                }
                $n++;
            }
            $n = ingres_num_rows($resultado);
        }

        return array('totalRegistros' => $n, 'registrosPagina' => $vetor_resultado);
    }

    public function limitarSql($sql, $qtd, $arrCamposBind = null)
    {
        //if (InfraDebug::isBolProcessar()) {
        //	InfraDebug::getInstance()->gravarInfra('[InfraIngres(' . $this->numTipoInstalacao . ')->limitarSql] ');
        //}

        if (!is_numeric($qtd)) {
            throw new InfraException('Valor numérico inválido [' . $qtd . '].');
        }

        $sql = trim($sql);
        if (strtoupper(substr($sql, 0, 7)) != 'SELECT ') {
            throw new InfraException('Início da consulta não localizado.');
        }
        $sql = substr($sql, 0, 7) . 'FIRST ' . $qtd . substr($sql, 6);
        return $this->consultarSql($sql);
    }

    public function executarSql($sql, $arrCamposBind = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->executarSql] ' . substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
            );
            $numSeg = InfraUtil::verificarTempoProcessamento();
        }


        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->executarSql] 10 : '.$sql);
        if ($this->conexao == null) {
            throw new InfraException('Tentando executar um comando em uma conexão fechada.');
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->executarSql] 20');

        if ($this->numTipoInstalacao == 1) {
            $resultado = ingres_query($sql, $this->conexao);
            if ($resultado === false) {
                throw new InfraException('Erro executando comando.');
            }
            $numReg = ingres_num_rows($this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            $resultado = ingres2_query($this->conexao, $sql);

            if (ingres2_errno()) {
                throw new InfraException(
                    ingres2_errno() . ': ' . ingres2_error(),
                    null,
                    substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
                );
            }

            if ($resultado === false) {
                throw new InfraException('Erro executando comando.', null, substr($sql, 0, INFRA_TAM_MAX_LOG_SQL));
            }

            $numReg = ingres2_num_rows($resultado);
        } elseif ($this->numTipoInstalacao == 3) {
            $resultado = ingres_query($this->conexao, $sql);

            if (ingres_errno()) {
                throw new InfraException(
                    ingres_errno() . ': ' . ingres_error(),
                    null,
                    substr($sql, 0, INFRA_TAM_MAX_LOG_SQL)
                );
            }

            if ($resultado === false) {
                throw new InfraException('Erro executando comando.', null, substr($sql, 0, INFRA_TAM_MAX_LOG_SQL));
            }

            //erro no retorno da função ingres_num_rows com PHP 5.4 (05/05/2015)
            $numReg = 1;
            //$numReg = ingres_num_rows($resultado);

        }

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->executarSql] 30');

        //InfraDebug::getInstance()->gravarInfra('[InfraIngres('.$this->numTipoInstalacao.')->executarSql] 40');

        if (InfraDebug::isBolProcessar()) {
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->executarSql] ' . $numReg . ' registro(s) afetado(s)'
            );
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->executarSql] ' . $numSeg . ' s'
            );
        }

        return $numReg;
    }

    public function prepararSql($sql)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra(
                '[InfraIngres(' . $this->numTipoInstalacao . ')->prepararSql] ' . $sql
            );
        }

        if ($this->conexao == null) {
            throw new InfraException('Tentando preparar um comando em uma conexão fechada.');
        }

        if ($this->numTipoInstalacao == 1) {
            ingres_prepare($sql, $this->conexao);
        } elseif ($this->numTipoInstalacao == 2) {
            ingres2_prepare($this->conexao, $sql);
        } elseif ($this->numTipoInstalacao == 3) {
            ingres_prepare($this->conexao, $sql);
        }
    }

    public function lerData($ingresDate)
    {
        //DD-MMM-YYYY HH:MM:SS
        //01-jan-2000 15:33:44
        if ($ingresDate === null) {
            return null;
        }

        $ingresDate = trim($ingresDate);

        if ($ingresDate === '') {
            return null;
        }

        if (strlen($ingresDate) == 19) {
            //dd/mm/aaaa hh:mm:ss
            return $ingresDate;
        } elseif (strlen($ingresDate) == 17) {
            //dd/mm/aa hh:mm:ss
            $ano = substr($ingresDate, 6, 2);
            if ($ano < 10) {
                $ano = '20' . substr($ingresDate, 6, 2);
            } else {
                $ano = '19' . substr($ingresDate, 6, 2);
            }
            return substr($ingresDate, 0, 6) . $ano . substr($ingresDate, 8);
        }

        $mes = substr($ingresDate, 3, 3);

        switch ($mes) {
            case 'jan':
                $mes = '01';
                break;
            case 'feb':
                $mes = '02';
                break;
            case 'mar':
                $mes = '03';
                break;
            case 'apr':
                $mes = '04';
                break;
            case 'may':
                $mes = '05';
                break;
            case 'jun':
                $mes = '06';
                break;
            case 'jul':
                $mes = '07';
                break;
            case 'aug':
                $mes = '08';
                break;
            case 'sep':
                $mes = '09';
                break;
            case 'oct':
                $mes = '10';
                break;
            case 'nov':
                $mes = '11';
                break;
            case 'dec':
                $mes = '12';
                break;
        }
        return substr($ingresDate, 0, 2) . '/' . $mes . '/' . substr($ingresDate, 7);
    }


    public function gravarData($brasilDate)
    {
        if (trim($brasilDate) === '') {
            return 'NULL';
        }

        //if (strlen($brasilDate)==10){
        //  $brasilDate .= ' 00:00:00';
        //}

        $numTamData = strlen($brasilDate);

        if (($numTamData != 10 && $numTamData != 19) || preg_match("/[^0-9 \/:]/", $brasilDate)) {
            throw new InfraException('Data inválida [' . $brasilDate . '].');
        }

        $mes = substr($brasilDate, 3, 2);

        switch ($mes) {
            case '01':
                $mes = 'jan';
                break;
            case '02':
                $mes = 'feb';
                break;
            case '03':
                $mes = 'mar';
                break;
            case '04':
                $mes = 'apr';
                break;
            case '05':
                $mes = 'may';
                break;
            case '06':
                $mes = 'jun';
                break;
            case '07':
                $mes = 'jul';
                break;
            case '08':
                $mes = 'aug';
                break;
            case '09':
                $mes = 'sep';
                break;
            case '10':
                $mes = 'oct';
                break;
            case '11':
                $mes = 'nov';
                break;
            case '12':
                $mes = 'dec';
                break;
        }
        return '\'' . substr($brasilDate, 0, 2) . '-' . $mes . '-' . substr($brasilDate, 6) . '\'';
    }

    public function criarSequencialNativa($strSequencia, $numInicial)
    {
        //InfraDebug::getInstance()->gravarInfra('[InfraIngres->criarSequencialNativa]');
    }
}

