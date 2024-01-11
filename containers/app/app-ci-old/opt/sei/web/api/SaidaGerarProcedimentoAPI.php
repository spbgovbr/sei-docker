<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/07/2014 - criado por mga
*
*/

class SaidaGerarProcedimentoAPI {
	private $IdProcedimento;
	private $ProcedimentoFormatado;
	private $LinkAcesso;
  private $RetornoInclusaoDocumentos;

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
	public function getProcedimentoFormatado()
	{
		return $this->ProcedimentoFormatado;
	}

	/**
	 * @param mixed $ProcedimentoFormatado
	 */
	public function setProcedimentoFormatado($ProcedimentoFormatado)
	{
		$this->ProcedimentoFormatado = $ProcedimentoFormatado;
	}

	/**
	 * @return mixed
	 */
	public function getLinkAcesso()
	{
		return $this->LinkAcesso;
	}

	/**
	 * @param mixed $LinkAcesso
	 */
	public function setLinkAcesso($LinkAcesso)
	{
		$this->LinkAcesso = $LinkAcesso;
	}

	/**
	 * @return mixed
	 */
	public function getRetornoInclusaoDocumentos()
	{
		return $this->RetornoInclusaoDocumentos;
	}

	/**
	 * @param mixed $RetornoInclusaoDocumentos
	 */
	public function setRetornoInclusaoDocumentos($RetornoInclusaoDocumentos)
	{
		$this->RetornoInclusaoDocumentos = $RetornoInclusaoDocumentos;
	}
}
?>