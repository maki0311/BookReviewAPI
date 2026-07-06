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

if (!empty($_POST)) {
    $user->values['user_name'] = trim($_POST['user_name']);
    $user->values['user_email'] = trim($_POST['user_email']);

    if (strlen(trim($user->values['user_name'])) < 2)
        $message = "Your name must be at least 2 characters long.";
    elseif (!empty($_POST['user_password']) && strlen(trim($_POST['user_password'])) < 5)
        $message = "Your password must be at least 5 characters long.";
    elseif (!empty($_POST['user_password']) && $_POST['user_password'] !== $_POST['user_password_verify'])
        $message = "Your password must match the password verification.";
    else {
        $check = new User();
        $check->load($user->values['user_email'], 'user_email');
        if ($check->get_id_value() && $check->get_id_value() != $user->get_id_value())
            $message = "An account with that email address already exists.";
    }

    if (!$message) {
        if (!empty($_POST['user_password'])) {
            $user->values['user_password'] = hash('sha256', $_POST['user_password']);
        }
        $user->save();
        
        // Redirect to dashboard after successful update
        header("Location: dashboard.php?message=" . urlencode("Your profile has been successfully updated!"));
        exit;
    }
}

require 'ssi_top.php';
?>
<div class="page-wrapper">
    <div class="form-container">
        <div class="form-header">Update Your Profile</div>
        <div class="form-content">

        <?
        if ($message) {
            if (strpos($message, "successfully") !== false) {
                echo "<p style='color:green; text-align: center;'>$message</p>";
            } else {
                echo "<p style='color:red; text-align: center;'>$message</p>";
            }
        }
        ?>

        <form method="POST" action="edit_profile.php">

        <input type="text" name="user_name" placeholder="Full Name" value="<?= htmlspecialchars($user->values['user_name']); ?>" required>

        <input type="email" name="user_email" placeholder="Email" value="<?= htmlspecialchars($user->values['user_email']); ?>" required>

        <p style="color: #666; font-size: 14px; margin: 15px 0;">Leave password fields blank to keep your current password.</p>

        <input type="password" name="user_password" placeholder="New Password (optional)">

        <input type="password" name="user_password_verify" placeholder="Verify New Password">

        <button type="submit">Update Profile</button>

        <p style="text-align: center; margin-top: 15px;">
            <a href="dashboard.php">Back to Dashboard</a>
        </p>
        </form>
        </div>
    </div>
</div>
<?
require 'ssi_bottom.php';
?>