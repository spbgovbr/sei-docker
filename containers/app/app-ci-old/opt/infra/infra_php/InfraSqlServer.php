<?
  /**
  * @package infra_php
  * ADICIONADO PDO EM 24/05/2018 - POR cle@trf4.jus.br
  */
  
  abstract class InfraSqlServer implements InfraIBanco {
    private $conexao;
    private $id;
    private $transacao;
    private $numTipoInstalacao = null;

    public static $TI_FREETDS = 1;
    public static $TI_SQLSRV = 2;
    public static $TI_PDO = 3;

		public abstract function getServidor();
		public abstract function getPorta();
		public abstract function getBanco();
		public abstract function getUsuario();
		public abstract function getSenha();
		
		public function __construct($numTipoInstalacao = null) {
			$this->conexao = null;
			$this->id = null;
			$this->transacao = false;

			if ($numTipoInstalacao != null) {

			  if ($numTipoInstalacao!=self::$TI_SQLSRV && $numTipoInstalacao!=self::$TI_FREETDS && $numTipoInstalacao!=self::$TI_PDO){
			    throw new InfraException('Tipo inválido para a extensão de acesso ao SQL Server.');
        }

			  $this->numTipoInstalacao = $numTipoInstalacao;

      }else{
        if (function_exists('sqlsrv_connect')) {
          $this->numTipoInstalacao = self::$TI_SQLSRV;
        } else if (function_exists('mssql_connect')) {
          $this->numTipoInstalacao = self::$TI_FREETDS;
        } elseif (class_exists('PDO')) {
          $this->numTipoInstalacao = self::$TI_PDO;
        } else {
          throw new InfraException('Nenhuma extensão detectada para acesso ao SQL Server.');
        }
      }
		}

		public function __destruct() {
		  if ($this->getIdConexao() != null) {
		    try {
		      $this->fecharConexao(); 
		    } catch(Exception $e){}
		  }
		}
				
		public function getIdBanco() {
			return __CLASS__.'-'.$this->getServidor().'-'.$this->getPorta().'-'.$this->getBanco().'-'.$this->getUsuario();
		}

		public function getIdConexao() {
		  return $this->id;
		}
		
		public function isBolProcessandoTransacao() {
		  return $this->transacao;
		}
		
		public function isBolValidarISO88591() {
		  return false;
		}

    public function isBolUsarPreparedStatement(){
      return false;
    }

		public function getValorSequencia($sequencia) {
		  $rs = $this->consultarSql('INSERT INTO '.$sequencia.' OUTPUT CAST(INSERTED.id as VARCHAR) as \'id\' VALUES (null);');
		  return $rs[0]['id'];
		}
		
		public function isBolForcarPesquisaCaseInsensitive() {
			return true;
		}

		public function isBolManterConexaoAberta() {
			return false;
		}

		public function isBolConsultaRetornoAssociativo() {
			return false;
		}

		public function getTipoInstalacao(){
		  return $this->numTipoInstalacao;
    }

  	//SELEÇÃO
    private function formatarSelecaoGenerico($tabela,$campo,$alias) {
      $ret = '';
      if ($tabela !== null) {
        $ret .= $tabela.'.';
      }
      
      $ret .= $campo;
      
      if ($alias != null) {
        $ret .= ' AS '.$alias;
      }
      return $ret;
    }
    
    private function formatarSelecaoAsVarchar($tabela,$campo,$alias) {
      $ret = 'CAST(';
      if ($tabela !== null) {
        $ret .= $tabela.'.';
      }
      $ret .= $campo.' as varchar)';
      
		  if ($alias !== null) {
		    $ret .= ' AS '.$alias;
		  } else {
		    $ret .= ' AS '.$campo;
		  }
		  
      return $ret;
    }

		public function formatarSelecaoDta($tabela,$campo,$alias) {
		  return $this->formatarSelecaoGenerico($tabela,$campo,$alias);
		}
		
		public function formatarSelecaoDth($tabela,$campo,$alias) {
		  return $this->formatarSelecaoGenerico($tabela,$campo,$alias);
		}
		
		public function formatarSelecaoStr($tabela,$campo,$alias) {
		  return $this->formatarSelecaoGenerico($tabela,$campo,$alias);
		}
		
		public function formatarSelecaoBol($tabela,$campo,$alias) {
		  return $this->formatarSelecaoGenerico($tabela,$campo,$alias);
		}
		
		public function formatarSelecaoNum($tabela,$campo,$alias) {
		  return $this->formatarSelecaoGenerico($tabela,$campo,$alias);
		}

		public function formatarSelecaoDin($tabela,$campo,$alias) {
		  return $this->formatarSelecaoAsVarchar($tabela,$campo,$alias);
		}
		
		public function formatarSelecaoDbl($tabela,$campo,$alias) {
      if ($this->numTipoInstalacao == self::$TI_SQLSRV) {
        return $this->formatarSelecaoGenerico($tabela, $campo, $alias);
      }else {
        return $this->formatarSelecaoAsVarchar($tabela,$campo,$alias);
      }
		}
		
    public function formatarSelecaoBin($tabela,$campo,$alias) {
		  return $this->formatarSelecaoGenerico($tabela,$campo,$alias);
		}
		
    //GRAVAÇÃO
		public function formatarGravacaoDta($dta) {
			return $this->gravarData(substr($dta,0,10));
		}
		
		public function formatarGravacaoDth($dth) {
      return $this->gravarData($dth);
    }
		
		public function formatarGravacaoStr($str) {
		  if ($str === null || $str === ''){
		    return 'NULL';
			}

			if ($this->isBolValidarISO88591() && InfraUtil::filtrarISO88591($str) != $str) {
			  throw new InfraException('Detectado caracter inválido.');
			}

			$str = str_replace("\'",'\'',$str);
			$str = str_replace("'",'\'\'',$str);
				
			return '\''.$str.'\'';
		}
		
		public function formatarGravacaoBol($bol) {
			if ($bol === true) {
				return 1;
			} 
			return 0;
		}

		public function formatarGravacaoNum($num) {
			$num = trim($num);

			if ($num === '') {
				return 'NULL';
			}

			if (!is_numeric($num)) {
				throw new InfraException('Valor numérico inválido ['.$num.'].');
			}

			return $num;
		}

		public function formatarGravacaoDin($din) {
			$din = trim($din);

			if ($din === '') {
				return 'NULL';
			}

			$din = InfraUtil::prepararDin($din);

			if (!is_numeric($din)) {
				throw new InfraException('Valor numérico inválido ['.$din.'].');
			}

			return $din;
		}

		public function formatarGravacaoDbl($dbl) {
			$dbl = trim($dbl);

			if ($dbl===''){
				return 'NULL';
			}

			$dbl = InfraUtil::prepararDbl($dbl);

			if (!is_numeric($dbl)){
				throw new InfraException('Valor numérico inválido ['.$dbl.'].');
			}

			return $dbl;
		}

		public function formatarGravacaoBin($bin) {
		  if ($bin === null || $bin === '') {
		    return 'NULL';
		  }
		  return '0x'.bin2hex($bin);
		}
		
    //LEITURA		
    public function converterStr($tabela,$campo) {
      $ret = 'CAST(';
      if ($tabela !== null){
        $ret .= $tabela.'.';
      }
      $ret .= $campo.' as varchar)';
		  
      return $ret;
    }

    public function formatarPesquisaStr($strTabela,$strCampo,$strValor,$strOperador,$bolCaseInsensitive,$strBind){

      if ($bolCaseInsensitive){
        if ($strBind == null) {
          return 'upper('.$strCampo.') '.$strOperador.' \''.str_replace("'", '\'\'', str_replace("\'", '\'', InfraString::transformarCaixaAlta($strValor))).'\' ';
        }else{
          return 'upper('.$strCampo.') '.$strOperador.' '.$strBind.' ';
        }
      }else{
        if ($strBind == null) {
          return $strCampo.' '.$strOperador.' \''.str_replace("'", '\'\'', str_replace("\'", '\'', $strValor)).'\' ';
        }else{
          return $strCampo.' '.$strOperador.' '.$strBind.' ';
        }
      }
    }
		
		public function formatarLeituraDta($dta) {
 			$dta = $this->lerData($dta);
 			if ($dta !== null) {
 			  $dta = substr($dta,0,10);
 			}
			return $dta;
		}
		
		public function formatarLeituraDth($dth) {
			 return $this->lerData($dth);
		}
		
		public function formatarLeituraStr($str) {
			return $str;
		}
		
		public function formatarLeituraBol($bol) {
			if ($bol == 1) {
				return true;
			} else {
			  return false;
			}	
		}
		
		public function formatarLeituraNum($num) {
			return $num;
		}

		public function formatarLeituraDin($din) {
		  return InfraUtil::formatarDin($din);
		}
		
		public function formatarLeituraDbl($dbl) {
 		  return InfraUtil::formatarDbl($dbl);
		}

  	public function formatarLeituraBin($bin) {
  	  return $bin;
		}

		public function getLocale(){
      return 'en_US.ISO-8859-1';
    }
		
	  public function abrirConexao() {
	    try {
        if (InfraDebug::isBolProcessar()) {
          InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->abrirConexao] ' . $this->getIdBanco());
        }
  
  		  if ($this->conexao != null) {
  		  	throw new InfraException('Tentativa de abrir nova conexão sem fechar a anterior.');
  		  }

        if ($this->numTipoInstalacao == self::$TI_SQLSRV) {

          if ($this->getLocale() != null) {
            setlocale(LC_ALL, $this->getLocale());
          }

          $connectionInfo = array("Database" => $this->getBanco(), "UID" => $this->getUsuario(), "PWD" => $this->getSenha(), 'MultipleActiveResultSets' => false, 'CharacterSet' => SQLSRV_ENC_CHAR);
          if (($this->conexao = sqlsrv_connect($this->getServidor().','.$this->getPorta(), $connectionInfo))===false){
            throw new InfraException($this->processarSqlSrvErrors());
          }

          //sqlsrv_query($this->conexao, "USE {$this->getBanco()};", array());

        } else if ($this->numTipoInstalacao == self::$TI_FREETDS) {
    			$this->conexao = mssql_connect($this->getServidor().':'.$this->getPorta(), $this->getUsuario(), $this->getSenha());
    			mssql_select_db($this->getBanco(), $this->conexao);
  		  } else if ($this->numTipoInstalacao == self::$TI_PDO) {
  		    $this->conexao = new PDO('dblib:host='.$this->getServidor().':'.$this->getPorta().';dbname='.$this->getBanco(), $this->getUsuario(), $this->getSenha());
        }

        $this->id = $this->getIdBanco();


	    } catch(Exception $e) {
	      if (strpos(strtolower($e->__toString()),'unable to connect to server') !== false) {
	        throw new InfraException('Não foi possível abrir conexão com a base de dados.');
	      } elseif (strpos(strtolower($e->__toString()),'not locate entry in sysdatabases for database') !== false) {
	        throw new InfraException('Base de dados não encontrada no servidor.');
        } elseif (strpos(strtolower($e->__toString()),'login failed for user') !== false) {
          throw new InfraException('Falha realizando login na base de dados.');
	      } else {
	        //die(InfraException::inspecionar($e));
	        throw $e;
	      }
	    }
    }
		
	  public function fecharConexao() {
      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->fecharConexao] ' . $this->getIdConexao());
      }
	  	
	  	if ($this->conexao == null) {
	  		throw new InfraException('Tentativa de fechar conexão que não foi aberta.');
	  	}

      if ($this->numTipoInstalacao == self::$TI_SQLSRV) {
        sqlsrv_close($this->conexao);
      }else if ($this->numTipoInstalacao == self::$TI_FREETDS) {
        mssql_close($this->conexao);
	  	}
	    
			$this->conexao = null;
			$this->id = null;
	  }

    public function abrirTransacao() {
      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->abrirTransacao] ' . $this->getIdConexao());
      }

	  	if ($this->conexao == null) {
	  		throw new InfraException('Tentando abrir transação em uma conexão fechada.');
	  	}

      if ($this->numTipoInstalacao == self::$TI_SQLSRV) {
        sqlsrv_begin_transaction($this->conexao);
      } else if ($this->numTipoInstalacao == self::$TI_FREETDS) {
        $this->executarSql('BEGIN TRANSACTION');
      } else if ($this->numTipoInstalacao == self::$TI_PDO){
        $this->conexao->beginTransaction();
	  	}
    	
    	$this->transacao = true;
    }

	  public function confirmarTransacao() {
      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->confirmarTransacao] ' . $this->getIdConexao());
      }

	  	if ($this->conexao == null) {
	  		throw new InfraException('Tentando confirmar transação em uma conexão fechada.');
	  	}

      if ($this->numTipoInstalacao == self::$TI_SQLSRV) {
        sqlsrv_commit($this->conexao);
      } else if ($this->numTipoInstalacao == self::$TI_FREETDS) {
        $this->executarSql('COMMIT TRANSACTION');
      } else if ($this->numTipoInstalacao == self::$TI_PDO) {
        $this->conexao->commit();
	  	}
    	
    	$this->transacao = false;
	  }

	  public function cancelarTransacao() {
      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->cancelarTransacao] ' . $this->getIdConexao());
      }

	  	if ($this->conexao == null) {
	  		throw new InfraException('Tentando desfazer transação em uma conexão fechada.');
	  	}

      if ($this->numTipoInstalacao == self::$TI_SQLSRV) {
        sqlsrv_rollback($this->conexao);
      } else if ($this->numTipoInstalacao == self::$TI_FREETDS) {
        $this->executarSql('ROLLBACK TRANSACTION');
      } else if ($this->numTipoInstalacao == self::$TI_PDO) {
	  	  $this->conexao->rollBack();
	  	}
    	
    	$this->transacao = false;
	  }

	  public function consultarSql($sql, $arrCamposBind = null) {

      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->consultarSql] ' . InfraBD::formatarDetalhesSql($sql,$arrCamposBind));
        $numSeg = InfraUtil::verificarTempoProcessamento();
      }
			
	  	if ($this->conexao == null) {
	  		throw new InfraException('Tentando executar uma consulta em uma conexão fechada.');
	  	}

	  	if ($this->getIdBanco() !== $this->getIdConexao()){
	  	  throw new InfraException('Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.');
	  	}
	  	
	  	$vetor_resultado = array();

      if ($this->numTipoInstalacao == self::$TI_SQLSRV) {

        //sqlsrv_query($this->conexao, "USE {$this->getBanco()};");

        if ($arrCamposBind != null && count($arrCamposBind) > 0) {

          $arrParams = array();

          $numBind = count($arrCamposBind);
          for ($i = 0; $i < $numBind; $i++) {
            $arrParams[] = &$arrCamposBind[$i];
          }

          //sqlsrv_prepare
          if (($stmt = call_user_func_array('sqlsrv_query', array($this->conexao, $sql, $arrParams))) === FALSE) {
            throw new InfraException($this->processarSqlSrvErrors(), null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
          }

          //if ( sqlsrv_execute($stmt) === FALSE){
          //  throw new InfraException($this->processarSqlSrvErrors(), null, InfraBD::formatarDetalhesSql($sql,$arrCamposBind));
          //}

          $tipo_vetor = SQLSRV_FETCH_NUMERIC;
          if ($this->isBolConsultaRetornoAssociativo()) {
            $tipo_vetor = SQLSRV_FETCH_ASSOC;
          }

          while ($registro = sqlsrv_fetch_array($stmt, $tipo_vetor)) {
            $vetor_resultado[] = $registro;
          }

          sqlsrv_free_stmt($stmt);

        } else {

          $resultado = sqlsrv_query($this->conexao, 'SET TEXTSIZE 2147483647;'.$sql);

          if ($resultado === FALSE) {
            throw new InfraException($this->processarSqlSrvErrors(), null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
          }

          $tipo_vetor = SQLSRV_FETCH_BOTH;
          if ($this->isBolConsultaRetornoAssociativo()) {
            $tipo_vetor = SQLSRV_FETCH_ASSOC;
          }

          while ($registro = sqlsrv_fetch_array($resultado, $tipo_vetor)) {
            $vetor_resultado[] = $registro;
          }
        }

      }else if ($this->numTipoInstalacao == self::$TI_FREETDS) {

        //mssql_select_db($this->getBanco(), $this->conexao);

	  	  $resultado = mssql_query('SET TEXTSIZE 2147483647;'.$sql, $this->conexao);
	  	  
	  	  if ($resultado === false) {
	  	    throw new InfraException(mssql_get_last_message(),null,InfraBD::formatarDetalhesSql($sql,$arrCamposBind));
	  	  }	  	  

				$tipo_vetor = MSSQL_BOTH;
				if ($this->isBolConsultaRetornoAssociativo()) {
					$tipo_vetor = MSSQL_ASSOC;
				}

				while ($registro = mssql_fetch_array($resultado, $tipo_vetor)) {
					$vetor_resultado[] = $registro;
				}

      } elseif ($this->numTipoInstalacao == self::$TI_PDO) {

        $hndQuery = $this->conexao->prepare($sql);
        $hndQuery->execute();
        while ($registro = $hndQuery->fetch()) {
          $vetor_resultado[] = $registro;
        }

	  	}

      if (InfraDebug::isBolProcessar()) {
        $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->consultarSql] ' . $numSeg . ' s');
      }

      return $vetor_resultado;      
	  }

 	  public function paginarSql($sql,$ini,$qtd,$arrCamposBind = null) {

      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->paginarSql]');
      }

      if (!is_numeric($ini)){
        throw new InfraException('Valor numérico inválido ['.$ini.'].');
      }

      if (!is_numeric($qtd)){
        throw new InfraException('Valor numérico inválido ['.$qtd.'].');
      }

      $arr = explode(' ',$sql);
      $select = '';
      
      for ($i=0; $i<count($arr); $i++) {
        if (strtoupper($arr[$i]) == 'FROM'){
          break;
        }
        
        $select .= ' '.$arr[$i];
      }

      $from = '';
      for(; $i<count($arr); $i++) {
        if (strtoupper($arr[$i]) == 'ORDER') {
          break;
        }
        $from .= ' '.$arr[$i];
      }

      if (trim($from) == '') {
        throw new InfraException('Cláusula FROM não encontrada.');        
      }
      
      $order = '';
      for(; $i<count($arr); $i++) {
        $order .= ' '.$arr[$i];
      }

      if (trim($order) == '') {
        throw new InfraException('Para utilizar a paginação com este banco de dados é necessário que a consulta utilize pelo menos um campo para ordenação.');
      }
      
      $sql = '';
      $sql .= ' SELECT * FROM (';
      
      if (strpos(strtoupper($select),'DISTINCT') === false) {
        $sql .= $select;
        $sql .= ',InfraRowCount = COUNT(*) OVER(),ROW_NUMBER() OVER ('.$order.') as InfraRowNumber ';
        $sql .= $from;
      } else {
        /*SE TIVER DISTINCT TEM QUE MONTAR DE OUTRA MANEIRA, ADICIONANDO OUTRO NÍVEL DE CONSULTA:
        SELECT TOP 100 * FROM (
        SELECT *
        ,ROW_NUMBER() OVER (order by id_pessoa) as InfraRowNumber [order by sem o nome da tabela nos campos]
        ,InfraRowCount = COUNT(*) OVER()
        FROM  (
        [sql original sem o order by]
        ) as InfraTabelaDistinct
        ) AS InfraTabela
        WHERE InfraRowNumber > 10*/

        $arrSelect = explode(' ',str_replace(',',' ',str_replace('CAST(','',str_replace(' as varchar)','',$select))));
      	$arrOrder = explode(' ',$order);
      	$order = '';

      	for ($i=0; $i<count($arrOrder); $i++) {
      		$order .= ' ';
      		for ($j=0; $j<count($arrSelect); $j++) {
      			if ($arrSelect[$j]==$arrOrder[$i] && isset($arrSelect[$j+1]) && strtoupper($arrSelect[$j+1])=='AS' && isset($arrSelect[$j+2])) {
      				$order .= $arrSelect[$j+2];
      				break;
      			}
      		}

      		if ($j == count($arrSelect) && strpos($arrOrder[$i],'.') !== false) {
      			//SE O CAMPO NAO TINHA ALIAS E POSSUI ".", DEVE RETIRAR O NOME DA TABELA PRINCIPAL
      			$order .= substr($arrOrder[$i],strpos($arrOrder[$i],'.')+1);
      		} elseif ($j == count($arrSelect)) {
      			//SE O CAMPO NAO TINHA ALIAS E NAO POSSUI ".", APENAS COPIA ORDER
      			$order .= $arrOrder[$i];
      		}
      	}

        $sql .= ' SELECT *';
        $sql .= ',InfraRowCount = COUNT(*) OVER(),ROW_NUMBER() OVER ('.$order.') as InfraRowNumber ';
        $sql .= ' FROM (';
        $sql .= $select;
        $sql .= $from;
        $sql .= ') AS InfraTabelaDistinct';
      }
      $sql .= ') AS InfraTabela WHERE InfraRowNumber BETWEEN '.($ini+1) .' AND ' .($ini+$qtd).' ORDER BY InfraRowNumber';

      $rs = $this->consultarSql($sql,$arrCamposBind);

      return array('totalRegistros'=>$rs[0]['InfraRowCount'],'registrosPagina'=>$rs);
	  }
	  	  	  	  
	  public function limitarSql($sql,$qtd,$arrCamposBind = null) {
      /*if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->limitarSql]');
      }*/

      if (!is_numeric($qtd)){
        throw new InfraException('Valor numérico inválido ['.$qtd.'].');
      }

	    $sqlUpper = strtoupper(trim($sql));
	     
	    if (substr($sqlUpper,0,7) != 'SELECT ') {
	      throw new InfraException('Início da consulta não localizado.');
	    }

	    if (($pos=strpos($sqlUpper,' DISTINCT ')) !== false) {
	    	$sql = substr($sql,0,$pos+10).'TOP '.$qtd.' '.substr($sql,$pos+10);
	    } else {
	      $sql = substr($sql,0,7).'TOP '.$qtd.' '.substr($sql,7);	
	    }
	    
	    return $this->consultarSql($sql,$arrCamposBind);
	  }
	  	  
	  public function executarSql($sql, $arrCamposBind=null) {

		  if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->executar] ' . InfraBD::formatarDetalhesSql($sql,$arrCamposBind));
        $numSeg = InfraUtil::verificarTempoProcessamento();
      }

	  	if ($this->conexao == null) {
	  		throw new InfraException('Tentando executar um comando em uma conexão fechada.');
	  	}

	  	if ($this->getIdBanco()!==$this->getIdConexao()) {
	  	  throw new InfraException('Tentando executar comando em um banco de dados diferente do utilizado pela conexão atual.');
	  	}

      if ($this->numTipoInstalacao == self::$TI_SQLSRV) {

        //sqlsrv_query($this->conexao, "USE {$this->getBanco()};");

        if ($arrCamposBind != null && count($arrCamposBind) > 0) {

          $arrParams = array();

          $numBind = count($arrCamposBind);
          for ($i = 0; $i < $numBind; $i++) {
            $arrParams[] = &$arrCamposBind[$i];
          }

          //sqlsrv_prepare
          if (($stmt = call_user_func_array('sqlsrv_query', array($this->conexao, $sql, $arrParams))) === FALSE) {
            throw new InfraException($this->processarSqlSrvErrors(), null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
          }

          //if ( sqlsrv_execute($stmt) === FALSE){
          //  throw new InfraException($this->processarSqlSrvErrors(), null, InfraBD::formatarDetalhesSql($sql,$arrCamposBind));
          //}

          $numReg = sqlsrv_rows_affected($stmt);

          sqlsrv_free_stmt($stmt);

        } else {

          $resultado = sqlsrv_query($this->conexao, $sql);

          if ($resultado === false) {
            throw new InfraException($this->processarSqlSrvErrors(), null, InfraBD::formatarDetalhesSql($sql, $arrCamposBind));
          }

          $numReg = sqlsrv_rows_affected($resultado);
        }
      } else if ($this->numTipoInstalacao == self::$TI_FREETDS) {

        //mssql_select_db($this->getBanco(), $this->conexao);

  	    $resultado = mssql_query($sql, $this->conexao);
  	    
  	    if ($resultado === false) {
  	    	throw new InfraException(mssql_get_last_message(),null,InfraBD::formatarDetalhesSql($sql,$arrCamposBind));
  	    }
  	    
  	    $numReg = mssql_rows_affected($this->conexao);

	  	} elseif ($this->numTipoInstalacao == self::$TI_PDO) {

	  	  $numReg = $this->conexao->exec($sql);
	  	  
	  	  if ($numReg === false) {
          $arrErro = $this->conexao->errorInfo();
          //'00000'->SUCCESS, '01000'->SUCCESS WITH WARNING
          if (($arrErro[0] !== '00000') && ($arrErro[0] !== '01000')) {
            throw new InfraException($arrErro[2],null,InfraBD::formatarDetalhesSql($sql,$arrCamposBind));
          }
        }	  	
	  	}

      if (InfraDebug::isBolProcessar()) {
        $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->executar] ' . $numReg . ' registro(s) afetado(s)');
        InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->executar] ' . $numSeg . ' s');
      }

	    return $numReg;
	  }
	  	  
		public function lerData($sqlServerDate) {
			//InfraDebug::getInstance()->gravarInfra($sqlServerDate);
			/* php.ini 
      ; Specify how datetime and datetim4 columns are returned
      ; On => Returns data converted to SQL server settings
      ; Off => Returns values as YYYY-MM-DD hh:mm:ss
      ;mssql.datetimeconvert = On*/

      if ($sqlServerDate === null) {
        return null;
      }

		  if ($this->numTipoInstalacao == self::$TI_SQLSRV) {
		    $sqlServerDate = $sqlServerDate->format('Y-m-d H:i:s');
		  }

			if (strlen($sqlServerDate) != 19) {
				throw new InfraException('Tamanho de data inválido.',null,$sqlServerDate);
			}
			
			return substr($sqlServerDate,8,2).'/'.substr($sqlServerDate,5,2).'/'.substr($sqlServerDate,0,4).substr($sqlServerDate,10);
		}
	
		public function gravarData($brasilDate) {
			 
      if(trim($brasilDate) === '') {
      	return 'NULL';
      }

      $numTamData = strlen($brasilDate);

      if (($numTamData!=10 && $numTamData!=19) || preg_match("/[^0-9 \/\-:]/", $brasilDate)){
        throw new InfraException('Data inválida ['.$brasilDate.'].');
      }

			if ($numTamData == 10) {
			  $brasilDate .= ' 00:00:00';
			}

			//31/12/2005 15:23:50 -> 2005-12-31 15:23:50
			return '\''.substr($brasilDate,6,4).'-'.substr($brasilDate,3,2).'-'.substr($brasilDate,0,2).substr($brasilDate,10).'\'';
		}

    public function formatarPesquisaFTS($strPalavras) {      
     $arrDados = InfraString::agruparItens($strPalavras);
   
     for ($i=0; $i<count($arrDados); $i++) {
       if (strpos($arrDados[$i]," ") !== false || strpos($arrDados[$i],",") !== false || (strpos($arrDados[$i],"*") !== false && strpos($arrDados[$i],"\"") === false)) {
         $arrDados[$i] = "\"".$arrDados[$i]."\"";
       }
        
       if ($arrDados[$i] == "e") {
         $arrDados[$i] = "and";
       } elseif($arrDados[$i] == "ou") {
         $arrDados[$i] = "or";
       } elseif($arrDados[$i] == "nao") {
         $arrDados[$i] = "and not";
       } elseif($arrDados[$i] == "prox") {
         $arrDados[$i] = "near";
       }
     }
   
     $strPesquisaFormatada = "";
     for($i=0; $i<count($arrDados); $i++) {
       //ADICIONA OPERADOR AND COMO PADRÃO SE NÃO INFORMADO
       if ($i > 0) {
         if (!in_array($arrDados[$i-1],array('and','or','and not','near','(')) && !in_array($arrDados[$i],array('and','or','and not','near',')'))) {
           $strPesquisaFormatada .= " and";
         }
       }
       $strPesquisaFormatada .= " ".$arrDados[$i];
     }
     $strPesquisaFormatada = trim(InfraString::substituirIterativo('and and not', 'and not', $strPesquisaFormatada));
   
     return $strPesquisaFormatada;
   }
   
    public function criarSequencialNativa($strSequencia, $numInicial) {
     if (InfraDebug::isBolProcessar()) {
       InfraDebug::getInstance()->gravarInfra('[InfraSqlServer->criarSequencialNativa]');
     }

     $this->executarSql('create table '.$strSequencia.' (id int identity('.$numInicial.',1), campo char(1) null)');
   }

    private function processarSqlSrvErrors(){
		  $ret = '';
		  if (($arrErros = sqlsrv_errors())!=null){
		    foreach($arrErros as $arrErro){
		      if ($ret != ''){
		        $ret .= "\n";
          }
		      $ret .= $arrErro['message'];
        }
      }
		  return $ret;
    }
  }
?>