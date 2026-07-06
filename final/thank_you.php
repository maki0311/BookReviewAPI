<?
require 'init.php';

$user = new User();
$user->load($_SESSION['user_id']);

if (!$user->get_id_value()) {
    header("Location: login.php");
    exit;
}

require 'ssi_top.php';
?>
<div style="text-align: center; margin-top: 50px;">
    <h2>Thank You for Signing Up!</h2>
    <p>Hi <strong><?= htmlspecialchars($user->values['user_name']); ?></strong>, your account has been successfully created.</p>
    <p style="margin-top: 30px;">
        <a href="dashboard.php" style="display: inline-block; background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
            Go to Dashboard
        </a>
    </p>
</div>
<?
require 'ssi_bottom.php';
?>