<?
class user extends data_operations{
    public function __construct(){
        $table = USER_TABLE;
        $id_field = 'user_id';
        $id_field_is_ai = true;
        $fields = array(
            'user_name',
            'user_email',
            'user_password',
            'user_created',
            'user_ip_address',
            'user_api_token',
            'user_is_admin'
        );
        parent::__construct($table, $id_field, $id_field_is_ai, $fields);
    }

    public function get_user_from_api_token($token){
        if (empty($token)) {
            return -1;
        }

        $sql = "SELECT user_id FROM " . $this->table . " WHERE user_api_token = :token";
        $result = lib::db_query($sql, [':token' => $token]);
        $row = $result->fetch();

        if ($row){
            return $row['user_id'];
        }
        return -1;
    }
}
?>