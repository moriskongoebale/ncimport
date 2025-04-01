<?php
// обновление
if(!defined('ANTIBOT')) die('access denied');

// удаление старых потерянных счетчиков:
$link = glob(__DIR__."/../data/counters/*");
foreach ($link as $line) {
if (filemtime($line) < $ab_config['time'] - 259200) {
unlink($line);
}
}

$unique_db = new SQLite3(__DIR__.'/../data/unique.db'); 
$unique_db->busyTimeout(2000);
$unique_db->exec("PRAGMA journal_mode = WAL;");
$vacuum = $unique_db->exec("VACUUM;");

clearstatcache(); // Clears file status cache
if(function_exists('opcache_reset')) {
@opcache_reset();
}

$content = '';

if (isset($_POST['update_submit']) AND isset($_POST['upd'])) {
// список изменений и хэши:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://'.$ab_config['files_url'].'/static/update/update9.json');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
curl_setopt($ch, CURLOPT_USERAGENT, 'AntiBot');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
$actual = @json_decode(trim(curl_exec($ch)), true);
curl_close($ch);

// скачивание архива с обновлением:
$fp = fopen(__DIR__.'/../data/update.zip', 'w');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://'.$ab_config['files_url'].'/static/update/update9.zip');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_USERAGENT, 'AntiBot');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
$outch = curl_exec($ch);
curl_close($ch);
fclose($fp);


if ($actual['md5'] == md5_file(__DIR__.'/../data/update.zip')) {
$zip = new ZipArchive;
if ($zip->open(__DIR__.'/../data/update.zip') === TRUE) {
    $zip->extractTo(__DIR__.'/../');
    $zip->close();
$content .= '<div class="alert alert-success" role="alert">
'.abTranslate('Update has been successful.').'
</div>';
@unlink(__DIR__.'/../data/beta');
} else {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Update failed.').'
</div>';
}

clearstatcache(true); // Clears file status cache
if(function_exists('opcache_reset')) {
@opcache_reset();
}

echo '<script>document.location.href="?'.$abw.$abp.'=update2";</script>';
abDie();
} else {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Update failed.').' '.abTranslate('The MD5 hash of the archive does not match the reference.').'
</div>';
}

} else {
// ---------------------------------------------------------------------
//  обновление баз и структуры файлов:
// v. 9.011:
//@unlink(__DIR__.'/../ab.php');
@unlink(__DIR__.'/../post.php');
// v. 9.022:
$add = @$antibot_db->exec("ALTER TABLE counters ADD adbpercent INTEGER NOT NULL default '';");
// v. 9.024:
$add = @$antibot_db->exec("ALTER TABLE counters ADD sqlerror INTEGER NOT NULL default '0';");
// v. 9.037:
if (!file_exists(__DIR__.'/../data/cookie')) { mkdir(__DIR__.'/../data/cookie');}
// v. 9.049:
$add = @$antibot_db->exec("ALTER TABLE hits ADD city TEXT NOT NULL default '';");

// ---------------------------------------------------------------------
}

$title = abTranslate('Update');

// не установлен ZIP
if (!class_exists('ZipArchive')) {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('The ZipArchive class doesn\'t exist. Please install the ZIP extension for PHP.').'
</div>';
}

$antibotdir = realpath(__DIR__.'/../');

// получение массива актуальных файлов:
function getDirContents($dir, &$results = array()) {
global $antibotdir;
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = $dir . DIRECTORY_SEPARATOR . $value;
        if (!is_dir($path)) {
//            $results[$path] = md5_file($path);
//            $filename = explode($antibotdir.'/', $path);
            $filename = explode($antibotdir.DIRECTORY_SEPARATOR, $path);
			$filename[1] = str_replace('\\', '/', $filename[1]);
//            echo $filename[1].'<br>';
            $results[$filename[1]] = md5_file($path).'_'.md5($filename[1]);
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            //$results[$path] = md5_file($path);
        }
    }
    return $results;
}

$local = getDirContents(realpath(__DIR__.'/../')); // локальный массив

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://'.$ab_config['files_url'].'/static/update/update9.json');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
curl_setopt($ch, CURLOPT_USERAGENT, 'AntiBot');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
$actual = @json_decode(trim(curl_exec($ch)), true);
curl_close($ch);

if (isset($actual['md5'])) {
$new_ab_version = isset($actual['v']) ? trim(strip_tags($actual['v'])) : 'n/a';
unset($actual['md5']);
unset($actual['v']);
// Сравнивает array1 с одним или несколькими другими массивами и возвращает значения из array1, которые отсутствуют во всех других массивах.
$differences = array_diff($actual, $local); // Отличающиеся файлы, которые есть в архиве
$content .= '<div class="row">
<div class="col">
<div class="card">
  <div class="card-header">'.abTranslate('Your script version:').' '.$ab_version.' '.(($beta == 1) ? '<span style="color:red;">Beta</span>' : '').' ➜ '.abTranslate('New version:').' '.$new_ab_version.'</div>
<iframe style="border:none; width: 100%; height: 200px;" src="https://'.$ab_config['main_url'].'/'.abTranslate('static/update/changelog9.txt').'"></iframe>
</div>
</div>
</div>
<br />
    <div class="row">
        <div class="col">
<div class="card">
<div class="card-header"><strong>'.abTranslate('These files will be replaced or added:').'</strong></div>
<ul class="list-group list-group-flush">';
$upderror = 0;
$updcount = 0;
foreach ($differences as $k => $v) {
if (file_exists(__DIR__.'/../'.$k) AND is_writable(__DIR__.'/../'.$k)) {
$content .= '<li class="list-group-item text-success">'.$k.' - '.abTranslate('will be replaced').'</li>';
} elseif (!file_exists(__DIR__.'/../'.$k)) {
$content .= '<li class="list-group-item text-success">'.$k.' - '.abTranslate('will be added').'</li>';
} else {
$content .= '<li class="list-group-item text-danger">'.$k.' - '.abTranslate('set write permissions for this file').'</li>';
$upderror = 1;
}
$updcount++;
}
$content .= '</ul>';
if ($upderror == 1) {
$content .= '<div class="card-body"><div class="alert alert-danger" role="alert">
'.abTranslate('Update is not possible. Please fix the errors mentioned above.').'
</div></div>';
} elseif ($updcount == 0) {
$content .= '<div class="card-body"><div class="alert alert-success" role="alert">
'.abTranslate('No update required.').'
</div></div>';
} elseif ($host == 'antibot.xx') {
$content .= '<div class="card-body"><div class="alert alert-danger" role="alert">
Тут нельзя обновлять.
</div></div>';
} else {
$content .= '<div class="card-body"><form class="form-inline" action="?'.$abw.$abp.'=update" method="post">
<input name="upd" type="hidden" value="1">
<input style="cursor:pointer;" class="btn btn-block btn-success" type="submit" name="update_submit" value="'.abTranslate('Make update').'">
</form></div>';
}

$differences = array_diff($local, $actual); // различия
foreach ($differences as $k => $v) {
if (isset($actual[$k])) {unset($differences[$k]);}
}
$content .= '</div></div>
        <div class="col">
<div class="card">
<div class="card-header"><strong>'.abTranslate('Local files that will not be modified:').'</strong></div>
<ul class="list-group list-group-flush">';
foreach ($differences as $k => $v) {
$content .= '<li class="list-group-item">'.$k.'</li>';
}
$content .= '</ul>
</div>
    </div></div>
';

// обновление шаблона:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://'.$ab_config['files_url'].'/static/update/tpl9.json');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'AntiBot');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
$actual_tpl = @json_decode(trim(curl_exec($ch)), true);
curl_close($ch);

$content .= '<hr />';
$local_tpl = md5_file(__DIR__.'/../data/tpl.txt');
if (isset($actual_tpl['md5']) AND $actual_tpl['md5'] != $local_tpl) {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('The tpl.txt template differs from the actual one:').' <a href="https://'.$ab_config['main_url'].'/static/update/tpl9.txt" rel="noopener" target="_blank">tpl.txt</a>
</div>';
$content .= '<p><form class="form-inline" action="?'.$abw.$abp.'=updatetpl" method="post">
<input name="upd" type="hidden" value="1">
<input style="cursor:pointer;" class="btn btn-sm btn-success" type="submit" name="updatetpl_submit" value="'.abTranslate('Make update').'">
</form></p>';
} else {
$content .= '<div class="alert alert-success" role="alert">
'.abTranslate('The tpl.txt template does not require an update.').'
</div>';
}

// обновление шаблона error.txt:
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://'.$ab_config['files_url'].'/static/update/error9.json');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'AntiBot');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
$actual_error = @json_decode(trim(curl_exec($ch)), true);
curl_close($ch);

$content .= '<hr />';
$local_error = md5_file(__DIR__.'/../data/error.txt');
if (isset($actual_error['md5']) AND $actual_error['md5'] != $local_error) {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('The error.txt template differs from the actual one:').' <a href="https://'.$ab_config['main_url'].'/'.'static/update/error9.txt" rel="noopener" target="_blank">error.txt</a>
</div>';
$content .= '<p><form class="form-inline" action="?'.$abw.$abp.'=updateerror" method="post">
<input name="upd" type="hidden" value="1">
<input style="cursor:pointer;" class="btn btn-sm btn-success" type="submit" name="updateerror_submit" value="'.abTranslate('Make update').'">
</form></p>';
} else {
$content .= '<div class="alert alert-success" role="alert">
'.abTranslate('The error.txt template does not require an update.').'
</div>';
}
} else {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Error checking for updates.').'
</div>';
}

$content .= '<hr />';

for ($i = 1; $i < 7; $i++) {
if (!file_exists(__DIR__.'/../img/'.$i.'.jpg')) {
copy(__DIR__.'/../code/default_img/'.$i.'.jpg', __DIR__.'/../img/'.$i.'.jpg');
}
if (md5_file(__DIR__.'/../code/default_img/'.$i.'.jpg') != md5_file(__DIR__.'/../img/'.$i.'.jpg')) {
$image = imagecreatefromjpeg(__DIR__.'/../code/default_img/'.$i.'.jpg');
ob_start();
imagejpeg($image, null, 80);
$image = ob_get_contents();
ob_end_clean();
$content .= '<div class="alert alert-info" role="alert">
<strong>'.$i.'.jpg</strong> '.abTranslate('is used:').' <img style="width: 38px; height: 38px;" src="img/'.$i.'.jpg" /> '.abTranslate('default:').' <img style="width: 38px; height: 38px;" src="data:image/jpeg;base64,'.base64_encode($image).'" /> 
'.abTranslate('set the default image:').' 
<form id="form-id-'.$i.'" action="?abp=setdefaultimg" method="post" style="display: inline-block;">
<input name="setdefaultimg_submit" type="hidden" value="1">
<input name="id" type="hidden" value="'.$i.'">
<span style="cursor:pointer;" class="btn btn-primary" onclick="document.getElementById(\'form-id-'.$i.'\').submit();"> '.abTranslate('set').' </span>
</form>
</div>';
}

}
