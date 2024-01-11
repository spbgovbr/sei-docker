<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MonitoramentoServicoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

  public function gerarTotaisMedias(MonitoramentoServicoDTO $objMonitoramentoServicoDTO){
    try{

      $sql = 'SELECT count(*) as total, avg(tempo_execucao) as media, monitoramento_servico.id_servico AS idservico,servico.identificacao AS identificacaoservico,
              usuario.sigla AS siglausuarioservico,usuario.nome AS nomeusuarioservico, monitoramento_servico.operacao
              FROM monitoramento_servico, servico, usuario
              WHERE monitoramento_servico.id_servico=servico.id_servico
              AND servico.id_usuario=usuario.id_usuario';

      if ($objMonitoramentoServicoDTO->isSetNumIdUsuarioServico()){
        $sql .= ' AND servico.id_usuario='.$this->getObjInfraIBanco()->formatarGravacaoNum($objMonitoramentoServicoDTO->getNumIdUsuarioServico());
      }

      if ($objMonitoramentoServicoDTO->isSetNumIdServico()){
        $sql .= ' AND monitoramento_servico.id_servico='.$this->getObjInfraIBanco()->formatarGravacaoNum($objMonitoramentoServicoDTO->getNumIdServico());
      }

      if ($objMonitoramentoServicoDTO->isSetStrOperacao()){
        $sql .= ' AND monitoramento_servico.operacao='.$this->getObjInfraIBanco()->formatarGravacaoStr($objMonitoramentoServicoDTO->getStrOperacao());
      }

      if ($objMonitoramentoServicoDTO->isSetDthInicial() && $objMonitoramentoServicoDTO->isSetDthFinal()) {
        if (!InfraString::isBolVazia($objMonitoramentoServicoDTO->getDthInicial()) && !InfraString::isBolVazia($objMonitoramentoServicoDTO->getDthFinal())) {
          $sql .= ' AND (monitoramento_servico.dth_acesso >= ' . $this->getObjInfraIBanco()->formatarGravacaoDth($objMonitoramentoServicoDTO->getDthInicial()) . '
                         AND monitoramento_servico.dth_acesso <= ' . $this->getObjInfraIBanco()->formatarGravacaoDth($objMonitoramentoServicoDTO->getDthFinal()) . ')';
        }
      }

      $sql .= ' GROUP BY monitoramento_servico.id_servico, servico.identificacao, monitoramento_servico.operacao, usuario.sigla, usuario.nome, monitoramento_servico.operacao
                ORDER BY usuario.sigla ASC, servico.identificacao ASC, monitoramento_servico.operacao ASC';

      $rs = $this->getObjInfraIBanco()->consultarSql($sql);

      $ret = array();
      foreach($rs as $item){
        $objMonitoramentoServicoDTO = new MonitoramentoServicoDTO();
        $objMonitoramentoServicoDTO->setNumIdServico($this->getObjInfraIBanco()->formatarLeituraNum($item['idservico']));
        $objMonitoramentoServicoDTO->setStrSiglaUsuarioServico($this->getObjInfraIBanco()->formatarLeituraStr($item['siglausuarioservico']));
        $objMonitoramentoServicoDTO->setStrNomeUsuarioServico($this->getObjInfraIBanco()->formatarLeituraStr($item['nomeusuarioservico']));
        $objMonitoramentoServicoDTO->setStrIdentificacaoServico($this->getObjInfraIBanco()->formatarLeituraStr($item['identificacaoservico']));
        $objMonitoramentoServicoDTO->setStrOperacao($this->getObjInfraIBanco()->formatarLeituraStr($item['operacao']));
        $objMonitoramentoServicoDTO->setNumTotal($this->getObjInfraIBanco()->formatarLeituraNum($item['total']));
        $objMonitoramentoServicoDTO->setNumTempoMedio($this->getObjInfraIBanco()->formatarLeituraNum($item['media']));
        $ret[] = $objMonitoramentoServicoDTO;
      }


      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro gerando totais e médias para monitoramento.',$e);
    }
  }
}
?>