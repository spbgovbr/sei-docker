<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/12/2011 - criado por tamir_db
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraAgendamentoTarefaRN extends InfraRN
{
    public static $PERIODICIDADE_EXECUCAO_MINUTO = 'N';
    public static $PERIODICIDADE_EXECUCAO_HORA = 'D';
    public static $PERIODICIDADE_EXECUCAO_DIA_SEMANA = 'S';
    public static $PERIODICIDADE_EXECUCAO_DIA_MES = 'M';
    public static $PERIODICIDADE_EXECUCAO_DIA_ANO = 'A';
    public static $REGEX_COMANDO = '/^[a-z][a-z0-9]+::[a-z][a-z0-9]+$/i';


    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoInfra::getInstance();
    }

    public function listarValoresPeriodicidadeExecucao()
    {
        try {
            $objArrInfraAgendamentoPeriodicidadeDTO = array();

            $objInfraAgendamentoPeriodicidadeDTO = new InfraAgendamentoPeriodicidadeDTO();
            $objInfraAgendamentoPeriodicidadeDTO->setStrStaPeriodicidadeExecucao(self::$PERIODICIDADE_EXECUCAO_MINUTO);
            $objInfraAgendamentoPeriodicidadeDTO->setStrDescricao('Minuto');
            $objArrInfraAgendamentoPeriodicidadeDTO[] = $objInfraAgendamentoPeriodicidadeDTO;

            $objInfraAgendamentoPeriodicidadeDTO = new InfraAgendamentoPeriodicidadeDTO();
            $objInfraAgendamentoPeriodicidadeDTO->setStrStaPeriodicidadeExecucao(self::$PERIODICIDADE_EXECUCAO_HORA);
            $objInfraAgendamentoPeriodicidadeDTO->setStrDescricao('Hora');
            $objArrInfraAgendamentoPeriodicidadeDTO[] = $objInfraAgendamentoPeriodicidadeDTO;

            $objInfraAgendamentoPeriodicidadeDTO = new InfraAgendamentoPeriodicidadeDTO();
            $objInfraAgendamentoPeriodicidadeDTO->setStrStaPeriodicidadeExecucao(
                self::$PERIODICIDADE_EXECUCAO_DIA_SEMANA
            );
            $objInfraAgendamentoPeriodicidadeDTO->setStrDescricao('Dia da Semana');
            $objArrInfraAgendamentoPeriodicidadeDTO[] = $objInfraAgendamentoPeriodicidadeDTO;

            $objInfraAgendamentoPeriodicidadeDTO = new InfraAgendamentoPeriodicidadeDTO();
            $objInfraAgendamentoPeriodicidadeDTO->setStrStaPeriodicidadeExecucao(self::$PERIODICIDADE_EXECUCAO_DIA_MES);
            $objInfraAgendamentoPeriodicidadeDTO->setStrDescricao('Dia do Mês');
            $objArrInfraAgendamentoPeriodicidadeDTO[] = $objInfraAgendamentoPeriodicidadeDTO;

            $objInfraAgendamentoPeriodicidadeDTO = new InfraAgendamentoPeriodicidadeDTO();
            $objInfraAgendamentoPeriodicidadeDTO->setStrStaPeriodicidadeExecucao(self::$PERIODICIDADE_EXECUCAO_DIA_ANO);
            $objInfraAgendamentoPeriodicidadeDTO->setStrDescricao('Dia do Ano');
            $objArrInfraAgendamentoPeriodicidadeDTO[] = $objInfraAgendamentoPeriodicidadeDTO;

            return $objArrInfraAgendamentoPeriodicidadeDTO;
        } catch (Exception $e) {
            throw new InfraException('Erro listando valores de Periodicidade da Execução.', $e);
        }
    }

    private function validarStrDescricao(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrDescricao())) {
            $objInfraException->adicionarValidacao('Descrição não informada.');
        } else {
            $objInfraAgendamentoTarefaDTO->setStrDescricao(trim($objInfraAgendamentoTarefaDTO->getStrDescricao()));

            if (strlen($objInfraAgendamentoTarefaDTO->getStrDescricao()) > 500) {
                $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 500 caracteres.');
            }
        }
    }

    private function validarStrComando(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrComando())) {
            $objInfraException->adicionarValidacao('Comando não informado.');
        } else {
            $objInfraAgendamentoTarefaDTO->setStrComando(trim($objInfraAgendamentoTarefaDTO->getStrComando()));

            if (strlen($objInfraAgendamentoTarefaDTO->getStrComando()) > 100) {
                $objInfraException->adicionarValidacao('Comando possui tamanho superior a 100 caracteres.');
            }

            if (preg_match(self::$REGEX_COMANDO, $objInfraAgendamentoTarefaDTO->getStrComando()) !== 1) {
                $objInfraException->adicionarValidacao('Sintaxe de Comando incorreta.');
            }
        }
    }

    private function validarStrSinAtivo(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrSinAtivo())) {
            $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objInfraAgendamentoTarefaDTO->getStrSinAtivo())) {
                $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
            }
        }
    }

    private function validarStrStaPeriodicidadeExecucao(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrStaPeriodicidadeExecucao())) {
            $objInfraException->adicionarValidacao('Periodicidade de Execução não informada.');
        } else {
            if (!in_array(
                $objInfraAgendamentoTarefaDTO->getStrStaPeriodicidadeExecucao(),
                InfraArray::converterArrInfraDTO(
                    $this->listarValoresPeriodicidadeExecucao(),
                    'StaPeriodicidadeExecucao'
                )
            )) {
                $objInfraException->adicionarValidacao('Periodicidade de Execução inválida.');
            }
        }
    }

    private function validarStrPeriodicidadeComplemento(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento())) {
            $objInfraException->adicionarValidacao('Complemento da Periodicidade não informado.');
        } else {
            $objInfraAgendamentoTarefaDTO->setStrPeriodicidadeComplemento(
                trim($objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento())
            );

            if (strlen($objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento()) > 200) {
                $objInfraException->adicionarValidacao(
                    'Complemento da Periodicidade possui tamanho superior a 200 caracteres.'
                );
            }

            $strPeriodicidadeComplemento = str_replace(
                ' ',
                '',
                $objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento()
            );

            switch ($objInfraAgendamentoTarefaDTO->getStrStaPeriodicidadeExecucao()) {
                case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_MINUTO:
                    $arrMinutoExecucao = explode(',', $strPeriodicidadeComplemento);

                    foreach ($arrMinutoExecucao as $item) {
                        $minuto = $item;
                        if (!is_numeric($minuto) || $minuto < 0 || $minuto > 59) {
                            $objInfraException->lancarValidacao('Minuto [' . $item . '] inválido.');
                        }
                    }
                    break;

                case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA:
                    $arrHoraExecucao = explode(',', $strPeriodicidadeComplemento);

                    foreach ($arrHoraExecucao as $item) {
                        $arrHora = explode(':', trim($item));
                        if (count($arrHora) == 1) {
                            $hora = $arrHora[0];
                            $minuto = 0;
                        } else {
                            $hora = $arrHora[0];
                            $minuto = $arrHora[1];
                        }

                        if (!is_numeric($hora) || $hora < 0 || $hora > 23) {
                            $objInfraException->lancarValidacao('Hora [' . $item . '] inválida.');
                        }

                        if (!is_numeric($minuto) || $minuto < 0 || $minuto > 59) {
                            $objInfraException->lancarValidacao('Minuto [' . $item . '] inválido.');
                        }
                    }
                    break;

                case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_DIA_SEMANA:

                    $arrDiaHoraExecucao = explode(',', $strPeriodicidadeComplemento);

                    foreach ($arrDiaHoraExecucao as $item) {
                        $tempo = explode('/', trim($item));
                        $dia = $tempo[0];
                        $arrHora = explode(':', $tempo[1]);
                        if (count($arrHora) == 1) {
                            $hora = $arrHora[0];
                            $minuto = 0;
                        } else {
                            $hora = $arrHora[0];
                            $minuto = $arrHora[1];
                        }

                        if (!is_numeric($dia) || $dia < 1 || $dia > 7) {
                            $objInfraException->lancarValidacao('Dia da Semana [' . $item . '] inválido.');
                        }

                        if (!is_numeric($hora) || $hora < 0 || $hora > 23) {
                            $objInfraException->lancarValidacao('Hora [' . $item . '] inválida.');
                        }

                        if (!is_numeric($minuto) || $minuto < 0 || $minuto > 59) {
                            $objInfraException->lancarValidacao('Minuto [' . $item . '] inválido.');
                        }
                    }
                    break;

                case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_DIA_MES:
                    $arrDiaHoraExecucao = explode(',', $strPeriodicidadeComplemento);

                    foreach ($arrDiaHoraExecucao as $item) {
                        $tempo = explode('/', trim($item));
                        $dia = $tempo[0];
                        $arrHora = explode(':', $tempo[1]);
                        if (count($arrHora) == 1) {
                            $hora = $arrHora[0];
                            $minuto = 0;
                        } else {
                            $hora = $arrHora[0];
                            $minuto = $arrHora[1];
                        }

                        if (!is_numeric($dia) || $dia < 1 || $dia > 31) {
                            $objInfraException->lancarValidacao('Dia do Mês [' . $item . '] inválido.');
                        }

                        if (!is_numeric($hora) || $hora < 0 || $hora > 23) {
                            $objInfraException->lancarValidacao('Hora [' . $item . '] inválida.');
                        }

                        if (!is_numeric($minuto) || $minuto < 0 || $minuto > 59) {
                            $objInfraException->lancarValidacao('Minuto [' . $item . '] inválido.');
                        }
                    }
                    break;

                case InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_DIA_ANO:
                    $arrDiaMesHoraExecucao = explode(',', $strPeriodicidadeComplemento);

                    foreach ($arrDiaMesHoraExecucao as $item) {
                        $tempo = explode('/', trim($item));
                        $dia = $tempo[0];
                        $mes = $tempo[1];
                        $arrHora = explode(':', $tempo[2]);
                        if (count($arrHora) == 1) {
                            $hora = $arrHora[0];
                            $minuto = 0;
                        } else {
                            $hora = $arrHora[0];
                            $minuto = $arrHora[1];
                        }

                        if (!is_numeric($dia) || $dia < 1 || $dia > 31) {
                            $objInfraException->lancarValidacao('Dia do Mês [' . $item . '] inválido.');
                        }

                        if (!is_numeric($mes) || $mes < 1 || $mes > 12) {
                            $objInfraException->lancarValidacao('Mês [' . $item . '] inválido.');
                        }

                        if (!is_numeric($hora) || $hora < 0 || $hora > 23) {
                            $objInfraException->lancarValidacao('Hora [' . $item . '] inválida.');
                        }

                        if (!is_numeric($minuto) || $minuto < 0 || $minuto > 59) {
                            $objInfraException->lancarValidacao('Minuto [' . $item . '] inválido.');
                        }
                    }
                    break;
            }
        }
    }

    private function validarDthUltimaExecucao(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getDthUltimaExecucao())) {
            $objInfraAgendamentoTarefaDTO->setDthUltimaExecucao(null);
        }
    }

    private function validarDthUltimaConclusao(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getDthUltimaConclusao())) {
            $objInfraAgendamentoTarefaDTO->setDthUltimaConclusao(null);
        }
    }

    private function validarStrParametro(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrParametro())) {
            $objInfraAgendamentoTarefaDTO->setStrParametro(null);
        } else {
            $objInfraAgendamentoTarefaDTO->setStrParametro(trim($objInfraAgendamentoTarefaDTO->getStrParametro()));

            if (strlen($objInfraAgendamentoTarefaDTO->getStrParametro()) > 250) {
                $objInfraException->adicionarValidacao('Parametro possui tamanho superior a 250 caracteres.');
            }
        }
    }

    private function validarStrEmailErro(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrEmailErro())) {
            $objInfraAgendamentoTarefaDTO->setStrEmailErro(null);
        } else {
            $objInfraAgendamentoTarefaDTO->setStrEmailErro(trim($objInfraAgendamentoTarefaDTO->getStrEmailErro()));

            if (strlen($objInfraAgendamentoTarefaDTO->getStrEmailErro()) > 250) {
                $objInfraException->adicionarValidacao('Email de Erro possui tamanho superior a 250 caracteres.');
            }

            $arr = explode(';', $objInfraAgendamentoTarefaDTO->getStrEmailErro());

            $numDestinatarios = count($arr);

            for ($i = 0; $i < $numDestinatarios; $i++) {
                if ($arr[$i] != '') {
                    if (!InfraUtil::validarEmail($arr[$i])) {
                        $objInfraException->adicionarValidacao('E-mail de erro "' . $arr[$i] . '" inválido.');
                    }
                }
            }
        }
    }

    private function validarStrSinSucesso(
        InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO,
        InfraException $objInfraException
    ) {
        if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getStrSinSucesso())) {
            $objInfraException->adicionarValidacao('Sinalizador de Sucesso da Execução não informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objInfraAgendamentoTarefaDTO->getStrSinSucesso())) {
                $objInfraException->adicionarValidacao('Sinalizador de Sucesso da Execução inválido.');
            }
        }
    }

    /*private function validarNumIdOrgao(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraAgendamentoTarefaDTO->getNumIdOrgao())){
        $objInfraException->adicionarValidacao('Orgão não informado.');
      }
    }*/

    protected function cadastrarControlado(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrDescricao($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarStrComando($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarStrSinAtivo($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarStrStaPeriodicidadeExecucao($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarStrPeriodicidadeComplemento($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarStrParametro($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarDthUltimaExecucao($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarDthUltimaConclusao($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarStrSinSucesso($objInfraAgendamentoTarefaDTO, $objInfraException);
            $this->validarStrEmailErro($objInfraAgendamentoTarefaDTO, $objInfraException);
            //$this->validarNumIdOrgao($objInfraAgendamentoTarefaDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            $ret = $objInfraAgendamentoTarefaBD->cadastrar($objInfraAgendamentoTarefaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando InfraAgendamentoTarefa.', $e);
        }
    }

    protected function alterarControlado(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objInfraAgendamentoTarefaDTO->isSetStrDescricao()) {
                $this->validarStrDescricao($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetStrComando()) {
                $this->validarStrComando($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetStrSinAtivo()) {
                $this->validarStrSinAtivo($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetStrStaPeriodicidadeExecucao()) {
                $this->validarStrStaPeriodicidadeExecucao($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetStrPeriodicidadeComplemento()) {
                $this->validarStrPeriodicidadeComplemento($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetStrParametro()) {
                $this->validarStrParametro($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetDthUltimaExecucao()) {
                $this->validarDthUltimaExecucao($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetDthUltimaConclusao()) {
                $this->validarDthUltimaConclusao($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetStrSinSucesso()) {
                $this->validarStrSinSucesso($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            if ($objInfraAgendamentoTarefaDTO->isSetStrEmailErro()) {
                $this->validarStrEmailErro($objInfraAgendamentoTarefaDTO, $objInfraException);
            }
            /*if ($objInfraAgendamentoTarefaDTO->isSetNumIdOrgao()){
              $this->validarNumIdOrgao($objInfraAgendamentoTarefaDTO, $objInfraException);
            }*/

            $objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            $objInfraAgendamentoTarefaBD->alterar($objInfraAgendamentoTarefaDTO);
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando InfraAgendamentoTarefa.', $e);
        }
    }

    protected function excluirControlado($arrObjInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjInfraAgendamentoTarefaDTO); $i++) {
                $objInfraAgendamentoTarefaBD->excluir($arrObjInfraAgendamentoTarefaDTO[$i]);
            }
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo InfraAgendamentoTarefa.', $e);
        }
    }

    protected function consultarConectado(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            $ret = $objInfraAgendamentoTarefaBD->consultar($objInfraAgendamentoTarefaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando InfraAgendamentoTarefa.', $e);
        }
    }

    protected function listarConectado(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            $ret = $objInfraAgendamentoTarefaBD->listar($objInfraAgendamentoTarefaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando InfraAgendamentoTarefa.', $e);
        }
    }

    protected function contarConectado(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            $ret = $objInfraAgendamentoTarefaBD->contar($objInfraAgendamentoTarefaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando InfraAgendamentoTarefa.', $e);
        }
    }

    protected function desativarControlado($arrObjInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_desativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjInfraAgendamentoTarefaDTO); $i++) {
                $objInfraAgendamentoTarefaBD->desativar($arrObjInfraAgendamentoTarefaDTO[$i]);
            }
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro desativando InfraAgendamentoTarefa.', $e);
        }
    }

    protected function reativarControlado($arrObjInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_reativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjInfraAgendamentoTarefaDTO); $i++) {
                $objInfraAgendamentoTarefaBD->reativar($arrObjInfraAgendamentoTarefaDTO[$i]);
            }
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro reativando InfraAgendamentoTarefa.', $e);
        }
    }

    protected function bloquearControlado(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO)
    {
        try {
            //Valida Permissao
            SessaoInfra::getInstance()->validarPermissao('infra_agendamento_tarefa_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraAgendamentoTarefaBD = new InfraAgendamentoTarefaBD($this->getObjInfraIBanco());
            $ret = $objInfraAgendamentoTarefaBD->bloquear($objInfraAgendamentoTarefaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro bloqueando InfraAgendamentoTarefa.', $e);
        }
    }

    public function executar(InfraAgendamentoTarefaDTO $objInfraAgendamentoTarefaDTO)
    {
        $objInfraAgendamentoTarefaDTOBanco = new InfraAgendamentoTarefaDTO();
        $objInfraAgendamentoTarefaDTOBanco->setBolExclusaoLogica(false);
        $objInfraAgendamentoTarefaDTOBanco->setNumIdInfraAgendamentoTarefa(
            $objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa()
        );
        $objInfraAgendamentoTarefaDTOBanco->retNumIdInfraAgendamentoTarefa();
        $objInfraAgendamentoTarefaDTOBanco->retStrComando();
        $objInfraAgendamentoTarefaDTOBanco->retStrParametro();

        $objInfraAgendamentoTarefaDTOBanco = $this->consultar($objInfraAgendamentoTarefaDTOBanco);

        if ($objInfraAgendamentoTarefaDTOBanco === null) {
            throw new InfraException('Agendamento não localizado.');
        }

        // obtém lista de parâmetros
        $strParametros = explode(',', $objInfraAgendamentoTarefaDTOBanco->getStrParametro());
        $arrParametros = array();
        foreach ($strParametros as $strParametro) {
            list($chave, $valor) = explode('=', $strParametro);
            $arrParametros[$chave][] = $valor;
        }

        // grava data de execução
        $objInfraAgendamentoTarefaDTO2 = new InfraAgendamentoTarefaDTO();
        $objInfraAgendamentoTarefaDTO2->setNumIdInfraAgendamentoTarefa(
            $objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa()
        );
        $objInfraAgendamentoTarefaDTO2->setDthUltimaExecucao(InfraData::getStrDataHoraAtual());
        $objInfraAgendamentoTarefaDTO2->setStrSinSucesso('N');
        $this->alterar($objInfraAgendamentoTarefaDTO2);

        //evita time out do banco
        if (BancoInfra::getInstance()->isBolManterConexaoAberta()) {
            BancoInfra::getInstance()->fecharConexao();
        }

        // executa tarefa
        $strComando = str_replace(' ', '', $objInfraAgendamentoTarefaDTOBanco->getStrComando());

        if (preg_match(self::$REGEX_COMANDO, $strComando) !== 1) {
            throw new InfraException('Sintaxe de Comando incorreta.');
        }

        //salvar banco da infra para evitar troca pelo metodo agendado
        $objBancoInfra = BancoInfra::getInstance();

        list($strClasse, $strMetodo) = explode('::', $strComando);
        $objRN = new $strClasse();
        $objRN->$strMetodo($arrParametros);

        BancoInfra::setObjInfraIBanco($objBancoInfra);

        // grava data de conclusão e seta flag de sucesso
        $objInfraAgendamentoTarefaDTO2 = new InfraAgendamentoTarefaDTO();
        $objInfraAgendamentoTarefaDTO2->setNumIdInfraAgendamentoTarefa(
            $objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa()
        );
        $objInfraAgendamentoTarefaDTO2->setDthUltimaConclusao(InfraData::getStrDataHoraAtual());
        $objInfraAgendamentoTarefaDTO2->setStrSinSucesso('S');
        $this->alterar($objInfraAgendamentoTarefaDTO2);
    }
}

