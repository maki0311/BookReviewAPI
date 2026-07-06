<?
require 'init.php';

if(!empty($_SESSION['logon_token'])){
    $logon = new Logon();
    $logon->delete($_SESSION['logon_token']);
}

session_destroy();

setcookie('remembered_email', '', time() - 3600, '/');
setcookie('PHPSESSID', '', time() - 3600, '/');

header("Location: login.php?message=" . urlencode("You have been logged out."));
exit;
?>