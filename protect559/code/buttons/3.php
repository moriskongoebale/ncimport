<?php
// ReCAPTCHA v2 + кнопка "Я не робот"

// хэш правильной кнопки:
$hash0 = '1|'.hash('sha256', $ab_config['salt'].$ab_config['time'].$ab_config['pass']);
$style0 = 'o'.md5($hash0);
$onestyle[] = '.'.$style0.' {} ';
$onebtns[] = '<div style="cursor: pointer;" class="'.$style0.' '.'s'.md5('antibot-btn-success'.$ab_config['time']).'" onclick="'.$cloud_test_func_name.'(\'post\', data, \''.$hash0.'\')">'.abTranslate('Go to website').'</div>'; // валидный

for ($i = 0; $i < rand(2,6); $i++) {
$hash0 = '1|'.hash('sha256', $ab_config['salt'].$ab_config['time'].$ab_config['pass'].rand(1,99999));
$style0 = 'o'.md5($hash0);
$onestyle[] = '.'.$style0.' {display: none;} ';
$onebtns[] = '<div style="cursor: pointer;" class="'.$style0.' '.'s'.md5('antibot-btn-success'.$ab_config['time']).'" onclick="'.$cloud_test_func_name.'(\'post\', data, \''.$hash0.'\')">'.abTranslate('Go to website').'</div>'; // рандомная
}
shuffle($onebtns);
shuffle($onestyle);

echo '
var script = document.createElement("script");
script.src = "https://www.google.com/recaptcha/api.js";
document.body.appendChild(script);
script.onload = function() {
document.getElementById("content").innerHTML = "<div style=\"max-width: 302px; text-align: center;margin: 0 auto;\"><p>'.abTranslate('Confirm that you are human:').'</p><p class=\"g-recaptcha\" style=\"display: inline-block;\" data-sitekey=\"'.$ab_config['recaptcha_key2'].'\" data-callback=\"onRecaptchaSuccess\">'.abTranslate('Loading...').'</p></div>";
}

// разгадали рекапчу:
window.onRecaptchaSuccess = function(token) {
data += "&g-recaptcha-response=" + token;
//'.$cloud_test_func_name.'(\'post\', data, \''.$hash0.'\');
document.getElementById("content").innerHTML = "<div style=\"max-width: 302px; text-align: center;margin: 0 auto;\">"+b64_to_utf8("'.base64_encode(''.implode('', $onebtns).'</div><style>'.implode(' ', $onestyle).'</style>').'");
}

';
