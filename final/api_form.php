<?
header('Content-Type: application/json');

require 'init.php';

$auth_header  = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
                ?? (function_exists('getallheaders') ? (getallheaders()['Authorization'] ?? '') : '') ?? '';
$bearer_token = str_replace('Bearer ', '', $auth_header);

$http_code = 400;
$response = ['status' => 'error', 'message' => 'Unknown error'];
$affiliate_user_id = null;
$request_method = $_SERVER['REQUEST_METHOD'];

if (!$bearer_token) {
    $http_code = 401;
    $response = ['status' => 'error', 'message' => 'Unauthorized — Bearer token required'];
} else {
    $user = new User();
    $affiliate_user_id = $user->get_user_from_api_token($bearer_token);
    
    if ($affiliate_user_id === -1) {
        $http_code = 403;
        $response = ['status' => 'error', 'message' => 'Forbidden — invalid token'];
    } else {
        if ($request_method === 'GET') {
            try {
                $sql = "SELECT * FROM " . FORM_TABLE . " ORDER BY form_review_id DESC";
                $result = lib::db_query($sql);
                $forms = $result->fetchAll();
                
                $http_code = 200;
                $response = [
                    'status' => 'success',
                    'count' => count($forms),
                    'data' => $forms
                ];
            } catch (Exception $e) {
                $http_code = 500;
                $response = ['status' => 'error', 'message' => 'Database error'];
            }
            
        } else if ($request_method === 'POST') {

            $form_book_title = $get_post['form_book_title'] ?? '';
            $form_age = $get_post['form_age'] ?? '';
            $age_valid = ctype_digit($form_age) && (int)$form_age > 0;

            if(!$form_book_title || !$age_valid){
                $http_code = 422;
                $response = ['status' => 'error', 'message' => 'Validation failed - required fields are missing or invalid.'];
            } else {
                try {
                    $form_email = $get_post['form_email'] ?? '';
                    $form_rating = $get_post['form_rating'] ?? 5;
                    $form_date_finished = $get_post['form_date_finished'] ?? '';
                    $form_review = $get_post['form_review'] ?? '';
                    $form_likes = isset($get_post['form_likes']) && is_array($get_post['form_likes']) ? implode(',', $get_post['form_likes']) : '';
                    $form_recommendation = $get_post['form_recommendation'] ?? 'No';
                    $form_reading_format = $get_post['form_reading_format'] ?? 'Physical Copy';
                    $form_genre = $get_post['form_genre'] ?? 'Fantasy';
                    $form_source = isset($get_post['form_source']) && is_array($get_post['form_source']) ? implode(',', $get_post['form_source']) : '';
                    $form_hidden_element = $get_post['form_hidden_element'] ?? '3487';

                    $sql = "INSERT INTO " . FORM_TABLE . " (
                        form_book_title,
                        form_email,
                        form_age,
                        form_rating,
                        form_date_finished,
                        form_review,
                        form_likes,
                        form_recommendation,
                        form_reading_format,
                        form_genre,
                        form_source,
                        form_hidden_element
                    ) VALUES (
                        :book_title,
                        :email,
                        :age,
                        :rating,
                        :date_finished,
                        :review,
                        :likes,
                        :recommendation,
                        :reading_format,
                        :genre,
                        :source,
                        :hidden_element
                    )";

                    $placeholders = [
                        ':book_title' => trim($_POST['form_book_title']),
                        ':email' => trim($_POST['form_email']),
                        ':age' => (int)$_POST['form_age'],
                        ':rating' => (int)$_POST['form_rating'],
                        ':date_finished' => $_POST['form_date_finished'],
                        ':review' => trim($_POST['form_review']),
                        ':likes' => $_POST['form_likes'] ?? '',
                        ':recommendation' => $_POST['form_recommendation'] ?? 'No',
                        ':reading_format' => $_POST['form_reading_format'] ?? 'Physical Copy',
                        ':genre' => $_POST['form_genre'] ?? 'Fantasy',
                        ':source' => $_POST['form_source'] ?? '',
                        ':hidden_element' => $_POST['form_hidden_element'] ?? 0
                    ];

                    lib::db_query($sql, $placeholders);
                    $form_id = $pdo->lastInsertId();

                    $log = new api_log();
                    $log->values['api_log_user_id'] = $affiliate_user_id;
                    $log->values['api_log_form_id'] = $form_id;
                    $log->values['api_log_timestamp'] = date('Y-m-d H:i:s');
                    $log->values['api_log_method'] = 'POST';
                    $log->values['api_log_http_code'] = 201;
                    $log->values['api_log_token'] = $bearer_token;
                    $log->save();

                    $http_code = 201;
                    $response = ['status' => 'success', 'message' => 'Form submitted successfully', 'form_id' => $form_id];
                } catch (Exception $e) {
                    $http_code = 500;
                    $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
                }
            }
        } else {
            $http_code = 405;
            $response = ['status' => 'error', 'message' => 'Request Method not allowed'];
        }
    }
}

http_response_code($http_code);
echo json_encode($response);
exit;
?>