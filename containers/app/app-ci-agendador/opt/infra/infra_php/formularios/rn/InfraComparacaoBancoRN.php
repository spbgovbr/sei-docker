<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/09/2008 - criado por mga
 *
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraComparacaoBancoRN {

  private $objInfraMetaBancoOrigem = null;
  private $objInfraMetaBancoDestino = null;
  
  public static $TBD_SQLSERVER = 'S';
  public static $TBD_MYSQL = 'M';
  public static $TBD_ORACLE = 'O';
  public static $TBD_POSTGRESQL = 'P';
   
  public function __construct(){
  }

  private function inicializarBancos(InfraComparacaoBancoDTO $parObjInfraComparacaoBancoDTO){
    try{
      $this->objInfraMetaBancoOrigem = null;
      $this->objInfraMetaBancoDestino = null;
      
      switch($parObjInfraComparacaoBancoDTO->getStrBancoDadosOrigem()){
        case self::$TBD_SQLSERVER:
          $this->objInfraMetaBancoOrigem = new InfraMetaBD(InfraBancoSqlServer::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrPortaOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrBancoOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrUsuarioOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrSenhaOrigem()));
          break;
          
        case self::$TBD_MYSQL:
          $this->objInfraMetaBancoOrigem = new InfraMetaBD(InfraBancoMySqli::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrPortaOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrBancoOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrUsuarioOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrSenhaOrigem()));
          break;
          
        case self::$TBD_ORACLE:
          $this->objInfraMetaBancoOrigem = new InfraMetaBD(InfraBancoOracle::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrPortaOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrBancoOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrUsuarioOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrSenhaOrigem()));
          break;

        case self::$TBD_POSTGRESQL:
          $this->objInfraMetaBancoOrigem = new InfraMetaBD(InfraBancoPostgreSql::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrPortaOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrBancoOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrUsuarioOrigem(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrSenhaOrigem()));
          break;

        default:
          throw new InfraException('Tipo do banco de dados origem ['.$parObjInfraComparacaoBancoDTO->getStrBancoDadosOrigem().'] inválido.');  
      }
      
      switch($parObjInfraComparacaoBancoDTO->getStrBancoDadosDestino()){
        case self::$TBD_SQLSERVER:
          $this->objInfraMetaBancoDestino = new InfraMetaBD(InfraBancoSqlServer::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorDestino(),
                                                                                    $parObjInfraComparacaoBancoDTO->getStrPortaDestino(),
                                                                                    $parObjInfraComparacaoBancoDTO->getStrBancoDestino(),
                                                                                    $parObjInfraComparacaoBancoDTO->getStrUsuarioDestino(),
                                                                                    $parObjInfraComparacaoBancoDTO->getStrSenhaDestino()));
          break;
          
        case self::$TBD_MYSQL:
          $this->objInfraMetaBancoDestino = new InfraMetaBD(InfraBancoMySqli::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrPortaDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrBancoDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrUsuarioDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrSenhaDestino()));
          break;
          
        case self::$TBD_ORACLE:
          $this->objInfraMetaBancoDestino = new InfraMetaBD(InfraBancoOracle::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrPortaDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrBancoDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrUsuarioDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrSenhaDestino()));
         break;

        case self::$TBD_POSTGRESQL:
          $this->objInfraMetaBancoDestino = new InfraMetaBD(InfraBancoPostgreSql::newInstance($parObjInfraComparacaoBancoDTO->getStrServidorDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrPortaDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrBancoDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrUsuarioDestino(),
                                                                                $parObjInfraComparacaoBancoDTO->getStrSenhaDestino()));
          break;

       default:
         throw new InfraException('Tipo do banco de dados destino ['.$parObjInfraComparacaoBancoDTO->getStrBancoDadosDestino().'] inválido.');
            
      }
      
      
    }catch(Exception $e){
      throw new InfraException('Erro inicializando bancos.',$e);
    }      
  }
  
  public function compararTabelas(InfraComparacaoBancoDTO $parObjInfraComparacaoBancoDTO){
    try{
      SessaoInfra::getInstance()->validarPermissao('infra_banco_comparar');
       
      $this->inicializarBancos($parObjInfraComparacaoBancoDTO);
      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->abrirConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->abrirConexao();

      $rsNomeTabelaOrigem = $this->objInfraMetaBancoOrigem->obterTabelas(null, $parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar());

      for($i=0;$i<count($rsNomeTabelaOrigem);$i++){
        
        $objInfraComparacaoBancoDTO = new InfraComparacaoBancoDTO();
        $objInfraComparacaoBancoDTO->setStrNomeTabelaOrigem($rsNomeTabelaOrigem[$i]['table_name']);
        $objInfraComparacaoBancoDTO->setStrSinColunasTabelaDestinoOK('S');
         
        $rsColunasTabelaOrigem =$this->objInfraMetaBancoOrigem->obterColunasTabela($rsNomeTabelaOrigem[$i]['table_name']);
        
        $rsNomeTabelaDestino = $this->objInfraMetaBancoDestino->obterTabelas($rsNomeTabelaOrigem[$i]['table_name']);

        $strColunasTabela = '';
        
        if (count($rsNomeTabelaDestino) == 0){
          
          $objInfraComparacaoBancoDTO->setStrSinColunasTabelaDestinoOK(null);
          $objInfraComparacaoBancoDTO->setNumQtdeRegistrosTabelaOrigem(null);
          $objInfraComparacaoBancoDTO->setNumQtdeRegistrosTabelaDestino(null);
          $objInfraComparacaoBancoDTO->setStrSinQtdeRegistrosTabelaDestinoOK(null);
          
          for($j=0;$j<count($rsColunasTabelaOrigem);$j++){
            $strColunasTabela .= '<b> + '.$rsColunasTabelaOrigem[$j]['column_name'].'</b><br />';
          }
          
          $objInfraComparacaoBancoDTO->setStrColunasTabelaOrigem($strColunasTabela);
          
        }else{ 
          
          $rsColunasTabelaDestino = $this->objInfraMetaBancoDestino->obterColunasTabela($rsNomeTabelaOrigem[$i]['table_name']);

          for($j=0;$j<count($rsColunasTabelaOrigem);$j++){
            
            for($k=0;$k<count($rsColunasTabelaDestino);$k++){
              $strColuna = '';
              
              if ($rsColunasTabelaOrigem[$j]['column_name']==$rsColunasTabelaDestino[$k]['column_name']){
                
                if ($parObjInfraComparacaoBancoDTO->getStrSinComparaTipoColunasTabela() == 'S'){

                  $strTipoColunaTabelaOrigem  = $rsColunasTabelaOrigem[$j]['data_type']
                                               .($rsColunasTabelaOrigem[$j]['character_maximum_length'] != '' ? '('.$rsColunasTabelaOrigem[$j]['character_maximum_length'].') ' : '')
                                               .($rsColunasTabelaOrigem[$j]['numeric_precision'] != '' ? '('.$rsColunasTabelaOrigem[$j]['numeric_precision'].','.$rsColunasTabelaOrigem[$j]['numeric_scale'].') ' : ' ')
                                               .($rsColunasTabelaOrigem[$j]['is_nullable'] == 'YES' ? 'NULL' : 'NOT NULL');
                    
                  $strTipoColunaTabelaDestino = $rsColunasTabelaDestino[$k]['data_type']
                                               .($rsColunasTabelaDestino[$k]['character_maximum_length'] != '' ? '('.$rsColunasTabelaDestino[$k]['character_maximum_length'].') ' : '')
                                               .($rsColunasTabelaDestino[$k]['numeric_precision'] != '' ? '('.$rsColunasTabelaDestino[$k]['numeric_precision'].','.$rsColunasTabelaDestino[$k]['numeric_scale'].') ' : ' ')
                                               .($rsColunasTabelaDestino[$k]['is_nullable'] == 'YES' ? 'NULL' : 'NOT NULL');
                  
                  
                  //if ($strTipoColunaTabelaOrigem != $strTipoColunaTabelaDestino){
                  if (!$this->compararTipos($this->objInfraMetaBancoOrigem->getObjInfraIBanco(),
                                            $rsColunasTabelaOrigem[$j]['data_type'],
                                            $rsColunasTabelaOrigem[$j]['character_maximum_length'],
                                            $rsColunasTabelaOrigem[$j]['numeric_precision'],
                                            $rsColunasTabelaOrigem[$j]['numeric_scale'],
                                            $this->objInfraMetaBancoDestino->getObjInfraIBanco(),
                                            $rsColunasTabelaDestino[$k]['data_type'],
                                            $rsColunasTabelaDestino[$k]['character_maximum_length'],
                                            $rsColunasTabelaDestino[$k]['numeric_precision'],
                                            $rsColunasTabelaDestino[$k]['numeric_scale']) || $rsColunasTabelaOrigem[$j]['is_nullable']<>$rsColunasTabelaDestino[$k]['is_nullable']){
                    $objInfraComparacaoBancoDTO->setStrSinColunasTabelaDestinoOK('N');
                    $strColuna = '<b> * '.$rsColunasTabelaOrigem[$j]['column_name'].' ['.$strTipoColunaTabelaOrigem.'] ['.$strTipoColunaTabelaDestino.']</b>';
                  }else{
                    $strColuna = ' '.$rsColunasTabelaOrigem[$j]['column_name'];
                  }
                }
                break; 
              }
            }
            
            if ($k==count($rsColunasTabelaDestino)){
              $strColuna = '<b> + '.$rsColunasTabelaOrigem[$j]['column_name'].'</b>';
              $objInfraComparacaoBancoDTO->setStrSinColunasTabelaDestinoOK('N');
            }else{
              if ($parObjInfraComparacaoBancoDTO->getStrSinComparaTipoColunasTabela() == 'N'){
                $strColuna = ' '.$rsColunasTabelaOrigem[$j]['column_name'];
              }
            } 
            $strColunasTabela .= $strColuna.'<br/>';
          }
          
          for($k=0;$k<count($rsColunasTabelaDestino);$k++){
            for($j=0;$j<count($rsColunasTabelaOrigem);$j++){
              if ($rsColunasTabelaOrigem[$j]['column_name']==$rsColunasTabelaDestino[$k]['column_name']){
                break;
              }
            }
            if ($j==count($rsColunasTabelaOrigem)){
              $strColunasTabela .= '<b> - '.$rsColunasTabelaDestino[$k]['column_name'].'</b><br />';
              $objInfraComparacaoBancoDTO->setStrSinColunasTabelaDestinoOK('N');              
            }
          }
          
          $objInfraComparacaoBancoDTO->setStrColunasTabelaOrigem($strColunasTabela);
          
          if ($parObjInfraComparacaoBancoDTO->getStrSinComparaQtdeRegistrosTabela() == 'S'){
            $rsQtdeRegistrosTabelaOrigem = $this->objInfraMetaBancoOrigem->obterRegistrosTabela($rsNomeTabelaOrigem[$i]['table_name']);
            $objInfraComparacaoBancoDTO->setNumQtdeRegistrosTabelaOrigem($rsQtdeRegistrosTabelaOrigem[0]['total']);
            //$parObjInfraComparacaoBancoDTO->setStrNomeTabelaOrigem($rsNomeTabelaOrigem[$i][name]);
            $rsQtdeRegistrosTabelaDestino = $this->objInfraMetaBancoDestino->obterRegistrosTabela($rsNomeTabelaOrigem[$i]['table_name']);
            
            $objInfraComparacaoBancoDTO->setNumQtdeRegistrosTabelaDestino($rsQtdeRegistrosTabelaDestino[0]['total']);
            
            if ($rsQtdeRegistrosTabelaOrigem[0]['total'] == $rsQtdeRegistrosTabelaDestino[0]['total']){
              $objInfraComparacaoBancoDTO->setStrSinQtdeRegistrosTabelaDestinoOK('S');
            }else{
              $objInfraComparacaoBancoDTO->setStrSinQtdeRegistrosTabelaDestinoOK('N');
            }
          }else{
            $objInfraComparacaoBancoDTO->setNumQtdeRegistrosTabelaOrigem(null);
            $objInfraComparacaoBancoDTO->setNumQtdeRegistrosTabelaDestino(null);
            $objInfraComparacaoBancoDTO->setStrSinQtdeRegistrosTabelaDestinoOK(null);
          }
        }

        $arrObjInfraComparacaoBancoDTO[] = $objInfraComparacaoBancoDTO;
      }
      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->fecharConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->fecharConexao();
       
      return $arrObjInfraComparacaoBancoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro verificando tabelas.',$e);
    }
  }
   
  public function compararConstraints(InfraComparacaoBancoDTO $parObjInfraComparacaoBancoDTO){
    try{
      SessaoInfra::getInstance()->validarPermissao('infra_banco_comparar');
       
      $this->inicializarBancos($parObjInfraComparacaoBancoDTO);
      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->abrirConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->abrirConexao();

      $rsNomeConstraintOrigem = $this->objInfraMetaBancoOrigem->obterConstraints(null,$parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar());
      $rsColunasConstraintsOrigem = $this->objInfraMetaBancoOrigem->obterColunasConstraints();
      $rsColunasConstraintsDestino = $this->objInfraMetaBancoDestino->obterColunasConstraints();
      //die(print_r($rsNomeConstraintOrigem,true));
      
      for($i=0;$i<count($rsNomeConstraintOrigem);$i++){

        $strTabelaOrigem = $rsNomeConstraintOrigem[$i]['table_name'];
        $strConstraintOrigem = $rsNomeConstraintOrigem[$i]['constraint_name'];

        $objInfraComparacaoBancoDTO = new InfraComparacaoBancoDTO();
        $objInfraComparacaoBancoDTO->setStrNomeTabelaOrigem($strTabelaOrigem);
        $objInfraComparacaoBancoDTO->setStrNomeConstraintOrigem($strConstraintOrigem);
        $objInfraComparacaoBancoDTO->setStrSinNomeColunasConstraintDestinoOK('S');

        $rsNomeColunasConstraintOrigem = $rsColunasConstraintsOrigem[$strTabelaOrigem][$strConstraintOrigem];

        $strNomeColunasConstraint = '';

        if ($rsNomeConstraintOrigem[$i]['constraint_type'] == 'primary key'){
          if ($this->objInfraMetaBancoOrigem->getObjInfraIBanco() instanceof InfraMySql && !$this->objInfraMetaBancoDestino->getObjInfraIBanco() instanceof InfraMySql){
            if (isset($rsColunasConstraintsDestino[$strTabelaOrigem])){
              foreach($rsColunasConstraintsDestino[$strTabelaOrigem] as $strNomeConstraintDestino => $arrColunasConstraintDestino){
                foreach($arrColunasConstraintDestino as $arrTabelReferenciada){
                  if ($arrTabelReferenciada[0] == null){
                    $strConstraintOrigem = $strNomeConstraintDestino;
                    break 2;
                  }
                }
              }
            }
          }else if (!$this->objInfraMetaBancoOrigem->getObjInfraIBanco() instanceof InfraMySql && $this->objInfraMetaBancoDestino->getObjInfraIBanco() instanceof InfraMySql){
            $strConstraintOrigem = 'primary';
          }
        }

        if (!isset($rsColunasConstraintsDestino[$strTabelaOrigem][$strConstraintOrigem])){
          
          $objInfraComparacaoBancoDTO->setStrSinNomeColunasConstraintDestinoOK('N');

          foreach($rsNomeColunasConstraintOrigem as $strColunaConstraint => $arrTabelaReferenciada){
            $strNomeColunasConstraint .= '<b> + '.$strColunaConstraint.'</b><br />';
            $objInfraComparacaoBancoDTO->setStrSinNomeColunasConstraintDestinoOK('N');
          }
          
        }else{
          
          $rsNomeColunasConstraintDestino = $rsColunasConstraintsDestino[$strTabelaOrigem][$strConstraintOrigem];

          foreach($rsNomeColunasConstraintOrigem as $strColunaConstraint => $arrTabelaReferenciadaOrigem){

            if (!isset($rsNomeColunasConstraintDestino[$strColunaConstraint])){
              $strNomeColunasConstraint .= '<b> + '.$strColunaConstraint.'</b>';
              $objInfraComparacaoBancoDTO->setStrSinNomeColunasConstraintDestinoOK('N');
            }else{

              $arrTabelaReferenciadaDestino = $rsNomeColunasConstraintDestino[$strColunaConstraint];

              if ($arrTabelaReferenciadaOrigem[0]!=$arrTabelaReferenciadaDestino[0] || $arrTabelaReferenciadaOrigem[1]!=$arrTabelaReferenciadaDestino[1]){
                $strNomeColunasConstraint .= '<b> '.$strColunaConstraint.' ( '.$arrTabelaReferenciadaOrigem[0].'.'.$arrTabelaReferenciadaOrigem[1].' &ne; '.$arrTabelaReferenciadaDestino[0].'.'.$arrTabelaReferenciadaDestino[1].' )</b>';
                $objInfraComparacaoBancoDTO->setStrSinNomeColunasConstraintDestinoOK('N');
              }else {
                $strNomeColunasConstraint .= $strColunaConstraint;
              }
            }
            
            $strNomeColunasConstraint .= '<br />';
          }

          foreach($rsNomeColunasConstraintDestino as $strColunaConstraint => $arrTabelaReferenciadaDestino){

            if (!isset($rsNomeColunasConstraintOrigem[$strColunaConstraint])){
              $strNomeColunasConstraint .= '<b> - '.$strColunaConstraint.'</b><br />';
              $objInfraComparacaoBancoDTO->setStrSinNomeColunasConstraintDestinoOK('N');            
            }
          }
        }
        
        $objInfraComparacaoBancoDTO->setStrNomeColunasConstraintOrigem($strNomeColunasConstraint);

        $arrObjInfraComparacaoBancoDTO[] = $objInfraComparacaoBancoDTO;
      }

      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->fecharConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->fecharConexao();
      
      return $arrObjInfraComparacaoBancoDTO;
       
    }catch(Exception $e){
      throw new InfraException('Erro verificando constraints.',$e);
    }
  }
  
  public function compararSequencias(InfraComparacaoBancoDTO $parObjInfraComparacaoBancoDTO){
    try{
      SessaoInfra::getInstance()->validarPermissao('infra_banco_comparar');

      $this->inicializarBancos($parObjInfraComparacaoBancoDTO);
      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->abrirConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->abrirConexao();

      $rsNomeTabelaSequenciaOrigem = $this->objInfraMetaBancoOrigem->obterSequencias($parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar());
      $rsNomeTabelaDestino = $this->objInfraMetaBancoDestino->obterSequencias($parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar());
      
      for($i=0;$i<count($rsNomeTabelaSequenciaOrigem);$i++){
        for($j=0;$j<count($rsNomeTabelaDestino);$j++){
          
          if ($rsNomeTabelaSequenciaOrigem[$i]['table_name'] == $rsNomeTabelaDestino[$j]['table_name']){
            
            $objInfraComparacaoBancoDTO = new InfraComparacaoBancoDTO();
            $objInfraComparacaoBancoDTO->setStrNomeTabelaOrigem($rsNomeTabelaSequenciaOrigem[$i]['table_name']);
            $objInfraComparacaoBancoDTO->setNumMaxIdTabelaOrigem(null);
            $objInfraComparacaoBancoDTO->setNumMaxIdTabelaSequenciaOrigem(null);
            $objInfraComparacaoBancoDTO->setNumMaxIdTabelaDestino(null);
            $objInfraComparacaoBancoDTO->setNumMaxIdTabelaSequenciaDestino(null);

            $rsMaxIdTabelaOrigem = $this->objInfraMetaBancoOrigem->obterMaxIdTabela(str_replace('seq_','',$rsNomeTabelaSequenciaOrigem[$i]['table_name']));
            if (count($rsMaxIdTabelaOrigem)){
              $objInfraComparacaoBancoDTO->setNumMaxIdTabelaOrigem($rsMaxIdTabelaOrigem[0]['maximo']);
            }

            $rsMaxIdTabelaDestino = $this->objInfraMetaBancoDestino->obterMaxIdTabela(str_replace('seq_','',$rsNomeTabelaSequenciaOrigem[$i]['table_name']));
            if (count($rsMaxIdTabelaDestino)){
              $objInfraComparacaoBancoDTO->setNumMaxIdTabelaDestino($rsMaxIdTabelaDestino[0]['maximo']);
            }

            $numMaxIdTabelaSequenciaOrigem = $this->objInfraMetaBancoOrigem->obterMaxIdTabelaSequencia($rsNomeTabelaSequenciaOrigem[$i]['table_name']);
            if ($numMaxIdTabelaSequenciaOrigem != null){
              $objInfraComparacaoBancoDTO->setNumMaxIdTabelaSequenciaOrigem($numMaxIdTabelaSequenciaOrigem);
            }

            $numMaxIdTabelaSequenciaDestino = $this->objInfraMetaBancoDestino->obterMaxIdTabelaSequencia($rsNomeTabelaSequenciaOrigem[$i]['table_name']);
            if ($numMaxIdTabelaSequenciaDestino != null){
              $objInfraComparacaoBancoDTO->setNumMaxIdTabelaSequenciaDestino($numMaxIdTabelaSequenciaDestino);
            }

            $arrObjInfraComparacaoBancoDTO[] = $objInfraComparacaoBancoDTO;
            break;
          }
        }
        
        if ($j == count($rsNomeTabelaDestino)){
          $objInfraComparacaoBancoDTO = new InfraComparacaoBancoDTO();
          $objInfraComparacaoBancoDTO->setStrNomeTabelaOrigem($rsNomeTabelaSequenciaOrigem[$i]['table_name']);
          $objInfraComparacaoBancoDTO->setNumMaxIdTabelaOrigem(null);
          $objInfraComparacaoBancoDTO->setNumMaxIdTabelaSequenciaOrigem(null);
          $objInfraComparacaoBancoDTO->setNumMaxIdTabelaDestino('[não encontrada]');
          $objInfraComparacaoBancoDTO->setNumMaxIdTabelaSequenciaDestino(null);
          
          $arrObjInfraComparacaoBancoDTO[] = $objInfraComparacaoBancoDTO;
        }
      }
      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->fecharConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->fecharConexao();
      
      return $arrObjInfraComparacaoBancoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro verificando tabelas sequência.',$e);
    }
  }
  
  public function compararIndices(InfraComparacaoBancoDTO $parObjInfraComparacaoBancoDTO){
    try{
      SessaoInfra::getInstance()->validarPermissao('infra_banco_comparar');
       
      $this->inicializarBancos($parObjInfraComparacaoBancoDTO);
      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->abrirConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->abrirConexao();

      $rsIndicesOrigemUnique = $this->objInfraMetaBancoOrigem->obterIndices($parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar(),null,true);
      $rsIndicesDestinoUnique = $this->objInfraMetaBancoDestino->obterIndices($parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar(),null,true);

      $rsIndicesOrigem = $this->objInfraMetaBancoOrigem->obterIndices($parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar());
      $rsIndicesDestino = $this->objInfraMetaBancoDestino->obterIndices($parObjInfraComparacaoBancoDTO->getArrTabelasIgnorar());
      
      foreach($rsIndicesOrigem as $strNomeTabelaOrigem => $arrIndicesOrigem){
        foreach($arrIndicesOrigem as $strNomeIndiceOrigem => $arrColunasIndiceOrigem){
          
          $objInfraComparacaoBancoDTO = new InfraComparacaoBancoDTO();
          $objInfraComparacaoBancoDTO->setStrNomeTabelaOrigem($strNomeTabelaOrigem);
          $objInfraComparacaoBancoDTO->setStrNomeIndiceOrigem($strNomeIndiceOrigem);
          $objInfraComparacaoBancoDTO->setStrColunasIndiceOrigem(null);
          $objInfraComparacaoBancoDTO->setStrSinIndiceOK('S');
          
          $strNomeColunasIndice = '';
          
          if (!isset($rsIndicesDestino[$strNomeTabelaOrigem]) || !isset($rsIndicesDestino[$strNomeTabelaOrigem][$strNomeIndiceOrigem])){
            foreach($arrColunasIndiceOrigem as $strColunaIndiceOrigem){
              $strNomeColunasIndice .= '<b> + '.$strColunaIndiceOrigem.'</b><br />';
            }
            $objInfraComparacaoBancoDTO->setStrSinIndiceOK('N');
          }else{
            
            if (isset($rsIndicesDestino[$strNomeTabelaOrigem][$strNomeIndiceOrigem])){
              $objInfraComparacaoBancoDTO->setStrColunasIndiceOrigem(null);
              
              $arrColunasIndiceDestino = $rsIndicesDestino[$strNomeTabelaOrigem][$strNomeIndiceOrigem];
              
              if (count($arrColunasIndiceOrigem) != count($arrColunasIndiceDestino)){
                $objInfraComparacaoBancoDTO->setStrSinIndiceOK('N');
              }
              
              foreach($arrColunasIndiceOrigem as $strColunaIndiceOrigem){
                if (in_array($strColunaIndiceOrigem,$arrColunasIndiceDestino)){
                  $strNomeColunasIndice .= $strColunaIndiceOrigem;
                }else{
                  $strNomeColunasIndice .= '<b> + '.$strColunaIndiceOrigem.'</b>';
                  $objInfraComparacaoBancoDTO->setStrSinIndiceOK('N');
                }
                $strNomeColunasIndice .= '<br />';
              }
              
              foreach($arrColunasIndiceDestino as $strColunaIndiceDestino){
                if (!in_array($strColunaIndiceDestino,$arrColunasIndiceOrigem)){
                  $strNomeColunasIndice .= '<b> - '.$strColunaIndiceDestino.'</b><br />';
                  $objInfraComparacaoBancoDTO->setStrSinIndiceOK('N');
                }
              }
            }
          }
          $objInfraComparacaoBancoDTO->setStrColunasIndiceOrigem($strNomeColunasIndice);

          $objInfraComparacaoBancoDTO->setStrUnique('');
          if (isset($rsIndicesOrigemUnique[$strNomeTabelaOrigem][$strNomeIndiceOrigem])){
            if (!isset($rsIndicesDestinoUnique[$strNomeTabelaOrigem][$strNomeIndiceOrigem])){
              $objInfraComparacaoBancoDTO->setStrUnique('<b> + Sim </b>');
              $objInfraComparacaoBancoDTO->setStrSinIndiceOK('N');
            }else{
              $objInfraComparacaoBancoDTO->setStrUnique('Sim');
            }
          }else{
            if (isset($rsIndicesDestinoUnique[$strNomeTabelaOrigem][$strNomeIndiceOrigem])){
              $objInfraComparacaoBancoDTO->setStrUnique('<b> + Não </b>');
              $objInfraComparacaoBancoDTO->setStrSinIndiceOK('N');
            }
          }

          $arrObjInfraComparacaoBancoDTO[] = $objInfraComparacaoBancoDTO;
        }
      }
      
      $this->objInfraMetaBancoOrigem->getObjInfraIBanco()->fecharConexao();
      $this->objInfraMetaBancoDestino->getObjInfraIBanco()->fecharConexao();
      
      return $arrObjInfraComparacaoBancoDTO;
       
    }catch(Exception $e){
      throw new InfraException('Erro verificando índices.',$e);
    }
  }
  
  private function compararTipos($objBancoOrigem, $strTipoOrigem, $numTamanhoOrigem, $numPrecisaoOrigem, $numEscalaOrigem, $objBancoDestino, $strTipoDestino, $numTamanhoDestino, $numPrecisaoDestino, $numEscalaDestino){

    if ($strTipoOrigem == $strTipoDestino && $numTamanhoOrigem == $numTamanhoDestino && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
      return true;
    }

    if ($objBancoOrigem instanceof InfraMySql && $objBancoDestino instanceof InfraSqlServer){
      
      if ($strTipoOrigem == 'longtext' && ($strTipoDestino=='varchar' && $numTamanhoDestino=='-1')){
        return true;
      }

      if ($strTipoOrigem=='decimal' && $strTipoDestino == 'numeric' && $numTamanhoOrigem == $numTamanhoDestino && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraSqlServer && $objBancoDestino instanceof InfraMySql){

      if (($strTipoOrigem=='varchar' && $numTamanhoOrigem=='-1') && $strTipoDestino == 'longtext'){
        return true;
      }

      if ($strTipoOrigem=='numeric' && $strTipoDestino == 'decimal' && $numTamanhoOrigem == $numTamanhoDestino && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraOracle && $objBancoDestino instanceof InfraMySql){
      
      if ($strTipoOrigem == 'number' && ($strTipoDestino=='int' || $strTipoDestino=='bigint')){
        return true;
      }

      if ($strTipoOrigem == 'number' && $strTipoDestino=='decimal' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
      	return true;
      }
      
      if ($strTipoOrigem == 'varchar2' && $strTipoDestino=='varchar' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }
      
      if ($strTipoOrigem == 'clob' && $strTipoDestino=='longtext'){
        return true;
      }

      if ($strTipoOrigem == 'date' && $strTipoDestino=='datetime'){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraOracle && $objBancoDestino instanceof InfraSqlServer){
      
      if ($strTipoOrigem == 'number' && ($strTipoDestino=='int' || $strTipoDestino=='bigint')){
        return true;
      }

      if ($strTipoOrigem == 'number' && $strTipoDestino=='numeric' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

      if ($strTipoOrigem == 'varchar2' && $strTipoDestino=='varchar' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem == 'clob' && ($strTipoDestino=='varchar' && $numTamanhoDestino=='-1')){
        return true;
      }

      if ($strTipoOrigem == 'date' && $strTipoDestino=='datetime'){
        return true;
      }
      
    }elseif ($objBancoOrigem instanceof InfraMySql && $objBancoDestino instanceof InfraOracle){
      
      if (($strTipoOrigem=='int' || $strTipoOrigem=='bigint') && $strTipoDestino == 'number'){
        return true;
      }
    
      if ($strTipoOrigem == 'decimal' && $strTipoDestino=='number' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
      	return true;
      }
      
      if ($strTipoOrigem == 'varchar' && $strTipoDestino=='varchar2' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }
    
      if ($strTipoOrigem == 'longtext' && $strTipoDestino=='clob'){
        return true;
      }
    
      if ($strTipoOrigem == 'datetime' && $strTipoDestino=='date'){
        return true;
      }
      
    }elseif ($objBancoOrigem instanceof InfraSqlServer && $objBancoDestino instanceof InfraOracle){
    
      if (($strTipoOrigem=='int' || $strTipoOrigem=='bigint') && $strTipoDestino == 'number'){
        return true;
      }
          
      if ($strTipoOrigem == 'numeric' && $strTipoDestino=='number' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
      	return true;
      }
      
      if ($strTipoOrigem == 'varchar' && $strTipoDestino=='varchar2' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }
    
      if (($strTipoOrigem=='varchar' && $numTamanhoOrigem=='-1') && $strTipoDestino == 'clob'){
        return true;
      }
    
      if ($strTipoOrigem == 'datetime' && $strTipoDestino=='date'){
        return true;
      }
    }elseif ($objBancoOrigem instanceof InfraPostgreSql && $objBancoDestino instanceof InfraMySql){

      if ($strTipoOrigem=='integer' && $strTipoDestino == 'int'){
        return true;
      }

      if ($strTipoOrigem=='bigint' && $strTipoDestino == 'bigint'){
        return true;
      }

      if ($strTipoOrigem=='numeric' && $strTipoDestino == 'decimal' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

      if ($strTipoOrigem=='character' && $strTipoDestino == 'char' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='character varying' && $strTipoDestino == 'varchar' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='timestamp without time zone' && $strTipoDestino == 'datetime'){
        return true;
      }

      if ($strTipoOrigem=='text' && $strTipoDestino == 'longtext'){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraMySql && $objBancoDestino instanceof InfraPostgreSql){

      if ($strTipoOrigem=='int' && $strTipoDestino == 'integer'){
        return true;
      }

      if ($strTipoOrigem=='bigint' && $strTipoDestino == 'bigint'){
        return true;
      }

      if ($strTipoOrigem=='decimal' && $strTipoDestino == 'numeric' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

      if ($strTipoOrigem=='char' && $strTipoDestino == 'character' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='varchar' && $strTipoDestino == 'character varying' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='datetime' && $strTipoDestino == 'timestamp without time zone'){
        return true;
      }

      if ($strTipoOrigem=='longtext' && $strTipoDestino == 'text'){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraPostgreSql && $objBancoDestino instanceof InfraSqlServer){

      if ($strTipoOrigem=='integer' && $strTipoDestino == 'int'){
        return true;
      }

      if ($strTipoOrigem=='bigint' && $strTipoDestino == 'bigint'){
        return true;
      }

      if ($strTipoOrigem=='character' && $strTipoDestino == 'char' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='timestamp without time zone' && $strTipoDestino == 'datetime'){
        return true;
      }

      if ($strTipoOrigem=='character varying' && $strTipoDestino == 'varchar' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='text' && $strTipoDestino == 'varchar' && $numTamanhoDestino == '-1'){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraSqlServer && $objBancoDestino instanceof InfraPostgreSql){

      if ($strTipoOrigem=='int' && $strTipoDestino == 'integer'){
        return true;
      }

      if ($strTipoOrigem=='bigint' && $strTipoDestino == 'bigint'){
        return true;
      }

      if ($strTipoOrigem=='decimal' && $strTipoDestino == 'numeric' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

      if ($strTipoOrigem=='char' && $strTipoDestino == 'character' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='varchar' && $strTipoDestino == 'character varying' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem=='datetime' && $strTipoDestino == 'timestamp without time zone'){
        return true;
      }

      if ($strTipoOrigem=='varchar' && $numTamanhoOrigem == '-1' && $strTipoDestino == 'text'){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraPostgreSql && $objBancoDestino instanceof InfraOracle){

      if (($strTipoOrigem=='integer' || $strTipoOrigem=='bigint')  && $strTipoDestino == 'number'){
        return true;
      }

      if ($strTipoOrigem == 'character' && $strTipoDestino=='char' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem == 'character varying' && $strTipoDestino=='varchar2' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem == 'numeric' && $strTipoDestino=='number' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

      if ($strTipoOrigem == 'text' && $strTipoDestino=='clob'){
        return true;
      }

      if ($strTipoOrigem == 'timestamp without time zone' && $strTipoDestino=='date'){
        return true;
      }

    }elseif ($objBancoOrigem instanceof InfraOracle && $objBancoDestino instanceof InfraPostgreSql){

      if ($strTipoOrigem == 'number' && ($strTipoDestino=='integer' || $strTipoDestino=='bigint')){
        return true;
      }

      if ($strTipoOrigem == 'char' && $strTipoDestino=='character' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem == 'varchar2' && $strTipoDestino=='character varying' && $numTamanhoOrigem == $numTamanhoDestino){
        return true;
      }

      if ($strTipoOrigem == 'number' && $strTipoDestino=='numeric' && $numPrecisaoOrigem==$numPrecisaoDestino && $numEscalaOrigem==$numEscalaDestino){
        return true;
      }

      if ($strTipoOrigem == 'clob' && $strTipoDestino=='text'){
        return true;
      }

      if ($strTipoOrigem == 'date' && $strTipoDestino=='timestamp without time zone'){
        return true;
      }
    }

    return false;
  }
}
?>