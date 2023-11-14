<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/04/2021 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.0
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraCaptchaINT extends InfraINT
{

    public static $ESCALA_ANUAL = 'A';
    public static $ESCALA_MENSAL = 'M';
    public static $ESCALA_DIARIO = 'D';

    public static function montarSelectAnos($numAno)
    {
        $objInfraCaptchaRN = new InfraCaptchaRN();

        $objInfraCaptchaDTO = new InfraCaptchaDTO();
        $objInfraCaptchaDTO->setNumMaxRegistrosRetorno(1);
        $objInfraCaptchaDTO->retNumAno();
        $objInfraCaptchaDTO->setOrdNumAno(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objInfraCaptchaDTOInicio = $objInfraCaptchaRN->consultar($objInfraCaptchaDTO);

        $objInfraCaptchaDTO->setOrdNumAno(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objInfraCaptchaDTOFim = $objInfraCaptchaRN->consultar($objInfraCaptchaDTO);

        $arrAnos = array();
        if ($objInfraCaptchaDTOInicio != null && $objInfraCaptchaDTOFim != null) {
            $numAnoInicio = $objInfraCaptchaDTOInicio->getNumAno();
            $numAnoFim = $objInfraCaptchaDTOFim->getNumAno();
            while ($numAnoInicio <= $numAnoFim) {
                $arrAnos[$numAnoInicio] = $numAnoInicio;
                $numAnoInicio++;
            }
        }

        return parent::montarSelectArray('', 'Todos', $numAno, $arrAnos);
    }


    public static function montarSelectIdentificacao($strIdentificacao)
    {
        $objInfraCaptchaDTO = new InfraCaptchaDTO();
        $objInfraCaptchaDTO->setDistinct(true);
        $objInfraCaptchaDTO->retStrIdentificacao();
        $objInfraCaptchaDTO->setOrdStrIdentificacao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objInfraCaptchaRN = new InfraCaptchaRN();
        $arrObjInfraCaptchaDTO = $objInfraCaptchaRN->listar($objInfraCaptchaDTO);

        return parent::montarSelectArrInfraDTO(
            '',
            'Todos',
            $strIdentificacao,
            $arrObjInfraCaptchaDTO,
            'Identificacao',
            'Identificacao'
        );
    }


    public static function montarSelectStaEscala($strStaEscala, $objInfraCaptchaDTO)
    {
        $arrEscala = array();
        if (!$objInfraCaptchaDTO->isSetNumAno()) {
            $arrEscala[self::$ESCALA_ANUAL] = 'Anual';
            $arrEscala[self::$ESCALA_MENSAL] = 'Mensal';
        } else {
            $arrEscala[self::$ESCALA_MENSAL] = 'Mensal';
            $arrEscala[self::$ESCALA_DIARIO] = 'Diário';
        }
        return parent::montarSelectArray(null, null, $strStaEscala, $arrEscala);
    }
}
