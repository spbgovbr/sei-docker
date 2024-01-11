<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/01/2017 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/Sip.php';

class ScriptSip {

  public static function obterIdSistema($strSigla){
    try {
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->setStrSigla($strSigla);

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
      if ($objSistemaDTO == null) {
        throw new InfraException('Sistema '.$strSigla.' não encontrado.');
      }

      return $objSistemaDTO->getNumIdSistema();

    }catch (Exception $e){
      throw new InfraException('Erro obtendo ID do sistema.', $e);
    }
  }

  public static function obterIdPerfil($numIdSistema, $strNome){
    try{
      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->retNumIdPerfil();
      $objPerfilDTO->setNumIdSistema($numIdSistema);
      $objPerfilDTO->setStrNome($strNome);

      $objPerfilRN = new PerfilRN();
      $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);
      if ($objPerfilDTO == null){
        throw new InfraException('Perfil '.$strNome.' não encontrado.');
      }

      return  $objPerfilDTO->getNumIdPerfil();

    }catch (Exception $e){
      throw new InfraException('Erro obtendo ID do perfil.', $e);
    }
  }

  public static function obterIdMenu($numIdSistema, $strNome){
    try{
      $objMenuDTO = new MenuDTO();
      $objMenuDTO->retNumIdMenu();
      $objMenuDTO->setNumIdSistema($numIdSistema);
      $objMenuDTO->setStrNome($strNome);

      $objMenuRN = new MenuRN();
      $objMenuDTO = $objMenuRN->consultar($objMenuDTO);
      if ($objMenuDTO == null){
        throw new InfraException('Menu '.$strNome.' não encontrado.');
      }

      return $objMenuDTO->getNumIdMenu();
    }catch (Exception $e){
      throw new InfraException('Erro obtendo ID do menu.', $e);
    }
  }

  public static function obterIdItemMenu($numIdSistema, $numIdMenu, $strRotulo, $numIdItemMenuPai = ''){
    try{
      $objItemMenuDTO = new ItemMenuDTO();
      $objItemMenuDTO->retNumIdItemMenu();
      $objItemMenuDTO->setNumIdSistema($numIdSistema);
      $objItemMenuDTO->setNumIdMenu($numIdMenu);
      $objItemMenuDTO->setStrRotulo($strRotulo);
      if($numIdItemMenuPai !== ''){
        $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
      }

      $objItemMenuRN = new ItemMenuRN();
      $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);
      if ($objItemMenuDTO == null){
        throw new InfraException('Item de menu '.$strRotulo.' não encontrado.');
      }

      return $objItemMenuDTO->getNumIdItemMenu();

    }catch (Exception $e){
      throw new InfraException('Erro obtendo ID do item de menu.', $e);
    }
  }

  public static function obterIdRecurso($numIdSistema, $strNome){
    try{
      $objRecursoDTO = new RecursoDTO();
      $objRecursoDTO->retNumIdRecurso();
      $objRecursoDTO->setNumIdSistema($numIdSistema);
      $objRecursoDTO->setStrNome($strNome);

      $objRecursoRN = new RecursoRN();
      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);
      if ($objRecursoDTO == null){
        throw new InfraException('Recurso '.$strNome.' não encontrado.');
      }

      return $objRecursoDTO->getNumIdRecurso();
    }catch (Exception $e){
      throw new InfraException('Erro obtendo ID do recurso.', $e);
    }
  }

  public static function adicionarRecursoPerfil($numIdSistema, $numIdPerfil, $strNome, $strCaminho = null){

    try{
      $objRecursoDTO = new RecursoDTO();
      $objRecursoDTO->retNumIdRecurso();
      $objRecursoDTO->setNumIdSistema($numIdSistema);
      $objRecursoDTO->setStrNome($strNome);

      $objRecursoRN = new RecursoRN();
      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

      if ($objRecursoDTO==null){

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->setNumIdRecurso(null);
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);
        $objRecursoDTO->setStrDescricao(null);

        if ($strCaminho == null){
          $objRecursoDTO->setStrCaminho('controlador.php?acao='.$strNome);
        }else{
          $objRecursoDTO->setStrCaminho($strCaminho);
        }

        $objRecursoDTO->setStrSinAtivo('S');
        $objRecursoDTO = $objRecursoRN->cadastrar($objRecursoDTO);
      }

      if ($numIdPerfil!=null){
        $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
        $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
        $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

        $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

        if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO)==0){
          $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
        }
      }

      return $objRecursoDTO;

    }catch (Exception $e){
      throw new InfraException('Erro adicionando recurso no perfil.', $e);
    }
  }

  public static function cadastrarPerfil($numIdSistemaSei, $strNomePerfil, $strDescricao, $sinAtivo, $sinCoordenado)
  {
    try{
      $objPerfilRN=new PerfilRN();
      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->retNumIdPerfil();
      $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
      $objPerfilDTO->setStrNome($strNomePerfil);
      $ret = $objPerfilRN->consultar($objPerfilDTO);
      if ($ret == null) {
        $objPerfilDTO->setStrDescricao($strDescricao);
        $objPerfilDTO->setStrSinAtivo($sinAtivo);
        $objPerfilDTO->setStrSinCoordenado($sinCoordenado);
        $ret = $objPerfilRN->cadastrar($objPerfilDTO);
      }

      return  $ret;

    }catch (Exception $e){
      throw new InfraException('Erro cadastrando perfil.', $e);
    }
  }

  public static function removerRecursoPerfil($numIdSistema, $strNome, $numIdPerfil){

    try{
      $objRecursoDTO = new RecursoDTO();
      $objRecursoDTO->setBolExclusaoLogica(false);
      $objRecursoDTO->retNumIdRecurso();
      $objRecursoDTO->setNumIdSistema($numIdSistema);
      $objRecursoDTO->setStrNome($strNome);

      $objRecursoRN = new RecursoRN();
      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

      if ($objRecursoDTO!=null){
        $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
        $objRelPerfilRecursoDTO->retTodos();
        $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
        $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);

        $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
        $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

        $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
        $objRelPerfilItemMenuDTO->retTodos();
        $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
        $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);

        $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
        $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
      }

    }catch (Exception $e){
      throw new InfraException('Erro removendo recurso do perfil.', $e);
    }
  }

  public static function desativarRecurso($numIdSistema, $strNome){
    try{
      $objRecursoDTO = new RecursoDTO();
      $objRecursoDTO->retNumIdRecurso();
      $objRecursoDTO->setNumIdSistema($numIdSistema);
      $objRecursoDTO->setStrNome($strNome);

      $objRecursoRN = new RecursoRN();
      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

      if ($objRecursoDTO!=null){
        $objRecursoRN->desativar(array($objRecursoDTO));
      }
    }catch (Exception $e){
      throw new InfraException('Erro desativando recurso.', $e);
    }
  }

  public static function removerRecurso($numIdSistema, $strNome){

    try{
      $objRecursoDTO = new RecursoDTO();
      $objRecursoDTO->setBolExclusaoLogica(false);
      $objRecursoDTO->retNumIdRecurso();
      $objRecursoDTO->setNumIdSistema($numIdSistema);
      $objRecursoDTO->setStrNome($strNome);

      $objRecursoRN = new RecursoRN();
      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

      if ($objRecursoDTO!=null){
        $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
        $objRelPerfilRecursoDTO->retTodos();
        $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

        $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
        $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdMenu();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

        $objItemMenuRN = new ItemMenuRN();
        $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);

        $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

        foreach($arrObjItemMenuDTO as $objItemMenuDTO){
          $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
          $objRelPerfilItemMenuDTO->retTodos();
          $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
          $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

          $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
        }

        $objItemMenuRN->excluir($arrObjItemMenuDTO);

        $objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
        $objRelRegraAuditoriaRecursoRN=new RelRegraAuditoriaRecursoRN();
        $objRelRegraAuditoriaRecursoDTO->retNumIdSistema();
        $objRelRegraAuditoriaRecursoDTO->retNumIdRecurso();
        $objRelRegraAuditoriaRecursoDTO->retNumIdRegraAuditoria();
        $objRelRegraAuditoriaRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

        $arrObjRelRegraAuditoriaRecursoDTO = $objRelRegraAuditoriaRecursoRN->listar($objRelRegraAuditoriaRecursoDTO);
        if (count($arrObjRelRegraAuditoriaRecursoDTO)>0) {
          $objRelRegraAuditoriaRecursoRN->excluir($arrObjRelRegraAuditoriaRecursoDTO);
        }

        $objRecursoRN->excluir(array($objRecursoDTO));
      }
    }catch (Exception $e){
      throw new InfraException('Erro removendo recurso.', $e);
    }
  }


  public static function renomearRecurso($numIdSistema, $strNomeAtual, $strNomeNovo){

    try{
      $objRecursoDTO = new RecursoDTO();
      $objRecursoDTO->setBolExclusaoLogica(false);
      $objRecursoDTO->retNumIdRecurso();
      $objRecursoDTO->retStrCaminho();
      $objRecursoDTO->setNumIdSistema($numIdSistema);
      $objRecursoDTO->setStrNome($strNomeAtual);

      $objRecursoRN = new RecursoRN();
      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

      if ($objRecursoDTO!=null){
        $objRecursoDTO->setStrNome($strNomeNovo);
        $objRecursoDTO->setStrCaminho(str_replace($strNomeAtual,$strNomeNovo,$objRecursoDTO->getStrCaminho()));
        $objRecursoRN->alterar($objRecursoDTO);
      }
    }catch (Exception $e){
      throw new InfraException('Erro renomeando recurso.', $e);
    }
  }

  public static function adicionarItemMenu($numIdSistema, $numIdPerfil, $numIdMenu, $numIdItemMenuPai, $numIdRecurso, $strRotulo, $numSequencia, $strIcone = null ){

    try{
      $objItemMenuDTO = new ItemMenuDTO();
      $objItemMenuDTO->retNumIdItemMenu();
      $objItemMenuDTO->setNumIdMenu($numIdMenu);

      if ($numIdItemMenuPai==null){
        $objItemMenuDTO->setNumIdMenuPai(null);
        $objItemMenuDTO->setNumIdItemMenuPai(null);
      }else{
        $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
        $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
      }

      $objItemMenuDTO->setNumIdSistema($numIdSistema);
      $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
      $objItemMenuDTO->setStrRotulo($strRotulo);

      $objItemMenuRN = new ItemMenuRN();
      $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

      if ($objItemMenuDTO==null){

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->setNumIdItemMenu(null);
        $objItemMenuDTO->setNumIdMenu($numIdMenu);

        if ($numIdItemMenuPai==null){
          $objItemMenuDTO->setNumIdMenuPai(null);
          $objItemMenuDTO->setNumIdItemMenuPai(null);
        }else{
          $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
          $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
        }

        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
        $objItemMenuDTO->setStrRotulo($strRotulo);
        $objItemMenuDTO->setStrDescricao(null);
        $objItemMenuDTO->setStrIcone($strIcone);
        $objItemMenuDTO->setNumSequencia($numSequencia);
        $objItemMenuDTO->setStrSinNovaJanela('N');
        $objItemMenuDTO->setStrSinAtivo('S');
        $objItemMenuDTO = $objItemMenuRN->cadastrar($objItemMenuDTO);
      }


      if ($numIdPerfil!=null && $numIdRecurso!=null){

        $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
        $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
        $objRelPerfilRecursoDTO->setNumIdRecurso($numIdRecurso);

        $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

        if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO)==0){
          $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
        }

        $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
        $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);
        $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilItemMenuDTO->setNumIdRecurso($numIdRecurso);
        $objRelPerfilItemMenuDTO->setNumIdMenu($numIdMenu);
        $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

        $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

        if ($objRelPerfilItemMenuRN->contar($objRelPerfilItemMenuDTO)==0){
          $objRelPerfilItemMenuRN->cadastrar($objRelPerfilItemMenuDTO);
        }
      }

      return $objItemMenuDTO;

    }catch (Exception $e){
      throw new InfraException('Erro adicionando item de menu.', $e);
    }
  }

  public static function removerItemMenu($numIdSistema, $numIdMenu, $numIdItemMenu){

    try{
      $objItemMenuDTO = new ItemMenuDTO();
      $objItemMenuDTO->retNumIdMenu();
      $objItemMenuDTO->retNumIdItemMenu();
      $objItemMenuDTO->setNumIdSistema($numIdSistema);
      $objItemMenuDTO->setNumIdMenu($numIdMenu);
      $objItemMenuDTO->setNumIdItemMenu($numIdItemMenu);

      $objItemMenuRN = new ItemMenuRN();
      $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

      if ($objItemMenuDTO!=null) {

        $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
        $objRelPerfilItemMenuDTO->retTodos();
        $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilItemMenuDTO->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
        $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

        $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
        $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

        $objItemMenuRN->excluir(array($objItemMenuDTO));
      }

    }catch (Exception $e){
      throw new InfraException('Erro removendo item de menu.', $e);
    }
  }

  public static function removerPerfil($numIdSistema, $strNome){

    try{
      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->retNumIdPerfil();
      $objPerfilDTO->setNumIdSistema($numIdSistema);
      $objPerfilDTO->setStrNome($strNome);

      $objPerfilRN = new PerfilRN();
      $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

      if ($objPerfilDTO!=null){

        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->retNumIdSistema();
        $objPermissaoDTO->retNumIdUsuario();
        $objPermissaoDTO->retNumIdPerfil();
        $objPermissaoDTO->retNumIdUnidade();
        $objPermissaoDTO->setNumIdSistema($numIdSistema);
        $objPermissaoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

        $objPermissaoRN = new PermissaoRN();
        $objPermissaoRN->excluir($objPermissaoRN->listar($objPermissaoDTO));

        $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
        $objRelPerfilItemMenuDTO->retTodos();
        $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

        $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
        $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

        $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
        $objRelPerfilRecursoDTO->retTodos();
        $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
        $objRelPerfilRecursoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

        $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
        $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

        $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
        $objCoordenadorPerfilDTO->retTodos();
        $objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);
        $objCoordenadorPerfilDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

        $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
        $objCoordenadorPerfilRN->excluir($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO));

        $objPerfilRN->excluir(array($objPerfilDTO));
      }

    }catch (Exception $e){
      throw new InfraException('Erro removendo perfil.', $e);
    }
  }

  public static function adicionarAuditoria($numIdSistema, $strNome, $arrRecursos){

    try{
      $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
      $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
      $objRegraAuditoriaDTO->setNumIdSistema($numIdSistema);
      $objRegraAuditoriaDTO->setStrDescricao($strNome);

      $objRegraAuditoriaRN = new RegraAuditoriaRN();
      $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);

      if($objRegraAuditoriaDTO==null){
        $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
        $objRegraAuditoriaDTO->setNumIdRegraAuditoria(null);
        $objRegraAuditoriaDTO->setNumIdSistema($numIdSistema);
        $objRegraAuditoriaDTO->setStrDescricao($strNome);
        $objRegraAuditoriaDTO->setStrSinAtivo('S');
        $objRegraAuditoriaDTO->setArrObjRelRegraAuditoriaRecursoDTO(array());
        $objRegraAuditoriaDTO = $objRegraAuditoriaRN->cadastrar($objRegraAuditoriaDTO);
      }

      $objRecursoDTO=new RecursoDTO();
      $objRecursoRN=new RecursoRN();
      $objRecursoDTO->setStrNome($arrRecursos,InfraDTO::$OPER_IN);
      $objRecursoDTO->setNumIdSistema($numIdSistema);
      $objRecursoDTO->retNumIdRecurso();
      $arrObjRecursoDTO=$objRecursoRN->listar($objRecursoDTO);


      $objRelRegraAuditoriaRecursoDTO=new RelRegraAuditoriaRecursoDTO();
      $objRelRegraAuditoriaRecursoRN=new RelRegraAuditoriaRecursoRN();
      $objRelRegraAuditoriaRecursoDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());
      $objRelRegraAuditoriaRecursoDTO->setNumIdSistema($numIdSistema);


      foreach($arrObjRecursoDTO as $objRecursoDTO){
        $objRelRegraAuditoriaRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
        if ($objRelRegraAuditoriaRecursoRN->contar($objRelRegraAuditoriaRecursoDTO)==0) {
          $objRelRegraAuditoriaRecursoRN->cadastrar($objRelRegraAuditoriaRecursoDTO);
        }
      }

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      if ($numIdSistema == $objInfraParametro->getValor('ID_SISTEMA_SIP')) {
        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objSistemaRN = new SistemaRN();
        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);
      }

    }catch (Exception $e){
      throw new InfraException('Erro adicionando recursos na auditoria.', $e);
    }
  }
}
?>