<?php

defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__)));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(BASE_PATH . '/../application'));

defined('FILES_PATH')
    || define('FILES_PATH', realpath(BASE_PATH . '/../files'));

defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(BASE_PATH . '/../library'));
	
defined('BACKUP_PATH')
    || define('BACKUP_PATH', realpath(BASE_PATH . '/../backup'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
ini_set('include_path',  '../library' . PATH_SEPARATOR . getcwd() . PATH_SEPARATOR);
// ini_set('include_path',  '/var/www/vhosts/4dl.pl/subdomains/testy/JKZend' . PATH_SEPARATOR . getcwd() . PATH_SEPARATOR);


/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();