<?php
// Несколько кнопок с выбором похожего ЦВЕТА
$color_base64['RED'] = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFBQIAX8jx0gAAAABJRU5ErkJggg=='; // красный
$color_base64['BLACK'] = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='; // черный
$color_base64['YELLOW'] = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5/hPwAIAgL/4d1j8wAAAABJRU5ErkJggg=='; // желтый
$color_base64['GRAY'] = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNs+A8AAgUBgQvw1B0AAAAASUVORK5CYII='; // серый
$color_base64['BLUE'] = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPj/HwADBwIAMCbHYQAAAABJRU5ErkJggg=='; // синий
$color_base64['GREEN'] = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkaGD4DwACiQGBU29HsgAAAABJRU5ErkJggg=='; // зеленый


shuffle($ab_config['colors']);
$color = $ab_config['colors'][0]; // оригинал названия цвета
// хэш правильного цвета:
$colorhash = hash('sha256', $ab_config['salt'].$color.$ab_config['time'].$ab_config['pass'].$ab_config['ip']);

shuffle($ab_config['colors']);
$tags = array('div', 'span', 'b', 'strong', 'i', 'em');
shuffle($tags);
$buttons = array();
foreach ($ab_config['colors'] as $ab_config['color']) {

$buttons[] = '<'.$tags[0].' style=\"background-image: url(data:image/png;base64,'.$color_base64[$ab_config['color']].');\" class=\"'.'s'.md5('antibot-btn-color'.$ab_config['time']).'\" onclick=\"'.$cloud_test_func_name.'(\'post\', data, \''.$ab_config['color'].'|'.$colorhash.'\')\"></'.$tags[0].'> ';

$buttons[] = '<'.$tags[0].' style=\"background-image: url(data:image/png;base64,'.$color_base64[$ab_config['color']].');display:none;\" class=\"'.'s'.md5('antibot-btn-color'.$ab_config['time']).'\" onclick=\"'.$cloud_test_func_name.'(\'post\', data, \''.$ab_config['color'].'|'.md5($colorhash).'\')\"></'.$tags[0].'> ';
}
shuffle($buttons);
$buttons = '<p>'.implode('',$buttons).'</p>';

$im = imagecreatetruecolor(rand(1,30), rand(1,30));

$color_code['RED'] = imagecolorallocate($im, rand(220,255), rand(0,30), rand(0,30)); // красный
$color_code['BLACK'] = imagecolorallocate($im, rand(0,15), rand(0,25), rand(0,25)); // черный
$color_code['YELLOW'] = imagecolorallocate($im, rand(245,255), rand(220,255), rand(0,25)); // желтый
$color_code['GRAY'] = imagecolorallocate($im, rand(120,130), rand(125,135), rand(125,135)); // серый
$color_code['BLUE'] = imagecolorallocate($im, rand(0,30), rand(0,30), rand(155,255)); // синий
$color_code['GREEN'] = imagecolorallocate($im, rand(0,30), rand(125,250), rand(0,30)); // зеленый

imagefill($im, 0, 0, $color_code[$color]);
ob_start(); 
imagepng($im);
imagedestroy($im);
$image_data = ob_get_contents(); 
ob_end_clean(); 

unset($color_base64);
unset($im);

echo '
document.getElementById("content").innerHTML = "<div class=\"s'.md5('antibot-btn-color'.$ab_config['time']).'\" style=\"cursor: none; pointer-events: none; background-image: url(data:image/png;base64,'.base64_encode($image_data).');\" /></div><p>'.abTranslate('If you are human, click on the similar color').'</p>'.$buttons.'";
';
