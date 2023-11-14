<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ProcedimentoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

  public function eliminar(ProcedimentoDTO $objProcedimentoDTO){
    try{

      $dblIdProcedimento = $objProcedimentoDTO->getDblIdProcedimento();

      $sql = 'delete from andamento_situacao where id_procedimento='.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from andamento_marcador where id_procedimento='.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = ' delete from comentario where id_procedimento='.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from anotacao where id_protocolo='.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from controle_prazo where id_protocolo='.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from retorno_programado where id_protocolo='.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from reabertura_programada where id_protocolo='.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setBolExclusaoLogica(false);
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retNumIdAtividade();
      $objAcessoExternoDTO->retNumIdTarefaAtividade();
      $objAcessoExternoDTO->retDtaValidade();
      $objAcessoExternoDTO->setDblIdProtocoloAtividade($dblIdProcedimento);

      $objAcessoExternoRN = new AcessoExternoRN();
      $arrObjAcessoExternoDTO = $objAcessoExternoRN->listar($objAcessoExternoDTO);

      if (count($arrObjAcessoExternoDTO)) {

        $arrIdAcessoExternoExcluir = array();
        $arrIdAtividadesExcluir = array();
        foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {
          if (($objAcessoExternoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO_CANCELADA || $objAcessoExternoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA) ||
            (!InfraString::isBolVazia($objAcessoExternoDTO->getDtaValidade()) && InfraData::compararDatas(InfraData::getStrDataAtual(), $objAcessoExternoDTO->getDtaValidade()) < 0)) {
            $arrIdAcessoExternoExcluir[] = $objAcessoExternoDTO->getNumIdAcessoExterno();
            $arrIdAtividadesExcluir[] = $objAcessoExternoDTO->getNumIdAtividade();
          }
        }

        if (count($arrIdAcessoExternoExcluir)) {
          $sql = ' delete from rel_acesso_ext_protocolo where '.$this->formatarIn('id_acesso_externo', $arrIdAcessoExternoExcluir, InfraDTO::$PREFIXO_NUM);
          $this->getObjInfraIBanco()->executarSql($sql);

          $sql = ' delete from rel_acesso_ext_serie where '.$this->formatarIn('id_acesso_externo', $arrIdAcessoExternoExcluir, InfraDTO::$PREFIXO_NUM);
          $this->getObjInfraIBanco()->executarSql($sql);

          $sql = ' delete from acesso_externo where '.$this->formatarIn('id_acesso_externo', $arrIdAcessoExternoExcluir, InfraDTO::$PREFIXO_NUM);
          $this->getObjInfraIBanco()->executarSql($sql);
        }

        if (count($arrIdAtividadesExcluir)){
          $sql = ' delete from atributo_andamento where '.$this->formatarIn('id_atividade', $arrIdAtividadesExcluir, InfraDTO::$PREFIXO_NUM);
          $this->getObjInfraIBanco()->executarSql($sql);

          $sql = ' delete from atividade where '.$this->formatarIn('id_atividade', $arrIdAtividadesExcluir, InfraDTO::$PREFIXO_NUM);
          $this->getObjInfraIBanco()->executarSql($sql);
        }

        $sql = ' delete from participante where id_protocolo= '.$dblIdProcedimento.
          ' and sta_participacao=\''.ParticipanteRN::$TP_ACESSO_EXTERNO.'\''.
          ' and not exists ('.
          '   select acesso_externo.id_acesso_externo'.
          '   from acesso_externo, atividade'.
          '   where acesso_externo.id_atividade=atividade.id_atividade'.
          '   and atividade.id_protocolo='.$dblIdProcedimento.
          '   and acesso_externo.id_participante=participante.id_participante'.
          ' )';
        $this->getObjInfraIBanco()->executarSql($sql);
      }


      $sql = 'delete from participante where id_protocolo= '.$dblIdProcedimento.' and sta_participacao<>\''.ParticipanteRN::$TP_ACESSO_EXTERNO.'\'';
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from observacao where id_protocolo= '.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $arrIdTarefas = array(TarefaRN::$TI_ELIMINACAO_ELETRONICO,
                            TarefaRN::$TI_DESARQUIVAMENTO_PARA_ELIMINACAO,
                            TarefaRN::$TI_PROCESSO_INCLUSAO_EDITAL_ELIMINACAO,
                            TarefaRN::$TI_PROCESSO_RETIRADA_EDITAL_ELIMINACAO,
                            TarefaRN::$TI_ARQUIVAMENTO,
                            TarefaRN::$TI_DESARQUIVAMENTO,
                            TarefaRN::$TI_RECEBIMENTO_ARQUIVO,
                            TarefaRN::$TI_CANCELADO_RECEBIMENTO_ARQUIVO,
                            TarefaRN::$TI_SOLICITADO_DESARQUIVAMENTO,
                            TarefaRN::$TI_CANCELADA_SOLICITACAO_DESARQUIVAMENTO,
                            TarefaRN::$TI_CANCELAR_ARQUIVAMENTO,
                            TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO,
                            TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA,
                            TarefaRN::$TI_ACESSO_EXTERNO_SISTEMA,
                            TarefaRN::$TI_DOCUMENTO_MOVIDO_PARA_PROCESSO,
                            TarefaRN::$TI_DOCUMENTO_MOVIDO_DO_PROCESSO,
                            TarefaRN::$TI_ASSINATURA_DOCUMENTO,
                            TarefaRN::$TI_AUTENTICACAO_DOCUMENTO,
                            TarefaRN::$TI_PUBLICACAO);

      $sql = ' delete from atributo_andamento'.
             ' where exists ('.
             '   select atividade.id_atividade'.
             '   from atividade'.
             '   where atividade.id_atividade=atributo_andamento.id_atividade'.
             '   and atividade.id_protocolo='.$dblIdProcedimento.
             '   and atividade.id_tarefa not in ('.implode(',',$arrIdTarefas).')'.
             ' )';
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = ' delete from atividade'.
             ' where id_protocolo='.$dblIdProcedimento.
             ' and id_tarefa not in ('.implode(',',$arrIdTarefas).')';
      $this->getObjInfraIBanco()->executarSql($sql);
      
      $sql = 'delete from rel_protocolo_assunto where id_protocolo= '.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from rel_protocolo_protocolo where id_protocolo_1= '.$dblIdProcedimento;
      //$this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from rel_protocolo_protocolo where id_protocolo_2= '.$dblIdProcedimento;
      //$this->getObjInfraIBanco()->executarSql($sql);

      //remove relacionamentos do processo
      $sql = 'delete from rel_protocolo_protocolo where sta_associacao=\''.RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO.'\' and (id_protocolo_1= '.$dblIdProcedimento.' or id_protocolo_2='.$dblIdProcedimento.')';
      $this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from acesso where id_protocolo= '.$dblIdProcedimento;
      //$this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from acompanhamento where id_protocolo= '.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from rel_bloco_protocolo where id_protocolo= '.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from procedimento where id_procedimento= '.$dblIdProcedimento;
      // $this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from protocolo where id_protocolo= '.$dblIdProcedimento;
      // $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'update procedimento set dta_eliminacao='.$this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()).' where id_procedimento = '.$dblIdProcedimento.' and dta_eliminacao is null';
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'update protocolo set sin_eliminado=\'S\' where id_protocolo = '.$dblIdProcedimento;
      $this->getObjInfraIBanco()->executarSql($sql);

    }catch(Exception $e){
      throw new InfraException('Erro eliminando Processo.',$e);
    }
  }
}
?>