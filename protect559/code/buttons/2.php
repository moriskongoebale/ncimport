<?php
// Несколько кнопок с выбором похожей КАРТИНКИ
$color_base64['RED'] = '1';
$color_base64['BLACK'] = '2';
$color_base64['YELLOW'] = '3';
$color_base64['GRAY'] = '4';
$color_base64['BLUE'] = '5';
$color_base64['GREEN'] = '6';

shuffle($ab_config['colors']);

$color = $ab_config['colors'][0]; // оригинал названия цвета
// хэш правильного цвета:
$colorhash = hash('sha256', $ab_config['salt'].$color.$ab_config['time'].$ab_config['pass'].$ab_config['ip']);

$buttons = array();
$jsf = array();
foreach ($ab_config['colors'] as $ab_config['color']) {
//$buttons[] = '<img src=\"'.$ab_config['webdir'].'img/'.$color_base64[$ab_config['color']].'.jpg?id='.md5($ab_config['salt'].$ab_config['host']).''.'\" style=\"cursor: pointer;\" onclick=\"'.$cloud_test_func_name.'(\'post\', data, \''.$ab_config['color'].'|'.$colorhash.'\')\"> ';
$md = md5($ab_config['time'].$ab_config['salt'].$color_base64[$ab_config['color']]);
$buttons[] = '<span id=\"'.$md.'\" style=\"cursor: pointer;\" onclick=\"'.$cloud_test_func_name.'(\'post\', data, \''.$ab_config['color'].'|'.$colorhash.'\')\"></span> ';
$jsf[] = 'fetchAndSetImage("'.$color_base64[$ab_config['color']].'", "'.$md.'");';
}
shuffle($buttons);
shuffle($jsf);
$buttons = '<p>'.implode('', $buttons).'</p>';

$red = rand(10,50);
$green = rand(10,50);
$blue = rand(10,50);
$im = imagecreatefromjpeg(__DIR__.'/../../img/'.$color_base64[$color].'.jpg');
imagefilter($im, IMG_FILTER_COLORIZE, $red, $green, $blue);
imageflip($im, IMG_FLIP_HORIZONTAL); // отражение по горизонтале
imagegammacorrect($im, 1.0, 1.537); // гамма коррекция
// накладывание рандом точек:
for ($i = 0; $i < 1000; $i++) {
$red = imagecolorallocate($im, rand(1,255), rand(1,255), rand(1,255));
imagesetpixel($im, rand(1,100),rand(1,100), $red);
}
$im = imagerotate($im, rand(-20,20), imageColorAllocateAlpha($im, 0, 0, 0, 127)); // поворот по кругу
ob_start();
imagepng($im);
$image_data1 = ob_get_contents();
imagedestroy($im);
ob_end_clean(); 
unset($im);
echo '
document.getElementById("content").innerHTML = "<img src=\"data:image/png;base64,'.base64_encode($image_data1).'\" /><p>'.abTranslate('If you are human, click on the similar image').' </p>'.$buttons.'";
';
?>

function fetchAndSetImage(param, imageId) {
  var url = '<?php echo $ab_config['uri']; ?>';
  var formData = new FormData();
  formData.append('img', param);
  formData.append('time', "<?php echo $ab_config['time']; ?>");
  formData.append('<?php echo $ab_config['post_md']; ?>', 'img');

  var requestOptions = {
    method: 'POST',
    body: formData
  };

  fetch(url, requestOptions)
    .then(response => response.blob())
    .then(blob => {
      var imageUrl = URL.createObjectURL(blob);
      var img = document.createElement('img'); // Создание элемента <img>
      img.src = imageUrl;                       // Установка URL-адреса изображения
      var span = document.getElementById(imageId);
//      span.innerHTML = ''; // Очистка содержимого <span> (если нужно)
      span.appendChild(img); // Вставка изображения в элемент <span>
    })
    .catch(error => console.error('Произошла ошибка при получении изображения:', error));
}

<?php echo implode("\n", $jsf); ?>

