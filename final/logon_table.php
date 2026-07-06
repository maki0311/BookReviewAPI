<?
class logon extends data_operations{
    public function __construct(){
        $table = LOG_TABLE;
        $id_field = 'logon_token';
        $id_field_is_ai = false;
        $fields = array(
            'logon_id',
            'logon_created',
            'logon_timestamp',
            'logon_address'
        );
        parent::__construct($table, $id_field, $id_field_is_ai, $fields);
    }
}
?>