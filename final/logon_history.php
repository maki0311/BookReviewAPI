<?
require 'init.php';
require 'ssi_security.php';

$user = new User();
$user->load($_SESSION['user_id']);

require 'ssi_top.php';
?>
<div class="page-wrapper">
    <div class="form-container" style="max-width: 800px;">
        <h2 style="text-align: center; color: #34495e;">Your Login History</h2>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #34495e; color: white;">
                    <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Login Time</th>
                    <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Last Activity</th>
                    <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">IP Address</th>
                    <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Duration</th>
                </tr>
            </thead>
            <tbody>
                <?
                $logon = new Logon();
                $result = $logon->load_table('logon_created', 'DESC', 'logon_id = ' . $_SESSION['user_id']);
                $rows = $result->fetchAll();

                if (count($rows) > 0){
                    foreach ($rows as $row){
                        $created = strtotime($row['logon_created']);
                        $last_activity = strtotime($row['logon_timestamp']);
                        $duration = $last_activity - $created;
                        $duration_mins = floor($duration / 60);
                        $duration_secs = $duration % 60;

                        echo '<tr style="border: 1px solid #ddd;">';
                        echo '<td style="padding: 10px;">' . htmlspecialchars($row['logon_created']) . '</td>';
                        echo '<td style="padding: 10px;">' . htmlspecialchars($row['logon_timestamp']) . '</td>';
                        echo '<td style="padding: 10px;">' . htmlspecialchars($row['logon_address']) . '</td>';
                        echo '<td style="padding: 10px;">' . $duration_mins . 'm ' . $duration_secs . 's</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" style="padding: 10px; text-align: center;">No login history found.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <p style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php">Back to Dashboard</a>
        </p>
    </div>
</div>
<?
require 'ssi_bottom.php';
?>
