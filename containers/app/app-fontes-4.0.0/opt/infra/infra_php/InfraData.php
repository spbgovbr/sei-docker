<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 24/10/2006 - CRIADO POR mga@trf4.gov.br
 * 20/04/2007 - ALTERADO POR cle@trf4.gov.br
 * @package infra_php
 */
class InfraData
{
  private static $instance = null;

  public static $DIA = 0;
  public static $MES = 1;
  public static $ANO = 2;
  public static $HOR = 3;
  public static $MIN = 4;
  public static $SEG = 5;

  public static $UNIDADE_DIAS = 'd';
  public static $UNIDADE_MESES = 'm';
  public static $UNIDADE_ANOS = 'a';
  public static $UNIDADE_SEMANAS = 'e';
  public static $UNIDADE_HORAS = 'h';
  public static $UNIDADE_MINUTOS = 'i';
  public static $UNIDADE_SEGUNDOS = 'u';

  public static $SENTIDO_ADIANTE = '+';
  public static $SENTIDO_ATRAS = '-';

  public static function getInstance()
  {
    if (self::$instance===null) {
      self::$instance = new InfraData();
    }
    return self::$instance;
  }

  private function __construct()
  {
  }

  /**
   * @param $strData
   * @return bool
   */
  public static function validarDataHora($strData)
  {

    if ($strData===null || trim($strData)==="") {
      return true;
    }

    // [1-31]/[1-12]/[1800-2100] [0-23]:[0-59]:[0-59]
    $strData = trim($strData);

    if (strlen($strData)===10) {
      $strData .= ' 00:00:00';
    }


    //Valida a data
    if (!self::validarData($strData)) {
      return false;
    }


    $arrData = self::decomporData($strData);

    if ($arrData===false) {
      return false;
    }

    if (!isset($arrData[self::$HOR], $arrData[self::$MIN], $arrData[self::$SEG])) {
      return false;
    }

    if ($arrData[self::$HOR]<0 || $arrData[self::$HOR]>23 ||
        $arrData[self::$MIN]<0 || $arrData[self::$MIN]>59 ||
        $arrData[self::$SEG]<0 || $arrData[self::$SEG]>59) {
      return false;
    }

    return true;
  }

  /**
   * @param $strData
   * @return bool
   */
  public static function validarData($strData)
  {
    if ($strData===null || trim($strData)==='') {
      return true;
    }
    $bissexto = 0;

    $arrData = self::decomporData($strData);

    if ($arrData==false) {
      return false;
    }

    $dia = $arrData[self::$DIA];
    $mes = $arrData[self::$MES];
    $ano = $arrData[self::$ANO];

    if (($ano>=1800) && ($ano<=5000)) {
      switch ($mes) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
          if ($dia<=31) {
            return true;
          }
          break;
        case 4:
        case 6:
        case 9:
        case 11:
          if ($dia<=30) {
            return true;
          }
          break;
        case 2:
          /*
           Se ano módulo 400 é 0 então bissexto
           Senão se ano módulo 100 é 0 então não_bissexto
           Senão se ano módulo 4 é 0 então bissexto
           Senão não_bissexto
          
          */
          if ($ano % 400===0) {
            $bissexto = 1;
          } else if ($ano % 100===0) {
            $bissexto = 0;
          } else if ($ano % 4===0) {
            $bissexto = 1;
          } else {
            $bissexto = 0;
          }
          /*
          if (($ano % 4 == 0) || ($ano % 100 == 0) || ($ano % 400 == 0)) {
              $bissexto = 1; 
          }
          */
          if (($bissexto===1) && ($dia<=29)) {
            return true;
          }
          if (($bissexto!==1) && ($dia<=28)) {
            return true;
          }
          break;
      }
    }
    return false;
  }

  /**
   * Retorna a diferença em dias entre as datas:
   * a) se > 0 datafim posterior a dataini
   * b) se = 0 datafim = dataini
   * c) se < 0 datafim anterior a dataini
   * Obs: Quando possível prefira a compararDataSimples() pois a validarData() é mais lenta e só funciona com datas até 2038.
   * 
   * @param $strDataIni
   * @param $strDataFim
   * @return float|null
   */
  public static function compararDatas($strDataIni, $strDataFim)
  {

    if ($strDataIni===null || $strDataFim===null || trim($strDataIni)==='' || trim($strDataFim)==='') {
      return null;
    }

    $arrDataIni = explode('/', $strDataIni);
    $arrDataFim = explode('/', $strDataFim);

    //usa gmmktime para evitar interferencia do horario de verao
    $iniDate = gmmktime(0, 0, 0, (int)$arrDataIni[1], (int)$arrDataIni[0], (int)$arrDataIni[2]);
    $fimDate = gmmktime(0, 0, 0, (int)$arrDataFim[1], (int)$arrDataFim[0], (int)$arrDataFim[2]);

    return round(($fimDate - $iniDate) / 86400);
  }

  /**Retorna:
   * a) +1, se datafim posterior a dataini
   * b)  0, se datafim = dataini
   * c) -1, se datafim anterior a dataini
   * mais rápido que o compararDatas e sem restrição de ano (compararDatas só aceita ano até 2038)
   * @param string $strDataIni
   * @param string $strDataFim
   * @return int|null
   */ 
  public static function compararDatasSimples($strDataIni, $strDataFim)
  {

    if ($strDataIni===null || $strDataFim===null || trim($strDataIni)==="" || trim($strDataFim)==="") {
      return null;
    }

    $arrDataIni = explode('/', $strDataIni);
    $arrDataFim = explode('/', $strDataFim);

    $iniNumDate = (int)$arrDataIni[2] . $arrDataIni[1] . $arrDataIni[0];
    $fimNumDate = (int)$arrDataFim[2] . $arrDataFim[1] . $arrDataFim[0];

    $numResult = $fimNumDate - $iniNumDate;

    if ($numResult<0) {
      return - 1;
    }
    if ($numResult>0) {
      return 1;
    }

    return 0;

  }

  /**
   * Retorna a diferença em segundos entre as datas:
   * a) se > 0 datafim posterior a dataini
   * b) se = 0 datafim = dataini
   * c) se < 0 datafim anterior a dataini
   * OBS: ano entre 1901 e 2037
   * 
   * @param $strDataHoraIni
   * @param $strDataHoraFim
   * @return int|null
   */
  public static function compararDataHora($strDataHoraIni, $strDataHoraFim)
  {

    if ($strDataHoraIni===null || $strDataHoraFim===null || trim($strDataHoraIni)==='' || trim($strDataHoraFim)==='') {
      return null;
    }

    $arrDataIni = explode('/', $strDataHoraIni);
    $arrDataFim = explode('/', $strDataHoraFim);


    if (strlen($arrDataIni[2])===13) {
      $arrHoraIni = explode(':', substr($arrDataIni[2], 5));
      $arrDataIni[2] = substr($arrDataIni[2], 0, 4);
    } else {
      $arrHoraIni = array(0, 0, 0);
    }

    if (strlen($arrDataFim[2])===13) {
      $arrHoraFim = explode(':', substr($arrDataFim[2], 5));
      $arrDataFim[2] = substr($arrDataFim[2], 0, 4);
    } else {
      $arrHoraFim = array(0, 0, 0);
    }

    //usa gmmktime para evitar interferencia do horario de verao
    $iniDate = gmmktime((int)$arrHoraIni[0], (int)$arrHoraIni[1], (int)$arrHoraIni[2], (int)$arrDataIni[1], (int)$arrDataIni[0], (int)$arrDataIni[2]);
    $fimDate = gmmktime((int)$arrHoraFim[0], (int)$arrHoraFim[1], (int)$arrHoraFim[2], (int)$arrDataFim[1], (int)$arrDataFim[0], (int)$arrDataFim[2]);

    return $fimDate - $iniDate;
  }

  /**
   * Retorna a data atual no formato dd/mm/aaaa 
   * @return false|string
   */
  public static function getStrDataAtual()
  {
    return date('d/m/Y');
  }

  /**
   * Retorna a data hora atual no formato dd/mm/aaaa hh:mm:ss
   * @return false|string
   */
  public static function getStrDataHoraAtual()
  {
    return date('d/m/Y H:i:s');
  }

  /**
   * Retorna a hora atual no formato hh:mm:ss
   * @return false|string
   */
  public static function getStrHoraAtual()
  {
    return date('H:i:s');
  }

  /**
   * RETORNA EXTENSO DA DATA
   * @param $data
   * @return string
   */
  //26 de outubro de 2018
  public static function formatarExtenso($data)
  {
    $arrMeses = array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');
    $arrPartesData = explode('/', $data);
    return $arrPartesData[0] . ' de ' . $arrMeses[$arrPartesData[1] - 1] . ' de ' . $arrPartesData[2];
  }

  //26 de outubro de 2018 às 14:31
  public static function formatarExtenso2($strDataHora)
  {
    $strExtenso = InfraData::formatarExtenso(substr($strDataHora,0,10));
    $strHora = substr($strDataHora,11,5);
    return $strExtenso.' às '.$strHora;
  }

  //Sexta-feira, 26 de outubro de 2018 às 14:31.
  public static function formatarExtenso3($strDataHora)
  {
    $strDiaSemana = InfraData::obterDescricaoDiaSemana(substr($strDataHora,0,10));
    $strDiaSemana = InfraString::transformarCaixaAlta(substr($strDiaSemana,0,1)).substr($strDiaSemana,1);
    $strExtenso = InfraData::formatarExtenso(substr($strDataHora,0,10));
    $strHora = substr($strDataHora,11,5);
    return $strDiaSemana.', '.$strExtenso.' às '.$strHora.'.';
  }

  //Sexta-feira, 26 de outubro às 14:31.
  public static function formatarExtenso4($strDataHora){
    $strDiaSemana = InfraData::obterDescricaoDiaSemana(substr($strDataHora, 0, 10));
    $strDiaSemana = InfraString::transformarCaixaAlta(substr($strDiaSemana,0,1)).substr($strDiaSemana,1);
    $strMes = InfraString::transformarCaixaBaixa(InfraData::descreverMes(substr($strDataHora, 3, 2)));

    return $strDiaSemana.', '.substr($strDataHora, 0, 2).' de '.$strMes.' às '.substr($strDataHora, 11, 5).'.';
  }

  /**
   * RETORNA NÚMERO CORERSPONDENTE À SIGLA DO MÊS
   * @param $mes
   * @return mixed|null
   */
  public static function obterMesNumerico($mes)
  {
    $vetor_meses_sig = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $vetor_meses_num = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $numMeses = count($vetor_meses_sig);
    for ($i = 0; $i<$numMeses; $i ++) {
      if (strtoupper($mes)==strtoupper($vetor_meses_sig[$i])) {
        return $vetor_meses_num[$i];
      }
    }
    return null;
  }

  /**
   * Retorna a sigla (Brasil) para o mês numérico
   * @param $mes
   * @return mixed|null
   */
  public static function obterMesSiglaBR($mes)
  {
    $vetor_meses_sig = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
    $vetor_meses_num = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $numMeses = count($vetor_meses_num);
    for ($i = 0; $i<$numMeses; $i ++) {
      if ($mes==$vetor_meses_num[$i]) {
        return $vetor_meses_sig[$i];
      }
    }
    return null;
  }

  /**
   * @param $strHora
   * @return bool
   */
  public static function validarHora($strHora)
  {
    $arrHora = explode(':', $strHora);
    if (!is_numeric($arrHora[0]) ||
        !is_numeric($arrHora[1]) ||
        !is_numeric($arrHora[2])) {
      return false;
    }

    if ((int)$arrHora[0]<0 || (int)$arrHora[0]>23 ||
        (int)$arrHora[1]<0 || (int)$arrHora[1]>59 ||
        (int)$arrHora[2]<0 || (int)$arrHora[2]>59) {
      return false;
    }

    return true;

  }

  /**
   * @param $strDataHora
   * @return array|bool
   */
  public static function decomporData($strDataHora)
  {

    // dd/mm/aaaa hh:mm:ss
    $arr = explode('/', $strDataHora);

    //trata hora
    if (strlen($arr[self::$ANO])>4) {
      $strHora = substr($arr[self::$ANO], 4);
      $arr[self::$ANO] = substr($arr[self::$ANO], 0, 4);
      $arrHora = explode(':', $strHora);
      if (!is_numeric($arrHora[0]) ||
          !is_numeric($arrHora[1]) ||
          !is_numeric($arrHora[2])) {
        return false;
      }
      $arr[self::$HOR] = (int)$arrHora[0];
      $arr[self::$MIN] = (int)$arrHora[1];
      $arr[self::$SEG] = (int)$arrHora[2];
    }

    if (!is_numeric($arr[self::$DIA]) ||
        !is_numeric($arr[self::$MES]) ||
        !is_numeric($arr[self::$ANO])) {
      return false;
    }

    $arr[self::$DIA] = (int)$arr[self::$DIA];
    $arr[self::$MES] = (int)$arr[self::$MES];
    $arr[self::$ANO] = (int)$arr[self::$ANO];

    return $arr;
  }

  /**
   * @param $quantidade
   * @param $unidade
   * @param $sentido
   * @param null $strData
   * @return false|string
   * @throws InfraException
   */
  public static function calcularData($quantidade, $unidade, $sentido, $strData = null)
  {

    if ($strData===null) {
      $strData = self::getStrDataAtual();
    }else if (!self::validarData($strData)){
      throw new InfraException('Data fornecida para cálculo inválida.');
    }

    $arr = self::decomporData($strData);

    if ($arr===false) {
      throw new InfraException('Erro obtendo dados da data fornecida para cálculo.');
    }

    $dia = $arr[self::$DIA];
    $mes = $arr[self::$MES];
    $ano = $arr[self::$ANO];

    if (isset($arr[self::$HOR],$arr[self::$MIN],$arr[self::$SEG])) {
      $hor = $arr[self::$HOR];
      $min = $arr[self::$MIN];
      $seg = $arr[self::$SEG];
    } else {
      $hor = 0;
      $min = 0;
      $seg = 0;
    }


    if ($sentido!==self::$SENTIDO_ADIANTE && $sentido!==self::$SENTIDO_ATRAS) {
      throw new InfraException('Sentido inválido no cálculo da data.');
    }

    switch ($unidade) {
      case self::$UNIDADE_SEGUNDOS:
        if ($sentido===self::$SENTIDO_ADIANTE) {
          $seg += $quantidade;
        } else {
          $seg -= $quantidade;
        }
        break; //seconds

      case self::$UNIDADE_MINUTOS:
        if ($sentido===self::$SENTIDO_ADIANTE) {
          $min += $quantidade;
        } else {
          $min -= $quantidade;
        }
        break; //minutes

      case self::$UNIDADE_HORAS:
        if ($sentido===self::$SENTIDO_ADIANTE) {
          $hor += $quantidade;
        } else {
          $hor -= $quantidade;
        }
        break; //hours

      case self::$UNIDADE_DIAS:
        if ($sentido===self::$SENTIDO_ADIANTE) {
          $dia += $quantidade;
        } else {
          $dia -= $quantidade;
        }
        break; //days

      case self::$UNIDADE_SEMANAS:
        if ($sentido===self::$SENTIDO_ADIANTE) {
          $dia += ($quantidade * 7);
        } else {
          $dia -= ($quantidade * 7);
        }
        break; //week

      case self::$UNIDADE_MESES:
        if ($sentido===self::$SENTIDO_ADIANTE) {
          $mes += $quantidade;
        } else {
          $mes -= $quantidade;
        }
        break; //month

      case self::$UNIDADE_ANOS:
        if ($sentido===self::$SENTIDO_ADIANTE) {
          $ano += $quantidade;
        } else {
          $ano -= $quantidade;
        }
        break; //year

      default:
        throw new InfraException('Unidade inválida no cálculo da data.');
    }

    $dateTime = mktime($hor, $min, $seg, $mes, $dia, $ano);

    //Se tinha hora OU a data calculada tem hora
    if (($hor>0 || $min>0 || $seg>0) || isset($arr[self::$HOR],$arr[self::$MIN],$arr[self::$SEG])) {
      return date('d/m/Y H:i:s', $dateTime);
    }

    return date('d/m/Y', $dateTime);
  }

  /**
   * @param $numMes
   * @return mixed|null
   */
  public static function descreverMes($numMes)
  {
    $numMes = (int)$numMes;

    if ($numMes<1 || $numMes>12) {
      return null;
    }

    //Meses do ano
    $arrMeses = array();
    $arrMeses[1] = 'Janeiro';
    $arrMeses[2] = 'Fevereiro';
    $arrMeses[3] = 'Março';
    $arrMeses[4] = 'Abril';
    $arrMeses[5] = 'Maio';
    $arrMeses[6] = 'Junho';
    $arrMeses[7] = 'Julho';
    $arrMeses[8] = 'Agosto';
    $arrMeses[9] = 'Setembro';
    $arrMeses[10] = 'Outubro';
    $arrMeses[11] = 'Novembro';
    $arrMeses[12] = 'Dezembro';

    return $arrMeses[$numMes];
  }

  /**
   * @param $strData
   * @return false|string
   */
  public static function obterDescricaoDiaSemana($strData)
  {
    $ano = substr("$strData", 6, 4);
    $mes = substr("$strData", 3, 2);
    $dia = substr("$strData", 0, 2);

    $diaSemana = date('w', mktime(0, 0, 0, $mes, $dia, $ano));

    switch ($diaSemana) {
      case'0':
        $diaSemana = 'domingo';
        break;
      case'1':
        $diaSemana = 'segunda-feira';
        break;
      case'2':
        $diaSemana = 'terça-feira';
        break;
      case'3':
        $diaSemana = 'quarta-feira';
        break;
      case'4':
        $diaSemana = 'quinta-feira';
        break;
      case'5':
        $diaSemana = 'sexta-feira';
        break;
      case'6':
        $diaSemana = 'sábado';
        break;
    }

    return $diaSemana;
  }

  /**
   * @param $numMes
   * @param $numAno
   * @return array|null
   */
  public static function gerarIntervaloMes($numMes, $numAno)
  {
    $numDia = 1;
    if (($numAno>=1800) && ($numAno<=2100)) {
      switch ($numMes) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
          $numDia = 31;
          break;

        case 4:
        case 6:
        case 9:
        case 11:
          $numDia = 30;
          break;

        case 2:
          /*
          Se ano módulo 400 é 0 então bissexto
          Senão se ano módulo 100 é 0 então não_bissexto
          Senão se ano módulo 4 é 0 então bissexto
          Senão não_bissexto
          */
          if ($numAno % 400==0) {
            $numDia = 29;
          } else if ($numAno % 100==0) {
            $numDia = 28;
          } else if ($numAno % 4==0) {
            $numDia = 29;
          } else {
            $numDia = 28;
          }
          break;
        default:
          return null;
      }
      return array('DtaInicial' => ('01/' . $numMes . '/' . $numAno), 'DtaFinal' => ($numDia . '/' . $numMes . '/' . $numAno));
    } else {
      return null;
    }
  }


  /**
   * @param $numMes
   * @param $numAno
   * @return bool|string
   * @throws InfraException
   */
  public static function obterUltimoDiaMes($numMes, $numAno)
  {

    $numMes ++;

    if ($numMes==13) {
      $numMes = 1;
      $numAno ++;
    }

    return substr(self::calcularData(1, self::$DIA, self::$SENTIDO_ATRAS, '01/' . $numMes . '/' . $numAno), 0, 2);
  }

  /**
   * @param string $dtaDataInicio1
   * @param string $dtaDataFim1
   * @param string $dtaDataInicio2
   * @param string $dtaDataFim2
   * @return array|null
   * @throws InfraException
   */
  public static function obterInterseccaoDatas($dtaDataInicio1, $dtaDataFim1, $dtaDataInicio2, $dtaDataFim2)
  {
    if (self::compararDatasSimples($dtaDataInicio1, $dtaDataFim1)<0 || self::compararDatasSimples($dtaDataInicio2, $dtaDataFim2)<0) {
      // inconsistência nos intervalos    		
      throw new InfraException('Erro: Intervalos de datas passadas incorretos.');
    }
    if (self::compararDatasSimples($dtaDataInicio1, $dtaDataFim2)<0 || self::compararDatasSimples($dtaDataFim1, $dtaDataInicio2)>0) {
      // não há intersecção entre os dois perídos de datas
      return null;
    }
    if (self::compararDatasSimples($dtaDataInicio1, $dtaDataInicio2)<=0 && self::compararDatasSimples($dtaDataFim1, $dtaDataFim2)>=0) {
      // Data1 totalmente contida na Data2
      return array('dtaDataInicio' => $dtaDataInicio1, 'dtaDataFim' => $dtaDataFim1);
    }
    if (self::compararDatasSimples($dtaDataInicio1, $dtaDataInicio2)>=0 && self::compararDatasSimples($dtaDataFim1, $dtaDataFim2)<=0) {
      // Data2 totalmente contida na Data1
      return array('dtaDataInicio' => $dtaDataInicio2, 'dtaDataFim' => $dtaDataFim2);
    }
    if (self::compararDatasSimples($dtaDataInicio1, $dtaDataInicio2)>0 && self::compararDatasSimples($dtaDataFim1, $dtaDataFim2)>0) {
      return array('dtaDataInicio' => $dtaDataInicio2, 'dtaDataFim' => $dtaDataFim1);
    }
    if (self::compararDatasSimples($dtaDataInicio1, $dtaDataInicio2)<0 && self::compararDatasSimples($dtaDataFim1, $dtaDataFim2)<0) {
      return array('dtaDataInicio' => $dtaDataInicio1, 'dtaDataFim' => $dtaDataFim2);
    }
  }

  /**
   * Recebe uma data no formato 'DD/MM/YYYY hh:mm:ss' e converte para o formato timestamp do Excel
   * @param $strData A data em formar de string
   * Autor: Walter - wvl@jfsc.gov.br
   * @return float|int
   */
  public static function converterDataEmExcelTimestamp($strData)
  {
    $strData1 = str_replace('/', '-', $strData);
    $strData2 = substr($strData1, 6, 4) . '-' . substr($strData1, 3, 2) . '-' . substr($strData1, 0, 2) . ' ' . substr($strData1, 11);
    $defaultTimezone = date_default_timezone_get();
    date_default_timezone_set('UTC');
    $strData3 = strtotime($strData2);
    date_default_timezone_set($defaultTimezone);
    return ($strData3 / 86400 + 25569);
  }

  /**
   * Recebe uma data no formato 'DD/MM/YYYY' ou 'DD/MM/YYYY hh:mm:ss' e devolve booleano indicando se o ano é bissexto
   * @param $strData A data em formar de string
   * @return boolean Indicando se o ano é bissexto ou não
   */
  public static function verificarAnoBissexto($strData)
  {
    $arrData = self::decomporData($strData);
    $numAno = $arrData[self::$ANO];

    if ($numAno % 400==0) {
      return true;
    } else if ($numAno % 100==0) {
      return false;
    } else if ($numAno % 4==0) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Formata um timestamp em dias/horas/minutos/seguntos
   * @param $t valor do timestamp
   * @return string
   */
  public static function formatarTimestamp($t)
  {
    $ret = '';
    if ($t>=86400) {
      $dias = (int)($t / 86400);
      $t = $t % 86400;
      $ret .= $dias . 'd ';
    }

    if ($t>=3600) {
      $horas = (int)($t / 3600);
      $t = $t % 3600;
      //$ret .= (($horas<=9)?'0':'').$horas.'h ';	
      $ret .= $horas . 'h ';
    }

    if ($t>=60) {
      $minutos = (int)($t / 60);
      $t = $t % 60;
      //$ret .= (($minutos<=9)?'0':'').$minutos.'m ';	
      $ret .= $minutos . 'm ';
    }

    if ($t>0) {
      //$ret .= (($t<=9)?'0':'').$t.'s';
      $ret .= $t . 's';
    }
    return $ret;
  }

  /**
   * @param $strDataHora
   * @return false|int
   */
  public static function getTimestamp($strDataHora)
  {
    $arr = self::decomporData($strDataHora);
    return mktime($arr[self::$HOR], $arr[self::$MIN], $arr[self::$SEG], $arr[self::$MES], $arr[self::$DIA], $arr[self::$ANO]);
  }

  /**
   * @param $dtaInicial
   * @param $dtaFinal
   * @param InfraException $objInfraException
   * @param bool $bolDtaInicialFutura
   * @param bool $bolDtaFinalFutura
   */
  public static function validarPeriodo($dtaInicial, $dtaFinal, InfraException $objInfraException, $bolDtaInicialFutura = false, $bolDtaFinalFutura = false)
  {

    if (!InfraString::isBolVazia($dtaInicial) || !InfraString::isBolVazia($dtaFinal)) {

      if (InfraString::isBolVazia($dtaInicial) || InfraString::isBolVazia($dtaFinal)) {

        $objInfraException->adicionarValidacao('Período de datas incompleto.');

      } else {

        $bolValidaDataInicial = self::validarData($dtaInicial);
        $bolValidaDataFinal = self::validarData($dtaFinal);

        if (!$bolValidaDataInicial || !$bolValidaDataFinal) {

          if (!$bolValidaDataInicial) {
            $objInfraException->adicionarValidacao('Data inicial do período inválida.');
          }

          if (!$bolValidaDataFinal) {
            $objInfraException->adicionarValidacao('Data final do período inválida.');
          }

        } else {

          $bolFuturoDataInicial = !$bolDtaInicialFutura && (self::compararDatas(self::getStrDataAtual(), $dtaInicial)>0);
          $bolFuturoDataFinal = !$bolDtaFinalFutura && (self::compararDatas(self::getStrDataAtual(), $dtaFinal)>0);

          if ($bolFuturoDataInicial || $bolFuturoDataFinal) {

            if ($bolFuturoDataInicial) {
              $objInfraException->adicionarValidacao('Data inicial do período não pode ser futura.');
            }

            if ($bolFuturoDataFinal) {
              $objInfraException->adicionarValidacao('Data final do período não pode ser futura.');
            }

          } else {

            if (self::compararDatas($dtaFinal, $dtaInicial)>0) {
              $objInfraException->adicionarValidacao('Período de datas inválido.');
            }

          }
        }
      }
    }
  }

  /**
   * Para calcular a Terça-feira de Carnaval, basta subtrair 47 dias do Domingo de Páscoa.
   * @param $numAno
   * @return false|string
   * @throws InfraException
   */
  public static function buscarTercaCarnaval($numAno)
  {
    return self::calcularData(47, self::$UNIDADE_DIAS, self::$SENTIDO_ATRAS, self::buscarDomingoPascoa($numAno));
  }

  /**
   * Para calcular a Quinta-feira de Corpus Christi, soma-se 60 dias ao Domingo de Páscoa.
   * @param $numAno
   * @return false|string
   * @throws InfraException
   */
  public static function buscarCorpusChristi($numAno)
  {
    return self::calcularData(60, self::$UNIDADE_DIAS, self::$SENTIDO_ADIANTE, self::buscarDomingoPascoa($numAno));
  }


  /**
   * Busca o domingo de páscoa de um determinado ano, baseando o cálculo na fórmula de Gauss
   * @param $numAno
   * @return string
   */
  public static function buscarDomingoPascoa($numAno)
  {

    //Array que guarda valores necessários para o cálculo
    $arrValoresReferencia = array(array('AnoInicio' => 1582, 'AnoFim' => 1599, 'X' => 22, 'Y' => 2),
        array('AnoInicio' => 1600, 'AnoFim' => 1699, 'X' => 22, 'Y' => 2),
        array('AnoInicio' => 1700, 'AnoFim' => 1799, 'X' => 23, 'Y' => 3),
        array('AnoInicio' => 1800, 'AnoFim' => 1899, 'X' => 24, 'Y' => 4),
        array('AnoInicio' => 1900, 'AnoFim' => 1999, 'X' => 24, 'Y' => 5),
        array('AnoInicio' => 2000, 'AnoFim' => 2099, 'X' => 24, 'Y' => 5),
        array('AnoInicio' => 2100, 'AnoFim' => 2199, 'X' => 24, 'Y' => 6),
        array('AnoInicio' => 2200, 'AnoFim' => 2299, 'X' => 25, 'Y' => 7));

    foreach ($arrValoresReferencia as $item) {
      if (($numAno>=$item['AnoInicio']) && ($numAno<=$item['AnoFim'])) {
        $x = $item['X'];
        $y = $item['Y'];
        break;
      }
    }

//    a = ANO MOD 19
    $a = $numAno % 19;
//    b= ANO MOD 4
    $b = $numAno % 4;
//    c = ANO MOD 7
    $c = $numAno % 7;
//    d = (19 * a + X) MOD 30
    $d = (19 * $a + $x) % 30;

//    e = (2 * b + 4 * c + 6 * d + Y) MOD 7
    $e = (2 * $b + 4 * $c + 6 * $d + $y) % 7;

//    Se (d + e) > 9 então DIA = (d + e - 9) e MES = Abril
//    senão DIA = (d + e + 22) e MES = Março
    if (($d + $e)>9) {
      $numDia = ($d + $e) - 9;
      $numMes = 4;
    } else {
      $numDia = ($d + $e) + 22;
      $numMes = 3;
    }

//    Há dois casos particulares que ocorrem duas vezes por século:

//    Quando o domingo de Páscoa cair em Abril e o dia for 26, corrige-se para uma semana antes, ou seja, vai para dia 19;
//    Quando o domingo de Páscoa cair em Abril e o dia for 25 e o termo "d" for igual a 28, simultaneamente com "a" maior que 10, então o dia é corrigido para 18.
    if (($numMes==4)) {
      if ($numDia==26) {
        $numDia = 19;
      } else {
        if (($numDia==25) && ($d==28) && ($a>10)) {
          $numDia = 18;
        }
      }
    }

    return str_pad($numDia, 2, '0', STR_PAD_LEFT) . '/' . str_pad($numMes, 2, '0', STR_PAD_LEFT) . '/' . $numAno;
  }

  /**
   * Transforma data do formato "dd/mm/aaaa" para o formato aaaa-mm-dd hh:mm:ss do banco
   * @param $dataAConverter
   * @param bool $bolDataInicio - Se true: para usar com data inicial - acrescenta 00:00:00 ao final da data;
   *                            - Se false: para usar com data final  - acrescenta 23:59:59 ao final da data;
   * @return string
   */
  public static function formatarDataBanco($dataAConverter, $bolDataInicio = true)
  {
    $dataAConverter = trim($dataAConverter);
    $arrData = explode('/', $dataAConverter);
    $novaData = $arrData[2] . '-' . $arrData[1] . '-' . $arrData[0]; //formato aaaa-mm-dd

    if ($bolDataInicio) {
      return $novaData . ' 00:00:00';
    }

    return $novaData . ' 23:59:59';
  }

  /**
   * Retorna a menor data do array
   * @param array $arrDatas
   * @return string
   */
  public static function obterMenor($arrDatas)
  {
    $dtaMinData = null;
    if (count($arrDatas)) {
      $dtaMinData = $arrDatas[0];
      foreach ($arrDatas as $dtaData) {
        $dtaMinData = self::compararDatasSimples($dtaData, $dtaMinData)>0 ? $dtaData : $dtaMinData;
      }
    }
    return $dtaMinData;
  }

  /**
   * Retorna a maior data do array
   * @param array $arrDatas
   * @return string
   */
  public static function obterMaior($arrDatas)
  {
    $dtaMaxData = null;
    if (count($arrDatas)) {
      $dtaMaxData = $arrDatas[0];
      foreach ($arrDatas as $dtaData) {
        $dtaMaxData = self::compararDatasSimples($dtaMaxData, $dtaData)>0 ? $dtaData : $dtaMaxData;
      }
    }
    return $dtaMaxData;
  }

  /**
   * Retorna:
   * a) +1, se datahora_fim posterior a datahora_ini
   * b)  0, se datahora_fim = datahora_ini
   * c) -1, se datahora_fim anterior a datahora_ini
   * mais rápido que o compararDatas e sem restrição de ano (compararDatas só aceita ano até 2038)
   *
   * @param $strDthIni
   * @param $strDthFim
   * @return int|null
   */
  public static function compararDataHorasSimples($strDthIni, $strDthFim){

    if ($strDthIni===null || $strDthFim===null || trim($strDthIni)==='' || trim($strDthFim)==='') {
      return null;
    }

    //separa data e hora
    $arrDthIni = explode(' ',$strDthIni);
    $arrDthFim =  explode(' ',$strDthFim);

    //processa data
    $arrDataIni = explode('/',$arrDthIni[0]);
    $arrDataFim =  explode('/',$arrDthFim[0]);

    //processa hora
    $arrHoraIni = explode(':',$arrDthIni[1]);
    $arrHoraFim =  explode(':',$arrDthFim[1]);

    //cria números
    $iniNumDth = (int) $arrDataIni[2].$arrDataIni[1].$arrDataIni[0].$arrHoraIni[0].$arrHoraIni[1].$arrHoraIni[2];
    $fimNumDth = (int) $arrDataFim[2].$arrDataFim[1].$arrDataFim[0].$arrHoraFim[0].$arrHoraFim[1].$arrHoraFim[2];

    $numResult = $fimNumDth - $iniNumDth;

    if ($numResult<0) {
      return -1;
    }
    if($numResult>0) {
      return 1;
    }
    return 0;
  }

}

?>