<?php
/**
 * Created by PhpStorm.
 * User: bcu
 * Date: 07/08/2015
 * Time: 13:43
 */
?>
<html>
<body>
<pre>
  Profile - InfraBD->listar
  <br>
  <br>
<?
require_once 'SEI.php';
SessaoSEI::getInstance(false);
$i0=1000000;

$t= InfraUtil::verificarTempoProcessamento();

//$dto=new EstatisticasDTO();
//$dto->setDblIdEstatisticas(1500);
//$dto->setDblIdProcedimento(1500);
//$dto->setNumIdTipoProcedimento(1500);
//$dto->setDblIdDocumento(1200);
//$dto->setNumIdUnidade(1300);
//$dto->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
//$dto->setNumMes(10);
//$dto->setNumAno(2015);
//$dto->setDblTempoAberto(null);
//$dto->setDthAbertura(null);
//$dto->setDthConclusao(null);
//$dto->setDthSnapshot('02/03/2015 14:00:00');
//$dto->setDblQuantidade(null);
//$dto->retTodos();
//$banco=new BancoSEI();
//$banco->abrirConexao();
//$arr=array($dto);
//$objEstatisticasBD=new EstatisticasBD($banco);
$strDataHoraIni="02/03/2015 14:10:12";
$strDataHoraFim="02/03/2015 14:10:16";

echo "Inicialização: ".InfraUtil::verificarTempoProcessamento($t)." s\n";
$t= InfraUtil::verificarTempoProcessamento();




for ($i=$i0;$i;$i--) {
  if ( trim($strDataHoraIni) === "" || trim($strDataHoraFim) === "") {
    return null;
  }
}
echo "Execução : ".InfraUtil::verificarTempoProcessamento($t)." s\n";
$t= InfraUtil::verificarTempoProcessamento();
for ($i=$i0;$i;$i--) {
  $arrDataIni = explode('/',$strDataHoraIni);
  $arrDataFim = explode('/',$strDataHoraFim);
}
echo "Execução : ".InfraUtil::verificarTempoProcessamento($t)." s\n";
$t= InfraUtil::verificarTempoProcessamento();


//$banco->fecharConexao();
echo "Execução : ".InfraUtil::verificarTempoProcessamento($t)." s\n";
?>
  </pre>
</body>
</html>

