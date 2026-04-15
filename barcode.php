<?php
/*
 * barcode.php - Gerador simples de código de barras (sem dependências)
 * Compatível com InfinityFree
 */

$code = $_GET['text'] ?? '0000000000000';
$type = strtoupper($_GET['codetype'] ?? 'EAN13');
$size = intval($_GET['size'] ?? 40);
$scale = 2;

header('Content-Type: image/png');

$im = imagecreate(strlen($code) * $scale * 7, $size + 20);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);

// Função simples para gerar padrões de barras
function drawBars($im, $x, $y, $code, $scale, $height, $black)
{
    $bars = [
        '0' => '0001101', '1' => '0011001', '2' => '0010011', '3' => '0111101',
        '4' => '0100011', '5' => '0110001', '6' => '0101111', '7' => '0111011',
        '8' => '0110111', '9' => '0001011'
    ];

    // Bordas EAN
    $pattern = '101';
    for ($i = 0; $i < strlen($code); $i++) {
        $pattern .= $bars[$code[$i]];
    }
    $pattern .= '101';

    for ($i = 0; $i < strlen($pattern); $i++) {
        if ($pattern[$i] === '1') {
            imagefilledrectangle($im, $x + ($i * $scale), $y, $x + ($i * $scale) + $scale - 1, $y + $height, $black);
        }
    }
}

drawBars($im, 10, 10, preg_replace('/\D/', '', $code), $scale, $size, $black);

// Exibe o número embaixo
imagestring($im, 3, 10, $size, $code, $black);

imagepng($im);
imagedestroy($im);
?>
