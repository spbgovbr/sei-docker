<?php
$event = isset($argv[1]) ? $argv[1] : null;
try {
    update_assets($event);
} catch (Exception $e) {
    pr('Aconteceu algum erro ao executar os comandos de build. Mensagem: ' . $e->__toString());
}

function update_assets($event) {
    switch ($event) {
        case 'update-php-assets':
            install_php_dependencies();
            break;
    }
}

function install_php_dependencies() {
    pr(str_pad('-', 1, 2));
    pr('Movendo assets da infra para /web');

    $dirs = [
        __DIR__ . "/../../../infra_js" => __DIR__ . "/public/infra_js",
        __DIR__ . "/../../../infra_css" => __DIR__ . "/public/infra_css"
    ];

    try {
        $houveAlteracoes = copiarArquivos($dirs);
        if ($houveAlteracoes) {
            printHouveAlteracoes();
        } else {
            pr('Nao houve alteracoes; nao sera necessario tomar outras acoes.');
        }
    } catch (Exception $e) {
        pr('Ocorreu um erro:');
        pr($e->__toString());
        pr('');
        pr('');
        pr('Sera necessario copiar os arquivos manualmente.');
        $i = 0;
        foreach ($dirs as $k => $v) {
            $i++;
            pr("$i. Copie [$k] para $v]");
        }
    }

    pr("Fim da movimentacao de assets da infra");
    pr('------------');
}


function printHouveAlteracoes() {
    pr('');
    pr('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
    pr('AVISO:');
    pr('O conteudo dos diretorios de assets foi modificado (ver no log acima). Lembre-se de:');
    pr('1 - Fazer upload para o servidor.');
    pr('2 - Atualizar o navegador com CTRL+F5 para atualizar o cache ou limpar o cache de seu navegador.');
    pr('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
}

/**
 * Copia os arquivos de uma origem para o destino.
 * O parâmetro esperado é um array multidimensional
 * onde a chave de cada array filho é a origem e o valor uma string referente ao path de destino.
 * Ex.:
 * [
 *      ['origem'   =>'destino'],
 *      ['origem2'  =>'destino2'],
 *      ...
 * ]
 *
 * @param $dirs
 */
function copiarArquivos($dirs) {
    $houveModificacoes = false;
    pr('------------------');
    foreach ($dirs as $de => $para) {
        pr("Origem: $de");
        pr("Destino: $para");

        if (file_exists($de)) {
            if (file_exists($para)) {
                pr('Destino existe; calculando diferencas...');

                try {
                    $hashDe = hashDirectory($de);
                    $hashPara = hashDirectory($para);
                } catch (Exception $e) {
                    pr('Ocorreu algum erro ao calcular o erro. Mensagem:');
                    pr($e->getMessage());
                    pr($e->getLine());
                    $hashDe = 'ERRO';
                    $hashPara = 'ERRO';
                }

                pr("Hash de origem: $hashDe");
                pr("Hash de destino: $hashPara");

                if ($hashDe == $hashPara) {
                    pr('Conteudo de origem igual ao de destino; nao e necessario alterar');
                } else {
                    //todo remover destino atual antes de copiar;
                    pr('Hashes diferentes, copiando novos arquivos.');
                    recurse_copy($de, $para);
                    $houveModificacoes = true;
                }
            } else {
                pr('Destino nao existe; copiando novos arquivos...');
                recurse_copy($de, $para);
                $houveModificacoes = true;
            }
            pr('- OK');
        } else {
            pr('x Origem não existe');
        }
        pr('------------------');
    }
    return $houveModificacoes;
}

function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}


/**
 * de https://jonlabelle.com/snippets/view/php/generate-md5-hash-for-directory
 * Generate an MD5 hash string from the contents of a directory.
 *
 * @param string $directory
 * @return boolean|string
 */
function hashDirectory($directory) {
    if (!is_dir($directory)) {
        return false;
    }

    $files = array();
    $dir = dir($directory);

    while (false !== ($file = $dir->read())) {
        if ($file != '.' and $file != '..') {
            if (is_dir($directory . '/' . $file)) {
                $files[] = hashDirectory($directory . '/' . $file);
            } else {
                $files[] = md5_file($directory . '/' . $file);
            }
        }
    }

    $dir->close();

    return md5(implode('', $files));
}

/**
 * Faz um echo de $str com PHP_EOL no fim
 * @param $str
 */
function pr($str) {
    echo $str . PHP_EOL;
}
