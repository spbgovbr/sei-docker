#!/bin/bash


if [ -z "$MODULO_PEN_REPOSITORIO_ORIGEM" ]; then
    echo "Nenhum valor informado para MODULO_PEN_REPOSITORIO_ORIGEM, n sera feita a configuracao automatica para esse parametro"
else
  echo "Configurando MODULO_PEN_REPOSITORIO_ORIGEM"
    php -r "
      require_once '/opt/sei/web/SEI.php';
      \$conexao = BancoSEI::getInstance();
      \$conexao->abrirConexao();
      \$conexao->executarSql(\"UPDATE md_pen_parametro SET valor = $MODULO_PEN_REPOSITORIO_ORIGEM WHERE nome = 'PEN_ID_REPOSITORIO_ORIGEM'\");
      "
fi

if [ -z "$MODULO_PEN_TIPO_PROCESSO_EXTERNO" ]; then
    echo "Nenhum valor informado para MODULO_PEN_TIPO_PROCESSO_EXTERNO, nao sera feita a config automatica para esse parametro"
else
    echo "Configurando MODULO_PEN_TIPO_PROCESSO_EXTERNO"
    php -r "
        require_once '/opt/sei/web/SEI.php';
        \$conexao = BancoSEI::getInstance();
        \$conexao->abrirConexao();
        \$conexao->executarSql(\"UPDATE md_pen_parametro SET valor = $MODULO_PEN_TIPO_PROCESSO_EXTERNO WHERE nome = 'PEN_TIPO_PROCESSO_EXTERNO'\");
        "
fi

if [ -z "$MODULO_PEN_UNIDADE_GERADORA" ]; then
    echo "Nenhum valor informado para MODULO_PEN_UNIDADE_GERADORA, nao sera feita a config automatica para esse parametro"
else
    echo "Configurando MODULO_PEN_UNIDADE_GERADORA"
    php -r "
        require_once '/opt/sei/web/SEI.php';
        \$conexao = BancoSEI::getInstance();
        \$conexao->abrirConexao();
        \$conexao->executarSql(\"UPDATE md_pen_parametro SET valor = $MODULO_PEN_UNIDADE_GERADORA WHERE nome = 'PEN_UNIDADE_GERADORA_DOCUMENTO_RECEBIDO'\");
        "
  
fi

if [ -z "$MODULO_PEN_UNIDADE_ASSOCIACAO_SUPER" ] || [ -z "$MODULO_PEN_UNIDADE_ASSOCIACAO_PEN" ]; then
    echo "Nenhum valor informado para MODULO_PEN_UNIDADE_ASSOCIACAO_PEN ou MODULO_PEN_UNIDADE_ASSOCIACAO_SUPER, nao sera feita a config automatica para esse parametro"
else
    echo "Configurando MODULO_PEN_UNIDADE_ASSOCIACAO_SUPER e MODULO_PEN_UNIDADE_ASSOCIACAO_PEN "
    php -r "
        require_once '/opt/sei/web/SEI.php';
        \$conexao = BancoSEI::getInstance();
        \$conexao->abrirConexao();
        \$conexao->executarSql(\"insert ignore into md_pen_unidade(id_unidade, id_unidade_rh) values ($MODULO_PEN_UNIDADE_ASSOCIACAO_SUPER, $MODULO_PEN_UNIDADE_ASSOCIACAO_PEN)\");
        "
  
fi