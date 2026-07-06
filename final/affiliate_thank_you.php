<?
$security = false;
require 'ssi_top.php';
?>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            background-color: #020617;
            color: #E5E7EB;
            display: flex;
            flex-direction: column;
        }

        .page-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60vh;
            width: 100%;
            padding: 20px;
        }

        .thank-you-container {
            background-color: #1E293B;
            padding: 35px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            margin: 20px;
            text-align: center;
            border: 1px solid #38BDF8;
        }

        .thank-you-container h2 {
            color: #38BDF8;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 28px;
        }

        .thank-you-container p {
            color: #E5E7EB;
            line-height: 1.6;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            background-color: #38BDF8;
            color: #020617;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            margin-top: 20px;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #FF5722;
            color: #E5E7EB;
            text-decoration: none;
        }

        h1 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            text-align: center;
            padding: 25px 20px;
            margin: 0;
            background-color: #0A1B3D;
            color: #FF5722;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            flex-shrink: 0;
        }
    </style>
    <h1>Thank You!</h1>
    
    <div class="page-wrapper">
        <div class="thank-you-container">
            <h2>Review Submitted Successfully</h2>
            
            <p>Thank you for participating in this survey! Your feedback has been submitted.</p>
                        
            <a href="dashboard.php" class="back-button">Back To Dashboard</a>
        </div>
    </div>
<?
require 'ssi_bottom.php';
?>