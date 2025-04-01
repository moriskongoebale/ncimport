<?php
// установка дефолтной картинки
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Update img');

if (isset($_POST['setdefaultimg_submit']) AND isset($_POST['id'])) {
$_POST['id'] = isset($_POST['id']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['id'])) : abDie('id');
if (file_exists(__DIR__.'/../code/default_img/'.$_POST['id'].'.jpg')) {
copy(__DIR__.'/../code/default_img/'.$_POST['id'].'.jpg', __DIR__.'/../img/'.$_POST['id'].'.jpg');
file_put_contents(__DIR__.'/../data/subsalt.php', '<?php $ab_config[\'subsalt\'] = \''.abRandword(8).'\';', LOCK_EX);
}
}

echo '<script>document.location.href="?'.$abw.$abp.'=update";</script>';
