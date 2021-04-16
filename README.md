## Descoloração seletiva

Script em PHP para realizar uma descoloração na imagem fornecida mas mantendo apenas uma cor escolhida.

### Como funciona

A cor escolhida (ou cores), a qual não irá descolorir, será definida pelos intervalos inferior e superior de cada valor no HSL. Assim, cada pixel encontrado, que esteja com os valores HSL dentro destes intervalos, não será convertido para a escala de cinza. Todo o resto da imagem será convertido para a escala de cinza.

O espaço de cor hue/saturation/lightness, ou, matiz/saturação/luminosidade, é um sistema de colorimetria para dimensionar uma cor por estas três propriedades. No HSL, o matiz é a cor pura numericamente ordenada em um círculo de cores de 360°. A saturação é o grau de pureza da cor pela mesclagem do matiz com a cor cinza, em uma escala de 0% (cinza) à 100% (pura). A luminosidade é a claridade da cor graduada do completamente enegrecido em 0% ao completamente embranquecido em 100%, deste modo, a cor pura está em 50% da luminosidade.

A régua de cores serve apenas para ilustrar o espaço HSL.

### Algoritmo

A conversão para a escala de cinza utiliza a equação de derivação do sinal de luminância, do padrão ITU-R BT.709-6, com os valores primários do espaço de cor CIE XYZ. A conversão entre RGB/HSL utiliza as funções deste repositório: [Conversão de espaço de cores RGB-HSL-HSV](https://github.com/danmadeira/conversao-rgb-hsl)

### Exemplo de descoloração seletiva

Para este exemplo foi adotado o intervalo de matiz 40-63, de saturação 50-100 e de luminosidade 1-100, no qual se encontra a cor amarela:

Imagem original (foto de Kai-Chieh Chan no Pexels):

![original](img/pexels-kaichieh-chan-910600.jpg?raw=true)

Imagem descolorida:

![descolorida](img/descolorida.jpg?raw=true)

### Referências

- BURGER, W.; BURGE, M. J. *Principles of Digital Image Processing: Core Algorithms.* Springer-Verlag London Limited, 2009.

- CHAN, K-C. *Mulher Segurando Uma Flor De Pétalas Amarelas.* Foto profissional gratuita, Pexels.com, 2018. Disponível em: <https://www.pexels.com/pt-br/foto/mulher-segurando-uma-flor-de-petalas-amarelas-910600/>

- COWBURN, P. e col. *Manual do PHP*. PHP Documentation Group. 12 de Abril de 2021. Disponível em: <https://www.php.net/manual/pt_BR/index.php>

- FORD, A.; ROBERTS, A. *Colour Space Conversions.* August 11, 1998. Disponível em: <http://poynton.ca/PDFs/coloureq.pdf>

- HOFFMANN, G. *CIE Color Space.* Disponível em: <http://docs-hoffmann.de/ciexyz29082000.pdf>

- IBRAHEEM, N. A.; HASAN, M. M.; KHAN, R. Z.; MISHRA, P. K. *Understanding Color Models: A Review. ARPN Journal of Science and Technology.* vol. 2, no. 3, pp. 265-275. April 2012.

- ITU *RECOMMENDATION ITU-R BT.709-6 - Parameter values for the HDTV standards for production and international programme exchange.* BT Series, Broadcasting service (television), 2015. Disponível em: <https://www.itu.int/rec/R-REC-BT.709-6-201506-I/en>

- MALACARA, D. *Color Vision and Colorimetry: Theory and Applications. 2nd ed.* SPIE. Bellingham, Washington, USA. 2011.

- SCHANDA, J. *Colorimetry: Understanding the CIE System.* Wiley, 2007.

- SHEVELL, S. K. *The Science of Color. Second Edition.* Optical Society of America. Elsevier. 2003.

- SMITH, A. R. *Color Gamut Transform Pairs.* Technical Memo No 7, Computer Graphics Lab, New York Institute of Technology, Jul 1978, issued as tutorial notes at SIGGRAPHs 78-82. Disponível em: <http://alvyray.com/Papers/CG/color78.pdf>

- STOKES, M.; ANDERSON, M.; CHANDRASEKAR, S.; MOTTA, R. *A Standard Default Color Space for the Internet - sRGB.* Version 1.10, November 5, 1996. Disponível em: <https://www.w3.org/Graphics/Color/sRGB>

- WALDMAN, N. *Math behind colorspace conversions, RGB-HSL.* May 8, 2013. Disponível em: <http://www.niwa.nu/2013/05/math-behind-colorspace-conversions-rgb-hsl/>
