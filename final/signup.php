<?
require 'init.php';

$security = false;

if (isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit;
}

$message = '';

if (!empty($_POST)) {
    $user = new User();

    $user->values['user_name']       = trim($_POST['user_name']);
    $user->values['user_email']      = trim($_POST['user_email']);
    $user->values['user_password']   = trim($_POST['user_password']);
    $user->values['user_created'] = date('Y-m-d H:i:s');
    $user->values['user_ip_address'] = $_SERVER['REMOTE_ADDR'];
    $user->values['user_api_token'] = '';
    $user->values['user_is_admin'] = '0';
    
    if (strlen(trim($user->values['user_name'])) < 2)
        $message = "Your name must be at least 2 characters long.";
    elseif (strlen(trim($_POST['user_password'])) < 5)
        $message = "Your password must be at least 5 characters long.";
    elseif ($_POST['user_password'] !== $_POST['user_password_verify'])
        $message = "Your password must match the password verification.";
    else {
        $check = new User();
        $check->load($user->values['user_email'], 'user_email');
        if ($check->get_id_value())
            $message = "An account with that email address already exists.";
    }

    if (!$message) {
        $user->values['user_password'] = hash('sha256', $user->values['user_password']);
        $user->save();

        $user_id = $user->get_id_value();

        $logon = new Logon();
        $logon_token = bin2hex(random_bytes(32));
        $logon->set_id_value($logon_token);
        $logon->values['logon_id'] = $user_id;
        $logon->values['logon_created'] = date('Y-m-d H:i:s');
        $logon->values['logon_timestamp'] = date('Y-m-d H:i:s');
        $logon->values['logon_address'] = $_SERVER['REMOTE_ADDR'];
        $logon->save();

        $_SESSION['logon_token'] = $logon_token;
        $_SESSION['user_id'] = $user_id;

        header("Location: thank_you.php");
        exit;
    }
}
require 'ssi_top.php';
?>
<div class="page-wrapper">
    <div class="form-container">
        <div class="form-header">Create Your Account</div>
        <div class="form-content">
            <?
                if (!empty($message)) {
                    echo '<p style="color: red; text-align: center;">' . htmlspecialchars($message) . '</p>';
                }
            ?>

            <form method="POST" action="signup.php">

                <input type="text" name="user_name" placeholder="Name" required>

                <input type="email" name="user_email" placeholder="Email" required>

                <input type="password" name="user_password" placeholder="Password" required>

                <input type="password" name="user_password_verify" placeholder="Verify Password" required>

                <div class="form-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>

                <button type="submit">Sign Up</button>

            </form>
        </div>
    </div>
</div>
<?
require 'ssi_bottom.php';
?>