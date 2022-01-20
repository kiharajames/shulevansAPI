<?php

	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] .DS. 'shulevansAPI');
	defined ('INC_PATH') ? null : define('INC_PATH', SITE_ROOT.DS.'includes');
	defined ('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT.DS.'core');
	defined ('SCHOOL_PATH') ? null : define('SCHOOL_PATH', SITE_ROOT.DS.'../user/school');
	defined ('DRIVER_PATH') ? null : define('DRIVER_PATH', SITE_ROOT.DS.'../user/driver');
	defined ('PARENT_PATH') ? null : define('PARENT_PATH', SITE_ROOT.DS.'../user/parent');
	//load the config file first
	require_once(INC_PATH.DS."config.php");

	//load the core classes
	require_once(CORE_PATH.DS."post.php");

	function jsonResponse($status, $data){
		$response['status'] = $status;
		$response['data'] = $data;
		echo json_encode($response);
	}


?>