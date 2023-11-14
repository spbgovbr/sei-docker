<?php

require_once '../infra_php/Infra.php';

session_start();

if ($_GET['v'] == InfraCaptcha::$TIPO_INFRA_V2) {
    $strCaptcha = InfraCaptcha::gerarV2($_SESSION['INFRA_CAPTCHA_CODIGO_'.$_GET['i']]);
} else {
    $strCaptcha = InfraCaptcha::gerar($_SESSION['INFRA_CAPTCHA_CODIGO']);
}

//PAUSAS DE 0.1S
$numPausa = 10;

$fields = join(
    '/',
    array(
        'H8ChunkID',
        'VChunkSize',
        'H8Format',
        'H8Subchunk1ID',
        'VSubchunk1Size',
        'vAudioFormat',
        'vNumChannels',
        'VSampleRate',
        'VByteRate',
        'vBlockAlign',
        'vBitsPerSample'
    )
);

$data = '';
$arrArquivos = array();
for ($i = 0; $i < strlen($strCaptcha); $i++) {
    $arrArquivos[] = '../infra_php/captcha/audio/' . strtoupper($strCaptcha[$i]) . '.wav';
    for ($j = 0; $j < $numPausa; $j++) {
        $arrArquivos[] = '../infra_php/captcha/audio/pausa.wav';
    }
}

foreach ($arrArquivos as $strArquivo) {
    $fp = fopen($strArquivo, 'rb');
    $header = fread($fp, 36);
    $info = unpack($fields, $header);
    //READ OPTIONAL EXTRA STUFF
    if ($info['Subchunk1Size'] > 16) {
        $header .= fread($fp, ($info['Subchunk1Size'] - 16));
    }
    //READ SUBCHUNK2ID
    $header .= fread($fp, 4);
    //READ SUBCHUNK2SIZE
    $size = unpack('vsize', fread($fp, 4));
    $size = $size['size'];
    //READ DATA
    $data .= fread($fp, $size);
}

switch ($_GET['f']) {
    case 'aac':
        $strArquivoWav = tempnam("/tmp", "infra-captcha-");
        $strArquivoAac = tempnam("/tmp", "infra-captcha-");
        file_put_contents($strArquivoWav, $header . pack('V', strlen($data)) . $data);
        exec('ffmpeg -i "' . $strArquivoWav . '" -f ipod ' . $strArquivoAac . ' -y 2>&1', $o, $err);
        if ($err == 0) {
            header('Content-Type: audio/mp4');
            readfile($strArquivoAac);
        } else {
            throw new InfraException('Erro do ffmpeg gerando audio AAC do captcha', null, 'Erro:' . $err . "\n" . $o);
        }
        try {
            unlink($strArquivoWav);
        } catch (Exception $e) {
        }
        break;
    case 'mp3':
        $strArquivoWav = tempnam("/tmp", "infra-captcha-");
        $strArquivoMp3 = tempnam("/tmp", "infra-captcha-");
        file_put_contents($strArquivoWav, $header . pack('V', strlen($data)) . $data);
        exec('ffmpeg -i "' . $strArquivoWav . '" -f mp3 ' . $strArquivoMp3 . ' -y 2>&1', $o, $err);
        if ($err == 0) {
            header('Content-Type: audio/mpeg');
            readfile($strArquivoMp3);
        } else {
            throw new InfraException('Erro do ffmpeg gerando audio MP3 do captcha', null, 'Erro:' . $err . "\n" . $o);
        }
        try {
            unlink($strArquivoWav);
            unlink($strArquivoMp3);
        } catch (Exception $e) {
        }
        break;
    case 'ogg':
        $strArquivoWav = tempnam("/tmp", "infra-captcha-");
        $strArquivoOgg = tempnam("/tmp", "infra-captcha-");
        file_put_contents($strArquivoWav, $header . pack('V', strlen($data)) . $data);
        exec('ffmpeg -i "' . $strArquivoWav . '" -f ogg ' . $strArquivoOgg . ' -y 2>&1', $o, $err);
        if ($err == 0) {
            header('Content-Type: audio/ogg');
            readfile($strArquivoOgg);
        } else {
            throw new InfraException('Erro do ffmpeg gerando audio OGG do captcha', null, 'Erro:' . $err . "\n" . $o);
        }
        try {
            unlink($strArquivoWav);
        } catch (Exception $e) {
        }
        break;
    case 'wav':
    default:
        header('Content-Type: audio/wav');
        echo $header . pack('V', strlen($data)) . $data;
}
