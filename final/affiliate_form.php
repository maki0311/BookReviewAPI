<?
require 'init.php';

define('FORM_API_URL', 'https://csci.lakeforest.edu/~vukovicm/csci488/final/api_form.php');
define('FORM_API_TOKEN', 'paste-your-bearer-token-here');

$form_book_title = '';
$form_email = '';
$form_age = '';
$form_rating = 5;
$form_date_finished = '';
$form_review = '';
$form_recommendation = '';
$form_reading_format = '';
$form_genre = '';
$form_hidden_element = '3487';

$message = '';

$task = $get_post['task'] ?? '';

if ($task === 'save') {
    $form_age = $get_post['form_age'] ?? '';
    $form_email = $get_post['form_email'] ?? '';
    $form_book_title = $get_post['form_book_title'] ?? '';
    $form_rating = $get_post['form_rating'] ?? 5;
    $form_date_finished = $get_post['form_date_finished'] ?? '';
    $form_review = $get_post['form_review'] ?? '';
    $form_likes = isset($get_post['form_likes']) ? implode(',', $get_post['form_likes']) : '';
    $form_recommendation = $get_post['form_recommendation'] ?? 'No';
    $form_reading_format = $get_post['form_reading_format'] ?? 'Physical Copy';
    $form_genre = $get_post['form_genre'] ?? 'Fantasy';
    $form_source = isset($get_post['form_source']) ? implode(',', $get_post['form_source']) : '';
    $form_hidden_element = $get_post['form_hidden_element'] ?? '3487';

    $age_valid = ctype_digit($form_age) && (int)$form_age > 0;

    if (!$form_book_title || !$age_valid) {
        $message = 'Your Form Submission was Missing Data';
    } else {
        $api_payload = [
            'form_book_title' => $form_book_title,
            'form_email' => $form_email,
            'form_age' => $form_age,
            'form_rating' => $form_rating,
            'form_date_finished' => $form_date_finished,
            'form_review' => $form_review,
            'form_likes' => $form_likes,
            'form_recommendation' => $form_recommendation,
            'form_reading_format' => $form_reading_format,
            'form_genre' => $form_genre,
            'form_source' => $form_source,
            'form_hidden_element' => $form_hidden_element
        ];

        $response = HTTP::curl(
            FORM_API_URL,
            'POST',
            $api_payload,
            [],
            ['bearer' => FORM_API_TOKEN]
        );

        if ($response['error']) {
            $message = "Could not reach the form API: " . $response['error'];
        } else if ($response['status'] == 201) {
            header("Location: affiliate_thank_you.php");
            exit();
        } else if ($response['status'] == 200 && $response['json'] && $response['json']['status'] == 'success') {
            header("Location: affiliate_thank_you.php");
            exit();
        } else {
            $error_message = $response['json']['message'] ?? $response['body'];
            $message = "API error (status " . $response['status'] . "): " . $error_message;
        }
    }
}

$security = false;
require 'ssi_top.php';
?>

<div class="page-wrapper">
    <div class="form-container">
        <div class="form-header">Submit Your Book Review</div>
        <div class="form-content">
            <? if (!empty($message)) { ?>
                <p style="color: red; text-align: center; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
            <? } ?>

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <input type="hidden" name="task" value="save">

                <label for="booktitle">Book Title:</label>
                <input type="text" id="booktitle" name="form_book_title" value="<?= htmlspecialchars($form_book_title) ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="form_email" value="<?= htmlspecialchars($form_email) ?>" required>

                <label for="age">Age:</label>
                <input type="number" id="age" name="form_age" value="<?= htmlspecialchars($form_age) ?>" min="1" max="120" required>

                <label for="rating">Rating:</label>
                <div style="text-align: center; margin: 10px 0; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <span>1</span>
                    <input type="range" id="rating" name="form_rating" min="1" max="10" value="<?= $form_rating ?: 5 ?>" required style="width: 100%; flex-grow: 1; margin: 0; padding: 0;">
                    <span>10</span>
                </div>

                <label for="date">Date Finished:</label>
                <input type="date" id="date" name="form_date_finished" value="<?= htmlspecialchars($form_date_finished) ?>" required>

                <label for="review">Full Written Review:</label>
                <textarea id="review" name="form_review" rows="4" required><?= htmlspecialchars($form_review) ?></textarea>

                <div style="margin: 15px 0;">
                    <p><strong>What did you like about the book?</strong></p>
                    <div class="checkbox-label">
                        <input type="checkbox" id="characters" name="form_likes[]" value="Characters">
                        <label for="characters">Characters</label>
                    </div>
                    <div class="checkbox-label">
                        <input type="checkbox" id="plot" name="form_likes[]" value="Plot">
                        <label for="plot">Plot</label>
                    </div>
                    <div class="checkbox-label">
                        <input type="checkbox" id="writing" name="form_likes[]" value="Writing">
                        <label for="writing">Writing</label>
                    </div>
                    <div class="checkbox-label">
                        <input type="checkbox" id="worldbuilding" name="form_likes[]" value="World-Building">
                        <label for="worldbuilding">World-Building</label>
                    </div>
                    <div class="checkbox-label">
                        <input type="checkbox" id="themes" name="form_likes[]" value="Themes">
                        <label for="themes">Themes</label>
                    </div>
                </div>

                <div style="margin: 15px 0;">
                    <p><strong>Would you recommend this book?</strong></p>
                    <div class="checkbox-label">
                        <input type="radio" id="yes" name="form_recommendation" value="Yes" required>
                        <label for="yes">Yes</label>
                    </div>
                    <div class="checkbox-label">
                        <input type="radio" id="no" name="form_recommendation" value="No" required>
                        <label for="no">No</label>
                    </div>
                </div>

                <div style="margin: 15px 0;">
                    <p><strong>What was the reading format?</strong></p>
                    <div class="checkbox-label">
                        <input type="radio" id="physical" name="form_reading_format" value="Physical Copy" required>
                        <label for="physical">Physical Copy</label>
                    </div>
                    <div class="checkbox-label">
                        <input type="radio" id="ebook" name="form_reading_format" value="eBook" required>
                        <label for="ebook">eBook</label>
                    </div>
                    <div class="checkbox-label">
                        <input type="radio" id="audiobook" name="form_reading_format" value="Audiobook" required>
                        <label for="audiobook">Audiobook</label>
                    </div>
                </div>

                <label for="genre">Select a genre:</label>
                <select name="form_genre" id="form_genre" required>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Science-Fiction">Science Fiction</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Romance">Romance</option>
                    <option value="Horror">Horror</option>
                </select>

                <label for="source">Where did you hear about this book? (Hold Ctrl to select multiple)</label>
                <select id="source" name="form_source[]" multiple required>
                    <option value="Social-Media">Social Media</option>
                    <option value="Friend">Friend Recommendation</option>
                    <option value="Bookstore">Bookstore</option>
                    <option value="Online-review">Online Review</option>
                    <option value="School-Work">School/Work</option>
                </select>

                <input type="hidden" id="form_hidden_element" name="form_hidden_element" value="3487">

                <button type="submit">Submit Review</button>
                <button type="reset">Reset</button>
            </form>

            <div class="back-link">
                <a href="dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?
require 'ssi_bottom.php';
?>