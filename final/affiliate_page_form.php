<?php
require 'init.php';

define('SURVEY_API_URL', 'https://csci.lakeforest.edu/~modestovae/csci488/final_project/page_form.php'); 
define('SURVEY_API_TOKEN', 'paste-your-bearer-token-here');  // affiliate's token

$message = '';
$task = $get_post['task'] ?? '';

$form_username = "";
$form_email_address = "";
$form_number_of_books = "";
$form_range_of_joy = "";
$form_date_last_read = "";
$form_favorite_book = "";
$form_pages = "";
$form_medium = "";
$form_club = "";
$form_hidden_input = "v 1.1";

$form_genre_array = [];
$form_books_read_array = [];

$genres = [
  "fantasy" => "Fantasy",
  "romance" => "Romance",
  "memoirs" => "Memoirs",
  "mystery" => "Mystery / Thriller",
  "science_fiction" => "Science Fiction",
  "historical_fiction" => "Historical Fiction",
  "nonfiction" => "Non‑fiction",
  "horror" => "Horror",
  "poetry" => "Poetry",
  "young_adult" => "Young Adult (YA)",
  "graphic_novels" => "Graphic Novels / Comics"
];

$books = [
  "the_poppy_war" => "The Poppy War by R.F. Kuang",
  "between_the_world" => "Between the World and Me by Ta-Nehisi Coates",
  "the_very_secret_society" => "The Very Secret Society of Irregular Witches by Sangu Mandanna",
  "one_day" => "One Day, Everyone Will Have Always Been Against This by Omar El Akkad"
];

$mediums = [
  "paperback" => "Paperback",
  "hardback" => "Hardback",
  "ebook" => "Electronic book",
  "medium_other" => "Other"
];

$club_options = [
  "" => "Select one",
  "club_yes" => "Yes",
  "club_no" => "No",
  "club_other" => "Other"
];

if ($task === 'save') {

  $form_username = trim($get_post['username'] ?? '');
  $form_email_address = trim($get_post['email_address'] ?? '');
  $form_number_of_books = trim($get_post['number_of_books_read'] ?? '');
  $form_range_of_joy = trim($get_post['range_of_joy_from_reading'] ?? '');
  $form_date_last_read = trim($get_post['date_last_finished_book'] ?? '');
  $form_favorite_book = trim($get_post['favorite_book'] ?? '');
  $form_pages = trim($get_post['prefered_pages'] ?? '');
  $form_medium = trim($get_post['favorite_medium'] ?? '');
  $form_club = trim($get_post['book_club'] ?? '');
  $form_hidden_input = trim($get_post['hidden_input'] ?? $form_hidden_input);

  $selected_genres = $get_post['genre_favorite'] ?? [];
  if (!is_array($selected_genres)) { $selected_genres = []; }
  $form_genre_array = $selected_genres;

  $selected_books = $get_post['books_read'] ?? [];
  if (!is_array($selected_books)) { $selected_books = []; }
  $form_books_read_array = $selected_books;

  $required_missing =
    ($form_username === '') ||
    (!filter_var($form_email_address, FILTER_VALIDATE_EMAIL)) ||
    ($form_number_of_books === '') ||
    ($form_range_of_joy === '') ||
    ($form_date_last_read === '') ||
    ($form_favorite_book === '') ||
    ($form_pages === '') ||
    ($form_medium === '') ||
    ($form_club === '') ||
    ($form_hidden_input === '');

  if ($required_missing) {
    $message = "Validation failed — required fields missing or invalid.";
  }
  else {
    $response = HTTP::curl(
      SURVEY_API_URL,
      'POST',
      $_POST,
      [],
      ['bearer' => SURVEY_API_TOKEN]
    );

    if (!empty($response['error'])) {
      $message = "Could not reach the survey API: " . $response['error'];
    }
    else if ((int)$response['status'] === 201) {
      header("Location: affiliate_thank_you.php");
      exit;
    }
    else {
      $api_msg = $response['json']['message'] ?? $response['body'] ?? 'Unknown API error';
      $message = "API error (status " . (int)$response['status'] . "): " . $api_msg;
    }
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Affiliate Survey Submission</title>
    </head>
    <body>

        <h2>Affiliate Survey Submission</h2>

        <?php if ($message): ?>
        <div style="color:red; font-weight:bold;">
            <?= htmlspecialchars($message) ?>
        </div>
        <br>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

        <input type="hidden" name="task" value="save">

        <p>Enter your name:</p>
        <input type="text" name="username" value="<?= htmlspecialchars($form_username) ?>">

        <p>What is your email address:</p>
        <input type="email" name="email_address" value="<?= htmlspecialchars($form_email_address) ?>">

        <p>How many books did you read last year:</p>
        <input type="number" name="number_of_books_read" value="<?= htmlspecialchars($form_number_of_books) ?>">

        <p>How much do you enjoy reading:</p>
        <input type="range" name="range_of_joy_from_reading" value="<?= htmlspecialchars($form_range_of_joy) ?>">

        <p>When did you finish the last book you read:</p>
        <input type="date" name="date_last_finished_book" value="<?= htmlspecialchars($form_date_last_read) ?>">

        <p>What is your favorite book:</p>
        <textarea name="favorite_book"><?= htmlspecialchars($form_favorite_book) ?></textarea>

        <p>What genres do you usually read?</p>
        <?php
        foreach ($genres as $code => $label) {
            $checked = in_array($code, $form_genre_array) ? "checked" : "";
            echo "<input type='checkbox' name='genre_favorite[]' value='" . htmlspecialchars($code) . "' $checked> " . htmlspecialchars($label) . " <br>";
        }
        ?>

        <p>Please select your favorite page count:</p>
        <?php
        foreach (["100","200","300","400","500"] as $p) {
            $checked = ($form_pages == $p) ? "checked" : "";
            echo "<input type='radio' name='prefered_pages' value='" . htmlspecialchars($p) . "' $checked> " . htmlspecialchars($p) . " <br>";
        }
        ?>

        <p>Please choose your preferred book medium:</p>
        <?php
        foreach ($mediums as $code => $label) {
            $checked = ($form_medium == $code) ? "checked" : "";
            echo "<input type='radio' name='favorite_medium' value='" . htmlspecialchars($code) . "' $checked> " . htmlspecialchars($label) . " <br>";
        }
        ?>

        <p>Have you ever been a member of a book club:</p>
        <?php
            echo lib::menu_from_assoc_array(
            "book_club",
            $club_options,
            "",
            $form_club
            );
        ?>

        <p>Have you read any of my favorite books:</p>
        <?php
            echo lib::menu_from_assoc_array(
            "books_read[]",
            $books,
            "",
            $form_books_read_array,
            "multiple"
            );
        ?>

        <input type="hidden" name="hidden_input" value="<?= htmlspecialchars($form_hidden_input) ?>">

        <br><br>

        <input type="submit" value="Submit">
        <input type="reset" value="Reset">

        <br><br><br>
    </body>
</html>
