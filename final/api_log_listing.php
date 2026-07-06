<?
require 'init.php';
require 'ssi_security.php';

require_once 'class_pageable_list.php';

if (!$is_admin){
    $message = 'You do not have permission to access this page.';
    header("Location: dashboard.php?message=" . urlencode($message));
    exit;
}

$sql = "
    SELECT
        api_log_id,
        api_log_user_id,
        api_log_form_id,
        api_log_timestamp,
        api_log_method,
        api_log_http_code,
        api_log_token,
        user_name,
        user_email
    FROM " . API_LOG_TABLE . " al
    LEFT JOIN " . USER_TABLE . " u ON al.api_log_user_id = u.user_id
";

$listing = new pg_list(
    $sql,
    'api_log_id',
    'api_log_timestamp',
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

$listing->add_column('user_name', 'Affiliate User');
$listing->add_column('user_email', 'Email');
$listing->add_column('api_log_form_id', 'Form ID');
$listing->add_column('api_log_timestamp', 'Timestamp');
$listing->add_column('api_log_method', 'Method');
$listing->add_column('api_log_http_code', 'HTTP Code');
$listing->add_column('api_log_token', 'API Token');

$listing->init_list();
?>

<?
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
        <div class="form-header">API Access Log</div>
        
        <div class="form-content">
            <?= $listing->get_html() ?>
            
            <div class="back-link">
                <a href="dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?
require 'ssi_bottom.php';
?>