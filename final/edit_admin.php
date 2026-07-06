<?
require 'init.php';
require 'ssi_security.php';

if (!$is_admin){
    $message = "You do not have permission to access this page.";
    header("Location: dashboard.php?message=" . urlencode($message));
    exit;
}

$user = new User();
$message = '';
$task = isset($_GET['task']) ? $_GET['task'] : '';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

if ($user_id){
    $user->load($user_id);
}

if (!$user->get_id_value() && $task != 'add'){
    header("Location: users_management.php?message=" . urlencode("User Not Found."));
    exit;
}

if(!empty($_POST)){
    $user->values['user_name'] = trim($_POST['user_name']);
    $user->values['user_email'] = trim($_POST['user_email']);
    $is_admin_checkbox = isset($_POST['user_is_admin']) ? '1' : '0';
    $user->values['user_is_admin'] = $is_admin_checkbox;

    if (strlen(trim($user->values['user_name'])) < 2)
        $message = "User name must be at least 2 characters long.";
    elseif (!empty($_POST['user_password']) && strlen(trim($_POST['user_password'])) < 5)
        $message = "Password must be at least 5 characters long.";
    elseif (!empty($_POST['user_password']) && $_POST['user_password'] !== $_POST['user_password_verify'])
        $message = "Passwords must match.";
    else {
        $check = new User();
        $check->load($user->values['user_email'], 'user_email');
        if ($check->get_id_value() && $check->get_id_value() != $user->get_id_value())
            $message = "An account with that email address already exists.";
    }

    if(!$message){
        if(!empty($_POST['user_password'])){
            $user->values['user_password'] = hash('sha256', $_POST['user_password']);
        }
        $user->save();
        header("Location: users_management.php?message=" . urlencode("User Updated Successfully!"));
        exit;
    }
}
require 'ssi_top.php';
?>
<div class="page-wrapper">
    <div class="form-container">
        <div class="form-header">Edit User</div>
        <div class="form-content">

        <?
        if ($message) {
            echo "<p style='color:red; text-align: center;'>$message</p>";
        }
        ?>

        <form method="POST" action="edit_admin.php?user_id=<?= $user->get_id_value(); ?>">

        <label for="user_name">User Name:</label>
        <input type="text" id="user_name" name="user_name" placeholder="Full Name" value="<?= htmlspecialchars($user->values['user_name']); ?>" required>

        <label for="user_email">Email:</label>
        <input type="email" id="user_email" name="user_email" placeholder="Email" value="<?= htmlspecialchars($user->values['user_email']); ?>" required>

        <div style="display: flex; align-items: center; margin: 15px 0;">
            <input type="checkbox" id="user_is_admin" name="user_is_admin" <?= $user->values['user_is_admin'] == '1' ? 'checked' : '' ?> style="width: auto; margin: 0; margin-right: 8px; accent-color: #38BDF8;">
            <label for="user_is_admin" style="margin: 0; color: #E5E7EB;">Admin Status</label>
        </div>

        <p style="color: #666; font-size: 14px; margin: 15px 0;">Leave password fields blank to keep the current password.</p>

        <label for="user_password">New Password (optional):</label>
        <input type="password" id="user_password" name="user_password" placeholder="New Password">

        <label for="user_password_verify">Verify New Password:</label>
        <input type="password" id="user_password_verify" name="user_password_verify" placeholder="Verify New Password">

        <button type="submit">Update User</button>

        <p style="text-align: center; margin-top: 15px;">
            <a href="users_management.php">Back to User Management</a>
        </p>
        </form>
        </div>
    </div>
</div>
<?
require 'ssi_bottom.php';
?>