<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/06/2006 - criado por MGA
 *
 * @package infra_php
 */

abstract class InfraBD {
  private $objInfraIBanco;
  private $arrAlias;
  private $arrBind;
  private $tipoBind;
  private $arrCriteriosProcessados;
  private $arrGruposProcessados;
  private $strSql;
  private $bolRetornarSql;

  private static $TB_MYSQL = 1;
  private static $TB_ORACLE = 2;
  private static $TB_SQLSERVER = 3;
  private static $TB_POSTGRESQL = 4;

  public function __construct($objInfraIBanco){
    $this->objInfraIBanco = $objInfraIBanco;
  }

  protected function getObjInfraIBanco(){
    return $this->objInfraIBanco;
  }

  public function cadastrar($varObjInfraDTO, $bolRetornarSql=false) {
    try{

      $this->configurarMontagem($bolRetornarSql);

      $bolOracle = $this->objInfraIBanco instanceof InfraOracle;
      if ($bolOracle && !$bolRetornarSql && !$this->objInfraIBanco->isBolUsarPreparedStatement()){
        $this->tipoBind = self::$TB_ORACLE;
        $this->arrBind = array();
      }

      $bolMultiplo=is_array($varObjInfraDTO);
      if(is_array($varObjInfraDTO)){
        $numRegistros=count($varObjInfraDTO);
        if ($numRegistros==0) return null;
        $objInfraDTO=$varObjInfraDTO[0];
      } else {
        $numRegistros=1;
        $objInfraDTO=$varObjInfraDTO;
        $varObjInfraDTO=array($objInfraDTO);
      }

      $objInfraDTO->montarChaves();
      $sql = '';
      $strValues = '';
      $strSeparador = '';

      $arrPK = $objInfraDTO->getArrPK();

      $bolPrimeiro=true;

      $strCampos='';
      $arrStrValues=array();
      foreach ($varObjInfraDTO as $objInfraDTO) {

        $arrAtributos = $objInfraDTO->getArrAtributos();

        ksort($arrAtributos);

        $arrKeys = array_keys($arrAtributos);

        foreach($arrKeys as $key){
          $atributo=$arrAtributos[$key];
          if ($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null && $atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]===null){
            if (isset($arrPK[$key]) && $arrPK[$key] == InfraDTO::$TIPO_PK_SEQUENCIAL){

              //Obtem proxima sequencia para a tabela
              if ($bolPrimeiro) {
                $strCampos .= $strSeparador . $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL];
              }

              $objInfraSequencia = new InfraSequencia($this->getObjInfraIBanco());
              $numProxSeq = $objInfraSequencia->obterProximaSequencia($objInfraDTO->getStrNomeTabela());
              call_user_func(array($objInfraDTO,'set'.$atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO].$key),$numProxSeq);

              $strValues .= $strSeparador.$this->adicionarBind($atributo, $numProxSeq);

              $strSeparador = ',';

            }else  if (isset($arrPK[$key]) && $arrPK[$key] == InfraDTO::$TIPO_PK_NATIVA){

              $numProxSeq = $this->getObjInfraIBanco()->getValorSequencia($objInfraDTO->getStrNomeSequenciaNativa());
              call_user_func(array($objInfraDTO,'set'.$atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO].$key),$numProxSeq);

              if ($bolPrimeiro) {
                $strCampos .= $strSeparador . $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL];
              }

              $strValues .= $strSeparador.$this->adicionarBind($atributo, $numProxSeq);

              $strSeparador = ',';

            } else if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET){

              if($bolPrimeiro) {
                $strCampos .= $strSeparador . $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL];
              }

              $strValues .= $strSeparador.$this->adicionarBind($atributo, $atributo[InfraDTO::$POS_ATRIBUTO_VALOR]);
              $strSeparador = ',';

            }
          }
        }

        $arrStrValues[]=$strValues;
        $strValues = '';
        $strSeparador = '';
        $bolPrimeiro=false;

      }

      if ($bolOracle && $numRegistros>1){ //MULTIPLOS REGISTROS NO ORACLE
        $sql = 'INSERT INTO '.$objInfraDTO->getStrNomeTabela().' (';
        $sql.= $strCampos;
        $sql .= ') (';
        for($i=0;$i<$numRegistros;$i++){
          $sql.= 'SELECT '.$arrStrValues[$i].((($i+1)<$numRegistros)?' FROM DUAL UNION ALL ':' FROM DUAL ');
        }
        $sql .= ')';
      } else { // 1 REGISTRO OU OUTROS BANCOS
        $sql = 'INSERT INTO '.$objInfraDTO->getStrNomeTabela().' (';
        $sql.= $strCampos;
        $sql .= ') VALUES ';
        $sql .= '('.$arrStrValues[0].')';
        for($i=1;$i<$numRegistros;$i++){
          $sql.=',('.$arrStrValues[$i].')';
        }
      }

      if ($bolRetornarSql){
        return $sql.';';
      }

      $this->getObjInfraIBanco()->executarSql($sql, $this->arrBind);

      if ($bolMultiplo) {
        return $varObjInfraDTO;
      } else {
        return $varObjInfraDTO[0];
      }

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando registro em '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  public function alterar($objInfraDTO, $bolRetornarSql=false){
    try {

      $this->configurarMontagem($bolRetornarSql);

      $bolOracle = $this->objInfraIBanco instanceof InfraOracle;
      if ($bolOracle && !$bolRetornarSql && !$this->objInfraIBanco->isBolUsarPreparedStatement()){
        $this->tipoBind = self::$TB_ORACLE;
        $this->arrBind = array();
      }

      $objInfraDTO->montarChaves();

      $strSeparador = '';
      $arrAtributos = $objInfraDTO->getArrAtributos();
      $arrPK = $objInfraDTO->getArrPK();


      $sql = 'UPDATE '.$objInfraDTO->getStrNomeTabela().' SET ';
      foreach($arrAtributos as $atributo){

        //Se é um atributo de banco e pertence a tabela do DTO
        if ($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null && $atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]===null){

          //Se não é chave-primária e esta com o valor setado
          if (!isset($arrPK[$atributo[InfraDTO::$POS_ATRIBUTO_NOME]]) && ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET)){
            $sql .= $strSeparador.$atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].'='.$this->adicionarBind($atributo, $atributo[InfraDTO::$POS_ATRIBUTO_VALOR]);
            $strSeparador = ',';
          }
        }
      }
      $sql .= ' '.$this->montarWherePK($objInfraDTO);

      if ($bolRetornarSql){
        return $sql.';';
      }
      //  echo $sql; exit;
      $numReg = $this->getObjInfraIBanco()->executarSql($sql, $this->arrBind);

      if ($numReg==0){
        throw new InfraException('Registro não encontrado em '.$objInfraDTO->getStrNomeTabela().'.', null, self::formatarDetalhesSql($sql,$this->arrBind));
      }

    }catch(Exception $e){
      throw new InfraException('Erro alterando registro de '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  public function excluir($objInfraDTO, $bolRetornarSql=false){
    try {
      $sql = '';

      $this->configurarMontagem($bolRetornarSql);

      $objInfraDTO->montarChaves();

      $sql = 'DELETE FROM '.$objInfraDTO->getStrNomeTabela().$this->montarWherePK($objInfraDTO);

      if ($bolRetornarSql){
        return $sql.';';
      }

      $numReg = $this->getObjInfraIBanco()->executarSql($sql, $this->arrBind);

      if ($numReg==0){
        throw new InfraException('Registro não encontrado em '.$objInfraDTO->getStrNomeTabela().'.', null, self::formatarDetalhesSql($sql,$this->arrBind));
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo registro em '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  public function consultar($objInfraDTO, $bolRetornarSql=false){
    try {

      $this->configurarMontagem($bolRetornarSql);

      $ret = $this->listar($objInfraDTO, $bolRetornarSql);

      if ($bolRetornarSql){
        return $ret;
      }

      $numRegistros = count($ret);

      //Retornou 1 registro OK
      //Critérios informados são uma chave-primária ou chave-candidata
      if ($numRegistros===1){
        $objDTO = $ret[0];
        //Nada foi encontrado OK
      }else if ($numRegistros===0){
        $objDTO = null;
        //Retornou mais de um registro, critérios informados não identificam
        //unicamente um registro na tabela ERRO
      }else{
        throw new InfraException('Consulta retornou mais de um registro de '.strtoupper($objInfraDTO->getStrNomeTabela()).'.', null, self::formatarDetalhesSql($this->strSql,$this->arrBind));
      }
      return $objDTO;

    }catch(Exception $e){
      throw new InfraException('Erro consultando registro de '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($this->strSql,$this->arrBind));
    }
    return null;
  }

  public function desativar($objInfraDTO, $bolRetornarSql=false){
    try {
      $sql = '';

      //if (!$objInfraDTO->isBolConfigurouExclusaoLogica()){
      //  throw new InfraException(get_class($objInfraDTO).' não possui exclusão lógica configurada.');
      //}

      $this->configurarMontagem($bolRetornarSql);

      $objInfraDTO->montarChaves();

      $arrAtributos = $objInfraDTO->getArrAtributos();

      $sql = 'UPDATE '.$objInfraDTO->getStrNomeTabela().' SET ';

      $strSet = $this->montarCondicao($objInfraDTO,$arrAtributos['SinAtivo'], InfraDTO::$OPER_IGUAL,'N');
      if ($this->getObjInfraIBanco() instanceof InfraPostgreSql){
        $strSet = str_replace($objInfraDTO->getStrNomeTabela().'.','',$strSet);
      }
      $sql .= $strSet;

      $sql .= $this->montarWherePK($objInfraDTO);

      if ($bolRetornarSql){
        return $sql.';';
      }

      $numReg = $this->getObjInfraIBanco()->executarSql($sql, $this->arrBind);

      if ($numReg==0){
        throw new InfraException('Registro de '.$objInfraDTO->getStrNomeTabela().' não encontrado para desativação.', null, self::formatarDetalhesSql($sql,$this->arrBind));
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando registro de '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  public function reativar($objInfraDTO, $bolRetornarSql=false){
    try {
      $sql = '';

      //if (!$objInfraDTO->isBolConfigurouExclusaoLogica()){
      //  throw new InfraException(get_class($objInfraDTO).' não possui exclusão lógica configurada.');
      //}

      $this->configurarMontagem($bolRetornarSql);

      $objInfraDTO->montarChaves();

      $arrAtributos = $objInfraDTO->getArrAtributos();

      $sql = 'UPDATE '.$objInfraDTO->getStrNomeTabela().' SET ';

      $strSet = $this->montarCondicao($objInfraDTO,$arrAtributos['SinAtivo'], InfraDTO::$OPER_IGUAL,'S');
      if ($this->getObjInfraIBanco() instanceof InfraPostgreSql){
        $strSet = str_replace($objInfraDTO->getStrNomeTabela().'.','',$strSet);
      }
      $sql .= $strSet;

      $sql .= $this->montarWherePK($objInfraDTO);

      if ($bolRetornarSql){
        return $sql.';';
      }

      $numReg = $this->getObjInfraIBanco()->executarSql($sql, $this->arrBind);
      if ($numReg==0){
        throw new InfraException('Registro de '.$objInfraDTO->getStrNomeTabela().' não encontrado para reativação.', null, self::formatarDetalhesSql($sql,$this->arrBind));
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando registro de '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  public function contar($objInfraDTO, $bolRetornarSql=false) {
    try {

      $this->configurarMontagem($bolRetornarSql);

      $objInfraDTO->montarChaves();

      $this->gerarAlias($objInfraDTO);

      $strRet = '';
      $strSeparador = '';

      if ($objInfraDTO->getDistinct()) {
        $arrAtributos = $objInfraDTO->getArrAtributos();
        foreach ($arrAtributos as $atributo) {
          if ($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL] !== null) {
            if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_RET) {
              $strRet .= $strSeparador;
              $strRet .=  $this->selecionarCampo($objInfraDTO, $atributo);
              $strSeparador = ',';
            }
          }
        }

        if ($strRet != '') {
          $sql = 'SELECT COUNT(*) AS total FROM (SELECT DISTINCT '.$strRet.' '.$this->montarFromJoinWhere($objInfraDTO).') Temp';
        }
      }

      if ((!$objInfraDTO->getDistinct()) || ($strRet == '')) {
        $sql = 'SELECT COUNT(*) AS total '.$this->montarFromJoinWhere($objInfraDTO);
      }

      if ($bolRetornarSql){
        return $sql.';';
      }

      $rs = $this->getObjInfraIBanco()->consultarSql($sql, $this->arrBind);

      if (!isset($rs[0]['total'])){
        throw new InfraException('Contagem não retornou nenhum valor.', null, self::formatarDetalhesSql($sql,$this->arrBind));
      }

      if (!is_numeric($rs[0]['total'])){
        throw new InfraException('Contagem não retornou valor numérico.', null, self::formatarDetalhesSql($sql,$this->arrBind));
      }

      return $rs[0]['total'];

    }catch(Exception $e){
      throw new InfraException('Erro contando registros de '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  public function listar($objInfraDTO, $bolRetornarSql=false) {
    try {
      $sql = '';

      $this->configurarMontagem($bolRetornarSql);

      $objInfraDTO->montarChaves();

      $this->gerarAlias($objInfraDTO);

      $sql .= $this->montarSelect($objInfraDTO);
      $sql .= $this->montarFromJoinWhere($objInfraDTO);
      $sql .= $this->montarOrderBy($objInfraDTO);

      if ($objInfraDTO->getStrSubSelectSqlNativo()!=null){
        $sql = str_replace('INFRA_SQL', $sql, $objInfraDTO->getStrSubSelectSqlNativo());
      }

      if ($objInfraDTO->getStrSqlPesquisa()!==null && $objInfraDTO->getStrSqlSubstituicao()!==null){
        $sql = str_replace($objInfraDTO->getStrSqlPesquisa(), $objInfraDTO->getStrSqlSubstituicao(), $sql);
      }

      if ($bolRetornarSql){
        return $sql.';';
      }

      //Sem paginação
      if ($objInfraDTO->getNumPaginaAtual()===null){
        if ($objInfraDTO->getNumMaxRegistrosRetorno()===null){
          $rs = $this->getObjInfraIBanco()->consultarSql($sql, $this->arrBind);
        }else{
          $rs = $this->getObjInfraIBanco()->limitarSql($sql,$objInfraDTO->getNumMaxRegistrosRetorno(), $this->arrBind);
        }
        //Com paginação
      }else{
        $pag = $this->getObjInfraIBanco()->paginarSql($sql,$objInfraDTO->getNumPaginaAtual()*$objInfraDTO->getNumMaxRegistrosRetorno(),$objInfraDTO->getNumMaxRegistrosRetorno(),$this->arrBind);
        $objInfraDTO->setNumTotalRegistros($pag['totalRegistros']);
        $objInfraDTO->setNumRegistrosPaginaAtual(count($pag['registrosPagina']));
        $rs = $pag['registrosPagina'];
      }


      $arrObjInfraDTO = array();

      /*
      $numReg = count($rs);
      for($i=0;$i<$numReg;$i++){
        $arrObjInfraDTO[$i]=$this->montarDTORetorno($objInfraDTO,$rs[$i]);
      }
      */


      $arrAtributos = $objInfraDTO->getArrAtributos();
      $arrKeys = array_keys($arrAtributos);
      $arrKeysRet = array();
      foreach($arrKeys as $key){
        if ( $arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null && $arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_RET){
          $arrKeysRet[] = $key;
        }
      }
      $reflectionClass = new ReflectionClass(get_class($objInfraDTO));
      $objBase = $reflectionClass->newInstance();

      foreach($rs as $item){

        $objRet = clone($objBase);

        $arrAtributosRet = array();

        foreach($arrKeysRet as $key){
          $valor = $this->lerCampo($arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_PREFIXO],$item[$this->arrAlias[$key]]);
          $arrAtributosRet[$key][InfraDTO::$POS_ATRIBUTO_VALOR] = $valor;
          $arrAtributosRet[$key][InfraDTO::$POS_ATRIBUTO_FLAGS] = InfraDTO::$FLAG_SET | InfraDTO::$FLAG_IGUAL;
        }

        $objRet->setArrAtributos($arrAtributosRet);
        $arrObjInfraDTO[] = $objRet;
      }

      //$rs = null;
      //unset($rs);

      $this->strSql = $sql;

      return $arrObjInfraDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando registros de '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  public function bloquear($objInfraDTO, $bolRetornarSql=false){
    try {

      $this->configurarMontagem($bolRetornarSql);

      $objInfraDTO->montarChaves();

      $reflectionClass = new ReflectionClass(get_class($objInfraDTO));
      $dto = $reflectionClass->newInstance();

      $bolRetTodos = true;
      $arrAtributos = $objInfraDTO->getArrAtributos();
      foreach($arrAtributos as $atributo){
        if ($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null && $atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_RET){

          if ($atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]!==null){
            throw new InfraException('Método bloquear aceita retorno apenas de campos da tabela principal.');
          }

          call_user_func(array($dto,'ret'.$atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO].$atributo[InfraDTO::$POS_ATRIBUTO_NOME]));
          $bolRetTodos = false;
        }
      }

      if ($bolRetTodos){
        //retornar atributos da tabela
        $dto->retTodos();
      }

      //seta apenas chave primária
      $arrPK = $objInfraDTO->getArrPK();
      if ($arrPK!=null){
        $arrKeys = array_keys($arrPK);
        foreach($arrKeys as $key){
          $valor = call_user_func(array($objInfraDTO,'get'.$arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_PREFIXO].$key));
          call_user_func(array($dto,'set'.$arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_PREFIXO].$key),$valor);
        }
      }

      $sqlIngres = '';
      if ($this->getObjInfraIBanco() instanceof InfraIngres){

        //$sqlIngres = 'set lockmode session where readlock=exclusive, timeout=30';
        $sqlIngres = 'set lockmode session where level=row, readlock=exclusive, timeout=30';

        if (!$bolRetornarSql){
          $this->getObjInfraIBanco()->executarSql($sqlIngres);
        }
      }

      $dto->montarChaves();
      $this->gerarAlias($dto);

      $sql = '';
      $sql .= $this->montarSelect($dto);
      $sql .= $this->montarFrom($dto);

      if ($this->getObjInfraIBanco() instanceof InfraSqlServer ){
        $sql .= ' WITH (HOLDLOCK ROWLOCK) ';
      }

      $sql .= $this->montarWherePK($dto);

      if ($this->getObjInfraIBanco() instanceof InfraMySql ||
          $this->getObjInfraIBanco() instanceof InfraOracle ||
          $this->getObjInfraIBanco() instanceof InfraIngres ||
          $this->getObjInfraIBanco() instanceof InfraPostgreSql){
        $sql .= ' FOR UPDATE';
      }

      if ($bolRetornarSql){
        return $sqlIngres.$sql.';';
      }

      $rs = $this->getObjInfraIBanco()->consultarSql($sql, $this->arrBind);

      $numRegistros = count($rs);

      if ($numRegistros===1){
        $dto = $this->montarDTORetorno($dto,$rs[0]);
      }else if ($numRegistros===0){
        $dto = null;
      }else if ($numRegistros > 1){
        throw new InfraException('Mais de um registro encontrado para bloqueio.', null, self::formatarDetalhesSql($sql,$this->arrBind));
      }

      return $dto;

    }catch(Exception $e){
      throw new InfraException('Erro bloqueando registro em '.$objInfraDTO->getStrNomeTabela().'.', $e, self::formatarDetalhesSql($sql,$this->arrBind));
    }
    return null;
  }

  private function obterPrefixoCampo($objInfraDTO, $atributo){
    $strRet = null;
    //Se tem Fks coloca prefixo na frente do campos
    if (is_array($objInfraDTO->getArrFK())){
      //atributos da propria tabela tem tab_origem nulo
      if ($atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]===null){
        $strRet = $objInfraDTO->getStrNomeTabela();
      } else {
        //Se tem espaco no nome da tabela assume que foi dado um alias
        //e este já foi referenciado pelo usuario ao adicionar o campo no DTO
        if (strpos($atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM],' ')===false){
          $strRet = $atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM];
        }
      }
    }
    return $strRet;
  }

  private function montarPrefixoCampo($objInfraDTO, $atributo){
    $strRet = $this->obterPrefixoCampo($objInfraDTO,$atributo);
    if ($strRet!==null){
      $strRet .= '.';
    }
    return $strRet;
  }

  protected function montarSelect($objInfraDTO) {

    $strRet = '';
    $strSeparador = '';

    $arrAtributos = $objInfraDTO->getArrAtributos();
    foreach($arrAtributos as $atributo){
      if ($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null){
        if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_RET){
          $strRet .= $strSeparador;
          $strRet .=  $this->selecionarCampo($objInfraDTO,$atributo);
          $strSeparador = ',';
        }
      }
    }

    if ($strRet==''){
      throw new InfraException('Nenhum campo solicitado para retorno no DTO.');
    }

    $strSelect = '';
    if (!$objInfraDTO->getDistinct()){
      $strSelect = 'SELECT ';
    }else{
      $strSelect = 'SELECT DISTINCT ';
    }

    $strSelect .= $strRet;

    return $strSelect;
  }
  /*
  FROM accounting_link_invoice AS a

  LEFT OUTER JOIN accounting_link_payment AS pmt
  ON a.contact_id = pmt.contact_id
  AND a.property_id = pmt.property_id
  AND a.invoice_id = pmt.invoice_id
  AND pmt.accounting_date <= CONVERT(datetime, '05/22/2007')

  LEFT OUTER JOIN accounting_link_credit AS cm
  ON a.contact_id = cm.contact_id
  AND a.property_id = cm.property_id
  AND a.invoice_id = cm.invoice_id
  AND cm.accounting_date <= CONVERT(datetime, '05/22/2007')

  WHERE (a.property_id = 921)
  AND (a.accounting_date <= CONVERT(datetime, '05/22/2007'))
  AND (a.contact_id = 929)


  SELECT tblalunos.nome, tblcursos.nomecurso, tblnotas.nota
  FROM tblcursos
  INNER JOIN
  (tblalunos INNER JOIN tblnotas
  ON tblalunos.codaluno = tblnotas.codaluno)
  ON tblcursos.codcurso = tblnotas.codcurso
  ORDER BY tblalunos.nome;


  SELECT administrador_sistema.id_usuario,administrador_sistema.id_sistema,usuario.sigla AS infra_campo_rel_1,sistema.sigla AS infra_campo_rel_2,usuario.id_orgao AS infra_campo_rel_3,a.sigla AS infra_campo_rel_4,sistema.id_orgao AS infra_campo_rel_5,b.sigla AS infra_campo_rel_6
  FROM administrador_sistema
  INNER JOIN usuario ON administrador_sistema.id_usuario=usuario.id_usuario
  INNER JOIN sistema ON administrador_sistema.id_sistema=sistema.id_sistema
  INNER JOIN orgao a ON usuario.id_orgao=a.id_orgao
  INNER JOIN orgao b ON sistema.id_orgao=b.id_orgao
  ORDER BY sistema.sigla ASC

  SELECT administrador_sistema.id_usuario,administrador_sistema.id_sistema,usuario.sigla AS infra_campo_rel_1,sistema.sigla AS infra_campo_rel_2,usuario.id_orgao AS infra_campo_rel_3,a.sigla AS infra_campo_rel_4,sistema.id_orgao AS infra_campo_rel_5,b.sigla AS infra_campo_rel_6
  FROM administrador_sistema
  INNER JOIN (usuario INNER JOIN orgao a ON usuario.id_orgao=a.id_orgao) ON administrador_sistema.id_usuario=usuario.id_usuario
  INNER JOIN (sistema INNER JOIN orgao b ON sistema.id_orgao=b.id_orgao) ON administrador_sistema.id_sistema=sistema.id_sistema
  ORDER BY sistema.sigla ASC

  SELECT serie.id_serie,serie.nome,tipo_serie.nome AS infra_campo_rel_1
  FROM serie
  INNER JOIN tipo_serie ON serie.id_tipo_serie=tipo_serie.id_tipo_serie
  WHERE serie.id_tipo_serie='2'
  AND serie.sin_ativo='S'
  ORDER BY serie.nome ASC

  */

  private function carregarArrJoin($objInfraDTO,$tabelaOrigem, &$arr){
    $arrFK = $objInfraDTO->getArrFK();
    if (is_array($arrFK)){
      $arrAtributos = $objInfraDTO->getArrAtributos();
      $arrNomesTabelasFK = array_keys($arrFK);
      foreach($arrNomesTabelasFK as $nomeTabelaFK){
        $arrAtributosFK = array_keys($arrFK[$nomeTabelaFK]);
        foreach($arrAtributosFK as $nomeAtributoFK){
          if ($arrAtributos[$nomeAtributoFK][InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]===$tabelaOrigem){
            if ($tabelaOrigem===null){
              $tab = $objInfraDTO->getStrNomeTabela();
            }else{
              $tab = $tabelaOrigem;
            }

            $numReg = count($arr);

            for($i=0;$i<$numReg;$i++){
              if ($arr[$i][0]==$tab && $arr[$i][1]==$nomeTabelaFK){
                break;
              }
            }

            //Se não existe no array (chaves-compostas)
            if ($i==$numReg){
              $n = $numReg;
              $arr[$n] = array();
              $arr[$n][0] = $tab;
              $arr[$n][1] = $nomeTabelaFK;
              $this->carregarArrJoin($objInfraDTO,$nomeTabelaFK,$arr);
            }
          }
        }
      }
    }
  }

  private function isLeftJoin($objInfraDTO){
    $arrFK = $objInfraDTO->getArrFK();

    if (is_array($arrFK)){
      //obtem tabelas referenciadas (primeira dimensao do array de fks)
      $arrNomesTabelasFK = array_keys($arrFK);

      //Para cada tabela referenciada
      foreach ($arrNomesTabelasFK as $nomeTabelaFK){
        $arrNomesAtributosFK = array_keys($arrFK[$nomeTabelaFK]);
        //Pega o tipo configurado no primeiro atributo da FK
        if( $arrFK[$nomeTabelaFK][$arrNomesAtributosFK[0]][InfraDTO::$POS_FK_TIPO]==InfraDTO::$TIPO_FK_OPCIONAL){
          return true;
        }
      }
    }
    return false;
  }

  private function getTipoJoin($objInfraDTO,$strTabPK, $strTabFK){
    $arrFK = $objInfraDTO->getArrFK();

    //obtem tabelas referenciadas (primeira dimensao do array de fks)
    $arrNomesTabelasFK = array_keys($arrFK);

    //Para cada tabela referenciada
    foreach ($arrNomesTabelasFK as $nomeTabelaFK){
      if ($strTabFK === $nomeTabelaFK){
        $arrNomesAtributosFK = array_keys($arrFK[$nomeTabelaFK]);

        //Pega o tipo configurado no primeiro atributo da FK
        $numTipoFK = $arrFK[$nomeTabelaFK][$arrNomesAtributosFK[0]][InfraDTO::$POS_FK_TIPO];
        if ($numTipoFK==InfraDTO::$TIPO_FK_OBRIGATORIA){
          return ' INNER JOIN ';
        }else{
          return ' LEFT JOIN ';
        }
      }
    }
  }

  /*
00038 - #documento-->subserie
00039 - #documento-->protocolo
00040 - #protocolo-->unidade a
00041 - #protocolo-->publicacao

FROM documento
INNER JOIN @1@subserie ON documento.id_subserie=subserie.id_subserie
INNER JOIN @2@(protocolo INNER JOIN @6@unidade a%3% ON protocolo.id_unidade_geradora=a.id_unidade)%1% ON documento.id_documento=protocolo.id_protocolo

*/

  private function montarOn($objInfraDTO,$tab1,$tab2){
    $strOn = '';
    $strSeparador = '';
    $arrAtributos = $objInfraDTO->getArrAtributos();


    //Se o array de fks existe
    if (is_array($objInfraDTO->getArrFK())){

      //Recupera as chaves estrangeiras montadas
      $arrFK = $objInfraDTO->getArrFK();

      //obtem tabelas referenciadas (primeira dimensao do array de fks)
      $arrNomesTabelasFK = array_keys($arrFK);

      //Para cada tabela referenciada
      foreach ($arrNomesTabelasFK as $nomeTabelaFK){

        if ($tab2 === $nomeTabelaFK){
          $strOn .= ' ON ';

          //Obtem atributos FK do DTO que apontam para a tabela referenciada
          $arrNomesAtributosFK = array_keys($arrFK[$nomeTabelaFK]);

          //Monta cruzamento entre as tabelas
          $strSeparador = '';
          foreach ($arrNomesAtributosFK as $nomeAtributoFK) {
            $strOn .= $strSeparador;

            //Testa se não é join entre outras tabelas, pode ser adicionado no
            //DTO campos estrangeiros e forçar um join de outras tabelas
            $strOn .= $this->montarPrefixoCampo($objInfraDTO,$arrAtributos[$nomeAtributoFK]).$arrAtributos[$nomeAtributoFK][InfraDTO::$POS_ATRIBUTO_CAMPO_SQL];

            //Adiciona a tabela e campo sql FK relacionados ao atributo
            $strOn .= '=';

            //Se tem espaco no nome da tabela assume que foi usado um alias
            //e que o campo foi adicionado no DTO considerando isso
            if (strpos($nomeTabelaFK,' ')===false){
              $strOn .= $nomeTabelaFK.'.'.$arrFK[$nomeTabelaFK][$nomeAtributoFK][InfraDTO::$POS_FK_CAMPO];
            }else{
              $strOn .= $arrFK[$nomeTabelaFK][$nomeAtributoFK][InfraDTO::$POS_FK_CAMPO];
            }
            $strSeparador = ' AND ';
          }

          //pega o filtro (ON/WHERE) configurado na primeira FK
          if ($arrFK[$nomeTabelaFK][$arrNomesAtributosFK[0]][InfraDTO::$POS_FK_FILTRO]==InfraDTO::$FILTRO_FK_ON){

            //Monta filtros para campos da outra tabela que foram setados
            foreach($arrAtributos as $atributo){
              if ($atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]==$nomeTabelaFK){
                if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET){
                  $strOn .= $strSeparador;
                  $strOn .= $this->montarCondicao($objInfraDTO,$atributo,$objInfraDTO->getOperador($atributo),$atributo[InfraDTO::$POS_ATRIBUTO_VALOR]);
                  $strSeparador = ' AND ';
                }
              }
            }

            //Monta os criterios/grupos que referenciam apenas as tabelas 1 e 2
            $strCriterios = $this->montarCriterios($objInfraDTO,array($tab1,$tab2));
            if ($strCriterios!=''){
              $strOn .= $strSeparador.$strCriterios;
              $strSeparador = ' AND ';
            }
          }
        }
      }
    }
    return $strOn;
  }

  /*
  SELECT tabela.id_tabela,tabela.id_isolada_pk_1,tabela.id_isolada_pk_2,tabela.id_nivel_3,tabela.atributo,tabela.id_relacionada_1,tabela.id_relacionada_2,nivel_3.nome AS infra_campo_rel_1,nivel_2.nome AS infra_campo_rel_2,nivel_1.nome AS infra_campo_rel_3,nivel_3.id_nivel_2_pk_1 AS infra_campo_rel_4,nivel_3.id_nivel_2_pk_2 AS infra_campo_rel_5,nivel_1.atributo_a AS infra_campo_rel_6,nivel_1.atributo_b AS infra_campo_rel_7,nivel_2.id_nivel_1 AS infra_campo_rel_8,relacionada_1.nome AS infra_campo_rel_9,relacionada_2.nome AS infra_campo_rel_10,relacionada_1.id_tipo_relacionada AS infra_campo_rel_11,a.nome AS infra_campo_rel_12,relacionada_2.id_tipo_relacionada AS infra_campo_rel_13,b.nome AS infra_campo_rel_14,isolada.nome AS infra_campo_rel_15
  FROM tabela
  LEFT JOIN isolada ON tabela.id_isolada_pk_1=isolada.id_isolada_pk_1 AND tabela.id_isolada_pk_2=isolada.id_isolada_pk_2
  LEFT JOIN (nivel_3 LEFT JOIN (nivel_2 LEFT JOIN nivel_1 ON nivel_2.id_nivel_1=nivel_1.id_nivel_1) ON nivel_3.id_nivel_2_pk_1=nivel_2.id_nivel_2_pk_1 AND nivel_3.id_nivel_2_pk_2=nivel_2.id_nivel_2_pk_2) ON tabela.id_nivel_3=nivel_3.id_nivel_3
  INNER JOIN (relacionada_1 INNER JOIN tipo_relacionada a ON relacionada_1.id_tipo_relacionada=a.id_tipo_relacionada) ON tabela.id_relacionada_1=relacionada_1.id_relacionada_1
  LEFT JOIN (relacionada_2 INNER JOIN tipo_relacionada b ON relacionada_2.id_tipo_relacionada=b.id_tipo_relacionada) ON tabela.id_relacionada_2=relacionada_2.id_relacionada_2
  WHERE tabela.id_tabela='1'
  */

  /*
  00037 - #login-->sistema
  00038 - #sistema-->orgao s
  00039 - #login-->usuario
  00040 - #login-->contexto
  00041 - #contexto-->orgao c
  */

  private function montarArvore($arrJoin,$objInfraDTO){
    $arrArvore = array();
    foreach($arrJoin as $join){
      if (!$this->adicionarFolhaArvore($join[0],$join[1],$arrArvore,$objInfraDTO)){

        if (!isset($arrArvore[$join[0]])){
          $arrArvore[$join[0]] = array();
        }

        $arrArvore[$join[0]][$join[1]] = null;

        //InfraDebug::getInstance()->gravar('@2'.$join[0].' - '.$join[1]);
      }
    }
    return $arrArvore;
  }

  private function adicionarFolhaArvore($tab1,$tab2,&$arr,$objInfraDTO){

    foreach(array_keys($arr) as $tab){
      if ($tab==$tab1){
        if ($arr[$tab1]===null){
          $arr[$tab1] = array();
        }
        $arr[$tab1][$tab2] = null;
        //InfraDebug::getInstance()->gravar('@1'.$tab1.' - '.$tab2);
        return true;
      }else if (is_array($arr[$tab])){
        if ($this->adicionarFolhaArvore($tab1,$tab2,$arr[$tab],$objInfraDTO)){;
          return true;
        }
      }
    }
    return false;
  }

  /*
  arr[login][sistema]
  arr[login][sistema][orgao s]
  */

  private function montarJoinRaiz($arrArvore,$arrJoin,$objInfraDTO,&$str){
    foreach(array_keys($arrArvore) as $raiz){
      $str .= $raiz.' ';
      $str .= $this->montarJoinFilhos($arrArvore[$raiz],$raiz,$arrJoin,$objInfraDTO,$str);
    }
  }

  private function montarJoinFilhos($arr,$pai,$arrJoin,$objInfraDTO,&$str){
    foreach(array_keys($arr) as $tab){
      $str .= $this->getTipoJoin($objInfraDTO,$pai,$tab);
      if (is_array($arr[$tab])){
        $str .= ' (';
        $str .= $tab;
        $str .= $this->montarJoinFilhos($arr[$tab],$tab,$arrJoin,$objInfraDTO,$str);
        $str .= ')';
      }else{
        $str .= $tab;
      }
      $str .= ' '.$this->montarOn($objInfraDTO,$pai,$tab);
    }
  }

  protected function montarFromJoinWhere($objInfraDTO){
    $this->arrCriteriosProcessados = array();
    $this->arrGruposProcessados = array();

    //Se MUMPS que não aceita sintaxe INNER JOIN e LEFT JOIN
    $bolMumps = !$this->getObjInfraIBanco() instanceof InfraMySql &&
                !$this->getObjInfraIBanco() instanceof InfraSqlServer &&
                !$this->getObjInfraIBanco() instanceof InfraOracle &&
                !$this->getObjInfraIBanco() instanceof InfraPostgreSql &&
                !$this->getObjInfraIBanco() instanceof InfraIngres;

    if ($bolMumps){
      $strFrom = $this->montarFrom($objInfraDTO);
      $strWhere = $this->montarWhere($objInfraDTO);
      return $strFrom.$strWhere;
    }

    $strFrom = '';
    $strJoin = '';
    $strWhere = '';
    $strSeparador = '';
    $arrAtributos = $objInfraDTO->getArrAtributos();

    $strFrom = ' FROM ';

    /*
    $arrFK = $objInfraDTO->getArrFK();
    if (is_array($arrFK)){
      $arrNomesTabelasFK = array_keys($arrFK);
      foreach ($arrNomesTabelasFK as $nomeTabelaFK){
         $arrNomesAtributosFK = array_keys($arrFK[$nomeTabelaFK]);
         foreach ($arrNomesAtributosFK  as $a){
           InfraDebug::getInstance()->gravar('@'.$nomeTabelaFK.'->'.$a);
         }
      }
    }

    00012 - @isolada->IdIsoladaPk1
    00013 - @isolada->IdIsoladaPk2
    00014 - @nivel_3->IdNivel3
    00015 - @nivel_2->IdNivel2Pk1
    00016 - @nivel_2->IdNivel2Pk2
    00017 - @nivel_1->IdNivel1
    00018 - @relacionada_1->IdRelacionada1
    00019 - @relacionada_2->IdRelacionada2
    00020 - @tipo_relacionada a->IdTipoRelacionada1
    00021 - @tipo_relacionada b->IdTipoRelacionada2
    */

    $arrJoin = array();
    $this->carregarArrJoin($objInfraDTO,null,$arrJoin);
    //foreach($arrJoin as $join){
    //  InfraDebug::getInstance()->gravar('#'.$join[0].'-->'.$join[1]);
    //}

    if (count($arrJoin)==0){
      $strFrom .= $objInfraDTO->getStrNomeTabela();
    }else{

      /*
      00022 - #tabela-->isolada
      00023 - #tabela-->nivel_3
      00024 - #nivel_3-->nivel_2
      00025 - #nivel_2-->nivel_1
      00026 - #tabela-->relacionada_1
      00027 - #relacionada_1-->tipo_relacionada a
      00028 - #tabela-->relacionada_2
      00029 - #relacionada_2-->tipo_relacionada b
      */


      $strJoin = '';
      $arrArvore = $this->montarArvore($arrJoin,$objInfraDTO);

      //InfraDebug::getInstance()->gravar(print_r($arrArvore,true));

      $this->montarJoinRaiz($arrArvore,$arrJoin,$objInfraDTO,$strJoin);
    }

    //Monta WHERE para campos da tabela principal
    $strSeparador = '';
    foreach($arrAtributos as $atributo){
      if ($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null && $atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]===null){
        if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET){
          $strWhere .= $strSeparador;
          $strWhere .= $this->montarCondicao($objInfraDTO,$atributo,$objInfraDTO->getOperador($atributo),$atributo[InfraDTO::$POS_ATRIBUTO_VALOR]);
          $strSeparador = ' AND ';
        }
      }
    }

    /////////////////////////////////////////////////

    if (is_array($objInfraDTO->getArrFK())){

      //Recupera as chaves estrangeiras montadas
      $arrFK = $objInfraDTO->getArrFK();

      //obtem tabelas referenciadas (primeira dimensao do array de fks)
      $arrNomesTabelasFK = array_keys($arrFK);

      //Para cada tabela referenciada
      foreach ($arrNomesTabelasFK as $nomeTabelaFK){

        //Obtem atributos FK do DTO que apontam para a tabela referenciada
        $arrNomesAtributosFK = array_keys($arrFK[$nomeTabelaFK]);

        //pega o filtro (ON/WHERE) configurado na primeira FK
        if ($arrFK[$nomeTabelaFK][$arrNomesAtributosFK[0]][InfraDTO::$POS_FK_FILTRO]==InfraDTO::$FILTRO_FK_WHERE){

          foreach($arrAtributos as $atributo){
            if ($atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM]==$nomeTabelaFK){
              if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET){
                $strWhere .= $strSeparador;
                $strWhere .= $this->montarCondicao($objInfraDTO,$atributo,$objInfraDTO->getOperador($atributo),$atributo[InfraDTO::$POS_ATRIBUTO_VALOR]);
                $strSeparador = ' AND ';
              }
            }
          }
        }
      }
    }

    $strCriterios = $this->montarCriterios($objInfraDTO);
    if ($strCriterios != ''){
      $strWhere .= $strSeparador.$strCriterios;
      $strSeparador = ' AND ';
    }

    if ($objInfraDTO->isBolConfigurouExclusaoLogica() && $objInfraDTO->isBolExclusaoLogica()){
      //Elimina registros excluidos logicamente
      $strWhere .= $strSeparador.$this->montarCondicao($objInfraDTO,$arrAtributos['SinAtivo'],InfraDTO::$OPER_IGUAL,'S');
      $strSeparador = ' AND ';
    }

    if ($objInfraDTO->isSetStrCriterioSqlNativo()) {
      $strWhere .= $strSeparador.'('.$objInfraDTO->getStrCriterioSqlNativo().')';
      $strSeparador = ' AND ';
    }

    if ($objInfraDTO->getArrCriteriosSqlNativos()!=null) {
      foreach($objInfraDTO->getArrCriteriosSqlNativos() as $arrCriterioSqlNativo){
        $strWhere .= $strSeparador.'('.$arrCriterioSqlNativo[InfraDTO::$POS_CRITERIO_SQL_NATIVO_VALOR].')';
        $strSeparador = ' AND ';
      }
    }

    if ($strWhere != ''){
      $strWhere = ' WHERE '.$strWhere;
    }

    return $strFrom.$strJoin.$strWhere;
  }

  private function montarCondicao($objInfraDTO, $atributo, $operador, $valor){

    $strRet = '';
    if ( $valor === null ) {
      $strRet .= $this->montarPrefixoCampo($objInfraDTO, $atributo);
      if ($operador==InfraDTO::$OPER_IGUAL){
        $strRet .= $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].' IS NULL';
      }else if($operador==InfraDTO::$OPER_DIFERENTE){
        $strRet .= $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].' IS NOT NULL';
      }else{
        throw new InfraException('Comparações com valores nulos somente são permitidas com os operadores de igualdade ou diferença.');
      }
    } else {
      //Testa operação in
      if ($operador==InfraDTO::$OPER_IN || $operador==InfraDTO::$OPER_NOT_IN){

        if (!is_array($valor)) {
          throw new InfraException('Array inválido processando operador ' . $operador . '.');
        }

        if ($this->getObjInfraIBanco() instanceof InfraOracle && count($valor) > 1000){

          $strRet .= '(';
          $arrPartes = array_chunk($valor, 1000);
          $strOr = '';
          foreach($arrPartes as $arrParte){
            if ($strOr !='' ) {
              $strRet .= $strOr;
            }
            $strRet .= $this->montarPrefixoCampo($objInfraDTO, $atributo);
            $strRet .= $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL] . ' ' . $operador . ' (';
            $strVirgula = '';
            foreach ($arrParte as $valorParte) {
              $strRet .= $strVirgula.$this->adicionarBind($atributo, $valorParte);
              $strVirgula = ',';
            }
            $strRet .= ') ';
            $strOr = ' '.InfraDTO::$OPER_LOGICO_OR.' ';
          }
          $strRet .= ')';

        }else {
          $strRet .= $this->montarPrefixoCampo($objInfraDTO, $atributo);
          $strRet .= $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL] . ' ' . $operador . ' (';
          $strVirgula = '';
          foreach ($valor as $valorParte) {
            $strRet .= $strVirgula.$this->adicionarBind($atributo, $valorParte);
            $strVirgula = ',';
          }
          $strRet .= ') ';
        }
      }else{

        //Chama metodo de formatacao de pesquisa específico
        if ($operador==InfraDTO::$OPER_LIKE || $operador==InfraDTO::$OPER_NOT_LIKE) {

          $bolCaseInsensitive = false;

          if ($atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO] == InfraDTO::$PREFIXO_STR) {

            $bolCaseInsensitive = $objInfraDTO->isBolCaseInsensitive($atributo);

            //não informado pega default do banco
            if ($bolCaseInsensitive === null) {
              $bolCaseInsensitive = $this->getObjInfraIBanco()->isBolForcarPesquisaCaseInsensitive();
            }

            if ($this->tipoBind != null && $bolCaseInsensitive){
              $valor = InfraString::transformarCaixaAlta($valor);
            }

            $strCampo = $this->montarPrefixoCampo($objInfraDTO, $atributo).$atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL];
            $strBind  = $this->adicionarBind($atributo, $valor);

          } else if ($atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO] == InfraDTO::$PREFIXO_NUM || $atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO] == InfraDTO::$PREFIXO_DBL || $atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO] == InfraDTO::$PREFIXO_DIN) {

            $strCampo = $this->getObjInfraIBanco()->converterStr($this->obterPrefixoCampo($objInfraDTO, $atributo), $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]);
            $strBind  = $this->adicionarBind($atributo, $valor);

          } else {
            throw new InfraException('Operador LIKE não pode ser utilizado com atributos do tipo '.$atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO].'.');
          }

          if ($atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM] === null) {
            $strTabela = $objInfraDTO->getStrNomeTabela();
          } else {
            $strTabela = $atributo[InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM];
          }

          if ($this->tipoBind == null){
            $strBind = null;
          }

          $strRet .= $this->getObjInfraIBanco()->formatarPesquisaStr($strTabela, $strCampo, $valor, $operador, $bolCaseInsensitive, $strBind);

        }else if ($operador==InfraDTO::$OPER_BIT_AND) {

          if ($this->getObjInfraIBanco() instanceof InfraOracle){
            $strRet .= 'BITAND('.$this->montarPrefixoCampo($objInfraDTO, $atributo).$atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].', '.$valor.') = '.$valor;
          }else {
            $strRet .= '('.$this->montarPrefixoCampo($objInfraDTO, $atributo).$atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].' '.$operador.' '.$valor.') = '.$valor;
          }

        }else if (is_object($valor) && $valor instanceof InfraAtributoDTO) {

          $strRet .= $this->montarPrefixoCampo($objInfraDTO, $atributo);
          $strRet .= $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].$operador;

          $arrAtributoParametro = $valor->getArrAtributo();
          $strRet .= $this->montarPrefixoCampo($objInfraDTO, $arrAtributoParametro);
          $strRet .= $arrAtributoParametro[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL];

        }else {
          //Se o valor for vazio '' tem que fazer um tratamento específico porque as classes de banco
          //convertem '' para NULL. Neste caso, não deve ser convertido '' para NULL porque nunca seria
          //possível fazer uma busca em um banco legado por vazio ('').
          //Bancos novos que só utilizaram a InfraPHP não deveriam ter valores vazios ('') pois no cadastro
          //e inserção estes valores são convertidos para NULL
          if ($valor==='' && !($this->getObjInfraIBanco() instanceof InfraPostgreSql && $atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO] != InfraDTO::$PREFIXO_STR)){
            $strRet .= $this->montarPrefixoCampo($objInfraDTO, $atributo);
            $strRet .= $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].$operador.'\'\'';
          }else{
            $strRet .= $this->montarPrefixoCampo($objInfraDTO, $atributo).$atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].$operador.$this->adicionarBind($atributo, $valor);
          }
        }
      }
    }
    return $strRet;
  }

  protected function montarFrom($objInfraDTO) {

    $strRet = ' FROM '.$objInfraDTO->getStrNomeTabela();

    //monta as tabelas relacionadas (keys do array de fks do DTO)
    if (is_array($objInfraDTO->getArrFK())){
      $arrFK = array_keys($objInfraDTO->getArrFK());
      foreach($arrFK as $tabFK){
        $strRet .= ', '.$tabFK;
      }
    }
    return $strRet;
  }

  protected function montarWhere($objInfraDTO) {
    $strWhere = '';
    $strSeparador = '';

    $arrAtributos = $objInfraDTO->getArrAtributos();

    //Se o array de fks existe
    if (is_array($objInfraDTO->getArrFK())){

      //Recupera as chaves estrangeiras montadas
      $arrFK = $objInfraDTO->getArrFK();

      //obtem tabelas referenciadas (primeira dimensao do array de fks)
      $arrNomesTabelasFK = array_keys($arrFK);

      //Para cada tabela referenciada
      foreach ($arrNomesTabelasFK as $nomeTabelaFK){

        //Obtem atributos do DTO que apontam para a tabela referenciada
        $arrNomesAtributosFK = array_keys($arrFK[$nomeTabelaFK]);

        //Para cada atributo
        foreach ($arrNomesAtributosFK as $nomeAtributoFK) {
          $strWhere .= $strSeparador;

          //Testa se não é join entre outras tabelas, pode ser adicionado no
          //DTO campos estrangeiros e forçar um join de outras tabelas
          $strWhere .= $this->montarPrefixoCampo($objInfraDTO,$arrAtributos[$nomeAtributoFK]).$arrAtributos[$nomeAtributoFK][InfraDTO::$POS_ATRIBUTO_CAMPO_SQL];

          //Adiciona a tabela e campo sql FK relacionados ao atributo
          $strWhere .= '=';

          //Se tem espaco no nome da tabela assume que foi usado um alias
          //e que o campo foi adicionado no DTO considerando isso
          if (strpos($nomeTabelaFK,' ')===false){
            $strWhere .= $nomeTabelaFK.'.'.$arrFK[$nomeTabelaFK][$nomeAtributoFK][InfraDTO::$POS_FK_CAMPO];
          }else{
            $strWhere .= $arrFK[$nomeTabelaFK][$nomeAtributoFK][InfraDTO::$POS_FK_CAMPO];
          }
          $strSeparador = ' AND ';
        }
      }
    }

    //Monta filtros para campos que foram setados
    foreach($arrAtributos as $atributo){
      if ($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null){
        if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET){
          $strWhere .= $strSeparador;
          $strWhere .= $this->montarCondicao($objInfraDTO,$atributo,$objInfraDTO->getOperador($atributo),$atributo[InfraDTO::$POS_ATRIBUTO_VALOR]);
          $strSeparador = ' AND ';
        }
      }
    }

    $strCriterios = $this->montarCriterios($objInfraDTO);
    if ($strCriterios != ''){
      $strWhere .= $strSeparador.$strCriterios;
      $strSeparador = ' AND ';
    }

    if ($objInfraDTO->isBolConfigurouExclusaoLogica() && $objInfraDTO->isBolExclusaoLogica()){
      //Elimina registros excluidos logicamente
      $strWhere .= $strSeparador.$this->montarCondicao($objInfraDTO,$arrAtributos['SinAtivo'],InfraDTO::$OPER_IGUAL,'S');
    }


    if ($strWhere != ''){
      $strWhere = ' WHERE '.$strWhere;
    }

    return $strWhere;
  }

  private function montarCriterios($objInfraDTO,$arrTabelas=null){
    $strCriterios = '';
    $strSeparador = '';
    $arrGrupos = $objInfraDTO->getArrGruposCriterios();
    $arrCriterios = $objInfraDTO->getArrCriterios();


    //ADICIONA CRITERIOS NAO AGRUPADOS
    $numCriterios = count($arrCriterios);
    $numGrupos = count($arrGrupos);

    for($i=0;$i<$numCriterios;$i++){
      if (!in_array($i,$this->arrCriteriosProcessados)){
        //Se o critério possui somente as tabelas informadas (processando LEFT JOIN ... ON ...)
        //descartando os critérios que possuem apenas atributos da tabela principal
        if ($this->verificarTabelasCriterios($objInfraDTO,$arrCriterios[$i],$arrTabelas)){

          //Varre os grupos de critérios existentes procurando pelo nome
          for($j=0;$j<$numGrupos;$j++){
            if (in_array($arrCriterios[$i][InfraDTO::$POS_CRITERIO_NOME],$arrGrupos[$j][InfraDTO::$POS_GRUPO_CRITERIO_CRITERIOS])){
              break;
            }
          }
          //Nao encontrou em nenhum grupo
          if ($j==$numGrupos){
            $str = $this->montarCriterio($objInfraDTO,$arrCriterios[$i]);
            if ($str!=''){
              $strCriterios .= $strSeparador.$str;
              $strSeparador = ' AND ';
            }
            $this->arrCriteriosProcessados[] = $i;
          }
        }
      }
    }

    //ADICIONA GRUPOS DE CRITERIOS
    for($i=0;$i<$numGrupos;$i++){
      if (!in_array($i,$this->arrGruposProcessados)){

        //Verifica se cada um dos critérios possui somente as tabelas informadas (processando LEFT JOIN ... ON ...)
        //descartando os critérios que possuem apenas atributos da tabela principal
        for ($j=0;$j<count($arrGrupos[$i][InfraDTO::$POS_GRUPO_CRITERIO_CRITERIOS]);$j++){
          for($k=0;$k<$numCriterios;$k++){
            if ($arrCriterios[$k][InfraDTO::$POS_CRITERIO_NOME]==$arrGrupos[$i][InfraDTO::$POS_GRUPO_CRITERIO_CRITERIOS][$j]){
              if (!$this->verificarTabelasCriterios($objInfraDTO,$arrCriterios[$k],$arrTabelas)){
                break 2;
              }
            }
          }
        }

        //Se os critérios possuem as tabelas
        if ($j==count($arrGrupos[$i][InfraDTO::$POS_GRUPO_CRITERIO_CRITERIOS])){
          $strCriterios .= $strSeparador.'(';
          //Para cada critério do grupo
          for ($j=0;$j<count($arrGrupos[$i][InfraDTO::$POS_GRUPO_CRITERIO_CRITERIOS]);$j++){
            if ($j>0){
              //coloca operador entre os criterios
              $strCriterios .= ' '.$arrGrupos[$i][InfraDTO::$POS_GRUPO_CRITERIO_OPERADORES_LOGICOS][$j-1].' ';
            }
            //localizar pelo nome o criterio do grupo no array de criterios e após montar
            for($k=0;$k<$numCriterios;$k++){
              if ($arrCriterios[$k][InfraDTO::$POS_CRITERIO_NOME]==$arrGrupos[$i][InfraDTO::$POS_GRUPO_CRITERIO_CRITERIOS][$j]){
                $strCriterios .= $this->montarCriterio($objInfraDTO,$arrCriterios[$k]);
              }
            }
          }
          $strCriterios .= ')';
          $strSeparador = ' AND ';
          $this->arrGruposProcessados[] = $i;
        }
      }
    }

    //InfraDebug::getInstance()->gravar('@'.$strCriterios);
    return $strCriterios;
  }

  private function montarCriterio($objInfraDTO, $arrCriterio){
    $arrAtributos = $objInfraDTO->getArrAtributos();
    $strCriterio = '(';
    for($j=0;$j<count($arrCriterio[InfraDTO::$POS_CRITERIO_ATRIBUTOS]);$j++){
      if ($j>0){
        $strCriterio .= ' '.$arrCriterio[InfraDTO::$POS_CRITERIO_OPERADORES_LOGICOS][$j-1].' ';
      }
      $strCriterio .= $this->montarCondicao($objInfraDTO,$arrAtributos[$arrCriterio[InfraDTO::$POS_CRITERIO_ATRIBUTOS][$j]],$arrCriterio[InfraDTO::$POS_CRITERIO_OPERADORES_ATRIBUTOS][$j],$arrCriterio[InfraDTO::$POS_CRITERIO_VALORES_ATRIBUTOS][$j]);
    }
    $strCriterio .= ')';
    return $strCriterio;
  }

  private function verificarTabelasCriterios($objInfraDTO,$arrCriterio,$arrTabelas){
    //Se não tem tabelas para verificacao
    if (!is_array($arrTabelas)){
      return true;
    }
    $arrAtributos = $objInfraDTO->getArrAtributos();
    $arrTabelasCriterio = array();
    //Para cada um dos atributos deste critério
    for($i=0;$i<count($arrCriterio[InfraDTO::$POS_CRITERIO_ATRIBUTOS]);$i++){
      //Obtem tabela do atributo
      $strTabela = $arrAtributos[$arrCriterio[InfraDTO::$POS_CRITERIO_ATRIBUTOS][$i]][InfraDTO::$POS_ATRIBUTO_TAB_ORIGEM];
      if ($strTabela==null){
        $strTabela = $objInfraDTO->getStrNomeTabela();
      }
      //Se a tabela deste atributo não esta no array
      if (!in_array($strTabela, $arrTabelas)){
        return false;
      }
      if (!in_array($strTabela,$arrTabelasCriterio)){
        $arrTabelasCriterio[] = $strTabela;
      }
    }

    //Se os critérios são apenas da tabela principal então deixa pra montar no WHERE
    //provocam efeitos colaterais quando usados em um LEFT JOIN
    if (count($arrTabelasCriterio)==1){

      if ($arrTabelasCriterio[0]==$objInfraDTO->getStrNomeTabela()){
        return false;
      }

      //Se o critério envolve apenas a primeira tabela do relacionamento deixa para montar
      //no próximo ON ou então no WHERE
      if (count($arrTabelas)==2 && $arrTabelasCriterio[0]==$arrTabelas[0]){
        return false;
      }

    }

    return true;
  }

  protected function montarOrderBy($objInfraDTO) {
    $strRet = '';
    $strSeparador = '';

    $arrOrdenacao = $objInfraDTO->getArrOrdenacao();
    if (is_array($arrOrdenacao)){
      $arrAtributos = $objInfraDTO->getArrAtributos();
      foreach($arrOrdenacao as $atributoOrd){
        $atributo = $arrAtributos[$atributoOrd[0]];
        if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_ORD){
          if($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null){
            $strRet .= $strSeparador;
            $strRet .= $this->montarPrefixoCampo($objInfraDTO, $atributo);
            $strRet .= $atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].' '.$atributoOrd[1];
            //$strRet .= $this->arrAlias[$atributo[InfraDTO::$POS_ATRIBUTO_NOME]].' '.$atributoOrd[1];
            $strSeparador = ', ';
          }
        }
      }
      if ( $strRet != '' ) {
        $strRet = ' ORDER BY '.$strRet;
      }
    }
    return $strRet;
  }

  protected function gerarAlias($objInfraDTO){
    $arrAtributos = $objInfraDTO->getArrAtributos();
    $this->arrAlias = array();
    $i = 1;

    $bolMumps = !$this->getObjInfraIBanco() instanceof InfraMySql &&
                !$this->getObjInfraIBanco() instanceof InfraSqlServer &&
                !$this->getObjInfraIBanco() instanceof InfraOracle &&
                !$this->getObjInfraIBanco() instanceof InfraPostgreSql &&
                !$this->getObjInfraIBanco() instanceof InfraIngres;

    foreach($arrAtributos as $atributo){
      //usar sempre em minusculas (Ingres sempre retorna minusculo)
      if ($bolMumps){
        //usa o nome do campo porque o mumps nao trata alias
        $this->arrAlias[$atributo[InfraDTO::$POS_ATRIBUTO_NOME]] = strtolower($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]);
      } else {

        $strAlias = strtolower($atributo[InfraDTO::$POS_ATRIBUTO_NOME]);

        //limitar em 30 porque o sql server retorna vazio se for maior que isso
        if (strlen($strAlias)<=30){
          $this->arrAlias[$atributo[InfraDTO::$POS_ATRIBUTO_NOME]] = $strAlias;
        }else{
          $this->arrAlias[$atributo[InfraDTO::$POS_ATRIBUTO_NOME]] = substr($strAlias,0,27).str_pad($i,3,'0',STR_PAD_LEFT);
          $i++;
        }
      }
    }
  }

  protected function montarDTORetorno($objInfraDTO, $rsItem){
    $reflectionClass = new ReflectionClass(get_class($objInfraDTO));
    $objRet = $reflectionClass->newInstance();

    $arrAtributosRet = array();
    $arrAtributos = $objInfraDTO->getArrAtributos();
    $arrKeys = array_keys($arrAtributos);
    foreach($arrKeys as $key){
      if ( $arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]!==null){
        if ( $arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_RET){
          $valor = $this->lerCampo($arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_PREFIXO],$rsItem[$this->arrAlias[$key]]);
          $arrAtributosRet[$key][InfraDTO::$POS_ATRIBUTO_VALOR]=$valor;
          $arrAtributosRet[$key][InfraDTO::$POS_ATRIBUTO_FLAGS] = InfraDTO::$FLAG_SET | InfraDTO::$FLAG_IGUAL;
          //call_user_func(array($objRet,'set'.$arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_PREFIXO].$key),$valor);
        }
      }
    }
    //Adiciona atributos diretamente economizando chamadas de metodos
    $objRet->setArrAtributos($arrAtributosRet);
    return $objRet;
  }

  protected function montarWherePK($objInfraDTO) {
    $strRet = '';
    $strSeparador = '';

    $arrAtributos = $objInfraDTO->getArrAtributos();
    $arrPK = $objInfraDTO->getArrPK();

    if ($arrPK!=null){
      $arrKeys = array_keys($arrPK);
      foreach($arrKeys as $key){
        //testar prefixo da pk
        $valor = call_user_func(array($objInfraDTO,'get'.$arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_PREFIXO].$key));
        $strRet .= $strSeparador.$this->montarPrefixoCampo($objInfraDTO,$arrAtributos[$key]).$arrAtributos[$key][InfraDTO::$POS_ATRIBUTO_CAMPO_SQL].'='.$this->adicionarBind($arrAtributos[$key], $valor);
        $strSeparador = ' AND ';
      }
    }

    if ( $strRet != '' ) {
      $strRet = ' WHERE '.$strRet;
    } else {
      throw new InfraException('Erro montando condição de chave-primária para tabela '.$objInfraDTO->getStrNomeTabela().'.');
    }
    return $strRet;
  }

  private function selecionarCampo($objInfraDTO, $atributo){


    $strTabela = $this->obterPrefixoCampo($objInfraDTO,$atributo);
    $strCampo = strtolower($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL]);
    $strAlias = $this->arrAlias[$atributo[InfraDTO::$POS_ATRIBUTO_NOME]];

    //Se o alias tem o mesmo nome do campo então é desnecessário
    if ($strAlias == strtolower($atributo[InfraDTO::$POS_ATRIBUTO_CAMPO_SQL])){
      $strAlias = null;
    }

    $strPrefixo = $atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO];

    if ($strPrefixo == InfraDTO::$PREFIXO_NUM){
      return $this->getObjInfraIBanco()->formatarSelecaoNum($strTabela, $strCampo, $strAlias);
    } else if($strPrefixo == InfraDTO::$PREFIXO_STR){
      return $this->getObjInfraIBanco()->formatarSelecaoStr($strTabela, $strCampo, $strAlias);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DTA){
      return $this->getObjInfraIBanco()->formatarSelecaoDta($strTabela, $strCampo, $strAlias);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DTH){
      return $this->getObjInfraIBanco()->formatarSelecaoDth($strTabela, $strCampo, $strAlias);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_BOL){
      return $this->getObjInfraIBanco()->formatarSelecaoBol($strTabela, $strCampo, $strAlias);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DIN){
      return $this->getObjInfraIBanco()->formatarSelecaoDin($strTabela, $strCampo, $strAlias);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DBL){
      return $this->getObjInfraIBanco()->formatarSelecaoDbl($strTabela, $strCampo, $strAlias);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_BIN){
      return $this->getObjInfraIBanco()->formatarSelecaoBin($strTabela, $strCampo, $strAlias);
    } else {
      throw new InfraException('Prefixo ['.$strPrefixo.'] inválido selecionando campo.');
    }

    return null;
  }

  private function gravarCampo($strPrefixo, $valor){

    if ($valor===null){
      return 'NULL';
    }

    if ($strPrefixo == InfraDTO::$PREFIXO_NUM){
      return $this->getObjInfraIBanco()->formatarGravacaoNum($valor);
    } else if($strPrefixo == InfraDTO::$PREFIXO_STR){
      return $this->getObjInfraIBanco()->formatarGravacaoStr($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DTA){
      return $this->getObjInfraIBanco()->formatarGravacaoDta($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DTH){
      return $this->getObjInfraIBanco()->formatarGravacaoDth($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_BOL){
      return $this->getObjInfraIBanco()->formatarGravacaoBol($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DIN){
      return $this->getObjInfraIBanco()->formatarGravacaoDin($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DBL){
      return $this->getObjInfraIBanco()->formatarGravacaoDbl($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_BIN){
      return $this->getObjInfraIBanco()->formatarGravacaoBin($valor);
    } else {
      throw new InfraException('Prefixo ['.$strPrefixo.'] inválido gravando campo.');
    }

    return null;
  }

  private function lerCampo($strPrefixo, $valor){

    if ($valor===null){
      return null;
    }

    if ($strPrefixo == InfraDTO::$PREFIXO_NUM){
      return $this->getObjInfraIBanco()->formatarLeituraNum($valor);
    } else if($strPrefixo == InfraDTO::$PREFIXO_STR){
      return $this->getObjInfraIBanco()->formatarLeituraStr($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DTA){
      return $this->getObjInfraIBanco()->formatarLeituraDta($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DTH){
      return $this->getObjInfraIBanco()->formatarLeituraDth($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_BOL){
      return $this->getObjInfraIBanco()->formatarLeituraBol($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DIN){
      return $this->getObjInfraIBanco()->formatarLeituraDin($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_DBL){
      return $this->getObjInfraIBanco()->formatarLeituraDbl($valor);
    } else if ($strPrefixo == InfraDTO::$PREFIXO_BIN){
      return $this->getObjInfraIBanco()->formatarLeituraBin($valor);
    } else {
      throw new InfraException('Prefixo \''.$strPrefixo.'\' inválido lendo campo.');
    }

    return null;
  }

  private function configurarMontagem($bolRetornarSql){

    $this->bolRetornarSql = $bolRetornarSql;
    $this->tipoBind = null;
    $this->arrBind = null;

    if (!$this->bolRetornarSql) {

      if ($this->objInfraIBanco->isBolUsarPreparedStatement()) {
        if ($this->objInfraIBanco instanceof InfraMySqli) {
          $this->tipoBind = self::$TB_MYSQL;
        } else if ($this->objInfraIBanco instanceof InfraOracle) {
          $this->tipoBind = self::$TB_ORACLE;
        } else if ($this->objInfraIBanco instanceof InfraSqlServer && $this->objInfraIBanco->getTipoInstalacao() == InfraSqlServer::$TI_SQLSRV) {
          $this->tipoBind = self::$TB_SQLSERVER;
        }else if ($this->objInfraIBanco instanceof InfraPostgreSql){
          $this->tipoBind = self::$TB_POSTGRESQL;
        }
      }

      if ($this->tipoBind != null) {
        $this->arrBind = array();
      }
    }
  }

  public static function formatarDetalhesSql($sql,$arrBind){

    $strDetalhes = $sql;

    if ($arrBind != null){

      $strDetalhes .= "\n\nBind:\n";

      foreach($arrBind as $chave => $valor) {
        $strDetalhes .= $chave.': ';

        if (!is_array($valor)){
          $strDetalhes .= $valor;
        }else{
          $strSep = '';
          foreach ($valor as $subchave => $subvalor){
            $strDetalhes .= $strSep.$subvalor;
            $strSep = ', ';
          }
        }
        $strDetalhes .= "\n";
      }
    }
    return substr($strDetalhes, 0, INFRA_TAM_MAX_LOG_SQL);
  }

  protected function adicionarBind($atributo, $valor)
  {

    $strPrefixo = $atributo[InfraDTO::$POS_ATRIBUTO_PREFIXO];

    $valorFormatado = $this->gravarCampo($strPrefixo, $valor);

    if ($this->tipoBind != null) {

      if ($this->tipoBind == self::$TB_MYSQL) {

        if ($strPrefixo == InfraDTO::$PREFIXO_BOL){
          return $valorFormatado;
        }

        $strTipo = 's';
        if ($strPrefixo == InfraDTO::$PREFIXO_NUM) {
          $strTipo = 'i';
        } else if ($strPrefixo == InfraDTO::$PREFIXO_DBL) {
          if (strpos($valorFormatado, '.') !== false) {
            $strTipo = 'd';
          } else {
            $strTipo = 'i';
          }
        } else if ($strPrefixo == InfraDTO::$PREFIXO_BIN) {
          $strTipo = 'b';
        }

        if ($valorFormatado == 'NULL') {
          $valorFormatado = null;
        }else{
          if ($strPrefixo == InfraDTO::$PREFIXO_STR) {

            //no bind utilizar valor original
            $valorFormatado = $valor;

          } else if ($strPrefixo == InfraDTO::$PREFIXO_DTA || $strPrefixo == InfraDTO::$PREFIXO_DTH) {

            //no bind remover apostrofos das datas formatadas
            $valorFormatado = str_replace('\'', '', $valorFormatado);
          }
        }

        $this->arrBind[] = array($strTipo, $valorFormatado);

        return '?';

      } else if ($this->tipoBind == self::$TB_ORACLE) {

        $strBind = 'v'.(count($this->arrBind)+1);

        if ($strPrefixo == InfraDTO::$PREFIXO_BIN) {
          $strBind = 'bin'.$strBind;
        }

        if ($valorFormatado == 'NULL') {
          $valorFormatado = null;
        }else{
          if ($strPrefixo == InfraDTO::$PREFIXO_STR ||
              $strPrefixo == InfraDTO::$PREFIXO_DTA ||
              $strPrefixo == InfraDTO::$PREFIXO_DTH) {

            //no bind utilizar valor original
            $valorFormatado = $valor;
          }
        }

        $this->arrBind[$strBind] = $valorFormatado;

        return ':'.$strBind;

      } else if ($this->tipoBind == self::$TB_SQLSERVER) {

        //valida campos
        $valorFormatado = $this->gravarCampo($strPrefixo, $valor);

        if ($valorFormatado == 'NULL') {
          $valorFormatado = null;
        }else{
          if ($strPrefixo == InfraDTO::$PREFIXO_STR) {

            //no bind utilizar valor original
            $valorFormatado = (string) $valor;

          } else if ($strPrefixo == InfraDTO::$PREFIXO_DTA || $strPrefixo == InfraDTO::$PREFIXO_DTH) {

            //no bind remover apostrofos das datas formatadas
            $valorFormatado = str_replace('\'', '', $valorFormatado);
          }
        }

        $this->arrBind[] = $valorFormatado;

        //limitacao no numero de binds do sql server (2100)
        if (count($this->arrBind) > 2095){
          $this->tipoBind = null;
        }

        if ($strPrefixo == InfraDTO::$PREFIXO_BIN && $valorFormatado == null){
          return 'CONVERT(VARBINARY, ?)';
        }

        return '?';

      }else if ($this->tipoBind == self::$TB_POSTGRESQL){

        $strBind = '$'.(count($this->arrBind)+1);

        if ($valorFormatado == 'NULL') {
          $valorFormatado = null;
        }else{
          if ($strPrefixo == InfraDTO::$PREFIXO_STR ||
              $strPrefixo == InfraDTO::$PREFIXO_DTA ||
              $strPrefixo == InfraDTO::$PREFIXO_DTH) {

            //no bind utilizar valor original
            $valorFormatado = $valor;
          }
        }

        $this->arrBind[$strBind] = $valorFormatado;

        return $strBind;

      }
    }

    return $valorFormatado;
  }

}
?>