<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 08/08/2012 - criado por mga
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraNavegadorBD extends InfraBD
{

    public function __construct(InfraIBanco $objInfraIBanco)
    {
        parent::__construct($objInfraIBanco);
    }

    public function pesquisar(InfraNavegadorDTO $parObjInfraNavegadorDTO)
    {
        try {
            $sql = '';
            $sql .= 'SELECT COUNT(*) as total_acessos, identificacao ';

            if ($parObjInfraNavegadorDTO->getStrSinIgnorarVersao() == 'N') {
                $sql .= ', versao ';
            }

            $sql .= 'FROM infra_navegador ';

            if ($parObjInfraNavegadorDTO->isSetDthInicial() && $parObjInfraNavegadorDTO->isSetDthFinal()) {
                $sql .= 'WHERE dth_acesso >= ' . $this->getObjInfraIBanco()->formatarGravacaoDth(
                        $parObjInfraNavegadorDTO->getDthInicial()
                    ) . ' ';
                $sql .= 'AND dth_acesso <= ' . $this->getObjInfraIBanco()->formatarGravacaoDth(
                        $parObjInfraNavegadorDTO->getDthFinal()
                    ) . ' ';
            }

            $sql .= 'GROUP BY identificacao ';

            if ($parObjInfraNavegadorDTO->getStrSinIgnorarVersao() == 'N') {
                $sql .= ', versao ';
            }

            $sql .= 'ORDER BY ';

            if ($parObjInfraNavegadorDTO->isOrdDblTotalAcessos()) {
                $sql .= 'total_acessos ' . $parObjInfraNavegadorDTO->getOrdDblTotalAcessos();
            }

            if ($parObjInfraNavegadorDTO->isOrdStrIdentificacao()) {
                $sql .= 'identificacao ' . $parObjInfraNavegadorDTO->getOrdStrIdentificacao();
            }

            //die($sql);

            $rs = $this->getObjInfraIBanco()->consultarSql($sql);

            $arrObjInfraNavegadorDTO = array();

            foreach ($rs as $item) {
                $objInfraNavegadorDTO = new InfraNavegadorDTO();
                $objInfraNavegadorDTO->setDblTotalAcessos(
                    $this->getObjInfraIBanco()->formatarLeituraDbl($item['total_acessos'])
                );
                $objInfraNavegadorDTO->setStrIdentificacao(
                    $this->getObjInfraIBanco()->formatarLeituraStr($item['identificacao'])
                );

                if ($parObjInfraNavegadorDTO->getStrSinIgnorarVersao() == 'N') {
                    $objInfraNavegadorDTO->setStrVersao(
                        $this->getObjInfraIBanco()->formatarLeituraStr($item['versao'])
                    );
                }

                $arrObjInfraNavegadorDTO[] = $objInfraNavegadorDTO;
            }

            return $arrObjInfraNavegadorDTO;
        } catch (Exception $e) {
            throw new InfraException('Erro pesquisando navegadores', $e);
        }
    }

}

