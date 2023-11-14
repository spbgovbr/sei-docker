<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/06/2006 - criado por MGA
 *
 * @package infra_php
 */


class InfraCalendario
{

    private $objInfraIBanco = null;

    public function __construct(InfraIBanco $objInfraIBanco)
    {
        $this->objInfraIBanco = $objInfraIBanco;
    }

    private function getObjInfraIBanco()
    {
        return $this->objInfraIBanco;
    }

    public function isBolFeriado($dta)
    {
        try {
            $this->getObjInfraIBanco()->abrirConexao();

            $sql = 'SELECT * FROM calendario WHERE dat_feriado = ' . $this->getObjInfraIBanco()->formatarGravacaoDta(
                    $dta
                );

            $rs = $this->getObjInfraIBanco()->consultarSql($sql);

            $this->getObjInfraIBanco()->fecharConexao();

            if (count($rs)) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            try {
                if ($this->getObjInfraIBanco() != null) {
                    $this->getObjInfraIBanco()->fecharConexao();
                }
            } catch (Exception $e2) {
                //Nao trata para evitar a perda do erro original
            }
            throw new InfraException('Erro verificando feriado.', $e);
        }
    }
}
