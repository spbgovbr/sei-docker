<?

try {
    require_once dirname(__FILE__).'/../web/Sip.php';

    session_start();

    SessaoSip::getInstance(false);

    InfraDebug::getInstance()->setBolLigado(true);
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->setBolEcho(true);
    InfraDebug::getInstance()->limpar();

    if (substr(SIP_VERSAO, 0, 3) != '3.1') {
        throw new InfraException('Versao do SIP '.SIP_VERSAO.' incompativel (versao requerida 3.1.x)');
    }

    InfraDebug::getInstance()->gravar('INICIANDO PROCESSAMENTO DE INDICES...');

    BancoSip::getInstance()->abrirConexao();

    $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());

    $arrTabelas = array(
        'administrador_sistema',
        'contexto',
        'coordenador_perfil',
        'coordenador_unidade',
        'grupo_rede',
        'hierarquia',
        'item_menu',
        'login',
        'menu',
        'orgao',
        'perfil',
        'permissao',
        'recurso',
        'recurso_vinculado',
        'regra_auditoria',
        'rel_grupo_rede_unidade',
        'rel_hierarquia_unidade',
        'rel_orgao_autenticacao',
        'rel_perfil_item_menu',
        'rel_perfil_recurso',
        'rel_regra_auditoria_recurso',
        'seq_infra_auditoria',
        'seq_infra_log',
        'servidor_autenticacao',
        'sistema',
        'tipo_permissao',
        'unidade',
        'usuario',
        'codigo_acesso',
        'usuario_historico',
        'codigo_bloqueio',
        'dispositivo_acesso',
        'email_sistema',
        'grupo_perfil',
        'rel_grupo_perfil_perfil'
    );

    sort($arrTabelas);

    $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas);

    BancoSip::getInstance()->fecharConexao();

    InfraDebug::getInstance()->gravar('FIM');
} catch (Exception $e) {
    if ($e instanceof InfraException && $e->contemValidacoes()) {
        die(InfraString::excluirAcentos($e->__toString())."\n");
    }

    echo(InfraException::inspecionar($e));

    try {
        LogSip::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
}
?>