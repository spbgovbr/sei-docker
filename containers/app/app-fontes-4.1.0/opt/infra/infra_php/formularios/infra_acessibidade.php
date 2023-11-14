<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/06/2022 - criado por mgb29
 *
 */

try {
    //require_once 'Infra.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoInfra::getInstance()->validarLink();

    PaginaInfra::getInstance()->verificarSelecao('infra_acessibilidade_exibir');

    //SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'infra_acessibilidade_exibir':
            $strTitulo = 'Acessibilidade - Teclas de Atalho';

            $arrComandos[] = '<button type="button" id="btnFechar" value="Fechar" class="infraButton" onclick="document.getElementById(\'divInfraBarraLocalizacao\').innerHTML=\'\';document.getElementById(\'divInfraAreaTelaD\').innerHTML=\'\';">Fechar</button>';

            $arrAcessibilidade = array();
            $arrAcessibilidade['Geral'] = array(
                'ALT + F1' => 'exibe esta tela',
                'ALT + F9' => 'exibir ou ocultar menu',
                'ALT + F11' => 'trocar de unidade',
                'ALT + F12' => 'posiciona no link sair do sistema',
                'ALT + M' => 'pesquisa no menu',
                'ALT + T' => 'posiciona no título da tela',
                'ALT + B' => 'posiciona no primeiro botão da barra de comandos',
                'TAB' => 'navegação entre componentes da tela',
                'SHIFT + TAB' => 'navegação inversa entre componentes da tela',
                'ALT + Seta acima' => 'posiciona no componente de seleção da linha anterior (se o foco está em um componente em tabela)',
                'ALT + Seta abaixo' => 'posiciona no componente de seleção da próxima linha (se o foco está em um componente em tabela)',
                'ESC' => 'fechar janelas de seleção abertas internamente',
            );

            $arrAcessibilidadeSistema = PaginaInfra::getInstance()->getArrStrAcessibilidade();
            if (is_array($arrAcessibilidadeSistema)) {
                foreach ($arrAcessibilidadeSistema as $strGrupo => $arrItens) {
                    if (isset($arrAcessibilidade[$strGrupo])) {
                        $arrAcessibilidade[$strGrupo] = array_merge(
                            $arrAcessibilidade[$strGrupo],
                            $arrAcessibilidadeSistema[$strGrupo]
                        );
                    } else {
                        $arrAcessibilidade[$strGrupo] = $arrItens;
                    }
                }
            }

            $strTexto = '';
            foreach ($arrAcessibilidade as $strGrupo => $arrItens) {
                $strTexto .= '<p>' . $strGrupo . ':</p>';
                $strTexto .= '<ul>';
                foreach ($arrItens as $tecla => $descricao) {
                    $strTexto .= '<li><b>' . $tecla . '</b> - ' . $descricao . '</li>';
                }
                $strTexto .= '</ul>';
                $strTexto .= '<br>';
            }


            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
} catch (Exception $e) {
    PaginaInfra::getInstance()->processarExcecao($e);
}
PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema());
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>
    p, li {font-size:.875rem;}
    #divTexto {padding:1rem}
<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavascript();
?>
    function inicializar(){
    document.getElementById('btnFechar').focus();
    }
<?
PaginaInfra::getInstance()->fecharJavascript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo, 'onload="inicializar()"');
?>
    <form id="frmInfraAcessibilidade" method="post">
        <?
        PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div id="divTexto" class="infraAreaDadosDinamica"
             tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>">
            <?= $strTexto; ?>
        </div>
        <?
        PaginaInfra::getInstance()->fecharAreaDados();
        ?>
    </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
