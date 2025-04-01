<?php
// March 21, 2025 - 13:31:36
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('error_log', __DIR__.'/errorlog.txt');
date_default_timezone_set('Europe/Moscow');
$ab_config['check'] = 1; // 1 - cloud, 0 - local
$ab_config['wh'] = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'); // array
$ab_config['unresponsive'] = 1; // 1 - stop, 0 - skip
$ab_config['phperror'] = 1;
$ab_config['timezone'] = 'Europe/Moscow';
$ab_config['webdir'] = '/protect559/'; // change if renamed script directory
$ab_config['ab_url'] = '';
$ab_config['cookie'] = 'hOlPHI';
$ab_config['auth'] = 'cookie'; // sqlite or cookie
$ab_config['js_error_msg'] = 'Your request has been denied.';
$ab_config['email'] = 'ceo@ncimport.ru'; // change to your email
$ab_config['pass'] = 'a2S1PbzoSc1wqkuvtyNn'; // change to your password
$ab_config['secondpass'] = '9529778604a9a389095fdf8093dfc241'; // delete this line if you forgot second password
$ab_config['salt'] = 'NMsgnTXZC_'.date('Y', time());
$ab_config['timesalt'] = 'Y';
$ab_config['samesite'] = 'Lax'; // Lax, Strict, None
$ab_config['is_bitrix'] = 0;
$ab_config['hits_per_user'] = 500;
$ab_config['input_button'] = 0; // 1 - off, 0 - on
$ab_config['tpl_lang'] = '';
$ab_config['buttons'] = 2; // buttons type
$ab_config['time_ban'] = '0'; // string
$ab_config['time_ban_2'] = '1'; // string
$ab_config['re_check'] = 0;
$ab_config['recaptcha_key2'] = '';
$ab_config['recaptcha_secret2'] = '';
$ab_config['recaptcha_key'] = '';
$ab_config['recaptcha_secret'] = '';
$ab_config['utm_referrer'] = 1; 
$ab_config['utm_noindex'] = 1;
$ab_config['check_get_ref'] = 1;
$ab_config['bad_get_ref'] = 'q text utm_source yclid ysclid utm_referrer';
$ab_config['secret_allow_get'] = '';
$ab_config['ptrcache_time'] = 10;
$ab_config['antibot_log_tests'] = 1;
$ab_config['antibot_log_local'] = 0;
$ab_config['antibot_log_allow'] = 1;
$ab_config['antibot_log_fake'] = 1;
$ab_config['antibot_log_goodip'] = 0;
$ab_config['antibot_log_block'] = 1;
$ab_config['header_test_code'] = 200;
$ab_config['header_error_code'] = 200;
$ab_config['period_cleaning'] = 'lastmonth';
$ab_config['php_handler'] = '';
$ab_config['noarchive'] = 0;
$ab_config['del_ref_query_string'] = 0;
$ab_config['del_page_query_string'] = 0;
$ab_config['hosting_block'] = 0;
$ab_config['block_fake_ref'] = 1; // 1 - block, 0 - do not check
$ab_config['local_null_ref_stop'] = 0; // 0 - no check
$ab_config['iframe_stop'] = 0; // 1 - block, 0 - no check
$ab_config['last_rule'] = '';
$ab_config['cloud_rus'] = 0; // 1 - Russian server
