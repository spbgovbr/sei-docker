<?php

/**
 * Created by PhpStorm.
 * User: bcu
 * Date: 15/02/2018
 * Time: 11:14
 */
include_once 'InfraData.php';

class InfraDataTest extends PHPUnit_Framework_TestCase
{

    public function testValidarDataHora()
    {
    }

    public function testValidarData()
    {
    }

    public function testCompararDatas()
    {
    }

    public function testCompararDatasSimples()
    {
    }

    public function testCompararDataHora()
    {
    }

    public function testGetStrDataAtual()
    {
    }

    public function testGetStrDataHoraAtual()
    {
    }

    public function testGetStrHoraAtual()
    {
    }

    public function testFormatarExtenso()
    {
    }

    public function testObterMesNumerico()
    {
    }

    public function testObterMesSiglaBR()
    {
    }

    public function testValidarHora()
    {
    }

    public function testDecomporData()
    {
    }

    public function testCalcularData()
    {
    }

    public function testDescreverMes()
    {
    }

    public function testObterDescricaoDiaSemana()
    {
    }

    public function testGerarIntervaloMes()
    {
    }

    public function testObterUltimoDiaMes()
    {
    }

    public function testObterInterseccaoDatas()
    {
    }

    public function testConverterDataEmExcelTimestamp()
    {
    }

    public function testVerificarAnoBissexto()
    {
    }

    public function testFormatarTimestamp()
    {
    }

    public function testGetTimestamp()
    {
    }

    public function testValidarPeriodo()
    {
    }

    public function testBuscarTercaCarnaval()
    {
    }

    public function testBuscarCorpusChristi()
    {
    }

    public function testBuscarDomingoPascoa()
    {
    }

    public function testFormatarDataBanco()
    {
    }

    public function testObterMenor()
    {
    }

    public function testObterMaior()
    {
    }

    public function testCompararDataHorasSimples()
    {
        $this->assertEquals(0, InfraData::compararDataHorasSimples('31/12/2018 10:16:32', '31/12/2018 10:16:32'));
        $this->assertEquals(1, InfraData::compararDataHorasSimples('31/12/2018 10:16:32', '31/12/2018 10:16:33'));
        $this->assertEquals(-1, InfraData::compararDataHorasSimples('31/12/2018 10:16:32', '31/12/2018 10:16:31'));
        $this->assertEquals(-1, InfraData::compararDataHorasSimples('31/12/2058 10:16:32', '31/12/2018 10:16:32'));
    }
}
