<?
if (!isset($security) || $security !== false) {
    require 'ssi_security.php';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Web App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="header-top">
        <div></div>
        <div>
            <?
            if (!empty($_SESSION['user_id'])) {
                $user_info = new User();
                $user_info->load($_SESSION['user_id']);
                echo htmlspecialchars($user_info->values['user_email']) . ' is logged in ';
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
        </div>
    </div>

    <h1 class="header-title">Survey Form API Web App</h1>

    <?
    if (!empty($_SESSION['user_id'])) {
    ?>
    <div class="header-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="edit_profile.php">Edit Profile</a>
        <a href="logon_history.php">Login History</a>
        <a href="affiliate_form_modestova.php">Eva's Survey</a>
        <a href="adamsFormAffiliate.php">Lexi's Survey</a>
    </div>
    <?
    }
    ?>