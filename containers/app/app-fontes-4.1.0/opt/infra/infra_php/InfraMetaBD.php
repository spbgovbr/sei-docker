<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 19/03/2015 - criado por MGA
 *
 * @package infra_php
 */

class InfraMetaBD
{

    private $objInfraIBanco;
    private $bolValidarIdentificador;

    public function __construct($objInfraIBanco)
    {
        $this->objInfraIBanco = $objInfraIBanco;
        $this->bolValidarIdentificador = false;
    }

    public function getObjInfraIBanco()
    {
        return $this->objInfraIBanco;
    }

    public function setBolValidarIdentificador($bolValidarIdentificador)
    {
        $this->bolValidarIdentificador = $bolValidarIdentificador;
    }

    private function validarIdentificador($strIdentificador)
    {
        $strIdentificador = trim($strIdentificador);
        if ($this->bolValidarIdentificador && strlen($strIdentificador) > 30) {
            throw new InfraException(
                'Identificador [' . $strIdentificador . '] possui tamanho superior a 30 caracteres.'
            );
        }
        return $strIdentificador;
    }

    private function normalizarOpcao($strOpcao)
    {
        $strOpcao = strtolower(trim($strOpcao));
        while (strpos($strOpcao, '  ') !== false) {
            $strOpcao = str_replace('  ', ' ', $strOpcao);
        }
        return $strOpcao;
    }

    public function obterTabelas($strNomeTabela = null, $arrTabelasIgnorar = null)
    {
        $ret = '';
        if ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql = 'select lower(name) as table_name from sys.tables where lower(name) not in (\'dtproperties\')';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= $this->formatarTabelasIgnorar('lower(name)', $arrTabelasIgnorar);

            $sql .= ' order by name asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraMySql) {
            $sql = 'select lower(table_name) as table_name from information_schema.tables where lower(table_type) = \'base table\' AND lower(table_schema)=\'' . strtolower(
                    $this->objInfraIBanco->getBanco()
                ) . '\'';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= $this->formatarTabelasIgnorar('lower(table_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name asc;';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql = 'select lower(table_name) as table_name from all_tables where lower(owner)=\'' . strtolower(
                    $this->objInfraIBanco->getUsuario()
                ) . '\'';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= $this->formatarTabelasIgnorar('lower(table_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql = 'SELECT lower(table_name) as table_name FROM information_schema.tables 
              WHERE lower(table_type) = \'base table\' 
              and table_schema NOT IN (\'pg_catalog\', \'information_schema\') 
              and lower(table_catalog) = \'' . strtolower($this->objInfraIBanco->getBanco()) . '\'';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= $this->formatarTabelasIgnorar('lower(table_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        }

        return $ret;
    }

    public function obterColunasTabela($strNomeTabela, $strNomeColuna = null)
    {
        $ret = '';

        if ($this->objInfraIBanco instanceof InfraMySql || $this->objInfraIBanco instanceof InfraSqlServer || $this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql = 'SELECT lower(column_name) as column_name, is_nullable as is_nullable, lower(data_type) as data_type, character_maximum_length as character_maximum_length , numeric_precision as numeric_precision, numeric_scale as numeric_scale
          FROM INFORMATION_SCHEMA.COLUMNS
          where lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';

            if ($this->objInfraIBanco instanceof InfraMySql) {
                $sql .= ' AND lower(table_schema)=\'' . strtolower($this->objInfraIBanco->getBanco()) . '\'';
            }

            if ($strNomeColuna != null) {
                $sql .= ' and lower(column_name)=\'' . strtolower($strNomeColuna) . '\'';
            }

            $sql .= ' order by ordinal_position asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql = 'SELECT lower(column_name) as column_name, nullable as is_nullable, lower(data_type) as data_type, char_length as character_maximum_length, data_precision as numeric_precision, data_scale as numeric_scale
          FROM all_tab_columns
          WHERE lower(table_name)=\'' . strtolower($strNomeTabela) . '\' AND lower(owner)=\'' . strtolower(
                    $this->objInfraIBanco->getUsuario()
                ) . '\'';


            if ($strNomeColuna != null) {
                $sql .= ' AND lower(column_name)=\'' . strtolower($strNomeColuna) . '\'';
            }

            //$sql .= ' order by ordinal_position asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        }

        $numRegistros = count($ret);
        for ($i = 0; $i < $numRegistros; $i++) {
            if ($ret[$i]['is_nullable'] == 'Y' || $ret[$i]['is_nullable'] == 'YES') {
                $ret[$i]['is_nullable'] = 'YES';
            } else {
                $ret[$i]['is_nullable'] = 'NO';
            }
        }

        return $ret;
    }

    public function obterRegistrosTabela($strNomeTabela)
    {
        $sql = 'select count(*) as total from ';

        if ($this->objInfraIBanco instanceof InfraOracle) {
            $sql .= $this->objInfraIBanco->getUsuario() . '.';
        }

        $sql .= $strNomeTabela;

        return $this->objInfraIBanco->consultarSql($sql);
    }

    public function obterConstraints($strNomeTabela = null, $arrTabelasIgnorar = null)
    {
        $ret = '';
        if ($this->objInfraIBanco instanceof InfraMySql || $this->objInfraIBanco instanceof InfraSqlServer) {
            $sql = 'select lower(constraint_name) as constraint_name, lower(constraint_type) as constraint_type, lower(table_name) as table_name from INFORMATION_SCHEMA.TABLE_CONSTRAINTS where lower(constraint_type) <> \'unique\'';

            if ($this->objInfraIBanco instanceof InfraMySql) {
                $sql .= ' AND lower(table_schema)=\'' . strtolower($this->objInfraIBanco->getBanco()) . '\'';
            }

            if ($strNomeTabela != null) {
                $sql .= ' and lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= $this->formatarTabelasIgnorar('lower(table_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name, constraint_name';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql = 'select lower(constraint_name) as constraint_name, decode(constraint_type, \'P\', \'primary key\', \'R\',\'foreign key\') as constraint_type, lower(table_name) as table_name from all_constraints where lower(constraint_type) in (\'p\',\'r\')
              AND lower(owner)=\'' . strtolower($this->objInfraIBanco->getUsuario()) . '\'';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= $this->formatarTabelasIgnorar('lower(table_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name, constraint_name';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql = 'select lower(constraint_name) as constraint_name, lower(constraint_type) as constraint_type, lower(table_name) as table_name 
              from INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
              where lower(constraint_type) in (\'primary key\', \'foreign key\')
              and lower(table_catalog)=\'' . strtolower($this->objInfraIBanco->getBanco()) . '\'';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= $this->formatarTabelasIgnorar('lower(table_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name, constraint_name';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        }

        return $ret;
    }

    public function obterColunasConstraints($strNomeTabela = null)
    {
        $ret = '';
        if ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql = 'SELECT DISTINCT lower(ccu.table_name) as table_name, lower(ccu.constraint_name) as constraint_name, lower(ccu.column_name) as column_name, lower(object_name(sfc.referenced_object_id)) as referenced_table_name, lower(ac2.name) as referenced_column_name
              FROM information_schema.constraint_column_usage ccu
              LEFT JOIN (sys.foreign_key_columns sfc
              inner join sys.all_columns ac1 on (ac1.object_id=sfc.parent_object_id and ac1.column_id=sfc.parent_column_id)
              inner join sys.all_columns ac2 on (ac2.object_id=sfc.referenced_object_id and ac2.column_id=sfc.referenced_column_id)) on object_name(sfc.constraint_object_id)=ccu.constraint_name and ac1.name=ccu.column_name
              inner join information_schema.table_constraints tc on tc.table_schema=ccu.table_schema and tc.table_name=ccu.table_name and tc.constraint_name=ccu.constraint_name and lower(tc.constraint_type) <> \'unique\'';

            if ($strNomeTabela != null) {
                $sql .= ' where lower(ccu.table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $rs = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraMySql) {
            $sql = 'select DISTINCT lower(table_name) as table_name, lower(constraint_name) as constraint_name, lower(column_name) as column_name, lower(referenced_table_name) as referenced_table_name, lower(referenced_column_name) as referenced_column_name
           from information_schema.key_column_usage
           where lower(constraint_schema) =\'' . strtolower($this->objInfraIBanco->getBanco()) . '\'
           AND lower(table_schema)=\'' . strtolower($this->objInfraIBanco->getBanco()) . '\'';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }


            $rs = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql = 'SELECT lower(alc.table_name) as table_name, lower(alc.constraint_name) as constraint_name, lower(cols.column_name) as column_name, lower(r_alc.table_name) as referenced_table_name, lower(r_cols.column_name) as referenced_column_name
              FROM all_cons_columns cols
              INNER JOIN all_constraints alc ON alc.constraint_name = cols.constraint_name AND alc.owner = cols.owner
              LEFT JOIN all_constraints r_alc ON alc.r_constraint_name = r_alc.constraint_name AND alc.r_owner = r_alc.owner
              LEFT JOIN all_cons_columns r_cols ON r_alc.constraint_name = r_cols.constraint_name AND r_alc.owner = r_cols.owner AND cols.position = r_cols.position
             WHERE lower(alc.owner) =\'' . strtolower($this->objInfraIBanco->getUsuario()) . '\'
             AND alc.constraint_type in (\'P\',\'R\')';

            if ($strNomeTabela != null) {
                $sql .= ' AND lower(alc.table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $rs = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql = 'SELECT lower(tc.table_name) as table_name, lower(tc.constraint_name) as constraint_name, lower(tc.constraint_type) as constraint_type, string_agg(distinct kcu.column_name, \',\') AS column_names, ccu.table_name AS referenced_table_name, string_agg(distinct ccu.column_name, \',\') AS foreign_column_names   
              FROM information_schema.table_constraints AS tc 
              INNER JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
              INNER JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
              WHERE lower(tc.constraint_catalog) = \'' . strtolower(
                    $this->objInfraIBanco->getBanco()
                ) . '\' and lower(tc.constraint_type) in (\'primary key\',\'foreign key\')';

            if ($strNomeTabela != null) {
                $sql .= ' and lower(tc.table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            $sql .= ' GROUP BY tc.table_name, tc.constraint_name, tc.constraint_type, ccu.table_name';

            $rsTemp = $this->objInfraIBanco->consultarSql($sql);
            $rs = array();
            foreach ($rsTemp as $item) {
                $arrColunas = explode(',', $item['column_names']);
                $arrColunasOrigem = explode(',', $item['foreign_column_names']);
                $numColunas = count($arrColunas);
                for ($i = 0; $i < $numColunas; $i++) {
                    if ($item['constraint_type'] == 'primary key') {
                        $strTabelaOrigem = null;
                        $strColunaOrigem = null;
                    } else {
                        $strTabelaOrigem = $item['referenced_table_name'];
                        $strColunaOrigem = $arrColunasOrigem[$i];
                    }

                    $rs[] = array(
                        'table_name' => $item['table_name'],
                        'constraint_name' => $item['constraint_name'],
                        'column_name' => $arrColunas[$i],
                        'referenced_table_name' => $strTabelaOrigem,
                        'referenced_column_name' => $strColunaOrigem
                    );
                }
            }
        }

        $ret = array();
        foreach ($rs as $arrConstraint) {
            if (!isset($ret[$arrConstraint['table_name']])) {
                $ret[$arrConstraint['table_name']] = array();
            }

            if (!isset($ret[$arrConstraint['table_name']][$arrConstraint['constraint_name']])) {
                $ret[$arrConstraint['table_name']][$arrConstraint['constraint_name']] = array();
            }

            $ret[$arrConstraint['table_name']][$arrConstraint['constraint_name']][$arrConstraint['column_name']] = array(
                $arrConstraint['referenced_table_name'],
                $arrConstraint['referenced_column_name']
            );
        }

        return $ret;
    }

    public function obterIndices($arrTabelasIgnorar = null, $strNomeTabela = null, $varSomenteUnique = null)
    {
        $ret = null;

        if ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql = 'SELECT distinct lower(T.name) AS table_name, lower(I.name) AS index_name, lower(AC.name) AS column_name
              FROM sys.tables AS T 
              INNER JOIN sys.indexes I ON T.object_id = I.object_id  AND I.is_primary_key = 0
              INNER JOIN sys.index_columns IC ON I.object_id = IC.object_id and I.index_id = IC.index_id
              INNER JOIN sys.all_columns AC ON T.object_id = AC.object_id AND IC.column_id = AC.column_id
              WHERE T.is_ms_shipped = 0 AND I.type_desc <> \'HEAP\'';

            $sql .= $this->formatarTabelasIgnorar('lower(T.name)', $arrTabelasIgnorar);

            if ($strNomeTabela != null) {
                $sql .= ' AND lower(T.name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            if (is_bool($varSomenteUnique)) {
                if ($varSomenteUnique) {
                    $sql .= ' AND I.is_unique=1';
                } else {
                    $sql .= ' AND I.is_unique=0';
                }
            }

            $sql .= ' ORDER BY table_name, index_name, column_name';

            $rsIndices = $this->objInfraIBanco->consultarSql($sql);

            $ret = array();
            $numIndices = count($rsIndices);
            for ($i = 0; $i < $numIndices; $i++) {
                $ret[$rsIndices[$i]['table_name']][$rsIndices[$i]['index_name']][] = $rsIndices[$i]['column_name'];
            }
        } elseif ($this->objInfraIBanco instanceof InfraMySql) {
            $rsTabelas = $this->obterTabelas($strNomeTabela, $arrTabelasIgnorar);

            $arrIndices = array();
            foreach ($rsTabelas as $tabela) {
                $sql = 'show indexes from ' . $tabela['table_name'];

                if (is_bool($varSomenteUnique)) {
                    if ($varSomenteUnique) {
                        $sql .= ' where non_unique=0';
                    } else {
                        $sql .= ' where non_unique=1';
                    }
                }

                $rsIndices = $this->objInfraIBanco->consultarSql($sql);

                $arrIndices = array_merge($arrIndices, $rsIndices);
            }

            $ret = array();
            for ($i = 0; $i < count($arrIndices); $i++) {
                if (strtolower($arrIndices[$i]['Key_name']) != 'primary') {
                    $ret[strtolower($arrIndices[$i]['Table'])][strtolower($arrIndices[$i]['Key_name'])][] = strtolower(
                        $arrIndices[$i]['Column_name']
                    );
                }
            }
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql = 'select lower(ai.table_name) as table_name, lower(ai.index_name) as index_name, lower(aic.column_name) as column_name' .
                ' from all_indexes ai, all_ind_columns aic' .
                ' where ai.table_owner=aic.table_owner and ai.table_name=aic.table_name and ai.index_name=aic.index_name' .
                ' and lower(ai.index_name) not like \'pk_%\'' .
                ' and lower(ai.owner)=\'' . strtolower($this->objInfraIBanco->getUsuario()) . '\'';

            $sql .= $this->formatarTabelasIgnorar('lower(ai.table_name)', $arrTabelasIgnorar);

            if ($strNomeTabela != null) {
                $sql .= ' AND lower(ai.table_name)=\'' . strtolower($strNomeTabela) . '\'';
            }

            if (is_bool($varSomenteUnique)) {
                if ($varSomenteUnique) {
                    $sql .= ' AND ai.uniqueness=\'UNIQUE\'';
                } else {
                    $sql .= ' AND ai.uniqueness=\'NONUNIQUE\'';
                }
            }

            $sql .= ' ORDER BY table_name, index_name, column_name';

            $rsIndices = $this->objInfraIBanco->consultarSql($sql);

            $ret = array();
            $numIndices = count($rsIndices);
            for ($i = 0; $i < $numIndices; $i++) {
                $ret[$rsIndices[$i]['table_name']][$rsIndices[$i]['index_name']][] = $rsIndices[$i]['column_name'];
            }
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql = 'select lower(t.relname) as table_name, lower(i.relname) as index_name, lower(a.attname) as column_name
              from pg_class t, pg_class i, pg_index ix, pg_attribute a
              where t.oid = ix.indrelid and i.oid = ix.indexrelid and a.attrelid = t.oid
              and a.attnum = ANY(ix.indkey) and t.relkind = \'r\' and t.relname not like \'pg_%\' and ix.indisprimary = false';

            $sql .= $this->formatarTabelasIgnorar('lower(t.relname)', $arrTabelasIgnorar);

            if ($strNomeTabela != null) {
                $sql .= ' AND lower(t.relname)=\'' . strtolower($strNomeTabela) . '\'';
            }

            if (is_bool($varSomenteUnique)) {
                if ($varSomenteUnique) {
                    $sql .= ' AND ix.indisunique=true';
                } else {
                    $sql .= ' AND ix.indisunique=false';
                }
            }

            $sql .= ' order by t.relname, i.relname, a.attname';

            $rsIndices = $this->objInfraIBanco->consultarSql($sql);

            $ret = array();
            $numIndices = count($rsIndices);
            for ($i = 0; $i < $numIndices; $i++) {
                $ret[$rsIndices[$i]['table_name']][$rsIndices[$i]['index_name']][] = $rsIndices[$i]['column_name'];
            }
        }
        return $ret;
    }

    public function obterSequencias($arrTabelasIgnorar = null)
    {
        $ret = '';
        if ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql = 'select lower(name) as table_name, ident_current(name) as current_value
              from sys.tables
              where lower(name) like \'seq_%\'';

            $sql .= $this->formatarTabelasIgnorar('lower(name)', $arrTabelasIgnorar);

            $sql .= ' order by name asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraMySql) {
            $sql = 'select lower(table_name) as table_name, auto_increment as current_value
              from information_schema.tables
              where lower(table_type) = \'base table\'
              and lower(table_schema)=\'' . strtolower($this->objInfraIBanco->getBanco()) . '\'
              and lower(table_name) like \'seq_%\'';

            $sql .= $this->formatarTabelasIgnorar('lower(table_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql = 'select lower(sequence_name) as table_name, last_number as current_value
              from all_sequences
              where sequence_owner = \'' . $this->objInfraIBanco->getUsuario() . '\'
              and lower(sequence_name) like \'seq_%\'';

            $sql .= $this->formatarTabelasIgnorar('lower(sequence_name)', $arrTabelasIgnorar);

            $sql .= ' order by table_name asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql = 'SELECT lower(sequencename) as table_name, COALESCE(last_value, start_value) as current_value FROM pg_sequences';

            $sql .= str_replace(
                ' and ',
                ' where ',
                $this->formatarTabelasIgnorar('lower(sequencename)', $arrTabelasIgnorar)
            );

            $sql .= ' order by sequencename asc';

            $ret = $this->objInfraIBanco->consultarSql($sql);
        }

        return $ret;
    }

    public function obterMaxIdTabelaSequencia($strNomeSequencia)
    {
        $ret = null;

        try {
            if ($this->objInfraIBanco instanceof InfraSqlServer) {
                $ret = $this->objInfraIBanco->consultarSql(
                    'SELECT IDENT_CURRENT(\'' . $strNomeSequencia . '\') as ultimo'
                );
                $ret = $ret[0]['ultimo'] + 1;
            } elseif ($this->objInfraIBanco instanceof InfraMySql) {
                $ret = $this->objInfraIBanco->consultarSql('SHOW TABLE STATUS LIKE \'' . $strNomeSequencia . '\'');
                $ret = $ret[0]['Auto_increment'];
            } elseif ($this->objInfraIBanco instanceof InfraOracle) {
                $ret = $this->objInfraIBanco->consultarSql(
                    'SELECT last_number FROM all_sequences WHERE lower(sequence_owner) = \'' . strtolower(
                        $this->objInfraIBanco->getUsuario()
                    ) . '\' AND lower(sequence_name) = \'' . $strNomeSequencia . '\''
                );
                $ret = $ret[0]['last_number'];
            } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
                $ret = $this->objInfraIBanco->consultarSql(
                    'SELECT COALESCE(last_value, start_value) as ultimo FROM pg_sequences WHERE lower(sequencename) = \'' . $strNomeSequencia . '\''
                );
                $ret = $ret[0]['ultimo'];
            }
        } catch (Exception $e) {
            $ret = '[erro]';
        }

        return $ret;
    }

    public function obterMaxIdTabela($strNomeTabela)
    {
        $ret = null;

        try {
            $sql = 'select max(id_' . $strNomeTabela . ') as maximo from ';

            if ($this->objInfraIBanco instanceof InfraOracle) {
                $sql .= $this->objInfraIBanco->getUsuario() . '.';
            }

            $sql .= $strNomeTabela;

            $ret = $this->objInfraIBanco->consultarSql($sql);
        } catch (Exception $e) {
            //throw new InfraException('Erro obtendo max(id) da tabela: '.$strNomeTabela,$e);
            return array(0 => array('maximo' => 'erro'));
        }

        return $ret;
    }

    private function formatarTabelasIgnorar($strCampo, $arrTabelasIgnorar)
    {
        $ret = '';
        if ($arrTabelasIgnorar != null) {
            foreach ($arrTabelasIgnorar as $strTabela) {
                $strTabela = trim($strTabela);

                if ($strTabela != '') {
                    if ($ret != '') {
                        $ret .= ',';
                    }
                    $ret .= '\'' . $strTabela . '\',\'seq_' . $strTabela . '\'';
                }
            }

            if ($ret != '') {
                $ret = ' and ' . $strCampo . ' not in (' . $ret . ')';
            }
        }
        return $ret;
    }

    public function tipoNumero()
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'integer';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'integer';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'number(*,0)';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'integer';
        }
    }

    public function tipoNumeroGrande()
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'bigint';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'bigint';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'number(*,0)';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'bigint';
        }
    }

    public function tipoNumeroDecimal($numDigitosTotal, $numDigitosDecimais)
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'numeric(' . $numDigitosTotal . ',' . $numDigitosDecimais . ')';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'numeric(' . $numDigitosTotal . ',' . $numDigitosDecimais . ')';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'number(' . $numDigitosTotal . ',' . $numDigitosDecimais . ')';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'numeric(' . $numDigitosTotal . ',' . $numDigitosDecimais . ')';
        }
    }

    public function tipoTextoFixo($numTamanho)
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'char(' . $numTamanho . ')';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'char(' . $numTamanho . ')';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'char(' . $numTamanho . ' byte)';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'char(' . $numTamanho . ')';
        }
    }

    public function tipoTextoVariavel($numTamanho)
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'varchar(' . $numTamanho . ')';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'varchar(' . $numTamanho . ')';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'varchar2(' . $numTamanho . ' byte)';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'varchar(' . $numTamanho . ')';
        }
    }

    public function tipoTextoGrande()
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'longtext';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'varchar(max)';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'clob';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'text';
        }
    }

    public function novaLinha()
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return '\n';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return '\' + char(10) + \'';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return '\' || CHR(10) || \'';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return '\' || chr(10) || \'';
        }
    }

    public function tipoDataHora()
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'datetime';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'datetime';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'date';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'timestamp';
        }
    }

    public function funcSubstring()
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            return 'substring';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            return 'substring';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            return 'substr';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            return 'substr';
        }
    }

    public function alterarColuna($strTabela, $strColuna, $strTipo, $strNull)
    {
        $sql = 'alter table ' . $strTabela . ' ';

        if ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql .= ' alter column ' . $this->validarIdentificador($strColuna) . ' type ' . $strTipo . ',';
            $sql .= ' alter column ' . $this->validarIdentificador($strColuna) . ' ' . $this->getOptionNull(
                    $strTabela,
                    $strColuna,
                    $strNull
                );
        } else {
            if ($this->objInfraIBanco instanceof InfraMySql) {
                $sql .= 'modify column';
            } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
                $sql .= 'alter column';
            } elseif ($this->objInfraIBanco instanceof InfraOracle) {
                $sql .= 'modify';

                if ($strTipo == 'clob') {
                    $rs = $this->objInfraIBanco->consultarSql(
                        'SELECT lower(data_type) as data_type FROM all_tab_columns WHERE lower(table_name)=\'' . strtolower(
                            $strTabela
                        ) . '\' AND lower(owner)=\'' . strtolower(
                            $this->objInfraIBanco->getUsuario()
                        ) . '\' and lower(column_name)=\'' . strtolower($strColuna) . '\''
                    );
                    if ($rs[0]['data_type'] == 'varchar' || $rs[0]['data_type'] == 'varchar2') {
                        $this->objInfraIBanco->executarSql(
                            'alter table ' . $strTabela . ' rename column ' . $strColuna . ' to x' . $strColuna
                        );
                        $this->objInfraIBanco->executarSql(
                            'alter table ' . $strTabela . ' add ' . $strColuna . ' clob null'
                        );
                        $this->objInfraIBanco->executarSql(
                            'update ' . $strTabela . ' set ' . $strColuna . ' = x' . $strColuna
                        );
                        $this->objInfraIBanco->executarSql('alter table ' . $strTabela . ' drop column x' . $strColuna);

                        if ($this->normalizarOpcao($strNull) == 'not null') {
                            $this->objInfraIBanco->executarSql(
                                'alter table ' . $strTabela . ' modify ' . $strColuna . ' not null'
                            );
                        }

                        return;
                    }
                }
            }
            $sql .= ' ' . $this->validarIdentificador($strColuna) . ' ' . $strTipo . ' ' . $this->getOptionNull(
                    $strTabela,
                    $strColuna,
                    $strNull
                );
        }

        return $this->objInfraIBanco->executarSql($sql);
    }

    public function adicionarColuna($strTabela, $strColuna, $strTipo, $strNull)
    {
        $sql = 'alter table ' . $strTabela . ' ';
        if ($this->objInfraIBanco instanceof InfraMySql) {
            $sql .= 'add column';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql .= 'add';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql .= 'add';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql .= 'add column';
        }
        $sql .= ' ' . $this->validarIdentificador($strColuna) . ' ' . $strTipo . ' ' . $strNull;
        return $this->objInfraIBanco->executarSql($sql);
    }

    public function excluirColuna($strTabela, $strColuna)
    {
        $sql = 'alter table ' . $strTabela . ' drop column ' . $strColuna;
        return $this->objInfraIBanco->executarSql($sql);
    }

    public function adicionarChavePrimaria($strTabela, $strNomePK, $arrCampos)
    {
        $this->objInfraIBanco->executarSql(
            'alter table ' . $strTabela . ' add constraint ' . $this->validarIdentificador(
                $strNomePK
            ) . ' primary key (' . implode(',', $arrCampos) . ')'
        );
    }

    public function adicionarChaveEstrangeira(
        $strNomeFK,
        $strTabela,
        $arrCampos,
        $strTabelaOrigem,
        $arrCamposOrigem,
        $bolCriarIndice = true
    ) {
        if ($bolCriarIndice) {
            $this->criarIndice($strTabela, $strNomeFK, $arrCampos);
        }

        $this->objInfraIBanco->executarSql(
            'alter table ' . $strTabela . ' add constraint ' . $this->validarIdentificador(
                $strNomeFK
            ) . ' foreign key (' . implode(',', $arrCampos) . ') references ' . $strTabelaOrigem . ' (' . implode(
                ',',
                $arrCamposOrigem
            ) . ')'
        );
    }

    public function excluirChavePrimaria($strTabela, $strPk)
    {
        $sql = 'alter table ' . $strTabela . ' ';
        if ($this->objInfraIBanco instanceof InfraMySql) {
            $sql .= 'drop primary key';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql .= 'drop constraint ' . $strPk;
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql .= 'drop constraint ' . $strPk;
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql .= 'drop constraint ' . $strPk;
        }
        $this->objInfraIBanco->executarSql($sql);
    }

    public function excluirChaveEstrangeira($strTabela, $strFk)
    {
        $sql = 'alter table ' . $strTabela . ' ';
        if ($this->objInfraIBanco instanceof InfraMySql) {
            $sql .= 'drop foreign key';
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql .= 'drop constraint';
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql .= 'drop constraint';
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql .= 'drop constraint';
        }
        $sql .= ' ' . $strFk;
        $this->objInfraIBanco->executarSql($sql);
    }

    private function prepararColunasComparacao($arr)
    {
        $arrColunas = $arr;

        $numColunas = count($arrColunas);

        for ($i = 0; $i < $numColunas; $i++) {
            $arrColunas[$i] = strtolower($arrColunas[$i]);
        }

        sort($arrColunas);

        return $arrColunas;
    }

    public function criarIndice($strTabela, $strIndex, $arrColunas, $bolUnique = false)
    {
        $bolDebugInfra = InfraDebug::getInstance()->isBolDebugInfra();

        if ($bolDebugInfra) {
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }

        $arrIndices = $this->obterIndices(null, $strTabela);

        if ($bolDebugInfra) {
            InfraDebug::getInstance()->setBolDebugInfra(true);
        }

        /*
        if (!isset($arrIndices[$strTabela])) {
          InfraDebug::getInstance()->gravarInfra('Nenhum índice encontrado.');
        }else{
          foreach($arrIndices[$strTabela] as $strIndiceTabela => $arrColunasIndiceTabela){
            InfraDebug::getInstance()->gravarInfra($strIndiceTabela.': '.implode(', ',$arrColunasIndiceTabela));
          }
        }
        */

        $bolEncontrou = false;

        if (isset($arrIndices[$strTabela])) {
            $arrColunasIndiceNovo = $this->prepararColunasComparacao($arrColunas);

            //verifica com o mesmo nome
            if (isset($arrIndices[$strTabela][$strIndex])) {
                $arrColunasIndiceBanco = $this->prepararColunasComparacao($arrIndices[$strTabela][$strIndex]);

                if ($arrColunasIndiceNovo == $arrColunasIndiceBanco) {
                    $bolEncontrou = true;
                } else {
                    $this->excluirIndice($strTabela, $strIndex);
                }

                unset($arrIndices[$strTabela][$strIndex]);
            }

            //verifica com outro nome
            if (!$bolEncontrou) {
                $arrIndicesBanco = array_keys($arrIndices[$strTabela]);

                foreach ($arrIndicesBanco as $strNomeIndiceBanco) {
                    $arrColunasIndiceBanco = $this->prepararColunasComparacao(
                        $arrIndices[$strTabela][$strNomeIndiceBanco]
                    );

                    if ($arrColunasIndiceNovo == $arrColunasIndiceBanco) {
                        $this->renomearIndice($strTabela, $strNomeIndiceBanco, $strIndex, $arrColunasIndiceBanco);

                        $bolEncontrou = true;

                        unset($arrIndices[$strTabela][$strNomeIndiceBanco]);

                        break;
                    }
                }
            }

            //exclui redundantes
            if ($bolEncontrou) {
                $arrIndicesBanco = array_keys($arrIndices[$strTabela]);

                foreach ($arrIndicesBanco as $strNomeIndiceBanco) {
                    $arrColunasIndiceBanco = $this->prepararColunasComparacao(
                        $arrIndices[$strTabela][$strNomeIndiceBanco]
                    );

                    if ($arrColunasIndiceNovo == $arrColunasIndiceBanco) {
                        $this->excluirIndice($strTabela, $strNomeIndiceBanco);
                    }
                }
            }
        }

        if (!$bolEncontrou) {

            $sql = 'create ' . ($bolUnique ? 'unique' : '') . ' index ' . $this->validarIdentificador($strIndex) . ' on ' . $strTabela . ' (' . implode(',', $arrColunas) . ')';

            if ($bolUnique && $this->objInfraIBanco instanceof InfraSqlServer){

                $sql .= ' where ';
                $strSeparador = '';

                foreach($arrColunas as $strColuna){
                    $sql .= $strSeparador.$strColuna. ' is not null';
                    $strSeparador = ' and ';
                }
            }

            $this->objInfraIBanco->executarSql($sql);
        }
    }

    public function excluirIndice($strTabela, $strIndex)
    {
        $sql = 'drop index ';
        if ($this->objInfraIBanco instanceof InfraMySql) {
            $sql .= $strIndex . ' on ' . $strTabela;
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            $sql .= $strIndex . ' on ' . $strTabela;
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $sql .= $strIndex;
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $sql .= $strIndex;
        }
        $this->objInfraIBanco->executarSql($sql);
    }

    public function renomearIndice($strTabela, $strIndexAntigo, $strIndexNovo, $arrColunas)
    {
        if ($this->objInfraIBanco instanceof InfraMySql) {
            $rs = $this->objInfraIBanco->consultarSql('select version()');
            $strVersao = $rs[0]['version()'];
            if (in_array(substr($strVersao, 0, 3), array('5.0', '5.1', '5.2', '5.3', '5.4', '5.5', '5.6')) || strpos(
                    $strVersao,
                    'MariaDB'
                ) !== false) {
                $this->objInfraIBanco->executarSql('SET FOREIGN_KEY_CHECKS = 0');
                $this->objInfraIBanco->executarSql(
                    'alter table ' . $strTabela . ' drop index ' . $strIndexAntigo . ', add index ' . $strIndexNovo . ' (' . implode(
                        ',',
                        $arrColunas
                    ) . ')'
                );
                $this->objInfraIBanco->executarSql('SET FOREIGN_KEY_CHECKS = 1');
            } else {
                $this->objInfraIBanco->executarSql(
                    'alter table ' . $strTabela . ' rename index ' . $strIndexAntigo . ' to ' . $strIndexNovo
                );
            }
        } elseif ($this->objInfraIBanco instanceof InfraSqlServer) {
            try {
                $this->objInfraIBanco->executarSql(
                    'EXEC sp_rename \'' . $strTabela . '.' . $strIndexAntigo . '\', \'' . $strIndexNovo . '\', \'INDEX\''
                );
            } catch (Exception $e) {
                if (strpos(
                        $e->__toString(),
                        'Caution: Changing any part of an object name could break scripts and stored procedures.'
                    ) === false) {
                    throw $e;
                }
            }
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $this->objInfraIBanco->executarSql('alter index ' . $strIndexAntigo . ' rename to ' . $strIndexNovo);
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            $this->objInfraIBanco->executarSql('alter index ' . $strIndexAntigo . ' rename to ' . $strIndexNovo);
        }
    }

    private function getOptionNull($strTabela, $strColuna, $strOption)
    {
        $ret = '';
        if ($this->objInfraIBanco instanceof InfraSqlServer || $this->objInfraIBanco instanceof InfraMySql) {
            $ret = $strOption;
        } elseif ($this->objInfraIBanco instanceof InfraOracle) {
            $strOption = $this->normalizarOpcao($strOption);
            $rs = $this->objInfraIBanco->consultarSql(
                'SELECT nullable  FROM all_tab_columns WHERE lower(table_name)=\'' . strtolower(
                    $strTabela
                ) . '\' AND lower(owner)=\'' . strtolower(
                    $this->objInfraIBanco->getUsuario()
                ) . '\' and lower(column_name)=\'' . strtolower($strColuna) . '\''
            );
            if (count($rs) && (($strOption == 'not null' && $rs[0]['nullable'] == 'Y') || ($strOption == 'null' && $rs[0]['nullable'] == 'N'))) {
                $ret = $strOption;
            }
        } elseif ($this->objInfraIBanco instanceof InfraPostgreSql) {
            if ($strOption == 'null') {
                $ret = 'drop not null';
            } else {
                $ret = 'set not null';
            }
        }
        return $ret;
    }

    public function processarIndicesChavesEstrangeiras($arrTabelas = null, $arrChavesEstrangeirasIgnorar = null)
    {
        $bolDebugInfra = InfraDebug::getInstance()->isBolDebugInfra();

        $arrConstraints = $this->obterConstraints();
        $arrColunasConstraints = $this->obterColunasConstraints();

        if ($bolDebugInfra) {
            InfraDebug::getInstance()->setBolDebugInfra(false);
        }

        $arrFKs = array();
        $arrPKs = array();

        foreach ($arrConstraints as $arrConstraint) {
            $strNomeTabela = $arrConstraint['table_name'];

            if ($arrTabelas == null || in_array($strNomeTabela, $arrTabelas)) {
                if ($arrConstraint['constraint_type'] == 'primary key') {
                    $arrColunasPK = array_keys(
                        $arrColunasConstraints[$strNomeTabela][$arrConstraint['constraint_name']]
                    );
                    $arrPKs[$strNomeTabela] = $this->prepararColunasComparacao($arrColunasPK);
                } else {
                    $strNomeIndiceFK = $arrConstraint['constraint_name'];

                    if ($arrChavesEstrangeirasIgnorar == null || !in_array(
                            $strNomeIndiceFK,
                            $arrChavesEstrangeirasIgnorar
                        )) {
                        $arrColunasFK = array_keys($arrColunasConstraints[$strNomeTabela][$strNomeIndiceFK]);
                        $arrFKs[$strNomeTabela][$strNomeIndiceFK] = $this->prepararColunasComparacao($arrColunasFK);
                    }
                }
            }
        }

        if ($bolDebugInfra) {
            InfraDebug::getInstance()->setBolDebugInfra(true);
        }

        foreach ($arrFKs as $strNomeTabela => $arrFKsTabela) {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra(
                    '[InfraMetaBD->processarIndicesChavesEstrangeiras]: ' . $strNomeTabela
                );
            }


            foreach ($arrFKsTabela as $strNomeIndiceFK => $arrColunasFK) {
                if (InfraDebug::isBolProcessar()) {
                    InfraDebug::getInstance()->gravarInfra(
                        '[InfraMetaBD->processarIndicesChavesEstrangeiras]: --> ' . $strNomeIndiceFK
                    );
                }

                if (!(isset($arrPKs[$strNomeTabela]) && $arrPKs[$strNomeTabela] == $arrColunasFK)) {
                    $this->criarIndice($strNomeTabela, $strNomeIndiceFK, $arrColunasFK);
                }
            }
        }
    }

    public function gerarDDL($bolTabelas = true, $bolPKs = true, $bolFKs = true, $bolIndices = true)
    {
        try {
            $ret = '';

            if ($bolTabelas) {
                $sqlTabelas = "\n";
                $arrTabelas = $this->obterTabelas();
                $numTabelas = count($arrTabelas);

                for ($i = 0; $i < $numTabelas; $i++) {
                    $sqlTabelas .= "\n" . 'create table ' . $arrTabelas[$i]['table_name'] . ' (';

                    $arrColunas = $this->obterColunasTabela($arrTabelas[$i]['table_name']);
                    $numColunas = count($arrColunas);

                    $sqlColunas = '';

                    for ($j = 0; $j < $numColunas; $j++) {
                        if (substr($arrColunas[$j]['column_name'], 0, 3) == 'id_') {
                            if ($sqlColunas != '') {
                                $sqlColunas .= ',' . "\n";
                            } else {
                                $sqlColunas .= "\n";
                            }

                            $sqlColunas .= $this->gerarDDLColuna($arrColunas[$j]);
                        }
                    }

                    for ($j = 0; $j < $numColunas; $j++) {
                        if (substr($arrColunas[$j]['column_name'], 0, 3) != 'id_') {
                            if ($sqlColunas != '') {
                                $sqlColunas .= ',' . "\n";
                            } else {
                                $sqlColunas .= "\n";
                            }

                            $sqlColunas .= $this->gerarDDLColuna($arrColunas[$j]);
                        }
                    }
                    $sqlTabelas .= $sqlColunas;
                    $sqlTabelas .= "\n" . ');' . "\n";
                }
                $ret .= $sqlTabelas;
            }

            if ($bolPKs) {
                $sqlPKs = "\n";
                $arrConstraints = $this->obterConstraints();
                $arrColunasContraints = $this->obterColunasConstraints();
                $numConstraints = count($arrConstraints);
                for ($i = 0; $i < $numConstraints; $i++) {
                    if ($arrConstraints[$i]['constraint_type'] == 'primary key') {
                        $arrColunasPK = array_keys(
                            $arrColunasContraints[$arrConstraints[$i]['table_name']][$arrConstraints[$i]['constraint_name']]
                        );
                        $sqlPKs .= 'alter table ' . $arrConstraints[$i]['table_name'] . ' add constraint ' . $arrConstraints[$i]['constraint_name'] . ' primary key (' . implode(
                                ',',
                                $arrColunasPK
                            ) . ');' . "\n";
                    }
                }
                $ret .= $sqlPKs;
            }


            if ($bolFKs) {
                $sqlFKs = "\n";
                $arrConstraints = $this->obterConstraints();
                $arrColunasContraints = $this->obterColunasConstraints();
                $numConstraints = count($arrConstraints);
                for ($i = 0; $i < $numConstraints; $i++) {
                    if ($arrConstraints[$i]['constraint_type'] == 'foreign key') {
                        $arrCampos = array();
                        $arrCamposOrigem = array();
                        $strTabelaOrigem = null;
                        foreach ($arrColunasContraints[$arrConstraints[$i]['table_name']][$arrConstraints[$i]['constraint_name']] as $strNomeColuna => $arrTabelaOrigem) {
                            $arrCampos[] = $strNomeColuna;
                            $strTabelaOrigem = $arrTabelaOrigem[0];
                            $arrCamposOrigem[] = $arrTabelaOrigem[1];
                        }
                        $sqlFKs .= 'alter table ' . $arrConstraints[$i]['table_name'] . ' add constraint ' . $arrConstraints[$i]['constraint_name'] . ' foreign key (' . implode(
                                ',',
                                $arrCampos
                            ) . ') references ' . $strTabelaOrigem . ' (' . implode(
                                ',',
                                $arrCamposOrigem
                            ) . ');' . "\n";
                    }
                }
                $ret .= $sqlFKs;
            }


            if ($bolIndices) {
                $sqlIndices = "\n";
                $arrIndices = $this->obterIndices(null, null, false);
                foreach ($arrIndices as $strTabela => $arrIndicesTabela) {
                    foreach ($arrIndicesTabela as $strIndice => $arrColunasIndice) {
                        $sqlIndices .= 'create index ' . $strIndice . ' on ' . $strTabela . ' (' . implode(
                                ',',
                                $arrColunasIndice
                            ) . ');' . "\n";
                    }
                }

                $arrIndices = $this->obterIndices(null, null, true);
                foreach ($arrIndices as $strTabela => $arrIndicesTabela) {
                    foreach ($arrIndicesTabela as $strIndice => $arrColunasIndice) {
                        $sqlIndices .= 'create unique index ' . $strIndice . ' on ' . $strTabela . ' (' . implode(
                                ',',
                                $arrColunasIndice
                            ) . ');' . "\n";
                    }
                }
                $ret .= $sqlIndices;
            }

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro gerando comandos DDL.', $e);
        }
    }

    private function gerarDDLColuna($arrColuna)
    {
        $ret = '  ' . $arrColuna['column_name'] . ' ' . $arrColuna['data_type'];

        if (!in_array($arrColuna['data_type'], array('int', 'integer', 'bigint'))) {
            if ($arrColuna['character_maximum_length'] != '') {
                $ret .= '(' . $arrColuna['character_maximum_length'] . ') ';
            }

            if ($arrColuna['numeric_precision'] != '') {
                $ret .= '(' . $arrColuna['numeric_precision'] . ($arrColuna['numeric_scale'] != '0' ? ',' . $arrColuna['numeric_scale'] : '') . ') ';
            }
        }

        $ret .= ($arrColuna['is_nullable'] == 'YES' ? ' null' : ' not null');

        return $ret;
    }
}

