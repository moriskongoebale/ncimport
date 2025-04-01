<?php
// ReCAPTCHA v2 без кнопок

// хэш правильной кнопки:
$hash0 = '1|'.hash('sha256', $ab_config['salt'].$ab_config['time'].$ab_config['pass']);

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
document.getElementById("content").innerHTML = "'.abTranslate('Loading...').'";
'.$cloud_test_func_name.'(\'post\', data, \''.$hash0.'\');
}

';
