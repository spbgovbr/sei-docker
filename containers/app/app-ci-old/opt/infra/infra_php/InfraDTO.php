<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 16/05/2006 - criado por MGA
 *
 * @version $Id$
 * @package infra_php
 *
 */
abstract class InfraDTO
{
    private static $arrMetaDados = null;
    private static $arrMetaDadosFK = null;

    private $bolInicializando = false;
    private $bolSohConfigurarFKs = false;

    private $bolRecursivo = false;
    private $strClasse = null;
    private $arrAtributos = array();
    private $arrPK = array();
    private $arrFK = array();
    private $arrOrd = array();
    private $arrCriterios = array();
    private $arrGruposCriterios = array();
    private $numFlags = 0;
    private $objInfraException = null;
    private $numMaxRegRet = null;
    private $strCriterioSqlNativo = null;
    private $arrCriteriosSqlNativos = null;
    private $strSubSelectSqlNativo = null;
    private $strSqlPesquisa = null;
    private $strSqlSubstituicao = null;

    public static $POS_ENTIDADE_ARTIGO = 0;
    public static $POS_ENTIDADE_NOME_SINGULAR = 1;
    public static $POS_ENTIDADE_NOME_PLURAL = 2;

    public static $POS_CAMPO_ARTIGO = 0;
    public static $POS_CAMPO_NOME = 1;
    public static $POS_CAMPO_ATALHO = 2;

    //Paginacao
    private $arrPaginacao = null;
    private static $POS_PAG_ATUAL = 0;
    private static $POS_PAG_TOTAL_REG = 1;
    private static $POS_PAG_TOTAL_PAG = 2;

    private static $FLAG_DISTINCT = 1;
    private static $FLAG_RELACIONADOS = 2;
    private static $FLAG_COMPOSTO = 4;
    private static $FLAG_CONFIGUROU_EL = 8;
    private static $FLAG_EL = 16;
    private static $FLAG_RET_TODOS = 32;
    //private static $FLAG_MONTAR_CHAVES = 64;

    public static $POS_ATRIBUTO_PREFIXO = 0;
    public static $POS_ATRIBUTO_NOME = 1;
    public static $POS_ATRIBUTO_VALOR = 2;
    public static $POS_ATRIBUTO_CAMPO_SQL = 3;
    public static $POS_ATRIBUTO_TAB_ORIGEM = 4;
    public static $POS_ATRIBUTO_FLAGS = 5;

    public static $FLAG_SET = 1;
    public static $FLAG_RET = 2;
    public static $FLAG_ORD = 4;
    public static $FLAG_IGUAL = 8;
    public static $FLAG_LIKE = 16;
    public static $FLAG_DIFERENTE = 32;
    public static $FLAG_IN = 64;
    public static $FLAG_NOT_IN = 128;
    public static $FLAG_MAIOR = 256;
    public static $FLAG_MENOR = 512;
    public static $FLAG_MAIOR_IGUAL = 1024;
    public static $FLAG_MENOR_IGUAL = 2048;
    public static $FLAG_NOT_LIKE = 4096;
    public static $FLAG_SET_CASE_INSENSITIVE = 8192;
    public static $FLAG_VALOR_CASE_INSENSITIVE = 16384;
    public static $FLAG_BIT_AND = 32768;

    public static $TIPO_PK_INFORMADO = 0;
    public static $TIPO_PK_SEQUENCIAL = 1;
    public static $TIPO_PK_NATIVA = 2;

    public static $TIPO_FK_OBRIGATORIA = 0;
    public static $TIPO_FK_OPCIONAL = 1;

    public static $POS_FK_CAMPO = 0;
    public static $POS_FK_TIPO = 1;
    public static $POS_FK_FILTRO = 2;

    public static $PREFIXO_NUM = 'Num';
    public static $PREFIXO_DIN = 'Din';
    public static $PREFIXO_DBL = 'Dbl';
    public static $PREFIXO_STR = 'Str';
    public static $PREFIXO_DTA = 'Dta';
    public static $PREFIXO_DTH = 'Dth';
    public static $PREFIXO_BOL = 'Bol';
    public static $PREFIXO_ARR = 'Arr';
    public static $PREFIXO_OBJ = 'Obj';
    public static $PREFIXO_BIN = 'Bin';

    public static $OPER_IN = 'IN';
    public static $OPER_NOT_IN = 'NOT IN';
    public static $OPER_IGUAL = '=';
    public static $OPER_DIFERENTE = '<>';
    public static $OPER_LIKE = 'LIKE';
    public static $OPER_NOT_LIKE = 'NOT LIKE';
    public static $OPER_MAIOR = '>';
    public static $OPER_MENOR = '<';
    public static $OPER_MAIOR_IGUAL = '>=';
    public static $OPER_MENOR_IGUAL = '<=';
    public static $OPER_BIT_AND = '&';

    public static $OPER_LOGICO_AND = 'AND';
    public static $OPER_LOGICO_OR = 'OR';

    public static $POS_CRITERIO_NOME = 0;
    public static $POS_CRITERIO_ATRIBUTOS = 1;
    public static $POS_CRITERIO_OPERADORES_ATRIBUTOS = 2;
    public static $POS_CRITERIO_VALORES_ATRIBUTOS = 3;
    public static $POS_CRITERIO_OPERADORES_LOGICOS = 4;

    public static $POS_GRUPO_CRITERIO_NOME = 0;
    public static $POS_GRUPO_CRITERIO_CRITERIOS = 1;
    public static $POS_GRUPO_CRITERIO_OPERADORES_LOGICOS = 2;

    public static $POS_CRITERIO_SQL_NATIVO_NOME = 0;
    public static $POS_CRITERIO_SQL_NATIVO_VALOR = 1;

    public static $TIPO_ORDENACAO_ASC = 'ASC';
    public static $TIPO_ORDENACAO_DESC = 'DESC';

    public static $FILTRO_FK_ON = 0;
    public static $FILTRO_FK_WHERE = 1;

    public abstract function getStrNomeTabela();

    public abstract function montar();

    public function getStrNomeSequenciaNativa()
    {
        return 'seq_' . $this->getStrNomeTabela();
    }

    /** Marca ativos os dígitos de exclusão lógica e a opção para aceitar atributos de tabelas
     * relacionadas na flag do DTO. Se $bolComposto for marcado true, seta flag do DTO para marcá-lo como composto.
     * Coloca no array de metadados o nome da classe filha da InfraDTO.
     * @param boolean $bolComposto - é false por padrão
     */
    public function __construct($bolComposto = false)
    {

        $this->numFlags = self::$FLAG_EL | self::$FLAG_RELACIONADOS;

        if ($bolComposto) {
            $this->numFlags |= self::$FLAG_COMPOSTO;
        }

        $this->strClasse = get_class($this);

        if (self::$arrMetaDados == null) {
            self::$arrMetaDados = array();
        }

        if (self::$arrMetaDadosFK == null) {
            self::$arrMetaDadosFK = array();
        }

        if (!isset(self::$arrMetaDados[$this->strClasse])) {
            $this->inicializar();
        }
    }

    private function inicializar()
    {
        //
        // Ao invocar o código do usuário "montar()", podem ocorrer excecoes.
        // Caso isso ocorra, limpamos as definicoes estáticas dos atributos,
        // para que não fiquem em estado inconsistente.
        //
        try {

            $this->bolInicializando = true;
            $this->montar();
            $this->bolInicializando = false;

        } catch (Exception $e) {
            self::$arrMetaDados = null;
            self::$arrMetaDadosFK = null;
            $this->bolInicializando = false;
            throw $e;
        }
    }

    /** Guarda o nome da classe filha (ex. PessoaDTO), obtido no array de metadados, na variavel arrMDS.
     * Cria um array que lista cada item não nulo do array de DTO.
     * @return array
     */
    public function __sleep()
    {

        $arrSleep = array();

        $arrSleep[] = "\0InfraDTO\0strClasse";

        if ($this->numFlags) {
            $arrSleep[] = "\0InfraDTO\0numFlags";
        }

        if ($this->arrAtributos !== null) {
            $arrSleep[] = "\0InfraDTO\0arrAtributos";
        }

        if ($this->arrPK !== null) {
            $arrSleep[] = "\0InfraDTO\0arrPK";
        }

        if ($this->arrFK !== null) {
            $arrSleep[] = "\0InfraDTO\0arrFK";
        }

        if ($this->arrOrd !== null) {
            $arrSleep[] = "\0InfraDTO\0arrOrd";
        }

        if ($this->arrCriterios !== null) {
            $arrSleep[] = "\0InfraDTO\0arrCriterios";
        }

        if ($this->arrGruposCriterios !== null) {
            $arrSleep[] = "\0InfraDTO\0arrGruposCriterios";
        }

        if ($this->numMaxRegRet !== null) {
            $arrSleep[] = "\0InfraDTO\0numMaxRegRet";
        }

        if ($this->arrPaginacao !== null) {
            $arrSleep[] = "\0InfraDTO\0arrPaginacao";
        }

        return $arrSleep;
    }

    /**
     * Cria a variável de metadados se não existe. Se o nome da classe não é determinado,
     * busca no nome na variável arrMDS. Se arrMDS é um array, o utiliza para preencher o array de metadados.
     * @return void
     */
    public function __wakeup()
    {

        if (!isset(self::$arrMetaDados) || !isset(self::$arrMetaDados[$this->strClasse])) {
            $this->inicializar();
        }

    }

    /**
     * Marca a flag do DTO no dígito de montar chaves. Chama a função montar(), que é abstrata na InfraDTO, e
     * implementada nas classes de DTO's, para adicionar os diferentes tipos de atributos. Após passar pela função,
     * desmarca a flag na posição.
     * @return void
     * @throws Exception
     */
    public function montarChaves()
    {
        //InfraDebug::getInstance()->gravarInfra('montarChaves');

        $this->montarArrayDeFKs();

        //
        // Invoca "montar" para atualizar alterações nas obrigatoriedades das FKs.
        // Eventualmente os tipos (FK opcional ou obrigatória) pode mudar a cada invocação do montar,
        // dependendo da lógica interna do montar.
        //
        try {

            $this->bolSohConfigurarFKs = true;
            $this->montar();
            $this->bolSohConfigurarFKs = false;

        } catch (Exception $e) {
            $this->bolSohConfigurarFKs = false;
            throw $e;
        }

        $this->validarSeFKsCompostasSaoDoMesmoTipo();
    }

    /**
     * Valida se as FKs que foram configuradas (arrFK) para uma determinada tabela
     * são do mesmo tipo (obrigatórias ou opcionais).
     * @return void
     * @throws InfraException, se uma FK composta tiver atributos com tipos diferentes
     */
    private function validarSeFKsCompostasSaoDoMesmoTipo()
    {
        if (isset($this->arrFK)) {

            //
            // para cada tabela relacionada que foi configurada...
            //
            $tabelasRelacionadas = array_keys($this->arrFK);
            foreach ($tabelasRelacionadas as $tabela) {

                //
                // checaremos se os tipos de cada atributo sao iguais
                //
                $fks = array_keys($this->arrFK[$tabela]);
                $tipo = null;
                foreach ($fks as $fk) {

                    if (!isset($tipo)) {
                        //
                        // armazenamos o tipo do primeiro atributo e processamos o proximo
                        //
                        $tipo = $this->arrFK[$tabela][$fk][self::$POS_FK_TIPO];
                        continue;
                    }

                    //
                    // se o tipo for diferente, atiramos excecao
                    //
                    if ($tipo !== $this->arrFK[$tabela][$fk][self::$POS_FK_TIPO]) {
                        throw new InfraException('Uma FK composta deve ter o seu tipo igual para todos os atributos.');
                    }
                }
            }
        }
    }

    /**
     * Monta o arrFK com base nos critérios e atributos informados no DTO.
     *
     * @return void
     * @throws InfraException
     */
    private function montarArrayDeFKs()
    {

        //
        // prepara um novo array de FKs
        //
        if ($this->arrFK != null) {
            $this->arrFK = null;
        }

        //
        //se faz parte de algum critério, adiciona o atributo
        //
        if (is_array($this->arrCriterios)) {
            foreach ($this->arrCriterios as $criterio) {
                foreach ($criterio[self::$POS_CRITERIO_ATRIBUTOS] as $atributo) {
                    $this->carregarAtributo($atributo);
                }
            }
        }

        //
        // montando o array de FKs com base nos atributos desejados
        //
        if (isset($this->arrAtributos)) {

            //
            // primeiro, montamos um array derivado contendo apenas atributos que possuem tabela relacionada configurada
            //
            $atributosComTabelaRelacionada = array();
            $atributos = array_keys($this->arrAtributos);
            foreach ($atributos as $atributo) {
                if (!isset(self::$arrMetaDados[$this->strClasse][$atributo][self::$POS_ATRIBUTO_TAB_ORIGEM])) {
                    continue;
                }

                $tabelaOrigem = self::$arrMetaDados[$this->strClasse][$atributo][self::$POS_ATRIBUTO_TAB_ORIGEM];
                if (isset($tabelaOrigem) && !isset($atributosComTabelaRelacionada[$tabelaOrigem])) {
                    $atributosComTabelaRelacionada[$atributo] = self::$arrMetaDados[$this->strClasse][$atributo];
                }
            }

            //
            // vamos configurar as FKs apenas quando existir algum atributo a ser usado e que tenha tabela relacionada
            //
            if (count($atributosComTabelaRelacionada) > 0) {

                //
                // Os arrays $nodes e $dependencias sao usados para ordenar o array de FKs, caso o usuário queira
                // comparar uma coluna de uma tabela relacionada com outra coluna de outra tabela relacionada declarados
                // no DTO.
                // $nodes contem a lista de colunas
                // $dependencias contem uma lista contendo um array com as dependencias.
                $nodes = array();
                $dependencias = array();

                //
                // lista contendo os nomes dos atributos relacionados a serem carregados
                //
                $atributos = array_keys($atributosComTabelaRelacionada);

                //
                // IMPORTANTE: não usamos foreach aqui porque a lista de atributos poderá ser aumentada durante o loop.
                // Isso ocorrerá quando uma FK depende de outra FK que não foi marcada para ser carregada. Nesse caso o
                // loop adicionará a dependência na lista de atributos a serem carregados.
                //
                for ($i = 0; $i < count($atributos); $i++) {
                    $atributo = $atributos[$i];

                    //
                    // verificamos a definição da tabela relacionada no array de metadados
                    //
                    $strTabelaRelacionada = self::$arrMetaDados[$this->strClasse][$atributo][self::$POS_ATRIBUTO_TAB_ORIGEM];
                    if (isset(self::$arrMetaDadosFK[$this->strClasse][$strTabelaRelacionada])) {

                        if ($this->arrFK === null) {
                            $this->arrFK = array();
                        }

                        if (!array_key_exists($strTabelaRelacionada, $this->arrFK)) {
                            $this->arrFK[$strTabelaRelacionada] = array();
                            $nodes[] = $strTabelaRelacionada;
                        }


                        //
                        // obtemos a lista de atributos que compoem a FK (pode ser composta)
                        //
                        $fks = array_keys(self::$arrMetaDadosFK[$this->strClasse][$strTabelaRelacionada]);
                        foreach ($fks as $fk) {
                            if (!isset($this->arrFK[$strTabelaRelacionada][$fk])) {
                                $this->arrFK[$strTabelaRelacionada][$fk] = array();
                                $this->arrFK[$strTabelaRelacionada][$fk][self::$POS_FK_CAMPO] = self::$arrMetaDadosFK[$this->strClasse][$strTabelaRelacionada][$fk];
                            }

                            //
                            // se o atributo que compoe a FK nao esta na lista de atributos:
                            // - carrega o atributo
                            // - marca para inclusao na lista de FKs (se o atributo depender de outra FK, será processado adequadamente)
                            //
                            if (!isset($this->arrAtributos[$fk])) {
                                $this->carregarAtributo($fk);
                                //array_splice($atributos, $i+1, 0, $fk);
                                $atributos[] = $fk;

                                $strTabelaRelacionada2 = self::$arrMetaDados[$this->strClasse][$fk][self::$POS_ATRIBUTO_TAB_ORIGEM];
                                if (isset($strTabelaRelacionada2) && isset($strTabelaRelacionada)) {
                                    $dependencias[] = array($strTabelaRelacionada2, $strTabelaRelacionada);
                                }
                            }
                        }
                    }
                }

                $bolOrdenarTopologicamente = false;
                $keys = array_keys($this->arrAtributos);
                foreach ($keys as $key) {
                    $valor = $this->arrAtributos[$key][self::$POS_ATRIBUTO_VALOR];
                    $definicao = self::$arrMetaDados[$this->strClasse][$key];

                    if (isset($definicao[self::$POS_ATRIBUTO_TAB_ORIGEM]) && is_object($valor) && $valor instanceof InfraAtributoDTO) {
                        $definicaoDoOutroAtributo = $valor->getArrAtributo();

                        if (isset($definicaoDoOutroAtributo) && isset($definicaoDoOutroAtributo[self::$POS_ATRIBUTO_TAB_ORIGEM])) {

                            $tabela = $definicao[self::$POS_ATRIBUTO_TAB_ORIGEM];
                            $tabelaDependente = $definicaoDoOutroAtributo[self::$POS_ATRIBUTO_TAB_ORIGEM];

                            if (array_key_exists($tabela, $this->arrFK) && array_key_exists($tabelaDependente, $this->arrFK)) {

                                //
                                // se entrar aqui, é porque o usuário deseja comparar um atributo de uma tabela relacionada
                                // com outro atributo de outra tabela relacionada, em uma situação em que uma depende da outra.
                                // Nesse caso, temos que ordenar o arrFK para que as tabelas dependentes sejam declaradas
                                // depois das tabelas sem dependencias.
                                // Caso isso não seja feito, pode ocorrer do SQL gerado ter um join com uma clausula ON
                                // que depende de outra tabela que ainda não foi declarada no join, causando um erro de:
                                // Error: Unknown column 'nometabela.nomecoluna' in 'on clause'
                                //
                                $bolOrdenarTopologicamente = true;

                                $dependencias[] = array($tabelaDependente, $tabela);
                            }
                        }
                    }
                }


                if($bolOrdenarTopologicamente){
                    $sorted = $this->topological_sort($nodes, $dependencias);

                    if(isset($sorted)){
                        $novo = array();
                        foreach($sorted as $key){
                            $novo[$key] = $this->arrFK[$key];
                        }
                        $this->arrFK = $novo;
                    }
                }
            }
        }

    }

    /*
     * Topological Sort:
     * https://gist.github.com/gowon/1744369137826450b185#file-topsort2-php
     *
     * Args:
	 *	$nodeids - an array of node ids,
	 *				e.g. array('paris', 'milan', 'vienna', ...);
 	 *	$edges - an array of directed edges,
	 *				e.g. array(array('paris','milan'),
	 *						   array('milan', 'vienna'),
	 *						   ...)
     * Returns:
 	 *	topologically sorted array of node ids, or NULL if graph is
	 *	unsortable (i.e. contains cycles)
     * */
    private function topological_sort($nodeids, $edges)
    {
        // initialize variables
        $L = $S = $nodes = array();
        // remove duplicate nodes
        $nodeids = array_unique($nodeids);
        // remove duplicate edges
        $hashes = array();
        foreach ($edges as $k => $e) {
            $hash = md5(serialize($e));
            if (in_array($hash, $hashes)) {
                unset($edges[$k]);
            } else {
                $hashes[] = $hash;
            };
        }
        // Build a lookup table of each node's edges
        foreach ($nodeids as $id) {
            $nodes[$id] = array('in' => array(), 'out' => array());
            foreach ($edges as $e) {
                if ($id == $e[0]) {
                    $nodes[$id]['out'][] = $e[1];
                }
                if ($id == $e[1]) {
                    $nodes[$id]['in'][] = $e[0];
                }
            }
        }
        // While we have nodes left, we pick a node with no inbound edges,
        // remove it and its edges from the graph, and add it to the end
        // of the sorted list.
        foreach ($nodes as $id => $n) {
            if (empty($n['in'])) $S[] = $id;
        }
        while (!empty($S)) {
            $L[] = $id = array_shift($S);
            foreach ($nodes[$id]['out'] as $m) {
                $nodes[$m]['in'] = array_diff($nodes[$m]['in'], array($id));
                if (empty($nodes[$m]['in'])) {
                    $S[] = $m;
                }
            }
            $nodes[$id]['out'] = array();
        }
        // Check if we have any edges left unprocessed
        foreach ($nodes as $n) {
            if (!empty($n['in']) or !empty($n['out'])) {
                return null; // not sortable as graph is cyclic
            }
        }
        return $L;
    }

    /**
     * Coloca em um array de atribudos ($arrAtributos), para cada atributo existente na variável
     * arrAtributos do DTO, um array em uma    nova dimensão com os dados: prefixo, nome, campo sql e tabela de origem.
     * @return array $arrAtributos
     */
    public function getArrAtributos()
    {
        $arrMetaDados = self::$arrMetaDados[$this->strClasse];
        $arrAtributos = $this->arrAtributos;
        if ($arrAtributos != null) {
            $arrKeys = array_keys($arrAtributos);
            foreach ($arrKeys as $key) {
                $arrTemp = $arrMetaDados[$key];
                $arrAtributos[$key][self::$POS_ATRIBUTO_PREFIXO] = $arrTemp[self::$POS_ATRIBUTO_PREFIXO];
                $arrAtributos[$key][self::$POS_ATRIBUTO_NOME] = $arrTemp[self::$POS_ATRIBUTO_NOME];
                $arrAtributos[$key][self::$POS_ATRIBUTO_CAMPO_SQL] = $arrTemp[self::$POS_ATRIBUTO_CAMPO_SQL];
                $arrAtributos[$key][self::$POS_ATRIBUTO_TAB_ORIGEM] = $arrTemp[self::$POS_ATRIBUTO_TAB_ORIGEM];
            }
        }
        return $arrAtributos;
    }

    /**
     * Usado para comparação de valores entre atributos do DTO através de métodos "set".
     * @param $strAtributo
     * @return InfraAtributoDTO
     * @throws InfraException
     */
    public function getObjInfraAtributoDTO($strAtributo)
    {
        $this->carregarAtributo($strAtributo);
        $arrMetaDados = self::$arrMetaDados[$this->strClasse];
        $objInfraAtributoDTO = new InfraAtributoDTO();
        $objInfraAtributoDTO->setStrNomeClasse($this->strClasse);
        $objInfraAtributoDTO->setArrAtributo($arrMetaDados[$strAtributo]);
        return $objInfraAtributoDTO;
    }

    /**
     * Determina a lista de atributos do DTO.
     * @param $arrAtributos
     */
    public function setArrAtributos($arrAtributos)
    {
        $this->arrAtributos = $arrAtributos;
    }

    /**
     *  Obtém o array de configuração da chave primária do DTO.
     * @return array
     */
    public function getArrPK()
    {
        return $this->arrPK;
    }

    /**
     *  Obtém o array de configuração das chaves estrangeiras do DTO.
     * @return array
     */
    public function getArrFK()
    {
        return $this->arrFK;
    }

    /**
     *  Obtém o array de configuração da ordenação dos resultados da consulta usando o DTO.
     *  <code>["arrOrd":"InfraDTO":private]=>  array(2) { [0]=> array(2) {
     * [0] => string(11) "DataCriacao"
     * [1]=> string(3) "ASC"}}</code>
     * @return array
     */
    public function getArrOrdenacao()
    {
        return $this->arrOrd;
    }

    /**
     *  Obtém o array de configuração dos critérios usados em uma operação usando o DTO.
     *  <code> ["arrCriterios":"InfraDTO":private]=> array(2) {
     * [0]=> array(5) {
     *    [0]=> string(5) "Criterio1"
     *    [1]=> array(1) {[0]=> string(8) "SinAtivo" }
     *    [2]=> array(1) { [0]=> string(1) "=" }
     *    [3]=> array(1) {[0]=> string(1) "S" }
     *    [4]=> array(0) {}
     *    }} </code>
     * @return array
     */
    public function getArrCriterios()
    {
        return $this->arrCriterios;
    }

    /**
     *  Obtém o valor da variavel do DTO arrGruposCriterios.
     *  <code>["arrGruposCriterios":"InfraDTO":private]=> array(1) {
     * [0]=> array(2) {
     *        [0]=> array(2) { [0]=> string(5) "Criterio1" [1]=> string(5) "Criterio2" }
     *        [1]=> array(1) { [0]=> string(3) "AND" }
     *  }
     *} </code>
     * @return array
     */
    public function getArrGruposCriterios()
    {
        return $this->arrGruposCriterios;
    }

    /** Atribui à variavel arrCriterios do DTO os criterios informados no DTO. Valida os parametros, se tudo estiver correto, atribui os valores de nome,
     * atributos (do DTO), operadores de atributos (>,<,=, etc.), valores dos atributos
     *  (usados na operação), e operadores lógicos entre as operações (AND,OR, etc.) ao array de critérios.
     * <code> $objNoticiaDTO->adicionarCriterio(array('SinAtivo'),array(InfraDTO::$OPER_IGUAL),array('S'),null,'NoticiaAtiva');</code>
     *
     * @param array $varAtributos
     * @param array $varOperadoresAtributos
     * @param array $varValoresAtributos
     * @param array|string $varOperadoresLogicos
     * @param array|string $strNome
     * @throws InfraException
     */
    public function adicionarCriterio($varAtributos, $varOperadoresAtributos, $varValoresAtributos, $varOperadoresLogicos = null, $strNome = null)
    {

        if (!is_array($varAtributos)) {
            if (InfraString::isBolVazia($varAtributos)) {
                throw new InfraException('Nenhum atributo informado ao adicionar critério.');
            }
            $varAtributos = array($varAtributos);
        }

        if (!is_array($varOperadoresAtributos)) {
            if (InfraString::isBolVazia($varOperadoresAtributos)) {
                throw new InfraException('Nenhum operador de atributo informado ao adicionar critério.');
            }
            $varOperadoresAtributos = array($varOperadoresAtributos);
        }

        if (count($varAtributos) != count($varOperadoresAtributos)) {
            throw new InfraException('Quantidade de operadores de atributos deve ser igual ao número de atributos.');
        }


        if (!is_array($varValoresAtributos)) {
            $varValoresAtributos = array($varValoresAtributos);
        }

        if (count($varAtributos) != count($varValoresAtributos)) {
            throw new InfraException('Quantidade de valores de atributos deve ser igual ao número de atributos.');
        }

        for ($i = 0; $i < count($varOperadoresAtributos); $i++) {
            if ($varOperadoresAtributos[$i] == self::$OPER_IN || $varOperadoresAtributos[$i] == self::$OPER_NOT_IN) {
                if (!is_array($varValoresAtributos[$i])) {
                    throw new InfraException('Valor do atributo [' . $varAtributos[$i] . '] não é um array.');
                }
                if (count($varValoresAtributos[$i]) == 0) {
                    throw new InfraException('Valor do atributo [' . $varAtributos[$i] . '] é um array vazio.');
                }
            }
        }

        if (!is_array($varOperadoresLogicos)) {
            if (InfraString::isBolVazia($varOperadoresLogicos)) {
                if (count($varAtributos) > 1) {
                    throw new InfraException('Nenhum operador lógico informado ao adicionar critério.');
                } else {
                    $varOperadoresLogicos = array();
                }
            } else {
                $varOperadoresLogicos = array($varOperadoresLogicos);
            }
        }

        if ((count($varAtributos) - 1) != count($varOperadoresLogicos)) {
            throw new InfraException('Quantidade de operadores lógicos deve ser igual ao número de atributos menos um.');
        }

        foreach ($varAtributos as $atributo) {
            $this->carregarAtributo($atributo);
        }

        for ($i = 0; $i < count($varValoresAtributos); $i++) {
            if ($varValoresAtributos[$i] === 'null') {
                $varValoresAtributos[$i] = null;
            }
        }

        foreach ($varOperadoresAtributos as $operador) {
            $this->validarOperador($operador);
        }

        foreach ($varOperadoresLogicos as $operador) {
            $this->validarOperadorLogico($operador);
        }

        if ($strNome !== null && trim($strNome) !== '') {
            if (is_array($this->arrCriterios)) {
                foreach ($this->arrCriterios as $criterio) {
                    if ($criterio[self::$POS_CRITERIO_NOME] == $strNome) {
                        throw new InfraException('Já foi adicionado um critério com o nome [' . $strNome . '].');
                    }
                }
            }
        }

        $arr = array();
        $arr[self::$POS_CRITERIO_NOME] = $strNome;
        $arr[self::$POS_CRITERIO_ATRIBUTOS] = $varAtributos;
        $arr[self::$POS_CRITERIO_OPERADORES_ATRIBUTOS] = $varOperadoresAtributos;
        $arr[self::$POS_CRITERIO_VALORES_ATRIBUTOS] = $varValoresAtributos;
        $arr[self::$POS_CRITERIO_OPERADORES_LOGICOS] = $varOperadoresLogicos;

        if (!is_array($this->arrCriterios)) {
            $this->arrCriterios = array();
        }
        $this->arrCriterios[] = $arr;
    }

    /**
     * Cria um array com critérios e os operadores lógicos, e o atribui à variavel arrGruposCriterios do DTO. Os operadores podem ser
     * colocados em um array.
     * <code> $objNoticiaDTO->agruparCriterios(array('Criterio1','Criterio2'), InfraDTO::$OPER_LOGICO_AND);</code>
     * @param array $arrCriterios
     * @param array|string $varOperadoresLogicos
     * @param array|string $strNome
     * @throws InfraException
     */
    public function agruparCriterios($arrCriterios, $varOperadoresLogicos, $strNome = null)
    {

        if (!is_array($arrCriterios)) {
            throw new InfraException('Nenhum critério informado ao agrupar critérios.');
        }

        if (count($arrCriterios) < 2) {
            throw new InfraException('Conjunto de critérios deve conter no mínimo 2 itens para agrupamento.');
        }

        if (!is_array($varOperadoresLogicos)) {
            if (InfraString::isBolVazia($varOperadoresLogicos)) {
                throw new InfraException('Nenhum operador lógico informado ao agrupar critérios.');
            } else {
                $varOperadoresLogicos = array($varOperadoresLogicos);
            }
        }

        if ((count($arrCriterios) - 1) != count($varOperadoresLogicos)) {
            throw new InfraException('Quantidade de operadores lógicos deve ser igual ao número de critérios menos um.');
        }

        foreach ($arrCriterios as $criterio) {
            $this->validarCriterio($criterio);
        }

        foreach ($varOperadoresLogicos as $operador) {
            $this->validarOperadorLogico($operador);
        }

        if (is_array($this->arrGruposCriterios)) {
            foreach ($this->arrGruposCriterios as $grupo) {
                foreach ($arrCriterios as $criterio) {
                    if (in_array($criterio, $grupo[self::$POS_GRUPO_CRITERIO_CRITERIOS])) {
                        throw new InfraException('Critério [' . $criterio . '] já pertence a outro grupo de critérios.');
                    }
                }
            }
        }


        $arr = array();
        $arr[self::$POS_GRUPO_CRITERIO_NOME] = $strNome;
        $arr[self::$POS_GRUPO_CRITERIO_CRITERIOS] = $arrCriterios;
        $arr[self::$POS_GRUPO_CRITERIO_OPERADORES_LOGICOS] = $varOperadoresLogicos;

        if (!is_array($this->arrGruposCriterios)) {
            $this->arrGruposCriterios = array();
        }
        $this->arrGruposCriterios[] = $arr;
    }

    /**
     * Remove os critérios informados para uma consulta, tornando nula a variável de critérios "arrCriterios" e de grupos de critérios "arrGruposCriterios" do DTO
     * @return void
     */
    public function removerCriterios()
    {

        if (is_array($this->arrCriterios)) {
            unset($this->arrCriterios);
        }

        $this->arrCriterios = null;

        if (is_array($this->arrGruposCriterios)) {
            unset($this->arrGruposCriterios);
        }

        $this->arrGruposCriterios = null;

        if (is_array($this->arrFK)) {
            unset($this->arrFK);
            $this->arrFK = null;
        }

    }

    /**
     * Remove o critério informado para uma consulta, retirando da variável de critérios "arrCriterios" validando se pertence a um grupo de critérios em "arrGruposCriterios" do DTO
     * @param $strCriterio
     * @throws InfraException
     */
    public function removerCriterio($strCriterio)
    {

        if (InfraString::isBolVazia($strCriterio)) {
            throw new InfraException('Nome do critério não informado para remoção.');
        }

        if (is_array($this->arrCriterios)) {

            $numCriterios = count($this->arrCriterios);
            for ($i = 0; $i < $numCriterios; $i++) {

                if ($this->arrCriterios[$i][self::$POS_CRITERIO_NOME] == $strCriterio) {

                    if (is_array($this->arrGruposCriterios)) {
                        foreach ($this->arrGruposCriterios as $arrGrupoCriterios) {
                            foreach ($arrGrupoCriterios[self::$POS_GRUPO_CRITERIO_CRITERIOS] as $arrCriterioGrupo) {
                                if ($arrCriterioGrupo[self::$POS_CRITERIO_NOME] == $strCriterio) {
                                    throw new InfraException('Critério [' . $strCriterio . '] faz parte de um grupo de critérios.');
                                }
                            }
                        }
                    }

                    unset($this->arrCriterios[$i]);
                    $this->arrCriterios = array_values($this->arrCriterios);

                    break;
                }
            }
        }

        if ($i == $numCriterios) {
            throw new InfraException('Critério [' . $strCriterio . '] não encontrado.');
        }

        if (is_array($this->arrFK)) {
            unset($this->arrFK);
            $this->arrFK = null;
        }

    }

    /**
     * Remove o grupo de critérios informado para uma consulta, retirando da variável de grupos de critérios "arrGruposCriterios" do DTO
     * @param $strGrupoCriterio
     * @throws InfraException
     */
    public function removerGrupoCriterio($strGrupoCriterio)
    {

        if (InfraString::isBolVazia($strGrupoCriterio)) {
            throw new InfraException('Nome do grupo de critério não informado para remoção.');
        }

        if (is_array($this->arrGruposCriterios)) {

            $numGruposCriterios = count($this->arrGruposCriterios);
            for ($i = 0; $i < $numGruposCriterios; $i++) {

                if ($this->arrGruposCriterios[$i][self::$POS_GRUPO_CRITERIO_NOME] == $strGrupoCriterio) {
                    unset($this->arrGruposCriterios[$i]);
                    $this->arrGruposCriterios = array_values($this->arrGruposCriterios);
                    break;
                }
            }
        }

        if ($i == $numGruposCriterios) {
            throw new InfraException('Grupo de critérios [' . $strGrupoCriterio . '] não encontrado.');
        }
    }

    /** Adiciona um atributo no DTO, que não tenha relação com campo de tabela.
     * @param string $strPrefixo ,
     * @param string $strNome
     * @return void
     */
    protected function adicionarAtributo($strPrefixo, $strNome)
    {
        $this->adicionarAtributoInterno($strPrefixo, $strNome);
    }

    /** Adiciona um atributo no DTO que represente o campo da tabela da entidade em questão.
     * @param string $strPrefixo ,
     * @param string $strNome
     * @param string $strCampoSql
     * @return void
     */
    protected function adicionarAtributoTabela($strPrefixo, $strNome, $strCampoSql)
    {
        $this->adicionarAtributoInterno($strPrefixo, $strNome, $strCampoSql);
    }

    /** Adiciona um atributo no DTO, que represente o campo de uma tabela relacionada à da entidade em questão. Para isso, verifica se
     *  a flag do DTO está marcada para aceitar campos relacionados, ou se o nome do parâmetro é o mesmo da variável strAtribCarga.
     * @param string $strPrefixo ,
     * @param string $strNome
     * @param string $strCampoSqlRelacionado
     * @param string $strTabelaRelacionada
     * @return void
     */
    protected function adicionarAtributoTabelaRelacionada($strPrefixo, $strNome, $strCampoSqlRelacionado, $strTabelaRelacionada)
    {
        if (($this->numFlags & self::$FLAG_RELACIONADOS)) {
            $this->adicionarAtributoInterno($strPrefixo, $strNome, $strCampoSqlRelacionado, $strTabelaRelacionada);
        }
    }

    /** No array de metadados, dentro dos atributos da classe, cria um array (tendo como chave o nome
     * informado do atributo), e preenche esse array como valores do prefixo, o nome, e se for o caso, o campo do sql relacionado e a
     * tabela relacionada.
     * @param string $strPrefixo
     * @param string $strNome
     * @param string $strCampoSql
     * @param string $strTabelaRelacionada
     */
    private function adicionarAtributoInterno($strPrefixo, $strNome, $strCampoSql = null, $strTabelaRelacionada = null)
    {
        if ($this->bolSohConfigurarFKs) {
            return;
        }

        if ($this->bolInicializando) {

            // Valida o Prefixo
            $this->validarTipoPrefixo($strPrefixo);

            // popula o array estático que contem os metadados
            if (!isset(self::$arrMetaDados[$this->strClasse][$strNome])) {
                self::$arrMetaDados[$this->strClasse][$strNome] = array();
                self::$arrMetaDados[$this->strClasse][$strNome][self::$POS_ATRIBUTO_PREFIXO] = $strPrefixo;
                self::$arrMetaDados[$this->strClasse][$strNome][self::$POS_ATRIBUTO_NOME] = $strNome;
                self::$arrMetaDados[$this->strClasse][$strNome][self::$POS_ATRIBUTO_CAMPO_SQL] = $strCampoSql;
                self::$arrMetaDados[$this->strClasse][$strNome][self::$POS_ATRIBUTO_TAB_ORIGEM] = $strTabelaRelacionada;
            }

        }
    }

    /**
     * Verifica se já foi marcada a flag no dígito de montar chaves, e se o atributo informado
     *  já está na array de chaves primárias "arrPK". Verifica se a PK informada existe como atributo.
     * @param $strAtributo
     * @param $numTipoPK
     * @throws InfraException
     */
    protected function configurarPK($strAtributo, $numTipoPK)
    {
        if ($this->bolInicializando) {
            return;
        }

        if ($this->bolSohConfigurarFKs && (!isset($this->arrPK) || !isset($this->arrPK[$strAtributo]))) {
            //Validacoes
            if (!isset($this->arrAtributos[$strAtributo])) {
                $this->carregarAtributo($strAtributo);
            }

            $this->validarTipoPK($numTipoPK);

            //Inicializa Array
            if ($this->arrPK == null) {
                $this->arrPK = array();
            }
            $this->arrPK[$strAtributo] = $numTipoPK;
        }
    }


    /**
     * Identifica os requisitos para verificar se um atributo é adequado para exclusão lógica. Procura se o atributo está definido na
     * clase do no DTO. Verifica se o nome do campo SQL é 'sin_ativo'. Se as validações estiverem ok, marca a flag para indicar a
     * exclusão lógica já está configurada;
     * @param string $strAtributo - já setado para SinAtivo
     * @param string $valorDesativado
     * @throws InfraException
     * @internal param string $numTipoPK
     */
    protected function configurarExclusaoLogica($strAtributo = 'SinAtivo', $valorDesativado = 'N')
    {
        if ($this->numFlags & self::$FLAG_CONFIGUROU_EL) {
            return;
        }

        if ($strAtributo != 'SinAtivo') {
            throw new InfraException('Nome de atributo [' . $strAtributo . '] inválido para exclusão lógica.');
        }

        if (!isset(self::$arrMetaDados[$this->strClasse][$strAtributo])) {
            throw new InfraException('Atributo [' . $strAtributo . '] da Exclusão Lógica não encontrado no DTO. Certifique-se que o atributo foi declarado antes da chamada do método configurarExclusaoLogica.');
        }

        $this->carregarAtributo('SinAtivo');

        if (self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_CAMPO_SQL] != 'sin_ativo') {
            throw new InfraException('Nome de coluna [' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_CAMPO_SQL] . '] inválido para exclusão lógica.');
        }

        $this->numFlags = self::$FLAG_CONFIGUROU_EL | $this->numFlags;

    }

    /**
     * Verifica se a flag está marcada para a aceitar a montagem de chaves (primária e estrangeiras). Se sim, verifica se o atributo já
     * foi adicionado na array de fk's do DTO, se não foi, prossegue. Verifica se o atribudo que servira de fk foi criado, e  a tabela
     * relacionada foi determinada. Se o array de fk's não foi configurado, cria, e passa o nome de cada campo informado como chave, a
     * cada chamada da função no DTO específico. Dentro desse array, de acordo com a chave (a tabela ligada por fk), cria um novo array
     * com o campo relacionado, o tipo de fk e o filtro.
     *
     * @param string $strAtributo
     * @param string $strTabelaRelacionada
     * @param string $strCampoRelacionado
     * @param int|string $numTipoFK =0
     * @param int|string $numFiltroFK =0
     * @throws InfraException
     */

    protected function configurarFK($strAtributo, $strTabelaRelacionada, $strCampoRelacionado, $numTipoFK = 0, $numFiltroFK = 0)
    {
        //
        // Bloco a ser executado se configurarFK for invocado no momento em que estiver inicializando o DTO.
        // O array de metadados que define as FKs será populado nesse momento
        //
        if ($this->bolInicializando) {

            $this->validarTipoFK($numTipoFK);
            $this->validarFiltroFK($numFiltroFK);

            if (!isset(self::$arrMetaDadosFK[$this->strClasse])) {
                self::$arrMetaDadosFK[$this->strClasse] = array();
            }

            if (!isset(self::$arrMetaDadosFK[$this->strClasse][$strTabelaRelacionada])) {
                self::$arrMetaDadosFK[$this->strClasse][$strTabelaRelacionada] = array();
            }

            if (!isset(self::$arrMetaDadosFK[$this->strClasse][$strTabelaRelacionada][$strAtributo])) {
                self::$arrMetaDadosFK[$this->strClasse][$strTabelaRelacionada][$strAtributo] = $strCampoRelacionado;
            }

            return;
        }


        //
        // Bloco a ser executado antes de realizar uma consulta/acesso ao banco de dados, de modo a definir
        // as chaves e resolver dependencias. Precisamos atualizar a situação atual de todos os numTipoFK e numFiltroFk,
        // pois eles podem mudar de opcional para obrigatorio de maneira dinamica
        //
        if ($this->bolSohConfigurarFKs && $this->arrFK !== null) {

            //
            // se encontrar a FK configurada, atualiza o tipo e o filtro
            //
            if (isset($this->arrFK[$strTabelaRelacionada][$strAtributo])) {
                $this->validarTipoFK($numTipoFK);
                $this->validarFiltroFK($numFiltroFK);

                $this->arrFK[$strTabelaRelacionada][$strAtributo][self::$POS_FK_TIPO] = $numTipoFK;
                $this->arrFK[$strTabelaRelacionada][$strAtributo][self::$POS_FK_FILTRO] = $numFiltroFK;
            }
        }
    }

    /**
     * Marca a flag para indicar que o DTO retorna todos os atributos. Procura se na flag se o DTO está indicado como composto, se sim,
     * ela deve indicar na flag que esta se tratando também com os relacionados. Caso o parâmetro $bolRelacionados como true, marca a
     * flag para tratar dos relacionados, senão, desmarca.
     *  <code>$objPessoaDTO->retTodos()</code>
     * @param boolean $bolRelacionados
     * @return void
     */
    public function retTodos($bolRelacionados = false, $bolSomenteAtributosBanco = false)
    {

        $this->numFlags = self::$FLAG_RET_TODOS | $this->numFlags;

        //COMPATIBILIDADE:
        //Se criou o DTO como composto sempre considerar os relacionados
        if ($this->numFlags & self::$FLAG_COMPOSTO) {
            $this->numFlags = self::$FLAG_RELACIONADOS | $this->numFlags;
        } else {
            if ($bolRelacionados) {
                $this->numFlags = self::$FLAG_RELACIONADOS | $this->numFlags;
            } else {
                $this->numFlags = ~self::$FLAG_RELACIONADOS & $this->numFlags;
            }
        }

        //Carrega todos os atributos do DTO que ainda não foram carregados
        $atributos = array_keys(self::$arrMetaDados[$this->strClasse]);
        foreach ($atributos as $atributo) {

            // se for atributo relacionado e não pudermos carregar os relacionados, passamos para o proximo
            if (!($this->numFlags & self::$FLAG_RELACIONADOS) && isset(self::$arrMetaDados[$this->strClasse][$atributo][self::$POS_ATRIBUTO_TAB_ORIGEM])) {
                continue;
            }

            $this->carregarAtributo($atributo);
        }

        //Configura flag para os que já foram carregados
        if (is_array($this->arrAtributos)) {
            foreach ($this->arrAtributos as &$atributo) {
                if (!$bolSomenteAtributosBanco || $atributo[self::$POS_ATRIBUTO_CAMPO_SQL]!==null) {
                    $atributo[self::$POS_ATRIBUTO_FLAGS] = (self::$FLAG_RET | $atributo[self::$POS_ATRIBUTO_FLAGS]);
                }
            }
        }
    }

    /**
     * Desmarca na flag do DTO o sinal de retornar todos. Procura o array de atributos do DTO, e
     * desmarca na flag de cada um a opção de retornar.
     * <code> $objPessoaDTO->unRetTodos()<code>
     * @return void
     */
    public function unRetTodos($bolSomenteAtributosBanco = false)
    {

        $this->numFlags = ~self::$FLAG_RET_TODOS & $this->numFlags;

        if (is_array($this->arrAtributos)) {
            foreach ($this->arrAtributos as &$atributo) {
                if (!$bolSomenteAtributosBanco || $atributo[self::$POS_ATRIBUTO_CAMPO_SQL]!==null) {
                    $atributo[self::$POS_ATRIBUTO_FLAGS] = (~self::$FLAG_RET & $atributo[self::$POS_ATRIBUTO_FLAGS]);
                }
            }
        }

        if (is_array($this->arrFK)) {
            unset($this->arrFK);
            $this->arrFK = null;
        }
    }

    /**
     * Verifica se a flag está setada na posição de retornar todos.
     * @return boolean
     */
    public function isBolRetTodos()
    {
        return $this->numFlags & self::$FLAG_RET_TODOS;
    }

    /**
     * Para cada um dos itens do array de atributos, desmarca na flag a informação de que ele foi declarado.
     * @return void
     */
    public function unSetTodos()
    {
        if (is_array($this->arrAtributos)) {
            foreach ($this->arrAtributos as &$atributo) {
                $atributo[self::$POS_ATRIBUTO_FLAGS] = (~self::$FLAG_SET & $atributo[self::$POS_ATRIBUTO_FLAGS]);
            }
        }

        if (is_array($this->arrFK)) {
            unset($this->arrFK);
            $this->arrFK = null;
        }
    }

    /**
     * Para todos os elementos do array de atributos, retira a marcação de ordenar da flag. Torna a variável de ordenação nula.
     * @return void
     */
    public function unOrdTodos($bolSomenteAtributosBanco = false)
    {
        if (is_array($this->arrAtributos)) {
            foreach ($this->arrAtributos as &$atributo) {
                if (!$bolSomenteAtributosBanco || $atributo[self::$POS_ATRIBUTO_CAMPO_SQL]!==null) {
                    $atributo[self::$POS_ATRIBUTO_FLAGS] = (~self::$FLAG_ORD & $atributo[self::$POS_ATRIBUTO_FLAGS]);
                }
            }
        }

        if ($this->arrOrd != null) {
            unset($this->arrOrd);
            $this->arrOrd = null;
        }

        if (is_array($this->arrFK)) {
            unset($this->arrFK);
            $this->arrFK = null;
        }
    }

    /**
     * O php permite a sobrecarga de métodos, isto é, permite que métodos não declarados em uma classe possam ser tratados da mesma forma
     * que os declarados. Esse método __call, próprio do php, é o que permite esse tratamento de todas as chamadas de métodos. O primeiro
     * parâmetro '$func', receberá o nome do método, e essa string será decomposta para que, de acordo com os padrões da Infra, sejam
     * identificados o tipo de ação, ou as propriedades do objeto que se deseja obter eu determinar. O segundo '$args ' receberá os
     * parâmetros passados à função. É avaliado se o método inicia com os seguintes prefixos: 'setOrd', 'getOrd',
     * 'get','set','unSet','isSet', 'ret','unRet','isRet','unOrd','isOrd'. Em alguns casos, nos quais há alguma operação sobre um
     * atributo do DTO, busca a identificação do prefixo ('NUM','STR','ARR','DTA','DBL' etc.), e em seguida, o nome do atributo
     * específico do DTO em questão como ('NomePessoa','PessoaIngresso','TipoBeneficiário' etc.). Verifica, caso a caso, se o método aceita
     * argumentos, e se sim, se isso é feito corretamente. Se as validações estiverem corretas, ou seta as flags do atributo na posição da
     * chave das flags do atributo de acordo com o tipo de solicitação, ou retorna um booleano se o objetivo for verificar um determinado
     * estado de um atributo ou do próprio objeto.
     * @param string $func
     * @param array $args
     * @return bool|void
     * @throws InfraException
     */
    public function __call($func, $args)
    {

        try {

            $strFunc6 = substr($func, 0, 6);

            if ($strFunc6 == 'setOrd') {
                $strPrefixo = substr($func, 6, 3);
                $strAtributo = substr($func, 9);

                if (count($args) == 0) {
                    throw new InfraException('Método [' . $func . '] chamado sem parâmetro.');
                }

                if (count($args) > 1) {
                    throw new InfraException('Método [' . $func . '] chamado com mais de um parâmetro.');
                }

                $this->carregarValidarPrefixoAtributo($strPrefixo, $strAtributo);

                if (self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] == self::$PREFIXO_ARR ||
                    self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] == self::$PREFIXO_OBJ ||
                    self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] == self::$PREFIXO_BIN
                ) {
                    throw new InfraException('Tipo do atributo [' . $strAtributo . '] não permite uso para ordenação.');
                }


                $this->validarTipoOrdenacao($args[0]);

                if ($this->arrOrd == null) {
                    $this->arrOrd = array();
                }

                if (self::$FLAG_ORD & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]) {
                    $arrTemp = array();
                    foreach ($this->arrOrd as $atributoOrd) {
                        if ($atributoOrd[0] != $strAtributo) {
                            $arrTemp[] = $atributoOrd;
                        }
                    }
                    $this->arrOrd = $arrTemp;
                }
                $this->arrOrd[] = array($strAtributo, $args[0]);
                $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS] = (self::$FLAG_ORD | $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

            } else if ($strFunc6 == 'getOrd') {
                $strPrefixo = substr($func, 6, 3);
                $strAtributo = substr($func, 9);

                if (count($args) != 0) {
                    throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                }

                $this->validarPrefixo($strPrefixo, $strAtributo);

                if (!isset($this->arrAtributos[$strAtributo])) {
                    throw new InfraException('Atributo [' . $strAtributo . '] não está configurado para ordenação.');
                }

                if ((self::$FLAG_ORD & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS])) {
                    foreach ($this->arrOrd as $atributoOrd) {
                        if ($atributoOrd[0] == $strAtributo) {
                            return $atributoOrd[1];
                        }
                    }

                    throw new InfraException('Atributo [' . $strAtributo . '] não encontrado no conjunto para ordenação.');
                }

                throw new InfraException('Atributo [' . $strAtributo . '] não está configurado para ordenação.');

            } else {

                $strFunc3 = substr($strFunc6, 0, 3);

                if ($strFunc3 == 'get') {

                    $strPrefixo = substr($func, 3, 3);
                    $strAtributo = substr($func, 6);

                    if (count($args) != 0) {
                        throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                    }

                    $this->carregarValidarPrefixoAtributo($strPrefixo, $strAtributo);

                    if (!(self::$FLAG_SET & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS])) {
                        throw new InfraException('Atributo [' . $strAtributo . '] não recebeu valor.');
                    }
                    return $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_VALOR];

                } else if ($strFunc3 == 'set') {
                    $strPrefixo = substr($func, 3, 3);
                    $strAtributo = substr($func, 6);

                    if (count($args) == 0) {
                        throw new InfraException('Método [' . $func . '] chamado sem parâmetro.');
                    }

                    $this->carregarValidarPrefixoAtributo($strPrefixo, $strAtributo);

                    //Assume 'null' string igual null
                    if ($args[0] === 'null') {
                        $args[0] = null;
                    }

                    if (is_object($args[0]) && $args[0] instanceof InfraAtributoDTO) {
                        if ($args[0]->getStrNomeClasse() != get_class($this)) {
                            $arrAtributo = $args[0]->getArrAtributo();
                            throw new InfraException('O atributo [' . $arrAtributo[self::$POS_ATRIBUTO_NOME] . '] não pertence a classe [' . get_class($this) . '].');
                        }
                    }

                    $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_VALOR] = $args[0];

                    if (count($args) == 1) {
                        $this->setOperador($this->arrAtributos[$strAtributo], self::$OPER_IGUAL);
                    } else if (count($args) == 2) {
                        $this->setOperador($this->arrAtributos[$strAtributo], $args[1]);
                    } else if (count($args) == 3) {
                        $this->setOperador($this->arrAtributos[$strAtributo], $args[1], $args[2]);
                    } else {
                        throw new InfraException('Número de parâmetros inválido para o método [' . $func . '].');
                    }

                    $strOperador = $this->getOperador($this->arrAtributos[$strAtributo]);

                    if ($strOperador == self::$OPER_IN || $strOperador == self::$OPER_NOT_IN) {
                        if (!is_array($this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_VALOR])) {
                            throw new InfraException('Parâmetro do método [' . $func . '] não é um array.');
                        }
                        if (count($this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_VALOR]) == 0) {
                            throw new InfraException('Parâmetro do método [' . $func . '] é um array vazio.');
                        }
                    } else if ($strOperador == self::$OPER_LIKE || $strOperador == self::$OPER_NOT_LIKE) {
                        if (count($args) == 3 && !is_bool($args[2])) {
                            throw new InfraException('Parâmetro do método [' . $func . '] não é um valor booleano.');
                        }
                    }

                    $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS] = (self::$FLAG_SET | $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                } else if ($strFunc3 == 'ret') {
                    $strPrefixo = substr($func, 3, 3);
                    $strAtributo = substr($func, 6);

                    if (count($args) != 0) {
                        throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                    }

                    $this->carregarValidarPrefixoAtributo($strPrefixo, $strAtributo);

                    $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS] = (self::$FLAG_RET | $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                } else {

                    $strFunc5 = substr($strFunc6, 0, 5);

                    if ($strFunc5 == 'unSet') {

                        $strPrefixo = substr($func, 5, 3);
                        $strAtributo = substr($func, 8);

                        if (count($args) != 0) {
                            throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                        }

                        $this->validarPrefixo($strPrefixo, $strAtributo);

                        if (!isset($this->arrAtributos[$strAtributo])) {
                            return;
                        }

                        $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS] = (~self::$FLAG_SET & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                    } else if ($strFunc5 == 'isSet') {
                        $strPrefixo = substr($func, 5, 3);
                        $strAtributo = substr($func, 8);

                        if (count($args) != 0) {
                            throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                        }

                        $this->validarPrefixo($strPrefixo, $strAtributo);

                        if (!isset($this->arrAtributos[$strAtributo])) {
                            return false;
                        }

                        return (boolean)(self::$FLAG_SET & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                    } else if ($strFunc5 == 'unRet') {
                        $strPrefixo = substr($func, 5, 3);
                        $strAtributo = substr($func, 8);

                        if (count($args) != 0) {
                            throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                        }

                        $this->validarPrefixo($strPrefixo, $strAtributo);

                        if (!isset($this->arrAtributos[$strAtributo])) {
                            return;
                        }

                        $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS] = (~self::$FLAG_RET & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                    } else if ($strFunc5 == 'isRet') {
                        $strPrefixo = substr($func, 5, 3);
                        $strAtributo = substr($func, 8);

                        if (count($args) != 0) {
                            throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                        }

                        $this->validarPrefixo($strPrefixo, $strAtributo);

                        if (!isset($this->arrAtributos[$strAtributo])) {
                            return false;
                        }

                        return (boolean)(self::$FLAG_RET & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                    } else if ($strFunc5 == 'unOrd') {
                        $strPrefixo = substr($func, 5, 3);
                        $strAtributo = substr($func, 8);

                        if (count($args) != 0) {
                            throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                        }

                        $this->validarPrefixo($strPrefixo, $strAtributo);

                        if (!isset($this->arrAtributos[$strAtributo])) {
                            return;
                        }

                        if (self::$FLAG_ORD & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]) {
                            $arrTemp = array();
                            foreach ($this->arrOrd as $atributoOrd) {
                                if ($atributoOrd[0] != $strAtributo) {
                                    $arrTemp[] = $atributoOrd;
                                }
                            }
                            $this->arrOrd = $arrTemp;
                        }

                        $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS] = (~self::$FLAG_ORD & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                    } else if ($strFunc5 == 'isOrd') {
                        $strPrefixo = substr($func, 5, 3);
                        $strAtributo = substr($func, 8);

                        if (count($args) != 0) {
                            throw new InfraException('Método [' . $func . '] não aceita parâmetros.');
                        }

                        $this->validarPrefixo($strPrefixo, $strAtributo);

                        if (!isset($this->arrAtributos[$strAtributo])) {
                            return false;
                        }

                        return (boolean)(self::$FLAG_ORD & $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS]);

                    } else {
                        throw new InfraException('Método [' . $func . '] não encontrado no objeto.');
                    }
                }
            }

        } catch (Exception $e) {
            throw new InfraException('Erro processando operação [' . $func . '].', $e);
        }
        return;
    }

    /**
     * Imprime todos os itens do array de atributos do DTO na página.
     * @return void
     */
    public function debug()
    {
        foreach ($this->arrAtributos as $a) {
            print_r($a);
            echo '<br />----------------------------<br />';
        }
    }

    /**
     * A cada tratamento de métodos, a função __call pode chamar a função carregarAtributo. Esta chama o método montar(), que é declarado
     * na classe do DTO específico. montar() chama os métodos da InfraDTO que adicionam atributos de diferentes tipos, e cada um desses
     * chama "adicionarAtributoInterno". Este vai então, tratar somente daqueles parâmetros que são os passados no uso do objeto DTO (a
     * cada chamada o atributo é carregado na variavel $this->strAtribCarga), para não adicionar dados desnecessários na instância do
     * DTO, à excessão de quando for para retornar todos.
     * @param string $strAtributo
     * @throws InfraException
     */
    private function carregarAtributo($strAtributo)
    {
        if (!isset($this->arrAtributos[$strAtributo])) {

            $definicao = self::$arrMetaDados[$this->strClasse][$strAtributo];

            if (!isset($definicao)) {
                throw new InfraException('Atributo [' . $strAtributo . '] não existe no DTO.');
            }

            if (!isset($this->arrAtributos)) {
                $this->arrAtributos = array();
            }

            if (!isset($this->arrAtributos[$strAtributo])) {
                $this->arrAtributos[$strAtributo] = array();
                $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_VALOR] = null;
                $this->arrAtributos[$strAtributo][self::$POS_ATRIBUTO_FLAGS] = 0;
            }

        }
    }

    /**
     * Verifica se o prefixo informado na chamada do método do DTO é o mesmo guardado no array de metadados.
     * @param string $strPrefixo
     * @param string $strAtributo
     * @throws InfraException
     */
    protected function validarPrefixo($strPrefixo, $strAtributo)
    {
        if (self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] != $strPrefixo) {
            throw new InfraException('Prefixo [' . $strPrefixo . '] não aplicável ao atributo ' . $strAtributo . '.');
        }
    }

    /**
     * Unifica carregarAtributo e validarPrefixo
     * @param $strPrefixo
     * @param $strAtributo
     * @throws InfraException
     */
    protected function carregarValidarPrefixoAtributo($strPrefixo, $strAtributo)
    {
        $ret = false;

        if (!isset($this->arrAtributos[$strAtributo])) {
            $this->carregarAtributo($strAtributo);
            $ret = true;
        }

        if (self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] != $strPrefixo) {
            throw new InfraException('Prefixo [' . $strPrefixo . '] não aplicável ao atributo ' . $strAtributo . '.');
        }

        return $ret;
    }


    /**
     * Verifica se o tipo de PK passado como argumento é válido.
     * @param integer $numTipoPK
     * @throws InfraException
     */
    protected function validarTipoPK($numTipoPK)
    {
        if ($numTipoPK != self::$TIPO_PK_INFORMADO &&
            $numTipoPK != self::$TIPO_PK_SEQUENCIAL &&
            $numTipoPK != self::$TIPO_PK_NATIVA
        ) {
            throw new InfraException('Tipo de PK [' . $numTipoPK . '] inválida.');
        }
    }

    /**
     * Verifica se o tipo de FK passado como argumento é válido.
     * @param integer $numTipoFK
     * @throws InfraException
     */
    protected function validarTipoFK($numTipoFK)
    {
        if ($numTipoFK != self::$TIPO_FK_OBRIGATORIA && $numTipoFK != self::$TIPO_FK_OPCIONAL) {
            throw new InfraException('Tipo de FK [' . $numTipoFK . '] inválida.');
        }
    }

    /**
     * Avalia se o prefixo passado no DTO está dentro dos tipos padrão (NUM, DIN, DBL, STR, DTA, DTH, BOL, ARR, OBJ, BIN).
     * Se não estiver, avisa que é inválido.
     * @param string $strPrefixo
     * @throws InfraException
     */
    protected function validarTipoPrefixo($strPrefixo)
    {
        if ($strPrefixo != self::$PREFIXO_NUM &&
            $strPrefixo != self::$PREFIXO_DIN &&
            $strPrefixo != self::$PREFIXO_DBL &&
            $strPrefixo != self::$PREFIXO_STR &&
            $strPrefixo != self::$PREFIXO_DTA &&
            $strPrefixo != self::$PREFIXO_DTH &&
            $strPrefixo != self::$PREFIXO_BOL &&
            $strPrefixo != self::$PREFIXO_ARR &&
            $strPrefixo != self::$PREFIXO_OBJ &&
            $strPrefixo != self::$PREFIXO_BIN
        ) {
            throw new InfraException('Prefixo [' . $strPrefixo . '] inválido.');
        }
    }

    /**
     * Avalia se o operador passado no critério do DTO está dentro dos tipos padrão ($OPER_IGUAL, $OPER_DIFERENTE, $OPER_MAIOR etc.).
     * Se não estiver, avisa que é inválido.
     * @param string $strOperador
     * @throws InfraException
     */
    protected function validarOperador($strOperador)
    {
        if ($strOperador != self::$OPER_IGUAL &&
            $strOperador != self::$OPER_DIFERENTE &&
            $strOperador != self::$OPER_LIKE &&
            $strOperador != self::$OPER_NOT_LIKE &&
            $strOperador != self::$OPER_MAIOR &&
            $strOperador != self::$OPER_MENOR &&
            $strOperador != self::$OPER_MAIOR_IGUAL &&
            $strOperador != self::$OPER_MENOR_IGUAL &&
            $strOperador != self::$OPER_IN &&
            $strOperador != self::$OPER_NOT_IN &&
            $strOperador != self::$OPER_BIT_AND
        ) {
            throw new InfraException('Operador [' . $strOperador . '] inválido.');
        }
    }

    /**
     * Avalia se o tipo de ordenação passado no critério do DTO está dentro dos tipos padrão ($TIPO_ORDENACAO_ASC ou
     *  $TIPO_ORDENACAO_DESC). Se não estiver, avisa que é inválido.
     * @param string $strTipoOrdenacao
     * @throws InfraException
     */
    protected function validarTipoOrdenacao($strTipoOrdenacao)
    {
        if (strtoupper($strTipoOrdenacao) != self::$TIPO_ORDENACAO_ASC &&
            strtoupper($strTipoOrdenacao) != self::$TIPO_ORDENACAO_DESC
        ) {
            throw new InfraException('Tipo de ordenação [' . $strTipoOrdenacao . '] inválida.');
        }
    }

    /**
     * Avalia se o tipo de operador lógico passado no critério do DTO está dentro dos tipos padrão ($OPER_LOGICO_AND ou
     *  $OPER_LOGICO_OR). Se não estiver, avisa que é inválido.
     * @param string $strOperador
     * @throws InfraException
     */
    protected function validarOperadorLogico($strOperador)
    {
        if (strtoupper($strOperador) != self::$OPER_LOGICO_AND &&
            strtoupper($strOperador) != self::$OPER_LOGICO_OR
        ) {
            throw new InfraException('Operador lógico [' . $strOperador . '] inválido.');
        }
    }

    /**
     * Percorre o array de critérios do DTO, e confere se o nome informado como parâmetro está nesse array.
     * Se não estiver, avisa que o critério não foi encontrado.
     * @param string $strCriterio
     * @throws InfraException
     */
    public function validarCriterio($strCriterio)
    {
        if (!$this->verificarCriterio($strCriterio)) {
            throw new InfraException('Critério [' . $strCriterio . '] não encontrado.');
        }
    }

    public function verificarCriterio($strCriterio)
    {
        if (is_array($this->arrCriterios)) {
            foreach ($this->arrCriterios as $c) {
                if ($c[self::$POS_CRITERIO_NOME] == $strCriterio) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function validarFiltroFK($numFiltroFK)
    {
        if ($numFiltroFK != self::$FILTRO_FK_ON && $numFiltroFK != self::$FILTRO_FK_WHERE) {
            throw new InfraException('Tipo de filtro para os registros [' . $numFiltroFK . '] inválido.');
        }
    }

    /**
     * Este método é uma alternativa à técnica de compor o nome do método na forma $objNoticiaDTO->setOrdStrAutor(InfraDTO::$TIPO_ORDENACAO_DESC)).
     * Para obter o valor de um atributo, passa-se o nome do mesmo sem o prefixo (isto é, "Nome", ao invés de
     * "StrNome") que será, então, obtido pelo array de metadados.
     * <code>$objNoticiaDTO->setOrd("Autor,"InfraDTO::$TIPO_ORDENACAO_DESC))</code>
     * @param string $strAtributo
     * @param $strTipoOrdenacao
     * @throws InfraException
     */
    public function setOrd($strAtributo, $strTipoOrdenacao)
    {
        $this->carregarAtributo($strAtributo);
        call_user_func(array($this, 'setOrd' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] . $strAtributo), $strTipoOrdenacao);
    }

    /**
     * Este método é uma alternativa à técnica de compor o nome do método na forma $objPessoaDTO->getStrNome(). Para obter o valor de um
     * atributo, passa-se o nome do mesmo sem o prefixo (isto é, "Nome", ao invés de "StrNome") que será, então, obtido pelo array de metadados.
     * <code>$objPessoaDTO->get('Nome'))</code>
     * @param string $strAtributo
     * @return mixed
     */
    public function get($strAtributo)
    {
        $this->carregarAtributo($strAtributo);
        return call_user_func(array($this, 'get' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] . $strAtributo));
    }

    /**
     * Este método é uma alternativa à técnica de compor o nome do método na forma $objPessoaDTO->setStrNome('Maria'). Para determinar o valor de
     * um atributo, passa-se o nome do mesmo sem o prefixo (isto é, "Nome", ao invés de "StrNome") que será, então, obtido pelo array de metadados, e o valor
     * do atributo. É opcional informar o operador do critério de busca.
     * <code>$objPessoaDTO->set('Nome','Maria')</code>
     *  quando o objetivo for consultar.
     * @param string $strAtributo
     * @param string $strValor
     * @param string $strOperador - por padrão é "=".
     * @return void
     */
    public function set($strAtributo, $strValor, $strOperador = '=')
    {
        $this->carregarAtributo($strAtributo);
        call_user_func(array($this, 'set' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] . $strAtributo), $strValor, $strOperador);
    }

    /**
     * Este método é uma alternativa à técnica de compor o nome do método na forma $objPessoaDTO->retStrNome(). Para obter o valor de um
     * atributo, passa-se o nome do mesmo sem o prefixo, isto é, "Nome", ao invés de "StrNome" , que  é obtido pelo array de metadados.
     * <code> $objPessoaDTO->ret('Nome')</code>
     * @param string $strAtributo
     * @return mixed
     */
    public function ret($strAtributo)
    {
        $this->carregarAtributo($strAtributo);
        return call_user_func(array($this, 'ret' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] . $strAtributo));
    }

    public function isRet($strAtributo)
    {
        $this->carregarAtributo($strAtributo);
        return call_user_func(array($this, 'isRet' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] . $strAtributo));
    }

    public function isSetAtributo($strAtributo)
    {
        $this->carregarAtributo($strAtributo);
        return call_user_func(array($this, 'isSet' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] . $strAtributo));
    }

    public function unSetAtributo($strAtributo)
    {
        $this->carregarAtributo($strAtributo);
        return call_user_func(array($this, 'unSet' . self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO] . $strAtributo));
    }

    /**
     * Carrega o atributo para o objeto DTO e busca o prefixo (Num, Str, Dbl, Bol, etc) no array de metadados.
     * @param string $strAtributo
     * @return string
     */
    public function getPrefixo($strAtributo)
    {
        $this->carregarAtributo($strAtributo);
        return self::$arrMetaDados[$this->strClasse][$strAtributo][self::$POS_ATRIBUTO_PREFIXO];
    }

    /**
     * Marca a flag do DTO para listar os registros como DISTINCT, no caso de "true", ou desmarca se estiver marcado, com o opção
     * "false".
     * <code> $objPessoaDTO->setDistinct(true)</code>
     * @param boolean $bolDistinct
     * @return void
     */
    public function setDistinct($bolDistinct)
    {
        if ($bolDistinct) {
            $this->numFlags = (self::$FLAG_DISTINCT | $this->numFlags);
        } else {
            $this->numFlags = (~self::$FLAG_DISTINCT & $this->numFlags);
        }
    }

    /**
     * Busca se o DTO está marcado para retornar registros no modo DISTINCT
     * @return boolean
     */
    public function getDistinct()
    {
        return (self::$FLAG_DISTINCT & $this->numFlags);
    }

    /**
     * Marca a flag do DTO para listar somente os registros que possuem atributo sin_ativo = "S", ou desmarca se estiver marcado, com o
     * opção "false", listando, nesse caso, tanto ativos quanto inativos.
     * <code>$objPessoaDTO->setDistinct(true)</code>
     * @param boolean $bolExclusaoLogica
     * @return void
     */
    public function setBolExclusaoLogica($bolExclusaoLogica)
    {
        if ($bolExclusaoLogica) {
            $this->numFlags = (self::$FLAG_EL | $this->numFlags);
        } else {
            $this->numFlags = (~self::$FLAG_EL & $this->numFlags);
        }
    }

    /**
     * Diz se um DTO está marcado como de exclusão lógica.
     * @return boolean
     */
    public function isBolExclusaoLogica()
    {
        return (self::$FLAG_EL & $this->numFlags);
    }

    /**
     * Diz se a exclusão lógica de um DTO já foi configurada.
     * @return boolean
     */
    public function isBolConfigurouExclusaoLogica()
    {
        return $this->numFlags & self::$FLAG_CONFIGUROU_EL;
    }

    /**
     * Atribui à propriedade objInfraException do DTO um objeto InfraException.
     * @param $objInfraException
     */
    public function setObjInfraException($objInfraException)
    {
        $this->objInfraException = $objInfraException;
    }

    /**
     * Obtém o objeto InfraException definido na propriedade objInfraException do DTO.
     * @return object
     */
    public function getObjInfraException()
    {
        return $this->objInfraException;
    }

    /**
     * Informa ao objeto DTO o limite de registros a serem recuperados em uma consulta.
     * <code> $objPessoaDTO->setNumMaxRegistrosRetorno(5)</code>
     * @param integer $numMax
     * @return void
     */
    public function setNumMaxRegistrosRetorno($numMax)
    {
        $this->numMaxRegRet = $numMax;
    }

    /**
     * Obtém o limite de registros a serem recuperados pelo DTO.
     * <code>$objPessoaDTO->getNumMaxRegistrosRetorno()</code>
     * @return integer
     */
    public function getNumMaxRegistrosRetorno()
    {
        return $this->numMaxRegRet;
    }

    /**
     * Desmarca no DTO a opção de retornar registros dentro de algum limite.
     * <code>$objPessoaDTO->unSetNumMaxRegistrosRetorno()</code>
     * @return void
     */
    public function unSetNumMaxRegistrosRetorno()
    {
        $this->numMaxRegRet = null;
    }

    /**
     * Avalia se o DTO foi configurado para ter limite nos registros retornados.
     * @return boolean
     */
    public function isSetNumMaxRegistrosRetorno()
    {
        return ($this->numMaxRegRet !== null);
    }

    /**
     * Se o array de paginação do DTO não está declarado, cria e define o valor do array
     * na chave $POS_PAG_ATUAL como o informado no parâmetro do método.
     * @param $numPagAtual
     */
    public function setNumPaginaAtual($numPagAtual)
    {
        if ($this->arrPaginacao == null) {
            $this->arrPaginacao = array();
        }
        $this->arrPaginacao[self::$POS_PAG_ATUAL] = $numPagAtual;
    }

    /**
     * Obtém, do array de paginação do DTO (se foi declarado), o número da página atual.
     * @return integer|null
     * */
    public function getNumPaginaAtual()
    {
        return isset($this->arrPaginacao[self::$POS_PAG_ATUAL]) ? $this->arrPaginacao[self::$POS_PAG_ATUAL] : null;
    }

    /**
     * Informa o total de registros por página ao array de paginação.
     * @param $numTotalReg
     */
    public function setNumTotalRegistros($numTotalReg)
    {
        if ($this->arrPaginacao == null) {
            $this->arrPaginacao = array();
        }
        $this->arrPaginacao[self::$POS_PAG_TOTAL_REG] = $numTotalReg;
    }

    /**
     * Obtém o total de registros por página ao array de paginação, se este foi definido, senão retorna null.
     * @return integer|null
     * */
    public function getNumTotalRegistros()
    {
        return isset($this->arrPaginacao[self::$POS_PAG_TOTAL_REG]) ? $this->arrPaginacao[self::$POS_PAG_TOTAL_REG] : null;
    }

    /**
     * Informa o total de registros da página atual ao array de paginação.
     * @param $numRegPagAtual
     */
    public function setNumRegistrosPaginaAtual($numRegPagAtual)
    {
        if ($this->arrPaginacao == null) {
            $this->arrPaginacao = array();
        }
        $this->arrPaginacao[self::$POS_PAG_TOTAL_PAG] = $numRegPagAtual;
    }

    /**
     * Informa o total de registros da página atual ao array de paginação.
     * @return integer|null
     * */
    public function getNumRegistrosPaginaAtual()
    {
        return isset($this->arrPaginacao[self::$POS_PAG_TOTAL_PAG]) ? $this->arrPaginacao[self::$POS_PAG_TOTAL_PAG] : null;
    }

    /**
     * Obtém a flag atual de um atributo, desmarca todos os valores configurados, e marca aquele que for informado.
     * @param $atributo
     * @param $operador
     * @param null $bolCaseInsensitive
     * @throws InfraException
     */
    public function setOperador(&$atributo, $operador, $bolCaseInsensitive = null)
    {

        $this->validarOperador($operador);

        $flags = $atributo[self::$POS_ATRIBUTO_FLAGS];


        $flags = (~self::$FLAG_IGUAL & $flags);
        $flags = (~self::$FLAG_LIKE & $flags);
        $flags = (~self::$FLAG_NOT_LIKE & $flags);
        $flags = (~self::$FLAG_SET_CASE_INSENSITIVE & $flags);
        $flags = (~self::$FLAG_VALOR_CASE_INSENSITIVE & $flags);
        $flags = (~self::$FLAG_DIFERENTE & $flags);
        $flags = (~self::$FLAG_IN & $flags);
        $flags = (~self::$FLAG_NOT_IN & $flags);
        $flags = (~self::$FLAG_MAIOR & $flags);
        $flags = (~self::$FLAG_MENOR & $flags);
        $flags = (~self::$FLAG_MAIOR_IGUAL & $flags);
        $flags = (~self::$FLAG_MENOR_IGUAL & $flags);
        $flags = (~self::$FLAG_BIT_AND & $flags);


        if ($operador == self::$OPER_IGUAL) {
            $flags = self::$FLAG_IGUAL | $flags;
        } else if ($operador == self::$OPER_LIKE) {
            $flags = self::$FLAG_LIKE | $flags;
        } else if ($operador == self::$OPER_NOT_LIKE) {
            $flags = self::$FLAG_NOT_LIKE | $flags;
        } else if ($operador == self::$OPER_DIFERENTE) {
            $flags = self::$FLAG_DIFERENTE | $flags;
        } else if ($operador == self::$OPER_IN) {
            $flags = self::$FLAG_IN | $flags;
        } else if ($operador == self::$OPER_NOT_IN) {
            $flags = self::$FLAG_NOT_IN | $flags;
        } else if ($operador == self::$OPER_MAIOR) {
            $flags = self::$FLAG_MAIOR | $flags;
        } else if ($operador == self::$OPER_MENOR) {
            $flags = self::$FLAG_MENOR | $flags;
        } else if ($operador == self::$OPER_MAIOR_IGUAL) {
            $flags = self::$FLAG_MAIOR_IGUAL | $flags;
        } else if ($operador == self::$OPER_MENOR_IGUAL) {
            $flags = self::$FLAG_MENOR_IGUAL | $flags;
        }else if ($operador == self::$OPER_BIT_AND) {
          $flags = self::$FLAG_BIT_AND | $flags;
        }

        if (is_bool($bolCaseInsensitive)) {

            $flags = self::$FLAG_SET_CASE_INSENSITIVE | $flags;

            if ($bolCaseInsensitive) {
                $flags = self::$FLAG_VALOR_CASE_INSENSITIVE | $flags;
            }
        }

        $atributo[self::$POS_ATRIBUTO_FLAGS] = $flags;
    }

    /**
     * Obtém a operador de comparação um atributo ('<', '>', 'LIKE', '=', etc.).
     * @param $varAtributo
     * @return int
     * @throws InfraException
     */
    public function getOperador($varAtributo)
    {

        if (is_array($varAtributo)) {
            $flags = $varAtributo[self::$POS_ATRIBUTO_FLAGS];
        } else {
            $flags = $this->arrAtributos[$varAtributo][self::$POS_ATRIBUTO_FLAGS];
        }

        if ($flags & self::$FLAG_IGUAL) {
            return self::$OPER_IGUAL;
        } else if ($flags & self::$FLAG_LIKE) {
            return self::$OPER_LIKE;
        } else if ($flags & self::$FLAG_NOT_LIKE) {
            return self::$OPER_NOT_LIKE;
        } else if ($flags & self::$FLAG_DIFERENTE) {
            return self::$OPER_DIFERENTE;
        } else if ($flags & self::$FLAG_IN) {
            return self::$OPER_IN;
        } else if ($flags & self::$FLAG_NOT_IN) {
            return self::$OPER_NOT_IN;
        } else if ($flags & self::$FLAG_MAIOR) {
            return self::$OPER_MAIOR;
        } else if ($flags & self::$FLAG_MENOR) {
            return self::$OPER_MENOR;
        } else if ($flags & self::$FLAG_MAIOR_IGUAL) {
            return self::$OPER_MAIOR_IGUAL;
        } else if ($flags & self::$FLAG_MENOR_IGUAL) {
            return self::$OPER_MENOR_IGUAL;
        }else if ($flags & self::$FLAG_BIT_AND) {
          return self::$OPER_BIT_AND;
        }

        throw new InfraException('Operador não configurado.');
    }

    /**
     * Verifica se a flag de uma tributo está marcada como CASE INSENSITIVE
     * @param $atributo
     * @return int
     */
    public function isBolCaseInsensitive($atributo)
    {

        $flags = $atributo[self::$POS_ATRIBUTO_FLAGS];

        if (!($flags & self::$FLAG_SET_CASE_INSENSITIVE)) {
            return null;
        }

        return ($flags & self::$FLAG_VALOR_CASE_INSENSITIVE);
    }

    /**
     * Imprime na forma de string a estrutura de um objeto DTO.
     * @return string $str
     */
    public function __toString()
    {
        if ($this->bolRecursivo) {
            return '** RECURSIVIDADE DETECTADA **';
        }
        $this->bolRecursivo = true;
        $str = $this->strClasse . ':';
        if (is_array($this->arrAtributos)) {
            foreach ($this->arrAtributos as $key => $atributo) {
                if (self::$FLAG_SET & $atributo[self::$POS_ATRIBUTO_FLAGS]) {
                    //$str .= '<br />';
                    $str .= "\n";
                    $str .= $key;

                    $val = $atributo[self::$POS_ATRIBUTO_VALOR];

                    $str .= ' ' . $this->getOperador($atributo) . ' ';

                    if (is_array($val)) {
                        $str .= "{";
                        $sep = '';
                        foreach ($val as $indice => $valor) {
                            $str .= $sep . "\n".' [' . $indice . '] => ' . $valor;
                            $sep = ',';
                        }
                        $str .= "}";

                    } else {
                        $str .= $val;

                        if ($atributo[self::$POS_ATRIBUTO_VALOR] === null) {
                            $str .= '[null]';
                        } else if ($atributo[self::$POS_ATRIBUTO_VALOR] === '') {
                            $str .= '[vazio]';
                        }
                    }
                }
            }
        }

        if (is_array($this->arrCriterios)) {

            foreach ($this->arrCriterios as $criterio) {
                $str .= "\n" . $criterio[InfraDTO::$POS_CRITERIO_NOME] . ':';
                for ($j = 0; $j < count($criterio[InfraDTO::$POS_CRITERIO_ATRIBUTOS]); $j++) {
                    if ($j > 0) {
                        $str .= ' ' . $criterio[InfraDTO::$POS_CRITERIO_OPERADORES_LOGICOS][$j - 1] . ' ';
                    }

                    $str .= $criterio[InfraDTO::$POS_CRITERIO_ATRIBUTOS][$j] . ' ' . $criterio[InfraDTO::$POS_CRITERIO_OPERADORES_ATRIBUTOS][$j] . ' ';

                    $val = $criterio[InfraDTO::$POS_CRITERIO_VALORES_ATRIBUTOS][$j];

                    if (is_array($val)) {
                        $str .= "{";
                        $sep = '';
                        foreach ($val as $indice => $valor) {
                            $str .= $sep . ' [' . $indice . '] => ' . $valor;
                            $sep = ',';
                        }
                        $str .= "}";
                    } else {
                        $str .= $val;
                    }

                }
            }
        }

        if (is_array($this->arrGruposCriterios)) {
            foreach ($this->arrGruposCriterios as $grupoCriterio) {
                $str .= "\n";
                for ($i = 0; $i < count($grupoCriterio[self::$POS_GRUPO_CRITERIO_CRITERIOS]); $i++) {

                    if ($i > 0) {
                        $str .= ' ' . $grupoCriterio[self::$POS_GRUPO_CRITERIO_OPERADORES_LOGICOS][$i - 1] . ' ';
                    }

                    $str .= $grupoCriterio[self::$POS_GRUPO_CRITERIO_CRITERIOS][$i];
                }
            }
        }
        $this->bolRecursivo = false;
        return $str;
    }

    public function adicionarCriterioSqlNativo($sql, $strNome = null)
    {

      $arr = array();
      $arr[self::$POS_CRITERIO_SQL_NATIVO_NOME] = $strNome;
      $arr[self::$POS_CRITERIO_SQL_NATIVO_VALOR] = $sql;

      if (!is_array($this->arrCriteriosSqlNativos)){
        $this->arrCriteriosSqlNativos = array();
      }

      $this->arrCriteriosSqlNativos[] = $arr;
    }

     public function removerCriterioSqlNativo($strCriterio)
     {

       if (InfraString::isBolVazia($strCriterio)) {
         throw new InfraException('Nome do critério SQL nativo não informado para remoção.');
       }

       if (is_array($this->arrCriteriosSqlNativos)) {

         $numCriterios = count($this->arrCriteriosSqlNativos);
         for ($i = 0; $i < $numCriterios; $i++) {
           if ($this->arrCriteriosSqlNativos[$i][self::$POS_CRITERIO_SQL_NATIVO_NOME] == $strCriterio) {
             unset($this->arrCriteriosSqlNativos[$i]);
             $this->arrCriteriosSqlNativos = array_values($this->arrCriteriosSqlNativos);
             return;
           }
         }
       }

       throw new InfraException('Critério SQL nativo [' . $strCriterio . '] não encontrado.');
     }

     public function removerCriteriosSqlNativos(){
       $this->arrCriteriosSqlNativos = null;
     }

     public function getArrCriteriosSqlNativos(){
      return $this->arrCriteriosSqlNativos;
     }

    /**
     * Informa ao DTO, na variável strCriterioSqlNativo, um critério nativo de consulta SQL.
     * @param string $sql
     * @return void
     */
    public function setStrCriterioSqlNativo($sql)
    {
        $this->strCriterioSqlNativo = $sql;
    }

    /**
     * Busca no DTO um critério nativo de consulta SQL.
     * @return string strCriterioSqlNativo
     */
    public function getStrCriterioSqlNativo()
    {
        return $this->strCriterioSqlNativo;
    }

    /**
     * Retira do DTO um critério nativo de consulta SQL informado, tornando nula a variável strCriterioSqlNativo.
     * @return void
     */
    public function unSetStrCriterioSqlNativo()
    {
        $this->strCriterioSqlNativo = null;
    }

    /**
     * Identifica se algum critério de consulta SQL nativo foi informado ao DTO.
     * @return boolean
     */
    public function isSetStrCriterioSqlNativo()
    {
        return ($this->strCriterioSqlNativo !== null);
    }

    /**
     * Informa ao DTO, na variável strSubSelectSqlNativo, um critério nativo de consulta SQL.
     * @param string $sql
     * @return void
     */
    public function setStrSubSelectSqlNativo($sql)
    {
        $this->strSubSelectSqlNativo = $sql;
    }

    /**
     * Busca no DTO um critério nativo de consulta SQL.
     * @return string strSubSelectSqlNativo
     */
    public function getStrSubSelectSqlNativo()
    {
        return $this->strSubSelectSqlNativo;
    }

    /**
     * Retira do DTO um critério nativo de consulta SQL informado, tornando nula a variável strSubSelectSqlNativo.
     * @return void
     */
    public function unSetStrSubSelectSqlNativo()
    {
        $this->strSubSelectSqlNativo = null;
    }

    /**
     * Identifica se algum critério de consulta SQL nativo foi informado ao DTO.
     * @return boolean
     */
    public function isSetStrSubSelectSqlNativo()
    {
        return ($this->strSubSelectSqlNativo !== null);
    }

    public function substituirSql($sqlPesquisa, $sqlSubstituicao)
    {
        $this->strSqlPesquisa = $sqlPesquisa;
        $this->strSqlSubstituicao = $sqlSubstituicao;
    }

    public function getStrSqlPesquisa()
    {
        return $this->strSqlPesquisa;
    }

    public function getStrSqlSubstituicao()
    {
        return $this->strSqlSubstituicao;
    }

  /**
   * Verifica existência de um atributo no array de metadados do DTO
   * @param $strAtributo
   * @return bool
   */
  public function isBolExisteAtributo($strAtributo)
  {
    return isset(self::$arrMetaDados[$this->strClasse][$strAtributo]);
  }
}

?>
