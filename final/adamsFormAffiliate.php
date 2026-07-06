<?php  
require 'init.php';
require 'ssi_top.php';

define('SURVEY_API_URL', 'https://csci.lakeforest.edu/~adamslpa78/csci488/final/form_api.php');
define('SURVEY_API_TOKEN', 'e77664d0ce31861e8056c8efe7c78b879b33db9efb76669c85eac4e3c166bc41');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

     $response = HTTP::curl(
         SURVEY_API_URL,
          'POST',
          $_POST,
          [],
          ['bearer' => SURVEY_API_TOKEN]
        );


    if ($response['error']) {
        $message = "Could not reach the survey API: " . $response['error'];
    }
    else if ($response['status'] == 201) {
        header('Location: affiliate_thank_you.php');
        exit;
    }
    else {
        $message = "API error (status " . $response['status'] . "): " . ($response['json']['message'] ?? $response['body']);
}
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>HTML Forms Homework</title>
    </head>
    <body>
        <h1>HTML Forms Homework</h1>
<?php if (!empty($message)) { echo "<p><strong>$message</strong></p>"; } ?>
        <form action="" method="POST">
            <p>Name:</p>
            <input type="text" name="name" required><br><br>

            <p>Email:</p>
            <input type="email" name="email" required><br><br>

            <p>Age:</p>
            <input type="number" name="age" required><br><br>

            <p>Gender:</p>
            <input type="radio" name="gender" value="male" required> Male<br>
            <input type="radio" name="gender" value="female" required> Female<br>
            <input type="radio" name="gender" value="nonbinary" required> Nonbinary<br><br>

            <p>Date:</p>
            <input type="date" name="date" required><br><br>

            <p>How much do you like listening to music? (Scale of 1-10):</p>
            <input type="range" name="music_rating" min="1" max="10" required><br><br>

            <p>Music Preferences:</p>
            <input type="checkbox" name="music[]" value="rock"> Rock<br>
            <input type="checkbox" name="music[]" value="pop"> Pop<br>
            <input type="checkbox" name="music[]" value="jazz"> Jazz<br>
            <input type="checkbox" name="music[]" value="classical"> Classical<br>
            <input type="checkbox" name="music[]" value="metal"> Metal<br><br>
            <p>Favorite Genre:</p>
            <select name="genre" required>
                <option value="rock">Rock</option>
                <option value="pop">Pop</option>
                <option value="jazz">Jazz</option>
                <option value="classical">Classical</option>
                <option value="metal">Metal</option>
            </select><br><br>

            <p>Do you play any instruments?:</p>
            <input type="radio" name="instruments" value="yes" required> Yes<br>
            <input type="radio" name="instruments" value="no" required> No<br><br>

            <p>What instruments do you play? (If Any):</p>
            <select name="instruments_list[]" multiple>
                <option value="vocals">Vocals</option>
                <option value="piano">Piano</option>
                <option value="guitar">Guitar</option>
                <option value="drums">Drums/Percussion</option>
                <option value="strings">Strings</option>
                <option value="brass">Brass</option>
                <option value="woodwinds">Woodwinds</option>
            </select><br><br>

            <p>Other Information:</p>
            <textarea name="other"></textarea><br><br>

            <input type="reset" value="Reset">
            <input type="submit" value="Submit">
        </form>
    </body>
</html>

<?php
require 'ssi_bottom.php';
?>