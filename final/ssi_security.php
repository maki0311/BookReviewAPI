<?
$message = '';
$is_admin = false;

if (!isset($_SESSION['logon_token'])) {
    $message = 'You must be logged in to access this page.';
    header("Location: login.php?message=" . urlencode($message));
    exit;
}

$logon = new Logon();
$logon->load($_SESSION['logon_token']);

if (!$logon->get_id_value()) {
    $message = 'Your login session is invalid. Please log in again.';
    session_destroy();
    header("Location: login.php?message=" . urlencode($message));
    exit;
}

$last_activity = strtotime($logon->values['logon_timestamp']);
$current_time = time();
$time_elapsed = $current_time - $last_activity;

if ($time_elapsed > SESSION_TIMEOUT) {
    $message = 'Your login session has expired. Please log in again.';
    session_destroy();
    header("Location: login.php?message=" . urlencode($message));
    exit;
}

$logon->values['logon_timestamp'] = date('Y-m-d H:i:s');
$logon->save();

if (empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = $logon->values['logon_id'];
}

$user_check = new User();
$user_check->load($_SESSION['user_id']);
$is_admin = $user_check->values['user_is_admin']  == '1';
?>