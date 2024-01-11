<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/04/2021 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.0
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraCaptchaBD extends InfraBD
{

    public function __construct(InfraIBanco $objInfraIBanco)
    {
        parent::__construct($objInfraIBanco);
    }

    public function registrar(InfraCaptchaDTO $objInfraCaptchaDTO)
    {
        try {
            $dtaAtual = InfraData::getStrDataAtual();

            $dia = substr($dtaAtual, 0, 2);
            $mes = substr($dtaAtual, 3, 2);
            $ano = substr($dtaAtual, 6, 4);

            $sql = 'update infra_captcha set ';

            if ($objInfraCaptchaDTO->getDblAcertos() == 1) {
                $sql .= ' acertos = acertos + 1';
            } else {
                $sql .= ' erros = erros + 1';
            }

            $sql .= ' where dia=' . $dia . ' and mes=' . $mes . ' and ano=' . $ano . ' and identificacao=' . $this->getObjInfraIBanco()->formatarGravacaoStr($objInfraCaptchaDTO->getStrIdentificacao());

            if ($objInfraCaptchaDTO->getDblAcertos() == 1) {
                $sql .= ' and acertos < 1000000000';
            } else {
                $sql .= ' and erros < 1000000000';
            }

            $ret = $this->getObjInfraIBanco()->executarSql($sql);

            if ($ret == 0) {
                $dto = new InfraCaptchaDTO();
                $dto->setNumDia($dia);
                $dto->setNumMes($mes);
                $dto->setNumAno($ano);
                $dto->setStrIdentificacao($objInfraCaptchaDTO->getStrIdentificacao());
                $dto->setDblAcertos($objInfraCaptchaDTO->getDblAcertos());
                $dto->setDblErros($objInfraCaptchaDTO->getDblErros());
                $this->cadastrar($dto);
            }
        } catch (Exception $e) {
            throw new InfraException('Erro registrando acesso captcha.', $e);
        }
    }
}
