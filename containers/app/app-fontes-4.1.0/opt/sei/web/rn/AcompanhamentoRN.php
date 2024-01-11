<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2010 - criado por jonatas_db
* 06/06/2018 - cjy - adição da opção/icone de acompanhamento especial
* 15/06/2018 - cjy - ícone de acompanhamento no controle de processos
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcompanhamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdGrupoAcompanhamento(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getNumIdGrupoAcompanhamento())){
      $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento(null);
    }
  }

  private function validarDblIdProtocolo(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarNumIdUsuario(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarDthAlteracao(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getDthAlteracao())){
      $objInfraException->adicionarValidacao('Data de Alteração não informada.');
    }else{
      if (!InfraData::validarDataHora($objAcompanhamentoDTO->getDthAlteracao())){
        $objInfraException->adicionarValidacao('Data de Alteração inválida.');
      }
    }
  }

  private function validarStrObservacao(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getStrObservacao())){
      $objAcompanhamentoDTO->setStrObservacao(null);
    }else{
      $objAcompanhamentoDTO->setStrObservacao(trim($objAcompanhamentoDTO->getStrObservacao()));
      $objAcompanhamentoDTO->setStrObservacao(InfraUtil::filtrarISO88591($objAcompanhamentoDTO->getStrObservacao()));

      if (strlen($objAcompanhamentoDTO->getStrObservacao())>$this->getNumMaxTamanhoObservacao()){
        $objInfraException->adicionarValidacao('Observação possui tamanho superior a '.$this->getNumMaxTamanhoObservacao().' caracteres.');
      }
    }
  }

  private function validarDuplicado(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    $dto = new AcompanhamentoDTO();
    $dto->retNumIdAcompanhamento();
    $dto->setNumIdAcompanhamento($objAcompanhamentoDTO->getNumIdAcompanhamento(),InfraDTO::$OPER_DIFERENTE);
    $dto->setDblIdProtocolo($objAcompanhamentoDTO->getDblIdProtocolo());
    $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $dto->setNumIdGrupoAcompanhamento($objAcompanhamentoDTO->getNumIdGrupoAcompanhamento());
    $dto->setNumMaxRegistrosRetorno(1);
    if ($this->consultar($dto) != null){
      if ($objAcompanhamentoDTO->getNumIdGrupoAcompanhamento()==null){
        $objInfraException->lancarValidacao('Já existe um Acompanhamento Especial no processo '.$objAcompanhamentoDTO->getStrProtocoloFormatado().' sem grupo definido.');
      }else{
        $objInfraException->lancarValidacao('Já existe um Acompanhamento Especial no processo '.$objAcompanhamentoDTO->getStrProtocoloFormatado().' com este grupo.');
      }
    }
  }

  private function validarStrIdxAcompanhamento(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getStrIdxAcompanhamento())){
      $objAcompanhamentoDTO->setStrIdxAcompanhamento(null);
    }else{
      $objAcompanhamentoDTO->setStrIdxAcompanhamento(trim($objAcompanhamentoDTO->getStrIdxAcompanhamento()));

      if (strlen($objAcompanhamentoDTO->getStrIdxAcompanhamento()) > 4000){
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  public function getNumMaxTamanhoObservacao(){
    return 500;
  }

  private function validarNumTipoVisualizacao(AcompanhamentoDTO $objAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcompanhamentoDTO->getNumTipoVisualizacao())){
      $objInfraException->adicionarValidacao('Tipo de visualização não informado.');
    }
  }

  protected function cadastrarMultiplosControlado($arrAcompanhamentoDTO) {
    $ret = array();
    $objInfraException_Multiplo = new InfraException();
    foreach($arrAcompanhamentoDTO as $objAcompanhamentoDTO) {
      try {
        $ret[] = $this->cadastrar($objAcompanhamentoDTO);
      } catch (Exception $e) {
        if ($e instanceof InfraException && $e->contemValidacoes()) {
          $objInfraException_Multiplo->adicionarValidacao($e->__toString());
        }else{
          throw $e;
        }
      }
    }
    if($objInfraException_Multiplo->contemValidacoes()){
      throw $objInfraException_Multiplo;
    }
    return $ret;
  }

  protected function cadastrarControlado(AcompanhamentoDTO $objAcompanhamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_cadastrar',__METHOD__,$objAcompanhamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAcompanhamentoDTO->setNumTipoVisualizacao(AtividadeRN::$TV_VISUALIZADO);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->setDblIdProtocolo($objAcompanhamentoDTO->getDblIdProtocolo());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

      $objAcompanhamentoDTO->setStrProtocoloFormatado($objProtocoloDTO->getStrProtocoloFormatado());

      $this->validarNumIdUnidade($objAcompanhamentoDTO, $objInfraException);
      $this->validarNumIdGrupoAcompanhamento($objAcompanhamentoDTO, $objInfraException);
      $this->validarDblIdProtocolo($objAcompanhamentoDTO, $objInfraException);
      $this->validarNumIdUsuario($objAcompanhamentoDTO, $objInfraException);
      $this->validarDthAlteracao($objAcompanhamentoDTO, $objInfraException);
      $this->validarStrObservacao($objAcompanhamentoDTO, $objInfraException);

      $this->validarDuplicado($objAcompanhamentoDTO, $objInfraException);

      if ($objProtocoloDTO==null){
        $objInfraException->lancarValidacao('Processo '.$objAcompanhamentoDTO->getStrProtocoloFormatado().' não encontrado.');
      }

      //if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO){
      //  $objInfraException->adicionarValidacao('Processo sigiloso '.$objAcompanhamentoDTO->getStrProtocoloFormatado().' não pode ser adicionado no Acompanhamento Especial.');
      //}

      $objInfraException->lancarValidacoes();

      $objAcompanhamentoDTO->setStrIdxAcompanhamento(null);

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objAcompanhamentoBD->cadastrar($objAcompanhamentoDTO);

      $this->montarIndexacao($ret);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Acompanhamento.',$e);
    }
  }

  protected function alterarGrupoControlado(AcompanhamentoDTO $parObjAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_alterar_grupo',__METHOD__,$parObjAcompanhamentoDTO);

      $arrIdAcompanhamento = $parObjAcompanhamentoDTO->getNumIdAcompanhamento();

      foreach($arrIdAcompanhamento as $numIdAcompanhamento){
        $objAcompanhamentoDTO = new AcompanhamentoDTO();
        $objAcompanhamentoDTO->setNumIdAcompanhamento($numIdAcompanhamento);
        $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($parObjAcompanhamentoDTO->getNumIdGrupoAcompanhamento());
        $this->alterar($objAcompanhamentoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo do Acompanhamento Especial.',$e);
    }
  }


  protected function alterarControlado(AcompanhamentoDTO $objAcompanhamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_alterar',__METHOD__,$objAcompanhamentoDTO);

      $objInfraException = new InfraException();

      //Regras de Negocio
      $objAcompanhamentoDTOBanco = new AcompanhamentoDTO();
      $objAcompanhamentoDTOBanco->retTodos();
      $objAcompanhamentoDTOBanco->retStrProtocoloFormatado();
      $objAcompanhamentoDTOBanco->setNumIdAcompanhamento($objAcompanhamentoDTO->getNumIdAcompanhamento());
      $objAcompanhamentoDTOBanco = $this->consultar($objAcompanhamentoDTOBanco);

      $objAcompanhamentoDTO->setStrProtocoloFormatado($objAcompanhamentoDTOBanco->getStrProtocoloFormatado());

      if ($objAcompanhamentoDTOBanco->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
        $objInfraException->lancarValidacao('Não é possível alterar um Acompanhamento Especial de outra unidade.');
      }

      if ($objAcompanhamentoDTO->isSetNumIdUnidade() && $objAcompanhamentoDTO->getNumIdUnidade()!=$objAcompanhamentoDTOBanco->getNumIdUnidade()) {
          $objInfraException->lancarValidacao('Não é possível alterar a unidade de um Acompanhamento Especial.');
      }else{
        $objAcompanhamentoDTO->setNumIdUnidade($objAcompanhamentoDTOBanco->getNumIdUnidade());
      }

      if ($objAcompanhamentoDTO->isSetNumIdGrupoAcompanhamento()){
      	$this->validarNumIdGrupoAcompanhamento($objAcompanhamentoDTO, $objInfraException);
      }else{
        $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($objAcompanhamentoDTOBanco->getNumIdGrupoAcompanhamento());
      }

      if ($objAcompanhamentoDTO->isSetDblIdProtocolo() && $objAcompanhamentoDTO->getDblIdProtocolo()!=$objAcompanhamentoDTOBanco->getDblIdProtocolo()){
        $objInfraException->lancarValidacao('Não é possível alterar o processo de um Acompanhamento Especial.');
      }else{
        $objAcompanhamentoDTO->setDblIdProtocolo($objAcompanhamentoDTOBanco->getDblIdProtocolo());
      }

      if ($objAcompanhamentoDTO->isSetStrObservacao()){
        $this->validarStrObservacao($objAcompanhamentoDTO, $objInfraException);
      }else{
        $objAcompanhamentoDTO->setStrObservacao($objAcompanhamentoDTOBanco->getStrObservacao());
      }

      if ($objAcompanhamentoDTO->getNumIdGrupoAcompanhamento()!=$objAcompanhamentoDTOBanco->getNumIdGrupoAcompanhamento() || $objAcompanhamentoDTO->getStrObservacao()!=$objAcompanhamentoDTOBanco->getStrObservacao()){
        $objAcompanhamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAcompanhamentoDTO->setDthAlteracao(InfraData::getStrDataHoraAtual());
      }

      if ($objAcompanhamentoDTO->isSetNumTipoVisualizacao()){
        $objAcompanhamentoDTO->unSetTipoVisualizacao();
      }

      if ($objAcompanhamentoDTO->isSetStrIdxAcompanhamento()){
        $objAcompanhamentoDTO->unSetStrIdxAcompanhamento();
      }

      $this->validarDuplicado($objAcompanhamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      $objAcompanhamentoBD->alterar($objAcompanhamentoDTO);

      $this->montarIndexacao($objAcompanhamentoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Acompanhamento.',$e);
    }
  }

  protected function excluirControlado($arrObjAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_excluir',__METHOD__,$arrObjAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();
      
      //$objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcompanhamentoDTO);$i++){
        $objAcompanhamentoBD->excluir($arrObjAcompanhamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Acompanhamento.',$e);
    }
  }

  protected function consultarConectado(AcompanhamentoDTO $objAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_consultar',__METHOD__,$objAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objAcompanhamentoBD->consultar($objAcompanhamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Acompanhamento.',$e);
    }
  }

  protected function listarConectado(AcompanhamentoDTO $objAcompanhamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_listar',__METHOD__,$objAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objAcompanhamentoBD->listar($objAcompanhamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Acompanhamentos.',$e);
    }
  }

  protected function contarConectado(AcompanhamentoDTO $objAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_listar',__METHOD__,$objAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objAcompanhamentoBD->contar($objAcompanhamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Acompanhamentos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_desativar',__METHOD__,$arrObjAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcompanhamentoDTO);$i++){
        $objAcompanhamentoBD->desativar($arrObjAcompanhamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Acompanhamento.',$e);
    }
  }

  protected function reativarControlado($arrObjAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_reativar',__METHOD__,$arrObjAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcompanhamentoDTO);$i++){
        $objAcompanhamentoBD->reativar($arrObjAcompanhamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Acompanhamento.',$e);
    }
  }

  protected function bloquearControlado(AcompanhamentoDTO $objAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_consultar',__METHOD__,$objAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objAcompanhamentoBD->bloquear($objAcompanhamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Acompanhamento.',$e);
    }
  }

 */
  
  protected function listarAcompanhamentosUnidadeConectado(AcompanhamentoDTO $objAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acompanhamento_listar',__METHOD__,$objAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

		  $objAcompanhamentoDTO->retNumIdAcompanhamento();
		  $objAcompanhamentoDTO->retNumIdUnidade();
		  $objAcompanhamentoDTO->retNumIdGrupoAcompanhamento();
		  $objAcompanhamentoDTO->retDblIdProtocolo();
		  $objAcompanhamentoDTO->retNumIdUsuario();
		  $objAcompanhamentoDTO->retDthAlteracao();
		  $objAcompanhamentoDTO->retStrObservacao();
		  $objAcompanhamentoDTO->retStrSiglaUsuario();
		  $objAcompanhamentoDTO->retStrSiglaUnidade();
		  $objAcompanhamentoDTO->retStrDescricaoUnidade();
		  $objAcompanhamentoDTO->retStrNomeGrupo();
		  $objAcompanhamentoDTO->retStrNomeUsuario();
		  $objAcompanhamentoDTO->retStrSiglaUsuario();
		  //$objAcompanhamentoDTO->retStrProtocoloFormatado();
      $objAcompanhamentoDTO->retNumTipoVisualizacao();
      //$objAcompanhamentoDTO->retNumIdTipoProcedimentoProcedimento();
      //$objAcompanhamentoDTO->retStrNomeTipoProcedimento();
      //$objAcompanhamentoDTO->retStrStaNivelAcessoGlobalProtocolo();
		  
		  $objAcompanhamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objAcompanhamentoDTO = InfraString::prepararPesquisaDTO($objAcompanhamentoDTO,"PalavrasPesquisa", "IdxAcompanhamento");

      if ($objAcompanhamentoDTO->isSetStrSinAlterados() && $objAcompanhamentoDTO->getStrSinAlterados()=='S'){
        $objAcompanhamentoDTO->setNumTipoVisualizacao(AtividadeRN::$TV_ATENCAO, InfraDTO::$OPER_BIT_AND);
      }

      if (($objAcompanhamentoDTO->isSetStrSinAbertos() && $objAcompanhamentoDTO->getStrSinAbertos()=='S') || ($objAcompanhamentoDTO->isSetStrSinFechados() && $objAcompanhamentoDTO->getStrSinFechados()=='S')){

        $strSql = 'select id_atividade 
                   from atividade 
                   where atividade.id_protocolo=acompanhamento.id_protocolo 
                   and atividade.id_unidade='.SessaoSEI::getInstance()->getNumIdUnidadeAtual().' 
                   and atividade.dth_conclusao is null 
                   and atividade.id_tarefa<>'.TarefaRN::$TI_SOBRESTAMENTO;

        if ($objAcompanhamentoDTO->isSetStrSinAbertos() && $objAcompanhamentoDTO->getStrSinAbertos()=='S'){
          $objAcompanhamentoDTO->setStrCriterioSqlNativo('exists ('.$strSql.')');
        }else{
          $objAcompanhamentoDTO->setStrCriterioSqlNativo('not exists ('.$strSql.')');
        }
      }

      $objAcompanhamentoDTO->setOrdDthAlteracao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objAcompanhamentoRN = new AcompanhamentoRN();
      $arrObjAcompanhamentoDTO = $objAcompanhamentoRN->listar($objAcompanhamentoDTO);
		  
      
			if (count($arrObjAcompanhamentoDTO)>0){

				$objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
				$objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS_EXCETO_SIGILOSOS_SEM_ACESSO);
				$objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjAcompanhamentoDTO,'IdProtocolo'));
				
				$objProtocoloRN = new ProtocoloRN();
				$arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');
			}

			$arrRet = array();
			foreach($arrObjAcompanhamentoDTO as $dto){
				//se tem acesso
				if (isset($arrObjProtocoloDTO[$dto->getDblIdProtocolo()])){
					$arrRet[] = $dto;
				}
			}

      if (count($arrRet)) {

        $arrIdProtocolos = InfraArray::converterArrInfraDTO($arrRet,'IdProtocolo');

        $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
        $objPesquisaPendenciaDTO->setDblIdProtocolo($arrIdProtocolos);
        $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objPesquisaPendenciaDTO->setStrSinSinalizacoes('S');

        $objAtividadeRN = new AtividadeRN();
        $arrObjProcedimentoDTOComPendencias = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO),'IdProcedimento');

        $arrIdProtocolos = array_diff($arrIdProtocolos, array_keys($arrObjProcedimentoDTOComPendencias));

        if (InfraArray::contar($arrIdProtocolos)) {

          $objProcedimentoDTO = new ProcedimentoDTO();
          $objProcedimentoDTO->setDblIdProcedimento($arrIdProtocolos, InfraDTO::$OPER_IN);
          $objProcedimentoDTO->setStrSinSinalizacoes('S');

          $objProcedimentoRN = new ProcedimentoRN();
          $arrObjProcedimentoDTOSemPendencias = InfraArray::indexarArrInfraDTO($objProcedimentoRN->listarCompleto($objProcedimentoDTO), 'IdProcedimento');

        }else{
          $arrObjProcedimentoDTOSemPendencias = array();
        }

        foreach($arrRet as $objAcompanhamentoDTO){

          $dblIdProtocolo = $objAcompanhamentoDTO->getDblIdProtocolo();

          if (isset($arrObjProcedimentoDTOComPendencias[$dblIdProtocolo])){
            $objAcompanhamentoDTO->setObjProcedimentoDTO($arrObjProcedimentoDTOComPendencias[$dblIdProtocolo]);
          }else{
            $arrObjProcedimentoDTOSemPendencias[$dblIdProtocolo]->setArrObjRetornoProgramadoDTO(null);
            $objAcompanhamentoDTO->setObjProcedimentoDTO($arrObjProcedimentoDTOSemPendencias[$dblIdProtocolo]);
          }

        }

      }

      return $arrRet;

    }catch(Exception $e){
      throw new InfraException('Erro listando acompanhamentos da unidade.',$e);
    }
  }

  protected function atualizarVisualizacaoControlado(AcompanhamentoDTO $parObjAcompanhamentoDTO){
    try{

      $objAcompanhamentoDTO = new AcompanhamentoDTO();
      $objAcompanhamentoDTO->retNumIdAcompanhamento();
      $objAcompanhamentoDTO->retNumIdUnidade();
      $objAcompanhamentoDTO->retNumTipoVisualizacao();
      $objAcompanhamentoDTO->setDblIdProtocolo($parObjAcompanhamentoDTO->getDblIdProtocolo());

      //se alguma unidade que não deve ser atualizada
      if ($parObjAcompanhamentoDTO->isSetNumIdUnidade() && $parObjAcompanhamentoDTO->getNumIdUnidade()!=null){
        if (is_array($parObjAcompanhamentoDTO->getNumIdUnidade())){
          $objAcompanhamentoDTO->setNumIdUnidade($parObjAcompanhamentoDTO->getNumIdUnidade(),InfraDTO::$OPER_NOT_IN);
        }else{
          $objAcompanhamentoDTO->setNumIdUnidade($parObjAcompanhamentoDTO->getNumIdUnidade(),InfraDTO::$OPER_DIFERENTE);
        }
      }

      $arrObjAcompanhamentoDTO = $this->listar($objAcompanhamentoDTO);

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());

      foreach($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO){
        $objAcompanhamentoDTO->setNumTipoVisualizacao($objAcompanhamentoDTO->getNumTipoVisualizacao() | $parObjAcompanhamentoDTO->getNumTipoVisualizacao());
        $objAcompanhamentoBD->alterar($objAcompanhamentoDTO);
      }

      return $arrObjAcompanhamentoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro atualizando visualização de Acompanhamento Especial.',$e);
    }
  }

  protected function atualizarVisualizacaoUnidadeControlado(AcompanhamentoDTO $parObjAcompanhamentoDTO){
    try{

      $objAcompanhamentoDTO = new AcompanhamentoDTO();
      $objAcompanhamentoDTO->retNumIdAcompanhamento();
      $objAcompanhamentoDTO->retNumTipoVisualizacao();
      $objAcompanhamentoDTO->setDblIdProtocolo($parObjAcompanhamentoDTO->getDblIdProtocolo());
      $objAcompanhamentoDTO->setNumIdUnidade($parObjAcompanhamentoDTO->getNumIdUnidade());

      $arrObjAcompanhamentoDTO = $this->listar($objAcompanhamentoDTO);

      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());

      foreach($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO){
        $objAcompanhamentoDTO->setNumTipoVisualizacao($objAcompanhamentoDTO->getNumTipoVisualizacao() | $parObjAcompanhamentoDTO->getNumTipoVisualizacao());
        $objAcompanhamentoBD->alterar($objAcompanhamentoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro atualizando visualização da unidade no Acompanhamento Especial.',$e);
    }
  }

  protected function marcarVisualizadoControlado(AcompanhamentoDTO $parObjAcompanhamentoDTO){
    try{

      $objProcedimentoDTO = $parObjAcompanhamentoDTO->getObjProcedimentoDTO();

      if ($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo()!=null){
        $objSinalizacaoFederacaoDTO = new SinalizacaoFederacaoDTO();
        $objSinalizacaoFederacaoDTO->setNumMaxRegistrosRetorno(1);
        $objSinalizacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objSinalizacaoFederacaoDTO->setStrIdProtocoloFederacao($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo());
        $objSinalizacaoFederacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objSinalizacaoFederacaoDTO->setNumStaSinalizacao(SinalizacaoFederacaoRN::$TSF_NENHUMA, InfraDTO::$OPER_DIFERENTE);

        $objSinalizacaoFederacaoRN = new SinalizacaoFederacaoRN();
        if ($objSinalizacaoFederacaoRN->consultar($objSinalizacaoFederacaoDTO)!=null){
          return;
        }
      }

      $objAcompanhamentoDTO = new AcompanhamentoDTO();
      $objAcompanhamentoDTO->setNumIdAcompanhamento($parObjAcompanhamentoDTO->getNumIdAcompanhamento());
      $objAcompanhamentoDTO->setNumTipoVisualizacao(AtividadeRN::$TV_VISUALIZADO);
      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());
      $objAcompanhamentoBD->alterar($objAcompanhamentoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro marcando visualização de Acompanhamento Especial.',$e);
    }
  }

  protected function montarIndexacaoControlado(AcompanhamentoDTO $parObjAcompanhamentoDTO){
    try{

      $objAcompanhamentoDTO = new AcompanhamentoDTO();
      $objAcompanhamentoDTO->retNumIdAcompanhamento();
      $objAcompanhamentoDTO->retStrProtocoloFormatado();
      $objAcompanhamentoDTO->retStrObservacao();
      $objAcompanhamentoDTO->retDthAlteracao();

      if (is_array($parObjAcompanhamentoDTO->getNumIdAcompanhamento())){
        $objAcompanhamentoDTO->setNumIdAcompanhamento($parObjAcompanhamentoDTO->getNumIdAcompanhamento(),InfraDTO::$OPER_IN);
      }else{
        $objAcompanhamentoDTO->setNumIdAcompanhamento($parObjAcompanhamentoDTO->getNumIdAcompanhamento());
      }

      $objInfraException = new InfraException();
      $objAcompanhamentoDTOIdx = new AcompanhamentoDTO();
      $objAcompanhamentoBD = new AcompanhamentoBD($this->getObjInfraIBanco());

      $arrObjAcompanhamentoDTO = $this->listar($objAcompanhamentoDTO);

      foreach($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO) {

        $objAcompanhamentoDTOIdx->setStrIdxAcompanhamento(InfraString::prepararIndexacao($objAcompanhamentoDTO->getStrProtocoloFormatado().' '.
                                                                                         $objAcompanhamentoDTO->getStrObservacao().' '.
                                                                                         $objAcompanhamentoDTO->getDthAlteracao()));
        $objAcompanhamentoDTOIdx->setNumIdAcompanhamento($objAcompanhamentoDTO->getNumIdAcompanhamento());

        $this->validarStrIdxAcompanhamento($objAcompanhamentoDTOIdx, $objInfraException);
        $objInfraException->lancarValidacoes();

        $objAcompanhamentoBD->alterar($objAcompanhamentoDTOIdx);
      }

    }catch(Exception $e){
      throw new InfraException('Erro montando indexação de Acompanhamento Especial.',$e);
    }
  }

  protected function complementarConectado($arrObjProcedimentoDTO){
    try {

      $objAcompanhamentoDTO  = new AcompanhamentoDTO();
      $objAcompanhamentoDTO->retNumIdAcompanhamento();
      $objAcompanhamentoDTO->retDblIdProtocolo();
      $objAcompanhamentoDTO->retNumTipoVisualizacao();
      $objAcompanhamentoDTO->retStrNomeGrupo();
      $objAcompanhamentoDTO->retStrObservacao();
      $objAcompanhamentoDTO->retDthAlteracao();
      $objAcompanhamentoDTO->retStrSiglaUsuario();
      $objAcompanhamentoDTO->retStrNomeUsuario();
      $objAcompanhamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAcompanhamentoDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);
      $objAcompanhamentoDTO->setOrdNumIdAcompanhamento(InfraDTO::$TIPO_ORDENACAO_ASC);
      $arrObjAcompanhamentoDTO = InfraArray::indexarArrInfraDTO($this->listarConectado($objAcompanhamentoDTO), 'IdProtocolo', true);

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if (isset($arrObjAcompanhamentoDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
          //$objProcedimentoDTO->setStrSinAcompanhamentos("S");
          $objProcedimentoDTO->setArrObjAcompanhamentoDTO($arrObjAcompanhamentoDTO[$objProcedimentoDTO->getDblIdProcedimento()]);
        } else {
          //$objProcedimentoDTO->setStrSinAcompanhamentos("N");
          $objProcedimentoDTO->setArrObjAcompanhamentoDTO(null);
        }
      }

    } catch (Exception $e) {
      throw new InfraException('Erro complementando Acompanhamento.', $e);
    }
  }

}
?>