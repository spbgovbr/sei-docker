<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/04/2019 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../SEI.php';

LimiteSEI::getInstance()->configurarNivel3();

class FederacaoWS extends InfraWS
{

  public function getObjInfraLog()
  {
    return LogSEI::getInstance();
  }

  public function solicitarRegistro($Identificacao)
  {
    try {

      //$IdInstalacaoFederacao, $Cnpj, $Sigla, $Descricao, $Endereco

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->verificarFederacao($Identificacao);

      $InstalacaoFederacao = $Identificacao->Remetente->Instalacao;
      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($InstalacaoFederacao->IdInstalacaoFederacao);
      $objInstalacaoFederacaoDTO->setDblCnpj(InfraUtil::retirarFormatacao($InstalacaoFederacao->Cnpj));
      $objInstalacaoFederacaoDTO->setStrSigla($InstalacaoFederacao->Sigla);
      $objInstalacaoFederacaoDTO->setStrDescricao($InstalacaoFederacao->Descricao);
      $objInstalacaoFederacaoDTO->setStrEndereco($InstalacaoFederacao->Endereco);
      $objInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->processarSolicitacaoRegistro($objInstalacaoFederacaoDTO);

      return array('IdInstalacaoFederacao' => $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao(),
          'Cnpj' => $objInstalacaoFederacaoDTO->getDblCnpj(),
          'Sigla' => $objInstalacaoFederacaoDTO->getStrSigla(),
          'Descricao' => $objInstalacaoFederacaoDTO->getStrDescricao(),
          'Endereco' => $objInstalacaoFederacaoDTO->getStrEndereco(),
          'StaEstado' => $objInstalacaoFederacaoDTO->getStrStaEstado()
      );

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function liberarRegistro($Identificacao)
  {
    try {
      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->verificarFederacao($Identificacao);

      $InstalacaoFederacao = $Identificacao->Remetente->Instalacao;

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($InstalacaoFederacao->IdInstalacaoFederacao);
      $objInstalacaoFederacaoDTO->setDblCnpj(InfraUtil::retirarFormatacao($InstalacaoFederacao->Cnpj));
      $objInstalacaoFederacaoDTO->setStrSigla($InstalacaoFederacao->Sigla);
      $objInstalacaoFederacaoDTO->setStrDescricao($InstalacaoFederacao->Descricao);
      $objInstalacaoFederacaoDTO->setStrEndereco($InstalacaoFederacao->Endereco);
      $objInstalacaoFederacaoDTO->setStrChavePublicaRemota($InstalacaoFederacao->ChavePublica);
      $objInstalacaoFederacaoRN->processarLiberacaoRegistro($objInstalacaoFederacaoDTO);

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function confirmarLiberacao($Identificacao)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->verificarFederacao($Identificacao);

      $InstalacaoFederacao = $Identificacao->Remetente->Instalacao;

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($InstalacaoFederacao->IdInstalacaoFederacao);
      $objInstalacaoFederacaoDTO->setDblCnpj(InfraUtil::retirarFormatacao($InstalacaoFederacao->Cnpj));
      $objInstalacaoFederacaoDTO->setStrSigla($InstalacaoFederacao->Sigla);
      $objInstalacaoFederacaoDTO->setStrHash($InstalacaoFederacao->Hash);
      $objInstalacaoFederacaoDTO->setStrChavePublicaRemota($InstalacaoFederacao->ChavePublica);
      $objInstalacaoFederacaoRN->processarConfirmacaoLiberacao($objInstalacaoFederacaoDTO);

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function bloquearRegistro($Identificacao)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->verificarFederacao($Identificacao);

      $InstalacaoFederacao = $Identificacao->Remetente->Instalacao;

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($InstalacaoFederacao->IdInstalacaoFederacao);
      $objInstalacaoFederacaoDTO->setStrSigla($InstalacaoFederacao->Sigla);
      $objInstalacaoFederacaoDTO->setStrDescricao($InstalacaoFederacao->Descricao);
      $objInstalacaoFederacaoDTO->setDblCnpj(InfraUtil::retirarFormatacao($InstalacaoFederacao->Cnpj));
      $objInstalacaoFederacaoRN->processarBloqueioRegistro($objInstalacaoFederacaoDTO);

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function verificarConexao($Identificacao)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      return InstalacaoFederacaoRN::$TC_OK;

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function pesquisarOrgaosUnidades($Identificacao, $PalavrasPesquisa)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
      $objAcessoFederacaoDTO->setStrPalavrasPesquisa($PalavrasPesquisa);

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $objInstalacaoFederacaoDTO = $objAcessoFederacaoRN->processarPesquisaOrgaosUnidades($objAcessoFederacaoDTO);

      $objInstalacao = new stdClass();
      $objInstalacao->IdInstalacaoFederacao = $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao();
      $objInstalacao->Sigla = $objInstalacaoFederacaoDTO->getStrSigla();
      $objInstalacao->Descricao = $objInstalacaoFederacaoDTO->getStrDescricao();

      $arrObjOrgaoFederacaoDTO = $objInstalacaoFederacaoDTO->getArrObjOrgaoFederacaoDTO();

      $objInstalacao = new stdClass();
      $objInstalacao->IdInstalacaoFederacao = $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao();
      $objInstalacao->Sigla = $objInstalacaoFederacaoDTO->getStrSigla();
      $objInstalacao->Descricao = $objInstalacaoFederacaoDTO->getStrDescricao();

      $arrOrgaos = array();
      foreach ($arrObjOrgaoFederacaoDTO as $objOrgaoFederacaoDTO) {

        $objOrgao = new stdClass();
        $objOrgao->IdOrgaoFederacao = $objOrgaoFederacaoDTO->getStrIdOrgaoFederacao();
        $objOrgao->Sigla = $objOrgaoFederacaoDTO->getStrSigla();
        $objOrgao->Descricao = $objOrgaoFederacaoDTO->getStrDescricao();

        $arrObjUnidadeFederacaoDTO = $objOrgaoFederacaoDTO->getArrObjUnidadeFederacaoDTO();

        $arrUnidades = array();
        foreach ($arrObjUnidadeFederacaoDTO as $objUnidadeFederacaoDTO) {
          $objUnidade = new stdClass();
          $objUnidade->IdUnidadeFederacao = $objUnidadeFederacaoDTO->getStrIdUnidadeFederacao();
          $objUnidade->Sigla = $objUnidadeFederacaoDTO->getStrSigla();
          $objUnidade->Descricao = $objUnidadeFederacaoDTO->getStrDescricao();
          $arrUnidades[] = $objUnidade;
        }
        $objOrgao->Unidades = $arrUnidades;

        $arrOrgaos[] = $objOrgao;
      }

      $objInstalacao->Orgaos = $arrOrgaos;

      return $objInstalacao;

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function concederAcesso($Identificacao, $Procedimento, $InstalacaoOrigem, $ProcedimentoOrigem, $Acessos, $Tipo, $Motivo, $DataHora)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $Remetente = $Identificacao->Remetente;
      $InstalacaoRemetente = $Remetente->Instalacao;
      $OrgaoRemetente = $Remetente->Orgao;
      $UnidadeRemetente = $Remetente->Unidade;
      $UsuarioRemetente = $Remetente->Usuario;

      $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
      $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($OrgaoRemetente->IdOrgaoFederacao);
      $objOrgaoFederacaoDTO->setStrIdInstalacaoFederacao($InstalacaoRemetente->IdInstalacaoFederacao);
      $objOrgaoFederacaoDTO->setStrSigla($OrgaoRemetente->Sigla);
      $objOrgaoFederacaoDTO->setStrDescricao($OrgaoRemetente->Descricao);

      $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
      $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($UnidadeRemetente->IdUnidadeFederacao);
      $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($InstalacaoRemetente->IdInstalacaoFederacao);
      $objUnidadeFederacaoDTO->setStrSigla($UnidadeRemetente->Sigla);
      $objUnidadeFederacaoDTO->setStrDescricao($UnidadeRemetente->Descricao);

      $objUsuarioFederacaoDTO = new UsuarioFederacaoDTO();
      $objUsuarioFederacaoDTO->setStrIdUsuarioFederacao($UsuarioRemetente->IdUsuarioFederacao);
      $objUsuarioFederacaoDTO->setStrIdInstalacaoFederacao($InstalacaoRemetente->IdInstalacaoFederacao);
      $objUsuarioFederacaoDTO->setStrSigla($UsuarioRemetente->Sigla);
      $objUsuarioFederacaoDTO->setStrNome($UsuarioRemetente->Nome);

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setStrIdProtocoloFederacaoProtocolo($Procedimento->IdProcedimentoFederacao);
      $objProcedimentoDTO->setNumIdTipoProcedimento($Procedimento->TipoProcedimento->IdTipoProcedimento);
      $objProcedimentoDTO->setStrNomeTipoProcedimento($Procedimento->TipoProcedimento->Nome);
      $objProcedimentoDTO->setStrProtocoloProcedimentoFormatado($Procedimento->ProtocoloFormatado);
      $objProcedimentoDTO->setDtaGeracaoProtocolo($Procedimento->DataAutuacao);
      $objProcedimentoDTO->setStrDescricaoProtocolo($Procedimento->Especificacao);
      $objProcedimentoDTO->setStrStaNivelAcessoLocalProtocolo($Procedimento->NivelAcesso);

      $arrObjParticipanteDTO = array();
      if (is_array($Procedimento->Interessados)) {
        $numInteressados = count($Procedimento->Interessados);
        for ($i = 0; $i < $numInteressados; $i++) {
          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->setStrSiglaContato($Procedimento->Interessados[$i]->Sigla);
          $objParticipanteDTO->setStrNomeContato($Procedimento->Interessados[$i]->Nome);
          $arrObjParticipanteDTO[] = $objParticipanteDTO;
        }
      }
      $objProcedimentoDTO->setArrObjParticipanteDTO($arrObjParticipanteDTO);

      $objInstalacaoFederacaoDTOOrigem = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTOOrigem->setStrIdInstalacaoFederacao($InstalacaoOrigem->IdInstalacaoFederacao);
      $objInstalacaoFederacaoDTOOrigem->setStrSigla($InstalacaoOrigem->Sigla);
      $objInstalacaoFederacaoDTOOrigem->setStrDescricao($InstalacaoOrigem->Descricao);
      $objInstalacaoFederacaoDTOOrigem->setDblCnpj(InfraUtil::retirarFormatacao($InstalacaoOrigem->Cnpj));
      $objInstalacaoFederacaoDTOOrigem->setStrEndereco($InstalacaoOrigem->Endereco);

      $objProcedimentoDTOOrigem = new ProcedimentoDTO();
      $objProcedimentoDTOOrigem->setStrIdProtocoloFederacaoProtocolo($ProcedimentoOrigem->IdProcedimentoFederacao);
      $objProcedimentoDTOOrigem->setStrProtocoloProcedimentoFormatado($ProcedimentoOrigem->ProtocoloFormatado);
      $objProcedimentoDTOOrigem->setDtaGeracaoProtocolo($ProcedimentoOrigem->DataAutuacao);

      $arrObjAcessoFederacaoDTO = array();

      $numAcessos = count($Acessos);
      for ($i = 0; $i < $numAcessos; $i++) {
        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->setStrIdAcessoFederacao($Acessos[$i]->IdAcessoFederacao);
        $objAcessoFederacaoDTO->setStrIdOrgaoFederacaoDest($Acessos[$i]->IdOrgaoFederacaoDest);
        $objAcessoFederacaoDTO->setStrIdUnidadeFederacaoDest($Acessos[$i]->IdUnidadeFederacaoDest);
        $arrObjAcessoFederacaoDTO[] = $objAcessoFederacaoDTO;
      }

      $objReceberProcessoFederacaoDTO = new ReceberProcessoFederacaoDTO();
      $objReceberProcessoFederacaoDTO->setObjInstalacaoFederacaoDTORemetente($objInstalacaoFederacaoDTO);
      $objReceberProcessoFederacaoDTO->setObjOrgaoFederacaoDTORemetente($objOrgaoFederacaoDTO);
      $objReceberProcessoFederacaoDTO->setObjUnidadeFederacaoDTORemetente($objUnidadeFederacaoDTO);
      $objReceberProcessoFederacaoDTO->setObjUsuarioFederacaoDTORemetente($objUsuarioFederacaoDTO);
      $objReceberProcessoFederacaoDTO->setObjProcedimentoDTO($objProcedimentoDTO);
      $objReceberProcessoFederacaoDTO->setObjInstalacaoFederacaoDTOOrigem($objInstalacaoFederacaoDTOOrigem);
      $objReceberProcessoFederacaoDTO->setObjProcedimentoDTOOrigem($objProcedimentoDTOOrigem);
      $objReceberProcessoFederacaoDTO->setArrObjAcessoFederacaoDTO($arrObjAcessoFederacaoDTO);
      $objReceberProcessoFederacaoDTO->setNumStaTipo($Tipo);
      $objReceberProcessoFederacaoDTO->setStrMotivo($Motivo);
      $objReceberProcessoFederacaoDTO->setDthDataHora($DataHora);

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $arrObjAcessoFederacaoDTO = $objAcessoFederacaoRN->processarConcessaoAcesso($objReceberProcessoFederacaoDTO);


      $arrRet = array();
      foreach ($arrObjAcessoFederacaoDTO as $objAcessoFederacaoDTO) {
        $objAcessoFederacao = new stdClass();
        $objAcessoFederacao->IdAcessoFederacao = $objAcessoFederacaoDTO->getStrIdAcessoFederacao();
        $objAcessoFederacao->IdOrgaoFederacaoDest = $objAcessoFederacaoDTO->getStrIdOrgaoFederacaoDest();
        $objAcessoFederacao->IdUnidadeFederacaoDest = $objAcessoFederacaoDTO->getStrIdUnidadeFederacaoDest();
        $arrRet[] = $objAcessoFederacao;
      }

      return $arrRet;

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function replicarAcessos($Identificacao, $Procedimento, $Acessos, $Instalacoes, $Orgaos, $Unidades, $Usuarios, $ProtocolosFederacao)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoDTORemetente = $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $Remetente = $Identificacao->Remetente;
      $InstalacaoRemetente = $Remetente->Instalacao;
      $OrgaoRemetente = $Remetente->Orgao;
      $UnidadeRemetente = $Remetente->Unidade;

      $objOrgaoFederacaoDTORemetente = new OrgaoFederacaoDTO();
      $objOrgaoFederacaoDTORemetente->setStrIdOrgaoFederacao($OrgaoRemetente->IdOrgaoFederacao);
      $objOrgaoFederacaoDTORemetente->setStrIdInstalacaoFederacao($InstalacaoRemetente->IdInstalacaoFederacao);
      $objOrgaoFederacaoDTORemetente->setStrSigla($OrgaoRemetente->Sigla);
      $objOrgaoFederacaoDTORemetente->setStrDescricao($OrgaoRemetente->Descricao);

      $objUnidadeFederacaoDTORemetente = new UnidadeFederacaoDTO();
      $objUnidadeFederacaoDTORemetente->setStrIdUnidadeFederacao($UnidadeRemetente->IdUnidadeFederacao);
      $objUnidadeFederacaoDTORemetente->setStrIdInstalacaoFederacao($InstalacaoRemetente->IdInstalacaoFederacao);
      $objUnidadeFederacaoDTORemetente->setStrSigla($UnidadeRemetente->Sigla);
      $objUnidadeFederacaoDTORemetente->setStrDescricao($UnidadeRemetente->Descricao);

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setStrIdProtocoloFederacaoProtocolo($Procedimento->IdProcedimentoFederacao);
      $objProcedimentoDTO->setStrVersaoAcessos($Procedimento->VersaoAcessos);

      $arrObjAcessoFederacaoDTO = array();
      $numAcessos = count($Acessos);
      for ($i = 0; $i < $numAcessos; $i++) {
        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->setStrIdAcessoFederacao($Acessos[$i]->IdAcessoFederacao);
        $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoRem($Acessos[$i]->IdInstalacaoFederacaoRem);
        $objAcessoFederacaoDTO->setStrIdOrgaoFederacaoRem($Acessos[$i]->IdOrgaoFederacaoRem);
        $objAcessoFederacaoDTO->setStrIdUnidadeFederacaoRem($Acessos[$i]->IdUnidadeFederacaoRem);
        $objAcessoFederacaoDTO->setStrIdUsuarioFederacaoRem($Acessos[$i]->IdUsuarioFederacaoRem);
        $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoDest($Acessos[$i]->IdInstalacaoFederacaoDest);
        $objAcessoFederacaoDTO->setStrIdOrgaoFederacaoDest($Acessos[$i]->IdOrgaoFederacaoDest);
        $objAcessoFederacaoDTO->setStrIdUnidadeFederacaoDest($Acessos[$i]->IdUnidadeFederacaoDest);
        $objAcessoFederacaoDTO->setStrIdUsuarioFederacaoDest($Acessos[$i]->IdUsuarioFederacaoDest);
        $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($Acessos[$i]->IdProcedimentoFederacao);
        $objAcessoFederacaoDTO->setStrIdDocumentoFederacao($Acessos[$i]->IdDocumentoFederacao);
        $objAcessoFederacaoDTO->setDthLiberacao($Acessos[$i]->DthLiberacao);
        $objAcessoFederacaoDTO->setStrMotivoLiberacao($Acessos[$i]->MotivoLiberacao);
        $objAcessoFederacaoDTO->setDthCancelamento($Acessos[$i]->DthCancelamento);
        $objAcessoFederacaoDTO->setStrMotivoCancelamento($Acessos[$i]->MotivoCancelamento);
        $objAcessoFederacaoDTO->setNumStaTipo($Acessos[$i]->StaTipo);
        $objAcessoFederacaoDTO->setStrSinAtivo($Acessos[$i]->SinAtivo);
        $arrObjAcessoFederacaoDTO[] = $objAcessoFederacaoDTO;
      }
      
      $arrObjInstalacaoFederacaoDTO = array();
      $numInstalacoes = count($Instalacoes);
      for ($i = 0; $i < $numInstalacoes; $i++) {
        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($Instalacoes[$i]->IdInstalacaoFederacao);
        $objInstalacaoFederacaoDTO->setDblCnpj($Instalacoes[$i]->Cnpj);
        $objInstalacaoFederacaoDTO->setStrSigla($Instalacoes[$i]->Sigla);
        $objInstalacaoFederacaoDTO->setStrDescricao($Instalacoes[$i]->Descricao);
        $objInstalacaoFederacaoDTO->setStrEndereco($Instalacoes[$i]->Endereco);
        $arrObjInstalacaoFederacaoDTO[] = $objInstalacaoFederacaoDTO;
      }
      
      $arrObjOrgaoFederacaoDTO = array();
      $numOrgaos = count($Orgaos);
      for ($i = 0; $i < $numOrgaos; $i++) {
        $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
        $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($Orgaos[$i]->IdOrgaoFederacao);
        $objOrgaoFederacaoDTO->setStrIdInstalacaoFederacao($Orgaos[$i]->IdInstalacaoFederacao);
        $objOrgaoFederacaoDTO->setStrSigla($Orgaos[$i]->Sigla);
        $objOrgaoFederacaoDTO->setStrDescricao($Orgaos[$i]->Descricao);
        $arrObjOrgaoFederacaoDTO[] = $objOrgaoFederacaoDTO;
      }

      $arrObjUnidadeFederacaoDTO = array();
      $numUnidades = count($Unidades);
      for ($i = 0; $i < $numUnidades; $i++) {
        $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
        $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($Unidades[$i]->IdUnidadeFederacao);
        $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($Unidades[$i]->IdInstalacaoFederacao);
        $objUnidadeFederacaoDTO->setStrSigla($Unidades[$i]->Sigla);
        $objUnidadeFederacaoDTO->setStrDescricao($Unidades[$i]->Descricao);
        $arrObjUnidadeFederacaoDTO[] = $objUnidadeFederacaoDTO;
      }

      $arrObjUsuarioFederacaoDTO = array();
      $numUsuarios = count($Usuarios);
      for ($i = 0; $i < $numUsuarios; $i++) {
        $objUsuarioFederacaoDTO = new UsuarioFederacaoDTO();
        $objUsuarioFederacaoDTO->setStrIdUsuarioFederacao($Usuarios[$i]->IdUsuarioFederacao);
        $objUsuarioFederacaoDTO->setStrIdInstalacaoFederacao($Usuarios[$i]->IdInstalacaoFederacao);
        $objUsuarioFederacaoDTO->setStrSigla($Usuarios[$i]->Sigla);
        $objUsuarioFederacaoDTO->setStrNome($Usuarios[$i]->Nome);
        $arrObjUsuarioFederacaoDTO[] = $objUsuarioFederacaoDTO;
      }

      $arrObjProtocoloFederacaoDTO = array();
      $numDocumentos = count($ProtocolosFederacao);
      for ($i = 0; $i < $numDocumentos; $i++) {
        $objProtocoloFederacaoDTO = new ProtocoloFederacaoDTO();
        $objProtocoloFederacaoDTO->setStrIdProtocoloFederacao($ProtocolosFederacao[$i]->IdProtocoloFederacao);
        $objProtocoloFederacaoDTO->setStrIdInstalacaoFederacao($ProtocolosFederacao[$i]->IdInstalacaoFederacao);
        $objProtocoloFederacaoDTO->setStrProtocoloFormatado($ProtocolosFederacao[$i]->ProtocoloFormatado);
        $arrObjProtocoloFederacaoDTO[] = $objProtocoloFederacaoDTO;
      }

      $objReplicarAcessosFederacaoDTO = new ReplicarAcessosFederacaoDTO();
      $objReplicarAcessosFederacaoDTO->setObjInstalacaoFederacaoDTORemetente($objInstalacaoFederacaoDTORemetente);
      $objReplicarAcessosFederacaoDTO->setObjOrgaoFederacaoDTORemetente($objOrgaoFederacaoDTORemetente);
      $objReplicarAcessosFederacaoDTO->setObjUnidadeFederacaoDTORemetente($objUnidadeFederacaoDTORemetente);
      $objReplicarAcessosFederacaoDTO->setObjProcedimentoDTO($objProcedimentoDTO);
      $objReplicarAcessosFederacaoDTO->setArrObjAcessoFederacaoDTO($arrObjAcessoFederacaoDTO);
      $objReplicarAcessosFederacaoDTO->setArrObjInstalacaoFederacaoDTO($arrObjInstalacaoFederacaoDTO);
      $objReplicarAcessosFederacaoDTO->setArrObjOrgaoFederacaoDTO($arrObjOrgaoFederacaoDTO);
      $objReplicarAcessosFederacaoDTO->setArrObjUnidadeFederacaoDTO($arrObjUnidadeFederacaoDTO);
      $objReplicarAcessosFederacaoDTO->setArrObjUsuarioFederacaoDTO($arrObjUsuarioFederacaoDTO);
      $objReplicarAcessosFederacaoDTO->setArrObjProtocoloFederacaoDTO($arrObjProtocoloFederacaoDTO);


      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      if ($objAcessoFederacaoRN->processarReplicacaoAcessos($objReplicarAcessosFederacaoDTO)) {

        try {
          $objAcessoFederacaoDTOReplicacao = new AcessoFederacaoDTO();
          $objAcessoFederacaoDTOReplicacao->setStrIdInstalacaoFederacaoDest($objInstalacaoFederacaoDTORemetente->getStrIdInstalacaoFederacao());
          $objAcessoFederacaoDTOReplicacao->setStrIdProcedimentoFederacao($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo());
          $objAcessoFederacaoRN->replicarAcessos($objAcessoFederacaoDTOReplicacao);
        }catch(Exception $e){
          LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
        }
      }

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function replicarSinalizacoes($Identificacao, $Sinalizacoes)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoDTORemetente = $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $Remetente = $Identificacao->Remetente;
      $InstalacaoRemetente = $Remetente->Instalacao;
      $OrgaoRemetente = $Remetente->Orgao;
      $UnidadeRemetente = $Remetente->Unidade;

      $objOrgaoFederacaoDTORemetente = new OrgaoFederacaoDTO();
      $objOrgaoFederacaoDTORemetente->setStrIdOrgaoFederacao($OrgaoRemetente->IdOrgaoFederacao);
      $objOrgaoFederacaoDTORemetente->setStrIdInstalacaoFederacao($InstalacaoRemetente->IdInstalacaoFederacao);
      $objOrgaoFederacaoDTORemetente->setStrSigla($OrgaoRemetente->Sigla);
      $objOrgaoFederacaoDTORemetente->setStrDescricao($OrgaoRemetente->Descricao);

      $objUnidadeFederacaoDTORemetente = new UnidadeFederacaoDTO();
      $objUnidadeFederacaoDTORemetente->setStrIdUnidadeFederacao($UnidadeRemetente->IdUnidadeFederacao);
      $objUnidadeFederacaoDTORemetente->setStrIdInstalacaoFederacao($InstalacaoRemetente->IdInstalacaoFederacao);
      $objUnidadeFederacaoDTORemetente->setStrSigla($UnidadeRemetente->Sigla);
      $objUnidadeFederacaoDTORemetente->setStrDescricao($UnidadeRemetente->Descricao);

      $arrObjSinalizacaoFederacaoDTO = array();
      $numSinalizacoes = count($Sinalizacoes);
      for ($i = 0; $i < $numSinalizacoes; $i++) {
        $objSinalizacaoFederacaoDTO = new SinalizacaoFederacaoDTO();
        $objSinalizacaoFederacaoDTO->setStrIdProtocoloFederacao($Sinalizacoes[$i]->IdProtocoloFederacao);
        $objSinalizacaoFederacaoDTO->setDthSinalizacao($Sinalizacoes[$i]->DthSinalizacao);
        $objSinalizacaoFederacaoDTO->setNumStaSinalizacao($Sinalizacoes[$i]->StaSinalizacao);
        $arrObjSinalizacaoFederacaoDTO[] = $objSinalizacaoFederacaoDTO;
      }

      $objReplicarSinalizacoesFederacaoDTO = new ReplicarSinalizacoesFederacaoDTO();
      $objReplicarSinalizacoesFederacaoDTO->setObjInstalacaoFederacaoDTORemetente($objInstalacaoFederacaoDTORemetente);
      $objReplicarSinalizacoesFederacaoDTO->setObjOrgaoFederacaoDTORemetente($objOrgaoFederacaoDTORemetente);
      $objReplicarSinalizacoesFederacaoDTO->setObjUnidadeFederacaoDTORemetente($objUnidadeFederacaoDTORemetente);
      $objReplicarSinalizacoesFederacaoDTO->setArrObjSinalizacaoFederacaoDTO($arrObjSinalizacaoFederacaoDTO);

      $objSinalizacaoFederacaoRN = new SinalizacaoFederacaoRN();
      $objSinalizacaoFederacaoRN->processarReplicacaoSinalizacoes($objReplicarSinalizacoesFederacaoDTO);

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function visualizarProcesso($Identificacao, $VisualizacaoProcesso)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $Procedimento = $VisualizacaoProcesso->Procedimento;

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $objVisualizarProcessoFederacaoDTO = new VisualizarProcessoFederacaoDTO();
      $objVisualizarProcessoFederacaoDTO->setObjInstalacaoFederacaoDTO($objInstalacaoFederacaoDTO);

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setStrIdProtocoloFederacaoProtocolo($Procedimento->IdProcedimentoFederacao);
      $objProcedimentoDTO->setStrVersaoAcessos($Procedimento->VersaoAcessos);
      $objVisualizarProcessoFederacaoDTO->setObjProcedimentoDTO($objProcedimentoDTO);

      $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacao($Procedimento->IdProcedimentoFederacao);
      $objVisualizarProcessoFederacaoDTO->setStrSinProtocolos($VisualizacaoProcesso->SinProtocolos);
      $objVisualizarProcessoFederacaoDTO->setNumPagProtocolos($VisualizacaoProcesso->PagProtocolos);

      $numMaxProtocolos = ConfiguracaoSEI::getInstance()->getValor('Federacao', 'NumMaxProtocolosConsulta', false);
      if (!is_numeric($numMaxProtocolos) || $numMaxProtocolos <= 0) {
        $numMaxProtocolos = 100;
      }
      $objVisualizarProcessoFederacaoDTO->setNumMaxProtocolos($numMaxProtocolos);

      $objVisualizarProcessoFederacaoDTO->setStrSinAndamentos($VisualizacaoProcesso->SinAndamentos);
      $objVisualizarProcessoFederacaoDTO->setNumPagAndamentos($VisualizacaoProcesso->PagAndamentos);

      $numMaxAndamentos = ConfiguracaoSEI::getInstance()->getValor('Federacao', 'NumMaxAndamentosConsulta', false);
      if (!is_numeric($numMaxAndamentos) || $numMaxAndamentos <= 0) {
        $numMaxAndamentos = 100;
      }
      $objVisualizarProcessoFederacaoDTO->setNumMaxAndamentos($numMaxAndamentos);

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $objVisualizarProcessoFederacaoDTORet = $objAcessoFederacaoRN->processarVisualizacaoProcesso($objVisualizarProcessoFederacaoDTO);

      $objProcedimentoDTO = $objVisualizarProcessoFederacaoDTORet->getObjProcedimentoDTO();

      $arrOrgaos = array();
      $arrUnidades = array();
      $arrUsuarios = array();
      $arrProtocolos = array();

      $objProcedimento = new stdClass();
      $objProcedimento->IdProcedimentoFederacao = $objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo();
      $objProcedimento->ProtocoloFormatado = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();
      $objProcedimento->NivelAcesso = $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo();
      $objTipoProcedimento = new stdClass();
      $objTipoProcedimento->IdTipoProcedimento = $objProcedimentoDTO->getNumIdTipoProcedimento();
      $objTipoProcedimento->Nome = $objProcedimentoDTO->getStrNomeTipoProcedimento();
      $objProcedimento->TipoProcedimento = $objTipoProcedimento;
      $objProcedimento->DataAutuacao = $objProcedimentoDTO->getDtaGeracaoProtocolo();
      $objProcedimento->VersaoAcessos = $objProcedimentoDTO->getStrVersaoAcessos();

      $arrInteressados = array();
      foreach($objProcedimentoDTO->getArrObjParticipanteDTO() as $objParticipanteDTO){
        $objInteressado = new stdClass();
        $objInteressado->Sigla = $objParticipanteDTO->getStrSiglaContato();
        $objInteressado->Nome = $objParticipanteDTO->getStrNomeContato();
        $arrInteressados[] = $objInteressado;
      }
      $objProcedimento->Interessados = $arrInteressados;

      $objVisualizacaoProcessoRet = new stdClass();
      $objVisualizacaoProcessoRet->Procedimento = $objProcedimento;

      $objUnidade = new stdClass();
      $objUnidade->IdUnidade = $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo();
      $objVisualizacaoProcessoRet->UnidadeOrigem = $objUnidade;

      $objUnidade = new stdClass();
      $objUnidade->IdUnidade = $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo();
      $objUnidade->IdOrgao = $objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo();
      $objUnidade->Sigla = $objProcedimentoDTO->getStrSiglaUnidadeGeradoraProtocolo();
      $objUnidade->Descricao = $objProcedimentoDTO->getStrDescricaoUnidadeGeradoraProtocolo();
      $arrUnidades[$objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo()] = $objUnidade;


      if ($VisualizacaoProcesso->SinProtocolos == 'S') {
        foreach ($objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO() as $objRelProtocoloProtocoloDTO) {

          if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

            $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

            $objProtocolo = new stdClass();
            $objProtocolo->StaProtocolo = $objDocumentoDTO->getStrStaProtocoloProtocolo();
            $objProtocolo->StaEstado = $objDocumentoDTO->getStrStaEstadoProtocolo();
            $objProtocolo->SinAcesso = $objRelProtocoloProtocoloDTO->getStrSinAcessoBasico();

            $objDocumento = new stdClass();
            $objDocumento->IdDocumentoFederacao = $objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo();
            $objDocumento->ProtocoloFormatado = $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
            $objDocumento->Numero = $objDocumentoDTO->getStrNumero();
            $objDocumento->DataGeracao = $objDocumentoDTO->getDtaGeracaoProtocolo();
            $objDocumento->SinPdf = $objDocumentoDTO->getStrSinPdf();
            $objDocumento->SinZip = $objDocumentoDTO->getStrSinZip();

            $objSerie = new stdClass();
            $objSerie->IdSerie = $objDocumentoDTO->getNumIdSerie();
            $objSerie->Nome = $objDocumentoDTO->getStrNomeSerie();

            $objDocumento->Serie = $objSerie;

            $arrAssinaturas = null;
            if ($objDocumentoDTO->isSetArrObjAssinaturaDTO() && count($objDocumentoDTO->getArrObjAssinaturaDTO())) {
              $arrAssinaturas = array();
              foreach ($objDocumentoDTO->getArrObjAssinaturaDTO() as $objAssinaturaDTO) {
                $objAssinatura = new stdClass();
                $objAssinatura->IdUsuario = $objAssinaturaDTO->getNumIdUsuario();
                $objAssinatura->Nome = $objAssinaturaDTO->getStrNome();
                $objAssinatura->CargoFuncao = $objAssinaturaDTO->getStrTratamento();
                $arrAssinaturas[] = $objAssinatura;
              }
            }
            $objDocumento->Assinaturas = $arrAssinaturas;

            $objPublicacao = null;
            if ($objDocumentoDTO->isSetObjPublicacaoDTO() && $objDocumentoDTO->getObjPublicacaoDTO() != null) {
              $objPublicacao = new stdClass();
              $objPublicacao->IdPublicacao = $objDocumentoDTO->getObjPublicacaoDTO()->getNumIdPublicacao();
              $objPublicacao->TextoInformativo = PublicacaoINT::obterTextoInformativoPublicacao($objDocumentoDTO);
            }
            $objDocumento->Publicacao = $objPublicacao;

            $objProtocolo->Documento = $objDocumento;

            $objUnidade = new stdClass();
            $objUnidade->IdUnidade = $objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo();
            $objProtocolo->Unidade = $objUnidade;

            if (!isset($arrUnidades[$objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()])) {
              $objUnidade = new stdClass();
              $objUnidade->IdUnidade = $objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo();
              $objUnidade->IdOrgao = $objDocumentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo();
              $objUnidade->Sigla = $objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo();
              $objUnidade->Descricao = $objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo();
              $arrUnidades[$objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()] = $objUnidade;
            }

            $arrProtocolos[] = $objProtocolo;

          } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

            $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

            $objProtocolo = new stdClass();
            $objProtocolo->StaProtocolo = ProtocoloRN::$TP_PROCEDIMENTO;
            $objProtocolo->StaEstado = $objProcedimentoDTOAnexado->getStrStaEstadoProtocolo();
            $objProtocolo->SinAcesso = $objRelProtocoloProtocoloDTO->getStrSinAcessoBasico();

            $objProcedimento = new stdClass();
            $objProcedimento->IdProcedimentoFederacao = $objProcedimentoDTOAnexado->getStrIdProtocoloFederacaoProtocolo();
            $objProcedimento->ProtocoloFormatado = $objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado();
            $objProcedimento->DataAutuacao = $objProcedimentoDTOAnexado->getDtaGeracaoProtocolo();

            $objTipoProcedimento = new stdClass();
            $objTipoProcedimento->IdTipoProcedimento = $objProcedimentoDTOAnexado->getNumIdTipoProcedimento();
            $objTipoProcedimento->Nome = $objProcedimentoDTOAnexado->getStrNomeTipoProcedimento();

            $objProcedimento->TipoProcedimento = $objTipoProcedimento;

            $objProtocolo->Procedimento = $objProcedimento;

            $objUnidade = new stdClass();
            $objUnidade->IdUnidade = $objProcedimentoDTOAnexado->getNumIdUnidadeGeradoraProtocolo();
            $objProtocolo->Unidade = $objUnidade;

            if (!isset($arrUnidades[$objProcedimentoDTOAnexado->getNumIdUnidadeGeradoraProtocolo()])) {
              $objUnidade = new stdClass();
              $objUnidade->IdUnidade = $objProcedimentoDTOAnexado->getNumIdUnidadeGeradoraProtocolo();
              $objUnidade->IdOrgao = $objProcedimentoDTOAnexado->getNumIdOrgaoUnidadeGeradoraProtocolo();
              $objUnidade->Sigla = $objProcedimentoDTOAnexado->getStrSiglaUnidadeGeradoraProtocolo();
              $objUnidade->Descricao = $objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo();
              $arrUnidades[$objProcedimentoDTOAnexado->getNumIdUnidadeGeradoraProtocolo()] = $objUnidade;
            }

            $arrProtocolos[] = $objProtocolo;
          }
        }
      }
      $objVisualizacaoProcessoRet->Protocolos = $arrProtocolos;
      $objVisualizacaoProcessoRet->MaxProtocolos = $numMaxProtocolos;
      $objVisualizacaoProcessoRet->RegProtocolos = $objVisualizarProcessoFederacaoDTORet->getNumRegProtocolos();
      $objVisualizacaoProcessoRet->TotProtocolos = $objVisualizarProcessoFederacaoDTORet->getNumTotProtocolos();

      $arrAndamentos = array();

      if ($VisualizacaoProcesso->SinAndamentos == 'S') {
        foreach ($objProcedimentoDTO->getArrObjAtividadeDTO() as $objAtividadeDTO) {

          $objAndamento = new stdClass();
          $objAndamento->DataHora = $objAtividadeDTO->getDthAbertura();
          $objAndamento->SinAberto = $objAtividadeDTO->getStrSinUltimaUnidadeHistorico();
          $objAndamento->Descricao = $objAtividadeDTO->getStrNomeTarefa();

          $objUsuario = new stdClass();
          $objUsuario->IdUsuario = $objAtividadeDTO->getNumIdUsuarioOrigem();
          $objAndamento->Usuario = $objUsuario;

          $objUnidade = new stdClass();
          $objUnidade->IdUnidade = $objAtividadeDTO->getNumIdUnidade();
          $objAndamento->Unidade = $objUnidade;

          if (!isset($arrUsuarios[$objAtividadeDTO->getNumIdUsuarioOrigem()])) {
            $objUsuario = new stdClass();
            $objUsuario->IdUsuario = $objAtividadeDTO->getNumIdUsuarioOrigem();
            $objUsuario->Sigla = $objAtividadeDTO->getStrSiglaUsuarioOrigem();
            $objUsuario->Nome = $objAtividadeDTO->getStrNomeUsuarioOrigem();
            $arrUsuarios[$objAtividadeDTO->getNumIdUsuarioOrigem()] = $objUsuario;
          }

          if (!isset($arrUnidades[$objAtividadeDTO->getNumIdUnidade()])) {
            $objUnidade = new stdClass();
            $objUnidade->IdUnidade = $objAtividadeDTO->getNumIdUnidade();
            $objUnidade->IdOrgao = $objAtividadeDTO->getNumIdOrgaoUnidade();
            $objUnidade->Sigla = $objAtividadeDTO->getStrSiglaUnidade();
            $objUnidade->Descricao = $objAtividadeDTO->getStrDescricaoUnidade();
            $arrUnidades[$objAtividadeDTO->getNumIdUnidade()] = $objUnidade;
          }

          $arrAndamentos[] = $objAndamento;
        }
      }
      $objVisualizacaoProcessoRet->Andamentos = $arrAndamentos;
      $objVisualizacaoProcessoRet->MaxAndamentos = $numMaxAndamentos;
      $objVisualizacaoProcessoRet->RegAndamentos = $objVisualizarProcessoFederacaoDTORet->getNumRegAndamentos();
      $objVisualizacaoProcessoRet->TotAndamentos = $objVisualizarProcessoFederacaoDTORet->getNumTotAndamentos();

      $arrIdOrgaos = array();
      if (count($arrUnidades)) {

        foreach ($arrUnidades as $objUnidade) {
          $arrIdOrgaos[$objUnidade->IdOrgao] = true;
        }

        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setBolExclusaoLogica(false);
        $objOrgaoDTO->retNumIdOrgao();
        $objOrgaoDTO->retStrSigla();
        $objOrgaoDTO->retStrDescricao();
        $objOrgaoDTO->setNumIdOrgao(array_keys($arrIdOrgaos), InfraDTO::$OPER_IN);

        $objOrgaoRN = new OrgaoRN();
        $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

        foreach ($arrObjOrgaoDTO as $objOrgaoDTO) {
          $objOrgao = new stdClass();
          $objOrgao->IdOrgao = $objOrgaoDTO->getNumIdOrgao();
          $objOrgao->Sigla = $objOrgaoDTO->getStrSigla();
          $objOrgao->Descricao = $objOrgaoDTO->getStrDescricao();
          $arrOrgaos[] = $objOrgao;
        }
      }

      $objVisualizacaoProcessoRet->Orgaos = $arrOrgaos;
      $objVisualizacaoProcessoRet->Unidades = $arrUnidades;
      $objVisualizacaoProcessoRet->Usuarios = $arrUsuarios;

      return $objVisualizacaoProcessoRet;

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function visualizarDocumento($Identificacao, $Procedimento, $Documento)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $objAcessoFederacaoDTO = new AcessoFederacaoDTO();

      $Remetente = $Identificacao->Remetente;
      $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoRem($Remetente->Instalacao->IdInstalacaoFederacao);
      $objAcessoFederacaoDTO->setStrIdOrgaoFederacaoRem($Remetente->Orgao->IdOrgaoFederacao);
      $objAcessoFederacaoDTO->setStrIdUnidadeFederacaoRem($Remetente->Unidade->IdUnidadeFederacao);
      $objAcessoFederacaoDTO->setStrIdUsuarioFederacaoRem($Remetente->Usuario->IdUsuarioFederacao);
      $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($Procedimento->IdProcedimentoFederacao);
      $objAcessoFederacaoDTO->setStrIdDocumentoFederacao($Documento->IdDocumentoFederacao);

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $objVisualizarDocumentoFederacaoDTO = $objAcessoFederacaoRN->processarVisualizacaoDocumento($objAcessoFederacaoDTO);

      $objVisualizacaoDocumento = new stdClass();

      $objDocumentoDTO = $objVisualizarDocumentoFederacaoDTO->getObjDocumentoDTO();
      $objDocumento = new stdClass();
      $objDocumento->IdDocumentoFederacao = $objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo();
      $objVisualizacaoDocumento->Documento = $objDocumento;

      $objVisualizacaoDocumento->LinkAcesso = $objVisualizarDocumentoFederacaoDTO->getStrLinkAcesso();

      return $objVisualizacaoDocumento;

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function gerarPdf($Identificacao, $Procedimento, $Protocolos)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $Remetente = $Identificacao->Remetente;

      $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
      $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoRem($Remetente->Instalacao->IdInstalacaoFederacao);
      $objAcessoFederacaoDTO->setStrIdOrgaoFederacaoRem($Remetente->Orgao->IdOrgaoFederacao);
      $objAcessoFederacaoDTO->setStrIdUnidadeFederacaoRem($Remetente->Unidade->IdUnidadeFederacao);
      $objAcessoFederacaoDTO->setStrIdUsuarioFederacaoRem($Remetente->Usuario->IdUsuarioFederacao);
      $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($Procedimento->IdProcedimentoFederacao);

      $arrIdProtocoloFederacao = array();
      if (is_array($Protocolos)) {
        $numProtocolos = count($Protocolos);
        for ($i = 0; $i < $numProtocolos; $i++) {
          $arrIdProtocoloFederacao[] = $Protocolos[$i]->IdProtocoloFederacao;
        }
      }
      $objAcessoFederacaoDTO->setStrIdDocumentoFederacao($arrIdProtocoloFederacao);

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $strLinkAcesso = $objAcessoFederacaoRN->processarGeracaoPdf($objAcessoFederacaoDTO);

      return $strLinkAcesso;

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }

  public function gerarZip($Identificacao, $Procedimento, $Protocolos)
  {
    try {

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_INTERNET, SessaoSEI::$UNIDADE_TESTE);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $objInstalacaoFederacaoRN->autenticarWS($Identificacao);

      $Remetente = $Identificacao->Remetente;

      $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
      $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoRem($Remetente->Instalacao->IdInstalacaoFederacao);
      $objAcessoFederacaoDTO->setStrIdOrgaoFederacaoRem($Remetente->Orgao->IdOrgaoFederacao);
      $objAcessoFederacaoDTO->setStrIdUnidadeFederacaoRem($Remetente->Unidade->IdUnidadeFederacao);
      $objAcessoFederacaoDTO->setStrIdUsuarioFederacaoRem($Remetente->Usuario->IdUsuarioFederacao);
      $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($Procedimento->IdProcedimentoFederacao);

      $arrIdProtocoloFederacao = array();
      if (is_array($Protocolos)) {
        $numProtocolos = count($Protocolos);
        for ($i = 0; $i < $numProtocolos; $i++) {
          $arrIdProtocoloFederacao[] = $Protocolos[$i]->IdProtocoloFederacao;
        }
      }
      $objAcessoFederacaoDTO->setStrIdDocumentoFederacao($arrIdProtocoloFederacao);

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $strLinkAcesso = $objAcessoFederacaoRN->processarGeracaoZip($objAcessoFederacaoDTO);

      return $strLinkAcesso;

    } catch (Throwable $e) {
      $this->processarExcecao($e);
    }
  }
}

$servidorSoap = new SoapServer("federacao.wsdl",array('encoding'=>'ISO-8859-1'));

$servidorSoap->setClass("FederacaoWS");

//Só processa se acessado via POST
if ($_SERVER['REQUEST_METHOD']=='POST') {
  $servidorSoap->handle();
}
