<?
class api_log extends data_operations{
    public function __construct(){
        $table = API_LOG_TABLE;
        $id_field = 'api_log_id';
        $id_field_is_ai = true;
        $fields = array(
            'api_log_user_id',
            'api_log_form_id',
            'api_log_timestamp',
            'api_log_method',
            'api_log_http_code',
            'api_log_token'
        );
        parent::__construct($table, $id_field, $id_field_is_ai, $fields);
    }
}
?>