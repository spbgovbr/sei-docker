<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/08/2016 - criado por mga
 *
 */

class ProcedimentoAPI
{
    private $IdProcedimento;
    private $IdTipoProcedimento;
    private $IdTipoPrioridade;
    private $NomeTipoProcedimento;
    private $NumeroProtocolo;
    private $DataAutuacao;
    private $Especificacao;
    private $Assuntos;
    private $Interessados;
    private $Observacao;
    private $NivelAcesso;
    private $IdHipoteseLegal;
    private $CodigoAcesso;
    private $SinAberto;
    private $IdUnidadeGeradora;
    private $IdOrgaoUnidadeGeradora;
    private $IdUsuarioGerador;
    private $GrauSigilo;

    /**
     * @return mixed
     */
    public function getIdProcedimento()
    {
        return $this->IdProcedimento;
    }

    /**
     * @param mixed $IdProcedimento
     */
    public function setIdProcedimento($IdProcedimento)
    {
        $this->IdProcedimento = $IdProcedimento;
    }

    /**
     * @return mixed
     */
    public function getIdTipoProcedimento()
    {
        return $this->IdTipoProcedimento;
    }

    /**
     * @return mixed
     */
    public function getIdTipoPrioridade()
    {
        return $this->IdTipoPrioridade;
    }

    /**
     * @return mixed
     */
    public function getNomeTipoProcedimento()
    {
        return $this->NomeTipoProcedimento;
    }

    /**
     * @param mixed $NomeTipoProcedimento
     */
    public function setNomeTipoProcedimento($NomeTipoProcedimento)
    {
        $this->NomeTipoProcedimento = $NomeTipoProcedimento;
    }


    /**
     * @param mixed $IdTipoProcedimento
     */
    public function setIdTipoProcedimento($IdTipoProcedimento)
    {
        $this->IdTipoProcedimento = $IdTipoProcedimento;
    }

    /**
     * @param mixed $IdTipoPrioridade
     */
    public function setIdTipoPrioridade($IdTipoPrioridade)
    {
        $this->IdTipoPrioridade = $IdTipoPrioridade;
    }

    /**
     * @return mixed
     */
    public function getNumeroProtocolo()
    {
        return $this->NumeroProtocolo;
    }

    /**
     * @param mixed $NumeroProtocolo
     */
    public function setNumeroProtocolo($NumeroProtocolo)
    {
        $this->NumeroProtocolo = $NumeroProtocolo;
    }

    /**
     * @return mixed
     */
    public function getDataAutuacao()
    {
        return $this->DataAutuacao;
    }

    /**
     * @param mixed $DataAutuacao
     */
    public function setDataAutuacao($DataAutuacao)
    {
        $this->DataAutuacao = $DataAutuacao;
    }

    /**
     * @return mixed
     */
    public function getEspecificacao()
    {
        return $this->Especificacao;
    }

    /**
     * @param mixed $Especificacao
     */
    public function setEspecificacao($Especificacao)
    {
        $this->Especificacao = $Especificacao;
    }

    /**
     * @return mixed
     */
    public function getAssuntos()
    {
        return $this->Assuntos;
    }

    /**
     * @param mixed $Assuntos
     */
    public function setAssuntos($Assuntos)
    {
        $this->Assuntos = $Assuntos;
    }

    /**
     * @return mixed
     */
    public function getInteressados()
    {
        return $this->Interessados;
    }

    /**
     * @param mixed $Interessados
     */
    public function setInteressados($Interessados)
    {
        $this->Interessados = $Interessados;
    }

    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->Observacao;
    }

    /**
     * @param mixed $Observacao
     */
    public function setObservacao($Observacao)
    {
        $this->Observacao = $Observacao;
    }

    /**
     * @return mixed
     */
    public function getNivelAcesso()
    {
        return $this->NivelAcesso;
    }

    /**
     * @param mixed $NivelAcesso
     */
    public function setNivelAcesso($NivelAcesso)
    {
        $this->NivelAcesso = $NivelAcesso;
    }

    /**
     * @return mixed
     */
    public function getIdHipoteseLegal()
    {
        return $this->IdHipoteseLegal;
    }

    /**
     * @param mixed $IdHipoteseLegal
     */
    public function setIdHipoteseLegal($IdHipoteseLegal)
    {
        $this->IdHipoteseLegal = $IdHipoteseLegal;
    }

    /**
     * @return mixed
     */
    public function getCodigoAcesso()
    {
        return $this->CodigoAcesso;
    }

    /**
     * @param mixed $CodigoAcesso
     */
    public function setCodigoAcesso($CodigoAcesso)
    {
        $this->CodigoAcesso = $CodigoAcesso;
    }

    /**
     * @return mixed
     */
    public function getSinAberto()
    {
        return $this->SinAberto;
    }

    /**
     * @param mixed $SinAberto
     */
    public function setSinAberto($SinAberto)
    {
        $this->SinAberto = $SinAberto;
    }

    /**
     * @return mixed
     */
    public function getIdUnidadeGeradora()
    {
        return $this->IdUnidadeGeradora;
    }

    /**
     * @param mixed $IdUnidadeGeradora
     */
    public function setIdUnidadeGeradora($IdUnidadeGeradora)
    {
        $this->IdUnidadeGeradora = $IdUnidadeGeradora;
    }

    /**
     * @return mixed
     */
    public function getIdOrgaoUnidadeGeradora()
    {
        return $this->IdOrgaoUnidadeGeradora;
    }

    /**
     * @param mixed $IdOrgaoUnidadeGeradora
     */
    public function setIdOrgaoUnidadeGeradora($IdOrgaoUnidadeGeradora)
    {
        $this->IdOrgaoUnidadeGeradora = $IdOrgaoUnidadeGeradora;
    }

    /**
     * @return mixed
     */
    public function getIdUsuarioGerador()
    {
        return $this->IdUsuarioGerador;
    }

    /**
     * @param mixed $IdUsuarioGerador
     */
    public function setIdUsuarioGerador($IdUsuarioGerador)
    {
        $this->IdUsuarioGerador = $IdUsuarioGerador;
    }

    /**
     * @return mixed
     */
    public function getGrauSigilo()
    {
        return $this->GrauSigilo;
    }

    /**
     * @param mixed $GrauSigilo
     */
    public function setGrauSigilo($GrauSigilo)
    {
        $this->GrauSigilo = $GrauSigilo;
    }
}

?>