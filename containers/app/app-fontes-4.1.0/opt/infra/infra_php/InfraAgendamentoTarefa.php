<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/03/2013 - criado por MGA
 *
 * @package infra_php
 */


/*
CREATE TABLE infra_agendamento_tarefa
(
   id_infra_agendamento_tarefa int NOT NULL,
   descricao varchar(500) NOT NULL,
   comando varchar(255) NOT NULL,
   sta_periodicidade_execucao char(1) NOT NULL,
   periodicidade_complemento varchar(200) NOT NULL,
   dth_ultima_execucao datetime,
   dth_ultima_conclusao datetime,
   sin_sucesso char(1) NOT NULL,
   parametro varchar(250),
   email_erro varchar(250),
   sin_ativo char(1) NOT NULL
);

ALTER TABLE infra_agendamento_tarefa ADD CONSTRAINT  pk_infra_agendamento_tarefa PRIMARY KEY (id_infra_agendamento_tarefa);
*/


abstract class InfraAgendamentoTarefa
{

    public static $IND_MINUTO = 'MINUTO';
    public static $IND_HORA = 'HORA';
    public static $IND_DIA_SEMANA = 'DIA_SEMANA';
    public static $IND_DIA_MES = 'DIA_MES';
    public static $IND_MES = 'MES';

    //public abstract static function getInstance();

    public function __construct(
        InfraConfiguracao $objInfraConfiguracao,
        InfraSessao $objInfraSessao,
        InfraIBanco $objInfraIBanco,
        InfraLog $objInfraLog
    ) {
        ConfiguracaoInfra::setObjInfraConfiguracao($objInfraConfiguracao);
        SessaoInfra::setObjInfraSessao($objInfraSessao);
        BancoInfra::setObjInfraIBanco($objInfraIBanco);
        LogInfra::setObjInfraLog($objInfraLog);
    }

    public function executar($strEmailErroRemetente = null, $strEmailErroDestinatario = null)
    {
        try {
            //////////////////////////////////////////////////////////////////////////////
            //InfraDebug::getInstance()->setBolLigado(false);
            //InfraDebug::getInstance()->setBolDebugInfra(true);
            //InfraDebug::getInstance()->limpar();
            //////////////////////////////////////////////////////////////////////////////

            // busca lista de tarefas ativas
            $objInfraAgendamentoTarefaDTO = new InfraAgendamentoTarefaDTO();
            $objInfraAgendamentoTarefaDTO->retTodos();

            $objInfraAgendamentoTarefaDTO->setStrSinAtivo('S');
            $objInfraAgendamentoTarefaDTO->setOrdNumIdInfraAgendamentoTarefa(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objInfraAgendamentoTarefaRN = new InfraAgendamentoTarefaRN();
            $arrObjInfraAgendamentoTarefaDTO = $objInfraAgendamentoTarefaRN->listar($objInfraAgendamentoTarefaDTO);

            $arrDataHoraAtual = array(
                self::$IND_MINUTO => intval(date('i')),
                self::$IND_HORA => intval(date('G')),
                self::$IND_DIA_SEMANA => intval(date('N')),
                self::$IND_DIA_MES => intval(date('j')),
                self::$IND_MES => intval(date('n'))
            );

            foreach ($arrObjInfraAgendamentoTarefaDTO as $objInfraAgendamentoTarefaDTO) {
                /* @var $objInfraAgendamentoTarefaDTO InfraAgendamentoTarefaDTO */

                // verifica condição de execução
                $bolExecutar = false;

                switch ($objInfraAgendamentoTarefaDTO->getStrStaPeriodicidadeExecucao()) {
                    case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_MINUTO:
                        $arrMinutoExecucao = explode(
                            ',',
                            $objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento()
                        );

                        foreach ($arrMinutoExecucao as $minuto) {
                            // se o minuto estiver no periodicidade complemento executa a tarefa
                            if ($arrDataHoraAtual[self::$IND_MINUTO] == intval($minuto)) {
                                $bolExecutar = true;
                                break;
                            }
                        }
                        break;

                    case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA:
                        $arrHoraExecucao = explode(
                            ',',
                            $objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento()
                        );

                        foreach ($arrHoraExecucao as $item) {
                            $arrHora = explode(':', $item);
                            if (count($arrHora) == 1) {
                                $hora = intval($arrHora[0]);
                                $minuto = 0;
                            } else {
                                $hora = intval($arrHora[0]);
                                $minuto = intval($arrHora[1]);
                            }

                            // se a hora estiver na periodicidade complemento executa a tarefa
                            if ($arrDataHoraAtual[self::$IND_HORA] . ':' . $arrDataHoraAtual[self::$IND_MINUTO] == $hora . ':' . $minuto) {
                                $bolExecutar = true;
                                break;
                            }
                        }
                        break;

                    case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_DIA_SEMANA:

                        $arrDiaHoraExecucao = explode(
                            ',',
                            $objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento()
                        );

                        foreach ($arrDiaHoraExecucao as $item) {
                            $tempo = explode('/', $item);
                            $dia = intval($tempo[0]);
                            $arrHora = explode(':', $tempo[1]);
                            if (count($arrHora) == 1) {
                                $hora = intval($arrHora[0]);
                                $minuto = 0;
                            } else {
                                $hora = intval($arrHora[0]);
                                $minuto = intval($arrHora[1]);
                            }

                            // se dia da semana/hora estiver no periodicidade complemento executa a tarefa
                            if ($arrDataHoraAtual[self::$IND_DIA_SEMANA] . '/' . $arrDataHoraAtual[self::$IND_HORA] . ':' . $arrDataHoraAtual[self::$IND_MINUTO] == $dia . '/' . $hora . ':' . $minuto) {
                                $bolExecutar = true;
                                true;
                            }
                        }
                        break;

                    case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_DIA_MES:
                        $arrDiaHoraExecucao = explode(
                            ',',
                            $objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento()
                        );

                        foreach ($arrDiaHoraExecucao as $item) {
                            $tempo = explode('/', $item);
                            $dia = intval($tempo[0]);
                            $arrHora = explode(':', $tempo[1]);
                            if (count($arrHora) == 1) {
                                $hora = intval($arrHora[0]);
                                $minuto = 0;
                            } else {
                                $hora = intval($arrHora[0]);
                                $minuto = intval($arrHora[1]);
                            }

                            // se dia do mês/hora estiver no periodicidade complemento executa a tarefa
                            if ($arrDataHoraAtual[self::$IND_DIA_MES] . '/' . $arrDataHoraAtual[self::$IND_HORA] . ':' . $arrDataHoraAtual[self::$IND_MINUTO] == $dia . '/' . $hora . ':' . $minuto) {
                                $bolExecutar = true;
                                break;
                            }
                        }
                        break;

                    case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_DIA_ANO:
                        $arrDiaMesHoraExecucao = explode(
                            ',',
                            $objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento()
                        );

                        foreach ($arrDiaMesHoraExecucao as $item) {
                            $tempo = explode('/', $item);
                            $dia = intval($tempo[0]);
                            $mes = intval($tempo[1]);
                            $arrHora = explode(':', $tempo[2]);
                            if (count($arrHora) == 1) {
                                $hora = intval($arrHora[0]);
                                $minuto = 0;
                            } else {
                                $hora = intval($arrHora[0]);
                                $minuto = intval($arrHora[1]);
                            }

                            // se dia do mês/mês/hora estiver no periodicidade complemento executa a tarefa
                            if ($arrDataHoraAtual[self::$IND_DIA_MES] . '/' . $arrDataHoraAtual[self::$IND_MES] . '/' . $arrDataHoraAtual[self::$IND_HORA] . ':' . $arrDataHoraAtual[self::$IND_MINUTO] == $dia . '/' . $mes . '/' . $hora . ':' . $minuto) {
                                $bolExecutar = true;
                                break;
                            }
                        }
                        break;

                    default:
                        break;
                }


                //executa, se necessário
                if ($bolExecutar) {
                    $objErro = null;

                    if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 7) {
                        try {
                            $objInfraAgendamentoTarefaRN->executar($objInfraAgendamentoTarefaDTO);
                        } catch (Throwable $e) {
                            $objErro = $e;
                        }
                    } else {
                        try {
                            $objInfraAgendamentoTarefaRN->executar($objInfraAgendamentoTarefaDTO);
                        } catch (Exception $e) {
                            $objErro = $e;
                        }
                    }

                    if ($objErro != null) {
                        $strAssunto = 'Agendamento FALHOU (' . gethostname() . ')';

                        $strErro = '';
                        $strErro .= 'Servidor: ' . gethostname() . "\n\n";
                        $strErro .= 'Data/Hora: ' . InfraData::getStrDataHoraAtual() . "\n\n";
                        $strErro .= 'Comando: ' . $objInfraAgendamentoTarefaDTO->getStrComando(
                            ) . '(' . $objInfraAgendamentoTarefaDTO->getStrParametro() . ')' . "\n\n";
                        $strErro .= 'Erro: ' . InfraException::inspecionar($objErro);

                        LogInfra::getInstance()->gravar($strAssunto . "\n\n" . $strErro);

                        if (!is_null($strEmailErroRemetente)) {
                            if (!is_null($objInfraAgendamentoTarefaDTO->getStrEmailErro())) {
                                InfraMail::enviarConfigurado(
                                    ConfiguracaoInfra::getInstance(),
                                    $strEmailErroRemetente,
                                    $objInfraAgendamentoTarefaDTO->getStrEmailErro(),
                                    null,
                                    null,
                                    $strAssunto,
                                    $strErro
                                );
                            } elseif (!is_null($strEmailErroDestinatario)) {
                                InfraMail::enviarConfigurado(
                                    ConfiguracaoInfra::getInstance(),
                                    $strEmailErroRemetente,
                                    $strEmailErroDestinatario,
                                    null,
                                    null,
                                    $strAssunto,
                                    $strErro
                                );
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $strAssunto = 'Erro executando agendamentos.';
            $strErro = InfraException::inspecionar($e);

            LogInfra::getInstance()->gravar($strAssunto . "\n\n" . $strErro);

            if (!is_null($strEmailErroRemetente) && !is_null($strEmailErroDestinatario)) {
                InfraMail::enviarConfigurado(
                    ConfiguracaoInfra::getInstance(),
                    $strEmailErroRemetente,
                    $strEmailErroDestinatario,
                    null,
                    null,
                    $strAssunto,
                    $strErro
                );
            }
        }
    }
}

