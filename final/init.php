<?
session_start();
// INIT file loads resources needed by multiple PHP pages in a Web Application.


define('SESSION_TIMEOUT', 60);

/******************************************************************************************
Database Connection
******************************************************************************************/
define('DB_SERVER','localhost');
define('DB_USERNAME','csci488_spring26');
define('DB_PASSWORD','writeMoreCode26');
define('DB_DATABASE','csci488_spring26');

$pdo = new PDO(
    'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE . ';charset=utf8mb4',
    DB_USERNAME,
    DB_PASSWORD
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

/******************************************************************************************
Database Tables
******************************************************************************************/
define('USER_TABLE','vukovic_users');
define('LOG_TABLE', 'vukovic_logon');
define('FORM_TABLE', 'vukovic_form');
define('API_LOG_TABLE', 'vukovic_api_log');

/******************************************************************************************
Classes
******************************************************************************************/
require_once 'class_lib.php';   // Wrapper for useful utility functions
require_once 'class_data_operations.php';
require_once 'class_pageable_list.php';
require_once 'class_HTTP.php';

require_once 'user_table.php';
require_once 'logon_table.php';
require_once 'api_log_table.php';

/******************************************************************************************
Merge and Trim the $_GET and $_POST super globals
******************************************************************************************/
$get_post = [];
foreach (array_merge($_GET, $_POST) as $key => $value) {
    $get_post[$key] = is_array($value) ? $value : trim($value);
}

// No whitespace after the closing php tag below because that would generate script output
// which would be whitespace at the beginning of the HTML code returned to the browser.
?>