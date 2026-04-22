<?php session_start();
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
	@extract($_POST);
	@extract($_GET);
	@extract($_FILES);
	@extract($_SESSION);
	@extract($_SERVER);


	ini_set('memory_limit', '800M');
	ini_set('max_upload_filesize', '100M');
	ini_set('max_execution_time', '150');
	ini_set('register_globals', 'on');
	ini_set('session.gc_maxlifetime', 3600);
	session_set_cookie_params(3600);
	if ($HTTP_HOST == 'localhost' || $HTTP_HOST== '127.0.0.1') {
		define('LOCAL_MODE', true);
	} else {
		define('LOCAL_MODE', false);
	}

	$tmp = dirname(__FILE__);
	$tmp = str_replace('\\' ,'/',$tmp);
	$tmp = substr($tmp, 0, strrpos($tmp, '/'));
	define('SITE_FS_PATH', $tmp); 
	define('INC_PATH','includes/');
	define('TEMPLATE_PATH',SITE_FS_PATH.'/template/');
        require_once(SITE_FS_PATH."/includes/config.php");
	require_once(SITE_FS_PATH."/includes/functions.php");
	
?>