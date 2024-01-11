<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 08/04/2013 - criado por MGA
 *
 */

require_once dirname(__FILE__) . '/Sip.php';

class AuditoriaSip extends InfraAuditoria {

  private static $instance = null;
  private static $arrObjSistemaDTO = array();
  private static $arrObjPerfilDTO = array();
  private static $arrObjRecursoDTO = array();
  private static $arrObjItemMenuDTO = array();
  private static $arrObjUsuarioDTO = array();
  private static $arrObjUnidadeDTO = array();

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new AuditoriaSip(BancoSip::getInstance(), SessaoSip::getInstance(), CacheSip::getInstance());
    }
    return self::$instance;
  }

  public function getTempoCache() {
    return CacheSip::getInstance()->getNumTempo();
  }

  public function getObjInfraIBancoAuditoria() {
    if (ConfiguracaoSip::getInstance()->isSetValor('BancoAuditoriaSip')) {
      return BancoAuditoriaSip::getInstance();
    }

    return null;
  }

  public function getArrExcecoesPost() {
    return array('pwdSenha', 'pwdSenhaPesquisa', 'pwdSenhaTeste', 'pwdBancoSenha');
  }

  public function processarComplemento(InfraAuditoriaDTO $objInfraAuditoriaDTO) {
    try {
      $strOperacao = $objInfraAuditoriaDTO->getStrOperacao();
      $strRecurso = $objInfraAuditoriaDTO->getStrRecurso();

      $strComplemento = '';

      $ini = 0;

      $tag = "\nId";

      while (true) {
        $ini = strpos($strOperacao, $tag, $ini);

        if ($ini !== false) {
          $igual = strpos($strOperacao, " = ", $ini);

          if ($igual !== false) {
            $i = $igual + 3;
            $t = strlen($strOperacao);

            $valor = '';
            while ($i < $t) {
              if (is_numeric($strOperacao{$i})) {
                $valor .= $strOperacao{$i};
              } else {
                break;
              }
              $i++;
            }

            if (is_numeric($valor)) {
              $fim = $i;

              $id = substr($strOperacao, $ini + 1, $igual - ($ini + 1));
              //InfraDebug::getInstance()->gravar($id.'='.$valor);

              if ($valor != null && $valor != '[null]') {
                switch ($id) {
                  case 'IdSistema':

                    if (isset(self::$arrObjSistemaDTO[$valor])) {
                      $objSistemaDTO = self::$arrObjSistemaDTO[$valor];
                    } else {
                      $objSistemaDTO = new SistemaDTO();
                      $objSistemaDTO->setBolExclusaoLogica(false);
                      $objSistemaDTO->retStrSigla();
                      $objSistemaDTO->retStrDescricao();
                      $objSistemaDTO->setNumIdSistema($valor);

                      $objSistemaRN = new SistemaRN();
                      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
                    }

                    if ($objSistemaDTO != null) {
                      if (!isset(self::$arrObjSistemaDTO[$valor])) {
                        self::$arrObjSistemaDTO[$valor] = $objSistemaDTO;
                      }

                      $strComplemento .= 'Sistema = ' . $objSistemaDTO->getStrSigla() . ' / ' . $objSistemaDTO->getStrDescricao() . "\n";
                    } else {
                      $strComplemento .= 'Sistema = (excluído)' . "\n";
                    }
                    break;

                  case 'IdPerfil':

                    if (isset(self::$arrObjPerfilDTO[$valor])) {
                      $objPerfilDTO = self::$arrObjPerfilDTO[$valor];
                    } else {
                      $objPerfilDTO = new PerfilDTO();
                      $objPerfilDTO->setBolExclusaoLogica(false);
                      $objPerfilDTO->retStrNome();
                      $objPerfilDTO->setNumIdPerfil($valor);

                      $objPerfilRN = new PerfilRN();
                      $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);
                    }

                    if ($objPerfilDTO != null) {
                      if (!isset(self::$arrObjPerfilDTO[$valor])) {
                        self::$arrObjPerfilDTO[$valor] = $objPerfilDTO;
                      }

                      $strComplemento .= 'Perfil = ' . $objPerfilDTO->getStrNome() . "\n";
                    } else {
                      $strComplemento .= 'Perfil = (excluído)' . "\n";
                    }
                    break;

                  case 'IdRecurso':

                    if (isset(self::$arrObjRecursoDTO[$valor])) {
                      $objRecursoDTO = self::$arrObjRecursoDTO[$valor];
                    } else {
                      $objRecursoDTO = new RecursoDTO();
                      $objRecursoDTO->setBolExclusaoLogica(false);
                      $objRecursoDTO->retStrNome();
                      $objRecursoDTO->setNumIdRecurso($valor);

                      $objRecursoRN = new RecursoRN();
                      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);
                    }

                    if ($objRecursoDTO != null) {
                      if (!isset(self::$arrObjRecursoDTO[$valor])) {
                        self::$arrObjRecursoDTO[$valor] = $objRecursoDTO;
                      }

                      $strComplemento .= 'Recurso = ' . $objRecursoDTO->getStrNome() . "\n";
                    } else {
                      $strComplemento .= 'Recurso = (excluído)' . "\n";
                    }
                    break;

                  case 'IdItemMenu':

                    if (isset(self::$arrObjItemMenuDTO[$valor])) {
                      $objItemMenuDTO = self::$arrObjItemMenuDTO[$valor];
                    } else {
                      $objItemMenuDTO = new ItemMenuDTO();
                      $objItemMenuDTO->setBolExclusaoLogica(false);
                      $objItemMenuDTO->retStrRotulo();
                      $objItemMenuDTO->retStrNomeRecurso();
                      $objItemMenuDTO->setNumIdItemMenu($valor);

                      $objItemMenuRN = new ItemMenuRN();
                      $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);
                    }

                    if ($objItemMenuDTO != null) {
                      if (!isset(self::$arrObjItemMenuDTO[$valor])) {
                        self::$arrObjItemMenuDTO[$valor] = $objItemMenuDTO;
                      }

                      $strComplemento .= 'Item de Menu = ' . $objItemMenuDTO->getStrRotulo() . ' (' . $objItemMenuDTO->getStrNomeRecurso() . ')' . "\n";
                    } else {
                      $strComplemento .= 'Item de Menu = (excluído)' . "\n";
                    }
                    break;


                  case 'IdUsuario':
                  case 'IdUsuarioEmulador':

                    if (isset(self::$arrObjUsuarioDTO[$valor])) {
                      $objUsuarioDTO = self::$arrObjUsuarioDTO[$valor];
                    } else {
                      $objUsuarioDTO = new UsuarioDTO();
                      $objUsuarioDTO->setBolExclusaoLogica(false);
                      $objUsuarioDTO->retStrSigla();
                      $objUsuarioDTO->retStrNome();
                      $objUsuarioDTO->retStrSiglaOrgao();
                      $objUsuarioDTO->setNumIdUsuario($valor);

                      $objUsuarioRN = new UsuarioRN();
                      $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);
                    }

                    if ($objUsuarioDTO != null) {
                      if (!isset(self::$arrObjUsuarioDTO[$valor])) {
                        self::$arrObjUsuarioDTO[$valor] = $objUsuarioDTO;
                      }

                      $strComplemento .= ($id == 'IdUsuario' ? 'Usuário' : 'Usuário Emulador') . ' = ' . $objUsuarioDTO->getStrSigla() . ' / ' . $objUsuarioDTO->getStrSiglaOrgao() . ' - ' . $objUsuarioDTO->getStrNome() . "\n";
                    } else {
                      $strComplemento .= ($id == 'IdUsuario' ? 'Usuário' : 'Usuário Emulador') . ' = (excluído)' . "\n";
                    }
                    break;

                  case 'IdUnidade':

                    if (isset(self::$arrObjUnidadeDTO[$valor])) {
                      $objUnidadeDTO = self::$arrObjUnidadeDTO[$valor];
                    } else {
                      $objUnidadeDTO = new UnidadeDTO();
                      $objUnidadeDTO->setBolExclusaoLogica(false);
                      $objUnidadeDTO->retStrSigla();
                      $objUnidadeDTO->retStrDescricao();
                      $objUnidadeDTO->retStrSiglaOrgao();
                      $objUnidadeDTO->setNumIdUnidade($valor);

                      $objUnidadeRN = new UnidadeRN();
                      $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);
                    }

                    if ($objUnidadeDTO != null) {
                      if (!isset(self::$arrObjUnidadeDTO[$valor])) {
                        self::$arrObjUnidadeDTO[$valor] = $objUnidadeDTO;
                      }

                      $strComplemento .= 'Unidade = ' . $objUnidadeDTO->getStrSigla() . ' / ' . $objUnidadeDTO->getStrSiglaOrgao() . ' - ' . $objUnidadeDTO->getStrDescricao() . "\n";
                    } else {
                      $strComplemento .= 'Unidade = (excluído)' . "\n";
                    }
                    break;
                }
              }


              $ini = $fim;
            } else {
              $ini = $igual;
            }
          } else {
            break;
          }
        } else {
          break;
        }
      }

      if ($strComplemento != '') {
        if (substr_count($strComplemento, "\n") > 1) {
          $strComplemento = "\n" . $strComplemento;
        } else {
          $strComplemento = str_replace("\n", '', $strComplemento);
        }
      }

      return $strComplemento;
    } catch (Exception $e) {
      throw new InfraException('Erro processando Complemento de Auditoria.', $e);
    }
  }

}

?>