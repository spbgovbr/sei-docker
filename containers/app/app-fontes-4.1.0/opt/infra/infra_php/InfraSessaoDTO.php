<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 16/05/2006 - criado por MGA
 *
 * @package infra_php
 */

class InfraSessaoDTO
{
    private $strSiglaOrgaoSistema;
    private $numIdOrgaoSistema;
    private $strDescricaoOrgaoSistema;
    private $strSiglaSistema;
    private $numIdSistema;
    private $strSiglaOrgaoUsuario;
    private $strDescricaoOrgaoUsuario;
    private $numIdOrgaoUsuario;
    private $numIdContextoUsuario;
    private $numIdUsuario;
    private $strSiglaUsuario;
    private $strNomeUsuario;
    private $strNomeRegistroCivilUsuario;
    private $strNomeSocialUsuario;
    private $strHashInterno;
    private $strHashUsuario;
    private $bol2Fatores;
    private $arrUnidadesPadrao;
    private $numTimestampLogin;
    private $arrPropriedades;
    private $arrPermissoes;
    private $numIdUnidadeAtual;
    private $strPaginaInicial;
    private $strUltimaPagina;
    private $strIdOrigemUsuario;
    private $numVersaoSip;
    private $numVersaoInfraSip;
    private $strSiglaOrgaoUsuarioEmulador;
    private $strDescricaoOrgaoUsuarioEmulador;
    private $numIdOrgaoUsuarioEmulador;
    private $numIdUsuarioEmulador;
    private $strSiglaUsuarioEmulador;
    private $strNomeUsuarioEmulador;
    private $arrOrgaos;
    private $arrUnidades;
    private $dthUltimoLogin;


    public function __construct()
    {
        $this->arrPropriedades = array();
        $this->arrPermissoes = null;
        $this->numIdUnidadeAtual = null;
    }

    //SiglaOrgaoSistema
    public function setStrSiglaOrgaoSistema($strSiglaOrgaoSistema)
    {
        $this->strSiglaOrgaoSistema = $strSiglaOrgaoSistema;
    }

    public function getStrSiglaOrgaoSistema()
    {
        return $this->strSiglaOrgaoSistema;
    }

    //NumIdOrgaoSistema
    public function setNumIdOrgaoSistema($numIdOrgaoSistema)
    {
        $this->numIdOrgaoSistema = $numIdOrgaoSistema;
    }

    public function getNumIdOrgaoSistema()
    {
        return $this->numIdOrgaoSistema;
    }

    //DescricaoOrgaoSistema
    public function setStrDescricaoOrgaoSistema($strDescricaoOrgaoSistema)
    {
        $this->strDescricaoOrgaoSistema = $strDescricaoOrgaoSistema;
    }

    public function getStrDescricaoOrgaoSistema()
    {
        return $this->strDescricaoOrgaoSistema;
    }

    //StrSiglaSistema
    public function setStrSiglaSistema($strSiglaSistema)
    {
        $this->strSiglaSistema = $strSiglaSistema;
    }

    public function getStrSiglaSistema()
    {
        return $this->strSiglaSistema;
    }

    //NumIdSistema
    public function setNumIdSistema($numIdSistema)
    {
        $this->numIdSistema = $numIdSistema;
    }

    public function getNumIdSistema()
    {
        return $this->numIdSistema;
    }

    //NumIdUnidadeAtual
    public function setNumIdUnidadeAtual($numIdUnidadeAtual)
    {
        //Somente seta o id da unidade se ela foi carregada
        $arrPermissoes = $this->getArrPermissoes();

        foreach ($arrPermissoes as $permissao) {
            if (isset($permissao[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES][$numIdUnidadeAtual])) {
                $this->numIdUnidadeAtual = $numIdUnidadeAtual;
                return;
            }
        }

        $this->numIdUnidadeAtual = null;
    }

    //PaginaInicial
    public function setStrPaginaInicial($strPaginaInicial)
    {
        $this->strPaginaInicial = $strPaginaInicial;
    }

    public function getStrPaginaInicial()
    {
        return $this->strPaginaInicial;
    }

    //Ultima Pagina
    public function setStrUltimaPagina($strUltimaPagina)
    {
        $this->strUltimaPagina = $strUltimaPagina;
    }

    public function getStrUltimaPagina()
    {
        return $this->strUltimaPagina;
    }

    public function getNumIdUnidadeAtual()
    {
        return $this->numIdUnidadeAtual;
    }

    public function setArrPermissoes($arrPermissoes)
    {
        $this->arrPermissoes = $arrPermissoes;
    }

    public function getArrPermissoes()
    {
        return $this->arrPermissoes;
    }

    public function setStrSiglaOrgaoUsuario($strSiglaOrgaoUsuario)
    {
        $this->strSiglaOrgaoUsuario = $strSiglaOrgaoUsuario;
    }

    public function getStrSiglaOrgaoUsuario()
    {
        return $this->strSiglaOrgaoUsuario;
    }

    public function setStrDescricaoOrgaoUsuario($strDescricaoOrgaoUsuario)
    {
        $this->strDescricaoOrgaoUsuario = $strDescricaoOrgaoUsuario;
    }

    public function getStrDescricaoOrgaoUsuario()
    {
        return $this->strDescricaoOrgaoUsuario;
    }

    public function setNumIdOrgaoUsuario($numIdOrgaoUsuario)
    {
        $this->numIdOrgaoUsuario = $numIdOrgaoUsuario;
    }

    public function getNumIdOrgaoUsuario()
    {
        return $this->numIdOrgaoUsuario;
    }

    public function setNumIdContextoUsuario($numIdContextoUsuario)
    {
        $this->numIdContextoUsuario = $numIdContextoUsuario;
    }

    public function getNumIdContextoUsuario()
    {
        return $this->numIdContextoUsuario;
    }

    public function setStrSiglaUsuario($strSiglaUsuario)
    {
        $this->strSiglaUsuario = $strSiglaUsuario;
    }

    public function getStrSiglaUsuario()
    {
        return $this->strSiglaUsuario;
    }

    public function setStrNomeUsuario($strNomeUsuario)
    {
        $this->strNomeUsuario = $strNomeUsuario;
    }

    public function getStrNomeUsuario()
    {
        return $this->strNomeUsuario;
    }

    public function setStrNomeRegistroCivilUsuario($strNomeRegistroCivilUsuario)
    {
        $this->strNomeRegistroCivilUsuario = $strNomeRegistroCivilUsuario;
    }

    public function getStrNomeRegistroCivilUsuario()
    {
        return $this->strNomeRegistroCivilUsuario;
    }

    public function setStrNomeSocialUsuario($strNomeSocialUsuario)
    {
        $this->strNomeSocialUsuario = $strNomeSocialUsuario;
    }

    public function getStrNomeSocialUsuario()
    {
        return $this->strNomeSocialUsuario;
    }

    public function setNumIdUsuario($numIdUsuario)
    {
        $this->numIdUsuario = $numIdUsuario;
    }

    public function getNumIdUsuario()
    {
        return $this->numIdUsuario;
    }

    public function setDthUltimoLogin($dthUltimoLogin)
    {
        $this->dthUltimoLogin = $dthUltimoLogin;
    }

    public function getDthUltimoLogin()
    {
        return $this->dthUltimoLogin;
    }

    public function setStrIdOrigemUsuario($strIdOrigemUsuario)
    {
        $this->strIdOrigemUsuario = $strIdOrigemUsuario;
    }

    public function getStrIdOrigemUsuario()
    {
        return $this->strIdOrigemUsuario;
    }

    public function setStrHashInterno($strHashInterno)
    {
        $this->strHashInterno = $strHashInterno;
    }

    public function getStrHashInterno()
    {
        return $this->strHashInterno;
    }

    public function setStrHashUsuario($strHashUsuario)
    {
        $this->strHashUsuario = $strHashUsuario;
    }

    public function getStrHashUsuario()
    {
        return $this->strHashUsuario;
    }

    public function setBol2Fatores($bol2Fatores)
    {
        $this->bol2Fatores = $bol2Fatores;
    }

    public function getBol2Fatores()
    {
        return $this->bol2Fatores;
    }

    public function setArrUnidadesPadrao($arrUnidadesPadrao)
    {
        $this->arrUnidadesPadrao = $arrUnidadesPadrao;
    }

    public function getArrUnidadesPadrao()
    {
        return $this->arrUnidadesPadrao;
    }

    public function getNumTimestampLogin()
    {
        return $this->numTimestampLogin;
    }

    public function setNumTimestampLogin($numTimestampLogin)
    {
        $this->numTimestampLogin = $numTimestampLogin;
    }

    public function setArrPropriedades($arrPropriedades)
    {
        $this->arrPropriedades = $arrPropriedades;
    }

    public function getArrPropriedades()
    {
        return $this->arrPropriedades;
    }

    public function setPropriedade($strSistema, $strChave, $strPropriedade, $strValor)
    {
        $strChave = strtolower($strChave);
        $strPropriedade = strtolower($strPropriedade);
        if (!isset($this->arrPropriedades[$strSistema])) {
            $this->arrPropriedades[$strSistema] = array();
        }
        if (!isset($this->arrPropriedades[$strSistema][$strChave])) {
            $this->arrPropriedades[$strSistema][$strChave] = array();
        }
        if (!isset($this->arrPropriedades[$strSistema][$strChave][$strPropriedade])) {
            $this->arrPropriedades[$strSistema][$strChave][$strPropriedade] = array();
        }
        $this->arrPropriedades[$strSistema][$strChave][$strPropriedade] = $strValor;
    }

    public function getPropriedade($strSistema, $strChave, $strPropriedade)
    {
        $strChave = strtolower($strChave);
        $strPropriedade = strtolower($strPropriedade);

        if (!isset($this->arrPropriedades[$strSistema])) {
            return null;
        }
        if (!isset($this->arrPropriedades[$strSistema][$strChave])) {
            return null;
        }

        if (!isset($this->arrPropriedades[$strSistema][$strChave][$strPropriedade])) {
            return null;
        }
        return $this->arrPropriedades[$strSistema][$strChave][$strPropriedade];
    }

    public function setNumVersaoSip($numVersaoSip)
    {
        $this->numVersaoSip = $numVersaoSip;
    }

    public function getNumVersaoSip()
    {
        return $this->numVersaoSip;
    }

    public function setNumVersaoInfraSip($numVersaoInfraSip)
    {
        $this->numVersaoInfraSip = $numVersaoInfraSip;
    }

    public function getNumVersaoInfraSip()
    {
        return $this->numVersaoInfraSip;
    }

    public function setStrSiglaOrgaoUsuarioEmulador($strSiglaOrgaoUsuarioEmulador)
    {
        $this->strSiglaOrgaoUsuarioEmulador = $strSiglaOrgaoUsuarioEmulador;
    }

    public function getStrSiglaOrgaoUsuarioEmulador()
    {
        return $this->strSiglaOrgaoUsuarioEmulador;
    }

    public function setStrDescricaoOrgaoUsuarioEmulador($strDescricaoOrgaoUsuarioEmulador)
    {
        $this->strDescricaoOrgaoUsuarioEmulador = $strDescricaoOrgaoUsuarioEmulador;
    }

    public function getStrDescricaoOrgaoUsuarioEmulador()
    {
        return $this->strDescricaoOrgaoUsuarioEmulador;
    }

    public function setNumIdOrgaoUsuarioEmulador($numIdOrgaoUsuarioEmulador)
    {
        $this->numIdOrgaoUsuarioEmulador = $numIdOrgaoUsuarioEmulador;
    }

    public function getNumIdOrgaoUsuarioEmulador()
    {
        return $this->numIdOrgaoUsuarioEmulador;
    }

    public function setStrSiglaUsuarioEmulador($strSiglaUsuarioEmulador)
    {
        $this->strSiglaUsuarioEmulador = $strSiglaUsuarioEmulador;
    }

    public function getStrSiglaUsuarioEmulador()
    {
        return $this->strSiglaUsuarioEmulador;
    }

    public function setStrNomeUsuarioEmulador($strNomeUsuarioEmulador)
    {
        $this->strNomeUsuarioEmulador = $strNomeUsuarioEmulador;
    }

    public function getStrNomeUsuarioEmulador()
    {
        return $this->strNomeUsuarioEmulador;
    }

    public function setNumIdUsuarioEmulador($numIdUsuarioEmulador)
    {
        $this->numIdUsuarioEmulador = $numIdUsuarioEmulador;
    }

    public function getNumIdUsuarioEmulador()
    {
        return $this->numIdUsuarioEmulador;
    }

    public function setArrOrgaos($arrOrgaos)
    {
        $this->arrOrgaos = $arrOrgaos;
    }

    public function getArrOrgaos()
    {
        return $this->arrOrgaos;
    }

    public function setArrUnidades($arrUnidades)
    {
        $this->arrUnidades = $arrUnidades;
    }

    public function getArrUnidades()
    {
        return $this->arrUnidades;
    }

    public function __toString()
    {
        return get_class($this);
    }
}

