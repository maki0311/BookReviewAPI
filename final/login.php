<?
require 'init.php';

$security = false;

$message = '';
$remembered_email = '';

if(isset($_GET['message'])){
    $message = htmlspecialchars($_GET['message']);
}

if (!empty($_SESSION['logon_token'])) {
    header("Location: dashboard.php");
    exit;
}

if(isset($_COOKIE['remembered_email'])) {
    $remembered_email = htmlspecialchars($_COOKIE['remembered_email']);
}

if (!empty($_POST)) {
    $user = new User();

    $user->values['user_email'] = trim($_POST['user_email']);
    $user->values['user_password'] = trim($_POST['user_password']);

    if (strlen(trim($user->values['user_email'])) < 1)
        $message = "Please enter your email address.";
    elseif (strlen(trim($user->values['user_password'])) < 1)
        $message = "Please enter your password.";
    else {
        $check = new User();
        $check->load($user->values['user_email'], 'user_email');

        if (!$check->get_id_value())
            $message = "No account found with that email address.";
        else{
            $hashed_password = hash('sha256', $user->values['user_password']);
            if ($hashed_password !== $check->values['user_password'])
                $message = "Incorrect password.";
        }
    }

    if (!$message) {
        $logon = new Logon();
        $logon_token = bin2hex(random_bytes(32));
        $logon->set_id_value($logon_token);
        $logon->values['logon_id'] = $check->get_id_value();
        $logon->values['logon_created'] = date('Y-m-d H:i:s');
        $logon->values['logon_timestamp'] = date('Y-m-d H:i:s');
        $logon->values['logon_address'] = $_SERVER['REMOTE_ADDR'];
        $logon->save();

        $_SESSION['logon_token'] = $logon_token;
        $_SESSION['user_id'] = $check->get_id_value();

        if (isset($_POST['remember_me'])) {
            setcookie('remembered_email', $user->values['user_email'], time() + (60*60*24*365));
        } else{
            setcookie('remembered_email', '', time() - 3600);
        }
        header("Location: dashboard.php");
        exit;
    }
}
require 'ssi_top.php';
?>
<div class="page-wrapper">
    <div class="form-container">
        <div class="form-header">Login to Your Account</div>
        <div class="form-content">
            <?
                if (!empty($message)) {
                    echo '<p style="color: red; text-align: center;">' . htmlspecialchars($message) . '</p>';
                }
            ?>

            <form method="POST" action="login.php">

                <input type="email" name="user_email" placeholder="Email" value="<?= $remembered_email; ?>" required>

                <input type="password" name="user_password" placeholder="Password" required>

                <div class="checkbox-label">
                    <input type="checkbox" id="remember_me" name="remember_me">
                    <label for="remember_me">Remember my email address</label>
                </div>

                <div class="form-link">
                    Don't have an account? <a href="signup.php">Sign up here</a>
                </div>

                <button type="submit">Login</button>

            </form>
        </div>
    </div>
</div>
<?
require 'ssi_bottom.php';
?>