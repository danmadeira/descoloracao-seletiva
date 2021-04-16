<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Código HTML do formulário de envio da imagem original e dos intervalos em HSL.
 * 
 * @return string
 */
function formulario()
{
    $form = '<form method="post" action="' . htmlentities($_SERVER['PHP_SELF']) . '" enctype="multipart/form-data">' . PHP_EOL .
            '<div style="margin: 20px;">' . PHP_EOL .
            '<label for="imagem">Imagem:</label>' . PHP_EOL .
            '<input type="file" name="imagem" id="imagem" value="">' . PHP_EOL .
            '</div>' . PHP_EOL .
            '<div style="margin: 20px;">' . PHP_EOL .
            '<label for="hinferior">Matiz Inferior:</label>' . PHP_EOL .
            '1<input type="range" name="hinferior" id="hinferior" step="1" min="1" max="360" value="200" oninput="this.form.hinf.value=this.value">360' . PHP_EOL .
            '<input type="number" name="hinf" min="1" max="360" value="200" oninput="this.form.hinferior.value=this.value" size="4">' . PHP_EOL .
            '<label for="hsuperior">Matiz Superior:</label>' . PHP_EOL .
            '1<input type="range" name="hsuperior" id="hsuperior" step="1" min="1" max="360" value="260" oninput="this.form.hsup.value=this.value">360' . PHP_EOL .
            '<input type="number" name="hsup" min="1" max="360" value="260" oninput="this.form.hsuperior.value=this.value" size="4">' . PHP_EOL .
            '</div>' . PHP_EOL .
            '<div style="margin: 20px;">' . PHP_EOL .
            '<label for="sinferior">Saturação Inferior:</label>' . PHP_EOL .
            '1<input type="range" name="sinferior" id="sinferior" step="1" min="1" max="100" value="1" oninput="this.form.sinf.value=this.value">100' . PHP_EOL .
            '<input type="number" name="sinf" min="1" max="100" value="1" oninput="this.form.sinferior.value=this.value" size="4">' . PHP_EOL .
            '<label for="ssuperior">Saturacao Superior:</label>' . PHP_EOL .
            '1<input type="range" name="ssuperior" id="ssuperior" step="1" min="1" max="100" value="100" oninput="this.form.ssup.value=this.value">100' . PHP_EOL .
            '<input type="number" name="ssup" min="1" max="100" value="100" oninput="this.form.ssuperior.value=this.value" size="4">' . PHP_EOL .
            '</div>' . PHP_EOL .
            '<div style="margin: 20px;">' . PHP_EOL .
            '<label for="linferior">Luminosidade Inferior:</label>' . PHP_EOL .
            '1<input type="range" name="linferior" id="linferior" step="1" min="1" max="100" value="1" oninput="this.form.linf.value=this.value">100' . PHP_EOL .
            '<input type="number" name="linf" min="1" max="100" value="1" oninput="this.form.linferior.value=this.value" size="4">' . PHP_EOL .
            '<label for="lsuperior">Luminosidade Superior:</label>' . PHP_EOL .
            '1<input type="range" name="lsuperior" id="lsuperior" step="1" min="1" max="100" value="100" oninput="this.form.lsup.value=this.value">100' . PHP_EOL .
            '<input type="number" name="lsup" min="1" max="100" value="100" oninput="this.form.lsuperior.value=this.value" size="4">' . PHP_EOL .
            '</div>' . PHP_EOL .
            '<div style="margin: 20px;"><input type="submit" value="Enviar"></div>' . PHP_EOL .
            '</form>' . PHP_EOL;
    return $form;
}

/**
 * Carrega os dados do arquivo e intervalos enviados pelo formulário.
 * 
 * @return array
 */
function carregarDados()
{
    $dados = array('erro' => false, 'arquivo' => '', 'intervalo' => '');
    if (isset($_FILES['imagem']) and ($_FILES['imagem']['error'] == UPLOAD_ERR_OK)) {
        $intervalo = array();
        $intervalo['hi'] = filter_input(INPUT_POST, 'hinferior', FILTER_VALIDATE_INT);
        $intervalo['hs'] = filter_input(INPUT_POST, 'hsuperior', FILTER_VALIDATE_INT);
        $intervalo['si'] = filter_input(INPUT_POST, 'sinferior', FILTER_VALIDATE_INT);
        $intervalo['ss'] = filter_input(INPUT_POST, 'ssuperior', FILTER_VALIDATE_INT);
        $intervalo['li'] = filter_input(INPUT_POST, 'linferior', FILTER_VALIDATE_INT);
        $intervalo['ls'] = filter_input(INPUT_POST, 'lsuperior', FILTER_VALIDATE_INT);
        $dados['erro'] = false;
        $dados['arquivo'] = $_FILES['imagem'];
        $dados['intervalo'] = $intervalo;
    } elseif (!empty($_POST)) {
        $dados['erro'] = true;
    }
    return $dados;
}

/**
 * Prepara as miniaturas da imagem original e da imagem descolorida.
 * 
 * @return string
 */
function miniaturas($dados)
{
    $saida = '';
    if ($dados['erro'] === false) {
        if (!empty($dados['arquivo'])) {
            $imagem = criarImagem($dados['arquivo']);
            $saida .= original($dados['arquivo'], $imagem['extensao'], $imagem['largura'], $imagem['altura']);
            $imagem = descolorirImagem($imagem, $dados['intervalo']);
            $saida .= descolorida($imagem);
        }
    } else {
        $saida .= '<p>Erro: não subiu arquivo.</p>';
    }
    return $saida;
}

/**
 * Cria uma cópia da imagem original.
 * 
 * @param array $arquivo
 * @return array
 */
function criarImagem($arquivo)
{
    if (($arquivo['type'] == "image/jpeg") or ( $arquivo['type'] == "image/jpg")) {
        $extensao = 'jpg';
    } elseif ($arquivo['type'] == "image/gif") {
        $extensao = 'gif';
    } elseif ($arquivo['type'] == "image/png") {
        $extensao = 'png';
    }
    if ($extensao == 'jpg') {
        $img = imagecreatefromjpeg($arquivo['tmp_name']);
    } elseif ($extensao == 'gif') {
        $img = imagecreatefromgif($arquivo['tmp_name']);
    } elseif ($extensao == 'png') {
        $img = imagecreatefrompng($arquivo['tmp_name']);
        imagealphablending($img, false);
        imagesavealpha($img, true);
    }
    $largura = imagesx($img);
    $altura = imagesy($img);
    return array('img' => $img, 'extensao' => $extensao, 'largura' => $largura, 'altura' => $altura);
}

/**
 * Gera o código HTML da imagem original.
 * 
 * @param array $arquivo
 * @param string $extensao
 * @param int $largura
 * @param int $altura
 * @return string
 */
function original($arquivo, $extensao, $largura, $altura)
{
    $conteudo = file_get_contents($arquivo['tmp_name']);
    $src = 'data:image/' . $extensao . ';base64,' . base64_encode($conteudo);
    $dimensao = redimensionar($largura, $altura);
    return '<img src="' . $src . '" width="' . $dimensao['largura'] . '" height="' . $dimensao['altura'] . '">' . PHP_EOL;
}

/**
 * Gera o código HTML da imagem descolorida.
 * 
 * @param array $imagem
 * @return string
 */
function descolorida($imagem)
{
    $conteudo = buffer($imagem['img'], $imagem['extensao']);
    $src = 'data:image/' . $imagem['extensao'] . ';base64,' . base64_encode($conteudo);
    $dimensao = redimensionar($imagem['largura'], $imagem['altura']);
    return '<img src="' . $src . '" width="' . $dimensao['largura'] . '" height="' . $dimensao['altura'] . '">' . PHP_EOL;
}

/**
 * Descolore a cópia da imagem.
 * 
 * @param array $imagem
 * @param array $intervalo
 * @return array
 */
function descolorirImagem($imagem, $intervalo)
{
    $hinf = $intervalo['hi'];
    $hsup = $intervalo['hs'];
    $sinf = $intervalo['si'];
    $ssup = $intervalo['ss'];
    $linf = $intervalo['li'];
    $lsup = $intervalo['ls'];
    for($y = 0; $y < $imagem['altura']; $y++) {
        for($x = 0; $x < $imagem['largura']; $x++) {
            $cores = imagecolorsforindex($imagem['img'], imagecolorat($imagem['img'], $x, $y));
            $hsl = rgbtohsl($cores);
            if (( (($hinf > $hsup) and ($hsl['hue'] < $hinf and $hsl['hue'] > $hsup)) or
                  (($hinf < $hsup) and ($hsl['hue'] < $hinf or $hsl['hue'] > $hsup))
                ) or (
                  ($hsl['saturation'] > $ssup or $hsl['saturation'] < $sinf) or
                  ($hsl['lightness'] > $lsup or $hsl['lightness'] < $linf)
                )) {
                $v = ($cores['red'] * 0.2126 + $cores['green'] * 0.7152 + $cores['blue'] * 0.0722);
                if ($imagem['extensao'] == 'png') {
                    $cor = imagecolorallocatealpha($imagem['img'], $v, $v, $v, $cores['alpha']);
                } else {
                    $cor = imagecolorallocate($imagem['img'], $v, $v, $v);
                }
                imagesetpixel($imagem['img'], $x, $y, $cor);
            }
        }
    }
    return $imagem;
}

/**
 * 
 * @param image $img
 * @param string $extensao
 * @return buffer
 */
function buffer($img, $extensao)
{
    ob_start();
    if ($extensao == 'jpg') {
        imagejpeg($img, NULL, 95);
    } elseif ($extensao == 'gif') {
        imagegif($img);
    } elseif ($extensao == 'png') {
        imagepng($img);
    }
    $conteudo = ob_get_contents();
    ob_end_clean();
    imagedestroy($img);
    return $conteudo;
}

/**
 * Diminui o tamanho para exibição da imagem, se maior que 650 pontos.
 * 
 * @param int $largura
 * @param int $altura
 * @return array
 */
function redimensionar($largura, $altura)
{
    $dimensao = array('largura' => $largura, 'altura' => $altura);
    if ($largura > 650) {
        $dimensao['largura'] = 650;
        $dimensao['altura'] = round(650 / ($largura / $altura));
    }
    return $dimensao;
}

/**
 * Gera uma imagem de uma régua de todo o espectro da matiz.
 * 
 * @return string
 */
function cores()
{
    $ncores = 360;
    $altura = 50;
    $faixa = round($altura * 16 / $ncores);
    $largura = $faixa * $ncores;
    $margimsup = 50;
    $margim = 30;
    $li = $largura + (2 * $margim);
    $ai = $altura + $margimsup + $margim;
    $imagem = imagecreatetruecolor($li, $ai);
    $fundo = imagecolorallocate($imagem, 255, 255, 255);
    imagefill($imagem, 0, 0, $fundo);
    $textcolor = imagecolorallocate($imagem, 0, 0, 0);
    $posicao = $margim;
    $escala = array(1, 30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 360);
    imagesetthickness($imagem, 2);

    for ($i=1; $i<=$ncores; $i++) {
        $hsl = array('hue' => $i, 'saturation' => 100, 'lightness' => 50);
        $rgb = hsltorgb($hsl);
        $cor = imagecolorallocate($imagem, $rgb['red'], $rgb['green'], $rgb['blue']);
        imagefilledrectangle($imagem, $posicao, $margimsup, $posicao + $faixa - 1, $altura + $margimsup - 1, $cor);
        if (in_array($i, $escala)) {
            $legenda = $i;
            if ($i < 10) {
                $posleg = $posicao-2;
            } elseif ($i < 100) {
                $posleg = $posicao-5;
            } else {
                $posleg = $posicao-9;
            }
            imagestring($imagem, 3, $posleg, $margimsup-20, $legenda, $textcolor);
            imageline($imagem, $posicao+1, $margimsup-5, $posicao+1, $margimsup-1, $textcolor);
        }
        $posicao = $posicao + $faixa;
    }
    
    ob_start();
    imagepng($imagem);
    $conteudo = ob_get_clean();
    $base64 = 'data:image/png;base64,' . base64_encode($conteudo);
    imagedestroy($imagem);
    return '<img src="' . $base64 . '" width="' . $li . '" height="' . $ai . '" />' . PHP_EOL;
}

/**
 * Conversão do modelo de cores HSL para RGB.
 * 
 * @param array $hsl
 * @return array
 */
function hsltorgb($hsl)
{
    $hue = $hsl['hue'] / 360;
    $saturation = $hsl['saturation'] / 100;
    $lightness = $hsl['lightness'] / 100;
    
    if ($saturation == 0) {
        $red = $green = $blue = $lightness;
    } else {
        if ($lightness < 0.5) {
            $sl1 = $lightness * (1.0 + $saturation);
        } else {
            $sl1 = $lightness + $saturation - $lightness * $saturation;
        }
        $sl2 = 2 * $lightness - $sl1;
        
        $tmpR = $hue + 1/3;
        $tmpG = $hue;
        $tmpB = $hue - 1/3;
        
        if ($tmpR < 0) {
            $tmpR = $tmpR + 1;
        } elseif ($tmpR > 1) {
            $tmpR = $tmpR - 1;
        }
        if ($tmpG < 0) {
            $tmpG = $tmpG + 1;
        } elseif ($tmpG > 1) {
            $tmpG = $tmpG - 1;
        }
        if ($tmpB < 0) {
            $tmpB = $tmpB + 1;
        } elseif ($tmpB > 1) {
            $tmpB = $tmpB - 1;
        }
    
        if (6 * $tmpR < 1) {
            $red = $sl2 + ($sl1 - $sl2) * 6 * $tmpR;
        } elseif (2 * $tmpR < 1) {
            $red = $sl1;
        } elseif (3 * $tmpR < 2) {
            $red = $sl2 + ($sl1 - $sl2) * (2/3 - $tmpR) * 6;
        } else {
            $red = $sl2;
        }
        
        if (6 * $tmpG < 1) {
            $green = $sl2 + ($sl1 - $sl2) * 6 * $tmpG;
        } elseif (2 * $tmpG < 1) {
            $green = $sl1;
        } elseif (3 * $tmpG < 2) {
            $green = $sl2 + ($sl1 - $sl2) * (2/3 - $tmpG) * 6;
        } else {
            $green = $sl2;
        }
        
        if (6 * $tmpB < 1) {
            $blue = $sl2 + ($sl1 - $sl2) * 6 * $tmpB;
        } elseif (2 * $tmpB < 1) {
            $blue = $sl1;
        } elseif (3 * $tmpB < 2) {
            $blue = $sl2 + ($sl1 - $sl2) * (2/3 - $tmpB) * 6;
        } else {
            $blue = $sl2;
        }
    }
    
    $rgb['red'] = (int) round($red * 255);
    $rgb['green'] = (int) round($green * 255);
    $rgb['blue'] = (int) round($blue * 255);
    return $rgb;
}

/**
 * Conversão do modelo de cores RGB para HSL.
 * 
 * @param array $rgb
 * @return array
 */
function rgbtohsl($rgb)
{
    $red = $rgb['red'] / 255;
    $green = $rgb['green'] / 255;
    $blue = $rgb['blue'] / 255;
    
    $max = max($red, $green, $blue);
    $min = min($red, $green, $blue);
    
    $lightness = ($max + $min) / 2;
    
    if ($max == $min) {
        $saturation = 0;
        $hue = 0;
    } else {
        if ($lightness < 0.5) {
            $saturation = ($max - $min) / ($max + $min);
        } else {
            $saturation = ($max - $min) / (2.0 - $max - $min);
        }
        if ($red >= $green and $red >= $blue) {
            $hue = (($green - $blue) / ($max - $min)) * 60; // red
        } elseif ($green >= $red and $green >= $blue) {
            $hue = (2.0 + ($blue - $red) / ($max - $min)) * 60; // green
        } else {
            $hue = (4.0 + ($red - $green) / ($max - $min)) * 60; // blue
        }
        if ($hue < 0) {
            $hue = $hue + 360;
        }
    }
    
    $hsl['hue'] = (int) round($hue);
    $hsl['saturation'] = (int) round($saturation * 100);
    $hsl['lightness'] = (int) round($lightness * 100);
    return $hsl;
}

echo '<!DOCTYPE html><html><head><title>Descoloração Seletiva</title></head><body>' . PHP_EOL;
echo cores();
echo formulario();
echo miniaturas(carregarDados());
echo '</body></html>' . PHP_EOL;
