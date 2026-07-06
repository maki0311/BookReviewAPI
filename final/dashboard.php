<?
require 'init.php';
require 'ssi_security.php';

$user = new User();
$user->load($_SESSION['user_id']);

if (!$user->get_id_value()) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

if(isset($_POST['generate_token'])){
    $user->values['user_api_token'] = bin2hex(random_bytes(32));
    $user->save();
    $message = "API Token Generated Successfully!";
}

require 'ssi_top.php';
?>

<div class="page-wrapper">
    <div class="form-container">
        <div class="form-header">Welcome, <?= htmlspecialchars($user->values['user_name']); ?>!</div>
        
        <div class="form-content">
            <?
            if (!empty($message)) {
                echo '<p class="message-success">' . $message . '</p>';
            }
            ?>
            <div class="account-summary">
                <h3 style="margin-top: 0;">Your Account Summary</h3>
                
                <p><strong>Name:</strong> <?= htmlspecialchars($user->values['user_name']); ?></p>
                
                <p><strong>Email:</strong> <?= htmlspecialchars($user->values['user_email']); ?></p>
                
                <p><strong>Account Created:</strong> <?= htmlspecialchars($user->values['user_created']); ?></p>
                
                <p><strong>Registration IP:</strong> <?= htmlspecialchars($user->values['user_ip_address']); ?></p>
            </div>

            <? $token = $user->values['user_api_token']; ?>

            <div class="account-summary">
                <h3>API Access Token</h3>
                
                <? if (!$token){ ?>
                    <p>You do not have an API Access Token yet. Click below to generate one.</p>
                <? }else{ ?>
                    <p><strong>Your Token:</strong></p>
                    <div class="token-display">
                        <?= htmlspecialchars($user->values['user_api_token']); ?>
                    </div>
                <? } ?>

                <form method="POST" action="dashboard.php" style="margin-top: 10px;">
                    <input type="hidden" name="generate_token" value="1">
                    <button type="submit">
                        <?= $token ? 'Re-Generate Token' : 'Generate Token'; ?>
                    </button>
                </form>

                <? if ($token){ ?>
                <div style="margin-top: 10px;">
                    <a href="affiliate_form.zip" download class="button-download">
                        Download Affiliate Form Package
                    </a>
                </div>
                <? } ?>
            </div>

            <? if ($is_admin){ ?>
            <div class="account-summary admin-portal">
                <h3>Admin Portal</h3>
                
                <p>Welcome, Admin.</p>
                
                <div class="button-group">
                    <a href="users_management.php" class="admin">Manage Users</a>
                    <a href="api_log_listing.php">API Access Log</a>
                </div>
            </div>
            <? } ?>
        </div>
    </div>
</div>
<?
require 'ssi_bottom.php';
?>