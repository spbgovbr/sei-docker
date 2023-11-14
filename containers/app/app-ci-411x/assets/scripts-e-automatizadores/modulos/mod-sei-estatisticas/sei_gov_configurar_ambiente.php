<?php

require_once dirname(__FILE__).'/../web/SEI.php';

class GenericoBD extends InfraBD {

    public function __construct(InfraIBanco $objInfraIBanco) {
        parent::__construct($objInfraIBanco);
    }

}

/**
 * Atualizador abstrato para sistema do SEI para instalar/atualizar o módulo PEN
 * 
 * @autor Join Tecnologia
 */
abstract class GovAtualizadorRn extends InfraRN {

    protected $sei_versao;

    /**
     * @var string Versão mínima requirida pelo sistema para instalação do PEN
     */
    protected $versaoMinRequirida;

    /**
     * @var InfraIBanco Instância da classe de persistência com o banco de dados
     */
    protected $objBanco;

    /**
     * @var InfraMetaBD Instância do metadata do banco de dados
     */
    protected $objMeta;

    /**
     * @var InfraDebug Instância do debuger
     */
    protected $objDebug;

    /**
     * @var integer Tempo de execução do script
     */
    protected $numSeg = 0;
    
    protected $objInfraBanco ;

    protected function inicializarObjInfraIBanco() {
        
        if (empty($this->objInfraBanco)) {
            $this->objInfraBanco = BancoSEI::getInstance();
            $this->objInfraBanco->abrirConexao();
        }
        
        return $this->objInfraBanco;
    }

    /**
     * Inicia a conexão com o banco de dados
     */
    protected function inicializarObjMetaBanco() {
        if (empty($this->objMeta)) {
            ;//$this->objMeta = new PenMetaBD($this->inicializarObjInfraIBanco());
        }
        return $this->objMeta;
    }

    /**
     * Adiciona uma mensagem ao output para o usuário
     * 
     * @return null
     */
    protected function logar($strMsg) {
        $this->objDebug->gravar($strMsg);
    }

    /**
     * Inicia o script criando um contator interno do tempo de execução
     * 
     * @return null
     */
    protected function inicializar($strTitulo) {

        $this->numSeg = InfraUtil::verificarTempoProcessamento();

        $this->logar($strTitulo);
    }

    /**
     * Finaliza o script informando o tempo de execução.
     * 
     * @return null
     */
    protected function finalizar($strMsg=null, $bolErro=false){
        if (!$bolErro) {
          $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
          $this->logar('TEMPO TOTAL DE EXECUCAO: ' . $this->numSeg . ' s');
        }else{
          $strMsg = 'ERRO: '.$strMsg;
        }

        if ($strMsg!=null){
          $this->logar($strMsg);
        }

        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        $this->numSeg = 0;
        die;
    }    

    /**
     * Construtor
     * 
     * @param array $arrArgs Argumentos enviados pelo script
     */
    public function __construct() {
        
        parent::__construct();
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        @ini_set('zlib.output_compression', '0');
        @ini_set('implicit_flush', '1');
        ob_implicit_flush();
        
        $this->inicializarObjInfraIBanco();
        $this->inicializarObjMetaBanco();

        $this->objDebug = InfraDebug::getInstance();
        $this->objDebug->setBolLigado(true);
        $this->objDebug->setBolDebugInfra(true);
        $this->objDebug->setBolEcho(true);
        $this->objDebug->limpar();
    }

}



class GovAtualizadorRn2 extends GovAtualizadorRn {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function atualizarVersao() {
        try {
            $this->inicializar('INICIANDO ATUALIZACAO DO MODULO GOV NO SEI VERSAO ' . SEI_VERSAO);

            //testando se esta usando BDs suportados
            if (!(BancoSEI::getInstance() instanceof InfraMySql) &&
                    !(BancoSEI::getInstance() instanceof InfraSqlServer) &&
                    !(BancoSEI::getInstance() instanceof InfraOracle)) {

                $this->finalizar('BANCO DE DADOS NAO SUPORTADO: ' . get_parent_class(BancoSEI::getInstance()), true);
            }
           
            //testando permissoes de criações de tabelas
            $objInfraMetaBD = new InfraMetaBD($this->objInfraBanco);
                        

            $objInfraParametro = new InfraParametro($this->objInfraBanco);            
            
            // Aplicação de scripts de atualização de forma incremental
            // Ausência de [break;] proposital para realizar a atualização incremental de versões
            $strVersaoModuloPen = "x";
            switch ($strVersaoModuloPen) {                
                case 'x': $this->instalarConfigCustom();

                break;
                default:
                    $this->finalizar('VERSAO DO MÓDULO JÁ CONSTA COMO ATUALIZADA');
                    break;
            }

            $this->finalizar('FIM');
            InfraDebug::getInstance()->setBolDebugInfra(true);
        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando VERSAO.', $e);
        }
    }

    
    protected function instalarConfigCustom() {        
        
        
        $objBD = new GenericoBD($this->inicializarObjInfraIBanco());
        
        //Agendamento
        $objDTO = new InfraAgendamentoTarefaDTO();

        $fnCadastrar = function($strComando, $strDesc) use($objDTO, $objBD, $objRN) {

            $objDTO->unSetTodos();
            $objDTO->setStrComando($strComando);

            if ($objBD->contar($objDTO) == 0) {

                $objDTO->setStrDescricao($strDesc);
                $objDTO->setStrStaPeriodicidadeExecucao('D');
                $objDTO->setStrPeriodicidadeComplemento('3');
                $objDTO->setStrSinAtivo('S');
                $objDTO->setStrSinSucesso('S');

                $objBD->cadastrar($objDTO);
            }
        };

        $fnCadastrar('MdEstatisticasAgendamentoRN::coletarIndicadores', 'Agendamento do SEI Gov.');

    }

}


try {

    
    SessaoSEI::getInstance(false);    
    
    $objAtualizarRN = new GovAtualizadorRn2();
    $objAtualizarRN->atualizarVersao();

    exit(0);
}
catch(InfraException $e){
    
    print $e->getStrDescricao().PHP_EOL;
}
catch(Exception $e) {
    
    print InfraException::inspecionar($e);
    
    try {
        LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
        
    }
    
    exit(1);
}

print PHP_EOL;