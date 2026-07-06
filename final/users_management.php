<?
require 'init.php';
require 'ssi_security.php';

require_once 'class_pageable_list.php';

if (!$is_admin){
    $message = 'You do not have permission to access this page.';
    header("Location: dashboard.php?message=" . urlencode($message));
    exit;
}

if (isset($_GET['task']) && $_GET['task'] == 'delete' && isset($_GET['user_id'])){
    $user = new User();
    $user->delete($_GET['user_id']);
    $message = 'User deleted successfully.';
    header("Location: users_management.php?message=" . urlencode($message));
    exit;
}

$sql = "
    SELECT
        user_id,
        user_name,
        user_email,
        user_created,
        user_api_token,
        CASE
            WHEN user_is_admin = 1 THEN 'Yes'
            ELSE 'No'
        END as user_is_admin,
        COUNT(l.logon_token) as logon_count,
        MAX(l.logon_timestamp) as last_activity
    FROM " . USER_TABLE . " u
    LEFT JOIN " . LOG_TABLE . " l on u.user_id = l.logon_id
    GROUP BY u.user_id
";

$count_result = lib::db_query("SELECT COUNT(*) as count FROM " . USER_TABLE);
$count_row = $count_result->fetch();
$total_users = $count_row['count'];

$listing = new pg_list(
    $sql,
    'user_id',
    'user_created',
    'DESC',
    '',
    '',
    1,
    5,
    true,
    10,
    'even_row_css',
    'odd_row_css',
    'highlight_css'
);

$listing->add_column('user_name', 'Name');
$listing->add_column('user_email', 'Email');
$listing->add_column('user_created', 'Signup Date');
$listing->add_column('logon_count', 'Login Count');
$listing->add_column('last_activity', 'Last Activity');
$listing->add_column('user_api_token', 'API Token', '', '', '', '', false, '', 'center');
$listing->add_column('user_is_admin', 'Admin', '', '', '', '', false, '', 'center');
$listing->add_column('', 'Actions', '', 'actions-column', '', '', false, '', 'column_action_links', 'edit_admin.php');

$listing->init_list();

$listing->num_rows = $total_users;

require 'ssi_top.php';
?>
    <style>
    .even_row_css {
        background-color:#EEE;
        font-size:10pt;
    }
    .odd_row_css {
        background-color:#DDD;
        font-size:10pt;
    }
    .highlight_css {
        background-color:#DDF;
        font-size:10pt;
    }
    tbody th {
        text-align: left;
    }
    </style>

    <div class="page-wrapper page-wrapper-tall">
        <div class="form-container form-container-wide">
            <div class="form-header">User Management</div>

            <? if (isset($_GET['message'])){ ?>
                <div class="success-message">
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
            <? } ?>
            
            <div class="form-content">
                <div style="overflow-x: auto;">
                    <?= $listing->get_html() ?>
                </div>
                
                <div class="back-link">
                    <a href="dashboard.php">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    function confirm_delete(user_id, user_name) {
        var choice = confirm("Are you sure you want to delete user: " + user_name + "?");
        if (choice == true) {
            window.location.href = "users_management.php?task=delete&user_id=" + user_id;
        }
    }
    </script>

<?
require 'ssi_bottom.php';
?>