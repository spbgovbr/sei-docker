<?php
/**
 * @package infra_php
 */


abstract class InfraCache
{

    public static $MEMCACHE = 'Memcache';
    public static $REDIS = 'Redis';

    private $objCache = null;
    private $strTipoCache = null;

    public function __construct()
    {
        $this->strTipoCache = $this->getStrTipo();

        if ($this->getStrServidor() != '') {
            if ($this->strTipoCache == self::$MEMCACHE) {
                $this->objCache = new Memcache();
            } elseif ($this->strTipoCache == self::$REDIS) {
                $this->objCache = new Redis();
            } else {
                throw new InfraException('Tipo do objeto [' . $this->strTipoCache . '] de cache inválido.');
            }

            try {
                if (InfraDebug::isBolProcessar()) {
                    InfraDebug::getInstance()->gravarInfra(
                        '[InfraCache->conectar] ' . $this->strTipoCache . ' ' . $this->getStrServidor(
                        ) . ':' . $this->getNumPorta()
                    );
                }

                $this->objCache->connect($this->getStrServidor(), $this->getNumPorta(), $this->getNumTimeout());
            } catch (Exception $e) {
                $this->objCache = null;

                if ($this->getObjInfraLog() != null) {
                    try {
                        $this->getObjInfraLog()->gravar(
                            'Servidor [' . $_SERVER['SERVER_ADDR'] . '] não conseguiu acessar o serviço do ' . ($this->strTipoCache == self::$MEMCACHE ? 'Memcache' : 'Redis') . ' no endereço [' . $this->getStrServidor(
                            ) . ':' . $this->getNumPorta() . '].' . "\n\n" . InfraException::inspecionar($e)
                        );
                    } catch (Exception $e) {
                    }
                }
            }
        }
    }

    abstract public function getStrServidor();

    abstract public function getNumPorta();

    abstract public function getObjInfraSessao();

    public function getObjInfraLog()
    {
        return null;
    }

    public function getNumTimeout()
    {
        return 1;
    }

    public function getStrTipo()
    {
        return self::$MEMCACHE;
    }

    public function setAtributo($strChave, $varValor, $numTempo)
    {
        if ($this->objCache != null) {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra('[InfraCache->setAtributo] ' . $strChave);
            }

            $strChave = trim($strChave);

            if ($strChave == '') {
                throw new InfraException('Chave do atributo não informada.');
            }

            if (strlen($strChave) > 250) {
                throw new InfraException('Chave do atributo não pode ter mais de 250 caracteres.');
            }

            if ($numTempo < 0 || $numTempo > 2592000) {
                throw new InfraException('Tempo de armazenagem na cache inválido para o atributo ' . $strChave . '.');
            }

            if ($this->strTipoCache == self::$MEMCACHE) {
                if ($this->objCache->replace($this->formatarChave($strChave), $varValor, 0, $numTempo) === false) {
                    if ($this->objCache->set($this->formatarChave($strChave), $varValor, 0, $numTempo) === false) {
                        throw new InfraException('Erro configurando atributo ' . $strChave . ' na cache.');
                    }
                }
            } else {
                $this->objCache->setex($this->formatarChave($strChave), $numTempo, $varValor);
            }
        }
    }

    public function getAtributo($strChave)
    {
        $ret = null;

        if ($this->objCache != null) {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra('[InfraCache->getAtributo] ' . $strChave);
            }

            $strChave = trim($strChave);

            if ($strChave == '') {
                throw new InfraException('Chave do atributo não informada.');
            }

            $ret = $this->objCache->get($this->formatarChave($strChave));
        }

        return $ret;
    }

    public function removerAtributo($strChave)
    {
        $ret = null;

        if ($this->objCache != null) {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra('[InfraCache->remover] ' . $strChave);
            }

            $strChave = trim($strChave);

            if ($strChave == '') {
                throw new InfraException('Chave do atributo não informada.');
            }

            if ($this->strTipoCache == self::$MEMCACHE) {
                $ret = $this->objCache->delete($this->formatarChave($strChave), 0);
            } else {
                $ret = $this->objCache->del($this->formatarChave($strChave));
            }
        }

        return $ret;
    }

    public function listarAtributos()
    {
        $ret = array();

        if ($this->objCache != null) {
            if (InfraDebug::isBolProcessar()) {
                InfraDebug::getInstance()->gravarInfra('[InfraCache->listarAtributos]');
            }

            $strChaveSistema = str_replace(' ', '_', $this->getStrChaveSistema()) . '.';
            $numTamChaveSistema = strlen($this->getStrChaveSistema()) + 1;

            if ($this->strTipoCache == self::$MEMCACHE) {
                $allSlabs = $this->objCache->getExtendedStats('slabs');

                foreach ($allSlabs as $server => $slabs) {
                    if (!is_array($slabs)) {
                        continue;
                    }

                    foreach (array_keys($slabs) as $slabId) {
                        $cdump = $this->objCache->getExtendedStats('cachedump', (int)$slabId, 999999999);

                        if (!is_array($cdump)) {
                            continue;
                        }

                        foreach ($cdump as $keys => $arrVal) {
                            if (!is_array($arrVal)) {
                                continue;
                            }

                            foreach (array_keys($arrVal) as $k) {
                                if (substr($k, 0, $numTamChaveSistema) == $strChaveSistema) {
                                    $ret[] = substr($k, $numTamChaveSistema);
                                }
                            }
                        }
                    }
                }
            } else {
                $ret = $this->objCache->keys($strChaveSistema . '*');
            }
        }

        return $ret;
    }

    private function getStrChaveSistema()
    {
        return $this->getObjInfraSessao()->getStrSiglaOrgaoSistema() . '.' . $this->getObjInfraSessao(
            )->getStrSiglaSistema();
    }

    private function formatarChave($strChave)
    {
        return $this->getStrChaveSistema() . '.' . $strChave;
    }
}
