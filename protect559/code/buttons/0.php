<?php
// Одна большая кнопка "Я не робот"

// хэш правильной кнопки:
$hash0 = '1|'.hash('sha256', $ab_config['salt'].$ab_config['time'].$ab_config['pass']);
$style0 = 'o'.md5($hash0);
$onestyle[] = '.'.$style0.' {} ';
$onebtns[] = '<div style="cursor: pointer;" class="'.$style0.' '.'s'.md5('antibot-btn-success'.$ab_config['time']).'" onclick="'.$cloud_test_func_name.'(\'post\', data, \''.$hash0.'\')">'.abTranslate('I\'m not a robot').'</div>'; // валидный

for ($i = 0; $i < rand(2,6); $i++) {
$hash0 = '1|'.hash('sha256', $ab_config['salt'].$ab_config['time'].$ab_config['pass'].rand(1,99999));
$style0 = 'o'.md5($hash0);
$onestyle[] = '.'.$style0.' {display: none;} ';
$onebtns[] = '<div style="cursor: pointer;" class="'.$style0.' '.'s'.md5('antibot-btn-success'.$ab_config['time']).'" onclick="'.$cloud_test_func_name.'(\'post\', data, \''.$hash0.'\')">'.abTranslate('I\'m not a robot').'</div>'; // рандомная
}
shuffle($onebtns);
shuffle($onestyle);

echo '
document.getElementById("content").innerHTML = b64_to_utf8("'.base64_encode('<p>'.abTranslate('Confirm that you are human:').'</p>'.implode('', $onebtns).'<style>'.implode(' ', $onestyle).'</style>').'");
';
