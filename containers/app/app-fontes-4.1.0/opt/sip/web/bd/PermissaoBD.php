<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class PermissaoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco) {
    parent::__construct($objInfraIBanco);
  }

  public function carregar(LoginDTO $objLoginDTO) {
    try {
      $sql = 'SELECT DISTINCT unidade.id_unidade,' . 'unidade.sigla as sigla_unidade, ' . 'unidade.descricao as descricao_unidade, ' . 'unidade.id_orgao as id_orgao_unidade, ' . 'unidade.id_origem as id_origem_unidade, ' . 'unidade.sin_global as sin_global_unidade, ' . 'permissao.sin_subunidades, ' . 'recurso.nome as nome_recurso, ' . 'rel_perfil_item_menu.id_item_menu ' . 'FROM permissao ' . 'INNER JOIN  (perfil INNER JOIN (rel_perfil_recurso INNER JOIN recurso ' . 'ON rel_perfil_recurso.id_sistema=recurso.id_sistema ' . 'AND rel_perfil_recurso.id_recurso=recurso.id_recurso ' . 'AND recurso.sin_ativo=\'S\' ' . 'LEFT JOIN (rel_perfil_item_menu INNER JOIN item_menu ' . 'ON item_menu.id_menu=rel_perfil_item_menu.id_menu ' . 'AND item_menu.id_item_menu=rel_perfil_item_menu.id_item_menu ' . 'AND item_menu.sin_ativo=\'S\') ' . 'ON rel_perfil_recurso.id_perfil=rel_perfil_item_menu.id_perfil ' . 'AND rel_perfil_recurso.id_sistema=rel_perfil_item_menu.id_sistema ' . 'AND rel_perfil_recurso.id_recurso=rel_perfil_item_menu.id_recurso) ' . 'ON rel_perfil_recurso.id_sistema=perfil.id_sistema ' . 'AND rel_perfil_recurso.id_perfil=perfil.id_perfil) ' . 'ON perfil.id_sistema=permissao.id_sistema ' . 'AND perfil.id_perfil=permissao.id_perfil ' . 'AND perfil.sin_ativo=\'S\' ' . 'INNER JOIN (unidade INNER JOIN orgao ON unidade.id_orgao=orgao.id_orgao AND orgao.sin_ativo=\'S\') ON permissao.id_unidade=unidade.id_unidade AND unidade.sin_ativo=\'S\' ' . 'INNER JOIN usuario ON permissao.id_usuario=usuario.id_usuario AND usuario.sin_ativo=\'S\' ' . 'INNER JOIN sistema ON perfil.id_sistema=sistema.id_sistema AND sistema.sin_ativo=\'S\' ' . 'WHERE (permissao.id_usuario = ' . $this->getObjInfraIBanco()->formatarGravacaoNum($objLoginDTO->getNumIdUsuario()) . ') ' . 'AND (permissao.id_sistema=' . $this->getObjInfraIBanco()->formatarGravacaoNum($objLoginDTO->getNumIdSistema()) . ') ' . 'AND (permissao.dta_inicio) <= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' AND (permissao.dta_fim >= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' OR permissao.dta_fim IS NULL) ' . 'ORDER BY unidade.id_unidade,  permissao.sin_subunidades';

      //Para logar o SQL:
      //LogSip::getInstance()->gravar($sql);
      //$objLoginDTO->getObjInfraSessaoDTO()->setArrPermissoes(array());
      //return;

      $rs = $this->getObjInfraIBanco()->consultarSql($sql);

      $arrOrgaos = array();
      $arrPermissoes = array();
      $arrRecursos = array();
      $arrItensMenu = array();
      $arrReplicarRecursos = array();
      $arrReplicarItensMenu = array();
      $arrUnidadesPermissaoGlobal = array();

      $arrHierarquia = null;

      foreach ($rs as $item) {
        $numIdUnidade = $item['id_unidade'];
        $strNomeRecurso = $item['nome_recurso'];
        $numIdItemMenu = $item['id_item_menu'];

        if ($item['sin_global_unidade'] == 'S') {
          $arrUnidadesPermissaoGlobal[$numIdUnidade] = true;
        }

        if (!isset($arrRecursos[$numIdUnidade])) {
          $arrRecursos[$numIdUnidade] = array();
          $arrItensMenu[$numIdUnidade] = array();
        }

        if (!isset($arrRecursos[$numIdUnidade][$strNomeRecurso])) {
          $arrRecursos[$numIdUnidade][$strNomeRecurso] = 0;
        }

        if ($numIdItemMenu != null && !isset($arrItensMenu[$numIdUnidade][$numIdItemMenu])) {
          $arrItensMenu[$numIdUnidade][$numIdItemMenu] = 0;
        }

        if ($item['sin_subunidades'] == 'S' && $item['sin_global_unidade'] == 'N') {
          if (!isset($arrReplicarRecursos[$numIdUnidade])) {
            $arrReplicarRecursos[$numIdUnidade] = array();
            $arrReplicarItensMenu[$numIdUnidade] = array();
          }

          if (!isset($arrReplicarRecursos[$numIdUnidade][$strNomeRecurso])) {
            $arrReplicarRecursos[$numIdUnidade][$strNomeRecurso] = 0;
          }

          if ($numIdItemMenu != null && !isset($arrReplicarItensMenu[$numIdUnidade][$numIdItemMenu])) {
            $arrReplicarItensMenu[$numIdUnidade][$numIdItemMenu] = 0;
          }
        }
      }


      foreach ($rs as $recurso) {
        $numIdUnidade = $recurso['id_unidade'];

        if (!isset($arrPermissoes[$numIdUnidade])) {
          if (!in_array($recurso['id_orgao_unidade'], $arrOrgaos)) {
            $arrOrgaos[] = $recurso['id_orgao_unidade'];
          }

          $arrPermissoes[$numIdUnidade] = array();
          $arrPermissoes[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES] = array(
            array(
              $numIdUnidade, $recurso['sigla_unidade'], $recurso['descricao_unidade'], $recurso['id_orgao_unidade'], $recurso['id_origem_unidade']
            )
          );
          $arrPermissoes[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS] = array_keys($arrRecursos[$numIdUnidade]);
          $arrPermissoes[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_MENU] = array_keys($arrItensMenu[$numIdUnidade]);
          //InfraDebug::getInstance()->gravar($numIdUnidade.': '.print_r($arrItensMenu[$numIdUnidade],true));
        }
      }

      //Replica recursos
      if (count($arrReplicarRecursos) > 0) {
        //Busca Hierarquia das unidades do sistema
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->setNumIdSistema($objLoginDTO->getNumIdSistema());
        $objSistemaDTO->setNumIdUnidade(array_keys($arrReplicarRecursos));
        $objSistemaRN = new SistemaRN();
        $objLoginDTO->setArrHierarquia($objSistemaRN->listarHierarquia($objSistemaDTO));
        $arrHierarquia = $objLoginDTO->getArrHierarquia();

        $arrPais = array_keys($arrReplicarRecursos);
        foreach ($arrPais as $numIdUnidadePai) {
          //Obtem subunidades desta unidade
          $arrFilhas = null;
          foreach ($arrHierarquia as $unidadeHierarquia) {
            if ($unidadeHierarquia->getNumIdUnidade() == $numIdUnidadePai) {
              $arrFilhas = $unidadeHierarquia->getArrUnidadesInferiores();
              $numFilhas = count($arrFilhas);
              break;
            }
          }

          if ($arrFilhas != null) {
            //Verifica se a subunidade já esta no array de permissões
            for ($j = 0; $j < $numFilhas; $j++) {
              $numIdUnidadeFilha = $arrFilhas[$j]->getNumIdUnidade();

              if (!isset($arrPermissoes[$numIdUnidadeFilha])) {
                if (!in_array($arrFilhas[$j]->getNumIdOrgaoUnidade(), $arrOrgaos)) {
                  $arrOrgaos[] = $arrFilhas[$j]->getNumIdOrgaoUnidade();
                }

                $arrPermissoes[$numIdUnidadeFilha] = array();
                $arrPermissoes[$numIdUnidadeFilha][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES] = array(
                  array(
                    $numIdUnidadeFilha, $arrFilhas[$j]->getStrSiglaUnidade(), $arrFilhas[$j]->getStrDescricaoUnidade(), $arrFilhas[$j]->getNumIdOrgaoUnidade(), $arrFilhas[$j]->getStrIdOrigemUnidade()
                  )
                );
                $arrPermissoes[$numIdUnidadeFilha][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS] = array_keys($arrReplicarRecursos[$numIdUnidadePai]);
                $arrPermissoes[$numIdUnidadeFilha][InfraSip::$WS_LOGIN_PERMISSAO_MENU] = array_keys($arrReplicarItensMenu[$numIdUnidadePai]);
              } else {
                //Complementa recursos
                $arrPermissoes[$numIdUnidadeFilha][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS] = array_unique(array_merge(array_keys($arrReplicarRecursos[$numIdUnidadePai]),
                    $arrPermissoes[$numIdUnidadeFilha][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS]));
                $arrPermissoes[$numIdUnidadeFilha][InfraSip::$WS_LOGIN_PERMISSAO_MENU] = array_unique(array_merge(array_keys($arrReplicarItensMenu[$numIdUnidadePai]), $arrPermissoes[$numIdUnidadeFilha][InfraSip::$WS_LOGIN_PERMISSAO_MENU]));
              }
            }
          }
        }
      }

      if (count($arrUnidadesPermissaoGlobal)) {
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retNumIdOrgao();
        $objUnidadeDTO->retStrSinGlobal();
        $objUnidadeDTO->setNumIdUnidade(array_keys($arrUnidadesPermissaoGlobal), InfraDTO::$OPER_IN);

        $objUnidadeRN = new UnidadeRN();
        $arrObjUnidadeDTO = InfraArray::indexarArrInfraDTO($objUnidadeRN->listar($objUnidadeDTO), 'IdUnidade');

        //Busca Hierarquia do sistema
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->setNumIdSistema($objLoginDTO->getNumIdSistema());
        $objSistemaRN = new SistemaRN();
        $objLoginDTO->setArrHierarquia($objSistemaRN->listarHierarquia($objSistemaDTO));
        $arrHierarquia = $objLoginDTO->getArrHierarquia();

        foreach (array_keys($arrUnidadesPermissaoGlobal) as $numIdUnidadeGlobal) {
          $arrPermissoesGlobais = array();
          foreach ($arrHierarquia as $unidadeHierarquia) {
            //Verifica se a unidade tem o mesmo órgão da unidade global
            if ($unidadeHierarquia->getNumIdOrgaoUnidade() == $arrObjUnidadeDTO[$numIdUnidadeGlobal]->getNumIdOrgao()) {
              $numIdUnidadeHierarquia = $unidadeHierarquia->getNumIdUnidade();

              //se a unidade não tem permissões específicas então somente armazena para apontar para as permissões da global
              if (!isset($arrPermissoes[$numIdUnidadeHierarquia])) {
                if (!in_array($unidadeHierarquia->getNumIdOrgaoUnidade(), $arrOrgaos)) {
                  $arrOrgaos[] = $unidadeHierarquia->getNumIdOrgaoUnidade();
                }

                $arrPermissoesGlobais[] = array(
                  $numIdUnidadeHierarquia, $unidadeHierarquia->getStrSiglaUnidade(), $unidadeHierarquia->getStrDescricaoUnidade(), $unidadeHierarquia->getNumIdOrgaoUnidade(), $unidadeHierarquia->getStrIdOrigemUnidade()
                );
              } else {
                //Complementa recursos
                $arrPermissoes[$numIdUnidadeHierarquia][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS] = array_unique(array_merge($arrPermissoes[$numIdUnidadeGlobal][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS],
                    $arrPermissoes[$numIdUnidadeHierarquia][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS]));
                $arrPermissoes[$numIdUnidadeHierarquia][InfraSip::$WS_LOGIN_PERMISSAO_MENU] = array_unique(array_merge($arrPermissoes[$numIdUnidadeGlobal][InfraSip::$WS_LOGIN_PERMISSAO_MENU],
                    $arrPermissoes[$numIdUnidadeHierarquia][InfraSip::$WS_LOGIN_PERMISSAO_MENU]));
              }
            }
          }
          $arrPermissoes[$numIdUnidadeGlobal][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES] = $arrPermissoesGlobais;
        }
      }

      if (count($arrPermissoes) > 1) {
        $arrIdUnidades = array_keys($arrPermissoes);

        $arrComparacao = array();

        foreach ($arrIdUnidades as $numIdUnidade) {
          sort($arrPermissoes[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS]);
          sort($arrPermissoes[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_MENU]);

          $arrComparacao[$numIdUnidade] = md5(serialize($arrPermissoes[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS]) . serialize($arrPermissoes[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_MENU]));
        }

        $numUnidades = count($arrIdUnidades);

        for ($i = 0; $i < $numUnidades; $i++) {
          if (isset($arrPermissoes[$arrIdUnidades[$i]])) {
            $arrUnidadesA = $arrPermissoes[$arrIdUnidades[$i]][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES];

            for ($j = $i + 1; $j < $numUnidades; $j++) {
              if (isset($arrPermissoes[$arrIdUnidades[$j]])) {
                if ($arrComparacao[$arrIdUnidades[$i]] == $arrComparacao[$arrIdUnidades[$j]]) {
                  $arrUnidadesB = $arrPermissoes[$arrIdUnidades[$j]][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES];

                  foreach ($arrUnidadesB as $arrDadosUnidadeB) {
                    $arrUnidadesA[] = $arrDadosUnidadeB;
                  }

                  $arrPermissoes[$arrIdUnidades[$i]][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES] = $arrUnidadesA;

                  unset($arrPermissoes[$arrIdUnidades[$j]]);
                }
              }
            }
          }
        }
      }

      if (count($arrOrgaos)) {
        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->retNumIdOrgao();
        $objOrgaoDTO->retStrSigla();
        $objOrgaoDTO->retStrDescricao();
        $objOrgaoDTO->setNumIdOrgao($arrOrgaos, InfraDTO::$OPER_IN);

        $objOrgaoRN = new OrgaoRN();
        $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);

        $arrOrgaos = array();
        foreach ($arrObjOrgaoDTO as $objOrgaoDTO) {
          $arrOrgaos[] = array(
            InfraSip::$WS_LOGIN_ORGAO_ID => $objOrgaoDTO->getNumIdOrgao(), InfraSip::$WS_LOGIN_ORGAO_SIGLA => $objOrgaoDTO->getStrSigla(), InfraSip::$WS_LOGIN_ORGAO_DESCRICAO => $objOrgaoDTO->getStrDescricao()
          );
        }
      }
      $objLoginDTO->getObjInfraSessaoDTO()->setArrOrgaos($arrOrgaos);
      $objLoginDTO->getObjInfraSessaoDTO()->setArrPermissoes($arrPermissoes);
    } catch (Exception $e) {
      throw new InfraException('Erro carregando Permissões do Usuário.', $e);
    }
  }

  public function carregarUsuarios(PermissaoDTO $objPermissaoDTO) {
    try {
      $sql = ' SELECT DISTINCT usuario.id_usuario, usuario.id_origem, usuario.id_orgao, usuario.sigla, usuario.nome_registro_civil, usuario.nome_social, usuario.cpf, usuario.email, usuario.sin_ativo, unidade.id_unidade, permissao.sin_subunidades' . ' FROM permissao, perfil, sistema, rel_perfil_recurso, recurso, unidade, usuario' . ' WHERE (permissao.id_sistema=' . $this->getObjInfraIBanco()->formatarGravacaoNum($objPermissaoDTO->getNumIdSistema()) . ')' . ' and (permissao.dta_inicio) <= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' and (permissao.dta_fim >= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' or permissao.dta_fim is null)' . ' and (permissao.id_usuario=usuario.id_usuario)' . ' and (permissao.id_perfil=perfil.id_perfil)' . ' and (permissao.id_sistema=perfil.id_sistema)' . ' and (permissao.id_unidade=unidade.id_unidade)' . ' and unidade.sin_ativo=\'S\'' . ' and unidade.sin_global=\'N\'' . ' and (perfil.id_perfil=rel_perfil_recurso.id_perfil)' . ' and (perfil.id_sistema=rel_perfil_recurso.id_sistema)' . ' and perfil.sin_ativo=\'S\'' . ' and (perfil.id_sistema=sistema.id_sistema)' . ' and sistema.sin_ativo=\'S\'' . ' and (rel_perfil_recurso.id_sistema=recurso.id_sistema)' . ' and (rel_perfil_recurso.id_recurso=recurso.id_recurso)';

      if ($objPermissaoDTO->isSetStrNomeRecurso()) {
        $sql .= ' and recurso.nome=' . $this->getObjInfraIBanco()->formatarGravacaoStr($objPermissaoDTO->getStrNomeRecurso());
      }

      if ($objPermissaoDTO->isSetStrNomePerfil()) {
        $sql .= ' and perfil.nome=' . $this->getObjInfraIBanco()->formatarGravacaoStr($objPermissaoDTO->getStrNomePerfil());
      }

      if ($objPermissaoDTO->isSetNumIdUnidade()) {
        $sql .= ' and (permissao.id_unidade=' . $this->getObjInfraIBanco()->formatarGravacaoNum($objPermissaoDTO->getNumIdUnidade()) . ' or permissao.sin_subunidades=\'S\')';
      }

      $sql .= ' and recurso.sin_ativo=\'S\'' . ' order by id_usuario, id_unidade, sin_subunidades';


      $rs = $this->getObjInfraIBanco()->consultarSql($sql);

      $ret = array();

      if (count($rs) > 0) {
        $arrSubunidades = array();

        foreach ($rs as $item) {
          if ($item['sin_subunidades'] == 'S') {
            $arrSubunidades[$item['id_unidade']] = true;
          }
        }

        $arrHierarquia = array();

        if (count($arrSubunidades)) {
          $objSistemaDTO = new SistemaDTO();
          $objSistemaDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
          $objSistemaDTO->setNumIdUnidade(array_keys($arrSubunidades));

          $objSistemaRN = new SistemaRN();
          $arrHierarquia = InfraArray::indexarArrInfraDTO($objSistemaRN->listarHierarquia($objSistemaDTO), 'IdUnidade');
        }


        foreach ($rs as $item) {
          $numIdUsuario = $item['id_usuario'];
          $numIdUnidade = $item['id_unidade'];

          if (!isset($ret[$numIdUsuario])) {
            $ret[$numIdUsuario] = array();
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_ID] = $item['id_usuario'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_ID_ORIGEM] = $item['id_origem'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_ORGAO_ID] = $item['id_orgao'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_SIGLA] = $item['sigla'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_NOME] = $item['nome_registro_civil'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_NOME_SOCIAL] = $item['nome_social'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_CPF] = $item['cpf'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_EMAIL] = $item['email'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_SIN_ATIVO] = $item['sin_ativo'];
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_UNIDADES] = array();
          }

          if (!in_array($numIdUnidade, $ret[$numIdUsuario][InfraSip::$WS_USUARIO_UNIDADES])) {
            $ret[$numIdUsuario][InfraSip::$WS_USUARIO_UNIDADES][] = $numIdUnidade;
          }

          if ($item['sin_subunidades'] == 'S') {
            $arrFilhas = $arrHierarquia[$numIdUnidade]->getArrUnidadesInferiores();
            foreach ($arrFilhas as $filha) {
              if (!in_array($filha->getNumIdUnidade(), $ret[$numIdUsuario][InfraSip::$WS_USUARIO_UNIDADES])) {
                $ret[$numIdUsuario][InfraSip::$WS_USUARIO_UNIDADES][] = $filha->getNumIdUnidade();
              }
            }
          }
        }
      }

      //Se filtrando pela unidade
      if ($objPermissaoDTO->isSetNumIdUnidade()) {
        $tmp = array();
        foreach ($ret as $item) {
          if (in_array($objPermissaoDTO->getNumIdUnidade(), $item[InfraSip::$WS_USUARIO_UNIDADES])) {
            $tmp[$item[InfraSip::$WS_USUARIO_ID]] = $item;
          }
        }
        $ret = $tmp;
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro carregando Usuários.', $e);
    }
  }

  public function listarUnidades(PermissaoDTO $objPermissaoDTO) {
    try {
      $sql = ' SELECT DISTINCT permissao.id_unidade, permissao.sin_subunidades' . ' FROM permissao, perfil, sistema, rel_perfil_recurso, recurso, unidade, usuario' . ' WHERE (permissao.id_sistema=' . $this->getObjInfraIBanco()->formatarGravacaoNum($objPermissaoDTO->getNumIdSistema()) . ')' . ' and (permissao.dta_inicio) <= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' and (permissao.dta_fim >= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' or permissao.dta_fim is null)' . ' and (permissao.id_usuario=usuario.id_usuario)' . ' and (permissao.id_perfil=perfil.id_perfil)' . ' and (permissao.id_sistema=perfil.id_sistema)' . ' and (permissao.id_unidade=unidade.id_unidade)' . ' and unidade.sin_ativo=\'S\'' . ' and unidade.sin_global=\'N\'' . ' and (perfil.id_perfil=rel_perfil_recurso.id_perfil)' . ' and (perfil.id_sistema=rel_perfil_recurso.id_sistema)' . ' and perfil.sin_ativo=\'S\'' . ' and (perfil.id_sistema=sistema.id_sistema)' . ' and sistema.sin_ativo=\'S\'' . ' and (rel_perfil_recurso.id_sistema=recurso.id_sistema)' . ' and (rel_perfil_recurso.id_recurso=recurso.id_recurso)' . ' and recurso.sin_ativo=\'S\'';

      if ($objPermissaoDTO->isSetNumIdUsuario()) {
        $sql .= ' and permissao.id_usuario=' . $this->getObjInfraIBanco()->formatarGravacaoNum($objPermissaoDTO->getNumIdUsuario());
      }

      if ($objPermissaoDTO->isSetStrNomePerfil()) {
        $sql .= ' and perfil.nome=' . $this->getObjInfraIBanco()->formatarGravacaoStr($objPermissaoDTO->getStrNomePerfil());
      }

      $rs = $this->getObjInfraIBanco()->consultarSql($sql);

      $ret = array();

      if (count($rs) > 0) {
        $arrIdUnidades = array();
        foreach ($rs as $item) {
          $arrIdUnidades[] = $item['id_unidade'];
        }

        //Busca Hierarquia do sistema
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdHierarquia();
        $objSistemaDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());

        $objSistemaRN = new SistemaRN();
        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->retArrUnidadesInferiores();
        $objRelHierarquiaUnidadeDTO->retArrUnidadesSuperiores();
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrIdUnidades);
        $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
        $arrHierarquia = InfraArray::indexarArrInfraDTO($objRelHierarquiaUnidadeRN->listarHierarquia($objRelHierarquiaUnidadeDTO), 'IdUnidade');


        foreach ($rs as $item) {
          $numIdUnidade = $item['id_unidade'];

          if (isset($arrHierarquia[$numIdUnidade]) && !isset($ret[$numIdUnidade])) {
            $ret[$numIdUnidade] = array();
            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_ID] = $arrHierarquia[$numIdUnidade]->getNumIdUnidade();
            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_ORGAO_ID] = $arrHierarquia[$numIdUnidade]->getNumIdOrgaoUnidade();
            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_SIGLA] = $arrHierarquia[$numIdUnidade]->getStrSiglaUnidade();
            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_DESCRICAO] = $arrHierarquia[$numIdUnidade]->getStrDescricaoUnidade();
            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_SIN_ATIVO] = $arrHierarquia[$numIdUnidade]->getStrSinAtivo();

            $arrUnidadesSuperiores = array();
            $arrPais = $arrHierarquia[$numIdUnidade]->getArrUnidadesSuperiores();
            foreach ($arrPais as $pai) {
              $arrUnidadesSuperiores[] = $pai->getNumIdUnidade();
            }

            $arrSubunidades = array();
            $arrFilhas = $arrHierarquia[$numIdUnidade]->getArrUnidadesInferiores();
            foreach ($arrFilhas as $filha) {
              $arrSubunidades[] = $filha->getNumIdUnidade();
            }

            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_SUBUNIDADES] = $arrSubunidades;
            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_UNIDADES_SUPERIORES] = $arrUnidadesSuperiores;
            $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_ID_ORIGEM] = $arrHierarquia[$numIdUnidade]->getStrIdOrigemUnidade();

            if ($item['sin_subunidades'] == 'S') {
              $arrFilhas = $arrHierarquia[$numIdUnidade]->getArrUnidadesInferiores();

              foreach ($arrFilhas as $filha) {
                $numIdUnidadeFilha = $filha->getNumIdUnidade();

                if (isset($arrHierarquia[$numIdUnidadeFilha]) && !isset($ret[$numIdUnidadeFilha])) {
                  $ret[$numIdUnidadeFilha] = array();
                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_ID] = $numIdUnidadeFilha;
                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_ORGAO_ID] = $filha->getNumIdOrgaoUnidade();
                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_SIGLA] = $filha->getStrSiglaUnidade();
                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_DESCRICAO] = $filha->getStrDescricaoUnidade();
                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_SIN_ATIVO] = $filha->getStrSinAtivo();

                  $arrPais = $arrHierarquia[$numIdUnidadeFilha]->getArrUnidadesSuperiores();
                  foreach ($arrPais as $pai) {
                    $arrUnidadesSuperiores[] = $pai->getNumIdUnidade();
                  }

                  $arrFilhas = $arrHierarquia[$numIdUnidadeFilha]->getArrUnidadesInferiores();
                  foreach ($arrFilhas as $filha) {
                    $arrSubunidades[] = $filha->getNumIdUnidade();
                  }

                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_SUBUNIDADES] = $arrSubunidades;
                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_UNIDADES_SUPERIORES] = $arrUnidadesSuperiores;
                  $ret[$numIdUnidadeFilha][InfraSip::$WS_UNIDADE_ID_ORIGEM] = $filha->getStrIdOrigemUnidade();
                }
              }
            }
          }
        }
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro carregando Unidades.', $e);
    }
  }

  public function carregarUsuario(PermissaoDTO $objPermissaoDTO) {
    try {
      $sql = ' SELECT DISTINCT unidade.id_unidade, unidade.sigla as siglaunidade, unidade.descricao as descricaounidade, permissao.sin_subunidades, perfil.id_perfil, perfil.nome as nomeperfil' . ' FROM permissao, perfil, sistema, unidade, usuario' . ' WHERE (permissao.id_sistema=' . $this->getObjInfraIBanco()->formatarGravacaoNum($objPermissaoDTO->getNumIdSistema()) . ')' . ' and (permissao.dta_inicio) <= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' and (permissao.dta_fim >= ' . $this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::getStrDataAtual()) . ' or permissao.dta_fim is null)' .

        ' and (permissao.id_usuario=' . $this->getObjInfraIBanco()->formatarGravacaoNum($objPermissaoDTO->getNumIdUsuario()) . ')' . ' and (permissao.id_usuario=usuario.id_usuario)' . ' and usuario.sin_ativo=\'S\'' .

        ' and (permissao.id_perfil=perfil.id_perfil)' . ' and (permissao.id_sistema=perfil.id_sistema)' . ' and perfil.sin_ativo=\'S\'' .

        ' and (perfil.id_sistema=sistema.id_sistema)' . ' and sistema.sin_ativo=\'S\'' .

        ' and (permissao.id_unidade=unidade.id_unidade)' . ' and unidade.sin_ativo=\'S\'' . ' and unidade.sin_global=\'N\'' .

        ' order by id_unidade, sin_subunidades, id_perfil';


      //LogSip::getInstance()->gravar($sql);


      $rs = $this->getObjInfraIBanco()->consultarSql($sql);

      $ret = array();

      $numRegistros = count($rs);

      if ($numRegistros) {
        $arrSubUnidades = array();
        for ($i = 0; $i < $numRegistros; $i++) {
          if ($rs[$i]['sin_subunidades'] == 'S') {
            $arrSubUnidades[$rs[$i]['id_unidade']] = true;
          }
        }

        $arrHierarquia = array();
        if (count($arrSubUnidades)) {
          //Busca Hierarquia do sistema
          $objSistemaDTO = new SistemaDTO();
          $objSistemaDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
          $objSistemaDTO->setNumIdUnidade(array_keys($arrSubUnidades));

          $objSistemaRN = new SistemaRN();
          $arrHierarquia = InfraArray::indexarArrInfraDTO($objSistemaRN->listarHierarquia($objSistemaDTO), 'IdUnidade');
        }

        for ($i = 0; $i < $numRegistros; $i++) {
          $numIdUnidade = $rs[$i]['id_unidade'];

          if (!isset($ret[$numIdUnidade])) {
            $ret[$numIdUnidade] = array(
              'IdUnidade' => $rs[$i]['id_unidade'], 'SiglaUnidade' => $rs[$i]['siglaunidade'], 'DescricaoUnidade' => $rs[$i]['descricaounidade'], 'Perfis' => array()
            );
          }

          $arrPerfis = array();
          for ($j = $i; $j < $numRegistros; $j++) {
            if ($rs[$i]['id_unidade'] == $rs[$j]['id_unidade']) {
              $arrPerfis[] = array(
                'IdPerfil' => $rs[$j]['id_perfil'], 'NomePerfil' => $rs[$j]['nomeperfil']
              );
            } else {
              $j--;
              break;
            }
          }

          $arrPerfisUnidade = $ret[$numIdUnidade]['Perfis'];
          foreach ($arrPerfis as $arrPerfil) {
            if (!isset($arrPerfisUnidade[$arrPerfil['IdPerfil']])) {
              $arrPerfisUnidade[$arrPerfil['IdPerfil']] = $arrPerfil;
            }
          }
          $ret[$numIdUnidade]['Perfis'] = $arrPerfisUnidade;

          if ($rs[$i]['sin_subunidades'] == 'S') {
            $arrFilhas = $arrHierarquia[$numIdUnidade]->getArrUnidadesInferiores();
            foreach ($arrFilhas as $filha) {
              $numIdUnidadeFilha = $filha->getNumIdUnidade();

              if (!isset($ret[$numIdUnidadeFilha])) {
                $ret[$numIdUnidadeFilha] = array(
                  'IdUnidade' => $filha->getNumIdUnidade(), 'SiglaUnidade' => $filha->getStrSiglaUnidade(), 'DescricaoUnidade' => $filha->getStrDescricaoUnidade(), 'Perfis' => array()
                );
              }

              $arrPerfisUnidade = $ret[$numIdUnidadeFilha]['Perfis'];
              foreach ($arrPerfis as $arrPerfil) {
                if (!isset($arrPerfisUnidade[$arrPerfil['IdPerfil']])) {
                  $arrPerfisUnidade[$arrPerfil['IdPerfil']] = $arrPerfil;
                }
              }
              $ret[$numIdUnidadeFilha]['Perfis'] = $arrPerfisUnidade;
            }
          }
          $i = $j;
        }
      }

      $retFormatado = array();
      foreach ($ret as $arrUnidade) {
        $retFormatado[] = array(
          'IdUnidade' => $arrUnidade['IdUnidade'], 'SiglaUnidade' => $arrUnidade['SiglaUnidade'], 'DescricaoUnidade' => $arrUnidade['DescricaoUnidade'], 'Perfis' => array_values($arrUnidade['Perfis'])
        );
      }
      return $retFormatado;
    } catch (Exception $e) {
      throw new InfraException('Erro carregando Usuário.', $e);
    }
  }

}

?>