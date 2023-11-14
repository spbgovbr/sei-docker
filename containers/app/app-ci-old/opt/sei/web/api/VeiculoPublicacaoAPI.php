<?

class VeiculoPublicacaoAPI
{
    private $IdVeiculoPublicacao;   
    private $Nome;
    private $Descricao;
    private $StaTipo;
    private $SinFonteFeriados;
    private $SinPermiteExtraordinaria;
    private $SinExibirPesquisaInterna;
    private $WebService;
    private $SinAtivo;
    
    public function getIdVeiculoPublicacao(){
        return $this->IdVeiculoPublicacao;
    }

    public function setIdVeiculoPublicacao($IdVeiculoPublicacao){
        $this->IdVeiculoPublicacao = $IdVeiculoPublicacao;
    }

    public function getNome(){
        return $this->Nome;
    }

    public function setNome($Nome){
        $this->Nome = $Nome;
    }

    public function getDescricao(){
        return $this->Descricao;
    }

    public function setDescricao($Descricao){
        $this->Descricao = $Descricao;
    }

    public function getStaTipo(){
        return $this->StaTipo;
    }

    public function setStaTipo($StaTipo){
        $this->StaTipo = $StaTipo;
    }

    public function getSinFonteFeriados(){
        return $this->SinFonteFeriados;
    }

    public function setSinFonteFeriados($SinFonteFeriados){
        $this->SinFonteFeriados = $SinFonteFeriados;
    }

    public function getSinPermiteExtraordinaria(){
        return $this->SinPermiteExtraordinaria;
    }

    public function setSinPermiteExtraordinaria($SinPermiteExtraordinaria){
        $this->SinPermiteExtraordinaria = $SinPermiteExtraordinaria;
    }

    public function getSinExibirPesquisaInterna(){
        return $this->SinExibirPesquisaInterna;
    }

    public function setSinExibirPesquisaInterna($SinExibirPesquisaInterna){
        $this->SinExibirPesquisaInterna = $SinExibirPesquisaInterna;
    }

    public function getWebService(){
        return $this->WebService;
    }

    public function setWebService($WebService){
        $this->WebService = $WebService;
    }

    public function getSinAtivo(){
        return $this->SinAtivo;
    }

    public function setSinAtivo($SinAtivo){
        $this->SinAtivo = $SinAtivo;
    }

    /**
     * Cria uma nova instância de VeiculoPublicacaoAPI baseado em uma instância de VeiculoPublicacaoDTO
     * @param  VeiculoPublicacaoDTO $objVeiculoPublicacaoDTO Objeto DTO de referência para a criação
     * @return VeiculoPublicacaoAPI                          Instância de VeiculoPublicacaoAPI gerada
     */
    public static function criar(VeiculoPublicacaoDTO $objVeiculoPublicacaoDTO){
        $objVeiculoPulicacaoAPI = new VeiculoPublicacaoAPI();

        if($objVeiculoPublicacaoDTO->isSetNumIdVeiculoPublicacao()){
            $objVeiculoPulicacaoAPI->setIdVeiculoPublicacao($objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrNome()){
            $objVeiculoPulicacaoAPI->setNome($objVeiculoPublicacaoDTO->getStrNome());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrDescricao()){
            $objVeiculoPulicacaoAPI->setDescricao($objVeiculoPublicacaoDTO->getStrDescricao());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrStaTipo()){
            $objVeiculoPulicacaoAPI->setStaTipo($objVeiculoPublicacaoDTO->getStrStaTipo());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrSinFonteFeriados()){
            $objVeiculoPulicacaoAPI->setSinFonteFeriados($objVeiculoPublicacaoDTO->getStrSinFonteFeriados());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrSinPermiteExtraordinaria()){
            $objVeiculoPulicacaoAPI->setSinPermiteExtraordinaria($objVeiculoPublicacaoDTO->getStrSinPermiteExtraordinaria());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrSinExibirPesquisaInterna()){
            $objVeiculoPulicacaoAPI->setSinExibirPesquisaInterna($objVeiculoPublicacaoDTO->getStrSinExibirPesquisaInterna());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrWebService()){
            $objVeiculoPulicacaoAPI->setWebService($objVeiculoPublicacaoDTO->getStrWebService());
        }
        
        if($objVeiculoPublicacaoDTO->isSetStrSinAtivo()){
            $objVeiculoPulicacaoAPI->setSinAtivo($objVeiculoPublicacaoDTO->getStrSinAtivo());
        }
        
        return $objVeiculoPulicacaoAPI;
    }
}